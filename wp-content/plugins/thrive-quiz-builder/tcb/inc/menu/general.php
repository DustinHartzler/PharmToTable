<div id="tve-layout-component" class="tve-component" data-view="Layout"></div>

<div id="tve-responsive-component" class="tve-component" data-view="Responsive">
	<div class="dropdown-header" data-prop="docked">
		<div class="group-description">
			<?php echo __( 'Responsive', 'thrive-cb' ) ?>
		</div>
		<i></i>
	</div>
	<div class="dropdown-content">
		<div class="tve-control no-space" data-view="Devices" data-extends="ButtonGroup" data-required="1" data-name="<?php _e( 'Visible on', 'thrive-cb' ); ?>"
			 data-checkbox="true"></div>
		<hr>
		<div class="tve-control" data-extends="Switch" data-view="ShowAllHidden" data-label="<?php _e( 'Show all hidden elements', 'thrive-cb' ); ?>"></div>
	</div>
</div>

<div id="tve-styles-templates-component" class="tve-component" data-view="StylesTemplates">
	<div class="dropdown-header" data-prop="docked">
		<?php echo __( 'HTML Attributes', 'thrive-cb' ); ?>
		<i></i>
	</div>
	<div class="dropdown-content">
		<div class="tve-control" data-input="class" data-key="Class" data-extends="LabelInput" data-label="<?php echo __( 'Class', 'thrive-cb' ); ?>"></div>
		<div class="tve-control" data-input="id" data-key="ID" data-extends="LabelInput" data-label="<?php echo __( 'ID', 'thrive-cb' ); ?>"></div>
	</div>
</div>

<div id="tve-background-component" class="tve-component" data-view="Background">
	<div class="dropdown-header" data-prop="docked">
		<div class="group-description"><?php echo __( 'Background Style', 'thrive-cb' ); ?></div>
		<i></i>
	</div>
	<div class="dropdown-content">
		<div class="gradient-layers"></div>
		<div class="tve-control" data-key="PreviewFilterList" data-view="PreviewList"></div>
		<div class="tve-control bg-icons" data-view="PreviewList"></div>
		<div class="v-sep"></div>
		<div class="tve-control" data-view="ColorPicker" data-show-gradient="0"></div>
		<div class="tve-control video-bg white" data-label="<?php echo __( 'Video Background', 'thrive-cb' ); ?>" data-key="video" data-initializer="video"></div>
	</div>
</div>

<div id="tve-typography-component" class="tve-component" data-view="Typography">
	<div class="dropdown-header" data-prop="docked">
		<div class="group-description"><?php echo __( 'Typography', 'thrive-cb' ); ?></div>
		<i></i>
	</div>
	<div class="dropdown-content">
		<div class="tve-control hide-states" data-view="ParagraphStyle"></div>
		<div class="tve-control hide-states" data-key="ParagraphStylePicker" data-initializer="paragraphStylePicker"></div>
		<div class="tve-control hide-states" data-view="FontFace"></div>
		<div class="tve-control" data-view="FontColor"></div>
		<div class="tve-control hide-states" data-view="TextTransform"></div>
		<div class="tve-control hide-states" data-view="TextAlign"></div>
		<div class="tve-control" data-view="TextStyle"></div>
		<div class="btn-group-light typography-button-toggle-controls">
			<div class="tve-control hide-states" data-view="ToggleControls"></div>

			<div class="tve-control hide-states tcb-typography-toggle-element tcb-typography-font-size" data-view="FontSize"></div>
			<div class="tve-control hide-states tcb-typography-toggle-element tcb-typography-line-height" data-view="LineHeight"></div>
			<div class="tve-control hide-states tcb-typography-toggle-element tcb-typography-letter-spacing pb-10" data-view="LetterSpacing"></div>
		</div>
		<hr>
		<div class="tcb-text-center clear-formatting mt-10">
			<span class="click tcb-text-uppercase clear-format custom-icon" data-fn="clear_formatting">
				<?php echo __( 'Clear element formatting', 'thrive-cb' ); ?>
			</span>
		</div>

		<div class="tve-advanced-controls extend-grey hide-states">
			<div class="dropdown-header" data-prop="advanced">
				<span>
					<?php echo __( 'Advanced', 'thrive-cb' ); ?>
				</span>
			</div>

			<div class="dropdown-content pt-0">
				<div class="hide-states">
					<div class="tve-control" data-view="Slider" data-key="p_spacing"></div>
					<div class="tve-control" data-view="Slider" data-key="h1_spacing"></div>
					<div class="tve-control" data-view="Slider" data-key="h2_spacing"></div>
					<div class="tve-control" data-view="Slider" data-key="h3_spacing"></div>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="tve-borders-component" class="tve-component" data-view="Borders">
	<div class="borders-options action-group">
		<div class="dropdown-header" data-prop="docked">
			<div class="group-description">
				<?php echo __( 'Borders & Corners', 'thrive-cb' ); ?>
			</div>
			<i></i>
		</div>
		<div class="dropdown-content">

			<div class="tve-control" data-view="Borders"></div>
			<hr>
			<div class="tve-control" data-view="Corners"></div>

		</div>
	</div>
