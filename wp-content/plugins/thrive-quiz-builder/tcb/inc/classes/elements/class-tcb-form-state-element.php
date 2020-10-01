<?php

class TCB_Form_State_Element extends TCB_Element_Abstract {

	public function name() {
		return __( 'Form State', 'thrive-cb' );
	}

	public function identifier() {
		return '.tve-form-state';
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
			'typography' => array( 'hidden' => true, ),
			'animation'  => array( 'hidden' => true, ),
			'responsive' => array( 'hidden' => true, ),
			'background' => array(
				'config' => array(),
			),
			'shadow'     => array(
				'config' => array(),
			),
			'layout'     => array(
				'disabled_controls' => array( 'Width', 'Height', 'Display', 'Alignment', 'Float', 'Position', 'PositionFrom' ),
			),
			'borders'    => array(
				'config' => array(
					'Borders' => array(),
					'Corners' => array(),
				),
			),
		);
	}
}
