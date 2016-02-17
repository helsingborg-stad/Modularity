<?php

namespace Modularity\Options;

class Archives
{
    public function __construct()
    {
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
