<?php
/*
 * Plugin Name: Modular
 * Plugin URI: -
 * Description: Module system for post types
 * Version: 0.1
 * Author: Kristoffer Svanmark
 * Author URI: -
 *
 * Copyright (C) 2015
 */

define('MODULAR_TEMPLATE_FOLDER', 'hbg-inherit');
define('MODULAR_PATH', plugin_dir_path(__FILE__));
define('MODULAR_URL', plugins_url('', __FILE__));

load_plugin_textdomain('modular', false, plugin_basename(dirname(__FILE__)) . '/languages');

require_once MODULAR_PATH . 'source/php/Vendor/Psr4ClassLoader.php';
require_once MODULAR_PATH . 'Public.php';

// Instantiate and register the autoloader
$loader = new Modular\Vendor\Psr4ClassLoader();
$loader->addPrefix('Modular', MODULAR_PATH);
$loader->addPrefix('Modular', MODULAR_PATH . 'source/php/');
$loader->register();

// Start application
new Modular\App();
