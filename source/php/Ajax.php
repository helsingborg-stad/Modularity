<?php

namespace Modularity;

class Ajax
{
    public function __construct()
    {
        add_action('wp_ajax_get_post', array($this, 'getPost'));
        add_action('wp_ajax_get_post_modules', array($this, 'getPostModules'));
    }

    /**
     * Get a post with ajax
     * @return string    JSON encoded object
     */
    public function getPost()
    {
        if (!isset($_POST['id']) || empty($_POST['id']) || is_null($_POST['id'])) {
            echo 'false';
            wp_die();
        }

        echo json_encode(get_post($_POST['id']));
        wp_die();
    }

    /**
     * Gets modules that's saved to a post/page
     * @return string JSON ecoded object
     */
    public function getPostModules()
    {
        if (!isset($_POST['id']) || empty($_POST['id']) || is_null($_POST['id'])) {
            echo 'false';
            wp_die();
        }

        echo json_encode(get_post_meta($_POST['id'], 'modularity-modules', true));
        wp_die();
    }
}
