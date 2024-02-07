<?php

declare(strict_types=1);

namespace OM4\WooCommerceZapier\Plugin\Memberships\Plan;

use OM4\WooCommerceZapier\Helper\FeatureChecker;
use OM4\WooCommerceZapier\Plugin\Memberships\Plan\MembershipPlanTaskCreator;
use OM4\WooCommerceZapier\WooCommerceResource\CustomPostTypeResource;

defined( 'ABSPATH' ) || exit;

/**
 * Definition of the Membership Plan resource type.
 *
 * This resource is only enabled if WooCommerce Memberships is available.
 *
 * WooCommerce Memberships has webhook payload, topic and delivery functionality built-in,
 * so this class extends the built-in trigger rules.
 *
 * @since 2.10.0
 */
class MembershipPlanResource extends CustomPostTypeResource {

	/**
	 * Feature Checker instance.
	 *
	 * @var FeatureChecker
	 */
	protected $checker;

	/**
	 * {@inheritDoc}
	 *
	 * @param FeatureChecker $checker    FeatureChecker instance.
	 */
	public function __construct( FeatureChecker $checker ) {
		$this->checker             = $checker;
		$this->key                 = MembershipPlanTaskCreator::resource_type();
		$this->name                = MembershipPlanTaskCreator::resource_name();
		$this->metabox_screen_name = 'wc_membership_plan';
	}
}
