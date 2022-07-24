<?php
/*
*Template Name: Home
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

    <!-- LogRocket -->
    <script src="https://cdn.lr-ingest.io/LogRocket.min.js" crossorigin="anonymous"></script>
    <script>window.LogRocket && window.LogRocket.init('dgbkxm/pharmtotable');</script>

    <!-- ========== Page Title ========== -->
    <title>PharmToTable | <?php wp_title(''); ?></title>

	<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-WMQK3FB');</script>
<!-- End Google Tag Manager -->

<!-- Facebook Pixel Code -->
    <script>
      !function(f,b,e,v,n,t,s)
      {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
      n.callMethod.apply(n,arguments):n.queue.push(arguments)};
      if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
      n.queue=[];t=b.createElement(e);t.async=!0;
      t.src=v;s=b.getElementsByTagName(e)[0];
      s.parentNode.insertBefore(t,s)}(window, document,'script',
      'https://connect.facebook.net/en_US/fbevents.js');
      fbq('init', '3168022206626913');
      fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
      src="https://www.facebook.com/tr?id=432268807399441&ev=PageView&noscript=1"
    /></noscript>
<!-- End Facebook Pixel Code -->

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-98971040-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-98971040-1');
</script>
<!-- End Google Analytics -->

    <!-- ========== Start Stylesheet ========== -->
    <link href="<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/assets/css/font-awesome.min.css" rel="stylesheet" />
    <link href="<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/assets/css/flaticon-set.css" rel="stylesheet" />
    <link href="<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/assets/css/magnific-popup.css" rel="stylesheet" />
    <link href="<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/assets/css/owl.carousel.min.css" rel="stylesheet" />
    <link href="<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/assets/css/owl.theme.default.min.css" rel="stylesheet" />
    <link href="<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/assets/css/animate.css" rel="stylesheet" />
    <link href="<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/assets/css/bootsnav.css" rel="stylesheet" />
    <link href="<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/style.css" rel="stylesheet">
    <link href="<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/style2.css" rel="stylesheet">
    <link href="<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/assets/css/responsive.css" rel="stylesheet" />
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
	<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WMQK3FB"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

    <!-- Preloader Start -->
    <div class="se-pre-con"></div>
    <!-- Preloader Ends -->

    <!-- Header
    ============================================= -->
    <header id="home">

        <!-- Start Navigation -->
        <nav class="navbar navbar-default attr-border navbar-sticky bootsnav">

            <div class="container">

                <!-- Start Atribute Navigation -->
                <div class="attr-nav">
                    <ul>
                        <li class="quote-btn"><a href="<?php bloginfo('url'); ?>/provider">Make Appointment</a></li>
                    </ul>
                </div>
                <!-- End Atribute Navigation -->

                <!-- Start Header Navigation -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-menu">
                        <i class="fa fa-bars"></i>
                    </button>
                    <a class="navbar-brand" href="<?php bloginfo('url'); ?>">
                        <img src="<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/assets/img/logo-pharmtotable.png" class="logo" alt="Logo">
                    </a>
                </div>
                <!-- End Header Navigation -->

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="navbar-menu">

                <?php wp_nav_menu( array(
                            'sort_column' => 'menu_order',
                            'theme_location' => 'main',
                            'container' => 'ul',
                            'menu_class' => 'nav navbar-nav navbar-right',
                            'items_wrap'      => '<ul class="%2$s">%3$s</ul>',
                            'walker' => new My_Walker_Nav_Menu(),

                            ) );
                            ?>
                </div><!-- /.navbar-collapse -->
            </div>

        </nav>
        <!-- End Navigation -->
        <? wp_head(); ?>
    </header>
    <!-- End Header -->


<!-- Start Banner
============================================= -->
<div class="banner-area">
    <div id="bootcarousel" class="carousel text-center inc-top-heading slide carousel-fade animate_text" data-ride="carousel">
        <!-- Wrapper for slides -->
        <div class="carousel-inner text-light carousel-zoom">
            <div class="item active">
                <div class="slider-thumb bg-cover" style="background-image: url(<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/assets/img/home-pharmtotableteam.jpg);"></div>
                <div class="box-table shadow dark">
                    <div class="box-cell">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="content">
                                        <h1 data-animation="animated slideInRight">Start Feeling your <span>BEST</span>.</h1>
                                        <p data-animation="animated slideInUp">
                                            Get personalized, convenient health care that addresses the underlying causes of chronic disease.
                                        </p>
                                        <a data-animation="animated slideInUp" class="btn btn-light border btn-md" href="https://pharmtotable.life/product/introduction-to-functional-medicine-orientation/">Download Our Discovery Class</a>
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

    <!-- Start About
    ============================================= -->
    <div class="about-area default-padding">
        <div class="container">
            <div class="row">
                <div class="about-items">
                    <div class="col-md-6 info">
                        <h4>Address Root Causes</h4>
                        <h2>Get personalized health care that addresses the underlying causes of chronic disease.</h2>
                        <p>
                        Being well is not just the absence of disease, being well is thriving. Are you tired and struggling to get rid of daily symptoms?  Has our current health-care system left you with lots of questions, still not feeling well, and lack of answers? We believe that we can help you transform your life by addressing the root cause of your symptoms. Find out the #1 root cause of chronic disease by watching our video below.
                        </p>
                        <div class="bottom">
                            <div class="video">
                                <a href="https://app.monstercampaigns.com/c/g9aoffuqh8sc1kutmuzw/" class="relative theme video-play-button item-center">
                                    <i class="fa fa-play"></i>
                                </a>
                            </div>
                            <div class="content">
                                <h4>The top 3 drivers for chronic disease</h4>
                                <p>Sign up to our list and we'll send you the videos.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 tabs-items">
                        <!-- Tab Nav -->
                        <ul class="nav nav-pills">
                            <li class="active">
                                <a data-toggle="tab" href="#tab1" aria-expanded="true">
                                    Discovery Class
                                </a>
                            </li>
                            <li>
                                <a data-toggle="tab" href="#tab2" aria-expanded="false">
                                    First Appointment
                                </a>
                            </li>
                            <li>
                                <a data-toggle="tab" href="#tab3" aria-expanded="false">
                                    The Journey
                                </a>
                            </li>
                        </ul>
                        <!-- End Tab Nav -->
                        <!-- Start Tab Content -->
                        <div class="tab-content tab-content-info">
                            <!-- Single Item -->
                            <div id="tab1" class="tab-pane fade active in">
                                <div class="info title">
                                    <h3>Consulted by experienced doctors</h3>
                                    <p>
                                    Looking to learn more about how our team can meet your health goals? Sign up for our Functional Medicine Orientation Discovery Class. Secure your spot in our next class. </p>
                                    <a class="btn btn-theme border btn-md" href="/product/introduction-to-functional-medicine-orientation/">Sign up for the Class</a>
                                </div>
                            </div>
                            <!-- End Single Item -->

                            <!-- Single Item -->
                            <div id="tab2" class="tab-pane fade">
                                <div class="info title">
                                    <h3>Your First Appointment</h3>
                                    <p>Ready to start your Functional Medicine journey? We want to help you feel better as soon as possible. To do that, we need to understand your whole health story. Book your appointment and get started on your health history forms right away! Forms must be completed at least  hours before your appointment. Book your appointment now.</p>
                                    <a class="btn btn-theme border btn-md" href="/provider/">Book an Appointment Today</a>
                                </div>
                            </div>
                            <!-- End Single Item -->

                            <!-- Single Item -->
                            <div id="tab3" class="tab-pane fade">
                                <div class="info title">
                                    <h3>Your Journey</h3>
                                    <p>
                                        Get ready for the best health care experience you've had. Our providers will listen to your needs and customize your journey. We may recommend personalized nutrition plans, supplements, lab testing and biometric testing, as appropriate. Everyone’s journey is unique. We look forward to building a relationship with you so we can see your health continue to improve.
                                    </p>
                                </div>
                            </div>
                            <!-- End Single Item -->

                        </div>
                        <!-- End Tab Content -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End About -->

    <!-- Start Services
    ============================================= -->
    <div class="services-area inc-icon bg-gray carousel-shadow default-padding">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="site-heading text-center">
                        <h2>Centers of <span>Excellence</span></h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="services-items text-center services-carousel owl-carousel owl-theme">
                        <!-- Single Item -->
                        <div class="item">
                            <div class="info">
                                <h4>
                                    <a href="#">Wellness</a>
                                </h4>
                                <div class="overlay">
                                    <i class="flaticon-yoga-pose"></i>
                                </div>
                                <p class="services">
                                You may have a few well managed chronic conditions, but are looking for recommendations on how to improve health and stay healthy for the long-haul. Receive personalized recommendations to enhance your health span and optimize your nutrient status.
                                </p>

                            </div>
                        </div>
                        <!-- End Single Item -->
                        <!-- Single Item -->
                        <div class="item">
                            <div class="info">
                                <h4>
                                    <a href="#">Medication Optimization</a>
                                </h4>
                                <div class="overlay">
                                    <i class="flaticon-drug"></i>
                                </div>
                                <p class="services">
                                Too many medications? Our PharmToTable pharmacists are specially trained to make sure your medication regimen is as safe, effective and affordable as possible, using a root-cause approach. We work with your health care team and make recommendations to optimize your medications and help you simplify your life!
                                </p>

                            </div>
                        </div>
                        <!-- End Single Item -->
                        <!-- Single Item -->
                        <div class="item">
                            <div class="info">
                                <h4>
                                    <a href="#">Functional Medicine</a>
                                </h4>
                                <div class="overlay">
                                    <i class="flaticon-tree-and-roots"></i>
                                </div>
                                <p class="services">
                                A deep dive into your story to address the root-cause of your symptoms.  This starts by mapping your personal timeline, and health history, then your provider will make recommendations for testing, lifestyle modifications, and possibly nutrient supplementation to put you on your journey to health.
                                </p>
                                <a class="btn btn-theme border circle btn-md" href="https://pharmtotable.life/services/functional-medicine/">Learn More</a>
                            </div>
                        </div>
                        <!-- End Single Item -->
                        <!-- Single Item -->
                        <div class="item">
                            <div class="info">
                                <h4>
                                    <a href="#">Nutrition</a>
                                </h4>
                                <div class="overlay">
                                    <i class="flaticon-apple"></i>
                                </div>
                                <p class="services">
                                Low-carb? Paleo? Low FODMAP? There are way too many dietary recommendations out there - Which one is right for you? Let PharmToTable nutrition therapy providers lead the way! Whether it’s a specific diet or more general advice, each provider can give you recommendations and help with meal plans that are right for YOU!
                                </p>

                            </div>
                        </div>
                        <!-- End Single Item -->
                        <!-- Single Item -->
                        <div class="item">
                            <div class="info">
                                <h4>
                                    <a href="#">Health Coaching</a>
                                </h4>
                                <div class="overlay">
                                    <i class="flaticon-clipboard"></i>
                                </div>
                                <p>
                                Whether you’re interested in weight loss, better nutrition or better overall health and energy, lasting changes are possible with a PharmToTable Health Coach. A health coach will work with you one-on-one to help you define your goals, pinpoint obstacles and make powerful, lasting changes in your life. Coaching is one of the best ways to help make healthy changes stick!
                                </p>

                            </div>
                        </div>
                        <!-- End Single Item -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Services -->

    <!-- Start Why Chose Us
    ============================================= -->
    <div class="chose-us-area item-half">
        <div class="container-full">
            <div class="row">
                <div class="col-md-6 thumb bg-cover" style="background-image: url(<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/assets/img/consult.jpg);"></div>
                <div class="col-md-6 info">
                    <div class="heading">
                        <h2>Why Choose us</h2>
                    </div>
                    <ul>
                        <li>
                            <div class="info">
                                <h4>Technology</h4>
                                <p>Our cutting edge digital health technology platform allows us to connect with your whole care team</p>
                            </div>
                        </li>
                        <li>
                            <div class="info">
                                <h4>Team Based Care</h4>
                                <p>We combine strengths from various providers, pharmacists, physicians, nutritionists, health-coaches, to meet your needs.</p>
                            </div>
                        </li>
                        <li>
                            <div class="info">
                                <h4>Convenient</h4>
                                <p>See your provider from your computer or smartphone . Providers have flexible availability.</p>
                            </div>
                        </li>
                        <li>
                            <div class="info">
                                <h4>Pharmacists</h4>
                                <p>Pharmacists are chemistry experts, we can help you reduce your medication burden, and simplify your life.</p>                              </p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- End Why Chose Us -->

    <!-- Start Testimonials
    ============================================= -->
    <div class="testimonials-area carousel-shadow bg-gray default-padding">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="site-heading text-center">
                        <h2>Patient <span>Testimonials</span></h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="testimonial-items testimonial-carousel owl-carousel owl-theme">
                    <?php
                        $args = array(
                            'post_type'     => 'testimonials',
                            'post_status'   => 'publish',
                            'posts_per_page' => 2,
                            'orderby' => 'rand',
                        );

                        $loop = new WP_Query( $args );
                        while ( $loop->have_posts() ) : $loop->the_post(); ?>
                        <!-- Single Item -->
                        <div class="item">
                            <div class="content">
                                <p>
                                    <?php the_content();?>
                                </p>
                            </div>
                            <div class="provider">
                                <div class="thumb">
                                    <img src="<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/assets/img/testimonial-standard.png" alt="Testimonial-Image">
                                </div>
                                <div class="info">
                                    <h4><?php echo the_title();?></h4>
                                </div>
                            </div>
                        </div>
                        <!-- End Single Item -->
                        <?php endwhile;
                        wp_reset_postdata();  ?>
                </div>
            </div>
        </div>
    </div>
    <!-- End Testimonials -->
<?php get_footer(); ?>
