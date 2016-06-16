<?php

namespace Modularity\Helper;

class Wp
{
    /**
     * Get core templates
     * @return array Core templates found
     */
    public static function getCoreTemplates($extension = false)
    {
        $paths = apply_filters('Modularity/CoreTemplatesSearchPaths', array(
            get_stylesheet_directory(),
            get_template_directory()
        ));

        $fileExt = apply_filters('Modularity/CoreTemplatesSearchFileExtension', array(
            '.php',
            '.blade.php'
        ));

        $search = array(
            'index',
            'comments',
            'front-page',
            'home',
            'single',
            'single-*',
            'archive',
            'archive-*',
            'page',
            'page-*',
            'category',
            'category-*',
            'author',
            'date',
            'search',
            'attachment',
            'image'
        );

        $templates = array();

        foreach ($paths as $path) {
            foreach ($search as $pattern) {
                foreach ($fileExt as $ext) {
                    $foundTemplates = array();
                    foreach (glob($path . '/' . $pattern . $ext) as $found) {
                        $basename = str_replace(array('.blade.php', '.php'), '', basename($found));

                        if ($extension) {
                            $foundTemplates[$basename] = basename($found);
                        } else {
                            $foundTemplates[$basename] = str_replace(array('.blade.php', '.php'), '', basename($found));
                        }
                    }

                    $templates = array_merge($templates, $foundTemplates);
                }
            }
        }

        $templates = array_unique($templates);

        return $templates;
    }

    /**
     * Get core templates
     * @return array Core templates found
     */
    public static function findCoreTemplates($templates = null, $extension = false)
    {
        $paths = apply_filters('Modularity/CoreTemplatesSearchPaths', array(
            get_stylesheet_directory(),
            get_template_directory()
        ));

        $fileExt = apply_filters('Modularity/CoreTemplatesSearchFileExtension', array(
            '.php',
            '.blade.php'
        ));

        $search = $templates;

        if (is_null($search) || !is_array($search)) {
            return false;
        }

        foreach ($paths as $path) {
            foreach ($search as $pattern) {
                foreach ($fileExt as $ext) {
                    $foundTemplates = array();
                    foreach (glob($path . '/' . $pattern . $ext) as $found) {
                        $basename = str_replace(array('.blade.php', '.php'), '', basename($found));

                        if ($extension) {
                            return basename($found);
                        } else {
                            return str_replace(array('.blade.php', '.php'), '', basename($found));
                        }
                    }
                }
            }
        }

        return false;
    }

    /**
     * Tries to get the template path
     * Checks the plugin's template folder, the parent theme's templates folder and the current theme's template folder
     * @param  string  $prefix The filename without prefix
     * @param  string  $slug   The directory
     * @param  boolean $error  Show errors or not
     * @return string          The path to the template to use
     */
    public static function getTemplate($prefix = '', $slug = '', $error = true)
    {
        $paths = apply_filters('Modularity/Module/TemplatePath', array(
            get_stylesheet_directory() . '/templates/',
            get_template_directory() . '/templates/',
            MODULARITY_PATH . 'templates/',
        ));

        $slug = apply_filters('Modularity/TemplatePathSlug', $slug ? $slug . '/' : '', $prefix);
        $prefix = $prefix ? '-'.$prefix : '';

        foreach ($paths as $path) {
            $file = $path . $slug . 'modularity' . $prefix.'.php';

            if (file_exists($file)) {
                return $file;
            }
        }

        error_log('Modularity: Template ' . $slug . 'modularity' . $prefix . '.php' . ' not found in any of the paths: ' . var_export($paths, true));

        if ($error) {
            trigger_error('Modularity: Template ' . $slug . 'modularity' . $prefix . '.php' . ' not found in any of the paths: ' . var_export($paths, true), E_USER_WARNING);
        }
    }

    /**
     * Gets site information
     * @return array
     */
    public static function getSiteInfo()
    {
        $siteInfo = array(
            'name' => get_bloginfo('name'),
            'url' => esc_url(home_url('/')),
        );

        return $siteInfo;
    }

    /**
     * Check if the add question form is opened in thickbox iframe
     * @return boolean
     */
    public static function isThickBox()
    {
        $referer = (isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : null;

        if ((isset($_GET['is_thickbox']) && $_GET['is_thickbox'] == 'true')
            || strpos($referer, 'is_thickbox=true') > -1) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Check if editor mode (Modularity)
     * @return boolean
     */
    public static function isEditor()
    {
        return isset($_GET['page']) && $_GET['page'] == 'modularity-editor';
    }

    /**
     * Fetches current archive slug
     * @return mixed (string, boolean) False if not archive else archive slug
     */
    public static function getArchiveSlug()
    {
        $archiveSlug = false;

        if (is_post_type_archive() || is_archive() || is_home() || is_search() || is_404()) {
            if (is_home()) {
                $archiveSlug = 'archive-post';
            } elseif (is_post_type_archive() && is_search()) {
                $archiveSlug = 'archive-' . get_post_type_object(get_post_type())->rewrite['slug'];
            } elseif (is_search()) {
                $archiveSlug = 'search';
            } elseif (is_404()) {
                $archiveSlug = 'e404';
            } elseif (is_author()) {
                $archiveSlug = 'author-' . get_the_author_meta('ID');
            } else {
                $archiveSlug = 'archive-' . get_post_type_object(get_post_type())->rewrite['slug'];
            }
        }

        return $archiveSlug;
    }
}
