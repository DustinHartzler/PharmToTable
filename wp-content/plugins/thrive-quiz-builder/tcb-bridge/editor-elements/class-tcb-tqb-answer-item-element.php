<?php

class TCB_TQB_Answer_Item extends TCB_Element_Abstract {
	public function name() {
		return __( 'Answer Item', Thrive_Quiz_Builder::T );
	}

	public function identifier() {
		return '.tqb-editor-answer-wrapper';
	}

	public function hide() {
		return true;
	}

	public function own_components() {
		return array(
			'answer_item'      => array(),
			'shadow'           => array(
				'config' => array(
					'important' => true,
				),
			),
			'borders'          => array(
				'config' => array(
					'Borders' => array(
						'important' => true,
					),
					'Corners' => array(
						'important' => true,
						'overflow'  => false,
					),
				),
			),
			'layout'           => array( 'hidden' => true ),
			'typography'       => array( 'hidden' => true ),
			'animation'        => array( 'hidden' => true ),
			'responsive'       => array( 'hidden' => true ),
			'styles-templates' => array( 'hidden' => true ),
		);
	}
}
