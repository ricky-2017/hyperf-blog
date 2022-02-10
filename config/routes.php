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

Router::addGroup('/w/', function () {
    // +----------------------------------------------------------------------
    // | 文章模块
    // +----------------------------------------------------------------------
    Router::get('article/list', 'App\Controller\IndexController@index');// 获取文章列表
    Router::get('article/archives', 'App\Controller\IndexController@getArticleArchives');// 获取文章归档列表
    Router::get('article', 'App\Controller\IndexController@getArticle');// 获取文章信息
    Router::get('article/search', 'App\Controller\IndexController@search');// 按文章标题和简介搜索
    Router::get('category/list', 'App\Controller\IndexController@categoryList');//获取分类列表
    Router::get('tag/list', 'App\Controller\IndexController@tagList');//获取标签列表

    // +----------------------------------------------------------------------
    // | 网站配置信息
    // +----------------------------------------------------------------------
    Router::get('getAbout', 'App\Controller\WebConfigController@getAboutMe');  //
    Router::get('getResume', 'App\Controller\WebConfigController@getResume');  //
    Router::get('blogInfo', 'App\Controller\WebConfigController@blogInfo');    //
    Router::get('friends/list', 'App\Controller\WebConfigController@getFriends');// 获取友链列表

    // +----------------------------------------------------------------------
    // | 文章评论
    // +----------------------------------------------------------------------
    Router::get('comments/list', 'App\Controller\IndexController@getComment'); // 评论列表
    Router::post('comments/add', 'App\Controller\IndexController@addComment'); // 添加评论
});

// +----------------------------------------------------------------------
// | 登录模块
// +----------------------------------------------------------------------
Router::addGroup('/a/', function () {
    Router::post('login', 'App\Controller\AdminController@login');
    Router::get('qiniu/token', 'App\Controller\UploadController@getToken');
    Router::post('uploads', 'App\Controller\UploadController@uploads');
    Router::post('qiniu/upToQiniu', 'App\Controller\UploadController@upToQiniu');
});
//}, ['middleware' => [App\Middleware\CorsMiddleware::class]]);

Router::addGroup('/a/', function () {
    // +----------------------------------------------------------------------
    // | 博客配置模块
    // +----------------------------------------------------------------------
    Router::get('webConfig', 'App\Controller\Admin\WebConfigController@getWebConfig');
    Router::post('webConfig/modify', 'App\Controller\Admin\WebConfigController@modify');// 修改博客配置
    Router::get('webConfig/getAbout', 'App\Controller\Admin\WebConfigController@getAboutMe');// 获取 关于我
    Router::post('webConfig/modifyAbout', 'App\Controller\Admin\WebConfigController@modifyAbout');// 修改 关于我
    Router::get('webConfig/getResume', 'App\Controller\Admin\WebConfigController@getResume');// 获取 我的简历
    Router::post('webConfig/modifyResume', 'App\Controller\Admin\WebConfigController@modifyResume');// 修改 我的简历

    // +----------------------------------------------------------------------
    // | 友链模块
    // +----------------------------------------------------------------------
    Router::get('friends/typeList', 'App\Controller\Admin\FriendsController@getFriendsType');
    Router::get('friends/list', 'App\Controller\Admin\FriendsController@getFriendsList');
    Router::post('friends/add', 'App\Controller\Admin\FriendsController@addFriend');
    Router::post('friends/modify', 'App\Controller\Admin\FriendsController@modifyFriend');
    Router::post('friends/delete', 'App\Controller\Admin\FriendsController@delFriend');

    // +----------------------------------------------------------------------
    // | 分类管理模块
    // +----------------------------------------------------------------------
    Router::get('category/get', 'App\Controller\Admin\CategoryController@getCategory');
    Router::get('category/list', 'App\Controller\Admin\CategoryController@list');
    Router::post('category/add', 'App\Controller\Admin\CategoryController@add');
    Router::post('category/modify', 'App\Controller\Admin\CategoryController@modify');
    Router::post('category/delete', 'App\Controller\Admin\CategoryController@delCategory');

    // +----------------------------------------------------------------------
    // | 标签管理
    // +----------------------------------------------------------------------
    Router::get('tag/get', 'App\Controller\Admin\TagController@getTag');
    Router::get('tag/list', 'App\Controller\Admin\TagController@tagList');
    Router::post('tag/add', 'App\Controller\Admin\TagController@addTag');
    Router::post('tag/modify', 'App\Controller\Admin\TagController@modifyTag');
    Router::post('tag/delete', 'App\Controller\Admin\TagController@delTag');

    // +----------------------------------------------------------------------
    // | 文章管理
    // +----------------------------------------------------------------------
    Router::get('article/info', 'App\Controller\Admin\ArticleController@getArticle');
    Router::get('article/list', 'App\Controller\Admin\ArticleController@getArticleList');
    Router::post('article/delete', 'App\Controller\Admin\ArticleController@delete');
    Router::post('article/save', 'App\Controller\Admin\ArticleController@save');
    Router::post('article/publish', 'App\Controller\Admin\ArticleController@publish');
    Router::post('article/modify', 'App\Controller\Admin\ArticleController@modify');

    // +----------------------------------------------------------------------
    // | 评论管理
    // +----------------------------------------------------------------------
    Router::get('comments/list', 'App\Controller\Admin\CommentsController@getComments');
    Router::get('comments/alllist', 'App\Controller\Admin\CommentsController@getAllComments');
    Router::post('comments/add', 'App\Controller\Admin\CommentsController@add');
    Router::post('comments/delete', 'App\Controller\Admin\CommentsController@delete');

    // +----------------------------------------------------------------------
    // | 系统信息
    // +----------------------------------------------------------------------
    Router::get('sys/log', 'App\Controller\Admin\SystemController@getSysLog');
    Router::get('statistics/home', 'App\Controller\Admin\SystemController@getHomeStatistics');
//});
}, ['middleware' => [App\Middleware\JwtAuthMiddleware::class]]);

