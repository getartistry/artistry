(function($){

	astraSingleProductAjax = {

		/**
		 * Quick view AJAX add to cart
		 */
		quick_view_enable       : astra.shop_quick_view_enable || false,

		/**
		 * Single product AJAX add to cart
		 */
		ajax_add_to_cart_enable : astra.single_product_ajax_add_to_cart || false,
	
		/**
		 * Init
		 */
		init: function()
		{
			this._bind();
		},
		
		/**
		 * Binds events
		 */
		_bind: function()
		{
			if ( astraSingleProductAjax.ajax_add_to_cart_enable ) {
				$( document ).on( 'click', 'body.single-product button.single_add_to_cart_button', astraSingleProductAjax._processAjaxRequest );
			}

			if ( astraSingleProductAjax.quick_view_enable ) {
				$( document.body ).on( 'click', '#ast-quick-view-content button.single_add_to_cart_button', astraSingleProductAjax._processAjaxRequest );
			}

			$( document.body ).on( 'added_to_cart', astraSingleProductAjax._updateButton );
		},

		/**
		 * Process add to cart AJAX request
		 *
		 * @param  object e Event object.
		 * @return void
		 */
		_processAjaxRequest: function( e )
		{
			e.preventDefault();

			var $form = $(this).closest('form');

			// If the form inputs are invalid
			if( ! $form[0].checkValidity() ) {
				$form[0].reportValidity();
				return false;
			}

			var $thisbutton  = $( this ),
				product_id 	 = $(this).val() || '',
				variation_id = $('input[name="variation_id"]').val() || '';
			
			if( $thisbutton.hasClass( 'disabled' ) ) {
				return;
			}

			// Add loading to the button.
			$thisbutton.removeClass( 'added' );
			$thisbutton.addClass( 'loading' );

			// Set Quantity.
			// 
			// For grouped product quantity should be array instead of single value
			// For that set the quantity as array for grouped product.
			var quantity = $('input[name="quantity"]').val()
			if( $('.woocommerce-grouped-product-list-item' ).length )
			{
				var quantities = $('input.qty'),
					quantity   = [];

				$.each(quantities, function(index, val) {

					var name = $( this ).attr( 'name' );

					name = name.replace('quantity[','');
					name = name.replace(']','');
					name = parseInt( name );

					if( $( this ).val() ) {
						quantity[ name ] = $( this ).val();
					}
				});
			}

			// Process the AJAX
			var cartFormData = $('form.cart').serialize();

			$.ajax ({
				url: astra.ajax_url,
				type:'POST',
				data:'action=astra_add_cart_single_product&add-to-cart='+product_id+'&'+cartFormData,
				success:function(results) {

					// Trigger event so themes can refresh other areas.
					$( document.body ).trigger( 'wc_fragment_refresh' );
					$( document.body ).trigger( 'added_to_cart', [ results.fragments, results.cart_hash, $thisbutton ] );
				}
			});
		},

		/**
		 * Update cart page elements after add to cart events.
		 */
		_updateButton: function( e, fragments, cart_hash, button )
		{
			button = typeof button === 'undefined' ? false : button;

			if ( $( 'button.single_add_to_cart_button' ).length ) {
				
				$( button ).removeClass( 'loading' );
				$( button ).addClass( 'added' );

				// View cart text.
				if ( ! astra.is_cart && $(button).parent().find( '.added_to_cart' ).length === 0 ) {
					$(button).after( ' <a href="' + astra.cart_url + '" class="added_to_cart wc-forward" title="' +
						astra.view_cart + '">' + astra.view_cart + '</a>' );
				}

				$( document.body ).trigger( 'wc_cart_button_updated', [ button ] );
			}
		}

	};

	/**
	 * Initialization
	 */
	$(function(){
		astraSingleProductAjax.init();
	});

})(jQuery);