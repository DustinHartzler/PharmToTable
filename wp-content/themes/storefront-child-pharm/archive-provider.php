<!DOCTYPE html>
<html lang="en">

<head>
    <!-- ========== Meta Tags ========== -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="PharmtoTable - Your Journey To Wellness Through Natural Methods">

    <!-- LogRocket -->
    <script src="https://cdn.lr-ingest.io/LogRocket.min.js" crossorigin="anonymous"></script>
    <script>window.LogRocket && window.LogRocket.init('dgbkxm/pharmtotable');</script>

    <!-- ========== Page Title ========== -->
    <title>PharmToTable | <?php wp_title(''); ?></title>

    <!-- ========== Start Stylesheet ========== -->
    <link href="<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/assets/css/font-awesome.min.css" rel="stylesheet" />
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
                            <?php get_search_form(); ?>
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
                        <li class="quote-btn"><a href="#">Make Appointment</a></li>
                    </ul>
                </div>
                <!-- End Atribute Navigation -->

                <!-- Start Header Navigation -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-menu">
                        <i class="fa fa-bars"></i>
                    </button>
                    <a class="navbar-brand" href="<?php bloginfo('url'); ?>">
                        <img src="<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/assets/img/pharmtotable-logo.png" class="logo" alt="Logo">
                    </a>
                </div>
                <!-- End Header Navigation -->

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="navbar-menu">

                <?php wp_nav_menu( array(
                            'sort_column' => 'menu_order',
                            'theme_location' => 'main',
                            'container' => 'ul',
                            'menu_class' => 'nav navbar-nav navbar-right',
                            'items_wrap'      => '<ul class="%2$s">%3$s</ul>',
                            'walker' => new My_Walker_Nav_Menu(),

                            ) );
                            ?>
                </div><!-- /.navbar-collapse -->
            </div>

            <!-- Start Side Menu -->
            <div class="side">
                <a href="#" class="close-side"><i class="fa fa-times"></i></a>
                <div class="widget">
                    <h4 class="title">About Us</h4>
                    <p>
                        Arrived compass prepare an on as. Reasonable particular on my it in sympathize. Size now easy eat hand how. Unwilling he departure elsewhere dejection at. Heart large seems may purse means few blind.
                    </p>
                </div>
                <div class="widget">
                    <h4 class="title">Our Department</h4>
                    <ul>
                        <li><a href="#">Eye Treatment</a></li>
                        <li><a href="#">Children Chare</a></li>
                        <li><a href="#">Traumatology</a></li>
                        <li><a href="#">X-ray</a></li>
                    </ul>
                </div>
                <div class="widget social">
                    <h4 class="title">Connect With Us</h4>
                    <ul class="link">
                        <li class="facebook"><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                        <li class="twitter"><a href="#"><i class="fab fa-twitter"></i></a></li>
                        <li class="pinterest"><a href="#"><i class="fab fa-pinterest"></i></a></li>
                        <li class="dribbble"><a href="#"><i class="fab fa-dribbble"></i></a></li>
                    </ul>
                </div>
            </div>
            <!-- End Side Menu -->

        </nav>
        <!-- End Navigation -->
        <? wp_head(); ?>
    </header>
    <!-- End Header -->


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
            <h4>Healthcare Professionals are licensed by state. Please click on the state below to find a team member in your state. If we do not have a licensed pharmacist in your state, you can check out our expanded network <a href="https://PharmToTable.Azova.com">here</a>.</h4>
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
                                <a href="<?php echo esc_url( get_the_permalink( $post_id ) ); ?>">
                                    <h4><?php the_title();?></h4>
                                    <h5><?php echo get_post_meta($post->ID, 'provider_credentials', true); ?></h5>
                                </a>
                                <h6>Licensed in: </h6>
                                <?php display_member_taxonomy_terms($post->ID); ?>


                                <div class="appoinment-btn">
                                    <a href="<?php echo get_post_meta($post->ID, 'provider_azova', true); ?>" target="_blank">Make appointment</a>
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

    <!-- Start Newsletter
============================================= -->
<div class="newsletter-area default-padding shadow dark bg-fixed text-center text-light" style="background-image: url(assets/img/2440x1578.png);">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h4>Subscribe For Updates</h4>
                <h2>Let’s Find A Pharmacist Near You.</h2>
                <form action="#">
                    <div class="input-group stylish-input-group">
                        <button type="submit">
                        <a href="https://app.monstercampaigns.com/c/g9aoffuqh8sc1kutmuzw/" target="_blank">
                        Sign Up for Email Updates <i class="fa fa-paper-plane"></i>
                        </a>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Newsletter -->

   <!-- Start Footer
    ============================================= -->
    <footer>
        <div class="container">
            <div class="row">

                <div class="f-items default-padding">
                    <!-- Single Item -->
                    <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("child-footer-1") ) : ?>
                    <?php endif;?>
                    <!-- End Single Item -->
                    <!-- Single Item -->
                    <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("child-footer-2") ) : ?>
                    <?php endif;?>
                    <!-- End Single Item -->
                    <!-- Single Item -->
                    <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("child-footer-3") ) : ?>
                    <?php endif;?>
                    <!-- End Single Item -->
                </div>
            </div>
        </div>
        <!-- Start Footer Bottom -->
        <div class="footer-bottom bg-dark text-light">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <p>&copy; Copyright 2020. All Rights Reserved by <a href="https://pharmtotable.life">PharmToTable</a></p>
                    </div>
                    <div class="col-md-6 text-right link">
                        <ul>
                            <li>
                                <a href="#">Terms of user</a>
                            </li>
                            <li>
                                <a href="#">License</a>
                            </li>
                            <li>
                                <a href="#">Support</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Footer Bottom -->
    </footer>
    <!-- End Footer -->

    <!-- jQuery Frameworks
    ============================================= -->
    <script src="<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/assets/js/jquery-1.12.4.min.js"></script>
    <script src="<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/assets/js/bootstrap.min.js"></script>
    <script src="<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/assets/js/equal-height.min.js"></script>
    <script src="<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/assets/js/jquery.appear.js"></script>
    <script src="<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/assets/js/jquery.easing.min.js"></script>
    <script src="<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/assets/js/jquery.magnific-popup.min.js"></script>
    <script src="<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/assets/js/modernizr.custom.13711.js"></script>
    <script src="<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/assets/js/owl.carousel.min.js"></script>
    <script src="<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/assets/js/wow.min.js"></script>
    <script src="<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/assets/js/isotope.pkgd.min.js"></script>
    <script src="<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/assets/js/imagesloaded.pkgd.min.js"></script>
    <script src="<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/assets/js/count-to.js"></script>
    <script src="<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/assets/js/YTPlayer.min.js"></script>
    <script src="<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/assets/js/jquery.nice-select.min.js"></script>
    <script src="<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/assets/js/bootsnav.js"></script>
    <script src="<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/assets/js/main.js"></script>
<?php wp_footer(); ?>
<!-- This site is converting visitors into subscribers and customers with OptinMonster - https://optinmonster.com -->
<script type="text/javascript" src="https://a.omappapi.com/app/js/api.min.js" data-account="44742" data-user="39038" async></script>
<!-- / https://optinmonster.com -->
</body>
</html>
