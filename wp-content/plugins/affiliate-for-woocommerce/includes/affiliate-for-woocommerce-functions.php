<?php
/**
 * Some common functions for Affiliate For WooCommerce
 *
 * @package     affiliate-for-woocommerce/includes/
 * @since       1.0.0
 * @version     1.13.2
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Automattic\WooCommerce\Internal\Utilities\URL;

/**
 * Encode affiliate id
 *
 * @param  integer $affiliate_id The affiliate id.
 * @return integer
 */
function afwc_encode_affiliate_id( $affiliate_id ) {
	return $affiliate_id;
}

/**
 * Get commission statuses
 *
 * @param string $status Commission Status.
 *
 * @return array|string
 */
function afwc_get_commission_statuses( $status = '' ) {
	$statuses = array(
		AFWC_REFERRAL_STATUS_PAID     => __( 'Paid', 'affiliate-for-woocommerce' ),
		AFWC_REFERRAL_STATUS_UNPAID   => __( 'Unpaid', 'affiliate-for-woocommerce' ),
		AFWC_REFERRAL_STATUS_REJECTED => __( 'Rejected', 'affiliate-for-woocommerce' ),
		AFWC_REFERRAL_STATUS_DRAFT    => __( 'Draft', 'affiliate-for-woocommerce' ),
	);

	// Return array of statuses if the provided status is empty.
	if ( empty( $status ) ) {
		return $statuses;
	}

	return ( ! empty( $statuses[ $status ] ) ) ? $statuses[ $status ] : '';
}

/**
 * Get commission status colors.
 *
 * @param string $status Commission Status.
 *
 * @return array|string
 */
function afwc_get_commission_status_colors( $status = '' ) {
	$colors = apply_filters(
		'afwc_commission_status_colors',
		array(
			AFWC_REFERRAL_STATUS_PAID     => 'green',
			AFWC_REFERRAL_STATUS_UNPAID   => 'orange',
			AFWC_REFERRAL_STATUS_REJECTED => 'red',
			AFWC_REFERRAL_STATUS_DRAFT    => 'gray',
		)
	);

	// Return array of colors if the provided status is empty.
	if ( empty( $status ) ) {
		return $colors;
	}

	return ( ! empty( $colors[ $status ] ) ) ? $colors[ $status ] : '';
}

/**
 * Get payout methods.
 *
 * @param string $method Payout method.
 *
 * @return array|string
 */
function afwc_get_payout_methods( $method = '' ) {
	$payout_methods = array(
		'paypal'        => esc_html__( 'PayPal', 'affiliate-for-woocommerce' ),
		'paypal-manual' => esc_html__( 'PayPal Manual', 'affiliate-for-woocommerce' ),
		'other'         => esc_html__( 'Other', 'affiliate-for-woocommerce' ),
	);

	// Return array of payout methods if method is not provided.
	if ( empty( $method ) ) {
		return $payout_methods;
	}

	return ( ! empty( $payout_methods[ $method ] ) ) ? $payout_methods[ $method ] : $method;
}

/**
 * Get table name
 *
 * @param  string $name The table.
 * @return string
 */
function afwc_get_tablename( $name ) {
	global $wpdb;
	return $wpdb->prefix . 'afwc_' . $name;
}

/**
 * Get referrer id
 *
 * @param string|int $customer The customer email address or customer's user ID.
 *
 * @return integer Return the affiliate ID, either from current customer's lifetime affiliate or cookie.
 */
function afwc_get_referrer_id( $customer = '' ) {
	// If the lifetime commission is enabled, check for a lifetime affiliate for the current customer.
	if ( 'yes' === get_option( 'afwc_enable_lifetime_commissions', 'no' ) ) {
		$customer = ! empty( $customer ) ? $customer : get_current_user_id();
		if ( ! empty( $customer ) ) {
			$ltc_affiliate = afwc_get_ltc_affiliate_by_customer( $customer );
			$affiliate_obj = ! empty( $ltc_affiliate ) ? new AFWC_Affiliate( $ltc_affiliate ) : null;

			if ( is_object( $affiliate_obj ) && is_callable( array( $affiliate_obj, 'is_ltc_enabled' ) ) && $affiliate_obj->is_ltc_enabled() ) {
				return intval( $ltc_affiliate );
			}
		}
	}

	// If there is an affiliate cookie set, return the ID of the affiliate in the cookie.
	return ! empty( $_COOKIE[ AFWC_AFFILIATES_COOKIE_NAME ] ) ? intval( wc_clean( wp_unslash( $_COOKIE[ AFWC_AFFILIATES_COOKIE_NAME ] ) ) ) : 0; // phpcs:ignore
}