</div>

<div id="tve-animation-component" class="tve-component" data-view="Animation">
	<?php $tabs = tcb_get_editor_actions(); ?>
	<div class="animation-options action-group">
		<div class="dropdown-header" data-prop="docked">
			<div class="group-description">
				<?php esc_html_e( 'Animation & Action', 'thrive-cb' ); ?>
			</div>
			<i></i>
		</div>
		<div class="dropdown-content">
			<div class="tve-animation control-grid no-space button-group-holder">
				<div class="group-label label">
					<?php esc_html_e( 'Add new:', 'thrive-cb' ) ?>
				</div>
				<div class="tve-btn-group grey input" id="tcb-anim-buttons">
					<?php foreach ( $tabs as $key => $tab ) : ?>
						<div class="btn-inline tve-btn click anim-<?php echo esc_attr( $key ) ?>" data-fn="tab_click"
							 data-value="<?php echo esc_attr( $key ) ?>"
							 data-tooltip="<?php echo esc_attr( $tab['title'] ) ?>" data-side="top">
							<?php tcb_icon( $tab['icon'] ) ?>
						</div>
					<?php endforeach ?>
				</div>
			</div>
			<div class="actions-holder">
				<?php foreach ( $tabs as $key => $tab ) : ?>
					<div class="action-tab action-<?php echo esc_attr( $key ) ?>" style="display: none"
						 data-tab="<?php echo esc_attr( $key ) ?>">
						<?php /* special case for links */ ?>
						<?php if ( $key == 'link' || isset( $tab['instance'] ) ) : ?>
							<div class="action-settings"
								 data-action="<?php echo isset( $tab['instance'] ) ? esc_attr( $tab['instance']->get_key() ) : 'link' ?>"
								 data-view="<?php echo isset( $tab['instance'] ) ? esc_attr( $tab['instance']->get_editor_js_view() ) : 'Link' ?>">
								<?php isset( $tab['instance'] ) ? $tab['instance']->render_editor_settings() : tcb_template( 'actions/link' ) ?>
							</div>
						<?php elseif ( isset( $tab['actions'] ) ) : ?>
							<?php if ( $key === 'popup' ) : ?>
								<div class="control-grid animation-popup-trigger">
									<label for="a-popup-trigger"><?php echo __( 'Trigger', 'thrive-cb' ) ?></label>
									<select id="a-popup-trigger">
										<option value="click" selected><?php echo __( 'Click', 'thrive-cb' ) ?></option>
										<option
												value="tve-viewport"><?php echo __( 'Comes into viewport', 'thrive-cb' ) ?></option>
									</select>
								</div>
							<?php endif ?>
							<hr class="animation-popup-trigger">
							<div class="action-collection">
								<?php
								$auto_select = count( $tab['actions'] ) == 1 ? 'checked="checked"' : '';
								foreach ( $tab['actions'] as $action ) : ?>
									<div class="action-item">
										<label class="tcb-radio">
											<input
													name="action_group_<?php echo esc_attr( $key ) ?>" <?php echo $auto_select ?>
													type="radio"
													class="action-chooser change"
													data-fn="action_select"
													value="<?php echo esc_attr( $action['instance']->get_key() ) ?>">
											<span><?php echo esc_html( $action['instance']->getName() ) ?></span>
										</label>
										<div class="action-settings" style="display: none"
											 data-action="<?php echo esc_attr( $action['instance']->get_key() ) ?>"
											 data-view="<?php echo esc_attr( $action['instance']->get_editor_js_view() ) ?>">
											<?php $action['instance']->render_editor_settings() ?>
										</div>
									</div>
								<?php endforeach ?>
							</div>
						<?php endif ?>
					</div>
				<?php endforeach ?>
			</div>
			<div id="tcb-anim-list" class="tcb-relative"></div>
		</div>
	</div>
