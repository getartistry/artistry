<?php
/**
 * class-groups-ws-handler.php
 *
 * Copyright (c) "kento" Karim Rahimpur www.itthinx.com
 *
 * This code is provided subject to the license granted.
 * Unauthorized use and distribution is prohibited.
 * See COPYRIGHT.txt and LICENSE.txt
 *
 * This code is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * This header and all notices must be kept intact.
 *
 * @author Karim Rahimpur
 * @package groups-woocommerce
 * @since groups-woocommerce 1.0.0
 */

/**
 * Product & subscription handler.
 */
class Groups_WS_Handler {

	/**
	 * Register action hooks.
	 */
	public static function init() {

		$options = get_option( 'groups-woocommerce', array() );
		$order_status = isset( $options[GROUPS_WS_MEMBERSHIP_ORDER_STATUS] ) ? $options[GROUPS_WS_MEMBERSHIP_ORDER_STATUS] : GROUPS_WS_DEFAULT_MEMBERSHIP_ORDER_STATUS;
		$remove_on_hold = isset( $options[GROUPS_WS_REMOVE_ON_HOLD] ) ? $options[GROUPS_WS_REMOVE_ON_HOLD] : GROUPS_WS_DEFAULT_REMOVE_ON_HOLD;

		// normal products

		// the essentials for normal order processing flow
		add_action( 'woocommerce_order_status_cancelled',  array( __CLASS__, 'order_status_cancelled' ) );
		add_action( 'woocommerce_order_status_completed',  array( __CLASS__, 'order_status_completed' ) );
		if ( $order_status == 'processing' ) {
			add_action( 'woocommerce_order_status_processing', array( __CLASS__, 'order_status_completed' ) );
		} else {
			add_action( 'woocommerce_order_status_processing', array( __CLASS__, 'order_status_processing' ) );
		}
		add_action( 'woocommerce_order_status_refunded',   array( __CLASS__, 'order_status_refunded' ) );

		// these are of concern when manual adjustments are made (backwards in order flow) 
		add_action( 'woocommerce_order_status_failed',     array( __CLASS__, 'order_status_failed' ) );
		add_action( 'woocommerce_order_status_on_hold',    array( __CLASS__, 'order_status_on_hold' ) );
		add_action( 'woocommerce_order_status_pending',    array( __CLASS__, 'order_status_pending' ) );

		// give products
		add_action( 'woocommerce_order_given', array( __CLASS__, 'order_status_completed' ) );

		// scheduled expirations
		add_action( 'groups_ws_subscription_expired', array( __CLASS__, 'subscription_expired' ), 10, 2 );

		// time-limited memberships
		add_action( 'groups_created_user_group', array( __CLASS__, 'groups_created_user_group' ), 10, 2 );
		add_action( 'groups_deleted_user_group', array( __CLASS__, 'groups_deleted_user_group' ), 10, 2 );

		// force registration at checkout
		add_filter( 'option_woocommerce_enable_guest_checkout', array( __CLASS__, 'option_woocommerce_enable_guest_checkout' ) );
		add_filter( 'option_woocommerce_enable_signup_and_login_from_checkout', array( __CLASS__, 'option_woocommerce_enable_signup_and_login_from_checkout' ) );

		// subscriptions
		// >= 2.x

		// do_action( 'woocommerce_subscription_status_updated', $this, $new_status, $old_status );
		add_action( 'woocommerce_subscription_status_updated', array( __CLASS__, 'woocommerce_subscription_status_updated' ), 10, 3 );
		// do_action( 'woocommerce_subscription_trashed', $post_id );
		add_action( 'woocommerce_subscription_trashed', array( __CLASS__, 'woocommerce_subscription_trashed' ), 10, 1 );
		// do_action( 'woocommerce_subscriptions_switched_item', $subscription, $new_order_item, WC_Subscriptions_Order::get_item_by_id( $new_order_item['switched_subscription_item_id'] ) );
		add_action( 'woocommerce_subscriptions_switched_item', array( __CLASS__, 'woocommerce_subscriptions_switched_item' ), 10, 3 );

		add_action( 'woocommerce_scheduled_subscription_end_of_prepaid_term', array( __CLASS__, 'woocommerce_scheduled_subscription_end_of_prepaid_term' ), 10, 1 );

		add_action( 'init', array( __CLASS__, 'wp_init' ) );

	}

	/**
	 * Old action handlers (moved out on init to be able to check for class presence).
	 */
	public static function wp_init() {

		// subscriptions < 2.x
		if ( class_exists( 'WC_Subscriptions_Product' ) && !class_exists( 'WCS_Action_Deprecator' ) ) {

			$options = get_option( 'groups-woocommerce', array() );
			$remove_on_hold = isset( $options[GROUPS_WS_REMOVE_ON_HOLD] ) ? $options[GROUPS_WS_REMOVE_ON_HOLD] : GROUPS_WS_DEFAULT_REMOVE_ON_HOLD;

			// do_action( 'activated_subscription', $user_id, $subscription_key );
			add_action( 'activated_subscription', array( __CLASS__, 'activated_subscription' ), 10, 2 );
			// do_action( 'cancelled_subscription', $user_id, $subscription_key );
			add_action( 'cancelled_subscription', array( __CLASS__, 'cancelled_subscription' ), 10, 2 );
			// do_action( 'subscription_end_of_prepaid_term', $user_id, $subscription_key );
			add_action( 'subscription_end_of_prepaid_term', array( __CLASS__, 'subscription_end_of_prepaid_term' ), 10, 2 );
			// do_action( 'subscription_expired', $user_id, $subscription_key );
			add_action( 'subscription_expired', array( __CLASS__, 'subscription_expired' ), 10, 2 );
			if ( $remove_on_hold ) {
				// do_action( 'subscription_put_on-hold', $user_id, $subscription_key );
				add_action( 'subscription_put_on-hold', array( __CLASS__, 'subscription_put_on_hold' ), 10, 2 );
				// do_action( 'reactivated_subscription', $user_id, $subscription_key );
				add_action( 'reactivated_subscription', array( __CLASS__, 'reactivated_subscription' ), 10, 2 );
			}
			// do_action( 'subscription_trashed', $user_id, $subscription_key );
			add_action( 'subscription_trashed', array( __CLASS__, 'subscription_trashed' ), 10, 2 );
			// do_action( 'switched_subscription', $original_order->customer_user, $original_subscription_key, $new_subscriptions_key );
			add_action( 'switched_subscription', array( __CLASS__, 'switched_subscription' ), 10, 3 );
		}
	}

	/**
	 * Cancel group memberships for the order.
	 * @param int $order_id
	 */
	public static function order_status_cancelled( $order_id ) {
		if ( $order = Groups_WS_Helper::get_order( $order_id ) ) {
			if ( $items = $order->get_items() ) {
				if ( $user_id = $order->get_user_id() ) { // not much we can do if there isn't
					foreach ( $items as $item ) {
						if ( $product = $item->get_product() ) {
							// Don't act on subscriptions here unless it's a refund.
							// Refunded subscription orders must be handled here as well
							// to assure that group membership is terminated immediately. 
							if ( $order->get_status() == 'refunded' || !class_exists( 'WC_Subscriptions_Product' ) || !WC_Subscriptions_Product::is_subscription( $product->get_id() ) ) {
								$groups_product_groups = get_user_meta( $user_id, '_groups_product_groups', true );
								if ( isset( $groups_product_groups[$order_id] ) &&
									 isset( $groups_product_groups[$order_id][$product->get_id()] ) &&
									 isset( $groups_product_groups[$order_id][$product->get_id()]['groups'] )
								) {
									foreach( $groups_product_groups[$order_id][$product->get_id()]['groups'] as $group_id ) {
										self::maybe_delete( $user_id, $group_id, $order_id );
									}
								}
							}
						}
					}
				}
			}
		}

		self::unregister_order( $order_id );
	}

