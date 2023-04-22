<?php
/**
 * Some common functions for Affiliate For WooCommerce
 *
 * @package     affiliate-for-woocommerce/includes/
 * @since       1.0.0
 * @version     1.5.2
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
	return $wpdb->prefix . AFWC_TABLE_PREFIX . $name;
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
	return ! empty( $_COOKIE[ AFWC_AFFILIATES_COOKIE_NAME ] ) ? intval( trim( wc_clean( wp_unslash( $_COOKIE[ AFWC_AFFILIATES_COOKIE_NAME ] ) ) ) ) : 0; // phpcs:ignore
}

/**
 * Get campaign id from cookie
 *
 * @return integer Return the campain id if exists in the cookie otherwise 0.
 */
function afwc_get_campaign_id() {
	return ! empty( $_COOKIE[ AFWC_CAMPAIGN_COOKIE_NAME ] ) ? intval( trim( wc_clean( wp_unslash( $_COOKIE[ AFWC_CAMPAIGN_COOKIE_NAME ] ) ) ) ) : 0; // phpcs:ignore
}

/**
 * Get date range for smart date selector
 * TODO:: Not in use
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
		$user_roles      = ! empty( $user->roles ) ? $user->roles : array();
		$affiliate_roles = get_option( 'affiliate_users_roles', array() );
		$is_affiliate    = ( is_array( $affiliate_roles ) && is_array( $user_roles ) ) && count( array_intersect( $affiliate_roles, $user_roles ) ) > 0 ? 'yes' : $is_affiliate;
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
 * @return string if we find active campaigns else NULL.
 */
function afwc_is_campaign_active() {
	global $wpdb;
	$is_found = $wpdb->get_var( // phpcs:ignore
		$wpdb->prepare( // phpcs:ignore
			"SELECT id FROM {$wpdb->prefix}afwc_campaigns WHERE status = %s LIMIT 1",
			'Active'
		)
	);
	return apply_filters( 'afwc_is_campaign_active', ! empty( $is_found ) );
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
 * @return array $rules
 */
function afwc_get_commission_plans( $status ) {
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
function afwc_get_paid_order_status() {
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
function afwc_get_reject_order_status() {
	$wc_reject_statuses = array();
	$wc_reject_statuses = apply_filters( 'afwc_rejected_order_statuses', array( 'refunded', 'cancelled', 'failed', 'draft' ) );
	foreach ( $wc_reject_statuses as $key => $value ) {
		$wc_reject_statuses[ $key ] = ( strpos( $value, 'wc-' ) === false ) ? 'wc-' . $value : $value;
	}
	return $wc_reject_statuses;
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
 * Get array of parent chain by user id.
 *
 * @param  int|string $user_id User id.
 * @return array $user_parents
 */
function afwc_get_parent_chain( $user_id = 0 ) {
	if ( empty( $user_id ) ) {
		return array();
	}
	$user_parents = get_user_meta( $user_id, 'afwc_parent_chain', true );
	return ! empty( $user_parents ) ? array_filter( explode( '|', $user_parents ) ) : array();
}

/**
 * Get Children tree
 *
 * @param int|string $user_id User id.
 * @param bool       $is_tree If return value should be with children's parent tree or only child ids.
 *
 * @return array
 */
function afwc_get_children( $user_id = 0, $is_tree = false ) {
	if ( empty( $user_id ) ) {
		return array();
	}

	global $wpdb;

	$children = $wpdb->get_col( // phpcs:ignore
		$wpdb->prepare(
			"SELECT DISTINCT um.user_id
		FROM {$wpdb->prefix}usermeta as um
		WHERE ( um.meta_key = %s AND um.meta_value LIKE %s )",
			esc_sql( 'afwc_parent_chain' ),
			esc_sql( '%' . $wpdb->esc_like( $user_id . '|' ) . '%' )
		)
	);

	$children_tree = array();

	if ( ! empty( $children ) ) {
		foreach ( $children as $child ) {
			$parent_chain = afwc_get_parent_chain( $child );
			// Check if parent chain exists.
			if ( ! empty( $parent_chain ) ) {
				// Double verify if current user_id exists in the parent chain.
				if ( in_array( strval( $user_id ), $parent_chain, true ) ) {
					if ( $is_tree ) {
						// Assign the parent chain of each child.
						$children_tree[ $child ] = $parent_chain;
					} else {
						// Assign only id of the child.
						$children_tree[] = $child;
					}
				}
			}
		}
	}
	return apply_filters( 'afwc_get_children', $children_tree );
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
 * Get regex pattern for referral params.
 * Restrict the params for referral URL.
 *
 * @return string Return the regex pattern for referral url params. Allows only the alphabets and numbers and the pattern should start from the alphabets.
 */
function afwc_referral_params_regex_pattern() {
	return '^[a-zA-Z]\w*$';
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
 * Function to get affiliate URL based on pretty referral setting.
 *
 * @param string $affiliate_url         The affiliate URL.
 * @param string $pname                 Affiliate tracking param name.
 * @param string $affiliate_identifier  Affiliate's unique ID.
 *
 * @return string Updated affiliate URL
 */
function afwc_get_affiliate_url( $affiliate_url = '', $pname = '', $affiliate_identifier = '' ) {
	if ( empty( $affiliate_url ) ) {
		return '';
	}

	if ( empty( $pname ) ) {
		$pname = afwc_get_pname();
	}

	if ( 'yes' === get_option( 'afwc_use_pretty_referral_links', 'no' ) ) {
		$affiliate_url .= $pname . '/' . $affiliate_identifier;
	} else {
		$affiliate_url = add_query_arg( $pname, $affiliate_identifier, trailingslashit( $affiliate_url ) );
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
