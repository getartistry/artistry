<?php

/**
 * Common class.
 *
 * Holds the config about what fields are available.
 */
class WoocommerceGpfCommon {

	private $settings = array();
	private $category_cache = array();
	public $product_fields = array();

	/**
	 * Constructor - set up the available product fields
	 *
	 * @access public
	 */
	function __construct() {

		$this->settings = get_option( 'woocommerce_gpf_config' );
		$this->product_fields = array(
			'availability' => array(
				'desc'        => __( 'Availability', 'woocommerce_gpf' ),
				'full_desc'   => __( 'What status to send for in stock items. Out of stock products will always show as "Out of stock" irrespective of this setting.', 'woocommerce_gpf' ),
				'callback'    => 'render_availability',
				'can_default' => true,
				'feed_types'  => array( 'google', 'googleinventory', 'bing' ),
			),

			'is_bundle' => array(
				'desc'        => __( 'Bundle indicator (is_bundle)', 'woocommerce_gpf' ),
				'full_desc'   => __( 'Allows you to indicate whether a product is a "bundle" of products.', 'woocommerce_gpf' ),
				'callback'    => 'render_is_bundle',
				'feed_types'  => array( 'google' ),
			),

			'availability_date' => array(
				'desc'        => __( 'Availability date', 'woocommerce_gpf' ),
				'full_desc'   => __( 'If you are accepting orders for products that are available for preorder, use this attribute to indicate when the product becomes available for delivery.', 'woocommerce_gpf' ),
				'callback'    => 'render_availability_date',
				'feed_types'  => array( 'google' ),
			 ),

			'condition' => array(
				'desc'        => __( 'Condition', 'woocommerce_gpf' ),
				'full_desc'   => __( 'Condition or state of items', 'woocommerce_gpf' ),
				'callback'    => 'render_condition',
				'can_default' => true,
				'feed_types'  => array( 'google', 'bing' ),
			 ),

			'brand' => array(
				'desc'            => __( 'Brand', 'woocommerce_gpf' ),
				'full_desc'       => __( 'Brand of the items', 'woocommerce_gpf' ),
				'can_default'     => true,
				'can_prepopulate' => true,
				'feed_types'      => array( 'google', 'bing' ),
				'google_len'      => 70,
				'max_values'      => 1,
			),

			'mpn' => array(
				'desc'            => __( 'Manufacturer Part Number (MPN)', 'woocommerce_gpf' ),
				'full_desc'       => __( 'This code uniquely identifies the product to its manufacturer', 'woocommerce_gpf' ),
				'feed_types'      => array( 'google', 'bing' ),
				'can_prepopulate' => true,
				'google_len'      => 70,
				'max_values'      => 1,
			),

			'product_type' => array(
				'desc'            => __( 'Product Type', 'woocommerce_gpf' ),
				'full_desc'       => __( 'Your category of the items', 'woocommerce_gpf' ),
				'callback'        => 'render_product_type',
				'can_default'     => true,
				'can_prepopulate' => true,
				'feed_types'      => array( 'google' ),
				'google_len'      => 750,
			),

			'google_product_category' => array(
				'desc'        => __( 'Google Product Category', 'woocommerce_gpf' ),
				'full_desc'   => __( "Google's category of the item", 'woocommerce_gpf' ),
				'callback'    => 'render_product_type',
				'can_default' => true,
				'feed_types'  => array( 'google' ),
				'google_len'  => 750,
			),

			'gtin' => array(
				'desc'            => __( 'Global Trade Item Number (GTIN)', 'woocommerce_gpf' ),
				'full_desc'       => __( 'Global Trade Item Numbers (GTINs) for your items. These identifiers include UPC (in North America), EAN (in Europe), JAN (in Japan), and ISBN (for books)', 'woocommerce_gpf' ),
				'feed_types'      => array( 'google' ),
				'can_prepopulate' => true,
				'google_len'      => 50,
				'multiple'        => true,
			),

			'gender' => array(
				'desc'        => __( 'Gender', 'woocommerce_gpf' ),
				'full_desc'   => __( 'Target gender for the item', 'woocommerce_gpf' ),
				'callback'    => 'render_gender',
				'can_default' => true,
				'feed_types'  => array( 'google' ),
			),

			'age_group' => array(
				'desc'        => __( 'Age Group', 'woocommerce_gpf' ),
				'full_desc'   => __( 'Target age group for the item', 'woocommerce_gpf' ),
				'callback'    => 'render_age_group',
				'can_default' => true,
				'feed_types'  => array( 'google' ),
			),

			'color' => array(
				'desc'            => __( 'Colour', 'woocommerce_gpf' ),
				'full_desc'       => __( "Item's Colour", 'woocommerce_gpf' ),
				'feed_types'      => array( 'google' ),
				'can_prepopulate' => true,
				'google_len'      => 100,
			),

			'size' => array(
				'desc'            => __( 'Size', 'woocommerce_gpf' ),
				'full_desc'       => __( 'Size of the items', 'woocommerce_gpf' ),
				'feed_types'      => array( 'google' ),
				'can_prepopulate' => true,
				'google_len'      => 100,
			),

			'size_type' => array(
				'desc'        => __( 'Size type', 'woocommerce_gpf' ),
				'full_desc'   => __( 'Size type of the items', 'woocommerce_gpf' ),
				'feed_types'  => array( 'google' ),
				'can_default' => true,
				'callback'    => 'render_size_type',
			),

			'size_system' => array(
				'desc'        => __( 'Size system', 'woocommerce_gpf' ),
				'full_desc'   => __( 'Size system', 'woocommerce_gpf' ),
				'feed_types'  => array( 'google' ),
				'can_default' => true,
				'callback'    => 'render_size_system',
			),

			'material' => array(
				'desc'            => __( 'Material', 'woocommerce_gpf' ),
				'full_desc'       => __( "Item's material", 'woocommerce_gpf' ),
				'feed_types'      => array( 'google' ),
				'can_prepopulate' => true,
				'google_len'      => 200,
			),

			'pattern' => array(
				'desc'            => __( 'Pattern', 'woocommerce_gpf' ),
				'full_desc'       => __( "Item's pattern", 'woocommerce_gpf' ),
				'feed_types'      => array( 'google' ),
				'can_prepopulate' => true,
				'google_len'      => 100,
			),

			'identifier_exists' => array(
				'desc'        => __( 'Identifier exists flag', 'woocommerce_gpf' ),
				'full_desc'   => __( "Whether to include 'Identifier exists - false' when products don't have the relevant identifiers", 'woocommerce_gpf' ),
				'callback'    => 'render_i_exists',
				'can_default' => true,
				'feed_types'  => array( 'google' ),
			),

			'adwords_grouping' => array(
				'desc'        => __( 'Adwords grouping filter', 'woocommerce_gpf' ),
				'full_desc'   => __( 'Used to group products in an arbitrary way. It can be used for Product Filters to limit a campaign to a group of products or Product Targets, to bid differently for a group of products. This is a required field if the advertiser wants to bid differently to different sub-sets of products in the CPC or CPA % version. It can only hold one value.', 'woocommerce_gpf' ),
				'can_default' => true,
				'feed_types'  => array( 'google' ),
			),

			'adwords_labels' => array(
				'desc'            => __( 'Adwords labels', 'woocommerce_gpf' ),
				'full_desc'       => __( 'Very similar to adwords_grouping, but it will only work on CPC. You can enter multiple values here, separating them with a comma (,). e.g. "widget,box".', 'woocommerce_gpf' ),
				'can_default'     => true,
				'can_prepopulate' => true,
				'feed_types'      => array( 'google' ),
				'multiple'        => true,
			),

			'bing_category' => array(
				'desc'        => __( 'Bing Category', 'woocommerce_gpf' ),
				'full_desc'   => __( "Bing's category of the item", 'woocommerce_gpf' ),
				'callback'    => 'render_b_category',
				'can_default' => true,
				'feed_types'  => array( 'bing' ),
			),

			'delivery_label' => array(
				'desc'            => __( 'Delivery label', 'woocommerce_gpf' ),
				'full_desc'       => __( 'You can use this to control which shipping rules from your Merchant Centre account are applied to this product.', 'woocommerce_gpf' ),
				'can_default'     => true,
				'can_prepopulate' => true,
				'callback'        => 'render_textfield',
				'feed_types'      => array( 'google' ),
				'google_len'      => 100,
			),

			'custom_label_0' => array(
				'desc'            => __( 'Custom label 0', 'woocommerce_gpf' ),
				'full_desc'       => __( 'Can be used to segment your products when setting up shopping campaigns in Adwords.', 'woocommerce_gpf' ),
				'can_default'     => true,
				'callback'        => 'render_textfield',
				'can_prepopulate' => true,
				'feed_types'      => array( 'google', 'bing' ),
				'google_len'      => 100,
				'max_values'      => 1,
			),

			'custom_label_1' => array(
				'desc'            => __( 'Custom label 1', 'woocommerce_gpf' ),
				'full_desc'       => __( 'Can be used to segment your products when setting up shopping campaigns in Adwords.', 'woocommerce_gpf' ),
				'can_default'     => true,
				'callback'        => 'render_textfield',
				'can_prepopulate' => true,
				'feed_types'      => array( 'google', 'bing' ),
				'google_len'      => 100,
				'max_values'      => 1,
			),

			'custom_label_2' => array(
				'desc'            => __( 'Custom label 2', 'woocommerce_gpf' ),
				'full_desc'       => __( 'Can be used to segment your products when setting up shopping campaigns in Adwords.', 'woocommerce_gpf' ),
				'can_default'     => true,
				'callback'        => 'render_textfield',
				'can_prepopulate' => true,
				'feed_types'      => array( 'google', 'bing' ),
				'google_len'      => 100,
				'max_values'      => 1,
			),

			'custom_label_3' => array(
				'desc'            => __( 'Custom label 3', 'woocommerce_gpf' ),
				'full_desc'       => __( 'Can be used to segment your products when setting up shopping campaigns in Adwords.', 'woocommerce_gpf' ),
				'can_default'     => true,
				'callback'        => 'render_textfield',
				'can_prepopulate' => true,
				'feed_types'      => array( 'google', 'bing' ),
				'google_len'      => 100,
				'max_values'      => 1,
			),

			'custom_label_4' => array(
				'desc'            => __( 'Custom label 4', 'woocommerce_gpf' ),
				'full_desc'       => __( 'Can be used to segment your products when setting up shopping campaigns in Adwords.', 'woocommerce_gpf' ),
				'can_default'     => true,
				'callback'        => 'render_textfield',
				'can_prepopulate' => true,
				'feed_types'      => array( 'google', 'bing' ),
				'google_len'      => 100,
				'max_values'      => 1,
			),

			'promotion_id' => array(
				'desc'            => __( 'Promotion ID', 'woocommerce_gpf' ),
				'full_desc'       => __( 'The unique ID of a promotion.' ),
				'can_default'     => true,
				'callback'        => 'render_textfield',
				'can_prepopulate' => true,
				'feed_types'      => array( 'google' ),
			),

		);

		$this->product_fields = apply_filters( 'woocommerce_gpf_all_product_fields', $this->product_fields );
	}



