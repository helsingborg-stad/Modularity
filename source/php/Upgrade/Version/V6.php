<?php

namespace Modularity\Upgrade\Version;

use \Modularity\Upgrade\Migrators\Block\AcfBlockMigration;
use \Modularity\Upgrade\Migrators\Module\AcfModuleMigration;
use \Modularity\Upgrade\Version\Helper\GetPostsByPostType;

class V6 implements versionInterface {
    private $db;
    private $name;

    public function __construct(\wpdb $db) {
        $this->db = $db;
        $this->name = 'manualinput';
    }

    public function upgrade(): bool
    {
        $this->upgradeBlocks();
        $this->upgradeModules();

        return true;
    }

    private function upgradeModules() 
    {
        $moduleMigrator = new AcfModuleMigration(
            $this->db,
            GetPostsByPostType::getPostsByPostType('mod-' . $this->name),
            $this->getFields()
        );

        $moduleMigrator->migrateModules();
    }

    private function upgradeBlocks() 
    {
        $blockMigrator = new AcfBlockMigration(
            $this->db,
            'acf/' . $this->name,
            $this->getFields()
        );

        $blockMigrator->migrateBlocks();
    }

    private function getFields() 
    {
        return 
        [
            'posts_columns' => [
                'type' => 'removeField'
            ],
            'posts_fields' => [
                'type' => 'removeField'
            ],
            'posts_data_source' => [
                'type' => 'removeField'
            ],
            'data' => [
                'type' => 'removeField'
            ],
            'posts_sort_by' => [
                'type' => 'removeField'
            ],
            'posts_sort_order' => [
                'type' => 'removeField'
            ],
            'posts_taxonomy_filter' => [
                'type' => 'removeField'
            ],
            'show_as_slider' => [
                'type' => 'removeField'
            ],
            'posts_highlight_first' => [
                'type' => 'removeField'
            ],
            'posts_display_as' => [
                'type' => 'removeField'
            ],
            'posts_list_column_titles' => [
                'type' => 'removeField'
            ],
            'taxonomy_display' => [
                'type' => 'removeField'
            ],
            'post_single_show_featured_image' => [
                'type' => 'removeField'
            ],
            'title_column_label' => [
                'type' => 'removeField'
            ],
            'allow_freetext_filtering' => [
                'type' => 'removeField'
            ]
        ];
    }
}