<?php
/**
 * Created by PhpStorm.
 * User: Kepler
 * Date: 2020/8/9
 * Time: 0:52
 */

namespace App\Constants;

class ReturnCode
{
    const SUCCESS = [10000,  'SUCCESS'];

    const INVALID_PARAM = [40000, '请求参数错误'];
    const UNSUPPORTED_HTTP_METHOD = [40001, 'HTTP请求方法错误'];
    const DATA_CONSTRAINT_ERROR = [40002, '数据约束错误'];
    const DATA_NOT_FOUND = [40003,'数据不存在'];

    const NULL_ACCESS_TOKEN = [40300, '授权标识为空'];
    const ACCESS_TOKEN_EXPIRE = [40301, '授权标识已过期'];
    const ACCESS_TOKEN_REJECTED = [40302, '授权标识已拒绝'];

    const UNDEFINED = [50000, '未知错误'];
}
