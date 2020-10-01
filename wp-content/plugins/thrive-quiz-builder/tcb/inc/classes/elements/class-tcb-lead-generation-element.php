<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package TCB2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden
}

/**
 * Class TCB_Lead_Generation_Element
 */
class TCB_Lead_Generation_Element extends TCB_Cloud_Template_Element_Abstract {

	/**
	 * @return string
	 */
	public function name() {
		return __( 'Lead Generation', 'thrive-cb' );
	}

	public function is_placeholder() {
		return false;
	}

	/**
	 * HTML layout of the element for when it's dragged in the canvas
	 *
	 * @return string
	 */
	public function html_placeholder( $title = null ) {
		if ( empty( $title ) ) {
			$title = $this->name();
		}

		return tcb_template( 'elements/element-placeholder', array(
			'icon'       => $this->icon(),
			'class'      => 'tcb-ct-placeholder',
			'title'      => $title,
			'extra_attr' => 'data-ct="' . $this->tag() . '-0" data-tcb-elem-type="'.$this->tag().'" data-tcb-lg-type="' . $this->tag() . '" data-specific-modal="lead-generation"',
		), true );
	}

	/**
	 * Get element alternate
	 *
	 * @return string
	 */
	public function alternate() {
		return 'form';
	}

	/**
	 * @return string
	 */
	public function icon() {
		return 'lead_gen';
	}

	/**
	 * @return string
	 */
	public function identifier() {
		return '.thrv_lead_generation';
	}

	/**
	 * @return string
	 */
	public function get_captcha_site_key() {

		$credentials = Thrive_Dash_List_Manager::credentials( 'recaptcha' );

		return ! empty( $credentials['site_key'] ) ? $credentials['site_key'] : '';
	}

	/**
	 * @return array
	 */
	public function own_components() {
		$lead_generation = array(
			'lead_generation' => array(
				'config' => array(
					'ModalPicker'        => array(
						'config' => array(
							'label' => __( 'Template', 'thrive-cb' ),
						),
					),
					'FormPalettes'       => array(
						'config'  => array(),
						'extends' => 'Palettes',
					),
					'connectionType'      => array(
						'config' => array(
							'name'    => __( 'Connection', 'thrive-cb' ),
							'buttons' => array(
								array(
									'text'    => 'API',
									'value'   => 'api',
									'default' => true,
								),
								array(
									'text'  => 'HTML code',
									'value' => 'custom-html',
								),
							),
						),
					),
					'FieldsControl'       => array(
						'config' => array(
							'sortable'      => true,
							'settings_icon' => 'pen-light',
						),
					),
					'HiddenFieldsControl' => array(
						'config'  => array(
							'sortable'      => false,
							'settings_icon' => 'pen-light',
						),
						'extends' => 'PreviewList',
					),
					'ApiConnections'      => array(
						'config' => array(),
					),
					'Captcha'             => array(
						'config'  => array(
							'name'     => '',
							'label'    => __( 'Captcha spam prevention', 'thrive-cb' ),
							'default'  => false,
							'site_key' => $this->get_captcha_site_key(),
						),
						'extends' => 'Switch',
					),
					'CaptchaTheme'        => array(
						'config'  => array(
							'name'    => __( 'Theme', 'thrive-cb' ),
							'options' => array(
								array(
									'value' => 'light',
									'name'  => __( 'Light', 'thrive-cb' ),
								),
								array(
									'value' => 'dark',
									'name'  => __( 'Dark', 'thrive-cb' ),
								),
							),
						),
						'extends' => 'Select',
					),
					'CaptchaType'         => array(
						'config'  => array(
							'name'    => __( 'Type', 'thrive-cb' ),
							'options' => array(
								array(
									'value' => 'image',
									'name'  => __( 'Image', 'thrive-cb' ),
								),
								array(
									'value' => 'audio',
									'name'  => __( 'Audio', 'thrive-cb' ),
								),
							),
						),
						'extends' => 'Select',
					),
					'CaptchaSize'         => array(
						'config'  => array(
							'name'    => __( 'Size', 'thrive-cb' ),
							'options' => array(
								array(
									'value' => 'normal',
									'name'  => __( 'Normal', 'thrive-cb' ),
								),
								array(
									'value' => 'compact',
									'name'  => __( 'Compact', 'thrive-cb' ),
								),
							),
						),
						'extends' => 'Select',
					),
					'consent'             => array(
						'config' => array(
							'labels' => array(
								'wordpress' => __( 'Create Wordpress account', 'thrive-cb' ),
								'default'   => __( '{service}', 'thrive-cb' ),
							),
						),
					),
				),
			),
			'typography'      => array(
				'hidden' => true,
			),
			'layout'          => array(
				'disabled_controls' => array(
					'.tve-advanced-controls',
				),
				'config'            => array(
					'Width' => array(
						'important' => true,
					),
				),
			),
			'borders'         => array(
				'disabled_controls' => array(),
			),
			'animation'       => array(
				'hidden' => true,
			),
			'shadow'          => array(
				'config' => array(
					'disabled_controls' => array( 'text' ),
				),
			),
		);

		return array_merge( $lead_generation, $this->group_component() );
	}

