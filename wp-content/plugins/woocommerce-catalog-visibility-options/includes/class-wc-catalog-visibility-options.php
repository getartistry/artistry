<?php

class WC_CVO_Visibility_Options {

	public function __construct() {
		add_action( 'woocommerce_init', array($this, 'on_woocommerce_init') );
	}

	public function on_woocommerce_init() {
		global $wc_cvo;

		if ( $wc_cvo->setting( 'wc_cvo_prices' ) != 'enabled' || $wc_cvo->setting( 'wc_cvo_atc' ) != 'enabled' ) {

			if ( ($wc_cvo->setting( 'wc_cvo_prices' ) == 'secured' && !catalog_visibility_user_has_access()) || $wc_cvo->setting( 'wc_cvo_prices' ) == 'disabled' ) {
				add_filter( 'woocommerce_grouped_price_html', array($this, 'on_price_html'), 100, 2 );
				add_filter( 'woocommerce_variable_price_html', array($this, 'on_price_html'), 100, 2 );
				add_filter( 'woocommerce_sale_price_html', array($this, 'on_price_html'), 100, 2 );
				add_filter( 'woocommerce_price_html', array($this, 'on_price_html'), 100, 2 );
				add_filter( 'woocommerce_empty_price_html', array($this, 'on_price_html'), 100, 2 );

				add_filter( 'woocommerce_variable_sale_price_html', array($this, 'on_price_html'), 100, 2 );
				add_filter( 'woocommerce_variable_free_sale_price_html', array($this, 'on_price_html'), 100, 2 );
				add_filter( 'woocommerce_variable_free_price_html', array($this, 'on_price_html'), 100, 2 );
				add_filter( 'woocommerce_variable_empty_price_html', array($this, 'on_price_html'), 100, 2 );

				add_filter( 'woocommerce_free_sale_price_html', array($this, 'on_price_html'), 100, 2 );
				add_filter( 'woocommerce_free_price_html', array($this, 'on_price_html'), 100, 2 );

				add_filter( 'woocommerce_variable_subscription_price_html', array($this, 'on_price_html'), 100, 2);

				if ( wc_is_21x() ) {
					//2.1x
					add_filter( 'woocommerce_cart_item_price', array($this, 'on_cart_item_price_html'), 100, 2 );
				} else {
					//2.0x
					add_filter( 'woocommerce_cart_item_price_html', array($this, 'on_cart_item_price_html'), 100, 2 );
				}

				add_filter('wc_get_template', array($this, 'on_get_variation_template'), 99, 2);

			}

			//Configure replacement HTML and content.  
			//Note:  If prices are disabled, and purchases are enabled, the alternate add-to-cart button content will still be used. 
			//       Add to cart only makes sense when prices are visibile. 
			if (
				(($wc_cvo->setting( 'wc_cvo_atc' ) == 'secured' && !catalog_visibility_user_has_access()) || $wc_cvo->setting( 'wc_cvo_atc' ) == 'disabled') ||
				(($wc_cvo->setting( 'wc_cvo_prices' ) == 'secured' && !catalog_visibility_user_has_access()) || $wc_cvo->setting( 'wc_cvo_prices' ) == 'disabled')
			) {

				//Since 2.7.2
				//Hook into cart validation to disallow items getting added to the cart. 
				add_filter( 'woocommerce_add_to_cart_validation', array($this, 'on_woocommerce_add_to_cart_validation'), 10, 2 );



				//Bulk variations compatibility
				add_filter( 'woocommerce_bv_render_form', '__return_false' );

				//Wishlist compatibility
				add_action( 'woocommerce_wishlist_user_can_purcahse', array($this, 'on_wishlist_user_can_purcahse'), 100, 2 );



				add_action( 'woocommerce_before_booking_form', array($this, 'on_before_booking_form'), 1 );

				add_action( 'woocommerce_before_add_to_cart_button', array($this, 'on_before_add_to_cart_button'), 1 );
				add_action( 'woocommerce_after_add_to_cart_button', array($this, 'on_after_add_to_cart_button'), 999 );



				remove_shortcode( 'woocommerce_cart' );
				remove_shortcode( 'woocommerce_checkout' );
				remove_shortcode( 'woocommerce_order_tracking' );

				add_shortcode( 'woocommerce_cart', array($this, 'get_woocommerce_cart') );
				add_shortcode( 'woocommerce_checkout', array($this, 'get_woocommerce_checkout') );
				add_shortcode( 'woocommerce_order_tracking', array($this, 'get_woocommerce_order_tracking') );
			}
		}
	}

	/**
	 * This is hooked when the user can not view prices.
	 * @param $located
	 * @param $template_name
	 * @param $args
	 * @param $template_path
	 * @param $default_path
	 *
	 * @return string
	 */
	public function on_get_variation_template( $located, $template_name ) {
		global $wc_cvo;

		if ( $template_name == 'single-product/add-to-cart/variation.php' ) {
			$located = $wc_cvo->plugin_dir() . '/templates/variation.php';
		}

		return $located;
	}

	/*
	 * Replacement Shortcodes
	 */

	public function get_woocommerce_cart( $atts ) {
		global $woocommerce;
		return $woocommerce->shortcode_wrapper( array($this, 'alternate_single_product_content'), $atts );
	}

	public function get_woocommerce_checkout( $atts ) {
		global $woocommerce;
		return $woocommerce->shortcode_wrapper( array($this, 'alternate_single_product_content'), $atts );
	}

	public function get_woocommerce_order_tracking( $atts ) {
		global $woocommerce;
		return $woocommerce->shortcode_wrapper( array($this, 'alternate_single_product_content'), $atts );
	}

