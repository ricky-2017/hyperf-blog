<?php
/**
 * Created by PhpStorm.
 * User: rubio
 * Date: 2021/11/10
 * Time: 16:19
 */

namespace App\Controller\System;


use App\Constants\ReturnCode;
use App\Controller\AbstractController;
use App\Model\Admin;
use Hyperf\HttpServer\Request;
use Phper666\JwtAuth\Jwt;

class AuthController extends AbstractController
{
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
            return jsonSuccess('登录成功', $userResult);
        } else {
            bizException(ReturnCode::DATA_CONSTRAINT_ERROR, '登录失败');
        }
    }
}