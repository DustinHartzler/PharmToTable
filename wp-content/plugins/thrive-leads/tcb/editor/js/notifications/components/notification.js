module.exports = TVE.Views.Base.component.extend( {
	after_init() {
		/* backwards compatibility stuff */
		TVE.Editor_Page.editor.find( '.animated' ).removeClass( 'animated' );
	},
	controls_init: function ( controls ) {
		/* Display Position Control  */
		controls[ 'DisplayPosition' ].input = function ( $element, dom ) {
			let positionAttribute = dom.getAttribute( 'data-value' ),
				horizontalPosition = positionAttribute.split( '-' )[ 1 ];
			const verticalPosition = positionAttribute.split( '-' )[ 0 ];

			if ( TVE.main.device === 'mobile' ) {
				horizontalPosition = $element.parent().attr( 'data-position' ).split( '-' )[ 1 ];
			}

			$element.parent().attr( 'data-position', verticalPosition.concat( '-', horizontalPosition ) );

			updateVerticalSpacing( $element );
			updateHorizontalSpacing( $element );
		};

		controls[ 'DisplayPosition' ].update = function ( $element ) {
			let positionAttribute = $element.parent().attr( 'data-position' );

			this.$( '.items-9' ).removeClass( 'mobile' );
			this.$( '.active' ).removeClass( 'active' );

			if ( TVE.main.device === 'mobile' ) {
				positionAttribute = positionAttribute.split( '-' )[ 0 ].concat( '-center' );
				this.$( '.items-9' ).addClass( 'mobile' );
			}

			this.$( `[data-value=${positionAttribute}]` ).addClass( 'active' );
		};

		/* Vertical Spacing Control  */
		controls[ 'VerticalSpacing' ].input = function ( $element, dom ) {
			/* Only allow numerical values */
			if ( isNaN( dom.value ) ) {
				controls[ 'VerticalSpacing' ].setValue( 0 );
			}

			const verticalPosition = $element.parent().attr( 'data-position' ).split( '-' )[ 0 ];
			$element.parent().head_css( {[ verticalPosition ]: dom.value}, false, `.notifications-content-wrapper[data-position*="${verticalPosition}"]`, true, '' );
		};

		controls[ 'VerticalSpacing' ].update = function ( $element ) {
			updateVerticalSpacing( $element );
		};

		/* Horizontal Spacing Control  */
		controls[ 'HorizontalSpacing' ].input = function ( $element, dom ) {
			/* Only allow numerical values */
			if ( isNaN( dom.value ) ) {
				controls[ 'VerticalSpacing' ].setValue( 0 );
			}

			const horizontalPosition = $element.parent().attr( 'data-position' ).split( '-' )[ 1 ];
			$element.parent().head_css( {[ horizontalPosition ]: dom.value}, false, `.notifications-content-wrapper[data-position*="${horizontalPosition}"]`, true, '' );
		};

		controls[ 'HorizontalSpacing' ].update = function ( $element ) {
			updateHorizontalSpacing( $element );
		};

		/* Animation Direction Control  */
		controls[ 'AnimationDirection' ].input = function ( $element, dom ) {
			$element.parent().attr( 'data-animation', dom.value );
			$element.parent().toggleClass( 'tcb-animated', dom.value !== 'none' );
		};

		controls[ 'AnimationDirection' ].update = function ( $element ) {
			this.setValue( $element.parent().attr( 'data-animation' ) );
		};

		/* Animation Time Control  */
		controls[ 'AnimationTime' ].input = function ( $element, dom ) {
			$element.parent().attr( 'data-timer', dom.value );
		};

		controls[ 'AnimationTime' ].update = function ( $element ) {
			if ( $element.parent().attr( 'data-timer' ) < 0 ) {
				$element.parent().attr( 'data-timer', 3000 );
			}
			this.setValue( $element.parent().attr( 'data-timer' ) );
		};

		function updateVerticalSpacing( $element ) {
			const position = $element.parent().attr( 'data-position' ).split( '-' ),
				verticalPosition = position[ 0 ];

			/* Vertical Spacing Control Update */
			if ( [ 'top', 'bottom' ].includes( verticalPosition ) ) {
				controls[ 'VerticalSpacing' ].$el.show();
				controls[ 'VerticalSpacing' ].$el.find( '.input-label' ).text( `${verticalPosition} spacing` );
				controls[ 'VerticalSpacing' ].setValue( $element.parent().css( `${verticalPosition}` ).split( 'px' )[ 0 ] );
			} else {
				controls[ 'VerticalSpacing' ].hide();
			}
		}

		function updateHorizontalSpacing( $element ) {
			const position = $element.parent().attr( 'data-position' ).split( '-' ),
				horizontalPosition = position[ 1 ];

			/* Horizontal Spacing Control Update */
			if ( ( [ 'left', 'right' ].includes( horizontalPosition ) ) && ! ( TVE.main.device === 'mobile' ) ) {
				controls[ 'HorizontalSpacing' ].$el.show();
				controls[ 'HorizontalSpacing' ].setValue( $element.parent().css( `${horizontalPosition}` ).split( 'px' )[ 0 ] );
			} else {
				controls[ 'HorizontalSpacing' ].hide();
			}
		}
	},

	edit_notifications: function () {
		const notificationWrapper = TVE.inner_$( '.notifications-content-wrapper' ),
			state = notificationWrapper.attr( 'data-state' ),
			main = require( '../main' );

		TVE.main.sidebar_extra.$( '.sidebar-item.add-element' ).show();
		notificationWrapper.find( '.tve-prevent-content-edit' ).removeClass( 'tve-prevent-content-edit' );

		TVE.main.EditMode.enter( notificationWrapper, {
			show_default_message: true,
			can_insert_elements: true,
			view_label: 'Editing Notification',
			prevent_default_functions: true,
			element_selectable: true,
			restore_state: false,
			states: [
				{
					label: 'Success',
					value: 'success',
					default: state === 'success',
				},
				{
					label: 'Warning',
					value: 'warning',
					default: state === 'warning',
				},
				{
					label: 'Error',
					value: 'error',
					default: state === 'error',
				}
			],
			callbacks: {
				exit: () => {
					/* Restore editor settings */
					const state = TVE.inner_$( '.notifications-content-wrapper' ).attr( 'data-state' );

					TVE.main.sidebar_extra.$( '.sidebar-item.add-element' ).hide();
					TVE.inner_$( '.tcb-compact-edit-mode' ).removeClass( 'tcb-compact-edit-mode' );
					TVE.inner_$( `.notifications-content.notification-${state}` ).children().addClass( 'tve-prevent-content-edit' );

					/* Set focus on the notification element */
					TVE.Editor_Page.focus_element( TVE.inner_$( `.notification-${state}` ) );
					TVE.main.EditMode.$componentPanel.find( '#tve-notification-component' ).show();

					/* Add corresponding link for the preview button */
					const previewButton = TVE.$( '.preview-content' ),
						previewLink = previewButton.attr( 'href' ).split( '&notification-state=' )[ 0 ].concat( `&notification-state=${state}` );
					previewButton.attr( 'href', previewLink );
				},
				state_change: ( state ) => {
					TVE.inner_$( '.tcb-compact-edit-mode' ).removeClass( 'tcb-compact-edit-mode' );
					TVE.inner_$( '.notifications-content-wrapper' ).attr( 'data-state', state );
					TVE.Editor_Page.focus_element( TVE.inner_$( `.notifications-content.notification-${state}` ).addClass( 'tcb-compact-edit-mode' ) );
					TVE.main.EditMode.$componentPanel.find( '#tve-notification-component' ).hide();
					main._updateMainFrameVars( state );
				}
			}
		} );
	},

	/**
	 * Reset the style of the notifications to the default style
	 */
	resetStyle: function () {
		const currentState = TVE.inner_$( '.notifications-content-wrapper' ).attr( 'data-state' ),
			main = require( '../main' );

		TVE.ajax( 'get_default_notification', 'GET' ).done( ( response ) => {
			TVE.Editor_Page.editor.html( response[ 'html' ] );
			TVE.Editor_Page.focus_element( TVE.inner_$( `.notifications-content-wrapper .notification-${currentState}` ) );
			TVE.Editor_Page.$body.find( '.notifications-content-wrapper' ).addClass( 'notification-edit-mode' ).attr( 'data-state', currentState );

			main.applyDefaultRules( false );
			main.initializeNotificationEditor( currentState );

			[ 'desktop', 'tablet', 'mobile' ].forEach( device => {
				TVE.CSS_Rule_Cache.removeRuleByMatchingSelector( TVE.main.responsive[ device ].media, '.notifications-content' );
			} );
		} );
	},
} );