</div>

<div id="tve-shadow-component" class="tve-component" data-view="Shadow">
	<div class="borders-options action-group">
		<div class="dropdown-header" data-prop="docked">
			<div class="group-description">
				<?php echo __( 'Shadow', 'thrive-cb' ); ?>
			</div>
			<i></i>
		</div>
		<div class="dropdown-content">
			<div class="tve-shadow no-space" id="tcb-shadow-buttons"></div>
			<div id="tcb-text-shadow-list" class="tcb-relative no-space tcb-preview-list" data-shadow-type="text-shadow"></div>
			<div id="tcb-box-shadow-list" class="tcb-relative no-space tcb-preview-list" data-shadow-type="box-shadow"></div>
		</div>
	</div>

</div>

<div id="tve-cloud-templates-component" data-key="cloud_templates" class="tve-component dynamic-component"
	 style="order: 5;" data-view="CloudTemplates">
	<div class="dropdown-header" data-prop="docked">
		<div class="group-description"><?php echo __( 'Template Options', 'thrive-cb' ); ?></div>
		<i></i>
	</div>
	<div class="dropdown-content">
		<div class="tve-control" data-view="ModalPicker" data-label="<?php echo __( 'Template', 'thrive-cb' ); ?>"></div>
	</div>
</div>

<div id="tve-group-component" class="tve-component" data-view="Group">
	<div class="dropdown-header" data-prop="docked">
		<div class="group-description"><?php echo __( 'Currently styling', 'thrive-cb' ); ?></div>
		<i></i>
	</div>
	<div class="dropdown-content">
		<div class="control-grid">
			<div class="tve-control fill no-space" data-view="preview"></div>
			<div class="tve-control pl-10" data-view="ButtonToggle"></div>
		</div>
		<hr>
		<div class="tcb-text-center">
			<a href="javascript:void(0);" class="click clear-format"
			   data-fn="close_group_options"><?php tcb_icon( 'exit-to-app' ); ?><?php echo __( 'Exit Group Styling', 'thrive-cb' ); ?></a>
		</div>

	</div>
</div>

