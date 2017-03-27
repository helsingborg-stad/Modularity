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
     * Path to template folder for this module
     * @var string
     */
    public $templateDir = false;

    /**
     * View data (data that will be sent to the blade view)
     * @var array
     */
    public $data = array();

    /**
     * Constructs a module
     * @param int $postId
     */
    public function __construct(\WP_Post $post = null)
    {
        if (is_numeric($post)) {
            $post = get_post($post);
        }

        if (is_a($post, '\WP_Post')) {
            $this->extractPostProperties($post);
        }

        add_action('admin_enqueue_scripts', array($this, 'adminEnqueue'));

        if (!is_admin() && $this->hasModule()) {
            add_action('wp_enqueue_scripts', array($this, 'style'));
            add_action('wp_enqueue_scripts', array($this, 'script'));
        }
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
        }
    }

    /**
     * Get module view
     * @return string
     */
    public function template()
    {
        if (!empty($this->slug)) {
            return $this->slug . '.blade.php';
        }

        return false;
    }

    /**
     * Get module view data
     * @return array
     */
    public function getViewData()
    {
        $data = $this->data;

        foreach ($this->extractedPostProperties as $property) {
            $data[$property] = $this->$property;
        }

        return $data;
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
            return apply_filters('Modularity/hasModule', false, null);
        }

        $modules = \Modularity\Editor::getPostModules($postId);
        $modules = json_encode($modules);

        return apply_filters('Modularity/hasModule', strpos($modules, '"post_type":"' . $this->moduleSlug . '"') == true, $archiveSlug);
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

            $moduleManager->register($module);
        });
    }
}
