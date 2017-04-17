<?php
$options        = thrive_get_options_for_post( get_the_ID() );
$featured_image = null;
if ( has_post_thumbnail( get_the_ID() ) ) {
	$featured_image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), "full" );
}

?>

<?php if ( $options['featured_title_bg_type'] == "image" && $featured_image && isset( $featured_image[0] ) && $options['featured_image_style'] == "wide" ): ?>

	<div
		class="hru fih
        <?php echo ( $options['featured_title_bg_img_static'] == 'static' ) ? 'hfi' : ''; ?>
        <?php echo ( $options['featured_title_bg_img_full_height'] == "on" ) ? 'fha' : ''; ?>
        "
		style="background-image: url('<?php echo $featured_image[0]; ?>');">
		<?php if ( $options['featured_title_bg_img_trans'] ): ?>
			<div class="ovh" style="background-color: <?php echo $options['featured_title_bg_img_trans'] ?>;"></div>
		<?php endif; ?>
		<?php if ( $options['featured_title_bg_img_full_height'] == "on" ): ?>
			<img class="tt-dmy" src="<?php echo $featured_image[0]; ?>"/>
		<?php endif; ?>

		<div class="hrui">
			<div class="wrp">
				<?php if ( $options['show_post_title'] != 0 ): ?>
					<h1>
						<?php the_title(); ?>
					</h1>
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

<?php elseif ( $options['show_post_title'] != 0 ): ?>
	<div class="hru tcbk"
	     <?php if ( $options['featured_title_bg_solid_color'] ): ?>style="background-color: <?php echo $options['featured_title_bg_solid_color'] ?>;"<?php endif; ?>>
		<div class="hrui">
			<div class="wrp">
				<h1>
					<?php the_title(); ?>
				</h1>

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
<?php endif; ?>
