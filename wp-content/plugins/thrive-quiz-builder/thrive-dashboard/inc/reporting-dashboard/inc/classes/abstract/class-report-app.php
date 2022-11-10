<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-dashboard
 */

namespace TVE\Reporting;

use function register_rest_route;

abstract class Report_App {

	abstract public static function key();

	abstract public static function label();

	/**
	 * Registered report types for this app
	 * @return Report_Type[]|Event[]
	 */
	public static function get_report_types(): array {
		return [];
	}

	public static function register() {
		Store::instance()->register_report_app( static::class );
	}

	public static function after_register() {
		add_action( 'rest_api_init', [ static::class, 'register_report_types' ] );
	}

	/**
	 * Register routes for each report type to get data
	 * @return void
	 */
	final public static function register_report_types() {
		foreach ( static::get_report_types() as $report_type_class ) {
			/** @var Report_Type|Event $report_type_class */

			$route = static::key() . '/' . $report_type_class::key();

			register_rest_route(
				Main::REST_NAMESPACE,
				'/' . $route,
				[
					[
						'methods'             => \WP_REST_Server::READABLE,
						'callback'            => static function ( \WP_REST_Request $request ) use ( $report_type_class ) {
							$query = $request->get_param( 'query' ) ?? [];

							return new \WP_REST_Response( $report_type_class::get_data( $query ) );
						},
						'args'                => [
							'query' => [
								'type'     => 'object',
								'required' => false,
							],
						],
						'permission_callback' => [ $report_type_class, 'permission_callback' ],
					],
				] );

			register_rest_route(
				Main::REST_NAMESPACE,
				'/' . $route . '/count',
				[
					[
						'methods'             => \WP_REST_Server::READABLE,
						'callback'            => static function ( \WP_REST_Request $request ) use ( $report_type_class ) {
							return new \WP_REST_Response( [
								'count' => $report_type_class::get_count(),
							] );
						},
						'permission_callback' => [ $report_type_class, 'permission_callback' ],
					],
				] );

			$report_type_class::register_filter_routes( $route );
		}

		register_rest_route(
			Main::REST_NAMESPACE,
			'/' . static::key() . '/report-types',
			[
				[
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => function () {
						return new \WP_REST_Response( array_map( static function ( $report_type ) {
							return [
								'key'     => $report_type::key(),
								'label'   => $report_type::label(),
								'group'   => $report_type::get_group_by(),
								'filters' => $report_type::get_filters(),
								'display' => $report_type::get_display_types(),
							];
						}, static::get_report_types() ) );
					},
					'permission_callback' => [ static::class, 'permission_callback' ],
				],
			] );
	}

	public static function permission_callback(): bool {
		return current_user_can( 'manage_options' );
	}
}
