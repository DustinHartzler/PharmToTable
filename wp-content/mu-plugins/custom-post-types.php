<?php
/*
Plugin Name:  Custom Post Types
Description:  Add on plugin that adds custom post types to theme
Version:      1.0
Plugin URI:   http://yourwebsiteengineer.com
Author:       Dustin Hartzler
*/

#-----------------------------------------------------------------
# Add support for Custom Post Type
#-----------------------------------------------------------------
function pharm_custom_post() {
	//Providers
	$labels = array(
		'name'               => _x( 'Providers', 'post type general name' ),
		'singular_name'      => _x( 'Provider', 'post type singular name' ),
		'add_new'            => _x( 'Add New', 'book' ),
		'add_new_item'       => __( 'Add New Provider' ),
		'edit_item'          => __( 'Edit Providers' ),
		'new_item'           => __( 'New Providers' ),
		'all_items'          => __( 'All Providers' ),
		'view_item'          => __( 'View Providers' ),
		'search_items'       => __( 'Search All Providers' ),
		'not_found'          => __( 'Nothing found' ),
		'not_found_in_trash' => __( 'Nothing found in the Trash' ),
		'parent_item_colon'  => '',
		'menu_name'          => 'Providers'
	);
	$args = array(
		'labels'        => $labels,
		'description'   => 'Holds all Providers',
		'public'        => true,
		'publicly_queryable' => true,
		'menu_position' => 5,
		'menu_icon'		=> 'dashicons-businessperson',
		'show_in_rest'  => true,
		'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions', 'custom-fields' ),
		'has_archive'   => true,
	);
	register_post_type( 'provider', $args );

    //Testimonials
	$labels = array(
		'name'               => _x( 'Testimonials', 'post type general name' ),
		'singular_name'      => _x( 'Testimonial', 'post type singular name' ),
		'add_new'            => _x( 'Add New', 'book' ),
		'add_new_item'       => __( 'Add New Testimonial' ),
		'edit_item'          => __( 'Edit Testimonials' ),
		'new_item'           => __( 'New Testimonials' ),
		'all_items'          => __( 'All Testimonials' ),
		'view_item'          => __( 'View Testimonials' ),
		'search_items'       => __( 'Search All Testimonials' ),
		'not_found'          => __( 'Nothing found' ),
		'not_found_in_trash' => __( 'Nothing found in the Trash' ),
		'parent_item_colon'  => '',
		'menu_name'          => 'Testimonials'
	);
	$args = array(
		'labels'        => $labels,
		'description'   => 'Holds all Testimonials',
		'public'        => true,
		'publicly_queryable' => true,
		'menu_position' => 5,
		'menu_icon'		=> 'dashicons-testimonial',
		'show_in_rest'  => true,
		'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions', 'custom-fields' ),
		'has_archive'   => true,
	);
	register_post_type( 'testimonials', $args );
}

add_action( 'init', 'pharm_custom_post' );

function my_taxonomies_providers() {
	$labels = array(
	  'name'              => _x( 'Provider State', 'taxonomy general name' ),
	  'singular_name'     => _x( 'Provider State', 'taxonomy singular name' ),
	  'search_items'      => __( 'Search States' ),
	  'all_items'         => __( 'All State Categories' ),
	  'parent_item'       => __( 'Parent State Category' ),
	  'parent_item_colon' => __( 'Parent State Category:' ),
	  'edit_item'         => __( 'Edit State Category' ),
	  'update_item'       => __( 'Update State Category' ),
	  'add_new_item'      => __( 'Add New State Category' ),
	  'new_item_name'     => __( 'New State Category' ),
	  'menu_name'         => __( 'Provider States' ),
	);
	$args = array(
	  	'labels' 			=> $labels,
	  	'hierarchical' 		=> true,
	  	'show_in_rest' 		=> true,
	  	'show_admin_column' => true,
		'public'			=> true,
        'query_var'         => true
        //'rewrite'           => array('slug' => 'provider')
	);
	register_taxonomy( 'state_category', array('provider'), $args );
  }
  add_action( 'init', 'my_taxonomies_providers', 0 );

function set_order_post_type($query) {
  if($query->is_admin) {

        if ($query->get('post_type') == 'provider')
        {
          $query->set('orderby', 'title');
          $query->set('order', 'ASC');
        }
  }
  return $query;
}
add_filter('pre_get_posts', 'set_order_post_type');


