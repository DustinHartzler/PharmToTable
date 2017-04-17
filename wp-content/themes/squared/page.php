<?php
$options = thrive_get_options_for_post( get_the_ID() );

$main_content_class = ( $options['sidebar_alignement'] == "right" || $options['sidebar_alignement'] == "left" ) ? $options['sidebar_alignement'] : "";

if ( $options['sidebar_alignement'] == "right" ) {
	$main_content_class = "left";
} elseif ( $options['sidebar_alignement'] == "left" ) {
	$main_content_class = "right";
} else {
	$main_content_class = "fullWidth";
}
$sidebar_is_active = _thrive_is_active_sidebar( $options );

if ( ! $sidebar_is_active ) {
	$main_content_class = "fullWidth";
}

get_header();
?>
<?php if ( $options['sidebar_alignement'] == "left" && $sidebar_is_active ): ?>
	<?php get_sidebar(); ?>
<?php endif; ?>
<?php if ( $sidebar_is_active ): ?>
	<div class="bSeCont">
<?php endif; ?>
	<section class="bSe <?php echo $main_content_class; ?>">

		<?php if ( have_posts() ): ?>

			<?php while ( have_posts() ): ?>

				<?php the_post(); ?>

				<?php get_template_part( 'content', 'single' ); ?>

				<?php if ( ! post_password_required() && $options['comments_on_pages'] != 0 ) : ?>
					<?php comments_template( '', true ); ?>
				<?php elseif ( ( ! comments_open() || post_password_required() ) && get_comments_number() > 0 ): ?>
					<?php comments_template( '/comments-disabled.php' ); ?>
				<?php endif; ?>

			<?php endwhile; ?>

		<?php else: ?>

		<?php endif ?>

	</section>
<?php if ( $sidebar_is_active ): ?>
	</div>
<?php endif; ?>

<?php if ( $options['sidebar_alignement'] == "right" && $sidebar_is_active ): ?>
	<?php get_sidebar(); ?>
<?php endif; ?>
	<div class="clear"></div>
<?php get_footer(); ?>