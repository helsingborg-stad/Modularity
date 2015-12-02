<?php

namespace Modularity;

class Ajax
{
    public function __construct()
    {
        add_action('wp_ajax_get_post', array($this, 'getPost'));
    }

    /**
     * Get a post with ajax
     * @return string    JSON encoded object
     */
    public function getPost()
    {
        if (!isset($_POST['id']) || empty($_POST['id']) || is_null($_POST['id'])) {
            echo 'false';
        }

        echo json_encode(get_post($_POST['id']));
        wp_die();
    }
}
