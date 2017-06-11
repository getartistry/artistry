<?php

abstract class WoocommerceGpfAbstractCacheRebuildJob extends WP_Background_Process {

	/**
	 * Array of feed formats which will be rebuilt.
	 */
	private $feed_formats;

	/**
	 * Instances of the feed handling classes.
	 */
	private $feed_handlers;

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct();
		$this->cache        = new WoocommerceGpfCache();
		$this->factory      = new WC_Product_Factory();
		$this->feed_formats = array( 'google', 'googleinventory', 'bing' );
		$this->feed_handlers['bing']            = new WoocommerceGpfFeedBing();
		$this->feed_handlers['google']          = new WoocommerceGpfFeedGoogle();
		$this->feed_handlers['googleinventory'] = new WoocommerceGpfFeedGoogleInventory();
	}

	protected function rebuild_item( $id ) {
		// Load the settings.
		$settings = get_option( 'woocommerce_gpf_config', array() );
		$woocommerce_product = wc_get_product( $id );

		switch ( $woocommerce_product->get_type() ) {
			case 'simple':
				return $this->process_simple_product( $woocommerce_product );
				break;
			case 'variable':
				if ( empty( $this->settings['include_variations'] ) ) {
					return $this->process_simple_product( $woocommerce_product );
				} else {
					return $this->process_variable_product( $woocommerce_product );
				}
				break;
			case 'composite':
				return $this->process_composite_product( $woocommerce_product );
				break;
			case 'bundle':
				return $this->process_bundle_product( $woocommerce_product );
				break;
			default:
				break;
		}
	}

	/**
	 * Process a simple product.
	 *
	 * @todo This is mostly a rough copy of the code in the frontend class. The
	 * logic could do with centralising.
	 */
	protected function process_simple_product( $woocommerce_product ) {

		foreach ( $this->feed_formats as $feed_format ) {
			// Construct the data for this item.
			$feed_item = new WoocommerceGpfFeedItem( $woocommerce_product, $feed_format );
			if ( $feed_item->is_excluded() ) {
				$this->cache->store( $feed_item->ID, $feed_format, '' );
				continue;
			}
			// Allow other plugins to modify the item before its rendered to the feed
			$feed_item = apply_filters( 'woocommerce_gpf_feed_item', $feed_item );
			$feed_item = apply_filters( 'woocommerce_gpf_feed_item_' . $feed_format, $feed_item );

			// Render it.
			$output = $this->feed_handlers[ $feed_format ]->render_item( $feed_item );

			// Store it to the cache.
			$this->cache->store( $feed_item->ID, $feed_format, $output );
		}
		return true;
	}

	/**
	 * Process a variable product.
	 *
	 * @todo This is mostly a rough copy of the code in the frontend class. The
	 * logic could do with centralising.
	 */
	protected function process_variable_product( $woocommerce_product ) {

		// Check if the whole product is excluded.
		$feed_item = new WoocommerceGpfFeedItem( $woocommerce_product, 'google' );
		if ( $feed_item->is_excluded() ) {
			foreach ( $this->feed_formats as $feed_format ) {
				$this->cache->store( $woocommerce_product->get_id(), $feed_format, '' );
			}
			return false;
		}

		$variations = $woocommerce_product->get_available_variations();
		foreach ( $this->feed_formats as $feed_format ) {
			$output     = '';
			foreach ( $variations as $variation ) {
				// Get the variation product.
				$variation_id      = $variation['variation_id'];
				$variation_product = $this->factory->get_product( $variation_id );
				$feed_item = new WoocommerceGpfFeedItem( $variation_product, $feed_format );
				// Skip to the next if this variation isn't to be included.
				if ( $feed_item->is_excluded() ) {
					continue;
				}
				// Allow other plugins to modify the item before its rendered to the feed
				$feed_item = apply_filters( 'woocommerce_gpf_feed_item', $feed_item );
				$feed_item = apply_filters( 'woocommerce_gpf_feed_item_' . $feed_format, $feed_item );

				// Render it.
				$output .= $this->feed_handlers[ $feed_format ]->render_item( $feed_item );
				$this->cache->store( $woocommerce_product->get_id(), $feed_format, $output );
			}
		}
		return true;
	}

	/**
	 * Process a composite product.
	 *
	 * @param  object  $woocommerce_product  WooCommerce Product Object
	 * @return bool                          True if one or more products were output, false
	 *                                       otherwise.
	 */
	protected function process_composite_product( $woocommerce_product ) {
		return $this->process_simple_product( $woocommerce_product );
	}

	/**
	 * Process a bundle product.
	 *
	 * @param  object  $woocommerce_product  WooCommerce Product Object
	 * @return bool                          True if one or more products were output, false
	 *                                       otherwise.
	 */
	protected function process_bundle_product( $woocommerce_product ) {
		return $this->process_simple_product( $woocommerce_product );
	}

}
