<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @class Variable_Sensei_Course_Title
 *
 * @since 5.6.10
 */
class Variable_Sensei_Course_Title extends Variable {

	/**
	 * Set description and other admin props
	 */
	public function load_admin_details() {
		$this->description = __( "Displays the Course's Title.", 'automatewoo' );
	}

	/**
	 * Get Variable Value.
	 *
	 * @param \WP_Post $course     \WP_Post Object
	 * @param array    $parameters Variable parameters
	 * @return string
	 */
	public function get_value( $course, $parameters ) {
		return get_the_title( $course );
	}
}
