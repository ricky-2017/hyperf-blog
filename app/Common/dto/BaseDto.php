<?php
/**
 * Created by PhpStorm.
 * User: rubio
 * Date: 2021/3/30
 * Time: 11:51
 */

namespace App\Common\dto;

class BaseDto
{
    private $arrayData = [];

    public static function fromRequest()
    {
        $param = make('Hyperf\HttpServer\Request')->all();

//        if ($name !== ".") {
//            if (empty($param[$name]) || !is_array($param[$name])) {
//                return;
//            }
//
//            $param = $param[$name];
//        }

//        if (!is_array($param)) {
//            return;
//        }

        // 静态延迟绑定
        $object = new static();
        // 反射获取类
        $class = new \ReflectionClass($object);

        foreach ($param as $key => $value) {
            $parsedKey = parse_name($key, 1, false);
            $setterName = "set" . parse_name($key, 1, true);
            if (!method_exists($object, $setterName)) {
                continue;
            };

            $property = $class->getProperty($parsedKey);
            $doc = $property->getDocComment();
            preg_match('/@var\s*([^\s]*)/i', $doc, $matches);
            if (is_array($matches) && count($matches) > 0 && class_exists($matches[0])) {
                $objectProperty = new $matches[0]();
                if (method_exists($objectProperty, "fromRequest")) {
                    $value = $objectProperty::fromRequest($key . "/a");
                }
            }

            $object->$setterName($value);
            $object->arrayData[$key] = $value;
        }

        return $object;
    }

    public function toArray($excludeFalse = false)
    {
        return $excludeFalse && !empty($this->arrayData) ? array_filter($this->arrayData) : $this->arrayData;
    }

    public static function __make()
    {
        return self::fromRequest();
    }

    public function offsetExists($offset)
    {
        return isset($this->arrayData[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->arrayData[$offset];
    }

    public function offsetSet($offset, $value)
    {
        $this->arrayData[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->arrayData[$offset]);
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->arrayData);
    }

    public function keys()
    {
        return array_keys($this->arrayData);
    }

    public function values()
    {
        return array_values($this->arrayData);
    }

    public function has($key)
    {
        return array_key_exists($key, $this->arrayData);
    }

    public function toJson($options = JSON_UNESCAPED_UNICODE)
    {
        return json_encode($this->arrayData, $options);
    }

    public function __toString()
    {
        return $this->toJson();
    }
}