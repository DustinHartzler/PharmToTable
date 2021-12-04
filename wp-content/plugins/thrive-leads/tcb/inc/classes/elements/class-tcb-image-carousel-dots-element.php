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
 * Class TCB_Image_Carousel_Arrows_Element
 */
class TCB_Image_Carousel_Dots_Element extends TCB_Icon_Element {
	/**
	 * Name of the element
	 *
	 * @return string
	 */
	public function name() {
		return __( 'Dots', 'thrive-cb' );
	}

	/**
	 * Element identifier
	 *
	 * @return string
	 */
	public function identifier() {
		return '.tcb-carousel-dots,.slick-dots';
	}

	/**
	 * Hide Element From Sidebar Menu
	 *
	 * @return bool
	 */
	public function hide() {
		return true;
	}

	/**
	 * @return array
	 */
	public function own_components() {
		$components = parent::own_components();

		$components['icon']['disabled_controls']   = array( 'ToggleURL', 'link', 'RotateIcon' );
		$components['layout']['disabled_controls'] = array(
			'Width',
			'Height',
			'Display',
			'Overflow',
			'ScrollStyle',
			'Alignment',
			'Position',
			'Float',
		);
		$components['scroll']                      = array( 'hidden' => true );
		$components['animation']                   = array( 'hidden' => false );

		$components['image_carousel_dots']                              = $components['icon'];
		$components['image_carousel_dots']['config']['HorizontalSpace'] = array(
			'config'  => array(
				'min'   => '0',
				'max'   => '100',
				'label' => __( 'Horizontal space', 'thrive-cb' ),
				'um'    => array( 'px', '%' ),
			),
			'extends' => 'Slider',
		);

		$components['layout']['config']['MarginAndPadding']['important'] = true;
		unset( $components['icon'] );

		return $components;
	}
}
