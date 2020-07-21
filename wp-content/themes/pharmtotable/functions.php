<?php
add_action('get_header', 'my_filter_head');

  function my_filter_head() {
    remove_action('wp_head', '_admin_bar_bump_cb');
  }

  //Menus
add_theme_support( 'menus' );
register_nav_menu('main', 'Main Navigation Menu');

class My_Walker_Nav_Menu extends Walker_Nav_Menu {
  function start_lvl(&$output, $depth=0, $args = array ()) {
    $indent = str_repeat("\t", $depth);
    $output .= "\n$indent<ul class=\"dropdown-menu\">\n";
  }
}

/**
*  Add Class to links in menu, so the scroll works
*/
function add_menuclass($ulclass) {
  return preg_replace('/<a /', '<a class="dropdown-toggle" data-toggle="dropdown" ', $ulclass);
}
add_filter('wp_nav_menu','add_menuclass');


/**
 * Set Random order for entries in Providers page
 *
 * @author Bill Erickson
 * @author Sridhar Katakam
 * @link http://www.billerickson.net/customize-the-wordpress-query/
 * @param object $query data
 *
 */
add_action( 'pre_get_posts', 'dh_providers_random' );

function dh_providers_random( $query ) {

	if( $query->is_main_query() && !is_admin() && is_post_type_archive( 'provider' ) ) {
		$query->set( 'orderby', 'rand' );
	}

}


// Display States

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

// Widget Areas

if ( function_exists('register_sidebar') ) {
  register_sidebar(array(
    'name' => 'Sidebar',
    'before_widget' => '<div class="sidebar-item"><div class = "title">',
    'after_widget' => '</div></div>',
    'before_title' => '<h4>',
    'after_title' => '</h4>',
  ));
  register_sidebar(array(
    'name' => 'Footer 1',
    'before_widget' => '<div class="col-md-4 item"><div class="f-item">',
    'after_widget' => '</div></div>',
    'before_title' => '<h4>',
    'after_title' => '</h4>',
  ));
  register_sidebar(array(
    'name' => 'Footer 2',
    'before_widget' => '<div class="col-md-4 item"><div class="f-item">',
    'after_widget' => '</div></div>',
    'before_title' => '<h4>',
    'after_title' => '</h4>',
  ));
  register_sidebar(array(
    'name' => 'Footer 3',
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


// Numbered Pagination
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

