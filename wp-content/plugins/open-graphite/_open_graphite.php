<?php
/*
Plugin Name: 	Open Graphite
Plugin URI: 	https://wordpress.org/plugins/open-graphite/
Description: 	Control how your content is viewed when shared on social media.
Version: 		1.4.2
Author: 		Rocket Apps
Author URI: 	https://rocketapps.com.au
License:        GPLv2
Domain Path: 	/languages/
*/

/* Look for translation file. */
function load_open_g_textdomain() {
    load_plugin_textdomain( 'open-graphite', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'load_open_g_textdomain' );

/* Add SETTINGS link from the plugins page */
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'open_graphite_link_from_plugin_page' );
function open_graphite_link_from_plugin_page( $links ) {
	return array_merge(array('settings' => '<a href="' . admin_url( '/admin.php?page=open-graphite-settings' ) . '">' . __( 'Settings', 'open-graphite' ) . '</a>'), $links);
}

/* Add custom CSS and Thickbox to admin. */
function open_graphite_admin_styles() {
	$plugin_directory = plugins_url('css/', __FILE__ );
	$open_graphite_plugin_data = get_plugin_data( __FILE__ );
	$open_graphite_plugin_version = $open_graphite_plugin_data['Version'];
	wp_enqueue_style('og-admin-styles', $plugin_directory . 'open-graphite.css', '', $open_graphite_plugin_version);
	wp_enqueue_script('jquery');
	wp_enqueue_media();
	add_thickbox();
}
add_action('admin_enqueue_scripts', 'open_graphite_admin_styles');


// Dimensions
function open_graphite_vars() {
    $ogvars = array(
        'default_title_chars'			=> 118,
		'default_desc_chars'			=> 140,
		'half_default_title_chars'		=> 59,
		'half_default_desc_chars'		=> 70,
		'full_dimensions'				=> '1200 x 630',
		'min_dimensions'				=> '600 x 315',
		'full_image_width'				=> 1200,
		'full_image_height'				=> 630,
		'min_image_width'				=> 600,
		'min_image_height'				=> 315,
    );
	return $ogvars;
	
	/*
		Usage: 
		$the_ogvar = open_graphite_vars(); // Must be present at the start of any pages you want to to use the variables on.
		echo $the_ogvar['min_image_height'];
 	*/
}


/* Add metabox for Object Types */
class open_graphite_otmeta {

    var $plugin_dir;
    var $plugin_url;
    
    function  __construct() {
		
        /* metabox types */
        add_action( 'add_meta_boxes', array( $this, '_open_graphite_open_type_meta_box' ) );
        add_action( 'save_post', array($this, 'save_data') );
    }
		
