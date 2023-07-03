<?php

namespace Modularity\Module\Posts\TemplateController;

use Modularity\Module\Posts\Helper\Tag as TagHelper;

class AbstractController
{
    protected $hookName = 'index';

    protected function anyPostHasImage($posts)
    {
        if (!is_array($posts)) {
            return false;
        }

        foreach ($posts as $post) {
            if (!empty($post->thumbnail) && !isset($post->thumbnail['src'])) {
                return true;
            }
        }
        return false;
    }

    protected function getImageDimensions($postColumns, $default = [400, 300])
    {
        /* Image size */
        $imageDimensions = $default;

        switch ($postColumns) {
            case "o-grid-12@md":    //1-col
                $imageDimensions = [1200, 900];
                break;
            case "o-grid-6@md":    //2-col
                $imageDimensions = [900, 675];
                break;
        }

        return $imageDimensions;
    }

    public function preparePosts()
    {
        foreach ($this->data['posts'] as $post) {
            $this->setPostFlags($post);
        }
    }

    /**
     * Booleans for hiding/showing stuff
     */
    public function setPostFlags(&$post)
    {
        // Booleans for hiding/showing stuff
        $post->showExcerpt  = in_array('excerpt', $this->data['posts_fields']);
        $post->showTitle    = in_array('title', $this->data['posts_fields']);
        $post->showImage    = in_array('image', $this->data['posts_fields']);
        $post->showDate     = in_array('date', $this->data['posts_fields']);
        $post->attributeList = !empty($post->attributeList) ? $post->attributeList : [];

        $post->purpose = false;
        if (!empty($post->postType)) {
            $post->purpose = \Modularity\Module\Posts\Helper\Purpose::getPurpose($post->postType);
        }

        $location = get_field('location', $post->id) ?? [];
        if (!empty($location)) {
            $post->location = $location;
            $post->attributeList['data-js-map-location'] = json_encode(\Municipio\Helper\Location::createMapMarker($post));
        }

        if ('event' == $post->purpose) {
            $post->showDate = true;
            $eventOccasions = get_post_meta($post->ID, 'occasions_complete', true);
            if (!empty($eventOccasions)) {
                $post->post_date = $eventOccasions[0]['start_date'];
                $post->dateBadge = true;
            } else {
                $post->post_date = false;
            }
        } else {
            if ($post->showDate && empty($post->dateBadge)) {
                $post->post_date = date_i18n(\Modularity\Helper\Date::getDateFormat('date-time'), strtotime($post->post_date));
            }
        }

        if (empty($post->showDate)) {
            $post->post_date = false;
        }
    }
}
