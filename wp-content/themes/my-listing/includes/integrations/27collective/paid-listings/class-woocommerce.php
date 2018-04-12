<?php
/**
 * WooCommerce Integrations
 *
 * @since 1.0.0
 */

namespace CASE27\Integrations\Paid_Listings;

/**
 * WooCommerce Paid Integrations.
 *
 * @since 1.0.0
 */
class WooCommerce {

	/**
	 * Use singleton instance.
	 */
	use \CASE27\Traits\Instantiatable;

	/**
	 * Constructor.
	 *
	 * @version 1.0.0
	 */
	public function __construct() {

		/* = PRODUCT = */

		// Add custom product type.
		add_filter( 'product_type_selector', array( $this, 'add_product_type' ) );

		// Product Class.
		add_filter( 'woocommerce_product_class' , array( $this, 'set_product_class' ), 10, 3 );

		// HTML.
		add_action( 'woocommerce_product_options_general_product_data', array( $this, 'product_data_html' ) );

		// Save Product Data.
		add_filter( 'woocommerce_process_product_meta_job_package', array( $this, 'save_product_data' ) );
		add_filter( 'woocommerce_process_product_meta_job_package_subscription', array( $this, 'save_product_data' ) );

		/* = CART = */

		// Use simple add to cart.
		add_action( 'woocommerce_job_package_add_to_cart', 'woocommerce_simple_add_to_cart', 30 );

		// Get cart item from session.
		add_filter( 'woocommerce_get_cart_item_from_session', array( $this, 'get_cart_item_from_session' ), 10, 2 );

		// Save listing on checkout.
		add_action( 'woocommerce_checkout_create_order_line_item', array( $this, 'checkout_create_order_line_item' ), 10, 4 );

		// Display listing in cart.
		add_filter( 'woocommerce_get_item_data', array( $this, 'get_listing_in_cart' ), 10, 2 );

		// Disable guest checkout when purchasing listing and enable checkout signup.
		add_filter( 'option_woocommerce_enable_signup_and_login_from_checkout', array( $this, 'enable_signup_and_login_from_checkout' ) );
		add_filter( 'option_woocommerce_enable_guest_checkout', array( $this, 'enable_guest_checkout' ) );

		/* = ORDER = */

		// Thank you page.
		add_action( 'woocommerce_thankyou', array( $this, 'woocommerce_thankyou' ), 5 );

		// Process order.
		add_action( 'woocommerce_order_status_processing', array( $this, 'order_paid' ) );
		add_action( 'woocommerce_order_status_completed', array( $this, 'order_paid' ) );
		add_action( 'woocommerce_order_status_cancelled', array( $this, 'order_cancelled' ) );
	}

	/**
	 * Add Product Type
	 *
	 * @since 1.0.0
	 *
	 * @param array $types Product types.
	 * @return array
	 */
	public function add_product_type( $types ) {
		$types['job_package'] = esc_html__( 'Listing Package', 'my-listing' );
		return $types;
	}

	/**
	 * Set Product Class to Load.
	 *
	 * @since 1.0.0
	 *
	 * @param string $classname Current classname found.
	 * @param string $product_type Current product type.
	 * @return string $classname
	 */
	public function set_product_class( $classname, $product_type ) {
		if ( 'job_package' === $product_type ) {
			return 'CASE27\Integrations\Paid_Listings\Product';
		}

		return $classname;
	}

