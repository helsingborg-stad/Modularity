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

        // Search for Modularity v1 template (deprecated template structure)
        foreach ($paths as $path) {
            $file = trailingslashit($path) . 'modularity-' . $module->post_type . '.php';

            if (file_exists($file)) {
                trigger_error('The ' . $module->post_type . ' module is using a deprecated template type. Please use blade template instead. Refer to the documentation at https://github.com/helsingborg-stad/Modularity/', E_USER_NOTICE);
                return $file;
            }
        }

        // Search for blade template version 3
        if (apply_filters('Modularity/Module/TemplateVersion3', false)) {
            foreach ($paths as $path) {
                $filename = $view . '-v3.blade.php';
                $fileWithSubfolder = trailingslashit($path) . trailingslashit($module->post_type) . $filename;
                $fileWithoutSubfolder = trailingslashit($path) . $filename;

                if (file_exists($fileWithSubfolder)) {
                    return $fileWithSubfolder;
                }

                if (file_exists($fileWithoutSubfolder)) {
                    return $fileWithoutSubfolder;
                }
            }
        }

        // Search for blade template
        foreach ($paths as $path) {
            $filename = $view . '.blade.php';
            $fileWithSubfolder = trailingslashit($path) . trailingslashit($module->post_type) . $filename;
            $fileWithoutSubfolder = trailingslashit($path) . $filename;

            if (file_exists($fileWithSubfolder)) {
                return $fileWithSubfolder;
            }

            if (file_exists($fileWithoutSubfolder)) {
                return $fileWithoutSubfolder;
            }
        }

        // Search for php template
        foreach ($paths as $path) {
            $filename = $view . '.php';
            $fileWithSubfolder = trailingslashit($path) . trailingslashit($module->post_type) . $filename;
            $fileWithoutSubfolder = trailingslashit($path) . $filename;

            if (file_exists($fileWithSubfolder)) {
                return $fileWithSubfolder;
            }

            if (file_exists($fileWithoutSubfolder)) {
                return $fileWithoutSubfolder;
            }
        }

        return false;
    }
}
