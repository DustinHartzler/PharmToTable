<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-dashboard
 */

namespace TVE\Reporting;

class Hooks {

	protected static $actions = [
		'init',
		'thrive_prepare_migrations',
	];

	public static function register() {

		foreach ( static::$actions as $action ) {
			if ( is_array( $action ) ) {
				if ( method_exists( __CLASS__, $action[0] ) ) {
					add_action( $action, [ __CLASS__, $action[0] ], $action[1] ?? 10, $action[2] ?? 1 );
				}
			} elseif ( is_string( $action ) && method_exists( __CLASS__, $action ) ) {
				add_action( $action, [ __CLASS__, $action ] );
			}
		}
	}

	public static function init() {
		do_action( 'thrive_reporting_init' );

		do_action( 'thrive_reporting_register_events', Store::instance() );

		do_action( 'thrive_reporting_register_report_apps', Store::instance() );
	}

	public static function thrive_prepare_migrations() {
		\TD_DB_Manager::add_manager(
			TVE_DASH_PATH . '/inc/reporting-dashboard/db',
			'tve_td_db_version',
			TVE_DASH_DB_VERSION,
			'Thrive Dashboard',
			'thrive_',
			'tve_dash_reset'
		);
	}
}
