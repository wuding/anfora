<?php

class Anfora_Autoload
{
    private static $loader;
    private static $vendorDir;
    private static $baseDir;
    private static $autoload_psr4;
    
    // 引入全局类加载
    public static function loadClassLoader($class)
    {
        if ('Anfora\Autoload\ClassLoader' === $class) {
            require_once __DIR__ . '/Autoload/ClassLoader.php';
        }
    }
    
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }
        
        // 注册类加载器函数并注销
        $autoload_function = array('Anfora_Autoload', 'loadClassLoader');
        spl_autoload_register($autoload_function, true, true);
        self::$loader = $loader = new \Anfora\Autoload\ClassLoader();
        spl_autoload_unregister($autoload_function);

        $rule = isset($GLOBALS['_ANFORA']['psr-4']) ? $GLOBALS['_ANFORA']['psr-4'] : [];
        $map = self::$autoload_psr4 ? : $rule;
        $loader->setPsr4($map);
        # print_r($map);

        
        // 注册全局类加载
        $loader->register(true);
        return $loader;
    }
}
