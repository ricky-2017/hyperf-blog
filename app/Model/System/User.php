<?php

namespace App\Model\System;

use App\Model\Model;
use Hyperf\DbConnection\Db;


class User extends Model
{
    protected $table = 'sys_user';
    protected $primaryKey = 'user_id';
    protected $hidden = ['user_password', 'user_token_version'];
    protected $guarded = ['user_id', 'user_create_time'];
    const CREATED_AT = 'user_create_time';
    const UPDATED_AT = 'user_update_time';

//    function searchUserNameAttr($query, $value, $data)
//    {
//        $query->where("user_name", "like", "%$value%");
//    }
//
//    function searchUserNicknameAttr($query, $value, $data)
//    {
//        $query->where("user_nickname", "like", "%$value%");
//    }
//
//    function searchUserPhoneAttr($query, $value, $data)
//    {
//        $query->where("user_phone", "like", "%$value%");
//    }

    function relationDelete($id)
    {
        Db::beginTransaction();
        $this->where('user_id', $id)->delete();
        UserRole::where('user_id', $id)->delete();
        Db::commit();
    }

    public function replaceRoles($id, $roleIds)
    {
        Db::beginTransaction();
        UserRole::where('user_id', $id)
            ->delete();

        array_map(function ($v) use ($id) {
            UserRole::create([
                'user_id' => $id,
                'role_id' => $v,
            ]);
        }, $roleIds);

        Db::commit();
    }

    public function getUserRolesAttribute($value)
    {
        $rs = UserRole::with('role')->where('user_id', $this->user_id)->get();
        $userRoles = array();
        foreach ($rs->toArray() as $k => $v) {
            $userRoles[$k] = $v['role'];
        }
        return $userRoles;
    }
}