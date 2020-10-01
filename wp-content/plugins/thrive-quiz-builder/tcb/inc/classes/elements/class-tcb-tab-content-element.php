<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-visual-editor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

class TCB_Tab_Content_Element extends TCB_ContentBox_Element {

	/**
	 * Name of the element
	 *
	 * @return string
	 */
	public function name() {
		return __( 'Tab Content', 'thrive-cb' );
	}


	/**
	 * Section element identifier
	 *
	 * @return string
	 */
	public function identifier() {
		return '.tve_tab_content';
	}

	public function own_components() {
		$prefix_config = tcb_selection_root() . ' ';

		$components = parent::own_components();

		unset( $components['contentbox'] );
		unset( $components['shared-styles'] );
		$components['layout'] = array(
			'disabled_controls' => array(
				'Display',
				'Float',
				'Position',
			),
			'config'            => array(
				'Width'  => array(
					'important' => true,
				),
				'Height' => array(
					'important' => true,
				),
			),
		);

		$components['background'] = array(
			'config' => array(
				'ColorPicker' => array( 'css_prefix' => $prefix_config ),
				'PreviewList' => array( 'css_prefix' => $prefix_config ),
				'to'          => '>.tve-content-box-background',
			),
		);

		$components['borders'] = array(
			'config' => array(
				'Borders' => array(
					'important' => true,
					'to'        => '>.tve-content-box-background',
				),
				'Corners' => array(
					'important' => true,
					'to'        => '>.tve-content-box-background',
				),
			),
		);

		$prefix_config_text       = array( 'css_prefix' => $prefix_config . '.tve_tab_content ' );
		$components['typography'] = array(
			'disabled_controls' => array(),
			'config'            => array(
				'to'         => '.tve-cb',
				'FontSize'   => $prefix_config_text,
				'FontColor'  => $prefix_config_text,
				'LineHeight' => $prefix_config_text,
				'FontFace'   => $prefix_config_text,
			),
		);
		$components['scroll']     = array( 'hidden' => true );
		$components['responsive'] = array( 'hidden' => true );
		$components['animation']  = array( 'hidden' => true );

		return $components;
	}


	public function hide() {
		return true;
	}
}