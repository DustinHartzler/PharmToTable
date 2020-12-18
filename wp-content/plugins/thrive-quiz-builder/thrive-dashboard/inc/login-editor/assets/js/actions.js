module.exports = {
	'tcb-ready': () => {

		/* Initialize selectors */
		tvd_login_editor.elements.forEach( element => {
			const selector = TVE.identifier( element )
			TVE.inner_$( selector )
			   .addClass( 'thrv_wrapper tve_no_drag' )
			   .attr( 'data-selector', selector )
		} )

		TVE.inner_$( 'form input' ).each( ( index, element ) => {
			/* prevent input autocomplete or password suggestion */
			element.setAttribute( 'readonly', true );

			switch ( element.type ) {
				case 'text':
					element.value = 'username';
					break;
				case 'password':
					element.value = 'password';
					break;
			}
		} );

		/* we don't need the content, so we use an empty div */
		TVE.Editor_Page.content_manager.editor = TVE.inner_$( '<div>' );

		/* enable click on the body */
		TVE.Editor_Page.editor.on( 'click.tcb', TVE.Editor_Page.selection_manager.element_click.bind( TVE.Editor_Page.selection_manager ) )

		TVE.inner.$body
		   .removeClass( 'tve_empty_dropzone' )
		   .attr( 'data-ct', 'tvd_login_screen-0' )
		   .attr( 'data-ct-name', 'Default' );

		if ( parseInt( tvd_login_editor.has_template ) !== 1 ) {
			TVE.modal_open( 'cloud-templates', {
				element: TVE.inner.$body
			} );

			TVE.ajax( 'update_option', 'post', {
				option_name: 'tvd_login_screen_has_template',
				option_value: 1
			} );

			tvd_login_editor.has_template = 1;
		}

		/* wait a little bit just after the editor image-fix is executed */
		setTimeout( TVE.Components[ 'tvd-login-logo' ].fixLogoRatio, 1000 )
	},
	/**
	 * When we increase the min width for the login form, we increase it also for the wrapper.
	 * @param selector
	 * @param rules
	 * @param media
	 */
	'tcb.write_css.tvd-login-form': ( selector, rules, media ) => {
		if ( typeof rules[ 'min-width' ] !== 'undefined' && selector === TVE.identifier( 'tvd-login-form' ) ) {
			const formWrapperSelector = TVE.identifier( 'tvd-login-form-wrapper' ),
				$wrapper = TVE.inner_$( formWrapperSelector ),
				width = $wrapper.outerWidth();

			if ( width <= parseInt( rules[ 'min-width' ] ) ) {
				TVE.write_css( formWrapperSelector, rules, media );

				const maxWidth = parseInt( $wrapper.head_css( 'max-width', media ) ) || 0;
				if ( maxWidth <= parseInt( rules[ 'min-width' ] ) ) {
					TVE.write_css( formWrapperSelector, {'max-width': rules[ 'min-width' ]}, media );
				}
			}
		}
	},
	/**
	 * Remove css before applying a new template
	 */
	'tcb_after_cloud_template': () => {
		TVE.head_css_remove( /.*/, '_ALL' )
	},
	/**
	 * Fix logo after we select cloud template
	 */
	'tcb_after_cloud_template_css_inserted': () => {
		/* always set our logo when loading a cloud template */
		TVE.inner_$( '#login > h1 > a' ).head_css( {
			'background-image': `url(${tvd_login_editor.logo})`
		} );

		setTimeout( () => {
			TVE.Components[ 'tvd-login-logo' ].fixLogoRatio( tvd_login_editor.logo ).fixHidePasswordButton();
		}, 24 )
	}
}
