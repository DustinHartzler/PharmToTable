<?php

/**
 * Storefront automatically loads the core CSS even if using a child theme as it is more efficient
 * than @importing it in the child theme style.css file.
 */

/**
 * Note: DO NOT! alter or remove the code above this text and only add your custom PHP functions below this text.
 */

/**
*  Add WooCommerce Support for Theme
*/
function mytheme_add_woocommerce_support() {
	add_theme_support( 'woocommerce' );
}
add_action( 'after_setup_theme', 'mytheme_add_woocommerce_support' );


/**
*  Display Providers Randomly on Providers page
*/
function dh_providers_random( $query ) {
	if( $query->is_main_query() && !is_admin() && is_post_type_archive( 'provider' ) ) {
		$query->set( 'orderby', 'rand' );
	}
}
add_action( 'pre_get_posts', 'dh_providers_random' );

/**
*  Allow HTML in Author's Bios
*/
remove_filter('pre_user_description', 'wp_filter_kses');

/**
*  Display States
*/
function display_member_taxonomy_terms($post_id){
    //get all terms assigned to this post
	$member_terms = get_the_terms($post_id,'state_category');
    //if we have member terms assigned to this post
    $i=1;
    if($member_terms){
        //loop through each term
        foreach($member_terms as $term){
            //collect term information and display it
            $term_name = $term->name;
            $term_link = get_term_link($term,'state_category');
            echo '<a href="' . $term_link . '">';
                echo '<span class="term">' . $term_name . '</span>';
            echo '</a>';
            echo ($i < count($member_terms))? ", " : "";
            $i++;
        }
    }
}

/**
*  Widget Areas
*/

if ( function_exists('register_sidebar') ) {
    register_sidebar(array(
      'name' => 'Sidebar',
      'id'            => 'child-sidebar',
      'before_widget' => '<div class="sidebar-item"><div class = "title">',
      'after_widget' => '</div></div>',
      'before_title' => '<h4>',
      'after_title' => '</h4>',
    ));
    register_sidebar(array(
      'name' => 'Pages',
      'id'            => 'child-pages',
      'before_widget' => '<div class="sidebar-item"><div class = "title">',
      'after_widget' => '</div></div>',
      'before_title' => '<h4>',
      'after_title' => '</h4>',
    ));
    register_sidebar(array(
      'name' => 'Footer 1',
      'id'            => 'child-footer-1',
      'before_widget' => '<div class="col-md-4 item"><div class="f-item">',
      'after_widget' => '</div></div>',
      'before_title' => '<h4>',
      'after_title' => '</h4>',
    ));
    register_sidebar(array(
      'name' => 'Footer 2',
      'id'            => 'child-footer-2',
      'before_widget' => '<div class="col-md-4 item"><div class="f-item">',
      'after_widget' => '</div></div>',
      'before_title' => '<h4>',
      'after_title' => '</h4>',
    ));
    register_sidebar(array(
      'name' => 'Footer 3',
      'id'            => 'child-footer-3',
      'before_widget' => '<div class="col-md-4 item"><div class="f-item">',
      'after_widget' => '</div></div>',
      'before_title' => '<h4>',
      'after_title' => '</h4>',
    ));
  }


  function wpse172754_add_widget_classes( $params ) {

    if ($params[0]['widget_name'] == 'Categories') {
      $params[0] = array_replace($params[0], array('before_widget' => str_replace("sidebar-item", "sidebar-item tags", $params[0]['before_widget'])));
    }

      return $params;

    }

    add_filter( 'dynamic_sidebar_params', 'wpse172754_add_widget_classes' );


/**
*  Numbered Pagination
*/
  if ( !function_exists( 'wpex_pagination' ) ) {

      function wpex_pagination() {

          $prev_arrow = is_rtl() ? '→' : '←';
          $next_arrow = is_rtl() ? '←' : '→';

          global $wp_query;
          $total = $wp_query->max_num_pages;
          $big = 999999999; // need an unlikely integer
          if( $total > 1 )  {
               if( !$current_page = get_query_var('paged') )
                   $current_page = 1;
               if( get_option('permalink_structure') ) {
                   $format = 'page/%#%/';
               } else {
                   $format = '&paged=%#%';
               }
              echo paginate_links(array(
                  'base'			=> str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
                  'format'		=> $format,
                  'current'		=> max( 1, get_query_var('paged') ),
                  'total' 		=> $total,
                  'mid_size'		=> 3,
                  'type' 			=> 'list',
                  'prev_text'		=> $prev_arrow,
                  'next_text'		=> $next_arrow,
               ) );
          }
      }

  }

  add_filter('next_posts_link_attributes', 'posts_link_attributes');
  add_filter('previous_posts_link_attributes', 'posts_link_attributes');



  /**
 * Disable sidebar on product pages in Storefront.
 *
 * @param bool $is_active_sidebar
 * @param int|string $index
 *
 * @return bool
 */
function iconic_remove_sidebar( $is_active_sidebar, $index ) {
	if( $index !== "sidebar-1" ) {
		return $is_active_sidebar;
	}

	if( ! is_product() ) {
		return $is_active_sidebar;
	}

	return false;
}

add_filter( 'is_active_sidebar', 'iconic_remove_sidebar', 10, 2 );



//Below is from FxMedCE

remove_filter ('the_exceprt', 'wpautop');

//Menus
add_theme_support( 'menus' );
register_nav_menu('main', 'Main Navigation Menu');
register_nav_menu('page', 'Page Navigation Menu');
register_nav_menu('account', 'My Account Navigation Menu');


