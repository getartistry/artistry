<?php

require_once dirname(__FILE__) . '/XmlImportWooCommerce.php';

class XmlImportWooCommerceShopOrder extends XmlImportWooCommerce{
	
	public $payment_gateways;
	public $shipping_methods;
    public $shipping_zone_methods = array();
    public $prices_include_tax = 0;
	public $tax_rates = array();
	public $order_data = array();

	public function __construct( $options ){		

		global $wpdb;

		$this->wpdb   = $wpdb;
		
		$this->import = $options['import'];
		$this->count  = $options['count'];
		$this->xml    = $options['xml'];
		$this->logger = $options['logger'];
		$this->chunk  = $options['chunk'];
		$this->xpath  = $options['xpath_prefix'];

        $this->prices_include_tax = ('yes' === get_option( 'woocommerce_prices_include_tax', 'no' ));

		$this->payment_gateways = WC_Payment_Gateways::instance()->get_available_payment_gateways();
		$this->shipping_methods = WC()->shipping->get_shipping_methods();

        if (class_exists('WC_Shipping_Zones')){
            $zones = WC_Shipping_Zones::get_zones();
            if (!empty($zones)){
                foreach ($zones as $zone_id => $zone){
                    if (!empty($zone['shipping_methods'])){
                        foreach ($zone['shipping_methods'] as $method){
                            $this->shipping_zone_methods[] = $method;
                        }
                    }
                }
            }
            else
            {
                $zone = new WC_Shipping_Zone( 0 );
                $this->shipping_zone_methods = $zone->get_shipping_methods();
            }
        }

		$tax_classes = array_filter( array_map( 'trim', explode( "\n", get_option( 'woocommerce_tax_classes' ) ) ) );
				
		if ( $tax_classes )
		{
			// Add Standard tax class
			if ( ! in_array( '', $tax_classes ) ) $tax_classes[] = '';
			
			foreach ( $tax_classes as $class )
			{
				foreach ( WC_Tax::get_rates_for_tax_class(sanitize_title( $class )) as $rate_key => $rate)
				{
					$this->tax_rates[$rate->tax_rate_id] = $rate;
				}				
			}
		}

        if ( ! has_filter('wp_all_import_is_post_to_skip')){
            add_filter('wp_all_import_is_post_to_skip', array( &$this, 'wp_all_import_is_post_to_skip'), 10, 5);
        }
        if ( ! has_filter('wp_all_import_combine_article_data')){
            add_filter('wp_all_import_combine_article_data', array( &$this, 'wp_all_import_combine_article_data'), 10, 4);
        }
		
	}

	protected function parse_item_row( $row, $cxpath, $count )
	{
		$tmp_files  = array();

		$row_data = array();

		$records = array();

		foreach ($row as $opt => $value) 
		{
			switch ($opt) 
			{			
				case 'class_xpath':
				case 'tax_code_xpath':
				case 'visibility_xpath':
					// skipp this field(s)
					break;

				case 'tax_rates':

					foreach ($value as $i => $tax_rate_row) 
					{
						$tax_rate_data = array();

						foreach ($tax_rate_row as $tax_rate_row_opt => $tax_rate_row_value) 
						{
							if ( ! empty($tax_rate_row_value) )
							{
								$tax_rate_data[$tax_rate_row_opt] = XmlImportParser::factory($this->xml, $cxpath, $tax_rate_row_value, $file)->parse($records); $tmp_files[] = $file;
							}	
							else
							{
								$count and $tax_rate_data[$tax_rate_row_opt] = array_fill(0, $count, $tax_rate_row_value);
							}										
						}
						$row_data[$opt][] = $tax_rate_data;
					}									

					break;

				case 'meta_name':
				case 'meta_value':

					foreach ($value as $meta) 
					{
						if ( ! empty($meta) )
						{
							$row_data[$opt][] = XmlImportParser::factory($this->xml, $cxpath, $meta, $file)->parse($records); $tmp_files[] = $file;
						}										
						else
						{
							$row_data[$opt][] = array_fill(0, $count, $meta);
						}
					}																	

					break;

				case 'class':
				case 'tax_code':
				case 'visibility':
					
					if ( $value == 'xpath' and $row[$opt . '_xpath'] != '' )
					{
						$row_data[$opt] = XmlImportParser::factory($this->xml, $cxpath, $row[$opt . '_xpath'], $file)->parse($records); $tmp_files[] = $file;
					}
					else
					{
						$count and $row_data[$opt] = array_fill(0, $count, $value);
					}

					break;

				case 'date':

					if ( ! empty($value))
					{
						$dates = XmlImportParser::factory($this->xml, $cxpath, $value, $file)->parse($records); $tmp_files[] = $file;
					
						$warned = array(); // used to prevent the same notice displaying several times
						foreach ($dates as $i => $d) {
							if ($d == 'now') $d = current_time('mysql'); // Replace 'now' with the WordPress local time to account for timezone offsets (WordPress references its local time during publishing rather than the server’s time so it should use that)
							$time = strtotime($d);
							if (FALSE === $time) {							
								$time = time();
							}
							$row_data[$opt][$i] = date('Y-m-d H:i:s', $time);
						}
					}	
					else
					{
						$count and $row_data[$opt] = array_fill(0, $count, date('Y-m-d H:i:s'));
					}	

					break;
				
				default:
					
					if ( ! empty($value))
					{
						$row_data[$opt] = XmlImportParser::factory($this->xml, $cxpath, $value, $file)->parse($records); $tmp_files[] = $file;	
					}
					else
					{
						$count and $row_data[$opt] = array_fill(0, $count, $value);
					}									

					break;
			}	
		}
		
		foreach ($tmp_files as $file) { // remove all temporary files created
			unlink($file);
		}

		return $row_data;
	}

