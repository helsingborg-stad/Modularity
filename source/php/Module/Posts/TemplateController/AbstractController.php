<?php

namespace Modularity\Module\Posts\TemplateController;

use Modularity\Helper\WpService;
use Modularity\Module\Posts\Helper\Column as ColumnHelper;

/**
 * Class AbstractController
 *
 * @package Modularity\Module\Posts\TemplateController
 */
class AbstractController
{
    /** @var array */
    public $data = [];
    
    /** @var array */
    public $fields = [];

    /** @var \Modularity\Module\Posts\Posts */
    protected $module;

    /**
     * AbstractController constructor.
     *
     * @param \Modularity\Module\Posts\Posts $module
    */
    public function __construct(\Modularity\Module\Posts\Posts $module)
    {
        $this->module               = $module;
        $this->fields               = $module->fields;
        $this->data                 = $this->addDataViewData($module->data, $module->fields);
        $this->data['posts']        = $this->preparePosts($module);

        $this->data['classList']    = [];
    }

    /**
     * Prepare posts for display.
     *
     * @param \Modularity\Module\Posts\Posts $module
     *
     * @return array
    */
    public function preparePosts(\Modularity\Module\Posts\Posts $module)
    {
        $stickyPosts = $module->data['stickyPosts'] ?? [];
        $stickyPosts = $this->addStickyPostsData($stickyPosts);
        $stickyPosts = $this->addPostData($stickyPosts);
        $posts       = $this->addPostData($module->data['posts']);
        $posts       = array_merge($stickyPosts, $posts);

        return $posts;
    }

    /**
     * Prepare and set data fields for posts display.
     *
     * @param array $data
     * @param array $fields
     *
     * @return array
    */
    public function addDataViewData(array $data, array $fields) 
    {
        $data['posts_columns'] = apply_filters('Modularity/Display/replaceGrid', $fields['posts_columns']);
        $data['ratio'] = $fields['ratio'] ?? '16:9';

        $data['highlight_first_column_as'] = $fields['posts_display_highlighted_as'] ?? 'block';
        $data['highlight_first_column'] = !empty($fields['posts_highlight_first']) ? 
            ColumnHelper::getFirstColumnSize($data['posts_columns']) : 
            false;
        $data['imagePosition'] = $fields['image_position'] ?? false;

        return $data;
    }

    /**
     * Prepare posts data by setting default values and post flags.
     *
     * @param array $posts
     *
     * @return array
     * TODO: This should require an array, but cant because sometimes it gets null. 
    */
    public function addPostData($posts = [])
    {
        $wpService = WpService::get();

        $posts = array_map(function($post) use ($wpService) {
            $data['taxonomiesToDisplay'] = !empty($fields['taxonomy_display'] ?? null) ? $this->fields['taxonomy_display'] : [];
            $helperClass = '\Municipio\Helper\Post';
            $helperMethod = 'preparePostObject';
            $helperArchiveMethod = 'preparePostObjectArchive';
            
            if(!class_exists($helperClass) || !method_exists($helperClass, $helperMethod) || !method_exists($helperClass, $helperArchiveMethod)) {
                error_log("Class or method does not exist: {$helperClass}::{$helperMethod} or {$helperClass}::{$helperArchiveMethod}");
                return $post;
            }

            if(!empty($post->originalBlogId) && $post->originalBlogId !== $wpService->getCurrentBlogId()) {
                $wpService->switchToBlog($post->originalBlogId);
            }

            if (isset($this->fields['posts_display_as']) && in_array($this->fields['posts_display_as'], ['expandable-list'])) {
                $post = call_user_func([$helperClass, $helperMethod], $post);
            } else {
                $post = call_user_func([$helperClass, $helperArchiveMethod], $post, $data);
            }

            $wpService->restoreCurrentBlog();
            
            if (!empty($post->schemaData['place']['pin'])) {
                $post->attributeList['data-js-map-location'] = json_encode($post->schemaData['place']['pin']);
            }
            
            return $post;

        }, $posts ?? []);

        if(!empty($posts)) {
            foreach ($posts as $index => &$post) {
                $post             = $this->setPostViewData($post, $index);
                $post->classList  = $post->classList ?? [];
                $post             = $this->addHighlightData($post, $index);

                // Apply $this->getDefaultValuesForPosts() to the post object without turning it into an array
                foreach ($this->getDefaultValuesForPosts() as $key => $value) {
                    if (!isset($post->$key)) {
                        $post->$key = $value;
                    }
                }
            }
        }

        return $posts;
    }

