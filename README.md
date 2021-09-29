## PHP常用工具类

#### Arr（数组）
- ##### column
  获取数组中指定的列
  ```php
  $array = [
      ['id' => 1, 'name' => '小明', 'age' => 20],
      ['id' => 2, 'name' => '小杨', 'age' => 18],
      ['id' => 3, 'name' => '小宋', 'age' => 19],
  ];
  $arr = \Zsirius\Utils\Arr::column($array, 'name');
  var_dump($arr);

  // 结果
  array(3) {
    [0]=> string(6) "小明"
    [1]=> string(6) "小杨"
    [2]=> string(6) "小宋"
  }
  ```
- ##### columns
  获取数组中指定的列 [支持多列]
  ```php
  $array = [
      ['id' => 1, 'name' => '小明', 'age' => 20],
      ['id' => 2, 'name' => '小杨', 'age' => 18],
      ['id' => 3, 'name' => '小宋', 'age' => 19],
  ];
  $arr = \Zsirius\Utils\Arr::columns($array, ['name', 'age']);
  var_dump($arr);

  // 结果
  array(3) {
    [0]=> array(2) {
      ["name"]=> string(6) "小明"
      ["age"]=> int(20)
    }
    [1]=> array(2) {
      ["name"]=> string(6) "小杨"
      ["age"]=> int(18)
    }
    [2]=> array(2) {
      ["name"]=> string(6) "小宋"
      ["age"]=> int(19)
    }
  }
  ```
- ##### column2key
  把二维数组中某列设置为键值
  *注意：存在相同列会进行覆盖*
  ```php
  $array = [
      ['id' => 7, 'name' => '小明', 'age' => 20],
      ['id' => 8, 'name' => '小杨', 'age' => 18],
      ['id' => 9, 'name' => '小宋', 'age' => 19],
  ];
  $arr = \Zsirius\Utils\Arr::column2key($array, 'age');
  var_dump($arr);

  // 结果
  array(3) {
    [20]=> array(3) {
      ["id"]=> int(7)
      ["name"]=> string(6) "小明"
      ["age"]=> int(20)
    }
    [18]=> array(3) {
      ["id"]=> int(8)
      ["name"]=> string(6) "小杨"
      ["age"]=> int(18)
    }
    [19]=> array(3) {
      ["id"]=> int(9)
      ["name"]=> string(6) "小宋"
      ["age"]=> int(19)
    }
  }
  ```
- ##### multiMerge
  多维数组合并
  ```php
  $array1 = [
      'a' => ['color' => 'red'],
      'b' => ['color' => 'blue'],
  ];
  $array2 = [
      'd' => ['color' => 'green', 1 => '测试'],
  ];
  $arr = \Zsirius\Utils\Arr::multiMerge($array1, $array2);
  var_dump($arr);

  // 结果
  array(3) {
    ["a"]=> array(1) {
      ["color"]=> string(3) "red"
    }
    ["b"]=> array(1) {
      ["color"]=> string(4) "blue"
    }
    ["d"]=> array(2) {
      ["color"]=> string(5) "green"
      [1]=> string(5) "测试"
    }
  }
  ```
- ##### search
  二维数组中查找指定值
  ```php
  $array = [
      'a' => ['color' => 'red'],
      'b' => ['color' => 'blue'],
  ];
  $bool = \Zsirius\Utils\Arr::search($array, 'color', 'blue');
  var_dump($bool);
  
  // 结果
  array(1) {
    ["color"]=> string(4) "blue"
  }

  $bool = \Zsirius\Utils\Arr::search($array, 'color', 'green');
  var_dump($bool);

  // 结果
  false
  ```
- ##### multi2single
  多维数组转化为一维数组
  ```php
  $array = [
      'a' => ['color' => 'red', '10' => '测试'],
      'b' => ['color' => 'blue'],
  ];
  $arr = \Zsirius\Utils\Arr::multi2single($array);
  var_dump($arr);

  // 结果
  array(3) {
    [0]=> string(3) "red"
    [1]=> string(6) "测试"
    [2]=> string(4) "blue"
  }
  ```
