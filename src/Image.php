<?php

namespace Zsirius\Utils;

/**
 * 图片处理类
 */
class Image
{
    /**
     * Base64生成图片文件,自动解析格式
     * 
     * @param  string  $base64   可以转成图片的base64字符串
     * @param  string  $path     绝对路径
     * @param  string  $filename 生成的文件名
     * @return array
     */
    public static function base64ToImage($base64, $path, $filename)
    {
        // 匹配base64字符串格式
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64, $result)) {
            // 保存最终的图片格式
            $postfix = $result[2];
            $base64 = base64_decode(substr(strstr($base64, ','), 1));
            $filename .= '.' . $postfix;
            $path .= $filename;
            //创建图片
            if (file_put_contents($path, $base64)) {
                return $filename;
            }
        }
        return false;
    }

    /**
     * 将图片转成base64字符串
     * 
     * @param  string   $filename 图片地址
     * @return string
     */
    public static function imageToBase64($filename = '')
    {
        $base64 = '';
        if (file_exists($filename)) {
            if ($fp = fopen($filename, 'rb', 0)) {
                $img = fread($fp, filesize($filename));
                fclose($fp);
                $base64 = 'data:image/jpg/png/gif;base64,' . chunk_split(base64_encode($img));
            }
        }
        return $base64;
    }
}