	/**
	 * Creates group membership for the order.
	 * @param int $order_id
	 */
	public static function order_status_completed( $order_id ) {

		$unhandled = self::register_order( $order_id );

		if ( !$unhandled ) {
			if ( GROUPS_WS_LOG ) {
				error_log( sprintf( __METHOD__ . ' abandoned due to previously handled order ID %d', $order_id ) );
			}
			return;
		}

		if ( $order = Groups_WS_Helper::get_order( $order_id ) ) {
			if ( $items = $order->get_items() ) {
				if ( $user_id = $order->get_user_id() ) { // not much we can do if there isn't
					foreach ( $items as $item ) {
						if ( $product = $item->get_product() ) {
							// add to groups
							$product_groups = get_post_meta( $product->get_id(), '_groups_groups', false );
							if ( $product->get_type() == 'variation' ) {
								$parent_id      = $product->get_parent_id();
								$product_groups = get_post_meta( $parent_id, '_groups_groups', false );
								$variation_id = $product->get_id();
								if ( !empty( $variation_id ) ) {
									if ( $variation_product_groups = get_post_meta( $variation_id, '_groups_variation_groups', false ) ) {
										$product_groups = array_merge( $product_groups, $variation_product_groups );
									}
								}
							}
							if ( $product_groups ) {
								// don't act on subscriptions here
								if ( !class_exists( 'WC_Subscriptions_Product' ) || !WC_Subscriptions_Product::is_subscription( $product->get_id() ) ) {
									if ( count( $product_groups ) > 0 ) {
										// add the groups to the user by order and product so that if the product is changed later on,
										// the data is still valid for what has been purchased
										$groups_product_groups = get_user_meta( $user_id, '_groups_product_groups', true );
										if ( empty( $groups_product_groups ) ) {
											$groups_product_groups = array();
										}
										$start = time();
										$groups_product_groups[$order_id][$product->get_id()]['version'] = GROUPS_WS_VERSION;
										$groups_product_groups[$order_id][$product->get_id()]['start']   = $start;
										$groups_product_groups[$order_id][$product->get_id()]['groups']  = $product_groups;
										update_user_meta( $user_id, '_groups_product_groups', $groups_product_groups );
										global $groups_ws_product_with_duration;
										$groups_ws_product_with_duration = Groups_WS_Product::has_duration( $product );
										if ( $groups_ws_product_with_duration ) {
											if ( $product->get_type() == 'variation' ) {
												$groups_product_groups[$order_id][$product->get_id()]['duration'] = get_post_meta( $product->get_parent_id(), '_groups_duration', true );
												$groups_product_groups[$order_id][$product->get_id()]['duration_uom'] = get_post_meta( $product->get_parent_id(), '_groups_duration_uom', true );
											} else {
												$groups_product_groups[$order_id][$product->get_id()]['duration'] = get_post_meta( $product->get_id(), '_groups_duration', true );
												$groups_product_groups[$order_id][$product->get_id()]['duration_uom'] = get_post_meta( $product->get_id(), '_groups_duration_uom', true );
											}
											update_user_meta( $user_id, '_groups_product_groups', $groups_product_groups );
										}

										// add the user to the groups
										foreach( $product_groups as $group_id ) {
											Groups_User_Group::create(
												array(
													'user_id' => $user_id,
													'group_id' => $group_id
												)
											);
											if ( $groups_ws_product_with_duration ) {
												Groups_WS_Terminator::schedule_termination( $start + Groups_WS_Product::get_duration( $product ), $user_id, $group_id );
											} else {
												Groups_WS_Terminator::mark_as_eternal( $user_id, $group_id );
											}
										}

									}
								}
							}
							// remove from groups
							$product_groups_remove = get_post_meta( $product->get_id(), '_groups_groups_remove', false );
							if ( $product->get_type() == 'variation' ) {
								$parent_id             = $product->get_parent_id();
								$product_groups_remove = get_post_meta( $parent_id, '_groups_groups_remove', false );
								$variation_id          = $product->get_id();
								if ( !empty( $variation_id ) ) {
									if ( $variation_product_groups_remove = get_post_meta( $variation_id, '_groups_variation_groups_remove', false ) ) {
										$product_groups_remove = array_merge( $product_groups_remove, $variation_product_groups_remove );
									}
								}
							}
							if ( $product_groups_remove ) {
								if ( !class_exists( 'WC_Subscriptions_Product' ) || !WC_Subscriptions_Product::is_subscription( $product->get_id() ) ) {
									if ( count( $product_groups_remove )  > 0 ) {
										$groups_product_groups_remove = get_user_meta( $user_id, '_groups_product_groups_remove', true );
										if ( empty( $groups_product_groups_remove ) ) {
											$groups_product_groups_remove = array();
										}
										$groups_product_groups_remove[$order_id][$product->get_id()]['groups'] = $product_groups_remove;
										update_user_meta( $user_id, '_groups_product_groups_remove', $groups_product_groups_remove );
										// remove the user from the groups
										foreach( $product_groups_remove as $group_id ) {
											self::maybe_delete( $user_id, $group_id, $order_id );
										}
									}
								}
							}
						}
					}
				}
			}
		}
	}

	/**
	 * Revokes group memberships for the order.
	 * @param int $order_id
	 */
	public static function order_status_refunded( $order_id ) {
		self::order_status_cancelled( $order_id );
	}

	/**
	 * Proxy for cancel.
	 * @param int $order_id
	 */
	public static function order_status_failed( $order_id ) {
		self::order_status_cancelled( $order_id );
	}

	/**
	 * Proxy for cancel.
	 * @param int $order_id
	 */
	public static function order_status_on_hold( $order_id ) {
		self::order_status_cancelled( $order_id );
	}

	/**
	 * Proxy for cancel.
	 * @param int $order_id
	 */
	public static function order_status_pending( $order_id ) {
		self::order_status_cancelled( $order_id );
	}

	/**
	 * Proxy for cancel.
	 * @param int $order_id
	 */
	public static function order_status_processing( $order_id ) {
		self::order_status_cancelled( $order_id );
	}

	/**
	 * Hooked on user added to group.
	 * @param int $user_id
	 * @param int $group_id
	 */
	public static function groups_created_user_group( $user_id, $group_id ) {
		global $groups_ws_product_with_duration;
		if ( !isset( $groups_ws_product_with_duration ) || !$groups_ws_product_with_duration ) {
			Groups_WS_Terminator::mark_as_eternal( $user_id, $group_id );
		}
	}

	/**
	 * Hooked on user removed from group.
	 * @param int $user_id
	 * @param int $group_id
	 */
	public static function groups_deleted_user_group( $user_id, $group_id ) {
		Groups_WS_Terminator::lift_scheduled_terminations( $user_id, $group_id, false );
	}

