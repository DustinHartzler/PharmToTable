<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-visual-editor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}
?>
<div class="error-container"></div>
<div class="tve-modal-content">
	<div id="cb-cloud-menu">
		<div class="fixed top">
			<div class="lp-search">
				<input type="text" data-source="search" class="keydown" data-fn="filter" placeholder="<?php echo esc_html__( 'Search', 'thrive-cb' ); ?>"/>
				<?php tcb_icon( 'search-regular' ); ?>
				<?php tcb_icon( 'close2', false, 'sidebar', 'click', array( 'data-fn' => 'domClearSearch' ) ); ?>
			</div>
		</div>
		<div class="lp-menu-wrapper fixed mt-10">
			<span><?php echo __( 'Type', 'thrive-cb' ); ?></span>
			<div id="types-wrapper" class="mt-10"></div>
		</div>
	</div>
	<div id="cb-cloud-templates">
		<div class="warning-ct-change no-margin">
			<div class="tcb-notification info-text">
				<div class="tcb-warning-label"><?php echo __( 'Warning!', 'thrive-cb' ); ?></div>
				<div class="tcb-notification-content"></div>
			</div>
		</div>
		<div class="tcb-modal-header flex-center space-between">
			<div id="cb-pack-title" class="mb-5"><?php echo __( 'Forms', 'thrive-cb' ) ?></div>
			<span data-fn="clearCache" class="tcb-refresh mr-30 click flex-center">
				<span class="mr-10"><?php tcb_icon( 'sync-regular' ); ?></span>
				<span class="mr-10"><?php echo __( 'Refresh from cloud', 'thrive-cb' ); ?></span>
			</span>
		</div>
		<div id="cb-pack-content"></div>
	</div>
</div>
