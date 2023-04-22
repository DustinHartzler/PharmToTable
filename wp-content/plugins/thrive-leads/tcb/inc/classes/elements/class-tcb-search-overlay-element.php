<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-visual-editor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

class TCB_Search_Overlay_Element extends TCB_ContentBox_Element {

	/**
	 * Name of the element
	 *
	 * @return string
	 */
	public function name() {
		return __( 'Search Overlay', 'thrive-cb' );
	}


	/**
	 * Section element identifier
	 *
	 * @return string
	 */
	public function identifier() {
		return '.tve-sf-overlay-container';
	}

	public function own_components() {
		$prefix_config = tcb_selection_root() . ' ';
		$bg_selector   = '>.tve-content-box-background';
		$components    = parent::own_components();

		unset( $components['contentbox'] );
		unset( $components['shared-styles'] );
		$components['layout'] = array(
			'disabled_controls' => array(
				'Display',
				'Float',
				'Position',
				'Width',
				'Alignment',
				'.tve-advanced-controls',
			),
			'config'            => array(
				'Height' => array(
					'important' => true,
				),
			),
		);

		$components['background'] = array(
			'config' => array(
				'ColorPicker' => array( 'css_prefix' => $prefix_config ),
				'PreviewList' => array( 'css_prefix' => $prefix_config ),
				'to'          => $bg_selector,
			),
		);

		$components['borders'] = array(
			'config' => array(
				'Borders' => array(
					'important' => true,
					'to'        => $bg_selector,
				),
				'Corners' => array(
					'important' => true,
					'to'        => $bg_selector,
				),
			),
		);

		$components['typography']       = array( 'hidden' => true );
		$components['scroll']           = array( 'hidden' => true );
		$components['responsive']       = array( 'hidden' => true );
		$components['animation']        = array( 'hidden' => true );
		$components['decoration']       = array( 'hidden' => true );
		$components['styles-templates'] = array( 'hidden' => true );

		return $components;
	}


	public function hide() {
		return true;
	}
}