	public function parse()
	{		
		$cxpath = $this->xpath . $this->import->xpath;

		$this->data = array();
		$records    = array();
		$tmp_files  = array();

		$this->chunk == 1 and $this->logger and call_user_func($this->logger, __('Composing shop order data...', 'wpai_woocommerce_addon_plugin'));

		$default = PMWI_Plugin::get_default_import_options();		

		foreach ($default['pmwi_order'] as $option => $default_value) 
		{
			if ( in_array($option, array('status_xpath', 'payment_method_xpath', 'order_note_visibility_xpath', 'billing_source', 
				'billing_source_match_by', 'shipping_source', 'products_source', 'order_taxes_logic', 'order_refund_issued_source', 'order_refund_issued_match_by', 
				'order_total_logic', 'order_note_separate_logic', 'order_note_separator', 'is_guest_matching', 'copy_from_billing')) or strpos($option, 'is_update_') !== false or strpos($option, '_repeater_mode') !== false) continue;

			switch ($option) 
			{
				case 'date':
				case 'order_refund_date':			
					
					if ( ! empty($this->import->options['pmwi_order'][$option]))
					{
						$dates = XmlImportParser::factory($this->xml, $cxpath, $this->import->options['pmwi_order'][$option], $file)->parse($records); $tmp_files[] = $file;
					
						$warned = array(); // used to prevent the same notice displaying several times
						foreach ($dates as $i => $d) {
							if ($d == 'now') $d = current_time('mysql'); // Replace 'now' with the WordPress local time to account for timezone offsets (WordPress references its local time during publishing rather than the server’s time so it should use that)
							$time = strtotime($d);
							if (FALSE === $time) {							
								$time = time();
							}
							$this->data['pmwi_order'][$option][$i] = date('Y-m-d H:i:s', $time);
						}
					}	
					else
					{
						$this->count and $this->data['pmwi_order'][$option] = array_fill(0, $this->count, date('Y-m-d H:i:s'));
					}									

					break;
				
				case 'status':
				case 'payment_method':
				case 'order_note_visibility':

					if ( $this->import->options['pmwi_order'][$option] == 'xpath' && $this->import->options['pmwi_order'][$option . '_xpath'] != "" )
					{
						$this->data['pmwi_order'][$option] = XmlImportParser::factory($this->xml, $cxpath, $this->import->options['pmwi_order'][$option . '_xpath'], $file)->parse($records); $tmp_files[] = $file;
					}
					else
					{
						$this->count and $this->data['pmwi_order'][$option] = array_fill(0, $this->count, $this->import->options['pmwi_order'][$option]);
					}

					break;

				case 'products':
				case 'manual_products':

					$this->data['pmwi_order'][$option] = array();
					
					switch ($this->import->options['pmwi_order']['products_repeater_mode']) 
					{
						case 'xml':
							
							foreach ($this->import->options['pmwi_order'][$option] as $key => $row) 
							{
								for ($k = 0; $k < $this->count; $k++) 
								{
									$base_xpath = '[' . ( $k + 1 ) . ']/'.  ltrim(trim($this->import->options['pmwi_order']['products_repeater_mode_foreach'],'{}!'), '/');

									$rows = XmlImportParser::factory($this->xml, $cxpath . $base_xpath, "{.}", $file)->parse(); $tmp_files[] = $file;						

									$row_data = $this->parse_item_row( $row, $cxpath . $base_xpath, count($rows) );												

									$products = array();

									if ( ! empty($row_data))
									{
										for ($j = 0; $j < count($rows); $j++) 
										{ 
											$products[] = array(
												'sku' => $row_data['sku'][$j],
												'qty' => $row_data['qty'][$j],
												'price_per_unit' => isset($row_data['price_per_unit'][$j]) ? $row_data['price_per_unit'][$j] : 0,
												'tax_rates' => array()
											);											

											if ( ! empty($row_data['tax_rates']))
											{
												foreach ($row_data['tax_rates'] as $tax_rate) 
												{
													$products[$j]['tax_rates'][] = array(
														'code' => $tax_rate['code'][$j],
														'calculate_logic' => $tax_rate['calculate_logic'][$j], 
														'percentage_value' => $tax_rate['percentage_value'][$j], 
														'amount_per_unit' => $tax_rate['amount_per_unit'][$j] 
													);
												}
											}											

											if ( ! empty($row_data['meta_name']))
											{
												foreach ($row_data['meta_name'] as $meta_name) 
												{
													if (isset($meta_name[$k])) $products[$j]['meta_name'][] = $meta_name[$k];
												}
											}											

											if ( ! empty($row_data['meta_value']))
											{
												foreach ($row_data['meta_value'] as $meta_value) 
												{
													if (isset($meta_value[$k])) $products[$j]['meta_value'][] = $meta_value[$k];
												}
											}											
										}	
									}																
									
									$this->data['pmwi_order'][$option][] = $products;			
								}

								break;
							}

							break;

						case 'csv':								

							foreach ($this->import->options['pmwi_order'][$option] as $key => $row) 
							{								
								if (empty($this->import->options['pmwi_order']['products_repeater_mode_separator'])) break;								

								$row_data = $this->parse_item_row( $row, $cxpath, $this->count );																													
								for ($k = 0; $k < $this->count; $k++) 
								{ 
									$products = array();

									$skus = explode($this->import->options['pmwi_order']['products_repeater_mode_separator'], $row_data['sku'][$k]);
									$qtys = explode($this->import->options['pmwi_order']['products_repeater_mode_separator'], $row_data['qty'][$k]);
									$prices = isset($row_data['price_per_unit'][$k]) ? explode($this->import->options['pmwi_order']['products_repeater_mode_separator'], $row_data['price_per_unit'][$k]) : array();

									if ( ! empty($skus))
									{
										for ($j = 0; $j < count($skus); $j++) 
										{ 				
											$products[] = array(
												'sku' => $skus[$j],
												'qty' => $qtys[$j],
												'price_per_unit' => isset($prices[$j]) ? $prices[$j] : 0,
												'tax_rates' => array()
											);																	

											if ( ! empty($row_data['tax_rates']))
											{
												foreach ($row_data['tax_rates'] as $tax_rate) 
												{
													$products[$j]['tax_rates'][] = array(
														'code' => $tax_rate['code'][$k],
														'calculate_logic' => $tax_rate['calculate_logic'][$k], 
														'percentage_value' => $tax_rate['percentage_value'][$k], 
														'amount_per_unit' => $tax_rate['amount_per_unit'][$k] 
													);																								
												}	
											}
											
											if ( ! empty($row_data['meta_name']))
											{
												foreach ($row_data['meta_name'] as $meta_name) 
												{
													if (isset($meta_name[$k])) $products[$j]['meta_name'][] = $meta_name[$k];
												}
											}
											
											if ( ! empty($row_data['meta_value']))
											{
												foreach ($row_data['meta_value'] as $meta_value) 
												{
													if (isset($meta_value[$k])) $products[$j]['meta_value'][] = $meta_value[$k];
												}
											}											
										}																					
									}	
									$this->data['pmwi_order'][$option][] = $products;									
								}									
																																							
								break;
							}

							break;
						
						default:
							
							$row_data = array();

							foreach ($this->import->options['pmwi_order'][$option] as $key => $row) 
							{																						
								$row_data[] = $this->parse_item_row( $row, $cxpath, $this->count );	
							}							

							for ($j = 0; $j < $this->count; $j++) 
							{
								$products = array(); 
								
								foreach ($row_data as $k => $product) 
								{
									$products[] = array(
										'sku' => $product['sku'][$j],
										'qty' => $product['qty'][$j],
										'price_per_unit' => isset($product['price_per_unit'][$j]) ? $product['price_per_unit'][$j] : 0,
										'tax_rates' => array()
									);

									if ( ! empty($product['tax_rates']))
									{
										foreach ($product['tax_rates'] as $tax_rate) 
										{
											$products[$k]['tax_rates'][] = array(
												'code' => $tax_rate['code'][$j],
												'calculate_logic' => $tax_rate['calculate_logic'][$j], 
												'percentage_value' => $tax_rate['percentage_value'][$j], 
												'amount_per_unit' => $tax_rate['amount_per_unit'][$j] 
											);
										}	
									}
									
									if ( ! empty($product['meta_name']))
									{
										foreach ($product['meta_name'] as $meta_name) 
										{
											if (isset($meta_name[$j])) $products[$k]['meta_name'][] = $meta_name[$j];
										}
									}									

									if ( ! empty($product['meta_value']))
									{
										foreach ($product['meta_value'] as $meta_value) 
										{
											if (isset($meta_value[$j])) $products[$k]['meta_value'][] = $meta_value[$j];
										}
									}									
								}
								$this->data['pmwi_order'][$option][] = $products;
							}														

							break;
					}											

					break;				
					
				case 'fees':
				case 'coupons':
				case 'shipping':
				case 'taxes':
				case 'notes':

					$this->data['pmwi_order'][$option] = array();

					switch ($this->import->options['pmwi_order'][$option . '_repeater_mode']) 
					{
						case 'xml':

							foreach ($this->import->options['pmwi_order'][$option] as $key => $row) 
							{
								for ($k = 0; $k < $this->count; $k++) 
								{
									$base_xpath = '[' . ( $k + 1 ) . ']/'.  ltrim(trim($this->import->options['pmwi_order'][$option . '_repeater_mode_foreach'],'{}!'), '/');

									$rows = XmlImportParser::factory($this->xml, $cxpath . $base_xpath, "{.}", $file)->parse(); $tmp_files[] = $file;

									$row_data = $this->parse_item_row( $row, $cxpath . $base_xpath, count($rows) );				

									$items = array();

									if ( ! empty($row_data))
									{
										for ($j = 0; $j < count($rows); $j++) 
										{
											foreach ($row_data as $itemkey => $values) 
											{
												$items[$j][$itemkey] = $values[$j];
											}										
										}																																															
									}									
																																
									$this->data['pmwi_order'][$option][] = $items;			
								}

								break;
							}

							break;

						case 'csv':

							$separator = $this->import->options['pmwi_order'][ $option . '_repeater_mode_separator'];

							foreach ($this->import->options['pmwi_order'][$option] as $key => $row) 
							{								
								if (empty($separator)) break;								

								$row_data = $this->parse_item_row( $row, $cxpath, $this->count );																															
								
								for ($k = 0; $k < $this->count; $k++) 
								{ 
									$items = array();									

									$maxCountRows = 0;

									foreach ($row_data as $itemkey => $values) 
									{
										$itemIndex = 0;

										$rows = explode($separator, $values[$k]);

										if ( ! empty($rows))
										{
											if (count($rows) > $maxCountRows) $maxCountRows = count($rows);

											if (count($rows) == 1)
											{
												for ($j = 0; $j < $maxCountRows; $j++) 
												{ 
													$items[$itemIndex][$itemkey] = trim($rows[0]);
													$itemIndex++;
												}
											}
											else
											{
												foreach ($rows as $val) 
												{
													$items[$itemIndex][$itemkey] = trim($val);
													$itemIndex++;
												}
											}											
										}
										// else 
										// {
										// 	for ($j = 0; $j < $maxCountRows; $j++) 
										// 	{ 
										// 		$items[$itemIndex][$itemkey] = '';
										// 		$itemIndex++;
										// 	}											
										// }
									}
									
									$this->data['pmwi_order'][$option][] = $items;									
								}									
																																							
								break;
							}

							break;

						default:

							$row_data = array();

							foreach ($this->import->options['pmwi_order'][$option] as $key => $row) 
							{																						
								$row_data[] = $this->parse_item_row( $row, $cxpath, $this->count );	
							}							

							for ($j = 0; $j < $this->count; $j++) 
							{
								$items = array(); 
								
								$itemIndex = 0;

								foreach ($row_data as $k => $item) 
								{									
									foreach ($item as $itemkey => $values) 
									{
										$items[$itemIndex][$itemkey] = $values[$j];
									}									
									$itemIndex++;
								}

								$this->data['pmwi_order'][$option][] = $items;
							}

							break;
					}					

					break;				

				default:

					if ( ! empty($this->import->options['pmwi_order'][$option]) )
					{
						$this->data['pmwi_order'][$option] = XmlImportParser::factory($this->xml, $cxpath, $this->import->options['pmwi_order'][$option], $file)->parse($records); $tmp_files[] = $file;
					}					
					else
					{
						$this->count and $this->data['pmwi_order'][$option] = array_fill(0, $this->count, $default_value);
					}
					
					break;
			}
		}

		foreach ($tmp_files as $file) { // remove all temporary files created
			unlink($file);
		}	
		
		// file_put_contents(PMWI_ROOT_DIR . '/order_parse.txt', json_encode($this->data));

		return $this->data;	
	}		

