<?php

namespace Modularity\Module\Posts;

use Throwable;
use BladeComponentLibrary\Init as CompLibInitator;

/**
 * Class Posts
 * @package Modularity\Module\Posts
 */
class Posts extends \Modularity\Module
{
    public $slug = 'posts';
    public $supports = array();
    public $icon = 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNC4zMzQgMjQuMzM0Ij48ZyBmaWxsPSIjMDMwMTA0Ij48cGF0aCBkPSJNMTAuMjk1IDBILjkzNUEuOTM2LjkzNiAwIDAgMCAwIC45MzZ2OS4zNmMwIC41MTYuNDIuOTM1LjkzNi45MzVoOS4zNmMuNTE2IDAgLjkzNS0uNDE4LjkzNS0uOTM1Vi45MzVBLjkzNi45MzYgMCAwIDAgMTAuMjk2IDB6TTIzLjM5OCAwaC05LjM2YS45MzYuOTM2IDAgMCAwLS45MzUuOTM2djkuMzZjMCAuNTE2LjQyLjkzNS45MzYuOTM1aDkuMzU4Yy41MTcgMCAuOTM2LS40MTguOTM2LS45MzVWLjkzNUEuOTM2LjkzNiAwIDAgMCAyMy4zOTggMHptLS45MzYgOS4zNmgtNy40ODdWMS44N2g3LjQ4N1Y5LjM2ek0xMC4yOTUgMTMuMTAzSC45MzVBLjkzNi45MzYgMCAwIDAgMCAxNC4wNHY5LjM1OGMwIC41MTcuNDIuOTM2LjkzNi45MzZoOS4zNmMuNTE2IDAgLjkzNS0uNDIuOTM1LS45MzZ2LTkuMzZhLjkzNS45MzUgMCAwIDAtLjkzNS0uOTM1em0tNS44NzUgOS4zNmMuMTYtLjI3Ny4yNi0uNTk0LjI2LS45MzdhMS44NzIgMS44NzIgMCAwIDAtMS44NzItMS44NzJjLS4zNDMgMC0uNjYuMS0uOTM2LjI2di0yLjM5Yy4yNzYuMTYuNTkzLjI2LjkzNi4yNkExLjg3MiAxLjg3MiAwIDAgMCA0LjY4IDE1LjkxYzAtLjM0Mi0uMS0uNjYtLjI2LS45MzVoMi4zOWExLjg1IDEuODUgMCAwIDAtLjI2LjkzNmMwIDEuMDM1Ljg0IDEuODczIDEuODczIDEuODczLjM0MyAwIC42Ni0uMS45MzYtLjI2djIuMzlhMS44NSAxLjg1IDAgMCAwLS45MzctLjI2IDEuODcyIDEuODcyIDAgMCAwLTEuODcyIDEuODczYzAgLjM0My4xLjY2LjI2LjkzNkg0LjQyek0yMy4zOTggMTMuMTAzaC05LjM2YS45MzYuOTM2IDAgMCAwLS45MzUuOTM2djkuMzU4YzAgLjUxNi40Mi45MzUuOTM2LjkzNWg5LjM1OGMuNTE2IDAgLjkzNS0uNDIuOTM1LS45MzZ2LTkuMzZhLjkzNC45MzQgMCAwIDAtLjkzNS0uOTM0em0tOC40MjMgNi4wMDNsNC4xMy00LjEzaDIuMDM0bC02LjE2NSA2LjE2M3YtMi4wMzR6bTAtNC4xM2gxLjQ4NGwtMS40ODUgMS40ODN2LTEuNDg1em03LjQ4NyAxLjMyMnYyLjAzMmwtNC4xMyA0LjEzMmgtMi4wMzRsNi4xNjQtNi4xNjR6bTAgNi4xNjRoLTEuNDg1bDEuNDg1LTEuNDg1djEuNDg1eiIvPjxjaXJjbGUgY3g9IjUuNjE2IiBjeT0iMTguNzE4IiByPSIxLjg3MiIvPjwvZz48L3N2Zz4=';
    private $enableFilters = false;

