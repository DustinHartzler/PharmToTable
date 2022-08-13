<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$the_ogvar = open_graphite_vars();
	/* 
		https://developer.wordpress.org/reference/functions/wp_get_attachment_image_src/
	*/
	$image_ID                = attachment_url_to_postid($filename); /* $filename is specified in _open_graphite_pro.php */

	$small_image             = wp_get_attachment_image_src( $image_ID, $size = 'thumbnail', $icon = false);
	$small_image_URL         = $small_image[0] ?? '';
	$small_image_width       = $small_image[1] ?? '';
	$small_image_height      = $small_image[2] ?? '';

	$full_image              = wp_get_attachment_image_src( $image_ID, $size = 'full', $icon = false);
	$full_image_URL          = $full_image[0] ?? '';
	$full_image_width        = $full_image[1] ?? '';
	$full_image_height       = $full_image[2] ?? '';

	$featured_image          = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full', $icon = false);
	$featured_image_URL      = $featured_image[0] ?? '';
	$featured_image_width    = $featured_image[1] ?? '';
	$featured_image_height   = $featured_image[2] ?? '';
?>

<?php /* If there is a custom image, show the Edit Image icon. */
if($filename) { ?>

	<div class="og-image-container">
		<a href="<?php echo admin_url() . 'post.php?post=' . $image_ID . '&action=edit&image-editor'; ?>" class="dashicons dashicons-admin-customizer" target="_blank" title="<?php _e( 'Edit image', 'open-graphite' ); ?>"></a>
		<img src="<?php echo $filename; ?>" class="og-image-preview" /> 
	</div>
	
<?php } else if ( has_post_thumbnail() ) { /* ...otherwise, if there is a featured image... */?>

	<div class="og-image-container">
		<a href="<?php echo admin_url() . 'post.php?post=' . get_post_thumbnail_id( $post_id ) . '&action=edit&image-editor'; ?>" class="dashicons dashicons-admin-customizer" target="_blank" title="<?php _e( 'Edit image', 'open-graphite' ); ?>"></a>
		<?php the_post_thumbnail( 'medium' ); ?>
	</div>

<?php } else { ?>

	<img class="og-image-preview" />

<?php } ?>

<div class="og-image-elements">
	<input type="button" class="media-button button" name="custom_image_btn" value="Browse" />
	<input type="text" id="_custom_image" name="_custom_image" value="<?php echo $filename; ?>" class="large-text" />

	<span>
		<?php _e( 'Leave empty if you prefer to use the featured image.', 'open-graphite' ); ?>
		<?php if($filename) { ?>
		<a class="clear-now">(<?php _e( 'Remove', 'open-graphite' ); ?>)</a>
		<?php } ?>
	</span>
		
	<?php if($filename) {
		if($full_image_width < $the_ogvar['min_image_width'] || $full_image_height < $the_ogvar['min_image_height']) { ?>
			<div class="image-error image-info og-settings-field-50">
				<span class="dashicons dashicons-warning"></span> 
				<?php printf( esc_html__( 'The image dimensions are too small at %1$s x %2$s. It is recommended the image be %3$s, or %4$s at very least.', 'open-graphite' ), $full_image_width, $full_image_height, $the_ogvar['full_dimensions'], $the_ogvar['min_dimensions'] ); ?>
			</div>
	<?php   }
		}
	?>
	
	<?php if(has_post_thumbnail() && !$filename) { 
		if($featured_image_width < $the_ogvar['min_image_width'] || $featured_image_height < $the_ogvar['min_image_height']) { ?>
			<div class="image-error image-info og-settings-field-50">
				<span class="dashicons dashicons-warning"></span> 
				<?php printf( esc_html__( 'The featured image dimensions are too small at %1$s x %2$s. It is recommended the image be %3$s, or %4$s at very least.', 'open-graphite' ), $featured_image_width, $featured_image_height, $the_ogvar['full_dimensions'], $the_ogvar['min_dimensions'] ); ?>
			</div>
	<?php } 
	} ?>
		
</div>

<script>
jQuery(function($) {
$('.clear-now').click(function() {
	$('#_custom_image').replaceWith('<input type="text" id="_custom_image" name="_custom_image" value="" class="large-text">');
	$('.image-error, .wp-post-image, .og-image-container .dashicons').remove();
	$('.og-image-preview').attr('src','');
});

$('.media-button').click(function(e) {
		e.preventDefault();
		var image = wp.media({ 
			title: 'Browse',
			multiple: false
		}).open()
		.on('select', function(e){
			var uploaded_image = image.state().get('selection').first();
			console.log(uploaded_image);
			var image_url = uploaded_image.toJSON().url;
			var image_id = uploaded_image.toJSON().id;
			$('#_custom_image').val(image_url);
			$('.og-image-container img, .og-image-preview').attr('srcset',image_url);
			$('.dashicons-admin-customizer').attr('href','<?php echo admin_url();?>post.php?post=' + image_id + '&action=edit&image-editor');
			$('.image-error').remove();
		});
	});
});
</script>