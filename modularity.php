<?php
/*
 * Plugin Name: Modularity
 * Plugin URI: -
 * Description: Module system for post types
 * Version: 0.1
 * Author: Kristoffer Svanmark
 * Author URI: -
 *
 * Copyright (C) 2015
 */

define('MODULARITY_PATH', plugin_dir_path(__FILE__));
define('MODULARITY_URL', plugins_url('', __FILE__));

define('MODULARITY_TEMPLATE_PATH', MODULARITY_PATH . 'templates/');

load_plugin_textdomain('modular', false, plugin_basename(dirname(__FILE__)) . '/languages');

require_once MODULARITY_PATH . 'source/php/Vendor/Psr4ClassLoader.php';
require_once MODULARITY_PATH . 'Public.php';

// Instantiate and register the autoloader
$loader = new Modularity\Vendor\Psr4ClassLoader();
$loader->addPrefix('Modularity', MODULARITY_PATH);
$loader->addPrefix('Modularity', MODULARITY_PATH . 'source/php/');
$loader->register();

// Start application
new Modularity\App();
