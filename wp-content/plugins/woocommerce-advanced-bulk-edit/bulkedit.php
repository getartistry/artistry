<?php

defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );


class W3ExAdvBulkEditView{
	
	private static $ins = null;
	private $attributes      = array();
	private $attributes_asoc = array();
	private $variations_fields = array();
	private $categories = array();
	private $cat_asoc = array();
	private $largeattributes = array();
	private $iswpml = false;
	private $isversion3 = false;
	
	public static function lang_category_id($id,$taxname)
	{
	  if(function_exists('icl_object_id')) 
	  {
	    return icl_object_id($id,$taxname,false);
	  }else 
	  {
	  	if(has_filter('wpml_object_id'))
	  	{
			return apply_filters( 'wpml_object_id', $id, $taxname ,FALSE);
		}else
	    	return $id;
	  }
	}
	
    public static function init()
    {
       self::instance()->_main();
    }

    public static function instance()
    {
        is_null(self::$ins) && self::$ins = new self;
        return self::$ins;
    }
    
    public static function relpaceInvalid($str)
    {
    	$str = strip_tags($str);
        $str = str_replace('"','\"',$str);
      	$str = preg_replace('#\R+#', ' ', $str);
        return $str;
    }
	
	public function mb_ucfirst($p_str)
	{
		if (function_exists('mb_substr') && function_exists('mb_strtoupper') && function_exists('mb_strlen')) 
		{
			$string = $p_str;
			if(mb_strlen($p_str) > 0)
			{
			    $string = mb_strtoupper(mb_substr($p_str, 0, 1)) . mb_substr($p_str, 1);
			}
		    return $string;
		}else
		{
			return ucfirst($p_str);
		}
	}
	
	public function LoadAttributeTerms(&$attr,$name,$iter,$bcat = false,$skiploadfrontpage = false)
	{
		global $wpdb;
		$offset = $iter * 1000;
		$iter++;
		$limit = "LIMIT 1000 OFFSET {$offset}";
		$attrprefix = "";
		if($name !== 'product_tag' && !$bcat)
		{
			$attrprefix = "pa_";
		}
		$getquery = "SELECT t.name,tt.term_taxonomy_id FROM {$wpdb->prefix}terms as t INNER JOIN {$wpdb->prefix}term_taxonomy AS tt ON t.term_id= tt.term_id WHERE tt.taxonomy IN('".$attrprefix. $name ."') ORDER BY t.slug ASC {$limit}";
		if($skiploadfrontpage)
		{
			$getquery = "SELECT t.term_id,t.name,t.slug,tt.term_taxonomy_id FROM {$wpdb->prefix}terms as t INNER JOIN {$wpdb->prefix}term_taxonomy AS tt ON t.term_id= tt.term_id WHERE tt.taxonomy IN('".$attrprefix. $name ."') ORDER BY t.slug ASC {$limit}";
		}
		if($bcat)
		{
			$getquery = "SELECT t.term_id,t.name,t.slug,tt.term_taxonomy_id,tt.parent FROM {$wpdb->prefix}terms as t INNER JOIN {$wpdb->prefix}term_taxonomy AS tt ON t.term_id= tt.term_id WHERE tt.taxonomy IN('". $name ."') ORDER BY t.slug ASC {$limit}";
		}
		
		$values = $wpdb->get_results($getquery);
		if(is_wp_error($values))
			return false;
		foreach($values as $val){
			if(!is_object($val)) continue;
			if(!property_exists($val,'term_taxonomy_id')) continue;
			if($bcat)
			{
		    	if(!property_exists($val,'term_id')) continue;
				$cat = new stdClass();
				$cat->category_id     = $val->term_taxonomy_id;
				if($this->iswpml)
				{
				   if(ICL_LANGUAGE_CODE != 'all')
				   {
				   	   $id = self::lang_category_id($cat->category_id,$name);
					   if($id === NULL || $id != $cat->category_id)
					   		continue;
				   }
			    }
				$cat->term_id         = $val->term_id;
				$cat->category_name   = $val->name;
				$cat->category_slug   = urldecode($val->slug);
				$cat->category_parent = $val->parent;
				$this->categories[] = $cat;   
				$this->cat_asoc[$cat->category_id] = $cat;
				continue;
			}
			
			$value          = new stdClass();
			$value->id      = $val->term_taxonomy_id;
			if($this->iswpml)
			{
			   if(ICL_LANGUAGE_CODE != 'all')
			   {
			   	   $id = self::lang_category_id($val->term_taxonomy_id,$attrprefix.$name);
				   if($id === NULL || $id != $val->term_taxonomy_id)
				   		continue;
			   }
		    }
			$value->term_id      = $val->term_id;
			if($skiploadfrontpage)
			{
				$value->slug    = $val->slug;
				$value->name    = $val->name;
			}
		
//			$value->parent  = $val->parent;
			$attr->values[]  = $value;
		}
		if(count($values) === 1000)
		{
			$curr_settings = get_option('w3exabe_settings');
			if(!is_array($curr_settings))
				$curr_settings = array();
		
			$largetemp = array();
			if(isset($curr_settings['largeattributes']) && is_array($curr_settings['largeattributes']))
			{
				$largetemp = $curr_settings['largeattributes'];
				if(isset($largetemp[$name]) && $largetemp[$name] === "0")
				{
					return;
				}
			}
			$this->LoadAttributeTerms($attr,$name,$iter,$bcat,$skiploadfrontpage);
		}
	}
	
	public function loadAttributes($skiploadfrontpage = false)
	{
		//categories
//		echo ICL_LANGUAGE_CODE;
		$args = array(
		    'number'     => 99999,
		    'orderby'    => 'slug',
		    'order'      => 'ASC',
		    'hide_empty' => false,
		    'include'    => '',
			'fields'     => 'all',
			'taxonomy'   => 'product_cat'
		);
//		if($this->iswpml)
//		{
//			$woo_categories = get_terms( 'product_cat', $args );
//			if(is_wp_error($woo_categories))
//				return;
//			foreach($woo_categories as $category){
//			   if(!is_object($category)) continue;
//			   if(!property_exists($category,'term_taxonomy_id')) continue;
//			    if(!property_exists($category,'term_id')) continue;
//			   $cat = new stdClass();
//			   $cat->category_id     = $category->term_taxonomy_id;
//			   if(ICL_LANGUAGE_CODE !== 'all')
//			   {
//			   	   $id = self::lang_category_id($cat->category_id,'product_cat');
//				   if($id === NULL || $id != $cat->category_id)
//				   		continue;
//			   }
//			 
//			   $cat->term_id         = $category->term_id;
//			   $cat->category_name   = $category->name;
//			   $cat->category_slug   = urldecode($category->slug);
//			   $cat->category_parent = $category->parent;
//			   $this->categories[] = $cat;   
//			   $this->cat_asoc[$cat->category_id] = $cat;
//			};
//		}else
		{
			$this->LoadAttributeTerms($args,'product_cat',0,true);
		}
		

		
		$curr_settings = get_option('w3exabe_settings');
		if(!is_array($curr_settings))
			$curr_settings = array();
		
		$largetemp = array();
		if(isset($curr_settings['largeattributes']) && is_array($curr_settings['largeattributes']))
		{
//			$this->$largeattributes = $curr_settings['largeattributes'];
			$largetemp = $curr_settings['largeattributes'];
		}
		if(isset($curr_settings['disattributes']))
		{
			if($curr_settings['disattributes'] == 1)
				return;
		}
	    global $wpdb;
		
		$woo_attrs = $wpdb->get_results("select * from " . $wpdb->prefix . "woocommerce_attribute_taxonomies",ARRAY_A);
		$counter = 0;
//		foreach($woo_attrs as $attr){
			
		foreach($woo_attrs as $attr)
		{
//			if($counter > 15)
//				return;
			$counter++;
			$att         = new stdClass();
			$att->id     = $attr['attribute_id'];
			$att->name   = $attr['attribute_name'];  
			if(function_exists('wc_sanitize_taxonomy_name'))
				$att->name = wc_sanitize_taxonomy_name($attr['attribute_name']);
			$att->label  = $attr['attribute_label']; 
			if(!$att->label)
				$att->label = ucfirst($att->name);
			$att->type   = $attr['attribute_type'];

		  
			$att->values = array();
			$args = array(
							    'number'     => 99999,
							    'orderby'    => 'slug',
							    'order'      => 'ASC',
							    'hide_empty' => false,
							    'include'    => '',
								'fields'     => 'all'
							);
//			$attrcount = wp_count_terms( 'pa_' . $att->name, array('hide_empty' => false));
//			if ( is_wp_error($attrcount) ) 
//				continue;
//			if($attrcount > 1600)
//				continue;
//			if(isset($this->largeattributes[$att->name]) && $this->largeattributes[$att->name] === "1")
			
				
			$this->LoadAttributeTerms($att,$att->name,0,false,$skiploadfrontpage);
//			$values     = get_terms( 'pa_' . $att->name, array('hide_empty' => false));
			
			$skip = false;
		 	if(count($att->values) > 0)
			{
				if(count($att->values) >= 2000)
				{
					if(!isset($largetemp[$att->name]))
					{
						$largetemp[$att->name] = "0";
					}
					$this->largeattributes[$att->name] = array();
					$this->largeattributes[$att->name]['name'] = $att->name;
					$this->largeattributes[$att->name]['label'] = $att->label;
					$this->largeattributes[$att->name]['status'] = $largetemp[$att->name];
					if(isset($largetemp[$att->name]) && $largetemp[$att->name] === "0")
					{
						$att->values = array();
						$skip = true;
					}
				}
				if(count($att->values) < 2000 && count($att->values) >= 100)
				{
					$this->largeattributes[$att->name] = array();
					$this->largeattributes[$att->name]['name'] = $att->name;
					$this->largeattributes[$att->name]['label'] = $att->label;
					if(!isset($largetemp[$att->name]))
					{
						$this->largeattributes[$att->name]['status'] = "1";
					}else
					{
						$this->largeattributes[$att->name]['status'] = $largetemp[$att->name];
					}
					if(isset($largetemp[$att->name]) && $largetemp[$att->name] === "0")
					{
						$att->values = array();
						$skip = true;
					}
					
				}
				if(!$skip)
					$this->attributes[]  = $att;
//				$this->attributes_asoc[$att->name] = $att;
//				$this->variations_fields[] = 'pattribute_'.$att->id;
			}
		}
		$curr_settings['largeattributes'] = $largetemp;
		update_option('w3exabe_settings',$curr_settings);
	}

	public function loadTranslations(&$arr)
	{
		$arr['post_excerpt'] = __( 'Product Short Description', 'woocommerce-advbulkedit');
		if($arr['post_excerpt'] === "Product Short Description")
			$arr['post_excerpt'] = __( 'Product Short Description', 'woocommerce');
		$arr['post_content'] = __( 'Description', 'woocommerce-advbulkedit');
		if($arr['post_content'] === "Description")
			$arr['post_content'] = __( 'Description', 'woocommerce');
		$arr['_thumbnail_id'] = __( 'Image', 'woocommerce-advbulkedit');
		if($arr['_thumbnail_id'] === "Image")
			$arr['_thumbnail_id'] = __( 'Image', 'woocommerce');
		$arr['_product_image_gallery'] = __( 'Product Gallery', 'woocommerce-advbulkedit');
		if($arr['_product_image_gallery'] === "Product Gallery")
			$arr['_product_image_gallery'] = __( 'Product Gallery', 'woocommerce');
		$arr['_sku'] = __( 'SKU', 'woocommerce-advbulkedit');
		if($arr['_sku'] === "SKU")
			$arr['_sku'] = __( 'SKU', 'woocommerce');
		$arr['post_name'] = __( 'Slug', 'woocommerce-advbulkedit');
		if($arr['post_name'] === "Slug")
			$arr['post_name'] = __( 'Slug', 'woocommerce');
		$arr['product_tag'] = __( 'Tags', 'woocommerce-advbulkedit');
		if($arr['product_tag'] === "Tags")
			$arr['product_tag'] = __( 'Tags', 'woocommerce');
		$arr['_virtual'] = __( 'Virtual', 'woocommerce-advbulkedit');
		if($arr['_virtual'] === "Virtual")
			$arr['_virtual'] = __( 'Virtual', 'woocommerce');
		$arr['_downloadable'] = __( 'Downloadable', 'woocommerce-advbulkedit');
		if($arr['_downloadable'] === "Downloadable")
			$arr['_downloadable'] = __( 'Downloadable', 'woocommerce');
		$arr['post_title'] = __( 'Title', 'woocommerce-advbulkedit');
		if($arr['post_title'] === "Title")
			$arr['post_title'] = __( 'Title', 'woocommerce');
		$arr['product_cat'] = __( 'Categories', 'woocommerce-advbulkedit');
		if($arr['product_cat'] === "Categories")
			$arr['product_cat'] = __( 'Categories', 'woocommerce');
		$arr['_regular_price'] = __( 'Regular Price', 'woocommerce-advbulkedit');
		if($arr['_regular_price'] === "Regular Price")
			$arr['_regular_price'] = __( 'Regular Price', 'woocommerce');
		$arr['_sale_price'] = __( 'Sale Price', 'woocommerce-advbulkedit');
		if($arr['_sale_price'] === "Sale Price")
			$arr['_sale_price'] = __( 'Sale Price', 'woocommerce');
		$arr['_default_attributes'] = __( 'Default Attributes', 'woocommerce-advbulkedit');
		if($arr['_default_attributes'] === "Default Attributes")
			$arr['_default_attributes'] = __( 'Default', 'woocommerce').' '.__( 'Attributes', 'woocommerce');
		$arr['product_type'] = __( 'Product Type', 'woocommerce-advbulkedit');
		if($arr['product_type'] === "Product Type")
			$arr['product_type'] = __( 'Product Type', 'woocommerce');
		$arr['menu_order'] = __( 'Menu order', 'woocommerce-advbulkedit');
		if($arr['menu_order'] === "Menu order")
			$arr['menu_order'] = __( 'Menu order', 'woocommerce');
		$arr['comment_status'] = __( 'Enable reviews', 'woocommerce-advbulkedit');
		if($arr['comment_status'] === "Enable reviews")
			$arr['comment_status'] = __( 'Enable reviews', 'woocommerce');
		$arr['_button_text'] = __( 'Button text', 'woocommerce-advbulkedit');
		if($arr['_button_text'] === "Button text")
			$arr['_button_text'] = __( 'Button text', 'woocommerce');
		$arr['_product_url'] = __( 'Product URL', 'woocommerce-advbulkedit');
		if($arr['_product_url'] === "Product URL")
			$arr['_product_url'] = __( 'Product URL', 'woocommerce');
		$arr['_download_type'] = __( 'Download Type', 'woocommerce-advbulkedit');
		if($arr['_download_type'] === "Download Type")
			$arr['_download_type'] = __( 'Download Type', 'woocommerce');
		$arr['_downloadable_files'] = __( 'Downloadable Files', 'woocommerce-advbulkedit');
		if($arr['_downloadable_files'] === "Downloadable Files")
			$arr['_downloadable_files'] = __( 'Downloadable Files', 'woocommerce');
		$arr['_download_limit'] = __( 'Download Limit', 'woocommerce-advbulkedit');
		if($arr['_download_limit'] === "Download Limit")
			$arr['_download_limit'] = __( 'Download Limit', 'woocommerce');
		$arr['_download_expiry'] = __( 'Download Expiry', 'woocommerce-advbulkedit');
		if($arr['_download_expiry'] === "Download Expiry")
			$arr['_download_expiry'] = __( 'Download Expiry', 'woocommerce');
		$arr['_virtual'] = __( 'Virtual', 'woocommerce-advbulkedit');
		if($arr['_virtual'] === "Virtual")
			$arr['_virtual'] = __( 'Virtual', 'woocommerce');
		$arr['_downloadable'] = __( 'Downloadable', 'woocommerce-advbulkedit');
		if($arr['_downloadable'] === "Downloadable")
			$arr['_downloadable'] = __( 'Downloadable', 'woocommerce');
		$arr['_crosssell_ids'] = __( 'Cross-Sells', 'woocommerce-advbulkedit');
		if($arr['_crosssell_ids'] === "Cross-Sells")
			$arr['_crosssell_ids'] = __( 'Cross-Sells', 'woocommerce');
		$arr['_upsell_ids'] = __( 'Up-Sells', 'woocommerce-advbulkedit');
		if($arr['_upsell_ids'] === "Up-Sells")
			$arr['_upsell_ids'] = __( 'Up-Sells', 'woocommerce');
		$arr['_visibility'] = __( 'Catalog visibility:', 'woocommerce-advbulkedit');
		if($arr['_visibility'] === "Catalog visibility:")
			$arr['_visibility'] = __( 'Catalog visibility:', 'woocommerce');
		$arr['post_status'] = __( 'Status', 'woocommerce-advbulkedit');
		if($arr['post_status'] === "Status")
			$arr['post_status'] = __( 'Status', 'woocommerce');
		$arr['_purchase_note'] = __( 'Purchase Note', 'woocommerce-advbulkedit');
		if($arr['_purchase_note'] === "Purchase Note")
			$arr['_purchase_note'] = __( 'Purchase Note', 'woocommerce');
		$arr['product_shipping_class'] = __( 'Shipping class', 'woocommerce-advbulkedit');
		if($arr['product_shipping_class'] === "Shipping class")
			$arr['product_shipping_class'] = __( 'Shipping class', 'woocommerce');
		$arr['grouped_items'] = __( 'Grouping', 'woocommerce-advbulkedit');
		if($arr['grouped_items'] === "Grouping")
			$arr['grouped_items'] = __( 'Grouping', 'woocommerce');
		$arr['_sold_individually'] = __( 'Sold Individually', 'woocommerce-advbulkedit');
		if($arr['_sold_individually'] === "Sold Individually")
			$arr['_sold_individually'] = __( 'Sold Individually', 'woocommerce');
		$arr['_backorders'] = __( 'Allow Backorders?', 'woocommerce-advbulkedit');
		if($arr['_backorders'] === "Allow Backorders?")
			$arr['_backorders'] = __( 'Allow Backorders?', 'woocommerce');
		$arr['_manage_stock'] = __( 'Manage Stock', 'woocommerce-advbulkedit');
		if($arr['_manage_stock'] === "Manage Stock")
			$arr['_manage_stock'] = __( 'Manage Stock', 'woocommerce');

		$arr['_stock_status'] = __( 'Stock Status', 'woocommerce-advbulkedit');
		if($arr['_stock_status'] === "Stock Status")
			$arr['_stock_status'] = __( 'Stock Status', 'woocommerce');
		$arr['_stock'] = __( 'Stock Qty', 'woocommerce-advbulkedit');
		if($arr['_stock'] === "Stock Qty")
			$arr['_stock'] = __( 'Stock Qty', 'woocommerce');
		$arr['_length'] = __( 'Length', 'woocommerce-advbulkedit');
		if($arr['_length'] === "Length")
			$arr['_length'] = __( 'Length', 'woocommerce');
		$arr['_sale_price_dates_to'] = __( 'Sale end date:', 'woocommerce-advbulkedit');
		if($arr['_sale_price_dates_to'] === "Sale end date:")
			$arr['_sale_price_dates_to'] = __( 'Sale end date:', 'woocommerce');
		$arr['_sale_price_dates_from'] = __( 'Sale start date:', 'woocommerce-advbulkedit');
		if($arr['_sale_price_dates_from'] === "Sale start date:")
			$arr['_sale_price_dates_from'] = __( 'Sale start date:', 'woocommerce');
		$arr['_tax_class'] = __( 'Tax class', 'woocommerce-advbulkedit');
		if($arr['_tax_class'] === "Tax class")
			$arr['_tax_class'] = __( 'Tax class', 'woocommerce');
		$arr['_tax_status'] = __( 'Tax Status', 'woocommerce-advbulkedit');
		if($arr['_tax_status'] === "Tax Status")
			$arr['_tax_status'] = __( 'Tax Status', 'woocommerce');
		$arr['_featured'] = __( 'Featured', 'woocommerce-advbulkedit');
		if($arr['_featured'] === "Featured")
			$arr['_featured'] = __( 'Featured', 'woocommerce');
		$arr['_width'] = __( 'Width', 'woocommerce-advbulkedit');
		if($arr['_width'] === "Width")
			$arr['_width'] = __( 'Width', 'woocommerce');
		$arr['_height'] = __( 'Height', 'woocommerce-advbulkedit');
		if($arr['_height'] === "Height")
			$arr['_height'] = __( 'Height', 'woocommerce');
		$arr['_weight'] = __( 'Weight', 'woocommerce-advbulkedit');
		if($arr['_weight'] === "Weight")
			$arr['_weight'] = __( 'Weight', 'woocommerce');
		$arr['post_date'] = __( 'Publish Date', 'woocommerce-advbulkedit');
		if($arr['post_date'] === "Publish Date")
			$arr['post_date'] = __( 'Publish Date', 'woocommerce');
		$arr['post_author'] = __( 'Post Author', 'woocommerce-advbulkedit');
		if($arr['post_author'] === "Post Author")
			$arr['post_author'] = __( 'Post Author', 'woocommerce');
		$arr['post_type'] = __( 'Post Type', 'woocommerce-advbulkedit');
		$arr['_variation_description'] = __( 'Variation Description', 'woocommerce-advbulkedit');
		$arr['_custom_attributes'] = __( 'Custom Attributes', 'woocommerce-advbulkedit');
		$arr['trans_data_placeholder'] = __( 'choose\search', 'woocommerce-advbulkedit');
		$arr['trans_column_settings'] = __( 'Column Settings', 'woocommerce-advbulkedit');
		$arr['trans_table_views'] = __( 'Table Views', 'woocommerce-advbulkedit');
		$arr['trans_custom_fields'] = __( 'Custom Fields', 'woocommerce-advbulkedit');
		$arr['trans_find_custom_fields'] = __( 'Find Custom Fields', 'woocommerce-advbulkedit');
		$arr['trans_plugin_settings'] = __( 'Plugin Settings', 'woocommerce-advbulkedit');
		$arr['trans_main_settings'] = __( 'Main Settings', 'woocommerce-advbulkedit');
		$arr['trans_search_settings'] = __( 'Search Fields', 'woocommerce-advbulkedit');
		$arr['trans_collapse_filters'] = __( 'Collapse Filters -', 'woocommerce-advbulkedit');
		$arr['trans_expand_filters'] = __( 'Expand Filters +', 'woocommerce-advbulkedit');
		$arr['trans_images_hover'] = __( 'Show larger images on hover', 'woocommerce-advbulkedit');
		$arr['trans_straight_edit'] = __( 'Clicking on image goes straight to edit', 'woocommerce-advbulkedit');
		$arr['trans_remove_name'] = __( 'remove name', 'woocommerce-advbulkedit');
		$arr['trans_remove_value'] = __( 'remove value', 'woocommerce-advbulkedit');
		$arr['trans_sell_status'] = __( 'To change table view you need to save/revert changes first', 'woocommerce-advbulkedit');
		$arr['trans_selected_text'] = __( "Selected rows for bulk editing", "woocommerce-advbulkedit");
		$arr['trans_saving_batch'] = __( "Saving batch", "woocommerce-advbulkedit");
		$arr['trans_show_sell'] = __( 'Show Selected Only', 'woocommerce-advbulkedit');
		$arr['trans_show_all'] = __( 'Show All', 'woocommerce-advbulkedit');
		$arr['trans_linkallvars'] =  __( 'Link all variations', 'woocommerce-advbulkedit');
		$arr['trans_skipduplicates'] =  __( 'Skip duplicate variations on creation', 'woocommerce-advbulkedit');
		$arr['trans_instock'] = __( 'In Stock', 'woocommerce-advbulkedit');
		if($arr['trans_instock'] === "In Stock")
			$arr['trans_instock'] = __( 'In Stock', 'woocommerce');
		$arr['trans_outofstock'] = __( 'Out of stock', 'woocommerce-advbulkedit');
		if($arr['trans_outofstock'] === "Out of stock")
			$arr['trans_outofstock'] = __( 'Out of stock', 'woocommerce');	
		
	}
	
