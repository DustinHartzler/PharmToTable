<?php

namespace AutomateWoo\AdminNotices;

use AutomateWoo\Admin;
use AutomateWoo\AdminNotices;

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
	protected static $version = '5.1';

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
			AdminNotices::add_notice( 'update' );
			AdminNotices::remove_notice( 'welcome' );
		}
	}

	/**
	 * Outputs the update notice including details about the update.
	 */
	public static function output_admin_notice() {

		$title       = __( 'Check out these new preset workflows', 'automatewoo' );
		$description = sprintf(
			__(
				'Reach out to your customers and automate your work easily with these presets. Learn more about <%1$s>preset workflows<%2$s>.',
				'automatewoo'
			),
			'a href="' . Admin::get_docs_link( 'preset-workflows/', 'update-notice' ) . '"',
			'/a'
		);
		$links       = [
			[
				'text'           => __( 'Browse presets', 'automatewoo' ),
				'href'           => Admin::page_url( 'workflow-presets' ),
				'class'          => 'button-primary',
				'data_link_type' => 'presets',
				'target'         => '_self',
			],
			[
				'text'           => __( 'What else is new in 5.1', 'automatewoo' ),
				'href'           => 'https://woocommerce.com/posts/automatewoo-5-1/',
				'class'          => 'button-secondary',
				'data_link_type' => 'getting_started',
			],
		];

		Admin::get_view(
			'welcome-notice',
			[
				'notice_identifier' => self::NOTICE_ID,
				'title'             => $title,
				'description'       => $description,
				'links'             => $links,
			]
		);
	}

}
