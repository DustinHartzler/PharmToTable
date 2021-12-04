<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-visual-editor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

/**
 * Main class for handling the editor page related stuff
 *
 * Class TCB_Editor_Page
 */
class TCB_Font_Manager {
	/**
	 * Instance
	 *
	 * @var TCB_Font_Manager
	 */
	private static $instance;
	/**
	 * Singleton instance method
	 *
	 * @return TCB_Font_Manager
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Return all fonts needed for font manager
	 *
	 * @return array
	 */
	public function all_fonts() {
		$fonts = array(
			'google' => array(
				'label' => __( 'Google Fonts', 'thrive-cb' ),
				'fonts' => array(), // $this->google_fonts() - we'll get those from js
			),
			'safe'   => array(
				'label' => __( 'Web Safe Fonts', 'thrive-cb' ),
				'fonts' => self::safe_fonts(),
			),
			'custom' => array(
				'label' => __( 'Custom Fonts', 'thrive-cb' ),
				'fonts' => $this->custom_fonts(),
			),
		);

		/**
		 * Compatibility with the "Custom Fonts" plugin
		 */
		if ( class_exists( 'Bsf_Custom_Fonts_Taxonomy' ) ) {
			$bsf = array();
			foreach ( Bsf_Custom_Fonts_Taxonomy::get_fonts() as $font_face => $urls ) {
				$bsf [ $font_face ] = array(
					'family'   => $font_face,
					'variants' => array(),
					'subsets'  => '',
				);
			}

			if ( $bsf ) {
				$fonts['custom_fonts_plugin'] = array(
					'label' => __( 'Custom Fonts Plugin', 'thrive-cb' ),
					'fonts' => $bsf,
				);
			}
		}

		return $fonts;
	}

	/**
	 * Return array of custom fonts
	 *
	 * @return array
	 */
	public function custom_fonts() {
		$custom_fonts   = json_decode( get_option( 'thrive_font_manager_options' ), true );
		$imported_fonts = Tve_Dash_Font_Import_Manager::getImportedFonts();

		if ( ! is_array( $custom_fonts ) ) {
			$custom_fonts = array();
		}

		$imported_keys = array();
		foreach ( $imported_fonts as $imp_font ) {
			$imported_keys[] = $imp_font['family'];
		}

		$return = array();
		foreach ( $custom_fonts as $font ) {
			$return[ $font['font_name'] ] = array(
				'family'         => $font['font_name'],
				'regular_weight' => intval( $font['font_style'] ),
				'class'          => $font['font_class'],
			);
		}

		return $return;
	}

	/**
	 * Return safe fonts array
	 *
	 * @return array
	 */
	public static function safe_fonts() {
		return array(
			array(
				'family'   => 'Georgia, serif',
				'variants' => array( 'regular', 'italic', '600' ),
				'subsets'  => array( 'latin' ),
			),
			array(
				'family'   => 'Palatino Linotype, Book Antiqua, Palatino, serif',
				'variants' => array( 'regular', 'italic', '600' ),
				'subsets'  => array( 'latin' ),
			),
			array(
				'family'   => 'Times New Roman, Times, serif',
				'variants' => array( 'regular', 'italic', '600' ),
				'subsets'  => array( 'latin' ),
			),
			array(
				'family'   => 'Arial, Helvetica, sans-serif',
				'variants' => array( 'regular', 'italic', '600' ),
				'subsets'  => array( 'latin' ),
			),
			array(
				'family'   => 'Arial Black, Gadget, sans-serif',
				'variants' => array( 'regular', 'italic', '600' ),
				'subsets'  => array( 'latin' ),
			),
			array(
				'family'   => 'Comic Sans MS, cursive, sans-serif',
				'variants' => array( 'regular', 'italic', '600' ),
				'subsets'  => array( 'latin' ),
			),
			array(
				'family'   => 'Impact, Charcoal, sans-serif',
				'variants' => array( 'regular', 'italic', '600' ),
				'subsets'  => array( 'latin' ),
			),
			array(
				'family'   => 'Lucida Sans Unicode, Lucida Grande, sans-serif',
				'variants' => array( 'regular', 'italic', '600' ),
				'subsets'  => array( 'latin' ),
			),
			array(
				'family'   => 'Tahoma, Geneva, sans-serif',
				'variants' => array( 'regular', 'italic', '600' ),
				'subsets'  => array( 'latin' ),
			),
			array(
				'family'   => 'Trebuchet MS, Helvetica, sans-serif',
				'variants' => array( 'regular', 'italic', '600' ),
				'subsets'  => array( 'latin' ),
			),
			array(
				'family'   => 'Verdana, Geneva, sans-serif',
				'variants' => array( 'regular', 'italic', '600' ),
				'subsets'  => array( 'latin' ),
			),
			array(
				'family'   => 'Courier New, Courier, monospace',
				'variants' => array( 'regular', 'italic', '600' ),
				'subsets'  => array( 'latin' ),
			),
			array(
				'family'   => 'Lucida Console, Monaco, monospace',
				'variants' => array( 'regular', 'italic', '600' ),
				'subsets'  => array( 'latin' ),
			),
		);
	}
}
