<?php

namespace Modularity\Module\Posts\TemplateController;

class HorizontalTemplate extends AbstractController
{
    protected $module;
    protected $args;

    public $data = array();

    public function __construct(\Modularity\Module\Posts\Posts $module, array $args, $data)
    {
        $this->hookName = 'items';
        $this->module = $module;
        $this->args = $args;
        $this->data = $data;

        $this->data['loadMorePostsAttributes'] = \Modularity\Module\Posts\Posts::loadMoreButtonAttributes($module, '.js-mod-posts-' . $module->ID, 'partials.post.post-horizontal', 6);

        $this->data['loadMoreButtonText'] = __('Load more', 'modularity');
        $this->mapPosts();
    }

    /**
     * mapPosts()
     *
     * @return void
     */
    public function mapPosts()
    {
        if (!isset($this->data['posts']) || empty($this->data['posts'])) {
            return;
        }

        $posts = array();
        $imageDimension = array(1200, 675);
        $imageRatio = '16:9';
        $i = 0;

        foreach ($this->data['posts'] as $post) {
            if ($i == 0 && !get_field('posts_highlight', $this->data['ID']) || $i > 0) {
                $imageRatio = '4:3';
                $imageDimension = array(900, 675);
            }


            $post->image = $this->getFeaturedImageSource($post, $imageDimension, $imageRatio);
            $post->terms = $this->getTerms($post);

            $post->humanReadableTime = (get_field('posts_date_format', $this->data['ID']) == 'readable') ? $this->readableTimeStamp(strtotime($post->post_date)) . ' ' . __('ago', 'modularity') : false;

            $posts[] = $post;

            $i++;
        }

        $this->data['posts'] = $posts;
    }

    /**
     * getTerms()
     *
     * Returns terms
     * @param class $post post object
     * @return string
     */
    public function getTerms($post)
    {
        $taxonomies = get_field('taxonomy_display', $this->data['ID']);

        if (empty($taxonomies)) {
            return [];
        }


        $terms = array();
        if (!empty($taxonomies)) {
            foreach ($taxonomies as $taxonomy) {
                $terms = array_merge($terms, get_the_terms($post->ID, $taxonomy));
            }
        }

        return $terms;
    }

    /**
     * getFeaturedImageSource()
     *
     * Returns the featured image url of a post or placeholder
     * @param class $post post object
     * @param array $imageDimension
     * @param string $imageRatio
     * @return string
     */
    public function getFeaturedImageSource($post, $imageDimension, $imageRatio)
    {
        $image = null;

        //Featured image (if exists)
        if (!empty(get_post_thumbnail_id($post->ID))) {
            $image = $this->getAttachmentUrl(get_post_thumbnail_id($post->ID), $imageDimension, $imageRatio)[0];
        }

        //User defined placeholder (if exists within the module)
        if (!$image && get_field('posts_placeholder', $this->data['ID'])) {
            $image = $this->getAttachmentUrl(get_field('posts_placeholder', $this->data['ID'])['ID'], $imageDimension, $imageRatio)[0];
        }

        return $image;
    }

    /**
     * Format a unix timestamp to a human friendly format
     * @param string $unixtime The timestamp in unixtime format
     * @return string Humean readable time
     */
    public function readableTimeStamp($unixtime): string
    {
        return human_time_diff($unixtime, current_time('timestamp'));
    }
}