	/**
	 * Product Data
	 *
	 * @since 1.0.0
	 */
	public function product_data_html() {
		global $post;
		$post_id = $post->ID;
		?>
		<div class="options_group show_if_job_package <?php echo esc_attr( class_exists( '\WC_Subscriptions' ) ? 'show_if_job_package_subscription' : '' );?>">

			<?php if ( class_exists( '\WC_Subscriptions' ) ) : ?>
				<?php woocommerce_wp_select( array(
					'id' => '_job_listing_package_subscription_type',
					'wrapper_class' => 'show_if_job_package_subscription',
					'label' => __( 'Subscription Type', 'my-listing' ),
					'description' => __( 'Choose how subscriptions affect this package', 'my-listing' ),
					'value' => get_post_meta( $post_id, '_package_subscription_type', true ),
					'desc_tip' => true,
					'options' => array(
						'package' => __( 'Link the subscription to the package (renew listing limit every subscription term)', 'my-listing' ),
						'listing' => __( 'Link the subscription to posted listings (renew posted listings every subscription term)', 'my-listing' ),
					),
				) ); ?>
			<?php endif; ?>

			<?php woocommerce_wp_text_input( array(
				'id'                => '_job_listing_limit',
				'label'             => __( 'Listing limit', 'my-listing' ),
				'description'       => __( 'The number of listings a user can post with this package.', 'my-listing' ),
				'value'             => ( $limit = get_post_meta( $post_id, '_job_listing_limit', true ) ) ? $limit : '',
				'placeholder'       => __( 'Unlimited', 'my-listing' ),
				'type'              => 'number',
				'desc_tip'          => true,
				'custom_attributes' => array(
					'min'   => '',
					'step' 	=> '1',
				),
			) ); ?>

			<?php woocommerce_wp_text_input( array(
				'id'                => '_job_listing_duration',
				'label'             => __( 'Listing duration', 'my-listing' ),
				'description'       => __( 'The number of days that the listing will be active.', 'my-listing' ),
				'value'             => get_post_meta( $post_id, '_job_listing_duration', true ),
				'placeholder'       => get_option( 'job_manager_submission_duration' ),
				'desc_tip'          => true,
				'type'              => 'number',
				'custom_attributes' => array(
					'min'   => '',
					'step' 	=> '1',
				),
			) ); ?>

			<?php /* @todo:featured */ ?>
			<div style="display:none !important;">
			<?php woocommerce_wp_checkbox( array(
				'id'                => '_job_listing_featured',
				'label'             => __( 'Feature Listings?', 'my-listing' ),
				'description'       => __( 'Feature this listing - it will be styled differently and sticky.', 'my-listing' ),
				'value'             => get_post_meta( $post_id, '_job_listing_featured', true ),
			) ); ?>
			</div>

			<?php woocommerce_wp_checkbox( array(
				'id'                => '_use_for_claims',
				'label'             => __( 'Use for Claim?', 'my-listing' ),
				'description'       => __( 'Allow this package to be an option for claiming a listing.', 'my-listing' ),
				'value'             => get_post_meta( $post_id, '_use_for_claims', true ),
			) ); ?>

			<script type="text/javascript">
				jQuery( function(){
					jQuery( '.pricing' ).addClass( 'show_if_job_package' );
					jQuery( '._tax_status_field' ).closest( 'div' ).addClass( 'show_if_job_package' );
					<?php if ( class_exists( '\WC_Subscriptions' ) ) : ?>
						jQuery('._tax_status_field').closest('div').addClass( 'show_if_job_package_subscription' );
						jQuery('.show_if_subscription, .options_group.pricing').addClass( 'show_if_job_package_subscription' );
						jQuery('#_job_listing_package_subscription_type').change(function(){
							if ( jQuery(this).val() === 'listing' ) {
								jQuery('#_job_listing_duration').closest('.form-field').hide().val('');
							} else {
								jQuery('#_job_listing_duration').closest('.form-field').show();
							}
						}).change();
					<?php endif; ?>
					jQuery( '#product-type' ).change();
				});
			</script>
		</div>
		<?php
	}

	/**
	 * Save Product Data
	 *
	 * @since 1.0.0
	 *
	 * @param int $post_id Product ID.
	 */
	public function save_product_data( $post_id ) {
		// Limit.
		if ( isset( $_POST['_job_listing_limit'] ) && $_POST['_job_listing_limit'] ) {
			update_post_meta( $post_id, '_job_listing_limit', absint( $_POST['_job_listing_limit'] ) );
		} else {
			delete_post_meta( $post_id, '_job_listing_limit' );
		}

		// Duration.
		if ( isset( $_POST['_job_listing_duration'] ) && $_POST['_job_listing_duration'] ) {
			update_post_meta( $post_id, '_job_listing_duration', absint( $_POST['_job_listing_duration'] ) );
		} else {
			delete_post_meta( $post_id, '_job_listing_duration' );
		}

		// Featured.
		if ( isset( $_POST['_job_listing_featured'] ) && $_POST['_job_listing_featured'] ) {
			update_post_meta( $post_id, '_job_listing_featured', 'yes' );
		} else {
			update_post_meta( $post_id, '_job_listing_featured', 'no' );
		}

		// Use for Claims.
		if ( isset( $_POST['_use_for_claims'] ) && $_POST['_use_for_claims'] ) {
			update_post_meta( $post_id, '_use_for_claims', 'yes' );
		} else {
			update_post_meta( $post_id, '_use_for_claims', 'no' );
		}

		// Subscription type.
		if ( isset( $_POST['_job_listing_package_subscription_type'] ) ) {
			$type = 'package' === $_POST['_job_listing_package_subscription_type'] ? 'package' : 'listing';
			update_post_meta( $post_id, '_package_subscription_type', $type );
		}
	}

