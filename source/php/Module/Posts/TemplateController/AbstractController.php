<?php

namespace Modularity\Module\Posts\TemplateController;

class AbstractController {
    protected function anyPostHasImage(array $posts) {
        foreach ($posts as $post) {
            if (!empty($post->thumbnail) && !isset($post->thumbnail['src'])) {
                return true;
            }
        }
        return false;
    }
}