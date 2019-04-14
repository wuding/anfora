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

$autoload = require __DIR__ . '/../src/autoload.php';
$anfora = new Anfora(__DIR__ . '/../app/config.php');
```

