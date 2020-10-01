/**
 * Created by Dan Brinzaru on 4/25/2019.
 */

( function ( $ ) {

	var base = require( './base' );

	module.exports = base.extend( {
		events: {
			'click .tvd-ni-notifications': 'open',
			'click .ni-counter-holder': 'open'
		},
		initialize: function () {

			var self = this;
			this.listenTo( this.collection, 'change:read', self.render );
			this.collection.on( 'bulk_update', function () {
				self.render();
			} );
		},
		render: function () {
			this.$( '.ni-counter-holder' ).html( TD_Inbox.total_unread );

			if ( TD_Inbox.total_unread >= 1 ) {
				this.$( '.ni-counter-holder' ).show();
			} else {
				this.$( '.ni-counter-holder' ).hide();
			}

			return this;
		},
		open: function () {
			$( '.tvd-inbox-holder' ).toggle();
		}
	} );

} )( jQuery );
