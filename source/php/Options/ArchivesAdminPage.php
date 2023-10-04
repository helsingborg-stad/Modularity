<?php

namespace Modularity\Options;

class ArchivesAdminPage implements \Modularity\Options\AdminPageInterface
{
    public function addHooks(): void
    {
        add_action('admin_menu', [$this, 'addAdminPage'], 10);
        add_action('after_setup_theme', [$this, 'fixBrokenArchiveLinks'], 10);
    }

    public function addAdminPage(): void
    {
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

            $editorLink = 'options.php?page=modularity-editor&id=' . \Modularity\Editor::pageForPostTypeTranscribe('archive-' . $postTypeSlug);

            add_submenu_page(
                'edit.php' . $postType,
                __('Archive modules', 'modularity'),
                __('Archive modules', 'modularity'),
                'edit_posts',
                $editorLink
            );
        }
    }

    public function fixBrokenArchiveLinks()
    {
        if (!is_admin()) {
            return;
        }
        if (isset($_GET['post_type']) && isset($_GET['page']) && isset($_GET['id']) && substr($_GET['page'], 0, 34) == "options.php?page=modularity-editor") {
            wp_redirect(admin_url($_GET['page'] . "&id=" . $_GET['id']), 302);
            exit;
        }
    }
}
