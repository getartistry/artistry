<?php
/**
 * Registers admin Control with Clip and plugin management.
 * 
 * @author Krolyn Studios
 * @package WP_Clips/Includes
 * @subpackage Admin
 * @since 2.0.2
 */


if( ! defined( 'ABSPATH' ) ) exit;


add_action( 'init', 'wp_clips_setups' );
add_action( 'admin_menu', 'wp_clips_menu' );
add_action( 'admin_menu', 'wp_clips_shortcut' );


/**
 * Setups
 *
 * @since 2.0.1
 */

function wp_clips_setups() {

	// Required name changes ( old => new )
	$renames = array(
		'precoded_1' => 'precoded',
		'clip_0' => 'clip_core',
		'clip' => 'clip_custom', // versions < 2.0
		'clip_1' => 'clip_custom',
		'clip_genesis' => 'clip_custom'
	);

	// Add vitals file (if required) and rename
	foreach( $renames as $o => $n )
		foreach( array( WPCLIPS_ROOT, WPCLIPS_UNCL ) as $dir )
			if( file_exists( $dir . $o ) ) {
				$vfile = $dir . $o . '/vitals.php';
				if( $n == 'clip_custom' && ! file_exists( $vfile ) )
					copy( WPCLIPS_ROOT . 'templates/custom/vitals.php', $vfile );
				rename( $dir . $o, $dir . $n );
			}

	// Remove template directory (versions < 2.0)
	if( file_exists( WPCLIPS_ROOT . 'template' ) )
		wp_clips_delete( WPCLIPS_ROOT . 'template' );

	// Setup directories (if doesn't exist)
	if( ! file_exists( WPCLIPS_UNCL ) )
		mkdir( untrailingslashit( WPCLIPS_UNCL ), 0755, true);

	// Setup Clips from template (if doesn't exist)
	foreach( array( 'core', 'custom' ) as $clip )
		if( ! file_exists( WPCLIPS_CLIP . $clip ) &&
			! file_exists( WPCLIPS_UNCL . 'clip_' . $clip )
		) {
			mkdir( WPCLIPS_CLIP . $clip, 0755, true);
			$tempfiles = WPCLIPS_ROOT . 'templates/' . $clip . '/*';
			foreach( glob( $tempfiles ) as $file )
				copy( $file, WPCLIPS_CLIP . $clip . '/' . basename( $file ) );
		}
}


/**
 * Actions for upload and update submits
 *
 * @since 2.0.2
 */

if( isset( $_POST[ 'Upload' ] ) )
	wp_clips_upload( WPCLIPS_ROOT );

if( isset( $_POST[ 'Update' ] ) )
	wp_clips_update();


/**
 * Add menus to WordPress admin
 *
 * @since 2.0.2
 */

function wp_clips_menu() {
	add_options_page( __( 'WP Clips Control', 'wp-clips' ), __( 'Clips', 'wp-clips' ), 'manage_options', 'wp-clips-control', 'wp_clips_options' );
}

// Add shortcuts to editor
function wp_clips_shortcut() {

	global $submenu;

	foreach( array( 'custom', 'core' ) as $clip ) {

		if( file_exists( WPCLIPS_CLIP . $clip ) &&
			basename( WPCLIPS_ROOT ) !== 'mu-plugins' &&
			current_user_can( 'manage_options' ) &&
			! defined( 'DISALLOW_FILE_EDIT' ) &&
			! is_multisite()
		) {
			$clipname = sprintf( __( 'Editor - %s Clip', 'wp-clips' ), ucfirst( $clip ) );
			$shortcut = sprintf( '%1$s?file=wp-clips/clip_%2$s/%2$s-functions.php', admin_url( 'plugin-editor.php' ), $clip );
			$submenu[ 'plugins.php' ][] = array( $clipname, 'manage_options', $shortcut );
		}
	}
}

