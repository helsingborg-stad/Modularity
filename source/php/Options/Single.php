<?php

namespace Modularity\Options;

/**
 * Enable modules editor for post type.
 */
class Single
{
    public function __construct()
    {
        /**
         * Add "archive modules" links in admin menu to each post type submenu
         */
        add_action('admin_menu', function () {
            $options = get_option('modularity-options');

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

                /* Checking if the post type has an archive, otherwise bail early. */
                $postTypeObject = get_post_type_object($postTypeSlug);
                if (!is_null($postTypeObject) && !$postTypeObject->_builtin && !$postTypeObject->has_archive) {
                    continue;
                }

                $editorLink = 'options.php?page=modularity-editor&id=' . \Modularity\Editor::pageForPostTypeTranscribe('single-' . $postTypeSlug);

                add_submenu_page(
                    'edit.php' . $postType,
                    __('Post type modules', 'modularity'),
                    __('Post type modules', 'modularity'),
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
                wp_redirect(admin_url($_GET['page'] . "&id=" . $_GET['id']), 302);
                exit;
            }
        }, 1);
    }

    /**
     * Get list of currently available archives slugs that has a template
     * @return array
     */
    public static function getSingleTemplateSlugs()
    {
        $postTypeNames = get_post_types(array(
            'public' => true,
            'show_ui' => true,
        ), 'names');

        $templates = array();

        foreach ($postTypeNames as $postTypeName) {
            $template = \Modularity\Helper\Wp::findCoreTemplates(array(
                'single-' . $postTypeName
            ));

            if ($template) {
                $templates[] = $template;
            } else {
                $templates[] = 'single';
            }
        }

        array_unique($templates);
        return $templates;
    }
}
