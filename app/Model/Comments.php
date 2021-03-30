<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2020/5/8
 * Time: 10:06
 */

namespace App\Model;

use Hyperf\DbConnection\Db;
use Hyperf\Utils\Collection;

class Comments extends Model
{
    protected $table ='comments';
    protected $primaryKey = 'id';
    protected $dateFormat = 'U';

    const CREATED_AT = 'create_time';

    protected $casts = [
        'create_time' => 'timestamp'
    ];


    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }


    // 评论列表
    public function getComments($articleId)
    {
        $field = ['id',
                'parent_id as parentId',
                'article_id as articleId',
                'reply_id as replyId',
                'name',
                'content',
                'create_time as createTime',
                'is_author as isAuthor'];
        $list = Db::table('comments')
            ->orderByDesc('create_time')
            ->where('status','=','0')
            ->where('article_id','=', $articleId)
            ->where('parent_id','=','0')
            ->select($field)
            ->get();

        $list = Collection::make($list)->toArray();

        foreach($list as $k => $v) {
            $list[$k] = Collection::make($list[$k])->toArray();

            $field = ['id',
                'parent_id as parentId',
                'article_id as articleId',
                'reply_id as replyId',
                'name',
                'content',
                'create_time as createTime',
                'is_author as isAuthor'];
            $children = DB::table('comments')
                ->orderBy('create_time')
                ->where('status','=','0')
                ->where('article_id','=', $articleId)
                ->where('parent_id','=', $list[$k]['id'])
                ->select($field)
                ->get();

            $list[$k]['children'] = $children;
        }
        return $list;
    }
}