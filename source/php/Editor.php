<?php

namespace Modularity;

class Editor extends \Modularity\Options
{
    public function __construct()
    {
        global $post;

        // Prepare Thickbox
        new \Modularity\Editor\Thickbox();

        if (isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0) {
            $post = get_post($_GET['id']);
        }

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

        $this->addSidebarsMetaBoxes();
    }

    /**
     * Loops registered sidebars and creates metaboxes for them
     */
    public function addSidebarsMetaBoxes()
    {
        global $wp_registered_sidebars;

        foreach ($wp_registered_sidebars as $sidebar) {
            $this->sidebarMetaBox($sidebar);
        }
    }

    /**
     * Create metabox for sidebars
     * @param  array $sidebar The sidebar args
     * @return void
     */
    public function sidebarMetaBox($sidebar)
    {
        add_meta_box(
            'modularity-mb-' . $sidebar['id'],
            $sidebar['name'],
            array($this, 'metaBoxSidebar'),
            $this->screenHook,
            'normal',
            'low',
            array('sidebar' => $sidebar)
        );
    }

    /**
     * The content for sidebar metabox
     * @param  array $post
     * @param  array $args The metabox args
     * @return void
     */
    public function metaBoxSidebar($post, $args)
    {
        $templatePath = \Modularity\Helper\Wp::getTemplate('sidebar-drop-area', 'editor');
        include $templatePath;
    }

    public function save()
    {
        if (!$this->isValidPostSave()) {
            return;
        }

        // Check if post id is valid
        if (!isset($_GET['id']) || empty($_GET['id']) || !is_numeric($_GET['id'])) {
            return trigger_error('Invalid post id. Please contact system administrator.');
        }

        $postId = $_GET['id'];
        update_post_meta($postId, 'modularity-modules', $_POST['modularity_modules']);

        $this->notice(__('Modules saved', 'modularity'), ['updated']);
    }
}
