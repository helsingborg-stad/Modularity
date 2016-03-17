<?php

namespace Modularity;

class Editor extends \Modularity\Options
{
    public static $isEditing = null;

    public function __construct()
    {
        global $post;

        // Prepare Thickbox
        new \Modularity\Editor\Thickbox();

        $this->adminBar();

        add_action('admin_head', array($this, 'registerTabs'));
        // add_action('media_buttons', array($this, 'mediaButtons'));
        add_action('wp_ajax_save_modules', array($this, 'save'));

        $this->registerEditorPage();
    }

    /**
     * Handle admin bar stuff
     * @return void
     */
    public function adminBar()
    {
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

                self::$isEditing = array(
                    'id' => $post->ID,
                    'title' => $post->post_title
                );
            } else {
                global $archive;
                $archive = $_GET['id'];

                self::$isEditing = array(
                    'id' => null,
                    'title' => $archive
                );
            }

            add_action('Modularity/options_page_title_suffix', function () {
                echo ': ' . self::$isEditing['title'];
            });
        }
    }

    /**
     * Add insert module button
     * @return void
     */
    public function mediaButtons()
    {
         echo '<button id="insert-modularity-shortcode" class="button"><span class="modularity-icon"></span> Insert module</button>';
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
     * @return  void
     */
    public function addSidebarsMetaBoxes()
    {
        global $wp_registered_sidebars;

        $template = \Modularity\Helper\Post::getPostTemplate();
        $sidebars = null;

        $activeAreas = $this->getActiveAreas($template);

        // Add no active sidebars message if no active sidebars exists
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
     * Get active areas for template.
     * If nothing found on the specific template (eg. archive-cars), fallback to the default template (eg. archive)
     * @param  string $template Template
     * @return array            Active sidebars
     */
    public function getActiveAreas($template)
    {
        $originalTemplate = $template;
        $options = get_option('modularity-options');
        $active = isset($options['enabled-areas'][$template]) ? $options['enabled-areas'][$template] : array();

        self::$isEditing['template'] = $template;

        // Fallback
        if (count($active) === 0 && !is_numeric($template) && strpos($template, 'archive-') !== false
            && !in_array($template, \Modularity\Options\Archives::getArchiveTemplateSlugs())) {
            $template = explode('-', $template, 2)[0];
            self::$isEditing['template'] = $template;
            $active = isset($options['enabled-areas'][$template]) ? $options['enabled-areas'][$template] : array();
        }

        if ($originalTemplate == 'archive-post') {
            $home = \Modularity\Helper\Wp::findCoreTemplates(array(
                'home'
            ));

            if ($home) {
                $active = isset($options['enabled-areas']['home']) ? $options['enabled-areas']['home'] : array();
                self::$isEditing['template'] = 'home';
            }
        }

        return $active;
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

        if (\Modularity\Helper\Post::isArchive()) {
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

        if (is_numeric($postId)) {
            $moduleSidebars = get_post_meta($postId, 'modularity-modules', true);
        } else {
            $moduleSidebars = get_option('modularity_' . $postId . '_modules');
        }

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
                    $retModules[$key]['modules'][$moduleId]->hidden = (isset($module['hidden']) && !empty($module['hidden'])) ? $module['hidden'] : '';
                    $retModules[$key]['modules'][$moduleId]->columnWidth = (isset($module['columnWidth']) && !empty($module['columnWidth'])) ? $module['columnWidth'] : '';
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

        if (\Modularity\Helper\Post::isArchive()) {
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

    /**
     * Saves post modules
     * @return boolean
     */
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

    /**
     * Saves archive modules
     * @return boolean
     */
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

    /**
     * Get column width options
     * @return string Options markup
     */
    public function getWidthOptions()
    {
        $markup = '';
        $options = apply_filters('Modularity\width_options', array(
            'col-md-12' => '100%',
            'col-md-9'  => '75%',
            'col-md-8'   => '66%',
            'col-md-6'  => '50%',
            'col-md-4'  => '33%',
            'col-md-3'  => '25%'
        ));

        foreach ($options as $key => $value) {
            $markup .= '<option value="' . $key . '">' . $value . '</option>' . "\n";
        }

        return $markup;
    }
}
