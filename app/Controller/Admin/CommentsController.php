<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2020/6/29
 * Time: 10:19
 */

namespace App\Controller\Admin;

use App\Controller\AbstractController;
use App\Model\Comments;
use Hyperf\DbConnection\Db;
use Hyperf\Utils\Collection;

class CommentsController extends AbstractController
{
    function getComments(Comments $comments)
    {
        $articleId = $this->request->query('articleId');
        $data = $comments->getComments($articleId);
        return jsonSuccess('',
            [
                'count' => count($data),
                'list' => $data
            ]);
    }

    function getAllComments()
    {
        $pageSize = $this->request->query('pageSize');

        $data = Comments::query()
            ->leftJoin('article', 'comments.article_id', '=', 'article.id')
            ->select(['comments.*', 'article.title as articleTitle'])
            ->paginate($pageSize);
        $list = [];

        if (!empty($data->items())) {
            foreach ($data->items() as $vo) {
                $list[] = arrayKeyTrans(Collection::make($vo)->toArray(), 'hump');
            }
        }

        $return = [
            'count' => $data->total(),
            'list' => $list,
            'page' => $data->currentPage(),
            'pageSize' => $data->perPage(),
        ];

        return jsonSuccess('', $return);
    }

    function delete()
    {
        $id = $this->request->post('commentsId');
        Db::table('comments')->where('id', $id)->update(['status' => 1, 'delete_time' => time()]);
        return jsonSuccess();
    }
}
