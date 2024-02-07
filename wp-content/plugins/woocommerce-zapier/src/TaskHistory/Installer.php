<?php

namespace OM4\WooCommerceZapier\TaskHistory;

use OM4\WooCommerceZapier\Helper\WordPressDB;
use OM4\WooCommerceZapier\Logger;
use OM4\WooCommerceZapier\TaskHistory\Task\Task;
use OM4\WooCommerceZapier\TaskHistory\Task\TaskDataStore;

defined( 'ABSPATH' ) || exit;

/**
 * Stores task history for WooCommerce Zapier outgoing data (Triggers),
 * and incoming data (actions).
 *
 * @since 2.0.0
 */
class Installer {

	/**
	 * WordPressDB instance.
	 *
	 * @var WordPressDB
	 */
	protected $wp_db;

	/**
	 * TaskDataStore instance.
	 *
	 * @var TaskDataStore
	 */
	protected $task_data_store;

	/**
	 * Task History database table name.
	 *
	 * @var string
	 */
	protected $db_table;

	/**
	 * Logger instance.
	 *
	 * @var Logger
	 */
	protected $logger;

	/**
	 * Constructor.
	 *
	 * @param Logger        $logger     The Logger.
	 * @param WordPressDB   $wp_db       WordPressDB instance.
	 * @param TaskDataStore $data_store WordPressDB instance.
	 */
	public function __construct( Logger $logger, WordPressDB $wp_db, TaskDataStore $data_store ) {
		$this->logger          = $logger;
		$this->wp_db           = $wp_db;
		$this->db_table        = $data_store->get_table_name();
		$this->task_data_store = $data_store;
	}

	/**
	 * Instructs the installer functionality to initialise itself.
	 *
	 * @return void
	 */
	public function initialise() {
		add_action( 'wc_zapier_db_upgrade_v_5_to_6', array( $this, 'install_database_table' ) );
		add_action( 'wc_zapier_db_upgrade_v_13_to_14', array( $this, 'delete_cron_jobs' ) );
		add_action( 'wc_zapier_db_upgrade_v_13_to_14', array( $this, 'update_messages_to_remove_view_edit_zap_link' ) );
		add_action( 'wc_zapier_db_upgrade_v_14_to_15', array( $this, 'install_database_table' ) );
		add_action( 'wc_zapier_db_upgrade_v_15_to_16', array( $this, 'install_database_table' ) );
		add_action( 'wc_zapier_db_upgrade_v_16_to_17', array( $this, 'alter_table_for_child_resource_support' ) );
		add_action( 'wc_zapier_db_upgrade_v_17_to_18', array( $this, 'alter_table_for_child_resource_support' ) );
		add_action( 'wc_zapier_db_upgrade_v_18_to_19', array( $this, 'install_database_table' ) );
		add_action( 'wc_zapier_db_upgrade_v_18_to_19', array( $this, 'set_status_column_for_old_records' ) );
	}

	/**
	 * Installs (or updates) the database table where history is stored.
	 *
	 * @return void
	 */
	public function install_database_table() {
		if ( $this->database_table_exists() ) {
			/*
			 * WordPress' dbDelta() function does not support renaming columns,
			 * so we need to do this manually first. Otherwise, the existing
			 * `variation_id` and `type` columns will be left as-is and new columns created.
			 */
			$this->alter_table_for_child_resource_support();
		}

		$collate = '';

		if ( $this->wp_db->has_cap( 'collation' ) ) {
			$collate = $this->wp_db->get_charset_collate();
		}

		$schema = <<<SQL
CREATE TABLE {$this->db_table} (
  history_id bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  status VARCHAR(256) NOT NULL,
  date_time datetime NOT NULL,
  webhook_id bigint UNSIGNED,
  resource_type varchar(32) NOT NULL,
  resource_id bigint UNSIGNED NOT NULL,
  child_type varchar(32) NOT NULL,
  child_id bigint UNSIGNED NOT NULL DEFAULT 0,
  message text NOT NULL,
  event_type varchar(32) NOT NULL,
  event_topic varchar(128) NOT NULL,
  PRIMARY KEY  (history_id),
  KEY resource_id_and_type (resource_id,resource_type),
  KEY status (status)
) $collate
SQL;

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		$result = dbDelta( $schema );

		if ( ! $this->database_table_exists() ) {
			$this->logger->critical(
				'Error creating history database table (%s). Error: %s',
				array(
					$this->db_table,
					isset( $result[ $this->db_table ] ) ? $result[ $this->db_table ] : '',
				)
			);
		}
	}

