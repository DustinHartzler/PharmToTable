<?php

require_once plugin_dir_path( __FILE__ ) . 'class-tcb-menu-item-element.php';

/**
 * Class TCB_Label_Disabled_Element
 *
 * Non edited label element. For inline text we use typography control
 */
class TCB_Megamenu_Item_Element extends TCB_Menu_Item_Element {

	/**
	 * Name of the element
	 *
	 * @return string
	 */
	public function name() {
		return __( 'Mega Menu Item', 'thrive-cb' );
	}

	/**
	 * Section element identifier
	 *
	 * @return string
	 */
	public function identifier() {
		return '.tcb-mega-drop li a';
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
	 * Component and control config
	 *
	 * @return array
	 */
	public function own_components() {
		$typography_defaults = array(
			'css_prefix' => '',
			'css_suffix' => '',
			'important'  => true,
		);

		return array(
			'megamenu_item' => array(
				'config' => array(
					'HasIcon'     => array(
						'config'  => array(
							'label' => __( 'Show Icon', 'thrive-cb' ),
						),
						'extends' => 'Switch',
					),
					'HasImage'    => array(
						'config'  => array(
							'label' => __( 'Show Section Image', 'thrive-cb' ),
						),
						'extends' => 'Switch',
					),
					'ImageSide'   => array(
						'extends' => 'ButtonGroup',
					),
					'ColorPicker' => array(
						'css_suffix' => ' .m-icon',
						'config'     => array(
							'label' => __( 'Icon Color', 'thrive-cb' ),
						),
					),
					'Slider'      => array(
						'css_suffix' => ' .m-icon',
						'config'     => array(
							'default' => 30,
							'min'     => 1,
							'max'     => 50,
							'label'   => __( 'Size', 'thrive-cb' ),
							'um'      => array( 'px' ),
							'css'     => 'fontSize',
						),
					),
				),
			),
			'typography'    => array(
				'disabled_controls' => array(
					'.tve-advanced-controls',
				),
				'config'            => array(
					'FontColor'     => $typography_defaults,
					'FontSize'      => $typography_defaults,
					'FontFace'      => $typography_defaults,
					'TextStyle'     => $typography_defaults,
					'LineHeight'    => $typography_defaults,
					'LetterSpacing' => $typography_defaults,
					'TextTransform' => $typography_defaults,
				),
			),
			'background'    => array(
				'config' => array(
					'ColorPicker' => array(
						'config' => array(
							'important' => false,
						),
					),
				),
			),
			'layout'        => array(
				'disabled_controls' => array(
					'.tve-advanced-controls',
					'Alignment',
					'Display',
				),
			),
		);
	}

	/**
	 * Get all available menu item templates
	 *
	 * @return array
	 */
	public function get_templates() {
		return get_option( 'tve_menu_item_templates', array() );
	}
}
