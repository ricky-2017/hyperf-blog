<?php

namespace App\Model;

class Tag extends Model
{
    //
    protected $table = 'tag';
    protected $primaryKey = 'aid';

    protected $dateFormat = 'U';

    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';

    // 多对多文章
    function articles()
    {
        return $this->belongsToMany(Article::class,'article_tag_mapper','tag_id','article_id','id','id');
    }
}
