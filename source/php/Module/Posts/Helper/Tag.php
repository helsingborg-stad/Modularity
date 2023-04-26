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
    public static function getTags($postId, $tax, $postLink)
    {

        if (!$tax || !$postId || !function_exists('get_field')) {
            return null;
        }

        $tags = [];
        $termIcon = [];
        $termColor = false;

        foreach ($tax as $key => $taxonomy) {
            $terms = wp_get_post_terms($postId, $taxonomy);

            if (count($terms) > 0) {
                foreach ($terms as $index => $term) {
                    if (class_exists('Municipio\Helper\Term')) {
                        if (empty($termIcon)) {
                            $icon = \Municipio\Helper\Term::getTermIcon($term, $taxonomy);
                            $color = \Municipio\Helper\Term::getTermColor($term, $taxonomy);

                            if (!empty($icon) && !empty($icon['src']) && $icon['type'] == 'icon') {
                                $termIcon['icon'] = $icon['src'];
                                $termIcon['size'] = 'md';
                                $termIcon['color'] = 'white';
                                $termIcon['backgroundColor'] = $color;
                            }

                            if (!empty($color)) {
                                $termColor = $color;
                            }
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

        if (empty($termIcon) && !empty($termColor)) {
            $termIcon['backgroundColor'] = $color;
        }

        return ['tags' => $tags, 'termIcon' => $termIcon];
    }
}
