<?php
namespace AutomateWoo\SystemChecks;

use AutomateWoo\Database_Tables;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class DatabaseTablesExist
 *
 * @package AutomateWoo\SystemChecks
 */
class DatabaseTablesExist extends AbstractSystemCheck {


	/**
	 * Class constructor
	 */
	public function __construct() {
		$this->title         = __( 'Database Tables Installed', 'automatewoo' );
		$this->description   = __( 'Checks the AutomateWoo custom database tables have been installed.', 'automatewoo' );
		$this->high_priority = true;
	}


	/**
	 * Perform the check
	 */
	public function run() {

		global $wpdb;

		$tables          = $wpdb->get_results( "SHOW TABLES LIKE '{$wpdb->prefix}automatewoo_%'", ARRAY_N );
		$expected_tables = count( Database_Tables::load_includes() );

		if ( count( $tables ) >= $expected_tables ) {
			return $this->success();
		}

		return $this->error( __( 'Tables could not be installed.', 'automatewoo' ) );
	}

}
