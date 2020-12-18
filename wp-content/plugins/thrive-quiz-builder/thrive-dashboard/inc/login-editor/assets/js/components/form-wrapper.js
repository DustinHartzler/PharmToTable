module.exports = TVE.Views.Base.component.extend( {
	/**
	 * Initialize controls
	 * @param controls
	 */
	controls_init: function ( controls ) {

		controls[ 'HorizontalPosition' ].input = function ( $element, button ) {
			TVE.inner.$body.head_css( {'justify-content': button.dataset.value} )
		};

		controls[ 'HorizontalPosition' ].update = function () {
			let align = TVE.inner.$body.head_css( 'justify-content' );

			if ( ! align ) {
				align = 'center';
			}

			this.setValue( align );
		};

		controls[ 'VerticalPosition' ].input = function ( $element, button ) {
			TVE.inner.$body.head_css( {'align-items': button.dataset.value} );

			$element.head_css( {
				'justify-content': button.dataset.value,
				'margin-top': 0,
				'margin-right': 0,
				'margin-bottom': 0,
				'margin-left': 0,
			} );
		};

		controls[ 'VerticalPosition' ].update = function () {
			let align = TVE.inner.$body.head_css( 'align-items' );

			if ( ! align ) {
				align = 'flex-start';
			}

			this.setValue( align );
		};

		controls[ 'FullHeight' ].input = function ( $element, input ) {
			if ( input.checked ) {
				$element.head_css( {
					'margin-top': 0,
					'margin-bottom': 0,
					'height': '100vh',
					'border': 0
				} );

				TVE.inner.$body.head_css( {
					'margin-top': 0,
					'padding-top': 0,
					'margin-bottom': 0,
					'padding-bottom': 0,
					'border': 0
				} );
			} else {
				$element.head_css( {
					'height': '',
				} );
			}
		};

		controls[ 'FullHeight' ].update = function ( $element ) {
			this.setValue( $element.outerHeight() === TVE.inner.$body.height() );
		};
	}
} );
