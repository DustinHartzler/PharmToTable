<?php
$options            = thrive_get_options_for_post( get_the_ID(), array( 'apprentice' => 1 ) );
$featured_image     = null;
$featured_image_alt = $featured_image_title = '';
$thrive_lesson_type = get_post_meta( get_the_ID(), '_thrive_meta_appr_lesson_type', true );
if ( has_post_thumbnail( get_the_ID() ) && $thrive_lesson_type != "audio" && $thrive_lesson_type != "video" ) {
	$featured_image_data  = thrive_get_post_featured_image( get_the_ID(), $options['featured_image_style'] );
	$featured_image       = $featured_image_data['image_src'];
	$featured_image_alt   = $featured_image_data['image_alt'];
	$featured_image_title = $featured_image_data['image_title'];
}

$template_name = _thrive_get_item_template( get_the_ID() );
if ( $template_name == "Landing Page" ) {
	$options['display_meta'] = 0;
}
$current_post_type = get_post_type( get_the_ID() );
?>
<?php tha_entry_before(); ?>

	<article>
		<div class="awr lnd <?php if ( $current_post_type != TT_APPR_POST_TYPE_PAGE ): ?>hfp<?php endif; ?>">

			<?php if ( $options['featured_image_style'] == "thumbnail" && $featured_image ): ?>
				<img class="fit" alt="<?php echo $featured_image_alt; ?>" title="<?php echo $featured_image_title; ?>"
				     src="<?php echo $featured_image; ?>"/>
			<?php endif; ?>

			<?php the_content(); ?>

			<div class="clear"></div>

			<?php get_template_part( 'appr/download-box' ); ?>

			<?php if ( $options['enable_social_buttons'] == 1 ): ?>
				<?php get_template_part( 'share-buttons' ); ?>
			<?php endif; ?>

			<?php if ( is_user_logged_in() && $current_post_type == TT_APPR_POST_TYPE_LESSON ): ?>
				<?php if ( $options['appr_progress_track'] == 1 ): ?>
					<?php
					$current_lesson_progress = _thrive_appr_get_progress( get_the_ID() );
					if ( $current_lesson_progress == THRIVE_APPR_PROGRESS_NEW ) {
						_thrive_appr_set_progress( get_the_ID(), 0, THRIVE_APPR_PROGRESS_STARTED );
					}
					?>
					<div class="acl clearfix">
						<div class="acl-com">
							<input id="completed-lesson" type="checkbox" value="completedLesson" name="completedLesson"
							       <?php if ( $current_lesson_progress == THRIVE_APPR_PROGRESS_COMPLETED ): ?>checked<?php endif; ?> />
							<label for="completed-lesson">
								<div><a></a></div>
								<span><?php echo $options['appr_completed_text']; ?></span>
							</label>
						</div>
						<?php if ( $options['appr_favorites'] == 1 ): ?>
							<div class="fav right" id="tt-favorite-lesson">
								<a class="heart right<?php if ( _thrive_appr_check_favorite( get_the_ID() ) ): ?> fill<?php endif; ?>"></a>
                            <span class="right">
                                <?php if ( _thrive_appr_check_favorite( get_the_ID() ) ): ?>
	                                <?php _e( "Remove from Favorites", 'thrive' ); ?>
                                <?php else: ?>
	                                <?php _e( "Mark as Favorite", 'thrive' ); ?>
                                <?php endif; ?>
                            </span>

								<div class="clear"></div>
							</div>
							<div class="clear"></div>
						<?php endif; ?>
					</div>

				<?php endif; ?>

			<?php endif; ?>
			<?php
			if ( isset( $options['display_meta'] ) && $options['display_meta'] == 1 && $current_post_type != TT_APPR_POST_TYPE_PAGE ):
				$li_width_style = "width:" . ( 100 / $options['meta_no_columns'] ) . "%;";
				?>
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
						<?php endif;
						?>
						<?php if ( isset( $options['meta_post_category'] ) && $options['meta_post_category'] == 1 ): ?>
							<?php
							$categories = wp_get_post_terms( get_the_ID(), "apprentice" );
							if ( $categories ):
								?>
								<?php if ( count( $categories ) > 1 ): ?>
								<li style="<?php echo $li_width_style; ?>">
									<span class="awe">&#xf115;</span>
									<?php foreach ( $categories as $key => $category ): ?>
										<a
										href="<?php echo get_term_link( $category ); ?>"><?php echo $category->name; ?></a><?php if ( $key < count( $categories ) - 1 ): ?>, <?php endif; ?>
									<?php endforeach; ?>
								</li>
							<?php elseif ( isset( $categories[0] ) ): ?>
								<li style="<?php echo $li_width_style; ?>"><span class="awe">&#xf115;</span><a
										href="<?php echo get_term_link( $categories[0] ); ?>"><?php echo $categories[0]->name; ?></a>
								</li>
							<?php endif; ?>
							<?php endif; ?>
						<?php endif; ?>
						<?php if ( isset( $options['meta_post_tags'] ) && $options['meta_post_tags'] == 1 ): ?>
							<?php
							$posttags = wp_get_post_terms( get_the_ID(), "apprentice-tag" );
							if ( is_array( $posttags ) && count( $posttags ) > 0 ):
								$first_tag = current( $posttags );
								?>
								<li class="tgs" style="<?php echo $li_width_style; ?>">
									<span class="awe sma right">&#xf175;</span>
									<div class="right" href="">
										<a href="<?php echo get_tag_link( $first_tag->term_id ); ?>"><?php echo $first_tag->name; ?></a>
										<div>
											<?php foreach ( $posttags as $tag ): ?>
												<a href="<?php echo get_term_link( $tag ); ?>"><?php echo $tag->name; ?></a>
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
			<div class="clear"></div>
			<?php tha_entry_bottom(); ?>

		</div>
	</article>
<?php tha_entry_after(); ?>