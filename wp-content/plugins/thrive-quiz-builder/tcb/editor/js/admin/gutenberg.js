( function ( $ ) {
	var ThriveGutenbergSwitch = {

		/**
		 * Check if we're using the block editor
		 * @returns {boolean}
		 */
		isGutenbergActive: function () {
			return typeof wp !== 'undefined' && typeof wp.blocks !== 'undefined';
		},

		init: function () {
			var $gutenberg = $( '#editor' ),
				$architectNotificationContent = $( '#thrive-gutenberg-switch' ).html(),
				$architectDisplay = $( '<div>' ).append( $architectNotificationContent ),
				$architectLauncher = $architectDisplay.find( '#thrive_preview_button' );

			setTimeout( function () {
				if ( $architectNotificationContent.indexOf( 'postbox' ) !== - 1 ) {
					$gutenberg.find( '.editor-post-title' ).append( $architectDisplay );
					$gutenberg.find( '.editor-block-list__layout,.block-editor-block-list__layout' ).hide();
					$gutenberg.find( '.editor-post-title__block' ).css( 'margin-bottom', '0' );
					$gutenberg.find( '.editor-writing-flow__click-redirect,.block-editor-writing-flow__click-redirect' ).hide();
					$gutenberg.find( '.edit-post-header-toolbar' ).css( 'visibility', 'hidden' );
				} else {
					$gutenberg.find( '.edit-post-header-toolbar' ).append( $architectLauncher );
					$architectLauncher.on( 'click', function () {
						$gutenberg.find( '.editor-block-list__layout' ).hide();
					} );
					$gutenberg.find( '.edit-post-header-toolbar' ).css( 'visibility', 'visible' );
				}

				$( '#tcb2-show-wp-editor' ).on( 'click', function () {
					var $editlink = $gutenberg.find( '.tcb-enable-editor' ),
						$postbox = $editlink.closest( '.postbox' );

					$.ajax( {
						type: 'post',
						url: ajaxurl,
						dataType: 'json',
						data: {
							_nonce: TCB_Post_Edit_Data.admin_nonce,
							post_id: this.getAttribute( 'data-id' ),
							action: 'tcb_admin_ajax_controller',
							route: 'disable_tcb'
						}
					} ).done( function ( response ) {
					} );

					$postbox.next( '.tcb-flags' ).find( 'input' ).prop( 'disabled', false );
					$postbox.remove();
					$gutenberg.find( '.editor-block-list__layout' ).show();
					$gutenberg.find( '.edit-post-header-toolbar' ).append( $architectLauncher );
					$gutenberg.find( '.edit-post-header-toolbar' ).css( 'visibility', 'visible' );
				} );

				$architectLauncher.on( 'click', function () {
					$.ajax( {
						type: 'post',
						url: ajaxurl,
						dataType: 'json',
						data: {
							_nonce: TCB_Post_Edit_Data.admin_nonce,
							post_id: this.getAttribute( 'data-id' ),
							action: 'tcb_admin_ajax_controller',
							route: 'change_post_status_gutenberg'
						}
					} )
				} );

				$( window ).on( 'storage.tcb', function ( e ) {
					var current_post = wp.data.select( "core/editor" ).getCurrentPost(),
						post;

					try {
						post = JSON.parse( e.originalEvent.newValue );
					} catch ( e ) {

					}

					if ( post && post.ID && e.originalEvent.key === 'tve_post_options_change' && post.ID === Number( current_post.id ) ) {
						window.location.reload();
					}
				} );

				wp.data.subscribe( function () {
					var coreEditor = wp.data.select( 'core/editor' );
					if ( coreEditor ) {
						var isSavingPost = coreEditor.isSavingPost(),
							isAutosavingPost = coreEditor.isAutosavingPost();

						if ( isSavingPost && ! isAutosavingPost ) {
							var data = JSON.stringify( coreEditor.getCurrentPost() );

							window.localStorage.setItem( 'tve_post_options_change', data );
						}
					}
				} )

			}, 200 );


		}
	};

	$( function () {
		if ( ThriveGutenbergSwitch.isGutenbergActive() ) {
			ThriveGutenbergSwitch.init();
		}
		window.addEventListener( 'load', function () {
			$( '.tcb-revert' ).on( 'click', function () {
				if ( confirm( 'Are you sure you want to DELETE all of the content that was created in this landing page and revert to the theme page? \n If you click OK, any custom content you added to the landing page will be deleted.' ) ) {
					location.href = location.href + '&tve_revert_theme=1';
					$( '#editor' ).find( '.edit-post-header-toolbar' ).css( 'visibility', 'visible' );
				}
			} );
		} );
	} );

}( jQuery ) );
