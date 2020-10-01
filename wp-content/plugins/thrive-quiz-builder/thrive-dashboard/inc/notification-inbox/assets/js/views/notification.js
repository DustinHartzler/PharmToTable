/**
 * Created by Dan Brinzaru on 4/9/2019.
 */

( function ( $ ) {

	var base = require( './base' );

	module.exports = base.extend( {
		render: function () {
			this.$el.append( TVE_Dash.tpl( 'ni-notification' )( {model: this.model} ) );

			return this;
		}
	} );

} )( jQuery );
