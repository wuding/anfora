# Class Ext\JSON\ComposerJSON



## Properties

| #    | Property   | Type | Default Value | Description |
| ---- | ---------- | ---- | ------------- | ----------- |
| 1    | $vendorDir |      |               |             |
|      |            |      |               |             |
|      |            |      |               |             |



## Methods

| #    | Method                   | Type | Return Value | Description              |
| ---- | ------------------------ | ---- | ------------ | ------------------------ |
| 1    | __construct()            |      |              |                          |
| 2    | getAutoload()            |      |              |                          |
| 3    | getSection()             |      |              |                          |
| 4    | getPsr4()                |      |              |                          |
| 5    | getPsr4Recursive()       |      |              |                          |
| 6    | getPsr0()                |      |              |                          |
| 7    | getFiles()               |      |              |                          |
| 8    | getClassmap()            |      |              |                          |
| 9    | getRequire()             |      |              |                          |
| 10   | getRequireComposerJson() |      |              |                          |
| 11   | object_to_array()        |      |              |                          |
| 12   | object_or_array_value()  |      |              |                          |
| 13   | getKeyValue()            |      |              |                          |
| 14   | getKeyValueRecursive()   |      |              |                          |
| 15   | setVendorDir()           |      |              | Setting Vendor Directory |
| 16   | setSuperVars()           |      |              | Setting Super Variables  |



## Details

### __construct($filename = null)

傳入配置文件地址



### setVendorDir($vendorDir = null)

設置供應商目錄



### setSuperVars()

聲明加載器的文件配置屬性：require(-dev), autoload(-dev) : files, psr-4, psr-0, classmap



---



### getRequireComposerJson($dev = null, $json_decoded = null)

通過必須的庫導入相應的配置文件；

提取鍵值對



### getFiles($dev = null, $format = true)

獲取要加載的文件列表



### getPsr0($dev = null)



### getPsr4($dev = null, $format = false)

獲取要查找的命名空間類名與對應目錄



### getPsr4Recursive($dev = null, $format = false, $json_object = null, $base_dir = null)

同上，更多的配置項與傳入值



### getClassmap($dev = null)



---



### getSection($section_name = 'require', $dev = null, $json_object = null)

獲取配置項鍵值對



### getRequire($dev = null, $json_decoded = null)

獲取必要的包名隊列



### getAutoload($dev = null)

獲取自動加載項鍵值對



### getKeyValue($array = [])

將配置轉換為可用的絕對鍵值對



### getKeyValueRecursive($array = [], $base_dir = '')

如某某



---



### object_to_array($object = null)

轉換類型：對象變爲數組



### object_or_array_value($object, $keyname = '')

獲取返回的對象或數組的值