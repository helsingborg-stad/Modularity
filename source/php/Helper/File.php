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
        $source = file_get_contents($path);

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
}
