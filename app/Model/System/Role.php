<?php

namespace App\Model\System;


use App\Constants\ReturnCode;
use App\Model\Model;
use Hyperf\DbConnection\Db;

class Role extends Model
{
    protected $table = 'sys_role';
    protected $primaryKey = 'role_id';

    protected $guarded = ['role_id', 'role_create_time'];

    const CREATED_AT = 'role_create_time';
    const UPDATED_AT = 'role_update_time';

    function replaceRoleRules($id, $ruleIds)
    {
        $roleInfo = $this->find($id);
        try {
            Db::beginTransaction();
            RoleRule::where('role_id', $id)
                ->delete();

            if (!empty($ruleIds)) {
                foreach ($ruleIds as $rule_id) {
                    RoleRule::create([
                        'role_rule_group' => $roleInfo['role_group'],
                        'role_id' => $id,
                        'rule_id' => $rule_id,
                    ]);
                }
            }
            Db::commit();
        } catch (\Exception $exception) {
            Db::rollBack();
            bizException(ReturnCode::UNDEFINED);
        }
    }

    function getRoleRulesAttribute($value)
    {
        $rs = RoleRule::with('rule')->where('role_id', $this->role_id)->get();

        $roleRules = array();
        if (!empty($rs)) {
            foreach ($rs->toArray() as $k => $v) {
                $roleRules[$k] = $v['rule'];
            }
        }

        return $roleRules;
    }


}