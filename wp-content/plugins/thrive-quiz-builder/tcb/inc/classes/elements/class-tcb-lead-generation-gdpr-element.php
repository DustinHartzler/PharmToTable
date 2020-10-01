<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package TCB2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden
}

class TCB_Lead_Generation_GDPR_Element extends TCB_Element_Abstract {

	public function name() {
		return __( 'Lead Generation GDPR Checkbox', 'thrive-cb' );
	}

	public function identifier() {
		return '.tcb-lg-consent';
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
	public function expanded_state_config() {
		return true;
	}

	/**
	 * @inheritDoc
	 */
	public function expanded_state_label() {
		return __( 'Selected', 'thrive-cb' );
	}

	public function own_components() {

		$prefix_config = tcb_selection_root() . ' ';

		$components = array(
			'lead_generation_gdpr' => array(
				'config' => array(
					'CheckboxPalettes'    => array(
						'to'        => '.tve_lg_checkbox_wrapper',
						'config'    => array(),
						'extends'   => 'Palettes',
						'important' => apply_filters( 'tcb_lg_color_inputs_important', true ),
					),
					'ShowLabel'           => array(
						'config'  => array(
							'label' => __( 'Show Label', 'thrive-cb' ),
						),
						'extends' => 'Switch',
					),
					'CheckboxSize'        => array(
						'to'         => '.tve_lg_checkbox_wrapper',
						'css_suffix' => ' .tve-checkmark',
						'config'     => array(
							'default' => '20',
							'min'     => '0',
							'max'     => '30',
							'label'   => __( 'Checkbox Size', 'thrive-cb' ),
							'um'      => array( 'px' ),
						),
						'extends'    => 'Slider',
					),
					'StyleChange'         => array(
						'to'     => '.tve_lg_checkbox_wrapper',
						'config' => array(
							'label'   => __( 'Checkbox Style', 'thrive-cb' ),
							'preview' => array(
								'key'   => '',
								'label' => 'default',
							),
						),
					),
					'CheckboxStylePicker' => array(
						'to'     => '.tve_lg_checkbox_wrapper',
						'config' => array(
							'label'   => __( 'Choose checkbox style', 'thrive-cb' ),
							'items'   => array(
								'default' => __( 'Default', 'thrive-cb' ),
								'style-1' => __( 'Style 1', 'thrive-cb' ),
								'style-2' => __( 'Style 2', 'thrive-cb' ),
								'style-3' => __( 'Style 3', 'thrive-cb' ),
								'style-4' => __( 'Style 4', 'thrive-cb' ),
								'style-5' => __( 'Style 5', 'thrive-cb' ),
								'style-6' => __( 'Style 6', 'thrive-cb' ),
								'style-7' => __( 'Style 7', 'thrive-cb' ),
							),
							'default' => 'no_style',
						),
					),
				),
			),
			'typography'           => array(
				'config' => array(
					'to'            => '.tve_lg_checkbox_wrapper',
					'FontColor'     => array(
						'css_suffix' => ' .tve-input-option-text',
						'important'  => true,
					),
					'TextAlign'     => array(
						'css_suffix' => ' .tve-input-option-text',
						'css_prefix' => $prefix_config,
						'important'  => true,
					),
					'FontSize'      => array(
						'css_suffix' => ' .tve-input-option-text',
						'important'  => true,
					),
					'TextStyle'     => array(
						'css_suffix' => ' .tve-input-option-text',
						'css_prefix' => $prefix_config,
						'important'  => true,
					),
					'LineHeight'    => array(
						'css_suffix' => ' .tve-input-option-text',
						'important'  => true,
					),
					'FontFace'      => array(
						'css_suffix' => ' .tve-input-option-text',
						'important'  => true,
					),
					'LetterSpacing' => array(
						'css_suffix' => ' .tve-input-option-text',
						'css_prefix' => $prefix_config,
						'important'  => true,
					),
					'TextTransform' => array(
						'css_suffix' => ' .tve-input-option-text',
						'css_prefix' => $prefix_config,
						'important'  => true,
					),
				),
			),
			'layout'               => array(
				'disabled_controls' => array(
					'Width',
					'Height',
					'Alignment',
					'.tve-advanced-controls',
					'hr',
				),
			),
			'borders'              => array(
				'config' => array(
					'to'            => '.tve_lg_checkbox_wrapper',
				),
			),
			'animation'            => array(
				'hidden' => true,
			),
			'background'           => array(
				'config' => array(
					'to'            => '.tve_lg_checkbox_wrapper',
				),
			),
			'shadow'               => array(
				'hidden' => true,
			),
			'styles-templates'     => array(
				'config' => array(),
			),
			'responsive'           => array(
				'hidden' => true,
			),
		);

		return array_merge( $components, $this->group_component() );
	}
}
