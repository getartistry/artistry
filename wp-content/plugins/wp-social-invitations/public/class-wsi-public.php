<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://wp.timersys.com/wordpress-social-invitations/
 * @since      2.5.0
 *
 * @package    Wsi
 * @subpackage Wsi/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    Wsi
 * @subpackage Wsi/public
 * @author     Damian Logghe <info@timersys.com>
 */
class Wsi_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    2.5
	 * @access   private
	 * @var      string    $wsi    The ID of this plugin.
	 */
	private $wsi;

	/**
	 * The version of this plugin.
	 *
	 * @since    2.5
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Plugin settings.
	 *
	 * @since    2.5
	 * @access   private
	 * @var      array    $version
	 */
	private $opts;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    2.5
	 *
	 * @param string $wsi
	 * @param string $version
	 * @param $opts
	 *
	 * @internal param string $wsi The name of the plugin.
	 * @internal param string $version The version of this plugin.
	 */
	public function __construct( $wsi, $version, $opts ) {

		$this->wsi = $wsi;
		$this->version = $version;
		$this->opts = $opts;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    2.5
	 */
	public function enqueue_styles() {



		wp_enqueue_style( $this->wsi, plugin_dir_url( __FILE__ ) . 'assets/css/wsi-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    2.5.0
	 */
	public function enqueue_scripts() {

		global $wsi_plugin;
		$current_user = wp_get_current_user();
		$opts = $wsi_plugin->get_opts();

		wp_enqueue_script( 'wsi-js', plugin_dir_url( __FILE__ ) . 'assets/js/wsi-public.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( 'wsi-js', 'WsiMyAjax', array(
			'login_url'			=> site_url( 'wp-login.php' ),
			'site_url' 			=> site_url('/'),
			'admin_url'			=> admin_url( 'admin-ajax.php' ),
			'nonce' 			=> wp_create_nonce( 'wsi-ajax-nonce' ),
			'locale' 			=> get_bloginfo('language'),
			'appId' 			=> $opts['facebook_key'],
			'fburl' 			=> $opts['facebook_share_url'],
			'fbCustomurl'		=> apply_filters('wsi/placeholders/custom_url'	, $opts['custom_url']),
			'redirect_url'		=> $opts['redirect_url'],
			'wsi_obj_id'		=> wsi_get_obj_id(),
			'current_url'	    => wsi_current_url(),
			'user_id'           => $current_user->ID,
		) );
	}

	/**
	 * Register Sidebar widget
	 * @since   2.5.0
	 */
	public function register_widgets(){
		register_widget('Wsi_Widget');
	}


	/**
	 * Simple function that add a hidden field
	 * so we don't get kicked on registration form submission
	 * if by_registration_lock is true
	 * @since 2.5.0
	 * @return void
	 */
	public function add_wsi_hidden_field(){
		echo '<input type="hidden" name="wsi_action" values="accept-invitation"/>';
	}

	/**
	 * Function that check for users that clicked on invitation link and creates the cookie
	 * @since 2.5.0
	 * @return void
	 */
	function catch_invited_users(){

		if( isset( $_REQUEST[ 'wsi_invitation' ] ) &&  isset($_REQUEST['wsi_action']) && $_REQUEST['wsi_action'] == 'accept-invitation' ) {

			if ( in_array( $GLOBALS['pagenow'], array( 'wp-login.php', 'wp-register.php', 'wp-signup.php' ) ) ) {
				remove_action( 'bp_init', 'bp_core_wpsignup_redirect' );
				add_action( 'bp_init', array( $this, 'custom_bp_signup_redirect') );
				add_action( 'login_message', array( $this, 'showRegistrationMessage' ));
			}

			add_action( 'bp_before_register_page', array( $this, 'showRegistrationMessage' ));
			if( isset($_REQUEST['wsi_referral'])){
				add_action('wp_head', array($this, 'add_fb_og_tags'), 1);
				add_action('login_head', array($this, 'add_fb_og_tags'), 1);
				setcookie ("wsi-fb-pre-accept", $_REQUEST[ 'wsi_invitation' ], time() + 84600, '/'); // we are really passing a user id instead of queue_id -damn fb!
			} else {
				setcookie ("wsi-pre-accept", $_REQUEST[ 'wsi_invitation' ], time() + 84600, '/');
			}
			return null;
		}

	}

	/**
	 * function that modify the buddypress redirect from wp-login to custom register page
	 * We need our custom one to add our get variables
	 * @since 2.5.0
	 * @return void
	 */
	function custom_bp_signup_redirect() {

		// Bail in admin or if custom signup page is broken
		if ( is_admin() || ! bp_has_custom_signup_page() ) {
			return;
		}

		$action = !empty( $_GET['action'] ) ? $_GET['action'] : '';

		// Not at the WP core signup page and action is not register
		if ( 'register' != $action  ) {
			return;
		}

		bp_core_redirect( bp_get_signup_page() . '?wsi_invitation=' . $_REQUEST[ 'wsi_invitation' ] . '&wsi_action=accept-invitation' );
	}


	/**
	 * Display registration welcome message when user is invited with WSI
	 * used on wp-login.php and custom buddypress registration page
	 * @since 2.5.0
	 *
	 */
	public function showRegistrationMessage(){
		global $wpdb;

		if( function_exists('bp_get_current_signup_step') && 'completed-confirmation' == bp_get_current_signup_step() )
			return;

		if( isset($_REQUEST['wsi_referral'])){
			$user_id = (int) base64_decode( $_REQUEST['wsi_invitation'] );
			$stat = get_userdata($user_id);
		} else {
			$queue_id = (int) base64_decode( $_REQUEST['wsi_invitation'] );
			$stat = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}wsi_stats WHERE queue_id = %d", array( $queue_id ) ) );
		}

		$html = '';

		if( isset($stat->id) )
		{
			$inviter_text = '';
			if( $stat->display_name != '' ) $inviter_text = sprintf( __("by %s", 'wsi'),$stat->display_name );
			ob_start();

			wsi_get_template('registration.php', array(
					'options' 					=> $this->opts,
					'data' 						=> $stat,
					'inviter_text'				=> $inviter_text,
					'box_class'                 => defined('BP_VERSION') ? 'wsi_bp_enabled' : ''
				)
			);
			$html = ob_get_contents();
			ob_end_clean();
		}

		echo $html;
	}

	/**
	 * When using facebook, add og tags message
	 * @echo string
	 */
	function add_fb_og_tags(){
		global $wpseo_og, $wpseo_front;
		// remove others og tags
		add_filter( 'jetpack_enable_open_graph', '__return_false' );
		remove_action( 'wpseo_head', array( $wpseo_og, 'opengraph' ), 30 );
		remove_action( 'wpseo_head', array( $wpseo_front, 'canonical' ), 20 );
		remove_action('wp_head', 'rel_canonical');
		// add ours
		$title 			= Wsi_Collector::replaceShortcodes( $this->opts['fb_title'] );
		$description 	= Wsi_Collector::replaceShortcodes( $this->opts['fb_message'] );
		echo '<!-- Wordpress Social Invitations-->
	      	<meta property="og:title" content="'.$title.'">
	      	<meta property="og:url" content="'.wsi_current_url().'">
	      	<meta property="og:description" content="'.$description.'">';
	}
	/**
	 * Function that check for new users to see if they were invited by our plugin
	 * @since 2.5.0
	 * @return void
	 */
	function check_new_registered_user($invited_id)
	{
		global $wpdb;
		if( !isset($_COOKIE['wsi-pre-accept']) && !isset($_COOKIE['wsi-fb-pre-accept']) )
			return;

		if( isset($_COOKIE['wsi-pre-accept']) ){
			//if the cookie exists, the user was invited with our plugin. Lets give some points aways
			$queue_id = (int) base64_decode( $_COOKIE['wsi-pre-accept'] );
			$stat     = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}wsi_stats WHERE queue_id = %d", array( $queue_id ) ) );
			do_action( 'wsi/invitation_accepted', $stat->user_id, $stat, $invited_id );
			//clear the cookie
			setcookie( "wsi-pre-accept", "", time() - 3600, '/' );
		} else {
			//if the cookie exists, the user was invited with our plugin. Lets give some points aways
			$user_id =  $_COOKIE['wsi-fb-pre-accept'] ;
			do_action( 'wsi/invitation_accepted', $user_id, '', $invited_id );
			//clear the cookie
			setcookie( "wsi-fb-pre-accept", "", time() - 3600, '/' );
		}

	}

	/**
	 * Main function that display widget
	 *
	 * @param $title
	 * @param null $providers
	 * @param bool $loker
	 *
	 * @param bool $hook
	 * @param null $widget_id
	 *
	 * @return string
	 */
	public static function widget( $title, $providers = null, $loker = false, $hook = false, $widget_id = null) {
		global $wsi_plugin;

		$ordered_providers = $wsi_plugin->get_ordered_providers();
		// check if some providers are passed and display only those
		if( !empty( $providers ) ){
			$providers = wsi_filter_providers($providers);
		} else {
			$providers = $ordered_providers;
		}

		$options   = $wsi_plugin->get_opts();

		// Check if widget id is passed or generate one
		if( empty($widget_id) )
			$widget_id = wsi_generate_id();

		ob_start();

		wsi_get_template('widget/widget.php', array(
				'options' 		=> $options,
				'providers' 	=> $providers,
				'id'            => $widget_id,
				'locker'        => $loker,
				'title'         => $title,
				'hook'          => $hook,
			)
		);
		$widget = ob_get_contents();
		ob_end_clean();

		return $widget;
	}

	/**
	 * Display the widget for Invite Anyone plugin with only supported providers
	 */
	public function display_widget_ia(){
		$title = apply_filters('wsi/invite_anyone/title', __('You can also add email addresses from:', 'wsi') );
		echo Wsi_Public::widget( $title,'google,live,mail,foursquare,yahoo',false,'anyone');
	}

	/**
	 * Display the widget for Buddypress after user activated his account
	 */
	public function display_widget_bp(){
		$title = apply_filters('wsi/buddypress/title', __('You account is active, now invite some friends', 'wsi') );
		echo Wsi_Public::widget( $title );
	}

	/**
	 * Check other plugin hooks and add actions if enabled
	 */
	function plugin_hooks() {

		if( $this->opts['hook_invite_anyone'] )
			add_action( 'invite_anyone_after_addresses', array( $this, 'display_widget_ia') );
		if( $this->opts['hook_buddypress'] ) {
			add_action( 'bp_after_activate_content', array( $this, 'display_widget_bp') );
		}

	}

}
