<?php

namespace AutomateWoo;

/**
 * @class Cart_Item
 * @since 3.2.6
 */
class Cart_Item {

	/** @var string */
	protected $key;

	/** @var array */
	protected $data;


	/**
	 * @param string $key
	 * @param array $data
	 */
	function __construct( $key, $data ) {
		$this->key = $key;
		$this->data = is_array( $data ) ? $data : [];
	}


	/**
	 * @return \WC_Product
	 */
	function get_product() {
		return wc_get_product( $this->get_variation_id() ? $this->get_variation_id() : $this->get_product_id() );
	}


	/**
	 * @return string
	 */
	function get_name() {
		if ( $product = $this->get_product() ) {
			return apply_filters( 'woocommerce_cart_item_name', Compat\Product::get_name( $product ), $this->data, $this->key );
		}
		return '';
	}


	/**
	 * @return int
	 */
	function get_key() {
		return $this->key;
	}


	/**
	 * @return array
	 */
	function get_data() {
		return $this->data;
	}


	/**
	 * @return array
	 */
	function get_variation_data() {
		return isset( $this->data['variation'] ) ? $this->data['variation'] : [];
	}


	/**
	 * @return int
	 */
	function get_product_id() {
		return isset( $this->data['product_id'] ) ? Clean::id( $this->data['product_id'] ) : 0;
	}


	/**
	 * @param $id
	 */
	function set_product_id( $id ) {
		$this->data['product_id'] = Clean::id( $id );
	}


	/**
	 * @return int
	 */
	function get_variation_id() {
		return isset( $this->data['variation_id'] ) ? Clean::id( $this->data['variation_id'] ) : 0;
	}


	/**
	 * @param $id
	 */
	function set_variation_id( $id ) {
		$this->data['variation_id'] = Clean::id( $id );
	}


	/**
	 * @return float
	 */
	function get_line_subtotal() {
		return isset( $this->data['line_subtotal'] ) ? floatval( $this->data['line_subtotal'] ) : 0;
	}


	/**
	 * @return float
	 */
	function get_line_subtotal_tax() {
		return isset( $this->data['line_subtotal_tax'] ) ? floatval( $this->data['line_subtotal_tax'] ) : 0;
	}


	/**
	 * @return int
	 */
	function get_quantity() {
		return isset( $this->data['quantity'] ) ? absint( $this->data['quantity'] ) : 0;
	}


	/**
	 * Gets and formats a list of cart item data + variations for display on the frontend.
	 *
	 * @param bool $flat (default: false)
	 * @return string
	 */
	public function get_item_data_html( $flat = false ) {
		$item_data = [];

		foreach ( $this->get_variation_data() as $name => $value ) {
			$taxonomy = wc_attribute_taxonomy_name( str_replace( 'attribute_pa_', '', urldecode( $name ) ) );

			// If this is a term slug, get the term's nice name
			if ( taxonomy_exists( $taxonomy ) ) {
				$term = get_term_by( 'slug', $value, $taxonomy );
				if ( ! is_wp_error( $term ) && $term && $term->name ) {
					$value = $term->name;
				}
				$label = wc_attribute_label( $taxonomy );

				// If this is a custom option slug, get the options name.
			} else {
				$value = apply_filters( 'woocommerce_variation_option_name', $value );
				$label = wc_attribute_label( str_replace( 'attribute_', '', $name ), $this->get_product() );
			}

			// WC 3.0 only
			if ( ! version_compare( WC()->version, '3.0', '<' ) ) {
				// Check the nicename against the title.
				if ( '' === $value || wc_is_attribute_in_product_name( $value, $this->get_name() ) ) {
					continue;
				}
			}

			$item_data[] = [
				'key'   => $label,
				'value' => $value,
			];
		}

		// Filter item data to allow 3rd parties to add more to the array
		$item_data = apply_filters( 'woocommerce_get_item_data', $item_data, $this->data );

		// Format item data ready to display
		foreach ( $item_data as $key => $data ) {
			// Set hidden to true to not display meta on cart.
			if ( ! empty( $data['hidden'] ) ) {
				unset( $item_data[ $key ] );
				continue;
			}
			$item_data[ $key ]['key']     = ! empty( $data['key'] ) ? $data['key'] : $data['name'];
			$item_data[ $key ]['display'] = ! empty( $data['display'] ) ? $data['display'] : $data['value'];
		}

		// Output flat or in list format
		if ( sizeof( $item_data ) > 0 ) {
			ob_start();

			if ( $flat ) {
				foreach ( $item_data as $data ) {
					echo esc_html( wp_strip_all_tags( $data['key'] ) ) . ': ' . wp_kses_post( $data['display'] ) . "\n";
				}
			} else {
				wc_get_template( 'cart/cart-item-data.php', [ 'item_data' => $item_data ] );
			}

			return ob_get_clean();
		}

		return '';
	}



}