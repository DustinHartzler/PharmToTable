<?php
$options = thrive_get_options_for_post( get_the_ID() );

$comment_nb_class     = ( $options['sidebar_alignement'] == "right" ) ? "comment_nb" : "right_comment_nb";
$featured_image_data  = thrive_get_post_featured_image( get_the_ID(), $options['featured_image_style'] );
$featured_image       = $featured_image_data['image_src'];
$featured_image_alt   = $featured_image_data['image_alt'];
$featured_image_title = $featured_image_data['image_title'];
$post_options         = get_post_custom( get_the_ID() );

$fname = get_the_author_meta( 'first_name' );
$lname = get_the_author_meta( 'last_name' );

$author_name  = get_the_author_meta( 'display_name' );
$display_name = empty( $author_name ) ? $fname . " " . $lname : $author_name;
?>
<?php tha_entry_before(); ?>
<article>
	<?php tha_entry_top(); ?>
	<div class="awr lnd <?php if ( ! is_page() ): ?>hfp<?php endif; ?>">

		<?php if ( $options['featured_image_style'] == "thumbnail" && $featured_image ): ?>
			<img class="fit" alt="<?php echo $featured_image_alt; ?>" title="<?php echo $featured_image_title; ?>"
			     src="<?php echo $featured_image; ?>"/>
		<?php endif; ?>

		<?php if ( $options['featured_title_bg_type'] != "image" && $options['featured_image_style'] == "wide" ): ?>
			<a href="<?php the_permalink(); ?>" class="fwit">
				<img alt="<?php echo $featured_image_alt; ?>" title="<?php echo $featured_image_title; ?>"
				     src="<?php echo $featured_image; ?>"/>
			</a>
		<?php endif; ?>

		<?php the_content(); ?>
		<div class="clear"></div>
		<?php
		wp_link_pages( array(
			'before'         => '<p class="ctr pgn">',
			'after'          => '<div class="clear"></div></p>',
			'next_or_number' => 'next_and_number',
			'echo'           => 1
		) );
		?>

		<?php if ( $options['enable_social_buttons'] == 1 ): ?>
			<?php get_template_part( 'share-buttons' ); ?>
		<?php endif; ?>
		<?php
		if ( isset( $options['display_meta'] ) && $options['display_meta'] == 1 && is_single() && get_post_type() == "post" ):
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
	</div>
	<?php tha_entry_bottom(); ?>
</article>
<?php _thrive_render_bottom_related_posts( get_the_ID(), $options ); ?>
<?php tha_entry_after(); ?>
<div class="spr"></div>