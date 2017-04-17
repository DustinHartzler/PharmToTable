<?php
$focus_area_class   = $current_attrs['_thrive_meta_focus_color'][0];
$action_link_target = ( $current_attrs['_thrive_meta_focus_new_tab'][0] == 1 ) ? "_blank" : "_self";
$section_position   = ( $position == "bottom" ) ? "fab" : "fat";
?>

<section class="far f2 <?php echo $focus_area_class; ?> <?php echo $section_position; ?>">
	<a href="<?php echo $current_attrs['_thrive_meta_focus_button_link'][0]; ?>"
	   target="<?php echo $action_link_target; ?>">
		<div class="wrp">
			<h3>
				<?php echo $current_attrs['_thrive_meta_focus_heading_text'][0]; ?> <span class="awe">&#xf178;</span>
			</h3>
		</div>
	</a>
</section>
