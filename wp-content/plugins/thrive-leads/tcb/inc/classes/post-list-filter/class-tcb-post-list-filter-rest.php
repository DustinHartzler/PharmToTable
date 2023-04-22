<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-visual-editor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

/**
 *
 */
class TCB_Post_List_Filter_Rest {
	const  REST_NAMESPACE = 'tcb/v1';
	const  REST_ROUTE     = '/post-list-filter';

	public static function register_routes() {
		register_rest_route( static::REST_NAMESPACE, static::REST_ROUTE . '/filter-options', [
			[
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => [ __CLASS__, 'get_filter_options' ],
				'permission_callback' => [ __CLASS__, 'route_permission' ],
				'args'                => [
					'filter_option' => [
						'type'     => 'string',
						'required' => true,
					],
				],
			],
		] );

		register_rest_route( static::REST_NAMESPACE, static::REST_ROUTE . '/option-template', [
			[
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => [ __CLASS__, 'get_option_template_ajax' ],
				'permission_callback' => [ __CLASS__, 'route_permission' ],
				'args'                => [
					'filter-type'             => [
						'type'     => 'string',
						'required' => true,
					],
					'filter-option'           => [
						'type'     => 'string',
						'required' => true,
					],
					'filter-option-selection' => [
						'type'     => 'string',
						'required' => false,
					],
					'filter-all-option'       => [
						'type'     => 'boolean',
						'required' => true,
					],
				],
			],
		] );
	}

	/**
	 * @param \WP_REST_Request
	 *
	 * @return \WP_REST_Response
	 */
	public static function get_filter_options( $request ) {
		$categories       = [];
		$filter_option    = sanitize_text_field( $request->get_param( 'filter_option' ) );
		$searched_keyword = sanitize_text_field( $request->get_param( 'search' ) );
		$selected_values  = $request->get_param( 'values' );
		$length           = sanitize_text_field( $request->get_param( 'length' ) );

		$attr = [];

		if ( ! empty( $length ) ) {
			$attr['number'] = (int) $length;
		}

		switch ( $filter_option ) {
			case 'category':
				foreach ( get_categories( $attr ) as $category ) {
					if ( static::filter_options( $category->term_id, $category->name, $selected_values, $searched_keyword ) ) {
						$categories[] = [
							'value' => (string) $category->term_id,
							'label' => $category->name,
						];
					}
				}
				break;
			case 'tag':
				foreach ( get_tags( $attr ) as $tag ) {
					if ( static::filter_options( $tag->term_id, $tag->name, $selected_values, $searched_keyword ) ) {
						$categories[] = [
							'value' => (string) $tag->term_id,
							'label' => $tag->name,
						];
					}
				}
				break;
			case 'author':
				foreach ( get_users( $attr ) as $user ) {
					if ( static::filter_options( $user->ID, $user->display_name, $selected_values, $searched_keyword ) ) {
						$categories[] = [
							'value' => (string) $user->ID,
							'label' => $user->display_name,
						];
					}
				}
				break;
			default:
				$attr['taxonomy'] = $filter_option;
				$terms            = get_terms( $attr );

				foreach ( $terms as $term ) {
					if ( static::filter_options( $term->term_id, $term->name, $selected_values, $searched_keyword ) ) {
						$categories[] = [
							'value' => (string) $term->term_id,
							'label' => $term->name,
						];
					}
				}
		}


		return new \WP_REST_Response( $categories );
	}

	/**
	 * Get the post list template according to it's type(button, radio, checkbox etc.)
	 *
	 * @param $request
	 *
	 * @return WP_REST_Response
	 */
	public static function get_option_template_ajax( $request ) {
		$filter_type          = $request->get_param( 'filter-type' );
		$filter_option        = $request->get_param( 'filter-option' );
		$filter_all_option    = $request->get_param( 'filter-all-option' );
		$filter_all_label     = $request->get_param( 'filter-all-label' );
		$classes              = $request->get_param( 'classes' );
		$css                  = $request->get_param( 'css' );
		$template             = $request->get_param( 'template' );
		$override_colors      = $request->get_param( 'override-colors' );
		$dropdown_icon        = $request->get_param( 'dropdown_icon' );
		$dropdown_icon_style  = $request->get_param( 'dropdown_icon_style' );
		$dropdown_animation   = $request->get_param( 'dropdown_animation' );
		$dropdown_placeholder = $request->get_param( 'dropdown_placeholder' );

		$filter_option_selection = json_decode( str_replace( array( '|{|', '|}|' ), array( '[', ']' ), $request->get_param( 'filter-option-selection' ) ), true );

		/* Add the 'All' option */
		if ( $filter_all_option ) {
			array_unshift( $filter_option_selection, 'all' );
		}

		$extra_attributes = [
			'css'                  => $css,
			'classes'              => $classes,
			'all_label'            => $filter_all_label,
			'template'             => $template,
			'override_colors'      => $override_colors,
			'dropdown_icon'        => $dropdown_icon,
			'dropdown_icon_style'  => $dropdown_icon_style,
			'dropdown_animation'   => $dropdown_animation,
			'dropdown_placeholder' => $dropdown_placeholder,
		];

		return new WP_REST_Response( array(
			'content' => TCB_Post_List_Filter::get_option_template( $filter_type, $filter_option, $filter_option_selection, $extra_attributes ),
		) );
	}

	/**
	 * Return the filtered options based on selected values and/or search keyword
	 *
	 * @param        $id
	 * @param        $label
	 * @param array  $selected_values
	 * @param string $searched_keyword
	 *
	 * @return bool
	 */
	public static function filter_options( $id, $label, $selected_values = [], $searched_keyword = '' ) {
		return
			( empty( $selected_values ) || in_array( $id, $selected_values ) ) && /* if there are pre-selected values, only return the options for those */
			( empty( $searched_keyword ) || stripos( $label, $searched_keyword ) !== false ); /* if there is a searched keyword, only return the options that match it */
	}

	/**
	 * Check if a given request has access to route
	 *
	 * @return \WP_Error|bool
	 */
	public static function route_permission() {
		$post_id = isset( $_REQUEST['post_id'] ) ? $_REQUEST['post_id'] : null;

		return \TCB_Product::has_external_access( $post_id );
	}
}
