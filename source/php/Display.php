<?php

namespace Modularity;

class Display
{
    /**
     * Holds the current post's/page's modules
     * @var array
     */
    public $modules = array();

    public function __construct()
    {
        add_action('wp', array($this, 'init'));
    }

    /**
     * Initialize, get post's/page's modules and start output
     * @return void
     */
    public function init()
    {
        global $post;
        $this->modules = \Modularity\Editor::getPostModules($post->ID);

        add_action('dynamic_sidebar_before', array($this, 'output'));
    }

    public function output($sidebar)
    {
        global $post;

        // Get modules
        $modules = $this->modules[$sidebar];

        // Loop and output modules
        foreach ($modules as $module) {
            echo $module->post_title . '<br>';
        }
    }
}