function provider_metaboxes(){
	add_action('add_meta_boxes', function(){
		add_meta_box ('provider_resources', 'Provider Details', 'provider_assets', 'provider');
	});
function provider_assets($post){
	$facebook    	= get_post_meta($post->ID, 'provider_facebook', true);
	$instagram    	= get_post_meta($post->ID, 'provider_instagram', true);
	$linkedin      	= get_post_meta($post->ID, 'provider_linkedin', true);
	$twitter   		= get_post_meta($post->ID, 'provider_twitter', true);
	$pinterest     	= get_post_meta($post->ID, 'provider_pinterest', true);
	$youtube   		= get_post_meta($post->ID, 'provider_youtube', true);
	$credentials 	= get_post_meta($post->ID, 'provider_credentials', true);
	$azova			= get_post_meta($post->ID, 'provider_azova', true);
	$focus_area		= get_post_meta($post->ID, 'provider_focus_area', true);
	$pro_bio		= get_post_meta($post->ID, 'provider_pro_bio', true);
	$personal_bio	= get_post_meta($post->ID, 'provider_personal_bio', true);
	$day1			= get_post_meta($post->ID, 'provider_day1', true);
	$hour1			= get_post_meta($post->ID, 'provider_hour1', true);
	$day2			= get_post_meta($post->ID, 'provider_day2', true);
	$hour2			= get_post_meta($post->ID, 'provider_hour2', true);
	$day3			= get_post_meta($post->ID, 'provider_day3', true);
	$hour3			= get_post_meta($post->ID, 'provider_hour3', true);
	$day4			= get_post_meta($post->ID, 'provider_day4', true);
	$hour4			= get_post_meta($post->ID, 'provider_hour4', true);
	$day5			= get_post_meta($post->ID, 'provider_day5', true);
	$hour5			= get_post_meta($post->ID, 'provider_hour5', true);
	$day6			= get_post_meta($post->ID, 'provider_day6', true);
	$hour6			= get_post_meta($post->ID, 'provider_hour6', true);
	$day7			= get_post_meta($post->ID, 'provider_day7', true);
	$hour7			= get_post_meta($post->ID, 'provider_hour7', true);


	?>
	<p>
	<table>
		<tr>
			<td width="25%">
				<label for="provider_credentials">Credentials: </label>
				<input type="text" class="widefat" name="provider_credentials" id="provider_credentials"
				value="<?php echo esc_attr($credentials); ?>"?>
			</td>
			<td width="25%">
				<label for="provider_azova">Azova Schedule Link: </label>
				<input type="text" class="widefat" name="provider_azova" id="provider_azova"
				value="<?php echo esc_attr($azova); ?>"?>
			</td>
		</tr>
			<tr>
			<td width="50%">
				<label for="provider_pro_bio">Professional Bio: </label>
				<textarea class="widefat" ROWS="8" COLS="75" name="provider_pro_bio"
				id="provider_pro_bio" ><?php echo esc_attr($pro_bio); ?></textarea>
			</td>
			<td width="50%">
				<label for="provider_personal_bio">Personal Bio: </label>
				<textarea class="widefat" ROWS="8" COLS="75" name="provider_personal_bio"
				id="provider_personal_bio" ><?php echo esc_attr($personal_bio); ?></textarea>
			</td>
		</tr>
		</table>
		<h3>Social Media</h3>
		<table>
		<tr>
			<td width="20%">
				<label for="provider_facebook">Facebook: </label>
				<input type="text" class="widefat" name="provider_facebook" id="provider_facebook"
				value="<?php echo esc_attr($facebook); ?>"?>
			</td>
			<td width="20%">
				<label for="provider_instagram">Instagram: </label>
				<input type="text" class="widefat" name="provider_instagram" id="provider_instagram"
				value="<?php echo esc_attr($instagram); ?>"?>
			</td>
			<td width="20%">
				<label for="provider_linkedin">Linkedin: </label>
				<input type="text" class="widefat" name="provider_linkedin" id="provider_linkedin"
				value="<?php echo esc_attr($linkedin); ?>"?>
			</td>
		</tr>
		<tr>
			<td width="20%">
				<label for="provider_twitter">Twitter: </label>
				<input type="text" class="widefat" name="provider_twitter" id="provider_twitter"
				value="<?php echo esc_attr($twitter); ?>"?>
			</td>
			<td width="20%">
				<label for="provider_pinterest">Pinterest: </label>
				<input type="text" class="widefat" name="provider_pinterest" id="provider_pinterest"
				value="<?php echo esc_attr($pinterest); ?>"?>
			</td>
			<td width="20%">
				<label for="provider_youtube">YouTube: </label>
				<input type="text" class="widefat" name="provider_youtube" id="provider_youtube"
				value="<?php echo esc_attr($youtube); ?>"?>
			</td>
		</tr>
	</table>
	<h3>Working Hours</h3>
		<table>
		<tr>
			<td width="20%">
				<label for="provider_day1">Day: </label>
				<input type="text" class="widefat" name="provider_day1" id="provider_day1"
				value="<?php echo esc_attr($day1); ?>"?>
			</td>
			<td width="20%">
				<label for="provider_hour1">Hours: </label>
				<input type="text" class="widefat" name="provider_hour1" id="provider_hour1"
				value="<?php echo esc_attr($hour1); ?>"?>
			</td>
		</tr>
		<tr>
			<td width="20%">
				<label for="provider_day2">Day: </label>
				<input type="text" class="widefat" name="provider_day2" id="provider_day2"
				value="<?php echo esc_attr($day2); ?>"?>
			</td>
			<td width="20%">
				<label for="provider_hour2">Hours: </label>
				<input type="text" class="widefat" name="provider_hour2" id="provider_hour2"
				value="<?php echo esc_attr($hour2); ?>"?>
			</td>
			</td>
		</tr>
		<tr>
			<td width="20%">
				<label for="provider_day3">Day: </label>
				<input type="text" class="widefat" name="provider_day3" id="provider_day3"
				value="<?php echo esc_attr($day3); ?>"?>
			</td>
			<td width="20%">
				<label for="provider_hour3">Hours: </label>
				<input type="text" class="widefat" name="provider_hour3" id="provider_hour3"
				value="<?php echo esc_attr($hour3); ?>"?>
			</td>
		</tr>
		<tr>
			<td width="20%">
				<label for="provider_day4">Day: </label>
				<input type="text" class="widefat" name="provider_day4" id="provider_day4"
				value="<?php echo esc_attr($day4); ?>"?>
			</td>
			<td width="20%">
				<label for="provider_hour4">Hours: </label>
				<input type="text" class="widefat" name="provider_hour4" id="provider_hour4"
				value="<?php echo esc_attr($hour4); ?>"?>
			</td>
			</td>
		</tr>
		<tr>
			<td width="20%">
				<label for="provider_day5">Day: </label>
				<input type="text" class="widefat" name="provider_day5" id="provider_day5"
				value="<?php echo esc_attr($day5); ?>"?>
			</td>
			<td width="20%">
				<label for="provider_hour5">Hours: </label>
				<input type="text" class="widefat" name="provider_hour5" id="provider_hour5"
				value="<?php echo esc_attr($hour5); ?>"?>
			</td>
		</tr>
		<tr>
			<td width="20%">
				<label for="provider_day6">Day: </label>
				<input type="text" class="widefat" name="provider_day6" id="provider_day6"
				value="<?php echo esc_attr($day6); ?>"?>
			</td>
			<td width="20%">
				<label for="provider_hour6">Hours: </label>
				<input type="text" class="widefat" name="provider_hour6" id="provider_hour6"
				value="<?php echo esc_attr($hour6); ?>"?>
			</td>
		</tr>
		<tr>
			<td width="20%">
				<label for="provider_day7">Day: </label>
				<input type="text" class="widefat" name="provider_day7" id="provider_day7"
				value="<?php echo esc_attr($day7); ?>"?>
			</td>
			<td width="20%">
				<label for="provider_hour7">Hours: </label>
				<input type="text" class="widefat" name="provider_hour7" id="provider_hour7"
				value="<?php echo esc_attr($hour7); ?>"?>
			</td>
		</tr>
	</table>
	</p>
	<?php
}
	add_action('save_post', function($id){
		if (isset($_POST['provider_facebook'])){
			update_post_meta($id, 'provider_facebook', strip_tags($_POST['provider_facebook']));
		}
		if (isset($_POST['provider_instagram'])){
			update_post_meta($id, 'provider_instagram', strip_tags($_POST['provider_instagram']));
		}
		if (isset($_POST['provider_linkedin'])){
			update_post_meta($id, 'provider_linkedin', strip_tags($_POST['provider_linkedin']));
		}
		if (isset($_POST['provider_twitter'])){
			update_post_meta($id, 'provider_twitter', strip_tags($_POST['provider_twitter']));
		}
		if (isset($_POST['provider_pinterest'])){
			update_post_meta($id, 'provider_pinterest', strip_tags($_POST['provider_pinterest']));
		}
		if (isset($_POST['provider_youtube'])){
			update_post_meta($id, 'provider_youtube', strip_tags($_POST['provider_youtube']));
		}
		if (isset($_POST['provider_credentials'])){
			update_post_meta($id, 'provider_credentials', $_POST['provider_credentials']);
		}
		if (isset($_POST['provider_azova'])){
			update_post_meta($id, 'provider_azova', $_POST['provider_azova']);
		}
		if (isset($_POST['provider_pro_bio'])){
			update_post_meta($id, 'provider_pro_bio', $_POST['provider_pro_bio']);
		}
		if (isset($_POST['provider_personal_bio'])){
			update_post_meta($id, 'provider_personal_bio', $_POST['provider_personal_bio']);
		}
		if (isset($_POST['provider_day1'])){
			update_post_meta($id, 'provider_day1', $_POST['provider_day1']);
		}
		if (isset($_POST['provider_hour1'])){
			update_post_meta($id, 'provider_hour1', $_POST['provider_hour1']);
		}
		if (isset($_POST['provider_day2'])){
			update_post_meta($id, 'provider_day2', $_POST['provider_day2']);
		}
		if (isset($_POST['provider_hour2'])){
			update_post_meta($id, 'provider_hour2', $_POST['provider_hour2']);
		}
		if (isset($_POST['provider_day3'])){
			update_post_meta($id, 'provider_day3', $_POST['provider_day3']);
		}
		if (isset($_POST['provider_hour3'])){
			update_post_meta($id, 'provider_hour3', $_POST['provider_hour3']);
		}
		if (isset($_POST['provider_day4'])){
			update_post_meta($id, 'provider_day4', $_POST['provider_day4']);
		}
		if (isset($_POST['provider_hour4'])){
			update_post_meta($id, 'provider_hour4', $_POST['provider_hour4']);
		}
		if (isset($_POST['provider_day5'])){
			update_post_meta($id, 'provider_day5', $_POST['provider_day5']);
		}
		if (isset($_POST['provider_hour5'])){
			update_post_meta($id, 'provider_hour5', $_POST['provider_hour5']);
		}
		if (isset($_POST['provider_day6'])){
			update_post_meta($id, 'provider_day6', $_POST['provider_day6']);
		}
		if (isset($_POST['provider_hour6'])){
			update_post_meta($id, 'provider_hour6', $_POST['provider_hour6']);
		}
		if (isset($_POST['provider_day7'])){
			update_post_meta($id, 'provider_day7', $_POST['provider_day7']);
		}
		if (isset($_POST['provider_hour7'])){
			update_post_meta($id, 'provider_hour7', $_POST['provider_hour7']);
		}
	});
}

add_action( 'init', 'provider_metaboxes', 0 );

#-----------------------------------------------------------------
# Featured Images in Post List
#-----------------------------------------------------------------
add_image_size( 'admin-list-thumb', 80, 80, true );
add_filter('manage_post_posts_columns', 'new_add_post_thumbnail_column', 7);
add_filter('manage_provider_posts_columns', 'new_add_post_thumbnail_column', 7);

function new_add_post_thumbnail_column($cols){
$cols['new_post_thumb'] = __('Image');
return $cols;
}
add_action('manage_post_posts_custom_column', 'new_display_post_thumbnail_column', 5, 2);
add_action('manage_provider_posts_custom_column', 'new_display_post_thumbnail_column', 5, 2);

function new_display_post_thumbnail_column($col, $id){
switch($col){
case 'new_post_thumb':
if( function_exists('the_post_thumbnail') ) {
echo the_post_thumbnail( 'admin-list-thumb' );

}
else
echo 'Not supported in theme';
break;
}
}
