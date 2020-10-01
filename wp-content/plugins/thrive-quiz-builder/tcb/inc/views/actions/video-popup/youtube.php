<div class="control-grid no-space wrap">
	<label for="v-yt-url"><?php echo __( 'URL', 'thrive-cb' ) ?></label>
	<input type="text" data-setting="url" class="yt-url fill" id="v-yt-url">
</div>
<div class="yt-url-validate inline-message"></div>

<div class="extra-settings">
	<div class="inline-checkboxes">
		<label class="tcb-checkbox"><input type="checkbox" class="change" data-fn="videoPopSettingChanged" data-setting="no-cookie" value="1"><span><?php echo __( 'Disable cookies on YouTube', 'thrive-cb' ) ?></span></label>
		<label class="tcb-checkbox"><input type="checkbox" class="change" data-fn="videoPopSettingChanged" data-setting="a" value="1" checked="checked"><span><?php echo __( 'Autoplay', 'thrive-cb' ) ?></span></label>
		<label class="tcb-checkbox"><input type="checkbox" class="change" data-fn="videoPopSettingChanged" data-setting="hrv" value="1"><span><?php echo __( 'Optimize related', 'thrive-cb' ) ?></span></label>
		<label class="tcb-checkbox"><input type="checkbox" class="change" data-fn="videoPopSettingChanged" data-setting="ahi" value="1"><span><?php echo __( 'Hide player controls', 'thrive-cb' ) ?></span></label>
		<label class="tcb-checkbox"><input type="checkbox" class="change" data-fn="videoPopSettingChanged" data-setting="hfs" value="1"><span><?php echo __( 'Hide full-screen', 'thrive-cb' ) ?></span></label>
		<label class="tcb-checkbox"><input type="checkbox" class="change" data-fn="videoPopSettingChanged" data-setting="hyl" value="1"><span><?php echo __( 'Hide logo', 'thrive-cb' ) ?></span></label>
	</div>
</div>
