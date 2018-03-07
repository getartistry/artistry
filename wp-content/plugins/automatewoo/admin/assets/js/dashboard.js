(function($) {

    var self;

    AW.Dashboard = {

        $el: $( '.automatewoo-dashboard-widgets' ),

        params: {},


        init: function() {
            self.params = automatewooDashboardLocalizeScript;
            self.initMasonry();
        },



        initMasonry: function() {
            self.$el.masonry({
                itemSelector: '.automatewoo-dashboard-widget',
                columnWidth: '.automatewoo-dashboard-widget-sizer',
                percentPosition: true,
                gutter: 20,
                transitionDuration: '0.2s'
            });
        },


        drawGraph: function( id, data, params ) {

            var $chart = $( '#' + id );
            var sets = [];
            var setColors = [ '#3498db', '#d0a0e4', '#72c9b2' ];

            _.each( data, function( values, name ) {
                var set = {
                    label: '',
                    data: values,
                    color: setColors.shift(),
                    points: {
                        show: true,
                        radius: 3,
                        lineWidth: 2,
                        fillColor: '#ffffff',
                        fill: true
                    },
                    lines: {
                        show: true,
                        lineWidth: 2,
                        fill: true,
                        fillColor: { colors: [ { opacity: 0.02 }, { opacity: 0.16 } ] }
                    },
                    shadowSize: 0,
                    isCurrency: params.is_currency
                };

                if ( _.size( data ) > 1 ) {
                    set.lines.fill = false;
                }

                sets.push( set );
            });


            var options = {
                legend: {
                    show: false
                },
                grid: {
                    color: '#aaa',
                    borderColor: 'transparent',
                    borderWidth: 0,
                    hoverable: true
                },
                xaxis: {
                    color: '#e5e5e5',
                    position: "bottom",
                    tickColor: 'transparent',
                    mode: "time",
                    monthNames: AW.params.locale.month_abbrev,
                    tickLength: 1,
                    font: {
                        color: "#aaa"
                    }
                },
                yaxis: {
                    color: '#fff',
                    font: {
                        color: "#fff"
                    }
                }
            };


            if ( params.interval == 30 ) {
                options.xaxis.minTickSize = [ 4, 'day' ];
            }


            $.plot( $chart, sets, options );


            $chart.bind( 'plothover', function( event, pos, item ) {

                var $wrap = $chart.parents( '.automatewoo-dashboard-chart:first' );
                var $tooltip = $chart.siblings( '.automatewoo-dashboard-chart__tooltip:first' );
                var wrap_offset = $wrap.offset();

                if ( item && item.series.points.show ) {

                    var content = item.datapoint[1];

                    if ( item.series.isCurrency ) {
                        content = AW.price( content );
                    }

                    $tooltip.html( content )
                        .css({
                            top: item.pageY - 9 - wrap_offset.top,
                            left: item.pageX + 12 - wrap_offset.left
                        })
                        .fadeIn( 200 );
                } else {
                    $tooltip.hide();
                }
            });

        }

    };

    self = AW.Dashboard;
    self.init();

})(jQuery);