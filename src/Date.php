<?php

namespace Zsirius\Utils;

class Date
{
    /**
     * 格式化 UNIX 时间戳
     * @param  int      $remote 时间戳
     * @param  mixed    $local  本地时间
     * @return string
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
     * 今日日期
     *
     * @return string
     */
    public static function today()
    {
        return date('Y-m-d');
    }

    /**
     * 昨日日期
     *
     * @return string
     */
    public static function yesterday()
    {
        return date('Y-m-d', strtotime('-1 day'));
    }

    /**
     * 本周开始和结束日期
     *
     * @return array
     */
    public static function thisWeek()
    {
        $N = date('N');
        $d = 7 - $N;
        $sta = date('Y-m-d', strtotime("-{$N} day"));
        $end = date('Y-m-d', strtotime("+{$d} day"));

        return [$sta, $end];
    }

    /**
     * 本周所有日期
     *
     * @return array
     */
    public static function thisWeeks()
    {
        $N = date('N') - 1;
        $mon = strtotime("-{$N} day");
        $weeks = [];

        for ($i = 0; $i < 7; $i++) {
            $date = '';
            $date = date('Y-m-d', strtotime("+{$i} day", $mon));

            $weeks[] = $date;
        }

        return $weeks;
    }

    /**
     * 上周开始和结束日期
     *
     * @return array
     */
    public static function lastWeek()
    {
        $week = date('Y-m-d', strtotime('-1 week'));
        $time = strtotime($week);
        $N = date('N', $time);
        $d = 7 - $N;
        $N = $N - 1;
        $sta = date('Y-m-d', strtotime("-{$N} day", $time));
        $end = date('Y-m-d', strtotime("+{$d} day", $time));

        return [$sta, $end];
    }

    /**
     * 上周所有日期
     *
     * @return array
     */
    public static function lastWeeks()
    {
        $week = date('Y-m-d', strtotime('-1 week'));
        $time = strtotime($week);
        $N = date('N', $time);
        $N = $N - 1;
        $mon = strtotime("-{$N} day", $time);

        $weeks = [];
        for ($i = 0; $i < 7; $i++) {
            $date = '';
            $date = date('Y-m-d', strtotime("+{$i} day", $mon));

            $weeks[] = $date;
        }

        return $weeks;
    }

    /**
     * 月份所有日期
     * @param  string  $month 月份
     * @return array
     */
    public static function monthDate($month = 'thismonth')
    {
        if ($month == 'thismonth') {
            $month = date('Y-m');
        }

        if ($month == 'lastmonth') {
            $month = date('Y-m', strtotime('-1 month'));
        }

        $t = date('t', strtotime($month));
        $time = strtotime($month);

        $dates = [];
        for ($i = 0; $i < $t; $i++) {
            $date = '';
            $date = date('Y-m-d', strtotime("+{$i} day", $time));

            $dates[] = $date;
        }

        return $dates;
    }

    /**
     * 本月开始和结束日期
     *
     * @return array
     */
    public static function thisMonth()
    {
        $ym = date('Y-m');
        $t = date('t');
        $sta = $ym . '-01';
        $end = $ym . '-' . $t;

        return [$sta, $end];
    }

    /**
     * 上月开始和结束日期
     *
     * @return array
     */
    public static function lastMonth()
    {
        $m = strtotime('-1 month');
        $t = date('t', $m);
        $sta = date('Y-m', $m) . '-01';
        $end = date('Y-m', $m) . '-' . $t;

        return [$sta, $end];
    }

    /**
     * 下月开始和结束日期
     *
     * @return array
     */
    public static function nextMonth()
    {
        $m = strtotime('+1 month');
        $t = date('t', $m);
        $sta = date('Y-m', $m) . '-01';
        $end = date('Y-m', $m) . '-' . $t;

        return [$sta, $end];
    }

    /**
     * 最近天数所有日期
     * @param  int     $days 天数
     * @return array
     */
    public static function daysDate($days = 1)
    {
        $sta = strtotime("-{$days} day");
        $dates = [];

        for ($i = 0; $i < $days; $i++) {
            $date = '';
            $date = date('Y-m-d', strtotime("+{$i} day", $sta));

            $dates[] = $date;
        }

        return $dates;
    }