	/**
	 * Marks order as handled only if not already marked.
	 * @param int $order_id
	 * @return boolean true if order wasn't handled yet and could be marked as handled, otherwise false
	 */
	public static function register_order( $order_id ) {
		$registered = false;
		if ( $order = Groups_WS_Helper::get_order( $order_id ) ) {
			$r = get_post_meta( $order->get_id(), '_groups_ws_registered', true );
			if ( empty( $r ) ) {
				$registered = update_post_meta( $order->get_id(), '_groups_ws_registered', true );
			}
		}
		return $registered;
	}

	/**
	 * Marks order as not handled.
	 * @param int $order_id
	 * @return boolean true if order could be marked as not handled, false on failure
	 */
	public static function unregister_order( $order_id ) {
		$unregistered = false;
		if ( $order = Groups_WS_Helper::get_order( $order_id ) ) {
			$r = get_post_meta( $order->get_id(), '_groups_ws_registered', true );
			if ( !empty( $r ) ) {
				$unregistered = delete_post_meta( $order->get_id(), '_groups_ws_registered' );
			}
		}
		return $unregistered;
	}

	/**
	 * Handle subscription status updates.
	 * Added for subscriptions 2.x compatibility.
	 * 
	 * @param WC_Subscription $subscription
	 * @param string $new_status
	 * @param string $old_status
	 */
	public static function woocommerce_subscription_status_updated( $subscription, $new_status, $old_status ) {

		switch( $new_status ) {

			case 'active' :
			case 'completed' :
				self::subscription_status_active( $subscription );
				// subscriptions >= 2.x can transition from pending to on-hold to active
				// so this doen't make sense anymore - left for reference
				//if ( $old_status != 'on-hold' ) {
				//	self::subscription_status_active( $subscription );
				//} else {
				//	self::subscription_status_reactivated( $subscription );
				//}
				break;

			case 'cancelled' :
				self::subscription_status_cancelled( $subscription );
				break;

			case 'pending' :
				self::subscription_status_pending( $subscription );
				break;

			case 'failed' :
			case 'on-hold' :
				self::subscription_status_on_hold( $subscription );
				break;

			case 'pending-cancel' :
				// nothing to do here, wait until cancelled
				break;

			case 'expired' :
				self::subscription_status_expired( $subscription );
				break;

			case 'switched' :
				self::subscription_status_switched( $subscription );
				break;
		}
	}

	/**
	 * Get the order ID for a subscription.
	 * 
	 * @param WC_Subscription $subscription
	 * @return int order id
	 */
	private static function get_subscription_order_id( $subscription ) {
		$order_id = null;
		if ( method_exists( $subscription, 'get_parent' ) ) {
			if ( $order = $subscription->get_parent() ) {
				$order_id = $order->get_id();
			}
		} else {
			if ( !empty( $subscription->order ) ) {
				$order_id = $subscription->order->id;
			} else {
				$order_id = $subscription->id;
			}
		}
		return $order_id;
	}

	/**
	 * Get the user ID for a subscription.
	 * 
	 * @param WC_Subscription $subscription
	 * @return int user ID
	 */
	private static function get_subscription_user_id( $subscription ) {
		$user_id = null;
		if ( method_exists( $subscription, 'get_user_id' ) ) {
			$user_id = $subscription->get_user_id();
		} else {
			$user_id  = $subscription->user_id;
		}
		return $user_id;
	}

	/**
	 * 2.x subscriptions activation handler.
	 * 
	 * @param WC_Subscription $subscription
	 * @since 1.9.0
	 */
	private static function subscription_status_active( $subscription ) {

		$order_id = self::get_subscription_order_id( $subscription );
		$user_id  = self::get_subscription_user_id( $subscription );

		// maybe unschedule pending expiration
		wp_clear_scheduled_hook(
			'groups_ws_subscription_expired',
			array(
				'user_id' => $user_id,
				'subscription_id' => $subscription->get_id()
			)
		);

		$items = $subscription->get_items();
		foreach( $items as $item ) {
			$product_id = $item['product_id'];
			// get the product from the subscription
			if ( $product = groups_ws_get_product( $product_id ) ) {
				if ( $product->exists() ) {
					// get the groups related to the product
					$product_groups = get_post_meta( $product_id, '_groups_groups', false );
					if ( isset( $item['variation_id'] ) ) {
						if ( $variation_product_groups = get_post_meta( $item['variation_id'], '_groups_variation_groups', false ) ) {
							$product_groups = array_merge( $product_groups, $variation_product_groups );
						}
					}
					if ( $product_groups ) {
						if ( count( $product_groups )  > 0 ) {
							// add the groups to the subscription (in case the product is changed later on, the subscription is still valid)
							$groups_product_groups = get_user_meta( $user_id, '_groups_product_groups', true );
							if ( empty( $groups_product_groups ) ) {
								$groups_product_groups = array();
							}
							$groups_product_groups[$order_id][$product_id]['version'] = GROUPS_WS_VERSION;
							$groups_product_groups[$order_id][$product_id]['start']  = time();
							$groups_product_groups[$order_id][$product_id]['groups']  = $product_groups;
							$groups_product_groups[$order_id][$product_id]['subscription_id'] = $subscription->get_id();
							update_user_meta( $user_id, '_groups_product_groups', $groups_product_groups );
							// add the user to the groups
							foreach( $product_groups as $group_id ) {
								Groups_User_Group::create(
									array(
										'user_id' => $user_id,
										'group_id' => $group_id
									)
								);
							}
							Groups_WS_Terminator::mark_as_eternal( $user_id, $group_id );
						}
					}
					// remove from groups
					$product_groups_remove = get_post_meta( $product_id, '_groups_groups_remove', false );
					if ( isset( $item['variation_id'] ) ) {
						if ( $variation_product_groups_remove = get_post_meta( $item['variation_id'], '_groups_variation_groups_remove', false ) ) {
							$product_groups_remove = array_merge( $product_groups_remove, $variation_product_groups_remove );
						}
					}
					if ( $product_groups_remove ) {
						if ( count( $product_groups_remove )  > 0 ) {
							$groups_product_groups_remove = get_user_meta( $user_id, '_groups_product_groups_remove', true );
							if ( empty( $groups_product_groups_remove ) ) {
								$groups_product_groups_remove = array();
							}
							$groups_product_groups_remove[$order_id][$product_id]['groups'] = $product_groups_remove;
							update_user_meta( $user_id, '_groups_product_groups_remove', $groups_product_groups_remove );
							// remove the user from the groups
							foreach( $product_groups_remove as $group_id ) {
								self::maybe_delete( $user_id, $group_id, $order_id );
							}
						}
					}
				}
			}
		}

	}

