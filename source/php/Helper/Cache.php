<?php

namespace Modularity\Helper;

class Cache
{
    /**
     * Output Cache
     * @param  string $postId      The post id that you want to cache
     * @param  string $ttl         The time that a cache sould live
     * @param  string $hash        Any input data altering output result as a concatinated string/array/object.
     * @return string              The request response
     */

    private $postId = null;
    private $ttl = null;

    public static $keyGroup = 'mod-obj-cache';

    public function __construct($postId, $hash = '', $ttl = 3600*24)
    {
        //Set variables
        $this->postId       = $postId;
        $this->ttl          = $ttl;

        //Create hash string
        $this->hash_key     = substr(base_convert(md5($hash), 16, 32), 0, 12);
    }

    public static function clearCache($postId)
    {
        if (wp_is_post_revision($postId) || get_post_status($postId) != 'publish') {
            return;
        }

        wp_cache_delete($postId, self::$keyGroup);
    }

    public function start()
    {
        if (!$this->hasCache()) {
            ob_start();
            return true;
        }

        $this->getCache(true);
        return false;
    }

    public function stop()
    {
        $return_data = ob_get_flush();

        if (!empty($return_data)) {

            $cacheArray = wp_cache_get($this->postId, $this->keyGroup);

            if (!is_array($cacheArray)) {
                $cacheArray = array();
            }

            $cacheArray[] = $return_data.$this->timeStampTag();

            wp_cache_add($this->postId, $cacheArray, $this->keyGroup, $this->ttl);

        }
    }

    private function hasCache()
    {
        return !empty($this->getCache(false));
    }

    private function getCache($print = true)
    {
        $cacheArray = wp_cache_get($this->postId, $this->keyGroup);

        if (array_key_exists($this->hash, $cacheArray)) {
            if ($print === true) {
                echo $cacheArray[$this->hash];
            }
            return $cacheArray[$this->hash];
        }
        return false;
    }

    private function timeStampTag()
    {
        return '<!-- Fragment cache time: ' . date("Y-m-d H:i:s") .'-->';
    }
}

/*
$cache = new Modularity\Helper\Cache($post->Id);
if ($cache->start()) {
    // Your cacheable content here
    $cache->stop();
}
*/
