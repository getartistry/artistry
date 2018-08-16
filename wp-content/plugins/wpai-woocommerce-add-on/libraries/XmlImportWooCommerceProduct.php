<?php

require_once dirname(__FILE__) . '/XmlImportWooCommerce.php';

class XmlImportWooCommerceProduct extends XmlImportWooCommerce{

	public $previousID;

    public $product_taxonomies;

	public $reserved_terms = array(
				'attachment', 'attachment_id', 'author', 'author_name', 'calendar', 'cat', 'category', 'category__and',
				'category__in', 'category__not_in', 'category_name', 'comments_per_page', 'comments_popup', 'cpage', 'day',
				'debug', 'error', 'exact', 'feed', 'hour', 'link_category', 'm', 'minute', 'monthnum', 'more', 'name',
				'nav_menu', 'nopaging', 'offset', 'order', 'orderby', 'p', 'page', 'page_id', 'paged', 'pagename', 'pb', 'perm',
				'post', 'post__in', 'post__not_in', 'post_format', 'post_mime_type', 'post_status', 'post_tag', 'post_type',
				'posts', 'posts_per_archive_page', 'posts_per_page', 'preview', 'robots', 's', 'search', 'second', 'sentence',
				'showposts', 'static', 'subpost', 'subpost_id', 'tag', 'tag__and', 'tag__in', 'tag__not_in', 'tag_id',
				'tag_slug__and', 'tag_slug__in', 'taxonomy', 'tb', 'term', 'type', 'w', 'withcomments', 'withoutcomments', 'year',
			);

	public function __construct( $options ){		

		global $wpdb;

		$this->wpdb   = $wpdb;		

		$this->import = $options['import'];
		$this->count  = $options['count'];
		$this->xml    = $options['xml'];
		$this->logger = $options['logger'];
		$this->chunk  = $options['chunk'];
		$this->xpath  = $options['xpath_prefix'];

        $product_taxonomies = array('post_format', 'product_type', 'product_shipping_class');
        $this->product_taxonomies = array_diff_key(get_taxonomies_by_object_type(array('product'), 'object'), array_flip($product_taxonomies));

	}

	public function parse()
	{
		$cxpath = $this->xpath . $this->import->xpath;

		$this->data = array();
		$records    = array();
		$tmp_files  = array();

		$this->chunk == 1 and $this->logger and call_user_func($this->logger, __('Composing product data...', 'wpai_woocommerce_addon_plugin'));

		$options = array('virtual', 'downloadable', 'enabled', 'featured', 'visibility', 'enable_reviews', 'manage_stock');

		foreach ($options as $option) 
		{
			if ($this->import->options['is_product_' . $option] == 'xpath' and "" != $this->import->options['single_product_' . $option])
			{
				$this->data['product_' . $option] = XmlImportParser::factory($this->xml, $cxpath, $this->import->options['single_product_' . $option], $file)->parse($records); $tmp_files[] = $file;						
			}
			else
			{
				$this->count and $this->data['product_' . $option] = array_fill(0, $this->count, $this->import->options['is_product_' . $option]);
			}
		}		
		
		$options = array('sku', 'variation_description', 'url', 'button_text', 'regular_price', 'sale_price', 'whosale_price', 'files', 
			'files_names', 'download_limit', 'download_expiry', 'download_type', 'stock_qty', 'weight', 'length', 'width', 'height', 'up_sells', 'cross_sells', 'purchase_note', 'menu_order');

		foreach ($options as $option) 
		{
			if ( "" != $this->import->options['single_product_' . $option] )
			{
				switch ($option) 
				{
					case 'regular_price':
					case 'sale_price':
						$this->data['product_' . $option] = array_map(array($this, 'adjust_price'), array_map(array($this, 'prepare_price'), XmlImportParser::factory($this->xml, $cxpath, $this->import->options['single_product_' . $option], $file)->parse($records)),  array_fill(0, $this->count, $option)); $tmp_files[] = $file;
						break;					
					
					default:
						$this->data['product_' . $option] = XmlImportParser::factory($this->xml, $cxpath, $this->import->options['single_product_' . $option], $file)->parse($records); $tmp_files[] = $file;
						break;
				}				
			}
			else
			{
				$this->count and $this->data['product_' . $option] = array_fill(0, $this->count, "");
			}
		}				

		$options = array('sale_price_dates_from', 'sale_price_dates_to');

		foreach ($options as $option) 
		{
			if ($this->import->options['is_regular_price_shedule'] and "" != $this->import->options['single_' . $option])
			{
				$this->data['product_' . $option] = XmlImportParser::factory($this->xml, $cxpath, $this->import->options['single_' . $option], $file)->parse($records); $tmp_files[] = $file;
			}
			else
			{
				$this->count and $this->data['product_' . $option] = array_fill(0, $this->count, "");
			}
		}

		$options = array('id', 'parent_id', 'id_first_is_parent_id', 'id_first_is_parent_title', 'id_first_is_variation', 'first_is_parent_id_parent_sku', 'first_is_parent_title_parent_sku');
		
		foreach ($options as $option) 
		{
			if ( isset($this->import->options['single_product_' . $option]) && "" != $this->import->options['single_product_' . $option] )
			{
				$this->data['single_product_' . preg_replace("%id$%", "ID", $option)] = XmlImportParser::factory($this->xml, $cxpath, $this->import->options['single_product_' . $option], $file)->parse($records); $tmp_files[] = $file;
			}
			else
			{
				$this->count and $this->data['single_product_' . preg_replace("%id$%", "ID", $option)] = array_fill(0, $this->count, "");
			}			
		}

		$options = array('type', 'tax_status', 'tax_class', 'shipping_class');
		
		foreach ($options as $option) 
		{
			$option_name = ($option == 'type') ? 'types' : $option;
			if ($this->import->options['is_multiple_product_' . $option] != 'yes' and "" != $this->import->options['single_product_' . $option])
			{
				$this->data['product_' . $option_name] = XmlImportParser::factory($this->xml, $cxpath, $this->import->options['single_product_' . $option], $file)->parse($records); $tmp_files[] = $file;		
			}
			else
			{
				$this->count and $this->data['product_' . $option_name] = array_fill(0, $this->count, $this->import->options['multiple_product_' . $option]);
			}			
		}				
											

		// Composing product Allow Backorders?						
		if ($this->import->options['product_allow_backorders'] == 'xpath' and "" != $this->import->options['single_product_allow_backorders']){
			$this->data['product_allow_backorders'] = XmlImportParser::factory($this->xml, $cxpath, $this->import->options['single_product_allow_backorders'], $file)->parse($records); $tmp_files[] = $file;						
		}
		else{
			$this->count and $this->data['product_allow_backorders'] = array_fill(0, $this->count, $this->import->options['product_allow_backorders']);
		}

		// Composing product Sold Individually?					
		if ($this->import->options['product_sold_individually'] == 'xpath' and "" != $this->import->options['single_product_sold_individually']){
			$this->data['product_sold_individually'] = XmlImportParser::factory($this->xml, $cxpath, $this->import->options['single_product_sold_individually'], $file)->parse($records); $tmp_files[] = $file;						
		}
		else{
			$this->count and $this->data['product_sold_individually'] = array_fill(0, $this->count, $this->import->options['product_sold_individually']);
		}

		// Composing product Stock status							
		if ($this->import->options['product_stock_status'] == 'xpath' and "" != $this->import->options['single_product_stock_status'])
		{
			$this->data['product_stock_status'] = XmlImportParser::factory($this->xml, $cxpath, $this->import->options['single_product_stock_status'], $file)->parse($records); $tmp_files[] = $file;						
		}
		elseif($this->import->options['product_stock_status'] == 'auto')
		{
			$this->count and $this->data['product_stock_status'] = array_fill(0, $this->count, $this->import->options['product_stock_status']);

            $nostock = absint( max( get_option( 'woocommerce_notify_no_stock_amount' ), 0 ) );

			foreach ($this->data['product_stock_qty'] as $key => $value) 
			{
				if ($this->data['product_manage_stock'][$key] == 'yes')
				{
					$this->data['product_stock_status'][$key] = (( (int) $value === 0 or (int) $value <= $nostock ) and $value != "") ? 'outofstock' : 'instock';
				}
				else{
					$this->data['product_stock_status'][$key] = 'instock';
				}
			}
		}
		else
		{
			$this->count and $this->data['product_stock_status'] = array_fill(0, $this->count, $this->import->options['product_stock_status']);
		}

		// Composing grouping product
		if ($this->import->options['is_multiple_grouping_product'] != 'yes')
		{		
			if ($this->import->options['grouping_indicator'] == 'xpath')
			{			
				if ("" != $this->import->options['single_grouping_product'])
				{
					$this->data['product_grouping_parent'] = XmlImportParser::factory($this->xml, $cxpath, $this->import->options['single_grouping_product'], $file)->parse($records); $tmp_files[] = $file;						
				}
				else
				{
					$this->count and $this->data['product_grouping_parent'] = array_fill(0, $this->count, $this->import->options['multiple_grouping_product']);
				}
			}
			else
			{
				if ("" != $this->import->options['custom_grouping_indicator_name'] and "" != $this->import->options['custom_grouping_indicator_value'] )
				{
					$this->data['custom_grouping_indicator_name']  = XmlImportParser::factory($this->xml, $cxpath, $this->import->options['custom_grouping_indicator_name'], $file)->parse($records); $tmp_files[] = $file;	
					$this->data['custom_grouping_indicator_value'] = XmlImportParser::factory($this->xml, $cxpath, $this->import->options['custom_grouping_indicator_value'], $file)->parse($records); $tmp_files[] = $file;	
				}
				else
				{
					$this->count and $this->data['custom_grouping_indicator_name']  = array_fill(0, $this->count, "");
					$this->count and $this->data['custom_grouping_indicator_value'] = array_fill(0, $this->count, "");
				}
			}		
		}
		else{
			$this->count and $this->data['product_grouping_parent'] = array_fill(0, $this->count, $this->import->options['multiple_grouping_product']);
		}

		// Composing product is Manage stock									
		if ($this->import->options['is_variation_product_manage_stock'] == 'xpath' and "" != $this->import->options['single_variation_product_manage_stock']){
			
			$this->data['v_product_manage_stock'] = XmlImportParser::factory($this->xml, $cxpath, $this->import->options['single_variation_product_manage_stock'], $file)->parse($records); $tmp_files[] = $file;						
			
		}
		else{
			$this->count and $this->data['v_product_manage_stock'] = array_fill(0, $this->count, $this->import->options['is_variation_product_manage_stock']);
		}

		// Composing Stock Qty
		if ($this->import->options['variation_stock'] != "")
		{		
			$this->data['v_stock'] = XmlImportParser::factory($this->xml, $cxpath, $this->import->options['variation_stock'], $file)->parse($records); $tmp_files[] = $file;
		}
		else
		{
			$this->count and $this->data['v_stock'] = array_fill(0, $this->count, '');
		}

		// Composing Stock Status
		if ($this->import->options['variation_stock_status'] == 'xpath' and "" != $this->import->options['single_variation_stock_status'])
		{
			$this->data['v_stock_status'] = XmlImportParser::factory($this->xml, $cxpath, $this->import->options['single_variation_stock_status'], $file)->parse($records); $tmp_files[] = $file;						
		}
		elseif($this->import->options['variation_stock_status'] == 'auto')
		{
			$this->count and $this->data['v_stock_status'] = array_fill(0, $this->count, $this->import->options['variation_stock_status']);

            $nostock = absint( max( get_option( 'woocommerce_notify_no_stock_amount' ), 0 ) );

			foreach ($this->data['v_stock'] as $key => $value) 
			{
				if ($this->data['v_product_manage_stock'][$key] == 'yes')
				{
					$this->data['v_stock_status'][$key] = ( ( (int) $value === 0 or (int) $value <= $nostock ) and $value != "") ? 'outofstock' : 'instock';
				}
				else
				{
					$this->data['v_stock_status'][$key] = 'instock';
				}
			}
		}
		else{
			$this->count and $this->data['v_stock_status'] = array_fill(0, $this->count, $this->import->options['variation_stock_status']);
		}

		if ($this->import->options['matching_parent'] != "auto") 
		{					
			switch ($this->import->options['matching_parent']) {
				case 'first_is_parent_id':
					$this->data['single_product_parent_ID'] = $this->data['single_product_ID'] = $this->data['single_product_id_first_is_parent_ID'];
					break;
				case 'first_is_parent_title':
					$this->data['single_product_parent_ID'] = $this->data['single_product_ID'] = $this->data['single_product_id_first_is_parent_title'];
					break;
				case 'first_is_variation':
					$this->data['single_product_parent_ID'] = $this->data['single_product_ID'] = $this->data['single_product_id_first_is_variation'];
					break;						
			}					
		}

		// Composing variations attributes					
		$this->chunk == 1 and $this->logger and call_user_func($this->logger, __('Composing variations attributes...', 'wpai_woocommerce_addon_plugin'));
		$attribute_keys    = array(); 
		$attribute_values  = array();	

		$attribute_options = array(
			'in_variations'   => array(),
			'is_visible'      => array(),
			'is_taxonomy'     => array(),
			'is_create_terms' => array()
		);
						
		if ( ! empty($this->import->options['attribute_name'][0]))
		{			
			foreach ($this->import->options['attribute_name'] as $j => $attribute_name) { if ($attribute_name == "") continue;					

				$attribute_keys[$j]   = XmlImportParser::factory($this->xml, $cxpath, $attribute_name, $file)->parse($records); $tmp_files[] = $file;												
				$attribute_values[$j] = XmlImportParser::factory($this->xml, $cxpath, $this->import->options['attribute_value'][$j], $file)->parse($records); $tmp_files[] = $file;				

				if (empty($this->import->options['is_advanced'][$j]))
				{					
					$attribute_options['in_variations'][$j] = XmlImportParser::factory($this->xml, $cxpath, $this->import->options['in_variations'][$j], $file)->parse($records); $tmp_files[] = $file;				
					$attribute_options['is_visible'][$j]   = XmlImportParser::factory($this->xml, $cxpath, $this->import->options['is_visible'][$j], $file)->parse($records); $tmp_files[] = $file;
					$attribute_options['is_taxonomy'][$j]  = XmlImportParser::factory($this->xml, $cxpath, $this->import->options['is_taxonomy'][$j], $file)->parse($records); $tmp_files[] = $file;
					$attribute_options['is_create_terms'][$j] = XmlImportParser::factory($this->xml, $cxpath, $this->import->options['create_taxonomy_in_not_exists'][$j], $file)->parse($records); $tmp_files[] = $file;								
				}				
				else
				{
					$options = array('in_variations', 'is_visible', 'is_taxonomy', 'is_create_terms');

					foreach ($options as $option) 
					{
						if ($this->import->options['advanced_' . $option][$j] == 'xpath' and "" != $this->import->options['advanced_'. $option .'_xpath'][$j])
						{
							$attribute_options[$option][$j] = XmlImportParser::factory($this->xml, $cxpath, $this->import->options['advanced_'. $option .'_xpath'][$j], $file)->parse($records); $tmp_files[] = $file;												
						}
						else
						{
							$attribute_options[$option][$j] = XmlImportParser::factory($this->xml, $cxpath, $this->import->options['advanced_'. $option][$j], $file)->parse($records); $tmp_files[] = $file;						
						}

						foreach ($attribute_options[$option][$j] as $key => $value) 
						{
							if ( ! in_array($value, array('yes', 'no')))
							{
								$attribute_options[$option][$j][$key] = 1;
							}
							else
							{
								$attribute_options[$option][$j][$key] = ($value == 'yes') ? 1 : 0;
							}
						}
					}					
				}
			}			
		}					
		
		// serialized attributes for product variations
		$this->data['serialized_attributes'] = array();

		if ( ! empty($attribute_keys) )
		{
			foreach ($attribute_keys as $j => $attribute_name) 
			{							
				$this->data['serialized_attributes'][] = array(
					'names' => $attribute_name,
					'value' => $attribute_values[$j],
					'is_visible'   => $attribute_options['is_visible'][$j],
					'in_variation' => $attribute_options['in_variations'][$j],
					'in_taxonomy'  => $attribute_options['is_taxonomy'][$j],
					'is_create_taxonomy_terms' => $attribute_options['is_create_terms'][$j]
				);						
			}
		} 

		foreach ($tmp_files as $file) { // remove all temporary files created
			unlink($file);
		}

		return $this->data;
	}

	public function is_update_data_allowed($option = '')
	{
		if ($this->import->options['is_keep_former_posts'] == 'yes') return false;		
		if ($this->import->options['update_all_data'] == 'yes') return true;
		return (!empty($this->import->options[$option])) ? true : false;
	}	

