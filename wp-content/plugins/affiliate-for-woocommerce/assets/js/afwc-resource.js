jQuery(function(){
    const { __, _x, _n, _nx } = wp.i18n;
    jQuery('#afwc_resources_wrapper').on('change, keyup', '#afwc_affiliate_link', function(){
        let start = afwcResourceParams.homeurl;
        let path = jQuery(this).val();
        let affiliate_id = jQuery('#afwc_id_change_wrap code').text();
        let affiliate_link = '';
        if ( -1 !== path.indexOf( '?' ) ) {
            affiliate_link = start + path + '&'+ afwcResourceParams.pname + affiliate_id ;
        } else {
            affiliate_link = start + path + '?'+ afwcResourceParams.pname + '=' + affiliate_id 
        }
        jQuery('#afwc_generated_affiliate_link').text( affiliate_link );
    });
    jQuery('#afwc_save_account_button').on('click', function(){
        let form_data      = jQuery('#afwc_account_form').serialize();
        let status_element = jQuery('#afwc_account_form .afwc_save_account_status');
        status_element.removeClass('afwc_status_yes').removeClass('afwc_status_no').removeClass('afwc_status_spinner').addClass('afwc_status_spinner');
        jQuery.ajax({
            url: afwcResourceParams.afwc_save_account_details,
            type: 'post',
            dataType: 'json',
            data: {
                security: afwcResourceParams.save_account_security,
                user_id: afwcResourceParams.userid,
                form_data: decodeURIComponent( form_data )
            },
            success: function( response ) {
                if ( response.success ) {
                    if ( 'yes' === response.success ) {
                        status_element.removeClass('afwc_status_yes').removeClass('afwc_status_no').removeClass('afwc_status_spinner').addClass('afwc_status_yes');
                    } else if ( 'no' === response.success ) {
                        status_element.removeClass('afwc_status_yes').removeClass('afwc_status_no').removeClass('afwc_status_spinner').addClass('afwc_status_no');
                        if ( response.message ) {
                            alert( response.message );
                        }
                    }
                }
            }
        });
    });
    jQuery('#afwc_resources_wrapper').on( 'click', '#afwc_change_identifier', function( e ) {
        e.preventDefault();
        jQuery('#afwc_id_change_wrap, #afwc_id_save_wrap').toggle();
    });
    jQuery('#afwc_resources_wrapper').on( 'click', '#afwc_save_identifier', function( e ) {
        e.preventDefault();
        var id = jQuery(this)[0].id;
        var ref_url_id = jQuery('#afwc_ref_url_id').val();
        if ( 'afwc_save_identifier' === id && ref_url_id !== '' ) {
            // Validate ref_url_id.
            if ( !isNaN(parseFloat(ref_url_id)) && isFinite(ref_url_id) ) {
                var msg = __( 'Numeric values are not allowed.', 'affiliate-for-woocommerce' );
                jQuery( '#afwc_id_msg' ).html( msg ).addClass( 'afwc_error' ).show();
                return;
            }
            var regx = /[!@#$%^&*.: ]/g ;
            if ( regx.test(ref_url_id) ) {
                var msg = __( 'Special characters are not allowed.', 'affiliate-for-woocommerce' );
                jQuery( '#afwc_id_msg' ).html( msg ).addClass( 'afwc_error' ).show();
                return;
            }
            jQuery('#afwc_save_id_loader').show();
            // Ajax call to save id.
            jQuery.ajax({
                url: afwcResourceParams.afwc_save_ref_url_identifier,
                type: 'post',
                dataType: 'json',
                data: {
                    security: afwcResourceParams.save_id_security,
                    user_id: afwcResourceParams.userid,
                    ref_url_id: ref_url_id
                },
                success: function( response ) {
                    jQuery('#afwc_save_id_loader').hide();
                    if ( response.success ) {
                        if ( 'yes' === response.success ) {
                            jQuery('#afwc_id_change_wrap, #afwc_id_save_wrap').toggle();
                            jQuery( '#afwc_id_msg' ).html( response.message ).addClass( 'afwc_sucess' ).removeClass( 'afwc_error' ).show();
                            jQuery('#afwc_id_change_wrap').find('code').text(ref_url_id);
                            jQuery('.afwc_ref_id_span').text(ref_url_id);
                            let affiliate_link = afwcResourceParams.homeurl + '?'+afwcResourceParams.pname+'=' + ref_url_id ;
                            jQuery('#afwc_affiliate_link_label').text(affiliate_link);
                        } else if ( 'no' === response.success ) {
                            jQuery( '#afwc_id_msg' ).html( response.message ).addClass( 'afwc_error' ).removeClass( 'afwc_sucess' ).show();
                        }
                    }
                    setTimeout( function(){ jQuery( '#afwc_id_msg' ).hide(); }, 10000);
                }
            });
        }


    })
});
function afwc_copy_affiliate_link( obj ) {
    let element = jQuery("<input>");
    jQuery("body").append(element);
    element.val(jQuery(obj).text()).select();
    document.execCommand("copy");
    element.remove();
}