<?php
/**
 * Product Bundles template functions
 *
 * @author   SomewhereWarm <info@somewherewarm.gr>
 * @package  WooCommerce Product Bundles
 * @since    4.11.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*--------------------------------------------------------*/
/*  Product Bundles single product template functions     */
/*--------------------------------------------------------*/

/**
 * Add-to-cart template for Product Bundles. Handles the 'Form location > After summary' case.
 *
 * @since  5.7.0
 */
function wc_pb_template_add_to_cart_after_summary() {

	global $product;

	if ( wc_pb_is_product_bundle() ) {
		if ( 'after_summary' === $product->get_add_to_cart_form_location() ) {
			$classes = implode( ' ', apply_filters( 'woocommerce_bundle_form_wrapper_classes', array( 'summary-add-to-cart-form', 'summary-add-to-cart-form-bundle' ), $product ) );
			?><div class="<?php echo esc_attr( $classes );?>"><?php
				do_action( 'woocommerce_bundle_add_to_cart' );
			?></div><?php
		}
	}
}


/**
 * Add-to-cart template for Product Bundles.
 */
function wc_pb_template_add_to_cart() {

	global $product;

	if ( doing_action( 'woocommerce_single_product_summary' ) ) {
		if ( 'after_summary' === $product->get_add_to_cart_form_location() ) {
			return;
		}
	}

	// Enqueue variation scripts.
	wp_enqueue_script( 'wc-add-to-cart-bundle' );

	wp_enqueue_style( 'wc-bundle-css' );

	$bundled_items = $product->get_bundled_items();
	$form_classes  = array( 'layout_' . $product->get_layout(), 'group_mode_' . $product->get_group_mode() );

	if ( ! empty( $bundled_items ) ) {
		wc_get_template( 'single-product/add-to-cart/bundle.php', array(
			'availability_html' => wc_get_stock_html( $product ),
			'bundle_price_data' => $product->get_bundle_price_data(),
			'bundled_items'     => $bundled_items,
			'product'           => $product,
			'product_id'        => $product->get_id(),
			'classes'           => implode( ' ', $form_classes )
		), false, WC_PB()->plugin_path() . '/templates/' );
	}
}

/**
 * Add-to-cart buttons area.
 *
 * @since 5.5.0
 *
 * @param  WC_Product_Bundle  $bundle
 */
function wc_pb_template_add_to_cart_wrap( $product ) {

	wc_get_template( 'single-product/add-to-cart/bundle-add-to-cart-wrap.php', array(
		'availability_html' => wc_get_stock_html( $product ),
		'bundle_price_data' => $product->get_bundle_price_data(),
		'product'           => $product,
		'product_id'        => $product->get_id()
	), false, WC_PB()->plugin_path() . '/templates/' );
}

/**
 * Add-to-cart button and quantity input.
 */
function wc_pb_template_add_to_cart_button() {

	if ( isset( $_GET[ 'update-bundle' ] ) ) {
		$updating_cart_key = wc_clean( $_GET[ 'update-bundle' ] );
		if ( isset( WC()->cart->cart_contents[ $updating_cart_key ] ) ) {
			echo '<input type="hidden" name="update-bundle" value="' . $updating_cart_key . '" />';
		}
	}

	wc_get_template( 'single-product/add-to-cart/bundle-quantity-input.php', array(), false, WC_PB()->plugin_path() . '/templates/' );
	wc_get_template( 'single-product/add-to-cart/bundle-button.php', array(), false, WC_PB()->plugin_path() . '/templates/' );
}

/**
 * Load the bundled item title template.
 *
 * @param  WC_Bundled_Item    $bundled_item
 * @param  WC_Product_Bundle  $bundle
 */
