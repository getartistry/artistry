<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       codepixelzmedia.com.np
 * @since      1.0.0
 *
 * @package    Woodivi
 * @subpackage Woodivi/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Woodivi
 * @subpackage Woodivi/public
 * @author     CodePixelzMedia <wordpress.enthusiast@gmail.com>
 */
class Woodivi_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woodivi_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woodivi_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/woodivi-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woodivi_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woodivi_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/woodivi-public.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->plugin_name, 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php') ) );

	}

	public function call_to_action_add_to_cart_callback() {
		global $woocommerce;
		$product_id = $_POST['product_id'];
		$redirect_url = ! empty( $_POST['redirect_url'] ) ? esc_url( $_POST['redirect_url'] ) : $woocommerce->cart->get_checkout_url();
		if ( sizeof( $woocommerce->cart->get_cart() ) > 0 ) {
			foreach ( $woocommerce->cart->get_cart() as $cart_item_key => $values ) {
				$woocommerce->cart->add_to_cart( $product_id );
			}
		} else {
			$woocommerce->cart->add_to_cart( $product_id );
		}
		die($redirect_url);
	}

}
