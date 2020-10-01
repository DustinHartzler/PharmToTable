<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden
}

$widgets = tcb_elements()->get_external_widgets();

?>
<div id="tve-widget-component" class="tve-component" data-view="Widget">
	<div class="dropdown-header" data-prop="docked">
		<?php echo __( 'Widget', 'thrive-cb' ); ?>
		<i></i>
	</div>
	<div class="dropdown-content">
		<?php foreach ( $widgets as $widget ) : ?>
			<div id="<?php echo 'widget_' . $widget->id_base; ?>" class="widget-form" data-name="<?php echo $widget->name; ?>">
				<?php
				echo tcb_template( 'widget-form.php', array(
					'widget'    => $widget,
					'form_data' => array(),
				), true );
				?>
			</div>
		<?php endforeach; ?>
	</div>
</div>
