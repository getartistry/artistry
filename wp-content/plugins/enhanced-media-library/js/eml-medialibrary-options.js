( function( $ ) {

    var orderValue;



    $( document ).ready( function() {

        orderValue = $('#wpuxss_eml_lib_options_media_order').val();
        $('#wpuxss_eml_lib_options_media_orderby').change();
        $('#wpuxss_eml_lib_options_grid_show_caption').change();
    });



    $( document ).on( 'change', '#wpuxss_eml_lib_options_media_orderby', function( event ) {

        var isMenuOrder = 'menuOrder' === $( event.target ).val(),
            isTitleOrder = 'title' === $( event.target ).val(),
            value;

        orderValue = isMenuOrder ? $('#wpuxss_eml_lib_options_media_order').val() : orderValue;
        value = isMenuOrder ? 'ASC' : orderValue;

        $('#wpuxss_eml_lib_options_media_order').prop( 'disabled', isMenuOrder ).val( value );
        $('#wpuxss_eml_lib_options_natural_sort').prop( 'hidden', ! isTitleOrder );
    });



    $( document ).on( 'change', '#wpuxss_eml_lib_options_grid_show_caption', function( event ) {

        var isChecked = $(this).prop( 'checked' );

        $('#wpuxss_eml_lib_options_grid_caption_type').prop( 'hidden', ! isChecked );
    });

})( jQuery );
