<?php

namespace OM4\WooCommerceZapier\TaskHistory;

use OM4\WooCommerceZapier\Helper\WordPressDB;
use OM4\WooCommerceZapier\Logger;

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

		// Daily Cleanup Cron Installation.
		add_action( 'wc_zapier_db_upgrade_v_7_to_8', array( $this, 'create_cron_jobs' ) );
		add_action( 'wc_zapier_plugin_deactivate', array( $this, 'delete_cron_jobs' ) );

		// Daily Cleanup Cron Execution.
		add_action( 'wc_zapier_history_cleanup', array( $this->task_data_store, 'cleanup_old_tasks' ) );
	}

	/**
	 * Installs (or updates) the database table where history is stored.
	 *
	 * @return bool
	 */
	public function install_database_table() {
		$collate = '';

		if ( $this->wp_db->has_cap( 'collation' ) ) {
			$collate = $this->wp_db->get_charset_collate();
		}

		$schema = <<<SQL
CREATE TABLE {$this->db_table} (
  history_id bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  date_time datetime NOT NULL,
  webhook_id bigint UNSIGNED,
  resource_type varchar(32) NOT NULL,
  resource_id bigint UNSIGNED NOT NULL,
  message text NOT NULL,
  type varchar(32) NOT NULL,
  PRIMARY KEY  (history_id)
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
			return false;
		}

		return true;

	}

	/**
	 * Create Task History related Action Scheduler cron job(s).
	 *
	 * Executed during initial plugin activation, and when an existing user upgrades.
	 *
	 * @return void
	 */
	public function create_cron_jobs() {
		if ( ! did_action( 'init' ) ) {
			// Activation has ran too early before Action Scheduler is correctly initialised.
			return;
		}
		$this->delete_cron_jobs();
		WC()->queue()->schedule_recurring( time() + ( 3 * HOUR_IN_SECONDS ), DAY_IN_SECONDS, 'wc_zapier_history_cleanup', array(), 'wc-zapier' );
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
	 * Whether or not the Installer database table exists.
	 *
	 * @return bool
	 */
	public function database_table_exists() {
		return $this->db_table === $this->wp_db->get_var( strval( $this->wp_db->prepare( 'SHOW TABLES LIKE %s', $this->db_table ) ) );
	}
}
