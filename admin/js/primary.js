var frame,
    rexvs = rexvs || {};

jQuery( document ).ready( function ( $ ) {
    'use strict';
    var wp = window.wp,
        $body = $( 'body' );

    $( '#term-color' ).wpColorPicker();

    // Update attribute image
    $body.on( 'click', '.rexvs-upload-image-button', function ( event ) {
        event.preventDefault();

        var $button = $( this ).prev();

        // If the media frame already exists, reopen it.
        if ( frame ) {
            frame.open();
            return;
        }

        // Create the media frame.
        frame = wp.media.frames.downloadable_file = wp.media( {
            title: rexvs.i18n.mediaTitle,
            button: {
                text: rexvs.i18n.mediaButton
            },
            multiple: false
        } );

        // When an image is selected, run a callback.
        frame.on( 'select', function () {
            var attachment = frame.state().get( 'selection' ).first().toJSON();
            $button.val( attachment.id );
            $button.siblings( '.rexvs-remove-image-button' ).show();
            $button.parent().prev( '.rexvs-term-image-thumbnail' ).find( 'img' ).attr( 'src', attachment.sizes.thumbnail.url );
        } );

        // Finally, open the modal.
        frame.open();

    } ).on( 'click', '.rexvs-remove-image-button', function () {
        var $button = $( this );

        $button.siblings( 'input.rexvs-term-image' ).val( '' );
        $button.siblings( '.rexvs-remove-image-button' ).show();
        $button.parent().prev( '.rexvs-term-image-thumbnail' ).find( 'img' ).attr( 'src', rexvs.placeholder );

        return false;
    } );

    // Toggle add new attribute term modal
    var $modal = $( '#rexvs-modal-container' ),
        $spinner = $modal.find( '.spinner' ),
        $msg = $modal.find( '.message' ),
        $metabox = null;

    $body.on( 'click', '.rexvs_add_new_attribute', function ( e ) {
        e.preventDefault();
        var $button = $( this ),
            taxInputTemplate = wp.template( 'rexvs-input-tax' ),
            data = {
                type: $button.data( 'type' ),
                tax: $button.closest( '.woocommerce_attribute' ).data( 'taxonomy' )
            };

        // Insert input
        $modal.find( '.rexvs-term-swatch' ).html( $( '#tmpl-rexvs-input-' + data.type ).html() );
        $modal.find( '.rexvs-term-tax' ).html( taxInputTemplate( data ) );

        if ( 'color' == data.type ) {
            $modal.find( 'input.rexvs-input-color' ).wpColorPicker();
        }

        $metabox = $button.closest( '.woocommerce_attribute.wc-metabox' );
        $modal.show();
    } ).on( 'click', '.rexvs-modal-close, .rexvs-modal-backdrop', function ( e ) {
        e.preventDefault();

        closeModal();
    } );

    // Send ajax request to add new attribute term
    $body.on( 'click', '.rexvs-new-attribute-submit', function ( e ) {
        e.preventDefault();

        var $button = $( this ),
            type = $button.data( 'type' ),
            error = false,
            data = {};

        // Validate
        $modal.find( '.rexvs-input' ).each( function () {
            var $this = $( this );

            if ( $this.attr( 'name' ) != 'slug' && !$this.val() ) {
                $this.addClass( 'error' );
                error = true;
            } else {
                $this.removeClass( 'error' );
            }

            data[ $this.attr( 'name' ) ] = $this.val();
        } );

        if ( error ) {
            return;
        }

        // Send ajax request
        $spinner.addClass( 'is-active' );
        $msg.hide();
        wp.ajax.send( 'rexvs_add_new_attribute', {
            data: data,
            error: function ( res ) {
                $spinner.removeClass( 'is-active' );
                $msg.addClass( 'error' ).text( res ).show();
            },
            success: function ( res ) {
                $spinner.removeClass( 'is-active' );
                $msg.addClass( 'success' ).text( res.msg ).show();

                $metabox.find( 'select.attribute_values' ).append( '<option value="' + res.id + '" selected="selected">' + res.name + '</option>' );
                $metabox.find( 'select.attribute_values' ).change();

                closeModal();
            }
        } );
    } );

    /**
     * Close modal
     */
    function closeModal() {
        $modal.find( '.rexvs-term-name input, .rexvs-term-slug input' ).val( '' );
        $spinner.removeClass( 'is-active' );
        $msg.removeClass( 'error success' ).hide();
        $modal.hide();
    }
} );
