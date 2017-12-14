<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

abstract class WC_Zapier_Trigger_Order extends WC_Zapier_Trigger {

	/**
	 * @var WC_Order instance
	 */
	protected $wc_order;

	/**
	 * The slug/key for the order status.
	 * Must correspond to a valid WooCommerce order status
	 *
	 * @var string
	 */
	protected $status_slug = '';


	/**
	 * Optional text that is added to the end of this Trigger's title inside brackets.
	 *
	 * @var string|null
	 */
	protected $title_suffix;

	/**
	 * Constructor
	 * @throws Exception
	 */
	public function __construct() {

		parent::__construct();

	}

	/**
	 * The sample WooCommerce order data that is sent to Zapier as sample data.
	 *
	 * @return array
	 */
	protected function get_sample_data() {
		// TODO: Update this sample order to use tax, a coupon, and shipping
		$order = array(
		   'id' => 123,
		   'number' => '#123',
		   'status' => 'processing',
		   'status_previous' => 'pending',
		   'date' => '2013-08-22T06:54:00+00:00',
		   'total' => '88.00',
		   'subtotal' => '88.00',
		   'currency' => 'USD',
		   'currency_symbol' => '$',
		   'transaction_id' => '123456789',
		   'view_url' => 'http://yourdomain.com/my-account/view-order/123',
		   'user_id' => '261',
		   'billing_first_name' => 'John',
		   'billing_last_name' => 'Smith',
		   'billing_company' => 'Acme, Inc.',
		   'billing_address' => 'Unit 1, 1600 Pennsylvania Ave NW, Washington, DC, 20500, US',
		   'billing_email' => 'john@example.com',
		   'billing_phone' => '+1 123 456 789',
		   'billing_address_1' => 'Unit 1',
		   'billing_address_2' => '1600 Pennsylvania Ave NW',
		   'billing_city' => 'Washington',
		   'billing_postcode' => '20500',
		   'billing_country' => 'US',
		   'billing_country_name' => 'United States',
		   'billing_state' => 'DC',
		   'billing_state_name' => 'District Of Columbia',
		   'shipping_first_name' => 'John',
		   'shipping_last_name' => 'Smith',
		   'shipping_company' => 'Acme, Inc.',
		   'shipping_address' => 'Unit 1, 1600 Pennsylvania Ave NW, Washington, DC, 20500, US',
		   'shipping_address_1' => 'Unit 1',
		   'shipping_address_2' => '1600 Pennsylvania Ave NW',
		   'shipping_city' => 'Washington',
		   'shipping_postcode' => '20500',
		   'shipping_country' => 'US',
		   'shipping_country_name' => 'United States',
		   'shipping_state' => 'DC',
		   'shipping_state_name' => 'District Of Columbia',
		   'shipping_method' => 'Free Shipping',
		   'payment_method' => 'Direct Bank Transfer',
		   'discount_total' => '', // No longer applicable in WC 2.3 and newer
		   'cart_discount' => '0.00',
		   'tax_total' => '0',
		   'shipping_total' => '0.00',
		   'shipping_tax' => '0.00',
		   'prices_include_tax' => false,
		   'customer_note' => 'This is a note added by the customer during checkout.',
		   'line_items' => array(
				 array(
		       'name' => 'Awesome Widget',
		       'quantity' => '1',
		       'product_id' => '33',
		       'variation_id' => '',
		       'sku' => 'WIDGET',
		       'categories' => 'Category A, Category B',
		       'tags' => 'TagA, TagB',
		       'type' => 'simple',
		       'line_subtotal' => '88.00',
		       'line_total' => '88.00',
		       'line_tax' => '0.00',
		       'line_subtotal_tax' => '0.00',
		       'tax_class' => '',
		       'item_meta' => array(
		       		// Empty unless using a plugin/extension that adds custom order item meta data
		       )
		    )
		  ),
		   'item_count' => 1,
		   'has_downloadable_item' => true,
		   'downloadable_files' => array(
				 array(
					 'download_url' => 'http://yourdomain.com/file.zip',
					 'filename' => 'file.zip'
				 )
			 ),
		   'notes' => array(
				 array(
						'note' => 'Thank you for your order. We will let you know once your order has shipped.',
						'date' => '2013-08-23T01:24:55+00:00',
						'author' => 'WooCommerce',
						'author_email' => 'storeowner@example.com',
				 )
			 ),
		);
		return $order;
	}

