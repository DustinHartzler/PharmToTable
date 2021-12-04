<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package TCB2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden
}

class TCB_Dynamic_Dropdown_Option_Element extends TCB_Element_Abstract {

	public function name() {
		return __( 'Dropdown Field Option', 'thrive-cb' );
	}

	public function identifier() {
		return '.tve-dynamic-dropdown-option';
	}

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
	 * @inheritDoc
	 */
	public function active_state_config() {
		return true;
	}

	public function own_components() {
		$prefix_config = tcb_selection_root() . ' ';
		$suffix        = ' .tve-input-option-text';

		return array(

			'typography'       => array(
				'config' => array(
					'FontColor'     => array(
						'css_suffix' => $suffix,
						'important'  => true,
					),
					'TextAlign'     => array(
						'css_suffix' => $suffix,
						'css_prefix' => $prefix_config,
						'important'  => true,
					),
					'FontSize'      => array(
						'css_suffix' => $suffix,
						'important'  => true,
					),
					'TextStyle'     => array(
						'css_suffix' => $suffix,
						'css_prefix' => $prefix_config,
						'important'  => true,
					),
					'LineHeight'    => array(
						'css_suffix' => $suffix,
						'important'  => true,
					),
					'FontFace'      => array(
						'css_suffix' => $suffix,
						'important'  => true,
					),
					'LetterSpacing' => array(
						'css_suffix' => $suffix,
						'css_prefix' => $prefix_config,
						'important'  => true,
					),
					'TextTransform' => array(
						'css_suffix' => $suffix,
						'css_prefix' => $prefix_config,
						'important'  => true,
					),
				),
			),
			'layout'           => array(
				'disabled_controls' => array(
					'margin',
					'.tve-advanced-controls',
					'Alignment',
					'Display',
				),
			),
			'animation'        => array(
				'hidden' => true,
			),
			'styles-templates' => array( 'hidden' => true ),
			'responsive'       => array( 'hidden' => true ),
		);
	}
}