	public function showMainPage()
	{
		global $wpdb;
		global $woocommerce;
		$skiploadfrontpage = true;
		if(isset($woocommerce) && property_exists($woocommerce,'version'))
		{
			$version = (double)$woocommerce->version;
			if($version > 2.6)
				$this->isversion3 = true;
		}
		if(function_exists('icl_object_id') || has_filter('wpml_object_id'))
		{
			if(ICL_LANGUAGE_CODE != 'all')
			{
				$this->iswpml = true;
			}
			
		}
		
		$this->loadAttributes($skiploadfrontpage);
		$sel_fields = array();
		$sel_fields = get_option('w3exabe_columns');
		$purl = plugin_dir_url(__FILE__);
		
//		for($i = 3200; $i < 22000; $i++)
//		{
//			$my_post = array(
//			  'post_title'    => 'post ua '.$i,
//			  'post_status'   => 'publish'
//			);
//			 
//			// Insert the post into the database
//			wp_insert_post( $my_post );
//		}
		if(is_rtl())
		{
			echo '<style>
					.w3exabe input,textarea {
						direction: rtl !important;
					}
					.w3exabe div.slick-cell {
						direction: rtl !important;
					}
				</style>';
		}
		echo "<script>
		var W3Ex = W3Ex || {};
		W3Ex.attributes =  {};
		W3Ex.attributes_mapped =  {};
		W3Ex.attributes_slugs_mapped =  {};
		W3Ex.attr_cols =  {};
		W3Ex.categories =  [];
		W3Ex._translate_strings = {};
		W3Ex._global_settings = {};
		W3Ex.imagepath = '".plugin_dir_url(__FILE__)."';";
		if(is_rtl())
		{
			echo PHP_EOL;
			echo 'W3Ex._isrtlenabled = true;';
		}
		if(function_exists('icl_object_id') || has_filter('wpml_object_id'))
		{
			if(ICL_LANGUAGE_CODE != 'all')
			{
				echo PHP_EOL;
				echo 'W3Ex._iswpmlenabled = 1;';
				
			}
			
		}
		echo PHP_EOL;
		if($this->isversion3 )
		{
			echo 'W3Ex._isversion3 = 1;';
			echo PHP_EOL;
		}
		
		$upload_dir = wp_upload_dir();
		if(is_array($upload_dir) && isset($upload_dir['baseurl']))
		{
			$upload_dir = $upload_dir['baseurl'];
			echo 'W3Ex.uploaddir = "'. $upload_dir .'";';
		}
		echo PHP_EOL;
		if(defined(WC_DELIMITER))
		{
			echo 'W3Ex._w3ex_wc_delimiter = "'. WC_DELIMITER .'";';
		}else
		{
			echo 'W3Ex._w3ex_wc_delimiter = "|";';
		}
		echo PHP_EOL;
		if($skiploadfrontpage)
		{
			foreach($this->attributes as $attr)
			{
		 		foreach($attr->values as $value)
				{
					$attrname = str_replace("\n", '\n', str_replace('"', '\"', addcslashes(str_replace("\r", '', (string)$attr->name), "\0..\37'\\")));
					$attrname = trim(preg_replace('/\s+/', ' ', $attrname));
					$attrslug = $value->slug;
					$attrslug = trim(preg_replace('/\s+/', ' ', $attrslug));
					$attrvalname = str_replace("\n", '\n', str_replace('"', '\"', addcslashes(str_replace("\r", '', (string)$value->name), "\0..\37'\\")));
					$attrvalname = trim(preg_replace('/\s+/', ' ', $attrvalname));
					echo 'W3Ex.attributes['.$value->id.'] = {id:'.$value->id.',term_id:'.$value->term_id.',name:"'.$attrvalname.'",attr:"'.$attrname.'",value:"'.$attrslug.'"};';
					echo PHP_EOL;
					echo 'W3Ex.attributes_mapped['.$value->term_id.'] = '.$value->id.';';
					echo PHP_EOL;
					echo 'W3Ex.attributes_slugs_mapped["'.$value->slug.$attr->name.'"] = '.$value->id.';';
					echo PHP_EOL;
				}
			}
		}
		foreach($this->attributes as $attr)
		{
			$attrname = $attr->name;
			$attrname = trim(preg_replace('/\s+/', ' ', $attrname));
//			$attrlabel = str_replace('"','\"',$attr->label);
			$attrlabel = str_replace("\n", '\n', str_replace('"', '\"', addcslashes(str_replace("\r", '', (string)$attr->label), "\0..\37'\\")));
			$attrlabel = trim(preg_replace('/\s+/', ' ', $attrlabel));
			echo 'W3Ex.attr_cols["'.$attrname.'"] = {id:'.$attr->id.',attr:"'.$attrlabel.'",value:"'.$attrname.'"};';
			echo PHP_EOL;
		}
		$blogusers = get_users( array( 'role' => 'vendor', 'fields' => array( 'ID', 'display_name' ) ));
		$blogusers1 = get_users( array( 'role' => 'administrator', 'fields' => array( 'ID', 'display_name' ) ));
		$blogusers = array_merge($blogusers,$blogusers1);
		$blogusers1 = get_users( array( 'role' => 'shop_manager', 'fields' => array( 'ID', 'display_name' ) ));
		$blogusers = array_merge($blogusers,$blogusers1);
		$blogusers1 = get_users( array( 'role' => 'seller', 'fields' => array( 'ID', 'display_name' ) ));
		$blogusers = array_merge($blogusers,$blogusers1);

		$settings = get_option('w3exabe_settings');
		if(!is_array($settings))
			$settings = array();
		if(is_array($sel_fields) && !empty($sel_fields))
		{
			echo 'W3Ex.colsettings = '. json_encode($sel_fields). ';';
		    echo PHP_EOL;
		}
		$sel_fields = get_option('w3exabe_views');
		if(is_array($sel_fields) && !empty($sel_fields))
		{
			echo 'W3Ex.w3exabe_listviews = '. json_encode($sel_fields). ';';
		    echo PHP_EOL;
		}
		$sel_fields = get_option('w3exabe_customsel');
		if(is_array($sel_fields) && !empty($sel_fields))
		{
			echo 'W3Ex.customfieldssel = '. json_encode($sel_fields). ';';
		    echo PHP_EOL;
		}
		
		$sel_fields = get_option('w3exabe_custom');
		if(is_array($sel_fields) && !empty($sel_fields))
		{
			echo 'W3Ex.customfields = '. json_encode($sel_fields). ';';
		    echo PHP_EOL;
		}
		$optionc = get_option('woocommerce_tax_classes');
		if(is_string($optionc))
		{
			$optionc = trim($optionc);
//			$optionc = str_replace("\n",",",$optionc);
			$classes = array_filter( array_map( 'trim', explode( "\n", $optionc ) ) );
			$classtring = "Standard";
			foreach($classes as $class)
			{
				$classtring.= ','.$class;
			}
			echo 'W3Ex._tax_class_values = "'.$classtring.'";'; echo PHP_EOL;
		}
		
//			$settings = get_option('w3exabe_settings');
//			if(is_array($settings))
			{
				if(isset($settings['tableheight']) && is_numeric($settings['tableheight']))
				{	
					echo 'W3Ex._w3esetting_table_height = "'.$settings['tableheight'].'";'; echo PHP_EOL;
				}
//				if(isset($settings['searchfiltersheight']) && is_numeric($settings['searchfiltersheight']))
//				{	
//					echo 'W3Ex._w3esetting_filter_height = "'.$settings['searchfiltersheight'].'";'; echo PHP_EOL;
//				}
				if(isset($settings['disablesafety']) && is_numeric($settings['disablesafety']))
				{	
					if($settings['disablesafety'] == 1)
					 echo 'W3Ex._w3esetting_disablesafety = true;'; echo PHP_EOL;
				}
				if(isset($settings['showthumbnails']))
				{	
					if($settings['showthumbnails'] == 1)
					 	echo 'W3Ex._global_settings["showthumbnails"] = true;'; echo PHP_EOL;
				}
				if(isset($settings['openimage']))
				{	
					if($settings['openimage'] == 1)
					 	echo 'W3Ex._global_settings["openimage"] = true;'; echo PHP_EOL;
				}
				if(isset($settings['usebuiltineditor']))
				{	
					if($settings['usebuiltineditor'] == 1)
					 	echo 'W3Ex._global_settings["usebuiltineditor"] = true;'; echo PHP_EOL;
				}
				if(isset($settings['filterstate']))
				{	
					if($settings['filterstate'] == 1)
					 	echo 'W3Ex._global_settings["filterstate"] = true;'; echo PHP_EOL;
				}
				if(isset($settings['savebatch']) && is_numeric($settings['savebatch']))
				{	
					echo 'W3Ex._global_settings["savebatch"] = "'.$settings['savebatch'].'";'; echo PHP_EOL;
				}
			}
			if ( is_plugin_active( 'woocommerce-cost-of-goods/woocommerce-cost-of-goods.php' ) ) 
			{
			    $settings['iswoocostog'] = 1;
			    update_option('w3exabe_settings',$settings);
			}else
			{
				if(isset($settings['iswoocostog']) && $settings['iswoocostog'] == 1)
				{
					unset($settings['iswoocostog']);
			   		update_option('w3exabe_settings',$settings);
				}
			} 
			$arrTranslated = array();
			$this->loadTranslations($arrTranslated);
			echo 'W3Ex._translate_strings["trans_linkallvars"] = "'.self::relpaceInvalid($arrTranslated['trans_linkallvars']).'";'; echo PHP_EOL;
			echo 'W3Ex._translate_strings["trans_skipduplicates"] = "'.self::relpaceInvalid($arrTranslated['trans_skipduplicates']).'";'; echo PHP_EOL;
			echo 'W3Ex._translate_strings["trans_column_settings"] = "'.self::relpaceInvalid($arrTranslated['trans_column_settings']).'";'; echo PHP_EOL;
			echo 'W3Ex._translate_strings["trans_selected_text"] = "'.self::relpaceInvalid($arrTranslated['trans_selected_text']).'";'; echo PHP_EOL;
			echo 'W3Ex._translate_strings["trans_saving_batch"] = "'.self::relpaceInvalid($arrTranslated['trans_saving_batch']).'";'; echo PHP_EOL;
			echo 'W3Ex._translate_strings["trans_table_views"] = "'.self::relpaceInvalid($arrTranslated['trans_table_views']).'";'; echo PHP_EOL;
			echo 'W3Ex._translate_strings["trans_custom_fields"] = "'.self::relpaceInvalid($arrTranslated['trans_custom_fields']).'";'; echo PHP_EOL;
			echo 'W3Ex._translate_strings["trans_find_custom_fields"] = "'.self::relpaceInvalid($arrTranslated['trans_find_custom_fields']).'";'; echo PHP_EOL;
			echo 'W3Ex._translate_strings["trans_plugin_settings"] = "'.self::relpaceInvalid($arrTranslated['trans_plugin_settings']).'";'; echo PHP_EOL;
			echo 'W3Ex._translate_strings["trans_collapse_filters"] = "'.self::relpaceInvalid($arrTranslated['trans_collapse_filters']).'";'; echo PHP_EOL;
			echo 'W3Ex._translate_strings["trans_expand_filters"] = "'.self::relpaceInvalid($arrTranslated['trans_expand_filters']).'";'; echo PHP_EOL;
			echo 'W3Ex._translate_strings["trans_images_hover"] = "'.self::relpaceInvalid($arrTranslated['trans_images_hover']).'";'; echo PHP_EOL;
			echo 'W3Ex._translate_strings["trans_straight_edit"] = "'.self::relpaceInvalid($arrTranslated['trans_straight_edit']).'";'; echo PHP_EOL;
			echo 'W3Ex._translate_strings["trans_data_placeholder"] = "'.self::relpaceInvalid($arrTranslated['trans_data_placeholder']).'";'; echo PHP_EOL;
			echo 'W3Ex._translate_strings["trans_sell_status"] = "'.self::relpaceInvalid($arrTranslated['trans_sell_status']).'";'; echo PHP_EOL;
			echo 'W3Ex._translate_strings["trans_show_sell"] = "'.self::relpaceInvalid($arrTranslated['trans_show_sell']).'";'; echo PHP_EOL;
			echo 'W3Ex._translate_strings["trans_show_all"] = "'.self::relpaceInvalid($arrTranslated['trans_show_all']).'";'; echo PHP_EOL;
			echo 'W3Ex.post_excerpt = "'. self::relpaceInvalid($arrTranslated['post_excerpt']).'";'; echo PHP_EOL;
			echo 'W3Ex.post_content = "'.self::relpaceInvalid($arrTranslated['post_content']).'";'; echo PHP_EOL;
			echo 'W3Ex._thumbnail_id = "'.self::relpaceInvalid($arrTranslated['_thumbnail_id']).'";'; echo PHP_EOL;
			echo 'W3Ex._product_image_gallery = "'.self::relpaceInvalid($arrTranslated['_product_image_gallery']).'";'; echo PHP_EOL;
			echo 'W3Ex._sku = "'.self::relpaceInvalid($arrTranslated['_sku']).'";'; echo PHP_EOL;
			echo 'W3Ex.post_name = "'.self::relpaceInvalid($arrTranslated['post_name']).'";'; echo PHP_EOL;
			echo 'W3Ex.product_tag = "'.self::relpaceInvalid($arrTranslated['product_tag']).'";'; echo PHP_EOL;
			echo 'W3Ex._virtual = "'.self::relpaceInvalid($arrTranslated['_virtual']).'";'; echo PHP_EOL;
			echo 'W3Ex._downloadable = "'.self::relpaceInvalid($arrTranslated['_downloadable']).'";'; echo PHP_EOL;
			echo 'W3Ex.instock = "'.self::relpaceInvalid(__( 'In stock', 'woocommerce')).'";'; echo PHP_EOL;
			echo 'W3Ex.outofstock = "'.self::relpaceInvalid(__( 'Out of stock', 'woocommerce')).'";'; echo PHP_EOL;
			echo 'W3Ex.post_title = "'.self::relpaceInvalid($arrTranslated['post_title']).'";'; echo PHP_EOL;
			echo 'W3Ex.product_cat = "'.self::relpaceInvalid($arrTranslated['product_cat']).'";'; echo PHP_EOL;
			echo 'W3Ex._regular_price = "'.self::relpaceInvalid($arrTranslated['_regular_price']).'";'; echo PHP_EOL;
			echo 'W3Ex._sale_price = "'.self::relpaceInvalid($arrTranslated['_sale_price']).'";'; echo PHP_EOL;
			echo 'W3Ex._sale_price_dates_from = "'.self::relpaceInvalid($arrTranslated['_sale_price_dates_from']).'";'; echo PHP_EOL;
			echo 'W3Ex._sale_price_dates_to = "'.self::relpaceInvalid($arrTranslated['_sale_price_dates_to']).'";'; echo PHP_EOL;
			echo 'W3Ex._featured = "'.self::relpaceInvalid($arrTranslated['_featured']).'";'; echo PHP_EOL;
			echo 'W3Ex._tax_status = "'.self::relpaceInvalid($arrTranslated['_tax_status']).'";'; echo PHP_EOL;
			echo 'W3Ex._tax_class = "'.self::relpaceInvalid($arrTranslated['_tax_class']).'";'; echo PHP_EOL;
			echo 'W3Ex._weight = "'.self::relpaceInvalid($arrTranslated['_weight']).'";'; echo PHP_EOL;
			echo 'W3Ex._height = "'.self::relpaceInvalid($arrTranslated['_height']).'";'; echo PHP_EOL;
			echo 'W3Ex._width = "'.self::relpaceInvalid($arrTranslated['_width']).'";'; echo PHP_EOL;
			echo 'W3Ex._length = "'.self::relpaceInvalid($arrTranslated['_length']).'";'; echo PHP_EOL;
			echo 'W3Ex._stock = "'.self::relpaceInvalid($arrTranslated['_stock']).'";'; echo PHP_EOL;
			echo 'W3Ex._stock_status  = "'.self::relpaceInvalid($arrTranslated['_stock_status']).'";'; echo PHP_EOL;
			echo 'W3Ex._manage_stock = "'.self::relpaceInvalid($arrTranslated['_manage_stock']).'";'; echo PHP_EOL;
			echo 'W3Ex._backorders = "'.self::relpaceInvalid($arrTranslated['_backorders']).'";'; echo PHP_EOL;
			echo 'W3Ex._sold_individually = "'.self::relpaceInvalid($arrTranslated['_sold_individually']).'";'; echo PHP_EOL;
			echo 'W3Ex.product_shipping_class = "'.self::relpaceInvalid($arrTranslated['product_shipping_class']).'";'; echo PHP_EOL;
			echo 'W3Ex._purchase_note = "'.self::relpaceInvalid($arrTranslated['_purchase_note']).'";'; echo PHP_EOL;
			echo 'W3Ex.post_status = "'.self::relpaceInvalid($arrTranslated['post_status']).'";'; echo PHP_EOL;
			echo 'W3Ex._visibility = "'.self::relpaceInvalid($arrTranslated['_visibility']).'";'; echo PHP_EOL;
			echo 'W3Ex._upsell_ids = "'.self::relpaceInvalid($arrTranslated['_upsell_ids']).'";'; echo PHP_EOL;
			echo 'W3Ex._crosssell_ids = "'.self::relpaceInvalid($arrTranslated['_crosssell_ids']).'";'; echo PHP_EOL;
			echo 'W3Ex._downloadable = "'.self::relpaceInvalid($arrTranslated['_downloadable']).'";'; echo PHP_EOL;
			echo 'W3Ex._virtual = "'.self::relpaceInvalid($arrTranslated['_virtual']).'";'; echo PHP_EOL;
			echo 'W3Ex._download_expiry = "'.self::relpaceInvalid($arrTranslated['_download_expiry']).'";'; echo PHP_EOL;
			echo 'W3Ex._download_limit = "'.self::relpaceInvalid($arrTranslated['_download_limit']).'";'; echo PHP_EOL;
			echo 'W3Ex._downloadable_files = "'.self::relpaceInvalid($arrTranslated['_downloadable_files']).'";'; echo PHP_EOL;
			echo 'W3Ex._download_type = "'.self::relpaceInvalid($arrTranslated['_download_type']).'";'; echo PHP_EOL;
			echo 'W3Ex._product_url = "'.self::relpaceInvalid($arrTranslated['_product_url']).'";'; echo PHP_EOL;
			echo 'W3Ex._button_text = "'.self::relpaceInvalid($arrTranslated['_button_text']).'";'; echo PHP_EOL;
			echo 'W3Ex.comment_status = "'.self::relpaceInvalid($arrTranslated['comment_status']).'";'; echo PHP_EOL;
			echo 'W3Ex.menu_order = "'.self::relpaceInvalid($arrTranslated['menu_order']).'";'; echo PHP_EOL;
			echo 'W3Ex.product_type = "'.self::relpaceInvalid($arrTranslated['product_type']).'";'; echo PHP_EOL;
			echo 'W3Ex._default_attributes = "'.self::relpaceInvalid($arrTranslated['_default_attributes']).'";'; echo PHP_EOL;
			echo 'W3Ex.grouped_items = "'.self::relpaceInvalid($arrTranslated['grouped_items']).'";'; echo PHP_EOL;
			echo 'W3Ex.post_date = "'.self::relpaceInvalid($arrTranslated['post_date']).'";'; echo PHP_EOL;
			echo 'W3Ex._custom_attributes = "'.self::relpaceInvalid($arrTranslated['_custom_attributes']).'";'; echo PHP_EOL;
			echo 'W3Ex._variation_description = "'.self::relpaceInvalid($arrTranslated['_variation_description']).'";'; echo PHP_EOL;
		echo "</script>";
		?>
		<script type="text/javascript">
                   if(jQuery.fn.select2 === undefined)
                   {
				   	   jQuery.fn.extend({
						    select2: function () {
						    	//dummy function to prevent code from morons
								return;
						    }
						});
				   }
                  
         </script>
		<div class="wrap w3exabe">
		<!--<div id="w3exibaparent">-->
		<div class="infomessage">
			<div class="background">
			    <div class="content">
			        <p>
			        	Duplicate SKU(s) detected.
			        </p>
			    </div>
			</div>
			<span class="icon"></span>
		</div>
		<h2><?php _e( 'Advanced Bulk Edit', 'woocommerce-advbulkedit');?></h2>
		<br/>
			<div id="frontpageinfoholder" style="position:relative;"></div>
			<!--<input id="showhidecustom" class="button" type="button" value="<?php _e("Save Changes","woocommerce-advbulkedit"); ?>" />-->
			<br />
			<!--<div id="searchfilterswrapper" style="max-height:350px; overflow: auto;border: 1px solid #808080;border-radius: 7px;padding:7px;">-->
			
			<button id="collapsefilters" class="button" data-state="collapse"><?php _e( 'Collapse Filters -', 'woocommerce-advbulkedit');?></button>
			<input id="searchfilters" type="text" style="width:150px;" placeholder="search filters"></input>
			<table cellpadding="5" cellspacing="0" id="tablesearchfilters" style="z-index: 12;overflow-y: auto;border: 1px solid #808080;border-radius: 7px;padding:7px;">
			<tbody>
			<tr>
			<td>
			<?php echo $arrTranslated['post_title']; ?>: </td>
			<td data-id="post_title">
			<select id="titleparams">
				<option value="con"><?php _e( 'contains', 'woocommerce-advbulkedit');?></option>
				<option value="isexactly"><?php _e( 'is exactly', 'woocommerce-advbulkedit');?></option>
				<option value="notcon"><?php _e( 'does not contain', 'woocommerce-advbulkedit');?></option>
				<option value="start"><?php _e( 'starts with', 'woocommerce-advbulkedit');?></option>
				<option value="end"><?php _e( 'ends with', 'woocommerce-advbulkedit');?></option>
			</select>
			<input id="titlevalue" type="text" class="showorcheckbox"/>
			</td>
			<td>
			<?php echo $arrTranslated['product_cat']; ?>: </td><td><select id="selcategory" class="makechosen catsel" data-placeholder="<?php echo $arrTranslated['trans_data_placeholder']; ?>" multiple style="width:250px;">
			 <option value=""></option>
			<?php
				$cats = $this->categories;
				$newcats = array();
				$cats_asoc = $this->cat_asoc;
				$depth = array();

			    foreach($cats as $cat)
				{
					if($cat->category_parent == 0)
					{
						$depth[$cat->term_id] = 0;
						$newcats[] = $cat;
					}
				}
				foreach($cats as $cat)
				{
					if($cat->category_parent == 0) continue;
					{
//						if(!isset($options[$cat->category_id]))
						{
							if(!isset($depth[$cat->term_id]))
							{
								$loop = true;
								$counter = 0;
								while($loop && ($counter < 1000))
								{
									foreach($cats as $catin)
									{
										if($catin->category_parent == 0)
										   continue;
										if(isset($depth[$catin->category_parent]))
										{
											$newdepth = $depth[$catin->category_parent];
											$newdepth++;
											if(!isset($depth[$catin->term_id]))
											{
												$depth[$catin->term_id] = $newdepth;
												for($i = 0; $i < count($newcats); $i++)
												{
													$catins = $newcats[$i];
													if($catins->term_id == $catin->category_parent)
													{
														array_splice($newcats, $i+1, 0,array($catin));
														break;
													}
												}
											}

											if($catin->term_id == $cat->term_id)
											{
												$loop = false;
												break;
											}
										}
									}
									$counter++;
								}
								if(!isset($depth[$cat->term_id]))
								{
									$depth[$cat->term_id] = 0;
									$newcats[] = $cat;
								}
							}
						}
					}
					
				}
				
				if(count($newcats) == count($cats))
				{
					foreach($newcats as $catin)
					{
						$depthstring = '';
						if(isset($depth[$catin->term_id]))
						{
							$depthn = (int)$depth[$catin->term_id];
							if($depthn < 15)
							{
								while($depthn > 0)
								{
									$depthstring = $depthstring.'&nbsp;&nbsp;&nbsp;';
//									$depthstring = $depthstring.'&#09; ';
									$depthn--;
								}
								
							}
						}
						echo '<option value="'.$catin->category_id.'" >'.$depthstring.$catin->category_name.'</option>';
					}
				}else
				{
					foreach($cats as $catin)
					{
						echo '<option value="'.$catin->category_id.'" >'.$catin->category_name.'</option>';
					}
				}
				echo '<option value="none" >Uncategorized</option>';
				
		
			?>
			</select>&nbsp;<label><input type="checkbox" id="categoryor" style="width:auto;">AND</input></label>
			</td></tr>
			<?php
				$endrow = false;
				$counter = 0;
				$settings = get_option('w3exabe_settings');
				$showattrs = "";
				if(is_array($settings))
				{
					if(isset($settings['showattributes']))
					{
						if($settings['showattributes'] == 0)
						{
							$showattrs = 'style="display: none"';
						}
					}
				}
				if(count($this->attributes) > 0)
				{
					foreach($this->attributes as $attr)
					{
						if($counter % 2 == 0)
						{
							echo '<tr class="showattributes" '.$showattrs.'><td>';
						}else
						{
							echo '<td>';
						}
						echo $attr->label.': </td><td><select class="makechosen custattributes" data-placeholder="'.$arrTranslated['trans_data_placeholder'].'" multiple style="width:250px;" data-attrslug="attribute_pa_'.$attr->name.'"> <option value=""></option>';
						
						foreach($attr->values as $value)
						{
							echo '<option value="'.$value->id.'">'.$value->name.'</option>';
						}
						echo '</select>';
						if($counter % 2 == 0)
						{
							$endrow = false;
							echo '</td>';
						}else
						{
							$endrow = true;
							echo '</td></tr>';
						}
						$counter++;					
				    }
					if(!$endrow)
					{
						echo '</tr>';
					}
				}
//				_e( 'Sale Price', 'wooadvbulkedit');
			?>
			<tr class="showprices"
			<?php
				if(is_array($settings))
				{
					if(isset($settings['showprices']))
					{
						if($settings['showprices'] == 0)
						{
							echo 'style="display: none"';
						}
					}
				}
			?>
			>
				<td><?php echo $arrTranslated['_regular_price']; ?>: </td>
				<td>
				<select id="price">
					<option value="more">></option>
					<option value="less"><</option>
					<option value="equal">==</option>
					<option value="moree">>=</option>
					<option value="lesse"><=</option>
				</select>
				<input id="pricevalue" type="text"/>
			</td>
				<td><?php echo $arrTranslated['_sale_price']; ?>: </td>
				<td>
				<select id="saleprice">
					<option value="more">></option>
					<option value="less"><</option>
					<option value="equal">==</option>
					<option value="moree">>=</option>
					<option value="lesse"><=</option>
				</select>
				<input id="salepricevalue" type="text"/>
			</td>
			</tr>
			<tr class="showskutags"
			<?php
				if(is_array($settings))
				{
					if(isset($settings['showskutags']))
					{
						if($settings['showskutags'] == 0)
						{
							echo 'style="display: none"';
						}
					}
				}
			?>
			>
				<td><?php echo $arrTranslated['_sku']; ?>: </td>
				<td>
				<select id="skuparams">
				<option value="con"><?php _e( 'contains', 'woocommerce-advbulkedit');?></option>
				<option value="isexactly"><?php _e( 'is exactly', 'woocommerce-advbulkedit');?></option>
				<option value="notcon"><?php _e( 'does not contain', 'woocommerce-advbulkedit');?></option>
				<option value="start"><?php _e( 'starts with', 'woocommerce-advbulkedit');?></option>
				<option value="end"><?php _e( 'ends with', 'woocommerce-advbulkedit');?></option>
			</select>
			<input id="skuvalue" type="text" class="showmultiplecheckbox"/>
			</td>
				<td><?php echo $arrTranslated['product_tag']; ?>: </td>
				<td>
					<select id='tagsparams' class="makechosen paramsvalues" data-placeholder="<?php echo $arrTranslated['trans_data_placeholder']; ?>" multiple style="width:250px;"> <option value=""></option>';
						<?php
						$args = array(
							    'number'     => 99999,
							    'orderby'    => 'slug',
							    'order'      => 'ASC',
							    'hide_empty' => false,
							    'include'    => '',
								'fields'     => 'all'
							);
//							$tagcount = wp_count_terms( 'product_tag', $args );
//							if($tagcount < 2000)
							{
//								$woo_tags = get_terms( 'product_tag', $args );
								$getquery = "SELECT t.name,tt.term_taxonomy_id FROM {$wpdb->prefix}terms as t INNER JOIN {$wpdb->prefix}term_taxonomy AS tt ON t.term_id= tt.term_id WHERE tt.taxonomy IN('product_tag')";
								$woo_tags = $wpdb->get_results($getquery);
								if(!is_wp_error($woo_tags) && is_array($woo_tags))
								{
									foreach($woo_tags as $tag)
									{
									   if(!is_object($tag)) continue;
									   if(!property_exists($tag,'term_taxonomy_id')) continue;
									   if(!property_exists($tag,'name')) continue;
									   if($this->iswpml)
										{
										   if(ICL_LANGUAGE_CODE !== 'all')
										   {
										   	   $id = self::lang_category_id($tag->term_taxonomy_id,'product_tag');
											   if($id === NULL || $id != $tag->term_taxonomy_id)
											   		continue;
										   }
									    }
									   echo '<option value="'.$tag->term_taxonomy_id.'" >'.$tag->name.'</option>';
								   /*$cat = new stdClass();
								   $cat->category_id     = $category->term_taxonomy_id;
								   $cat->term_id         = $category->term_id;
								   $cat->category_name   = $category->name;
								   $cat->category_slug   = urldecode($category->slug);
								   $cat->category_parent = $category->parent;
								   $this->categories[] = $cat;   
								   $this->cat_asoc[$cat->category_id] = $cat;*/
									};
								}
							}
							
							/*foreach($attr->values as $value)
							{
								echo '<option value="'.$value->id.'">'.$value->name.'</option>';
							}*/
						?>
					</select>
				</td>
			</tr>
			<tr class="showdescriptions"
			<?php
				$echovar = 'style="display: none"';
				if(is_array($settings))
				{
					if(isset($settings['showdescriptions']))
					{
						if($settings['showdescriptions'] == 1)
						{
							$echovar = "";
						}
					}
				}
				echo $echovar;
			?>
			>
				<td><?php echo $arrTranslated['post_content']; ?>: </td>
				<td data-id="post_content">
				<select id="descparams">
				<option value="con"><?php _e( 'contains', 'woocommerce-advbulkedit');?></option>
				<option value="notcon"><?php _e( 'does not contain', 'woocommerce-advbulkedit');?></option>
				<option value="start"><?php _e( 'starts with', 'woocommerce-advbulkedit');?></option>
				<option value="end"><?php _e( 'ends with', 'woocommerce-advbulkedit');?></option>
			</select>
			<input id="descvalue" type="text" class="showorcheckbox"/>
			</td>
				<td><?php echo $arrTranslated['post_excerpt']; ?>: </td>
				<td data-id="post_excerpt">
				<select id="shortdescparams">
				<option value="con"><?php _e( 'contains', 'woocommerce-advbulkedit');?></option>
				<option value="notcon"><?php _e( 'does not contain', 'woocommerce-advbulkedit');?></option>
				<option value="start"><?php _e( 'starts with', 'woocommerce-advbulkedit');?></option>
				<option value="end"><?php _e( 'ends with', 'woocommerce-advbulkedit');?></option>
			</select>
			<input id="shortdescvalue" type="text" class="showorcheckbox"/>
			</td>
			</tr>
			<tr class="showstocksearch"
			<?php
				$echovar = 'style="display: none"';
				if(is_array($settings))
				{
					if(isset($settings['showstocksearch']))
					{
						if($settings['showstocksearch'] == 1)
						{
							$echovar = "";
						}
					}
				}
				echo $echovar;
			?>
			>
				<td><?php echo $arrTranslated['_stock']; ?>: </td>
				<td>
				<select id="stockqtyparams">
					<option value="more">></option>
					<option value="less"><</option>
					<option value="equal">==</option>
					<option value="moree">>=</option>
					<option value="lesse"><=</option>
				</select>
				<input id="stockqtyvalue" type="text"/>
			</td>
				<td>&nbsp;</td>
				<td>
				&nbsp;
			<!--<input id="shortdescvalue" type="text"/>-->
			</td>
			</tr>
			<!--<tr class="showidsearch"
				<?php
					$echovar = 'style="display: none"';
					if(is_array($settings))
					{
						if(isset($settings['showidsearch']))
						{
							if($settings['showidsearch'] == 1)
							{
								$echovar = "";
							}
						}
					}
					echo $echovar;
				?>
				>
					<td><?php _e( 'ID', 'woocommerce');?>: </td>
					<td>
					<input id="idvalue" type="text"/>
					</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
				</tr>-->
			</tbody>
			</table>
			<!--</div>-->
			<br/><br/><br/>
			<div id="loadsavediv">
			 <button id="getproducts" class="button" type="button">
			   <span class="icon-download-1"></span>
				<?php _e("Get Products","woocommerce-advbulkedit"); ?>
			 </button>
			 &nbsp;&nbsp;
			  <label><input id="getvariations" type="checkbox" <?php 
				if(is_array($settings))
				{
					if(isset($settings['isvariations']))
					{
						if($settings['isvariations'] == 1)
						{
							echo 'checked=checked';
						}
					}else
					{
						echo 'checked=checked';
					}
				}else
				{
					echo 'checked=checked';
				}
			  ?>/><?php _e( 'Variations', 'woocommerce');?></label>
			  
			   <button id="savechanges" class="button" type="button">
			   <span class="icon-floppy"></span>
				<?php _e("Save Changes","woocommerce-advbulkedit"); ?>
				</button>
			 		 <div style="display: inline-block;position: relative;width:320px;">
			 		 <img id="showsavetool" src="<?php echo plugin_dir_url(__FILE__);?>images/help18x18.png"/>
					<div id="savenote"> <?php _e("Changes are saved on going to a different page of products, adding products/variations or via the 'Save Changes' button","woocommerce-advbulkedit"); ?></div>
					</div>
			</div>
			<br /><br />
			<div style="position: relative;" id="mainbuttons">
			 <!--<button id="bulkedit">Bulk Edit</button>-->
			 <input id="settings" class="button-primary-copied" type="button" value="<?php _e( "Show/Hide Fields", "woocommerce-advbulkedit"); ?>" />
			 <div id="addprodarea">
				<button id="addprodbut" class="button" type="button">
				<span class="icon-plus-outline"></span>
				<?php echo $this->mb_ucfirst(__( "add", "woocommerce-advbulkedit"));?>
				</button>
			</div>
			<div id="duplicateprodarea">
				<button id="duplicateprodbut" class="button" type="button">
				<span class="icon-layers"></span>
				<?php _e( "Duplicate", "woocommerce-advbulkedit");?>
				</button>
			</div>
			<div id="deletearea">
				<button id="deletebut" class="button" type="button">
			<span class="icon-trash"></span>
			<?php echo $this->mb_ucfirst(__( "delete", "woocommerce-advbulkedit"));?>
			</button>
			</div>
			<input id="selectedit" class="button" type="button" value="<?php
_e( "Selection Manager", "woocommerce-advbulkedit");
?>" />
			<button id="bulkedit" class="button" type="button">
			<span class="icon-edit"></span>
			<?php echo _e( "Bulk Edit", "woocommerce-advbulkedit");?>
			</button>
			<!--<div style="display: inline-block;"><i class="icon-edit"></i></div>-->
			 <div id="quicksettingsarea">
				<input id="quicksettingsbut" class="button" type="button" value="<?php
_e( "Quick Settings", "woocommerce-advbulkedit");
?>" />
			</div>
			<div id="bulkedittext" style="display: inline-block;"><?php _e( "Selected rows for bulk editing", "woocommerce-advbulkedit"); ?>:<!--<input id="showselectedbut" class="button" type="button" value="Show Selected" />--></div><div id="bulkeditinfo"> 0 of 0</div>
			</div>
			<div style="position:relative">
				<div style="width:100%;">
				    <div id="myGrid" style="width:100%;height:80vh;"></div>
				</div>
			</div>
			<div id="pagingholder" style="position:relative;">
			<input id="gotopage" class="button" type="button" value="<?php _e( "First", "woocommerce-advbulkedit"); ?>" /><input id="butprevious" class="button" type="button" value="<?php _e( "Previous", "woocommerce-advbulkedit"); ?>" /> <?php _e( "Page", "woocommerce-advbulkedit"); ?>:<input id="gotopagenumber" type="text" value="1" style="width:15px;" readonly/> 	<input id="butnext" class="button" type="button" value="<?php _e( "Next", "woocommerce-advbulkedit"); ?>" /> <?php _e( "Total records", "woocommerce-advbulkedit"); ?>: <div id="totalrecords" style="display:inline-block;padding:0px 6px;"></div><div id="totalpages" style="display:inline-block;"></div><div id="viewingwhich" style="display:inline-block;padding:0px 6px;"></div></div> <br /><br />
			<div id="revertinfo"><?php _e( "Revert to original vaue", "woocommerce-advbulkedit"); ?></div> 
			<!--<input id="revertcell" class="button" type="button" value="<?php _e( "Active Cell", "woocommerce-advbulkedit"); ?>" />
			<input id="revertrow" class="button" type="button" value="<?php _e( "Active Row", "woocommerce-advbulkedit"); ?>" />-->
			<input id="revertselected" class="button" type="button" value="<?php _e( "Selected Rows", "woocommerce-advbulkedit"); ?>" />
			<input id="revertall" class="button" type="button" value="<?php _e( "All Rows", "woocommerce-advbulkedit"); ?>" />
			<br /><br /><br />
			
			
			<input id="viewdialogbut" class="button" type="button" value="<?php _e( "Load/Save View", "woocommerce-advbulkedit"); ?>" />
			<input id="customfieldsbut" class="button" type="button" value="<?php _e( "Custom Fields", "woocommerce-advbulkedit"); ?>" />
			<input id="findcustomfieldsbut" class="button" type="button" value="<?php _e( "Find Custom Fields", "woocommerce-advbulkedit"); ?>" />
			<button id="pluginsettingsbut" class="button" type="button">
			   <span class="icon-cog-outline"></span>
				<?php _e( "Plugin Settings", "woocommerce-advbulkedit"); ?>
			 </button>
			<input id="exportproducts" class="button" type="button" value="<?php _e( "Export to CSV", "woocommerce-advbulkedit"); ?>" />
			
			<div class="fileUpload">
    <input id="exportproducts1" class="button" type="button" value="<?php _e( "Update via CSV", "woocommerce-advbulkedit"); ?>" />
    <input id="exportbrowse" type="file" class="upload" />
</div>
			<!--<div style="display:inline-block;border: 1px solid black;">
			<input id="updateviacsv" type="file" class="button" value="<?php _e( "Update Table via CSV", "woocommerce-advbulkedit"); ?>" />
			<input id="updateviacsvsubmit" type="submit" value="<?php _e( "Update", "woocommerce-advbulkedit"); ?>">
			</div>-->
			<div id="dvCSV"></div>
			<div id="exportinfo"></div>
			<br/><br/><br/>
			<div style="position: relative;">
			  <label><input id="linkededit" type="checkbox"/><?php _e( 'Linked editing', 'woocommerce-advbulkedit'); ?></label>
			  <div style="display: inline-block;">
			  <img id="showlinked" src="<?php echo plugin_dir_url(__FILE__);?>images/help18x18.png"/></div>
			<div id="linkednote"> <?php _e( 'Manual changes on any selected product will affect all of them', 'woocommerce-advbulkedit'); ?></div>
			</div>
			<div id="exportdialog">
			<div>
				<table id="tablecsvexport" cellpadding="10" cellspacing="0">
					<tr>
						<td>
							<input id="exportall" type="radio" value="0" name="exportwhat" checked="checked">
							<label for="exportall"><?php _e( 'All products in table', 'woocommerce-advbulkedit'); ?></label>
							<br/><br/>
							<input id="exportsel" type="radio" value="1" name="exportwhat">
							<label for="exportsel"><?php _e( 'Selected products only', 'woocommerce-advbulkedit'); ?></label>
						</td>
					</tr>
					<tr>
						<td>
							<input id="allfields" type="radio" value="0" name="exportwhichfields" checked="checked">
							<label for="allfields"><?php _e( 'All fields', 'woocommerce-advbulkedit'); ?></label>
							<br/><br/>
							<input id="shownfields" type="radio" value="1" name="exportwhichfields">
							<label for="shownfields"><?php _e( 'Visible fields only', 'woocommerce-advbulkedit'); ?></label>
						</td>
					</tr>
					<tr>
						<td>
							<?php _e( 'Delimiter', 'woocommerce-advbulkedit'); ?>: 
							<select id="exportdelimiter">
								<option value=",">,</option>
								<option value=";">;</option>
							</select>
						</td>
					</tr>
					<tr>
						<td style="border-bottom:none; ">
							<label><?php _e( 'Use real meta values', 'woocommerce-advbulkedit'); ?>: 
							<input id="userealmeta" type="checkbox"></input></label>
						</td>
					</tr>
				</table>
			</div>
			</div>
			<div id="confirmdialog">
				<div>
					<?php _e( 'Are you sure you want to continue ?', 'woocommerce-advbulkedit'); ?>
				</div>
			</div>
			<div id="addproddialog">
			</div>
			<!--//plugin settings-->
			<div id="pluginsettings">
			<div style="width:100%;height:100%;">
			<br/>
			<!--settings-->
			<div id="pluginsettingstab">
					<ul>
					<li><a href="#pluginsettingstab-1"><?php echo $arrTranslated['trans_main_settings']; ?></a></li>
					<li><a href="#pluginsettingstab-2"><?php echo $arrTranslated['trans_search_settings']; ?></a></li>
					<?php
						if(!empty($this->largeattributes))
						{
							echo '<li><a href="#pluginsettingstab-3">Large Attributes</a></li>';
						}
					?>
					</ul>
					
					<div id="pluginsettingstab-1">
				
				<table cellpadding="10" cellspacing="0" style="margin: 0 auto;">
					<tr>
						<td>
							<?php _e( 'Limit on product retrieval', 'woocommerce-advbulkedit'); ?>
						</td>
						<td>
							<input id="productlimit" type="text" style="width:50px;" 
							<?php
								$settings = get_option('w3exabe_settings');
								if(!is_array($settings)) $settings = array();
								if(isset($settings['settlimit']))
								{		
									echo 'value="'.$settings['settlimit'].'"';
								}else
								{
									echo ' value="1000"';
								}
								
							?>
							>
						</td>
					</tr>
					<tr>
						<td width="50%"  style="padding-top: 25px;">
							<?php _e( 'Save products in batches of', 'woocommerce-advbulkedit'); ?>
						</td>
						<td width="50%"  style="padding-top: 25px;">
							<input id="savebatch" type="text" style="width:50px;" autocomplete="off"
							<?php
								$settings = get_option('w3exabe_settings');
								if(!is_array($settings)) $settings = array();
								if(!isset($settings['savebatch']))
								{
									$settings['savebatch'] = 50;
								}
								if(isset($settings['savebatch']) && is_numeric($settings['savebatch']))
								{		
									echo 'value="'.$settings['savebatch'].'"';
								}else
								{
									echo ' value=""';
								}
							?>
							>
							/<?php _e( 'empty for a single ajax query', 'woocommerce-advbulkedit'); ?>/
						</td>
					</tr>
					<tr>
						<td width="50%" style="padding-top: 20px;">
							<label><input id="gettotalnumber" type="checkbox" autocomplete="off"
							<?php 
//						  	$settings = get_option('w3exabe_settings');
							if(isset($settings['settgetall']))
							{
								if($settings['settgetall'] == 1)
								{
									echo 'checked=checked';
								}
							}?>
							><?php _e( 'Do not retrieve total number', 'woocommerce-advbulkedit'); ?></label>
						</td>
						<td  style="padding-top: 20px;">
							/<?php _e( 'check if you have a large number of products and want to speed up the query', 'woocommerce-advbulkedit'); ?>/
						</td>
					</tr>
					<tr>
						<td width="50%" style="padding-top: 20px;">
							<label><input id="deleteimages" type="checkbox"
							<?php 
//						  	$settings = get_option('w3exabe_settings');
							if(isset($settings['deleteimages']))
							{
								if($settings['deleteimages'] == 1)
								{
									echo 'checked=checked';
								}
							}?>
							><?php _e( 'Delete images from server/media library when removing from product image/gallery or deleting product', 'woocommerce-advbulkedit'); ?></label>
						</td>
						<td  style="padding-top: 20px;">
							/<?php _e( 'This is not revertable ! Use with caution', 'woocommerce-advbulkedit'); ?>/
						</td>
					</tr>
					<tr>
						<td width="50%" style="padding-top: 20px;">
							<label><input id="deleteinternal" type="checkbox"
							<?php 
//						  	$settings = get_option('w3exabe_settings');
							if(isset($settings['deleteinternal']))
							{
								if($settings['deleteinternal'] == 1)
								{
									echo 'checked=checked';
								}
							}?>
							><?php _e( 'Use sql queries when deleting products', 'woocommerce-advbulkedit'); ?></label>
						</td>
						<td  style="padding-top: 20px;">
							/<?php _e( 'will speed up the query', 'woocommerce-advbulkedit'); ?>/
						</td>
					</tr>
					<tr>
						<td width="50%" style="padding-top: 20px;">
							<label><input id="retrievevariations" type="checkbox"
							<?php 
//						  	$settings = get_option('w3exabe_settings');
							if(isset($settings['settgetvars']))
							{
								if($settings['settgetvars'] == 1)
								{
									echo 'checked=checked';
								}
							}?>
							><?php _e( 'Retrieve all variations on attribute search', 'woocommerce-advbulkedit'); ?></label>
						</td>
						<td  style="padding-top: 20px;">
							/<?php _e( 'if the parent has it', 'woocommerce-advbulkedit'); ?>/
						</td>
					</tr>
					<tr>
						<td width="50%" style="padding-top: 20px;">
							<label><input id="includechildren" type="checkbox"
							<?php 
//						  	$settings = get_option('w3exabe_settings');
							if(isset($settings['incchildren']))
							{
								if($settings['incchildren'] == 1)
								{
									echo 'checked=checked';
								}
							}
							?>
							><?php _e( 'Get all children of selected category on search', 'woocommerce-advbulkedit'); ?></label>
						</td>
						<td  style="padding-top: 20px;">
							
						</td>
					</tr>
					<tr>
						<td width="50%" style="padding-top: 20px;">
							<label><input id="bgetallvarstaxonomies" type="checkbox"
							<?php 
//						  	$settings = get_option('w3exabe_settings');
							if(isset($settings['bgetallvarstaxonomies']))
							{
								if($settings['bgetallvarstaxonomies'] == 1)
								{
									echo 'checked=checked';
								}
							}
							?>
							><?php _e( 'Retrieve all variations on custom taxonomy search', 'woocommerce-advbulkedit'); ?></label>
						</td>
						<td  style="padding-top: 20px;">
							
						</td>
					</tr>
					<tr>
						<td width="50%" style="padding-top: 20px;">
							<label><input id="disattributes" type="checkbox"
							<?php 
							if(isset($settings['disattributes']))
							{
								if($settings['disattributes'] == 1)
								{
									echo 'checked=checked';
								}
							}
							?>
							><?php _e( 'Disable attribute support', 'woocommerce-advbulkedit'); ?></label>
						</td>
						<td  style="padding-top: 20px;">
							
						</td>
					</tr>
					<tr>
						<td width="50%" style="padding-top: 20px;">
							<label><input id="converttoutf8" type="checkbox"
							<?php 
							$echotext = "checked=checked";
							if(isset($settings['converttoutf8']))
							{
								if($settings['converttoutf8'] == 0)
								{
									$echotext = "";
								}
							}
							echo $echotext;	
							?>				 
							><?php _e( 'Convert manually to UTF-8', 'woocommerce-advbulkedit'); ?></label>
						</td>
						<td  style="padding-top: 20px;">
							
						</td>
					</tr>
					<tr>
						<td width="50%" style="padding-top: 20px;">
							<label><input id="dontcheckusedfor" type="checkbox"
							<?php 
							$echotext = "checked=checked";
							if(isset($settings['dontcheckusedfor']))
							{
								if($settings['dontcheckusedfor'] == 0)
								{
									$echotext = "";
								}
							}
							echo $echotext;	 ?>
							><?php _e( 'Do not check "Used for variations" automatically', 'woocommerce-advbulkedit'); ?></label>
						</td>
						<td  style="padding-top: 20px;">
							
						</td>
					</tr>
					<tr>
						<td width="50%" style="padding-top: 20px;">
							<label><input id="calldoaction" type="checkbox"
							<?php 
							$echotext = "";
							if(isset($settings['calldoaction']))
							{
								if($settings['calldoaction'] == 1)
								{
									
									$echotext = "checked=checked";
								}
							}
							echo $echotext;	 ?>
							><?php _e( 'Call woocommerce action on save', 'woocommerce-advbulkedit'); ?></label>
						</td>
						<td  style="padding-top: 20px;">
							/<?php _e( 'for better compatibility with third-party cache plugins', 'woocommerce-advbulkedit'); ?>/
						</td>
					</tr>
					<tr>
						<td width="50%" style="padding-top: 20px;">
							<label><input id="calldosavepost" type="checkbox"
							<?php 
							$echotext = "";
							if(isset($settings['calldosavepost']))
							{
								if($settings['calldosavepost'] == 1)
								{
									$echotext = "checked=checked";
								}
							}
							echo $echotext;	 ?>
							><?php _e( 'Call save_post action', 'woocommerce-advbulkedit'); ?></label>
						</td>
						<td  style="padding-top: 20px;">
							
						</td>
					</tr>
					<tr>
						<td width="50%" style="padding-top: 20px;">
							<label><input id="confirmsave" type="checkbox"
							<?php 
							if(isset($settings['confirmsave']))
							{
								if($settings['confirmsave'] == 1)
								{
									echo 'checked=checked';
								}
							}
							?>
							><?php _e( 'Require confirmation on save', 'woocommerce-advbulkedit'); ?></label>
						</td>
						<td  style="padding-top: 20px;">
						</td>
					</tr>
					<tr>
						<td width="50%" style="padding-top: 20px;">
							<label><input id="disablesafety" type="checkbox"
							<?php 
							if(isset($settings['disablesafety']))
							{
								if($settings['disablesafety'] == 1)
								{
									echo 'checked=checked';
								}
							}
							?>
							><?php _e( 'Disable safety net on edit', 'woocommerce-advbulkedit'); ?></label>
						</td>
						<td  style="padding-top: 20px;">
						</td>
					</tr>
					<tr>
						<td width="50%"  style="padding-top: 25px;">
							<?php _e( 'Choose row height /needs page reload/', 'woocommerce-advbulkedit'); ?>
						</td>
						<td width="50%"  style="padding-top: 25px;">
							<select id="rowheight" >
							<?php
								$normal = "selected";
								$medium = "";
								$big = "";
								if(isset($settings['rowheight']) && is_numeric($settings['rowheight']))
								{		
									if($settings['rowheight'] == "3")
									{
										$big = 'selected';
									}elseif($settings['rowheight'] == "2")
									{
										$medium = 'selected';
									}else
									{
										$normal = 'selected';
									}
								}
							?>
							<option value ="1" <?php echo $normal; ?>>normal</option>
							<option value ="2" <?php echo $medium; ?>>medium</option>
							<option value ="3" <?php echo $big; ?>>big</option>
							</select>
						</td>
					</tr>
					<!--<tr>
						<td width="50%"  style="padding-top: 25px;">
							<?php _e( 'Set manual search filters height', 'woocommerce-advbulkedit'); ?>
						</td>
						<td width="50%"  style="padding-top: 25px;">
							<input id="searchfiltersheight" type="text" style="width:50px;" 
							<?php
								$settings = get_option('w3exabe_settings');
								if(!is_array($settings)) $settings = array();
								if(isset($settings['searchfiltersheight']) && is_numeric($settings['searchfiltersheight']))
								{		
									echo 'value="'.$settings['searchfiltersheight'].'"';
								}else
								{
									echo ' value=""';
								}
							?>
							>
							px
						</td>
					</tr>-->
					<tr>
						<td width="50%"  style="padding-top: 25px;">
							<?php _e( 'Set manual table height', 'woocommerce-advbulkedit'); ?>
						</td>
						<td width="50%"  style="padding-top: 25px;">
							<input id="tableheight" type="text" style="width:50px;" 
							<?php
								$settings = get_option('w3exabe_settings');
								if(!is_array($settings)) $settings = array();
								if(isset($settings['tableheight']) && is_numeric($settings['tableheight']))
								{		
									echo 'value="'.$settings['tableheight'].'"';
								}else
								{
									echo ' value=""';
								}
							?>
							>
							px
						</td>
					</tr>
					<tr>
						<td width="50%" style="padding-top: 20px;">
							<label><input id="debugmode" type="checkbox"
							<?php 
							if(isset($settings['debugmode']))
							{
								if($settings['debugmode'] == 1)
								{
									echo 'checked=checked';
								}
							}
							?>
							><?php _e( 'Enable debug mode', 'woocommerce-advbulkedit'); ?></label>
						</td>
						<td  style="padding-top: 20px;">
						</td>
					</tr>
				</table>
				</div>
				<div id="pluginsettingstab-2">
					<table cellpadding="25" cellspacing="0" style="margin: 0 auto;width:100%;">
					<tr>
						<td width="45%" style="padding-top: 20px;">
							<label><input id="showattributes" type="checkbox"
							<?php 
							$echotext = "checked=checked";
							if(isset($settings['showattributes']))
							{
								if($settings['showattributes'] == 0)
								{
									$echotext = "";
								}
							}
							echo $echotext;	 ?>
							><?php _e( 'Attributes', 'woocommerce-advbulkedit'); ?></label>
						</td>
						<td width="55%" style="padding-top: 20px;">
							<label><input id="showprices" type="checkbox"
							<?php 
							$echotext = "checked=checked";
							if(isset($settings['showprices']))
							{
								if($settings['showprices'] == 0)
								{
									$echotext = "";
								}
							}
							echo $echotext;	 ?>
							><?php _e( 'Regular/Sale Price', 'woocommerce-advbulkedit'); ?></label>
						</td>
					</tr>
					<tr>
						<td width="45%" style="padding-top: 20px;">
							<label><input id="showskutags" type="checkbox"
							<?php 
							$echotext = "checked=checked";
							if(isset($settings['showskutags']))
							{
								if($settings['showskutags'] == 0)
								{
									$echotext = "";
								}
							}
							echo $echotext;	 ?>
							><?php _e( 'SKU/Tags', 'woocommerce-advbulkedit'); ?></label>
						</td>
						<td width="55%" style="padding-top: 20px;">
							<label><input id="showdescriptions" type="checkbox"
							<?php 
							$echotext = "";
							if(isset($settings['showdescriptions']))
							{
								if($settings['showdescriptions'] == 1)
								{
									$echotext = "checked=checked";
								}
							}
							echo $echotext;	 ?>
							><?php _e( 'Long/Short Descriptions', 'woocommerce-advbulkedit'); ?></label>
						</td>
					</tr>
					<tr>
						<td width="45%" style="padding-top: 20px;">
							<label><input id="showidsearch" type="checkbox"
							<?php 
							$echotext = "";
							if(isset($settings['showidsearch']))
							{
								if($settings['showidsearch'] == 1)
								{
									$echotext = "checked=checked";
								}
							}
							echo $echotext;	 ?>
							><?php _e( 'ID', 'woocommerce-advbulkedit'); ?></label>
						</td>
						<td width="55%" style="padding-top: 20px;">
							<label><input id="showstocksearch" type="checkbox"
							<?php 
							$echotext = "";
							if(isset($settings['showstocksearch']))
							{
								if($settings['showstocksearch'] == 1)
								{
									$echotext = "checked=checked";
								}
							}
							echo $echotext;	 ?>
							><?php _e( 'Stock Qty', 'woocommerce-advbulkedit'); ?></label>
						</td>
					</tr>
					</table>
					</div>
					<?php
						if(!empty($this->largeattributes))
						{
							echo '<div id="pluginsettingstab-3"><div style="margin-top:32px;"></div>';
							foreach($this->largeattributes as $lattr)
							{
								$checked = '';
								if($lattr['status'] === "1")
								{
									$checked = "checked=checked";
								}
								echo '<div class="largeattr"><label><input type="checkbox" data-id="'.$lattr['name'].'" '.$checked.' autocomplete="off">'.$lattr['label'].'</label></div>';
							}
							echo '<div style="clear:both;width:1px;height:1px;"></div><p>needs page reload for the changes to take effect</p></div>';
						}
					?>
				</div>
				</div>
			</div>
			<?php 
				$setnew = __( 'set new', 'woocommerce-advbulkedit');
				$prepend = __( 'prepend', 'woocommerce-advbulkedit');
				$append = __( 'append', 'woocommerce-advbulkedit');
				$replacetext = __( 'replace text', 'woocommerce-advbulkedit');
				$ignorecase = __( 'Ignore case', 'woocommerce-advbulkedit');
				$withtext = __( 'with text', 'woocommerce-advbulkedit');
				$delete = __( 'delete', 'woocommerce-advbulkedit');
			    echo '<script>';echo PHP_EOL;
				if(isset($settings['showidsearch']))
				{
					if($settings['showidsearch'] == 1)
					{
						echo 'W3Ex.w3ex_show_id_search ="1";';  echo PHP_EOL;
					}
				}
				echo 'W3Ex.trans_setnew = "'.$setnew.'";'; echo PHP_EOL;
				echo 'W3Ex.trans_prepend = "'.$prepend.'";'; echo PHP_EOL;
				echo 'W3Ex.trans_append = "'.$append.'";'; echo PHP_EOL;
				echo 'W3Ex.trans_replacetext = "'.$replacetext.'";'; echo PHP_EOL;
				echo 'W3Ex.trans_ignorecase = "'.$ignorecase.'";'; echo PHP_EOL;
				echo 'W3Ex.trans_withtext = "'.$withtext.'";'; echo PHP_EOL;						echo 'W3Ex.trans_delete = "'.$delete.'";'; echo PHP_EOL;	
				echo 'W3Ex.trans_incbyvalue = "'.__( "increase by value", "woocommerce-advbulkedit").'";'; echo PHP_EOL;
				echo 'W3Ex.trans_decbyvalue = "'.__( "decrease by value", "woocommerce-advbulkedit").'";'; echo PHP_EOL;
				echo 'W3Ex.trans_incbyper = "'.__( "increase by %", "woocommerce-advbulkedit").'";'; echo PHP_EOL;
				echo 'W3Ex.trans_decbyper = "'.__( "decrease by %", "woocommerce-advbulkedit").'";'; echo PHP_EOL;
				echo 'W3Ex.trans_movetrash = "'.__( "Move to Trash", "woocommerce").'";'; echo PHP_EOL;
				echo 'W3Ex.trans_delperm = "'.__( "Delete Permanently", "woocommerce").'";'; echo PHP_EOL;
				echo 'W3Ex.trans_products = "'.__( "Products", "woocommerce").'";'; echo PHP_EOL;
				echo 'W3Ex.trans_variations = "'.__( "Variations", "woocommerce").'";'; echo PHP_EOL;
				echo 'W3Ex.trans_duplicate = "'.__( "Duplicate", "woocommerce-advbulkedit").'";'; echo PHP_EOL;
				echo 'W3Ex.trans_times = "'.__( "Time(s)", "woocommerce-advbulkedit").'";'; echo PHP_EOL;
				echo 'W3Ex.trans_add = "'.__( "add", "woocommerce-advbulkedit").'";'; echo PHP_EOL;
				echo 'W3Ex.trans_linkednote = "'.__( "Note ! - Linked editing is turned on, all new variations will be added to all of the selected products. A large number of products * variations can cause a php timeout", "woocommerce-advbulkedit").'";'; echo PHP_EOL;
				echo 'W3Ex.trans_attributes = "'.__( "Attributes", "woocommerce").'";'; echo PHP_EOL;
				echo 'W3Ex.trans_select = "'.__( "Select", "woocommerce").'";'; echo PHP_EOL;
				echo 'W3Ex.trans_bulkadd = "'.__( "Bulk Add", "woocommerce-advbulkedit").'";'; echo PHP_EOL;
				echo 'W3Ex.trans_addsingle = "'.__( "Add Single Variation", "woocommerce-advbulkedit").'";'; echo PHP_EOL;
				echo 'W3Ex.trans_seldoesnot = "'.__( "Selected product does not have any attributes", "woocommerce-advbulkedit").'";'; echo PHP_EOL;
				echo "</script>";
			 ?>
			<!--//bulk dialog-->
			<div id="bulkdialog">
			<table class="custstyle-table">
				<tr data-id="post_title" style="display: table-row;">
					<td style="width:20% !important;">
						<?php echo $arrTranslated['post_title'];  ?>
					</td>
					<td>
						 <select id="bulkpost_title" class="bulkselect">
							<option value="new"><?php echo $setnew; ?></option>
							<option value="prepend"><?php echo $prepend; ?></option>
							<option value="append"><?php echo $append; ?></option>
							<option value="replace"><?php echo $replacetext; ?></option>
							<option value="replaceregexp">replace regexp</option>
						</select>
						<label class="labelignorecase" style="display:none;">
						<input class="inputignorecase" type="checkbox">
						<?php echo $ignorecase; ?></label>
					</td>
					<td class="tdbulkvalue">
						<div class="imgButton sm mapto">
					    </div>
						<input id="bulkpost_titlevalue" type="text" placeholder="Skipped (empty)" data-id="post_title" class="bulkvalue"/>
					</td>
					<td>
						<div class="divwithvalue" style="display:none;"><?php echo $withtext; ?> <input class="inputwithvalue" type="text"></div>
					</td>
				</tr>
				<tr data-id="post_content">
					<td>
						<?php echo $arrTranslated['post_content']; ?>
					</td>
					<td>
						 <select id="bulkpost_content" class="bulkselect">
							<option value="new"><?php echo $setnew; ?></option>
							<option value="prepend"><?php echo $prepend; ?></option>
							<option value="append"><?php echo $append; ?></option>
							<option value="replace"><?php echo $replacetext; ?></option>
							<option value="replaceregexp">replace regexp</option>
						</select>
						<label class="labelignorecase" style="display:none;">
						<input class="inputignorecase" type="checkbox">
						<?php echo $ignorecase; ?></label>
					</td>
					<td class="tdbulkvalue">
						<div class="imgButton sm mapto">
					    </div>
						<textarea id="bulkpost_contentvalue" rows="1" cols="15" data-id="post_content" class="bulkvalue" placeholder="Skipped (empty)"></textarea>
					</td>
					<td>
						<div class="divwithvalue" style="display:none;"><?php echo $withtext; ?> <textarea class="inputwithvalue" rows="1" cols="15"></textarea></div>
					</td>
				</tr>
				<tr data-id="post_excerpt">
					<td>
						<?php echo $arrTranslated['post_excerpt']; ?>
					</td>
					<td>
						 <select id="bulkpost_excerpt" class="bulkselect">
							<option value="new"><?php echo $setnew; ?></option>
							<option value="prepend"><?php echo $prepend; ?></option>
							<option value="append"><?php echo $append; ?></option>
							<option value="replace"><?php echo $replacetext; ?></option>
							<option value="replaceregexp">replace regexp</option>
						</select>
						<label class="labelignorecase" style="display:none;">
						<input class="inputignorecase" type="checkbox">
						<?php echo $ignorecase; ?></label>
					</td>
					<td class="tdbulkvalue">
						<div class="imgButton sm mapto">
					    </div>
						<textarea id="bulkpost_excerptvalue" rows="1" cols="15" data-id="post_excerpt" class="bulkvalue" placeholder="Skipped (empty)"></textarea>
					</td>
					<td>
						<div class="divwithvalue" style="display:none;"><?php echo $withtext; ?> <textarea class="inputwithvalue" rows="1" cols="15"></textarea></div>
					</td>
				</tr>
				<tr data-id="post_name">
					<td>
						<?php echo $arrTranslated['post_name']; ?>
					</td>
					<td>
						 <select id="bulkpost_name" class="bulkselect">
							<option value="new"><?php echo $setnew; ?></option>
							<option value="prepend"><?php echo $prepend; ?></option>
							<option value="append"><?php echo $append; ?></option>
							<option value="replace"><?php echo $replacetext; ?></option>
							<option value="replaceregexp">replace regexp</option>
						</select>
						<label class="labelignorecase" style="display:none;">
						<input class="inputignorecase" type="checkbox">
						<?php echo $ignorecase; ?></label>
					</td>
					<td class="tdbulkvalue">
						<div class="imgButton sm mapto">
					    </div>
						<textarea id="bulkpost_namevalue" rows="1" cols="15" data-id="post_name" class="bulkvalue" placeholder="Skipped (empty)"></textarea>
					</td>
					<td>
						<div class="divwithvalue" style="display:none;"><?php echo $withtext; ?> <textarea class="inputwithvalue" rows="1" cols="15"></textarea></div>
					</td>
				</tr>
				<tr data-id="_sku">
					<td>
						<?php echo $arrTranslated['_sku']; ?>
					</td>
					<td>
						 <select id="bulk_sku" class="bulkselect">
							<option value="new"><?php echo $setnew; ?></option>
							<option value="prepend"><?php echo $prepend; ?></option>
							<option value="append"><?php echo $append; ?></option>
							<option value="replace"><?php echo $replacetext; ?></option>
							<option value="fillseries">fill series</option>
						</select>
						<label class="labelignorecase" style="display:none;">
						<input class="inputignorecase" type="checkbox">
						<?php echo $ignorecase; ?></label>
					</td>
					<td class="tdbulkvalue">
						<div class="imgButton sm mapto">
					    </div>
						<input id="bulk_skuvalue" type="text" data-id="_sku" class="bulkvalue" placeholder="Skipped (empty)"/>
					</td>
					<td>
						<div class="divwithvalue" style="display:none;"><?php echo $withtext; ?> <input class="inputwithvalue" type="text"></div>
					</td>
				</tr>
				<tr data-id="product_cat">
					<td>
						<input id="setproduct_cat" type="checkbox" class="bulkset" data-id="product_cat" data-type="customtaxh"><label for="setproduct_cat"><?php echo $arrTranslated['product_cat']; ?></label>
					</td>
					<td>
						 <select id="bulkaddproduct_cat" class="bulkselect">
							<option value="new"><?php echo $setnew; ?></option>
							<option value="add"><?php _e( "add", "woocommerce-advbulkedit"); ?></option>
							<option value="remove"><?php _e( "remove", "woocommerce-advbulkedit"); ?></option>
						</select>
						<button class="butnewattribute button newcat" type="button"><span class="icon-plus-outline"></span>new</button>
						<div class="divnewattribute"> 
		   <input class="inputnewattributename" type="text" placeholder="name" data-slug="product_cat"></input><br/> 
		   <input class="inputnewattributeslug" type="text" placeholder="slug (optional)"></input><br/> 
		   <select class="selectnewcategory" data-placeholder="select parent(optional)" multiple></select><br/>
		   <button class="butnewattributesave butbulkdialog newcat" style="position:relative;">Ok</button><button class="butnewattributecancel newcat">Cancel</button></div> 
		   <div class="divnewattributeerror"></div> 
					</td>
					<td class="nontextnumbertd">
						 <select id="bulkproduct_cat" class="makechosen catselset" style="width:250px;" data-placeholder="select category" multiple >
						 <option value=""></option>
						<?php
							$cats = $this->categories;
							$newcats = array();
							$cats_asoc = $this->cat_asoc;
							$depth = array();

						    foreach($cats as $cat)
							{
								if($cat->category_parent == 0)
								{
									$depth[$cat->term_id] = 0;
									$newcats[] = $cat;
								}
							}
							foreach($cats as $cat)
							{
								if($cat->category_parent == 0) continue;
								{
			//						if(!isset($options[$cat->category_id]))
									{
										if(!isset($depth[$cat->term_id]))
										{
											$loop = true;
											$counter = 0;
											while($loop && ($counter < 1000))
											{
												foreach($cats as $catin)
												{
													if($catin->category_parent == 0)
													   continue;
													if(isset($depth[$catin->category_parent]))
													{
														$newdepth = $depth[$catin->category_parent];
														$newdepth++;
														if(!isset($depth[$catin->term_id]))
														{
															$depth[$catin->term_id] = $newdepth;
															for($i = 0; $i < count($newcats); $i++)
															{
																$catins = $newcats[$i];
																if($catins->term_id == $catin->category_parent)
																{
																	array_splice($newcats, $i+1, 0,array($catin));
																	break;
																}
															}
														}

														if($catin->term_id == $cat->term_id)
														{
															$loop = false;
															break;
														}
													}
												}
												$counter++;
											}
											if(!isset($depth[$cat->term_id]))
											{
												$depth[$cat->term_id] = 0;
												$newcats[] = $cat;
											}
										}
									}
								}
								
							}
							if(count($newcats) == count($cats))
							{
								foreach($newcats as $catin)
								{
									$depthstring = '';
									if(isset($depth[$catin->term_id]))
									{
										$depthn = (int)$depth[$catin->term_id];
										if($depthn < 15)
										{
											while($depthn > 0)
											{
												$depthstring = $depthstring.'&nbsp;&nbsp;&nbsp;';
												$depthn--;
											}
											
										}
									}
									echo '<option value="'.$catin->term_id.'" >'.$depthstring.$catin->category_name.'</option>';
								}
							}else
							{
								foreach($cats as $catin)
								{
									echo '<option value="'.$catin->term_id.'" >'.$catin->category_name.'</option>';
								}
							}
//						    foreach($this->categories as $category)
//							{
//									echo '<option value="'.$category->term_id.'" >'.$category->category_name.'</option>';
//								
//							}
					
						?>
						</select>
						
					</td>
					<td>
						
					</td>
				</tr>
				<tr data-id="product_tag">
					<td>
						<input id="setproduct_tag" type="checkbox" class="bulkset" data-id="product_tag" data-type="customtaxh"><label for="setproduct_tag"><?php echo $arrTranslated['product_tag']; ?></label>
					</td>
					<td>
						 <select id="bulkaddproduct_tag" class="bulkselect">
							<option value="new"><?php echo $setnew; ?></option>
							<option value="add"><?php _e( "add", "woocommerce-advbulkedit"); ?></option>
							<option value="remove"><?php _e( "remove", "woocommerce-advbulkedit"); ?></option>
						</select>
						<button class="butnewattribute button newcat" type="button"><span class="icon-plus-outline"></span>new</button>
						<div class="divnewattribute"> 
		   <input class="inputnewattributename" type="text" placeholder="name" data-slug="product_tag"></input><br/> 
		   <input class="inputnewattributeslug" type="text" placeholder="slug (optional)"></input><br/> 
		<!--   <select class="selectnewcategory" data-placeholder="select parent(optional)" multiple></select><br/>-->
		   <button class="butnewattributesave butbulkdialog newcat" style="position:relative;">Ok</button><button class="butnewattributecancel newcat">Cancel</button></div> 
		   <div class="divnewattributeerror"></div> 
					</td>
					<td class="nontextnumbertd">
						 <select id="bulkproduct_tag" class="makechosen catselset" style="width:250px;" data-placeholder="select tags" multiple >
						 <option value=""></option>
						<?php
							$args = array(
							    'number'     => 99999,
							    'orderby'    => 'slug',
							    'order'      => 'ASC',
							    'hide_empty' => false,
							    'include'    => '',
								'fields'     => 'all'
							);
//							$tagcount = wp_count_terms( 'product_tag', $args );
//							if($tagcount < 2000)
							{
//								$woo_tags = get_terms( 'product_tag', $args );
								$getquery = "SELECT t.name,tt.term_taxonomy_id,tt.term_id FROM {$wpdb->prefix}terms as t INNER JOIN {$wpdb->prefix}term_taxonomy AS tt ON t.term_id= tt.term_id WHERE tt.taxonomy IN('product_tag')";
								$woo_tags = $wpdb->get_results($getquery);
								if(!is_wp_error($woo_tags) && is_array($woo_tags))
								{
									foreach($woo_tags as $tag)
									{
									   if(!is_object($tag)) continue;
									   if(!property_exists($tag,'term_taxonomy_id')) continue;
									   if(!property_exists($tag,'term_id')) continue;
									   if(!property_exists($tag,'name')) continue;
									   if($this->iswpml)
										{
										   if(ICL_LANGUAGE_CODE !== 'all')
										   {
										   	   $id = self::lang_category_id($tag->term_taxonomy_id,'product_tag');
											   if($id === NULL || $id != $tag->term_taxonomy_id)
											   		continue;
										   }
									    }
									   echo '<option value="'.$tag->term_id.'" >'.$tag->name.'</option>';
									};
								}
							}
						?>
						</select>
						
					</td>
					<td>
						
					</td>
				</tr>
			<!--	<tr data-id="product_tag">
					<td>
						<?php echo $arrTranslated['product_tag']; ?>
					</td>
					<td>
						 <select id="bulkproduct_tag" class="bulkselect" data-id="product_tag">
							<option value="new"><?php echo $setnew; ?></option>
							<option value="prepend"><?php echo $prepend; ?></option>
							<option value="append"><?php echo $append; ?></option>
							<option value="replace"><?php echo $replacetext; ?></option>
							<option value="replaceregexp">replace regexp</option>
							<option value="delete"><?php echo $delete; ?></option>
						</select>
						<label class="labelignorecase" style="display:none;">
						<input class="inputignorecase" type="checkbox">
						<?php echo $ignorecase; ?></label>
					</td>
					<td>
						<input id="bulkproduct_tagvalue" type="text" placeholder="Skipped (empty)" data-id="product_tag" class="bulkvalue"/>
					</td>
					<td>
						<div class="divwithvalue" style="display:none;"><?php echo $withtext; ?> <input class="inputwithvalue" type="text"></div>
					</td>
				</tr>-->
				<tr data-id="_regular_price">
					<td>
						<?php echo $arrTranslated['_regular_price']; ?>
					</td>
					<td>
						 <select id="bulk_regular_price" data-id="_regular_price" class="bulksetdecimal">
							<option value="new"><?php echo $setnew; ?></option>
							<option value="incvalue"><?php _e( "increase by value", "woocommerce-advbulkedit"); ?></option>
							<option value="decvalue"><?php _e( "decrease by value", "woocommerce-advbulkedit"); ?></option>
							<option value="incpercent"><?php _e( "increase by %", "woocommerce-advbulkedit"); ?></option>
							<option value="decpercent"><?php _e( "decrease by %", "woocommerce-advbulkedit"); ?></option>
						</select>
					</td>
					<td class="tdbulkvalue">
					 <!-- <div class="imgButton med remove">
    </div>-->
					    <div class="imgButton sm mapto">
					    </div>
						<input id="bulk_regular_pricevalue" type="text" data-id="_regular_price" class="bulkvalue" placeholder="Skipped (empty)"/>
					</td>
					<td>
						 <select id="bulk_regular_price_round" style="display: none;">
						 	<option value="noround"><?php _e( "no rounding", "woocommerce-advbulkedit"); ?></option>
							<option value="roundup100"><?php _e( "round-up (100)", "woocommerce-advbulkedit"); ?></option>
							<option value="roundup10"><?php _e( "round-up (10)", "woocommerce-advbulkedit"); ?></option>
							<option value="roundup"><?php _e( "round-up (1)", "woocommerce-advbulkedit"); ?></option>
							<option value="roundup1"><?php _e( "round-up (0.1)", "woocommerce-advbulkedit"); ?></option>
							<option value="rounddown1"><?php _e( "round-down (0.1)", "woocommerce-advbulkedit"); ?></option>
							<option value="rounddown"><?php _e( "round-down (1)", "woocommerce-advbulkedit"); ?></option>
							<option value="rounddown10"><?php _e( "round-down (10)", "woocommerce-advbulkedit"); ?></option>
							<option value="rounddown100"><?php _e( "round-down (100)", "woocommerce-advbulkedit"); ?></option>
						 </select>
					</td>
				</tr>
				<tr data-id="_sale_price">
					<td>
						<?php echo $arrTranslated['_sale_price']; ?>
					</td>
					<td>
						 <select id="bulk_sale_price" data-id="_sale_price" class="bulksetdecimal">
							<option value="new"><?php echo $setnew; ?></option>
							<option value="incvalue"><?php _e( "increase by value", "woocommerce-advbulkedit"); ?></option>
							<option value="decvalue"><?php _e( "decrease by value", "woocommerce-advbulkedit"); ?></option>
							<option value="incpercent"><?php _e( "increase by %", "woocommerce-advbulkedit"); ?></option>
							<option value="decpercent"><?php _e( "decrease by %", "woocommerce-advbulkedit"); ?></option>
							<option value="decvaluereg"><?php _e( "decrease by value", "woocommerce-advbulkedit"); ?> (from reg.)</option>
							<option value="decpercentreg"><?php _e( "decrease by %", "woocommerce-advbulkedit"); ?> (from reg.)</option>
							<option value="delete"><?php echo $delete; ?></option>
						</select>
					</td>
					<td class="tdbulkvalue">
						 <div class="imgButton sm mapto">
					    </div>
						<input id="bulk_sale_pricevalue" type="text" data-id="_sale_price" class="bulkvalue" placeholder="Skipped (empty)"/>
					</td>
					<td>
					 	 <select id="bulk_sale_price_round" style="display: none;">
						 	<option value="noround"><?php _e( "no rounding", "woocommerce-advbulkedit"); ?></option>
							<option value="roundup100"><?php _e( "round-up (100)", "woocommerce-advbulkedit"); ?></option>
							<option value="roundup10"><?php _e( "round-up (10)", "woocommerce-advbulkedit"); ?></option>
							<option value="roundup"><?php _e( "round-up (1)", "woocommerce-advbulkedit"); ?></option>
							<option value="roundup1"><?php _e( "round-up (0.1)", "woocommerce-advbulkedit"); ?></option>
							<option value="rounddown1"><?php _e( "round-down (0.1)", "woocommerce-advbulkedit"); ?></option>
							<option value="rounddown"><?php _e( "round-down (1)", "woocommerce-advbulkedit"); ?></option>
							<option value="rounddown10"><?php _e( "round-down (10)", "woocommerce-advbulkedit"); ?></option>
							<option value="rounddown100"><?php _e( "round-down (100)", "woocommerce-advbulkedit"); ?></option>
						 </select>
						 <input type="checkbox" id="saleskip"><label id="saleskiplabel" for="saleskip"> Skip products that have a sale price</label>
					</td>
				</tr>
				<tr data-id="_tax_status">
					<td>
						<input id="set_tax_status" type="checkbox" class="bulkset" data-id="_tax_status"><label for="set_tax_status"><?php echo $arrTranslated['_tax_status']; ?></label>
					</td>
					<td>
						
					</td>
					<td class="nontextnumbertd">
						 <select id="bulk_tax_status">
							<option value="Taxable">Taxable</option>
							<option value="Shipping only">Shipping only</option>
							<option value="None">None</option>
						</select>
					</td>
					<td>
						
					</td>
				</tr>
				<tr data-id="_tax_class">
					<td>
						<input id="set_tax_class" type="checkbox" class="bulkset" data-id="_tax_class"><label for="set_tax_class"><?php echo $arrTranslated['_tax_class']; ?></label>
					</td>
					<td>
						
					</td>
					<td class="nontextnumbertd">
						 <select id="bulk_tax_class">
							<option value="Standard">Standard</option>
							<option value="Reduced Rate">Reduced Rate</option>
							<option value="Zero Rate">Zero Rate</option>
						</select>
					</td>
					<td>
						
					</td>
				</tr>
				<tr data-id="_weight">
					<td>
						<?php echo $arrTranslated['_weight']; ?>
					</td>
					<td>
						 <select id="bulk_weight" data-id="_weight" class="bulksetdecimal">
							<option value="new"><?php echo $setnew; ?></option>
							<option value="incvalue"><?php _e( "increase by value", "woocommerce-advbulkedit"); ?></option>
							<option value="decvalue"><?php _e( "decrease by value", "woocommerce-advbulkedit"); ?></option>
							<option value="incpercent"><?php _e( "increase by %", "woocommerce-advbulkedit"); ?></option>
							<option value="decpercent"><?php _e( "decrease by %", "woocommerce-advbulkedit"); ?></option>
							<option value="delete"><?php echo $delete; ?></option>
						</select>
					</td>
					<td class="tdbulkvalue">
						<div class="imgButton sm mapto">
					    </div>
						<input id="bulk_weightvalue" type="text" data-id="_weight" class="bulkvalue" placeholder="Skipped (empty)"/>
					</td>
					<td>
						 <select id="bulk_weight_round" style="display:none;">
						 	<option value="noround"><?php _e( "no rounding", "woocommerce-advbulkedit"); ?></option>
							<option value="roundup100"><?php _e( "round-up (100)", "woocommerce-advbulkedit"); ?></option>
							<option value="roundup10"><?php _e( "round-up (10)", "woocommerce-advbulkedit"); ?></option>
							<option value="roundup"><?php _e( "round-up (1)", "woocommerce-advbulkedit"); ?></option>
							<option value="roundup1"><?php _e( "round-up (0.1)", "woocommerce-advbulkedit"); ?></option>
							<option value="rounddown1"><?php _e( "round-down (0.1)", "woocommerce-advbulkedit"); ?></option>
							<option value="rounddown"><?php _e( "round-down (1)", "woocommerce-advbulkedit"); ?></option>
							<option value="rounddown10"><?php _e( "round-down (10)", "woocommerce-advbulkedit"); ?></option>
							<option value="rounddown100"><?php _e( "round-down (100)", "woocommerce-advbulkedit"); ?></option>
						 </select>
					</td>
				</tr>
				<tr data-id="_height">
					<td>
						<?php echo $arrTranslated['_height']; ?>
					</td>
					<td>
						 <select id="bulk_height" data-id="_height" class="bulksetdecimal">
							<option value="new"><?php echo $setnew; ?></option>
							<option value="incvalue"><?php _e( "increase by value", "woocommerce-advbulkedit"); ?></option>
							<option value="decvalue"><?php _e( "decrease by value", "woocommerce-advbulkedit"); ?></option>
							<option value="incpercent"><?php _e( "increase by %", "woocommerce-advbulkedit"); ?></option>
							<option value="decpercent"><?php _e( "decrease by %", "woocommerce-advbulkedit"); ?></option>
							<option value="delete"><?php echo $delete; ?></option>
						</select>
					</td>
					<td class="tdbulkvalue">
						<div class="imgButton sm mapto">
					    </div>
						<input id="bulk_heightvalue" type="text" data-id="_height" class="bulkvalue" placeholder="Skipped (empty)"/>
					</td>
					<td>
						 <select id="bulk_height_round" style="display:none;">
						 	<option value="noround"><?php _e( "no rounding", "woocommerce-advbulkedit"); ?></option>
							<option value="roundup100"><?php _e( "round-up (100)", "woocommerce-advbulkedit"); ?></option>
							<option value="roundup10"><?php _e( "round-up (10)", "woocommerce-advbulkedit"); ?></option>
							<option value="roundup"><?php _e( "round-up (1)", "woocommerce-advbulkedit"); ?></option>
							<option value="roundup1"><?php _e( "round-up (0.1)", "woocommerce-advbulkedit"); ?></option>
							<option value="rounddown1"><?php _e( "round-down (0.1)", "woocommerce-advbulkedit"); ?></option>
							<option value="rounddown"><?php _e( "round-down (1)", "woocommerce-advbulkedit"); ?></option>
							<option value="rounddown10"><?php _e( "round-down (10)", "woocommerce-advbulkedit"); ?></option>
							<option value="rounddown100"><?php _e( "round-down (100)", "woocommerce-advbulkedit"); ?></option>
						 </select>
					</td>
				</tr>
				<tr data-id="_width">
					<td>
						<?php echo $arrTranslated['_width']; ?>
					</td>
					<td>
						 <select id="bulk_width" data-id="_width" class="bulksetdecimal">
							<option value="new"><?php echo $setnew; ?></option>
							<option value="incvalue"><?php _e( "increase by value", "woocommerce-advbulkedit"); ?></option>
							<option value="decvalue"><?php _e( "decrease by value", "woocommerce-advbulkedit"); ?></option>
							<option value="incpercent"><?php _e( "increase by %", "woocommerce-advbulkedit"); ?></option>
							<option value="decpercent"><?php _e( "decrease by %", "woocommerce-advbulkedit"); ?></option>
							<option value="delete"><?php echo $delete; ?></option>
						</select>
					</td>
					<td class="tdbulkvalue">
						<div class="imgButton sm mapto">
					    </div>
						<input id="bulk_widthvalue" type="text" data-id="_width" class="bulkvalue" placeholder="Skipped (empty)"/>
					</td>
					<td>
						 <select id="bulk_width_round" style="display:none;">
						 	<option value="noround"><?php _e( "no rounding", "woocommerce-advbulkedit"); ?></option>
							<option value="roundup100"><?php _e( "round-up (100)", "woocommerce-advbulkedit"); ?></option>
							<option value="roundup10"><?php _e( "round-up (10)", "woocommerce-advbulkedit"); ?></option>
							<option value="roundup"><?php _e( "round-up (1)", "woocommerce-advbulkedit"); ?></option>
							<option value="roundup1"><?php _e( "round-up (0.1)", "woocommerce-advbulkedit"); ?></option>
							<option value="rounddown1"><?php _e( "round-down (0.1)", "woocommerce-advbulkedit"); ?></option>
							<option value="rounddown"><?php _e( "round-down (1)", "woocommerce-advbulkedit"); ?></option>
							<option value="rounddown10"><?php _e( "round-down (10)", "woocommerce-advbulkedit"); ?></option>
							<option value="rounddown100"><?php _e( "round-down (100)", "woocommerce-advbulkedit"); ?></option>
						 </select>
					</td>
				</tr>
				<tr data-id="_length">
					<td>
						<?php echo $arrTranslated['_length']; ?>
					</td>
					<td>
						 <select id="bulk_length" data-id="_length" class="bulksetdecimal">
							<option value="new"><?php echo $setnew; ?></option>
							<option value="incvalue"><?php _e( "increase by value", "woocommerce-advbulkedit"); ?></option>
							<option value="decvalue"><?php _e( "decrease by value", "woocommerce-advbulkedit"); ?></option>
							<option value="incpercent"><?php _e( "increase by %", "woocommerce-advbulkedit"); ?></option>
							<option value="decpercent"><?php _e( "decrease by %", "woocommerce-advbulkedit"); ?></option>
							<option value="delete"><?php echo $delete; ?></option>
						</select>
					</td>
					<td class="tdbulkvalue">
						<div class="imgButton sm mapto">
					    </div>
						<input id="bulk_lengthvalue" type="text" data-id="_length" class="bulkvalue" placeholder="Skipped (empty)" />
					</td>
					<td>
						 <select id="bulk_length_round" style="display:none;">
						 	<option value="noround"><?php _e( "no rounding", "woocommerce-advbulkedit"); ?></option>
							<option value="roundup100"><?php _e( "round-up (100)", "woocommerce-advbulkedit"); ?></option>
							<option value="roundup10"><?php _e( "round-up (10)", "woocommerce-advbulkedit"); ?></option>
							<option value="roundup"><?php _e( "round-up (1)", "woocommerce-advbulkedit"); ?></option>
							<option value="roundup1"><?php _e( "round-up (0.1)", "woocommerce-advbulkedit"); ?></option>
							<option value="rounddown1"><?php _e( "round-down (0.1)", "woocommerce-advbulkedit"); ?></option>
							<option value="rounddown"><?php _e( "round-down (1)", "woocommerce-advbulkedit"); ?></option>
							<option value="rounddown10"><?php _e( "round-down (10)", "woocommerce-advbulkedit"); ?></option>
							<option value="rounddown100"><?php _e( "round-down (100)", "woocommerce-advbulkedit"); ?></option>
						 </select>
					</td>
				</tr>
				<tr data-id="_stock">
					<td>
						<?php echo $arrTranslated['_stock']; ?>
					</td>
					<td>
						 <select id="bulk_stock" data-id="_stock" class="bulkselectinteger">
							<option value="new"><?php echo $setnew; ?></option>
							<option value="incvalue"><?php _e( "increase by value", "woocommerce-advbulkedit"); ?></option>
							<option value="decvalue"><?php _e( "decrease by value", "woocommerce-advbulkedit"); ?></option>
							<option value="delete"><?php echo $delete; ?></option>
						</select>
					</td>
					<td class="tdbulkvalue">
						<div class="imgButton sm mapto">
					    </div>
						<input id="bulk_stockvalue" type="text" data-id="_stock" class="bulkvalue" placeholder="Skipped (empty)"/>
					</td>
					<td>
						
					</td>
					<!--
					<td>
						 <select id="bulk_stock" data-id="_stock" class="bulksetdecimal">
							<option value="new"><?php echo $setnew; ?></option>
							<option value="incvalue"><?php _e( "increase by value", "woocommerce-advbulkedit"); ?></option>
							<option value="decvalue"><?php _e( "decrease by value", "woocommerce-advbulkedit"); ?></option>
							<option value="incpercent"><?php _e( "increase by %", "woocommerce-advbulkedit"); ?></option>
							<option value="decpercent"><?php _e( "decrease by %", "woocommerce-advbulkedit"); ?></option>
							<option value="delete"><?php echo $delete; ?></option>
						</select>
					</td>
					<td class="tdbulkvalue">
						<div class="imgButton sm mapto">
					    </div>
						<input id="bulk_stockvalue" type="text" data-id="_stock" class="bulkvalue" placeholder="Skipped (empty)"/>
					</td>
					<td>
						 <select id="bulk_stock_round" style="display:none;">
						 	<option value="noround"><?php _e( "no rounding", "woocommerce-advbulkedit"); ?></option>
							<option value="roundup100"><?php _e( "round-up (100)", "woocommerce-advbulkedit"); ?></option>
							<option value="roundup10"><?php _e( "round-up (10)", "woocommerce-advbulkedit"); ?></option>
							<option value="roundup"><?php _e( "round-up (1)", "woocommerce-advbulkedit"); ?></option>
							<option value="roundup1"><?php _e( "round-up (0.1)", "woocommerce-advbulkedit"); ?></option>
							<option value="rounddown1"><?php _e( "round-down (0.1)", "woocommerce-advbulkedit"); ?></option>
							<option value="rounddown"><?php _e( "round-down (1)", "woocommerce-advbulkedit"); ?></option>
							<option value="rounddown10"><?php _e( "round-down (10)", "woocommerce-advbulkedit"); ?></option>
							<option value="rounddown100"><?php _e( "round-down (100)", "woocommerce-advbulkedit"); ?></option>
						 </select>
					</td>-->
					
				</tr>
				<tr data-id="_stock_status">
					<td>
						<input id="set_stock_status" type="checkbox" class="bulkset" data-id="_stock_status"><label for="set_stock_status"><?php echo $arrTranslated['_stock_status']; ?></label>
					</td>
					<td>
						
					</td>
					<td class="nontextnumbertd">
						 <select id="bulk_stock_status">
							<option value="instock">In stock</option>
							<option value="outofstock">Out of stock</option>
						</select>
					</td>
					<td>
						
					</td>
				</tr>
				<tr data-id="_manage_stock">
					<td>
						<input id="set_manage_stock" type="checkbox" class="bulkset" data-id="_manage_stock"><label for="set_manage_stock"><?php echo $arrTranslated['_manage_stock']; ?></label>
					</td>
					<td>
						
					</td>
					<td class="nontextnumbertd">
						 <select id="bulk_manage_stock">
							<option value="yes">Yes</option>
							<option value="no">No</option>
						</select>
					</td>
					<td>
						
					</td>
				</tr>
				<tr data-id="_backorders">
					<td>
						<input id="set_backorders" type="checkbox" class="bulkset" data-id="_backorders"><label for="set_backorders"><?php echo $arrTranslated['_backorders']; ?></label>
					</td>
					<td>
						
					</td>
					<td class="nontextnumbertd">
						 <select id="bulk_backorders">
							<option value="Do not allow">Do not allow</option>
							<option value="Allow but notify">Allow but notify</option>
							<option value="Allow">Allow</option>
						</select>
					</td>
					<td>
						
					</td>
				</tr>
				<tr data-id="_sold_individually">
					<td>
						<input id="set_sold_individually" type="checkbox" class="bulkset" data-id="_sold_individually"><label for="set_sold_individually"><?php echo $arrTranslated['_sold_individually']; ?></label>
					</td>
					<td>
						
					</td>
					<td class="nontextnumbertd">
						 <select id="bulk_sold_individually">
							<option value="yes">Yes</option>
							<option value="no">No</option>
						</select>
					</td>
					<td>
						
					</td>
				</tr>
				<tr data-id="product_shipping_class">
					<td>
						<input id="setproduct_shipping_class" type="checkbox" class="bulkset" data-id="product_shipping_class" data-type="customtaxh"><label for="setproduct_shipping_class"><?php echo $arrTranslated['product_shipping_class']; ?></label>
					</td>
					<td>
						
					</td>
					<td class="nontextnumbertd">
						 <select id="bulkproduct_shipping_class" class="makechosen catselset" style="width:250px;" data-placeholder="select">
						 <option value="">none</option>
						<?php
							//shipping class
						$args = array(
						    'number'     => 99999,
						    'orderby'    => 'slug',
						    'order'      => 'ASC',
						    'hide_empty' => false,
						    'include'    => '',
							'fields'     => 'all'
						);

						$woo_categories = get_terms( 'product_shipping_class', $args );
						foreach($woo_categories as $category){
						    if(!is_object($category)) continue;
						    if(!property_exists($category,'name')) continue;
						    if(!property_exists($category,'term_id')) continue;
						   	echo '<option value="'.$category->term_id.'" >'.$category->name.'</option>';
						};
						?>
						</select>
					</td>
					<td>
						
					</td>
				</tr>
				<tr data-id="_purchase_note">
					<td>
						<?php echo $arrTranslated['_purchase_note']; ?>
					</td>
					<td>
						 <select id="bulk_purchase_note" class="bulkselect" data-id="_purchase_note">
							<option value="new"><?php echo $setnew; ?></option>
							<option value="prepend"><?php echo $prepend; ?></option>
							<option value="append"><?php echo $append; ?></option>
							<option value="replace"><?php echo $replacetext; ?></option>
							<option value="replaceregexp">replace regexp</option>
						</select>
						<label class="labelignorecase" style="display:none;">
						<input class="inputignorecase" type="checkbox">
						<?php echo $ignorecase; ?></label>
					</td>
					<td class="tdbulkvalue">
						<div class="imgButton sm mapto">
					    </div>
						<textarea id="bulk_purchase_notevalue" rows="1" cols="15" data-id="_purchase_note" class="bulkvalue" placeholder="Skipped (empty)"></textarea>
					</td>
					<td>
						<div class="divwithvalue" style="display:none;"><?php echo $withtext; ?> <textarea class="inputwithvalue" rows="1" cols="15"></textarea></div>
					</td>
				</tr>
				<tr data-id="post_status">
					<td>
						<input id="setpost_status" type="checkbox" class="bulkset" data-id="post_status"><label for="setpost_status"><?php echo $arrTranslated['post_status']; ?></label>
					</td>
					<td>
						
					</td>
					<td class="nontextnumbertd">
						 <select id="bulkpost_status">
							<option value="publish">Publish</option>
							<option value="draft">Draft</option>
							<option value="private">Private</option>
						</select>
					</td>
					<td>
						
					</td>
				</tr>
				<tr data-id="_visibility">
					<td>
						<input id="set_visibility" type="checkbox" class="bulkset" data-id="_visibility"><label for="set_visibility"><?php echo $arrTranslated['_visibility']; ?></label>
					</td>
					<td>
						
					</td>
					<td class="nontextnumbertd">
						 <select id="bulk_visibility">
							<option value="Catalog/search">Catalog/search</option>
							<option value="Catalog">Catalog</option>
							<option value="Search">Search</option>
							<option value="Hidden">Hidden</option>
						</select>
					</td>
					<td>
						
					</td>
				</tr>
				<tr data-id="_upsell_ids">
					<td>
						<?php echo $arrTranslated['_upsell_ids']; ?>
					</td>
					<td>
						 <select id="bulk_upsell_ids" class="bulkselect">
							<option value="new"><?php echo $setnew; ?></option>
							<option value="prepend"><?php echo $prepend; ?></option>
							<option value="append"><?php echo $append; ?></option>
							<option value="replace"><?php echo $replacetext; ?></option>
						</select>
						<label class="labelignorecase" style="display:none;">
						<input class="inputignorecase" type="checkbox">
						<?php echo $ignorecase; ?></label>
					</td>
					<td class="nontextnumbertd">
						<input id="bulk_upsell_idsvalue" type="text" data-id="_upsell_ids" class="bulkvalue" placeholder="Skipped (empty)"/>
					</td>
					<td>
						<div class="divwithvalue" style="display:none;"><?php echo $withtext; ?> <input class="inputwithvalue" type="text"></div>
					</td>
				</tr>
				<tr data-id="_crosssell_ids">
					<td>
						<?php echo $arrTranslated['_crosssell_ids']; ?>
					</td>
					<td>
						 <select id="bulk_crosssell_ids" class="bulkselect">
							<option value="new"><?php echo $setnew; ?></option>
							<option value="prepend"><?php echo $prepend; ?></option>
							<option value="append"><?php echo $append; ?></option>
							<option value="replace"><?php echo $replacetext; ?></option>
						</select>
						<label class="labelignorecase" style="display:none;">
						<input class="inputignorecase" type="checkbox">
						<?php echo $ignorecase; ?></label>
					</td>
					<td class="nontextnumbertd">
						<input id="bulk_crosssell_idsvalue" type="text" data-id="_crosssell_ids" class="bulkvalue" placeholder="Skipped (empty)"/>
					</td>
					<td>
						<div class="divwithvalue" style="display:none;"><?php echo $withtext; ?> <input class="inputwithvalue" type="text"></div>
					</td>
				</tr>
				<tr data-id="_downloadable">
					<td>
						<input id="set_downloadable" type="checkbox" class="bulkset" data-id="_downloadable"><label for="set_downloadable"><?php echo $arrTranslated['_downloadable']; ?></label>
					</td>
					<td>
						
					</td>
					<td class="nontextnumbertd">
						 <select id="bulk_downloadable">
							<option value="yes">Yes</option>
							<option value="no">No</option>
						</select>
					</td>
					<td>
						
					</td>
				</tr>
				<tr data-id="_virtual">
					<td>
						<input id="set_virtual" type="checkbox" class="bulkset" data-id="_virtual"><label for="set_virtual"><?php echo $arrTranslated['_virtual']; ?></label>
					</td>
					<td>
						
					</td>
					<td class="nontextnumbertd">
						 <select id="bulk_virtual">
							<option value="yes">Yes</option>
							<option value="no">No</option>
						</select>
					</td>
					<td>
						
					</td>
				</tr>
				<tr data-id="_download_limit">
					<td>
						<?php echo $arrTranslated['_download_limit']; ?>
					</td>
					<td>
						 <select id="bulk_download_limit" data-id="_download_limit" class="bulkselectinteger">
							<option value="new"><?php echo $setnew; ?></option>
							<option value="incvalue"><?php _e( "increase by value", "woocommerce-advbulkedit"); ?></option>
							<option value="decvalue"><?php _e( "decrease by value", "woocommerce-advbulkedit"); ?></option>
							<!--<option value="incpercent"><?php _e( "increase by %", "woocommerce-advbulkedit"); ?></option>-->
							<!--<option value="decpercent"><?php _e( "decrease by %", "woocommerce-advbulkedit"); ?></option>-->
							<option value="delete">set unlimited (<?php echo $delete; ?>)</option>
						</select>
					</td>
					<td class="tdbulkvalue">
						<div class="imgButton sm mapto">
					    </div>
						<input id="bulk_download_limitvalue" type="text" data-id="_download_limit" class="bulkvalue" placeholder="Skipped (empty)" />
					</td>
					<td>
						
					</td>
				</tr>
				<tr data-id="_download_expiry">
					<td>
						<?php echo $arrTranslated['_download_expiry']; ?>
					</td>
					<td>
						 <select id="bulk_download_expiry" data-id="_download_expiry" class="bulkselectinteger">
							<option value="new"><?php echo $setnew; ?></option>
							<option value="incvalue"><?php _e( "increase by value", "woocommerce-advbulkedit"); ?></option>
							<option value="decvalue"><?php _e( "decrease by value", "woocommerce-advbulkedit"); ?></option>
							<!--<option value="incpercent"><?php _e( "increase by %", "woocommerce-advbulkedit"); ?></option>-->
							<!--<option value="decpercent"><?php _e( "decrease by %", "woocommerce-advbulkedit"); ?></option>-->
							<option value="delete">set unlimited (<?php echo $delete; ?>)</option>
						</select>
					</td>
					<td class="tdbulkvalue">
						<div class="imgButton sm mapto">
					    </div>
						<input id="bulk_download_expiryvalue" type="text" data-id="_download_expiry" class="bulkvalue" placeholder="Skipped (empty)" />
					</td>
					<td>
						
					</td>
				</tr>
				<tr data-id="_download_type">
					<td>
						<input id="set_download_type" type="checkbox" class="bulkset" data-id="_download_type"><label for="set_download_type"><?php echo $arrTranslated['_download_type']; ?></label>
					</td>
					<td>
						
					</td>
					<td class="nontextnumbertd">
						 <select id="bulk_download_type">
							<option value="Standard">Standard</option>
							<option value="Application">Application</option>
							<option value="Music">Music</option>
						</select>
					</td>
					<td>
						
					</td>
				</tr>
				<tr data-id="_featured">
					<td>
						<input id="set_featured" type="checkbox" class="bulkset" data-id="_featured"><label for="set_featured"><?php echo $arrTranslated['_featured']; ?></label>
					</td>
					<td>
						
					</td>
					<td class="nontextnumbertd">
						 <select id="bulk_featured">
							<option value="yes">Yes</option>
							<option value="no">No</option>
						</select>
					</td>
					<td>
						
					</td>
				</tr>
				<tr data-id="_product_url">
					<td>
						<?php echo $arrTranslated['_product_url']; ?>
					</td>
					<td>
						 <select id="bulk_product_url" class="bulkselect" data-id="_product_url">
							<option value="new"><?php echo $setnew; ?></option>
							<option value="prepend"><?php echo $prepend; ?></option>
							<option value="append"><?php echo $append; ?></option>
							<option value="replace"><?php echo $replacetext; ?></option>
							<option value="replaceregexp">replace regexp</option>
							<option value="delete"><?php echo $delete; ?></option>
						</select>
						<label class="labelignorecase" style="display:none;">
						<input class="inputignorecase" type="checkbox">
						<?php echo $ignorecase; ?></label>
					</td>
					<td class="tdbulkvalue">
						<div class="imgButton sm mapto">
					    </div>
						<input id="bulk_product_urlvalue" type="text" data-id="_product_url" class="bulkvalue" placeholder="Skipped (empty)"/>
					</td>
					<td>
						<div class="divwithvalue" style="display:none;"><?php echo $withtext; ?> <input class="inputwithvalue" type="text"></div>
					</td>
				</tr>
				<tr data-id="_button_text">
					<td>
						<?php echo $arrTranslated['_button_text']; ?>
					</td>
					<td>
						 <select id="bulk_button_text" class="bulkselect" data-id="_button_text">
							<option value="new"><?php echo $setnew; ?></option>
							<option value="prepend"><?php echo $prepend; ?></option>
							<option value="append"><?php echo $append; ?></option>
							<option value="replace"><?php echo $replacetext; ?></option>
							<option value="replaceregexp">replace regexp</option>
							<option value="delete"><?php echo $delete; ?></option>
						</select>
						<label class="labelignorecase" style="display:none;">
						<input class="inputignorecase" type="checkbox">
						<?php echo $ignorecase; ?></label>
					</td>
					<td class="tdbulkvalue">
						<div class="imgButton sm mapto">
					    </div>
						<input id="bulk_button_textvalue" type="text" data-id="_button_text" class="bulkvalue" placeholder="Skipped (empty)"/>
					</td>
					<td>
						<div class="divwithvalue" style="display:none;"><?php echo $withtext; ?> <input class="inputwithvalue" type="text"></div>
					</td>
				</tr>
				<tr data-id="menu_order">
					<td>
						<?php echo $arrTranslated['menu_order']; ?>
					</td>
					<td>
						 <select id="bulkmenu_order" data-id="menu_order">
							<option value="new"><?php echo $setnew; ?></option>
							<option value="incvalue"><?php _e( "increase by value", "woocommerce-advbulkedit"); ?></option>
							<option value="decvalue"><?php _e( "decrease by value", "woocommerce-advbulkedit"); ?></option>
						</select>
					</td>
					<td class="tdbulkvalue">
						<div class="imgButton sm mapto">
					    </div>
						<input id="bulkmenu_ordervalue" type="text" data-id="menu_order" class="bulkvalue" placeholder="Skipped (empty)" />
					</td>
					<td>
						
					</td>
				</tr>
				<tr data-id="_variation_description">
					<td>
						<?php echo $arrTranslated['_variation_description']; ?>
					</td>
					<td>
						 <select id="bulk_variation_description" class="bulkselect" data-id="_variation_description">
							<option value="new"><?php echo $setnew; ?></option>
							<option value="prepend"><?php echo $prepend; ?></option>
							<option value="append"><?php echo $append; ?></option>
							<option value="replace"><?php echo $replacetext; ?></option>
							<option value="replaceregexp">replace regexp</option>
						</select>
						<label class="labelignorecase" style="display:none;">
						<input class="inputignorecase" type="checkbox">
						<?php echo $ignorecase; ?></label>
					</td>
					<td class="tdbulkvalue">
						<div class="imgButton sm mapto">
					    </div>
						<textarea id="bulk_variation_descriptionevalue" rows="1" cols="15" data-id="_variation_description" class="bulkvalue" placeholder="Skipped (empty)"></textarea>
					</td>
					<td>
						<div class="divwithvalue" style="display:none;"><?php echo $withtext; ?> <textarea class="inputwithvalue" rows="1" cols="15"></textarea></div>
					</td>
				</tr>
				<tr data-id="product_type">
					<td>
						<input id="setproduct_type" type="checkbox" class="bulkset" data-id="product_type" data-type="customtaxh"><label for="setproduct_type"><?php echo $arrTranslated['product_type']; ?></label>
					</td>
					<td>
						
					</td>
					<td class="nontextnumbertd">
						 <select id="bulkproduct_type" class="makechosen catselset" style="width:250px;" data-placeholder="select">
						<?php
							//categories
						$args = array(
						    'number'     => 99999,
						    'orderby'    => 'slug',
						    'order'      => 'ASC',
						    'hide_empty' => false,
						    'include'    => '',
							'fields'     => 'all'
						);

						$woo_categories = get_terms( 'product_type', $args );
						foreach($woo_categories as $category){
						    if(!is_object($category)) continue;
						    if(!property_exists($category,'name')) continue;
						    if(!property_exists($category,'term_id')) continue;
						   	echo '<option value="'.$category->term_id.'" >'.$category->name.'</option>';
						};
						?>
						</select>
					</td>
					<td>
						
					</td>
				</tr>
				<tr data-id="comment_status">
					<td>
						<input id="setcomment_status" type="checkbox" class="bulkset" data-id="comment_status"><label for="setcomment_status"><?php echo $arrTranslated['comment_status']; ?></label>
					</td>
					<td>
						
					</td>
					<td class="nontextnumbertd">
						 <select id="bulkcomment_status">
							<option value="yes">Yes</option>
							<option value="no">No</option>
						</select>
					</td>
					<td>
						
					</td>
				</tr>
				<tr data-id="grouped_items">
					<td>
						<input id="setgrouped_items" type="checkbox" class="bulkset" data-id="grouped_items" data-type="customtaxh"><label for="setgrouped_items"><?php echo $arrTranslated['grouped_items']; ?></label>
					</td>
					<td>
						
					</td>
					<td class="nontextnumbertd">
						 <select id="bulkgrouped_items" class="makechosen catselset" style="width:250px;" data-placeholder="select">
						 <option value="0"> Choose a grouped product...</option>
						<?php
						$argsgr = array(
							'posts_per_page'   => 500,
							'post_type' => 'product',
							'product_type' => 'grouped'
						);
						$query = new WP_Query( $argsgr );

						// The Loop
						while ( $query->have_posts() ) {
							$query->the_post();
							echo '<option value="'.$query->post->ID.'" >'.get_the_title().'</option>';
						}
						wp_reset_postdata();
						?>
						<?php
							//categories
					/*	$args = array(
						    'number'     => 99999,
						    'orderby'    => 'slug',
						    'order'      => 'ASC',
						    'hide_empty' => false,
						    'include'    => '',
							'fields'     => 'all'
						);

						$woo_categories = get_terms( 'product_type', $args );
						foreach($woo_categories as $category){
						    if(!is_object($category)) continue;
						    if(!property_exists($category,'name')) continue;
						    if(!property_exists($category,'term_id')) continue;
						   	echo '<option value="'.$category->term_id.'" >'.$category->name.'</option>';
						};*/
						?>
						</select>
					</td>
					<td>
						
					</td>
				</tr>
				<tr data-id="_custom_attributes">
					<td>
						<input id="set_custom_attributes" type="checkbox" class="bulkset" data-id="_custom_attributes"><label for="set_custom_attributes">Set <?php echo $arrTranslated['_custom_attributes']; ?></label>
					</td>
					<td>
				
						 
						 <select id="bulkadd_custom_attributes" class="bulkselect" data-id="_custom_attributes" disabled="disabled">
							<option value="new"><?php echo $setnew; ?></option>
							<option value="addvalue">add value</option>
							<option value="renameattr">rename attr</option>
							<option value="removename"><?php echo $arrTranslated['trans_remove_name']; ?></option>
							<option value="removevalue"><?php echo  $arrTranslated['trans_remove_value']; ?></option>
						</select>
					</td>
					<td class="tdbulkvalue">
					<div id="customname" >
						<label id="custnamelabel">Name:</label>
						<input id="bulk_custom_attributesname" type="text" data-id="_custom_attributes" class="bulkvalue" placeholder="Skipped (empty)"/>
						</div>
						<div id="customvalue">
						<label id="custvaluelabel">Value:</label>
						<input id="bulk_custom_attributesvalue" type="text" data-id="_custom_attributes"/>
						</div>
					</td>
					<td>
						(<select class="selectvisiblefp" disabled data-id="_custom_attributes">
						<option value="skip">skip</option><option value="andset">and set</option><option value="onlyset">only set</option>
						</select>&nbsp;<input type="checkbox" disabled class="visiblefp" data-id="_custom_attributes">Visible on p. p.)&nbsp;
						(<select disabled class="selectusedforvars" data-id="_custom_attributes"><option value="skip">skip</option><option value="andset">and set</option><option value="onlyset">only set</option>
						</select>&nbsp;<input type="checkbox" disabled class="usedforvars" data-id="_custom_attributes">Used for var.)
					</td>
				</tr>
				<tr data-id="post_author">
					<td>
						<input id="setpost_author" type="checkbox" class="bulkset" data-id="post_author" data-type="customtaxh"><label for="setpost_author"><?php echo $arrTranslated['post_author']; ?></label>
					</td>
					<td>
						
					</td>
					<td class="nontextnumbertd">
						 <select id="bulkpost_author" class="makechosen catselset" style="width:250px;" data-placeholder="select">
						<?php
							foreach ( $blogusers as $user ) 
							{
								echo '<option value="'.$user->ID.'" >'.$user->display_name.'</option>';
							}
						?>
						</select>
					</td>
					<td>
						
					</td>
				</tr>
				<!--<tr data-id="post_type">
					<td>
						<?php echo $arrTranslated['post_type'];  ?>
					</td>
					<td>
						 <select id="bulkpost_type" class="bulkselect">
							<option value="new"><?php echo $setnew; ?></option>
							<option value="prepend"><?php echo $prepend; ?></option>
							<option value="append"><?php echo $append; ?></option>
							<option value="replace"><?php echo $replacetext; ?></option>
						</select>
						<label class="labelignorecase" style="display:none;">
						<input class="inputignorecase" type="checkbox">
						<?php echo $ignorecase; ?></label>
					</td>
					<td class="tdbulkvalue">
						<div class="imgButton sm mapto">
					    </div>
						<input id="bulkpost_typevalue" type="text" placeholder="Skipped (empty)" data-id="post_type" class="bulkvalue"/>
					</td>
					<td>
						<div class="divwithvalue" style="display:none;"><?php echo $withtext; ?> <input class="inputwithvalue" type="text"></div>
					</td>
				</tr>-->
			</table>
			<br/>
			</div>
			
			<!--//select dialog-->
			<div id="selectdialog">
			<div id="selquickactions">
				<?php _e( "Quick actions", "woocommerce-advbulkedit"); ?>:
				<input id="selallproducts" class="button" type="button" value="<?php _e( "Select all products", "woocommerce-advbulkedit"); ?>" />
				<input id="selallvars" class="button" type="button" value="<?php _e( "Select all variations", "woocommerce-advbulkedit"); ?>" />
				<input id="seldupproducts" class="button" type="button" value="<?php _e( "Select duplicate products", "woocommerce-advbulkedit"); ?>" />
				(<select id="selectdupproducts">
					<option value="post_title"><?php _e('same title','woocommerce-advbulkedit'); ?></option>
					<option value="post_content"><?php _e('description','woocommerce-advbulkedit'); ?></option>
					<option value="post_excerpt"><?php _e('short description','woocommerce-advbulkedit'); ?></option>
				</select>)
				<!--<label><input id="sametitle" type="radio" value="0" name="dupproducts"><?php _e( "same title", "woocommerce-advbulkedit"); ?></label><br/>
				<label><input id="samedescription" type="radio" value="1" name="dupproducts"><?php _e( "description", "woocommerce-advbulkedit"); ?></label><br/>
				<label><input id="sameshortdescription" type="radio" value="2" name="dupproducts"><?php _e( "short description", "woocommerce-advbulkedit"); ?></label>-->
				<input id="seldupvars" class="button" type="button" value="<?php _e( "Select duplicate variations", "woocommerce-advbulkedit"); ?>" />
				(<?php _e( "same attributes", "woocommerce-advbulkedit"); ?> )
			</div>
			<hr />
			<div id="selectdiv">
			<select id="selectselect">
				<option value="select"><?php _e('select','woocommerce-advbulkedit'); ?></option>
				<option value="deselect"><?php _e('deselect','woocommerce-advbulkedit'); ?></option>
			</select>
			<select id="selectproduct">
				<option value="prodvar"><?php _e('products and variations','woocommerce-advbulkedit'); ?></option>
				<option value="prod"><?php _e('products only','woocommerce-advbulkedit'); ?></option>
				<option value="var"><?php _e('variations only','woocommerce-advbulkedit'); ?></option>
			</select>
			<?php _e('which meet','woocommerce-advbulkedit'); ?>
			<select id="selectany">
				<option value="any"><?php _e('any of the search criteria','woocommerce-advbulkedit'); ?></option>
				<option value="all"><?php _e('all of the search criteria','woocommerce-advbulkedit'); ?></option>
			</select>
			</div>
			<!--<hr />-->
			<?php 
				$t_contains = __( 'contains', 'woocommerce-advbulkedit');
				$t_doesnot = __( 'does not contain', 'woocommerce-advbulkedit');
				$t_starts = __( 'starts with', 'woocommerce-advbulkedit');
				$t_ends = __( 'ends with', 'woocommerce-advbulkedit');
				$t_isempty = __( 'field is empty', 'woocommerce-advbulkedit');
				 echo '<script>'; echo PHP_EOL;
				echo 'W3Ex.trans_contains = "'.$t_contains.'";'; echo PHP_EOL;
				echo 'W3Ex.trans_doesnot = "'.$t_doesnot.'";'; echo PHP_EOL;
				echo 'W3Ex.trans_starts = "'.$t_starts.'";'; echo PHP_EOL;
				echo 'W3Ex.trans_ends = "'.$t_ends.'";'; echo PHP_EOL;
				echo 'W3Ex.trans_isempty = "'.$t_isempty.'";'; echo PHP_EOL;
//				echo 'W3Ex.trans_withtext = "'.$withtext.'";'; echo PHP_EOL;			
//				echo 'W3Ex.trans_delete = "'.$delete.'";'; echo PHP_EOL;			
				echo "</script>";
			 ?>
			<table class="custstyle-table">
				<tr data-id="post_title" style="display: table-row;">
					<td style="width:25% !important;">
						<?php echo $arrTranslated['post_title']; ?>
					</td>
					<td>
						 <select id="selectpost_title" class="selectselect" data-id="post_title">
							<option value="con"><?php echo $t_contains; ?></option>
							<option value="notcon"><?php echo $t_doesnot; ?></option>
							<option value="start"><?php echo $t_starts; ?></option>
							<option value="end"><?php echo $t_ends; ?></option>
						</select>
					</td>
					<td>
						<input id="selectpost_titlevalue" type="text" placeholder="Skipped (empty)" data-id="post_title" class="selectvalue"/>
					</td>
					<td>
						<label><input data-id="post_title" class="selectifignorecase" type="checkbox"> <?php echo $ignorecase; ?></label>
					</td>
					<td>
						<input data-id="post_title" class="checkboxifspecial" type="checkbox">
						<select class="selectsplit" disabled="disabled"><option value="split">split commas</option><option value="regexp">reg exp</option></select>
						<select class="selectsplitand" disabled="disabled"><option value="and">AND</option><option value="or">OR</option></select>
					</td>
				</tr>
				<tr data-id="post_content">
					<td>
						<?php echo $arrTranslated['post_content']; ?>
					</td>
					<td>
						 <select id="selectpost_content" class="selectselect" data-id="post_content">
							<option value="con"><?php echo $t_contains; ?></option>
							<option value="notcon"><?php echo $t_doesnot; ?></option>
							<option value="start"><?php echo $t_starts; ?></option>
							<option value="end"><?php echo $t_ends; ?></option>
							<option value="empty"><?php echo $t_isempty; ?></option>
						</select>
					</td>
					<td>
						<textarea cols="15" rows="1" id="selectpost_contentvalue" placeholder="Skipped (empty)" data-id="post_content" class="selectvalue"></textarea >
					</td>
					<td>
						<label><input data-id="post_content" class="selectifignorecase" type="checkbox"> <?php echo $ignorecase; ?></label>
					</td>
					<td>
						<input data-id="post_content" class="checkboxifspecial" type="checkbox">
						<select class="selectsplit" disabled="disabled"><option value="split">split commas</option><option value="regexp">reg exp</option></select>
						<select class="selectsplitand" disabled="disabled"><option value="and">AND</option><option value="or">OR</option></select>
					</td>
				</tr>
				<tr data-id="post_excerpt">
					<td>
						<?php echo $arrTranslated['post_excerpt']; ?>
					</td>
					<td>
						 <select id="selectpost_excerpt" class="selectselect" data-id="post_excerpt">
							<option value="con"><?php echo $t_contains; ?></option>
							<option value="notcon"><?php echo $t_doesnot; ?></option>
							<option value="start"><?php echo $t_starts; ?></option>
							<option value="end"><?php echo $t_ends; ?></option>
							<option value="empty"><?php echo $t_isempty; ?></option>
						</select>
					</td>
					<td>
						<textarea cols="15" rows="1" id="selectpost_excerptvalue" placeholder="Skipped (empty)" data-id="post_excerpt" class="selectvalue"></textarea >
					</td>
					<td>
						<label><input data-id="post_excerpt" class="selectifignorecase" type="checkbox"> <?php echo $ignorecase; ?></label>
					</td>
					<td>
						<input data-id="post_excerpt" class="checkboxifspecial" type="checkbox">
						<select class="selectsplit" disabled="disabled"><option value="split">split commas</option><option value="regexp">reg exp</option></select>
						<select class="selectsplitand" disabled="disabled"><option value="and">AND</option><option value="or">OR</option></select>
					</td>
				</tr>
				<tr data-id="post_name">
					<td>
						<?php echo $arrTranslated['post_name']; ?>
					</td>
					<td>
						 <select id="selectpost_name" class="selectselect" data-id="post_name">
							<option value="con"><?php echo $t_contains; ?></option>
							<option value="notcon"><?php echo $t_doesnot; ?></option>
							<option value="start"><?php echo $t_starts; ?></option>
							<option value="end"><?php echo $t_ends; ?></option>
							<option value="iscon">is contained in</option>
						</select>
					</td>
					<td>
						<textarea cols="15" rows="1" id="selectpost_namevalue" placeholder="Skipped (empty)" data-id="post_name" class="selectvalue"></textarea >
					</td>
					<td>
						<label><input data-id="post_name" class="selectifignorecase" type="checkbox"> <?php echo $ignorecase; ?></label>
					</td>
					<td>
						<input data-id="post_name" class="checkboxifspecial" type="checkbox">
						<select class="selectsplit" disabled="disabled"><option value="split">split commas</option><option value="regexp">reg exp</option></select>
						<select class="selectsplitand" disabled="disabled"><option value="and">AND</option><option value="or">OR</option></select>
					</td>
				</tr>
				<tr data-id="_sku">
					<td>
						<?php echo $arrTranslated['_sku']; ?>
					</td>
					<td>
						 <select id="select_sku" class="selectselect" data-id="_sku">
							<option value="con"><?php echo $t_contains; ?></option>
							<option value="notcon"><?php echo $t_doesnot; ?></option>
							<option value="start"><?php echo $t_starts; ?></option>
							<option value="end"><?php echo $t_ends; ?></option>
							<option value="empty"><?php echo $t_isempty; ?></option>
						</select>
					</td>
					<td>
						<input id="select_skuvalue" type="text" placeholder="Skipped (empty)" data-id="_sku" class="selectvalue"/>
					</td>
					<td>
						<label><input data-id="_sku" class="selectifignorecase" type="checkbox"> <?php echo $ignorecase; ?></label>
					</td>
					<td>
						<input data-id="_sku" class="checkboxifspecial" type="checkbox">
						<select class="selectsplit" disabled="disabled"><option value="split">split commas</option><option value="regexp">reg exp</option></select>
						<select class="selectsplitand" disabled="disabled"><option value="and">AND</option><option value="or">OR</option></select>
					</td>
				</tr>
				<tr data-id="product_cat">
					<td>
						<?php echo $arrTranslated['product_cat']; ?>
					</td>
					<td>
						 <select id="selectproduct_cat" class="selectselect" data-id="product_cat">
							<option value="con"><?php echo $t_contains; ?></option>
							<option value="notcon"><?php echo $t_doesnot; ?></option>
							<option value="start"><?php echo $t_starts; ?></option>
							<option value="end"><?php echo $t_ends; ?></option>
							<option value="empty"><?php echo $t_isempty; ?></option>
						</select>
					</td>
					<td>
						<input id="selectproduct_catvalue" type="text" placeholder="Skipped (empty)" data-id="product_cat" class="selectvalue"/>
					</td>
					<td>
						<label><input data-id="product_cat" class="selectifignorecase" type="checkbox"> <?php echo $ignorecase; ?></label>
					</td>
					<td>
						<input data-id="product_cat" class="checkboxifspecial" type="checkbox">
						<select class="selectsplit" disabled="disabled"><option value="split">split commas</option><option value="regexp">reg exp</option></select>
						<select class="selectsplitand" disabled="disabled"><option value="and">AND</option><option value="or">OR</option></select>
					</td>
				</tr>
				<tr data-id="product_tag">
					<td>
						<?php echo $arrTranslated['product_tag']; ?>
					</td>
					<td>
						 <select id="selectproduct_tag" class="selectselect" data-id="product_tag">
							<option value="con"><?php echo $t_contains; ?></option>
							<option value="notcon"><?php echo $t_doesnot; ?></option>
							<option value="start"><?php echo $t_starts; ?></option>
							<option value="end"><?php echo $t_ends; ?></option>
							<option value="empty"><?php echo $t_isempty; ?></option>
						</select>
					</td>
					<td>
						<input id="selectproduct_tagvalue" type="text" placeholder="Skipped (empty)" data-id="product_tag" class="selectvalue"/>
					</td>
					<td>
						<label><input data-id="product_tag" class="selectifignorecase" type="checkbox"> <?php echo $ignorecase; ?></label>
					</td>
					<td>
						<input data-id="product_tag" class="checkboxifspecial" type="checkbox">
						<select class="selectsplit" disabled="disabled"><option value="split">split commas</option><option value="regexp">reg exp</option></select>
						<select class="selectsplitand" disabled="disabled"><option value="and">AND</option><option value="or">OR</option></select>
					</td>
				</tr>
				<tr data-id="_regular_price">
					<td>
						<?php echo $arrTranslated['_regular_price']; ?>
					</td>
					<td>
						 <select id="select_regular_price" class="selectselect" data-id="_regular_price">
							<option value="more">></option>
							<option value="less"><</option>
							<option value="equal">==</option>
							<option value="moree">>=</option>
							<option value="lesse"><=</option>
							<option value="empty"><?php echo $t_isempty; ?></option>
						</select>
					</td>
					<td>
						<input id="select_regular_pricevalue" type="text" placeholder="Skipped (empty)"  data-id="_regular_price" class="selectvalue" />
					</td>
					<td>
						
					</td>
					<td>
					</td>
				</tr>
				<tr data-id="_sale_price">
					<td>
						<?php echo $arrTranslated['_sale_price']; ?>
					</td>
					<td>
						 <select id="select_sale_price" class="selectselect" data-id="_sale_price">
							<option value="more">></option>
							<option value="less"><</option>
							<option value="equal">==</option>
							<option value="moree">>=</option>
							<option value="lesse"><=</option>
							<option value="empty"><?php echo $t_isempty; ?></option>
						</select>
					</td>
					<td>
						<input id="select_sale_pricevalue" type="text" placeholder="Skipped (empty)" data-id="_sale_price" class="selectvalue" />
					</td>
					<td>
						 <!--<input type="checkbox" id="selectsaleskip"><label id="selectsaleskiplabel" for="selectsaleskip"> Skip products that have a sale price</label>-->
					</td>
					<td>
					</td>
				</tr>
				<tr data-id="_tax_status">
					<td>
						<input id="setsel_tax_status" type="checkbox" class="selectset" data-id="_tax_status"><label for="setsel_tax_status"><?php echo $arrTranslated['_tax_status']; ?></label>
					</td>
					<td>
						
					</td>
					<td>
						 <select id="select_tax_status">
							<option value="Taxable">Taxable</option>
							<option value="Shipping only">Shipping only</option>
							<option value="None">None</option>
						</select>
					</td>
					<td>
						
					</td>
					<td>
					</td>
				</tr>
				<tr data-id="_tax_class">
					<td>
						<input id="setsel_tax_class" type="checkbox" class="selectset" data-id="_tax_class"><label for="setsel_tax_class"><?php echo $arrTranslated['_tax_class']; ?></label>
					</td>
					<td>
						
					</td>
					<td>
						 <select id="select_tax_class">
							<option value="Standard">Standard</option>
							<option value="Reduced Rate">Reduced Rate</option>
							<option value="Zero Rate">Zero Rate</option>
						</select>
					</td>
					<td>
						
					</td>
					<td>
					</td>
				</tr>
				<tr data-id="_weight">
					<td>
						<?php echo $arrTranslated['_weight']; ?>
					</td>
					<td>
						 <select id="select_weight" class="selectselect" data-id="_weight">
							<option value="more">></option>
							<option value="less"><</option>
							<option value="equal">==</option>
							<option value="moree">>=</option>
							<option value="lesse"><=</option>
							<option value="empty"><?php echo $t_isempty; ?></option>
						</select>
					</td>
					<td>
						<input id="select_weightvalue" type="text" placeholder="Skipped (empty)" data-id="_weight" class="selectvalue" />
					</td>
					<td>
						
					</td>
					<td>
					</td>
				</tr>
				<tr data-id="_height" class="selectselect">
					<td>
						<?php echo $arrTranslated['_height']; ?>
					</td>
					<td>
						 <select id="select_height" class="selectselect" data-id="_height">
							<option value="more">></option>
							<option value="less"><</option>
							<option value="equal">==</option>
							<option value="moree">>=</option>
							<option value="lesse"><=</option>
							<option value="empty"><?php echo $t_isempty; ?></option>
						</select>
					</td>
					<td>
						<input id="select_heightvalue" type="text" placeholder="Skipped (empty)" data-id="_height" class="selectvalue" />
					</td>
					<td>
						
					</td>
					<td>
					</td>
				</tr>
				<tr data-id="_width">
					<td>
						<?php echo $arrTranslated['_width']; ?>
					</td>
					<td>
						 <select id="select_width" class="selectselect" data-id="_width">
							<option value="more">></option>
							<option value="less"><</option>
							<option value="equal">==</option>
							<option value="moree">>=</option>
							<option value="lesse"><=</option>
							<option value="empty"><?php echo $t_isempty; ?></option>
						</select>
					</td>
					<td>
						<input id="select_widthvalue" type="text" placeholder="Skipped (empty)" data-id="_width" class="selectvalue" />
					</td>
					<td>
						
					</td>
					<td>
					</td>
				</tr>
				<tr data-id="_length">
					<td>
						<?php echo $arrTranslated['_length']; ?>
					</td>
					<td>
						 <select id="select_length" class="selectselect" data-id="_length">
							<option value="more">></option>
							<option value="less"><</option>
							<option value="equal">==</option>
							<option value="moree">>=</option>
							<option value="lesse"><=</option>
							<option value="empty"><?php echo $t_isempty; ?></option>
						</select>
					</td>
					<td>
						<input id="select_lengthvalue" type="text" placeholder="Skipped (empty)" data-id="_length" class="selectvalue" />
					</td>
					<td>
						
					</td>
					<td>
					</td>
				</tr>
				<tr data-id="_stock">
					<td>
						<?php echo $arrTranslated['_stock']; ?>
					</td>
					<td>
						 <select id="select_stock" class="selectselect" data-id="_stock">
							<option value="more">></option>
							<option value="less"><</option>
							<option value="equal">==</option>
							<option value="moree">>=</option>
							<option value="lesse"><=</option>
							<option value="empty"><?php echo $t_isempty; ?></option>
						</select>
					</td>
					<td>
						<input id="select_stockvalue" type="text" placeholder="Skipped (empty)" data-id="_stock" class="selectvalue" />
					</td>
					<td>
						
					</td>
					<td>
					</td>
				</tr>
				<tr data-id="_stock_status">
					<td>
						<input id="setsel_stock_status" type="checkbox" class="selectset" data-id="_stock_status"><label for="setsel_stock_status"><?php echo $arrTranslated['_stock_status']; ?></label>
					</td>
					<td>
						
					</td>
					<td>
						 <select id="select_stock_status">
							<option value="instock">In stock</option>
							<option value="outofstock">Out of stock</option>
						</select>
					</td>
					<td>
						
					</td>
					<td>
					</td>
				</tr>
				<tr data-id="_manage_stock">
					<td>
						<input id="setsel_manage_stock" type="checkbox" class="selectset" data-id="_manage_stock"><label for="setsel_manage_stock"><?php echo $arrTranslated['_manage_stock']; ?></label>
					</td>
					<td>
						
					</td>
					<td>
						 <select id="select_manage_stock">
							<option value="yes">Yes</option>
							<option value="no">No</option>
						</select>
					</td>
					<td>
						
					</td>
					<td>
					</td>
				</tr>
				<tr data-id="_backorders">
					<td>
						<input id="setsel_backorders" type="checkbox" class="selectset" data-id="_backorders"><label for="setsel_backorders"><?php echo $arrTranslated['_backorders']; ?></label>
					</td>
					<td>
						
					</td>
					<td>
						 <select id="select_backorders">
							<option value="Do not allow">Do not allow</option>
							<option value="Allow but notify">Allow but notify</option>
							<option value="Allow">Allow</option>
						</select>
					</td>
					<td>
						
					</td>
					<td>
					</td>
				</tr>
				<tr data-id="_sold_individually">
					<td>
						<input id="setsel_sold_individually" type="checkbox" class="selectset" data-id="_sold_individually"><label for="setsel_sold_individually"><?php echo $arrTranslated['_sold_individually']; ?></label>
					</td>
					<td>
						
					</td>
					<td>
						 <select id="select_sold_individually">
							<option value="yes">Yes</option>
							<option value="no">No</option>
						</select>
					</td>
					<td>
						
					</td>
					<td>
					</td>
				</tr>
				<tr data-id="product_shipping_class">
					<td>
						<?php echo $arrTranslated['product_shipping_class']; ?>
					</td>
					<td>
						 <select id="selectproduct_shipping_class" class="selectselect" data-id="product_shipping_class">
							<option value="con"><?php echo $t_contains; ?></option>
							<option value="notcon"><?php echo $t_doesnot; ?></option>
							<option value="start"><?php echo $t_starts; ?></option>
							<option value="end"><?php echo $t_ends; ?></option>
							<option value="empty"><?php echo $t_isempty; ?></option>
						</select>
					</td>
					<td>
						<input id="selectproduct_shipping_classvalue" type="text" placeholder="Skipped (empty)" data-id="product_shipping_class" class="selectvalue"/>
					</td>
					<td>
						<label><input data-id="product_shipping_class" class="selectifignorecase" type="checkbox"> <?php echo $ignorecase; ?></label>
					</td>
					<td>
						<input data-id="product_shipping_class" class="checkboxifspecial" type="checkbox">
						<select class="selectsplit" disabled="disabled"><option value="split">split commas</option><option value="regexp">reg exp</option></select>
						<select class="selectsplitand" disabled="disabled"><option value="and">AND</option><option value="or">OR</option></select>
					</td>
				</tr>
				<tr data-id="_purchase_note">
					<td>
						<?php echo $arrTranslated['_purchase_note']; ?>
					</td>
					<td>
						 <select id="select_purchase_note" class="selectselect" data-id="_purchase_note">
							<option value="con"><?php echo $t_contains; ?></option>
							<option value="notcon"><?php echo $t_doesnot; ?></option>
							<option value="start"><?php echo $t_starts; ?></option>
							<option value="end"><?php echo $t_ends; ?></option>
							<option value="empty"><?php echo $t_isempty; ?></option>
						</select>
					</td>
					<td>
						<textarea cols="15" rows="1" id="select_purchase_notevalue" placeholder="Skipped (empty)" data-id="_purchase_note" class="selectvalue"></textarea >
					</td>
					<td>
						<label><input data-id="_purchase_note" class="selectifignorecase" type="checkbox"> <?php echo $ignorecase; ?></label>
					</td>
					<td>
						<input data-id="_purchase_note" class="checkboxifspecial" type="checkbox">
						<select class="selectsplit" disabled="disabled"><option value="split">split commas</option><option value="regexp">reg exp</option></select>
						<select class="selectsplitand" disabled="disabled"><option value="and">AND</option><option value="or">OR</option></select>
					</td>
				</tr>
				<tr data-id="post_status">
					<td>
						<input id="setselpost_status" type="checkbox" class="selectset" data-id="post_status"><label for="setselpost_status"><?php echo $arrTranslated['post_status']; ?></label>
					</td>
					<td>
						
					</td>
					<td>
						 <select id="selectpost_status">
							<option value="publish">Publish</option>
							<option value="draft">Draft</option>
							<option value="private">Private</option>
						</select>
					</td>
					<td>
						
					</td>
					<td>
					</td>
				</tr>
				<tr data-id="_visibility">
					<td>
						<input id="setsel_visibility" type="checkbox" class="selectset" data-id="_visibility"><label for="setsel_visibility"><?php echo $arrTranslated['_visibility']; ?></label>
					</td>
					<td>
						
					</td>
					<td>
						 <select id="select_visibility">
							<option value="Catalog/search">Catalog/search</option>
							<option value="Catalog">Catalog</option>
							<option value="Search">Search</option>
							<option value="Hidden">Hidden</option>
						</select>
					</td>
					<td>
						
					</td>
					<td>
					</td>
				</tr>
				<tr data-id="_upsell_ids">
					<td>
						<?php echo $arrTranslated['_upsell_ids']; ?>
					</td>
					<td>
						 <select id="select_upsell_ids" class="selectselect" data-id="_upsell_ids">
							<option value="con"><?php echo $t_contains; ?></option>
							<option value="notcon"><?php echo $t_doesnot; ?></option>
							<option value="start"><?php echo $t_starts; ?></option>
							<option value="end"><?php echo $t_ends; ?></option>
							<option value="empty"><?php echo $t_isempty; ?></option>
						</select>
					</td>
					<td>
						<input id="select_upsell_idsvalue" type="text" placeholder="Skipped (empty)" data-id="_upsell_ids" class="selectvalue"/>
					</td>
					<td>
						<label><input data-id="_upsell_ids" class="selectifignorecase" type="checkbox"> <?php echo $ignorecase; ?></label>
					</td>
					<td>
						<input data-id="_upsell_ids" class="checkboxifspecial" type="checkbox">
						<select class="selectsplit" disabled="disabled"><option value="split">split commas</option><option value="regexp">reg exp</option></select>
						<select class="selectsplitand" disabled="disabled"><option value="and">AND</option><option value="or">OR</option></select>
					</td>
				</tr>
				<tr data-id="_crosssell_ids">
					<td>
						<?php echo $arrTranslated['_crosssell_ids']; ?>
					</td>
					<td>
						 <select id="select_crosssell_ids" class="selectselect" data-id="_crosssell_ids">
							<option value="con"><?php echo $t_contains; ?></option>
							<option value="notcon"><?php echo $t_doesnot; ?></option>
							<option value="start"><?php echo $t_starts; ?></option>
							<option value="end"><?php echo $t_ends; ?></option>
							<option value="empty"><?php echo $t_isempty; ?></option>
						</select>
					</td>
					<td>
						<input id="select_crosssell_idsvalue" type="text" placeholder="Skipped (empty)" data-id="_crosssell_ids" class="selectvalue"/>
					</td>
					<td>
						<label><input data-id="_crosssell_ids" class="selectifignorecase" type="checkbox"> <?php echo $ignorecase; ?></label>
					</td>
					<td>
						<input data-id="_crosssell_ids" class="checkboxifspecial" type="checkbox">
						<select class="selectsplit" disabled="disabled"><option value="split">split commas</option><option value="regexp">reg exp</option></select>
						<select class="selectsplitand" disabled="disabled"><option value="and">AND</option><option value="or">OR</option></select>
					</td>
				</tr>
				<tr data-id="_downloadable">
					<td>
						<input id="setsel_downloadable" type="checkbox" class="selectset" data-id="_downloadable"><label for="setsel_downloadable"><?php echo $arrTranslated['_downloadable']; ?></label>
					</td>
					<td>
						
					</td>
					<td>
						 <select id="select_downloadable">
							<option value="yes">Yes</option>
							<option value="no">No</option>
						</select>
					</td>
					<td>
						
					</td>
					<td>
					</td>
				</tr>
				<tr data-id="_virtual">
					<td>
						<input id="setsel_virtual" type="checkbox" class="selectset" data-id="_virtual"><label for="setsel_virtual"><?php echo $arrTranslated['_virtual']; ?></label>
					</td>
					<td>
						
					</td>
					<td>
						 <select id="select_virtual">
							<option value="yes">Yes</option>
							<option value="no">No</option>
						</select>
					</td>
					<td>
						
					</td>
					<td>
					</td>
				</tr>
				<tr data-id="_download_limit">
					<td>
						<?php echo $arrTranslated['_download_limit']; ?>
					</td>
					<td>
						 <select id="select_download_limit" class="selectselect" data-id="_download_limit">
							<option value="more">></option>
							<option value="less"><</option>
							<option value="equal">==</option>
							<option value="moree">>=</option>
							<option value="lesse"><=</option>
							<option value="empty"><?php echo $t_isempty; ?></option>
						</select>
					</td>
					<td>
						<input id="select_download_limitvalue" type="text" placeholder="Skipped (empty)" data-id="_download_limit" class="selectvalue" />
					</td>
					<td>
						
					</td>
					<td>
					</td>
				</tr>
				<tr data-id="_download_expiry">
					<td>
						<?php echo $arrTranslated['_download_expiry']; ?>
					</td>
					<td>
						 <select id="select_download_expiry" class="selectselect" data-id="_download_expiry">
						<option value="more">></option>
							<option value="less"><</option>
							<option value="equal">==</option>
							<option value="moree">>=</option>
							<option value="lesse"><=</option>
							<option value="empty"><?php echo $t_isempty; ?> (unlimited)</option>
						</select>
					</td>
					<td>
						<input id="select_download_expiryvalue" type="text" placeholder="Skipped (empty)" data-id="_download_expiry" class="selectvalue" />
					</td>
					<td>
						
					</td>
					<td>
					</td>
				</tr>
				<tr data-id="_download_type">
					<td>
						<input id="setsel_download_type" type="checkbox" class="selectset" data-id="_download_type"><label for="setsel_download_type"><?php echo $arrTranslated['_download_type']; ?></label>
					</td>
					<td>
						
					</td>
					<td>
						 <select id="select_download_type">
							<option value="Standard">Standard</option>
							<option value="Application">Application</option>
							<option value="Music">Music</option>
						</select>
					</td>
					<td>
						
					</td>
					<td>
					</td>
				</tr>
				<tr data-id="_featured">
					<td>
						<input id="setsel_featured" type="checkbox" class="selectset" data-id="_featured"><label for="setsel_featured"><?php echo $arrTranslated['_featured']; ?></label>
					</td>
					<td>
						
					</td>
					<td>
						 <select id="select_featured">
							<option value="yes">Yes</option>
							<option value="no">No</option>
						</select>
					</td>
					<td>
						
					</td>
					<td>
					</td>
				</tr>
				<tr data-id="_product_url">
					<td>
						<?php echo $arrTranslated['_product_url']; ?>
					</td>
					<td>
						 <select id="select_product_url" class="selectselect" data-id="_product_url">
							<option value="con"><?php echo $t_contains; ?></option>
							<option value="notcon"><?php echo $t_doesnot; ?></option>
							<option value="start"><?php echo $t_starts; ?></option>
							<option value="end"><?php echo $t_ends; ?></option>
							<option value="empty"><?php echo $t_isempty; ?></option>
						</select>
					</td>
					<td>
						<input id="select_product_urlvalue" type="text" placeholder="Skipped (empty)" data-id="_product_url" class="selectvalue"/>
					</td>
					<td>
						<label><input data-id="_product_url" class="selectifignorecase" type="checkbox"> <?php echo $ignorecase; ?></label>
					</td>
					<td>
						<input data-id="_product_url" class="checkboxifspecial" type="checkbox">
						<select class="selectsplit" disabled="disabled"><option value="split">split commas</option><option value="regexp">reg exp</option></select>
						<select class="selectsplitand" disabled="disabled"><option value="and">AND</option><option value="or">OR</option></select>
					</td>
				</tr>
				<tr data-id="_button_text">
					<td>
						<?php echo $arrTranslated['_button_text']; ?>
					</td>
					<td>
						 <select id="select_button_text" class="selectselect" data-id="_button_text">
							<option value="con"><?php echo $t_contains; ?></option>
							<option value="notcon"><?php echo $t_doesnot; ?></option>
							<option value="start"><?php echo $t_starts; ?></option>
							<option value="end"><?php echo $t_ends; ?></option>
							<option value="empty"><?php echo $t_isempty; ?></option>
						</select>
					</td>
					<td>
						<input id="select_button_textvalue" type="text" placeholder="Skipped (empty)" data-id="_button_text" class="selectvalue"/>
					</td>
					<td>
						<label><input data-id="_button_text" class="selectifignorecase" type="checkbox"> <?php echo $ignorecase; ?></label>
					</td>
					<td>
						<input data-id="_button_text" class="checkboxifspecial" type="checkbox">
						<select class="selectsplit" disabled="disabled"><option value="split">split commas</option><option value="regexp">reg exp</option></select>
						<select class="selectsplitand" disabled="disabled"><option value="and">AND</option><option value="or">OR</option></select>
					</td>
				</tr>
				<tr data-id="menu_order">
					<td>
						<?php echo $arrTranslated['menu_order']; ?>
					</td>
					<td>
						 <select id="selectmenu_order" class="selectselect" data-id="menu_order">
							<option value="more">></option>
							<option value="less"><</option>
							<option value="equal">==</option>
							<option value="moree">>=</option>
							<option value="lesse"><=</option>
							<option value="empty"><?php echo $t_isempty; ?></option>
						</select>
					</td>
					<td>
						<input id="selectmenu_ordervalue" type="text" placeholder="Skipped (empty)" data-id="menu_order" class="selectvalue" />
					</td>
					<td>
						
					</td>
					<td>
					</td>
				</tr>
				<tr data-id="_variation_description">
					<td>
						<?php echo $arrTranslated['_variation_description']; ?>
					</td>
					<td>
						 <select id="select_variation_description" class="selectselect" data-id="_variation_description">
							<option value="con"><?php echo $t_contains; ?></option>
							<option value="notcon"><?php echo $t_doesnot; ?></option>
							<option value="start"><?php echo $t_starts; ?></option>
							<option value="end"><?php echo $t_ends; ?></option>
							<option value="empty"><?php echo $t_isempty; ?></option>
						</select>
					</td>
					<td>
						<textarea cols="15" rows="1" id="select_variation_descriptionvalue" placeholder="Skipped (empty)" data-id="_variation_description" class="selectvalue"></textarea >
					</td>
					<td>
						<label><input data-id="_variation_description" class="selectifignorecase" type="checkbox"> <?php echo $ignorecase; ?></label>
					</td>
					<td>
						<input data-id="_variation_description" class="checkboxifspecial" type="checkbox">
						<select class="selectsplit" disabled="disabled"><option value="split">split commas</option><option value="regexp">reg exp</option></select>
						<select class="selectsplitand" disabled="disabled"><option value="and">AND</option><option value="or">OR</option></select>
					</td>
				</tr>
				<tr data-id="product_type">
					<td>
						<?php echo $arrTranslated['product_type']; ?>
					</td>
					<td>
						 <select id="selectproduct_type" class="selectselect" data-id="product_type">
							<option value="con"><?php echo $t_contains; ?></option>
							<option value="notcon"><?php echo $t_doesnot; ?></option>
							<option value="start"><?php echo $t_ends; ?></option>
							<option value="end"><?php echo $t_ends; ?></option>
							<option value="empty"><?php echo $t_isempty; ?></option>
						</select>
					</td>
					<td>
						<input id="selectproduct_typevalue" type="text" placeholder="Skipped (empty)" data-id="product_type" class="selectvalue"/>
					</td>
					<td>
						<label><input data-id="product_type" class="selectifignorecase" type="checkbox"> <?php echo $ignorecase; ?></label>
					</td>
					<td>
						<input data-id="product_type" class="checkboxifspecial" type="checkbox">
						<select class="selectsplit" disabled="disabled"><option value="split">split commas</option><option value="regexp">reg exp</option></select>
						<select class="selectsplitand" disabled="disabled"><option value="and">AND</option><option value="or">OR</option></select>
					</td>
				</tr>
				<tr data-id="comment_status">
					<td>
						<input id="setselcomment_status" type="checkbox" class="selectset" data-id="comment_status"><label for="setselcomment_status"><?php echo $arrTranslated['comment_status']; ?></label>
					</td>
					<td>
						
					</td>
					<td>
						 <select id="selectcomment_status">
							<option value="yes">Yes</option>
							<option value="no">No</option>
						</select>
					</td>
					<td>
						
					</td>
					<td>
					</td>
				</tr>
				<tr data-id="grouped_items">
					<td>
						<?php echo $arrTranslated['grouped_items']; ?>
					</td>
					<td>
						 <select id="selectgrouped_items" class="selectselect" data-id="grouped_items">
							<option value="con"><?php echo $t_contains; ?></option>
							<option value="notcon"><?php echo $t_doesnot; ?></option>
							<option value="start"><?php echo $t_ends; ?></option>
							<option value="end"><?php echo $t_ends; ?></option>
							<option value="empty"><?php echo $t_isempty; ?></option>
						</select>
					</td>
					<td>
						<input id="selectgrouped_itemsvalue" type="text" placeholder="Skipped (empty)" data-id="grouped_items" class="selectvalue"/>
					</td>
					<td>
						<label><input data-id="grouped_items" class="selectifignorecase" type="checkbox"> <?php echo $ignorecase; ?></label>
					</td>
					<td>
						<input data-id="grouped_items" class="checkboxifspecial" type="checkbox">
						<select class="selectsplit" disabled="disabled"><option value="split">split commas</option><option value="regexp">reg exp</option></select>
						<select class="selectsplitand" disabled="disabled"><option value="and">AND</option><option value="or">OR</option></select>
					</td>
				</tr>
				<tr data-id="_custom_attributes">
					<td>
						<?php echo $arrTranslated['_custom_attributes']; ?>
						<select id="select_custom_attributes_what" data-id="_custom_attributes">
							<option value="name">name</option>
							<option value="value">value</option>
						</select>
					</td>
					<td>
						 <select id="select_custom_attributes" class="selectselect" data-id="_custom_attributes">
							<option value="con"><?php echo $t_contains; ?></option>
							<option value="notcon"><?php echo $t_doesnot; ?></option>
							<option value="start"><?php echo $t_starts; ?></option>
							<option value="end"><?php echo $t_ends; ?></option>
							<option value="empty"><?php echo $t_isempty; ?></option>
						</select>
					</td>
					<td>
						<textarea cols="15" rows="1" id="select_custom_attributesvalue" placeholder="Skipped (empty)" data-id="_custom_attributes" class="selectvalue"></textarea >
					</td>
					<td>
						<label><input data-id="_custom_attributes" class="selectifignorecase" type="checkbox"> <?php echo $ignorecase; ?></label>
					</td>
					<td>
						<input data-id="_custom_attributes" class="checkboxifspecial" type="checkbox">
						<select class="selectsplit" disabled="disabled"><option value="split">split commas</option><option value="regexp">reg exp</option></select>
						<select class="selectsplitand" disabled="disabled"><option value="and">AND</option><option value="or">OR</option></select>
					</td>
				</tr>
				<tr data-id="post_author">
					<td>
						<?php echo $arrTranslated['post_author']; ?>
					</td>
					<td>
						 <select id="selectpost_author" class="selectselect" data-id="post_author">
							<option value="con"><?php echo $t_contains; ?></option>
							<option value="notcon"><?php echo $t_doesnot; ?></option>
							<option value="start"><?php echo $t_starts; ?></option>
							<option value="end"><?php echo $t_ends; ?></option>
							<option value="empty"><?php echo $t_isempty; ?></option>
						</select>
					</td>
					<td>
						<input id="selectpost_authorvalue" type="text" placeholder="Skipped (empty)" data-id="post_author" class="selectvalue"/>
					</td>
					<td>
						<label><input data-id="post_author" class="selectifignorecase" type="checkbox"> <?php echo $ignorecase; ?></label>
					</td>
					<td>
						<input data-id="post_author" class="checkboxifspecial" type="checkbox">
						<select class="selectsplit" disabled="disabled"><option value="split">split commas</option><option value="regexp">reg exp</option></select>
						<select class="selectsplitand" disabled="disabled"><option value="and">AND</option><option value="or">OR</option></select>
					</td>
				</tr>
				<!--<tr data-id="post_type">
					<td>
						<?php echo $arrTranslated['post_type']; ?>
					</td>
					<td>
						 <select id="selectpost_type" class="selectselect" data-id="post_type">
							<option value="con"><?php echo $t_contains; ?></option>
							<option value="notcon"><?php echo $t_doesnot; ?></option>
							<option value="start"><?php echo $t_starts; ?></option>
							<option value="end"><?php echo $t_ends; ?></option>
						</select>
					</td>
					<td>
						<input id="selectpost_typevalue" type="text" placeholder="Skipped (empty)" data-id="post_type" class="selectvalue"/>
					</td>
					<td>
						<label><input data-id="post_type" class="selectifignorecase" type="checkbox"> <?php echo $ignorecase; ?></label>
					</td>
					<td>
						<input data-id="post_type" class="checkboxifspecial" type="checkbox">
						<select class="selectsplit" disabled="disabled"><option value="split">split commas</option><option value="regexp">reg exp</option></select>
						<select class="selectsplitand" disabled="disabled"><option value="and">AND</option><option value="or">OR</option></select>
					</td>
				</tr>-->
			</table>
			<br/>
			</div>
			
		<!--	
		settings dialog
		-->
			<!--//show/hide fields-->
			<div id="settingsdialog">
			<table class="settings-table" >
				<br/>
			    <input id="searchsettings" type="text" style="width:150px;" placeholder="search"></input>
			    <!--<button id="searchsettingsreset" class="button"><?php _e( 'show all', 'woocommerce-advbulkedit'); ?></button>-->
			    <br/>
				<tr>
					
					<td>
						<input id="dimage" class="dsettings" data-id="_thumbnail_id" type="checkbox"><label for="dimage"> <?php echo $arrTranslated['_thumbnail_id']; ?></label>
					</td>
					<td>
						<div>
						 <img id="dimage_check" src="<?php echo $purl;?>images/tick.png" style="visibility:hidden;"/>
						</div>
					</td>
					<td>
						<input id="d_product_image_gallery" class="dsettings" data-id="_product_image_gallery" type="checkbox"><label for="d_product_image_gallery"> <?php echo $arrTranslated['_product_image_gallery']; ?></label>
					</td>
					<td>
						<div>
						 <img id="d_product_image_gallery_check" src="<?php echo $purl;?>images/tick.png" style="visibility:hidden;"/>
						</div>
					</td>
				</tr>
				<tr>
					
					<td>
						<input id="dmenu_order" class="dsettings" data-id="menu_order" type="checkbox"><label for="dmenu_order"> <?php echo $arrTranslated['menu_order']; ?></label>
					</td>
					<td>
						<div>
						 <img id="dmenu_order_check" src="<?php echo $purl;?>images/tick.png" style="visibility:hidden;"/>
						</div>
					</td>
					<td>
						<input id="dfeatured" class="dsettings" data-id="_featured" type="checkbox"><label for="dfeatured"> <?php echo $arrTranslated['_featured']; ?></label>
					</td>
					<td>
						<div>
						 <img id="dfeatured_check" src="<?php echo $purl;?>images/tick.png" style="visibility:hidden;"/>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<input id="dprodcutdescription" class="dsettings" data-id="post_content" type="checkbox"><label for="dprodcutdescription"> <?php echo $arrTranslated['post_content']; ?></label>
					</td>
					<td>
						<div>
						 <img id="dprodcutdescription_check" src="<?php echo $purl;?>images/tick.png" style="visibility:hidden;"/>
						</div>
					</td>
					<td>
						<input id="dprodcutexcerpt" class="dsettings" data-id="post_excerpt" type="checkbox"><label for="dprodcutexcerpt"> <?php echo $arrTranslated['post_excerpt']; ?></label>
					</td>
					<td>
						<div>
						 <img id="dprodcutexcerpt_check" src="<?php echo $purl;?>images/tick.png" style="visibility:hidden;"/>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<input id="dpost_name" class="dsettings" data-id="post_name" type="checkbox"><label for="dpost_name"> <?php echo $arrTranslated['post_name']; ?></label>
					</td>
					<td>
						<div>
						 <img id="dpost_name_check" src="<?php echo $purl;?>images/tick.png" style="visibility:hidden;"/>
						</div>
					</td>
					<td>
						<input id="dpost_date" class="dsettings" data-id="post_date" type="checkbox"><label for="dpost_date"> <?php echo $arrTranslated['post_date']; ?></label>
					</td>
					<td>
						<div>
						 <img id="dpost_date_check" src="<?php echo $purl;?>images/tick.png" style="visibility:hidden;"/>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<input id="dsku" class="dsettings" data-id="_sku" type="checkbox"><label for="dsku"> <?php echo $arrTranslated['_sku']; ?></label>
					</td>
					<td>
						<div>
						 <img id="dsku_check" src="<?php echo $purl;?>images/tick.png" style="visibility:hidden;"/>
						</div>
					</td>
					<td>
						<input id="dproduct_cat" class="dsettings" data-id="product_cat" type="checkbox"><label for="dproduct_cat"> <?php echo $arrTranslated['product_cat']; ?></label>
					</td>
					<td>
						<div>
						 <img id="dproduct_cat_check" src="<?php echo $purl;?>images/tick.png" style="visibility:hidden;"/>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<input id="dproduct_tag" class="dsettings" data-id="product_tag" type="checkbox"><label for="dproduct_tag"> <?php echo $arrTranslated['product_tag']; ?></label>
					</td>
					<td>
						<div>
						 <img id="dproduct_tag_check" src="<?php echo $purl;?>images/tick.png" style="visibility:hidden;"/>
						</div>
					</td>
					<td>
						<input id="dproduct_shipping_class" class="dsettings" data-id="product_shipping_class" type="checkbox"><label for="dproduct_shipping_class"> <?php echo $arrTranslated['product_shipping_class']; ?></label>
					</td>
					<td>
						<div>
						 <img id="dproduct_shipping_class_check" src="<?php echo $purl;?>images/tick.png" style="visibility:hidden;"/>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<input id="dregprice" class="dsettings" data-id="_regular_price" type="checkbox"><label for="dregprice"> <?php echo $arrTranslated['_regular_price']; ?></label>
					</td>
					<td>
						<div>
						 <img id="dregprice_check" src="<?php echo $purl;?>images/tick.png" style="visibility:hidden;"/>
						</div>
					</td>
					<td>
						<input id="dsaleprice" class="dsettings" data-id="_sale_price" type="checkbox"><label for="dsaleprice"> <?php echo $arrTranslated['_sale_price']; ?></label>
					</td>
					<td>
						<div>
						 <img id="dsaleprice_check" src="<?php echo $purl;?>images/tick.png" style="visibility:hidden;"/>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<input id="dsalefrom" class="dsettings" data-id="_sale_price_dates_from" type="checkbox"><label for="dsalefrom"> <?php echo $arrTranslated['_sale_price_dates_from']; ?></label>
					</td>
					<td>
						<div>
						 <img id="dsalefrom_check" src="<?php echo $purl;?>images/tick.png" style="visibility:hidden;"/>
						</div>
					</td>
					<td>
						<input id="dsaleto" class="dsettings" data-id="_sale_price_dates_to" type="checkbox"><label for="dsaleto"> <?php echo $arrTranslated['_sale_price_dates_to']; ?></label>
					</td>
					<td>
						<div>
						 <img id="dsaleto_check" src="<?php echo $purl;?>images/tick.png" style="visibility:hidden;"/>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<input id="dtaxstatus" class="dsettings" data-id="_tax_status" type="checkbox"><label for="dtaxstatus"> <?php echo $arrTranslated['_tax_status']; ?></label>
					</td>
					<td>
						<div>
						 <img id="dtaxstatus_check" src="<?php echo $purl;?>images/tick.png" style="visibility:hidden;"/>
						</div>
					</td>
					<td>
						<input id="dtaxclass" class="dsettings" data-id="_tax_class" type="checkbox"><label for="dtaxclass"> <?php echo $arrTranslated['_tax_class']; ?></label>
					</td>
					<td>
						<div>
						 <img id="dtaxclass_check" src="<?php echo $purl;?>images/tick.png" style="visibility:hidden;"/>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<input id="dweight" class="dsettings" data-id="_weight" type="checkbox"><label for="dweight"> <?php echo $arrTranslated['_weight']; ?></label>
					</td>
					<td>
						<div>
						 <img id="dweight_check" src="<?php echo $purl;?>images/tick.png" style="visibility:hidden;"/>
						</div>
					</td>
					<td>
						<input id="dheight" class="dsettings" data-id="_height" type="checkbox"><label for="dheight"> <?php echo $arrTranslated['_height']; ?></label>
					</td>
					<td>
						<div>
						 <img id="dheight_check" src="<?php echo $purl;?>images/tick.png" style="visibility:hidden;"/>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<input id="dwidth" class="dsettings" data-id="_width" type="checkbox"><label for="dwidth"> <?php echo $arrTranslated['_width']; ?></label>
					</td>
					<td>
						<div>
						 <img id="dwidth_check" src="<?php echo $purl;?>images/tick.png" style="visibility:hidden;"/>
						</div>
					</td>
					<td>
						<input id="dlength" class="dsettings" data-id="_length" type="checkbox"><label for="dlength"> <?php echo $arrTranslated['_length']; ?></label>
					</td>
					<td>
						<div>
						 <img id="dlength_check" src="<?php echo $purl;?>images/tick.png" style="visibility:hidden;"/>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<input id="dstockquantity" class="dsettings" data-id="_stock" type="checkbox"><label for="dstockquantity"> <?php echo $arrTranslated['_stock']; ?></label>
					</td>
					<td>
						<div>
						 <img id="dstockquantity_check" src="<?php echo $purl;?>images/tick.png" style="visibility:hidden;"/>
						</div>
					</td>
					<td>
						<input id="dstockstatus" class="dsettings" data-id="_stock_status" type="checkbox"><label for="dstockstatus"> <?php echo $arrTranslated['_stock_status']; ?></label>
					</td>
					<td>
						<div>
						 <img id="dstockstatus_check" src="<?php echo $purl;?>images/tick.png" style="visibility:hidden;"/>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<input id="dmanagestock" class="dsettings" data-id="_manage_stock" type="checkbox"><label for="dmanagestock"> <?php echo $arrTranslated['_manage_stock']; ?></label>
					</td>
					<td>
						<div>
						 <img id="dmanagestock_check" src="<?php echo $purl;?>images/tick.png" style="visibility:hidden;"/>
						</div>
					</td>
					<td>
						<input id="dbackorders" class="dsettings" data-id="_backorders" type="checkbox"><label for="dbackorders"> <?php echo $arrTranslated['_backorders']; ?></label>
					</td>
					<td>
						<div>
						 <img id="dbackorders_check" src="<?php echo $purl;?>images/tick.png" style="visibility:hidden;"/>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<input id="dsoldind" class="dsettings" data-id="_sold_individually" type="checkbox"><label for="dsoldind"> <?php echo $arrTranslated['_sold_individually']; ?></label>
					</td>
					<td>
						<div>
						 <img id="dsoldind_check" src="<?php echo $purl;?>images/tick.png" style="visibility:hidden;"/>
						</div>
					</td>
					<td>
						<input id="dpurchasenote" class="dsettings" data-id="_purchase_note" type="checkbox"><label for="dpurchasenote"> <?php echo $arrTranslated['_purchase_note']; ?></label>
					</td>
					<td>
						<div>
						 <img id="dpurchasenote_check" src="<?php echo $purl;?>images/tick.png" style="visibility:hidden;"/>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<input id="d_grouped_items" class="dsettings" data-id="grouped_items" type="checkbox"><label for="d_grouped_items"> <?php echo $arrTranslated['grouped_items']; ?></label>
					</td>
					<td>
						<div>
						 <img id="d_grouped_items_check" src="<?php echo $purl;?>images/tick.png" style="visibility:hidden;"/>
						</div>
					</td>
					<td>
						<input id="d_product_adminlink" class="dsettings" data-id="_product_adminlink" type="checkbox"><label for="d_product_adminlink"> Edit in admin</label>
					</td>
					<td>
						<div>
						 <img id="d_product_adminlink_check" src="<?php echo $purl;?>images/tick.png" style="visibility:hidden;"/>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<input id="dproductstatus" class="dsettings" data-id="post_status" type="checkbox"><label for="dproductstatus"> <?php echo $arrTranslated['post_status']; ?></label>
					</td>
					<td>
						<div>
						 <img id="dproductstatus_check" src="<?php echo $purl;?>images/tick.png" style="visibility:hidden;"/>
						</div>
					</td>
					<td>
						<input id="dcatalog" class="dsettings" data-id="_visibility" type="checkbox"><label for="dcatalog"> <?php echo $arrTranslated['_visibility']; ?></label>
					</td>
					<td>
						<div>
						 <img id="dcatalog_check" src="<?php echo $purl;?>images/tick.png" style="visibility:hidden;"/>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<input id="d_upsell_ids" class="dsettings" data-id="_upsell_ids" type="checkbox"><label for="d_upsell_ids"> <?php echo $arrTranslated['_upsell_ids']; ?></label>
					</td>
					<td>
						<div>
						 <img id="d_upsell_ids_check" src="<?php echo $purl;?>images/tick.png" style="visibility:hidden;"/>
						</div>
					</td>
					<td>
						<input id="d_crosssell_ids" class="dsettings" data-id="_crosssell_ids" type="checkbox"><label for="d_crosssell_ids"> <?php echo $arrTranslated['_crosssell_ids']; ?></label>
					</td>
					<td>
						<div>
						 <img id="d_crosssell_ids_check" src="<?php echo $purl;?>images/tick.png" style="visibility:hidden;"/>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<input id="ddownloadable" class="dsettings" data-id="_downloadable" type="checkbox"><label for="ddownloadable"> <?php echo $arrTranslated['_downloadable']; ?></label>
					</td>
					<td>
						<div>
						 <img id="ddownloadable_check" src="<?php echo $purl;?>images/tick.png" style="visibility:hidden;"/>
						</div>
					</td>
					<td>
						<input id="dvirtual" class="dsettings" data-id="_virtual" type="checkbox"><label for="dvirtual"> <?php echo $arrTranslated['_virtual']; ?></label>
					</td>
					<td>
						<div>
						 <img id="dvirtual_check" src="<?php echo $purl;?>images/tick.png" style="visibility:hidden;"/>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<input id="ddownexpiry" class="dsettings" data-id="_download_expiry" type="checkbox"><label for="ddownexpiry"> <?php echo $arrTranslated['_download_expiry']; ?></label>
					</td>
					<td>
						<div>
						 <img id="ddownexpiry_check" src="<?php echo $purl;?>images/tick.png" style="visibility:hidden;"/>
						</div>
					</td>
					<td>
						<input id="ddownlimit" class="dsettings" data-id="_download_limit" type="checkbox"><label for="ddownlimit">  <?php echo $arrTranslated['_download_limit']; ?></label>
					</td>
					<td>
						<div>
						 <img id="ddownlimit_check" src="<?php echo $purl;?>images/tick.png" style="visibility:hidden;"/>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<input id="ddownfiles" class="dsettings" data-id="_downloadable_files" type="checkbox"><label for="ddownfiles"> <?php echo $arrTranslated['_downloadable_files']; ?></label>
					</td>
					<td>
						<div>
						 <img id="ddownfiles_check" src="<?php echo $purl;?>images/tick.png" style="visibility:hidden;"/>
						</div>
					</td>
					<td>
						<input id="ddowntype" class="dsettings" data-id="_download_type" type="checkbox"><label for="ddowntype"> <?php echo $arrTranslated['_download_type']; ?></label>
					</td>
					<td>
						<div>
						 <img id="ddowntype_check" src="<?php echo $purl;?>images/tick.png" style="visibility:hidden;"/>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<input id="d_product_url" class="dsettings" data-id="_product_url" type="checkbox"><label for="d_product_url"> <?php echo $arrTranslated['_product_url']; ?></label>
					</td>
					<td>
						<div>
						 <img id="d_product_url_check" src="<?php echo $purl;?>images/tick.png" style="visibility:hidden;"/>
						</div>
					</td>
					<td>
						<input id="d_button_text" class="dsettings" data-id="_button_text" type="checkbox"><label for="d_button_text"> <?php echo $arrTranslated['_button_text']; ?></label>
					</td>
					<td>
						<div>
						 <img id="d_button_text_check" src="<?php echo $purl;?>images/tick.png" style="visibility:hidden;"/>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<input id="dproduct_type" class="dsettings" data-id="product_type" type="checkbox"><label for="dproduct_type"> <?php echo $arrTranslated['product_type']; ?></label>
					</td>
					<td>
						<div>
						 <img id="dproduct_type_check" src="<?php echo $purl;?>images/tick.png" style="visibility:hidden;"/>
						</div>
					</td>
					<td>
						<input id="dcomment_status" class="dsettings" data-id="comment_status" type="checkbox"><label for="dcomment_status"> <?php echo $arrTranslated['comment_status']; ?></label>
					</td>
					<td>
						<div>
						 <img id="dcomment_status_check" src="<?php echo $purl;?>images/tick.png" style="visibility:hidden;"/>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<input id="d_product_permalink" class="dsettings" data-id="_product_permalink" type="checkbox"><label for="d_product_permalink"> Product URL (permalink)</label>
					</td>
					<td>
						<div>
						 <img id="d_product_permalink_check" src="<?php echo $purl;?>images/tick.png" style="visibility:hidden;"/>
						</div>
					</td>
					<td>
						<input id="d_default_attributes" class="dsettings" data-id="_default_attributes" type="checkbox"><label for="d_default_attributes"> <?php echo $arrTranslated['_default_attributes']; ?></label>
					</td>
					<td>
						<div>
						 <img id="d_default_attributes_check" src="<?php echo $purl;?>images/tick.png" style="visibility:hidden;"/>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<input id="d_custom_attributes" class="dsettings" data-id="_custom_attributes" type="checkbox"><label for="d_custom_attributes"> <?php echo $arrTranslated['_custom_attributes']; ?></label>
					</td>
					<td>
						<div>
						 <img id="d_custom_attributes_check" src="<?php echo $purl;?>images/tick.png" style="visibility:hidden;"/>
						</div>
					</td>
					<td>
						<input id="d_variation_description" class="dsettings" data-id="_variation_description" type="checkbox"><label for="d_variation_description"> <?php echo $arrTranslated['_variation_description']; ?></label>
					</td>
					<td>
						<div>
						 <img id="d_variation_description_check" src="<?php echo $purl;?>images/tick.png" style="visibility:hidden;"/>
						</div>
					</td>
					<!--<td>-->
						<!--<input id="d_default_attributes" class="dsettings" data-id="_default_attributes" type="checkbox"><label for="d_default_attributes"> <?php _e( 'Default', 'woocommerce'); ?> <?php _e( 'Attributes', 'woocommerce'); ?></label>-->
					<!--</td>
					<td>-->
						<!--<div>
						 <img id="d_default_attributes_check" src="<?php echo $purl;?>images/tick.png" style="visibility:hidden;"/>
						</div>-->
					<!--</td>-->
				</tr>
				<tr>
					<td>
						<input id="dpost_author" class="dsettings" data-id="post_author" type="checkbox"><label for="dpost_author"> <?php echo $arrTranslated['post_author']; ?></label>
					</td>
					<td>
						<div>
						 <img id="dpost_author_check" src="<?php echo $purl;?>images/tick.png" style="visibility:hidden;"/>
						</div>
					</td>
					<td>
						&nbsp;
					</td>
					<td>
						&nbsp;
					</td>
				</tr>
				<!--<tr>
					<td>
						<input id="dpost_type" class="dsettings" data-id="post_type" type="checkbox"><label for="dpost_type"> <?php echo $arrTranslated['post_type']; ?></label>
					</td>
					<td>
						<div>
						 <img id="dpost_type_check" src="<?php echo $purl;?>images/tick.png" style="visibility:hidden;"/>
						</div>
					</td>
					<td>
						&nbsp;
					</td>
					<td>
						&nbsp;
					</td>
				</tr>-->
				<?php
					$counter = 0;
					foreach($this->attributes as $attr)
					{
						if($counter % 2 == 0)
						{
							echo '<tr><td>';
						}else
						{
							echo '<td>';
						}
						$attr_slug = "attribute_pa_".$attr->name;
						echo '<input id="d'.$attr_slug.'" class="dsettings" data-id="'.$attr_slug.'" type="checkbox"><label for="d'.$attr_slug.'"> (attr) '.$attr->label.'</label>
					</td>
					<td>
						<div>
						 <img id="d'.$attr_slug.'_check" src="'.$purl.'images/tick.png" style="visibility:hidden;"/>
						</div>';
//						echo 'W3Ex.attr_cols['.$attr->id.'] = {id:'.$attr->id.',attr:"'.$attr->label.'",value:"'.$.'"};';
						if($counter % 2 == 0)
						{
							$endrow = false;
							echo '</td>';
						}else
						{
							$endrow = true;
							echo '</td></tr>';
						}
						$counter++;
					}
				?>
			</table>
			<br/>
			</div>
			<!--//table views-->
			<div id="dialogtableviews">
				<table cellpadding="0" cellspacing="0" id="tableviews">
					<tr>
						<td style="width:35%;">
						<label><input type="radio" name="viewdialog" value="savenew">
							 <?php _e( 'Save to new view', 'woocommerce-advbulkedit'); ?></label>
						</td>
						<td><input type="text" id="viewinputnew"></input></td>
					</tr>
					<tr>
						<td style="width:35%;">
						<label><input type="radio" name="viewdialog" value="load" checked="checked">
							 <?php _e( 'Load existing view', 'woocommerce-advbulkedit'); ?></label>
						</td>
						<td><select id="viewselectload"><option value="">none</option></select> </td>
					</tr>
					<tr>
						<td style="width:35%;">
						<label><input type="radio" name="viewdialog" value="save">
							<?php _e( 'Save to exisiting view /replace/', 'woocommerce-advbulkedit'); ?></label>
						</td>
						<td><select id="viewselectreplace"><option value="">none</option></select></td>
					</tr>
					
					<tr>
						<td style="width:35%;border-bottom: none;">
						<label><input type="radio" name="viewdialog" value="edit">
							<?php _e( 'Edit View List', 'woocommerce-advbulkedit'); ?></label>
						</td>
						<td style="border-bottom: none;">
							<button id="buttonviewrename"><?php _e( 'Rename', 'woocommerce-advbulkedit'); ?></button><select id="viewselectedit"><option value="">none</option></select> to <input type="text" id="viewinputnewname"></input>
							<br/>
							<button id="buttonviewdelete"><?php
echo $this->mb_ucfirst(__( "delete", "woocommerce-advbulkedit"));
?></button>
						</td>
					</tr>
				</table>
			<!--<button id="okforviewdialog">Ok</button>-->
			</div>
			<!--//built-in taxonomies select-->
			<div id="categoriesdialog">
				<div class="grouped_items">
					<ul class="categorychecklist form-no-clear clearothers">
						<li><label class="selectit"><input value="0" type="checkbox"  /> Choose a grouped product...</label></li>
					<?php
						$argsgr = array(
							'posts_per_page'   => 1000,
							'post_type' => 'product',
							'product_type' => 'grouped'
						);
						$query = new WP_Query( $argsgr );

						// The Loop
						while ( $query->have_posts() ) {
							$query->the_post();
							echo '<li><label class="selectit"><input value="'.$query->post->ID.'" type="checkbox" data-name="'.addslashes(get_the_title()).'" />'.get_the_title().'</label></li>';
						}
						wp_reset_postdata();
					?>
					</ul>
				</div>
				<div class='product_cat'>
					<?php
							$args = array(
							'descendants_and_self'  => 0,
							'selected_cats'         => false,
							'popular_cats'          => false,
							'walker'                => null,
							'taxonomy'              => 'product_cat',
							'checked_ontop'         => true
						);

						?>
					<ul class="categorychecklist form-no-clear">
							<?php wp_terms_checklist( 0, $args ); ?>
					</ul>
				</div>
				<div class='product_tag'>
					<?php
							$args = array(
							'descendants_and_self'  => 0,
							'selected_cats'         => false,
							'popular_cats'          => false,
							'walker'                => null,
							'taxonomy'              => 'product_tag',
							'checked_ontop'         => true
						);

						?>
					<ul class="categorychecklist form-no-clear">
							<!--uncomment php below for hiearchical tags-->
							<?php 
//							$tagcount = wp_count_terms( 'product_tag', $args );
//							if(!is_wp_error($tagcount) && $tagcount < 2000)
//							{
								wp_terms_checklist( 0, $args ); 
//							}
							?>
					</ul>
				</div>
				<div class='product_shipping_class'>
					<?php
							$args = array(
							'descendants_and_self'  => 0,
							'selected_cats'         => false,
							'popular_cats'          => false,
							'walker'                => null,
							'taxonomy'              => 'product_shipping_class',
							'checked_ontop'         => true
						);

					?>
					<ul class="categorychecklist form-no-clear clearothers">
					<li id="product_shipping_class-none">
    					<label class="selectit">
					        <input id="in-product_shipping_class-none" type="checkbox" name="tax_input[product_shipping_class][]" value=""></input>
					         None
					    </label>
					</li>
							<?php wp_terms_checklist( 0, $args ); ?>
					</ul>
				</div>
				<div class='product_type'>
					<?php
							$args = array(
							'descendants_and_self'  => 0,
							'selected_cats'         => false,
							'popular_cats'          => false,
							'walker'                => null,
							'taxonomy'              => 'product_type',
							'checked_ontop'         => true
						);

					?>
					<ul class="categorychecklist form-no-clear clearothers">
							<?php wp_terms_checklist( 0, $args ); ?>
					</ul>
				</div>
				<div class='post_author'>
					<ul class="categorychecklist form-no-clear clearothers">
							<?php 
							foreach ( $blogusers as $user ) 
							{
								echo '<li>
				    					<label class="selectit">
									        <input value="'.$user->ID.'" type="checkbox">
									        '.$user->display_name.'
									    </label>
									</li>'
								;
							}
							
							 ?>
					</ul>
				</div>
				
				<?php
					if(is_array($this->attributes) && !empty($this->attributes))
					{
						$allattrs = '<div id="allattributeslist"><ul>';
						foreach($this->attributes as $attr)
						{
							if(count($attr->values) > 2000)
								continue;
							echo '<div class="attribute_pa_'.$attr->name.'">';
							$allattrs.= '<li><label><input type="checkbox" data-label="'.$attr->label.'" value="attribute_pa_'.$attr->name.'">'.$attr->label.'</label></li>';
							$args = array(
								'descendants_and_self'  => 0,
								'selected_cats'         => false,
								'popular_cats'          => false,
								'walker'                => null,
								'taxonomy'              => 'pa_'.$attr->name,
								'checked_ontop'         => true
							);
							echo '<ul class="categorychecklist form-no-clear">';
							try{
								wp_terms_checklist( 0, $args );
							}catch (Exception $e)
							{
							    ;
							}
								
							echo '</ul>';
							echo '</div>';
					    }
						$allattrs.= '</ul></div>';
						echo $allattrs;
					}
				?>
				<?php
					if(is_array($sel_fields) && !empty($sel_fields))
					{
						foreach($sel_fields as $key => $innerarray)
						{
							if(isset($innerarray['type']))
							{
								if($innerarray['type'] === 'customh')
								{
									if(taxonomy_exists($key))
									{
										echo '<div class="'.$key.'">';
										echo PHP_EOL;
										
										if($key === 'product_sale_labels' || $key === 'product_delivery_times')
										{
											echo '<ul class="categorychecklist form-no-clear clearothers">';
										}else
										{
											echo '<ul class="categorychecklist form-no-clear">';
										}
										$args = array(
											'descendants_and_self'  => 0,
											'selected_cats'         => false,
											'popular_cats'          => false,
											'walker'                => null,
											'taxonomy'              => $key,
											'checked_ontop'         => true
										);
										wp_terms_checklist( 0, $args );
										echo '</ul></div>';
									}
								}
							}
						}
					}
				?>
			</div>
			<?php
				if(is_array($this->attributes) && !empty($this->attributes) && $skiploadfrontpage)
				{
					echo '<script>';
					foreach($this->attributes as $attr)
					{
						$attr_label = substr($attr->label,0,100);
						$attr_label = preg_replace('/\s+/', ' ', trim($attr_label));
						$key = "attribute_pa_".$attr->name;
						$bulktext = '<tr data-id="'.$key.'"><td>'
						.'<input id="set'.$key.'" type="checkbox" class="bulkset" data-id="'.$key.'" data-type="customtaxh"><label for="set'.$key.'">Set (attr) '.$attr_label.'</label></td><td>'.
						'<select id="bulkadd'.$key.'" class="bulkselect">'.
							'<option value="new">'.__('set new','woocommerce-advbulkedit').'</option>'.
							'<option value="add">'.__('add','woocommerce-advbulkedit').'</option>'.
							'<option value="remove">'.__('remove','woocommerce-advbulkedit').'</option></select><button class="butnewattribute button" type="button"><span class="icon-plus-outline"></span>new</button><div class="divnewattribute">' 
		   .'<input class="inputnewattributename" type="text" placeholder="name" data-slug="'.$key.'"></input><br/>'
		   .'<input class="inputnewattributeslug" type="text" placeholder="slug (optional)"></input><br/>'
		   .'<button class="butnewattributesave butbulkdialog" style="position:relative;">Ok</button><button class="butnewattributecancel">Cancel</button></div>'
		   .'<div class="divnewattributeerror"></div></td><td class="nontextnumbertd">'
						 .'<select id="bulk'.$key.'" class="makechosen catselset" style="width:250px;" data-placeholder="'.str_replace('\\','\\\\',$arrTranslated['trans_data_placeholder']).'" multiple ><option value=""></option>';
						  
						foreach($attr->values as $value)
						{
							$val_name = substr($value->name,0,100);
							$val_name = preg_replace('/\s+/', ' ', trim($val_name));
							$bulktext.= '<option value="'.$value->term_id.'">'.$val_name.'</option>';
						}
						$bulktext.= '</select></td><td>(<select class="selectvisiblefp" disabled data-id="'.$key.'">'.
						'<option value="skip">skip</option><option value="andset">and set</option><option value="onlyset">only set</option>'.
						'</select>&nbsp;<input type="checkbox" disabled class="visiblefp" data-id="'.$key.'">Visible on p. p.)&nbsp;&nbsp;'.
						'(<select disabled class="selectusedforvars" data-id="'.$key.'"><option value="skip">skip</option><option value="andset">and set</option><option value="onlyset">only set</option>'.
						'</select>&nbsp;<input type="checkbox" disabled class="usedforvars" data-id="'.$key.'">Used for var.)</td></tr>';
						echo "W3Ex['".str_replace("'","\'",$key)."bulk'] = '".str_replace("'","\'",$bulktext)."';";
						echo PHP_EOL;
					}
					echo '</script>';
				}
				if(is_array($sel_fields) && !empty($sel_fields))
				{
					echo PHP_EOL;
					echo '<script>';
					foreach($sel_fields as $key => $innerarray)
					{
						if(isset($innerarray['type']))
						{
							if($innerarray['type'] === 'customh' || $innerarray['type'] === 'custom')
							{
								if(taxonomy_exists($key))
								{
									
									$bulktext = '<tr data-id="'.$key.'"><td>'
									.'<input id="set'.$key.'" type="checkbox" class="bulkset" data-id="'.$key.'" data-type="customtaxh"><label for="set'.$key.'">Set '.$key.'</label></td><td>'.
						'<select id="bulkadd'.$key.'" class="bulkselect">'.
							'<option value="new">'.__('set new','woocommerce-advbulkedit').'</option>'.
							'<option value="add">'.__('add','woocommerce-advbulkedit').'</option>'.
							'<option value="remove">'.__('remove','woocommerce-advbulkedit').'</option></select>'.
							'<button class="butnewattribute button newcat" type="button"><span class="icon-plus-outline"></span>new</button>'.
						'<div class="divnewattribute">' .
		   '<input class="inputnewattributename" type="text" placeholder="name" data-slug="'.$key.'"></input><br/> '.
		  '<input class="inputnewattributeslug" type="text" placeholder="slug (optional)"></input><br/>'.
		   '<select class="selectnewcategory" data-placeholder="select parent(optional)" multiple></select><br/>'.
		   '<button class="butnewattributesave butbulkdialog newcat" style="position:relative;">Ok</button><button class="butnewattributecancel newcat">Cancel</button></div>'. 
		   '<div class="divnewattributeerror"></div>'.
							'</td><td class="nontextnumbertd">'
									 .'<select id="bulk'.$key.'" class="makechosen catselset" style="width:250px;" data-placeholder="'.str_replace('\\','\\\\',$arrTranslated['trans_data_placeholder']).'" multiple ><option value=""></option>';
									 $searchtext = ' class="makechosen catselset" style="width:250px;" data-placeholder="'.str_replace('\\','\\\\',$arrTranslated['trans_data_placeholder']).'" multiple ><option value=""></option>';
									   $argsb = array(
									    'number'     => 99999,
									    'orderby'    => 'slug',
									    'order'      => 'ASC',
									    'hide_empty' => false,
									    'include'    => '',
										'fields'     => 'all'
									);

									$woo_categoriesb = get_terms($key, $argsb );
									if(is_wp_error($woo_categoriesb))
											continue;
									foreach($woo_categoriesb as $category)
									{
									    if(!is_object($category)) continue;
									    if(!property_exists($category,'name')) continue;
									    if(!property_exists($category,'term_id')) continue;
										if(!property_exists($category,'term_taxonomy_id')) continue;
										$catname = str_replace('"','\"',$category->name);
										$catname = trim(preg_replace('/\s+/', ' ', $catname));
									   	$bulktext.= '<option value="'.$category->term_id.'" >'.$catname.'</option>';
										$searchtext.= '<option value="'.$category->term_taxonomy_id.'" >'.$catname.'</option>';
									}
									$bulktext.= '</select></td><td></td></tr>';
									$searchtext.= '</select>';
									if($innerarray['type'] === 'customh')
									{
										echo "W3Ex['".str_replace("'","\'",$key)."bulk'] = '".str_replace("'","\'",$bulktext)."';";
									}
									echo "W3Ex['taxonomyterms".str_replace("'","\'",$key)."'] = '".str_replace("'","\'",$searchtext)."';";
									echo PHP_EOL;
								}
							}
						}
					}
					
					echo '</script>';
				}
			?>
			<?php
			//add shipping class
				echo PHP_EOL;
				echo '<script>';
				$key = 'product_shipping_class';
				$searchtext = ' class="makechosen catselset" style="width:250px;" data-placeholder="select" multiple ><option value="none">none</option>';
				   $argsb = array(
				    'number'     => 99999,
				    'orderby'    => 'slug',
				    'order'      => 'ASC',
				    'hide_empty' => false,
				    'include'    => '',
					'fields'     => 'all'
				);

				$woo_categoriesb = get_terms($key, $argsb );

				foreach($woo_categoriesb as $category)
				{
				    if(!is_object($category)) continue;
				    if(!property_exists($category,'name')) continue;
				    if(!property_exists($category,'term_taxonomy_id')) continue;
					$catname = str_replace('"','\"',$category->name);
					$catname = trim(preg_replace('/\s+/', ' ', $catname));
					$searchtext.= '<option value="'.$category->term_taxonomy_id.'" >'.$catname.'</option>';
				}
				$searchtext.= '</select>';
				
				echo "W3Ex['taxonomyterms".str_replace("'","\'",$key)."'] = '".str_replace("'","\'",$searchtext)."';";
				
				$key = 'post_author';
				$searchtext = ' class="makechosen catselset" style="width:250px;" data-placeholder="select" multiple >';
				 

				foreach ( $blogusers as $user ) 
				{
					$catname = str_replace('"','\"',$user->display_name);
					$catname = trim(preg_replace('/\s+/', ' ', $catname));
					$searchtext.= '<option value="'.$user->ID.'" >'.$catname.'</option>';
				}

				$searchtext.= '</select>';
				
				echo "W3Ex['taxonomyterms".str_replace("'","\'",$key)."'] = '".str_replace("'","\'",$searchtext)."';";
				
				$key = 'product_type';
				$searchtext = ' class="makechosen catselset" style="width:250px;" data-placeholder="select" multiple >';
				   $argsb = array(
				    'number'     => 99999,
				    'orderby'    => 'slug',
				    'order'      => 'ASC',
				    'hide_empty' => false,
				    'include'    => '',
					'fields'     => 'all'
				);

				$woo_categoriesb = get_terms($key, $argsb );

				foreach($woo_categoriesb as $category)
				{
				    if(!is_object($category)) continue;
				    if(!property_exists($category,'name')) continue;
				    if(!property_exists($category,'term_taxonomy_id')) continue;
					$catname = str_replace('"','\"',$category->name);
					$catname = trim(preg_replace('/\s+/', ' ', $catname));
					$searchtext.= '<option value="'.$category->term_taxonomy_id.'" >'.$catname.'</option>';
				}
				$searchtext.= '</select>';
				
				echo "W3Ex['taxonomyterms".str_replace("'","\'",$key)."'] = '".str_replace("'","\'",$searchtext)."';";
				echo '</script>';
				echo PHP_EOL;
			?>
			<!--//custom fields dialog-->
			<div id="customfieldsdialog">
			<table cellpadding="10" cellspacing="0" id="customfieldstable">
				<tr class="addcontrols">
					<td>
						Meta key/tax. slug:<br />
						<input id="fieldname" type="text"/>
					</td>
					<td>
						Field name(display as):<br />
						<input id="fieldname1" type="text"/>
					</td>
					<td>
						Field type:<br />
						<select id="fieldtype">
							<option value="text">Text (single line)</option>
							<option value="multitext">Text (multi line)</option>
							<option value="integer">Number (integer)</option>
							<option value="decimal">Number (decimal .00)</option>
							<option value="decimal3">Number (decimal .000)</option>
							<option value="select">Dropdown Select</option>
							<option value="checkbox">Checkbox</option>
							<option value="custom">Custom taxonomy</option>
							<option value="customh">Custom taxonomy (hierarchical)</option>
						</select>
					</td>
					<td>
						Visible:<br />
						<select id="fieldvisible">
							<option value="yes">Yes</option>
							<option value="no">No</option>
						</select>
					</td>
				</tr>
				<tr class="addokcancel">
					<td>
						 <button id="addok" class="button">Ok</button>&nbsp;&nbsp;&nbsp;&nbsp;
						 <button id="addcancel" class="button">Cancel</button>
					</td>
					<td><div id="extracustominfo"></div>
					</td>
					<td>
					</td>
				</tr>
			</table><br />
			 <button id="addcustomfield" class="button"><?php _e( 'Add Custom Field', 'woocommerce-advbulkedit'); ?></button>
		</div>
		<div id="findcustomfieldsdialog">
			 <br /><br />
			 <button id="findcustomfieldsauto" class="button"><?php _e('Find Custom Fields','woocommerce-advbulkedit'); ?></button>&nbsp;(recommended)&nbsp;&nbsp;&nbsp;&nbsp; <button id="findcustomtaxonomies" class="button"><?php _e('Find Taxonomies','woocommerce-advbulkedit'); ?></button>&nbsp;&nbsp;&nbsp;&nbsp;<?php _e('Find custom fields by product ID','woocommerce-advbulkedit'); ?>:<input id="productid" type="text"/><button id="findcustomfield" class="button"><?php _e('Find','woocommerce-advbulkedit'); ?></button> 
			 <br /><br /><br />
			 <table cellpadding="25" cellspacing="0" class="tablecustomfields">
			</table>
		</div>
		<div id="debuginfo"></div>
			<iframe id="exportiframe" width="0" height="0">
  			</iframe>
		
		
		<div id="memorylimit">
		<?php
		if(isset($settings['debugmode']))
		{
			if($settings['debugmode'] == 1)
			{
//				$totalmem = (int) ini_get('memory_limit') ;
//				echo "Allocated: ".$totalmem."<br/>";
			}
		}?>
		</div>
		<div id="memoryusage">
		<?php
		if(isset($settings['debugmode']))
		{
			if($settings['debugmode'] == 1)
			{
				if(function_exists('memory_get_usage'))
				{
					$usage = memory_get_usage();
					echo 'Memory usage: '.round($usage /(1024 * 1024),2);
				}
			}
		}?>
		</div>
		<div id="editorcontainer">
			 <?php
				 $settingsed = array( 'textarea_name' => 'post_text' );//,'wpautop' => false,'tinymce' => array('forced_root_block' => true,'convert_newlines_to_brs' => false));
				 wp_editor("", "editorid",$settingsed );
			 ?>
			<textarea style="display:none;" name="post_text" id="editorid" rows="3"></textarea>
			<DIV style='text-align:right' id="savewordpeditor"><BUTTON>Save</BUTTON><BUTTON id="cancelwordpeditor">Cancel</BUTTON></DIV>
			</div>
		</div>
		<?php
		
	}
	
	
    public function _main()
    {
		$this->showMainPage();
    }
}

W3ExAdvBulkEditView::init();
