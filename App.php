<?php

namespace Modularity;

class App
{
    /**
     * JS and CSS suffix
     * Should be "dev" or "min"
     * @var string
     */
    public static $assetSuffix = 'dev';

    public function __construct()
    {
        add_action('admin_enqueue_scripts', array($this, 'enqueue'));
        add_action('admin_menu', array($this, 'addAdminMenuPage'));

        /**
         * Redirect top level Modularity page to the Modularity options page
         */
        add_action('load-toplevel_page_modularity', function () {
            wp_redirect(admin_url('admin.php?page=modularity-options'));
        });

        $this->setupAdminBar();

        new Ajax();
        new Options\General();
        new Options\Archives();
        new Module();
        new Editor();
        new Display();

        new Helper\Acf();

        do_action('Modularity');
    }

    /**
     * Add buttons to admin bar (public)
     * @return void
     */
    public function setupAdminBar()
    {
        // Link to editor from page
        add_action('admin_bar_menu', function () {
            if (is_admin()) {
                return;
            }

            global $wp_admin_bar;

            $editorLink = admin_url('options.php?page=modularity-editor&id=' . get_the_id());

            if (is_post_type_archive() || is_archive()) {
                $postType = get_post_type_object(get_post_type());
                $editorLink = admin_url('options.php?page=modularity-editor&id=archive-' . $postType->rewrite['slug']);
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

    /**
     * Enqueues scripts and styles
     * @return void
     */
    public function enqueue()
    {
        if (!$this->isModularityPage()) {
            return;
        }

        // Style
        wp_register_style('modularity', MODULARITY_URL . '/dist/css/modularity.' . self::$assetSuffix . '.css', false, '1.0.0');
        wp_enqueue_style('modularity');

        // Scripts
        wp_register_script('modularity', MODULARITY_URL . '/dist/js/modularity.' . self::$assetSuffix . '.js', false, '1.0.0', true);
        wp_localize_script('modularity', 'modularityAdminLanguage', array(
            'langedit' => __('Edit', 'modularity'),
            'langimport' => __('Import', 'modularity'),
            'langremove' => __('Remove', 'modularity'),
            'langhide' => __('Hide', 'modularity'),
            'actionRemove' => __('Are you sure you want to remove this module?', 'modularity'),
            'isSaving' => __('Saving…', 'modularity'),
            'close' => __('Close', 'modularity')
        ));
        wp_enqueue_script('modularity');

        // If editor
        if (\Modularity\Helper\Wp::isEditor()) {
            wp_enqueue_script('jquery-ui-sortable');
            wp_enqueue_script('jquery-ui-draggable');
            wp_enqueue_script('jquery-ui-droppable');

            add_action('admin_head', function () {
                global $post;

                echo "
                    <script>
                        var admin_url = '" . admin_url() . "';
                        var modularity_post_id = " . $post->ID . "
                    </script>
                ";
            });

            add_thickbox();
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
            && ($current_screen->action != 'add'
                && (
                    isset($_GET['action'])
                    && $_GET['action'] != 'edit')
                )
            && $current_screen->base != 'post') {
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