	public function import( $importData )
	{	
		$order_id = $importData['pid'];
		$index    = $importData['i'];
		$this->articleData = $importData['articleData'];

		/*
		*
		* Import Order details - status, date
		*
		*/

		$order_status = trim($this->data['pmwi_order']['status'][$index]);

		// detect order status by slug or title
		$all_order_statuses = wc_get_order_statuses();		
		if ( empty($all_order_statuses[$order_status])){
			$status_founded = false;
			foreach ($all_order_statuses as $key => $value) {
				if (strtolower($value) == strtolower($order_status)){
					$order_status = $key;
					$status_founded = true;
					break;
				}
			}
			if ( ! $status_founded ){
				$order_status = 'wc-pending';
			}
		}
		
		$this->order_data = array(
			'ID' => $order_id,
			'post_title'    => 'Order &ndash; ' . date_i18n( 'F j, Y @ h:i A', strtotime($this->data['pmwi_order']['date'][$index]) ),
			'post_content'  => '',
			'post_date' 	=> $this->data['pmwi_order']['date'][$index],
			'post_date_gmt' => get_gmt_from_date($this->data['pmwi_order']['date'][$index]),
			'post_status'   => $order_status,
			'ping_status'   => 'closed',
			'post_password' => uniqid( 'order_' ),
			'post_excerpt'  => $this->data['pmwi_order']['customer_provided_note'][$index], 
		);

		if ( ! empty($this->articleData['ID']))
		{
			if ( $this->import->options['update_all_data'] == 'no' ){
				if ( ! $this->import->options['is_update_dates']) { // preserve date of already existing article when duplicate is found
					$this->order_data['post_title'] = 'Order &ndash; ' . date_i18n( 'F j, Y @ h:i A', strtotime($this->articleData['post_date']) );
					$this->order_data['post_date'] = $this->articleData['post_date'];
					$this->order_data['post_date_gmt'] = $this->articleData['post_date_gmt'];					
				}
				if ( ! $this->import->options['is_update_status']) { // preserve status and trashed flag
					$this->order_data['post_status'] = $this->articleData['post_status'];						
				}
				if ( ! $this->import->options['is_update_excerpt']){ // preserve customer's note
					$this->order_data['post_excerpt'] = $this->articleData['post_excerpt'];
				}
			}						
		}

		$order_id = wp_update_post( $this->order_data );

		if ( is_wp_error( $order_id ) ) {
			return $order_id;
		}

		/** @var WC_Order $order */
		$order = wc_get_order($order_id);

		/*
		*
		* Import Order billing & shipping details
		*
		*/		

		$billing_fields = array('billing_first_name', 'billing_last_name', 'billing_company', 'billing_address_1', 'billing_address_2', 'billing_city', 'billing_postcode', 'billing_country', 'billing_state', 'billing_phone', 'billing_email');

		$billing_data = array();

		if ( empty($this->articleData['ID']) or $this->import->options['update_all_data'] == 'yes' or $this->import->options['is_update_billing_details'] )
		{
			$this->logger and call_user_func($this->logger, sprintf(__('- Importing billing & shipping information for Order ID `%s`.', 'wpai_woocommerce_addon_plugin'), $order_id));			

			// [ Importing billing information ]		
			switch ($this->import->options['pmwi_order']['billing_source']) 
			{
				// Load details from existing customer
				case 'existing':							
					
					$customer = $this->get_existing_customer('billing_source', $index);

					if ( $customer )
					{					
						$this->logger and call_user_func($this->logger, sprintf(__('- %s Existing customer with ID `%s` founded for Order `%s`.', 'wpai_woocommerce_addon_plugin'), $customer->ID, $order_id));
						
						foreach ($billing_fields as $billing_field) 
						{
							$billing_data[$billing_field] = get_user_meta( $customer->ID, $billing_field, true);
							update_post_meta( $order_id, '_' . $billing_field, $billing_data[$billing_field]);
							$this->logger and call_user_func($this->logger, sprintf(__('- Billing field `%s` has been updated with value `%s` for order `%s` ...', 'wp_all_import_plugin'), $billing_field, $billing_data[$billing_field], $order_id));						
						}
						update_post_meta( $order_id, '_customer_user', $customer->ID);
					}
					else
					{
                        if ($this->import->options['pmwi_order']['is_guest_matching']){
                            foreach ($billing_fields as $billing_field) {
                                $billing_data[$billing_field] = $this->data['pmwi_order']['guest_' . $billing_field][$index];
                                update_post_meta( $order_id, '_' . $billing_field, $billing_data[$billing_field]);
                                $this->logger and call_user_func($this->logger, sprintf(__('- Billing field `%s` has been updated with value `%s` for order `%s` ...', 'wp_all_import_plugin'), $billing_field, $this->data['pmwi_order']['guest_' . $billing_field][$index], $order_id));
                            }

                            update_post_meta( $order_id, '_customer_user', '0');
                        }
                        else{
                            $this->logger and call_user_func($this->logger, sprintf(__('<b>WARNING</b>: Existing customer not found for Order `%s`.', 'wpai_woocommerce_addon_plugin'), $this->order_data['post_title']));
                        }
					}

					break;
				
				// Use guest customer
				default:
					
					foreach ($billing_fields as $billing_field) {			
						$billing_data[$billing_field] = $this->data['pmwi_order'][$billing_field][$index];		
						update_post_meta( $order_id, '_' . $billing_field, $billing_data[$billing_field]);
						$this->logger and call_user_func($this->logger, sprintf(__('- Billing field `%s` has been updated with value `%s` for order `%s` ...', 'wp_all_import_plugin'), $billing_field, $this->data['pmwi_order'][$billing_field][$index], $order_id));						
					}
					
					update_post_meta( $order_id, '_customer_user', '0');

					break;
			}
		}		
		// [\Importing billing information ]			

		// [ Importing shipping information ]	
		if ( empty($this->articleData['ID']) or $this->import->options['update_all_data'] == 'yes' or $this->import->options['is_update_shipping_details'] )
		{	
			switch ($this->import->options['pmwi_order']['shipping_source']) 
			{
				// Copy from billing
				case 'copy':

					$this->logger and call_user_func($this->logger, sprintf(__('- Copying shipping from billing information...', 'wpai_woocommerce_addon_plugin')));

					if ( ! empty( $billing_data )){				
						foreach ($billing_data as $key => $value) {
							$shipping_field = str_replace('billing', 'shipping', $key);
							update_post_meta( $order_id, '_' . $shipping_field, $value );
							$this->logger and call_user_func($this->logger, sprintf(__('- Shipping field `%s` has been updated with value `%s` for order `%s` ...', 'wp_all_import_plugin'), $shipping_field, $value, $order_id));
						}
					}

					break;

				// Import shipping address
				default:
					foreach ($billing_fields as $billing_field) {		
						$shipping_field = str_replace('billing', 'shipping', $billing_field);
                        $shipping_value = '';
                        if ( ! empty($this->data['pmwi_order'][$shipping_field][$index]) ){
                            $shipping_value = $this->data['pmwi_order'][$shipping_field][$index];
                        }
                        elseif ($this->import->options['pmwi_order']['copy_from_billing']) {
                            $shipping_value = empty($billing_data[$billing_field]) ? '' : $billing_data[$billing_field];
                        }
                        update_post_meta( $order_id, '_' . $shipping_field, $shipping_value);
                        $this->logger and call_user_func($this->logger, sprintf(__('- Shipping field `%s` has been updated with value `%s` for order `%s` ...', 'wp_all_import_plugin'), $shipping_field, $shipping_value, $order_id));
					}

					break;
			}
		}
		// [\Importing shipping information ]

		// [ Importing payment information ]		
		if ( empty($this->articleData['ID']) or $this->import->options['update_all_data'] == 'yes' or $this->import->options['is_update_payment'] )
		{
			$payment_method = $this->data['pmwi_order']['payment_method'][$index];

			if ( ! empty($payment_method) )
			{
				if ( ! empty($this->payment_gateways[$payment_method]) )
				{
					update_post_meta( $order_id, '_payment_method', $payment_method );
					update_post_meta( $order_id, '_payment_method_title', $this->payment_gateways[$payment_method]->title );
				}	
				else
				{
					$method = false;
					if ( ! empty($this->payment_gateways))
					{
						foreach ($this->payment_gateways as $payment_gateway_slug => $payment_gateway) 
						{
							if ( strtolower($payment_gateway->method_title) == strtolower(trim($payment_method)) )
							{
								$method = $payment_method;
								break;
							}
						}						
					}
					
					if ($method)
					{
						update_post_meta( $order_id, '_payment_method', $payment_method );
						update_post_meta( $order_id, '_payment_method_title', $method->method_title );	
					}
				}			
			}
			else
			{
				update_post_meta( $order_id, '_payment_method', 'N/A' );
			}

			update_post_meta( $order_id, '_transaction_id', $this->data['pmwi_order']['transaction_id'][$index] );
		}		
		// [\Importing payment information ]		

		/*
		*
		* Import Order Items
		*
		*/

		// Importing product items
		if ( empty($this->articleData['ID']) or $this->import->options['update_all_data'] == 'yes' or $this->import->options['is_update_products'] )
		{			
			if ( ! empty($this->articleData['ID']) and ( $this->import->options['update_all_data'] == 'yes' or $this->import->options['is_update_products'] and $this->import->options['update_products_logic'] == 'full_update' ) )
			{

				$previously_updated_order = get_option('wp_all_import_previously_updated_order_' . $this->import->id, false);

				if ( empty($previously_updated_order) or $previously_updated_order != $this->articleData['ID'] ){

					$order->remove_order_items( 'line_item' );

					global $wpdb;				

					$wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->prefix}pmxi_posts WHERE import_id = %d AND post_id = %d AND unique_key LIKE %s;", $this->import->id, $order_id, '%' . $wpdb->esc_like('line-item') . '%' ) );

				}
				
			}

			$this->_import_line_items( $order, $order_id, $index );				
		}

		// Importing fee items
		if ( empty($this->articleData['ID']) or $this->import->options['update_all_data'] == 'yes' or $this->import->options['is_update_fees'] )
		{
			$this->_import_fee_items( $order, $order_id, $index );
		}

		// Importing coupons items
		if ( empty($this->articleData['ID']) or $this->import->options['update_all_data'] == 'yes' or $this->import->options['is_update_coupons'] )
		{
			$this->_import_coupons_items( $order, $order_id, $index );
		}

		// Importing shipping items
		if ( empty($this->articleData['ID']) or $this->import->options['update_all_data'] == 'yes' or $this->import->options['is_update_shipping'] )
		{
			$this->_import_shipping_items( $order, $order_id, $index );
		}

		// Importing taxes items
		if ( empty($this->articleData['ID']) or $this->import->options['update_all_data'] == 'yes' or $this->import->options['is_update_taxes'] )
		{
			$this->_import_taxes_items( $order, $order_id, $index );
		}

        /*
        *
        * Import Order Total
        *
        */

        if ( empty($this->articleData['ID']) or $this->import->options['update_all_data'] == 'yes' or $this->import->options['is_update_total'] )
        {
            if ( $this->import->options['pmwi_order']['order_total_logic'] !== 'auto' )
            {
                if ( version_compare(WOOCOMMERCE_VERSION, '3.0') < 0 ) {
                    $order->set_total($this->data['pmwi_order']['order_total_xpath'][$index], 'total');
                }
                else{
                    update_post_meta( $order_id, '_order_total', wc_format_decimal( $this->data['pmwi_order']['order_total_xpath'][$index], wc_get_price_decimals() ) );
                }
            }
            else
            {
                $order->calculate_totals();
            }
        }

