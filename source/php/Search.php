<?php

namespace Modularity;

class Search
{
    public function __construct()
    {
        add_action('wp', array($this, 'moduleSearch'));

        add_filter('ep_pre_index_post', array($this, 'elasticPressPreIndex'));
        add_filter('ep_post_sync_args_post_prepare_meta', array($this, 'elasticPressPreIndex'));
    }

    /**
     * Add modules to post_content before indexing a post (makes modules searchable)
     * @param  WP_Post $post
     * @return void
     */
    public function elasticPressPreIndex($post)
    {
        if (!$post) {
            return;
        }

        if (is_array($post)) {
            $postId = $post['ID'];
        } else {
            $postId = $post->ID;
        }

        $modules = \Modularity\Editor::getPostModules($postId);
        $onlyModules = array();

        // Normalize modules array
        foreach ($modules as $sidebar => $item) {
            if (!isset($item['modules']) || count($item['modules']) === 0) {
                continue;
            }

            $onlyModules = array_merge($onlyModules, $item['modules']);
        }

        // Render modules and append to post content
        $rendered = "<br><br>";
        foreach ($onlyModules as $module) {
            if ($module->post_type === 'mod-wpwidget') {
                continue;
            }

            $markup = \Modularity\App::$display->outputModule($module, array('edit_module' => false), array(), false);
            $rendered .= $markup;
        }

        if (is_array($post)) {
            $post['post_content'] .= $rendered;
        } else {
            $post->post_content .= $rendered;
        }

        return $post;
    }

    /**
     * This method will switch module search results with posts the module is used in
     * @return void
     */
    public function moduleSearch()
    {
        global $wp_query;

        if (!$wp_query->is_search()) {
            return;
        }

        $searchResult = $wp_query->posts;

        foreach ($wp_query->posts as $key => $post) {
            // Continue if not a modularity post type
            if (substr($post->post_type, 0, 4) != 'mod-') {
                continue;
            }

            // Find module usage
            $usage = \Modularity\ModuleManager::getModuleUsage($post->ID);

            $usagePosts = array();
            foreach ($usage as $item) {
                $usagePosts[] = get_post($item->post_id);
            }

            $searchResult = $this->appendToArray($searchResult, $key, $usagePosts);
            unset($searchResult[$key]);
        }

        $wp_query->posts = array_values($searchResult);
    }

    /**
     * Appends items to array after specific key
     * @param  array  $array The array to append to
     * @param  string $key   The key to append after
     * @param  array  $new   The items to append
     * @return array         The new array
     */
    public function appendToArray(array $array, $key, array $new)
    {
        $keys = array_keys($array);
        $index = array_search($key, $keys);
        $pos = false === $index ? count($array) : $index + 1;

        return array_merge(array_slice($array, 0, $pos), $new, array_slice($array, $pos));
    }
}
