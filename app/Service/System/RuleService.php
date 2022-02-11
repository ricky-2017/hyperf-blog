<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/14
 * Time: 15:47
 */

namespace App\Service\System;

use App\Dto\System\RuleReq;
use App\Dto\PagingReq;

interface RuleService
{
    function lists(PagingReq $paging, RuleReq $search);

    function get($id);

    function post(RuleReq $req);

    function put($id, RuleReq $req);

    function patch($id, RuleReq $req);

    function delete($id);

    function putStatus($id);

    function putResources($id, $resourceIds, $resourceType);

}