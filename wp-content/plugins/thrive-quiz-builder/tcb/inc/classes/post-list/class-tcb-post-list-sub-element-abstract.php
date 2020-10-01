<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

/**
 * Class TCB_Post_List_Sub_Element_Abstract
 */
abstract class TCB_Post_List_Sub_Element_Abstract extends TCB_Element_Abstract {

	/**
	 * Thrive_Theme_Element_Abstract constructor.
	 *
	 * @param string $tag
	 */
	public function __construct( $tag = '' ) {
		parent::__construct( $tag );

		add_filter( 'tcb_element_' . $this->tag() . '_config', array( $this, 'add_config' ) );
	}

	public function add_config( $config ) {
		$config['shortcode'] = $this->shortcode();

		return $config;
	}

	/**
	 * If an element has a shortcode tag (empty by default, override by children who have shortcode tags).
	 *
	 * @return bool
	 */
	public function shortcode() {
		return '';
	}

	/**
	 * Element category that will be displayed in the sidebar
	 *
	 * @return string
	 */
	public function category() {
		return TCB_Post_List::elements_group_label();
	}

	/**
	 * Default components that most post list sub-elements use
	 *
	 * @return array
	 */
	public function own_components() {
		$prefix = tcb_selection_root() . ' ';

		return array(
			'styles-templates' => array( 'hidden' => true ),
			'animation'        => array( 'disabled_controls' => array( '.btn-inline.anim-link' ) ),
			'typography'       => array(
				'disabled_controls' => array(
					'.tve-advanced-controls',
					'p_spacing',
					'h1_spacing',
					'h2_spacing',
					'h3_spacing',
				),
				'config'            => array(
					'css_suffix'    => '',
					'css_prefix'    => '',
					'TextShadow'    => array(
						'css_suffix' => '',
						'css_prefix' => $prefix,
					),
					'FontColor'     => array(
						'css_suffix' => '',
						'css_prefix' => $prefix,
					),
					'FontSize'      => array(
						'css_suffix' => '',
						'css_prefix' => $prefix,
					),
					'TextStyle'     => array(
						'css_suffix' => '',
						'css_prefix' => $prefix,
					),
					'LineHeight'    => array(
						'css_suffix' => '',
						'css_prefix' => $prefix,
					),
					'FontFace'      => array(
						'css_suffix' => '',
						'css_prefix' => $prefix,
					),
					'LetterSpacing' => array(
						'css_suffix' => '',
						'css_prefix' => $prefix,
					),
					'TextAlign'     => array(
						'css_suffix' => '',
						'css_prefix' => $prefix,
					),
					'TextTransform' => array(
						'css_suffix' => '',
						'css_prefix' => $prefix,
					),
				),
			),
		);
	}
}
