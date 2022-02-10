<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/31
 * Time: 10:10
 */

namespace App\Service\System;

use app\system\dto\RoleReq;
use App\Dto\PagingReq;

interface RoleService
{

    function lists(PagingReq $paging, RoleReq $search);

    function get($id);

    function post(RoleReq $req);

    function put($id, RoleReq $req);

    function delete($id);

    function putStatus($id);

    function putRules($id, $ruleIds);
}