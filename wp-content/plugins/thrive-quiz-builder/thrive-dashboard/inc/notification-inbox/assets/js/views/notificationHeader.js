/**
 * Created by Dan Brinzaru on 4/25/2019.
 */

( function ( $ ) {

	var base = require( './base' );

	module.exports = base.extend( {
		render: function () {
			this.$el.html( TVE_Dash.tpl( 'ni-notification-detail' )( {model: this.model} ) );

			return this;
		}
	} );

} )( jQuery );
