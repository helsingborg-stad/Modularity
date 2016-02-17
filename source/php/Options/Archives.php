<?php

namespace Modularity\Options;

class Archives
{
    public function __construct()
    {
        /*
        add_action('admin_menu', function () {
            add_submenu_page(
                'modularity',
                __('Archives', 'modularity'),
                __('Archives', 'modularity'),
                'edit_posts',
                'modularity-archives',
                array($this, 'setupListTable')
            );
        });
        */

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

    public function setupListTable()
    {
        if (!class_exists('WP_List_Table')) {
            require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
        }

        $listTable = new \Modularity\Options\ArchivesList();
        $listTable->prepare_items();

        $templatePath = \Modularity\Helper\Wp::getTemplate('archives', 'options');
        include $templatePath;
    }

    public static function getArchives()
    {
        $archives = get_post_types(array(
            'has_archive' => true
        ), 'object');

        return $archives;
    }
}
