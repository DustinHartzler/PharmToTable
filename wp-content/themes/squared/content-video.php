<?php
$options             = thrive_get_theme_options();
$featured_image_data = thrive_get_post_featured_image( get_the_ID(), $options['featured_image_style'] );
$featured_image      = $featured_image_data['image_src'];

$thrive_meta_postformat_video_type        = get_post_meta( get_the_ID(), '_thrive_meta_postformat_video_type', true );
$thrive_meta_postformat_video_youtube_url = get_post_meta( get_the_ID(), '_thrive_meta_postformat_video_youtube_url', true );
$thrive_meta_postformat_video_vimeo_url   = get_post_meta( get_the_ID(), '_thrive_meta_postformat_video_vimeo_url', true );
$thrive_meta_postformat_video_custom_url  = get_post_meta( get_the_ID(), '_thrive_meta_postformat_video_custom_url', true );

$youtube_attrs = array(
	'hide_logo'       => get_post_meta( get_the_ID(), '_thrive_meta_postformat_video_youtube_hide_logo', true ),
	'hide_controls'   => get_post_meta( get_the_ID(), '_thrive_meta_postformat_video_youtube_hide_controls', true ),
	'hide_related'    => get_post_meta( get_the_ID(), '_thrive_meta_postformat_video_youtube_hide_related', true ),
	'hide_title'      => get_post_meta( get_the_ID(), '_thrive_meta_postformat_video_youtube_hide_title', true ),
	'autoplay'        => get_post_meta( get_the_ID(), '_thrive_meta_postformat_video_youtube_autoplay', true ),
	'hide_fullscreen' => get_post_meta( get_the_ID(), '_thrive_meta_postformat_video_youtube_hide_fullscreen', true ),
	'video_width'     => 1080
);

if ( $thrive_meta_postformat_video_type == "youtube" ) {
	$video_code = _thrive_get_youtube_embed_code( $thrive_meta_postformat_video_youtube_url, $youtube_attrs );
} elseif ( $thrive_meta_postformat_video_type == "vimeo" ) {
	$video_code = _thrive_get_vimeo_embed_code( $thrive_meta_postformat_video_vimeo_url );
} else {
	$video_code = do_shortcode( "[video src='" . $thrive_meta_postformat_video_custom_url . "']" );
	if ( strpos( $thrive_meta_postformat_video_custom_url, "<" ) !== false || strpos( $thrive_meta_postformat_video_custom_url, "[" ) !== false ) { //if embeded code or shortcode
		$video_code = do_shortcode( $thrive_meta_postformat_video_custom_url );
	} else {
		$video_code = do_shortcode( "[video src='" . $thrive_meta_postformat_video_custom_url . "']" );
	}
}

