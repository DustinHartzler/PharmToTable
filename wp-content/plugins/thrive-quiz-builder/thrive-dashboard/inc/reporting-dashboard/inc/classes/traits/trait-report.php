<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-dashboard
 */

namespace TVE\Reporting\Traits;

use TVE\Reporting\EventFields\Event_Field;
use TVE\Reporting\Logs;

trait Report {

	public static function permission_callback(): bool {
		return current_user_can( 'manage_options' );
	}

	public static function get_report_title(): string {
		return static::label();
	}

	/**
	 * ways we can display data on the reporting dashboard
	 * @return string[]
	 */
	public static function get_display_types(): array {
		return [
			'card',
			'table',
			'column_chart',
			'line_chart',
			'pie_chart',
		];
	}

	/**
	 * Return an array of key-value for all the ways we can group this report
	 * @return string[]
	 */
	public static function get_group_by(): array {
		$group_by = [];

		foreach ( static::get_registered_fields() as $field ) {
			/** @var $field Event_Field */
			if ( $field::can_group_by() ) {
				$group_by[ $field::key() ] = $field::get_label();
			}
		}

		return $group_by;
	}

	/**
	 * Return an array of all filters supported by fields
	 * @return array
	 */
	public static function get_filters(): array {
		$filters = [];

		foreach ( static::get_registered_fields() as $field ) {
			/** @var $field Event_Field */
			if ( $field::can_filter_by() ) {
				$filters[ $field::key() ] = [
					'label' => $field::get_label(),
					'type'  => $field::get_filter_type(),
				];
			}
		}

		return $filters;
	}

	/**
	 * Register REST routes for each field to return its values
	 *
	 * @param $report_type_route
	 */
	public static function register_filter_routes( $report_type_route ) {
		if ( ! method_exists( static::class, 'get_registered_fields' ) ) {
			return;
		}

		foreach ( static::get_registered_fields() as $field ) {
			/** @var $field Event_Field */
			if ( $field::can_filter_by() ) {
				$field::register_options_route( $report_type_route, static::class );
			}
		}
	}

	/**
	 * Prepare query, ensure default values, filter only allowed fields
	 *
	 * @param $query
	 *
	 * @return array
	 */
	protected static function parse_query( $query ): array {

		$query = array_merge( [
			'event_type'     => static::key(),
			'page'           => 0,
			'items_per_page' => 0,
			'group_by'       => [],
		], $query );

		if ( is_string( $query['group_by'] ) ) {
			$query['group_by'] = empty( $query['group_by'] ) ? [] : explode( ',', $query['group_by'] );
		}

		/* make sure we use the db key when we group/select */
		$query['group_by'] = array_map( static function ( $field_key ) {
			return static::get_registered_field( trim( $field_key ) )::key();
		}, $query['group_by'] );

		/* we only need to select the fields we group by and the timestamp */
		$query['fields'] = static::get_query_select_fields( array_merge(
			[ 'date' ],
			$query['group_by']
		) );

		/* the fields should be db-compatible */
		if ( ! empty( $query['filters'] ) && is_array( $query['filters'] ) ) {
			$parsed_filters  = [];
			$allowed_filters = static::get_filters();

			foreach ( $query['filters'] as $key => $value ) {
				$db_col_key = static::get_field_table_col( $key );

				if ( isset( $allowed_filters[ $key ] ) || isset( $allowed_filters[ $db_col_key ] ) ) {
					$parsed_filters[ $db_col_key ] = $value;
				}
			}

			$query['filters'] = $parsed_filters;
		}

		return $query;
	}

	/**
	 * Array of fields to select from the logs table
	 * @return string[]
	 */
	public static function get_query_select_fields( $fields = [] ): array {
		$selected_fields = [];

		foreach ( static::get_registered_fields() as $db_col => $field ) {
			/** @var $field Event_Field */
			if ( in_array( $db_col, $fields, true ) || in_array( $field::key(), $fields, true ) ) {
				$selected_fields[] = $field::get_query_select_field( $db_col );
			}
		}

		return $selected_fields;
	}

	public static function get_data( $query = [] ): array {
		$title = static::get_report_title();

		$query = static::parse_query( $query );

		$items = Logs::instance()->set_query( $query )->get();

		$labels = static::get_data_labels( $items );

		return [
			'labels' => $labels,
			'title'  => $title,
			'items'  => $items,
		];
	}

	public static function get_count( $query = [] ): int {
		$query = static::parse_query( $query );

		$logs = Logs::instance()->set_query( $query );

		$logs->get();

		return $logs->num_rows();
	}

	/**
	 * @param $items
	 *
	 * @return array
	 */
	protected static function get_data_labels( $items ): array {

		$items = array_map( static function ( $item ) {
			return new static( $item );
		}, $items );

		$labels = [];

		foreach ( $items as $item ) {
			foreach ( $item->get_fields() as $field ) {
				if ( is_a( $field, Event_Field::class ) ) {

					$field_key = $field::key();

					if ( empty( $labels[ $field_key ] ) ) {
						$labels[ $field_key ] = [
							'key'      => $field_key,
							'singular' => $field::get_label(),
							'plural'   => $field::get_label( false ),
							'values'   => [],
						];
					}

					$field_value = $field->get_value( false );

					if ( ! isset( $labels[ $field_key ]['values'][ $field_value ] ) ) {
						$labels[ $field_key ]['values'][ $field_value ] = $field->get_title();
					}
				}
			}
		}

		return $labels;
	}

	/**
	 * Event description - used for user timeline
	 * @return string
	 */
	public function get_event_description(): string {
		$user = $this->get_field( 'user_id' )->get_title();
		$item = $this->get_field( 'item_id' )->get_title();
		$date = $this->get_field_value( 'created' );

		return "$user did this event for `$item` on $date.";
	}
}
