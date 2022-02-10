<?php
/**
 * Created by PhpStorm.
 * User: rubio
 * Date: 2022/2/10
 * Time: 12:00
 */


// 服务配置文件
return [

    //后台分组标识，ADMIN为默认超管且索引为0
    'SYS_GROUP' => ['ADMIN', 'ENTERPRISE'],
    'SYS_GROUP_DESC' => ['平台管理员', '企业管理员'],

    //超级管理员，默认不能删除
    'SUPER_ADMIN' => 'super',

];