	/**
	 * Reactivated (subscription status transition from on-hold to active).
	 * 
	 * Not used. See notes above for 'active' status handling.
	 *
	 * @param WC_Subscription $subscription
	 * @since 1.9.0
	 */
	private static function subscription_status_reactivated( $subscription ) {
		$order_id = self::get_subscription_order_id( $subscription );
		$user_id  = self::get_subscription_user_id( $subscription );
		$items = $subscription->get_items();
		if ( $order_id ) {
			foreach( $items as $item ) {
				$product_id = $item['product_id'];
				$groups_product_groups = get_user_meta( $user_id, '_groups_product_groups', true );
				if (
					isset( $groups_product_groups[$order_id] ) &&
					isset( $groups_product_groups[$order_id][$product_id] ) &&
					isset( $groups_product_groups[$order_id][$product_id]['groups'] )
				) {
					foreach( $groups_product_groups[$order_id][$product_id]['groups'] as $group_id ) {
						Groups_User_Group::create(
							array(
								'user_id' => $user_id,
								'group_id' => $group_id
							)
						);
					}
				}
			}
		} else {
			// get the groups directly from each item's product
			foreach( $items as $item ) {
				$product_id = $item['product_id'];
				// get the product from the subscription
				if ( $product = groups_ws_get_product( $product_id ) ) {
					if ( $product->exists() ) {
						// get the groups related to the product
						$product_groups = get_post_meta( $product_id, '_groups_groups', false );
						if ( isset( $item['variation_id'] ) ) {
							if ( $variation_product_groups = get_post_meta( $item['variation_id'], '_groups_variation_groups', false ) ) {
								$product_groups = array_merge( $product_groups, $variation_product_groups );
							}
						}
						if ( $product_groups ) {
							if ( count( $product_groups ) > 0 ) {
								foreach( $product_groups as $group_id ) {
									Groups_User_Group::create(
										array(
											'user_id' => $user_id,
											'group_id' => $group_id
										)
									);
								}
							}
						}
					}
				}
			}
		}
	}

	/**
	 * Remove the user from the subscription products' related groups.
	 *
	 * @param int $user_id
	 * @param WC_Subscription $subscription
	 * @since 1.9.0
	 */
	private static function subscription_status_cancelled( $subscription ) {
		$order_id = self::get_subscription_order_id( $subscription );
		$user_id  = self::get_subscription_user_id( $subscription );
		$items = $subscription->get_items();
		if ( $order_id ) {
			foreach( $items as $item ) {
				$product_id = $item['product_id'];
				$groups_product_groups = get_user_meta( $user_id, '_groups_product_groups', true );
				if (
					isset( $groups_product_groups[$order_id] ) &&
					isset( $groups_product_groups[$order_id][$product_id] ) &&
					isset( $groups_product_groups[$order_id][$product_id]['groups'] )
				) {
					foreach( $groups_product_groups[$order_id][$product_id]['groups'] as $group_id ) {
						self::maybe_delete( $user_id, $group_id, $order_id );
					}
				}
			}
		} else {
			// get the groups directly from each item's product
			foreach( $items as $item ) {
				$product_id = $item['product_id'];
				// get the product from the subscription
				if ( $product = groups_ws_get_product( $product_id ) ) {
					if ( $product->exists() ) {
						// get the groups related to the product
						$product_groups = get_post_meta( $product_id, '_groups_groups', false );
						if ( isset( $item['variation_id'] ) ) {
							if ( $variation_product_groups = get_post_meta( $item['variation_id'], '_groups_variation_groups', false ) ) {
								$product_groups = array_merge( $product_groups, $variation_product_groups );
							}
						}
						if ( $product_groups ) {
							if ( count( $product_groups ) > 0 ) {
								foreach( $product_groups as $group_id ) {
									self::maybe_delete( $user_id, $group_id, $order_id );
								}
							}
						}
					}
				}
			}
		}
	}

	/**
	 * Invokes the handler for cancelled.
	 * 
	 * @param WC_Subscription $subscription
	 * @uses Groups_WS_Handler::subscription_status_cancelled( $subscription )
	 * @since 1.9.0
	 */
	private static function subscription_status_expired( $subscription ) {
		self::subscription_status_cancelled( $subscription );
	}

	/**
	 * Invokes the handler for cancelled.
	 * 
	 * @param WC_Subscription $subscription
	 * @uses Groups_WS_Handler::subscription_status_cancelled( $subscription )
	 * @since 1.9.0
	 */
	private static function subscription_status_switched( $subscription ) {
		self::subscription_status_cancelled( $subscription );
	}

	/**
	 * Trashed subscriptions expire immediately.
	 * 
	 * @param int $post_id
	 * @since 1.9.0
	 */
	public static function woocommerce_subscription_trashed( $post_id ) {
		if ( $subscription = wcs_get_subscription( $post_id ) ) {
			self::subscription_status_expired( $subscription );
			$user_id = self::get_subscription_user_id( $subscription );
			// unschedule pending expiration if any
			wp_clear_scheduled_hook(
				'groups_ws_subscription_expired',
				array(
					'user_id' => $user_id,
					'subscription_id' => $subscription->get_id()
				)
			);
		}
	}

	/**
	 * Invokes the handler for on-hold subscription status.
	 * 
	 * @param WC_Subscription $subscription
	 * @uses Groups_WS_Handler::subscription_status_on_hold( $subscription )
	 * @since 1.9.0
	 */
	private static function subscription_status_pending( $subscription ) {
		self::subscription_status_on_hold( $subscription );
	}

	/**
	 * Handles subscriptions put on hold; does the same thing as for
	 * cancelled subscriptions; keep as separate implementations though.
	 * 
	 * @param WC_Subscription $subscription
	 * @since 1.9.0
	 */
	private static function subscription_status_on_hold( $subscription ) {
		$order_id = self::get_subscription_order_id( $subscription );
		$user_id  = self::get_subscription_user_id( $subscription );
		$items = $subscription->get_items();
		foreach( $items as $item ) {
			$product_id = $item['product_id'];
			$groups_product_groups = get_user_meta( $user_id, '_groups_product_groups', true );
			if (
				isset( $groups_product_groups[$order_id] ) &&
				isset( $groups_product_groups[$order_id][$product_id] ) &&
				isset( $groups_product_groups[$order_id][$product_id]['groups'] )
			) {
				foreach( $groups_product_groups[$order_id][$product_id]['groups'] as $group_id ) {
					self::maybe_delete( $user_id, $group_id, $order_id );
				}
			}
		}
	}

