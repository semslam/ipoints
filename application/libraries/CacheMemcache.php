<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CacheMemcache {
    var $iTtl = 600; // Time To Live
    var $bEnabled = false; // Memcache enabled?
    var $oCache = null;
    // constructor
    public function CacheMemcache() {

        if (class_exists('Memcache')) {
            $this->oCache = new Memcache();
            $this->bEnabled = true;
            $URL = $this->input->ip_address();
            $POST = $_SERVER['HTTP_HOST'];
            if (! $this->oCache->connect($URL,$POST))  { // Instead 'localhost' here can be IP
                $this->oCache = null;
                $this->bEnabled = false;
            }
        }
    }
    // get data from cache server
   public static function getData($sKey) {
       $cache = new static();
        $vData = $cache->oCache->get($sKey);
        return false === $vData ? null : $vData;
    }
    // save data to cache server, defalut time is 5 minit
   public static function setData($sKey, $vData, $time = 0) {
        //Use MEMCACHE_COMPRESSED to store the item compressed (uses zlib).
        $cache = new static();
        $timer = (empty($time)) ? $cache->iTtl : $time;
        return $cache->oCache->set($sKey, $vData, 0, $timer);
    }
    // delete data from cache server
    public function delData($sKey) {
        $cache = new static();
        return $cache->oCache->delete($sKey);
    }
}