function wc_pb_template_bundled_item_title( $bundled_item, $bundle ) {

	$min_qty = $bundled_item->get_quantity();
	$max_qty = $bundled_item->get_quantity( 'max' );

	$qty     = $min_qty > 1 && $min_qty === $max_qty ? $min_qty : '';

	wc_get_template( 'single-product/bundled-item-title.php', array(
		'quantity'     => $qty,
		'title'        => $bundled_item->get_title(),
		'permalink'    => $bundled_item->get_permalink(),
		'optional'     => $bundled_item->is_optional(),
		'bundled_item' => $bundled_item,
		'bundle'       => $bundle
	), false, WC_PB()->plugin_path() . '/templates/' );
}

/**
 * Load the bundled item thumbnail template.
 *
 * @param  WC_Bundled_Item    $bundled_item
 * @param  WC_Product_Bundle  $bundle
 */
function wc_pb_template_bundled_item_thumbnail( $bundled_item, $bundle ) {

	$layout     = $bundle->get_layout();
	$product_id = $bundled_item->get_product_id();

	if ( 'tabular' === $layout ) {
		echo '<td class="bundled_item_col bundled_item_images_col">';
	}

	if ( $bundled_item->is_visible() ) {
		if ( $bundled_item->is_thumbnail_visible() ) {

			/**
			 * 'woocommerce_bundled_product_gallery_classes' filter.
			 *
			 * @param  array            $classes
			 * @param  WC_Bundled_Item  $bundled_item
			 */
			$gallery_classes = apply_filters( 'woocommerce_bundled_product_gallery_classes', array( 'bundled_product_images', 'images' ), $bundled_item );

			wc_get_template( 'single-product/bundled-item-image.php', array(
				'post_id'         => $product_id,
				'product_id'      => $product_id,
				'bundled_item'    => $bundled_item,
				'gallery_classes' => $gallery_classes,
				'image_size'      => $bundled_item->get_bundled_item_thumbnail_size(),
				'image_rel'       => current_theme_supports( 'wc-product-gallery-lightbox' ) ? 'photoSwipe' : 'prettyPhoto',
			), false, WC_PB()->plugin_path() . '/templates/' );
		}
	}

	if ( 'tabular' === $layout ) {
		echo '</td>';
	}
}

/**
 * Load the bundled item short description template.
 *
 * @param  WC_Bundled_Item    $bundled_item
 * @param  WC_Product_Bundle  $bundle
 */
function wc_pb_template_bundled_item_description( $bundled_item, $bundle ) {

	wc_get_template( 'single-product/bundled-item-description.php', array(
		'description' => $bundled_item->get_description()
	), false, WC_PB()->plugin_path() . '/templates/' );
}

/**
 * Adds the 'bundled_product' container div.
 *
 * @param  WC_Bundled_Item    $bundled_item
 * @param  WC_Product_Bundle  $bundle
 */
function wc_pb_template_bundled_item_details_wrapper_open( $bundled_item, $bundle ) {

	$layout = $bundle->get_layout();

	if ( 'default' === $layout ) {
		$el = 'div';
	} elseif ( 'tabular' === $layout ) {
		$el = 'tr';
	}

	$classes = $bundled_item->get_classes();
	$style   = $bundled_item->is_visible() ? '' : ' style="display:none;"';

	echo '<' . $el . ' class="bundled_product bundled_product_summary product ' . $classes . '"' . $style . ' >';
}

/**
 * Adds a qty input column when using the tabular template.
 *
 * @param  WC_Bundled_Item    $bundled_item
 * @param  WC_Product_Bundle  $bundle
 */
