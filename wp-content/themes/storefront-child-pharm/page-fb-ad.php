<?php
/*
*Template Name: Facebook Ad Landing Page
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
    <title>PharmToTable - Orientation Class</title>

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
                        <img src="<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/assets/img/pharmtotable-logo.png" class="logo" alt="Logo">
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

 <!-- Start Breadcrumb
============================================= -->
<div class="breadcrumb-area shadow dark bg-fixed text-light" style="background-image: url(assets/img/2440x1578.png);">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h1><?php the_title(); ?></h1>
            </div>
            <div class="col-md-6 text-right">
                <ul class="breadcrumb">
                    <li><a href="<?php echo get_home_url(); ?>"><i class="fas fa-home"></i> Home</a></li>
                    <li class="active"><?php the_title(); ?></li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- End Breadcrumb -->

 <!-- Start Page Block
============================================= -->
<div class="services-details-area default-padding">
    <div class="container">
        <div class="row">
            <!-- Page Content -->
            <div class="col-md-12 content">

            <?php
                if (have_posts()) : while (have_posts()) : the_post();
                the_content();
                endwhile; endif; ?>
            </div>
            <!-- End Page Content -->
        </div>
    </div>
</div>
<!-- End Page Block -->

    <!-- Start Why Chose Us
    ============================================= -->
    <div class="chose-us-area smaller-padding item-half">
        <div class="container">
            <div class="row">
                <div class="col-md-6 info">
                    <div class="heading">
                        <h2>Invest in Your Health</h2>
                        <p>
                        Interested in Functional Medicine, but still have questions? We get it. Starting this journey is an investment – one that could change your life for the better. We want to make sure our team is a good fit for you, too! Join us at our PharmToTable Orientation class. You’ll get a preview of your journey and have a chance to get all of your questions answered about the process.
                        <br><br> We'll discuss: <br></p>
                    </div>
                    <ul>
                        <li>
                            <div class="info">
                                <h4>The current challenges</h4>
                                <p>
                                    We'll discuss the challenges in the current healthcare model
                                </p>
                            </div>
                        </li>
                        <li>
                            <div class="info">
                                <h4>What is Functional Medicine</h4>
                                <p>
                                    How it's different from the traditional approach to healthcare
                                </p>
                            </div>
                        </li>
                        <li>
                            <div class="info">
                                <h4>Why Pharmacists?</h4>
                                <p>
                                    Pharmacists are a key partner in your healthcare and suited for functional approach to care
                                </p>
                            </div>
                        </li>
                        <li>
                            <div class="info">
                                <h4>Journey to Wellness</h4>
                                <p>
                                    What your first visit will look like, and what to expect in the coming months of our partnership.
                                </p>
                            </div>
                        </li>
                    </ul>
                </div>
                <!-- Start Faq -->
                <div class="col-md-6 faq-area">
                    <div class="heading">
                        <h2>Questions and Answers</h2>
                    </div>
                    <div class="acd-items acd-arrow">
                        <div class="panel-group symb" id="accordion">

                            <!-- Single Item -->
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#ac1">
                                            How long is the class?
                                        </a>
                                    </h4>
                                </div>
                                <div id="ac1" class="panel-collapse collapse in">
                                    <div class="panel-body">
                                        <p>The class is one hour long.</p>
                                    </div>
                                </div>
                            </div>
                            <!-- End Single Item -->

                            <!-- Single Item -->
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#ac2">
                                            Will we have time to ask questions at the end?
                                        </a>
                                    </h4>
                                </div>
                                <div id="ac2" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <p>Yes! The last 10-15 minutes will be for you to get your questions answered.</p>
                                    </div>
                                </div>
                            </div>
                            <!-- End Single Item -->

                            <!-- Single Item -->
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#ac3">
                                            Will I be meeting with the pharmacist from my state?
                                        </a>
                                    </h4>
                                </div>
                                <div id="ac3" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <p>Our class is led by one of the pharmacists on our team. The pharmacist may not be licensed in the state where you reside. After the course, our team will connect you with the pharmacists in your state. You will be able to sign up for a free 15 min consultation to meet them and make sure it’s a good fit. </p>
                                    </div>
                                </div>
                            </div>
                            <!-- End Single Item -->

                            <!-- Single Item -->
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#ac4">
                                            How can I sign up?
                                        </a>
                                    </h4>
                                </div>
                                <div id="ac4" class="panel-collapse collapse in">
                                    <div class="panel-body">
                                        <p>
                                            We offer a variety of times and days of the week for our orientation.
                                        </p>
                                        <p>
                                        <a href="https://pharmtotable.life/product/introduction-to-functional-medicine-orientation/" style="color: green; text-decoration: underline;">Click here to schedule your time!</a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <!-- Single Item -->
                        </div>
                    </div>
                </div>
                <!-- End Faq -->
            </div>
        </div>
    </div>
    <!-- End Why Chose Us -->

<!-- Start Why Chose Us
============================================= -->
<div class="chose-us-area item-half default-padding">
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
                    <!-- Single Item -->
                    <div class="item">
                        <div class="content">
                            <p>
                            <em>My experience with my functional medicine pharmacist, Melody Hartzler, was phenomenal!</em><br><br>
                            I came to her after seeing numerous doctors and nutritionists with stomach pain, bloating, and constipation.  No one had an answer for me in the past, so my nutritionist suggested Dr. Hartzler. <br><br>
                            We did a telehealth meeting and she quickly decided we needed more tests.  Once the test results arrived, she was able to determine a course of action using mostly herbal medicines.  A month into the meds and my stomach pain is nearly gone as is my constipation.  It has been  amazing! The plan hasn't stopped there and we continue to meet and communicate on the best course of action going forward.  I highly recommend Dr. Hartzler for your needs - she listens and goes above and beyond!
                            </p>
                        </div>
                        <div class="provider">
                            <div class="thumb">
                                <img src="<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/assets/img/testimonial-standard.png" alt="Testimonial-Image">
                            </div>
                            <div class="info">
                                <h4>Sara L.</h4>
                            </div>
                        </div>
                    </div>
                    <!-- End Single Item -->
                    <!-- Single Item -->
                    <div class="item">
                        <div class="content">
                            <p>
                            By working directly with Dr. Hartzler, my medication has not only changed, but the dosage has been reduced, while adding natural vitamin supplements that help support my body’s specific needs.  Her ability to weave both functional and conventional medicine into a treatment plan has been shall we say, more than wonderful!!  Her knowledge about how to integrate these two different types of treatments have been instrumental in not only lowering my A1C, but weight loss and more overall energy.<br><br>
                            <em><strong>She is no longer just treating the symptoms of my disease, but the underlying causes.  </strong></em><br><br>
                            This unique insight has been the difference between the previous negative side effects and the improved health I enjoy. Without the help and guidance of Dr. Melody Hartzler, my quality of life would have continued to degrade over time, today I have a much better prognosis for the rest of my life.</p>
                        </div>
                        <div class="provider">
                            <div class="thumb">
                                <img src="<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/assets/img/testimonial-standard.png" alt="Testimonial-Image">
                            </div>
                            <div class="info">
                                <h4>Kevin</h4>
                            </div>
                        </div>
                    </div>
                    <!-- End Single Item -->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Testimonials -->
<?php get_footer(); ?>
