<?php

namespace App\Controller\System;

use App\Controller\AbstractController;
use App\Service\System\SysGroupService;


class SysGroup extends AbstractController
{
    /**
     * 获取后台分组标识
     * @param SysGroupService $sysGroupService
     * @return mixed
     */
    public function getSysGroups(SysGroupService $sysGroupService)
    {
        $result = $sysGroupService->getSysGroups();
        return jsonSuccess($result);
    }
}