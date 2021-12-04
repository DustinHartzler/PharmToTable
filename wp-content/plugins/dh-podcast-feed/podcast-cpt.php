<?php /**
Plugin Name: Your Website Engineer Podcast Feed
Description: Plugin version in a folder
Version: 1.0
**/
#-----------------------------------------------------------------
# Add Jetpack Markdown Support for Podcast Custom Post Type
#-----------------------------------------------------------------
add_action('init', 'my_custom_init');
function my_custom_init() {
 	add_post_type_support( 'webinars', 'wpcom-markdown' );
}

// Register Custom Post Type
// Register Custom Post Type
function podcast_custom_post_type() {

	$labels = array(
		'name'                  => _x( 'Podcasts', 'Post Type General Name', 'text_domain' ),
		'singular_name'         => _x( 'Podcast', 'Post Type Singular Name', 'text_domain' ),
		'menu_name'             => __( 'Podcasts', 'text_domain' ),
		'name_admin_bar'        => __( 'Podcasts', 'text_domain' ),
		'archives'              => __( 'Podcast Archives', 'text_domain' ),
		'attributes'            => __( 'Item Attributes', 'text_domain' ),
		'parent_item_colon'     => __( 'Parent Item:', 'text_domain' ),
		'all_items'             => __( 'All Podcasts', 'text_domain' ),
		'add_new_item'          => __( 'Add New Podcast', 'text_domain' ),
		'add_new'               => __( 'Add New', 'text_domain' ),
		'new_item'              => __( 'New Podcast', 'text_domain' ),
		'edit_item'             => __( 'Edit Podcast', 'text_domain' ),
		'update_item'           => __( 'Update Podcast', 'text_domain' ),
		'view_item'             => __( 'View Podcast', 'text_domain' ),
		'view_items'            => __( 'View Podcasts', 'text_domain' ),
		'search_items'          => __( 'Search Podcasts', 'text_domain' ),
		'not_found'             => __( 'Not found', 'text_domain' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
		'featured_image'        => __( 'Featured Image', 'text_domain' ),
		'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
		'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
		'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
		'insert_into_item'      => __( 'Insert into item', 'text_domain' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'text_domain' ),
		'items_list'            => __( 'Items list', 'text_domain' ),
		'items_list_navigation' => __( 'Items list navigation', 'text_domain' ),
		'filter_items_list'     => __( 'Filter items list', 'text_domain' ),
	);
	$args = array(
		'label'                 => __( 'Podcast', 'text_domain' ),
		'description'           => __( 'All Podcast Episodes', 'text_domain' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'thumbnail', 'revisions', 'custom-fields', 'wpcom-markdown', 'excerpt' ),
		'taxonomies'            => array( 'category', 'post_tag' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'menu_icon'             => 'dashicons-microphone',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'show_in_rest' 			=> true,
		'capability_type'       => 'page',
	);
	register_post_type( 'podcast', $args );

}
add_action( 'init', 'podcast_custom_post_type', 0 );

function transcript_meta_callback($post){
	$spp 		= get_post_meta($post->ID, 'podcast-player', true);
	$enclosure	= get_post_meta($post->ID, 'enclosure', true);
    $transcript = get_post_meta($post->ID, 'transcript', true);
    $length		= get_post_meta($post->ID, 'show-length', true);
    $size		= get_post_meta($post->ID, 'show-size', true);
    $mp3link	= get_post_meta($post->ID, 'show-mp3', true);
	//$host	= get_post_meta($post->ID, 'show-host', true);
	$provider1	= get_post_meta($post->ID, 'show-provider1', true);
	$provider2	= get_post_meta($post->ID, 'show-provider2', true);
	$provider3	= get_post_meta($post->ID, 'show-provider3', true);
	$show_host = isset( $values['show_host'] ) ? esc_attr( $values['show_host'] ) : '';

    ?>

    <table>
    	<tr>
			<td align="left" width="180" colspan="3">
				<label for="podcast-player">Smart Podcast Player: </label>
				<input type="text" class="widefat" name="podcast-player" id="podcast-player"
				value="<?php echo esc_attr($spp); ?>"?>
			</td>
		</tr>
		<tr>
			<td width="10%" style="padding-right:25px">
				<label for="show-length">Show Length: </label>
				<input type="text" class="widefat" name="show-length" id="show-length"
				value="<?php echo esc_attr($length); ?>"?>
			</td>
			<td width="10%" style="padding-right:25px">
				<label for="show-size">Show Size: </label>
				<input type="text" class="widefat" name="show-size" id="show-size"
				value="<?php echo esc_attr($size); ?>"?>
			</td>
			<td width="60%">
				<label for="show-mp3">MP3 File Link: </label>
				<input type="text" class="widefat" name="show-mp3" id="show-mp3"
				value="<?php echo esc_attr($mp3link); ?>"?>
			</td>
		</tr>
		<tr>
			<td align="left" width="180" colspan="3">
				<label for="transcript">Transcript: </label><br>
				<textarea class="widefat" ROWS="3" COLS="75" name="transcript"
				id="transcript" ><?php echo esc_attr($transcript); ?></textarea>
		</td>

		</tr>
	</table>

    <?php
	$args = array( 	'post_type' => 'provider',
	'post_status' => 'publish',
	'numberposts' => '25');
$pages = get_posts($args);
?>
<p>
<label for="show_host">Podcast Host: </label>
<select name='show_host' id='show_host'>
<?php foreach ( $pages as $page ):
$host_name = esc_attr($page->post_title);
$host = '<option value="' . $host_name . '" ';
$host .= selected( $select, $host_name ) . ' >';
$host .= $host_name;
$host .= '</option>';
echo $host;
endforeach; ?>
</select><?php
}


	add_action('save_post', function($id){
		if (isset($_POST['podcast-player'])){
			update_post_meta($id, 'podcast-player', strip_tags($_POST['podcast-player']));
		}
		if (isset($_POST['show-length'])){
			update_post_meta($id, 'show-length', strip_tags($_POST['show-length']));
		}
		if (isset($_POST['show-size'])){
			update_post_meta($id, 'show-size', strip_tags($_POST['show-size']));
		}
		if (isset($_POST['show-mp3'])){
			update_post_meta($id, 'show-mp3', strip_tags($_POST['show-mp3']));
		}
		if (isset($_POST['transcript'])){
			update_post_meta($id, 'transcript', strip_tags($_POST['transcript']));
		}
		if (isset($_POST['show_host'])){
			//update_post_meta($id, 'show-host', strip_tags($_POST['show-host']));
			update_post_meta( $id, 'show_host', esc_attr( $_POST['show_host'] ) );
		}
	});

	//add_action( 'save_post', 'cd_meta_box_save' );
	function cd_meta_box_save( $post_id )
	{
		// Bail if we're doing an auto save
		if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

		// if our nonce isn't there, or we can't verify it, bail
		if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'my_meta_box_nonce' ) ) return;

		// if our current user can't edit this post, bail
		if( !current_user_can( 'edit_post' ) ) return;

		// now we can actually save the data
		$allowed = array(
			'a' => array( // on allow a tags
				'href' => array() // and those anchors can only have href attribute
			)
		);

		// Make sure your data is set before trying to save it
		if( isset( $_POST['my_meta_box_text'] ) )
			update_post_meta( $post_id, 'my_meta_box_text', wp_kses( $_POST['my_meta_box_text'], $allowed ) );

		if( isset( $_POST['my_meta_box_select'] ) )
			update_post_meta( $post_id, 'my_meta_box_select', esc_attr( $_POST['my_meta_box_select'] ) );

		// This is purely my personal preference for saving check-boxes
		$chk = isset( $_POST['my_meta_box_check'] ) && $_POST['my_meta_box_select'] ? 'on' : 'off';
		update_post_meta( $post_id, 'my_meta_box_check', $chk );
	}



