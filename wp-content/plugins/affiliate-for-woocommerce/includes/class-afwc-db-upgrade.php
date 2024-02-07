<?php
/**
 * Class for upgrading Database of Affiliate For WooCommerce
 *
 * @package     affiliate-for-woocommerce/includes/
 * @since       1.2.1
 * @version     1.4.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'AFWC_DB_Upgrade' ) ) {

	/**
	 * Class for upgrading Database of Affiliate For WooCommerce
	 */
	class AFWC_DB_Upgrade {

		/**
		 * Variable to hold instance of this class
		 *
		 * @var $instance
		 */
		private static $instance = null;

		/**
		 * Get single instance of this class
		 *
		 * @return AFWC_DB_Upgrade Singleton object of this class
		 */
		public static function get_instance() {

			// Check if instance is already exists.
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Constructor
		 */
		private function __construct() {
			$db_upgrading = get_option( 'afwc_db_upgrade_running', false );

			if ( empty( $db_upgrading ) ) {
				add_action( 'init', array( $this, 'initialize_db_upgrade' ) );
			}

			// add update (v2.8.3) date for feedback.
			$date                = gmdate( 'Y-m-d', Affiliate_For_WooCommerce::get_offset_timestamp() );
			$feedback_start_date = get_option( 'afwc_feedback_start_date', false );
			if ( empty( $feedback_start_date ) ) {
				update_option( 'afwc_feedback_start_date', $date, 'no' );
			}
		}

		/**
		 * Initialize database upgrade
		 * Will only have one entry point to run all upgrades
		 */
		public function initialize_db_upgrade() {
			$current_db_version = get_option( '_afwc_current_db_version' );
			if ( version_compare( $current_db_version, '1.3.4', '<' ) || empty( $current_db_version ) ) {
				update_option( 'afwc_db_upgrade_running', true, 'no' );
				$this->do_db_upgrade();
			}
		}

		/**
		 * Do the database upgrade
		 */
		public function do_db_upgrade() {
			global $wpdb, $blog_id;

			// For multisite table prefix.
			if ( is_multisite() ) {
				$blog_ids = $wpdb->get_col( "SELECT blog_id FROM {$wpdb->blogs}", 0 ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
			} else {
				$blog_ids = array( $blog_id );
			}

			foreach ( $blog_ids as $id ) {

				if ( is_multisite() ) {
					switch_to_blog( $id ); // @codingStandardsIgnoreLine
				}

				// All the DB update functions should be called from here since they should run for each blog id.
				if ( false === get_option( '_afwc_current_db_version' ) || '' === get_option( '_afwc_current_db_version' ) ) {
					$this->upgrade_to_1_2_1();
				}

				if ( '1.2.1' === get_option( '_afwc_current_db_version' ) ) {
					$this->upgrade_to_1_2_2();
				}

				if ( '1.2.2' === get_option( '_afwc_current_db_version' ) ) {
					$this->upgrade_to_1_2_3();
				}

				if ( '1.2.3' === get_option( '_afwc_current_db_version' ) ) {
					$this->upgrade_to_1_2_4();
				}

				if ( '1.2.4' === get_option( '_afwc_current_db_version' ) ) {
					$this->upgrade_to_1_2_5();
				}

				if ( '1.2.5' === get_option( '_afwc_current_db_version' ) ) {
					$this->upgrade_to_1_2_6();
				}

				if ( '1.2.6' === get_option( '_afwc_current_db_version' ) ) {
					$this->upgrade_to_1_2_7();
				}

				if ( '1.2.7' === get_option( '_afwc_current_db_version' ) ) {
					$this->upgrade_to_1_2_8();
				}

				if ( '1.2.8' === get_option( '_afwc_current_db_version' ) ) {
					$this->upgrade_to_1_2_9();
				}

				if ( '1.2.9' === get_option( '_afwc_current_db_version' ) ) {
					$this->upgrade_to_1_3_0();
				}

				if ( '1.3.0' === get_option( '_afwc_current_db_version' ) ) {
					$this->upgrade_to_1_3_1();
				}

				if ( '1.3.1' === get_option( '_afwc_current_db_version' ) ) {
					$this->upgrade_to_1_3_2();
				}

				if ( '1.3.2' === get_option( '_afwc_current_db_version' ) ) {
					$this->upgrade_to_1_3_3();
				}

				if ( '1.3.3' === get_option( '_afwc_current_db_version' ) ) {
					$this->upgrade_to_1_3_4();
				}

				update_option( 'afwc_db_upgrade_running', false, 'no' );

				if ( is_multisite() ) {
					restore_current_blog();
				}
			}
		}

		/**
		 * Function to upgrade the database to version 1.2.1
		 */
		public function upgrade_to_1_2_1() {
			global $wpdb;

			$offset = (int) ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS );

			$afwc_hits_table = $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $wpdb->prefix . 'afwc_hits' ) . '%' ) ); // phpcs:ignore
			if ( ! empty( $afwc_hits_table ) ) {
				$wpdb->query( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
					$wpdb->prepare(
						"UPDATE {$wpdb->prefix}afwc_hits
							SET datetime = DATE_ADD( datetime, INTERVAL %d SECOND )",
						$offset
					)
				);
			}

			$afwc_payouts_table = $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $wpdb->prefix . 'afwc_payouts' ) . '%' ) ); // phpcs:ignore
			if ( ! empty( $afwc_payouts ) ) {
				$wpdb->query(  // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
					$wpdb->prepare(
						"UPDATE {$wpdb->prefix}afwc_payouts
							SET datetime = DATE_ADD( datetime, INTERVAL %d SECOND )",
						$offset
					)
				);
			}

			$afwc_referrals_table = $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $wpdb->prefix . 'afwc_referrals' ) . '%' ) ); // phpcs:ignore
			if ( ! empty( $afwc_referrals_table ) ) {
				$wpdb->query(  // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
					$wpdb->prepare(
						"UPDATE {$wpdb->prefix}afwc_referrals
							SET datetime = DATE_ADD( datetime, INTERVAL %d SECOND )",
						$offset
					)
				);
			}

			update_option( '_afwc_current_db_version', '1.2.1', 'no' );
		}

		/**
		 * Function to upgrade the database to version 1.2.2
		 */
		public function upgrade_to_1_2_2() {
			$page_id = afwc_create_reg_form_page();

			update_option( '_afwc_current_db_version', '1.2.2', 'no' );
		}

		/**
		 * Function to upgrade the database to version 1.2.3
		 */
		public function upgrade_to_1_2_3() {
			global $wpdb;

			// create campaign table.
			$collate = '';

			if ( $wpdb->has_cap( 'collation' ) ) {
				if ( ! empty( $wpdb->charset ) ) {
					$collate .= "DEFAULT CHARACTER SET $wpdb->charset";
				}
				if ( ! empty( $wpdb->collate ) ) {
					$collate .= " COLLATE $wpdb->collate";
				}
			}
			include_once ABSPATH . 'wp-admin/includes/upgrade.php';

			$afwc_campaign_table = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}afwc_campaigns (
									  id int(20) UNSIGNED NOT NULL AUTO_INCREMENT,
									  title varchar(255) NOT NULL,
									  slug varchar(255) NOT NULL,
									  target_link varchar(255) NOT NULL,
									  short_description mediumtext NOT NULL,
									  body longtext NOT NULL,
									  status enum ('Active', 'Draft') DEFAULT 'Draft',
									  meta_data longtext NOT NULL,
									  PRIMARY KEY  (id)
									) $collate;
						";
			dbDelta( $afwc_campaign_table );

			// alter tables.
			$cols_from_hits = $wpdb->get_col( "SHOW COLUMNS FROM {$wpdb->prefix}afwc_hits" ); // phpcs:ignore
			if ( ! in_array( 'campaign_id', $cols_from_hits, true ) ) {
				$wpdb->query( "ALTER table {$wpdb->prefix}afwc_hits ADD campaign_id int(20) DEFAULT 0" );// phpcs:ignore
			}

			$cols_from_referrals = $wpdb->get_col( "SHOW COLUMNS FROM {$wpdb->prefix}afwc_referrals" ); // phpcs:ignore
			if ( ! in_array( 'campaign_id', $cols_from_referrals, true ) ) {
				$wpdb->query( "ALTER table {$wpdb->prefix}afwc_referrals ADD campaign_id int(20) DEFAULT 0" );// phpcs:ignore
			}

			// import sample campaign data.
			if ( ! class_exists( 'AFWC_Campaign_Dashboard' ) ) {
				include_once AFWC_PLUGIN_DIRPATH . '/includes/admin/class-afwc-campaign-dashboard.php';
			}
			$afwc_campaign_dashboard = AFWC_Campaign_Dashboard::get_instance();
			$sample_campaigns        = is_callable( array( $afwc_campaign_dashboard, 'get_sample_campaigns' ) ) ? $afwc_campaign_dashboard->get_sample_campaigns() : '';
			is_callable( array( $afwc_campaign_dashboard, 'add_sample_campaigns_to_db' ) ) ? $afwc_campaign_dashboard->add_sample_campaigns_to_db( $sample_campaigns ) : '';

			update_option( '_afwc_current_db_version', '1.2.3', 'no' );
		}


		/**
		 * Function to upgrade the database to version 1.2.4
		 */
		public function upgrade_to_1_2_4() {

			global $wpdb;

			// create commission plans table.
			$collate = '';

			if ( $wpdb->has_cap( 'collation' ) ) {
				if ( ! empty( $wpdb->charset ) ) {
					$collate .= "DEFAULT CHARACTER SET $wpdb->charset";
				}
				if ( ! empty( $wpdb->collate ) ) {
					$collate .= " COLLATE $wpdb->collate";
				}
			}
			include_once ABSPATH . 'wp-admin/includes/upgrade.php';

			$afwc_campaign_table = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}afwc_commission_plans (
									  id int(20) UNSIGNED NOT NULL AUTO_INCREMENT,
									  name varchar(255) NOT NULL,
									  rules longtext NOT NULL,
									  amount decimal(18,2) DEFAULT NULL,
									  type enum ('Flat', 'Percentage' ) DEFAULT 'Percentage',
									  status enum ('Active', 'Draft', 'Trash') DEFAULT 'Draft',
									  PRIMARY KEY  (id)
									) $collate;
						";
			dbDelta( $afwc_campaign_table );

			// port user commissions to rules.
			$afwc_storewide_commission      = get_option( 'afwc_storewide_commission', 0 );
			$afwc_storewide_commission      = ( ! empty( $afwc_storewide_commission ) ) ? floatval( $afwc_storewide_commission ) : 0;
			$afw_is_user_commission_enabled = get_option( 'afwc_user_commission', 'no' );
			$status                         = ( 'yes' === $afw_is_user_commission_enabled ) ? 'Active' : 'Draft';
			$user_commission_result = $wpdb->get_results( // phpcs:ignore
				"SELECT user_id, meta_value as plan FROM {$wpdb->prefix}usermeta WHERE meta_key = 'afwc_commission_rate'",
				'ARRAY_A'
			);

			$commission_plans = array();
			// create array for commission and user_id.
			foreach ( $user_commission_result as $value ) {

				$value['plan'] = maybe_unserialize( $value['plan'] );
				if ( floatval( $value['plan']['commission'] ) === $afwc_storewide_commission && 'percentage' === $value['plan']['type'] ) {
					continue;
				}
				if ( empty( $commission_plans[ $value['plan']['commission'] ] ) ) {
					$commission_plans[ $value['plan']['commission'] ] = array();
				}

				$commission_plans[ $value['plan']['commission'] ][ $value['plan']['type'] ]['user_ids'][] = $value['user_id'];

			}

			// for each commission create rule.
			foreach ( $commission_plans as $amount => $value ) {

				foreach ( $value as $type => $user_ids ) {
					$rule_data = array();
					$rule      = array();
					$rule_obj  = array();

					$rule_data['name'] = 'User commission ' . $type . '_' . $amount;

					$rule['condition'] = 'AND';

					$rule_obj['type']     = 'affiliate';
					$rule_obj['operator'] = 'in';
					$rule_obj['value']    = array_shift( $user_ids );
					$rule['rules']        = array();
					$rule['rules'][]      = $rule_obj;

					$root_rule_group              = array();
					$root_rule_group['condition'] = 'AND';
					$root_rule_group['rules'][]   = $rule;

					$rule_data['rules'] = wp_json_encode( $root_rule_group );

					$rule_data['amount'] = $amount;
					$rule_data['type']   = strtolower( $type );
					$rule_data['status'] = $status;
					$wpdb->insert( // phpcs:ignore
						$wpdb->prefix . 'afwc_commission_plans',
						$rule_data,
						array( '%s', '%s', '%s', '%s', '%s' )
					);
				}
			}
			update_option( '_afwc_current_db_version', '1.2.4', 'no' );
		}

		/**
		 * Function to upgrade the database to version 1.2.5
		 */
		public function upgrade_to_1_2_5() {
			global $wpdb;

			// alter tables.
			$cols_from_commission_plan = $wpdb->get_col( "SHOW COLUMNS FROM {$wpdb->prefix}afwc_commission_plans" ); // phpcs:ignore
			if ( ! in_array( 'apply_to', $cols_from_commission_plan, true ) ) {
				$wpdb->query( "ALTER table {$wpdb->prefix}afwc_commission_plans ADD apply_to varchar(20) DEFAULT NULL" );// phpcs:ignore
			}
			if ( ! in_array( 'action_for_remaining', $cols_from_commission_plan, true ) ) {
				$wpdb->query( "ALTER table {$wpdb->prefix}afwc_commission_plans ADD action_for_remaining varchar(20) DEFAULT NULL" );// phpcs:ignore
			}
			update_option( '_afwc_current_db_version', '1.2.5', 'no' );
		}

		/**
		 * Function to upgrade the database to version 1.2.6
		 */
		public function upgrade_to_1_2_6() {
			global $wpdb;
			// alter tables.
			$cols_from_commission_plan = $wpdb->get_col( "SHOW COLUMNS FROM {$wpdb->prefix}afwc_commission_plans" ); // phpcs:ignore
			if ( in_array( 'amount', $cols_from_commission_plan, true ) ) {
				$wpdb->query( "ALTER table {$wpdb->prefix}afwc_commission_plans MODIFY amount decimal(18,2) default NULL" );// phpcs:ignore
			}

			update_option( '_afwc_current_db_version', '1.2.6', 'no' );
		}

		/**
		 * Function to upgrade the database to version 1.2.7
		 */
		public function upgrade_to_1_2_7() {
			global $wpdb;
			// alter tables.
			$cols_from_referrals = $wpdb->get_col( "SHOW COLUMNS FROM {$wpdb->prefix}afwc_referrals" ); // phpcs:ignore
			if ( ! in_array( 'order_status', $cols_from_referrals, true ) ) {
				$wpdb->query( "ALTER table {$wpdb->prefix}afwc_referrals ADD order_status VARCHAR(20) DEFAULT NULL" );// phpcs:ignore
			} else {
				// check if order status col is already there and order status is not null then do not run migration.
				$order_with_null_status = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}afwc_referrals WHERE order_status IS NULL" );// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
				if ( 0 === absint( $order_with_null_status ) ) {
					update_option( 'afwc_migration_for_order_status_done', true, 'no' );
				}
			}
			update_option( '_afwc_current_db_version', '1.2.7', 'no' );
		}

		/**
		 * Function to upgrade the database to version 1.2.8
		 */
		public function upgrade_to_1_2_8() {
			global $wpdb;
			$default_plan = array();

			$table_name = $wpdb->prefix . 'afwc_commission_plans';

			$cols_from_commission_plan = $wpdb->get_col( "SHOW COLUMNS FROM {$wpdb->prefix}afwc_commission_plans" ); // phpcs:ignore
			if ( ! in_array( 'no_of_tiers', $cols_from_commission_plan, true ) ) {
				$wpdb->query( "ALTER table {$wpdb->prefix}afwc_commission_plans ADD no_of_tiers VARCHAR(20) default NULL" );// phpcs:ignore
			}
			if ( ! in_array( 'distribution', $cols_from_commission_plan, true ) ) {
				$wpdb->query( "ALTER table {$wpdb->prefix}afwc_commission_plans ADD distribution VARCHAR(50) default NULL" );// phpcs:ignore
			}

			$table_name_from_db = $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name ) ) );// phpcs:ignore
			if ( $table_name_from_db === $table_name ) {
				$storewide_percentage = get_option( 'afwc_storewide_commission', 0 );
				$storewide_percentage = ( ! empty( $storewide_percentage ) ) ? floatval( $storewide_percentage ) : 0;

				$default_plan['name']                 = 'Storewide Default Commission';
				$default_plan['rules']                = '';
				$default_plan['amount']               = $storewide_percentage;
				$default_plan['type']                 = 'Percentage';
				$default_plan['status']               = 'Active';
				$default_plan['apply_to']             = 'all';
				$default_plan['action_for_remaining'] = 'continue';
				$default_plan['no_of_tiers']          = '1';
				$default_plan['distribution']         = '';

				$wpdb->insert( // phpcs:ignore
					$table_name,
					$default_plan,
					array( '%s', '%s', '%f', '%s', '%s', '%s', '%s', '%s', '%s' )
				);

				$default_plan_id = $wpdb->insert_id;
				$plan_order      = get_option( 'afwc_plan_order', array() );
				$plan_order[]    = $default_plan_id;
				update_option( 'afwc_plan_order', $plan_order, 'no' );

				update_option( 'afwc_default_commission_plan_id', $default_plan_id );

				update_option( '_afwc_current_db_version', '1.2.8', 'no' );
			}
		}

		/**
		 * Function to upgrade the database to version 1.2.9
		 */
		public function upgrade_to_1_2_9() {
			global $wpdb;
			// alter tables.
			$total_hits_record      = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}afwc_hits" );// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
			$total_referrals_record = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}afwc_referrals" );// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
			$total_payouts_record   = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}afwc_payouts" );// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
			if ( ! $wpdb->get_var( "SHOW COLUMNS FROM {$wpdb->prefix}afwc_hits LIKE 'migrate_date';" ) && ( $total_hits_record > 0 ) ) {// phpcs:ignore
				$wpdb->query( "ALTER table {$wpdb->prefix}afwc_hits ADD migrate_date BOOLEAN DEFAULT NULL" );// phpcs:ignore
			}
			if ( ! $wpdb->get_var( "SHOW COLUMNS FROM {$wpdb->prefix}afwc_referrals LIKE 'migrate_date';" ) && ( $total_referrals_record > 0 )) {// phpcs:ignore
				$wpdb->query( "ALTER table {$wpdb->prefix}afwc_referrals ADD migrate_date BOOLEAN DEFAULT NULL" );// phpcs:ignore
			}
			if ( ! $wpdb->get_var( "SHOW COLUMNS FROM {$wpdb->prefix}afwc_payouts LIKE 'migrate_date';" ) && ( $total_payouts_record > 0 ) ) {// phpcs:ignore
				$wpdb->query( "ALTER table {$wpdb->prefix}afwc_payouts ADD migrate_date BOOLEAN DEFAULT NULL" );// phpcs:ignore
			}
			// check if there is any record, if not found set migration option done.
			if ( empty( $total_hits_record ) && empty( $total_referrals_record ) && empty( $total_payouts_record ) ) {
				update_option( 'afwc_dates_migration_done', 'yes', 'no' );
			}
			update_option( '_afwc_current_db_version', '1.2.9', 'no' );
		}

		/**
		 * Function to upgrade the database to version 1.3.0
		 * to update PayPal display option to yes for users using PayPal payouts.
		 */
		public function upgrade_to_1_3_0() {
			if ( 'not_found' === get_option( 'afwc_allow_paypal_email', 'not_found' ) ) {
				$afwc_paypal = is_callable( array( 'AFWC_PayPal_API', 'get_instance' ) ) ? AFWC_PayPal_API::get_instance() : null;
				if ( ! empty( $afwc_paypal ) && is_callable( array( $afwc_paypal, 'get_api_setting_status' ) ) ) {
					$paypal_api_settings = $afwc_paypal->get_api_setting_status();
					if ( ! empty( $paypal_api_settings ) && ! empty( $paypal_api_settings['value'] ) && 'yes' === $paypal_api_settings['value'] ) {
						update_option( 'afwc_allow_paypal_email', 'yes' );
					}
				}
			}
			update_option( '_afwc_current_db_version', '1.3.0', 'no' );
		}

		/**
		 * Function to upgrade the database to version 1.3.1.
		 * Update the flag for the flush rewrite rule.
		 */
		public function upgrade_to_1_3_1() {

			if ( 'not_found' === get_option( 'afwc_flushed_rules' ) ) {
				update_option( 'afwc_flushed_rules', 1, 'no' );
			} else {
				delete_option( 'afwc_flushed_rules' );
			}

			update_option( '_afwc_current_db_version', '1.3.1', 'no' );
		}

		/**
		 * Function to upgrade the database to version 1.3.2.
		 * Update afwc_hits table and afwc_referral table.
		 */
		public function upgrade_to_1_3_2() {
			global $wpdb;
			// Operation on afwc_hits table.
			$afwc_hits_table = $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $wpdb->prefix . 'afwc_hits' ) . '%' ) ); // phpcs:ignore

			// Check if table exist.
			if ( ! empty( $afwc_hits_table ) ) {

				// Check if columns exist.
				$cols_from_afwc_hits = $wpdb->get_col( "SHOW COLUMNS FROM {$wpdb->prefix}afwc_hits" ); // phpcs:ignore

				// Add column id as primary key to afwc_hits if it doesn't exist.
				if ( ! in_array( 'id', $cols_from_afwc_hits, true ) ) {
					$wpdb->query( "ALTER TABLE {$wpdb->prefix}afwc_hits ADD COLUMN id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST" ); // phpcs:ignore
				}

				// Modify column ip in afwc_hits if it exists.
				if ( in_array( 'ip', $cols_from_afwc_hits, true ) ) {
					$wpdb->query( "ALTER TABLE {$wpdb->prefix}afwc_hits MODIFY COLUMN ip VARCHAR(100) DEFAULT NULL" ); // phpcs:ignore
				}

				// Modify column user_id in afwc_hits if it exists.
				if ( in_array( 'user_id', $cols_from_afwc_hits, true ) ) {
					$wpdb->query( "ALTER TABLE {$wpdb->prefix}afwc_hits MODIFY COLUMN user_id BIGINT(20) DEFAULT 0" ); // phpcs:ignore
				}

				// Modify column count in afwc_hits if it exists.
				if ( in_array( 'count', $cols_from_afwc_hits, true ) ) {
					$wpdb->query( "ALTER TABLE {$wpdb->prefix}afwc_hits MODIFY COLUMN count BIGINT(20) DEFAULT 1" ); // phpcs:ignore
				}

				// Modify column type in afwc_hits if it exists.
				if ( in_array( 'type', $cols_from_afwc_hits, true ) ) {
					$wpdb->query( "ALTER TABLE {$wpdb->prefix}afwc_hits MODIFY COLUMN type ENUM('link', 'coupon') DEFAULT 'link'" ); // phpcs:ignore
				}

				// Modify column campaign_id in afwc_hits if it exists.
				if ( in_array( 'campaign_id', $cols_from_afwc_hits, true ) ) {
					$wpdb->query( "ALTER TABLE {$wpdb->prefix}afwc_hits MODIFY COLUMN campaign_id INT(20) UNSIGNED DEFAULT 0" ); // phpcs:ignore
				}

				// Add column user_agent to afwc_hits if it doesn't exist.
				if ( ! in_array( 'user_agent', $cols_from_afwc_hits, true ) ) {
					$wpdb->query( "ALTER TABLE {$wpdb->prefix}afwc_hits ADD COLUMN user_agent TEXT DEFAULT NULL" ); // phpcs:ignore
				}

				// Add column url to afwc_hits if it doesn't exist.
				if ( ! in_array( 'url', $cols_from_afwc_hits, true ) ) {
					$wpdb->query( "ALTER TABLE {$wpdb->prefix}afwc_hits ADD COLUMN url TEXT DEFAULT NULL" ); // phpcs:ignore
				}
			}

			// Operation on afwc_referrals table.
			$afwc_referrals_table = $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $wpdb->prefix . 'afwc_referrals' ) . '%' ) ); // phpcs:ignore

			// Check if the table exists.
			if ( ! empty( $afwc_referrals_table ) ) {
				$cols_from_referral_table = $wpdb->get_col( "SHOW COLUMNS FROM {$wpdb->prefix}afwc_referrals" ); // phpcs:ignore

				// Add new column `hit_id` if it doesn't exist.
				if ( ! in_array( 'hit_id', $cols_from_referral_table, true ) ) {
					$wpdb->query( "ALTER TABLE {$wpdb->prefix}afwc_referrals ADD COLUMN hit_id BIGINT(20) UNSIGNED NOT NULL DEFAULT 0" ); // phpcs:ignore
				}
			}

			update_option( '_afwc_current_db_version', '1.3.2', 'no' );
		}

		/**
		 * Function to upgrade the database to version 1.3.3.
		 * Add the rules column to the campaign table.
		 */
		public function upgrade_to_1_3_3() {
			global $wpdb;

			// Operation on afwc_campaigns table.
			$afwc_campaigns_table = $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $wpdb->prefix . 'afwc_campaigns' ) . '%' ) ); // phpcs:ignore

			// Check if the table exists.
			if ( ! empty( $afwc_campaigns_table ) ) {
				$cols_from_campaigns_table = $wpdb->get_col( "SHOW COLUMNS FROM {$wpdb->prefix}afwc_campaigns" ); // phpcs:ignore

				// Add new column `rules` if it doesn't exist.
				if ( ! in_array( 'rules', $cols_from_campaigns_table, true ) ) {
					$wpdb->query( "ALTER table {$wpdb->prefix}afwc_campaigns ADD COLUMN rules longtext DEFAULT NULL AFTER status" );// phpcs:ignore
				}
			}

			update_option( '_afwc_current_db_version', '1.3.3', 'no' );
		}

		/**
		 * Function to upgrade the database to version 1.3.4.
		 * Create a subscription commission rule for recurring commission.
		 */
		public static function upgrade_to_1_3_4() {

			global $wpdb;

			// Check if the class WCS_AFWC_Compatibility is available to ensure Woo Subscription is enabled.
			if ( class_exists( 'WCS_AFWC_Compatibility' ) ) {

				if ( 'no' === get_option( 'is_recurring_commission', 'no' ) ) {
					// Insert the subscription rule if recurring commission setting is disabled.

					$rule_data = array();

					// Object for subscription rule.
					$subscription_rule           = new stdClass();
					$subscription_rule->type     = 'subscription_renewal';
					$subscription_rule->operator = 'gte';
					$subscription_rule->value    = '0';

					// Object for the first group.
					$rule_group            = new stdClass();
					$rule_group->condition = 'AND';
					$rule_group->rules     = array( $subscription_rule );

					// Object for the all groups.
					$main_rule_obj            = new stdClass();
					$main_rule_obj->condition = 'AND';
					$main_rule_obj->rules     = array( $rule_group );

					$wpdb->insert( // phpcs:ignore
						$wpdb->prefix . 'afwc_commission_plans',
						array(
							'name'                 => "Do not issue recurring commission as 'Issue recurring commission?' is disabled",
							'rules'                => wp_json_encode( $main_rule_obj ),
							'amount'               => 0,
							'type'                 => 'Percentage',
							'status'               => 'Active',
							'apply_to'             => 'all',
							'action_for_remaining' => 'zero',
							'no_of_tiers'          => '1',
							'distribution'         => '',
						),
						array( '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s' )
					);

					$last_id = ! empty( $wpdb->insert_id ) ? intval( $wpdb->insert_id ) : 0;

					if ( ! empty( $last_id ) ) {
						if ( ! class_exists( 'AFWC_Commission_Dashboard' ) ) {
							$commission_plan_class = AFWC_PLUGIN_DIRPATH . '/includes/admin/class-afwc-commission-dashboard.php';
							if ( file_exists( $commission_plan_class ) ) {
								include_once $commission_plan_class;
							}
						}

						$afwc_campaign_dashboard = is_callable( array( 'AFWC_Commission_Dashboard', 'get_instance' ) ) ? AFWC_Commission_Dashboard::get_instance() : null;

						$plan_order = is_callable( array( $afwc_campaign_dashboard, 'get_commission_plans_order' ) ) ? $afwc_campaign_dashboard->get_commission_plans_order() : array();

						if ( is_array( $plan_order ) && ! empty( $plan_order ) ) {
							// Add the plan ID to the beginning of the array.
							array_unshift( $plan_order, $last_id );
						} else {
							$plan_order = array( $last_id );
						}

						update_option( 'afwc_plan_order', $plan_order, 'no' );
					}

					update_option( 'afwc_show_subscription_admin_dashboard_notice', 'yes', 'no' );
				}
			}

			update_option( '_afwc_current_db_version', '1.3.4', 'no' );
		}
	}
}

AFWC_DB_Upgrade::get_instance();