- ##### obj2arr
  将对象深度转化成数组
  ```php
  class Test{
      public $a;
      public $b;
      public function __construct($a) {
          $this->a = $a;
      }
  }
  $test = new Test('test1');
  $test->b = new Test('test2');
  
  $array = \Zsirius\Utils\Arr::obj2arr($test);
  var_dump($array);

  // 结果
  array(2) {
    ["a"]=> string(5) "test1"
    ["b"]=> array(2) {
      ["a"]=> string(5) "test2"
      ["b"]=> NULL
    }
  }
  ```
- ##### multi2sort
  二维数组排序
  ```php
  $array = [
    ['id' => 7, 'name' => '小明', 'age' => 20],
    ['id' => 8, 'name' => '小杨', 'age' => 18],
    ['id' => 9, 'name' => '小宋', 'age' => 19],
  ];
  $arr = \Zsirius\Utils\Arr::multi2sort($array, 'age', 'desc');
  var_dump($arr);

  // 结果
  array(3) {
    [0]=> array(3) {
      ["id"]=> int(7)
      ["name"]=> string(6) "小明"
      ["age"]=> int(20)
    }
    [2]=> array(3) {
      ["id"]=> int(9)
      ["name"]=> string(6) "小宋"
      ["age"]=> int(19)
    }
    [1]=> array(3) {
      ["id"]=> int(8)
      ["name"]=> string(6) "小杨"
      ["age"]=> int(18)
    }
  }
  ```
- ##### str2arr
  字符串转换为数组
  ```php
  $str = '1,2,3,4';
  $arr = \Zsirius\Utils\Arr::str2arr($str, ',');
  var_dump($arr);

  // 结果
  array(4) {
    [0]=> string(1) "1"
    [1]=> string(1) "2"
    [2]=> string(1) "3"
    [3]=> string(1) "4"
  }
  ```
- ##### arr2str
  数组转换为字符串
  ```php
  $arr = [1, 2, 3, 4];
  $str = \Zsirius\Utils\Arr::arr2str($arr, '-');
  var_dump($str);

  // 结果
  string(7) "1-2-3-4"
  ```
- ##### arr2xml
  将数组转换为XML格式的字符串
  ```php
  $arr = [
      'sn' => '2020101010',
      'money' => 100
  ];
  $xml = \Zsirius\Utils\Arr::arr2xml($arr);
  var_dump($xml);

  // 结果
  string(71) "<?xml version="1.0"?><xml><sn>2020101010</sn><money>100</money></xml>"
  ```
- ##### xml2arr
  将XML格式字符串转换为数组
  ```php
  $xml = '<?xml version="1.0"?>
  <xml><sn>2020101010</sn><money>100</money></xml>';
  $arr = \Zsirius\Utils\Arr::xml2arr($xml);
  var_dump($arr);

  // 结果
  array(2) {
    ["sn"]=> string(10) "2020101010"
    ["money"]=> string(3) "100"
  }
  ```
- ##### multiCombine
  多个数组的笛卡尔积
  ```php
  $arr1 = array('A','B');
  $arr2 = array('@','#');
  $arr = \Zsirius\Utils\Arr::multiCombine($arr1, $arr2);
  var_dump($arr);

  // 结果
  array(4) {
    [0]=> array(2) {
      [0]=> string(1) "A"
      [1]=> string(1) "@"
    }
    [1]=> array(2) {
      [0]=> string(1) "A"
      [1]=> string(1) "#"
    }
    [2]=> array(2) {
      [0]=> string(1) "B"
      [1]=> string(1) "@"
    }
    [3]=> array(2) {
      [0]=> string(1) "B"
      [1]=> string(1) "#"
    }
  }
  ```

#### Crypt（加解密）
- ##### encode
  字符串加密
  ```php
  $str = 'hello world';
  $encode = \Zsirius\Utils\Crypt::encode($str);
  var_dump($encode);

  // 结果
  string(58) "8204edc6foJvHi48wg6KWUbD+YK/uZG7xzCAcuGdJm/2L2zDVMqz4EgSIg"
  ```
- ##### decode
  解密encode加密的字符串
  ```php
  $encode = '8204edc6foJvHi48wg6KWUbD+YK/uZG7xzCAcuGdJm/2L2zDVMqz4EgSIg';
  $str = \Zsirius\Utils\Crypt::decode($encode);
  var_dump($str);

  // 结果
  string(11) "hello world"
  ```
