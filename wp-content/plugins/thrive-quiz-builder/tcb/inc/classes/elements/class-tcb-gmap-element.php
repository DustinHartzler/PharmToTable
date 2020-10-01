<?php
/**
 * Created by PhpStorm.
 * User: Ovidiu
 * Date: 4/28/2017
 * Time: 4:08 PM
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

/**
 * Class TCB_Gmaps_Element
 */
class TCB_Gmap_Element extends TCB_Element_Abstract {
	/**
	 * Name of the element
	 *
	 * @return string
	 */
	public function name() {
		return __( 'Google Map', 'thrive-cb' );
	}

	/**
	 * Get element alternate
	 *
	 * @return string
	 */
	public function alternate() {
		return 'address';
	}


	/**
	 * Return icon class needed for display in menu
	 *
	 * @return string
	 */
	public function icon() {
		return 'gmaps';
	}

	/**
	 * Wordpress element identifier
	 *
	 * @return string
	 */
	public function identifier() {
		return '.thrv-google-map-embedded-code, .tve-flexible-container';
	}

	/**
	 * Component and control config
	 *
	 * @return array
	 */
	public function own_components() {
		return array(
			'gmap'       => array(
				'config' => array(
					'ExternalFields' => array(
						'config'  => array(
							'key'               => 'map',
							'shortcode_element' => 'iframe',
						),
						'extends' => 'CustomFields',
					),
					'address' => array(
						'config'  => array(
							'label'       => __( 'Address', 'thrive-cb' ),
							'placeholder' => __( 'Insert Address', 'thrive-cb' ),
						),
						'extends' => 'LabelInput',
					),
					'zoom'    => array(
						'config'  => array(
							'default' => '10',
							'min'     => '1',
							'max'     => '20',
							'label'   => __( 'Zoom', 'thrive-cb' ),
							'um'      => '',
						),
						'extends' => 'Slider',
					),
				),
			),
			'background' => array(
				'hidden' => true,
			),
			'typography' => array(
				'hidden' => true,
			),
			'animation'  => array(
				'hidden' => true,
			),
			'shadow'     => array(
				'config' => array(
					'disabled_controls' => array( 'text' ),
				),
			),
			'layout'     => array(
				'disabled_controls' => array(
					'padding',
					'Width',
					'Height',
					'.tve-advanced-controls',
					'Alignment',
				),
			),
		);
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
