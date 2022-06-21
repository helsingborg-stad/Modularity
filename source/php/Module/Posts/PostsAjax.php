<?php

namespace Modularity\Module\Posts;

use Throwable;
use BladeComponentLibrary\Init as CompLibInitator;

class PostsAjax {
    /**
     * @var Posts
     */
    private $posts;

    public function __construct(Posts $posts) {
        $this->posts = $posts;

        add_action('wp_ajax_get_taxonomy_types_v2', array($this, 'getTaxonomyTypes'));
        add_action('wp_ajax_get_taxonomy_values_v2', array($this, 'getTaxonomyValues'));
        add_action('wp_ajax_get_sortable_meta_keys_v2', array($this, 'getSortableMetaKeys'));

        add_action('wp_ajax_mod_posts_load_more', array($this, 'loadMorePostsUsingAjax'));
        add_action('wp_ajax_nopriv_mod_posts_load_more', array($this, 'loadMorePostsUsingAjax'));

        add_action('wp_ajax_mod_posts_get_date_source', array($this, 'loadDateFieldAjax'));
    }

    public function loadDateFieldAjax()
    {
        $postType = $_POST['state'] ?? false;

        if (empty($postType)) {
            return false;
        }

        wp_send_json($this->posts->getDateSource($postType));
    }

        /**
     * Load more data with Ajax
     * @return json data
     */
    public function loadMorePostsUsingAjax()
    {
        if (!defined('DOING_AJAX') || !DOING_AJAX) {
            return false;
            die;
        }

        //Nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'mod-posts-load-more')) {
            die('Busted!');
        }

        $statusCodes = [
            'badRequest' => 400,
            'noContent' => 204,
            'success' => 200
        ];

        //Make sure required post data exists
        $requiredPostDataKeys = ['postsPerPage', 'offset', 'module', 'bladeTemplate'];
        foreach ($requiredPostDataKeys as $key) {
            if (!isset($_POST[$key])) {
                $error = 'Missing required $_POST[' . $key . '].';
                error_log($error);
                wp_send_json_error(['error' => $error], $statusCodes['badRequest']);
                die;
            }
        }

        //Append class propeties
        foreach ($_POST['module'] as $key => $value) {
            $this->$key = $value;
        }

        //Get posts
        $args = Posts::getPostArgs($this->ID);
        $args['posts_per_page'] = $_POST['postsPerPage'];
        $args['offset'] = $_POST['offset'];
        $this->data['posts'] = get_posts($args);

        $this->posts->getTemplateData(
            Posts::replaceDeprecatedTemplate(
                $this->data['posts_display_as']
            )
        ); //Include template controller data

        //No posts
        if (empty($this->data['posts'])) {
            wp_send_json(['Message' => 'Could not find more posts'], $statusCodes['noContent']);
            die;
        }

        $moduleView = MODULARITY_PATH . 'source/php/Module/Posts/views';
        $init = new CompLibInitator([$moduleView]);
        $blade = $init->getEngine();
        $posts = [];

        foreach ($this->data['posts'] as $post) {

            try {
                $posts[] = $blade->make($_POST['bladeTemplate'], array_merge(['post' => $post], $this->data))->render();
            } catch (Throwable $e) {
                echo '<pre style="border: 3px solid #f00; padding: 10px;">';
                echo '<strong>' . $e->getMessage() . '</strong>';
                echo '<hr style="background: #000; outline: none; border:none; display: block; height: 1px;"/>';
                echo $e->getTraceAsString();
                echo '</pre>';
            }
        }

        wp_send_json($posts);
    }

        /**
     * AJAX CALLBACK
     * Get availabel taxonomies for a post type
     * @return void
     */
    public function getTaxonomyTypes()
    {
        if (!isset($_POST['posttype']) || empty($_POST['posttype'])) {
            echo '0';
            die();
        }

        $post = $_POST['post'];

        $result = [
            'types' => get_object_taxonomies($_POST['posttype'], 'object'),
            'curr' => get_field('posts_taxonomy_type', $post)
        ];

        echo json_encode($result);
        die();
    }

        /**
     * AJAX CALLBACK
     * Gets a taxonomies available values
     * @return void
     */
    public function getTaxonomyValues()
    {
        if (!isset($_POST['tax']) || empty($_POST['tax'])) {
            echo '0';
            die();
        }

        $taxonomy = $_POST['tax'];
        $post = $_POST['post'];

        $result = [
            'tax' => get_terms($taxonomy, [
                'hide_empty' => false,
            ]),
            'curr' => get_field('posts_taxonomy_value', $post)
        ];

        echo json_encode($result);
        die();
    }
}
