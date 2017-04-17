<?php
/*
 * Constants for the apprentice feature
 */
define( "TT_APPR_POST_TYPE_LESSON", "appr_lesson" );
define( "TT_APPR_POST_TYPE_PAGE", "appr_page" );

define( "TT_IMG_RESIZE_STATUS_ERROR", - 1 );
define( "TT_IMG_RESIZE_STATUS_NOT_STARTED", 0 );
define( "TT_IMG_RESIZE_STATUS_STARTED", 1 );
define( "TT_IMG_RESIZE_STATUS_FINISHED", 2 );
define( "TT_IMG_RESIZE_TYPE_SCALE", "scale" );
define( "TT_IMG_RESIZE_TYPE_SCALE_AND_CROP", "scale_and_crop" );
define( "TT_IMG_RESIZE_TYPE_DEFAULT", "default" );

/*
 * Returns an array with all the images sizes needed for this theme,
 * ordered ascending by the width
 */
function _thrive_get_theme_image_sizes_array( $key = null ) {

	$tt_image_sizes = array(
		"tt_post_icon"             => array( 50, 50 ), //tabs widget, post list shortcode
		"tt_featured_thumbnail"    => array( 250, 250 ),
		"tt_extended_menu"         => array( 250, 100 ),
		"tt_related_posts"         => array( 400, 200 ), //related posts
		"tt_grid_layout"           => array( 350, 150 ), //grid si masonry si post gallery
		"tt_featured_wide_sidebar" => array( 840, 462 ),
		"tt_featured_wide_narrow"  => array( 860, 473 ),
		"tt_featured_wide_full"    => array( 1030, 567 ),
	);

	if ( $key && isset( $tt_image_sizes[ $key ] ) ) {
		return $tt_image_sizes[ $key ];
	}

	return $tt_image_sizes;
}