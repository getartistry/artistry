<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the dashboard.
 *
 * @link       http://wp.timersys.com/wordpress-social-invitations/
 * @since      2.5
 *
 * @package    Wsi
 * @subpackage Wsi/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, dashboard-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      2.5
 * @package    Wsi
 * @subpackage Wsi/includes
 * @author     Damian Logghe <info@timersys.com>
 */
if(!isset($_SESSION))
{
	session_start();
}
class Wsi {
	/**
	 * @var Wsi classes
	 */
	public $plugin_admin;
	public $activator;
	public $admin_settings;
	public $wsi_public;
	public $wsi_cron;
	public $wsi_queue;
	public $wsi_bp;
	public $wsi_collector;
	public $wsi_notices;
	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    2.5.0
	 * @access   protected
	 * @var      Wsi_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    2.5.0
	 * @access   protected
	 * @var      string    $wsi    The string used to uniquely identify this plugin.
	 */
	protected $wsi;

	/**
	 * The current version of the plugin.
	 *
	 * @since    2.5.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * All the settings options stored in db
	 *
	 * @since    2.5.0
	 * @access   protected
	 * @var      array    $settings  Saved settings
	 */
	protected $settings;

	/**
	 * Default values
	 * @since   2.5.0
	 * @access  private
	 * @var array of defaults
	 */
	private $defaults;

	/**
	 * Plugin Instance
	 * @since 2.0.6
	 * @var The Wsi plugin instance
	 */
	protected static $_instance = null;

