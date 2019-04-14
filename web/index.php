<?php

/**
 * 项目固定绝对唯一目录
 */
define('SRC_ROOT', __DIR__ . '/../src');

/**
 * 预定义示例请求地址文件名
 */
$_NAMES = array(
    '' => 'index',
    'index',
);


/**
 * 解析 JSON 转换为遍历可用数组
 */
$json = new Json(SRC_ROOT . '/../composer.json');
# $json->__destruct();
$autoload = Json::composer_json();

$ANFORA_RULE = (array) $autoload->{'psr-4'};
$ANFORA_FILE = $autoload->files;
$VENDOR_ROOT = realpath(__DIR__ . '/..');
foreach ($ANFORA_RULE as $key => &$value) {
    $value = trim($value, '/');

    if (!preg_match('/:/', $value)) {
        $value = $VENDOR_ROOT . '/' . $value;
    }
    $value = str_replace('\\', '/', $value);
}
foreach ($ANFORA_FILE as $key => &$value) {
    $value = trim($value, '/');
    if (!preg_match('/:/', $value)) {
        $value = $VENDOR_ROOT . '/' . $value;
    }
    $value = str_replace('\\', '/', $value);
}
# print_r(get_defined_vars());

/**
 * 包含赋值类文件加载器对象
 *
 * 声明函数库
 */
$Composer = require __DIR__ . '/../src/autoload.php';
$anfora = new \Anfora;
$functions = [
    '_isset' => ['', [], '', null],
    #'\Func\array_diff_kv' => ['', [], [], [], false],
    'str_match' => ['', '//', '', null, false],
    #'\Func\Arr\arr_fixed_assoc' => ['', [], false],
    #'arr_reset_values',
];
func($functions, ['variable', 'arr', 'pcre', 'filesystem']);



/**
 * 数组修复键重设值，文件名检测包含源代码
 */
arr_fixed_assoc($_NAMES, true);
arr_reset_values($_NAMES, ['prefix' => __DIR__ .  '/../example/', 'suffix' => '.php'], true);
$basename = path_info(0, PATHINFO_BASENAME);
#print_r([__LINE__, get_defined_functions()['user'], get_included_files(), $_NAMES, $basename, $_NAMES[$basename]]);
if (array_key_exists($basename, $_NAMES) && include $_NAMES[$basename]) {
    //
} else {
    include $_NAMES[''];
}
/**/
# print_r([__LINE__, get_defined_constants(), get_defined_vars()]);

/**
 * JSON 文件类型格式读写
 */
class Json
{
    private static $json_decoded = null;

    /**
     * 构建函数
     */
    public function __construct($filename = null)
    {
        $this->init($filename);
    }

    /**
     * 初始化
     */
    public function init($filename = null)
    {
        if (!$filename) {
            return false;
        }
        $file_contents = file_get_contents($filename);
        self::$json_decoded = $object_array = json_decode($file_contents);
    }

    /**
     * 标准结构化读写
     */
    public static function composer_json()
    {
        $instance = new static;
        return $autoload = self::$json_decoded->autoload;
    }

    /**
     * 析构函数
     */
    public function __destruct()
    {
        # print_r(self::$json_decoded);
    }
}
