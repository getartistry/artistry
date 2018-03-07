<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Trigger_Wishlist_Item_Goes_On_Sale
 */
class Trigger_Wishlist_Item_Goes_On_Sale extends Trigger {

	public $supplied_data_items = [ 'customer', 'product', 'wishlist' ];

	public $allow_queueing = false;


	function load_admin_details() {
		$this->title = sprintf( __( 'Wishlist Item On Sale (%s)', 'automatewoo'), Wishlists::get_integration_title() );
		$this->group = __( 'Wishlists', 'automatewoo' );
		$this->description = __(
			"This trigger can't fire immediately when a product goes on sale so instead it performs a check every four hours. "
			. "Please note this doesn't work for guests because their wishlist data only exists in their session data.",
			'automatewoo');
	}


	function register_hooks() {
		$integration = Wishlists::get_integration();

		if ( ! $integration )
			return;

		add_action( 'automatewoo/products/gone_on_sale', [ $this, 'catch_hooks_' . $integration ] );
	}


	function catch_hooks_woothemes( $products ) {

		if ( ! $this->has_workflows() ) {
			return;
		}

		$wishlists = new \WP_Query([
			'post_type' => 'wishlist',
			'fields' => 'ids',
			'posts_per_page' => -1
		]);

		$this->start_background_process( $wishlists->posts, $products );
	}


	/**
	 * @param array $products
	 */
	function catch_hooks_yith( $products ) {

		if ( ! $this->has_workflows() ) {
			return;
		}

		$wishlists = YITH_WCWL()->get_wishlists([
			'user_id' => false,
			'show_empty' => false
		]);

		$this->start_background_process( $wishlists ? $wishlists : [], $products );
	}


	/**
	 * @param array $wishlists
	 * @param array $products
	 */
	function start_background_process( $wishlists, $products ) {

		/** @var Background_Processes\Wishlist_Item_On_Sale $process */
		$process = Background_Processes::get('wishlist_item_on_sale');

		foreach( $wishlists as $wishlist_id ) {
			$process->push_to_queue([
				'wishlist_id' => $wishlist_id,
				'product_ids' => $products
			]);
		}

		$process->start();
	}


	/**
	 * @param $workflow Workflow
	 * @return bool
	 */
	function validate_workflow( $workflow ) {

		$customer = $workflow->data_layer()->get_customer();
		$product = $workflow->data_layer()->get_product();

		if ( ! $customer || ! $product ) {
			return false;
		}

		// Only trigger once per user, per product, per workflow, check logs
		$query = ( new Log_Query() )
			->where( 'workflow_id', $workflow->get_id() )
			->where( 'product_id', Compat\Product::get_id( $product ) )
			->where( 'user_id', $customer->get_user_id() );

		if ( $query->has_results() ) {
			return false;
		}

		return true;
	}


	/**
	 * @param Workflow $workflow
	 * @return bool
	 */
	function validate_before_queued_event( $workflow ) {

		if ( ! parent::validate_before_queued_event( $workflow ) ) {
			return false;
		}

		$product = $workflow->data_layer()->get_product();

		if ( ! $product->is_on_sale() ) {
			return false; // check product is still on sale
		}

		return true;
	}

}

