<?php
/**
 * PHPUnit bootstrap file
 *
 * @package Thrive_Apprentice
 */

! defined( 'TVE_UNIT_TESTS_RUNNING' ) ? define( 'TVE_UNIT_TESTS_RUNNING', true ) : null;

$_tests_dir = getenv( 'WP_TESTS_DIR' );

if ( ! $_tests_dir ) {
	$_tests_dir = rtrim( sys_get_temp_dir(), '/\\' ) . '/wordpress-tests-lib';
}

if ( ! file_exists( $_tests_dir . '/includes/functions.php' ) ) {
	echo "Could not find $_tests_dir/includes/functions.php, have you run bin/install-wp-tests.sh ?" . PHP_EOL; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	exit( 1 );
}

error_reporting( E_ALL );
ini_set( 'display_errors', 1 );

// Give access to tests_add_filter() function.
require_once $_tests_dir . '/includes/functions.php';

function tva_load_dash_version() {
	$tve_dash_path      = dirname( __FILE__, 2 );
	$tve_dash_file_path = $tve_dash_path . '/version.php';

	if ( is_file( $tve_dash_file_path ) ) {
		$version                                  = require_once( $tve_dash_file_path );
		$GLOBALS['tve_dash_versions'][ $version ] = array(
			'path'   => $tve_dash_path . '/thrive-dashboard.php',
			'folder' => '/',
			'from'   => 'plugins',
		);
	}
}

tests_add_filter( 'muplugins_loaded', 'tva_load_dash_version' );

// Start up the WP testing environment.
require $_tests_dir . '/includes/bootstrap.php';

require __DIR__ . '/td-abstract-testcase.php';