	/* Add the meta box */
    function _open_graphite_open_type_meta_box() {

		$frontpage_id 	= get_option( 'page_on_front' );
		$blog_id 		= get_option( 'page_for_posts' );
		/* Prevent metabox showing on the frontpage in admin */
		if(get_the_ID() != $frontpage_id) {
				
			$ogoptions 		= get_option( 'openg_settings' ); 
			$user           = wp_get_current_user();
			$allowed_roles  = array('editor', 'administrator', 'author'); /* Show metabox to these roles */
			$post_types		= isset($ogoptions['post_types']) ? $ogoptions['post_types'] : '';
			if( array_intersect($allowed_roles, $user->roles ) ) {
			
				if ($post_types) {
					add_meta_box(
						'og-meta-box',
						__( 'Open Graphite' ),
							array( &$this, 'render_open_graphite_meta_box' ),
						$ogoptions['post_types'],
						'advanced',
						'high'
					);
				}
			}
		}
	}
	
	
    function render_open_graphite_meta_box(){
        global $post;
		$ogoptions = get_option( 'openg_settings' ); 
		wp_nonce_field( plugin_basename( __FILE__ ), '_open_graphite_open_type__nounce' );

		$open_graphite_title 				= get_post_meta($post->ID, '_open_graph_title', true);
		$open_graphite_description			= get_post_meta($post->ID, '_open_graph_description', true);
		$open_graphite_post_type 			= get_post_meta($post->ID, '_open_graph_post_type', true);
		$open_graphite_title_default 		= isset($ogoptions['open_graphite_title_default']) ? $ogoptions['open_graphite_title_default'] : '';
		$open_graphite_description_default	= isset($ogoptions['open_graphite_description_default']) ? $ogoptions['open_graphite_description_default'] : '';
		$title_char_limit					= isset($ogoptions['open_graphite_title_char_limit']) ? $ogoptions['open_graphite_title_char_limit'] : '';
		$description_char_limit				= isset($ogoptions['open_graphite_description_char_limit']) ? $ogoptions['open_graphite_description_char_limit'] : '';
		$open_graphite_post_type_default	= isset($ogoptions['open_graphite_open_type_post_default']) ? $ogoptions['open_graphite_open_type_post_default'] : '';
		?>
		
		<!--/ Start Metabox /-->
        <div class="og-common">

			<!--/ Start title /-->
            <div class="og-div og-div-01">

                <p>
					<strong><?php _e( 'Title', 'open-graphite' ); ?></strong>
					<span><?php _e( 'The title that will be shown when this post is shared on social networks.', 'open-graphite' ); ?></span>
				</p>
				<?php if(!$open_graphite_title_default) { ?>
					<input type="text" id="_open_graph_title" name="_open_graph_title" value="<?php if ($open_graphite_title) { echo esc_html($open_graphite_title); } ?>" maxlength="<?php if($title_char_limit) { echo $title_char_limit; } else { echo $the_ogvar['default_title_chars']; } ?>" />
					<a class="get-title"><?php _e( 'Copy from title', 'open-graphite' ); ?></a>
				<?php } else { ?>
					<p class="using-default">
						<span class="dashicons dashicons-info"></span>
						<?php _e( 'You have set the open graph title to always use the <strong>post title</strong>.', 'open-graphite' ); ?>
					</p>
				<?php } ?>
				
            </div>
			<!--/ End title /-->
			
			<!--/ Start type /-->
			<div class="og-div og-div-02">
				<p>
					<strong><?php _e( 'Object Type', 'open-graphite' ); ?></strong>
					<span><a href="https://developers.facebook.com/docs/reference/opengraph#types" target="_blank" rel="noopener"><?php _e( 'Learn about object types', 'open-graphite' ); ?></a> <img src="<?php echo plugins_url('images/external.svg', __FILE__ );?>" class="og-external" /></span>
				</p>
					
				<?php if(!$open_graphite_post_type_default) { ?>

				<select id="_open_graph_post_type" name="_open_graph_post_type" class="half">
					<option></option>
					
					<optgroup label="<?php _e( 'Global', 'open-graphite' ); ?>">
						<option value="article"<?php if($open_graphite_post_type == "article") { echo " selected"; } ?>><?php _e( 'article', 'open-graphite' ); ?></option>
						<option value="book"<?php if($open_graphite_post_type == "book") { echo " selected"; } ?>><?php _e( 'book', 'open-graphite' ); ?></option>
						<option value="books.author"<?php if($open_graphite_post_type == "books.author") { echo " selected"; } ?>><?php _e( 'books.author', 'open-graphite' ); ?></option>
						<option value="books.book"<?php if($open_graphite_post_type == "books.book") { echo " selected"; } ?>><?php _e( 'books.book', 'open-graphite' ); ?></option>
						<option value="books.genre"<?php if($open_graphite_post_type == "books.genre") { echo " selected"; } ?>><?php _e( 'books.genre', 'open-graphite' ); ?></option>
						<option value="profile"<?php if($open_graphite_post_type == "profile") { echo " selected"; } ?>><?php _e( 'profile', 'open-graphite' ); ?></option>
						<option value="website"<?php if($open_graphite_post_type == "website") { echo " selected"; } ?>><?php _e( 'website', 'open-graphite' ); ?></option>
					</optgroup>

					<optgroup label="<?php _e( 'Music', 'open-graphite' ); ?>">
						<option value="music.album"<?php if($open_graphite_post_type == "music.album") { echo " selected"; } ?>><?php _e( 'music.album', 'open-graphite' ); ?></option>
						<option value="music.radio_station"<?php if($open_graphite_post_type == "music.radio_station") { echo " selected"; } ?>><?php _e( 'music.radio_station', 'open-graphite' ); ?></option>
						<option value="music.song"<?php if($open_graphite_post_type == "music.song") { echo " selected"; } ?>><?php _e( 'music.song', 'open-graphite' ); ?></option>
					</optgroup>

					<optgroup label="<?php _e( 'Video', 'open-graphite' ); ?>">
						<option value="video.episode"<?php if($open_graphite_post_type == "video.episode") { echo " selected"; } ?>><?php _e( 'video.episode', 'open-graphite' ); ?></option>
						<option value="video.movie"<?php if($open_graphite_post_type == "video.movie") { echo " selected"; } ?>><?php _e( 'video.movie', 'open-graphite' ); ?></option>
						<option value="video.other"<?php if($open_graphite_post_type == "video.other") { echo " selected"; } ?>><?php _e( 'video.other', 'open-graphite' ); ?></option>
						<option value="video.tv_show"<?php if($open_graphite_post_type == "video.tv_show") { echo " selected"; } ?>><?php _e( 'video.tv_show', 'open-graphite' ); ?></option>
					</optgroup>

					<optgroup label="<?php _e( 'Other', 'open-graphite' ); ?>">
						<option value="business.business"<?php if($open_graphite_post_type == "business.business") { echo " selected"; } ?>><?php _e( 'business.business', 'open-graphite' ); ?></option>
						<option value="object"<?php if($open_graphite_post_type == "object") { echo " selected"; } ?>><?php _e( 'object', 'open-graphite' ); ?></option>
						<option value="place"<?php if($open_graphite_post_type == "place") { echo " selected"; } ?>><?php _e( 'place', 'open-graphite' ); ?></option>
						<option value="product"<?php if($open_graphite_post_type == "product") { echo " selected"; } ?>><?php _e( 'product', 'open-graphite' ); ?></option>
						<option value="product.group"<?php if($open_graphite_post_type == "product.group") { echo " selected"; } ?>><?php _e( 'product.group', 'open-graphite' ); ?></option>
						<option value="restaurant.menu"<?php if($open_graphite_post_type == "restaurant.menu") { echo " selected"; } ?>><?php _e( 'restaurant.menu', 'open-graphite' ); ?></option>
						<option value="restaurant.menu_item"<?php if($open_graphite_post_type == "restaurant.menu_item") { echo " selected"; } ?>><?php _e( 'restaurant.menu_item', 'open-graphite' ); ?></option>
					</optgroup>

				</select>

				<?php } else { ?>

					<p class="using-default">
						<span class="dashicons dashicons-info"></span>
						<?php printf( __( 'You have set the open graph object type to always be <strong>%1$s</strong>.', 'open-graphite' ), $open_graphite_post_type_default ); ?>
						<input type="hidden" name="_open_graph_post_type" value="<?php echo esc_html($open_graphite_post_type_default); ?>" />
					</p>
				<?php } ?>
			</div>
			<!--/ End type /-->
    
			<!--/ Start description /-->
            <div class="og-div og-div-03">
                <p>
					<strong><?php _e( 'Description', 'open-graphite' ); ?></strong>
					<span><?php _e( 'The text that will be shown when this post is shared on social networks.', 'open-graphite' ); ?></span>
				</p>
					
				<?php if($open_graphite_description_default) { ?>

					<?php if(get_post_type() !== 'page') { ?>
						<p class="using-default">
							<span class="dashicons dashicons-info"></span>
							<?php _e( 'You have set the open graph description to always use the <strong>excerpt</strong>.', 'open-graphite' ); ?>
						</p>
					<?php } else { ?>
						<textarea id="_open_graph_description" name="_open_graph_description" maxlength="<?php if($description_char_limit) { echo $description_char_limit; } else { echo $the_ogvar['default_desc_chars']; } ?>"><?php if ($open_graphite_description) { echo $open_graphite_description; } else { echo strip_tags(get_the_excerpt()); } ?></textarea>
						<?php if ( get_post_type() !== 'page' ) { ?>
							<a class="get-excerpt"><?php _e( 'Copy from excerpt', 'open-graphite' ); ?></a>
						<?php } ?>
					<?php }  ?>

					<?php } else { ?>

					<textarea id="_open_graph_description" name="_open_graph_description" maxlength="<?php if($description_char_limit) { echo $description_char_limit; } else { echo $the_ogvar['default_desc_chars']; } ?>"><?php if ($open_graphite_description) { echo $open_graphite_description; } else { echo strip_tags(get_the_excerpt()); } ?></textarea>
					<?php if ( get_post_type() !== 'page' ) { ?>
						<a class="get-excerpt"><?php _e( 'Copy from excerpt', 'open-graphite' ); ?></a>
					<?php } ?>

				<?php } ?>
				
            </div>
			<!--/ End description /-->

			<!--/ Start Image Browse /-->
	        <div class="og-div og-div-04">
				<p>
					<strong><?php _e( 'Image', 'open-graphite' ); ?></strong>
					<span><?php _e( 'The image that will be shown when this post is shared on social networks.', 'open-graphite' ); ?></span>
				</p>
				<?php 
					$filename = get_post_meta($post->ID, '_custom_image', true);
					$image_default 		= isset($ogoptions['open_graphite_image_default']) ? $ogoptions['open_graphite_image_default'] : '';
					
					if(!$image_default) { /* If there is NO default image specified in settings */

						require_once('inc/post-image.php');
						
					} else { /* ...otherwise... */
						
						if(has_post_thumbnail()) {

							$fullsize_featured_image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full', $icon = false); ?>
							
							<input type="hidden" name="_custom_image" value="<?php echo $fullsize_featured_image[0]; ?>" />
						
						<?php }
						
					?>
					
					<p class="using-default">
						<span class="dashicons dashicons-info"></span>
						<?php _e( 'You have set the open graph image to always use the <strong>featured image</strong>.', 'open-graphite' ); ?>
					</p>
					
					<?php } /* End if there is NO default image specified in settings */ ?>
				
				</div>
				<!--/ End Image Browse /-->
			
				<div class="og-div og-div-05">
					<p>
						<strong><?php _e( 'Debugging', 'open-graphite' ); ?></strong>
						<span><?php _e( 'See how these social networks view your homepage.', 'open-graphite' ); ?></span>
					</p>
					<p><a href="https://developers.facebook.com/tools/debug/sharing/?q=<?php echo home_url(); ?>" target="_blank" rel="noopener" class="fb"><?php _e( 'Facebook', 'open-graphite' ); ?></a> | <a href="https://cards-dev.twitter.com/validator" target="_blank" rel="noopener" class="tw"><?php _e( 'Twitter', 'open-graphite' ); ?></a></p>
			
				</div>
			
		</div>
		<script>
			jQuery(function($) {
				/* Count description chars */
				$('#_open_graph_description').keyup(updateCount);
				$('#_open_graph_description').keydown(updateCount);

				function updateCount() {
					var cs = $(this).val().length;
					$('.count').text(cs);
				}

				/* Count title chars */
				$('#_open_graph_title').keyup(updateTitleCharCount);
				$('#_open_graph_title').keydown(updateTitleCharCount);

				function updateTitleCharCount() {
					var cs = $(this).val().length;
					$('.count-title').text(cs);
				}
				
				$('.get-excerpt').click(function() {
					$('#_open_graph_description').val($('#excerpt, .editor-post-excerpt textarea').val());
				});
				
				$('.get-title').click(function() {
					$('#_open_graph_title').val($('#title, .editor-post-title__input').val());
				});
				
			});
		</script>
		<!--/ End Metabox /-->
	
<?php  }
	

function save_data($post_id) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		return;

