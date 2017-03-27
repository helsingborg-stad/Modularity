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
     * Holds a list of registered modules
     * @var array
     */
    public static $registered = array();

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
        self::$enabled = self::getEnabled();
        self::$registered = $this->getRegistered(false);
        $this->init();

        // Hide title option
        add_action('edit_form_before_permalink', array($this, 'hideTitleCheckbox'));
        add_action('save_post', array($this, 'saveHideTitleCheckbox'), 10, 2);

        // Shortcode metabox
        add_action('add_meta_boxes', array($this, 'shortcodeMetabox'));

        // Add usage metabox
        add_action('add_meta_boxes', array($this, 'whereUsedMetaBox'));

        // Description meta box
        add_action('add_meta_boxes', array($this, 'descriptionMetabox'), 5);
        add_action('save_post', array($this, 'descriptionMetaboxSave'));
    }

    /**
     * Get available modules (WP filter)
     * @return array
     */
    public function getRegistered($getBundled = true)
    {
        if ($getBundled) {
            $bundeled = $this->getBundeled();
            self::$registered = array_merge(self::$registered, $bundeled);
        }

        return apply_filters('Modularity/Modules', self::$registered);
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
        foreach (self::$registered as $path => $module) {
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

        // Add to list of available modules
        \Modularity\ModuleManager::$available[$postTypeSlug] = $args;

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

    /**
     * Adds checkbox to post edit page to hide title
     * @return void
     */
    public function hideTitleCheckbox()
    {
        global $post;

        if (substr($post->post_type, 0, 4) != 'mod-') {
            return;
        }

        $current = self::$moduleSettings[$post->post_type]['hide_title'];

        if (strlen(get_post_meta($post->ID, 'modularity-module-hide-title', true)) > 0) {
            $current = boolval(get_post_meta($post->ID, 'modularity-module-hide-title', true));
        }

        $checked = checked(true, $current, false);

        echo '<div>
            <label style="cursor:pointer;">
                <input type="checkbox" name="modularity-module-hide-title" value="1" ' . $checked . '>
                ' . __('Hide title', 'modularity') . '
            </label>
        </div>';
    }

    /**
     * Saves the hide title checkboc
     * @param  int $postId
     * @param  WP_Post $post
     * @return void
     */
    public function saveHideTitleCheckbox($postId, $post)
    {
        if (substr($post->post_type, 0, 4) != 'mod-') {
            return;
        }

        if (!isset($_POST['modularity-module-hide-title'])) {
            update_post_meta($post->ID, 'modularity-module-hide-title', 0);
            return;
        }

        update_post_meta($post->ID, 'modularity-module-hide-title', 1);
        return;
    }

    /**
     * Shortcode metabox
     * @return void
     */
    public function shortcodeMetabox()
    {
        add_meta_box('modularity-shortcode', 'Modularity Shortcode', function () {
            global $post;
            echo '<p>';
            echo __('Copy and paste this shortcode to display the module inline.', 'modularity');
            echo '</p><p>';
            echo '<label><input type="checkbox" class="modularity-inline-template" checked> Use inline template</label>';
            echo '<textarea style="margin-top:10px; overflow: hidden;width: 100%;height:30px;background:#f9f9f9;border:1px solid #ddd;padding:5px;">[modularity id="' . $post->ID . '"]</textarea>';
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
        }, self::$enabled, 'side', 'default');
    }

    /**
     * Metabox that shows where the module is used
     * @return void
     */
    public function whereUsedMetaBox()
    {
        global $post;
        $module = $this;

        if (is_null($post)) {
            return;
        }

        $usage = self::getModuleUsage($post->ID);

        add_meta_box('modularity-usage', 'Module usage', function () use ($module, $usage) {
            if (count($usage) == 0) {
                echo '<p>' . __('This modules is not used yet.', 'modularity')  . '</p>';
                return;
            }

            echo '<p>' . __('This module is used on the following places:', 'modularity') . '</p><p><ul class="modularity-usage-list">';

            foreach ($usage as $page) {
                echo '<li><a href="' . get_permalink($page->post_id) . '">' . $page->post_title . '</a></li>';
            }

            echo '</ul></p>';
        }, self::$enabled, 'side', 'default');
    }

    /**
     * Search database for where the module is used
     * @param  integer $id Module id
     * @return array       List of pages where the module is used
     */
    public static function getModuleUsage($id, $limit = false)
    {
        global $wpdb;

        // Normal modules
        $query = "
            SELECT
                {$wpdb->postmeta}.post_id,
                {$wpdb->posts}.post_title,
                {$wpdb->posts}.post_type
            FROM {$wpdb->postmeta}
            LEFT JOIN
                {$wpdb->posts} ON ({$wpdb->postmeta}.post_id = {$wpdb->posts}.ID)
            WHERE
                {$wpdb->postmeta}.meta_key = 'modularity-modules'
                AND ({$wpdb->postmeta}.meta_value REGEXP '.*\"postid\";s:[0-9]+:\"{$id}\".*')
                AND {$wpdb->posts}.post_type != 'revision'
            ORDER BY {$wpdb->posts}.post_title ASC
        ";

        $modules = $wpdb->get_results($query, OBJECT);

        // Shortcode modules
        $query = "
            SELECT
                {$wpdb->posts}.ID AS post_id,
                {$wpdb->posts}.post_title,
                {$wpdb->posts}.post_type
            FROM {$wpdb->posts}
            WHERE
                ({$wpdb->posts}.post_content REGEXP '([\[]modularity.*id=\"{$id}\".*[\]])')
                AND {$wpdb->posts}.post_type != 'revision'
            ORDER BY {$wpdb->posts}.post_title ASC
        ";

        $shortcodes = $wpdb->get_results($query, OBJECT);

        $result = array_merge($modules, $shortcodes);

        if (is_numeric($limit)) {
            if (count($result) > $limit) {
                $sliced = array_slice($result, $limit);
            } else {
                $sliced = $result;
            }

            return (object) array(
                'data' => $sliced,
                'more' => (count($result) > 0 && count($sliced) > 0) ? count($result) - count($sliced) : 0
            );
        }

        return $result;
    }

    /**
     * Description metabox content
     * @return void
     */
    public function descriptionMetabox()
    {
        add_meta_box(
            'modularity-description',
            'Module description',
            function () {
                $description = get_post_meta(get_the_id(), 'module-description', true);
                include MODULARITY_TEMPLATE_PATH . 'editor/modularity-module-description.php';
            },
            self::$enabled,
            'normal',
            'high'
        );
    }

    /**
     * Saves the description
     * @return void
     */
    public function descriptionMetaboxSave()
    {
        if (!isset($_POST['modularity-module-description'])) {
            return;
        }

        update_post_meta(intval($_POST['post_ID']), 'module-description', trim($_POST['modularity-module-description']));
    }
}
