<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/14
 * Time: 15:17
 */

namespace App\Model\System;


use App\Model\Model;

class RoleRule extends Model
{
    protected $table = 'sys_role_rule';
    protected $primaryKey = 'role_rule_id';
//    protected $readonly = ['role_rule_id', 'role_rule_create_time'];
    const CREATED_AT = 'role_rule_create_time';
    const UPDATED_AT = 'role_rule_update_time';

    public function rule()
    {
        return $this->hasOne('Rule', 'rule_id', 'rule_id');
    }
}