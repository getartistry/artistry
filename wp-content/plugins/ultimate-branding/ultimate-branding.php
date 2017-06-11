<?php
/*
Plugin Name: Ultimate Branding
Plugin URI: https://premium.wpmudev.org/project/ultimate-branding/
Description: A complete white-label and branding solution for multisite. Login images, favicons, remove WordPress links and branding, and much more.
Author: WPMU DEV
Version: 1.8.4
Author URI: http://premium.wpmudev.org/
Text_domain: ub
WDP ID: 9135

Copyright 2009-2017 Incsub (http://incsub.com)

Lead Developer - Sam Najian (Incsub)

Contributors - Ve Bailovity (Incsub), Barry (Incsub), Andrew Billits, Ulrich Sossou, Marko Miljus, Joseph Fusco (Incsub), Marcin Pietrzak (Incsub)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License (Version 2 - GPLv2) as published by
the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */


// Include the configuration library
require_once( 'ultimate-branding-files/includes/config.php' );
// Include the functions library
require_once( 'ultimate-branding-files/includes/functions.php' );

// Set up my location
set_ub_url( __FILE__ );
set_ub_dir( __FILE__ );

require_once( 'ultimate-branding-files/classes/ubadmin.php' );

if ( is_admin() ) {
	// Add in the contextual help
	require_once( 'ultimate-branding-files/classes/class.help.php' );
	// Include the admin class
	$uba = new UltimateBrandingAdmin();
} else {
	// Include the public class
	require_once( 'ultimate-branding-files/classes/ubpublic.php' );
	$ubp = new UltimateBrandingPublic();
}

$data = get_plugin_data( __FILE__ );
$ub_version = $data['Version'];

include_once( 'external/dash-notice/wpmudev-dash-notification.php' );