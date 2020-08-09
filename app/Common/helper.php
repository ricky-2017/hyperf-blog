<?php
/**
 * Created by PhpStorm.
 * User: Kepler
 * Date: 2020/8/9
 * Time: 10:44
 */

/**
 * 向外抛出业务异常
 * @see \app\common\exception\BizException
 * @param array $code
 * @param string $message
 * @param array $data 额外调试信息
 * @param Throwable $previous 异常堆栈
 * @throws \app\common\exception\BizException 业务异常
 *
 */
if(!function_exists('bizException'))
{
    function bizException(array $code, $message = "", $data = [], Throwable $previous = null) {
        throw new \App\Exception\BizException($code, $message, $data, $previous);
    }
}

/**
 * 接口返回代码通用函数
 * @param array $code
 * @param array $data
 * @param string $msg
 */

if(!function_exists('jsonReturnCode'))
{
    function jsonReturnCode(array $code = \App\Constants\ReturnCode::UNDEFINED, $data = [], $msg = '') {
        $return_data = [
            'code' => $code[0],
            'msg' => empty($msg) ? $code[1] : $msg,
            'data' => $data,
        ];
//          TODO 研究乱码原因
//        return json_encode($return_data,JSON_UNESCAPED_UNICODE);
        return json_encode($return_data);

    }
}

/**
 * 接口返回成功
 * @param array $data
 * @param string $msg
 * @return string
 */
if(!function_exists('jsonSuccess'))
{
    function jsonSuccess($msg='', $data=[]) {
        return jsonReturnCode(\App\Constants\ReturnCode::SUCCESS, $data, $msg);
    }
}
