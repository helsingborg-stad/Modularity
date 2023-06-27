<?php

namespace Modularity;

use Throwable;
use ComponentLibrary\Init as ComponentLibraryInit;

class Display
{
    /**
     * Holds the current post's/page's modules
     * @var array
     */
    public $modules = array();
    public $options = null;

    private static $sidebarState = []; //Holds state of sidebars.

    public function __construct()
    {
        add_filter('wp', array($this, 'init'));
        add_filter('is_active_sidebar', array($this, 'isActiveSidebar'), 10, 2);

        add_shortcode('modularity', array($this, 'shortcodeDisplay'));
        add_filter('the_post', array($this, 'filterNestedModuleShortocde'));

        add_filter('Modularity/Display/Markup', array($this, 'addGridToSidebar'), 10, 2);

        add_filter('acf/format_value/type=wysiwyg', array( $this, 'filterModularityShortcodes'), 9, 3);
        add_filter('Modularity/Display/SanitizeContent', array($this, 'sanitizeContent'), 10);
        add_filter('Modularity/Display/replaceGrid', array($this, 'replaceGridClasses'), 10);
    }

    public function replaceGridClasses($className)
    {
        $className = str_replace('grid-md-12', 'o-grid-12@md', $className);
        $className = str_replace('grid-md-9', 'o-grid-9@md', $className);
        $className = str_replace('grid-md-8', 'o-grid-8@md', $className);
        $className = str_replace('grid-md-6', 'o-grid-6@md', $className);
        $className = str_replace('grid-md-4', 'o-grid-4@md', $className);
        $className = str_replace('grid-md-3', 'o-grid-3@md', $className);

        return $className;
    }

    /**
     * Get module directory by post-type
     * @param $postType
     * @return mixed|null
     */
    public function getModuleDirectory($postType)
    {
        if (!is_dir(MODULARITY_PATH . 'source/php/Module/')) {
            return null;
        }

        foreach (glob(MODULARITY_PATH . 'source/php/Module/*') as $dir) {
            $pathinfo = pathinfo($dir);
            if (strtolower(str_replace('mod-', '', $postType)) === strtolower($pathinfo['filename'])) {
                return $pathinfo['filename'];
            }
        }

        return null;
    }

    /**
     * @param $view
     * @param array $data
     * @return bool
     * @throws \Exception
     */
    public function renderView($view, $data = array()): string
    {
        $data['sidebarContext'] = \Modularity\Helper\Context::get();

        // Adding Module path to filter
        $moduleView = MODULARITY_PATH . 'source/php/Module/' . $this->getModuleDirectory($data['post_type']) . '/views';
        $externalViewPaths = apply_filters('/Modularity/externalViewPath', []);

        if (isset($externalViewPaths[$data['post_type']])) {
            $moduleView = $externalViewPaths[$data['post_type']];
        }

        $init = new ComponentLibraryInit([$moduleView]);
        $blade = $init->getEngine();

        $filters = [
            fn ($d) => apply_filters('Modularity/Display/viewData', $d),
            fn ($d) => apply_filters("Modularity/Display/{$d['post_type']}/viewData", $d),
        ];

        $viewData = array_reduce(
            $filters,
            fn (array $d, callable $applyFilter) => $applyFilter($d),
            $data
        );

        try {
            return $blade->make($view, $viewData)->render();
        } catch (Throwable $e) {
            echo '<pre style="border: 3px solid #f00; padding: 10px;">';
            echo '<strong>' . $e->getMessage() . '</strong>';
            echo '<hr style="background: #000; outline: none; border:none; display: block; height: 1px;"/>';
            echo $e->getTraceAsString();
            echo '</pre>';
        }

        return false;
    }

    /**
     * Removes modularity shortcodes wysiwyg fields to avoid infinity loops
     * @param mixed $value  The value which was loaded from the database
     * @param mixed $postId The post ID from which the value was loaded
     * @param array $field  An array containing all the field settings for the field which was used to upload the attachment
     * @return mixed
     */
    public function filterModularityShortcodes($value, $postId, $field)
    {
        $value = preg_replace('/\[modularity(.*)\]/', '', $value);
        return $value;
    }

    /**
     * Removes modularity shortcodes from post content field to avoid infinity loops
     * @param string  $content  The post content
     * @param int     $postId   The post content
     * @return string
     */
    public function sanitizeContent($content)
    {
        $content = preg_replace('/\[modularity(.*)\]/', '', $content);
        return $content;
    }

    /**
     * New is_active_sidebar logic which includes module check
     * @param  boolean  $isActiveSidebar Original response
     * @param  string   $sidebar         Sidebar id
     * @return boolean
     */
    public function isActiveSidebar($isActiveSidebar, $sidebar)
    {
        //Just figure out the state of a sidebar once
        if (isset(self::$sidebarState[$sidebar])) {
            return self::$sidebarState[$sidebar];
        }

        $widgets = wp_get_sidebars_widgets() ?? [];
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
            return self::$sidebarState[$sidebar] = true;
        }

