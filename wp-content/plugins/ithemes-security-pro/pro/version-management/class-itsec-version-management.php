<?php

final class ITSEC_Version_Management {
	private static $instance;

	private $settings;

	private function __construct() {
		$this->settings = ITSEC_Modules::get_settings( 'version-management' );

		if ( $this->settings['strengthen_when_outdated'] && $this->settings['is_software_outdated'] ) {
			if ( ! defined( 'DISALLOW_FILE_EDIT' ) ) {
				define( 'DISALLOW_FILE_EDIT', true );
			}

			add_filter( 'bloginfo_url', array( $this, 'remove_pingback_url' ), 10, 2 );

			if ( defined( 'XMLRPC_REQUEST' ) && XMLRPC_REQUEST ) {
				add_filter( 'authenticate', array( $this, 'block_multiauth_attempts' ), 0, 3 );
			}
		}

		if ( $this->settings['wordpress_automatic_updates'] ) {
			add_filter( 'auto_update_core', '__return_true', 20 );
			add_filter( 'allow_dev_auto_core_updates', '__return_true', 20 );
			add_filter( 'allow_minor_auto_core_updates', '__return_true', 20 );
			add_filter( 'allow_major_auto_core_updates', '__return_true', 20 );
		}

		if ( $this->settings['plugin_automatic_updates'] ) {
			add_filter( 'auto_update_plugin', '__return_true', 20 );
		}

		if ( $this->settings['theme_automatic_updates'] ) {
			add_filter( 'auto_update_theme', '__return_true', 20 );
		}

		if ( $this->settings['scan_for_old_wordpress_sites'] ) {
			add_action( 'itsec_scheduled_old-site-scan', array( $this, 'scan_for_old_sites' ) );
		}

		if ( $this->settings['strengthen_when_outdated'] ) {
			add_action( 'itsec_scheduled_outdated-software', array( $this, 'check_for_outdated_software' ) );
			add_action( 'upgrader_process_complete', array( $this, 'check_for_outdated_software' ), 100 );
		}

		add_filter( 'automatic_updates_send_debug_email', array( $this, 'maybe_enable_automatic_updates_debug_email' ) );
		add_filter( 'automatic_updates_debug_email', array( $this, 'filter_automatic_updates_debug_email' ) );

		add_action( 'itsec_scheduler_register_events', array( __CLASS__, 'register_events' ) );

		add_filter( 'itsec_notifications', array( $this, 'register_notifications' ) );
		add_filter( 'itsec_old-site-scan_notification_strings', array( $this, 'old_site_scan_strings' ) );
		add_filter( 'itsec_automatic-updates-debug_notification_strings', array( $this, 'automatic_updates_strings' ) );
	}

