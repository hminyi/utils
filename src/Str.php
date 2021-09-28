<?php

namespace Zsirius\Utils;

/**
 * 字符串类
 */
class Str
{
    /**
     * 格式化字节大小
     * @param  number $size                               字节数
     * @param  string $delimiter                          数字和单位分隔符
     * @return string 格式化后的带单位的大小
     */
    public function formatBytes($size, $delimiter = '')
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
        for ($i = 0; $size >= 1024 && $i < 5; $i++) {
            $size /= 1024;
        }

        return round($size, 2) . $delimiter . $units[$i];
    }

    /**
     * 过滤emoji表情
     * @param  string                 $text
     * @return null|string|string[]
     */
    public static function filterEmoji($text)
    {
        if (!is_string($text)) {
            return $text;
        }
        // 此处的preg_replace用于过滤emoji表情
        // 如需支持emoji表情, 需将mysql的编码改为utf8mb4
        return preg_replace('/[\xf0-\xf7].{3}/', '', $text);
    }

    /**
     * 下划线转驼峰
     * @param  string   $uncamelized_words
     * @param  string   $separator
     * @return string
     */
    public static function camelize($uncamelized_words, $separator = '_')
    {
        $uncamelized_words = $separator . str_replace($separator, ' ', strtolower($uncamelized_words));
        return ltrim(str_replace(' ', '', ucwords($uncamelized_words)), $separator);
    }

    /**
     * 驼峰转下划线
     * @param  string   $camelCaps
     * @param  string   $separator
     * @return string
     */
    public static function uncamelize($camelCaps, $separator = '_')
    {
        return strtolower(preg_replace('/([a-z])([A-Z])/', '$1' . $separator . '$2', $camelCaps));
    }

    /**
     * 删除字符串两端的字符串
     * 
     * @param $str
     * @param $remove
     */
    public static function trim($str, $remove)
    {
        return self::rtrim(self::ltrim($str, $remove), $remove);
    }

    /**
     * @param  $str
     * @param  $remove
     * @return mixed
     */
    public static function ltrim($str, $remove)
    {
        if (!$str || !$remove) {
            return $str;
        }
        while (substr($str, 0, strlen($remove)) == $remove) {
            $str = substr($str, strlen($remove));
        }
        return $str;
    }

    /**
     * @param  $str
     * @param  $remove
     * @return mixed
     */
    public static function rtrim($str, $remove)
    {
        if (!$str || !$remove) {
            return $str;
        }
        while (substr($str, -strlen($remove)) == $remove) {
            $str = substr($str, 0, -strlen($remove));
            echo $str;
        }
        return $str;
    }

    /**
     * 生成订单号
     * @return string
     */
    public static function sn()
    {
        $orderstr = date('YmdH') . substr(implode('', array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
        return substr($orderstr, 2);
    }
}
