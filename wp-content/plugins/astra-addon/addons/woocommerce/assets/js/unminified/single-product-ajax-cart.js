(function ($) {

	var single_product_ajax_add_to_cart =  astra.single_product_ajax_add_to_cart || '';
	if ( ! single_product_ajax_add_to_cart ) {
		return false;
	}
	/**
	 * SingleAddToCartHandler class.
	 */
	var SingleAddToCartHandler = function() {
		$( document.body )
			.on( 'click', 'button.single_add_to_cart_button', this.onAddToCart )
			.on( 'added_to_cart', this.updateButton );
	};

	/**
	 * Handle the add to cart event.
	 */
	SingleAddToCartHandler.prototype.onAddToCart = function( e ) {

		e.preventDefault();

		var $thisbutton = $( this ),
			product_id = $(this).val(),
			variation_id = $('input[name="variation_id"]').val() || '',
			quantity = $('input[name="quantity"]').val();
		if ( $thisbutton.is( '.single_add_to_cart_button' ) ) {
			$thisbutton.removeClass( 'added' );
			$thisbutton.addClass( 'loading' );

			// Ajax action.
			if ( variation_id ) {
				jQuery.ajax ({
					url: astra.ajax_url,
					type:'POST',
					data:'action=astra_add_cart_single_product&product_id=' + product_id + '&variation_id=' + variation_id + '&quantity=' + quantity,

					success:function(results) {
						// Trigger event so themes can refresh other areas.
						$( document.body ).trigger( 'wc_fragment_refresh' );
						$( document.body ).trigger( 'added_to_cart', [ $thisbutton ] );
					}
				});
			} else {
				jQuery.ajax ({
					url: astra.ajax_url,
					type:'POST',
					data:'action=astra_add_cart_single_product&product_id=' + product_id + '&quantity=' + quantity,

					success:function(results) {
						// Trigger event so themes can refresh other areas.
						$( document.body ).trigger( 'wc_fragment_refresh' );
						$( document.body ).trigger( 'added_to_cart', [ $thisbutton ] );
					}
				});
			}
		}
	};

	/**
	 * Update cart page elements after add to cart events.
	 */
	SingleAddToCartHandler.prototype.updateButton = function( e, button ) {
		button = typeof button === 'undefined' ? false : button;

		if ( $(button).parent().parent().find( 'button.single_add_to_cart_button' ).length === 1 ) {
			$(button).removeClass( 'loading' );
			$(button).addClass( 'added' );

			// View cart text.
			if ( ! astra.is_cart && $(button).parent().find( '.added_to_cart' ).length === 0 ) {
				$(button).after( ' <a href="' + astra.cart_url + '" class="added_to_cart wc-forward" title="' +
					astra.view_cart + '">' + astra.view_cart + '</a>' );
			}


		}
	};

	/**
	 * Init SingleAddToCartHandler.
	 */
	new SingleAddToCartHandler();

})(jQuery);
