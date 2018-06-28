( function( $ ) {

    var l10n = wpuxss_eml_media_list_l10n;



    $( document ).ready( function() {

        var $filters = $('select.attachment-filters, select#filter-by-date'),
            $mainFilter = $('select[name="attachment-filter"]'),
            $taxFilters = $('select.eml-taxonomy-filters'),
            $resetFilters,
            $_GET = $.parseJSON( l10n.$_GET );


        // Add "All Uncategorized" option
        $mainFilter.append('<option value="uncategorized">'+l10n.uncategorized+'</option>');


        // Add "Reset All Filters" button
        if ( $filters.length > 1 ) {
            $('#post-query-submit').after('<input type="submit" name="filter_action" id="eml-reset-filters-query-submit" class="button" value="'+l10n.reset_all_filters+'">');
            $resetFilters = $('#eml-reset-filters-query-submit');
        }

        if ( 'uncategorized' == $_GET['attachment-filter'] ) {
            $mainFilter.val('uncategorized');
        }


        $resetFilters.prop( 'disabled', ! $filters.filter( function() { return $(this).prop( 'selectedIndex' ) } ).get().length );



        $( document ).on( 'change', 'select[name="attachment-filter"]', {
            checkFilter : $mainFilter,
            resetFilter : $taxFilters
        }, resetFilters );

        $( document ).on( 'change', 'select.eml-taxonomy-filters', {
            checkFilter : $mainFilter,
            resetFilter : $mainFilter
        }, resetFilters );

        $( document ).on( 'click', '#eml-reset-filters-query-submit', function() {
            $filters.prop( 'selectedIndex', 0 );
        });

    });

    function resetFilters( event ) {

        if ( 'uncategorized' == event.data.checkFilter.val() )
            event.data.resetFilter.prop( 'selectedIndex', 0 );
    }

})( jQuery );
