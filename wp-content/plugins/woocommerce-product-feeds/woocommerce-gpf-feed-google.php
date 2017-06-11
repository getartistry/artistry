<?php

/**
 * Google feed class - renders the Google feed.
 */
class WoocommerceGpfFeedGoogle extends WoocommerceGpfFeed {

	private $tax_excluded  = false;
	private $tax_attribute = false;

	/**
	 * Constructor. Grab the settings, and add filters if we have stuff to do
	 *
	 * @access public
	 */
	function __construct() {
		parent::__construct();
		$this->store_info->feed_url = add_query_arg( 'woocommerce_gpf', 'google', $this->store_info->feed_url_base );
		if ( ! empty( $this->store_info->base_country ) ) {
			if ( 'US' === substr( $this->store_info->base_country, 0, 2 ) ||
			     'CA' === substr( $this->store_info->base_country, 0, 2 ) ||
			     'IN' === substr( $this->store_info->base_country, 0, 2 ) ) {
				$this->tax_excluded = true;
				if ( 'US' === substr( $this->store_info->base_country, 0, 2 ) ) {
					$this->tax_attribute = true;
				}
			}
		}
		add_filter( 'woocommerce_gpf_feed_item_google', array( $this, 'enforce_max_lengths' ) );
	}

	/**
	 * Generate a simple list of field and max length from the field config array.
	 *
	 * @return array  Array of max lengths, keyed on field name.
	 */
	private function get_field_max_lengths() {

		global $woocommerce_gpf_common;

		static $max_lengths = array();
		if ( ! empty( $max_lengths ) ) {
			return $max_lengths;
		}
		// Max lengths for core fields
		$max_lengths['title'] = 150;
		$max_lengths['description'] = 5000;
		// Max lengths for non-core fields
		foreach ( $woocommerce_gpf_common->product_fields as $field_name => $field_config ) {
			if ( isset( $field_config['google_len'] ) ) {
				$max_lengths[ $field_name ] = $field_config['google_len'];
			}
		}
		return $max_lengths;
	}

	/**
	 * Enforce maximum lengths of fields in the Google field.
	 */
	public function enforce_max_lengths( $feed_item ) {
		$max_lengths = $this->get_field_max_lengths();
		foreach ( $max_lengths as $field_name => $length ) {
			if ( ! empty( $feed_item->$field_name ) ) {
				$feed_item->$field_name = mb_substr( $feed_item->$field_name, 0, $length );
			}
			if ( ! empty( $feed_item->additional_elements[ $field_name ] ) ) {
				foreach ( $feed_item->additional_elements[ $field_name ] as $key => $value ) {
					$feed_item->additional_elements[ $field_name ][ $key ] = mb_substr( $value, 0, $length );
				}
			}
		}
		return $feed_item;
	}

	/**
	 * Figure out if the item has the identifiers it requires
	 *
	 * @param  object  $item The item being rendered
	 *
	 * @return boolean       True if the item doens't need identifiers, or has the required
	 *                       identifiers. False if not.
	 */
	private function has_identifier( $item ) {
		if ( empty( $item->additional_elements['brand'] ) ) {
			return false;
		}
		if ( empty( $item->additional_elements['gtin'] ) &&
			 empty( $item->additional_elements['mpn'] ) ) {
			return false;
		}
		return true;
	}


