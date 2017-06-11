<?php

/**
 * Two-Factor Administrative Screens
 *
 * Sets up all administrative functions for the two-factor authentication feature
 * including fields, sanitation and all other privileged functions.
 *
 * @since   1.2.0
 *
 * @package iThemes_Security
 */
class ITSEC_Two_Factor_Admin {

	/**
	 * The module's saved options
	 *
	 * @since  1.2.0
	 * @access private
	 * @var array
	 */
	private $_settings;

	/**
	 * The core plugin class utilized in order to set up admin and other screens
	 *
	 * @since  1.2.0
	 * @access private
	 * @var ITSEC_Core
	 */
	private $_core;

	/**
	 * Helper class
	 *
	 * @access private
	 * @var ITSEC_Two_Factor_Helper
	 */
	private $_helper;

	public function __construct() {
		require_once( 'class-itsec-two-factor-helper.php' );
		require_once( 'class-itsec-two-factor-core-compat.php' );
		$this->_helper  = ITSEC_Two_Factor_Helper::get_instance();
	}

	/**
	 * Setup the module's administrative functionality
	 *
	 * Loads the two-factor module's priviledged functionality including
	 * settings fields.
	 *
	 * @since 1.2.0
	 *
	 * @param ITSEC_Core $core The core plugin instance
	 *
	 * @return void
	 */
	function run( $core ) {

		$this->_settings = $this->_helper->get_settings();
		$this->_core     = $core;

		if ( is_multisite() ) {
			$this->_helper->set_core( $this->_core );
		}

		add_action( 'itsec_admin_init',           array( $this, 'itsec_admin_init' ) ); //initialize admin area
		add_action( 'itsec_add_admin_meta_boxes', array( $this, 'itsec_add_admin_meta_boxes' ) ); //add meta boxes to admin page

		add_filter( 'itsec_add_dashboard_status', array( $this, 'itsec_add_dashboard_status' ) ); //add information for plugin status
		add_filter( 'itsec_tracking_vars',        array( $this, 'itsec_tracking_vars' ) );

		//manually save options on multisite
		if ( is_multisite() ) {
			add_action( 'itsec_admin_init', array( $this, 'itsec_admin_init_multisite' ) ); //save multisite options
		}

	}

	/**
	 * Add meta boxes to primary options pages
	 *
	 * Adds the module's meta settings box to the settings page and
	 * registers the added box in the page's table of contents.
	 *
	 * @since 1.2.0
	 *
	 * @return void
	 */
	public function itsec_add_admin_meta_boxes() {

		$id    = 'two_factor_options';
		$title = __( 'Two-Factor Authentication', 'it-l10n-ithemes-security-pro' );

		add_meta_box(
			$id,
			$title,
			array( $this, 'metabox_two_factor_settings' ),
			'security_page_toplevel_page_itsec_pro',
			'advanced',
			'core'
		);

		$this->_core->add_pro_toc_item(
			array(
				'id'    => $id,
				'title' => $title,
			)
		);

	}

	/**
	 * Sets the status in the plugin dashboard
	 *
	 * Sets a low or high (depending on scheduled or not) priority item for the module's functionality
	 * in the plugin dashboard.
	 *
	 * @since 1.2.0
	 *
	 * @param array $statuses array of existing plugin dashboard statuses
	 *
	 * @return array statuses
	 */
	public function itsec_add_dashboard_status( $statuses ) {

		if ( ! empty( $this->_settings['enabled-providers'] ) ) {

			$status_array = 'safe-high';
			$status       = array( 'text' => __( 'You have at least one two-factor method enabled. Make sure to Encourage your users to use it!', 'it-l10n-ithemes-security-pro' ), 'link' => '#itsec_two_factor_enabled', 'pro' => true, );

		} else {

			$status_array = 'high';
			$status       = array( 'text' => __( 'You are not allowing two-factor authentication.', 'it-l10n-ithemes-security-pro' ), 'link' => '#two-factor-providers', 'pro' => true, );

		}

		array_push( $statuses[ $status_array ], $status );

		return $statuses;

	}

