<?php

namespace Modularity\Helper;

class Template
{
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
            $module->templateDir
        ));

        //General filter
        $paths = apply_filters(
            'Modularity/Module/TemplatePath', 
            $paths
        );

        //Specific filter
        $paths = apply_filters(
            'Modularity/Module/' . $module->slug . '/TemplatePath', 
            $paths
        );

        // Search for blade template
        foreach ($paths as $path) {
            $filename = $view . '.blade.php';

            $fileWithSubfolder      = trailingslashit($path) . trailingslashit($module->post_type) . $filename;
            $fileWithoutSubfolder   = trailingslashit($path) . $filename;

            if (\Modularity\Helper\File::fileExists($fileWithSubfolder)) {
                return $fileWithSubfolder;
            }

            if (\Modularity\Helper\File::fileExists($fileWithoutSubfolder)) {
                return $fileWithoutSubfolder;
            }
        }

        return false;
    }
}
