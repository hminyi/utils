<?php

namespace Zsirius\Utils;

/**
 * 通用验证类
 */
class Validator
{
    /**
     * 检查邮件地址
     * @param  string $email 邮件地址字符串
     * @return bool
     */
    public static function checkEmail($email)
    {
        $status = false;
        $email = trim($email);
        if ($email != '' && strstr($email, '@') && strstr($email, '.')) {
            $pattern = '/([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)/';
            $status = preg_match($pattern, $email) ? true : false;
        }
        return $status;
    }

    /**
     * 检查字符串
     * @param  string $str 待查字符串
     * @param  int    $min 最小长度
     * @param  int    $max 最大长度($max=0则不作长度检查)
     * @return bool
     */
    public static function checkStr($str, $min = 0, $max = 0, $isUtf8 = false)
    {
        $status = false;
        if ($str != '' && is_string($str)) {
            if ($max > 0) {
                $len = $isUtf8 ? String::strlenUtf8($str) : strlen($str);
                $status = ($len >= $min && $len <= $max) ? true : false;
            } else {
                $status = true;
            }
        }
        return $status;
    }

    /**
     * 检查整数
     * @param  int    $num 待查整数
     * @param  int    $min 最小值
     * @param  int    $max 最大值(如果没有值范围限制，此处传0过来)
     * @return bool
     */
    public static function checkInt($num, $min = 0, $max = 0)
    {
        $status = false;
        // 考虑到表单传过来整数需要转换等情况，故用此种方式
        if (is_numeric($num)) {
            $num += 0;
            if (is_int($num)) {
                if ($max > 0) {
                    $status = ($num >= $min && $num <= $max) ? true : false;
                } else {
                    $status = true;
                }
            }
        }
        return $status;
    }

    /**
     * 检查使用HTTP协议的网址
     * @param  string $str 待查url
     * @return bool
     */
    public static function checkHttp($str)
    {
        $status = false;
        $str = trim($str);
        if ($str != '') {
            $pattern = '/^http:\/\/[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\':+!]*([^<>\"\"])*$/';
            $status = preg_match($pattern, $str) ? true : false;
        }
        return $status;
    }

    /**
     * 检查电话号码
     * @param  string $str 待查电话号码(正确格式010-87786632或(010)32678878或32678878)
     * @return bool
     */
    public static function checkTel($str)
    {
        $status = false;
        $str = trim($str);
        if ($str != '') {
            $pattern = '/^[+]{0,1}[\(]?(\d){1,3}[\)]?[ ]?([-]?((\d)|[ ]){1,12})+$/';
            $status = preg_match($pattern, $str) ? true : false;
        }
        return $status;
    }

    /**
     * 检查手机号码
     * @param  string $str 待查手机号码
     * @return bool
     */
    public static function checkMobile($str)
    {
        $status = false;
        $str = trim($str);
        if ($str != '') {
            $pattern = '/^((\(\d{3}\))|(\d{3}\-))?1[3,4,5,7,8]\d{9}$/';
            $status = preg_match($pattern, $str) ? true : false;
            if ($status === false) {
                $pattern = '/^09\d{8}$/';
                $status = preg_match($pattern, $str) ? true : false;
            }
            if ($status === false) {
                $pattern = '/^00852\d{8}$/';
                $status = preg_match($pattern, $str) ? true : false;
            }
        }
        return $status;
    }

    /**
     * 检查邮编
     * @param  string $str 待查邮编
     * @return bool
     */
    public static function checkZipCode($str)
    {
        $status = false;
        $str = trim($str);
        if ($str != '') {
            $pattern = '/^[0-9]\d{5}$/';
            $status = preg_match($pattern, $str) ? true : false;
        }
        return $status;
    }

    /**
     * 检查日期是否正确
     * @param  string $str 日期(格式：2007-04-25)
     * @return bool
     */
    public static function checkDate($str)
    {
        $status = false;
        $str = trim($str);
        if (preg_match('/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$/', $str)) {
            $dateArr = explode('-', $str);
            $status = (checkdate($dateArr[1], $dateArr[2], $dateArr[0])) ? true : false;
        }
        return $status;
    }

    /**
     * 检查开始日期是否小于结束日期
     * @param  string $begin 开始日期(格式：2007-04-25)
     * @param  string $end   结束日期(格式：2007-04-28)
     * @return bool
     */
    public static function checkDateRange($begin, $end)
    {
        $status = false;
        if (self::checkDate($begin) && self::checkDate($end)) {
            $status = ((strtotime($end) - strtotime($begin)) > 0) ? true : false;
        }
        return $status;
    }

    /**
     * 检查开始时间是否小于结束时间
     * @param  string $begin 开始日期(格式：2007-04-25 10:00:00)
     * @param  string $end   结束日期(格式：2007-04-28 10:00:00)
     * @return bool
     */
    public static function checkDatetimeRange($begin, $end)
    {
        $status = false;
        if (self::checkDatetime($begin) && self::checkDatetime($end)) {
            $status = ((strtotime($end) - strtotime($begin)) > 0) ? true : false;
        }
        return $status;
    }

    /**
     * 检查是否为合法金额
     * @param  string $str    金额字符串
     * @param  int    $length 整数部分的最大位数
     * @return bool
     */
    public static function checkMoney($str, $length = 8)
    {
        $status = false;
        $str = trim($str);
        if ($str != '') {
            $pattern = '/^[0-9]{1,' . $length . '}[.]{0,1}[0-9]{0,2}$/';
            $status = preg_match($pattern, $str) ? true : false;
        }
        return $status;
    }

