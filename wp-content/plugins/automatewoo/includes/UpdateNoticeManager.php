<?php

namespace AutomateWoo;

/**
 * Display an admin notice on plugin update
 *
 * @since   5.0.0
 * @package AutomateWoo
 */
class UpdateNoticeManager {
	const NOTICE_ID = 'update';

	/**
	 * The version this notice relates to.
	 *
	 * @var string
	 *
	 * @see output_admin_notice method to update the version number displayed in the notice
	 */
	protected static $version = '5.0';

	/**
	 * Attach callbacks.
	 *
	 * @since 5.0.0
	 */
	public static function init() {
		add_action( 'automatewoo_version_changed', [ __CLASS__, 'maybe_add_admin_notice' ], 10, 2 );
		add_action( 'automatewoo/admin_notice/update', [ __CLASS__, 'output_admin_notice' ] );
	}

	/**
	 * Add an admin notice when the plugin is updated.
	 *
	 * @param string $previous_version The version of AutomateWoo the store was running prior to updating.
	 * @param string $current_version  The new version the site has been updated to.
	 *
	 * @since 5.0.0
	 */
	public static function maybe_add_admin_notice( $previous_version, $current_version ) {
		if ( '' !== $previous_version && version_compare( $previous_version, self::$version, '<' ) && version_compare( $current_version, self::$version, '>=' ) ) {
			Admin_Notices::add_notice( 'update' );
			Admin_Notices::remove_notice( 'welcome' );
		}
	}

	/**
	 * Outputs the update notice including details about the update.
	 */
	public static function output_admin_notice() {
		$description = __( 'This release contains one of our most requested features - Manual Workflows, along with several other new features and improvements!', 'automatewoo' );
		$link        = 'https://woocommerce.com/posts/automatewoo-5/';

		Admin::get_view(
			'update-notice',
			[
				'notice_identifier' => self::NOTICE_ID,
				'version'           => self::$version,
				'description'       => $description,
				'link'              => $link,
			]
		);
	}

}
