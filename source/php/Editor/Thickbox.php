<?php

namespace Modularity\Editor;

class Thickbox
{
    public function __construct()
    {
        if (\Modularity\Helper\Wp::isThickBox()) {
            $this->init();
        }
    }

    /**
     * Initializes the class if we're in a thickbox (checked in the __construct method)
     * @return void
     */
    public function init()
    {
        add_action('admin_head', array($this, 'addJsVariables'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue'));
    }

    /**
     * Adds required javascript variables to the thickbox page
     * @return void
     */
    public function addJsVariables()
    {
        global $current_screen;
        global $post;

        if (substr($current_screen->post_type, 0, 4) == 'mod-' && ($current_screen->action == 'add' || $current_screen->action == '')) {
            echo "
                <script>
                    var modularity_post_id = ". $post->ID . ";
                    var modularity_post_action = '" . $current_screen->action . "';
                </script>
            ";
        }
    }

    /**
     * Enqueue scripts and styles specific for the Thickbox content
     * @return void
     */
    public function enqueue()
    {
        // Script
        wp_register_script('modularity-thickbox', MODULARITY_URL . '/dist/js/modularity-editor-modal.' . \Modularity\App::$assetSuffix . '.js', false, '1.0.0', true);
        wp_enqueue_script('modularity-thickbox');

        // Style
        wp_register_style(
            'modularity-thickbox',
            MODULARITY_URL . '/dist/css/modularity-thickbox-edit.' . \Modularity\App::$assetSuffix . '.css',
            false,
            '1.0.0'
        );

        wp_enqueue_style('modularity-thickbox');
    }
}