	public function alternate_single_product_content( $atts ) {
		global $wc_cvo;

		$html = '';

		if ( ($wc_cvo->setting( 'wc_cvo_prices' ) == 'secured' && !catalog_visibility_user_has_access()) || $wc_cvo->setting( 'wc_cvo_prices' ) == 'disabled' ) {
			$html = apply_filters( 'catalog_visibility_alternate_content', apply_filters( 'the_content', $wc_cvo->setting( 'wc_cvo_s_price_text' ) ) );
		} elseif ( ($wc_cvo->setting( 'wc_cvo_atc' ) == 'secured' && !catalog_visibility_user_has_access()) || $wc_cvo->setting( 'wc_cvo_atc' ) == 'disabled' ) {
			$html = apply_filters( 'catalog_visibility_alternate_content', apply_filters( 'the_content', $wc_cvo->setting( 'wc_cvo_s_price_text' ) ) );
		}

		echo $html;
	}

	/*
	 * Replacement HTML
	 */

	public function on_price_html( $html, $product ) {
		global $wc_cvo;
		if ( !WC_Catalog_Restrictions_Filters::instance()->user_can_view_price( $product ) ) {
			if ( ($wc_cvo->setting( 'wc_cvo_prices' ) == 'secured' && !catalog_visibility_user_has_access()) || $wc_cvo->setting( 'wc_cvo_prices' ) == 'disabled' ) {
				return apply_filters( 'catalog_visibility_alternate_price_html', do_shortcode( wptexturize( $wc_cvo->setting( 'wc_cvo_c_price_text' ) ) ) );
			}
		}

		return $html;
	}

	public function on_cart_item_price_html( $price, $cart_item ) {
		global $wc_cvo;
		$product = $cart_item['data'];

		if ( !WC_Catalog_Restrictions_Filters::instance()->user_can_view_price( $product ) ) {
			if ( ($wc_cvo->setting( 'wc_cvo_prices' ) == 'secured' && !catalog_visibility_user_has_access()) || $wc_cvo->setting( 'wc_cvo_prices' ) == 'disabled' ) {
				return apply_filters( 'catalog_visibility_alternate_price_html', do_shortcode( wptexturize( $wc_cvo->setting( 'wc_cvo_c_price_text' ) ) ) );
			}
		}

		return $price;
	}

	public function on_before_booking_form() {
		remove_action( 'woocommerce_before_add_to_cart_button', array($this, 'on_before_add_to_cart_button'), 1 );
		global $product;
		if ( !WC_Catalog_Restrictions_Filters::instance()->user_can_purchase( $product ) ) {
			$this->buffer_on = ob_start();
		}
	}

	public function on_before_add_to_cart_button() {
		global $product;
		if ( !WC_Catalog_Restrictions_Filters::instance()->user_can_purchase( $product ) ) {
			$this->buffer_on = ob_start();
		}
	}

	public function on_after_add_to_cart_button() {
		global $wc_cvo, $product;
		if ( !WC_Catalog_Restrictions_Filters::instance()->user_can_purchase( $product ) ) {

			if ( $this->buffer_on ) {
				ob_end_clean();
			}

			do_action( 'catalog_visibility_before_alternate_add_to_cart_button' );

			$html = apply_filters( 'catalog_visibility_alternate_add_to_cart_button', do_shortcode( wpautop( wptexturize( $wc_cvo->setting( 'wc_cvo_s_price_text' ) ) ) ) );


			// Variable product price handling
			if ( $product->is_type( 'variable' ) ) {
				?>
				<div class="single_variation_wrap">
					<div class="single_variation woocommerce-variation"></div>
					<div class="variations_button">
						<?php echo $html; ?>
						<input type="hidden" name="product_id" value="<?php echo absint( $product->get_id() ); ?>" />
						<input type="hidden" name="variation_id" class="variation_id" value="0" />
					</div>
					<?php do_action( 'wc_cvo_after_single_variation', $product ); ?>
				</div>
				<?php
			} else {
				echo $html;
			}

			do_action( 'catalog_visibility_after_alternate_add_to_cart_button' );
		}
	}

	public function on_wishlist_user_can_purcahse( $result, $product ) {
		return $result & WC_Catalog_Restrictions_Filters::instance()->user_can_purchase( $product );
	}

	/**
	 * Hooks into cart validation to disallow items from being added to the cart at all.
	 * @since 2.7.2
	 * @param bool $result
	 * @param int $product_id
	 * @return bool
	 */
	public function on_woocommerce_add_to_cart_validation( $result, $product_id ) {
		$product = wc_get_product( $product_id );
		$user_can_purchase = WC_Catalog_Restrictions_Filters::instance()->user_can_purchase( $product );

		//If the result was OK, but the user can not purchase the product the result of this function will be false. 
		//When adding an item to a wishlist however we need the result to be true as long as the regular validation is true;
		if ( $result && !$user_can_purchase ) {
			add_filter( 'woocommerce_add_to_wishlist_validation', array($this, 'on_woocommerce_add_to_wishlist_validation'), 10, 1 );
		}

		return $result & $user_can_purchase;
	}

	/**
	 * Hook to override catalog visibility disallowing items from being added to a wishlist.
	 * @since 2.7.2
	 * @param bool $result
	 * @return boolean
	 */
	public function on_woocommerce_add_to_wishlist_validation( $result ) {
		remove_filter( 'woocommerce_add_to_wishlist_validation', array($this, 'on_woocommerce_add_to_wishlist_validation'), 10, 1 );

		//This function is only called in the event that catalog visibility options has disallowed purchases AND regular validation already passed.  
		//Hardcode to true to allow adding the item to a wishlist
		$result = true;
		return $result;
	}

}
