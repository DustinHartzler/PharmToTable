<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-visual-editor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
} ?>

<div id="tve-notification-component" class="tve-component" data-view="Notification">
	<div class="dropdown-header" data-prop="docked">
		<?php echo __( 'Main Options', 'thrive-cb' ); ?>
	</div>
	<div class="dropdown-content">
		<div class="tcb-text-center mb-10 mr-5 ml-5">
			<button class="tve-button orange click" data-fn="edit_notifications">
				<?php echo __( 'Edit design', 'thrive-cb' ); ?>
			</button>
		</div>
		<hr>
		<div class="tve-notification-spacing mb-10">
			<div class="tve-control mt-5 full-width" data-view="DisplayPosition"></div>
			<div class="tve-control mt-5" data-view="VerticalSpacing"></div>
			<div class="tve-control mt-5" data-view="HorizontalSpacing"></div>
		</div>
		<hr>
		<div class="tve-control mt-5" data-view="AnimationDirection"></div>
		<div class="tve-control mt-5" data-view="AnimationTime"></div>
		<hr>
		<div class="click flex-center mt-10 tve-notifications-reset" data-fn="resetStyle">
			<span class="mr-10"><?php tcb_icon( 'sync-regular' ); ?></span>
			<span><?php echo __( 'Reset style to default', 'thrive-cb' ); ?></span>
		</div>
	</div>
</div>
