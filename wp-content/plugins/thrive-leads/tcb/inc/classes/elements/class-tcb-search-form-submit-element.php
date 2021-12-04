<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

/**
 * Class TCB_Search_Form_Submit_Element
 */
class TCB_Search_Form_Submit_Element extends TCB_Element_Abstract {
	/**
	 * Name of the element
	 *
	 * @return string
	 */
	public function name() {
		return __( 'Search Submit', 'thrive-cb' );
	}

	/**
	 * Section element identifier
	 *
	 * @return string
	 */
	public function identifier() {
		return '.thrv-sf-submit';
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
		$prefix = tcb_selection_root( false ) . ' ';

		$controls_default_config = array(
			'css_suffix' => ' button',
			'css_prefix' => $prefix,
		);

		return array(
			'search_form_submit' => array(
				'config' => array(
					'MasterColor'  => array(
						'css_suffix' => $controls_default_config['css_suffix'],
						'css_prefix' => $prefix,
						'config'     => array(
							'default'             => '000',
							'label'               => __( 'Master Color', 'thrive-cb' ),
							'important'           => true,
							'affected_components' => array( 'shadow', 'background', 'borders' ),
							'options'             => array(
								'showGlobals' => false,
							),
						),
					),
				),
			),
			'typography'         => array(
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
			'layout'             => array(
				'disabled_controls' => array(
					'Width',
					'Height',
					'Alignment',
					'Display',
					'margin',
					'.tve-advanced-controls',
				),
				'config'            => array(
					'MarginAndPadding' => $controls_default_config,
				),
			),
			'borders'            => array(
				'config' => array(
					'Borders' => $controls_default_config,
					'Corners' => $controls_default_config,
				),
			),
			'animation'          => array(
				'hidden' => true,
			),
			'responsive'         => array(
				'hidden' => true,
			),
			'background'         => array(
				'config' => array(
					'ColorPicker' => $controls_default_config,
					'PreviewList' => $controls_default_config,
				),
			),
			'shadow'             => array(
				'config' => $controls_default_config,
			),
			'styles-templates'   => array(
				'hidden' => true,
			),
		);
	}
}