<?php
/**
 * Template Name: Page for Table Talk Membership
 */

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<!-- ========== Meta Tags ========== -->
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="PharmtoTable - Your Journey To Wellness Through Natural Methods">
	<meta property="og:title" content="The Stress Solution" />
	<meta property="og:image"
		content="<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/assets/img/stress-free.jpg" />

	<!-- ========== Page Title ========== -->
	<title>PharmToTable | <?php wp_title(''); ?>
	</title>

	<!-- Google Tag Manager -->
	<script>
		(function(w, d, s, l, i) {
			w[l] = w[l] || [];
			w[l].push({
				'gtm.start': new Date().getTime(),
				event: 'gtm.js'
			});
			var f = d.getElementsByTagName(s)[0],
				j = d.createElement(s),
				dl = l != 'dataLayer' ? '&l=' + l : '';
			j.async = true;
			j.src =
				'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
			f.parentNode.insertBefore(j, f);
		})(window, document, 'script', 'dataLayer', 'GTM-WMQK3FB');
	</script>
	<!-- End Google Tag Manager -->

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

	<!-- ========== Favicon Icon ========== -->
	<link rel="shortcut icon" href="assets/img/favicon.png" type="image/x-icon">

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
		href="<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/stress.css"
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
	<?php wp_head(); ?>
</head>

