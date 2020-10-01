<?php


class TCB_Toc_Title_Element extends TCB_ContentBox_Element {

	public function name() {
		return __( 'Table of Contents Title', 'thrive-cb' );
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

	/**
	 * Element identifier
	 *
	 * @return string
	 */
	public function identifier() {
		return '.tve-toc-title';
	}

	/**
	 * Either to display or not the element in the sidebar menu
	 *
	 * @return bool
	 */
	public function hide() {
		return true;
	}

	/**
	 * @inheritDoc
	 */
	public function expanded_state_apply_inline() {
		return true;
	}

	/**
	 * For TOC expanded is collapsed because we can
	 *
	 * @inheritDoc
	 */
	public function expanded_state_label() {
		return __( 'Collapsed', 'thrive-cb' );
	}

	public function own_components() {
		$prefix_config = tcb_selection_root() . ' .tve-toc-title';

		$components = parent::own_components();


		$components['toc_title'] = array(
			'order'  => 0,
			'config' => array(
				'State'         => array(
					'config'  => array(
						'name'    => __( 'State', 'thrive-cb' ),
						'buttons' => array(
							array(
								'icon'    => '',
								'text'    => 'Expanded',
								'value'   => 'expanded',
								'default' => true,
							),
							array(
								'icon'  => '',
								'text'  => 'Collapsed',
								'value' => 'collapsed',

							),
						),
					),
					'extends' => 'ButtonGroup',
				),
				'ShowIcon'      => array(
					'config'  => array(
						'label' => __( 'Show Icon' ),
					),
					'extends' => 'Switch',
				),
				'IconColor'     => array(
					'css_suffix' => ' .tve-toc-title-icon',
					'config'     => array(
						'label' => __( 'Icon color', 'thrive-cb' ),
					),
					'important'  => true,
					'extends'    => 'ColorPicker',
				),
				'IconPlacement' => array(
					'config'  => array(
						'name'    => __( 'Placement', 'thrive-cb' ),
						'buttons' => array(
							array(
								'icon'    => '',
								'text'    => 'Left',
								'value'   => 'left',
								'default' => true,
							),
							array(
								'icon'  => '',
								'text'  => 'Right',
								'value' => 'right',
							),
						),
					),
					'extends' => 'ButtonGroup',
				),
				'IconSize'      => array(
					'config'     => array(
						'default' => '15',
						'min'     => '0',
						'max'     => '100',
						'label'   => __( 'Icon Size', 'thrive-cb' ),
						'um'      => array( 'px', '%' ),
						'css'     => 'font-size',

					),
					'css_suffix' => ' .tve-toc-title-icon',
					'important'  => true,
					'extends'    => 'Slider',
				),
				'RotateIcon'    => array(
					'config'  => array(
						'step'    => '45',
						'label'   => __( 'Rotate Icon', 'thrive-cb' ),
						'default' => '0',
						'min'     => '-180',
						'max'     => '180',
						'um'      => array( ' Deg' ),
					),
					'extends' => 'Slider',
				),
			),
		);

		unset( $components['contentbox'] );
		unset( $components['shared-styles'] );
		$components['layout'] = array(
			'disabled_controls' => array(
				'Display',
				'Float',
				'Position',
			),
			'config'            => array(
				'Width'  => array(
					'important' => true,
				),
				'Height' => array(
					'important' => true,
				),
			),
		);

		$components['background'] = array(
			'config' => array(
				'ColorPicker' => array( 'css_prefix' => $prefix_config ),
				'PreviewList' => array( 'css_prefix' => $prefix_config ),
				'css_suffix'  => ' > .tve-content-box-background',
			),
		);

		$components['borders']                         = array(
			'config' => array(
				'Borders' => array(
					'important' => true,
				),
				'Corners' => array(
					'important' => true,
				),
				'css_suffix'  => ' > .tve-content-box-background',
			),
		);
		$components['typography']['config']            = array(
			'FontSize'       => array( 'css_prefix' => $prefix_config ),
			'FontColor'      => array( 'css_prefix' => $prefix_config ),
			'LineHeight'     => array( 'css_prefix' => $prefix_config ),
			'FontFace'       => array( 'css_prefix' => $prefix_config ),
			'ParagraphStyle' => array( 'hidden' => false ),
		);
		$components['typography']['disabled_controls'] = array(
			'.tve-advanced-controls',
			'p_spacing',
			'h1_spacing',
			'h2_spacing',
			'h3_spacing',

		);

		$components['scroll']     = array( 'hidden' => true );
		$components['responsive'] = array( 'hidden' => true );
		$components['animation']  = array( 'hidden' => true );

		return $components;
	}
}
