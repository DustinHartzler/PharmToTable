<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

class TCB_Login_Element extends TCB_Cloud_Template_Element_Abstract {

	/**
	 * Name of the element
	 *
	 * @return string
	 */
	public function name() {
		return __( 'Login Form', 'thrive-cb' );
	}

	/**
	 * Return icon class needed for display in menu
	 *
	 * @return string
	 */
	public function icon() {
		return 'login_elem';
	}

	/**
	 * Get element alternate
	 *
	 * @return string
	 */
	public function alternate() {
		return 'login';
	}

	/**
	 * Element identifier
	 *
	 * @return string
	 */
	public function identifier() {
		return '.thrv-login-element';
	}

	/**
	 * Whether or not this element is a placeholder
	 *
	 * @return bool
	 */
	public function is_placeholder() {
		return false;
	}

	/**
	 * Element category that will be displayed in the sidebar
	 *
	 * @return string
	 */
	public function category() {
		return $this->get_thrive_integrations_label();
	}

	/**
	 * Element HTML
	 *
	 * @return string
	 */
	public function html() {
		return tcb_template( 'elements/login.php', array(), true );
	}

	/**
	 * Component and control config
	 *
	 * @return array
	 */
	public function own_components() {
		$login = array(
			'login'      => array(
				'config' => array(
					'AddRemoveLabels' => array(
						'config'     => array(
							'name'    => '',
							'label'   => __( 'Labels', 'thrive-cb' ),
							'default' => true,
						),
						'css_suffix' => ' .tcb-removable-label',
						'css_prefix' => '',
						'extends'    => 'Switch',
					),
					'RememberMe'      => array(
						'config'     => array(
							'name'    => '',
							'label'   => __( 'Remember Me', 'thrive-cb' ),
							'default' => true,
						),
						'css_suffix' => ' .tcb-remember-me-item',
						'css_prefix' => '',
						'extends'    => 'Switch',
					),
					'PassResetUrl'    => array(
						'config'     => array(
							'name'    => '',
							'label'   => __( 'Password Reset Link', 'thrive-cb' ),
							'default' => true,
						),
						'css_suffix' => ' .tcb-lost-password-link',
						'css_prefix' => '',
						'extends'    => 'Switch',
					),
					'Align'          => array(
						'config' => array(
							'name'       => __( 'Size and Alignment', 'thrive-cb' ),
							'full-width' => true,
							'buttons' => array(
								array(
									'icon'    => 'a_left',
									'value'   => 'left',
									'tooltip' => __( 'Align Left', 'thrive-cb' ),
								),
								array(
									'icon'    => 'a_center',
									'value'   => 'center',
									'default' => true,
									'tooltip' => __( 'Align Center', 'thrive-cb' ),
								),
								array(
									'icon'    => 'a_right',
									'value'   => 'right',
									'tooltip' => __( 'Align Right', 'thrive-cb' ),
								),
								array(
									'text'    => 'FULL',
									'value'   => 'full',
									'tooltip' => __( 'Full Width', 'thrive-cb' ),
								),
							),
						),
						'extends' => 'ButtonGroup',
					),
					'FormWidth'    => array(
						'config'  => array(
							'default' => '400',
							'min'     => '10',
							'max'     => '1080',
							'label'   => __( 'Form width', 'thrive-cb' ),
							'um'      => array( '%', 'px' ),
							'css'     => 'max-width',
						),
						'extends' => 'Slider',
					),
				),
			),
			'typography' => array(
				'hidden' => true,
			),
			'animation'  => array(
				'hidden' => true,
			),
		);

		return array_merge( $login, $this->group_component() );
	}

	/**
	 * Group Edit Properties
	 *
	 * @return array|bool
	 */
	public function has_group_editing() {

		return array(
			'exit_label'    => __( 'Exit Group Styling', 'thrive-cb' ),
			'select_values' => array(
				array(
					'value'    => 'all_form_items',
					'selector' => '.tve-login-form-item',
					'name'     => __( 'Grouped Form Items', 'thrive-cb' ),
					'singular' => __( '-- Form Item %s', 'thrive-cb' ),
				),
				array(
					'value'    => 'all_inputs',
					'selector' => '.tve-login-form-input',
					'name'     => __( 'Grouped Inputs', 'thrive-cb' ),
					'singular' => __( '-- Input %s', 'thrive-cb' ),
				),
				array(
					'value'    => 'all_labels',
					'selector' => '.tve-login-form-item .tcb-label',
					'name'     => __( 'Grouped Labels', 'thrive-cb' ),
					'singular' => __( '-- Label %s', 'thrive-cb' ),
				),
				array(
					'value'    => 'all_submit_buttons',
					'selector' => '.tar-login-elem-button',
					'name'     => __( 'Submit Buttons', 'thrive-cb' ),
					'singular' => __( '-- Label %s', 'thrive-cb' ),
				),
				array(
					'value'    => 'all_form_link',
					'selector' => '.tar-login-elem-link',
					'name'     => __( 'Form Links', 'thrive-cb' ),
					'singular' => __( '-- Link %s', 'thrive-cb' ),
				),
				array(
					'value'    => 'all_form_link_text',
					'selector' => '.tar-login-elem-link .tve-dynamic-link',
					'name'     => __( 'Form Links Texts', 'thrive-cb' ),
					'singular' => __( '-- Text %s', 'thrive-cb' ),
				),
				array(
					'value'    => 'all_form_titles',
					'selector' => '.thrv-form-title',
					'name'     => __( 'Form Title', 'thrive-cb' ),
					'singular' => __( '-- Title %s', 'thrive-cb' ),
				),
				array(
					'value'    => 'all_form_info',
					'selector' => '.thrv-form-info',
					'name'     => __( 'Form Texts', 'thrive-cb' ),
					'singular' => __( '-- Text %s', 'thrive-cb' ),
				),
				array(
					'value'    => 'all_submit_texts',
					'selector' => '.tar-login-submit  .tcb-button-text',
					'name'     => __( 'Submit Button Text', 'thrive-cb' ),
					'singular' => __( '-- Submit Text %s', 'thrive-cb' ),
				),
				array(
					'value'    => 'all_inputs_icons',
					'selector' => '.tve-login-form-input .thrv_icon',
					'name'     => __( 'Input Icons', 'thrive-cb' ),
					'singular' => __( '-- Input Icon %s', 'thrive-cb' ),
				),
				array(
					'value'    => 'all_states',
					'selector' => '.tve-form-state',
					'name'     => __( 'Form States', 'thrive-cb' ),
					'singular' => __( '-- Form State %s', 'thrive-cb' ),
				),
			),
		);
	}
}
