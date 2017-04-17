<?php
$options = thrive_get_theme_options();

$featured_image_data                 = thrive_get_post_featured_image( get_the_ID(), $options['featured_image_style'] );
$featured_image                      = $featured_image_data['image_src'];
$thrive_meta_postformat_quote_text   = get_post_meta( get_the_ID(), '_thrive_meta_postformat_quote_text', true );
$thrive_meta_postformat_quote_author = get_post_meta( get_the_ID(), '_thrive_meta_postformat_quote_author', true );
?>
<?php tha_entry_before(); ?>
	<article <?php if ( is_sticky() ): ?>class="sticky"<?php endif; ?>>
		<?php tha_entry_top(); ?>

		<div
			class="awr lnd <?php if ( $options['featured_image_style'] == "wide" && $featured_image ): ?>hasf<?php endif; ?>">

			<a class="ccb" href="<?php the_permalink(); ?>#comments"
			   <?php if ( $options['meta_comment_count'] != 1 || get_comments_number() == 0 ): ?>style='display:none;'<?php endif; ?>>
				<?php echo get_comments_number(); ?>
			</a>

			<?php if ( $featured_image ): ?>

				<div class="hru quo qui" style="background-image: url('<?php echo $featured_image; ?>')">
					<div class="hrui">
						<div class="wrp">
							<h2>
								<a href="<?php the_permalink(); ?>">
									<?php echo $thrive_meta_postformat_quote_text; ?>
								</a>
							</h2>
							<p><?php echo $thrive_meta_postformat_quote_author; ?></p>
						</div>
					</div>
				</div>

			<?php else: ?>

				<div class="hru tcbk quo">
					<div class="hrui">
						<div class="wrp">
							<h2>
								<a href="<?php the_permalink(); ?>">
									<?php echo $thrive_meta_postformat_quote_text; ?>
								</a>
							</h2>
							<p><?php echo $thrive_meta_postformat_quote_author; ?></p>
						</div>
					</div>
				</div>

			<?php endif; ?>
		</div>

		<?php tha_entry_bottom(); ?>
	</article>
<?php _thrive_render_bottom_related_posts( get_the_ID(), $options ); ?>
<?php tha_entry_after(); ?>