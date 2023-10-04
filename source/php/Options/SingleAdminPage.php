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
            $postTypeSlug = $postType;
            $postTypeUrlParam = '?post_type=' . $postType;
            $editorLink = 'options.php?page=modularity-editor&id=' . \Modularity\Editor::pageForPostTypeTranscribe('single-' . $postTypeSlug);

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
