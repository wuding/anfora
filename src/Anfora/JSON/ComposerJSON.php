<?php

/**
 * composer.json 读写
 */

namespace Ext\JSON;

use Ext\JSON;

class ComposerJSON extends JSON
{
    public static $vendorDir = '';

    /**
     * 构建函数
     */
    public function __construct($filename = null)
    {
        parent::__construct($filename);
    }

    /**
     * autoload
     */
    public static function getAutoload($dev = null)
    {
        if (!$dev) {
            return parent::$json_decoded->autoload;
        }
        return $notation = isset(parent::$json_decoded->{'autoload-dev'}) ? parent::$json_decoded->{'autoload-dev'} : null;
    }

    /**
     * get section
     */
    public static function getSection($section_name = 'require', $dev = null, $json_object = null)
    {
        $json_object = $json_object ? : parent::$json_decoded;
        $json_object = self::object_to_array($json_object);
        if (!$dev) {
            $section_value = isset($json_object[$section_name]) ? $json_object[$section_name] : null;
            if (!$section_value) {
                print_r([__FILE__, __LINE__, get_defined_vars()]);exit;
            }
            return $section_value;
        }
        $section_name .= '-dev';
        return $notation = $json_object[$section_name];
    }

    /**
     * PSR 4
     */
    public static function getPsr4($dev = null, $format = false)
    {
        $autoload = self::getAutoload($dev);
        $notation = self::object_or_array_value($autoload, 'psr-4');
        $notation = $notation ? : null;
        (array) $array = $format ? self::getKeyValue($notation, [__METHOD__, __LINE__, __FILE__]) : $notation;
        # print_r([__FILE__, __LINE__, get_defined_vars()]);
        return $array;
    }

    public static function getPsr4Recursive($dev = null, $format = false, $json_object = null, $base_dir = null)
    {
        $autoload = self::getSection('autoload', $dev, $json_object);
        $notation = isset($autoload['psr-4']) ? $autoload['psr-4'] : null;
        (array) $array = $format ? self::getKeyValueRecursive($notation, $base_dir) : $notation;
        # print_r([__FILE__, __LINE__, get_defined_vars()]);
        return $array;
    }

    /**
     * PSR 0
     */
    public static function getPsr0($dev = null)
    {
        $autoload = self::getAutoload($dev);
        return (array) $autoload->{'psr-0'};
    }

    /**
     * files
     */
    public static function getFiles($dev = null, $format = true)
    {
        $autoload = self::getAutoload($dev);
        $notation = self::object_or_array_value($autoload, 'files');
        $notation = $notation ? : null;
        (array) $array = $format ? self::getKeyValue($notation) : $notation;
        return $array;
    }

    /**
     * classmap
     */
    public static function getClassmap($dev = null)
    {
        $autoload = self::getAutoload($dev);
        return (array) $autoload->{'classmap'};
    }

    /**
     * get require
     */
    public static function getRequire($dev = null, $json_decoded = null)
    {
        $require = self::getSection('require', $dev, $json_decoded) ? : [];
        foreach ($require as $vendor_package => $project_version) {
            if (!preg_match('/\//', $vendor_package)) {
                unset($require->{$vendor_package});
            }
        }
        return $require;
    }

