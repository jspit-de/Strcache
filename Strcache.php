<?php
/**
.---------------------------------------------------------------------------.
|  Software: Strcache - PHP Cache Class                                     |
|   Version: 1.2                                                            |
|   Modify : 2021-06-08                                                     |
| ------------------------------------------------------------------------- |
| Copyright (c) 2015..2021 Peter Junk. All Rights Reserved.                 |
| ------------------------------------------------------------------------- |
|   License: Distributed under the Lesser General Public License (LGPL)     |
|            http://www.gnu.org/copyleft/lesser.html                        |
| This program is distributed in the hope that it will be useful - WITHOUT  |
| ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or     |
| FITNESS FOR A PARTICULAR PURPOSE.                                         |
'---------------------------------------------------------------------------'
 * 
 */

class Strcache{

  const CACHE_UNKNOWN = -1;
  const CACHE_EMPTY = 0;
  const CACHE_NEW = 1;  //$fct was called
  const CACHE_NORMAL = 2;
  const CACHE_OLD = 3;  //$fct was called with error
  const CACHE_ERROR = 4;
 
  private $cacheFileName = 'strcache.dat';
  private $callbackFkt = NULL;
  private $cacheStatus = self::CACHE_UNKNOWN;
  private $cacheDateTime;
  
 /*
  * create new strcache object
  * param $fct callbackfunktion, must return new content as string
  * param $fileName filename for cachefile (default strcache.dat)
  */ 
  public function __construct($fct,$fileName = null)
  {
    if(is_callable($fct)) {
      $this->setCallbackFunction($fct);
    }
    else {
      throw new InvalidArgumentException("first constructor parameter of class ".__CLASS__." must be callable");
    }
    
    if($fileName !== null) $this->setCacheFileName($fileName);
  }
  
 /*
  * create a new Object
  */
  public static function create($fct,$fileName = null)
  {
    return new self($fct,$fileName);
  }
 
 /*
  * set a new function to fetch content 
  */
  public function setCallbackFunction($fct)
  {
    if(is_callable($fct)) {
      $this->callbackFkt = $fct;
    }
    else {
      throw new InvalidArgumentException("parameter for Method ".__METHOD__." of class ".__CLASS__." must be callable");
    }
    return $this;
  }

 /*
  * set new CacheFile
  */
  public function setCacheFileName($fileName)
  {
    $this->cacheFileName = $fileName;
    clearstatcache();
    return $this;
  }

 /*
  * maxAge: string rel. DateTime or numeric (Seconds)
  * return Cache as string, "" if error
  * if Cache older as maxAge, $fct called to fetch new Content
  * More see Method Status
  */  
  public function getContent($maxAge = "0 Sec")
  {
    if(is_numeric($maxAge)) $maxAge .= " Sec";
    if(!is_readable($this->cacheFileName) or 
        filemtime($this->cacheFileName) <= strtotime("Now - ".$maxAge)) { 
      //Cachefile not exists or to Old 
      $this->refresh();
    }
    else {
      $this->cacheStatus = self::CACHE_NORMAL;
    }
    if(is_readable($this->cacheFileName)) {
      return file_get_contents($this->cacheFileName);
    }
    return ""; //empty string for error
  }
  
 /*
  * cache file delete
  * return true if delete ok or file not exists
  */  
  public function clear()
  {
    $ok = true;
    if(file_exists($this->cacheFileName)){
      $ok = unlink($this->cacheFileName);
      if($ok) $this->cacheStatus = self::CACHE_EMPTY;
      clearstatcache();
    }
    return $ok;
  }
  
 /*
  * return cache status
  * -1: Status Unknown
  *  0: Clear
  *  1: New, $fct was called succesfull for new Content or after refresh or setContent
  *  2: Normal
  *  3: Old, $fct was called with error for new Content
  */
  public function status()
  {
    return $this->cacheStatus;
  }

 /*
  * return DateTime Modify Cache 
  * or false if Cachefile not exists or Error
  */
  public function dateTimeModify()
  {
    if(is_readable($this->cacheFileName)) {
      return date_create()->setTimestamp(filemtime($this->cacheFileName));
    }
    return false;
  }
  
 /*
  * refresh content and set status
  * @return : void
  */
  private function refresh()
  {
    $fct = $this->callbackFkt;
    $content = $fct();  //return string or false
    if(is_string($content) AND $content != "") {
      $ok = file_put_contents($this->cacheFileName, $content);
      if($ok === false) {
        trigger_error('Can not write Cache-File "'.$this->cacheFileName.'"', E_USER_WARNING);
        $this->cacheStatus = self::CACHE_UNKNOWN; 
      }
      else { 
        $this->cacheStatus = self::CACHE_NEW;
        clearstatcache();
      }
    }
    else{
      $this->cacheStatus = self::CACHE_OLD;  
    }
  }
  
  
}
