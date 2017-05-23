<?php

namespace Modularity\Helper;

class Template
{
    /**
     * Check if a template is blade or not
     * @param  string  $template Template path
     * @return boolean
     */
    public static function isBlade($template)
    {
        if (substr($template, -strlen('.blade.php'), strlen('.blade.php')) === '.blade.php') {
            return true;
        }

        return false;
    }

    /**
     * Search for a specific template (view)
     * @param  string             $view   View name (filename)
     * @param  \Modularity\Module $module
     * @return string                     Found template/view
     */
    public static function getModuleTemplate($view, $module)
    {
        $view = basename($view, '.blade.php');
        $view = basename($view, '.php');

        // Paths to search
        $paths = array_unique(array(
            get_stylesheet_directory() . '/templates/module/',
            get_template_directory() . '/templates/module/',
            $module->templateDir,
            MODULARITY_PATH . 'templates/',
        ));

        $paths = apply_filters('Modularity/Module/TemplatePath', $paths);
        $paths = apply_filters('Modularity/Module/' . $module->post_type . '/TemplatePath', $paths);

        // Search for blade template
        foreach ($paths as $path) {
            $file = trailingslashit($path) . $module->post_type . '/' . $view . '.blade.php';

            if (file_exists($file)) {
                return $file;
            }
        }

        // Search for php template
        foreach ($paths as $path) {
            $file = trailingslashit($path) . $module->post_type . '/' . $view . '.php';

            if (file_exists($file)) {
                return $file;
            }
        }

        return false;
    }
}
