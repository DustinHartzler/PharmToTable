( function ( $ ) {
	_.templateSettings = {
		evaluate: /<#([\s\S]+?)#>/g,
		interpolate: /<#=([\s\S]+?)#>/g,
		escape: /<#-([\s\S]+?)#>/g
	};
	/*
	 * This is needed to emulate HTTP methods other than GET/POST
	 * Not all hosts allow PUT/DELETE/PATCH etc.
	 */
	Backbone.emulateHTTP = true;

	var ScriptManager = ScriptManager || {};

	$.extend( ScriptManager, {
		sm_router: Backbone.Router.extend( {

			view: null,
			$el: $( '#tvd-sm-container' ),

			routes: {
				'': 'dashboard'
			},

			dashboard: function () {
				if ( this.view ) {
					this.view.remove();
				}
				this.view = new ScriptManager.views.ScriptDashboard( {
					el: this.$el
				} )
			}
		} ),

		utils: require( '../utils' ),
		models: require( './models' ),
		views: require( './views' )
	} );

	ScriptManager.router = new ScriptManager.sm_router();

	Backbone.history.start( {hashchange: true} );
} )( jQuery );