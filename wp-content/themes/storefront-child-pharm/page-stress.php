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

                 <!-- Start Atribute Navigation -->
                 <div class="attr-nav">
                    <ul>
                        <li class="quote-btn"><a href="#contact">Buy Now</a></li>
                    </ul>
                </div>
                <!-- End Atribute Navigation -->

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
                                            <a data-animation="animated slideInUp" class="btn btn-theme effect btn-md" href="#checkout">Sign Up Now</a>
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
                <div class="col-md-6 thumb bg-cover" style="background-image: url(<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/assets/img/stress-stock.jpg)"></div>
                <div class="col-md-6 info">
                    <div class="heading">
                        <h3>Finding solutions to chronic health and wellness challenges should not be hard.</h3>
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
                    <a class="btn btn-theme effect btn-md" href="#checkout">Sign Up Now</a>

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
                                <div class="col-md-3"></div>
                                <div class="col-md-8">
                                    <div class="info title">
                                        <h3>What You Get When You Sign Up</h3>
                                        <li class="info" style="font-size: 20px;">A full day’s training live from functional medicine pharmacists</li>
                                        <li class="info" style="font-size: 20px;">PDFs copies of speaker’s slides</li>
                                        <li class="info" style="font-size: 20px;">Instant downloadable handouts</li>
                                        <li class="info" style="font-size: 20px;">Exclusive discounts on appointments and programs with functional medicine clinicians</li>
                                        <li class="info" style="font-size: 20px;">Access to the pharmacist’s favorite products</li>
                                    </div>
                                </div>
                                <div class="col-md-2"></div>
                                <!-- End Department Info -->

                                <!-- Start Conference Schedule -->
                                <div class="col-md-1"></div>
                                <div class="col-md-7 opening-hours">
                                    <div class="opening-info">
                                        <h4>Agenda of the Day</h4>
                                        <ul class="schedule" style="list-style-type: none">
                                            <li>10:00 - 10:30 am <div class="pull-right"> Introduction to Functional Medicine</div></li>
                                            <li>10:30 - 11:15 am <div class="pull-right"> How Stress Hormones Work </div></li>
                                            <li>11:15 - 11:30 am <div class="pull-right"> Relaxation and Movement Break </div></li>
                                            <li>11:30 - 12:00 pm <div class="pull-right"> How the Thyroid Works </div></li>
                                            <li>12:05 - 12:50 pm <div class="pull-right"> How Female Hormones Impact Disease </div></li>
                                            <li>12:50 - 1:15 pm <div class="pull-right"> Panel Discussion </div></li>
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
                                <div class="col-md-2"></div>
                                <!-- End Conference Schedule -->

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
                                <img src="https://functionalmedicinece.com/wp-content/uploads/2020/01/LindseyElmore.jpg" alt="Thumb">
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
                                <h4>Lindsey Elmore</h4>
                                <h5>PharmD, BCPS, CYP-250</h5>
                                <div class="appoinment-btn">
                                    <a href="#">View Bio</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Single Item -->
                    <!-- Single Item -->
                    <div class="col-md-4 col-sm-6 equal-height">
                        <div class="item">
                            <div class="thumb">
                                <img src="https://functionalmedicinece.com/wp-content/uploads/2019/09/melody_hartzler-scaled.jpg" alt="Thumb">
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
                                <h4>Melody Hartzler</h4>
                                <h5>PharmD, BCACP, BC-ADM</h5>
                                <div class="appoinment-btn">
                                    <a href="#">View Bio</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Single Item -->
                    <!-- Single Item -->
                    <div class="col-md-4 col-sm-6 equal-height">
                        <div class="item">
                            <div class="thumb">
                                <img src="https://functionalmedicinece.com/wp-content/uploads/2021/01/vanessalesneski.png" alt="Thumb">
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
                                <h4>Vanessa Lesneski</h4>
                                <h5>PharmD, BCPS, CPh</h5>
                                <div class="appoinment-btn">
                                    <a href="#">View Bio</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Single Item -->
                    <!-- Single Item -->
                    <div class="col-md-2 col-sm-6 equal-height"></div>
                    <div class="col-md-4 col-sm-6 equal-height">
                        <div class="item">
                            <div class="thumb">
                                <img src="https://functionalmedicinece.com/wp-content/uploads/2019/09/lara_zakaria_headshots_square-scaled.jpg" alt="Thumb">
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
                                <h4>Lara Zakaria</h4>
                                <h5>PharmD, MS CNS CDN IFMCP</h5>
                                <div class="appoinment-btn">
                                    <a href="#">View Bio</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Single Item -->
                    <!-- Single Item -->
                    <div class="col-md-4 col-sm-6 equal-height">
                        <div class="item">
                            <div class="thumb">
                                <img src="https://pharmtotable.life/wp-content/uploads/2020/06/sarahbachofner.jpg" alt="Thumb">
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
                                <h4>Sarah Bachofner</h4>
                                <h5>PharmD</h5>
                                <div class="appoinment-btn">
                                    <a href="#">View Bio</a>
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
    <div class="testimonials-area carousel-shadow bg-gray default-padding">
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
        <!-- End Testimonials -->
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



<?php get_footer(); ?>

</body>
</html>
