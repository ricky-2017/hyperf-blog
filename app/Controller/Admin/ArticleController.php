<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2020/6/29
 * Time: 10:16
 */

namespace App\Controller\Admin;

use App\Controller\AbstractController;
use App\Model\Article;
use App\Service\ArticleService;
use Hyperf\DbConnection\Db;

class ArticleController extends AbstractController
{
    function getArticle()
    {
        $articleId = $this->request->query('id');

        $article_info = Article::with(['tags','category'])->where('id',$articleId)->first()->toArray();

        $result = [
          'article' => $article_info,
          'category'=> $article_info['category'],
          'tags'    => $article_info['tags'],

        ];

        return jsonSuccess('success',arrayKeyTrans($result,'hump'));
    }

    function getArticleList(ArticleService $articleService)
    {
        $page = $this->request->query('page');
        $pageSize = $this->request->query('pageSize');

        $search = $this->request->all();

        $result = $articleService->list($search, $page, $pageSize );

        return jsonSuccess('success',$result);

    }

    function delete()
    {
        $article_id = $this->request->post('id');
        Db::table('article')->where('id',$article_id)->update(['status'=>1]);

        return jsonSuccess();
    }

    function modify(ArticleService $articleService)
    {
        $data = $this->request->all();

        $article_id = $articleService->saveArticle($data);

        return jsonSuccess('success',$article_id);
    }

    function publish(ArticleService $articleService)
    {
        $data = $this->request->all();
        $data['status'] = 0;
        $data['publish_time'] = time();

        $article_id = $articleService->saveArticle($data);

        return jsonSuccess('success',$article_id);
    }

    function save(ArticleService $articleService)
    {
        $data = $this->request->all();

        $article_id = $articleService->saveArticle($data);

        return jsonSuccess('success',$article_id);

    }


}
