( ( $ ) => {
	module.exports = TVE.modal.base.extend( {
		after_initialize: function () {
			/* make the modal medium-small size */
			this.$el.addClass( 'medium-small' );
		},
		reset: function () {
			$.ajax( {
				url: ajaxurl,
				dataType: 'json',
				type: 'POST',
				data: {
					_wpnonce: TVE.CONST.dash_nonce,
					action: 'tve_dash_backend_ajax',
					route: 'resetPostStyle',
					post_id: TVE.CONST.post_id
				}
			} ).success( () => {
				TVE.page_message( 'Design has now reset to default.', false, 5000 );

				TVE.iframe_refresh();

				TVE.Editor_Page.blur();
			} ).error( () => {
				TVE.page_message( 'Unable to reset design.', true, 5000 );
			} ).always( () => {
				this.close();
			} );
		}
	} );
} )( jQuery );
