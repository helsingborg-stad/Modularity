<?php

namespace Modularity\Module\Posts;

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

    public function init()
    {
        $this->nameSingular = __('Posts', 'modularity');
        $this->namePlural = __('Posts', 'modularity');
        $this->description = __('Outputs selected posts in specified layout', 'modularity');
        
        /* Saves meta data to expandable list posts */
        new \Modularity\Module\Posts\Helper\AddMetaToExpandableList();

        add_filter('acf/load_field/name=posts_date_source', array($this, 'loadDateField'));

        //Add full width data to view
        add_filter('Modularity/Block/Data', array($this, 'blockData'), 50, 3);
        add_filter('Modularity/Module/Posts/template', array( $this, 'setTemplate' ), 10, 3);

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
    public function setTemplate($template, $module, $moduleData)
    {
        $showAsSlider = get_field('show_as_slider', $moduleData['ID']);
        $postsDisplayAs = get_field('posts_display_as', $moduleData['ID']);

        $layoutsWithSliderAvailable = array('items', 'news', 'index', 'grid', 'features-grid', 'segment');

        if (1 === (int) $showAsSlider && in_array($postsDisplayAs, $layoutsWithSliderAvailable, true)) {
            $this->getTemplateData(self::replaceDeprecatedTemplate('slider'), $moduleData);
            return 'slider.blade.php';
        }

        return $template;
    }

    /**
     * Retrieve the current WordPress post ID.
     *
     * This function retrieves the unique identifier (ID) of the current WordPress post.
     * It first checks if the global variable $post is set and contains a valid numeric ID.
     * If a valid ID is found, it returns the post ID; otherwise, it returns false.
     *
     * @return int|false Returns the post ID if available and numeric, or false if not found or invalid.
     */
    private static function getCurrentPostID() {
        global $post; 
        if(isset($post->ID) && is_numeric($post->ID)) {
            return $post->ID;
        }
        return false;
    }

    /**
     * Get list of date sources
     *
     * @param string $postType
     * @return array
     */
    public function getDateSource($postType): array
    {

        //TODO: Get this from municipio customozer- 
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
        $this->getTemplateData(self::replaceDeprecatedTemplate($this->data['posts_display_as'] ?? ''));
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
    public function getTemplateData(string $template = '', array $data = array())
    {
        if (empty($template)) {
            return false;
        }
        if (!empty($data)) {
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

        $fields = $this->arrayToObject(
            $this->getFields()
        );

        $data['posts_display_as'] = $fields->posts_display_as ?? false;
        $data['display_reading_time'] = !empty($fields->posts_fields) && in_array('reading_time', $fields->posts_fields) ?? false;

        // Posts
        $data['preamble'] = $fields->preamble ?? false;
        $data['posts_fields'] = $fields->posts_fields ?? false;
        $data['posts_date_source'] = $fields->posts_date_source ?? false;
        $data['posts_data_post_type'] = $fields->posts_data_post_type ?? false;
        $data['posts_data_source'] = $fields->posts_data_source ?? false;

        $data['posts'] = self::getPosts($this);

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

        $data['archive_link_url'] = $this->getArchiveUrl(
            $data['posts_data_post_type'],
            $fields ?? null
        );

        $data['ariaLabels'] =  (object) [
           'prev' => __('Previous slide', 'modularity'),
           'next' => __('Next slide', 'modularity'),
        ];

        if ($this->ID) {
            $data['sliderId'] = $this->ID;
        } else {
            $data['sliderId'] = uniqid();
        }

        return $data;
    }

    /**
     * Converts an associative array to an object.
     *
     * This function takes an associative array and converts it into an object by first
     * encoding the array as a JSON string and then decoding it back into an object.
     * The resulting object will have properties corresponding to the keys in the original array.
     *
     * @param array $array The associative array to convert to an object.
     *
     * @return object Returns an object representing the associative array.
     */
    private function arrayToObject(array $array): object
    {
        return json_decode(json_encode($array)); 
    }

    /**
     * Get the archive URL for a specified post type using provided fields.
     *
     * This function retrieves the archive URL for a specified post type based on the given fields.
     * If the post type is empty or if the archive link field is not set or falsy, it returns false.
     * If the post type is "post," it attempts to retrieve the posts archive URL.
     * Otherwise, it attempts to retrieve the archive URL for the custom post type.
     *
     * @param string      $postType The name of the post type.
     * @param object|null $fields   An object containing fields related to the post type.
     *
     * @return string|false The archive URL if it exists, or false if it doesn't.
     */
    private function getArchiveUrl($postType, $fields) {
        if (empty($postType) || !isset($fields->archive_link) || !$fields->archive_link) {
            return false;
        }

        if ($postType == 'post' && $archiveUrl = $this->getPostsArchiveUrl()) {
            return $archiveUrl;
        }

        if($archiveUrl = $this->getPostTypeArchiveUrl($postType)) {
            return $archiveUrl;
        }

        return false;
    }

    /**
     * Get the archive URL for the posts page.
     *
     * This function retrieves the URL of the page that displays the blog posts archive.
     * If a static page is set as the posts page, it returns the permalink to that page.
     * If the option "Front page displays" is set to "Your latest posts," it returns the home URL.
     * If no valid posts page is found, it returns false.
     *
     * @return string|false The archive URL if it exists, or false if it doesn't.
     */
    private function getPostsArchiveUrl() {
        $pageForPosts = get_option('page_for_posts');

        if(is_numeric($pageForPosts) && post_exists($pageForPosts)) {
            return get_permalink($pageForPosts); 
        }

        if(get_option('show_on_front') == 'posts') {
            return get_home_url(); 
        }

        return false;
    }

    /**
     * Get the archive URL for a custom post type.
     *
     * This function retrieves the archive URL for a given custom post type.
     * If the post type does not have an archive, it returns false.
     *
     * @param string $postType The key of the custom post type.
     *
     * @return string|false The archive URL if it exists, or false if it doesn't.
     */
    private function getPostTypeArchiveUrl($postType) {
        if($postTypeObject = get_post_type_object($postType)) {
            if(is_a($postTypeObject, 'WP_Post_Type') && $postTypeObject->has_archive) {
                return get_post_type_archive_link($postType);
            }
        }
        return false;
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

        $meta = \Municipio\Helper\Post::getPosttypeMetaKeys($_POST['posttype']);

        $response = [
            'meta_keys' => $meta,
            'sort_curr' => get_field('posts_sort_by', $_POST['post']),
            'filter_curr' => get_field('posts_meta_key', $_POST['post']),
        ];

        echo json_encode($response);
        die();
    }

    /**
     * "Fake" WP_POST objects for manually inputted posts
     * @param array $data The data to "fake"
     * @return array        Faked data
     */

     /* REMOVE: remove after refactoring */
    public static function getManualInputPosts($data, bool $stripLinksFromContent = false)
    {
        $posts = [];
        foreach ($data as $key => $item) {
            $posts[] = array_merge((array)$item, [
                'ID' => $key,
                'post_name' => $key,
                'post_excerpt' => $stripLinksFromContent ? strip_tags($item->post_content, '') : $item->post_content
            ]);
        }
        
        foreach ($posts as &$post) {
            if (class_exists('\Municipio\Helper\FormatObject')) {
                $post = \Municipio\Helper\FormatObject::camelCase($post);
            }
        }

        $posts = self::arrayToObject($posts);

        return $posts;
    }

    /**
     * Get included posts
     * @param object $module Module object
     * @return array          Array with post objects
     */
    public static function getPosts($module): array
    {
        $fields = self::arrayToObject(
            get_fields($module->ID)
        );

        //TODO: Remove [Start feature: Manual Input]
        if ($fields->posts_data_source == 'input') {
            // Strip links from content if display items are linked (we can't do links in links)
            $stripLinksFromContent = in_array($fields->posts_display_as, ['items', 'index', 'news', 'collection']) ?? false;
            return (array) self::getManualInputPosts(
                $fields->data, 
                $stripLinksFromContent
            );
        }
        //TODO: Remove [End feature: Manual Input]

        $posts = (array) get_posts(self::getPostArgs($module->ID));
        if (!empty($posts)) {
            foreach ($posts as &$_post) {
                $data['taxonomiesToDisplay'] = !empty($fields->taxonomy_display) ? $fields->taxonomy_display : [];
                
                if (class_exists('\Municipio\Helper\Post')) {
                    $_post = \Municipio\Helper\Post::preparePostObject($_post, $data);

                    if (!empty($_post)) {
                        $_post->attributeList['data-js-map-location'] = json_encode($_post->location);
                    }

                    if (!empty($fields->posts_fields) && in_array('image', $fields->posts_fields) && !empty($_post->thumbnail) && empty($_post->thumbnail['src'])) {
                        $_post->thumbnail['src'] = \Modularity\Helper\Wp::getThemeMod('logotype_emblem') ?: get_stylesheet_directory_uri() . '/assets/images/broken_image.svg';
                        $_post->hasPlaceholderImage = true;
                    }
                    
                    $_post->floating = apply_filters('Modularity/Module/Posts/Floating', [], $_post);
                }
            }
        }

        return $posts;
    }

    public static function getPostArgs($id)
    {
        $fields = self::arrayToObject(
            get_fields($id)
        );

        $metaQuery  = false;
        $orderby    = isset($fields->posts_sort_by) && $fields->posts_sort_by ? $fields->posts_sort_by : 'date';
        $order      = isset($fields->posts_sort_order) && $fields->posts_sort_order ? $fields->posts_sort_order : 'desc';

        // Get post args
        $getPostsArgs = [
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
                if($currentPostID = self::getCurrentPostID()) {
                    $getPostsArgs['post__not_in'] = [
                        $currentPostID
                    ]; 
                }
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
            'news'  => 'index'
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
