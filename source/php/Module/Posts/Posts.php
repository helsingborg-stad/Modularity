<?php

namespace Modularity\Module\Posts;

use Municipio\Helper\Image as ImageHelper;
use Modularity\Module\Posts\Helper\GetArchiveUrl as ArchiveUrlHelper;
use Modularity\Module\Posts\Helper\GetPosts as GetPostsHelper;

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

        //Add full width data to view
        add_filter('Modularity/Block/Data', array($this, 'blockData'), 50, 3);

        new PostsAjax($this);
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

        $this->fields = $fields;
        
        $data['posts_display_as'] = $fields->posts_display_as ?? false;
        $data['display_reading_time'] = !empty($fields->posts_fields) && in_array('reading_time', $fields->posts_fields) ?? false;

        // Posts
        $data['preamble'] = $fields->preamble ?? false;
        $data['posts_fields'] = $fields->posts_fields ?? false;
        $data['posts_date_source'] = $fields->posts_date_source ?? false;
        $data['posts_data_post_type'] = $fields->posts_data_post_type ?? false;
        $data['posts_data_source'] = $fields->posts_data_source ?? false;

        $data['posts'] = $this->getPosts($fields);

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

        if (isset($fields->posts_taxonomy_filter) && $fields->posts_taxonomy_filter === true && !empty($fields->posts_taxonomy_type)) {
            $taxType = $fields->posts_taxonomy_type;
            $taxValues = (array)$fields->posts_taxonomy_value;
            $taxValues = implode('|', $taxValues);

            $data['filters']['filter[' . $taxType . ']'] = $taxValues;
        }

        $data['archive_link_url'] = ArchiveUrlHelper::getArchiveUrl(
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

        if (!empty($this->fields->show_as_slider) && in_array($this->fields->posts_display_as, $this->sliderCompatibleLayouts, true)) {
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
            $controller = new $class($this, $this->args, $this->data, $this->fields);
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
     * "Fake" WP_POST objects for manually inputted posts
     * @param array $data The data to "fake"
     * @return array        Faked data
     */

    //TODO: Remove [Start feature: Manual Input]
    public function getManualInputPosts($data, bool $stripLinksFromContent = false)
    {
        $posts = [];

        foreach ($data as $key => $item) {
            $posts[] = array_merge((array)$item, [
                'ID' => $key,
                'post_name' => $key,
                'post_excerpt' => $stripLinksFromContent ? strip_tags($item->post_content, '') : $item->post_content,
                'excerpt_short' => $stripLinksFromContent ? strip_tags($item->post_content, '') : $item->post_content,
                'images' => [
                    'thumbnail_16:9' => ImageHelper::getImageAttachmentData($item->image ?? false, [400, 225]),
                    'thumbnail_4:3' => ImageHelper::getImageAttachmentData($item->image ?? false, [390, 520]),
                    'thumbnail_1:1' => ImageHelper::getImageAttachmentData($item->image ?? false, [500, 500]),
                    'thumbnail_3:4' => ImageHelper::getImageAttachmentData($item->image ?? false, [400, 225]),
                    'featuredImage' => ImageHelper::getImageAttachmentData($item->image ?? false, [240, 320]),
                    'thumbnail_12:16' => ImageHelper::getImageAttachmentData($item->image ?? false, [240, 320])
                ],
                'postDateFormatted' => null,
                'termsUnlinked' => null,
                'dateBadge' => false,
                'termIcon' => false,
                'postContentFiltered' => apply_filters('the_content', $item->post_content)
            ]);
        }
        
        foreach ($posts as &$post) {
            if (class_exists('\Municipio\Helper\FormatObject')) {
                $post = \Municipio\Helper\FormatObject::camelCase($post);
            }
        }

        return $posts;
    }
    //TODO: Remove [End feature: Manual Input]

    /**
     * Get included posts
     * @param object Acf fields
     * @return array Array with post objects
     */
    public function getPosts($fields): array
    {
        //TODO: Remove [Start feature: Manual Input]. Remove whole method and move to GetPost Helper
        if ($fields->posts_data_source == 'input') {
            // Strip links from content if display items are linked (we can't do links in links)
            $stripLinksFromContent = in_array($fields->posts_display_as, ['items', 'index', 'news', 'collection']) ?? false;
            return (array) $this->getManualInputPosts(
                $fields->data, 
                $stripLinksFromContent
            );
        }
        //TODO: Remove [End feature: Manual Input]
        return GetPostsHelper::getPosts($fields);
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
        wp_register_script('mod-posts-script', MODULARITY_URL . '/source/php/Module/Posts/assets/mod-posts-taxonomy.js');
        wp_localize_script('mod-posts-script', 'modPosts', [
            'currentPostID' => GetPostsHelper::getCurrentPostID(),
        ]);
        wp_enqueue_script('mod-posts-script');
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