	/**
	 * Handle group assignment : assign the user to the groups related to the subscription's product.
	 * @param int $user_id
	 * @param string $subscription_key
	 */
	public static function activated_subscription( $user_id, $subscription_key ) {
		$subscription = self::get_subscription_by_subscription_key( $subscription_key );
		if ( isset( $subscription['product_id'] ) && isset( $subscription['order_id'] ) ) {
			$product_id = $subscription['product_id'];
			$order_id = $subscription['order_id'];
			// Leasving this here for reference, it can be assumed that normally,
			// if the product's groups are modified, a reactivation should take its
			// data from the current product, not from its previous state.
			// See if the subscription was activated before and try to get subscription's groups.
			// If there are any, use these instead of those from the product.
			// This is necessary when a subscription has been cancelled and re-activated and the
			// original product groups were modified since and we do NOT want to make group
			// assignments based on the current state of the product.
			$done = false;
			//$groups_product_groups = get_user_meta( $user_id, '_groups_product_groups', true );
			//if ( isset( $groups_product_groups[$order_id] ) && isset( $groups_product_groups[$order_id][$product_id] ) &&
			//	 isset( $groups_product_groups[$order_id][$product_id]['groups'] ) &&
			//	 isset( $groups_product_groups[$order_id][$product_id]['subscription_key'] ) &&
			//	( $groups_product_groups[$order_id][$product_id]['subscription_key'] === $subscription_key )
			//) {
			//	foreach( $groups_product_groups[$order_id][$product_id]['groups'] as $group_id ) {
			//		Groups_User_Group::create( $user_id, $group_id );
			//	}
			//	$done = true;
			//}

			// maybe unschedule pending expiration
			wp_clear_scheduled_hook(
				'groups_ws_subscription_expired',
				array(
					'user_id' => $user_id,
					'subscription_key' => $subscription_key
				)
			);

			if ( !$done ) {
				// get the product from the subscription
				if ( $product = groups_ws_get_product( $product_id ) ) {
					if ( $product->exists() ) {
						// get the groups related to the product
						$product_groups = get_post_meta( $product_id, '_groups_groups', false );
						if ( isset( $subscription['variation_id'] ) ) {
							if ( $variation_product_groups = get_post_meta( $subscription['variation_id'], '_groups_variation_groups', false ) ) {
								$product_groups = array_merge( $product_groups, $variation_product_groups );
							}
						}
						if ( $product_groups ) {
							if ( count( $product_groups )  > 0 ) {
								// add the groups to the subscription (in case the product is changed later on, the subscription is still valid)
								$groups_product_groups = get_user_meta( $user_id, '_groups_product_groups', true );
								if ( empty( $groups_product_groups ) ) {
									$groups_product_groups = array();
								}
								$groups_product_groups[$order_id][$product_id]['version'] = GROUPS_WS_VERSION;
								$groups_product_groups[$order_id][$product_id]['start']  = time();
								$groups_product_groups[$order_id][$product_id]['groups']  = $product_groups;
								$groups_product_groups[$order_id][$product_id]['subscription_key'] = $subscription_key;
								update_user_meta( $user_id, '_groups_product_groups', $groups_product_groups );
								// add the user to the groups
								foreach( $product_groups as $group_id ) {
									Groups_User_Group::create(
										array(
											'user_id' => $user_id,
											'group_id' => $group_id
										)
									);
								}
								Groups_WS_Terminator::mark_as_eternal( $user_id, $group_id );
							}
						}
						// remove from groups
						$product_groups_remove = get_post_meta( $product_id, '_groups_groups_remove', false );
						if ( isset( $subscription['variation_id'] ) ) {
							if ( $variation_product_groups_remove = get_post_meta( $subscription['variation_id'], '_groups_variation_groups_remove', false ) ) {
								$product_groups_remove = array_merge( $product_groups_remove, $variation_product_groups_remove );
							}
						}
						if ( $product_groups_remove ) {
							if ( count( $product_groups_remove )  > 0 ) {
								$groups_product_groups_remove = get_user_meta( $user_id, '_groups_product_groups_remove', true );
								if ( empty( $groups_product_groups_remove ) ) {
									$groups_product_groups_remove = array();
								}
								$groups_product_groups_remove[$order_id][$product_id]['groups'] = $product_groups_remove;
								update_user_meta( $user_id, '_groups_product_groups_remove', $groups_product_groups_remove );
								// remove the user from the groups
								foreach( $product_groups_remove as $group_id ) {
									self::maybe_delete( $user_id, $group_id, $order_id );
								}
							}
						}
					}
				}
			}
		}
	}

	/**
	 * Add to groups after a subscription on hold has been reactivated.
	 * 
	 * This must NOT replicate the full action taken when a subscription is
	 * activated initially but reinstate group membership that was previously
	 * revoked.
	 * 
	 * @param int $user_id
	 * @param string $subscription_key
	 */
	public static function reactivated_subscription( $user_id, $subscription_key ) {
		$subscription = self::get_subscription_by_subscription_key( $subscription_key );
		if ( isset( $subscription['product_id'] ) && isset( $subscription['order_id'] ) ) {
			$product_id = $subscription['product_id'];
			$order_id = $subscription['order_id'];
			$groups_product_groups = get_user_meta( $user_id, '_groups_product_groups', true );
			if ( isset( $groups_product_groups[$order_id] ) &&
					isset( $groups_product_groups[$order_id][$product_id] ) &&
					isset( $groups_product_groups[$order_id][$product_id]['groups'] )
			) {
				foreach( $groups_product_groups[$order_id][$product_id]['groups'] as $group_id ) {
					Groups_User_Group::create(
						array(
							'user_id' => $user_id,
							'group_id' => $group_id
						)
					);
				}
			}
		}
	}

	/**
	 * Remove the user from the subscription product's related groups.
	 * 
	 * For cancelled subscriptions that should still allow group membership
	 * until the end of the related subscription's end of term,
	 * Groups_WS_Handler::subscription_end_of_prepaid_term() is used instead.
	 * 
	 * This will only act when the related order is refunded or cancelled.
	 * 
	 * @see Groups_WS_Handler::subscription_end_of_prepaid_term()
	 * @param int $user_id
	 * @param string $subscription_key
	 */
	public static function cancelled_subscription( $user_id, $subscription_key ) {
		$subscription = self::get_subscription_by_subscription_key( $subscription_key );
		if ( isset( $subscription['product_id'] ) && isset( $subscription['order_id'] ) ) {
			if ( $order = Groups_WS_Helper::get_order( $subscription['order_id'] ) ) {
				switch( $order->get_status() ) {
					case 'cancelled' :
					case 'refunded' :
						self::subscription_expired( $user_id, $subscription_key );
						break;
				}
			}
		}
	}

	/**
	 * Handle switched subscription (Subscriptions 2.x)
	 * 
	 * @param WC_Subscription $subscription
	 * @param unknown $new_order_item
	 * @param unknown $switched_order_item
	 */
	public static function woocommerce_subscriptions_switched_item( $subscription, $new_order_item, $switched_order_item ) {
		$new_subscription_key = wcs_get_old_subscription_key( $subscription );
		if ( isset( $switched_order_item['product_id'] ) && isset( $switched_order_item['order_id'] ) ) {
			$order_id = $switched_order_item['order_id'];  // the subscription id
			$product_id = $switched_order_item['product_id'];
			$user_id = $subscription->get_user_id();
			if ( $switched_subscription = wcs_get_subscription( $order_id ) ) {
				self::subscription_status_cancelled( $switched_subscription );
			}
		}
		self::subscription_status_active( $subscription );
	}

	/**
	 * Handle a switched subscription.
	 * 
	 * This action is not invoked anymore from Subscriptions 2.x.
	 * 
	 * @param int $user_id customer's user ID
	 * @param string $subscription_key switched subscription's key
	 * @param string $new_subscription_key subscription key of the new subscription
	 */
	public static function switched_subscription( $user_id, $subscription_key, $new_subscription_key ) {
		$subscription = self::get_subscription_by_subscription_key( $subscription_key );
		if ( isset( $subscription['product_id'] ) && isset( $subscription['order_id'] ) ) {
			$product_id            = $subscription['product_id'];
			$order_id              = $subscription['order_id'];
			$groups_product_groups = get_user_meta( $user_id, '_groups_product_groups', true );
			if (
				isset( $groups_product_groups[$order_id] ) &&
				isset( $groups_product_groups[$order_id][$product_id] ) &&
				isset( $groups_product_groups[$order_id][$product_id]['groups'] )
			) {
				foreach( $groups_product_groups[$order_id][$product_id]['groups'] as $group_id ) {
					self::maybe_delete( $user_id, $group_id, $order_id );
				}
			}
		}
	}

