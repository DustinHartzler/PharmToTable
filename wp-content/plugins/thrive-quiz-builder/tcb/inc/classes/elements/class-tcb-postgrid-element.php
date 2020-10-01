<?php
/**
 * Created by PhpStorm.
 * User: Ovidiu
 * Date: 5/26/2017
 * Time: 4:37 PM
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

/**
 * Class TCB_Postgrid_Element
 */
class TCB_Postgrid_Element extends TCB_Element_Abstract {

	/**
	 * Name of the element
	 *
	 * @return string
	 */
	public function name() {
		return __( 'Post Grid', 'thrive-cb' );
	}

	/**
	 * We don't use this anymore
	 * @return bool
	 */
	public function hide() {
		return true;
	}

	/**
	 * Get element alternate
	 *
	 * @return string
	 */
	public function alternate() {
		return 'list';
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
		return '.thrv_post_grid ';
	}

	/**
	 * Post Grid extra sidebar state - used in MANAGE CELLS mode.
	 *
	 * @return null|string
	 */
	public function get_sidebar_extra_state() {
		return tcb_template( 'sidebars/post-grid-edit-grid-options', null, true );
	}

	/**
	 * Gets all the post types for post grid
	 *
	 * @return array
	 */
	private function get_all_post_types() {
		$types        = array();
		$banned_types = TCB_Utils::get_banned_post_types();

		foreach ( get_post_types( array(), 'objects' ) as $type ) {
			if ( ! in_array( $type->name, $banned_types ) ) {
				$types[] = array(
					'id'   => $type->name,
					'text' => $type->label,
				);
			}
		}

		return $types;
	}

	/**
	 * Construct number of posts data
	 *
	 * @return array
	 */
	private function get_number_of_posts() {
		$return = array(
			array(
				'value' => 0,
				'name'  => 'All',
			),
		);
		foreach ( range( 1, 19 ) as $number ) {
			$return[] = array(
				'value' => $number,
				'name'  => $number,
			);
		}

		return $return;
	}

	/**
	 * Constructs the categories list for "Category filter"
	 *
	 * @return array
	 */
	public static function get_categories( $term ) {
		$taxonomies = array( 'category' );

		if ( taxonomy_exists( 'apprentice' ) ) {
			$taxonomies[] = 'apprentice';
		}

		$terms = get_terms( $taxonomies, array( 'search' => $term ) );

		$categories = array();
		foreach ( $terms as $item ) {
			$categories[] = array(
				'id'   => $item->name,
				'text' => $item->name,
			);
		}

		return $categories;
	}

	/**
	 * Constructs the tags list for "Tags filter"
	 *
	 * @return array
	 */
	public static function get_tags( $term ) {
		$taxonomies = array(
			'post_tag',
		);

		if ( taxonomy_exists( 'apprentice' ) ) {
			$taxonomies[] = 'apprentice-tag';
		}

		$terms = get_terms( $taxonomies, array( 'search' => $term ) );

		$tags = array();
		foreach ( $terms as $item ) {
			$tags[] = array(
				'id'   => $item->name,
				'text' => $item->name,
			);
		}

		return $tags;
	}

	/**
	 * Constructs the taxonomies list for "Custom Taxonomies filter"
	 *
	 * @return array
	 */
	public static function get_custom_taxonomies( $term ) {
		$items      = get_taxonomies();
		$banned     = array( 'category', 'post_tag' );
		$taxonomies = array();

		foreach ( $items as $item ) {
			if ( in_array( $item, $banned ) ) {
				continue;
			}

			if ( strpos( $item, $term ) !== false ) {
				$taxonomies[] = array(
					'id'   => $item,
					'text' => $item,
				);
			}
		}

		return $taxonomies;
	}

	/**
	 * Constructs the author list for "Authors filter"
	 *
	 * @return array
	 */
	public static function get_authors( $term ) {
		$users   = get_users( array( 'search' => "*$term*" ) );
		$authors = array();
		foreach ( $users as $item ) {
			$authors[] = array(
				'id'   => $item->data->user_nicename,
				'text' => $item->data->user_nicename,
			);
		}

		return $authors;
	}

	/**
	 * Constructs the post lists for "Individual Post / Pages filter"
	 *
	 * @return array
	 */
	public static function get_posts_list( $term ) {
		$args    = array(
			'order_by'    => 'post_title',
			'post_type'   => array( 'page', 'post' ),
			'post_status' => array( 'publish' ),
			's'           => $term,
		);
		$results = new WP_Query( $args );

		$list = array();
		foreach ( $results->get_posts() as $post ) {
			$list[] = array(
				'id'   => $post->ID,
				'text' => $post->post_title,
			);
		}

		return $list;
	}


