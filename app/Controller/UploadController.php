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
    public function getToken(FilesystemFactory $factory)
    {
        $config = config('file.storage.qiniu');
        $accessKey = $config['accessKey'];
        $secretKey = $config['secretKey'];
        $bucket    = $config['bucket'];
        // 构建鉴权对象
        $auth = new Auth($accessKey, $secretKey);
        // 生成上传 Token
        $token = $auth->uploadToken($bucket);
        $data = $token;
//        $data = '七牛accessKey:'.$token;
        return jsonSuccess(['token'=>$data]);
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

        $config = config('file.storage.qiniu');
        $cdn_route = $config['prefix'];
        return jsonSuccess(['imgUrl' => $cdn_route.$savePath]);
//        return jsonSuccess(['imgUrl' => env('DOMAIN','http://swooleapi.lubiao9.cn').$savePath]);
    }

    public function upToQiniu(Request $request,FilesystemFactory $factory)
    {
        $factory = $factory->get('qiniu');

        $file = $request->file('file');

        $config = config('file.storage.qiniu');
        $stream = fopen($file->getRealPath(), 'r+');

        $savePath = '/upload/images/'.date('Y-m-d').'/'.create_id().'.'.$file->getExtension();
        $factory->writeStream(
            $savePath,
            $stream
        );
        fclose($stream);

        $cdn_route = $config['prefix'];
        return jsonSuccess(['imgUrl' => $cdn_route.$savePath]);
    }
}
