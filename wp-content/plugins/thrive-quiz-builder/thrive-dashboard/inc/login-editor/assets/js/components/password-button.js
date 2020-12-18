module.exports = TVE.Views.Base.component.extend( {
	/**
	 * Initialize controls
	 * @param controls
	 */
	controls_init: function ( controls ) {

		controls[ 'Color' ].input = function ( color ) {
			this.applyElementCss( {'color': `${color.toString()}!important`} );
		};

		controls[ 'Color' ].update = function () {
			this.setValue( TVE.ActiveElement.head_css( 'color' ) );
		};
	}
} );
