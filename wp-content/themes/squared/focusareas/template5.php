<?php
$btn_class = ( empty( $current_attrs['_thrive_meta_focus_button_color'][0] ) ) ? "blue" : strtolower( $current_attrs['_thrive_meta_focus_button_color'][0] );
if ( ! is_array( $optinFieldsArray ) ) {
	$optinFieldsArray = array();
}
if ( count( $optinFieldsArray ) > 2 ) {
	echo "This focus area template supports only 2 input fields. Please check your opt-in configuration in order to use this template.";

	return;
}
?>

<div class="far fab f3">
	<?php if ( $current_attrs['_thrive_meta_focus_image'][0] != "" ): ?>
		<div class="tg left"><img src="<?php echo $current_attrs['_thrive_meta_focus_image'][0]; ?>"/></div>
	<?php endif; ?>
	<div class="tw left">
		<h4>
			<?php echo $current_attrs['_thrive_meta_focus_heading_text'][0]; ?>
		</h4>

		<p>
			<?php echo nl2br( do_shortcode( $current_attrs['_thrive_meta_focus_subheading_text'][0] ) ); ?>
		</p>
	</div>
	<div class="clear"></div>
	<div class="frm i<?php echo count( $optinFieldsArray ); ?>">
		<form action="">
			<?php
			if ( $optinFieldsArray && is_array( $optinFieldsArray ) ):
				foreach ( $optinFieldsArray as $name_attr => $field_label ):
					?>
					<input type="text" placeholder="<?php echo $field_label; ?>"
					       name='<?php echo _thrive_get_optin_name_attr_fixed( $name_attr ); ?>'/>
				<?php endforeach; ?>
			<?php endif; ?>

			<div class="fob btn medium <?php echo $btn_class; ?> ">
				<input type="submit" value="<?php echo $current_attrs['_thrive_meta_focus_button_text'][0]; ?>"/>
			</div>
			<?php echo $optinHiddenInputs; ?>
			<div class="clear"></div>
		</form>
	</div>
</div>