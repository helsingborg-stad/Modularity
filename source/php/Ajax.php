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

        $post_modules = \Modularity\Editor::getPostModules($_POST['id']);
        foreach ($post_modules as $post_module) {
            if (!empty($post_module['modules'])) {
                foreach ($post_module['modules'] as &$module) {
                    $incompability = apply_filters('Modularity/Editor/SidebarIncompability', array(), $module->post_type);
                    $module->sidebar_incompability = (!empty($incompability['sidebar_incompability'])) ? $incompability['sidebar_incompability'] : array();
                }
            }
        }

        echo json_encode($post_modules);
        wp_die();
    }
}