- ##### operate
  加解密
  ```php
  $str = 'hello world';
  $encode  = \Zsirius\Utils\Crypt::operate($str, 'ENCODE', 'key');
  var_dump($encode);

  // 结果
  string(58) "1d0c41d7Nr+PNgEe4J/MmIifFVlPPAwpwtH4Hhw506I5XrVG5mbQV0qa8A"

  $encode = '1d0c41d7Nr+PNgEe4J/MmIifFVlPPAwpwtH4Hhw506I5XrVG5mbQV0qa8A';
  $str = \Zsirius\Utils\Crypt::operate($encode, 'DECODE', 'key');
  var_dump($str);

  // 结果
  string(11) "hello world"
  ```

#### Date（日期）
- ##### human
  格式化日期
  ```php
  $date = \Zsirius\Utils\Date::human(time() - 3600);
  var_dump($date);

  // 结果
  string(10) "1小时前"
  ```
- ##### today
  当天日期
  ```php
  $date = \Zsirius\Utils\Date::today();
  var_dump($date);

  // 结果
  string(10) "2021-09-29"
  ```
- ##### yesterday
  昨日日期
  ```php
  $date = \Zsirius\Utils\Date::yesterday();
  var_dump($date);

  // 结果
  string(10) "2021-09-28"
  ```
- ##### thisWeek
  本周开始结束日期
  ```php
  $date = \Zsirius\Utils\Date::thisWeek();
  var_dump($date);

  // 结果
  array(2) {
    [0]=> string(10) "2021-09-26"
    [1]=> string(10) "2021-10-03"
  }
  ```
- ##### thisWeeks
  本周所有日期
  ```php
  $date = \Zsirius\Utils\Date::thisWeeks();
  var_dump($date);

  // 结果
  array(7) {
    [0]=> string(10) "2021-09-27"
    [1]=> string(10) "2021-09-28"
    [2]=> string(10) "2021-09-29"
    [3]=> string(10) "2021-09-30"
    [4]=> string(10) "2021-10-01"
    [5]=> string(10) "2021-10-02"
    [6]=> string(10) "2021-10-03"
  }
  ```
- ##### lastWeek
  上周开始结束日期
  ```php
  $date = \Zsirius\Utils\Date::lastWeek();
  var_dump($date);

  // 结果
  array(2) {
    [0]=> string(10) "2021-09-20"
    [1]=> string(10) "2021-09-26"
  }
  ```
- ##### lastWeeks
  上周所有日期
  ```php
  $date = \Zsirius\Utils\Date::lastWeeks();
  var_dump($date);

  // 结果
  array(7) {
    [0]=> string(10) "2021-09-20"
    [1]=> string(10) "2021-09-21"
    [2]=> string(10) "2021-09-22"
    [3]=> string(10) "2021-09-23"
    [4]=> string(10) "2021-09-24"
    [5]=> string(10) "2021-09-25"
    [6]=> string(10) "2021-09-26"
  }
  ```
- ##### monthDate
  上月本月所有日期
  ```php
  $date = \Zsirius\Utils\Date::monthDate('thismonth'); // lastmonth
  var_dump($date);

  // 结果
  array(30) {
    [0]=> string(10) "2021-09-01"
    [1]=> string(10) "2021-09-02"
    [2]=> string(10) "2021-09-03"
    ...
  }
  ```
- ##### thisMonth
  本月开始结束日期
  ```php
  $date = \Zsirius\Utils\Date::thisMonth();
  var_dump($date);

  // 结果
  array(2) {
    [0]=> string(10) "2021-09-01"
    [1]=> string(10) "2021-09-30"
  }
  ```
- ##### lastMonth
  上月开始结束日期
  ```php
  $date = \Zsirius\Utils\Date::lastMonth();
  var_dump($date);

  // 结果
  array(2) {
    [0]=> string(10) "2021-08-01"
    [1]=> string(10) "2021-08-31"
  }
  ```
- ##### nextMonth
  下月开始结束日期
  ```php
  $date = \Zsirius\Utils\Date::nextMonth();
  var_dump($date);

  // 结果
  array(2) {
    [0]=> string(10) "2021-10-01"
    [1]=> string(10) "2021-10-31"
  }
  ```
- ##### daysDate
  最近天数所有日期
  ```php
  $date = \Zsirius\Utils\Date::daysDate(2);
  var_dump($date);

  // 结果
  array(2) {
    [0]=> string(10) "2021-09-27"
    [1]=> string(10) "2021-09-28"
  }
  ```
