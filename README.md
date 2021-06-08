# Strcache
PHP Cache Class

## Class-Info

| Info | Value |
| :--- | :---- |
| Declaration | class Strcache |
| Datei | Strcache.php |
| Date/Time modify File | 2021-06-08 10:42:14 |
| File-Size | 4938 Byte |
| MD5 File | 1748923e2c6e41f8c24ce9ffa8c2f88b |
| Version | 1.2 |

## Public Methods

| Methods and Parameter | Description/Comments |
| :-------------------- | :------------------- |
| public function __construct($fct,$fileName = null) | create new strcache object<br>param $fct callbackfunktion, must return new content as string<br>param $fileName filename for cachefile (default strcache.dat) |
| public static function create($fct,$fileName = null) | create a new Object |
| public function setCallbackFunction($fct) | set a new function to fetch content  |
| public function setCacheFileName($fileName) | set new CacheFile |
| public function getContent($maxAge = &quot;0 Sec&quot;) | maxAge: string rel. DateTime or numeric (Seconds)<br>return Cache as string, &quot;&quot; if error<br>if Cache older as maxAge, $fct called to fetch new Content<br>More see Method Status |
| public function clear() | cache file delete<br>return true if delete ok or file not exists |
| public function status() | return cache status<br>-1: Status Unknown<br>0: Clear<br>1: New, $fct was called succesfull for new Content or after refresh or setContent<br>2: Normal<br>3: Old, $fct was called with error for new Content |
| public function dateTimeModify() | return DateTime Modify Cache <br>or false if Cachefile not exists or Error |

## Constants

| Declaration/Name | Value | Description/Comments |
| :--------------- | :---- | :------------------- |
|  const CACHE_UNKNOWN = -1; | -1 |   |
|  const CACHE_EMPTY = 0; | 0 |   |
|  const CACHE_NEW = 1; //$fct was called | 1 |  $fct was called  |
|  const CACHE_NORMAL = 2; | 2 |   |
|  const CACHE_OLD = 3; //$fct was called with error | 3 |  $fct was called with error  |
|  const CACHE_ERROR = 4; | 4 |   |

