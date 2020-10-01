<div id="<?php echo $data['wrapper-id']; ?>" class="thrv_wrapper thrv-search-form <?php echo $data['wrapper-class']; ?>" data-css="<?php echo $data['data-css-form']; ?>" data-tcb-events="<?php echo esc_html( $data['wrapper-events'] ); ?>" data-ct-name="<?php echo $data['data-ct-name']; ?>" data-ct="<?php echo $data['data-ct']; ?>">
	<form role="search" method="get" action="<?php echo home_url(); ?>">
		<div class="thrv-sf-submit" data-button-layout="<?php echo $data['button-layout']; ?>" data-css="<?php echo $data['data-css-submit']; ?>">
			<button type="submit">
				<span class="tcb-sf-button-icon">
					<span class="thrv_wrapper thrv_icon tve_no_drag tve_no_icons tcb-icon-inherit-style tcb-icon-display" data-css="<?php echo $data['data-css-icon']; ?>"><?php echo $data['button-icon']; ?></span>
				</span>
				<span class="tve_btn_txt"><?php echo $data['button-label']; ?></span>
			</button>
		</div>
		<div class="thrv-sf-input" data-css="<?php echo $data['data-css-input']; ?>">
			<input type="search" placeholder="<?php echo $data['input-placeholder']; ?>" value="<?php echo get_search_query(); ?>" name="s"/>
		</div>
		<?php foreach ( $data['post-types'] as $type => $label ) : ?>
			<input type="hidden" class="tcb_sf_post_type" name="tcb_sf_post_type[]" value="<?php echo $type; ?>" data-label="<?php echo esc_attr( $label ); ?>"/>
		<?php endforeach; ?>
	</form>
</div>