	public function import( $importData )
	{		
		extract($importData); 

		$cxpath = $xpath_prefix . $this->import->xpath;

		global $woocommerce;		

		extract($this->data);

		$is_new_product = empty($articleData['ID']);

		$product_type 	= empty( $product_types[$i] ) ? 'simple' : sanitize_title( stripslashes( $product_types[$i] ) );

        $product 	  = WC()->product_factory->get_product($pid);

		if ($this->import->options['update_all_data'] == 'no' and ! $this->import->options['is_update_product_type'] and ! $is_new_product ){
			if ( ! empty($product->product_type) ) $product_type = $product->product_type;
		}		
		
		$this->articleData = $articleData;

		$total_sales = get_post_meta($pid, 'total_sales', true);

		if ( empty($total_sales)) update_post_meta($pid, 'total_sales', '0');

		$is_downloadable 	= $product_downloadable[$i];
		$is_virtual 		= $product_virtual[$i];
		$is_featured 		= $product_featured[$i];

		// Product type + Downloadable/Virtual
		if ($is_new_product or $this->import->options['update_all_data'] == 'yes' or ($this->import->options['update_all_data'] == 'no' and $this->import->options['is_update_product_type'])) { 						
			$product_type_term = is_exists_term($product_type, 'product_type', 0);	
			if ( ! empty($product_type_term) and ! is_wp_error($product_type_term) ){					
				$this->associate_terms( $pid, array( (int) $product_type_term['term_taxonomy_id'] ), 'product_type' );	
			}			
		}

		if ( ! $is_new_product )
		{
			delete_post_meta($pid, '_is_first_variation_created');
		}

		$this->pushmeta($pid, '_downloadable', ($is_downloadable == "yes") ? 'yes' : 'no' );
		$this->pushmeta($pid, '_virtual', ($is_virtual == "yes") ? 'yes' : 'no' );

		// Update post meta
		$this->pushmeta($pid, '_regular_price', ($product_regular_price[$i] == "") ? '' : stripslashes( $product_regular_price[$i] ) );
        if ( $product_sale_price[$i] != '' ){
            $_regular_price = get_post_meta($pid, '_regular_price', true);
            if ($product_sale_price[$i] > $_regular_price){
                $product_sale_price[$i] = '';
            }
        }
		$this->pushmeta($pid, '_sale_price', ($product_sale_price[$i] == "") ? '' : stripslashes( $product_sale_price[$i] ) );
		$this->pushmeta($pid, '_tax_status', stripslashes( $product_tax_status[$i] ) );
		$this->pushmeta($pid, '_tax_class', strtolower($product_tax_class[$i]) == 'standard' ? '' : stripslashes( $product_tax_class[$i] ) );
		$this->pushmeta($pid, '_purchase_note', stripslashes( $product_purchase_note[$i] ) );
        if (version_compare(WOOCOMMERCE_VERSION, '3.0') < 0) {
            $this->pushmeta($pid, '_featured', ($is_featured == "yes") ? 'yes' : 'no');
            $this->pushmeta($pid, '_visibility', stripslashes($product_visibility[$i]));
        }

		// Dimensions		
		if ( $is_virtual == 'yes' ) {
			$this->pushmeta($pid, '_weight', '' );
			$this->pushmeta($pid, '_length', '' );
			$this->pushmeta($pid, '_width', '' );
			$this->pushmeta($pid, '_height', '' );			
		}
		else{
            $this->pushmeta($pid, '_weight', stripslashes( $product_weight[$i] ) );
            $this->pushmeta($pid, '_length', stripslashes( $product_length[$i] ) );
            $this->pushmeta($pid, '_width', stripslashes( $product_width[$i] ) );
            $this->pushmeta($pid, '_height', stripslashes( $product_height[$i] ) );
        }

		if ($is_new_product or $this->is_update_data_allowed('is_update_comment_status')) $this->wpdb->update( $this->wpdb->posts, array('comment_status' => ( in_array($product_enable_reviews[$i], array('yes', 'open')) ) ? 'open' : 'closed' ), array('ID' => $pid));

		if ($is_new_product or $this->is_update_data_allowed('is_update_menu_order')) $this->wpdb->update( $this->wpdb->posts, array('menu_order' => ($product_menu_order[$i] != '') ? (int) $product_menu_order[$i] : 0 ), array('ID' => $pid));

		// Save shipping class
		if ( pmwi_is_update_taxonomy($articleData, $this->import->options, 'product_shipping_class') )
		{			

			$p_shipping_class = ($product_type != 'external') ? $product_shipping_class[$i] : '';			

			if ( $p_shipping_class != '' )
			{

				if ( (int) $product_shipping_class[$i] !== 0 )
				{				

					if ( (int) $product_shipping_class[$i] > 0){

						$t_shipping_class = get_term_by('slug', $p_shipping_class, 'product_shipping_class');		
						// For compatibility with WPML plugin
						$t_shipping_class = apply_filters('wp_all_import_term_exists', $t_shipping_class, 'product_shipping_class', $p_shipping_class, null);							

						if ( ! empty($t_shipping_class) and ! is_wp_error($t_shipping_class) ) 
						{
							$p_shipping_class = (int) $t_shipping_class->term_taxonomy_id; 						
						}
						else
						{						
							$t_shipping_class = is_exists_term( (int) $p_shipping_class, 'product_shipping_class');
												
							if ( ! empty($t_shipping_class) and ! is_wp_error($t_shipping_class) )
							{												
								$p_shipping_class = (int) $t_shipping_class['term_taxonomy_id']; 	
							}
							else
							{
								$t_shipping_class = wp_insert_term(
									$p_shipping_class, // the term 
								  	'product_shipping_class' // the taxonomy										  	
								);	

								if ( ! empty($t_shipping_class) and ! is_wp_error($t_shipping_class) )
								{												
									$p_shipping_class = (int) $t_shipping_class['term_taxonomy_id']; 	
								}
							}
						}						
					}
					else
					{
						$p_shipping_class = '';
					}						
				}
				else{
					
					$t_shipping_class = is_exists_term($product_shipping_class[$i], 'product_shipping_class');
					
					if ( ! empty($t_shipping_class) and ! is_wp_error($t_shipping_class) )
					{
						$p_shipping_class = (int) $t_shipping_class['term_taxonomy_id']; 	
					}
					else
					{
						$t_shipping_class = is_exists_term(htmlspecialchars(strtolower($product_shipping_class[$i])), 'product_shipping_class');
						
						if ( ! empty($t_shipping_class) and ! is_wp_error($t_shipping_class) )
						{
							$p_shipping_class = (int) $t_shipping_class['term_taxonomy_id']; 	
						}
						else
						{
							$t_shipping_class = wp_insert_term(
								$product_shipping_class[$i], // the term 
							  	'product_shipping_class' // the taxonomy										  	
							);	

							if ( ! empty($t_shipping_class) and ! is_wp_error($t_shipping_class) )
							{												
								$p_shipping_class = (int) $t_shipping_class['term_taxonomy_id']; 	
							}
						}
					}							
				}
			}
			
			if ( $p_shipping_class !== false and ! is_wp_error($p_shipping_class)) $this->associate_terms( $pid, array( $p_shipping_class ), 'product_shipping_class' );	
			
		}

		// Unique SKU
		$sku				= ($is_new_product) ? '' : get_post_meta($pid, '_sku', true);
        $new_sku 			= wc_clean(addslashes($product_sku[$i]));

        if ( ( in_array($product_type, array('variation', 'variable')) or $product_types[$i] == "variable" ) and ! $this->import->options['link_all_variations'] ){
            switch ($this->import->options['matching_parent']){
                case 'first_is_parent_id':
                    if (!empty($single_product_first_is_parent_id_parent_sku[$i])){
                        update_post_meta($pid, '_parent_sku', $single_product_first_is_parent_id_parent_sku[$i]);
                    }
                    break;
                case 'first_is_variation':
                    if (!empty($single_product_first_is_parent_title_parent_sku[$i])){
                        update_post_meta($pid, '_parent_sku', $single_product_first_is_parent_title_parent_sku[$i]);
                    }
                    break;
            }
        }

		if ( $new_sku == '' and $this->import->options['disable_auto_sku_generation'] ) {
			$this->pushmeta($pid, '_sku', '' );				
		}
		elseif ( $new_sku == '' and ! $this->import->options['disable_auto_sku_generation'] ) {
			if ($is_new_product or $this->is_update_cf('_sku')){
				$unique_keys = XmlImportParser::factory($xml, $cxpath, $this->import->options['unique_key'], $file)->parse(); $tmp_files[] = $file;
				foreach ($tmp_files as $file) { // remove all temporary files created
					@unlink($file);
				}
				$new_sku = substr(md5($unique_keys[$i]), 0, 12);

                if ( ( in_array($product_type, array('variation', 'variable')) or $product_types[$i] == "variable" ) and ! $this->import->options['link_all_variations'] ){
                    switch ($this->import->options['matching_parent']){
                        case 'first_is_parent_id':
                            if (empty($single_product_first_is_parent_id_parent_sku[$i])){
                                update_post_meta($pid, '_parent_sku', strrev($new_sku));
                            }
                            break;
                        case 'first_is_variation':
                            if (empty($single_product_first_is_parent_title_parent_sku[$i])){
                                update_post_meta($pid, '_parent_sku', strrev($new_sku));
                            }
                            break;
                    }
                }
			}
		}
		if ( $new_sku != '' and $new_sku !== $sku ) {
			if ( ! empty( $new_sku ) ) {
				if ( ! $this->import->options['disable_sku_matching'] and 
					$this->wpdb->get_var( $this->wpdb->prepare("
						SELECT ".$this->wpdb->posts.".ID
					    FROM ".$this->wpdb->posts."
					    LEFT JOIN ".$this->wpdb->postmeta." ON (".$this->wpdb->posts.".ID = ".$this->wpdb->postmeta.".post_id)
					    WHERE (".$this->wpdb->posts.".post_type = 'product'
					    OR ".$this->wpdb->posts.".post_type = 'product_variation')
					    AND ".$this->wpdb->posts.".post_status = 'publish'
					    AND ".$this->wpdb->postmeta.".meta_key = '_sku' AND ".$this->wpdb->postmeta.".meta_value = '%s'
					 ", $new_sku ) )
					) {
					$logger and call_user_func($logger, sprintf(__('<b>WARNING</b>: Product SKU must be unique.', 'wpai_woocommerce_addon_plugin')));
									
				} else {					
					$this->pushmeta($pid, '_sku', $new_sku );							
				}
			} else {
				$this->pushmeta($pid, '_sku', '' );
			}
		}

		$this->pushmeta($pid, '_variation_description', $product_variation_description[$i] );

		// Save Attributes
		$attributes = array();

		$is_variation_attributes_defined = false;

        $max_attribute_length = apply_filters('wp_all_import_max_woo_attribute_term_length', 199);

		if ( $this->import->options['update_all_data'] == "yes" or ( $this->import->options['update_all_data'] == "no" and $this->import->options['is_update_attributes']) or $is_new_product){ // Update Product Attributes		

			$is_update_attributes = true;

			if ( !empty($serialized_attributes) ) {
				
				$attribute_position = 0;

				foreach ($serialized_attributes as $anum => $attr_data) {	$attr_name = $attr_data['names'][$i];

					if (empty($attr_name)) continue;

					// $attr_names[] = $attr_name; 

					$is_visible 	= intval( $attr_data['is_visible'][$i] );
					$is_variation 	= intval( $attr_data['in_variation'][$i] );
					$is_taxonomy 	= intval( $attr_data['in_taxonomy'][$i] );

					if ( $is_variation and $attr_data['value'][$i] != "" ) {
				 		$is_variation_attributes_defined = true;
				 	}

					// Update only these Attributes, leave the rest alone
					if ( ! $is_new_product and $this->import->options['update_all_data'] == "no" and $this->import->options['is_update_attributes'] and $this->import->options['update_attributes_logic'] == 'only'){
						if ( ! empty($this->import->options['attributes_list']) and is_array($this->import->options['attributes_list'])) {
							if ( ! in_array( ( ($is_taxonomy) ? wc_attribute_taxonomy_name( $attr_name ) : $attr_name ) , array_filter($this->import->options['attributes_list'], 'trim'))){ 
								$attribute_position++;
								continue;
							}
						}
						else {
							$is_update_attributes = false;
							break;
						}
					}

					// Leave these attributes alone, update all other Attributes
					if ( ! $is_new_product and $this->import->options['update_all_data'] == "no" and $this->import->options['is_update_attributes'] and $this->import->options['update_attributes_logic'] == 'all_except'){
						if ( ! empty($this->import->options['attributes_list']) and is_array($this->import->options['attributes_list'])) {
							if ( in_array( ( ($is_taxonomy) ? wc_attribute_taxonomy_name( $attr_name ) : $attr_name ) , array_filter($this->import->options['attributes_list'], 'trim'))){ 
								$attribute_position++;
								continue;
							}
						}
					}

					if ( $is_taxonomy ) {										

						if ( isset( $attr_data['value'][$i] ) ) {
					 		
					 		$values = array_map( 'stripslashes', explode( '|', $attr_data['value'][$i] ) );

						 	// Remove empty items in the array
						 	$values = array_filter( $values, array($this, "filtering") );			

						 	if (intval($attr_data['is_create_taxonomy_terms'][$i])){
                                $attr_name = $this->create_taxonomy($attr_name, $logger);
                            }

						 	if ( ! empty($values) and taxonomy_exists( wc_attribute_taxonomy_name( $attr_name ) )){

						 		$attr_values = array();						 								 		
						 			
						 		foreach ($values as $key => $val) {

						 			$value = substr($val, 0, $max_attribute_length);

						 			$term = get_term_by('name', $value, wc_attribute_taxonomy_name( $attr_name ), ARRAY_A);
						 			
						 			// For compatibility with WPML plugin
						 			$term = apply_filters('wp_all_import_term_exists', $term, wc_attribute_taxonomy_name( $attr_name ), $value, null);

						 			if ( empty($term) and !is_wp_error($term) ){		

							 			$term = is_exists_term($value, wc_attribute_taxonomy_name( $attr_name ));							 			

							 			if ( empty($term) and !is_wp_error($term) ){																																
											$term = is_exists_term(htmlspecialchars($value), wc_attribute_taxonomy_name( $attr_name ));	
											if ( empty($term) and !is_wp_error($term) and intval($attr_data['is_create_taxonomy_terms'][$i])){		
												
												$term = wp_insert_term(
													$value, // the term 
												  	wc_attribute_taxonomy_name( $attr_name ) // the taxonomy										  	
												);													
											}
										}
									}

									if ( ! is_wp_error($term) )				
									{										
										$attr_values[] = (int) $term['term_taxonomy_id']; 
									}																		

						 		}

						 		$values = $attr_values;
						 		$values = array_map( 'intval', $values );
								$values = array_unique( $values );
						 	} 
						 	else $values = array(); 					 							 	

					 	} 				 				 						 	

				 		// Update post terms
				 		if ( taxonomy_exists( wc_attribute_taxonomy_name( $attr_name ) ))			 			
				 			$this->associate_terms( $pid, $values, wc_attribute_taxonomy_name( $attr_name ) );				 					 	
				 		
				 		if ( !empty($values) ) {									 			
					 		// Add attribute to array, but don't set values
					 		$attributes[ sanitize_title(wc_attribute_taxonomy_name( $attr_name )) ] = array(
						 		'name' 			=> wc_attribute_taxonomy_name( $attr_name ),
						 		'value' 		=> $attr_data['value'][$i],
						 		'position' 		=> $attribute_position,
						 		'is_visible' 	=> $is_visible,
						 		'is_variation' 	=> $is_variation,
						 		'is_taxonomy' 	=> 1,
						 		'is_create_taxonomy_terms' => (!empty($attr_data['is_create_taxonomy_terms'][$i])) ? 1 : 0
						 	);

					 	}

				 	} else {

				 		if ( taxonomy_exists( wc_attribute_taxonomy_name( $attr_name ) )){
				 			//wp_set_object_terms( $pid, NULL, wc_attribute_taxonomy_name( $attr_name ) );			 		
				 			$this->associate_terms( $pid, NULL, wc_attribute_taxonomy_name( $attr_name ) );	
				 		}

				 		if (trim($attr_data['value'][$i]) != ""){

					 		// Custom attribute - Add attribute to array and set the values
						 	$attributes[ sanitize_title( $attr_name ) ] = array(
						 		'name' 			=> sanitize_text_field( $attr_name ),
						 		'value' 		=> trim($attr_data['value'][$i]),
						 		'position' 		=> $attribute_position,
						 		'is_visible' 	=> $is_visible,
						 		'is_variation' 	=> $is_variation,
						 		'is_taxonomy' 	=> 0
						 	);
						}

				 	}				 	

				 	$attribute_position++;
				}							
			}						
			
			if ($is_new_product or $is_update_attributes) {
				
				$current_product_attributes = get_post_meta($pid, '_product_attributes', true);

				update_post_meta($pid, '_product_attributes', ( ! empty($current_product_attributes)) ? array_merge($current_product_attributes, $attributes) : $attributes );					
			}

		}else{

			$is_variation_attributes_defined = true;

		}	// is update attributes

		// Sales and prices
		if ( ! in_array( $product_type, array( 'grouped' ) ) ) {

			$date_from = isset( $product_sale_price_dates_from[$i] ) ? $product_sale_price_dates_from[$i] : '';
			$date_to   = isset( $product_sale_price_dates_to[$i] ) ? $product_sale_price_dates_to[$i] : '';

			// Dates
			if ( $date_from ){
				$this->pushmeta($pid, '_sale_price_dates_from', strtotime( $date_from ));				
			}
			else{
				$this->pushmeta($pid, '_sale_price_dates_from', '');				
			}

			if ( $date_to ){
				$this->pushmeta($pid, '_sale_price_dates_to', strtotime( $date_to ));								
			}
			else{
				$this->pushmeta($pid, '_sale_price_dates_to', '');												
			}

			if ( $date_to && ! $date_from ){
				$this->pushmeta($pid, '_sale_price_dates_from', strtotime( 'NOW', current_time( 'timestamp' ) ) );	
			}

			// Update price if on sale			
			if ( ! empty($this->articleData['ID']) and ! $this->is_update_cf('_sale_price') )
			{
				$product_sale_price[$i] = get_post_meta($pid, '_sale_price', true);				
			}

			if ( $product_sale_price[$i] != '' && $date_to == '' && $date_from == '' ){				

				$this->pushmeta($pid, '_price', ($product_sale_price[$i] == "") ? '' : stripslashes( $product_sale_price[$i] ));						
				
			}
			else{

                // Update price if on sale
                if ( ! empty($this->articleData['ID']) and ! $this->is_update_cf('_regular_price') )
                {
                    $product_regular_price[$i] = get_post_meta($pid, '_regular_price', true);
                }

				$this->pushmeta($pid, '_price', ($product_regular_price[$i] == "") ? '' : stripslashes( $product_regular_price[$i] ));						
			}

			if ( $product_sale_price[$i] != '' && $date_from && strtotime( $date_from ) < strtotime( 'NOW', current_time( 'timestamp' ) ) ){				
				$this->pushmeta($pid, '_price', ($product_sale_price[$i] == "") ? '' : stripslashes( $product_sale_price[$i] ));				
			}

			if ( $date_to && strtotime( $date_to ) < strtotime( 'NOW', current_time( 'timestamp' ) ) ) {
				$this->pushmeta($pid, '_price', ($product_regular_price[$i] == "") ? '' : stripslashes( $product_regular_price[$i] ));
			}
		}

		if (in_array( $product_type, array( 'simple', 'external', 'variable' ) )) {

			if ($this->import->options['is_multiple_grouping_product'] != 'yes'){
				if ($this->import->options['grouping_indicator'] == 'xpath' and ! preg_match("%^[0-9]*$%", $product_grouping_parent[$i])){
					$dpost = pmxi_findDuplicates(array(
						'post_type' => 'product',
						'ID' => $pid,
						'post_parent' => $articleData['post_parent'],
						'post_title' => $product_grouping_parent[$i]
					));				
					if (!empty($dpost))
						$product_grouping_parent[$i] = $dpost[0];	
					else				
						$product_grouping_parent[$i] = 0;
				}
				elseif ($this->import->options['grouping_indicator'] != 'xpath'){
					$dpost = pmxi_findDuplicates($articleData, $custom_grouping_indicator_name[$i], $custom_grouping_indicator_value[$i], 'custom field');
					if (!empty($dpost))
						$product_grouping_parent[$i] = array_shift($dpost);
					else				
						$product_grouping_parent[$i] = 0;
				}
			}

			if ( "" != $product_grouping_parent[$i] and absint($product_grouping_parent[$i]) > 0){

				$this->wpdb->update( $this->wpdb->posts, array('post_parent' => absint( $product_grouping_parent[$i] ) ), array('ID' => $pid));

                $all_grouped_products = get_post_meta($product_grouping_parent[$i], '_children', true);

                if (empty($all_grouped_products)) $all_grouped_products = array();

                if (!empty($all_grouped_products) & !is_array($all_grouped_products)){
                    $all_grouped_products = array($all_grouped_products);
                }

                if ( ! in_array($pid, $all_grouped_products) ){
                    $all_grouped_products[] = $pid;
                    update_post_meta($product_grouping_parent[$i], '_children', $all_grouped_products);
                }
			}
		}	

		// Update parent if grouped so price sorting works and stays in sync with the cheapest child
		if ( $product_type == 'grouped' || ( "" != $product_grouping_parent[$i] and absint($product_grouping_parent[$i]) > 0)) {

			$clear_parent_ids = array();													

			if ( $product_type == 'grouped' )
				$clear_parent_ids[] = $pid;		

			if ( "" != $product_grouping_parent[$i] and absint($product_grouping_parent[$i]) > 0 )
				$clear_parent_ids[] = absint( $product_grouping_parent[$i] );					

			if ( $clear_parent_ids ) {
				foreach( $clear_parent_ids as $clear_id ) {

					$children_by_price = get_posts( array(
						'post_parent' 	=> $clear_id,
						'orderby' 		=> 'meta_value_num',
						'order'			=> 'asc',
						'meta_key'		=> '_price',
						'posts_per_page'=> 1,
						'post_type' 	=> 'product',
						'fields' 		=> 'ids'
					) );
					if ( $children_by_price ) {
						foreach ( $children_by_price as $child ) {
							$child_price = get_post_meta( $child, '_price', true );							
							update_post_meta( $clear_id, '_price', $child_price );
						}
					}

					// Clear cache/transients
					//wc_delete_product_transients( $clear_id );
				}
			}
		}	

		// Sold Individuall
		if ( "yes" == $product_sold_individually[$i] ) {
			$this->pushmeta($pid, '_sold_individually', 'yes');			
		} else {
			$this->pushmeta($pid, '_sold_individually', '');			
		}

		// Stock Data
        $stock_data_args = array(
            'stock_status' => $product_stock_status[$i],
            'manage_stock' => $product_manage_stock[$i],
            'allow_backorders' => $product_allow_backorders[$i],
            'qty' => $product_stock_qty[$i]
        );
        $this->import_stock_data($pid, $product_type, $stock_data_args );

		// Upsells
		$this->import_linked_products($pid, $product_up_sells[$i], '_upsell_ids', $is_new_product);

		// Cross sells
		$this->import_linked_products($pid, $product_cross_sells[$i], '_crosssell_ids', $is_new_product);		

		// Downloadable options
		if ( $is_downloadable == 'yes' ) {

			$_download_limit = absint( $product_download_limit[$i] );
			if ( ! $_download_limit )
				$_download_limit = ''; // 0 or blank = unlimited

			$_download_expiry = absint( $product_download_expiry[$i] );
			if ( ! $_download_expiry )
				$_download_expiry = ''; // 0 or blank = unlimited
			
			// file paths will be stored in an array keyed off md5(file path)
			if ( !empty( $product_files[$i] ) ) {
				$_file_paths = array();
				
				$file_paths = explode( $this->import->options['product_files_delim'] , $product_files[$i] );
				$file_names = explode( $this->import->options['product_files_names_delim'] , $product_files_names[$i] );

				foreach ( $file_paths as $fn => $file_path ) {
					$file_path = trim( $file_path );					
					$_file_paths[ md5( $file_path ) ] = array('name' => ((!empty($file_names[$fn])) ? $file_names[$fn] : basename($file_path)), 'file' => $file_path);
				}								

				$this->pushmeta($pid, '_downloadable_files', $_file_paths);	

			}
			if ( isset( $product_download_limit[$i] ) )
				$this->pushmeta($pid, '_download_limit', esc_attr( $_download_limit ));	

			if ( isset( $product_download_expiry[$i] ) )
				$this->pushmeta($pid, '_download_expiry', esc_attr( $_download_expiry ));	
				
			if ( isset( $product_download_type[$i] ) )
				$this->pushmeta($pid, '_download_type', esc_attr( $product_download_type[$i] ));	
				
		}

		// Product url
		if ( $product_type == 'external' ) {
			if ( isset( $product_url[$i] ) && $product_url[$i] ){							
				$this->auto_cloak_links($this->import, $product_url[$i]);
				$this->pushmeta($pid, '_product_url', esc_url_raw( $product_url[$i] ));					
			}
			if ( isset( $product_button_text[$i] ) && $product_button_text[$i] ){
				$this->pushmeta($pid, '_button_text', esc_attr( $product_button_text[$i] ));						
			}
		}
        // Update product visibility term WC 3.0.0
        if ( version_compare(WOOCOMMERCE_VERSION, '3.0') >= 0 ) {

            $associate_terms = array();

            $term_ids = wp_get_object_terms($pid, 'product_visibility', array('fields' => 'ids'));

            // If Not Update Featured Status checking for current featured status
            if ( ! empty($articleData['ID']) && ( ! $this->is_update_data_allowed('is_update_advanced_options') || ! $this->is_update_data_allowed('is_update_featured_status'))) {
                $featured_term = get_term_by( 'name', 'featured', 'product_visibility' );
                if ( ! empty($featured_term) && ! is_wp_error($featured_term) && in_array($featured_term->term_id, $term_ids) ){
                    $associate_terms[] = $featured_term->term_taxonomy_id;
                }
            }
            else{
                if ($is_featured == "yes"){
                    $featured_term = get_term_by( 'name', 'featured', 'product_visibility' );
                    if ( ! empty($featured_term) && !is_wp_error($featured_term)){
                        $associate_terms[] = $featured_term->term_taxonomy_id;
                    }
                }
            }

            // If Not Update Product Visibility checking for current product visibility
            if ( ! empty($articleData['ID']) && ( ! $this->is_update_data_allowed('is_update_advanced_options') || ! $this->is_update_data_allowed('is_update_catalog_visibility'))) {
                $exclude_search_term = get_term_by( 'name', 'exclude-from-search', 'product_visibility' );
                if (!empty($exclude_search_term) && !is_wp_error($exclude_search_term) && in_array($exclude_search_term->term_id, $term_ids)){
                    $associate_terms[] = $exclude_search_term->term_taxonomy_id;
                }
                $exclude_catalog_term = get_term_by( 'name', 'exclude-from-catalog', 'product_visibility' );
                if (!empty($exclude_catalog_term) && !is_wp_error($exclude_catalog_term) && in_array($exclude_catalog_term->term_id, $term_ids)){
                    $associate_terms[] = $exclude_catalog_term->term_taxonomy_id;
                }
            }
            else{
                if (in_array($product_visibility[$i], array('hidden', 'search'))){
                    $exclude_search_term = get_term_by( 'name', 'exclude-from-catalog', 'product_visibility' );
                    if (!empty($exclude_search_term) && !is_wp_error($exclude_search_term)){
                        $associate_terms[] = $exclude_search_term->term_taxonomy_id;
                    }
                }
                if (in_array($product_visibility[$i], array('hidden', 'catalog'))){
                    $exclude_catalog_term = get_term_by( 'name', 'exclude-from-search', 'product_visibility' );
                    if (!empty($exclude_catalog_term) && !is_wp_error($exclude_catalog_term)){
                        $associate_terms[] = $exclude_catalog_term->term_taxonomy_id;
                    }
                }
            }

            $_stock_status = get_post_meta( $pid, '_stock_status', true);
            if ( $_stock_status == 'outofstock' ){
                $outofstock_term = get_term_by( 'name', 'outofstock', 'product_visibility' );
                if (!empty($outofstock_term) && !is_wp_error($outofstock_term)){
                    $associate_terms[] = $outofstock_term->term_taxonomy_id;
                }
            }

            $this->associate_terms( $pid, $associate_terms, 'product_visibility' );
        }

		// prepare bulk SQL query
		//$this->executeSQL();

		wc_delete_product_transients($pid);

		// VARIATIONS
		if ( ( in_array($product_type, array('variation', 'variable')) or $product_types[$i] == "variable" ) and ! $this->import->options['link_all_variations'] and "xml" != $this->import->options['matching_parent'] ){												

			$set_defaults = false;

			$product_parent_post_id = false;			
				
			//[search parent product]
			$first_is_parent =  ( in_array($this->import->options['matching_parent'], array("auto", "first_is_parent_title")) ) ? "yes" : "no";																														
			
			if ( "manual" != $this->import->options['duplicate_matching'] or $is_new_product )
			{
                $product_parent_post = false;

				// find corresponding article among previously imported
				if ( ! empty($single_product_parent_ID[$i]) ){
					$postRecord = $this->wpdb->get_row($this->wpdb->prepare("SELECT * FROM " . $this->wpdb->prefix . "pmxi_posts WHERE `import_id` = %d AND `product_key` = %s ORDER BY post_id ASC", $this->import->id, $single_product_parent_ID[$i]));
                    if ( ! empty($postRecord) ){
                        $product_parent_post_id = apply_filters( 'pmwi_product_parent_post_id', $postRecord->post_id );
                        $product_parent_post =  get_post($product_parent_post_id);
                    }
				}
			}
			else
			{											

				if (empty($articleData['post_parent'])){

					$product_parent_post_id = $pid;						

					$args = array(
						'post_type' => 'product_variation',
						'meta_query' => array(
							array(
								'key' => '_sku',
								'value' => get_post_meta($pid, '_sku', true),
							)
						)
					);								
					$query = new WP_Query( $args );													

					if ( $query->have_posts() ){ 

						$duplicate_id = $query->post->ID;												

						if ($duplicate_id) {																													

							$pid = $duplicate_id;

							$this->duplicate_post_meta($pid, $product_parent_post_id);			

							$tmp = get_post_meta( $product_parent_post_id, '_stock', true);										
							$this->pushmeta($product_parent_post_id, '_stock_tmp', $tmp);	
							if ( empty($this->import->options['set_parent_stock']) ) 
								$this->pushmeta($product_parent_post_id, '_stock', '');	
								
							$tmp = get_post_meta( $product_parent_post_id, '_regular_price', true);										
							$this->pushmeta($product_parent_post_id, '_regular_price_tmp', $tmp);	
							$this->pushmeta($product_parent_post_id, '_regular_price', '');	

							$tmp = get_post_meta( $product_parent_post_id, '_price', true);										
							$this->pushmeta($product_parent_post_id, '_price_tmp', $tmp);	
							$this->pushmeta($product_parent_post_id, '_price', '');																																																			

						}

					}	

					wp_reset_postdata();
					
				}
				else
				{
					if (!empty($articleData['post_parent']))
					{
						$product_parent_post_id = $articleData['post_parent'];						
					}	
					elseif($articleData['post_type'] == 'product_variation')
					{
						$variation_post = get_post($pid);
						$product_parent_post_id = $variation_post->post_parent;												
					}				
				}
				
				$product_parent_post = $product_parent_post_id ? get_post($product_parent_post_id) : false;	

			}	
			//[\search parent product]		

			if (in_array($product_type, array('variation', 'variable')))
			{

				//$first_is_parent = ( in_array($this->import->options['matching_parent'], array("auto", "first_is_parent_title")) ) ? "yes" : "no";								

				if ( ! empty($product_parent_post_id) and ( (int)$product_parent_post_id != (int)$pid or (int)$product_parent_post_id == (int)$pid and $first_is_parent == "no" and ( ! $this->import->options['make_simple_product'] and ("manual" != $this->import->options['duplicate_matching'] or $is_new_product) ) )) {

					$create_new_variation = false;

					$product_ids = array();

					if ($first_is_parent == "no")
					{
						$is_first_variation_created = get_post_meta($product_parent_post_id, '_is_first_variation_created', true);
						if ( ! $is_first_variation_created )
						{
							$create_new_variation = true;
							update_post_meta($product_parent_post_id, '_is_first_variation_created', 1);
							
							$product_ids[] = ("manual" == $this->import->options['duplicate_matching'] and ! $is_new_product) ? $pid : $product_parent_post_id;
						}

						if ( ! in_array($pid, $product_ids)) $product_ids[] = $pid;
					}
					else
					{
						$product_ids[] = $pid;
					}				

					foreach ($product_ids as $iter => $pid):	

						$create_new_variation = ($create_new_variation && !$iter) ? true : false;

                        $parent_sku = get_post_meta($product_parent_post_id, '_parent_sku', true);

                        if (empty($parent_sku)) {
                            $parent_sku = get_post_meta($product_parent_post_id, '_sku', TRUE);
                        }

						if ( $create_new_variation) {

							$postRecord = new PMXI_Post_Record();
							
							$postRecord->clear();
							
							if ("manual" != $this->import->options['duplicate_matching'] or $is_new_product){
								// find corresponding article among previously imported
								$postRecord->getBy(array(
									'unique_key' => 'Variation ' . $parent_sku,
									'import_id'  => $this->import->id,
								));
								
								$pid = ( ! $postRecord->isEmpty() ) ? $postRecord->post_id : false;
							}						
								
						}

						$is_product_enabled = ($create_new_variation and $this->import->options['make_simple_product']) ? get_post_meta($product_parent_post_id, '_v_variation_enabled', true) : $product_enabled[$i];

						$variable_enabled = ($is_product_enabled == "yes") ? 'yes' : 'no'; 

						$attributes = array(); 

						// Enabled or disabled
						$post_status = ( $variable_enabled == 'yes' ) ? 'publish' : 'private';

						// Generate a useful post title
						$variation_post_title = sprintf( __( 'Variation #%s of %s', 'wpai_woocommerce_addon_plugin' ), absint( $pid ), $product_parent_post->post_title);						
						// Update or Add post							
						$variation = array(
							'post_title' 	=> $variation_post_title,
							'post_content' 	=> '',	
							'post_status'   => $post_status,					
							'post_parent' 	=> $product_parent_post_id,
							'post_type' 	=> 'product_variation'							
						);

						if ( $pid and ! $is_new_product and ! $this->is_update_data_allowed('is_update_status'))
						{						
							$variation['post_status'] = get_post_status($pid);						
						}							

						if ( ! $pid ) {

							if ($this->import->options['create_new_records']){
								
								$pid = wp_insert_post( $variation );

                                // update first variation title with generated ID
                                $updated_variation_title = str_replace('#0', '#'.$pid, $variation_post_title);

                                $this->wpdb->update( $this->wpdb->posts, array(
                                    'post_title' => $updated_variation_title,
                                    'post_name' => sanitize_title($updated_variation_title)
                                ), array( 'ID' => $pid ) );
								//$logger and call_user_func($logger, sprintf(__('<b>CREATED</b>: %s variation from parent product %s.', 'wpai_woocommerce_addon_plugin'), $variation_post_title, $articleData['post_title']));	

								if ($create_new_variation){															
									
									$this->duplicate_post_meta($pid, $product_parent_post_id);

									// associate variation with import
									$postRecord->isEmpty() and $postRecord->set(array(
										'post_id' => $pid,
										'import_id' => $this->import->id,
										'unique_key' => 'Variation ' . $parent_sku,
										'product_key' => ''
									))->insert();

									$postRecord->set(array('iteration' => $this->import->iteration))->update();							

								}												
							}				
						} 
						else 
						{
							if ($create_new_variation) 
							{
								if ("manual" != $this->import->options['duplicate_matching'] or $is_new_product)
								{
									$this->duplicate_post_meta($pid, $product_parent_post_id);									
								}							
								
								if ( ! $postRecord->isEmpty()) $postRecord->set(array('iteration' => $this->import->iteration))->update();

								if ("manual" == $this->import->options['duplicate_matching'] and ! $is_new_product)
								{
									$create_new_variation = false;
								}
							}						

                            wp_update_post( array_merge($variation, array('ID' => $pid)) );

							//$logger and call_user_func($logger, sprintf(__('<b>UPDATED</b>: %s variation for parent product %s.', 'wpai_woocommerce_addon_plugin'), $variation_post_title, $articleData['post_title']));		

						}		

						if ( ! $this->import->options['make_simple_product'] ) $create_new_variation = false;						

						if ($pid){									

							if ( $this->import->options['create_draft'] == "yes" ) $this->wpdb->update( $this->wpdb->posts, array('post_status' => 'publish' ), array('ID' => $pid));												

							if ( $first_is_parent == "no" ){
								
								$_v_product_manage_stock = $create_new_variation ? get_post_meta($product_parent_post_id, '_v_product_manage_stock', true) : $v_product_manage_stock[$i];
								$_v_stock = $create_new_variation ? get_post_meta($product_parent_post_id, '_v_stock', true) : $v_stock[ $i ];
								$_v_stock_status = $create_new_variation ? get_post_meta($product_parent_post_id, '_v_stock_status', true) : $v_stock_status[ $i ];

                                // Stock Data
                                $stock_data_args = array(
                                    'stock_status' => $_v_stock_status,
                                    'manage_stock' => $_v_product_manage_stock,
                                    'allow_backorders' => $product_allow_backorders[$i],
                                    'qty' => $_v_stock
                                );
                                $this->import_stock_data($pid, $product_type, $stock_data_args );

								// Stock handling						
								$this->pushmeta($pid, '_manage_stock', $_v_product_manage_stock);

								if ( empty($this->import->options['set_parent_stock']) ){
									$this->pmwi_buf_prices($product_parent_post_id);	
									$this->is_update_cf('_stock') and delete_post_meta( $product_parent_post_id, '_stock' );
								}

								if ( pmwi_is_update_taxonomy($articleData, $this->import->options, 'product_shipping_class') ){							
									if ($create_new_variation){
										$v_shipping_class = get_post_meta($product_parent_post_id, '_v_shipping_class', true);									
										$this->associate_terms( $pid, array( $v_shipping_class ), 'product_shipping_class' );															
									}			
									else{
										$this->associate_terms( $pid, array( $p_shipping_class ), 'product_shipping_class' );
									}													
								}
							}					
							else
							{
								$stock_status = wc_clean( $product_stock_status[$i] );	

								if ( $stock_status ) {
									update_post_meta( $pid, '_stock_status', $stock_status );
								}

								if (empty($articleData['ID']) or $this->is_update_cf('_tax_class'))
								{
									if ( $product_tax_class[ $i ] !== 'parent' )
										$this->pushmeta($pid, '_tax_class', strtolower($product_tax_class[ $i ]) == 'standard' ? '' : sanitize_text_field( $product_tax_class[ $i ] ));
									else
										delete_post_meta( $pid, '_tax_class' );
								}

								if ( $is_downloadable == 'yes' ) {
									$this->pushmeta($pid, '_download_limit', sanitize_text_field( $product_download_limit[ $i ] ));	
									$this->pushmeta($pid, '_download_expiry', sanitize_text_field( $product_download_expiry[ $i ] ));	
									$this->pushmeta($pid, '_download_type', sanitize_text_field( $product_download_type[ $i ] ));									

									$_file_paths = array();
									
									if ( !empty($product_files[$i]) ) {
										$file_paths = explode( $this->import->options['product_files_delim'] , $product_files[$i] );
										$file_names = explode( $this->import->options['product_files_names_delim'] , $product_files_names[$i] );

										foreach ( $file_paths as $fn => $file_path ) {
											$file_path = sanitize_text_field( $file_path );							
											$_file_paths[ md5( $file_path ) ] = array('name' => ((!empty($file_names[$fn])) ? $file_names[$fn] : basename($file_path)), 'file' => $file_path);
										}
									}

									$this->pushmeta($pid, '_downloadable_files', $_file_paths);									

								} else {
									$this->pushmeta($pid, '_download_limit', '');	
									$this->pushmeta($pid, '_download_expiry', '');	
									$this->pushmeta($pid, '_download_type', '');	
									$this->pushmeta($pid, '_downloadable_files', '');									
								}
							}											

							if ($this->import->options['update_all_data'] == 'yes' or $this->import->options['update_all_data'] == 'no' and $this->import->options['is_update_product_type']){
								$this->associate_terms( $pid, NULL, 'product_type' );	
							}

							// Remove old taxonomies attributes so data is kept up to date
							if ( $pid and ($this->import->options['update_all_data'] == "yes" or ( $this->import->options['update_all_data'] == "no" and $this->import->options['is_update_attributes']))) {
								// Update all Attributes
								if ( $this->import->options['update_all_data'] == "yes" or $this->import->options['update_attributes_logic'] == 'full_update' ) 
									$this->wpdb->query( $this->wpdb->prepare( "DELETE FROM {$this->wpdb->postmeta} WHERE meta_key LIKE 'attribute_%%' AND post_id = %d;", $pid ) );					

								wp_cache_delete( $pid, 'post_meta');
							}										

							// Update taxonomies
							if ( $this->import->options['update_all_data'] == "yes" or ( $this->import->options['update_all_data'] == "no" and $this->import->options['is_update_attributes']) or $is_new_product){
								
								if ( $create_new_variation )
								{
									$parent_attributes = get_post_meta($product_parent_post_id, '_first_variation_attributes', true);								

									if ( ! empty($parent_attributes))
									{
										foreach ($parent_attributes as $key => $attr_data) 
										{
											
											$attr_name = $key;

											if ( intval($attr_data['is_taxonomy']) and ( strpos($attr_name, "pa_") === false or strpos($attr_name, "pa_") !== 0 ) ) $attr_name = "pa_" . $attr_name;

											// Update only these Attributes, leave the rest alone
											if ( ! $is_new_product and $this->import->options['update_all_data'] == "no" and $this->import->options['is_update_attributes'] and $this->import->options['update_attributes_logic'] == 'only'){
												if ( ! empty($this->import->options['attributes_list']) and is_array($this->import->options['attributes_list'])){
													if ( ! in_array( $attr_name , array_filter($this->import->options['attributes_list'], 'trim'))) continue;
												}
												else break;								
											}	

											// Leave these attributes alone, update all other Attributes
											if ( ! $is_new_product and $this->import->options['update_all_data'] == "no" and $this->import->options['is_update_attributes'] and $this->import->options['update_attributes_logic'] == 'all_except'){
												if ( ! empty($this->import->options['attributes_list']) and is_array($this->import->options['attributes_list'])) {
													if ( in_array( $attr_name , array_filter($this->import->options['attributes_list'], 'trim'))) continue;									
												}
											}											

											$is_variation 	= intval( $attr_data['is_variation']);			

											if (intval($attr_data['is_taxonomy']))
											{
                                                $attr_name = wc_attribute_taxonomy_name($this->create_taxonomy(preg_replace("%^pa_%", "", $attr_name), $logger));
												$cname = wc_attribute_taxonomy_name( preg_replace("%^pa_%", "", $attr_name) );
												$this->associate_terms( $pid, NULL, $cname );
											}

											if ( $is_variation)
											{
												// Don't use woocommerce_clean as it destroys sanitized characters																								
												$values = substr((intval($attr_data['is_taxonomy'])) ? $attr_data['value'] : $attr_data['value'], 0, $max_attribute_length);
												
												if (intval($attr_data['is_taxonomy'])){
																									
													$cname = wc_attribute_taxonomy_name( preg_replace("%^pa_%", "", $attr_name) );

													$term = get_term_by('name', $values, $cname, ARRAY_A);

													// For compatibility with WPML plugin
						 							$term = apply_filters('wp_all_import_term_exists', $term, $cname, $values, null);

											 		if ( empty($term) and !is_wp_error($term) ){	
														$term = is_exists_term($values, $cname);

														if ( empty($term) and !is_wp_error($term) ){																																
															$term = is_exists_term(htmlspecialchars($values), $cname);
														}
													}

													if ( ! empty($term) and ! is_wp_error($term) ){	
														$term = get_term_by('id', $term['term_id'], $cname);																							
														if ( ! empty($term) and ! is_wp_error($term) )
															update_post_meta($pid, 'attribute_' . sanitize_title( $attr_name ), $term->slug);																					
													}
													else{																																	
														update_post_meta($pid, 'attribute_' . sanitize_title( $attr_name ), '');
													}

												} 
												else 
												{
													$attr_value = trim($attr_data['value']);
													if ( $attr_value != "" ){
														update_post_meta($pid, 'attribute_' . sanitize_title( $attr_name ), $attr_value);
													}													
													else{
														delete_post_meta($pid, 'attribute_' . sanitize_title( $attr_name ));
													}
												}
											}
											else
											{										
												delete_post_meta($pid, 'attribute_' . sanitize_title( $attr_name ));
											}																			
										}
									}
								}
								else
								{
									// $attr_names = array();

									foreach ($serialized_attributes as $anum => $attr_data) {

										$attr_name = $attr_data['names'][$i];

										if (empty($attr_name)) continue;
												
										// $attr_names[] = $attr_name;

										// Update only these Attributes, leave the rest alone
										if ( ! $is_new_product and $this->import->options['update_all_data'] == "no" and $this->import->options['is_update_attributes'] and $this->import->options['update_attributes_logic'] == 'only'){
											if ( ! empty($this->import->options['attributes_list']) and is_array($this->import->options['attributes_list'])){
												if ( ! in_array( ( (intval($attr_data['in_taxonomy'][$i])) ? wc_attribute_taxonomy_name( $attr_name ) : $attr_name ) , array_filter($this->import->options['attributes_list'], 'trim'))) continue;
											}
											else break;								
										}	

										// Leave these attributes alone, update all other Attributes
										if ( ! $is_new_product and $this->import->options['update_all_data'] == "no" and $this->import->options['is_update_attributes'] and $this->import->options['update_attributes_logic'] == 'all_except'){
											if ( ! empty($this->import->options['attributes_list']) and is_array($this->import->options['attributes_list'])) {
												if ( in_array( ( ($is_taxonomy) ? wc_attribute_taxonomy_name( $attr_name ) : $attr_name ) , array_filter($this->import->options['attributes_list'], 'trim'))) continue;									
											}
										}						

										if ( intval($attr_data['in_taxonomy'][$i]) and ( strpos($attr_name, "pa_") === false or strpos($attr_name, "pa_") !== 0 ) ) $attr_name = "pa_" . $attr_name;	

										$is_variation 	= intval( $attr_data['in_variation'][$i]);													

										if (intval($attr_data['in_taxonomy'][$i]))
										{
                                            $attr_name = wc_attribute_taxonomy_name($this->create_taxonomy(preg_replace("%^pa_%", "", $attr_name), $logger));
											$cname = wc_attribute_taxonomy_name( preg_replace("%^pa_%", "", $attr_name) );
											$this->associate_terms( $pid, NULL, $cname );	
										}

										if ($is_variation){
											
											// Don't use woocommerce_clean as it destroys sanitized characters																								
											$values = substr((intval($attr_data['in_taxonomy'][$i])) ? $attr_data['value'][$i] : $attr_data['value'][$i], 0, $max_attribute_length);

											if (intval($attr_data['in_taxonomy'][$i])){

                                                $cname = wc_attribute_taxonomy_name( preg_replace("%^pa_%", "", $attr_name) );
												$term = get_term_by('name', $values, $cname, ARRAY_A);

												// For compatibility with WPML plugin
						 						$term = apply_filters('wp_all_import_term_exists', $term, $cname, $values, null);

										 		if ( empty($term) and !is_wp_error($term) ){	
													$term = is_exists_term($values, $cname);

													if ( empty($term) and !is_wp_error($term) ){																																
														$term = is_exists_term(htmlspecialchars($values), $cname);
													}
												}

												if ( ! empty($term) and ! is_wp_error($term) ){	
													$term = get_term_by('id', $term['term_id'], $cname);									
													if ( ! empty($term) and ! is_wp_error($term) )
														update_post_meta($pid, 'attribute_' . sanitize_title( $attr_name ), $term->slug);																					
												}
												else{
													update_post_meta($pid, 'attribute_' . sanitize_title( $attr_name ), '');
												}
											} 
											else 
											{
												$attr_value = trim($values);
												if ( $attr_value != "" ){
													update_post_meta($pid, 'attribute_' . sanitize_title( $attr_name ), $attr_value);
												}													
												else{
													delete_post_meta($pid, 'attribute_' . sanitize_title( $attr_name ));
												}																																					
											}
										}						
										else{
											delete_post_meta($pid, 'attribute_' . sanitize_title( $attr_name ));
										}
									}
								}													
							}
						}									

						$this->pmwi_buf_prices($product_parent_post_id);						
													
						if ($product_parent_post_id) wc_delete_product_transients($product_parent_post_id);		

						if ($create_new_variation) do_action( 'pmxi_saved_post', $pid, null);

						do_action( 'pmxi_update_product_variation', $pid );				

						$create_new_variation = false;

					endforeach;
									
				}	
				else
				{
					if ($first_is_parent == "no")
					{
						update_post_meta($product_parent_post_id, '_v_product_manage_stock', $v_product_manage_stock[$i]);
						update_post_meta($product_parent_post_id, '_v_stock', $v_stock[$i]);
						update_post_meta($product_parent_post_id, '_v_stock_status', $v_stock_status[$i]);	
						update_post_meta($product_parent_post_id, '_v_variation_enabled', $product_enabled[$i]);
						
						if ( !empty($serialized_attributes) ) 
						{
							$attributes = array();
							$attribute_position = 0;
							foreach ($serialized_attributes as $anum => $attr_data) 
							{	
								$attr_name = $attr_data['names'][$i];
								$is_visible 	= intval( $attr_data['is_visible'][$i] );
								$is_variation 	= intval( $attr_data['in_variation'][$i] );
								$is_taxonomy 	= intval( $attr_data['in_taxonomy'][$i] );

								// Custom attribute - Add attribute to array and set the values
							 	$attributes[ sanitize_title( $attr_name ) ] = array(
							 		'name' 			=> sanitize_text_field( $attr_name ),
                                    'value' 		=> isset($attr_data['value'][$i]) ? trim($attr_data['value'][$i]) : '',
							 		'position' 		=> $attribute_position,
							 		'is_visible' 	=> $is_visible,
							 		'is_variation' 	=> $is_variation,
							 		'is_taxonomy' 	=> $is_taxonomy
							 	);
							 	$attribute_position++;
							}
							update_post_meta($product_parent_post_id, '_first_variation_attributes', $attributes);
						}

						$this->pmwi_buf_prices($product_parent_post_id);

						if ( pmwi_is_update_taxonomy($articleData, $this->import->options, 'product_shipping_class') ){
							update_post_meta($product_parent_post_id, '_v_shipping_class', $p_shipping_class);
						}
					}
				}
			}									
								
			$previousID = get_option('wp_all_import_' . $this->import->id . '_parent_product');							

			// [execute only for parent products]								
			if ( ! isset($product_types[$i + 1]) or isset($product_types[$i + 1]) and ! in_array($product_types[$i + 1], array('variation', 'variable')) or ( ! empty($previousID) and ( empty($product_parent_post_id) or $product_parent_post_id != $previousID or "yes" == $this->import->options['is_keep_former_posts'] ) ) ){

				$parent_product_ids = empty($previousID) ? array() : array($previousID);
				
				if ( ! isset($product_types[$i + 1]) and ! empty($product_parent_post_id) and ! in_array($product_parent_post_id, $parent_product_ids)) 
				{
					$parent_product_ids[] = $product_parent_post_id;
				}

				if ( ! isset($product_types[$i + 1]) and ! empty($pid) and ! in_array($pid, $parent_product_ids)) 
				{
					$parent_product_ids[] = $pid;
				}				

				foreach ($parent_product_ids as $post_parent) {

				    $children = get_posts( array(
						'post_parent' 	=> $post_parent,
						'posts_per_page'=> -1,
						'post_type' 	=> 'product_variation',
						'fields' 		=> 'ids',
						'orderby'		=> 'ID',
						'order'			=> 'ASC',
						'post_status'	=> array('draft', 'publish', 'trash', 'pending', 'future', 'private')
					) );			

					if ( count($children) ){

                        $parent_sku = get_post_meta($post_parent, '_parent_sku', true);

                        if (!empty($parent_sku)){
                            $first_variation_sku = get_post_meta($post_parent, '_sku', true);
                            $this->pushmeta($post_parent, '_sku', $parent_sku);
                            $this->pushmeta($post_parent, '_first_variation_sku', $first_variation_sku);
                        }

						$product_type_term = is_exists_term('variable', 'product_type', 0);	
						if ( ! empty($product_type_term) and ! is_wp_error($product_type_term) ){	
							$this->associate_terms( $post_parent, array( (int) $product_type_term['term_taxonomy_id'] ), 'product_type' );	
						}

						$lowest_price = $lowest_regular_price = $lowest_sale_price = $highest_price = $highest_regular_price = $highest_sale_price = '';
						$lowest_price_id = $lowest_regular_price_id = $lowest_sale_price_id = $highest_price_id = $highest_regular_price_id = $highest_sale_price_id = '';						

						$total_instock = 0;

						if ( $children ) {
						    $children_prices = array();
							foreach ( $children as $n => $child ) {
								
								$_variation_stock = get_post_meta($child, '_stock_status', true);

								$total_instock += ($_variation_stock == 'instock') ? 1 : 0;

								$child_price 			= get_post_meta( $child, '_price', true );
                                if ( ! in_array($child_price, $children_prices) ) $children_prices[] = $child_price;
								$child_regular_price 	= get_post_meta( $child, '_regular_price', true );
								$child_sale_price 		= get_post_meta( $child, '_sale_price', true );

								// Regular prices
								if ( empty( $lowest_regular_price ) ||  (float) $child_regular_price < (float) $lowest_regular_price ){
									$lowest_regular_price = $child_regular_price;
									$lowest_regular_price_id = $child;
								}

								if ( empty( $highest_regular_price ) || (float) $child_regular_price > (float) $highest_regular_price ){
									$highest_regular_price = $child_regular_price;
									$highest_regular_price_id = $child;
								}
								
								// Sale prices
								if ( $child_price == $child_sale_price ) {
									if ( $child_sale_price !== '' && ( ! is_numeric( $lowest_sale_price ) || (float) $child_sale_price < (float) $lowest_sale_price ) ){
										$lowest_sale_price = $child_sale_price;
										$lowest_sale_price_id = $child;
									}

									if ( $child_sale_price !== '' && ( ! is_numeric( $highest_sale_price ) || (float) $child_sale_price > (float) $highest_sale_price ) ){
										$highest_sale_price = $child_sale_price;
										$highest_sale_price_id = $child;
									}
								}
							}

					    	$lowest_price 	= $lowest_sale_price === '' || (float) $lowest_regular_price < (float) $lowest_sale_price ? $lowest_regular_price : $lowest_sale_price;
							$highest_price 	= $highest_sale_price === '' || (float) $highest_regular_price > (float) $highest_sale_price ? $highest_regular_price : $highest_sale_price;

							$lowest_price_id 	= $lowest_sale_price === '' || (float) $lowest_regular_price < (float) $lowest_sale_price ? $lowest_regular_price_id : $lowest_sale_price_id;
							$highest_price_id 	= $highest_sale_price === '' || (float) $highest_regular_price > (float) $highest_sale_price ? $highest_regular_price_id : $highest_sale_price_id;

						}

						$new_parent_stock_status = ($total_instock > 0) ? 'instock' : 'outofstock';

                        update_post_meta($post_parent, '_stock_status', $new_parent_stock_status);

                        $this->update_stock_status($post_parent, $new_parent_stock_status);

						$this->pushmeta($post_parent, '_price', $lowest_price);		

                        if ( empty($articleData['ID']) or $this->is_update_cf('_price') ){
                            foreach ($children_prices as $children_price){
                                add_post_meta($post_parent, '_price', $children_price);
                            }
                        }

						update_post_meta($post_parent, '_min_variation_price', $lowest_price);		
						update_post_meta($post_parent, '_max_variation_price', $highest_price);		
						update_post_meta($post_parent, '_min_variation_regular_price', $lowest_regular_price);		
						update_post_meta($post_parent, '_max_variation_regular_price', $highest_regular_price);		
						update_post_meta($post_parent, '_min_variation_sale_price', $lowest_sale_price);		
						update_post_meta($post_parent, '_max_variation_sale_price', $highest_sale_price);									

						update_post_meta($post_parent, '_min_price_variation_id', $lowest_price_id);
						update_post_meta($post_parent, '_max_price_variation_id', $highest_price_id);
						update_post_meta($post_parent, '_min_regular_price_variation_id', $lowest_regular_price_id);
						update_post_meta($post_parent, '_max_regular_price_variation_id', $highest_regular_price_id);
						update_post_meta($post_parent, '_min_sale_price_variation_id', $lowest_sale_price_id);
						update_post_meta($post_parent, '_max_sale_price_variation_id', $highest_sale_price_id);

						// Update default attribute options setting
						if ( $this->import->options['update_all_data'] == "yes" or ( $this->import->options['update_all_data'] == "no" and $this->import->options['is_update_attributes'] ) or $is_new_product ){
							
							$default_attributes = array();
							$parent_attributes  = array();
							$unique_attributes  = array();
							$attribute_position = 0;
							$is_update_attributes = true;

                            $post_parent_attributes = (array) maybe_unserialize( get_post_meta( $post_parent, '_product_attributes', true ) );

                            foreach ($post_parent_attributes as $attr)
                            {
                                if ( empty($attr['name']) ) continue;
                                if ( ! in_array($attr['name'], $unique_attributes)) {
                                    $attributes[] = $attr;
                                    $unique_attributes[] = $attr['name'];
                                }
                            }

                            $children[] = $post_parent;

							foreach ( $children as $child ) {

								$child_attributes = (array) maybe_unserialize( get_post_meta( $child, '_product_attributes', true ) );

								foreach ($child_attributes as $attr) 
								{
									if ( empty($attr['name']) ) continue;
									if ( ! in_array($attr['name'], $unique_attributes)) {
										$attributes[] = $attr;
										$unique_attributes[] = $attr['name'];
									}
								}									
							}				

							foreach ( $attributes as $attribute ) {															

								$default_attributes[ sanitize_title($attribute['name']) ] = "";

								$values = array();

								foreach ( $children as $child_number => $child ) {
									
									$value = array();

									if ( $attribute['is_variation'] && $child != $post_parent)
									{	
										$value = array_map( 'stripslashes', array_map( 'strip_tags',  explode("|", trim( get_post_meta($child, 'attribute_'.sanitize_title($attribute['name']), true)))));
									}
									else
									{
										$child_attributes = (array) maybe_unserialize( get_post_meta( $child, '_product_attributes', true ) );
										if ( ! empty($child_attributes[sanitize_title($attribute['name'])]))
										{
											$value = array_map( 'stripslashes', array_map( 'strip_tags',  explode("|", trim( $child_attributes[sanitize_title($attribute['name'])]['value'] ))));
										}																			
									}
									
									if ( is_array($value) and isset($value[0]) ){
										//$this->pushmeta($child, 'attribute_' . sanitize_title( $attribute['name'] ), $value[0]);
                                        $value = array_map('trim', $value);
										foreach ($value as $val) {
											if ( $attribute['is_taxonomy'] && class_exists('SitePress')){
												$term = get_term_by('slug', $val, $attribute['name'], ARRAY_A);
												// For compatibility with WPML plugin
						 						$term = apply_filters('wp_all_import_term_exists', $term, $attribute['name'], $val, null);
												if ( ! empty($term) and ! is_wp_error($term) )
												{
													$val = $term['name'];
												}
											}											
										 	if ( trim($val) != "" and ! in_array($val, $values, true) )  $values[] = trim($val);
										} 
									}

									if ( $attribute['is_variation'] ) {							

										if ( isset($values[0]) and empty($default_attributes[ $attribute['name'] ])){
											switch ($this->import->options['default_attributes_type']) {
												case 'instock':
                                                    $is_instock = get_post_meta($child, '_stock_status', true);
                                                    if ($is_instock == 'instock'){
                                                        $default_attribute_value = (is_array($value)) ? $value[0] : $value;
                                                        $default_attributes[ sanitize_title($attribute['name']) ] = $attribute['is_taxonomy'] ? sanitize_title($default_attribute_value) : $default_attribute_value;
                                                    }
													break;
												case 'first':													
													if ($first_is_parent != "no" or $first_is_parent == "no" and ! $this->import->options['make_simple_product'] )
													{
                                                        $default_attribute_value = (is_array($values)) ? $values[0] : $values;
														$default_attributes[ sanitize_title($attribute['name']) ] = $attribute['is_taxonomy'] ? sanitize_title($default_attribute_value) : $default_attribute_value;
													}													
													elseif ($first_is_parent == "no" and $child_number)
													{																					
														if (is_array($values) and isset($values[1]))
														{
															$default_attributes[ sanitize_title($attribute['name']) ] = $attribute['is_taxonomy'] ? sanitize_title($values[1]) : $values[1];
														}
														else
														{
                                                            $default_attribute_value = (is_array($values)) ? $values[0] : $values;
															$default_attributes[ sanitize_title($attribute['name']) ] = $attribute['is_taxonomy'] ? sanitize_title($default_attribute_value) : $default_attribute_value;
														}															
													}	
													
													break;
												
												default:
													# code...
													break;
											}
																							
										}									
									}									
								}								

								// Update only these Attributes, leave the rest alone
								if ( ! $is_new_product and $this->import->options['update_all_data'] == "no" and $this->import->options['is_update_attributes'] and $this->import->options['update_attributes_logic'] == 'only'){
									if ( ! empty($this->import->options['attributes_list']) and is_array($this->import->options['attributes_list'])){
										if ( ! in_array( $attribute['name'] , array_filter($this->import->options['attributes_list'], 'trim'))){ 
											$attribute_position++;		
											continue;
										}
									}
									else {
										$is_update_attributes = false;
										break;
									}
								}

								// Leave these attributes alone, update all other Attributes
								if ( ! $is_new_product and $this->import->options['update_all_data'] == "no" and $this->import->options['is_update_attributes'] and $this->import->options['update_attributes_logic'] == 'all_except'){
									if ( ! empty($this->import->options['attributes_list']) and is_array($this->import->options['attributes_list'])) {
										if ( in_array( $attribute['name'] , array_filter($this->import->options['attributes_list'], 'trim'))){ 
											$attribute_position++;
											continue;
										}
									}
								}

								// $values = array_filter($values);

								if ( $attribute['is_taxonomy'] ){
									
									if ( ! empty($values) ) {				 												

									 	// Remove empty items in the array
									 	$values = array_filter( $values, array($this, "filtering") );						 	

								 		$attr_values = array();

								 		foreach ($values as $key => $value) {								 			

								 			$term = get_term_by('name', $value, $attribute['name'], ARRAY_A);
								 			// For compatibility with WPML plugin
						 					$term = apply_filters('wp_all_import_term_exists', $term, $attribute['name'], $value, null);

						 					if ( empty($term) and !is_wp_error($term) ){	
								 			
									 			$term = is_exists_term($value, $attribute['name']);	

									 			if ( empty($term) and !is_wp_error($term) ){																																
													$term = is_exists_term(htmlspecialchars($value), $attribute['name']);	
													if ( empty($term) and !is_wp_error($term) and $attribute['is_create_taxonomy_terms']){													
														$term = wp_insert_term(
															$value, // the term 
														  	$attribute['name'] // the taxonomy										  	
														);													
													}
												}
											}
											
											if ( ! is_wp_error($term) )												
												$attr_values[] = (int) $term['term_taxonomy_id']; 
								 			
								 		}

								 		$values = $attr_values;
								 		$values = array_map( 'intval', $values );
										$values = array_unique( $values );

								 	} else {
								 		$values = array();
								 	}
								 	
							 		// Update post terms
							 		if ( $values and taxonomy_exists( $attribute['name'] ) )
							 			$this->associate_terms( $post_parent, $values, $attribute['name'] );									 			

							 		//do_action('wpai_parent_set_object_terms', $post_parent, $attribute['name']);

							 		if ( $values ) {
							 			
								 		// Add attribute to array, but don't set values
								 		$parent_attributes[ sanitize_title( $attribute['name'] ) ] = array(
									 		'name' 			=> $attribute['name'],
									 		'value' 		=> $attribute['value'],
									 		'position' 		=> $attribute_position,
									 		'is_visible' 	=> $attribute['is_visible'],
									 		'is_variation' 	=> $attribute['is_variation'],
									 		'is_taxonomy' 	=> 1,
									 		'is_create_taxonomy_terms' => $attribute['is_create_taxonomy_terms'],
									 	);
								 	
								 	}						 	

								}
								else
								{									
									if (!empty($values)){
										$parent_attributes[ sanitize_title( $attribute['name'] ) ] = array(
									 		'name' 			=> sanitize_text_field( $attribute['name'] ),
									 		'value' 		=> implode('|', $values),
									 		'position' 		=> $attribute_position,
									 		'is_visible' 	=> $attribute['is_visible'],
									 		'is_variation' 	=> $attribute['is_variation'],
									 		'is_taxonomy' 	=> 0
									 	);
									}									
								}

							 	$attribute_position++;		
							}				
							
							if ($this->import->options['is_default_attributes'] and (empty($articleData['ID']) or $this->import->options['update_all_data'] == "yes" or $this->import->options['update_all_data'] == "no" and $this->import->options['is_update_attributes'])) $this->pushmeta($post_parent, '_default_attributes', $default_attributes);							

							if ($is_new_product or $this->import->options['update_all_data'] == "yes" or $this->import->options['update_all_data'] == "no" and $this->import->options['is_update_attributes']){ 
								
								$current_product_attributes = get_post_meta($post_parent, '_product_attributes', true);						

								update_post_meta($post_parent, '_product_attributes', (( ! empty($current_product_attributes)) ? array_merge($current_product_attributes, $parent_attributes) : $parent_attributes));
							}
						}

                        foreach ( $children as $k => $child ) {
                            if ( $post_parent == $child ) unset($children[$k]);
                        }

						if (count($children) == 1 and $this->import->options['make_simple_product'] and $first_is_parent == "no"){// and "manual" != $this->import->options['duplicate_matching']
							$this->make_simple_product($post_parent);																			
						}

						if ( $this->import->options['make_simple_product']) { // and "manual" != $this->import->options['duplicate_matching']
							$product_attributes = get_post_meta($post_parent, '_product_attributes', true);		
							if ( empty($product_attributes) ){
								$this->make_simple_product($post_parent);																		
							}
						}

                        wc_delete_product_transients($post_parent);

                        if ( version_compare(WOOCOMMERCE_VERSION, '3.0') >= 0 ){

                            if ( $children ) {
                                foreach ($children as $n => $child) {
                                    $childProduct = WC()->product_factory->get_product($child);
                                    $new_title = $this->generate_product_title( $childProduct );
                                    $this->wpdb->update( $this->wpdb->posts, array( 'post_title' => $new_title ), array( 'ID' => $child ) );
//                                    wc_delete_product_transients($child);
                                }
                            }

                        }

                        do_action('wp_all_import_variable_product_imported', $post_parent);

					} 
					elseif ( $this->import->options['make_simple_product']) {// and "manual" != $this->import->options['duplicate_matching']
                        $table = $this->wpdb->posts;
                        $p = $this->wpdb->get_row($this->wpdb->prepare("SELECT * FROM $table WHERE ID = %d;", $post_parent));
                        if ($p->post_type != 'product_variation'){
                            $parent_sku = get_post_meta($post_parent, '_parent_sku', true);
                            if (!empty($parent_sku)){
                                $first_variation_sku = get_post_meta($post_parent, '_sku', true);
                                $this->pushmeta($post_parent, '_sku', $parent_sku);
                                $this->pushmeta($post_parent, '_first_variation_sku', $first_variation_sku);
                            }
                            $this->make_simple_product($post_parent);
                            wc_delete_product_transients($post_parent);
                            do_action('wp_all_import_variable_product_imported', $post_parent);
                        }
                    }
				}
			}
			// \[execute only for parent products]

			update_option('wp_all_import_' . $this->import->id . '_parent_product', $product_parent_post_id ? $product_parent_post_id : $pid);

		} elseif ( in_array( $product_type, array( 'variable' ) ) ){

			// Link All Variations
			if ( "variable" == $product_type and $this->import->options['link_all_variations'] and ($this->import->options['update_all_data'] == "yes" or ($this->import->options['update_all_data'] == "no" and $this->import->options['is_update_attributes']) or $is_new_product)){

				$added_variations = $this->pmwi_link_all_variations($pid, $this->import->options, $this->import->id, $this->import->iteration);

				$logger and call_user_func($logger, sprintf(__('<b>CREATED</b>: %s variations for parent product %s.', 'wpai_woocommerce_addon_plugin'), $added_variations, $articleData['post_title']));	

			}

			// Variable products have no prices		
			$this->pmwi_buf_prices($pid);

		}

		if ( in_array( $product_type, array( 'grouped' ) ) ){
			$this->pushmeta($pid, '_regular_price', '');
			$this->pushmeta($pid, '_sale_price', '');
			$this->pushmeta($pid, '_sale_price_dates_from', '');
			$this->pushmeta($pid, '_sale_price_dates_to', '');
			$this->pushmeta($pid, '_price', '');	
		}

		//$this->executeSQL();			

		// Find children elements by XPath and create variations
		if ( "variable" == $product_type and "xml" == $this->import->options['matching_parent'] and "" != $this->import->options['variations_xpath'] and ! $this->import->options['link_all_variations'] and ( "" != $this->import->options['variable_sku'] or empty($this->import->options['disable_auto_sku_generation']))) {
			
			$logger and call_user_func($logger, __('- Importing Variations', 'wpai_woocommerce_addon_plugin'));

			$variation_xpath = $cxpath . '[' . ( $i + 1 ) . ']/'.  ltrim(trim(str_replace("[*]", "", $this->import->options['variations_xpath']),'{}'), '/');
			
			$records = array();

            $variations = XmlImportParser::factory($xml, $variation_xpath, '/', $file)->parse($records); $tmp_files[] = $file;

			$count_variations = count($variations);			

			if ( $count_variations > 0 ){

                // Variation SKUs
                if ($this->import->options['variable_sku'] != ""){
                    $variation_sku = XmlImportParser::factory($xml, $variation_xpath, $this->import->options['variable_sku'], $file)->parse($records); $tmp_files[] = $file;
                }
                else{
                    $count_variations and $variation_sku = array_fill(0, $count_variations, '');
                }

				// Composing product is Manage stock									
				if ($this->import->options['is_variable_product_manage_stock'] == 'xpath' and "" != $this->import->options['single_variable_product_manage_stock']){
					if ($this->import->options['single_variable_product_manage_stock_use_parent']){
						$parent_variable_product_manage_stock = XmlImportParser::factory($xml, $cxpath, $this->import->options['single_variable_product_manage_stock'], $file)->parse($records); $tmp_files[] = $file;
                        $count_variations and $variation_product_manage_stock = array_fill(0, $count_variations, $parent_variable_product_manage_stock[$i]);						
					}
					else {
						$variation_product_manage_stock = XmlImportParser::factory($xml, $variation_xpath, $this->import->options['single_variable_product_manage_stock'], $file)->parse($records); $tmp_files[] = $file;						
					}
				}
				else{
                    $count_variations and $variation_product_manage_stock = array_fill(0, $count_variations, $this->import->options['is_variable_product_manage_stock']);
				}

				// Variation Description
				if ($this->import->options['variable_description'] != ""){
					if ($this->import->options['variable_description_use_parent']){
						$parent_variation_description = XmlImportParser::factory($xml, $cxpath, $this->import->options['variable_description'], $file)->parse($records); $tmp_files[] = $file;
                        $count_variations and $variation_description = array_fill(0, $count_variations, $parent_variation_description[$i]);						
					}
					else {
						$variation_description = XmlImportParser::factory($xml, $variation_xpath, $this->import->options['variable_description'], $file)->parse($records); $tmp_files[] = $file;
					}
				}
				else{
					$count_variations and $variation_description = array_fill(0, $count_variations, '');
				}

				// Stock Qty
				if ($this->import->options['variable_stock'] != ""){
					if ($this->import->options['variable_stock_use_parent']){
						$parent_variation_stock = XmlImportParser::factory($xml, $cxpath, $this->import->options['variable_stock'], $file)->parse($records); $tmp_files[] = $file;
						$count_variations and $variation_stock = array_fill(0, $count_variations, $parent_variation_stock[$i]);						
					}
					else {
						$variation_stock = XmlImportParser::factory($xml, $variation_xpath, $this->import->options['variable_stock'], $file)->parse($records); $tmp_files[] = $file;
					}
				}
				else{
					$count_variations and $variation_stock = array_fill(0, $count_variations, '');
				}				

				// Stock Status
				if ($this->import->options['variable_stock_status'] == 'xpath' and "" != $this->import->options['single_variable_stock_status']){
					$variable_stock_status = XmlImportParser::factory($xml, $variation_xpath, $this->import->options['single_variable_stock_status'], $file)->parse($records); $tmp_files[] = $file;						
				}
				elseif($this->import->options['variable_stock_status'] == 'auto'){
					$count_variations and $variable_stock_status = array_fill(0, $count_variations, $this->import->options['variable_stock_status']);
					foreach ($variation_stock as $key => $value) {
						$variable_stock_status[$key] = ( (int) $value <= 0) ? 'outofstock' : 'instock';
					}
				}
				else{
					$count_variations and $variable_stock_status = array_fill(0, $count_variations, $this->import->options['variable_stock_status']);
				}

				// Composing product Allow Backorders?
				if ($this->import->options['variable_allow_backorders'] == 'xpath' and "" != $this->import->options['single_variable_allow_backorders']){
					$variable_allow_backorders =  XmlImportParser::factory($xml, $variation_xpath, $this->import->options['single_variable_allow_backorders'], $file)->parse($records); $tmp_files[] = $file;
				}
				else{					
					$count_variations and $variable_allow_backorders = array_fill(0, $count_variations, $this->import->options['variable_allow_backorders']);
				}

				// Image			
				$variation_image = array();				
				if ($this->import->options['variable_image']) {
					
					if ($this->import->options['variable_image_use_parent']){
						$parent_image = XmlImportParser::factory($xml, $cxpath, $this->import->options['variable_image'], $file)->parse($records); $tmp_files[] = $file;						
						$count_variations and $variation_image = array_fill(0, $count_variations, $parent_image[$i]);						
					}
					else {
						$variation_image = XmlImportParser::factory($xml, $variation_xpath, $this->import->options['variable_image'], $file)->parse($records); $tmp_files[] = $file;	
					}					
					
				} else {
					$count_variations and $variation_image = array_fill(0, $count_variations, '');
				}

				// Regular Price
				if (!empty($this->import->options['variable_regular_price'])){
					if ($this->import->options['variable_regular_price_use_parent']){						
						$parent_regular_price = array_map(array($this, 'adjust_price'), array_map(array($this, 'prepare_price'), XmlImportParser::factory($xml, $cxpath, $this->import->options['variable_regular_price'], $file)->parse($records)),  array_fill(0, $count_variations, "variable_regular_price")); $tmp_files[] = $file;
						$count_variations and $variation_regular_price = array_fill(0, $count_variations, $parent_regular_price[$i]);						
					}
					else {
						$variation_regular_price = array_map(array($this, 'prepare_price'), XmlImportParser::factory($xml, $variation_xpath, $this->import->options['variable_regular_price'], $file)->parse($records)); $tmp_files[] = $file;
					}
				}
				else{
					$count_variations and $variation_regular_price = array_fill(0, $count_variations, '');
				}

				// Sale Price
				if (!empty($this->import->options['variable_sale_price'])){
					if ($this->import->options['variable_sale_price_use_parent']){
						$parent_sale_price = array_map(array($this, 'adjust_price'), array_map(array($this, 'prepare_price'), XmlImportParser::factory($xml, $cxpath, $this->import->options['variable_sale_price'], $file)->parse($records)),  array_fill(0, $count_variations, "variable_sale_price")); $tmp_files[] = $file;
						$count_variations and $variation_sale_price = array_fill(0, $count_variations, $parent_sale_price[$i]);						
					}
					else {
						$variation_sale_price = array_map(array($this, 'prepare_price'), XmlImportParser::factory($xml, $variation_xpath, $this->import->options['variable_sale_price'], $file)->parse($records)); $tmp_files[] = $file;
					}
				}
				else{
					$count_variations and $variation_sale_price = array_fill(0, $count_variations, '');
				}	

				// Who Sale Price
				if (!empty($this->import->options['variable_whosale_price'])){
					if ($this->import->options['variable_whosale_price_use_parent']){
						$parent_whosale_price = array_map(array($this, 'prepare_price'), XmlImportParser::factory($xml, $cxpath, $this->import->options['variable_whosale_price'], $file)->parse($records)); $tmp_files[] = $file;
						$count_variations and $variation_whosale_price = array_fill(0, $count_variations, $parent_whosale_price[$i]);						
					}
					else {
						$variation_whosale_price = array_map(array($this, 'prepare_price'), XmlImportParser::factory($xml, $variation_xpath, $this->import->options['variable_whosale_price'], $file)->parse($records)); $tmp_files[] = $file;
					}
				}
				else{
					$count_variations and $variation_whosale_price = array_fill(0, $count_variations, '');
				}	

				if ( $this->import->options['is_variable_sale_price_shedule']){
					// Sale price dates from
					if (!empty($this->import->options['variable_sale_price_dates_from'])){

						if ($this->import->options['variable_sale_dates_use_parent']){
							$parent_sale_date_start = XmlImportParser::factory($xml, $cxpath, $this->import->options['variable_sale_price_dates_from'], $file)->parse($records); $tmp_files[] = $file;
							$count_variations and $variation_sale_price_dates_from = array_fill(0, $count_variations, $parent_sale_date_start[$i]);							
						}
						else {
							$variation_sale_price_dates_from = XmlImportParser::factory($xml, $variation_xpath, $this->import->options['variable_sale_price_dates_from'], $file)->parse($records); $tmp_files[] = $file;
						}
					}
					else{
						$count_variations and $variation_sale_price_dates_from = array_fill(0, $count_variations, '');
					}

					// Sale price dates to
					if (!empty($this->import->options['variable_sale_price_dates_to'])){
						
						if ($this->import->options['variable_sale_dates_use_parent']){
							$parent_sale_date_end = XmlImportParser::factory($xml, $cxpath, $this->import->options['variable_sale_price_dates_to'], $file)->parse($records); $tmp_files[] = $file;
							$count_variations and $variation_sale_price_dates_to = array_fill(0, $count_variations, $parent_sale_date_end[$i]);							
						}
						else {
							$variation_sale_price_dates_to = XmlImportParser::factory($xml, $variation_xpath, $this->import->options['variable_sale_price_dates_to'], $file)->parse($records); $tmp_files[] = $file;
						}						
					}
					else{
						$count_variations and $variation_sale_price_dates_to = array_fill(0, $count_variations, '');
					}
				}			

				// Composing product is Virtual									
				if ($this->import->options['is_variable_product_virtual'] == 'xpath' and "" != $this->import->options['single_variable_product_virtual']){
					if ($this->import->options['single_variable_product_virtual_use_parent']){
						$parent_variable_product_virtual = XmlImportParser::factory($xml, $cxpath, $this->import->options['single_variable_product_virtual'], $file)->parse($records); $tmp_files[] = $file;
						$count_variations and $variation_product_virtual = array_fill(0, $count_variations, $parent_variable_product_virtual[$i]);						
					}
					else {
						$variation_product_virtual = XmlImportParser::factory($xml, $variation_xpath, $this->import->options['single_variable_product_virtual'], $file)->parse($records); $tmp_files[] = $file;						
					}
				}
				else{
					$count_variations and $variation_product_virtual = array_fill(0, $count_variations, $this->import->options['is_variable_product_virtual']);
				}				

				// Composing product is Downloadable									
				if ($this->import->options['is_variable_product_downloadable'] == 'xpath' and "" != $this->import->options['single_variable_product_downloadable']){
					if ($this->import->options['single_variable_product_downloadable_use_parent']){
						$parent_variable_product_downloadable = XmlImportParser::factory($xml, $cxpath, $this->import->options['single_variable_product_downloadable'], $file)->parse($records); $tmp_files[] = $file;
						$count_variations and $variation_product_downloadable = array_fill(0, $count_variations, $parent_variable_product_downloadable[$i]);						
					}
					else {
						$variation_product_downloadable = XmlImportParser::factory($xml, $variation_xpath, $this->import->options['single_variable_product_downloadable'], $file)->parse($records); $tmp_files[] = $file;						
					}
				}
				else{
					$count_variations and $variation_product_downloadable = array_fill(0, $count_variations, $this->import->options['is_variable_product_downloadable']);
				}

				// Weigth										
				if (!empty($this->import->options['variable_weight'])){
					if ($this->import->options['variable_weight_use_parent']){
						$parent_weight = XmlImportParser::factory($xml, $cxpath, $this->import->options['variable_weight'], $file)->parse($records); $tmp_files[] = $file;
						$count_variations and $variation_weight = array_fill(0, $count_variations, $parent_weight[$i]);						
					}
					else {
						$variation_weight = XmlImportParser::factory($xml, $variation_xpath, $this->import->options['variable_weight'], $file)->parse($records); $tmp_files[] = $file;
					}
				}
				else{
					$count_variations and $variation_weight = array_fill(0, $count_variations, '');
				}

				// Length										
				if (!empty($this->import->options['variable_length'])){
					if ($this->import->options['variable_dimensions_use_parent']){
						$parent_length = XmlImportParser::factory($xml, $cxpath, $this->import->options['variable_length'], $file)->parse($records); $tmp_files[] = $file;
						$count_variations and $variation_length = array_fill(0, $count_variations, $parent_length[$i]);						
					}
					else {
						$variation_length = XmlImportParser::factory($xml, $variation_xpath, $this->import->options['variable_length'], $file)->parse($records); $tmp_files[] = $file;
					}
				}
				else{
					$count_variations and $variation_length = array_fill(0, $count_variations, '');
				}

				// Width
				if (!empty($this->import->options['variable_width'])){
					if ($this->import->options['variable_dimensions_use_parent']){
						$parent_width = XmlImportParser::factory($xml, $cxpath, $this->import->options['variable_width'], $file)->parse($records); $tmp_files[] = $file;
						$count_variations and $variation_width = array_fill(0, $count_variations, $parent_width[$i]);						
					}
					else {
						$variation_width = XmlImportParser::factory($xml, $variation_xpath, $this->import->options['variable_width'], $file)->parse($records); $tmp_files[] = $file;
					}
				}
				else{
					$count_variations and $variation_width = array_fill(0, $count_variations, '');
				}

				// Heigth										
				if (!empty($this->import->options['variable_height'])){
					if ($this->import->options['variable_dimensions_use_parent']){
						$parent_heigth = XmlImportParser::factory($xml, $cxpath, $this->import->options['variable_height'], $file)->parse($records); $tmp_files[] = $file;
						$count_variations and $variation_height = array_fill(0, $count_variations, $parent_heigth[$i]);						
					}
					else {
						$variation_height = XmlImportParser::factory($xml, $variation_xpath, $this->import->options['variable_height'], $file)->parse($records); $tmp_files[] = $file;
					}
				}
				else{
					$count_variations and $variation_height = array_fill(0, $count_variations, '');
				}
				
				// Composing product Shipping Class				
				if ($this->import->options['is_multiple_variable_product_shipping_class'] != 'yes' and "" != $this->import->options['single_variable_product_shipping_class']){
					if ($this->import->options['single_variable_product_shipping_class_use_parent']){
						$parent_shipping_class = XmlImportParser::factory($xml, $cxpath, $this->import->options['single_variable_product_shipping_class'], $file)->parse($records); $tmp_files[] = $file;
						$count_variations and $variation_product_shipping_class = array_fill(0, $count_variations, $parent_shipping_class[$i]);						
					}
					else {
						$variation_product_shipping_class = XmlImportParser::factory($xml, $variation_xpath, $this->import->options['single_variable_product_shipping_class'], $file)->parse($records); $tmp_files[] = $file;						
					}
				}
				else{
					$count_variations and $variation_product_shipping_class = array_fill(0, $count_variations, $this->import->options['multiple_variable_product_shipping_class']);
				}

				// Composing product Tax Class				
				if ($this->import->options['is_multiple_variable_product_tax_class'] != 'yes' and "" != $this->import->options['single_variable_product_tax_class']){
					if ($this->import->options['single_variable_product_tax_class_use_parent']){
						$parent_tax_class = XmlImportParser::factory($xml, $cxpath, $this->import->options['single_variable_product_tax_class'], $file)->parse($records); $tmp_files[] = $file;
						$count_variations and $variation_product_tax_class = array_fill(0, $count_variations, $parent_tax_class[$i]);						
					}
					else {
						$variation_product_tax_class = XmlImportParser::factory($xml, $variation_xpath, $this->import->options['single_variable_product_tax_class'], $file)->parse($records); $tmp_files[] = $file;						
					}
				}
				else{
					$count_variations and $variation_product_tax_class = array_fill(0, $count_variations, $this->import->options['multiple_variable_product_tax_class']);
				}

				// Download limit										
				if (!empty($this->import->options['variable_download_limit'])){
					if ($this->import->options['variable_download_limit_use_parent']){
						$parent_download_limit = XmlImportParser::factory($xml, $cxpath, $this->import->options['variable_download_limit'], $file)->parse($records); $tmp_files[] = $file;
						$count_variations and $variation_download_limit = array_fill(0, $count_variations, $parent_download_limit[$i]);						
					}
					else {
						$variation_download_limit = XmlImportParser::factory($xml, $variation_xpath, $this->import->options['variable_download_limit'], $file)->parse($records); $tmp_files[] = $file;
					}
				}
				else{
					$count_variations and $variation_download_limit = array_fill(0, $count_variations, '');
				}

				// Download expiry										
				if (!empty($this->import->options['variable_download_expiry'])){
					if ($this->import->options['variable_download_expiry_use_parent']){
						$parent_download_expiry = XmlImportParser::factory($xml, $cxpath, $this->import->options['variable_download_expiry'], $file)->parse($records); $tmp_files[] = $file;
						$count_variations and $variation_download_expiry = array_fill(0, $count_variations, $parent_download_expiry[$i]);						
					}
					else {
						$variation_download_expiry = XmlImportParser::factory($xml, $variation_xpath, $this->import->options['variable_download_expiry'], $file)->parse($records); $tmp_files[] = $file;
					}
				}
				else{
					$count_variations and $variation_download_expiry = array_fill(0, $count_variations, '');
				}

				// File paths								
				if (!empty($this->import->options['variable_file_paths'])){
					$variation_file_paths = XmlImportParser::factory($xml, $variation_xpath, $this->import->options['variable_file_paths'], $file)->parse($records); $tmp_files[] = $file;
				}
				else{
					$count_variations and $variation_file_paths = array_fill(0, $count_variations, '');
				}

				// File names								
				if (!empty($this->import->options['variable_file_names'])){
					$variation_file_names = XmlImportParser::factory($xml, $variation_xpath, $this->import->options['variable_file_names'], $file)->parse($records); $tmp_files[] = $file;
				}
				else{
					$count_variations and $variation_file_names = array_fill(0, $count_variations, '');
				}

				// Variation enabled								
				if ($this->import->options['is_variable_product_enabled'] == 'xpath' and "" != $this->import->options['single_variable_product_enabled']){
					$variation_product_enabled = XmlImportParser::factory($xml, $variation_xpath, $this->import->options['single_variable_product_enabled'], $file)->parse($records); $tmp_files[] = $file;						
				}
				else{
					$count_variations and $variation_product_enabled = array_fill(0, $count_variations, $this->import->options['is_variable_product_enabled']);
				}

				$variation_attribute_keys = array(); 
				$variation_attribute_values = array();	
				$variation_attribute_in_variation = array(); 
				$variation_attribute_is_visible = array();
				$variation_attribute_in_taxonomy = array();			
				$variable_create_terms_in_not_exists = array();
									
				if (!empty($this->import->options['variable_attribute_name'][0])){
					foreach ($this->import->options['variable_attribute_name'] as $j => $attribute_name) { if ($attribute_name == "") continue;						
						$variation_attribute_keys[$j]   = XmlImportParser::factory($xml, $variation_xpath, $attribute_name, $file)->parse($records); $tmp_files[] = $file;
						$variation_attribute_values[$j] = XmlImportParser::factory($xml, $variation_xpath, $this->import->options['variable_attribute_value'][$j], $file)->parse($records); $tmp_files[] = $file;
						$variation_attribute_in_variation[$j] = XmlImportParser::factory($xml, $variation_xpath, $this->import->options['variable_in_variations'][$j], $file)->parse($records); $tmp_files[] = $file;
						$variation_attribute_is_visible[$j] = XmlImportParser::factory($xml, $variation_xpath, $this->import->options['variable_is_visible'][$j], $file)->parse($records); $tmp_files[] = $file;						
						$variation_attribute_in_taxonomy[$j] = XmlImportParser::factory($xml, $variation_xpath, $this->import->options['variable_is_taxonomy'][$j], $file)->parse($records); $tmp_files[] = $file;						
						$variable_create_terms_in_not_exists[$j] = XmlImportParser::factory($xml, $variation_xpath, $this->import->options['variable_create_taxonomy_in_not_exists'][$j], $file)->parse($records); $tmp_files[] = $file;
					}
				}					

				// serialized attributes for product variations
				$variation_serialized_attributes = array();
				if (!empty($variation_attribute_keys)){
					foreach ($variation_attribute_keys as $j => $attribute_name) {											
						if (!in_array($attribute_name[0], array_keys($variation_serialized_attributes))){
							$variation_serialized_attributes[$attribute_name[0]] = array(
								'value' => $variation_attribute_values[$j],
								'is_visible' => $variation_attribute_is_visible[$j],
								'in_variation' => $variation_attribute_in_variation[$j],
								'in_taxonomy' => $variation_attribute_in_taxonomy[$j],
								'is_create_taxonomy_terms' => $variable_create_terms_in_not_exists[$j]
							);						
						}							
					}
				} 

				// Create Variations
				foreach ($variations as $j => $void) {

                      $variation_sku_for_title = ("" == $variation_sku[$j]) ? $j : $variation_sku[$j];

                      if ($this->import->options['variable_sku_add_parent']){
                          $variation_sku[$j] = $product_sku[$i] . '-' . $variation_sku[$j];
                          $variation_sku_for_title = $product_sku[$i] . '-' . $variation_sku[$j];
                      }

                      $is_variation_have_attributes = false;
                      foreach ($variation_serialized_attributes as $attr => $attr_options){
                          if ($attr_options['in_variation'][$j] && $attr_options['value'][$j] != ''){
                              $is_variation_have_attributes = true;
                              break;
                          }
                      }
                      // do not create variation if it doesn't have attributes
                      if ( ! $is_variation_have_attributes && $this->import->options['make_simple_product']) continue;

					$variable_enabled = ($variation_product_enabled[$j] == "yes") ? 'yes' : 'no'; 					

					// Enabled or disabled
					$post_status = ( $variable_enabled == 'yes' ) ? 'publish' : 'private';
					$variation_to_update_id = false;					
					$postRecord = new PMXI_Post_Record();
					$postRecord->clear();																					
						
					// Generate a useful post title
					$variation_post_title = sprintf( __( 'Variation #%s of %s', 'wpai_woocommerce_addon_plugin' ), $variation_sku_for_title, $articleData['post_title'] );

					// handle duplicates according to import settings
					/*if ($duplicates = pmxi_findDuplicates(array('post_title' => $variation_post_title, 'post_type' => 'product_variation', 'post_parent' => $pid),'','','parent')) {															
						$duplicate_id = array_shift($duplicates);							
						if ($duplicate_id) {														
							$variation_to_update = get_post($variation_to_update_id = $duplicate_id);
						}						
					}	*/					

					// Update or Add post							

					$variation = array(
						'post_title' 	=> $variation_post_title,
						'post_content' 	=> '',
						'post_status' 	=> $post_status,									
						'post_parent' 	=> $pid,
						'post_type' 	=> 'product_variation'									
					);

					$variation_just_created = false;

					$postRecord->getBy(array(
						'unique_key' => 'Variation ' . $variation_sku_for_title . ' of ' . $pid,
						'import_id' => $this->import->id
					));
					if ( ! $postRecord->isEmpty() ){
						$variation_to_update_id = $postRecord->post_id;
						$postRecord->set(array('iteration' => $this->import->iteration))->update();											
					}

					if ( ! $variation_to_update_id ) {

						$variation_to_update_id = wp_insert_post( $variation );

						// associate variation with import
						$postRecord->isEmpty() and $postRecord->set(array(
							'post_id' => $variation_to_update_id,
							'import_id' => $this->import->id,
							'unique_key' => 'Variation ' . $variation_sku_for_title . ' of ' . $pid,
							'product_key' => ''
						))->insert();	

						$postRecord->set(array('iteration' => $this->import->iteration))->update();

						$variation_just_created = true;		

						$logger and call_user_func($logger, sprintf(__('- `%s`: variation created successfully', 'wpai_woocommerce_addon_plugin'), sprintf( __( 'Variation #%s of %s', 'wpai_woocommerce_addon_plugin' ), absint( $variation_to_update_id ), esc_html( get_the_title( $pid ) ) )));

					} else {											
							
						$this->wpdb->update( $this->wpdb->posts, $variation, array( 'ID' => $variation_to_update_id ) );
						//do_action( 'woocommerce_update_product_variation', $variation_to_update_id );
						$logger and call_user_func($logger, sprintf(__('- `%s`: variation updated successfully', 'wpai_woocommerce_addon_plugin'), $variation_post_title));
						
						if ( $this->import->options['update_all_data'] == 'yes' or ( $this->import->options['update_all_data'] == 'no' and $this->import->options['is_update_attachments'])) {
							$logger and call_user_func($logger, sprintf(__('Deleting attachments for `%s`', 'wp_all_import_plugin'), $variation_post_title));								
							wp_delete_attachments($variation_to_update_id, true, 'files');
						}
						// handle obsolete attachments (i.e. delete or keep) according to import settings
						if ( $this->import->options['update_all_data'] == 'yes' or ( $this->import->options['update_all_data'] == 'no' and $this->import->options['is_update_images'] and $this->import->options['update_images_logic'] == "full_update")){
							$logger and call_user_func($logger, sprintf(__('Deleting images for `%s`', 'wp_all_import_plugin'), $variation_post_title));								
							wp_delete_attachments($variation_to_update_id, ! $this->import->options['do_not_remove_images'], 'images');
						}

					}		

					do_action( 'pmxi_update_product_variation', $variation_to_update_id );								

					$existing_variation_meta_keys = array();
					foreach (get_post_meta($variation_to_update_id, '') as $cur_meta_key => $cur_meta_val) $existing_variation_meta_keys[] = $cur_meta_key;

					// delete keys which are no longer correspond to import settings																
					if ( !empty($existing_variation_meta_keys) ){
                        foreach ($existing_variation_meta_keys as $cur_meta_key) {

                            // Do not delete post meta for features image
                            if ( in_array($cur_meta_key, array('_thumbnail_id','_product_image_gallery')) ) continue;

                            // Update all data
                            if ($this->import->options['update_all_data'] == 'yes' or $variation_just_created) {
                                delete_post_meta($variation_to_update_id, $cur_meta_key);
                                continue;
                            }

                            // Do not update attributes
                            if ( ($variation_just_created or ! $this->import->options['is_update_attributes']) and (in_array($cur_meta_key, array('_default_attributes', '_product_attributes')) or strpos($cur_meta_key, "attribute_") === 0)) continue;

                            // Update only these Attributes, leave the rest alone
                            if ( ($variation_just_created or $this->import->options['is_update_attributes']) and $this->import->options['update_attributes_logic'] == 'only'){

                                if ($cur_meta_key == '_product_attributes'){
                                    $current_product_attributes = get_post_meta($variation_to_update_id, '_product_attributes', true);
                                    if ( ! empty($current_product_attributes) and ! empty($this->import->options['attributes_list']) and is_array($this->import->options['attributes_list']))
                                        foreach ($current_product_attributes as $attr_name => $attr_value) {
                                            if ( in_array($attr_name, array_filter($this->import->options['attributes_list'], 'trim'))) unset($current_product_attributes[$attr_name]);
                                        }

                                    update_post_meta($variation_to_update_id, '_product_attributes', $current_product_attributes);
                                    continue;
                                }

                                if ( strpos($cur_meta_key, "attribute_") === 0 and ! empty($this->import->options['attributes_list']) and is_array($this->import->options['attributes_list']) and ! in_array(str_replace("attribute_", "", $cur_meta_key), array_filter($this->import->options['attributes_list'], 'trim'))) continue;

                                if (in_array($cur_meta_key, array('_default_attributes'))) continue;
                            }

                            // Leave these attributes alone, update all other Attributes
                            if ( ($variation_just_created or $this->import->options['is_update_attributes']) and $this->import->options['update_attributes_logic'] == 'all_except'){

                                if ($cur_meta_key == '_product_attributes'){

                                    if (empty($this->import->options['attributes_list'])) { delete_post_meta($variation_to_update_id, $cur_meta_key); continue; }

                                    $current_product_attributes = get_post_meta($variation_to_update_id, '_product_attributes', true);
                                    if ( ! empty($current_product_attributes) and ! empty($this->import->options['attributes_list']) and is_array($this->import->options['attributes_list']))
                                        foreach ($current_product_attributes as $attr_name => $attr_value) {
                                            if ( ! in_array($attr_name, array_filter($this->import->options['attributes_list'], 'trim'))) unset($current_product_attributes[$attr_name]);
                                        }

                                    update_post_meta($variation_to_update_id, '_product_attributes', $current_product_attributes);
                                    continue;
                                }

                                if ( strpos($cur_meta_key, "attribute_") === 0 and ! empty($this->import->options['attributes_list']) and is_array($this->import->options['attributes_list']) and in_array(str_replace("attribute_", "", $cur_meta_key), array_filter($this->import->options['attributes_list'], 'trim'))) continue;

                                if (in_array($cur_meta_key, array('_default_attributes'))) continue;
                            }

                            // Update all Custom Fields is defined
                            if ($this->import->options['update_custom_fields_logic'] == "full_update"){
                                delete_post_meta($variation_to_update_id, $cur_meta_key);
                            }
                            // Update only these Custom Fields, leave the rest alone
                            elseif ($this->import->options['update_custom_fields_logic'] == "only"){
                                if ( ! empty($this->import->options['custom_fields_list']) and is_array($this->import->options['custom_fields_list']) and in_array($cur_meta_key, $this->import->options['custom_fields_list'])) delete_post_meta($variation_to_update_id, $cur_meta_key);
                            }
                            // Leave these fields alone, update all other Custom Fields
                            elseif ($this->import->options['update_custom_fields_logic'] == "all_except"){
                                if ( empty($this->import->options['custom_fields_list']) or ! in_array($cur_meta_key, $this->import->options['custom_fields_list'])) delete_post_meta($variation_to_update_id, $cur_meta_key);
                            }
                        }
                    }

					// Add any default post meta
					//add_post_meta( $variation_to_update_id, 'total_sales', '0', true );
					$v_total_sales = get_post_meta($variation_to_update_id, 'total_sales', true);

					if ( empty($v_total_sales)) update_post_meta($variation_to_update_id, 'total_sales', '0');	
					
					// Product type + Downloadable/Virtual
					wp_set_object_terms( $variation_to_update_id, NULL, 'product_type' );
					update_post_meta( $variation_to_update_id, '_downloadable', ($variation_product_downloadable[$j] == "yes") ? 'yes' : 'no' );
					update_post_meta( $variation_to_update_id, '_virtual', ($variation_product_virtual[$j] == "yes") ? 'yes' : 'no' );						
					
					// Update post meta
					if ($variation_just_created or $this->is_update_cf('_regular_price')) update_post_meta( $variation_to_update_id, '_regular_price', stripslashes( $variation_regular_price[$j] ) );
					if ($variation_just_created or $this->is_update_cf('_sale_price')) update_post_meta( $variation_to_update_id, '_sale_price', stripslashes( $variation_sale_price[$j] ) );
					if ( class_exists('woocommerce_wholesale_pricing') ) update_post_meta( $variation_to_update_id, 'pmxi_wholesale_price', stripslashes( $variation_whosale_price[$j] ) );

					// Dimensions
					if ( $variation_product_virtual[$j] == 'yes' ) {
                        if ($variation_just_created or $this->is_update_cf('_weight')) update_post_meta( $variation_to_update_id, '_weight', '' );
                        if ($variation_just_created or $this->is_update_cf('_length')) update_post_meta( $variation_to_update_id, '_length', '' );
                        if ($variation_just_created or $this->is_update_cf('_width')) update_post_meta( $variation_to_update_id, '_width', '' );
                        if ($variation_just_created or $this->is_update_cf('_height')) update_post_meta( $variation_to_update_id, '_height', '' );
					} else {
                        if ($variation_just_created or $this->is_update_cf('_weight')) update_post_meta( $variation_to_update_id, '_weight', stripslashes( $variation_weight[$j] ) );
                        if ($variation_just_created or $this->is_update_cf('_length')) update_post_meta( $variation_to_update_id, '_length', stripslashes( $variation_length[$j] ) );
                        if ($variation_just_created or $this->is_update_cf('_width')) update_post_meta( $variation_to_update_id, '_width', stripslashes( $variation_width[$j] ) );
                        if ($variation_just_created or $this->is_update_cf('_height')) update_post_meta( $variation_to_update_id, '_height', stripslashes( $variation_height[$j] ) );
					}															
					
					// Save shipping class		
					if (ctype_digit($variation_product_shipping_class[ $j ])){

                        $t_shipping_class = get_term_by('slug', $variation_product_shipping_class[ $j ], 'product_shipping_class');

                        // For compatibility with WPML plugin
                        $t_shipping_class = apply_filters('wp_all_import_term_exists', $t_shipping_class, 'product_shipping_class', $variation_product_shipping_class[ $j ], null);

                        if ( ! empty($t_shipping_class) and ! is_wp_error($t_shipping_class) )
                        {
                            $v_shipping_class = (int) $t_shipping_class->term_taxonomy_id;
                        }
                        else{
                            $t_shipping_class = is_exists_term( (int) $variation_product_shipping_class[ $j ], 'product_shipping_class', 0);

                            if ( ! empty($t_shipping_class) and ! is_wp_error($t_shipping_class) )
                            {
                                $v_shipping_class = (int) $t_shipping_class['term_taxonomy_id'];
                            }
                        }

					}
					else{

						$vt_shipping_class = is_exists_term($variation_product_shipping_class[ $j ], 'product_shipping_class', 0);	
						if ( empty($vt_shipping_class) and !is_wp_error($vt_shipping_class) ){																																
							$vt_shipping_class = is_exists_term(htmlspecialchars($variation_product_shipping_class[ $j ]), 'product_shipping_class', 0);						
						}
						if ( ! is_wp_error($vt_shipping_class) )												
							$v_shipping_class = (int) $vt_shipping_class['term_id']; 				
					}

                    $this->associate_terms( $variation_to_update_id, array( $v_shipping_class ), 'product_shipping_class' );

					// Unique SKU
					$sku				= get_post_meta($variation_to_update_id, '_sku', true);
					$new_sku 			= esc_html( trim( stripslashes( $variation_sku[$j] ) ) );
					
					if ( $new_sku == '' and $this->import->options['disable_auto_sku_generation'] ) {
						if ($variation_just_created or $this->is_update_cf('_sku')) 				
								update_post_meta( $variation_to_update_id, '_sku', '' );
					}
					elseif ( $new_sku == '' and ! $this->import->options['disable_auto_sku_generation'] ) {
						if ($variation_just_created or $this->is_update_cf('_sku')){
							$new_sku = substr(md5($variation_post_title), 0, 12);
						}
					}

					if ( $new_sku == '' ) {
						update_post_meta( $variation_to_update_id, '_sku', '' );
					} elseif ( $new_sku !== $sku ) {
						if ( ! empty( $new_sku ) ) {
							if ( ! $this->import->options['disable_sku_matching']  and 
								$this->wpdb->get_var( $this->wpdb->prepare("
									SELECT ".$this->wpdb->posts.".ID
								    FROM ".$this->wpdb->posts."
								    LEFT JOIN ".$this->wpdb->postmeta." ON (".$this->wpdb->posts.".ID = ".$this->wpdb->postmeta.".post_id)
								    WHERE ".$this->wpdb->posts.".post_type = 'product'
								    AND ".$this->wpdb->posts.".post_status = 'publish'
								    AND ".$this->wpdb->postmeta.".meta_key = '_sku' AND ".$this->wpdb->postmeta.".meta_value = '%s'
								 ", $new_sku ) )
								) {
								$logger and call_user_func($logger, sprintf(__('- <b>WARNING</b>: Product SKU must be unique.', 'wpai_woocommerce_addon_plugin')));							
								
							} else {
								update_post_meta( $variation_to_update_id, '_sku', $new_sku );
							}
						} else {
							update_post_meta( $variation_to_update_id, '_sku', '' );
						}
					}

					$date_from = isset( $variation_sale_price_dates_from[$j] ) ? $variation_sale_price_dates_from[$j] : '';
					$date_to = isset( $variation_sale_price_dates_to[$i] ) ? $variation_sale_price_dates_to[$i] : '';

					// Variable Description
					$this->pushmeta($variation_to_update_id, '_variation_description', $variation_description[$j] );

					// Dates
					if ( $date_from )
                        $this->pushmeta( $variation_to_update_id, '_sale_price_dates_from', strtotime( $date_from ) );
					else
                        $this->pushmeta( $variation_to_update_id, '_sale_price_dates_from', '' );

					if ( $date_to )
                        $this->pushmeta( $variation_to_update_id, '_sale_price_dates_to', strtotime( $date_to ) );
					else
                        $this->pushmeta( $variation_to_update_id, '_sale_price_dates_to', '' );

					if ( $date_to && ! $date_from )
                        $this->pushmeta( $variation_to_update_id, '_sale_price_dates_from', strtotime( 'NOW', current_time( 'timestamp' ) ) );

					// Update price if on sale
					if ( $variation_sale_price[$j] == '' )
					{
						if ( ! empty($this->articleData['ID']) and ! $this->is_update_cf('_sale_price') )
						{
							$variation_sale_price[$j] = get_post_meta($variation_to_update_id, '_sale_price', true);							
						}						
					}

					if ( $variation_sale_price[$j] != '' && $date_to == '' && $date_from == '' ){
						$this->pushmeta($variation_to_update_id, '_price', stripslashes( $variation_sale_price[$j] ));						
					}
					else{
						$this->pushmeta($variation_to_update_id, '_price', stripslashes( $variation_regular_price[$j] ));						
					}

                    if ( $variation_sale_price[$j] != '' && $date_from && strtotime( $date_from ) < strtotime( 'NOW', current_time( 'timestamp' ) ) ){
                        $this->pushmeta($variation_to_update_id, '_price', ($variation_sale_price[$j] == "") ? '' : stripslashes( $variation_sale_price[$j] ));
                    }

                    if ( $date_to && strtotime( $date_to ) < strtotime( 'NOW', current_time( 'timestamp' ) ) ) {
                        $this->pushmeta($variation_to_update_id, '_price', ($variation_regular_price[$j] == "") ? '' : stripslashes( $variation_regular_price[$j] ));
                    }

                    // Stock Data
                    $stock_data_args = array(
                        'stock_status' => $variable_stock_status[$j],
                        'manage_stock' => $variation_product_manage_stock[$j],
                        'allow_backorders' => $variable_allow_backorders[$j],
                        'qty' => $variation_stock[$j]
                    );
                    $this->import_stock_data($variation_to_update_id, 'variable', $stock_data_args );

                    // Update product visibility term WC 3.0.0
                    if ( version_compare(WOOCOMMERCE_VERSION, '3.0') >= 0 ) {

                        $associate_terms = array();

                        $term_ids = wp_get_object_terms($variation_to_update_id, 'product_visibility', array('fields' => 'ids'));

                        // If Not Update Featured Status checking for current featured status
                        if ( ! empty($articleData['ID']) && ( ! $this->is_update_data_allowed('is_update_advanced_options') || ! $this->is_update_data_allowed('is_update_featured_status'))) {
                            $featured_term = get_term_by( 'name', 'featured', 'product_visibility' );
                            if ( ! empty($featured_term) && ! is_wp_error($featured_term) && in_array($featured_term->term_id, $term_ids) ){
                                $associate_terms[] = $featured_term->term_taxonomy_id;
                            }
                        }
                        else{
                            if ($is_featured == "yes"){
                                $featured_term = get_term_by( 'name', 'featured', 'product_visibility' );
                                if ( ! empty($featured_term) && !is_wp_error($featured_term)){
                                    $associate_terms[] = $featured_term->term_taxonomy_id;
                                }
                            }
                        }

                        // If Not Update Product Visibility checking for current product visibility
                        if ( ! empty($articleData['ID']) && ( ! $this->is_update_data_allowed('is_update_advanced_options') || ! $this->is_update_data_allowed('is_update_catalog_visibility'))) {
                            $exclude_search_term = get_term_by( 'name', 'exclude-from-search', 'product_visibility' );
                            if (!empty($exclude_search_term) && !is_wp_error($exclude_search_term) && in_array($exclude_search_term->term_id, $term_ids)){
                                $associate_terms[] = $exclude_search_term->term_taxonomy_id;
                            }
                            $exclude_catalog_term = get_term_by( 'name', 'exclude-from-catalog', 'product_visibility' );
                            if (!empty($exclude_catalog_term) && !is_wp_error($exclude_catalog_term) && in_array($exclude_catalog_term->term_id, $term_ids)){
                                $associate_terms[] = $exclude_catalog_term->term_taxonomy_id;
                            }
                        }
                        else{
                            if (in_array($product_visibility[$i], array('hidden', 'search'))){
                                $exclude_search_term = get_term_by( 'name', 'exclude-from-catalog', 'product_visibility' );
                                if (!empty($exclude_search_term) && !is_wp_error($exclude_search_term)){
                                    $associate_terms[] = $exclude_search_term->term_taxonomy_id;
                                }
                            }
                            if (in_array($product_visibility[$i], array('hidden', 'catalog'))){
                                $exclude_catalog_term = get_term_by( 'name', 'exclude-from-search', 'product_visibility' );
                                if (!empty($exclude_catalog_term) && !is_wp_error($exclude_catalog_term)){
                                    $associate_terms[] = $exclude_catalog_term->term_taxonomy_id;
                                }
                            }
                        }

                        $_stock_status = get_post_meta( $variation_to_update_id, '_stock_status', true);
                        if ( $_stock_status == 'outofstock' ){
                            $outofstock_term = get_term_by( 'name', 'outofstock', 'product_visibility' );
                            if (!empty($outofstock_term) && !is_wp_error($outofstock_term) && !in_array($outofstock_term->term_id, $term_ids)){
                                $associate_terms[] = $outofstock_term->term_taxonomy_id;
                            }
                        }

                        $this->associate_terms( $variation_to_update_id, $associate_terms, 'product_visibility' );
                    }

					if ( $variation_product_tax_class[ $j ] !== 'parent' )
						update_post_meta( $variation_to_update_id, '_tax_class', strtolower($variation_product_tax_class[ $j ]) == 'standard' ? '' : sanitize_text_field( $variation_product_tax_class[ $j ] ) );
					else
						delete_post_meta( $variation_to_update_id, '_tax_class' );

					if ( $variation_product_downloadable[$j] == 'yes' ) {
						update_post_meta( $variation_to_update_id, '_download_limit', sanitize_text_field( $variation_download_limit[ $j ] ) );
						update_post_meta( $variation_to_update_id, '_download_expiry', sanitize_text_field( $variation_download_expiry[ $j ] ) );

						$_file_paths = array();
						
						if ( !empty($variation_file_paths[$j]) ) {
							$file_paths = explode( $this->import->options['variable_product_files_delim'] , $variation_file_paths[$j] );
							$file_names = explode( $this->import->options['variable_product_files_names_delim'] , $variation_file_names[$j] );

							foreach ( $file_paths as $fn => $file_path ) {
								$file_path = sanitize_text_field( $file_path );								
								$_file_paths[ md5( $file_path ) ] = array('name' => ((!empty($file_names[$fn])) ? $file_names[$fn] : basename($file_path)), 'file' => $file_path);
							}
						}

						// grant permission to any newly added files on any existing orders for this product						
						update_post_meta( $variation_to_update_id, '_downloadable_files', $_file_paths );
					} else {
						update_post_meta( $variation_to_update_id, '_download_limit', '' );
						update_post_meta( $variation_to_update_id, '_download_expiry', '' );
						update_post_meta( $variation_to_update_id, '_downloadable_files', '' );
						update_post_meta( $variation_to_update_id, '_download_type', '' );
					}

					// Remove old taxonomies attributes so data is kept up to date
					if ( $variation_to_update_id and ( $this->import->options['update_all_data'] == 'yes' or ($this->import->options['update_all_data'] == 'no' and $this->import->options['is_update_attributes']) or $variation_just_created) ) {
						if ($this->import->options['update_all_data'] == 'yes' or $this->import->options['update_attributes_logic'] == 'full_update' ) $this->wpdb->query( $this->wpdb->prepare( "DELETE FROM {$this->wpdb->postmeta} WHERE meta_key LIKE 'attribute_%%' AND post_id = %d;", $variation_to_update_id ) );
						wp_cache_delete( $variation_to_update_id, 'post_meta');
					}

					// Update taxonomies
					if ( $this->import->options['update_all_data'] == 'yes' or ($this->import->options['update_all_data'] == 'no' and $this->import->options['is_update_attributes']) or $variation_just_created ){

						foreach ($variation_serialized_attributes as $a_name => $attr_data) {																										

							$attr_name = $a_name;														

							// Update only these Attributes, leave the rest alone
							if ($this->import->options['update_all_data'] == 'no' and $this->import->options['update_attributes_logic'] == 'only' and ! $variation_just_created ){
								if ( ! empty($this->import->options['attributes_list']) and is_array($this->import->options['attributes_list'])){
									if ( ! in_array( ( (intval($attr_data['in_taxonomy'][$j])) ? wc_attribute_taxonomy_name( $attr_name ) : $attr_name ) , array_filter($this->import->options['attributes_list'], 'trim'))) continue;
								}
								else break;								
							}	

							// Leave these attributes alone, update all other Attributes
							if ($this->import->options['update_all_data'] == 'no' and $this->import->options['update_attributes_logic'] == 'all_except' and ! $variation_just_created){
								if ( ! empty($this->import->options['attributes_list']) and is_array($this->import->options['attributes_list'])) {
									if ( in_array( ( (intval($attr_data['in_taxonomy'][$j])) ? wc_attribute_taxonomy_name( $attr_name ) : $attr_name ) , array_filter($this->import->options['attributes_list'], 'trim'))) continue;								
								}
							}	
												
							if ( intval($attr_data['in_taxonomy'][$j]) and ( strpos($attr_name, "pa_") === false or strpos($attr_name, "pa_") !== 0 ) ) $attr_name = "pa_" . $attr_name;								

							$is_variation 	= intval( $attr_data['in_variation'][$j]);													
								
							// Don't use woocommerce_clean as it destroys sanitized characters																								
							$values = (intval($attr_data['in_taxonomy'][$j])) ? $attr_data['value'][$j] : $attr_data['value'][$j];	
							
							if (intval($attr_data['in_taxonomy'][$j])){

								if (intval($attr_data['is_create_taxonomy_terms'][0])){
                                    $a_name = $this->create_taxonomy($a_name, $logger);
                                }

								$term = get_term_by('name', $values, wc_attribute_taxonomy_name( $a_name ), ARRAY_A);
								// For compatibility with WPML plugin
						 		$term = apply_filters('wp_all_import_term_exists', $term, wc_attribute_taxonomy_name( $a_name ), $values, null);

								if ( empty($term) and ! is_wp_error($term) ){		

						 			$term = is_exists_term($values, wc_attribute_taxonomy_name( $a_name ));							 			

						 			if ( empty($term) and !is_wp_error($term) ){																																
										$term = is_exists_term(htmlspecialchars($values), wc_attribute_taxonomy_name( $a_name ));	
										if ( empty($term) and !is_wp_error($term) and intval($attr_data['is_create_taxonomy_terms'][0])){		
											
											$term = wp_insert_term(
												$values, // the term 
											  	wc_attribute_taxonomy_name( $a_name ) // the taxonomy										  	
											);													
										}
									}									
								}
								
								if ( ! is_wp_error($term) )				
								{
									$term = get_term_by( 'id', $term['term_id'], wc_attribute_taxonomy_name( $a_name ));
									update_post_meta( $variation_to_update_id, 'attribute_' . sanitize_title( $attr_name ), $term->slug );
								}								

							} else {
								update_post_meta( $variation_to_update_id, 'attribute_' . sanitize_title( $attr_name ), $values );		
							}							
							
						}
					}					

					if ( ! is_array($variation_image[$j]) ) $variation_image[$j] = array($variation_image[$j]);

					$uploads = wp_upload_dir();

					if ( ! empty($uploads) and false === $uploads['error'] and !empty($variation_image[$j]) and (empty($articleData['ID']) or $this->import->options['update_all_data'] == "yes" or ( $this->import->options['update_all_data'] == "no" and $this->import->options['is_update_images']))) {

						require_once(ABSPATH . 'wp-admin/includes/image.php');	

						$targetDir = $uploads['path'];
						$targetUrl = $uploads['url'];

						$gallery_attachment_ids = array();	

						foreach ($variation_image[$j] as $featured_image)
						{							
							$imgs = explode(',', $featured_image);

							if (!empty($imgs)) {	

								foreach ($imgs as $img_url) { if (empty($img_url)) continue;	

									$attid = false;		

									$attch = null;	

									$url = str_replace(" ", "%20", trim($img_url));
									$bn  = wp_all_import_sanitize_filename(basename($url));
									
									$img_ext = pmxi_getExtensionFromStr($url);									
									$default_extension = pmxi_getExtension($bn);																									
									if ($img_ext == "") 										
										$img_ext = pmxi_get_remote_image_ext($url);																			

									// generate local file name
									$image_name = apply_filters("wp_all_import_image_filename", urldecode(sanitize_file_name((($img_ext) ? str_replace("." . $default_extension, "", $bn) : $bn))) . (("" != $img_ext) ? '.' . $img_ext : ''));																										
									// if wizard store image data to custom field									
									$create_image = false;
									$download_image = true;

									$image_filename = wp_unique_filename($uploads['path'], $image_name);
									$image_filepath = $uploads['path'] . DIRECTORY_SEPARATOR . $image_filename;																

									// search existing attachment
									if ($this->import->options['search_existing_images'] or "gallery" == $this->import->options['download_images']){
										
										$image_filename = $image_name;

										$attch = wp_all_import_get_image_from_gallery($image_name, $targetDir, "images");

										if ("gallery" == $this->import->options['download_images']) $download_image = false;

										if (empty($attch))
										{
											$logger and call_user_func($logger, sprintf(__('- <b>WARNING</b>: Image %s not found in media gallery.', 'wp_all_import_plugin'), trim($image_name)));
										}	
										else
										{
											$logger and call_user_func($logger, sprintf(__('- Using existing image `%s` for post `%s` ...', 'wp_all_import_plugin'), trim($image_name), $variation_post_title));
											$download_image = false;
											$create_image   = false;
											$attid 			= $attch->ID;															
										}	
									}

									if ($download_image && "gallery" != $this->import->options['download_images']){

										// do not download images
										if ( "no" == $this->import->options['download_images'] ){													

											$image_filename = $image_name;
											$image_filepath = $targetDir . DIRECTORY_SEPARATOR . $image_filename;		
												
											$wpai_uploads = $uploads['basedir'] . DIRECTORY_SEPARATOR . PMXI_Plugin::FILES_DIRECTORY . DIRECTORY_SEPARATOR;
											$wpai_image_path = $wpai_uploads . str_replace('%20', ' ', $url);

											$logger and call_user_func($logger, sprintf(__('- Searching for existing image `%s` in `%s` folder', 'wp_all_import_plugin'), $wpai_image_path, $wpai_uploads));
											
											if ( @file_exists($wpai_image_path) and @copy( $wpai_image_path, $image_filepath )){
												$download_image = false;		
												// valdate import attachments
												if( ! ($image_info = @getimagesize($image_filepath)) or ! in_array($image_info[2], array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG))) {
													$logger and call_user_func($logger, sprintf(__('- <b>WARNING</b>: File %s is not a valid image and cannot be set as featured one', 'wp_all_import_plugin'), $image_filepath));														
													@unlink($image_filepath);
												} else {
													$create_image = true;											
													$logger and call_user_func($logger, sprintf(__('- Image `%s` has been successfully found', 'wp_all_import_plugin'), $wpai_image_path));
												}
											}													
										}	
										else {												
											
											$logger and call_user_func($logger, sprintf(__('- Downloading image from `%s`', 'wp_all_import_plugin'), $url));

											$request = get_file_curl($url, $image_filepath);

											if ( (is_wp_error($request) or $request === false) and ! @file_put_contents($image_filepath, @file_get_contents($url))) {
												@unlink($image_filepath); // delete file since failed upload may result in empty file created
											} else{

												if( ($image_info = @getimagesize($image_filepath)) and in_array($image_info[2], array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG))) {
													$create_image = true;		
													$logger and call_user_func($logger, sprintf(__('- Image `%s` has been successfully downloaded', 'wp_all_import_plugin'), $url));									
												}
											}												
											
											if ( ! $create_image ){

												$url = str_replace(" ", "%20", trim(pmxi_convert_encoding($img_url)));
												
												$request = get_file_curl($url, $image_filepath);

												if ( (is_wp_error($request) or $request === false) and ! @file_put_contents($image_filepath, @file_get_contents($url))) {
													$logger and call_user_func($logger, sprintf(__('- <b>WARNING</b>: File %s cannot be saved locally as %s', 'wp_all_import_plugin'), $url, $image_filepath));													
													@unlink($image_filepath); // delete file since failed upload may result in empty file created										
												} 
												else{
													if( ! ($image_info = @getimagesize($image_filepath)) or ! in_array($image_info[2], array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG))) {
														$logger and call_user_func($logger, sprintf(__('- <b>WARNING</b>: File %s is not a valid image and cannot be set as featured one', 'wp_all_import_plugin'), $url));														
														@unlink($image_filepath);
													} else {
														$create_image = true;	
														$logger and call_user_func($logger, sprintf(__('- Image `%s` has been successfully downloaded', 'wp_all_import_plugin'), $url));												
													}
												}
											}
										}												
									}			

									$handle_image = false;						

									if ($create_image){

										$handle_image = array(
											'file' => $image_filepath,
											'url'  => $targetUrl . '/' . $image_filename,
											'type' => image_type_to_mime_type($image_info[2])
										); 
										
										$logger and call_user_func($logger, sprintf(__('- Creating an attachment for image `%s`', 'wp_all_import_plugin'), $handle_image['url']));	

										$attachment_title = explode(".", $image_name);
										if (is_array($attachment_title) and count($attachment_title) > 1) array_pop($attachment_title);

										$attachment = array(
											'post_mime_type' => $handle_image['type'],
											'guid' => $handle_image['url'],
											'post_title' => implode(".", $attachment_title),
											'post_content' => ''											
										);
										if ($image_meta = wp_read_image_metadata($handle_image['file'])) {
											if (trim($image_meta['title']) && ! is_numeric(sanitize_title($image_meta['title'])))
												$attachment['post_title'] = $image_meta['title'];
											if (trim($image_meta['caption']))
												$attachment['post_content'] = $image_meta['caption'];
										}											

										$attid = wp_insert_attachment($attachment, $handle_image['file'], $variation_to_update_id);										

										if (is_wp_error($attid)) {
											$logger and call_user_func($logger, __('- <b>WARNING</b>', 'wp_all_import_plugin') . ': ' . $attid->get_error_message());		
										} else {
											wp_update_attachment_metadata($attid, wp_generate_attachment_metadata($attid, $handle_image['file']));							
										}																				
									}	

									if ($attid)
									{	

										if ($attch != null and empty($attch->post_parent)){
											wp_update_post(
											    array(
											        'ID' => $attch->ID, 
											        'post_parent' => $variation_to_update_id
											    )
											);											
										}

										$success_images = true;												

										$post_thumbnail_id = get_post_thumbnail_id( $variation_to_update_id );
										
										if (empty($post_thumbnail_id) and $this->import->options['is_featured'] ) {
											set_post_thumbnail($variation_to_update_id, $attid);
										}
										elseif(!in_array($attid, $gallery_attachment_ids) and $post_thumbnail_id != $attid){
											$gallery_attachment_ids[] = (int) $attid;
										}

                                        do_action( 'pmxi_gallery_image', $variation_to_update_id, $attid, ($handle_image) ? $handle_image['file'] : $image_filepath);

										if ($attch != null and empty($attch->post_parent))
										{
											$logger and call_user_func($logger, sprintf(__('- Attachment has been successfully updated for image `%s`', 'wp_all_import_plugin'), ($handle_image) ? $handle_image['url'] : $targetUrl . '/' . $image_filename));
										}																										
										elseif(empty($attch))
										{
											$logger and call_user_func($logger, sprintf(__('- Attachment has been successfully created for image `%s`', 'wp_all_import_plugin'), ($handle_image) ? $handle_image['url'] : $targetUrl . '/' . $image_filename));
										}
									}																									
								}							
							}						
						}						

					    if (!empty($this->import->options['import_additional_variation_images'])){
                            update_post_meta( $variation_to_update_id, '_wc_additional_variation_images', implode(",", $gallery_attachment_ids) );
                        }
					}

					wc_delete_product_transients( $variation_to_update_id );	
				}

				foreach ($tmp_files as $file) { // remove all temporary files created
					if (file_exists($file)) @unlink($file);
				}

				// Update parent if variable so price sorting works and stays in sync with the cheapest child				

				$children = get_posts( array(
					'post_parent' 	=> $pid,
					'posts_per_page'=> -1,
					'post_type' 	=> 'product_variation',
					'fields' 		=> 'ids',
					'post_status'	=> array('draft', 'publish', 'trash', 'pending', 'future', 'private')
				) );

				$lowest_price = $lowest_regular_price = $lowest_sale_price = $highest_price = $highest_regular_price = $highest_sale_price = '';

				$total_instock = 0;

				if ( $children ) {
					foreach ( $children as $child ) {

						$_variation_stock = get_post_meta($child, '_stock_status', true);

						$total_instock += ($_variation_stock == 'instock') ? 1 : 0;

						$child_price 			= get_post_meta( $child, '_price', true );
						$child_regular_price 	= get_post_meta( $child, '_regular_price', true );
						$child_sale_price 		= get_post_meta( $child, '_sale_price', true );

						// Regular prices
						if ( ! is_numeric( $lowest_regular_price ) || $child_regular_price < $lowest_regular_price )
							$lowest_regular_price = $child_regular_price;

						if ( ! is_numeric( $highest_regular_price ) || $child_regular_price > $highest_regular_price )
							$highest_regular_price = $child_regular_price;

						// Sale prices
						if ( $child_price == $child_sale_price ) {
							if ( $child_sale_price !== '' && ( ! is_numeric( $lowest_sale_price ) || $child_sale_price < $lowest_sale_price ) )
								$lowest_sale_price = $child_sale_price;

							if ( $child_sale_price !== '' && ( ! is_numeric( $highest_sale_price ) || $child_sale_price > $highest_sale_price ) )
								$highest_sale_price = $child_sale_price;
						}
					}

                    $lowest_price 	= $lowest_sale_price === '' || $lowest_regular_price < $lowest_sale_price ? $lowest_regular_price : $lowest_sale_price;
					$highest_price 	= $highest_sale_price === '' || $highest_regular_price > $highest_sale_price ? $highest_regular_price : $highest_sale_price;

                    $new_parent_stock_status =  ($total_instock > 0) ? 'instock' : 'outofstock';

                    update_post_meta($pid, '_stock_status', $new_parent_stock_status);

                    $this->update_stock_status($pid, $new_parent_stock_status);

					$this->pushmeta($pid, '_price', $lowest_price);		
					
					update_post_meta( $pid, '_min_variation_price', $lowest_price );
					update_post_meta( $pid, '_max_variation_price', $highest_price );
					update_post_meta( $pid, '_min_variation_regular_price', $lowest_regular_price );
					update_post_meta( $pid, '_max_variation_regular_price', $highest_regular_price );
					update_post_meta( $pid, '_min_variation_sale_price', $lowest_sale_price );
					update_post_meta( $pid, '_max_variation_sale_price', $highest_sale_price );

					// Update default attribute options setting
					if ( $this->import->options['update_all_data'] == 'yes' or ($this->import->options['update_all_data'] == 'no' and $this->import->options['is_update_attributes']) or $variation_just_created ){
						
						$default_attributes = array();
						$parent_attributes  = array();
						$attribute_position = 0;
						$is_update_attributes = true;

						foreach ($variation_serialized_attributes as $a_name => $attr_data) {

							$attr_name = $a_name;							
							
							$values = array();

							// Update only these Attributes, leave the rest alone
							if ($this->import->options['update_all_data'] == 'no' and $this->import->options['is_update_attributes'] and $this->import->options['update_attributes_logic'] == 'only'){
								if ( ! empty($this->import->options['attributes_list']) and is_array($this->import->options['attributes_list'])){
									if ( ! in_array( (( intval($attr_data['in_taxonomy'][$j]) ) ? wc_attribute_taxonomy_name( $attr_name ) : $attr_name), array_filter($this->import->options['attributes_list'], 'trim'))){ 
										$attribute_position++;		
										continue;
									}
								}
								else {
									$is_update_attributes = false;
									break;
								}
							}

							// Leave these attributes alone, update all other Attributes
							if ($this->import->options['update_all_data'] == 'no' and $this->import->options['is_update_attributes'] and $this->import->options['update_attributes_logic'] == 'all_except'){
								if ( ! empty($this->import->options['attributes_list']) and is_array($this->import->options['attributes_list'])) {
									if ( in_array( (( intval($attr_data['in_taxonomy'][$j]) ) ? wc_attribute_taxonomy_name( $attr_name ) : $attr_name) , array_filter($this->import->options['attributes_list'], 'trim'))){ 
										$attribute_position++;
										continue;
									}
								}
							}

							foreach ($variation_sku as $j => $void) {							

								$is_variation 	= ( intval($attr_data['in_variation'][$j]) ) ? 1 : 0;								

								$value = esc_attr(trim( $attr_data['value'][$j] ));

								if ( ! in_array($value, $values, true))  $values[] = $value;

								if ($is_variation){									

									if ( ! empty($value) and empty($default_attributes[ (( intval($attr_data['in_taxonomy'][$j])) ? wc_attribute_taxonomy_name( $attr_name ) : sanitize_title($attr_name)) ])){

										switch ($this->import->options['default_attributes_type']) {
											case 'instock':												
												if ($variable_stock_status[$j] == 'instock'){
													$default_attributes[ (( intval($attr_data['in_taxonomy'][$j]) ) ? wc_attribute_taxonomy_name( $attr_name ) : sanitize_title($attr_name)) ] = sanitize_title($value);
												}
												break;
											case 'first':
												$default_attributes[ (( intval($attr_data['in_taxonomy'][$j]) ) ? wc_attribute_taxonomy_name( $attr_name ) : sanitize_title($attr_name)) ] = sanitize_title($value);
												break;
											
											default:
												# code...
												break;
										}
									}
								}
							}												

							if ( intval($attr_data['in_taxonomy'][0]) ){						

								if (intval($attr_data['is_create_taxonomy_terms'][0])){
                                    $attr_name = $this->create_taxonomy($attr_name, $logger);
                                }

								if ( isset($values) and taxonomy_exists( wc_attribute_taxonomy_name( $attr_name ) ) ) {				 							 		

								 	// Remove empty items in the array
								 	$values = array_filter( $values, array($this, "filtering") );

								 	if ( ! empty($values) ){

								 		$attr_values = array();

								 		foreach ($values as $key => $value) {

									 		$term = get_term_by('name', $value, wc_attribute_taxonomy_name( $attr_name ), ARRAY_A);
									 		// For compatibility with WPML plugin
						 					$term = apply_filters('wp_all_import_term_exists', $term, wc_attribute_taxonomy_name( $attr_name ), $value, null);

									 		if ( empty($term) and !is_wp_error($term) ){		

									 			$term = is_exists_term($value, wc_attribute_taxonomy_name( $attr_name ));							 			

									 			if ( empty($term) and !is_wp_error($term) ){																																
													$term = is_exists_term(htmlspecialchars($value), wc_attribute_taxonomy_name( $attr_name ));	
													if ( empty($term) and !is_wp_error($term) and intval($attr_data['is_create_taxonomy_terms'][0])){		
														
														$term = wp_insert_term(
															$value, // the term 
														  	wc_attribute_taxonomy_name( $attr_name ) // the taxonomy										  	
														);													
													}
												}
											}
											if ( ! is_wp_error($term) )													
											{
												$attr_values[] = (int) $term['term_taxonomy_id'];
											}
										}										 									 		

								 		$values = $attr_values;
								 	}

							 	} else {
							 		$values = array();
							 	}					 						 	
						 		// Update post terms
						 		if ( taxonomy_exists( wc_attribute_taxonomy_name( $attr_name ) )){
                                    $this->associate_terms( $pid, $values, wc_attribute_taxonomy_name( $attr_name ) );
                                }

						 		if ( $values ) {
							 		// Add attribute to array, but don't set values
							 		$parent_attributes[ sanitize_title(wc_attribute_taxonomy_name( $attr_name )) ] = array(
								 		'name' 			=> wc_attribute_taxonomy_name( $attr_name ),
								 		'value' 		=> '',
								 		'position' 		=> $attribute_position,
								 		'is_visible' 	=> (!empty($attr_data['is_visible'][0])) ? 1 : 0,
								 		'is_variation' 	=> (!empty($attr_data['in_variation'][0])) ? 1 : 0,
								 		'is_taxonomy' 	=> 1,
								 		'is_create_taxonomy_terms' => (!empty( $attr_data['is_create_taxonomy_terms'][0] )) ? 1 : 0
								 	);
							 	}

							}
							else{

								if ( taxonomy_exists( wc_attribute_taxonomy_name( $attr_name ) )){
                                  $this->associate_terms( $pid, NULL, wc_attribute_taxonomy_name( $attr_name ) );
                                }

                                // Remove empty items in the array
                                $values = array_filter( $values, array($this, "filtering") );

                                if ( $values ){
                                  $parent_attributes[ sanitize_title( $attr_name ) ] = array(
                                    'name' 			=> sanitize_text_field( $attr_name ),
                                    'value' 		=> implode('|', $values),
                                    'position' 		=> $attribute_position,
                                    'is_visible' 	=> (!empty($attr_data['is_visible'][0])) ? 1 : 0,
                                    'is_variation' 	=> (!empty($attr_data['in_variation'][0])) ? 1 : 0,
                                    'is_taxonomy' 	=> 0
                                  );
                                }
							}

						 	$attribute_position++;	
							
						}			

						if ($this->import->options['is_default_attributes'] and $is_update_attributes) {

							$current_default_attributes = get_post_meta($pid, '_default_attributes', true);		

							update_post_meta( $pid, '_default_attributes', (( ! empty($current_default_attributes)) ? array_merge($current_default_attributes, $default_attributes) : $default_attributes) );

						}
				
						if ($is_new_product or $is_update_attributes) {
							
							$current_product_attributes = get_post_meta($pid, '_product_attributes', true);						

							update_post_meta( $pid, '_product_attributes', (( ! empty($current_product_attributes)) ? array_merge($current_product_attributes, $parent_attributes) : $parent_attributes) );	

						}
					}
				}
				elseif ( $this->import->options['make_simple_product']){
					$this->make_simple_product($pid);					
				}								
			}	
			elseif ( $this->import->options['make_simple_product']){
				$this->make_simple_product($pid);				
			}
		}
	}

    function import_stock_data($pid, $product_type, $stock_data_args){

        if ( 'yes' === get_option( 'woocommerce_manage_stock' ) ) {
            $manage_stock = 'no';
            $backorders   = 'no';
            $stock_status = wc_clean( $stock_data_args['stock_status'] );

            if ( 'external' === $product_type ) {

                $stock_status = 'instock';

            } elseif ( 'variable' === $product_type and ! $this->import->options['link_all_variations'] ) {

                // Stock status is always determined by children so sync later
                if ( $stock_data_args['manage_stock'] == 'yes' ) {
                    $manage_stock = 'yes';
                    $backorders   = wc_clean( $stock_data_args['allow_backorders'] );
                }

            } elseif ( 'grouped' !== $product_type && $stock_data_args['manage_stock'] == 'yes' ) {
                $manage_stock = 'yes';
                $backorders   = wc_clean( $stock_data_args['allow_backorders'] );
            }

            $this->pushmeta($pid, '_manage_stock', $manage_stock);

            $current_manage_stock = get_post_meta( $pid, '_manage_stock', true );

            if ($current_manage_stock == 'yes' && ! in_array($product_type, array('external', 'grouped')) ){
                $backorders   = wc_clean( $stock_data_args['allow_backorders'] );
            }

            $this->pushmeta($pid, '_backorders', $backorders);

            // Set Stock Status to instock if backorders are enabled #24
            $_backorders = get_post_meta( $pid, '_backorders', true );
            if ( in_array($_backorders, array('notify', 'yes')) ) {
                $stock_status = 'instock';
            }

            if ( $this->is_update_cf('_stock') && $stock_status ) {
                update_post_meta( $pid, '_stock_status', $stock_status );
            }

            if ( $stock_data_args['manage_stock'] == 'yes' || ! $this->is_update_cf('_manage_stock') && $current_manage_stock == 'yes') {
                $this->pushmeta( $pid, '_stock', wc_stock_amount( $stock_data_args['qty'] ) );
            } else {
                $this->pushmeta($pid, '_stock', '');
            }

        } else {
            update_post_meta( $pid, '_stock_status', wc_clean( $stock_data_args['stock_status'] ) );
        }
    }

    function update_stock_status( $pid, $status = '' ){
        // get current visibility terms for current product
        $term_ids = wp_get_object_terms($pid, 'product_visibility', array('fields' => 'ids'));
        // get outofstock term
        $outofstock_term = get_term_by( 'name', 'outofstock', 'product_visibility' );

        if (!empty($outofstock_term) && !is_wp_error($outofstock_term)){
            $outofstock_term_id = $outofstock_term->term_taxonomy_id;
            if (!in_array($outofstock_term_id, $term_ids) && 'outofstock' == $status){
                $term_ids[] = $outofstock_term_id;
            }
            if (in_array($outofstock_term_id, $term_ids) && 'outofstock' != $status){
                foreach ($term_ids as $key => $term_id){
                    if ($term_id == $outofstock_term_id){
                        unset($term_ids[$key]);
                    }
                }
            }
        }
        $this->associate_terms( $pid, $term_ids, 'product_visibility' );
    }

    protected function generate_product_title( $product ) {
        $attributes = (array) $product->get_attributes();

        // Don't include attributes if the product has 3+ attributes.
        $should_include_attributes = count( $attributes ) < 3;

        // Don't include attributes if an attribute name has 2+ words.
        if ( $should_include_attributes ) {
            foreach ( $attributes as $name => $value ) {
                if ( false !== strpos( $name, '-' ) ) {
                    $should_include_attributes = false;
                    break;
                }
            }
        }

        $should_include_attributes = apply_filters( 'woocommerce_product_variation_title_include_attributes', $should_include_attributes, $product );
        $separator                 = apply_filters( 'woocommerce_product_variation_title_attributes_separator', ' - ', $product );
        $title_base                = get_post_field( 'post_title', $product->get_parent_id() );
        $title_suffix              = $should_include_attributes ? wc_get_formatted_variation( $product, true, false ) : '';

        return apply_filters( 'woocommerce_product_variation_title', rtrim( $title_base . $separator . $title_suffix, $separator ), $product, $title_base, $title_suffix );
    }

	public function after_save_post( $importData )
	{

		$table = $this->wpdb->posts;

		$p = $this->wpdb->get_row($this->wpdb->prepare("SELECT * FROM $table WHERE ID = %d;", $importData['pid']));

		if ($p)
		{
			$post_to_update_id = false;

			if ($p->post_type == 'product_variation')
			{
				if ($this->import->options['create_draft'] == 'yes' and $p->post_status == 'draft')
				{
					$this->wpdb->update( $this->wpdb->posts, array('post_status' => 'publish' ), array('ID' => $importData['pid']));
				}

                $this->wpdb->update( $this->wpdb->posts, array( 'post_excerpt' => '', 'post_name' => sanitize_title($p->post_title), 'guid' => '' ), array('ID' => $importData['pid']));
                $this->product_taxonomies = apply_filters('wp_all_import_variation_taxonomies', $this->product_taxonomies);
                if ( ! empty($this->product_taxonomies) ):
                  foreach ($this->product_taxonomies as $ctx):
                    if ( strpos($ctx->name, "pa_") === 0 ) continue;
                    $this->associate_terms($importData['pid'], NULL, $ctx->name);
                  endforeach;
                endif;

                $post_to_update_id = $p->post_parent;

                // remove temporary custom fields
                delete_post_meta($importData['pid'], '_v_product_manage_stock');
				delete_post_meta($importData['pid'], '_v_stock');
				delete_post_meta($importData['pid'], '_v_stock_status');
				delete_post_meta($importData['pid'], '_v_variation_enabled');
				delete_post_meta($importData['pid'], '_first_variation_attributes');
				delete_post_meta($importData['pid'], '_v_shipping_class');

			}
			else
			{
				// unset attributes
                $product_attributes = get_post_meta( $importData['pid'], '_product_attributes', true );
                if ( ! empty($this->product_taxonomies) ):
                    foreach ($this->product_taxonomies as $ctx):
                        if ( strpos($ctx->name, "pa_") === 0 && ! isset($product_attributes[strtolower(urlencode($ctx->name))]) ){
                            $this->associate_terms($importData['pid'], NULL, $ctx->name);
                        }
                    endforeach;
                endif;

			    update_post_meta( $importData['pid'], '_product_version', WC_VERSION );
				$post_to_update_id = $importData['pid'];

				// [associate linked products]
				$wp_all_import_not_linked_products = get_option('wp_all_import_not_linked_products_' . $this->import->id );

				if ( ! empty($wp_all_import_not_linked_products) )
				{
					$post_to_update_sku = get_post_meta($post_to_update_id, '_sku', true);

					foreach ($wp_all_import_not_linked_products as $product)
					{
						if ( $product['pid'] != $post_to_update_id && ! empty($product['not_linked_products']) )
						{
							if ( in_array($post_to_update_sku, $product['not_linked_products'])
									or in_array( (string) $post_to_update_id, $product['not_linked_products'])
										or in_array($p->post_title, $product['not_linked_products'])
											or in_array($p->post_name, $product['not_linked_products'])
											)
							{
								$linked_products = get_post_meta($product['pid'], $product['type'], true);

								if (empty($linked_products)) $linked_products = array();

								if ( ! in_array($post_to_update_id, $linked_products))
								{
									$linked_products[] = $post_to_update_id;

									$this->logger and call_user_func($this->logger, sprintf(__('Added to %s list of product ID %d.', 'wpai_woocommerce_addon_plugin'), $product['type'] == '_upsell_ids' ? 'Up-Sells' : 'Cross-Sells', $product['pid']) );

									update_post_meta($product['pid'], $product['type'], $linked_products);

								}
							}
						}
					}
				}
				// [\associate linked products]
			}

			// update first variation
            if ($post_to_update_id){

                $postRecord = new PMXI_Post_Record();

                $postRecord->clear();

                $parent_sku = get_post_meta($post_to_update_id, '_parent_sku', true);

                if (empty($parent_sku)) {
                    $parent_sku = get_post_meta($post_to_update_id, '_sku', TRUE);
                }

                // find corresponding article among previously imported
                $postRecord->getBy(array(
                    'unique_key' => 'Variation ' . $parent_sku,
                    'import_id'  => $this->import->id,
                ));

                $pid = ( ! $postRecord->isEmpty() ) ? $postRecord->post_id : false;

                if ( $pid )
                {
                    $table = _get_meta_table('post');

                    $post_meta_infos = $this->wpdb->get_results($this->wpdb->prepare("SELECT meta_key, meta_value FROM ". $table ." WHERE post_id = %d", $post_to_update_id));

                    if ( ! empty($post_meta_infos) and ! empty($this->import->options['custom_name']) )
                    {
                        foreach ($post_meta_infos as $meta_info) {

                            if ( in_array($meta_info->meta_key, $this->import->options['custom_name']) )
                            {
                                $this->pushmeta($pid, $meta_info->meta_key, maybe_unserialize($meta_info->meta_value));
                            }
                        }
                    }

                    // check is variation already processed
                    $is_variation_updated = get_post_meta($pid, '_variation_updated', true);

                    if ( empty($is_variation_updated) ){

                        // save thumbnail
                        if (empty($this->articleData['ID']) || $this->import->options['is_update_images'] && $this->import->options['update_images_logic'] == 'full_update'){

                            $post_thumbnail_id = get_post_thumbnail_id( $post_to_update_id );

                            if ($post_thumbnail_id)
                            {
                                set_post_thumbnail($pid, $post_thumbnail_id);
                            }

                            if ($this->import->options['put_variation_image_to_gallery'] and $post_thumbnail_id)
                            {
                                do_action('pmxi_gallery_image', $pid, $post_thumbnail_id, false);
                            }
                        }

                        if ($this->import->options['create_draft'] == 'yes')
                        {
                            $this->wpdb->update( $this->wpdb->posts, array('post_status' => 'publish' ), array('ID' => $pid));
                        }

                        update_post_meta($pid, '_variation_updated', 1);

                        wc_delete_product_transients($pid);
                    }
                }
            }

			// [update product gallery]
			$tmp_gallery = explode(",", get_post_meta( $post_to_update_id, '_product_image_gallery_tmp', true));
			$gallery     = explode(",", get_post_meta( $post_to_update_id, '_product_image_gallery', true));
			if (is_array($gallery)){
				$gallery = array_filter($gallery);
				if ( ! empty($tmp_gallery))
				{
					$gallery = array_unique(array_merge($gallery, $tmp_gallery));
				}
			}
			elseif ( ! empty($tmp_gallery))
			{
				$gallery = $tmp_gallery;
			}
            $this->pushmeta( $post_to_update_id, '_product_image_gallery', implode(",", $gallery) );
			// [\update product gallery]

			wc_delete_product_transients($importData['pid']);
		}

	}

	protected function associate_terms($pid, $assign_taxes, $tx_name, $logger = false){

        $terms = wp_get_object_terms( $pid, $tx_name );
        $term_ids = array();

        $assign_taxes = (is_array($assign_taxes)) ? array_filter($assign_taxes) : false;

        if ( ! empty($terms) ){
            if ( ! is_wp_error( $terms ) ) {
                foreach ($terms as $term_info) {
                    $term_ids[] = $term_info->term_taxonomy_id;
                    $this->wpdb->query(  $this->wpdb->prepare("UPDATE {$this->wpdb->term_taxonomy} SET count = count - 1 WHERE term_taxonomy_id = %d", $term_info->term_taxonomy_id) );
                }
                $in_tt_ids = "'" . implode( "', '", $term_ids ) . "'";
                $this->wpdb->query( $this->wpdb->prepare( "DELETE FROM {$this->wpdb->term_relationships} WHERE object_id = %d AND term_taxonomy_id IN ($in_tt_ids)", $pid ) );
            }
        }

        if (empty($assign_taxes)) return;

        // foreach ($assign_taxes as $tt) {
        // 	$this->wpdb->insert( $this->wpdb->term_relationships, array( 'object_id' => $pid, 'term_taxonomy_id' => $tt ) );
        // 	$this->wpdb->query( "UPDATE {$this->wpdb->term_taxonomy} SET count = count + 1 WHERE term_taxonomy_id = $tt" );
        // }

        $values = array();
        $term_order = 0;
        foreach ( $assign_taxes as $tt )
        {
            do_action('wp_all_import_associate_term', $pid, $tt, $tx_name);
            $values[] = $this->wpdb->prepare( "(%d, %d, %d)", $pid, $tt, ++$term_order);
            $this->wpdb->query( "UPDATE {$this->wpdb->term_taxonomy} SET count = count + 1 WHERE term_taxonomy_id = $tt" );
        }


        if ( $values ){
            if ( false === $this->wpdb->query( "INSERT INTO {$this->wpdb->term_relationships} (object_id, term_taxonomy_id, term_order) VALUES " . join( ',', $values ) . " ON DUPLICATE KEY UPDATE term_order = VALUES(term_order)" ) ){
                $logger and call_user_func($logger, __('<b>ERROR</b> Could not insert term relationship into the database', 'wp_all_import_plugin') . ': '. $this->wpdb->last_error);
            }
        }

        wp_cache_delete( $pid, $tx_name . '_relationships' );

    }

	protected function duplicate_post_meta( $new_id, $id ) {

		$table = _get_meta_table('post');
		
		$post_meta_infos = $this->wpdb->get_results($this->wpdb->prepare("SELECT meta_key, meta_value FROM ". $table ." WHERE post_id = %d", $id));

		if (count($post_meta_infos)!=0) {
			$sql_query_sel = array();
			$sql_query = "INSERT INTO $table (post_id, meta_key, meta_value) ";
			foreach ($post_meta_infos as $meta_info) {															
				if (strpos($meta_info->meta_key, '_min') === false and strpos($meta_info->meta_key, '_max') === false and ! in_array($meta_info->meta_key, array('_default_attributes', '_price', '_first_variation_sku', '_product_version')))
				{
                    $cf_value = apply_filters('pmxi_custom_field', maybe_unserialize($meta_info->meta_value), $new_id, $meta_info->meta_key, $post_meta_infos, $this->import->id);
					$this->pushmeta($new_id, $meta_info->meta_key, $cf_value);
				}
			}
			$_first_variation_sku = get_post_meta($id, '_first_variation_sku', true);
            if (!empty($_first_variation_sku)){
                $this->pushmeta($new_id, '_sku', $_first_variation_sku);
                delete_post_meta($id, '_first_variation_sku');
            }
            delete_post_meta($new_id, '_product_version');
            delete_post_meta($new_id, '_variation_updated');

			if (empty($this->articleData['ID']) or $this->is_update_cf('_price'))
			{
				$sale_price    = get_post_meta($id, '_sale_price', true);
				$regular_price = get_post_meta($id, '_regular_price', true);
				if ($sale_price != '' && $regular_price != '')
				{
					$price = ($sale_price <= $regular_price) ? $sale_price : $regular_price;
				}
				elseif ($sale_price != '')
				{
					$price = $sale_price;
				}
				else
				{
					$price = $regular_price;
				}
				
				$this->pushmeta($new_id, '_price', $price);
			}			
		}

		if ($this->import->options['put_variation_image_to_gallery'])
		{
			$post_thumbnail_id = get_post_thumbnail_id( $id );

			do_action('pmxi_gallery_image', $new_id, $post_thumbnail_id, false);			
		}

	}	

	function pmwi_link_all_variations($product_id, $options = array(), $import_id, $iteration = 0) {

		global $woocommerce;

		@set_time_limit(0);

		$post_id = intval( $product_id );

		if ( ! $post_id ) return 0;

		$variations = array();

		$_product = WC()->product_factory->get_product($post_id);

		$v = $_product->get_attributes();		

		// Put variation attributes into an array
        wp_cache_flush();

        /** @var WC_Product_Attribute $attribute */
        foreach ( $_product->get_attributes() as $attribute ) {

            if ( $attribute instanceof WC_Product_Attribute){
                $attribute = array(
                    'name' => $attribute->get_name(),
                    'is_taxonomy' => $attribute->is_taxonomy(),
                    'is_variation' => $attribute->get_variation(),
                    'value' => wc_implode_text_attributes( $attribute->get_options() )
                );
            }

            if ( ! $attribute['is_variation'] ) {
                continue;
            }

            $attribute_field_name = 'attribute_' . sanitize_title( $attribute['name'] );

            if ( $attribute['is_taxonomy'] ) {
                $options = array();
                $options_arr = wc_get_product_terms( $post_id, $attribute['name'], array( 'orderby' => 'parent' ) );
                if (!empty($options_arr)){
                    foreach ($options_arr as $key => $value) {
                        if (!in_array($value->slug, $options)) $options[] = $value->slug;
                    }
                }
            } else {
                $options = explode( '|', $attribute['value'] );
            }

			$options = array_map( 'trim', $options );

			$variations[ $attribute_field_name ] = $options;
		}

		// Quit out if none were found
		if ( sizeof( $variations ) == 0 ) {

            foreach( $_product->get_children() as $child_id ) {
                $child = wc_get_product( $child_id );

                if ( $this->import->options['is_delete_missing'] && $this->import->options['is_update_missing_cf'] || $this->import->options['missing_records_stock_status']){
                    $child_attr = $child->get_attributes();
                    if ($child_attr){
                        foreach ($child_attr as $attr_name => $attr_value){
                            if (isset($v[$attr_name])){
                                $term = get_term_by('slug', $attr_value, $attr_name, ARRAY_A);
                                if ($term){
                                    $attr_options = $v[$attr_name]->get_options();
                                    if (!in_array($term['term_taxonomy_id'], $attr_options)){
                                        $attr_options[] = (int) $term['term_taxonomy_id'];
                                    }
                                    $v[$attr_name]->set_options($attr_options);
                                }
                            }
                        }
                    }
                    if ($this->import->options['is_update_missing_cf']){
                        update_post_meta( $child_id, $this->import->options['update_missing_cf_name'], $this->import->options['update_missing_cf_value'] );
                        $this->logger and call_user_func($this->logger, sprintf(__('Instead of deletion post with ID `%s`, set Custom Field `%s` to value `%s`', 'wp_all_import_plugin'), $child_id, $this->import->options['update_missing_cf_name'], $this->import->options['update_missing_cf_value']));
                    }
                    if ($this->import->options['missing_records_stock_status']){
                        update_post_meta( $child_id, '_stock_status', 'outofstock' );
                        update_post_meta( $child_id, '_stock', 0 );
                        $this->logger and call_user_func($this->logger, sprintf(__('Updating stock status for missing record ID `%s`', 'wp_all_import_plugin'), $child_id));
                    }
                }
                else{
                    $child->delete(true);
                }
            }

            if ( $this->import->options['make_simple_product']) {
                $this->make_simple_product($post_id);
                wc_delete_product_transients($post_id);
                do_action('wp_all_import_variable_product_imported', $post_id);
            }

		    return 0;
        }

        $possible_variations = $this->array_cartesian( $variations );

		// Get existing variations so we don't create duplicates
        $available_variations = array();

        foreach( $_product->get_children() as $child_id ) {

            $child = wc_get_product( $child_id );

            if ( ! empty( $child->variation_id ) ) {

                $postRecord = new PMXI_Post_Record();
                $postRecord->getBy(array(
                  'post_id' => $child->variation_id,
                  'import_id' => $import_id,
                  'unique_key' => 'Variation ' . $child->variation_id . ' of ' . $post_id,
                ));
                if ( ! $postRecord->isEmpty() ){
                    $postRecord->set(array('iteration' => $iteration))->update();
                }

                $variation_attributes = $child->get_variation_attributes();

                foreach ($variation_attributes as $key => $value) {
                    $variation_attributes[$key] = sanitize_title($value);
                }

                update_post_meta( $child->variation_id, '_regular_price', get_post_meta( $post_id, '_regular_price', true ) );
                update_post_meta( $child->variation_id, '_sale_price', get_post_meta( $post_id, '_sale_price', true ) );
                if ( class_exists('woocommerce_wholesale_pricing') ) update_post_meta( $child->variation_id, 'pmxi_wholesale_price', get_post_meta( $post_id, 'pmxi_wholesale_price', true ) );
                update_post_meta( $child->variation_id, '_sale_price_dates_from', get_post_meta( $post_id, '_sale_price_dates_from', true ) );
                update_post_meta( $child->variation_id, '_sale_price_dates_to', get_post_meta( $post_id, '_sale_price_dates_to', true ) );
                update_post_meta( $child->variation_id, '_price', get_post_meta( $post_id, '_price', true ) );
                update_post_meta( $child->variation_id, '_stock', get_post_meta( $post_id, '_stock', true ) );
                update_post_meta( $child->variation_id, '_stock_status', get_post_meta( $post_id, '_stock_status', true ) );
                update_post_meta( $child->variation_id, '_manage_stock', get_post_meta( $post_id, '_manage_stock', true ) );
                update_post_meta( $child->variation_id, '_backorders', get_post_meta( $post_id, '_backorders', true ) );

                if ( ! in_array($variation_attributes, $possible_variations)){
                    if ( $this->import->options['is_delete_missing'] && $this->import->options['is_update_missing_cf'] || $this->import->options['missing_records_stock_status']){
                        $child_attr = $child->get_attributes();
                        if ($child_attr){
                            foreach ($child_attr as $attr_name => $attr_value){
                                if (isset($v[$attr_name])){
                                    $term = get_term_by('slug', $attr_value, $attr_name, ARRAY_A);
                                    if ($term){
                                        $attr_options = $v[$attr_name]->get_options();
                                        if (!in_array($term['term_taxonomy_id'], $attr_options)){
                                            $attr_options[] = (int) $term['term_taxonomy_id'];
                                        }
                                        $v[$attr_name]->set_options($attr_options);
                                    }
                                }
                            }
                        }
                        if ($this->import->options['is_update_missing_cf']){
                            update_post_meta( $child->variation_id, $this->import->options['update_missing_cf_name'], $this->import->options['update_missing_cf_value'] );
                            $this->logger and call_user_func($this->logger, sprintf(__('Instead of deletion post with ID `%s`, set Custom Field `%s` to value `%s`', 'wp_all_import_plugin'), $child->variation_id, $this->import->options['update_missing_cf_name'], $this->import->options['update_missing_cf_value']));
                        }
                        if ($this->import->options['missing_records_stock_status']){
                            update_post_meta( $child->variation_id, '_stock_status', 'outofstock' );
                            update_post_meta( $child->variation_id, '_stock', 0 );
                            $this->logger and call_user_func($this->logger, sprintf(__('Updating stock status for missing record ID `%s`', 'wp_all_import_plugin'), $child->variation_id));
                        }
                    }
                    else{
                        $child->delete(true);
                        continue;
                    }
                }

                $available_variations[] = $variation_attributes;

                do_action( 'pmxi_product_variation_saved', $child->variation_id );
            }
        }

		// Created posts will all have the following data
		$variation_post_data = array(
			'post_title' => 'Product #' . $post_id . ' Variation',
			'post_content' => '',
			'post_status' => 'publish',
			'post_author' => get_current_user_id(),
			'post_parent' => $post_id,
			'post_type' => 'product_variation'
		);

		$variation_ids = array();
		$added = 0;

		foreach ( $possible_variations as $variation ) {

			// Check if variation already exists
			if ( in_array( $variation, $available_variations ) )
				continue;

			$variation_id = wp_insert_post( $variation_post_data );		

			$postRecord = new PMXI_Post_Record();
			$postRecord->isEmpty() and $postRecord->set(array(
				'post_id' => $variation_id,
				'import_id' => $import_id,
				'unique_key' => 'Variation ' . $variation_id . ' of ' . $post_id,
				'product_key' => '',
				'iteration' => $iteration
			))->insert();			
			
			update_post_meta( $variation_id, '_regular_price', get_post_meta( $post_id, '_regular_price', true ) );
			update_post_meta( $variation_id, '_sale_price', get_post_meta( $post_id, '_sale_price', true ) );
			if ( class_exists('woocommerce_wholesale_pricing') ) update_post_meta( $variation_id, 'pmxi_wholesale_price', get_post_meta( $post_id, 'pmxi_wholesale_price', true ) );
			update_post_meta( $variation_id, '_sale_price_dates_from', get_post_meta( $post_id, '_sale_price_dates_from', true ) );
			update_post_meta( $variation_id, '_sale_price_dates_to', get_post_meta( $post_id, '_sale_price_dates_to', true ) );
			update_post_meta( $variation_id, '_price', get_post_meta( $post_id, '_price', true ) );
			update_post_meta( $variation_id, '_stock', get_post_meta( $post_id, '_stock', true ) );
			update_post_meta( $variation_id, '_stock_status', get_post_meta( $post_id, '_stock_status', true ) );			
			update_post_meta( $variation_id, '_manage_stock', get_post_meta( $post_id, '_manage_stock', true ) );			
			update_post_meta( $variation_id, '_backorders', get_post_meta( $post_id, '_backorders', true ) );			
			

			$variation_ids[] = $variation_id;

			foreach ( $variation as $key => $value ) {
				update_post_meta( $variation_id, $key, $value );
			}

			$added++;

			do_action( 'pmxi_product_variation_saved', $variation_id );
			
		}		

		$children = get_posts( array(
			'post_parent' 	=> $post_id,
			'posts_per_page'=> -1,
			'post_type' 	=> 'product_variation',
			'fields' 		=> 'ids',
			'orderby'		=> 'ID',
			'order'			=> 'ASC',
			'post_status'	=> array('draft', 'publish', 'trash', 'pending', 'future', 'private')
		) );						

//        $children_attributes = array();
//        if (count($children)){
//            foreach ($children as $child){
//                $child_attr = $child->get_attributes();
//                if ($child_attr){
//                    foreach ($child_attr as $attr_name => $attr_value){
//                        $term = get_term_by('slug', $attr_value, $attr_name, ARRAY_A);
//                        if ($term){
//                            $term_id = (int) $term['term_taxonomy_id'];
//                            if (isset($children_attributes[$attr_name]) && !in_array($term_id, $children_attributes[$attr_name])){
//                                $children_attributes[$attr_name][] = $term_id;
//                            }
//                            if (!isset($children_attributes[$attr_name])){
//                                $children_attributes[$attr_name] = array($term_id);
//                            }
//                        }
//                    }
//                }
//            }
//        }

        $product_attributes = array();
		$default_attributes = array();
		foreach ( $v as $attribute ) {

            $product_attributes[ $attribute->get_name() ] = $attribute->get_data();

			$default_attributes[ $attribute->get_name() ] = array();

			$values = array();

			foreach ( $children as $child ) {
				
				$value = array_map( 'stripslashes', array_map( 'strip_tags',  explode("|", trim( get_post_meta($child, 'attribute_'. $attribute->get_name(), true)))));
				
				if ( ! empty($value) ){					
					foreach ($value as $val) {
					 	if ( ! in_array($val, $values, true) )  $values[] = $val;
					} 
				}

				if ( $attribute->get_variation() ) {

					if ( ! empty($values) and empty($default_attributes[ $attribute->get_name() ])){
						switch ($this->import->options['default_attributes_type']) {
							case 'instock':
								$is_instock = get_post_meta($child, '_stock_status', true);
								if ($is_instock == 'instock'){
									$default_attributes[ $attribute->get_name() ] = sanitize_title((is_array($value)) ? $value[0] : $value);
								}
								break;
							case 'first':
								$default_attributes[ $attribute->get_name() ] = sanitize_title((is_array($values)) ? $values[0] : $values);
								break;
							
							default:
								# code...
								break;
						}																		
					}					
				}
			}
            //if (!empty($this->articleData['ID'])) $this->associate_terms( $post_id, $attribute->get_options(), $attribute->get_name() );
		}

        if (!empty($this->articleData['ID'])) $this->pushmeta($post_id, '_product_attributes', $product_attributes);

		if ($this->import->options['is_default_attributes']) $this->pushmeta($post_id, '_default_attributes', $default_attributes);

		wc_delete_product_transients( $post_id );

		return $added;
	}

	function array_cartesian( $input ) {

	    $result = array();

	    while ( list( $key, $values ) = each( $input ) ) {
	        // If a sub-array is empty, it doesn't affect the cartesian product
	        if ( empty( $values ) ) {
	            continue;
	        }

	        // Special case: seeding the product array with the values from the first sub-array
	        if ( empty( $result ) ) {
	            foreach ( $values as $value ) {
	                $result[] = array( $key => $value );
	            }
	        }
	        else {
	            // Second and subsequent input sub-arrays work like this:
	            //   1. In each existing array inside $product, add an item with
	            //      key == $key and value == first item in input sub-array
	            //   2. Then, for each remaining item in current input sub-array,
	            //      add a copy of each existing array inside $product with
	            //      key == $key and value == first item in current input sub-array

	            // Store all items to be added to $product here; adding them on the spot
	            // inside the foreach will result in an infinite loop
	            $append = array();
	            foreach( $result as &$product ) {
	                // Do step 1 above. array_shift is not the most efficient, but it
	                // allows us to iterate over the rest of the items with a simple
	                // foreach, making the code short and familiar.
	                $product[ $key ] = array_shift( $values );

	                // $product is by reference (that's why the key we added above
	                // will appear in the end result), so make a copy of it here
	                $copy = $product;

	                // Do step 2 above.
	                foreach( $values as $item ) {
	                    $copy[ $key ] = $item;
	                    $append[] = $copy;
	                }

	                // Undo the side effecst of array_shift
	                array_unshift( $values, $product[ $key ] );
	            }

	            // Out of the foreach, we can add to $results now
	            $result = array_merge( $result, $append );
	        }
	    }

	    return $result;
	}
	
	function create_taxonomy($attr_name, $logger, $prefix = 1){
		
		global $woocommerce;

        $attr_name_real = $prefix > 1 ? $attr_name . " " . $prefix : $attr_name;

		if ( ! taxonomy_exists( wc_attribute_taxonomy_name( $attr_name_real ) ) ) {

	 		// Grab the submitted data							
			$attribute_name    = ( isset( $attr_name ) ) ? wc_sanitize_taxonomy_name( stripslashes( (string) $attr_name_real ) ) : '';
			$attribute_label   = stripslashes( (string) $attr_name );
			$attribute_type    = 'select';
			$attribute_orderby = 'menu_order';						

			if ( in_array( wc_sanitize_taxonomy_name( stripslashes( (string) $attr_name_real)), $this->reserved_terms ) ) {
                $prefix++;
                return $this->create_taxonomy($attr_name, $logger, $prefix);
				//$logger and call_user_func($logger, sprintf(__('- <b>WARNING</b>: Slug “%s” is not allowed because it is a reserved term. Change it, please.', 'wpai_woocommerce_addon_plugin'), wc_attribute_taxonomy_name( $attribute_name )));
			}			
			else{				

				// Register the taxonomy now so that the import works!
				$domain = wc_attribute_taxonomy_name( $attr_name_real );
				if (strlen($domain) < 31){

					$this->wpdb->insert(
						$this->wpdb->prefix . 'woocommerce_attribute_taxonomies',
                        array(
                            'attribute_label'   => $attribute_label,
                            'attribute_name'    => $attribute_name,
                            'attribute_type'    => $attribute_type,
                            'attribute_orderby' => $attribute_orderby,
                            'attribute_public'  => 1
                        )
					);												

					register_taxonomy( $domain,
				        apply_filters( 'woocommerce_taxonomy_objects_' . $domain, array('product') ),
				        apply_filters( 'woocommerce_taxonomy_args_' . $domain, array(
				            'hierarchical' => true,
				            'show_ui' => false,
				            'query_var' => true,
				            'rewrite' => false,
				        ) )
				    );

					delete_transient( 'wc_attribute_taxonomies' );
					$attribute_taxonomies = $this->wpdb->get_results( "SELECT * FROM " . $this->wpdb->prefix . "woocommerce_attribute_taxonomies" );
					set_transient( 'wc_attribute_taxonomies', $attribute_taxonomies );

					$logger and call_user_func($logger, sprintf(__('- <b>CREATED</b>: Taxonomy attribute “%s” have been successfully created.', 'wpai_woocommerce_addon_plugin'), wc_attribute_taxonomy_name( $attribute_name )));

				}
				else{
					$logger and call_user_func($logger, sprintf(__('- <b>WARNING</b>: Taxonomy “%s” name is more than 28 characters. Change it, please.', 'wpai_woocommerce_addon_plugin'), $attr_name));
				}				
			}
	 	}
	 	else{
            if ( in_array( wc_sanitize_taxonomy_name( stripslashes( (string) $attr_name_real)), $this->reserved_terms ) ) {
                $prefix++;
                return $this->create_taxonomy($attr_name, $logger, $prefix);
                //$logger and call_user_func($logger, sprintf(__('- <b>WARNING</b>: Slug “%s” is not allowed because it is a reserved term. Change it, please.', 'wpai_woocommerce_addon_plugin'), wc_attribute_taxonomy_name( $attribute_name )));
            }
        }

        return $attr_name_real;
	}

	function make_simple_product($post_parent){		
		if ( empty($this->articleData['ID']) or $this->import->options['update_all_data'] == 'yes' or $this->import->options['is_update_product_type'] ){
			$product_type_term = is_exists_term('simple', 'product_type', 0);	
			if ( ! empty($product_type_term) and ! is_wp_error($product_type_term) ){	
				$this->associate_terms( $post_parent, array( (int) $product_type_term['term_taxonomy_id'] ), 'product_type' );	
			}

			$this->pmwi_update_prices( $post_parent );

            $first_variation_sku = get_post_meta($post_parent, '_first_variation_sku', true);
            if (!empty($first_variation_sku)){
                $this->pushmeta($post_parent, '_sku', $first_variation_sku);
            }

            do_action('wp_all_import_make_product_simple', $post_parent, $this->import->id);
		}		
	}

	function pmwi_buf_prices($pid){

		$table = _get_meta_table('post');
		
		$post_meta_infos = $this->wpdb->get_results($this->wpdb->prepare("SELECT meta_key, meta_value FROM ". $table ." WHERE post_id = %d", $pid));

		foreach ($post_meta_infos as $meta_info) {
			if (in_array($meta_info->meta_key, array('_regular_price', '_sale_price', '_sale_price_dates_from', '_sale_price_dates_from', '_sale_price_dates_to', '_price', '_stock', '_stock_status'))){
				update_post_meta($pid, $meta_info->meta_key . '_tmp', $meta_info->meta_value);		
			}
		}		
	}

	function pmwi_update_prices($pid){

		$table = _get_meta_table('post');
		
		$post_meta_infos = $this->wpdb->get_results($this->wpdb->prepare("SELECT meta_key, meta_value FROM ". $table ." WHERE post_id = %d", $pid));

		foreach ($post_meta_infos as $meta_info) {
			if (in_array($meta_info->meta_key, array('_regular_price_tmp', '_sale_price_tmp', '_sale_price_dates_from_tmp', '_sale_price_dates_from_tmp', '_sale_price_dates_to_tmp', '_price_tmp', '_stock_tmp', '_stock_status_tmp'))){
				$this->pushmeta($pid, str_replace('_tmp', '', $meta_info->meta_key), $meta_info->meta_value);
				delete_post_meta( $pid, $meta_info->meta_key );
			}
		}
	}

	function auto_cloak_links($import, &$url){
		
		$url = apply_filters('pmwi_cloak_affiliate_url', trim($url), $this->import->id);
		
		// cloak urls with `WP Wizard Cloak` if corresponding option is set
		if ( ! empty($this->import->options['is_cloak']) and class_exists('PMLC_Plugin')) {														
			if (preg_match('%^\w+://%i', $url)) { // mask only links having protocol
				// try to find matching cloaked link among already registered ones
				$list = new PMLC_Link_List(); $linkTable = $list->getTable();
				$rule = new PMLC_Rule_Record(); $ruleTable = $rule->getTable();
				$dest = new PMLC_Destination_Record(); $destTable = $dest->getTable();
				$list->join($ruleTable, "$ruleTable.link_id = $linkTable.id")
					->join($destTable, "$destTable.rule_id = $ruleTable.id")
					->setColumns("$linkTable.*")
					->getBy(array(
						"$linkTable.destination_type =" => 'ONE_SET',
						"$linkTable.is_trashed =" => 0,
						"$linkTable.preset =" => '',
						"$linkTable.expire_on =" => '0000-00-00',
						"$ruleTable.type =" => 'ONE_SET',
						"$destTable.weight =" => 100,
						"$destTable.url LIKE" => $url,
					), NULL, 1, 1)->convertRecords();
				if ($list->count()) { // matching link found
					$link = $list[0];
				} else { // register new cloaked link
					global $wpdb;
					$slug = max(
						intval($wpdb->get_var("SELECT MAX(CONVERT(name, SIGNED)) FROM $linkTable")),
						intval($wpdb->get_var("SELECT MAX(CONVERT(slug, SIGNED)) FROM $linkTable")),
						0
					);
					$i = 0; do {
						is_int(++$slug) and $slug > 0 or $slug = 1;
						$is_slug_found = ! intval($wpdb->get_var("SELECT COUNT(*) FROM $linkTable WHERE name = '$slug' OR slug = '$slug'"));
					} while( ! $is_slug_found and $i++ < 100000);
					if ($is_slug_found) {
						$link = new PMLC_Link_Record(array(
							'name' => strval($slug),
							'slug' => strval($slug),
							'header_tracking_code' => '',
							'footer_tracking_code' => '',
							'redirect_type' => '301',
							'destination_type' => 'ONE_SET',
							'preset' => '',
							'forward_url_params' => 1,
							'no_global_tracking_code' => 0,
							'expire_on' => '0000-00-00',
							'created_on' => date('Y-m-d H:i:s'),
							'is_trashed' => 0,
						));
						$link->insert();
						$rule = new PMLC_Rule_Record(array(
							'link_id' => $link->id,
							'type' => 'ONE_SET',
							'rule' => '',
						));
						$rule->insert();
						$dest = new PMLC_Destination_Record(array(
							'rule_id' => $rule->id,
							'url' => $url,
							'weight' => 100,
						));
						$dest->insert();
					} else {
						$logger and call_user_func($logger, sprintf(__('- <b>WARNING</b>: Unable to create cloaked link for %s', 'wpai_woocommerce_addon_plugin'), $url));						
						$link = NULL;
					}
				}
				if ($link) { // cloaked link is found or created for url
					$url = preg_replace('%' . preg_quote($url, '%') . '(?=([\s\'"]|$))%i', $link->getUrl(), $url);								
				}									
			}
		}
	}

	function import_linked_products( $pid, $products, $type, $is_new_product )
	{
		if ( ! $is_new_product and ! $this->is_update_cf($type) ) return;

		if ( ! empty( $products ) ) 
		{
			$not_found = array();

			$linked_products = array();
			
			$ids = array_filter(explode(',', $products), 'trim');

			foreach ( $ids as $id )
			{
				// search linked product by _SKU
				$args = array(
					'post_type' => array('product', 'product_variation'),
					'meta_query' => array(
						array(
							'key' => '_sku',
							'value' => $id,						
						)
					)
				);			
				$query = new WP_Query( $args );

				$linked_product = false;
				
				if ( $query->have_posts() ) 
				{
					$linked_product = get_post($query->post->ID);
				}

				wp_reset_postdata();

				if ( ! $linked_product )
				{							
					if (is_numeric($id))
					{
						// search linked product by ID						
						$query = new WP_Query( array( 'post_type' => array('product', 'product_variation'), 'post__in' => array( $id ) ) );
						if ( $query->have_posts() ) 
						{							
							$linked_product = get_post($query->post->ID);
						}						
						wp_reset_postdata();
					}				
					if ( ! $linked_product )
					{
						// search linked product by slug
						$args = array(
						  'name'        => $id,
						  'post_type'   => 'product',
						  'post_status' => 'publish',
						  'numberposts' => 1
						);
						$query = get_posts($args);
						if( $query )
						{							
							$linked_product = $query[0];
						}
						wp_reset_postdata();
					}	
				}

				if ($linked_product)
				{
					$linked_products[] = $linked_product->ID;					
					
					$this->logger and call_user_func($this->logger, sprintf(__('Product `%s` with ID `%d` added to %s list.', 'wpai_woocommerce_addon_plugin'), $linked_product->post_title, $linked_product->ID, $type == '_upsell_ids' ? 'Up-Sells' : 'Cross-Sells') );		
				}
				else
				{
					$not_found[] = $id;
				}							
			}	

			// not all linked products founded
			if ( ! empty($not_found))
			{
				$not_founded_linked_products = get_option( 'wp_all_import_not_linked_products_' . $this->import->id );

				if (empty($not_founded_linked_products)) $not_founded_linked_products = array();				

				$not_founded_linked_products[] = array(					
					'pid'  => $pid,
					'type' => $type,
					'not_linked_products' => $not_found
				);

				update_option( 'wp_all_import_not_linked_products_' . $this->import->id, $not_founded_linked_products );
			}					

			$this->pushmeta($pid, $type, $linked_products);	
			
		} 
		else 
		{
			delete_post_meta( $pid, $type );
		}
	}	

	function prepare_price( $price )
	{   
		return pmwi_prepare_price( $price, 
			$this->import->options['disable_prepare_price'], 
			$this->import->options['prepare_price_to_woo_format'], 
			$this->import->options['convert_decimal_separator'] 
		);		
	}

	function adjust_price( $price, $field )
	{
		return pmwi_adjust_price( $price, $field, $this->import->options);		
	}	
}