<?php

namespace Modularity;

class Editor
{
    public function __construct()
    {
        $tabs = new \Modularity\Editor\Tabs();
        $tabs->add(__('Content', 'modularity'), 'admin.php');
        $tabs->add(__('Modules', 'modularity'), 'layout.php');

        $this->modulesEditorPage();
    }

    public function modulesEditorPage()
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