function wc_pb_template_tabular_bundled_item_qty( $bundled_item, $bundle ) {

	$layout = $bundle->get_layout();

	if ( 'tabular' === $layout ) {

		/** Documented in 'WC_PB_Cart::get_posted_bundle_configuration'. */
		$bundle_fields_prefix = apply_filters( 'woocommerce_product_bundle_field_prefix', '', $bundle->get_id() );

		$quantity_min = $bundled_item->get_quantity();
		$quantity_max = $bundled_item->get_quantity( 'max', array( 'bound_by_stock' => true ) );
		$input_name   = $bundle_fields_prefix . 'bundle_quantity_' . $bundled_item->get_id();
		$hide_input   = $quantity_min === $quantity_max || false === $bundled_item->is_in_stock();

		echo '<td class="bundled_item_col bundled_item_qty_col">';

		wc_get_template( 'single-product/bundled-item-quantity.php', array(
			'bundled_item'         => $bundled_item,
			'quantity_min'         => $quantity_min,
			'quantity_max'         => $quantity_max,
			'input_name'           => $input_name,
			'layout'               => $layout,
			'hide_input'           => $hide_input,
			'bundle_fields_prefix' => $bundle_fields_prefix
		), false, WC_PB()->plugin_path() . '/templates/' );

		echo '</td>';
	}
}

/**
 * Adds a qty input column when using the default template.
 *
 * @param  WC_Bundled_Item  $bundled_item
 */
function wc_pb_template_default_bundled_item_qty( $bundled_item ) {

	$bundle = $bundled_item->get_bundle();
	$layout = $bundle->get_layout();

	if ( 'default' === $layout ) {

		/** Documented in 'WC_PB_Cart::get_posted_bundle_configuration'. */
		$bundle_fields_prefix = apply_filters( 'woocommerce_product_bundle_field_prefix', '', $bundle->get_id() );

		$quantity_min = $bundled_item->get_quantity();
		$quantity_max = $bundled_item->get_quantity( 'max', array( 'bound_by_stock' => true ) );
		$input_name   = $bundle_fields_prefix . 'bundle_quantity_' . $bundled_item->get_id();
		$hide_input   = $quantity_min === $quantity_max || false === $bundled_item->is_in_stock();

		wc_get_template( 'single-product/bundled-item-quantity.php', array(
			'bundled_item'         => $bundled_item,
			'quantity_min'         => $quantity_min,
			'quantity_max'         => $quantity_max,
			'input_name'           => $input_name,
			'layout'               => $layout,
			'hide_input'           => $hide_input,
			'bundle_fields_prefix' => $bundle_fields_prefix
		), false, WC_PB()->plugin_path() . '/templates/' );
	}
}


/**
 * Close the 'bundled_product' container div.
 *
 * @param  WC_Bundled_Item    $bundled_item
 * @param  WC_Product_Bundle  $bundle
 */
function wc_pb_template_bundled_item_details_wrapper_close( $bundled_item, $bundle ) {

	$layout = $bundle->get_layout();

	if ( 'default' === $layout ) {
		$el = 'div';
	} elseif ( 'tabular' === $layout ) {
		$el = 'tr';
	}

	echo '</' . $el . '>';
}

/**
 * Add a 'details' container div.
 *
 * @param  WC_Bundled_Item    $bundled_item
 * @param  WC_Product_Bundle  $bundle
 */
function wc_pb_template_bundled_item_details_open( $bundled_item, $bundle ) {

	$layout = $bundle->get_layout();

	if ( 'tabular' === $layout ) {
		echo '<td class="bundled_item_col bundled_item_details_col">';
	}

	echo '<div class="details">';
}

/**
 * Close the 'details' container div.
 *
 * @param  WC_Bundled_Item    $bundled_item
 * @param  WC_Product_Bundle  $bundle
 */
function wc_pb_template_bundled_item_details_close( $bundled_item, $bundle ) {

	$layout = $bundle->get_layout();

	echo '</div>';

	if ( 'tabular' === $layout ) {
		echo '</td>';
	}
}

/**
 * Display bundled product details templates.
 *
 * @param  WC_Bundled_Item    $bundled_item
 * @param  WC_Product_Bundle  $bundle
 */
