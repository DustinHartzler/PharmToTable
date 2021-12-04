<div id="thrive-api-connections">
	<?php if ( empty( $connected_apis ) ) : ?>
		<h6><?php echo esc_html__( "You currently don't have any API integrations set up.", TVE_DASH_TRANSLATE_DOMAIN ) ?></h6>
		<a href="<?php echo esc_url( admin_url( 'admin.php?page=tve_dash_api_connect' ) ); ?>" target="_blank"
		   class="tve_lightbox_link tve_lightbox_link_create"><?php echo esc_html__( "Click here to set up a new API connection", TVE_DASH_TRANSLATE_DOMAIN ) ?></a>
	<?php else : ?>
		<h6><?php echo esc_html__( "Choose from your list of existing API connections or", TVE_DASH_TRANSLATE_DOMAIN ) ?>
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=tve_dash_api_connect' ) ); ?>" target="_blank" class="tve_lightbox_link tve_lightbox_link_create">
				<?php echo esc_html__( 'add a new integration', TVE_DASH_TRANSLATE_DOMAIN ) ?>
			</a>
		</h6>
		<div class="tve_lightbox_select_holder tve_lightbox_input_inline tve_lightbox_select_inline">
			<select id="thrive-api-connections-select" class="tve_change"
					data-ctrl="function:auto_responder.api.api_get_lists" autocomplete="off">
				<?php foreach ( $connected_apis as $key => $api ) : ?>
					<option
						value="<?php echo esc_attr( $key ) ?>"<?php echo $edit_api_key == $key ? ' selected="selected"' : '' ?>><?php echo esc_html( $api->getTitle() ); ?></option>
				<?php endforeach ?>
			</select>
		</div>
		&nbsp;&nbsp;&nbsp;
		<a href="javascript:void(0)" data-ctrl="function:auto_responder.api.reload_apis" class="tve_click tve_lightbox_link tve_lightbox_link_refresh"><?php echo esc_html__( "Reload", TVE_DASH_TRANSLATE_DOMAIN ) ?></a>
	<?php endif ?>
</div>
