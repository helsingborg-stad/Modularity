<?php
/*
 * Plugin Name: Modularity
 * Plugin URI: -
 * Description: Modular component system for WordPress
 * Version: 1.6.5
 * Author: Kristoffer Svanmark, Sebastian Thulin
 * Author URI: -
 * Text domain: modularity
 *
 * Copyright (C) 2016
 */

define('MODULARITY_PATH', plugin_dir_path(__FILE__));
define('MODULARITY_URL', plugins_url('', __FILE__));

define('MODULARITY_TEMPLATE_PATH', MODULARITY_PATH . 'templates/');

add_action('plugins_loaded', function () {
    load_plugin_textdomain('modularity', false, plugin_basename(dirname(__FILE__)) . '/languages');
});

require_once MODULARITY_PATH . 'source/php/Vendor/Psr4ClassLoader.php';
require_once MODULARITY_PATH . 'Public.php';

// Instantiate and register the autoloader
$loader = new Modularity\Vendor\Psr4ClassLoader();
$loader->addPrefix('Modularity', MODULARITY_PATH);
$loader->addPrefix('Modularity', MODULARITY_PATH . 'source/php/');
$loader->register();

// Start application
add_action('init', function () {
    new Modularity\App();
});

modularity_register_module(MODULARITY_PATH . 'Testmodul', 'TestModule');
