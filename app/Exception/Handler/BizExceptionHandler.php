<?php
/**
 * Created by PhpStorm.
 * User: Kepler
 * Date: 2020/8/9
 * Time: 1:05
 */

namespace App\Exception\Handler;


use Hyperf\ExceptionHandler\ExceptionHandler;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class BizExceptionHandler extends ExceptionHandler
{
    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        // TODO: Implement handle() method.

    }

    public function isValid(Throwable $throwable) : bool{
        // TODO: Implement isValid() method.
    }
}
