<?php
$options = thrive_get_options_for_post( get_the_ID() );
$enable_fb_comments = thrive_get_theme_options( "enable_fb_comments" );
$fb_app_id = thrive_get_theme_options( "fb_app_id" );
$logo_pos_class = ( $options['logo_position'] != "top" ) ? "side_logo wrp" : "center_logo";
$has_phone = ( ! empty( $options['header_phone_no'] ) || ! empty( $options['header_phone_text'] ) ) ? "has_phone" : "";
$float_menu_attr = "";
if ( $options['navigation_type'] == "float" || $options['navigation_type'] == "scroll" ) {
	$float_menu_attr = ( $options['navigation_type'] == "float" ) ? " data-float='float-fixed'" : " data-float='float-scroll'";
}
?><!DOCTYPE html>
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

			<!-- Web Fonts  -->
		<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800%7CShadows+Into+Light" rel="stylesheet" type="text/css">
		<link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,200italic,200,300,300italic,400italic,600,600italic,700,700italic,900,900italic|Raleway:400,700,600,500&amp;subset=latin,latin-ext' rel='stylesheet' type='text/css' />

	<?php tha_head_bottom(); ?>

</head>
<body <?php body_class() ?>>

<?php if ( isset( $options['analytics_body_script_top'] ) && ! empty( $options['analytics_body_script_top'] ) ): ?>
	<?php echo $options['analytics_body_script_top']; ?>
<?php endif; ?>
<?php if ( is_singular() && $enable_fb_comments != "off" && ! empty( $fb_app_id ) ) : ?>
	<?php include get_template_directory() . '/partials/fb-script.php' ?>
<?php endif; ?>
<?php tha_body_top(); ?>