    /**
     * get require composer.json
     *
     * 获取必须
     * 遍历包名，获取配置地址及内容，转为数组
     * 获取 PSR-4 键值对
     *
     */
    public static function getRequireComposerJson($dev = null, $json_decoded = null)
    {
        $require = self::getRequire($dev, $json_decoded) ? : [];
        # print_r(get_defined_vars());
        $require_recursive = [];
        foreach ($require as $vendor_package => $project_version) {
            if (!preg_match('/\//', $vendor_package)) {
                goto end;
            }
            $filename = self::$vendorDir  . $vendor_package . '/composer.json';
            $composer_json = realpath($filename);
            if (in_array($composer_json, $GLOBALS['_ANFORA']['composer_json'])) {
                goto end;
            }
            $GLOBALS['_ANFORA']['composer_json'][] = $composer_json;
            $file_contents = $composer_json ? file_get_contents($composer_json) : '{}';
            if (!$composer_json) {
                print_r([__FILE__, __LINE__, get_defined_vars()]);exit;
            }
            $json_decoded = self::object_to_array(json_decode($file_contents));
            $sub_require = self::getRequireComposerJson($dev, $json_decoded);
            $require_recursive = array_merge($require_recursive, $sub_require);
            # print_r([__FILE__, __LINE__, $sub_require]);
            $psr4 = self::getPsr4Recursive(null, true, (object) $json_decoded, realpath(self::$vendorDir  . $vendor_package));
            $json_decoded['autoload']['psr-4'] = $psr4;
            # print_r([$json_decoded, $psr4, __FILE__, __LINE__]);
            $parent_json_decoded = self::object_to_array(parent::$json_decoded);
            $json_merge =  array_merge_recursive($parent_json_decoded, $json_decoded);
            parent::$json_decoded = (object) $json_merge;
            if ('nikic/fast-route' == $vendor_package) {
                # print_r([__FILE__, __LINE__, $json_merge]);
            }
            # $require->{$vendor_package} = [$project_version, $composer_json, $json_merge];
            end:
        }
        # print_r([__FILE__, __LINE__, $require_recursive]);
        return $require;
    }

    /**
     * object to array
     */
    public static function object_to_array($object = null)
    {
        if ('object' == gettype($object)) {
            $object = (array) $object;
        }

        if ('array' == gettype($object)) {
            foreach ($object as $key => &$value) {
                $value = self::object_to_array($value);
            }
        }
        return $object;
    }

    /**
     * get object or array value
     */
    public static function object_or_array_value($object, $keyname = '')
    {
        if (is_object($object)) {
            return $object->{$keyname};
        }

        if (is_array($object)) {
            return $object[$keyname];
        }
    }


    /**
     * reset value path
     */
    public static function getKeyValue($array = [])
    {
        foreach ($array as $key => &$value) {
            if (is_array($value)) {
                $value = array_pop($value);
            }
            $value = trim($value, '/');
            if (!preg_match('/:/', $value)) {
                if (preg_match('/^(src\/|app)/', $value)) {
                    $value = BASE_DIR . '/' . $value;
                    goto end;
                }

                if (preg_match('/^vendor\//', $value)) {
                    $value = preg_replace('/^vendor\//', '', $value);
                }
                $value = self::$vendorDir . $value;
            }
            end:
            $value = realpath($value);
            $value = str_replace('\\', '/', $value);
        }
        return $array;
    }

    public static function getKeyValueRecursive($array = [], $base_dir = '')
    {
        # print_r([__FILE__, __LINE__, $array, $base_dir]);
        # print_r(debug_backtrace());
        foreach ($array as $key => &$value) {
            $value = trim($value, '/');
            if (!preg_match('/:/', $value)) {
                $value = $base_dir . '/' . $value;
                $value = realpath($value);
            }
            $value = str_replace('\\', '/', $value);
        }
        # print_r([func_get_args(), get_defined_vars(), __FILE__, __LINE__]);
        return $array;
    }

    /**
     * setting vendorDir
     */
    public static function setVendorDir($vendorDir = null)
    {
        if ($vendorDir) {
            $vendorDir .= '/';
        }
        $vendorDir = str_replace('\\', '/', $vendorDir);
        self::$vendorDir = $vendorDir;
    }

    /**
     * set super vars
     */
    public static function setSuperVars()
    {
        $GLOBALS['_ANFORA']['require'] = self::getRequireComposerJson();
        $GLOBALS['_ANFORA']['files'] = array_merge(self::getFiles(), [realpath(__DIR__ . '/../../../src/Anfora.php')]);
        $GLOBALS['_ANFORA']['psr-4'] = array_merge((array) self::getPsr4(0, true), ['Anfora\\' => realpath(__DIR__ . '/../../../src')]);
    }
}