- ##### year
  几年前开始和结束的日期
  ```php
  $date = \Zsirius\Utils\Date::year(2);
  var_dump($date);

  // 结果
  array(2) {
    [0]=> string(10) "2019-01-01"
    [1]=> string(10) "2019-12-31"
  }
  ```
- ##### dayToNow
  几天前到现在/昨日结束的日期
  ```php
  $date = \Zsirius\Utils\Date::dayToNow(2, true);
  var_dump($date);

  // 结果
  array(2) {
    [0]=> string(10) "2021-09-27"
    [1]=> string(10) "2021-09-29"
  }
  ```
- ##### betweenDates
  两个日期间的所有日期
  ```php
  $date = \Zsirius\Utils\Date::betweenDates('2021-09-28', '2021-10-01');
  var_dump($date);

  // 结果
  array(4) {
    [0]=> string(10) "2021-09-28"
    [1]=> string(10) "2021-09-29"
    [2]=> string(10) "2021-09-30"
    [3]=> string(10) "2021-10-01"
  }
  ```
- ##### daysAgo
  几天前的日期
  ```php
  $date = \Zsirius\Utils\Date::daysAgo(2);
  var_dump($date);

  // 结果
  string(10) "2021-09-27"
  ```
- ##### daysAfter
  几天后的日期
  ```php
  $date = \Zsirius\Utils\Date::daysAfter(2);
  var_dump($date);

  // 结果
  string(10) "2021-10-01"
  ```
- ##### daysToSecond
  天数转换成秒数
  ```php
  $date = \Zsirius\Utils\Date::daysToSecond(3);
  var_dump($date);

  // 结果
  int(259200)
  ```
- ##### weekToSecond
  周数转换成秒数
  ```php
  $date = \Zsirius\Utils\Date::weekToSecond(2);
  var_dump($date);

  // 结果
  int(1209600)
  ```
- ##### datetime
  日期的开始时间和结束时间
  ```php
  $date = \Zsirius\Utils\Date::datetime('2021-09-29');
  var_dump($date);

  // 结果
  array(2) {
    [0]=> string(19) "2021-09-29 00:00:00"
    [1]=> string(19) "2021-09-29 23:59:59"
  }
  ```
