<?php

namespace AutomateWoo\Fields;

use AutomateWoo\Compat;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Subscription_Products
 */
class Subscription_Products extends Select {

	protected $name = 'subscription_products';

	public $multiple = true;


	function __construct() {
		parent::__construct( false );

		$this->set_title( __( 'Subscription products', 'automatewoo' ) );
		$this->set_placeholder( __( '[Any]', 'automatewoo' ) );

		$options = [];

		$query = new \WP_Query([
			'post_type' => 'product',
			'posts_per_page' => -1,
			'no_found_rows' => true,
			'tax_query' => [
				[
					'taxonomy' => 'product_type',
					'field' => 'slug',
					'terms' => [
						'subscription',
						'variable-subscription'
					],
				],
			],
		]);

		foreach ( $query->posts as $subscription_post ) {
			$product = wc_get_product( $subscription_post );

			$options[ Compat\Product::get_id( $product ) ] = $product->get_formatted_name();

			if ( $product->is_type('variable-subscription') ) {
				foreach ( $product->get_children() as $variation_id ) {
					$variation = wc_get_product( $variation_id );
					$options[ $variation_id ] = $variation->get_formatted_name();
				}
			}
		}

		$this->set_options( $options );
	}

}
