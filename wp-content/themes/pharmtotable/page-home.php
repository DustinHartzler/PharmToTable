<?php
/*
*Template Name: Home
*/

get_header(); ?>

<!-- Start Banner
============================================= -->
<div class="banner-area">
    <div id="bootcarousel" class="carousel text-center inc-top-heading slide carousel-fade animate_text" data-ride="carousel">
        <!-- Wrapper for slides -->
        <div class="carousel-inner text-light carousel-zoom">
            <div class="item active">
                <div class="slider-thumb bg-cover" style="background-image: url(<?php bloginfo('template_url'); ?>/assets/img/family-1.jpg);"></div>
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
                                        <a data-animation="animated slideInUp" class="btn btn-light border btn-md" href="#">View Details</a>
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
                                <a href="https://www.youtube.com/watch?v=5vY-D42NFP4" class="popup-youtube relative theme video-play-button item-center">
                                    <i class="fa fa-play"></i>
                                </a>
                            </div>
                            <div class="content">
                                <h4>Let’s see our intro video</h4>
                                <p>
                                    If your smile is not becoming to you, then you should be coming to me!
                                </p>
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
                                    <h3>First Step to Thriving</h3>
                                    <p>
                                    Looking to learn more about how our team can meet your health goals? Sign up for our Introduction to Functional Medicine Discovery session. Secure your spot in our next class. </p>
                                    <a class="btn btn-theme border btn-md" href="#">Sign up for the Class</a>
                                </div>
                            </div>
                            <!-- End Single Item -->

                            <!-- Single Item -->
                            <div id="tab2" class="tab-pane fade">
                                <div class="info title">
                                    <h3>Your First Appointment</h3>
                                    <p>
                                    Ready to start your Functional Medicine journey? We want to help you feel better as soon as possible. To do that, we need to understand your whole health story. Book your appointment and get started on your health history forms right away! Forms must be completed at least  hours before your appointment. Book your appointment now.
                                    </p>
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
                        <h2>Centres of <span>Excellence</span></h2>
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
                                <a class="btn btn-theme border circle btn-md" href="#">Read More</a>
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
                                <a class="btn btn-theme border circle btn-md" href="#">Read More</a>
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
                                <a class="btn btn-theme border circle btn-md" href="#">Read More</a>
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
                                <a class="btn btn-theme border circle btn-md" href="#">Read More</a>
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
                                <a class="btn btn-theme border circle btn-md" href="#">Read More</a>
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
                <div class="col-md-6 thumb bg-cover" style="background-image: url(<?php bloginfo('template_url'); ?>/assets/img/consult.jpg);"></div>
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
                                    <img src="<?php bloginfo('template_url'); ?>/assets/img/testimonial-standard.png" alt="Testimonial-Image">
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
                                    <img src="<?php bloginfo('template_url'); ?>/assets/img/testimonial-standard.png" alt="Testimonial-Image">
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
