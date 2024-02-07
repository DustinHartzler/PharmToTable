<?php

declare(strict_types=1);

namespace OM4\WooCommerceZapier\Plugin\Memberships\User;

use OM4\WooCommerceZapier\Helper\FeatureChecker;
use OM4\WooCommerceZapier\Webhook\Trigger;
use OM4\WooCommerceZapier\Webhook\ZapierWebhook;
use OM4\WooCommerceZapier\WooCommerceResource\CustomPostTypeResource;
use WC_Memberships_User_Membership;
use WC_Webhook;

defined( 'ABSPATH' ) || exit;

/**
 * Definition of the User Membership resource type.
 *
 * This resource is only enabled if WooCommerce Memberships is available.
 *
 * WooCommerce Memberships has webhook payload, topic and delivery functionality built-in,
 * so this class extends the built-in trigger rules.
 *
 * @since 2.10.0
 */
class UserMembershipResource extends CustomPostTypeResource {

	/**
	 * Whether our hooks have been added.
	 * This is used to ensure that our hooks are only added once, even if this class is instantiated multiple times.
	 *
	 * @var bool
	 */
	protected static $hooks_added = false;

	/**
	 * Feature Checker instance.
	 *
	 * @var FeatureChecker
	 */
	protected $checker;

	/**
	 * {@inheritDoc}
	 *
	 * @param FeatureChecker $checker FeatureChecker instance.
	 */
	public function __construct( FeatureChecker $checker ) {
		$this->checker             = $checker;
		$this->key                 = UserMembershipsTaskCreator::resource_type();
		$this->name                = UserMembershipsTaskCreator::resource_name();
		$this->metabox_screen_name = 'wc_user_membership';

		if ( ! self::$hooks_added ) {
			add_action(
				'wc_memberships_user_membership_status_changed',
				array( $this, 'wc_memberships_user_membership_status_changed' ),
				10,
				3
			);
			self::$hooks_added = true;
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_webhook_triggers() {
		return array_merge(
			array(
				new Trigger(
					'user_membership.status_changed',
					__( 'User Membership status changed (any status)', 'woocommerce-zapier' ),
					// `wc_memberships_user_membership_status_changed` hook with our own prefix/handler to convert the arg from a WC_Memberships_User_Membership object to a membership ID.
					array( 'wc_zapier_user_membership_status_changed' )
				),
			),
			$this->get_status_changed_dynamic_triggers()
		);
	}

	/**
	 * Dynamically create a "User Membership Status Changed to ..." Trigger Rule,
	 * one for each registered WooCommerce Memberships status.
	 *
	 * @return Trigger[]
	 */
	protected function get_status_changed_dynamic_triggers() {
		$triggers = array();
		foreach ( $this->get_statuses() as $status => $status_label ) {
			$status_key = str_replace( '-', '_', sanitize_title_with_dashes( $status ) );
			$triggers[] = new Trigger(
				"user_membership.status_changed_to_{$status_key}",
				// Translators: Membership Status Name/Label.
				sprintf( __( 'User Membership status changed to %s', 'woocommerce-zapier' ), $status_label ),
				array( "wc_zapier_user_membership_status_{$status}" )
			);
		}
		return $triggers;
	}

	/**
	 * Get a list of all registered WooCommerce User Memberships statuses.
	 *
	 * @return array<string, string> Status key excludes the 'wc-' prefix.
	 */
	protected function get_statuses() {
		$statuses = array();
		foreach ( \wc_memberships_get_user_membership_statuses() as $status => $status_texts ) {
			// Use the status without wcm- internal prefix.
			$status              = 'wcm-' === substr( $status, 0, 4 ) ? substr( $status, 4 ) : $status;
			$statuses[ $status ] = $status_texts['label'];
		}
		return $statuses;
	}

	/**
	 * Whenever a User Membership status is changed, trigger the appropriate WooCommerce Zapier hook
	 * that will then trigger the appropriate Zapier webhook's Trigger Rule.
	 *
	 * Also converts the first argument from a WC_Memberships_User_Membership object to a membership ID.
	 *
	 * Executed during the `wc_memberships_user_membership_status_changed` hook.
	 *
	 * @param  WC_Memberships_User_Membership $user_membership  The membership.
	 * @param  string                         $old_status  Old status, without the `wcm-` prefix.
	 * @param  string                         $new_status  New status, without the `wcm-` prefix.
	 *
	 * @return void
	 */
	public function wc_memberships_user_membership_status_changed( $user_membership, $old_status, $new_status ) {
		/**
		 * Execute the WooCommerce Zapier handler for the "User Membership status changed (any status)" trigger rule.
		 *
		 * @internal
		 * @since 2.10.0
		 *
		 * @param int $arg User Membership ID.
		 */
		do_action( 'wc_zapier_user_membership_status_changed', $user_membership->get_id() );

		/**
		 * Execute the WooCommerce Zapier handler for the "User Membership status changed to %s" trigger rule.
		 *
		 * @internal
		 * @since 2.10.0
		 *
		 * @param int $arg User Membership ID.
		 */
		do_action( "wc_zapier_user_membership_status_{$new_status}", $user_membership->get_id() );
	}
}
