<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

/**
 * Class TCB_Post_List_Element
 */
class TCB_Post_List_Element extends TCB_Cloud_Template_Element_Abstract {

	/**
	 * Name of the element
	 *
	 * @return string
	 */
	public function name() {
		return __( 'Post List', 'thrive-cb' );
	}

	/**
	 * Return icon class needed for display in menu
	 *
	 * @return string
	 */
	public function icon() {
		return 'post-list';
	}

	/**
	 * This element is not a placeholder
	 *
	 * @return bool|true
	 */
	public function is_placeholder() {
		return false;
	}

	/**
	 * WordPress element identifier
	 *
	 * @return string
	 */
	public function identifier() {
		return '.' . TCB_POST_LIST_CLASS;
	}

	/**
	 * Hide this element in the places where it doesn't make sense, but show it on posts, pages, custom post types, etc.
	 *
	 * @return bool
	 */
	public function hide() {
		$blacklisted_post_types = TCB_Utils::get_banned_post_types();

		$hide = in_array( get_post_type( get_the_ID() ), $blacklisted_post_types );

		return apply_filters( 'tcb_hide_post_list_element', $hide );
	}

	/**
	 * Override the parent implementation of this method in order to add more classes.
	 *
	 * Returns the HTML placeholder for an element (contains a wrapper, and a button with icon + element name)
	 *
	 * @param string $title Optional. Defaults to the name of the current element
	 *
	 * @return string
	 */
	public function html_placeholder( $title = null ) {
		if ( empty( $title ) ) {
			$title = $this->name();
		}
		$post_list_args = TCB_Post_List::default_args();

		$attr = array(
			'query'         => $post_list_args['query'],
			'ct'            => $this->tag() . '-0',
			'tcb-elem-type' => $this->tag(),
			'element-name'  => esc_attr( $this->name() ),
		);

		$extra_attr = '';

		foreach ( $attr as $key => $value ) {
			$extra_attr .= 'data-' . $key . '="' . $value . '" ';
		}

		return tcb_template( 'elements/element-placeholder', array(
			'icon'       => $this->icon(),
			'class'      => 'tcb-ct-placeholder tcb-compact-element',
			'title'      => $title,
			'extra_attr' => $extra_attr,
		), true );
	}

	/**
	 * Component and control config
	 *
	 * @return array
	 */
	public function own_components() {
		$pagination_types = array();

		/* for each pagination instance, get the label and the type for the select control config */
		foreach ( TCB_Pagination::$all_types as $type ) {
			$instance = tcb_pagination( $type );

			$pagination_types[] = array(
				'name'  => $instance->get_label(),
				'value' => $instance->get_type(),
			);
		}

		$components = array(
			'animation'        => array( 'hidden' => true ),
			'styles-templates' => array( 'hidden' => true ),
			'typography'       => array( 'hidden' => true ),
			'layout'           => array(
				'disabled_controls' => array( 'MaxWidth', 'Float', 'hr', 'Position', 'PositionFrom', 'Display', 'Overflow' ),
			),
			'post_list'        => array(
				'order'  => 1,
				'config' => array(
					'Type'            => array(
						'config'  => array(
							'default' => 'grid',
							'name'    => __( 'Display Type', 'thrive-cb' ),
							'buttons' => array(
								array(
									'icon'    => '',
									'text'    => 'LIST',
									'value'   => 'list',
									'default' => true,
								),
								array(
									'icon'  => '',
									'text'  => 'GRID',
									'value' => 'grid',
								),
								array(
									'icon'  => '',
									'text'  => 'MASONRY',
									'value' => 'masonry',
								),
							),
						),
						'extends' => 'ButtonGroup',
					),
					'ColumnsNumber'   => array(
						'config'  => array(
							'default' => '3',
							'min'     => '1',
							'max'     => '10',
							'label'   => __( 'Columns Number', 'thrive-cb' ),
							'um'      => array( '' ),
						),
						'extends' => 'Slider',
					),
					'VerticalSpace'   => array(
						'config'  => array(
							'min'   => '0',
							'max'   => '240',
							'label' => __( 'Vertical Space', 'thrive-cb' ),
							'um'    => array( 'px' ),
						),
						'extends' => 'Slider',
					),
					'HorizontalSpace' => array(
						'config'  => array(
							'min'   => '0',
							'max'   => '240',
							'label' => __( 'Horizontal Space', 'thrive-cb' ),
							'um'    => array( 'px' ),
						),
						'extends' => 'Slider',
					),
					/* get the select control for the pagination type */
					'PaginationType'  => array(
						'config'  => array(
							'default' => TCB_Pagination::NONE,
							/* if this is the control from the post list, change the name a bit */
							'name'    => __( 'Pagination Type', 'thrive-cb' ),
							'options' => $pagination_types,
						),
						'extends' => 'Select',
					),
					'ContentSize'     => array(
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
					'WordsTrim'       => array(
						'config'  => array(
							'name'      => __( 'Word Count', 'thrive-cb' ),
							'default'   => 12,
							'maxlength' => 2,
							'min'       => 1,
						),
						'extends' => 'Input',
					),
					'ReadMoreText'    => array(
						'config'  => array(
							'label'       => __( 'Read More Text', 'thrive-cb' ),
							'default'     => '',
							'placeholder' => __( 'e.g. Continue reading', 'thrive-cb' ),
						),
						'extends' => 'LabelInput',
					),
					'Linker'          => array(
						'config'  => array(
							'name'  => '',
							'label' => __( 'Link entire item to content', 'thrive-cb' ),
						),
						'extends' => 'Switch',
					),
					'Featured'        => array(
						'config'  => array(
							'name'  => '',
							'label' => __( 'Show Featured Content' ),
							'info'  => true,
						),
						'extends' => 'Switch',
					),
					'NumberOfItems'   => array(
						'config'  => array(
							'name'      => __( 'Number of Items', 'thrive-cb' ),
							'default'   => get_option( 'posts_per_page' ),
							'maxlength' => 4,
							'min'       => 1,
						),
						'extends' => 'Input',
					),
				),
			),
		);

		return $components;
	}

	/**
	 * Element category that will be displayed in the sidebar
	 *
	 * @return string
	 */
	public function category() {
		return $this->get_thrive_advanced_label();
	}
}