/**
 * Get campaign id from cookie.
 *
 * @return integer Return the campaign id if exists in the cookie otherwise 0.
 */
function afwc_get_campaign_id() {
	return ! empty( $_COOKIE[ AFWC_CAMPAIGN_COOKIE_NAME ] ) ? intval( wc_clean( wp_unslash( $_COOKIE[ AFWC_CAMPAIGN_COOKIE_NAME ] ) ) ) : 0; // phpcs:ignore
}

/**
 * Get hit id from cookie.
 *
 * @return integer Return the hit id if exists in the cookie otherwise 0.
 */
function afwc_get_hit_id() {
	return ! empty( $_COOKIE[ AFWC_HIT_COOKIE_NAME ] ) ? intval( wc_clean( wp_unslash( $_COOKIE[ AFWC_HIT_COOKIE_NAME ] ) ) ) : 0; // phpcs:ignore
}

/**
 * Get user id based on affiliate id
 *
 * @param  integer $affiliate_id The affiliate id.
 * @return integer
 */
function afwc_get_user_id_based_on_affiliate_id( $affiliate_id ) {
	global $wpdb;

	$afwc_affiliates_users = afwc_get_tablename( 'affiliates_users' );
	$is_table              = $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $afwc_affiliates_users ) ); // phpcs:ignore

	if ( ! empty( $is_table ) ) {
		if ( is_numeric( $affiliate_id ) ) {
			$result = $wpdb->get_var( $wpdb->prepare( "SELECT user_id FROM {$wpdb->prefix}afwc_affiliates_users WHERE affiliate_id = %d ", $affiliate_id ) ); // phpcs:ignore
		} else {
			$result            = 0;
			$results           = $wpdb->get_results( "SELECT user_id, MD5( affiliate_id ) AS affiliate_id_md5 FROM {$wpdb->prefix}afwc_affiliates_users", ARRAY_A ); // phpcs:ignore
			$user_to_affiliate = array();
			foreach ( $results as $result ) {
				$user_to_affiliate[ $result['user_id'] ] = $result['affiliate_id_md5'];
			}
			$user_id = array_search( $affiliate_id, $user_to_affiliate, true );
			if ( false !== $user_id ) {
				$result = $user_id;
			}
		}
	}

	if ( ! empty( $result ) ) {
		$affiliate_id = $result;
	}

	$user = get_user_by( 'id', $affiliate_id );
	if ( $user ) {
		return $affiliate_id;
	} else {
		return '';
	}
}

/**
 * Get affiliate id based on user id
 *
 * @param  integer $user_id The user id.
 * @return integer
 */
function afwc_get_affiliate_id_based_on_user_id( $user_id ) {
	global $wpdb;

	$afwc_affiliates_users = afwc_get_tablename( 'affiliates_users' );
	$is_table              = $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $afwc_affiliates_users ) ); // phpcs:ignore

	if ( ! empty( $is_table ) ) {
		$result = $wpdb->get_var( $wpdb->prepare( "SELECT affiliate_id FROM {$wpdb->prefix}afwc_affiliates_users WHERE user_id = %d ", $user_id ) ); // phpcs:ignore
		if ( ! empty( $result ) ) {
			$user_id = $result;
		}
	}

	return $user_id;
}

/**
 * Check if a provided plugin is active or not
 *
 * @param  string $plugin The plugin to check.
 * @return boolean
 */
function afwc_is_plugin_active( $plugin = '' ) {
	if ( ! empty( $plugin ) ) {
		if ( function_exists( 'is_plugin_active' ) ) {
			return is_plugin_active( $plugin );
		} else {
			$active_plugins = (array) get_option( 'active_plugins', array() );
			if ( is_multisite() ) {
				$active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
			}
			if ( ( in_array( $plugin, $active_plugins, true ) || array_key_exists( $plugin, $active_plugins ) ) ) {
				return true;
			}
		}
	}
	return false;
}

