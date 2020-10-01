<?php
/**
 * FileName  class-tcb-footer-element.php.
 *
 * @project  : thrive-visual-editor
 * @company  : BitStone
 */

/**
 * Class TCB_Footer_Element
 */
class TCB_Header_Element extends TCB_Symbol_Element_Abstract {

	/**
	 * TCB_Header_Element constructor.
	 *
	 * @param string $tag element tag.
	 */
	public function __construct( $tag = '' ) {
		parent::__construct( $tag );
	}

	/**
	 * Name of the element
	 *
	 * @return string
	 */
	public function name() {
		return __( 'Header', 'thrive-cb' );
	}

	/**
	 * Return icon class needed for display in menu
	 *
	 * @return string
	 */
	public function icon() {
		return 'post_grid';
	}

	/**
	 * Element identifier
	 *
	 * @return string
	 */
	public function identifier() {
		return '.thrv_symbol.thrv_header';
	}

	/**
	 * Whether or not this element is only a placeholder ( it has no menu, it's not selectable etc )
	 * e.g. Content Templates
	 *
	 * @return bool
	 */
	public function is_placeholder() {
		return false;
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
	 * Component and control config
	 *
	 * @return array
	 */
	public function own_components() {

		$background_selector = '.symbol-section-out';
		$content_selector    = '.symbol-section-in';

		$components = array(
			'header'     => array(
				'config' => array(
					'Visibility'         => array(
						'config'  => array(
							'name'    => '',
							'label'   => __( 'Visibility', 'thrive-cb' ),
							'default' => true,
						),
						'extends' => 'Switch',
					),
					'InheritContentSize' => array(
						'config'  => array(
							'name'    => '',
							'label'   => __( 'Inherit content size from layout', 'thrive-cb' ),
							'default' => true,
						),
						'extends' => 'Switch',
					),
					'StretchBackground'  => array(
						'config'  => array(
							'name'    => '',
							'label'   => __( 'Stretch background to full width', 'thrive-cb' ),
							'default' => true,
						),
						'extends' => 'Switch',
					),
					'ContentWidth'       => array(
						'config'     => array(
							'default' => '1080',
							'min'     => '1',
							'max'     => '1980',
							'label'   => __( 'Content Width', 'thrive-cb' ),
							'um'      => array( 'px' ),
							'css'     => 'max-width',
						),
						'extends'    => 'Slider',
						'css_suffix' => $content_selector,
					),
					'StretchContent'     => array(
						'config'     => array(
							'name'    => '',
							'label'   => __( 'Stretch content to full width', 'thrive-cb' ),
							'default' => true,
						),
						'extends'    => 'Switch',
						'css_suffix' => ' .symbol-section-in',
					),
					'HeaderPosition'     => array(
						'config'  => array(
							'name'       => 'Header Position',
							'full-width' => true,
							'buttons'    => array(
								array( 'value' => 'push', 'text' => __( 'Push Content' ), 'default' => true ),
								array( 'value' => 'over', 'text' => __( 'Over Content' ) ),
							),
						),
						'extends' => 'ButtonGroup',
					),
					'Height'             => array(
						'config'  => array(
							'default' => '80',
							'min'     => '1',
							'max'     => '1000',
							'label'   => __( 'Content Minimum Height', 'thrive-cb' ),
							'um'      => array( 'px', 'vh' ),
							'css'     => 'min-height',
						),
						'to'      => $content_selector,
						'extends' => 'Slider',
					),
					'FullHeight'         => array(
						'config'  => array(
							'name'    => '',
							'label'   => __( 'Match height to screen', 'thrive-cb' ),
							'default' => true,
						),
						'to'      => $content_selector,
						'extends' => 'Switch',
					),
					'VerticalPosition'   => array(
						'config'  => array(
							'name'    => __( 'Vertical Position', 'thrive-cb' ),
							'buttons' => array(
								array(
									'icon'    => 'top',
									'default' => true,
									'value'   => '',
								),
								array(
									'icon'  => 'vertical',
									'value' => 'center',
								),
								array(
									'icon'  => 'bot',
									'value' => 'flex-end',
								),
							),
						),
						'to'      => $content_selector,
						'extends' => 'ButtonGroup',
					),
				),
			),
			'background' => array(
				'config'            => array(
					'to' => $background_selector,
				),
				'disabled_controls' => array(),
			),
			'shadow'     => array(
				'config' => array(
					'to' => $background_selector,
				),
			),
			'layout'     => array(
				'disabled_controls' => array( '.tve-advanced-controls', 'Float', 'hr', 'Position', 'PositionFrom', 'zIndex', 'Width', 'Height', 'Alignment', 'Display' ),
			),
			'borders'    => array(
				'config' => array(
					'Borders'    => array(),
					'Corners'    => array(),
					'css_suffix' => ' .thrive-symbol-shortcode',
				),
			),
			'typography' => array(
				'disabled_controls' => array(),
				'config'            => array(
					'to' => $content_selector,
				),
			),
			'decoration' => array(
				'config' => array(
					'to' => $background_selector,
				),
			),
			'animation'  => array( 'hidden' => true ),
			'scroll'     => array(
				'order'             => 2,
				'config'            => array(
					'to' => '.thrive-symbol-shortcode',
				),
				'disabled_controls' => array( '[data-value="parallax"]' ),
				'hidden'            => false,
			),
		);

		$components['layout']['config']['MarginAndPadding']['padding_to'] = $content_selector;

		return $components;
	}

	/**
	 * Update meta for scroll on behaviour
	 *
	 * @param $meta
	 *
	 * @return bool
	 */
	public function update_meta( $meta ) {
		$header_id = $meta['header_id'];

		update_post_meta( $header_id, $meta['meta_key'], $meta['meta_value'] );

		return true;
	}
}
