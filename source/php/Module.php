<?php

namespace Modularity;

class Module
{
    const MODULE_PREFIX = 'mod';

    /**
     * The modules slug (id)
     * @var boolean/string "false" if not set otherwise string id
     */
    public $moduleSlug = false;

    /**
     * Available and enabled modules
     * @var array
     */
    public static $available = array();
    public static $enabled = array();
    public static $options = array();

    public function __construct()
    {
        self::$enabled = self::getEnabled();
        $this->initBundledModules();
    }

    /**
     * Get enabled modules id:s
     * @return array
     */
    public static function getEnabled()
    {
        $options = get_option('modularity-options');

        if (!isset($options['enabled-modules'])) {
            return array();
        }

        return $options['enabled-modules'];
    }

    /**
     * ACTION: Modularity/Module/<MODULE SLUG>/enqueue
     * Enqueue assets (css and/or js) to the add/edit pages of the given module
     * @return void
     */
    public function enqueue()
    {
        if ($this->isAddOrEditOfPostType()) {
            do_action('Modularity/Module/' . $this->moduleSlug . '/enqueue');
        }
    }

    /**
     * Enqueue styles
     * @return void
     */
    public function style()
    {

    }

    /**
     * Enqueue scripts
     * @return void
     */
    public function script()
    {

    }

    /**
     * Checks if a page has a modules
     * @return boolean
     */
    public function hasModule()
    {
        global $post;
        $modules = array();

        if (is_post_type_archive() || is_archive() || is_home() || is_search() || is_404()) {
            if (is_home()) {
                $archiveSlug = 'archive-post';
            } elseif (is_search()) {
                $archiveSlug = 'search';
            } elseif (is_404()) {
                $archiveSlug = 'e404';
            } else {
                $archiveSlug = 'archive-' . get_post_type_object(get_post_type())->rewrite['slug'];
            }

            $modules = \Modularity\Editor::getPostModules($archiveSlug);
        } else {
            $modules = \Modularity\Editor::getPostModules($post->ID);
        }

        $modules = json_encode($modules);

        return strpos($modules, '"post_type":"' . $this->moduleSlug . '"') == true;
    }

    /**
     * Check if current page is add new/edit post
     * @return boolean
     */
    public function isAddOrEditOfPostType()
    {
        global $current_screen;

        return $current_screen->base == 'post'
                && $current_screen->id == $this->moduleSlug
                && (
                    $current_screen->action == 'add' || (isset($_GET['action']) && $_GET['action'] == 'edit')
                );
    }

    /**
     * Initializes bundled modules which is set to be active in the Modularity options
     * @return void
     */
    private function initBundledModules()
    {
        $directory = MODULARITY_PATH . 'source/php/Module/';

        foreach (@glob($directory . "*", GLOB_ONLYDIR) as $folder) {
            $class = '\Modularity\Module\\' . basename($folder) . '\\' . basename($folder);

           // if (class_exists($class)) {
                new $class;
            //}
        }
    }

    /**
     * Check if the module should be displayed in the admin menu
     * @return boolean
     */
    public function showInAdminMenu()
    {
        $options = get_option('modularity-options');

        if (isset($options['show-modules-in-menu']) && $options['show-modules-in-menu'] == 'on') {
            return true;
        }

        return false;
    }

