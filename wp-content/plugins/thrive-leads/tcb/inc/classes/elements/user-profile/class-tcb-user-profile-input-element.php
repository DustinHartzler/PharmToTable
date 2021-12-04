<?php

class TCB_User_Profile_Input_Element extends TCB_Element_Abstract {

	/**
	 * Name of the element
	 *
	 * @return string
	 */
	public function name() {
		return __( 'Form Input', 'thrive-cb' );
	}

	/**
	 * Element identifier
	 *
	 * @return string
	 */
	public function identifier() {
		return '.tve-up-input';
	}

	/**
	 * Hide the element in the sidebar menu
	 *
	 * @return bool
	 */
	public function hide() {
		return true;
	}

	public function own_components() {
		$controls_default_config_text = array(
			'css_suffix' => array(
				' input',
				' input::placeholder',
				' textarea',
				' textarea::placeholder',
				' select',
				' select::placeholder',
			),
		);

		$controls_default_config = array(
			'css_suffix' => array(
				' input',
				' textarea',
				' select',
			),
		);

		return array(
			'up_input'         => array(
				'config' => array(
					'Width' => array(
						'config'  => array(
							'default' => '0',
							'min'     => '10',
							'max'     => '500',
							'label'   => __( 'Width', 'thrive-cb' ),
							'um'      => array( '%', 'px' ),
							'css'     => 'max-width',
						),
						'extends' => 'Slider',
					),
				),
			),
			'typography'       => array(
				'config' => array(
					'FontSize'      => $controls_default_config_text,
					'FontColor'     => $controls_default_config_text,
					'TextAlign'     => $controls_default_config_text,
					'TextStyle'     => $controls_default_config_text,
					'TextTransform' => $controls_default_config_text,
					'FontFace'      => $controls_default_config_text,
					'LineHeight'    => $controls_default_config_text,
					'LetterSpacing' => $controls_default_config_text,
				),
			),
			'layout'           => array(
				'disabled_controls' => array(
					'Width',
					'Height',
					'Alignment',
					'.tve-advanced-controls',
					'hr',
				),
				'config'            => array(
					'MarginAndPadding' =>
						array_merge(
							$controls_default_config,
							array( 'margin_suffix' => '' ) ),
				),
			),
			'borders'          => array(
				'config' => array(
					'Borders' => $controls_default_config,
					'Corners' => $controls_default_config,
				),
			),
			'animation'        => array(
				'hidden' => true,
			),
			'background'       => array(
				'config' => array(
					'ColorPicker' => $controls_default_config,
					'PreviewList' => $controls_default_config,
				),
			),
			'shadow'           => array(
				'config' => $controls_default_config,
			),
			'responsive'       => array(
				'hidden' => true,
			),
			'styles-templates' => array(
				'hidden' => true,
			),
		);
	}

	public function has_hover_state() {
		return true;
	}
}