- ##### weeks
  获取周
  ```php
  $date = \Zsirius\Utils\Date::weeks();
  var_dump($date);

  // 结果
  array(7) {
    [1]=> string(6) "周一"
    [2]=> string(6) "周二"
    [3]=> string(6) "周三"
    [4]=> string(6) "周四"
    [5]=> string(6) "周五"
    [6]=> string(6) "周六"
    [0]=> string(6) "周日"
  }
- ##### diffBetweenTwoDays
  求两个日期之间相差的天数
  ```php
  $date = \Zsirius\Utils\Date::diffBetweenTwoDays("2021-09-28", "2021-10-01");
  var_dump($date);

  // 结果
  int(3)
  ```
- ##### betweenTimes
  获取两个时间点内所有的时间
  ```php
  $date = \Zsirius\Utils\Date::betweenTimes("10:15", "12:15");
  var_dump($date);

  // 结果
  array(2) {
    [0]=> array(2) {
      ["start_time"]=> string(5) "10:15"
      ["end_time"]=> string(5) "11:15"
    }
    [1]=> array(2) {
      ["start_time"]=> string(5) "11:15"
      ["end_time"]=> string(5) "12:15"
    }
  }
  ```
- ##### microtime
  获取毫秒时间戳
  ```php
  $date = \Zsirius\Utils\Date::microtime();
  var_dump($date);

  // 结果
  float(1632885426280)
  ```
#### File（文件）
- ##### createDir
  生成目录
  ```php
  $file = \Zsirius\Utils\File::createDir('./a/b/c');
  var_dump($file);

  // 结果
  bool(true)
  ```
- ##### readFile
  读取文件内容
  ```php
  $file = \Zsirius\Utils\File::readFile('./a/b/c/test.txt');
  var_dump($file);

  // 结果
  string(11) "hello world"
  ```
- ##### writeFile
  写文件
  ```php
  $file = \Zsirius\Utils\File::writeFile('./a/b/c/test.txt', 'hello world');
  var_dump($file);

  // 结果
  bool(true)
  ```
- ##### delDir
  删除目录
  ```php
  $file = \Zsirius\Utils\File::delDir('./a/b');
  var_dump($file);

  // 结果
  bool(true)
  ```
- ##### copyDir
  复制目录
  ```php
  $file = \Zsirius\Utils\File::copyDir('./a', './b');
  var_dump($file);

  // 结果
  bool(true)
  ```
- ##### rename
  文件重命名
  ```php
  $file = \Zsirius\Utils\File::rename('./a', 'c');
  var_dump($file);

  // 结果
  bool(true)
  ```
- ##### listFile
  取得目录下面的文件信息
  ```php
  $file = \Zsirius\Utils\File::listFile('./b/');
  var_dump($file);

  // 结果
  array(1) {
    [0]=>
    array(18) {
      ["filename"]=>
      string(8) "test.txt"
      ["pathname"]=>
      string(25) "/www/localhost/b/test.txt"
      ["owner"]=>
      int(1000)
      ["perms"]=>
      int(33261)
      ["inode"]=>
      int(2251799813785715)
      ["group"]=>
      int(1000)
      ["path"]=>
      string(3) "./b"
      ["atime"]=>
      int(1632898050)
      ["ctime"]=>
      int(1632898050)
      ["size"]=>
      int(0)
      ["type"]=>
      string(4) "file"
      ["ext"]=>
      string(3) "txt"
      ["mtime"]=>
      int(1632898050)
      ["isDir"]=>
      bool(false)
      ["isFile"]=>
      bool(true)
      ["isLink"]=>
      bool(false)
      ["isReadable"]=>
      bool(true)
      ["isWritable"]=>
      bool(true)
    }
  }
  ```
- ##### getDirs
  列出目录
  ```php
  $file = \Zsirius\Utils\File::getDirs('./b');
  var_dump($file);

  // 结果
  array(3) {
    [0]=>
    array(1) {
      [0]=>
      NULL
    }
    ["file"]=>
    array(1) {
      [0]=>
      string(8) "test.txt"
    }
    ["dir"]=>
    array(2) {
      [0]=>
      string(1) "."
      [1]=>
      string(2) ".."
    }
  }
  ```
- ##### getFiles
  获取指定文件夹下的指定后缀文件（含子目录）
  ```php
  $file = \Zsirius\Utils\File::getFiles('./b', ['txt']);
  var_dump($file);

  // 结果
  array(1) {
    [0]=>
    string(12) "./b/test.txt"
  }
  ```
- ##### getSize
  统计文件夹大小
  ```php
  $file = \Zsirius\Utils\File::getSize('./b');
  var_dump($file);

  // 结果
  int(11)
  ```
- ##### getExtension
  获取文件扩展名
  ```php
  $file = \Zsirius\Utils\File::getExtension('./b/test.txt');
  var_dump($file);

  // 结果
  string(3) "txt"
  ```
- ##### isEmpty
  判断目录是否为空
  ```php
  $file = \Zsirius\Utils\File::isEmpty('./b');
  var_dump($file);

  // 结果
  bool(false)
  ```
- ##### isWritable
  判断文件或文件夹是否可写
  ```php
  $file = \Zsirius\Utils\File::isWritable('./b/test.txt');
  var_dump($file);

  // 结果
  bool(true)
  ```
#### Http（请求）
- ##### get
  发送一个POST请求
  ```php
  $res = \Zsirius\Utils\Http::get('http://www.weather.com.cn/data/sk/101241001.html', [], []);
  var_dump($res);
  ```
- ##### post
  发送一个GET请求
  ```php
  $res = \Zsirius\Utils\Http::post('http://www.weather.com.cn/data/sk/101241001.html', [], []);
  var_dump($res);
  ```
- ##### sendRequest
  发送请求
  ```php
  $res = \Zsirius\Utils\Http::sendRequest('http://www.weather.com.cn/data/sk/101241001.html', [], 'GET', []);
  var_dump($res);
  ```
- ##### sendAsyncRequest
  异步发送一个请求
  ```php
  $res = \Zsirius\Utils\Http::sendAsyncRequest('http://www.weather.com.cn/data/sk/101241001.html', [], 'GET');
  var_dump($res);
  ```
- ##### sendToBrowser
  发送文件到客户端（下载）
  ```php
  $res = \Zsirius\Utils\Http::sendToBrowser('./b/test.txt');
  var_dump($res);
  ```
#### Image（图片）
- ##### base64ToImage
  Base64生成图片文件
  ```php
  $res = \Zsirius\Utils\Image::base64ToImage('data:image/jpg/png/gif;base64,....');
  var_dump($res);
  ```
- ##### imageToBase64
  图片转成base64字符串
  ```php
  $res = \Zsirius\Utils\Image::imageToBase64('./b/faces.jpg');
  var_dump($res);
  ```
#### Log（日志）
- ##### error
  记录error日志
  ```php
  $log = \Zsirius\Utils\Log::init('./b');
  $log->error('test');
  ```
- ##### warning
  记录warning日志
  ```php
  $log = \Zsirius\Utils\Log::init('./b');
  $log->warning('test');
  ```
- ##### notice
  记录notice日志
  ```php
  $log = \Zsirius\Utils\Log::init('./b');
  $log->notice('test');
  ```
- ##### info
  记录info日志
  ```php
  $log = \Zsirius\Utils\Log::init('./b');
  $log->info('test');
  ```
- ##### debug
  记录debug日志
  ```php
  $log = \Zsirius\Utils\Log::init('./b');
  $log->debug('test');
  ```
#### Random（随机）
- ##### alnum
  生成数字和字母
  ```php
  $random = \Zsirius\Utils\Random::alnum(6);
  var_dump($random);

  // 结果
  string(6) "ZGQbKf"
  ```
- ##### alpha
  仅生成字符
  ```php
  $random = \Zsirius\Utils\Random::alpha(6);
  var_dump($random);

  // 结果
  string(6) "czjVSI"
  ```
- ##### numeric
  生成指定长度的随机数字
  ```php
  $random = \Zsirius\Utils\Random::numeric(6);
  var_dump($random);

  // 结果
  string(6) "672513"
  ```
- ##### nozero
  生成指定长度的无0随机数字
  ```php
  $random = \Zsirius\Utils\Random::nozero(6);
  var_dump($random);

  // 结果
  string(6) "834965"
  ```
- ##### build
  能用的随机数生成（alpha/alnum/numeric/nozero/unique/md5/encrypt/sha1）
  ```php
  $random = \Zsirius\Utils\Random::build('md5');
  var_dump($random);

  // 结果
  string(32) "29ec32b7f844ad7d16b23119ec471b4e"
  ```
- ##### lottery
  根据数组元素的概率获得键名
  ```php
  $arr = ['p1' => 20, 'p2' => 30, 'p3' => 50];
  $random = \Zsirius\Utils\Random::lottery($arr, 1);
  var_dump($random);

  // 结果
  string(2) "p1"
  ```
- ##### uuid
  获取全球唯一标识
  ```php
  $random = \Zsirius\Utils\Random::uuid();
  var_dump($random);

  // 结果
  string(36) "7ac748f7-a9b2-404e-834b-daf4d2a2e307"
  ```
#### Sensitive（敏感词）
  ```php
  $content = '您好';
  // 检验【文件模式】
  $res = \Zsirius\Utils\Sensitive::init()->setTreeByFile('./c/test.txt')->islegal($content);
  var_dump($res); // true
  // 检验【数组模式】
  $res = \Zsirius\Utils\Sensitive::init()->setTree(['您好1'])->islegal($content);
  var_dump($res); // false
  // 检测文字中的敏感词
  \Zsirius\Utils\Sensitive::init()->setTreeByFile('./c/test.txt')->getBadWord($content, 1, 0);
  // 替换敏感字字符
  \Zsirius\Utils\Sensitive::init()->setTreeByFile('./c/test.txt')->replace($content, 'test');
  // 标记敏感词
  \Zsirius\Utils\Sensitive::init()->setTreeByFile('./c/test.txt')->mark($content, '<mark>', '</mark>');
  ```
#### Str（字符串）
- ##### formatBytes
- ##### filterEmoji
- ##### trim
- ##### ltrim
- ##### rtrim
- ##### contains
- ##### endsWith
- ##### startsWith
- ##### lower
- ##### upper
- ##### length
- ##### substr
- ##### snake
- ##### camel
- ##### studly
- ##### title
- ##### orderSn

#### Tree（树型）

#### Validator（验证）

#### Zip（解压缩）
