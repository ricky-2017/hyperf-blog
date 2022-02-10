<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2020/6/29
 * Time: 10:19
 */

namespace App\Controller\Admin;

use App\Controller\AbstractController;
use App\Model\SysLog;
use Hyperf\DbConnection\Db;

class SystemController extends AbstractController
{
    function getSysLog()
    {
        $page = $this->request->query('page');
        $pageSize = $this->request->query('pageSize');
        $data = SysLog::query()->orderByDesc('time')->paginate($pageSize);


        $return = [
            'page' => $data->currentPage(),
            'pageSize' => $pageSize,
            'count' => $data->total(),
            'list' => $data->items(),
        ];
        return jsonSuccess($return);
    }

    function getHomeStatistics()
    {
        $publish_count = DB::table('article')->where('status', 0)->count();
        $drafts_count = DB::table('article')->where('status', 2)->count();
        $deleted_count = DB::table('article')->where('status', 1)->count();
        $category_count = DB::table('category')->count();
        $tag_count = DB::table('tag')->count();
        $comments_count = DB::table('comments')->count();


        $result = array(
            'publishCount' => $publish_count,
            'draftsCount' => $drafts_count,
            'deletedCount' => $deleted_count,
            'categoryCount' => $category_count,
            'tagCount' => $tag_count,
            'commentsCount' => $comments_count
        );

        return jsonSuccess($result);
    }
}
