<?php

namespace App\Controller\System;

use App\Controller\System\BaseController;
use app\system\dto\UploadReq;
use App\Service\System\UploadService;

class Upload extends BaseController
{
    /**
     * 上传文件
     * @param UploadService $uploadService
     * @return mixed
     * @throws \ReflectionException
     */
    public function uploadFile(UploadService $uploadService)
    {
        return $uploadService->uploadFile(UploadReq::fromRequest());
    }
}