?>
<?php tha_entry_before(); ?>
	<article <?php if ( is_sticky() ): ?>class="sticky"<?php endif; ?>>
		<?php tha_entry_top(); ?>

		<div class="awr lnd">

			<?php if ( ! empty( $video_code ) ) : ?>
				<?php $wistiaVideoCode = ( strpos( $video_code, "wistia" ) !== false ) ? ' wistia-video-container' : ''; ?>
				<div class="fwi brve<?php echo $wistiaVideoCode; ?>">
					<?php echo $video_code; ?>
				</div>
			<?php endif ?>

			<h2 class="entry-title">
				<a class="ccb" href="<?php the_permalink(); ?>#comments"
				   <?php if ( $options['meta_comment_count'] != 1 || get_comments_number() == 0 ): ?>style='display:none;'<?php endif; ?>>
					<?php echo get_comments_number(); ?>
				</a>
				<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
			</h2>

			<?php if ( $options['other_show_excerpt'] != 1 ): ?>
				<?php the_content(); ?>
			<?php else: ?>
				<?php the_excerpt(); ?>
				<?php $read_more_text = ( $options['other_read_more_text'] != "" ) ? $options['other_read_more_text'] : "Read more"; ?>
				<?php if ( $options['other_read_more_type'] == "button" ): ?>
					<a href="<?php the_permalink(); ?>"
					   class="btn dark medium"><span><?php echo $read_more_text ?></span></a>
				<?php else: ?>
					<a href='<?php the_permalink(); ?>' class=''><?php echo $read_more_text ?></a>
				<?php endif; ?>
			<?php endif; ?>
		</div>
		<?php
		if ( isset( $options['display_meta'] ) && $options['display_meta'] == 1 ):
			$li_width_style = "width:" . ( 100 / $options['meta_no_columns'] ) . "%;";
			?>
			<div class="clear"></div>
			<footer>
				<ul class="clearfix">
					<?php if ( isset( $options['meta_author_name'] ) && $options['meta_author_name'] == 1 ): ?>
						<li style="<?php echo $li_width_style; ?>">
							<span class="awe">&#xf007;</span>
							<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><?php echo get_the_author(); ?></a>
						</li>
					<?php endif; ?>
					<?php if ( isset( $options['meta_post_date'] ) && $options['meta_post_date'] == 1 ): ?>
						<li class="dlm" style="<?php echo $li_width_style; ?>">
							<span class="awe">&#xf017;</span>
                        <span>
                            <?php if ( $options['relative_time'] == 1 ): ?>
	                            <?php echo thrive_human_time( get_the_time( 'U' ) ); ?>
                            <?php else: ?>
	                            <?php echo get_the_date(); ?>
                            <?php endif; ?>
                        </span>
						</li>
					<?php endif; ?>
					<?php if ( isset( $options['meta_post_category'] ) && $options['meta_post_category'] == 1 ): ?>
						<?php
						$categories = get_the_category();
						if ( $categories ):
							?>
							<?php if ( count( $categories ) > 1 ): ?>
							<li style="<?php echo $li_width_style; ?>">
								<span class="awe">&#xf115;</span>
								<?php foreach ( $categories as $key => $category ): ?>
									<a
									href="<?php echo get_category_link( $category->term_id ); ?>"><?php echo $category->cat_name; ?></a><?php if ( $key < count( $categories ) - 1 ): ?>, <?php endif; ?>
								<?php endforeach; ?>
							</li>
						<?php elseif ( isset( $categories[0] ) ): ?>
							<li style="<?php echo $li_width_style; ?>"><span class="awe">&#xf115;</span><a
									href="<?php echo get_category_link( $categories[0]->term_id ); ?>"><?php echo $categories[0]->cat_name; ?></a>
							</li>
						<?php endif; ?>
						<?php endif; ?>
					<?php endif; ?>
					<?php if ( isset( $options['meta_post_tags'] ) && $options['meta_post_tags'] == 1 ): ?>
						<?php
						$posttags = get_the_tags();
						if ( is_array( $posttags ) ):
							$first_tag = current( $posttags );
							?>
							<li style="<?php echo $li_width_style; ?>" class="tgs">
								<span class="awe sma right">&#xf175;</span>
								<div class="right" href="">
									<a href="<?php echo get_tag_link( $first_tag->term_id ); ?>"><?php echo $first_tag->name; ?></a>
									<div>
										<?php foreach ( $posttags as $tag ): ?>
											<a href="<?php echo get_tag_link( $tag->term_id ); ?>"><?php echo $tag->name; ?></a>
										<?php endforeach; ?>
									</div>
								</div>
								<span class="awe right">&#xf02b;</span>
								<div class="clear"></div>
							</li>
						<?php endif; ?>
					<?php endif; ?>
				</ul>
				<div class="clear"></div>
			</footer>
		<?php endif; ?>
		<?php tha_entry_bottom(); ?>
	</article>
<?php _thrive_render_bottom_related_posts( get_the_ID(), $options ); ?>
<?php tha_entry_after(); ?>