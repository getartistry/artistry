<?php

class ITSEC_Malware_Scheduling_Admin {

	private
		$settings,
		$core,
		$module_path,
		$defaults;

	function run( $core ) {
		$this->defaults = array(
			'enabled'             => false,
			'email_notifications' => true,
			'email_contacts'      => array(),
		);

		$this->core        = $core;
		$this->settings    = get_site_option( 'itsec_malware_scheduling' );
		$this->module_path = ITSEC_Lib::get_module_path( __FILE__ );
		
		if ( ! is_array( $this->settings ) ) {
			$this->settings = array();
		}
		
		$this->settings = array_merge( $this->defaults, $this->settings );

		add_action( 'itsec_add_admin_meta_boxes', array( $this, 'itsec_add_admin_meta_boxes' ) ); //add meta boxes to admin page
		add_action( 'itsec_admin_init', array( $this, 'itsec_admin_init' ) ); //initialize admin area
		add_filter( 'itsec_add_dashboard_status', array( $this, 'dashboard_status' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) ); //enqueue scripts for admin page
		add_action( 'wp_ajax_itsec_jquery_malware_filetree_ajax', array( $this, 'wp_ajax_itsec_jquery_malware_filetree_ajax' ) );

		//manually save options on multisite
		if ( is_multisite() ) {
			add_action( 'itsec_admin_init', array( $this, 'itsec_admin_init_multisite' ) ); //save multisite options
		}
	}
	
	public function run_scan() {
		require_once( dirname( __FILE__ ) . '/class-itsec-malware-scheduling-scanner.php' );
		
		ITSEC_Malware_Scheduling_Scanner::scan();
	}

	/**
	 * Add malware scheduling admin Javascript
	 *
	 * @since 1.6
	 *
	 * @return void
	 */
	public function admin_enqueue_scripts() {
		global $itsec_globals;
		
		if ( isset( get_current_screen()->id ) && ( strpos( get_current_screen()->id, 'security_page_toplevel_page_itsec_pro' ) !== false ) ) {
			wp_enqueue_script( 'itsec_malware_scheduling_js', $this->module_path . 'js/malware-scheduling.js', array( 'jquery' ), $itsec_globals['plugin_build'] );
			wp_enqueue_style( 'itsec_malware-scheduling_css', $this->module_path . 'css/malware-scheduling.css', array(), $itsec_globals['plugin_build'] );
		}
	}

	/**
	 * Add meta boxes to primary options pages
	 *
	 * @since 1.6
	 *
	 * @return void
	 */
	public function itsec_add_admin_meta_boxes() {

		$id    = 'malware_scheduling_options';
		$title = __( 'Malware Scan Scheduling', 'it-l10n-ithemes-security-pro' );

		add_meta_box(
			$id,
			$title,
			array( $this, 'metabox_malware_scheduling_settings' ),
			'security_page_toplevel_page_itsec_pro',
			'advanced',
			'core'
		);

		$this->core->add_pro_toc_item(
			array(
				'id'    => $id,
				'title' => $title,
			)
		);

	}

	/**
	 * Execute admin initializations
	 *
	 * @since 1.6
	 *
	 * @return void
	 */
	public function itsec_admin_init() {
		// Add settings sections
		
		add_settings_section(
			'malware_scheduling-enabled',
			__( 'Malware Scanner Scheduling', 'it-l10n-ithemes-security-pro' ),
			'__return_empty_string',
			'security_page_toplevel_page_itsec_pro'
		);
		
		add_settings_section(
			'malware_scheduling-settings',
			__( 'Malware Scanner Settings', 'it-l10n-ithemes-security-pro' ),
			'__return_empty_string',
			'security_page_toplevel_page_itsec_pro'
		);
		
		add_settings_section(
			'malware_scheduling-email_contacts',
			__( 'Malware Scanner Email Contacts', 'it-l10n-ithemes-security-pro' ),
			'__return_empty_string',
			'security_page_toplevel_page_itsec_pro'
		);
		
		
		// Add settings fields
		
		add_settings_field(
			'itsec_malware_scheduling[enabled]',
			__( 'Scheduled Malware Scanning', 'it-l10n-ithemes-security-pro' ),
			array( $this, 'settings_field_enabled' ),
			'security_page_toplevel_page_itsec_pro',
			'malware_scheduling-enabled'
		);
		
		add_settings_field(
			'itsec_malware_scheduling[email_notifications]',
			__( 'Email Notifications', 'it-l10n-ithemes-security-pro' ),
			array( $this, 'settings_field_email_notifications' ),
			'security_page_toplevel_page_itsec_pro',
			'malware_scheduling-settings'
		);
		
		add_settings_field(
			'itsec_malware_scheduling[email_contacts]',
			__( 'Email Contacts', 'it-l10n-ithemes-security-pro' ),
			array( $this, 'settings_field_email_contacts' ),
			'security_page_toplevel_page_itsec_pro',
			'malware_scheduling-email_contacts'
		);
		
		
		// Register settings for the module.
		register_setting(
			'security_page_toplevel_page_itsec_pro',
			'itsec_malware_scheduling',
			array( $this, 'sanitize_module_input' )
		);
	}
	