    /**
     * 检查是否为一个合法的时间格式
     * @param  string $str 时间字符串
     * @return bool
     */
    public static function checkDatetime($str)
    {
        $status = false;
        $str = trim($str);
        if ($str != '') {
            $pattern = '/[\d]{4}-[\d]{1,2}-[\d]{1,2}\s[\d]{1,2}:[\d]{1,2}:[\d]{1,2}/';
            $status = preg_match($pattern, $str) ? true : false;
        }
        return $status;
    }

    /**
     * 检测一个宽松的日期格式
     * @param  string $str 要检查的日期字符串，如：2008,2008-08,2008-08-08或2008.8.8等等
     * @return bool
     */
    public static function checkLooseDate($str)
    {
        $status = false;
        $str = trim($str);
        if ($str != '') {
            $pattern = '/[\d]{4}|[\d]{4}[-\/\.][\d]{1,2}|[\d]{4}[-\/\.][\d]{1,2}[-\/\.][\d]{1,2}/';
            $status = preg_match($pattern, $str) ? true : false;
        }
        return $status;
    }

    /**
     * 判断是否是浮点数
     * @param  string $str 要检查的值
     * @param  int    $num 小数点位数
     * @param  int    $max 整数位最大长度
     * @return bool
     */
    public static function checkFloat($str, $num, $max = 5)
    {
        $status = false;
        $str = trim($str);
        if ($str != '') {
            $pattern = '/^[0-9]{1,' . $max . '}[\.]{0,1}[0-9]{0,' . $num . '}$/';
            $status = preg_match($pattern, $str) ? true : false;
        }
        return $status;
    }

    /**
     * 身份证号码验证
     * @param string $str 身份证号码
     * @param bool
     */
    public static function checkIdCard($str)
    {
        //加权因子
        $wi = [7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2];
        //校验码串
        $ai = ['1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2'];
        $sigma = 0;
        //按顺序循环处理前17位
        for ($i = 0; $i < 17; $i++) {
            //提取前17位的其中一位，并将变量类型转为实数
            $b = (int) $str{$i};
            //提取相应的加权因子
            $w = $wi[$i];

            //把从身份证号码中提取的一位数字和加权因子相乘，并累加
            $sigma += $b * $w;
        }
        //计算序号
        $str = $sigma % 11;

        //按照序号从校验码串中提取相应的字符。
        $check_str = $ai[$str];

        if ($str{17} == $check_str) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 中文名验证
     * @param  string $name 用户姓名
     * @return bool
     */
    public static function checkChineseName($str)
    {
        $status = false;
        $str = trim($str);
        if ($str != '') {
            $pattern = '/^([\xe4-\xe9][\x80-\xbf]{2}){2,4}$/';
            $status = preg_match($pattern, $str) ? true : false;
        }
        return $status;
    }

    /**
     * 判断一个url是否是图片链接
     * @param  string $img_url 图片链接
     * @return bool
     */
    public static function checkImgUrl($img_url)
    {
        if (!preg_match('/.*(\.png|\.jpg|\.jpeg|\.gif)$/', $img_url)) {
            return true;
        } else {
            $header = get_headers($img_url, 1);
            if (!empty($header['Content-Type'])) {
                if (strstr($header['Content-Type'], 'image/')) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * 判断是否为合法的ip地址
     * @param  string $ip ip地址
     * @return bool
     */
    public static function checkIP($ip)
    {
        $ipv4Regex = '/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/';
        $ipv6Regex = '/^(((?=.*(::))(?!.*\3.+\3))\3?|([\dA-F]{1,4}(\3|:\b|$)|\2))(?4){5}((?4){2}|(((2[0-4]|1\d|[1-9])?\d|25[0-5])\.?\b){4})$/i';

        if (preg_match($ipv4Regex, $ip)) {
            return 4;
        }

        if (false !== strpos($ip, ':') && preg_match($ipv6Regex, trim($ip, ' []'))) {
            return 6;
        }

        return false;
    }

    /**
     * 验证是否是 json 字符串
     * @param  string $str 字符串
     * @return bool
     */
    public static function checkJson($str)
    {
        json_decode($str);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    /**
     * 判断是否为手机访问
     * @return bool
     */
    public static function isMobile()
    {
        $sp_is_mobile = false;

        if (empty($_SERVER['HTTP_USER_AGENT'])) {
            $sp_is_mobile = false;
        } elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile') !== false// many mobile devices (all iPhone, iPad, etc.)
             || strpos($_SERVER['HTTP_USER_AGENT'], 'Android') !== false
            || strpos($_SERVER['HTTP_USER_AGENT'], 'Silk/') !== false
            || strpos($_SERVER['HTTP_USER_AGENT'], 'Kindle') !== false
            || strpos($_SERVER['HTTP_USER_AGENT'], 'BlackBerry') !== false
            || strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mini') !== false
            || strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mobi') !== false) {
            $sp_is_mobile = true;
        } else {
            $sp_is_mobile = false;
        }

        return $sp_is_mobile;
    }

    /**
     * 判断是否为微信访问
     * @return bool
     */
    public static function isWeiXin()
    {
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
            return true;
        }
        return false;
    }

    /**
     * 判断当前协议是否为HTTPS
     * @return bool
     */
    public static function isHttps()
    {
        if (!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off') {
            return true;
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
            return true;
        } elseif (!empty($_SERVER['HTTP_FRONT_END_HTTPS']) && strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) !== 'off') {
            return true;
        }
        return false;
    }
}
