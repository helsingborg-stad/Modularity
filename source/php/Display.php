<?php

namespace Modularity;

class Display
{
    /**
     * Holds the current post's/page's modules
     * @var array
     */
    public $modules = array();

    public function __construct()
    {
        add_action('wp', array($this, 'init'));
    }

    /**
     * Initialize, get post's/page's modules and start output
     * @return void
     */
    public function init()
    {
        global $post;
        $this->modules = \Modularity\Editor::getPostModules($post->ID);

        add_action('dynamic_sidebar_before', array($this, 'output'));
    }

    /**
     * Get sidebar arguments of a specific sidebar id
     * @param  string $id        The sidebar id to look for
     * @return boolean/array     false if nothing found, else the arguments in array
     */
    public function getSidebarArgs($id)
    {
        global $wp_registered_sidebars;

        if (!isset($wp_registered_sidebars[$id])) {
            return false;
        }

        return $wp_registered_sidebars[$id];
    }

    /**
     * Outputs the modules of a specific sidebar
     * @param  string $sidebar Sidebar id/slug
     * @return void
     */
    public function output($sidebar)
    {
        global $post;

        // Get modules
        $modules = $this->modules[$sidebar];

        $sidebarArgs = $this->getSidebarArgs($sidebar);

        // Loop and output modules
        foreach ($modules as $module) {
            $this->outputModule($module, $sidebarArgs);
        }
    }

    /**
     * Outputs a specific module
     * @param  object $module      The module data
     * @param  array $sidebarArgs  The sidebar data
     * @return boolean             True if success otherwise false
     */
    public function outputModule($module, $args)
    {
        $templatePath = \Modularity\Helper\Wp::getTemplate($module->post_type, 'module', false);

        if (!$templatePath) {
            return false;
        }

        setup_postdata($module);

        if (isset($args['before_widget'])) {
            $beforeWidget = str_replace('%1$s', 'modularity-' . $module->post_type . '-' . $module->ID, $args['before_widget']);
            $beforeWidget = str_replace('%2$s', 'modularity-' . $module->post_type, $beforeWidget);

            echo apply_filters('Modularity/Display/BeforeModule', $beforeWidget, $args, $module->post_type, $module->ID);
        }

        include $templatePath;

        if (isset($args['after_widget'])) {
            echo apply_filters('Modularity/Display/AfterModule', $args['after_widget'], $args, $module->post_type, $module->ID);
        }

        return true;
    }
}
