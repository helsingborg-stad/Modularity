<?php

namespace Modularity\Options;

class General
{
    protected $screenHook = null;

    public function __construct()
    {
        add_action('admin_menu', array($this, 'registerGeneralOptionsPage'));
    }

    /**
     * The setup of the general options page
     * @return void
     */
    public function registerGeneralOptionsPage()
    {
        $this->screenHook = add_menu_page(
            $page_title = __('Modularity options', 'modular'),
            $menu_title = __('Modularity', 'modular'),
            $capability = 'manage_options',
            $menu_slug = 'modularity',
            $function = array($this, 'generalOptionsPageContent'),
            $icon_url = 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pg0KPCEtLSBHZW5lcmF0b3I6IEFkb2JlIElsbHVzdHJhdG9yIDE2LjAuMCwgU1ZHIEV4cG9ydCBQbHVnLUluIC4gU1ZHIFZlcnNpb246IDYuMDAgQnVpbGQgMCkgIC0tPg0KPCFET0NUWVBFIHN2ZyBQVUJMSUMgIi0vL1czQy8vRFREIFNWRyAxLjEvL0VOIiAiaHR0cDovL3d3dy53My5vcmcvR3JhcGhpY3MvU1ZHLzEuMS9EVEQvc3ZnMTEuZHRkIj4NCjxzdmcgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB4PSIwcHgiIHk9IjBweCINCgkgd2lkdGg9IjU0Ljg0OXB4IiBoZWlnaHQ9IjU0Ljg0OXB4IiB2aWV3Qm94PSIwIDAgNTQuODQ5IDU0Ljg0OSIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgNTQuODQ5IDU0Ljg0OTsiDQoJIHhtbDpzcGFjZT0icHJlc2VydmUiPg0KPGc+DQoJPGc+DQoJCTxnPg0KCQkJPHBhdGggZD0iTTU0LjQ5NywzOS42MTRsLTEwLjM2My00LjQ5bC0xNC45MTcsNS45NjhjLTAuNTM3LDAuMjE0LTEuMTY1LDAuMzE5LTEuNzkzLDAuMzE5Yy0wLjYyNywwLTEuMjU0LTAuMTA0LTEuNzktMC4zMTgNCgkJCQlsLTE0LjkyMS01Ljk2OEwwLjM1MSwzOS42MTRjLTAuNDcyLDAuMjAzLTAuNDY3LDAuNTI0LDAuMDEsMC43MTZMMjYuNTYsNTAuODFjMC40NzcsMC4xOTEsMS4yNTEsMC4xOTEsMS43MjksMEw1NC40ODgsNDAuMzMNCgkJCQlDNTQuOTY0LDQwLjEzOSw1NC45NjksMzkuODE3LDU0LjQ5NywzOS42MTR6Ii8+DQoJCQk8cGF0aCBkPSJNNTQuNDk3LDI3LjUxMmwtMTAuMzY0LTQuNDkxbC0xNC45MTYsNS45NjZjLTAuNTM2LDAuMjE1LTEuMTY1LDAuMzIxLTEuNzkyLDAuMzIxYy0wLjYyOCwwLTEuMjU2LTAuMTA2LTEuNzkzLTAuMzIxDQoJCQkJbC0xNC45MTgtNS45NjZMMC4zNTEsMjcuNTEyYy0wLjQ3MiwwLjIwMy0wLjQ2NywwLjUyMywwLjAxLDAuNzE2TDI2LjU2LDM4LjcwNmMwLjQ3NywwLjE5LDEuMjUxLDAuMTksMS43MjksMGwyNi4xOTktMTAuNDc5DQoJCQkJQzU0Ljk2NCwyOC4wMzYsNTQuOTY5LDI3LjcxNiw1NC40OTcsMjcuNTEyeiIvPg0KCQkJPHBhdGggZD0iTTAuMzYxLDE2LjEyNWwxMy42NjIsNS40NjVsMTIuNTM3LDUuMDE1YzAuNDc3LDAuMTkxLDEuMjUxLDAuMTkxLDEuNzI5LDBsMTIuNTQxLTUuMDE2bDEzLjY1OC01LjQ2Mw0KCQkJCWMwLjQ3Ny0wLjE5MSwwLjQ4LTAuNTExLDAuMDEtMC43MTZMMjguMjc3LDQuMDQ4Yy0wLjQ3MS0wLjIwNC0xLjIzNi0wLjIwNC0xLjcwOCwwTDAuMzUxLDE1LjQxDQoJCQkJQy0wLjEyMSwxNS42MTQtMC4xMTYsMTUuOTM1LDAuMzYxLDE2LjEyNXoiLz4NCgkJPC9nPg0KCTwvZz4NCjwvZz4NCjxnPg0KPC9nPg0KPGc+DQo8L2c+DQo8Zz4NCjwvZz4NCjxnPg0KPC9nPg0KPGc+DQo8L2c+DQo8Zz4NCjwvZz4NCjxnPg0KPC9nPg0KPGc+DQo8L2c+DQo8Zz4NCjwvZz4NCjxnPg0KPC9nPg0KPGc+DQo8L2c+DQo8Zz4NCjwvZz4NCjxnPg0KPC9nPg0KPGc+DQo8L2c+DQo8Zz4NCjwvZz4NCjwvc3ZnPg0K',
            $position = 100
        );

        // Setup meta box support
        add_action('load-' . $this->screenHook, array($this, 'setupMetaBoxSupport'));
        add_action('admin_footer-' . $this->screenHook, array($this, 'printScripts'));

        // Hook to add the metaboxes
        add_action('add_meta_boxes_' . $this->screenHook, array($this, 'addMetaBoxes'));
    }

    /**
     * Adds meta boxes to the general options page
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
                require_once $templatePath;
            },
            $this->screenHook,
            'side'
        );

        // Core options
        add_meta_box(
            'modularity-mb-core-options',
            __('Core options', 'modularity'),
            function () {
                $templatePath = \Modularity\Helper\Wp::getTemplate('core-options', 'options/partials');
                require_once $templatePath;
            },
            $this->screenHook,
            'normal'
        );
    }

    /**
     * The contents of the general options page
     * @return void
     */
    public function generalOptionsPageContent()
    {
        wp_enqueue_script('postbox');

        // Load template file
        $templatePath = \Modularity\Helper\Wp::getTemplate('options', 'options');
        require_once $templatePath;
    }

    /**
     * Add metabox support to the options page
     * @return void
     */
    public function setupMetaBoxSupport()
    {
        do_action('add_meta_boxes_' . $this->screenHook, null);
        do_action('add_meta_boxes', $this->screenHook, null);

        add_screen_option('layout_columns', array('max' => 2, 'default' => 2));
    }

    /**
     * Prints inline scripts to admin footer
     * @return void
     */
    public function printScripts()
    {
        echo "<script>jQuery(document).ready(function($){
                postboxes.add_postbox_toggles(pagenow);
                $('#publish').on('click', function () {
                    $(this).siblings('.spinner').css('visibility', 'visible');
                });
              });</script>";
    }
}
