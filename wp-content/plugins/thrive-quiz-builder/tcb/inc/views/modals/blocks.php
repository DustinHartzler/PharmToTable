<div class="error-container"></div>
<div style="position: absolute;top: 0;" id="blocks-lightbox-drop-panels"></div>
<div class="tve-modal-content">
	<div id="cb-cloud-menu">
		<div class="lp-search">
			<?php tcb_icon( 'search-regular' ); ?>
			<input type="text" data-source="search" class="keydown" data-fn="filter" placeholder="<?php echo esc_html__( 'Search', 'thrive-cb' ); ?>"/>
			<?php tcb_icon( 'close2', false, 'sidebar', 'click', array( 'data-fn' => 'domClearSearch' ) ); ?>
		</div>
		<div class="lp-menu-wrapper">
			<div id="block-source-select-wrapper">
				<span class="text-12"><?php echo __( 'Filter blocks:', 'thrive-cb' ); ?></span>
				<select id="block-source-select" class="change" data-fn="sourceChanged"></select>
			</div>
			<div class="lp-label-wrapper mt-30">
				<span><?php echo __( 'Block types', 'thrive-cb' ); ?></span>
				<span class="separator"></span>
			</div>
			<div id="lp-groups-wrapper"></div>
		</div>
	</div>
	<div id="cb-cloud-templates">
		<div class="tcb-modal-header flex-center space-between">
			<div id="lp-blk-pack-title" class="mb-5"></div>
			<span data-fn="clearCache" class="tcb-refresh mr-30 click flex-center">
				<span class="mr-10"><?php tcb_icon( 'sync-regular' ); ?></span>
				<span class="mr-10"><?php echo __( 'Refresh from cloud', 'thrive-cb' ); ?></span>
			</span>
		</div>
		<div id="lp-blk-pack-description"></div>
		<div id="cb-pack-content"></div>
	</div>
</div>
