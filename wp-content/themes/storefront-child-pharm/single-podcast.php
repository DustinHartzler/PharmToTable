<!DOCTYPE html>
<html lang="en">

<head>
	<!-- ========== Meta Tags ========== -->
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="PharmtoTable - Your Journey To Wellness Through Natural Methods">

	<!-- LogRocket -->
	<script src="https://cdn.lr-ingest.io/LogRocket.min.js" crossorigin="anonymous"></script>
	<script>
		window.LogRocket && window.LogRocket.init('dgbkxm/pharmtotable');
	</script>

	<!-- ========== Page Title ========== -->
	<title>PharmToTable | <?php wp_title(''); ?>
	</title>

	<!-- Facebook Pixel Code -->
	<script>
		! function(f, b, e, v, n, t, s) {
			if (f.fbq) return;
			n = f.fbq = function() {
				n.callMethod ?
					n.callMethod.apply(n, arguments) : n.queue.push(arguments)
			};
			if (!f._fbq) f._fbq = n;
			n.push = n;
			n.loaded = !0;
			n.version = '2.0';
			n.queue = [];
			t = b.createElement(e);
			t.async = !0;
			t.src = v;
			s = b.getElementsByTagName(e)[0];
			s.parentNode.insertBefore(t, s)
		}(window, document, 'script',
			'https://connect.facebook.net/en_US/fbevents.js');
		fbq('init', '3168022206626913');
		fbq('track', 'PageView');
	</script>
	<noscript><img height="1" width="1" style="display:none"
			  src="https://www.facebook.com/tr?id=432268807399441&ev=PageView&noscript=1" /></noscript>
	<!-- End Facebook Pixel Code -->

	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-98971040-1"></script>
	<script>
		window.dataLayer = window.dataLayer || [];

		function gtag() {
			dataLayer.push(arguments);
		}
		gtag('js', new Date());

		gtag('config', 'UA-98971040-1');
	</script>
	<!-- End Google Analytics -->

	<!-- ========== Start Stylesheet ========== -->
	<link
		href="<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/assets/css/bootstrap.min.css"
		rel="stylesheet" />
	<link
		href="<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/assets/css/font-awesome.min.css"
		rel="stylesheet" />
	<link
		href="<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/assets/css/flaticon-set.css"
		rel="stylesheet" />
	<link
		href="<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/assets/css/magnific-popup.css"
		rel="stylesheet" />
	<link
		href="<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/assets/css/owl.carousel.min.css"
		rel="stylesheet" />
	<link
		href="<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/assets/css/owl.theme.default.min.css"
		rel="stylesheet" />
	<link
		href="<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/assets/css/animate.css"
		rel="stylesheet" />
	<link
		href="<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/assets/css/bootsnav.css"
		rel="stylesheet" />
	<link
		href="<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/style.css"
		rel="stylesheet">
	<link
		href="<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/style2.css"
		rel="stylesheet">
	<link
		href="<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/assets/css/responsive.css"
		rel="stylesheet" />
	<!-- ========== End Stylesheet ========== -->

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
      <script src="assets/js/html5/html5shiv.min.js"></script>
      <script src="assets/js/html5/respond.min.js"></script>
    <![endif]-->

	<!-- ========== Google Fonts ========== -->
	<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Poppins:300,500,600,700,800" rel="stylesheet">

</head>

