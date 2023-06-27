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
        $imageDimensions = $this->getImageDimensions($this->data['posts_columns']);

        $amount = $this->getTruncateAmount($this->data['posts_display_as']);

        $this->data['purpose'] = \Modularity\Module\Posts\Helper\Purpose::getPurpose($this->data['posts_data_post_type'] ?? '');

        foreach ($this->data['posts'] as $post) {
            $image = $this->getPostImage($post, $this->data['posts_data_source'], $imageDimensions, '16:9');
            // Image fetch
            $post->thumbnail = apply_filters('Modularity/Module/Posts/thumbnail', $image);

            // Get link for card, or tags
            $post->link = $this->data['posts_data_source'] === 'input' ? $post->permalink : get_permalink($post->ID);

            $tagData = TagHelper::getTags(
                $post->ID,
                $this->data['taxonomyDisplayFlat'],
                $post->link
            );

            $post->tags = $tagData['tags'] ?? false;
            $post->termIcon = $tagData['termIcon'] ?? false;

            $post->post_content = !empty($post->post_excerpt) ? $post->post_excerpt : $this->truncateExcerpt($post->post_content, $amount);

            $this->setPostFlags($post);
        }
    }

    public function getTruncateAmount($displayAs = 'default')
    {
        switch ($displayAs) {
            case 'collection':
                return 10;
                break;
            case 'segment':
                return 15;
                break;
            default:
                return 25;
                break;
        }
    }

    public function truncateExcerpt($content, $amount = 25)
    {
        if (empty(get_extended($content)['main'])) {
            return;
        }
        return apply_filters('the_excerpt', wp_trim_words(wp_strip_all_tags(strip_shortcodes(get_extended($content)['main'])), $amount, '...'));
    }

    /**
     * Booleans for hiding/showing stuff
     */
    public function setPostFlags(&$post)
    {
        //Booleans for hiding/showing stuff
        $post->showExcerpt  = in_array('excerpt', $this->data['posts_fields']);
        $post->showTitle    = in_array('title', $this->data['posts_fields']);
        $post->showImage    = in_array('image', $this->data['posts_fields']);
        $post->showDate     = in_array('date', $this->data['posts_fields']);
        $post->attributeList = !empty($post->attributeList) ? $post->attributeList : [];

        $post->purpose = false;
        if (!empty($post->post_type)) {
            $post->purpose = \Modularity\Module\Posts\Helper\Purpose::getPurpose($post->post_type);
        }

        $location = get_field('location', $post->ID) ?? [];
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

    /**
     * Prepare a date to show in view
     *
     * @param   array $posts    The posts
     * @return  array           The posts - with archive date
     */
    public function getDate($post, $dateSource = 'post_date')
    {

        if (!$dateSource) {
            return false;
        }

        $isMetaKey = in_array($dateSource, ['post_date', 'post_modified']) ? false : true;

        if ($isMetaKey == true) {
            $postDate = get_post_meta($post->ID, $dateSource, true);
        } else {
            $postDate = $post->{$dateSource};
        }

        if (!is_string($postDate) || empty($postDate) || strtotime($postDate) === false) {
            $postDate = false;
        }

        return $postDate;
    }

    protected function getPostImage($post, $postsDataSource, array $imageDimensions, $ratio)
    {
        $image = null;

        if ($postsDataSource !== 'input') {
            $image = $this->getAttachmentUrl(get_post_thumbnail_id($post->ID), $imageDimensions, $ratio);
        } elseif ($post->image) {
            $image = $this->getAttachmentUrl($post->image->ID, $imageDimensions, $ratio);
        }

        return $image;
    }

    public function getAttachmentUrl($attachmentId, array $dimension = [1200, 900], string $ratio = '16:9')
    {
        return wp_get_attachment_image_src(
            $attachmentId,
            apply_filters(
                'modularity/image/posts/' . $this->hookName,
                municipio_to_aspect_ratio($ratio, $dimension),
                $this->args,
                $this->module
            )
        );
    }
}
