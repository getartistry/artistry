/*
This contains all the vars for the namespace */
/* Get current date*/
var my_date = new Date();
var b_gg_today = my_date.getDate();
var b_mm_today = my_date.getMonth() + 1;
var b_yy_today = my_date.getFullYear();
var sp = sp || {};
sp.vars = {};
sp.vars.errors = {
    destinationErrorMsg: objectL10n.destinationErrorMsg,
    tooManyDays: objectL10n.tooManyDays,
    dateInThePast: objectL10n.dateInThePast,
    cObeforeCI: objectL10n.cObeforeCI
};
sp.vars.gen = {
    b_todays_date: b_yy_today + '-' + b_mm_today + '-' + b_gg_today
};
booking = {};
booking.env = {};
sp.variables = {
    calendar_nextMonth: objectL10n.nextMonth,
    calendar_prevMonth: objectL10n.calendar_prevMonth,
    calendar_closeCalendar: objectL10n.calendar_closeCalendar,
    calendar_url: '',
    months: [objectL10n.january, objectL10n.february, objectL10n.march,
        objectL10n.april, objectL10n.may, objectL10n.june, objectL10n.july,
        objectL10n.august, objectL10n.september, objectL10n.october, objectL10n.november,
        objectL10n.december
    ],
    days: [objectL10n.mo, objectL10n.tu, objectL10n.we, objectL10n.th, objectL10n
        .fr, objectL10n.sa, objectL10n.su
    ],
    b_is_searchbox: true
};
// TODO: Sort all of the naming out. Once all of the products have been consolidated the name space needs cleaning up to stay consistent.
sp.gen = {
    difference: function(a, b) {
        return Math.abs(a - b);
    },
    id: function(elm) {
        return (elm) ? document.getElementById(elm) : false;
    }
};
// Calender
calendar = new Object();
tr = new Object();
var filaMonth;

function showCalendar(img, cal, dt, frm, m, y, d) {
    var d = document;
    if (d.getElementById) {
        var c = d.getElementById(cal),
            i = d.getElementById(img),
            f = d.getElementById(frm);
        calendar.calfrm = frm;
        calendar.cal = c;
        calendar.caldt = dt;
        calendar.calf = f;
        var my = f[dt + '_month'].value.split("-");
        y = my[0];
        m = my[1];
        d = f[dt + '_day'].value;
        buildCal(y, m, d);
        var l = 0;
        var t = 0;
        aTag = i;
        do {
            aTag = aTag.offsetParent;
            l += aTag.offsetLeft;
            t += aTag.offsetTop;
        } while (aTag.offsetParent && aTag.tagName != 'body');
        var left = i.offsetLeft + l;
        var top = i.offsetTop + t + i.offsetHeight + 2;
        // Adding a class for the check in and check out. 
        jQuery(c).attr('class', "b_popup " + dt);
        if (sp.variables.b_is_ie6) {
            if (sp.variables.b_action === "index") {
                left = i.offsetLeft + 140;
                top = i.offsetTop + 290;
            }
            if (sp.variables.b_action === "hotel") {
                left = i.offsetLeft + 160;
                top = i.offsetTop + 150;
            }
            if (sp.variables.b_action.match(/city|region|landmark|country|place/g)) {
                left = i.offsetLeft + 160;
                top = i.offsetTop + 150;
            }
        }
        if (sp.variables.b_is_searchbox) {
            var bWidth = jQuery("#b_searchboxInc").width(),
                cWidth = jQuery(c).innerWidth(),
                bHeight = jQuery("#b_searchboxInc").height(),
                cHeight = jQuery(c).innerHeight();
            if ((cWidth + left) >= bWidth) {
                left = left - sp.gen.difference((cWidth + left), bWidth);
            }
            var container;
            if (container = document.getElementById('container')) {
                if (container.offsetHeight <= 180) {
                    top = "-35";
                } else {
                    if ((cHeight + top) >= bHeight) {
                        top = top - sp.gen.difference((cHeight + top), bHeight);
                        if ((cWidth + (left + 10)) < bWidth) {
                            left = left + 10;
                        }
                    }
                }
            } else {
                if ((cHeight + top) >= bHeight) {
                    top = top - sp.gen.difference((cHeight + top), bHeight);
                    if ((cWidth + (left + 10)) < bWidth) {
                        left = left + 10;
                    }
                }
            }
        }
        c.style.position = "absolute";
        c.style.left = left + 'px';
        c.style.top = top + 'px';
        c.style.display = "block";
    }
}

function closeCal() {
    calendar.cal.style.display = 'none';
}

