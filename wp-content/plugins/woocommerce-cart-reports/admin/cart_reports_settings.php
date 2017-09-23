<?php
/**
 *
 * 
 */

class AV8_Cart_Reports_Settings {

	/**
	 *
	 * 
	 */
	public function __construct() {

		global $wp_roles;

		add_action('init', array($this, 'settings_setup'));
					
		if( is_admin() ) {
			add_action('admin_init', array( &$this, 'admin_init' ) );
			add_action( 'admin_enqueue_scripts', array( &$this, 'enqueue_settings_scripts' ) );
		}
	}

	/** 
	 * Initialize admin script
	 */

	public function enqueue_settings_scripts() {
		wp_enqueue_script( 'wc_cart_reports_settings_script', plugin_dir_url( __FILE__ ) . '../assets/js/admin-settings.js' );
	}

	/**
	 * Initialize WooCommerce Settings
	 * 
	 */	


	public function settings_setup() {

		global $wp_roles;
		$roles = $wp_roles->get_names();
		$roles["guest"] = "Guest"; //Need to add guest manually

		$this->settings = array(
			array( 'name' => __( 'Cart Reports Settings', 'woocommerce_cart_reports' ), 'type' => 'title', 'desc' => '', 'id' => 'minmax_quantity_options' ),
			array(  
				'name' 		=> __('Abandoned Cart Timeout (Seconds)', 'woocommerce_cart_reports'),
				'desc' 		=> __('Site activity timeout length for cart abandonment, in seconds.', 'woocommerce_cart_reports'),
				'id' 		=> 'wc_cart_reports_timeout',
				'type' 		=> 'number'
			),
			array(  
				'name' 		=> __('Dashboard Widget Time Range (Days)', 'woocommerce_cart_reports'),
				'desc' 		=> __('Time-range displayed in the middle column of the "Recent Cart Activity" dashboard widget.', 'woocommerce_cart_reports'),
				'id' 		=> 'wc_cart_reports_dashboardrange',
				'type' 		=> 'number'
			),
			array(  
				'name' 		=> __('Show Products On The Cart Index Page', 'woocommerce_cart_reports'),
				'desc' 		=> __('Displaying cart products may slow down table listing when showing many carts at once.', 'woocommerce_cart_reports'),
				'id' 		=> 'wc_cart_reports_productsindex',
				'type' 		=> 'checkbox'
			),
			array(  
				'name' 		=> __('Exclude User Roles from Cart Tracking', 'woocommerce_cart_reports'),
				'desc' 		=> __('Choose WP Roles to exclude from cart tracking', 'woocommerce_cart_reports'),
				'id' 		=> 'wc_cart_reports_trackedroles',
				'type' 		=> 'multiselect',
				'options' => $roles,
			),
			array(  
				'name' 		=> __('Log Customer IP Address', 'woocommerce_cart_reports'),
				'desc' 		=> __('Logged IP addresses are visible in the edit cart view.', 'woocommerce_cart_reports'),
				'id' 		=> 'wc_cart_reports_logip',
				'type' 		=> 'checkbox'
			),

			array(  
				'name' 		=> __('Automatically delete carts after a predefined interval?', 'woocommerce_cart_reports'),
				'desc' 		=> __('Saving a large number of carts can affect site performance. Automatically clearing the cart lists can help increase site speed.', 'woocommerce_cart_reports'),
				'id' 		=> 'wc_cart_reports_expiration_opt_in',
				'type' 		=> 'checkbox'
			),
			array(  
				'name' 		=> __('Automatically clear carts older than a specified number of days.', 'woocommerce_cart_reports'),
				'desc' 		=> __('Any cart that becomes older than the number of days specified will be automatically deleted in the background. The deletion cannot be undone', 'woocommerce_cart_reports'),
				'id' 		=> 'wc_cart_reports_expiration',
				'type' 		=> 'number'
			),
			array( 'type' => 'sectionend', 'id' => 'woocommerce_cart_report_settings'),
		);
		
		//Defaults
		add_option('wc_cart_reports_timeout', '1200');
		add_option('wc_cart_reports_dashboardrange', '2');
		add_option('wc_cart_reports_trackedroles', '');
		add_option('wc_cart_reports_productsindex', 'yes');
		add_option('wc_cart_reports_logip', 'yes');
		add_option('wc_cart_reports_expiration', 0);
		add_option('wc_cart_reports_expiration_opt_in', '');

	}

	public function admin_settings() {
		woocommerce_admin_fields( $this->settings );
	}
	public function save_admin_settings(){
		woocommerce_update_options( $this->settings );
	}
	public function admin_init()
	{
	global $pagenow;
	if($pagenow == 'admin.php' && isset($_GET['page']) && $_GET['page'] =='woocommerce_cart_reports');
		add_action('woocommerce_settings_general_options_after', array(&$this, 'admin_settings'));
		add_action('woocommerce_update_options_general', array(&$this, 'save_admin_settings'));
	} // function
		
} //END CLASS


?>