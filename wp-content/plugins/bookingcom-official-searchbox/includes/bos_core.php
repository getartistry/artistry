<?php
/**
 * CORE SCRIPT
 * ----------------------------------------------------------------------------
 */
register_activation_hook( BOS_PLUGIN_FILE, 'bos_searchbox_install' );
function bos_searchbox_install( ) {
                //this install defaults values
                $bos_searchbox_options = array(
                                 'plugin_ver' => BOS_PLUGIN_VERSION //plugin version 
                );
                update_option( 'bos_searchbox_options', $bos_searchbox_options );
}
// Add settings link on plugin page
add_filter( 'plugin_action_links_' . BOS_PLUGIN_FILE, 'bos_searchbox_settings_link' );
function bos_searchbox_settings_link( $links ) {
                $settings_link = '<a href="options-general.php?page=bos_searchbox.php">' . __( 'Settings', BOS_PLUGIN_FILE ) . '</a>';
                array_unshift( $links, $settings_link );
                return $links;
}
// Add a menu for our option page
add_action( 'admin_menu', 'bos_searchbox_add_page' );
function bos_searchbox_add_page( ) {
                add_options_page( 'Booking.com Search Box settings', // Page title on browser bar 
                                'Booking.com Search Box', // menu item text
                                'manage_options', // only administartors can open this
                                'bos_searchbox', // unique name of settings page
                                'bos_searchbox_option_page' //call to fucntion which creates the form
                                );
}
/* Localization and internazionalization */
add_action( 'plugins_loaded', 'bos_searchbox_init' );
function bos_searchbox_init( ) {
                load_plugin_textdomain( 'bookingcom-official-searchbox', false, dirname( BOS_PLUGIN_FILE ) . '/languages/' );
}
// Meta Boxes
@include BOS_INC_PLUGIN_DIR . '/bos_meta_boxes.php';
/* Ajax for searchbox preview */
add_action( 'wp_ajax_bos_preview', 'bos_ajax_preview' );
function bos_ajax_preview( ) {
                if ( isset( $_REQUEST[ 'nonce' ] ) ) {
                                // Verify that the incoming request is coming with the security nonce
                                if ( wp_verify_nonce( $_REQUEST[ 'nonce' ], 'bos_ajax_nonce' ) ) {
                                                $arrayFields = bos_searchbox_settings_fields_array();
                                                foreach ( $arrayFields as $field ) {
                                                                if ( $field[ 1 ] == 'text' || $field[ 1 ] == 'radio' || $field[ 1 ] == 'select' ) {
                                                                                $options[ $field[ 0 ] ] = isset( $_REQUEST[ $field[ 0 ] ] ) ? stripslashes( sanitize_text_field( $_REQUEST[ $field[ 0 ] ] ) ) : '';
                                                                } //if ( $field[ 1 ] == 'text' )
                                                                elseif ( $field[ 1 ] == 'checkbox' ) {
                                                                                if ( $field[ 0 ] == 'calendar' ) {
                                                                                                $options[ $field[ 0 ] ] = empty( $_REQUEST[ 'calendar' ] ) ? 0 : 1;
                                                                                } //if ( $field[ 0 ] == 'calendar' )
                                                                                if ( $field[ 0 ] == 'flexible_dates' ) {
                                                                                                $options[ $field[ 0 ] ] = empty( $_REQUEST[ 'flexible_dates' ] ) ? 0 : 1;
                                                                                } //if ( $field[ 0 ] == 'flexible_dates' )
                                                                } //$field[ 1 ] == 'checkbox'
                                                } //foreach( $arrayFields as $field)
                                                $preview = true;
                                                echo '<div id="bos_preview_title"><img src="' . BOS_IMG_PLUGIN_DIR . '/preview_title.png" alt="Preview" /></div>';
                                                bos_create_searchbox( $options, $preview );
                                                die( );
                                } //wp_verify_nonce( $_REQUEST[ 'nonce' ], 'bos_ajax_nonce' )
                                else {
                                                die( 'There was an issue in the preview statement' );
                                }
                } //isset( $_REQUEST[ 'nonce' ] )
}
// Create the searchbox
function bos_create_searchbox( $searchbox_options, $preview ) {
                $options                     = $searchbox_options;
                $preview_mode                = $preview ? $preview : false;
                // Set variables for the searchbox: if none the default values will be used
                $destination                 = !empty( $options[ 'destination' ] ) ? $options[ 'destination' ] : '';
                $dest_type                   = !empty( $options[ 'dest_type' ] ) ? $options[ 'dest_type' ] : BOS_DEST_TYPE;
                $dest_id                     = !empty( $options[ 'dest_id' ] ) ? $options[ 'dest_id' ] : '';
                $display_in_custom_post_type = !empty( $options[ 'display_in_custom_post_type' ] ) ? $options[ 'display_in_custom_post_type' ] : '';
                $widget_width                = !empty( $options[ 'widget_width' ] ) ? $options[ 'widget_width' ] : '';
                $calendar                    = !empty( $options[ 'calendar' ] ) ? $options[ 'calendar' ] : BOS_CALENDAR;
                $domain                      = !empty( $options[ 'cname' ] ) ? '//' . $options[ 'cname' ] . '/' : BOS_DEFAULT_DOMAIN;
                $cname                       = !empty( $options[ 'cname' ] ) ? $options[ 'cname' ] : '';
                $month_format                = !empty( $options[ 'month_format' ] ) ? $options[ 'month_format' ] : BOS_MONTH_FORMAT;
                $flexible_dates              = !empty( $options[ 'flexible_dates' ] ) ? $options[ 'flexible_dates' ] : BOS_FLEXIBLE_DATES;
                $logodim                     = !empty( $options[ 'logodim' ] ) ? $options[ 'logodim' ] : BOS_LOGODIM;
                $logopos                     = !empty( $options[ 'logopos' ] ) ? $options[ 'logopos' ] : BOS_LOGOPOS;
                //$save_button_on_widget = !empty(  $options[ 'save_button_on_widget' ] ) ? $options[ 'save_button_on_widget' ] : BOS_SAVE_BUTTON ;    
                //$prot = !empty(  $options[ 'prot' ] ) ? $options[ 'prot' ] : BOS_PROTOCOL ;
                $buttonpos                   = !empty( $options[ 'buttonpos' ] ) ? $options[ 'buttonpos' ] : BOS_BUTTONPOS;
                $textcolor                   = !empty( $options[ 'textcolor' ] ) ? $options[ 'textcolor' ] : BOS_TEXTCOLOR;
                $bgcolor                     = !empty( $options[ 'bgcolor' ] ) ? $options[ 'bgcolor' ] : BOS_BGCOLOR;
                $submit_bgcolor              = !empty( $options[ 'submit_bgcolor' ] ) ? $options[ 'submit_bgcolor' ] : BOS_SUBMIT_BGCOLOR;
                $submit_bordercolor          = !empty( $options[ 'submit_bordercolor' ] ) ? $options[ 'submit_bordercolor' ] : BOS_SUBMIT_BORDERCOLOR;
                $submit_textcolor            = !empty( $options[ 'submit_textcolor' ] ) ? $options[ 'submit_textcolor' ] : BOS_SUBMIT_TEXTCOLOR;
                $maintitle                   = !empty( $options[ 'maintitle' ] ) ? $options[ 'maintitle' ] : __( 'Search hotels and more...', 'bookingcom-official-searchbox' );
                $dest_title                  = !empty( $options[ 'dest_title' ] ) ? $options[ 'dest_title' ] : __( 'Destination', 'bookingcom-official-searchbox' );
                $checkin                     = !empty( $options[ 'checkin' ] ) ? $options[ 'checkin' ] : __( 'Check-in date', 'bookingcom-official-searchbox' );
                $checkout                    = !empty( $options[ 'checkout' ] ) ? $options[ 'checkout' ] : __( 'Check-out date', 'bookingcom-official-searchbox' );
                $submit                      = !empty( $options[ 'submit' ] ) ? $options[ 'submit' ] : __( 'Search', 'bookingcom-official-searchbox' );
                // Set the default searchresults page
                $target_page                 = !empty( $options[ 'target_page' ] ) ? $options[ 'target_page' ] : BOS_TARGET_PAGE;
                // Set the default aid if no aid provided
                $aid                         = empty( $options[ 'aid' ] ) || !is_numeric( $options[ 'aid' ] ) || $options[ 'aid' ] == '' || $options[ 'aid' ] == ' ' ? BOS_DEFAULT_AID : trim( $options[ 'aid' ] );
                include BOS_INC_PLUGIN_DIR . '/bos_searchbox.php';
}
?>