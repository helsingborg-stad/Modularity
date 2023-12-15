<?php

namespace Modularity\Module\Posts\TemplateController;

use Modularity\Module\Posts\Helper\Column as ColumnHelper;

class AbstractController
{
    protected $hookName = 'index';

    /**
     * Check if any post in the given array has an image.
     *
     * @param array $posts An array of post objects.
     *
     * @return bool Returns true if any post has an image, false otherwise.
     */
    protected function anyPostHasImage(array $posts)
    {
        if (!is_array($posts)) {
            return false;
        }

        foreach ($posts as $post) {
            if (!empty($post->images) && !isset($post->images['thumbnail16:9']['src'])) {
                return true;
            }
        }
        return false;
    }

    /**
     * Prepare posts data by setting default values and post flags.
     * 
     * Setting default values for posts variables.
     */
    public function preparePosts()
    {
        $this->data['contentType'] = \Modularity\Module\Posts\Helper\ContentType::getContentType(
            $this->data['posts_data_post_type'] ?? ''
        );
        if(!empty($this->data['posts']) && is_array($this->data['posts'])) {
            foreach ($this->data['posts'] as $index => &$post) {
                $this->setPostFlags($post, $index);
                $post = array_filter((array) $post, function($value) {
                    return !empty($value) || $value === false;
                });

                $post = (object) array_merge($this->getDefaultValuesForPosts(), $post);
            }
        }
    }

    /**
     * Get default values for keys in the post object.
     *
     * @return array An array of default values for post object keys.
     */
    public function getDefaultValuesForPosts() {
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
            'imagePosition' => true
        ];
    }

    /**
     * Set boolean flags for hiding/showing specific post details.
     *
     * @param object $post  The post object.
     * @param int|false  $index The index of the post.
     */
    public function setPostFlags(object &$post, $index = false)
    {
        if (empty($post)) return;
        // Booleans for hiding/showing stuff
        $post->excerptShort         = in_array('excerpt', $this->data['posts_fields']) ? $post->excerptShort : false;
        $post->postTitle            = in_array('title', $this->data['posts_fields']) ? $post->postTitle : false;
        $post->image                = in_array('image', $this->data['posts_fields']) ? $this->getImageBasedOnRatio($post->images, $index) : [];
        $post->postDateFormatted    = in_array('date', $this->data['posts_fields']) ? $post->postDateFormatted : false;
        $post->attributeList        = !empty($post->attributeList) ? $post->attributeList : [];
        $post->hasPlaceholderImage  = in_array('image', $this->data['posts_fields']) && empty($post->images['thumbnail16:9']['src']) ? true : false;
        $post->readingTime          = in_array('reading_time', $this->data['posts_fields']) ? $post->readingTime : false;
        
        if (!empty($post->image) && is_array($post->image)) {
            $post->image['backgroundColor'] = 'secondary';
        }

        if (isset($post->contentType) && 'event' == $post->contentType) {
            $eventOccasions = get_post_meta($post->id, 'occasions_complete', true);
            if (!empty($eventOccasions)) {
                $post->postDateFormatted = $eventOccasions[0]['start_date'];
                $post->dateBadge = true;
            } else {
                $post->postDateFormatted = false;
            }
        } 
    }

    /**
     * Get the image based on ratio and index.
     *
     * @param array $images An array of post images.
     * @param mixed $index  The index of the post.
     *
     * @return mixed|null Returns the image based on conditions, or null if not found.
     */
    public function getImageBasedOnRatio(array $images, $index) {
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
     * Prepare and set data fields for posts display.
     *
     * @param object $fields An object containing post fields data.
     */
    public function prepareFields(object $fields) {
        $this->data['posts_columns'] = apply_filters('Modularity/Display/replaceGrid', $fields->posts_columns);
        $this->data['ratio'] = $fields->ratio ?? '16:9';
        $this->data['highlight_first_column_as'] = $fields->posts_display_highlighted_as ?? 'block';
        $this->data['highlight_first_column'] = !empty($fields->posts_highlight_first) ? 
        ColumnHelper::getFirstColumnSize($this->data['posts_columns']) : false;
        $this->data['imagePosition'] = $fields->image_position ?? false;

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
    public static function arrayToObject(array $array)
    {
        if(!is_array($array)) {
            return $array;
        }

        return json_decode(json_encode($array)); 
    }
}
