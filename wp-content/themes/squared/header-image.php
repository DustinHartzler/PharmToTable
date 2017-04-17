<?php
$options        = thrive_get_options_for_post( get_the_ID() );
$featured_image = array();
if ( has_post_thumbnail( get_the_ID() ) ) {
	$featured_image = thrive_get_post_featured_image( get_the_ID(), 'full' );
}
?>
<?php if ( ! empty( $featured_image['image_src'] ) ): ?>
	<div class="hui hru fha" style="background-image: url('<?php echo $featured_image['image_src']; ?>')">
		<img class="tt-dmy" src="<?php echo $featured_image['image_src']; ?>"
		     alt="<?php echo $featured_image['image_alt'] ?>" title="<?php echo $featured_image['image_title'] ?>"/>
		<div class="hut">
			<div class="wrp">
				<?php if ( $options['show_post_title'] != 0 ): ?>
					<h1><?php the_title(); ?></h1>
				<?php endif; ?>
			</div>
		</div>
	</div>
<?php endif; ?>