<div id="<?php echo $data['wrapper-id']; ?>"
	 class="thrv_wrapper thrv-search-form <?php echo $data['wrapper-class']; ?>"
	 data-css="<?php echo esc_attr( $data['data-css-form'] ); ?>"
	 data-tcb-events="<?php echo esc_html( $data['wrapper-events'] ); ?>"
	 data-ct-name="<?php echo esc_attr( $data['data-ct-name'] ); ?>"
	 data-ct="<?php echo esc_attr( $data['data-ct'] ); ?>"
	 data-list="<?php echo isset( $data['data-list'] ) ? esc_attr( $data['data-list'] ) : ''; ?>"
	 data-display-d="<?php echo isset( $data['data-display-d'] ) ? esc_attr( $data['data-display-d'] ) : 'none'; ?>"
	 data-display-t="<?php echo isset( $data['data-display-t'] ) ? esc_attr( $data['data-display-t'] ) : ''; ?>"
	 data-display-m="<?php echo isset( $data['data-display-m'] ) ? esc_attr( $data['data-display-m'] ) : ''; ?>"
	 data-position-d="<?php echo isset( $data['data-position-d'] ) ? esc_attr( $data['data-position-d'] ) : 'left'; ?>"
	 data-position-t="<?php echo isset( $data['data-position-t'] ) ? esc_attr( $data['data-position-t'] ) : ''; ?>"
	 data-position-m="<?php echo isset( $data['data-position-m'] ) ? esc_attr( $data['data-position-m'] ) : ''; ?>"
	 data-editor-preview="<?php echo isset( $data['data-editor-preview'] ) ? esc_attr( $data['data-editor-preview'] ) : ''; ?>"
>
	<form class="tve-prevent-content-edit" role="search" method="get" action="<?php echo esc_attr( home_url() ); ?>">
		<div class="thrv-sf-submit" data-button-layout="<?php echo esc_attr( $data['button-layout'] ); ?>" data-css="<?php echo esc_attr( $data['data-css-submit'] ); ?>">
			<button type="submit">
				<span class="tcb-sf-button-icon">
					<span class="thrv_wrapper thrv_icon tve_no_drag tve_no_icons tcb-icon-inherit-style tcb-icon-display" data-css="<?php echo esc_attr( $data['data-css-icon'] ); ?>"><?php echo $data['button-icon']; //phpcs:ignore ?></span>
				</span>
				<span class="tve_btn_txt"><?php echo esc_html( $data['button-label'] ); ?></span>
			</button>
		</div>
		<div class="thrv-sf-input" data-css="<?php echo esc_attr( $data['data-css-input'] ); ?>">
			<input type="search" placeholder="<?php echo esc_attr( $data['input-placeholder'] ); ?>" name="s" value="<?php echo esc_attr( get_search_query() ); ?>"/>
		</div>
		<?php foreach ( $data['post-types'] as $type => $label ) : ?>
			<input type="hidden" class="tcb_sf_post_type" name="tcb_sf_post_type[]" value="<?php echo esc_attr( $type ); ?>" data-label="<?php echo esc_attr( $label ); ?>"/>
		<?php endforeach; ?>
	</form>
</div>
