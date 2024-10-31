(function ( $ ) {
    "use strict";
    var wWidth = $( window ).width();

    jQuery( document ).ready( function ( $ ) {
        $( "#rexvs-tabs" ).tabs();
        //$('.circle-loading-section').fadeOut();
        // $( "#rexvs-tabs" ).css('display', 'flex');
    } );

    /*-----preloader----*/
    $(window).on('load', function() {
        $('.circle-loading-section').fadeOut('slow');
    });
}( jQuery ));