	/**
	 * Handle switched subscriptions to remove the user from the subscription
	 * product's related groups when a subscription has been switched.
	 * 
	 * @access private
	 * @deprecated Previously used for switched subscriptions, currently not used.
	 * 
	 * @param string $subscription_key
	 * @param array $new_subscription_details
	 */
	public static function updated_users_subscription( $subscription_key, $new_subscription_details ) {
		$subscription = self::get_subscription_by_subscription_key( $subscription_key );
		if ( isset( $subscription['status'] ) && ( 'switched' == $subscription['status'] ) ) {
			if ( isset( $subscription['product_id'] ) && isset( $subscription['order_id'] ) ) {
				if ( $order = Groups_WS_Helper::get_order( $subscription['order_id'] ) ) {
					if ( ( $order->get_status() == 'processing' ) || ( $order->get_status() == 'completed' ) ) { // $order->status does not have the wc- prefix
						if ( $user_id = $order->get_user_id() ) {
							self::subscription_expired( $user_id, $subscription_key );
						}
					}
				}
			}
		}
	}

	/**
	 * Immediately remove the user from the subscription product's related groups.
	 * This is called when a cancelled subscription paid up period ends.
	 * The cancelled_subscription hook cannot be used because subscription is
	 * already cleared when the action is triggered and the
	 * get_next_payment_date() method will not return a payment date that
	 * we could use.
	 * @since 1.3.4
	 * @param int $user_id
	 * @param string $subscription_key
	 */
	public static function subscription_end_of_prepaid_term( $user_id, $subscription_key ) {
		self::subscription_expired( $user_id, $subscription_key );
	}

	/**
	 * Immediately remove the user from the subscription product's related groups.
	 * This is called when a cancelled subscription paid up period ends.
	 * The cancelled_subscription hook cannot be used because subscription is
	 * already cleared when the action is triggered and the
	 * get_next_payment_date() method will not return a payment date that
	 * we could use.
	 * 
	 * @param int $subscription_id
	 * @since 1.9.5
	 */
	public static function woocommerce_scheduled_subscription_end_of_prepaid_term( $subscription_id ) {
		if ( $subscription = wcs_get_subscription( $subscription_id ) ) {
			self::subscription_status_expired( $subscription );
		}
	}

	/**
	 * Trashed subscriptions expire immediately.
	 * @param int $user_id
	 * @param string $subscription_key
	 */
	public static function subscription_trashed( $user_id, $subscription_key ) {
		self::subscription_expired( $user_id, $subscription_key );
		// unschedule pending expiration if any
		wp_clear_scheduled_hook(
			'groups_ws_subscription_expired',
			array(
				'user_id' => $user_id,
				'subscription_key' => $subscription_key
			)
		);
	}

	/**
	 * Subscription on hold => remove users from groups.
	 * 
	 * The semantics are different than those of an expired subscription,
	 * do NOT make use of Groups_WS_Handler::subscription_expired(), even
	 * though the current implementation is exactly the same, it would most
	 * probably not be appropriate if the implementation of that method was
	 * changed.
	 * 
	 * @param int $user_id
	 * @param string $subscription_key
	 */
	public static function subscription_put_on_hold( $user_id, $subscription_key ) {
		$subscription = self::get_subscription_by_subscription_key( $subscription_key );
		if ( isset( $subscription['product_id'] ) && isset( $subscription['order_id'] ) ) {
			$product_id = $subscription['product_id'];
			$order_id = $subscription['order_id'];
			$groups_product_groups = get_user_meta( $user_id, '_groups_product_groups', true );
			if ( isset( $groups_product_groups[$order_id] ) &&
				 isset( $groups_product_groups[$order_id][$product_id] ) &&
				 isset( $groups_product_groups[$order_id][$product_id]['groups'] )
			) {
				foreach( $groups_product_groups[$order_id][$product_id]['groups'] as $group_id ) {
					self::maybe_delete( $user_id, $group_id, $order_id );
				}
			}
		}
	}

	/**
	 * Same as when a subscription is cancelled.
	 * @param int $user_id
	 * @param string $subscription_key
	 */
	public static function subscription_expired( $user_id, $subscription_key ) {
		$subscription = self::get_subscription_by_subscription_key( $subscription_key );
		if ( isset( $subscription['product_id'] ) && isset( $subscription['order_id'] ) ) {
			$product_id = $subscription['product_id'];
			$order_id = $subscription['order_id'];
			$groups_product_groups = get_user_meta( $user_id, '_groups_product_groups', true );
			if ( isset( $groups_product_groups[$order_id] ) &&
				 isset( $groups_product_groups[$order_id][$product_id] ) &&
				 isset( $groups_product_groups[$order_id][$product_id]['groups'] )
			) {
				foreach( $groups_product_groups[$order_id][$product_id]['groups'] as $group_id ) {
					self::maybe_delete( $user_id, $group_id, $order_id );
				}
			}
		}
	}

	/**
	 * Obtain subscription by subscriptions < 2.x subscription key without
	 * use of deprecated methods when using subscriptions >= 2.x
	 * 
	 * @param string $subscription_key
	 * @return array subscription
	 */
	private static function get_subscription_by_subscription_key( $subscription_key ) {
		$subscription = array();
		if (
			function_exists( 'wcs_get_subscription_from_key' ) &&
			function_exists( 'wcs_get_subscription_in_deprecated_structure' )
		) {
			try {
				$subscription_id = wcs_get_subscription_id_from_key( $subscription_key );
				if ( null !== $subscription_id && is_numeric( $subscription_id ) ) {
					if ( $subscription = wcs_get_subscription_from_key( $subscription_key ) ) {
						$subscription = wcs_get_subscription_in_deprecated_structure( $subscription );
					}
				}
			} catch ( Exception $e ) {
				$subscription = array();
			}
		} else {
			$subscription = WC_Subscriptions_Manager::get_subscription( $subscription_key );
		}
		return $subscription;
	}

	/**
	 * Force registration on checkout when a subscription is
	 * in the cart or when a product with groups assigned is in the cart.
	 * 
	 * @param mixed $value
	 * @return mixed
	 */
	public static function option_woocommerce_enable_guest_checkout( $value ) {
		$options = get_option( 'groups-woocommerce', null );
		$force_registration = isset( $options[GROUPS_WS_FORCE_REGISTRATION] ) ? $options[GROUPS_WS_FORCE_REGISTRATION] : GROUPS_WS_DEFAULT_FORCE_REGISTRATION;
		if ( $force_registration ) {
			if ( self::has_groups_product_in_cart() ) {
				$value = 'no';
			}
		}
		return $value;
	}

	/**
	 * Enable login form on checkout when a subscription is
	 * in the cart or when a product with groups assigned is in the cart.
	 * 
	 * @param unknown_type $value
	 */
	public static function option_woocommerce_enable_signup_and_login_from_checkout( $value ) {
		$options = get_option( 'groups-woocommerce', null );
		$force_registration = isset( $options[GROUPS_WS_FORCE_REGISTRATION] ) ? $options[GROUPS_WS_FORCE_REGISTRATION] : GROUPS_WS_DEFAULT_FORCE_REGISTRATION;
		if ( $force_registration ) {
			if ( self::has_groups_product_in_cart() ) {
				$value = 'yes';
			}
		}
		return $value;
	}

