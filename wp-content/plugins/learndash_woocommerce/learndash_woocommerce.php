<?php

/*
Plugin Name: LearnDash WooCommerce Integration
Plugin URI: http://www.learndash.com/work/woocommerce/
Description: LearnDash WooCommerce Integration Plugin
Version: 1.4.0
Author: LearnDash
Author URI: http://www.learndash.com
Domain Path: /languages/
Text Domain: ld-woocommerce
*/

class learndash_woocommerce {
	public $debug = false;

	public function __construct() {
		// Setup translation
		add_action( 'plugins_loaded', array( $this, 'load_translation' ) );

		add_filter( 'product_type_selector', array( $this, 'add_product_type' ), 1 );
		add_action( 'woocommerce_product_options_general_product_data', array( $this, 'render_course_selector' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'add_scripts' ) );
		// add_action( 'wp_enqueue_scripts', array( $this, 'add_front_scripts' ) );
		add_action( 'save_post', array( $this, 'store_related_courses' ), 1, 2 );
		add_action( 'woocommerce_order_status_completed', array( $this, 'send_receipt' ), 10, 1 );
		add_action( 'woocommerce_order_status_processing', array( $this, 'send_receipt' ), 10, 1 );
		add_action( 'woocommerce_order_status_refunded', array( $this, 'remove_course' ), 10, 1 );

		// Give and remove course access based on WooCommerce subscriptions
		add_action( 'cancelled_subscription', array( $this, 'delete_course_access_old' ), 10, 2 );
		add_action( 'subscription_put_on-hold', array( $this, 'delete_course_access_old' ), 10, 2 );
		add_action( 'subscription_expired', array( $this, 'delete_course_access_old' ), 10, 2 );
		add_action( 'activated_subscription', array( $this, 'give_course_access_old' ), 10, 2 );

		// New hooks for WC subscription
		add_action( 'woocommerce_subscription_status_cancelled', array( $this, 'delete_course_access' ) );
		add_action( 'woocommerce_subscription_status_on-hold', array( $this, 'delete_course_access' ) );
		add_action( 'woocommerce_subscription_status_expired', array( $this, 'delete_course_access' ) );
		// add_action( 'woocommerce_subscription_status_active', array( $this, 'give_course_access' ) );
		add_action( 'woocommerce_subscription_status_updated', array( $this, 'subscription_on_hold_to_active' ), 10, 3 );

		// Force user to log in or create account if there is LD course
		add_action( 'woocommerce_checkout_init', array( $this, 'force_login' ), 10, 1 );
	}

	public function load_translation()
	{
		// Set filter for plugin language directory
		$lang_dir = dirname( plugin_basename( __FILE__ ) ) . '/languages/';
		$lang_dir = apply_filters( 'ld_woocommerce_languages_directory', $lang_dir );

		// Load plugin translation file
		load_plugin_textdomain( 'ld-woocommerce', false, $lang_dir );
	}

	public function add_product_type( $types ) {
		$types['course'] = __( 'Course', 'ld-woocommerce' );
		return $types;
	}

	public function add_scripts() {
		wp_enqueue_script( 'ld_wc', plugins_url( '/learndash_woocommerce.js', __FILE__ ), array( 'jquery' ) );
	}

	public function add_front_scripts() {
		wp_enqueue_script( 'ld_wc_front', plugins_url( '/front.js', __FILE__ ), array( 'jquery' ) );
	}

	public function render_course_selector() {
		global $post;

		$courses_options = array(0 => __('No Related Courses', 'ld-woocommerce') );

		$courses = $this->list_courses();
		if ( ( is_array( $courses ) ) && ( !empty( $courses ) ) ) {
			$courses_options = $courses_options + $courses;
		}
		
		echo '<div class="options_group show_if_course show_if_simple">';

		$values = get_post_meta( $post->ID, '_related_course', true );
		if ( ! $values ) {
			$values = array(0);
		}

		woocommerce_wp_select( array(
			'id'          => '_related_course[]',
			'label'       => __( 'Related Courses', 'ld-woocommerce' ),
			'options'     => $courses_options,
			'desc_tip'    => true,
			'description' => __( 'You can select multiple courses to sell together holding the SHIFT key when clicking.', 'ld-woocommerce' )
		) );

		echo '<script>ldRelatedCourses = ' . json_encode( $values ) . '</script>';

		echo '</div>';
	}

