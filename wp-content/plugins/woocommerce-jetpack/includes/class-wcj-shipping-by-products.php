<?php
/**
 * Booster for WooCommerce - Module - Shipping Methods by Products
 *
 * @version 3.5.0
 * @since   3.2.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'WCJ_Shipping_By_Products' ) ) :

class WCJ_Shipping_By_Products extends WCJ_Module_Shipping_By_Condition {

	/**
	 * Constructor.
	 *
	 * @version 3.2.0
	 * @since   3.2.0
	 * @todo    (maybe) add customer messages on cart and checkout pages (if some shipping method is not available)
	 */
	function __construct() {

		$this->id         = 'shipping_by_products';
		$this->short_desc = __( 'Shipping Methods by Products', 'woocommerce-jetpack' );
		$this->desc       = __( 'Set products, product categories or tags to include/exclude for WooCommerce shipping methods to show up.', 'woocommerce-jetpack' );
		$this->link_slug  = 'woocommerce-shipping-methods-by-products';

		$this->condition_options = array(
			'products' => array(
				'title' => __( 'Products', 'woocommerce-jetpack' ),
				'desc'  => __( 'Shipping methods by <strong>products</strong>.', 'woocommerce-jetpack' ),
			),
			'product_cats' => array(
				'title' => __( 'Product Categories', 'woocommerce-jetpack' ),
				'desc'  => __( 'Shipping methods by <strong>products categories</strong>.', 'woocommerce-jetpack' ),
			),
			'product_tags' => array(
				'title' => __( 'Product Tags', 'woocommerce-jetpack' ),
				'desc'  => __( 'Shipping methods by <strong>products tags</strong>.', 'woocommerce-jetpack' ),
			),
		);

		parent::__construct();
	}

	/**
	 * check.
	 *
	 * @version 3.5.0
	 * @since   3.2.0
	 * @todo    (maybe) if needed, prepare `$products_variations` earlier (and only once)
	 */
	function check( $options_id, $products_or_cats_or_tags, $include_or_exclude ) {
		if ( ! isset( WC()->cart ) || WC()->cart->is_empty() ) {
			return true;
		}
		if ( 'products' === $options_id && ( $do_add_variations = ( 'yes' === get_option( 'wcj_shipping_by_' . $options_id . '_add_variations_enabled', 'no' ) ) ) ) {
			$products_variations = array();
			foreach ( $products_or_cats_or_tags as $_product_id ) {
				$_product = wc_get_product( $_product_id );
				if ( $_product->is_type( 'variable' ) ) {
					$products_variations = array_merge( $products_variations, $_product->get_children() );
				} else {
					$products_variations[] = $_product_id;
				}
			}
			$products_or_cats_or_tags = array_unique( $products_variations );
		}
		$validate_all_for_include = ( 'include' === $include_or_exclude && 'yes' === get_option( 'wcj_shipping_by_' . $options_id . '_validate_all_enabled', 'no' ) );
		foreach ( WC()->cart->get_cart() as $cart_item_key => $values ) {
			switch( $options_id ) {
				case 'products':
					$_product_id = ( $do_add_variations && 0 != $values['variation_id'] ? $values['variation_id'] : $values['product_id'] );
					if ( $validate_all_for_include && ! in_array( $_product_id, $products_or_cats_or_tags ) ) {
						return false;
					} elseif ( ! $validate_all_for_include && in_array( $_product_id, $products_or_cats_or_tags ) ) {
						return true;
					}
					break;
				case 'product_cats':
				case 'product_tags':
					$product_terms = get_the_terms( $values['product_id'], ( 'product_cats' === $options_id ? 'product_cat' : 'product_tag' ) );
					if ( empty( $product_terms ) ) {
						if ( $validate_all_for_include ) {
							return false;
						} else {
							continue;
						}
					}
					foreach( $product_terms as $product_term ) {
						if ( $validate_all_for_include && ! in_array( $product_term->term_id, $products_or_cats_or_tags ) ) {
							return false;
						} elseif ( ! $validate_all_for_include && in_array( $product_term->term_id, $products_or_cats_or_tags ) ) {
							return true;
						}
					}
					break;
			}
		}
		return $validate_all_for_include;
	}

	/**
	 * get_condition_options.
	 *
	 * @version 3.5.0
	 * @since   3.2.0
	 */
	function get_condition_options( $options_id ) {
		switch( $options_id ) {
			case 'products':
				return ( 'yes' === get_option( 'wcj_shipping_by_' . $options_id . '_add_variations_enabled', 'no' ) ? wcj_get_products( array(), 'any', 256, true ) : wcj_get_products() );
			case 'product_cats':
				return wcj_get_terms( 'product_cat' );
			case 'product_tags':
				return wcj_get_terms( 'product_tag' );
		}
	}

	/**
	 * get_additional_section_settings.
	 *
	 * @version 3.5.0
	 * @since   3.2.1
	 */
	function get_additional_section_settings( $options_id ) {
		$return = array(
			array(
				'title'    => __( '"Include" Options', 'woocommerce-jetpack' ),
				'desc_tip' => __( 'Enable this checkbox if you want all products in cart to be valid (instead of at least one).', 'woocommerce-jetpack' ),
				'desc'     => __( 'Validate all', 'woocommerce-jetpack' ),
				'id'       => 'wcj_shipping_by_' . $options_id . '_validate_all_enabled',
				'type'     => 'checkbox',
				'default'  => 'no',
			),
		);
		if ( 'products' === $options_id ) {
			$return[] = array(
				'title'    => __( 'Add Products Variations', 'woocommerce-jetpack' ),
				'desc_tip' => __( 'Enable this checkbox if you want to add products variations to the products list.', 'woocommerce-jetpack' ) . ' ' .
					__( 'Save changes after enabling this option.', 'woocommerce-jetpack' ),
				'desc'     => __( 'Add', 'woocommerce-jetpack' ),
				'id'       => 'wcj_shipping_by_' . $options_id . '_add_variations_enabled',
				'type'     => 'checkbox',
				'default'  => 'no',
			);
		}
		return $return;
	}

}

endif;

return new WCJ_Shipping_By_Products();
