<?php
/*
 * Plugin Name: Modularity Image Module
 * Plugin URI: -
 * Description: Image modue for Modularity
 * Version: 1.0
 * Author: Modularity
 */

define('IMAGE_MODULE_PATH', plugin_dir_path(__FILE__));

add_action('Modularity', function () {
    require_once IMAGE_MODULE_PATH . 'ImageModule.php';
    new \BasicModule\Image();
});
