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
            <div class="col-md-12">
                <?php if (is_page_template( 'page-landing.php' )) { ?>
                    <h2>Ready to Start Your Journey?</h2>
                    <form action="#">
                    <button type="submit">
                        <a href="/provider" >
                        Schedule an Appointment
                        </a>
                        </button>
                        </form>
                <?php } else {?>
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
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<!-- End Newsletter -->

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