function buildCal(y, m, d) {
    var daysInMonth = [31, 0, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
    td = new Date();
    if (!y) {
        y = td.getFullYear();
    }
    if (!m) {
        m = td.getMonth() + 1;
    }
    if (!d) {
        d = td.getDate;
    }
    var frm = calendar.calfrm;
    var dt = calendar.caldt;
    var mDate = new Date(y, m - 1, 1);
    var firstMonthDay = mDate.getDay();
    daysInMonth[1] = (((mDate.getFullYear() % 100 != 0) && (mDate.getFullYear() %
        4 == 0)) || (mDate.getFullYear() % 400 == 0)) ? 29 : 28;
    var today = (y == td.getFullYear() && m == td.getMonth() + 1) ? td.getDate() :
        0;
    var t = '<table class="b_caltable" cellspacing="0"><tr class="b_calHeader">';
    var flm = td.getMonth() + 1;
    var flyr = td.getFullYear();
    for (p = 0; p <= 11; p++) {
        if (flm == m) {
            filaMonth = p;
        }
        flm++;
        if (flm > 12) {
            flm = 1;
            flyr++;
        }
    }
    t += '<td colspan="7">';
    if (filaMonth == 0) {
        t += '<span class="prevMonthDisabled">&nbsp;&lt;&nbsp;</span>';
    } else {
        t += '<a class="changeMonth" href="javascript:prevMonth(' + y + ',' + m +
            ');" title="' + sp.variables.calendar_Month + '">&nbsp;&lt;&nbsp;</a>';
    }
    if (!sp.variables.b_hide_month_dd) {
        t +=
            '&nbsp;<select name="ym" class="selectMonth" onchange="goMonth(this.options[this.selectedIndex].value)">';
        var mn = td.getMonth() + 1;
        var yr = td.getFullYear();
        for (n = 0; n <= 11; n++) {
            t += '<option value="' + mn + '"';
            if (mn == m) {
                t += ' selected="selected"';
            }
            t += '>' + sp.variables.months[mn - 1] + ' ' + yr + '</option>';
            mn++;
            if (mn > 12) {
                mn = 1;
                yr++;
            }
        }
        t += '</select>&nbsp;';
    }
    if (filaMonth == 11) {
        t += '&nbsp;&gt;&nbsp;';
    } else {
        t += '<a class="changeMonth" href="javascript:nextMonth(' + y + ',' + m +
            ');" title="' + sp.variables.calendar_nextMonth + '">&nbsp;&gt;&nbsp;</a>';
    }
    t += '</td></tr>';
    t += '<tr class="b_calDayNames">';
    for (dn = 0; dn < 7; dn++) {
        var cl = '';
        if ((dn % 7 == 5) || (dn % 7 == 6)) {
            cl += ' b_calWeekend';
        }
        t += '<th class="' + cl + '">' + sp.variables.days[dn] + '</th>';
    }
    t += '</tr><tr class="b_calDays">';
    // Make the previous and next months dates appear. 
    if (sp.variables.full_dates) {
        var getPrevMonth = (m - 1) - 1,
            prevMonth = daysInMonth[getPrevMonth],
            newMonth = 1;
    }
    for (i = 1; i <= 42; i++) {
        var x = i - (firstMonthDay + 6) % 7,
            prevM = 0,
            nextM;
        // This out puts the days in the month
        if (x > daysInMonth[m - 1] || x < 1) {
            if (sp.variables.full_dates) {
                prevM = (x < 1) ? 1 : 0;
                nextM = (x > daysInMonth[m - 1]) ? 1 : 0;
            }
            x = (!sp.variables.full_dates) ? '&nbsp;' : (x >= daysInMonth[m - 1]) ?
                newMonth++ : (prevMonth - x);
        }
        var cl = '';
        var href = 0;
        if ((i % 7 == 0) || (i % 7 == 6)) {
            cl += ' b_calWeekend';
        }
        if (x > 0) {
            var xDay = new Date(y, m - 1, x);
            if ((xDay.getFullYear() == y) && (xDay.getMonth() + 1 == m) && (xDay.getDate() ==
                d)) {
                cl += ' b_calSelected';
                href = 1;
            }
            if ((xDay.getFullYear() == td.getFullYear()) && (xDay.getMonth() == td.getMonth()) &&
                (xDay.getDate() == td.getDate())) {
                cl += ' b_calToday';
                href = 1;
            } else {
                if (xDay > td && !prevM || sp.variables.full_dates && nextM && !prevM) {
                    cl += (nextM) ? 'nextMonth b_calFuture' : ' b_calFuture';
                    href = 1;
                } else {
                    if (xDay < td || sp.variables.full_dates && prevM) {
                        cl += ' b_calPast'
                    }
                }
            }
        };
        t += '<td class="' + cl + '">';
        if (href) {
            t += '<a id="' + x + '-' + m + '-' + y +
                '" class="calDateClick" href="javascript:pickDate(' + y + ',' + m + ',' +
                x + ',\'' + dt + '\',\'' + frm + '\');">' + x + '</a>';
        } else {
            t += x;
        }
        t += '</td>';
        if (((i) % 7 == 0) && (i < 36)) {
            t += '</tr><tr class="b_calDays">';
        }
    }
    t +=
        '</tr><tr class="b_calClose"><td colspan="7"><a href="javascript:closeCal();">' +
        sp.variables.calendar_closeCalendar + '</a></td></tr></table>';
    document.getElementById("b_calendarInner").innerHTML = t;
}

function prevMonth(y, m) {
    if (new Date(y, m - 1, 1) < td) {
        return;
    }
    if (m > 1) {
        m--;
    } else {
        m = 12;
        y--;
    };
    buildCal(y, m);
}

function nextMonth(y, m) {
    if (m < 12) {
        m++;
    } else {
        m = 1;
        y++;
    }
    if (y > td.getFullYear() && m >= (td.getMonth() + 1)) {
        return;
    }
    buildCal(y, m);
}

function goMonth(m) {
    var y = td.getFullYear();
    if (m < td.getMonth() + 1) {
        y++;
    }
    buildCal(y, m);
}

function pickDate(y, m, d, dt, frm) {
    // set form values
    var f = calendar.calf,
        dt = calendar.caldt,
        tInput;
    f[dt + '_month'].value = y + "-" + m;
    f[dt + '_day'].value = d;
    if (tInput = document.getElementById(dt + '_input')) {
        tInput.value = d + "/" + m + "/" + y;
    }
    tickCheckBox('b_availcheck');
    if (dt == "b_checkin") {
        checkDateOrder(calendar.calfrm, 'b_checkin_day', 'b_checkin_month',
            'b_checkout_day', 'b_checkout_month');
    }
    closeCal();
}

function checkDateOrder(frm, ci_day, ci_month_year, co_day, co_month_year) {
    var frm = document.getElementById(frm),
        my = frm[ci_month_year].value.split("-"),
        ci = new Date(my[0], my[1] - 1, frm[ci_day].value, 12, 0, 0, 0);
    // create date object from checkout values
    my = frm[co_month_year].value.split("-");
    var co = new Date(my[0], my[1] - 1, frm[co_day].value, 12, 0, 0, 0);
    // if checkin date is at or after checkout date,
    // add a day full of milliseconds, and set the
    // selectbox values for checkout date to new value
    if (ci >= co) {
        co.setTime(ci.getTime() + 1000 * 60 * 60 * 24);
        frm[co_day].value = co.getDate();
        var com = co.getMonth() + 1;
        frm[co_month_year].value = co.getFullYear() + "-" + com;
        var tInput;
        if (tInput = document.getElementById('b_checkout_input')) {
            tInput.value = co.getDate() + "/" + com + "/" + co.getFullYear();
        }
    }
    //updateSelectOptions( ci_day, ci_month_year, co_day, co_month_year );
}

function updateSelectOptions(ci_day, ci_month_year, co_day, co_month_year) {
    var daysInMonth = [31, 0, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31],
        checkInDay = sp.gen.id(ci_day),
        checkInMonth = sp.gen.id(ci_month_year).value.split(/-/),
        checkInOptions = "",
        checkOutDay = sp.gen.id(co_day),
        checkOutMonth = sp.gen.id(co_month_year).value.split(/-/),
        checkOutOptions = "";
    daysInMonth[1] = (new Date(checkInDay[1], 1, 28).getMonth()) ? 28 : 29;
    if (sp.variables.b_is_ie) {
        dim = daysInMonth[checkInMonth[1] - 1];
        checkInOptions = checkInDay.getElementsByType('option');
        var cIOLng = checkInOptions.length;
        for (var i = 0; i <= 31; i++) {
            if (i <= dim) {
                if (dim) {
                    checkInOptions[i].value = i;
                    checkInOptions[i].innerHTML = i;
                } else {}
            } else {
                checkInOptions[i].parentNode.removeChild(checkInOptions[i]);
            }
        }
        dim = daysInMonth[checkOutMonth[1] - 1];
    } else {
        var dim = "";
        // Set check in day number
        checkInDay.innerHTML = "";
        dim = daysInMonth[checkInMonth[1] - 1];
        for (var i = 1; i <= dim; i++) {
            checkInOptions += "<option value='" + i + "'>" + i + "</option>";
        }
        checkInDay.innerHTML = checkInOptions;
        // Set check out day number
        checkOutDay.innerHTML = "";
        dim = daysInMonth[checkOutMonth[1] - 1];
        for (var i = 1; i <= dim; i++) {
            checkOutOptions += "<option value='" + i + "'>" + i + "</option>";
        }
        checkOutDay.innerHTML = checkOutOptions;
    }
}

function tickCheckBox(a) {
        if (document.getElementById(a)) {
            document.getElementById(a).checked = true;
        }
        return true;
    }
    // For the text inputs setting up behaviours. 
    (function(d) {
        var checkIn,
            checkOut,
            validaton = function(e) {
                var keyCodeMap = {
                        48: "0",
                        49: "1",
                        50: "2",
                        51: "3",
                        52: "4",
                        53: "5",
                        54: "6",
                        55: "7",
                        56: "8",
                        57: "9",
                        111: "/",
                        189: "-",
                        46: "delete",
                        37: "left",
                        38: "up",
                        39: "right",
                        40: "down",
                        8: "backspace"
                    },
                    keycode;
                if (window.event) {
                    keycode = window.event.keyCode;
                } else if (e) {
                    keycode = e.which;
                }
                if (keyCodeMap[keycode]) {
                    return true;
                }
                return false;
            },
            updateTextInput = function(e) {
                var elm = e.explicitOriginalTarget.parentNode,
                    splitDates;
                if (!elm.className.match(/changeMonth|selectMonth/)) {
                    if (elm.className === "calDateClick" && elm.href.match(/javascript/)) {
                        splitDates = elm.id.split("-");
                    } else {
                        if (e.currentTarget.value.match(/\//)) {
                            splitDates = e.currentTarget.value.split("/");
                        } else {
                            splitDates = e.currentTarget.value.split("-");
                        }
                    }
                    pickDate(splitDates[2], splitDates[1], splitDates[0], "b_" + e.currentTarget
                        .className.split("_")[1], "b_frm");
                }
            };
        if (checkIn = d.getElementById('b_checkin_input')) {
            checkIn.onblur = updateTextInput;
            checkIn.onkeydown = validaton;
            checkIn.onkeyup = validaton;
        }
        if (checkOut = d.getElementById('b_checkout_input')) {
            checkOut.onblur = updateTextInput;
            checkOut.onkeydown = validaton;
            checkOut.onkeyup = validaton;
        }
        // Set current date to ensure even cached pages a correct datetime
        var currentDate = my_date;
        var currentYear = 1900 + currentDate.getYear();
        var dailyMS = 24 * 60 * 60 * 1000;
        var arrivalDate = new Date(currentDate.getTime());
        var departureDate = new Date(currentDate.getTime() + 1 * dailyMS);
        var arrivalYearMonth = 1900 + arrivalDate.getYear() + "-" + 1 + arrivalDate.getMonth();
        var arrivalDay = arrivalDate.getDate();
        var departureYearMonth = 1900 + departureDate.getYear() + "-" + 1 +
            departureDate.getMonth();
        var departureDay = departureDate.getDate();
        var frm = document.getElementById('b_frm');
        if (frm && frm.length > 0 && frm != null) {
            if ((frm.checkin_monthday.selectedIndex == 0) && (frm.checkout_monthday.selectedIndex ==
                0)) {
                frm.checkin_monthday.options[arrivalDay - 1].selected = true;
                frm.checkout_monthday.options[departureDay - 1].selected = true;
            }
            // create date object from checkin values
            // set date to 12:00 to avoid problems with one
            // date being wintertime and the other summertime
            var my = frm['b_checkin_month'].value.split("-");
            var ci = new Date(my[0], my[1] - 1, frm['b_checkin_day'].value, 12, 0, 0, 0);
            // create date object from checkout values
            my = frm['b_checkout_month'].value.split("-");
            var co = new Date(my[0], my[1] - 1, frm['b_checkout_day'].value, 12, 0, 0,
                0);
            if (ci >= co) {
                co.setTime(ci.getTime() + 1000 * 60 * 60 * 24);
                frm['b_checkout_day'].value = co.getDate();
                var com = co.getMonth() + 1;
                frm['b_checkout_month'].value = co.getFullYear() + "-" + com;
            }
        } // if(  frm &&  frm.length > 0 && frm != null )
    })(document);