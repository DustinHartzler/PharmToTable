<?php

class TCB_TQB_Progress_Bar extends TCB_Element_Abstract {
	public function name() {
		return __( 'Quiz Progress bar', Thrive_Quiz_Builder::T );
	}

	public function identifier() {
		return '.tqb-progress-container';
	}

	public function hide() {
		return true;
	}

	public function own_components() {
		return array(
			'tqb_progress_bar' => array(
				'config' => array(
					'Palettes'            => array(
						'config'    => array(),
						'important' => true,
						'to'        => '.tqb-progress',
					),
					'ProgressBarPosition' => array(
						'config'     => array(
							'name'    => '',
							'label'   => __( 'Progress Bar Position', Thrive_Quiz_Builder::T ),
							'default' => true,
							'options' => array(
								array(
									'name'  => 'Above question',
									'value' => __( 'position_top', Thrive_Quiz_Builder::T ),
								),
								array(
									'name'  => 'Below question',
									'value' => __( 'position_bottom', Thrive_Quiz_Builder::T ),
								),
							),
						),
						'css_suffix' => '',
						'css_prefix' => '',
						'extends'    => 'Select',
					),
					'ProgressBarType'     => array(
						'config'     => array(
							'name'    => '',
							'label'   => __( 'Progress Type', Thrive_Quiz_Builder::T ),
							'default' => true,
							'options' => array(
								array(
									'name'  => 'Percent completed',
									'value' => __( 'percentage_completed', Thrive_Quiz_Builder::T ),
								),
								array(
									'name'  => 'Percentage remaining',
									'value' => __( 'percentage_remaining', Thrive_Quiz_Builder::T ),
								),
							),
						),
						'css_suffix' => '',
						'css_prefix' => '',
						'extends'    => 'Select',
					),
					'ProgressBarLabel'    => array(
						'config'     => array(
							'name'    => '',
							'label'   => __( 'Progress bar Label', Thrive_Quiz_Builder::T ),
							'default' => true,
						),
						'css_suffix' => '',
						'css_prefix' => '',
					),
				),
			),
			'typography'       => array( 'hidden' => true ),
			'animation'        => array( 'hidden' => true ),
			'responsive'       => array( 'hidden' => true ),
			'styles-templates' => array( 'hidden' => true ),
			'layout'           => array(
				'disabled_controls' => array( 'Width', 'Height', 'hr', 'Alignment', 'Display', '.tve-advanced-controls' ),
			),
		);
	}
}
