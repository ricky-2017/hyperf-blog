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


Router::addGroup('/w/',function() {
    Router::get('article/list', 'App\Controller\IndexController@index');// 获取文章列表 yes
    Router::get('article/archives', 'App\Controller\IndexController@getArticleArchives');// 获取文章归档列表 yes
    Router::get('article', 'App\Controller\IndexController@getArticle');// 获取文章信息 yes
    Router::get('article/search', 'App\Controller\IndexController@search');// 按文章标题和简介搜索 yes

    Router::get('category/list','App\Controller\IndexController@categoryList');//获取分类列表 yes
    Router::get('tag/list','App\Controller\IndexController@tagList');//获取标签列表 yes

    // 网站配置信息
    Router::get('getAbout','App\Controller\WebConfigController@getAboutMe');  //yes
    Router::get('getResume','App\Controller\WebConfigController@getResume');  //yes
    Router::get('blogInfo','App\Controller\WebConfigController@blogInfo');    //yse
    Router::get('friends/list','App\Controller\WebConfigController@getFriends');// 获取友链列表

    // 获取文章评论列表
    Router::get('comments/list','App\Controller\ArticleController@getComments'); // 评论列表 yse
    Router::post('comments/add','App\Controller\ArticleController@addComments'); // 添加评论 yse
});

Router::addGroup('/a/',function (){
    Router::post('login', 'App\Controller\AdminController@login');// 登录
    Router::get('qiniu/token', 'App\Controller\UploadController@getToken');//
    Router::post('uploads', 'App\Controller\UploadController@uploads');//
    Router::post('qiniu/upToQiniu', 'App\Controller\UploadController@upToQiniu');//
});

Router::addGroup('/a/',function() {
    Router::get('webConfig', 'App\Controller\Admin\WebConfigController@getWebConfig');
    Router::post('webConfig/modify', 'App\Controller\Admin\WebConfigController@modify');// 修改博客配置
    Router::get('webConfig/getAbout', 'App\Controller\Admin\WebConfigController@getAboutMe');// 获取 关于我
    Router::post('webConfig/modifyAbout', 'App\Controller\Admin\WebConfigController@modifyAbout');// 修改 关于我
    Router::get('webConfig/getResume', 'App\Controller\Admin\WebConfigController@getResume');// 获取 我的简历
    Router::post('webConfig/modifyResume', 'App\Controller\Admin\WebConfigController@modifyResume');// 修改 我的简历

    // 友链
    Router::get('friends/typeList', 'App\Controller\Admin\FriendsController@getFriendsType');
    Router::get('friends/list', 'App\Controller\Admin\FriendsController@getFriendsList');
    Router::post('friends/add', 'App\Controller\Admin\FriendsController@addFriend');
    Router::post('friends/modify', 'App\Controller\Admin\FriendsController@modifyFriend');
    Router::post('friends/delete', 'App\Controller\Admin\FriendsController@delFriend');

    // 分类管理
    Router::get('category/get', 'App\Controller\Admin\CategoryController@getCategory');
    Router::get('category/list', 'App\Controller\Admin\CategoryController@list');
    Router::post('category/add', 'App\Controller\Admin\CategoryController@add');
    Router::post('category/modify', 'App\Controller\Admin\CategoryController@modify');
    Router::post('category/delete', 'App\Controller\Admin\CategoryController@delCategory');

    // 标签管理
    Router::get('tag/get', 'App\Controller\Admin\TagController@getTag');
    Router::get('tag/list', 'App\Controller\Admin\TagController@tagList');
    Router::post('tag/add', 'App\Controller\Admin\TagController@addTag');
    Router::post('tag/modify', 'App\Controller\Admin\TagController@modifyTag');
    Router::post('tag/delete', 'App\Controller\Admin\TagController@delTag');

    // 文章管理
    Router::get('article/info', 'App\Controller\Admin\ArticleController@getArticle');
    Router::get('article/list', 'App\Controller\Admin\ArticleController@getArticleList');
    Router::post('article/delete', 'App\Controller\Admin\ArticleController@delete');
    Router::post('article/save', 'App\Controller\Admin\ArticleController@save');
    Router::post('article/publish', 'App\Controller\Admin\ArticleController@publish');
    Router::post('article/modify', 'App\Controller\Admin\ArticleController@modify');

    // 评论管理
    Router::get('comments/list', 'App\Controller\Admin\CommentsController@getComments');
    Router::get('comments/alllist', 'App\Controller\Admin\CommentsController@getAllComments');
    Router::post('comments/add', 'App\Controller\Admin\CommentsController@add');
    Router::post('comments/delete', 'App\Controller\Admin\CommentsController@delete');

    // 系统信息
    Router::get('sys/log', 'App\Controller\Admin\SystemController@getSysLog');
    Router::get('statistics/home', 'App\Controller\Admin\SystemController@getHomeStatistics');
//});
}, ['middleware' => [App\Middleware\JwtAuthMiddleware::class]]);



