<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden
}

class TCB_User_Profile_Item_Element extends TCB_Element_Abstract {

	public function name() {
		return __( 'Form Item', 'thrive-cb' );
	}

	public function identifier() {
		return '.tve-up-item';
	}

	/**
	 * Hide Element From Sidebar Menu
	 *
	 * @return bool
	 */
	public function hide() {
		return true;
	}

	public function own_components() {

		return array(
			'up_item' => array(
				'config' => array(
					'FieldsLabel'        => array(
						'config'  => array(
							'name'    => __( 'Field label location', 'thrive-cb' ),
							'buttons' => array(
								array(
									'value'   => 'top',
									'text'    => __( 'Above', 'thrive-cb' ),
									'default' => true,
								),
								array(
									'value' => 'left',
									'text'  => __( 'Left', 'thrive-cb' ),
								),
								array(
									'value' => 'hidden',
									'text'  => __( 'Hidden', 'thrive-cb' ),
								),
							),
						),
						'extends' => 'ButtonGroup',
					),
				),
			),
			'typography'       => array(
				'hidden' => true,
			),
			'layout'           => array(
				'disabled_controls' => array(
					'Width',
					'Height',
					'Alignment',
					'Display',
					'.tve-advanced-controls',
				),
			),
			'animation'        => array(
				'hidden' => true,
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

