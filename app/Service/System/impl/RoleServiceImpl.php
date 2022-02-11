<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/31
 * Time: 14:51
 */

namespace App\Service\System\impl;


use App\Constants\ReturnCode;
use App\Dto\PagingReq;
use App\Model\System\Role;
use App\Service\System\RoleService;
use App\Dto\System\RoleReq;

class RoleServiceImpl implements RoleService
{

    /**
     * @var Role
     */
    private $role;

    /**
     * RoleServiceImpl constructor.
     * @param Role $role
     */
    public function __construct(Role $role)
    {
        $this->role = $role;
    }

    function lists(PagingReq $paging, RoleReq $search)
    {
        $query = $this->role->orderby('role_id');

        if (!empty($search->getName())) {
            $query->where('role_name', 'like', '%' . urldecode($search->getName()) . '%');
        }

        if (!empty($search->getGroup())) {
            if (is_array($search->getGroup())) {
                $query->whereIn('role_group', $search->getGroup());
            } else {
                $query->where('role_group', '=', $search->getGroup());
            }
        }

        if ($search->getStatus()) {
            $query->where('role_status', $search->getStatus());
        }

        $data = $query->paginate();

        return array(
            'page' => $data->currentPage(),
            'pageSize' => $data->perPage(),
            'count' => $data->total(),
            'data' => $data->items()
        );
    }

    function get($id)
    {
        $data = $this->role->find($id);

        $data->append(['role_rules']);

        if (empty($data)) {
            bizException(ReturnCode::REQUESTED_RESOURCE_NOT_FOUND);
        }

        return $data;
    }


    function post(RoleReq $req)
    {
        $data = [
            'role_group' => $req->getGroup(),
            'role_name' => $req->getName(),
            'role_status' => $req->getStatus()
        ];
        if (empty($data['role_group']) || !in_array($data['role_group'], config('service.SYS_GROUP'))) {
            bizException(ReturnCode::INVALID_PARAM, '后台分组标识有误');
        }
//        $validate = new \app\system\validate\Role();
//        if (!$validate->scene('save')->check($data)) {
//            bizException(ReturnCode::INVALID_PARAM, $validate->getError());
//        }
        $role = $this->role->where('role_name', $req->getName())->first();
        if ($role) {
            bizException(ReturnCode::DUPLICATE_DATA_NOT_ALLOW, "角色已存在");
        }
        return Role::create($req->toArray());
    }

    function put($id, RoleReq $req)
    {
        $data = [
            'role_id' => $id,
            'role_name' => $req->getName(),
        ];
        if ($req->getStatus()) {
            $data['role_status'] = $req->getStatus();
        }
//        $validate = new \app\system\validate\Role();
//        if (!$validate->scene('update_name')->check($data)) {
//            bizException(ReturnCode::INVALID_PARAM, $validate->getError());
//        }

        $role = $this->role->where('role_id', $id)->first();
        if (!$role) {
            bizException(ReturnCode::DATA_NOT_FOUND, "角色不存在");
        }

        $findRs = $this->role->where('role_id', '<>', $data['role_id'])
            ->where('role_name', $data['role_name'])
            ->first();
        if ($findRs) {
            bizException(ReturnCode::DUPLICATE_DATA_NOT_ALLOW, "角色已存在");
        }
        $this->role->find($id)->update($data);

    }

    function delete($id)
    {
        $this->role->where("role_id", $id)->delete();
    }

    function putStatus($id)
    {
        $role = $this->role->find($id);
        $role->role_status = ($role['role_status'] == 1) ? 0 : 1;
        return $role->save();
    }

    function putRules($id, $ruleIds)
    {
        $this->role->replaceRoleRules($id, $ruleIds);
    }


}