	/**
	 * Get the data from the session on page load
	 *
	 * @since 1.0.0
	 *
	 * @param array $cart_item
	 * @param array $values
	 * @return array
	 */
	public function get_cart_item_from_session( $cart_item, $values ) {
		if ( ! empty( $values['job_id'] ) ) {
			$cart_item['job_id'] = $values['job_id'];
			$cart_item['is_claim'] = isset( $values['is_claim'] ) && $values['is_claim'] ? true : false;
		}
		return $cart_item;
	}

	/**
	 * Set the order line item's meta data prior to being saved.
	 *
	 * @since 1.0.0
	 *
	 * @param WC_Order_Item_Product $order_item
	 * @param string                $cart_item_key  The hash used to identify the item in the cart
	 * @param array                 $cart_item_data The cart item's data.
	 * @param WC_Order              $order          The order or subscription object to which the line item relates
	 */
	public function checkout_create_order_line_item( $order_item, $cart_item_key, $cart_item_data, $order ) {
		if ( isset( $cart_item_data['job_id'] ) ) {
			$order_item->update_meta_data( '_job_id', $cart_item_data['job_id'] );
			if ( isset( $cart_item_data['is_claim'] ) ) {
				$order_item->update_meta_data( '_is_claim', $cart_item_data['is_claim'] ? 1 : 0 );
			}
		}
	}

	/**
	 * Output job name in cart
	 *
	 * @since 1.0.0
	 *
	 * @param  array $data
	 * @param  array $cart_item
	 * @return array
	 */
	public function get_listing_in_cart( $data, $cart_item ) {
		if ( isset( $cart_item['job_id'] ) ) {
			$data[] = array(
				'name'  => isset( $cart_item['is_claim'] ) && $cart_item['is_claim'] ? esc_html__( 'Claim for', 'my-listings' ) : esc_html__( 'Listing', 'my-listings' ),
				'value' => get_the_title( absint( $cart_item['job_id'] ) ),
			);
		}
		return $data;
	}

	/**
	 * When cart contain listing product, always set to "yes".
	 *
	 * @since 1.0.0
	 *
	 * @param string $value
	 * @return string
	 */
	public function enable_signup_and_login_from_checkout( $value ) {
		global $woocommerce;
		$contain_listing = false;
		if ( ! empty( $woocommerce->cart->cart_contents ) ) {
			foreach ( $woocommerce->cart->cart_contents as $cart_item ) {
				$product = $cart_item['data'];
				if ( $product instanceof WC_Product && $product->is_type( array( 'job_package', 'job_package_subscription' ) ) ) {
					$contain_listing = true;
				}
			}
		}

		return $contain_listing ? 'yes' : $value;
	}

	/**
	 * When cart contain listing product, always set to "no".
	 *
	 * @param string $value
	 * @return string
	 */
	public function enable_guest_checkout( $value ) {
		global $woocommerce;
		$contain_listing = false;
		if ( ! empty( $woocommerce->cart->cart_contents ) ) {
			foreach ( $woocommerce->cart->cart_contents as $cart_item ) {
				$product = $cart_item['data'];
				if ( $product instanceof WC_Product && $product->is_type( array( 'job_package', 'job_package_subscription' ) ) ) {
					$contain_listing = true;
				}
			}
		}

		return $contain_listing ? 'no' : $value;
	}


