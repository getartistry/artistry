<?php
/**
 * Convert Pro Addon Analytics loader file
 *
 * @package Convert Pro Addon
 * @author Brainstorm Force
 */

// Prohibit direct script loading.
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

$loader_file_path = CP_ADDON_DIR . 'addons/analytics/classes/class-cp-ga-loader.php';

if ( file_exists( $loader_file_path ) ) {
	require_once $loader_file_path;
}
