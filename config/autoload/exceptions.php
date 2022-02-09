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
return [
    'handler' => [
        'http' => [
            // 顺序决定执行先后
            Hyperf\HttpServer\Exception\Handler\HttpExceptionHandler::class,
            App\Exception\Handler\TokenExceptionHandler::class,
            App\Exception\Handler\BizExceptionHandler::class,
            App\Exception\Handler\AppExceptionHandler::class
        ],
    ],
];
