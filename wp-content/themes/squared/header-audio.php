<?php
$options                                            = thrive_get_options_for_post( get_the_ID() );
$thrive_meta_postformat_audio_type                  = get_post_meta( get_the_ID(), '_thrive_meta_postformat_audio_type', true );
$thrive_meta_postformat_audio_file                  = get_post_meta( get_the_ID(), '_thrive_meta_postformat_audio_file', true );
$thrive_meta_postformat_audio_soundcloud_url        = get_post_meta( get_the_ID(), '_thrive_meta_postformat_audio_soundcloud_url', true );
$thrive_meta_postformat_audio_soundcloud_autoplay   = get_post_meta( get_the_ID(), '_thrive_meta_postformat_audio_soundcloud_autoplay', true );
$thrive_meta_postformat_audio_soundcloud_embed_code = get_post_meta( get_the_ID(), '_thrive_meta_postformat_audio_soundcloud_embed_code', true );

$featured_image = null;
if ( has_post_thumbnail( get_the_ID() ) ) {
	$featured_image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), "full" );
}
?>
<?php if ( $options['featured_title_bg_type'] == "image" && $featured_image && isset( $featured_image[0] ) && $options['featured_image_style'] == "wide" ): ?>
	<div class="hru fih
        <?php echo ( $options['featured_title_bg_img_static'] == 'static' ) ? 'hfi' : ''; ?>
         <?php echo ( $options['featured_title_bg_img_full_height'] == "on" ) ? 'fha' : ''; ?>
         " style="background-image: url('<?php echo $featured_image[0]; ?>');">
		<?php if ( $options['featured_title_bg_img_trans'] ): ?>
			<div class="ovh" style="background-color: <?php echo $options['featured_title_bg_img_trans'] ?>;"></div>
		<?php endif; ?>
		<?php if ( $options['featured_title_bg_img_full_height'] == "on" ): ?>
			<img class="tt-dmy" src="<?php echo $featured_image[0]; ?>"/>
		<?php endif; ?>
		<div class="hrui">
			<div class="wrp">
				<?php if ( $options['show_post_title'] != 0 ): ?>
					<h1><?php the_title(); ?></h1>
				<?php endif; ?>
				<?php if ( $thrive_meta_postformat_audio_type != "soundcloud" ): ?>
					<?php echo do_shortcode( "[audio src='" . $thrive_meta_postformat_audio_file . "'][/audio]" ); ?>
				<?php else: ?>
					<?php echo $thrive_meta_postformat_audio_soundcloud_embed_code; ?>
				<?php endif; ?>
				<div class="hcc"
				     <?php if ( $options['meta_comment_count'] != 1 || get_comments_number() == 0 ): ?>style='display:none;'<?php endif; ?>>
					<a href="#comments">
						<?php echo get_comments_number(); ?>
						<?php echo ucfirst( _thrive_get_comments_label( get_comments_number() ) ); ?>
					</a>
				</div>
			</div>
		</div>
	</div>
<?php else: ?>
	<div class="hru tcbk">
		<div class="hrui">
			<div class="wrp">
				<?php if ( $options['show_post_title'] != 0 ): ?>
					<h1><?php the_title(); ?></h1>
				<?php endif; ?>
				<?php if ( $thrive_meta_postformat_audio_type != "soundcloud" ): ?>
					<?php echo do_shortcode( "[audio src='" . $thrive_meta_postformat_audio_file . "'][/audio]" ); ?>
				<?php else: ?>
					<?php echo $thrive_meta_postformat_audio_soundcloud_embed_code; ?>
				<?php endif; ?>
				<div class="hcc"
				     <?php if ( $options['meta_comment_count'] != 1 || get_comments_number() == 0 ): ?>style='display:none;'<?php endif; ?>>
					<a href="#comments"><?php echo get_comments_number(); ?><?php _e( "Comments", 'thrive' ); ?></a>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>