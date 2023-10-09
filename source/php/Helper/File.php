<?php

namespace Modularity\Helper;

class File
{
    /**
     * Get PHP namespace from file
     * @param  string $source Path to file
     * @return string         Namespace
     */
    public static function getNamespace(string $path) : string
    {        
        if(!self::fileExists($path)) {
            return '';
        }

        $source = self::fileGetContents($path); 

        if($source === false) {
            add_action('admin_notices', function() use($path) {
                $malfunctionalPlugin = array_pop(get_plugins( "/" . explode( '/', plugin_basename( $path ))[0]));
                printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr('notice notice-error'), esc_html("ERROR: Could not find module definition (file) in " . $malfunctionalPlugin['Name']));
            });
        }

        $tokens = token_get_all($source);
        $count = count($tokens);
        $i = 0;
        $namespace = '';
        $namespaceFound = false;

        while ($i < $count) {
            $token = $tokens[$i];

            if (is_array($token) && $token[0] === T_NAMESPACE) {
                // Found namespace declaration
                while (++$i < $count) {
                    if ($tokens[$i] === ';') {
                        $namespaceFound = true;
                        $namespace = trim($namespace);
                        break;
                    }

                    $namespace .= is_array($tokens[$i]) ? $tokens[$i][1] : $tokens[$i];
                }

                break;
            }

            $i++;
        }

        if (!$namespaceFound) {
            return '';
        }

        return $namespace;
    }

    /**
     * Creates directory if needed
     * @param  string $path
     * @return string
     */
    public static function maybeCreateDir($path)
    {
        if (file_exists($path)) {
            return $path;
        }

        mkdir($path, 0777, true);
        return $path;
    }

    /**
     * Check if a file exists, cache in redis. 
     *
     * @param   string  The file path
     * @param   integer Time to store positive result
     * @param   integer Time to store negative result
     *
     * @return  bool    If the file exists or not.
     */
    public static function fileExists($filePath, $expireFound = 0, $expireNotFound = 86400)
    {
        //Unique cache value
        $uid = "mod_file_exists_cache_" . md5($filePath); 

        //If in cahce, found
        if(wp_cache_get($uid)) {
            return true;
        }

        //If not in cache, look for it, if found cache. 
        if(file_exists($filePath)) {
            wp_cache_set($uid, true, '', $expireFound);
            return true;
        }

        //Opsie, file not found
        wp_cache_set($uid, false, '', $expireNotFound); 
        return false; 
    }

     /**
     * Check if a file exists, cache in redis. 
     *
     * @param   string  The file path
     * @param   integer Time to store positive result
     * @param   integer Time to store negative result
     *
     * @return  bool    If the file exists or not.
     */
    public static function fileGetContents($filePath, $expire = 86400)
    {
        //Unique cache value
        $uid = "mod_file_get_contents_cache_" . md5($filePath); 

        //If in cahce, found
        $cachedContents = $contents = wp_cache_get($uid); 
        if($cachedContents !== false) {
            return $cachedContents;
        }

        //If not in cache, look for it, if found cache. 
        $contents = file_get_contents($filePath); 

        //Store in cache
        wp_cache_set($uid, $contents, '', $expire); 

        //Return results
        return $contents;
    }

    /**
     * Glob files with caching to improve performance.
     *
     * This function uses PHP's glob function to search for files that match the given pattern.
     * It also utilizes caching to store and retrieve the results, improving performance for
     * repeated calls to the same pattern.
     *
     * @param string $pattern      The file glob pattern to search for files.
     * @param int    $flags        Flags to pass to the glob function (optional, default is 0).
     * @param int    $expireFound  Cache expiration time for found results in seconds (optional, default is 0, which means no expiration).
     * @param int    $expireNotFound Cache expiration time for not found results in seconds (optional, default is 86400 seconds or 24 hours).
     *
     * @return array|false An array of matched files or false if no matches were found.
     */
    public static function glob($pattern, $flags = 0, $expireFound = 0, $expireNotFound = 86400)
    {
        // Unique cache value
        $uid = "mod_glob_cache_" . md5($pattern);

        // If in cache, return cached result
        $cachedResult = wp_cache_get($uid);
        if ($cachedResult !== false) {
            return $cachedResult;
        }

        // If not in cache, use glob to search for files
        $result = glob($pattern, $flags);

        // Cache the result
        if ($result !== false) {
            wp_cache_set($uid, $result, '', $expireFound);
        } else {
            wp_cache_set($uid, [], '', $expireNotFound);
        }

        return $result;
    }

}
