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
    public function getTags($postId, $tax)
    {

        if (!$tax || !$postId) {
            return null;
        }

        $tags = [];
        foreach ($tax as $key => $taxonomy) {
            $terms = wp_get_post_terms($postId, $taxonomy);
        

            if (count($terms) > 0)  {
                foreach ($terms as $index => $term) {
                    $tags[$index] = [];
                    $tags[$index]['label'] = $term->name;
                    $tags[$index]['color'] = 'secondary';
                    $tags[$index]['href'] = get_term_link($term->term_id);
                }

                
            }
             
        }
        
                echo "<pre>";
                //var_dump(wp_get_post_terms( $postId, 'post_tag' ));
                var_dump($tags);
                echo "</pre>";
        return $tags; 
    }
}