        return self::$sidebarState[$sidebar] = false;
    }

    /**
     * Initialize, get post's/page's modules and start output
     * @return void
     */
    public function init()
    {
        global $post;
        global $wp_query;

        if (!$wp_query->is_main_query() || empty($post)) {
            return;
        }

        if (defined('PAGE_FOR_POSTTYPE_ID') && is_numeric(PAGE_FOR_POSTTYPE_ID)) {
            $realPostID = PAGE_FOR_POSTTYPE_ID;
        } else {
            $realPostID = $post->ID;
        }

        if (is_admin() || is_feed() || is_tax() || post_password_required($realPostID)) {
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
            $this->options = get_post_meta($realPostID, 'modularity-sidebar-options', true);
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

        // Update context
        if (isset($sidebarArgs['id'])) {
            \Modularity\Helper\Context::set(
                "sidebar." . $sidebarArgs['id']
            );
        }

        // Loop and output modules
        if (isset($modules['modules']) && is_array($modules['modules']) && !empty($modules['modules'])) {
            foreach ($modules['modules'] as $module) {
                if (!is_preview() && $module->hidden == 'true') {
                    continue;
                }

                $this->outputModule($module, $sidebarArgs, \Modularity\ModuleManager::$moduleSettings[get_post_type($module)]);
            }
        }

        //Reset context
        if (isset($sidebarArgs['id'])) {
            \Modularity\Helper\Context::set(false);
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
            get_stylesheet_directory() . '/views/v3/',
            get_template_directory() . '/views/v3/',
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
        
        $cache = new \Modularity\Helper\Cache(
            $module->ID, [
                $module, 
                $args['id']
            ], 
            $moduleSettings['cache_ttl']
        );

        if (empty($moduleSettings['cache_ttl']) || $cache->start()) {
            $moduleMarkup = $this->getModuleMarkup($module, $args);
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
            'modularity-' . $module->post_type . '-' . $module->ID,
            (property_exists($module, 'columnWidth')) ? $module->columnWidth :  'o-grid-12'
        );

        //Hide module if preview
        if (is_preview() && $module->hidden) {
            $classes[] = 'modularity-preview-hidden';
        }

        //Add selected scope class
        if (isset($module->data['meta']) && isset($module->data['meta']['module_css_scope']) &&
            is_array($module->data['meta']['module_css_scope'])) {
            if (!empty($module->data['meta']['module_css_scope'][0])) {
                $classes[] = $module->data['meta']['module_css_scope'][0];
            }
        }

        // Build before & after module markup
        $beforeModule = (array_key_exists('before_widget', $args)) ? $args['before_widget'] :
            '<div id="%1$s" class="%2$s" >';
        $afterModule = (array_key_exists('after_widget', $args)) ? $args['after_widget'] : '</div>';

        // Apply filter for classes
        $classes = (array) apply_filters('Modularity/Display/BeforeModule::classes', $classes, $args, $module->post_type, $module->ID);

        // Set id (%1$s) and classes (%2$s)
        $beforeModule = sprintf($beforeModule, $module->post_type . '-' . $module->ID . '-' . uniqid(), implode(' ', $classes));
        
        // Append module edit to before markup
        if ($this->displayEditModule($module, $args)) {

            $linkParameters = [
                'post' => $module->ID ,
                'action' => 'edit',
                'is_thickbox' => 'true',
                'is_inline' => 'true'
            ]; 

            $beforeModule .= '
                <div class="modularity-edit-module">
                    <a href="' . admin_url('post.php?' . http_build_query($linkParameters)) . '">
                        ' . __('Edit module', 'modularity') . ': ' . $module->data['post_type_name'] .  '
                    </a>
                </div>
            ';
        }

        // Apply filter for before/after markup
        $beforeModule = apply_filters(
            'Modularity/Display/BeforeModule', 
            $beforeModule, $args, $module->post_type, $module->ID
        );

        $afterModule = apply_filters(
            'Modularity/Display/AfterModule', 
            $afterModule, $args, $module->post_type, $module->ID
        );

        return $beforeModule . $moduleMarkup . $afterModule;
    }

    /**
     * Determine if the edit module button should be displayed.
     * 
     * @param  class  $module   Module class
     * @param  array    $args   Module argument array
     * @return bool             If the button should be displayed or not         
     */
    private function displayEditModule($module, $args) {
        
        if(isset($args['edit_module']) && $args['edit_module'] !== false) {
            return false;
        }
        
        if(wp_doing_ajax()) {
            return false;
        }

        if(defined('REST_REQUEST')) {
            return false;
        }

        if(!current_user_can('edit_module', $module->ID)) {
            return false;
        }

        return true; 
    }

    /**
     * Check if template exists (first check for blade, then php) and renders the template
     * @param string $view View file
     * @param class $module Module class
     * @return string         Template markup
     * @throws \Exception
     */
    public function loadBladeTemplate($view, $module, array $args = array())
    {
        \Modularity\Helper\File::maybeCreateDir(MODULARITY_CACHE_DIR);

        if (!$module->templateDir) {
            throw new \LogicException('Class ' . get_class($module) . ' must have property $templateDir');
        }


        $template   = \Modularity\Helper\Template::getModuleTemplate($view, $module);
        $view       = basename($template, '.blade.php');
        $view       = basename($view, '.php');

        if (\Modularity\Helper\Template::isBlade($template)) {
            return $this->renderView($view, $module->data);
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
