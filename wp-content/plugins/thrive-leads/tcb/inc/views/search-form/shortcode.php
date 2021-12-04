<div id="<?php echo esc_attr( $data['wrapper-id'] ); ?>" <?php echo isset( $data['data-list'] ) ? 'data-list="' . esc_attr( $data['data-list'] ) . '"' : ''; ?>class="thrv_wrapper thrv-search-form <?php echo esc_attr( $data['wrapper-class'] ); ?>" data-css="<?php echo esc_attr( $data['data-css-form'] ); ?>" data-tcb-events="<?php echo esc_html( $data['wrapper-events'] ); ?>" data-ct-name="<?php echo esc_attr( $data['data-ct-name'] ); ?>" data-ct="<?php echo esc_attr( $data['data-ct'] ); ?>">
	<form role="search" method="get" action="<?php echo esc_url( home_url() ); ?>">
		<div class="thrv-sf-submit" data-button-layout="<?php echo esc_attr( $data['button-layout'] ); ?>" data-css="<?php echo esc_attr( $data['data-css-submit'] ); ?>">
			<button type="submit" aria-label="<?php echo __( 'Search', 'thrive-cb' ); ?>">
				<span class="tcb-sf-button-icon">
					<span class="thrv_wrapper thrv_icon tve_no_drag tve_no_icons tcb-icon-inherit-style tcb-icon-display" data-css="<?php echo esc_attr( $data['data-css-icon'] ); ?>"><?php echo $data['button-icon']; //phpcs:ignore ?></span>
				</span>
				<span class="tve_btn_txt"><?php echo esc_html( $data['button-label'] ); ?></span>
			</button>
		</div>
		<div class="thrv-sf-input" data-css="<?php echo esc_attr( $data['data-css-input'] ); ?>">
			<input type="search" placeholder="<?php echo esc_attr( $data['input-placeholder'] ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s"/>
		</div>
		<?php foreach ( $data['post-types'] as $type => $label ) : ?>
			<input type="hidden" class="tcb_sf_post_type" name="tcb_sf_post_type[]" value="<?php echo esc_attr( $type ); ?>" data-label="<?php echo esc_attr( $label ); ?>"/>
		<?php endforeach; ?>
	</form>
</div>