		if ( !wp_verify_nonce( $_POST['_open_graphite_open_type__nounce'], plugin_basename( __FILE__ ) ) )
		return;

		require_once('inc/update-post-meta.php');

		/* Facebook API scrape */
		$ogoptions 			= get_option( 'openg_settings' ); 
		$fb_app_id			= isset($ogoptions['open_graphite_home_fb_app_id']) ? $ogoptions['open_graphite_home_fb_app_id'] : '';
		$fb_access_token	= isset($ogoptions['open_graphite_home_access_token']) ? $ogoptions['open_graphite_home_access_token'] : '';

		
		if($fb_access_token && $fb_app_id) {
			$url = 'https://graph.facebook.com/v6.0/?id=' . urlencode(get_permalink($post_id)) . '&access_token=' . $fb_access_token . '&scrape=true&method=post';
			$json = file_get_contents($url, false, stream_context_create(
				array (
					'http' => array(
						'method'    => 'POST',
						'header'    => 'Content-type: application/x-www-form-urlencoded',
						'content'   =>  $url
					)
				)
			));
			$obj = json_decode($json, true); 
		}

		/* Check permissions */
		if ( 'page' == $_POST['post_type'] ){
			if ( !current_user_can( 'edit_page', $post_id ) )
				return;
		} else {
			if ( !current_user_can( 'edit_post', $post_id ) )
				return;
		}

	}
}

