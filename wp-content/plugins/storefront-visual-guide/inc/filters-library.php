<?php
/**
 * Storefront Visual Hook Guide Filters Library
 *
 * @package storefront-visual-guide
 * @since   2.0
 */

 if ( ! defined( 'ABSPATH' ) ) {
 	exit; // Exit if accessed directly.
 }

 /**
  * Storefront filter hooks array
  *
  * @since   2.0
  */

 $filter_hooks = array(
   array(
     'area'  => 'global',
     'hook'  => 'storefront_before_site',
     'title' => __('Global Filters', 'storefront-visual-guide'),
   ),
   array(
     'area'  => 'homepage',
     'hook'  => 'homepage',
     'title' => __('Homepage Filters', 'storefront-visual-guide'),
   ),
 );


/**
 * Storefront filters array
 *
 * @since   2.0
 */

$filters = array (
	'global' => array(
		  array(
				'filter'	    => 'storefront_make_me_cute',
				'input'       => '',
				'description' => __( 'Storefront Easter Egg.', 'storefront-visual-guide' ),
				'function'	  => esc_html( 'return true;' ),
			),
			array(
				'filter'	    => 'storefront_custom_background_args',
				'input'       => '',
				'description' => __( 'Setup custom background color & image arguments. Overrides the <code>storefront_default_background_color</code> filter.', 'storefront-visual-guide' ),
				'function'	  => esc_html( 'array(
						\'default-color\' => \'cccccc\',
						\'default-image\' => \'\')
						' ),
			),
			array(
				'filter'	    => 'storefront_default_background_color',
				'input'       => '',
				'description' => __( 'Edits the background color in HEX format.', 'storefront-visual-guide' ),
				'function'	  => esc_html( 'return \'cccccc\';' ),
			),
			array(
				'filter'	    => 'storefront_google_font_families',
				'input'       => '',
				'description' => __( 'Filters the default Google fonts. You need to change your CSS font-families.', 'storefront-visual-guide' ),
				'function'	  => esc_html( 'array(
						\'source-sans-pro\' => \'Source+Sans+Pro:400,300,300italic,400italic,600,700,900\');
						' ),
			),
			array(
				'filter'	    => 'storefront_menu_toggle_text',
				'input'       => '',
				'description' => __( 'Filters the text in the responsive menu toggle.', 'storefront-visual-guide' ),
				'function'	  => esc_html( ' \'Navigation\' ' ),
			),
			array(
				'filter'	    => 'storefront_navigation_markup_template',
				'input'       => '',
				'description' => __( 'Filters the HTML markup of the navigation menu.', 'storefront-visual-guide' ),
				'function'	  => esc_html( '' ),
			),
			array(
				'filter'	    => 'storefront_header_styles',
				'input'       => '',
				'description' => __( 'Filters the header styles with an array of all the inline css properties you want to change.', 'storefront-visual-guide' ),
				'function'	  => esc_html( '' ),
			),
			array(
				'filter'	    => 'storefront_custom_logo_args',
				'input'       => '',
				'description' => __( 'Enables support for site logo, defining width, height, flex dimensions and header text.', 'storefront-visual-guide' ),
				'function'	  => esc_html( '' ),
			),
			array(
				'filter'	    => 'storefront_register_nav_menus',
				'input'       => '',
				'description' => __( 'Changes the default registered navigation menus.', 'storefront-visual-guide' ),
				'function'	  => esc_html( 'array(
						\'primary\'   => \'Primary Menu\',
						\'secondary\' => \'Secondary Menu\',
						\'handheld\'  => \'Handheld Menu\'	)
				' ),
			),
			array(
				'filter'	    => 'storefront_html5_args',
				'input'       => '',
				'description' => __( 'Switch default core markup to output valid HTML5.', 'storefront-visual-guide' ),
				'function'	  => esc_html( 'array(
						\'comment-form\',
						\'comment-list\',
						\'gallery\',
						\'widgets\',
						\'caption\' )
				' ),
			),
	),
	'homepage' => array(
			array(
				'filter'	    => 'storefront_homepage_content_styles',
				'input'       => '',
				'description' => __( 'Filters the Homepage styles with an array of all the inline css properties you want to change.', 'storefront-visual-guide' ),
				'function'	  => esc_html( '' ),
			),
			array(
				'filter'	    => 'storefront_product_categories_args',
				'input'       => '',
				'description' => __( 'Filters the homepage product category arguments to define the number of categories to display, categories per column, sort by, title & parent status.', 'storefront-visual-guide' ),
				'function'	  => esc_html( '' ),
			),
			array(
				'filter'	    => 'storefront_product_categories_shortcode_args',
				'input'       => '',
				'description' => __( 'Filters the homepage product category display as <code>storefront_product_categories_args</code> but using shortcode arguments.', 'storefront-visual-guide' ),
				'function'	  => esc_html( '' ),
			),
			array(
				'filter'	    => 'storefront_recent_products_args',
				'input'       => '',
				'description' => __( 'Filters the recent products section arguments to define the number of categories to display, categories per column, sort by & title.', 'storefront-visual-guide' ),
				'function'	  => esc_html( '' ),
			),
			array(
				'filter'	    => 'storefront_recent_products_shortcode_args',
				'input'       => '',
				'description' => __( 'Filters the recent products section display as <code>storefront_recent_products_args</code> but using shortcode arguments.', 'storefront-visual-guide' ),
				'function'	  => esc_html( '' ),
			),
			array(
				'filter'	    => 'storefront_featured_products_args',
				'input'       => '',
				'description' => __( 'Filters the featured products section arguments to define the number of categories to display, categories per column, sort by, visibility & title.', 'storefront-visual-guide' ),
				'function'	  => esc_html( '' ),
			),
			array(
				'filter'	    => 'storefront_featured_products_shortcode_args',
				'input'       => '',
				'description' => __( 'Filters the featured products section display as <code>storefront_featured_products_args</code> but using shortcode arguments.', 'storefront-visual-guide' ),
				'function'	  => esc_html( '' ),
			),
			array(
				'filter'	    => 'storefront_popular_products_args',
				'input'       => '',
				'description' => __( 'Filters the popular products section arguments to define the number of categories to display, categories per column, sort by & title.', 'storefront-visual-guide' ),
				'function'	  => esc_html( '' ),
			),
			array(
				'filter'	    => 'storefront_popular_products_shortcode_args',
				'input'       => '',
				'description' => __( 'Filters the popular products section display as <code>storefront_popular_products_args</code> but using shortcode arguments.', 'storefront-visual-guide' ),
				'function'	  => esc_html( '' ),
			),
			array(
				'filter'	    => 'storefront_on_sale_products_args',
				'input'       => '',
				'description' => __( 'Filters the On-Sale products section arguments to define the number of categories to display, categories per column, sort by & title.', 'storefront-visual-guide' ),
				'function'	  => esc_html( '' ),
			),
			array(
				'filter'	    => 'storefront_on_sale_products_shortcode_args',
				'input'       => '',
				'description' => __( 'Filters the On-Sale products section display as <code>storefront_on_sale_products_args</code> but using shortcode arguments.', 'storefront-visual-guide' ),
				'function'	  => esc_html( '' ),
			),
			array(
				'filter'	    => 'storefront_best_selling_products_args',
				'input'       => '',
				'description' => __( 'Filters the best selling products section arguments to define the number of categories to display, categories per column, sort by & title.', 'storefront-visual-guide' ),
				'function'	  => esc_html( '' ),
			),
			array(
				'filter'	    => 'storefront_best_selling_products_shortcode_args',
				'input'       => '',
				'description' => __( 'Filters the best selling products section display as <code>storefront_best_selling_products_args</code> but using shortcode arguments.', 'storefront-visual-guide' ),
				'function'	  => esc_html( '' ),
			),
	),
	'single' => array(
		array(
			'filter'	    => 'storefront_single_post_posted_on_html',
			'input'       => '',
			'description' => __( 'Filters the published date markup on single posts', 'storefront-visual-guide' ),
			'function'	  => esc_html( '' ),
		),
		array(
			'filter'	    => 'storefront_comment_form_args',
			'input'       => '',
			'description' => __( 'Changes the comments reply span wrapper', 'storefront-visual-guide' ),
			'function'	  => esc_html( '' ),
		),
	),
	'widgets' => array(
			array(
				'filter'	    => 'storefront_footer_widget_rows',
				'input'       => '',
				'description' => __( 'Filters the number of footer widget rows.', 'storefront-visual-guide' ),
				'function'	  => esc_html( '' ),
			),
			array(
				'filter'	    => 'storefront_footer_widget_columns',
				'input'       => '',
				'description' => __( 'Filters the number of footer widget columns.', 'storefront-visual-guide' ),
				'function'	  => esc_html( '' ),
			),
			array(
				'filter'	    => 'storefront_sidebar_args',
				'input'       => '',
				'description' => __( 'Changes of default widget areas arguments (name, ID and description) for the sidebar, header and footer widget areas.', 'storefront-visual-guide' ),
				'function'	  => esc_html( '' ),
			),
	),
	'footer' => array(
			array(
				'filter'	    => 'storefront_copyright_text',
				'input'       => '',
				'description' => __( 'Modifies the Storefront footer copyright text.', 'storefront-visual-guide' ),
				'function'	  => esc_html( '' ),
			),
			array(
				'filter'	    => 'storefront_credit_link',
				'input'       => '',
				'description' => __( 'Show or hide the Storefront credit link.', 'storefront-visual-guide' ),
				'function'	  => esc_html( '' ),
			),
			array(
				'filter'	    => 'storefront_handheld_footer_bar_links',
				'input'       => '',
				'description' => __( 'Filters the handheld menu bar links.', 'storefront-visual-guide' ),
				'function'	  => esc_html( '' ),
			),
	),
	'woocommerce' => array(
			array(
				'filter'	    => 'storefront_woocommerce_args',
				'input'       => '',
				'description' => __( 'Declare WooCommerce support and the default parameters.', 'storefront-visual-guide' ),
				'function'	  => esc_html( '' ),
			),
			array(
				'filter'	    => 'storefront_customizer_woocommerce_extension_css',
				'input'       => '',
				'description' => __( 'Get extension css styles for Booking, Product Reviews & Smart Coupon addons.', 'storefront-visual-guide' ),
				'function'	  => esc_html( '' ),
			),
			array(
				'filter'	    => 'storefront_loop_columns',
				'input'       => '',
				'description' => __( 'Filters the number of products per row', 'storefront-visual-guide' ),
				'function'	  => esc_html( '' ),
			),
			array(
				'filter'	    => 'storefront_page_option_names',
				'input'       => '',
				'description' => __( 'Get WooCommerce page by ID.', 'storefront-visual-guide' ),
				'function'	  => esc_html( '' ),
			),
			array(
				'filter'	    => 'storefront_product_thumbnail_columns',
				'input'       => '',
				'description' => __( 'Filters the product gallery thumbnail columns number.', 'storefront-visual-guide' ),
				'function'	  => esc_html( '' ),
			),
			array(
				'filter'	    => 'storefront_products_per_page',
				'input'       => '',
				'description' => __( 'Filters the number of products per page in shop pages.', 'storefront-visual-guide' ),
				'function'	  => esc_html( '' ),
			),
			array(
				'filter'	    => 'storefront_related_products_args',
				'input'       => '',
				'description' => __( 'Filters the related products number and colums arguments.', 'storefront-visual-guide' ),
				'function'	  => esc_html( '' ),
			),
			array(
				'filter'	    => 'storefront_sticky_order_review',
				'input'       => '',
				'description' => __( 'Enqueue the Sticky Payment script in WooCommerce Checkout Add-Ons plugin.', 'storefront-visual-guide' ),
				'function'	  => esc_html( '' ),
			),
			array(
				'filter'	    => 'storefront_upsells_columns',
				'input'       => '',
				'description' => __( 'Filters the number of columns of upsell products.', 'storefront-visual-guide' ),
				'function'	  => esc_html( '' ),
			),
			array(
				'filter'	    => 'storefront_single_product_pagination_same_category',
				'input'       => '',
				'description' => __( 'Changes the option to only show products of the same category.', 'storefront-visual-guide' ),
				'function'	  => esc_html( '' ),
			),
			array(
				'filter'	    => 'storefront_sticky_add_to_cart_params',
				'input'       => '',
				'description' => __( 'Filters the sticky Add to Cart parameters.', 'storefront-visual-guide' ),
				'function'	  => esc_html( '' ),
			),
	),
	'customizer' => array(
			array(
				'filter'	    => '',
				'input'       => '',
				'description' => __( '', 'storefront-visual-guide' ),
				'function'	  => esc_html( '' ),
			),
			array(
				'filter'	    => '',
				'input'       => '',
				'description' => __( '', 'storefront-visual-guide' ),
				'function'	  => esc_html( '' ),
			),
			array(
				'filter'	    => '',
				'input'       => '',
				'description' => __( '', 'storefront-visual-guide' ),
				'function'	  => esc_html( '' ),
			),
			array(
				'filter'	    => '',
				'input'       => '',
				'description' => __( '', 'storefront-visual-guide' ),
				'function'	  => esc_html( '' ),
			),
	),
	'starter-content' => array(
		'filter'	    => 'storefront_starter_content',
		'input'       => '',
		'description' => __( '', 'storefront-visual-guide' ),
		'function'	  => esc_html( '' ),
	),
	'woo-starter-content' => array(
		'filter'	    => 'storefront_starter_content_products',
		'input'       => '',
		'description' => __( '', 'storefront-visual-guide' ),
		'function'	  => esc_html( '' ),
	),
);
