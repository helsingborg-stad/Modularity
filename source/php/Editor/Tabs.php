<?php

namespace Modularity\Editor;

class Tabs
{
    protected $tabs = array();

    public function __construct()
    {
        add_action('in_admin_header', array($this, 'output'));
    }

    public function output($activeIndex = 0)
    {
        if (!$this->shouldOutput()) {
            return false;
        }

        echo '<h2 class="nav-tab-wrapper">';

        $index = 0;
        foreach ($this->tabs as $tab => $url) {
            if ($activeIndex == $index) {
                echo '<a href="' . $url . '" class="nav-tab nav-tab-active">' . $tab . '</a>';
            } else {
                echo '<a href="' . $url . '" class="nav-tab">' . $tab . '</a>';
            }

            $index++;
        }

        echo '</h2>';
    }

    public function add($title, $url)
    {
        $this->tabs[$title] = $url;
        return true;
    }

    protected function shouldOutput()
    {
        global $current_screen;

        $options = get_option('modularity-options');
        $enabled = isset($options['enabled-post-types']) && is_array($options['enabled-post-types']) ? $options['enabled-post-types'] : array();

        $validPostType = in_array($current_screen->id, $enabled);

        $action = $current_screen->action;
        if (empty($action)) {
            $action = (isset($_GET['action']) && !empty($_GET['action'])) ? $_GET['action'] : null;
        }

        $validAction = in_array($action, array(
            'add',
            'edit'
        ));

        return $validPostType && $validAction;
    }
}
