<?php

$_CONFIG = require __DIR__ . '/../app/config.php';

/**
 * 包含赋值类文件加载器对象
 *
 * 声明函数库
 */
$autoload = require __DIR__ . '/../src/autoload.php';
$anfora = new \Anfora;

// 依赖函数
func($_CONFIG['func']['config'], $_CONFIG ['func']['load']);

/**
 * 数组修复键重设值，文件名检测包含源代码
 */
arr_fixed_assoc($_NAMES, true);
arr_reset_values($_NAMES, ['prefix' => __DIR__ .  '/../example/', 'suffix' => '.php'], true);
$basename = path_info(0, PATHINFO_BASENAME);
if (array_key_exists($basename, $_NAMES) && include $_NAMES[$basename]) {
    //
} else {
    include $_NAMES[''];
}
