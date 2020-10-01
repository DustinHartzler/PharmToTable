<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-visual-editor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}
$name_placeholder = '<span class="element-name"></span>';
?>

<div class="tcb-modal-header flex-center space-between">
	<h2 class="tcb-modal-title">
		<?php if ( defined( 'TVE_STAGING_TEMPLATES' ) && TVE_STAGING_TEMPLATES ) : ?>
			<span style="color: #810000"><?php echo __( 'Warning! The templates listed here are only used for testing purposes', 'thrive-cb' ); ?></span>
		<?php else : ?>
			<?php echo sprintf( esc_html( __( 'Choose %s Template', 'thrive-cb' ) ), $name_placeholder ); ?>
		<?php endif ?>
	</h2>
	<span data-fn="clearCache" class="tcb-refresh mr-30 click flex-center">
		<span class="mr-10"><?php tcb_icon( 'sync-regular' ); ?></span>
		<span class="mr-10"><?php echo __( 'Refresh from cloud', 'thrive-cb' ); ?></span>
	</span>
</div>

<div class="error-container tcb-absolute"></div>
<div class="warning-ct-change">
	<div class="tcb-notification info-text">
		<div class="tcb-warning-label"><?php echo __( 'Warning!', 'thrive-cb' ); ?></div>
		<div class="tcb-notification-content"></div>
	</div>
</div>

<div class="tve-templates-wrapper">
	<div class="content-templates" id="cloud-templates"></div>
</div>

<div class="tcb-modal-footer clearfix flex-end p-20">
	<button type="button" class="justify-self-start tve-button medium tcb-modal-back grey" style="display: none"><?php echo __( 'Back', 'thrive-cb' ); ?></button>
	<div class="tcb-confirmation-footer flex-end">
		<span class="tcb-confirmation-message"><?php echo __( 'Please confirm that you want to replace this template', 'thrive-cb' ); ?></span>
		<button type="button" class="tcb-right tve-button medium tcb-modal-save">
			<?php echo __( 'Replace Template', 'thrive-cb' ); ?>
		</button>
	</div>
</div>

