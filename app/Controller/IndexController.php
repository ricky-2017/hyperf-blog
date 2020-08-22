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

use App\Service\ArticleService;


class IndexController extends AbstractController
{

    public function index(ArticleService $articleService)
    {
        $result = $articleService->list($this->request->input('get.'),$this->request->input('get.page'),$this->request->input('get.pageSize'));

        return jsonSuccess('',$result);
    }

    public function test()
    {
        return 111;
    }
}
