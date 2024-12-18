<?php

namespace Modularity\Upgrade\Version;

use \Modularity\Upgrade\Version\Helper\GetPostsByPostType;

/**
 * Class V8
 * This version migrates really old posts modules that have previously been migrated to manualinput in the V5 upgrade.
 * Due to the old structure, this was not done properly and the data is not correctly migrated.
 * 
 * @package Modularity\Upgrade\Version
 */

class V8 implements versionInterface {
    private $db;
    private $oldKey = 'data';
    private $newKey = 'manual_inputs';

    public function __construct(\wpdb $db) {
      $this->db = $db;
    }

    public function upgrade(): bool
    {
      $this->upgradeModules();
      return true;
    }

    /**
     * Migrate modules from old key to new key
     * 
     * @return bool
     */
    private function upgradeModules() 
    {
      $modulesMatchingCriteria = $this->getModules(); 

      if(empty($modulesMatchingCriteria)) {
        return false;
      }

      foreach ($modulesMatchingCriteria as $module) {
        /* Update prefix */ 
        $query = $this->db->prepare(
          "UPDATE {$this->db->prefix}postmeta 
          SET meta_key = REPLACE(meta_key, %s, %s) 
          WHERE post_id = %d AND meta_key ",
          $this->oldKey,  // The part to replace
          $this->newKey,  // The new value to replace it with
          $module->ID,
        ) . "LIKE '%{$this->oldKey}%'";
        $this->db->query($query);

        /* Updates suffix */ 
        foreach(['_post_title' => '_title', '_post_content' => '_content'] as $old => $new) {
          $subQuery = $query = $this->db->prepare(
            "UPDATE {$this->db->prefix}postmeta 
            SET meta_key = REPLACE(meta_key, %s, %s) 
            WHERE post_id = %d AND meta_key ",
            $old,  
            $new,
            $module->ID,
          ) . "LIKE '%{$this->newKey}%{$old}%'";
          $this->db->query($subQuery);
        }
      }

      /* Update how many rows that have been migrated */
      $query = $this->db->prepare(
          "SELECT COUNT(*) 
          FROM {$this->db->prefix}postmeta 
          WHERE post_id = %d AND meta_key LIKE ",
          $module->ID,
        ) . "'{$this->newKey}_%_title'";
      update_post_meta($module->ID, 'manual_inputs', $this->db->get_var($query) ?? 0);
    
      return true;
    }

    /**
     * Get modules that should be migrated. We assume that all 
     * manualinput modules that does not have any data in the 
     * manual_inputs field should be migrated.
     * 
     * @return array
     */
    private function getModules(): array 
    {
        $postsModules = GetPostsByPostType::getPostsByPostType('mod-manualinput');

        //Get modules that does not have any data in the manual_inputs field
        $filteredPostsModules = array_filter($postsModules, function ($module) {
            if (!empty($module->ID)) {
                $dataInList = get_field($this->newKey, $module->ID);
                return empty($dataInList);
            }
            return false;
        });
        return $filteredPostsModules ?? [];
    }

}