<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

/**
 * Class TCB_Post_Author_Picture_Element
 */
class TCB_Post_Author_Picture_Element extends TCB_Image_Element {
	/**
	 * Name of the element
	 *
	 * @return string
	 */
	public function name() {
		return __( 'Author Image', 'thrive-cb' );
	}

	/**
	 * Return icon class needed for display in menu
	 *
	 * @return string
	 */
	public function icon() {
		return 'author-picture';
	}

	public function html() {
		return tcb_template( 'post-list-sub-elements/author-image.php', array(), true );
	}

	/**
	 * Component and control config
	 *
	 * @return array
	 */
	public function own_components() {
		$components = parent::own_components();

		/* change the default source type */
		$components['image']['config']['ImageSourceType']['config']['buttons'] = array(
			array(
				'value' => 'static',
				'text'  => __( 'Static', 'thrive-cb' ),
			),
			array(
				'value'   => 'dynamic',
				'text'    => __( 'Dynamic', 'thrive-cb' ),
				'default' => true,
			),
		);

		/* change the default selected value */
		$components['image']['config']['DynamicSourceType']['config']['default'] = 'author';

		return $components;
	}

	/**
	 * Element category that will be displayed in the sidebar
	 *
	 * @return string
	 */
	public function category() {
		return TCB_Post_List::elements_group_label();
	}
}