	public function assemble_data( $args, $action_name ) {

		$order_id        = '';
		$new_status      = '';
		$previous_status = '';

		if ( $this->is_sample() ) {
			// The webhook/trigger is being tested
			return $this->get_sample_data();

		} else {
			// Using real live data

			$order_id = intval( $args[0] );

			if ( 'woocommerce_new_order' == $action_name ) {
				$previous_status = '';
			} else if ( 'woocommerce_order_status_changed' == $action_name ) {
				$previous_status = $args[1];
				$new_status = $args[2];
			} else if ( preg_match( '/^woocommerce_order_status_([a-z-]+)_to_([a-z-]+)$/i', $action_name, $matches ) ) {
				// Note: order statuses can be a-z characters or a hyphen
				$previous_status = $matches[1];
				$new_status = $matches[2];
			} else if ( 'woocommerce_payment_complete' == $action_name ) {
				// We don't know the previous status
				// Nothing special required here
			}

			if ( ! $order_id )
				return false;

			$this->wc_order = new WC_Order($order_id);

		}

		if ( empty( $new_status ) )
			$new_status = is_callable( array( $this->wc_order, 'get_status' ) ) ? $this->wc_order->get_status() : $this->wc_order->status;

		// Note: this could fire for any order statuses (including pending/unpaid)

		// Compile the order details/data that will be sent to Zapier
		$order = new stdClass();

		// WooCommerce 3.0+ use WC_Order::get_*() functions, and previous WC versions use  WC_Order properties.
		// TODO: Once WooCommerce 3.1 is released, the is_callable() references below can be removed.

		// Order Details
		$order->id              = is_callable( array( $this->wc_order, 'get_id' ) ) ? $this->wc_order->get_id() : $this->wc_order->id; // Order ID (integer)
		$order->number          = $this->wc_order->get_order_number(); // Order Number (eg #123)
		$order->status          = $new_status; // New order status (on-hold, processing, etc)
		$order->status_previous = $previous_status; // Previous order status (on-hold, processing, etc)
		$order->date            = WC_Zapier::format_date( is_callable( array( $this->wc_order, 'get_date_created' ) ) ? $this->wc_order->get_date_created() : $this->wc_order->order_date );
		$order->total           = WC_Zapier::format_price( $this->wc_order->get_total() ); // Order total
		$order->subtotal        = WC_Zapier::format_price( $this->wc_order->get_subtotal() ); // Cart Subtotal
		$order->currency        = is_callable( array( $this->wc_order, 'get_currency' ) ) ? $this->wc_order->get_currency() : $this->wc_order->get_order_currency(); // Currency (eg AUD)
		$order->currency_symbol = WC_Zapier::decode( get_woocommerce_currency_symbol( $order->currency ) ); // Currency Symbol (eg $)
		$order->transaction_id  = $this->wc_order->get_transaction_id();
		$order->view_url        = $this->wc_order->get_view_order_url();

		// Billing Details
		$order->user_id = $this->wc_order->get_user_id();
		$order->billing_first_name = is_callable( array( $this->wc_order, 'get_billing_first_name' ) ) ? $this->wc_order->get_billing_first_name() : $this->wc_order->billing_first_name;
		$order->billing_last_name  = is_callable( array( $this->wc_order, 'get_billing_last_name' ) ) ? $this->wc_order->get_billing_last_name() : $this->wc_order->billing_last_name;
		$order->billing_company    = is_callable( array( $this->wc_order, 'get_billing_company' ) ) ? $this->wc_order->get_billing_company() : $this->wc_order->billing_company;
		$order->billing_address    = WC_Zapier::decode( $this->wc_order->get_formatted_billing_address() ); // Single line billing address separated by commas
		$order->billing_email      = is_callable( array( $this->wc_order, 'get_billing_email' ) ) ? $this->wc_order->get_billing_email() : $this->wc_order->billing_email;
		$order->billing_phone      = is_callable( array( $this->wc_order, 'get_billing_phone' ) ) ? $this->wc_order->get_billing_phone() : $this->wc_order->billing_phone;
		// Individual Billing Address Components
		$order->billing_address_1    = is_callable( array( $this->wc_order, 'get_billing_address_1' ) ) ? $this->wc_order->get_billing_address_1() : $this->wc_order->billing_address_1;
		$order->billing_address_2    = is_callable( array( $this->wc_order, 'get_billing_address_2' ) ) ? $this->wc_order->get_billing_address_2() : $this->wc_order->billing_address_2;
		$order->billing_city         = is_callable( array( $this->wc_order, 'get_billing_city' ) ) ? $this->wc_order->get_billing_city() : $this->wc_order->billing_city;
		$order->billing_postcode     = is_callable( array( $this->wc_order, 'get_billing_postcode' ) ) ? $this->wc_order->get_billing_postcode() : $this->wc_order->billing_postcode;
		$order->billing_country      = is_callable( array( $this->wc_order, 'get_billing_country' ) ) ? $this->wc_order->get_billing_country() : $this->wc_order->billing_country; // Two letter country code
		$order->billing_country_name = ''; // Country Name
		if ( $order->billing_country != '' ) {
			// Only if the order has a billing country
			$order->billing_country_name = WC()->countries->countries[$order->billing_country];
		}
		$order->billing_state        = is_callable( array( $this->wc_order, 'get_billing_state' ) )? $this->wc_order->get_billing_state() : $this->wc_order->billing_state;

		$order->billing_state_name = '';
		if ( !empty( $order->billing_state ) ) {
			$order->billing_state_name = WC()->countries->states[$order->billing_country][$order->billing_state];
		}


		// Shipping Details
		$order->shipping_first_name = is_callable( array( $this->wc_order, 'get_shipping_first_name' ) ) ? $this->wc_order->get_shipping_first_name() : $this->wc_order->shipping_first_name;
		$order->shipping_last_name  = is_callable( array( $this->wc_order, 'get_shipping_last_name' ) ) ? $this->wc_order->get_shipping_last_name() : $this->wc_order->shipping_last_name;
		$order->shipping_company    = is_callable( array( $this->wc_order, 'get_shipping_company' ) ) ? $this->wc_order->get_shipping_company() : $this->wc_order->shipping_company;
		$order->shipping_address    = WC_Zapier::decode( $this->wc_order->get_formatted_shipping_address() ); // Single line shipping address separated by commas
		// Individual Shipping Address Components
		$order->shipping_address_1    = is_callable( array( $this->wc_order, 'get_shipping_address_1' ) ) ? $this->wc_order->get_shipping_address_1() : $this->wc_order->shipping_address_1;
		$order->shipping_address_2    = is_callable( array( $this->wc_order, 'get_shipping_address_2' ) ) ? $this->wc_order->get_shipping_address_2() : $this->wc_order->shipping_address_2;
		$order->shipping_city         = is_callable( array( $this->wc_order, 'get_shipping_city' ) ) ? $this->wc_order->get_shipping_city() : $this->wc_order->shipping_city;
		$order->shipping_postcode     = is_callable( array( $this->wc_order, 'get_shipping_postcode' ) ) ? $this->wc_order->get_shipping_postcode() : $this->wc_order->shipping_postcode;
		$order->shipping_country      = is_callable( array( $this->wc_order, 'get_shipping_country' ) ) ? $this->wc_order->get_shipping_country() : $this->wc_order->shipping_country; // Two letter country code
		$order->shipping_country_name = ''; // Country Name
		if ( $order->shipping_country != '' ) {
			// Only if the order has a shipping address
			$order->shipping_country_name = WC()->countries->countries[$order->shipping_country];
		}
		$order->shipping_state        = is_callable( array( $this->wc_order, 'get_shipping_state' ) ) ? $this->wc_order->get_shipping_state() : $this->wc_order->shipping_state;

		$order->shipping_state_name = '';
		if ( !empty( $order->shipping_state ) ) {
			$order->shipping_state_name = WC()->countries->states[$order->shipping_country][$order->shipping_state];
		}


		// Shipping & Payment Methods
		$order->shipping_method = $this->wc_order->get_shipping_method();
		$order->payment_method  = is_callable( array(  $this->wc_order, 'get_payment_method_title' ) ) ? $this->wc_order->get_payment_method_title() : $this->wc_order->payment_method_title;


		// Other Amounts

		// WC 2.3 removed the concept of an "after tax" discount, which included deprecating the WC_Order::get_order_discount() function with no alternative available.
		// This field will be removed in a future release. For now, it will return an empty value.
		// Ref: https://woocommerce.wordpress.com/2014/12/12/upcoming-coupon-changes-in-woocommerce-2-3/
		$order->discount_total = '';
		$order->cart_discount  = WC_Zapier::format_price( $this->wc_order->get_total_discount() ); // Before tax discount total
		$order->tax_total      = WC_Zapier::format_price( is_callable( array( $this->wc_order, 'get_cart_tax' ) ) ? $this->wc_order->get_cart_tax() : $this->wc_order->order_tax ); // Tax for the items total
		 // Shipping cost
		$order->shipping_total = WC_Zapier::format_price( is_callable( array( $this->wc_order, 'get_shipping_total' ) ) ? $this->wc_order->get_shipping_total() : $this->wc_order->get_total_shipping() );
		$order->shipping_tax   = WC_Zapier::format_price( $this->wc_order->get_shipping_tax() ); // Shipping tax


		// Miscellaneous
		$order->prices_include_tax = is_callable( array( $this->wc_order, 'get_prices_include_tax' ) ) ? $this->wc_order->get_prices_include_tax() : $this->wc_order->prices_include_tax;
		$order->customer_note      = is_callable( array( $this->wc_order, 'get_customer_note' ) ) ? $this->wc_order->get_customer_note() : $this->wc_order->customer_note; // Note added by the customer


		// Order line items
		// Arrays are not very well supported by Zapier at this point, but we'll send the data anyway
		$order->line_items      = array(); // Array of order line items
		$downloadable_file_urls = array();

		foreach ( $this->wc_order->get_items() as $line_item_data ) {

			// We also need product data such as SKU and categories
			// WC 3.0 plus backwards compat with previous versions
			$product_id   = is_callable( array( $line_item_data, 'get_product_id' ) ) ? $line_item_data->get_product_id() : $line_item_data['item_meta']['_product_id'][0];
			$variation_id = is_callable( array( $line_item_data, 'get_variation_id' ) ) ? $line_item_data->get_variation_id() : $line_item_data['item_meta']['_variation_id'][0];
			$product      = is_callable( array( $line_item_data, 'get_product' ) ) ? $line_item_data->get_product() : $this->wc_order->get_product_from_item( $line_item_data );

			$line_item                    = new stdClass();
			$line_item->name              = is_callable( array( $line_item_data, 'get_name' ) ) ? $line_item_data->get_name() : $line_item_data['name'];
			$line_item->quantity          = is_callable( array( $line_item_data, 'get_quantity' ) ) ? $line_item_data->get_quantity() : $line_item_data['qty'];
			$line_item->product_id        = $product_id;
			$line_item->variation_id      = $variation_id;
			$line_item->sku               = $product ? $product->get_sku() : '';

			// If this line item is a variation (WC 3.0+) or variable (WC 2.6.x) product, get the parent product's categories and tags
			$product_to_check = $product;
			if ( $product->is_type( 'variation' ) || $product->is_type( 'variable' ) ) {
				$product_to_check = wc_get_product( is_callable( array( $product, 'get_parent_id' ) ) ? $product->get_parent_id() : $product->parent->id );
			}

			$line_item->categories        = '';
			if ( $product ) {
				$line_item->categories = function_exists( 'wc_get_product_category_list' ) ? wc_get_product_category_list( $product_to_check->get_id() ) : $product_to_check->get_categories();
			}
			if ( $line_item->categories ) {
				// Remove links/HTML from list of categories
				$line_item->categories = strip_tags( $line_item->categories );
			} else {
				$line_item->categories = '';
			}

			// Product Tags
			$line_item->tags        = '';
			if ( $product ) {
				$line_item->tags = function_exists( 'wc_get_product_tag_list' ) ? wc_get_product_tag_list( $product_to_check->get_id() ) : $product_to_check->get_tags();
			}
			if ( $line_item->tags ) {
				// Remove links/HTML from list of tags
				$line_item->tags = strip_tags( $line_item->tags );
			} else {
				$line_item->tags = '';
			}
			
			// Product Type.
			$line_item->type              = $product->get_type();

			// Line Item Data.
			$line_item->line_subtotal     = WC_Zapier::format_price( is_callable( array( $line_item_data, 'get_subtotal' ) ) ? $line_item_data->get_subtotal() : $line_item_data['line_subtotal'] );
			$line_item->line_total        = WC_Zapier::format_price( is_callable( array( $line_item_data, 'get_total' ) ) ? $line_item_data->get_total() : $line_item_data['line_total'] );
			$line_item->line_tax          = WC_Zapier::format_price( is_callable( array( $line_item_data, 'get_total_tax' ) ) ? $line_item_data->get_total_tax() : $line_item_data['line_tax'] );
			$line_item->line_subtotal_tax = WC_Zapier::format_price( is_callable( array( $line_item_data, 'get_subtotal_tax' ) ) ? $line_item_data->get_subtotal_tax() : $line_item_data['line_subtotal_tax'] );
			$line_item->tax_class         = is_callable( array( $line_item_data, 'get_tax_class' ) ) ? $line_item_data->get_tax_class() : $line_item_data['tax_class'];

			// Downloadable files
			// Only included once the customer has permission to download the files (typically when the order status is Processing or Completed).
			// See https://docs.woocommerce.com/document/digitaldownloadable-product-handling/#section-3 for more details.
			foreach ( ( is_callable( array( $line_item_data, 'get_item_downloads' ) ) ? $line_item_data->get_item_downloads() : $this->wc_order->get_item_downloads( $line_item_data ) ) as $download_id => $download_details ) {
				$file                     = new stdClass();
				// TODO: also include WC 2.1+ downloadable file name
				$file->filename           = wc_get_filename_from_url( $download_details['file'] );
				$file->download_url       = $download_details['download_url'];
				$downloadable_file_urls[] = $file;
			}

			/*
			 * Order Line Item Meta
			 * For compatibility with any extensions that add their own order line item meta data, such as:
			 * - Product Add-ons: https://woocommerce.com/products/product-add-ons/
			 * - Gravity Forms Add-ons: https://woocommerce.com/products/gravity-forms-add-ons/
			 *
			 * These extensions typically use the woocommerce_add_order_item_meta() function to add their own metadata to an order line item.
			 */
			$line_item->item_meta = array();

			foreach ( $line_item_data['item_meta'] as $meta_key => $meta_value ) {

				if ( '_' === $meta_key[0] ) {
					// Skip meta items that have a key beginning with a _ because these are usually internal/hidden
					continue;
				}
				
				$meta_key = WC_Zapier::decode( $meta_key );

				if ( is_array( $meta_value ) ) {
					if ( 1 == count( $meta_value ) ) {
						// Single value for this meta key - send single value as a string instead of a 1-sized array
						$line_item->item_meta[$meta_key] = $meta_value[0];
					} else {
						// Multiple values for this meta key - send entire array as-is
						$line_item->item_meta[$meta_key] = $meta_value;
					}
				} else {
					// Meta value is a string (WC 3.0+)
					$line_item->item_meta[$meta_key] = $meta_value;
				}

				$line_item->item_meta[$meta_key] = WC_Zapier::decode( $line_item->item_meta[$meta_key] );

			}

			$order->line_items[]          = $line_item;

		}



		$order->item_count            = $this->wc_order->get_item_count(); // Total number of items
		$order->has_downloadable_item = $this->wc_order->has_downloadable_item(); // If the order contains a downloadable product.
		$order->downloadable_files    = empty($downloadable_file_urls) ? array() : $downloadable_file_urls;

		// Customer Notes (Private Notes aren't included)
		// Arrays are not very well supported by Zapier at this point, but we'll send the data anyway
		$order->notes = array(); // Array of order notes
		foreach ( $this->wc_order->get_customer_order_notes() as $order_note_data ) {
			$note               = new stdClass();
			$note->note         = $order_note_data->comment_content;
			$note->date         = WC_Zapier::format_date( $order_note_data->comment_date );
			$note->author       = $order_note_data->comment_author;
			$note->author_email = $order_note_data->comment_author_email;
			$order->notes[]     = $note;
		}

		// Order data needs to be an array.
		$order = (array) $order;

		WC_Zapier()->log( "Assembled order data.", $order['id'] );

		return $order;

	}

