<?php get_header(); ?>

<!-- Start Breadcrumb
============================================= -->
<div class="breadcrumb-area shadow dark bg-fixed text-light" style="background-image: url(assets/img/2440x1578.png);">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h1>Blog</h1>
            </div>
            <div class="col-md-6 text-right">
                <ul class="breadcrumb">
                    <li><a href="#"><i class="fas fa-home"></i> Home</a></li>
                    <li class="active">Blog</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- End Breadcrumb -->


<!-- Start Blog
    ============================================= -->
    <div class="blog-area single full-blog left-sidebar default-padding">
        <div class="container">
            <div class="row">
                <div class="blog-items">
                    <div class="blog-content col-md-12">
                    <?php while ( have_posts() ) : the_post(); ?>
                        <div class="item">

                            <!-- Start Post Thumb -->
                            <div class="thumb">
                                <?php the_post_thumbnail( array( 600, 600 ) ); ?>
                                <div class="post-type">
                                    <i class="fas fa-images"></i>
                                </div>
                            </div>
                            <!-- Start Post Thumb -->

                            <div class="info">

                                <h3><?php the_title(); ?></h3>
                                <?php the_content(); ?>




                                <?php endwhile; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Blog -->

<?php get_footer(); ?>