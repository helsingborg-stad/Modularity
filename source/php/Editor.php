<?php

namespace Modularity;

class Editor extends \Modularity\Options
{
    public function __construct()
    {
        global $post;

        // Prepare Thickbox
        new \Modularity\Editor\Thickbox();

        if (isset($_GET['id'])) {
            if (is_numeric($_GET['id']) && $_GET['id'] > 0) {
                $post = get_post($_GET['id']);
                setup_postdata($post);

                add_action('admin_bar_menu', function () use ($post) {
                    global $wp_admin_bar;
                    $wp_admin_bar->add_node(array(
                        'id' => 'view_page',
                        'title' => __('View Page'),
                        'href' => get_permalink($post->ID),
                        'meta' => array(
                            'target' => '_blank'
                        )
                    ));
                }, 1050);
            } else {
                global $archive;
                $archive = $_GET['id'];
            }
        }

        add_action('admin_head', array($this, 'registerTabs'));
        add_action('wp_ajax_save_modules', array($this, 'save'));

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

        $template = $this->getPostTemplate();
        $options = get_option('modularity-options');
        $sidebars = null;

        $activeAreas = isset($options['enabled-areas'][$template]) ? $options['enabled-areas'][$template] : array();

        if (count($activeAreas) === 0) {
            add_meta_box(
                'no-sidebars',
                __('No active sidebar areas', 'modularity'),
                function () {
                    echo '<p>' . __('There\'s no active sidebars. Please activate sidebar areas in the Modularity Options to add modules.', 'modularity') . '</p>';
                },
                $this->screenHook,
                'normal',
                'low',
                null
            );
            return;
        }

        foreach ($activeAreas as $area) {
            if (isset($wp_registered_sidebars[$area])) {
                $sidebars[$area] = $wp_registered_sidebars[$area];
            }
        }

        if (is_array($sidebars)) {
            foreach ($sidebars as $sidebar) {
                $this->sidebarMetaBox($sidebar);
            }
        }
    }

    /**
     * Gets the post template of the current editor page
     * @return string Template slug
     */
    public function getPostTemplate()
    {
        if ($this->isArchive()) {
            global $archive;
            return $archive;
        }

        global $post;
        $template = get_page_template_slug($post->ID);

        if (!$template) {
            $template = $this->detectCoreTemplate();
        }

        return $template;
    }

    /**
     * Detects core templates
     * @return string Template
     */
    public function detectCoreTemplate()
    {
        global $post;

        switch ($post->post_type) {
            case 'post':
                return 'single';
                break;

            case 'page':
                return 'page';
                break;

            default:
                return 'single-' . $post->post_type;
                break;
        }

        return 'index';
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

        $options = null;

        if ($this->isArchive()) {
            global $archive;
            $options = get_option('modularity_' . $archive . '_sidebar-options');
        } else {
            $options = get_post_meta($post->ID, 'modularity-sidebar-options', true);
        }

        if (isset($options[$args['args']['sidebar']['id']])) {
            $options = $options[$args['args']['sidebar']['id']];
        } else {
            $options = null;
        }

        $optionsBeforeModule = array(
            // classes => title
            'grid-lg-1' => 1,
            'grid-lg-2' => 2,
            'grid-lg-3' => 3,
            'grid-lg-4' => 4,
            'grid-lg-5' => 5,
            'grid-lg-6' => 6,
            'grid-lg-7' => 7,
            'grid-lg-8' => 8,
            'grid-lg-9' => 9,
            'grid-lg-10' => 10,
            'grid-lg-11' => 11,
            'grid-lg-12' => 12
        );
        $optionsBeforeModule = apply_filters('Modularity/before_module_options', $optionsBeforeModule);

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
        $moduleSidebars = null;

        if (is_string($postId)) {
            $moduleSidebars = get_option('modularity_' . $postId . '_modules');
        } else {
            $moduleSidebars = get_post_meta($postId, 'modularity-modules', true);
        }

       //$moduleSidebars = get_post_meta($postId, 'modularity-modules', true);

        if (!empty($moduleSidebars)) {
            foreach ($moduleSidebars as $sidebar) {
                foreach ($sidebar as $module) {
                    $moduleIds[] = $module['postid'];
                }
            }
        }

        // Get module posts
        $modulesPosts = get_posts(array(
            'posts_per_page' => -1,
            'post_type' => $enabled,
            'include' => $moduleIds
        ));

        // Add module id's as keys in the array
        if (!empty($modulesPosts)) {
            foreach ($modulesPosts as $module) {
                $modules[$module->ID] = $module;
            }
        }

        // Create an strucural correct array with module post data
        if (!empty($moduleSidebars)) {
            foreach ($moduleSidebars as $key => $sidebar) {
                $retModules[$key] = array(
                    'modules' => array(),
                    'options' => get_post_meta($postId, 'modularity-sidebar-options', true)
                );

                foreach ($sidebar as $moduleUid => $module) {
                    $moduleId = $module['postid'];

                    if (!isset($modules[$moduleId])) {
                        continue;
                    }

                    $retModules[$key]['modules'][$moduleId] = $modules[$moduleId];

                    // Get the post type name and append it to the module post data
                    $retModules[$key]['modules'][$moduleId]->post_type_name = $available[$retModules[$key]['modules'][$moduleId]->post_type]['labels']['name'];
                    $retModules[$key]['modules'][$moduleId]->meta = get_post_custom($moduleId);
                    $retModules[$key]['modules'][$moduleId]->hidden = $module['hidden'];
                }
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
        if (!isset($_REQUEST['id']) || empty($_REQUEST['id'])) {
            return trigger_error('Invalid post id. Please contact system administrator.');
        }

        if ($this->isArchive()) {
            $this->saveArchive();
        } else {
            $this->savePost();
        }

        // If this is an ajax post, return "success" as plain text
        if (defined('DOING_AJAX') && DOING_AJAX) {
            echo "success";
            wp_die();
        }

        $this->notice(__('Modules saved', 'modularity'), ['updated']);
    }

    // get_option('modularity_' . $archive . '_sidebar-options');

    public function savePost()
    {
        $postId = $_REQUEST['id'];

        // Save/remove modules
        if (isset($_POST['modularity_modules'])) {
            update_post_meta($postId, 'modularity-modules', $_POST['modularity_modules']);
        } else {
            delete_post_meta($postId, 'modularity-modules');
        }

        // Save/remove sidebar options
        if (isset($_POST['modularity_sidebar_options'])) {
            update_post_meta($postId, 'modularity-sidebar-options', $_POST['modularity_sidebar_options']);
        } else {
            delete_post_meta($postId, 'modularity-sidebar-options');
        }

        return true;
    }

    public function saveArchive()
    {
        global $archive;

        // Save/remove modules
        $optionName = 'modularity_' . $archive . '_modules';

        if (isset($_POST['modularity_modules'])) {
            if (get_option($optionName)) {
                update_option($optionName, $_POST['modularity_modules']);
            } else {
                add_option($optionName, $_POST['modularity_modules'], '', 'no');
            }
        } else {
            delete_option($optionName);
        }

        // Save/remove sidebar options
        $optionName = 'modularity_' . $archive . '_sidebar-options';

        if (isset($_POST['modularity_sidebar_options'])) {
            if (get_option($optionName)) {
                update_option($optionName, $_POST['modularity_sidebar_options']);
            } else {
                add_option($optionName, $_POST['modularity_sidebar_options'], '', 'no');
            }
        } else {
            delete_option($optionName);
        }

        return true;
    }

    public function isArchive()
    {
        global $archive;
        return $archive != '';
    }
}
