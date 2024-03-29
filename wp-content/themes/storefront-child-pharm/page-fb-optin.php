<?php
/*
*Template Name: Facebook Ad Opt-In
*/
 ?>

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

	<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-WMQK3FB');</script>
<!-- End Google Tag Manager -->

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

    <?php wp_head(); ?>
</head>

<body>
	<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WMQK3FB"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

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

                </div>
                <!-- End Atribute Navigation -->

                <!-- Start Header Navigation -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-menu">
                        <i class="fa fa-bars"></i>
                    </button>
                    <a class="navbar-brand">
                        <img src="<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/assets/img/logo-pharmtotable.png" class="logo" alt="Logo">
                    </a>
                </div>
                <!-- End Header Navigation -->


            </div>

        </nav>
        <!-- End Navigation -->
        <? wp_head(); ?>
    </header>
    <!-- End Header -->



 <!-- Start Page Block
============================================= -->
<div class="services-details-area default-padding">
    <div class="container">
        <div class="row">
            <!-- Page Content -->
            <div class="col-md-12 content">

            <?php
                if (have_posts()) : while (have_posts()) : the_post();
                the_content();
                endwhile; endif; ?>
            </div>
            <!-- End Page Content -->
        </div>
    </div>
</div>
<!-- End Page Block -->

<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package storefront
 */

?>

		</div><!-- .col-full -->
	</div><!-- #content -->

    <?php do_action( 'storefront_before_footer' ); ?>

    <!-- Start Newsletter
============================================= -->
<div class="newsletter-area default-padding shadow dark bg-fixed text-center text-light" style="background-image: url(assets/img/2440x1578.png);">
    <div class="container">
        <div class="row">

        </div>
    </div>
</div>
<!-- End Newsletter -->

<footer>
    <div class="container">
            <div class="row">
            </div>
        </div>

	<?php do_action( 'storefront_after_footer' ); ?>

</div><!-- #page -->

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

<script src="<?php echo get_theme_root_uri(); ?>/storefront-child-pharm/assets/js/click-outside.js"></script>
<script type="text/javascript">
_linkedin_partner_id = "4910812";
window._linkedin_data_partner_ids = window._linkedin_data_partner_ids || [];
window._linkedin_data_partner_ids.push(_linkedin_partner_id);
</script><script type="text/javascript">
(function(l) {
if (!l){window.lintrk = function(a,b){window.lintrk.q.push([a,b])};
window.lintrk.q=[]}
var s = document.getElementsByTagName("script")[0];
var b = document.createElement("script");
b.type = "text/javascript";b.async = true;
b.src = "https://snap.licdn.com/li.lms-analytics/insight.min.js";
s.parentNode.insertBefore(b, s);})(window.lintrk);
</script>
<noscript>
<img height="1" width="1" style="display:none;" alt="" src="https://px.ads.linkedin.com/collect/?pid=4910812&fmt=gif" />
</noscript>

</body>
</html>
