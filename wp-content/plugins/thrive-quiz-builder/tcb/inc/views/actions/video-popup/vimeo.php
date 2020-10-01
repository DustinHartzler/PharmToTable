<div class="control-grid wrap">
	<label for="v-v-url">
		<?php echo __( 'URL', 'thrive-cb' ) ?>
	</label>
	<input type="text" data-setting="url" class="v-url fill" id="v-v-url">
</div>
<div class="inline-message"></div>
<div class="extra-settings">
	<div class="control-grid no-space wrap">
		<label>
			<?php echo __( 'Player color', 'thrive-cb' ) ?>
		</label>
		<div class="tcb-text-right v-setting-color full-width"></div>
	</div>
	<div class="inline-checkboxes">
		<label class="tcb-checkbox"><input type="checkbox" data-setting="a" value="1" checked="checked"><span><?php echo __( 'Autoplay', 'thrive-cb' ) ?></span></label>
		<label class="tcb-checkbox"><input type="checkbox" data-setting="l" value="1"><span><?php echo __( 'Loop', 'thrive-cb' ) ?></span></label>
		<label class="tcb-checkbox"><input type="checkbox" data-setting="p" value="1"><span><?php echo __( 'Show portrait', 'thrive-cb' ) ?></span></label>
		<label class="tcb-checkbox"><input type="checkbox" data-setting="t" value="1"><span><?php echo __( 'Show title', 'thrive-cb' ) ?></span></label>
		<label class="tcb-checkbox"><input type="checkbox" data-setting="b" value="1"><span><?php echo __( 'Show byline', 'thrive-cb' ) ?></span></label>
	</div>
	<span class="info-text">
		<?php echo tcb_icon( 'info' ) ?>
		<?php echo __( 'Title, portrait and byline are visible only when the video is stopped.' ); ?>
	</span>
</div>