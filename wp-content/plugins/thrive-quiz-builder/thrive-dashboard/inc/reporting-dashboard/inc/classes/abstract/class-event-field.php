<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-dashboard
 */

namespace TVE\Reporting\EventFields;

use TVE\Reporting\Event;
use TVE\Reporting\Logs;
use TVE\Reporting\Main;
use TVE\Reporting\Report_Type;

abstract class Event_Field {
	/**
	 * @var mixed|null
	 */
	protected $value;

	/**
	 * Return true/false if a report can be filtered by this field
	 * @return bool
	 */
	public static function can_filter_by(): bool {
		return true;
	}

	/*
	 * Return true/false if we can group data by this field in a report
	 */
	public static function can_group_by(): bool {
		return false;
	}

	/**
	 * Label used to display this field in reports
	 *
	 * @param $singular
	 *
	 * @return string
	 */
	public static function get_label( $singular = true ): string {
		return $singular ? 'Item' : 'Items';
	}

	/**
	 * Query used to select this field from the db
	 *
	 * @param $db_col
	 *
	 * @return string
	 */
	public static function get_query_select_field( $db_col ): string {
		return "`$db_col` AS " . static::key();
	}

	/**
	 * Identifier for the field
	 * @return string
	 */
	abstract public static function key(): string;

	public function __construct( $value = null ) {
		$this->value = $value;
	}

	public function get_value( $format = true ) {
		return $format ? $this->format( $this->value ) : $this->value;
	}

	/**
	 * Most of the time we store ID's in the db so this method will convert the ID in the title of the field
	 * @return string
	 */
	public function get_title(): string {
		return $this->value === null ? 'Item' : get_the_title( $this->value );
	}

	public function format( $value ) {
		return $value;
	}

	/**
	 * In case we can filter by this field, what type of filter we can use
	 * @return string
	 */
	public static function get_filter_type(): string {
		return 'multiple-select';
	}

	/**
	 * Register rest route for filter values
	 *
	 * @param                            $report_type_route
	 * @param Report_Type|Event|string   $report_type_class
	 */
	public static function register_options_route( $report_type_route, $report_type_class ) {
		$route = '/' . $report_type_route . '/filter-data';

		register_rest_route( Main::REST_NAMESPACE, $route . '/' . static::key(), [
			[
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => static function ( $request ) use ( $report_type_class ): \WP_REST_Response {
					$options            = [];
					$field_key          = $request->get_param( 'field' );
					$field_table_column = $report_type_class::get_field_table_col( $field_key );

					$fields = Logs::instance()->get_fields( $report_type_class::key(), $field_table_column );

					foreach ( $fields as $field ) {
						$field_instance = new  static( $field['value'] );

						$options[] = [
							'id'    => $field['value'],
							'label' => $field_instance->get_title(),
						];
					}

					return new \WP_REST_Response( $options );
				},
				'args'                => [
					'field' => [
						'type'     => 'string',
						'required' => true,
					],
				],
				'permission_callback' => [ __CLASS__, 'permission_callback' ],
			],
		] );
	}

	public static function permission_callback(): bool {
		return current_user_can( 'manage_options' );
	}
}