	/**
	 * Element category that will be displayed in the sidebar
	 *
	 * @return string
	 */
	public function category() {
		return $this->get_thrive_advanced_label();
	}

	/**
	 * Group Edit Properties
	 *
	 * @return array|bool
	 */
	public function has_group_editing() {
		return array(
			'select_values' => array(
				array(
					'value'    => 'all_labels',
					'selector' => '.thrv_text_element[data-label-for]',
					'name'     => __( 'Grouped Lead Generation Labels', 'thrive-cb' ),
					'singular' => __( '-- Label %s', 'thrive-cb' ),
				),
				array(
					'value'    => 'all_lead_gen_items',
					'selector' => '.tve_lg_input,.tve_lg_textarea',
					'name'     => __( 'Grouped Lead Generation Inputs', 'thrive-cb' ),
					'singular' => __( '-- Input %s', 'thrive-cb' ),
				),
				array(
					'value'    => 'all_radio_elements',
					'selector' => '.tve_lg_radio',
					'name'     => __( 'Grouped Lead Generation Radio', 'thrive-cb' ),
					'singular' => __( '-- Radio %s', 'thrive-cb' ),
				),
				array(
					'value'    => 'all_checkbox_elements',
					'selector' => '.tve_lg_checkbox:not(.tcb-lg-consent)',
					'name'     => __( 'Grouped Lead Generation Checkbox', 'thrive-cb' ),
					'singular' => __( '-- Checkbox %s', 'thrive-cb' ),
				),
				array(
					'value'    => 'all_dropdown_elements',
					'selector' => '.tve_lg_dropdown',
					'name'     => __( 'Grouped Lead Generation Dropdown', 'thrive-cb' ),
					'singular' => __( '-- Dropdown %s', 'thrive-cb' ),
				),
				array(
					'value'    => 'radio_options',
					'selector' => '.tve_lg_radio_wrapper',
					'name'     => __( 'Grouped Radio Options', 'thrive-cb' ),
					'singular' => __( '-- Option %s', 'thrive-cb' ),
				),
				array(
					'value'    => 'dropdown_options',
					'selector' => '.tve-lg-dropdown-option',
					'name'     => __( 'Grouped Dropdown Options', 'thrive-cb' ),
					'singular' => __( '-- Option %s', 'thrive-cb' ),
				),
				array(
					'value'    => 'checkbox_options',
					'selector' => '.tve_lg_checkbox_wrapper:not(.tcb-lg-consent .tve_lg_checkbox_wrapper)',
					'name'     => __( 'Grouped Checkbox Options', 'thrive-cb' ),
					'singular' => __( '-- Option %s', 'thrive-cb' ),
				),
			),
		);
	}
}
