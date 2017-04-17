<?php
$focus_area_class = $current_attrs['_thrive_meta_focus_color'][0];
$section_position = ( $position == "bottom" ) ? "fab" : "fat";
?>

<section class="far f1 <?php echo $focus_area_class; ?> <?php echo $section_position; ?>">
	<div class="wrp">
		<h3><?php echo $current_attrs['_thrive_meta_focus_heading_text'][0]; ?></h3>

		<div class="frmH">
			<form class="frm" action="<?php echo $optinFormAction; ?>" method="<?php echo $optinFormMethod ?>">

				<?php echo $optinHiddenInputs; ?>

				<?php echo $optinNotVisibleInputs; ?>

				<?php if ( $optinFieldsArray ): ?>
					<?php foreach ( $optinFieldsArray as $name_attr => $field_label ): ?>
						<?php echo Thrive_OptIn::getInstance()->getInputHtml( $name_attr, $field_label ); ?>
					<?php endforeach; ?>
				<?php endif; ?>

				<div class="btn small dark">
					<input type="submit" value="<?php echo $current_attrs['_thrive_meta_focus_button_text'][0]; ?>"
					       onclick="this.form.submit();"/>
				</div>
			</form>
		</div>
	</div>
</section>