<?php
/**
 * Created by PhpStorm.
 * User: Kepler
 * Date: 2020/8/16
 * Time: 13:25
 */

namespace App\Exception\Handler;


use App\Exception\BizException;

use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\ExceptionHandler\Formatter\FormatterInterface;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class BizExceptionHandler extends ExceptionHandler
{
    /**
     * @var StdoutLoggerInterface
     */
    protected $logger;

    /**
     * @var FormatterInterface
     */
    protected $formatter;

    public function __construct(StdoutLoggerInterface $logger, FormatterInterface $formatter)
    {
        $this->logger = $logger;
        $this->formatter = $formatter;
    }

    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        if ($throwable instanceof BizException) {
            $this->logger->debug($this->formatter->format($throwable));

            $this->stopPropagation();

            $body = [
                'code' => $throwable->getCode(),
                'data' => $throwable->getData(),
                'message' => $throwable->getMessage(),
            ];

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
