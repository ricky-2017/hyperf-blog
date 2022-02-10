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

namespace App\Exception\Handler;

use App\Constants\ReturnCode;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Phper666\JwtAuth\Exception\TokenValidException;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class TokenExceptionHandler extends ExceptionHandler
{
    /**
     * @var StdoutLoggerInterface
     */
    protected $logger;

    public function __construct(StdoutLoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        if ($throwable instanceof TokenValidException) {
            $this->stopPropagation();

            $body = [
                'code' => ReturnCode::ACCESS_TOKEN_EXPIRE[0],
                'data' => [],
                'message' => ReturnCode::ACCESS_TOKEN_EXPIRE[1]
            ];
            $this->logger->debug(json_encode($body));

            return $response->withHeader('Content-Type', 'application/json')
                ->withStatus(200)
                ->withBody(new SwooleStream(json_encode($body, JSON_UNESCAPED_UNICODE)));
        }
        return $response;
    }

    public function isValid(Throwable $throwable): bool
    {
        return true;
    }
}
