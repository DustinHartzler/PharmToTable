( function( $ ) {
    var item = $( '#setting-facebook > .wc-ct-form-group' ).length;
    var i = item + 1;
    $( 'body' ).on( 'click', '#add-new-pixel', function( e ) {
        e.preventDefault();

        newDiv = $( ' #setting-facebook > .wc-ct-form-group' ).clone();
        newDiv.find( 'input' ).each( function(){
            var attr = this.name.replace('[0]', '['+i+']');
            name =  $( this ).attr( 'name', attr );
        } );

        i++;
        $( ' #setting-facebook > .inner-setting' ).append( newDiv );
        $('#setting-facebook > .inner-setting > .wc-ct-form-group').last().append('<button id="remove" class="button button-secondary button-small" style="float:right;margin-right: 12px;margin-top: -30px;"><span class="dashicons dashicons-trash"></span></button>');
    });

    // Remove Button
    $('body').on( 'click', '#remove', function( e ) {
        e.preventDefault();
        $( this ).parent().remove();
    } );


    // Copy Clip board
    // Copy Clip board
    var clipboard = new Clipboard('.wcct-copy-clipboard');
    clipboard.on('success', function(e) {
        $( '.clip-board-tooltip' ).text('Copied!').css({'width' : '60px', 'margin-left' : '-32px'});
    });

    $( '.wcct-tooltip' ).on( 'mouseout', function(){
        $( '.clip-board-tooltip' ).text('Copy to clipboard').css({'width' : '100px', 'margin-left' : '-60px'});
    } );

    // save permission
    $( '#wcct-save-permission' ).on( 'submit', function( e ) {
        e.preventDefault();

        var form = $(this),
            button = form.find('button');

        button.addClass( 'updating-message' );

        wp.ajax.send( 'wcct_save_permissions', {
            data: form.serialize(),
            success: function( response ) {

                $("#ajax-message")
                    .html('<p><strong>' + response.message + '</strong></p>')
                    .show()
                    .delay(3000)
                    .slideUp('fast');

                $('html, body').animate({
                    scrollTop: 0
                }, 'fast');

                button.removeClass( 'updating-message' );
            },
            error: function( error ) {
                alert('something wrong happend');
            }
        });

        return false;
    });

    // Set authentication

    $( '#wcct-authenticate-form-enable' ).on( 'change', function(){
        $( '.authenticate-form' ).stop().toggle();
    } );


    if ( $( '#wcct-authenticate-form-enable' ).is( ':checked' ) ) {
        $( '.authenticate-form' ).css( 'display', 'table-row' );
    }

    // Save authentication data
    $( '#wcct-authenticate-submit' ).on( 'click', function( e ) {
        e.preventDefault();
        $( '#wcct-authenticate-submit' ).addClass( 'updating-message' );

        wp.ajax.send( 'wcct_save_authenticate', {
            data: $( '#wcct-authenticate-form' ).serialize(),
            success: function( response ) {

                $("#ajax-message")
                    .html('<p><strong>' + response.message + '</strong></p>')
                    .show()
                    .delay(3000)
                    .slideUp('fast');

                $('html, body').animate({
                    scrollTop: 0
                }, 'fast');

                $( '#wcct-authenticate-submit' ).removeClass( 'updating-message' );
            },
            error: function( error ) {
                alert('something wrong happend');
            }
        });

        return false;
    });


})( jQuery );