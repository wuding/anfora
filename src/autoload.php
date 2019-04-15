<?php

/**
 * 返回赋值自动加载类文件对象
 */

require_once __DIR__ . '/Anfora/Autoload.php';

/**
 * 解析 JSON 转换为遍历可用数组
 */
include __DIR__ . '/Anfora/JSON.php';
include __DIR__ . '/Anfora/JSON/ComposerJSON.php';

use Ext\JSON\ComposerJSON;

$composerJson = new ComposerJSON(defined('COMPOSER_JSON') ? COMPOSER_JSON : realpath(BASE_DIR . '/composer.json'));
ComposerJSON::setVendorDir(VENDOR_DIR);
ComposerJSON::setSuperVars();
# print_r($ANFORA_FILE);exit;

return $anfora = Anfora_Autoload::getLoader();