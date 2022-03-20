<?php

namespace TVD\Dashboard\Access_Manager;

use TVD\Dashboard\Access_Manager\Functionality;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

class Login_Redirect extends Functionality {
	public static function hooks() {
		add_filter( 'login_redirect', array( __CLASS__, 'login_redirect' ), 10, 3 );
	}

	public static function get_name() {
		return 'Wordpress Login Redirect';
	}

	public static function get_tag() {
		return 'login_redirect';
	}

	public static function get_default() {
		return 'inherit';
	}

	public static function get_options() {
		$options = array(
			[
				'name' => 'Inherit',
				'tag'  => 'inherit',
			],
			[
				'name' => 'WordPress Dashboard',
				'tag'  => 'wpdashboard',
			],
			[
				'name' => 'Homepage',
				'tag'  => 'homepage',
			],

			[
				'name' => 'Custom',
				'tag'  => 'custom',
			],
		);
		if ( is_plugin_active( 'thrive-apprentice/thrive-apprentice.php' ) ) {
			$options[] = [
				'name' => 'Apprentice Homepage',
				'tag'  => 'apphomepage',
			];
		}

		return $options;
	}

	public static function get_icon() {
		return 'wordpress';
	}

	public static function get_options_type() {
		return 'dropdown';
	}

	public static function get_option_value( $user_role ) {
		$option = get_option( static::get_option_name( $user_role ) );

		if ( $option === 'apphomepage' && ! is_plugin_active( 'thrive-apprentice/thrive-apprentice.php' ) ) {
			$option = 'inherit';
		}

		return $option;
	}

	public static function login_redirect( $redirect, $requested_redirect_to, $logged_in_user ) {
		if ( ! is_wp_error( $logged_in_user ) && $logged_in_user && $logged_in_user->ID ) {
			$user_role = $logged_in_user->roles[0];
			$url       = static::get_option_value( $user_role );

			if ( $url ) {
				switch ( $url ) {
					case 'inherit':
						break;
					case 'wpdashboard':
						$redirect = get_admin_url();
						break;
					case 'homepage':
						$redirect = get_home_url();
						break;
					case 'apphomepage':
						if ( is_plugin_active( 'thrive-apprentice/thrive-apprentice.php' ) ) {
							$redirect = get_permalink( tva_get_settings_manager()->factory( 'index_page' )->get_value() );
						}
						break;
					default:
						$redirect = get_permalink( $url['pageId'] );
						break;
				}
			}
		}

		return $redirect;
	}
}