	public function store_related_courses( $id, $post ) {
		$related_courses = $_POST['_related_course'];
		if ( isset( $_POST['_related_course'] ) ) {
			update_post_meta( $id, '_related_course', $_POST['_related_course'] );
		}

	}

	/**
	 * Remove course when order is refunded
	 * @param  int    $order_id  Order ID
	 * @param  int    $refund_id Refund ID
	 */
	public function remove_course( $order_id  )
	{
		$order    = new WC_Order( $order_id );
		$products = $order->get_items();

		foreach ( $products as $product ) {
			$courses_id = get_post_meta( $product['product_id'], '_related_course', true );
			if ( $courses_id && is_array( $courses_id ) ) {
				foreach ( $courses_id as $cid ) {
					$this->remove_course_access( $cid, $order->customer_user );
				}
			}
		}
	}

	public function send_receipt( $order_id ) {

		$order = new WC_Order( $order_id );
		if ( ($order) && ( $order->has_status( 'completed' ) ) ) {
			$products = $order->get_items();

			foreach($products as $product){
				$courses_id = get_post_meta( $product['product_id'], '_related_course', true );

				if ( $courses_id && is_array( $courses_id ) ) {
					foreach ( $courses_id as $cid ) {
						$this->add_course_access( $cid, $order->customer_user );

						// if WooCommerce subscription plugin enabled
						if ( class_exists( 'WC_Subscriptions' ) ) {
							// If it's a subscription...
							if ( WC_Subscriptions_Order::order_contains_subscription($order) || WC_Subscriptions_Renewal_Order::is_renewal( $order ) ) {
								error_log("Subscription (may be renewal) detected");
								if ( $sub_key = WC_Subscriptions_Manager::get_subscription_key($order_id, $product['product_id'] ) ) {
									error_log("Subscription key: " . $sub_key );
									$subscription_r = WC_Subscriptions_Manager::get_subscription( $sub_key );
									$start_date = $subscription_r['start_date'];
									error_log( "Start Date:" . $start_date );
									update_user_meta( $order->customer_user, "course_".$cid."_access_from", strtotime( $start_date ) );
								}
							}
						}
					}
				}
			}
		}
	}

	public function debug( $msg ) {
		$original_log_errors = ini_get( 'log_errors' );
		$original_error_log  = ini_get( 'error_log' );
		ini_set( 'log_errors', true );
		ini_set( 'error_log', dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'debug.log' );

		global $ld_sf_processing_id;
		if ( empty( $ld_sf_processing_id ) ) {
			$ld_sf_processing_id = time();
		}

		if ( isset( $_GET['debug'] ) || $this->debug ) {
			error_log( "[$ld_sf_processing_id] " . print_r( $msg, true ) );
		} //Comment This line to stop logging debug messages.

		ini_set( 'log_errors', $original_log_errors );
		ini_set( 'error_log', $original_error_log );
	}

	public function list_courses() {
		global $post;
		$postid = $post->ID;
		query_posts( array( 'post_type' => 'sfwd-courses', 'posts_per_page' => - 1 ) );
		$courses = array();
		while ( have_posts() ) {
			the_post();
			$courses[ get_the_ID() ] = get_the_title();
		}
		wp_reset_query();
		$post = get_post( $postid );

		return $courses;
	}

	/**
	 * Remove Access to the course linked to the subscription key
	 * 
	 * @param  int    $user_id          User ID
	 * @param  string $subscription_key Subscription key
	 * @link   https://thomaslecoz.com/learndash-with-woocommerce-subscriptions/
	 */
	public function delete_course_access_old( $user_id, $subscription_key ) {
			
		// Get the course ID related to the subscription
		$subscription = WC_Subscriptions_Manager::get_subscription( $subscription_key );
		$courses_id = get_post_meta( $subscription['product_id'], '_related_course', true );

		// Update access to the courses
		if ( $courses_id && is_array( $courses_id ) ) {
			foreach ( $courses_id as $course_id ) {
				$this->remove_course_access( $course_id, $user_id );
			}
			
		}
	}

