<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/14
 * Time: 15:18
 */

namespace App\Model\System;


use App\Model\Model;

class UserRole extends Model
{
    protected $table = 'sys_user_role';
    protected $primaryKey = 'user_role_id';
    protected $guarded = ['user_role_id', 'user_role_create_time'];
    const CREATED_AT = 'user_role_create_time';
    const UPDATED_AT = 'user_role_update_time';

    public function role()
    {
        return $this->hasOne(Role::class, 'role_id', 'role_id');
    }
}