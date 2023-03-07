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
        if (!$this->requestHasRequieredParams()) {
            $this->abortRequest();
        }

        if( ($post = get_post($_POST['id'])) === null ) {
            $this->abortRequest();
        }

        echo json_encode($post);
        wp_die();
    }

    /**
     * Gets modules that's saved to a post/page
     * @return string JSON ecoded object
     */
    public function getPostModules()
    {
        if (!$this->requestHasRequieredParams()) {
            $this->abortRequest();
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

    private function requestHasRequieredParams() {
        return isset($_POST['id']) && !empty($_POST['id']) && !is_null($_POST['id']);
    }

    private function abortRequest() {
        echo 'false';
        wp_die();
    }
}
