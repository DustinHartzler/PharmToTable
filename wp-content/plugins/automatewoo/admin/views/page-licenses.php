<?php
// phpcs:ignoreFile

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @var Admin\Controllers\Base $controller
 * @var bool|\WP_Error $dev_check
 */
?>

<div class="wrap woocommerce automatewoo-page automatewoo-page--licenses">

    <h1><?php echo esc_attr( $controller->get_heading() ) ?></h1>

	<?php $controller->output_messages(); ?>

    <div class="automatewoo-content">

		<?php
		if ( Licenses::has_marketplace_subscription( 'automatewoo' ) ) {
			Marketplace_License_Transition_Helper::output_marketplace_license_prompt_notice( false );
		} else {
			Marketplace_License_Transition_Helper::output_legacy_license_warning_notice();
		}
		?>

		<p><?php wp_kses_post( printf(
				  __( 'In order to receive plugin updates for AutomateWoo extensions you must enter a license key. If you do not have a license key please see <a href="%s" target="_blank">details & pricing</a>.', 'automatewoo' ),
				  Admin::get_marketplace_product_link()
			  ) ); ?></p>


		 <?php if ( is_wp_error( $dev_check ) ): ?>

             <?php Admin::notice( 'error', $dev_check->get_error_message() )?>

		 <?php elseif ( $dev_check ): ?>

           <div class="automatewoo-info-box">
               <span class="dashicons dashicons-info"></span> <strong><?php _e( 'Development Install Detected', 'automatewoo' ) ?></strong> -
				  <?php printf(
					  __( 'Activating this domain will not count against the activation limit of your license. For more info please see <a href="%s" target="_blank">our documentation</a>.', 'automatewoo' ),
					  Admin::get_docs_link('licenses', 'development-detected-notice' )
				  ); ?>
           </div>

		 <?php endif; ?>


        <form method="post" enctype="multipart/form-data">

            <input type="hidden" name="action" value="activate">

			  <?php

			  $list_table = new Admin_Licenses_Table();
			  $list_table->nonce_action = $controller->get_nonce_action();
			  $list_table->prepare_items();
			  $list_table->display();
			  wp_nonce_field( $controller->get_nonce_action() );

			  if ( Licenses::has_unactivated_products() ) {
				  submit_button( __( 'Activate Licenses', 'automatewoo' ), 'button-primary' );
			  }

			  ?>

        </form>

    </div>

</div>

