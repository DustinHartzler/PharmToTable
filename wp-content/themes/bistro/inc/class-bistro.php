<?php
/**
 * Bistro Class
 *
 * @author   WooThemes
 * @package  Bistro
 * @since    1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Bistro' ) ) {
	/**
	 * The main Bistro class
	 */
	class Bistro {
		/**
		 * Setup class.
		 *
		 * @since 1.0
		 */
		public function __construct() {
			add_action( 'wp_enqueue_scripts',              array( $this, 'enqueue_styles' ) );
			add_filter( 'storefront_google_font_families', array( $this, 'bistro_fonts' ) );
			add_filter( 'woocommerce_breadcrumb_defaults', array( $this,'change_breadcrumb_delimiter' ) );


			add_filter( 'storefront_product_categories_args',    array( $this, 'bistro_homepage_categories' ) );
			add_filter( 'storefront_recent_products_args',       array( $this, 'bistro_homepage_products' ) );
			add_filter( 'storefront_featured_products_args',     array( $this, 'bistro_homepage_products' ) );
			add_filter( 'storefront_popular_products_args',      array( $this, 'bistro_homepage_products' ) );
			add_filter( 'storefront_on_sale_products_args',      array( $this, 'bistro_homepage_products' ) );
			add_filter( 'storefront_best_selling_products_args', array( $this, 'bistro_homepage_products' ) );
		}

		/**
		 * Enqueue Storefront Styles
		 *
		 * @return void
		 */
		public function enqueue_styles() {
			global $storefront_version, $bistro_version;

			/**
			 * Styles
			 */
			wp_enqueue_style( 'storefront-style', get_template_directory_uri() . '/style.css', $storefront_version );
			wp_style_add_data( 'storefront-child-style', 'rtl', 'replace' );

			if ( is_page_template( 'template-homepage.php' ) ) {
				wp_enqueue_style( 'slick-style', get_stylesheet_directory_uri() . '/assets/css/slick.css' );
			}

			/**
			 * Javascript
			 */
			wp_enqueue_script( 'bistro', get_stylesheet_directory_uri() . '/assets/js/bistro.min.js', array( 'jquery' ), $bistro_version, true );

			if ( is_page_template( 'template-homepage.php' ) ) {
				wp_enqueue_script( 'slick', get_stylesheet_directory_uri() . '/assets/js/slick.min.js', array( 'jquery' ), true );
				wp_enqueue_script( 'slick-init', get_stylesheet_directory_uri() . '/assets/js/slick-init.min.js', array( 'jquery' ), true );
			}
		}

		/**
		 * Replaces Source Sans with the Bistro fonts
		 *
		 * @param  array $fonts the desired fonts.
		 * @return array
		 */
		public function bistro_fonts( $fonts ) {
			$fonts = array(
				'alegreya'      => 'Alegreya:400,400italic,700,900',
				'alegreya-sans' => 'Alegreya+Sans:400,400italic,700,900',
			);

			return $fonts;
		}

		/**
		 * Remove the breadcrumb delimiter
		 * @param  array $defaults thre breadcrumb defaults
		 * @return array           thre breadcrumb defaults
		 */
		public function change_breadcrumb_delimiter( $defaults ) {
			$defaults['delimiter'] = '<span class="breadcrumb-separator"> / </span>';
			return $defaults;
		}

		/**
		 * Specified how many categories to display on the homepage
		 *
		 * @param array $args The arguments used to control the layout of the homepage category section.
		 */
		public function bistro_homepage_categories( $args ) {
			$args['limit']   = 6;
			$args['columns'] = 3;

			return $args;
		}

		/**
		 * Specified how many categories to display on the homepage
		 *
		 * @param array $args The arguments used to control the layout of the homepage category section.
		 */
		public function bistro_homepage_products( $args ) {
			$args['limit']   = 9;
			$args['columns'] = 3;

			return $args;
		}
	}
}

return new Bistro();
