<?php

namespace Modularity\Helper;

use Modularity\Helper\ModuleUsageById;

class ModuleUsageByName
{
    private static $cache = [];
    private static $cacheGroup = 'modularity-module-usage';
    private static $cacheTtl = 3600;

    private static function primeCache($postType, $cacheKey)
    {
        if (!isset(self::$cache[$cacheKey])) {
            self::$cache[$cacheKey] = wp_cache_get($postType, self::$cacheGroup);
        }
    }

    private static function setCache($postType, $data, $cacheKey)
    {
        self::$cache[$cacheKey] = $data;
        wp_cache_set($postType, $data, self::$cacheGroup, self::$cacheTtl);
        return self::$cache[$cacheKey];
    }

    public static function getModuleUsageByName($postType)
    {
        $cacheKey = __FUNCTION__ . ':' . $postType;
        self::primeCache($postType, $cacheKey);

        if (isset(self::$cache[$cacheKey])) {
            return self::$cache[$cacheKey];
        }
        
        $modules = self::getPagesByModuleName($postType);
        $blocks = self::getBlocksByModuleName($postType);

        return self::setCache($postType, array_unique(array_merge($modules, $blocks)), $cacheKey);
    }

    public static function getBlocksByModuleName($postType)
    {
        $cacheKey = __FUNCTION__ . ':' . $postType;
        self::primeCache($postType, $cacheKey);

        if (isset(self::$cache[$cacheKey])) {
            return self::$cache[$cacheKey];
        }

        global $wpdb;

        $postType = str_replace('mod-', 'acf/', $postType);
        $pages = $wpdb->get_results(
            "SELECT ID
            FROM $wpdb->posts
            WHERE post_content LIKE '%{$postType}%'
            AND post_status = 'publish'"
        );

        $result = is_array($pages) ? array_column($pages, 'ID') : [];
        return self::setCache($postType, $result, $cacheKey);
    }

    public static function getPagesByModuleName($postType)
    {
        $cacheKey = __FUNCTION__ . ':' . $postType;
        self::primeCache($postType, $cacheKey);

        if (isset(self::$cache[$cacheKey])) {
            return self::$cache[$cacheKey];
        }

        $posts = self::getPostsByType($postType);
        $pages = [];
        foreach ($posts as $post) {
            if (empty($post->ID)) {
                continue;
            }

            $moduleUsageOnPages = self::getModuleUsageById($post->ID);
            if (!empty($moduleUsageOnPages)) {
                $pages = array_merge($pages, array_column($moduleUsageOnPages, 'post_id'));
            }
        }

        $result = is_array($pages) ? $pages : [];
        return self::setCache($postType, $result, $cacheKey);
    }

    public static function getPostsByType($postType)
    {
        $cacheKey = __FUNCTION__ . ':' . $postType;
        self::primeCache($postType, $cacheKey);

        if (isset(self::$cache[$cacheKey])) {
            return self::$cache[$cacheKey];
        }

        $args = array(
            'post_type'      => $postType,
            'posts_per_page' => -1,
            'post_status'    => 'publish',
        );

        $result = get_posts($args);
        return self::setCache($postType, $result, $cacheKey);
    }

    private static function getModuleUsageById($postId)
    {
        return ModuleUsageById::getModuleUsageById($postId);
    }
}
