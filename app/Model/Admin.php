<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2020/6/28
 * Time: 17:37
 */

namespace App\Model;

use App\Constants\ReturnCode;
use Hyperf\DbConnection\Db;

class Admin extends Model
{
    protected $table = 'admin';

//    public function register($username, $password)
//    {
//        // 检查用户名是否存在
//        $isEx = $this->db->where('username', $username)->count_all_results(TABLE_ADMIN);
//        if ($isEx) {
//            return fail('该用户名已经注册');
//        }
//
//        $encrypt = cb_encrypt($password);
//
//        $time = time();
//
//        $data = array(
//            'username' => $username,
//            'password' => $encrypt['password'],
//            'salt' => $encrypt['salt'],
//            'user_id' => create_id(),
//            'last_login_time' => $time,
//            'access_token' => create_id(),
//            'token_expires_in' => $time + WEEK,
//            'create_time'=> $time
//        );
//
//        $this->db->insert(TABLE_ADMIN, $data);
//
//        $adminInfo = $this->db->where('username', $username)
//            ->from(TABLE_ADMIN)
//            ->get()
//            ->row_array();
//
//        $userResult = array(
//            'userId' => $adminInfo['user_id'],
//            'userName' => $adminInfo['username'],
//            'lastLoginTime' => $adminInfo['last_login_time'],
//            'token' => array(
//                'accessToken' => $adminInfo['access_token'],
//                'tokenExpiresIn' => $adminInfo['token_expires_in'],
//                'exp' => WEEK
//            )
//        );
//
//        return success($userResult);
//    }


    public function checkPassword($username, $password)
    {
        // 检查用户名是否存在
        $adminInfo = Db::table('admin')->where('username', $username)->where('status', 0)->first();

        if (!cb_passwordEqual($adminInfo->password, $adminInfo->salt, $password)) {

            bizException(ReturnCode::DATA_CONSTRAINT_ERROR, '用户不存在');
        } else {

            return [
                'user_id' => $adminInfo->user_id,
                'username' => $adminInfo->username
            ];
        }

    }
}
