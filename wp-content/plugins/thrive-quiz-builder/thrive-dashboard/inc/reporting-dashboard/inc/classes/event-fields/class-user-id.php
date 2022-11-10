<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-dashboard
 */

namespace TVE\Reporting\EventFields;

class User_Id extends Event_Field {

	public static function key(): string {
		return 'user_id';
	}

	public static function can_group_by(): bool {
		return true;
	}

	public static function get_label( $singular = true ): string {
		return $singular ? 'User' : 'Users';
	}

	public function format( $value ) {
		return (int) $value;
	}

	public function get_title(): string {
		if ( $this->value === null ) {
			$user_name = 'Users';
		} elseif ( (int) $this->value === 0 ) {
			$user_name = 'Unknown user';
		} else {
			$user = get_user_by( 'ID', $this->value );

			$user_name = $user instanceof \WP_User ? $user->display_name : "User $this->value";
		}

		return $user_name;
	}
}
