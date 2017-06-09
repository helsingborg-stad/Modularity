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
        add_action('wp_ajax_save_modules', array($this, 'save'));
        add_action('wp_insert_post_data', array($this, 'avoidDuplicatePostName'), 10, 2);

        $this->registerEditorPage();
    }

    /**
     * Avoid duplicate post_name in db
     * @param  array $data    Post data
     * @param  array $postarr Postarr
     * @return array          Post data to save
     */
    public function avoidDuplicatePostName($data, $postarr)
    {
        if (!isset($data['post_type']) || (isset($data['post_type']) && substr($data['post_type'], 0, 4) != 'mod-')) {
            return $data;
        }

        $data['post_name'] = $data['post_type'] . '_' . uniqid() . '_' . sanitize_title($data['post_title']);

        return $data;
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
                if (!$post) {
                    return;
                }

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

            self::$isEditing = apply_filters('Modularity/is_editing', self::$isEditing);

            add_action('Modularity/options_page_title_suffix', function () {
                echo ': ' . self::$isEditing['title'];
            });
        }
    }

    /**
     * Registers navigation tabs
     * @return void
     */
    public function registerTabs()
    {
        global $post;

        if (!$post && !isset($_GET['page_for'])) {
            return;
        }

        if (!$post && isset($_GET['page_for']) && !empty($_GET['page_for'])) {
            $post = get_post($_GET['page_for']);
        }

        $modulesEditorId = false;

        if ($post) {
            $modulesEditorId = $post->ID;
            $thePostId = $post->ID;
        }

        if ($postType = self::isPageForPostType($post->ID)) {
            $modulesEditorId = 'archive-' . $postType . '&page_for=' . $post->ID;
        }

        $tabs = new \Modularity\Editor\Tabs();
        $tabs->add(__('Content', 'modularity'), admin_url('post.php?post=' . $thePostId . '&action=edit'));
        $tabs->add(__('Modules', 'modularity'), admin_url('options.php?page=modularity-editor&id=' . $modulesEditorId));
    }

    /**
     * Check if the current page id is a page for a post type archive
     * (Option page_for_{post_type_slug} = $post->ID)
     * @param  [type]  $postId [description]
     * @return boolean         [description]
     */
    public static function isPageForPostType($postId)
    {
        $postTypes = get_post_types();

        foreach ($postTypes as $postType) {
            $option = get_option('page_for_' . $postType);
            $pageContent = get_option('page_for_' . $postType . '_content');

            if ($option && $option === $postId && !$pageContent) {
                return $postType;
            }
        }

        return false;
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
                $enabled = \Modularity\ModuleManager::$enabled;
                $available = \Modularity\ModuleManager::$available;
                $deprecated = \Modularity\ModuleManager::$deprecated;

                $modules = array();
                foreach ($enabled as $module) {
                    if (isset($available[$module]) && !in_array($module, $deprecated)) {
                        $modules[$module] = $available[$module];
                    }
                }

                uasort($modules, function ($a, $b) {
                    return $a['labels']['name'] > $b['labels']['name'];
                });

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

        if (self::$isEditing['title'] == 'archive-post') {
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
            if ($post) {
                $options = get_post_meta($post->ID, 'modularity-sidebar-options', true);
            } else {
                if (!isset($_GET['id']) || empty($_GET['id'])) {
                    throw new \Error('Get paramter ID is empty.');
                }

                $options = get_post_meta($_GET['id'], 'modularity-sidebar-options', true);
            }
        }

        if (isset($options[$args['args']['sidebar']['id']])) {
            $options = $options[$args['args']['sidebar']['id']];
        } else {
            $options = null;
        }

        include MODULARITY_TEMPLATE_PATH . 'editor/modularity-sidebar-drop-area.php';
    }

    public static function pageForPostTypeTranscribe($postId)
    {
        if (is_numeric($postId) && $postType = \Modularity\Editor::isPageForPostType($postId)) {
            $postId = 'archive-' . $postType;
        }

        if (substr($postId, 0, 8) === 'archive-') {
            $postType = str_replace('archive-', '', $postId);
            $pageForPostType = get_option('page_for_' . $postType);
            $contentFromPage = get_option('page_for_' . $postType . '_content');

            if ($contentFromPage) {
                $postId = (int) $pageForPostType;
            }
        }

        return $postId;
    }

    /**
     * Get modules added to a specific post
     * @param  integer $postId The post id
     * @return array           The modules on the post
     */
    public static function getPostModules($postId)
    {
        $postId = self::pageForPostTypeTranscribe($postId);

        $modules = array();
        $retModules = array();

        // Get enabled modules
        $available = \Modularity\ModuleManager::$available;
        $enabled = \Modularity\ModuleManager::$enabled;

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
                    if (!isset($module['postid'])) {
                        continue;
                    }

                    $moduleIds[] = $module['postid'];
                }
            }
        }

        $postStatuses = array('publish');
        if (is_user_logged_in()) {
            $postStatuses[] = 'private';
        }

        // Get module posts
        $modulesPosts = get_posts(array(
            'posts_per_page' => -1,
            'post_type' => $enabled,
            'include' => $moduleIds,
            'post_status' => $postStatuses
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

                    // Todo: This will duplicate for every sidebar, move it to top level of array(?)
                    // Alternatively only fetch options for the current sidebar (not all like now)
                    'options' => get_post_meta($postId, 'modularity-sidebar-options', true)
                );

                $arrayIndex = 0;

                foreach ($sidebar as $moduleUid => $module) {
                    if (!isset($module['postid'])) {
                        continue;
                    }

                    $moduleId = $module['postid'];

                    if (!isset($modules[$moduleId])) {
                        continue;
                    }

                    $moduleObject = self::getModule($moduleId, $module);
                    if (!$moduleObject) {
                        continue;
                    }

                    $retModules[$key]['modules'][$arrayIndex] = $moduleObject;

                    $arrayIndex++;
                }
            }
        }

        return $retModules;
    }

    public static function getModule($id, $moduleArgs = array())
    {
        $available = \Modularity\ModuleManager::$available;

        // Basics
        $moduleList = get_posts(array(
            'post_type' => 'any',
            'include' => $id,
            'suppress_filters' => false,
        ));
        $module = (isset($moduleList[0]) && !empty($moduleList[0])) ? $moduleList[0] : null;

        if (!$module || !isset($available[$module->post_type])) {
            return false;
        }

        $module->post_type_name = $available[$module->post_type]['labels']['name'];
        $module->meta = get_post_custom($module->ID);
        $module->isDeprecated = in_array($module->post_type, \Modularity\ModuleManager::$deprecated);

        // Args
        if (count($moduleArgs) > 0) {
            $module->hidden = (isset($moduleArgs['hidden']) && $moduleArgs['hidden'] == 'true') ? true : false;
            $module->columnWidth = isset($moduleArgs['columnWidth']) && !empty($moduleArgs['columnWidth']) ? $moduleArgs['columnWidth'] : '';
        }

        // Hide title?
        $hideTitle = \Modularity\ModuleManager::$moduleSettings[$module->post_type]['hide_title'];

        if (strlen(get_post_meta($module->ID, 'modularity-module-hide-title', true)) > 0) {
            $hideTitle = boolval(get_post_meta($module->ID, 'modularity-module-hide-title', true));
        }

        $module->hideTitle = $hideTitle;

        return $module;
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

        $this->savePost();

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
        $key = $_REQUEST['id'];

        if (is_numeric($key)) {
            return $this->saveAsPostMeta($key);
        }

        if (\Modularity\Helper\Post::isArchive()) {
            global $archive;
            $key = $archive;
        }

        return $this->saveAsOption($key);
    }

    public function saveAsPostMeta($key)
    {
        // Save/remove modules
        if (isset($_POST['modularity_modules'])) {
            update_post_meta($key, 'modularity-modules', $_POST['modularity_modules']);
        } else {
            delete_post_meta($key, 'modularity-modules');
        }

        // Save/remove sidebar options
        if (isset($_POST['modularity_sidebar_options'])) {
            update_post_meta($key, 'modularity-sidebar-options', $_POST['modularity_sidebar_options']);
        } else {
            delete_post_meta($key, 'modularity-sidebar-options');
        }

        return true;
    }

    /**
     * Saves archive modules
     * @return boolean
     */
    public function saveAsOption($key)
    {
        $key = 'modularity_' . $key;
        $optionName = $key . '_modules';

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
        $optionName = $key . '_sidebar-options';

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
        $markup = '<option value="">' . __('Inherit', 'modularity') . '</option>' . "\n";

        foreach (self::widthOptions() as $key => $value) {
            $markup .= '<option value="' . $key . '">' . $value . '</option>' . "\n";
        }

        return $markup;
    }

    /**
     * Define avabile width classes
     * @return array
     */
    public static function widthOptions()
    {
        return apply_filters('Modularity/Editor/WidthOptions', array(
            'grid-md-12' => '100%',
            'grid-md-9' => '75%',
            'grid-md-8' => '66%',
            'grid-md-6' => '50%',
            'grid-md-4' => '33%',
            'grid-md-3' => '25%'
        ));
    }
}
