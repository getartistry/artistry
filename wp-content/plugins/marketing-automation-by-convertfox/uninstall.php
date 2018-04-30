<?php
/**
 * Uninstall procedures
 * 
 * @package ConvertFox
 * @author Jitta Raghavender Rao <jitta@convertfox.com>
 * @version 1.4
 * @since 1.4
 */

// Exit if not called from WordPress
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) { exit; }

// Remove options
delete_option( 'convertfox_settings' );