    public function init()
    {
        $this->nameSingular = __('Posts', 'modularity');
        $this->namePlural = __('Posts', 'modularity');
        $this->description = __('Outputs selected posts in specified layout', 'modularity');

        add_action('Modularity/Module/' . $this->moduleSlug . '/enqueue', array($this, 'enqueueScripts'));
        add_action('add_meta_boxes', array($this, 'addColumnFields'));
        add_action('save_post', array($this, 'saveColumnFields'));
        
        add_action('wp_ajax_get_taxonomy_types_v2', array($this, 'getTaxonomyTypes'));
        add_action('wp_ajax_get_taxonomy_values_v2', array($this, 'getTaxonomyValues'));
        add_action('wp_ajax_get_sortable_meta_keys_v2', array($this, 'getSortableMetaKeys'));
        
        add_action('wp_ajax_mod_posts_load_more', array($this, 'loadMorePostsUsingAjax'));
        add_action('wp_ajax_nopriv_mod_posts_load_more', array($this, 'loadMorePostsUsingAjax'));
        
        add_action('admin_init', array($this, 'addTaxonomyDisplayOptions'));
        

    }

    public static function loadMoreButtonAttributes($module, $target, $bladeTemplate, $postsPerPage)
    {
        if (defined('DOING_AJAX') && DOING_AJAX) {
            return '';
        }

        unset($module->data['posts']);

        return json_encode(array(
            'target' => $target,
            'postsPerPage' => $postsPerPage,
            'offset' => (get_field('posts_count', $module->data['ID']) > 0) ? get_field('posts_count', $module->data['ID']) : 0,
            'module' => $module,
            'bladeTemplate' => $bladeTemplate,
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('mod-posts-load-more')
        ));
    }

    /**
     * Load more data with Ajax
     * @return json data
     */
    public function loadMorePostsUsingAjax()
    {
        if (!defined('DOING_AJAX') || !DOING_AJAX) {
            return false;
            die;
        }

        //Nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'mod-posts-load-more')) {
            die('Busted!');
        }

        $statusCodes = array(
            'badRequest' => 400,
            'noContent' => 204,
            'success' => 200
        );

        //Make sure required post data exists
        $requiredPostDataKeys = array('postsPerPage', 'offset', 'module', 'bladeTemplate');
        foreach ($requiredPostDataKeys as $key) {
            if (!isset($_POST[$key])) {
                $error = 'Missing required $_POST[' . $key . '].';
                error_log($error);
                wp_send_json_error(['error' => $error], $statusCodes['badRequest']);
                die;
            }
        }

        //Append class propeties
        foreach ($_POST['module'] as $key => $value) {
            $this->$key = $value;
        }

        //Get posts
        $args = self::getPostArgs($this->ID);
        $args['posts_per_page'] = $_POST['postsPerPage'];
        $args['offset'] = $_POST['offset'];
        $this->data['posts'] = get_posts($args);

        $this->getTemplateData(self::replaceDeprecatedTemplate($this->data['posts_display_as'])); //Include template controller data

        //No posts
        if (empty($this->data['posts'])) {
            wp_send_json(['Message' => 'Could not find more posts'], $statusCodes['noContent']);
            die;
        }

        $moduleView = MODULARITY_PATH . 'source/php/Module/Posts/views';
        $init = new CompLibInitator([$moduleView]);
        $blade = $init->getEngine();
        $posts = [];

        foreach ($this->data['posts'] as $post) {

            try {
                $posts[] = $blade->make($_POST['bladeTemplate'], array_merge(['post' => $post], $this->data))->render();
            } catch (Throwable $e) {
                echo '<pre style="border: 3px solid #f00; padding: 10px;">';
                echo '<strong>' . $e->getMessage() . '</strong>';
                echo '<hr style="background: #000; outline: none; border:none; display: block; height: 1px;"/>';
                echo $e->getTraceAsString();
                echo '</pre>';
            }
        }

