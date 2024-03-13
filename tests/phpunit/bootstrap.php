<?php

/**
 * PHPUnit bootstrap file.
 *
 * @package Modularity
 */

// Load Composer autoloader.
require dirname(dirname(__FILE__)) . '/../vendor/autoload.php';

WP_Mock::bootstrap();