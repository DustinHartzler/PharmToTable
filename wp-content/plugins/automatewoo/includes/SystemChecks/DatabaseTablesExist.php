<?php
// phpcs:ignoreFile

namespace AutomateWoo\SystemChecks;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class DatabaseTablesExist
 *
 * @package AutomateWoo\SystemChecks
 */
class DatabaseTablesExist extends AbstractSystemCheck {


	function __construct() {
		$this->title = __( 'Database Tables Installed', 'automatewoo' );
		$this->description = __( 'Checks the AutomateWoo custom database tables have been installed.', 'automatewoo' );
		$this->high_priority = true;
	}


	/**
	 * Perform the check
	 */
	function run() {

		global $wpdb;

		$tables = $wpdb->get_results( "SHOW TABLES LIKE '{$wpdb->prefix}automatewoo_%'", ARRAY_N );

		if ( count( $tables ) >= 10 ) {
			return $this->success();
		}

		return $this->error( __( 'Tables could not be installed.', 'automatewoo' ) );
	}

}