	/**
	 * Execute admin initializations
	 *
	 * Calls the dashboard warning method and sets up all module settings fields and
	 * sections.
	 *
	 * @since 1.2.0
	 *
	 * @return void
	 */
	public function itsec_admin_init() {

		//Add Settings sections
		add_settings_section(
			'two-factor-providers',
			__( 'Two-Factor Authentication', 'it-l10n-ithemes-security-pro' ),
			'__return_empty_string',
			'security_page_toplevel_page_itsec_pro'
		);

		// Field to use for enabling all providers
		add_settings_field(
			'itsec-two-factor-providers',
			__( 'Enable Two-Factor Providers', 'it-l10n-ithemes-security-pro' ),
			array( $this, 'settings_field_provider' ),
			'security_page_toplevel_page_itsec_pro',
			'two-factor-providers'
		);

		//Register the settings field for the entire module
		register_setting(
			'security_page_toplevel_page_itsec_pro',
			'itsec_two_factor'
		);

	}

	/**
	 * Prepare and save options in network settings
	 *
	 * Saves the options in a multi-site network where data sanitization and processing is not
	 * called automatically on form submission.
	 *
	 * @since 1.2.0
	 *
	 * @return void
	 */
	public function itsec_admin_init_multisite() {

		if ( isset( $_POST['itsec_two_factor'] ) ) {

			if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'security_page_toplevel_page_itsec_pro-options' ) ) {
				die( __( 'Security error!', 'it-l10n-ithemes-security-pro' ) );
			}

			update_site_option( 'itsec_two_factor', $_POST['itsec_two_factor'] ); //we must manually save network options

		}

	}

	/**
	 * Adds fields that will be tracked for Google Analytics
	 *
	 * Registers all settings in the module that will be tracked on change by
	 * Google Analytics if "allow tracking" is enabled.
	 *
	 * @since 1.2.0
	 *
	 * @param array $vars tracking vars
	 *
	 * @return array tracking vars
	 */
	public function itsec_tracking_vars( $vars ) {

		$vars['itsec_two_factor'] = array(
			'enabled' => '0:b',
			'roll'    => 'administrator:s',
		);

		return $vars;

	}

	/**
	 * Render the settings metabox
	 *
	 * Displays the contents of the module's settings metabox on the "Pro"
	 * page with all module options.
	 *
	 * @since 1.2.0
	 *
	 * @return void
	 */
	public function metabox_two_factor_settings() {

		global $itsec_globals;

		echo '<p>' . sprintf( __( "To allow users to log in with two-factor authentication, enable one or more two-factor providers. Once at least one two-factor provider is enabled, users can configure two-factor authentication from their <a href='%s'>profile</a>.", 'it-l10n-ithemes-security-pro' ), get_edit_profile_url() ) . '</p>';
		echo '<p>' . __( 'If possible, all providers should be enabled. A provider should only be disabled if it will not work properly with your site. For instance, the email provider should not be enabled if your site cannot send emails.', 'it-l10n-ithemes-security-pro' ) . '</p>';

		$this->_core->do_settings_section( 'security_page_toplevel_page_itsec_pro', 'two-factor-providers', false );

		echo '<p>' . PHP_EOL;

		settings_fields( 'security_page_toplevel_page_itsec_pro' );

		echo '<input class="button-primary" name="submit" type="submit" value="' . __( 'Save All Changes', 'it-l10n-ithemes-security-pro' ) . '" />' . PHP_EOL;

		echo '</p>' . PHP_EOL;

	}

	/**
	 * echos Provider Field
	 *
	 * Echo's the settings field that allows a provider to be enabled
	 *
	 * @return void
	 */
	public function settings_field_provider( $provider ) {

		foreach ( $this->_helper->get_all_provider_instances() as $class => $provider ) {
			$enabled = in_array( $class, $this->_settings['enabled-providers'] );
			echo '<input type="checkbox" id="itsec_two_factor_enabled-' . esc_attr( $class ) . '" name="itsec_two_factor[enabled-providers][]" value="' . esc_attr( get_class( $provider ) ) . '" ' . checked( $enabled, true, false ) . '/>';
			echo '<label for="itsec_two_factor_enabled-' . esc_attr( $class ) . '"> ';
			$provider->print_label();
			echo '</label>';
			do_action( 'two-factor-admin-options-' . $class, $enabled );
			echo '<br />';
		}

	}

}