/**
 * Format price value
 *
 * @param  float   $price              The price.
 * @param  integer $decimals           The number of decimals.
 * @param  string  $decimal_separator  The decimal separator.
 * @param  string  $thousand_separator The thousand separator.
 * @return string  The formatted name
 */
function afwc_format_price( $price = 0, $decimals = null, $decimal_separator = null, $thousand_separator = null ) {
	if ( is_null( $decimals ) ) {
		$decimals = afwc_get_price_decimals();
	}

	if ( empty( $decimal_separator ) ) {
		$decimal_separator = afwc_get_price_decimal_separator();
	}

	if ( empty( $thousand_separator ) ) {
		$thousand_separator = afwc_get_price_thousand_separator();
	}
	return number_format( $price, $decimals, $decimal_separator, $thousand_separator );
}

/**
 * Return the number of decimals after the decimal point.
 *
 * @return integer
 */
function afwc_get_price_decimals() {
	return absint( get_option( 'woocommerce_price_num_decimals', 2 ) );
}

/**
 * Return the thousand separator for prices
 *
 * @return string
 */
function afwc_get_price_thousand_separator() {
	$separator = wp_specialchars_decode( stripslashes( get_option( 'woocommerce_price_thousand_sep' ) ), ENT_QUOTES );
	return $separator;
}

/**
 * Return the decimal separator for prices
 *
 * @return string
 */
function afwc_get_price_decimal_separator() {
	$separator = wp_specialchars_decode( stripslashes( get_option( 'woocommerce_price_decimal_sep' ) ), ENT_QUOTES );
	return $separator ? $separator : '.';
}

/**
 * Check if the user is affiliate or not.
 *
 * @param  WP_User|int $user The user object/ID.
 *
 * @return string Return affiliate status(yes/no/pending/not_registered).
 */
function afwc_is_user_affiliate( $user = null ) {
	$is_affiliate = 'not_registered';

	// Create User object if user id is provided.
	$user = is_int( $user ) ? new WP_User( $user ) : $user;

	if ( ! $user instanceof WP_User || empty( $user->ID ) ) {
		return $is_affiliate;
	}

	// Get affiliate status from meta.
	$have_meta = get_user_meta( $user->ID, 'afwc_is_affiliate', true );

	if ( empty( $have_meta ) ) {
		// Check if the affiliate exists in the affiliate user roles.
		$user_roles   = ! empty( $user->roles ) ? $user->roles : array();
		$is_affiliate = ( true === afwc_is_affiliate_user_role( $user_roles ) ) ? 'yes' : $is_affiliate;
	} else {
		// Assign the affiliate meta.
		$is_affiliate = $have_meta;
	}

	return $is_affiliate;
}

/**
 * Function to create page for registration
 *
 * @return int
 */
function afwc_create_reg_form_page() {
	$slug    = 'affiliates';
	$page_id = '';
	if ( ! get_page_by_path( $slug ) || ! get_page_by_path( 'afwc_registration_form' ) ) {
		$reg_page = array(
			'post_type'    => 'page',
			'post_name'    => $slug,
			'post_title'   => __( 'Join our affiliate program', 'affiliate-for-woocommerce' ),
			'post_status'  => 'draft',
			'post_content' => '[afwc_registration_form]',
		);
		$page_id  = wp_insert_post( $reg_page );
	}
	return $page_id;
}

/**
 * Function to get campaign id from slug
 *
 * @param string $slug campaign slug to get campaign id.
 * @return int $campaign_id campaign id.
 */
function afwc_get_campaign_id_by_slug( $slug ) {
	global $wpdb;
	$campaign_id = $wpdb->get_var( // phpcs:ignore
		$wpdb->prepare( // phpcs:ignore
			"SELECT id FROM {$wpdb->prefix}afwc_campaigns WHERE slug = %s AND status = %s",
			array( $slug, 'Active' )
		)
	);
	$campaign_id = ! empty( $campaign_id ) ? $campaign_id : 0;
	return $campaign_id;
}

/**
 * Function to check if we have any active campaigns.
 *
 * @param bool $check_rules Whether to validate the rules.
 *
 * @return bool if we find active campaigns for the current users otherwise false.
 */
