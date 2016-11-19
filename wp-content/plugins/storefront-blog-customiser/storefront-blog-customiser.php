<?php
/**
 * Plugin Name: Storefront Blog Customiser
 * Plugin URI: http://woothemes.com/products/storefront-blog-customiser/
 * Description: Adds blog customisation settings to the Storefront theme
 * Version: 1.2.1
 * Author: WooThemes
 * Author URI: http://woothemes.com/
 * Requires at least: 4.1.0
 * Tested up to: 4.5.0
 *
 * Text Domain: storefront-blog-customiser
 * Domain Path: /languages/
 *
 * @package Storefront_Blog_Customiser
 * @category Core
 * @author James Koster
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Required functions
 */
if ( ! function_exists( 'woothemes_queue_update' ) ) {
	require_once( 'woo-includes/woo-functions.php' );
}

/**
 * Plugin updates
 */
woothemes_queue_update( plugin_basename( __FILE__ ), 'b6db5f01709cb92bf7e03bdb9f967eb1', '603523' );

/**
 * Returns the main instance of Storefront_Blog_Customiser to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return object Storefront_Blog_Customiser
 */
function storefront_blog_customiser() {
	return Storefront_Blog_Customiser::instance();
} // End Storefront_Blog_Customiser()

storefront_blog_customiser();

/**
 * Main Storefront_Blog_Customiser Class
 *
 * @class Storefront_Blog_Customiser
 * @version	1.0.0
 * @since 1.0.0
 * @package	Storefront_Blog_Customiser
 */
final class Storefront_Blog_Customiser {
	/**
	 * Storefront_Blog_Customiser The single instance of Storefront_Blog_Customiser.
	 *
	 * @var 	object
	 * @access  private
	 * @since 	1.0.0
	 */
	private static $_instance = null;

	/**
	 * The token.
	 *
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $token;

	/**
	 * The version number.
	 *
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $version;

	/**
	 * The admin object.
	 *
	 * @var     object
	 * @access  public
	 * @since   1.0.0
	 */
	public $admin;

	/**
	 * Constructor function.
	 *
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function __construct() {
		$this->token 			= 'storefront-blog-customiser';
		$this->plugin_url 		= plugin_dir_url( __FILE__ );
		$this->plugin_path 		= plugin_dir_path( __FILE__ );
		$this->version 			= '1.2.1';

		register_activation_hook( __FILE__, array( $this, 'install' ) );

		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		add_action( 'init', array( $this, 'sbc_setup' ) );
	} // End __construct()

	/**
	 * Main Storefront_Blog_Customiser Instance
	 *
	 * Ensures only one instance of Storefront_Blog_Customiser is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see Storefront_Blog_Customiser()
	 * @return Main Storefront_Blog_Customiser instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) )
			self::$_instance = new self();
		return self::$_instance;
	} // End instance()

	/**
	 * Load the localisation file.
	 *
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'storefront-blog-customiser', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	} // End load_plugin_textdomain()

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, esc_attr__( 'Cheatin&#8217; huh?' ), '1.0.0' );
	} // End __clone()

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, esc_attr__( 'Cheatin&#8217; huh?' ), '1.0.0' );
	} // End __wakeup()

	/**
	 * Installation. Runs on activation.
	 *
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function install() {
		$this->_log_version_number();

		// Get theme customizer url.
		$url = admin_url() . 'customize.php?';
		$url .= 'url=' . urlencode( site_url() . '?storefront-customizer=true' );
		$url .= '&return=' . urlencode( admin_url() . 'plugins.php' );
		$url .= '&storefront-customizer=true';

		$notices 		= get_option( 'sbc_activation_notice', array() );
		$notices[]		= sprintf( __( '%sThanks for installing the Storefront Blog Customiser extension. To get started, visit the %sCustomizer%s.%s %sOpen the Customizer%s', 'storefront-blog-customiser' ), '<p>', '<a href="' . $url . '">', '</a>', '</p>', '<p><a href="' . $url . '" class="button button-primary">', '</a></p>' );

		update_option( 'sbc_activation_notice', $notices );
	} // End install()

	/**
	 * Log the plugin version number.
	 *
	 * @access  private
	 * @since   1.0.0
	 * @return  void
	 */
	private function _log_version_number() {
		// Log the version number.
		update_option( $this->token . '-version', $this->version );
	} // End _log_version_number()

