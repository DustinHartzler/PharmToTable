<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

?>

<div class="error-container"></div>
<div class="tve-modal-content">
	<div id="cb-cloud-menu">
		<div class="lp-search">
			<input type="text" data-source="search" class="keydown" data-fn="filter" placeholder="<?php echo esc_html__( 'Search', 'thrive-cb' ); ?>"/>
			<?php tcb_icon( 'search-regular' ); ?>
			<?php tcb_icon( 'close2', false, 'sidebar', 'click', array( 'data-fn' => 'domClearSearch' ) ); ?>
		</div>
		<div class="lp-menu-wrapper fixed">
			<div class="lp-label-wrapper mt-30">
				<span><?php echo __( 'Type', 'thrive-cb' ); ?></span>
				<span class="separator"></span>
			</div>
			<div id="lp-groups-wrapper"></div>
		</div>
		<div class="fixed bottom"></div>
	</div>

	<div id="cb-cloud-templates">
		<div id="lp-blk-pack-title" class="mb-5">Templates</div>
		<div id="cb-pack-content">
			<div class="tve-symbols-wrapper">
				<div class="text-no-symbols">
					<?php echo __( "Oups! We couldn't find anything called " ) ?><span class="search-word"></span><?php echo __( '. Maybe search for something else ?' ); ?>
				</div>
			</div>

			<div class="tve-content-templates-wrapper">
				<div class="text-no-templates" style="display: none;">
					<?php echo __( "Oups! We couldn't find anything called " ) ?><span class="search-word"></span><?php echo __( '. Maybe search for something else ?' ); ?>
				</div>
			</div>
		</div>
	</div>
</div>
