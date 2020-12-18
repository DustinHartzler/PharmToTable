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
 * Class TCB_Countdown_Digit_Element
 */
class TCB_Countdown_Digit_Element extends TCB_Element_Abstract {

	/**
	 * Name of the element
	 *
	 * @return string
	 */
	public function name() {
		return __( 'Countdown Digit', 'thrive-cb' );
	}


	/**
	 * Section element identifier
	 *
	 * @return string
	 */
	public function identifier() {
		return '.tve-countdown-digit';
	}

	public function own_components() {
		$digit_cfg   = array( 'css_prefix' => tcb_selection_root() . ' ', 'css_suffix' => array( ' span', ' span::before' ), 'important' => true );
		$wrapper_cfg = array( 'css_prefix' => tcb_selection_root() . ' ', 'css_suffix' => ' .t-digit-part' );

		return array(
			'countdown_digit' => array(
				'config' => array(
					'FontColor'   => array_merge( $digit_cfg, array(
							'config'  => array(
								'default' => '000',
								'label'   => 'Color',
								'options' => array(
									'output' => 'object',
								),
							),
							'extends' => 'ColorPicker',
						)
					),
					'TextStyle'   => $digit_cfg,
					'FontFace'    =>
						array_merge( $digit_cfg, array(
								'config' => array(
									'template' => 'controls/font-manager',
									'inline'   => false,
								),
							)
						),
					'BorderColor' => array(
						'config'  => array(
							'default' => '000',
							'label'   => __( 'Divider color', 'thrive-cb' ),
							'options' => array(
								'output' => 'object',
							),
						),
						'extends' => 'ColorPicker',

					),
					'BorderSize'  => array(
						'config'  => array(
							'min'   => '0',
							'max'   => '12',
							'step'  => '0.5',
							'um'    => array( 'px' ),
							'label' => __( 'Divider size', 'thrive-cb' ),
						),
						'extends' => 'Slider',
					),
					'BorderStyle' => array(
						'config'  => array(
							'name'    => __( 'Divider style', 'thrive-cb' ),
							'options' => array(
								'solid'  => __( 'Solid', 'thrive-cb' ),
								'dotted' => __( 'Dotted', 'thrive-cb' ),
								'dashed' => __( 'Dashed', 'thrive-cb' ),
							),
						),
						'extends' => 'Select',
					),
				),
			),
			'layout'          => array(
				'disabled_controls' => array( 'Display', 'Alignment', '.tve-advanced-controls', 'Width', 'Height' ),
				'config'            => array(
					'MarginAndPadding' => array(
						'css_prefix'     => tcb_selection_root() . ' ',
						'important'      => true,
						'padding_suffix' => array( ' .t-digit-part > span' ),
					),
				),
			),
			'typography'      => array(
				'hidden' => true,
			),
			'background'      => array(
				'config' => array(
					'ColorPicker' => $digit_cfg,
					'PreviewList' => $digit_cfg,
				),
			),
			'borders'         => array(
				'config' => array(
					'Borders' => $wrapper_cfg,
					'Corners' => array_merge( $wrapper_cfg, array( 'overflow' => false ) ),

				),
			),
			'responsive'      => array(
				'hidden' => true,
			),
			'shadow'          => array( 'config' => $wrapper_cfg ),
			'animation'       => array(
				'hidden' => true,
			),
		);
	}


	public function hide() {
		return true;
	}
}
