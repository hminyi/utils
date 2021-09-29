<?php

namespace Zsirius\Utils;

/**
 * 操作文件类
 */
class File
{
    /**
     * 生成目录
     * @param  string $path 目录
     * @param  int    $mode 权限
     * @return bool
     */
    public static function createDir($path, $mode = 0755)
    {
        if (is_dir($path)) {
            return true;
        }
        $path = str_replace('\\', '/', $path);
        if (substr($path, -1) != '/') {
            $path = $path . '/';
        }
        $temp = explode('/', $path);
        $cur_dir = '';
        $max = count($temp) - 1;
        for ($i = 0; $i < $max; $i++) {
            $cur_dir .= $temp[$i] . '/';
            if (@is_dir($cur_dir)) {
                continue;
            }
            @mkdir($cur_dir, $mode, true);
            @chmod($cur_dir, $mode);
        }
        return is_dir($path);
    }

    /**
     * 读取文件内容
     * @param  string   $filename 文件名
     * @return string
     */
    public static function readFile($filename)
    {
        $content = '';
        if (function_exists('file_get_contents')) {
            @$content = file_get_contents($filename);
        } else {
            if (@$fp = fopen($filename, 'r')) {
                @$content = fread($fp, filesize($filename));
                @fclose($fp);
            }
        }
        return $content;
    }

    /**
     * 写文件
     * @param  string $filename 文件名
     * @param  string $content  文件内容
     * @param  string $mode     打开方式
     * @return bool
     */
    public static function writeFile($filename, $content, $mode = 'w')
    {
        if (function_exists('file_put_contents')) {
            file_put_contents($filename, $content);
            return true;
        } else {
            if (@$fp = fopen($filename, $mode)) {
                flock($fp, 2);
                fwrite($fp, $content);
                fclose($fp);
                return true;
            }
        }
        return false;
    }

    /**
     * 删除目录
     * @param  string $dirName 原目录
     * @return bool
     */
    public static function delDir($dirName)
    {
        if (!file_exists($dirName)) {
            return false;
        }

        $dir = opendir($dirName);
        while ($fileName = readdir($dir)) {
            $file = $dirName . '/' . $fileName;
            if ($fileName != '.' && $fileName != '..') {
                if (is_dir($file)) {
                    self::delDir($file);
                } else {
                    unlink($file);
                }
            }
        }
        closedir($dir);
        return rmdir($dirName);
    }

    /**
     * 复制目录
     * @param  string $surDir 原目录
     * @param  string $toDir  目标目录
     * @return bool
     */
    public static function copyDir($surDir, $toDir)
    {
        $surDir = rtrim($surDir, '/') . '/';
        $toDir = rtrim($toDir, '/') . '/';
        if (!file_exists($surDir)) {
            return false;
        }

        if (!file_exists($toDir)) {
            self::createDir($toDir);
        }
        $file = opendir($surDir);
        while ($fileName = readdir($file)) {
            $file1 = $surDir . '/' . $fileName;
            $file2 = $toDir . '/' . $fileName;
            if ($fileName != '.' && $fileName != '..') {
                if (is_dir($file1)) {
                    self::copyDir($file1, $file2);
                } else {
                    copy($file1, $file2);
                }
            }
        }
        closedir($file);
        return true;
    }

    /**
     * 文件重命名
     * @param  string $oldName 源文件名
     * @param  string $newName 新文件名
     * @return bool
     */
    public static function rename($oldName, $newName)
    {
        if (($newName != $oldName) && is_writable($oldName)) {
            return rename($oldName, $newName);
        }
        return false;
    }

