<?php

namespace AutomateWoo;

/**
 * Class Marketplace_License_Transition_Helper
 *
 * Contains logic related to transition from a legacy (AW.com) to marketplace (WC.com) license.
 *
 * @since   4.7.0
 * @package AutomateWoo
 */
class Marketplace_License_Transition_Helper {

	/**
	 * Init marketplace license helper.
	 */
	public static function init() {
		if ( Licenses::is_legacy() ) {
			add_action( 'automatewoo_fifteen_minute_worker', [ __CLASS__, 'check_for_marketplace_license' ] );
			add_action( 'automatewoo/admin_notice/marketplace_license_prompt', [ __CLASS__, 'admin_notice_marketplace_license_prompt' ] );
		}

		add_action( 'woocommerce_helper_subscription_activate_success', [ __CLASS__, 'handle_marketplace_subscription_activated' ] );
	}

	/**
	 * Check if the site has an marketplace subscription for AW.
	 */
	public static function check_for_marketplace_license() {
		if ( get_option( 'automatewoo_did_marketplace_license_prompt' ) ) {
			return;
		}

		if ( Licenses::has_marketplace_subscription( 'automatewoo' ) ) {
			Admin_Notices::add_notice( 'marketplace_license_prompt' );
			update_option( 'automatewoo_did_marketplace_license_prompt', true, false );
		}
	}

	/**
	 * Maybe output admin marketplace license prompt.
	 */
	public static function admin_notice_marketplace_license_prompt() {
		// Don't show on license page as notice is included in page view.
		if ( Admin::is_page( 'licenses' ) ) {
			return;
		}

		self::output_marketplace_license_prompt_notice( true );
	}

	/**
	 * Output marketplace license prompt notice.
	 *
	 * @param bool $dismissible
	 */
	public static function output_marketplace_license_prompt_notice( $dismissible = true ) {
		$message = sprintf(
			__( '<strong>It looks like you have an AutomateWoo subscription on WooCommerce.com.</strong> This store is using a legacy license but can be switched over by activating your AutomateWoo subscription in the <%1$s>WooCommerce.com Subscriptions tab<%2$s>.', 'automatewoo' ),
			'a href="' . Admin::get_marketplace_subscriptions_tab_url() . '"',
			'/a'
		);
		?>
		<div class="notice woocommerce-message <?php echo $dismissible ? 'is-dismissible' : ''; ?>"
			<?php echo $dismissible ? 'data-automatewoo-dismissible-notice="marketplace_license_prompt"' : ''; ?>
		>
			<p><?php echo wp_kses_post( $message ); ?></p>
		</div>
		<?php
	}

	/**
	 * Output legacy license warning notice.
	 *
	 * Shown if the user is yet to have a marketplace license.
	 */
	public static function output_legacy_license_warning_notice() {
		$message = sprintf(
			__( '<strong>AutomateWoo has moved to the official WooCommerce Extensions Store!</strong> To find out what this means for existing AutomateWoo.com license holders please read <%1$s>the announcement post<%2$s>.', 'automatewoo' ),
			'a href="https://automatewoo.com/blog/moving-to-woocommerce-com/"',
			'/a'
		);
		?>
		<div class="notice woocommerce-message">
			<p><?php echo wp_kses_post( $message ); ?></p>
		</div>
		<?php
	}

	/**
	 * Automatically switch license option if AW is activated via WC_Helper.
	 *
	 * @param int $product_id
	 */
	public static function handle_marketplace_subscription_activated( $product_id ) {
		if ( (int) $product_id !== Licenses::$marketplace_product_ids['automatewoo'] ) {
			return;
		}

		update_option( 'automatewoo_license_system', 'wc', true );
	}

}
