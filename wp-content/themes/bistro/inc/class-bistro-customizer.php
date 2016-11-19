<?php
/**
 * Bistro_Customizer Class
 * Makes adjustments to Storefront cores Customizer implementation.
 *
 * @author   WooThemes
 * @package  Bistro
 * @since    1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Bistro_Customizer' ) ) {
	/**
	 * The Bistro Customizer class
	 */
	class Bistro_Customizer {

		/**
		 * Setup class.
		 *
		 * @since 1.0
		 */
		public function __construct() {
			global $storefront_version;

			add_action( 'wp_enqueue_scripts',                array( $this, 'add_customizer_css' ), 999 );
			add_action( 'customize_register',                array( $this, 'edit_settings' ),      99 );
			add_filter( 'storefront_setting_default_values', array( $this, 'bistro_defaults' ) );
		}

		/**
		 * Returns an array of the desired default Storefront options
		 *
		 * @param array $args an array of default values.
		 * @return array
		 */
		public function bistro_defaults( $args ) {
			// Header.
			$args['storefront_header_background_color']     = '#FFFFFF';
			$args['storefront_header_link_color']           = '#411A17';
			$args['storefront_header_text_color']           = '#666666';

			// Footer
			$args['storefront_footer_background_color']     = '#EEEBE2';
			$args['storefront_footer_text_color']           = '#4B3918';
			$args['storefront_footer_heading_color']        = '#401A18';

			// Typography.
			$args['storefront_accent_color']                = '#EE6948';
			$args['storefront_text_color']                  = '#4B3918';
			$args['storefront_heading_color']               = '#401A18';

			// Buttons.
			$args['storefront_button_background_color']     = '#EE6948';

			$args['storefront_button_alt_background_color'] = '#EAC355';
			$args['storefront_button_alt_text_color']       = '#421A17';

			// General
			$args['background_color']                       = '#F9F4EE';

			return apply_filters( 'bistro_customizer_defaults', $args );
		}

		/**
		 * Modify the default controls
		 *
		 * @param array $wp_customize the Customizer object.
		 * @return void
		 */
		public function edit_settings( $wp_customize ) {
			$wp_customize->get_setting( 'storefront_header_text_color' )->transport = 'refresh';
		}

		/**
		 * Add CSS using settings obtained from the theme options.
		 *
		 * @return void
		 */
		public function add_customizer_css() {
			$bg_color          = get_theme_mod( 'background_color' );
			$content_bg_color  = storefront_get_content_background_color();
			$header_text_color = get_theme_mod( 'storefront_header_text_color' );
			$header_link_color = get_theme_mod( 'storefront_header_link_color' );
			$header_bg_color   = get_theme_mod( 'storefront_header_background_color' );
			$footer_bg_color   = get_theme_mod( 'storefront_footer_background_color' );
			$accent_color      = get_theme_mod( 'storefront_accent_color' );
			$button_bg         = get_theme_mod( 'storefront_button_background_color' );
			$button_text       = get_theme_mod( 'storefront_button_text_color' );
			$button_alt_bg     = get_theme_mod( 'storefront_button_alt_background_color' );
			$button_alt_text   = get_theme_mod( 'storefront_button_alt_text_color' );
			$headings_color    = get_theme_mod( 'storefront_heading_color' );
			$text_color        = get_theme_mod( 'storefront_text_color' );

			$style = '
				.main-navigation ul li.smm-active li ul.products li.product h3 {
					color: ' . $header_text_color . ';
				}

				ul.products li.product .price,
				.widget-area ul.menu li.current-menu-item > a {
					color: ' . storefront_adjust_color_brightness( $text_color, 30 ) . ';
				}

				.storefront-product-section:not(.storefront-product-categories) .slick-dots li button {
					background-color: ' . storefront_adjust_color_brightness( $bg_color, -20 ) . ';
				}

				.storefront-product-section:not(.storefront-product-categories) .slick-dots li button:hover,
				.storefront-product-section:not(.storefront-product-categories) .slick-dots li.slick-active button {
					background-color: ' . storefront_adjust_color_brightness( $bg_color, -40 ) . ';
				}

				ul.products li.product,
				.storefront-handheld-footer-bar {
					background-color: ' . storefront_adjust_color_brightness( $content_bg_color, 10 ) . ';
				}

				.input-text, input[type=text], input[type=email], input[type=url], input[type=password], input[type=search], textarea,
				.input-text:focus, input[type=text]:focus, input[type=email]:focus, input[type=url]:focus, input[type=password]:focus, input[type=search]:focus, textarea:focus {
					background-color: ' . storefront_adjust_color_brightness( $content_bg_color, -15 ) . ';
				}

				ul.products li.product .bistro-rating-cart-button .button {
					color: ' . $headings_color . ';
				}

				ul.products li.product .bistro-rating-cart-button .button:after,
				ul.products li.product .bistro-rating-cart-button .button:hover:before,
				.widget-area .widget a {
					color: ' . $accent_color . ';
				}

				.site-header {
					border-top: 2em solid ' . storefront_adjust_color_brightness( $bg_color, -10 ) . ';
				}

				.site-header:before,
				.site-header:after {
					background: -moz-linear-gradient(transparent 0%, transparent 0%), -moz-linear-gradient(135deg, ' . $header_bg_color . ' 33.33%, transparent 33.33%) 0 0%, transparent -moz-linear-gradient(45deg, ' . $header_bg_color . ' 33.33%, transparent 33.33%) 0 0%;
					background: -webkit-linear-gradient(transparent 0%, transparent 0%), -webkit-linear-gradient(135deg, ' . $header_bg_color . ' 33.33%, transparent 33.33%) 0 0%, transparent -webkit-linear-gradient(45deg, ' . $header_bg_color . ' 33.33%, transparent 33.33%) 0 0%;
					background-size: 0px 100%, 1.618em 1.618em, 1.618em 1.618em;
				}

				.site-footer:before {
					background: -moz-linear-gradient(transparent 0%, transparent 0%), -moz-linear-gradient(135deg, ' . $footer_bg_color . ' 33.33%, transparent 33.33%) 0 0%, transparent -moz-linear-gradient(45deg, ' . $footer_bg_color . ' 33.33%, transparent 33.33%) 0 0%;
					background: -webkit-linear-gradient(transparent 0%, transparent 0%), -webkit-linear-gradient(135deg, ' . $footer_bg_color . ' 33.33%, transparent 33.33%) 0 0%, transparent -webkit-linear-gradient(45deg, ' . $footer_bg_color . ' 33.33%, transparent 33.33%) 0 0%;
					background-size: 0px 100%, 1.618em 1.618em, 1.618em 1.618em;
				}

				.woocommerce-breadcrumb a,
				.storefront-product-section .slick-prev,
				.storefront-product-section .slick-next,
				.storefront-product-section .slick-prev.slick-disabled:hover,
				.storefront-product-section .slick-next.slick-disabled:hover,
				ul.products li.product .onsale {
					background-color: ' . $button_alt_bg . ';
					color: ' . $button_alt_text . ';
				}

				ul.products li.product .onsale:before {
					border-top-color: ' . $button_alt_bg . ';
					border-bottom-color: ' . $button_alt_bg . ';
				}

				.woocommerce-breadcrumb a + span + a,
				.storefront-product-section .slick-prev:hover,
				.storefront-product-section .slick-next:hover {
					background-color: ' . storefront_adjust_color_brightness( $button_alt_bg, 10 ) . ';
				}

				.woocommerce-breadcrumb a + span + a + span + a {
					background-color: ' . storefront_adjust_color_brightness( $button_alt_bg, 20 ) . ';
				}

				.woocommerce-breadcrumb a + span + a + span + a + span + a {
					background-color: ' . storefront_adjust_color_brightness( $button_alt_bg, 30 ) . ';
				}

				.woocommerce-breadcrumb a:after {
					border-left-color: ' . $button_alt_bg . ';
				}

				.woocommerce-breadcrumb a + span + a:after {
					border-left-color: ' . storefront_adjust_color_brightness( $button_alt_bg, 10 ) . ';
				}

				.woocommerce-breadcrumb a + span + a + span + a:after {
					border-left-color: ' . storefront_adjust_color_brightness( $button_alt_bg, 20 ) . ';
				}

				.woocommerce-breadcrumb a + span + a + span + a + span + a:after {
					border-left-color: ' . storefront_adjust_color_brightness( $button_alt_bg, 30 ) . ';
				}

				table th,
				#respond {
					background-color: ' . storefront_adjust_color_brightness( $content_bg_color, 7 ) . ';
				}

				table tbody td {
					background-color: ' . storefront_adjust_color_brightness( $content_bg_color, 2 ) . ';
				}

				table tbody tr:nth-child(2n) td,
				#reviews .commentlist li .comment_container,
				#comments .comment-list .comment-content .comment-text {
					background-color: ' . storefront_adjust_color_brightness( $content_bg_color, 4 ) . ';
				}

				@media (min-width: 768px) {
					.site-header .main-navigation ul.menu > li > a:before, .site-header .main-navigation ul.nav-menu > li > a:before {
						background-color: ' . $accent_color . ';
					}

					.woocommerce-cart .site-header .site-header-cart:before {
						color: ' . $header_link_color . ' !important;
					}

					.site-header .site-search .widget_product_search form label:hover:before,
					.site-header .site-search .widget_product_search form label:focus:before,
					.site-header .site-search .widget_product_search form label:active:before,
					.site-header .site-search.active .widget_product_search form label:before,
					.site-header .site-header-cart:hover:before,
					.site-header .site-header-cart.active:before {
						color: ' . $button_alt_bg . ';
					}

					.site-header .site-search .widget_product_search form label:before,
					.site-header .site-header-cart:before,
					.woocommerce-cart .site-header-cart:hover:before,
					.woocommerce-cart .site-header-cart.active:before {
						color: ' . $header_link_color . ';
					}

					.main-navigation ul li a:hover, .main-navigation ul li:hover > a, .main-navigation ul.menu li.current-menu-item > a {
						color: ' . storefront_adjust_color_brightness( $header_link_color, 40 ) . ';
					}

					.site-header .site-header-cart .count,
					.main-navigation ul.menu ul a:hover, .main-navigation ul.menu ul li:hover > a, .main-navigation ul.nav-menu ul a:hover, .main-navigation ul.nav-menu ul li:hover > a {
						background-color: ' . $button_bg . ';
					}

					.site-header .site-header-cart .count,
					.main-navigation ul.menu ul a:hover, .main-navigation ul.menu ul li:hover > a, .main-navigation ul.nav-menu ul a:hover, .main-navigation ul.nav-menu ul li:hover > a {
						color: ' . $button_text . ';
					}

					.site-header .site-header-cart .count {
						border-color: ' . $header_bg_color . ';
					}

					.site-header-cart .widget_shopping_cart,
					.site-header .site-search input[type=search],
					.main-navigation ul.menu ul.sub-menu,
					.main-navigation ul.nav-menu ul.children {
						background-color: ' . $header_bg_color . ';
					}

					.widget_shopping_cart a.button:not(.checkout) {
						background-color: ' . storefront_adjust_color_brightness( $bg_color, -10 ) . ';
						color: ' . $headings_color . ';
					}

					.widget_shopping_cart a.button.checkout {
						background-color: ' . $button_bg . ';
						color: ' . $button_text . ';
					}

					.widget_shopping_cart a.button:not(.checkout):hover {
						background-color: ' . storefront_adjust_color_brightness( $bg_color, -5 ) . ';
						color: ' . $headings_color . ';
					}

					.site-header .site-header-cart .widget_shopping_cart:before {
						border-bottom-color: ' . $header_bg_color . ';
					}

					.site-header-cart .widget_shopping_cart a,
					.storefront-product-categories mark  {
						color: ' . $accent_color . ';
					}

					.site-header .site-search input[type=search] {
						border-color: ' . $button_alt_bg . ';
					}
				}';

			wp_add_inline_style( 'storefront-child-style', $style );
		}
	}
}

return new Bistro_Customizer();
