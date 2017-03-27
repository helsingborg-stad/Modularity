<?php

namespace Modularity;

class ModuleManager
{
    /**
     * Prefix for module slugs
     * @var  string
     */
    const MODULE_PREFIX = 'mod-';

    /**
     * Holds all module's settings
     * @var array
     */
    public static $moduleSettings = array();

    /**
     * Holds a list of available (initialized) modules
     * @var array
     */
    public static $available = array();

    /**
     * Holds a list of enabled modules
     * @var array
     */
    public static $enabled = array();

    /**
     * Holds a list of deprecated modules
     * @var array
     */
    public static $deprecated = array();

    /**
     * Available width settings for modules
     * @var array
     */
    public static $widths = array();

    public function __construct()
    {
        self::$available = $this->getAvailable(false);
        $this->init();
    }

    /**
     * Get available modules (WP filter)
     * @return array
     */
    public function getAvailable($getBundled = true)
    {
        if ($getBundled) {
            $bundeled = $this->getBundeled();
            self::$available = array_merge(self::$available, $bundeled);
        }

        return apply_filters('Modularity/Modules', self::$available);
    }

    /**
     * Gets bundeled modules
     * @return array
     */
    public function getBundeled() : array
    {
        $directory = MODULARITY_PATH . 'source/php/Module/';
        $bundeled = array();

        foreach (@glob($directory . "*", GLOB_ONLYDIR) as $folder) {
            $bundeled[$folder] = basename($folder);
        }

        return $bundeled;
    }

    /**
     * Initializes modules
     * @return void
     */
    public function init()
    {
        foreach (self::$available as $path => $module) {
            $path = trailingslashit($path);
            $source = $path . $module . '.php';
            $namespace = \Modularity\Helper\File::getNamespace($source);

            if (!$namespace) {
                continue;
            }

            require_once $source;
            $class = $namespace . '\\' . $module;

            $this->register($class, $path);
        }
    }

    /**
     * Registers a module with all it's components (post types etc)
     * @param  string $class Module class (\Modularity\Module extension)
     * @param  string $path  Path to module
     * @return string        Module's post type slug
     */
    public function register(string $class, string $path = '')
    {
        // Get post type slug
        $postTypeSlug = self::prefixSlug($class::$slug);

        // Set labels
        $labels = array(
            'name'               => _x($class::$nameSingular, 'post type general name', 'modularity'),
            'singular_name'      => _x($class::$nameSingular, 'post type singular name', 'modularity'),
            'menu_name'          => _x($class::$namePlural, 'admin menu', 'modularity'),
            'name_admin_bar'     => _x($class::$nameSingular, 'add new on admin bar', 'modularity'),
            'add_new'            => _x('Add New', 'add new button', 'modularity'),
            'add_new_item'       => sprintf(__('Add new %s', 'modularity'), $class::$nameSingular),
            'new_item'           => sprintf(__('New %s', 'modularity'), $class::$nameSingular),
            'edit_item'          => sprintf(__('Edit %s', 'modularity'), $class::$nameSingular),
            'view_item'          => sprintf(__('View %s', 'modularity'), $class::$nameSingular),
            'all_items'          => sprintf(__('Edit %s', 'modularity'), $class::$namePlural),
            'search_items'       => sprintf(__('Search %s', 'modularity'), $class::$namePlural),
            'parent_item_colon'  => sprintf(__('Parent %s', 'modularity'), $class::$namePlural),
            'not_found'          => sprintf(__('No %s', 'modularity'), $class::$namePlural),
            'not_found_in_trash' => sprintf(__('No %s in trash', 'modularity'), $class::$namePlural)
        );

        // Set args
        $args = array(
            'labels'               => $labels,
            'description'          => __($class::$description, 'modularity'),
            'public'               => false,
            'publicly_queriable'   => false,
            'show_ui'              => true,
            'show_in_nav_menus'    => false,
            'show_in_menu'         => ($this->showInAdminMenu()) ? 'modularity' : false,
            'has_archive'          => false,
            'rewrite'              => false,
            'hierarchical'         => false,
            'menu_position'        => 100,
            'exclude_from_search'  => false,
            'menu_icon'            => $class::$icon,
            'supports'             => array_merge($class::$supports, array('title', 'revisions')),
            'capabilities'         => array(
                'edit_post'          => 'edit_module',
                'edit_posts'         => 'edit_modules',
                'edit_others_posts'  => 'edit_other_modules',
                'publish_posts'      => 'publish_modules',
                'read_post'          => 'read_module',
                'read_private_posts' => 'read_private_posts',
                'delete_post'        => 'delete_module'
            ),
            'map_meta_cap'         => true
        );

        // Get menu icon
        if (empty($args['menu_icon']) && $icon = $this->getIcon($path, $class)) {
            $args['menu_icon'] = $icon;
            $args['menu_icon_auto_import'] = true;
        }

        // Register the post type if module is enabled
        if (in_array($postTypeSlug, self::$enabled)) {
            add_action('init', function () use ($postTypeSlug, $args) {
                register_post_type($postTypeSlug, $args);
            });

            // Require plugins
            if (is_array($class::$plugin)) {
                foreach ($class::$plugin as $plugin) {
                    require_once $plugin;
                }
            }
        }

        // Check if module is deprecated
        if ($class::$isDeprecated) {
            \Modularity\ModuleManager::$deprecated[] = $postTypeSlug;
        }

        // Store settings of each module in static var
        self::$moduleSettings[$postTypeSlug] = array(
            'slug' => $class::$slug,
            'singular_name' => $class::$nameSingular,
            'plural_name' => $class::$namePlural,
            'description' => $class::$description,
            'supports' => $class::$supports,
            'icon' => $class::$icon,
            'plugin' => $class::$plugin,
            'cache_ttl' => $class::$cacheTtl,
            'hide_title' => $class::$hideTitle
        );

        return $postTypeSlug;
    }

    /**
     * Prefixes module post type slug if needed
     * @param  string $slug
     * @return string
     */
    public static function prefixSlug(string $slug) : string
    {
        if (substr($slug, 0, strlen(self::MODULE_PREFIX)) !== self::MODULE_PREFIX) {
            $slug = self::MODULE_PREFIX . $slug;
        }

        if (strlen($slug) > 20) {
            $slug = substr($slug, 0, 20);
        }

        $slug = strtolower($slug);

        return $slug;
    }

    /**
     * Get bundeled icon
     * @param  string $path Path to module folder
     * @return string
     */
    public function getIcon(string $path, $class) : string
    {
        if (file_exists($path . '/assets/icon.svg')) {
            return file_get_contents($path . '/assets/icon.svg');
        }

        // If fail to load (may happen on some systems) TODO: Make this more fancy
        if (file_exists($path . preg_replace('/\s+/', '', ucwords($class::$nameSingular)). '/assets/icon.svg')) {
            return file_get_contents($path . preg_replace('/\s+/', '', ucwords($class::$nameSingular)). '/assets/icon.svg');
        }

        return '';
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
}
