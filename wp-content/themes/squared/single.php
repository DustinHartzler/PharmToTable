<?php
$options = thrive_get_theme_options();

$main_content_class = ( $options['sidebar_alignement'] == "right" ) ? "left" : "right";
$sidebar_is_active  = is_active_sidebar( 'sidebar-1' );
if ( ! $sidebar_is_active ) {
	$main_content_class = "fullWidth";
}
?>

<?php get_header(); ?>

<?php if ( $options['sidebar_alignement'] == "left" && $sidebar_is_active ): ?>
	<?php get_sidebar(); ?>
<?php endif; ?>

<?php if ( $sidebar_is_active ): ?>
<div class="bSeCont">
	<?php endif; ?>
	<section class="bSe <?php echo $main_content_class; ?>">

		<?php if ( have_posts() ): ?>
			<?php
			while ( have_posts() ):
				?>
				<?php the_post(); ?>
				<?php get_template_part( 'content', 'single' ); ?>

				<?php if ( isset( $options['bottom_about_author'] ) && $options['bottom_about_author'] == 1 ): ?>
				<?php get_template_part( 'authorbox' ); ?>
			<?php endif; ?>

				<?php if ( ! post_password_required() ) : ?>
				<?php comments_template( '', true ); ?>
			<?php elseif ( ( ! comments_open() ) && get_comments_number() > 0 ): ?>
				<?php comments_template( '/comments-disabled.php' ); ?>
			<?php endif; ?>


				<?php
				if ( isset( $options['bottom_previous_next'] ) && $options['bottom_previous_next'] == 1 && get_permalink( get_adjacent_post( false, '', false ) ) != "" && get_permalink( get_adjacent_post( false, '', true ) ) != "" ):
					?>
					<div class="spr"></div>
					<div class="awr ctr pgn clearfix">
						<?php $prev_post = get_adjacent_post( false, '', true ); ?>
						<?php if ( $prev_post ) : ?>
							<a class="page-numbers nxt"
							   href='<?php echo get_permalink( get_adjacent_post( false, '', true ) ); ?>'>&larr;<?php _e( "Previous post", 'thrive' ) ?> </a>
						<?php endif; ?>
						<?php $next_post = get_adjacent_post( false, '', false ); ?>
						<?php if ( $next_post ) : ?>
							<a class="page-numbers prv"
							   href='<?php echo get_permalink( get_adjacent_post( false, '', false ) ); ?>'><?php _e( "Next post", 'thrive' ) ?>&rarr;</a>
						<?php endif; ?>
					</div>

				<?php endif; ?>

				<?php
			endwhile;
			?>
		<?php else: ?>
			<!--No contents-->
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
