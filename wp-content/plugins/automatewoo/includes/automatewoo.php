<?php

if ( ! defined( 'ABSPATH' ) ) exit;

include_once 'automatewoo-legacy.php';


/**
 * Plugin singleton
 * @class AutomateWoo
 */
final class AutomateWoo extends AutomateWoo_Legacy {

	/** @var string */
	public $version;

	/** @var string  */
	public $plugin_slug;

	/** @var string */
	public $plugin_basename;

	/** @var string  */
	public $website_url = 'https://automatewoo.com/';

	/** @var AutomateWoo\Admin @deprecated */
	public $admin;

	/** @var AutomateWoo\Session_Tracker */
	public $session_tracker;

	/** @var AutomateWoo\Order_Helper */
	public $order_helper;

	/** @var AutomateWoo\Database_Tables */
	private $database_tables;

	/** @var AutomateWoo\Options */
	private $options;

	/** @var AutomateWoo */
	private static $_instance = null;


	/**
	 * Constructor
	 */
	private function __construct() {
		$this->version = AUTOMATEWOO_VERSION;
		$this->plugin_basename = plugin_basename( AUTOMATEWOO_FILE );
		$this->plugin_slug = AUTOMATEWOO_SLUG;
		spl_autoload_register( [ $this, 'autoload' ] );
		add_action( 'woocommerce_init', [ $this, 'init' ], 20 );
	}


	/**
	 * Init
	 */
	function init() {

		$this->includes();

		AutomateWoo\Constants::init();
		AutomateWoo\Post_Types::init();
		AutomateWoo\Cron::init();
		AutomateWoo\Ajax::init();

		$this->session_tracker = new AutomateWoo\Session_Tracker();
		$this->order_helper = new AutomateWoo\Order_Helper();
		AutomateWoo\Customers::init();

		do_action( 'automatewoo_init_addons' );

		$this->database_tables()->init();

		// Init all triggers
		// Actions don't load until required by admin interface or when a workflow runs
		AutomateWoo\Triggers::init();

		if ( is_admin() ) {
			$this->admin = new AutomateWoo\Admin();
			AutomateWoo\Admin::init();
			AutomateWoo\Updater::init();
			AutomateWoo\Installer::init();
		}

		do_action( 'automatewoo_init' );

		AutomateWoo\Event_Helpers\User_Registration::init();
		AutomateWoo\Event_Helpers\Order_Pending::init();
		AutomateWoo\Event_Helpers\Order_Created::init();
		AutomateWoo\Event_Helpers\Order_Paid::init();
		AutomateWoo\Event_Helpers\Order_Status_Changed::init();
		AutomateWoo\Event_Helpers\Products_On_Sale::init();
		AutomateWoo\Event_Helpers\Review_Posted::init();

		if ( AutomateWoo\Integrations::subscriptions_enabled() ) {
			AutomateWoo\Event_Helpers\Subscription_Created::init();
			AutomateWoo\Event_Helpers\Subscription_Status_Changed::init();
		}

		AutomateWoo\Background_Processes::get_all(); // load all background processes

		if ( AW()->options()->abandoned_cart_enabled ) {
			AutomateWoo\Carts::init();
		}

		AutomateWoo\Hooks::init();

		do_action( 'automatewoo_loaded' );
	}


	/**
	 * @since 2.0
	 * @param $class
	 */
	function autoload( $class ) {
		$path = $this->get_autoload_path( $class );

		if ( $path && file_exists( $path ) ) {
			include $path;
		}
	}


	/**
	 * @param $class
	 * @return string
	 */
	function get_autoload_path( $class ) {

		if ( substr( $class, 0, 3 ) != 'AW_' && substr( $class, 0, 12 ) != 'AutomateWoo\\' )
			return false;

		$file = str_replace( ['AW_', 'AutomateWoo\\' ], '/', $class );
		$file = str_replace( '_', '-', $file );
		$file = strtolower( $file );
		$file = str_replace( '\\', '/', $file );

		$abstracts = [
			'/action',
			'/trigger',
			'/query',
			'/model',
			'/query-custom-table',
			'/integration',
			'/variable',
			'/options-api',
			'/tool',
			'/data-type',
			'/database-table',
		];


		if ( in_array( $file, $abstracts ) ) {
			return $this->path() . '/includes/abstracts' . $file . '.php';
		}
		elseif ( strstr( $file, '/admin-' ) || strstr( $file, '/admin/' ) ) {
			$file = str_replace( '/admin-', '/admin/', $file );
			$file = str_replace( '/controller-', '/controllers/', $file );

			return $this->path() . $file . '.php';
		}
		else {
			$file = str_replace( '/trigger-', '/triggers/', $file );
			$file = str_replace( '/action-', '/actions/', $file );
			$file = str_replace( '/field-', '/fields/deprecated/', $file );
			$file = str_replace( '/query-', '/queries/query-', $file );
			$file = str_replace( '/model-', '/models/model-', $file );
			$file = str_replace( '/variable-', '/variables/', $file );
			$file = str_replace( '/integration-', '/integrations/', $file );
			$file = str_replace( '/rule-', '/rules/', $file );

			return $this->path() . '/includes' . $file . '.php';
		}
	}