function wc_pb_template_bundled_item_product_details( $bundled_item, $bundle ) {

	if ( $bundled_item->is_purchasable() ) {

		$bundle_id          = $bundle->get_id();
		$bundled_product    = $bundled_item->product;
		$bundled_product_id = $bundled_product->get_id();
		$availability       = $bundled_item->get_availability();

		/** Documented in 'WC_PB_Cart::get_posted_bundle_configuration'. */
		$bundle_fields_prefix = apply_filters( 'woocommerce_product_bundle_field_prefix', '', $bundle_id );

		$bundled_item->add_price_filters();

		if ( $bundled_item->is_optional() ) {

			// Optional checkbox template.
			wc_get_template( 'single-product/bundled-item-optional.php', array(
				'quantity'             => $bundled_item->get_quantity(),
				'bundled_item'         => $bundled_item,
				'bundle_fields_prefix' => $bundle_fields_prefix
			), false, WC_PB()->plugin_path() . '/templates/' );
		}

		if ( $bundled_product->get_type() === 'simple' || $bundled_product->get_type() === 'subscription' ) {

			// Simple Product template.
			wc_get_template( 'single-product/bundled-product-simple.php', array(
				'bundled_product_id'   => $bundled_product_id,
				'bundled_product'      => $bundled_product,
				'bundled_item'         => $bundled_item,
				'bundle_id'            => $bundle_id,
				'bundle'               => $bundle,
				'bundle_fields_prefix' => $bundle_fields_prefix,
				'availability'         => $availability,
				'custom_product_data'  => apply_filters( 'woocommerce_bundled_product_custom_data', array(), $bundled_item )
			), false, WC_PB()->plugin_path() . '/templates/' );

		} elseif ( $bundled_product->get_type() === 'variable' || $bundled_product->get_type() === 'variable-subscription' ) {

			$do_ajax                       = $bundled_item->use_ajax_for_product_variations();
			$variations                    = $do_ajax ? false : $bundled_item->get_product_variations();
			$variation_attributes          = $bundled_item->get_product_variation_attributes();
			$selected_variation_attributes = $bundled_item->get_selected_product_variation_attributes();

			if ( ! $do_ajax && empty( $variations ) ) {

				// Unavailable Product template.
				wc_get_template( 'single-product/bundled-product-unavailable.php', array(
					'bundled_item'        => $bundled_item,
					'bundle'              => $bundle,
					'custom_product_data' => apply_filters( 'woocommerce_bundled_product_custom_data', array(), $bundled_item )
				), false, WC_PB()->plugin_path() . '/templates/' );

			} else {

				// Variable Product template.
				wc_get_template( 'single-product/bundled-product-variable.php', array(
					'bundled_product_id'                  => $bundled_product_id,
					'bundled_product'                     => $bundled_product,
					'bundled_item'                        => $bundled_item,
					'bundle_id'                           => $bundle_id,
					'bundle'                              => $bundle,
					'bundle_fields_prefix'                => $bundle_fields_prefix,
					'availability'                        => $availability,
					'bundled_product_attributes'          => $variation_attributes,
					'bundled_product_variations'          => $variations,
					'bundled_product_selected_attributes' => $selected_variation_attributes,
					'custom_product_data'                 => apply_filters( 'woocommerce_bundled_product_custom_data', array(
						'bundle_id'       => $bundle_id,
						'bundled_item_id' => $bundled_item->get_id()
					), $bundled_item )
				), false, WC_PB()->plugin_path() . '/templates/' );
			}
		}

		$bundled_item->remove_price_filters();

	} else {
		// Unavailable Product template.
		wc_get_template( 'single-product/bundled-product-unavailable.php', array(
			'bundled_item'        => $bundled_item,
			'bundle'              => $bundle,
			'custom_product_data' => apply_filters( 'woocommerce_bundled_product_custom_data', array(), $bundled_item )
		), false, WC_PB()->plugin_path() . '/templates/' );
	}
}

/**
 * Bundled variation details.
 *
 * @param  int              $product_id
 * @param  WC_Bundled_Item  $bundled_item
 */
function wc_pb_template_single_variation( $product_id, $bundled_item ) {
	?><div class="woocommerce-variation single_variation bundled_item_cart_details"></div><?php
}

/**
 * Bundled variation template.
 *
 * @since  5.6.0
 *
 * @param  int              $product_id
 * @param  WC_Bundled_Item  $bundled_item
 */
