<?php
/**
 * Created by PhpStorm.
 * User: rubio
 * Date: 2020/8/25
 * Time: 11:22
 */
namespace App\Controller;

use Hyperf\HttpServer\Request;
use Hyperf\Filesystem\FilesystemFactory;
use Qiniu\Auth;
use Qiniu\Config;
use Qiniu\Storage\UploadManager;
use Qiniu\Zone;

class UploadController extends AbstractController
{
    // 前端上传 先这么处理吧
    public function getToken()
    {
        $data = '七牛accessKey:047Ta2H29LH_88sqh4x9hAYnDDM=:eyJzY29wZSI6ImJsb2dpbWciLCJkZWFkbGluZSI6MTU5MzQ0MDg5MCwicmV0dXJuQm9keSI6IntcImltZ1VybFwiOiBcImh0dHA6XC9cL2Jsb2dpbWcuY29kZWJlYXIuY25cLyQoa2V5KVwifSJ9';
        return jsonSuccess('success',['token'=>$data]);
    }

    // 文件存储
    public function uploads(Request $request,FilesystemFactory $factory)
    {
        $factory = $factory->get('qiniu');
        // Process Upload
        $file = $request->file('file');
        $stream = fopen($file->getRealPath(), 'r+');

        $savePath = '/upload/images/'.date('Y-m-d').'/'.create_id().'.'.$file->getExtension();
        $factory->writeStream(
            $savePath,
            $stream
        );
        fclose($stream);

        return jsonSuccess('success',['imgUrl' => 'http://swooleapi.lubiao9.cn'.$savePath]);
    }

    public function upToQiniu(Request $request,FilesystemFactory $factory)
    {
//        $file = $request->file('file');
//
//        $config = config('file.storage.qiniu');
//        return $config;

//        $accessKey = $config['accessKey'];
//        $secretKey = $config['secretKey'];
//        $bucket    = $config['bucket'];
//        // 构建鉴权对象
//        $auth = new Auth($accessKey, $secretKey);
//        // 生成上传 Token
//        $token = $auth->uploadToken($bucket);
//        // 要上传文件的本地路径
//        $filePath = $file->getRealPath();
//        // 上传到七牛后保存的文件名
////        $key = 'my-php-logo.png';
//        $savePath = '/upload/images/'.date('Y-m-d').'/'.create_id().'.'.$file->getExtension();
//
//        // 初始化 UploadManager 对象并进行文件的上传。
////        $zone = new Zone(array('upload-z2.qiniup.com'));
//        $config = new Config(Zone::zonez2());
//        $uploadMgr = new UploadManager($config);
//
//        try{
//            list($ret, $err) = $uploadMgr->putFile($token, $savePath, $filePath);
//            return [$ret,$err];
//
//        }catch (\Exception $e)
//        {
//            return $e->getMessage();
//        }

        $qiniu = $factory->get('qiniu');

        // Write Files
        $qiniu->write('path/to/file.txt', 'contents');
        // Process Upload
//        $file = $request->file('file');
//        $stream = fopen($file->getRealPath(), 'r+');
//
//        $savePath = '/upload/images/'.date('Y-m-d').'/'.create_id().'.'.$file->getExtension();
//        $savePath = '/upload/images/'.date('Y-m-d').'/'.create_id().'.txt';
//        $factory->write(
//            $savePath,
//            'sdfjslkdjflksdjflk'
//        );

//        return jsonSuccess('success',['imgUrl' => 'http://qiniu.lubiao9.cn'.$savePath]);

    }
}