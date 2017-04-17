<?php
$options            = thrive_get_options_for_post( get_the_ID(), array( 'apprentice' => 1 ) );
$enable_fb_comments = thrive_get_theme_options( "enable_fb_comments" );
$fb_app_id          = thrive_get_theme_options( "fb_app_id" );
$logo_pos_class     = ( $options['logo_position'] != "top" ) ? "side_logo" : "center_logo";
$has_phone          = ( ! empty( $options['header_phone_no'] ) || ! empty( $options['header_phone_text'] ) ) ? "has_phone" : "";
$float_menu_attr    = "";
if ( $options['navigation_type'] == "float" || $options['navigation_type'] == "scroll" ) {
	$float_menu_attr = ( $options['navigation_type'] == "float" ) ? " data-float='float-fixed'" : " data-float='float-scroll'";
}
$post_type = get_post_type();

$featured_image = null;
if ( has_post_thumbnail( get_the_ID() ) ) {
	$featured_image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), "full" );
}

?>
<!DOCTYPE html>
<?php tha_html_before(); ?>
<html <?php language_attributes(); ?>>
<head>
	<?php tha_head_top(); ?>
	<title>
		<?php wp_title( '' ); ?>
	</title>
	<!--[if lt IE 9]>
	<script src="<?php echo get_template_directory_uri() ?>/js/html5/dist/html5shiv.js"></script>
	<script src="//css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
	<![endif]-->
	<!--[if IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri() ?>/css/ie8.css"/>
	<![endif]-->
	<!--[if IE 7]>
	<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri() ?>/css/ie7.css"/>
	<![endif]-->
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<?php if ( $options['favicon'] && $options['favicon'] != "" ): ?>
		<link rel="shortcut icon" href="<?php echo $options['favicon']; ?>"/>
	<?php endif; ?>

	<?php if ( isset( $options['analytics_header_script'] ) && $options['analytics_header_script'] != "" ): ?>
		<?php echo $options['analytics_header_script']; ?>
	<?php endif; ?>

	<?php thrive_enqueue_head_fonts(); ?>
	<?php wp_head(); ?>
	<?php if ( isset( $options['custom_css'] ) && $options['custom_css'] != "" ): ?>
		<style type="text/css"><?php echo $options['custom_css']; ?></style>
	<?php endif; ?>

	<?php tha_head_bottom(); ?>
</head>
<body <?php body_class() ?>>
<?php if ( is_single() && $enable_fb_comments != "off" && ! empty( $fb_app_id ) ) : //REFACTORIZE THIS?>
	<?php include get_template_directory() . '/partials/fb-script.php' ?>
<?php endif; ?>
<?php tha_body_top(); ?>
<div id="floating_menu" <?php echo $float_menu_attr; ?>>
	<?php tha_header_before(); ?>
	<?php
	$header_type  = get_theme_mod( 'thrivetheme_theme_background' );
	$header_class = '';
	$header_style = '';
	switch ( $header_type ) {
		case 'default-header':
			$header_class = '';
			$header_style = '';
			break;
		case '#customize-control-thrivetheme_background_value':
			$header_class = 'hbc';
			$header_style = 'background-image: none; background-color:' . get_theme_mod( 'thrivetheme_background_value' );
			break;
		case '#customize-control-thrivetheme_header_pattern':
			$header_class = 'hbp';
			if ( get_theme_mod( 'thrivetheme_header_pattern' ) != 'anopattern' ) {
				$header_style = 'background-image:url(' . get_bloginfo( 'template_url' ) . '/images/patterns/' . get_theme_mod( 'thrivetheme_header_pattern' ) . '.png);';
			}
			break;
		case '#customize-control-thrivetheme_header_background_image, #customize-control-thrivetheme_header_image_type, #customize-control-thrivetheme_header_image_height':
			switch ( get_theme_mod( 'thrivetheme_header_image_type' ) ) {
				case 'full':
					$header_class = 'hif';
					$header_style = 'background-image:url(' . get_theme_mod( 'thrivetheme_header_background_image' ) . '); height:' . get_theme_mod( 'thrivetheme_header_image_height' ) . 'px;';
					break;
				case 'centered':
					$header_class = 'hic';
					$header_style = 'background-image:url(' . get_theme_mod( 'thrivetheme_header_background_image' ) . ');';
					break;
			}
			break;
	}
	?>
	<header class="<?php echo $header_class; ?>" style="<?php echo $header_style; ?>">
		<?php tha_header_top(); ?>
		<?php if ( $header_class == "hic" ): ?>
			<img class="tt-dmy" src="<?php echo get_theme_mod( 'thrivetheme_header_background_image' ); ?>"/>
		<?php endif; ?>
		<div class="<?php echo $logo_pos_class; ?> <?php echo $has_phone; ?> wrp" id="head_wrp">
			<div class="h-i">
				<?php if ( $options['logo_position'] == "top" ): ?>
				<div class="wrp">
					<?php endif; ?>
					<?php
					$thrive_logo = false;
					if ( $options['logo_type'] == "text" ):
						if ( get_theme_mod( 'thrivetheme_header_logo' ) != 'hide' ):
							?>
							<div id="text_logo"
							     class="<?php if ( $options['logo_color'] == "default" ): ?><?php echo $options['color_scheme'] ?><?php else: ?><?php echo $options['logo_color'] ?><?php endif; ?> ">
								<a class="left"
								   href="<?php echo _thrive_appr_get_lessons_root_url(); ?>"><?php echo $options['logo_text']; ?></a>
							</div>
							<?php if ( $options['logo_position'] == "top" && $options['header_phone'] == 1 ): ?>
							<div class="phone">
								<a href="tel:<?php echo $options['header_phone_no']; ?>">
									<div class="phr">
										<span><?php echo $options['header_phone_text']; ?></span>
										<span class="apnr"><?php echo $options['header_phone_no']; ?></span>
									</div>
								</a>
							</div>
						<?php endif; ?>
							<div class="clear"></div>
						<?php endif; ?>
					<?php elseif ( $options['logo'] && $options['logo'] != "" ): $thrive_logo = true; ?>
						<?php if ( get_theme_mod( 'thrivetheme_header_logo' ) != 'hide' ): ?>
							<div id="logo" class="left">
							<a href="<?php echo _thrive_appr_get_lessons_root_url(); ?>" class="lg">
								<img src="<?php echo $options['logo']; ?>"
								     alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>"/>
							</a>

							<?php if ( $options['logo_position'] == "top" && $options['header_phone'] == 1 ): ?>
								<div class="phone">
									<a href="tel:<?php echo $options['header_phone_no']; ?>">
										<div class="phr">
											<span><?php echo $options['header_phone_text']; ?></span>
											<span class="apnr"><?php echo $options['header_phone_no']; ?></span>
										</div>
									</a>
								</div>
							<?php endif; ?>
						<?php endif; ?>
						</div>
					<?php endif; ?>

					<div class="hmn">
						<div class="awe rmn right">&#xf0c9;</div>
						<div class="clear"></div>
					</div>

					<?php if ( $options['logo_position'] == "top" ): ?>
				</div>
			<?php endif; ?>

				<?php if ( $options['logo_position'] == "top" ): ?>
				<div class="mhl right" id="nav_right">
					<div class="wrp">
						<?php if ( $options['header_phone'] == 1 ): ?>
							<div class="phone_mobile <?php echo $options['header_phone_btn_color'] ?>">
								<a href="tel:<?php echo $options['header_phone_no']; ?>">
									<div class="phr">
										<span class="mphr"><?php echo $options['header_phone_text_mobile']; ?></span>
										<span class="apnr"><?php echo $options['header_phone_no']; ?></span>
									</div>
								</a>
							</div>
						<?php endif; ?>
						<?php else: ?>
						<div class="mhl right" id="nav_right">
							<?php if ( $options['header_phone'] == 1 ): ?>
								<div class="phone_mobile <?php echo $options['header_phone_btn_color'] ?>">
									<a href="tel:<?php echo $options['header_phone_no']; ?>">
										<div class="phr">
											<span
												class="mphr"><?php echo $options['header_phone_text_mobile']; ?></span>
											<span class="apnr"><?php echo $options['header_phone_no']; ?></span>
										</div>
									</a>
								</div>
							<?php endif; ?>
							<?php endif; ?>
							<?php if ( has_nav_menu( "apprentice" ) ): ?>
								<?php wp_nav_menu( array(
									'container'       => 'nav',
									'depth'           => 0,
									'theme_location'  => 'apprentice',
									'container_class' => "right",
									'menu_class'      => 'menu',
									'walker'          => new thrive_custom_menu_walker()
								) ); ?>
							<?php else: ?>
								<div class="dfm">
									<?php _e( "Assign an 'apprentice' menu", 'thrive' ); ?>
								</div>
							<?php endif; ?>
							<?php if ( $options['logo_position'] == "top" ): ?>
						</div>
					</div>
					<?php else: ?>
				</div>
			<?php endif; ?>

				<?php if ( $options['logo_position'] != "top" && $options['header_phone'] == 1 ): ?>
					<div class="phone">
						<a href="tel:<?php echo $options['header_phone_no']; ?>">
							<div class="phr">
								<span><?php echo $options['header_phone_text']; ?></span>
								<span class="apnr"><?php echo $options['header_phone_no']; ?></span>
							</div>
						</a>
					</div>
				<?php endif; ?>

				<div class="clear"></div>
			</div>
		</div>
		<?php tha_header_bottom(); ?>
	</header>
	<?php tha_header_after(); ?>
</div>
<?php
if ( thrive_check_top_focus_area() ):
	thrive_render_top_focus_area();
endif;
?>

<?php
if ( is_tax() ):
	$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
	?>
	<div class="hru tcbk">
		<div class="hrui">
			<div class="wrp">
				<h1>
					<?php _e( 'Lessons for ', 'thrive' ); ?><?php echo $term->name; ?>
				</h1>
			</div>
		</div>
	</div>
<?php endif; ?>


<?php
$thrive_lesson_type = get_post_meta( get_the_ID(), '_thrive_meta_appr_lesson_type', true );

if ( is_single() && ! empty( $thrive_lesson_type ) && $thrive_lesson_type == "audio" ) {
	get_template_part( 'appr/header-audio' );
} elseif ( is_single() && ! empty( $thrive_lesson_type ) && $thrive_lesson_type == "video" ) {
	get_template_part( 'appr/header-video' );
} elseif ( ( is_single() && ! empty( $thrive_lesson_type ) && $thrive_lesson_type == "text" && $options['show_post_title'] != 0 ) || ( $post_type == TT_APPR_POST_TYPE_PAGE && $options['show_post_title'] != 0 ) ) {
	$thrive_bg_color = ( $options['appr_media_bg_color'] != "default" && ! empty( $options['appr_media_bg_color'] ) ) ?
		strtolower( $options['appr_media_bg_color'] ) : $options['color_scheme'];
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

			<div class="hrui">
				<div class="wrp">
					<?php if ( $options['show_post_title'] != 0 ): ?>
						<h1>
							<?php the_title(); ?>
						</h1>
					<?php endif; ?>
					<div class="hcc"
					     <?php if ( $options['meta_comment_count'] != 1 || get_comments_number() == 0 ): ?>style='display:none;'<?php endif; ?>>
						<a href="#comments">
							<?php echo get_comments_number(); ?>
							<?php echo ucfirst( _thrive_get_comments_label( get_comments_number() ) ); ?>
						</a>
					</div>
				</div>
			</div>
		</div>

	<?php else : ?>
		<div class="hru tcbk"
		     <?php if ( $thrive_bg_color ): ?>style="background-color: <?php echo $thrive_bg_color ?>;"<?php endif; ?>>
			<div class="hrui">
				<div class="wrp">
					<h1>
						<?php the_title(); ?>
					</h1>
				</div>
			</div>
		</div>
	<?php endif ?>
<?php }
?>

<?php get_template_part( 'appr/breadcrumbs' ); ?>

<?php tha_content_before(); ?>

<?php tha_content_top(); ?>
