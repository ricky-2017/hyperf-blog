<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/9
 * Time: 18:09
 */

namespace App\Model\System;


use App\Model\Model;

class ElementApi extends Model
{
    protected $table = 'sys_element_api';
    protected $primaryKey = "api_id";
    protected $guarded = ['sys_element_api'];
//    public function resource()
//    {
//        return $this->morphOne(RuleResource::class, 'resource');
//    }

}