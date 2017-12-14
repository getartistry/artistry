<?php
/**
 * Main class
 *
 * @author Your Inspiration Themes
 * @package YITH WooCommerce Stripe
 * @version 1.0.0
 */

use \Stripe\Error;

if ( ! defined( 'YITH_WCSTRIPE' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'YITH_WCStripe_Gateway_Addons' ) ) {
	/**
	 * WooCommerce Stripe gateway class
	 *
	 * @since 1.0.0
	 */
	class YITH_WCStripe_Gateway_Addons extends YITH_WCStripe_Gateway_Advanced {

		/**
		 * Constructor.
		 *
		 * @return \YITH_WCStripe_Gateway_Addons
		 * @since 1.0.0
		 */
		public function __construct() {
			parent::__construct();
		}

		/**
		 * Process the payment
		 *
		 * @param  int $order_id
		 * @return array
		 */
		public function process_payment( $order_id ) {
			$order      = wc_get_order( $order_id );
			$this->_current_order = $order;

			// Processing subscription
			if ( 'standard' == $this->mode && ( $this->order_contains_subscription( $order_id ) ) ) {
				return $this->process_subscription();

				// Processing regular product
			} else {
				return parent::process_payment( $order_id );
			}
		}

		/**
		 * Check if order contains subscriptions.
		 *
		 * @param  int $order_id
		 * @return bool
		 */
		protected function order_contains_subscription( $order_id ) {
			return function_exists( 'YITH_WC_Subscription' ) && YITH_WC_Subscription()->order_has_subscription( $order_id );
		}

		/**
		 * Process the subscription
		 *
		 * @param WC_Order $order
		 *
		 * @return array
		 * @throws Error\Api
		 * @internal param string $cart_token
		 */
		protected function process_subscription( $order = null ) {
			if ( empty( $order ) ) {
				$order = $this->_current_order;
			}
			
			$order_id = yit_get_order_id( $order );

			try {

				// Initializate SDK and set private key
				$this->init_stripe_sdk();

				// Card selected during payment
				$selected_card = $this->get_credit_card_num();

				// Set the token with card ID selected
				if ( $this->save_cards && 'new' != $selected_card && empty( $this->token ) ) {
					$this->token = $selected_card;
				}

				if ( empty( $this->token ) ) {
					$error_msg = __( 'Please make sure that your card details have been entered correctly and that your browser supports JavaScript.', 'yith-woocommerce-stripe' );

					if ( 'test' == $this->env ) {
						$error_msg .= ' ' . __( 'Developers: Please make sure that you\'re including jQuery and that there are no JavaScript errors in the page.', 'yith-woocommerce-stripe' );
					}

					$this->log( 'Wrong token ' . $this->token . ': ' . print_r( $_POST, true ) );

					throw new Error\Api( $error_msg );
				}

				// pay
				$response = $this->pay( $order );

				// create subscriptions
				if( $subscriptions = yit_get_prop( $order, 'subscriptions', true ) ) {
					foreach ( array_map( 'intval', $subscriptions ) as $subscription_id ) {
						$subscription = ywsbs_get_subscription( $subscription_id );
						$plan         = $this->get_plan( $subscription );
						$customer     = $this->get_customer( $order );

						// create subscription on stripe
						$stripe_subscription = $this->api->create_subscription( $customer, $plan->id, array(
							'metadata' => array(
								'subscription_id' => $subscription_id,
								'instance'        => $this->instance
							)
						) );

						// set meta data
						$subscription->set( 'stripe_subscription_id', $stripe_subscription->id );
						$subscription->set( 'stripe_customer_id', $customer->id );
						$subscription->set( 'stripe_charge_id', $order->get_transaction_id() );
						$subscription->set( 'payment_due_date', $stripe_subscription->current_period_end );

						// set meta of order
						$user = $order->get_user();
						if ( $customer ) {
							yit_save_prop( $order, 'Subscriber ID', $customer->id );
						}
						if ( $user ) {
							yit_save_prop( $order, 'Subscriber first name', $user->first_name );
							yit_save_prop( $order, 'Subscriber last name', $user->last_name );
							yit_save_prop( $order, 'Subscriber address', $user->billing_email );
						}
						yit_save_prop( $order, 'Subscriber payment type', $this->id );
						yit_save_prop( $order, 'Stripe Subscribtion ID', $stripe_subscription->id );
					}
				}

				if ( $response === true ) {
					$response = array(
						'result'   => 'success',
						'redirect' => $this->get_return_url( $order )
					);
				}

				return $response;

			} catch ( Error\Base $e ) {
				$body = $e->getJsonBody();
				$message = $e->getMessage();

				if ( $body ) {
					$err     = $body['error'];
					if ( isset( $this->errors[ $err['code'] ] ) ) {
						$message = $this->errors[ $err['code'] ];
					}

					$this->log( 'Stripe Error: ' . $e->getHttpStatus() . ' - ' . print_r( $e->getJsonBody(), true ) );

					// add order note
					$order->add_order_note( 'Stripe Error: ' . $e->getHttpStatus() . ' - ' . $e->getMessage() );

					// add block if there is an error on card
					if ( $err['type'] == 'card_error' ) {
						$this->add_block( "order_id={$order_id}" );
						WC()->session->refresh_totals = true;
					}
				}

				wc_add_notice( $message, 'error' );

				return array(
					'result'   => 'fail',
					'redirect' => ''
				);

			}
		}

		/**
		 * Get subscription ID by stripe subscription id
		 */
		public function get_subscription_id( $stripe_subscription_id ) {
			global $wpdb;
			return $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM {$wpdb->postmeta} pm INNER JOIN {$wpdb->posts} p ON pm.post_id = p.ID AND p.post_type = %s WHERE pm.meta_value = %s ORDER BY pm.post_id DESC LIMIT 1", 'ywsbs_subscription', $stripe_subscription_id ) );
		}

		/**
		 * Retrieve the plan.
		 *
		 * If it doesn't exist, create a new one and returns it.
		 *
		 * @param $subscription YWSBS_Subscription
		 *
		 * @return \Stripe\Plan
		 */
		public function get_plan( $subscription, $order = null ) {
			$object_id = ! empty( $subscription->variation_id ) ? $subscription->variation_id : $subscription->product_id;
			$product = wc_get_product( $object_id );
			$order = $order ? $order : $this->_current_order;
			//$plan_amount = $subscription->line_total + $subscription->line_tax;
			//fixed the amount of the subscription plan
			$plan_amount = $subscription->order_total;

			// translate the option saved on subscription options of product to values requested by stripe
			$interval_periods = array(
				'days'   => 'day',
				'weeks'  => 'week',
				'months' => 'month',
				'years'  => 'year'
			);

			// calculate trial days
			$interval          = str_replace( array_keys( $interval_periods ), array_values( $interval_periods ), yit_get_prop( $product, '_ywsbs_price_time_option', true ) );
			$interval_count    = intval( yit_get_prop( $product, '_ywsbs_price_is_per', true ) );
			$trial_period      = yit_get_prop( $product, '_ywsbs_trial_per', true );
			$trial_time_option = yit_get_prop( $product, '_ywsbs_trial_time_option', true );

			if ( ! empty( $trial_period ) && in_array( $trial_time_option, array( 'days', 'weeks', 'months', 'years' ) ) ) {
				$trial_end = strtotime( "+{$trial_period} {$trial_time_option}" );
				$trial = ( $trial_end - time() ) / DAY_IN_SECONDS;
			} else {
				$trial_end = strtotime( "+{$interval_count} {$interval}" );
				$trial = ( $trial_end - time() ) / DAY_IN_SECONDS;
			}

			$trial_period_days = intval( $trial );

			// hash used to prevent differences between subscription configuration
			$hash = md5( $plan_amount . $interval . $interval_count . $trial_period_days );

			// get plan if exists
			$plan_id = "product_{$object_id}_{$hash}";
			$plan = $this->api->get_plan( $plan_id );

			// if some parameter is changed with save plan, delete it to recreate it
			if ( $plan ) {
//				if ( $plan->amount != $plan_amount || $plan->interval != $interval || $plan->interval_count != $interval_count || $plan->trial_period_days != $trial_period_days ) {
//					$this->api->delete_plan( $plan_id );
//				} else {
					return $plan;
//				}
			}

			// format the name of plan
			$plan_name = strip_tags( html_entity_decode( $product->get_formatted_name() ) );
			$exploded_plan_name = explode( ' – ', $plan_name );
			$plan_name = str_replace( array_shift( $exploded_plan_name ) . ' – ', '', $plan_name );

				// if it doesn't exist, create it
			$plan = $this->api->create_plan( array(
				'id'                => $plan_id,
				'name'              => $plan_name,
				'currency'          => strtolower( $order->get_order_currency() ? $order->get_order_currency() : get_woocommerce_currency() ),
				'interval'          => $interval,
				'interval_count'    => $interval_count,
				'amount'            => YITH_WCStripe::get_amount( $plan_amount ),
				'trial_period_days' => $trial_period_days,
				'metadata'          => array(
					'product_id' => $object_id
				)
			) );

			return $plan;
		}
	}
}