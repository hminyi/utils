<?php

namespace Zsirius\Utils;

/**
 * 数组类
 */
class Arr
{
    /**
     * 获取数组中指定的列
     * @param  array   $source 数组
     * @param  string  $column 键名
     * @return array
     */
    public static function column($source, $column)
    {
        $columnArr = [];
        foreach ($source as $item) {
            $columnArr[] = $item[$column];
        }
        return $columnArr;
    }

    /**
     * 获取数组中指定的列 [支持多列]
     * @param  array   $source  数组
     * @param  array   $columns 列数组
     * @return array
     */
    public static function columns($source, $columns)
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
     * @param  array   $source 数组
     * @param  string  $index  键值
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
     * @param  array   $array1 数组1
     * @param  array   $array2 数组2
     * @return array
     */
    public static function multiMerge($array1, $array2)
    {
        $merge = $array1 + $array2;
        $data = [];
        foreach ($merge as $key => $val) {
            if (isset($array1[$key]) && is_array($array1[$key]) && isset($array2[$key]) && is_array($array2[$key])) {
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
     * @param  array   $array 多维数组
     * @return array
     */
    public static function multi2single($array)
    {
        /**
         * @var array
         */
        static $resultArr = [];
        foreach ($array as $v) {
            if (is_array($v)) {
                self::multi2single($v);
            } else {
                $resultArr[] = $v;
            }
        }
        return $resultArr;
    }

    /**
     * 将obj深度转化成array
     * @param  obj|array $obj 要转换的数据
     * @return array
     */
    public static function obj2arr(&$object)
    {
        if (is_object($object)) {
            $arr = (array) ($object);
        } else {
            $arr = &$object;
        }
        if (is_array($arr)) {
            foreach ($arr as $varName => $varValue) {
                $arr[$varName] = self::obj2arr($varValue);
            }
        }
        return $arr;
    }

    /**
     * 二维数组排序
     * @param  array   $array 数组
     * @param  string  $keys  根据键值
     * @param  string  $type  升序降序
     * @return array
     */
    public static function multi2sort($array, $keys, $type = 'desc')
    {
        $keyValue = $newArray = [];
        foreach ($array as $k => $v) {
            $keyValue[$k] = $v[$keys];
        }
        if ($type == 'asc') {
            asort($keyValue);
        } else {
            arsort($keyValue);
        }
        reset($keyValue);
        foreach ($keyValue as $k => $v) {
            $newArray[$k] = $array[$k];
        }
        return $newArray;
    }

    /**
     * 字符串转换为数组
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
     * 数组转换为字符串
     * @param  array    $array 要连接的数组
     * @param  string   $glue  分割符
     * @return string
     */
    public static function arr2str($array = [], $glue = ',')
    {
        if (empty($array)) {
            return '';
        } else {
            return implode($glue, $array);
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
        $badResult = json_decode($json, true); // value，一个字段多次出现，结果中的value是数组
        foreach ($badResult as $k => $v) {
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
     * 两个数组的笛卡尔积
     * @param  array   $array1 数组1
     * @param  array   $array2 数组2
     * @return array
     */
    private function combine($array1, $array2)
    {
        $result = [];
        foreach ($array1 as $item1) {
            foreach ($array2 as $item2) {
                $temp = $item1;
                $temp[] = $item2;
                $result[] = $temp;
            }
        }
        return $result;
    }

    /**
     * 多个数组的笛卡尔积
     * @return array
     */
    public static function multiCombine()
    {
        $data = func_get_args();
        $cnt = count($data);
        $result = [];
        foreach ($data[0] as $item) {
            $result[] = [$item];
        }
        for ($i = 1; $i < $cnt; $i++) {
            $result = self::combine($result, $data[$i]);
        }
        return $result;
    }
}
