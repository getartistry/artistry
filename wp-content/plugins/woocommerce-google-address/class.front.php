<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * WooCommerce_Google_Address_Plugin_Front
 * Class for frontend.
 * @since 1.1
 */

if ( ! class_exists( 'WooCommerce_Google_Address_Plugin_Front' ) ) {

	class WooCommerce_Google_Address_Plugin_Front
	{

		protected $billing_fields_to_group;
		protected $shipping_fields_to_group;
		
		protected $billing_fields_to_put_after_address;
		protected $shipping_fields_to_put_after_address;
		
		protected $hideAddressFieldsForReturningCustomers;
		
		protected $enabled_on_shipping_address;
		protected $enabled_on_billing_address;
		
		protected $countries_with_additional_fields;
		protected $additional_fields;

		
		/*
		 * Use the filters woogoogad_billing_fields_to_group_filter and woogoogad_shipping_fields_to_group_filter to change or reorder the fields in the address group
		 */
		function __construct()
		{				
			$this->billing_fields_to_group = array('billing_address_1', 'billing_address_2', 'billing_city', 'billing_state', 'billing_postcode', 'billing_country');
			
			$this->shipping_fields_to_group = array('shipping_address_1', 'shipping_address_2', 'shipping_city', 'shipping_state', 'shipping_postcode', 'shipping_country');
			
			$this->billing_fields_to_put_after_address = array();
			
			$this->shipping_fields_to_put_after_address = array();
			
			$this->hideAddressFieldsForReturningCustomers = false;
			
			$this->enabled_on_shipping_address = true;
			$this->enabled_on_billing_address = true;
			
			$this->countries_with_additional_fields = array();
			$this->additional_fields = array();
			
			$this->hooks();
		} //__construct
		
		public function woocommerce_cart_loaded_from_session()
		{
			if(!is_admin())
			{
				if(!function_exists('wc_get_order_statuses')) //before WC 2.1
				{
					if(get_option('woocommerce_ship_to_billing_address_only') == 'yes')
					{
						$this->enabled_on_shipping_address = false;
					}
					else
					{
						global $woocommerce;
						if ( !$woocommerce->cart->needs_shipping() )
							$this->enabled_on_shipping_address = false;

					}
				}
				else //WC 2.1 +
				{
					if(get_option('woocommerce_ship_to_destination') == 'billing_only') 
					{
						$this->enabled_on_shipping_address = false;
					}
					elseif(!WC()->cart->needs_shipping_address())
					{
						$this->enabled_on_shipping_address = false;
					}
				}
			}
		}
		
		protected function hooks()
		{
			//add the address search fields
			add_filter( 'woocommerce_billing_fields', array(&$this, 'woocommerce_billing_fields_filter'), 10, 2 );
			
			add_filter( 'woocommerce_shipping_fields', array(&$this, 'woocommerce_shipping_fields_filter'), 10, 2 );
			
			//enqueue the google places js api and the plugin scripts
			add_action( 'wp_enqueue_scripts', array( $this, 'load_scripts' ) );
			
			add_action ('wp_print_scripts',  array( $this, 'groupGoogleScripts' ) );

			add_action( 'woocommerce_cart_loaded_from_session',  array( $this, 'woocommerce_cart_loaded_from_session' ) );
			
			

					
		} // hooks
		
		
		public function groupGoogleScripts()
		{
			if(is_checkout())
			{
				//avoid problems of multiple enqueues of Google Maps
				global $wp_scripts;
				$googles = array();
				
				foreach((array)$wp_scripts->registered as $script)
				{
					if(strpos($script->src, 'maps.googleapis.com/maps/api/js') !== false or strpos($script->src, 'maps.google.com/maps/api/js') !== false )
						$googles[] = $script;
						
				}
				
				$libraries = array();
				$unregistered = array(); 
				foreach($googles as $g)
				{
					wp_dequeue_script($g->handle);
					$unregistered[] = $g->handle;
					$qs = parse_url($g->src);
					$qs = $qs['query'];
					parse_str($qs, $params);
					
					if(isset($params['libraries']))
						$libraries = array_merge($libraries, explode(',', $params['libraries']) );

				}
				
				foreach((array)$wp_scripts->registered as $i=>$script)
				{
					foreach((array)$script->deps as $j => $dept)
					{
					
						if(in_array($dept, $unregistered))
						{
							$script->deps[$j] = 'google-api-grouped';
						}
					}
				
				}
    			
				$library = '';
				if(count($libraries))
					$library = 'libraries='.implode(',', $libraries);
				
				//for the list of languages see : https://developers.google.com/maps/faq#languagesupport
				$language = apply_filters('woogoogad_force_google_dropdown_language', '');
				if($language != '')
					$language = '&language='.$language;
					
					
				$other_parameters = apply_filters('woogoogad_gg_api_other_parameters', '');

				wp_enqueue_script( 'google-api-grouped', '//maps.googleapis.com/maps/api/js?'.$library.$language.$other_parameters, array(), '', true);	
			}	
		}
		
		/*
		 *	Loads the scripts on checkout pages
		 */
		public function load_scripts()
		{
			if(is_checkout())
			{
				//for the list of languages see : https://developers.google.com/maps/faq#languagesupport
				$language = apply_filters('woogoogad_force_google_dropdown_language', '');
				if($language != '')
					$language = '&language='.$language;
			
				
				$other_parameters = apply_filters('woogoogad_gg_api_other_parameters', '');
			
				wp_enqueue_script( 'google-places', '//maps.googleapis.com/maps/api/js?libraries=places'.$language.$other_parameters, array(), '', true);
				
				wp_enqueue_script( 'woogoogad-js', plugins_url('js/woogoogad.js', __FILE__), array('jquery', 'woocommerce', 'google-places'), '2.3.4', true );      
				
				
				$this->enabled_on_billing_address = apply_filters('woogoogad_enabled_on_billing_address_filter', $this->enabled_on_billing_address);
				
				$this->enabled_on_shipping_address = apply_filters('woogoogad_enabled_on_shipping_address_filter', $this->enabled_on_shipping_address);
			
				$this->handleAdditionalFields();
				
				$translation_array = array( 
					'billing_fields_to_group' => apply_filters( 'woogoogad_billing_fields_to_group_filter', $this->billing_fields_to_group),
					'shipping_fields_to_group' => apply_filters( 'woogoogad_shipping_fields_to_group_filter', $this->shipping_fields_to_group),
					'billing_fields_to_put_after_address' => apply_filters( 'woogoogad_billing_fields_to_put_after_address_filter', $this->billing_fields_to_put_after_address),
					'shipping_fields_to_put_after_address' => apply_filters( 'woogoogad_shipping_fields_to_put_after_address_filter', $this->shipping_fields_to_put_after_address),
					'billing_address_not_found_label' => apply_filters('woogoogad_billing_address_not_found_label_filter', __('Address not found ?', 'woogoogad')),
					'shipping_address_not_found_label' => apply_filters('woogoogad_shipping_address_not_found_label_filter', __('Address not found ?', 'woogoogad')),
					'hideAddressFieldsForReturningCustomers' => apply_filters('woogoogad_hide_address_fields_for_returning_customers_filter', $this->hideAddressFieldsForReturningCustomers),
					
					'billing_hide_detailed_address_label' => apply_filters( 'woogoogad_billing_hide_detailed_address_label_filter', __('Hide details', 'woogoogad')),
					'shipping_hide_detailed_address_label' => apply_filters( 'woogoogad_shipping_hide_detailed_address_label_filter', __('Hide details', 'woogoogad')),
					
					'enabled_on_shipping_address' => $this->enabled_on_shipping_address,
					'enabled_on_billing_address' => $this->enabled_on_billing_address,
					'restrict_billing_country' => apply_filters( 'woogoogad_restrict_billing_country_filter', ''),
					'restrict_shipping_country' => apply_filters( 'woogoogad_restrict_shipping_country_filter', ''),
					'shop_base_location' => apply_filters('woogoogad_shop_base_location_filter', get_option('woocommerce_default_country')),
				);
				
				
				$translation_array['woogoogad_countries_with_additional_fields'] = $this->countries_with_additional_fields;
				
				if(count($this->countries_with_additional_fields))
				{
					$translation_array['woogoogad_additional_fields'] = $this->additional_fields;
				}
				
				
    			wp_localize_script( 'woogoogad-js', 'woogoogad', $translation_array );    	
            
                if(apply_filters( 'woogoogad_use_css_filter', true))
                {
                    wp_enqueue_style( 'woogoogad-css', plugins_url('css/woogoogad.css', __FILE__) ); 
                }
            }
		}
		
		public function handleAdditionalFields()
		{
			$this->countries_with_additional_fields = apply_filters('woogoogad_countries_with_additional_fields', $this->countries_with_additional_fields);
			$this->additional_fields = apply_filters('woogoogad_additional_fields', $this->additional_fields);
			
			if(count($this->countries_with_additional_fields) && 
				(
					$this->enabled_on_billing_address &&
					
					(!isset($this->additional_fields['billing']) or !isset($this->additional_fields['billing']['street_name']) or !isset($this->additional_fields['billing']['house_number']))
				)
				or
				(
					$this->enabled_on_shipping_address &&
					
					(!isset($this->additional_fields['shipping']) or !isset($this->additional_fields['shipping']['street_name']) or !isset($this->additional_fields['shipping']['house_number']))
				)
			)
			{
				$this->countries_with_additional_fields = array();
			}
			
			if(count($this->countries_with_additional_fields))
			{	
				if($this->enabled_on_billing_address)
				{
					if(isset($this->additional_fields['billing']['house_number_suffix']) and $this->additional_fields['billing']['house_number_suffix'])
						array_unshift($this->billing_fields_to_group, $this->additional_fields['billing']['house_number_suffix']);
						
					if(isset($this->additional_fields['billing']['house_number']) and $this->additional_fields['billing']['house_number'])
						array_unshift($this->billing_fields_to_group, $this->additional_fields['billing']['house_number']);
						
					if(isset($this->additional_fields['billing']['street_name']) and $this->additional_fields['billing']['street_name'])
						array_unshift($this->billing_fields_to_group, $this->additional_fields['billing']['street_name']);
					
					
					if(isset($this->additional_fields['billing']['house_number']) and $this->additional_fields['billing']['house_number'])
						array_unshift($this->billing_fields_to_group, $this->additional_fields['billing']['house_number']);
						
					if(isset($this->additional_fields['billing']['bairro']) and $this->additional_fields['billing']['bairro'])
					{
						$city_position = array_search('billing_city', $this->billing_fields_to_group)+1;

						$this->billing_fields_to_group = array_merge (array_slice($this->billing_fields_to_group, 0, $city_position), array( $this->additional_fields['billing']['bairro']), array_slice($this->billing_fields_to_group, $city_position));

					}
					
					
				}
				
				if($this->enabled_on_shipping_address)
				{
					if(isset($this->additional_fields['shipping']['house_number_suffix']) and $this->additional_fields['shipping']['house_number_suffix'])
						array_unshift($this->shipping_fields_to_group, $this->additional_fields['shipping']['house_number_suffix']);
					
					if(isset($this->additional_fields['shipping']['house_number']) and $this->additional_fields['shipping']['house_number'])
						array_unshift($this->shipping_fields_to_group, $this->additional_fields['shipping']['house_number']);
						
					if(isset($this->additional_fields['shipping']['street_name']) and $this->additional_fields['shipping']['street_name'])
						array_unshift($this->shipping_fields_to_group, $this->additional_fields['shipping']['street_name']);
						
					if(isset($this->additional_fields['shipping']['house_number']) and $this->additional_fields['shipping']['house_number'])
						array_unshift($this->shipping_fields_to_group, $this->additional_fields['shipping']['house_number']);
				
					if(isset($this->additional_fields['shipping']['bairro']) and $this->additional_fields['shipping']['bairro'])
					{
						$city_position = array_search('shipping_city', $this->shipping_fields_to_group)+1;

						$this->shipping_fields_to_group = array_merge (array_slice($this->shipping_fields_to_group, 0, $city_position), array( $this->additional_fields['shipping']['bairro']), array_slice($this->shipping_fields_to_group, $city_position));

					}
				}
			}
		}

		/*
		 *	Add a field to enter the searched address in billing form
		 */
		public function woocommerce_billing_fields_filter($address_fields, $country)
		{
			if(is_checkout())
			{
				if($this->enabled_on_billing_address)
				{
					$address_fields['billing_address_google'] = array(
						'label' => apply_filters('woogoogad_billing_address_label_filter', __('Full address ', 'woogoogad').' <abbr class="required" title="' . esc_attr__( 'required', 'woocommerce'  ) . '">*</abbr>'),
						'class' => apply_filters('woogoogad_billing_row_classes_filter', array('form-row-wide', 'address-field')),
						'required' => false,
						'placeholder' => apply_filters('woogoogad_billing_row_placeholder', __('Start typing your address...', 'woogoogad'))
				);
				}
			}
			return $address_fields;

		} //woocommerce_billing_fields_filter
		
		
		/*
		 *	Add a field to enter the searched address in shipping form
		 */
		public function woocommerce_shipping_fields_filter($address_fields, $country)
		{
			if(is_checkout())
			{
				if($this->enabled_on_shipping_address)
				{
					$address_fields['shipping_address_google'] = array(
						'label' => apply_filters('woogoogad_shipping_address_label_filter', __('Full address ', 'woogoogad').' <abbr class="required" title="' . esc_attr__( 'required', 'woocommerce'  ) . '">*</abbr>'),
						'class' => apply_filters('woogoogad_shipping_row_classes_filter', array('form-row-wide', 'address-field')),
						'required' => false,
						'placeholder' => apply_filters('woogoogad_shipping_row_placeholder', __('Start typing your address...', 'woogoogad'))
					);
				}
			}
    		
    		return $address_fields;
			
		} //woocommerce_shipping_fields_filter
		
	} // WooCommerce_Google_Address_Plugin_Front
}





