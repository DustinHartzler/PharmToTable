/**
 * Created by Dan Brinzaru on 4/25/2019.
 */

( function ( $ ) {

	var base = require( './base' );

	module.exports = base.extend( {
		render: function () {
			this.$el.html( TVE_Dash.tpl( 'ni-notification-detail' )( {model: this.model} ) );

			/**
			 * Set read status only for unreaded notifications
			 */
			if ( this.model.get( 'read' ) === 'unread' ) {
				this.save();
			}

			return this;
		},
		save: function () {
			var self = this;
			$.ajax( {
				type: 'POST',
				url: TD_Inbox.ajaxurl,
				data: {
					'action': 'thrv_notifications',
					'notification_id': self.model.get( 'id' ),
					'_nonce': TD_Inbox.admin_nonce,
				}
			} ).success( function ( response ) {

				var response = JSON.parse( response );
				if ( typeof response.total_unread !== 'undefined' ) {
					TD_Inbox.total_unread = response.total_unread;

					if ( TD_Inbox.total_unread >= 1 ) {
						self.$( '.ni-counter-holder' ).show();
					}
					self.model.set( {read: "read"} );
				}

			} ).error( function ( response ) {
				TVE_Dash.err( response.responseJSON.error );
			} );
		}
	} );

} )( jQuery );