$open_graphite_otmeta = new open_graphite_otmeta;

/*
	Open Graphite Settings Page
*/

/* Register settings */
function openg_settings_init(){
    register_setting(
	'openg_settings',
	'openg_settings',
	'open_graphite_options_validate'
	);
}
add_action( 'admin_init', 'openg_settings_init' );

/* Add settings page */
function open_graphite_menu() {
	
	$open_graphite_parent_slug 	= 'open-graphite-settings';
	$open_graphite_capability	= apply_filters( 'open_graphite_required_capabilities', 'manage_options' );
	$icon_path 	    			= plugins_url('images/', __FILE__ ) . 'open-graphite-nav-icon.svg';
	$position					= 66;
	
	add_menu_page( __( 'Open Graphite' ), __( 'Open Graphite' ), $open_graphite_capability, $open_graphite_parent_slug, 'openg_settings_page' ,$icon_path, $position);
	add_submenu_page( $open_graphite_parent_slug, __( 'Help', 'open-graphite' ), __( 'Help', 'open-graphite' ), $open_graphite_capability, 'open-graphite-help', 'open_graphite_help' );
	do_action( 'open_graphite_extra_menu_items', $open_graphite_parent_slug, $open_graphite_capability );

}
add_action( 'admin_menu', 'open_graphite_menu' );