// Render Control page
function wp_clips_options() {

	if( ! current_user_can( 'manage_options' ) )
		wp_die( __( 'You do not have sufficient permissions to access this page.', 'wp-clips' ) );

	// Check with current versions for plugin updates
	$versions = wp_remote_retrieve_body( wp_remote_get( 'http://wpclips.net/versions.php' ) );
	$versions = maybe_unserialize( $versions );
	$vers_new = $versions[ 'wp-clips' ];

	// Get Clips and sort
	$clipdirs = wp_clips_array();

	?><div class="wrap">
		<h1><?php _e( 'WP Clips Control', 'wp-clips' ); ?></h1><hr>
		<h3><?php _e( 'Install / Update', 'wp-clips' ); ?></h3>
		<p><?php

			if( ! is_multisite() ) {
				printf( __( 'Install and update Precoded Clips, or update the WP Clips plugin (currently v%s)', 'wp-clips' ), WPCLIPS_VERSION ); 
				if( version_compare( WPCLIPS_VERSION, $vers_new, '<' ) == true )
					printf( ' &#10141; <a href="http://wpclips.net/updating-wp-clips/">%s</a>', __( 'Update', 'wp-clips' ) );
			}
			else
				printf( '%s &#10141; <a href="http://wpclips.net/updating-wp-clips/">%s</a>',
					__( 'This plugin version is not compatible with multisite ', 'wp-clips' ),
					__( 'Upgrade to WP Clips Multisite', 'wp-clips' )
				);
		?></p>
		<form enctype="multipart/form-data" method="post">
			<label><?php _e( 'Select a compatible zip file:', 'wp-clips' ); ?></label>
			<input type="file" name="zip_file" />
			<p><input type="submit" class="button" name="Upload" value="
				<?php printf( __( 'Install / %s', 'wp-clips' ), is_multisite() ? 'Upgrade' : 'Update' ); ?>" /></p>
		</form><br />
        <?php if( is_multisite() ) return; ?>
		<h3><?php _e( 'Management', 'wp-clips' ); ?></h3>
		<p><?php _e( 'Clips can be activated or unclipped. Precoded Clips can also be deleted after being unclipped.', 'wp-clips' ); ?><br /></p>
		<form method="post">
			<ul>
			<p><span class="dashicons dashicons-yes"></span>
			<span class="dashicons dashicons-minus"></span>
			<span class="dashicons dashicons-no-alt"></span>
			</p><?php

			// List Clips with buttons
			foreach( $clipdirs as $clipdir ) {

				// Get Clip data
				$clip = basename( $clipdir );
				$clipdata = explode( '.', $clip, 2 );
				$clipname = $clipdata[0];
				if( isset( $clipdata[1] ) ) 
					$vers = $clipdata[1];

				$active = ! strpos( $clipdir, '/unclipped/' );

				$html = '<li>';

				foreach( array( 'active', 'unclipped', 'delete' ) as $btn ) {

					$html .= sprintf( '<input type="radio" name="%s" value="%s"', $clipname, $btn );
					switch( $btn ) {
						case 'active':
							$html .= $active ? 'checked' : '';
							break;
						case 'unclipped':
							$html .= $active ? '' : 'checked';
							break;
						case 'delete':
							$html .= ( ! isset( $vers ) || $active ) ? ' disabled' : '';
							$alert = __( "'Do you really want to delete this Clip?'", 'wp-clips' );
							$html .= sprintf( ' onclick="return confirm(%s)"', $alert );
					}
					$html .= sprintf( '> </input><span class="screen-reader-text">' . __( '%s', 'wp-clips' ) . '</span>', $btn );
				}

				$html .= isset( $vers ) ? $clipname . sprintf( __( ' (v%s)', 'wp-clips' ), $vers ) : str_replace( '_', ' (', $clipname ) . ')';
				unset( $vers );

				$html .= '</li>';
				echo $html;
			}?>
			</ul><br />
			<input type="submit" class="button-primary" name="Update" value="<?php _e( 'Update', 'wp-clips' ); ?>" />
		</form>
	</div>
<?php
}


/**
 * Common functions
 *
 * @since 2.0.0
 */

// Create clean array of Clips
function wp_clips_array() {

	$clipdirs = array();
	foreach( new RecursiveIteratorIterator(
		new RecursiveDirectoryIterator( WPCLIPS_ROOT ), RecursiveIteratorIterator::CHILD_FIRST )
	as $path => $fileinfo ) {
		if( is_dir( $path ) && substr( basename( $path ), 0, 4 ) == 'clip' )
			$clipdirs[] = $path;
	}
	sort( $clipdirs );
	return $clipdirs;
}

// Delete directory and its contents
function wp_clips_delete( $dir ) {

	foreach( new RecursiveIteratorIterator(
		new RecursiveDirectoryIterator( $dir, FilesystemIterator::SKIP_DOTS ),
		RecursiveIteratorIterator::CHILD_FIRST )
	as $del ) {
		if( $del->isDir() )
			rmdir( $del->getPathname() );
		else
			unlink( $del->getPathname() );
	}
	rmdir( $dir );
}


/**
 * Upload or update Precoded Clips or plugin
 *
 * @since 2.0.0
 */

