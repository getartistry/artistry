<?php
/**
 * SETTINGS SECTION
 * ----------------------------------------------------------------------------
 */
// Fields input arrays 
function bos_searchbox_settings_fields_array( ) {
                $fields                                   = array( );
                // 'field name', 'input type',  'field label', 'field bonus expl.', 'input maxlenght', 'input size', 'required', 'which section belongs to?','placeholder'
                $fields[ 'aid' ]                          = array(
                                 'aid',
                                'text',
                                __( 'Your affiliate ID', 'bookingcom-official-searchbox' ),
                                __( 'Your affiliate ID is a unique number that allows Booking.com to track commission. If you are not an affiliate yet, <a href="http://www.booking.com/content/affiliates.html" target="_blank">check our affiliate programme</a> and get an affiliate ID. It\'s easy and fast. Start earning money, <a href="https://secure.booking.com/partnerreg.html" target="_blank">sign up now!</a>', 'bookingcom-official-searchbox' ),
                                7,
                                10,
                                0,
                                'main',
                                __( 'e.g.', 'bookingcom-official-searchbox' ) . ' ' . BOS_DEFAULT_AID 
                );
                $fields[ 'widget_width' ]                 = array(
                                 'widget_width',
                                'text',
                                __( 'Width', 'bookingcom-official-searchbox' ),
                                __( 'Need a specific width (e.g. 150px)? You can customise the width of your search box easily - just fill in your pixel requirements. If you leave this field empty, you\'ll get default settings.', 'bookingcom-official-searchbox' ),
                                4,
                                4,
                                0,
                                'main',
                                '' 
                );
                $fields[ 'calendar' ]                     = array(
                                 'calendar',
                                'checkbox',
                                __( 'Need a calendar?', 'bookingcom-official-searchbox' ),
                                '',
                                '',
                                '',
                                0,
                                'main',
                                '' 
                );
                $fields[ 'cname' ]                        = array(
                                 'cname',
                                'text',
                                __( 'Cname', 'bookingcom-official-searchbox' ),
                                __( 'Set your cname if you have one. Remember to point it to www.booking.com and to inform our support team.', 'bookingcom-official-searchbox' ),
                                '',
                                '',
                                0,
                                'main',
                                __( 'e.g. hotels.mydomain.com', 'bookingcom-official-searchbox' ) 
                );
                $fields[ 'month_format' ]                 = array(
                                 'month_format',
                                'radio',
                                __( 'Month format', 'bookingcom-official-searchbox' ),
                                '',
                                '',
                                '',
                                0,
                                'main',
                                '' 
                );
                $fields[ 'flexible_dates' ]               = array(
                                 'flexible_dates',
                                'checkbox',
                                __( 'Add a &quot;flexible-date&quot; check box', 'bookingcom-official-searchbox' ),
                                '',
                                '',
                                '',
                                0,
                                'main',
                                '' 
                );
                $fields[ 'logodim' ]                      = array(
                                 'logodim',
                                'radio',
                                __( 'Select which logo and dimension you prefer', 'bookingcom-official-searchbox' ),
                                '',
                                '',
                                '',
                                0,
                                'main',
                                '' 
                );
                $fields[ 'buttonpos' ]                    = array(
                                 'buttonpos',
                                'select',
                                __( 'Button position', 'bookingcom-official-searchbox' ),
                                '',
                                '',
                                '',
                                0,
                                'main',
                                '' 
                );
                $fields[ 'logopos' ]                      = array(
                                 'logopos',
                                'select',
                                __( 'Logo position', 'bookingcom-official-searchbox' ),
                                '',
                                '',
                                '',
                                0,
                                'main',
                                '' 
                );
                $fields[ 'destination' ]                  = array(
                                 'destination',
                                'text',
                                __( 'Destination', 'bookingcom-official-searchbox' ),
                                __( 'You can pre-fill this field with a specific destination ( e.g. Amsterdam )', 'bookingcom-official-searchbox' ),
                                '',
                                18,
                                0,
                                'destination',
                                __( 'e.g. Amsterdam', 'bookingcom-official-searchbox' ) 
                );
                $fields[ 'dest_type' ]                    = array(
                                 'dest_type',
                                'select',
                                __( 'Destination type', 'bookingcom-official-searchbox' ),
                                '',
                                '',
                                '',
                                0,
                                'destination',
                                '' 
                );
                $fields[ 'dest_id' ]                      = array(
                                 'dest_id',
                                'text',
                                __( 'Destination ID ( e.g. -2140479 for Amsterdam )', 'bookingcom-official-searchbox' ),
                                '<a href="#" id="bos_info_displayer" title="Info box"><img  style="border: none;" src="' . BOS_IMG_PLUGIN_DIR . '/bos_info_icon.png" alt="info"></a>',
                                '',
                                25,
                                0,
                                'destination',
                                __( 'e.g. -2140479 for Amsterdam', 'bookingcom-official-searchbox' ) 
                );
                $fields[ 'display_in_custom_post_types' ] = array(
                                 'display_in_custom_post_types',
                                'text',
                                __( 'Enable meta boxes for these Custom Post Type ( use the slug )', 'bookingcom-official-searchbox' ),
                                __( 'If you have multiple posts, use a "," (comma) to separate them. i.e.: cpt1, cpt2, cpt3', 'bookingcom-official-searchbox' ),
                                '',
                                '',
                                0,
                                'destination',
                                '' 
                );
                $fields[ 'bgcolor' ]                      = array(
                                 'bgcolor',
                                'text',
                                __( 'Background colour', 'bookingcom-official-searchbox' ),
                                '',
                                7,
                                10,
                                0,
                                'color',
                                '' 
                );
                $fields[ 'textcolor' ]                    = array(
                                 'textcolor',
                                'text',
                                __( 'Text colour', 'bookingcom-official-searchbox' ),
                                '',
                                7,
                                10,
                                0,
                                'color',
                                '' 
                );
                $fields[ 'submit_bgcolor' ]               = array(
                                 'submit_bgcolor',
                                'text',
                                __( 'Button background colour', 'bookingcom-official-searchbox' ),
                                '',
                                7,
                                10,
                                0,
                                'color',
                                '' 
                );
                $fields[ 'submit_bordercolor' ]           = array(
                                 'submit_bordercolor',
                                'text',
                                __( 'Button border colour', 'bookingcom-official-searchbox' ),
                                '',
                                7,
                                10,
                                0,
                                'color',
                                '' 
                );
                $fields[ 'submit_textcolor' ]             = array(
                                 'submit_textcolor',
                                'text',
                                __( 'Button text colour', 'bookingcom-official-searchbox' ),
                                '',
                                7,
                                10,
                                0,
                                'color',
                                '' 
                );
                $fields[ 'maintitle' ]                    = array(
                                 'maintitle',
                                'text',
                                __( 'Default title ( e.g. Search hotels and more... )', 'bookingcom-official-searchbox' ),
                                '',
                                '',
                                '',
                                0,
                                'wording',
                                __( 'Search hotels and more...', 'bookingcom-official-searchbox' ) 
                );
                $fields[ 'dest_title' ]                   = array(
                                 'dest_title',
                                'text',
                                __( 'Destination ( e.g. Destination )', 'bookingcom-official-searchbox' ),
                                '',
                                '',
                                '',
                                0,
                                'wording',
                                __( 'Destination', 'bookingcom-official-searchbox' ) 
                );
                $fields[ 'checkin' ]                      = array(
                                 'checkin',
                                'text',
                                __( 'Check-in ( e.g. Check-in date )', 'bookingcom-official-searchbox' ),
                                '',
                                '',
                                '',
                                0,
                                'wording',
                                __( 'Check-in date', 'bookingcom-official-searchbox' ) 
                );
                $fields[ 'checkout' ]                     = array(
                                 'checkout',
                                'text',
                                __( 'Check-out ( e.g. Check-out date )', 'bookingcom-official-searchbox' ),
                                '',
                                '',
                                '',
                                0,
                                'wording',
                                __( 'Check-out date', 'bookingcom-official-searchbox' ) 
                );
                $fields[ 'submit' ]                       = array(
                                 'submit',
                                'text',
                                __( 'Submit button ( e.g. Search )', 'bookingcom-official-searchbox' ),
                                '',
                                '',
                                '',
                                0,
                                'wording',
                                __( 'Search', 'bookingcom-official-searchbox' ) 
                );
                return $fields;
}
function bos_searchbox_retrieve_all_user_options( ) {
                // Retrieve all user options from DB
                $user_options = get_option( 'bos_searchbox_user_options' );
                return $user_options;
}
// Draw the option page
function bos_searchbox_option_page( ) {
                // Include of checkin and checkout select
?>
    <div class="wrap">
        
        <?php
                screen_icon();
?>
        <h2><img src="<?php
                echo BOS_IMG_PLUGIN_DIR . '/booking_logotype_blue_150x25.png';
?>" /></h2>
        
        <div class="updated"><p><?php
                _e( 'Customise your Booking.com search box below, or use the default search box by navigating to <strong>Appearance &gt; Widgets</strong> now.', 'bookingcom-official-searchbox' );
?></p></div>
        
        <div id="bos_wrap"  style="background: transparent url( <?php
                echo BOS_IMG_PLUGIN_DIR . '/sand.jpg';
?> ) repeat-y 60% 0;">
            <div id="bos_left">
                <div class="bos_banner_plugin_promotion">
                    <a href="https://wordpress.org/plugins/bookingcom-banner-creator/" target="_blank"><img class="bos_banner_plugin_promotion_image" src="<?php
                    echo BOS_IMG_PLUGIN_DIR . '/bos_banner_creator_plugin_icon.png';?>"></a>
                    <p><?php _e( 'Have you already downloaded the new <a href="https://wordpress.org/plugins/bookingcom-banner-creator/" target="_blank">Banner Creator</a> plugin? Lots of responsive and customisable ready-to-use banners for your WP site!', 'bookingcom-official-searchbox' ) ?>                    
                    </p>
                </div>
                <form action="options.php" method="post" id="bos_form">
                    <?php
                settings_fields( 'bos_searchbox_settings' );
