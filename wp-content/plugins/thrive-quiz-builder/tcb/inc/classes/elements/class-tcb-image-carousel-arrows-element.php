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
class TCB_Image_Carousel_Arrows_Element extends TCB_Icon_Element {
	/**
	 * Name of the element
	 *
	 * @return string
	 */
	public function name() {
		return __( 'Arrows', 'thrive-cb' );
	}

	/**
	 * Element identifier
	 *
	 * @return string
	 */
	public function identifier() {
		return '.thrv_icon.tcb-carousel-arrow';
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

		$components['icon']['disabled_controls']              = array( 'ToggleURL', 'link', 'RotateIcon' );
		$components['layout']['disabled_controls']            = array(
			'Width',
			'Height',
			'Display',
			'Overflow',
			'ScrollStyle',
			'Alignment',
			'Position',
			'Float',
		);
		$components['icon']['config']['Slider']['css_prefix'] = tcb_selection_root() . '.tcb-carousel-arrow';

		$components['scroll']    = array( 'hidden' => true );
		$components['animation'] = array( 'hidden' => false );

		$components['image_carousel_arrows'] = $components['icon'];
		unset( $components['icon'] );

		return $components;
	}
}