function podcast_register_meta_boxes() {
	add_meta_box( 'transcript_meta', 'Show Assets', 'transcript_meta_callback', 'podcast' );
	add_meta_box( 'transcript_meta', 'Show Assets', 'transcript_meta_callback', 'post' );
}
//add_action( 'add_meta_boxes', 'podcast_register_meta_boxes' );

function my_meta_box_add() {
    add_meta_box( 'my_meta_box', 'Show Assets', 'my_meta_box', 'podcast' );
}
//add_action( 'add_meta_boxes', 'my_meta_box_add' );


function my_meta_box( $post ) {
	?>
    </p>
	<p>
        <label for="my_meta_box_post_type">Provider 1: </label>
        <select name='my_meta_box_post_type' id='my_meta_box_post_type'>
    <?php foreach ( $pages as $page ):
		$provider1 = '<option value="' . get_page_link( $page->ID ) . '">';
		$provider1 .= $page->post_title;
		$provider1 .= '</option>';
		echo $provider1;
        ?>
    <?php endforeach; ?>
</select>
    </p>
    <?php
}

#-----------------------------------------------------------------
# Transcript Checkbox in Podcast List
#-----------------------------------------------------------------

add_filter( 'manage_podcast_posts_columns', 'transcript_modify_post_table', 5 );

