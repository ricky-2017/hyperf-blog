<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2020/6/28
 * Time: 17:37
 */

namespace App\Model;


class Admin extends Model
{
    protected $table ='admin';

//    public function login($username, $password)
//    {
//        // 检查用户名是否存在
//        $adminInfo = D::table('admin')->where('username', $username)->where('status',0)->first();
//
//        if (!cb_passwordEqual($adminInfo->password, $adminInfo->salt, $password))
//            bizException(ReturnCode::ADMIN_PASSWORD_ERROR,'用户不存在');
//
//        $time = time();
//        $token = create_id();
//
//        $data = array(
//            'last_login_time' => $time,
//            'access_token' => $token,
//            'token_expires_in' => $time + 7*84600
//        );
//
//        // 更新数据
//        DB::table('admin')->where('username', $username)->update($data);
//
//        $userResult = array(
//            'userId' => $adminInfo->user_id,
//            'userName' => $adminInfo->username,
//            'lastLoginTime' => $time,
//            'token' => array(
//                'accessToken' => $token,
//                'tokenExpiresIn' => $time + 7*84600,
//                'exp' => 7*84600
//            )
//        );
//
//        return $userResult;
//    }
}