<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-dashboard
 */

namespace TVE\Reporting\EventFields;

class Created extends Event_Field {

	public static function key(): string {
		return 'date';
	}

	public static function can_group_by(): bool {
		return true;
	}

	public static function get_query_select_field( $db_col ): string {
		return 'DATE(`created`) AS date';
	}

	public static function get_label( $singular = true ): string {
		return $singular ? 'Date' : 'Dates';
	}

	public function format( $value ) {
		if ( is_string( $value ) ) {
			$value = strtotime( $value );
		}

		return wp_date( get_option( 'date_format' ), $value );
	}

	public function get_title(): string {
		return $this->value === null ? 'Date' : $this->format( $this->value );
	}

	public static function get_filter_type(): string {
		return 'date-range-picker';
	}
}
