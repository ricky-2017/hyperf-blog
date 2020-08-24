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
namespace App\Controller;

use App\Service\ArticleService;
use Hyperf\HttpServer\Request;


class IndexController extends AbstractController
{
    public function index(Request $request,ArticleService $articleService)
    {
        $result = $articleService->list($request->query(),$request->query('page'),$request->query('pageSize'));

        return jsonSuccess('',$result);
    }

    public function getArticleArchives(Request $request,ArticleService $articleService)
    {

        $result = $articleService->archivesList($request->query(),$request->query('page'),$request->query('pageSize'));

        return jsonSuccess('success',$result);
    }

    // tags
    public function tagList(ArticleService $articleService)
    {
        return jsonSuccess('success',$articleService->tags());
    }

    // category
    public function categoryList(ArticleService $articleService)
    {
        return jsonSuccess('success',$articleService->categories());
    }

    public function getArticle(Request $request,ArticleService $articleService)
    {
        return jsonSuccess('success',$articleService->getArticle($request->query('id')));
    }
}
