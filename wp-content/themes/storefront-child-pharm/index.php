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
                        <li class="search"><a href="#"><i class="fa fa-search"></i></a></li>
                        <li class="quote-btn"><a href="#">Make Appoinment</a></li>
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
