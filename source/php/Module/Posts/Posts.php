<?php

namespace Modularity\Module\Posts;

use Modularity\Module\Posts\Helper\GetArchiveUrl;
use Modularity\Module\Posts\Helper\GetPosts;

/**
 * Class Posts
 * @package Modularity\Module\Posts
 */
class Posts extends \Modularity\Module
{
    public $slug = 'posts';
    public $supports = [];
    public $fields;
    public $blockSupports = array(
        'align' => ['full']
    );
    public $getPostsHelper;
    public $archiveUrlHelper;
    private array $enabledSchemaTypes = [];

    private $sliderCompatibleLayouts = ['items', 'news', 'index', 'grid', 'features-grid', 'segment'];

    public function init()
    {
        $this->nameSingular     = __('Posts', 'modularity');
        $this->namePlural       = __('Posts', 'modularity');
        $this->description      = __('Outputs selected posts in specified layout', 'modularity');
        
        // Saves meta data to expandable list posts
        new \Modularity\Module\Posts\Helper\AddMetaToExpandableList();

        // Handle date field
        add_filter('acf/load_field/name=posts_date_source', array($this, 'loadDateField'));
        
        // Populate enabled schema types
        add_filter('Municipio/SchemaData/EnabledSchemaTypes', [$this, 'setEnabledSchemaTypes']);

        // Populate schema types field
        add_filter('acf/load_field/name=posts_data_schema_type', [$this, 'loadSchemaTypesField']);
        
        // Populate schema types field
        add_filter('acf/load_field/name=posts_data_network_sources', [$this, 'loadNetworkSourcesField']);

        //Add full width data to view
        add_filter('Modularity/Block/Data', array($this, 'blockData'), 50, 3);
        add_filter(
            'acf/fields/post_object/query/name=posts_data_posts', 
            array($this, 'removeUnwantedPostTypesFromManuallyPicked'), 10, 3
        );
        
        // Helpers
        $this->getPostsHelper = new GetPosts();
        $this->archiveUrlHelper = new GetArchiveUrl();
        new PostsAjax($this);
    }

    /**
     * Set enabled schema types
     *
     * @param array $types
     * @return array
     */
    public function setEnabledSchemaTypes(array $types):array {
        $this->enabledSchemaTypes = array_keys($types);
        return $types;
    }

    /**
     * Load schema types field
     *
     * @param array $field
     * @return array
     */
    public function loadSchemaTypesField(array $field = []):array {
        if(get_post_type() === 'acf-field-group') {
            return $field;
        }

        $field['choices'] = array_combine($this->enabledSchemaTypes, $this->enabledSchemaTypes);
        return $field;
    }

    public function loadNetworkSourcesField(array $field = []):array {
        
        if(!is_multisite() || get_post_type() === 'acf-field-group') {
            return $field;
        }

        $field['choices'] = [];

        foreach (get_sites() as $site) {
            switch_to_blog($site->blog_id);
            $field['choices'][$site->blog_id] = get_bloginfo('name');
            restore_current_blog();
        }

        return $field;
    }

    /**
     * @return array
     */
    public function data(): array
    {
        $data = [];
        $this->fields = $this->getFields();
        $data['posts_display_as'] = $this->fields['posts_display_as'] ?? false;
        $data['display_reading_time'] = !empty($this->fields['posts_fields']) && in_array('reading_time', $this->fields['posts_fields']) ?? false;

        // Posts
        $data['preamble']               = $this->fields['preamble'] ?? false;
        $data['posts_fields']           = $this->fields['posts_fields'] ?? false;
        $data['posts_date_source']      = $this->fields['posts_date_source'] ?? false;
        $data['posts_data_post_type']   = $this->fields['posts_data_post_type'] ?? false;
        $data['posts_data_source']      = $this->fields['posts_data_source'] ?? false;

        $postsAndPaginationData = $this->getPostsAndPaginationData();
        $data['posts'] = $postsAndPaginationData['posts'];

        if( !empty($this->fields['posts_pagination']) && $this->fields['posts_pagination'] === 'page_numbers' ) {
            $data['maxNumPages'] = $postsAndPaginationData['maxNumPages'];
            $data['paginationArguments'] = $this->getPaginationArguments($data['maxNumPages'], $this->getPageNumber());
        } else {
            $data['paginationArguments'] = null;
        }

        // Sorting
        $data['sortBy'] = false;
        $data['orderBy'] = false;
        if (isset($this->fields['posts_sort_by']) && substr($this->fields['posts_sort_by'], 0, 9) === '_metakey_') {
            $data['sortBy'] = 'meta_key';
            $data['sortByKey'] = str_replace('_metakey_', '', $this->fields['posts_sort_by']);
        }

        $data['order'] = isset($this->fields['posts_sort_order']) ? $this->fields['posts_sort_order'] : 'asc';

        // Setup filters
        $filters = [
            'orderby' => sanitize_text_field($data['sortBy']),
            'order' => sanitize_text_field($data['order'])
        ];

        if ($data['sortBy'] == 'meta_key') {
            $filters['meta_key'] = $data['sortByKey'];
        }

        $data['filters'] = [];

        if (isset($this->fields['posts_taxonomy_filter']) && $this->fields['posts_taxonomy_filter'] === true && !empty($this->fields['posts_taxonomy_type'])) {
            $taxType = $this->fields['posts_taxonomy_type'];
            $taxValues = (array)$this->fields['posts_taxonomy_value'];
            $taxValues = implode('|', $taxValues);

            $data['filters']['filter[' . $taxType . ']'] = $taxValues;
        }

        //Get archive link
        $data['archive_link_url'] = $this->archiveUrlHelper->getArchiveUrl(
            $data['posts_data_post_type'],
            $this->fields ?? null
        );

        //Add filters to archive link
        if($data['archive_link_url'] && is_array($data['filters']) && !empty($data['filters'])) {
            $data['archive_link_url'] .= "?" . http_build_query($data['filters']);
        }

        $data['ariaLabels'] =  (object) [
            'prev' => __('Previous slide', 'modularity'),
            'next' => __('Next slide', 'modularity'),
        ];

        if ($this->ID) {
            $data['sliderId'] = $this->ID;
        } else {
            $data['sliderId'] = uniqid();
            $data['ID'] = uniqid();
        }

        $data['classList'] = [];

        $data['lang'] = [
            'showMore' => __('Show more', 'modularity'),
            'readMore' => __('Read more', 'modularity')
        ];

        return $data;
    }

