<?php
/**
 * CHECKIN & CHECKOUT DROPDOWN
 * ----------------------------------------------------------------------------
 */

function bos_dateSelector( $month_format, $calendar, $checkin, $checkout, $textcolor ) {
                /* create all variables */
                /* Detect language */
                $wp_system_language = get_locale();

                if ( $month_format == 'long' ) {
                                $monthName = array(
                                                 1 => __( 'January', 'bookingcom-official-searchbox' ),
                                                __( 'February', 'bookingcom-official-searchbox' ),
                                                __( 'March', 'bookingcom-official-searchbox' ),
                                                __( 'April', 'bookingcom-official-searchbox' ),
                                                __( 'May', 'bookingcom-official-searchbox' ),
                                                __( 'June', 'bookingcom-official-searchbox' ),
                                                __( 'July', 'bookingcom-official-searchbox' ),
                                                __( 'August', 'bookingcom-official-searchbox' ),
                                                __( 'September', 'bookingcom-official-searchbox' ),
                                                __( 'October', 'bookingcom-official-searchbox' ),
                                                __( 'November', 'bookingcom-official-searchbox' ),
                                                __( 'December', 'bookingcom-official-searchbox' ) 
                                );
                } //$month_format == 'long'
                else {
                                $monthName = array(
                                                 1 => __( 'Jan', 'bookingcom-official-searchbox' ),
                                                __( 'Feb', 'bookingcom-official-searchbox' ),
                                                __( 'Mar', 'bookingcom-official-searchbox' ),
                                                __( 'Apr', 'bookingcom-official-searchbox' ),
                                                trim( __( 'May ', 'bookingcom-official-searchbox' ) ),
                                                __( 'Jun', 'bookingcom-official-searchbox' ),
                                                __( 'Jul', 'bookingcom-official-searchbox' ),
                                                __( 'Aug', 'bookingcom-official-searchbox' ),
                                                __( 'Sept', 'bookingcom-official-searchbox' ),
                                                __( 'Oct', 'bookingcom-official-searchbox' ),
                                                __( 'Nov', 'bookingcom-official-searchbox' ),
                                                __( 'Dec', 'bookingcom-official-searchbox' ) 
                                );
                } // a space is intentionally added to May in order to differenciate other translations/localizations
                $checkin      = $checkin ? $checkin : __( 'Check-in date', 'bookingcom-official-searchbox' );
                $checkout     = $checkout ? $checkout : __( 'Check-out date', 'bookingcom-official-searchbox' );
                $textcolor    = $textcolor ? 'color:' . $textcolor . ';' : 'color: #003580;';
                $currentDate  = time();
                $currentYear  = intval( date( "Y", $currentDate ) );
                /* next year */
                $nextYear     = $currentYear + 1;
                /* add one day to today in seconds*/
                $tomorrow     = $currentDate + ( 1 * 24 * 60 * 60 );
                $tomorrowYear = intval( date( "Y", $tomorrow ) );

                if( $wp_system_language == 'ja' ) {
                   /*  CHECKIN STARTS*/
                    $output       = '<div id="b_searchCheckInDate" class="bos_lang_'. $wp_system_language .'">';
                    $output .= '<h4 id="checkInDate_h4" style="' . $textcolor . '">' . $checkin . '</h4>';
                    $output .= '<div class="b_searchDatesInner">';

                    $output .= $calendar ? '<a id="b_checkinCalPos" class="b_requiresJsInline" href="javascript:showCalendar(\'b_checkinCalPos\', \'b_calendarPopup\', \'b_checkin\', \'b_frm\');" title="' . __( 'Open calendar and pick a date', 'bookingcom-official-searchbox' ) . '"><img class="b_seeThrough" src="' . BOS_IMG_PLUGIN_DIR . '/b_calendar_icon.jpg' . '" alt="" title="' . __( 'Open calendar and pick a date', 'bookingcom-official-searchbox' ) . '"  /></a>' : '';

                    /* make checkin month-year selector */
                    $output .= '<select name="checkin_year_month"  id="b_checkin_month"  onchange="checkDateOrder(\'b_frm\', \'b_checkin_day\', \'b_checkin_month\', \'b_checkout_day\', \'b_checkout_month\');">';
                    $selected = '';
                    for ( $currentMonth = intval( date( "m", $currentDate ) ); $currentMonth < 13; $currentMonth++ ) {
                                    if ( is_admin() ) { // enable checkin and checkout to keep current date when using the ajax function in admin settings
                                                    if ( intval( date( "m", $currentDate ) ) == $currentMonth ) {
                                                                    $selected = 'selected="selected"';
                                                    } //intval( date( "m", $currentDate ) ) == $currentMonth
                                                    else {
                                                                    $selected = '';
                                                    }
                                    } //is_admin()                                
                                    $output .= "<option " . $selected . " value='" . $currentYear . "-" . $currentMonth . "'>" . $currentYear . "&nbsp;" . $monthName[ $currentMonth ]  . "</option>\n";
                    } //$currentMonth = intval( date( "m", $currentDate ) ); $currentMonth < 13; $currentMonth++
                    for ( $currentMonth = 1; $currentMonth < ( intval( date( "m", $currentDate ) ) ); $currentMonth++ ) {
                                    $output .= "<option value='" . $nextYear . "-" . $currentMonth . "'>" . $nextYear . "&nbsp;" . $monthName[ $currentMonth ] . "</option>\n";
                    } //$currentMonth = 1; $currentMonth < ( intval( date( "m", $currentDate ) ) ); $currentMonth++
                    $output .= '</select>';

                    /* make checkin day selector */
                    
                    $output .= '<select name="checkin_monthday" id="b_checkin_day" onchange="checkDateOrder(\'b_frm\', \'b_checkin_day\', \'b_checkin_month\', \'b_checkout_day\', \'b_checkout_month\');">';
                    for ( $currentDay = 1; $currentDay < 32; $currentDay++ ) {
                                    $output .= '<option value="' . $currentDay . '"';
                                    if ( is_admin() ) { // enable checkin and checkout to keep current date when using the ajax function in admin settings
                                                    if ( intval( date( "d", $currentDate ) ) == $currentDay ) {
                                                                    $output .= ' selected="selected"';
                                                    } //intval( date( "d", $currentDate ) ) == $currentDay
                                    } //is_admin()
                                    $output .= ">" . $currentDay . "</option>\n";
                    } //$currentDay = 1; $currentDay < 32; $currentDay++
                    $output .= '</select>';

                    $output .= '</div>';
                    $output .= '</div>';
                    /* CHECKOUT STARTS */
                    /* make checkout day selector - default tomorrow */
                    $output .= '<div id="b_searchCheckOutDate" class="bos_lang_'. $wp_system_language .'">';
                    $output .= '<h4 id="checkOutDate_h4" style="' . $textcolor . '">' . $checkout . '</h4>';
                    $output .= '<div class="b_searchDatesInner">';
                    $output .= $calendar ? '<a id="b_checkoutCalPos" class="b_requiresJsInline" href="javascript:showCalendar(\'b_checkoutCalPos\', \'b_calendarPopup\', \'b_checkout\', \'b_frm\');" title="' . __( 'Open calendar and pick a date', 'bookingcom-official-searchbox' ) . '"><img class="b_seeThrough" src="' . BOS_IMG_PLUGIN_DIR . '/b_calendar_icon.jpg' . '" alt="" title="' . __( 'Open calendar and pick a date', 'bookingcom-official-searchbox' ) . '"  /></a>' : '';
                    
                    /* make checkin month-year selector */
                    $output .= '<select name="checkout_year_month"  id="b_checkout_month">';
                    $selected = '';
                    for ( $currentMonth = intval( date( "m", $currentDate ) ); $currentMonth < 13; $currentMonth++ ) {
                                    if ( is_admin() ) { // enable checkin and checkout to keep current date when using the ajax function in admin settings
                                                    if ( intval( date( "m", $tomorrow ) ) == $currentMonth ) {
                                                                    $selected = 'selected="selected"';
                                                    } //intval( date( "m", $tomorrow ) ) == $currentMonth
                                                    else {
                                                                    $selected = '';
                                                    }
                                    } //is_admin()
                                    $output .= "<option " . $selected . " value='" . $currentYear . "-" . $currentMonth . "'>" . $currentYear . "&nbsp;" . $monthName[ $currentMonth ] . "</option>\n";
                    } //$currentMonth = intval( date( "m", $currentDate ) ); $currentMonth < 13; $currentMonth++
                    for ( $currentMonth = 1; $currentMonth < ( intval( date( "m", $currentDate ) ) ); $currentMonth++ ) {
                                    $output .= "<option value='" . $nextYear . "-" . $currentMonth . "'>" . $nextYear . "&nbsp;" . $monthName[ $currentMonth ] . "</option>\n";
                    } //$currentMonth = 1; $currentMonth < ( intval( date( "m", $currentDate ) ) ); $currentMonth++
                    $output .= '</select>';
                    $output .= '<select name="checkout_monthday" id="b_checkout_day">';
                    for ( $tomorrowDay = 1; $tomorrowDay < 32; $tomorrowDay++ ) {
                                    $output .= '<option value="' . $tomorrowDay . '"';
                                    if ( is_admin() ) { // enable checkin and checkout to keep current date when using the ajax function in admin settings
                                                    if ( intval( date( "d", $tomorrow ) ) == $tomorrowDay ) {
                                                                    $output .= ' selected="selected"';
                                                    } //intval( date( "d", $tomorrow ) ) == $tomorrowDay
                                    } //is_admin()
                                    $output .= ">" . $tomorrowDay . "</option>\n";
                    } //$tomorrowDay = 1; $tomorrowDay < 32; $tomorrowDay++
                    $output .= '</select>';
                    $output .= '</div>';                
                    $output .= '</div>';


                }
                else {
                    /*  CHECKIN STARTS*/
                    $output       = '<div id="b_searchCheckInDate" class="'. $wp_system_language .'">';
                    $output .= '<h4 id="checkInDate_h4" style="' . $textcolor . '">' . $checkin . '</h4>';
                    $output .= '<div class="b_searchDatesInner">';

                    $output .= $calendar ? '<a id="b_checkinCalPos" class="b_requiresJsInline" href="javascript:showCalendar(\'b_checkinCalPos\', \'b_calendarPopup\', \'b_checkin\', \'b_frm\');" title="' . __( 'Open calendar and pick a date', 'bookingcom-official-searchbox' ) . '"><img class="b_seeThrough" src="' . BOS_IMG_PLUGIN_DIR . '/b_calendar_icon.jpg' . '" alt="" title="' . __( 'Open calendar and pick a date', 'bookingcom-official-searchbox' ) . '"  /></a>' : '';
                    /* make checkin day selector */


                    
                    $output .= '<select name="checkin_monthday" id="b_checkin_day" onchange="checkDateOrder(\'b_frm\', \'b_checkin_day\', \'b_checkin_month\', \'b_checkout_day\', \'b_checkout_month\');">';
                    for ( $currentDay = 1; $currentDay < 32; $currentDay++ ) {
                                    $output .= '<option value="' . $currentDay . '"';
                                    if ( is_admin() ) { // enable checkin and checkout to keep current date when using the ajax function in admin settings
                                                    if ( intval( date( "d", $currentDate ) ) == $currentDay ) {
                                                                    $output .= ' selected="selected"';
                                                    } //intval( date( "d", $currentDate ) ) == $currentDay
                                    } //is_admin()
                                    $output .= ">" . $currentDay . "</option>\n";
                    } //$currentDay = 1; $currentDay < 32; $currentDay++
                    $output .= '</select>';
                    /* make checkin month-year selector */
                    $output .= '<select name="checkin_year_month"  id="b_checkin_month"  onchange="checkDateOrder(\'b_frm\', \'b_checkin_day\', \'b_checkin_month\', \'b_checkout_day\', \'b_checkout_month\');">';
                    $selected = '';
                    for ( $currentMonth = intval( date( "m", $currentDate ) ); $currentMonth < 13; $currentMonth++ ) {
                                    if ( is_admin() ) { // enable checkin and checkout to keep current date when using the ajax function in admin settings
                                                    if ( intval( date( "m", $currentDate ) ) == $currentMonth ) {
                                                                    $selected = 'selected="selected"';
                                                    } //intval( date( "m", $currentDate ) ) == $currentMonth
                                                    else {
                                                                    $selected = '';
                                                    }
                                    } //is_admin()                                
                                    $output .= "<option " . $selected . " value='" . $currentYear . "-" . $currentMonth . "'>" . $monthName[ $currentMonth ] . "&nbsp;" . $currentYear . "</option>\n";
                    } //$currentMonth = intval( date( "m", $currentDate ) ); $currentMonth < 13; $currentMonth++
                    for ( $currentMonth = 1; $currentMonth < ( intval( date( "m", $currentDate ) ) ); $currentMonth++ ) {
                                    $output .= "<option value='" . $nextYear . "-" . $currentMonth . "'>" . $monthName[ $currentMonth ] . "&nbsp;" . $nextYear . "</option>\n";
                    } //$currentMonth = 1; $currentMonth < ( intval( date( "m", $currentDate ) ) ); $currentMonth++
                    $output .= '</select>';
                    $output .= '</div>';
                    $output .= '</div>';
                    /* CHECKOUT STARTS */
                    /* make checkout day selector - default tomorrow */
                    $output .= '<div id="b_searchCheckOutDate" class="'. $wp_system_language .'">';
                    $output .= '<h4 id="checkOutDate_h4" style="' . $textcolor . '">' . $checkout . '</h4>';
                    $output .= '<div class="b_searchDatesInner">';
                    $output .= $calendar ? '<a id="b_checkoutCalPos" class="b_requiresJsInline" href="javascript:showCalendar(\'b_checkoutCalPos\', \'b_calendarPopup\', \'b_checkout\', \'b_frm\');" title="' . __( 'Open calendar and pick a date', 'bookingcom-official-searchbox' ) . '"><img class="b_seeThrough" src="' . BOS_IMG_PLUGIN_DIR . '/b_calendar_icon.jpg' . '" alt="" title="' . __( 'Open calendar and pick a date', 'bookingcom-official-searchbox' ) . '"  /></a>' : '';
                    $output .= '<select name="checkout_monthday" id="b_checkout_day">';
                    for ( $tomorrowDay = 1; $tomorrowDay < 32; $tomorrowDay++ ) {
                                    $output .= '<option value="' . $tomorrowDay . '"';
                                    if ( is_admin() ) { // enable checkin and checkout to keep current date when using the ajax function in admin settings
                                                    if ( intval( date( "d", $tomorrow ) ) == $tomorrowDay ) {
                                                                    $output .= ' selected="selected"';
                                                    } //intval( date( "d", $tomorrow ) ) == $tomorrowDay
                                    } //is_admin()
                                    $output .= ">" . $tomorrowDay . "</option>\n";
                    } //$tomorrowDay = 1; $tomorrowDay < 32; $tomorrowDay++
                    $output .= '</select>';
                    /* make checkin month-year selector */
                    $output .= '<select name="checkout_year_month"  id="b_checkout_month">';
                    $selected = '';
                    for ( $currentMonth = intval( date( "m", $currentDate ) ); $currentMonth < 13; $currentMonth++ ) {
                                    if ( is_admin() ) { // enable checkin and checkout to keep current date when using the ajax function in admin settings
                                                    if ( intval( date( "m", $tomorrow ) ) == $currentMonth ) {
                                                                    $selected = 'selected="selected"';
                                                    } //intval( date( "m", $tomorrow ) ) == $currentMonth
                                                    else {
                                                                    $selected = '';
                                                    }
                                    } //is_admin()
                                    $output .= "<option " . $selected . " value='" . $currentYear . "-" . $currentMonth . "'>" . $monthName[ $currentMonth ] . "&nbsp;" . $currentYear . "</option>\n";
                    } //$currentMonth = intval( date( "m", $currentDate ) ); $currentMonth < 13; $currentMonth++
                    for ( $currentMonth = 1; $currentMonth < ( intval( date( "m", $currentDate ) ) ); $currentMonth++ ) {
                                    $output .= "<option value='" . $nextYear . "-" . $currentMonth . "'>" . $monthName[ $currentMonth ] . "&nbsp;" . $nextYear . "</option>\n";
                    } //$currentMonth = 1; $currentMonth < ( intval( date( "m", $currentDate ) ) ); $currentMonth++
                    $output .= '</select>';

                    $output .= '</div>';                
                    $output .= '</div>';
                } // if( $wp_system_language == 'ja' )
                return $output;
}
?>