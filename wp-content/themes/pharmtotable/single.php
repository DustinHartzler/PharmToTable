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
                    <div class="blog-content col-md-8">
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
                                <div class="meta">
                                    <ul>
                                        <li><a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><?php the_author();?></a></li>
                                        <li><?php the_date();?></li>
                                    </ul>
                                </div>
                                <h3><?php the_title(); ?></h3>
                                <?php the_content(); ?>
                                <!-- Start Post Pagination -->
                                <div class="post-pagi-area">
                                <?php
                                    $prev_post = get_adjacent_post(false, '', true);
                                    $next_post = get_adjacent_post(false, '', false);
if(!empty($prev_post)) {
echo '<a href="' . get_permalink($prev_post->ID) . '" title="' . $prev_post->post_title . '"><i class="fas fa-angle-double-left"></i>Previous Post</a>';
}

if(!empty($next_post)) {
echo '<a href="' . get_permalink($next_post->ID) . '" title="' . $next_post->post_title . '">Next Post<i class="fas fa-angle-double-right"></i></a>';
}
else {echo '<a href="#">&nbsp;</a>';}

      ?>



                                </div>
                                <!-- End Post Pagination -->

                                <!-- Start Post Tag s-->
                                <div class="post-tags share">

                                    <div class="tags">
                                        <span>Categories: </span>
                                        <?php the_category(); ?>
                                        <span>Tags: </span><br>
                                        <?php the_tags('', ''); ?>
                                    </div>
                                </div>
                                <!-- End Post Tags -->

                                <!-- Start Author Post -->
                                <div class="author-bio">
                                    <div class="avatar">
                                        <?php echo get_avatar( get_the_author_meta( 'ID' ), 200 ); ?>
                                    </div>
                                    <div class="content">
                                        <p><?php the_author_description(); ?></p>
                                        <h4><?php the_author_posts_link(); ?></h4>
                                    </div>
                                </div>
                                <!-- End Author Post -->

                                <?php endwhile; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Start Sidebar -->
                    <?php get_sidebar(); ?>
                    <!-- End Start Sidebar -->
                </div>
            </div>
        </div>
    </div>
    <!-- End Blog -->

<?php get_footer(); ?>