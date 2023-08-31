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
     * A place to store the id of a module instance.
     * Adds block support, which dosen't require a ID (works well with null).
     *
     * @var int|null
     */
    public $ID = null;

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
     * What the block block supports
     * Example: align (full width etc)
     * @var array
     */
    public $blockSupports = array();

    /**
     * If empty block notice should be used. 
     * @var bool
     */
    public $useEmptyBlockNotice = false; 

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
     * Module mode
     * @var string
     */
    public $mode = 'module'; //May be either 'module' or 'block'.

    /**
     * Data dataFetched.
     * Keeps track if the current data is fetched by the native data fetch functionality. 
     * @var string
     */
    public $dataFetched = false; //May be either 'module' or 'block'.

    

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

        if (is_a($post, '\WP_Post') && $post->post_title) {
            $this->data['postTitle'] = $post->post_title;
        }

        // FIXME: hasModule() is not working as expected here, doesn't accept any parameters.
        if (!is_admin() && $this->hasModule($post)) {
            add_action('wp_enqueue_scripts', array($this, 'style'));
            add_action('wp_enqueue_scripts', array($this, 'script'));
        }

        add_action('save_post', function($postID, $post, $update) {
            wp_cache_delete('modularity_has_modules_' . $postID);
        }, 1, 3);
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

    public function data(): array
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
     * Get metadata for block or module.
     * @return array
     */
    protected function getFields() {
        $this->dataFetched = true;
        if(is_numeric($this->ID)) {
            return get_fields($this->ID);
        }
        return get_fields(); //Blocks
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

        //Collect all modules active

        if(!$modules = wp_cache_get('modularity_has_modules_' . $postId)) {
            $modules = \Modularity\Editor::getPostModules($postId);
            $modules = array_merge($modules, $this->getShortcodeModules($postId));
            $modules = array_merge($modules, $this->getBlocks($postId));

            //Sort out active module post types
            $modules = $this->getValueFromKeyRecursive($modules, 'post_type');

            //Set cache
            wp_cache_set('modularity_has_modules_' . $postId, $modules);
        }

        //Look for
        $moduleSlug = $this->moduleSlug;
        if (empty($moduleSlug)) {
            $moduleSlug = isset($this->data['post_type']) ? $this->data['post_type'] : null;
        }

        return apply_filters(
            'Modularity/hasModule',
            in_array($moduleSlug, $modules),
            $archiveSlug
        );
    }

    /**
     * Get values from array recursively
     *
     * @param array $haystack
     * @param string $needle
     * @return array
     */
    private function getValueFromKeyRecursive(array $haystack, $needle)
    {
        $stack = [];
        $iterator  = new \RecursiveArrayIterator($haystack);
        $recursive = new \RecursiveIteratorIterator(
            $iterator,
            \RecursiveIteratorIterator::SELF_FIRST
        );
        foreach ($recursive as $key => $value) {
            if ($key === $needle) {
                $stack[] = $value;
            }
        }
        return array_unique(array_filter($stack));
    }

    /**
     * Check for use of block modules
     * @return array Array with modules from one page sections
     */
    public function getBlocks($postId): array
    {
        if (!has_blocks($postId)) {
            return [];
        }

        if ($currentPost = get_post($postId)) {
            $blocks = parse_blocks($currentPost->post_content);

            if (is_array($blocks) && !empty($blocks)) {
                foreach ($blocks as $block) {
                    $modules[] = str_replace('acf/', 'mod-', $block['blockName']);
                }
                return $modules;
            }
        }

        return [];
    }

    /**
     * Get modules used in shortcodes
     * @param  string $post_id Current post id
     * @return array           Array with module post types
     */
    public function getShortcodeModules($post_id): array
    {
        $post = get_post($post_id);
        $pattern = get_shortcode_regex();
        $modules = array();

        if (
            is_object($post) && preg_match_all('/' . $pattern . '/s', $post->post_content, $matches)
            && array_key_exists(2, $matches)
            && in_array('modularity', $matches[2])
        ) {
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
