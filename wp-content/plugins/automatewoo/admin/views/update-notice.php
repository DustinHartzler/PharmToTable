<?php
/**
 * @var string $notice_identifier
 * @var string $version
 * @var string $description
 * @var string $link
 */

defined( 'ABSPATH' ) || exit;

?>

<div class="notice automatewoo-welcome-notice is-dismissible"
	 data-automatewoo-dismissible-notice="<?php echo esc_attr( $notice_identifier ); ?>">
	<?php // translators: placeholder is AutomateWoo version string ('5.0') ?>
	<h3 class="automatewoo-welcome-notice__heading"><?php echo esc_html( sprintf( __( 'Welcome to AutomateWoo %s!', 'automatewoo' ), $version ) ); ?></h3>
	<div class="automatewoo-welcome-notice__text">
		<p>
			<?php esc_html_e( 'Thank you for updating to the latest version of AutomateWoo.', 'automatewoo' ); ?>
			<?php if ( ! empty( $description ) ) : ?>
				<span> <?php echo wp_kses_post( $description ); ?></span>
			<?php endif; ?>
		</p>
		<?php if ( ! empty( $link ) ) : ?>
			<p><a target="_blank" href="<?php echo esc_url( $link ); ?>"
				  class="button-primary"><?php esc_html_e( "Discover what's new", 'automatewoo' ); ?></a></p>
		<?php endif; ?>
	</div>
	<div class="automatewoo-welcome-notice__robot"></div>
</div>



