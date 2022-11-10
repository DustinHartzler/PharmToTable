<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-dashboard
 */

namespace TVE\Reporting\EventFields;

class Item_Id extends Event_Field {

	public static function key(): string {
		return 'item_id';
	}

	public static function can_group_by(): bool {
		return true;
	}

	public function format( $value ) {
		return (int) $value;
	}
}
