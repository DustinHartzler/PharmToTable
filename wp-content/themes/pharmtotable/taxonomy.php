<?php get_header(); ?>

<!-- Start Breadcrumb
============================================= -->
<div class="breadcrumb-area shadow dark bg-fixed text-light" style="background-image: url(assets/img/2440x1578.png);">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h1>Meet the Team</h1>
            </div>
            <div class="col-md-6 text-right">
                <ul class="breadcrumb">
                    <li><a href="<?php echo get_home_url(); ?>"><i class="fas fa-home"></i> Home</a></li>
                    <li class="active">Meet the Team</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- End Breadcrumb -->

<!-- Start Doctors
    ============================================= -->
    <div class="doctor-area bg-gray default-padding bottom-less">
        <div class="container">
            <div class="row">
                <div class="post-tags">
                    Filter by State:
                    <ul class="post-categories">
                        <?php
                    $args = array( 'hide_empty=0' );

                    $terms = get_terms( 'state_category', $args );
                    if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                        $count = count( $terms );
                        $i = 0;

                        foreach ( $terms as $term ) {
                            $i++;
                            $term_list .= '<li><a href="' . esc_url( get_term_link( $term ) ) . '" alt="' . esc_attr( sprintf( __( 'View all providers in %s', 'my_localization_domain' ), $term->name ) ) . '">' . $term->name . '</a></li>';
                        }
                        echo $term_list;
                    }
                    ?>
                    </ul>
                </div>
                <div class="doctor-items text-center">
                    <!-- Single Item -->
                    <?php while ( have_posts() ) : the_post(); ?>

                    <?php   $facebook   = get_post_meta( $post->ID, 'provider_facebook', true );
                            $instagram  = get_post_meta( $post->ID, 'provider_instagram', true );
                            $twitter    = get_post_meta( $post->ID, 'provider_twitter', true );
                            $linkedin   = get_post_meta( $post->ID, 'provider_linkedin', true );
                            $pinterest  = get_post_meta( $post->ID, 'provider_pinterest', true );
                            $youtube    = get_post_meta( $post->ID, 'provider_youtube', true );

                                ?>
                    <div class="col-md-4 col-sm-6 equal-height">
                        <div class="item">
                            <div class="thumb">
                                <?php the_post_thumbnail( array( 360, 360 ) ); ?>
                                <div class="overlay">
                                    <a href="<?php echo esc_url( get_the_permalink( $post_id ) ); ?>"><i class="fas fa-plus"></i></a>
                                </div>
                                <div class="social">
                                    <ul>
                                        <?php if( !empty ( $facebook ) ){ ?>
                                            <li class="facebook"><a href="<?php echo get_post_meta($post->ID, 'provider_facebook', true); ?>"><i class="fab fa-facebook-f"></i></a></li>
                                        <?php } ?>
                                        <?php if( !empty ( $instagram ) ){ ?>
                                            <li class="instagram"><a href="<?php echo get_post_meta($post->ID, 'provider_instagram', true); ?>"><i class="fab fa-instagram"></i></a></li>
                                        <?php } ?>
                                        <?php if( !empty ( $linkedin ) ){ ?>
                                        <li class="linkedin"><a href="<?php echo get_post_meta($post->ID, 'provider_linkedin', true); ?>"><i class="fab fa-linkedin-in"></i></a></li>
                                        <?php } ?>
                                        <?php if( !empty ( $twitter ) ){ ?>
                                        <li class="twitter"><a href="<?php echo get_post_meta($post->ID, 'provider_twitter', true); ?>"><i class="fab fa-twitter"></i></a></li>
                                        <?php } ?>
                                        <?php if( !empty ( $pinterest ) ){ ?>
                                        <li class="pinterest"><a href="<?php echo get_post_meta($post->ID, 'provider_pinterest', true); ?>"><i class="fab fa-pinterest"></i></a></li>
                                        <?php } ?>
                                        <?php if( !empty ( $youtube ) ){ ?>
                                        <li class="youtube"><a href="<?php echo get_post_meta($post->ID, 'provider_youtube', true); ?>"><i class="fab fa-youtube"></i></a></li>
                                        <?php } ?>


                                    </ul>
                                </div>
                            </div>

                            <div class="info">
                                <h4><?php the_title();?></h4>
                                <h5><?php echo get_post_meta($post->ID, 'provider_credentials', true); ?></h5>
                                <h6>Licensed in: </h6>
                                <?php display_member_taxonomy_terms($post->ID); ?>


                                <div class="appoinment-btn">
                                    <a href="<?php echo get_post_meta($post->ID, 'provider_azova', true); ?>" target="_blank">Make appoinment</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                    <!-- End Single Item -->
                </div>
            </div>
        </div>
    </div>
    <!-- End Doctors -->

<?php get_footer(); ?>