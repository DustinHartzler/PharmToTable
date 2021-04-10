/**
 * Displays toast message from storage, it is used when the user is redirected after login
 */
if ( window.sessionStorage ) {
	window.addEventListener( 'load', function () {
		var message = sessionStorage.getItem( 'tcb_toast_message' );

		if ( message ) {
			tcbToast( sessionStorage.getItem( 'tcb_toast_message' ), false );
			sessionStorage.removeItem( 'tcb_toast_message' );
		}
	} );
}

/**
 * Displays toast message
 */
function tcbToast( message, error, callback ) {
	/* Also allow "message" objects */
	if ( typeof message !== 'string' ) {
		message = message.message || message.error || message.success;
	}
	if ( ! error ) {
		error = false;
	}

	let _icon = 'checkmark',
		_extra_class = '';
	if ( error ) {
		_icon = 'cross';
		_extra_class = ' tve-toast-error';
	}

	jQuery( 'body' ).slideDown( 'fast', function () {
		jQuery( 'body' ).prepend( '<div class="tvd-toast tve-fe-message"><div class="tve-toast-message"><div class="tve-toast-icon-container' + _extra_class + '"><span class="tve_tick thrv-svg-icon"><svg xmlns="http://www.w3.org/2000/svg" class="tcb-checkmark" style="width: 100%; height: 1em; stroke-width: 0; fill: #ffffff; stroke: #ffffff;" viewBox="0 0 32 32"><path d="M27 4l-15 15-7-7-5 5 12 12 20-20z"></path></svg></span></div><div class="tve-toast-message-container">' + message + '</div></div></div>' );
	} );

	setTimeout( function () {
		jQuery( '.tvd-toast' ).hide();

		if ( typeof callback === 'function' ) {
			callback();
		}

	}, 3000 );
}
