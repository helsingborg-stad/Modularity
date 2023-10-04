<?php

namespace Modularity\Options;

use WP_Post_Type;

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
            $postTypeObject = get_post_type_object($postType);

            if ($this->postTypeAllowsArchiveModules($postTypeObject)) {
                $postTypeUrlParam = $postType === 'post' ? '' : '?post_type=' . $postType;
                $transcribedPostType = \Modularity\Editor::pageForPostTypeTranscribe('archive-' . $postType);
                $editorLink = "options.php?page=modularity-editor&id={$transcribedPostType}";
                add_submenu_page(
                    'edit.php' . $postTypeUrlParam,
                    __('Archive modules', 'modularity'),
                    __('Archive modules', 'modularity'),
                    'edit_posts',
                    $editorLink
                );
            }
        }
    }


    private function postTypeAllowsArchiveModules(?WP_Post_Type $postType): bool
    {
        if (is_null($postType)) {
            return false;
        }

        return $postType->has_archive && !$postType->hierarchical;
    }


    public function fixBrokenArchiveLinks()
    {
        if (
            is_admin() &&
            isset($_GET['post_type']) &&
            isset($_GET['page']) &&
            isset($_GET['id']) &&
            substr($_GET['page'], 0, 34) == "options.php?page=modularity-editor"
        ) {
            wp_redirect(admin_url($_GET['page'] . "&id=" . $_GET['id']), 302);
            exit;
        }
    }
}
