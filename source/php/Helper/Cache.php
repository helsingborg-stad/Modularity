<?php

namespace Modularity\Helper;

class Cache
{
    /**
     * Fragment cache in memcached
     * @param  string $postId      The post id that you want to cache
     * @param  string $ttl         The time that a cache should live
     * @param  string $hash        Any input data altering output result as a concatinated string/array/object.
     * @return string              The request response
     */

    private $postId = null;
    private $ttl = null;
    private $hash = null;
    private $keyGroup = null;

    public static $keyGroupPrefix = 'mod-cache-';
    public static $keyGroupAuth = 'auth';
    public static $keyGroupNoAuth = 'noauth';

    public function __construct($postId, $module = '', $ttl = 3600*24)
    {
        //Set variables
        $this->postId       = $postId;
        $this->ttl          = $ttl;

        //Create hash string
        if (is_array($module)||is_object($module)) {
            $this->hash     = substr(base_convert(md5(serialize($module)), 16, 32), 0, 12);
        } else {
            $this->hash     = substr(base_convert(md5($module), 16, 32), 0, 12);
        }

        //Key Group
        $this->keyGrop = self::$keyGroupPrefix . (is_user_logged_in() ? self::$keyGroupAuth : self::$keyGroupNoAuth);
    }

    public static function clearCache($postId)
    {
        if (wp_is_post_revision($postId) || get_post_status($postId) != 'publish') {
            return;
        }

        //Clear cache for logged in and looged out
        wp_cache_delete($postId, self::$keyGroupPrefix . self::$keyGroupAuth);
        wp_cache_delete($postId, self::$keyGroupPrefix . self::$keyGroupNoAuth);
    }

    public function start()
    {
        if (!$this->isActive()) {
            return true;
        }

        if (!$this->hasCache()) {
            ob_start();
            return true;
        }

        $this->getCache(true);

        return false;
    }

    public function stop()
    {
        if ($this->isActive()) {
            $return_data = ob_get_flush();

            if (!empty($return_data)) {
                $cacheArray = wp_cache_get($this->postId, $this->keyGroup);

                if (!is_array($cacheArray)) {
                    $cacheArray = array();
                }

                $cacheArray[$this->hash] = $return_data.$this->timeStampTag();

                wp_cache_delete($this->postId, $this->keyGroup);

                wp_cache_add($this->postId, $cacheArray, $this->keyGroup, $this->ttl);
            }
        }
    }

    private function hasCache()
    {
        if (!$this->isActive()) {
            return false;
        }

        return !empty($this->getCache(false));
    }

    private function getCache($print = true)
    {
        $cacheArray = wp_cache_get($this->postId, $this->keyGroup);

        if (is_array($cacheArray) && array_key_exists($this->hash, $cacheArray)) {
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

    private function isActive()
    {
        if (!defined('WP_USE_MEMCACHED') || defined('WP_USE_MEMCACHED') && !WP_USE_MEMCACHED) {
            return false;
        }

        return true;
    }
}

/*
$cache = new Modularity\Helper\Cache($post->Id);
if ($cache->start()) {
    // Your cacheable content here
    $cache->stop();
}
*/