function wp_clips_upload( $dir ) {

	if( ! $_FILES[ 'zip_file' ][ 'name' ] ) return;

	$filename = $_FILES[ 'zip_file' ][ 'name' ];
	$source = $_FILES[ 'zip_file' ][ 'tmp_name' ];
	$type = $_FILES[ 'zip_file' ][ 'type' ];
	$name = explode( '.', $filename );
	$target = $dir . $filename;
	$accepted_types = array( 'application/zip', 'application/x-zip-compressed', 'multipart/x-zip', 'application/x-compressed' );
	foreach( $accepted_types as $mime_type )
		if( $mime_type == $type ) {
			$okay = true;
			break;
		}

	// Check if file meets name and type protocols
	$zip_check = substr( $filename, -4 ) == '.zip';
	$plugin_check = substr( $filename, 0, 9 ) == 'wp-clips.';
	$clip_check = substr( $filename, 0, 5 ) == 'clip-';
	$class = 'notice-error'; // Default

	if( ! $zip_check || ! ( $plugin_check || $clip_check ) )
		$notice = __( 'The file is not a Clip or plugin zip file. Please try again.', 'wp-clips' );

	// Check for mu-plugin install
	elseif( $plugin_check && basename( $dir ) === 'mu-plugins' )
		$notice = __( 'Must use (mu-) plugins must be updated manually. Please refer to Clip documentation.', 'wp-clips' );

	// Check for multisite
	elseif( $plugin_check && strpos( $filename, 'multisite' ) && ! is_multisite() )
		$notice = __( 'WP Clips Multisite requires WordPress multisite to be enabled.', 'wp-clips' );

	// Upload and extract file if checks
	elseif( move_uploaded_file( $source, $target ) ) {

		$zip = new ZipArchive();
		$x = $zip->open( $target );
		if( $x === true ) {

			$msg = 'was uploaded and installed.';
			$class = 'notice-success';

			if( $plugin_check ) {

				// Cleanup
				$safe_files = array( 'clip', 'prec' );
				foreach( glob( $dir . '*' ) as $file )
					if( ! in_array( substr( basename( $file ), 0, 4 ), $safe_files ) )
						if( is_dir( $file ) )
							wp_clips_delete( $file );
						else 
							unlink( $file );	

				$msg = 'was updated. This page will refresh in 5 seconds.';
				$path = dirname( $dir );
			}
			elseif( $clip_check ) {

				// Cleanup
				foreach( wp_clips_array() as $clipdir )
					if( strpos( $clipdir, $name[0] ) ) {
						wp_clips_delete( $clipdir . '/' );
						$msg = 'was updated.';
					}

				$path = $dir . 'precoded/';
			}

			$notice = '<b>' . $name[0] . '</b> ' . sprintf( __( '%s', 'wp-clips' ), $msg );

			$zip->extractTo( $path );
			$zip->close();
			if( file_exists( $target ) ) unlink( $target );
		}
	}

	// Error if unsuccessful
	else
		$notice = __( 'There was a problem with the upload. Please try again.', 'wp-clips' );

	// Display notice
	add_action( 'admin_print_footer_scripts', function() use( $class, $notice ) {
		echo '<div class="notice ' . $class . '"><p>' . $notice . '</p></div>';
	});

	// Refresh page if plugin update
	if( $plugin_check && $class === 'notice-success' ) header( 'Refresh:4' );
}


/**
 * Clip actions on update
 *
 * @since 2.0.0
 */

function wp_clips_update() {

	foreach( wp_clips_array() as $clipdir ) {

		$clip = basename( $clipdir );
		$clipname = explode( '.', $clip );

		if( ! isset( $_POST[ $clipname[0] ] ) ) continue;

		switch( $_POST[ $clipname[0] ] ) {

			// Move Clip to base directory if 'active' selected
			case 'active':
				if( basename( dirname( $clipdir ) ) === 'unclipped' ) {
					$path = ( substr( $clip, 0, 5 ) == 'clip_' ) ? '/..' : '';
					rename( $clipdir, dirname( $clipdir ) . $path . '/../' . $clip );
				}
				break;

			// Move Clip to sub directory if 'unclipped' selected
			case 'unclipped':
				if( basename( dirname( $clipdir ) ) !== 'unclipped' ) {
					$path = ( substr( $clip, 0, 5 ) == 'clip_' ) ? '/precoded' : '';
					rename( $clipdir, dirname( $clipdir ) . $path . '/unclipped/' . $clip );
				}
				break;

			// Check uninstalls and delete Clip if 'delete' selected
			case 'delete':
				$uninstall = $clipdir . '/uninstall.php';
				if( file_exists( $uninstall ) ) include( $uninstall );
				wp_clips_delete( $clipdir . '/' );
		}
	}

	// Update notice
	add_action( 'admin_print_footer_scripts', function() {
		printf( '<div class="notice notice-success"><p>%s.</p></div>', __( 'Clips have been updated', 'wp-clips' ) );
	});
}