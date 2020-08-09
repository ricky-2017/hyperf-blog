<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace App\Controller;

use App\Constants\ReturnCode;

class IndexController extends AbstractController
{
    public function index()
    {
        $user = $this->request->input('user', 'Hyperf');
        $method = $this->request->getMethod();
        $array = ['list'=>['s','s','数据库的建立'],'jkjj'=>2];

        bizException(ReturnCode::INVALID_PARAM,'错误参数测试',$array);

        return jsonSuccess($array);



//        return [
//            'method' => $method,
//            'message' => "Hello Hyperf {$user}.",
//        ];
    }

    public function test()
    {
        return 111;
    }
}
