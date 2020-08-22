<?php
/**
 * Created by PhpStorm.
 * User: rubio
 * Date: 2020/8/22
 * Time: 13:24
 */

namespace App\Service\impl;


use App\Model\Article;
use App\Service\ArticleService;

class ArticleServiceImpl implements ArticleService
{
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
            'list'    => arrayKeyTrans($list,'hump')
        ];

        return $result;
    }
}