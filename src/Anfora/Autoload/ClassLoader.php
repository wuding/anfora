<?php

/**
 * 类文件加载器类
 */

namespace Anfora\Autoload;

class ClassLoader
{
    public static $autoload_psr4;
    public static $loaded_files = [];

    public function register($prepend = false)
    {
        spl_autoload_register(array($this, 'loadClass'), true, $prepend);
    }
    
    /**
     * 加载类文件
     */
    public function loadClass($class)
    {
        $this->autoloadFiles();

        $included = null;
        if ($file = $this->findFile($class)) {
            $included = includeFile($file);
        }
        
        return $included;
    }

    /**
     * 查找文件地址
     */
    public function findFile($class)
    {
        $file = $this->findFileWithExtension($class, '.php');
        return $file;
    }

    /**
     * 返回文件绝对相对地址
     * 类名匹配前缀，绝对路径连接目录
     */
    public function findFileWithExtension($class, $ext = '.php')
    {
        $class = trim($class, '\\');
        $class = str_replace('\\', '/', $class);
        $file = null;

        foreach (self::$autoload_psr4 as $key => $dir) {
            $key = str_replace('\\', '/', $key);
            $key = addcslashes($key, '/');
            if (preg_match("/^($key)/i", $class, $matches)) {
                $class = preg_replace("/^$key/i", '', $class);
                $file = $dir . '/' . $class;
                break;
            }
        }

        if (!$file) {
            return $file;
        }

        return $file .= $ext;
    }

    /**
     * 赋值 PSR-4 规则
     */
    public function setPsr4($rule)
    {
        self::$autoload_psr4 = $rule;
    }

    /**
     * 直接加载文件
     */
    public function autoloadFiles()
    {
        $includeFiles = isset($GLOBALS['ANFORA_FILE']) ? $GLOBALS['ANFORA_FILE'] : [];
        # print_r($includeFiles);exit;
        foreach ($includeFiles as $fileIdentifier => $file) {
            includeFile($file);
        }
    }
}

function includeFile($file) {
    $fileIdentifier = md5($file);
    $GLOBALS['_ANFORA']['files'][$fileIdentifier] = $file;
    return $include = @include_once $file;
}
