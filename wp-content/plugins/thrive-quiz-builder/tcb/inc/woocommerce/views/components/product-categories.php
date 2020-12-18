<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-visual-editor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden
}
?>

<div id="tve-product-categories-component" class="tve-component" data-view="ProductCategories">
	<div class="dropdown-header" data-prop="docked">
		<?php echo __( 'Category List Options', 'thrive-cb' ); ?>
		<i></i>
	</div>
	<div class="dropdown-content">
		<div class="center-xs col-xs-12 mb-10 edit-mode-hidden">
			<button class="tve-button orange click" data-fn="editProductCategories">
				<?php echo __( 'Edit Design', 'thrive-cb' ); ?>
			</button>
		</div>
		<div class="hide-tablet hide-mobile">
			<div class="edit-mode-hidden">
				<hr>
				<div class="tve-control" data-view="Limit"></div>
				<div class="tve-control" data-view="Columns"></div>
				<div class="tve-control" data-view="OrderBy"></div>
				<div class="tve-control" data-view="Order"></div>
				<hr>
			</div>

			<div class="tve-control pt-5" data-view="TextLayout"></div>
			<div class="tve-control" data-view="TextPosition"></div>

			<hr>

			<div class="tve-control" data-view="title-visibility"></div>
			<div class="tve-control" data-view="product-number-visibility"></div>
			<div class="tve-control edit-mode-hidden" data-view="empty-category"></div>

			<hr>

			<div class="tve-control" data-view="Alignment"></div>
			<div class="tve-control" data-view="ImageSize"></div>

			<div class="tve-advanced-controls edit-mode-hidden">
				<div class="dropdown-header" data-prop="advanced">
				<span class="mb-5">
					<?php echo __( 'Filter categories', 'thrive-cb' ); ?>
				</span>
				</div>
				<div class="dropdown-content pt-0">
					<div class="ids-container mb-5">
						<label for="tcb-woo-category-ids-select">
							<?php echo __( 'Show individual categories', 'thrive-cb' ); ?>
						</label>
						<select id="tcb-woo-product-category-select" class="tcb-woo-select"></select>
					</div>
					<hr class="mt-10">
					<div class="tve-control" data-view="ParentCategory"></div>
				</div>
			</div>
		</div>
	</div>
</div>