<body>

	<!-- Preloader Start -->
	<div class="se-pre-con"></div>
	<!-- Preloader Ends -->

	<!-- Header
    ============================================= -->
	<header id="home">

		<!-- Start Navigation -->
		<nav class="navbar navbar-default attr-border navbar-sticky bootsnav">

			<!-- Start Top Search -->
			<div class="container">
				<div class="row">
					<div class="top-search">
						<div class="input-group">
							<form action="#">
								<?php get_search_form(); ?>
								<input type="text" name="text" class="form-control" placeholder="Search">
								<button type="submit">
									<i class="fas fa-search"></i>
								</button>
							</form>
						</div>
					</div>
				</div>
			</div>
			<!-- End Top Search -->

			<div class="container">

				<!-- Start Atribute Navigation -->
				<div class="attr-nav">
					<ul>
						<li class="quote-btn"><a
								href="<?php bloginfo('url'); ?>/provider">Make
								Appointment</a></li>
					</ul>
				</div>
				<!-- End Atribute Navigation -->

				<!-- Start Header Navigation -->
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-menu">
						<i class="fa fa-bars"></i>
					</button>
					<a class="navbar-brand"
						href="<?php bloginfo('url'); ?>">
						<img src="<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/assets/img/logo-pharmtotable.png"
							class="logo" alt="Logo">
					</a>
				</div>
				<!-- End Header Navigation -->

				<!-- Collect the nav links, forms, and other content for toggling -->
				<div class="collapse navbar-collapse" id="navbar-menu">

					<?php wp_nav_menu(array(
                            'sort_column' => 'menu_order',
                            'theme_location' => 'main',
                            'container' => 'ul',
                            'menu_class' => 'nav navbar-nav navbar-right',
                            'items_wrap'      => '<ul class="%2$s">%3$s</ul>',
                            'walker' => new My_Walker_Nav_Menu(),

                            ));
                            ?>
				</div><!-- /.navbar-collapse -->
			</div>

		</nav>
		<!-- End Navigation -->
		<?php wp_head(); ?>
	</header>
	<!-- End Header -->


	<!-- Start Breadcrumb
============================================= -->
	<div class="breadcrumb-area shadow dark bg-fixed text-light"
		style="background-image: url(assets/img/2440x1578.png);">
		<div class="container">
			<div class="row">
				<div class="col-md-6">
					<h1><?php the_title(); ?></h1>
				</div>
				<div class="col-md-6 text-right">
					<ul class="breadcrumb">
						<li><a href="https://pharmtotable.life"><i class="fas fa-home"></i> Home</a></li>
						<li class="active">Table Talk Podcast</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<!-- End Breadcrumb -->


	<!-- Start Blog
    ============================================= -->
	<div class="blog-area single full-blog right-sidebar default-padding">
		<div class="container">
			<div class="row">
				<div class="blog-items">
					<div class="blog-content col-md-8">
						<?php while ( have_posts() ) : the_post(); ?>
						<div class="item">

							<!-- Start Post Thumb -->
							<div>
								<?php the_post_thumbnail( array( 600, 600 ) ); ?>
							</div>
							<!-- Stop Post Thumb -->
							<div class="info">
								<?php the_content(); ?>
								<!-- Start Post Pagination -->
								<div class="post-pagi-area">
									<?php
                                    $prev_post = get_adjacent_post(false, '', true);
                                    $next_post = get_adjacent_post(false, '', false);
if (!empty($prev_post)) {
    echo '<a href="' . get_permalink($prev_post->ID) . '" title="' . $prev_post->post_title . '"><i class="fas fa-angle-double-left"></i>Previous Post</a>';
}

if (!empty($next_post)) {
    echo '<a href="' . get_permalink($next_post->ID) . '" title="' . $next_post->post_title . '">Next Podcast<i class="fas fa-angle-double-right"></i></a>';
} else {
    echo '<a href="#">&nbsp;</a>';
}

      ?>



								</div>
								<!-- End Post Pagination -->

								<!-- Start Post Tag s-->
								<div class="post-tags share">

									<div class="tags">
										<span>Categories: </span>
										<?php the_category(); ?>
										<span>Tags: </span><br>
										<?php the_tags('', ''); ?>
									</div>
								</div>
								<!-- End Post Tags -->

								<!-- Start Author Post -->
								<div class="author-bio">
									<div class="avatar">
										<?php echo get_avatar(get_the_author_meta('ID'), 200); ?>
									</div>
									<div class="content">
										<p><?php the_author_meta('description'); ?>
										</p>
										<h4><?php the_author_posts_link(); ?>
										</h4>
									</div>
								</div>
								<!-- End Author Post -->

								<?php endwhile; ?>
							</div>
						</div>
					</div>

					<!-- Start Sidebar -->
					<?php get_sidebar(); ?>
					<!-- End Start Sidebar -->
				</div>
			</div>
		</div>
	</div>
	<!-- End Blog -->

	<!-- Start Newsletter