	/**
	 * Component and control config
	 *
	 * @return array
	 */
	public function own_components() {
		return array(
			'postgrid'        => array(
				'config' => array(
					'read_more'         => array(
						'config'  => array(
							'label' => __( 'Read More', 'thrive-cb' ),
						),
						'extends' => 'LabelInput',
					),
					'read_more_color'   => array(
						'config'  => array(
							'default'   => 'f00',
							'label'     => __( 'Text Color', 'thrive-cb' ),
							'important' => true,
							'options'   => array( 'allowEmpty' => true ),
						),
						'extends' => 'ColorPicker',
					),
					'img_height'        => array(
						'config'  => array(
							'default' => '100',
							'min'     => '10',
							'max'     => '999',
							'um'      => array( 'px' ),
						),
						'extends' => 'Slider',
					),
					'title_font_size'   => array(
						'css_suffix' => ' .tve-post-grid-title',
						'config'     => array(
							'default' => '16',
							'min'     => '10',
							'max'     => '100',
							'um'      => array( 'px' ),
						),
						'extends'    => 'Slider',
					),
					'title_line_height' => array(
						'css_suffix' => ' .tve-post-grid-title',
						'config'     => array(
							'default' => '16',
							'min'     => '10',
							'max'     => '100',
							'um'      => array( 'px' ),
						),
						'extends'    => 'Slider',
					),
					'tabs'              => array(
						'config' => array(
							'buttons' => array(
								array(
									'value' => 'img-height',
									'text'  => __( 'Image Height', 'thrive-cb' ),
								),
								array(
									'value' => 'title-font',
									'text'  => __( 'Title Font', 'thrive-cb' ),
								),
								array(
									'value' => 'line-height',
									'text'  => __( 'Line Height', 'thrive-cb' ),
								),
							),
						),
					),
				),
			),
			'postgrid-layout' => array(
				'config' => array(
					'number_of_columns' => array(
						'config'  => array(
							'name'    => __( 'Columns', 'thrive-cb' ),
							'default' => 3,
							'options' => range( 1, 6 ),
						),
						'extends' => 'Select',
					),
					'display'           => array(
						'config'  => array(
							'name'    => __( 'Display', 'thrive-cb' ),
							'options' => array(
								array(
									'value' => 'grid',
									'name'  => 'Grid',
								),
								array(
									'value' => 'masonry',
									'name'  => 'Masonry',
								),
							),
						),
						'extends' => 'Select',
					),
					'grid_layout'       => array(
						'config'  => array(
							'name'    => __( 'Grid Layout', 'thrive-cb' ),
							'options' => array(
								array(
									'value' => 'horizontal',
									'name'  => 'Horizontal',
								),
								array(
									'value' => 'vertical',
									'name'  => 'Vertical',
								),
							),
						),
						'extends' => 'Select',
					),
					'featured_image'    => array(
						'config'  => array(
							'name'    => '',
							'label'   => __( 'Featured image', 'thrive-cb' ),
							'default' => false,
						),
						'extends' => 'Checkbox',
					),
					'title'             => array(
						'config'  => array(
							'name'    => '',
							'label'   => __( 'Title', 'thrive-cb' ),
							'default' => false,
						),
						'extends' => 'Checkbox',
					),
					'read_more_lnk'     => array(
						'config'  => array(
							'name'    => '',
							'label'   => __( 'Read more link', 'thrive-cb' ),
							'default' => false,
						),
						'extends' => 'Checkbox',
					),
					'text'              => array(
						'config'  => array(
							'name'    => '',
							'label'   => __( 'Text', 'thrive-cb' ),
							'default' => false,
						),
						'extends' => 'Checkbox',
					),
					'text_type'         => array(
						'config'  => array(
							'name'    => __( 'Text type', 'thrive-cb' ),
							'options' => array(
								array(
									'value' => 'summary',
									'name'  => 'Summary',
								),
								array(
									'value' => 'excerpt',
									'name'  => 'Excerpt',
								),
								array(
									'value' => 'fulltext',
									'name'  => 'Full text',
								),
							),
						),
						'extends' => 'Select',
					),
					'preview'           => array(
						'config' => array(
							'sortable' => true,
							'labels'   => array(
								'featured_image' => __( 'Featured Image', 'thrive-cb' ),
								'title'          => __( 'Title', 'thrive-cb' ),
								'text'           => __( 'Text', 'thrive-cb' ),
								'read_more'      => __( 'Read More', 'thrive-cb' ),
							),
						),
					),
				),
			),
			'postgrid-query'  => array(
				'config' => array(
					'content'         => array(
						'config'  => array(
							'label'            => __( 'Content', 'thrive-cb' ),
							'tags'             => false,
							'data'             => $this->get_all_post_types(),
							'min_input_length' => 0,
							'remote'           => false,
							'no_results'       => __( 'No posts were found satisfying your Query', 'thrive-cb' ),
						),
						'extends' => 'SelectMultiple',
					),
					'order_by'        => array(
						'config'  => array(
							'name'    => __( 'Order By', 'thrive-cb' ),
							'options' => array(
								array(
									'value' => 'date',
									'name'  => 'Date',
								),
								array(
									'value' => 'title',
									'name'  => 'Title',
								),
								array(
									'value' => 'author',
									'name'  => 'Author',
								),
								array(
									'value' => 'comment_count',
									'name'  => 'Number of Comments',
								),
								array(
									'value' => 'rand',
									'name'  => 'Random',
								),
							),
						),
						'extends' => 'Select',
					),
					'order_mode'      => array(
						'config'  => array(
							'name'    => __( 'Order', 'thrive-cb' ),
							'options' => array(
								array(
									'value' => 'DESC',
									'name'  => 'Descending',
								),
								array(
									'value' => 'ASC',
									'name'  => 'Ascending',
								),
							),
						),
						'extends' => 'Select',
					),
					'number_of_posts' => array(
						'config'  => array(
							'name'    => __( 'Number of posts', 'thrive-cb' ),
							'options' => $this->get_number_of_posts(),
						),
						'extends' => 'Select',
					),
					'recent_days'     => array(
						'config'  => array(
							'inline'    => true,
							'name'      => __( 'Days', 'thrive-cb' ),
							'default'   => 0,
							'min'       => 0,
							'max'       => 999,
							'maxlength' => 3,
						),
						'extends' => 'Input',
					),
					'start'           => array(
						'config'  => array(
							'name'      => __( 'Start', 'thrive-cb' ),
							'default'   => 0,
							'min'       => 0,
							'max'       => 19,
							'maxlength' => 2,
						),
						'extends' => 'Input',
					),
				),
			),
			'postgrid-filter' => array(
				'config' => array(
					'categories'            => array(
						'config'  => array(
							'label'            => __( 'Categories', 'thrive-cb' ),
							'tags'             => false,
							'min_input_length' => 2,
							'remote'           => true,
							'custom_ajax'      => 'post_grid_categories',
							'no_results'       => __( 'No categories were found satisfying your Query', 'thrive-cb' ),
						),
						'extends' => 'SelectMultiple',
					),
					'tags'                  => array(
						'config'  => array(
							'label'            => __( 'Tags', 'thrive-cb' ),
							'tags'             => false,
							'custom_ajax'      => 'post_grid_tags',
							'remote'           => true,
							'min_input_length' => 2,
							'no_results'       => __( 'No tags were found satisfying your Query', 'thrive-cb' ),
						),
						'extends' => 'SelectMultiple',
					),
					'authors'               => array(
						'config'  => array(
							'label'            => __( 'Authors', 'thrive-cb' ),
							'tags'             => false,
							'custom_ajax'      => 'post_grid_users',
							'remote'           => true,
							'min_input_length' => 2,
							'no_results'       => __( 'No authors were found satisfying your Query', 'thrive-cb' ),
						),
						'extends' => 'SelectMultiple',
					),
					'custom_taxonomies'     => array(
						'config'  => array(
							'label'            => __( 'Custom Taxonomies', 'thrive-cb' ),
							'tags'             => false,
							'custom_ajax'      => 'post_grid_custom_taxonomies',
							'remote'           => true,
							'min_input_length' => 2,
							'no_results'       => __( 'No taxonomies were found satisfying your Query', 'thrive-cb' ),
						),
						'extends' => 'SelectMultiple',
					),
					'individual_post_pages' => array(
						'config'  => array(
							'label'            => __( 'Individual Posts / Pages', 'thrive-cb' ),
							'tags'             => false,
							'custom_ajax'      => 'post_grid_individual_post_pages',
							'remote'           => true,
							'min_input_length' => 2,
							'no_results'       => __( 'No post / pages were found satisfying your Query', 'thrive-cb' ),
						),
						'extends' => 'SelectMultiple',
					),
				),
			),
			'background'      => array(
				'config' => array(
					'css_suffix' => ' .tve_pg_container',
				),
			),
			'borders'         => array(
				'config' => array(
					'Borders'    => array(
						'important' => true,
					),
					'css_suffix' => ' .tve_pg_container',
				),
			),
			'typography'      => array(
				'config'            => array(
					'FontColor' => array(
						'css_suffix' => array( ' .tve-post-grid-text', ' .tve-post-grid-title' ),
					),
					'FontFace'  => array(
						'css_suffix' => array( ' .tve-post-grid-text', ' .tve-post-grid-title' ),
					),
				),
				'disabled_controls' => array(
					'TextStyle',
					'TextTransform',
					'.typography-button-toggle-controls', //Hides FontSize, LineHeight, LetterSpacing
					'.typography-button-toggle-hr',
					'.typography-text-transform-hr',
					'.tve-advanced-controls',
				),
			),
			'layout'          => array(
				'disabled_controls' => array(
					'Width',
					'Height',
					'.tve-advanced-controls',
					'Alignment',
				),
			),
		);
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