        wp_send_json($posts);
    }

    /**
     * @return false|string
     */
    public function template()
    {
        $this->getTemplateData(self::replaceDeprecatedTemplate($this->data['posts_display_as'] ));
        return apply_filters('Modularity/Module/Posts/template', self::replaceDeprecatedTemplate($this->data['posts_display_as']) . '.blade.php', $this,
            $this->data);
    }

    /**
     * @param $template
     */
    public function getTemplateData($template)
    {
        $template = explode('-', $template);
        $template = array_map('ucwords', $template);
        $template = implode('', $template);

        $class = '\Modularity\Module\Posts\TemplateController\\' . $template . 'Template';
        $this->data['meta']['posts_display_as'] = self::replaceDeprecatedTemplate($this->data['posts_display_as'] );

        if (class_exists($class)) {
            $controller = new $class($this, $this->args, $this->data);
            $this->data = array_merge($this->data, $controller->data);
        }
    }

    /**
     * @return array
     */
    public function data(): array
    {
        $fields = json_decode(json_encode(get_fields($this->ID)));
        $data['posts_display_as'] = $fields->posts_display_as;

        if (get_field('front_end_tax_filtering', $this->ID) && get_field('posts_data_post_type',
                $this->ID) === 'post' || get_field('front_end_tax_filtering',
                $this->ID) && get_field('posts_data_post_type', $this->ID) === 'page') {

            $this->enableFilters = true;

            $data['frontEndFilters']['front_end_tax_filtering_text_search'] = get_field('front_end_tax_filtering_text_search',
                $this->ID) ? true : false;
            $data['frontEndFilters']['front_end_tax_filtering_dates'] = get_field('front_end_tax_filtering_dates',
                $this->ID) ? true : false;
            $data['frontEndFilters']['front_end_tax_filtering_taxonomy'] = get_field('front_end_tax_filtering_taxonomy',
                $this->ID) ? true : false;

            $data['frontEndFilters']['front_end_button_text'] = get_field('front_end_button_text', $this->ID);
            $data['frontEndFilters']['front_end_hide_date'] = get_field('front_end_hide_date', $this->ID);
            $data['frontEndFilters']['front_end_display'] = get_field('front_end_display', $this->ID);

            $postFilters = new \Modularity\Module\Posts\PostsFilters($this);

            if ($enabledTaxonomyFilters = $postFilters->getEnabledTaxonomies($group = true)) {
                $data['enabledTaxonomyFilters'] = $enabledTaxonomyFilters;
            } else {
                $data['enabledTaxonomyFilters'] = array();
            }

            $data['queryString'] = (isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING'])) ? true : false;
            $data['pageUrl'] = $postFilters->getPostUrl();
            $data['searchQuery'] = get_query_var('search');
        }
        $data['modId'] = $this->ID;
        $data['posts'] = \Modularity\Module\Posts\Posts::getPosts($this);

        // Sorting
        $data['sortBy'] = false;
        $data['orderBy'] = false;
        if (isset($fields->posts_sort_by) && substr($fields->posts_sort_by, 0, 9) === '_metakey_') {
            $data['sortBy'] = 'meta_key';
            $data['sortByKey'] = str_replace('_metakey_', '', $fields->posts_sort_by);
        }

        $data['order'] = isset($fields->posts_sort_order) ? $fields->posts_sort_order : 'asc';

        // Setup filters
        $filters = array(
            'orderby' => sanitize_text_field($data['sortBy']),
            'order' => sanitize_text_field($data['order'])
        );

        if ($data['sortBy'] == 'meta_key') {
            $filters['meta_key'] = $data['sortByKey'];
        }

        $data['filters'] = array();

        if (isset($fields->posts_taxonomy_filter) && $fields->posts_taxonomy_filter === true) {
            $taxType = $fields->posts_taxonomy_type;
            $taxValues = (array)$fields->posts_taxonomy_value;
            $taxValues = implode('|', $taxValues);

            $data['filters']['filter[' . $taxType . ']'] = $taxValues;

        }

        $data['taxonomyDisplayFlat'] = $this->getTaxonomyDisplayFlat();
        $data['posts_data_post_type'] = isset($fields->posts_data_post_type) ? $fields->posts_data_post_type : false;
        $data['posts_data_source'] = $fields->posts_data_source;
        $data['posts_fields'] = isset($fields->posts_fields) ? $fields->posts_fields : false;

        $data['archive_link'] = isset($fields->archive_link) ? $fields->archive_link : false;
        $data['archive_link_url'] = get_post_type_archive_link($data['posts_data_post_type']);

        return $data;
    }


    /**
     * @return array
     */
    public function getTaxonomyDisplayFlat()
    {
        if (empty(get_field('taxonomy_display', $this->ID))) {
            return array();
        }

        return get_field('taxonomy_display', $this->ID);
    }

    /**
     * Add options fields to setup taxonomy display
     * i.e where to display taxonomy labels in the layout
     */
    public function addTaxonomyDisplayOptions()
    {
        if (!function_exists('acf_add_local_field_group')) {
            return;
        }

        $taxonomies = get_taxonomies();
        $taxonomies = array_diff($taxonomies, array(
            'nav_menu',
            'link_category'
        ));

        $taxonomyDisplayChoices = array();

        $taxonomiesNew = array();
        foreach ($taxonomies as $taxonomy) {
            $tax = get_taxonomy($taxonomy);
            $taxonomiesNew[] = $tax;
            $taxonomyDisplayChoices[$tax->name] = $tax->label;
        }

        $taxonomies = $taxonomiesNew;

        $fieldgroup = array(
            'key' => 'group_' . md5('mod_posts_taxonomy_display'),
            'title' => __('Taxonomy display', 'municipio'),
            'fields' => array(),
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'mod-posts',
                    ),
                ),
            ),
            'menu_order' => 20,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
            'active' => 1,
            'description' => '',
        );

        $fieldgroup['fields'][] = array(
            'key' => 'field_56f00fe21f918_' . md5('display_taxonomies'),
            'label' => 'Taxonomies to display',
            'name' => 'taxonomy_display',
            'type' => 'checkbox',
            'layout' => 'horizontal',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'choices' => $taxonomyDisplayChoices,
            'default_value' => array(),
            'allow_null' => 0,
            'multiple' => 0,
            'ui' => 0,
            'ajax' => 0,
            'placeholder' => '',
            'readonly' => 0,
        );


        acf_add_local_field_group($fieldgroup);
    }

    /**
     * AJAX CALLBACK
     * Get metakeys which we can use to sort the posts
     * @return void
     */
    public function getSortableMetaKeys()
    {
        if (!isset($_POST['posttype']) || empty($_POST['posttype'])) {
            echo '0';
            die();
        }

        $meta = \Modularity\Helper\Post::getPosttypeMetaKeys($_POST['posttype']);

        $response = array(
            'meta_keys' => $meta,
            'sort_curr' => get_field('posts_sort_by', $_POST['post']),
            'filter_curr' => get_field('posts_meta_key', $_POST['post']),
        );

        echo json_encode($response);
        die();
    }

    /**
     * AJAX CALLBACK
     * Get availabel taxonomies for a post type
     * @return void
     */
    public function getTaxonomyTypes()
    {
        if (!isset($_POST['posttype']) || empty($_POST['posttype'])) {
            echo '0';
            die();
        }

        $post = $_POST['post'];

        $result = array(
            'types' => get_object_taxonomies($_POST['posttype'], 'object'),
            'curr' => get_field('posts_taxonomy_type', $post)
        );

        echo json_encode($result);
        die();
    }

    /**
     * AJAX CALLBACK
     * Gets a taxonomies available values
     * @return void
     */
    public function getTaxonomyValues()
    {
        if (!isset($_POST['tax']) || empty($_POST['tax'])) {
            echo '0';
            die();
        }

        $taxonomy = $_POST['tax'];
        $post = $_POST['post'];

        $result = array(
            'tax' => get_terms($taxonomy, array(
                'hide_empty' => false,
            )),
            'curr' => get_field('posts_taxonomy_value', $post)
        );

        echo json_encode($result);
        die();
    }

    /**
     * Saves column names if exandable list template is used
     * @param int $postId The id of the post
     * @return void
     */
    public function saveColumnFields($postId)
    {
        //Meta key
        $metaKey = "modularity-mod-posts-expandable-list";

        //Bail early if autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return false;
        }

        //Bail early if cron
        if (defined('DOING_CRON') && DOING_CRON) {
            return false;
        }

        //Bail early if not a post request
        if (!isset($_POST) || (is_array($_POST) && empty($_POST)) || !is_array($_POST)) {
            return false;
        }

        //Update if nonce verification succeed
        if (isset($_POST['modularity_post_columns']) && wp_verify_nonce($_POST['modularity_post_columns'],
                'save_columns')) {
            //Delete if not posted data
            if (!isset($_POST[$metaKey])) {
                delete_post_meta($postId, $metaKey);
                return;
            }

            //Save meta data
            update_post_meta($postId, $metaKey, $_POST[$metaKey]);
        }
    }

    /**
     * Check wheather to add expandable list column fields to edit post screeen
     */
    public function addColumnFields()
    {
        global $post;
        $screen = get_current_screen();

        if (empty($post->post_type) || $screen->base != 'post') {
            return;
        }

        $modules = array();

        // If manually picked
        if ($newModules = $this->checkIfManuallyPicked($post->ID)) {
            $modules = array_merge($modules, $newModules);
        }

        // If post type
        if ($newModules = $this->checkIfPostType($post->ID)) {
            $modules = array_merge($modules, $newModules);
        }

        // If child
        if ($newModules = $this->checkIfChild($post->ID)) {
            $modules = array_merge($modules, $newModules);
        }

        if (empty($modules)) {
            return false;
        }

        $modules = array_filter($modules, function ($item) {
            return !wp_is_post_revision($item);
        });

        $fields = $this->getColumns($modules);

        if (!empty($fields)) {
            add_meta_box(
                'modularity-mod-posts-expandable-list',
                __('Modularity expandable list column values', 'modularity'),
                array($this, 'columnFieldsMetaBoxContent'),
                null,
                'normal',
                'default',
                array($fields)
            );
        }
    }

    /**
     * Expandable list column value fields metabox content
     * @param object $post Post object
     * @param array $args Arguments
     * @return void
     */
    public function columnFieldsMetaBoxContent($post, $args)
    {
        $fields = $args['args'][0];
        $fieldValues = get_post_meta($post->ID, 'modularity-mod-posts-expandable-list', true);

        foreach ($fields as $field) {
            $fieldSlug = sanitize_title($field);
            $value = isset($fieldValues[$fieldSlug]) && !empty($fieldValues[$fieldSlug]) ? $fieldValues[$fieldSlug] : '';
            echo '
                <p>
                    <label for="mod-' . $fieldSlug . '">' . $field . ':</label>
                    <input value="' . $value . '" class="widefat" type="text" name="modularity-mod-posts-expandable-list[' . sanitize_title($field) . ']" id="mod-' . sanitize_title($field) . '">
                </p>
            ';
        }

        echo wp_nonce_field('save_columns', 'modularity_post_columns');
    }

    /**
     * Get field columns
     * @param array $posts Post ids
     * @return array        Column names
     */
    public function getColumns($posts)
    {
        $columns = array();

        if (is_array($posts)) {
            foreach ($posts as $post) {
                $values = get_field('posts_list_column_titles', $post);

                if (is_array($values)) {
                    foreach ($values as $value) {
                        $columns[] = $value['column_header'];
                    }
                }
            }
        }

        return $columns;
    }

    public function checkIfChild($id)
    {
        global $post;
        global $wpdb;

        $result = $wpdb->get_results("
            SELECT *
            FROM $wpdb->postmeta
            WHERE meta_key = 'posts_data_child_of'
                  AND meta_value = '{$post->post_parent}'
        ", OBJECT);

        if (count($result) === 0) {
            return false;
        }

        $posts = array();
        foreach ($result as $item) {
            $posts[] = $item->post_id;
        }

        return $posts;
    }

    /**
     * Check if current post is included in the data source post type
     * @param integer $id Postid
     * @return array       Modules included in
     */
    public function checkIfPostType($id)
    {
        global $post;
        global $wpdb;

        $result = $wpdb->get_results("
            SELECT *
            FROM $wpdb->postmeta
            WHERE meta_key = 'posts_data_post_type'
                  AND meta_value = '{$post->post_type}'
        ", OBJECT);

        if (count($result) === 0) {
            return false;
        }

        $posts = array();
        foreach ($result as $item) {
            $posts[] = $item->post_id;
        }

        return $posts;
    }

    /**
     * Check if current post is included in a manually picked data source in exapndable list
     * @param integer $id Post id
     * @return array       Modules included in
     */
    public function checkIfManuallyPicked($id)
    {
        global $wpdb;

        $result = $wpdb->get_results("
            SELECT *
            FROM $wpdb->postmeta
            WHERE meta_key = 'posts_data_posts'
                  AND meta_value LIKE '%\"{$id}\"%'
        ", OBJECT);

        if (count($result) === 0) {
            return false;
        }

        $posts = array();
        foreach ($result as $item) {
            $posts[] = $item->post_id;
        }

        return $posts;
    }

    /**
     * Enqueue scripts (frontend)
     * @return void
     */
    public function script()
    {
        wp_register_script('mod-posts-load-more-button', MODULARITY_URL . '/dist/js/mod-posts-load-more-button.min.js', false, filemtime(MODULARITY_PATH . 'dist/js/mod-posts-load-more-button.min.js'), false);
        wp_enqueue_script('mod-posts-load-more-button');
    }

    /**
     * Enqueue scripts
     * @return void
     */
    public function adminEnqueue()
    {
        wp_register_script('mod-posts-taxonomy', MODULARITY_URL . '/dist/js/mod-posts-taxonomy.min.js', false, filemtime(MODULARITY_PATH . 'dist/js/mod-posts-taxonomy.min.js'), false);
        wp_enqueue_script('mod-posts-taxonomy');

        add_action('admin_head', function () {
            global $post;
            global $archive;

            $id = isset($post->ID) ? $post->ID : "'" . $archive . "'";
            if (empty($id)) {
                return;
            }

            echo '<script>var modularity_current_post_id = ' . $id . ';</script>';
        });
    }

    /**
     * "Fake" WP_POST objects for manually inputted posts
     * @param array $data The data to "fake"
     * @return array        Faked data
     */
    public static function getManualInputPosts($data)
    {
        $posts = array();

        foreach ($data as $key => $item) {
            $posts[] = array_merge((array)$item, array(
                'ID' => $key,
                'post_name' => $key,
                'post_excerpt' => $item->post_content
            ));
        }

        $posts = json_decode(json_encode($posts));

        return $posts;
    }

    /**
     * Get included posts
     * @param object $module Module object
     * @return array          Array with post objects
     */
    public static function getPosts($module)
    {
        $fields = json_decode(json_encode(get_fields($module->ID)));
        if ($fields->posts_data_source == 'input') {
            return self::getManualInputPosts($fields->data);
        }

        return get_posts(self::getPostArgs($module->ID));
    }

    public static function getPostArgs($id)
    {
        $fields = json_decode(json_encode(get_fields($id)));

        $metaQuery = false;
        $orderby = isset($fields->posts_sort_by) && $fields->posts_sort_by ? $fields->posts_sort_by : 'date';
        $order = isset($fields->posts_sort_order) && $fields->posts_sort_order ? $fields->posts_sort_order : 'desc';

        // Get post args
        $getPostsArgs = array(
            'posts_per_page' => $fields->posts_count,
            'post_type' => 'any',
            'suppress_filters' => false
        );

        // Sort by meta key
        if (strpos($orderby, '_metakey_') === 0) {
            $orderby_key = substr($orderby, strlen('_metakey_'));
            $orderby = 'order_clause';
            $metaQuery = array(
                array(
                    'relation' => 'OR',
                    'order_clause' => array(
                        'key' => $orderby_key,
                        'compare' => 'EXISTS'
                    ),
                    array(
                        'key' => $orderby_key,
                        'compare' => 'NOT EXISTS'
                    )
                )
            );
        }

        if ($orderby != 'false') {
            $getPostsArgs['order'] = $order;
            $getPostsArgs['orderby'] = $orderby;
        }

        // Post statuses
        $getPostsArgs['post_status'] = array('publish', 'inherit');
        if (is_user_logged_in()) {
            $getPostsArgs['post_status'][] = 'private';
        }

        // Taxonomy filter
        if (isset($fields->posts_taxonomy_filter) && $fields->posts_taxonomy_filter === true) {
            $taxType = $fields->posts_taxonomy_type;
            $taxValues = (array)$fields->posts_taxonomy_value;

            foreach ($taxValues as $term) {
                $getPostsArgs['tax_query'][] = array(
                    'taxonomy' => $taxType,
                    'field' => 'slug',
                    'terms' => $term
                );
            }
        }

        // Meta filter
        if (isset($fields->posts_meta_filter) && $fields->posts_meta_filter === true) {
            $metaQuery[] = array(
                'key' => $fields->posts_meta_key ?? '',
                'value' => array($fields->posts_meta_value ?? ''),
                'compare' => 'IN',
            );
        }

        // Data source
        switch ($fields->posts_data_source) {
            case 'posttype':
                $getPostsArgs['post_type'] = $fields->posts_data_post_type;
                break;

            case 'children':
                $getPostsArgs['post_type'] = get_post_type();
                $getPostsArgs['post_parent'] = $fields->posts_data_child_of;
                break;

            case 'manual':
                $getPostsArgs['post__in'] = $fields->posts_data_posts;
                if ($orderby == 'false') {
                    $getPostsArgs['orderby'] = 'post__in';
                }
                break;
        }

        // Add metaquery to args
        if ($metaQuery) {
            $getPostsArgs['meta_query'] = $metaQuery;
        }

        return $getPostsArgs;
    }

    /**
     * For version 3.0 - Replace old post templates with existing replacement.
     * @param $templateSlug
     * @return mixed
     */
    public static function replaceDeprecatedTemplate($templateSlug){
  
        // Add deprecated template/replacement slug to array.
        $deprecatedTemplates = [
            'items' => 'index',
            'grid' => 'index',
            'news' => 'index'
        ];

        if (array_key_exists($templateSlug, $deprecatedTemplates)){
            return  $deprecatedTemplates[$templateSlug];
        }

        return $templateSlug;
    }


    /**
     * Available "magic" methods for modules:
     * init()            What to do on initialization
     * data()            Use to send data to view (return array)
     * style()           Enqueue style only when module is used on page
     * script            Enqueue script only when module is used on page
     * adminEnqueue()    Enqueue scripts for the module edit/add page in admin
     * template()        Return the view template (blade) the module should use when displayed
     */
}
