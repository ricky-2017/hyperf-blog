<?php

namespace App\Model;


class Category extends Model
{
    protected $table = 'category';
    protected $primaryKey = 'aid';

    protected $dateFormat = 'U';

    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';
    
    // 一对多文章
    function article()
    {
        return $this->hasMany(Article::class, 'category_id', 'id');
    }

}
