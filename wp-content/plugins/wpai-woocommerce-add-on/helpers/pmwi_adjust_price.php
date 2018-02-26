<?php
function pmwi_adjust_price( $price, $field, $options ){

	switch ($field) {
		case 'variable_regular_price':
		case 'regular_price':
			
			if ( ! empty($options['single_product_regular_price_adjust']) ){

				switch ($options['single_product_regular_price_adjust_type']) {
					case '%':
						$price = ($price/100) * $options['single_product_regular_price_adjust'];
						break;

					case '$':

						$price += (double) $options['single_product_regular_price_adjust'];

						break;						
				}

				$price = ( (double) $price > 0) ? number_format( (double) $price, 2, '.', '' ) : 0;
			}

			break;
		case 'variable_sale_price':	
		case 'sale_price':
			
			if ( ! empty($options['single_product_sale_price_adjust']) ){

				switch ($options['single_product_sale_price_adjust_type']) {
					case '%':
						$price = ($price/100) * $options['single_product_sale_price_adjust'];
						break;

					case '$':

						$price += (double) $options['single_product_sale_price_adjust'];

						break;						
				}

				$price = ( (double) $price > 0) ? number_format( (double) $price, 2, '.', '' ) : 0;

			}

			break;
		
		/*default:
			
			return $price;*/

			break;
	}

	return $price;

}