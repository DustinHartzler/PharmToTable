<?php


class TCB_Toc_Number_Element extends TCB_Label_Disabled_Element {
	public function name() {
		return __( 'Number', 'thrive-cb' );
	}

	/**
	 * Element identifier
	 *
	 * @return string
	 */
	public function identifier() {
		return '.tve-toc-number';
	}

	/**
	 * Either to display or not the element in the sidebar menu
	 *
	 * @return bool
	 */
	public function hide() {
		return true;
	}

	public function own_components() {
		return array(
			'toc_number' => array(
				'config' => array(
					'NumberSuffix' => array(
						'config'  => array(
							'label' => 'Suffix',
						),
						'extends' => 'LabelInput',
					),
				),
			),
			'typography' => array(
				'disabled_controls' => array(
					'TextTransform',
					'typography-text-transform-hr',
					'.tve-advanced-controls',
				),
				'config'            => array(
					'FontColor'  => array(
						'css_suffix' => ' .tve-toc-disabled',
					),
					'FontSize'   => array(
						'css_suffix' => ' .tve-toc-disabled',
					),
					'FontFace'   => array(
						'css_suffix' => ' .tve-toc-disabled',
					),
					'TextStyle'  => array(
						'css_suffix' => ' .tve-toc-disabled',
					),
					'LineHeight' => array(
						'css_suffix' => ' .tve-toc-disabled',
					),
				),
			),
			'layout'     => array(
				'disabled_controls' => array(
					'.tve-advanced-controls',
					'Width',
					'Height',
					'Alignment',
				),
			),
		);
	}
}
