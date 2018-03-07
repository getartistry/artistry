<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Trigger_Wishlist_Item_Added
 * @since 2.3
 */
class Trigger_Wishlist_Item_Added extends Trigger {

	public $supplied_data_items = [ 'user', 'wishlist', 'product' ];


	function load_admin_details() {
		$this->title = sprintf( __( 'Customer Adds Product (%s)', 'automatewoo'), Wishlists::get_integration_title() );
		$this->group = __( 'Wishlists', 'automatewoo' );
	}


	function load_fields() {
		$this->add_field_user_pause_period();
	}


	function register_hooks() {
		add_action( 'yith_wcwl_added_to_wishlist', [ $this, 'catch_hooks' ], 20, 3 );
	}


	/**
	 * Route hooks through here
	 * @param int $product_id
	 * @param int $wishlist_id
	 * @param int $user_id
	 */
	function catch_hooks( $product_id, $wishlist_id, $user_id ) {

		if ( ! $this->has_workflows() ) {
			return;
		}

		$integration = Wishlists::get_integration();

		if ( $integration == 'yith' ) {

			$wishlist = Wishlists::get_wishlist( $wishlist_id );
			$user = get_user_by( 'id', $user_id );

			$this->maybe_run([
				'user' => $user,
				'wishlist' => $wishlist,
				'product' => wc_get_product( $product_id )
			]);
		}
		else {
			return;
		}
	}


	/**
	 * @param $workflow Workflow
	 *
	 * @return bool
	 */
	function validate_workflow( $workflow ) {

		$user = $workflow->data_layer()->get_user();
		$wishlist = $workflow->data_layer()->get_wishlist();
		$product = $workflow->data_layer()->get_product();

		if ( ! $user || ! $wishlist || ! $product )
			return false;

		if ( ! $this->validate_field_user_pause_period( $workflow ) )
			return false;

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

		$user = $workflow->data_layer()->get_user();
		$wishlist = $workflow->data_layer()->get_wishlist();
		$product = $workflow->data_layer()->get_product();

		if ( ! $user || ! $wishlist || ! $product )
			return false;

		if ( Wishlists::get_integration() !== 'yith' ) {
			return false;
		}

		// check product is still in wishlist
		if ( ! in_array( Compat\Product::get_id( $product ), $wishlist->get_items() ) ) {
			return false;
		}

		return true;
	}

}
