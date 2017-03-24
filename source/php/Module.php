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
     * Indicates wheather the module is deprecated or not
     * @var boolean
     */
    public $isDeprecated = false;

    /**
     * Available and enabled modules
     * @var array
     */
    public static $available = array();
    public static $widths = array();
    public static $deprecated = array();
    public static $enabled = array();
    public static $options = array();
    public static $moduleSettings = array();

    public function __construct()
    {
        self::$enabled = self::getEnabled();
        $this->initBundledModules();

        // Hide title option
        add_action('edit_form_before_permalink', array($this, 'hideTitleCheckbox'));
        add_action('save_post', array($this, 'saveHideTitleCheckbox'), 10, 2);
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
     * (PLACEHOLDER) Enqueue styles
     * @return void
     */
    public function style()
    {
    }

    /**
     * (PLACEHOLDER) Enqueue scripts
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

            if (class_exists($class)) {
                new $class;
            } elseif (function_exists('error_log')) {
                error_log(__("Error: Could not init '".$class."' module.", 'modularity'));
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
    protected function register($slug, $nameSingular, $namePlural, $description, $supports = array(), $icon = null, $plugin = null, $cache_ttl = 0, $hideTitle = false)
    {
        $labels = array(
            'name'               => _x($nameSingular, 'post type general name', 'modularity'),
            'singular_name'      => _x($nameSingular, 'post type singular name', 'modularity'),
            'menu_name'          => _x($namePlural, 'admin menu', 'modularity'),
            'name_admin_bar'     => _x($nameSingular, 'add new on admin bar', 'modularity'),
            'add_new'            => _x('Add New', 'add new button', 'modularity'),
            'add_new_item'       => sprintf(__('Add new %s', 'modularity'), $nameSingular),
            'new_item'           => sprintf(__('New %s', 'modularity'), $nameSingular),
            'edit_item'          => sprintf(__('Edit %s', 'modularity'), $nameSingular),
            'view_item'          => sprintf(__('View %s', 'modularity'), $nameSingular),
            'all_items'          => sprintf(__('Edit %s', 'modularity'), $namePlural),
            'search_items'       => sprintf(__('Search %s', 'modularity'), $namePlural),
            'parent_item_colon'  => sprintf(__('Parent %s', 'modularity'), $namePlural),
            'not_found'          => sprintf(__('No %s', 'modularity'), $namePlural),
            'not_found_in_trash' => sprintf(__('No %s in trash', 'modularity'), $namePlural)
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
            'exclude_from_search'  => false,
            'menu_icon'            => $icon,
            'supports'             => array_merge($supports, array('title', 'revisions')),
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
         * Add to available (and depracated if it is) modules
         */
        if ($this->isDeprecated) {
            self::$deprecated[] = $postTypeSlug;
        }

        self::$available[$postTypeSlug] = $args;
        $this->moduleSlug = $postTypeSlug;

        // Enqueue
        add_action('admin_enqueue_scripts', array($this, 'enqueue'));
        add_action('wp_enqueue_scripts', array($this, 'style'));
        add_action('wp_enqueue_scripts', array($this, 'script'));

        // Shortcode metabox
        add_action('add_meta_boxes', array($this, 'shortcodeMetabox'));

        // Add usage metabox
        add_action('add_meta_boxes', array($this, 'whereUsedMetaBox'));

        // Description meta box
        add_action('add_meta_boxes', array($this, 'descriptionMetabox'), 5);
        add_action('save_post', array($this, 'descriptionMetaboxSave'));

        // Setup list table fields
        $this->setupListTableField();

        /**
         * Include plugin
         */
        if (!is_null($plugin) && file_exists(__DIR__ . '/../../plugins/'. $plugin)) {
            require_once __DIR__.'/../../plugins/' . $plugin;
        }

        /**
         * Store settings of each module in static var
         * @var array
         */
        self::$moduleSettings[$postTypeSlug] = array(
            'slug' => $slug,
            'singular_name' => $nameSingular,
            'plural_name' => $namePlural,
            'description' => $description,
            'supports' => $supports,
            'icon' => $icon,
            'plugin' => $plugin,
            'cache_ttl' => $cache_ttl,
            'hide_title' => $hideTitle
        );

        return $postTypeSlug;
    }

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
     * Setup list table fields
     * @return void
     */
    public function setupListTableField()
    {
        add_filter('manage_edit-' . $this->moduleSlug . '_columns', array($this, 'listTableColumns'));
        add_action('manage_' . $this->moduleSlug . '_posts_custom_column', array($this, 'listTableColumnContent'), 10, 2);
        add_filter('manage_edit-' . $this->moduleSlug . '_sortable_columns', array($this, 'listTableColumnSorting'));
    }



    /**
     * Define list table columns
     * @param  array $columns  Default columns
     * @return array           Modified columns
     */
    public function listTableColumns($columns)
    {
        $columns = array(
            'cb'               => '<input type="checkbox">',
            'title'            => __('Title'),
            'description'      => __('Description'),
            'usage'            => __('Usage', 'modularity'),
            'date'             => __('Date')
        );

        return $columns;
    }

    /**
     * List table column content
     * @param  string $column  Column
     * @param  integer $postId Post id
     * @return void
     */
    public function listTableColumnContent($column, $postId)
    {
        switch ($column) {
            case 'description':
                $description = get_post_meta($postId, 'module-description', true);
                echo !empty($description) ? $description : '';
                break;

            case 'usage':
                $usage = self::getModuleUsage($postId, 3);

                if (count($usage->data) == 0) {
                    echo __('Not used', 'modularity');
                    break;
                }

                $i = 0;

                foreach ($usage->data as $item) {
                    $i++;

                    if ($i > 1) {
                        echo ', ';
                    }

                    echo '<a href="' . get_permalink($item->post_id) . '">' . $item->post_title . '</a>';
                }

                if ($usage->more > 0) {
                    echo ' (' . $usage->more . ' ' . __('more', 'modularity') . ')';
                }

                break;
        }
    }

    /**
     * Table list column sorting
     * @param  array $columns Default sortable columns
     * @return array          Modified sortable columns
     */
    public function listTableColumnSorting($columns)
    {
        $columns['description'] = 'description';
        $columns['usage'] = 'usage';
        return $columns;
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
        }, $this->moduleSlug, 'side', 'default');
    }

    /**
     * Metabox that shows where the module is used
     * @return void
     */
    public function whereUsedMetaBox()
    {
        if (!$this->moduleSlug) {
            return;
        }

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

        }, $this->moduleSlug, 'side', 'default');
    }

    /**
     * Description metabox content
     * @return void
     */
    public function descriptionMetabox()
    {
        if (!$this->moduleSlug) {
            return;
        }

        add_meta_box(
            'modularity-description',
            'Module description',
            function () {
                $description = get_post_meta(get_the_id(), 'module-description', true);
                include MODULARITY_TEMPLATE_PATH . 'editor/modularity-module-description.php';
            },
            $this->moduleSlug,
            'normal',
            'high'
        );
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
