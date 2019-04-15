<?php

/**
 * 项目固定绝对唯一目录
 */
define('BASE_DIR', __DIR__ . '/..');
# define('VENDOR_DIR', __DIR__ . '/../vendor');
define('VENDOR_DIR', realpath(__DIR__ . '/..'));
define('COMPOSER_JSON', realpath(BASE_DIR . '/composer.json.dist'));

/**
 * 预定义示例请求地址文件名
 */
$_NAMES = array(
    '' => 'index',
    'index',
);

/**
 * 包含赋值类文件加载器对象
 *
 * 声明函数库
 */
$autoload = require __DIR__ . '/../src/autoload.php';
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
