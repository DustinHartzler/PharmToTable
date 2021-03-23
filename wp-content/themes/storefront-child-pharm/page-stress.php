<?php
/*
*Template Name: Page for Stress Conference
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

    <!-- ========== Page Title ========== -->
    <title>PharmToTable | <?php wp_title(''); ?></title>

    <!-- ========== Favicon Icon ========== -->
    <link rel="shortcut icon" href="assets/img/favicon.png" type="image/x-icon">

    <!-- ========== Start Stylesheet ========== -->
    <link href="<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/assets/css/bootstrap.min.css" rel="stylesheet" /> <link href="<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/assets/css/font-awesome.min.css" rel="stylesheet" />
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
        <?php wp_head(); ?>
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

                <!-- Start Header Navigation -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-menu">
                        <i class="fa fa-bars"></i>
                    </button>
                    <a class="navbar-brand" href="index.html">
                        <img src="assets/img/logo.png" class="logo" alt="Logo">
                    </a>
                </div>
                <!-- End Header Navigation -->

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="navbar-menu">
                    <ul class="nav navbar-nav navbar-right" data-in="#" data-out="#">
                        <li>
                            <a class="smooth-menu" href="#home">Home</a>
                        </li>
                        <li>
                            <a class="smooth-menu" href="#departments">Departments</a>
                        </li>
                        <li>
                            <a class="smooth-menu" href="#doctors">Doctors</a>
                        </li>
                        <li>
                            <a class="smooth-menu" href="#gallery">Gallery</a>
                        </li>
                        <li>
                            <a class="smooth-menu" href="#testimonials">Testimonials</a>
                        </li>
                        <li>
                            <a class="smooth-menu" href="#blog">Blog</a>
                        </li>
                    </ul>
                </div><!-- /.navbar-collapse -->
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
                    <div class="slider-thumb bg-cover" style="background-image: url(<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/assets/img/stress-stock.jpg);"></div>
                    <div class="box-table">
                        <div class="box-cell">
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="content">
                                            <h1 data-animation="animated fadeInUp" style="color:white">The Stress <span>Solution</span></h1>
                                            <h2 data-animation="animated fadeInDown" style="color:white">A Course to Help You Reduce Stress and Heal Hormones</h2>
                                            <p data-animation="animated slideInUp" style="color:white">May 15, 2021</p>
                                            <a data-animation="animated slideInUp" class="btn btn-theme effect btn-md" href="#">Sign Up Now</a>
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
    <div class="chose-us-area item-half">
        <div class="container-full">
            <div class="row">
                <div class="col-md-6 thumb bg-cover" style="background-image: url(<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/assets/img/consult.jpg);"></div>
                <div class="col-md-6 info">
                    <div class="heading">
                        <h3>Finding solutions to chronic health and wellness challenges should not be hard. In fact, most doctors don’t make the connection between the toxic toll of stress and chronic disease.</h3>
                        <h4>By the time you finish this course, you’ll walk away with the knowledge to:</h4>
                    </div>
                    <ul>
                        <li>
                            <div class="info">
                                <p>Reduce stress in your daily life</p>
                            </div>
                        </li>
                        <li>
                            <div class="info">
                                <p>Include movement without feeling like exercising</p>
                            </div>
                        </li>
                        <li>
                            <div class="info">
                                <p>Understand how stress is disrupting your blood sugar, thryoid, and sex hormones</p>
                            </div>
                        </li>
                        <li>
                            <div class="info">
                                <p>Create a system to make small changes daily</p>                              </p>
                            </div>
                        </li>
                    </ul>
                    <br>
                    <a class="btn btn-theme effect btn-md" href="#">Sign Up Now</a>

                </div>
            </div>
        </div>
    </div>
    <!-- End Why Chose Us -->

    <!-- Start Departments
    ============================================= -->
    <div id="departments" class="department-tabs default-padding">
        <div class="container">
            <div class="row">
                <div class="col-md-12 tab-contents">
                    <div class="row">
                        <!-- Start Tab Content -->
                        <div class="tab-content tab-content-info">
                            <!-- Single Item -->
                            <div id="tab1" class="tab-pane fade active in">

                                <!-- Start Department Info -->
                                <div class="col-md-5">
                                    <div class="info title">
                                        <h3>What You Get When You Sign Up</h3>
                                        <li class="info">A full day’s training live from functional medicine pharmacists</li>
                                        <li class="info">PDFs copies of speaker’s slides</li>
                                        <li class="info">Instant Downloadable Handouts</li>
                                        <li class="info">Exclusive discounts on appointments and programs with functional medicine clinicians</li>
                                        <li class="info">Access to the pharmacist’s favorite products</li>
                                    </div>
                                </div>
                                <!-- End Department Info -->

                                <!-- Start Opening Hours -->
                                <div class="col-md-7 opening-hours">
                                    <div class="opening-info">
                                        <h4>Agenda of the Day</h4>
                                        <ul>
                                            <li>10:00 - 10:30 am <div class="pull-right"> Introduction to Functional Medicine</div></li>
                                            <li>10:30 - 11:15 am <div class="pull-right"> How Stress Hormones Work </div></li>
                                            <li>11:15 - 11:30 am <div class="pull-right"> Relaxation and Movement Break </div></li>
                                            <li>11:30 - 12:00 pm <div class="pull-right"> How the Thyroid Works </div></li>
                                            <li>12:00 - 12:05 pm <div class="pull-right"> Movement Break </div></li>
                                            <li>12:05 - 12:50 pm <div class="pull-right"> How Female Hormones Impact Disease </div></li>
                                            <li>12:50 - 1:15 pm <div class="pull-right"> Panel Discussion </div></li>
                                            <li>1:15 - 1:20 pm <div class="pull-right"> Movement Break </div></li>
                                            <li>1:20 - 2:00 pm <div class="pull-right"> Keeping a Healthy Gut </div></li>
                                            <li>2:00 - 3:00 pm <div class="pull-right"> Lunch </div></li>
                                            <li>3:00 - 3:30 pm <div class="pull-right"> How to Eat </div></li>
                                            <li>3:30 - 4:00 pm <div class="pull-right"> How to Breathe and Move </div></li>
                                            <li>4:00 - 4:30 pm <div class="pull-right"> Personal Healing Stories </div></li>
                                            <li>4:30 - 4:45 pm <div class="pull-right"> How to set SMART Goals </div></li>
                                            <li>5:15 - 5:45 pm <div class="pull-right"> Breaking the Cycle of Disease </div></li>
                                            <li>5:45 - 6:00 pm <div class="pull-right"> Final Q&A </div></li>
                                            <li></li>
                                        </ul>
                                    </div>
                                </div>
                                <!-- End Opening Hours -->

                            </div>
                            <!-- End Single Item -->
                        </div>
                        <!-- End Tab Content -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Departments -->

        <!-- Start Doctors Tips
    ============================================= -->
    <div id="tips" class="doctor-tips-area default-padding bg-gray">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="site-heading text-center">
                        <h2>Meet our <span>Speakers</span></h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="health-tips-items tips-carousel owl-carousel owl-theme">
                    <!-- Single Item -->
                    <div class="single-item">
                        <div class="col-md-5">
                            <div class="thumb">
                                <img src="https://functionalmedicinece.com/wp-content/uploads/2020/01/LindseyElmore.jpg" alt="">
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="info">
                                <div class="doctor">
                                    <h4>Lindsey Elmore</h4>
                                    <h5>PharmD, BCPS, CYP-250</h5>
                                </div>
                                <p>After a long battle with insomnia, Dr. Elmore started a lifelong study of natural medicine. She now combines a multidisciplinary approach including functional medicine, yoga, diet and exercise to help people heal themselves. </p>

                            </div>
                        </div>
                    </div>
                    <!-- End Single Item -->
                    <!-- Single Item -->
                    <div class="single-item">
                        <div class="col-md-5">
                            <div class="thumb">
                                <img src="https://functionalmedicinece.com/wp-content/uploads/2019/09/melody_hartzler-scaled.jpg" alt="">
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="info">
                                <div class="doctor">
                                    <h4>Melody Hartzler</h4>
                                    <h5>PharmD, BCACP, BC-ADM</h5>
                                </div>
                                <p>Dr. Hartzler found herself with daily abdominal pain, bloating, and hives 9 months after her first child. Her journey to functional medicine was found because traditional medicine didn’t have the answers. As she worked with several practitioners to heal her gut, she researched and learned how to and address root causes of chronic conditions, which she now incorporates into her clinical practice. She founded and leads the PharmToTable Team, a group of functional medicine pharmacists across the country. </p>

                            </div>
                        </div>
                    </div>
                    <!-- End Single Item -->
                    <!-- Single Item -->
                    <div class="single-item">
                        <div class="col-md-5">
                            <div class="thumb">
                                <img src="https://functionalmedicinece.com/wp-content/uploads/2021/01/vanessalesneski.png" alt="">
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="info">
                                <div class="doctor">
                                    <h4>Vanessa Lesneski</h4>
                                    <h5>PharmD, BCPS, CPh</h5>
                                </div>
                                <p>Dr. Lesneski's functional medicine journey started when she began having acid reflux symptoms. After several years of prescription medications, the reflux symptoms became so severe she was evaluated for a medical device that uses electrical stimulation to close the upper esophageal sphincter.  After an extensive and intrusive workup the research team said she did not have acid reflux.  She needed answers and conventional medicine had only ended in dead ends.  Her personal experience with functional medicine was so profound that now she teaches functional medicine to pharmacy students in addition to leading a pharmacy clinic within a functional medicine office. </p>

                            </div>
                        </div>
                    </div>
                    <!-- End Single Item --><!-- Single Item -->
                    <div class="single-item">
                        <div class="col-md-5">
                            <div class="thumb">
                                <img src="https://functionalmedicinece.com/wp-content/uploads/2019/09/lara_zakaria_headshots_square-scaled.jpg" alt="">
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="info">
                                <div class="doctor">
                                    <h4>Lara Zakaria</h4>
                                    <h5>PharmD, MS CNS CDN IFMCP</h5>
                                </div>
                                <p>Struggling with frequent episodes of hypoglycemia and acne at 28 led Dr Zakaria down the path of nutrition and Functional Medicine. She made the connection about how working long, stressful shifts as a pharmacist, eating a diet that was failing to optimize blood sugar balance, and acid reflux were all contributing to her symptoms. That experience and applying the concepts to improve her own health is what drove Dr Zakaria to get her masters in nutrition, become a certified nutrition specialist and certified Functional Medicine practitioner.</p>

                            </div>
                        </div>
                    </div>
                    <!-- End Single Item -->
                    <!-- Single Item -->
                    <div class="single-item">
                        <div class="col-md-5">
                            <div class="thumb">
                                <img src="https://pharmtotable.life/wp-content/uploads/2020/06/sarahbachofner.jpg" alt="">
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="info">
                                <div class="doctor">
                                    <h4>Sarah Bachofner</h4>
                                    <h5>PharmD</h5>
                                </div>
                                <p>Dr. Bachofner discovered functional medicine after five years of loss with no conventional explanation as to why on the journey to giving birth to two incredible blessings. Since then, she learned that she was significantly deficient in several key nutrients. She discovered the root cause was likely in part due to a few genetic mutations and bacterial imbalance and inflammation of the gut. The stress of these five long years after the stress of pharmacy school was a huge contributor to it all. She has learned how to manage this stress and has made it her mission to help others struggling with health issues due to stress as a member of the PharmToTable Team.</p>

                            </div>
                        </div>
                    </div>
                    <!-- End Single Item -->
                </div>
            </div>
        </div>
    </div>
    <!-- End Doctors Tips -->

    <!-- Start Doctors
    ============================================= -->
    <div id="doctors" class="doctor-area bg-gray default-padding bottom-less">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="site-heading text-center">
                        <h2>Meet Our <span>Speakers</span></h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="doctor-items text-center">
                    <!-- Single Item -->
                    <div class="col-md-4 col-sm-6 equal-height">
                        <div class="item">
                            <div class="thumb">
                                <img src="<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/assets/img/800x600.png" alt="Thumb">
                                <div class="overlay">
                                    <a href="#"><i class="fas fa-plus"></i></a>
                                </div>
                                <div class="social">
                                    <ul>
                                        <li class="facebook">
                                            <a href="#"><i class="fab fa-facebook-f"></i></a>
                                        </li>
                                        <li class="twitter">
                                            <a href="#"><i class="fab fa-twitter"></i></a>
                                        </li>
                                        <li class="instagram">
                                            <a href="#"><i class="fab fa-instagram"></i></a>
                                        </li>
                                        <li class="linkedin">
                                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="info">
                                <h4>Jessica Jones</h4>
                                <h5>Cardiologist</h5>
                                <div class="appoinment-btn">
                                    <a href="#">Make appoinment</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Single Item -->
                    <!-- Single Item -->
                    <div class="col-md-4 col-sm-6 equal-height">
                        <div class="item">
                            <div class="thumb">
                                <img src="<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/assets/img/800x600.png" alt="Thumb">
                                <div class="overlay">
                                    <a href="#"><i class="fas fa-plus"></i></a>
                                </div>
                                <div class="social">
                                    <ul>
                                        <li class="facebook">
                                            <a href="#"><i class="fab fa-facebook-f"></i></a>
                                        </li>
                                        <li class="twitter">
                                            <a href="#"><i class="fab fa-twitter"></i></a>
                                        </li>
                                        <li class="instagram">
                                            <a href="#"><i class="fab fa-instagram"></i></a>
                                        </li>
                                        <li class="linkedin">
                                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="info">
                                <h4>Ahel Natasha</h4>
                                <h5>Dental surgeon</h5>
                                <div class="appoinment-btn">
                                    <a href="#">Make appoinment</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Single Item -->
                    <!-- Single Item -->
                    <div class="col-md-4 col-sm-6 equal-height">
                        <div class="item">
                            <div class="thumb">
                                <img src="<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/assets/img/800x600.png" alt="Thumb">
                                <div class="overlay">
                                    <a href="#"><i class="fas fa-plus"></i></a>
                                </div>
                                <div class="social">
                                    <ul>
                                        <li class="facebook">
                                            <a href="#"><i class="fab fa-facebook-f"></i></a>
                                        </li>
                                        <li class="twitter">
                                            <a href="#"><i class="fab fa-twitter"></i></a>
                                        </li>
                                        <li class="instagram">
                                            <a href="#"><i class="fab fa-instagram"></i></a>
                                        </li>
                                        <li class="linkedin">
                                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="info">
                                <h4>Gabriela Beckett</h4>
                                <h5>Cosmetic Surgeon</h5>
                                <div class="appoinment-btn">
                                    <a href="#">Make appoinment</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Single Item -->
                    <!-- Single Item -->
                    <div class="col-md-4 col-sm-6 equal-height">
                        <div class="item">
                            <div class="thumb">
                                <img src="<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/assets/img/800x600.png" alt="Thumb">
                                <div class="overlay">
                                    <a href="#"><i class="fas fa-plus"></i></a>
                                </div>
                                <div class="social">
                                    <ul>
                                        <li class="facebook">
                                            <a href="#"><i class="fab fa-facebook-f"></i></a>
                                        </li>
                                        <li class="twitter">
                                            <a href="#"><i class="fab fa-twitter"></i></a>
                                        </li>
                                        <li class="instagram">
                                            <a href="#"><i class="fab fa-instagram"></i></a>
                                        </li>
                                        <li class="linkedin">
                                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="info">
                                <h4>Jessica Jones</h4>
                                <h5>Cardiologist</h5>
                                <div class="appoinment-btn">
                                    <a href="#">Make appoinment</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Single Item -->
                    <!-- Single Item -->
                    <div class="col-md-4 col-sm-6 equal-height">
                        <div class="item">
                            <div class="thumb">
                                <img src="<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/assets/img/800x600.png" alt="Thumb">
                                <div class="overlay">
                                    <a href="#"><i class="fas fa-plus"></i></a>
                                </div>
                                <div class="social">
                                    <ul>
                                        <li class="facebook">
                                            <a href="#"><i class="fab fa-facebook-f"></i></a>
                                        </li>
                                        <li class="twitter">
                                            <a href="#"><i class="fab fa-twitter"></i></a>
                                        </li>
                                        <li class="instagram">
                                            <a href="#"><i class="fab fa-instagram"></i></a>
                                        </li>
                                        <li class="linkedin">
                                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="info">
                                <h4>Ahel Natasha</h4>
                                <h5>Dental surgeon</h5>
                                <div class="appoinment-btn">
                                    <a href="#">Make appoinment</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Single Item -->
                    <!-- Single Item -->
                    <div class="col-md-4 col-sm-6 equal-height">
                        <div class="item">
                            <div class="thumb">
                                <img src="<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/assets/img/800x600.png" alt="Thumb">
                                <div class="overlay">
                                    <a href="#"><i class="fas fa-plus"></i></a>
                                </div>
                                <div class="social">
                                    <ul>
                                        <li class="facebook">
                                            <a href="#"><i class="fab fa-facebook-f"></i></a>
                                        </li>
                                        <li class="twitter">
                                            <a href="#"><i class="fab fa-twitter"></i></a>
                                        </li>
                                        <li class="instagram">
                                            <a href="#"><i class="fab fa-instagram"></i></a>
                                        </li>
                                        <li class="linkedin">
                                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="info">
                                <h4>Gabriela Beckett</h4>
                                <h5>Cosmetic Surgeon</h5>
                                <div class="appoinment-btn">
                                    <a href="#">Make appoinment</a>
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

        <!-- Start About
    ============================================= -->
    <div id="about" class="about-area default-padding">
        <div class="container">
            <div class="row">
                <div class="about-items">
                    <div class="col-md-12">
                    <?php
                if (have_posts()) : while (have_posts()) : the_post();
                the_content();
                endwhile; endif; ?>
            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End About -->

    <!-- Start Footer
    ============================================= -->
    <footer class="bg-dark text-light">
        <div class="container">
            <div class="row">
                <div class="f-items default-padding">

                    <!-- Single Item -->
                    <div class="col-md-3 col-sm-6 equal-height item">
                        <div class="f-item">
                            <h4>About</h4>
                            <p>
                                Excellence decisively nay man yet impression for contrasted remarkably. There spoke happy for you are out. Fertile how old address.
                            </p>
                            <div class="opening-info">
                                <h5>Opening Hours</h5>
                                <ul>
                                    <li> <span> Mon - Tues :  </span>
                                      <div class="pull-right"> 6.00 am - 10.00 pm </div>
                                    </li>
                                    <li> <span> Wednes - Thurs :</span>
                                      <div class="pull-right"> 8.00 am - 6.00 pm </div>
                                    </li>
                                    <li> <span> Sun : </span>
                                      <div class="pull-right closed"> Closed </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- End Single Item -->
                    <!-- Single Item -->
                    <div class="col-md-3 col-sm-6 equal-height item">
                        <div class="f-item link">
                            <h4>Our Depeartment</h4>
                            <ul>
                                <li>
                                    <a href="#"><i class="fas fa-arrow-right"></i> Medecine and Health</a>
                                </li>
                                <li>
                                    <a href="#"><i class="fas fa-arrow-right"></i> Dental Care and Surgery</a>
                                </li>
                                <li>
                                    <a href="#"><i class="fas fa-arrow-right"></i> Eye Treatment</a>
                                </li>
                                <li>
                                    <a href="#"><i class="fas fa-arrow-right"></i> Children Chare</a>
                                </li>
                                <li>
                                    <a href="#"><i class="fas fa-arrow-right"></i> Nuclear magnetic</a>
                                </li>
                                <li>
                                    <a href="#"><i class="fas fa-arrow-right"></i> Traumatology</a>
                                </li>
                                <li>
                                    <a href="#"><i class="fas fa-arrow-right"></i> X-ray</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- End Single Item -->
                    <!-- Single Item -->
                    <div class="col-md-3 col-sm-6 equal-height item">
                        <div class="f-item twitter-widget">
                            <h4>Latest tweets</h4>
                            <div class="twitter-item">
                                <div class="twitter-content">
                                    <p>
                                        <a href="#">@Becare</a> Looking for an awesome CREATIVE WordPress Theme? Find it here: <a target="_blank" href="http://t.co/0WWEMQEQ48">http://t.co/0WWEMQEQ48</a>
                                    </p>
                                </div>
                                <div class="twitter-context">
                                    <i class="fab fa-twitter"></i><span class="twitter-date"> 01 day ago</span>
                                </div>
                            </div>
                            <div class="twitter-item">
                                <div class="twitter-content">
                                    <p>
                                        <a href="#">@Jisham</a> It is a long established fact that a reader will be distracted by the readable . Find it here: <a target="_blank" href="http://t.co/0WWEMQEQ48">http://t.co/0WWEMQEQ48</a>
                                    </p>
                                </div>
                                <div class="twitter-context">
                                    <i class="fab fa-twitter"></i><span class="twitter-date"> 02 days ago</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Single Item -->
                    <!-- Single Item -->
                    <div class="col-md-3 col-sm-6 equal-height item">
                        <div class="f-item contact">
                            <h4>Contact</h4>
                            <ul>
                                <li>
                                    <i class="fas fa-phone"></i>
                                    <p>Phone <span>+123 456 7890</span></p>
                                </li>
                                <li>
                                    <i class="fas fa-envelope"></i>
                                    <p>Email <span><a href="mailto:support@validtheme.com">support@validtheme.com</a></span></p>
                                </li>
                                <li>
                                    <i class="fas fa-map"></i>
                                    <p>Office <span>123 6th St. Melbourne, FL 32904</span></p>
                                </li>
                            </ul>
                            <h5>Subscribe Newsletter</h5>
                            <form action="#">
                                <div class="input-group stylish-input-group">
                                    <input type="email" name="email" class="form-control" placeholder="Enter your e-mail here">
                                    <button type="submit">
                                        <i class="fa fa-paper-plane"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- End Single Item -->
                </div>
            </div>
        </div>
        <!-- Start Footer Bottom -->
        <div class="footer-bottom bg-dark text-light">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <p>&copy; Copyright 2019. All Rights Reserved by <a href="#">validthemes</a></p>
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

<?php get_footer(); ?>

</body>
</html>
