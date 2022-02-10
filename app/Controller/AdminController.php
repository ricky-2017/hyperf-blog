<?php
/**
 * Created by PhpStorm.
 * User: Kepler
 * Date: 2020/8/24
 * Time: 23:10
 */

namespace App\Controller;

use App\Constants\ReturnCode;
use App\Model\Admin;
use Hyperf\HttpServer\Request;
use Phper666\JwtAuth\Jwt;

class AdminController extends AbstractController
{
//    /**
//     * 创建管理员账号
//     */
//    public function create(Request $request)
//    {
//        $params = $request->post();
//
//        if (empty($params['username']) || empty($params['password']) || strlen($params['password']) < 6) {
//            bizException(ReturnCode::INVALID_PARAM);
//
//        }
//
//        $result = $this->admin->register($data['username'], $data['password']);
//
//        return jsonSuccess();
//    }

    public function login(Request $request, Admin $adminModel, Jwt $jwt)
    {
        $username = $request->post('username');
        $password = $request->post('password');

        if ($username == '')
            bizException(ReturnCode::INVALID_PARAM, '用户名不能为空');
        if ($password == '' || strlen($password) < 6)
            bizException(ReturnCode::INVALID_PARAM, '密码不能少于6位');

        $result = $adminModel->checkPassword($username, $password);

        if (!empty($result)) {
            $token = (string)$jwt->getToken($result);

            $userResult = array(
                'userId' => $result['user_id'],
                'userName' => $result['username'],
                'lastLoginTime' => time(),
                'token' => array(
                    'accessToken' => $token,
                    'tokenExpiresIn' => time() + 7200,
                    'exp' => 7200
                )
            );
            return jsonSuccess($userResult);
        } else {
            bizException(ReturnCode::DATA_CONSTRAINT_ERROR, '登录失败');
        }
    }
}
