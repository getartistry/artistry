<?php

namespace AutomateWoo\Compat;

/**
 * @class Product
 * @since 2.9
 */
class Product {

	/**
	 * @param \WC_Product|\WC_Product_Variation $product
	 * @return int
	 */
	static function get_id( $product ) {
		if ( version_compare( WC()->version, '3.0', '<' ) ) {
			return self::is_variation( $product ) ? $product->variation_id : $product->id;
		} else {
			return $product->get_id();
		}
	}


	/**
	 * @param \WC_Product $product
	 * @return int
	 */
	static function get_parent_id( $product ) {
		if ( version_compare( WC()->version, '3.0', '<' ) ) {
			return self::is_variation( $product ) ? $product->id : $product->get_parent();
		}
		else {
			return $product->get_parent_id();
		}
	}


	/**
	 * @param \WC_Product $product
	 * @return int
	 */
	static function get_name( $product ) {
		if ( version_compare( WC()->version, '3.0', '<' ) ) {
			return $product->get_title();
		}
		else {
			return $product->get_name();
		}
	}


	/**
	 * @param \WC_Product $product
	 * @return bool
	 */
	static function is_variation( $product ) {
		return $product->is_type( [ 'variation', 'subscription_variation' ] );
	}


	/**
	 * @param \WC_Product $product
	 * @param $key
	 * @return mixed
	 */
	static function get_meta( $product, $key ) {
		if ( is_callable( [ $product, 'get_meta' ] ) ) {
			return $product->get_meta( $key );
		}
		else {
			return get_post_meta( self::get_id( $product ), $key, true );
		}
	}


	/**
	 * @param \WC_Product $product
	 * @param $key
	 * @return mixed
	 */
	static function get_parent_meta( $product, $key ) {

		if ( ! $parent_id = self::get_parent_id( $product ) ) {
			return false;
		}

		if ( is_callable( [ $product, 'get_meta' ] ) ) {
			if ( ! $parent = wc_get_product( $parent_id ) ) {
				return false;
			}

			return $parent->get_meta( $key );
		}
		else {
			return get_post_meta( $parent_id, $key, true );
		}
	}


	/**
	 * @param \WC_Product $product
	 * @param $key
	 * @param $value
	 * @return mixed
	 */
	static function update_meta( $product, $key, $value ) {
		if ( version_compare( WC()->version, '3.0', '<' ) ) {
			update_post_meta( self::get_id( $product ), $key, $value );
		}
		else {
			$product->update_meta_data( $key, $value );
			$product->save();
		}
	}


	/**
	 * @param \WC_Product $product
	 * @return array
	 */
	static function get_cross_sell_ids( $product ) {
		if ( version_compare( WC()->version, '3.0', '<' ) ) {
			return $product->get_cross_sells();
		}
		else {
			return $product->get_cross_sell_ids();
		}
	}


	/**
	 * @param \WC_Product $product
	 * @return array
	 */
	static function get_price_including_tax( $product ) {
		if ( version_compare( WC()->version, '3.0', '<' ) ) {
			return $product->get_price_including_tax();
		}
		else {
			return wc_get_price_including_tax( $product );
		}
	}


	/**
	 * @param \WC_Product $product
	 * @return mixed
	 */
	static function get_description( $product ) {
		if ( is_callable( [ $product, 'get_description' ] ) ) {
			return $product->get_description();
		}
		else {
			if ( self::is_variation( $product ) ) {
				return self::get_meta( $product, '_variation_description' );
			}
			else {
				return $product->post->post_content;
			}
		}
	}


	/**
	 * @param \WC_Product $product
	 * @return string
	 */
	static function get_short_description( $product ) {
		if ( version_compare( WC()->version, '3.0', '<' ) ) {
			return $product->post->post_excerpt;
		}
		else {
			return $product->get_short_description();
		}
	}


	/**
	 * @param \WC_Product $product
	 * @return array
	 */
	static function get_related( $product, $limit = 5 ) {
		if ( version_compare( WC()->version, '3.0', '<' ) ) {
			return $product->get_related( $limit );
		}
		else {
			return wc_get_related_products( $product->get_id(), $limit );
		}
	}


}