<?php
/**
 * The editor frame
 *
 * @author 		WaspThemes
 * @category 	Core
 * @version     1.0
 */

// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

// don't load page if not demo mode.
// demo mode just for developers.
// default: not defined.
if ( defined('YP_DEMO_MODE') == false ) {
	die( '-1' );
}

// Get frame.
yp_frame_output();

?>