<?php
/**
     * Plugin Name: Booking.com Official Search Box
     * Plugin URI: http://www.booking.com/general.html?tmpl=docs/partners_affiliate_examples
     * Description: This plugin creates a search box for Booking.com Affiliate Partners to implement using their affiliate ID. If you’re not an Affiliate Partner yet, you can still implement the plugin. To get the most out of the plugin and earn commission, you’ll need to <a href="http://www.booking.com/content/affiliates.html" target="_blank">sign up for the Booking.com Affiliate Partner Programme.</a>
     * Version: 2.0
     * Author: Strategic Partnership Department at Booking.com
     * Author URI: http://www.booking.com/general.html?tmpl=docs/partners_affiliate_examples
     * Text Domain: bookingcom-official-searchbox
     * Domain Path: /languages
     * License: GPLv2
     */
     
     
     /*  Copyright 2014-2016  Strategic Partnership Department at Booking.com  ( email : wp-plugins@booking.com )
        This program is free software; you can redistribute it and/or modify
        it under the terms of the GNU General Public License as published by
        the Free Software Foundation; either version 2 of the License, or
        (at your option) any later version.
        This program is distributed in the hope that it will be useful,
        but WITHOUT ANY WARRANTY; without even the implied warranty of
        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
        GNU General Public License for more details.
        You should have received a copy of the GNU General Public License 
        along with this program; if not, write to the Free Software 
        Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
    */
    
/*Define constants and paths*/
define( 'BOS_PLUGIN_NAME' , 'Booking.com Official Search Box' ) ;
define( 'BOS_PLUGIN_VERSION' , '2.0' ) ; 
define( 'BOS_PLUGIN_FILE' , plugin_basename( __FILE__ ) ) ;    
define( 'BOS_PLUGIN_DIR_PATH' , plugin_dir_path( __FILE__ ) ) ;
define( 'BOS_PLUGIN_DIR_URL' , plugin_dir_url( __FILE__ ) ) ;
define( 'BOS_JS_PLUGIN_DIR', BOS_PLUGIN_DIR_URL.'js' ) ;
define( 'BOS_CSS_PLUGIN_DIR', BOS_PLUGIN_DIR_URL.'css' ) ;
define( 'BOS_IMG_PLUGIN_DIR', BOS_PLUGIN_DIR_URL.'images' ) ;
define( 'BOS_INC_PLUGIN_DIR', BOS_PLUGIN_DIR_PATH.'includes' ) ;
define( 'BOS_WP_VERSION' , get_bloginfo( 'version' ) ) ;
define( 'BOS_DEFAULT_AID', 382821 ) ; //default aid in case no affiliate aid provided

// Default fallback values
define( 'BOS_CALENDAR' , 0 ) ; // calendar ( invisible )
define( 'BOS_DEST_TYPE' , 'select' ) ; // destination type of settings and meta box
define( 'BOS_FLEXIBLE_DATES' , 0 ) ; // flexible dates ( invisible )
//define( 'BOS_SAVE_BUTTON' , 0 ) ; // save button on widget ( invisible )
define( 'BOS_MONTH_FORMAT' , 'short' ) ; // calendar month format
define( 'BOS_LOGODIM' , 'blue_150x25' ) ; // logo dimension and color
define( 'BOS_LOGOPOS' , 'left' ) ; // booking.com logo position
//define( 'BOS_PROTOCOL' , 'http://' ) ; // booking.com logo position
define( 'BOS_BUTTONPOS' , 'right' ) ; // button position
define( 'BOS_BGCOLOR' , '#FEBA02' ) ; // searchbox background color
define( 'BOS_TEXTCOLOR' , '#003580' ) ; // searchbox text color 
define( 'BOS_SUBMIT_BGCOLOR' , '#0896FF' ) ; // submit background color
define( 'BOS_SUBMIT_BORDERCOLOR' , '#0896FF' ) ; // submit border color
define( 'BOS_SUBMIT_TEXTCOLOR' , '#FFFFFF' ) ; // submit border color
define( 'BOS_DISPLAY_IN_CUSTOM_TYPES' , '' ) ; // submit border color
define( 'BOS_DEFAULT_DOMAIN' , '//www.booking.com/' ) ; // landing page
define( 'BOS_TARGET_PAGE' , 'searchresults.html' ) ; // landing page

@include BOS_INC_PLUGIN_DIR . '/bos_core.php' ;
@include BOS_INC_PLUGIN_DIR . '/bos_style_and_script.php' ;
@include BOS_INC_PLUGIN_DIR . '/bos_forms.php' ;
@include BOS_INC_PLUGIN_DIR . '/bos_widget.php' ;

?>