<?php

namespace Modularity\Module\Posts\TemplateController;

class AbstractController
{
    protected $hookName = 'index';

    protected function anyPostHasImage($posts)
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

    public function preparePosts()
    {
        $this->data['contentType'] = \Modularity\Module\Posts\Helper\ContentType::getContentType(
            $this->data['posts_data_post_type'] ?? ''
        );

        if(!empty($this->data['posts']) && is_array($this->data['posts'])) {
            foreach ($this->data['posts'] as &$post) {
                $post = array_filter((array) $post, function($value) {
                    return !empty($value) || $value === false;
                });
                $post = array_merge($this->getDefaultValuesForPosts(), $post);
                $this->setPostFlags($post);
            }
        }
        
    }

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
        ];
    }

    /**
     * Booleans for hiding/showing stuff
     */
    public function setPostFlags(&$post)
    {
        if (empty($post)) return;
        $post = (object) $post;
        // Booleans for hiding/showing stuff
        $post->excerptShort         = in_array('excerpt', $this->data['posts_fields']) ? $post->excerptShort : false;
        $post->postTitle            = in_array('title', $this->data['posts_fields']) ? $post->postTitle : false;
        $post->images               = in_array('image', $this->data['posts_fields']) ? $post->images : false;
        $post->postDateFormatted    = in_array('date', $this->data['posts_fields']) ? $post->postDateFormatted : false;
        $post->attributeList        = !empty($post->attributeList) ? $post->attributeList : [];

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
    public static function arrayToObject($array)
    {
        if(!is_array($array)) {
            return $array;
        }

        return json_decode(json_encode($array)); 
    }
}
