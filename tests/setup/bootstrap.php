<?php
/**
 * PHPUnit bootstrap file.
 *
 * @package Modularity
 */

$tmpDir = sys_get_temp_dir();
$testsDir = "{$tmpDir}/wordpress-tests-lib";

// Forward custom PHPUnit Polyfills configuration to PHPUnit bootstrap file.
$phpUnitPolyfillsPath = getenv( 'WP_TESTS_PHPUNIT_POLYFILLS_PATH' );
if ( false !== $phpUnitPolyfillsPath ) {
	define( 'WP_TESTS_PHPUNIT_POLYFILLS_PATH', $phpUnitPolyfillsPath );
}

if ( ! file_exists( "{$testsDir}/includes/functions.php" ) ) {
	echo "Could not find {$testsDir}/includes/functions.php, have you run tests/setup/install-wp-tests.sh ?" . PHP_EOL; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	exit( 1 );
}

// Give access to tests_add_filter() function.
require_once "{$testsDir}/includes/functions.php";

/**
 * Manually load the plugin being tested.
 */
$manuallyLoadPlugin = function () use ( $tmpDir ) {
	// Load required plugin advanced-custom-fields-pro
	require  "{$tmpDir}/advanced-custom-fields-pro/acf.php";
	require dirname( dirname( __FILE__ ) ) . '/../modularity.php';
};

tests_add_filter( 'muplugins_loaded', $manuallyLoadPlugin );

// Start up the WP testing environment.
require "{$testsDir}/includes/bootstrap.php";
