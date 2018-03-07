<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Actions
 * @since 2.9
 */
class Actions extends Registry {

	/** @var array */
	static $includes;

	/** @var array  */
	static $loaded = [];


	/**
	 * @return array
	 */
	static function load_includes() {

		$includes = [
			'send_email' => 'AutomateWoo\Action_Send_Email',
			'send_email_raw' => 'AutomateWoo\Action_Send_Email_Raw',

			'customer_change_role' => 'AutomateWoo\Action_Customer_Change_Role',
			'customer_update_meta' => 'AutomateWoo\Action_Customer_Update_Meta',
			'customer_add_tags' => 'AutomateWoo\Action_Customer_Add_Tags',
			'customer_remove_tags' => 'AutomateWoo\Action_Customer_Remove_Tags',

			'change_order_status' => 'AutomateWoo\Action_Order_Change_Status',
			'update_order_meta' => 'AutomateWoo\Action_Order_Update_Meta',
			'resend_order_email' => 'AutomateWoo\Action_Order_Resend_Email',
			'trigger_order_action' => 'AutomateWoo\Action_Order_Trigger_Action',
			'order_update_customer_shipping_note' => 'AutomateWoo\Action_Order_Update_Customer_Shipping_Note',
			'order_add_note' => 'AutomateWoo\Action_Order_Add_Note',

			'clear_queued_events' => 'AutomateWoo\Action_Clear_Queued_Events',
			'change_workflow_status' => 'AutomateWoo\Action_Change_Workflow_Status',
		];

		if ( AW()->options()->mailchimp_integration_enabled ) {
			$includes[ 'mailchimp_subscribe' ] = 'AutomateWoo\Action_MailChimp_Subscribe';
			$includes[ 'mailchimp_unsubscribe' ] = 'AutomateWoo\Action_MailChimp_Unsubscribe';
			$includes[ 'mailchimp_update_contact_field' ] = 'AutomateWoo\Action_MailChimp_Update_Contact_Field';
			$includes[ 'mailchimp_add_to_group' ] = 'AutomateWoo\Action_MailChimp_Add_To_Group';
			$includes[ 'mailchimp_remove_from_group' ] = 'AutomateWoo\Action_MailChimp_Remove_From_Group';
		}

		if ( AW()->options()->campaign_monitor_enabled ) {
			$includes[ 'campaign_monitor_add_subscriber' ] = 'AutomateWoo\Action_Campaign_Monitor_Add_Subscriber';
			$includes[ 'campaign_monitor_remove_subscriber' ] = 'AutomateWoo\Action_Campaign_Monitor_Remove_Subscriber';
		}

		if ( Integrations::subscriptions_enabled() ) {
			$includes[ 'change_subscription_status' ] = 'AutomateWoo\Action_Subscription_Change_Status';
			$includes[ 'subscription_send_invoice' ] = 'AutomateWoo\Action_Subscription_Send_Invoice';
		}

		if ( Integrations::is_memberships_enabled() ) {
			$includes[ 'memberships_change_plan' ] = 'AutomateWoo\Action_Memberships_Change_Plan';
			$includes[ 'memberships_delete_user_membership' ] = 'AutomateWoo\Action_Memberships_Delete_User_Membership';
		}

		if ( AW()->options()->twilio_integration_enabled ) {
			$includes[ 'send_sms_twilio' ] = 'AutomateWoo\Action_Send_SMS_Twilio';
		}

		if ( AW()->options()->active_campaign_integration_enabled ) {
			$includes[ 'add_user_to_active_campaign_list' ] = 'AutomateWoo\Action_Active_Campaign_Create_Contact';
			$includes[ 'active_campaign_add_tag' ] = 'AutomateWoo\Action_Active_Campaign_Add_Tag';
			$includes[ 'active_campaign_remove_tag' ] = 'AutomateWoo\Action_Active_Campaign_Remove_Tag';
			$includes[ 'active_campaign_update_custom_field' ] = 'AutomateWoo\Action_Active_Campaign_Update_Contact_Field';
		}

		$includes[ 'custom_function' ] = 'AutomateWoo\Action_Custom_Function';
		$includes[ 'update_product_meta' ] = 'AutomateWoo\Action_Update_Product_Meta';
		$includes[ 'change_post_status' ] = 'AutomateWoo\Action_Change_Post_Status';

		$includes[ 'add_to_mad_mimi_list' ] = 'AutomateWoo\Action_Add_To_Mad_Mimi_List';
		$includes[ 'add_to_campaign_monitor' ] = 'AutomateWoo\Action_Add_To_Campaign_Monitor';


		return apply_filters( 'automatewoo/actions', $includes );
	}


	/**
	 * @param $action_name string
	 * @return Action|false
	 */
	static function get( $action_name ) {

		static::load_legacy();
		static::load( $action_name );

		if ( ! isset( static::$loaded[ $action_name ] ) )
			return false;

		return static::$loaded[ $action_name ];
	}


	/**
	 * @return Action[]
	 */
	static function get_all() {

		foreach ( static::get_includes() as $name => $path ) {
			static::load( $name );
		}

		static::load_legacy();

		return static::$loaded;
	}


	/**
	 * @param $action_name
	 */
	static function load( $action_name ) {

		if ( static::is_loaded( $action_name ) )
			return;

		$action = false;
		$includes = static::get_includes();

		if ( ! empty( $includes[ $action_name ] ) ) {
			/** @var Action $action */
			$action = new $includes[ $action_name ]();
			$action->set_name( $action_name );
		}

		static::$loaded[ $action_name ] = $action;
	}


	/**
	 * Old method of loading actions
	 */
	static function load_legacy() {

		if ( did_action('automatewoo_init_actions') ) return;
		if ( did_action( 'automatewoo_actions_loaded' ) ) return;

		do_action( 'automatewoo_before_actions_loaded' );

		do_action( 'automatewoo_actions_loaded' );
		do_action('automatewoo_init_actions');
	}

}
