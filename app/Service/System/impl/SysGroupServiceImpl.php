<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/13
 * Time: 14:25
 */

namespace App\Service\System\impl;

use App\Service\System\SysGroupService;

class SysGroupServiceImpl implements SysGroupService
{
    function getSysGroups()
    {
        $result = config('service.SYS_GROUP');
        $returnData = [];
        if (!empty($result)) {
            foreach ($result as $key => $item) {
                array_push($returnData, [
                    'id' => $key + 1,
                    'group_name' => $item,
                    'group_name_zn' => config('service.SYS_GROUP_DESC')[$key],
                ]);
            }
        }
        return $returnData;
    }
}