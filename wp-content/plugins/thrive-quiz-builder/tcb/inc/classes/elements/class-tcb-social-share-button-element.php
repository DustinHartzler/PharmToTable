<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

/**
 * Class TCB_Social_Follow_Item_Element
 */
class TCB_Social_Share_Button_Element extends TCB_Element_Abstract {
	/**
	 * Name of the element
	 *
	 * @return string
	 */
	public function name() {
		return __( 'Social Share Button', 'thrive-cb' );
	}

	/**
	 * Default element identifier
	 *
	 * @return string
	 */
	public function identifier() {
		return '.thrv_social .tve_s_item';
	}

	/**
	 * Either to display or not the element in the sidebar menu
	 *
	 * @return bool
	 */
	public function hide() {
		return true;
	}

	/**
	 * @return bool
	 */
	public function has_hover_state() {
		return true;
	}

	/**
	 * Component and control config
	 *
	 * @return array
	 */
	public function own_components() {
		$typography_defaults = array(
			'css_prefix' => '',
			'css_suffix' => '',
			'important'  => true,
		);

		$color_typography = array(
			'css_suffix' => ' a.tve_s_link .tve_s_text',
			'important'  => true,
		);

		return array(
			'social_share_button' => array(
				'config' => array(
					'NetworkColor' => array(
						'config'  => array(
							'label' => __( 'Network Color', 'thrive-cb' ),
						),
						'extends' => 'ColorPicker',
					),
				),
			),
			'typography'          => array(
				'css_prefix' => '.tve_s_text ',
				'config'     => array(
					'FontSize'      => $typography_defaults,
					'FontColor'     => $color_typography,
					'LineHeight'    => $typography_defaults,
					'FontFace'      => $typography_defaults,
					'TextStyle'     => $typography_defaults,
					'LetterSpacing' => $typography_defaults,
					'TextTransform' => $typography_defaults,
					'TextAlign'     => $typography_defaults,
				),
			),
			'styles-templates'    => array( 'hidden' => true ),
			'shadow'              => array(
				'config' => array(
					'css_suffix' => '',
					'important'  => true,
				),
			),
			'background'          => array(
				'config' => array(
					'ColorPicker' => array( 'css_prefix' => tcb_selection_root() . ' .tve_social_items ' ),
					'PreviewList' => array( 'css_prefix' => tcb_selection_root() . ' .tve_social_items ' ),
				),
			),
			'borders'             => array(
				'config' => array(
					'Borders' => array(
						'important' => true,
					),
					'Corners' => array(
						'important' => true,
					),
				),
			),
			'borders'             => array(
				'config' => array(
					'Borders' => array(
						'important' => true,
					),
					'Corners' => array(
						'important' => true,
					),
				),
			),
			'animation'           => array(
				'disabled_controls' => array( '.btn-inline.anim-link', '.btn-inline.anim-popup' ),
			),
			'layout'              => array(
				'disabled_controls' => array( 'Alignment', 'Display' ),
			),
		);
	}
}