	/**
	 * Helper function to remove blank array elements
	 *
	 * @access public
	 * @param array $array The array of elements to filter
	 * @return array The array with blank elements removed
	 */
	private function remove_blanks( $array ) {
		if ( empty( $array ) || ! is_array( $array ) ) {
			return $array;
		}
		foreach ( array_keys( $array ) as $key ) {
			if ( empty( $array[ $key ] ) || empty( $this->settings['product_fields'][ $key ] ) ) {
				unset( $array[ $key ] );
			}
		}
		return $array;
	}



	/**
	 * Helper function to remove items not needed in this feed type
	 *
	 * @access public
	 * @param array $array The list of fields to be filtered
	 * @param string $feed_format The feed format that should have its fields maintained
	 * @return array The list of fields filtered to only contain elements that apply to the selected $feed_format
	 */
	private function remove_other_feeds( $array, $feed_format ) {
		if ( empty( $array ) || ! is_array( $array ) ) {
			return $array;
		}
		foreach ( array_keys( $array ) as $key ) {
			if ( empty( $this->product_fields[ $key ] ) || ! in_array( $feed_format, $this->product_fields[ $key ]['feed_types'] ) ) {
				unset( $array[ $key ] );
			}
		}
		return $array;
	}



	/**
	 * Retrieve the values that should be output for a particular variation.
	 *
	 * This function does *not* take into account defaults. You should merge the results of this
	 * with the return value of get_values_for_product() for the parent product, thus picking
	 * up defaults etc.
	 *
	 */
	public function get_values_for_variation( $variation_id, $feed_format = 'all' ) {
		$values = array();
		// Grab prepopulated data if required.
		if ( ! empty( $this->settings['product_prepopulate'] ) ) {
			$prepopulated_values = $this->get_values_to_prepopulate( $variation_id );
			$prepopulated_values = $this->remove_blanks( $prepopulated_values );
			$values              = array_merge( $values, $prepopulated_values );
		}

		// Merge per-product settings.
		$product_settings = get_post_meta( $variation_id, '_woocommerce_gpf_data', true );
		if ( $product_settings ) {
			$product_settings = $this->remove_blanks( $product_settings );
			$values = array_merge( $values, $product_settings );
		}
		if ( 'all' !== $feed_format ) {
			$values = $this->remove_other_feeds( $values, $feed_format );
		}
		$values = $this->limit_max_values( $values );
		return $values;
	}

