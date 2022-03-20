<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-dashboard
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden
}

/**
 * Handles update related messages across wp-admin
 *
 * Class TD_TTW_Messages_Manager
 */
class TD_TTW_Messages_Manager {

	use TD_Singleton;

	use TD_TTW_Utils;

	private function __construct() {

		/**  @var TD_TTW_User_Licenses $licenses */
		$licenses = TD_TTW_User_Licenses::get_instance();

		if ( $licenses->has_membership() && ! $licenses->get_membership()->can_update() ) {
			add_action( 'admin_notices', array( __CLASS__, 'inactive_membership' ) );
		}
	}

	/**
	 * Render a message from templates/messages/ directory
	 * First param should be template name
	 * Second whether or not to echo/return the output
	 * Third an array with needed vars in template
	 *
	 * @return false|string
	 */
	public static function render() {

		$args     = func_get_args();
		$template = ! empty( $args[0] ) ? $args[0] : null;

		if ( empty( $template ) ) {
			return false;
		}

		$action = ! empty( $args[1] ) && 1 === (int) $args[1] ? 'return' : 'echo';
		$vars   = ! empty( $args[2] ) && is_array( $args[2] ) ? $args[2] : array();

		/**
		 * Prepare variables names for template file
		 * $key => var name
		 * $value => var value
		 */
		foreach ( $vars as $key => $value ) {
			$$key = $value;
		}

		ob_start();

		include self::path( 'templates/messages/' . $template . '.phtml' );

		$html = ob_get_clean();

		if ( 'return' === $action ) {
			return $html;
		}

		echo $html; //phpcs:ignore
	}

	/**
	 * Show license related notices in wp dash
	 */
	public static function inactive_membership() {
		/**  @var TD_TTW_User_Licenses $licenses */
		$licenses = TD_TTW_User_Licenses::get_instance();

		if ( TD_TTW_Connection::get_instance()->is_connected() ) {
			$tpl = 'expired-connected';
		} else {
			$tpl = 'expired-disconnected';
		}

		self::render(
			$tpl,
			false,
			[
				'membership_name' => $licenses->get_membership()->get_name(),
			]
		);
	}

	/**
	 * Get plugin update message
	 *
	 * @param stdClass $state
	 * @param array    $plugin_data
	 *
	 * @return string
	 */
	public static function get_update_message( $state, $plugin_data ) {

		/**  @var $licenses TD_TTW_User_Licenses */
		$licenses = TD_TTW_User_Licenses::get_instance();

		if ( TD_TTW_Connection::get_instance()->is_connected() && $licenses->has_active_membership() ) {
			return '';
		}

		$return = '';
		$tpl    = '';

		if ( ! TD_TTW_Connection::get_instance()->is_connected() && empty( $licenses->get_licenses_details() ) ) {
			$tpl = 'disconnected';
		} elseif ( $licenses->has_membership() && ! $licenses->get_membership()->can_update() ) {
			$tpl = 'expired';
		}

		if ( ! empty( $tpl ) ) {
			$return .= self::render(
				$tpl,
				true,
				array(
					'state'       => $state,
					'plugin_data' => $plugin_data,
				)
			);
		}

		return $return;
	}
}

TD_TTW_Messages_Manager::get_instance();
