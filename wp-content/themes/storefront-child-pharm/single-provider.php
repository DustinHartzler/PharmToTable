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

<!-- Facebook Pixel Code -->
    <script>
      !function(f,b,e,v,n,t,s)
      {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
      n.callMethod.apply(n,arguments):n.queue.push(arguments)};
      if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
      n.queue=[];t=b.createElement(e);t.async=!0;
      t.src=v;s=b.getElementsByTagName(e)[0];
      s.parentNode.insertBefore(t,s)}(window, document,'script',
      'https://connect.facebook.net/en_US/fbevents.js');
      fbq('init', '3168022206626913');
      fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
      src="https://www.facebook.com/tr?id=432268807399441&ev=PageView&noscript=1"
    /></noscript>
<!-- End Facebook Pixel Code -->

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-98971040-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-98971040-1');
</script>
<!-- End Google Analytics -->

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

            <div class="container">

                <!-- Start Atribute Navigation -->
                <div class="attr-nav">
                    <ul>
                        <li class="quote-btn"><a href="<?php bloginfo('url'); ?>/provider">Make Appointment</a></li>
                    </ul>
                </div>
                <!-- End Atribute Navigation -->

                <!-- Start Header Navigation -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-menu">
                        <i class="fa fa-bars"></i>
                    </button>
                    <a class="navbar-brand" href="<?php bloginfo('url'); ?>">
                        <img src="<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/assets/img/logo-pharmtotable.png" class="logo" alt="Logo">
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

        </nav>
        <!-- End Navigation -->
        <? wp_head(); ?>
    </header>
    <!-- End Header -->

<?php
        // Start the loop.
        while ( have_posts() ) : the_post(); ?>

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
                        <li><a href="#"><i class="fas fa-home"></i> Home</a></li>
                        <li class="active">Providers</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- End Breadcrumb -->

    <!-- Start Doctors Details
    ============================================= -->
    <div class="doctor-details-area default-padding">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="thumb">
                        <?php the_post_thumbnail( array( 360, 360 ) ); ?>
                        <div class="overlay">
                        <?php   $facebook   = get_post_meta( $post->ID, 'provider_facebook', true );
                                $instagram  = get_post_meta( $post->ID, 'provider_instagram', true );
                                $twitter    = get_post_meta( $post->ID, 'provider_twitter', true );
                                $linkedin   = get_post_meta( $post->ID, 'provider_linkedin', true );
                                $pinterest  = get_post_meta( $post->ID, 'provider_pinterest', true );
                                $youtube    = get_post_meta( $post->ID, 'provider_youtube', true );
                                $package    = get_post_meta( $post->ID, 'provider_azova_package', true );
                                ?>
                            <ul>
                                <?php if( !empty ( $facebook ) ){ ?>
                                    <li><a href="<?php echo get_post_meta($post->ID, 'provider_facebook', true); ?>"><i class="fab fa-facebook-f" aria-hidden="true"></i></a></li>
                                <?php } ?>
                                <?php if( !empty ( $instagram ) ){ ?>
                                    <li><a href="<?php echo get_post_meta($post->ID, 'provider_instagram', true); ?>"><i class="fab fa-instagram" aria-hidden="true"></i></a></li>
                                <?php } ?>
                                <?php if( !empty ( $linkedin ) ){ ?>
                                    <li><a href="<?php echo get_post_meta($post->ID, 'provider_linkedin', true); ?>"><i class="fab fa-linkedin" aria-hidden="true"></i></a></li>
                                <?php } ?>
                                <?php if( !empty ( $twitter ) ){ ?>
                                    <li><a href="<?php echo get_post_meta($post->ID, 'provider_twitter', true); ?>"><i class="fab fa-twitter" aria-hidden="true"></i></a></li>
                                <?php } ?>
                                <?php if( !empty ( $pinterest ) ){ ?>
                                    <li><a href="<?php echo get_post_meta($post->ID, 'provider_pinterest', true); ?>"><i class="fab fa-pinterest" aria-hidden="true"></i></a></li>
                                <?php } ?>
                                <?php if( !empty ( $youtube ) ){ ?>
                                    <li><a href="<?php echo get_post_meta($post->ID, 'provider_youtube', true); ?>"><i class="fab fa-youtube" aria-hidden="true"></i></a></li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="info">
                        <h2><?php the_title();?></h2>
                        <h4><?php echo get_post_meta($post->ID, 'provider_credentials', true); ?>
                        <?php   // Get terms for post
 $terms = get_the_terms( $post->ID , 'state_category' );
 // Loop over each item since it's an array
 if ( $terms != null ){
 foreach( $terms as $term ) {
 // Print the name method from $term which is an OBJECT
 //print $term->slug ; echo ', ';
 // Get rid of the other data stored in the object, since it's not needed
 unset($term);
} } ?> </h4>
                        <?php the_content(); ?>
                        <a class="btn btn-theme border btn-md" href="<?php echo get_post_meta($post->ID, 'provider_azova', true); ?>" target="_blank">Make Appointment</a>
                        <?php if( !empty ( $package ) ){ ?>
                            <a class="btn btn-theme border btn-md" href="<?php echo get_post_meta($post->ID, 'provider_azova_package', true); ?>" target="_blank">Book a Package</a>
                        <?php } ?>

                         <!-- Tab Nav -->
                        <ul class="nav nav-pills">
                            <li class="active">
                                <a data-toggle="tab" href="#tab1" aria-expanded="false">
                                    Professional Bio
                                </a>
                            </li>
                            <li>
                                <a data-toggle="tab" href="#tab2" aria-expanded="false">
                                    Personal Bio
                                </a>
                            </li>
                        </ul>
                        <!-- End Tab Nav -->
                        <!-- Start Tab Content -->
                        <div class="tab-content tab-content-info">
                            <!-- Single Item -->
                            <div id="tab1" class="tab-pane fade active in">
                                <div class="info title">
                                    <?php echo get_post_meta($post->ID, 'provider_pro_bio', true); ?>
                                </div>
                            </div>
                            <!-- End Single Item -->

                            <!-- Single Item -->
                            <div id="tab2" class="tab-pane fade">
                                <div class="info title">
                                    <?php echo get_post_meta($post->ID, 'provider_personal_bio', true); ?>
                                </div>
                            </div>
                            <!-- End Single Item -->

                        </div>
                        <!-- End Tab Content -->
                    </div>
                </div>
            </div>
        </div>

        <?php

// End of the loop.
endwhile;
?>
    </div>
    <!-- End Doctor Details -->

<?php get_footer(); ?>
