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
use App\Model\Article;
use App\Service\UserService;
use Hyperf\DbConnection\Db;

class IndexController extends AbstractController
{
    public function index(UserService $userService)
    {
//        return $userService->test();

        $user = $this->request->input('user', 'Hyperf');
        $method = $this->request->getMethod();
//        return 111;
//        $a = Db::table('test')->where('id',1)->get();
//        $a->toArray();
//
//        return $a->toArray();
        return Db::table('article')->get()->toArray();
//        $data = make(Article::Class)->getArticles(1,10);
        return $data;

        $array = ['list'=>['s','s','数据库的建立   你好哈'],'jkjj'=>2];

        bizException(ReturnCode::INVALID_PARAM,'错误参数测试',$array);

        return jsonSuccess($array);

    }

    public function test()
    {
        return 111;
    }
}
