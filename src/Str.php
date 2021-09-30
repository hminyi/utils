<?php

namespace Zsirius\Utils;

/**
 * 字符串类
 */
class Str
{
    /**
     * @var array
     */
    protected static $snakeCache = [];
    /**
     * @var array
     */
    protected static $camelCache = [];
    /**
     * @var array
     */
    protected static $studlyCache = [];

    /**
     * 格式化字节大小
     *
     * @param  int      $size      字节数
     * @param  string   $delimiter 数字和单位分隔符
     * @return string
     */
    public static function formatBytes($size, $delimiter = '')
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
        for ($i = 0; $size >= 1024 && $i < 5; $i++) {
            $size /= 1024;
        }

        return round($size, 2) . $delimiter . $units[$i];
    }

    /**
     * 过滤emoji表情
     *
     * @param  string              $text
     * @return null|string|array
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
     * 删除字符串两端的字符串
     *
     * @param  string   $str    字符串
     * @param  string   $remove 删除字符串
     * @return string
     */
    public static function trim($str, $remove, $mode = 'all')
    {
        switch ($mode) {
            case 'left':
                return self::ltrim($str, $remove);
                break;
            case 'right':
                return self::rtrim($str, $remove);
                break;
            case 'all':
                return self::rtrim(self::ltrim($str, $remove), $remove);
                break;
        }
    }

    /**
     * 删除字符串左边的字符串
     *
     * @param  string   $str    字符串
     * @param  string   $remove 删除字符串
     * @return string
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
     * 删除字符串右边的字符串
     *
     * @param  string   $str    字符串
     * @param  string   $remove 删除字符串
     * @return string
     */
    public static function rtrim($str, $remove)
    {
        if (!$str || !$remove) {
            return $str;
        }
        while (substr($str, -strlen($remove)) == $remove) {
            $str = substr($str, 0, -strlen($remove));
        }
        return $str;
    }

    /**
     * 检查字符串中是否包含某些字符串
     *
     * @param  string       $haystack
     * @param  string|array $needles
     * @return bool
     */
    public static function contains($haystack, $needles)
    {
        foreach ((array) $needles as $needle) {
            if ($needle != '' && mb_strpos($haystack, $needle) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * 检查字符串是否以某些字符串结尾
     *
     * @param  string       $haystack
     * @param  string|array $needles
     * @return bool
     */
    public static function endsWith($haystack, $needles)
    {
        foreach ((array) $needles as $needle) {
            if ((string) $needle === static::substr($haystack, -static::length($needle))) {
                return true;
            }
        }

        return false;
    }

    /**
     * 检查字符串是否以某些字符串开头
     *
     * @param  string       $haystack
     * @param  string|array $needles
     * @return bool
     */
    public static function startsWith($haystack, $needles)
    {
        foreach ((array) $needles as $needle) {
            if ($needle != '' && mb_strpos($haystack, $needle) === 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * 字符串转小写
     *
     * @param  string   $value
     * @return string
     */
    public static function lower($value)
    {
        return mb_strtolower($value, 'UTF-8');
    }

    /**
     * 字符串转大写
     *
     * @param  string   $value
     * @return string
     */
    public static function upper($value)
    {
        return mb_strtoupper($value, 'UTF-8');
    }

    /**
     * 获取字符串的长度
     *
     * @param  string $value
     * @return int
     */
    public static function length($value)
    {
        return mb_strlen($value);
    }

    /**
     * 截取字符串
     *
     * @param  string   $string
     * @param  int      $start
     * @param  int|null $length
     * @return string
     */
    public static function substr($string, $start, $length = null)
    {
        return mb_substr($string, $start, $length, 'UTF-8');
    }

    /**
     * 驼峰转下划线
     *
     * @param  string   $value
     * @param  string   $delimiter
     * @return string
     */
    public static function snake($value, $delimiter = '_')
    {
        $key = $value;

        if (isset(static::$snakeCache[$key][$delimiter])) {
            return static::$snakeCache[$key][$delimiter];
        }

        if (!ctype_lower($value)) {
            $value = preg_replace('/\s+/u', '', $value);

            $value = static::lower(preg_replace('/(.)(?=[A-Z])/u', '$1' . $delimiter, $value));
        }

        return static::$snakeCache[$key][$delimiter] = $value;
    }

    /**
     * 下划线转驼峰(首字母小写)
     *
     * @param  string   $value
     * @return string
     */
    public static function camel($value)
    {
        if (isset(static::$camelCache[$value])) {
            return static::$camelCache[$value];
        }

        return static::$camelCache[$value] = lcfirst(static::studly($value));
    }

    /**
     * 下划线转驼峰(首字母大写)
     *
     * @param  string   $value
     * @return string
     */
    public static function studly($value)
    {
        $key = $value;

        if (isset(static::$studlyCache[$key])) {
            return static::$studlyCache[$key];
        }

        $value = ucwords(str_replace(['-', '_'], ' ', $value));

        return static::$studlyCache[$key] = str_replace(' ', '', $value);
    }

    /**
     * 转为首字母大写的标题格式
     *
     * @param  string   $value
     * @return string
     */
    public static function title($value)
    {
        return mb_convert_case($value, MB_CASE_TITLE, 'UTF-8');
    }

    /**
     * 生成订单号
     *
     * @return string
     */
    public static function orderSn()
    {
        $str = date('YmdH') . substr(implode('', array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
        return substr($str, 2);
    }
}