function afwc_is_campaign_active( $check_rules = true ) {
	if ( ! class_exists( 'AFWC_Campaign_Dashboard' ) ) {
		include_once AFWC_PLUGIN_DIRPATH . '/includes/admin/class-afwc-campaign-dashboard.php';
	}
	$campaign_dashboard = AFWC_Campaign_Dashboard::get_instance();
	return apply_filters(
		'afwc_is_campaign_active',
		is_callable( array( $campaign_dashboard, 'fetch_campaigns' ) ) ? ! empty(
			$campaign_dashboard->fetch_campaigns(
				array(
					'campaign_status' => 'Active',
					'affiliate_id'    => get_current_user_id(),
					'check_rules'     => $check_rules,
				)
			)
		) : false
	);
}

/**
 * Add prefix to WC order statuses
 *
 * @return $prefixed_statuses
 */
function afwc_get_prefixed_order_statuses() {
	$statuses = wc_get_is_paid_statuses();

	$prefixed_statuses = array();
	foreach ( $statuses as $key => $value ) {
		$prefixed_statuses[ $key ] = 'wc-' . $value;
	}

	return $prefixed_statuses;
}

/**
 * Get id name map for affiliate tags
 *
 * @return array $result
 */
function afwc_get_user_tags_id_name_map() {
	$result = array();
	$terms  = get_terms(
		array(
			'taxonomy'   => 'afwc_user_tags',
			'hide_empty' => false,
		)
	);
	if ( ! is_wp_error( $terms ) ) {
		foreach ( $terms as $key => $value ) {
			$result[ $value->term_id ] = $value->name;
		}
	}
	return $result;
}

/**
 * Get commission plans available
 *
 * @param string $status commission to fetch.
 *
 * @return array Return the array of plans.
 */
function afwc_get_commission_plans( $status = '' ) {
	global $wpdb;
	$status = ! empty( $status ) ? $status : 'Active';
	$status = ucfirst( $status );
	$plans = $wpdb->get_results( // phpcs:ignore
		$wpdb->prepare(
			"SELECT * FROM {$wpdb->prefix}afwc_commission_plans WHERE status = %s",
			$status
		),
		ARRAY_A
	);

	return apply_filters( 'afwc_commission_plans_details', ! empty( $plans ) ? $plans : array() );
}

/**
 * Get WC paid status.
 *
 * @return array $wc_paid_statuses
 */
function afwc_get_paid_order_status() {
	$wc_paid_statuses = array();
	$wc_paid_statuses = wc_get_is_paid_statuses();
	$wc_paid_statuses = apply_filters( 'afwc_paid_order_statuses', $wc_paid_statuses );
	foreach ( $wc_paid_statuses as $key => $value ) {
		$wc_paid_statuses[ $key ] = afwc_prefix_wc_to_order_status( $value );
	}
	return $wc_paid_statuses;
}

/**
 * Get WC unpaid status.
 *
 * @return array $wc_reject_statuses
 */
function afwc_get_reject_order_status() {
	$wc_reject_statuses = array();
	$wc_reject_statuses = apply_filters( 'afwc_rejected_order_statuses', array( 'refunded', 'cancelled', 'failed', 'draft' ) );
	foreach ( $wc_reject_statuses as $key => $value ) {
		$wc_reject_statuses[ $key ] = afwc_prefix_wc_to_order_status( $value );
	}
	return $wc_reject_statuses;
}

/**
 * Function to prefix order status if not present.
 *
 * @param string $order_status The order status.
 * @return string The prefixed orer status.
 */
function afwc_prefix_wc_to_order_status( $order_status = '' ) {
	if ( empty( $order_status ) ) {
		return;
	}

	$prefixed_order_status = ( strpos( $order_status, 'wc-' ) === false ) ? 'wc-' . $order_status : $order_status;

	return $prefixed_order_status;
}

/**
 * Get default plan details.
 *
 * @return array Return default plan details.
 */
function afwc_get_default_plan_details() {
	global $wpdb;

	$default_plan_id = afwc_get_default_commission_plan_id();

	if ( empty( $default_plan_id ) ) {
		return array();
	}

	$default_plan_details = $wpdb->get_results( // phpcs:ignore
		$wpdb->prepare(
			"SELECT * FROM {$wpdb->prefix}afwc_commission_plans WHERE id = %d",
			intval( $default_plan_id )
		),
		ARRAY_A
	);
	return ( ! empty( $default_plan_details ) && is_array( $default_plan_details ) ) ? reset( $default_plan_details ) : array();
}