?>
                    <?php
                do_settings_sections( 'bos_searchbox' );
?>
                <p class="submit">
                    <!-- fallback in case no javascript -->
                    <noscript><style>#reset_default, #preview_button, #bos_right { display: none; } #bos_wrap { background: none !important; }</style></noscript>
                    
                    <input type="button" id="preview_button" class="button-primary" value="<?php
                _e( 'Preview', 'bookingcom-official-searchbox' );
?>" />
                    <input type="submit" class="button-primary" value="<?php
                _e( 'Save Changes', 'bookingcom-official-searchbox' );
?>" />
                    <input type="submit" id="reset_default" class="button-secondary" value="<?php
                _e( 'Reset to default', 'bookingcom-official-searchbox' );
?>" />                    
                </p>
                </form>
                <div class="bos_banner_plugin_promotion">
                    <a href="https://wordpress.org/plugins/bookingcom-banner-creator/" target="_blank"><img class="bos_banner_plugin_promotion_image" src="<?php
                    echo BOS_IMG_PLUGIN_DIR . '/bos_banner_creator_plugin_icon.png';?>"></a>
                    <p><?php _e( 'Have you already downloaded the new <a href="https://wordpress.org/plugins/bookingcom-banner-creator/" target="_blank">Banner Creator</a> plugin? Lots of responsive and customisable ready-to-use banners for your WP site!', 'bookingcom-official-searchbox' ) ?>                    
                    </p>
                </div>
            </div>      
            <div id="bos_right">
                
                <div id="bos_preview">
                    
                    <div id="bos_preview_title"><img src="<?php
                echo BOS_IMG_PLUGIN_DIR . '/preview_title.png';
