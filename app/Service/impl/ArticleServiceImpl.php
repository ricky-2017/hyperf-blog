<?php
/**
 * Created by PhpStorm.
 * User: rubio
 * Date: 2020/8/22
 * Time: 13:24
 */

namespace App\Service\impl;


use App\Model\Article;
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


        $articles = Article::with(['tags','category'])
            ->where('id','!=',-1)
            ->where('status',0)
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
        $tagList = Tag::query()->where('article_count', '>', 0)
                               ->orderByDesc('aid')
                               ->get($field);
        $tagList = Collection::make($tagList)->toArray();
        return ['count' => count($tagList),'list' => arrayKeyTrans($tagList,'hump')];
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
