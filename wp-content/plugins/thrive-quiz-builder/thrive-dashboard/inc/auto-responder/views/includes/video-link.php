<?php $api_slug = strtolower( str_replace( array( ' ', '-' ), '', $this->getKey() ) ); ?>
<?php $video_urls = $this->apiVideosUrls(); ?>
<?php if ( array_key_exists( $api_slug, $video_urls ) && ! empty( $video_urls[ $api_slug ] ) ) : ?>
	<div class="ttw-video-urls-container tvd-m6 tvd-no-padding tvd-left ttw-<?php echo $api_slug ?>-video">
		<p class="ttw-video-urls-wrapper">
			<a href="<?php echo $video_urls[ $api_slug ] ?>"
			   class="ttw-video-urls wistia-popover[height=450,playerColor=2bb914,width=800]"><?php echo __( 'I need help with this', TVE_DASH_TRANSLATE_DOMAIN ); ?></a>
		</p>
	</div>
<?php endif ?>
