<?php
/**
 * Created by PhpStorm.
 * User: rubio
 * Date: 2020/8/22
 * Time: 13:24
 */

namespace App\Service\impl;


use App\Model\Article;
use App\Model\ArticleTagMapper;
use App\Model\Category;
use App\Model\Tag;
use App\Service\ArticleService;
use Hyperf\Logger\LoggerFactory;
use Hyperf\Utils\Collection;

class ArticleServiceImpl implements ArticleService
{
    private $logger;

    public function __construct(LoggerFactory $loggerFactory)
    {
        $this->logger = $loggerFactory->get('log', 'default');
    }

    public function list($search = [], $page, $pageSize)
    {
        $where = [];

        $where[] = ['id', '<>', -1];
        $where[] = ['status', '=' , 0];
        if(isset($search['searchValue']))
        {
            $where[] = ['title','like','%'.$search['searchValue'].'%'];
        }
        if(isset($search['by']) && $search['by'] == 'category' && isset($search['categoryId']))
        {
            $where[] = ['category_id','eq',$search['categoryId']];
        }

        if(isset($search['by']) && $search['by'] == 'tag' && isset($search['tagId']))
        {
            $articles = ArticleTagMapper::query()->where('tag_id',$search['tagId'])->get('article_id');
            if($articles->isNotEmpty())
            {
                $articles_ids = array_column($articles->toArray(),'article_id');
                // TODO 查看查询出错原因
                $where[] = ['id','in',$articles_ids];
            }
        }

        $articles = Article::with(['tags','category'])
            ->where($where)
            ->orderBy('publish_time', 'desc')
            ->paginate($pageSize);

        $list = $articles->items();

        $result = [
            'page'    => $articles->currentPage(),
            'pageSize'=> $articles->perPage(),
            'count'   => $articles->total(),
            'list'    => arrayKeyTrans(Collection::make($list)->toArray(),'hump')
        ];

        return $result;
    }

    public function archivesList($search = [], $page, $pageSize)
    {
        $result = $this->list($search = [], $page, $pageSize);
        $list = array();

        foreach($result['list'] as $k => $v) {
            $year = date('Y', $v['publishTime']).'年';
            if (!isset($list[$year])) {
                $list[$year] = array();
            }
            $month = date('m', $v['publishTime']).'月';
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
            'status',
            'article_count as articleCount'
        ];
        $tags = ArticleTagMapper::query()->groupBy('tag_id')->get('tag_id');
        if($tags->isNotEmpty())
        {
            $tags = array_column($tags->toArray(),'tag_id');

            $tagList = Tag::query()->where('status', 0)
                ->whereIn('id', $tags)
                ->orderByDesc('aid')
                ->get($field);
            $tagList = Collection::make($tagList)->toArray();
            return ['count' => count($tagList),'list' => arrayKeyTrans($tagList,'hump')];
        }else{
            return ['count'=>0,'list'=>[]];
        }

    }

    public function categories()
    {
        $field = ['id as categoryId',
            'name as categoryName',
            'create_time as createTime',
            'update_time as updateTime',
            'status',
            'article_count as articleCount'];
        $categoryList = Category::query()
            ->where('article_count', '>', 0)
            ->orderByDesc('aid')
            ->get($field);
        $categoryList = Collection::make($categoryList)->toArray();
        return ['count' => count($categoryList),'list' => arrayKeyTrans($categoryList,'hump')];
    }
}
