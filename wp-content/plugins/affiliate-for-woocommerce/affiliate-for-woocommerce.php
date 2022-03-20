<?php
/**
 * Plugin Name: Affiliate For WooCommerce
 * Plugin URI: https://woocommerce.com/products/affiliate-for-woocommerce/
 * Description: The best affiliate management plugin for WooCommerce. Track, manage and payout affiliate commissions easily.
 * Version: 4.0.1
 * Author: StoreApps
 * Author URI: http://www.storeapps.org/
 * Developer: StoreApps
 * Developer URI: https://www.storeapps.org/
 * Requires at least: 5.0.0
 * Tested up to: 5.9.2
 * WC requires at least: 3.6.0
 * WC tested up to: 6.3.1
 * Text Domain: affiliate-for-woocommerce
 * Domain Path: /languages/
 * Woo: 4830848:0f21ae7f876a631d2db8952926715502
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * Copyright (c) 2019-2022 StoreApps All rights reserved.
 *
 * @package affiliate-for-woocommerce
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

register_activation_hook( __FILE__, 'affiliate_for_woocommerce_activate' );

/**
 * Actions to perform on activation of the plugin
 */
function affiliate_for_woocommerce_activate() {
	include_once 'includes/class-afwc-install.php';
	add_option( 'afwc_default_commission_status', 'unpaid', '', 'no' );
	add_option( 'afwc_do_activation_redirect', true );
	add_option( 'afwc_pname', 'ref', '', 'no' );
	update_option( 'afwc_flushed_rules', 'notfound', 'no' );
}

/**
 * Handle redirect
 */
function afwc_redirect() {
	if ( get_option( 'afwc_do_activation_redirect', false ) ) {
		delete_option( 'afwc_do_activation_redirect' );
		wp_safe_redirect( admin_url( 'admin.php?page=affiliate-for-woocommerce-documentation' ) );
		exit;
	}
}
add_action( 'admin_init', 'afwc_redirect' );

/**
 * Load Affiliate For WooCommerce only if woocommerce is activated
 */
function initialize_affiliate_for_woocommerce() {
	define( 'AFWC_PLUGIN_FILE', __FILE__ );
	if ( ! defined( 'AFWC_PLUGIN_DIRPATH' ) ) {
		define( 'AFWC_PLUGIN_DIRPATH', dirname( __FILE__ ) );
	}

	// To insert the option on plugin update.
	$afwc_admin_contact_email_address = get_option( 'new_admin_email', '' );
	$afwc_admin_contact_email_address = empty( $afwc_admin_contact_email_address ) ? get_option( 'admin_email', '' ) : $afwc_admin_contact_email_address;
	add_option( 'afwc_contact_admin_email_address', $afwc_admin_contact_email_address, '', 'no' );

	$active_plugins = (array) get_option( 'active_plugins', array() );
	if ( is_multisite() ) {
		$active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
	}

	if ( ( in_array( 'woocommerce/woocommerce.php', $active_plugins, true ) || array_key_exists( 'woocommerce/woocommerce.php', $active_plugins ) ) ) {
		include_once 'includes/class-affiliate-for-woocommerce.php';
		$GLOBALS['affiliate_for_woocommerce'] = Affiliate_For_WooCommerce::get_instance();
	} else {
		if ( is_admin() ) {
			?>
			<div class="notice notice-error">
				<p><?php echo esc_html__( 'Affiliate for WooCommerce requires WooCommerce to be activated.', 'affiliate-for-woocommerce' ); ?></p>
			</div>
			<?php
		}
	}
}
add_action( 'plugins_loaded', 'initialize_affiliate_for_woocommerce' );
