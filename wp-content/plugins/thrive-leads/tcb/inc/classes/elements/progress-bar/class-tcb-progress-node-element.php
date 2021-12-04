<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

/**
 * Class TCB_Progress_Node_Element
 *
 */
class TCB_Progress_Node_Element extends TCB_Element_Abstract {

	public function name() {
		return __( 'Node', 'thrive-cb' );
	}

	public function identifier() {
		return '.tve-progress-node';
	}

	public function hide() {
		return true;
	}

	/**
	 * @inheritDoc
	 */
	public function expanded_state_config() {
		return true;
	}

	/**
	 * @return bool
	 */
	public function has_hover_state() {
		return true;
	}

	/**
	 * @inheritDoc
	 */
	public function expanded_state_apply_inline() {
		return true;
	}

	/**
	 *
	 * @inheritDoc
	 */
	public function expanded_state_label() {
		return __( 'Completed', 'thrive-cb' );
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
				'disabled_controls' => array( 'Alignment', 'Display', '.tve-advanced-controls', 'Width', 'Height' ),
			),
			'borders'    => array(
				'disabled_controls' => array(),
				'config'            => array(
					'Corners' => array(
						'overflow' => false,
					),
				),
			),
		);
	}
}