	/**
	 * Retrieve the values that should be output for a particular product
	 * Takes into account store defaults, category defaults, and per-product
	 * settings
	 *
	 * @access public
	 * @param  int  $product_id       The ID of the product to retrieve info for
	 * @param  string  $feed_format   The feed format being generated
	 * @param  boolean $defaults_only Whether to retrieve the
							*         store/category defaults only
	 * @return array                  The values for the product
	 */
	public function get_values_for_product( $product_id = null, $feed_format = 'all', $defaults_only = false ) {

		if ( ! $product_id ) {
			return false;
		}

		// Get Store defaults.
		if ( ! isset( $this->settings['product_defaults'] ) ) {
			$this->settings['product_defaults'] = array();
		}
		$settings = $this->remove_blanks( $this->settings['product_defaults'] );
		// Merge category settings.
		$categories = get_the_terms( $product_id, 'product_cat' );
		if ( false === $categories ) {
			$categories = array();
		}
		foreach ( $categories as $category ) {
			$category_id = $category->term_id;
			$category_settings = $this->get_values_for_category( $category_id );
			$category_settings = $this->remove_blanks( $category_settings );
			if ( 'all' !== $feed_format ) {
				$category_settings = $this->remove_other_feeds( $category_settings, $feed_format );
			}
			if ( $category_settings ) {
				$settings = array_merge( $settings, $category_settings );
			}
		}

		if ( $defaults_only ) {
			return $settings;
		}

		// Merge pre-populated data if required.
		if ( ! empty( $this->settings['product_prepopulate'] ) ) {
			$prepopulated_values = $this->get_values_to_prepopulate( $product_id );
			$prepopulated_values = $this->remove_blanks( $prepopulated_values );
			$settings            = array_merge( $settings, $prepopulated_values );
		}
		// Merge per-product settings.
		$product_settings = get_post_meta( $product_id, '_woocommerce_gpf_data', true );
		if ( $product_settings ) {
			$product_settings = $this->remove_blanks( $product_settings );
			$settings = array_merge( $settings, $product_settings );
		}

		if ( 'all' !== $feed_format ) {
			$settings = $this->remove_other_feeds( $settings, $feed_format );
		}
		$settings = $this->limit_max_values( $settings );

		return $settings;
	}