    /**
     * Add post columns class.
     *
     * @param object $post
     * @param false|int $index
     *
     * @return object
    */
    private function addHighlightData(object $post, $index): object
    {
        $columnsClass =  $this->data['posts_columns'] ?? 'o-grid-12@md';

        if (!empty($post->isSticky)) {
            $columnsClass = 'o-grid-12@md';
            $post->isHighlighted = true;
        } elseif ($index === 0 && !empty($this->data['highlight_first_column'])) {
            $columnsClass = $this->data['highlight_first_column'];
            $post->isHighlighted = true;
        }

        $post->classList[] = $columnsClass;

        return $post;
    }

    /**
     * Get default values for keys in the post object.
     *
     * @return array
    */
    private function getDefaultValuesForPosts() {
        return [
            'postTitle' => false,
            'excerptShort' => false,
            'termsUnlinked' => false,
            'postDateFormatted' => false,
            'dateBadge' => false,
            'images' => false,
            'hasPlaceholderImage' => false,
            'readingTime' => false,
            'permalink' => false,
            'id' => false,
            'postType' => false,
            'termIcon' => false,
            'callToActionItems' => false,
            'imagePosition' => true,
            'image' => false,
            'attributeList' => [],
            'isSticky' => false,
            'commentCount' => false,
        ];
    }

    /**
     * Set boolean flags for hiding/showing specific post details.
     *
     * @param object $post
     * @param false|int $index
     *
     * @return object
    */
    private function setPostViewData(object $post, $index = false)
    {
        $post->excerptShort         = in_array('excerpt', $this->data['posts_fields'] ?? []) ? $post->excerptShort : false;
        $post->postTitle            = in_array('title', $this->data['posts_fields'] ?? []) ? $post->getTitle() : false;
        $post->image                = in_array('image', $this->data['posts_fields'] ?? []) ? $this->getImageContractOrByRatio(
            $post->images ?? null, 
            $post->imageContract ?? null
        ) : [];
        $post->postDateFormatted    = in_array('date', $this->data['posts_fields'] ?? []) ? $post->postDateFormatted : false;
        $post->hasPlaceholderImage  = in_array('image', $this->data['posts_fields'] ?? []) && empty($post->image) ? true : false;
        $post->commentCount         = in_array('comment_count', $this->data['posts_fields'] ?? []) ? (string) $post->getCommentCount() : false;
        $post->readingTime          = in_array('reading_time', $this->data['posts_fields'] ?? []) ? $post->readingTime : false;

        $post->attributeList                    = !empty($post->attributeList) ? $post->attributeList : [];
        $post->attributeList['data-js-item-id'] = $post->getId();

        if (!empty($post->image) && is_array($post->image)) {
            $post->image['removeCaption'] = true;
            $post->image['backgroundColor'] = 'secondary';
        }

        return $post;
    }

    public function postUsesSchemaTypeEvent(object $post):bool {
        if(!isset($post->schemaObject)) {
            return false;
        } 

        $implements = class_implements($post->schemaObject);
        
        return  in_array('Spatie\SchemaOrg\BaseType', $implements) &&
                isset($post->schemaObject['@type']) &&
                $post->schemaObject['@type'] == 'Event';
    }

    /**
     * Get the image based on ratio and index.
     *
     * @param array $images
     *
     * @return mixed|null
    */
    private function getImageBasedOnRatio(array $images) {
        if (empty($this->data['posts_display_as']) || empty($images['thumbnail16:9']['src'])) return false;

        if (!empty($this->data['highlight_first_column']) && in_array($this->data['posts_display_as'], ['block', 'index'])) {
            return $images['featuredImage'];
        }

        switch ($this->data['posts_display_as']) {
            case 'grid': 
                return $images['thumbnail' . $this->data['ratio']] ?? false;
            default: 
                return $images['thumbnail16:9'];
        }

        return false;
    }

    /**
     * Get image by contract or by ratio.
     *
     * @param array $images
     * @param mixed $imageContract
     *
     * @return mixed
    */
    private function getImageContractOrByRatio(array $images, $imageContract) {

        //Image by contract
        if(is_a($imageContract, 'ComponentLibrary\Integrations\Image\Image') && !empty($imageContract->getUrl())) {
            return $imageContract;
        }

        //Image by ratio
        $imageByRatio = $this->getImageBasedOnRatio($images);
        if(isset($imageByRatio['src']) && !empty($imageByRatio['src'])) {
            return $imageByRatio['src'];
        }

        return false;
    }

    /**
     * Add sticky posts data.
     *
     * @param array $stickyPosts
     *
     * @return array
    */
    private function addStickyPostsData(array $stickyPosts = [])
    {
        if (empty($stickyPosts)) {
            return [];
        }

        foreach ($stickyPosts as &$post) {
            $post->isSticky    = true;
        }

        return $stickyPosts;
    }
}
