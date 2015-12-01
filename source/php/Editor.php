<?php

namespace Modularity;

class Editor extends \Modularity\Options
{
    public function __construct()
    {
        add_action('admin_head', array($this, 'registerTabs'));
        $this->registerEditorPage();
    }

    /**
     * Registers navigation tabs
     * @return void
     */
    public function registerTabs()
    {
        global $post;

        if (isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0) {
            $post = get_post($_GET['id']);
        }

        if ($post) {
            $tabs = new \Modularity\Editor\Tabs();
            $tabs->add(__('Content', 'modularity'), admin_url('post.php?post=' . $post->ID . '&action=edit'));
            $tabs->add(__('Modules', 'modularity'), admin_url('options.php?page=modularity-editor&id=' . $post->ID));
        }
    }

    /**
     * Register an option page for the editor
     * @return void
     */
    public function registerEditorPage()
    {
        $this->register(
            $pageTitle = __('Modularity editor'),
            $menuTitle = __('Editor'),
            $capability = 'edit_posts',
            $menuSlug = 'modularity-editor',
            $iconUrl = null,
            $position = 1,
            $parent = 'options.php'
        );
    }

    /**
     * Adds meta boxes to the options page
     * @return void
     */
    public function addMetaBoxes()
    {
        // Publish
        add_meta_box(
            'modularity-mb-publish',
            __('Save options', 'modularity'),
            function () {
                $templatePath = \Modularity\Helper\Wp::getTemplate('publish', 'options/partials');
                include $templatePath;
            },
            $this->screenHook,
            'side'
        );

        // Modules
        add_meta_box(
            'modularity-mb-modules',
            __('Enabled modules', 'modularity'),
            function () {
                $modularityOptions = get_option('modularity-options');
                $enabled = isset($modularityOptions['enabled-modules']) && is_array($modularityOptions['enabled-modules']) ? $modularityOptions['enabled-modules'] : array();
                $available = \Modularity\Module::$available;

                $modules = array();
                foreach ($enabled as $module) {
                    $modules[$module] = $available[$module];
                }

                $templatePath = \Modularity\Helper\Wp::getTemplate('enabled-modules', 'editor');
                include $templatePath;
            },
            $this->screenHook,
            'side'
        );
    }
}