        /*
        *
        * Import Order Refunds
        *
        */
		if ( empty($this->articleData['ID']) or $this->import->options['update_all_data'] == 'yes' or $this->import->options['is_update_refunds'] )
		{
			if ( ! empty($this->data['pmwi_order']['order_refund_amount'][$index]) )
			{
				$refund_item = new PMXI_Post_Record();
				$refund_item->getBy(array(
					'import_id'  => $this->import->id,
					'post_id'    => $order_id,
					'unique_key' => 'refund-item-' . $order_id
				));

				$args = array(
					'amount'     => $this->data['pmwi_order']['order_refund_amount'][$index],
					'reason'     => $this->data['pmwi_order']['order_refund_reason'][$index],
					'order_id'   => $order_id,
					'refund_id'  => 0,
					'line_items' => array(),
					'date'       => $this->data['pmwi_order']['order_refund_date'][$index]
				);
				
				if ( ! $refund_item->isEmpty() ) $args['refund_id'] = str_replace('refund-item-', '', $refund_item->product_key);

                if ( ! empty($this->import->options['do_not_send_order_notifications']) ) {
                    remove_all_actions('woocommerce_order_partially_refunded');
                    remove_all_actions('woocommerce_order_fully_refunded');
                    remove_all_actions( 'woocommerce_order_status_refunded_notification' );
                    remove_all_actions( 'woocommerce_order_partially_refunded_notification' );
                    remove_action( 'woocommerce_order_status_refunded', array( 'WC_Emails', 'send_transactional_email' ) );
                    remove_action( 'woocommerce_order_partially_refunded', array( 'WC_Emails', 'send_transactional_email' ) );
                }

				$refund = wc_create_refund( $args );

                if ( version_compare(WOOCOMMERCE_VERSION, '3.0') < 0 ) {
                    $refundID = $refund->id;
                }
                else{
                    $refundID = $refund->get_id();
                }

				if ( $refund instanceOf WC_Order_Refund )
				{
					$refund_item->set(array(
						'import_id'   => $this->import->id,
						'post_id'     => $order_id,
						'unique_key'  => 'refund-item-' . $order_id,
						'product_key' => 'refund-item-' . $refundID,
						'iteration'   => $this->import->iteration
					))->save();

					switch ($this->import->options['pmwi_order']['order_refund_issued_source']) 
					{
						case 'existing':
							
							$customer = $this->get_existing_customer('order_refund_issued', $index);					

							if ($customer)
							{
								wp_update_post(array(
									'ID' => $refundID,
									'post_author' => $customer->ID
								));
							}

							break;
						
						default:
							
							wp_update_post(array(
								'ID' => $refundID,
								'post_author' => 0
							));

							break;
					}
				}			
			}
		}

		/*
		*
		* Import Order Notes
		*
		*/

		if ( empty($this->articleData['ID']) or $this->import->options['update_all_data'] == 'yes' or $this->import->options['is_update_notes'] )
		{			
			$this->_import_order_notes( $order, $order_id, $index );			
		}

