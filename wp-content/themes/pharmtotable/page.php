<?php get_header(); ?>


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

    <!-- Start Services Details
    ============================================= -->
    <div class="services-details-area default-padding">
        <div class="container">
            <div class="row">
                <!-- Services Content -->
                <div class="col-md-8 content">

                <?php
        if (have_posts()) :
            while (have_posts()) :
               the_post();
                  the_content();
            endwhile;
         endif; ?>
                </div>
                <!-- End Services Content -->

                <!-- Widget Items -->
                <div class="col-md-4 sidebar">
                    <!-- Single Widget -->
                    <div class="widget appoinment">
                        <div class="title">
                            <h4>Make an Appointment</h4>
                        </div>
                        <div class="appoinment-box">
                            <form action="#">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <input class="form-control" id="name" name="name" placeholder="Name" type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <select>
                                                <option value="1">Male</option>
                                                <option value="2">Female</option>
                                                <option value="3">Child</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <select>
                                                <option value="1">Department</option>
                                                <option value="2">Medecine</option>
                                                <option value="4">Dental Care</option>
                                                <option value="5">Traumatology</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group comments">
                                            <textarea class="form-control" id="comments" name="comments" placeholder="Your Message"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <button type="submit" name="submit" id="submit">
                                            Submit Query <i class="fa fa-paper-plane"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- End Single Widget -->

                    <!-- Single Widget -->
                    <div class="widget doctor text-center">
                        <div class="title">
                            <h4>Expert Doctor</h4>
                        </div>
                        <div class="thumb">
                            <img src="assets/img/800x800.png" alt="Thumb">
                            <div class="overlay">
                                <h4>Jessica Jones</h4>
                                <h5>Cargiologist</h5>
                            </div>
                        </div>
                    </div>
                    <!-- End Single Widget -->

                    <!-- Single Widget -->
                    <div class="widget link">
                        <div class="title">
                            <h4>Our Departments</h4>
                        </div>
                        <ul>
                            <li>
                                <a href="#"><i class="fas fa-angle-right"></i> Medecine and Health</a>
                            </li>
                            <li>
                                <a href="#"><i class="fas fa-angle-right"></i> Dental Care and Surgery</a>
                            </li>
                            <li>
                                <a href="#"><i class="fas fa-angle-right"></i> Eye Treatment</a>
                            </li>
                            <li>
                                <a href="#"><i class="fas fa-angle-right"></i> Children Chare</a>
                            </li>
                            <li>
                                <a href="#"><i class="fas fa-angle-right"></i> Nuclear magnetic</a>
                            </li>
                        </ul>
                    </div>
                    <!-- End Single Widget -->

                    <!-- Single Widget -->
                    <div class="widget opening-hours">
                        <div class="title">
                            <h4>Opening Hours</h4>
                        </div>
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
                    <!-- End Single Widget -->
                </div>
                <!-- End Widget Items -->

            </div>
        </div>
    </div>
    <!-- End End Services -->

    <!-- Start Newsletter
    ============================================= -->
    <div class="newsletter-area default-padding shadow dark bg-fixed text-center text-light" style="background-image: url(assets/img/2440x1578.png);">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h4>Subscribe For Get Update</h4>
                    <h2>Let’s Find An Office Near You.</h2>
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
        </div>
    </div>
    <!-- End Newsletter -->

<?php get_footer(); ?>