	/**
	 * Main Wsi Instance
	 *
	 * Ensures only one instance of WSI is loaded or can be loaded.
	 *
	 * @since 2.0.6
	 * @static
	 * @see WSI()
	 * @return Wsi - Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Cloning is forbidden.
	 * @since 2.0.6
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'wsi' ), '2.1' );
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 * @since 2.0.6
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'wsi' ), '2.1' );
	}

	/**
	 * Auto-load in-accessible properties on demand.
	 * @param mixed $key
	 * @since 2.0.6
	 * @return mixed
	 */
	public function __get( $key ) {
		if ( in_array( $key, array( 'payment_gateways', 'shipping', 'mailer', 'checkout' ) ) ) {
			return $this->$key();
		}
	}

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the Dashboard and
	 * the public-facing side of the site.
	 *
	 * @since    2.5.0
	 */
	public function __construct() {

		$this->wsi = 'wsi';
		$this->version = WSI_VERSION;
		$this->providers 		= 	array('facebook' 	=> __('Facebook','wsi'),
		                                   'google' 	=> __('Gmail','wsi'),
		                                   'yahoo'		=> __('Yahoo Mail','wsi'),
		                                   'linkedin'	=> __('LinkedIn','wsi'),
		                                   'live'		=> __('Live, Hotmail','wsi'),
		                                   'twitter'	=> __('Twitter','wsi'),
		                                   'foursquare'	=> __('Foursquare','wsi'),
		                                   'mail'	    => __('Emails','wsi')
		);

		$this->load_defaults();
		$this->load_dependencies();
		$this->set_locale();
		$this->load_opts();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Wsi_Loader. Orchestrates the hooks of the plugin.
	 * - Wsi_i18n. Defines internationalization functionality.
	 * - Wsi_Admin. Defines all hooks for the dashboard.
	 * - Wsi_Public. Defines all hooks for the public side of the site.
	 * - Wsi_Upgrader. Defines all checks for plugin upgrades.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    2.5.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wsi-loader.php';
		/**
		 * Hybridauth library
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'hybridauth/hybridauth/Hybrid/Auth.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wsi-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the Dashboard.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wsi-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wsi-public.php';

		/**
		 * The class responsible for activation
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wsi-activator.php';

		/**
		 * The class responsible for Admin Settings Page. Used in Wsi Admin Class
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/includes/class-wsi-settings.php';
		/**
		 * The class responsible for all cron activities
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wsi-cron.php';
		/**
		 * The class responsible for sending messages
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wsi-queue.php';


		/**
		 * Widgets
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wsi-widget.php';

		/**
		 * The class resposible for popups and messages collector
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wsi-collector.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wsi-hybrid.php';

		/**
		 * Providers classes
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/providers/abstract-providers.php';
		foreach( $this->providers as $provider => $name ) {
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/providers/class-wsi-'.$provider.'.php';
		}

		/**
		 * Providers Senders
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/senders/abstract-senders.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/senders/class-wsi-mailer.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/senders/class-wsi-twitter.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/senders/class-wsi-linkedin.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/senders/class-wsi-facebook.php';

		/**
		 * Logger classes
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wsi-logger.php';

		/**
		 * Shortcodes
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/shortcodes.php';

		/**
		 * Buddypress
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wsi-bp.php';

		/**
		 * Helper functions
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/functions.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wsi-googl.php';
		/**
		 * Admin notices
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wsi-notices.php';



		$this->loader = new Wsi_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Wsi_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    2.5.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Wsi_i18n();
		$plugin_i18n->set_domain( $this->get_wsi() );

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the dashboard functionality
	 * of the plugin.
	 *
	 * @since    2.5.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$this->plugin_admin   = new Wsi_Admin( $this->get_wsi(), $this->get_version() );
		$this->activator      = new Wsi_Activator();
		$this->admin_settings = new Wsi_Settings( $this->wsi, $this->version );
		$this->wsi_notices    = new Wsi_Notices( $this->wsi, $this->version );
		if( get_option('wsi_plugin_updated') && !get_option('wsi_rate_plugin') )
			$this->loader->add_action( 'admin_notices', $this->wsi_notices, 'rate_plugin' );

		$this->loader->add_action( 'wpmu_new_blog', $this->activator , 'on_create_blog',10, 6 );

		$this->loader->add_action( 'admin_menu', $this->plugin_admin, 'register_menu' );
		$this->loader->add_action( 'admin_enqueue_scripts', $this->plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $this->plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'wp_ajax_wsi_test_email', $this->plugin_admin, 'send_test_email' );
		$this->loader->add_action( 'wp_ajax_wsi_delete_logs', $this->admin_settings, 'delete_logs' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    2.5.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$this->wsi_public     = new Wsi_Public( $this->get_wsi(), $this->get_version(), $this->settings );
		$this->wsi_cron       = new Wsi_Cron( $this->get_wsi(), $this->get_version() );
		$this->wsi_queue      = new Wsi_Queue( $this->get_wsi(), $this->get_version() );
		$this->wsi_bp         = new Wsi_BP();
		$this->wsi_collector  = new Wsi_Collector( $this->get_wsi(), $this->get_version() );


		$this->loader->add_filter( 'wsi/get_opts', $this, 'set_defaults', 1 );

		$this->loader->add_action( 'cron_schedules', $this->wsi_cron, 'add_cron_schedule' );
		if( !defined('WSI_SERVER_CRON') )
			$this->loader->add_action( 'wsi_queue_cron', $this->wsi_cron, 'run' );

		$this->loader->add_action( 'init', $this->wsi_cron, 'server_cron' );


		$this->loader->add_action( 'init', $this->wsi_collector, 'run', 1 );

		$this->loader->add_action( 'wp_ajax_add_to_wsi_queue', $this->wsi_queue, 'send_to_queue' );
		$this->loader->add_action( 'wp_ajax_nopriv_add_to_wsi_queue', $this->wsi_queue, 'send_to_queue' );
		$this->loader->add_action( 'wp_ajax_wsi_fb_link', $this->wsi_queue, 'get_fb_queue_id' );
		$this->loader->add_action( 'wp_ajax_nopriv_wsi_fb_link', $this->wsi_queue, 'get_fb_queue_id' );
		$this->loader->add_action( 'admin_init', $this->wsi_queue, 'unlock_queue' );

		$this->loader->add_action( 'wp_enqueue_scripts', $this->wsi_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $this->wsi_public, 'enqueue_scripts' );
		$this->loader->add_action( 'widgets_init', $this->wsi_public, 'register_widgets' );
		$this->loader->add_action( 'init', $this->wsi_public, 'catch_invited_users',9 );
		$this->loader->add_action( 'user_register', $this->wsi_public, 'check_new_registered_user' );
		$this->loader->add_action( 'init', $this->wsi_public, 'plugin_hooks' );


		$this->loader->add_action( 'bp_setup_globals', $this->wsi_bp, 'setup_globals', 2 );
		$this->loader->add_action( 'bp_setup_nav', $this->wsi_bp, 'setup_nav', 2 );
		$this->loader->add_action( 'admin_bar_menu', $this->wsi_bp, 'add_menu', 2 );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    2.5.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     2.5.0
	 * @return    string    The name of the plugin.
	 */
	public function get_wsi() {
		return $this->wsi;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     2.5.0
	 * @return    Wsi_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     2.5.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Retrieve list of available providers
	 * @since   2.5.0
	 * @return array
	 */
	public function get_providers() {
		return $this->providers;
	}

	/**
	 * Load all settings and defaults
	 * @since   2.5.0
	 */
	private function load_opts() {
		$this->settings = apply_filters( 'wsi/get_opts', wp_parse_args( get_option( 'wsi_settings', $this->defaults ), $this->defaults ) );
	}

	/**
	 * Filter opts with defaults array
	 * @param $opts
	 * @return array
	 */
	public function set_defaults( $opts ) {
		return wp_parse_args( $opts, $this->defaults );
	}
	/**
	 * Retrieve the plugin settings
	 * @return array
	 */
	public function get_opts() {
		return $this->settings;
	}

	/**
	 * Load plugin defaults
	 */
	public function load_defaults(){

		$defaults         =   array(
			'wsi_license_key'               => '',
			'redirect_url'                  => '',
			'bypass_registration_lock'      => '0',
			'force_invites'                 => '1',
			'hook_buddypress'               => '1',
			'hook_invite_anyone'            => '1',
			'enable_facebook'               => '0',
			'facebook_key'                  => '',
			'facebook_share_url'            => '',
			'enable_twitter'                => '0',
			'twitter_key'                   => '',
			'twitter_secret'                => '',
			'enable_google'                 => '0',
			'google_key'                    => '',
			'google_secret'                 => '',
			'enable_linkedin'               => '0',
			'linkedin_key'                  => '',
			'linkedin_secret'               => '',
			'enable_yahoo'                  => '0',
			'yahoo_key'                     => '',
			'yahoo_secret'                  => '',
			'enable_foursquare'             => '0',
			'foursquare_key'                => '',
			'foursquare_secret'             => '',
			'enable_live'                   => '0',
			'enable_mail'                   => '0',
			'custom_url'                    => '',
			'subject'                       => sprintf(__('I invite you to join %s', 'wsi'), get_bloginfo('name')),
			'subject_editable'              => '1',
			'html_message'                  => __('<h3>%%INVITERNAME%% just invited you!</h3><br>%%INVITERNAME%% would like you to join %%SITENAME%%.', 'wsi'),
			'html_message_editable'         => '1',
			'html_non_editable_message'     => __('Please accept the invitation in %%ACCEPTURL%% <br> Follow my twitter <a href="http://twitter.com/chifliiiii">@chifliiiii</a>', 'wsi'),
			'footer'                        => 'Powered by <a href="http://www.timersys.com/plugins/wordpress-social-invitations/">Wordpress Social Invitions</a>',
			'text_subject'                  => sprintf(__('I invite you to join %s', 'wsi'), get_bloginfo('name')),
			'text_subject_editable'         => '1',
			'message'                       => __('%%INVITERNAME%% would like you to join %%SITENAME%% , click on %%ACCEPTURL%%', 'wsi'),
			'message_editable'              => '1',
			'fb_message'                    => __('Hi! %%INVITERNAME%% would like you to join %%SITENAME%%, click here to visit this cool site.','wsi'),
			'fb_title'                      => __('%%INVITERNAME%% would like you to join %%SITENAME%%','wsi'),
			'tw_message_editable'           => '1',
			'tw_message'                    => __('%%INVITERNAME%% would like you to join %%SITENAME%% , click on %%ACCEPTURL%%', 'wsi'),
			'send_with'                     => 'own',
			'gmail_username'                => '',
			'gmail_pass'                    => '',
			'smtp_server'                   => '',
			'smtp_username'                 => '',
			'smtp_pass'                     => '',
			'smtp_port'                     => '',
			'smtp_secure'                   => '',
			'emails_limit'                  => '20',
			'emails_limit_time'             => '600',
			'enable_dev'                    => '0',
		);

		$this->defaults = apply_filters( 'wsi/get_opts/defaults', $defaults );
	}

	/**
	 * Get ordered providers
	 * @since 2.5.0
	 * @return array
	 */
	public function get_ordered_providers(){

		$providers = get_option('wsi_widget_order',true);
		return is_array($providers) ? $providers : $this->providers;
	}
}
