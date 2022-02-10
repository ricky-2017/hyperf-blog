<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/12
 * Time: 15:13
 */

namespace App\Service\System;


use app\system\dto\ElementApiReq;
use app\system\dto\ElementReq;
use app\system\dto\ElementSearchReq;
use App\Dto\PagingReq;

/**
 * 前端元素业务类
 * Interface ElementService
 * @package App\Service\System
 */
interface ElementService {

    /**
     * 前端元素列表
     * @param PagingReq $paging
     * @param ElementSearchReq $search
     * @return mixed
     */
    function lists(PagingReq $paging, ElementSearchReq $search);

    /**
     * 返回树结构前端元素
     * @param int $depth
     * @param null $type
     * @param null $userId
     * @param string $group
     * @return mixed
     */
    function listTree($depth = -1, $type = null, $group = '', $userId = null);

    /**
     * 获取元素详情
     * @param $id
     * @return mixed
     */
    function get($id);

    /**
     * 新增
     * @param ElementReq $req
     * @return mixed
     */
    function post(ElementReq $req, ElementApiReq $elementApiReq);

    /**
     * 修补
     * @param $id
     * @param ElementReq $req
     * @return mixed
     */
    function patch($id, ElementReq $req, ElementApiReq $elementApiReq);

    /**
     * 编辑
     * @param $id
     * @param ElementReq $req
     * @return mixed
     */
    function put($id, ElementReq $req, ElementApiReq $elementApiReq);

    /**
     * 排序
     * @param $id
     * @param $sort
     * @return mixed
     */
    function sort($id, $sort);

    /**
     * 删除
     * @param $id
     * @return mixed
     */
    function delete($id);

    /**
     * 批量删除
     * @param array $ids
     * @return mixed
     */
    function batchDelete(array $ids);

    function getMyButtonsPrivilege($sysUserId, $userGroup);
}