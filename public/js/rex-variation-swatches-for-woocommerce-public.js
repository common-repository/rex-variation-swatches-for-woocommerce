( function ( $ ) {
    'use strict';

    /**
     * @desc This function is to blur the out-of-stock
     * attributes
     */

    jQuery( document ).ready( function ( $ ) {
        var selected_count = 0;
        var selected_attr_name = [];
        var selected_attr = [];
        var prev_attr_name = '';
        var row_selected = [];
        var flag = false

        $( '.swatch' ).on( 'click', function ( e ) {
            var product_id = $( this ).parents( '.variations_form' ).attr( 'data-product_id' );
            var attribute_name = $( this ).parents( '.rexvs-swatches' ).attr( 'data-attribute_name' );
            var attribute_value = $( this ).attr( 'data-value' );
            var product_variation = jQuery.parseJSON( $( this ).parents( '.variations_form' ).attr( 'data-product_variations' ) );
            var prime_property = product_variation[ 0 ].attributes;

            var product_attr_count = Object.keys( prime_property ).length;

            var prime_index = '';
            var prime_value = '';

            var i = 0;
            $.each( prime_property, function ( pr_index, pr_val ) {
                if ( ! flag ) {
                    if ( row_selected.length === 0 ) {
                        row_selected[ pr_index ] = false;
                    }
                    flag = true;
                }
                if ( i == 0 ) {
                    prime_index = pr_index;
                    prime_value = pr_val;
                }
                i = 1;
            } );

            setTimeout( function () {
                var is_current_selected = $( '.variations_form[data-product_id|=' + product_id + ']' ).find( '.swatch-' + attribute_value ).hasClass( 'selected' );

                if ( is_current_selected === true ) {
                    if ( ! row_selected[ attribute_name ] ) {
                        selected_count++;
                        row_selected[ attribute_name ] = true;
                    }

                    prev_attr_name = attribute_name;
                    selected_attr_name[ attribute_name ] = attribute_value;
                    selected_attr = [];

                    for ( let property in selected_attr_name ) {
                        if ( selected_attr_name[ property ] !== '' ) {
                            selected_attr.push( selected_attr_name[ property ] );
                        }
                    }
                }
                else {
                    selected_attr_name[ attribute_name ] = '';
                    selected_attr = [];
                    selected_count--;
                    row_selected[ attribute_name ] = false;

                    for ( let property in selected_attr_name ) {
                        if ( selected_attr_name[ property ] !== '' ) {
                            selected_attr.push( selected_attr_name[ property ] );
                        }
                    }
                }

                $.each( product_variation, function ( index, val ) {
                    var attr = val.attributes;
                    var count = 0;
                    var flag = true;

                    $.each( attr, function ( index, val ) {
                        count++;
                        if ( count <= selected_count ) {
                            if ( selected_attr.indexOf( val ) === -1 ) {
                                flag = false;
                            }
                        }
                    } );

                    if ( flag === true && product_attr_count - selected_count === 1 ) {
                        var last_attr_value = attr[ Object.keys( attr )[ Object.keys( attr ).length - 1 ] ]

                        if ( ! val.is_in_stock ) {
                            $( '.variations_form[data-product_id|=' + product_id + ']' ).find( '.swatch-' + last_attr_value ).children('span').addClass('blurred-state');
                            $( '.swatch-' + last_attr_value ).addClass( 'rexvs-disable' );
                        }
                    }
                    else {
                        var last_attr_value = attr[ Object.keys( attr )[ Object.keys( attr ).length - 1 ] ]
                        if ( ! val.is_in_stock ) {
                            $( '.variations_form[data-product_id|=' + product_id + ']' ).find( '.swatch-' + last_attr_value ).children('span').removeClass('blurred-state');
                            $( '.swatch-' + last_attr_value ).removeClass( 'rexvs-disable' );
                        }
                    }
                } );
            }, 500 );
        } );

        $( '.reset_variations' ).on( 'click', function () {
            var product_id = $( this ).parents( '.variations_form' ).attr( 'data-product_id' );

            $( this ).parents( '.variations_form[data-product_id|=' + product_id + ']' ).find( '.swatch' ).removeClass( 'rexvs-disable' ).removeClass( 'rexvs-enable' );

            selected_count = 0;
            selected_attr = [];
            selected_attr_name = [];
            prev_attr_name = '';
            row_selected = [];
            flag = false;
        } );
    } );

} )( jQuery );

( function ( $ ) {
    'use strict';

    /**
     * @desc This function is to show cross-sign on
     * the no-variation attributes
     */

    jQuery( document ).ready( function ( $ ) {
        $( '.swatch' ).append( '<span class="disable-state"></span>' );
        $( '.swatch' ).on( 'click', function ( e ) {
            var product_id = $( this ).parents( '.variations_form' ).attr( 'data-product_id' );
            var attribute_name = $( this ).parents( '.rexvs-swatches' ).attr( 'data-attribute_name' );
            var attribute_value = $( this ).attr( 'data-value' );
            var product_variation = jQuery.parseJSON( $( this ).parents( '.variations_form' ).attr( 'data-product_variations' ) );
            var prime_property = product_variation[ 0 ].attributes;//property with variation

            var prime_index = '';
            var i = 0;

            $.each( prime_property, function ( pr_index, pr_val ) {
                if ( i == 0 ) {
                    prime_index = pr_index;
                }
                i = 1;
            } );

            //attribute_name is for attribute_pa_size
            if ( attribute_name == prime_index ) {
                var available_variations_for_current_attribute = [];
                $.each( product_variation, function ( index, val ) {
                    var attr = val.attributes;

                    $.each( attr, function ( attr_index, attr_val ) {
                        if ( attr_index == attribute_name && attr_val == attribute_value ) {
                            available_variations_for_current_attribute.push( attr );
                        }
                        else {
                            $( '.variations_form[data-product_id|=' + product_id + ']' ).find( '.swatch-' + attr_val ).removeClass( 'rexvs-enable' );
                            $( '.swatch-' + attr_val ).removeClass( 'rexvs-enable' );

                            if ( attr_index !== attribute_name ) {
                                $( '.variations_form[data-product_id|=' + product_id + ']' ).find( '.swatch-' + attr_val ).addClass( 'rexvs-disable' );
                            }
                        }
                    } );
                } );


                $.each( available_variations_for_current_attribute, function ( av_index, av_val ) {
                    $.each( av_val, function ( avs_index, avs_val ) {

                        if ( avs_val !== attribute_value ) {
                            $( '.variations_form[data-product_id|=' + product_id + ']' ).find( '.swatch-' + avs_val ).addClass( 'rexvs-enable' );
                            $( '.variations_form[data-product_id|=' + product_id + ']' ).find( '.swatch-' + avs_val ).removeClass( 'rexvs-disable' );
                        }
                    } );
                } );
            }
        } );

        $( '.reset_variations' ).on( 'click', function () {
            var product_id = $( this ).parents( '.variations_form' ).attr( 'data-product_id' );
            $( this ).parents( '.variations_form[data-product_id|=' + product_id + ']' ).find( '.swatch' ).removeClass( 'rexvs-disable' ).removeClass( 'rexvs-enable' );

        } );
    } );
} )( jQuery );

