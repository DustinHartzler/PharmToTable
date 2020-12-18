<?php

defined( 'ABSPATH' ) || exit;

/**
 * @var array  $widgets
 * @var string $date_text
 * @var string $date_current
 * @var array  $date_tabs
 */

?>

<div class="wrap woocommerce automatewoo-page automatewoo-page--dashboard">

	<div class="automatewoo-dashboard-header">
		<div class="automatewoo-dashboard-date-nav">
			<?php foreach ( $date_tabs as $date_tab_key => $date_tab_text ) : ?>
				<a href="<?php echo esc_url( add_query_arg( 'date', $date_tab_key, AutomateWoo\Admin::page_url( 'dashboard' ) ) ); ?>"
					class="automatewoo-dashboard-date-nav__tab <?php echo ( $date_tab_key === $date_current ? 'automatewoo-dashboard-date-nav__tab--current' : '' ); ?>">
					<?php echo esc_attr( $date_tab_text ); ?>
				</a>
			<?php endforeach; ?>
		</div>

		<h1><?php esc_html_e( 'Dashboard', 'automatewoo' ); ?></h1>
	</div>


	<div class="automatewoo-dashboard-widgets">
		<div class="automatewoo-dashboard-widget-sizer"></div>
		<?php foreach ( $widgets as $widget ) : ?>
			<?php $widget->output(); ?>
		<?php endforeach; ?>
	</div>

</div>