// 管理后台登录
//Router::addGroup('/', function () {
//    Router::post('system/login', 'App\Controller\System\AuthController@login');
//});

Router::addGroup('/system/', function () {
    Router::get('getSysGroups', 'App\Controller\System\SysGroup@getSysGroups');//获取后台分组标识
    Router::post('login', 'App\Controller\System\LoginController@login');//获取后台分组标识
    Router::post('logout', 'App\Controller\System\LoginController@logout');//退出登录
//    Router::get('system/captcha', 'captcha');
});

Router::addGroup('/system/', function () {
    Router::addGroup('elements', function () {
        Router::get('/getMyButtonsPrivilege', 'App\Controller\System\ElementController@getMyButtonsPrivilege');
        Router::get('/lists', 'App\Controller\System\ElementController@lists');
        Router::get('/listMyTree', 'App\Controller\System\ElementController@listMyTree');
        Router::get('/listTree', 'App\Controller\System\ElementController@listTree');
        Router::get('/get', 'App\Controller\System\ElementController@get');
        Router::post('/post', 'App\Controller\System\ElementController@post');
        Router::post('/put', 'App\Controller\System\ElementController@put');
        Router::post('/patch', 'App\Controller\System\ElementController@patch');
        Router::post('/delete', 'App\Controller\System\ElementController@delete');
    });

    Router::addGroup('rule', function () {
        Router::post('/putStatus', 'App\Controller\System\Rule@putStatus');
        Router::post('/putResources', 'App\Controller\System\Rule@putResources');
        Router::get('/lists', 'App\Controller\System\Rule@lists');
        Router::get('/get', 'App\Controller\System\Rule@get');
        Router::post('/post', 'App\Controller\System\Rule@post');
        Router::post('/put', 'App\Controller\System\Rule@put');
        Router::post('/delete', 'App\Controller\System\Rule@delete');
    });

    Router::addGroup('roles', function () {
        Router::post('/putStatus', 'App\Controller\System\Role@putStatus');
        Router::post('/putRules', 'App\Controller\System\Role@putRules');
        Router::get('/lists', 'App\Controller\System\Role@lists');
        Router::get('/get', 'App\Controller\System\Role@get');
        Router::post('/post', 'App\Controller\System\Role@post');
        Router::post('/put', 'App\Controller\System\Role@put');
        Router::post('/delete', 'App\Controller\System\Role@delete');
    });

    Router::addGroup('users', function () {
        Router::post('/putStatus', 'App\Controller\System\SysUser@putStatus');
        Router::post('/putPassword', 'App\Controller\System\SysUser@putPassword');
        Router::post('/putRoles', 'App\Controller\System\SysUser@putRoles');
        Router::get('/getMyUser', 'App\Controller\System\SysUser@getMyUser');
        Router::get('/lists', 'App\Controller\System\SysUser@lists');
        Router::get('/get', 'App\Controller\System\SysUser@get');
        Router::post('/post', 'App\Controller\System\SysUser@post');
        Router::post('/put', 'App\Controller\System\SysUser@put');
        Router::post('/patch', 'App\Controller\System\SysUser@patch');
        Router::post('/delete', 'App\Controller\System\SysUser@delete');
    });
}, ['middleware' => [App\Middleware\JwtAuthMiddleware::class, App\Middleware\SystemNormalUserPermission::class]]);
//});


//Route::group(['prefix' => 'system/sys_group/'], function () {
//    Route::get('system/getSysGroups', 'getSysGroups');//获取后台分组标识
//})->allowCrossDomain();
//

//Route::group('system', function () {
//    Route::group('elements', function () {
//        Route::get('/getMyButtonsPrivilege', 'getMyButtonsPrivilege');
//        Route::get('/lists', 'lists');
//        Route::get('/listMyTree', 'listMyTree');
//        Route::get('/listTree', 'listTree');
//        Route::get('/get', 'get');
//        Route::post('/post', 'post');
//        Route::post('/put', 'put');
//        Route::post('/patch', 'patch');
//        Route::post('/delete', 'delete');
//    })->prefix('element/');
//
//    Route::group('rules', function () {
//        Route::post('/putStatus', 'putStatus');
//        Route::post('/putResources', 'putResources');
//        Route::get('/lists', 'lists');
//        Route::get('/get', 'get');
//        Route::post('/post', 'post');
//        Route::post('/put', 'put');
//        Route::post('/delete', 'delete');
//    })->prefix('rule/');
//
//    Route::group('roles', function () {
//        Route::post('/putStatus', 'putStatus');
//        Route::post('/putRules', 'putRules');
//        Route::get('/lists', 'lists');
//        Route::get('/get', 'get');
//        Route::post('/post', 'post');
//        Route::post('/put', 'put');
//        Route::post('/delete', 'delete');
//    })->prefix('role/');
//
//    Route::group('users', function () {
//        Route::post('/putStatus', 'putStatus');
//        Route::post('/putPassword', 'putPassword');
//        Route::post('/putRoles', 'putRoles');
//        Route::get('/getMyUser', 'getMyUser');
//        Route::get('/lists', 'lists');
//        Route::get('/get', 'get');
//        Route::post('/post', 'post');
//        Route::post('/put', 'put');
//        Route::post('/patch', 'patch');
//        Route::post('/delete', 'delete');
//    })->prefix('sys_user/');
//
//})->prefix('system/')->allowCrossDomain();




