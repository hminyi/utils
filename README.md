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

  $arr = Arr::column2key($array, 'age');
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

  $arr = Arr::multiMerge($array1, $array2);
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

  $bool = Arr::search($array, 'color', 'blue');
  var_dump($bool);
  
  // 结果
  array(1) {
    ["color"]=> string(4) "blue"
  }

  $bool = Arr::search($array, 'color', 'green');
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

  $arr = Arr::multi2single($array);
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
  
  $array = Arr::obj2arr($test);
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

  
  $arr = Arr::multi2sort($array, 'age', 'desc');
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

  
  $arr = Arr::str2arr($str, ',');
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

  $str = Arr::arr2str($arr, '-');
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

  
  $xml = Arr::arr2xml($arr);
  var_dump($xml);

  // 结果
  string(71) "<?xml version="1.0"?><xml><sn>2020101010</sn><money>100</money></xml>"
  ```
- ##### xml2arr
  将XML格式字符串转换为数组
  ```php
  $xml = '<?xml version="1.0"?>
  <xml><sn>2020101010</sn><money>100</money></xml>';

  
  $arr = Arr::xml2arr($xml);
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
  
  $arr = Arr::multiCombine($arr1, $arr2);
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
  $encode = Crypt::encode($str);
  var_dump($encode);

  // 结果
  string(58) "8204edc6foJvHi48wg6KWUbD+YK/uZG7xzCAcuGdJm/2L2zDVMqz4EgSIg"
  ```
- ##### decode
  解密encode加密的字符串
  ```php
  $encode = '8204edc6foJvHi48wg6KWUbD+YK/uZG7xzCAcuGdJm/2L2zDVMqz4EgSIg';
  $str = Crypt::decode($encode);
  var_dump($str);

  // 结果
  string(11) "hello world"
  ```
- ##### operate
  加解密
  ```php
  $str = 'hello world';
  $encode  = Crypt::operate($str, 'ENCODE', 'key');
  var_dump($encode);

  // 结果
  string(58) "1d0c41d7Nr+PNgEe4J/MmIifFVlPPAwpwtH4Hhw506I5XrVG5mbQV0qa8A"

  $encode = '1d0c41d7Nr+PNgEe4J/MmIifFVlPPAwpwtH4Hhw506I5XrVG5mbQV0qa8A';
  $str = Crypt::operate($encode, 'DECODE', 'key');
  var_dump($str);

  // 结果
  string(11) "hello world"
  ```

#### Date（日期）
- ##### human
  格式化日期
  ```php
  $date = Date::human(time() - 3600);
  var_dump($date);

  // 结果
  string(10) "1小时前"
  ```
- ##### today
  当天日期
  ```php
  $date = Date::today();
  var_dump($date);

  // 结果
  string(10) "2021-09-29"
  ```
- ##### yesterday
  昨日日期
  ```php
  $date = Date::yesterday();
  var_dump($date);

  // 结果
  string(10) "2021-09-28"
  ```
- ##### thisWeek
  本周开始结束日期
  ```php
  $date = Date::thisWeek();
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
  $date = Date::thisWeeks();
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
  $date = Date::lastWeek();
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
  $date = Date::lastWeeks();
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
  $date = Date::monthDate('thismonth'); // lastmonth
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
  $date = Date::thisMonth();
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
  $date = Date::lastMonth();
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
  $date = Date::nextMonth();
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
  $date = Date::daysDate(2);
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
  $date = Date::year(2);
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
  $date = Date::dayToNow(2, true);
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
  $date = Date::betweenDates('2021-09-28', '2021-10-01');
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
  $date = Date::daysAgo(2);
  var_dump($date);

  // 结果
  string(10) "2021-09-27"
  ```
- ##### daysAfter
  几天后的日期
  ```php
  $date = Date::daysAfter(2);
  var_dump($date);

  // 结果
  string(10) "2021-10-01"
  ```
- ##### daysToSecond
  天数转换成秒数
  ```php
  $date = Date::daysToSecond(3);
  var_dump($date);

  // 结果
  int(259200)
  ```
- ##### weekToSecond
  周数转换成秒数
  ```php
  $date = Date::weekToSecond(2);
  var_dump($date);

  // 结果
  int(1209600)
  ```
- ##### datetime
  日期的开始时间和结束时间
  ```php
  $date = Date::datetime('2021-09-29');
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
  $date = Date::weeks();
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
  $date = Date::diffBetweenTwoDays("2021-09-28", "2021-10-01");
  var_dump($date);

  // 结果
  int(3)
  ```
- ##### betweenTimes
  获取两个时间点内所有的时间
  ```php
  $date = Date::betweenTimes("10:15", "12:15");
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
  $date = Date::microtime();
  var_dump($date);

  // 结果
  float(1632885426280)
  ```
#### File（文件）
#### Http（请求）
#### Image（图片）
#### Log（日志）
#### Random（随机）
#### Sensitive（敏感词）
#### Str（字符串）
#### Tree（树型）
#### Validator（验证）
#### Zip（解压缩）
