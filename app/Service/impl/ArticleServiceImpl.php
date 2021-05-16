<?php
/**
 * Created by PhpStorm.
 * User: rubio
 * Date: 2020/8/22
 * Time: 13:24
 */

namespace App\Service\impl;

use App\Amqp\Producer\CommentProducer;
use App\Constants\ReturnCode;
use App\Dto\CommentDto;
use App\Model\Article;
use App\Model\ArticleTagMapper;
use App\Model\BlogConfig;
use App\Model\Category;
use App\Model\Comments;
use App\Model\Tag;
use App\Service\ArticleService;
use Hyperf\Amqp\Producer;
use Hyperf\DbConnection\Db;
use Hyperf\Logger\LoggerFactory;
use Hyperf\Utils\ApplicationContext;
use Hyperf\Utils\Collection;

class ArticleServiceImpl implements ArticleService
{
    private $logger;

    public function __construct(LoggerFactory $loggerFactory)
    {
        $this->logger = $loggerFactory->get('log', 'default');
    }

    public function list($page, $pageSize, $search = [])
    {
        $query = Article::with(['tags', 'category']);

        $query->where('id', '<>', -1);

        if (isset($search['searchValue'])) {
            $query->where('title', 'like', '%' . $search['searchValue'] . '%');
        }
        if (isset($search['by']) && $search['by'] === 'category' && isset($search['categoryId'])) {
            $query->where('category_id', '=', $search['categoryId']);
        }

        if (isset($search['by']) && $search['by'] == 'tag' && isset($search['tagId'])) {
            $articles = ArticleTagMapper::query()->where('tag_id', $search['tagId'])->get('article_id');
            if ($articles->isNotEmpty()) {
                $articles_ids = array_column($articles->toArray(), 'article_id');
            }
        }

        if (isset($search['by']) && $search['by'] == 'status' && isset($search['status'])) {
            $query->where('status','=', $search['status']);
        } else {
            $query->where('status','=', 0);
        }

        if (isset($articles_ids) && !empty($articles_ids))
            $query->whereIn('id', $articles_ids);

        $articles = $query->orderBy('publish_time', 'desc')
            ->paginate($pageSize);

        $list = $articles->items();

        $result = [
            'page' => $articles->currentPage(),
            'pageSize' => $articles->perPage(),
            'count' => $articles->total(),
            'list' => arrayKeyTrans(Collection::make($list)->toArray(), 'hump')
        ];

        return $result;
    }

    public function archivesList($search = [], $page, $pageSize)
    {
        $result = $this->list($search = [], $page, $pageSize);
        $list = array();

        foreach ($result['list'] as $k => $v) {
            $year = date('Y', $v['publishTime']) . '年';
            if (!isset($list[$year])) {
                $list[$year] = array();
            }
            $month = date('m', $v['publishTime']) . '月';
            if (!isset($list[$year][$month])) {
                $list[$year][$month] = array();
            }
            array_push($list[$year][$month], $v);
        }

        $result['list'] = $list;

        return $result;
    }

    public function tags()
    {
        $field = [
            'id as tagId',
            'name as tagName',
            'create_time as createTime',
            'update_time as updateTime',
            'status'
        ];

        $tags = ArticleTagMapper::query()->groupBy('tag_id')->get('tag_id');

        if ($tags->isNotEmpty()) {
            $tags = array_column($tags->toArray(), 'tag_id');

            $tagList = Tag::query()->where('status', 0)
                ->whereIn('id', $tags)
                ->orderByDesc('aid')
                ->get($field)
                ->map(function ($item, $key) {
                    $item['articleCount'] = Article::query()
                        ->where('category_id', $item['categoryId'])
                        ->where('status', 0)
                        ->count();
                    return $item;
                });

            $tagList = Collection::make($tagList)->toArray();
            return [
                'count' => count($tagList),
                'list' => arrayKeyTrans($tagList, 'hump')
            ];
        } else {
            return [
                'count' => 0,
                'list' => []
            ];
        }

    }

