<?php

namespace Modularity;

class Module
{
    const MODULE_PREFIX = 'mod';

    public static $available = array();
    public static $enabled = array();

    public function __construct()
    {
        self::$enabled = self::getEnabled();
        $this->initBundledModules();
    }

    /**
     * Get enabled module id's
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
     * Initializes bundled modules which is set to be active in the Modularity options
     * @return void
     */
    private function initBundledModules()
    {
        $directory = MODULARITY_PATH . 'source/php/Module/';

        foreach (@glob($directory . "*.php") as $filename) {
            $class = '\Modularity\Module\\' . pathinfo($filename)['filename'];
            
            if (class_exists($class)) {
	            new $class;
            }
            
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
    protected function register($slug, $nameSingular, $namePlural, $description, $supports = array(), $icon = null)
    {
        $labels = array(
            'name'               => _x($nameSingular, 'post type general name', 'modularity'),
            'singular_name'      => _x($nameSingular, 'post type singular name', 'modularity'),
            'menu_name'          => _x($namePlural, 'admin menu', 'modularity'),
            'name_admin_bar'     => _x($nameSingular, 'add new on admin bar', 'modularity'),
            'add_new'            => _x('Add New', 'book', 'modularity'),
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
            'show_ui'              => $this->showInAdminMenu(),
            'show_in_nav_menus'    => $this->showInAdminMenu(),
            'show_in_menu'         => 'modularity',
            'has_archive'          => false,
            'rewrite'              => false,
            'hierarchical'         => false,
            'menu_position'        => 100,
            'exclude_from_search'  => true,
            'menu_icon'            => $icon
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

        return $postTypeSlug;
    }
}
