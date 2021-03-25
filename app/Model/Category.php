<?php

namespace App\Model;


class Category extends Model
{
    protected $table = 'category';
    protected $primaryKey = 'aid';

    protected $dateFormat = 'U';

    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';

    protected $guarded =[
        'id',
        'id' => 'categoryId',
        'article_count as articleCount',
        'create_time as createTime',
        'update_time as updateTime',
        'id as categoryId',
        'name as categoryName',
        'status',
        'can_del as canDel',
    ];

    // 一对多文章
    function article()
    {
        return $this->hasMany(Article::class,'category_id','id');
    }

}
