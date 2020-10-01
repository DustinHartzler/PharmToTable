/**
 * Created by Dan Brinzaru on 4/9/2019.
 */

module.exports = Backbone.Model.extend( {

	idAttribute: 'ID',

	defaults: {
		title: '',
		read: 0
	},

	initialize: function ( attrs ) {
		this.set( 'read', attrs.read === 0 ? 'unread' : 'read' );
	},

	get_action: function () {
		return 'notification';
	},

	url: function () {

		var url = ajaxurl + ( this.get_action() + '&' + this.get_route() );

		if ( $.isNumeric( this.get( 'ID' ) ) ) {
			url += '&ID=' + this.get( 'ID' );
		}

		return url;
	}
} );
