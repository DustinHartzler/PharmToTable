<?php

class TCB_Login_Form_Input_Element extends TCB_Element_Abstract {

	/**
	 * Name of the element
	 *
	 * @return string
	 */
	public function name() {
		return __( 'Login Form Input', 'thrive-cb' );
	}

	/**
	 * Element identifier
	 *
	 * @return string
	 */
	public function identifier() {
		return '.tve-login-form-input';
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
		$prefix_config                = tcb_selection_root();
		$controls_default_config_text = array(
			'css_suffix' => array(
				' input',
				' input::placeholder',
			),
		);

		$controls_default_config = array(
			'css_suffix' => array(
				' input',
			),
		);

		return array(
			'login_form_input' => array(
				'config' => array(
					'icon_side' => array(
						'rem_ic_css_suf' => $controls_default_config['css_suffix'], //Remove Icon Css Suffix
						'css_suffix'     => ' .thrv_icon',
						'css_prefix'     => $prefix_config . ' ',
						'config'         => array(
							'name'    => __( 'Icon Side', 'thrive-cb' ),
							'buttons' => array(
								array(
									'value' => 'left',
									'text'  => __( 'Left', 'thrive-cb' ),
								),
								array(
									'value' => 'right',
									'text'  => __( 'Right', 'thrive-cb' ),
								),
							),
						),
					),
					'placeholder' => array(
						'config' => array(
							'label' => __( 'Placeholder', 'thrive-cb' ),
						),
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
					'MarginAndPadding' => $controls_default_config,
				),
			),
			'borders'          => array(
				'config' => array(
					'Borders' => array_merge( array( 'css_prefix' => $prefix_config . ' ' ), $controls_default_config ),
					'Corners' => array_merge( array( 'css_prefix' => $prefix_config . ' ' ), $controls_default_config ),
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
}