	/**
	 * Setup all the things, if Storefront or a child theme using Storefront that has not disabled the Customizer settings is active
	 *
	 * @return void
	 */
	public function sbc_setup() {
		$theme = wp_get_theme();

		if ( 'Storefront' == $theme->name || 'storefront' == $theme->template && apply_filters( 'storefront_blog_customiser_enabled', true ) ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'sbc_script' ) );
			add_action( 'customize_register', array( $this, 'sbc_customize_register' ) );
			add_filter( 'body_class', array( $this, 'sbc_body_class' ) );
			add_filter( 'post_class', array( $this, 'sbc_post_class' ) );
			add_action( 'homepage', array( $this, 'storefront_homepage_blog' ), 80 );

			add_action( 'wp', array( $this, 'sbc_layout' ), 999 );

			add_action( 'customize_preview_init', array( $this, 'sbc_customize_preview_js' ) );

			add_action( 'admin_notices', array( $this, 'customizer_notice' ) );

			// Hide the 'More' section in the customizer.
			add_filter( 'storefront_customizer_more', '__return_false' );
		}
	}

	/**
	 * Display a notice linking to the Customizer
	 *
	 * @since   1.0.0
	 * @return  void
	 */
	public function customizer_notice() {
		$notices = get_option( 'sbc_activation_notice' );

		if ( $notices = get_option( 'sbc_activation_notice' ) ) {

			foreach ( $notices as $notice ) {
				echo '<div class="updated">' . wp_kses_post( $notice ) . '</div>';
			}

			delete_option( 'sbc_activation_notice' );
		}
	}

	/**
	 * Enqueue CSS.
	 *
	 * @since   1.0.0
	 * @return  void
	 */
	public function sbc_script() {
		wp_enqueue_style( 'sbc-styles', plugins_url( '/assets/css/style.css', __FILE__ ) );
	}

	/**
	 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
	 *
	 * @since  1.0.0
	 */
	public function sbc_customize_preview_js() {
		wp_enqueue_script( 'sbc-customizer', plugins_url( '/assets/js/customizer.min.js', __FILE__ ), array( 'customize-preview' ), '1.0', true );
	}

	/**
	 * Customizer Controls and settings
	 *
	 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
	 */
	public function sbc_customize_register( $wp_customize ) {

		$theme	= wp_get_theme();

		/**
		 * Add the blog panel
		 */
		$wp_customize->add_panel( 'sbc_panel', array(
		    'priority'       	=> 60,
		    'capability'     	=> 'edit_theme_options',
		    'theme_supports' 	=> '',
		    'title'				=> __( 'Blog', 'storefront-blog-customiser' ),
		    'description'    	=> __( 'Customise the appearance of your blog posts and archives.', 'storefront-blog-customiser' ),
		) );

		/**
	     * Blog archives section
	     */
	    $wp_customize->add_section( 'storefront_blog_archive' , array(
		    'title'      	=> __( 'Archives', 'storefront' ),
		    'priority'   	=> 10,
		    'description' 	=> __( 'Customise the look & feel of the blog archives', 'storefront-blog-customiser' ),
		    'panel'			=> 'sbc_panel',
		) );

		/**
	     * Single blog post section
	     */
	    $wp_customize->add_section( 'storefront_blog_single' , array(
		    'title'      	=> __( 'Single posts', 'storefront' ),
		    'priority'   	=> 20,
		    'description' 	=> __( 'Customise the look & feel of the blog post pages', 'storefront-blog-customiser' ),
		    'panel'			=> 'sbc_panel',
		) );

		/**
	     * Homepage blog section
	     */
	    $wp_customize->add_section( 'storefront_blog_homepage' , array(
		    'title'      	=> __( 'Homepage', 'storefront' ),
		    'priority'   	=> 30,
		    'description' 	=> __( 'Configure the display of blog posts on the homepage template', 'storefront-blog-customiser' ),
		    'panel'			=> 'sbc_panel',
		) );

		/**
		 * Post layout
		 */
		$wp_customize->add_setting( 'sbc_post_layout_archive', array(
			'default'    		=> 'meta-left',
			'sanitize_callback' => 'storefront_sanitize_choices',
		) );

		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'sbc_post_layout_archive', array(
			'label'    => __( 'Post meta display', 'storefront-blog-customiser' ),
			'section'  => 'storefront_blog_archive',
			'settings' => 'sbc_post_layout_archive',
			'type'     => 'select',
			'priority' => 10,
			'choices'  => array(
				'default'            => __( 'Left of content', 'storefront-blog-customiser' ),
				'meta-right'         => __( 'Right of content', 'storefront-blog-customiser' ),
				'meta-inline-top'    => __( 'Above content', 'storefront-blog-customiser' ),
				'meta-inline-bottom' => __( 'Beneath content', 'storefront-blog-customiser' ),
				'meta-hidden'        => __( 'Hidden', 'storefront-blog-customiser' ),
			),
		) ) );

		/**
		 * Blog archive layout
		 */
		$wp_customize->add_setting( 'sbc_blog_archive_layout', array(
			'default'           => false,
			'sanitize_callback' => 'storefront_sanitize_checkbox',
		) );

		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'sbc_blog_archive_layout', array(
			'label'         => __( 'Full width', 'storefront-blog-customiser' ),
			'description'   => __( 'Display blog archives in a full width layout.', 'storefront-blog-customiser' ),
			'section'       => 'storefront_blog_archive',
			'settings'      => 'sbc_blog_archive_layout',
			'type'          => 'checkbox',
			'priority'      => 20,
		) ) );

		/**
		 * Magazine layout
		 */
		$wp_customize->add_setting( 'sbc_magazine_layout', array(
			'default'           => false,
			'sanitize_callback'	=> 'storefront_sanitize_checkbox',
		) );

		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'sbc_magazine_layout', array(
			'label'         => __( 'Magazine layout', 'storefront-blog-customiser' ),
			'description'   => __( 'Apply a "magazine" layout to blog archives.', 'storefront-blog-customiser' ),
			'section'       => 'storefront_blog_archive',
			'settings'      => 'sbc_magazine_layout',
			'type'          => 'checkbox',
			'priority'      => 30,
		) ) );

		/**
		 * Single post layout
		 */
		$wp_customize->add_setting( 'sbc_post_layout_single', array(
			'default'    		=> 'meta-left',
			'sanitize_callback' => 'storefront_sanitize_choices',
		) );

		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'sbc_post_layout_single', array(
			'label'    => __( 'Post meta display', 'storefront-blog-customiser' ),
			'section'  => 'storefront_blog_single',
			'settings' => 'sbc_post_layout_single',
			'type'     => 'select',
			'priority' => 10,
			'choices'  => array(
				'default'            => __( 'Left of content', 'storefront-blog-customiser' ),
				'meta-right'         => __( 'Right of content', 'storefront-blog-customiser' ),
				'meta-inline-top'    => __( 'Above content', 'storefront-blog-customiser' ),
				'meta-inline-bottom' => __( 'Beneath content', 'storefront-blog-customiser' ),
				'meta-hidden'        => __( 'Hidden', 'storefront-blog-customiser' ),
			),
		) ) );

		/**
		 * Blog single full width
		 */
		$wp_customize->add_setting( 'sbc_blog_single_layout', array(
			'default'           => false,
			'sanitize_callback'	=> 'storefront_sanitize_checkbox',
		) );

		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'sbc_blog_single_layout', array(
			'label'         => __( 'Full width', 'storefront-blog-customiser' ),
			'description'   => __( 'Give the single blog post pages a full width layout.', 'storefront-blog-customiser' ),
			'section'       => 'storefront_blog_single',
			'settings'      => 'sbc_blog_single_layout',
			'type'          => 'checkbox',
			'priority'      => 20,
		) ) );

		/**
		 * Homepage Blog Toggle
		 */
		$wp_customize->add_setting( 'sbc_homepage_blog_toggle', array(
			'default'           => false,
			'sanitize_callback' => 'storefront_sanitize_checkbox',
		) );

		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'sbc_homepage_blog_toggle', array(
			'label'         => __( 'Display blog posts', 'storefront-blog-customiser' ),
			'description'   => __( 'Toggle the display of blog posts on the homepage.', 'storefront-blog-customiser' ),
			'section'       => 'storefront_blog_homepage',
			'settings'      => 'sbc_homepage_blog_toggle',
			'type'          => 'checkbox',
			'priority'      => 10,
			)
		) );

		/**
		 * Homepage Blog Title
		 */
		$wp_customize->add_setting( 'sbc_homepage_blog_title', array(
			'default'           => __( 'Recent Blog Posts', 'storefront-blog-customiser' ),
			'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'sbc_homepage_blog_title', array(
			'label'         	=> __( 'Blog post title', 'storefront-blog-customiser' ),
			'section'       	=> 'storefront_blog_homepage',
			'settings'      	=> 'sbc_homepage_blog_title',
			'type'     			=> 'text',
			'priority'			=> 20,
			)
		) );

		/**
		 * Homepage post layout
		 */
		$wp_customize->add_setting( 'sbc_post_layout_homepage', array(
			'default'    		=> 'meta-left',
			'sanitize_callback' => 'storefront_sanitize_choices',
		) );

		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'sbc_post_layout_homepage', array(
			'label'    => __( 'Post meta display', 'storefront-blog-customiser' ),
			'section'  => 'storefront_blog_homepage',
			'settings' => 'sbc_post_layout_homepage',
			'type'     => 'select',
			'priority' => 25,
			'choices'  => array(
				'default'            => __( 'Left of content', 'storefront-blog-customiser' ),
				'meta-right'         => __( 'Right of content', 'storefront-blog-customiser' ),
				'meta-inline-top'    => __( 'Above content', 'storefront-blog-customiser' ),
				'meta-inline-bottom' => __( 'Beneath content', 'storefront-blog-customiser' ),
				'meta-hidden'        => __( 'Hidden', 'storefront-blog-customiser' ),
			),
		) ) );

		/**
		 * Homepage Blog Columns
		 */
		$wp_customize->add_setting( 'sbc_homepage_blog_columns', array(
			 'default'           => '2',
			 'sanitize_callback' => 'storefront_sanitize_choices',
		) );

		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'sbc_homepage_blog_columns', array(
			'label'    => __( 'Blog post columns', 'storefront-blog-customiser' ),
			'section'  => 'storefront_blog_homepage',
			'settings' => 'sbc_homepage_blog_columns',
			'type'     => 'select',
			'priority' => 30,
			'choices'  => array(
							'1' => '1',
							'2' => '2',
							'3' => '3',
						),
			)
		) );

		/**
		 * Homepage Blog limit
		 */
		$wp_customize->add_setting( 'sbc_homepage_blog_limit', array(
			'default'           => 2,
			'sanitize_callback' => 'storefront_sanitize_choices',
		) );

		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'sbc_homepage_blog_limit', array(
				'label'    => __( 'Number of posts to display', 'storefront-blog-customiser' ),
				'section'  => 'storefront_blog_homepage',
				'settings' => 'sbc_homepage_blog_limit',
				'type'     => 'select',
				'priority' => 40,
				'choices'  => array(
								'1'	 => '1',
								'2'	 => '2',
								'3'	 => '3',
								'4'	 => '4',
								'5'	 => '5',
								'6'  => '6',
								'7'  => '7',
								'8'  => '8',
								'9'	 => '9',
								'10' => '10',
								'11' => '11',
								'12' => '12',
							),
			)
		) );

	}

	/**
	 * Storefront Blog Customiser Body Class
	 *
	 * @param array $classes the classes applied to the body tag.
	 * @see get_theme_mod()
	 */
	public function sbc_body_class( $classes ) {
		global $storefront_version;

		$post_layout_archive     = get_theme_mod( 'sbc_post_layout_archive', 'default' );
		$post_layout_single      = get_theme_mod( 'sbc_post_layout_single', 'default' );
		$post_layout_homepage    = get_theme_mod( 'sbc_post_layout_homepage', 'default' );
		$blog_archive_full_width = get_theme_mod( 'sbc_blog_archive_layout', false );
		$blog_single_full_width  = get_theme_mod( 'sbc_blog_single_layout', false );
		$magazine                = get_theme_mod( 'sbc_magazine_layout', false );

		if ( version_compare( $storefront_version, '2.0.0', '>=' ) ) {
			$version = '-2';
		} else {
			$version = '';
		}

		if ( is_archive() || is_category() || is_tag() || ( is_home() && ! is_page_template( 'template-homepage.php' ) ) ) {
			$classes[] = 'sbc-' . $post_layout_archive . $version;
		}

		if ( is_single() ) {
			$classes[] = 'sbc-' . $post_layout_single . $version;
		}

		if ( is_page_template( 'template-homepage.php' ) ) {
			$classes[] = 'sbc-' . $post_layout_homepage . $version;
		}

		if ( ( is_category() || is_tag() || is_home() ) && true == $blog_archive_full_width ) {
			$classes[] = 'storefront-full-width-content';
		}

		if ( is_singular( 'post' ) && true == $blog_single_full_width ) {
			$classes[] = 'storefront-full-width-content';
		}

		if ( ( is_category() || is_tag() || is_home() ) && true == $magazine ) {
			$classes[] = 'sbc-magazine';
		}

		return $classes;
	}

	/**
	 * Layout
	 * Tweaks layout based on settings
	 */
	public function sbc_layout() {
		$post_layout_archive     = get_theme_mod( 'sbc_post_layout_archive', 'default' );
		$post_layout_single      = get_theme_mod( 'sbc_post_layout_single', 'default' );
		$post_layout_homepage    = get_theme_mod( 'sbc_post_layout_homepage', 'default' );
		$blog_archive_full_width = get_theme_mod( 'sbc_blog_archive_layout', false );
		$blog_single_full_width  = get_theme_mod( 'sbc_blog_single_layout', false );

		// Archives.
		if ( 'meta-inline-bottom' == $post_layout_archive && ( is_archive() || is_category() || is_tag() || ( is_home() && ! is_page_template( 'template-homepage.php' ) ) ) ) {
			remove_action( 'storefront_loop_post', 'storefront_post_meta', 20 );
			add_action( 'storefront_loop_post',    'storefront_post_meta', 35 );
		}

		if ( 'meta-hidden' == $post_layout_archive && ( is_archive() || is_category() || is_tag() || ( is_home() && ! is_page_template( 'template-homepage.php' ) ) ) ) {
			remove_action( 'storefront_loop_post', 'storefront_post_meta', 20 );
		}

		// Single posts
		if ( 'meta-inline-bottom' == $post_layout_single && is_single() ) {
			remove_action( 'storefront_single_post', 'storefront_post_meta', 20 );
			add_action( 'storefront_single_post',    'storefront_post_meta', 35 );
		}

		if ( 'meta-hidden' == $post_layout_single && is_single() ) {
			remove_action( 'storefront_single_post', 'storefront_post_meta', 20 );
		}

		// Homepage
		if ( 'meta-inline-bottom' == $post_layout_homepage && is_page_template( 'template-homepage.php' ) ) {
			remove_action( 'storefront_loop_post', 'storefront_post_meta', 20 );
			add_action( 'storefront_loop_post',	   'storefront_post_meta', 35 );
		}

		if ( 'meta-hidden' == $post_layout_homepage && is_page_template( 'template-homepage.php' ) ) {
			remove_action( 'storefront_loop_post', 'storefront_post_meta', 20 );
		}

		if ( ( is_category() || is_tag() || is_home() ) && true == $blog_archive_full_width ) {
			remove_action( 'storefront_sidebar', 'storefront_get_sidebar', 10 );
		}

		if ( is_singular( 'post' ) && true == $blog_single_full_width ) {
			remove_action( 'storefront_sidebar', 'storefront_get_sidebar', 10 );
		}
	}

	/**
	 * Applies classes to the post tag.
	 *
	 * @param  array $classes The classes.
	 * @return array $classes The classes.
	 */
	function sbc_post_class( $classes ) {
		$magazine = get_theme_mod( 'sbc_magazine_layout', false );

		if ( true == $magazine && ! is_single() ) {
			global $wp_query;

			// Set "odd" or "even" class if is not single.
			$classes[] = $wp_query->current_post % 2 == 0 ? 'sbc-even' : 'sbc-odd';
		}

		return $classes;
	}

	/**
	 * Display the blog posts on the homepage
	 *
	 * @return void
	 */
	public static function storefront_homepage_blog() {
		$display_homepage_blog 	= get_theme_mod( 'sbc_homepage_blog_toggle', false );
		$title 					= get_theme_mod( 'sbc_homepage_blog_title', __( 'Recent Blog Posts', 'storefront-blog-customiser' ) );
		$homepage_blog_columns 	= get_theme_mod( 'sbc_homepage_blog_columns', '2' );
		$homepage_blog_limit 	= get_theme_mod( 'sbc_homepage_blog_limit', 2 );

		if ( true == $display_homepage_blog ) {
			$args 	= array(
					'post_type'           => 'post',
					'posts_per_page'      => absint( $homepage_blog_limit ),
					'ignore_sticky_posts' => true,
				);

			$query 	= new WP_Query( $args );

			echo '<div class="storefront-product-section storefront-blog columns-' . esc_attr( $homepage_blog_columns ) . '">';

			echo apply_filters( 'storefront_homepage_blog_section_title_html', $blog_section_title = '<h2 class="section-title">' . esc_attr( $title ) . '</h2>', $title );

			if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();

					get_template_part( 'content' );

			endwhile;
				wp_reset_postdata();
			else :
				echo '<p>' . esc_attr__( 'Sorry, no posts matched your criteria.', 'storefront-blog-customiser' ) . '</p>';
			endif;

			echo '</div>';
		}
	}
} // End Class
