<?php

namespace Modularity\Module\Posts;

class Posts extends \Modularity\Module
{
    /**
     * Constructor
     */
    public function __construct()
    {
        // This will register the module
        $this->register(
            'posts',
            __('Posts', 'modularity'),
            __('Posts', 'modularity'),
            __('Outputs selected posts in specified layout', 'modularity'),
            array(),
            'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNC4zMzQgMjQuMzM0Ij48ZyBmaWxsPSIjMDMwMTA0Ij48cGF0aCBkPSJNMTAuMjk1IDBILjkzNUEuOTM2LjkzNiAwIDAgMCAwIC45MzZ2OS4zNmMwIC41MTYuNDIuOTM1LjkzNi45MzVoOS4zNmMuNTE2IDAgLjkzNS0uNDE4LjkzNS0uOTM1Vi45MzVBLjkzNi45MzYgMCAwIDAgMTAuMjk2IDB6TTIzLjM5OCAwaC05LjM2YS45MzYuOTM2IDAgMCAwLS45MzUuOTM2djkuMzZjMCAuNTE2LjQyLjkzNS45MzYuOTM1aDkuMzU4Yy41MTcgMCAuOTM2LS40MTguOTM2LS45MzVWLjkzNUEuOTM2LjkzNiAwIDAgMCAyMy4zOTggMHptLS45MzYgOS4zNmgtNy40ODdWMS44N2g3LjQ4N1Y5LjM2ek0xMC4yOTUgMTMuMTAzSC45MzVBLjkzNi45MzYgMCAwIDAgMCAxNC4wNHY5LjM1OGMwIC41MTcuNDIuOTM2LjkzNi45MzZoOS4zNmMuNTE2IDAgLjkzNS0uNDIuOTM1LS45MzZ2LTkuMzZhLjkzNS45MzUgMCAwIDAtLjkzNS0uOTM1em0tNS44NzUgOS4zNmMuMTYtLjI3Ny4yNi0uNTk0LjI2LS45MzdhMS44NzIgMS44NzIgMCAwIDAtMS44NzItMS44NzJjLS4zNDMgMC0uNjYuMS0uOTM2LjI2di0yLjM5Yy4yNzYuMTYuNTkzLjI2LjkzNi4yNkExLjg3MiAxLjg3MiAwIDAgMCA0LjY4IDE1LjkxYzAtLjM0Mi0uMS0uNjYtLjI2LS45MzVoMi4zOWExLjg1IDEuODUgMCAwIDAtLjI2LjkzNmMwIDEuMDM1Ljg0IDEuODczIDEuODczIDEuODczLjM0MyAwIC42Ni0uMS45MzYtLjI2djIuMzlhMS44NSAxLjg1IDAgMCAwLS45MzctLjI2IDEuODcyIDEuODcyIDAgMCAwLTEuODcyIDEuODczYzAgLjM0My4xLjY2LjI2LjkzNkg0LjQyek0yMy4zOTggMTMuMTAzaC05LjM2YS45MzYuOTM2IDAgMCAwLS45MzUuOTM2djkuMzU4YzAgLjUxNi40Mi45MzUuOTM2LjkzNWg5LjM1OGMuNTE2IDAgLjkzNS0uNDIuOTM1LS45MzZ2LTkuMzZhLjkzNC45MzQgMCAwIDAtLjkzNS0uOTM0em0tOC40MjMgNi4wMDNsNC4xMy00LjEzaDIuMDM0bC02LjE2NSA2LjE2M3YtMi4wMzR6bTAtNC4xM2gxLjQ4NGwtMS40ODUgMS40ODN2LTEuNDg1em03LjQ4NyAxLjMyMnYyLjAzMmwtNC4xMyA0LjEzMmgtMi4wMzRsNi4xNjQtNi4xNjR6bTAgNi4xNjRoLTEuNDg1bDEuNDg1LTEuNDg1djEuNDg1eiIvPjxjaXJjbGUgY3g9IjUuNjE2IiBjeT0iMTguNzE4IiByPSIxLjg3MiIvPjwvZz48L3N2Zz4=',
            null
        );

        add_action('Modularity/Module/' . $this->moduleSlug . '/enqueue', array($this, 'enqueueScripts'));
        add_action('add_meta_boxes', array($this, 'addColumnFields'));
        add_action('save_post', array($this, 'saveColumnFields'));

        add_action('wp_ajax_get_taxonomy_types_v2', array($this, 'getTaxonomyTypes'));
        add_action('wp_ajax_get_taxonomy_values_v2', array($this, 'getTaxonomyValues'));
        add_action('wp_ajax_get_sortable_meta_keys_v2', array($this, 'getSortableMetaKeys'));
    }

