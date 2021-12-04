<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

/**
 * Class TCB_Social_Follow_Item_Element
 */
class TCB_Social_Share_Icon_Element extends TCB_Social_Follow_Item_Element {
	/**
	 * Element name
	 *
	 * @return string
	 */
	public function name() {
		return __( 'Social Share Icon', 'thrive-cb' );
	}

	/**
	 * Element identifier
	 *
	 * @return string
	 */
	public function identifier() {
		return '.tve_share_item .tve_s_icon';
	}

	/**
	 * Component and control config
	 *
	 * @return array
	 */
	public function own_components() {
		$components = parent::own_components();

		$components['animation']['disabled_controls'] = array( '.btn-inline.anim-link', '.btn-inline.anim-popup' );

		$components['social_follow_item']['config']['Slider']['css_suffix'] = '.tve_s_item .tve_s_icon ';

		$components['social_follow_item']['disabled_controls'] = array( 'NetworkColor' );

		$components['background']['config'] = array(
			'ColorPicker' => array( 'css_prefix' => tcb_selection_root() . ' a ' ),
			'PreviewList' => array( 'css_prefix' => tcb_selection_root() . ' a ' ),
		);

		$components['borders'] = array(
			'config' => array(
				'Borders' => array( 'css_prefix' => '', 'important' => 'true' ),
				'Corners' => array( 'css_prefix' => '', 'important' => 'true' ),
			),
		);

		$components['shadow'] = array(
			'config' => array(
				'css_prefix' => '',
				'css_suffix' => '',
				'important'  => 'true',
				'disabled_controls' => array( 'text' ),
			),
		);

		return $components;
	}

}