function open_graphite_help() {
	require_once('inc/help.php');	
}

/* Start settings page UI */
function openg_settings_page() {
	require_once('inc/settings-ui.php');	
}


/* Sanitize and validate */
function open_graphite_options_validate( $input ) {
    global $args, $select_options, $radio_options;
    if ( !isset( $input['openg_settings'] ) )
        $input['openg_settings'] = null;
    	$input['openg_settings'] = ( $input['openg_settings'] == 1 ? 1 : 0 );
    	$input['openg_settings'] = wp_filter_nohtml_kses( $input['openg_settings'] );
    if ( !isset( $input['openg_settings'] ) )
        $input['openg_settings'] = null;
    if ( is_array($args) && (!array_key_exists( $input['openg_settings'], $radio_options ) )) 
        $input['openg_settings'] = null;
    	$input['openg_settings'] = wp_filter_post_kses( $input['openg_settings'] );
	return $input;
	wp_verify_nonce($_POST['og-stuff'], 'save-og-settings');
}

/* Troubleshooting priority setting */
$ogoptions = get_option( 'openg_settings' ); 
$output_priority = isset($ogoptions['output_priority']) ? $ogoptions['output_priority'] : '';
if($output_priority == 'low_priority') {
	$output_priority = 99999;
} else if($output_priority == 'high_priority') {
	$output_priority = 1;
} else if($output_priority == 'auto_priority') {
	$output_priority = '';
} else {
	$output_priority = '';
}

