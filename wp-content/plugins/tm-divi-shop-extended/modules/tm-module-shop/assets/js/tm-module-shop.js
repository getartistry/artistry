jQuery(document).ready(function( $ ) {
 
	//Add class to remove styles from another code
	$('.et_pb_shop_tm ul.products li.product a').addClass('et-module-different');

	var products = $('.et_pb_shop_tm ul.products');
	var product = $('.et_pb_shop_tm ul.products li.product');
	var module_container = $('div.et_pb_shop_tm');
	
	// Insert backlayer
	product.prepend("<div class='tm-backlayer'></div>");
		
	// Fixed Activate carroussel for module 
		if(typeof slider_columns == 'undefined'){
			var slider_columns = '0';
		}
		if(slider_columns === '0'){
			slider_columns = 5;
		}else{
			slider_columns = parseInt(slider_columns);
		}
		if (module_container.hasClass('tm_carrousel_active')){
			// Select containers
			var slider_products = $('.tm_carrousel_active ul.products');
			var slider_product = $('.tm_carrousel_active ul.products li.product');
			

			// Hide <em> products
			$('.tm_carrousel_active ul.products em').hide();
			

			// Wrap elements on divs for carroussel (slick)
			slider_products.wrap("<div class='tm-module-shop-products'></div>");
			slider_product.wrap("<div class='tm-module-shop-product'></div>");

			// T E S T I N G
				$('.tm_carrousel_active').each(function(){
				var cols = 5;
				var woo_wrap = $(this).find('div.woocommerce');
				if(woo_wrap.hasClass('columns-3')) cols = 3;
				if(woo_wrap.hasClass('columns-4')) cols = 4;
				if(woo_wrap.hasClass('columns-5')) cols = 5;
				if(woo_wrap.hasClass('columns-6')) cols = 6;

				if( (typeof cols) == undefined || (cols==='0') || (cols === '')){
					cols = 5;
				}else{
					cols = parseInt(cols);
				}
				console.log("cols: " + cols);
					var json_str = '{"slidesToShow": '+cols+', "slidesToScroll": 1, "autoplay": false, "autoplaySpeed": 3000, "accessibility": true, "arrows": true, "responsive": [ {"breakpoint": 1024, "settings": { "slidesToShow": 3, "slidesToScroll": 1, "infinite": true}},{"breakpoint": 767,"settings": {"slidesToShow": 1,"slidesToScroll": 1}},{"breakpoint": 600,"settings": {"slidesToShow": 1,"slidesToScroll": 1}},{"breakpoint": 480,"settings": {"slidesToShow": 1,"slidesToScroll": 1}}]}';
					$(this).find('ul.products').attr('data-slick', json_str);
				});
					
			// ----------------------------------------------------------------------
			var slider_products = $('.tm_carrousel_active ul.products');
			slider_products.slick();
			
		}
	//--------------------------------------------------------------------------------------------------


	// Category filter
		
		$('.et_pb_shop_tm .tm-cat-button').css('text-transform','capitalize'); // First letter to uppercase in buttons

		// Static var def for category in use
		function category_static (){};
		
		// Handle category buttons click event
		$('div.tm-cat-filter .tm-cat-button').click(function(){
			var all = 'all';
			all = all.normalize();
			if(	$(this).hasClass('tm-cat-button-active') && ( $(this).attr('data-cat') !== all) ){
				// Do nothing
			}else{
				// Button cat to lower case + normalize unicode
				category_static.button_cat = $(this).attr('data-cat');
				category_static.button_cat = category_static.button_cat.toLowerCase();
				category_static.button_cat = category_static.button_cat.normalize();
				console.log( 'Button cat:'+category_static.button_cat );

				// Cat filter container - deactivate all buttons and activate this
				var cat_filter_container = $(this).closest('.tm-cat-filter');
                cat_filter_container.find('.tm-cat-button').removeClass('tm-cat-button-active');
				$(this).addClass('tm-cat-button-active');

                // Creates hidden style for hide some elements			
				category_static.hidden_style = 'display:none !important; transition:1s; animation:ease-out; margin-right:24px !important;';
				
                $(this).closest('.et_pb_shop_tm').find('.product').each(function(index){
                    // Get categories for this product + to lower case + normalize unicode
					category_static.prod_cats = $(this).find('.tm-product-categories').text();
					category_static.prod_cats = category_static.prod_cats.toLowerCase();
					category_static.prod_cats = category_static.prod_cats.normalize();
					category_static.prod_cats = category_static.prod_cats.split(',');
					
					console.log('Product '+index+' cats:'+category_static.prod_cats);

					// If not button category in product categories hide product
					//console.log("testing if ["+category_static.button_cat+"] in "+category_static.prod_cats);
                    if( category_static.prod_cats.indexOf( category_static.button_cat ) < 0 ){
						
						$(this).addClass('tm-cat-prod-text-hidden');
						$(this).attr('style', category_static.hidden_style);
						$(this).closest('.et_pb_shop_tm').find('.product').addClass('tm-cat-filter-prod');
						
					}else{
						console.log('This product have '+category_static.button_cat+ ' category');
						$(this).attr('style','transition:1s;animation:ease-out;'); //show product
						$(this).removeClass('tm-cat-prod-text-hidden');
					}
				});
			}
			
			if(	$(this).attr('data-cat').normalize() === all ){
				if( !($(this).hasClass('tm-cat-button-active')) ){
					cat_filter_container.find('.tm-cat-button').removeClass('tm-cat-button-active');
					$(this).addClass('tm-cat-button-active');
				}
				$(this).closest('.et_pb_shop_tm').find('.product').removeClass('tm-cat-filter-prod');
				$(this).closest('.et_pb_shop_tm').find('.product').attr('style','transition:1s;animation:ease-out;'); //show product
				$(this).closest('.et_pb_shop_tm').find('.product').removeClass('tm-cat-prod-text-hidden');
			}


		});
	// -------------------------------------------------------------------------------------------------

});