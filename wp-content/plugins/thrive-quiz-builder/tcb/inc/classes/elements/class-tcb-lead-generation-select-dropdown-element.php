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
 * Class TCB_Menu_Child_Element
 */
class TCB_Lead_Generation_Select_Dropdown_Element extends TCB_Element_Abstract {

	/**
	 * Name of the element
	 *
	 * @return string
	 */
	public function name() {
		return __( 'Lead Generation Option Container', 'thrive-cb' );
	}

	/**
	 * Section element identifier
	 *
	 * @return string
	 */
	public function identifier() {
		return '.tve-lg-dropdown-list';
	}

	/**
	 * Hidden element
	 *
	 * @return string
	 */
	public function hide() {
		return true;
	}

	/**
	 * Component and control config
	 *
	 * @return array
	 */
	public function own_components() {
		return array(
			'borders'          => array(
				'blocked_controls' => array(
					'Corners' => __( 'This is disabled for the current element because it can have an unpredictable behaviour', 'thrive-cb' ),
				),
				'config'           => array(
					'Borders' => array(
						'important' => true,
					),
					'Corners' => array(
						'important' => true,
					),
				),
			),
			'shadow'           => array(
				'config' => array(
					'important'      => true,
					'default_shadow' => 'none',
				),
			),
			'layout'           => array(
				'disabled_controls' => array(
					'margin',
					'.tve-advanced-controls',
					'Height',
					'Width',
					'Alignment',
					'Display',
				),
			),
			'typography'       => array( 'hidden' => true ),
			'animation'        => array( 'hidden' => true ),
			'responsive'       => array( 'hidden' => true ),
			'styles-templates' => array( 'hidden' => true ),

		);
	}
}
