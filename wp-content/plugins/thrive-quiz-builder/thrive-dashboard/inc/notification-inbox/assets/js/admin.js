/**
 * Created by Dan Brinzaru on 4/9/2019.
 */

TD_Inbox = TD_Inbox || {};

( function ( $ ) {

	var notificationsList = require( './views/notificationsList' ),
		notification_counter = require( './views/notificationCounter' );

	var notificationCollection = require( './collections/notifications' );

	$( function () {
		var collection = new notificationCollection( TD_Inbox.data );
		var view = new notificationsList( {
			el: $( '.tvd-inbox-holder' ),
			collection: collection
		} );

		view.render();

		new notification_counter( {
			el: $( '.ni-inbox-counter' ),
			collection: collection
		} ).render();

		if ( $( '.tvd-ni-notifications' ).length && $( '.tvd-inbox-holder' ).length ) {
			$( document ).click( function ( event ) {

				if ( $( event.target ).is( '.tvd-ni-notifications, .ni-counter-holder' ) ) {
					return;
				}

				/**
				 * click outside the wrapper
				 */
				if ( $( event.target ).closest( '.tvd-inbox-holder' ).length <= 0 ) {
					$( "body" ).find( ".tvd-inbox-holder" ).hide();
				}
			} );
		}
	} )

} )( jQuery );
