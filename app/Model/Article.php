<?php

namespace App\Model;

use App\Constants\ReturnCode;
use Hyperf\DbConnection\Db;
use Hyperf\Utils\Collection;

class Article extends Model
{
    protected $table = 'article';
    protected $primaryKey = 'aid';
    protected $dateFormat = 'U';

    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    // 一对一分类
    public function category()
    {
        return $this->hasOne(Category::Class, 'id', 'category_id');
    }

    // 多对多标签
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'article_tag_mapper', 'article_id', 'tag_id', 'id', 'id');
    }

    /**
     * 获取文章的分类、标签信息
     */
    public function getArticleInfo($articleId)
    {
        $field = ['t.*',
            'c.id as category_id',
            'c.name as category_name',
        ];

        $article = DB::table('article', 't')->leftJoin('category as c', 't.category_id', '=', 'c.id')
            ->where('t.id', $articleId)
            ->select($field)
            ->get()->each(function ($item, $key) {
                $item->tags = DB::table('article_tag_mapper', 't')->where('t.article_id', $item->id)
                    ->join('tag', 't.tag_id', '=', 'tag.id')
                    ->select('tag.id', 'tag.name')->get()->map(function ($value) {
                        return (array)$value;
                    });
                return (array)$item;
            })->toArray();

        return $article[0];
    }

    // 根据ID获取
    public function getArticleById($articleId)
    {
        $tags = DB::table('article_tag_mapper')
            ->where('article_tag_mapper.article_id', '=', $articleId)
            ->select('tag.id', 'tag.name')
            ->join('tag', 'tag.id', '=', 'article_tag_mapper.tag_id')
            ->get();
        $article = DB::table('article')
            ->where('id', '=', $articleId)
            ->get();
        $article = (array)$article[0];

        $category = DB::table('category')
            ->where('id', $article['category_id'])
            ->select('id', 'name')
            ->get();

        $result = array(
            'article' => arrayKeyTrans($article),
            'category' => $category[0] ?? [],
            'tags' => $tags
        );

        return $result;
    }


    /**
     * 获取文章
     */
    public function get_article($articleId)
    {
        $article = DB::table('article')
            ->where('id', $articleId)
            ->get();
        $article = (array)$article[0];

        return $article;
    }

    /**
     * 获取文章列表
     */
    public function getArticles($page, $pageSize)
    {
        $field = ['id',
            'title',
            'cover',
            'sub_message as subMessage',
            'pageview',
            'status',
            'category_id as categoryId',
            'is_encrypt as isEncrypt',
            'publish_time as publishTime',
            'create_time as createTime',
            'update_time as updateTime',
            'delete_time as deleteTime'];

        $articles = DB::table('article')
            ->where('article.id', '!=', '-1')
            ->where('status', '=', '0')
            ->orderBy('publish_time', 'desc')
            ->select($field)->paginate();

        $items = Collection::make($articles->items())->toArray();

        $list = [];
        foreach ($items as &$item) {
            $item = Collection::make($item)->toArray();
            $temp = $this->getArticleAppend($item['id']);
            $category = ['id' => $temp->category_id, 'name' => $temp->category_name];
            $tags = $temp->tags;
            $list[] = ['article' => $item, 'category' => $category, 'tags' => $tags];
        }

        $data = array(
            'page' => $articles->currentPage(),
            'pageSize' => $articles->perPage(),
            'count' => count($articles->items()),
            'list' => $list
        );

        return $data;
    }


    private function getArticleAppend($articleId)
    {
        $field = ['t.*',
            'c.id as category_id',
            'c.name as category_name',
        ];

        $article = DB::table('article as t')->leftJoin('category as c', 't.category_id', '=', 'c.id')
            ->where('t.id', $articleId)
            ->select($field)
            ->get()->each(function ($item, $key) {
                $item->tags = DB::table('article_tag_mapper as t')->where('t.article_id', $item->id)
                    ->join('tag', 't.tag_id', '=', 'tag.id')
                    ->select('tag.id', 'tag.name')->get()->map(function ($value) {
                        return (array)$value;
                    });
                return (array)$item;
            })->toArray();


        return $article[0];
    }

    /**
     * 通过分类获取文章列表
     */
    public function getArticlesByCategory($categoryId, $page, $pageSize)
    {
        $field = 'id, title, cover, sub_message as subMessage, pageview, status, category_id as categoryId, is_encrypt as isEncrypt, publish_time as publishTime, create_time as createTime, update_time as updateTime, delete_time as deleteTime';
        $field = explode(', ', $field);
        $articles = DB::table('article')->select($field)
            ->orderByDesc('publish_time')
            ->where('status', '=', '0')
            ->where('article.id', '!=', '-1')
            ->where('category_id', '=', $categoryId)->paginate();

        $items = Collection::make($articles->items())->toArray();

        $list = [];
        foreach ($items as &$item) {
            $item = Collection::make($item)->toArray();
            $temp = $this->getArticleAppend($item['id']);

            $category = ['id' => $temp->category_id, 'name' => $temp->category_name];
            $tags = $temp->tags;
            $list[] = ['article' => $item, 'category' => $category, 'tags' => $tags];
        }

        $data = array(
            'page' => $articles->currentPage(),
            'pageSize' => $articles->perPage(),
            'count' => $articles->total(),
            'list' => $list
        );

        return $data;
    }

    /**
     * 通过标签获取文章列表
     */
    public function getArticlesByTag($tagId, $page, $pageSize)
    {
        $field = 'article.id as id, title, cover, sub_message as subMessage, pageview, article.status as status, category_id as categoryId, is_encrypt as isEncrypt, article.publish_time as publishTime, article.create_time as createTime, article.update_time as updateTime, article.delete_time as deleteTime';
        $field = explode(', ', $field);

        $articles = Db::table('article')->select($field)
            ->orderByDesc('article.publish_time')
            ->leftJoin('article_tag_mapper', 'article_tag_mapper.article_id', '=', 'article.id')
            ->where('article_tag_mapper.tag_id', '=', $tagId)
            ->where('article.id', '!=', '-1')
            ->where('article.status', '=', '0')->paginate();

        $items = Collection::make($articles->items())->toArray();

        $list = [];
        foreach ($items as &$item) {
            $item = Collection::make($item)->toArray();
            $temp = $this->getArticleAppend($item['id']);

            $category = ['id' => $temp->category_id, 'name' => $temp->category_name];
            $tags = $temp->tags;
            $list[] = ['article' => $item, 'category' => $category, 'tags' => $tags];
        }

        $data = array(
            'page' => $articles->currentPage(),
            'pageSize' => $articles->perPage(),
            'count' => $articles->total(),
            'list' => $list
        );

        return $data;
    }

    public function getPreNextArticle($publishTime)
    {
        $pre = Db::table('article')
            ->select('id', 'title')
            ->where('status', '0')
            ->where('publish_time', '>', $publishTime)
            ->orderBy('publish_time', 'asc')
            ->first();

        $next = Db::table('article')
            ->select('id', 'title')
            ->where('status', '0')
            ->where('publish_time', '<', $publishTime)
            ->orderBy('publish_time', 'desc')
            ->first();

        $result = array(
            'pre' => $pre,
            'next' => $next
        );

        return $result;
    }

    /**
     * 按文章标题和简介搜索
     */
    public function search($searchValue, $page, $pageSize)
    {
        $field = [
            'id',
            'title',
            'cover',
            'sub_message as subMessage',
            'pageview',
            'status',
            'category_id as categoryId',
            'is_encrypt as isEncrypt',
            'publish_time as publishTime',
            'create_time as createTime',
            'update_time as updateTime',
            'delete_time as deleteTime'
        ];
        $article = Db::table('article')
            ->orderByDesc('publish_time')
            ->where('status', '=', '0')
            ->where('article.id', '!=', '-1')
            ->where('title', 'like', '%' . $searchValue . '%')
            ->select($field)
            ->paginate();
        $list = Collection::make($article->items())->toArray();

        $result = array();
        foreach ($list as $k => $v) {
            array_push($result, $this->getArticleById($v->id));
        }

        $data = array(
            'page' => $article->currentPage(),
            'pageSize' => $pageSize,
            'count' => $article->total(),
            'list' => $result
        );

        return $data;
    }

    // 管理后台列表页
    function sysList($page, $pageSize, $search = [])
    {
        $field = 'article.id as id, title, cover, sub_message as subMessage, pageview, article.status as status, category_id as categoryId, is_encrypt as isEncrypt, article.publish_time as publishTime, article.create_time as createTime, article.update_time as updateTime, article.delete_time as deleteTime, category.name as categoryName';
        $field = explode(', ', $field);

        $map = [];
        $map[] = ['article.id', '!=', -1];

        if (isset($search['status']) && is_numeric($search['status']))
            $map[] = ['article.status', '=', $search['status']];

        if (isset($search['tagId']) && is_numeric($search['tagId']))
            $map[] = ['article_tag_mapper.tag_id', '=', $search['tagId']];

        if (isset($search['categoryId']) && is_numeric($search['categoryId']))
            $map[] = ['article.category_id', '=', $search['categoryId']];

        $articles = DB::table('article')
            ->orderByDesc('article.publish_time')
            ->leftJoin('article_tag_mapper', 'article_tag_mapper.article_id', '=', 'article.id')
            ->leftJoin('category', 'category.id', '=', 'article.category_id')
            ->where($map)
            ->groupBy('article.id')
            ->select($field)
            ->paginate($pageSize, $field, 'page', $page);

        $items = Collection::make($articles->items())->toArray();

        $list = [];
        foreach ($items as $item) {
            $item = Collection::make($item)->toArray();
            $list[] = $item;
        }


        $data = array(
            'page' => $articles->currentPage(),
            'pageSize' => $articles->perPage(),
            'count' => $articles->total(),
            'list' => $list
        );

        return $data;
    }

    public function saveArticle($data)
    {
        try {
            Db::beginTransaction();
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
                    $data['publish_time'] = time();
                }
            }

            if (!isset($data['id'])) {
                $insert = [
                    'id' => $article_id,
                    'create_time' => time(),
                ];

                Db::table('article')->insert(array_merge($insert, $common_part));

            } else {

                $update = [
                    'update_time' => time(),
                ];

                Db::table('article')->where('id', $article_id)->update(array_merge($update, $common_part));
                // 标签处理
                Db::table('article_tag_mapper')->where('article_id', '=', $article_id)->delete();
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
                            // 文章标签
                            $id = create_id();

                            $tag = new Tag();
                            $tag->id = $id;
                            $tag->article_id = $article_id;
                            $tag->save();

                            $mapper[] = ['article_id' => $article_id, 'tag_id' => $id, 'create_time' => time()];
                        }
                    }


                }
                Db::table('article_tag_mapper')->insert($mapper);
            }


            Db::commit();
            return $article_id;

        } catch (\Exception $e) {
            Db::rollBack();
            bizException(ReturnCode::DATA_CONSTRAINT_ERROR, '保存失败' . $e->getMessage());
        }
//        save_system_log('更新了文章'.$article_id,$_SERVER['REMOTE_ADDR']);
    }
}
