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
            if (isset($sidebarArgs['before_widget'])) {
                $beforeWidget = str_replace('%1$s', 'modularity-' . $module->post_type . '-' . $module->ID, $sidebarArgs['before_widget']);
                $beforeWidget = str_replace('%2$s', 'modularity-' . $module->post_type, $beforeWidget);

                echo apply_filters('Modularity/Display/BeforeModule', $beforeWidget, $module->post_type, $module->ID);
            }

            echo $module->post_title . '<br>';

            if (isset($sidebarArgs['after_widget'])) {
                echo apply_filters('Modularity/Display/AfterModule', $sidebarArgs['after_widget'], $module->post_type, $module->ID);
            }
        }
    }
}
