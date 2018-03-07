<?php

namespace AutomateWoo;

/**
 * @class Admin_Data_Layer_Formatter
 */
class Admin_Data_Layer_Formatter {


	/**
	 * @param Data_Layer $data_layer
	 * @return array
	 */
	static function format( $data_layer ) {

		$data = $data_layer->get_raw_data();
		$formatted_data = [];

		foreach ( $data as $data_type => $data_item ) {

			if ( ! $data_item ) {
				continue;
			}

			switch ( $data_type ) {

				case 'order':
					/** @var \WC_Order $data_item */
					$link = get_edit_post_link( Compat\Order::get_id( $data_item ) );
					$formatted_data[] = [
						'title' => __('Order', 'automatewoo'),
						'value' => "<a href='$link'>#".Compat\Order::get_id( $data_item )."</a>"
					];
					break;

				case 'customer':
					$formatted_data[] = [
						'title' => __( 'Customer', 'automatewoo' ),
						'value' => Format::customer( $data_item )
					];
					break;


				case 'guest':
					/** @var $data_item Guest */
					$formatted_data[] = [
						'title' => __('Guest', 'automatewoo'),
						'value' => "<a href='mailto:{$data_item->get_email()}'>{$data_item->get_email()}</a>"
					];
					break;


				case 'cart':
					/** @var $data_item Cart */
					$formatted_data[] = [
						'title' => __('Cart', 'automatewoo'),
						'value' => '#' . $data_item->get_id()
					];
					break;

				case 'review':
					/** @var $data_item Review */
					$link = get_edit_comment_link( $data_item->get_id() );
					$formatted_data[] = [
						'title' => __('Review', 'automatewoo'),
						'value' => "<a href='$link'>#" . $data_item->get_id(). "</a>"
					];
					break;

				case 'product':
					/** @var $data_item \WC_Product */
					$link = get_edit_post_link( Compat\Product::get_id( $data_item ) );
					$formatted_data[] = [
						'title' => __('Product', 'automatewoo'),
						'value' => "<a href='$link'>" . $data_item->get_title(). "</a>"
					];
					break;

				case 'subscription':
					/** @var $data_item \WC_Subscription */
					$link = get_edit_post_link( Compat\Subscription::get_id( $data_item ) );
					$formatted_data[] = [
						'title' => __('Subscription', 'automatewoo'),
						'value' => "<a href='$link'>#".Compat\Subscription::get_id( $data_item )."</a>"
					];
					break;

				case 'membership':
					/** @var $data_item \WC_Memberships_User_Membership */
					$link = get_edit_post_link( $data_item->id );
					$formatted_data[] = [
						'title' => __( 'Membership', 'automatewoo' ),
						'value' => "<a href='$link'>#$data_item->id</a>"
					];
					break;

				case 'wishlist':

					$formatted_data[] = [
						'title' => __( 'Wishlist', 'automatewoo' ),
						'value' => '#' . $data_item->id
					];

					break;
			}
		}

		return apply_filters( 'automatewoo/formatted_data_layer', $formatted_data, $data_layer );
	}

}