function transcript_modify_post_table( $column ) {
    $column['transcript'] = '<span class="dashicons dashicons-playlist-audio"></span>';

    return $column;
}

add_action( 'manage_podcast_posts_custom_column', 'transcript_modify_post_table_row', 3, 2 );

function transcript_modify_post_table_row( $column, $post_id ) {

    $custom_fields = get_post_custom( $post_id );

    switch ($column) {
        case 'transcript' :
        	if ($custom_fields['transcript'][0] != "")
            echo '<span class="dashicons dashicons-yes" style="color:green; font-size:24px;"></span>';
            break;
    }
}

#-----------------------------------------------------------------
# Limit to 2 revisions per post
#-----------------------------------------------------------------

add_filter( 'wp_revisions_to_keep', 'filter_function_name', 10, 2 );

function filter_function_name( $num, $post ) {

    if( 'podcast' == $post->post_type ) {
	$num = 2;
    }
    return $num;
}


add_action('init', 'podcast_rss');
function podcast_rss(){
  add_feed('podcast-feed', 'my_podcast_rss');
}

function my_podcast_rss(){
  require_once( dirname( __FILE__ ) . '/podcast-rss-template.php' );
}

#-----------------------------------------------------------------
# Remove the slug from published post permalinks. Only affect our custom post type, though.
#-----------------------------------------------------------------
function gp_remove_cpt_slug( $post_link, $post ) {

    if ( 'podcast' === $post->post_type && 'publish' === $post->post_status ) {
        $post_link = str_replace( '/' . $post->post_type . '/', '/', $post_link );
    }

    return $post_link;
}
add_filter( 'post_type_link', 'gp_remove_cpt_slug', 10, 2 );


function gp_add_cpt_post_names_to_main_query( $query ) {
	// Bail if this is not the main query.
	if ( ! $query->is_main_query() ) {
		return;
	}
	// Bail if this query doesn't match our very specific rewrite rule.
	if ( ! isset( $query->query['page'] ) || 2 !== count( $query->query ) ) {
		return;
	}
	// Bail if we're not querying based on the post name.
	if ( empty( $query->query['name'] ) ) {
		return;
	}
	// Add CPT to the list of post types WP will include when it queries based on the post name.
	$query->set( 'post_type', array( 'post', 'page', 'podcast' ) );
}
add_action( 'pre_get_posts', 'gp_add_cpt_post_names_to_main_query' );

#-----------------------------------------------------------------
# Add Custom Post Type to At a Glance
#-----------------------------------------------------------------
// add_action( 'dashboard_glance_items', 'cpad_at_glance_content_table_end' );
// function cpad_at_glance_content_table_end() {
//     $args = array(
//         'public' => true,
//         '_builtin' => false
//     );
//     $output = 'object';
//     $operator = 'and';