    /**
     * 取得目录下面的文件信息
     * @param  string  $pathname 路径
     * @param  string  $pattern  匹配
     * @return array
     */
    public static function listFile($pathname, $pattern = '*')
    {
        if (strpos($pattern, '|') !== false) {
            $patterns = explode('|', $pattern);
        } else {
            $patterns[0] = $pattern;
        }
        $i = 0;
        $dir = [];
        foreach ($patterns as $pattern) {
            $list = glob($pathname . $pattern);
            if ($list !== false) {
                foreach ($list as $file) {
                    //$dir[$i]['filename']    = basename($file);
                    //basename取中文名出问题.改用此方法
                    //编码转换.把中文的调整一下.
                    $dir[$i]['filename'] = preg_replace('/^.+[\\\\\\/]/', '', $file);
                    $dir[$i]['pathname'] = realpath($file);
                    $dir[$i]['owner'] = fileowner($file);
                    $dir[$i]['perms'] = fileperms($file);
                    $dir[$i]['inode'] = fileinode($file);
                    $dir[$i]['group'] = filegroup($file);
                    $dir[$i]['path'] = dirname($file);
                    $dir[$i]['atime'] = fileatime($file);
                    $dir[$i]['ctime'] = filectime($file);
                    $dir[$i]['size'] = filesize($file);
                    $dir[$i]['type'] = filetype($file);
                    $dir[$i]['ext'] = is_file($file) ? strtolower(substr(strrchr(basename($file), '.'), 1)) : '';
                    $dir[$i]['mtime'] = filemtime($file);
                    $dir[$i]['isDir'] = is_dir($file);
                    $dir[$i]['isFile'] = is_file($file);
                    $dir[$i]['isLink'] = is_link($file);
                    //$dir[$i]['isExecutable']= function_exists('is_executable')?is_executable($file):'';
                    $dir[$i]['isReadable'] = is_readable($file);
                    $dir[$i]['isWritable'] = is_writable($file);
                    $i++;
                }
            }
        }
        // 对结果排序 保证目录在前面
        usort($dir, function ($a, $b) {
            if (($a['isDir'] && $b['isDir']) || (!$a['isDir'] && !$b['isDir'])) {
                return $a['filename'] > $b['filename'] ? 1 : -1;
            } else {
                if ($a['isDir']) {
                    return -1;
                } elseif ($b['isDir']) {
                    return 1;
                }
                if ($a['filename'] == $b['filename']) {
                    return 0;
                }
                return $a['filename'] > $b['filename'] ? -1 : 1;
            }
        });
        return $dir;
    }

    /**
     * 列出目录
     * @param  string  $dir 目录名
     * @return array
     */
    public static function getDirs($dir)
    {
        $dir = rtrim($dir, '/') . '/';
        $dirArray[][] = null;
        if (false != ($handle = opendir($dir))) {
            $i = 0;
            $j = 0;
            while (false !== ($file = readdir($handle))) {
                if (is_dir($dir . $file)) {
                    //判断是否文件夹
                    $dirArray['dir'][$i] = $file;
                    $i++;
                } else {
                    $dirArray['file'][$j] = $file;
                    $j++;
                }
            }
            closedir($handle);
        }
        return $dirArray;
    }

    /**
     * 获取指定文件夹下的指定后缀文件（含子目录）
     *
     * @param  string  $path   文件夹路径
     * @param  array   $suffix 指定后缀名
     * @param  array   $files  返回的结果集
     * @return array
     */
    public static function getFiles($path, $suffix = ['php', 'html'], &$files = [])
    {
        $response = opendir($path);
        while ($file = readdir($response)) {
            if ($file != '..' && $file != '.') {
                if (is_dir($path . '/' . $file)) {
                    self::getFiles($path . '/' . $file, $suffix, $files);
                } else {
                    $pathinfo = pathinfo($file);
                    if (in_array(strtolower($pathinfo['extension']), $suffix)) {
                        $files[] = $path . '/' . $file;
                    }
                }
            }
        }
        closedir($response);
        return $files;
    }

    /**
     * 统计文件夹大小
     * @param  string $dir 目录名
     * @return int
     */
    public static function getSize($dir)
    {
        $handle = opendir($dir);
        $size = 0;
        while (false !== ($folderOrFile = readdir($handle))) {
            if ($folderOrFile != '.' && $folderOrFile != '..') {
                if (is_dir("$dir/$folderOrFile")) {
                    $size += self::getSize("$dir/$folderOrFile");
                } else {
                    $size += filesize("$dir/$folderOrFile");
                }
            }
        }
        closedir($handle);
        return $size;
    }

    /**
     * 获取文件扩展名
     * @param  string   $filename 文件路径
     * @return string
     */
    public static function getExtension($filename)
    {
        $pathInfo = pathinfo($filename);
        return strtolower($pathInfo['extension']);
    }

    /**
     * 判断目录是否为空
     * @param  string $directory 目录路径
     * @return bool
     */
    public static function isEmpty($directory)
    {
        $handle = opendir($directory);
        while (($file = readdir($handle)) !== false) {
            if ($file != '.' && $file != '..') {
                closedir($handle);
                return false;
            }
        }
        closedir($handle);
        return true;
    }

    /**
     * 判断文件或文件夹是否可写.
     * @param  string $file 文件或目录
     * @return bool
     */
    public static function isWritable($file)
    {
        if (DIRECTORY_SEPARATOR === '/') {
            return is_writable($file);
        }
        if (is_dir($file)) {
            $file = rtrim($file, '/') . '/' . md5(mt_rand());
            if (($fp = @fopen($file, 'ab')) === false) {
                return false;
            }
            fclose($fp);
            @chmod($file, 0777);
            @unlink($file);

            return true;
        } elseif (!is_file($file) || ($fp = @fopen($file, 'ab')) === false) {
            return false;
        }
        fclose($fp);
        return true;
    }
}