	/**
	 * Make sure that each element does not contain more values than it should.
	 *
	 * @param   array   $data  The data for a product / category.
	 * @return                 The modified data array.
	 */
	private function limit_max_values( $data ) {
		foreach ( $this->product_fields as $key => $element_settings ) {
			if ( empty( $element_settings['max_values'] ) ||
				 empty( $data[ $key ] ) ||
				 ! is_array( $data[ $key ] ) ) {
				continue;
			}
			$limit = intval( $element_settings['max_values'] );
			$data[ $key ] = array_slice( $data[ $key ], 0, $limit );
		}
		return $data;
	}

	/**
	 * Retrieve category defaults for a specific category
	 *
	 * @access public
	 * @param  int $category_id The category ID to retrieve information for
	 * @return array            The category data
	 */
	private function get_values_for_category( $category_id ) {
		if ( ! $category_id ) {
			return false;
		}
		if ( isset( $this->category_cache[ $category_id ] ) ) {
			return $this->category_cache[ $category_id ];
		}
		$values = get_woocommerce_term_meta( $category_id, '_woocommerce_gpf_data', true );
		$this->category_cache[ $category_id ] = &$values;
		return $this->category_cache[ $category_id ];

	}

	/**
	 * Get all of the prepopulated values for a product.
	 *
	 * @param  int    $product_id  The product ID.
	 *
	 * @return array               Array of prepopulated values.
	 */
	private function get_values_to_prepopulate( $product_id = null ) {
		$results = array();
		foreach ( $this->settings['product_prepopulate'] as $gpf_key => $prepopulate ) {

			if ( empty( $prepopulate ) ) {
				continue;
			}
			$value = $this->get_prepopulate_value_for_product( $prepopulate, $product_id );
			if ( ! empty( $value ) ) {
				$results[ $gpf_key ] = $value;
			}
		}
		return $results;
	}

