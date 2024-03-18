<?php

namespace Modularity\Upgrade\Migrators\Block;

use Modularity\Upgrade\Migrators\Block\AcfBlockMigrationHandler;

class AcfBlockMigration {

    private $db;
    private $blockName;
    private $fields;
    private $newBlockName;
    private $blockConditionFunctionName;

    public function __construct($db, $blockName, array $fields = [], $newBlockName = false, $blockConditionFunctionName = false) {
        $this->db = $db;
        $this->blockName = $blockName;
        $this->fields = $fields;
        $this->newBlockName = $newBlockName;
        $this->blockConditionFunctionName = $blockConditionFunctionName;
    }

    /**
     * Block: Extract a field value and adds it to another field.
     * 
     * @param string $pages Pages with the block
     * @param array $fields Fields is an array with the old name of the field being a key and the value being the new name of the field
     * @param string|false $newBlockName renames the block to a different block.
     */
    public function migrateBlocks() 
    {
        $pages = $this->getPagesFromBlockName();
        
        if ($this->isValidPagesAndFields($pages, $this->fields)) {
            foreach ($pages as &$page) {
                $blocks = $this->updateBlocks(parse_blocks($page->post_content), $page);
                $this->updatePageContent($blocks, $page);
            }
        }
    }

    private function updateBlocks($blocks):array {
        if (empty($blocks) || !is_array($blocks)) {
            return [];
        }
        foreach ($blocks as &$block) {
            if (!empty($block['blockName']) && $block['blockName'] === $this->blockName && !empty($block['attrs']['data']) && $this->blockCondition($this->blockConditionFunctionName, $block)) {
                
                $migrationFieldManager = new AcfBlockMigrationHandler($this->fields, $block['attrs']['data']);
                $block['attrs']['data'] = $migrationFieldManager->migrateBlockFields();

                if (!empty($this->newBlockName)) {
                    $block['blockName'] = $this->newBlockName;
                    $block['attrs']['name'] = $this->newBlockName;
                }
            }
        }

        return $blocks;
    }

    private function updatePageContent($blocks, $page) {
        $serializedBlocks = serialize_blocks($blocks); 

        if (!empty($serializedBlocks) && !empty($page->ID)) {
            $queryUpdateContent = $this->db->prepare(
                "UPDATE " . $this->db->posts . " SET post_content = %s WHERE ID = %d", 
                $serializedBlocks, 
                $page->ID
            ); 
            $this->db->query($queryUpdateContent); 
        }
    }

    /* FIX: This parts needs to be moved */
    private function postsBlockCondition($block) {
        return !empty($block['attrs']['data']['posts_data_source']) && $block['attrs']['data']['posts_data_source'] == 'input';
    }

    private function isValidPagesAndFields($pages, $fields):bool {
        return 
            !empty($pages) && 
            is_array($pages) && 
            !empty($fields) && 
            is_array($fields);
    }

    /**
     * Check a condition for a block based on a function.
     * 
     * @param string|false $function The name of the condition-checking function.
     * @param array $block The block data to be checked.
     * @return bool Returns true or the condition function.
     */
    private function blockCondition($function, $block) {
        if ($function && method_exists($this, $function)) {
            return $this->$function($block);
        }

        return true;
    }


    /**
     * Gets all pages that have a specific block attached
     * 
     * @param string $blockName Name of the block
     */
    private function getPagesFromBlockName() {
        global $wpdb;
        $pages = $wpdb->get_results(
            "SELECT *
            FROM $wpdb->posts
            WHERE post_content LIKE '%{$this->blockName}%'
            AND post_type != 'customize_changeset'"
        );
        
        return $pages;
    }
}