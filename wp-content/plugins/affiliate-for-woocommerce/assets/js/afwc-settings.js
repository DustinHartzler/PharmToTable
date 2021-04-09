/* phpcs:ignoreFile */
jQuery(
	function(){
		jQuery( 'form' ).on(
			'click',
			'.woocommerce-save-button',
			function(e){
				var new_pname = jQuery( '#afwc-pname' ).val();
				var old_pname = afwc_settings_pre_data.old_pname || '';
				if ( 'ref' === old_pname && old_pname !== new_pname ) {
					return;
				}
				if ( jQuery( 'form' ).find( '#afwc_admin_settings_security' ).length > 0 ) {
					if ( confirm( afwc_settings_pre_data.confirm_msg ) ) {
						return true;
					} else {
						return false;
					}
				}
			}
		);
		jQuery( 'form' ).on(
			'change, keyup',
			'#afwc_pname',
			function( event ){
				var new_pname = jQuery( this ).val();
				jQuery( '#afwc_pname_span' ).text( new_pname );
			}
		);
		jQuery( 'form' ).on(
			'keydown',
			'#afwc_pname',
			function( event ){
				var key = event.which;
				if ( ! ( ( key == 8 ) || ( key == 46 ) || ( key >= 35 && key <= 40 ) || ( key >= 65 && key <= 90 ) ) ) {
					event.preventDefault();
				}
			}
		);
		jQuery('#affiliate_reg_form').css('display', 'none');
		jQuery('#affiliate_tags').css('display', 'none');

	}
);