/* Disable Jetpack open graph */
$disable_jetpack_og = isset($ogoptions['disable_jetpack_og']) ? $ogoptions['disable_jetpack_og'] : '';
if($disable_jetpack_og) {
	add_filter( 'jetpack_enable_open_graph', '__return_false' );
}

/* Open Graph Ouput into <head> */
add_action('wp_head', 'openghead', $output_priority);
function openghead() { 
	global $post;
	$ogoptions 				= get_option( 'openg_settings' ); 
	$home_url 				= get_option('home');
	$default_title						= isset($ogoptions['open_graphite_default_title']) ? $ogoptions['open_graphite_default_title'] : '';
	$ot 								= isset($ogoptions['open_graphite_open_type_homepage_default']) ? $ogoptions['open_graphite_open_type_homepage_default'] : '';
	$default_description				= isset($ogoptions['open_graphite_default_description']) ? $ogoptions['open_graphite_default_description'] : '';
	$default_image						= isset($ogoptions['default_home_image']) ? $ogoptions['default_home_image'] : '';
	$fb_app_ID							= isset($ogoptions['open_graphite_home_fb_app_id']) ? $ogoptions['open_graphite_home_fb_app_id'] : '';
	$open_graphite_post_type_default	= isset($ogoptions['open_graphite_open_type_post_default']) ? $ogoptions['open_graphite_open_type_post_default'] : '';
	$open_graphite_description_default 	= isset($ogoptions['open_graphite_description_default']) ? $ogoptions['open_graphite_description_default'] : '';

	$twitter_card			= $ogoptions['open_graphite_home_twitter_card_type'];
	if($twitter_card) {
		$twitter_card = $twitter_card;
	} else {
		$twitter_card = 'summary';
	}
	$twitter_username = $ogoptions['open_graphite_twitter_username'];
	if($twitter_username) { 
		$twitter_username = '
<meta name="twitter:creator" content="' . $twitter_username . '" />';
	}
	$image_ID				= attachment_url_to_postid($default_image);
	$full_image				= wp_get_attachment_image_src( $image_ID, $size = 'full', $icon = false);
	$full_image_URL			= $full_image[0];
	$full_image_width		= $full_image[1];
	$full_image_height		= $full_image[2];

	if($fb_app_ID) { 
		$fb_app_ID = '
<meta property="fb:app_id" content="' . $fb_app_ID . '" />';
	}
	
	/* Homepage */
	if ( is_home() || is_front_page() ) {

		$open_graphite_head = '
<!--/ Open Graphite Start /-->
<meta property="og:locale" content="' . get_locale() . '" />
<meta property="og:type" content="' . $ot . '" />
<meta property="og:url" content="' . $home_url . '" />
<meta property="og:title" content="' . esc_html($default_title) . '" />
<meta property="og:description" content="' . esc_html($default_description) . '" />
<meta property="og:site_name" content="' . esc_html($default_title) . '" />
<meta property="og:image" content="' . $default_image . '" />
<meta property="og:image:width" content="' . $full_image_width . '" />
<meta property="og:image:height" content="' . $full_image_height . '" />' 
. $fb_app_ID . '

<meta itemtype="'. $ot . '" />
<meta itemprop="description" content="' . esc_html($default_description) . '" />
<meta itemprop="image" content="' . $default_image . '" />

<meta name="twitter:card" content="' . $twitter_card . '" />
<meta name="twitter:url" content="' . $home_url . '" />
<meta name="twitter:title" content="' . esc_html($default_title) . '" />
<meta name="twitter:description" content="' . esc_html($default_description) . '" />
<meta name="twitter:image" content="' . $default_image . '" />'
. $twitter_username . '
<meta name="twitter:site" content="' . esc_html($default_title) . '" />
<!--/ Open Graphite End /-->' . "\n\n";
	
	/* Other pages, posts and custom post types */
	} else {

		if (isset($ogoptions['post_types']) && in_array(get_post_type(), $ogoptions['post_types']) || class_exists( 'WooCommerce' )) {
			
			if(class_exists( 'WooCommerce' )) {
				if(is_shop()) { 
					$id					= get_option( 'woocommerce_shop_page_id' );
					$open_graphite_url	= get_permalink( wc_get_page_id( 'shop' ) );
				} else {
					$id					= get_the_ID();
					$open_graphite_url	= get_permalink();
				}
			} else {
				$id					= get_the_ID();
				$open_graphite_url	= get_permalink();
			}

			$open_graphite_title		= get_post_meta($id, '_open_graph_title', true);

			if ( ! empty( $ogoptions['open_graphite_open_type_post_default'] ) ) {
				$ot = $open_graphite_post_type_default;
			} else {
				$ot = get_post_meta($id, '_open_graph_post_type', true);
			}

			if ( ! empty( $ogoptions['open_graphite_description_default'] ) ) {
				$open_graphite_description = get_the_excerpt($id);
			} else {
				$open_graphite_description = get_post_meta($id, '_open_graph_description', true);
			}
		
			$openg_objects 				= isset( $ogoptions['objects'] ) ? $ogoptions['objects'] : array();
			$open_graphite_image		= get_post_meta($id, '_custom_image', true);
			
			if($open_graphite_image && !has_post_thumbnail()) {
				$open_graphite_image	= $open_graphite_image;
			} else 
			if(has_post_thumbnail() && !$open_graphite_image) {
				$open_graphite_image	= get_the_post_thumbnail_url(get_the_ID(),'full');
			} else
			if(!$open_graphite_image || !has_post_thumbnail() ) {
				$open_graphite_image	= $default_image; /* When no featured or custom image, use the homepage default image instead. */
			}

			if ( ! empty( $ogoptions['open_graphite_title_default'] ) ) {
				$open_graphite_title = get_the_title();
			} else {
				$open_graphite_title = $open_graphite_title;
			}

			$image_ID                = attachment_url_to_postid($open_graphite_image);
			$full_image              = wp_get_attachment_image_src( $image_ID, $size = 'full', $icon = false);
			$full_image_URL          = $full_image[0];
			$full_image_width        = $full_image[1];
			$full_image_height       = $full_image[2];
			
			$open_graphite_head = '
<!--/ Open Graphite /-->
<meta property="og:locale" content="' . get_locale() . '" />
<meta property="og:type" content="' . $ot . '" />
<meta property="og:url" content="' . $open_graphite_url . '" />
<meta property="og:title" content="' . esc_html($open_graphite_title) . '" />
<meta property="og:description" content="' . esc_html($open_graphite_description) . '" />
<meta property="og:site_name" content="' . esc_html($default_title) . '" />
<meta property="og:image" content="' . $open_graphite_image . '" />
<meta property="og:image:width" content="' . $full_image_width . '" />
<meta property="og:image:height" content="' . $full_image_height . '" />'
. $fb_app_ID . '

<meta itemprop="description" content="' . esc_html($open_graphite_description) . '" />
<meta itemprop="image" content="' . $open_graphite_image . '" />

<meta name="twitter:card" content="' . $twitter_card . '" />
<meta name="twitter:url" content="' . $open_graphite_url . '" />
<meta name="twitter:title" content="' . esc_html($open_graphite_title) . '" />
<meta name="twitter:description" content="' . esc_html($open_graphite_description) . '" />
<meta name="twitter:image" content="' . $open_graphite_image . '" />'
. $twitter_username . '
<meta name="twitter:site" content="' . esc_html($default_title) . '" />
<!--/ Open Graphite End /-->' . "\n\n";
		}
	}
	echo $open_graphite_head;
}