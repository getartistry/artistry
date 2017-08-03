<?php

class WC_Catalog_Restrictions_Filters {

	private static $instance;

	public static function instance() {
		if ( !self::$instance ) {
			self::$instance = new WC_Catalog_Restrictions_Filters();
		}
		return self::$instance;
	}

	public $buffer_on = false;
	public $action_removed = false;

	public function __construct() {

		add_filter( 'woocommerce_get_price_html', array($this, 'on_price_html'), 99, 2 );
		add_filter( 'woocommerce_variable_subscription_price_html', array($this, 'on_price_html'), 100, 2);
		add_filter( 'woocommerce_sale_flash', array($this, 'on_sale_flash'), 99, 3 );

		add_action( 'woocommerce_before_add_to_cart_button', array($this, 'on_before_add_to_cart_button'), 0 );
		add_action( 'woocommerce_after_add_to_cart_button', array($this, 'on_after_add_to_cart_button'), 998 );

		//Since 2.7.0 use the loop_add_to_cart link to filter the button, rather than before and after loop item. 
		add_filter( 'woocommerce_loop_add_to_cart_link', array($this, 'on_loop_add_to_cart_link'), 99, 2 );

		add_action( 'template_redirect', array($this, 'plugin_compatibility_filters'), 11 );

		//Since 2.8.1 - reset the availability_html so stock information does not show up in WC 2.6+
		add_filter( 'woocommerce_available_variation', array($this, 'on_get_woocommerce_available_variation'), 10, 3 );

		add_filter('wc_get_template', array($this, 'on_get_variation_template'), 99, 2);
	}

	public function plugin_compatibility_filters() {
		if ( is_product() ) {
			if ( !$this->user_can_purchase( wc_get_product( get_the_ID() ) ) ) {
				add_filter( 'woocommerce_bv_render_form', '__return_false' );
			}
		}
	}

	/**
	 * Reset the availability_html so stock information does not show up in WC 2.6+
	 * @since 2.8.1
	 * @param array $variation_data
	 * @param WC_Product_Variable $variable
	 * @param WC_Product $variation
	 * @return string
	 */
	public function on_get_woocommerce_available_variation( $variation_data, $variable, $variation ) {

		if ( (!$this->user_can_view_price( $variation ) || !$this->user_can_purchase( $variation )) && !(class_exists('WC_Wishlists_Plugin')) ) {
			$variation_data['availability_html'] = '';
		}
		
		return $variation_data;
	}

	/*
	 * Replacement HTML
	 */

	public function on_price_html( $html, $_product ) {
		global $wc_cvo;

		if ( !$this->user_can_view_price( $_product ) ) {
			return apply_filters( 'catalog_visibility_alternate_price_html', do_shortcode( wptexturize( $wc_cvo->setting( 'wc_cvo_c_price_text' ) ) ), $_product );
		}

		return $html;
	}

	public function on_sale_flash( $html, $post, $product ) {
		if ( !$this->user_can_view_price( $product ) ) {
			return '';
		}

		return $html;
	}

	public function on_before_add_to_cart_button() {
		global $product;

		if ( !$this->user_can_purchase( $product ) ) {
			$this->buffer_on = ob_start();
		}
	}

	public function on_after_add_to_cart_button() {
		global $wc_cvo, $product;

		if ( !$this->user_can_purchase( $product ) ) {
			ob_end_clean();
		} else {
			return;
		}

		do_action( 'catalog_visibility_before_alternate_add_to_cart_button' );

		$html = apply_filters( 'catalog_visibility_alternate_add_to_cart_button', do_shortcode( wpautop( wptexturize( $wc_cvo->setting( 'wc_cvo_s_price_text' ) ) ) ), $product );

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
		}else {
			echo $html;
		}


		do_action( 'catalog_visibility_after_alternate_add_to_cart_button' );
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

        $product = wc_get_product();
		if ($product && $template_name == 'single-product/add-to-cart/variation.php' ) {
			if (!$this->user_can_view_price($product)) {
				$located = $wc_cvo->plugin_dir() . '/templates/variation.php';
			}
		}

		return $located;
	}

	public function on_loop_add_to_cart_link( $markup, $product ) {
		global $wc_cvo;
		if ( !$this->user_can_purchase( $product ) ) {
			$label = wptexturize( $wc_cvo->setting( 'wc_cvo_atc_text' ) );
			if ( empty( $label ) ) {
				return;
			}
			$link = get_permalink( $product->get_id() );
			return apply_filters( 'catalog_visibility_alternate_add_to_cart_link', sprintf( '<a href="%s" data-product_id="%s" class="button product_type_%s">%s</a>', $link, $product->get_id(), $product->get_type(), $label ) );
		} else {
			return $markup;
		}
	}

	public function user_can_purchase( $product ) {
		//If the user can not view prices, they can not purchase the product. 
		$price_result = $this->user_can_view_price( $product );

		if ( $price_result ) {
			$pfilter = get_post_meta( $product->get_id(), '_wc_restrictions_purchase', true );
			$result = false;
			if ( $pfilter == 'public' ) {
				$result = true; //Everyone
			} elseif ( $pfilter == 'restricted' ) {
				$roles = get_post_meta( $product->get_id(), '_wc_restrictions_purchase_roles', true );
				if ( $roles && is_array( $roles ) ) {
					if ( !is_user_logged_in() ) {
						return false;
					}

					foreach ( $roles as $role ) {

						if ( current_user_can( $role ) ) {
							$result = true;
							break;
						}
					}
				}
			} else {
				$result = $this->user_can_purchase_in_category( $product );
			}
		} else {
			$result = false;
		}

		return apply_filters( 'catalog_visibility_user_can_purchase', $result, $product );
	}

	public function user_can_purchase_in_category( $product ) {
		global $wc_cvo;
		$atc = $wc_cvo->setting( 'wc_cvo_atc' ) == 'enabled' | ($wc_cvo->setting( 'wc_cvo_atc' ) == 'secured' && catalog_visibility_user_has_access());
		$prices = (($wc_cvo->setting( 'wc_cvo_prices' ) == 'secured' && catalog_visibility_user_has_access()) | $wc_cvo->setting( 'wc_cvo_prices' ) == 'enabled');

		return apply_filters( 'catalog_visibility_user_can_purchase_in_category', $atc & $prices, $product );
	}

	public function user_can_view_price( $product ) {
		$pfilter = get_post_meta( $product->get_id(), '_wc_restrictions_price', true );
		$result = false;
		if ( $pfilter == 'public' ) {
			$result = true;
		} elseif ( $pfilter == 'restricted' ) {
			$roles = get_post_meta( $product->get_id(), '_wc_restrictions_price_roles', true );
			if ( $roles && is_array( $roles ) ) {
				if ( !is_user_logged_in() ) {
					return false;
				}

				foreach ( $roles as $role ) {

					if ( current_user_can( $role ) ) {
						$result = true;
						break;
					}
				}
			}
		} else {
			$result = $this->user_can_view_price_in_category( $product );
		}

		return apply_filters( 'catalog_visibility_user_can_view_price', $result, $product );
	}

	public function user_can_view_price_in_category( $product ) {
		global $wc_cvo;
		$result = (($wc_cvo->setting( 'wc_cvo_prices' ) == 'secured' && catalog_visibility_user_has_access()) | $wc_cvo->setting( 'wc_cvo_prices' ) == 'enabled');
		return apply_filters( 'catalog_visibility_user_can_view_price_in_category', $result, $product );
	}

}
