const {applyDefaultRules, initializeNotificationEditor, _updateMainFrameVars} = require( "./main" );
module.exports = {
	'tcb-ready': () => {
		const displayedState = TVE.inner_$( '.notifications-content-wrapper' ).attr( 'data-state' );

		/* Focus the notification element, open its Main Controls and update them */
		TVE.Editor_Page.focus_element( TVE.inner_$( `.notification-${displayedState}` ) );

		initializeNotificationEditor( displayedState );
		_updateMainFrameVars( displayedState );

		/* Update the preview link with the current state */
		const previewButton = TVE.$( '.preview-content' );

		previewButton.attr( 'href', previewButton.attr( 'href' ).concat( '&notification-state=success' ) );

		/* Only display a set of allowed elements that can be added inside the Notification element */
		const allowedElements = [ 'text', 'image', 'button', 'columns', 'contentbox', 'divider', 'icon', 'notification_message' ];

		const hiddenElements = Object.keys( TVE.Elements ).filter( element => ! allowedElements.includes( element ) );

		TVE.main.sidebar_toggle_elements( hiddenElements, false );

		if ( TVE.stylesheet.cssRules.length === 0 ) {
			applyDefaultRules( true )
		}
	},

	'tcb.element.focus': ( $element ) => {
		if ( $element.is( '.notifications-content' ) ) {
			/* Disable Margin Control for the Notification Element */
			TVE.Components.layout.disable_extra_controls( [ 'top', 'right', 'bottom', 'left' ].map( side => 'margin-' + side ) );

			/* In edit mode, hide the main controls of the notification element */
			if ( TVE.$body.hasClass( 'edit-mode-active' ) ) {
				TVE.main.EditMode.$componentPanel.find( '#tve-notification-component' ).hide();
			}
		}
	},
	'tcb.after-insert': ( $element ) => {
		if ( $element.is( '.thrv-notification_message' ) ) {
			$element.addClass( 'tcb-selector-no_save tcb-selector-no_clone' )
		}
	}
};
