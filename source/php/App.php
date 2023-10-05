<?php

namespace Modularity;

class App
{
    public static $display = null;
    public static $moduleManager = null;
    public $editor = null;


    public function __construct()
    {
        add_action('admin_enqueue_scripts', array($this, 'enqueueAdmin'), 950);
        add_action('enqueue_block_editor_assets', array($this, 'enqueueBlockEditor')); 
        add_action('wp_enqueue_scripts', array($this, 'enqueueFront'), 950);
        add_action('admin_menu', array($this, 'addAdminMenuPage'));
        add_action('admin_init', array($this, 'addCaps'));
        add_action('post_updated', array($this, 'updateDate'), 10, 2);

        // Main hook
        do_action('Modularity');

        /**
         * Redirect top level Modularity page to the Modularity options page
         */
        add_action('load-toplevel_page_modularity', function () {
            wp_redirect(admin_url('admin.php?page=modularity-options'));
        });

        $this->setupAdminBar();
        
        new Upgrade();
        new Ajax();
        new Options\General();
        
        $archivesAdminPage = new Options\ArchivesAdminPage();
        $archivesAdminPage->addHooks();
        $optionsForSingleViews = new Options\SingleAdminPage();
        $optionsForSingleViews->addHooks();

        // Rest Controllers
        $modulesRestController = new Api\V1\Modules();
        $modulesRestController->register_routes();

        self::$moduleManager = new ModuleManager();

        $this->editor = new Editor();
        self::$display = new Display();

        new Helper\Acf();
        new CachePurge();

        new Search();

        add_action('widgets_init', function () {
            register_widget('\Modularity\Widget');
        });
    }


    /**
     * Update modified date on related post when module is saved
     * @return boolean True if update(s) where made, otherwise false.
     */
    public function updateDate(int $postId, $postAfter)
    {
        $usedInPosts = self::$moduleManager->getModuleUsage($postId);

        if (empty($usedInPosts)) {
            return false;
        }

        $modified = $postAfter->post_modified;

        foreach ($usedInPosts as $post) {
            wp_update_post([
                'ID' => $post->post_id,
                'post_modified' => $modified,
                'post_modified_gmt' => get_gmt_from_date($modified)
            ]);
        }

        return true;
    }

    public function addCaps()
    {
        $admin = get_role('administrator');
        if (is_a($admin, 'WP_Role') && $admin->has_cap('edit_module')) {
            return;
        }

        $caps = array(
            'administrator' => array(
                'edit_module',
                'edit_modules',
                'edit_other_modules',
                'publish_modules',
                'read_modules',
                'delete_module'
            ),
            'editor' => array(
                'edit_module',
                'edit_modules',
                'edit_other_modules',
                'publish_modules',
                'read_modules',
                'delete_module'
            ),
            'author' => array(
                'edit_module',
                'edit_modules',
                'edit_other_modules',
                'publish_modules',
                'read_modules'
            )
        );

        foreach ($caps as $roleId => $cap) {
            $role = get_role($roleId);

            foreach ($cap as $item) {
                $role->add_cap($item);
            }
        }
    }

    /**
     * Add buttons to admin bar (public)
     * @return void
     */
    public function setupAdminBar()
    {
        // Link to editor from page
        add_action('admin_bar_menu', function () {
            $options = get_option('modularity-options');

            if (is_admin() || !current_user_can('edit_posts')) {
                return;
            }

            global $wp_admin_bar;
            global $post;

            $editorLink = admin_url('options.php?page=modularity-editor&id=' . get_the_id());

            $archiveSlug = \Modularity\Helper\Wp::getArchiveSlug();
            if ($archiveSlug && $postId = \Modularity\Editor::pageForPostTypeTranscribe($archiveSlug)) {
                $editorLink = admin_url('options.php?page=modularity-editor&id=' . $postId);
            }

            if (isset($options['enabled-post-types']) && is_array($options['enabled-post-types']) && !in_array(get_post_type(), $options['enabled-post-types'])) {
                $editorLink = null;
            }

            $editorLink = apply_filters('Modularity/adminbar/editor_link', $editorLink, $post, $archiveSlug, $this->currentUrl());

            if (empty($editorLink)) {
                return;
            }

            $wp_admin_bar->add_node(array(
                'id' => 'modularity_editor',
                'title' => __('Edit', 'modularity') . ' ' . strtolower(__('Modules', 'modularity')),
                'href' => $editorLink,
                'meta' => array(
                    'class' => 'modularity-editor-icon'
                )
            ));
        }, 1050);
    }

    public function currentUrl($querystring = true)
    {
        $url =  '//' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

        if (!$querystring) {
            $url = preg_replace('/\?(.*)/', '', $url);
        }

        return $url;
    }