	protected function data_sent_to_feed( WC_Zapier_Feed $feed, $result, $action_name, $arguments, $num_attempts = 0 ) {

		$note = '';

		if ( 1 == $num_attempts  ) {
			// Successful on the first attempt
			$note .= sprintf( __( 'Order sent to Zapier via the <a href="%1$s">%2$s</a> Zapier feed.', 'wc_zapier' ), $feed->edit_url(), $feed->title() );
		} else {
			// It took more than 1 attempt so add that to the note
			$note .= sprintf( __( 'Order sent to Zapier via the <a href="%1$s">%2$s</a> Zapier feed after %3$d attempts.', 'wc_zapier' ), $feed->edit_url(), $feed->title(), $num_attempts );
		}

		$note .= sprintf( __( '<br ><br />Trigger:<br />%1$s<br />%2$s', 'wc_zapier' ), $feed->trigger()->get_trigger_title(), "<small>{$action_name}</small>" );

		// Add a private note to this order
		$this->wc_order->add_order_note( $note );

		WC_Zapier()->log( $note, is_callable( array( $this->wc_order, 'get_id') ) ? $this->wc_order->get_id() : $this->wc_order->id );

		parent::data_sent_to_feed( $feed, $result, $action_name, $arguments, $num_attempts );

	}
}