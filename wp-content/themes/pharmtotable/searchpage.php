<?php
/*
Template Name: Search Page
*/
get_header(); ?>


       <!-- Start Breadcrumb
    ============================================= -->
    <div class="breadcrumb-area shadow dark bg-fixed text-light" style="background-image: url(assets/img/2440x1578.png);">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h1>Search Results</h1>
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

                    <?php get_search_form(); ?>
                </div>
                <!-- End Services Content -->



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