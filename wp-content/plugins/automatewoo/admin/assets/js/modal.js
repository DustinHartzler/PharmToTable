// Register eslint ignored glabals - to be revisited.
// https://github.com/woocommerce/automatewoo/issues/1212
/* global AutomateWoo, AW, CustomEvent */
/**
 * AutomateWoo Modal
 */
jQuery( function ( $ ) {
	AutomateWoo.Modal = {
		init() {
			$( document.body ).on(
				'click',
				'.js-close-automatewoo-modal',
				this.close
			);
			$( document.body ).on(
				'click',
				'.automatewoo-modal-overlay',
				this.close
			);
			$( document.body ).on(
				'click',
				'.js-open-automatewoo-modal',
				this.handle_link
			);

			$( document ).on( 'keydown', function ( e ) {
				if ( e.keyCode === 27 ) {
					AutomateWoo.Modal.close();
				}
			} );
		},

		handle_link( e ) {
			e.preventDefault();

			const $a = $( this );
			const type = $a.data( 'automatewoo-modal-type' );
			const size = $a.data( 'automatewoo-modal-size' );

			if ( type === 'ajax' ) {
				AutomateWoo.Modal.open( type, size );
				AutomateWoo.Modal.loading();

				$.post( $a.attr( 'href' ), {}, function ( response ) {
					AutomateWoo.Modal.contents( response );
				} );
			} else if ( type === 'inline' ) {
				const contents = $(
					$a.data( 'automatewoo-modal-contents' )
				).html();
				AutomateWoo.Modal.open( type, size );
				AutomateWoo.Modal.contents( contents );
			}
		},

		open( type, size ) {
			const classes = [ 'automatewoo-modal--type-' + type ];

			if ( size ) {
				classes.push( 'automatewoo-modal--size-' + size );
			}

			$( document.body )
				.addClass( 'automatewoo-modal-open' )
				.append(
					'<div class="automatewoo-modal-container"><div class="automatewoo-modal-overlay"></div><div class="automatewoo-modal ' +
						classes +
						'"><div class="automatewoo-modal__contents"><div class="automatewoo-modal__header"></div></div><div class="automatewoo-icon-close js-close-automatewoo-modal"></div></div></div>'
				);
		},

		loading() {
			$( document.body ).addClass( 'automatewoo-modal-loading' );
		},

		contents( contents ) {
			$( document.body ).removeClass( 'automatewoo-modal-loading' );
			$( '.automatewoo-modal__contents' ).html( contents );

			AW.initTooltips();
		},

		/**
		 * Closes modal, by changin classes on `document.body` and removing modal elements.
		 *
		 * @fires awmodal-close on the `document.body`.
		 */
		close() {
			$( document.body ).removeClass(
				'automatewoo-modal-open automatewoo-modal-loading'
			);
			$( '.automatewoo-modal-container' ).remove();

			// Fallback to Event in the browser does not support CustomEvent, like IE.
			const eventCtor =
				typeof CustomEvent === 'undefined' ? Event : CustomEvent;
			document.body.dispatchEvent( new eventCtor( 'awmodal-close' ) );
		},
	};

	AutomateWoo.Modal.init();
} );
