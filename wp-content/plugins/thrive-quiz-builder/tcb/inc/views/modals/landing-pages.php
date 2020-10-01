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
<div style="position: absolute;top: 0;" id="lp-lightbox-drop-panels"></div>
<div class="tcb-modal-step" data-step="1">
	<div class="lp-cloud-menu">
		<div class="lp-search">
			<?php tcb_icon( 'search-regular' ); ?>
			<input type="text" data-source="search" class="keydown" data-fn="filter" placeholder="<?php echo esc_html__( 'Search', 'thrive-cb' ); ?>"/>
			<?php tcb_icon( 'close2', false, 'sidebar', 'click', array( 'data-fn' => 'domClearSearch' ) ); ?>
		</div>
		<div class="lp-menu-wrapper">
			<div class="mt-30">
				<div class="lp-side-title">
					<p><?php echo __( 'Default Templates', 'thrive-cb' ); ?></p>
					<span class="tcb-hl"></span>
				</div>
				<div id="lp-default-filters"></div>
			</div>
			<div class="mt-30">
				<div class="lp-side-title">
					<p><?php echo __( 'My Templates', 'thrive-cb' ); ?></p>
					<span class="tcb-hl"></span>
				</div>
				<div id="lp-saved-filters"></div>
			</div>
		</div>
	</div>
	<div class="lp-cloud-templates">
		<div class="lp-template-title-text ml-10 space-between">
			<span class="tcb-modal-title">
				<?php if ( defined( 'TVE_STAGING_TEMPLATES' ) && TVE_STAGING_TEMPLATES ) : ?>
					<span style="color: #810000"><?php echo __( 'Warning! The templates listed here are only used for testing purposes', 'thrive-cb' ); ?></span>
				<?php else : ?>
					<?php echo __( 'Landing Page Library', 'thrive-cb' ); ?>
				<?php endif; ?>
			</span>
				<span data-fn="clearCache" class="tcb-refresh mr-30 click flex-center">
					<span class="mr-10"><?php tcb_icon( 'sync-regular' ); ?></span>
					<span class="mr-10"><?php echo __( 'Refresh from cloud', 'thrive-cb' ); ?></span>
				</span>
		</div>
		<div id="lp-set-list" class="mt-20"></div>
	</div>
</div>
<div class="tcb-modal-step" data-step="2" style="display: none;">
	<div class="lp-cloud-menu">
		<div class="lp-menu-wrapper">
			<div class="lp-side-title mt-10">
				<p class="set-name"></p>
				<span class="tcb-hl"></span>
			</div>
			<div class="set-filters"></div>
		</div>
	</div>
	<div class="lp-cloud-templates">
		<div class="lp-modal-back ml-10 click" data-fn="domGoToSetsView">
			<span>
				<?php tcb_icon( 'chevron-circle-left-light', false, 'sidebar' ); ?>
			</span>
			<span>
				<?php echo __( 'BACK to library', 'thrive-cb' ); ?>
			</span>
		</div>
		<div class="lp-template-title-text mt-20 ml-10">
			<span class="set-name"></span>
			<i></i>
			<span class="set-full lp-locked"><?php echo __( 'Upgrade', 'thrive-cb' ); ?></span>
			<?php
			/**
			 * Allows other plugins / themes to insert icons here
			 */
			do_action( 'tcb_extra_landing_page_lightbox_icons' );
			?>
			<span><?php tcb_icon( 'cubes-light', false, 'sidebar', 'set-blocks' ); ?></span>
		</div>
		<div id="lp-set-tpl-list" class="mt-20"></div>
	</div>
	<div class="lp-footer">
		<button class="tve-btn tve-button click green click" data-fn="save"><?php echo __( 'Apply Template', 'thrive-cb' ); ?></button>
	</div>
</div>
