<?php

namespace Zsirius\Utils;

/**
 * 坐标转换工具
 */
class GeoTrans
{
    /**
     * @var float
     */
    private static $pi = 3.14159265358979324;
    /**
     * @var float
     */
    private static $earth_radius = 6378.137;

    /**
     * 获取两个坐标之间的距离
     *
     * @param  float       $lng1     经度1
     * @param  float       $lat1     维度1
     * @param  float       $lng2     经度2
     * @param  float       $lat2     维度2
     * @param  int         $len_type 返回结果类型（1千米, 2公里）默认千米
     * @param  int         $decimal  保留小数位数（默认2）
     * @return int|float
     */
    public static function getDistance($lng1, $lat1, $lng2, $lat2, $len_type = 1, $decimal = 2)
    {
        $radLat1 = $lat1 * self::$pi / 180.0;
        $radLat2 = $lat2 * self::$pi / 180.0;
        $a = $radLat1 - $radLat2;
        $b = ($lng1 * self::$pi / 180.0) - ($lng2 * self::$pi / 180.0);
        $s = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2)));
        $s = $s * self::$earth_radius;
        if ($len_type == 1) {
            $s = round($s * 1000);
        }
        return round($s, $decimal);
    }
}