	/**
	 * Render the feed header information
	 *
	 * @access public
	 */
	function render_header() {

		header( 'Content-Type: application/xml; charset=UTF-8' );
		if ( isset( $_REQUEST['feeddownload'] ) ) {
			header( 'Content-Disposition: attachment; filename="E-Commerce_Product_List.xml"' );
		} else {
			header( 'Content-Disposition: inline; filename="E-Commerce_Product_List.xml"' );
		}

		// Core feed information
		echo "<?xml version='1.0' encoding='UTF-8' ?>\n";
		echo "<rss version='2.0' xmlns:atom='http://www.w3.org/2005/Atom' xmlns:g='http://base.google.com/ns/1.0'>\n";
		echo "  <channel>\n";
		echo '    <title><![CDATA[' . $this->store_info->blog_name . " Products]]></title>\n";
		echo '    <link>' . $this->store_info->site_url . "</link>\n";
		echo "    <description>This is the WooCommerce Product List RSS feed</description>\n";
		echo "    <generator>WooCommerce Google Product Feed Plugin (http://plugins.leewillis.co.uk/store/plugins/woocommerce-google-product-feed/)</generator>\n";
		echo "    <atom:link href='" . esc_url( $this->store_info->feed_url ) . "' rel='self' type='application/rss+xml' />\n";

	}


	/**
	 * Generate the output for an individual item, and return it
	 *
	 * @access public
	 * @param  object $feed_item  The information about the item.
	 *
	 *@return  string             The rendered output for this item.
	 */
	function render_item( $feed_item ) {
		// Google do not allow free items in the feed.
		if ( empty ( $feed_item->price_inc_tax ) ) {
			return '';
		}
		// Remove non-printable UTF-8 / CDATA escaping
		$title    = preg_replace(
			'/[\x00-\x08\x0B\x0C\x0E-\x1F\x80-\x9F]/u',
			'',
			$feed_item->title
		);
		$title = str_replace( ']]>', ']]]]><![CDATA[>', $title );
		$product_description = preg_replace(
			'/[\x00-\x08\x0B\x0C\x0E-\x1F\x80-\x9F]/u',
			'',
			$feed_item->description
		);
		$product_description = str_replace( ']]>', ']]]]><![CDATA[>', $product_description );
		$escaped_url = apply_filters( 'woocommerce_gpf_feed_item_escaped_url', esc_url( $feed_item->purchase_link ), $feed_item );

		$output = '';
		$output .= "    <item>\n";
		$output .= '      <title><![CDATA[' . $title . "]]></title>\n";
		$output .= '      <link>' . $escaped_url . "</link>\n";
		$output .= '      <g:ID>' . $feed_item->guid . "</g:ID>\n";
		$output .= '      <description><![CDATA[' . $product_description . "]]></description>\n";

		if ( ! empty( $feed_item->image_link ) ) {
			$output .= '      <g:image_link><![CDATA[' . $feed_item->image_link . "]]></g:image_link>\n";
		}

		$output .= $this->render_prices( $feed_item );

		$cnt = 0;
		if ( apply_filters( 'woocommerce_gpf_google_additional_images', true ) ) {
			foreach ( $feed_item->additional_images as $image_url ) {
				// Google limit the number of additional images to 10
				if ( 10 == $cnt ) {
					break;
				}
				$output .= '      <g:additional_image_link><![CDATA[' . $image_url . "]]></g:additional_image_link>\n";
				$cnt++;
			}
		}

		$done_condition = false;
		$done_weight = false;

		if ( count( $feed_item->additional_elements ) ) {
			foreach ( $feed_item->additional_elements as $element_name => $element_values ) {
				foreach ( $element_values as $element_value ) {
					if ( 'availability' === $element_name ) {
						// Google no longer supports "available for order". Mapped this to "in stock" as per
						// specification update September 2014.
						if ( 'available for order' === $element_value ) {
							$element_value = 'in stock';
						}
						// Only send the value if the product is in stock, otherwise force to
						// "out of stock".
						if ( ! $feed_item->is_in_stock ) {
							$element_value = 'out of stock';
						}
					}
					if ( 'identifier_exists' === $element_name ) {
						if ( 'included' === $element_value ) {
							if ( ! $this->has_identifier( $feed_item ) ) {
								$output .= ' <g:identifier_exists>FALSE</g:identifier_exists>';
							}
							continue;
						} else {
							continue;
						}
					}
					if ( 'availability_date' === $element_name ) {
						if ( strlen( $element_value ) === 10 ) {
							$tz_offset = get_option( 'gmt_offset' );
							$element_value .= 'T00:00:00' . sprintf( '%+03d', $tz_offset ) . '00';
						}
					}
					if ( 'is_bundle' === $element_name ) {
						if ( 'on' === $element_value ) {
							$output .= " <g:is_bundle>TRUE</g:is_bundle>\n";
						}
						continue;
					}
					$output .= '      <g:' . $element_name . '>';
					$output .= '<![CDATA[' . $element_value . ']]>';
					$output .= '</g:' . $element_name . ">\n";

				}

				if ( 'shipping_weight' === $element_name ) {
					$done_weight = true;
				}

				if ( 'condition' === $element_name ) {
					$done_condition = true;
				}
			}
		}

		if ( ! $done_condition ) {
			$output .= "      <g:condition>new</g:condition>\n";
		}

		if ( ! $done_weight ) {
			$weight = apply_filters( 'woocommerce_gpf_shipping_weight', $feed_item->shipping_weight, $feed_item->ID );
			if ( 'lbs' === $this->store_info->weight_units ) {
				$weight_units = 'lb';
			} else {
				$weight_units = $this->store_info->weight_units;
			}
			if ( $weight && is_numeric( $weight ) && $weight > 0 ) {
				$output .= "      <g:shipping_weight>$weight $weight_units</g:shipping_weight>\n";
			}
		}
		$output .= "    </item>\n";
		return $output;
	}

