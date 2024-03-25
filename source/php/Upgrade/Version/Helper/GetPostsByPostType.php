<?php

namespace Modularity\Upgrade\Version\Helper;

class GetPostsByPostType {
    public static function getPostsByPostType(string $postType) {
        $args = array(
            'post_type' => $postType,
            'numberposts' => -1
        );
        
        $posts = get_posts($args);

        return $posts;
    }
}