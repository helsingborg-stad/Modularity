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
        if(!file_exists($path)) {
            return '';
        }

        $source = file_get_contents($path); 

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
}