    public function categories()
    {
        $categories = Article::query()->where('status', 0)->groupBy('category_id')->get('category_id');

        if ($categories->isNotEmpty()) {
            $categories = array_column($categories->toArray(), 'category_id');
            $categoryList = Category::query()
                ->whereIn('id', $categories)
                ->orderByDesc('aid')
                ->get([
                    'id as categoryId',
                    'name as categoryName',
                    'create_time as createTime',
                    'update_time as updateTime',
                    'status'
                ])
                ->map(function ($item, $key) {
                    $item['articleCount'] = Article::query()
                        ->where('category_id', $item['categoryId'])
                        ->where('status', 0)
                        ->count();
                    return $item;
                });
            $categoryList = Collection::make($categoryList)->toArray();
            return [
                'count' => count($categoryList),
                'list' => arrayKeyTrans($categoryList, 'hump')
            ];
        } else {
            return ['count' => 0, 'list' => []];
        }

    }

    public function getArticle($id)
    {
        $article_info = Article::with(['tags', 'category'])->where('id', $id)->first();

        if (empty($article_info))
            bizException(ReturnCode::DATA_NOT_FOUND);

        $result = arrayKeyTrans($article_info->toArray(), 'hump');

        $qrCode = (new BlogConfig())->getQrCode();

        $result['qrcode'] = $qrCode;

        $pn = (new Article)->getPreNextArticle($result['publishTime']);

        $result['pn'] = $pn;
        // 阅读数自增
        Article::query()->where('id', $id)->increment('pageview');

        return arrayKeyTrans($result, 'hump');
    }

    public function saveArticle($data)
    {
        try {
            DB::beginTransaction();
            $article_id = isset($data['id']) ? $data['id'] : create_id();
            $common_part = [
                'title' => $data['title'],
                'content' => $data['content'],
                'category_id' => isset($data['category']['id']) ? $data['category']['id'] : null,
                'html_content' => $data['htmlContent'],
                'cover' => $data['cover'],
                'sub_message' => $data['subMessage'],
                'is_encrypt' => $data['isEncrypt'],
            ];

            // publish使用
            if (isset($data['status'])) {
                $common_part['status'] = $data['status'];
                if ($data['status'] == 0) {
                    $common_part['publish_time'] = time();
                }
            }

            if (!isset($data['id'])) {
                $insert = [
                    'id' => $article_id,
                    'create_time' => time(),
                ];

                DB::table('article')->insert(array_merge($insert, $common_part));

            } else {

                $update = [
                    'update_time' => time(),
                ];

                DB::table('article')->where('id', $article_id)->update(array_merge($update, $common_part));
                // 标签处理
                DB::table('article_tag_mapper')->where('article_id', '=', $article_id)->delete();
            }

            // 标签处理
            $mapper = [];
            if (isset($data['tags']) && !empty($data['tags'])) {
                foreach ($data['tags'] as $vo) {
                    if (isset($vo['id'])) {
                        $mapper[] = ['article_id' => $article_id, 'tag_id' => $vo['id'], 'create_time' => time()];
                    } else {
                        // 新插入的标签
                        if (isset($vo['name'])) {
                            $id = create_id();

                            $tag = new Tag();
                            $tag->name = $vo['name'];
                            $tag->id = $id;
                            $tag->save();

                            $mapper[] = ['article_id' => $article_id, 'tag_id' => $id, 'create_time' => time()];
                        }
                    }
                }
                DB::table('article_tag_mapper')->insert($mapper);
            }

            DB::commit();
            return $article_id;
        } catch (\Exception $e) {
            DB::rollBack();
            bizException(ReturnCode::DATA_CONSTRAINT_ERROR, '保存失败' . $e->getMessage());
        }
    }

    public function addComment(CommentDto $commentDto)
    {
        $message = new CommentProducer(CommentDto::fromRequest()->toArray());
        $producer = ApplicationContext::getContainer()->get(Producer::class);
        return $producer->produce($message);
    }

    public function getComment($article_id)
    {
        // 检测文章是否存在
        $is = Article::query()->where('aid', '=', $article_id)->exists();
        if (!$is) {
            bizException(ReturnCode::DATA_NOT_FOUND, '文章不存在');
        }

        $list = (new Comments())->getComments($article_id);

        return [
            'count' => count($list),
            'list' => $list
        ];
    }
}
