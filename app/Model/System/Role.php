<?php

namespace App\Model\System;


use App\Model\Model;
use Hyperf\DbConnection\Db;

class Role extends Model
{
    protected $table = 'sys_role';
    protected $primaryKey = 'role_id';

    const CREATED_AT = 'role_create_time';
    const UPDATED_AT = 'role_update_time';

    function replaceRoleRules($id, $ruleIds)
    {
        $roleInfo = $this->find($id);
        Db::beginTransaction();
        RoleRule::where('role_id', $id)
            ->delete();
        RoleRule::create(array_map(function ($v) use ($id, $roleInfo) {
            return [
                'role_rule_group' => $roleInfo['role_group'],
                'role_id' => $id,
                'rule_id' => $v,
            ];
        }, $ruleIds));

        Db::commit();
    }

    function getRoleRulesAttribute($value)
    {
        $rs = RoleRule::with('rule')->where('role_id', $this->role_id)->select();
        $roleRules = array();
        foreach ($rs as $k => $v) {
            $roleRules[$k] = $v['rule']->toArray();
        }
        return $roleRules;
    }

    // 搜索器
    public function searchRoleGroupAttr($query, $value, $data)
    {
        $query->where('role_group', is_array($value) ? 'in' : 'eq', $value);
    }

    public function searchRoleNameAttr($query, $value, $data)
    {
        if (!empty($value)) {
            $query->where('role_name', 'like', '%' . urldecode($value) . '%');
        }
    }
}