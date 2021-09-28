<?php

namespace Zsirius\Utils;

class Date
{
    /**
     * 格式化 UNIX 时间戳为人易读的字符串
     *
     *
     * @param  int    Unix                          时间戳
     * @param  mixed  $local                        本地时间
     * @return string 格式化的日期字符串
     */
    public static function human($remote, $local = null)
    {
        $time_diff = (is_null($local) || $local ? time() : $local) - $remote;
        $tense = $time_diff < 0 ? '后' : '前';
        $time_diff = abs($time_diff);
        $chunks = [
            [60 * 60 * 24 * 365, '年'],
            [60 * 60 * 24 * 30, '月'],
            [60 * 60 * 24 * 7, '周'],
            [60 * 60 * 24, '天'],
            [60 * 60, '小时'],
            [60, '分钟'],
            [1, '秒'],
        ];
        $name = 'second';
        $count = 0;

        for ($i = 0, $j = count($chunks); $i < $j; $i++) {
            $seconds = $chunks[$i][0];
            $name = $chunks[$i][1];
            if (($count = floor($time_diff / $seconds)) != 0) {
                break;
            }
        }
        return $count . $name . $tense;
    }

    /**
     * 比较2个日期相差多少天
     * @param  $date1
     * @param  $date2
     * @return float
     */
    public static function computeDateDifferent($date1, $date2)
    {
        $timestamp1 = strtotime($date1);
        $timestamp2 = strtotime($date2);

        $diffTime = abs($timestamp2 - $timestamp1);
        $days = $diffTime / (24 * 3600);
        return $days;
    }

    /**
     * 比较2个小时相差多少时
     * @param  $hour1
     * @param  $hour2
     * @return mixed
     */
    public static function computeHoursDifference($hour1, $hour2)
    {
        $diffTime = $hour2 - $hour1;
        return $diffTime;
    }

    /**
     * 获取日期的周数
     * @param  $date
     * @return bool
     */
    public static function getWeekOfDate($date)
    {
        if (empty($date)) {
            return false;
        }
        $weeks = ['日', '一', '二', '三', '四', '五', '六'];
        $position = date('w', strtotime($date));
        return $weeks[$position];
    }

    /**
     * 是合法的日期
     * @param  $date
     * @return bool
     */
    public static function isLegalDate($date)
    {
        return strtotime($date) === false ? false : true;
    }

    /**
     * 获取本月日期
     * @return array
     */
    public static function getThisMonth()
    {
        $firstDays = date('Y-m-d H:i:s', mktime(0, 0, 0, date('m'), 1, date('Y')));
        $lastDays = date('Y-m-d H:i:s', mktime(23, 59, 59, date('m'), date('t'), date('Y')));

        return [$firstDays, $lastDays];
    }

    /**
     * 获取上个月
     * @return array
     */
    public static function getLastMonth()
    {
        $firstDays = date('Y-m-d H:i:s', mktime(0, 0, 0, date('m') - 1, 1, date('Y')));
        $lastDays = date('Y-m-d H:i:s', mktime(23, 59, 59, date('m'), 0, date('Y')));

        return [$firstDays, $lastDays];
    }

    /**
     * 获取今年
     * @return array
     */
    public static function getThisYear()
    {
        $firstDays = date('Y-m-d H:i:s', mktime(0, 0, 0, 1, 1, date('Y')));
        $lastDays = date('Y-m-d H:i:s', mktime(23, 59, 59, date('m'), date('t'), date('Y')));

        return [$firstDays, $lastDays];
    }

    /**
     * 获取去年
     * @return array
     */
    public static function getLastYear()
    {
        $firstDays = date('Y-m-d H:i:s', mktime(0, 0, 0, 1, 1, date('Y') - 1));
        $lastDays = date('Y-m-d H:i:s', mktime(23, 59, 59, 1, 1, date('Y')));

        return [$firstDays, $lastDays];
    }

    /**
     * 获取毫秒时间戳
     * @author nipeiquan
     *
     * @return mixed|string 返回类型
     */
    public static function getMillisecond()
    {
        //获取毫秒的时间戳
        $time = explode(' ', microtime());
        $time = $time[1] . ($time[0] * 1000);
        $time2 = explode('.', $time);
        $time = $time2[0];
        return $time;
    }
}
