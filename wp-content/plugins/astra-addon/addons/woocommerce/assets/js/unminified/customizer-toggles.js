/**
 * Showing and Hiding controls of Customizer.
 *
 * @package Astra Addon
 * @since 1.0.0
 */

( function( $ ) {
	ASTControlTrigger.addHook( 'astra-toggle-control', function( argument, api ) {

		// Display related & upsell product
		// grid & no. of products option.
		ASTCustomizerToggles['astra-settings[single-product-related-display]'] = [
			{
				controls: [
					'astra-settings[single-product-related-upsell-grid]',
					'astra-settings[single-product-related-upsell-per-page]',
				],
				callback: function( disabled_related ) {

					var disabled_upsell = api( 'astra-settings[single-product-up-sells-display]' ).get();

					if ( ! disabled_related && ! disabled_upsell ) {
						return false;
					}

					return true;
				}
			}
		];

		ASTCustomizerToggles['astra-settings[single-product-up-sells-display]'] = [
			{
				controls: [
					'astra-settings[single-product-related-upsell-grid]',
					'astra-settings[single-product-related-upsell-per-page]',
				],
				callback: function( disabled_related ) {

					var disabled_upsell = api( 'astra-settings[single-product-related-display]' ).get();

					if ( ! disabled_related && ! disabled_upsell ) {
						return false;
					}

					return true;
				}
			}
		];

		// Sale notifications.
		ASTCustomizerToggles['astra-settings[product-sale-notification]'] = [
			{
				controls: [
					'astra-settings[product-sale-style]',
					'astra-settings[product-sale-percent-value]',
				],
				callback: function( layout ) {

					if ( 'none' == layout ) {
						return false;
					}

					return true;
				}
			},
			{

				controls: [
					'astra-settings[product-sale-percent-value]',
				],
				callback: function( layout ) {

					if ( 'none' == layout || 'default' == layout ) {
						return false;
					}

					return true;
				}
			}
		];

		// Single Layout Tab Enable/Disable.
		ASTCustomizerToggles['astra-settings[single-product-tabs-display]'] = [
			{
				controls: [
					'astra-settings[single-product-tabs-layout]',
				],
				callback: function( product_tab ) {
					if ( product_tab ) {
						return true;
					}

					return false;
				}
			},
		];

		// Shop Pagination
		ASTCustomizerToggles ['astra-settings[shop-pagination]'] = [
			{
				controls: [
					'astra-settings[shop-pagination-style]',
				],
				callback: function( shop_pagination ) {

					if ( 'number' == shop_pagination ) {
						return true;
					}
					return false;
				}
			},
			{
				controls: [
					'astra-settings[shop-infinite-scroll-event]',
				],
				callback: function( shop_pagination ) {

					if ( 'infinite' == shop_pagination ) {
						return true;
					}
					return false;
				}
			},
			{
				controls: [
					'astra-settings[shop-load-more-text]',
				],
				callback: function( shop_pagination ) {

					var scroll_event = api( 'astra-settings[shop-infinite-scroll-event]' ).get();

					if ( 'infinite' == shop_pagination && 'click' == scroll_event ) {
						return true;
					}
					return false;
				}
			}
		];

		ASTCustomizerToggles ['astra-settings[shop-infinite-scroll-event]'] = [
			{
				controls: [
					'astra-settings[shop-load-more-text]',
				],
				callback: function( scroll_event ) {

					var shop_pagination = api( 'astra-settings[shop-pagination]' ).get();

					if ( 'infinite' == shop_pagination && 'click' == scroll_event ) {
						return true;
					}

					return false;
				}
			}
		];

		ASTCustomizerToggles ['astra-settings[single-product-structure]'] = [
			{
				controls: [
					'astra-settings[single-product-ajax-add-to-cart]',
				],
				callback: function( single_product_structure ) {

					if ( jQuery.inArray ( "add_cart", single_product_structure ) !== -1 ) {
						return true;
					}
					return false;
				}
			}
		];

		// Off Canvas.
		ASTCustomizerToggles ['astra-settings[shop-off-canvas-trigger-type]'] = [
			{
				controls: [
					'astra-settings[shop-active-filters-display]',
				],
				callback: function( trigger_type ) {

					if ( 'disable' != trigger_type ) {
						return true;
					}

					return false;
				}
			},
			{
				controls: [
					'astra-settings[shop-filter-trigger-link]',
				],
				callback: function( trigger_type ) {

					if ( 'link' == trigger_type || 'button' == trigger_type ) {
						return true;
					}

					return false;
				}
			},
			{
				controls: [
					'astra-settings[shop-filter-trigger-custom-class]',
				],
				callback: function( trigger_type ) {

					if ( 'custom-class' == trigger_type ) {
						return true;
					}
					return false;
				}
			}
		];
		
		// Checkout Width.
		ASTCustomizerToggles ['astra-settings[checkout-content-width]'] = [
			{
				controls: [
					'astra-settings[checkout-content-max-width]',
				],
				callback: function( checkout_width ) {

					if ( 'custom' == checkout_width ) {
						return true;
					}

					return false;
				}
			}
		];

		// Header Cart Style.
		ASTCustomizerToggles ['astra-settings[woo-header-cart-icon-style]'] = [
			{
				controls: [
					'astra-settings[woo-header-cart-icon-color]',
					'astra-settings[woo-header-cart-icon-radius]',
				],
				callback: function( style ) {

					if ( 'none' != style ) {
						return true;
					}

					return false;
				}
			}
		];

		// Breadcrumb.
		ASTCustomizerToggles ['astra-settings[single-product-breadcrumb-disable]'] = [
			{
				controls: [
					'astra-settings[single-product-breadcrumb-color]',
					'astra-settings[typo-product-breadcrumb-divider]',
					'astra-settings[font-size-product-breadcrumb]',
					'astra-settings[line-height-product-breadcrumb]',
					'astra-settings[font-family-product-breadcrumb]',
					'astra-settings[font-weight-product-breadcrumb]',
					'astra-settings[text-transform-product-breadcrumb]',
				],
				callback: function( breadcrumb ) {

					if ( 1 != breadcrumb ) {
						return true;
					}

					return false;
				}
			}
		];

		// Single Product.
		ASTCustomizerToggles ['astra-settings[single-product-structure]'] = [
			{
				controls: [
					'astra-settings[typo-product-title-divider]',
					'astra-settings[font-family-product-title]',
					'astra-settings[font-weight-product-title]',
					'astra-settings[text-transform-product-title]',
					'astra-settings[font-size-product-title]',
					'astra-settings[line-height-product-title]',
					'astra-settings[single-product-title-color]',
				],
				callback: function( structure ) {

					if ( 0 <= jQuery.inArray( 'title', structure ) ) {
						return true;
					}

					return false;
				}
			},
			{
				controls: [
					'astra-settings[typo-product-single-price-divider]',
					'astra-settings[font-family-product-price]',
					'astra-settings[font-weight-product-price]',
					'astra-settings[font-size-product-price]',
					'astra-settings[line-height-product-price]',
					'astra-settings[single-product-price-color]',
				],
				callback: function( structure ) {

					if ( 0 <= jQuery.inArray( 'price', structure ) ) {
						return true;
					}

					return false;
				}
			},
		];

		// Shop Product.
		ASTCustomizerToggles ['astra-settings[shop-product-structure]'] = [
			{
				controls: [
					'astra-settings[typo-shop-product-title-divider]',
					'astra-settings[font-family-shop-product-title]',
					'astra-settings[font-weight-shop-product-title]',
					'astra-settings[text-transform-shop-product-title]',
					'astra-settings[font-size-shop-product-title]',
					'astra-settings[line-height-shop-product-title]',
					'astra-settings[shop-product-title-color]',
				],
				callback: function( structure ) {

					if ( 0 <= jQuery.inArray( 'title', structure ) ) {
						return true;
					}

					return false;
				}
			},
			{
				controls: [
					'astra-settings[typo-product-shop-price-divider]',
					'astra-settings[font-family-shop-product-price]',
					'astra-settings[font-weight-shop-product-price]',
					'astra-settings[font-size-shop-product-price]',
					'astra-settings[line-height-shop-product-price]',
					'astra-settings[shop-product-price-color]',
				],
				callback: function( structure ) {

					if ( 0 <= jQuery.inArray( 'price', structure ) ) {
						return true;
					}

					return false;
				}
			},
			{
				controls: [
					'astra-settings[typo-product-shop-content-divider]',
					'astra-settings[font-family-shop-product-content]',
					'astra-settings[font-weight-shop-product-content]',
					'astra-settings[text-transform-shop-product-content]',
					'astra-settings[font-size-shop-product-content]',
					'astra-settings[line-height-shop-product-content]',
				],
				callback: function( structure ) {
					if ( 0 <= jQuery.inArray( 'category', structure ) || 0 <= jQuery.inArray( 'short_desc', structure ) ) {
						return true;
					}

					return false;
				}
			}
		];

	});
})( jQuery );
