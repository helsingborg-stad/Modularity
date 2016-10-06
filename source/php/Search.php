<?php

namespace Modularity;

class Search
{
    public function __construct()
    {
        add_action('wp', array($this, 'moduleSearch'));
        add_action('ep_pre_index_post', array($this, 'elasticPressPreIndex'));
        add_action('pre_get_posts', array($this, 'preElasticSearch'));
    }

    /**
     * Limit searches to non module post types if using elasticsearch
     * @param  WP_Query $query
     * @return void
     */
    public function preElasticSearch($query)
    {
        // If not search or main query, return the default query
        if (!defined('EP_VERSION') || !is_search() || !$query->is_main_query()) {
            return;
        }

        $postTypes = array_filter(get_post_types(), function ($postType) {
            return substr($postType, 0, 4) !== 'mod-';
        });

        $query->query_vars['post_type'] = $postTypes;
    }

    /**
     * Add modules to post_content before indexing a post (makes modules searchable)
     * @param  WP_Post $post
     * @return void
     */
    public function elasticPressPreIndex($post = null)
    {
        if (!$post) {
            $post = get_post(1131);
        }

        $modules = \Modularity\Editor::getPostModules($post->ID);
        $onlyModules = array();

        // Normalize modules array
        foreach ($modules as $sidebar => $item) {
            if (!isset($item['modules']) || count($item['modules']) === 0) {
                continue;
            }

            $onlyModules = array_merge($onlyModules, $item['modules']);
        }

        // Render modules and append to post content
        $rendered = '';
        foreach ($onlyModules as $module) {
            $markup = \Modularity\App::$display->outputModule($module, array(), array(), false);
            $rendered .= $markup;
        }

        $post->post_content .= $rendered;
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
            $usage = \Modularity\Module::getModuleUsage($post->ID);

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
