<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden
}
?>
<div id="tve-search_form-component" class="tve-component" data-view="SearchForm">
	<div class="dropdown-header" data-prop="docked">
		<?php echo esc_html__( 'Main Options', 'thrive-cb' ); ?>
		<i></i>
	</div>
	<div class="dropdown-content">
		<div class="mb-5 tcb-text-center">
			<button class="tve-button orange click" data-fn="editFormElements">
				<?php echo esc_html__( 'Edit Form Elements', 'thrive-cb' ); ?>
			</button>
		</div>
		<div class="mb-5"><?php echo esc_html__( 'Format', 'thrive-cb' ); ?></div>
		<div class="tve-control" data-view="FormType"></div>
		<hr>
		<div class="mb-5 hide-tablet hide-mobile sf-button-layout-ctrl"><?php echo esc_html__( 'Button Layout', 'thrive-cb' ); ?></div>
		<div class="tve-control hide-tablet hide-mobile sf-button-layout-ctrl" data-view="ButtonLayout"></div>
		<hr class="hide-tablet hide-mobile sf-button-layout-ctrl">
		<div class="control-grid add-post-type-control">
			<div class="label"><?php echo esc_html__( 'Search the following content', 'thrive-cb' ); ?></div>
			<div class="full">
				<button class="tcb-right tve-button blue click" data-fn="addPostType">
					<?php echo esc_html__( 'MANAGE', 'thrive-cb' ) ?>
				</button>
			</div>
		</div>
		<div class="tve-control" data-key="PostTypes" data-initializer="getPostTypesControl"></div>
		<hr>
		<div class="tve-control" data-view="ContentWidth"></div>
		<hr>
		<div class="tve-control" data-view="Size"></div>
	</div>
</div>

