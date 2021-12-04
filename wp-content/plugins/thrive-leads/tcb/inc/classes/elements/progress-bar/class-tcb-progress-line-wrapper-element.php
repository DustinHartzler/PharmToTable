<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

/**
 * Class TCB_Progress_Line_Element
 *
 */
class TCB_Progress_Line_Wrapper_Element extends TCB_Element_Abstract {

	public function name() {
		return __( 'Progress line wrapper', 'thrive-cb' );
	}

	public function identifier() {
		return '.tve-line-wrapper';
	}

	public function hide() {
		return true;
	}

	/**
	 * Component and control config
	 *
	 * @return array
	 */
	public function own_components() {
		return array(
			'typography' => array( 'hidden' => true ),
			'responsive' => array( 'hidden' => true ),
			'animation'  => array( 'hidden' => true ),
			'layout'     => array(
				'disabled_controls' => array( 'Alignment', 'Display', '.tve-advanced-controls', 'Width' ),
			),
		);
	}
}
