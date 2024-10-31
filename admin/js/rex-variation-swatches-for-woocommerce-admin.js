(function ( $ ) {
    'use strict';

    /**
     * All of the code for your admin-facing JavaScript source
     * should reside in this file.
     *
     * Note: It has been assumed you will write jQuery code here, so the
     * $ function reference has been prepared for usage within the scope
     * of this function.
     *
     * This enables you to define handlers, for when the DOM is ready:
     *
     * $(function() {
     *
     * });
     *
     * When the window is loaded:
     *
     * $( window ).load(function() {
     *
     * });
     *
     * ...and/or other possibilities.
     *
     * Ideally, it is not considered best practise to attach more than a
     * single DOM-ready or window-load handler for a particular page.
     * Although scripts in the WordPress core, Plugins and Themes may be
     * practising this, we should strive to set a better example in our own work.
     */

    jQuery( document ).ready( function ( $ ) {
        $( '#rexvs_settings_submit' ).on( 'click', function ( event ) {
            event.preventDefault();

            var ajaxurl = rexvs_obj.ajaxurl;
            var ajaxnonce = rexvs_obj.ajax_nonce;

            $( '#rexsv-spinner' ).show();

            $( '#rexvs_settings_status' ).hide();
            var rexvs_default_dropdown_to_button = $( "input:checkbox[name=rexvs_default_dropdown_to_button]:checked" ).val();
            if ( rexvs_default_dropdown_to_button == undefined ) {
                rexvs_default_dropdown_to_button = 'off';
            }
            var rexvs_delete_data = $( "input:checkbox[name=rexvs_delete_data]:checked" ).val();
            if ( rexvs_delete_data == undefined ) {
                rexvs_delete_data = 'off';
            }

            //-----enable/disable default stylesheet-----
            var rexvs_disable_stylesheet = $( "input:checkbox[name=rexvs_disable_stylesheet]:checked" ).val();
            if ( rexvs_disable_stylesheet == undefined ) {
                rexvs_disable_stylesheet = 'off';
            }

            //--tooltip enable/disable---
            var rexvs_tooltip = $( "input:checkbox[name=rexvs_tooltip]:checked" ).val();
            if ( rexvs_tooltip == undefined ) {
                rexvs_tooltip = 'off';
            }

            //--swatches border enable/disable---
            var rexvs_swatches_border = $( "input:checkbox[name=rexvs_swatches_border]:checked" ).val();
            if ( rexvs_swatches_border == undefined ) {
                rexvs_swatches_border = 'off';
            }

            var rexvs_shape_style = $( "input:radio[name=rexvs_shape_style]:checked" ).val();
            var rexvs_shape_height = $( "input[name=rexvs_shape_height]" ).val();
            var rexvs_shape_width = $( "input[name=rexvs_shape_width]" ).val();

            var rexvs_tooltip_fnt_size = $( "input[name=rexvs_tooltip_fnt_size]" ).val();
            var rexvs_tooltip_color = $( "input[name=rexvs_tooltip_color]" ).val();
            var rexvs_tooltip_bg_color = $( "input[name=rexvs_tooltip_bg_color]" ).val();

            var rexvs_swatches_font_size = $( "input[name=rexvs_swatches_font_size]" ).val();
            var rexvs_swatches_bg_color = $( "input[name=rexvs_swatches_bg_color]" ).val();
            var rexvs_swatches_color = $( "input[name=rexvs_swatches_color]" ).val();

            var rexvs_swatches_border_size = $( "input[name=rexvs_swatches_border_size]" ).val();
            var rexvs_swatches_border_style = $( "select[name=rexvs_swatches_border_style]" ).val();
            var rexvs_swatches_border_color = $( "input[name=rexvs_swatches_border_color]" ).val();

            var rexvs_seltd_swatches_bg_color = $( "input[name=rexvs_seltd_swatches_bg_color]" ).val();
            var rexvs_seltd_swatches_color = $( "input[name=rexvs_seltd_swatches_color]" ).val();
            var rexvs_seltd_swatches_border_size = $( "input[name=rexvs_seltd_swatches_border_size]" ).val();
            var rexvs_seltd_swatches_border_color = $( "input[name=rexvs_seltd_swatches_border_color]" ).val();

            var rexvs_hvr_swatches_bg_color = $( "input[name=rexvs_hvr_swatches_bg_color]" ).val();
            var rexvs_hvr_swatches_color = $( "input[name=rexvs_hvr_swatches_color]" ).val();
            var rexvs_hvr_swatches_border_size = $( "input[name=rexvs_hvr_swatches_border_size]" ).val();
            var rexvs_hvr_swatches_border_color = $( "input[name=rexvs_hvr_swatches_border_color]" ).val();

            jQuery.ajax( {
                type: "POST",
                url: ajaxurl,
                data: {
                    action: "rexvs_settings_submit",
                    nonce: ajaxnonce,
                    rexvs_default_dropdown_to_button: rexvs_default_dropdown_to_button,
                    rexvs_delete_data: rexvs_delete_data,
                    rexvs_disable_stylesheet: rexvs_disable_stylesheet,
                    rexvs_tooltip: rexvs_tooltip,

                    rexvs_shape_style: rexvs_shape_style,
                    rexvs_shape_height: rexvs_shape_height,
                    rexvs_shape_width: rexvs_shape_width,

                    rexvs_tooltip_fnt_size: rexvs_tooltip_fnt_size,
                    rexvs_tooltip_color: rexvs_tooltip_color,
                    rexvs_tooltip_bg_color: rexvs_tooltip_bg_color,

                    rexvs_swatches_font_size: rexvs_swatches_font_size,
                    rexvs_swatches_bg_color: rexvs_swatches_bg_color,
                    rexvs_swatches_color: rexvs_swatches_color,

                    rexvs_swatches_border: rexvs_swatches_border,
                    rexvs_swatches_border_size: rexvs_swatches_border_size,
                    rexvs_swatches_border_style: rexvs_swatches_border_style,
                    rexvs_swatches_border_color: rexvs_swatches_border_color,

                    rexvs_seltd_swatches_bg_color: rexvs_seltd_swatches_bg_color,
                    rexvs_seltd_swatches_color: rexvs_seltd_swatches_color,
                    rexvs_seltd_swatches_border_size: rexvs_seltd_swatches_border_size,
                    rexvs_seltd_swatches_border_color: rexvs_seltd_swatches_border_color,

                    rexvs_hvr_swatches_bg_color: rexvs_hvr_swatches_bg_color,
                    rexvs_hvr_swatches_color: rexvs_hvr_swatches_color,
                    rexvs_hvr_swatches_border_size: rexvs_hvr_swatches_border_size,
                    rexvs_hvr_swatches_border_color: rexvs_hvr_swatches_border_color,

                },

                success: function ( response ) {
                    $( '#rexsv-spinner' ).hide();
                    if ( response.status == 'success' ) {
                        $( '#rexvs_settings_status' ).show();
                        $( '#rexvs_settings_status' ).text( response.message );
                        setTimeout( function () {
                            $( "#rexvs_settings_status" ).hide();
                        }, 3000 );
                    }
                },
                error: function ( response ) {
                    $( '#rexvs_settings_status' ).show();
                    $( '#rexvs_settings_status' ).text( 'Error submitting data' );
                    setTimeout( function () {
                        $( "#rexvs_settings_status" ).hide();
                    }, 3000 );
                }
            } );
        } );

        //------tooltip color/font-size/bg enable disable-----
        var rexvs_tooltip_switch_data = $( "input:checkbox[name=rexvs_tooltip]:checked" ).val();
        if ( rexvs_tooltip_switch_data == 'on' ) {
            $( '.enabled-global-tooltip' ).show();
        } else {
            $( '.enabled-global-tooltip' ).hide();
        }

        $( "input:checkbox[name=rexvs_tooltip]" ).on( 'click', function () {
            if ( $( this ).is( ":checked" ) ) {
                $( '.enabled-global-tooltip' ).show();
            } else {
                $( '.enabled-global-tooltip' ).hide();
            }
        } );

        //------swatches border enable/disable-----
        var rexvs_swatches_border_data = $( "input:checkbox[name=rexvs_swatches_border]:checked" ).val();
        if ( rexvs_swatches_border_data == 'on' ) {
            $( '.enabled-global-swatches-border' ).show();
        } else {
            $( '.enabled-global-swatches-border' ).hide();
        }

        $( "input:checkbox[name=rexvs_swatches_border]" ).on( 'click', function () {
            if ( $( this ).is( ":checked" ) ) {
                $( '.enabled-global-swatches-border' ).show();
            } else {
                $( '.enabled-global-swatches-border' ).hide();
            }
        } );


    } );

    $( function () {
        // Add Color Picker to all inputs that have 'color-field' class
        $( '.rexsv-color-picker' ).wpColorPicker();

    } );

})( jQuery );
