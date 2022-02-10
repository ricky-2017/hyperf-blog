<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/31
 * Time: 10:10
 */

namespace App\Service\System;

use app\system\dto\UserReq;
use App\Dto\PagingReq;

interface UserService
{
    function lists(PagingReq $paging, UserReq $req);

    function get($id = '');

    function post(UserReq $req);

    function put($id, UserReq $req);

    function refreshAdminLoginStatus($id, $tokenVersion);

    function delete($id);

    function putStatus($id);

    function putPassword($id, $password);

    function putRoles($id, $roleIds);

    function login($account, $password);

    function captcha();

}