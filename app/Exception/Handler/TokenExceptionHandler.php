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
        if($throwable instanceof TokenValidException)
        {
            $this->stopPropagation();

            $body = [
                'code'        => $throwable->getCode(),
                'data'        => [],
                'message'     => $throwable->getMessage()
            ];

            return $response->withHeader('Server', 'Hyperf')->withStatus(200)->withBody(new SwooleStream(json_encode($body)));
        }

    }

    public function isValid(Throwable $throwable): bool
    {
        return true;
    }
}
