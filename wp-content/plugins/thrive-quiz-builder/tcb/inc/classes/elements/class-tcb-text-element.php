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
 * Class TCB_Text_Element
 */
class TCB_Text_Element extends TCB_Element_Abstract {

	/**
	 * Name of the element
	 *
	 * @return string
	 */
	public function name() {
		return __( 'Text', 'thrive-cb' );
	}

	/**
	 * Get element alternate
	 *
	 * @return string
	 */
	public function alternate() {
		return 'text';
	}


	/**
	 * Return icon class needed for display in menu
	 *
	 * @return string
	 */
	public function icon() {
		return 'text';
	}

	/**
	 * Text element identifier
	 *
	 * @return string
	 */
	public function identifier() {
		return '.thrv_text_element';
	}

	/**
	 * Component and control config
	 *
	 * @return array
	 */
	public function own_components() {
		return array(
			'text'       => array(
				'config' => array(
					'ToggleControls' => array(
						'config'  => array(
							'buttons' => array(
								array( 'value' => 'tcb-text-font-size', 'text' => __( 'Font Size', 'thrive-cb' ), 'default' => true ),
								array( 'value' => 'tcb-text-line-height', 'text' => __( 'Line Height', 'thrive-cb' ) ),
								array( 'value' => 'tcb-text-letter-spacing', 'text' => __( 'Letter Spacing', 'thrive-cb' ) ),
							),
						),
						'extends' => 'ButtonGroup',
					),
					'FontSize'       => array(
						'config'  => array(
							'default' => '16',
							'min'     => '1',
							'max'     => '100',
							'label'   => '',
							'um'      => array( 'px', 'em' ),
							'css'     => 'fontSize',
						),
						'extends' => 'FontSize',
					),
					'LineHeight'     => array(
						'css_prefix' => tcb_selection_root() . ' ',
						'config'     => array(
							'default' => '1',
							'min'     => '1',
							'max'     => '200',
							'label'   => '',
							'um'      => array( 'em', 'px' ),
							'css'     => 'lineHeight',
						),
						'extends'    => 'LineHeight',
					),
					'LetterSpacing'  => array(
						'config'  => array(
							'default' => 'auto',
							'min'     => '0',
							'max'     => '100',
							'label'   => '',
							'um'      => array( 'px' ),
							'css'     => 'letterSpacing',
						),
						'extends' => 'Slider',
					),
					'FontColor'      => array(
						'config'  => array(
							'default' => '000',
							'label'   => 'Color',
							'options' => array(
								'output' => 'object',
							),
						),
						'extends' => 'ColorPicker',
					),
					'FontBackground' => array(
						'config'  => array(
							'default' => '000',
							'label'   => 'Highlight',
							'options' => array(
								'output' => 'object',
							),
						),
						'extends' => 'ColorPicker',
					),
					'FontFace'       => array(
						'css_prefix' => tcb_selection_root() . ' ',
						'config'     => array(
							'template' => 'controls/font-manager',
							'inline'   => true,
						),
					),
					'TextStyle'      => array(
						'css_prefix' => tcb_selection_root() . ' ',
						'config'     => array(
							'important' => true,
						),
					),
					'TextTransform'  => array(
						'config'  => array(
							'name'    => 'Transform',
							'buttons' => array(
								array(
									'icon'    => 'none',
									'text'    => '',
									'value'   => 'none',
									'default' => true,
								),
								array(
									'icon'  => 'format-all-caps',
									'text'  => '',
									'value' => 'uppercase',
								),
								array(
									'icon'  => 'format-capital',
									'text'  => '',
									'value' => 'capitalize',
								),
								array(
									'icon'  => 'format-lowercase',
									'text'  => '',
									'value' => 'lowercase',
								),
							),
						),
						'extends' => 'ButtonGroup',
					),
					'LineSpacing'    => array(
						'css_prefix' => tcb_selection_root() . ' ',
						'config'     => array(
							'important' => true,
						),
					),
					'HeadingToggle'  => array(
						'config'  => array(
							'label' => __( 'Include heading in table of contents element (if eligible)', 'thrive-cb' ),
						),
						'extends' => 'Switch',
					),
					'HeadingRename'  => array(
						'config'  => array(
							'label' => __( 'Customize heading label', 'thrive-cb' ),
						),
						'extends' => 'Switch',
					),
					'HeadingAltText' => array(
						'config'  => array(
							'placeholder' => __( 'Enter heading to be displayed', 'thrive-cb' ),
						),
						'extends' => 'LabelInput',
					),
				),
			),
			'layout'     => array(
				'config'            => array(
					'MarginAndPadding' => array(),
					'Position'         => array(
						'important' => true,
					),
				),
				'disabled_controls' => array(
					'Overflow',
				),
			),
			'borders'    => array(
				'config' => array(
					'Borders' => array(
						'important' => true,
					),
					'Corners' => array(
						'important' => true,
					),
				),
			),
			'shadow'     => array(
				'config' => array(
					'important'   => true,
					'with_froala' => true,
				),
			),
			'typography' => array(
				'hidden' => true,
			),
			'animation'  => array(
				'disabled_controls' => array(
					'.btn-inline:not(.anim-animation)',
				),
			),
			'scroll'     => array(
				'hidden'            => false,
				'disabled_controls' => array( '[data-value="sticky"]' ),
			),
		);
	}

	/**
	 * Element category that will be displayed in the sidebar
	 *
	 * @return string
	 */
	public function category() {
		return self::get_thrive_basic_label();
	}
}