	/**
	 * Thank you page after checkout completed.
	 *
	 * @since 1.0.0
	 *
	 * @param int $order_id
	 */
	public function woocommerce_thankyou( $order_id ) {
		global $wp_post_types;
		$order = wc_get_order( $order_id );
		$is_paid = in_array( $order->get_status(), array( 'completed', 'processing' ) );

		foreach ( $order->get_items() as $item ) {
			if ( isset( $item['job_id'] ) ) {
				$listing_status = get_post_status( $item['job_id'] );
				$is_claim = isset( $item['is_claim'] ) && $item['is_claim'];

				if ( $is_claim ) {
					if ( $is_paid ) {
						echo wpautop( sprintf( __( 'Your claim to %s has been submitted successfully.', 'my-listing' ), get_the_title( $item['job_id'] ) ) );
					} else {
						echo wpautop( sprintf( __( 'Your claim to %s will be processed after order completed.', 'my-listing' ), get_the_title( $item['job_id'] ) ) );
					}
				} else {
					switch ( get_post_status( $item['job_id'] ) ) {
						case 'pending' :
							echo wpautop( sprintf( __( '%s has been submitted successfully and will be visible once approved.', 'my-listing' ), get_the_title( $item['job_id'] ) ) );
						break;
						case 'pending_payment' :
						case 'expired' :
							echo wpautop( sprintf( __( '%s has been submitted successfully and will be visible once payment has been confirmed.', 'my-listing' ), get_the_title( $item['job_id'] ) ) );
						break;
						default :
							echo wpautop( sprintf( __( '%s has been submitted successfully.', 'my-listing' ), get_the_title( $item['job_id'] ) ) );
						break;
					}
				}
				?>

				<p class="job-manager-submitted-paid-listing-actions">
					<?php
					if ( 'publish' === get_post_status( $item['job_id'] ) ) {
						echo '<a class="button" href="' . get_permalink( $item['job_id'] ) . '">' . __( 'View Listing', 'my-listing' ) . '</a> ';
					} elseif ( get_option( 'job_manager_job_dashboard_page_id' ) ) {
						echo '<a class="button" href="' . get_permalink( get_option( 'job_manager_job_dashboard_page_id' ) ) . '">' . __( 'View Dashboard', 'my-listing' ) . '</a> ';
					}
					?>
				</p>

				<?php

			}
		}// End foreach().
	}

	/**
	 * Triggered when an order is paid
	 *
	 * @since 1.0.0
	 *
	 * @param int $order_id
	 */
	public function order_paid( $order_id ) {
		// Get the order
		$order = wc_get_order( $order_id );

		// Bail if already processed. Using WCPL prefix for back-compat.
		if ( get_post_meta( $order_id, 'wc_paid_listings_packages_processed', true ) ) {
			return;
		}

		foreach ( $order->get_items() as $item ) {
			$product = wc_get_product( $item['product_id'] );

			if ( $product->is_type( array( 'job_package', 'job_package_subscription' ) ) && $order->get_customer_id() ) {

				// Give packages to user
				$user_package_id = false;
				for ( $i = 0; $i < $item['qty']; $i ++ ) {
					$user_package_id = case27_paid_listing_add_package( array(
						'user_id'        => $order->get_customer_id(),
						'order_id'       => $order_id,
						'product_id'     => $product->get_id(),
						'duration'       => $product->get_duration(),
						'limit'          => $product->get_limit(),
						'featured'       => $product->is_listing_featured(),
						'use_for_claims' => $product->is_listing_featured(),
					) );

					// User package created & make sure job ID is set.
					if ( $user_package_id && isset( $item['job_id'] ) ) {

						// Check listing.
						$listing = get_post( $item['job_id'] );

						if ( $listing && 'job_listing' === $listing->post_type ) {

							// Add user package info to listing.
							update_post_meta( $item['job_id'], '_user_package_id', $user_package_id );

							// Create claim.
							if ( isset( $item['is_claim'] ) && $item['is_claim'] ) {
								$claim_args = array(
									'listing_id'      => absint( $listing->ID ),
									'user_package_id' => absint( $user_package_id ),
									'user_id'         => absint( $order->get_customer_id() ),
								);
								$claim_id = case27_paid_listing_claim_create_claim( $claim_args );
							} else {
								// Update listing status.
								$listing_data = array(
									'ID'            => $listing->ID,
									'post_status'   => get_option( 'job_manager_submission_requires_approval' ) ? 'pending' : 'publish',
								);
								wp_update_post( $listing_data );
							}
						}

					}
				}


			}
		}

		// Mark that this order already processed.
		update_post_meta( $order_id, 'wc_paid_listings_packages_processed', true );
	}

	/**
	 * Fires when a order was canceled. Looks for Job Packages in order and deletes the package if found.
	 *
	 * @since 1.0.0
	 *
	 * @param $order_id
	 */
	public function order_cancelled( $order_id ) {
		$packages = case27_paid_listing_get_user_packages( array(
			'status'   => 'any',
			'meta_query' => array(
				'relation' => 'AND',
				array(
					'key'     => '_order_id',
					'value'   => $order_id,
					'compare' => 'IN',
				),
			),
		) );
		if ( $packages && is_array( $packages ) ) {
			foreach ( $packages as $package_id ) {
				wp_update_post( array(
					'ID'          => $package_id,
					'post_status' => 'case27_cancelled',
				) );
			}
		}
	}
}

WooCommerce::instance();