<div id="tve-scroll-component" class="tve-component" data-view="Scroll">
	<div class="dropdown-header" data-prop="docked">
		<div class="group-description"><?php echo __( 'Scroll Behavior', 'thrive-cb' ); ?></div>
		<i></i>
	</div>
	<div class="dropdown-content">
		<div class="tve-control no-space" data-extends="ButtonGroup" data-key="toggle" data-name="<?php esc_attr_e( 'Behavior on Scroll', 'thrive-cb' ); ?>"></div>

		<div class="tve-control padding-top-10" data-extends="ButtonGroup" data-key="Devices" data-required="1" data-name="<?php esc_attr_e( 'Visible on', 'thrive-cb' ); ?>" data-checkbox="true"></div>
		<div class="if-parallax">
			<div class="tve-control" data-key="ParallaxEffectsControl" data-initializer="initParallaxEffects"></div>
			<div id="add-parallax-effect" class="tve-button click" data-fn="addNewParallax"><?php echo __( 'Add New Effect', 'thrive-cb' ); ?></div>
			<div class="tve-control mt-5" data-key="ParallaxPreview" data-extends="Switch" data-label="<?php esc_attr_e( 'Parallax Preview', 'thrive-cb' ); ?>"></div>
		</div>
		<div class="if-sticky">
			<div class="tve-control mb-10" data-extends="ButtonGroup" data-key="stickyPosition" data-name="<?php esc_attr_e( 'Stick to screen', 'thrive-cb' ); ?>"></div>
			<div class="tve-control" data-extends="Slider" data-key="top"
				 data-label="<?php echo __( 'Distance from the top of the screen', 'thrive-cb' ); ?>"></div>
			<div class="tve-control no-space" data-extends="Select" data-key="until" data-label="<?php echo __( 'Sticky until', 'thrive-cb' ); ?>"></div>
			<div class="tve-control no-space if-until-element padding-top-10" data-extends="LabelInput" data-key="element_id"
				 data-label="<?php echo __( 'Element ID', 'thrive-cb' ); ?>"></div>
			<div class="info-text if-until-element"><?php echo __( 'You can set the ID of an element from the "HTML Attributes" section', 'thrive-cb' ); ?></div>
			<div class="tve-control mt-10" data-extends="Switch" data-key="switchOnScroll" data-label="<?php echo __( 'Switch on scroll', 'thrive-cb' ); ?>"></div>
			<div class="tve-control no-space" data-extends="Slider" data-key="switchAfter" data-label="<?php echo __( 'Switch after', 'thrive-cb' ); ?>"></div>
		</div>
	</div>
</div>

<div id="tve-shared-styles-component" class="tve-component" data-view="SharedStyles">
	<div class="dropdown-header" data-prop="docked">
		<div><?php echo __( 'Style Options', 'thrive-cb' ) ?></div>
		<i></i>
	</div>
	<div class="dropdown-content">

		<div class="control-grid no-space">
			<div class="label tve-control no-space" data-key="preview" data-initializer="preview_style_control"></div>

			<div class="shared-styles-actions ml-5" data-state="0">
				<div class="tve-control ml-5" data-key="global_style" data-initializer="global_style_control"></div>
			</div>

			<div class="shared-styles-actions ml-5" data-state="1">
				<a href="javascript:void(0)" class="click" data-fn="edit_style" data-side="top" data-tooltip="<?php echo __( 'Edit Global Style', 'thrive-cb' ); ?>">
					<?php tcb_icon( 'edit' ); ?>
				</a>

				<a href="javascript:void(0)" class="click" data-fn="dom_unlink_style" data-side="top" data-tooltip="<?php echo __( 'Unlink Global Style', 'thrive-cb' ); ?>">
					<?php tcb_icon( 'unlink-regular' ); ?>
				</a>

				<a href="javascript:void(0)" class="click" data-fn="toggle_more_options">
					<?php tcb_icon( 'ellipsis-v-regular' ); ?>
				</a>
			</div>

			<div class="control-grid no-space shared-styles-actions" data-state="2">
				<span class="global-edit-warning">
					<span><?php echo __( 'You are editing the Global Style', 'thrive-cb' ); ?>: </span>
					<strong><span class="shared-styles-name"></span></strong>.
					<span> <?php echo __( 'The changes you make on it will affect all elements which use this style.', 'thrive-cb' ); ?></span>
				</span>
			</div>

			<ul class="shared-styles-drop-content tcb-hide">
				<li class="click" data-fn="rename">
					<a href="javascript:void(0)"><?php echo __( 'Rename', 'thrive-cb' ) ?></a>
				</li>
				<li class="click" data-fn="delete">
					<a href="javascript:void(0)"><?php echo __( 'Delete', 'thrive-cb' ) ?></a>
				</li>
			</ul>
		</div>
		<div class="control-grid pt-5 no-space shared-styles-actions" data-state="1">
			<span class="global-edit-warning">
					<?php echo __( 'This element has a Global Style applied: some of its properties are not editable at the instance level (e.g. Typography, Background, Borders etc). You can modify these by clicking on the pencil icon above.', 'thrive-cb' ); ?>
			</span>
		</div>

	</div>
</div>