    /**
     * 几年前开始和结束的日期
     * @param  int     $year 几年
     * @return array
     */
    public static function year($year = 0)
    {
        $year = date('Y') - $year;
        $sta = $year . '-01-01';
        $end = $year . '-12-31';

        return [$sta, $end];
    }

    /**
     * 几天前到现在/昨日结束的日期
     * @param  int     $day 天数
     * @param  bool    $now 现在或者昨天结束日期
     * @return array
     */
    public static function dayToNow($day = 1, $now = false)
    {
        $end = date('Y-m-d');

        if (!$now) {
            $end = date('Y-m-d', strtotime('-1 day'));
        }

        $sta = date('Y-m-d', strtotime("-{$day} day"));

        return [$sta, $end];
    }

    /**
     * 两个日期间的所有日期
     * @param  string  $sta 开始日期
     * @param  string  $end 结束日期
     * @return array
     */
    public static function betweenDates($sta = '', $end = '')
    {
        $dt_sta = strtotime($sta);
        $dt_end = strtotime($end);
        $dates = [];

        while ($dt_sta <= $dt_end) {
            $dates[] = date('Y-m-d', $dt_sta);
            $dt_sta = strtotime('+1 day', $dt_sta);
        }

        return $dates;
    }

    /**
     * 几天前的日期
     * @param  int   $days 天数
     * @return int
     */
    public static function daysAgo($days = 1)
    {
        $date = date('Y-m-d', strtotime("-{$days} day"));

        return $date;
    }

    /**
     * 几天后的日期
     * @param  int   $days 天数
     * @return int
     */
    public static function daysAfter($days = 1)
    {
        $date = date('Y-m-d', strtotime("+{$days} day"));

        return $date;
    }

    /**
     * 天数转换成秒数
     * @param  int   $days 天数
     * @return int
     */
    public static function daysToSecond($days = 1)
    {
        return $days * 86400;
    }

    /**
     * 周数转换成秒数
     * @param  int   $week 周数
     * @return int
     */
    public static function weekToSecond($weeks = 1)
    {
        return self::daysToSecond() * 7 * $weeks;
    }

    /**
     * 日期的开始时间和结束时间
     * @param  string  $date 日期
     * @return array
     */
    public static function datetime($date = '')
    {
        if (empty($date)) {
            $date = date('Y-m-d');
        }

        $sta = $date . ' 00:00:00';
        $end = $date . ' 23:59:59';

        return [$sta, $end];
    }

    /**
     * 获取周数
     *
     * @return array
     */
    public static function weeks()
    {
        $weeks = [1 => '周一', 2 => '周二', 3 => '周三', 4 => '周四', 5 => '周五', 6 => '周六', 0 => '周日'];
        return $weeks;
    }

    /**
     * 求两个日期之间相差的天数
     * @param  string   $day1 日期1
     * @param  string   $day2 日期2
     * @return number
     */
    public static function diffBetweenTwoDays($day1, $day2)
    {
        $second1 = strtotime($day1);
        $second2 = strtotime($day2);
        return ($second2 - $second1) / 86400;
    }

    /**
     * 获取两个时间内所有的时间
     * @param  string  $sta      开始时间
     * @param  string  $end      结束时间
     * @param  int     $interval 间隔时长
     * @return array
     */
    public static function betweenTimes($sta = '', $end = '', $interval = 1)
    {
        $t_sta = strtotime(date('Y-m-d') . ' ' . $sta);
        $t_end = strtotime(date('Y-m-d') . ' ' . $end);
        $times = [];

        while ($t_sta < $t_end) {
            $start_time = $t_sta;
            $t_sta = $t_sta + $interval * 60 * 60;
            $times[] = [
                'start_time' => date('H:i', $start_time),
                'end_time'   => date('H:i', $t_sta),
            ];
        }

        return $times;
    }

    /**
     * 获取毫秒时间戳
     * @return mixed|string 返回类型
     */
    public static function microtime()
    {
        list($msec, $sec) = explode(' ', microtime());
        $microtime = (float) sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
        return $microtime;
    }
}