	public static function get_instance() {
		if ( ! self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public static function activate() {
		self::register_events();

		$self = self::get_instance();
		$self->check_for_outdated_software();
	}

	public static function deactivate() {
		ITSEC_Core::get_scheduler()->unschedule( 'old-site-scan' );
		ITSEC_Core::get_scheduler()->unschedule( 'outdated-software' );
	}

	/**
	 * Register the events.
	 *
	 * @param ITSEC_Scheduler|null $scheduler
	 */
	public static function register_events( $scheduler = null ) {
		$scheduler = $scheduler ? $scheduler : ITSEC_Core::get_scheduler();

		$old_site = ITSEC_Modules::get_setting( 'version-management', 'scan_for_old_wordpress_sites', false );
		$strengthen = ITSEC_Modules::get_setting( 'version-management', 'strengthen_when_outdated', false );

		if ( $old_site ) {
			$scheduler->schedule( ITSEC_Scheduler::S_DAILY, 'old-site-scan' );
		}

		if ( $strengthen ) {
			$scheduler->schedule( ITSEC_Scheduler::S_DAILY, 'outdated-software' );
		}
	}

	/**
	 * When the site is out of date, prevent the pingback URL from being displayed.
	 *
	 * @param string $output
	 * @param string $show
	 *
	 * @return string
	 */
	public function remove_pingback_url( $output, $show ) {
		if ( $show === 'pingback_url' ) {
			return '';
		}

		return $output;
	}

	/**
	 * Prevent a user from attempting multiple authentications in one XML RPC request.
	 *
	 * @see ITSEC_WordPress_Tweaks::block_multiauth_attempts()
	 *
	 * @param WP_User|WP_Error|null $filter_val
	 * @param string                $username
	 * @param string                $password
	 *
	 * @return mixed
	 */
	public function block_multiauth_attempts( $filter_val, $username, $password ) {
		if ( empty( $this->first_xmlrpc_credentials ) ) {
			$this->first_xmlrpc_credentials = array(
				$username,
				$password
			);

			return $filter_val;
		}

		if ( $username === $this->first_xmlrpc_credentials[0] && $password === $this->first_xmlrpc_credentials[1] ) {
			return $filter_val;
		}

		status_header( 405 );
		header( 'Content-Type: text/plain' );
		die( __( 'XML-RPC services are disabled on this site.' ) );
	}

	/**
	 * Run the scanner to detect if outdated software is running.
	 *
	 * The scanner will not be run if the software is already marked as outdated.
	 */
	public function check_for_outdated_software() {
		if ( ! $this->settings['strengthen_when_outdated'] ) {
			return;
		}

		require_once( dirname( __FILE__ ) . '/outdated-software-scanner.php' );

		ITSEC_VM_Outdated_Software_Scanner::run_scan();

		$this->update_outdated_software_flag();
	}

	/**
	 * Mark the site as running outdated software in this module's settings.
	 */
	public function update_outdated_software_flag() {
		require_once( dirname( __FILE__ ) . '/strengthen-site.php' );

		$is_software_outdated = ITSEC_Version_Management_Strengthen_Site::is_software_outdated();

		if ( $is_software_outdated !== $this->settings['is_software_outdated'] ) {
			$this->settings['is_software_outdated'] = $is_software_outdated;
			ITSEC_Modules::set_setting( 'version-management', 'is_software_outdated', $is_software_outdated );
		}
	}

	/**
	 * Scan for outdated sites in the same web root.
	 *
	 * This will not be run if old WordPress sites have already been detected.
	 */
	public function scan_for_old_sites() {
		require_once( dirname( __FILE__ ) . '/old-site-scanner.php' );

		ITSEC_VM_Old_Site_Scanner::run_scan();
	}

	/**
	 * Enable the automatic debug email if it is enabled in the Notification Center.
	 *
	 * @param bool $enabled
	 *
	 * @return bool
	 */
	public function maybe_enable_automatic_updates_debug_email( $enabled ) {

		// If the debug email is already enabled, don't disable it.
		if ( ! $enabled ) {
			$enabled = ITSEC_Core::get_notification_center()->is_notification_enabled( 'automatic-updates-debug' );
		}

		return $enabled;
	}

	/**
	 * Set automatic update email addresses.
	 *
	 * @param array $email
	 *
	 * @return array
	 */
	public function filter_automatic_updates_debug_email( $email ) {

		if ( ITSEC_Core::get_notification_center()->is_notification_enabled( 'automatic-updates-debug' ) ) {
			$email['to'] = ITSEC_Core::get_notification_center()->get_recipients( 'automatic-updates-debug' );
		}

		return $email;
	}

	public function register_notifications( $notifications ) {

		// Ask for the settings again in case of saving and adding new notifications so the cache clear happens.
		$settings = ITSEC_Modules::get_settings( 'version-management' );

		if ( $settings['wordpress_automatic_updates'] || $settings['plugin_automatic_updates'] || $settings['theme_automatic_updates'] ) {
			$notifications['automatic-updates-debug'] = array(
				'recipient' => ITSEC_Notification_Center::R_USER_LIST,
				'optional'  => true,
				'module'    => 'version-management',
			);
		}

		if ( $settings['scan_for_old_wordpress_sites'] ) {
			$notifications['old-site-scan'] = array(
				'slug'             => 'old-site-scan',
				'recipient'        => ITSEC_Notification_Center::R_USER_LIST,
				'schedule'         => ITSEC_Notification_Center::S_CONFIGURABLE,
				'subject_editable' => true,
				'module'           => 'version-management',
				'template'         => array(
					array(
						'header',
						esc_html__( 'Outdated Site Scan', 'it-l10n-ithemes-security-pro' ),
						/* translators: %s is a date range ( 1/1/16 - 2/1/16 ) */
						sprintf( esc_html__( 'Outdated sites detected on %s', 'it-l10n-ithemes-security-pro' ), '<b>{{ $_period }}</b>' )
					),
					array(
						'table',
						array(
							esc_html__( 'File Path', 'it-l10n-ithemes-security-pro' ),
							esc_html__( 'WordPress Version', 'it-l10n-ithemes-security-pro' )
						),
						array(
							':data.path',
							':data.version',
						),
					),
					array(
						'footer'
					),
				),
			);
		}

		return $notifications;
	}

	public function automatic_updates_strings() {
		return array(
			'label'       => esc_html__( 'Automatic Updates Info', 'it-l10n-ithemes-security-pro' ),
			'description' => sprintf(
				esc_html__( 'The %sVersion Management%s module will send an email with details about any automatic updates that have been performed.', 'it-l10n-ithemes-security-pro' ),
				'<a href="#" data-module-link="version-management">',
				'</a>'
			)
		);
	}

	public function old_site_scan_strings() {
		return array(
			'label'       => esc_html__( 'Old Site Scan', 'it-l10n-ithemes-security-pro' ),
			'description' => sprintf( esc_html__( 'The %1$sVersion Management%2$s module will send an email if it detects outdated WordPress sites on your hosting account. A single outdated WordPress site with a vulnerability could allow attackers to compromise all the other sites on the same hosting account.', 'it-l10n-ithemes-security-pro' ), '<a href="#" data-module-link="version-management">', '</a>' ),
			'subject'     => esc_html__( 'Old sites found on hosting account', 'it-l10n-ithemes-security-pro' )
		);
	}
}
ITSEC_Version_Management::get_instance();
