<?php
/**
 * Main class
 *
 * @author Your Inspiration Themes
 * @package YITH WooCommerce Stripe
 * @version 1.0.0
 */

if ( ! defined( 'YITH_WCSTRIPE' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'YITH_WCStripe_Webhook' ) ){
	/**
	 * Manage webhooks of stripe
	 *
	 * @since 1.0.0
	 */
	class YITH_WCStripe_Webhook {

		/** @var object|\Stripe\Event */
		private static $event = null;

		/** @var YITH_WCStripe_Gateway|YITH_WCStripe_Gateway_Advanced|YITH_WCStripe_Gateway_Addons */
		private static $gateway = null;

		/** @var bool Avoid performing a webhook if already runned */
		private static $running = false;

		/**
		 * Constructor.
		 *
		 * Route the webhook to the own method
		 *
		 * @return \YITH_WCStripe_Webhook
		 * @since 1.0.0
		 */
		public static function route() {
			if ( self::$running ) {
				return;
			}

			self::$running = true;

			$body = @file_get_contents( 'php://input' );
			self::$event = json_decode( $body );

			// retrieve the callback to use fo this event
			$callback = str_replace( '.', '_', self::$event->type );

			if ( ! method_exists( __CLASS__, $callback ) ) {
				self::_sendSuccess( __( 'No action to perform with this event (method invoked is: ' . $callback . '.', 'yith-woocommerce-stripe' ) );
			}

			if ( ! self::$gateway = YITH_WCStripe()->get_gateway() ) {
				self::_sendSuccess( __( 'No gateway.', 'yith-woocommerce-stripe' ) );
			}

			self::$gateway->init_stripe_sdk();

			try {
				// call the method event
				call_user_func( array( __CLASS__, $callback ) );
				self::_sendSuccess( __( 'Webhook performed without error.', 'yith-woocommerce-stripe' ) );
			}

			catch ( Stripe\Error\Base $e ) {
				self::$gateway->log( 'Charge updated: ' . $e->getMessage() );
				self::_sendError( var_export( $e->getJsonBody(), true ) . "\n\n" . $e->getTraceAsString() );
			}

			catch ( Exception $e ) {
				self::$gateway->log( 'Charge updated: ' . $e->getMessage() );
				self::_sendError( $e->getCode() . ': ' . $e->getMessage() . "\n\n" . $e->getTraceAsString() );
			}

		}

		/**
		 * Handle the captured charge
		 *
		 * @var $charge \Stripe\Charge
		 * @since 1.0.0
		 */
		private static function charge_captured() {
			$charge = self::$event->data->object;
			$gateway = self::$gateway;

			// check the domain
			if ( ! isset( $charge->metadata->instance ) || $charge->metadata->instance != $gateway->instance ) {
				self::_sendSuccess( 'Instance does not match -> ' . $charge->metadata->instance . ' : ' . $gateway->instance );
			}

			// get order
			if ( ! isset( $charge->metadata->order_id ) || empty( $charge->metadata->order_id ) ) {
				self::_sendSuccess( 'No order ID set' );
			}

			$order_id = $charge->metadata->order_id;
			$order = wc_get_order( $charge->metadata->order_id );

			if ( false === $order ) {
				self::_sendSuccess( 'No order for this event' );
			}

			yit_save_prop( $order, '_captured', 'yes' );

			// check if refunds
			if ( $charge->refunds->total_count > 0 ) {
				$refunds = $charge->refunds->data;
				$amount_captured = YITH_WCStripe::get_original_amount( $charge->amount - $charge->amount_refunded );

				/**
				 * @var $refund \Stripe\Refund
				 */
				foreach ( $refunds as $refund ) {
					$amount_refunded = YITH_WCStripe::get_original_amount( $refund->amount, $refund->currency );

					// add refund to order
					$order_refund = wc_create_refund(
						array(
							'amount'     => $amount_refunded,
							'reason'     => sprintf( __( 'Captured only %s via Stripe.', 'yith-woocommerce-stripe' ), strip_tags( wc_price( $amount_captured ) ) ) . ( ! empty( $refund->reason ) ? '<br />' . $refund->reason : '' ),
							'order_id'   => $order_id,
						)
					);

					// set metadata
					yit_save_prop( $order_refund, '_refund_stripe_id', $refund->id );
				}
			}

			// complete order
			$order->update_status( 'completed', __( 'Charge captured via Stripe account.', 'yith-woocommerce-stripe' ) . '<br />' );
		}

		/**
		 * Handle the refunded charge
		 *
		 * @since 1.0.0
		 */
		private static function charge_refunded() {
			$charge = self::$event->data->object;
			$gateway = self::$gateway;

			// check the domain
			if ( ! isset( $charge->metadata->instance ) || $charge->metadata->instance != $gateway->instance ) {
				self::_sendSuccess( 'Instance does not match -> ' . $charge->metadata->instance . ' : ' . $gateway->instance );
			}

			//get order
			if ( ! isset( $charge->metadata->order_id ) || empty( $charge->metadata->order_id ) ) {
				self::_sendSuccess( 'No order ID set' );
			}

			$order = wc_get_order( $charge->metadata->order_id );
			$order_id = $charge->metadata->order_id;

			if ( false === $order ) {
				self::_sendSuccess( 'No order for this event' );
			}

			// If already captured, set as refund
			if ( $charge->captured ) {
				yit_save_prop( $order, '_captured', 'yes' );

				// check if refunds
				if ( $charge->refunds->total_count > 0 ) {
					$refunds = $charge->refunds->data;

					/**
					 * @var $refund \Stripe\Refund
					 */
					foreach ( $refunds as $refund ) {
						$amount_refunded = YITH_WCStripe::get_original_amount( $refund->amount, $refund->currency );

						// check if already exists
						foreach ( $order->get_refunds() as $the ) {
							if ( yit_get_prop( $the, '_refund_stripe_id' ) == $refund->id ) {
								continue 2;
							}
						}

						// add refund to order
						$order_refund = wc_create_refund(
							array(
								'amount'     => $amount_refunded,
								'reason'     => __( 'Refunded via Stripe.', 'yith-woocommerce-stripe' ) . ( ! empty( $refund->reason ) ? '<br />' . $refund->reason : '' ),
								'order_id'   => $order_id,
							)
						);

						$order->add_order_note( sprintf( __( 'Refunded %1$s - Refund ID: %2$s', 'woocommerce' ), $amount_refunded, $refund->id ) );

						// set metadata
						yit_save_prop( $order_refund, '_refund_stripe_id', $refund->id );
					}

					// refund order if is fully refunded
					if ( $charge->amount == $charge->amount_refunded ) {
						$order->update_status( 'refunded' );
					}
				}
			}

			// if isn't captured yet, set as cancelled
			else {
				yit_save_prop( $order, '_captured', 'no' );

				// set cancelled
				$order->update_status( 'cancelled', __( 'Authorization released via Stripe.', 'yith-woocommerce-stripe' ) . '<br />' );
			}
		}

		/**
		 * Handle the change of customer data
		 *
		 * @since 1.0.0
		 */
		private static function customer_updated() {
			$customer = self::$event->data->object;

			self::_updateCustomer( $customer );
		}

		/**
		 * Handle the change of customer data
		 *
		 * @since 1.0.0
		 */
		private static function customer_source_created() {
			$card = self::$event->data->object;

			self::_updateCustomer( $card->customer );
		}

		/**
		 * Handle the change of customer data
		 *
		 * @since 1.0.0
		 */
		private static function customer_source_updated() {
			$card = self::$event->data->object;

			self::_updateCustomer( $card->customer );
		}

		/**
		 * Handle the change of customer data
		 *
		 * @since 1.0.0
		 */
		private static function customer_source_deleted() {
			$card = self::$event->data->object;

			self::_updateCustomer( $card->customer );
		}

		/**
		 * Subscription recurring payed success
		 */
		private static function invoice_payment_succeeded() {
			/** @var \Stripe\Invoice $invoice */
			$invoice = self::$event->data->object;
			$gateway = self::$gateway;

			// get subscription line from invoice
			foreach ( $invoice->lines->data as $line ) {
				if ( 'subscription' == $line->type ) {
					$stripe_subscription_line_obj = $line;
					break;
				}
			}

			if ( empty( $stripe_subscription_line_obj ) ) {
				self::_sendSuccess( 'No subscriptions for this event.' );
			}

			// amount_due == 0 to avoid duplication on
			if ( $invoice->amount_due == 0 || ! property_exists( $invoice, 'subscription' ) || ! property_exists( $invoice, 'paid' ) || $invoice->paid !== true || ! property_exists( $invoice, 'charge' ) ) {
				self::_sendSuccess( 'Duplication' );
			}

			$stripe_subscription_id = $invoice->subscription;
			$subscription_id        = $gateway->get_subscription_id( $stripe_subscription_id );

			if ( empty( $subscription_id ) ) {
				self::_sendSuccess( 'No subscription ID on website' );
			}

			$subscription = ywsbs_get_subscription( $subscription_id );
			$invoices_processed = isset( $subscription->stripe_invoices_processed ) ? $subscription->stripe_invoices_processed : array();

			if ( in_array( $invoice->id, $invoices_processed, $invoice->charge ) ) {
				self::_sendSuccess( 'Invoice already processed.' );
			}

			$order        = wc_get_order( $subscription->order_id );
			$customer_id  = $invoice->customer;
			$user = $order->get_user();

			if ( $subscription->status == 'cancelled' ) {
				$msg = 'YSBS - Webhook stripe subscription payment error #' . $subscription_id . ' is cancelled';
				$gateway->log( $msg );
				self::_sendSuccess( $msg );
			}

			$pending_order = $subscription->renew_order;
			$last_order    = $pending_order ? wc_get_order( intval( $pending_order ) ) : false;

			if ( $last_order ) {
				$order_id      = yit_get_order_id( $last_order );
				$order = wc_get_order( $order_id );
				$order_to_save = $last_order;

			} else {

				//if the renew_order is not created try to create it
				$new_order_id  = YWSBS_Subscription_Order()->renew_order( $subscription->id );
				$order_to_save = wc_get_order( $new_order_id );
				$order_id      = $new_order_id;
				$order = wc_get_order( $order_id );

				yit_save_prop( $order, 'software_processed', 1 );
				$subscription->set( 'renew_order', $order_id );
			}

			$gateway->api->update_charge( $invoice->charge, array(
				'metadata' => array(
					'order_id' => $order_id,
					'instance' => $gateway->instance
				)
			) );

			// check if it will be expired on next renew
			if ( $subscription->expired_date != '' && $invoice->period_end > $subscription->expired_date ) {
				$gateway->api->cancel_subscription( $customer_id, $subscription_id );
			}

			yit_save_prop( $order, array_merge( array(
					'Subscriber ID' => $customer_id,
					'Subscriber payment type' => $gateway->id,
					'Stripe Subscribtion ID' => $stripe_subscription_id,
					'_captured' => 'yes',
					'next_payment_attempt' => $invoice->next_payment_attempt,
				),
				$user ? array(
					'Subscriber address' => $user->billing_email,
					'Subscriber first name' => $user->first_name,
					'Subscriber last name' => $user->last_name,
				) : array()
			) );

			// filter to increase performance during "payment_complete" action
			add_filter( 'woocommerce_delete_version_transients_limit', create_function( '', 'return 10;' ) );

			$invoices_processed[] = $invoice->id;
			$subscription->set( 'stripe_invoices_processed', $invoices_processed );
			$subscription->set( 'stripe_charge_id', $invoice->charge );
			$subscription->set( 'payment_method', $gateway->id );
			$subscription->set( 'payment_method_title', $gateway->get_title() );

			// remove the invoice from failed invoices list, if it exists
			$failed_invoices = get_user_meta( $order->get_user_id(), 'failed_invoices', true );
			if ( isset( $failed_invoices[ $subscription->id ] ) ) {
				unset( $failed_invoices[ $subscription->id ] );
				update_user_meta( $order->get_user_id(), 'failed_invoices', $failed_invoices );
			}

			// add a user meta to show him a success notice for renew
			add_user_meta( $order->get_user_id(), 'invoice_charged', true );

			$order_to_save->add_order_note( __( 'Stripe subscription payment completed.', 'yith-woocommerce-stripe' ) );
			$order_to_save->set_payment_method( $gateway );
			$order_to_save->payment_complete( $invoice->charge );

			// must be after payment_complete, because subscription plugin add the period to payment_due_date, on payment_complete
			$subscription->set( 'payment_due_date', $stripe_subscription_line_obj->period->end );
		}


		/**
		 * Subscription recurring payed failed
		 */
		private static function invoice_payment_failed() {
			/** @var \Stripe\Invoice $invoice */
			$invoice = self::$event->data->object;
			$gateway = self::$gateway;

			$stripe_subscription_id = $invoice->subscription;
			$subscription_id = $gateway->get_subscription_id( $stripe_subscription_id );

			if ( empty( $subscription_id ) ) {
				self::_sendSuccess( 'No subscription ID on website' );
			}

			$subscription = ywsbs_get_subscription( $subscription_id );

			if ( empty( $subscription ) ) {
				self::_sendSuccess( 'No subscription on website' );
			}

			$order_id = $subscription->order_id;
			$order = wc_get_order( $order_id );
			$order_sub_id  = yit_get_prop( $order, 'Stripe Subscribtion ID', true );

			if ( $stripe_subscription_id != $order_sub_id ) {
				$gateway->log( 'YSBS - Webhook stripe subscription failed payment' . $order_id );

			} else {

				// backward compatibility before YITH Subscription  1.1.3
				if ( method_exists( $subscription, 'register_failed_attemp' ) ) {
					$subscription->register_failed_attemp( $invoice->attempt_count );
				} else {
					yit_save_prop( $order, 'failed_attemps', $invoice->attempt_count );
					YITH_WC_Activity()->add_activity( $subscription_id, 'failed-payment', 'success', $order_id, sprintf( __( 'Failed payment for order %d', 'yith-woocommerce-stripe' ), $order_d ) );
				}
				
				// suspend subscription
				if( get_option('ywsbs_suspend_for_failed_recurring_payment') == 'yes' ){
					$subscription->update_status( 'suspended', 'yith-stripe' );
				}

				// register next attempt date
				yit_save_prop( $order, 'next_payment_attempt', $invoice->next_payment_attempt );
				
				$order->add_order_note( __( 'YSBS - IPN Failed payment', 'yith-woocommerce-stripe' ) );

				// save a user meta to notify the failure on my account page
				$failed_invoices = get_user_meta( $order->get_user_id(), 'failed_invoices', true );
				if ( ! isset( $failed_invoices[ $subscription->id ] ) ) {
					$failed_invoices[ $subscription->id ] = $invoice->id;
					update_user_meta( $order->get_user_id(), 'failed_invoices', $failed_invoices );
				}

				// Subscription Cancellation Completed
				$gateway->log( 'YSBS - Webhook stripe subscription failed payment ' . $order_id );
			}
		}

		/**
		 * Subscription deleted
		 */
		private static function customer_subscription_deleted() {
			$stripe_subscription = self::$event->data->object;
			$gateway = self::$gateway;

			// remove subscription on wordpress site
			$subscription_id = $gateway->get_subscription_id( $stripe_subscription->id );

			if ( empty( $subscription_id ) ) {
				return;
			}

			$subscription = ywsbs_get_subscription( $subscription_id );
			if ( 'cancelled' != $subscription->status ) {
				$subscription->cancel();
			}

			// remove the invoice from failed invoices list, if it exists
			$order = wc_get_order( $subscription->order_id );
			$failed_invoices = get_user_meta( $order->get_user_id(), 'failed_invoices', true );
			if ( isset( $failed_invoices[ $subscription->id ] ) ) {
				unset( $failed_invoices[ $subscription->id ] );
				update_user_meta( $order->get_user_id(), 'failed_invoices', $failed_invoices );
			}
		}

		/**
		 * Util method for customer update.
		 *
		 * Get profile data from stripe and update in the database
		 *
		 * @param int $customer_id The ID of customer
		 *
		 * @since 1.0.0
		 */
		private static function _updateCustomer( $customer ) {
			$gateway = self::$gateway;

			// retrieve customer from stripe profile
			$gateway->init_stripe_sdk();

			if ( is_string( $customer ) ) {
				$customer = $gateway->api->get_customer( $customer );
			}

			// exit if there is an user_id linked
			if ( ! isset( $customer->metadata->instance ) || $customer->metadata->instance != $gateway->instance || ! isset( $customer->metadata->user_id ) || empty( $customer->metadata->user_id ) ) {
				return;
			}

			// update tokens
			if ( method_exists( $gateway, 'sync_tokens' ) ) {
				$gateway->sync_tokens( $customer->metadata->user_id, $customer );
			}

			// back-compatibility
			YITH_WCStripe()->get_customer()->update_usermeta_info( $customer->metadata->user_id, array(
				'id'             => $customer->id,
				'cards'          => $customer->sources->data,
				'default_source' => $customer->default_source
			) );
		}

		/**
		 * Return success
		 *
		 * @param string $msg
		 * @since 1.2.6
		 */
		protected static function _sendSuccess( $msg = '' ) {
			status_header( 200 );
			header('Content-Type: text/plain');

			if ( ! empty( $msg ) ) {
				echo $msg;
			}

			self::$running = false;

			exit( 0 );
		}

		/**
		 * Return error
		 *
		 * @param string|Exception $msg
		 * @since 1.2.6
		 */
		protected static function _sendError( $msg = '' ) {
			header( 'Content-Type: plain/text' );
			status_header( 500 );

			if ( ! empty( $msg ) ) {
				echo $msg;
			}

			self::$running = false;

			exit( 0 );
		}
	}
}