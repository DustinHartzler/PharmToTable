<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-dashboard
 */

namespace TVE\Reporting;

use TVE\Reporting\Traits\Report;

abstract class Report_Type {

	use Report;

	abstract public static function key(): string;

	abstract public static function label(): string;

	abstract public static function get_data( $query );

	public static function get_filters(): array {
		return [];
	}
}
