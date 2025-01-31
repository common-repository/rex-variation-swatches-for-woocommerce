;(function ( $ ) {
    'use strict';

    /**
     * @desc Code a function the calculate available combination
     * instead of use WC hooks for shop page
     */
    $.fn.rexvs_variation_swatches_form = function () {
        return this.each( function () {
            var $form = $( this ),
                clicked = null,
                selected = [];

            $form
                .addClass( 'swatches-support' )
                .on( 'click', '.swatch', function ( e ) {
                    e.preventDefault();

                    $( this ).parents( '.rexvs-variations' ).find( '.variation_notice' ).hide();

                    var $el = $( this ),
                        $select = $el.closest( '.value' ).find( 'select' ),
                        attribute_name = $select.data( 'attribute_name' ) || $select.attr( 'name' ),
                        value = $el.data( 'value' );

                    $select.trigger( 'focusin' );

                    // Check if this combination is available
                    if ( !$select.find( 'option[value="' + value + '"]' ).length ) {
                        $el.siblings( '.swatch' ).removeClass( 'selected' );
                        $select.val( '' ).change();
                        $( this ).parents( '.rexvs-variations' ).find( '.variation_notice' ).show();
                        return;
                    }

                    clicked = attribute_name;

                    if ( selected.indexOf( attribute_name ) === -1 ) {
                        selected.push( attribute_name );
                    }

                    if ( $el.hasClass( 'selected' ) ) {
                        $select.val( '' );
                        $el.removeClass( 'selected' );

                        delete selected[ selected.indexOf( attribute_name ) ];
                    } else {
                        $el.addClass( 'selected' ).siblings( '.selected' ).removeClass( 'selected' );
                        $select.val( value );
                    }

                    $select.change();
                } )
                .on( 'click', '.reset_variations', function () {
                    $( this ).closest( '.variations_form' ).find( '.swatch.selected' ).removeClass( 'selected' );
                    selected = [];
                    $( this ).parents( '.rexvs-variations' ).find( '.variation_notice' ).hide();
                } )
                .on( 'rexvs_no_matching_variations', function () {
                    $( this ).parents( '.rexvs-variations' ).find( '.variation_notice' ).show();
                } );
        } );
    };

    $( function () {
        $( '.variations_form' ).rexvs_variation_swatches_form();
        $( document.body ).trigger( 'rexvs_initialized' );
    } );
})( jQuery );