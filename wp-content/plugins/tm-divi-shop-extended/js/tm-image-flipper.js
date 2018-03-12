jQuery(document).ready(function($){
	jQuery( 'ul.products li.pif-has-gallery a:first-child' ).hover( function() {
		jQuery( this ).children( '.wp-post-image' ).removeClass( 'fadeInDown' ).addClass( 'animated fadeOutUp' );
		jQuery( this ).children( '.secondary-image' ).removeClass( 'fadeOutUp' ).addClass( 'animated fadeInDown' );
	}, function() {
		jQuery( this ).children( '.wp-post-image' ).removeClass( 'fadeOutUp' ).addClass( 'fadeInDown' );
		jQuery( this ).children( '.secondary-image' ).removeClass( 'fadeInDown' ).addClass( 'fadeOutUp' );
	});

	function image_gallery(){}
		image_gallery.first_image = jQuery( '.woocommerce-product-gallery .woocommerce-product-gallery__image:nth-child(1) img' );
		image_gallerysecond_image = jQuery( '.woocommerce-product-gallery .woocommerce-product-gallery__image:nth-child(2) img' );

	jQuery( '.woocommerce-product-gallery .woocommerce-product-gallery__image:nth-child(1)' ).hover( function() {
		var first_image = jQuery( '.woocommerce-product-gallery .woocommerce-product-gallery__image:nth-child(1) img' );
		var second_image = jQuery( '.woocommerce-product-gallery .woocommerce-product-gallery__image:nth-child(2) img' );
		
		var first_image_src = first_image.attr("src");
		var second_image_src = second_image.attr("src");
		
		
		first_image.attr("src", second_image_src);
		first_image.attr("srcset", second_image_src);
		second_image.attr("src", first_image_src);
		second_image.attr("srcset", first_image_src);

	}, function() {
		var first_image = jQuery( '.woocommerce-product-gallery .woocommerce-product-gallery__image:nth-child(1) img' );
		var second_image = jQuery( '.woocommerce-product-gallery .woocommerce-product-gallery__image:nth-child(2) img' );
		
		var first_image_src = first_image.attr("src");
		var second_image_src = second_image.attr("src");
		
		
		first_image.attr("src", second_image_src);
		first_image.attr("srcset", second_image_src);
		second_image.attr("src", first_image_src);
		second_image.attr("srcset", first_image_src);
	});

});