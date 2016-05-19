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
        add_filter('is_active_sidebar', array($this, 'isActiveSidebar'), 10, 2);

        add_shortcode('modularity', array($this, 'shortcodeDisplay'));
    }

    /**
     * New is_active_sidebar logic which includes module check
     * @param  boolean  $isActiveSidebar Original response
     * @param  string   $sidebar         Sidebar id
     * @return boolean
     */
    public function isActiveSidebar($isActiveSidebar, $sidebar)
    {
        $widgets = wp_get_sidebars_widgets();
        $widgets = array_map('array_filter', $widgets);
        $visibleModules = false;

        if (isset($this->modules[$sidebar]) && count($this->modules[$sidebar]) > 0) {
            foreach ($this->modules[$sidebar]['modules'] as $module) {
                if ($module->hidden == 'true') {
                    continue;
                }

                $visibleModules = true;
            }
        }

        $hasWidgets = !empty($widgets[$sidebar]);
        $hasModules = ($visibleModules && isset($this->modules[$sidebar]) && count($this->modules[$sidebar]) > 0);

        if ($hasWidgets || $hasModules) {
            return true;
        }

        return false;
    }

    /**
     * Initialize, get post's/page's modules and start output
     * @return void
     */
    public function init()
    {
        global $post;

        if (is_admin()) {
            return;
        }

        $archiveSlug = \Modularity\Helper\Wp::getArchiveSlug();

        if ($archiveSlug) {
            $this->modules = \Modularity\Editor::getPostModules($archiveSlug);
            $this->options = get_option('modularity_' . $archiveSlug . '_sidebar-options');
        } else {
            $this->modules = \Modularity\Editor::getPostModules($post->ID);
            $this->options = get_post_meta($post->ID, 'modularity-sidebar-options', true);
        }

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
        if (!isset($this->modules[$sidebar]) || !$this->isModularitySidebarActive($sidebar)) {
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

    public function isModularitySidebarActive($sidebar)
    {
        $template = \Modularity\Helper\Post::getPostTemplate();
        $template = \Modularity\Helper\Wp::findCoreTemplates([$template, 'archive']);
        $options = get_option('modularity-options');

        if (is_home()) {
            $template = 'home';
        }

        if (!isset($options['enabled-areas'][$template]) || !in_array($sidebar, $options['enabled-areas'][$template])) {
            return false;
        }

        return true;
    }

    /**
     * Outputs a specific module
     * @param  object $module      The module data
     * @param  array $sidebarArgs  The sidebar data
     * @return boolean             True if success otherwise false
     */
    public function outputModule($module, $args = array())
    {
        $templatePath = \Modularity\Helper\Wp::getTemplate($module->post_type, 'module', false);

        if (!$templatePath) {
            return false;
        }

        ob_start();
        include $templatePath;
        $moduleMarkup = ob_get_clean();

        if (strlen($moduleMarkup) === 0) {
            return;
        }

        if (isset($module->columnWidth) && !empty($module->columnWidth)) {
            $beforeWidget = $module->columnWidth;
            echo apply_filters('Modularity/Display/BeforeModule', '<div class="' . $beforeWidget . ' modularity-' . $module->post_type . ' modularity-' . $module->post_type . '-' . $module->ID . '">', $args, $module->post_type, $module->ID);
        } elseif (isset($args['before_widget'])) {
            $beforeWidget = str_replace('%1$s', 'modularity-' . $module->post_type . '-' . $module->ID, $args['before_widget']);
            $beforeWidget = str_replace('%2$s', 'modularity-' . $module->post_type, $beforeWidget);

            echo apply_filters('Modularity/Display/BeforeModule', $beforeWidget, $args, $module->post_type, $module->ID);
        }

        echo $moduleMarkup;

        if (isset($module->columnWidth) && !empty($module->columnWidth)) {
            echo apply_filters('Modularity/Display/AfterModule', '</div>', $args, $module->post_type, $module->ID);
        } elseif (isset($this->options[$args['id']]['after_module']) && !empty($this->options[$args['id']]['after_module'])) {
            echo apply_filters('Modularity/Display/AfterModule', '</div>', $args, $module->post_type, $module->ID);
        } elseif (isset($args['after_widget'])) {
            echo apply_filters('Modularity/Display/AfterModule', $args['after_widget'], $args, $module->post_type, $module->ID);
        }

        return true;
    }

    /**
     * Display module with shortcode
     * @param  array $args Args
     * @return string      Html markup
     */
    public function shortcodeDisplay($args)
    {
        $args = shortcode_atts(array(
            'id' => false,
            'inline' => true
        ), $args);

        if (!is_numeric($args['id'])) {
            return;
        }

        $module = get_post($args['id']);

        if (substr($module->post_type, 0, 4) != 'mod-') {
            return;
        }

        $templatePath = \Modularity\Helper\Wp::getTemplate($module->post_type, 'module-inline', false);

        if (!$templatePath || $args['inline'] !== true) {
            $templatePath = \Modularity\Helper\Wp::getTemplate($module->post_type, 'module', false);
        }

        if (!$templatePath) {
            return false;
        }

        ob_start();
        include $templatePath;
        $moduleMarkup = ob_get_clean();

        return $moduleMarkup;
    }
}
