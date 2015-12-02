<?php

namespace Modularity\Helper;

class Wp
{
    public static function getTemplate($prefix = '', $slug = '', $error = true)
    {
        $paths = apply_filters('Evaluate/TemplatePath', array(
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

        if ($error) {
            trigger_error('Modularity: Template ' . $slug . 'evaluate' . $prefix . '.php' . ' not found in any of the paths: ' . var_export($paths, true), E_USER_WARNING);
        }
    }

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

    public static function isEditor()
    {
        return isset($_GET['page']) && $_GET['page'] == 'modularity-editor';
    }
}
