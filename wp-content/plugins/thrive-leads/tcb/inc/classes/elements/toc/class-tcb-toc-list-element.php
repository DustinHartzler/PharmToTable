<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

/**
 * General element representing each of the individually stylable typography elements
 *
 * Class TCB_Toc_Heading_Element
 */
class TCB_Toc_List_Element extends TCB_ContentBox_Element {

	public function name() {
		return __( 'Heading List', 'thrive-cb' );
	}

	/**
	 * Element identifier
	 *
	 * @return string
	 */
	public function identifier() {
		return '.tve-toc-list';
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
		$prefix_config  = tcb_selection_root() . ' ';
		$typography_cfg = array( 'css_suffix' => '', 'css_prefix' => '' );
		$components     = parent::own_components();

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
				'to'          => '>.tve-content-box-background',
			),
		);

		$components['borders'] = array(
			'config' => array(
				'Borders' => array(
					'important' => true,
					'to'        => '>.tve-content-box-background',
				),
				'Corners' => array(
					'important' => true,
					'to'        => '>.tve-content-box-background',
				),
			),
		);

		$components['typography'] = array(
			'disabled_controls' => array(
				'p_spacing',
				'h1_spacing',
				'h2_spacing',
				'h3_spacing',
			),
			'config'            => array(
				'to'            => '.tve-toc-heading',
				'FontSize'      => $typography_cfg,
				'FontColor'     => $typography_cfg,
				'TextAlign'     => $typography_cfg,
				'TextStyle'     => $typography_cfg,
				'TextTransform' => $typography_cfg,
				'FontFace'      => $typography_cfg,
				'LineHeight'    => $typography_cfg,
				'LetterSpacing' => $typography_cfg,
			),
		);
		$components['scroll']     = array( 'hidden' => true );
		$components['responsive'] = array( 'hidden' => true );
		$components['animation']  = array( 'hidden' => true );

		return $components;
	}
}