	/**
	 * Render the applicable price elements.
	 *
	 * @param  object $feed_item The feed item to be rendered.
	 */
	private function render_prices( $feed_item ) {

		// Regular price
		if ( $this->tax_excluded ) {
			// Some country's prices have to be submitted excluding tax.
			$price = number_format( $feed_item->regular_price_ex_tax, 2, '.', '' );
		} else {
			// Non-US prices have to be submitted including tax
			$price = number_format( $feed_item->regular_price_inc_tax, 2, '.', '' );
		}
		$output = '      <g:price>' . $price . ' ' . $this->store_info->currency . "</g:price>\n";

		// If there's no sale price, then we're done.
		if ( empty( $feed_item->sale_price_inc_tax ) ) {
			return $output;
		}

		// Otherwise, include the sale_price tag.
		if ( $this->tax_excluded ) {
			// US prices have to be submitted excluding tax.
			$sale_price = number_format( $feed_item->sale_price_ex_tax, 2, '.', '' );
		} else {
			$sale_price = number_format( $feed_item->sale_price_inc_tax, 2, '.', '' );
		}
		$output .= '      <g:sale_price>' . $sale_price . ' ' . $this->store_info->currency . "</g:sale_price>\n";

		// Include start / end dates if provided.
		if ( ! empty( $feed_item->sale_price_start_date ) &&
			 ! empty( $feed_item->sale_price_end_date ) ) {
			if ( is_object( $feed_item->sale_price_start_date ) ) {
				// WC >= 3.0
				$effective_date = (string) $feed_item->sale_price_start_date;
				$effective_date .= '/';
				$effective_date .= (string) $feed_item->sale_price_end_date;
			} else {
				// WC < 3.0
				$offset = get_option( 'gmt_offset' );
				$offset_string = sprintf( '%+03d', $offset );
				$offset_string .= sprintf( '%02d', ( $offset - floor( $offset ) ) * 60 );
				$effective_date = date( 'Y-m-d\TH:i', $feed_item->sale_price_start_date ) . $offset_string;
				$effective_date .= '/';
				$effective_date .= date( 'Y-m-d\TH:i', $feed_item->sale_price_end_date ) . $offset_string;
			}
			$output .= '      <g:sale_price_effective_date>' . $effective_date . '</g:sale_price_effective_date>';
		}
		return $output;
	}

	/**
	 * Output the feed footer
	 *
	 * @access public
	 */
	function render_footer() {
		echo "  </channel>\n";
		echo '</rss>';
		exit();
	}
}
