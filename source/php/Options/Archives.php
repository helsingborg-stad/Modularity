<?php

namespace Modularity\Options;

class Archives
{
    public function __construct()
    {
        /**
         * Add "archive modules" links in admin menu to each post type submenu
         */
        add_action('admin_menu', function () {
            $options = get_option('modularity-options');
            $options['enabled-post-types'] = apply_filters('Modularity/Options/Archives/Modules::EnabledPostTypes', $options['enabled-post-types']);

            if (!isset($options['enabled-post-types']) || !is_array($options['enabled-post-types'])) {
                return;
            }

            foreach ($options['enabled-post-types'] as $postType) {
                $postTypeSlug = $postType;

                // Do not add archive modules for page
                if ($postType == 'page') {
                    continue;
                }

                if ($postType == 'post') {
                    $postType = '';
                } else {
                    $postType = '?post_type=' . $postType;
                }

                $editorLink = 'options.php?page=modularity-editor&id=' . \Modularity\Editor::pageForPostTypeTranscribe('archive-' . $postTypeSlug);

                add_submenu_page(
                    'edit.php' . $postType,
                    __('Archive modules', 'modularity'),
                    __('Archive modules', 'modularity'),
                    'edit_posts',
                    $editorLink
                );
            }
        }, 10);

        /* Fixes broken admin pages */
        add_action('after_setup_theme', function () {
            if (!is_admin()) {
                return;
            }
            if (isset($_GET['post_type']) && isset($_GET['page']) && isset($_GET['id']) && substr($_GET['page'], 0, 34) == "options.php?page=modularity-editor") {
                wp_redirect(admin_url($_GET['page']. "&id=" . $_GET['id']), 302);
                exit;
            }
        }, 1);
    }

    /**
     * Get a list of all archives available
     * @return object
     */
    public static function getArchives()
    {
        $archives = get_post_types(array(
            'has_archive' => true
        ), 'object');

        return $archives;
    }

    /**
     * Get list of currently available archives slugs that has a template
     * @return array
     */
    public static function getArchiveTemplateSlugs()
    {
        $archives = get_post_types(array(
            'has_archive' => true
        ), 'names');

        $templates = array();

        foreach ($archives as $archive) {
            $template = \Modularity\Helper\Wp::findCoreTemplates(array(
                'archive-' . $archive
            ));

            if ($template) {
                $templates[] = $template;
            } else {
                $templates[] = 'archive';
            }
        }

        array_unique($templates);
        return $templates;
    }
}
