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



/**
 * 将数组的下标按要求格式返回
 *
 * @param array $data 需要格式化的数组
 * @param string $type 返回下标字符串格式，可选范围 hump、underline
 * @return array 格式化后的数组
 */
if (!function_exists('arrayKeyTrans')){
    function arrayKeyTrans($data = [], $type = 'hump')
    {
        $fun = 'hump' == $type ? 'lineToHump' : 'humpToLine';
        $newData = [];

        foreach ($data as $key => $val) {

            // 递归全部改变
            if(is_array($val))
            {
                $val = arrayKeyTrans($val, $type);
            }

            $newKey = $fun($key);
            $newData[$newKey] = $val;
        }

        return $newData;
    }
}

/**
 *
 * 下划线式字符串转成驼锋式字符串
 *
 * @param string $str 待格式化字符串
 * @return null|string|string[]
 */
if (!function_exists('lineToHump')) {

    function lineToHump($str = '')
    {
        $str = preg_replace_callback('/([-_]+([a-z]{1}))/i', function ($matches) {
            return strtoupper($matches[2]);
        }, $str);
        return $str;
    }
}
/**
 * 驼锋式字符串转成下划线式字符串
 *
 * @param string $str 待格式化字符串
 * @return null|string|string[]
 */
if (!function_exists('humpToLine')) {

    function humpToLine($str = '')
    {
        $str = preg_replace_callback('/([A-Z]{1})/', function ($matches) {
            return '_' . strtolower($matches[0]);
        }, $str);
        return $str;
    }
}

/**
 * 明文密码哈希
 *
 * @param  string $password         明文密码
 * @return array  password和salt
 */

if (!function_exists('cb_encrypt'))
{
    function cb_encrypt($password) {
        $salt = password_hash('mypassword', PASSWORD_BCRYPT, ['cost' => 10]);
        $password = md5($password . $salt);
        return [
            'password' => $password,
            'salt' => $salt,
        ];
    }
}

/**
 * 密码比对
 *
 * @param  string $hash          哈希值
 * @param  string $salt          盐
 * @param  string $password      明文密码
 * @return void   一致为真
 */
if (!function_exists('cb_passwordEqual'))
{
    function cb_passwordEqual($hash, $salt, $password) {
        $new_hash = md5($password . $salt);
        if (hash_equals($hash, $new_hash)) {
            return true;
        }
        return false;
    }
}

/**
 * 创建63进制的唯一id
 * @method create_id
 * @return [type]    [description]
 */
if (!function_exists('create_id'))
{
    function create_id() {
        return decTo63(base_convert(md5(uniqid()), 16, 10));
    }
}


/**
 * 十进制的字符串转63进制
 * @method decTo63
 * @param  [type]  $str [description]
 * @return [type]       [description]
 */
if (!function_exists('decTo63')) {
    function decTo63($str)
    {
        $array63 = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
            'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l',
            'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L',
            'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');

        $ayyayLen = count($array63);
        $result = '';
        $quotient = $str;
        $divisor = $str;
        $flag = true;
        while ($flag) {
            $len = strlen($divisor);
            $pos = 1;
            $quotient = 0;
            $div = substr($divisor, 0, 2);
            $remainder = $div[0];
            while ($pos < $len) {
                $div = $remainder == 0 ? $divisor[$pos] : $remainder . $divisor[$pos];
                $remainder = $div % $ayyayLen;
                $quotient = $quotient . floor($div / $ayyayLen);
                $pos++;
            }
            $quotient = trim_left_zeros($quotient);
            $divisor = "$quotient";
            $result = $array63[$remainder] . $result;
            if (strlen($divisor) <= 2) {
                if ($divisor < $ayyayLen - 1) {
                    $flag = false;
                }
            }
        }
        $result = $array63[$quotient] . $result;
        $result = trim_left_zeros($result);
        return $result;
    }
}

if( !function_exists('trim_left_zeros'))
{
    function trim_left_zeros($str)
    {
        $str = ltrim($str, '0');
        if (empty($str)) {
            $str = '0';
        }
        return $str;
    }
}

if ( !function_exists('parse_name'))
{
    /**
     * 字符串命名风格转换
     * type 0 将Java风格转换为C的风格 1 将C风格转换为Java的风格
     * @param string  $name 字符串
     * @param integer $type 转换类型
     * @param bool    $ucfirst 首字母是否大写（驼峰规则）
     * @return string
     */
    function parse_name($name, $type = 0, $ucfirst = true)
    {
        if ($type) {
            $name = preg_replace_callback('/_([a-zA-Z])/', function ($match) {
                return strtoupper($match[1]);
            }, $name);

            return $ucfirst ? ucfirst($name) : lcfirst($name);
        } else {
            return strtolower(trim(preg_replace("/[A-Z]/", "_\\0", $name), "_"));
        }
    }
}