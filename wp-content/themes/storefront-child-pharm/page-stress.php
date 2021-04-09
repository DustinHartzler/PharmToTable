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
    <meta property="og:title" content="The Stress Solution" />
    <meta property="og:image" content="<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/assets/img/stress-free.jpg" />

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
    <link href="<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/stress.css" rel="stylesheet">
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
                        <h2 class="header">The <span>Stress</span> Solution</h2>
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
                    <div class="slider-thumb bg-cover" style="background-image: url(<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/assets/img/stress-free.jpg);"></div>
                    <div class="box-table">
                        <div class="box-cell">
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="content">
                                            <h1 data-animation="animated fadeInUp">The <span style="font-family: 'silver_south_scriptregular'; color: #71CABC">Stress</span> Solution</h1>
                                            <h2 data-animation="animated fadeInDown" style="color:black;">A Course to Help You Reduce Stress and Heal Hormones</h2>
                                            <p data-animation="animated slideInUp" style="color:black">May 15, 2021</p>
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
    <div class="chose-us-area bg-gray item-half">
        <div class="container-full">
            <div class="row">
                <div class="col-md-6 thumb bg-cover" style="background-image: url(<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/assets/img/stressed.jpeg)"></div>
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
    <div id="departments" class="department-tabs default-padding chose-us-area">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="site-heading text-center">
                        <h2><span>Details</span></h2>
                    </div>
                </div>
            </div>
            <div class="row">

                <div class="col-md-12 info">
                    <div class="heading">
                        <h3>What You Get When You Sign Up:</h3>
                    </div>
                    <div class="col-md-6 info">
                        <ul>
                            <li>
                                <div class="info">
                                    <p>A full day’s training live from functional medicine pharmacists</p>
                                </div>
                            </li>
                            <li>
                                <div class="info">
                                    <p>PDFs copies of speaker’s slides</p>
                                </div>
                            </li>
                            <li>
                                <div class="info">
                                    <p>Instant downloadable handouts</p>
                                </div>
                            </li>
                        </ul>
                        </div>
                        <div class="col-md-6 info">
                        <ul>
                        <li>
                            <div class="info">
                                <p>Exclusive discounts on appointments and programs with functional medicine clinicians</p>
                            </div>
                        </li>
                        <li>
                            <div class="info">
                                <p>Access to the pharmacist’s favorite products</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 tab-contents">
                    <div class="row">
                        <!-- Start Tab Content -->
                        <div class="tab-content tab-content-info">
                            <!-- Single Item -->
                            <div id="tab1" class="tab-pane fade active in">

                                <!-- Start Conference Schedule -->
                                <div class="col-md-2"></div>
                                <div class="col-md-8 opening-hours">
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
                                <div class="social">
                                    <ul>
                                        <li class="facebook">
                                            <a href="https://www.facebook.com/lindseyelmore/" target="_blank"><i class="fab fa-facebook-f"></i></a>
                                        </li>
                                        <li class="instagram">
                                            <a href="https://www.instagram.com/lindseyelmore/" target="_blank"><i class="fab fa-instagram"></i></a>
                                        </li>
                                        <li class="twitter">
                                            <a href="https://twitter.com/drlindseyelmore" target="_blank"><i class="fab fa-twitter"></i></a>
                                        </li>
                                        <li class="linkedin">
                                            <a href="https://www.linkedin.com/in/lindseyelmore/" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="info">
                            <div class="cards">
                                    <div class="card" data-description="Dr. Lindsey Elmore is a speaker, author, and world-renowned wellness expert. She translates complicated science into understandable stories, and travels the world educating audiences about natural wellness.<br><br>
                                    Dr. Elmore obtained an undergraduate degree in chemistry from the University of Alabama, Birmingham and a doctorate in pharmacy from the University of California San Francisco. She completed her first-year post-doctoral residency in pharmacy practice at Princeton Baptist Medical Center in Birmingham, AL and her second-year specialty residency in ambulatory care at New Hanover Regional Medical Center in Wilmington, NC. She is a Board-Certified Pharmacotherapy Specialist and licensed to practice in three states.  She is a Certified 250-hour vinyasa, yin, and Aroma Yoga® Instructor, an Aroma Freedom practitioner, and the creator of the Clean Slate Cleanse.<br><br>
                                    Dr. Elmore has spoken to audiences on five continents and more than 30 countries. Her educational materials have been translated into more than 25 languages. She is the author of <em>Essentials: 75 Answers to Common Questions about Essential Oils and Supplements</em>, and the <em>Clean Slate Cleanse Cookbook</em> and <em>Workbook </em>series. She is published in a wide variety of pharmacy and medical journals, and has been quoted in Bustle, Elite Daily, Reader’s Digest, Yahoo News, Parade, MSN, Brit + Co, Business Insider and more. She has been featured on news segments on NBC, ABC, CBS, Fox, CW, and the Daily Buzz.<br><br>
                                    Dr. Elmore reaches millions on social media. Her Facebook content reaches more than 85 countries, and more than 15 million minutes of education have been watched on Facebook alone.<br><br>
                                    From all of her travels and diverse learnings, she created The Club, a hub for health, wellness, and business education. She has more than 200 videos on essential oils and supplements inside The Club, teaches exclusive live events online regularly, and constantly creates content requested by her audience. Join Dr. Elmore on her journey: <a href='https://www.facebook.com/lindseyelmore/'>@lindseyelmore</a> on Facebook and Instagram and online at <a href='http://www.lindseyelmore.com'>lindseyelmore.com</a>. You can also follow along with her gluten-free, vegan foodstagram <a href='https://www.insagram.com/cleanslatecleanse/'>@cleanslatecleanse</a> on Instagram and <a href='https://www.facebook.com/thecleanslatecleanse/'>@thecleanslatecleanse</a> on Facebook.</p>">
                                <h4>Lindsey Elmore</h4>
                                <h5>PharmD, BCPS, CYP-250</h5>
                                <button>View Bio →</button>
                                </div></div>
                            </div>
                        </div>
                    </div>
                    <!-- End Single Item -->
                    <!-- Single Item -->
                    <div class="col-md-4 col-sm-6 equal-height">
                        <div class="item">
                            <div class="thumb">
                                <img src="https://functionalmedicinece.com/wp-content/uploads/2019/09/melody_hartzler-scaled.jpg" alt="Thumb">
                                <div class="social">
                                    <ul>
                                        <li class="facebook">
                                            <a href="https://www.facebook.com/PharmtoTable.Life/" target="_blank"><i class="fab fa-facebook-f"></i></a>
                                        </li>
                                        <li class="instagram">
                                            <a href="https://www.instagram.com/pharmtotable.life/" target="_blank"><i class="fab fa-instagram"></i></a>
                                        </li>
                                        <li class="twitter">
                                            <a href="https://twitter.com/DrHartzler" target="_blank"><i class="fab fa-twitter"></i></a>
                                        </li>
                                        <li class="linkedin">
                                            <a href="https://www.linkedin.com/in/melody-hartzler-12667569/" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="info">
                            <div class="cards">
                                    <div class="card" data-description="Dr. Melody L. Hartzler, PharmD, BCACP, BC-ADM is a family medicine clinical pharmacist and Associate professor of pharmacy practice. Dr. Hartzler is a graduate from Ohio Northern Raabe College of Pharmacy (2009).<br><br>
                                    She completed her PGY-1 Pharmacy Practice Residency with an emphasis in Ambulatory Care at the Chalmers P. Wylie VA Ambulatory Care Center in Columbus, OH (2010). Following residency, she joined faculty at Cedarville University School of Pharmacy and developed a collaborative practice in a family medicine residency program.<br><br>
                                    She now serves part-time for Cedarville University School of Pharmacy and part-time as a clinical pharmacist at Western Medicine Providers. Her primary practice interests are diabetes, pulmonology, and integrative/functional medicine. Her passion is recognizing the role of the gut in relationship to chronic disease.<br><br>
                                    Dr. Hartzler has developed an integrative medicine service in her clinical practice in family medicine and also has a blog focused on incorporating this approach into chronic disease statement management. She provides treatment of chronic disease through consult agreements with an integrative approach.<br><br>She also uses her blog <a href='https://pharmtotable.life/'>PharmToTable.Life</a> as a platform to educate patients and health care providers about this practice. She has developed functional medicine services at her current practice and is currently developing telehealth services to meet the growing need.'</p>">
                                <h4>Melody Hartzler</h4>
                                <h5>PharmD, BCACP, BC-ADM</h5>
                                <button>View Bio →</button>
                                </div></div>
                            </div>
                        </div>
                    </div>
                    <!-- End Single Item -->
                    <!-- Single Item -->
                    <div class="col-md-4 col-sm-6 equal-height">
                        <div class="item">
                            <div class="thumb">
                                <img src="https://functionalmedicinece.com/wp-content/uploads/2021/01/vanessalesneski.png" alt="Thumb">
                                <div class="social">
                                    <ul>
                                        <li class="facebook">
                                            <a href="https://www.facebook.com/BrookUFRx" target="_blank"><i class="fab fa-facebook-f"></i></a>
                                        </li>
                                        <li class="linkedin">
                                            <a href="https://www.linkedin.com/in/vanessa-lesneski-pharmd-bcps-cph-12a83296/" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="info">
                                <div class="cards">
                                    <div class="card" data-description="Vanessa Lesneski is an Assistant Professor in Pharmacy Practice at the Lake Erie College of Osteopathic Medicine School of Pharmacy Distance Education pathway. Her areas of expertise include pharmacogenetics, nutrition, and pharmacotherapy. She is the course coordinator for “Functional Pharmacy,” an online elective for second and third year pharmacy students across all three pathways at LECOM, Florida, Erie, and DE.  <br><br>
                                    The elective introduces functional medicine and other complementary and/or alternative therapies.  The instructors include Dr. Lesneski and volunteers who practice in the area of functional medicine or CAM.  The objective of the class is to introduce alternative treatment modalities and encourage the student to objectively and critically “think outside the box.” Vanessa also runs a practice site at Forum Health for pharmacy students to obtain hands on training. ">
                                <h4>Vanessa Lesneski</h4>
                                <h5>PharmD, BCPS, CPh</h5>
                                <button>View Bio →</button>

                                        </div></div>
                            </div>
                        </div>
                    </div>
                    <!-- End Single Item -->
                    <!-- Single Item -->
                    <div class="col-md-2 col-sm-6 equal-height"></div>
                    <div class="col-md-4 col-sm-6 equal-height">
                        <div class="item">
                            <div class="thumb">
                                <img src="https://functionalmedicinece.com/wp-content/uploads/2020/05/LZakaria.jpeg" alt="Thumb">
                                <div class="social">
                                    <ul>
                                        <li class="facebook">
                                            <a href="https://www.facebook.com/foodiefarmacist/" target="_blank"><i class="fab fa-facebook-f"></i></a>
                                        </li>
                                        <li class="instagram">
                                            <a href="https://www.instagram.com/foodiefarmacist/" target="_blank"><i class="fab fa-instagram"></i></a>
                                        </li>
                                        <li class="twitter">
                                            <a href="https://twitter.com/FoodieFarmacist" target="_blank"><i class="fab fa-twitter"></i></a>
                                        </li>
                                        <li class="linkedin">
                                            <a href="https://www.linkedin.com/in/lara-zakaria/" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="info">
                                <div class="cards">
                                    <div class="card" data-description="Lara Zakaria is a Pharmacist and Clinical Nutritionist specializing in Functional Medicine. Combining her background in pharmacy and training in Functional Nutrition, Lara designs personalized protocols that incorporate whole food, herbs, nutrigenomics/pharmacogenomics, medication history, and lifestyle modification to optimize patient health outcomes.<br><br>
                                    Lara’s passion for nutrition and Functional Medicine began when she realized her pharmacy practice was full of patients frustrated with their growing list medications and declining health.<br><br>
                                    After 15 years in community pharmacy, Lara was craving a more holistic approach to patient care. She decided to pursue her passion for nutrition and personalized medicine - first by earning a Master of Science in Clinical Nutrition from the University of Bridgeport, and then pursuing Institute for Functional Medicine (IFM) training and certification.<br><br>
                                    Lara is founder of Foodie Farmacist, LLC , a consulting company focused on delivering patient-centered Functional Medicine care to manage chronic conditions including metabolic and autoimmune disease. Her focus is split between direct patient practice and professional development programs for Functional Medicine professionals.">
                                <h4>Lara Zakaria</h4>
                                <h5>PharmD, MS CNS CDN IFMCP</h5>
                                <button>View Bio →</button>

                                </div></div>
                            </div>
                        </div>
                    </div>
                    <!-- End Single Item -->
                    <!-- Single Item -->
                    <div class="col-md-4 col-sm-6 equal-height">
                        <div class="item">
                            <div class="thumb">
                                <img src="https://pharmtotable.life/wp-content/uploads/2020/06/sarahbachofner.jpg" alt="Thumb">
                                <div class="social">
                                    <ul>
                                        <li class="facebook">
                                            <a href="http://www.facebook.com/drsarahbachofner" target="_blank"><i class="fab fa-facebook-f"></i></a>
                                        </li>
                                        <li class="instagram">
                                            <a href="https://www.instagram.com/drsarahbachofner/" target="_blank"><i class="fab fa-instagram"></i></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="info">
                                <div class="cards">
                                    <div class="card" data-description="Dr. Bachofner’s focus areas are hormone imbalances, mood disorders, and chronic disease management, particularly diabetes. She has always believed in a holistic approach to healthcare and that preventing illness and promoting wellness are crucial to improving health. <br><br>

                                    She provides education and establishes a partnership with the common goal of recovery and wellness. She exudes a positive and joyful approach to patient care. She can’t wait to see the hope in your eyes when you realize you’re about to head down a journey of health toward living your best life!">

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
                                    Dr. Zakaria is super knowledgeable about using a functional medicine approach to achieve optimal health and wellness. <br><br><em>She has a gift for making science easy to understand which is great when talking about stress and hormones.</em> <br><br>The Stress Solution is sure to help so many people and couldn’t have come at a more perfect time!
                                </p>
                            </div>
                            <div class="provider">
                                <div class="thumb">
                                    <img width="150" src="https://pharmtotable.life/wp-content/uploads/2020/06/meganmorrison.jpg" alt="Testimonial-Image">
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
                                    Stress plays such a <strong>HUGE</strong> role in health and <strong>I CANNOT WAIT</strong> to hear what these ladies have to say about it! <br><br><em>I am in awe of their knowledge and passion every time I hear them speak.</em><br><br> Combining their Functional Medicine knowledge with serious stress reduction techniques is sure to get anyone going in the right direction on their health journey.
                                </p>
                            </div>
                            <div class="provider">
                                <div class="thumb">
                                    <img src="https://pharmtotable.life/wp-content/uploads/2020/06/nicolegram.png" alt="Testimonial-Image">
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
    </div></div>
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
                endwhile; endif; ?>
            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End About -->



<?php get_footer(); ?>
<script src="<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/assets/js/click-outside.js"></script>
</body>
</html>
