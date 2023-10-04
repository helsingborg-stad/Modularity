<?php

namespace Modularity\Options;

/**
 * Enable modules editor for post type.
 */
class SingleAdminPage implements \Modularity\Options\AdminPageInterface
{

    public function addHooks(): void
    {
        add_action('admin_menu', [$this, 'addAdminPage'], 10);
    }

    public function addAdminPage(): void
    {
        $options = get_option('modularity-options');

        if (!isset($options['enabled-post-types']) || !is_array($options['enabled-post-types'])) {
            return;
        }

        foreach ($options['enabled-post-types'] as $postType) {
            $postTypeUrlParam = '?post_type=' . $postType;
            $transcribedPostType = \Modularity\Editor::pageForPostTypeTranscribe('single-' . $postType);
            $editorLink = "options.php?page=modularity-editor&id={$transcribedPostType}";

            add_submenu_page(
                'edit.php' . $postTypeUrlParam,
                __('Post type modules', 'modularity'),
                __('Post type modules', 'modularity'),
                'edit_posts',
                $editorLink
            );
        }
    }
}
