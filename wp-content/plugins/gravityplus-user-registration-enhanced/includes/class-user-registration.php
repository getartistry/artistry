<?php
/**
 * @package   GFP_User_Registration
 * @copyright 2014-2017 gravity+
 * @license   GPL-2.0+
 * @since     1.0.0
 */

/**
 * Main Class
 *
 * Loads everything
 *
 * @since 1.0.0
 *        
 * @author Naomi C. Bush for gravity+ <support@gravityplus.pro>
 */
class GFP_User_Registration {

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *           
	 * @author Naomi C. Bush for gravity+ <support@gravityplus.pro>
	 *
	 * @var      object
	 */
	private static $_this = null;

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 *          
	 * @author Naomi C. Bush for gravity+ <support@gravityplus.pro>
	 *
	 * @var     string
	 */
	protected $version = '1.1.0';

	/**
	 * GFP_User_Registration constructor.
	 */
	public function __construct () {

		self::$_this = $this;

		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );
		
		add_action( 'admin_init', array( $this, 'admin_init' ) );
	
	}

	/**
	 * @since 1.0.0
	 *        
	 * @author Naomi C. Bush for gravity+ <support@gravityplus.pro>
	 */
	public function plugins_loaded () {
		
		if ( class_exists( 'GFForms' ) && class_exists( 'GFUser' ) ) {
			
			$this->load_textdomain();

			require_once( trailingslashit( GFP_USER_REGISTRATION_PATH ) . 'includes/feed/class-feed.php' );
			
			require_once( trailingslashit( GFP_USER_REGISTRATION_PATH ) . 'includes/form-display/class-form-display.php' );

			new GFP_User_Registration_Feed();
			
			new GFP_User_Registration_Form_Display();
		
		}
	
	}

	/**
	 * @since 1.0.0
	 *
	 * @author Naomi C. Bush for gravity+ <support@gravityplus.pro>
	 */
	public function load_textdomain () {

		$gfp_user_registration_lang_dir = dirname( plugin_basename( GFP_USER_REGISTRATION_FILE ) ) . '/languages/';
		$gfp_user_registration_lang_dir = apply_filters( 'gfp_user_registration_language_dir', $gfp_user_registration_lang_dir );

		$locale = apply_filters( 'plugin_locale', get_locale(), 'gravityplus-user-registration-enhanced' );
		$mofile = sprintf( '%1$s-%2$s.mo', 'gravityplus-user-registration-enhanced', $locale );

		$mofile_local  = $gfp_user_registration_lang_dir . $mofile;
		$mofile_global = WP_LANG_DIR . '/gravityplus-user-registration-enhanced/' . $mofile;

		if ( file_exists( $mofile_global ) ) {
			
			load_textdomain( 'gravityplus-user-registration-enhanced', $mofile_global );
		
		}
		elseif ( file_exists( $mofile_local ) ) {
			
			load_textdomain( 'gravityplus-user-registration-enhanced', $mofile_local );
		
		}
		else {
			
			load_plugin_textdomain( 'gravityplus-user-registration-enhanced', false, $gfp_user_registration_lang_dir );
		
		}
	
	}

	/**
	 *
	 * @since 1.0.0
	 *        
	 * @author Naomi C. Bush for gravity+ <support@gravityplus.pro>
	 */
	public function admin_init () {
		
		$this->check_if_new_version();
	
	}

	/**
	 * @since 1.0.0
	 *        
	 * @author Naomi C. Bush for gravity+ <support@gravityplus.pro>
	 */
	private function check_if_new_version () {
		
		if ( ( $current_version = get_option( 'gfp_user_registration_version' ) ) != $this->version ) {
			
			if ( GFForms::get_wp_option( 'gfp_user_registration_version' ) != $this->version ) {
				
				update_option( 'gfp_user_registration_version', $this->version );

				do_action( 'gfp_user_registration_new_version' );
			
			}
		
		}
	
	}

	/**
	 * @since 1.0.0
	 *        
	 * @author Naomi C. Bush for gravity+ <support@gravityplus.pro>
	 *
	 * @return string
	 */
	public static function get_version () {
		
		return self::$_this->version;
	
	}

}