<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Triggers
 * @since 2.9
 */
class Triggers extends Registry {

	/** @var array  */
	static $includes;

	/** @var array  */
	static $loaded = [];


	/**
	 * @return array
	 */
	static function load_includes() {

		$includes = [
			'order_status_changes' => 'AutomateWoo\Trigger_Order_Status_Changes',
			'order_status_changes_each_line_item' => 'AutomateWoo\Trigger_Order_Status_Changes_Each_Line_Item',
			'order_placed' => 'AutomateWoo\Trigger_Order_Created',
			'order_placed_each_line_item' => 'AutomateWoo\Trigger_Order_Created_Each_Line_Item',
			'order_payment_received' => 'AutomateWoo\Trigger_Order_Paid',
			'order_payment_received_each_line_item' => 'AutomateWoo\Trigger_Order_Paid_Each_Line_Item',
			'order_processing' => 'AutomateWoo\Trigger_Order_Processing',
			'order_completed' => 'AutomateWoo\Trigger_Order_Completed',
			'order_cancelled' => 'AutomateWoo\Trigger_Order_Cancelled',
			'order_on_hold' => 'AutomateWoo\Trigger_Order_On_Hold',
			'order_refunded' => 'AutomateWoo\Trigger_Order_Refunded',
			'order_pending' => 'AutomateWoo\Trigger_Order_Pending',
			'order_note_added' => 'AutomateWoo\Trigger_Order_Note_Added',

			'user_new_account' => 'AutomateWoo\Trigger_Customer_New_Account',
			'user_absent' => 'AutomateWoo\Trigger_Customer_Win_Back',
			'users_total_spend' => 'AutomateWoo\Trigger_Customer_Total_Spend_Reaches',
			'users_order_count_reaches' => 'AutomateWoo\Trigger_Customer_Order_Count_Reaches',
			'user_purchases_from_category' => 'AutomateWoo\Trigger_User_Purchases_From_Category',
			'user_purchases_from_tag' => 'AutomateWoo\Trigger_User_Purchases_From_Tag',
			'user_purchases_from_taxonomy_term' => 'AutomateWoo\Trigger_User_Purchases_From_Taxonomy_Term',
			'user_purchases_specific_product' => 'AutomateWoo\Trigger_User_Purchases_Specific_Product',
			'user_purchases_product_variation_with_attribute' => 'AutomateWoo\Trigger_User_Purchases_Product_Variation_With_Attribute'
		];


		if ( AW()->options()->abandoned_cart_enabled ) {
			$includes[ 'abandoned_cart_customer' ] = 'AutomateWoo\Trigger_Abandoned_Cart_Customer';
			$includes[ 'abandoned_cart' ] = 'AutomateWoo\Trigger_Abandoned_Cart_User';
			$includes[ 'guest_abandoned_cart' ] = 'AutomateWoo\Trigger_Abandoned_Cart_Guest';
		}

		// reviews
		$includes[ 'review_posted' ] = 'AutomateWoo\Trigger_Review_Posted';

		if ( Integrations::subscriptions_enabled() ) {
			$includes[ 'subscription_created' ] = 'AutomateWoo\Trigger_Subscription_Created';
			$includes[ 'subscription_status_changed' ] = 'AutomateWoo\Trigger_Subscription_Status_Changed';
			$includes[ 'subscription_status_changed_each_line_item' ] = 'AutomateWoo\Trigger_Subscription_Status_Changed_Each_Line_Item';
			$includes[ 'subscription_before_renewal' ] = 'AutomateWoo\Trigger_Subscription_Before_Renewal';
			$includes[ 'subscription_before_end' ] = 'AutomateWoo\Trigger_Subscription_Before_End';
			$includes[ 'subscription_payment_complete' ] = 'AutomateWoo\Trigger_Subscription_Payment_Complete';
			$includes[ 'subscription_payment_failed' ] = 'AutomateWoo\Trigger_Subscription_Payment_Failed';
		}

		if ( Integrations::is_memberships_enabled() ) {
			$includes[ 'membership_created' ] = 'AutomateWoo\Trigger_Membership_Created';
			$includes[ 'membership_status_changed' ] = 'AutomateWoo\Trigger_Membership_Status_Changed';
		}

		if ( Integrations::is_mc4wp() ) {
			$includes[ 'mc4wp_form_submission' ] = 'AutomateWoo\Trigger_MC4WP_Form_Submission';
		}

		if ( $wishlist_integration = Wishlists::get_integration() ) {
			$includes[ 'wishlist_item_goes_on_sale' ] = 'AutomateWoo\Trigger_Wishlist_Item_Goes_On_Sale';
			$includes[ 'wishlist_reminder' ] = 'AutomateWoo\Trigger_Wishlist_Reminder';

			if ( $wishlist_integration == 'yith' ) {
				$includes[ 'wishlist_item_added' ] = 'AutomateWoo\Trigger_Wishlist_Item_Added';
			}
		}

		$includes[ 'workflow_times_run_reaches' ] = 'AutomateWoo\Trigger_Workflow_Times_Run_Reaches';

		$includes[ 'guest_created' ] = 'AutomateWoo\Trigger_Guest_Created';

		// deprecated
		$includes[ 'guest_leaves_review' ] = 'AutomateWoo\Trigger_Guest_Leaves_Review';
		$includes[ 'user_leaves_review' ] = 'AutomateWoo\Trigger_User_Leaves_Review';

		return apply_filters( 'automatewoo/triggers', $includes );
	}


	/**
	 * @param $trigger_name string
	 * @return Trigger|false
	 */
	static function get( $trigger_name ) {
		static::init();

		if ( ! isset( static::$loaded[ $trigger_name ] ) )
			return false;

		return static::$loaded[ $trigger_name ];
	}


	/**
	 * @return Trigger[]
	 */
	static function get_all() {
		static::init();
		return static::$loaded;
	}


	/**
	 * Load and init all triggers
	 */
	static function init() {

		foreach ( static::get_includes() as $name => $path ) {
			static::load( $name );
		}

		if ( did_action('automatewoo_init_triggers') ) return;
		static::load_legacy();
		do_action('automatewoo_init_triggers');
	}


	/**
	 * @param $trigger_name
	 */
	static function load( $trigger_name ) {

		if ( static::is_loaded( $trigger_name ) )
			return;

		$trigger = false;
		$includes = static::get_includes();

		if ( ! empty( $includes[ $trigger_name ] ) ) {
			/** @var Trigger $trigger */
			$trigger = new $includes[ $trigger_name ]();
			$trigger->set_name( $trigger_name );
		}

		static::$loaded[ $trigger_name ] = $trigger;
	}


	/**
	 * Load
	 */
	static function load_legacy() {

		if ( did_action('automatewoo_triggers_loaded') ) return;

		do_action('automatewoo_before_triggers_loaded');


		do_action('automatewoo_triggers_loaded');
	}

}
