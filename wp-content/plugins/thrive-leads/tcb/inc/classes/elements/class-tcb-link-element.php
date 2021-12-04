<?php

require_once plugin_dir_path( __FILE__ ) . 'class-tcb-label-element.php';

/**
 * Class TCB_Label_Disabled_Element
 *
 * Non edited label element. For inline text we use typography control
 */
class TCB_Link_Element extends TCB_Element_Abstract {

	/**
	 * Name of the element
	 *
	 * @return string
	 */
	public function name() {
		return __( 'Link Text', 'thrive-cb' );
	}

	public function hide() {
		return true;
	}

	/**
	 * Section element identifier
	 *
	 * @return string
	 */
	public function identifier() {
		return '.thrv_text_element a, .tcb-styled-list a, .tcb-numbered-list a, .tve-input-option-text a';
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
	 * Links have hover states
	 *
	 * @return bool
	 */
	public function has_hover_state() {
		return true;
	}

	/**
	 * @return array|void
	 */
	public function general_components() {
		return array(
			'link'   => array(
				'config' => array(
					'ToggleColor'  => array(
						'config'  => array(
							'name'    => __( 'Color', 'thrive-cb' ),
							'buttons' => array(
								array( 'value' => 'inherit', 'text' => __( 'Inherit', 'thrive-cb' ), 'default' => true ),
								array( 'value' => 'specific', 'text' => __( 'Specific', 'thrive-cb' ) ),
							),
						),
						'extends' => 'Tabs',
					),
					'FontColor'    => array(
						'config'  => array(
							'default' => '000',
							'label'   => ' ',
							'options' => array(
								'output' => 'object',
							),
						),
						'extends' => 'ColorPicker',
					),
					'BgColor'      => array(
						'config'  => array(
							'default' => '000',
							'label'   => __( 'Highlight', 'thrive-cb' ),
							'options' => array(
								'output' => 'object',
							),
						),
						'extends' => 'ColorPicker',
					),
					'ToggleFont'   => array(
						'config'  => array(
							'name'    => __( 'Font', 'thrive-cb' ),
							'buttons' => array(
								array( 'value' => 'inherit', 'text' => __( 'Inherit', 'thrive-cb' ), 'default' => true ),
								array( 'value' => 'specific', 'text' => __( 'Specific', 'thrive-cb' ) ),
							),
						),
						'extends' => 'Tabs',
					),
					'FontFace'     => array(
						'config' => array(
							'label'    => ' ',
							'template' => 'controls/font-manager',
							'inline'   => true,
						),
					),
					'ToggleSize'   => array(
						'config'  => array(
							'name'    => __( 'Size', 'thrive-cb' ),
							'buttons' => array(
								array( 'value' => 'inherit', 'text' => __( 'Inherit', 'thrive-cb' ), 'default' => true ),
								array( 'value' => 'specific', 'text' => __( 'Specific', 'thrive-cb' ) ),
							),
						),
						'extends' => 'Tabs',
					),
					'FontSize'     => array(
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
					'TextStyle'    => array(
						'config' => array(
							'important' => true,
							'buttons'   => array(
								'underline'    => array(
									'data' => array( 'style' => 'text-decoration-line' ),
								),
								'line-through' => array(
									'data' => array( 'style' => 'text-decoration-line' ),
								),
							),
						),
					),
					'Effect'       => array(
						'config'  => array(
							'label' => __( 'Effect', 'thrive-cb' ),
						),
						'extends' => 'StyleChange',
					),
					'EffectPicker' => array(
						'config' => array(
							'label'   => __( 'Choose link effect', 'thrive-cb' ),
							'default' => 'none',
						),
					),
					'EffectColor'  => array(
						'config'  => array(
							'label'   => __( 'Effect Color', 'thrive-cb' ),
							'options' => array(
								'output'      => 'object',
								'showGlobals' => false,
							),
						),
						'extends' => 'ColorPicker',
					),
					'EffectSpeed'  => array(
						'label'   => __( 'Effect Speed', 'thrive-cb' ),
						'config'  => array(
							'default' => '0.2',
							'min'     => '0.05',
							'step'    => '0.05',
							'max'     => '1',
							'label'   => __( 'Speed', 'thrive-cb' ),
							'um'      => array( 's' ),
						),
						'extends' => 'Slider',
					),
				),
			),
			'shadow' => array(
				'order'  => 140,
				'config' => array(
					'disabled_controls' => array( 'drop', 'inner' ),
					'with_froala'       => true,
				),
			),
		);
	}
}
