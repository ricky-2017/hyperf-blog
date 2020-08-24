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
    protected $table ='admin';

    public function checkPassword($username, $password)
    {
        // 检查用户名是否存在
        $adminInfo = Db::table('admin')->where('username', $username)->where('status',0)->first();

        if (!cb_passwordEqual($adminInfo->password, $adminInfo->salt, $password))
            bizException(ReturnCode::DATA_CONSTRAINT_ERROR,'用户不存在');

        return [
            'user_id'   => $adminInfo->user_id,
            'username' => $adminInfo->username
        ];
    }
}
