<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

/**
 * Class TCB_Post_Content_Element
 */
class TCB_Post_Content_Element extends TCB_Post_List_Sub_Element_Abstract {
	/**
	 * Name of the element
	 *
	 * @return string
	 */
	public function name() {
		return __( 'Post Content', 'thrive-cb' );
	}

	/**
	 * Return icon class needed for display in menu
	 *
	 * @return string
	 */
	public function icon() {
		return 'post-content';
	}

	/**
	 * Wordpress element identifier
	 *
	 * @return string
	 */
	public function identifier() {
		return '.tcb-post-content';
	}

	/**
	 * Return the shortcode tag of the element.
	 *
	 * @return string
	 */
	public function shortcode() {
		return 'tcb_post_content';
	}

	/**
	 * Element HTML
	 *
	 * @return string
	 */
	public function html() {
		$default_attr = array(
			'data-size'      => 'words',
			'data-read_more' => TCB_Post_List_Content::$default_read_more,
		);

		return TCB_Utils::wrap_content( '', 'section', '', 'tcb-post-content' . ' ' . THRIVE_WRAPPER_CLASS . ' ' . TCB_SHORTCODE_CLASS, $default_attr );
	}

	/**
	 * Add suffixes for post content elements
	 *
	 * @return array
	 */
	public function own_components() {
		$components = parent::own_components();

		$elements = array( ' p', ' a', ' ul', ' ul > li', ' ol', ' ol > li', ' h1', ' h2', ' h3', ' h4', ' h5', ' h6', ' blockquote > p', ' pre' );

		foreach ( $components['typography']['config'] as $control => $config ) {
			if ( is_array( $config ) ) {
				$components['typography']['config'][ $control ]['css_suffix'] = $elements;
			}
		}

		$components['typography']['config']['css_suffix'] = $elements;

		$components['post_content'] = array(
			'order'  => 1,
			'config' => array(
				'ContentSize'  => array(
					'config'  => array(
						'name'    => __( 'Content', 'thrive-cb' ),
						'buttons' => array(
							array(
								'icon'  => '',
								'text'  => 'Full',
								'value' => 'content',
							),
							array(
								'icon'  => '',
								'text'  => 'Excerpt',
								'value' => 'excerpt',
							),
							array(
								'icon'    => '',
								'text'    => 'Words',
								'value'   => 'words',
								'default' => true,
							),
						),
					),
					'extends' => 'ButtonGroup',
				),
				'WordsTrim'    => array(
					'config'  => array(
						'name'      => __( 'Word Count', 'thrive-cb' ),
						'default'   => 12,
						'maxlength' => 2,
						'min'       => 1,
					),
					'extends' => 'Input',
				),
				'ReadMoreText' => array(
					'config'  => array(
						'label'       => __( 'Read More Text', 'thrive-cb' ),
						'default'     => '...',
					),
					'extends' => 'LabelInput',
				),
			),
		);

		return $components;
	}
}
