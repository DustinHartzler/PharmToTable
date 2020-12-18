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
 * Class TCB_Rating_Element
 */
class TCB_Rating_Element extends TCB_Element_Abstract {

	/**
	 * Name of the element
	 *
	 * @return string
	 */
	public function name() {
		return __( 'Star Rating', 'thrive-cb' );
	}

	/**
	 * Get element alternate
	 *
	 * @return string
	 */
	public function alternate() {
		return 'review';
	}

	/**
	 * Return icon class needed for display in menu
	 *
	 * @return string
	 */
	public function icon() {
		return 'rating';
	}

	/**
	 * Element identifier
	 *
	 * @return string
	 */
	public function identifier() {
		return '.thrv-rating';
	}

	/**
	 * The HTML is generated from js
	 *
	 * @return string
	 */
	protected function html() {
		return '';
	}

	/**
	 * Component and control config
	 *
	 * @return array
	 */
	public function own_components() {
		return array(
			'rating'           => array(
				'config' => array(
					'ratingValue' => array(
						'config' => array(
							'default_value' => 2.5,
							'default_max'   => 5,
							'max_size'      => 10,
						),
					),
					'ExternalFields'       => array(
						'config'  => array(
							'key'           => 'number',
							'shortcode_element' => '.thrv-rating',
						),
						'extends' => 'CustomFields',
					),
					'style'       => array(
						'config' => array(
							'label' => __( 'Style', 'thrive-cb' ),
						),
					),
					'stylePicker' => array(
						'config' => array(
							'label' => __( 'Change style', 'thrive-cb' ),
						),
					),
					'size'        => array(
						'config' => array(
							'default' => '25',
							'min'     => '10',
							'max'     => '150',
							'label'   => __( 'Size', 'thrive-cb' ),
							'um'      => array( 'px' ),
						),
					),
					'background'  => array(
						'config' => array(
							'label'   => __( 'Background', 'thrive-cb' ),
							'options' => array( 'noBeforeInit' => false ),
						),
					),
					'fill'        => array(
						'config' => array(
							'label'   => __( 'Fill', 'thrive-cb' ),
							'options' => array( 'noBeforeInit' => false ),
						),
					),
					'outline'     => array(
						'config' => array(
							'label'   => __( 'Outline', 'thrive-cb' ),
							'options' => array( 'noBeforeInit' => false ),
						),
					),
				),
				'order'  => 1,
			),
			'typography'       => array(
				'hidden' => true,
			),
			'animation'        => array(
				'hidden' => true,
			),
			'styles-templates' => array(
				'hidden' => true,
			),
			'layout'           => array(
				'disabled_controls' => array(
					'Width',
					'Height',
					'Overflow',
				),
			),
			'shadow'           => array(
				'config' => array(
					'disabled_controls' => array( 'text' ),
				),
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