    public function getSortableMetaKeys()
    {
        if (!isset($_POST['posttype']) || empty($_POST['posttype'])) {
            echo '0';
            die();
        }

        $meta = \Modularity\Helper\Post::getPosttypeMetaKeys($_POST['posttype']);

        $response = array(
            'meta_keys' => $meta,
            'curr' => get_field('sorted_by', $_POST['post'])
        );

        echo json_encode($response);
        die();
    }

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
            'tax' => get_terms($taxonomy),
            'curr' => get_field('posts_taxonomy_value', $post)
        );

        echo json_encode($result);
        die();
    }

    public function saveColumnFields($postId)
    {
        if (!isset($_POST['modularity-mod-posts-expandable-list'])) {
            delete_post_meta($postId, 'modularity-mod-posts-expandable-list');
            return;
        }

        update_post_meta($postId, 'modularity-mod-posts-expandable-list', $_POST['modularity-mod-posts-expandable-list']);
    }

    /**
     * Check wheather to add expandable list column fields to edit post screeen
     */
    public function addColumnFields()
    {
        global $post;
        global $current_screen;

        if (empty($post_type)) {
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

        $fields = $this->getColumns($modules);

        add_meta_box(
            'modularity-mod-posts-expandable-list',
            'Modularity expandable list column values',
            array($this, 'columnFieldsMetaBoxContent'),
            null,
            'normal',
            'default',
            array($fields)
        );
    }

    /**
     * Expandable list column value fields metabox content
     * @param  object $post Post object
     * @param  array  $args Arguments
     * @return void
     */
    public function columnFieldsMetaBoxContent($post, $args)
    {
        $fields = $args['args'][0];
        $fieldValues = get_post_meta( $post->ID, 'modularity-mod-posts-expandable-list', true);

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
    }

    /**
     * Get field columns
     * @param  array $posts Post ids
     * @return array        Column names
     */
    public function getColumns($posts)
    {
        $columns = array();

        foreach ($posts as $post) {
            $values = get_field('posts_list_column_titles', $post);

            foreach ($values as $value) {
                $columns[] = $value['column_header'];
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
     * @param  integer $id Postid
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
     * @param  integer $id Post id
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
     * Enqueue scripts
     * @return void
     */
    public function enqueueScripts()
    {
        wp_enqueue_script('mod-latest-taxonomy', MODULARITY_URL . '/dist/js/Posts/assets/mod-posts-taxonomy.js', array(), '1.0.0', true);

        add_action('admin_head', function () {
            global $post;

            if (empty($post)) {
                return;
            }

            echo '<script>var modularity_current_post_id = ' . $post->ID . ';</script>';
        });
    }

    public static function getManualInputPosts($data)
    {
        $posts = array();

        foreach ($data as $key => $item) {
            $posts[] = array_merge((array) $item, array(
                'ID' => $key,
                'post_name' => $key
            ));
        }

        $posts = json_decode(json_encode($posts));

        return $posts;
    }

    /**
     * Get included posts
     * @param  object $module Module object
     * @return array          Array with post objects
     */
    public static function getPosts($module)
    {
        $fields = json_decode(json_encode(get_fields($module->ID)));

        if ($fields->posts_data_source == 'input') {
            return self::getManualInputPosts($fields->data);
        }

        $metaQuery = false;
        $sortBy = $fields->posts_sort_by ? $fields->posts_sort_by : 'date';
        $order = $fields->posts_sort_order ? $fields->posts_sort_order : 'desc';

        // Get post args
        $getPostsArgs = array(
            'posts_per_page' => $fields->posts_count,
            'post_type' => 'any'
        );

        if ($sortBy != 'false') {
            $getPostsArgs['order'] = $order;
            $getPostsArgs['orderby'] = $sortBy;
        }

        // Sort by meta key
        if (strpos($sortBy, '_metakey_') > -1) {
            $orderby = str_replace('_metakey_', '', $sortBy);
            $metaQuery = array(
                'relation' => 'OR',
                array(
                    'key' => $orderby,
                    'compare' => 'EXISTS'
                ),
                array(
                    'key' => $orderby,
                    'compare' => 'NOT EXISTS'
                )
            );

            $sortBy = 'meta_key';
            $getPostsArgs['orderby'] = $orderby;
        }

        // Taxonomy filter
        if ($fields->posts_taxonomy_filter === true) {
            $taxType = $fields->posts_taxonomy_type;
            $taxValues = (array) $fields->posts_taxonomy_value;

            foreach ($taxValues as $term) {
                $getPostsArgs['tax_query'][] = array(
                    'taxonomy' => $taxType,
                    'field'    => 'slug',
                    'terms'    => $term
                );
            }
        }

        // Data source
        switch ($fields->posts_data_source) {
            case 'posttype':
                $getPostsArgs['post_type'] = $fields->posts_data_post_type;
                break;

            case 'children':
                $getPostsArgs['post_parent'] = $fields->posts_data_child_of;
                break;

            case 'manual':
                $getPostsArgs['post__in'] = $fields->posts_data_posts;
                if ($sortBy == 'false') $getPostsArgs['orderby'] = 'post__in';
                break;
        }

        // Add metaquery to args
        if ($metaQuery) {
            $getPostsArgs['meta_query'] = $metaQuery;
        }

        return get_posts($getPostsArgs);
    }
}
