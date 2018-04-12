<?php

namespace CASE27\Integrations\PromotedListings;

class PromotionProduct extends \WC_REST_Products_Controller {

	public function c27_update_product()
	{
		if (did_action('woocommerce_after_register_taxonomy') >= 1) {
			return $this->c27_handle_product_update();
		}

		return add_action( 'woocommerce_after_register_taxonomy', [ $this, 'c27_handle_product_update' ] );
	}

	public function c27_handle_product_update() {
		// $days = [
		// 	['days' => 10, 'regular_price' => 21.99],
		// 	['days' => 14, 'regular_price' => 34.99],
		// 	['days' => 21, 'regular_price' => 46.99],
		// 	['days' => 28, 'regular_price' => 55.99],
		// ];

		$days = c27()->get_setting( 'promotions_packages', [] );

		$data = [
		    'name' => 'Promotional Key',
		    'type' => 'variable',
		    'description' => 'A promotion key allows you to advertise any of your listings accross the site. The listing may be featured in special sections, it will be ranked higher in search results, etc. The promotion can be cancelled at any time, and the remaining amout of ad time will be saved until you decide to use the key again, on the same listing or on a different one.',
		    'short_description' => 'Advertise your listings using promotion keys.',
		    'categories' => [],
		    'sold_individually' => true,
		    'images' => [
		        // [
		        //     'src' => 'http://demo.woothemes.com/woocommerce/wp-content/uploads/sites/56/2013/06/T_4_front.jpg',
		        //     'position' => 0
		        // ],
		    ],
		    'attributes' => [
		        [
		            'name' => 'Days',
		            'position' => 0,
		            'visible' => true,
		            'variation' => true,
		            'options' => array_column( $days, 'days' ),
		        ]
		    ],
		    'default_attributes' => [
		        [
		            'name' => 'Days',
		            'option' => $days[0]['days'],
		        ]
		    ],
		    'meta_data' => [
		    	['key' => 'case27_wc_product', 'value' => 'listing_promotion'],
		    ],
		];

		$promoProductID = get_option( 'case27_promotion_product_id', false );

		if ($promoProductID && $promoProductID >= 1) {
			$data['id'] = absint( $promoProductID );
		}

		$product = $this->prepare_object_for_database($data, true);

		if ( ! is_wp_error( $product ) ) {
			$product->save();

			update_option( 'case27_promotion_product_id', $product->get_id(), true );

			$days_arr = array_map( 'absint', array_column( $days, 'days' ) );

			if ( ! empty( $days ) ) {
				wc_maybe_define_constant( 'WC_MAX_LINKED_VARIATIONS', 49 );
				wc_set_time_limit( 0 );

				// Get existing variations so we don't create duplicates.
				$existing_variations = array_map( 'wc_get_product', $product->get_children() );
				$persisted_variations = [];
				$added               = 0;

				// Clean up old variations.
				foreach ( $existing_variations as $existing_variation ) {
					$variation_attributes = $existing_variation->get_attributes();
					if ( ! isset( $variation_attributes['days'] ) || ! is_numeric( $variation_attributes['days'] ) || ! $variation_attributes['days'] ) {
						$existing_variation->delete();
						continue;
					}

					if ( ! in_array( absint( $variation_attributes['days'] ), $days_arr ) ) {
						$existing_variation->delete();
						continue;
					}

					if ( isset( $persisted_variations[ absint( $variation_attributes['days'] ) ] ) ) {
						$existing_variation->delete();
						continue;
					}

					$persisted_variations[ absint( $variation_attributes['days'] ) ] = $existing_variation;
				}

				// Insert variations.
				foreach ( $days as $day ) {

					$variation = isset( $persisted_variations[ absint( $day['days'] ) ] ) ? $persisted_variations[ absint( $day['days'] ) ] : new \WC_Product_Variation;
					$variation->set_parent_id( $product->get_id() );
					$variation->set_attributes( ['days' => absint( $day['days'] )] );
					$variation->set_virtual( true );
					$variation->set_regular_price( (float) $day['regular_price'] );

					$variation->save();

					if ( ( $added ++ ) > WC_MAX_LINKED_VARIATIONS ) {
						break;
					}
				}
			}
		}
	}
}
