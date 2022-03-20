<?php
// phpcs:ignoreFile

namespace AutomateWoo\Compat;

use AutomateWoo\Integrations;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Subscription
 * @since 2.9
 */
class Subscription extends Order {


	/**
	 * @param \WC_Subscription $subscription
	 * @param bool $gmt
	 * @return string
	 */
	static function get_date_created( $subscription, $gmt = false ) {
		$timezone = $gmt ? 'gmt' : 'site';

		if ( Integrations::is_subscriptions_active( '2.2.0' ) ) {
			return $subscription->get_date( 'date_created', $timezone );
		}

		return $subscription->get_date( 'start', $timezone );
	}


	/**
	 * @param \WC_Subscription $subscription
	 * @param bool $gmt
	 * @return string
	 */
	static function get_date_last_order_created( $subscription, $gmt = false ) {
		$timezone = $gmt ? 'gmt' : 'site';

		if ( Integrations::is_subscriptions_active( '2.2.0' ) ) {
			return $subscription->get_date( 'last_order_date_created', $timezone );
		}

		return $subscription->get_date( 'last_payment', $timezone );
	}


}
