// Load the Visualization API and the corechart package.
google.charts.load('current', {'packages':['bar']});

function drawChart( style, filter = '' ) {
    
    var jsonData = jQuery.ajax({
        url: cp_ajax.url,
        method: 'post',
        data: {
            action: 'cp_get_ga_data',
            style_id: style,
            filter: filter
        },
        dataType: "json",
        async: false
    }).responseText;

    var parse_data = JSON.parse( jsonData );

    jQuery.each( parse_data, function( index, value ) {
        // convert date format
        value[0] = new Date( value[0] );
        parse_data[index] = value;
    }); 

    // Create the data table.
    var data = new google.visualization.DataTable();
    data.addColumn('date', 'Date');
    data.addColumn('number', 'Impressions');
    data.addColumn('number', 'Conversions');
    data.addRows( parse_data  ); 

    // Set chart options

    var options = {
        'title':'Analytics',
        'width': 850,
        'height': 350,
        hAxis: {
          title: 'Date'
        },
        vAxis: {
          title: 'Impressions'
        },
      };

    // Instantiate and draw our chart, passing in some options.
    var chart = new google.charts.Bar(document.getElementById('cp_ga_chart_div'));
    chart.draw( data, options );
    setTimeout(function(){
        jQuery('.cp-ga-filter-wrap .cp-ga-filter').removeClass('cp-show');
    }, 200);
}

jQuery( document ).on( 'click', '.cp-ga-filter', function( e ) {
    jQuery(this).addClass( 'cp-show' );
    var filter = jQuery( this ).data( 'filter' ),
        style = jQuery( this ).data( 'style' );
        
    jQuery( '.cp-ga-filter' ).removeClass( 'cp-ga-filter-active' );
    jQuery( this ).addClass( 'cp-ga-filter-active' );
    
    drawChart( style, filter );   
} );

jQuery( document ).on( 'click', '.cp-delete-ga-integration', function( e ) {

    if( confirm( cp_ga_object.confirm_delete_ga ) ) {
        
        jQuery.ajax({
            url: cp_ajax.url,
            data: {
                action : 'cp_delete_ga_integration'
            },
            type: 'POST',
            dataType:'JSON',
            success:function( result ){

                if( result.success == true ) {
                    location.reload();
                }
            },
            error:function(){
                console.log( 'Error' );
            }
        });
    }
} );


jQuery(document).on( "click", ".cp-style-analytics", function(e) {
    e.preventDefault();

    var parentDiv = jQuery("#cp-ga-dashboard-modal");


    parentDiv.addClass("cp-show");
    jQuery(".cp-md-overlay").addClass("cp-show");

 
    parentDiv.find(".cp-save-animate-container").removeClass("cp-zoomOut").addClass(" cp-animated cp-zoomIn");

    var style = jQuery(this).data("style");
    jQuery( '.cp-ga-filter' ).data( 'style', style );
    drawChart(style, '');
});

jQuery(document).on( "click", ".cp-close-wrap", function(e) {
    jQuery(".cp-md-overlay").trigger('click');
});

jQuery(document).on( "click", "#cp-resync-ga", function(e) {

    e.preventDefault();

    var $this = jQuery( this );
    $this.addClass( 'cp-resync-progress' );
    $this.before( '<h5 class="cp-resync-notice">' + cp_ga_object.ga_resync + '</h5>' );

    var action_data = { action: "cp_resync_ga_data" }

    jQuery(this).attr( "disabled", "disabled" );

    jQuery.ajax({
        url: cp_ajax.url,
        data: action_data,
        type: 'POST',
        dataType:'JSON',
        success:function( result ){
            if( result.success == true ) {
                setTimeout(
                    function() {
                        $this.removeClass( 'cp-resync-progress' );
                        jQuery( 'h5.cp-resync-notice' ).text( cp_ga_object.ga_resync_done );
                    },
                    200
                );
                setTimeout(
                    function() {
                        jQuery( 'h5.cp-resync-notice' ).remove();
                    },
                    600
                );
                setTimeout(
                    function() {
                        location.reload();
                    },
                    1000
                );
            }
        },
        error:function(){
            console.log( 'Error' );
        }
    });
});