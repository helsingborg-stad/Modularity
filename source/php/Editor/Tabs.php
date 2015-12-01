<?php

namespace Modularity\Editor;

class Tabs
{
    protected $tabs = array();

    public function __construct()
    {
        add_action('in_admin_header', array($this, 'output'));
    }

    /**
     * Outputs the tabbar html
     * @param  integer $activeIndex Current active tab index
     * @return void
     */
    public function output()
    {
        if (!$this->shouldOutput()) {
            return false;
        }

        echo '<h2 class="nav-tab-wrapper" id="modularity-tabs">';

        foreach ($this->tabs as $tab => $url) {
            if (strpos($url, $_SERVER['REQUEST_URI']) !== false || (strpos($_SERVER['REQUEST_URI'], 'post-new.php') !== false && strpos($url, 'post.php') !== false)) {
                echo '<a href="' . $url . '" class="nav-tab nav-tab-active">' . $tab . '</a>';
            } else {
                echo '<a href="' . $url . '" class="nav-tab">' . $tab . '</a>';
            }
        }

        echo '</h2>';
    }

    /**
     * Add a tab to the tabbar
     * @param string $title The tab title text
     * @param string $url   The target url
     */
    public function add($title, $url)
    {
        $this->tabs[$title] = $url;
        return true;
    }

    /**
     * Check if tabs should be outputted
     * @return boolean
     */
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

        return ($validPostType && $validAction) || $current_screen->id == 'admin_page_modularity-editor';
    }
}
