<?php

namespace Modularity;

use Philo\Blade\Blade;

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
        add_filter('wp', array($this, 'init'));
        add_filter('is_active_sidebar', array($this, 'isActiveSidebar'), 10, 2);

        add_shortcode('modularity', array($this, 'shortcodeDisplay'));
        add_filter('the_post', array($this, 'filterNestedModuleShortocde'));

        add_filter('Modularity/Display/Markup', array($this, 'addGridToSidebar'), 10, 2);
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
                if (!is_preview() && $module->hidden == 'true') {
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
        global $wp_query;

        if (is_admin() || is_feed() || is_tax() || post_password_required()) {
            return;
        }

        $archiveSlug = \Modularity\Helper\Wp::getArchiveSlug();

        if (isset($wp_query->query['modularity_template']) && !empty($wp_query->query['modularity_template'])) {
            $this->modules = \Modularity\Editor::getPostModules($wp_query->query['modularity_template']);
            $this->options = get_option('modularity_' . $wp_query->query['modularity_template'] . '_sidebar-options');
        } elseif ($archiveSlug) {
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

        // Get sidebar arguments
        $sidebarArgs = $this->getSidebarArgs($sidebar);

        // Loop and output modules
        foreach ($modules['modules'] as $module) {
            if (!is_preview() && $module->hidden == 'true') {
                continue;
            }

            $this->outputModule($module, $sidebarArgs, \Modularity\ModuleManager::$moduleSettings[get_post_type($module)]);
        }
    }

    public function isModularitySidebarActive($sidebar)
    {
        $template = \Modularity\Helper\Post::getPostTemplate();

        //Where to look
        $paths = apply_filters('Modularity/Theme/TemplatePath', array(
            "",
            get_stylesheet_directory() . '/',
            get_template_directory() . '/',
            get_stylesheet_directory() . '/views/',
            get_template_directory() . '/views/',
        ));

        //Check if exists
        $template_exists = false;
        foreach ((array) $paths as $path) {
            if (file_exists($path.$template)) {
                $template_exists = true;
            }
        }

        if (!$template_exists) {
            $template = \Modularity\Helper\Wp::findCoreTemplates([$template, 'archive']);
        }
        $options = get_option('modularity-options');

        if (!isset($options['enabled-areas'][$template]) || !in_array($sidebar, (array) $options['enabled-areas'][$template])) {
            return false;
        }

        return true;
    }

    /**
     * Fills module object with missing params if needed
     * @param  object $module
     * @param  array $moduleSettings
     * @return object
     */
    public function fillMissingParams($module, $moduleSettings)
    {
        // Set class properties if needed
        if (empty($module->slug)) {
            foreach ($moduleSettings as $key => $value) {
                $module->$key = $value;
            }

            $module->isLegacy = true;
        }

        return $module;
    }

    /**
     * Outputs a specific module
     * @param  object $module           The module data
     * @param  array $args              The sidebar data
     * @param  array $moduleSettings    The module configuration
     * @return boolean                  True if success otherwise false
     */
    public function outputModule($module, $args = array(), $moduleSettings = array(), $echo = true)
    {
        if (!isset($args['id'])) {
            $args['id'] = 'no-id';
        }

        if (!is_object($module)) {
            return false;
        }

        $class = \Modularity\ModuleManager::$classes[$module->post_type];
        $module = new $class($module, $args);
        $module = $this->fillMissingParams($module, $moduleSettings);

        if (!$echo || !isset($moduleSettings['cache_ttl'])) {
            $moduleSettings['cache_ttl'] = 0;
        }

        $cache = new \Modularity\Helper\Cache($module->ID, array($module, $args['id']), $moduleSettings['cache_ttl']);

        if (empty($moduleSettings['cache_ttl']) || $cache->start()) {
            $moduleMarkup = $this->getModuleMarkup($module, $args);

            if (isset($module->columnWidth) && !empty($module->columnWidth)) {
                $moduleMarkup .= apply_filters('Modularity/Display/AfterModule', '</div>', $args, $module->post_type, $module->ID);
            } elseif (isset($this->options[$args['id']]['after_module']) && !empty($this->options[$args['id']]['after_module'])) {
                $moduleMarkup .= apply_filters('Modularity/Display/AfterModule', '</div>', $args, $module->post_type, $module->ID);
            } elseif (isset($args['after_widget'])) {
                $moduleMarkup .= apply_filters('Modularity/Display/AfterModule', $args['after_widget'], $args, $module->post_type, $module->ID);
            }

            $moduleMarkup = apply_filters('Modularity/Display/Markup', $moduleMarkup, $module);
            $moduleMarkup = apply_filters('Modularity/Display/' . $module->post_type . '/Markup', $moduleMarkup, $module);

            if (!$echo) {
                return $moduleMarkup;
            }

            echo $moduleMarkup;

            if (!empty($moduleSettings['cache_ttl'])) {
                $cache->stop();
            }
        }

        return true;
    }

    /**
     * Gets markup for a module
     * @param  object $module The module object
     * @param  array  $args   Module args
     * @return string
     */
    public function getModuleMarkup($module, $args)
    {
        $templatePath = $module->template();

        // Get template for legacy modules
        if (!$templatePath) {
            $templatePath = \Modularity\Helper\Wp::getTemplate($module->post_type, 'module', false);
        }

        if (!$templatePath) {
            return false;
        }

        $moduleMarkup = '';
        if (preg_match('/.blade.php$/i', $templatePath)) {
            $moduleMarkup = $this->loadBladeTemplate($templatePath, $module, $args);
        } else {
            $moduleMarkup = $this->loadTemplate($templatePath, $module, $args);
        }

        if (empty($moduleMarkup)) {
            return;
        }

        $classes = array(
            'modularity-' . $module->post_type,
            'modularity-' . $module->post_type . '-' . $module->ID
        );

        //Hide module if preview
        if (is_preview() && $module->hidden) {
            $classes[] = 'modularity-preview-hidden';
        }

        //Add selected scope class
        if (isset($module->data['meta']) && isset($module->data['meta']['module_css_scope']) && is_array($module->data['meta']['module_css_scope'])) {
            if (!empty($module->data['meta']['module_css_scope'][0])) {
                $classes[] = $module->data['meta']['module_css_scope'][0];
            }
        }

        $beforeModule = '';
        $moduleEdit = '';
        if (!(isset($args['edit_module']) && $args['edit_module'] === false) && current_user_can('edit_module', $module->ID)) {
            $moduleEdit = '<div class="modularity-edit-module"><a href="' . admin_url('post.php?post=' . $module->ID . '&action=edit&is_thickbox=true&is_inline=true') . '">' . __('Edit module', 'modularity') . ': ' . $module->data['post_type_name'] .  '</a></div>';
        }

        if (isset($module->columnWidth) && !empty($module->columnWidth)) {
            $beforeWidget = $module->columnWidth;

            $classes[] = $beforeWidget;

            $beforeModule = apply_filters('Modularity/Display/BeforeModule', '<div class="' . implode(' ', $classes) . '">', $args, $module->post_type, $module->ID);
        } elseif (isset($args['before_widget'])) {
            $beforeWidget = str_replace('%1$s', $module->post_type . '-' . $module->ID, $args['before_widget']);
            $beforeWidget = str_replace('%2$s', implode(' ', $classes), $beforeWidget);
            $beforeModule = apply_filters('Modularity/Display/BeforeModule', $beforeWidget, $args, $module->post_type, $module->ID);
        }

        $moduleMarkup = $beforeModule . $moduleEdit . $moduleMarkup;
        return $moduleMarkup;
    }

    /**
     * Check if template exists (first check for blade, then php) and renders the template
     * @param  string $view   View file
     * @param  class  $module Module class
     * @return string         Template markup
     */
    public function loadBladeTemplate($view, $module, array $args = array())
    {
        \Modularity\Helper\File::maybeCreateDir(MODULARITY_CACHE_DIR);

        if (!$module->templateDir) {
            throw new \LogicException('Class ' . get_class($module) . ' must have property $templateDir');
        }

        $template = \Modularity\Helper\Template::getModuleTemplate($view, $module);
        $templatePath = trailingslashit(dirname($template));
        $view = basename($template, '.blade.php');
        $view = basename($view, '.php');

        if (\Modularity\Helper\Template::isBlade($template)) {
            $blade = new Blade($templatePath, MODULARITY_CACHE_DIR);
            return $blade->view()->make($view, $module->data)->render();
        }

        return $this->loadTemplate($template, $module, $args);
    }

    /**
     * Renders php template for module
     * @param  string $view   View file
     * @param  class  $module Module class
     * @return string         Template markup
     */
    public function loadTemplate($view, $module, array $args = array())
    {
        ob_start();
        include $view;
        return ob_get_clean();
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

        //Get module details
        $module = \Modularity\Editor::getModule($args['id']);

        //If not valid details, abort.
        if (!is_object($module) || empty($module->post_type)) {
            return "";
        }

        //Create instance
        $class = \Modularity\ModuleManager::$classes[$module->post_type];
        $module = new $class($module, $args);

        $moduleMarkup = $this->getModuleMarkup($module, $args);
        if (empty($moduleMarkup)) {
            return;
        }

        $moduleMarkup = apply_filters('Modularity/Display/Markup', $moduleMarkup, $module);
        $moduleMarkup = apply_filters('Modularity/Display/' . $module->post_type . '/Markup', $moduleMarkup, $module);

        return '<div class="' . $module->post_type . '">' . $moduleMarkup . '</div>';
    }

    /**
     * Removes nested shortcodes
     * @param  WP_Post $post
     * @return WP_Post
     */
    public function filterNestedModuleShortocde($post)
    {
        if (is_admin()) {
            return $post;
        }

        if (substr($post->post_type, 0, 4) != 'mod-') {
            return $post;
        }

        $post->post_content = preg_replace('/\[modularity(.*)\]/', '', $post->post_content);
        return $post;
    }

    /**
     * Add container grid to specified modules, in specified sidebars
     * @param  $markup The module markup
     * @return $module Module object
     */

    public function addGridToSidebar($markup, $module)
    {
        $sidebars = apply_filters('Modularity/Module/Container/Sidebars', array());
        $modules = apply_filters('Modularity/Module/Container/Modules', array());
        $template = apply_filters('Modularity/Module/Container/Template', '<div class="container"><div class="grid"><div class="grid-xs-12">{{module-markup}}</div></div></div>');

        if (in_array($module->args['id'], $sidebars) && in_array($module->post_type, $modules)) {
            return str_replace('{{module-markup}}', $markup, $template);
        }

        return $markup;
    }
}