	/**
	 * Includes
	 */
	function includes() {
		include_once $this->path() . '/includes/deprecated.php';
		include_once $this->path() . '/includes/compatibility-functions.php';
		include_once $this->path() . '/includes/helpers.php';
		include_once $this->path() . '/includes/hooks.php';

		if ( ! class_exists('Easy_User_Tags') ) {
			new AutomateWoo\User_Tags();
		}

		if ( is_admin() ) {
			include_once $this->admin_path() . '/admin.php';
		}
	}


	/**
	 * @return AutomateWoo\Database_Tables
	 */
	function database_tables() {
		if ( ! isset( $this->database_tables ) ) {
			$this->database_tables = new AutomateWoo\Database_Tables();
		}
		return $this->database_tables;
	}


	/**
	 * @return AutomateWoo\Options
	 */
	function options() {
		if ( ! isset( $this->options ) ) {
			$this->options = new AutomateWoo\Options();
		}
		return $this->options;
	}


	/**
	 * What type of request is this?
	 * string $type ajax, frontend or admin.
	 *
	 * @param $type string
	 * @return bool
	 */
	function is_request( $type ) {
		switch ( $type ) {
			case 'admin' :
				return is_admin();
			case 'ajax' :
				return defined( 'DOING_AJAX' );
			case 'cron' :
				return defined( 'DOING_CRON' );
			case 'frontend' :
				return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
		}
		return false;
	}


	/**
	 * @param string $end
	 * @return string
	 */
	function url( $end = '' ) {
		return untrailingslashit( plugin_dir_url( $this->plugin_basename ) ) . $end;
	}


	/**
	 * @param string $end
	 * @return string
	 */
	function admin_assets_url( $end = '' ) {
		return AW()->url( '/admin/assets' . $end );
	}


	/**
	 * @param string $end
	 * @return string
	 */
	function path( $end = '' ) {
		return untrailingslashit( dirname( AUTOMATEWOO_FILE ) ) . $end;
	}


	/**
	 * @param string $end
	 * @return string
	 */
	function admin_path( $end = '' ) {
		return $this->path( '/admin' . $end );
	}


	/**
	 * @param string $end
	 * @return string
	 */
	function lib_path( $end = '' ) {
		return $this->path( '/includes/libraries' . $end );
	}


	/**
	 * @return string
	 * @since 2.4.4
	 * @deprecated use WC_Geolocation::get_ip_address()
	 */
	function get_ip() {
		if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) )
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) )
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		else
			$ip = $_SERVER['REMOTE_ADDR'];
		return $ip;
	}


	/**
	 * @param $id
	 * @return AutomateWoo\Log|bool
	 */
	function get_log( $id ) {
		return AutomateWoo\Log_Factory::get( $id );
	}


	/**
	 * @param $id
	 * @return AutomateWoo\Workflow|bool
	 */
	function get_workflow( $id ) {
		if ( ! $id ) return false;
		$workflow = new AutomateWoo\Workflow( $id );
		return $workflow->exists ? $workflow : false;
	}


	/**
	 * @param $id
	 * @return AutomateWoo\Queued_Event|bool
	 */
	function get_queued_event( $id ) {
		return AutomateWoo\Queued_Event_Factory::get( $id );
	}


	/**
	 * @param $id
	 * @return AutomateWoo\Guest|bool
	 */
	function get_guest( $id ) {
		return AutomateWoo\Guest_Factory::get( $id );
	}


	/**
	 * @param $id
	 * @return AutomateWoo\Cart|bool
	 */
	function get_cart( $id ) {
		return AutomateWoo\Cart_Factory::get( $id );
	}



	/**
	 * @return AutomateWoo - Main instance
	 */
	static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

}

/**
 * Backwards compatible
 * @return AutomateWoo
 */
function AutomateWoo() {
	return AW();
}

/**
 * @return AutomateWoo
 */
function AW() {
	return AutomateWoo::instance();
}

AW();
