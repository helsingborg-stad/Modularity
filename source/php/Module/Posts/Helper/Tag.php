<?php

namespace Modularity\Module\Posts\Helper;

use \Municipio\Helper\Term as TermHelper;

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
        $termIcon = [];
      
        foreach ($tax as $key => $taxonomy) {
            $terms = wp_get_post_terms($postId, $taxonomy);

            if (count($terms) > 0) {
                foreach ($terms as $index => $term) {
                    if (empty($termIcon)) {
                        $icon = TermHelper::getTermIcon($term->term_id, $taxonomy);
                        
                        if (!empty($icon)/*  && !empty($icon['src']) && $icon['type'] == 'icon' */) {
                            $termIcon['icon'] = /* $icon['src'] */ 'all_out';
                            $termIcon['size'] = 'md';
                            $termIcon['backgroundColor'] = TermHelper::getTermColor($term->term_id, $taxonomy); 
                            $termIcon['color'] = 'white';
                        }
                    }
                    $tags[$term->name] = [];
                    $tags[$term->name]['label'] = $term->name;

                    if (class_exists('Municipio\Helper\Term')) {
                        $color = \Municipio\Helper\Term::getTermColor($term->term_id, $taxonomy);
                    } else {
                        $color = get_field('colour', $term);
                    }

                    $tags[$term->name]['color'] = $color ?? 'secondary';
                    $tags[$term->name]['href'] = empty($postLink) ? get_term_link($term->term_id) : "";
                }
            }
        }

        return ['tags' => $tags, 'termIcon' => $termIcon]; 
    }
}
