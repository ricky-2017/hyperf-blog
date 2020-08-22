<?php
/**
 * Created by PhpStorm.
 * User: rubio
 * Date: 2020/8/22
 * Time: 10:22
 */

namespace App\Model;


class ArticleTagMapper extends Model
{
    protected $table ='article_tag_mapper';
    protected $primaryKey = 'id';
    protected $dateFormat = 'U';

    const CREATED_AT = 'create_time';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

}