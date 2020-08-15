<?php get_header(); ?>

<!-- Start Breadcrumb
    ============================================= -->
    <div class="breadcrumb-area shadow dark bg-fixed text-light" style="background-image: url(assets/img/2440x1578.png);">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h1>Blog Archives</h1>
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
    <div class="blog-area full-blog left-sidebar default-padding">
        <div class="container">
            <div class="row">
                <div class="blog-items">
                    <div class="blog-content col-md-8">
                        <?php while ( have_posts() ) : the_post(); ?>

                        <!-- Single Item -->
                        <div class="single-item item">
                            <div class="thumb">
                                    <?php the_post_thumbnail( array( 600, 600 ) ); ?>
                                    <div class="post-type">
                                        <i class="fas fa-images"></i>
                                    </div>
                            </div>
                            <div class="info">
                                <div class="meta">
                                    <ul>
                                        <li><a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><?php the_author();?></a></li>
                                        <li><?php the_date();?></li>
                                    </ul>
                                </div>
                                <h3>
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h3>
                                <p>
                                    <?php the_excerpt(); ?>
                                </p>
                                <a class="btn btn-theme circle border btn-sm" href="<?php the_permalink(); ?>">Read More <i class="fas fa-angle-right"></i></a>
                            </div>
                        </div>
                        <!-- End Single Item -->
                        <?php endwhile; ?>
                        <div class="row">
                            <div class="col-md-12 pagi-area">
                                <nav aria-label="navigation">
                                    <?php wpex_pagination(); ?>
                                </nav>
                            </div>
                        </div>

                    </div>
                    <?php get_sidebar(); ?>
                </div>
            </div>
        </div>
    </div>
    <!-- End Blog -->

<?php get_footer(); ?>