	/**
	 * Returns true if a product with groups assigned is in the cart, false otherwise.
	 * 
	 * @return boolean whether a product with groups assigned is in the cart
	 */
	public static function has_groups_product_in_cart() {
		global $woocommerce;
		$result = false;
		if ( isset( $woocommerce ) && isset( $woocommerce->cart ) && did_action( 'wp_loaded' ) ) {
			foreach ( $woocommerce->cart->get_cart() as $cart_item_key => $values ) {
				$product_groups        = get_post_meta( $values['product_id'], '_groups_groups', false );
				$product_groups_remove = get_post_meta( $values['product_id'], '_groups_groups_remove', false );
				if ( isset( $values['variation_id'] ) ) {
					if ( $variation_product_groups = get_post_meta( $values['variation_id'], '_groups_variation_groups', false ) ) {
						$product_groups = array_merge( $product_groups, $variation_product_groups );
					}
					if ( $variation_product_groups_remove = get_post_meta( $values['variation_id'], '_groups_variation_groups_remove', false ) ) {
						$product_groups_remove = array_merge( $product_groups_remove, $variation_product_groups_remove );
					}
				}
				if ( ( count( $product_groups ) > 0 ) || count( $product_groups_remove ) > 0 ) {
					$result = true;
					break;
				}
			}
		}
		return $result;
	}

	/**
	 * Returns an array of order IDs for valid orders related to the user.
	 * Valid orders or those that are completed (and processing if the option is set).
	 * 
	 * @param int $user_id
	 * @return array of int, order IDs
	 */
	public static function get_user_valid_order_ids( $user_id ) {
		$order_ids = array();
		if ( !empty( $user_id ) ) {
			$statuses = array( 'completed' );
			$options = get_option( 'groups-woocommerce', array() );
			$order_status = isset( $options[GROUPS_WS_MEMBERSHIP_ORDER_STATUS] ) ? $options[GROUPS_WS_MEMBERSHIP_ORDER_STATUS] : GROUPS_WS_DEFAULT_MEMBERSHIP_ORDER_STATUS;
			if ( $order_status == 'processing' ) {
				$statuses[] = 'processing';
			}
			if ( groups_ws_is_wc22() ) {
				$wc_statuses = groups_ws_order_status( $statuses );
				$order_ids = get_posts( array(
					'fields'      => 'ids',
					'numberposts' => -1,
					'meta_key'    => '_customer_user',
					'meta_value'  => $user_id,
					'post_type'   => 'shop_order',
					'post_status' => $wc_statuses
				) );
			} else {
				$order_ids = get_posts( array(
					'fields'      => 'ids',
					'numberposts' => -1,
					'meta_key'    => '_customer_user',
					'meta_value'  => $user_id,
					'post_type'   => 'shop_order',
					'post_status' => 'publish',
					'tax_query'   => array( array(
						'taxonomy' => 'shop_order_status',
						'field'    => 'slug',
						'terms'    => $statuses
					) )
				) );
			}
		}
		return $order_ids;
	}

	/**
	 * Returns an array of order IDs for valid orders that grant group
	 * membership for the given group to the user related to the order.
	 * 
	 * Currently not used.
	 * 
	 * @deprecated DO NOT USE unless adapated to use $base_statuses as in get_valid_order_ids_granting_group_membership_from_order_items(...)
	 * 
	 * @param int $user_id
	 * @param int $group_id
	 * @return array of int, order IDs
	 */
	public static function get_valid_order_ids_granting_group_membership_from_product_groups( $user_id, $group_id ) {
		$order_ids = array();
		if ( !empty( $user_id ) ) {
			$statuses = array( 'completed' );
			$options = get_option( 'groups-woocommerce', array() );
			$order_status = isset( $options[GROUPS_WS_MEMBERSHIP_ORDER_STATUS] ) ? $options[GROUPS_WS_MEMBERSHIP_ORDER_STATUS] : GROUPS_WS_DEFAULT_MEMBERSHIP_ORDER_STATUS;
			if ( $order_status == 'processing' ) {
				$statuses[] = 'processing';
			}
			// $statuses = groups_ws_order_status( $statuses ); NO, $order->status returns the status with the wc- prefix
			$groups_product_groups = get_user_meta( $user_id, '_groups_product_groups', true );
			if ( empty( $groups_product_groups ) ) {
				$groups_product_groups = array();
			}
			foreach( $groups_product_groups as $order_id => $product_ids ) {
				if ( $order = Groups_WS_Helper::get_order( $order_id ) ) {
					if ( in_array( $order->get_status(), $statuses ) ) {
						// this is a completed/processing order so we must consider group assignments
						foreach( $product_ids as $product_id => $group_ids ) {
							if ( in_array( $group_id, $group_ids ) ) {
								$order_ids[] = $order_id;
							}
						}
					}
				}
			}
		}
		return $order_ids;
	}

