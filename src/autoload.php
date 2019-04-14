<?php

/**
 * 返回赋值自动加载类文件对象
 */

require_once __DIR__ . '/Anfora/Autoload.php';
return $anfora = Anfora_Autoload::getLoader();