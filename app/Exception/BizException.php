<?php
/**
 * Created by PhpStorm.
 * User: Kepler
 * Date: 2020/8/9
 * Time: 0:56
 */

namespace App\Exception;

use App\Constants\ReturnCode;
use Hyperf\Server\Exception\ServerException;
use Throwable;

class BizException extends ServerException
{
    /**
     * @var array ReturnCode
     */
    protected $returnCode;

    public function __construct(array $returnCode = ReturnCode::UNDEFINED,
                                $message, $data=[],
                                Throwable $previous = null
    ) {
        $this->message = $message ? $message : $returnCode[1];
        $this->returnCode = $returnCode;
        $this->code = $returnCode[0];
        $this->data = $data;
        parent::__construct($this->message, $returnCode[0], $previous);
    }

    /**
     * @return array
     */
    public function getReturnCode() {
        return $this->returnCode;
    }
}