    /**
     * Get pagination identifier
     * 
     * @return string
     */
    private function getPagintationIdentifier():string {
        return "{$this->post_type}-{$this->ID}-page";
    }

    /**
     * Get current page number
     * 
     * @return int Default is 1
     */
    private function getPageNumber():int {
        return filter_input( INPUT_GET, $this->getPagintationIdentifier(), FILTER_SANITIZE_NUMBER_INT ) ?: 1;
    }

    /**
     * Get pagination arguments for page numbers.
     * 
     * @param int $maxNumPages
     * @param int $currentPage
     * @return array
     */
    private function getPaginationArguments(int $maxNumPages, int $currentPage):array {

        if($maxNumPages < 2) {
            return [];
        }
        
        $listItemOne = [
            'href' => remove_query_arg($this->getPagintationIdentifier()),
            'label' => __("First page", 'modularity')
        ];

        $listItems = array_map(function($pageNumber) {
            return [
                'href' => add_query_arg($this->getPagintationIdentifier(), $pageNumber),
                'label' => sprintf(__("Page %d", 'modularity'), $pageNumber)
            ];
        }, range(2, $maxNumPages));

        return [
            'list' => array_merge([$listItemOne], $listItems),
            'current' => $currentPage
        ];
    }

    /**
     * Get list of date sources
     *
     * @param string $postType
     * @return array
     */
    public function getDateSource($postType): array
    {
        //TODO: Remove [Start feature: Date from Archive settings]
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

        //TODO: Remove [End feature: Date from Archive settings]
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
     * Removes unwanted post types from the manually picked post types.
     *
     * @param array $args The arguments for the query.
     * @param string $field The field name.
     * @param int $id The ID of the module.
     * @return array The modified arguments.
     */
    public function removeUnwantedPostTypesFromManuallyPicked($args, $field, $id) 
    {
        $skipablePostTypes = ['attachment'];

        $args['post_type'] = array_filter($args['post_type'] ?? [], function($postType) use ($skipablePostTypes) {
            return !in_array($postType, $skipablePostTypes);
        });

        return $args;
    }

    //TODO: Remove [Start feature: Date from Archive settings]
    public function loadDateField($field = [])
    {
        $postType = get_field('posts_data_post_type', $this->ID);

        if (empty($postType)) {
            return $field;
        }

        $field['choices'] = $this->getDateSource($postType);

        return $field;
    }
    //TODO: Remove [End feature: Date from Archive settings]

    /**
     * @return false|string
     */
    public function template()
    {   
        $template = $this->data['posts_display_as'] ?? 'list';

        if (!empty($this->fields['show_as_slider']) && in_array($this->fields['posts_display_as'], $this->sliderCompatibleLayouts, true)) {
            $template = 'slider';
        }
        
        $this->getTemplateData($this->replaceDeprecatedTemplate($template));

        return apply_filters(
            'Modularity/Module/Posts/template',
            $this->replaceDeprecatedTemplate($template) . '.blade.php',
            $this,
            $this->data,
            $this->fields
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

        $this->data['meta']['posts_display_as'] = $this->replaceDeprecatedTemplate($this->data['posts_display_as']);

        if (class_exists($class)) {
            $controller = new $class($this);
            $this->data = array_merge($this->data, $controller->data);
        }
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
    public function arrayToObject($array)
    {
        if(!is_array($array)) {
            return $array;
        }

        return json_decode(json_encode($array)); 
    }

    /**
     * Get posts and pagination data.
     *
     * @return array $postsAndPaginationData Array with posts and pagination data. e.g. ['posts' => [], 'maxNumPages' => 0]
     */
    public function getPostsAndPaginationData(): array
    {
        if ($this->fields) {
            return $this->getPostsHelper->getPostsAndPaginationData($this->fields, $this->getPageNumber());
        }

        return [
            'posts' => [],
            'maxNumPages' => 0
        ];
    }

    /**
     * For version 3.0 - Replace old post templates with existing replacement.
     * @param $templateSlug
     * @return mixed
     */
    public function replaceDeprecatedTemplate($templateSlug)
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

    public function adminEnqueue() {
        wp_register_script('mod-posts-taxonomy-filtering', MODULARITY_URL . '/dist/'
        . \Modularity\Helper\CacheBust::name('js/mod-posts-taxonomy-filtering.js'));
        wp_localize_script('mod-posts-taxonomy-filtering', 'modPostsTaxonomyFiltering', [
            'currentPostID' => $this->getPostsHelper->getCurrentPostID(),
        ]);
        wp_enqueue_script('mod-posts-taxonomy-filtering');
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
