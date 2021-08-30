<?php

namespace Modularity;

class Module
{
    /**
     * WP_Post properties will automatically be extracted to properties of this class.
     * This array contains the keys to the extracted properties.
     * @var array
     */
    public $extractedPostProperties = array();

    /**
     * The slug of the module
     * Example: image
     * @var string
     */
    public $slug = '';
    public $moduleSlug = '';

    /**
     * Singular name of the modue
     * Example: Image
     * @var string
     */
    public $nameSingular = '';

    /**
     * Plural name of the module
     * Example: Images
     * @var string
     */
    public $namePlural = '';

    /**
     * Module description
     * Shows a fixed with and height image
     * @var string
     */
    public $description = '';

    /**
     * Module icon (Base64 endoced data uri)
     * @var string
     */
    public $icon = '';

    /**
     * What the module post type should support (title and revision will be added automatically)
     * Example: array('editor', 'attributes')
     * @var array
     */
    public $supports = array();

    /**
     * Any module plugins (path to file to include)
     * @var array
     */
    public $plugin = array();

    /**
     * Cache ttl
     * @var integer
     */
    public $cacheTtl = 3600 * 24 * 7;

    /**
     * The initial setting for "hide title" of the module
     * @var boolean
     */
    public $hideTitle  = false;

    /**
     * Sidebar arguments
     * @var array
     */
    public $args = array();

    /**
     * Is the module deprecated?
     * @var boolean
     */
    public $isDeprecated = false;

    /**
     * Will the module work as a block?
     * @var boolean
     */
    public $isBlockCompatible = true;

    /**
     * A field to replace the post title if module is used as a block.
     * @var boolean
     */
    public $expectsTitleField = true;

    /**
     * Is this module a legacy module (not updated to new registration methods)
     * @var boolean
     */
    public $isLegacy = false;

    /**
     * Set to tro if only available for multisites
     * @var boolean
     */
    public $multisiteOnly = false;

    /**
     * Path to template folder for this module
     * @var string
     */
    public $templateDir = false;

    /**
     * Path to assets folder for this module
     * @var string
     */
    public $assetDir = false;

    /**
     * View data (data that will be sent to the blade view)
     * @var array
     */
    public $data = array();

    /**
     * Constructs a module
     * @param int $postId
     */
    public function __construct(\WP_Post $post = null, $args = array())
    {
        $this->args = $args;
        $this->init();

        // Defaults to the path of the class .php-file and subdir /views
        // Example: my-module/my-module.php (module class)
        //          my-module/views/        (views folder)
        if (!$this->templateDir) {
            $reflector = new \ReflectionClass(get_class($this));
            $this->templateDir = trailingslashit(dirname($reflector->getFileName())) . 'views/';
        }

        if (!$this->assetDir) {
            $reflector = new \ReflectionClass(get_class($this));
            $this->assetDir = trailingslashit(dirname($reflector->getFileName())) . 'assets/';
        }

        if (is_numeric($post)) {
            $post = get_post($post);
        }

        if (is_a($post, '\WP_Post')) {
            $this->extractPostProperties($post);
            $this->collectViewData();
        }

        add_action('admin_enqueue_scripts', array($this, 'adminEnqueue'));
        add_filter( 'the_title', array($this, 'setBlockTitle'), 10, 2 );

        if (!is_admin() && $this->hasModule()) {
            add_action('wp_enqueue_scripts', array($this, 'style'));
            add_action('wp_enqueue_scripts', array($this, 'script'));
        }
    }

    /**
     * If used as block and has a block_title field return it,
     * otherwise return the post title
     * @return string
     */
    public function setBlockTitle( $title, $id = null ) {    

        if($titleField = get_field('block_title', $this->ID)) {
            return $titleField;        
        }

        return $title;
    }

    public function init()
    {
    }

    /**
     * Method to enqueu styles when module exists on page
     * @return void
     */
    public function style()
    {
        // Put styles here
    }

    /**
     * Method to enqueu scripts when module exists on page
     * @return void
     */
    public function script()
    {
        // Put scripts here
    }

    /**
     * Enqueue for admin
     * @return void
     */
    public function adminEnqueue()
    {
        if (\Modularity\Helper\Wp::isAddOrEditOfPostType($this->moduleSlug)) {
            do_action('Modularity/Module/' . $this->moduleSlug . '/enqueue');
        }
    }

    /**
     * Extracts WP_Post properties into Module properties
     * @param  \WP_Post $post
     * @return void
     */
    private function extractPostProperties(\WP_Post $post)
    {
        foreach ($post as $key => $value) {
            $this->extractedPostProperties[] = $key;
            $this->$key = $value;
            $this->data[$key] = $value;
        }
    }

    public function data() : array
    {
        return array();
    }

    /**
     * Get module view
     * @return string
     */
    public function template()
    {
        if (!$this->isLegacy && !empty($this->slug)) {
            return $this->slug . '.blade.php';
        }

        return false;
    }

