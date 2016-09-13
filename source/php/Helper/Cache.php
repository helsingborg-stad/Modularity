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

    public static $keyGroup = 'mod-cache';

    public function __construct($postId, $module = '', $ttl = 3600*24)
    {
        //Set variables
        $this->postId       = $postId;
        $this->ttl          = $ttl;

        //Create hash string
        $this->hash = $this->createShortHash($module);

        //Role based key
        if (is_user_logged_in()) {
            if (isset(wp_get_current_user()->caps) && is_array(wp_get_current_user()->caps)) {
                $this->hash = $this->hash . "-auth-" . $this->createShortHash(wp_get_current_user()->caps, true);
            }
        }
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
        if ($this->isActive() && !$this->hasCache()) {
            $return_data = ob_get_clean();

            if (!empty($return_data)) {
                $cacheArray = wp_cache_get($this->postId, self::$keyGroup);

                if (!is_array($cacheArray)) {
                    $cacheArray = array();
                }

                $cacheArray[$this->hash] = $return_data.$this->fragmentTag();

                if (wp_cache_delete($this->postId, self::$keyGroup)) {
                    wp_cache_add($this->postId, $cacheArray, self::$keyGroup, $this->ttl);
                }
            }

            echo $return_data;
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
        $cacheArray = wp_cache_get($this->postId, self::$keyGroup);
        if (is_array($cacheArray) && array_key_exists($this->hash, $cacheArray)) {
            if ($print === true) {
                echo $cacheArray[$this->hash];
            }
            return $cacheArray[$this->hash];
        }
        return false;
    }

    private function fragmentTag()
    {
        return '<!-- FGC: [' . current_time("Y-m-d H:i:s", 1) .'| ' .$this->hash. ']-->';
    }

    private function isActive()
    {
        if (!defined('WP_USE_MEMCACHED') || defined('WP_USE_MEMCACHED') && !WP_USE_MEMCACHED) {
            return false;
        }

        return true;
    }

    private function createShortHash($input, $keysOnly = false)
    {
        if ($keysOnly === true && (is_array($input) || is_object($input))) {
            $input = array_keys($input);
        }

        if (is_array($input)||is_object($input)) {
            $input = substr(base_convert(md5(serialize($input)), 16, 32), 0, 12);
        } else {
            $input = substr(base_convert(md5($input), 16, 32), 0, 12);
        }

        return $input;
    }
}

/*
$cache = new Modularity\Helper\Cache($post->Id);
if ($cache->start()) {
    // Your cacheable content here
    $cache->stop();
}
*/
