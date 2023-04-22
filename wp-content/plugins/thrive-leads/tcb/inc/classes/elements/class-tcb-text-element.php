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
			'text'                => array(
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
					'FontSize'       => [
						'config'  => [
							'default' => '16',
							'min'     => '1',
							'max'     => '100',
							'label'   => '',
							'um'      => [ 'px', 'em' ],
							'css'     => 'fontSize',
						],
						'extends' => 'FontSize',
					],
					'LineHeight'     => array(
						'css_prefix' => tcb_selection_root() . ' ',
						'config'     => [
							'default' => '1',
							'min'     => '1',
							'max'     => '200',
							'label'   => '',
							'um'      => [ 'em', 'px' ],
							'css'     => 'lineHeight',
						],
						'extends'    => 'LineHeight',
					),
					'LetterSpacing'  => [
						'config'  => [
							'default' => 'auto',
							'min'     => '0',
							'max'     => '100',
							'label'   => '',
							'um'      => [ 'px', 'em' ],
							'css'     => 'letterSpacing',
						],
						'extends' => 'Slider',
					],
					'FontColor'      => [
						'config'  => [
							'default' => '000',
							'label'   => 'Color',
							'options' => [
								'output' => 'object',
							],
						],
						'extends' => 'ColorPicker',
					],
					'FontBackground' => [
						'config'  => [
							'default' => '000',
							'label'   => 'Highlight',
							'options' => [
								'output' => 'object',
							],
						],
						'extends' => 'ColorPicker',
					],
					'FontFace'       => array(
						'css_prefix' => tcb_selection_root() . ' ',
						'config'     => [
							'template' => 'controls/font-manager',
							'inline'   => true,
						],
					),
					'TextStyle'      => array(
						'css_prefix' => tcb_selection_root() . ' ',
						'config'     => [
							'important' => true,
						],
					),
					'TextTransform'  => [
						'config'  => [
							'name'    => 'Transform',
							'buttons' => [
								[
									'icon'    => 'none',
									'text'    => '',
									'value'   => 'none',
									'default' => true,
								],
								[
									'icon'  => 'format-all-caps',
									'text'  => '',
									'value' => 'uppercase',
								],
								[
									'icon'  => 'format-capital',
									'text'  => '',
									'value' => 'capitalize',
								],
								[
									'icon'  => 'format-lowercase',
									'text'  => '',
									'value' => 'lowercase',
								],
							],
						],
						'extends' => 'ButtonGroup',
					],
					'LineSpacing'    => array(
						'css_prefix' => tcb_selection_root() . ' ',
						'config'     => [
							'important' => true,
						],
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
			'layout'              => [
				'config'            => [
					'MarginAndPadding' => [],
					'Position'         => [
						'important' => true,
					],
				],
				'disabled_controls' => [
					'Overflow',
					'ScrollStyle',
				],
			],
			'borders'             => [
				'config' => [
					'Borders' => [
						'important' => true,
					],
					'Corners' => [
						'important' => true,
					],
				],
			],
			'shadow'              => [
				'config' => [
					'important'   => true,
					'with_froala' => true,
				],
			],
			'typography'          => [
				'hidden' => true,
			],
			'animation'           => array(
				'disabled_controls' => array(
					'.btn-inline:not(.anim-animation)',
				),
			),
			'scroll'              => [
				'hidden'            => false,
				'disabled_controls' => [ '[data-value="sticky"]' ],
			],
			'conditional-display' => [
				'hidden' => false,
			],
		);
	}

	/**
	 * Element category that will be displayed in the sidebar
	 *
	 * @return string
	 */
	public function category() {
		return static::get_thrive_basic_label();
	}

	/**
	 * Element info
	 *
	 * @return string|string[][]
	 */
	public function info() {
		return [
			'instructions' => [
				'type' => 'help',
				'url'  => 'text',
				'link' => 'https://help.thrivethemes.com/en/articles/4425764-how-to-use-the-text-element',
			],
		];
	}
}
