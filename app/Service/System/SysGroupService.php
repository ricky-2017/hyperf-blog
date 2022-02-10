<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/13
 * Time: 14:19
 */

namespace App\Service\System;

interface SysGroupService
{
    /**
     * 获取后台分组标识
     * @return mixed
     */
    function getSysGroups();
}