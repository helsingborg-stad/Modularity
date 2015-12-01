<?php

namespace Modularity;

class Editor
{
    public function __construct()
    {
        add_action('admin_head', array($this, 'registerTabs'));

        $this->registerEditorPage();
    }

    public function registerTabs()
    {
        global $post;

        if (isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0) {
            $post = get_post($_GET['id']);
        }

        $tabs = new \Modularity\Editor\Tabs();
        $tabs->add(__('Content', 'modularity'), admin_url('post.php?post=' . $post->ID . '&action=edit'));
        $tabs->add(__('Modules', 'modularity'), admin_url('options.php?page=modularity-editor&id=' . $post->ID));
    }

    public function registerEditorPage()
    {
        add_action('admin_menu', function () {
            add_submenu_page(
                'options.php',
                __('Modules', 'modularity'),
                __('Modules'),
                'edit_posts',
                'modularity-editor',
                array($this, 'modulesEditor')
            );
        });
    }

    public function modulesEditor()
    {
        echo "EDITORRRR!";
    }
}
