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
use app\system\dto\RoleReq;

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

//        $searchKeys = $search->keys();
//        $searchValues = $search->toArray();
//        if (!empty($search)) {
//            $query->withSearch($searchKeys, $searchValues);
//        }
        if ($search->getStatus()) {
            $query->where('role_status', $search->getStatus());
        }

        return $query->paginate();
    }

    function get($id)
    {
        $data = $this->role->find($id);

        if (empty($data)) {
            bizException(ReturnCode::REQUESTED_RESOURCE_NOT_FOUND);
        }

        return $data->append(['role_rules']);
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

        $this->role->save($req->toArray());
        return $this->role;
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
        $this->role->find($id)->save($data);

    }

    function delete($id)
    {
        $this->role->where("role_id", $id)->delete();
    }

    function putStatus($id)
    {
        $role = $this->role->find($id);
        $role->save(['role_status' => !$role['role_status']]);
        return $this->role;
    }

    function putRules($id, $ruleIds)
    {
        $this->role->replaceRoleRules($id, $ruleIds);
    }


}