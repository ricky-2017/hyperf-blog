<?php
/**
 * Created by PhpStorm.
 * User: rubio
 * Date: 2020/8/25
 * Time: 11:22
 */
namespace App\Controller;

use League\Flysystem\Filesystem;
use Hyperf\HttpServer\Request;


class UploadController extends AbstractController
{
    // 前端上传 先这么处理吧
    public function getToken()
    {
        $data = '七牛accessKey:047Ta2H29LH_88sqh4x9hAYnDDM=:eyJzY29wZSI6ImJsb2dpbWciLCJkZWFkbGluZSI6MTU5MzQ0MDg5MCwicmV0dXJuQm9keSI6IntcImltZ1VybFwiOiBcImh0dHA6XC9cL2Jsb2dpbWcuY29kZWJlYXIuY25cLyQoa2V5KVwifSJ9';
        return jsonSuccess('success',['token'=>$data]);
    }

    public function uploads(Request $request,Filesystem $filesystem)
    {
        // Process Upload
        $file = $request->file('file');
        $stream = fopen($file->getRealPath(), 'r+');
        $filesystem->writeStream(
            'uploads/'.$file->getClientFilename(),
            $stream
        );
        fclose($stream);
    }
}