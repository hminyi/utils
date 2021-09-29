<?php

namespace Zsirius\Utils;

if (!function_exists('posix_getpid')) {
    function posix_getpid()
    {
        return getmypid();
    }
}

/**
 * 日志记录类
 */
class Log
{
    /**
     * @var mixed
     */
    private static $logPath; //日志路径/a/b
    /**
     * @var mixed
     */
    private static $logLevel; //日志的写入级别  debug < info < notice < warning < error
    /**
     * @var mixed
     */
    private static $logPid; //进程号
    /**
     * @var mixed
     */
    private static $logId; //日志唯一标识id
    /**
     * @var mixed
     */
    private static $rollType; //日志文件类型
    /**
     * @var mixed
     */
    private static $noticeStr; //追加notice日志

    //日志类型
    const HOUR_ROLLING = 1;
    const DAY_ROLLING = 2;
    const MONTH_ROLLING = 3;

    //日志级别
    const DEBUG = 1;
    const INFO = 2;
    const NOTICE = 4;
    const WARNING = 8;
    const ERROR = 16;

    public function __construct() {}
    public function __destruct() {}
    /**
     * @param string $path     日志路径 例/a/b
     * @param string $name     日志文件名 例 error info
     * @param int    $level    日志级别 低于设定级别的日志不会被记录 error级别写入error文件 其他写入access文件
     * @param string $logId    日志唯一标识
     * @param string $rollType 日志文件类别 1:YmdH 2:Ymd 3:Ym 其他: .log
     */
    public static function init($path, $level = self::DEBUG, $logId = '', $rollType = self::DAY_ROLLING)
    {
        if (empty($path)) {
            return false;
        }
        if (!is_writable($path)) {
            return false;
        }

        self::$logPath = $path;
        self::$logLevel = $level;
        self::$logPid = posix_getpid();
        self::$logId = empty($logId) ? self::generateLogId() : $logId;
        self::$rollType = $rollType;

        return new static();
    }

    /**
     * 设置logId
     */
    public static function generateLogId()
    {
        return md5(microtime() . posix_getpid() . uniqid());
    }

    /**
     * @param string|array $msg 信息
     */
    public function error($msg)
    {
        $this->writeLog(self::ERROR, $msg);
    }

    /**
     * @param string|array $msg 信息
     */
    public function warning($msg)
    {
        $this->writeLog(self::WARNING, $msg);
    }

    /**
     * @param string|array $msg 信息
     */
    public function notice($msg)
    {
        $this->writeLog(self::NOTICE, $msg);
    }

    /**
     * @param string|array $msg 信息
     */
    public function info($msg)
    {
        $this->writeLog(self::INFO, $msg);
    }

    /**
     * @param string|array $msg 信息
     */
    public function debug($msg)
    {
        $this->writeLog(self::DEBUG, $msg);
    }

    /**
     * 追加日志
     *
     * @param string|array $msg 信息
     */
    public function pushNotice($msg)
    {
        if (is_array($msg)) {
            foreach ($msg as $k => $val) {
                if (is_array($val)) {
                    $val = json_encode($val);
                }
                self::$noticeStr .= ' ' . $k . ':' . $val;
            }
        } else {
            self::$noticeStr .= ' ' . $msg;
        }
    }

    /**
     * 写入日志
     *
     * @param int          $level 等级
     * @param string|array $msg   信息
     */
    private function writeLog($level, $msg)
    {
        if ($level < self::$logLevel) {
            //低于设定级别的日志不记录
            return;
        }

        $logLevelName = [1 => 'debug', 2 => 'info', 4 => 'notice', 8 => 'warning', 16 => 'error'];
        list($usec, $sec) = explode(' ', microtime());
        $str = sprintf(
            '[%s] %s.%-06d %s %s',
            $logLevelName[$level],
            date('Y-m-d H:i:s', $sec),
            $usec * 1000000,
            'logId:' . self::$logId,
            'pid:' . posix_getpid()
        );

        if (is_array($msg)) {
            foreach ($msg as $k => $val) {
                if (is_array($val)) {
                    $val = json_encode($val);
                }
                $str .= ' ' . $k . ':' . $val;
            }
        } else {
            $str .= ' ' . $msg;
        }

        if (!empty(self::$noticeStr) && $level == self::NOTICE) {
            $str .= self::$noticeStr;
        }
        $str .= "\n";

        $filePath = $this->getLogFilePath($level);
        file_put_contents($filePath, $str, FILE_APPEND);
        self::$noticeStr = '';
        return;
    }

    /**
     * 获取等级路径
     *
     * @param  string   $level 等级
     * @return string
     */
    private function getLogFilePath($level)
    {
        $file = $level == self::ERROR ? 'error' : 'access';
        $filePath = rtrim(self::$logPath, '/') . '/' . $file;
        switch (self::$rollType) {
            case self::DAY_ROLLING:
                $filePath .= date('Ymd') . '.log';
                break;
            case self::MONTH_ROLLING:
                $filePath .= date('Ym') . '.log';
                break;
            case self::HOUR_ROLLING:
                $filePath .= date('YmdH') . '.log';
                break;
            default:
                $filePath .= '.log';
                break;
        }
        return $filePath;
    }

    /**
     * 获取去文件行号
     *
     * @return array
     */
    public function getFileLineNo()
    {
        $bt = debug_backtrace();
        if (isset($bt[1]) && isset($bt[1]['file'])) {
            $c = $bt[1];
        } else {
            if (isset($bt[2]) && isset($bt[2]['file'])) {
                //为了兼容回调函数使用log
                $c = $bt[2];
            } else {
                if (isset($bt[0]) && isset($bt[0]['file'])) {
                    $c = $bt[0];
                } else {
                    $c = ['file' => 'faint', 'line' => 'faint'];
                }
            }
        }
        return ['file' => $c['file'], 'line' => $c['line']];
    }
}
