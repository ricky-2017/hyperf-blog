<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/31
 * Time: 17:39
 */

namespace App\Service\System\impl;


use App\Constants\ReturnCode;
use App\Dto\PagingReq;
use App\Model\System\Role;
use App\Model\System\User;
use App\Service\System\UserService;
use App\Dto\System\UserReq;
use Phper666\JwtAuth\Jwt;

class UserServiceImpl implements UserService
{

    private $user;
    private $role;
    private $captcha;
    private $jwt;

    public function __construct(User $admin, Role $role, Jwt $jwt)
    {
        $this->user = $admin;
        $this->role = $role;
        $this->jwt = $jwt;
    }

    function lists(PagingReq $paging, UserReq $req)
    {
        return $this->user
            ->where('user_group', config('service.SYS_GROUP')[0])
            ->paginate();
    }

    function get($id = '')
    {
        if (empty($id)) {
            bizException(ReturnCode::INVALID_PARAM, '缺失[id]参数');
        }

        $data = $this->user->where('user_id', '=', $id)->first();

        if (empty($data)) {
            bizException(ReturnCode::REQUESTED_RESOURCE_NOT_FOUND);
        }

        return $data->append(['user_roles']);
    }

    function post(UserReq $req)
    {
        $admin = $this->user->where('user_name', $req->getName())->first();
        if ($admin) {
            bizException(ReturnCode::DUPLICATE_DATA_NOT_ALLOW, "管理员已存在");
        }

        $password = password_hash($req['user_password'], PASSWORD_BCRYPT);
        $req['user_password'] = $password;
        $req['user_status'] = 1;

        $this->user->save($req->toArray());
        return $this->user;
    }

    function put($id, UserReq $req)
    {
        $admin = $this->user->where('user_id', $id)->first();
        if (!$admin) {
            bizException(ReturnCode::DATA_NOT_FOUND, "管理员不存在");
        }
        $admin = $this->user->where('user_name', $req->getName())->first();
        if ($admin) {
            bizException(ReturnCode::DUPLICATE_DATA_NOT_ALLOW, "用户名已被使用");
        }

        $this->user->find($id)->save($req->toArray());
    }

    function refreshAdminLoginStatus($id, $tokenVersion)
    {
        $this->user->where('user_id', $id)->update([
            "user_token_version" => $tokenVersion,
            "user_last_login_time" => date('Y-m-d H:i:s'),
//            "user_last_login_ip" => request()->ip(),
        ]);
    }


    function delete($id)
    {
        $admin = $this->user->where('user_id', $id)->first();
        if ($admin['user_name'] == config('service.SUPER_ADMIN')) {
            bizException(ReturnCode::INVALID_PARAM, '默认超管帐号不能删除');
        }
        $this->user->relationDelete($id);
    }

    function login($account, $password)
    {
        $user = $this->user->where('user_name', $account)->first();

        if (empty($user)) {
            bizException(ReturnCode::DATA_NOT_FOUND, "管理员不存在");
        }

        if (!password_verify($password, $user->user_password)) {
            bizException(ReturnCode::DATA_NOT_FOUND);
        };

        if (!$user->user_status) {
            bizException(ReturnCode::DATA_NOT_FOUND);
        }


        $token = (string)$this->jwt->getToken(['user_id' => $user['user_id'], 'user_name' => $user['nickname']]);

//        $this->refreshAdminLoginStatus($user->user_id, $token->getClaim("jti"));

        return $token;
    }

    function putStatus($id)
    {
        $admin = $this->user->find($id);

        if ($admin['user_name'] == config('service.SUPER_ADMIN')) {
            bizException(ReturnCode::INVALID_PARAM, '无权限修改超管帐号');
        }

        $admin->save(['user_status' => !$admin['user_status']]);
    }

    function putPassword($id, $password)
    {
        if (empty($password)) {
            bizException(ReturnCode::INVALID_PARAM, "密码不能为空");
        }

        $admin = $this->user->find($id);
        if ($admin['user_name'] == config('service.SUPER_ADMIN')) {
            bizException(ReturnCode::INVALID_PARAM, '无权限修改超管帐号');
        }

        $password = password_hash($password, PASSWORD_BCRYPT);
        $this->user->find($id)->save([
            'user_password' => $password,
            'last_update_password_time' => date('Y-m-d H:i:s')
        ]);
    }

    function putRoles($id, $roleIds)
    {
        $this->user->replaceRoles($id, $roleIds);
    }

    function captcha()
    {
        return $this->captcha->entry(request()->ip());
    }
}