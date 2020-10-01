<?php

require_once plugin_dir_path( __FILE__ ) . 'class-tcb-label-element.php';

/**
 * Class TCB_Label_Disabled_Element
 *
 * Non edited label element. For inline text we use typography control
 */
class TCB_Menu_Item_Element extends TCB_Label_Element {

	/**
	 * Name of the element
	 *
	 * @return string
	 */
	public function name() {
		return __( 'Menu Item', 'thrive-cb' );
	}

	/**
	 * Section element identifier
	 *
	 * @return string
	 */
	public function identifier() {
		return '.thrv_widget_menu li';
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
	 * Removes the unnecessary components from the element json string
	 *
	 * @return array
	 */
	protected function general_components() {
		$general_components                                      = parent::general_components();
		$general_components['animation']['config']['hide_items'] = array( 'animation', 'tooltip', 'link' );

		unset( $general_components['responsive'], $general_components['styles-templates'] );

		return $general_components;
	}

	/**
	 * Component and control config
	 *
	 * @return array
	 */
	public function own_components() {
		return array(
			'menu_item'  => array(
				'config' => array(
					'HoverEffect' => array(
						'config'  => array(
							'name'    => __( 'Hover Effect', 'thrive-cb' ),
							'options' => array(
								''            => 'None',
								'c-underline' => 'Underline',
								'c-double'    => 'Double line',
								'c-brackets'  => 'Brackets',
								'c-thick'     => 'Thick Underline',
							),
						),
						'extends' => 'Select',
					),
					'StyleChange' => array(
						'config' => array(
							'label'      => __( 'Item style', 'thrive-cb' ),
							'label_none' => __( 'Choose...', 'thrive-cb' ),
						),
					),
					'StylePicker' => array(
						'config' => array(
							'label' => __( 'Choose item style', 'thrive-cb' ),
							'items' => $this->get_templates(),
						),
					),
					'HasIcon'     => array(
						'config'  => array(
							'label' => __( 'Show Icon', 'thrive-cb' ),
						),
						'extends' => 'Switch',
					),
					'ColorPicker' => array(
						'css_suffix' => ' > a .m-icon',
						'config'     => array(
							'label' => __( 'Icon Color', 'thrive-cb' ),
						),
					),
					'Slider'      => array(
						'css_suffix' => ' > a .m-icon',
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
			'typography' => array(
				'disabled_controls' => array(
					'.tve-advanced-controls',
				),
				'config'            => array(
					'FontColor'     => array(
						'css_prefix' => '',
						'css_suffix' => ' > a',
						'important'  => true,
					),
					'FontSize'      => array(
						'css_prefix' => '',
						'css_suffix' => ' > a',
						'important'  => true,
					),
					'FontFace'      => array(
						'css_prefix' => '',
						'css_suffix' => ' > a',
						'important'  => true,
					),
					'TextStyle'     => array(
						'css_suffix' => ' > a',
						'important'  => true,
					),
					'LineHeight'    => array(
						'css_suffix' => ' > a',
						'css_prefix' => '',
						'important'  => true,
					),
					'LetterSpacing' => array(
						'css_suffix' => ' > a',
						'important'  => true,
					),
					'TextTransform' => array(
						'css_suffix' => ' > a',
						'important'  => true,
					),
				),
			),
			'background' => array(
				'config' => array(
					'ColorPicker' => array(
						'config' => array(
							'important' => false,
						),
					),
				),
			),
			'layout'     => array(
				'disabled_controls' => array(
					'margin-top',
					'margin-bottom',
					'.tve-advanced-controls',
					'Alignment',
					'Display',
				),
			),
			'borders'    => array(
				'config' => array(
					'Corners' => array(
						'overflow' => false,
					),
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

	/**
	 * @inheritDoc
	 */
	public function active_state_config() {
		return true;
	}
}