		update_post_meta( $order_id, '_order_version', WC_VERSION );
        $_order_tax = get_post_meta($order_id, '_order_tax', true);
        if (empty($_order_tax)){
            update_post_meta( $order_id, '_order_tax', 0 );
        }
        update_post_meta( $order_id, '_order_shipping_tax', 0 );
	}

	/**
	*
	* When users are matching to existing customers and/or products and no match it found, 
	* WP All Import doesn't have enough information to import that order, so the whole order will be skipped.
	*
	*/ 
	public function wp_all_import_is_post_to_skip( $is_post_to_skip, $import_id, $current_xml_node, $index, $post_to_update_id ) 
	{		

		$order_title = 'Order &ndash; ' . date_i18n( 'F j, Y @ h:i A', strtotime($this->data['pmwi_order']['date'][$index]) );

		if ( empty($post_to_update_id) or $this->import->options['update_all_data'] == 'yes' or $this->import->options['is_update_billing_details'] )
		{
			if ( $this->import->options['pmwi_order']['billing_source'] == 'existing' ) {
				$customer = $this->get_existing_customer('billing_source', $index);						
				if ( empty($customer) && empty($this->import->options['pmwi_order']['is_guest_matching']))
				{
					$this->logger and call_user_func($this->logger, sprintf(__('<b>SKIPPED</b>: %s Existing customer not found for Order `%s`.', 'wpai_woocommerce_addon_plugin'), $this->get_existing_customer_for_logger('billing_source', $index), $order_title));
					$is_post_to_skip = true;
				}
			}
		}
		
		if ( empty($post_to_update_id) or $this->import->options['update_all_data'] == 'yes' or $this->import->options['is_update_products'] )
		{
			if ( ! $is_post_to_skip and $this->import->options['pmwi_order']['products_source'] == 'existing')
			{
				$is_product_founded = false;

				$searching_for_sku = '';

				foreach ($this->data['pmwi_order']['products'][$index] as $productIndex => $productItem) 
				{				
					if (empty($productItem['sku']))	continue;

					$searching_for_sku = $productItem['sku'];

					$args = array(
						'post_type' => 'product',
						'meta_key' 	=> '_sku',
						'meta_value' => $productItem['sku'],
						'meta_compare' => '=',
					);

					$product = false;				

					$query = new WP_Query( $args );
					while ( $query->have_posts() ) {
						$query->the_post();
						$product = WC()->product_factory->get_product($query->post->ID);
						break;
					}
					wp_reset_postdata();

					if (empty($product))
					{
						$args['post_type'] = 'product_variation';
						$query = new WP_Query( $args );
						while ( $query->have_posts() ) {
							$query->the_post();
							$product = WC()->product_factory->get_product($query->post->ID);
							break;
						}
						wp_reset_postdata();
					}
					if ( $product )
					{
						$is_product_founded = true;
						break;
					}
				}
				if ( ! $is_product_founded ){
					$this->logger and call_user_func($this->logger, sprintf(__('<b>SKIPPED</b>: Existing product `%s` not found for Order `%s`.', 'wpai_woocommerce_addon_plugin'), $searching_for_sku, $order_title));
					$is_post_to_skip = true;
				}
			}
		}		

		return $is_post_to_skip;
	}

	public function wp_all_import_combine_article_data( $articleData, $post_type, $import_id, $index )
	{
		if ( $post_type == 'shop_order' && empty($articleData['post_title'])) 
		{
			$articleData['post_title'] = 'Order &ndash; ' . date_i18n( 'F j, Y @ h:i A', strtotime($this->data['pmwi_order']['date'][$index]) );
		}

		return $articleData;
	}

	public function after_save_post( $importData )
	{
        $old_status = str_replace("wc-", "", $this->articleData['post_status']);
        $new_status = str_replace("wc-", "", $this->order_data['post_status']);

        // send notifications on order status changed
        if ( ! empty($this->articleData['ID']) and $new_status !== $old_status && empty($this->import->options['do_not_send_order_notifications']))
        {
            do_action( 'woocommerce_order_status_' . $old_status . '_to_' . $new_status, $importData['pid'] );
            do_action( 'woocommerce_order_status_changed', $importData['pid'], $old_status, $new_status );

            if ( $new_status == 'completed' )
            {
                do_action( 'woocommerce_order_status_completed', $importData['pid']);
            }

        }

        // send new order notification
        if ( empty($this->articleData['ID']) && empty($this->import->options['do_not_send_order_notifications']) )
        {
            /** @var WC_Order $order */
            $order = wc_get_order($importData['pid']);

            do_action( 'woocommerce_order_status_' . $new_status, $importData['pid'] );

            do_action( 'woocommerce_order_status_pending_to_' . $new_status, $importData['pid'] );

            do_action( 'woocommerce_before_resend_order_emails', $order );

            // Load mailer
            $mailer = WC()->mailer();

            $email_to_send = 'new_order';

            $mails = $mailer->get_emails();

            if ( ! empty( $mails ) ) {
                foreach ( $mails as $mail ) {
                    if ( $mail->id == $email_to_send ) {
                        $mail->trigger( $importData['pid'] );
                        $this->logger and call_user_func($this->logger, sprintf(__('- %s email notification has beed sent. ...', 'wp_all_import_plugin'), $mail->title));
                    }
                }
            }

            do_action( 'woocommerce_after_resend_order_email', $order, $email_to_send );
        }

		update_option('wp_all_import_previously_updated_order_' . $this->import->id, $importData['pid']);	
	}

	protected function get_order_notes( $order_id ) {
		$notes = array();
		$args  = array(
			'post_id' => $order_id,
			'approve' => 'approve',
			'type'    => ''
		);

		remove_filter( 'comments_clauses', array( 'WC_Comments', 'exclude_order_comments' ) );

		$comments = get_comments( $args );

		foreach ( $comments as $comment ) {										
			if ($comment->comment_approved != 'trash') $notes[] = $comment;
		}

		add_filter( 'comments_clauses', array( 'WC_Comments', 'exclude_order_comments' ) );

		return $notes;
	}

	protected function _import_line_items( & $order, $order_id, $index )
	{
		
		$is_product_founded = false;

		switch ( $this->import->options['pmwi_order']['products_source'] ) 
		{
			// Get data from existing products
			case 'existing':								

				foreach ($this->data['pmwi_order']['products'][$index] as $productIndex => $productItem) 
				{					
					if (empty($productItem['sku']))	continue;

					$args = array(
						'post_type' => 'product',
						'meta_key' 	=> '_sku',
						'meta_value' => $productItem['sku'],
						'meta_compare' => '=',
					);

					$product = false;				

					$query = new WP_Query( $args );
					while ( $query->have_posts() ) {
						$query->the_post();
						$product = WC()->product_factory->get_product($query->post->ID);
						break;
					}
					wp_reset_postdata();

					if ( empty($product))
					{
						$args['post_type'] = 'product_variation';
						$query = new WP_Query( $args );
						while ( $query->have_posts() ) {
							$query->the_post();
							$product = WC()->product_factory->get_product($query->post->ID);
							break;
						}
						wp_reset_postdata();
					}					

					if ( $product )
					{
						$is_product_founded = true;

						$item_price = empty($productItem['price_per_unit']) ? $product->get_price() : $productItem['price_per_unit'];

						$item_qty = empty($productItem['qty']) ? 1 : $productItem['qty']; 

						$item_subtotal = $item_price * $item_qty;

						$item_subtotal_tax = 0;

						$line_taxes = array();

						foreach ($productItem['tax_rates'] as $key => $tax_rate) 
						{		
							if (empty($tax_rate['code'])) continue;

							$tax_rate_codes   = explode("|", $tax_rate['code']);
							$percentage_value = explode("|", $tax_rate['percentage_value']);
							$amount_per_unit  = explode("|", $tax_rate['amount_per_unit']);

							foreach ($tax_rate_codes as $rate_key => $tax_rate_code) {

							    if ( $tax_rate_code == 'standard' ) $tax_rate_code = '';

								$line_tax = 0;												

								switch ($tax_rate['calculate_logic']) 
								{
									case 'percentage':										

										if ( ! empty($percentage_value[$rate_key]) and is_numeric($percentage_value[$rate_key]))
										{
											$line_tax = WC_Tax::round( ($item_subtotal/100) * $percentage_value[$rate_key] );
											$item_subtotal_tax += $line_tax;
										}

                                        if ( ! empty($this->tax_rates)){
                                            foreach($this->tax_rates as $rate_id => $rate){
                                                if ($rate->tax_rate_name == $tax_rate_code){
                                                    $line_taxes[$rate->tax_rate_id] = $line_tax;
                                                    break;
                                                }
                                            }
                                        }

										break;

									case 'per_unit';
										
										if ( ! empty($amount_per_unit[$rate_key]) and is_numeric($amount_per_unit[$rate_key]))
										{
											$line_tax = WC_Tax::round( $amount_per_unit[$rate_key] * $item_qty );
											$item_subtotal_tax += $line_tax;
										}

                                        if ( ! empty($this->tax_rates)){
                                            foreach($this->tax_rates as $rate_id => $rate){
                                                if ($rate->tax_rate_name == $tax_rate_code){
                                                    $line_taxes[$rate->tax_rate_id] = $line_tax;
                                                    break;
                                                }
                                            }
                                        }
										break;
								
									// Look up tax rate code
									default:
										
										$found_rates = WC_Tax::get_rates_for_tax_class( $tax_rate_code );

										if ( ! empty($found_rates))
										{
											$found_priority    = array();

											foreach ( $found_rates as $found_rate ) {
                                                $matched_tax_rates = array();

												if ( in_array( $found_rate->tax_rate_priority, $found_priority ) ) {
													continue;
												}

												$matched_tax_rates[ $found_rate->tax_rate_id ] = array(
													'rate'     => $found_rate->tax_rate,
													'label'    => $found_rate->tax_rate_name,
													'shipping' => $found_rate->tax_rate_shipping ? 'yes' : 'no',
													'compound' => $found_rate->tax_rate_compound ? 'yes' : 'no'
												);

                                                $line_tax = array_sum( WC_Tax::calc_tax( $item_subtotal, $matched_tax_rates, $this->prices_include_tax ) );

                                                $item_subtotal_tax += $line_tax;
                                                $line_taxes[$found_rate->tax_rate_id] = $line_tax;

                                                $found_priority[] = $found_rate->tax_rate_priority;
											}
										}

										break;
								}
							}							
						}						

						$variation = array();

						$variation_str = '';

						if ( $product instanceOf WC_Product_Variation )
						{
							$variation = $product->get_variation_attributes();

							if (!empty($variation)){
								foreach ($variation as $key => $value) {
									$variation_str .= $key . '-' . $value;
								}
							}
						}

						$product_item = new PMXI_Post_Record();
						$product_item->getBy(array(
							'import_id'  => $this->import->id,
							'post_id'    => $order_id,
							'unique_key' => 'line-item-' . $product->get_id() . '-' . $variation_str
						));
						
						if ( $product_item->isEmpty() )
						{	
							$item_id = false;
							
							// in case when this is new order just add new line items
							if ( ! $item_id )
							{
								$item_id = $order->add_product(
									$product,
									$item_qty,
									array(
										'variation' => $variation,
										'totals'    => array(
											'subtotal'     => $item_subtotal,
											'subtotal_tax' => $item_subtotal_tax,
											'total'        => $item_subtotal,
											'tax'          => $item_subtotal_tax,
											'tax_data'     => array( 'total' => $line_taxes, 'subtotal' => array()) // Since 2.2
										)
									)
								);								
							}							
								
							if ( ! $item_id ) {
								$this->logger and call_user_func($this->logger, __('- <b>WARNING</b> Unable to create order line product.', 'wp_all_import_plugin'));		
							}
							else
							{
								$product_item->set(array(
									'import_id'   => $this->import->id,
									'post_id'     => $order_id,
									'unique_key'  => 'line-item-' . $product->get_id() . '-' . $variation_str,
									'product_key' => 'line-item-' . $item_id,
									'iteration'   => $this->import->iteration
								))->save();
							}
						}
						else
						{
							$item_id = str_replace('line-item-', '', $product_item->product_key);
							$is_updated = $order->update_product(
								$item_id, 
								$product, 
								array(
									'qty' => $item_qty,
									'tax_class' => $product->get_tax_class(),
									'totals' => array(
										'subtotal'     => $item_subtotal,
										'subtotal_tax' => $item_subtotal_tax,
										'total'        => $item_subtotal,
										'tax'          => $item_subtotal_tax,
										'tax_data'     => array( 'total' => $line_taxes, 'subtotal' => array() ) // Since 2.2
									),
									'variation' =>  $variation
								)
							);
							if ( $is_updated )
							{
								$product_item->set(array(								
									'iteration'   => $this->import->iteration
								))->save();
							}
						}									
					}	
				}				
				
				break;

			// Manually import product order data and do not try to match to existing products
			default:

				$is_product_founded = true;
				
				foreach ($this->data['pmwi_order']['manual_products'][$index] as $productIndex => $productItem) 
				{

				    if (empty($productItem['sku']))	continue;

					$item_price = $productItem['price_per_unit'];

					$item_qty = empty($productItem['qty']) ? 1 : $productItem['qty']; 

					$item_subtotal = $item_price * $item_qty;

					$item_subtotal_tax = 0;

					$line_taxes = array();

					foreach ($productItem['tax_rates'] as $key => $tax_rate) 
					{		
						if (empty($tax_rate['code'])) continue;

						$line_tax = 0;												

						switch ($tax_rate['calculate_logic']) 
						{
							case 'percentage':

								if ( ! empty($tax_rate['percentage_value']) and is_numeric($tax_rate['percentage_value']))
								{
									$line_tax = WC_Tax::round( ($item_subtotal/100) * $tax_rate['percentage_value'] );
									$item_subtotal_tax += $line_tax;
								}
								break;

							case 'per_unit';
								
								if ( ! empty($tax_rate['amount_per_unit']) and is_numeric($tax_rate['amount_per_unit']))
								{
									$line_tax = WC_Tax::round( $tax_rate['amount_per_unit'] * $item_qty );
									$item_subtotal_tax += $line_tax;
								}
								break;
						
							// Look up tax rate code
							default:
								
								$found_rates = WC_Tax::get_rates_for_tax_class( $tax_rate['code'] );

								if ( ! empty($found_rates))
								{									
									$matched_tax_rates = array();
									$found_priority    = array();

									foreach ( $found_rates as $found_rate ) {
										if ( in_array( $found_rate->tax_rate_priority, $found_priority ) ) {
											continue;
										}

										$matched_tax_rates[ $found_rate->tax_rate_id ] = array(
											'rate'     => $found_rate->tax_rate,
											'label'    => $found_rate->tax_rate_name,
											'shipping' => $found_rate->tax_rate_shipping ? 'yes' : 'no',
											'compound' => $found_rate->tax_rate_compound ? 'yes' : 'no'
										);

										$found_priority[] = $found_rate->tax_rate_priority;
									}
									$line_tax = array_sum( WC_Tax::calc_tax( $item_subtotal, $matched_tax_rates, true ) );
									$item_subtotal_tax += $line_tax;
								}

								break;
						}

						if ( ! empty($this->tax_rates)){
							foreach($this->tax_rates as $rate_id => $rate){
								$line_taxes[$rate->tax_rate_id] = $line_tax;
								break;
							}							
						}
					}

					$variation = array();

					$product_item = new PMXI_Post_Record();
					$product_item->getBy(array(
						'import_id'  => $this->import->id,
						'post_id'    => $order_id,
						'unique_key' => 'manual-line-item-' . $productIndex . '-' . $productItem['sku']
					));
					
					if ( $product_item->isEmpty() )
					{
						$item_id = wc_add_order_item( $order_id, array(
							'order_item_name' => $productItem['sku'],
							'order_item_type' => 'line_item'
						) );						
						
						if ( ! $item_id ) {
							$this->logger and call_user_func($this->logger, __('- <b>WARNING</b> Unable to create order line product.', 'wp_all_import_plugin'));			
						}
						else
						{
							wc_add_order_item_meta( $item_id, '_qty',          wc_stock_amount( $item_qty ) );
							wc_add_order_item_meta( $item_id, '_tax_class',    '' );

							wc_add_order_item_meta( $item_id, '_line_subtotal',     wc_format_decimal( $item_subtotal ));
							wc_add_order_item_meta( $item_id, '_line_total',        wc_format_decimal( $item_subtotal ));
							wc_add_order_item_meta( $item_id, '_line_subtotal_tax', wc_format_decimal( $item_subtotal_tax ));
							wc_add_order_item_meta( $item_id, '_line_tax',          wc_format_decimal( $item_subtotal_tax ));
							wc_add_order_item_meta( $item_id, '_line_tax_data', 	array( 'total' => $line_taxes, 'subtotal' => array() ) );

							if ( ! empty($productItem['meta_name']))
							{
								foreach ($productItem['meta_name'] as $key => $meta_name) 
								{
									wc_add_order_item_meta( $item_id, $meta_name, isset($productItem['meta_value'][$key]) ? $productItem['meta_value'][$key] : '');		
								}
							}							

							$product_item->set(array(
								'import_id'   => $this->import->id,
								'post_id'     => $order_id,
								'unique_key'  => 'manual-line-item-' . $productIndex . '-' . $productItem['sku'],
								'product_key' => 'manual-line-item-' . $item_id,
								'iteration'   => $this->import->iteration
							))->save();
						}
					}
					else
					{
						$item_id = str_replace('manual-line-item-', '', $product_item->product_key);						
						
						if ( is_numeric($item_id) )
						{
							wc_update_order_item( $item_id, array(
								'order_item_name' => $productItem['sku'],
								'order_item_type' => 'line_item'
							));

							wc_update_order_item_meta( $item_id, '_qty', wc_stock_amount( $item_qty ) );
							wc_update_order_item_meta( $item_id, '_tax_class',    '' );

							wc_update_order_item_meta( $item_id, '_line_subtotal',     wc_format_decimal( $item_subtotal ));
							wc_update_order_item_meta( $item_id, '_line_total',        wc_format_decimal( $item_subtotal ));
							wc_update_order_item_meta( $item_id, '_line_subtotal_tax', wc_format_decimal( $item_subtotal_tax ));
							wc_update_order_item_meta( $item_id, '_line_tax',          wc_format_decimal( $item_subtotal_tax ));
							wc_update_order_item_meta( $item_id, '_line_tax_data', 	array( 'total' => $line_taxes, 'subtotal' => array() ) );

							if ( ! empty($productItem['meta_name']))
							{
								foreach ($productItem['meta_name'] as $key => $meta_name) 
								{
									wc_update_order_item_meta( $item_id, $meta_name, isset($productItem['meta_value'][$key]) ? $productItem['meta_value'][$key] : '');		
								}
							}

							$product_item->set(array(								
								'iteration'   => $this->import->iteration
							))->save();
						}
					}
				}

				break;
		}

		return $is_product_founded;
	}

	protected function _import_fee_items( & $order, $order_id, $index )
	{
		if ( ! empty($this->data['pmwi_order']['fees'][$index]))
		{
			foreach ($this->data['pmwi_order']['fees'][$index] as $feeIndex => $fee) 
			{
				if (empty($fee['name'])) continue;

				$fee_item = new PMXI_Post_Record();
				$fee_item->getBy(array(
					'import_id'  => $this->import->id,
					'post_id'    => $order_id,
					'unique_key' => 'fee-item-' . $feeIndex
				));
				
				if ( $fee_item->isEmpty() )
				{

					$item_id = false;

					if ( ! empty($this->articleData['ID']) )
					{							
						$order_items = $order->get_items('fee');
						
						foreach ($order_items as $order_item_id => $order_item) 
						{
							if ( $order_item['name'] == $fee['name'] )
							{
								$item_id = $order_item_id;
								break(2);
							}									
						}	
					}

					if ( ! $item_id )
					{
						$fee_line = array(
							'name' => $fee['name'],
							'tax_class' => '',
							'amount' => $fee['amount'],
							'tax' => '',
							'tax_data' => array(),
							'taxable' => 0
						);
                        if ( version_compare(WOOCOMMERCE_VERSION, '3.0') < 0 ) {
                            $item_id = $order->add_fee((object) $fee_line);
                        }
                        else{

                            $item = new WC_Order_Item_Fee();
                            $item->set_order_id( $order_id );
                            $item->set_name( wc_clean( $fee_line['name'] ) );
                            $item->set_total( isset( $fee_line['amount'] ) ? floatval( $fee_line['amount'] ) : 0 );

                            // if taxable, tax class and total are required
                            if ( ! empty( $fee_line['taxable'] ) ) {
                                if ( ! isset( $fee_line['tax_class'] ) ) {
                                    $this->logger and call_user_func($this->logger, __('- <b>WARNING</b> Fee tax class is required when fee is taxable.', 'wp_all_import_plugin'));
                                }
                                else{
                                    $item->set_tax_status( 'taxable' );
                                    $item->set_tax_class( $fee_line['tax_class'] );

                                    if ( isset( $fee_line['total_tax'] ) ) {
                                        $item->set_total_tax( isset( $fee_line['total_tax'] ) ? wc_format_refund_total( $fee_line['total_tax'] ) : 0 );
                                    }

                                    if ( isset( $fee_line['tax_data'] ) ) {
                                        $item->set_total_tax( wc_format_refund_total( array_sum( $fee_line['tax_data'] ) ) );
                                        $item->set_taxes( array_map( 'wc_format_refund_total', $fee_line['tax_data'] ) );
                                    }
                                }
                            }
                            $item_id = $item->save();
                        }
					}					

					if ( ! $item_id ) {
						$this->logger and call_user_func($this->logger, __('- <b>WARNING</b> order line fee is not added.', 'wp_all_import_plugin'));
					}
					else
					{
						$fee_item->set(array(
							'import_id'   => $this->import->id,
							'post_id'     => $order_id,
							'unique_key'  => 'fee-item-' . $feeIndex,
							'product_key' => 'fee-item-' . $item_id,
							'iteration'   => $this->import->iteration
						))->save();
					}
				}
				else
				{
					$item_id = str_replace('fee-item-', '', $fee_item->product_key);

                    if ( version_compare(WOOCOMMERCE_VERSION, '3.0') < 0 ) {
                        $is_updated = $order->update_fee($item_id, array(
                            'name' => $fee['name'],
                            'tax_class' => '',
                            'line_total' => $fee['amount'],
                            'line_tax' => 0
                        ));
                    }
                    else{
                        $item = new WC_Order_Item_Fee( $item_id );

                        if ( isset( $fee['title'] ) ) {
                            $item->set_name( wc_clean( $fee['name'] ) );
                        }
                        if ( isset( $fee['tax_class'] ) ) {
                            $item->set_tax_class( $fee['tax_class'] );
                        }
                        if ( isset( $fee['amount'] ) ) {
                            $item->set_total( floatval( $fee['amount'] ) );
                        }
                        if ( isset( $fee['total_tax'] ) ) {
                            $item->set_total_tax( floatval( $fee['total_tax'] ) );
                        }
                        $is_updated = $item->save();
                    }

					if ( $is_updated )
					{
						$fee_item->set(array(								
							'iteration'   => $this->import->iteration
						))->save();
					}
				}
			}
			$this->_calculate_fee_taxes( $order );
		}
	}

	protected function _import_coupons_items( & $order, $order_id, $index )
	{
		$total_discount_amount = 0;
		$total_discount_amount_tax = 0;

		if ( ! empty($this->data['pmwi_order']['coupons'][$index]))
		{			
			foreach ($this->data['pmwi_order']['coupons'][$index] as $couponIndex => $coupon) 
			{
				if (empty($coupon['code'])) continue;

				$coupon += array('code' => '', 'amount' => '', 'amount_tax' => '');

				$order_item = new PMXI_Post_Record();
				$order_item->getBy(array(
					'import_id'  => $this->import->id,
					'post_id'    => $order_id,
					'unique_key' => 'coupon-item-' . $couponIndex
				));

                $absAmount = abs($coupon['amount']);

				if ( ! empty($absAmount) ) $total_discount_amount += $absAmount;
				if ( ! empty($coupon['amount_tax']) ) $total_discount_amount_tax += $coupon['amount_tax'];
				
				if ( $order_item->isEmpty() )
				{	
					$item_id = false;

					if ( ! empty($this->articleData['ID']) )
					{							
						$order_items = $order->get_items('coupon');
						
						foreach ($order_items as $order_item_id => $order_item) 
						{
							if ( $order_item['name'] == $coupon['code'] )
							{
								$item_id = $order_item_id;
								break(2);
							}									
						}	
					}

					if ( ! $item_id )
					{
						if ( version_compare(WOOCOMMERCE_VERSION, '3.0') < 0 ){
                            $item_id = $order->add_coupon( $coupon['code'], $absAmount, $coupon['amount_tax'] );
                        }
                        else{
                            $item = new WC_Order_Item_Coupon();
                            $item->set_props( array(
                                'code'         => $coupon['code'],
                                'discount'     => isset( $coupon['amount'] ) ? floatval( $coupon['amount'] ) : 0,
                                'discount_tax' => 0,
                                'order_id'     => $order_id,
                            ) );
                            $item_id = $item->save();
                        }
					}					

					if ( ! $item_id ) {
						$this->logger and call_user_func($this->logger, __('- <b>WARNING</b> Unable to create order coupon line.', 'wp_all_import_plugin'));
					}
					else
					{
						$order_item->set(array(
							'import_id'   => $this->import->id,
							'post_id'     => $order_id,
							'unique_key'  => 'coupon-item-' . $couponIndex,
							'product_key' => 'coupon-item-' . $item_id,
							'iteration'   => $this->import->iteration
						))->save();
					}					
				}		
				else
				{
					$item_id = str_replace('coupon-item-', '', $order_item->product_key);

                    if ( version_compare(WOOCOMMERCE_VERSION, '3.0') < 0 ) {

                        $is_updated = $order->update_coupon($item_id, array(
                            'code' => $coupon['code'],
                            'discount_amount' => $absAmount,
                            // 'discount_amount_tax' => empty($coupon['amount_tax']) ? NULL : $coupon['amount_tax']
                        ));

                    }
                    else{

                        $item = new WC_Order_Item_Coupon( $item_id );

                        if ( isset( $coupon['code'] ) ) {
                            $item->set_code( $coupon['code'] );
                        }

                        if ( isset( $coupon['amount'] ) ) {
                            $item->set_discount( floatval( $coupon['amount'] ) );
                        }

                        $is_updated = $item->save();
                    }

					if ( $is_updated )
					{
						$order_item->set(array(								
							'iteration'   => $this->import->iteration
						))->save();
					}
				}		
			}			
		}
		update_post_meta($order_id, '_cart_discount', $total_discount_amount);
		update_post_meta($order_id, '_cart_discount_tax', $total_discount_amount_tax);
	}

    /**
     * @param WC_Order $order
     * @param $order_id
     * @param $index
     */
	protected function _import_shipping_items( & $order, $order_id, $index )
	{
		if ( ! empty($this->data['pmwi_order']['shipping'][$index]))
		{			
			$total_shipping = 0;

			foreach ($this->data['pmwi_order']['shipping'][$index] as $shippingIndex => $shipping)
			{
				if (empty($shipping['name'])) continue;

				$method = false;				

				if ($this->import->options['pmwi_order']['shipping'][0]['class'] == 'xpath')
				{
					if ( empty($this->shipping_methods[$shipping['class']]))
					{
						foreach ($this->shipping_methods as $shipping_method_slug => $shipping_method) 
						{

							if ( $shipping_method_slug == str_replace(" ", "_", strtolower(trim($shipping['class']))) || $shipping_method->method_title == $shipping['class'])
							{
								$method = $shipping_method;
								break;
							}
						}						
					} 	
					else
					{
						$method = $this->shipping_methods[$shipping['class']];
					}

                    if ( empty($method) && !empty($this->shipping_zone_methods) ){
                        foreach ($this->shipping_zone_methods as $shipping_zone_method) {
                            if ($shipping_zone_method->title == $shipping['class']){
                                $method = $shipping_zone_method;
                                break;
                            }
                        }
                    }
				}	
				else
				{
					foreach ($this->shipping_methods as $shipping_method_slug => $shipping_method) 
					{						
						if ( $shipping_method_slug == str_replace(" ", "_", strtolower(trim($shipping['class']))) || $shipping_method->method_title == $shipping['class'])
						{
							$method = $shipping_method;
							break;
						}
					}
				}

				if ( $method )
				{					
					$shipping_method = new WC_Shipping_Rate($method->id, $shipping['name'], $shipping['amount']);

					$shipping_item = new PMXI_Post_Record();
					$shipping_item->getBy(array(
						'import_id'  => $this->import->id,
						'post_id'    => $order_id,
						'unique_key' => 'shipping-item-' . $shippingIndex
					));
					
					$total_shipping += $shipping['amount'];

					if ( $shipping_item->isEmpty() )
					{
						$item_id = false;

						if ( ! empty($this->articleData['ID']) )
						{							
							$order_items = $order->get_items('shipping');
							
							foreach ($order_items as $order_item_id => $order_item) 
							{
								if ( $order_item['name'] == $shipping['name'] )
								{
									$item_id = $order_item_id;
									break(2);
								}									
							}	
						}

						if ( ! $item_id )
						{
                            if ( version_compare(WOOCOMMERCE_VERSION, '3.0') < 0 ) {
                                $item_id = $order->add_shipping( $shipping_method );
                            }
                            else {
                                $item = new WC_Order_Item_Shipping();
                                $item->set_props(array(
                                    'method_title' => $shipping_method->label,
                                    'method_id' => $shipping_method->id,
                                    'total' => wc_format_decimal($shipping_method->cost),
                                    'taxes' => $shipping_method->taxes,
                                    'order_id' => $order_id,
                                ));
                                foreach ($shipping_method->get_meta_data() as $key => $value) {
                                    $item->add_meta_data($key, $value, TRUE);
                                }
                                $item->save();
                                $order->add_item($item);
                                $item_id = $item->get_id();
                            }
						}

						if ( ! $item_id ) {						
							$this->logger and call_user_func($this->logger, __('- <b>WARNING</b> Unable to create order shipping line.', 'wp_all_import_plugin'));
						}
						else
						{
							$shipping_item->set(array(
								'import_id'   => $this->import->id,
								'post_id'     => $order_id,
								'unique_key'  => 'shipping-item-' . $shippingIndex,
								'product_key' => 'shipping-item-' . $item_id,
								'iteration'   => $this->import->iteration
							))->save();
						}
					}
					else
					{
                        $item_id = str_replace('shipping-item-', '', $shipping_item->product_key);
                        $args = array(
                            'method_title' => $shipping_method->label,
                            'method_id' => $shipping_method->id,
                            'cost' => $shipping_method->cost
                        );
                        if ( version_compare(WOOCOMMERCE_VERSION, '3.0') < 0 ) {
                            $item_id = $order->add_shipping( $shipping_method );
                            $order->update_shipping($item_id, $args);
                        }
                        else{
                            $item = $order->get_item( $item_id );
                            if ( is_object( $item ) && $item->is_type( 'shipping' ) ) {
                                $item->set_order_id( $order_id );
                                $item->set_props( $args );
                                $item->save();
                                $order->calculate_shipping();

                                $is_updated = $item->get_id();
                            }
                        }

                        if ( $is_updated )
						{
							$shipping_item->set(array(								
								'iteration'   => $this->import->iteration
							))->save();
						}
					}
				}				
			}
			update_post_meta($order_id, '_order_shipping', $total_shipping);

            $this->_calculate_shipping_taxes( $order );
		}
	}

	protected function _import_taxes_items( & $order, $order_id, $index )
	{
		if ( ! empty($this->data['pmwi_order']['taxes'][$index]))
		{
			foreach ($this->data['pmwi_order']['taxes'][$index] as $taxIndex => $tax) 
			{
				if (empty($tax['tax_code'])) continue;

				$founded = true;	

				$tax_rate = null;			

				if ($this->import->options['pmwi_order']['taxes'][0]['tax_code'] == 'xpath')
				{
					if ( empty($this->tax_rates[$tax['tax_code']])) 
					{
						$founded_by_name = false;
						foreach ($this->tax_rates as $rate_id => $rate) {							
							if ($rate->tax_rate_name == $tax['tax_code']){
								$founded_by_name = true;
								$tax_rate = $rate;
								break;
							}
						}
						if ( ! $founded_by_name ) $founded = false;			
					}
					else
					{
						$tax_rate = $this->tax_rates[$tax['tax_code']];
//						$tax['tax_amount'] = 0;
						$tax['shipping_tax_amount'] = 0;
					}
				}
                else{
                    if ( ! empty($this->tax_rates[$tax['tax_code']])) {
                        $tax_rate = $this->tax_rates[$tax['tax_code']];
//                        $tax['tax_amount'] = 0;
                        $tax['shipping_tax_amount'] = 0;
                    }
                    else{
                        $founded = false;
                    }
                }

                if ( $founded )
				{
					$tax_item = new PMXI_Post_Record();
					$tax_item->getBy(array(
						'import_id'  => $this->import->id,
						'post_id'    => $order_id,
						'unique_key' => 'tax-item-' . $taxIndex
					));

					if ( $tax_item->isEmpty() )
					{
						$item_id = false;

						if ( ! empty($this->articleData['ID']) )
						{							
							$order_items = $order->get_items('tax');
							
							foreach ($order_items as $order_item_id => $order_item) 
							{
								if ( $order_item['name'] == $tax['tax_code'] )
								{
									$item_id = $order_item_id;
									break(2);
								}									
							}	
						}

						if ( ! $item_id )
						{

                            if ( version_compare(WOOCOMMERCE_VERSION, '3.0') < 0 ) {
                                $item_id = $order->add_tax( $tax_rate->tax_rate_id, $tax['tax_amount'], $tax['shipping_tax_amount'] );
                            }
                            else{

                                $item = new WC_Order_Item_Tax();
                                $item->set_props( array(
                                    'name'      => $tax_rate->tax_rate_name,
                                    'tax_class' => empty($tax_rate->tax_rate_class) ? 0 : $tax_rate->tax_rate_class,
                                    'total'     => $tax['tax_amount'],
                                    'total_tax' => $tax['tax_amount'],
                                    'order_id'  => $order_id,
                                ) );
                                $item_id = $item->save();
                            }
						}

						if ( ! $item_id ) {						
							$this->logger and call_user_func($this->logger, __('- <b>WARNING</b> Unable to create order tax line.', 'wp_all_import_plugin'));
						}
						else
						{
							$tax_item->set(array(
								'import_id'   => $this->import->id,
								'post_id'     => $order_id,
								'unique_key'  => 'tax-item-' . $taxIndex,
								'product_key' => 'tax-item-' . $item_id,
								'iteration'   => $this->import->iteration
							))->save();

						}
					}
					else{

                        $item_id = str_replace('tax-item-', '', $tax_item->product_key);

                        if ( version_compare(WOOCOMMERCE_VERSION, '3.0') >= 0 ) {

                            try{
                                $item = new WC_Order_Item_Tax( $item_id );

                                if ( isset( $tax_rate->tax_rate_name ) ) {
                                    $item->set_name( wc_clean( $tax_rate->tax_rate_name ) );
                                }
                                if ( isset( $tax_rate->tax_rate_id ) ) {
                                    $item->set_rate( $tax_rate->tax_rate_id );
                                }
                                if ( isset( $tax['tax_amount'] ) ) {
                                    $item->set_tax_total( floatval( $tax['tax_amount'] ) );
                                }

                                $is_updated = $item->save();

                                if ( $is_updated )
                                {
                                    $tax_item->set(array(
                                        'iteration'   => $this->import->iteration
                                    ))->save();
                                }
                            }
                            catch (Exception $e){
                                $tax_item->delete();
                            }
                        }

                    }
//					$order->update_taxes();
				}				
			}
		}
	}

	protected function _import_order_notes( & $order, $order_id, $index )
	{
		if ( ! empty($this->data['pmwi_order']['notes'][$index]))
		{
			$notes_count = 0;
			foreach ($this->data['pmwi_order']['notes'][$index] as $noteIndex => $note) 
			{
				if (empty($note['content'])) continue;

				$note_item = new PMXI_Post_Record();
				$note_item->getBy(array(
					'import_id'  => $this->import->id,
					'post_id'    => $order_id,
					'unique_key' => 'note-item-' . $order_id . '-' . $noteIndex
				));	

				if ( ! $note_item->isEmpty()){
					$note_id = str_replace('note-item-', '', $note_item->product_key);					
					$is_note_exist = get_comment($note_id);					
					if ( empty($is_note_exist) || $is_note_exist->comment_approved == 'trash' ){
						$note_item->delete();						
						$note_item->clear();
						if ( ! empty($is_note_exist) && $is_note_exist->comment_approved == 'trash' ){
							wp_delete_comment( $note_id, true );
						}
					}
				}

				$comment_author = empty($note['username']) ? 'WP All Import' : $note['username'];
				$comment_author_email = $note['email'];

				if ( empty($comment_author) && empty($comment_author_email) )
				{
					if ( is_user_logged_in() ) {
						$user                 = get_user_by( 'id', get_current_user_id() );							
						$comment_author_email = $user->user_email;
					} else {
						$comment_author_email = strtolower( __( 'WooCommerce', 'wp_all_import_plugin' ) ) . '@';
						$comment_author_email .= isset( $_SERVER['HTTP_HOST'] ) ? str_replace( 'www.', '', $_SERVER['HTTP_HOST'] ) : 'noreply.com';
						$comment_author_email = sanitize_email( $comment_author_email );
					}
				}				

				$comment_post_ID        = $order_id;
				$comment_author_url     = '';
				$comment_content        = $note['content'];
				$comment_agent          = 'WooCommerce';
				$comment_type           = 'order_note';
				$comment_parent         = 0;
				$comment_approved       = 1;
				$is_customer_note       = $note['visibility'] == "private" ? 0 : 1;
				$commentdata            = apply_filters( 'woocommerce_new_order_note_data', compact( 'comment_post_ID', 'comment_author', 'comment_author_email', 'comment_author_url', 'comment_content', 'comment_agent', 'comment_type', 'comment_parent', 'comment_approved' ), array( 'order_id' => $order_id, 'is_customer_note' => $is_customer_note ) );					

				if ( $note_item->isEmpty())
				{						
					$comment_id = false;

					if ( ! empty($this->articleData['ID']))
					{
						$current_notes = $this->get_order_notes( $order_id );

						if ( ! empty($current_notes) )
						{
							foreach ($current_notes as $current_note) 
							{									
								if ($current_note->comment_content == $commentdata['comment_content'] )
								{
									$comment_id = $current_note->comment_ID;
									break;
								}	
							}
						}

					}						

					if ( ! $comment_id )
					{
						$comment_id = wp_insert_comment( $commentdata );

						if ( $note['visibility'] != 'private') 
						{
							add_comment_meta( $comment_id, 'is_customer_note', 1 );
							// send customer note notification
							if ( empty($this->import->options['do_not_send_order_notifications']) ) 
							{												
								do_action( 'woocommerce_new_customer_note', array( 'order_id' => $order_id, 'customer_note' => $commentdata['comment_content'] ) );								
							}
						}
					}						

					$note_item->set(array(
						'import_id'   => $this->import->id,
						'post_id'     => $order_id,
						'unique_key'  => 'note-item-' . $order_id . '-' . $noteIndex,
						'product_key' => 'note-item-' . $comment_id,
						'iteration' => $this->import->iteration
					))->save();
				}				
				else
				{
					$commentdata['comment_ID'] = str_replace('note-item-', '', $note_item->product_key);
					
					wp_update_comment( $commentdata );

					if ( $note['visibility'] != 'private') {
						update_comment_meta( $commentdata['comment_ID'], 'is_customer_note', 1 );
					}
					else
					{
						delete_comment_meta( $commentdata['comment_ID'], 'is_customer_note' );
					}

					$note_item->set(array(						
						'iteration' => $this->import->iteration
					))->save();
				}
				$notes_count++;
			}

			global $wpdb;			

			$wpdb->update( $wpdb->posts, array('comment_count'  => $notes_count), array('ID' => $order_id), array( '%d' ), array( '%d' ) );
		}
	}

	protected function get_existing_customer_for_logger( $option_slug, $index )
	{
		$log = __("Search customer by ", "wpai_woocommerce_addon_plugin");

		switch ($this->import->options['pmwi_order'][$option_slug . '_match_by']) 
		{
			case 'username':

				$log .= __("username", "wpai_woocommerce_addon_plugin") . " `" . $this->data['pmwi_order'][$option_slug . '_username'][$index] . "`";
				
				break;

			case 'email':
				
				$log .= __("email", "wpai_woocommerce_addon_plugin") . " `" . $this->data['pmwi_order'][$option_slug . '_email'][$index] . "`";

				break;

			case 'cf':

				$log .= __("custom field", "wpai_woocommerce_addon_plugin") . ": `" . $this->data['pmwi_order'][$option_slug . '_cf_name'][$index] . "` equals to `" . $this->data['pmwi_order'][$option_slug . '_cf_value'][$index] . "`";				

				break;

			case 'id':

				$log .= __("ID", "wpai_woocommerce_addon_plugin") . " `" . $this->data['pmwi_order'][$option_slug . '_id'][$index] . "`";

				break;
		}

		return $log . ".";
	}

	protected function get_existing_customer( $option_slug, $index )
	{
		$customer = false;
				
		switch ($this->import->options['pmwi_order'][$option_slug . '_match_by']) 
		{
			case 'username':
				$search_by = $this->data['pmwi_order'][$option_slug . '_username'][$index];
				$customer  = get_user_by('login', $search_by) or $customer = get_user_by('slug', $search_by);
				break;

			case 'email':
				$search_by = $this->data['pmwi_order'][$option_slug . '_email'][$index];
				$customer  = get_user_by('email', $search_by);
				break;

			case 'cf':
				$cf_name   = $this->data['pmwi_order'][$option_slug . '_cf_name'][$index];
				$cf_value  = $this->data['pmwi_order'][$option_slug . '_cf_value'][$index];

				$user_query = new WP_User_Query( array( 'meta_key' => $cf_name, 'meta_value' => $cf_value ) );

				if ( ! empty( $user_query->results ) ) {
					$customer = array_shift($user_query->results);							
				}

				break;

			case 'id':
				$search_by = $this->data['pmwi_order'][$option_slug . '_id'][$index];
				$customer = get_user_by('id', $search_by);
				break;
		}

		return $customer;
	}

    /**
     * @param WC_Order $order
     * @return bool
     */
    protected function _calculate_shipping_taxes(& $order ){

        $tax_total          = 0;
        $shipping_tax_total = 0;
        $taxes              = array();
        $shipping_taxes     = array();
        $tax_based_on       = get_option( 'woocommerce_tax_based_on' );

        // If is_vat_exempt is 'yes', or wc_tax_enabled is false, return and do nothing.
        if ( ! wc_tax_enabled() ) {
            return false;
        }

        if ( 'billing' === $tax_based_on ) {
            $country  = $order->get_billing_country();
            $state    = $order->get_billing_state();
            $postcode = $order->get_billing_postcode();
            $city     = $order->get_billing_city();
        } elseif ( 'shipping' === $tax_based_on ) {
            $country  = $order->get_shipping_country();
            $state    = $order->get_shipping_state();
            $postcode = $order->get_shipping_postcode();
            $city     = $order->get_shipping_city();
        }

	    // Calc taxes for shipping
        foreach ( $order->get_shipping_methods() as $item_id => $item ) {
            $shipping_tax_class = get_option( 'woocommerce_shipping_tax_class' );

            // Inherit tax class from items
            if ( '' === $shipping_tax_class ) {
                $tax_classes       = WC_Tax::get_tax_classes();
                $found_tax_classes = $order->get_items_tax_classes();

                foreach ( $tax_classes as $tax_class ) {
                    $tax_class = sanitize_title( $tax_class );
                    if ( in_array( $tax_class, $found_tax_classes ) ) {
                        $tax_rates = WC_Tax::find_shipping_rates( array(
                          'country'   => $country,
                          'state'     => $state,
                          'postcode'  => $postcode,
                          'city'      => $city,
                          'tax_class' => $tax_class,
                        ) );
                        break;
                    }
                }
            } else {
                $tax_rates = WC_Tax::find_shipping_rates( array(
                  'country'   => $country,
                  'state'     => $state,
                  'postcode'  => $postcode,
                  'city'      => $city,
                  'tax_class' => 'standard' === $shipping_tax_class ? '' : $shipping_tax_class,
                ) );
            }

            $line_taxes          = WC_Tax::calc_tax( $item['cost'], $tax_rates, false );
            $line_tax            = max( 0, array_sum( $line_taxes ) );
            $shipping_tax_total += $line_tax;

            wc_update_order_item_meta( $item_id, '_line_tax', wc_format_decimal( $line_tax ) );
            wc_update_order_item_meta( $item_id, '_line_tax_data', array( 'total' => $line_taxes ) );

            // Sum the item taxes
            foreach ( array_keys( $shipping_taxes + $line_taxes ) as $key ) {
                $shipping_taxes[ $key ] = ( isset( $line_taxes[ $key ] ) ? $line_taxes[ $key ] : 0 ) + ( isset( $shipping_taxes[ $key ] ) ? $shipping_taxes[ $key ] : 0 );
            }
            wc_update_order_item_meta( $item_id, 'taxes', $shipping_taxes );
        }

        // Save tax totals
        $order->set_total( $shipping_tax_total, 'shipping_tax' );
//        $order->set_total( $tax_total, 'tax' );
    }

    /**
     * @param WC_Order $order
     * @return bool
     */
    protected function _calculate_fee_taxes(&$order ){

        $tax_total          = 0;
        $shipping_tax_total = 0;
        $taxes              = array();
        $shipping_taxes     = array();
        $tax_based_on       = get_option( 'woocommerce_tax_based_on' );

        // If is_vat_exempt is 'yes', or wc_tax_enabled is false, return and do nothing.
        if ( ! wc_tax_enabled() ) {
            return false;
        }

        if ( 'billing' === $tax_based_on ) {
            $country  = $order->get_billing_country();
            $state    = $order->get_billing_state();
            $postcode = $order->get_billing_postcode();
            $city     = $order->get_billing_city();
        } elseif ( 'shipping' === $tax_based_on ) {
            $country  = $order->get_shipping_country();
            $state    = $order->get_shipping_state();
            $postcode = $order->get_shipping_postcode();
            $city     = $order->get_shipping_city();
        }

        // Default to base
        if ( 'base' === $tax_based_on || empty( $country ) ) {
            $default  = wc_get_base_location();
            $country  = $default['country'];
            $state    = $default['state'];
            $postcode = '';
            $city     = '';
        }

        // Get items
        foreach ( $order->get_items( array( 'fee' ) ) as $item_id => $item ) {

            $product           = $order->get_product_from_item( $item );
            $line_total        = isset( $item['line_total'] ) ? $item['line_total'] : 0;
            $line_subtotal     = isset( $item['line_subtotal'] ) ? $item['line_subtotal'] : 0;
            $tax_class         = $item['tax_class'];
            $item_tax_status   = $product ? $product->get_tax_status() : 'taxable';

            if ( '0' !== $tax_class && 'taxable' === $item_tax_status ) {

                $tax_rates = WC_Tax::find_rates( array(
                  'country'   => $country,
                  'state'     => $state,
                  'postcode'  => $postcode,
                  'city'      => $city,
                  'tax_class' => $tax_class
                ) );

                $line_subtotal_taxes = WC_Tax::calc_tax( $line_subtotal, $tax_rates, false );
                $line_taxes          = WC_Tax::calc_tax( $line_total, $tax_rates, false );
                $line_subtotal_tax   = max( 0, array_sum( $line_subtotal_taxes ) );
                $line_tax            = max( 0, array_sum( $line_taxes ) );
                $tax_total           += $line_tax;

                wc_update_order_item_meta( $item_id, '_line_subtotal_tax', wc_format_decimal( $line_subtotal_tax ) );
                wc_update_order_item_meta( $item_id, '_line_tax', wc_format_decimal( $line_tax ) );
                wc_update_order_item_meta( $item_id, '_line_tax_data', array( 'total' => $line_taxes, 'subtotal' => $line_subtotal_taxes ) );

                // Sum the item taxes
                foreach ( array_keys( $taxes + $line_taxes ) as $key ) {
                    $taxes[ $key ] = ( isset( $line_taxes[ $key ] ) ? $line_taxes[ $key ] : 0 ) + ( isset( $taxes[ $key ] ) ? $taxes[ $key ] : 0 );
                }
            }
        }
    }
}