/**
 * Get default commission plan id.
 *
 * @return int
 */
function afwc_get_default_commission_plan_id() {
	return apply_filters(
		'afwc_default_commission_plan_id',
		intval( get_option( 'afwc_default_commission_plan_id', 0 ) )
	);
}

/**
 * Check if self-refer is allowed by the affiliate settings.
 *
 * @return bool Return true if affiliates are allowed to self-refer otherwise false.
 */
function afwc_allow_self_refer() {
	return boolval( 'yes' === get_option( 'afwc_allow_self_refer', 'yes' ) );
}

/**
 * Get regex pattern for affiliate identifier.
 *
 * @return string Return the regex pattern for affiliate identifier. Allows only the alphabets and numbers and the pattern should start from the alphabets.
 */
function afwc_affiliate_identifier_regex_pattern() {
	return apply_filters( 'afwc_affiliate_identifier_regex_pattern', '^[a-zA-Z]\w*$' );
}

/**
 * Get affiliate tracking param name.
 *
 * @return string Affiliate tracking param name.
 */
function afwc_get_pname() {
	$pname = get_option( 'afwc_pname', 'ref' );
	$pname = ( ! empty( $pname ) ) ? $pname : 'ref';
	return $pname;
}

/**
 * Function to get affiliate URL based on provided URL.
 *
 * @param string $url                   The existing URL.
 * @param string $pname                 Affiliate tracking param name.
 * @param string $affiliate_identifier  Affiliate's unique ID.
 *
 * @return string Updated affiliate URL
 */
function afwc_get_affiliate_url( $url = '', $pname = '', $affiliate_identifier = '' ) {
	if ( empty( $url ) ) {
		return '';
	}

	if ( empty( $pname ) ) {
		$pname = afwc_get_pname();
	}

	if ( 'yes' === get_option( 'afwc_use_pretty_referral_links', 'no' ) ) {
		$parsed_url = wp_parse_url( $url );
		// Update the path by appending referral tracking param.
		$update_path   = trailingslashit( ( ! empty( $parsed_url['path'] ) ? $parsed_url['path'] : '' ) ) . $pname . '/' . $affiliate_identifier;
		$url_obj       = new URL( $url );
		$affiliate_url = is_callable( array( $url_obj, 'get_url' ) ) ? $url_obj->get_url( array( 'path' => trailingslashit( $update_path ) ) ) : '';
	} else {
		$affiliate_url = add_query_arg( $pname, $affiliate_identifier, $url );
	}

	return $affiliate_url;
}

/**
 * Function to check if HPOS is enabled.
 *
 * @return boolean
 */
function afwc_is_hpos_enabled() {
	if ( class_exists( '\Automattic\WooCommerce\Utilities\OrderUtil' ) && is_callable( array( '\Automattic\WooCommerce\Utilities\OrderUtil', 'custom_orders_table_usage_is_enabled' ) ) ) {
		return \Automattic\WooCommerce\Utilities\OrderUtil::custom_orders_table_usage_is_enabled();
	}

	return false;
}

/**
 * Get the lifetime affiliate by customer.
 *
 * @param string|int $customer The customer email address or customer ID.
 *
 * @return int Return the affiliate Id if the customer is linked with any affiliate otherwise 0.
 */
function afwc_get_ltc_affiliate_by_customer( $customer = '' ) {
	if ( 'no' === get_option( 'afwc_enable_lifetime_commissions', 'no' ) ) {
		return 0;
	}

	global $wpdb;

	$affiliate_id = $wpdb->get_var( // phpcs:ignore
		$wpdb->prepare(
			"SELECT DISTINCT um.user_id
		   FROM {$wpdb->prefix}usermeta as um
		   WHERE ( um.meta_key = %s AND FIND_IN_SET(%s, um.meta_value) > 0 )",
			esc_sql( 'afwc_ltc_customers' ),
			esc_sql( $customer )
		)
	);

	return ! empty( $affiliate_id ) ? intval( $affiliate_id ) : 0;
}

/**
 * Function to check whether the given roles come under the affiliate user role.
 *
 * @param array|string $user_roles The user role.
 *
 * @return boolean Return true if the user roles are selected for the affiliate role in affiliate setting.
 */