class My_Walker_Nav_Menu extends Walker_Nav_Menu {
    function start_lvl(&$output, $depth=0, $args = array ()) {
      $indent = str_repeat("\t", $depth);
      $output .= "\n$indent<ul class=\"dropdown-menu\">\n";
    }
  }

  //add_filter( 'wp_nav_menu_objects', 'wpse_wp_nav_menu_objects', 10, 2 );
  function wpse_wp_nav_menu_objects( $sorted_menu_items, $args  ) {
      // Only modify the "main" menu.
      if ( ! isset( $args->menu->slug ) || 'main' !== $args->menu->slug ) {
          return $sorted_menu_items;
      }

      // Loop over the menu items wrapping only top level items in span tags.
      foreach ( $sorted_menu_items as $item ) {
          if ( ! $item->menu_item_parent ) {
              $item->title = '<span>' . $item->title . '</span>';
          }
      }

      return $sorted_menu_items;
  }


/**
*  Add Class to links in menu, so the dropdown arrow appears
*/
function add_menuclass($ulclass) {
    return preg_replace('/<a /', '<a class="dropdown-toggle" ', $ulclass, 1);
 }
 //add_filter('wp_nav_menu','add_menuclass');


 function your_submenu_class($menu) {

    $menu = preg_replace('/ class="dropdown"/','/ class="dropdown-menu" /',$menu);

    return $menu;

    }

    //add_filter('wp_nav_menu','your_submenu_class');

/**
* Remove search from Header
*/
add_action( 'init', 'jk_remove_storefront_header_search' );
function jk_remove_storefront_header_search() {
remove_action( 'storefront_header', 'storefront_product_search', 40 );
}

/**
* Remove Image Zoom
*/
function remove_image_zoom_support() {
    remove_theme_support( 'wc-product-gallery-zoom' );
}
add_action( 'wp', 'remove_image_zoom_support', 100 );

/**
* Remove SKU and Category on Product Pages
*/
add_filter( 'wc_product_sku_enabled', '__return_false' );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );

/**
* Change footer text
*/

add_action( 'init', 'custom_remove_footer_credit', 10 );

function custom_remove_footer_credit () {
    remove_action( 'storefront_footer', 'storefront_credit', 20 );
    add_action( 'storefront_after_footer', 'custom_storefront_credit', 20 );
}

//Don't Load WooCommerce CSS
add_filter('storefront_customizer_css', '__return_false');

//No related posts for providers
function winwar_no_related_posts( $options ) {
    if ( !is_singular( 'post' ) ) {
        $options['enabled'] = false;
    }
    return $options;
}
add_filter( 'jetpack_relatedposts_filter_options', 'winwar_no_related_posts' );

// WooCommerce pages full-width
add_action( 'wp', 'woa_remove_sidebar_shop_page' );
function woa_remove_sidebar_shop_page() {

if ( is_shop() || is_tax( 'product_cat' ) || get_post_type() == 'product' ) {

remove_action( 'storefront_sidebar', 'storefront_get_sidebar', 10 );
add_filter( 'body_class', 'woa_remove_sidebar_class_body', 10 );
}
}

function woa_remove_sidebar_class_body( $wp_classes ) {

$wp_classes[] = 'page-template-template-fullwidth-php';
return $wp_classes;
}

//Remove Storefront Styling
add_filter( 'storefront_customizer_enabled', '__return_false' );
add_filter( 'storefront_customizer_css', '__return_false' );
add_filter( 'storefront_customizer_woocommerce_css', '__return_false' );


function custom_storefront_credit() {
    ?>
    <!-- Start Footer
    ============================================= -->
    <footer>
    <!-- Start Footer Bottom -->
    <div class="footer-bottom bg-dark text-light">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <p>&copy; Copyright <?php echo get_the_date( 'Y' ); ?>. All Rights Reserved by <a href="https://pharmtotable.life">PharmToTable</a></p>
                    </div>
                    <div class="col-md-6 text-right link">
                        <ul>
                            <li>
                                <a href="#">Terms of user</a>
                            </li>
                            <li>
                                <a href="#">License</a>
                            </li>
                            <li>
                                <a href="#">Support</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Footer Bottom -->
        </footer>
    <!-- End Footer -->
    <?php
}


// Remove Storefront Header from Full page template
// 2223 => Toxic Report and 2231 => GI Report

add_action( 'wp', 'storefront_remove_title_from_home_default_template' );

function storefront_remove_title_from_home_default_template() {
   if ( is_front_page() || is_page('2223') || is_page('2231')) {
        remove_action( 'storefront_header', 'storefront_header_container', 0 );
        remove_action( 'storefront_header', 'storefront_skip_links', 5 );
        remove_action( 'storefront_header', 'storefront_social_icons', 10 );
        remove_action( 'storefront_header', 'storefront_site_branding', 20 );
        remove_action( 'storefront_header', 'storefront_secondary_navigation', 30 );
        remove_action( 'storefront_header', 'storefront_product_search', 40 );
        remove_action( 'storefront_header', 'storefront_header_container_close', 41 );
        remove_action( 'storefront_header', 'storefront_primary_navigation_wrapper', 42 );
        remove_action( 'storefront_header', 'storefront_primary_navigation', 50 );
        remove_action( 'storefront_header', 'storefront_header_cart', 60 );
        remove_action( 'storefront_header', 'storefront_primary_navigation_wrapper_close', 68);
        remove_action( 'storefront_before_content', 'storefront_page_header', 30 );
        remove_action( 'storefront_homepage', 'storefront_page_header', 10);
        remove_action( 'storefront_homepage', 'storefront_page_content', 20);
        remove_action( 'storefront_before_content', 'storefront_header_widget_region', 10);
remove_action( 'storefront_before_content', 'woocommerce_breadcrumb', 10);
   }
}
