<?php
/**
 * Main class for Affiliate For WooCommerce Install
 *
 * @package     affiliate-for-woocommerce/includes/
 * @since       1.0.0
 * @version     1.0.7
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'AFWC_Install' ) ) {

	/**
	 * Class to handle installation of the plugin
	 */
	class AFWC_Install {

		/**
		 * Constructor
		 */
		public function __construct() {
			$this->install();
		}

		/**
		 * Function to handle install process
		 */
		public function install() {
			// Change schema of afwc_hits table to introduce the primary key.
			$this->maybe_add_primary_key_in_hits_table();
			$this->create_tables();
		}

		/**
		 * Function to create tables
		 */
		public function create_tables() {
			global $wpdb;

			$collate = '';

			if ( $wpdb->has_cap( 'collation' ) ) {
				if ( $wpdb->has_cap( 'collation' ) ) {
					$collate = $wpdb->get_charset_collate();
				}
				if ( ! empty( $wpdb->collate ) ) {
					$collate .= " COLLATE $wpdb->collate";
				}
			}

			include_once ABSPATH . 'wp-admin/includes/upgrade.php';

			$afwc_tables = "
							CREATE TABLE {$wpdb->prefix}afwc_hits (
								id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
								affiliate_id bigint(20) UNSIGNED NOT NULL DEFAULT 0,
								datetime datetime NOT NULL,
								ip varchar(100) DEFAULT NULL,
								user_id bigint(20) UNSIGNED DEFAULT 0,
								count bigint(20) DEFAULT 1,
								type enum('link', 'coupon') DEFAULT 'link',
								campaign_id int(20) UNSIGNED DEFAULT 0,
								user_agent text DEFAULT NULL,
								url text DEFAULT NULL,
								PRIMARY KEY (id)
							) $collate;
							CREATE TABLE {$wpdb->prefix}afwc_referrals (
							  	referral_id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
								affiliate_id bigint(20) unsigned NOT NULL default '0',
								post_id bigint(20) unsigned NOT NULL default '0',
								datetime datetime NOT NULL,
								description varchar(5000),
								ip int(10) unsigned default NULL,
								user_id bigint(20) unsigned default NULL,
								amount decimal(18,2) default NULL,
								currency_id char(3) default NULL,
								data longtext default NULL,
								status varchar(10) NOT NULL DEFAULT 'pending',
								type varchar(10) NULL,
								reference varchar(100) DEFAULT NULL,
								campaign_id int(20) DEFAULT NULL,
								order_status VARCHAR(20) DEFAULT NULL,
								hit_id bigint(20) UNSIGNED NOT NULL DEFAULT 0,
								PRIMARY KEY  (referral_id),
								KEY afwc_referrals_apd (affiliate_id, post_id, datetime),
								KEY afwc_referrals_da (datetime, affiliate_id),
								KEY afwc_referrals_sda (status, datetime, affiliate_id),
								KEY afwc_referrals_tda (type, datetime, affiliate_id),
								KEY afwc_referrals_ref (reference(20))
							) $collate;
							CREATE TABLE {$wpdb->prefix}afwc_payouts (
							  	payout_id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
								affiliate_id bigint(20) unsigned NOT NULL default '0',
								datetime datetime NOT NULL,
								amount decimal(18,2) default NULL,
								currency char(3) default NULL,
								payout_notes varchar(5000),
								payment_gateway varchar(20) NULL,
								receiver varchar(50) NULL,
								type varchar(10) NULL,
								PRIMARY KEY  (payout_id),
								KEY afwc_payouts_da (datetime, affiliate_id),
								KEY afwc_payouts_tda (type, datetime, affiliate_id)
							) $collate;
							CREATE TABLE {$wpdb->prefix}afwc_payout_orders (
							  	payout_id bigint(20) UNSIGNED NOT NULL,
								post_id bigint(20) unsigned NOT NULL default '0',
								amount decimal(18,2) default NULL,
								KEY afwc_payout_orders (payout_id, post_id)
							) $collate;
							CREATE TABLE {$wpdb->prefix}afwc_campaigns (
								id int(20) UNSIGNED NOT NULL AUTO_INCREMENT,
								title varchar(255) NOT NULL,
								slug varchar(255) NOT NULL,
								target_link varchar(255) NOT NULL,
								short_description mediumtext NOT NULL,
								body longtext NOT NULL,
								status enum('Draft', 'Active') DEFAULT 'Draft',
								meta_data longtext NOT NULL,
								PRIMARY KEY  (id)
							) $collate;
							CREATE TABLE {$wpdb->prefix}afwc_commission_plans (
								id int(20) UNSIGNED NOT NULL AUTO_INCREMENT,
								name varchar(255) NOT NULL,
								rules longtext NOT NULL,
								amount decimal(18,2) DEFAULT NULL,
								type enum ('Flat', 'Percentage' ) DEFAULT 'Percentage',
								status enum ('Active', 'Draft', 'Trash') DEFAULT 'Draft',
								apply_to varchar(20) DEFAULT NULL,
								action_for_remaining varchar(20) DEFAULT NULL,
								no_of_tiers varchar(20) DEFAULT NULL,
								distribution varchar(50) DEFAULT NULL,
								PRIMARY KEY  (id)
							) $collate;
							";

			dbDelta( $afwc_tables );
		}

		/**
		 * Add the primary key to the afwc_hits table.
		 * This is a fallback method of dbDelta() function as this WordPress function is not able to handle primary key changes and IF NOT EXISTS checks.
		 *
		 * @link https://github.com/woocommerce/woocommerce/issues/21534
		 * @link https://core.trac.wordpress.org/ticket/40357
		 *
		 * @return void.
		 */
		public function maybe_add_primary_key_in_hits_table() {
			global $wpdb;

			// Return if the table does not exists.
			if ( empty( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $wpdb->prefix . 'afwc_hits' ) ) ) ) ) { // phpcs:ignore
				return;
			}

			$cols_from_table = $wpdb->get_col( "SHOW COLUMNS FROM {$wpdb->prefix}afwc_hits" ); // phpcs:ignore
			// Return if the colum is exists.
			if ( ! is_array( $cols_from_table ) || in_array( 'id', $cols_from_table, true ) ) {
				return;
			}

			// Run the alter table query.
			$wpdb->query( "ALTER TABLE {$wpdb->prefix}afwc_hits ADD COLUMN id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST" ); // phpcs:ignore
		}

	}

}

return new AFWC_Install();