	/**
	 * Gets a specific prepopulated value for a product.
	 *
	 * @param  string  $prepopulate  The prepopulation value for a product.
	 * @param  int     $product_id   The product ID being queried.
	 *
	 * @return string                The prepopulated value for this product.
	 */
	private function get_prepopulate_value_for_product( $prepopulate, $product_id ) {

		global $woocommerce_gpf_frontend;

		$result = array();
		list( $type, $value ) = explode( ':', $prepopulate );
		switch ( $type ) {
			case 'tax':
				$result = $this->get_tax_prepopulate_value_for_product( $value, $product_id );
				break;
			case 'field':
				$result = $this->get_field_prepopulate_value_for_product( $value, $product_id );
				break;
		}
		return $result;
	}

	/**
	 * Gets a taxonomy value for a product to prepopulate.
	 *
	 * @param  string $value      The taxonomy to grab values for.
	 * @param  int    $product_id The product, or variation ID.
	 * @return array              Array of values to use.
	 */
	private function get_tax_prepopulate_value_for_product( $value, $product_id ) {

		global $woocommerce_gpf_frontend;

		$result = array();
		$product = wc_get_product( $product_id );

		if ( is_callable( array( $product, 'get_type' ) ) ) {
			$product_type = $product->get_type();
		} else {
			$product_type = $product->product_type;
		}
		if ( 'variation' === $product_type ) {
			// Get the attributes.
			$attributes = $product->get_variation_attributes();
			// If the requested taxonomy is used as an attribute, grab it's value for this variation.
			if ( ! empty( $attributes[ 'attribute_' . $value ] ) ) {
				$terms = get_terms( array(
					'taxonomy' => $value,
					'slug'     => $attributes[ 'attribute_' . $value ],
				) );
				if ( empty( $terms ) || is_wp_error( $terms ) ) {
					$result = array();
				} else {
					$result = array( $terms[0]->name );
				}
			} else {
				// Otherwise grab the values to use direct from the term relationships.
				$terms = get_the_terms( $product_id, $value );
				if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
					$result = wp_list_pluck( $terms, 'name' );
				} else {
					// Couldn't find it against the variation - grab the parent product value.
					if ( is_callable( array( $product, 'get_parent_id' ) ) ) {
						$terms = get_the_terms( $product->get_parent_id(), $value );
					} else {
						$terms = get_the_terms( $product->parent->id, $value );
					}
					if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
						$result = wp_list_pluck( $terms, 'name' );
					}
				}
			}
		} else {
			// Get the term(s) tagged against the main product.
			$terms = get_the_terms( $product_id, $value );
			if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
				$result = wp_list_pluck( $terms, 'name' );
			}
		}
		return $result;
	}

	/**
	 * Get a prepopulate value for a specific field for a product.
	 *
	 * @param  string  $field       Details of the field we want.
	 * @param  int     $product_id  The product ID.
	 *
	 * @return array                The value for this field on this product.
	 */
	private function get_field_prepopulate_value_for_product( $field, $product_id ) {

		global $woocommerce_gpf_frontend;

		$product = wc_get_product( $product_id );
		if ( ! $product ) {
			return array();
		}

		if ( 'sku' == $field ) {
			$sku = $product->get_sku();
			if ( ! empty( $sku ) ) {
				return array( $sku );
			}
		}

		return array();
	}

	/**
	 * Generate a list of choices for the "prepopulate" options.
	 *
	 * @return array  An array of preopulate choices.
	 */
	public function get_prepopulate_options() {
		$options = array();
		$options = array_merge( $options, $this->get_available_taxonomies() );
		$options = array_merge( $options, $this->get_prepopulate_fields() );
		return $options;
	}

	/**
	 * get a list of the available fields to use for prepopulation.
	 *
	 * @return array  Array of the available fields.
	 */
	private function get_prepopulate_fields() {
		$fields = array(
			'field:sku' => 'SKU',
		);
		asort( $fields );
		return array_merge( array( 'disabled:fields' => __( '- Product fields -', 'woo_gpf' ) ), $fields );
	}

	/**
	 * Get a list of the available taxonomies.
	 *
	 * @return array Array of available product taxonomies.
	 */
	private function get_available_taxonomies() {
		$taxonomies = get_object_taxonomies( 'product' );
		$taxes = array();
		foreach ( $taxonomies as $taxonomy ) {
			$tax_details = get_taxonomy( $taxonomy );
			$taxes[ 'tax:' . $taxonomy ] = $tax_details->labels->name;
		}
		asort( $taxes );
		return array_merge( array( 'disabled:taxes' => __( '- Taxonomies -', 'woo_gpf' ) ), $taxes );
	}
}

global $woocommerce_gpf_common;
$woocommerce_gpf_common = new WoocommerceGpfCommon();
