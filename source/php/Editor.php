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
            setup_postdata($post);
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
            'modularity-mb-editor-publish',
            __('Save modules', 'modularity'),
            function () {
                include MODULARITY_TEMPLATE_PATH . 'editor/modularity-publish.php';
            },
            $this->screenHook,
            'side'
        );

        // Modules
        add_meta_box(
            'modularity-mb-modules',
            __('Enabled modules', 'modularity'),
            function () {
                $enabled = \Modularity\Module::$enabled;
                $available = \Modularity\Module::$available;

                $modules = array();
                foreach ($enabled as $module) {
                    if (isset($available[$module])) {
                        $modules[$module] = $available[$module];
                    }
                }

                include MODULARITY_TEMPLATE_PATH . 'editor/modularity-enabled-modules.php';
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
        global $post;
        $options = get_post_meta($post->ID, 'modularity-sidebar-options', true)[$args['args']['sidebar']['id']];

        include MODULARITY_TEMPLATE_PATH . 'editor/modularity-sidebar-drop-area.php';
    }

    /**
     * Get modules added to a specific post
     * @param  integer $postId The post id
     * @return array           The modules on the post
     */
    public static function getPostModules($postId)
    {
        $modules = array();
        $retModules = array();

        // Get enabled modules
        $available = \Modularity\Module::$available;
        $enabled = \Modularity\Module::$enabled;

        // Get modules structure
        $moduleIds = array();
        $moduleSidebars = get_post_meta($postId, 'modularity-modules', true);
        foreach ($moduleSidebars as $sidebar) {
            $moduleIds = array_merge($moduleIds, $sidebar);
        }

        // Get module posts
        $posts = get_posts(array(
            'posts_per_page' => -1,
            'post_type' => $enabled,
            'include' => $moduleIds
        ));

        // Add module id's as keys in the array
        foreach ($posts as $module) {
            $modules[$module->ID] = $module;
        }

        // Create an strucural correct array with module post data
        //
        // array(
        //     'sidebar-id-1' => array(
        //          0 => Module #1,
        //          1 => Module #2
        //     ),
        //     'sidebar-id-2' => array(
        //          0 => Module #1,
        //          1 => Module #2
        //     )
        // )
        foreach ($moduleSidebars as $key => $sidebar) {
            $retModules[$key] = array(
                'modules' => array(),
                'options' => get_post_meta($postId, 'modularity-sidebar-options', true)
            );

            foreach ($sidebar as $moduleId) {
                $retModules[$key]['modules'][$moduleId] = $modules[$moduleId];

                // Get the post type name and append it to the module post data
                $retModules[$key]['modules'][$moduleId]->post_type_name = $available[$retModules[$key]['modules'][$moduleId]->post_type]['labels']['name'];
            }
        }

        return $retModules;
    }

    /**
     * Saves the selected modules
     * @return void
     */
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

        // Remove post meta if not set.
        if (isset($_POST['modularity_modules'])) {
            update_post_meta($postId, 'modularity-modules', $_POST['modularity_modules']);
        } else {
            delete_post_meta($postId, 'modularity-modules');
        }

        // Remove post meta if not set.
        if (isset($_POST['modularity_sidebar_options'])) {
            update_post_meta($postId, 'modularity-sidebar-options', $_POST['modularity_sidebar_options']);
        } else {
            delete_post_meta($postId, 'modularity-sidebar-options');
        }

        $this->notice(__('Modules saved', 'modularity'), ['updated']);
    }
}