<body>
	<!-- Google Tag Manager (noscript) -->
	<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WMQK3FB" height="0" width="0"
			style="display:none;visibility:hidden"></iframe></noscript>
	<!-- End Google Tag Manager (noscript) -->

	<!-- Preloader Start -->
	<div class="se-pre-con"></div>
	<!-- Preloader Ends -->

	<!-- Header
    ============================================= -->
	<header id="home">

		<!-- Start Navigation -->
		<nav class="navbar navbar-default attr-border bootsnav">

			<!-- Start Top Search -->
			<div class="container">
				<div class="row">
					<div class="top-search">
						<div class="input-group">
							<form action="#">
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
						<li class="quote-btn"><a href="#checkout">Buy Now</a></li>
					</ul>
				</div>
				<!-- End Atribute Navigation -->

				<!-- Start Header Navigation -->
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-menu">
						<i class="fa fa-bars"></i>
					</button>
					<a class="navbar-brand" href="index.html">
						<h2 class="header">The <span>Table Talk</span> Membership</h2>
					</a>
				</div>
				<!-- End Header Navigation -->
			</div>

		</nav>
		<!-- End Navigation -->

	</header>
	<!-- End Header -->

	<!-- Start Banner
	============================================= -->
	<div class="banner-area heading-exchange text-dark">
		<div id="bootcarousel" class="carousel inc-top-heading slide carousel-fade animate_text" data-ride="carousel">
			<!-- Wrapper for slides -->
			<div class="carousel-inner carousel-zoom">
				<div class="item active">
					<div class="slider-thumb bg-cover"
						style="background-image: url(<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/assets/img/stress-free.jpg);">
					</div>
					<div class="box-table">
						<div class="box-cell">
							<div class="container">
								<div class="row">
									<div class="col-md-8">
										<div class="content">
											<h1 data-animation="animated fadeInUp">The <span
													style="font-family: 'silver_south_scriptregular'; color: #71CABC">Table
													Talk</span> Membership</h1>
											<h2 data-animation="animated fadeInDown" style="color:black;">Getting
												answers to your health questions should not be hard.</h2>
											<a data-animation="animated slideInUp" class="btn btn-theme effect btn-md"
												href="#checkout">Start Your Free Trial</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

			</div>
			<!-- End Wrapper for slides -->
		</div>
	</div>
	<!-- End Banner -->

	<!-- Start Why Chose Us
    ============================================= -->
	<div class="chose-us-area bg-gray item-half">
		<div class="container-full">
			<div class="row">
				<div class="col-md-6 thumb bg-cover"
					style="background-image: url(<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/assets/img/stressed.jpeg)">
				</div>
				<div class="col-md-6 info">
					<div class="heading">
						<h3>Get the answers you need without waiting for an appointment.</h3>
					</div>
					<p>When it comes to health and nutrition, there is way too much confusing information on the
						internet. What's the truth about supplements? Should you fast? What's the deal with toxins?</p>
					<p>It seems like no one one can agree, and you're left on your own to decide what information you
						can trust. Often, you have to wait for a doctor's visit just to get your questions answered.</p>
					<h4>The Solution. . .The Table Talk Monthly Membership</h4>
					<br>
					<a class="btn btn-theme effect btn-md" href="#checkout">I'm Ready to Take Action</a>



				</div>
			</div>
		</div>
	</div>
	<!-- End Why Chose Us -->

	<!-- Start Departments
	============================================= -->
	<div id="departments" class="department-tabs default-padding chose-us-area">
		<div class="container">
			<div class="row">
				<div class="col-md-8 col-md-offset-2">
					<div class="site-heading text-center">
						<h2><span>What's Included</span></h2>
					</div>
				</div>
			</div>
			<div class="row" style="padding-bottom:40px">

				<div class="col-md-12 info">
					<div class="heading">
						<h3>What You Get When You Sign Up:</h3>
					</div>
					<div class="col-md-6 info">
						<ul>
							<li>
								<div class="info">
									<p>2 live Q&A sessions on the 2nd and 4th Sunday of the month</p>
								</div>
							</li>
							<li>
								<div class="info">
									<p>Bring all of your health related questions and get real answers from a
										PharmToTable Functional Medicine Pharmacist</p>
								</div>
							</li>
							<li>
								<div class="info">
									<p>Valuable information about relevant health topics</p>
								</div>
							</li>
						</ul>
					</div>
					<div class="col-md-6 info">
						<ul>
							<li>
								<div class="info">
									<p>Real, practical solutions to improving your health</p>
								</div>
							</li>
							<li>
								<div class="info">
									<p>Personalized information and tools to help you become empowered on your health
										journey</p>
								</div>
							</li>
						</ul>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12 tab-contents">
						<div class="row">
							<!-- Start Tab Content -->
							<div class="tab-content tab-content-info" style="text-align: center">
								<a class="btn btn-theme effect btn-md" href="#checkout">Sign Up Now!</a>
							</div>
							<!-- End Tab Content -->
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- End Departments -->

		<!-- Start Doctors
	============================================= -->
		<div id="doctors" class="doctor-area bg-gray default-padding bottom-less">
			<div class="container">
				<div class="row">
					<div class="col-md-8 col-md-offset-2">
						<div class="site-heading text-center">
							<h2>Meet Our <span>Pharmacists</span></h2>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="doctor-items text-center">
						<!-- Spacer -->
						<div class="col-md-2 col-sm-6 equal-height"></div>
						<!-- Single Pharmacist -->
						<div class="col-md-4 col-sm-6 equal-height">
							<div class="item">
								<div class="thumb">
									<img src="https://pharmtotable.life/wp-content/uploads/2020/06/sarahbachofner.jpg"
										alt="Dr. Sarah Bachofner">
									<div class="social">
										<ul>
											<li class="facebook">
												<a href="https://www.facebook.com/lindseyelmore/" target="_blank"><i
														class="fab fa-facebook-f"></i></a>
											</li>
											<li class="instagram">
												<a href="https://www.instagram.com/lindseyelmore/" target="_blank"><i
														class="fab fa-instagram"></i></a>
											</li>
											<li class="twitter">
												<a href="https://twitter.com/drlindseyelmore" target="_blank"><i
														class="fab fa-twitter"></i></a>
											</li>
											<li class="linkedin">
												<a href="https://www.linkedin.com/in/lindseyelmore/" target="_blank"><i
														class="fab fa-linkedin-in"></i></a>
											</li>
										</ul>
									</div>
								</div>
								<div class="info">
									<div class="cards">
										<div class="card"
											data-description="After a long battle with insomnia, Dr. Elmore started a lifelong study of natural medicine. She now combines a multidisciplinary approach including functional medicine, yoga, diet and exercise to help people heal themselves. ">
											<h4>Sarah Bachofner</h4>
											<h5>PharmD</h5>
											<button>View Bio →</button>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- End Single Pharmacist -->
						<!-- Single Pharmacist -->
						<div class="col-md-4 col-sm-6 equal-height">
							<div class="item">
								<div class="thumb">
									<img src="https://functionalmedicinece.com/wp-content/uploads/2021/01/vanessalesneski.png"
										alt="Thumb">
									<div class="social">
										<ul>
											<li class="facebook">
												<a href="https://www.facebook.com/BrookUFRx" target="_blank"><i
														class="fab fa-facebook-f"></i></a>
											</li>
											<li class="linkedin">
												<a href="https://www.linkedin.com/in/vanessa-lesneski-pharmd-bcps-cph-12a83296/"
													target="_blank"><i class="fab fa-linkedin-in"></i></a>
											</li>
										</ul>
									</div>
								</div>
								<div class="info">
									<div class="cards">
										<div class="card"
											data-description="Dr. Lesneski's functional medicine journey started when she began having acid reflux symptoms. After several years of prescription medications, the reflux symptoms became so severe she was evaluated for a medical device trial.  After an extensive and intrusive workup the research team said she did not have acid reflux.  She needed answers and conventional medicine had only ended in dead ends.  Her personal experience with functional medicine was so profound that she now teaches functional medicine to pharmacy students in addition to leading a pharmacy clinic within a functional medicine office.  ">
											<h4>Vanessa Lesneski</h4>
											<h5>PharmD, BCPS, CPh</h5>
											<button>View Bio →</button>

										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- End Single Item -->
						<!-- Spacer -->
						<div class="col-md-2 col-sm-6 equal-height"></div>
						<!-- Spacer -->
						<div class="col-md-2 col-sm-6 equal-height"></div>
						<!-- Single Item -->
						<div class="col-md-4 col-sm-6 equal-height">
							<div class="item">
								<div class="thumb">
									<img src="https://functionalmedicinece.com/wp-content/uploads/2020/05/LZakaria.jpeg"
										alt="Thumb">
									<div class="social">
										<ul>
											<li class="facebook">
												<a href="https://www.facebook.com/foodiefarmacist/" target="_blank"><i
														class="fab fa-facebook-f"></i></a>
											</li>
											<li class="instagram">
												<a href="https://www.instagram.com/foodiefarmacist/" target="_blank"><i
														class="fab fa-instagram"></i></a>
											</li>
											<li class="twitter">
												<a href="https://twitter.com/FoodieFarmacist" target="_blank"><i
														class="fab fa-twitter"></i></a>
											</li>
											<li class="linkedin">
												<a href="https://www.linkedin.com/in/lara-zakaria/" target="_blank"><i
														class="fab fa-linkedin-in"></i></a>
											</li>
										</ul>
									</div>
								</div>
								<div class="info">
									<div class="cards">
										<div class="card"
											data-description="Struggling with frequent episodes of hypoglycemia and acne at 28 led Dr Zakaria down the path of nutrition and Functional Medicine. She made the connection about how working long, stressful shifts as a pharmacist, eating a diet that was failing to optimize blood sugar balance, and acid reflux were all contributing to her symptoms. That experience and applying the concepts to improve her own health is what drove Dr Zakaria to get her masters in nutrition, become a certified nutrition specialist and certified Functional Medicine practitioner.">
											<h4>Lara Zakaria</h4>
											<h5>PharmD, MS CNS CDN IFMCP</h5>
											<button>View Bio →</button>

										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- End Single Item -->
						<!-- Single Item -->
						<div class="col-md-4 col-sm-6 equal-height">
							<div class="item">
								<div class="thumb">
									<img src="https://pharmtotable.life/wp-content/uploads/2020/06/sarahbachofner.jpg"
										alt="Thumb">
									<div class="social">
										<ul>
											<li class="facebook">
												<a href="http://www.facebook.com/drsarahbachofner" target="_blank"><i
														class="fab fa-facebook-f"></i></a>
											</li>
											<li class="instagram">
												<a href="https://www.instagram.com/drsarahbachofner/" target="_blank"><i
														class="fab fa-instagram"></i></a>
											</li>
										</ul>
									</div>
								</div>
								<div class="info">
									<div class="cards">
										<div class="card"
											data-description="Dr. Bachofner discovered functional medicine after five years of loss with no conventional explanation as to why on the journey to giving birth to two incredible blessings. Since then, she learned that she was significantly deficient in several key nutrients. She discovered the root cause was likely in part due to a few genetic mutations and bacterial imbalance and inflammation of the gut. The stress of these five long years after the stress of pharmacy school was a huge contributor to it all. She has learned how to manage this stress and has made it her mission to help others struggling with health issues due to stress as a member of the PharmToTable Team.">

											<h4>Sarah Bachofner</h4>
											<h5>PharmD</h5>
											<button>View Bio →</button>
											<div class="modal-outer">
												<div class="modal-inner"></div>
											</div>
										</div>
									</div>

								</div>
							</div>
						</div>
						<!-- End Single Item -->

					</div>
				</div>
			</div>
		</div>
		<!-- End Doctors -->
		<!-- Start Testimonials
	============================================= -->
		<div class="testimonials-area carousel-shadow default-padding">
			<div class="container">
				<div class="row">
					<div class="col-md-8 col-md-offset-2">
						<div class="site-heading text-center">
							<h2><span>Testimonials</span></h2>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="testimonial-items testimonial-carousel owl-carousel owl-theme">
							<!-- Single Item -->
							<div class="item">
								<div class="content">
									<p>
										Dr. Zakaria is super knowledgeable about using a functional medicine approach to
										achieve optimal health and wellness. <br><br><em>She has a gift for making
											science easy to understand which is great when talking about stress and
											hormones.</em> <br><br>The Stress Solution is sure to help so many people
										and couldn’t have come at a more perfect time!
									</p>
								</div>
								<div class="provider">
									<div class="thumb">
										<img width="150"
											src="https://pharmtotable.life/wp-content/uploads/2020/06/meganmorrison.jpg"
											alt="Testimonial-Image">
									</div>
									<div class="info">
										<h4>Dr. Megan Morrison</h4>
										<h5>Functional Medicine Pharmacist</h5>
									</div>
								</div>
							</div>
							<!-- End Single Item -->
							<!-- Single Item -->
							<div class="item">
								<div class="content">
									<p>
										Stress plays such a <strong>HUGE</strong> role in health and <strong>I CANNOT
											WAIT</strong> to hear what these ladies have to say about it! <br><br><em>I
											am in awe of their knowledge and passion every time I hear them
											speak.</em><br><br> Combining their Functional Medicine knowledge with
										serious stress reduction techniques is sure to get anyone going in the right
										direction on their health journey.
									</p>
								</div>
								<div class="provider">
									<div class="thumb">
										<img src="https://pharmtotable.life/wp-content/uploads/2020/06/nicolegram.png"
											alt="Testimonial-Image">
									</div>
									<div class="info">
										<h4>Dr. Nicole Grams</h4>
										<h5>Functional Medicine Pharmacist and Anxiety Coach</h5>
									</div>
								</div>
							</div>
							<!-- End Single Item -->
						</div>
					</div>
				</div>
			</div>
			<!-- End Testimonials -->
		</div>
	</div>

	<!-- Start About
============================================= -->
	<div id="about" class="about-area bg-gray default-padding">
		<div class="container">
			<div class="row">
				<div class="col-md-8 col-md-offset-2">
					<div class="site-heading text-center">
						<h2 id="checkout"><span>Checkout</span></h2>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="about-items">
					<div class="col-md-12">
						<?php
                        if (have_posts()) : while (have_posts()) : the_post();
                                the_content();
                        endwhile; endif;
                        ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	</div>
	<!-- End About -->
	<?php
the_content("here");
?>

	<?php get_footer(); ?>
	<script
		src="<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/assets/js/click-outside.js">
	</script>
</body>

</html>