    public function enqueueFront()
    {
        wp_register_style('modularity', MODULARITY_URL . '/dist/'
            . \Modularity\Helper\CacheBust::name('css/modularity.css'));
        wp_enqueue_style('modularity');

        wp_register_script('modularity', MODULARITY_URL . '/dist/'
            . \Modularity\Helper\CacheBust::name('js/modularity.js'), [], null, true);

        wp_localize_script('modularity', 'modularityAdminLanguage', array(
            'langedit' => __('Edit', 'modularity'),
            'langimport' => __('Import', 'modularity'),
            'langremove' => __('Remove', 'modularity'),
            'langhide' => __('Hide module', 'modularity'),
            'actionRemove' => __('Are you sure you want to remove this module?', 'modularity'),
            'isSaving' => __('Saving…', 'modularity'),
            'close' => __('Close', 'modularity'),
            'width' => __('Width', 'modularity'),
            'widthOptions' => $this->editor->getWidthOptions(),
            'deprecated' => __('Deprecated', 'modularity')
        ));
        wp_enqueue_script('modularity');

        if (!current_user_can('edit_posts')) {
            return;
        }
        //Register admin specific scripts/styling here

        if (wp_script_is('jquery', 'registered') && !wp_script_is('jquery', 'enqueued')) {
            wp_enqueue_script('jquery');
        }
    }

    public function enqueueBlockEditor() {
        
        if ($modulesEditorId = \Modularity\Helper\Wp::isGutenbergEditor()) {
            wp_register_script('block-editor-edit-modules', MODULARITY_URL . '/dist/'
            . \Modularity\Helper\CacheBust::name('js/edit-modules-block-editor.js'), [], null, ['in_footer' => true]);

            wp_localize_script('block-editor-edit-modules', 'modularityBlockEditor', array(
                'editModulesLinkLabel' => __('Edit Modules', 'modularity'),
                'editModulesLinkHref' => admin_url('options.php?page=modularity-editor&id=' . $modulesEditorId)
            ));

            wp_enqueue_script('block-editor-edit-modules');
        }
    }

    /**
     * Enqueues scripts and styles
     * @return void
     */
    public function enqueueAdmin()
    {
        if (!$this->isModularityPage()) {
            return;
        }

        wp_register_style('modularity', MODULARITY_URL . '/dist/'
        . \Modularity\Helper\CacheBust::name('css/modularity.css'));
        wp_enqueue_style('modularity');

        wp_register_script('modularity', MODULARITY_URL . '/dist/'
        . \Modularity\Helper\CacheBust::name('js/modularity.js'), [], null, true);
        wp_localize_script('modularity', 'modularityAdminLanguage', array(
            'langedit' => __('Edit', 'modularity'),
            'langimport' => __('Import', 'modularity'),
            'langremove' => __('Remove', 'modularity'),
            'langhide' => __('Hide module', 'modularity'),
            'actionRemove' => __('Are you sure you want to remove this module?', 'modularity'),
            'isSaving' => __('Saving…', 'modularity'),
            'close' => __('Close', 'modularity'),
            'width' => __('Width', 'modularity'),
            'widthOptions' => $this->editor->getWidthOptions(),
            'deprecated' => __('Deprecated', 'modularity')
        ));
        wp_enqueue_script('modularity');

        wp_register_script('dynamic-acf', MODULARITY_URL . '/dist/'
        . \Modularity\Helper\CacheBust::name('js/dynamic-acf.js'));
        wp_enqueue_script('dynamic-acf');
        
        wp_register_script('dynamic-map-acf', MODULARITY_URL . '/dist/'
        . \Modularity\Helper\CacheBust::name('js/dynamic-map-acf.js'));
        wp_enqueue_script('dynamic-map-acf');

        add_action('admin_head', function () {
            echo "
                <script>
                    var admin_url = '" . admin_url() . "';
                </script>
            ";
        });

        
        // If editor
        if (\Modularity\Helper\Wp::isEditor()) {
            wp_enqueue_script('jquery-ui-sortable');
            wp_enqueue_script('jquery-ui-draggable');
            wp_enqueue_script('jquery-ui-droppable');

            add_action('admin_head', function () {
                global $post;
                global $archive;

                if (isset($_GET['id']) && is_numeric($_GET['id']) && get_post_status($_GET['id'])) {
                    $id = $_GET['id'];
                } else {
                    $id = isset($post->ID) ? $post->ID : "'" . $archive . "'";
                }

                echo "
                    <script>
                        var modularity_post_id = " . $id . "
                    </script>
                ";
            }, 10);
        }
    }

    /**
     * Check if current page is a modularity page
     * @return boolean
     */
    public function isModularityPage()
    {
        global $current_screen;

        $result = true;

        if (strpos($current_screen->id, 'modularity') === false
            && strpos($current_screen->id, 'mod-') === false
            && ($current_screen->action != 'add'
                && (
                    isset($_GET['action'])
                    && $_GET['action'] != 'edit')
                )
            && $current_screen->base != 'post'
            && $current_screen->base != 'widgets') {
            $result = false;
        }
        return $result;
    }

