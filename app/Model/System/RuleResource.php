<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/14
 * Time: 15:17
 */

namespace app\Model\system;


use App\Model\Model;

class RuleResource extends Model
{
    protected $table = 'sys_rule_resource';
    protected $primaryKey = 'rule_resource_id';
//    protected $readonly = ['rule_resource_id', 'rule_resource_create_time'];
    const CREATED_AT = 'rule_resource_create_time';
    const UPDATED_AT = 'rule_resource_update_time';

    public function resource()
    {
        return $this->morphTo('resource', ['ELEMENT' => 'Element', 'API' => 'Api']);
    }
}