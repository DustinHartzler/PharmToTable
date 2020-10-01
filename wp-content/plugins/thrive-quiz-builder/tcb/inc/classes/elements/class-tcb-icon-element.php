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
 * Class TCB_Icon_Element
 */
class TCB_Icon_Element extends TCB_Element_Abstract {

	/**
	 * @return string
	 */
	public function icon() {
		return 'icon';
	}

	/**
	 * @return string
	 */
	public function name() {
		return __( 'Icon', 'thrive-cb' );
	}

	/**
	 * Get element alternate
	 *
	 * @return string
	 */
	public function alternate() {
		return 'media,icon,icons';
	}


	/**
	 * @return string
	 */
	public function identifier() {
		return '.tve_lg_file .thrv_icon,.thrv_icon:not(.tve_lg_input_container .thrv_icon, .tve-login-form-input .thrv_icon)';
	}

	/**
	 * @return array
	 */
	public function own_components() {
		return array(
			'icon'       => array(
				'config' => array(
					'ColorPicker' => array(
						'css_prefix' => tcb_selection_root() . ' ',
						'css_suffix' => ' > :first-child',
						'config'     => array(
							'label' => __( 'Color', 'thrive-cb' ),
						),
					),
					'Slider'      => array(
						'config' => array(
							'default' => '30',
							'min'     => '8',
							'max'     => '1024',
							'label'   => __( 'Size', 'thrive-cb' ),
							'um'      => array( 'px' ),
							'css'     => 'fontSize',
						),
					),
					'link'        => array(
						'config' => array(
							'label' => __( 'Icon link', 'thrive-cb' ),
							'class' => 'thrv_icon',
						),
					),
					'StylePicker' => array(
						'config' => array(
							'label' => __( 'Choose icon style', 'thrive-cb' ),
							'items' => array(
								'circle_outlined'  => 'Circle Outlined',
								'circle_shaded'    => 'Circle Shaded',
								'circle_inverted'  => 'Circle Inverted',
								'rounded_outlined' => 'Rounded Outlined',
								'rounded_shaded'   => 'Rounded Shaded',
								'rounded_inverted' => 'Rounded Inverted',
								'square_outlined'  => 'Square Outlined',
								'square_shaded'    => 'Square Shaded',
								'square_inverted'  => 'Square Inverted',
							),
						),
					),
					'IconPicker'  => array(
						'config'  => array(
							'label_style' => __( 'Change style', 'thrive-cb' ),
							'label_modal' => __( 'Change icon', 'thrive-cb' ),
							'label'       => __( 'Icon and style', 'thrive-cb' ),
						),
						'extends' => 'ModalStylePicker',
					),
				),
			),
			'typography' => array( 'hidden' => true ),
			'shadow'     => array(
				'config' => array(
					'disabled_controls' => array( 'text' ),
				),
			),
			'layout'     => array(
				'config'            => array(),
				'disabled_controls' => array(
					'Width',
					'Height',
					'Display',
					'Overflow',
				),
			),
			'scroll'     => array(
				'hidden'            => false,
				'disabled_controls' => array( '[data-value="sticky"]' ),
			),
		);
	}

	/**
	 * @return bool
	 */
	public function has_hover_state() {
		return true;
	}

	/**
	 * Element category that will be displayed in the sidebar
	 *
	 * @return string
	 */
	public function category() {
		return $this->get_thrive_advanced_label();
	}
}
