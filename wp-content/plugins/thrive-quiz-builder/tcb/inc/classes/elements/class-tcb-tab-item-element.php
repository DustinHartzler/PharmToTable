<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-visual-editor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

class TCB_Tab_Item_Element extends TCB_Element_Abstract {

	/**
	 * Name of the element
	 *
	 * @return string
	 */
	public function name() {
		return __( 'Tab Item', 'thrive-cb' );
	}


	/**
	 * Section element identifier
	 *
	 * @return string
	 */
	public function identifier() {
		return '.tve_tab_title_item';
	}


	public function hide() {
		return true;
	}

	/**
	 * @inheritDoc
	 */
	public function expanded_state_label() {
		return __( 'Selected', 'thrive-cb' );
	}

	public function own_components() {
		$prefix_config = tcb_selection_root() . ' ';
		$suffix        = ' .tve_tab_title > .tve-tab-text';

		return array(
			'tab_item'   => array(
				'config' => array(
					'TextTypeDropdown' => array(
						'config'  => array(
							'default'     => 'none',
							'name'        => __( 'Change text type', 'thrive-cb' ),
							'label_col_x' => 6,
							'options'     => array(
								array(
									'name'  => __( 'Heading 1', 'thrive-cb' ),
									'value' => 'h1',
								),
								array(
									'name'  => __( 'Heading 2', 'thrive-cb' ),
									'value' => 'h2',
								),
								array(
									'name'  => __( 'Heading 3', 'thrive-cb' ),
									'value' => 'h3',
								),
								array(
									'name'  => __( 'Heading 4', 'thrive-cb' ),
									'value' => 'h4',
								),
								array(
									'name'  => __( 'Heading 5', 'thrive-cb' ),
									'value' => 'h5',
								),
								array(
									'name'  => __( 'Heading 6', 'thrive-cb' ),
									'value' => 'h6',
								),
								array(
									'name'  => __( 'Paragraph', 'thrive-cb' ),
									'value' => 'p',
								),
								array(
									'name'  => __( 'Plain text', 'thrive-cb' ),
									'value' => 'span',
								),
							),
						),
						'extends' => 'Select',
					),
					'HasIcon'          => array(
						'config'  => array(
							'label' => __( 'Show Icon', 'thrive-cb' ),
						),
						'extends' => 'Switch',
					),
					'HasImage'         => array(
						'config'  => array(
							'label' => __( 'Show Tab Image', 'thrive-cb' ),
						),
						'extends' => 'Switch',
					),
					'ColorPicker'      => array(
						'config'     => array(
							'label' => __( 'Icon Color', 'thrive-cb' ),
						),
						'css_suffix' => ' .tve-tab-icon',
					),
					'Slider'           => array(
						'config'     => array(
							'default' => 20,
							'min'     => 1,
							'max'     => 100,
							'label'   => __( 'Size', 'thrive-cb' ),
							'um'      => array( 'px' ),
							'css'     => 'fontSize',
						),
						'extends'    => 'Slider',
						'css_suffix' => ' .tve-tab-icon',
						'css_prefix' => $prefix_config,
					),
				),
			),
			'typography' => array(
				'config'            => array(
					'FontColor'     => array(
						'css_suffix' => $suffix,
						'important'  => true,
					),
					'FontSize'      => array(
						'css_suffix' => $suffix,
						'important'  => true,
					),
					'TextStyle'     => array(
						'css_suffix' => $suffix,
						'css_prefix' => $prefix_config,
						'important'  => true,
					),
					'LineHeight'    => array(
						'css_suffix' => $suffix,
						'important'  => true,
					),
					'FontFace'      => array(
						'css_suffix' => $suffix,
						'important'  => true,
					),
					'LetterSpacing' => array(
						'css_suffix' => $suffix,
						'css_prefix' => $prefix_config,
						'important'  => true,
					),
					'TextTransform' => array(
						'css_suffix' => $suffix,

						'important' => true,
					),
				),
				'disabled_controls' => array( 'TextAlign', '.tve-advanced-controls' ),
			),
			'animation'  => array( 'hidden' => true ),
			'layout'     => array(
				'disabled_controls' => array(
					'Alignment',
					'Display',
					'.tve-advanced-controls',
				),
				'config'            => array(
					'MarginAndPadding' => array(
						'css_prefix' => $prefix_config,
						'important'  => true,
					),
					'Width'            => array(
						'css_prefix' => $prefix_config,
						'important'  => true,
					),
					'Height'           => array(
						'css_prefix' => $prefix_config,
						'important'  => true,
					),
				),
			),
			'background' => array(
				'config' => array(
					'ColorPicker' => array( 'css_prefix' => $prefix_config ),
					'PreviewList' => array( 'css_prefix' => $prefix_config ),
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
			'shadow'     => array( 'config' => array( 'css_prefix' => $prefix_config ) ),
			'responsive' => array( 'hidden' => true ),
		);
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
}
