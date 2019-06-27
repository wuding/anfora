# Anfora

PHP Class Files Loader



## Install

```
composer require wuding/anfora
```



## Usage

> web/index.php

```php
<?php
define('BASE_DIR', __DIR__ . '/..');
define('VENDOR_DIR', __DIR__ . '/../vendor');
# define('COMPOSER_JSON', __DIR__ . '/../composer.json');

$autoload = require __DIR__ . '/../src/autoload.php';
$anfora = new Anfora(__DIR__ . '/../app/config.php');
```



> vendor/composer/ 目录下

`autoload_classmap`, `autoload_namespaces`, `autoload_files`, `autoload_psr4`

```php
$vendorDir = dirname(dirname(__FILE__));
$baseDir = dirname($vendorDir);
```

替换为

```php
include __DIR__ . '/../../app/composer.php';
```



> app/config.php

添加常量定义以禁用 `autoload_static`

```php
define('HHVM_VERSION', '-1');
```

或修改 vendor/composer/autoload_static.php

`__DIR__ . '/../..'` 替换为 `BASE_DIR`

`__DIR__ . '/..'` 替换为 `VENDOR_DIR`