<?php tha_header_before(); ?>
<div class="flex-cnt">
	<div id="floating_menu" <?php echo $float_menu_attr; ?>>
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
				$header_class   = 'hbp';
				$header_pattern = get_theme_mod( 'thrivetheme_header_pattern' );
				if ( $header_pattern != 'anopattern' && strpos( $header_pattern, '#' ) === false ) {
					$header_style = 'background-image:url(' . get_bloginfo( 'template_url' ) . '/images/patterns/' . $header_pattern . '.png);';
				}
				break;
			case '#customize-control-thrivetheme_header_background_image, #customize-control-thrivetheme_header_image_type, #customize-control-thrivetheme_header_image_height':
				$header_image_type = get_theme_mod( 'thrivetheme_header_image_type' ) ? get_theme_mod( 'thrivetheme_header_image_type' ) : 'full';
				switch ( $header_image_type ) {
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
			<div class="<?php echo $logo_pos_class; ?> <?php echo $has_phone; ?>" id="head_wrp">
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
									   href="<?php echo home_url( '/' ); ?>"><?php echo $options['logo_text']; ?></a>
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
									<a href="<?php echo home_url( '/' ); ?>" class="lg">
										<img src="<?php echo $options['logo']; ?>"
											 alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>"/>
									</a>
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
							<?php endif; ?>

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
											<span
													class="mphr"><?php echo $options['header_phone_text_mobile']; ?></span>
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
								<?php if ( has_nav_menu( "primary" ) ): ?>
									<?php locate_template( '/inc/templates/woocommerce-navbar-mini-cart.php', true, true ); ?>
									<?php wp_nav_menu( array(
										'container'       => 'nav',
										'depth'           => 0,
										'theme_location'  => 'primary',
										'container_class' => "right",
										'menu_class'      => 'menu',
										'walker'          => new thrive_custom_menu_walker()
									) ); ?>
								<?php else: ?>
									<div class="dfm">
										<?php _e( "Assign a 'primary' menu", 'thrive' ); ?>
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
	</div>
	<?php tha_header_after(); ?>

	<?php
	if ( ( is_archive() || is_search() ) && _thrive_check_focus_area_for_pages( "archive", "top" ) ) {
		thrive_render_top_focus_area( "top", "archive" );
	} elseif ( is_home() && _thrive_check_focus_area_for_pages( "blog", "top" ) ) {
		thrive_render_top_focus_area( "top", "blog" );
	} elseif ( thrive_check_top_focus_area() ) {
		thrive_render_top_focus_area();
	}
	?>

	<?php
	if ( is_single() || is_page() ):
		$post_format = get_post_format();
		?>
		<?php if ( $post_format == "audio" ): ?>
		<?php get_template_part( 'header', 'audio' ); ?>
	<?php elseif ( $post_format == "image" ): ?>
		<?php get_template_part( 'header', 'image' ); ?>
	<?php elseif ( $post_format == "gallery" ): ?>
		<?php get_template_part( 'header', 'gallery' ); ?>
	<?php elseif ( $post_format == "quote" ): ?>
		<?php get_template_part( 'header', 'quote' ); ?>
	<?php elseif ( $post_format == "video" ): ?>
		<?php get_template_part( 'header', 'video' ); ?>
	<?php else: ?>
		<?php get_template_part( 'header', 'title' ); ?>
	<?php endif; ?>
	<?php endif; ?>

	<?php if ( is_archive() && ! is_category() && ! is_author() && ! is_search() && ! is_tag() ): ?>
		<div class="hru tcbk">
			<div class="hrui">
				<div class="wrp">
					<h1>
						<?php if ( is_day() ) : ?>
							<?php printf( __( 'Daily Archives: %s', 'thrive' ), '' . get_the_date() . '' ); ?>
						<?php elseif ( is_month() ) : ?>
							<?php printf( __( 'Monthly Archives: %s', 'thrive' ), '' . get_the_date( _x( 'F Y', 'monthly archives date format', 'thrive' ) ) . '' ); ?>
						<?php elseif ( is_year() ) : ?>
							<?php printf( __( 'Yearly Archives: %s', 'thrive' ), '' . get_the_date( _x( 'Y', 'yearly archives date format', 'thrive' ) ) . '' ); ?>
						<?php else : ?>
							<?php if ( function_exists( 'woocommerce_page_title' ) ) : ?>
								<?php woocommerce_page_title(); ?>
							<?php else : ?>
								<?php $archive_name = post_type_archive_title(); ?>
								<?php echo empty( $archive_name ) ? __( 'Blog Archives', 'thrive' ) : $archive_name; ?>
							<?php endif; ?>
						<?php endif; ?>
					</h1>
				</div>
			</div>
		</div>
	<?php endif; ?>

	<?php if ( is_author() ): ?>
		<div class="hru tcbk">
			<div class="hrui aa">
				<div class="hra">
					<div class="hai">
						<div class="heic"
							 style="background-image: url('<?php echo _thrive_get_avatar_url( get_avatar( get_the_author_meta( 'user_email' ), 230 ) ); ?>')"></div>
					</div>
					<div class="wrp">
						<h1>All Posts by <a href=""><?php echo get_the_author(); ?></a></h1>
					</div>
				</div>
			</div>
		</div>
	<?php endif; ?>

	<?php if ( is_category() ): ?>
		<div class="hru tcbk">
			<div class="hrui">
				<div class="wrp">
					<h1><?php printf( __( 'Category Archives for "%s"', 'thrive' ), '' . single_cat_title( '', false ) . '' ); ?></h1>
				</div>
			</div>
		</div>
	<?php endif; ?>

	<?php if ( is_search() ): ?>
		<div class="hru tcbk">
			<div class="hrui">
				<div class="wrp">
					<h1><?php printf( __( 'Search Results for: %s', 'thrive' ), '' . get_search_query() . '' ); ?></h1>
				</div>
			</div>
		</div>
	<?php endif; ?>

	<?php if ( is_tag() ): ?>
		<div class="hru tcbk">
			<div class="hrui">
				<div class="wrp">
					<h1><?php printf( __( 'Tag Archives for " %s " ', 'thrive' ), '' . single_tag_title( '', false ) . '' ); ?></h1>
				</div>
			</div>
		</div>
	<?php endif; ?>

	<?php if ( is_404() ): ?>
		<div class="hru tcbk">
			<div class="hrui">
				<div class="wrp">
					<h1 class="big">404</h1>
				</div>
			</div>
		</div>
	<?php endif; ?>

	<?php tha_content_before(); ?>

	<?php get_template_part( 'breadcrumbs' ); ?>

	<?php tha_content_top(); ?>

	<div class="wrp cnt"> <!-- Start the wrapper div -->