function afwc_is_affiliate_user_role( $user_roles = array() ) {
	if ( empty( $user_roles ) ) {
		return false;
	}

	if ( ! is_array( $user_roles ) ) {
		$user_roles = (array) $user_roles;
	}

	$affiliate_roles = get_option( 'affiliate_users_roles', array() );

	return ( ! empty( $affiliate_roles ) && is_array( $affiliate_roles ) ) && count( array_intersect( $affiliate_roles, $user_roles ) ) > 0;
}

if ( ! function_exists( 'afwc_get_current_url' ) ) {
	/**
	 * Function to get the current url.
	 *
	 * @return string.
	 */
	function afwc_get_current_url() {
		$server_scheme      = ( ! empty( $_SERVER['HTTPS'] ) ) ? wc_clean( wp_unslash( $_SERVER['HTTPS'] ) ) : ''; // phpcs:ignore
		$server_http_host   = ( ! empty( $_SERVER['HTTP_HOST'] ) ) ? wc_clean( wp_unslash( $_SERVER['HTTP_HOST'] ) ) : ''; // phpcs:ignore
		$server_request_uri = ( ! empty( $_SERVER['REQUEST_URI'] ) ) ? wc_clean( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : ''; // phpcs:ignore
		$base_url           = ( ( ! empty( $server_scheme ) && 'on' === $server_scheme ) ? 'https' : 'http' ) . '://' . $server_http_host;
		return $base_url . $server_request_uri;
	}
}

if ( ! function_exists( 'afwc_myaccount_dashboard_url' ) ) {
	/**
	 * Function to get affiliate dashboard URL.
	 *
	 * @return string.
	 */
	function afwc_myaccount_dashboard_url() {

		$affiliate_dashboard_page_id = get_option( 'afwc_custom_affiliate_dashboard_page_id' );
		$affiliate_dashboard_url     = '';

		if ( ! empty( $affiliate_dashboard_page_id ) && 'publish' === get_post_status( $affiliate_dashboard_page_id ) ) {
			$affiliate_dashboard_url = get_permalink( $affiliate_dashboard_page_id );
		} else {
			$endpoint                = get_option( 'woocommerce_myaccount_afwc_dashboard_endpoint', 'afwc-dashboard' );
			$my_account_page_id      = wc_get_page_id( 'myaccount' );
			$my_account_page         = ( ! empty( $my_account_page_id ) && ( intval( $my_account_page_id ) > 0 ) ) ? wc_get_page_permalink( 'myaccount' ) : '';
			$affiliate_dashboard_url = ! empty( $my_account_page ) && ! empty( $endpoint ) ? wc_get_endpoint_url( $endpoint, '', $my_account_page ) : get_home_url();
		}

		return apply_filters( 'afwc_myaccount_dashboard_url', $affiliate_dashboard_url );
	}
}

if ( ! function_exists( 'afwc_get_product_affiliate_url' ) ) {
	/**
	 * Function to get affiliate URL for the provided product.
	 *
	 * @param int  $product_id     The product ID.
	 * @param int  $affiliate_id   The affiliate ID.
	 * @param bool $force_generate Whether to generate forcefully without checking the exclude products.
	 *
	 * @return string Return the product affiliate URL.
	 */
	function afwc_get_product_affiliate_url( $product_id = 0, $affiliate_id = 0, $force_generate = false ) {

		$product_id   = absint( $product_id );
		$affiliate_id = absint( $affiliate_id );
		if ( empty( $product_id ) || empty( $affiliate_id ) ) {
			return '';
		}

		if ( 'no' === get_option( 'afwc_show_product_referral_url', 'no' ) ) {
			return '';
		}

		$affiliate = new AFWC_Affiliate( $affiliate_id );

		// Return if the affiliate ID is not existing for the affiliate(Not a valid affiliate).
		if ( empty( $affiliate->affiliate_id ) ) {
			return '';
		}

		if ( ! $force_generate ) {
			$excluded_products = afwc_get_storewide_excluded_products();

			// Return if the product is listed under excluded products for commission.
			if ( ! empty( $excluded_products ) && is_array( $excluded_products ) ) {
				$excluded_products = array_map( 'absint', $excluded_products );

				if ( in_array( $product_id, $excluded_products, true ) ) {
					return '';
				}
			}

			// Return if the product page assigned to another affiliate for landing page.
			if ( is_callable( 'AFWC_Landing_Page', 'is_enabled' ) && AFWC_Landing_Page::is_enabled() ) {
				$landing_page          = AFWC_Landing_Page::get_instance();
				$lp_assigned_affiliate = ( $affiliate instanceof AFWC_Affiliate && is_callable( array( $landing_page, 'get_affiliate_id' ) ) ) ? absint( $landing_page->get_affiliate_id( $product_id ) ) : 0;

				if ( ! empty( $lp_assigned_affiliate ) && ( absint( $affiliate->affiliate_id ) !== $lp_assigned_affiliate ) ) {
					return '';
				}
			}
		}

		$product = wc_get_product( $product_id );
		// Get product link.
		$product_link = ( $product instanceof WC_Product && is_callable( array( $product, 'get_permalink' ) ) ) ? $product->get_permalink( $product_id ) : '';

		if ( empty( $product_link ) ) {
			return '';
		}

		$identifier = ( $affiliate instanceof AFWC_Affiliate && is_callable( array( $affiliate, 'get_identifier' ) ) ) ? $affiliate->get_identifier() : '';

		return afwc_get_affiliate_url( $product_link, '', $identifier );
	}
}

if ( ! function_exists( 'afwc_get_storewide_excluded_products' ) ) {
	/**
	 * Function to get storewide excluded products.
	 *
	 * @return array Returns the array of product IDs otherwise empty array.
	 */
	function afwc_get_storewide_excluded_products() {
		$excluded_products = get_option( 'afwc_storewide_excluded_products', array() );

		if ( empty( $excluded_products ) || ! is_array( $excluded_products ) ) {
			return array();
		}

		return afwc_get_variable_variation_product_ids( $excluded_products );
	}
}


if ( ! function_exists( 'afwc_get_variable_variation_product_ids' ) ) {
	/**
	 * Function to get variable product IDs.
	 * This will return the parent product's ID as well as all its variation IDs for variable products.
	 *
	 * @param array $product_ids Array of product IDs.
	 *
	 * @return array The updated array of product IDs with variable + variations.
	 */
	function afwc_get_variable_variation_product_ids( $product_ids = array() ) {
		if ( empty( $product_ids ) || ! is_array( $product_ids ) ) {
			return array();
		}

		$ids = array();

		foreach ( $product_ids as $product_id ) {
			$product = wc_get_product( intval( $product_id ) );

			// Continue the loop, if the instance is not a WooCommerce product.
			if ( ! $product instanceof WC_Product ) {
				continue;
			}

			// Push the product ID.
			$ids[] = intval( $product_id );

			if ( is_callable( array( $product, 'is_type' ) ) && $product->is_type( 'variable' ) ) {
				// Push respective variation IDs if the product type is variable.
				$ids = array_merge(
					$ids,
					is_callable( array( $product, 'get_children' ) ) ? array_map( 'intval', $product->get_children() ) : array()
				);
			}
		}

		return array_unique( $ids );
	}
}

if ( ! function_exists( 'afwc_current_user_can_manage_affiliate' ) ) {
	/**
	 * Function to check whether current user can manage affiliate.
	 *
	 * @return bool Returns true if current user can manage affiliate otherwise false.
	 */
	function afwc_current_user_can_manage_affiliate() {
		return current_user_can( 'manage_woocommerce' ); // phpcs:ignore WordPress.WP.Capabilities.Unknown
	}
}

if ( ! function_exists( 'afwc_get_affiliates_by_user_roles' ) ) {
	/**
	 * Function to get the affiliate IDs by affiliate user roles.
	 *
	 * @return array Return the array of affiliate IDs otherwise empty array if there is no user roles for affiliates.
	 */
	function afwc_get_affiliates_by_user_roles() {

		$affiliate_roles = get_option( 'affiliate_users_roles', array() );

		if ( empty( $affiliate_roles ) ) {
			return array();
		}

		$user_ids = get_users(
			array(
				'role__in' => $affiliate_roles,
				'fields'   => 'ID',
			)
		);

		return ! empty( $user_ids ) && is_array( $user_ids ) ? array_map( 'intval', $user_ids ) : array();
	}
}
