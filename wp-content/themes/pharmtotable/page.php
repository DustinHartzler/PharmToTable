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
                    <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Pages") ) : ?>
                    <?php endif;?>
                </div>
                <!-- End Widget Items -->

            </div>
        </div>
    </div>
    <!-- End End Services -->

<?php get_footer(); ?>