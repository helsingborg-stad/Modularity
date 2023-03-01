<?php

namespace Modularity\Module\Posts\Helper;

/**
 * Class Tag
 * @package Modularity\Module\Posts\Helper
 */
class Tag
{
    /**
     * @param $postId
     * @param $tax
     * @return array|null
     */
    public function getTags($postId, $tax, $postLink)
    {

        if (!$tax || !$postId) {
            return null;
        }

        $tags = [];
  
        foreach ($tax as $key => $taxonomy) {
            $terms = wp_get_post_terms($postId, $taxonomy);

            if (count($terms) > 0)  {
                foreach ($terms as $index => $term) {
                    $tags[$term->name] = [];
                    $tags[$term->name]['label'] = $term->name;
                    $tags[$term->name]['color'] = 'secondary';
                    $tags[$term->name]['href'] = empty($postLink) ? get_term_link($term->term_id) : "";
                }

                
            }
             
        }
        
        return $tags; 
    }
}