    /**
     * Get module view data
     * @return array
     */
    public function collectViewData()
    {
        $this->data = array_merge($this->data, $this->data());
    }

    /**
     * Checks if a current page/post has module(s) of this type
     * @return boolean
     */
    protected function hasModule()
    {
        global $post;

        $postId = null;
        $modules = array();
        $archiveSlug = \Modularity\Helper\Wp::getArchiveSlug();

        if ($archiveSlug) {
            $postId = $archiveSlug;
        } elseif (isset($post->ID)) {
            $postId = $post->ID;
        } else {
            return apply_filters('Modularity/hasModule', true, null);
        }

        $modules = \Modularity\Editor::getPostModules($postId);
        $modules = array_merge($modules, $this->getShortcodeModules($postId));
        $modules = array_merge($modules, $this->getOnePageModules($postId));

        $modules = json_encode($modules);

        $moduleSlug = $this->moduleSlug;
        if (empty($moduleSlug)) {
            $moduleSlug = isset($this->data['post_type']) ? $this->data['post_type'] : null;
        }

        return apply_filters('Modularity/hasModule', strpos($modules, '"post_type":"' . $moduleSlug . '"') == true, $archiveSlug);
    }

    /**
     * Get modules used in one page sections
     * @return array Array with modules from one page sections
     */
    public function getOnePageModules() : array
    {
        $modules = array();

        if (is_front_page() && is_plugin_active('modularity-onepage/modularity-onepage.php')) {
            $postStatus = array('publish');
            if (is_user_logged_in()) {
                $postStatus[] = 'private';
            }

            $sections = get_posts(array(
                'post_type' => 'onepage',
                'post_status' => $postStatus,
                'orderby' => 'menu_order',
                'order' => 'asc',
                'posts_per_page'   => -1
            ));

            foreach ($sections as $section) {
                $section_modules = \Modularity\Editor::getPostModules($section->ID);

                if (!isset($section_modules['onepage-sidebar'])) {
                    continue;
                }

                $section_modules = $section_modules['onepage-sidebar']['modules'];
                if (is_array($section_modules) && !empty($section_modules)) {
                    foreach ($section_modules as $module) {
                        if (!$module->hidden) {
                            $modules[] = $module;
                        }
                    }
                }
            }
        }

        return $modules;
    }

    /**
     * Get modules used in shortcodes
     * @param  string $post_id Current post id
     * @return array           Array with module post types
     */
    public function getShortcodeModules($post_id) : array
    {
        $post = get_post($post_id);
        $pattern = get_shortcode_regex();
        $modules = array();

        if (is_object($post) && preg_match_all( '/'. $pattern .'/s', $post->post_content, $matches)
            && array_key_exists(2, $matches)
            && in_array('modularity', $matches[2])) {

            $shortcodes = preg_replace('/[^0-9]/', '', $matches[3]);
            foreach ($shortcodes as $key => $shortcode) {
                $modules[] = array(
                    'ID' => $shortcode,
                    'post_type' => get_post_type($shortcode)
                );
            }
        }

        return $modules;
    }

    /**
     * Registers a Modularity module
     *
     * @deprecated 2.0.0
     * @deprecated No longer used by internal code and not recommended, exists as fallback for old third party modules
     * @deprecated Now creates a "ghost" module class to initialize old module type
     *
     * @param  string $slug         Module id suffix (will be prefixed with constant MODULE_PREFIX)
     * @param  string $nameSingular Singular name of the module
     * @param  string $namePlural   Plural name of the module
     * @param  string $description  Description of the module
     * @param  array  $supports     Which core post type fileds this module supports
     * @param  string $icon
     * @param  string $plugin
     * @param  int    $cache_ttl
     * @param  bool   $hideTitle
     * @return string               The prefixed module id/slug
     */
    public function register($slug, $nameSingular, $namePlural, $description, $supports = array(), $icon = null, $plugin = null, $cache_ttl = 0, $hideTitle = false)
    {
        //\Modularity\Helper\Wp::deprecatedFunction('Function $module->' . __FUNCTION__ . ' is deprecated since Modularity version 2.0.0');

        if (empty($slug)) {
            return;
        }

        add_action('Modularity/Init', function ($moduleManager) use ($slug, $nameSingular, $namePlural, $description, $supports, $icon, $plugin, $cache_ttl, $hideTitle) {
            $module = new \Modularity\Module();
            $module->slug = $slug;
            $module->nameSingular = $nameSingular;
            $module->namePlural = $namePlural;
            $module->description = $description;
            $module->supports = $supports;
            $module->icon = $icon;
            $module->plugin = $plugin;
            $module->cacheTtl = $cache_ttl;
            $module->hideTitle = $hideTitle;
            $module->isDeprecated = $this->isDeprecated;
            $module->ghost = true;

            $class = get_class($this);
            $class = explode('\\', $class);
            $moduleManager->register($module, MODULARITY_PATH . 'source/php/Module/' . end($class));
        });
    }
}
