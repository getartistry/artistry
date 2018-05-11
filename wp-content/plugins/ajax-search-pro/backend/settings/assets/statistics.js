jQuery(function ($) {

    $("form[name='asp_stat_settings'] .wpdreamsYesNoInner").on("click", function () {
        setTimeout(function () {
            $("form[name='asp_stat_settings']").get(0).submit();
        }, 500);
    });

    $('a[tabid=1]').click();

    $('#wpdreams .deletekeyword').click(function () {
        var del = confirm("Do yo really want to delete this item?");
        var $this = $(this);
        if (del) {
            id = $(this).attr('keyword');
            var data = {
                action: 'ajaxsearchpro_deletekeyword',
                keywordid: id
            };
            jQuery.post(ajaxurl, data, function (response) {
                if (response == 1) {
                    $this.parent().fadeOut();
                }
            });
        }
    });

    if (line1.length > 0) {
        var plot1 = $.jqplot('top20', [line1], {
            title: 'Top 20 Search Phrases',
            series: [
                {renderer: $.jqplot.BarRenderer}
            ],
            axesDefaults: {
                tickRenderer: $.jqplot.CanvasAxisTickRenderer,
                tickOptions: {
                    angle: -30,
                    fontSize: '10pt'
                }
            },
            axes: {
                xaxis: {
                    renderer: $.jqplot.CategoryAxisRenderer
                }
            }
        });
    }
    if (line2.length > 0) {
        var plot2 = $.jqplot('last20', [line2], {
            title: 'Last 20 Search Phrases',
            series: [
                {renderer: $.jqplot.BarRenderer}
            ],
            axesDefaults: {
                tickRenderer: $.jqplot.CanvasAxisTickRenderer,
                tickOptions: {
                    angle: -30,
                    fontSize: '10pt'
                }
            },
            axes: {
                xaxis: {
                    renderer: $.jqplot.CategoryAxisRenderer
                }
            }
        });
    }
});