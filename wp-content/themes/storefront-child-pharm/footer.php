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

	<?php
	do_action( 'storefront_before_footer' ); 
	if ( is_page_template( 'page-14-day-reset.php' ) || is_page_template( 'page-discovery.php' ) || is_page_template( 'page-gut-pdf-download.php' ) ) {
} else {?>
    <!-- Start Newsletter
============================================= -->
<div class="newsletter-area default-padding shadow dark bg-fixed text-center text-light" style="background-image: url(assets/img/2440x1578.png);">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php if ( is_page_template( 'page-landing.php' ) ) { ?>
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
                    <!-- Footer 1 Item -->
					<div class="col-md-4 item">
							<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar("child-footer-1")) : ?>
							<?php endif;?>
					</div>
                    <!-- End Footer 1 Item -->
                    <!-- Footer 2 Item -->
					<div class="col-md-4 item">
						<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar("child-footer-2")) : ?>
						<?php endif;?>
					</div>
                    <!-- End Footer 2 Item -->
                    <!-- Footer 3 Item -->
					<div class="col-md-4 item">
						<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar("child-footer-3")) : ?>
						<?php endif;?>
					</div>
                    <!-- End Footer 3 Item -->
                </div>
            </div>
        </div>
        <?php }?>
	<?php do_action('storefront_after_footer'); ?>

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

<script>
  window.prechatTemplate = {
      "SubmitLabel": "Start Chat",
      "fields": {
"0": {
"error": "Please Enter a valid name",
"fieldId": "name",
"label": "Name",
"required": "yes",
"type": "text"
},
"1": {
"error": "Please Enter a valid Email",
"fieldId": "email",
"label": "Email",
"required": "yes",
"type": "email"
},
"2": {
"error": "Please Enter a valid Phone Number",
"fieldId": "phone",
"label": "Phone",
"required": "yes",
"type": "phone"
},
      },
      "heading": "👋 Chat with Us",
      "mainbgColor": "#003d42",
      "maintxColor": "#fff",
      "textBanner": "We can't wait to talk to you. But first, please take a couple of moments to tell us a bit about yourself."
    };
    window.fcSettings = {
      onInit: function() {
        window.fcPreChatform.fcWidgetInit(window.prechatTemplate);
    }
    };
    window.fcWidgetMessengerConfig = {
      config: {
content: {
actions: {
tab_chat: "Chat"
},
headers: {
chat: "Chat With Us"
}
},
cssNames: {
expanded: "custom_fc_expanded",
widget: "custom_fc_frame"
}
      }
    };
  </script>
  <script src="https://snippets.freshchat.com/js/fc-pre-chat-form-v2.js"></script>
  <script src='//fw-cdn.com/6640648/3233413.js' chat='true'></script>
