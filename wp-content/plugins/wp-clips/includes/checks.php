<?php
/**
 * Checks Precoded Clip vitals including compatible theme and required plugins.
 * 
 * @author Krolyn Studios
 * @package WP_Clips/Includes
 * @subpackage Checks
 * @since 2.0.0
 */


if( ! defined( 'ABSPATH' ) ) exit;


function wp_clips_vitals_check( $themes, $plugins, $clipdir ) {

	$notices = array();

	// Check required plugins are active
	if( isset( $plugins ) ) {
		$plugins_wash = array_filter( $plugins );
		if( ! empty( $plugins_wash ) )
			foreach( $plugins as $plugin ) {
				if( ! is_plugin_active( $plugin ) ) {
					$notices[] = 'requires other plugins to be installed and active';
					break;
				}
			}
	}

	// Check theme is compatible
	if( isset( $themes ) ) {
		$themes_wash = array_filter( $themes );
		$current = basename( get_stylesheet_directory() );
		$parent = basename( get_template_directory() );
		if( ! (
			empty( $themes_wash ) ||
			in_array( $current, $themes ) ||
			in_array( $parent, $themes )
		) ) $notices[] = 'is not compatible with the current theme';
	}

	if( count( $notices ) > 0 ) {

		// Unclip and alert if fail
		$clip = basename( $clipdir );
		$clipname = preg_split( '/[._]/', $clip );
		rename( $clipdir, WPCLIPS_UNCL . $clip );

		foreach( $notices as $notice ) {
			add_action( 'admin_print_footer_scripts', function() use( $clipname, $notice ) {
				$custom = ( $clipname[0] == 'clip' ) ? ' (custom)' : '';
				?><div class="notice notice-error">
                	<p><strong><?php echo $clipname[0] . $custom; ?></strong><?php 
                		printf( __( ' %s. Refer to Clip %s.', 'wp-clips' ), $notice, $custom ? 'vitals' : 'documentation' );
					?></p>
				</div><?php
			});
		}
		return false;
	}
	return true;
}