	/**
	 * Give Access to the course linked to the subscription key
	 * 
	 * @param  int    $user_id          User ID
	 * @param  string $subscription_key Subscription key
	 * @link   https://thomaslecoz.com/learndash-with-woocommerce-subscriptions/
	 */
	public function give_course_access_old( $user_id, $subscription_key ) {

		// Get the course ID related to the subscription
		$subscription = WC_Subscriptions_Manager::get_subscription( $subscription_key );
		$courses_id = get_post_meta( $subscription['product_id'], '_related_course', true );
		$start_date = $subscription['start_date'];
		error_log( 'Start Date :' . $start_date . ' Epoch: ' . strtotime( $start_date ) );

		// Update access to the courses
		if ( $courses_id && is_array( $courses_id ) ) {
			foreach ( $courses_id as $course_id ) {
				
				error_log( "Checking for course: " . $course_id . " and User: " . $user_id );

				if(  empty( $user_id ) || empty( $course_id ) ) {
					error_log( "User id: " . $user_id . " Course Id:" . $course_id );
					return;
				}

				error_log( "Empty list or user don't have access yet." );
				$this->add_course_access( $course_id, $user_id );
				// Replace start date to keep the drip feeding working
				error_log( "Updating subscription date to original order" );
				update_user_meta( $user_id, "course_" . $course_id . "_access_from", strtotime( $start_date ) );
			}
		}
	}

	public function delete_course_access( $order )
	{
		// Get products related to this order
		$products = $order->get_items();

		foreach ( $products as $product ) {
			$courses_id = get_post_meta( $product['product_id'], '_related_course', true );
	
			// Update access to the courses
			if ( $courses_id && is_array( $courses_id ) ) {
				foreach ( $courses_id as $course_id ) {
					$this->remove_course_access( $course_id, $order->customer_user );
				}
				
			}
		}		
	}

	public function give_course_access( $order )
	{
		$products = $order->get_items();
		$start_date = $order->start_date;

		foreach ( $products as $product ) {
			$courses_id = get_post_meta( $product['product_id'], '_related_course', true );
		
			// Update access to the courses
			if ( $courses_id && is_array( $courses_id ) ) {
				foreach ( $courses_id as $course_id ) {

					error_log( "Checking for course: " . $course_id . " and User: " . $order->customer_user );

					if(  empty( $order->customer_user ) || empty( $course_id ) ) {
						error_log( "User id: " . $order->customer_user . " Course Id:" . $course_id );
						return;
					}

					error_log( "Empty list or user don't have access yet." );
					$this->add_course_access( $course_id, $order->customer_user );
					// Replace start date to keep the drip feeding working
					error_log( "Updating subscription date to original order" );
					update_user_meta( $order->customer_user, "course_" . $course_id . "_access_from", strtotime( $start_date ) );
				}
			}
		}
	}

	public function subscription_on_hold_to_active( $order, $new_status, $old_status )
	{
		if ( 'on-hold' != $old_status || 'active' != $new_status ) {
			return;
		}

		$this->give_course_access( $order );
	}

	/**
	 * Force user to login when there is a LD course in cart
	 * 
	 * @param  object $checkout Checkout object
	 */
	public function force_login( $checkout )
	{
		$cart_items = WC()->cart->cart_contents;
		foreach ( $cart_items as $key => $item ) {
			$courses = get_post_meta( $item['data']->id, '_related_course', true );
			// wp_die( var_dump( $courses ) );
			if ( isset( $courses ) || ! empty( $courses ) ) {
				foreach ( $courses as $course ) {
					if ( $course != 0 ) {
						$this->add_front_scripts();
						break 2;
					}
				}
			}
		}
	}

	/**
	 * Add course access
	 * 
	 * @param int $course_id ID of a course
	 * @param int $user_id   ID of a user
	 */
	private function add_course_access( $course_id, $user_id )
	{
		$this->increment_course_access_counter( $course_id, $user_id );
		ld_update_course_access( $user_id, $course_id );
	}