function wc_pb_template_single_variation_template( $product_id, $bundled_item ) {

	wc_get_template( 'single-product/bundled-variation.php', array(
		'bundled_item'         => $bundled_item,
		'bundle_fields_prefix' => apply_filters( 'woocommerce_product_bundle_field_prefix', '', $bundled_item->get_bundle_id() ) // Filter documented in 'WC_PB_Cart::get_posted_bundle_configuration'.
	), false, WC_PB()->plugin_path() . '/templates/' );
}

/**
 * Echo opening tabular markup if necessary.
 *
 * @param  WC_Product_Bundle  $bundle
 */
function wc_pb_template_before_bundled_items( $bundle ) {

	$layout = $bundle->get_layout();

	if ( 'tabular' === $layout ) {

		?><table cellspacing="0" class="bundled_products">
			<thead>
				<th class="bundled_item_col bundled_item_images_head"></th>
				<th class="bundled_item_col bundled_item_details_head"><?php _e( 'Product', 'woocommerce-product-bundles' ); ?></th>
				<th class="bundled_item_col bundled_item_qty_head"><?php _e( 'Quantity', 'woocommerce-product-bundles' ); ?></th>
			</thead>
			<tbody><?php
	}
}

/**
 * Echo closing tabular markup if necessary.
 *
 * @param  WC_Product_Bundle  $bundle
 */
function wc_pb_template_after_bundled_items( $bundle ) {

	$layout = $bundle->get_layout();

	if ( 'tabular' === $layout ) {
		echo '</tbody></table>';
	}
}

/**
 * Display bundled product attributes.
 *
 * @param  WC_Product  $product
 */
function wc_pb_template_bundled_item_attributes( $product ) {

	if ( $product->is_type( 'bundle' ) ) {

		$bundled_items = $product->get_bundled_items();

		if ( ! empty( $bundled_items ) ) {

			foreach ( $bundled_items as $bundled_item ) {

				/** Documented in 'WC_Product_Bundle::has_attributes()'. */
				$show_bundled_product_attributes = apply_filters( 'woocommerce_bundle_show_bundled_product_attributes', $bundled_item->is_visible(), $product, $bundled_item );

				if ( ! $show_bundled_product_attributes ) {
					continue;
				}

				$bundled_product = $bundled_item->product;

				if ( $bundled_product->has_attributes() ) {

					// Filter bundled item attributes based on active variation filters.
					add_filter( 'woocommerce_attribute', array( $bundled_item, 'filter_bundled_item_attribute' ), 10, 3 );

					wc_get_template( 'single-product/bundled-item-attributes.php', array(
						'title'              => $bundled_item->get_title(),
						'product'            => $bundled_product,
						'attributes'         => array_filter( $bundled_product->get_attributes(), 'wc_attributes_array_filter_visible' ),
						'display_dimensions' => $bundled_item->is_shipped_individually() && apply_filters( 'wc_product_enable_dimensions_display', $product->has_weight() || $product->has_dimensions() )
					), false, WC_PB()->plugin_path() . '/templates/' );

					remove_filter( 'woocommerce_attribute', array( $bundled_item, 'filter_bundled_item_attribute' ), 10, 3 );
				}
			}
		}
	}
}

/**
 * Variation attribute options for bundled items. If:
 *
 * - only a single variation is active,
 * - all attributes have a defined value, and
 * - the single values are actually selected as defaults,
 *
 * ...then wrap the dropdown in a hidden div and show the single attribute value description before it.
 *
 * @param  array  $args
 */
