<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

/**
 * Class TCB_Search_Form_Input_Element
 */
class TCB_Search_Form_Input_Element extends TCB_Element_Abstract {
	/**
	 * Name of the element
	 *
	 * @return string
	 */
	public function name() {
		return __( 'Search Input', 'thrive-cb' );
	}

	/**
	 * Section element identifier
	 *
	 * @return string
	 */
	public function identifier() {
		return '.thrv-sf-input';
	}

	/**
	 * There is no need for HTML for this element since we need it only for control filter
	 *
	 * @return string
	 */
	protected function html() {
		return '';
	}

	/**
	 * Hide Element From Sidebar Menu
	 *
	 * @return bool
	 */
	public function hide() {
		return true;
	}

	/**
	 * Whether or not the this element can be edited while under :hover state
	 *
	 * @return bool
	 */
	public function has_hover_state() {
		return true;
	}

	/**
	 * Component and control configuration
	 *
	 * @return array
	 */
	public function own_components() {
		$prefix_config = tcb_selection_root() . ' ';

		$controls_default_config = array(
			'css_suffix' => ' input',
			'css_prefix' => $prefix_config,
		);

		$tag_default_config = array(
			'css_suffix' => ' input',
		);

		return array(
			'search_form_input' => array(
				'config' => array(
					'InputPlaceholder' => array(
						'config'  => array(
							'label' => __( 'Placeholder', 'thrive-cb' ),
						),
						'extends' => 'LabelInput',
					),
				),
			),
			'typography'        => array(
				'config' => array(
					'FontSize'      => $controls_default_config,
					'FontColor'     => $controls_default_config,
					'TextAlign'     => $controls_default_config,
					'TextStyle'     => $controls_default_config,
					'TextTransform' => $controls_default_config,
					'FontFace'      => $controls_default_config,
					'LineHeight'    => $controls_default_config,
					'LetterSpacing' => $controls_default_config,
				),
			),
			'layout'            => array(
				'disabled_controls' => array(
					'Width',
					'Height',
					'Alignment',
					'Display',
					'margin',
					'.tve-advanced-controls',
				),
				'config'            => array(
					'MarginAndPadding' => $tag_default_config,
				),
			),
			'borders'           => array(
				'config' => array(
					'Borders' => array_merge( array( 'css_prefix' => $prefix_config ), $tag_default_config ),
					'Corners' => array_merge( array( 'css_prefix' => $prefix_config ), $tag_default_config ),
				),
			),
			'animation'         => array(
				'hidden' => true,
			),
			'background'        => array(
				'config' => array(
					'ColorPicker' => $tag_default_config,
					'PreviewList' => $tag_default_config,
				),
			),
			'shadow'            => array(
				'config' => $tag_default_config,
			),
			'responsive'        => array(
				'hidden' => true,
			),
			'styles-templates'  => array(
				'hidden' => true,
			),
		);
	}

}