    /**
     * Registers a Modularity module
     * @param  string $slug         Module id suffix (will be prefixed with constant MODULE_PREFIX)
     * @param  string $nameSingular Singular name of the module
     * @param  string $namePlural   Plural name of the module
     * @param  string $description  Description of the module
     * @param  array  $supports     Which core post type fileds this module supports
     * @return string               The prefixed module id/slug
     */
    protected function register($slug, $nameSingular, $namePlural, $description, $supports = array(), $icon = null, $plugin = null)
    {
        $labels = array(
            'name'               => _x($nameSingular, 'post type general name', 'modularity'),
            'singular_name'      => _x($nameSingular, 'post type singular name', 'modularity'),
            'menu_name'          => _x($namePlural, 'admin menu', 'modularity'),
            'name_admin_bar'     => _x($nameSingular, 'add new on admin bar', 'modularity'),
            'add_new'            => _x('Add New', 'add new button', 'modularity'),
            'add_new_item'       => __('Add New ' . $nameSingular, 'modularity'),
            'new_item'           => __('New ' . $nameSingular, 'modularity'),
            'edit_item'          => __('Edit ' . $nameSingular, 'modularity'),
            'view_item'          => __('View ' . $nameSingular, 'modularity'),
            'all_items'          => __('All ' . $namePlural, 'modularity'),
            'search_items'       => __('Search ' . $namePlural, 'modularity'),
            'parent_item_colon'  => __('Parent ' . $namePlural . ':', 'modularity'),
            'not_found'          => __('No ' . $namePlural . ' found.', 'modularity'),
            'not_found_in_trash' => __('No ' . $namePlural . ' found in Trash.', 'modularity')
        );

        $args = array(
            'labels'               => $labels,
            'description'          => __($description, 'modularity'),
            'public'               => false,
            'publicly_queriable'   => false,
            'show_ui'              => true,
            'show_in_nav_menus'    => false,
            'show_in_menu'         => ($this->showInAdminMenu()) ? 'modularity' : false,
            'has_archive'          => false,
            'rewrite'              => false,
            'hierarchical'         => false,
            'menu_position'        => 100,
            'exclude_from_search'  => true,
            'menu_icon'            => $icon,
            'supports'             => array_merge($supports, array('title'))
        );

        /**
         * Builds the post type id/slug from the $slug parameter and prefix
         * Max 20 characters long
         * @var string
         */
        $postTypeSlug = self::MODULE_PREFIX . '-' . $slug;
        if (strlen($postTypeSlug) > 20) {
            $postTypeSlug = substr($postTypeSlug, 0, 20);
        }

        $postTypeSlug = strtolower($postTypeSlug);

        /**
         * Try to get an icon if not defined in module configuration file.
         * Max 20 characters long
         * @var string
         */
        if (empty($args['menu_icon']) && file_exists(MODULARITY_PATH . "/source/php/Module/" . ucwords($slug) . "/assets/icon.svg")) {
            $args['menu_icon'] = file_get_contents(MODULARITY_PATH . "/source/php/Module/" . ucwords($slug) . "/assets/icon.svg");
            $args['menu_icon_auto_import'] = true;
        }

        //If fail to load (may happen on some systems) TODO: Make this more fancy
        if (empty($args['menu_icon']) && file_exists(MODULARITY_PATH . "/source/php/Module/" . preg_replace('/\s+/', '', ucwords($nameSingular)). "/assets/icon.svg")) {
            $args['menu_icon'] = file_get_contents(MODULARITY_PATH . "/source/php/Module/" . preg_replace('/\s+/', '', ucwords($nameSingular))  . "/assets/icon.svg");
            $args['menu_icon_auto_import'] = true;
        }

        /**
         * Register the post type on WP Init
         */
        if (in_array($postTypeSlug, self::$enabled)) {
            add_action('init', function () use ($postTypeSlug, $args) {
                register_post_type($postTypeSlug, $args);
            });
        }

        /**
         * Add to available modules
         */
        self::$available[$postTypeSlug] = $args;

        $this->moduleSlug = $postTypeSlug;
        add_action('admin_enqueue_scripts', array($this, 'enqueue'));
        add_action('wp_enqueue_scripts', array($this, 'style'));
        add_action('wp_enqueue_scripts', array($this, 'script'));

        add_action('add_meta_boxes', array($this, 'shortcodeMetabox'));

        /**
         * Include plugin
         */
        if (!is_null($plugin) && file_exists(__DIR__ . '/../../plugins/'. $plugin)) {
            require __DIR__.'/../../plugins/' . $plugin;
        }

        return $postTypeSlug;
    }

    /**
     * Shortcode metabox
     * @return void
     */
    public function shortcodeMetabox()
    {
        if (!$this->moduleSlug) {
            return;
        }

        add_meta_box('modularity-shortcode', 'Modularity Shortcode', function () {
            global $post;
            echo '<p>';
            _e('Copy and paste this shortcode to display the module inline.', 'modularity');
            echo '</p><p>';
            echo '<label><input type="checkbox" class="modularity-inline-template" checked> Use inline template</label>';
            echo '<pre style="overflow: auto;background:#f9f9f9;border:1px solid #ddd;padding:5px;">[modularity id="' . $post->ID . '"<span></span>]</pre>';
            echo '</p>';

            echo "<script>
                jQuery(document).ready(function ($) {
                    $('.modularity-inline-template').prop('checked', true);

                    $('.modularity-inline-template').on('change', function () {
                        if ($(this).prop('checked') == false) {
                            $(this).parents('.inside').find('pre span').text(' inline=\"false\"');
                        } else {
                            $(this).parents('.inside').find('pre span').text('');
                        }
                    });
                });
            </script>";
        }, $this->moduleSlug, 'side', 'default');
    }
}
