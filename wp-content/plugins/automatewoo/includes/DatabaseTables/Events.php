<?php
// phpcs:ignoreFile

namespace AutomateWoo\DatabaseTables;

use AutomateWoo\Database_Table;


if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Events database table class.
 *
 * @since 3.4.0
 */
class Events extends Database_Table {

	function __construct() {
		global $wpdb;

		$this->name = $wpdb->prefix . 'automatewoo_events';
		$this->primary_key = 'id';
	}


	/**
	 * @return array
	 */
	function get_columns() {
		return [
			'id' => '%d',
			'hook' => '%s',
			'status' => '%s',
			'args' => '%s',
			'args_hash' => '%s',
			'date_scheduled' => '%s',
			'date_created' => '%s',
		];
	}


	/**
	 * @return string
	 */
	function get_install_query() {
		return "CREATE TABLE {$this->get_name()} (
			id bigint(20) NOT NULL AUTO_INCREMENT,
			hook varchar(100) NOT NULL default '',
			status varchar(50) NOT NULL default '',
			args longtext NOT NULL default '',
			args_hash varchar(32) NOT NULL default '',
			date_scheduled datetime NULL,
			date_created datetime NULL,
			PRIMARY KEY  (id),
			KEY status (status),
			KEY hook (hook),
			KEY date_scheduled (date_scheduled)
			) {$this->get_collate()};";
	}
}
