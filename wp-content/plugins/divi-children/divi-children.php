<?php

/*
Plugin Name: Divi Children
Version: 3.0.7
Plugin URI: http://divi4u.com/divi-children-plugin/
Description: Easily creates highly customizable child themes of Divi, directly from your WordPress admin area.
Author: Luis Alejandre
Author URI: http://divi4u.com
Text Domain: divi-children
Domain Path: /lang

Divi Children plugin
Copyright (C) 2014-2017, Luis Alejandre - luis@divi4u.com

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.

*/


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


class DiviChildren {

	/**
	 * The current version of the plugin.
	 *
	 * @var str $plugin_version - The current version of the plugin.
	 */
	protected $plugin_version;


	/**
	 * The current version of the Divi Children Engine.
	 *
	 * @var str $engine_version - The current version of the Divi Children Engine.
	 */
	protected $engine_version;


	/**
	 * Constructor - initialize plugin
	 *
	 * Also sets the plugin version and the current version of the Divi Children Engine that can be used throughout the plugin.
	 */
	function __construct() {

		$this->plugin_version = '3.0.7';

		$this->engine_version = '3.0.6';

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_styles_scripts' ) );

		add_filter( 'admin_menu', array( $this, 'add_divi_children_page' ) );

		add_action( 'plugins_loaded', array( $this, 'load_divi_children_textdomain' ) );

		add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array( $this, 'add_settings_link' ) );

	}


	/**
	 * Enqueue admin CSS and JS
	 *
	 */
	function admin_styles_scripts() {

		$plugin_dir = plugin_dir_url( __FILE__ );

		wp_enqueue_style( 'divichildren_admin_styles', $plugin_dir . 'css/admin_styles.css', false, $this->plugin_version, 'all' );

		wp_register_script( 'jquerytabs', $plugin_dir . 'js/jquery-ui-tabs.min.js', array( 'jquery' ) );
		wp_enqueue_script( 'jquerytabs' );

		wp_register_script( 'jquerypanes', $plugin_dir . 'js/tabs-panes.js', array( 'jquerytabs' ) );
		wp_enqueue_script( 'jquerypanes' );

		wp_enqueue_media();

		wp_register_script( 'mediaup', $plugin_dir . 'js/mediaup.js', array( 'jquery' ) );
		wp_enqueue_script( 'mediaup' );

	}


	/**
	 * Add sub menu Divi Children page to the Appearance menu.
	 *
	 */	
	function add_divi_children_page() {
		add_theme_page( 'Make a Child Theme for your Divi Theme', 'Divi Children', 'install_themes', 'divi-children-page', array( $this, 'divi_children_page' ) );
	}


	/**
	 * Divi Children plugin page.
	 *
	 */	
	function divi_children_page() {

		if ( ! empty( $_POST['theme_name'] ) ) {
			$created_child = $this->create_child_theme();
			if ( is_wp_error( $created_child ) ) {
				$create_error = $created_child->get_error_message();
			}
			require( 'includes/results-page-create.php' );
			exit;
		}

		if ( ! empty( $_POST['divi_child'] ) AND ! empty( $_POST['ad_image'] ) ) {
			$screenshot_changed = $this->change_screenshot();
			if ( is_wp_error( $screenshot_changed ) ) {
				$screenshot_error = $screenshot_changed->get_error_message();
			}
			require( 'includes/results-page-screenshot.php' );
			exit;			
		}

		if ( ! empty( $_POST['divichild_to_update'] ) ) {
			$update_child = $this->child_theme_update();
			if ( is_wp_error( $update_child ) ) {
				$update_child_error = $update_child->get_error_message();
			}
			require( 'includes/results-page-update-child.php' );
			exit;
		}

		require_once( plugin_dir_path( __FILE__ ) . '/includes/forms-page.php' );

	}


	/**
	 * Create a new Divi child theme
	 *
	 * Uses via $_POST the field values from the new child theme creation form entered by user.
	 * @return bool/array - false on failure, array containing the new child theme data on success.
	 **/
	private function create_child_theme() {

		global $wp_filesystem;

		check_admin_referer( 'child-create-nonce' );

		$theme_name = sanitize_text_field( $_POST['theme_name'] );
		$theme_uri = sanitize_text_field( $_POST['theme_uri'] );
		$theme_version = sanitize_text_field( $_POST['theme_version'] );
		$theme_description = sanitize_text_field( $_POST['theme_description'] );
		$theme_authorname = sanitize_text_field( $_POST['theme_authorname'] );
		$theme_authoruri = sanitize_text_field( $_POST['theme_authoruri'] );

		$theme_slug = sanitize_title( $theme_name );
		$new_dir = get_theme_root() . '/' . $theme_slug;
		$plugin_dir = plugin_dir_path( __FILE__ );
		$source_files_dir = $plugin_dir . 'sources/child-theme-files';
		$dce_dir = $plugin_dir . 'sources/divi-children-engine-dir';

		$form_url = wp_nonce_url( 'themes.php?page=divi-children-page', 'child-create-nonce' );

		if ( ! $this->simple_filesystem_init( $form_url ) ) {
			$create_child_message = '<h3>' . __( 'Error: WP Filesystem could not be initialized. Your child theme was not created.', 'divi-children' ) . '</h3>';
			return new WP_Error( 'create_child_message', $create_child_message );
		}

		// We can use $wp_filesystem now
		// Check if a child theme folder with that slug already exists
		if( $wp_filesystem->is_dir( $new_dir ) ) {
			$repeated_dir_message = '<h3>' . __( 'The child theme could not be created. A theme folder with the same slug already exists.', 'divi-children' ) . '</h3><p>' . __( 'Please choose a different child theme name.', 'divi-children' ) . '</p>';
			return new WP_Error( 'repeated_dir_error', $repeated_dir_message );
		}		

		// Create child theme folder
		if( ! $wp_filesystem->mkdir( $new_dir ) ) {
			$create_dir_message = '<h3>' . __( 'Error: The new child theme folder could not be created.', 'divi-children' ) . '</h3>';
			return new WP_Error( 'create_dir_error', $create_dir_message );
		}

		// Create child theme stylesheet
		$stylesheet_template = $plugin_dir . 'sources/child-theme-file-templates/style-template.php';
		ob_start();
		require( $stylesheet_template );
		$stylesheet_content = ob_get_clean();
		$stylesheet_file = $new_dir . '/style.css';
		if( ! $wp_filesystem->put_contents( $stylesheet_file, $stylesheet_content, FS_CHMOD_FILE ) ) {
			$stylesheet_error_message = '<h3>' . __( 'Error: The new child theme stylesheet could not be created.', 'divi-children' ) . '</h3>';
			return new WP_Error( 'stylesheet_error', $stylesheet_error_message );
		}

		// Create rest of child theme files and folders, except Divi Children Engine
		if( ! copy_dir( $source_files_dir, $new_dir ) ) {
			$copy_dir_message = '<h3>' . __( 'Error: The new child theme files could not be created.', 'divi-children' ) . '</h3>';
			return new WP_Error( 'copy_dir_error', $copy_dir_message );
		}

		// Create Divi Children Engine
		if( ! copy_dir( $dce_dir, $new_dir ) ) {
			$copy_dce_message = '<h3>' . __( 'Error: The Divi Children Engine could not be created.', 'divi-children' ) . '</h3>';
			return new WP_Error( 'copy_dce_error', $copy_dce_message );
		}

		// Create Divi Children Engine versions file:
		if( ! $this->create_dce_versions_file( $theme_slug ) ) {
			$dce_versions_error_message  = '<h3>' . __( 'Error when trying to set the Divi Children Engine version control.', 'divi-children' ) . '</h3>';
			return new WP_Error( 'dce_versions_error', $dce_versions_error_message );
		}		

		// Enable the new child theme for multisite installs:
		$allowed_themes = get_site_option( 'allowedthemes' );
		$allowed_themes[$theme_slug] = true;
		update_site_option( 'allowedthemes', $allowed_themes );

		// Check if the child theme creation was successful and return the new child theme data
		$created_theme = wp_get_theme( $theme_slug );
		if ( $created_theme->exists() ) {
			$results = array(
				'theme_slug'		=>	$created_theme->get_stylesheet(),
				'theme_name'		=>	$created_theme->get( 'Name' ),
				'theme_uri'			=>	$created_theme->get( 'ThemeURI' ),
				'theme_version'		=>	$created_theme->get( 'Version' ),
				'theme_description'	=>	$created_theme->get( 'Description' ),
				'theme_authorname'	=>	$created_theme->get( 'Author' ),			
				'theme_authoruri'	=>	$created_theme->get( 'AuthorURI' ),
				'theme_parent'		=>	$created_theme->get( 'Template' ),
				'theme_screenshot'	=>	$created_theme->get_screenshot(),
			);
			return $results;
		}

		return false;

	}


	/**
	 * Change the screenshot of any existing Divi child theme
	 *
	 * Uses via $_POST the field values from the change screenshot form entered by user.
	 * @return bool/array - false on failure, array containing the new screenshot info on success.
	 **/
	private function change_screenshot() {

		global $wp_filesystem;

		check_admin_referer( 'change-screenshot-nonce' );
			
		$child_slug = sanitize_title( $_POST['divi_child'] );
		$screenshot_url = $_POST['ad_image'];
		$child_path = get_theme_root() . '/' . $child_slug;

		$form_url = wp_nonce_url( 'themes.php?page=divi-children-page', 'change-screenshot-nonce' );

		if ( ! $this->simple_filesystem_init( $form_url ) ) {
			$screenshot_error = '<h3>' . __( 'Error: WP Filesystem could not be initialized. Your screenshot was not changed.', 'divi-children' ) . '</h3>';
			return new WP_Error( 'change_screenshot', $screenshot_error );
		}

		$screenshot_info = @getimagesize( $screenshot_url );
		if ( ! $screenshot_info ) {
			$screenshot_error = '<h3>' . __( 'No valid image file found in the URL you provided for your new screenshot', 'divi-children' ) . '</h3><p><b>' . __( 'The screenshot was not changed', 'divi-children' ) . '.</b></p>';
			return new WP_Error( 'no_screenshot', $screenshot_error );
		}
		$screenshot_mime = $screenshot_info['mime'];
		if ( ( 'image/png' !== $screenshot_mime ) AND ( 'image/jpeg' !== $screenshot_mime ) AND ( 'image/gif' !== $screenshot_mime ) ) {
			$screenshot_error = '<h3>' . __( 'Your new screenshot should be a .png, .jpeg, .jpg or .gif image file', 'divi-children' ) . '</h3><p><b>' . __( 'The screenshot was not changed', 'divi-children' ) . '.</b></p>';
			return new WP_Error( 'wrong_screenshot', $screenshot_error );
		}
		$filetype = wp_check_filetype( $screenshot_url );
		$extension = $filetype['ext'];
	
		// Delete existing screenshots with any extension
		foreach ( glob( $child_path . '/screenshot.*' ) as $filename ) {
		   $wp_filesystem->delete( $filename );
		}

		// Copy the selected screenshot to the child theme folder:
		$new_screenshot = $child_path . '/screenshot.' . $extension;
		if( ! $wp_filesystem->copy( $screenshot_url, $new_screenshot, true ) ) {
			$screenshot_error = '<h3>' . __( 'Error when trying to copy the new screenshot to the child theme folder.', 'divi-children' ) . '</h3>';
			return new WP_Error( 'copy_screenshot_error', $screenshot_error );
		}	

		if( $wp_filesystem->exists( $new_screenshot ) ) {
			$child_name = wp_get_theme( $child_slug )->get( 'Name' );
			return array(
				'new_screenshot'	=>	get_theme_root_uri() . '/' . $child_slug . '/screenshot.' . $extension,
				'origin_screenshot'	=>	$screenshot_url,
				'child_name'		=>	$child_name,	
			);
		}

		return false;

	}	


	/**
	 * Get versions store in the Divi Children Engine versions file
	 *
	 * @return array - results array containing the versions data.
	 **/
	private function get_versions_from_dce( $child_slug ) {
		$installed_versions_file = get_theme_root() . '/' . $child_slug . '/divi-children-engine/functions/dce-versions.php';
		if ( ! function_exists( 'get_installed_dce_versions' ) ) {
			require( $installed_versions_file );
		}		
		$installed_versions = get_installed_dce_versions();
		return $installed_versions;
	}


	/**
	 * Create Divi Children Engine versions file
	 *
	 * Assumes that WP_Filesystem() has already been called and setup.
	 * @return array - results array containing the versions data.
	 **/
	private function create_dce_versions_file( $child_slug ) {
		global $wp_filesystem;
		$dce_path = get_theme_root() . '/' . $child_slug . '/divi-children-engine';
		$installed_by_version = $this->plugin_version;
		$installed_dce_version = $this->engine_version;
		$versions_template = plugin_dir_path( __FILE__ ) . 'sources/child-theme-file-templates/dce-versions-template.php';
		ob_start();
		require( $versions_template );
		$versions_content = ob_get_clean();
		$versions_file = $dce_path . '/functions/dce-versions.php';
		if( $wp_filesystem->put_contents( $versions_file, $versions_content, FS_CHMOD_FILE ) ) {
				$new_installed_versions = array(
					// 'child_slug' 			=>	$child_slug,
					'installed_dce_version' =>	$installed_dce_version,
					'installed_by_version'	=>	$installed_by_version,
				);
				return $new_installed_versions;
			} else {
				return false;
		}
	}


	/**
	 * Check the Divi Children Engine version of an existing Divi child theme, and update it if needed
	 *
	 * Uses via $_POST the theme slug of the child theme selected by the user.
	 * @return array - results array containing the update action needed or performed, plus versions data.
	 **/
	private function child_theme_update() {

		$child_slug = sanitize_text_field( $_POST['divichild_to_update'] );
		$installed_versions = $this->get_versions_from_dce( $child_slug );
		$installed_dce_version = $installed_versions['installed_version'];
		$dce_installed_by = $installed_versions['installed_by'];

		$results = array(
			'theme_slug'				=>	$child_slug,
			'theme_engine_version'		=>	$installed_dce_version,
			'theme_plugin_version'		=>	$dce_installed_by,
			'current_engine_version'	=>	$this->engine_version,
			'current_plugin_version'	=>	$this->plugin_version,
		);

		$engine_version_compare = version_compare( $this->engine_version, $installed_dce_version );

		if ( 1 === $engine_version_compare ) { // Divi Children Engine needs to be updated

				$source_dce_dir = plugin_dir_path( __FILE__ ) . 'sources/divi-children-engine-dir';
				$child_path = get_theme_root() . '/' . $child_slug;
				$dce_path = $child_path . '/divi-children-engine';

				global $wp_filesystem;
				check_admin_referer( 'child-update-nonce' );
				$form_url = wp_nonce_url( 'themes.php?page=divi-children-page', 'child-update-nonce' );

				// Initialize Filesystem:
				if ( ! $this->simple_filesystem_init( $form_url ) ) {
					$copy_dce_message = '<h3>' . __( 'Error: WP Filesystem could not be initialized. Your child theme was not updated.', 'divi-children' ) . '</h3>';
					return new WP_Error( 'copy_dce_error', $copy_dce_message );
				}			

				// Create old Divi Children Engine Backup
				$dce_backup_path = $child_path . '/divi-children-engine-backup';
				if( ! $wp_filesystem->mkdir( $dce_backup_path ) ) {
					$copy_dce_message = '<h3>' . __( 'Error: The Divi Children Engine backup folder could not be created. Your child theme was not updated.', 'divi-children' ) . '</h3>';
					return new WP_Error( 'copy_dce_error', $copy_dce_message );
				}
				if( ! copy_dir( $dce_path, $dce_backup_path ) ) {
					$copy_dce_message = '<h3>' . __( 'Error: The Divi Children Engine backup could not be created. Your child theme was not updated.', 'divi-children' ) . '</h3>';
					return new WP_Error( 'copy_dce_error', $copy_dce_message );
				}

				// Remove old Divi Children Engine:
				if( ! $wp_filesystem->rmdir( $dce_path, true ) ) {
					$copy_dce_message = '<h3>' . __( 'Error: The old Divi Children Engine could not be removed. Your child theme was not updated.', 'divi-children' ) . '</h3>';
					return new WP_Error( 'copy_dce_error', $copy_dce_message );
				}

				// Create new Divi Children Engine
				if( ! copy_dir( $source_dce_dir, $child_path ) ) {
					$copy_dce_message = '<h3>' . __( 'Error: The new Divi Children Engine could not be created. Your child theme was not updated.', 'divi-children' ) . '</h3>';
					return new WP_Error( 'copy_dce_error', $copy_dce_message );
				}

				// Create new Divi Children Engine versions file:
				$new_versions = $this->create_dce_versions_file( $child_slug );
				if( ! $new_versions ) {
						$dce_versions_error_message = '<h3>' . __( 'Error when trying to set the Divi Children Engine version control.', 'divi-children' ) . '</h3>';
						return new WP_Error( 'dce_versions_error', $dce_versions_error_message );
					} else {
						$results['update_action'] = 'updated';
						$results['new_installed_dce_version'] = $new_versions['installed_dce_version'];
						$results['new_installed_by_version'] = $new_versions['installed_by_version'];
						// Remove old Divi Children Engine Backup:
						$wp_filesystem->rmdir( $dce_backup_path, true );
				}

			} elseif( 0 === $engine_version_compare ) {  // Divi Children Engine is updated, no action needed

				$results['update_action'] = 'none';

			} elseif( -1 === $engine_version_compare ) { // There is something wrong with the Divi Children version being used

				$results['update_action'] = 'wrong-version';

		}

		return $results;

	}

	/**
	 * Get existing Divi child themes
	 *
	 * @param str $updatable - Whether to retrieve only updatable child themes (child themes with DCE and with installed versions file), if this variable is equal to 'updatable'
	 * @return bool/array - false if no Divi child themes found, array if Divi child themes found, or empty array if Divi child themes found but none of them are updatable.
	 **/
	private function get_divi_childs( $updatable = NULL ) {
		$divi_childs = false;
		$themes = wp_get_themes();
		foreach ( $themes as $theme ) {
			$parent = $theme->get( 'Template' );
			$name = $theme->get( 'Name' );
			$slug = $theme->get_stylesheet();
			if ( $parent == 'Divi' AND $slug !== 'engined' ) {
				if ( ! $divi_childs ) {
					$divi_childs = array();
				}
				if ( ! $updatable ) {
						$divi_childs[$slug] = $name;
					} elseif ( 'updatable' == $updatable ) {
						$installed_versions_file = get_theme_root() . '/' . $slug . '/divi-children-engine/functions/dce-versions.php';
						if ( file_exists( $installed_versions_file ) ) {
							$divi_childs[$slug] = $name;
						}
				}
			}
		}
		return $divi_childs;
	}


	/**
	 * Load the plugin text domain for translation.
	 *
	 */
	public function load_divi_children_textdomain() {
		load_plugin_textdomain(
			'divi-children',
			false,
			dirname( plugin_basename(__FILE__) ) . '/lang/'
		);
	}


	/**
	 * Adds settings link to the plugin on WP-Admin / Plugins page
	 *
	 */
	function add_settings_link( $links ){

		$settings = sprintf( '<a href="%1$s">%2$s</a>',
			esc_url( admin_url( 'themes.php?page=divi-children-page' ) ),
			esc_html__( 'Settings', 'divi-children' )
		);

		array_push( $links, $settings );

		return $links;

	}


	/**
	 * Initialize Filesystem object - simplified for use in this plugin
	 *
	 * @param str $form_url - URL of the page to display request form
	 * @return bool/str - false on failure, stored text on success
	 **/
	function simple_filesystem_init( $form_url ) {
		return $this->filesystem_init( $form_url, '', '', false );
	}


	/**
	 * Initialize Filesystem object
	 *
	 * @param str $form_url - URL of the page to display request form
	 * @param str $method - connection method
	 * @param str $context - destination folder
	 * @param array $fields - fields of $_POST array that should be preserved between screens
	 * @return bool/str - false on failure, stored text on success
	 **/
	function filesystem_init( $form_url, $method, $context, $fields = null ) {
		
		global $wp_filesystem;

		$credentials = request_filesystem_credentials( $form_url, $method, false, $context, $fields );

		// first attempt to get credentials:
		if ( false === $credentials ) {
			// if we get here, then we don't have credentials yet, but have just produced a form for the user to fill in, so stop processing for now:
			return false;
		}

		//check whether credentials are correct or not:
		if ( ! WP_Filesystem( $credentials ) ) {
			// incorrect connection data - ask for credentials again, now with error message:
			request_filesystem_credentials( $form_url, $method, true, $context, $fields );
			return false;
		}

		return true; //filesystem object successfully initiated

	}
	
}

new DiviChildren();

?>
