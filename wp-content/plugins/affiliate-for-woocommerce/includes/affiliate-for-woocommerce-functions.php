<?php
/**
 * Some common functions for Affiliate For WooCommerce
 *
 * @since       1.0.0
 * @version     1.1.0
 *
 * @package     affiliate-for-woocommerce/includes/
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

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
 * Get commission payout statuses
 *
 * @return array
 */
function get_afwc_commission_statuses() {

	return array(
		AFWC_REFERRAL_STATUS_PAID     => 'Paid',
		AFWC_REFERRAL_STATUS_UNPAID   => 'Unpaid',
		AFWC_REFERRAL_STATUS_REJECTED => 'Rejected',
	);

}

/**
 * Get table name
 *
 * @param  string $name The table.
 * @return string
 */
function get_afwc_tablename( $name ) {
	global $wpdb;
	return $wpdb->prefix . AFWC_TABLE_PREFIX . $name;
}

/**
 * Get referrer id
 *
 * @return integer $affiliate_id
 *
 * Credit: [itthinx]
 */
function get_referrer_id() {
	$affiliate_id = isset( $_COOKIE[ AFWC_AFFILIATES_COOKIE_NAME ] ) ? trim( wc_clean( wp_unslash( $_COOKIE[ AFWC_AFFILIATES_COOKIE_NAME ] ) ) ) : false; // phpcs:ignore
	return $affiliate_id;
}

/**
 * Get campaign id from cookie
 *
 * @return integer $campaign_id
 */
function get_campaign_id() {
	$campaign_id = isset( $_COOKIE[ 'afwc_campaign' ] ) ? trim( wc_clean( wp_unslash( $_COOKIE[ 'afwc_campaign' ] ) ) ) : false; // phpcs:ignore
	return $campaign_id;
}

/**
 * Get date range for smart date selector
 *
 * @param  string $for    The smart date label.
 * @param  string $format The format.
 * @return array
 */
function get_afwc_date_range( $for = '', $format = 'd-M-Y' ) {
	if ( empty( $for ) ) {
		return array();
	}
	$today            = gmdate( $format, Affiliate_For_WooCommerce::get_offset_timestamp() );
	$date             = new DateTime( $today );
	$date_from        = $date;
	$date_to          = $date;
	$offset_timestamp = Affiliate_For_WooCommerce::get_offset_timestamp();
	switch ( $for ) {
		case 'today':
			$from_date = $today;
			$to_date   = $today;
			break;

		case 'yesterday':
			$from_date = gmdate( $format, Affiliate_For_WooCommerce::get_offset_timestamp( strtotime( '-1 second', strtotime( 'today' ) ) ) );
			$to_date   = $from_date;
			break;

		case 'this_week':
			$from_date = gmdate( $format, mktime( 0, 0, 0, gmdate( 'm', $offset_timestamp ), gmdate( 'd', $offset_timestamp ) - intval( get_option( 'start_of_week' ) ) - 1, gmdate( 'Y', $offset_timestamp ) ) );
			$to_date   = $today;
			break;

		case 'last_week':
			$from_date = gmdate( $format, mktime( 0, 0, 0, gmdate( 'm', $offset_timestamp ), gmdate( 'd', $offset_timestamp ) - intval( get_option( 'start_of_week' ) ) - 8, gmdate( 'Y', $offset_timestamp ) ) );
			$to_date   = gmdate( $format, mktime( 0, 0, 0, gmdate( 'm', $offset_timestamp ), gmdate( 'd', $offset_timestamp ) - intval( get_option( 'start_of_week' ) ) - 2, gmdate( 'Y', $offset_timestamp ) ) );
			break;

		case 'this_month':
			$from_date = gmdate( $format, mktime( 0, 0, 0, gmdate( 'n', $offset_timestamp ), 1, gmdate( 'Y', $offset_timestamp ) ) );
			$to_date   = $today;
			break;

		case 'last_month':
			$from_date = gmdate( $format, mktime( 0, 0, 0, gmdate( 'n', $offset_timestamp ) - 1, 1, gmdate( 'Y', $offset_timestamp ) ) );
			$to_date   = gmdate( $format, strtotime( '-1 second', strtotime( gmdate( 'm', $offset_timestamp ) . '/01/' . gmdate( 'Y', $offset_timestamp ) . ' 00:00:00' ) ) );
			break;

		case 'three_months':
			$from_date = gmdate( $format, mktime( 0, 0, 0, gmdate( 'n', $offset_timestamp ) - 2, 1, gmdate( 'Y', $offset_timestamp ) ) );
			$to_date   = $today;
			break;

		case 'six_months':
			$from_date = gmdate( $format, mktime( 0, 0, 0, gmdate( 'n', $offset_timestamp ) - 5, 1, gmdate( 'Y', $offset_timestamp ) ) );
			$to_date   = $today;
			break;

		case 'this_year':
			$from_date = gmdate( $format, mktime( 0, 0, 0, 1, 1, gmdate( 'Y', $offset_timestamp ) ) );
			$to_date   = $today;
			break;

		case 'last_year':
			$from_date = gmdate( $format, mktime( 0, 0, 0, 1, 1, gmdate( 'Y', $offset_timestamp ) - 1 ) );
			$to_date   = gmdate( $format, strtotime( '-1 second', strtotime( '01/01/' . gmdate( 'Y', $offset_timestamp ) . ' 00:00:00' ) ) );
			break;
	}

	return array(
		'from' => $from_date,
		'to'   => $to_date,
	);
}