?>" alt="Preview" /></div>
                    <?php
                $options = bos_searchbox_retrieve_all_user_options();
                $preview = true;
                bos_create_searchbox( $options, $preview );
?>
                </div>
            </div>
            
            <div class="clear"></div>
         </div>
    </div>
    <?php
}
// Register and define the settings
add_action( 'admin_init', 'bos_searchbox_admin_init' );
function bos_searchbox_admin_init( ) {
                register_setting( 'bos_searchbox_settings', 'bos_searchbox_user_options', 'bos_searchbox_validate_options' );
                add_settings_section( //Main settings 
                                'bos_searchbox_main', //id
                                __( 'Main settings', 'bookingcom-official-searchbox' ), //title
                                'bos_searchbox_section_main', //callback
                                'bos_searchbox' //page
                                );
                add_settings_section( //Destination
                                'bos_searchbox_destination', //id
                                __( 'Preset destination', 'bookingcom-official-searchbox' ), //title
                                'bos_searchbox_section_destination', //callback
                                'bos_searchbox' //page
                                );
                add_settings_section( //Color settings
                                'bos_searchbox_color', __( 'Colour scheme', 'bookingcom-official-searchbox' ), 'bos_searchbox_section_color', 'bos_searchbox' );
                add_settings_section( //Wording settings
                                'bos_searchbox_wording', __( 'Search box text', 'bookingcom-official-searchbox' ), 'bos_searchbox_section_wording', 'bos_searchbox' );
                $arrayFields = bos_searchbox_settings_fields_array();
                foreach ( $arrayFields as $field ) {
                                add_settings_field( 'bos_searchbox_' . $field[ 0 ], //id
                                                __( $field[ 2 ], 'bookingcom-official-searchbox' ), //title
                                                'bos_searchbox_settings_field', //callback
                                                'bos_searchbox', //page
                                                'bos_searchbox_' . $field[ 7 ], //section
                                                $args = array(
                                                 $field[ 0 ],
                                                $field[ 1 ],
                                                $field[ 3 ],
                                                $field[ 4 ],
                                                $field[ 5 ],
                                                $field[ 8 ] 
                                ) //args
                                                );
                } //$arrayFields as $field
}
// Draw section header
function bos_searchbox_section_main( ) {
                echo '<div id="bos_main_settings_wrapper">';
                echo '<p><em>' . __( 'Use these settings to customise your search box.', 'bookingcom-official-searchbox' ) . '</em></p>';
                echo '<span id="bos_ajax_nonce" class="hidden" style="visibility: hidden;">' . wp_create_nonce( 'bos_ajax_nonce' ) . '</span>';
                echo '</div>';
}
function bos_searchbox_section_destination( ) {
                echo '<div id="bos_dest_settings_wrapper" class="bos_hide">';
                echo '<p><em>' . __( 'Use the following fields to select a specific destination. <em>Destination types</em> and <em>IDs</em> make guest searches more accurate.', 'bookingcom-official-searchbox' ) . '</em><span></span></p>';
                echo '</div>';
}
// Draw color section header
function bos_searchbox_section_color( ) {
                echo '<div id="bos_color_settings_wrapper" class="bos_hide">';
                echo '<p><em>' . __( 'Enter your colour scheme settings here.', 'bookingcom-official-searchbox' ) . '</em><span></span></p>';
                echo '</div>';
}
// Draw wording section header
function bos_searchbox_section_wording( ) {
                echo '<div id="bos_wording_settings_wrapper" class="bos_hide">';
                echo '<p><em>' . __( 'Customise the search box text here.', 'bookingcom-official-searchbox' ) . '</em><span></span></p>';
                echo '</div>';
}
// Display and fill general fields
function bos_searchbox_settings_field( $args ) {
                // get options value from the database        
                $options      = bos_searchbox_retrieve_all_user_options();
                $fields_array = $args[ 0 ];
                $fields_value = '';
                if ( !empty( $options[ $fields_array ] ) ) {
                                $fields_value = $options[ $fields_array ]; // if user eneterd values fields_value
                } //!empty( $options[ $fields_array ] )
                $output = '';
                // echo the fields
                if ( $args[ 1 ] == 'text' ) {
                                $output .= '<input name="bos_searchbox_user_options[' . $fields_array . ']" id="' . $args[ 0 ] . '" type="' . $args[ 1 ] . '" ';
                                if ( !empty( $args[ 3 ] ) ) {
                                                $output .= ' maxlength="' . $args[ 3 ] . '" ';
                                } //!empty( $args[ 3 ] )
                                if ( !empty( $args[ 4 ] ) ) {
                                                $output .= ' size="' . $args[ 4 ] . '" ';
                                } //!empty( $args[ 4 ] )
                                if ( !empty( $args[ 5 ] ) ) {
                                                $output .= ' placeholder="' . $args[ 5 ] . '" ';
                                } //!empty( $args[ 5 ] )
                                // If default plugin values empty show default values  ( but for aid as we do not want the default aid is shown on the input field )
                                if ( $args[ 0 ] == 'aid' && ( $fields_value == BOS_DEFAULT_AID || empty( $fields_value ) || $fields_value == '' || $fields_value == ' ' || !is_numeric( $fields_value ) ) ) {
                                                $fields_value = '';
                                } //$args[ 0 ] == 'aid' && ( $fields_value == BOS_DEFAULT_AID || empty( $fields_value ) || $fields_value == '' || $fields_value == ' ' || !is_numeric( $fields_value ) )
                                // Color scheme default values in case no custom values
                                if ( $args[ 0 ] == 'bgcolor' && ( empty( $fields_value ) || $fields_value == '' || $fields_value == ' ' ) ) {
                                                $fields_value = BOS_BGCOLOR;
                                } //$args[ 0 ] == 'bgcolor' && ( empty( $fields_value ) || $fields_value == '' || $fields_value == ' ' )
                                if ( $args[ 0 ] == 'textcolor' && ( empty( $fields_value ) || $fields_value == '' || $fields_value == ' ' ) ) {
                                                $fields_value = BOS_TEXTCOLOR;
                                } //$args[ 0 ] == 'textcolor' && ( empty( $fields_value ) || $fields_value == '' || $fields_value == ' ' )
                                if ( $args[ 0 ] == 'submit_bgcolor' && ( empty( $fields_value ) || $fields_value == '' || $fields_value == ' ' ) ) {
                                                $fields_value = BOS_SUBMIT_BGCOLOR;
                                } //$args[ 0 ] == 'submit_bgcolor' && ( empty( $fields_value ) || $fields_value == '' || $fields_value == ' ' )
                                if ( $args[ 0 ] == 'submit_bordercolor' && ( empty( $fields_value ) || $fields_value == '' || $fields_value == ' ' ) ) {
                                                $fields_value = BOS_SUBMIT_BORDERCOLOR;
                                } //$args[ 0 ] == 'submit_bordercolor' && ( empty( $fields_value ) || $fields_value == '' || $fields_value == ' ' )
                                if ( $args[ 0 ] == 'submit_textcolor' && ( empty( $fields_value ) || $fields_value == '' || $fields_value == ' ' ) ) {
                                                $fields_value = BOS_SUBMIT_TEXTCOLOR;
                                } //$args[ 0 ] == 'submit_textcolor' && ( empty( $fields_value ) || $fields_value == '' || $fields_value == ' ' )
                                $output .= 'value="' . $fields_value . '" />&nbsp;' . __( $args[ 2 ], 'bookingcom-official-searchbox' );
                                if ( $args[ 0 ] == 'dest_id' ) {
                                                $output .= '<div id="bos_info_box" style="display: none;padding: 1em; background-color:#FFFFE0;border:1px solid  #E6DB55; margin:10px 0 10px;">';
                                                $output .= __( 'For more info on your destination ID, login to the <a href="https://admin.booking.com/partner/" target="_blank">Partner Center</a>. Check <em>&quot;URL constructor&quot;</em> section to find your destination ID. These IDs, also known as UFIs, are usually a negative number ( e.g. <strong>-2140479 is for Amsterdam</strong> , but can be positive ones in the US ) while regions, district and landmarks are always positive ( e.g. <strong>1408 is for Ibiza</strong> ).', 'bookingcom-official-searchbox' );
                                                $output .= '</div>';
                                } //$args[ 0 ] == 'dest_id'
                } // $args[ 1 ] == 'text'
                elseif ( $args[ 1 ] == 'checkbox' ) {
                                if ( $args[ 0 ] == 'calendar' ) {
                                                if ( empty( $fields_value ) ) {
                                                                $fields_value = BOS_CALENDAR;
                                                } // default value
                                } //$args[ 0 ] == 'calendar'
                                else if ( $args[ 0 ] == 'flexible_dates' ) {
                                                if ( empty( $fields_value ) ) {
                                                                $fields_value = BOS_FLEXIBLE_DATES;
                                                } // default values
                                } //$args[ 0 ] == 'flexible_dates'
                                /*else if ( $args[ 0 ] == 'save_button_on_widget' )  {
                                
                                if ( empty( $fields_value ) ) { $fields_value = BOS_SAVE_BUTTON ; } // default values
                                
                                }  */
                                $output .= '<input name="bos_searchbox_user_options[' . $fields_array . ']" id="' . $args[ 0 ] . '" type="' . $args[ 1 ] . '"  ' . checked( 1, $fields_value, false ) . ' />';
                } //$args[ 1 ] == 'checkbox'
                                elseif ( $args[ 1 ] == 'radio' ) {
                                if ( $args[ 0 ] == 'month_format' ) {
                                                if ( empty( $fields_value ) ) {
                                                                $fields_value = BOS_MONTH_FORMAT;
                                                } // default values
                                                //if( empty( $fields_value ) ) { $fields_value = 'short' ; }// set defaults value
                                                $output .= '<input name="bos_searchbox_user_options[' . $fields_array . ']" class="' . $args[ 0 ] . '" type="' . $args[ 1 ] . '"  value="long" ' . checked( 'long', $fields_value, false ) . ' />&nbsp;' . __( 'long', 'bookingcom-official-searchbox' );
                                                $output .= '&nbsp;<input name="bos_searchbox_user_options[' . $fields_array . ']" class="' . $args[ 0 ] . '" type="' . $args[ 1 ] . '"  value="short" ' . checked( 'short', $fields_value, false ) . ' />&nbsp;' . __( 'short', 'bookingcom-official-searchbox' );
                                } // $args[ 0 ] == 'month_format'
                                if ( $args[ 0 ] == 'logodim' ) {
                                                //if( empty( $fields_value ) ) { $fields_value = 'blue_150x25' ; }// set defaults value
                                                $bgcolor = $options[ 'bgcolor' ] ? $options[ 'bgcolor' ] : BOS_BGCOLOR; // default values
                                                if ( empty( $fields_value ) ) {
                                                                $fields_value = BOS_LOGODIM;
                                                } // default values
                                                $output .= '<span id="bos_img_blue_logo" class="bos_logo_dim_box" style="background: ' . $bgcolor . ';"><img  src="' . BOS_IMG_PLUGIN_DIR . '/booking_logotype_blue_150x25.png" alt="Booking.com logo" /></span>';
                                                $output .= '<input name="bos_searchbox_user_options[' . $fields_array . ']" class="' . $args[ 0 ] . '" type="' . $args[ 1 ] . '"  value="blue_150x25"  ' . checked( 'blue_150x25', $fields_value, false ) . ' />&nbsp;( 150x25 )&nbsp;';
                                                $output .= '<input name="bos_searchbox_user_options[' . $fields_array . ']" class="' . $args[ 0 ] . '" type="' . $args[ 1 ] . '"  value="blue_200x33"  ' . checked( 'blue_200x33', $fields_value, false ) . ' />&nbsp;( 200x33 )&nbsp;';
                                                $output .= '<input name="bos_searchbox_user_options[' . $fields_array . ']" class="' . $args[ 0 ] . '" type="' . $args[ 1 ] . '"  value="blue_300x50" ' . checked( 'blue_300x50', $fields_value, false ) . ' />&nbsp;( 300x50 )&nbsp;';
                                                $output .= '<br /><br />';
                                                $output .= '<span id="bos_img_white_logo" class="bos_logo_dim_box" style="background: ' . $bgcolor . ';"><img src="' . BOS_IMG_PLUGIN_DIR . '/booking_logotype_white_150x25.png" alt="Booking.com logo" /></span>';
                                                $output .= '<input name="bos_searchbox_user_options[' . $fields_array . ']" class="' . $args[ 0 ] . '" type="' . $args[ 1 ] . '"  value="white_150x25" ' . checked( 'white_150x25', $fields_value, false ) . ' />&nbsp;( 150x25 )&nbsp;';
                                                $output .= '<input name="bos_searchbox_user_options[' . $fields_array . ']" class="' . $args[ 0 ] . '" type="' . $args[ 1 ] . '"  value="white_200x33" ' . checked( 'white_200x33', $fields_value, false ) . ' />&nbsp;( 200x33 )&nbsp;';
                                                $output .= '<input name="bos_searchbox_user_options[' . $fields_array . ']" class="' . $args[ 0 ] . '" type="' . $args[ 1 ] . '"  value="white_300x50" ' . checked( 'white_300x50', $fields_value, false ) . ' />&nbsp;( 300x50 )&nbsp;';
                                } // $args[ 0 ] == 'logodim'            
                } // $args[ 1 ] == 'radio'      
                                elseif ( $args[ 1 ] == 'select' ) {
                                if ( $args[ 0 ] == 'logopos' ) {
                                                $output .= '<select name="bos_searchbox_user_options[' . $fields_array . ']" id="' . $args[ 0 ] . '" >';
                                                $output .= '<option value="left" ' . selected( 'left', $fields_value, false ) . ' >' . __( 'Left', 'bookingcom-official-searchbox' ) . '</option>';
                                                $output .= '<option value="center" ' . selected( 'center', $fields_value, false ) . ' >' . __( 'Centre', 'bookingcom-official-searchbox' ) . '</option>';
                                                $output .= '<option value="right" ' . selected( 'right', $fields_value, false ) . ' >' . __( 'Right', 'bookingcom-official-searchbox' ) . '</option>';
                                                $output .= '</select>';
                                } // $args[ 0 ] == 'logopos'                               
                                if ( $args[ 0 ] == 'buttonpos' ) {
                                                if ( empty( $fields_value ) ) {
                                                                $fields_value = BOS_BUTTONPOS;
                                                } //empty( $fields_value )
                                                $output .= '<select name="bos_searchbox_user_options[' . $fields_array . ']" id="' . $args[ 0 ] . '" >';
                                                $output .= '<option value="left" ' . selected( 'left', $fields_value, false ) . ' >' . __( 'Left', 'bookingcom-official-searchbox' ) . '</option>';
                                                $output .= '<option value="center" ' . selected( 'center', $fields_value, false ) . ' >' . __( 'Centre', 'bookingcom-official-searchbox' ) . '</option>';
                                                $output .= '<option value="right" ' . selected( 'right', $fields_value, false ) . ' >' . __( 'Right', 'bookingcom-official-searchbox' ) . '</option>';
                                                $output .= '</select>&nbsp;' . __( $args[ 2 ], 'bookingcom-official-searchbox' );
                                } // $args[ 0 ] == 'buttonpos'
                                if ( $args[ 0 ] == 'dest_type' ) {
                                                $output .= '<select name="bos_searchbox_user_options[' . $fields_array . ']" id="' . $args[ 0 ] . '" >';
                                                $output .= '<option value="select" ' . selected( 'select', $fields_value, false ) . ' >' . __( 'select...', 'bookingcom-official-searchbox' ) . '</option>';
                                                $output .= '<option value="city" ' . selected( 'city', $fields_value, false ) . ' >' . __( 'city', 'bookingcom-official-searchbox' ) . '</option>';
                                                $output .= '<option value="landmark" ' . selected( 'landmark', $fields_value, false ) . ' >' . __( 'landmark', 'bookingcom-official-searchbox' ) . '</option>';
                                                //$output .= '<option value="district" ' . selected( 'district', $fields_value, false ) . ' >' . __( 'district' , BOS_TEXT_DOMAIN) . '</option>' ;
                                                $output .= '<option value="region" ' . selected( 'region', $fields_value, false ) . ' >' . __( 'region', 'bookingcom-official-searchbox' ) . '</option>';
                                                $output .= '<option value="airport" ' . selected( 'airport', $fields_value, false ) . ' >' . __( 'airport', 'bookingcom-official-searchbox' ) . '</option>';
                                                $output .= '</select>';
                                } //$args[ 0 ] == 'dest_type'
                } // $args[ 1 ] == 'select'       
                echo $output;
}
// Validate user inputs 
function bos_searchbox_validate_options( $input ) {
                $valid       = array( );
                $message     = array( );
                $error       = false;
                $arrayFields = bos_searchbox_settings_fields_array();
                foreach ( $arrayFields as $field ) {
                                if ( $field[ 1 ] == 'text' ) {
                                                if ( $field[ 0 ] == 'widget_width' ) {
                                                                $valid[ $field[ 0 ] ] = $input[ $field[ 0 ] ];
                                                                if ( !empty( $input[ $field[ 0 ] ] ) && $input[ $field[ 0 ] ] != '' && !is_numeric( $input[ $field[ 0 ] ] ) ) {
                                                                                $error      = true;
                                                                                $message[ ] = '"' . $field[ 2 ] . '": ' . __( 'needs to be an integer', 'bookingcom-official-searchbox' ) . '<br>';
                                                                } //!empty( $input[ $field[ 0 ] ] ) && $input[ $field[ 0 ] ] != '' && !is_numeric( $input[ $field[ 0 ] ] )
                                                } //$field[ 0 ] == 'widget_width'
                                                if ( $field[ 0 ] == 'aid' ) {
                                                                $valid[ $field[ 0 ] ] = $input[ $field[ 0 ] ];
                                                                if ( !empty( $input[ $field[ 0 ] ] ) && $input[ $field[ 0 ] ] != '' && !is_numeric( $input[ $field[ 0 ] ] ) ) {
                                                                                $error      = true;
                                                                                $message[ ] = '"' . $field[ 2 ] . '": ' . __( 'needs to be an integer', 'bookingcom-official-searchbox' ) . '<br>';
                                                                } //!empty( $input[ $field[ 0 ] ] ) && $input[ $field[ 0 ] ] != '' && !is_numeric( $input[ $field[ 0 ] ] )
                                                                // Check if user is placing correct affiliate ID and not partner ID
                                                                else if ( !empty( $input[ $field[ 0 ] ] ) && is_numeric( $input[ $field[ 0 ] ] ) ) {
                                                                                $input[ $field[ 0 ] ] = strval( $input[ $field[ 0 ] ] );
                                                                                if ( $input[ $field[ 0 ] ][ 0 ] == '4' ) { // check first number of the converted value into a string 
                                                                                                $error      = true;
                                                                                                $message[ ] = '"' . $field[ 2 ] . '": ' . __( 'affiliate ID is different from partner ID: should start with a 1, 3, 8 or 9. Please change it.', 'bookingcom-official-searchbox' ) . '<br>';
                                                                                } //$input[ $field[ 0 ] ][ 0 ] == '4'
                                                                } //!empty( $input[ $field[ 0 ] ] ) && is_numeric( $input[ $field[ 0 ] ] )
                                                } //$field[ 0 ] == 'aid'
                                                if ( $field[ 0 ] == 'display_in_custom_post_types' ) {
                                                                // accept only string with letters ( lowercase and uppercase ), numbers,dash, underscore and commas
                                                                if ( !empty( $input[ $field[ 0 ] ] ) ) {
                                                                                if ( preg_match( '/^[a-zA-Z0-9-_,]+$/', $input[ $field[ 0 ] ] ) ) {
                                                                                                $valid[ $field[ 0 ] ] = $input[ $field[ 0 ] ];
                                                                                } //preg_match( '/^[a-zA-Z0-9-_,]+$/', $input[ $field[ 0 ] ] )
                                                                                else {
                                                                                                $error      = true;
                                                                                                $message[ ] = '"' . $field[ 2 ] . '": ' . __( 'Use only alphanumeric strings and commas for multiple slugs', 'bookingcom-official-searchbox' ) . '<br>';
                                                                                }
                                                                } // if( !empty( $input[ $field[ 0 ] ] )  )
                                                } //$field[ 0 ] == 'display_in_custom_post_types'
                                                if ( $field[ 0 ] == 'cname' ) {
                                                                if ( !empty( $input[ $field[ 0 ] ] ) ) {
                                                                            if ( preg_match( '/^(([a-zA-Z0-9]|[a-zA-Z0-9][a-zA-Z0-9\\-_]*[a-zA-Z0-9])\\.)*([A-Za-z0-9]|[A-Za-z0-9][A-Za-z0-9\\-]*[A-Za-z0-9])$/', $input[ $field[ 0 ] ] ) ) {
                                                                                            $valid[ $field[ 0 ] ] = $input[ $field[ 0 ] ];
                                                                            } //preg_match( '/^(([a-zA-Z0-9]|[a-zA-Z0-9][a-zA-Z0-9\\-_]*[a-zA-Z0-9])\\.)*([A-Za-z0-9]|[A-Za-z0-9][A-Za-z0-9\\-]*[A-Za-z0-9])$/')
                                                                            else {
                                                                                            $error      = true;
                                                                                            $message[ ] = '"' . $field[ 2 ] . '": ' . __( 'Cname format is incorrect', 'bookingcom-official-searchbox' ) . '<br>';
                                                                            }
                                                                    }// if ( !empty( $input[ $field[ 0 ] ] ) )
                                                } //$field[ 0 ] == 'cname'
                                                else {
                                                                $valid[ $field[ 0 ] ] = sanitize_text_field( $input[ $field[ 0 ] ] ); //sanitize and escape malicius input
                                                                if ( $valid[ trim( $field[ 0 ] ) ] != trim( $input[ $field[ 0 ] ] ) ) {
                                                                                $error      = true;
                                                                                $message[ ] = '"' . $field[ 2 ] . '": ' . __( 'Missing or incorrect information', 'bookingcom-official-searchbox' ) . '<br>';
                                                                } //$valid[ trim( $field[ 0 ] ) ] != trim( $input[ $field[ 0 ] ] )
                                                }
                                } //if ( $field[ 1 ] == 'text' )
                                elseif ( $field[ 1 ] == 'radio' ) {
                                                if ( $field[ 0 ] == 'month_format' ) {
                                                                switch ( $input[ $field[ 0 ] ] ) {
                                                                                case 'short':
                                                                                                $valid[ $field[ 0 ] ] = $input[ $field[ 0 ] ];
                                                                                                break;
                                                                                case 'long':
                                                                                default:
                                                                                                $valid[ $field[ 0 ] ] = 'long'; //default : long
                                                                                                break;
                                                                } //$input[ $field[ 0 ] ]
                                                } //$field[ 0 ] == 'month_format'
                                                if ( $field[ 0 ] == 'logodim' ) {
                                                                switch ( $input[ $field[ 0 ] ] ) {
                                                                                case 'blue_200x33':
                                                                                case 'blue_300x50':
                                                                                case 'white_150x25':
                                                                                case 'white_200x33':
                                                                                case 'white_300x50':
                                                                                                $valid[ $field[ 0 ] ] = $input[ $field[ 0 ] ];
                                                                                                break;
                                                                                case 'blue_150x25':
                                                                                default:
                                                                                                $valid[ $field[ 0 ] ] = 'blue_150x25'; //default : blue_150x25
                                                                                                break;
                                                                } //$input[ $field[ 0 ] ]
                                                } //$field[ 0 ] == 'logodim'           
                                } //elseif ( $field[ 1 ] == 'radio'  )
                                                elseif ( $field[ 1 ] == 'checkbox' ) {
                                                if ( $field[ 0 ] == 'calendar' ) {
                                                                $valid[ $field[ 0 ] ] = empty( $input[ $field[ 0 ] ] ) ? 0 : 1;
                                                } //if ( $field[ 0 ] == 'calendar' )
                                                if ( $field[ 0 ] == 'flexible_dates' ) {
                                                                $valid[ $field[ 0 ] ] = empty( $input[ $field[ 0 ] ] ) ? 0 : 1;
                                                } //if ( $field[ 0 ] == 'flexible_dates' )
                                                /*if ( $field[ 0 ] == 'save_button_on_widget' ) {
                                                
                                                $valid[ $field[ 0 ] ] = empty( $input[ $field[ 0 ] ] ) ? 0 : 1 ;                     
                                                
                                                } //if ( $field[ 0 ] == 'save_button_on_widget' )*/
                                } //$field[ 1 ] == 'checkbox'
                                else {
                                                /*if ( $field[ 0 ] == 'prot' ) {
                                                
                                                switch( $input[ $field[ 0 ] ] ) {   
                                                
                                                case 'https://' :
                                                $valid[ $field[ 0 ] ] = $input[ $field[ 0 ] ] ; 
                                                break ;
                                                case 'http://' :
                                                default:
                                                $valid[ $field[ 0 ] ] = 'http://' ; //default : http://
                                                break ;        
                                                }
                                                
                                                
                                                }*/
                                                if ( $field[ 0 ] == 'buttonpos' ) {
                                                                switch ( $input[ $field[ 0 ] ] ) {
                                                                                case 'center':
                                                                                case 'left':
                                                                                                $valid[ $field[ 0 ] ] = $input[ $field[ 0 ] ];
                                                                                                break;
                                                                                case 'right':
                                                                                default:
                                                                                                $valid[ $field[ 0 ] ] = 'right'; //default : right
                                                                                                break;
                                                                } //$input[ $field[ 0 ] ]
                                                } //$field[ 0 ] == 'buttonpos'
                                                elseif ( $field[ 0 ] == 'logopos' ) {
                                                                switch ( $input[ $field[ 0 ] ] ) {
                                                                                case 'center':
                                                                                case 'right':
                                                                                                $valid[ $field[ 0 ] ] = $input[ $field[ 0 ] ];
                                                                                                break;
                                                                                case 'left':
                                                                                default:
                                                                                                $valid[ $field[ 0 ] ] = 'left'; //default : left
                                                                                                break;
                                                                } //$input[ $field[ 0 ] ]
                                                } //$field[ 0 ] == 'logopos'
                                                else {
                                                                switch ( $input[ $field[ 0 ] ] ) {
                                                                                case 'city':
                                                                                case 'region':
                                                                                case 'district':
                                                                                case 'landmark':
                                                                                                $valid[ $field[ 0 ] ] = $input[ $field[ 0 ] ];
                                                                                                break;
                                                                                case 'select':
                                                                                default:
                                                                                                $valid[ $field[ 0 ] ] = 'select'; //default : select
                                                                                                break;
                                                                } //$input[ $field[ 0 ] ]
                                                }
                                } //logopos entries      
                } //foreach( $arrayFields as $field)
                if ( $error ) {
                                add_settings_error( 'bos_searchbox_user_options', //setting
                                                'bos_searchbox_texterror', //code added to tag #id            
                                                implode( '', $message ), 'error' );
                } //$error
                return $valid;
}
?>