	/**
	 * Prepare and save options in network settings
	 *
	 * @return void
	 */
	public function itsec_admin_init_multisite() {

		if ( isset( $_POST['itsec_malware_scheduling'] ) ) {

			if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'security_page_toplevel_page_itsec_pro-options' ) ) {
				die( __( 'Security error!', 'it-l10n-ithemes-security-pro' ) );
			}

			update_site_option( 'itsec_malware_scheduling', $_POST['itsec_malware_scheduling'] ); //we must manually save network options

		}

	}

	/**
	 * Render the settings metabox
	 *
	 * @since 4.0
	 *
	 * @return void
	 */
	public function metabox_malware_scheduling_settings() {
		echo '<p>' . __( 'Protect your site with automated malware scans. When this feature is enabled, the site will be automatically scanned each day. If a problem is found, an email can be sent to select users.', 'it-l10n-ithemes-security-pro' ) . "</p>\n";
		echo '<p>' . __( 'This malware scan is powered by <a href="https://ithemes.com/sitecheck">Sucuri SiteCheck</a>. It checks for known malware, blacklisting status, website errors, and out-of-date software. Although the Sucuri team does its best to provide the best results, 100% accuracy is not realistic and is not guaranteed.', 'it-l10n-ithemes-security-pro' ) . "</p>\n";
		echo '<p>' . sprintf( __( 'Results of previous malware scans can be found on the <a href="%s">logs page</a>.', 'it-l10n-ithemes-security-pro' ), admin_url( 'admin.php?page=toplevel_page_itsec_logs&itsec_log_filter=malware#itsec_log_all' ) ) . "</p>\n";
		
		
		$this->core->do_settings_section( 'security_page_toplevel_page_itsec_pro', 'malware_scheduling-enabled', false );
		
		echo '<div id="malware_scheduling-settings" class="hide-if-js">';
		$this->core->do_settings_section( 'security_page_toplevel_page_itsec_pro', 'malware_scheduling-settings', false );
		
		echo '<div id="malware_scheduling-email_contacts" class="hide-if-js">';
		$this->core->do_settings_section( 'security_page_toplevel_page_itsec_pro', 'malware_scheduling-email_contacts', false );
		echo '</div>';
		
		echo '</div>';
		
		echo "<p>\n";
		settings_fields( 'security_page_toplevel_page_itsec_pro' );
		echo '<input class="button-primary" name="submit" type="submit" value="' . __( 'Save All Changes', 'it-l10n-ithemes-security-pro' ) . "\" />\n";
		echo "</p>\n";
	}

	/**
	 * Sanitize and validate input
	 *
	 * @since 1.6
	 *
	 * @param  Array $input array of input fields
	 *
	 * @return Array         Sanitized array
	 */
	public function sanitize_module_input( $input ) {
		$input['enabled']             = ( isset( $input['enabled'] ) && intval( $input['enabled'] == 1 ) ? true : false );
		$input['email_notifications'] = ( isset( $input['email_notifications'] ) && intval( $input['email_notifications'] == 1 ) ? true : false );
		
		if ( empty( $input['email_contacts'] ) ) {
			$input['email_contacts'] = array();
		}
		
		self::update_schedule( $input['enabled'] );
		
		if ( is_multisite() ) {
			$this->core->show_network_admin_notice( false );
			
			$this->settings = $input;
		}
		
		return $input;
	}
	
	public static function update_schedule( $enabled = false ) {
		$hook = 'itsec_malware_scheduled_scan';
		
		
		// Unschedule any existing schedules. This is to prevent faults from creating multiple schedules.
		$timestamp = wp_next_scheduled( $hook );
		$count = 0;
		
		while ( false !== $timestamp ) {
			wp_unschedule_event( $timestamp, $hook );
			
			$timestamp = wp_next_scheduled( $hook );
			
			if ( ++$count > 20 ) {
				// Prevent endless loops.
				break;
			}
		}
		
		
		if ( $enabled ) {
			wp_schedule_event( time(), 'twicedaily', $hook );
		}
	}
	
	/**
	 * Scheduled Malware Scanning Settings
	 *
	 * @since 4.4
	 *
	 * @return void
	 */
	public function settings_field_enabled() {
		$enabled = $this->settings['enabled'] ? 1 : 0;
		
		echo '<input type="checkbox" id="itsec_malware_scheduling_enabled" name="itsec_malware_scheduling[enabled]" value="1" ' . checked( 1, $enabled, false ) . '/>';
		echo '<label for="itsec_malware_scheduling_enabled"> ' . __( 'Enable scheduled malware scanning.', 'it-l10n-ithemes-security-pro' ) . '</label>';
	}

	/**
	 * Email Notifications Settings
	 *
	 * @since 4.4
	 *
	 * @return void
	 */
	public function settings_field_email_notifications() {
		$email_notifications = $this->settings['email_notifications'] ? 1 : 0;
		
		echo '<input type="checkbox" id="itsec_malware_scheduling_email_notifications" name="itsec_malware_scheduling[email_notifications]" value="1" ' . checked( 1, $email_notifications, false ) . '/>';
		echo '<label for="itsec_malware_scheduling_email_notifications"> ' . __( 'Send email notifications when an issue is found.', 'it-l10n-ithemes-security-pro' ) . '</label>';
	}
	
	public static function get_available_admin_users_and_roles() {
		if ( is_callable( 'wp_roles' ) ) {
			$roles = wp_roles();
		} else {
			$roles = new WP_Roles();
		}
		
		$available_roles = array();
		$available_users = array();
		
		foreach ( $roles->roles as $role => $details ) {
			if ( isset( $details['capabilities']['manage_options'] ) && ( true === $details['capabilities']['manage_options'] ) ) {
				$available_roles[$role] = $details['name'];
				
				$users = get_users( array( 'role' => $role ) );
				
				foreach ( $users as $user ) {
					$available_users[$user->ID] = sprintf( _x( '%1$s (%2$s)', 'user display name (user login)', 'it-l10n-ithemes-security-pro' ), $user->display_name, $user->user_login );
				}
			}
		}
		
		natcasesort( $available_users );
		
		return array(
			'users' => $available_users,
			'roles' => $available_roles,
		);
	}

	/**
	 * Email Contacts Settings
	 *
	 * @since 4.4
	 *
	 * @return void
	 */
	public function settings_field_email_contacts() {
		$users_and_roles = self::get_available_admin_users_and_roles();
		
		$available_users = $users_and_roles['users'];
		$available_roles = $users_and_roles['roles'];
		
		natcasesort( $available_users );
		
		
		if ( empty( $this->settings['email_contacts'] ) || ! is_array( $this->settings['email_contacts'] ) ) {
			$contacts = array();
			
			foreach ( array_keys( $available_roles ) as $role ) {
				$contacts[] = "role:$role";
			}
		} else {
			$contacts = $this->settings['email_contacts'];
		}
		
		
		echo '<p>' . __( 'Select which users should get an email if an issue is found during a malware scan.', 'it-l10n-ithemes-security-pro' ) . "</p>\n";
		
		echo "<ul>\n";
		foreach ( $available_roles as $role => $name ) {
			$checked = in_array( "role:$role", $contacts ) ? ' checked="checked"' : '';
			
			echo '<li>';
			echo '<input type="checkbox" id="' . esc_attr( "itsec_malware_scheduling_email_contacts-role-$role" ) . '" name="itsec_malware_scheduling[email_contacts][]" value="' . esc_attr( "role:$role" ) . '"' . $checked . ' /> ';
			echo '<label for="' . esc_attr( "itsec_malware_scheduling_email_contacts-role-$role" ) . '">' . esc_html( sprintf( _x( 'All %s users', 'role', 'it-l10n-ithemes-security-pro' ), $name ) ) . '</label>';
			echo "</li>\n";
		}
		echo "</ul>\n";
		
		echo "<ul>\n";
		foreach ( $available_users as $id => $name ) {
			$checked = in_array( $id, $contacts ) ? ' checked="checked"' : '';
			
			echo '<li>';
			echo '<input type="checkbox" id="' . esc_attr( "itsec_malware_scheduling_email_contacts-$id" ) . '" name="itsec_malware_scheduling[email_contacts][]" value="' . esc_attr( $id ) . '"' . $checked . '" /> ';
			echo '<label for="' . esc_attr( "itsec_malware_scheduling_email_contacts-$id" ) . '">' . esc_html( $name ) . '</label>';
			echo "</li>\n";
		}
		echo "</ul>\n";
	}

	/**
	 * Sets the status in the plugin dashboard
	 *
	 * @since 4.4
	 *
	 * @param array $statuses array of statuses
	 *
	 * @return array array of statuses
	 */
	public function dashboard_status( $statuses ) {
		if ( $this->settings['enabled'] ) {
			$status_array = 'safe-high';
			$status       = array( 'text' => __( 'Malware scanning is scheduled to run automatically.', 'it-l10n-ithemes-security-pro' ), 'link' => '#itsec_malware_scheduling_enabled', 'pro' => true );
		} else {
			$status_array = 'high';
			$status       = array( 'text' => __( 'Malware scanning is not scheduled to run automatically.', 'it-l10n-ithemes-security-pro' ), 'link' => '#itsec_malware_scheduling_enabled', 'pro' => true );
		}
		
		array_push( $statuses[$status_array], $status );
		
		return $statuses;
	}
}