/**
 * Get user id based on affiliate id
 *
 * @param  integer $affiliate_id The affiliate id.
 * @return integer
 */
function get_user_id_based_on_affiliate_id( $affiliate_id ) {
	global $wpdb;

	$afwc_affiliates_users = get_afwc_tablename( 'affiliates_users' );
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
function get_affiliate_id_based_on_user_id( $user_id ) {
	global $wpdb;

	$afwc_affiliates_users = get_afwc_tablename( 'affiliates_users' );
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
function afwc_format_price( $price, $decimals = null, $decimal_separator = null, $thousand_separator = null ) {
	if ( is_null( $decimals ) ) {
		$decimals = afwc_get_price_decimals();
	}

	if ( empty( $decimal_separator ) ) {
		$decimal_separator = afwc_get_price_decimal_separator();
	}

	if ( empty( $decimal_separator ) ) {
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
 * Check if the user is affilaite or not
 *
 * @param  WP_User $user The user object.
 * @return yes/no/pending/not_registered
 */
function afwc_is_user_affiliate( $user ) {
	$is_affiliate = 'no';
	$user_id      = 0;
	if ( is_int( $user ) ) {
		$user_id = $user;
		$user    = new WP_User( $user );
	} elseif ( $user instanceof WP_User ) {
		$user_id = $user->ID;
	}

	if ( $user instanceof WP_User ) {

		$have_meta = get_user_meta( $user_id, 'afwc_is_affiliate', true );
		if ( $have_meta ) {
			$is_affiliate = ( ! empty( $have_meta ) ) ? $have_meta : 'no';
		} else {
			$role_name           = $user->roles[0];
			$get_affiliate_roles = get_option( 'affiliate_users_roles', array() );
			$is_affiliate        = ( in_array( $role_name, $get_affiliate_roles, true ) ) ? 'yes' : 'no';
			$is_affiliate        = ( 'no' === $is_affiliate && '' === $have_meta ) ? 'not_registered' : $is_affiliate;
		}
	}

	return $is_affiliate;
}

/**
 * Function to create page for registration
 *
 * @return int
 */
function create_reg_form_page() {
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
function get_campaign_id_by_slug( $slug ) {
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
function get_afwc_user_tags_id_name_map() {
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
 * @return array $rules
 */
function get_commission_plans( $status ) {
	global $wpdb;
	$status     = ! empty( $status ) ? $status : 'Active';
	$status     = ucfirst( $status );
	$afwc_rules = $wpdb->get_results( // phpcs:ignore
		$wpdb->prepare(
			"SELECT * FROM {$wpdb->prefix}afwc_commission_plans WHERE status = %s",
			$status
		),
		ARRAY_A
	);

	return $afwc_rules;

}

/**
 * Get WC paid status
 *
 * @return array $wc_paid_statuses
 */
function get_afwc_paid_order_status() {
	$wc_paid_statuses = array();
	$wc_paid_statuses = wc_get_is_paid_statuses();
	$wc_paid_statuses = apply_filters( 'afwc_paid_order_statuses', $wc_paid_statuses );
	foreach ( $wc_paid_statuses as $key => $value ) {
		$wc_paid_statuses[ $key ] = ( strpos( $value, 'wc-' ) === false ) ? 'wc-' . $value : $value;
	}
	return $wc_paid_statuses;
}

/**
 * Get WC unpaid status
 *
 * @return array $wc_reject_statuses
 */
function get_afwc_reject_order_status() {
	$wc_reject_statuses = array();
	$wc_reject_statuses = apply_filters( 'afwc_rejected_order_statuses', array( 'refunded', 'cancelled', 'failed', 'draft' ) );
	foreach ( $wc_reject_statuses as $key => $value ) {
		$wc_reject_statuses[ $key ] = ( strpos( $value, 'wc-' ) === false ) ? 'wc-' . $value : $value;
	}
	return $wc_reject_statuses;
}

