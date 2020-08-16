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
use Hyperf\DbConnection\Db;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Monolog\Formatter\FormatterInterface;
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
        $this->logger->debug($this->formatter->format($throwable));

        $this->isPropagationStopped();
//        Db::rollBack();
        $body = [
            'code'        => $throwable->getCode(),
            'data'        => $throwable->getData(),
            'message'     => $throwable->getMessage()
        ];

        return $response->withHeader('Server', 'Hyperf')->withStatus(200)->withBody(new SwooleStream(json_encode($body)));

    }

    public function isValid(Throwable $throwable): bool
    {
        return $throwable instanceof BizException;
    }

}