	/**
	 * Returns an array of order IDs for valid orders that grant group
	 * membership for the given group to the user related to the order.
	 * 
	 * @param int $user_id
	 * @param int $group_id
	 * @return array of int, order IDs
	 */
	public static function get_valid_order_ids_granting_group_membership_from_order_items( $user_id, $group_id ) {
		$order_ids = array();
		if ( !empty( $user_id ) ) {
			$base_statuses = array( 'processing', 'completed' );
			$statuses      = array( 'completed' );
			$options       = get_option( 'groups-woocommerce', array() );
			$order_status  = isset( $options[GROUPS_WS_MEMBERSHIP_ORDER_STATUS] ) ? $options[GROUPS_WS_MEMBERSHIP_ORDER_STATUS] : GROUPS_WS_DEFAULT_MEMBERSHIP_ORDER_STATUS;
			if ( $order_status == 'processing' ) {
				$statuses[] = 'processing';
			}
			// DO NOT use groups_ws_order_status( $statuses ) for $statuses or $base_statuses here,
			// $order->status doesn't provide the wc- prefix.

			$groups_product_groups = get_user_meta( $user_id, '_groups_product_groups', true );
			if ( empty( $groups_product_groups ) ) {
				$groups_product_groups = array();
			}

			foreach( $groups_product_groups as $order_id => $product_ids ) {
				if ( $order = Groups_WS_Helper::get_order( $order_id ) ) {
					// If this is a completed/processing order, consider group assignments.
					// We check the order status for non-subscription products below,
					// for subscriptions the subscription status is checked.
					if ( in_array( $order->get_status(), $base_statuses ) ) {
						// Note that for orders placed with versions up to 1.4.1, the following won't give the results we might expect if the product group-related information has changed since the order was placed.
						// As we don't store that information (WC doesn't store the whole lot of the product when purchased, nor does GW) checking the duration based on the product is the best effort at
						// finding out about the group membership duration we can make.
						// Use the order items (only existing order items are taken into account).
						if ( $items = $order->get_items() ) {
							foreach ( $items as $item ) {
								if ( $product = $item->get_product() ) {
									// Use the groups that were stored for the product when it was ordered,
									// this avoids hickups when the product's groups were changed since.
									if ( isset( $product_ids[$product->get_id()] ) && isset( $product_ids[$product->get_id()]['groups'] ) ) {
										$product_groups = $product_ids[$product->get_id()]['groups'];
										if ( in_array( $group_id, $product_groups ) ) {
											// non-subscriptions
											if ( !class_exists( 'WC_Subscriptions_Product' ) || !WC_Subscriptions_Product::is_subscription( $product->get_id() ) ) {
												if ( in_array( $order->get_status(), $statuses ) ) {
													if ( isset( $product_ids[$product->get_id()] ) &&
														 isset( $product_ids[$product->get_id()]['version'] ) // as of 1.5.0
													) {
														$has_duration =
															isset( $product_ids[$product->get_id()]['duration'] ) &&
															$product_ids[$product->get_id()]['duration'] &&
															isset( $product_ids[$product->get_id()]['duration_uom'] );
													} else {
														$has_duration = Groups_WS_Product::has_duration( $product );
													}
													// unlimited membership
													if ( !$has_duration ) {
														if ( !in_array( $order_id, $order_ids ) ) {
															$order_ids[] = $order_id;
														}
													} else {
														if ( isset( $product_ids[$product->get_id()] ) &&
														 	 isset( $product_ids[$product->get_id()]['version'] ) // as of 1.5.0
														) {
															$duration = Groups_WS_Product::calculate_duration(
																$product_ids[$product->get_id()]['duration'],
																$product_ids[$product->get_id()]['duration_uom']
															);
														} else { // <= 1.4.1
															$duration = Groups_WS_Product::get_duration( $product );
														}
														// time-limited membership
														if ( $duration ) {
															if ( ( $order_date = $order->get_date_paid() ) === null ) {
																$order_date = $order->get_date_created();
															}
															if ( $order_date !== null ) {
																$order_date = gmdate( 'Y-m-d H:i:s', $order_date->getOffsetTimestamp() );
																$start_date = $order_date;
																$end = strtotime( $start_date ) + $duration;
																if ( time() < $end ) {
																	if ( !in_array( $order_id, $order_ids ) ) {
																		$order_ids[] = $order_id;
																	}
																}
															}
														}
													}
												}

											} else {

												// include active subscriptions ( subscriptions >= 2.x )
												if ( function_exists( 'wcs_get_subscriptions_for_order' ) ) {
													if ( $subscriptions = wcs_get_subscriptions_for_order( $order_id ) ) {
														if ( is_array( $subscriptions ) ) {
															foreach( $subscriptions as $subscription ) {
																if ( $subscription->has_product( $product->get_id() ) ) {
																	$valid = false;
																	if ( $subscription->get_status() == 'active' ) {
																		$valid = true;
																	} else if ( $subscription->get_status() == 'cancelled' ) {
																		$hook_args = array( 'subscription_id' => $subscription->get_id() );
																		$end_timestamp = wp_next_scheduled( 'scheduled_subscription_end_of_prepaid_term', $hook_args );
																		if ( ( $end_timestamp !== false ) && ( $end_timestamp > time() ) ) {
																			$valid = true;
																		}
																	}
																	if ( $valid ) {
																		if ( !in_array( $order_id, $order_ids ) ) {
																			$order_ids[] = $order_id;
																			break;
																		}
																	}
																}
															}
														}
													}
												} else {
													$subscription_key = WC_Subscriptions_Manager::get_subscription_key( $order_id, $product->get_id() );
													$subscription = WC_Subscriptions_Manager::get_subscription( $subscription_key );
													if ( isset( $subscription['status'] ) ) {
														$valid = false;
														if ( $subscription['status'] == 'active' ) {
															$valid = true;
														} else if ( $subscription['status'] == 'cancelled' ) {
															$hook_args = array( 'user_id' => ( int ) $user_id, 'subscription_key' => $subscription_key );
															$end_timestamp = wp_next_scheduled( 'scheduled_subscription_end_of_prepaid_term', $hook_args );
															if ( ( $end_timestamp !== false ) && ( $end_timestamp > time() ) ) {
																$valid = true;
															}
														}
														if ( $valid ) {
															if ( !in_array( $order_id, $order_ids ) ) {
																$order_ids[] = $order_id;
															}
														}
													}
												}
											}
										}
									}
								}
							}
						}

					}
				}
			}
		}
		return $order_ids;
	}

	/**
	 * Returns the IDs of all subscriptions that are active or pending cancellation and granting access to the group.
	 *
	 * @param int $user_id
	 * @param int $group_id
	 * @return array
	 */
	public static function get_valid_subscription_ids_granting_group_membership( $user_id, $group_id ) {
		$ids = array();
		if ( function_exists( 'wcs_get_subscriptions' ) ) {
			$subscriptions = wcs_get_subscriptions( array(
				'customer_id' => $user_id,
				'subscriptions_per_page' => -1,
				'subscription_status' => 'active'
			) );
			$subscriptions += wcs_get_subscriptions( array(
				'customer_id' => $user_id,
				'subscriptions_per_page' => -1,
				'subscription_status' => 'pending-cancel'
			) );
			if ( count( $subscriptions ) > 0 ) {
				foreach( $subscriptions as $post_id => $subscription ) {
					$items = $subscription->get_items();
					foreach( $items as $item ) {
						$product_id = $item['product_id'];
						// get the product from the subscription
						if ( $product = groups_ws_get_product( $product_id ) ) {
							if ( $product->exists() ) {
								// get the groups related to the product
								$product_groups = get_post_meta( $product_id, '_groups_groups', false );
								if ( isset( $item['variation_id'] ) ) {
									if ( $variation_product_groups = get_post_meta( $item['variation_id'], '_groups_variation_groups', false ) ) {
										$product_groups = array_merge( $product_groups, $variation_product_groups );
									}
								}
								if ( $product_groups ) {
									if ( count( $product_groups )  > 0 ) {
										if ( in_array( $group_id, $product_groups ) ) {
											if ( method_exists( $subscription, 'get_id' ) ) {
												$ids[] = $subscription->get_id();
											} else {
												$ids[] = $subscription->id;
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}
		return $ids;
	}

	/**
	 * Deletes the user from the group if no other orders than $order_id
	 * currently grant membership to that group.
	 * 
	 * @param int $user_id
	 * @param int $group_id
	 * @param int $order_id
	 */
	public static function maybe_delete( $user_id, $group_id, $order_id ) {
		$order_ids = self::get_valid_order_ids_granting_group_membership_from_order_items( $user_id, $group_id );
		$ids = array_diff( $order_ids, array( $order_id ) );
		// also, any active subscriptions granting access to the group => don't delete :
		$sub_ids = self::get_valid_subscription_ids_granting_group_membership( $user_id, $group_id );
		$n_subs = count( $sub_ids );
		if ( ( count( $ids ) == 0 ) && ( $n_subs == 0 ) ) {
			Groups_User_Group::delete( $user_id, $group_id );
			if ( GROUPS_WS_LOG ) {
				error_log( sprintf( __METHOD__ . ' deleted membership for user ID %d with group ID %d', $user_id, $group_id ) );
			}
		} else {
			if ( GROUPS_WS_LOG ) {
				if ( count( $ids ) != 0 ) {
					error_log( sprintf(
						__METHOD__ . ' membership for user ID %d with group ID %d has not been deleted due to other orders granting membership, order IDs: %s',
						$user_id,
						$group_id,
						implode( ',', $order_ids )
					) );
				} else if ( $n_subs > 0 ) {
					error_log( sprintf(
						__METHOD__ . ' membership for user ID %d with group ID %d has not been deleted due to valid subscriptions granting membership, subscription IDs: %s',
						$user_id,
						$group_id,
						implode( ',', $sub_ids )
					) );
				}
			}
		}
	}
}
Groups_WS_Handler::init();
