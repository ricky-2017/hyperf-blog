<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
use Hyperf\HttpServer\Router\Router;

Router::addRoute(['GET', 'POST', 'HEAD'], '/', 'App\Controller\IndexController@index');

//Router::addRoute(['GET', 'POST', 'HEAD'], '/w/article/list', 'App\Controller\IndexController@index');

//Router::get('w/article/list', 'App\Controller\IndexController@index');// 获取文章列表 yes
//
Router::addGroup('/w/',function() {
    Router::get('article/list', 'App\Controller\IndexController@index');// 获取文章列表 yes
    Router::get('article/archives', 'App\Controller\IndexController@getArticleArchives');// 获取文章归档列表 yes
    Router::get('article', 'App\Controller\IndexController@getArticle');// 获取文章信息 yes
    Router::get('article/search', 'App\Controller\IndexController@search');// 按文章标题和简介搜索 yes

    Router::get('category/list','App\Controller\IndexController@categoryList');//获取分类列表 yes
    Router::get('tag/list','App\Controller\IndexController@tagList');//获取标签列表 yes

    // 网站配置信息
    Router::get('getAbout','App\Controller\WebConfig@getAboutMe');  //yes
    Router::get('getResume','App\Controller\WebConfig@getResume');  //yes
    Router::get('blogInfo','App\Controller\WebConfig@blogInfo');    //yse
    Router::get('friends/list','App\Controller\WebConfig@getFriends');// 获取友链列表

    // 获取文章评论列表
    Router::get('comments/list','App\Controller\ArticleController@getComments'); // 评论列表 yse
    Router::post('comments/add','App\Controller\ArticleController@addComments'); // 添加评论 yse
});