============================================= -->
	<div class="newsletter-area default-padding shadow dark bg-fixed text-center text-light"
		style="background-image: url(assets/img/2440x1578.png);">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<h4>Subscribe For Updates</h4>
					<h2>Let’s Find A Pharmacist Near You.</h2>
					<form action="#">
						<div class="input-group stylish-input-group">
							<button type="submit">
								<a href="https://app.monstercampaigns.com/c/g9aoffuqh8sc1kutmuzw/" target="_blank">
									Sign Up for Email Updates <i class="fa fa-paper-plane"></i>
								</a>
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<!-- End Newsletter -->

	<!-- Start Footer
    ============================================= -->
	<footer>
		<div class="container">
			<div class="row">

				<div class="f-items default-padding">
					<!-- Single Item -->
					<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar("child-footer-1")) : ?>
					<?php endif;?>
					<!-- End Single Item -->
					<!-- Single Item -->
					<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar("child-footer-2")) : ?>
					<?php endif;?>
					<!-- End Single Item -->
					<!-- Single Item -->
					<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar("child-footer-3")) : ?>
					<?php endif;?>
					<!-- End Single Item -->
				</div>
			</div>
		</div>
		<!-- Start Footer Bottom -->
		<div class="footer-bottom bg-dark text-light">
			<div class="container">
				<div class="row">
					<div class="col-md-6">
						<p>&copy; Copyright 2020. All Rights Reserved by <a
								href="https://pharmtotable.life">PharmToTable</a></p>
					</div>
					<div class="col-md-6 text-right link">
						<ul>
							<li>
								<a href="#">Terms of user</a>
							</li>
							<li>
								<a href="#">License</a>
							</li>
							<li>
								<a href="#">Support</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<!-- End Footer Bottom -->
	</footer>
	<!-- End Footer -->

	<!-- jQuery Frameworks
    ============================================= -->
	<script
		src="<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/assets/js/jquery-1.12.4.min.js">
	</script>
	<script
		src="<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/assets/js/bootstrap.min.js">
	</script>
	<script
		src="<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/assets/js/equal-height.min.js">
	</script>
	<script
		src="<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/assets/js/jquery.appear.js">
	</script>
	<script
		src="<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/assets/js/jquery.easing.min.js">
	</script>
	<script
		src="<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/assets/js/jquery.magnific-popup.min.js">
	</script>
	<script
		src="<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/assets/js/modernizr.custom.13711.js">
	</script>
	<script
		src="<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/assets/js/owl.carousel.min.js">
	</script>
	<script
		src="<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/assets/js/wow.min.js">
	</script>
	<script
		src="<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/assets/js/isotope.pkgd.min.js">
	</script>
	<script
		src="<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/assets/js/imagesloaded.pkgd.min.js">
	</script>
	<script
		src="<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/assets/js/count-to.js">
	</script>
	<script
		src="<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/assets/js/jquery.nice-select.min.js">
	</script>
	<script
		src="<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/assets/js/bootsnav.js">
	</script>
	<script
		src="<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/assets/js/main.js">
	</script>
	<?php wp_footer(); ?>
	<!-- This site is converting visitors into subscribers and customers with OptinMonster - https://optinmonster.com -->
	<script type="text/javascript" src="https://a.omappapi.com/app/js/api.min.js" data-account="44742" data-user="39038"
		async></script>
	<!-- / https://optinmonster.com -->
</body>

</html>
