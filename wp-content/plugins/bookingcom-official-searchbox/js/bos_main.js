(function(c, a) {
    var b = {};
    b.name = "Strategic Partnerships namespace";
    b.gElm = function(d) {
        return (d) ? c.getElementById(d) : false;
    };
    b.validation = {
        validSearch: function() {
            var idf = jQuery('#b_idf:checked').val();
            //alert(idf);
            if (idf != 'on') { // check if idf checkbox is checked and exclude date validation
                if (!this.checkDestination() || !this.checkDates()) {
                    return false;
                }
            }
        },
        checkDestination: function() {
            var d = b.gElm("b_destination").value || "";
            if (d) {
                return true;
            }
            this.showFormError(b.vars.errors.destinationErrorMsg,
                "searchBox_error_msg");
            return false;
        },
        checkDates: function() {
            var j = b.gElm,
                k = parseInt(j("b_checkin_day").value),
                f = j("b_checkin_month").value,
                p = f.split(/-/),
                g = new Date(p[0], p[1] - 1, k),
                h = parseInt(j("b_checkout_day").value),
                l = j("b_checkout_month").value,
                i = l.split(/-/),
                n = new Date(i[0], i[1] - 1, h),
                o = b.vars.gen.b_todays_date.split(/-/),
                m = j("b_checkin_input") || false,
                d = m && m.offsetWidth ? true : false;
            textCheckOut = j("b_checkout_input") || false;
            textCheckOutShown = m && m.offsetWidth ? true : false;
            if (parseInt(i[1]) === parseInt(o[1]) && k < parseInt(o[2])) {
                this.showFormError(b.vars.errors.dateInThePast,
                    "searchBox_dates_error_msg");
                return false;
            }
            if (n.getTime() <= g.getTime()) {
                this.showFormError(b.vars.errors.cObeforeCI, "searchBox_dates_error_msg");
                return false;
            }
            if ((n - g) / (1000 * 60 * 60 * 24) > 30) {
                this.showFormError(b.vars.errors.tooManyDays,
                    "searchBox_dates_error_msg");
                return false;
            }
            return true;
        },
        showFormError: function(f, d) {
            if (!f || !d) {
                return false;
            }
            var e = c.getElementById(d),
                g = function() {
                    jQuery(e).fadeOut("default");
                };
            e.innerHTML = f;
            e.style.cursor = "pointer";
            jQuery(e).fadeIn("default", function() {
                var h = this;
                if (h.addEventListener) {
                    h.addEventListener("click", g, false);
                }
                if (h.attachEvent) {
                    h.attachEvent("onclick", g);
                }
                setTimeout(g, 5000);
            });
        }
    };
    a.sp = b;
    a.e = b.gElm;
})(document, window);

jQuery(document).on('click', '#b_dest_unlocker', function(event){       
    event.preventDefault();
    jQuery("#b_dest_type, #b_dest_id, #b_open_search").remove(); 
    jQuery("#b_destination").removeAttr("readonly");
    jQuery("#b_destination").val('');
    jQuery("#b_destination").attr("placeholder", objectL10n.placeholder);
    jQuery("#b_dest_unlocker").remove();
    jQuery("#b_destination").css({
        "background": "#FFFFFF",
        "color": "#003580"
    });
    
});

jQuery(document).on('hover', '#b_dest_unlocker', function(event){  
//jQuery("#b_dest_unlocker").hover(function() {
    jQuery("#b_open_search").toggle();
});

jQuery("#bos_info_displayer").click(function(event) {
    event.preventDefault();
    jQuery("#bos_info_box").toggle();
});