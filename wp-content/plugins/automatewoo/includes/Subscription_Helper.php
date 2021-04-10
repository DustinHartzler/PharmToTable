<?php
// phpcs:ignoreFile

namespace AutomateWoo;

aw_deprecated_class( Subscription_Helper::class, '5.2.0' );

/**
 * @class Subscription_Helper
 * @since 2.8.2
 *
 * @deprecated this class is no longer required due to changes in AutomateWoo\Data_Layer
 */
class Subscription_Helper {

	/**
	 * @deprecated this is no longer required due to changes in AutomateWoo\Data_Layer
	 *
	 * @param \WC_Subscription $subscription
	 * @return \WP_User|bool
	 */
	static function prepare_user_data( $subscription ) {

		wc_deprecated_function( __METHOD__, '5.2.0' );

		if ( ! $subscription || ! Integrations::is_subscriptions_active() ) {
			return false;
		}

		$user = $subscription->get_user();

		if ( ! $user ) {
			return false;
		}

		// ensure first and last name are set
		if ( ! $user->first_name ) $user->first_name = Compat\Subscription::get_billing_first_name( $subscription );
		if ( ! $user->last_name ) $user->last_name = Compat\Subscription::get_billing_last_name( $subscription );
		if ( ! $user->billing_phone ) $user->billing_phone = Compat\Subscription::get_billing_phone( $subscription );

		return $user;
	}

}
