<?php

namespace Zsirius\Utils;

/**
 * 数组类
 */
class Arr
{
    /**
     * 获取数组中指定的列
     * @param  $source
     * @param  $column
     * @return array
     */
    public static function getColumn($source, $column)
    {
        $columnArr = [];
        foreach ($source as $item) {
            $columnArr[] = $item[$column];
        }
        return $columnArr;
    }

    /**
     * 获取数组中指定的列 [支持多列]
     * @param  $source
     * @param  $columns
     * @return array
     */
    public static function getColumns($source, $columns)
    {
        $columnArr = [];
        foreach ($source as $item) {
            $temp = [];
            foreach ($columns as $index) {
                $temp[$index] = $item[$index];
            }
            $columnArr[] = $temp;
        }
        return $columnArr;
    }

    /**
     * 把二维数组中某列设置为key返回
     * @param  $source
     * @param  $index
     * @return array
     */
    public static function column2key($source, $index)
    {
        $data = [];
        foreach ($source as $item) {
            $data[$item[$index]] = $item;
        }
        return $data;
    }

    /**
     * 多维数组合并
     * @param  $array1
     * @param  $array2
     * @return array
     */
    public static function multiMerge($array1, $array2)
    {
        $merge = $array1 + $array2;
        $data = [];
        foreach ($merge as $key => $val) {
            if (
                isset($array1[$key])
                && is_array($array1[$key])
                && isset($array2[$key])
                && is_array($array2[$key])
            ) {
                $data[$key] = self::multiMerge($array1[$key], $array2[$key]);
            } else {
                $data[$key] = isset($array2[$key]) ? $array2[$key] : $array1[$key];
            }
        }
        return $data;
    }

    /**
     * 在二维数组中查找指定值
     * @param  array  $array     二维数组
     * @param  string $searchIdx 查找的索引
     * @param  string $searchVal 查找的值
     * @return bool
     */
    public static function search($array, $searchIdx, $searchVal)
    {
        foreach ($array as $item) {
            if ($item[$searchIdx] == $searchVal) {
                return $item;
            }
        }
        return false;
    }

    /**
     * 多维数组转化为一维数组
     * @param  $array 多维数组
     * @return array  一维数组
     */
    public static function multi2single($array)
    {
        /**
         * @var array
         */
        static $result_array = [];
        foreach ($array as $value) {
            if (is_array($value)) {
                self::multi2single($value);
            } else {
                $result_array[] = $value;
            }
        }
        return $result_array;
    }

    /**
     * 将obj深度转化成array
     * @param  obj|array $obj 要转换的数据
     * @return array
     */
    public static function obj2arr($obj)
    {
        if (is_array($obj)) {
            foreach ($obj as &$value) {
                $value = self::obj2arr($value);
            }
            return $obj;
        } elseif (is_object($obj)) {
            $obj = get_object_vars($obj);
            return self::obj2arr($obj);
        } else {
            return $obj;
        }
    }

    /**
     * 二维数组排序
     * @param  array   $arr  数组
     * @param  string  $keys 根据键值
     * @param  string  $type 升序降序
     * @return array
     */
    public static function multi2sort($arr, $keys, $type = 'desc')
    {
        $key_value = $new_array = [];
        foreach ($arr as $k => $v) {
            $key_value[$k] = $v[$keys];
        }
        if ($type == 'asc') {
            asort($key_value);
        } else {
            arsort($key_value);
        }
        reset($key_value);
        foreach ($key_value as $k => $v) {
            $new_array[$k] = $arr[$k];
        }
        return $new_array;
    }

    /**
     * 字符串转换为数组，主要用于把分隔符调整到第二个参数
     *
     * @param  string  $str  要分割的字符串
     * @param  string  $glue 分割符
     * @return array
     */
    public static function str2arr($str = '', $glue = ',')
    {
        if ($str) {
            return explode($glue, $str);
        } else {
            return [];
        }
    }

    /**
     * 数组转换为字符串，主要用于把分隔符调整到第二个参数
     *
     * @param  array    $arr  要连接的数组
     * @param  string   $glue 分割符
     * @return string
     */
    public static function arr2str($arr = [], $glue = ',')
    {
        if (empty($arr)) {
            return '';
        } else {
            return implode($glue, $arr);
        }
    }

    /**
     * 将array转换为XML格式的字符串
     * @param  array         $data
     * @throws \Exception
     * @return string
     */
    public static function arr2xml($data)
    {
        $xml = new \SimpleXMLElement('<xml/>');
        foreach ($data as $k => $v) {
            if (is_string($k) && (is_numeric($v) || is_string($v))) {
                $xml->addChild("$k", htmlspecialchars("$v"));
            } else {
                throw new \Exception('Invalid array, will not be converted to xml');
            }
        }
        return $xml->asXML();
    }

    /**
     * 将XML格式字符串转换为array
     * @param  string        $str XML格式字符串
     * @throws \Exception
     * @return array
     */
    public static function xml2arr($str)
    {
        $xml = simplexml_load_string($str, 'SimpleXMLElement', LIBXML_NOCDATA);
        $json = json_encode($xml);
        $result = [];
        $bad_result = json_decode($json, true); // value，一个字段多次出现，结果中的value是数组
        foreach ($bad_result as $k => $v) {
            if (is_array($v)) {
                if (count($v) == 0) {
                    $result[$k] = '';
                } elseif (count($v) == 1) {
                    $result[$k] = $v[0];
                } else {
                    throw new \Exception('Duplicate elements in XML. ' . $str);
                }
            } else {
                $result[$k] = $v;
            }
        }
        return $result;
    }

    /**
     * @desc 多个数组的笛卡尔积
     * @param  $data
     * @return array
     */
    public static function multiCombine()
    {
        $data = func_get_args();
        $data = current($data);
        $cnt = count($data);
        $result = [];
        $arr1 = array_shift($data);
        foreach ($arr1 as $item) {
            $result[] = [$item];
        }

        foreach ($data as $item) {
            $result = self::combine($result, $item);
        }
        return $result;
    }

    /**
     * 两个数组的笛卡尔积
     * @param  $arr1
     * @param  $arr2
     * @return array
     */
    public static function combine($arr1, $arr2)
    {
        $result = [];
        foreach ($arr1 as $item1) {
            foreach ($arr2 as $item2) {
                $temp = $item1;
                $temp[] = $item2;
                $result[] = $temp;
            }
        }
        return $result;
    }
}
