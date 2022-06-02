<?php

namespace Modularity\Module\Posts\TemplateController;

use Modularity\Module\Posts\Helper\Column as ColumnHelper;

class NewsTemplate
{
    protected $module;
    protected $args;

    public $data = array();

    public function __construct(\Modularity\Module\Posts\Posts $module, array $args, $data)
    {
        $this->module = $module;
        $this->args = $args;
        $this->data = $data;

        $fields = json_decode(json_encode(get_fields($this->module->ID)));

        $this->data['posts_columns'] = apply_filters('Modularity/Display/replaceGrid', $fields->posts_columns);
        $this->data['classes'] = apply_filters('Modularity/Module/Classes', array(), $module->post_type, $args);
        
        if($fields->posts_highlight_first ?? false) {
            $this->data['first_column'] = ColumnHelper::getFirstColumnSize($this->data['posts_columns']);
        }

        $this->preparePosts();
    }

    public function preparePosts()
    {

        /* Image size */
        $imageDimensions = array(400,300);

        switch ($this->data['posts_columns']) {
            case "o-grid-12@md":    //1-col
                $imageDimensions = array(1200,900);
                break;

            case "o-grid-6@md":    //2-col
                $imageDimensions = array(800,600);
                break;
        }

        
        foreach ($this->data['posts'] as $post) {

            /* Image */
            $image = null;
            if ($this->data['posts_data_source'] !== 'input') {
                $image = wp_get_attachment_image_src(
                    get_post_thumbnail_id($post->ID),
                    apply_filters(
                        'modularity/image/posts/index',
                        municipio_to_aspect_ratio('16:9', $imageDimensions),
                        $this->args
                    )
                );
            } else {
                if ($post->image) {
                    $image = wp_get_attachment_image_src(
                        $post->image->ID,
                        apply_filters(
                            'modularity/image/posts/index',
                            municipio_to_aspect_ratio('16:9', $imageDimensions),
                            $this->args
                        )
                    );
                }
            }

            // Image fetch
            $post->thumbnail = $image;

            // Get link for card, or tags 
            $post->link = $this->data['posts_data_source'] === 'input' ? $post->permalink : get_permalink($post->ID); 
            $post->tags = (new \Modularity\Module\Posts\Helper\Tag)->getTags($post->ID, $this->data['taxonomyDisplayFlat']);
            if(!empty($post->link) && is_array($post->tags) && !empty($post->tags)) {
                foreach($post->tags as $tagKey => $tag) {
                    $post->tags[$tagKey]['href'] = "";
                }
            }

            // Get excerpt
            $post->post_content = isset(get_extended($post->post_content)['main']) ? apply_filters('the_excerpt', wp_trim_words(wp_strip_all_tags(strip_shortcodes(get_extended($post->post_content)['main'])), 30, null)) : ''; 

            //Booleans for hiding/showing stuff
            $post->showDate     = (bool) in_array('date', $this->data['posts_fields']);
            $post->showExcerpt  = (bool) in_array('excerpt', $this->data['posts_fields']);
            $post->showTitle    = (bool) in_array('title', $this->data['posts_fields']);
            $post->showImage    = (bool) in_array('image', $this->data['posts_fields']);

        }
    }
}