    public function addAdminMenuPage()
    {
        add_menu_page(
            'Modularity',
            'Modularity',
            'manage_options',
            'modularity',
            function () {

            },
            'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pg0KPCEtLSBHZW5lcmF0b3I6IEFkb2JlIElsbHVzdHJhdG9yIDE2LjAuMCwgU1ZHIEV4cG9ydCBQbHVnLUluIC4gU1ZHIFZlcnNpb246IDYuMDAgQnVpbGQgMCkgIC0tPg0KPCFET0NUWVBFIHN2ZyBQVUJMSUMgIi0vL1czQy8vRFREIFNWRyAxLjEvL0VOIiAiaHR0cDovL3d3dy53My5vcmcvR3JhcGhpY3MvU1ZHLzEuMS9EVEQvc3ZnMTEuZHRkIj4NCjxzdmcgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB4PSIwcHgiIHk9IjBweCINCgkgd2lkdGg9IjU0Ljg0OXB4IiBoZWlnaHQ9IjU0Ljg0OXB4IiB2aWV3Qm94PSIwIDAgNTQuODQ5IDU0Ljg0OSIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgNTQuODQ5IDU0Ljg0OTsiDQoJIHhtbDpzcGFjZT0icHJlc2VydmUiPg0KPGc+DQoJPGc+DQoJCTxnPg0KCQkJPHBhdGggZD0iTTU0LjQ5NywzOS42MTRsLTEwLjM2My00LjQ5bC0xNC45MTcsNS45NjhjLTAuNTM3LDAuMjE0LTEuMTY1LDAuMzE5LTEuNzkzLDAuMzE5Yy0wLjYyNywwLTEuMjU0LTAuMTA0LTEuNzktMC4zMTgNCgkJCQlsLTE0LjkyMS01Ljk2OEwwLjM1MSwzOS42MTRjLTAuNDcyLDAuMjAzLTAuNDY3LDAuNTI0LDAuMDEsMC43MTZMMjYuNTYsNTAuODFjMC40NzcsMC4xOTEsMS4yNTEsMC4xOTEsMS43MjksMEw1NC40ODgsNDAuMzMNCgkJCQlDNTQuOTY0LDQwLjEzOSw1NC45NjksMzkuODE3LDU0LjQ5NywzOS42MTR6Ii8+DQoJCQk8cGF0aCBkPSJNNTQuNDk3LDI3LjUxMmwtMTAuMzY0LTQuNDkxbC0xNC45MTYsNS45NjZjLTAuNTM2LDAuMjE1LTEuMTY1LDAuMzIxLTEuNzkyLDAuMzIxYy0wLjYyOCwwLTEuMjU2LTAuMTA2LTEuNzkzLTAuMzIxDQoJCQkJbC0xNC45MTgtNS45NjZMMC4zNTEsMjcuNTEyYy0wLjQ3MiwwLjIwMy0wLjQ2NywwLjUyMywwLjAxLDAuNzE2TDI2LjU2LDM4LjcwNmMwLjQ3NywwLjE5LDEuMjUxLDAuMTksMS43MjksMGwyNi4xOTktMTAuNDc5DQoJCQkJQzU0Ljk2NCwyOC4wMzYsNTQuOTY5LDI3LjcxNiw1NC40OTcsMjcuNTEyeiIvPg0KCQkJPHBhdGggZD0iTTAuMzYxLDE2LjEyNWwxMy42NjIsNS40NjVsMTIuNTM3LDUuMDE1YzAuNDc3LDAuMTkxLDEuMjUxLDAuMTkxLDEuNzI5LDBsMTIuNTQxLTUuMDE2bDEzLjY1OC01LjQ2Mw0KCQkJCWMwLjQ3Ny0wLjE5MSwwLjQ4LTAuNTExLDAuMDEtMC43MTZMMjguMjc3LDQuMDQ4Yy0wLjQ3MS0wLjIwNC0xLjIzNi0wLjIwNC0xLjcwOCwwTDAuMzUxLDE1LjQxDQoJCQkJQy0wLjEyMSwxNS42MTQtMC4xMTYsMTUuOTM1LDAuMzYxLDE2LjEyNXoiLz4NCgkJPC9nPg0KCTwvZz4NCjwvZz4NCjxnPg0KPC9nPg0KPGc+DQo8L2c+DQo8Zz4NCjwvZz4NCjxnPg0KPC9nPg0KPGc+DQo8L2c+DQo8Zz4NCjwvZz4NCjxnPg0KPC9nPg0KPGc+DQo8L2c+DQo8Zz4NCjwvZz4NCjxnPg0KPC9nPg0KPGc+DQo8L2c+DQo8Zz4NCjwvZz4NCjxnPg0KPC9nPg0KPGc+DQo8L2c+DQo8Zz4NCjwvZz4NCjwvc3ZnPg0K',
            100
        );
    }
}
