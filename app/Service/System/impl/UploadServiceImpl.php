<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2021/3/6
 * Time: 10:56
 */

namespace App\Service\System\impl;


use App\Constants\ReturnCode;
use App\Service\System\UploadService;
use App\Dto\System\UploadReq;

class UploadServiceImpl implements UploadService
{

    function uploadFile(UploadReq $uploadReq)
    {
        if (empty($uploadReq->getInputName())) {
            bizException(ReturnCode::INVALID_PARAM, 'input_name参数不能为空');
        }
        if (empty($uploadReq->getFileType()) || !in_array($uploadReq->getFileType(), ['IMAGE', 'VIDEO', 'PDF', 'EXCEL', 'APP', 'PAYMENT'])) {
            bizException(ReturnCode::INVALID_PARAM, 'file_type参数错误');
        }

        $inputName = $uploadReq->getInputName() ?? 'file';
        $file = request()->file($inputName);
        if (!$file) {
            bizException(ReturnCode::INVALID_PARAM, 'file不存在');
        }

        if (empty($uploadReq->getMaxSize())) {
            $uploadReq->getMaxSize(1024 * 1024 * 5); //默认大小5M
        }

        $validateRule = [];
        switch ($uploadReq->getFileType()) {
            case "IMAGE":
                $validateRule = [
                    'size' => $uploadReq->getMaxSize(),
                    'ext' => 'jpeg,jpg,png,gif',
                    'type' => 'image/gif,image/jpeg,image/jpg,image/pjpeg,image/x-png,image/png'
                ];
                break;
            case "VIDEO":
                $validateRule = [
                    'size' => $uploadReq->getMaxSize(),
                    'ext' => 'mp4',
                    'type' => 'video/mp4'
                ];
                break;
            case "EXCEL":
                $validateRule = [
                    'size' => $uploadReq->getMaxSize(),
                    'ext' => 'xls,xlsx'
                ];
                break;
            case "PDF":
                $validateRule = [
                    'size' => $uploadReq->getMaxSize(),
                    'ext' => 'pdf'
                ];
                break;
            case "APP":
                $validateRule = [
                    'size' => $uploadReq->getMaxSize(),
                    'ext' => 'apk,ipa'
                ];
                break;
            case "PAYMENT":
                $validateRule = [
                    'size' => $uploadReq->getMaxSize()
                ];
                break;
        }
        if ($uploadReq->getFileType() == "PAYMENT") {
            $info = $file->validate($validateRule)
                ->rule(function () {
                    return md5(time() . rand());
                })->move(config('service.UPLOAD_MOVE_BASE_PATH') . 'payment' . DIRECTORY_SEPARATOR . date('Ymd'));
            if (!$info) {
                bizException(ReturnCode::DATA_CONSTRAINT_ERROR, $file->getError());
            }
            $filePath = (config('service.UPLOAD_BASE_SAVE_PATH') . 'payment' . DIRECTORY_SEPARATOR . date('Ymd') . DIRECTORY_SEPARATOR . $info->getSaveName());
        } else {
            $info = $file->validate($validateRule)
                ->rule(function () {
                    return md5(time() . rand());
                })->move(config('service.UPLOAD_MOVE_BASE_PATH') . 'uploadFile' . DIRECTORY_SEPARATOR . date('Ymd'));
            if (!$info) {
                bizException(ReturnCode::DATA_CONSTRAINT_ERROR, $file->getError());
            }
            $filePath = (config('service.UPLOAD_BASE_SAVE_PATH') . 'uploadFile' . DIRECTORY_SEPARATOR . date('Ymd') . DIRECTORY_SEPARATOR . $info->getSaveName());
        }

        $absolutePath = toAbsolutePath($filePath);

        //图片裁剪
        if ($uploadReq->getFileType() == 'IMAGE'
            && !empty($uploadReq->getCropW())
            && !empty($uploadReq->getCropH())) {
            $image = Image::open($filePath);
            $cropResult = $image
                ->crop($uploadReq->getCropW(), $uploadReq->getCropH(), $uploadReq->getCropX(), $uploadReq->getCropY())
                ->save($filePath, null, 100);
            if (!$cropResult) {
                bizException(ReturnCode::UNEXPECTED_ERROR, $file->getError());
            }
        }

        //上传OSS !empty(config('service.OSS_ACCESS_KEY_ID')) &&
        if (!empty(config('object_storage.object_storage_key')) && $uploadReq->getFileType() != "PAYMENT") {
            my_app(ObjectStorageService::class)->uploadFile($absolutePath, $filePath);
            @unlink($absolutePath);
        }

        return $filePath;
    }

    function getUrlPrefix()
    {
        if (!empty(config('object_storage.object_storage_key'))) {
            $type = config('object_storage.object_storage_key');
            return config('object_storage.' . $type . '.OUT_NET_DOMAIN');
        } else {
            return config('service.BASE_DOMAIN');
        }
    }


}