//     $post_types = get_post_types( $args, $output, $operator );
//     foreach ( $post_types as $post_type ) {
//         $num_posts = wp_count_posts( $post_type->name );
//         $num = number_format_i18n( $num_posts->publish );
//         $text = _n( $post_type->labels->singular_name, $post_type->labels->name, intval( $num_posts->publish ) );
//         if ( current_user_can( 'edit_posts' ) ) {
//             $output = '<a href="edit.php?post_type=' . $post_type->name . '">' . $num . ' ' . $text . '</a>';
//             echo '<li class="post-count ' . $post_type->name . '-count">' . $output . '</li>';
//         }
//     }
// }

//Add Custom Post Types To Your At A Glance Dashboard Widget
add_action( 'dashboard_glance_items', 'cpad_at_glance_content_table_end' );
function cpad_at_glance_content_table_end() {
    echo "<style type='text/css'>
           #dashboard_right_now li.podcast-count a:before {
                content: '\\f482';
                margin-left: -1px;
            }
           #dashboard_right_now li.tips-count a:before {
                content: '\\f486';
                margin-left: -1px;
            }
            #dashboard_right_now li.webinars-count a:before {
                content: '\\f118';
                margin-left: -1px;
            }
            #dashboard_right_now li.events-count a:before {
                content: '\\f145';
                margin-left: -1px;
            }
    </style>";
    $args = array(
        'public' => true,
        '_builtin' => false
    );
    $output = 'object';
    $operator = 'and';

    $post_types = get_post_types( $args, $output, $operator );
    foreach ( $post_types as $post_type ) {
        $num_posts = wp_count_posts( $post_type->name );
        $num = number_format_i18n( $num_posts->publish );
        $text = _n( $post_type->labels->singular_name, $post_type->labels->name, intval( $num_posts->publish ) );
        if ( current_user_can( 'edit_posts' ) ) {
            $output = '<a href="edit.php?post_type=' . $post_type->name . '">' . $num . ' ' . $text . '</a>';
            echo '<li class="post-count ' . $post_type->name . '-count">' . $output . '</li>';
        }
    }
}


add_action('add_meta_boxes','mind_scholar_meta_box');
function mind_scholar_meta_box() {
	add_meta_box('ms_meta_box','Info Meta Box','mind_scholar_meta_fields','podcast','normal','high');
}

function mind_scholar_meta_fields() {

	global $post;
	$field=get_post_meta($post->ID,'mind_scholar_key',true);
	$select=get_post_meta($post->ID,'mind_scholar_select_key',true);    ?> <html>    <body>    <table>
	   <tr>
		  <td width="50%">
			 School Name:
		  </td>
		  <td width="50%">
			 <input type="text" name="first" value="<?php echo $field ?>">
		  </td>
	   </tr>
	   <tr>
		  <td width="50%">
			 Stars Rating:
		  </td>
		  <td>
			 <select name='second_select'>
				<option value="star_1" <?php selected( $select, 'star_1' ); ?>>Star 1</option>
				<option value="star_2" <?php selected( $select, 'star_2' ); ?>>Star 2</option>
				<option value="star_3" <?php selected( $select, 'star_3' ); ?>>Star 3</option>
				<option value="star_4" <?php selected( $select, 'star_4' ); ?>>Star 4</option>
				<option value="star_5" <?php selected( $select, 'star_5' ); ?>>Star 5</option>
			 </select>
		  </td>
	   </tr>    </table>    </body>    </html>    <?php }

add_action('save_post','save_posts');

function save_posts() {
	global $post;
	$ms_field=$_POST['first'];
	$ms_field_select=$_POST['second_select'];

	update_post_meta($post->ID,'mind_scholar_key',$ms_field);
	update_post_meta($post->ID,'mind_scholar_select_key',$ms_field_select);

 }