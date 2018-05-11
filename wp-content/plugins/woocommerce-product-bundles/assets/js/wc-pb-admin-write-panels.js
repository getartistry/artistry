/* global wc_bundles_admin_params */
/* global woocommerce_admin_meta_boxes */

jQuery( function($) {

	function Bundled_Item( $el ) {

		var self = this;

		this.$content                        = $el.find( 'div.item-data' );
		this.$discount                       = this.$content.find( '.discount' );
		this.$visibility                     = this.$content.find( '.item_visibility' );
		this.$price_visibility               = this.$content.find( '.price_visibility' );
		this.$allowed_variations             = this.$content.find( 'div.allowed_variations' );
		this.$default_variation_attributes   = this.$content.find( 'div.default_variation_attributes' );
		this.$custom_title                   = this.$content.find( 'div.custom_title' );
		this.$custom_description             = this.$content.find( 'div.custom_description' );
		this.$override_title                 = this.$content.find( '.override_title' );
		this.$override_description           = this.$content.find( '.override_description' );
		this.$hide_thumbnail                 = this.$content.find( '.hide_thumbnail' );

		this.$section_links                  = this.$content.find( '.subsubsub a' );
		this.$sections                       = this.$content.find( '.options_group' );

		this.$priced_individually_input      = this.$content.find( '.priced_individually input' );
		this.$override_variations_input      = this.$content.find( '.override_variations input' );
		this.$override_defaults_input        = this.$content.find( '.override_default_variation_attributes input' );
		this.$override_title_input           = this.$override_title.find( 'input' );
		this.$override_description_input     = this.$override_description.find( 'input' );

		this.$price_visibility_product_input = this.$price_visibility.find( 'input.price_visibility_product' );
		this.$price_visibility_cart_input    = this.$price_visibility.find( 'input.price_visibility_cart' );
		this.$price_visibility_order_input   = this.$price_visibility.find( 'input.price_visibility_order' );

		this.$visibility_product_input       = this.$visibility.find( 'input.visibility_product' );
		this.$visibility_cart_input          = this.$visibility.find( 'input.visibility_cart' );
		this.$visibility_order_input         = this.$visibility.find( 'input.visibility_order' );

		this.priced_individually_input_changed = function() {
			if ( self.$priced_individually_input.is( ':checked' ) ) {
				self.$discount.show();
				self.$price_visibility.show();
			} else {
				self.$discount.hide();
				self.$price_visibility.hide();
			}
		};

		this.override_variations_input_changed = function() {
			if ( self.$override_variations_input.is( ':checked' ) ) {
				self.$allowed_variations.show();
			} else {
				self.$allowed_variations.hide();
			}
		};

		this.override_defaults_input_changed = function() {
			if ( self.$override_defaults_input.is( ':checked' ) ) {
				self.$default_variation_attributes.show();
			} else {
				self.$default_variation_attributes.hide();
			}
		};

		this.override_title_input_changed = function() {
			if ( self.$override_title_input.is( ':checked' ) ) {
				self.$custom_title.show();
			} else {
				self.$custom_title.hide();
			}
		};

		this.override_description_input_changed = function() {
			if ( self.$override_description_input.is( ':checked' ) ) {
				self.$custom_description.show();
			} else {
				self.$custom_description.hide();
			}
		};

		this.visibility_product_input_changed = function() {
			if ( self.$visibility_product_input.is( ':checked' ) ) {

				self.$override_title.show();
				self.$override_description.show();
				self.$hide_thumbnail.show();

				self.override_title_input_changed();
				self.override_description_input_changed();

			} else {

				self.$override_title.hide();
				self.$override_description.hide();
				self.$hide_thumbnail.hide();

				self.$custom_description.hide();
				self.$custom_title.hide();
			}
		};

		this.toggled_visibility = function( visibility_class ) {

			if ( self[ '$visibility_' + visibility_class + '_input' ].is( ':checked' ) ) {
				self[ '$price_visibility_' + visibility_class + '_input' ].css( 'opacity', 1 );
			} else {
				self[ '$price_visibility_' + visibility_class + '_input' ].css( 'opacity', 0.5 );
			}

		};

		this.section_changed = function( $section_link ) {

			self.$section_links.removeClass( 'current' );
			$section_link.addClass( 'current' );

			self.$sections.addClass( 'options_group_hidden' );
			self.$content.find( '.options_group_' + $section_link.data( 'tab' ) ).removeClass( 'options_group_hidden' );
		};

		this.initialize = function() {

			self.priced_individually_input_changed();
			self.override_variations_input_changed();
			self.override_defaults_input_changed();
			self.override_title_input_changed();
			self.override_description_input_changed();
			self.visibility_product_input_changed();

			self.toggled_visibility( 'product' );
			self.toggled_visibility( 'cart' );
			self.toggled_visibility( 'order' );
		};

		this.initialize();
	}

	var $edit_in_cart                 = $( '.bundle_edit_in_cart' ),
		$group_mode_select            = $( 'select#_wc_pb_group_mode' ),
		$bundled_products_panel       = $( '#bundled_product_data' ),
		$bundled_products_toolbar     = $bundled_products_panel.find( '.toolbar' ),
		$bundled_products_container   = $( '.wc-bundled-items' ),
		$bundled_products             = $( '.wc-bundled-item', $bundled_products_container ),
		$bundled_product_search       = $( '#bundled_product', $bundled_products_panel ),
		bundled_product_objects       = {},
		bundled_products_add_count    = $bundled_products.length,
		block_params                  = {
			message: 	null,
			overlayCSS: {
				background: '#fff',
				opacity: 	0.6
			}
		};

	$.fn.wc_bundles_select2 = function() {
		$( document.body ).trigger( 'wc-enhanced-select-init' );
	};

	// Bundle type move stock msg up.
	$( '.bundle_stock_msg' ).appendTo( '._manage_stock_field .description' );

	// Hide the default "Sold Individually" field.
	$( '#_sold_individually' ).closest( '.form-field' ).addClass( 'hide_if_bundle' );

	// Hide the "Grouping" field.
	$( '#linked_product_data .grouping.show_if_simple, #linked_product_data .form-field.show_if_grouped' ).addClass( 'hide_if_bundle' );

	// Simple type options are valid for bundles.
	$( '.show_if_simple:not(.hide_if_bundle)' ).addClass( 'show_if_bundle' );

	/*
	 * WC core event handling.
	 */

	// Bundle type specific options.
	$( 'body' ).on( 'woocommerce-product-type-change', function( event, select_val ) {

		if ( 'bundle' === select_val ) {

			$( '.show_if_external' ).hide();
			$( '.show_if_bundle' ).show();

			$( 'input#_manage_stock' ).change();

			$( '#_nyp' ).change();
		}

	} );

	$group_mode_select.change( function() {
		if ( $.inArray( $group_mode_select.val(), wc_bundles_admin_params.group_modes_with_parent ) === -1 ) {
			$edit_in_cart.hide();
		} else {
			$edit_in_cart.show();
		}
	} );

	// Downloadable support.
	$( 'input#_downloadable' ).change( function() {
		$( 'select#product-type' ).change();
	} );

	// Trigger product type change.
	$( 'select#product-type' ).change();

	// Trigger group mode change.
	$group_mode_select.change();

	init_event_handlers();

	init_bundled_products();

	function init_event_handlers() {

		// Add Product.
		$bundled_product_search

			.on( 'change', function() {

				var bundled_product_ids = $bundled_product_search.val(),
					bundled_product_id  = bundled_product_ids && bundled_product_ids.length > 0 ? bundled_product_ids.shift() : false;

				if ( ! bundled_product_id ) {
					return false;
				}

				$bundled_product_search.val( [] ).change();

				$bundled_products_panel.block( block_params );

				bundled_products_add_count++;

				var data = {
					action: 	'woocommerce_add_bundled_product',
					post_id: 	woocommerce_admin_meta_boxes.post_id,
					id: 		bundled_products_add_count,
					product_id: bundled_product_id,
					security: 	wc_bundles_admin_params.add_bundled_product_nonce
				};

				setTimeout( function() {

					$.post( woocommerce_admin_meta_boxes.ajax_url, data, function ( response ) {

						if ( '' !== response.markup ) {

							$bundled_products_container.append( response.markup );

							var $added   = $( '.wc-bundled-item', $bundled_products_container ).last(),
								added_id = 'bundled_item_' + bundled_products_add_count;

							$added.data( 'bundled_item_id', added_id );
							bundled_product_objects[ added_id ] = new Bundled_Item( $added );

							$bundled_products_panel.triggerHandler( 'wc-bundled-products-changed' );

							$added.find( '.woocommerce-help-tip' ).tipTip( {
								'attribute' : 'data-tip',
								'fadeIn' : 50,
								'fadeOut' : 50,
								'delay' : 200
							} );

							$added.wc_bundles_select2();

							$bundled_products_panel.trigger( 'wc-bundles-added-bundled-product' );

						} else if ( response.message !== '' ) {
							window.alert( response.message );
						}

						// Open and close to resolve "sticky" modal issue.
						if ( 'yes' === wc_bundles_admin_params.is_wc_version_gte_3_2 ) {
							$bundled_product_search.selectWoo( 'open' );
							$bundled_product_search.selectWoo( 'close' );
						} else {
							$bundled_product_search.select2( 'open' );
							$bundled_product_search.select2( 'close' );
						}

						$bundled_products_panel.unblock();

					} );

				}, 250 );

				return false;

			} );

		$bundled_products_panel

			// Update menu order and toolbar states.
			.on( 'wc-bundled-products-changed', function() {

				$bundled_products = $( '.wc-bundled-item', $bundled_products_container );

				$bundled_products.each( function( index, el ) {
					$( '.item_menu_order', el ).val( index );
				} );

				update_toolbar_state();

			} );

		$bundled_products_container

			// Remove Item.
			.on( 'click', 'a.remove_row', function( e ) {

				var $el   = $( this ).closest( '.wc-bundled-item' ),
					el_id = $el.data( 'bundled_item_id' );

				$el.find( '*' ).off();
				$el.remove();

				delete bundled_product_objects[ el_id ];

				$bundled_products_panel.triggerHandler( 'wc-bundled-products-changed' );

				e.preventDefault();

			} )

			// Priced individually.
			.on( 'change', '.priced_individually input', function() {

				var $el             = $( this ).closest( '.wc-bundled-item' ),
					el_id           = $el.data( 'bundled_item_id' ),
					bundled_product = bundled_product_objects[ el_id ];

				bundled_product.priced_individually_input_changed();
			} )

			// Variation filtering options.
			.on( 'change', '.override_variations input', function() {

				var $el             = $( this ).closest( '.wc-bundled-item' ),
					el_id           = $el.data( 'bundled_item_id' ),
					bundled_product = bundled_product_objects[ el_id ];

				bundled_product.override_variations_input_changed();
			} )

			// Selection defaults options.
			.on( 'change', '.override_default_variation_attributes input', function() {

				var $el             = $( this ).closest( '.wc-bundled-item' ),
					el_id           = $el.data( 'bundled_item_id' ),
					bundled_product = bundled_product_objects[ el_id ];

				bundled_product.override_defaults_input_changed();
			} )

			// Custom title options.
			.on( 'change', '.override_title input', function() {

				var $el             = $( this ).closest( '.wc-bundled-item' ),
					el_id           = $el.data( 'bundled_item_id' ),
					bundled_product = bundled_product_objects[ el_id ];

				bundled_product.override_title_input_changed();
			} )

			// Custom description options.
			.on( 'change', '.override_description input', function() {

				var $el             = $( this ).closest( '.wc-bundled-item' ),
					el_id           = $el.data( 'bundled_item_id' ),
					bundled_product = bundled_product_objects[ el_id ];

				bundled_product.override_description_input_changed();
			} )

			// Visibility.
			.on( 'change', 'input.visibility_product', function() {

				var $el             = $( this ).closest( '.wc-bundled-item' ),
					el_id           = $el.data( 'bundled_item_id' ),
					bundled_product = bundled_product_objects[ el_id ];

				bundled_product.visibility_product_input_changed();
				bundled_product.toggled_visibility( 'product' );
			} )

			.on( 'change', 'input.visibility_cart', function() {

				var $el             = $( this ).closest( '.wc-bundled-item' ),
					el_id           = $el.data( 'bundled_item_id' ),
					bundled_product = bundled_product_objects[ el_id ];

				bundled_product.toggled_visibility( 'cart' );
			} )

			.on( 'change', 'input.visibility_order', function() {

				var $el             = $( this ).closest( '.wc-bundled-item' ),
					el_id           = $el.data( 'bundled_item_id' ),
					bundled_product = bundled_product_objects[ el_id ];

				bundled_product.toggled_visibility( 'order' );
			} )

			// Sections.
			.on( 'click', '.subsubsub a', function( event ) {

				var $section_link   = $( this ),
					$el             = $( this ).closest( '.wc-bundled-item' ),
					el_id           = $el.data( 'bundled_item_id' ),
					bundled_product = bundled_product_objects[ el_id ];

				bundled_product.section_changed( $section_link );

				event.preventDefault();

			} );

	}

	function init_bundled_products() {

		// Create objects.
		$bundled_products.each( function( index ) {

			var $el   = $( this ),
				el_id = 'bundled_item_' + index;

			$el.data( 'bundled_item_id', el_id );
			bundled_product_objects[ el_id ] = new Bundled_Item( $el );
		} );

		// Item ordering.
		$bundled_products_container.sortable( {
			items: '.wc-bundled-item',
			cursor: 'move',
			axis: 'y',
			handle: 'h3',
			scrollSensitivity: 40,
			forcePlaceholderSize: true,
			helper: 'clone',
			opacity: 0.65,
			placeholder: 'wc-metabox-sortable-placeholder',
			start:function( event, ui ){
				ui.item.css( 'background-color','#f6f6f6' );
			},
			stop:function( event, ui ){
				ui.item.removeAttr( 'style' );
				$bundled_products_panel.triggerHandler( 'wc-bundled-products-changed' );
			}
		} );

		// Expand/collapse toolbar state.
		update_toolbar_state();
	}

	function update_toolbar_state() {

		if ( $bundled_products.length > 0 ) {
			$bundled_products_toolbar.removeClass( 'disabled' );
		} else {
			$bundled_products_toolbar.addClass( 'disabled' );
		}
	}

} );
