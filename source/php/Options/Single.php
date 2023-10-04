<?php

namespace Modularity\Options;

/**
 * Enable modules editor for post type.
 */
class Single
{

    public function addHooks() {
        add_action('admin_menu', [$this, 'addAdminPage'], 10);
    }

    public function addAdminPage() {
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

    /**
     * Get list of currently available archives slugs that has a template
     * @return array
     */
    public static function getSingleTemplateSlugs()
    {
        $postTypeNames = self::getPublicPostTypeNames();
        return self::getTemplatesFromPostTypeNames($postTypeNames);
    }

    private static function getTemplatesFromPostTypeNames(array $postTypeNames)
    {
        $templates = array_map(function ($postTypeName) {
            $template = \Modularity\Helper\Wp::findCoreTemplates(['single-' . $postTypeName]);
            return ($template)
                ? $template
                : 'single';
        }, $postTypeNames);

        return array_unique($templates);
    }

    private static function getPublicPostTypeNames() {
        return get_post_types(array(
            'public' => true,
            'show_ui' => true,
        ), 'names');
    }
}
