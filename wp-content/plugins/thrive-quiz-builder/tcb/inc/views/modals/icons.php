<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package TCB2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden
}
?>

<div class="tve-modal-content">
	<div id="cb-cloud-menu">
		<div class="fixed top">
			<div class="icons-input">
				<?php tcb_icon( 'search-regular' ); ?>
				<input type="text" class="tve-search-icon keyup click" data-fn="searchIcon" data-fn-click="focusSearch" data-source="search" placeholder="<?php echo esc_html__( 'Search', 'thrive-cb' ); ?>"/>
				<?php tcb_icon( 'close2', false, 'sidebar', 'click tcb-hidden', array( 'data-fn' => 'domClearSearch' ) ); ?>
			</div>
		</div>
		<div class="icons-menu-wrapper">
			<div class="icons-label-wrapper p-10 pl-20">
				<span class="icons-label"><?php echo __( 'Icon style', 'thrive-cb' ); ?></span>
				<span class="separator"></span>
			</div>
			<div class="tve-icon-styles tve-icon-filters">
				<div class="tve-icon-pack click mt-5 tve-selected tve-icon-default-style" data-fn="filterByStyle"><span><?php echo __( 'All ', 'thrive-cb' ) ?></span></div>
			</div>
			<div class="icons-label-wrapper p-10 pl-20">
				<span class="icons-label"><?php echo __( 'Icon packs', 'thrive-cb' ); ?></span>
				<span class="separator"></span>
			</div>
			<div class="tve-icon-packs tve-icon-filters">

			</div>
		</div>
		<div class="fixed bottom mt-10 mb-10">
			<div class="tve-icon-settings click pr-10 mb-10 mt-10 pl-20" data-fn="showSettings">
				<hr>
				<div class="pt-10 pb-10">
					<?php tcb_icon( 'cog-light' ); ?>
					<span class="icons-label ml-10"><?php echo __( 'Manage icon packs', 'thrive-cb' ) ?></span>
				</div>
				<hr>
			</div>
		</div>
	</div>
	<div id="tve-icon-content">
		<span class="icons-title pl-10"><?php echo __( 'Icon library', 'thrive-cb' ); ?></span>
		<div class="choose-icon mr-30 pl-10">
			<span><?php echo __( 'Choose an icon', 'thrive-cb' ); ?></span>
			<span class="tve-icons-number"></span>
		</div>
		<div id="icon-pack-content" class="mt-10">
			<div class="tve-icons-wrapper pt-5">
				<div class="tve-icons-before" style="height: 4000px;"></div>
				<div class="tve-icons-rendered"></div>
				<div class="tve-icons-after"></div>
			</div>
		</div>
		<div class="tcb-modal-footer clearfix flex-end">
			<button type="button" class="tcb-right tve-button medium tcb-modal-save m-20">
				<?php echo __( 'Select', 'thrive-cb' ); ?>
			</button>
		</div>
	</div>
	<div id="tve-icon-settings" class="tcb-hidden">
		<div class="tve-fa-pro-settings">
			<span class="icons-title mb-35"><?php echo __( 'Font Awesome Pack', 'thrive-cb' ); ?></span>
			<span>
				<?php echo __( 'To enable Font Awesome Pro icons, paste your kit ID or script below. Once your kit has been accepted, your new icons will be available under the matching filters.', 'thrive-cb' ); ?>
			</span>
			<a href="https://thrivethemes.com/tkb_item/how-to-add-font-awesome-pro-icons-in-thrive-architect/" target="_blank" class="mt-15 mb-15"><?php echo __( 'Learn how to use Font Awesome Pro here', 'thrive-cb' ); ?></a>

			<div class="icons-input white tve-fa-input pr-0">
				<input type="text" class="change input tve-fa-kit" data-fn="toggleProSettings" placeholder="<?php echo __( 'Add your Font Awesome Pro Kit', 'thrive-cb' ); ?>">
				<?php tcb_icon( 'check-regular', false, 'sidebar', 'click tcb-hidden kit-action', array( 'data-fn' => 'handlePro' ) ); ?>
				<?php tcb_icon( 'sync-regular', false, 'sidebar', 'click tcb-hidden kit-action', array( 'data-fn' => 'handlePro' ) ); ?>
				<?php tcb_icon( 'trash-alt-light', false, 'sidebar', 'click tcb-hidden kit-action', array( 'data-fn' => 'toggleDeletePro' ) ); ?>
			</div>
			<div class="icons-input tve-fa-warning tcb-hidden pr-0">
				<span><?php echo __( 'Are you sure you want to delete your kit?', 'thrive-cb' ); ?></span>
				<span class="mr-10 click kit-action" data-fn="clearFAPro"><?php echo __( 'YES', 'thrive-cb' ); ?></span>
				<span class="click kit-action" data-fn="toggleProInput"><?php echo __( 'CANCEL', 'thrive-cb' ); ?></span>
			</div>
		</div>

	</div>
</div>
