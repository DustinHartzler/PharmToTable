<?php
$thrive_meta_postformat_quote_text   = get_post_meta( get_the_ID(), '_thrive_meta_postformat_quote_text', true );
$thrive_meta_postformat_quote_author = get_post_meta( get_the_ID(), '_thrive_meta_postformat_quote_author', true );
$options                             = thrive_get_options_for_post( get_the_ID() );
$featured_image                      = null;
if ( has_post_thumbnail( get_the_ID() ) ) {
	$featured_image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), "full" );
}
?>
<?php if ( $featured_image && isset( $featured_image[0] ) ): ?>
	<div class="hru fih quo
        <?php echo ( $options['featured_title_bg_img_static'] == 'static' ) ? 'hfi' : ''; ?>
        <?php echo ( $options['featured_title_bg_img_full_height'] == "on" ) ? 'fha' : ''; ?>"
	     style="background-image: url('<?php echo $featured_image[0]; ?>');">
		<?php if ( $options['featured_title_bg_img_trans'] ): ?>
			<div class="ovh" style="background-color: <?php echo $options['featured_title_bg_img_trans'] ?>;"></div>
		<?php endif; ?>
		<?php if ( $options['featured_title_bg_img_full_height'] == "on" ): ?>
			<img class="tt-dmy" src="<?php echo $featured_image[0]; ?>"/>
		<?php endif; ?>
		<div class="hrui">
			<div class="wrp">
				<h1><?php echo $thrive_meta_postformat_quote_text; ?></h1>
				<?php if ( ! empty( $thrive_meta_postformat_quote_author ) ): ?>
					<p><?php echo $thrive_meta_postformat_quote_author; ?></p>
				<?php endif; ?>
			</div>
		</div>
	</div>
<?php else: ?>
	<div class="hru tcbk quo">
		<div class="hrui">
			<div class="wrp">
				<h1><?php echo $thrive_meta_postformat_quote_text; ?></h1>
				<?php if ( ! empty( $thrive_meta_postformat_quote_author ) ): ?>
					<p><?php echo $thrive_meta_postformat_quote_author; ?></p>
				<?php endif; ?>
			</div>
		</div>
	</div>
<?php endif; ?>