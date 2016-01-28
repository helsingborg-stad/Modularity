<?php

namespace Modularity;

class Display
{
    /**
     * Holds the current post's/page's modules
     * @var array
     */
    public $modules = array();
    public $options = null;

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

        if (is_admin() || !$post) {
            return;
        }

        $this->modules = \Modularity\Editor::getPostModules($post->ID);
        $this->options = get_post_meta($post->ID, 'modularity-sidebar-options', true);

        add_action('dynamic_sidebar_before', array($this, 'outputBefore'));
        add_action('dynamic_sidebar_after', array($this, 'outputAfter'));

        add_filter('sidebars_widgets', array($this, 'hideWidgets'));
    }

    /**
     * Unsets (hides) widgets from sidebar if set in Modularity options
     * @param  array $sidebars Sidebars and widgets
     * @return array           Filtered sidebars and widgets
     */
    public function hideWidgets($sidebars)
    {
        $retSidebars = $sidebars;

        foreach ($retSidebars as $sidebar => $widgets) {
            if (!empty($retSidebars[$sidebar]) && (!isset($this->options[$sidebar]['hide_widgets']) || $this->options[$sidebar]['hide_widgets'] != 'true')) {
                continue;
            }

            $retSidebars[$sidebar] = array('');
        }

        return $retSidebars;
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
     * Check if modules should be outputted before widgets
     * @param  string $sidebar Current sidebar
     * @return boolean|void
     */
    public function outputBefore($sidebar)
    {
        if (!isset($this->options[$sidebar]['hook']) || $this->options[$sidebar]['hook'] != 'before') {
            return false;
        }

        $this->output($sidebar);
    }

    /**
     * Check if modules should be outputted after widgets
     * @param  string $sidebar Current sidebar
     * @return boolean|void
     */
    public function outputAfter($sidebar)
    {
        if (isset($this->options[$sidebar]['hook']) && $this->options[$sidebar]['hook'] != 'after') {
            return false;
        }

        $this->output($sidebar);
    }

    /**
     * Outputs the modules of a specific sidebar
     * @param  string $sidebar Sidebar id/slug
     * @return void
     */
    public function output($sidebar)
    {
        if (!isset($this->modules[$sidebar])) {
            return;
        }

        // Get modules
        $modules = $this->modules[$sidebar];

        $sidebarArgs = $this->getSidebarArgs($sidebar);

        // Loop and output modules
        foreach ($modules['modules'] as $module) {
            if ($module->hidden == 'true') {
                continue;
            }

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

        if (isset($this->options[$args['id']]['before_module']) && !empty($this->options[$args['id']]['before_module'])) {
            $beforeWidget = str_replace('%1$s', 'modularity-' . $module->post_type . '-' . $module->ID, $this->options[$args['id']]['before_module']);
            $beforeWidget = str_replace('%2$s', 'modularity-' . $module->post_type, $beforeWidget);

            echo apply_filters('Modularity/Display/BeforeModule', $beforeWidget, $args, $module->post_type, $module->ID);
        }
        else if (isset($args['before_widget'])) {
            $beforeWidget = str_replace('%1$s', 'modularity-' . $module->post_type . '-' . $module->ID, $args['before_widget']);
            $beforeWidget = str_replace('%2$s', 'modularity-' . $module->post_type, $beforeWidget);

            echo apply_filters('Modularity/Display/BeforeModule', $beforeWidget, $args, $module->post_type, $module->ID);
        }

        include $templatePath;

        if (isset($this->options[$args['id']]['after_module']) && !empty($this->options[$args['id']]['after_module'])) {
            echo apply_filters('Modularity/Display/AfterModule', $this->options[$args['id']]['after_module'], $args, $module->post_type, $module->ID);
        }
        else if (isset($args['after_widget'])) {
            echo apply_filters('Modularity/Display/AfterModule', $args['after_widget'], $args, $module->post_type, $module->ID);
        }

        return true;
    }
}
