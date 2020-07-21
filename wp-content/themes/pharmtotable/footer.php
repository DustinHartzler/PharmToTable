   <!-- Start Footer
    ============================================= -->
    <footer>
        <div class="container">
            <div class="row">

                <div class="f-items default-padding">
                    <!-- Single Item -->
                    <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Footer 1") ) : ?>
                    <?php endif;?>
                    <!-- End Single Item -->
                    <!-- Single Item -->
                    <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Footer 2") ) : ?>
                    <?php endif;?>
                    <!-- End Single Item -->
                    <!-- Single Item -->
                    <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Footer 3") ) : ?>
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
    <script src="<?php bloginfo('template_url'); ?>/assets/js/jquery-1.12.4.min.js"></script>
    <script src="<?php bloginfo('template_url'); ?>/assets/js/bootstrap.min.js"></script>
    <script src="<?php bloginfo('template_url'); ?>/assets/js/equal-height.min.js"></script>
    <script src="<?php bloginfo('template_url'); ?>/assets/js/jquery.appear.js"></script>
    <script src="<?php bloginfo('template_url'); ?>/assets/js/jquery.easing.min.js"></script>
    <script src="<?php bloginfo('template_url'); ?>/assets/js/jquery.magnific-popup.min.js"></script>
    <script src="<?php bloginfo('template_url'); ?>/assets/js/modernizr.custom.13711.js"></script>
    <script src="<?php bloginfo('template_url'); ?>/assets/js/owl.carousel.min.js"></script>
    <script src="<?php bloginfo('template_url'); ?>/assets/js/wow.min.js"></script>
    <script src="<?php bloginfo('template_url'); ?>/assets/js/isotope.pkgd.min.js"></script>
    <script src="<?php bloginfo('template_url'); ?>/assets/js/imagesloaded.pkgd.min.js"></script>
    <script src="<?php bloginfo('template_url'); ?>/assets/js/count-to.js"></script>
    <script src="<?php bloginfo('template_url'); ?>/assets/js/YTPlayer.min.js"></script>
    <script src="<?php bloginfo('template_url'); ?>/assets/js/jquery.nice-select.min.js"></script>
    <script src="<?php bloginfo('template_url'); ?>/assets/js/bootsnav.js"></script>
    <script src="<?php bloginfo('template_url'); ?>/assets/js/main.js"></script>
<?php wp_footer(); ?>
</body>
</html>