	/**
	 * Alter the database table to support various resources having children.
	 *
	 * @since 2.8.0
	 *
	 * @return void
	 */
	public function alter_table_for_child_resource_support() {
		// Rename `variation_id` to `child_id`.
		if ( $this->wp_db->get_var( "SHOW COLUMNS FROM {$this->db_table} LIKE 'variation_id'" ) ) {
			if ( $this->wp_db->get_var( "SHOW COLUMNS FROM {$this->db_table} LIKE 'child_id'" ) ) {
				// Both new and old columns exist, so drop the new one, so we can rename the old one.
				$this->wp_db->query( "ALTER TABLE {$this->db_table} DROP COLUMN `child_id`" );
				$this->logger->notice( 'Dropped `child_id` column before renaming `variation_id`.' );
			}
			$this->wp_db->query( "ALTER TABLE {$this->db_table} CHANGE COLUMN `variation_id` `child_id` bigint UNSIGNED NOT NULL DEFAULT 0" );
			$this->logger->info( 'Renamed `variation_id` column to `child_id`.' );
		}
		// Add `child_type` column.
		if ( ! $this->wp_db->get_var( "SHOW COLUMNS FROM {$this->db_table} LIKE 'child_type'" ) ) {
			$this->wp_db->query(
				"ALTER TABLE {$this->db_table} ADD `child_type` varchar(32) NOT NULL AFTER `resource_id`"
			);
			$this->logger->info( 'Added `child_type` column.' );
		}
		// Rename `type` to `event_type`.
		if ( $this->wp_db->get_var( "SHOW COLUMNS FROM {$this->db_table} LIKE 'type'" ) ) {
			if ( $this->wp_db->get_var( "SHOW COLUMNS FROM {$this->db_table} LIKE 'event_type'" ) ) {
				// Both new and old columns exist, so drop the new one, so we can rename the old one.
				$this->wp_db->query( "ALTER TABLE {$this->db_table} DROP COLUMN `event_type`" );
				$this->logger->notice( 'Dropped `event_type` column before renaming `type`.' );
			}
			$this->wp_db->query( "ALTER TABLE {$this->db_table} CHANGE COLUMN `type` `event_type` varchar(32) NOT NULL" );
			$this->logger->info( 'Renamed `type` column to `event_type`.' );
		}
		// Add `event_topic` column.
		if ( ! $this->wp_db->get_var( "SHOW COLUMNS FROM {$this->db_table} LIKE 'event_topic'" ) ) {
			$this->wp_db->query(
				"ALTER TABLE {$this->db_table} ADD `event_topic` varchar(128) NOT NULL AFTER `event_type`"
			);
			$this->logger->info( 'Added `event_topic` column.' );
		}
	}

	/**
	 * Ensure all existing Task History records with no status, get their status set to 'success'.
	 *
	 * @since 2.10.0
	 *
	 * @return void
	 */
	public function set_status_column_for_old_records() {
		$num_records = $this->wp_db->query( "UPDATE {$this->db_table} SET status = '" . Task::STATUS_SUCCESS . "' WHERE status=''" );
		$this->logger->info( "Set the `status` column to 'success' for %d existing record(s).", "$num_records" );
	}

	/**
	 * Delete Task History related Action Scheduler cron job(s).
	 *
	 * Executed during plugin deactivation.
	 *
	 * @return void
	 */
	public function delete_cron_jobs() {
		WC()->queue()->cancel( 'wc_zapier_history_cleanup' );
	}

	/**
	 * Whether the database table exists.
	 *
	 * @return bool
	 */
	public function database_table_exists() {
		$query = $this->wp_db->prepare( 'SHOW TABLES LIKE %s', $this->db_table );
		if ( ! is_string( $query ) ) {
			return false;
		}
		return $this->db_table === $this->wp_db->get_var( $query );
	}

	/**
	 * Update all existing messages in the Task History Table,
	 * removing the `View/Edit Zap` link.
	 *
	 * @since 2.3.0
	 *
	 * @return void
	 */
	public function update_messages_to_remove_view_edit_zap_link() {
		// Bulk update all messages, and only keep the message text up to the <br /> tag.
		$result = $this->wp_db->query(
			'UPDATE ' . $this->task_data_store->get_table_name() . " SET `message` = (SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(message,'<br />',1),'<br />',-1) AS columName) WHERE message LIKE '%<br />%'"
		);
		$this->logger->info( '%d task history record(s) updated to remove View/Edit Zap link.', array( $result ) );
	}
}