	/**
	 * Add course access
	 * 
	 * @param int $course_id ID of a course
	 * @param int $user_id   ID of a user
	 */
	private function remove_course_access( $course_id, $user_id )
	{
		$this->decrement_course_access_counter( $course_id, $user_id );
		$counter = $this->get_courses_access_counter( $user_id );

		if ( ( isset( $counter[ $course_id ] ) && $counter[ $course_id ] < 1 ) || 
			empty( $counter ) 
		) {
			ld_update_course_access( $user_id, $course_id, $remove = true );
		}
	}

	/**
	 * Get all LearnDash courses
	 * 
	 * @return object LearnDash course
	 */
	private function get_learndash_courses()
	{
		global $wpdb;
		$query = "SELECT posts.* FROM $wpdb->posts posts WHERE posts.post_type = 'sfwd-courses' AND posts.post_status = 'publish' ORDER BY posts.post_title";

		return $wpdb->get_results( $query, OBJECT );
	}

	/**
	 * Add enrolled course record to a user
	 * 
	 * @param int $course_id ID of a course
	 * @param int $user_id   ID of a user
	 */
	private function increment_course_access_counter( $course_id, $user_id )
	{
		$courses = $this->get_courses_access_counter( $user_id );

		if ( isset( $courses[ $course_id ] ) ) {
			$courses[ $course_id ] += 1;
		} else {
			$courses[ $course_id ] = 1;
		}

		update_user_meta( $user_id, '_learndash_woocommerce_enrolled_courses_access_counter', $courses );
	}

	/**
	 * Delete enrolled course record from a user
	 * 
	 * @param int $course_id ID of a course
	 * @param int $user_id   ID of a user
	 */
	private function decrement_course_access_counter( $course_id, $user_id )
	{
		$courses = $this->get_courses_access_counter( $user_id );

		if ( isset( $courses[ $course_id ] ) && $courses[ $course_id ] > 0 ) {
			$courses[ $course_id ] -= 1;
		}

		update_user_meta( $user_id, '_learndash_woocommerce_enrolled_courses_access_counter', $courses );
	}

	/**
	 * Get user enrolled course access counter
	 * 
	 * @param  int $user_id ID of a user
	 * @return array        Course access counter array
	 */
	private function get_courses_access_counter( $user_id )
	{
		$courses = get_user_meta( $user_id, '_learndash_woocommerce_enrolled_courses_access_counter', true );

		if ( ! empty( $courses ) ) {
			$courses = maybe_unserialize( $courses );
		} else {
			$courses = array();
		}
		
		return $courses;
	}
}
new learndash_woocommerce();

add_action( 'plugins_loaded', 'learndash_woocommerce_set_course_as_virtual' );


/**
 * Establish the Course Product type that is virtual, and sold individually
 */
function learndash_woocommerce_set_course_as_virtual() {
	if (class_exists('WC_Product')) {
		class WC_Product_Course extends WC_Product {

			/**
			 * Initialize course product.
			 *
			 * @param mixed $product
			 */
			public function __construct( $product ) {
				$this->product_type      = 'course';
				$this->virtual           = 'yes';
				$this->sold_individually = true;
				parent::__construct( $product );
			}


			/**
			 * Get the add to cart button text
			 *
			 * @return string
			 */
			public function add_to_cart_text() {
				$text = $this->is_purchasable() ? __( 'Add to cart', 'ld-woocommerce' ) : __( 'Read More', 'ld-woocommerce' );
				return apply_filters( 'woocommerce_product_add_to_cart_text', $text, $this );
			}

			/**
			 * Set the add to cart button URL used on the /shop/ page
			 *
			 * @return string
			 * @since 1.3.1
			 */
			public function add_to_cart_url() {
				// Code copied from WP Simple Product function of same name
				$url = $this->is_purchasable() && $this->is_in_stock() ? remove_query_arg( 'added-to-cart', add_query_arg( 'add-to-cart', $this->id ) ) : get_permalink( $this->id );
				return apply_filters( 'woocommerce_product_add_to_cart_url', $url, $this );
			}
		}
	}
}


/**
 * Add To Cart template, use the simple template
 */
add_action( 'woocommerce_course_add_to_cart', 'woocommerce_simple_add_to_cart', 30 );
