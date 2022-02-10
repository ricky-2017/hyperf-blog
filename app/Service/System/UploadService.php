<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2021/3/6
 * Time: 10:56
 */

namespace App\Service\System;


use app\system\dto\UploadReq;

interface UploadService
{
    /**
     * 上传文件
     * @param UploadReq $uploadReq
     * @return mixed
     */
    function uploadFile(UploadReq $uploadReq);

    /**
     * 获取上传文件的域名前缀
     * @return mixed
     */
    function getUrlPrefix();
}