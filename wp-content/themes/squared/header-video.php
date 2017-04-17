<?php
$options = thrive_get_options_for_post( get_the_ID() );

$featured_image = null;
if ( has_post_thumbnail( get_the_ID() ) ) {
	$featured_image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), "full" );
}

$thrive_meta_postformat_video_type        = get_post_meta( get_the_ID(), '_thrive_meta_postformat_video_type', true );
$thrive_meta_postformat_video_youtube_url = get_post_meta( get_the_ID(), '_thrive_meta_postformat_video_youtube_url', true );
$thrive_meta_postformat_video_vimeo_url   = get_post_meta( get_the_ID(), '_thrive_meta_postformat_video_vimeo_url', true );
$thrive_meta_postformat_video_custom_url  = get_post_meta( get_the_ID(), '_thrive_meta_postformat_video_custom_url', true );
$vimeo_embed_class;
$youtube_attrs     = array(
	'hide_logo'       => get_post_meta( get_the_ID(), '_thrive_meta_postformat_video_youtube_hide_logo', true ),
	'hide_controls'   => get_post_meta( get_the_ID(), '_thrive_meta_postformat_video_youtube_hide_controls', true ),
	'hide_related'    => get_post_meta( get_the_ID(), '_thrive_meta_postformat_video_youtube_hide_related', true ),
	'hide_title'      => get_post_meta( get_the_ID(), '_thrive_meta_postformat_video_youtube_hide_title', true ),
	'autoplay'        => get_post_meta( get_the_ID(), '_thrive_meta_postformat_video_youtube_autoplay', true ),
	'hide_fullscreen' => get_post_meta( get_the_ID(), '_thrive_meta_postformat_video_youtube_hide_fullscreen', true ),
	'video_width'     => 1080
);
$vimeo_embed_class = "";
if ( $thrive_meta_postformat_video_type == "youtube" ) {
	$video_code = _thrive_get_youtube_embed_code( $thrive_meta_postformat_video_youtube_url, $youtube_attrs );
} elseif ( $thrive_meta_postformat_video_type == "vimeo" ) {
	$video_code        = _thrive_get_vimeo_embed_code( $thrive_meta_postformat_video_vimeo_url );
	$vimeo_embed_class = "v-cep";
} else {
	if ( strpos( $thrive_meta_postformat_video_custom_url, "<" ) !== false ) { //if embeded code or url
		$video_code = $thrive_meta_postformat_video_custom_url;
	} else {
		$video_code = do_shortcode( "[video src='" . $thrive_meta_postformat_video_custom_url . "']" );
	}
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

		<div class="hrui <?php echo $vimeo_embed_class; ?>">
			<?php $wistiaVideoCode = ( strpos( $video_code, "wistia" ) !== false ) ? ' wistia-video-container' : ''; ?>
			<div class="scvps<?php echo $wistiaVideoCode; ?>">
				<div class="vdc lv">
					<div class="<?php if ( ! empty( $video_code ) ) : ?>ltx<?php endif ?>">
						<?php if ( $options['show_post_title'] != 0 ): ?>
							<h1><?php the_title(); ?></h1>
						<?php endif; ?>
						<?php if ( ! empty( $video_code ) ) : ?>
							<div class="pvb">
								<a></a>
							</div>
						<?php endif ?>
						<div class="hcc"
						     <?php if ( $options['meta_comment_count'] != 1 || get_comments_number() == 0 ): ?>style='display:none;'<?php endif; ?>>
							<a href="#comments">
								<?php echo get_comments_number(); ?>
								<?php echo ucfirst( _thrive_get_comments_label( get_comments_number() ) ); ?>
							</a>
						</div>
					</div>
				</div>
				<?php if ( ! empty( $video_code ) ) : ?>
					<div class="vdc lv video-container" style="display: none">
						<div class="vwr">
							<?php echo $video_code; ?>
						</div>
					</div>
				<?php endif ?>
			</div>
		</div>
	</div>

<?php elseif ( $options['show_post_title'] != 0 ): ?>
	<div class="hru tcbk"
	     <?php if ( $options['featured_title_bg_solid_color'] ): ?>style="background-color: <?php echo $options['featured_title_bg_solid_color'] ?>;"<?php endif; ?>>
		<div class="hrui <?php echo $vimeo_embed_class; ?>">
			<?php $wistiaVideoCode = ( strpos( $video_code, "wistia" ) !== false ) ? ' wistia-video-container' : ''; ?>
			<div class="scvps<?php echo $wistiaVideoCode; ?>">
				<div class="vdc lv">
					<div class="<?php if ( ! empty( $video_code ) ) : ?>ltx<?php endif ?>">
						<?php if ( $options['show_post_title'] != 0 ): ?>
							<h1><?php the_title(); ?></h1>
						<?php endif; ?>
						<?php if ( ! empty( $video_code ) ) : ?>
							<div class="pvb">
								<a></a>
							</div>
						<?php endif ?>
						<div class="hcc"
						     <?php if ( $options['meta_comment_count'] != 1 || get_comments_number() == 0 ): ?>style='display:none;'<?php endif; ?>>
							<a href="#comments">
								<?php echo get_comments_number(); ?>
								<?php echo ucfirst( _thrive_get_comments_label( get_comments_number() ) ); ?>
							</a>
						</div>
					</div>
				</div>
				<?php if ( ! empty( $video_code ) ) : ?>
					<div class="vdc lv video-container" style="display: none">
						<div class="vwr">
							<?php echo $video_code; ?>
						</div>
					</div>
				<?php endif ?>
			</div>
		</div>
	</div>
<?php endif; ?>


