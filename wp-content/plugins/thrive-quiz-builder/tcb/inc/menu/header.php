<?php
/**
 * FileName  header.php.
 *
 * @project: thrive-visual-editor
 * @company: BitStone
 */
?>
<div id="tve-header-component" class="tve-component" data-view="HFSection" data-type="header">
	<div class="section-select">
		<div class="dropdown-header" data-prop="docked">
			<?php echo __( 'Main Options', 'thrive-cb' ); ?>
			<i></i>
		</div>
		<div class="dropdown-content">
			<div class="tve-control mb-10" data-view="PageMap"></div>

			<div class="tve-control mb-5" data-view="Visibility"></div>

			<div class="tve-control mb-5 sep-top" data-view="StretchBackground"></div>

			<div class="tve-control mb-5 sep-top" data-view="InheritContentSize"></div>

			<div class="tve-control mb-5" data-view="ContentWidth"></div>

			<div class="tve-control mt-5" data-view="StretchContent"></div>

			<div class="pb-10 tcb-text-center">
				<?php echo __( 'This is a header. You can edit it as a global element( it updates simultaneously in all places you used it) or change it with another saved header', 'thrive-tcb' ); ?>
			</div>
			<hr>
			<div class="row pb-10 header-actions">
				<div class="col-xs-6">
					<button class="tve-button blue long click" data-fn="editSection">
						<?php echo __( 'Edit Header', 'thrive-cb' ) ?>
					</button>
				</div>

				<div class="col-xs-6">
					<button class="tve-button grey long click" data-fn="placeholder_action">
						<?php echo __( 'Change Header', 'thrive-cb' ) ?>
					</button>
				</div>
			</div>
		</div>
	</div>

	<div class="action-group" style="display: none">
		<div class="dropdown-header" data-prop="docked">
			<?php echo __( 'Header Options', 'thrive-cb' ); ?>
			<i></i>
		</div>
		<div class="dropdown-content">
			<div class="tve-control" data-view="HeaderPosition"></div>
			<hr>
			<div class="tve-control" data-view="Height"></div>
			<div class="tve-control" data-view="FullHeight"></div>
			<hr>
			<div class="tve-control" data-view="VerticalPosition"></div>
		</div>
	</div>

</div>