function wc_pb_template_bundled_variation_attribute_options( $args ) {

	$bundled_item                = $args[ 'bundled_item' ];
	$variation_attribute_name    = $args[ 'attribute' ];
	$variation_attribute_options = $args[ 'options' ];

	/** Documented in 'WC_PB_Cart::get_posted_bundle_configuration'. */
	$bundle_fields_prefix = apply_filters( 'woocommerce_product_bundle_field_prefix', '', $bundled_item->get_bundle_id() );

	// The currently selected attribute option.
	$selected_option = isset( $_REQUEST[ $bundle_fields_prefix . 'bundle_attribute_' . sanitize_title( $variation_attribute_name ) . '_' . $bundled_item->get_id() ] ) ? wc_clean( stripslashes( urldecode( $_REQUEST[ $bundle_fields_prefix . 'bundle_attribute_' . sanitize_title( $variation_attribute_name ) . '_' . $bundled_item->get_id() ] ) ) ) : $bundled_item->get_selected_product_variation_attribute( $variation_attribute_name );

	$variation_attributes              = $bundled_item->get_product_variation_attributes();
	$configurable_variation_attributes = $bundled_item->get_product_variation_attributes( true );
	$show_dropdown                     = isset( $configurable_variation_attributes[ $variation_attribute_name ] );
	$html                              = '';

	// Fill required args.
	$args[ 'selected' ] = $selected_option;
	$args[ 'name' ]     = $bundle_fields_prefix . 'bundle_attribute_' . sanitize_title( $variation_attribute_name ) . '_' . $bundled_item->get_id();
	$args[ 'product' ]  = $bundled_item->product;

	if ( false === $show_dropdown ) {
		/**
		 * 'woocommerce_force_show_bundled_dropdown_variation_attribute_options' filter.
		 *
		 * @param  boolean  $force_show
		 * @param  array    $args
		 */
		$show_dropdown = apply_filters( 'woocommerce_force_show_bundled_dropdown_variation_attribute_options', false, $args );
	}

	// Render everything.
	if ( false === $show_dropdown ) {

		$variation_attribute_value = '';

		// Get the singular option description.
		if ( taxonomy_exists( $variation_attribute_name ) ) {

			// Get terms if this is a taxonomy.
			$terms = wc_get_product_terms( $bundled_item->get_product_id(), $variation_attribute_name, array( 'fields' => 'all' ) );

			foreach ( $terms as $term ) {
				if ( $term->slug === sanitize_title( $selected_option ) ) {
					$variation_attribute_value = esc_html( apply_filters( 'woocommerce_variation_option_name', $term->name ) );
					break;
				}
			}

		} else {

			foreach ( $variation_attribute_options as $option ) {

				if ( sanitize_title( $selected_option ) === $selected_option ) {
					$singular_found = $selected_option === sanitize_title( $option );
				} else {
					$singular_found = $selected_option === $option;
				}

				if ( $singular_found ) {
					$variation_attribute_value = esc_html( apply_filters( 'woocommerce_variation_option_name', $option ) );
					break;
				}
			}
		}

		$html .= '<span class="bundled_variation_attribute_value">' . $variation_attribute_value . '</span>';

		// See https://github.com/woothemes/woocommerce/pull/11944 .
		$args[ 'show_option_none' ] = false;

		// Get the dropdowns markup.
		ob_start();
		wc_dropdown_variation_attribute_options( $args );
		$attribute_options = ob_get_clean();

		// Add the dropdown (hidden).
		$html .= '<div class="bundled_variation_attribute_options_wrapper" style="display:none;">' . $attribute_options . '</div>';

	} else {

		// Get the dropdowns markup.
		ob_start();
		wc_dropdown_variation_attribute_options( $args );
		$attribute_options = ob_get_clean();

		// Just render the dropdown.
		$html .= $attribute_options;
	}

	if ( sizeof( $configurable_variation_attributes ) === sizeof( $variation_attributes ) ) {
		$variation_attribute_keys = array_keys( $variation_attributes );
		// ...and add the reset-variations link.
		if ( end( $variation_attribute_keys ) === $variation_attribute_name ) {
			$html .= apply_filters( 'woocommerce_reset_variations_link', '<a class="reset_variations" href="#">' . __( 'Clear', 'woocommerce' ) . '</a>' );
		}
	}

	return $html;
}
