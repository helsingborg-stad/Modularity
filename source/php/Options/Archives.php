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

            foreach ($options['enabled-post-types'] as $postType) {
                $postTypeSlug = $postType;

                if ($postType == 'post') {
                    $postType = '';
                } else {
                    $postType = '?post_type=' . $postType;
                }

                add_submenu_page(
                    'edit.php' . $postType,
                    __('Archive modules'),
                    __('Archive modules'),
                    'edit_posts',
                    'options.php?page=modularity-editor&id=archive-' . $postTypeSlug
                );
            }
        }, 10);
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
    public static function getArchiveSlugs()
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

        return $archives;
    }
}
