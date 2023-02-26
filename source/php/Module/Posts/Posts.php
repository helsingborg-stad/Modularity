<?php

namespace Modularity\Module\Posts;

use \Modularity\Module\Posts\PostsFilters;

/**
 * Class Posts
 * @package Modularity\Module\Posts
 */
class Posts extends \Modularity\Module
{
    public $slug = 'posts';
    public $supports = [];
    public $blockSupports = array(
        'align' => ['full']
    );
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

        add_filter('acf/load_field/name=posts_date_source', array($this, 'loadDateField'));
        add_filter('acf/load_field/key=field_62a309f9c59bb', array($this, 'addIconsList'));

        //Add full width data to view
        add_filter('Modularity/Block/Data', array($this, 'blockData'), 50, 3);

        add_filter('Modularity/Module/Posts/template', array( $this, 'sliderTemplate' ), 10, 3);

        new PostsAjax($this);
    }

   /**
    * If the module is set to show as a slider, then return the slider template
    *
    * @param string template The template that is currently being used.
    * @param object module The module object
    * @param array moduleData The data for the module.
    *
    * @return The template name.
    */
    public function sliderTemplate($template, $module, $moduleData)
    {
        $showAsSlider = get_field('show_as_slider', $moduleData['ID']);
        $postsDisplayAs = get_field('posts_display_as', $moduleData['ID']);
        
        $layoutsWithSliderAvailable = array('items', 'news', 'index', 'grid', 'features-grid');
        
        if (1 === (int) $showAsSlider && in_array($postsDisplayAs, $layoutsWithSliderAvailable, true)) {
            $this->getTemplateData(self::replaceDeprecatedTemplate('slider'), $moduleData);
            return 'slider.blade.php';
        }

        return $template;
    }

    /**
     * Get list of date sources
     *
     * @param string $postType
     * @return array
     */
    public function getDateSource($postType): array
    {
        if (empty($postType)) {
            return false;
        }

        $metaKeys = [
            'post_date'  => 'Date published',
            'post_modified' => 'Date modified',
        ];

        $metaKeysRaw = \Municipio\Helper\Post::getPosttypeMetaKeys($postType);

        if (isset($metaKeysRaw) && is_array($metaKeysRaw) && !empty($metaKeysRaw)) {
            foreach ($metaKeysRaw as $metaKey) {
                $metaKeys[$metaKey] = $metaKey;
            }
        }

        return $metaKeys;
    }

    /**
     * Add full width setting to frontend.
     *
     * @param [array] $viewData
     * @param [array] $block
     * @param [object] $module
     * @return array
     */
    public function blockData($viewData, $block, $module)
    {
        $viewData['noGutter'] = false;
        if (in_array($block['name'], ['posts', 'acf/posts']) && $block['align'] == 'full') {
            if (!is_admin()) {
                $viewData['stretch'] = true;
            }
            $viewData['noGutter'] = true;
        }

        return $viewData;
    }

    /**
     * Add list to dropdown
     *
     * @param array $field  Field definition
     * @return array $field Field definition with choices
     */
    public function addIconsList($field): array
    {
        $choices = \Modularity\Helper\Icons::getIcons();

        $field['choices'] = [];
        if (is_array($choices) && !empty($choices)) {
            foreach ($choices as $choice) {
                $field['choices'][$choice] = '<i class="material-icons" style="float: left;">'
                    . $choice
                    . '</i> <span style="height: 24px; display: inline-block; line-height: 24px; margin-left: 8px;">'
                    . $choice
                    . '</span>';
            }
        }

        return $field;
    }

    public function loadDateField($field = [])
    {
        $postType = get_field('posts_data_post_type', $this->ID);

        if (empty($postType)) {
            return $field;
        }

        $field['choices'] = $this->getDateSource($postType);

        return $field;
    }

    /**
     * @return false|string
     */
    public function template()
    {
        $this->getTemplateData(self::replaceDeprecatedTemplate($this->data['posts_display_as']));
        if (!self::replaceDeprecatedTemplate($this->data['posts_display_as'])) {
            return 'list';
        }

        return apply_filters(
            'Modularity/Module/Posts/template',
            self::replaceDeprecatedTemplate($this->data['posts_display_as']) . '.blade.php',
            $this,
            $this->data
        );
    }

    /**
     * @param $template
     */
    public function getTemplateData(string $template, array $data = array())
    {
        if (! empty($data)) {
            $this->data = $data;
        }
        
        $template = explode('-', $template);
        $template = array_map('ucwords', $template);
        $template = implode('', $template);

        $class = '\Modularity\Module\Posts\TemplateController\\' . $template . 'Template';

        $this->data['meta']['posts_display_as'] = self::replaceDeprecatedTemplate($this->data['posts_display_as']);
        
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
        $data = [];
        $fields = json_decode(json_encode(get_fields($this->ID)));
        $data['posts_display_as'] = $fields->posts_display_as;

        $this->enableFilters = $this->enableFilters();
        if ($this->enableFilters) {
            $data['frontEndFilters'] = $this->getFrontendFilters();

            $postFilters = new PostsFilters($this);

            $data['enabledTaxonomyFilters'] = [];
            $enabledTaxonomyFilters = $postFilters->getEnabledTaxonomies($group = true);
            if ($enabledTaxonomyFilters) {
                $data['enabledTaxonomyFilters'] = $enabledTaxonomyFilters;
            }

            $data['queryString'] = (isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING'])) ? true : false;
            $data['pageUrl'] = $postFilters->getPostUrl();
            $data['searchQuery'] = get_query_var('search');
        }
        $data['modId'] = $this->ID;

        // Posts
        $data['preamble'] = $fields->preamble ?? false;
        $data['posts_fields'] = $fields->posts_fields ?? false;
        $data['posts_date_source'] = $fields->posts_date_source ?? false;
        $data['posts_data_post_type'] = $fields->posts_data_post_type ?? false;
        $data['posts_data_source'] = $fields->posts_data_source ?? false;
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
        $filters = [
            'orderby' => sanitize_text_field($data['sortBy']),
            'order' => sanitize_text_field($data['order'])
        ];

        if ($data['sortBy'] == 'meta_key') {
            $filters['meta_key'] = $data['sortByKey'];
        }

        $data['filters'] = [];

        if (isset($fields->posts_taxonomy_filter) && $fields->posts_taxonomy_filter === true) {
            $taxType = $fields->posts_taxonomy_type;
            $taxValues = (array)$fields->posts_taxonomy_value;
            $taxValues = implode('|', $taxValues);

            $data['filters']['filter[' . $taxType . ']'] = $taxValues;
        }

        $data['taxonomyDisplayFlat']    = $this->getTaxonomyDisplayFlat();
        $data['archive_link_url']       = $this->getArchiveUrl(
            $data['posts_data_post_type'], 
            $fields
        );

        $data['ariaLabels'] =  (object) [
           'prev' => __('Previous slide', 'modularity'),
           'next' => __('Next slide', 'modularity'),
        ];

        if($this->ID) {
            $data['sliderId'] = $this->ID;
        } else {
            $data['sliderId'] = uniqid();
        }
        
        return $data;
    }

    private function getArchiveUrl($postType, $fields) {

        if(empty($postType)) {
            return false;
        }

        if(!isset($fields->archive_link) || !$fields->archive_link) {
            return false;
        }

        if($postType == 'post') {
            if($pageForPosts = get_option('page_for_posts')) {
                return get_permalink($pageForPosts);
            }

            if(get_option('show_on_front') == 'posts') {
                return get_home_url();
            }

            return false;
        }

        if($postObject = get_post_type_object($postType)) {
            if(isset($postObject->has_archive) && $postObject->has_archive) {
                return get_post_type_archive_link($postType); 
            }
        }
        
        return false;
    }


    /**
     * @return array
     */
    public function getTaxonomyDisplayFlat()
    {
        if (empty(get_field('taxonomy_display', $this->ID))) {
            return [];
        }

        return get_field('taxonomy_display', $this->ID);
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

        $response = [
            'meta_keys' => $meta,
            'sort_curr' => get_field('posts_sort_by', $_POST['post']),
            'filter_curr' => get_field('posts_meta_key', $_POST['post']),
        ];

        echo json_encode($response);
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
        if (
            isset($_POST['modularity_post_columns'])
            && wp_verify_nonce(
                $_POST['modularity_post_columns'],
                'save_columns'
            )
        ) {
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

        $modules = [];

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
                [$this, 'columnFieldsMetaBoxContent'],
                null,
                'normal',
                'default',
                [$fields]
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
            $value = isset($fieldValues[$fieldSlug]) && !empty($fieldValues[$fieldSlug])
                ? $fieldValues[$fieldSlug] : '';
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
        $columns = [];

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

        $posts = [];
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

        $posts = [];
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

        $posts = [];
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
        wp_register_script('mod-posts-load-more-button', MODULARITY_URL . '/dist/'
            . \Modularity\Helper\CacheBust::name('js/mod-posts-load-more-button.js'));
        wp_enqueue_script('mod-posts-load-more-button');
    }

    /**
     * Enqueue scripts
     * @return void
     */
    public function adminEnqueue()
    {
        wp_register_script('mod-posts-taxonomy', MODULARITY_URL . '/dist/'
            . \Modularity\Helper\CacheBust::name('js/mod-posts-taxonomy.js'));
        wp_enqueue_script('mod-posts-taxonomy');

        add_action('admin_head', function () {
            global $post;
            global $archive;

            $id = isset($post->ID) ? $post->ID : "'" . $archive . "'";
            if (empty($id)) {
                return;
            }

            echo '<script>modularity_current_post_id = ' . $id . ';</script>';
        });
    }

    private function getFrontendFilters()
    {
        $frontendFilters = [];
        $frontendFilters['front_end_tax_filtering_text_search'] = get_field(
            'front_end_tax_filtering_text_search',
            $this->ID
        ) ? true : false;
        $frontendFilters['front_end_tax_filtering_dates'] = get_field(
            'front_end_tax_filtering_dates',
            $this->ID
        ) ? true : false;
        $frontendFilters['front_end_tax_filtering_taxonomy'] = get_field(
            'front_end_tax_filtering_taxonomy',
            $this->ID
        ) ? true : false;

        $frontendFilters['front_end_button_text'] = get_field('front_end_button_text', $this->ID);
        $frontendFilters['front_end_hide_date'] = get_field('front_end_hide_date', $this->ID);
        $frontendFilters['front_end_display'] = get_field('front_end_display', $this->ID);

        return $frontendFilters;
    }

    private function enableFilters()
    {
        return get_field('front_end_tax_filtering', $this->ID)
            && get_field('posts_data_post_type', $this->ID) === 'post'
            || get_field('front_end_tax_filtering', $this->ID)
            && get_field('posts_data_post_type', $this->ID) === 'page';
    }

    /**
     * "Fake" WP_POST objects for manually inputted posts
     * @param array $data The data to "fake"
     * @return array        Faked data
     */
    public static function getManualInputPosts($data)
    {
        $posts = [];

        foreach ($data as $key => $item) {
            $posts[] = array_merge((array)$item, [
                'ID' => $key,
                'post_name' => $key,
                'post_excerpt' => $item->post_content
            ]);
        }

        $posts = json_decode(json_encode($posts));

        return $posts;
    }

    /**
     * Get included posts
     * @param object $module Module object
     * @return array          Array with post objects
     */
    public static function getPosts($module): array
    {
        $fields = json_decode(json_encode(get_fields($module->ID)));


        if ($fields->posts_data_source == 'input') {
            return (array) self::getManualInputPosts($fields->data);
        }

        $posts = (array) get_posts(self::getPostArgs($module->ID));
        if (!empty($posts)) {
            foreach ($posts as &$_post) {
                if (empty($_post->permalink)) {
                    $_post->permalink = get_permalink($_post->ID);
                }
            }
        }
        
        return $posts;
    }

    public static function getPostArgs($id)
    {
        $fields = json_decode(json_encode(get_fields($id)));

        $metaQuery = false;
        $orderby = isset($fields->posts_sort_by) && $fields->posts_sort_by ? $fields->posts_sort_by : 'date';
        $order = isset($fields->posts_sort_order) && $fields->posts_sort_order ? $fields->posts_sort_order : 'desc';

        // Get post args
        $getPostsArgs = [
            'posts_per_page' => $fields->posts_count,
            'post_type' => 'any',
            'suppress_filters' => false
        ];

        // Sort by meta key
        if (strpos($orderby, '_metakey_') === 0) {
            $orderby_key = substr($orderby, strlen('_metakey_'));
            $orderby = 'order_clause';
            $metaQuery = [
                [
                    'relation' => 'OR',
                    'order_clause' => [
                        'key' => $orderby_key,
                        'compare' => 'EXISTS'
                    ],
                    [
                        'key' => $orderby_key,
                        'compare' => 'NOT EXISTS'
                    ]
                ]
            ];
        }

        if ($orderby != 'false') {
            $getPostsArgs['order'] = $order;
            $getPostsArgs['orderby'] = $orderby;
        }

        // Post statuses
        $getPostsArgs['post_status'] = ['publish', 'inherit'];
        if (is_user_logged_in()) {
            $getPostsArgs['post_status'][] = 'private';
        }

        // Taxonomy filter
        if (isset($fields->posts_taxonomy_filter) && $fields->posts_taxonomy_filter === true) {
            $taxType = $fields->posts_taxonomy_type;
            $taxValues = (array)$fields->posts_taxonomy_value;

            foreach ($taxValues as $term) {
                $getPostsArgs['tax_query'][] = [
                    'taxonomy' => $taxType,
                    'field' => 'slug',
                    'terms' => $term
                ];
            }
        }

        // Meta filter
        if (isset($fields->posts_meta_filter) && $fields->posts_meta_filter === true) {
            $metaQuery[] = [
                'key' => $fields->posts_meta_key ?? '',
                'value' => [$fields->posts_meta_value ?? ''],
                'compare' => 'IN',
            ];
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
    public static function replaceDeprecatedTemplate($templateSlug)
    {
        // Add deprecated template/replacement slug to array.
        $deprecatedTemplates = [
            'items' => 'index',
            'news' => 'index'
        ];

        if (array_key_exists($templateSlug, $deprecatedTemplates)) {
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
