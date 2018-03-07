<?php

defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

class W3ExABulkEditAjaxHandler{
	
	private static $bwoosave = true;
	private static $bhandlewoocog = false;
	private static $bsavepost = false;
	private static $debugmode = false;
	private static $mapcustom = array();
	private static $last = null;
	private static $columns = array();
	private static $isversion3 = false;
	
							
//	private static $childrencache = null;
    public static function CallMetaUpdated($ID,$metakey,$metavalue)
    {
    	global $wpdb;
		$mid = $wpdb->get_var( $wpdb->prepare("SELECT meta_id FROM $wpdb->postmeta WHERE post_id = %d AND meta_key = %s", $ID, $metakey) );
		if( !is_wp_error($mid) && $mid != '' )
		{
			do_action( 'updated_postmeta', (int)$mid, $ID, $metakey,$metavalue );
		}
	}
	
	public static function lang_object_id($id,$taxname = 'product')
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
	
	public static function WriteDebugInfo($info,$curr_settings,$arr = null)
	{

	    if(!self::$debugmode)
	    	return;
		if($info === "clear")
		{
			update_option('w3exabe_debuginfo',array());
			return;
		}
		
	    $now = microtime(true);
		$elapsed = $now;
	    if (self::$last != null) 
	    {
	       $elapsed = round(($now - self::$last),5);
	    }

	    self::$last = $now;

		if($arr === null)
		{
//			$curr_settings['debuginfo'] = $info;
//			update_option('w3exabe_settings',$curr_settings);
			$info.= " (".$elapsed. " sec.)";
			$retarr = get_option('w3exabe_debuginfo');
			if(!is_array($retarr))
				$retarr = array($info);
			else
				$retarr[0] = $info;
//			$result = array_merge($retarr, $arr);
			update_option('w3exabe_debuginfo',$retarr);
//			update_option('w3exabe_debuginfo',array($info));
		}
		if($arr !== null)
		{
			$retarr = get_option('w3exabe_debuginfo');
			
			if(!is_array($retarr))
				$retarr = array();
			if(!is_array($arr))
			{
				if($arr === "") return;
				$arr.= " (".$elapsed. " sec.)";
				$retarr[] = $arr;
			}
			else
			{
				if(empty($arr)) return;
				if(count($arr) === 1)
				{
					if($arr[0] === "")
						return;
				}
				$retarr = array_merge($retarr, $arr);
			}
			update_option('w3exabe_debuginfo',$retarr);
		}
	}
	
	public static function CallWooAction($productid,$oldpost = null,$proddata = null)
	{
		do_action('wpla_product_has_changed', $productid );
		if(self::$bwoosave)
		{
			$product = null;
			if(function_exists('wc_get_product'))
			{
				$product = wc_get_product($productid);
			}elseif(function_exists('get_product'))
			{
				$product = get_product($productid);
			}
			if(!empty($product) && is_object($product))
			{
					if($product->product_type !== 'variation')
					{
						do_action( 'woocommerce_process_product_meta_' . $product->product_type, $productid ); 
						do_action( 'woocommerce_update_product_variation', $productid );
					}
				if($proddata !== null)
				{
					foreach($proddata as $arrrow => $rowdata)
					{
						$_REQUEST[$arrrow] = $rowdata;
					}
				}
				if(self::$bhandlewoocog)
				{
					if(isset($proddata['_wc_cog_cost']) && $product->product_type === 'variation')
					{
						update_post_meta($productid,'_wc_cog_default_cost','no');
					}
					$cog = get_post_meta($productid,'_wc_cog_cost',true);
					$_REQUEST['_wc_cog_cost'] = $cog;
//						$cog = get_post_meta($productid,'_wc_cog_default_cost',true);
//						$_REQUEST['_wc_cog_default_cost'] = $cog;
				}
				do_action( 'woocommerce_product_quick_edit_save',$product);
				
				if($proddata !== null)
					do_action( 'woocommerce_api_edit_product', $productid, $proddata ); 
			}
		}
		if(self::$bsavepost)
		{
//			clean_post_cache( $productid );
			$post = get_post($productid);
			do_action( 'edit_post', $productid, $post );
//			$post_after = get_post($post_ID);

		/**
		 * Fires once an existing post has been updated.
		 *
		 * @since 3.0.0
		 *
		 * @param int     $post_ID      Post ID.
		 * @param WP_Post $post_after   Post object following the update.
		 * @param WP_Post $post_before  Post object before the update.
		 */
//			if($oldpost !== null)
//				do_action( 'post_updated', $productid, $post, $oldpost);
			if($proddata !== null)
			{
				foreach($proddata as $arrrow => $rowdata)
				{
					$_REQUEST[$arrrow] = $rowdata;
				}
			}
			do_action( 'save_post',$productid,$post,true);
			do_action( "save_post_{$post->post_type}", $productid, $post, true );
			do_action( 'wp_insert_post', $productid, $post, true );
		}
	}
	
	public static function mres($value)
	{
//		$search = array("\x00", "\n", "\r", "\\", "'", "\"", "\x1a");
//		$replace = array("\\x00", "\\n", "\\r", "\\\\" ,"\';", "\\\"", "\\\x1a");

//		return str_replace($search, $replace, $value);
		return strtr($value, array(
		  "\x00" => '\x00',
		  "\n" => '\n', 
		  "\r" => '\r', 
		  '\\' => '\\\\',
		  "'" => "\'", 
		  '"' => '\"', 
		  "\x1a" => '\x1a'
		));
	}
	
	public static function GetTaxClasses(&$tax_classes)
	{
		$option = get_option('woocommerce_tax_classes');
		if(!is_string($option)) return;
		$classes = array_filter( array_map( 'trim', explode( "\n", $option ) ) );
		foreach($classes as $class)
		{
			$tax_classes[sanitize_title($class)] = $class;
		}
	}
	
	public static function LoopMetaData(&$metavals,&$ids,&$tax_classes,$converttoutf8)
	{
		foreach($metavals as &$val)
		{
			
			if(array_key_exists($val->ID,$ids))
			{
				$obj = $ids[$val->ID];
				
				$metavalue = "";
				if($converttoutf8)
				{
					$metavalue = mb_convert_encoding($val->meta_value, "UTF-8");
				}else
				{
					$metavalue = $val->meta_value;
				}
				$metakey = $val->meta_key;
				if(strpos($metakey,'attribute_pa_') !== FALSE && $obj->post_type == 'product_variation')
				{
					$newmeta = apply_filters( 'sanitize_taxonomy_name', urldecode( sanitize_title( $metakey ) ), $metakey );
					$obj->{$newmeta} = $metavalue;
					continue;
//					$attname = ucfirst($metavalue);
//					if($attname !== "")
//					{
//						if(isset($attrmapslugtoname[$metavalue]))
//						{
//							$attname = $attrmapslugtoname[$metavalue];
//						}
//						$attname = "(". $attname . ")";
//					}
//					if(property_exists($obj,'post_title'))
//					{
//						$obj->post_title = $obj->post_title." ".$attname;
//					}else
//					{
//						$obj->post_title = "Variation ".$attname;
//					}
//					if(is_array($attributes) && !empty($attributes))
//					{
//						foreach($attributes as $attr)
//						{
//							$attr_col = 'attribute_pa_'.$attr->name;
//							if($attr_col == $metakey)
//							{
//								foreach($attr->values as $value)
//								{
//									if($metavalue == $value->slug)
//									{
//										$obj->{$val->meta_key} = $value->name;
//										$obj->{$val->meta_key . '_ids'} = $value->term_id;
////										$idmap = array((string)$value->name,'attribute_pa_'.$attr->name);
////										$cats_assoc[$value->id] = $idmap;
//										break;
//									}
//								}
//								break;
//							}
//					    }
//					}
//					continue;
				}
				if($val->meta_key == '_downloadable_files')
				{
					$downloadable_files = maybe_unserialize($val->meta_value);
						
					if ( $downloadable_files ) 
					{
						if(is_array($downloadable_files))
						{
							$obj->_downloadable_files = "";
							$obj->_downloadable_files_val = "";
							foreach ( $downloadable_files as $key => $file ) 
							{
								$filepath = $file["file"];
								$filename = "";
								if(isset($file["name"]))
									$filename = $file["name"];
//									if($filename != "")
								{
									$obj->_downloadable_files = $obj->_downloadable_files . " Name:" . $filename . " URL:" . $filepath;
									if($obj->_downloadable_files_val == "")
										$obj->_downloadable_files_val = $filename . "#####" . $filepath;
									else
										$obj->_downloadable_files_val = $obj->_downloadable_files_val . "*****" . $filename . "#####" . $filepath;
								}
							}
						}
					}
				}else if($metakey == '_download_type'){
					if($val->meta_value == "")
						$obj->_download_type = "Standard";
					if($val->meta_value == "application")
						$obj->_download_type = "Application";
					if($val->meta_value == "music")
						$obj->_download_type = "Music";
				}else if($metakey == '_stock_status'){
					if($val->meta_value !== "outofstock")
						$obj->_stock_status = "instock";
					else
						$obj->_stock_status = "outofstock";
				}else if($metakey == '_stock'){
						if($metavalue !== "")
							$obj->_stock = (int)$metavalue;
						else
							$obj->_stock = $metavalue;
				}else if($metakey == '_visibility'){
					$obj->_visibility = "Catalog/search";
					if($val->meta_value == "visible")
						$obj->_visibility = "Catalog/search";
					if($val->meta_value == "catalog")
						$obj->_visibility = "Catalog";
					if($val->meta_value == "search")
						$obj->_visibility = "Search";
					if($val->meta_value == "hidden")
						$obj->_visibility = "Hidden";
				}else if($metakey == '_tax_class'){
//					if(!is_string($val->meta_value))
//						$val->meta_value = "";
					$metavalue = trim($metavalue);
					if($metavalue == "")
						$obj->_tax_class = "Standard";
					else
					{
						if(count($tax_classes) >0)
						{
							if(isset($tax_classes[$metavalue]))
							{
								$obj->_tax_class = $tax_classes[$metavalue];
							}
						}else
						{
							if($metavalue == "reduced-rate")
								$obj->_tax_class = "Reduced Rate";
							if($metavalue == "zero-rate")
								$obj->_tax_class = "Zero Rate";
						}
					}
				}
				else if($metakey == '_tax_status')
				{
					if($metavalue == "" || $val->meta_value == "taxable")
						$obj->{$metakey} = "Taxable";
					else if($metavalue == "shipping")
						$obj->{$metakey} ="Shipping only";
					else if($metavalue == "none")
						$obj->{$metakey} ="None";
//						$obj->{$val->meta_key} = $val->meta_value;
				}else if($metakey == '_upsell_ids'){
					if($val->meta_value !== "")
					{
						$sellids = maybe_unserialize($val->meta_value);
						if(is_array($sellids) && count($sellids) > 0)
						{
							$insertstr = '';
							foreach ( $sellids as $curid ) 
							{
								if($insertstr === '')
								{
									$insertstr = (string)$curid;
								}else
								{
									$insertstr.= ', '.(string)$curid;
								}
							}
							$obj->{$metakey} = $insertstr;
						}
						
					}
						
				}else if($metakey == '_crosssell_ids'){
					if($val->meta_value !== "")
					{
						$sellids = maybe_unserialize($val->meta_value);
						if(is_array($sellids) && count($sellids) > 0)
						{
							$insertstr = '';
							foreach ( $sellids as $curid ) 
							{
								if($insertstr === '')
								{
									$insertstr = (string)$curid;
								}else
								{
									$insertstr.= ', '.(string)$curid;
								}
							}
							$obj->{$metakey} = $insertstr;
						}
						
					}
				}else if($metakey == '_backorders'){
					if($metavalue == "no")
						$obj->_backorders = "Do not allow";
					if($metavalue == "notify")
						$obj->_backorders = "Allow but notify";
					if($metavalue == "yes")
						$obj->_backorders = "Allow";
				}else if($metakey == '_sale_price_dates_from' || $metakey == '_sale_price_dates_to'){
					if($metavalue !== "")
						$obj->{$metakey} = date('Y-m-d', $metavalue);
				}else if($metakey == '_regular_price' || $metakey == '_sale_price'){
					$obj->{$metakey} = str_replace(",",".",$metavalue);
				}else if($metakey == '_sold_individually'){
					if($metavalue == "")
						$obj->_sold_individually = "no";
					else
						$obj->{$metakey} = $metavalue;
				}else if($metakey == '_default_attributes'){
					if($metavalue !== "")
					{
						$def_attrs = maybe_unserialize($metavalue);
						if(is_array($def_attrs) && count($def_attrs) > 0)
						{
							$value = "";
							foreach($def_attrs as $attr => $def_slug)
							{
								if($value === "")
								{
									$value = $attr.','.$def_slug;
								}else
								{
									$value.= ' ;'.$attr.','.$def_slug;
								}
							}
							
							$obj->{$metakey} = $value;
						}
						
					}
				}else if($metakey == '_product_attributes')
				{
					if($obj->post_type == 'product')
					{
//						if(is_array($attributes))
						{
							$attributes_meta = maybe_unserialize($metavalue);
							$attributes_meta1 = array();
							if (is_array($attributes_meta)) 
							{
								foreach($attributes_meta as $keyattr => $valarray)
								{
									
									if(isset( $valarray['is_taxonomy']) &&  $valarray['is_taxonomy'] === 1)
									{
										$keyattr1 = apply_filters( 'sanitize_taxonomy_name', urldecode( sanitize_title( $keyattr ) ), $keyattr );
										$attributes_meta1[$keyattr1] = array();
										$attributes_meta1[$keyattr1] = $valarray;
									}else
									{
										$attributes_meta1[$keyattr] = array();
										$attributes_meta1[$keyattr] = $valarray;
									}
								}
							}
							$obj->{$metakey} = $attributes_meta1;
							if (is_array($attributes_meta)) 
							{
								foreach($attributes_meta as $keyattr => $valarray)
								{
									if(isset( $valarray['is_taxonomy']) &&  $valarray['is_taxonomy'] === 0)
									{
										if(!isset(self::$mapcustom[$valarray['name']]))
										{
											self::$mapcustom[$valarray['name']] = sanitize_title($valarray['name']);
//											self::$mapcustom[self::$mapcustom[$valarray['name']]] = $valarray['name'];
										}
										$values = array_map( 'trim', explode( WC_DELIMITER, $valarray['value'] ) );

										foreach ( $values as $value ) 
										{
											if(!isset(self::$mapcustom[$value]))
											{
												self::$mapcustom[$value] = sanitize_title($value);
											}
//											self::$mapcustom[self::$mapcustom[$value]] = $value;
										} 
									}
								}
							}
							continue;
							if (is_array($attributes_meta)) 
							{
								foreach($attributes_meta as $keyattr => $valarray)
								{
	//								$taxonomy_slug = str_replace('attribute_','',$attribute);
		if(array_key_exists($keyattr,$attributekeys))
		{
			$taxonomy_slug = 'attribute_' . $keyattr; 
//			if(property_exists($obj,$taxonomy_slug))
			{
				if(isset( $valarray['is_visible']))
				{
					if(!property_exists($obj,$taxonomy_slug.'_visiblefp'))
					{
						
						$isvars = (int)$valarray['is_visible'];
						if($isvars > 0)
							$isvars = 1;
						$obj->{$taxonomy_slug.'_visiblefp'} = $isvars;
					}
					else
					{
						$oldvalue = (int)$obj->{$taxonomy_slug.'_visiblefp'};
						$isvars = (int)$valarray['is_visible'];
						if($isvars > 0)
							$isvars = 1;
						$oldvalue|= $isvars;
						$obj->{$taxonomy_slug.'_visiblefp'} = $oldvalue;
					}
				}
				if(isset( $valarray['is_variation']))
				{
					if(!property_exists($obj,$taxonomy_slug.'_visiblefp'))
					{
						
						$obj->{$taxonomy_slug.'_visiblefp'} = $valarray['is_variation'];
						$isvars = (int)$valarray['is_variation'];
						if($isvars > 0)
							$isvars = 2;
						else 
							$isvars = 0;
						$obj->{$taxonomy_slug.'_visiblefp'} = $isvars;
					}
					else
					{
						$oldvalue = (int)$obj->{$taxonomy_slug.'_visiblefp'};
						$isvars = (int)$valarray['is_variation'];
						if($isvars > 0)
							$isvars = 2;
						$oldvalue|= $isvars;
						$obj->{$taxonomy_slug.'_visiblefp'} = $oldvalue;
					}
				}
			}
		}else
		{//custom one
//			$obj->_custom_attributes = $keyattr;
			if(!property_exists($obj,'_custom_attributes'))
				$obj->_custom_attributes = array();
			$newcustom = new stdClass();
			$newcustom->name = $valarray['name'];
			$newcustom->value = $valarray['value'];
			$obj->_custom_attributes[] = $newcustom;
		}
								}
															
							}
						}
					}
				}else{
					$obj->{$metakey} = $metavalue;
				}
			}
		}
	}
	
	public static function PrepareQuery($which,$customparam = NULL)
	{
		$ret = "";
		if($which === "wp_posts")
		{
//			p1.ID,p1.post_title,p1.post_parent,p1.post_status,p1.post_content,p1.post_excerpt,p1.post_name,p1.post_date,p1.comment_status,p1.menu_order,p1.post_type
			$fields = array();
			$fields[] = 'post_title';
			$fields[] = 'post_author';
			$fields[] = 'post_status';
			$fields[] = 'post_content';
			$fields[] = 'post_excerpt';
			$fields[] = 'post_name';
			$fields[] = 'post_date';
			$fields[] = 'comment_status';
			$fields[] = 'menu_order';
			foreach($fields as $field)
			{
				if(in_array($field,self::$columns) || empty(self::$columns))
				{
					if($ret === "")
					{
						$ret = 'p1.'.$field;
					}else
					{
						$ret = $ret.',p1.'.$field;
					}
				}
			}
		}elseif($which === "wp_meta1")
		{
//			'_regular_price','_sale_price','_sku','_weight','_length','_width','_height','_stock','_stock_status','_visibility','_virtual','_download_type','_download_limit','_download_expiry'
			$fields = array();
			$fields[] = '_regular_price';
			$fields[] = '_sale_price';
			$fields[] = '_sku';
			$fields[] = '_weight';
			$fields[] = '_length';
			$fields[] = '_width';
			$fields[] = '_height';
			$fields[] = '_stock';
			$fields[] = '_stock_status';
			if(!self::$isversion3)
			{
				
				$fields[] = '_visibility';
			}
			$fields[] = '_virtual';
			$fields[] = '_download_type';
			$fields[] = '_download_limit';
			$fields[] = '_download_expiry';
			foreach($fields as $field)
			{
				if(in_array($field,self::$columns) || empty(self::$columns))
				{
					if($ret === "")
					{
						$ret = "'".$field."'";
					}else
					{
						$ret = $ret.",'".$field."'";
					}
				}
			}
			
		}elseif($which === "wp_meta2")
		{
//			//		'_downloadable_files','_downloadable','_sale_price_dates_from','_sale_price_dates_to','_tax_class','_tax_status','_backorders','_manage_stock','_featured','_purchase_note'
			$fields = array();
			$fields[] = '_downloadable_files';
			$fields[] = '_downloadable';
			$fields[] = '_sale_price_dates_from';
			$fields[] = '_sale_price_dates_to';
			$fields[] = '_tax_class';
			$fields[] = '_tax_status';
			$fields[] = '_backorders';
			$fields[] = '_manage_stock';
			if(!self::$isversion3)
				$fields[] = '_featured';
			$fields[] = '_purchase_note';
			foreach($fields as $field)
			{
				if(in_array($field,self::$columns) || empty(self::$columns))
				{
					if($ret === "")
					{
						$ret = "'".$field."'";
					}else
					{
						$ret = $ret.",'".$field."'";
					}
				}
			}
		}elseif($which === "wp_meta3")
		{
//'_variation_description','_sold_individually','_product_url','_button_text','_thumbnail_id','_product_image_gallery','_upsell_ids','_crosssell_ids','_product_attributes','_default_attributes'{$customfields}
			$fields = array();
			$fields[] = '_variation_description';
			$fields[] = '_sold_individually';
			$fields[] = '_product_url';
			$fields[] = '_button_text';
			$fields[] = '_thumbnail_id';
			$fields[] = '_product_image_gallery';
			$fields[] = '_upsell_ids';
			$fields[] = '_crosssell_ids';
			$fields[] = '_default_attributes';
			$ret = "'_product_attributes'";
			foreach($fields as $field)
			{
				if(in_array($field,self::$columns) || empty(self::$columns))
				{
					if($ret === "")
					{
						$ret = "'".$field."'";
					}else
					{
						$ret = $ret.",'".$field."'";
					}
				}
			}
			if($customparam !== NULL)
			{
				foreach($customparam as $value)
				{
					if(in_array($value,self::$columns) || empty(self::$columns))
					{
						if($ret === "")
						{
							$ret = "'".esc_attr($value)."'";
						}else
						{
							$ret = $ret.",'".esc_attr($value)."'";
						}
					}
				}
			}
		}elseif($which === "columnchange")
		{
//			//		'_downloadable_files','_downloadable','_sale_price_dates_from','_sale_price_dates_to','_tax_class','_tax_status','_backorders','_manage_stock','_featured','_purchase_note'
			$fields = array();
			$fields[] = '_downloadable_files';
			$fields[] = '_downloadable';
			$fields[] = '_sale_price_dates_from';
			$fields[] = '_sale_price_dates_to';
			$fields[] = '_tax_class';
			$fields[] = '_tax_status';
			$fields[] = '_backorders';
			$fields[] = '_manage_stock';
			if(!self::$isversion3)
				$fields[] = '_featured';
			$fields[] = '_purchase_note';
			foreach($fields as $field)
			{
				if(in_array($field,self::$columns) || empty(self::$columns))
				{
					if($ret === "")
					{
						$ret = "'".$field."'";
					}else
					{
						$ret = $ret.",'".$field."'";
					}
				}
			}
		}

		return $ret;
	}
	public static function loadProducts($titleparam,$catparams,$attrparams,$priceparam,$saleparam,$customparam,&$total,$ispagination,$isnext,&$hasnext,&$isbegin,$categoryor,$skuparam,$tagsparams,$descparam,$shortdescparam,$custsearchparam,$arrduplicate = null, $reserved = null)
	{
	try {
		global $wpdb;
		$wpdb->suppress_errors( true );
		global $woocommerce;
		if(isset($woocommerce) && property_exists($woocommerce,'version'))
		{
			$version = (double)$woocommerce->version;
			if($version > 2.6)
				self::$isversion3 = true;
		}
//		$chars = get_bloginfo('charset');
		$posts = $wpdb->posts;
		$meta = $wpdb->postmeta;
		$temptable = $wpdb->prefix."wpmelon_advbedit_temp";
		$term = $wpdb->term_relationships;
		$term_taxonomy = $wpdb->term_taxonomy;
		$attributes = array();
		$attrmapslugtoname = array();
		$LIMIT = 1000;
		$temptotal = 0;
		$idlimitquery = "";
		$bgetvariations = true;
		$bgettotalnumber = true;
		$bgetallvars = false;
		$bgetallvarstaxonomies = false;
		$bdebugmode = false;
		$idquery = "";
		$minused = "";
		$maxused = "";
		$p1idquery = "";
		$getnumberquery = "";
		$limitquery = "";
		$sortquery = " DESC";
		$info = array();
		$tax_classes = array();
		self::GetTaxClasses($tax_classes);
		
		$curr_settings = get_option('w3exabe_settings');
		if(!is_array($curr_settings))
		{
			$curr_settings = array();
		}
		
		if(isset($curr_settings['isvariations']))
		{
			if($curr_settings['isvariations'] == 0)
				$bgetvariations = false;
		}
		if(isset($curr_settings['settlimit']))
		{
			$LIMIT = (int)$curr_settings['settlimit'];
		}
		if(isset($curr_settings['settgetall']))
		{
			if($curr_settings['settgetall'] == 1)
				$bgettotalnumber = false;
		}
		if(isset($curr_settings['settgetvars']))
		{
			if($curr_settings['settgetvars'] == 1)
				$bgetallvars = true;
		}
		if(isset($curr_settings['bgetallvarstaxonomies']))
		{
			if($curr_settings['bgetallvarstaxonomies'] == 1)
				$bgetallvarstaxonomies = true;
		}
		if(isset($curr_settings['debugmode']))
		{
			if($curr_settings['debugmode'] == 1)
			{
				$bdebugmode = true;
				self::$debugmode = true;
			}
		}
			self::WriteDebugInfo("0.5 after get t classes ".__LINE__,$curr_settings);
			self::WriteDebugInfo("clear",$curr_settings);
		
		self::GetAttributes($attributes,$attrmapslugtoname);

			self::WriteDebugInfo("0.6 after get attrs ".__LINE__,$curr_settings);
			
		$attributekeys = array();
		if(is_array($attributes) && !empty($attributes))
		{
			foreach($attributes as $attr)
			{
				$attributekeys['pa_'.$attr->name] = 'pa_'.$attr->name;
		    }
		}
		$query = "CREATE TABLE IF NOT EXISTS {$temptable} (
			 ID bigint(20) unsigned NOT NULL DEFAULT '0',
   			 type int(1) NOT NULL DEFAULT '0',
    	     post_parent bigint(20) unsigned NOT NULL DEFAULT '0',
			 useit int(1) NOT NULL DEFAULT '0',
			 PRIMARY KEY(ID))";
if($arrduplicate === null)
{
		$ret = $wpdb->query($query);

			self::WriteDebugInfo("0.9 after create t ".__LINE__,$curr_settings);

		if ( false === $ret) {
				return new WP_Error( 'db_query_error', 
					__( 'Could not execute query' ), $wpdb->last_error );
		} 
		$orderby = "ORDER BY {$posts}.ID DESC";
		if ( $ispagination) 
		{
			$query = "SELECT MIN(ID) FROM {$temptable} WHERE useit=1";
			$ret = $wpdb->get_var($query);
			$minused = $ret;
			$query = "SELECT MAX(ID) FROM {$temptable} WHERE useit=1";
			$ret = $wpdb->get_var($query);
			$maxused = $ret;
			if($isnext)
			{
				if($ret)
				{
					$idquery = " AND ID < {$minused}";
					$p1idquery = " AND p1.ID < {$minused}";
					
				}else
				{
					$ispagination = false;
					$isbegin = true;
				}
			}				
			else
			{
				
				if($ret)
				{
					$idquery = " AND ID > {$maxused}";
					$p1idquery = " AND p1.ID > {$maxused}";
					$sortquery = " ASC";
					$orderby = "ORDER BY {$posts}.ID ASC";
					
				}else
				{
					$ispagination = false;
					$isbegin = true;
				}
				
			}
			
			
		}
	
			self::WriteDebugInfo("1 before truncate",$curr_settings);
		
		$query = "TRUNCATE TABLE {$temptable}";
		$ret = $wpdb->query($query);
		if ( false === $ret) {
			if ( is_wp_error( $ret ) ) {
				return new WP_Error( 'db_query_error', 
					__( 'Could not execute query' ), $wpdb->last_error );
			} else {
				$query = "DELETE FROM {$temptable} WHERE 1";
				$ret = $wpdb->query($query);
				if ( false === $ret) {
					return $wpdb->last_error;
				}
			}
		}
		if($bdebugmode)
		{
			self::WriteDebugInfo("2 after truncate",$curr_settings);
		}
		$catsquery = "";
		$pricequery = "";
		$salequery = "";
//		$titlequery = "";
		$titlelike = "";
		if($catparams == NULL) $catparams = array();
		if($attrparams == NULL) $attrparams = array();
		if($titleparam == NULL) $titleparam = "";
		if($descparam == NULL) $descparam = "";
		if($shortdescparam == NULL) $shortdescparam = "";
		if($customparam == NULL) $customparam = array();
		if($skuparam == NULL) $skuparam = "";
		if($tagsparams == NULL) $tagsparams = array();
		if($custsearchparam == NULL) $custsearchparam = array();
		$hascustomtax = false;
		$hasattribute = false;
		$hascatnone = false;
		$wherenotin = "";  //AND	{$posts}.ID NOT IN (SELECT {$term}.object_id FROM {$term} WHERE {$term}.term_taxonomy_id IN (43,44))
		foreach($custsearchparam as $custitem)
		{
			if(isset($custitem['type']) && ($custitem['type'] === 'custom' || $custitem['type'] === 'customh'))
			{
				if(isset($custitem['array']) && is_array($custitem['array']))
				{
					$hascustomtax = true;
				}
				if($custitem['id'] === 'product_type')
					$bgetallvarstaxonomies = true;
			}
			if(isset($custitem['type']) && ($custitem['type'] === 'attribute'))
			{
//				if(isset($custitem['array']) && is_array($custitem['array']))
				{
					$hasattribute = true;
				}
			}
		}
		if(count($catparams) > 0 || count($attrparams) > 0 || count($tagsparams) > 0 || $hascustomtax || $hasattribute)
		{
			if(is_array($curr_settings))
			{
				if(isset($curr_settings['incchildren']))
				{
					if($curr_settings['incchildren'] == 1)
						self::HandleCatParams($catparams);
					self::WriteDebugInfo("incchildren",$curr_settings,array($curr_settings['incchildren']));
				}
			}
			//$catsquery = "INNER JOIN {$term} rel ON {$posts}.ID=rel.object_id AND rel.term_taxonomy_id IN (";
			
			$bfirst = true;
			$catcounter = 0;
		if(!in_array('none',$catparams))
		{
			if($categoryor)
			{
				foreach($catparams as $catparam)
				{
					$catcounter++;
					$catsquery.= " INNER JOIN {$term} rel{$catcounter} ON {$posts}.ID=rel{$catcounter}.object_id AND rel{$catcounter}.term_taxonomy_id IN (".$catparam.")";
				}
				foreach($tagsparams as $tagparam)
				{
					$catcounter++;
					$catsquery.= " INNER JOIN {$term} rel{$catcounter} ON {$posts}.ID=rel{$catcounter}.object_id AND rel{$catcounter}.term_taxonomy_id IN (".$tagparam.")";
				}
				foreach($custsearchparam as $custitem)
				{
					if(isset($custitem['id']) && $custitem['id'] === 'post_author')
						continue;
					if(isset($custitem['type']) && ($custitem['type'] === 'custom' || $custitem['type'] === 'customh'))
					{
						
						if(isset($custitem['array']) && is_array($custitem['array']))
						{
							if(in_array('none',$custitem['array'] ))
							   continue;
							foreach($custitem['array'] as $custarritem)
							{
								$catcounter++;
								$catsquery.= " INNER JOIN {$term} rel{$catcounter} ON {$posts}.ID=rel{$catcounter}.object_id AND rel{$catcounter}.term_taxonomy_id IN (".$custarritem.")";
							}
						}
					}
					if(isset($custitem['type']) && ($custitem['type'] === 'attribute'))
					{
						$catcounter++;
						$catsquery.= " INNER JOIN {$term} rel{$catcounter} ON {$posts}.ID=rel{$catcounter}.object_id AND rel{$catcounter}.term_taxonomy_id IN (".$custitem['title']['id'].")";
					}
				}
			}else
			{
				$taxids = "";
				foreach($catparams as $catparam)
				{
					if($taxids === "")
					{
						$taxids = $catparam;
					}else
					{
						$taxids.= ','.$catparam;
					}
				}
				foreach($tagsparams as $tagparam)
				{
					if($taxids === "")
					{
						$taxids = $tagparam;
					}else
					{
						$taxids.= ','.$tagparam;
					}
				}
				foreach($custsearchparam as $custitem)
				{
					if(isset($custitem['id']) && $custitem['id'] === 'post_author')
						continue;
					if(isset($custitem['type']) && ($custitem['type'] === 'custom' || $custitem['type'] === 'customh'))
					{
						if(isset($custitem['array']) && is_array($custitem['array']))
						{
							if(in_array('none',$custitem['array'] ))
							  	continue;
							foreach($custitem['array'] as $custarritem)
							{
								if($taxids === "")
								{
									$taxids = $custarritem;
								}else
								{
									$taxids.= ','.$custarritem;
								}
							}
						}
					}
					if(isset($custitem['type']) && ($custitem['type'] === 'attribute'))
					{
						if($taxids === "")
						{
							$taxids = $custitem['title']['id'];
						}else
						{
							$taxids.= ','.$custitem['title']['id'];
						}
					}
				}
				if($taxids !== "")
					$catsquery= " INNER JOIN {$term} rel ON {$posts}.ID=rel.object_id AND rel.term_taxonomy_id IN (".$taxids.") ";
			}
			foreach($catparams as $catparam)
			{
//				if($bfirst)
//				{
//					$bfirst = false;
//					$catsquery.= $catparam;
//					if($categoryor)
//					{
//						foreach($attrparams as $attrparam)
//						{
//							$catsquery.= ','. $attrparam['id'];
//						}
//						foreach($tagsparams as $tagparam)
//						{
//							$catsquery.= ','. $tagparam;
//						}
//						foreach($custsearchparam as $custitem)
//						{
//							if(isset($custitem['type']) && ($custitem['type'] === 'custom' || $custitem['type'] === 'customh'))
//							{
//								if(isset($custitem['array']) && is_array($custitem['array']))
//								{
//									if(in_array('none',$custitem['array'] ))
//									{
//										foreach($custitem['array'] as $custarritem)
//										{
//											if($custarritem === "none") continue;
//											if($wherenotin === "")
//											{
//												$wherenotin = $custarritem;
//											}else
//											{
//												$wherenotin.= ','. $custarritem;
//											}
//										}
//										continue;
//									}
//									foreach($custitem['array'] as $custarritem)
//									{
//										$catsquery.= ','. $custarritem;
//									}
//								}
//							}
//						}
//						if($catsquery !== "")
//						{
//							$catsquery = " INNER JOIN {$term} rel ON {$posts}.ID=rel.object_id AND rel.term_taxonomy_id IN (".$catsquery.")";
//						}
//						
//					}
//				}else
//				{
//					$catcounter++;
//					if($categoryor)
//					{
//						$catsquery.= " INNER JOIN {$term} rel{$catcounter} ON {$posts}.ID=rel{$catcounter}.object_id AND rel{$catcounter}.term_taxonomy_id IN (".$catparam;
//						foreach($attrparams as $attrparam)
//						{
//							$catsquery.= ','. $attrparam['id'];
//						}
//						foreach($tagsparams as $tagparam)
//						{
//							$catsquery.= ','. $tagparam;
//						}
//						foreach($custsearchparam as $custitem)
//						{
//							if(isset($custitem['type']) && ($custitem['type'] === 'attribute'))
//							{
//								if(isset($custitem['array']) && is_array($custitem['array']))
//								{
//									
//								}
//							}
//							if(isset($custitem['type']) && ($custitem['type'] === 'custom' || $custitem['type'] === 'customh'))
//							{
//								if(isset($custitem['array']) && is_array($custitem['array']))
//								{
//									if(in_array('none',$custitem['array'] ))
//									{
//										foreach($custitem['array'] as $custarritem)
//										{
//											if($custarritem === "none") continue;
//											if($wherenotin === "")
//											{
//												$wherenotin = $custarritem;
//											}else
//											{
//												$wherenotin.= ','. $custarritem;
//											}
//										}
//										continue;
//									}
//									foreach($custitem['array'] as $custarritem)
//									{
//										$catsquery.= ','. $custarritem;
//									}
//								}
//							}
//						}
//						if($catsquery !== "")
//						{
//							$catsquery.= ')';
//							$catsquery = "INNER JOIN {$term} rel ON {$posts}.ID=rel.object_id AND rel.term_taxonomy_id IN (".$catsquery.")";
//						}
//					}else
//					{
//						$catsquery.= ','. $catparam;
//					}
//					
//				}
			}
		}else // end if not uncategorized
		{
			foreach($catparams as $catparam)
			{
				if($catparam === "none") continue;
				if($wherenotin === "")
				{
					$wherenotin = $catparam;
				}else
				{
					$wherenotin.= ','. $catparam;
				}
			}
			$hascatnone = true;
		}
		//shipping none
		foreach($custsearchparam as $custitem)
		{
			if(isset($custitem['id']) && $custitem['id'] === 'post_author')
				continue;
			if(isset($custitem['type']) && ($custitem['type'] === 'custom' || $custitem['type'] === 'customh'))
			{
				if(isset($custitem['array']) && is_array($custitem['array']))
				{
					if(in_array('none',$custitem['array'] ))
					{
						foreach($custitem['array'] as $custarritem)
						{
							if($custarritem === "none") continue;
							if($wherenotin === "")
							{
								$wherenotin = $custarritem;
							}else
							{
								$wherenotin.= ','. $custarritem;
							}
						}
					}
				}
			}
		}
			

		}
		if($wherenotin !== "")
		{
			$wherenotin = " AND {$posts}.ID NOT IN (SELECT {$term}.object_id FROM {$term} WHERE {$term}.term_taxonomy_id IN (".$wherenotin."))";
		}
		

		$arrsearchtitle = array();
		$dateparam = "";
		foreach($custsearchparam as $custitem)
		{
			if(isset($custitem['type']) && $custitem['type'] === 'date' && isset($custitem['value']) && isset($custitem['title']))
			{
				if($custitem['value'] === 'between' && !isset($custitem['title1']))
					break;
				if($custitem['value'] === 'more')
				{
					$dateparam = " AND {$posts}.post_date >= '".$custitem['title']."' ";
				}
				if($custitem['value'] === 'less')
				{
					$dateparam = " AND {$posts}.post_date <= '".$custitem['title']."' ";
				}
				if($custitem['value'] === 'between')
				{
					$dateparam = " AND ({$posts}.post_date >= '".$custitem['title']."' AND {$posts}.post_date <= '".$custitem['title1']."') ";
				}
				break;
			}
		}
		if($titleparam != NULL && $titleparam !== "")
		{
			$multiaction = "AND";
			if($reserved !== NULL)
			{
				foreach($reserved as $reserveitem)
				{
					if(isset($reserveitem['action']) && $reserveitem['action'] === 'OR' &&  $reserveitem['id'] === 'post_title')
					{
						$multiaction = "OR";
						break;
					}
				}
			}
			switch($titleparam['value']){
				case "con":
				{
					$searchstring = $titleparam['title'];
						$searchstring = trim($searchstring);
					if($searchstring == "") break;
					$arrstrings = explode(' ',$searchstring);
					
					if(count($arrstrings) > 1)
					{
						$titlelike = " AND (";
						$counter = 0;
						foreach($arrstrings as $arrstring)
						{
							$arrstring = trim($arrstring);
							if($arrstring == "") continue;
							if($titlelike == " AND (")
							{
								$titlelike.= "{$posts}.post_title LIKE '%%%s%%'";
							}
							else
							{
								$titlelike.= " {$multiaction} {$posts}.post_title LIKE '%%%s%%'";
							}
							$arrsearchtitle[] = $arrstring;
						}
						$titlelike.= ")";
						$counter++;
					}else
					{
						$titlelike = " AND {$posts}.post_title LIKE '%".$searchstring."%' ";
//						$arrsearchtitle[] = $searchstring;
					}
						
				}
				break;
				case "isexactly":
				{
					$titlelike = " AND {$posts}.post_title = '".$titleparam['title']."' ";
				}
				break;
				case "notcon":
				{
					$searchstring = $titleparam['title'];
						$searchstring = trim($searchstring);
					if($searchstring == "") break;
					$arrstrings = explode(' ',$searchstring);
					
					if(count($arrstrings) > 1)
					{
						$titlelike = " AND NOT (";
						$counter = 0;
						foreach($arrstrings as $arrstring)
						{
							$arrstring = trim($arrstring);
							if($arrstring == "") continue;
							if($titlelike == " AND NOT (")
							{
								$titlelike.= "{$posts}.post_title LIKE '%%%s%%'";
							}
							else
							{
								$titlelike.= " {$multiaction} {$posts}.post_title LIKE '%%%s%%'";
							}
							$arrsearchtitle[] = $arrstring;
						}
						$titlelike.= ")";
						$counter++;
					}else
					{
						$titlelike = " AND {$posts}.post_title NOT LIKE '%".$searchstring."%' ";
//						$arrsearchtitle[] = $searchstring;
					}
//					$titlelike = " AND {$posts}.post_title NOT LIKE '%".$titleparam['title']."%' ";
				}
				break;
				case "start":
				{
					$titlelike = " AND {$posts}.post_title LIKE '".$titleparam['title']."%' ";
				}
				break;
				case "end":
				{
					$titlelike = " AND {$posts}.post_title LIKE '%".$titleparam['title']."' ";
				}
				break;
				default:
					break;
			}
		}

		$desclike = "";
		if($descparam != NULL && $descparam !== "")
		{
			$multiaction = "AND";
			if($reserved !== NULL)
			{
				foreach($reserved as $reserveitem)
				{
					if(isset($reserveitem['action']) && $reserveitem['action'] === 'OR' &&  $reserveitem['id'] === 'post_content')
					{
						$multiaction = "OR";
						break;
					}
				}
			}
			switch($descparam['value']){
				case "con":
				{
					$searchstring = $descparam['title'];
					$searchstring = trim($searchstring);
					if($searchstring == "") break;
					$arrstrings = explode(' ',$searchstring);
					
					if(count($arrstrings) > 1)
					{
						$desclike = " AND (";
						$counter = 0;
						foreach($arrstrings as $arrstring)
						{
							$arrstring = trim($arrstring);
							if($arrstring == "") continue;
							if($desclike == " AND (")
							{
								$desclike.= "{$posts}.post_content LIKE '%%%s%%'";
							}
							else
							{
								$desclike.= " {$multiaction} {$posts}.post_content LIKE '%%%s%%'";
							}
							$arrsearchtitle[] = $arrstring;
						}
						$desclike.= ")";
						$counter++;
					}else
					{
						$desclike = " AND {$posts}.post_content LIKE '%".$searchstring."%' ";
//						$arrsearchtitle[] = $searchstring;
					}
						
				}
				break;
				case "notcon":
				{
					$searchstring = $descparam['title'];
					$searchstring = trim($searchstring);
					if($searchstring == "") break;
					$arrstrings = explode(' ',$searchstring);
					
					if(count($arrstrings) > 1)
					{
						$desclike = " AND NOT (";
						$counter = 0;
						foreach($arrstrings as $arrstring)
						{
							$arrstring = trim($arrstring);
							if($arrstring == "") continue;
							if($desclike == " AND NOT (")
							{
								$desclike.= "{$posts}.post_content LIKE '%%%s%%'";
							}
							else
							{
								$desclike.= " {$multiaction} {$posts}.post_content LIKE '%%%s%%'";
							}
							$arrsearchtitle[] = $arrstring;
						}
						$desclike.= ")";
						$counter++;
					}else
					{
						$desclike = " AND {$posts}.post_content NOT LIKE '%".$searchstring."%' ";
//						$arrsearchtitle[] = $searchstring;
					}
//					$desclike = " AND {$posts}.post_content NOT LIKE '%".$descparam['title']."%' ";
				}
				break;
				case "start":
				{
					$desclike = " AND {$posts}.post_content LIKE '".$descparam['title']."%' ";
				}
				break;
				case "end":
				{
					$desclike = " AND {$posts}.post_content LIKE '%".$descparam['title']."' ";
				}
				break;
				default:
					break;
			}
		}

		$shortdesclike = "";
		if($shortdescparam != NULL && $shortdescparam !== "")
		{
			$multiaction = "AND";
			if($reserved !== NULL)
			{
				foreach($reserved as $reserveitem)
				{
					if(isset($reserveitem['action']) && $reserveitem['action'] === 'OR' &&  $reserveitem['id'] === 'post_excerpt')
					{
						$multiaction = "OR";
						break;
					}
				}
			}
			switch($shortdescparam['value']){
				case "con":
				{
					$searchstring = $shortdescparam['title'];
					$searchstring = trim($searchstring);
					if($searchstring == "") break;
					$arrstrings = explode(' ',$searchstring);
					
					if(count($arrstrings) > 1)
					{
						$shortdesclike = " AND (";
						$counter = 0;
						foreach($arrstrings as $arrstring)
						{
							$arrstring = trim($arrstring);
							if($arrstring == "") continue;
							if($shortdesclike == " AND (")
							{
								$shortdesclike.= "{$posts}.post_excerpt LIKE '%%%s%%'";
							}
							else
							{
								$shortdesclike.= " {$multiaction} {$posts}.post_excerpt LIKE '%%%s%%'";
							}
							$arrsearchtitle[] = $arrstring;
						}
						$shortdesclike.= ")";
						$counter++;
					}else
					{
						$shortdesclike = " AND {$posts}.post_excerpt LIKE '%".$searchstring."%' ";
//						$arrsearchtitle[] = $searchstring;
					}
						
				}
				break;
				case "notcon":
				{
					$searchstring = $shortdescparam['title'];
					$searchstring = trim($searchstring);
					if($searchstring == "") break;
					$arrstrings = explode(' ',$searchstring);
					
					if(count($arrstrings) > 1)
					{
						$shortdesclike = " AND NOT (";
						$counter = 0;
						foreach($arrstrings as $arrstring)
						{
							$arrstring = trim($arrstring);
							if($arrstring == "") continue;
							if($shortdesclike == " AND NOT (")
							{
								$shortdesclike.= "{$posts}.post_excerpt LIKE '%%%s%%'";
							}
							else
							{
								$shortdesclike.= " {$multiaction} {$posts}.post_excerpt LIKE '%%%s%%'";
							}
							$arrsearchtitle[] = $arrstring;
						}
						$shortdesclike.= ")";
						$counter++;
					}else
					{
						$shortdesclike = " AND {$posts}.post_excerpt NOT LIKE '%".$searchstring."%' ";
//						$arrsearchtitle[] = $searchstring;
					}
//					$shortdesclike = " AND {$posts}.post_excerpt NOT LIKE '%".$shortdescparam['title']."%' ";
				}
				break;
				case "start":
				{
					$shortdesclike = " AND {$posts}.post_excerpt LIKE '".$shortdescparam['title']."%' ";
				}
				break;
				case "end":
				{
					$shortdesclike = " AND {$posts}.post_excerpt LIKE '%".$shortdescparam['title']."' ";
				}
				break;
				default:
					break;
			}
		}
		
		$skuquery = "";
		$innercounter = 5;
		$ismultiple = false;
		if($skuparam != NULL && $skuparam !== "")
		{
			if($reserved !== NULL)
			{
				foreach($reserved as $reserveitem)
				{
					if(isset($reserveitem['action']) && $reserveitem['action'] === 'multiple' &&  $reserveitem['id'] === '_sku')
					{
						$ismultiple = true;
						break;
					}
				}
			}
			$skuquery = " INNER JOIN {$meta} meta3 ON {$posts}.ID=meta3.post_id
			AND CASE WHEN meta3.meta_key='_sku' THEN meta3.meta_value";
			switch($skuparam['value']){
				case "con":
				{
					if($ismultiple)
					{
						$searchstring = $skuparam['title'];
						$searchstring = trim($searchstring);
						if($searchstring == "") break;
						$arrstrings = explode(',',$searchstring);
						
						if(count($arrstrings) > 1)
						{
							$counter = 0;
							foreach($arrstrings as $arrstring)
							{
								$arrstring = trim($arrstring);
								if($arrstring == "") continue;
								$counter++;
								if($counter === 1)
								{
									$skuquery.= " LIKE '%".$arrstring."%'";
									continue;
								}
								$skuquery.= " OR meta3.meta_value LIKE '%".$arrstring."%'";
							}
						}
					}else
					{
						$skuquery.= " LIKE '%".$skuparam['title']."%'";
					}
				}
				break;
				case "isexactly":
				{
					if($ismultiple)
					{
						$searchstring = $skuparam['title'];
						$searchstring = trim($searchstring);
						if($searchstring == "") break;
						$arrstrings = explode(',',$searchstring);
						
						if(count($arrstrings) > 1)
						{
							$counter = 0;
							foreach($arrstrings as $arrstring)
							{
								$arrstring = trim($arrstring);
								if($arrstring == "") continue;
								$counter++;
								if($counter === 1)
								{
									$skuquery.= " = '".$arrstring."'";
									continue;
								}
								$skuquery.= " OR meta3.meta_value = '".$arrstring."'";
							}
						}
					}else
					{
						$skuquery.= " = '".$skuparam['title']."'";
					}
					
				}
				break;
				case "notcon":
				{
					if($ismultiple)
					{
						$searchstring = $skuparam['title'];
						$searchstring = trim($searchstring);
						if($searchstring == "") break;
						$arrstrings = explode(',',$searchstring);
						
						if(count($arrstrings) > 1)
						{
							$counter = 0;
							foreach($arrstrings as $arrstring)
							{
								$arrstring = trim($arrstring);
								if($arrstring == "") continue;
								$counter++;
								if($counter === 1)
								{
									$skuquery.= " NOT LIKE '%".$arrstring."%'";
									continue;
								}
								$skuquery.= " AND meta3.meta_value NOT LIKE '%".$arrstring."%'";
							}
						}
					}else
					{
						$skuquery.= " NOT LIKE '%".$skuparam['title']."%'";
					}
					
				}
				break;
				case "start":
				{
					if($ismultiple)
					{
						$searchstring = $skuparam['title'];
						$searchstring = trim($searchstring);
						if($searchstring == "") break;
						$arrstrings = explode(',',$searchstring);
						
						if(count($arrstrings) > 1)
						{
							$counter = 0;
							foreach($arrstrings as $arrstring)
							{
								$arrstring = trim($arrstring);
								if($arrstring == "") continue;
								$counter++;
								if($counter === 1)
								{
									$skuquery.= " LIKE '".$arrstring."%'";
									continue;
								}
								$skuquery.= " OR meta3.meta_value LIKE '".$arrstring."%'";
							}
						}
					}else
					{
						$skuquery.= " LIKE '".$skuparam['title']."%'";
					}
					
				}
				break;
				case "end":
				{
					if($ismultiple)
					{
						$searchstring = $skuparam['title'];
						$searchstring = trim($searchstring);
						if($searchstring == "") break;
						$arrstrings = explode(',',$searchstring);
						
						if(count($arrstrings) > 1)
						{
							$counter = 0;
							foreach($arrstrings as $arrstring)
							{
								$arrstring = trim($arrstring);
								if($arrstring == "") continue;
								$counter++;
								if($counter === 1)
								{
									$skuquery.= " LIKE '%".$arrstring."'";
									continue;
								}
								$skuquery.= " OR meta3.meta_value LIKE '%".$arrstring."'";
							}
						}
					}else
					{
						$skuquery.= " LIKE '%".$skuparam['title']."'";
					}
					
				}
				break;
				default:
					break;
			}
			$skuquery.=" END";
		}

		$posttypesearch = "'draft','publish','private','pending'";
		$custommetasearch = "";
		{
			
			foreach($custsearchparam as $custitem)
			{
				$innercounter++;
				if(isset($custitem['id']) && $custitem['id'] === 'post_author')
				{
					$shortdesclike = " AND {$posts}.post_author IN (".$custitem['array'][0].") ";
					continue;
				}
				if(isset($custitem['type']) && ($custitem['type'] !== 'custom' && $custitem['type'] !== 'customh' && $custitem['type'] !== 'attribute'))
				{
					if($custitem['type'] === "date")
						continue;
						
					if($custitem['id'] == "ID" || $custitem['id'] == "_stock" || $custitem['id'] == "_stock_status" || $custitem['id'] == "post_status"|| $custitem['id'] == "post_author")
					{
						if($custitem['id'] == "_stock")
						{
							if(!is_numeric($custitem['title']))
								continue;
						}
						if($custitem['id'] == "post_status")
						{
							$posttypesearch = "'" . $custitem['value'] . "'";
							continue;
						}
						if($custitem['id'] == "ID")
						{
							$shortdesclike = " AND {$posts}.ID IN (".$custitem['value'].") ";
							continue;
						}
						
				
						$custommetasearch.= " INNER JOIN {$meta} meta{$innercounter} ON {$posts}.ID=meta{$innercounter}.post_id
							AND CASE WHEN meta{$innercounter}.meta_key='{$custitem['id']}' THEN meta{$innercounter}.meta_value";
						if($custitem['id'] == "_stock")
						{
							if($custitem['value'] == 'more')
							{
								$custommetasearch.= ' > ';
							}else if($custitem['value'] == 'less')
							{
								$custommetasearch.= ' < ';
							}else if($custitem['value'] == 'equal')
							{
								$custommetasearch.= ' = ';
							}else if($custitem['value'] == 'moree')
							{
								$custommetasearch.= ' >= ';
							}else
							{//lesse
								$custommetasearch.= ' <= ';
							}
							$custommetasearch.= $custitem['title'].' END ';
						}else if($custitem['id'] == "_stock_status")
						{
//							$custommetasearch.= " LIKE '%".$custitem['title']."%' END ";
							if(!self::$isversion3)
							{
								if($custitem['title'] == "outofstock")
									$custommetasearch.= " LIKE 'outofstock' END ";
								else
									$custommetasearch.= " NOT LIKE 'outofstock' END ";
							}
						}
						continue;
					}
					if(isset($custitem['title']) && isset($custitem['value']))
					{
						if( $custitem['type'] === 'integer' ||  $custitem['type'] === 'decimal' ||  $custitem['type'] === 'decimal3')
						{
							if(!is_numeric($custitem['title']))
								continue;
						}
						$custommetasearch.= " INNER JOIN {$meta} meta{$innercounter} ON {$posts}.ID=meta{$innercounter}.post_id
							AND CASE WHEN meta{$innercounter}.meta_key='{$custitem['id']}' THEN meta{$innercounter}.meta_value";
						if($custitem['type'] === 'integer' ||  $custitem['type'] === 'decimal' ||  $custitem['type'] === 'decimal3')
						{
							if($custitem['value'] == 'more')
							{
								$custommetasearch.= ' > ';
							}else if($custitem['value'] == 'less')
							{
								$custommetasearch.= ' < ';
							}else if($custitem['value'] == 'equal')
							{
								$custommetasearch.= ' = ';
							}else if($custitem['value'] == 'moree')
							{
								$custommetasearch.= ' >= ';
							}else
							{//lesse
								$custommetasearch.= ' <= ';
							}
							$custommetasearch.= $custitem['title'].' END ';
							
						}else
						{
							switch($custitem['value'])
							{
								case "con":
								{
									$custommetasearch.= " LIKE '%".$custitem['title']."%' ";
								}
								break;
								case "notcon":
								{
									$custommetasearch.= " NOT LIKE '%".$custitem['title']."%' ";
								}
								break;
								case "start":
								{
									$custommetasearch.= " LIKE '".$custitem['title']."%' ";
								}
								break;
								case "end":
								{
									$custommetasearch.= " LIKE '%".$custitem['title']."' ";
								}
								break;
								default:
									break;
							}
							$custommetasearch.= ' END ';
						}
					}
				}
				
			}
		}
		
		$LIMIT+= 1;
//		if($catsquery !== "")
//		{
//			$catsquery.= "INNER JOIN {$term} rel ON {$posts}.ID=rel.object_id{$catsquery}";
//		}
		if(!$bgettotalnumber)
			$limitquery = " LIMIT {$LIMIT}";
//			INNER JOIN {$term} rel ON {$posts}.ID=rel.object_id{$catsquery}

/////////////////////////
		//get products
///////////////////////////////////////
		if($bgettotalnumber)
		{
			$query = "INSERT INTO {$temptable} (
				SELECT 
				{$posts}.ID, 0 AS type, 0 AS post_parent,0 as useit 
				FROM {$posts}
				{$catsquery}{$pricequery}{$salequery}{$skuquery}{$custommetasearch}
				WHERE {$posts}.post_type='product'{$titlelike}{$desclike}{$shortdesclike}{$dateparam} AND {$posts}.post_status IN ({$posttypesearch}) {$wherenotin} GROUP BY {$posts}.ID
				{$orderby})";
		}else
		{
			$query = "INSERT INTO {$temptable} (
				SELECT 
				{$posts}.ID, 0 AS type, 0 AS post_parent,0 as useit 
				FROM {$posts}
				{$catsquery}{$pricequery}{$salequery}{$skuquery}{$custommetasearch}
				WHERE {$posts}.post_type='product'{$titlelike}{$desclike}{$shortdesclike}{$dateparam} AND {$posts}.post_status IN ({$posttypesearch}) {$wherenotin} {$idquery} GROUP BY {$posts}.ID {$orderby}{$limitquery})";
		}
		if($catsquery === '')
		{//let's get products without product_type'
			if($bgettotalnumber)
			{
				$query = "INSERT INTO {$temptable} (
					SELECT 
					{$posts}.ID, 0 AS type, 0 AS post_parent,0 as useit 
					FROM {$posts}{$pricequery}{$salequery}{$skuquery}{$custommetasearch}
					WHERE ({$posts}.post_type='product'{$titlelike}{$desclike}{$shortdesclike}{$dateparam} {$wherenotin} AND {$posts}.post_status IN ({$posttypesearch})) {$orderby})";
			}else
			{
				$query = "INSERT INTO {$temptable} (
					SELECT 
					{$posts}.ID, 0 AS type, 0 AS post_parent,0 as useit 
					FROM {$posts}{$pricequery}{$salequery}{$skuquery}{$custommetasearch}
					WHERE ({$posts}.post_type='product'{$titlelike}{$desclike}{$shortdesclike}{$dateparam} {$wherenotin} AND {$posts}.post_status IN ({$posttypesearch}){$idquery} ) {$orderby} {$limitquery}) ";
			}
		}
		self::WriteDebugInfo("catsquery",$curr_settings,array($catsquery));
//		$query = mysql_escape_string($query);
		if(count($arrsearchtitle) > 0)
		{
			$ret = $wpdb->query($wpdb->prepare($query,$arrsearchtitle));
		}else{
			$ret = $wpdb->query($query);
		}
		if($bdebugmode)
		{
			self::WriteDebugInfo("3 after first query ".__LINE__,$curr_settings);
		}
		
		$LIMIT-= 1;
		if ( is_wp_error($ret) ) {
			return new WP_Error( 'db_query_error', 
				__( 'Could not execute query' ), $wpdb->last_error );
		} 

		$query = "SELECT MIN(ID) as minid, MAX(ID) as maxid FROM {$temptable} LIMIT {$LIMIT}";
		$ret = $wpdb->get_results($query);
		if ( is_wp_error($ret) ) {
			return new WP_Error( 'db_query_error', 
				__( 'Could not execute query' ), $wpdb->last_error );
		} 
		$minid = $ret[0]->minid;
		$maxid = $ret[0]->maxid;
		$query = "SELECT COUNT(ID) FROM {$temptable}";
		$ret = $wpdb->get_var($query);
		if ( is_wp_error($ret) ) {
			return new WP_Error( 'db_query_error', 
				__( 'Could not execute query' ), $wpdb->last_error );
		} 
		$total = (int)$ret;
		$bdontcheckforparent = false;
		if((int)$ret > $LIMIT)
		{
			$hasnext = true;
			if($minid !== NULL && $maxid !== NULL)
			{
//				$idlimitquery = $idlimitquery = " AND p1.ID > {$minid} AND p1.ID < {$maxid}";//$p1idquery;
				if($ispagination)
				{
					if($isnext && $maxused !== "")
					{
						$idlimitquery = " AND p1.ID < {$maxused}"; //AND p1.ID < {$maxid}";//$p1idquery;
					}else
					{
						if($minused == "")
							$idlimitquery = " AND p1.ID > {$minused}";//$p1idquery;
					}
				}
				
			}
				
			if(!$bgettotalnumber)
			{
				$total = -1;
				$bdontcheckforparent = true;
			}
		}
		if(!$bgettotalnumber)
				$total = -1;
		if(!$bgettotalnumber)
		{
			$limitquery = " LIMIT {$LIMIT}";
		}else
		{
			$idlimitquery = "";
		}

		$attrsquery = "";
		if(count($attrparams) > 0)
		{
			$attrsquery = " INNER JOIN {$meta} ON p1.ID={$meta}.post_id AND ";
			$bfirst = true;
			foreach($attrparams as $attrparam)
			{
				if($bfirst)
				{
					$bfirst = false;
					$attrsquery.= "(({$meta}.meta_key='attribute_pa_".sanitize_title($attrparam['attr'])."' AND {$meta}.meta_value='".$attrparam['value']."')";
				}else
				{
					$attrsquery.= " OR ({$meta}.meta_key='attribute_pa_".sanitize_title($attrparam['attr'])."' AND {$meta}.meta_value='".$attrparam['value']."')";
				}
			}
			$attrsquery.= ")";
		}
		
		$query ="INSERT INTO {$temptable}(
			SELECT p1.ID, 1 AS type,p1.post_parent,0 as useit 
			FROM {$posts} p1{$attrsquery}
			WHERE (p1.post_parent IN (SELECT ID FROM {$temptable}))
			AND (p1.post_type='product_variation'){$idlimitquery} ORDER BY p1.ID DESC {$limitquery})";
		if($bdontcheckforparent)
		{
			$query ="INSERT INTO {$temptable}(
			SELECT p1.ID, 1 AS type,p1.post_parent,0 as useit 
			FROM {$posts} p1{$attrsquery}
			WHERE (p1.post_type='product_variation'){$idlimitquery} ORDER BY p1.ID {$sortquery} {$limitquery})";
		}
		
		if($skuquery != "" && $bgetvariations)
		{
			$skuquery = " INNER JOIN {$meta} meta ON p1.ID=meta.post_id
			AND CASE WHEN meta.meta_key='_sku' THEN meta.meta_value";
			switch($skuparam['value']){
				case "con":
				{
					if($ismultiple)
					{
						$searchstring = $skuparam['title'];
						$searchstring = trim($searchstring);
						if($searchstring == "") break;
						$arrstrings = explode(',',$searchstring);
						
						if(count($arrstrings) > 1)
						{
							$counter = 0;
							foreach($arrstrings as $arrstring)
							{
								$arrstring = trim($arrstring);
								if($arrstring == "") continue;
								$counter++;
								if($counter === 1)
								{
									$skuquery.= " LIKE '%".$arrstring."%'";
									continue;
								}
								$skuquery.= " OR meta.meta_value LIKE '%".$arrstring."%'";
							}
						}
					}else
					{
						$skuquery.= " LIKE '%".$skuparam['title']."%'";
					}
				}
				break;
				case "isexactly":
				{
					if($ismultiple)
					{
						$searchstring = $skuparam['title'];
						$searchstring = trim($searchstring);
						if($searchstring == "") break;
						$arrstrings = explode(',',$searchstring);
						
						if(count($arrstrings) > 1)
						{
							$counter = 0;
							foreach($arrstrings as $arrstring)
							{
								$arrstring = trim($arrstring);
								if($arrstring == "") continue;
								$counter++;
								if($counter === 1)
								{
									$skuquery.= " = '".$arrstring."'";
									continue;
								}
								$skuquery.= " OR meta.meta_value = '".$arrstring."'";
							}
						}
					}else
					{
						$skuquery.= " = '".$skuparam['title']."'";
					}
					
				}
				break;
				case "notcon":
				{
					if($ismultiple)
					{
						$searchstring = $skuparam['title'];
						$searchstring = trim($searchstring);
						if($searchstring == "") break;
						$arrstrings = explode(',',$searchstring);
						
						if(count($arrstrings) > 1)
						{
							$counter = 0;
							foreach($arrstrings as $arrstring)
							{
								$arrstring = trim($arrstring);
								if($arrstring == "") continue;
								$counter++;
								if($counter === 1)
								{
									$skuquery.= " NOT LIKE '%".$arrstring."%'";
									continue;
								}
								$skuquery.= " AND meta.meta_value NOT LIKE '%".$arrstring."%'";
							}
						}
					}else
					{
						$skuquery.= " NOT LIKE '%".$skuparam['title']."%'";
					}
					
				}
				break;
				case "start":
				{
					if($ismultiple)
					{
						$searchstring = $skuparam['title'];
						$searchstring = trim($searchstring);
						if($searchstring == "") break;
						$arrstrings = explode(',',$searchstring);
						
						if(count($arrstrings) > 1)
						{
							$counter = 0;
							foreach($arrstrings as $arrstring)
							{
								$arrstring = trim($arrstring);
								if($arrstring == "") continue;
								$counter++;
								if($counter === 1)
								{
									$skuquery.= " LIKE '".$arrstring."%'";
									continue;
								}
								$skuquery.= " OR meta.meta_value LIKE '".$arrstring."%'";
							}
						}
					}else
					{
						$skuquery.= " LIKE '".$skuparam['title']."%'";
					}
					
				}
				break;
				case "end":
				{
					if($ismultiple)
					{
						$searchstring = $skuparam['title'];
						$searchstring = trim($searchstring);
						if($searchstring == "") break;
						$arrstrings = explode(',',$searchstring);
						
						if(count($arrstrings) > 1)
						{
							$counter = 0;
							foreach($arrstrings as $arrstring)
							{
								$arrstring = trim($arrstring);
								if($arrstring == "") continue;
								$counter++;
								if($counter === 1)
								{
									$skuquery.= " LIKE '%".$arrstring."'";
									continue;
								}
								$skuquery.= " OR meta.meta_value LIKE '%".$arrstring."'";
							}
						}
					}else
					{
						$skuquery.= " LIKE '%".$skuparam['title']."'";
					}
					
				}
				break;
				default:
					break;
			}
			$skuquery.= ' END ';
			//get all variations of parent
			{
				$query ="INSERT INTO {$temptable}(
				SELECT p1.ID, 1 AS type,p1.post_parent,0 as useit 
				FROM {$posts} p1
				WHERE (p1.post_parent IN (SELECT ID FROM {$temptable})) AND p1.ID NOT IN (SELECT ID FROM {$temptable})
				AND (p1.post_type='product_variation'){$idlimitquery} ORDER BY p1.ID DESC {$limitquery})";
				$ret = $wpdb->query($query);
				if ( is_wp_error($ret) ) {
						return new WP_Error( 'db_query_error', 
							__( 'Could not execute query' ), $wpdb->last_error );
					} 
			}
			if($attrsquery != "")
			{
				{
					$query ="INSERT INTO {$temptable}(
					SELECT p1.ID, 1 AS type,p1.post_parent,0 AS useit
					FROM {$posts} p1{$attrsquery}{$skuquery}
					WHERE (p1.post_type='product_variation'){$idlimitquery} ORDER BY p1.ID DESC {$limitquery}
					)";
					$ret = $wpdb->query($query);
					if ( is_wp_error($ret) ) {
						return new WP_Error( 'db_query_error', 
						__( 'Could not execute query' ), $wpdb->last_error );
					} 
					$query ="INSERT INTO {$temptable}(
						SELECT p1.ID, 0 AS type,0 AS post_parent, 0 AS useit
						FROM {$posts} p1
						WHERE p1.ID IN (SELECT post_parent FROM {$temptable} WHERE type=1) AND (p1.post_type='product') AND (p1.post_status IN ('publish','draft','private','pending')) {$idlimitquery} AND p1.ID NOT IN (SELECT ID FROM {$temptable}) ORDER BY p1.ID DESC {$limitquery})";
				}
			}else
			{
				$query ="INSERT INTO {$temptable}(
					SELECT p1.ID, 1 AS type,p1.post_parent ,0 AS useit
					FROM {$posts} p1{$attrsquery}{$skuquery}
					WHERE p1.post_type='product_variation'{$idlimitquery} ORDER BY p1.ID DESC {$limitquery})";
				$ret = $wpdb->query($query);
				if ( is_wp_error($ret) ) {
					return new WP_Error( 'db_query_error', 
					__( 'Could not execute query' ), $wpdb->last_error );
				} 
				$query ="INSERT INTO {$temptable}(
					SELECT p1.ID, 0 AS type,0 AS post_parent, 0 AS useit
					FROM {$posts} p1
					WHERE p1.ID IN (SELECT post_parent FROM {$temptable} WHERE type=1) AND (p1.post_type='product') AND (p1.post_status IN ('publish','draft','private','pending')) {$idlimitquery} AND p1.ID NOT IN (SELECT ID FROM {$temptable}) ORDER BY p1.ID DESC {$limitquery})";
				
			}
			
		}
		
		$wherenotin = "";
		$catsquery = "";
		if($bgetvariations)
		{
			foreach($custsearchparam as $custitem)
			{
				if(isset($custitem['id']) && $custitem['id'] === 'post_author')
				{
					continue;
				}
				if(isset($custitem['type']) && ($custitem['type'] === 'custom' || $custitem['type'] === 'customh'))
				{
					if(isset($custitem['array']) && is_array($custitem['array']))
					{
						if(in_array('none',$custitem['array'] ))
						{
							foreach($custitem['array'] as $custarritem)
							{
								if($custarritem === "none") continue;
								if($wherenotin === "")
								{
									$wherenotin = $custarritem;
								}else
								{
									$wherenotin.= ','. $custarritem;
								}
							}
							continue;
						}
						foreach($custitem['array'] as $custarritem)
						{
							if($catsquery === "")
							{
								$catsquery = $custarritem;
							}else
							{
								$catsquery.= ','. $custarritem;
							}
						}
					}
				}
			}
		}
		
		
		
		if($wherenotin !== "" || $catsquery !== "")
		{
			if($wherenotin !== "")
			{
				$wherenotin = " AND	p1.ID NOT IN (SELECT {$term}.object_id FROM {$term} WHERE {$term}.term_taxonomy_id IN (".$wherenotin."))";
			}
			if($catsquery !== "")
			{
				$catsquery = " AND	p1.ID IN (SELECT {$term}.object_id FROM {$term} WHERE {$term}.term_taxonomy_id IN (".$catsquery."))";//"INNER JOIN {$term} rel ON {$posts}.ID=rel.object_id AND rel.term_taxonomy_id IN (".$catsquery.")";
			}
			$query ="INSERT INTO {$temptable}(
					SELECT p1.ID, 1 AS type,p1.post_parent ,0 AS useit
					FROM {$posts} p1{$attrsquery}{$custommetasearch}
					WHERE p1.post_type='product_variation'{$idlimitquery}{$wherenotin}{$catsquery}  AND p1.ID NOT IN (SELECT ID FROM {$temptable}) ORDER BY p1.ID ASC {$limitquery})";
				$ret = $wpdb->query($query);
				if ( is_wp_error($ret) ) {
					return new WP_Error( 'db_query_error', 
						__( 'Could not execute query' ), $wpdb->last_error );
				} 
				if($bdebugmode)
				{
					self::WriteDebugInfo("4 after sec query 1260".__LINE__,$curr_settings);
				}
				$query ="INSERT INTO {$temptable}(
					SELECT p1.ID, 0 AS type,0 AS post_parent, 0 AS useit
					FROM {$posts} p1
					WHERE p1.ID IN (SELECT post_parent FROM {$temptable} WHERE type=1) AND (p1.post_type='product') AND (p1.post_status IN ('publish','draft','private','pending')){$idlimitquery} ORDER BY p1.ID ASC {$limitquery})";
		}	
		
		if(($custommetasearch !== "" || $hasattribute) && $bgetvariations)
		{
			$innercounter = 5;
			$custommetasearch = "";
			foreach($custsearchparam as $custitem)
			{
				if(isset($custitem['type']) && ($custitem['type'] !== 'custom' && $custitem['type'] !== 'customh'))
				{
					if($custitem['type'] === "date")
						continue;
					$innercounter++;
					if($custitem['type'] === 'attribute')
					{
						if($categoryor)
						{
							$custommetasearch.= " INNER JOIN {$meta} meta{$innercounter} ON p1.ID=meta{$innercounter}.post_id
						AND CASE WHEN meta{$innercounter}.meta_key='attribute_pa_{$custitem['title']['attr']}' THEN meta{$innercounter}.meta_value='{$custitem['title']['value']}' END ";		
						}
						continue;
					}
					if(isset($custitem['title']) && isset($custitem['value']))
					{
						if($custitem['id'] == "ID" || $custitem['id'] == "_stock" || $custitem['id'] == "_stock_status")
						{
							if($custitem['id'] == "_stock")
							{
								if(!is_numeric($custitem['title']))
									continue;
							}
							if($custitem['id'] == "ID")
							{
								continue;
							}
							$custommetasearch.= " INNER JOIN {$meta} meta{$innercounter} ON p1.ID=meta{$innercounter}.post_id
							AND CASE WHEN meta{$innercounter}.meta_key='{$custitem['id']}' THEN meta{$innercounter}.meta_value";
							if($custitem['id'] == "_stock")
							{
								if($custitem['value'] == 'more')
								{
									$custommetasearch.= ' > ';
								}else if($custitem['value'] == 'less')
								{
									$custommetasearch.= ' < ';
								}else if($custitem['value'] == 'equal')
								{
									$custommetasearch.= ' = ';
								}else if($custitem['value'] == 'moree')
								{
									$custommetasearch.= ' >= ';
								}else
								{//lesse
									$custommetasearch.= ' <= ';
								}
								$custommetasearch.= $custitem['title'].' END ';
							}else
							{
								if(!self::$isversion3)
								{
									if($custitem['title'] == "outofstock")
										$custommetasearch.= " LIKE 'outofstock' END ";
									else
										$custommetasearch.= " NOT LIKE 'outofstock' END ";
								}
							}
//							$innercounter++;
							continue;
						}
						if( $custitem['type'] === 'integer' ||  $custitem['type'] === 'decimal' ||  $custitem['type'] === 'decimal3')
						{
							if(!is_numeric($custitem['title']))
								continue;
						}
						$custommetasearch.= " INNER JOIN {$meta} meta{$innercounter} ON p1.ID=meta{$innercounter}.post_id
							AND CASE WHEN meta{$innercounter}.meta_key='{$custitem['id']}' THEN meta{$innercounter}.meta_value";
						if($custitem['type'] === 'integer' ||  $custitem['type'] === 'decimal' ||  $custitem['type'] === 'decimal3')
						{
							if($custitem['value'] == 'more')
							{
								$custommetasearch.= ' > ';
							}else if($custitem['value'] == 'less')
							{
								$custommetasearch.= ' < ';
							}else if($custitem['value'] == 'equal')
							{
								$custommetasearch.= ' = ';
							}else if($custitem['value'] == 'moree')
							{
								$custommetasearch.= ' >= ';
							}else
							{//lesse
								$custommetasearch.= ' <= ';
							}
							$custommetasearch.= $custitem['title'].' END ';
							
						}else
						{
							switch($custitem['value']){
								case "con":
								{
									$custommetasearch.= " LIKE '%".$custitem['title']."%' ";
								}
								break;
								case "notcon":
								{
									$custommetasearch.= " NOT LIKE '%".$custitem['title']."%' ";
								}
								break;
								case "start":
								{
									$custommetasearch.= " LIKE '".$custitem['title']."%' ";
								}
								break;
								case "end":
								{
									$custommetasearch.= " LIKE '%".$custitem['title']."' ";
								}
								break;
								default:
									break;
							}
							$custommetasearch.= ' END ';
						}
//						$innercounter++;
					}
					
				}
			}
			$skipquery = false;
			if(!$categoryor && $hasattribute && $bgetvariations)	
			{
				$attrsquery = " INNER JOIN {$meta} ON p1.ID={$meta}.post_id AND ";
				$bfirst = true;
				foreach($custsearchparam as $custitem)
				{
					if(isset($custitem['type']) && $custitem['type'] === 'attribute')
					{
						if($bfirst)
						{
//							$bfirst = false;
//							AND CASE WHEN meta{$innercounter}.meta_key='{$custitem['id']}' THEN meta{$innercounter}.meta_value
							$attrsquery = " INNER JOIN {$meta} ON p1.ID={$meta}.post_id AND ";
							$attrsquery.= "({$meta}.meta_key='attribute_pa_".sanitize_title($custitem['title']['attr'])."' AND {$meta}.meta_value='".$custitem['title']['value']."')";
//							$attrsquery.= "( CASE WHEN {$meta}.meta_key='attribute_pa_".$custitem['title']['attr']."' THEN {$meta}.meta_value='".$custitem['title']['value']."' ";
						}else
						{
							$attrsquery.= " OR ({$meta}.meta_key='attribute_pa_".sanitize_title($custitem['title']['attr'])."' AND {$meta}.meta_value='".$custitem['title']['value']."')";
//							$attrsquery.= " WHEN {$meta}.meta_key='attribute_pa_".$custitem['title']['attr']."' THEN {$meta}.meta_value='".$custitem['title']['value']."' ";
						}
						{
							$skipquery = true;
							$notin = "NOT";
							$id = "p1.ID";
							if($skuquery !== "" || $titlelike !== "" || $desclike !== "" || $shortdesclike !== "")
							{
								$notin = "";
								$id = "p1.post_parent";
							}
							$query ="INSERT INTO {$temptable}(
							SELECT p1.ID, 1 AS type,p1.post_parent,0 AS useit
							FROM {$posts} p1{$attrsquery}{$custommetasearch}
							WHERE (p1.post_type='product_variation'){$idlimitquery}  AND {$id} {$notin} IN (SELECT ID FROM {$temptable}) ORDER BY p1.ID ASC {$limitquery})";
							$ret = $wpdb->query($query);
							if ( is_wp_error($ret) ) {
								return new WP_Error( 'db_query_error', 
									__( 'Could not execute query' ), $wpdb->last_error );
							} 
							if($bdebugmode)
							{
								self::WriteDebugInfo("7 after attr query ".__LINE__,$curr_settings);
							}
							$query ="INSERT INTO {$temptable}(
								SELECT p1.ID, 0 AS type,0 AS post_parent, 0 AS useit
								FROM {$posts} p1
								WHERE p1.ID IN (SELECT post_parent FROM {$temptable} WHERE type=1) AND (p1.post_type='product') AND (p1.post_status IN ('publish','draft','private','pending')){$idlimitquery} ORDER BY p1.ID ASC {$limitquery})";
						}
					}
				}
				$attrsquery.= ")";
//				$attrsquery = "";
			}	
			
			if($attrsquery != "")
			{
				if(!$skipquery)
				{
					$notin = "NOT";
					$id = "p1.ID";
					if($skuquery !== "" || $titlelike !== "" || $desclike !== "" || $shortdesclike !== "")
					{
//						$notin = "";
//						$id = "p1.post_parent";
					}
					$query ="INSERT INTO {$temptable}(
					SELECT p1.ID, 1 AS type,p1.post_parent,0 AS useit
					FROM {$posts} p1{$attrsquery}{$custommetasearch}
					WHERE (p1.post_type='product_variation'){$idlimitquery}  AND {$id} {$notin} IN (SELECT ID FROM {$temptable}) ORDER BY p1.ID ASC {$limitquery})";
					$ret = $wpdb->query($query);
					if ( is_wp_error($ret) ) {
						return new WP_Error( 'db_query_error', 
							__( 'Could not execute query' ), $wpdb->last_error );
					} 
					if($bdebugmode)
					{
						self::WriteDebugInfo("7 after attr query ".__LINE__,$curr_settings);
					}
					$query ="INSERT INTO {$temptable}(
						SELECT p1.ID, 0 AS type,0 AS post_parent, 0 AS useit
						FROM {$posts} p1
						WHERE p1.ID IN (SELECT post_parent FROM {$temptable} WHERE type=1) AND (p1.post_type='product') AND (p1.post_status IN ('publish','draft','private','pending')){$idlimitquery} ORDER BY p1.ID ASC {$limitquery})";
				}
			}else
			{
				$notin = "NOT";
				$id = "p1.ID";
				if($categoryor && (count($catparams) > 0  || count($tagsparams) > 0 || $hascustomtax))
				{
					$notin = "";
					$id = "p1.post_parent";
				}
				if($skuquery !== "" || $titlelike !== "" || $desclike !== "" || $shortdesclike !== "")
				{
//					$notin = "";
//					$id = "p1.post_parent";
				}
				$query ="INSERT INTO {$temptable}(
					SELECT p1.ID, 1 AS type,p1.post_parent ,0 AS useit
					FROM {$posts} p1{$attrsquery}{$custommetasearch}
					WHERE p1.post_type='product_variation'{$idlimitquery}  AND {$id} {$notin} IN (SELECT ID FROM {$temptable}) ORDER BY p1.ID ASC {$limitquery})";
				$ret = $wpdb->query($query);
				if($bdebugmode)
				{
					self::WriteDebugInfo("8 after attr query ".__LINE__,$curr_settings);
				}
				if ( is_wp_error($ret) ) {
					return new WP_Error( 'db_query_error', 
						__( 'Could not execute query' ), $wpdb->last_error );
				} 
				$query ="INSERT INTO {$temptable}(
					SELECT p1.ID, 0 AS type,0 AS post_parent, 0 AS useit
					FROM {$posts} p1
					WHERE p1.ID IN (SELECT post_parent FROM {$temptable} WHERE type=1) AND (p1.post_type='product') AND (p1.post_status IN ('publish','draft','private','pending')){$idlimitquery} ORDER BY p1.ID ASC {$limitquery})";
				
			}
		}
		
		if($bgetvariations)
		{
			$ret = $wpdb->query($query);
			if($bdebugmode)
			{
				self::WriteDebugInfo("9 after attr query ".__LINE__,$curr_settings);
			}
			if ( is_wp_error($ret) ) {
					return new WP_Error( 'db_query_error', 
						__( 'Could not execute query' ), $wpdb->last_error );
				} 
			if(($bgetallvars && $hasattribute) || ($bgetallvarstaxonomies && $hascustomtax))// || $skuquery !== "")
			{
				$query ="INSERT INTO {$temptable}(
				SELECT p1.ID, 1 AS type,p1.post_parent,0 as useit 
				FROM {$posts} p1
				WHERE (p1.post_parent IN (SELECT ID FROM {$temptable})) AND p1.ID NOT IN (SELECT ID FROM {$temptable})
				AND (p1.post_type='product_variation'){$idlimitquery} ORDER BY p1.ID DESC {$limitquery})";
				$ret = $wpdb->query($query);
				if ( is_wp_error($ret) ) {
						return new WP_Error( 'db_query_error', 
							__( 'Could not execute query' ), $wpdb->last_error );
					} 
				if($bdebugmode)
				{
					self::WriteDebugInfo("10 after query ".__LINE__,$curr_settings);
				}
			}
		}
		
		
		//////////////////////////////////////////////////
		//and search qieries//////////////////////////////
		//////////////////////////////////////////////////
		
		if($bgettotalnumber)
		{
			$query ="SELECT count(DISTINCT ID) 
					FROM {$temptable}";
			$total = $wpdb->get_var($query);
			if($total == NULL) $total = -1;
		}
		
		$useit = "";
		$query ="UPDATE {$temptable} SET useit=1 ORDER BY ID DESC LIMIT {$LIMIT}";
		if($ispagination)
		{
			$query ="UPDATE {$temptable} SET useit=1 WHERE 1{$idquery} ORDER BY ID{$sortquery} LIMIT {$LIMIT}";
		}
		$ret = $wpdb->query($query);
		if ( is_wp_error($ret) ) {
			return new WP_Error( 'db_query_error', 
				__( 'Could not execute query' ), $wpdb->last_error );
		}
		if($bdebugmode)
		{
			self::WriteDebugInfo("11 after query ".__LINE__,$curr_settings);
		}
		$useit =  " WHERE {$temptable}.useit=1"; 
//		if($total < $LIMIT)
		{//check and added variations
		
			$query ="SELECT MIN(ID) as maxid FROM {$temptable}";
			if($ispagination)
			{
				if(!$isnext)
					$query ="SELECT MAX(ID) as maxid FROM {$temptable}";
			}
		
			$ret = $wpdb->get_var($query);
			if ( is_wp_error($ret) ) {
				return new WP_Error( 'db_query_error', 
					__( 'Could not execute query' ), $wpdb->last_error );
			} 
			if($ret === NULL)
			{
				$hasnext = false;
				return;
			}
			$query ="SELECT useit FROM {$temptable} WHERE ID={$ret}";
			$ret = $wpdb->get_var($query);
			if($ret == 0)
			{
				$hasnext = true;
			}
			else
			{
				$hasnext = false;
			}
		}

				
		$ret = $wpdb->query($query);
		if ( is_wp_error($ret) ) {
				return new WP_Error( 'db_query_error', 
					__( 'Could not execute query' ), $wpdb->last_error );
			} 
		if($bdebugmode)
		{
			self::WriteDebugInfo("12 after query ".__LINE__,$curr_settings);
		}
		$sqlfields = self::PrepareQuery('wp_posts');
		if($sqlfields !== "")
			$sqlfields.= ",";
		$query = "SELECT CASE WHEN p1.post_parent = 0 THEN p1.ID ELSE p1.post_parent END AS Sort,
			{$sqlfields}p1.ID,p1.post_parent,post_type
			FROM {$posts} p1
			WHERE p1.ID IN (SELECT ID FROM {$temptable}{$useit})
			ORDER BY Sort DESC LIMIT {$LIMIT}";
		$info = $wpdb->get_results($query);
}
		
			self::WriteDebugInfo("12 after 1 get_results ".__LINE__,$curr_settings);
		
		if($arrduplicate !== null)
		{
			$info = $arrduplicate;
		}
		$infodel = array();
		if(isset($_POST['_iswpmlenabled']))
		{
//			 if(ICL_LANGUAGE_CODE != 'all')
			 {
				for($i = 0; $i < count($info); ++$i) 
				{
					if($info[$i]->post_type === 'product')
					{
						$idret = self::lang_object_id((int)$info[$i]->ID);
						if($idret === null || $idret != ((int)$info[$i]->ID))
						{
							$infodel[] = $info[$i]->ID;
							array_splice($info,$i,1);
							if(count($info) > 0 || $i !== 0)
								$i--;
						}
					}
				}
				for($i = 0; $i < count($info); ++$i) 
				{
					if($info[$i]->post_type === 'product_variation')
					{
						if(in_array($info[$i]->post_parent,$infodel))
						{
							array_splice($info,$i,1);
							if(count($info) > 0 || $i !== 0)
								$i--;
						}
					}
				}
			}
		}
		$ids = array();
		
		for($i = 0; $i < count($info); ++$i) 
		{
			$ids[$info[$i]->ID] =&$info[$i];
		}

			self::WriteDebugInfo("12.1 after array map ".__LINE__,$curr_settings);
		$blogusers = array();
		if(in_array('post_author',self::$columns) || empty(self::$columns))
		{
			$blogusers = get_users( array( 'role' => 'vendor', 'fields' => array( 'ID', 'display_name' ) ));
			$blogusers1 = get_users( array( 'role' => 'administrator', 'fields' => array( 'ID', 'display_name' ) ));
			$blogusers = array_merge($blogusers,$blogusers1);
			$blogusers1 = get_users( array( 'role' => 'shop_manager', 'fields' => array( 'ID', 'display_name' ) ));
			$blogusers = array_merge($blogusers,$blogusers1);
			$blogusers1 = get_users( array( 'role' => 'seller', 'fields' => array( 'ID', 'display_name' ) ));
			$blogusers = array_merge($blogusers,$blogusers1);
		}
		foreach($ids as &$id)
		{
			if($id->post_parent != 0 && $id->post_type == 'product_variation')
			{
				if(property_exists($id,'post_title'))
				{
					$id->post_title = ' [#'.$id->post_parent.' var]';
					if(array_key_exists($id->post_parent,$ids))
					{
						$obj = $ids[$id->post_parent];
						$obj->haschildren = true;
						$partitle = $obj->post_title;
						if(function_exists('mb_strlen') && function_exists('mb_substr'))
						{
							if(mb_strlen($partitle) > 15)
							{
								$partitle = mb_substr($partitle,0,15) . '...';
							}
						}else
						{
							if(strlen($partitle) > 15)
							{
								$partitle = substr($partitle,0,15) . '...';
							}
							
						}
						$partitle = str_replace("<", "&lt;", $partitle);
						$id->post_title = $partitle.' [#'.$id->post_parent.' var]';
	//					$var = new WC_Product_Variation($id->ID);
	//					$id->post_title = $var->get_formatted_name().' (Var. of #'.$id->post_parent.')';
					}
				}
				$id->comment_status = 'no';
				$id->post_name = '';
				$id->post_date = '';
			}else
			{
				if(property_exists($id,'comment_status'))
				{
					if($id->comment_status === 'open')
						$id->comment_status = 'yes';
					else
						$id->comment_status = 'no';
				}
				if(in_array('_product_permalink',self::$columns))
				{
					$id->_product_permalink = '';
					$permalink = get_permalink($id->ID);
					if(false !== $permalink)
					{
						$id->_product_permalink = $permalink;
					}
				}
				
			}
//			if(property_exists($id,'post_title'))
//			{
//				$id->post_title = str_replace("<", "&lt;", $id->post_title);
//				$id->post_excerpt = str_replace(chr(194),"", $id->post_excerpt);
//				$id->post_excerpt = str_replace(chr(160)," ", $id->post_excerpt);
//			}
			if(property_exists($id,'post_author'))
			{
				if($id->post_type === 'product_variation')
				{
					$id->post_author = '';
				}else if($id->post_type === 'product')
				{
					foreach ( $blogusers as $user ) 
					{
						if($id->post_author === (string)$user->ID)
						{
							$id->post_author_ids = (string)$user->ID;
							$id->post_author = $user->display_name;
							break;
						}
					}
				}
				
			}
			if(property_exists($id,'post_excerpt'))
			{
				$id->post_excerpt = str_replace("\r\n", "\n", $id->post_excerpt);
//				$id->post_excerpt = str_replace(chr(194),"", $id->post_excerpt);
//				$id->post_excerpt = str_replace(chr(160)," ", $id->post_excerpt);
			}
			if(property_exists($id,'post_content'))
			{
				$id->post_content = str_replace("\r\n", "\n", $id->post_content);
//				$id->post_content = str_replace(chr(194),"", $id->post_content);
//				$id->post_content = str_replace(chr(160)," ", $id->post_content);
			}
		}
		
			self::WriteDebugInfo("12.2 after array loop ".__LINE__,$curr_settings);

		$customfields = "";
		if($customparam !== NULL)
		{
			foreach($customparam as $value)
			{
				$customfields.= ",'" . esc_attr($value) . "'";
			}
			
		}
		$metavals = array();
		
		
			self::WriteDebugInfo("12.3 after customfields loop ".__LINE__,$curr_settings);
	$duplicateids = "";
	if($arrduplicate !== null)
	{
		foreach($arrduplicate as $key => $value)
		{
			if($duplicateids == "")
				$duplicateids.= $value->ID;
			else
				$duplicateids.= ",".$value->ID;
		}	
	}
	
	$converttoutf8 = true;
	if(is_array($curr_settings))
	{
		if(isset($curr_settings['converttoutf8']))
		{
			if($curr_settings['converttoutf8'] == 0)
				$converttoutf8 = false;
		}
	}
	if(!function_exists('mb_convert_encoding'))
	{
		$converttoutf8 = false;
	}
		
		
	$sqlfields = self::PrepareQuery('wp_meta1');
	if($sqlfields !== "")
	{
		if($arrduplicate === null)
		{
			$query ="SELECT p1.ID, p1.post_title,p1.post_parent, {$meta}.meta_key, {$meta}.meta_value
				FROM {$posts} p1
				INNER JOIN {$meta} ON p1.ID={$meta}.post_id 
				AND ({$meta}.meta_key IN ({$sqlfields})) WHERE p1.ID IN (SELECT ID FROM {$temptable}{$useit})";
			$metavals =  $wpdb->get_results($query);
			if ( is_wp_error($metavals) ) {
				return new WP_Error( 'db_query_error', 
					__( 'Could not execute query' ), $wpdb->last_error );
			} 
			
			
				self::WriteDebugInfo("13.1 after meta get_results ".__LINE__,$curr_settings);
			
		}else
		{
			$query ="SELECT p1.ID, p1.post_title,p1.post_parent, {$meta}.meta_key, {$meta}.meta_value
				FROM {$posts} p1
				INNER JOIN {$meta} ON p1.ID={$meta}.post_id 
				AND ({$meta}.meta_key IN ({$sqlfields}))	WHERE p1.ID IN ({$duplicateids})";
			$metavals =  $wpdb->get_results($query);
			
				self::WriteDebugInfo("14.1 after meta2 get_results ".__LINE__,$curr_settings);

			if ( is_wp_error($metavals) ) {
				return new WP_Error( 'db_query_error', 
					__( 'Could not execute query' ), $wpdb->last_error );
					
			} 
		}
		self::LoopMetaData($metavals,$ids,$tax_classes,$converttoutf8);
	}
			
	
	
		
	
	
	$sqlfields = self::PrepareQuery('wp_meta2');
	if($sqlfields !== "")
	{
		if($arrduplicate === null)
		{
			$query ="SELECT p1.ID, p1.post_title,p1.post_parent, {$meta}.meta_key, {$meta}.meta_value
				FROM {$posts} p1
				INNER JOIN {$meta} ON p1.ID={$meta}.post_id 
				AND ({$meta}.meta_key IN ({$sqlfields})) WHERE p1.ID IN (SELECT ID FROM {$temptable}{$useit})";
			$metavals =  $wpdb->get_results($query);
			if ( is_wp_error($metavals) ) {
				return new WP_Error( 'db_query_error', 
					__( 'Could not execute query' ), $wpdb->last_error );
			} 
			
				self::WriteDebugInfo("13.2 after meta get_results ".__LINE__,$curr_settings);
			
		}else
																{
			$query ="SELECT p1.ID, p1.post_title,p1.post_parent, {$meta}.meta_key, {$meta}.meta_value
				FROM {$posts} p1
				INNER JOIN {$meta} ON p1.ID={$meta}.post_id 
				AND ({$meta}.meta_key IN ({$sqlfields}))	WHERE p1.ID IN ({$duplicateids})";
			$metavals =  $wpdb->get_results($query);
			
				self::WriteDebugInfo("14.2 after meta2 get_results ".__LINE__,$curr_settings);

			if ( is_wp_error($metavals) ) {
				return new WP_Error( 'db_query_error', 
					__( 'Could not execute query' ), $wpdb->last_error );
				
		} 
	}
		
		self::LoopMetaData($metavals,$ids,$tax_classes,$converttoutf8);
	}
//second query to reduce ram usage
	$sqlfields = self::PrepareQuery('wp_meta3',$customparam);
	if($sqlfields !== "")
	{
		if($arrduplicate === null)
		{
			$query ="SELECT p1.ID, p1.post_title,p1.post_parent, {$meta}.meta_key, {$meta}.meta_value
				FROM {$posts} p1
				INNER JOIN {$meta} ON p1.ID={$meta}.post_id 
				AND ({$meta}.meta_key IN ({$sqlfields})
				OR {$meta}.meta_key LIKE 'attribute_%')
				WHERE p1.ID IN (SELECT ID FROM {$temptable}{$useit})";
			$metavals =  $wpdb->get_results($query);
			if ( is_wp_error($metavals) ) {
				return new WP_Error( 'db_query_error', 
					__( 'Could not execute query' ), $wpdb->last_error );
			} 
			
				self::WriteDebugInfo("15 after meta2 get_results ".__LINE__,$curr_settings);
			
		}else
		{
			$query ="SELECT p1.ID, p1.post_title,p1.post_parent, {$meta}.meta_key, {$meta}.meta_value
				FROM {$posts} p1
				INNER JOIN {$meta} ON p1.ID={$meta}.post_id 
				AND ({$meta}.meta_key IN ({$sqlfields})
				OR {$meta}.meta_key LIKE 'attribute_%')
				WHERE p1.ID IN ({$duplicateids})";
			$metavals =  $wpdb->get_results($query);
			
				self::WriteDebugInfo("16 after meta2-1 get_results ".__LINE__,$curr_settings);

			if ( is_wp_error($metavals) ) {
				return new WP_Error( 'db_query_error', 
					__( 'Could not execute query' ), $wpdb->last_error );
					
			} 
		}
	}
		self::LoopMetaData($metavals,$ids,$tax_classes,$converttoutf8);
		self::WriteDebugInfo("16.5 after loop meta ".__LINE__,$curr_settings);
		unset($metavals);
		$thumbids = "";
		$thumbcounter = 0;
		$thumbsidmap = array();
		$gal_thumbids = "";
		$gal_thumbcounter = 0;
		$gal_thumbsidmap = array();
		$upload_dir = wp_upload_dir();
		if(is_array($upload_dir) && isset($upload_dir['baseurl']))
			$upload_dir = $upload_dir['baseurl'];
		else
			$upload_dir = "";
		
		
		$sel_fields = get_option('w3exabe_custom');
		$hasproductdelivery = false;
		$hasproductsales = false;
		if(is_array($sel_fields) && !empty($sel_fields))
		{
			foreach($sel_fields as $i => $innerarray)
			{
				if(isset($innerarray['type']))
				{
					if($innerarray['type'] === 'customh' || $innerarray['type'] === 'custom')
					{
						if(taxonomy_exists($i))
						{
							if($i === 'product_delivery_times')
							{
								$hasproductdelivery = true;
							}
							if($i === 'product_sale_labels')
							{
								$hasproductsales = true;
							}
						}
					}
				}
				
			}
		}
		
		foreach($ids as &$id)
		{
			if($converttoutf8)
			{
				if(property_exists($id,'post_title'))
				{
					$id->post_title =  mb_convert_encoding($id->post_title, "UTF-8");
				}
				if(property_exists($id,'post_content'))
				{
					$id->post_content =	mb_convert_encoding($id->post_content, "UTF-8");
				}
				if(property_exists($id,'post_excerpt'))
				{
					$id->post_excerpt =	mb_convert_encoding($id->post_excerpt, "UTF-8");
				}
			}
			if($hasproductdelivery)
			{
				$taxdata = get_post_meta ( $id->ID, '_lieferzeit', true);
				if($taxdata !== "")
				{
					$terminf = get_term( $taxdata, 'product_delivery_times' );
					if($terminf && is_object($terminf) && property_exists($terminf,'name'))
					{
						$id->product_delivery_times  = $terminf->name; 
					}
					
					$id->product_delivery_times_ids = $taxdata;
				}
			}
			if($hasproductsales)
			{
				$taxdata = get_post_meta ( $id->ID, '_sale_label', true);
				if($taxdata !== "")
				{
					$terminf = get_term( $taxdata, 'product_sale_labels' );
					if($terminf && is_object($terminf) && property_exists($terminf,'name'))
					{
						$id->product_sale_labels  = $terminf->name; 
					}
					
					$id->product_sale_labels_ids = $taxdata;
				}
			}
//			if($id->post_parent == 0 ||  $id->post_type == 'product')
//			{
//				if(is_array($attributes) && !empty($attributes))
//				{
//					foreach($attributes as $attr)
//					{
//						if(!property_exists($id,'attribute_pa_'.$attr->name.'_visiblefp'))
//							$id->{'attribute_pa_'.$attr->name.'_visiblefp'} = 0;
//				    }
//				}
//			}
			if(!property_exists($id,'_tax_class'))
			{
				$id->_tax_class = "Standard";
			}
			if(!property_exists($id,'_tax_status') && $id->post_type == 'product')
			{
				$id->_tax_status = "Taxable";
			}
			if(property_exists($id,'_downloadable'))
			{
				if($id->_downloadable == "yes")
				{
					if(!property_exists($id,'_download_type'))
					{
						$id->_download_type = "Standard";
					}
				}
			}
			if(property_exists($id,'post_parent'))
			{
				if($id->post_parent == 0 || $id->post_type == 'product')
				{
					if(!property_exists($id,'_stock_status'))
					{
						$id->stock_status = "instock";
					}
				}
			}
			if(self::$isversion3)
			{
				$id->_featured = 'no';
				if($id->post_type == 'product')
					$id->_stock_status = 'instock';
				$id->_visibility = "Catalog/search";
			}
			if($upload_dir === "") continue;
			if(property_exists($id,'_thumbnail_id'))
			{
				if($id->_thumbnail_id != "")
				{
					if(array_key_exists($id->_thumbnail_id,$thumbsidmap))
					{
						$oldids = $thumbsidmap[$id->_thumbnail_id];
						$oldids.= ';'. (string)$id->ID;
						$thumbsidmap[$id->_thumbnail_id] = $oldids;
					}else
					{
						$thumbsidmap[$id->_thumbnail_id] = (string)$id->ID;
					}
					
					if($thumbids == "")
					{
						$thumbids = $id->_thumbnail_id;
					}else
					{
						$thumbids.= ',' . $id->_thumbnail_id;
					}
					if($thumbcounter > 100)
					{
						$query ="SELECT post_id,meta_value
						FROM  {$meta} WHERE post_id IN ({$thumbids}) AND meta_key='_wp_attachment_metadata'";
						$metathumbs =  $wpdb->get_results($query);
						if ( false === $metathumbs) {
							$thumbcounter = 0;
							$thumbids = "";
							$metathumbs = array();
						}
						foreach($metathumbs as &$thumb)
			{
				if(array_key_exists($thumb->post_id,$thumbsidmap))
				{
					$thumbidsmul = $thumbsidmap[$thumb->post_id];
					$curthumbids = explode(';',$thumbidsmul);
					foreach($curthumbids as $curthumbid)
					{
					if(array_key_exists($curthumbid,$ids))
					{
						$obj = $ids[$curthumbid];
						$allsizes = maybe_unserialize($thumb->meta_value);
						if ( $allsizes ) 
						{
							if(is_array($allsizes))
							{
								$obj->_thumbnail_id_val = "";
								if(isset($allsizes['file']))
								{
									$dirpart = $allsizes['file'];
									$lastSlash = strrpos($dirpart,"/");
									if(FALSE !== $lastSlash)
									{
										$dirpart = substr($dirpart,0,$lastSlash + 1);
									}else
									{
										$dirpart = "";
									}
									
									$obj->_thumbnail_id_val = $upload_dir.'/'.$allsizes['file'];
									$obj->_thumbnail_id_original = $allsizes['file'];
									$obj->_thumbnail_id_info = "<div class='fileinfo'>".$allsizes['file']."</div>";
									if(isset($allsizes['width']) && isset($allsizes['height']))
									{
										$obj->_thumbnail_id_info.= "<div class='dims'>".$allsizes['width']." x ".$allsizes['height']."</div>";
									}
					if(isset($allsizes['sizes'])) // && $dirpart !== ""
					{
						$sizes = $allsizes['sizes'];
						//check for thumbnail or medium size to save bandwith
							$lastheight = 0;
							$lastwidth = 0;
							foreach($sizes as $size)
							{
								if(!isset($size["file"]) || !isset($size["width"]) || !isset($size["height"]))
									continue;
								if($lastheight === 0 && $lastwidth === 0)
								{
									$lastheight = (int)$size["height"];
									$lastwidth  = (int)$size["width"];
									$obj->_thumbnail_id_val = $upload_dir.'/'.$dirpart.$size["file"];
								}else
								{
									if($size["height"] < $lastheight && $size["width"] < $lastwidth && $size["height"] >= 150 &&  $size["width"] >= 150)
									{
										$lastheight = (int)$size["height"];
										$lastwidth  = (int)$size["width"];
										$obj->_thumbnail_id_val = $upload_dir.'/'.$dirpart.$size["file"];
									}
								}
							}
				
//						if(isset($sizes["thumbnail"]) && isset($sizes["thumbnail"]["file"]))
//						{
//							$obj->_thumbnail_id_val = $upload_dir.'/'.$dirpart.$sizes["thumbnail"]["file"];
//						}else if(isset($sizes["shop_thumbnail"]) && isset($sizes["shop_thumbnail"]["file"]))
//						{
//							$obj->_thumbnail_id_val = $upload_dir.'/'.$dirpart.$sizes["shop_thumbnail"]["file"];
//						}else if(isset($sizes["medium"]) && isset($sizes["medium"]["file"]))
//						{
//							$obj->_thumbnail_id_val = $upload_dir.'/'.$dirpart.$sizes["medium"]["file"];
//						}else if(isset($sizes["shop_single"]) && isset($sizes["shop_single"]["file"]))
//						{
//							$obj->_thumbnail_id_val = $upload_dir.'/'.$dirpart.$sizes["shop_single"]["file"];
//						}
					}
								}
								
							}
						}
					}
					}
				}
			}
						$thumbcounter = 0;
						$thumbids = "";
						unset($thumbsidmap);
						$thumbsidmap = array();
					}else
					{
						$thumbcounter++;
					}
				}
			}
			
			//gallery
			if(property_exists($id,'_product_image_gallery'))
			{
				if($id->_product_image_gallery != "")
				{
					$id->_product_image_gallery = trim($id->_product_image_gallery);
					$id->_product_image_gallery = trim($id->_product_image_gallery, ',');
					{//no caching
							$val_for_pruduct = "";
							$val_for_pruduct_temp = "";
							$orig_val_for_pruduct = "";
							$orig_val_for_pruduct_temp = "";
//							$mapids= array();
//							$mapidsorig = array();
						$gal_thumbids = explode(',',$id->_product_image_gallery);
						$newgalthumbs = "";
						foreach($gal_thumbids as $galthumbid)
						{
						$query ="SELECT post_id,meta_value
						FROM  {$meta} WHERE post_id IN ({$galthumbid}) AND meta_key='_wp_attachment_metadata'";
						$metathumbs =  $wpdb->get_results($query);
						if ( false === $metathumbs) {
							continue;
						}
						$metamap = array();
						foreach($metathumbs as &$thumb)
						{
								$allsizes = maybe_unserialize($thumb->meta_value);
								if ( !$allsizes ) continue;
								if(!is_array($allsizes)) continue;
								if(!isset($allsizes['file'])) continue;
								$val_for_pruduct_temp = "";
								$orig_val_for_pruduct_temp = "";
								$dirpart = $allsizes['file'];
								$lastSlash = strrpos($dirpart,"/");
								if(FALSE !== $lastSlash)
								{
									$dirpart = substr($dirpart,0,$lastSlash + 1);
								}else
								{
									$dirpart = "";
								}
									
									$val_for_pruduct_temp = $upload_dir.'/'.$allsizes['file'];
									$orig_val_for_pruduct_temp =  $upload_dir.'/'.$allsizes['file'];
						if(isset($allsizes['sizes'])) // && $dirpart !== ""
						{
							$sizes = $allsizes['sizes'];
							$lastheight = 0;
							$lastwidth = 0;
							foreach($sizes as $size)
							{
								if(!isset($size["file"]) || !isset($size["width"]) || !isset($size["height"]))
									continue;
								if($lastheight === 0 && $lastwidth === 0)
								{
									$lastheight = (int)$size["height"];
									$lastwidth  = (int)$size["width"];
									$val_for_pruduct_temp = $upload_dir.'/'.$dirpart.$size["file"];
								}else
								{
									if($size["height"] < $lastheight && $size["width"] < $lastwidth && $size["height"] >= 150 &&  $size["width"] >= 150)
									{
										$lastheight = (int)$size["height"];
										$lastwidth  = (int)$size["width"];
										$val_for_pruduct_temp = $upload_dir.'/'.$dirpart.$size["file"];
									}
								}
							}
						
						}//end if set sizes
//								$mapids[(string)$thumb->post_id] = $val_for_pruduct_temp;
//								$mapidsorig[(string)$thumb->post_id] = $orig_val_for_pruduct_temp;
//
//								
//							}
//							$gal_thumbids = explode(',',$id->_product_image_gallery);
//							foreach($gal_thumbids as $imageid)
//							{
//								if($val_for_pruduct == "")
//								{
//									$val_for_pruduct = $mapids[$imageid];
//								}else
//								{
//									$val_for_pruduct = $val_for_pruduct . "|" . $mapids[$imageid];
//								}
//								if($orig_val_for_pruduct == "")
//								{
//									$orig_val_for_pruduct = $mapidsorig[$imageid];
//								}else
//								{
//									$orig_val_for_pruduct = $orig_val_for_pruduct . "|" . $mapidsorig[$imageid];
//								}
//							}
//							$id->_product_image_gallery_val = $val_for_pruduct;
//							$id->_product_image_gallery_original = $orig_val_for_pruduct;
//							continue;	
								if($val_for_pruduct == "")
								{
									$val_for_pruduct = $val_for_pruduct_temp;
								}else
								{
									$val_for_pruduct = $val_for_pruduct . "|" . $val_for_pruduct_temp;
								}
								if($orig_val_for_pruduct == "")
								{
									$orig_val_for_pruduct = $orig_val_for_pruduct_temp;
								}else
								{
									$orig_val_for_pruduct = $orig_val_for_pruduct . "|" . $orig_val_for_pruduct_temp;
								}
								if($newgalthumbs == "")
								{
									$newgalthumbs = $galthumbid;
								}else
								{
									$newgalthumbs = $newgalthumbs . "," . $galthumbid;
								}
								
							}
							}
//							foreach($prod_ids_arr as $prodid)
//							{
//								if(!array_key_exists($prodid,$ids)) continue;
//								$obj = $ids[$prodid];
								$id->_product_image_gallery = $newgalthumbs;
								$id->_product_image_gallery_val = $val_for_pruduct;
								$id->_product_image_gallery_original = $orig_val_for_pruduct;
//							}
						}
						continue;
						
//					if(array_key_exists($id->_product_image_gallery,$gal_thumbsidmap))
//					{
//						$oldids = $gal_thumbsidmap[$id->_product_image_gallery];
//						$oldids.= ';'. (string)$id->ID;
//						$gal_thumbsidmap[$id->_product_image_gallery] = $oldids;
//					}else
//					{
//						$gal_thumbsidmap[$id->_product_image_gallery] = (string)$id->ID;
//					}
//					
//					if($gal_thumbids == "")
//					{
//						$gal_thumbids = $id->_product_image_gallery;
//					}else
//					{
//						$gal_thumbids.= ',' . $id->_product_image_gallery;
//					}
//					if($gal_thumbcounter > 100)
//					{
//						$temparr[] = explode(",", $gal_thumbids); // put all in an array
//						$temparr = array_values( array_unique( $temparr ) );
//						$gal_thumbids = $temparr.join(',');
//						$query ="SELECT post_id,meta_value
//						FROM  {$meta} WHERE post_id IN ({$gal_thumbids}) AND meta_key='_wp_attachment_metadata'";
//						$metathumbs =  $wpdb->get_results($query);
//						if ( false === $metathumbs) {
//							$gal_thumbcounter = 0;
//							$gal_thumbids = "";
//							continue;
//						}
//						$metamap = array();
//						foreach($metathumbs as &$thumb)
//						{
//							$metamap[$thumb->post_id] = $thumb;
//						}
//						foreach($gal_thumbsidmap as $gal_ids => $prod_ids)
//						{
//							$gal_ids_arr = explode(',',$gal_ids);
//							$val_for_pruduct = "";
//							$val_for_pruduct_temp = "";
//							$orig_val_for_pruduct = "";
//							$orig_val_for_pruduct_temp = "";
//							foreach($gal_ids_arr as $imgid)
//							{
//								if(!array_key_exists($imgid,$metamap))
//								{
////									if($val_for_pruduct == "")
////									{
////										$val_for_pruduct = " ";
////									}else
////									{
////										$val_for_pruduct = $val_for_pruduct . "| ";
////									}
////									if($orig_val_for_pruduct == "")
////									{
////										$orig_val_for_pruduct = " ";
////									}else
////									{
////										$orig_val_for_pruduct = $orig_val_for_pruduct . "| ";
////									}
//									 continue;
//								}
//								$thumb = &$metamap[$imgid];
//								$valueruss = 'a:6:{s:5:"width";i:800;s:6:"height";i:800;s:4:"file";s:45:"2015/07/af215c2e3fd41f95136c70d1284f22ae.jpeg";s:5:"sizes";a:6:{s:9:"thumbnail";a:5:{s:4:"file";s:45:"af215c2e3fd41f95136c70d1284f22ae-600x600.jpeg";s:5:"width";i:600;s:6:"height";i:600;s:9:"mime-type";s:10:"image/jpeg";s:20:"ewww_image_optimizer";s:86:"Уменьшен на 2.8% (1,2 kB) - Ранее оптимизированные";}s:6:"medium";a:5:{s:4:"file";s:45:"af215c2e3fd41f95136c70d1284f22ae-600x600.jpeg";s:5:"width";i:600;s:6:"height";i:600;s:9:"mime-type";s:10:"image/jpeg";s:20:"ewww_image_optimizer";s:33:"Не оптимизировать";}s:14:"shop_thumbnail";a:5:{s:4:"file";s:45:"af215c2e3fd41f95136c70d1284f22ae-190x190.jpeg";s:5:"width";i:190;s:6:"height";i:190;s:9:"mime-type";s:10:"image/jpeg";s:20:"ewww_image_optimizer";s:88:"Уменьшен на 12.2% (839,0 B) - Ранее оптимизированные";}s:12:"shop_catalog";a:5:{s:4:"file";s:45:"af215c2e3fd41f95136c70d1284f22ae-220x220.jpeg";s:5:"width";i:220;s:6:"height";i:220;s:9:"mime-type";s:10:"image/jpeg";s:20:"ewww_image_optimizer";s:87:"Уменьшен на 9.8% (839,0 B) - Ранее оптимизированные";}s:11:"shop_single";a:4:{s:4:"file";s:45:"af215c2e3fd41f95136c70d1284f22ae-630x630.jpeg";s:5:"width";i:630;s:6:"height";i:630;s:9:"mime-type";s:10:"image/jpeg";}s:5:"ideas";a:5:{s:4:"file";s:45:"af215c2e3fd41f95136c70d1284f22ae-460x460.jpeg";s:5:"width";i:460;s:6:"height";i:460;s:9:"mime-type";s:10:"image/jpeg";s:20:"ewww_image_optimizer";s:87:"Уменьшен на 3.4% (942,0 B) - Ранее оптимизированные";}}s:10:"image_meta";a:11:{s:8:"aperture";i:0;s:6:"credit";s:0:"";s:6:"camera";s:0:"";s:7:"caption";s:0:"";s:17:"created_timestamp";i:0;s:9:"copyright";s:0:"";s:12:"focal_length";i:0;s:3:"iso";i:0;s:13:"shutter_speed";i:0;s:5:"title";s:0:"";s:11:"orientation";i:0;}s:20:"ewww_image_optimizer";s:86:"Уменьшен на 3.3% (2,1 kB) - Ранее оптимизированные";}';
////								$allsizes = maybe_unserialize($thumb->meta_value);
//								$allsizes = wp_get_attachment_metadata( $imgid);
////								$allsizes = get_post_meta( $id->ID, '_product_image_gallery', true );
////								$allsizes =  @unserialize( $valueruss );
//								if ( !$allsizes ) continue;
//								if(!is_array($allsizes)) continue;
//								if(!isset($allsizes['file'])) continue;
//								$val_for_pruduct_temp = "";
//								$orig_val_for_pruduct_temp = "";
//								$dirpart = $allsizes['file'];
//								$lastSlash = strrpos($dirpart,"/");
//								if(FALSE !== $lastSlash)
//								{
//									$dirpart = substr($dirpart,0,$lastSlash + 1);
//								}else
//								{
//									$dirpart = "";
//								}
//									
//									$val_for_pruduct_temp = $upload_dir.'/'.$allsizes['file'];
//									$orig_val_for_pruduct_temp =  $upload_dir.'/'.$allsizes['file'];
//						if(isset($allsizes['sizes']) && $dirpart !== "")
//						{
//							$sizes = $allsizes['sizes'];
//							$lastheight = 0;
//							$lastwidth = 0;
//							foreach($sizes as $size)
//							{
//								if(!isset($size["file"]) || !isset($size["width"]) || !isset($size["height"]))
//									continue;
//								if($size["file"] == "")
//									continue;
//								if($lastheight === 0 && $lastwidth === 0)
//								{
//									$lastheight = (int)$size["height"];
//									$lastwidth  = (int)$size["width"];
//									$val_for_pruduct_temp = $upload_dir.'/'.$dirpart.$size["file"];
//								}else
//								{
//									if($size["height"] < $lastheight && $size["width"] < $lastwidth && $size["height"] >= 150 &&  $size["width"] >= 150)
//									{
//										$lastheight = (int)$size["height"];
//										$lastwidth  = (int)$size["width"];
//										$val_for_pruduct_temp = $upload_dir.'/'.$dirpart.$size["file"];
//									}
//								}
//							}
//							//check for thumbnail or medium size to save bandwith
////							if(isset($sizes["thumbnail"]) && isset($sizes["thumbnail"]["file"]))
////							{
////								$val_for_pruduct_temp = $upload_dir.'/'.$dirpart.$sizes["thumbnail"]["file"];
////							}else if(isset($sizes["shop_thumbnail"]) && isset($sizes["shop_thumbnail"]["file"]))
////							{
////								$val_for_pruduct_temp = $upload_dir.'/'.$dirpart.$sizes["shop_thumbnail"]["file"];
////							}else if(isset($sizes["medium"]) && isset($sizes["medium"]["file"]))
////							{
////								$val_for_pruduct_temp = $upload_dir.'/'.$dirpart.$sizes["medium"]["file"];
////							}else if(isset($sizes["shop_single"]) && isset($sizes["shop_single"]["file"]))
////							{
////								$val_for_pruduct_temp = $upload_dir.'/'.$dirpart.$sizes["shop_single"]["file"];
////							}
//						}//end if set sizes
//								
//								if($val_for_pruduct == "")
//								{
//									$val_for_pruduct = $val_for_pruduct_temp;
//								}else
//								{
//									$val_for_pruduct = $val_for_pruduct . "|" . $val_for_pruduct_temp;
//								}
//								if($orig_val_for_pruduct == "")
//								{
//									$orig_val_for_pruduct = $orig_val_for_pruduct_temp;
//								}else
//								{
//									$orig_val_for_pruduct = $orig_val_for_pruduct . "|" . $orig_val_for_pruduct_temp;
//								}
//								
//							}
//							$prod_ids_arr = explode(';',$prod_ids);
//							foreach($prod_ids_arr as $prodid)
//							{
//								if(!array_key_exists($prodid,$ids)) continue;
//								$obj = $ids[$prodid];
//								$obj->_product_image_gallery_val = $val_for_pruduct;
//								$obj->_product_image_gallery_original = $orig_val_for_pruduct;
//							}
//							
//						}
//						$gal_thumbcounter = 0;
//						$gal_thumbids = "";
//						unset($gal_thumbsidmap);
//						$gal_thumbsidmap = array();
//					}else
//					{
//						$gal_thumbcounter++;
//					}
				}
			}
		}
//		return new WP_Error( 'db_query_error', 
//					__( 'Could not execute query' ), $wpdb->last_error );
		self::WriteDebugInfo("16.6 after ids loop ".__LINE__,$curr_settings);
		if($gal_thumbcounter !== 0 && $gal_thumbids !== "" && false)
		{
			$query ="SELECT post_id,meta_value
			FROM  {$meta} WHERE post_id IN ({$gal_thumbids}) AND meta_key='_wp_attachment_metadata'";
			$metathumbs =  $wpdb->get_results($query);
			if ( false === $metathumbs) {
				$gal_thumbcounter = 0;
				$gal_thumbids = "";
				$metathumbs = array();
			}
			$metamap = array();
			foreach($metathumbs as &$thumb)
			{
				$metamap[$thumb->post_id] = $thumb;
			}
			foreach($gal_thumbsidmap as $gal_ids => $prod_ids)
			{
				$gal_ids_arr = explode(',',$gal_ids);
				$val_for_pruduct = "";
				$val_for_pruduct_temp = "";
				$orig_val_for_pruduct = "";
				$orig_val_for_pruduct_temp = "";
				foreach($gal_ids_arr as $imgid)
				{
					if(!array_key_exists($imgid,$metamap))
					{
//						if($val_for_pruduct == "")
//						{
//							$val_for_pruduct = " ";
//						}else
//						{
//							$val_for_pruduct = $val_for_pruduct . "| ";
//						}
//						if($orig_val_for_pruduct == "")
//						{
//							$orig_val_for_pruduct = " ";
//						}else
//						{
//							$orig_val_for_pruduct = $orig_val_for_pruduct . "| ";
//						}
						 continue;
					}
					$thumb = &$metamap[$imgid];
					$allsizes = maybe_unserialize($thumb->meta_value);
					if ( !$allsizes ) continue;
					if(!is_array($allsizes)) continue;
					if(!isset($allsizes['file'])) continue;
					$val_for_pruduct_temp = "";
					$orig_val_for_pruduct_temp = "";
					$dirpart = $allsizes['file'];
					$lastSlash = strrpos($dirpart,"/");
					if(FALSE !== $lastSlash)
					{
						$dirpart = substr($dirpart,0,$lastSlash + 1);
					}else
					{
						$dirpart = "";
					}
						
						$val_for_pruduct_temp = $upload_dir.'/'.$allsizes['file'];
						$orig_val_for_pruduct_temp = $upload_dir.'/'.$allsizes['file'];
			if(isset($allsizes['sizes']) && $dirpart !== "")
			{
				$sizes = $allsizes['sizes'];
				//check for thumbnail or medium size to save bandwith
				$lastheight = 0;
				$lastwidth = 0;
				foreach($sizes as $size)
				{
					if(!isset($size["file"]) || !isset($size["width"]) || !isset($size["height"]))
						continue;
					if($lastheight === 0 && $lastwidth === 0)
					{
						$lastheight = (int)$size["height"];
						$lastwidth  = (int)$size["width"];
						$val_for_pruduct_temp = $upload_dir.'/'.$dirpart.$size["file"];
					}else
					{
						if($size["height"] < $lastheight && $size["width"] < $lastwidth && $size["height"] >= 150 &&  $size["width"] >= 150)
						{
							$lastheight = (int)$size["height"];
							$lastwidth  = (int)$size["width"];
							$val_for_pruduct_temp = $upload_dir.'/'.$dirpart.$size["file"];
						}
					}
				}
				
//				if(isset($sizes["thumbnail"]) && isset($sizes["thumbnail"]["file"]))
//				{
//					$val_for_pruduct_temp = $upload_dir.'/'.$dirpart.$sizes["thumbnail"]["file"];
//				}else if(isset($sizes["shop_thumbnail"]) && isset($sizes["shop_thumbnail"]["file"]))
//				{
//					$val_for_pruduct_temp = $upload_dir.'/'.$dirpart.$sizes["shop_thumbnail"]["file"];
//				}else if(isset($sizes["medium"]) && isset($sizes["medium"]["file"]))
//				{
//					$val_for_pruduct_temp = $upload_dir.'/'.$dirpart.$sizes["medium"]["file"];
//				}else if(isset($sizes["shop_single"]) && isset($sizes["shop_single"]["file"]))
//				{
//					$val_for_pruduct_temp = $upload_dir.'/'.$dirpart.$sizes["shop_single"]["file"];
//				}
			}//end if set sizes
					
					if($val_for_pruduct == "")
					{
						$val_for_pruduct = $val_for_pruduct_temp;
					}else
					{
						$val_for_pruduct = $val_for_pruduct . "|" . $val_for_pruduct_temp;
					}
					if($orig_val_for_pruduct == "")
					{
						$orig_val_for_pruduct = $orig_val_for_pruduct_temp;
					}else
					{
						$orig_val_for_pruduct = $orig_val_for_pruduct . "|" . $orig_val_for_pruduct_temp;
					}
					
				}
				$prod_ids_arr = explode(';',$prod_ids);
				foreach($prod_ids_arr as $prodid)
				{
					if(!array_key_exists($prodid,$ids)) continue;
					$obj = $ids[$prodid];
					$obj->_product_image_gallery_val = $val_for_pruduct;
					$obj->_product_image_gallery_original = $orig_val_for_pruduct;
				}
				
			}
			$gal_thumbcounter = 0;
			$gal_thumbids = "";
		}
		if($thumbcounter !== 0 && $thumbids !== "")
		{
			$query = "SELECT post_id,meta_value
			FROM  {$meta} WHERE post_id IN ({$thumbids}) AND meta_key='_wp_attachment_metadata'";
			$metathumbs =  $wpdb->get_results($query);
			if ( false === $metathumbs) {
				$thumbcounter = 0;
				$thumbids = "";
				$metathumbs = array();
			}
			foreach($metathumbs as &$thumb)
			{
				if(array_key_exists($thumb->post_id,$thumbsidmap))
				{
					$thumbidsmul = $thumbsidmap[$thumb->post_id];
					$curthumbids = explode(';',$thumbidsmul);
					foreach($curthumbids as $curthumbid)
					{
					if(array_key_exists($curthumbid,$ids))
					{
						$obj = $ids[$curthumbid];
						$allsizes = maybe_unserialize($thumb->meta_value);
						if ( $allsizes ) 
						{
							if(is_array($allsizes))
							{
								
								$obj->_thumbnail_id_val = "";
								if(isset($allsizes['file']))
								{
									$dirpart = $allsizes['file'];
									$lastSlash = strrpos($dirpart,"/");
									if(FALSE !== $lastSlash)
									{
										$dirpart = substr($dirpart,0,$lastSlash + 1);
									}else
									{
										$dirpart = "";
									}
									
									$obj->_thumbnail_id_val = $upload_dir.'/'.$allsizes['file'];
									$obj->_thumbnail_id_original = $allsizes['file'];
									$obj->_thumbnail_id_info = "<div class='fileinfo'>".$allsizes['file']."</div>";
									if(isset($allsizes['width']) && isset($allsizes['height']))
									{
										$obj->_thumbnail_id_info.= "<div class='dims'>".$allsizes['width']." x ".$allsizes['height']."</div>";
									}
//									if(isset($allsizes['width']) && isset($allsizes['width']))
//									{
//										$obj->_thumbnail_id_info.= "<div class='dims'>".$allsizes['width']." x ".$allsizes['height']."</div>";
//									}
					if(isset($allsizes['sizes'])) // && $dirpart !== ""
					{
						$sizes = $allsizes['sizes'];
						$lastheight = 0;
							$lastwidth = 0;
							foreach($sizes as $size)
							{
								if(!isset($size["file"]) || !isset($size["width"]) || !isset($size["height"]))
									continue;
								if($lastheight === 0 && $lastwidth === 0)
								{
									$lastheight = (int)$size["height"];
									$lastwidth  = (int)$size["width"];
									$obj->_thumbnail_id_val = $upload_dir.'/'.$dirpart.$size["file"];
								}else
								{
									if($size["height"] < $lastheight && $size["width"] < $lastwidth && $size["height"] >= 150 &&  $size["width"] >= 150)
									{
										$lastheight = (int)$size["height"];
										$lastwidth  = (int)$size["width"];
										$obj->_thumbnail_id_val = $upload_dir.'/'.$dirpart.$size["file"];
									}
								}
							}
						//check for thumbnail or medium size to save bandwith
//						if(isset($sizes["thumbnail"]) && isset($sizes["thumbnail"]["file"]))
//						{
//							$obj->_thumbnail_id_val = $upload_dir.'/'.$dirpart.$sizes["thumbnail"]["file"];
//						}else if(isset($sizes["shop_thumbnail"]) && isset($sizes["shop_thumbnail"]["file"]))
//						{
//							$obj->_thumbnail_id_val = $upload_dir.'/'.$dirpart.$sizes["shop_thumbnail"]["file"];
//						}else if(isset($sizes["medium"]) && isset($sizes["medium"]["file"]))
//						{
//							$obj->_thumbnail_id_val = $upload_dir.'/'.$dirpart.$sizes["medium"]["file"];
//						}else if(isset($sizes["shop_single"]) && isset($sizes["shop_single"]["file"]))
//						{
//							$obj->_thumbnail_id_val = $upload_dir.'/'.$dirpart.$sizes["shop_single"]["file"];
//						}
					}
								}
								
							}
						}
					}
					
					}
				}
			}
		}
		self::WriteDebugInfo("16.7 after thumb gen ".__LINE__,$curr_settings);
		$cats = array();
		if($arrduplicate === null)
		{
			if($useit != "")
			{
				$useit = " AND {$temptable}.useit=1";
			}
			$query = "SELECT 
				{$temptable}.ID, rel.term_taxonomy_id, term.term_id
				FROM {$temptable}
				INNER JOIN {$term} rel ON {$temptable}.ID=rel.object_id
				INNER JOIN {$term_taxonomy} term ON rel.term_taxonomy_id=term.term_taxonomy_id
				{$useit}";
			$cats = $wpdb->get_results($query);
			if ( is_wp_error($cats) ) {
				return new WP_Error( 'db_query_error', 
					__( 'Could not execute query' ), $wpdb->last_error );
			} 
		}else
		{
			$duplicateids = "";
			foreach($arrduplicate as $key => $value)
			{
				if($duplicateids == "")
					$duplicateids.= $value->ID;
				else
					$duplicateids.= ",".$value->ID;
			}
				
			$query = "SELECT 
				{$posts}.ID, rel.term_taxonomy_id, term.term_id
				FROM {$posts}
				INNER JOIN {$term} rel ON {$posts}.ID=rel.object_id
				INNER JOIN {$term_taxonomy} term ON rel.term_taxonomy_id=term.term_taxonomy_id
				WHERE {$posts}.ID IN ({$duplicateids})";
			$cats = $wpdb->get_results($query);
			if ( is_wp_error($cats) ) {
				return new WP_Error( 'db_query_error', 
					__( 'Could not execute query' ), $wpdb->last_error );
			} 
		}
		
			self::WriteDebugInfo("17 after get taxonomies ".__LINE__,$curr_settings);
		//categories
//		return new WP_Error( 'db_query_error', 
//					__( 'Could not execute query' ), $wpdb->last_error );
		$cats_assoc = array();
		
		$arrtaxonomies = array();
		if(in_array('product_cat',self::$columns) || empty(self::$columns))
			$arrtaxonomies[] = 'product_cat';
		if(in_array('product_shipping_class',self::$columns) || empty(self::$columns))
			$arrtaxonomies[] = 'product_shipping_class';
		if(in_array('_visibility',self::$columns) || in_array('_stock_status',self::$columns) || in_array('_featured',self::$columns) || empty(self::$columns))
		{
			if(self::$isversion3)
			{
				if(taxonomy_exists('product_visibility'))
					$arrtaxonomies[] = 'product_visibility';
			}
		}
		$arrtaxonomies[] = 'product_type';
		
		$args_cats = array(
		    'number'     => 99999,
		    'orderby'    => 'slug',
		    'order'      => 'ASC',
		    'hide_empty' => false,
		    'include'    => '',
			'fields'     => 'all'
		);
//		$tagcount = wp_count_terms( 'product_tag', $args_cats );
//		if($tagcount < 2000)
		{
			if(in_array('product_tag',self::$columns) || empty(self::$columns))
				$arrtaxonomies[] = 'product_tag';
		}
						
		$sel_fields = get_option('w3exabe_custom');
	
		if(is_array($sel_fields) && !empty($sel_fields))
		{
			foreach($sel_fields as $i => $innerarray)
			{
				if(isset($innerarray['type']))
				{
					if($innerarray['type'] === 'customh' || $innerarray['type'] === 'custom')
					{
						if(taxonomy_exists($i))
						{
							if($i === 'product_delivery_times' || $i === 'product_sale_labels')
								continue;
							if(in_array($i,self::$columns))
								$arrtaxonomies[] = $i;
						}
					}
				}
				
			}
		}
		
		foreach($arrtaxonomies as $taxonomy)
		{
//			$woo_categories = get_terms( $taxonomy, $args_cats );
			$getquery = "SELECT t.name,tt.term_taxonomy_id FROM {$wpdb->prefix}terms as t INNER JOIN {$wpdb->prefix}term_taxonomy AS tt ON t.term_id= tt.term_id WHERE tt.taxonomy IN('".$taxonomy."')";
			$woo_categories = $wpdb->get_results($getquery);
			if(is_wp_error($woo_categories))
				continue;
			foreach($woo_categories as $category)
			{
			   if(!is_object($category)) continue;
			   if(!property_exists($category,'term_taxonomy_id')) continue;
			   if(!property_exists($category,'name')) continue;
//			   if($taxonomy == 'product_cat')
//			   	  $idmap = array((string)$category->name,'cats');
//			   else
				if($converttoutf8 && function_exists('mb_convert_encoding'))
				{
					$category->name =  mb_convert_encoding($category->name, "UTF-8");
				}
			  	  $idmap = array((string)$category->name,$taxonomy);
			   $cats_assoc[$category->term_taxonomy_id] = $idmap;
			};
		}
		self::WriteDebugInfo("18 after map taxonomies ".__LINE__,$curr_settings);
		if(is_array($attributes) && !empty($attributes))
		{
			foreach($attributes as $attr)
			{
//				if(!property_exists($attr,'values'))
//					continue;
				foreach($attr->values as $value)
				{
//					if(!property_exists($value,'name') || !property_exists($attr,'name'))
//						continue;
					if($converttoutf8)
					{
//						$value->name =  mb_convert_encoding($value->name, "UTF-8");
//						$attr->name =	mb_convert_encoding($attr->name, "UTF-8");
					}
				    $idmap = array((string)"ids",'_w3ex_attr');
					$cats_assoc[$value->id] = $idmap;
				}
		    }
		}
		self::WriteDebugInfo("19 after map attrs ".__LINE__,$curr_settings);
//		return new WP_Error( 'db_query_error', 
//					__( 'Could not execute query' ), $wpdb->last_error );
		foreach($cats as &$val)
		{
			if(!property_exists($val,'ID') || !property_exists($val,'term_id') || !property_exists($val,'term_taxonomy_id'))
				continue;
			if(array_key_exists($val->term_taxonomy_id,$cats_assoc))
			{
				if(array_key_exists($val->ID,$ids))
				{
					$idmap = $cats_assoc[$val->term_taxonomy_id];
					$obj = $ids[$val->ID];
					if(!is_object($obj))
						continue;
					if(!isset($idmap[1]) || !isset($idmap[0]))
						continue;
					if(strpos($idmap[1],'_w3ex_attr') === 0)
					{
						if($obj->post_type != 'product')
							continue;
						if(property_exists($obj,'attribute_pa_ids'))
							$obj->attribute_pa_ids = $obj->attribute_pa_ids . ',' .$val->term_id;
						else
							$obj->attribute_pa_ids = $val->term_id;
						continue;
					} 
					if($idmap[1] === 'product_type' && $idmap[0] === 'grouped')
					{
						$args = apply_filters( 'woocommerce_grouped_children_args', array(
				        			'post_parent' 	=> $obj->ID,
				        			'post_type'		=> 'product',
				        			'orderby'		=> 'menu_order',
				        			'order'			=> 'ASC',
				        			'fields'		=> 'ids',
				        			'post_status'	=> 'publish',
				        			'numberposts'	=> -1
				        		) );

						$children = get_posts( $args );	
						$obj->product_type_children = implode (',' , $children );				
					}
					if($idmap[1] === 'product_visibility')
					{
						if($idmap[0] === 'outofstock')
						{
							$obj->_stock_status = 'outofstock';
						}
						if($idmap[0] === 'featured')
						{
							$obj->_featured = 'yes';
						}
						if($idmap[0] === 'exclude-from-catalog')
						{
							if($obj->_visibility === 'Catalog')
							{
								$obj->_visibility = 'Hidden';
							}else
							{
								$obj->_visibility = 'Search';
							}
						}
						if($idmap[0] === 'exclude-from-search')
						{
							if($obj->_visibility === 'Search')
							{
								$obj->_visibility = 'Hidden';
							}else
							{
								$obj->_visibility = 'Catalog';
							}
						}
					}
					if(property_exists($obj,$idmap[1]) && property_exists($obj,$idmap[1] . '_ids'))
					{
//						if(strpos($idmap[1],'attribute_pa_attribute') !== FALSE)
//						{
//							if($obj->post_type != 'product')
//								continue;
//							$obj->attribute_pa_ids = $obj->attribute_pa_ids . ',' .$val->term_id;
//							continue;
//						} 
						$obj->{$idmap[1]} = $obj->{$idmap[1]}. ', '. $idmap[0];
						$obj->{$idmap[1] . '_ids'} = $obj->{$idmap[1] . '_ids'} . ',' .$val->term_id;
					}else
					{
//						if(strpos($idmap[1],'attribute_pa_attribute') !== FALSE)
//						{
//							if($obj->post_type != 'product')
//								continue;
//							$obj->attribute_pa_ids = $val->term_id;
//							continue;
//						} 
						$obj->{$idmap[1]} = $idmap[0];
						$obj->{$idmap[1] . '_ids'} = $val->term_id;
					}
				}
			}
		}
}
catch(Exception $e) {
  return $e->getMessage();
}
//		return new WP_Error( 'db_query_error', 
//					__( 'Could not execute query' ), $wpdb->last_error );
		return $info;
	}
	
	public static function GenerateAttribute($prodid,&$attributes)
	{
		$attrarrays = array();
		if(is_array($attributes) && !empty($attributes))
		{
			foreach($attributes as $attr)
			{
				if(!property_exists($attr,'name'))
					continue;
				$attrarrays[] = 'pa_'.$attr->name;
		    }
		}
		$ID = $prodid;
		
			$patt = get_post_meta($ID,'_product_attributes',true);
			if(!is_array($patt))
				$patt = array();
			$attrs = wp_get_object_terms($ID,$attrarrays);
			if(is_array($attrs))
			{

				$existing = array();
				foreach($attrs as $attr_obj)
				{
					if(!is_object($attr_obj)) continue;
					if(!property_exists($attr_obj,'term_id')) continue;
					if(!property_exists($attr_obj,'taxonomy')) continue;
					$attr_slug = sanitize_title($attr_obj->taxonomy);
					if(!in_array($attr_slug,$existing))
						$existing[] = $attr_slug;
					if(!isset($patt[$attr_slug]))
					{
						$patt[$attr_slug] = array();
						$patt[$attr_slug]["name"] = $attr_obj->taxonomy;
						$patt[$attr_slug]["is_visible"]   = 0;
						$patt[$attr_slug]["is_taxonomy"]  = 1;
						$patt[$attr_slug]["is_variation"] = 0;
						$patt[$attr_slug]["value"]  = "";
						$patt[$attr_slug]["position"] = count($patt);
					}
				}
//							if(count($attrs) === 0)
				{//check for deleted terms
					foreach($patt as $patt_name => $patt_item)
					{
						if(is_array($patt_item))
						{
							if(isset($patt_item["is_taxonomy"]) && $patt_item["is_taxonomy"] == 1)
							{
								if(!in_array($patt_name,$existing))
								 unset($patt[$patt_name]);
							}
								
						}
					}
				}
				update_post_meta($ID,'_product_attributes',$patt);
				self::CallWooAction($ID);
			}
					
	}
	public static function saveProducts(&$data,&$children,&$currentpos,&$batchnumber)
	{
		global $wpdb;
		$posts = $wpdb->posts;
		$meta = $wpdb->postmeta;
		$temptable = $wpdb->prefix."wpmelon_advbedit_temp";
		$term = $wpdb->term_relationships;
		$handledchildren = array();
		$sel_fields = get_option('w3exabe_custom');
		$handledattrs = array();
		$attributes = array();
		$attrmapslugtoname = array();
		$parentattrs_cache = array();
		$update_parent_attr = array();
		$update_vars_price = array();
		self::GetAttributes($attributes,$attrmapslugtoname,false,'',false,true);
		$tax_classes = array();
		self::GetTaxClasses($tax_classes);
		$retarray = array();
		$counter = 0;
		$processcounter = 0;
		self::WriteDebugInfo("clear","");
		$rowstoskip = -1;
		$arr_prod_vis = array();
		global $woocommerce;
		if(isset($woocommerce) && property_exists($woocommerce,'version'))
		{
			$version = (double)$woocommerce->version;
			if($version > 2.6)
			{
				self::$isversion3 = true;
				$arr_prod_vis = array_map( 'absint', wp_parse_args(
				wp_list_pluck(
					get_terms( array(
						'taxonomy' => 'product_visibility',
						'hide_empty' => false,
					) ),
					'term_taxonomy_id',
					'name'
				),
				array(
					'exclude-from-catalog' => 0,
					'exclude-from-search'  => 0,
					'featured'             => 0,
					'outofstock'           => 0,
					'rated-1'              => 0,
					'rated-2'              => 0,
					'rated-3'              => 0,
					'rated-4'              => 0,
					'rated-5'              => 0,
				)
			) );
			}
		}
		
		if($currentpos !== -1)
		{
			$rowstoskip = $currentpos * $batchnumber;
			if($rowstoskip >= count($data))
			{
				$rowstoskip = -1;
			}
			$currentpos++;
		}
		
		foreach($data as $arrrow)
		{
			if(!is_array($arrrow)) continue;
			$counter++;
			
			$oldpost = null;
			if($rowstoskip !== -1)
			{
				if($counter <= $rowstoskip)
					continue;
				if($processcounter < $batchnumber)
				{
					$processcounter++;
				}else
				{
					continue;
				}
			}
//			self::WriteDebugInfo("loop number ".__LINE__,$curr_settings);
			self::WriteDebugInfo("loop number ".$counter,"");
			$ID = 0;
			if(array_key_exists('ID',$arrrow))
			{
				$ID = (int)$arrrow['ID'];
			
				$parentid = 0;
				if(array_key_exists('post_parent',$arrrow))
					$parentid = (int)$arrrow['post_parent'];
				if(array_key_exists('_sale_price',$arrrow))
					$arrrow['_sale_price'] = str_replace(",",".",$arrrow['_sale_price']);
				if(array_key_exists('_regular_price',$arrrow))
					$arrrow['_regular_price'] = str_replace(",",".",$arrrow['_regular_price']);
				if($ID < 0) continue;
				if(self::$bsavepost)
				{
					$oldpost = get_post($ID);
				}
				$where = "";
				$fields = "";
				foreach($arrrow as $i => $Row)
				{
					if(is_array($sel_fields) && !empty($sel_fields))
					{
						if(array_key_exists($i,$sel_fields))
						{
							if(isset($sel_fields[$i]['type']))
							{
								if($sel_fields[$i]['type'] === 'customh')
								{
									if(taxonomy_exists($i))
									{
										if($i === 'product_delivery_times' || $i === 'product_sale_labels')
										{
											if($i === 'product_delivery_times')
											{
												$cat_ids = explode(',',$Row);
												$cat_ids = array_map( 'intval', $cat_ids );
												$cat_ids = array_unique( $cat_ids );
												if(isset($cat_ids[0]))
													update_post_meta ( $ID,'_lieferzeit' , $cat_ids[0]);
												else
													delete_post_meta ( $ID,'_lieferzeit');
											}
											if($i === 'product_sale_labels')
											{
												$cat_ids = explode(',',$Row);
												$cat_ids = array_map( 'intval', $cat_ids );
												$cat_ids = array_unique( $cat_ids );
												if(isset($cat_ids[0]))
													update_post_meta ( $ID,'_sale_label' , $cat_ids[0]);
												else
													delete_post_meta ( $ID,'_sale_label');
											}
											continue;
										}
										$cat_ids = explode(',',$Row);
										$cat_ids = array_map( 'intval', $cat_ids );
										$cat_ids = array_unique( $cat_ids );
										$term_info = wp_set_object_terms($ID,$cat_ids,$i);
//										if(!is_wp_error($term_info))
//											do_action( 'add_term_relationship', $ID, $term_info['term_taxonomy_id'] );
									}
									continue;
								}elseif($sel_fields[$i]['type'] === 'custom')
								{
									if(isset($sel_fields[$i]['isnewvals']) && ($sel_fields[$i]['isnewvals'] === 'true') && taxonomy_exists($i))
									{
										$cat_ids = explode(',',$Row);
										$cat_ids = array_map( 'trim', $cat_ids );
										$cat_ids = array_unique( $cat_ids );
										wp_set_object_terms($ID,$cat_ids,$i);
									}else
									{
										$cat_ids = explode(',',$Row);
										$cat_ids = array_map( 'trim', $cat_ids );
										$cat_ids = array_unique( $cat_ids );
										$new_ids = array();
										foreach($cat_ids as $value)
										{
											if(term_exists($value,$i))
											{
												$new_ids[] = $value;
											}
										}
										wp_set_object_terms($ID,$new_ids,$i);
									}
									continue;
								}
							}
							
						}
					}
					
					switch($i){
						case "post_title"://title
						{
							$query = "UPDATE {$posts} SET post_title='".$Row."' WHERE ID={$ID}";
							$wpdb->query($query);
							do_action( 'w3exabe_post_data_changed', $ID, $i, $Row );
						}break;
						case "post_type":
						{
							$query = "UPDATE {$posts} SET post_type='".$Row."' WHERE ID={$ID}";
							$wpdb->query($query);
							if($Row === "product")
							{//delete attribute data
								$query ="SELECT meta_key FROM {$meta} WHERE post_id={$ID} AND meta_key LIKE 'attribute_%'";
								$metavals =  $wpdb->get_results($query);
								if ( !is_wp_error($metavals) ) 
								{
								     foreach($metavals as $metain)
								     {
									 	delete_post_meta($ID, $metain->meta_key);
									 }
								} 
							}
						}break;
						case "post_content"://desct
						{
							$Row = str_replace("\r\n", "\n",$Row);
							$Row = str_replace("\n", "\r\n",$Row);
							$query = "UPDATE {$posts} SET post_content='".$Row."' WHERE ID={$ID}";
							$wpdb->query($query);
							do_action( 'wpmelabe_post_data_changed', $ID, $i, $Row );
						}break;
						case "post_excerpt":
						{
							$Row = str_replace("\r\n", "\n",$Row);
							$Row = str_replace("\n", "\r\n",$Row);
							$query = "UPDATE {$posts} SET post_excerpt='".$Row."' WHERE ID={$ID}";
							$wpdb->query($query);
							do_action( 'wpmelabe_post_data_changed', $ID, $i, $Row );
						}break;
						case "post_name":
						{
							$slug = apply_filters('sanitize_title', $Row);
							$slug = sanitize_title_with_dashes($slug,'','save');
							$slug = wp_unique_post_slug( $slug, $ID, 'publish', 'product', 0);
							
							$query = "UPDATE {$posts} SET post_name='{$slug}' WHERE ID={$ID}";
							$wpdb->query($query);
							do_action( 'wpmelabe_post_data_changed', $ID, $i, $Row );
//							self::CallWooAction($ID,$oldpost,$arrrow);
//							if($slug != $Row)
							{
								$newvar = new stdClass();
								$newvar->ID = (string)$ID;
								$newvar->post_name = $slug;
								$permalink = get_permalink($ID);
								if(false !== $permalink)
								{
									$newvar->_product_permalink = $permalink;
								}
								$retarray[] = $newvar;
							}
							
								
						}break;
						case "post_date":
						{
							$date = $Row;
							$date1 = new DateTime($date);
							$date = $date1->format('Y-m-d');
//							$datenow = new DateTime();
							$date = $date.' '.date('H:i:s');
							$date_gmt = get_gmt_from_date($date);
							$query = "UPDATE {$posts} SET post_date='{$date}', post_date_gmt='{$date_gmt}' WHERE ID={$ID}";
							$wpdb->query($query);
							do_action( 'wpmelabe_post_data_changed', $ID, $i, $date );
						}break;
						case "menu_order":
						{
							$query = "UPDATE {$posts} SET menu_order='".intval($Row)."' WHERE ID={$ID}";
							$wpdb->query($query);
							do_action( 'wpmelabe_post_data_changed', $ID, $i, $Row );
						}break;
						case "comment_status":
						{
							if($Row == 'yes')
								$query = "UPDATE {$posts} SET comment_status='open' WHERE ID={$ID}";
							else
								$query = "UPDATE {$posts} SET comment_status='closed' WHERE ID={$ID}";
							$wpdb->query($query);
							do_action( 'wpmelabe_post_data_changed', $ID, $i, $Row );
						}break;
						case "post_author":
						{
							$cat_ids = explode(',',$Row);
							$cat_ids = array_map( 'intval', $cat_ids );
							$cat_ids = array_unique( $cat_ids );
							$val = implode("",$cat_ids);
							$query = "UPDATE {$posts} SET post_author='".$val."' WHERE ID={$ID}";
							$wpdb->query($query);
							do_action( 'wpmelabe_post_data_changed', $ID, $i, $val );
						}break;
						case "_featured":
						{
							if(self::$isversion3)
							{
								if($Row === 'yes')
								{
									wp_set_object_terms($ID,$arr_prod_vis['featured'],'product_visibility',true);
								}else
								{
									$product_terms = wp_get_object_terms( $ID,  'product_visibility',array('fields' => 'ids') );
									$product_terms = array_diff($product_terms, array($arr_prod_vis['featured']));
									wp_set_object_terms($ID,$product_terms,'product_visibility');
								}
							}else
							{
								update_post_meta( $ID , '_featured', $Row);
							}
							
						}break;
						case "_visibility":
						{
							$visibility = "visible";
							if($Row == "Catalog/search")
								$visibility = "visible";
							if($Row == "Catalog")
								$visibility = "catalog";
							if($Row == "Search")
								$visibility = "search";
							if($Row == "Hidden")
								$visibility = "hidden";
							if(self::$isversion3)
							{
								if($Row == "Catalog")
								{
									$product_terms = wp_get_object_terms( $ID,  'product_visibility',array('fields' => 'ids') );
									$product_terms = array_diff($product_terms, array($arr_prod_vis['exclude-from-catalog'],$arr_prod_vis['exclude-from-search']));
									$product_terms[] = $arr_prod_vis['exclude-from-search'];
									wp_set_object_terms($ID,$product_terms,'product_visibility');
								}
								if($Row == "Catalog/search")
								{
									$product_terms = wp_get_object_terms( $ID,  'product_visibility',array('fields' => 'ids') );
									$product_terms = array_diff($product_terms, array($arr_prod_vis['exclude-from-catalog'],$arr_prod_vis['exclude-from-search']));
//									$product_terms[] = $arr_prod_vis['exclude-from-search'];
									wp_set_object_terms($ID,$product_terms,'product_visibility');
								}
								if($Row == "Search")
								{
									$product_terms = wp_get_object_terms( $ID,  'product_visibility',array('fields' => 'ids') );
									$product_terms = array_diff($product_terms, array($arr_prod_vis['exclude-from-catalog'],$arr_prod_vis['exclude-from-search']));
									$product_terms[] = $arr_prod_vis['exclude-from-catalog'];
									wp_set_object_terms($ID,$product_terms,'product_visibility');
								}
								if($Row == "Hidden")
								{
									$product_terms = wp_get_object_terms( $ID,  'product_visibility',array('fields' => 'ids') );
									$product_terms = array_diff($product_terms, array($arr_prod_vis['exclude-from-catalog'],$arr_prod_vis['exclude-from-search']));
									$product_terms[] = $arr_prod_vis['exclude-from-catalog'];
									$product_terms[] = $arr_prod_vis['exclude-from-search'];
									wp_set_object_terms($ID,$product_terms,'product_visibility');
								}
							}else
							{
								update_post_meta( $ID , '_visibility', $visibility);
							}
						}break;
						case "_sku":
						{
							$save_sku = wc_clean($Row);

							if ($save_sku === '') 
							{
								update_post_meta($ID, '_sku', '');
							}else
							{
								$isunique = wc_product_has_unique_sku( $ID, $save_sku);
								if($isunique)
								{
									update_post_meta( $ID, '_sku', $save_sku );
								}else
								{
									$newvar = new stdClass();
									$newvar->ID = (string)$ID;
									$newvar->_sku = true;
									$retarray[] = $newvar;
								}
							}
						}break;
						case "grouped_items":
						{
							$cat_ids = explode(',',$Row);
							$cat_ids = array_map( 'intval', $cat_ids );
							$cat_ids = array_unique( $cat_ids );
							if(count($cat_ids) > 0)
							{
								$parentid1 = $cat_ids[0];
								if($cat_ids[0] === 0)
								{
									$parentid1 = $parentid;
								}
								$query = "UPDATE {$posts} SET post_parent='.$cat_ids[0].' WHERE ID={$ID}";
								$wpdb->query($query);
								//update parent grouped
        						$transient_name = 'wc_product_children_' . $parentid1;
				        		$args = apply_filters( 'woocommerce_grouped_children_args', array(
				        			'post_parent' 	=> $parentid1,
				        			'post_type'		=> 'product',
				        			'orderby'		=> 'menu_order',
				        			'order'			=> 'ASC',
				        			'fields'		=> 'ids',
				        			'post_status'	=> 'publish',
				        			'numberposts'	=> -1,
				        		) );

						        $children = get_posts( $args );

								set_transient( $transient_name, $children, DAY_IN_SECONDS * 30 );
							}
						}break;
						case "product_cat":
						{
							$cat_ids = explode(',',$Row);
							$cat_ids = array_map( 'intval', $cat_ids );
							$cat_ids = array_unique( $cat_ids );
							wp_set_object_terms($ID,$cat_ids,'product_cat');
//							self::WriteDebugInfo("loop number ".__LINE__,$curr_settings);
						}break;
						case "product_tag":
						{
							$cat_ids = explode(',',$Row);
							//use intval insterad of trim for hierarchical tags
							$cat_ids = array_map( 'intval', $cat_ids );
							$cat_ids = array_unique( $cat_ids );
							wp_set_object_terms($ID,$cat_ids,'product_tag');
						}break;
						case "product_shipping_class":
						{
							$cat_ids = explode(',',$Row);
							$cat_ids = array_map( 'intval', $cat_ids );
							$cat_ids = array_unique( $cat_ids );
							wp_set_object_terms($ID,$cat_ids,'product_shipping_class');
						}break;
						case "product_type":
						{
							$cat_ids = explode(',',$Row);
							$cat_ids = array_map( 'intval', $cat_ids );
							$cat_ids = array_unique( $cat_ids );
							wp_set_object_terms($ID,$cat_ids,'product_type');
						}break;
						case "_download_expiry":
						{
							update_post_meta( $ID , '_download_expiry',$Row);
						}break;
						case "_download_limit":
						{
							update_post_meta( $ID , '_download_limit', $Row);
						}break;	
						case "_download_type":
						{
							$down_type= "";
							if($Row == "Application")
								$down_type = "application";
							if($Row == "Music")
								$down_type = "music";
							update_post_meta( $ID , '_download_type', $down_type);
						}break;
						case "_downloadable_files":
						{
							 $down_files = array();
							 $files = array();
							 $down_files = explode('*****',$Row);
							 if($down_files)
							 {
							 	 for($i = 0; $i < count($down_files); $i++)
								 {
								 	$itemsarr = $down_files[$i];
									if(!isset($itemsarr) || $itemsarr === "") continue;
								  	  $items =  explode('#####',$itemsarr);
									  $name = "";
									  for($j = 0; $j < count($items); $j++)
								  	  {
									      $item = $items[$j];	
										  if(!isset($item) || $item === "") continue;
										  if($j == 0)
										  {//name
										  	   $name = $item;
										  }else
										  {//url
										  	if($item != "")
											{
										  	   $files[ md5( $item )] = array(
													'name' => $name,
													'file' => $item
												);
											}
										  }
									  }
								  }
							 }else
							 {
							 	  $items =  explode('#####',$Row);
								  $name = "";
								  if($items)
								  {
								  	  for($j = 0; $j < count($items); $j++)
								  	  {
									      $item = $items[$j];	
										  if(!isset($item) || $item === "") continue;
										  if($j == 0)
										  {//name
										  	   $name = $item;
										  }else
										  {//url
										  	if($item != "")
											{
										  	   $files[ md5( $item )] = array(
													'name' => $name,
													'file' => $item
												);
											}
										  }
									  }
								  }
								  
							 }
							self::HandleFiles($ID,$files);
							update_post_meta( $ID , '_downloadable_files', $files );
						}break;
						case "_upsell_ids":
						{
							if($Row === "")
							{
								delete_post_meta( $ID , '_upsell_ids');
							}else
							{
								 $sell_ids = array();
								 $sell_idsch = explode(',',$Row);
								 if($sell_idsch)
								 {
								 	 for($i = 0; $i < count($sell_idsch); $i++)
									 {
									 	$itemsarr = $sell_idsch[$i];
										$itemsarr = trim($itemsarr);
										if(!isset($itemsarr) || $itemsarr === "") continue;
										if(!is_numeric($itemsarr)) continue;
									  	$sell_ids[] = absint($itemsarr);
									  }
								 }
								update_post_meta( $ID , '_upsell_ids', $sell_ids );
							}
						}break;
						case "_crosssell_ids":
						{
							if($Row === "")
							{
								delete_post_meta( $ID , '_crosssell_ids');
							}else
							{
								 $sell_ids = array();
								 $sell_idsch = explode(',',$Row);
								 if($sell_idsch)
								 {
								 	 for($i = 0; $i < count($sell_idsch); $i++)
									 {
									 	$itemsarr = $sell_idsch[$i];
										$itemsarr = trim($itemsarr);
										if(!isset($itemsarr) || $itemsarr === "") continue;
										if(!is_numeric($itemsarr)) continue;
									  	$sell_ids[] = absint($itemsarr);
									  }
								 }
								update_post_meta( $ID , '_crosssell_ids', $sell_ids );
							}
						}break;
						case "post_status":
						{
							$query = "SELECT post_type FROM {$posts} WHERE ID={$ID}";
							$ret = $wpdb->get_var($query);
							$bcallaction = true;
							$old_status = "";
							$post = new stdClass();
							if($ret === 'product')
							{
								$post = get_post($ID);
								$old_status = $post->post_status;
								$bcallaction = true;
							}
							if($Row == 'publish' && $ret === 'product')
							{
								$query = "SELECT {$posts}.post_name FROM {$posts} WHERE {$posts}.ID={$ID}";
								$ret = $wpdb->get_var($query);
								if(!is_wp_error($ret) && $ret == '')
								{
									$query = "SELECT post_title, post_date FROM {$posts} WHERE {$posts}.ID={$ID}";
									$ret = $wpdb->get_results($query);
									if(!is_wp_error($ret) && count($ret) == 1)
									{
										$obj = $ret[0];
										$title = $obj->post_title;
										$iso9_table = array(
											'ě' => 'e', 'š' => 's', 'č' => 'c', 'ř' => 'r', 'ž' => 'z',
											'ý' => 'y', 'á' => 'a', 'í' => 'i', 'é' => 'e', 'ď' => 'd',
											'ť' => 't', 'ň' => 'n', 'ú' => 'u', 'ů' => 'u', 'Š' => 'S',
											'Č' => 'C', 'Ř' => 'R', 'Ž' => 'Z', 'Á' => 'A', 'Ú' => 'U',
											'ü' => 'u','ğ' => 'g','Ş' => 'S','ş' => 's','ö' => 'o',
											'Ö' => 'o','ç' => 'c','Ç' => 'c','ı' => 'i'
										);
										$iso9_table1 = array(
											'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Ѓ' => 'G',
											'Ґ' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'YO', 'Є' => 'YE',
											'Ж' => 'ZH', 'З' => 'Z', 'Ѕ' => 'Z', 'И' => 'I', 'Й' => 'J',
											'Ј' => 'J', 'І' => 'I', 'Ї' => 'YI', 'К' => 'K', 'Ќ' => 'K',
											'Л' => 'L', 'Љ' => 'L', 'М' => 'M', 'Н' => 'N', 'Њ' => 'N',
											'О' => 'O', 'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T',
											'У' => 'U', 'Ў' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'TS',
											'Ч' => 'CH', 'Џ' => 'DH', 'Ш' => 'SH', 'Щ' => 'SHT', 'Ъ' => '',
											'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'YU', 'Я' => 'YA',
											'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'ѓ' => 'g',
											'ґ' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'є' => 'ye',
											'ж' => 'zh', 'з' => 'z', 'ѕ' => 'z', 'и' => 'i', 'й' => 'j',
											'ј' => 'j', 'і' => 'i', 'ї' => 'yi', 'к' => 'k', 'ќ' => 'k',
											'л' => 'l', 'љ' => 'l', 'м' => 'm', 'н' => 'n', 'њ' => 'n',
											'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't',
											'у' => 'u', 'ў' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'ts',
											'ч' => 'ch', 'џ' => 'dh', 'ш' => 'sh', 'щ' => 'sht', 'ъ' => '',
											'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu', 'я' => 'ya'
										);
										$iso9_table = array_merge($iso9_table, $iso9_table1);
										$geo2lat = array(
											'ა' => 'a', 'ბ' => 'b', 'გ' => 'g', 'დ' => 'd', 'ე' => 'e', 'ვ' => 'v',
											'ზ' => 'z', 'თ' => 'th', 'ი' => 'i', 'კ' => 'k', 'ლ' => 'l', 'მ' => 'm',
											'ნ' => 'n', 'ო' => 'o', 'პ' => 'p','ჟ' => 'zh','რ' => 'r','ს' => 's',
											'ტ' => 't','უ' => 'u','ფ' => 'ph','ქ' => 'q','ღ' => 'gh','ყ' => 'qh',
											'შ' => 'sh','ჩ' => 'ch','ც' => 'ts','ძ' => 'dz','წ' => 'ts','ჭ' => 'tch',
											'ხ' => 'kh','ჯ' => 'j','ჰ' => 'h'
										);
										$iso9_table = array_merge($iso9_table, $geo2lat);
										$title = strtr($title, apply_filters('ctl_table', $iso9_table));
										if (function_exists('iconv')){
											$title = iconv('UTF-8', 'UTF-8//TRANSLIT//IGNORE', $title);
										}
										$slug = apply_filters('name_save_pre',$title );
										$slug = apply_filters('sanitize_title', $slug,$slug,'save');
										$slug = sanitize_title_with_dashes($slug,'','save');
										$slug = wp_unique_post_slug( $slug, $ID, 'publish', 'product', 0);
										$date_gmt = get_gmt_from_date($obj->post_date);
										$query = "UPDATE {$posts} SET post_name='{$slug}',post_status='publish',post_date_gmt='{$date_gmt}' WHERE ID={$ID}";
										$wpdb->query($query);
//										if($slug != $Row)
										{
											$newvar = new stdClass();
											$newvar->ID = (string)$ID;
											$newvar->post_name = $slug;
											$permalink = get_permalink($ID);
											$newvar->_product_permalink = "";
											if(false !== $permalink)
											{
												$newvar->_product_permalink = $permalink;
											}
											$retarray[] = $newvar;
										}
									}
								}else
								{
									$query = "UPDATE {$posts} SET post_status='".$Row."' WHERE ID={$ID}";
									$wpdb->query($query);
								}
							}else
							{
								$query = "UPDATE {$posts} SET post_status='".$Row."' WHERE ID={$ID}";
								$wpdb->query($query);
							}
							if($ret === 'product_variation')
							{
//								$modified_date = date_i18n( 'Y-m-d H:i:s', current_time( 'timestamp' ) );
//
//								$wpdb->update( $wpdb->posts, array(
//										'post_status'       => $Row,
//										'post_modified'     => $modified_date,
//										'post_modified_gmt' => get_gmt_from_date( $modified_date )
//								), array( 'ID' => $ID ) );	
//								clean_post_cache( $ID );

//								do_action( 'woocommerce_update_product_variation', $ID );
							}
							
							if($bcallaction)
							{
								wp_transition_post_status($Row,$old_status,$post);
								if($parentid > 0 && $ret === 'product')
								{
									$transient_name = 'wc_product_children_' . $parentid;
					        		$args = apply_filters( 'woocommerce_grouped_children_args', array(
					        			'post_parent' 	=> $parentid,
					        			'post_type'		=> 'product',
					        			'orderby'		=> 'menu_order',
					        			'order'			=> 'ASC',
					        			'fields'		=> 'ids',
					        			'post_status'	=> 'publish',
					        			'numberposts'	=> -1,
					        		) );

							        $children = get_posts( $args );

									set_transient( $transient_name, $children, DAY_IN_SECONDS * 30 );
								}
							}
						}break;
						case "_sale_price_dates_from":
						{
							$value = strtotime($Row);
							update_post_meta( $ID , $i, $value);
						}break;
						case "_sale_price_dates_to":
						{
							$value = strtotime($Row);
							update_post_meta( $ID , $i, $value);
						}break;
						case "_tax_class":
						{
							$class = "";
							if(count($tax_classes) === 0)
								{
								if($Row == "Reduced Rate")
									$class= "reduced-rate";
								if($Row == "Zero Rate")
									$class = "zero-rate";
							}else
							{
								foreach($tax_classes as $key => $value)
								{
									if($value === $Row)
									{
										$class = $key;
										break;
									}
								}
							}
							update_post_meta( $ID , $i, $class);
						}break;
						case "_tax_status":
						{
							$class = "taxable";
							if($Row == "Shipping only")
								$class= "shipping";
							if($Row == "None")
								$class = "none";
							update_post_meta( $ID , $i, $class);
						}break;
						case "_sold_individually":
						{
							$back = "";
							if($Row == "no")
								$back = "";
							if($Row == "yes")
								$back = "yes";
							update_post_meta( $ID , $i, $back);
						}break;
						case "_backorders":
						{
							$back = "no";
							if($Row == "Do not allow")
								$back = "no";
							if($Row == "Allow but notify")
								$back = "notify";
							if($Row == "Allow")
								$back = "yes";
							update_post_meta( $ID , $i, $back);
						}break;
						case "_default_attributes":
						{
							 $def_attrs = array();
							 $cur_attr = array();
							 $all_attrs = explode(';',$Row);
							 if(is_array($all_attrs) && count($all_attrs) > 0)
							 {
							 	 for($i = 0; $i < count($all_attrs); $i++)
								 {
								 	$itemsarr = $all_attrs[$i];
									$itemsarr = trim($itemsarr);
									if(!isset($itemsarr) || $itemsarr === "") continue;
								  	  $items =  explode(',',$itemsarr);
									  $name = "";
									  if(!is_array($items)) continue;
									  $cur_attr = array();
									  for($j = 0; $j < count($items); $j++)
								  	  {
									      $item = $items[$j];	
										  if(!isset($item) || $item === "") continue;
										  if($j == 0)
										  {//name
										  	   $name = $item;
										  }else
										  {//url
										  	if($item != "")
											{
										  	  $def_attrs[$name] = $item;
											}
										  }
									  }
								  }
							 }
							update_post_meta( $ID , '_default_attributes', $def_attrs );
						}break;
						case "_custom_attributes":
						{
							$query = "SELECT post_type FROM {$posts} WHERE ID={$ID}";
							$ret = $wpdb->get_var($query);
							if($ret === 'product')
							{
								 $attributessave = array();
								 $attributessave = explode('*****',$Row);
								 $patt = get_post_meta($ID,'_product_attributes',true);
								$taxonomy_slug = "";
								if(!is_array($patt))
								{
									$patt = array();
								}
								 $attrsaved = array();
								 if($attributessave)
								 {
								 	 for($i = 0; $i < count($attributessave); $i++)
									 {
									 	$itemsarr = $attributessave[$i];
									 	$insertarr = array();
										if(!isset($itemsarr) || $itemsarr === "") continue;
									  	  $items =  explode('#####',$itemsarr);
										  for($j = 0; $j < count($items); $j++)
									  	  {
										      $item = $items[$j];	
											  if(!isset($item) || $item === "") continue;
											  switch($j)
											  {
											  	case 0:{//name
													$taxonomy_slug = sanitize_title($item);
													$insertarr["name"] = $item;
												}break;
											  	case 1:{//value
													$insertarr["value"]  = $item;
												}break;
												case 2:{//is_visible
													$insertarr["is_visible"] = (int)$item;
												}break;
												case 3:{//is_variation
													$insertarr["is_variation"] = (int)$item;
												}break;
											  	default:
											  		break;
											 
										  		}
										  }
										  if(isset($patt[$taxonomy_slug]))
										 {
										 	$patt[$taxonomy_slug]["name"] = $insertarr["name"];
										 	$patt[$taxonomy_slug]["value"] = $insertarr["value"];
										 	$patt[$taxonomy_slug]["is_visible"] = $insertarr["is_visible"];
										 	$patt[$taxonomy_slug]["is_variation"] = $insertarr["is_variation"];
										 	$patt[$taxonomy_slug]["is_taxonomy"] = 0;
//											$patt[$taxonomy_slug]["position"] = count($patt);
										 }else
										 {
										 	$patt[$taxonomy_slug] = array();
										 	$patt[$taxonomy_slug]["name"] = $insertarr["name"];
										 	$patt[$taxonomy_slug]["value"] = $insertarr["value"];
										 	$patt[$taxonomy_slug]["is_visible"] = $insertarr["is_visible"];
										 	$patt[$taxonomy_slug]["is_variation"] = $insertarr["is_variation"];
										 	$patt[$taxonomy_slug]["is_taxonomy"] = 0;
										 	$patt[$taxonomy_slug]["position"] = count($patt);
										 }
										 $attrsaved[$taxonomy_slug] = 1;
										 
									  }
								 }else
								 {
 											$items =  explode('#####',$itemsarr);
										  for($j = 0; $j < count($items); $j++)
									  	  {
										      $item = $items[$j];	
											  if(!isset($item) || $item === "") continue;
											  switch($j)
											  {
											  	case 0:{//name
													$taxonomy_slug = sanitize_title($item);
													$insertarr["name"] = $item;
												}break;
											  	case 1:{//value
													$insertarr["value"]  = $item;
												}break;
												case 2:{//is_visible
													$insertarr["is_visible"] = (int)$item;
												}break;
												case 3:{//is_variation
													$insertarr["is_variation"] = (int)$item;
												}break;
											  	default:
											  		break;
											 
										  		}
										  }
										  if(isset($patt[$taxonomy_slug]))
										 {
										 	$patt[$taxonomy_slug]["name"] = $insertarr["name"];
										 	$patt[$taxonomy_slug]["value"] = $insertarr["value"];
										 	$patt[$taxonomy_slug]["is_visible"] = $insertarr["is_visible"];
										 	$patt[$taxonomy_slug]["is_variation"] = $insertarr["is_variation"];
										 	$patt[$taxonomy_slug]["is_taxonomy"] = 0;
//											$patt[$taxonomy_slug]["position"] = count($patt);
										 }else
										 {
										 	$patt[$taxonomy_slug] = array();
										 	$patt[$taxonomy_slug]["name"] = $insertarr["name"];
										 	$patt[$taxonomy_slug]["value"] = $insertarr["value"];
										 	$patt[$taxonomy_slug]["is_visible"] = $insertarr["is_visible"];
										 	$patt[$taxonomy_slug]["is_variation"] = $insertarr["is_variation"];
										 	$patt[$taxonomy_slug]["is_taxonomy"] = 0;
										 	$patt[$taxonomy_slug]["position"] = count($patt);
										 }
										 $attrsaved[$taxonomy_slug] = 1;
									  
								 }
								foreach($patt as $attrin => $attrval)
								{
									 if(isset($attrval["is_taxonomy"]) && $attrval["is_taxonomy"] === 0)
									 {
									 	if(!isset($attrsaved[$attrin]))
									 	{
											unset($patt[$attrin]);
										}
//									 	for($j = 0; $j < count($attributes); $j++)
//										{
//											 $attr = $attributes[$j];
//											 if($attr['name'] === $attrin)
//											 	break;
//										}
									 }
								}
								update_post_meta($ID,'_product_attributes',$patt);
								self::RefreshCustMetaKeys($ID,$attrsaved,$patt,true);
							}else
							{//variation
								 $attributessave = array();
								 $attributessave = explode('*****',$Row);
								 $attrsaved = array();
								 if($attributessave)
								 {
								 	 for($i = 0; $i < count($attributessave); $i++)
									 {
									 	$itemsarr = $attributessave[$i];
									 	$insertarr = array();
									 	$attrslug = "attribute_";
									    $attrvalue = "";
										if(!isset($itemsarr) || $itemsarr === "") continue;
									  	  $items =  explode('#####',$itemsarr);
										  for($j = 0; $j < count($items); $j++)
									  	  {
										      $item = $items[$j];	
											  if(!isset($item) || $item === "") continue;
											  switch($j)
											  {
											  	case 0:{//name
												$attrslug.= $item;
												}break;
											  	case 1:{//value
													$attrvalue = $item;
												}break;
											  	default:
											  		break;
											 
										  		}
										  }
										  $attrsaved[$attrslug] = 1;
										  update_post_meta($ID,$attrslug,$attrvalue);
									  }
								 }else
								 {
									  $items =  explode('#####',$itemsarr);
									  $attrslug = "attribute_";
									  $attrvalue = "";
									  for($j = 0; $j < count($items); $j++)
								  	  {
									      $item = $items[$j];	
										  if(!isset($item) || $item === "") continue;
										  switch($j)
										  {
										  	case 0:{//name
												$attrslug.= $item;
											}break;
										  	case 1:{//value
												$attrvalue = $item;
											}break;
										  	default:
										  		break;
										 
									  		}
									  }
									  $attrsaved[$attrslug] = 1;
									  update_post_meta($ID,$attrslug,$attrvalue);
								 }
								 self::RefreshCustMetaKeys($ID,$attrsaved,$attributes,false,$parentid);
							}
						}break;
						case "_stock_status":
						{
							if(self::$isversion3)
							{
								if(function_exists('wc_update_product_stock_status'))
								{
									wc_update_product_stock_status( $ID, $Row );
//									update_post_meta( $ID , $i, $Row);
								}else
								{
									if($Row === 'outofstock')
									{
										wp_set_object_terms($ID,$arr_prod_vis['outofstock'],'product_visibility',true);
									}
									update_post_meta( $ID , $i, $Row);
								}
								
							}else
							{
								if(function_exists('wc_update_product_stock_status'))
								{
									wc_update_product_stock_status( $ID, $Row );
								}else
								{
									update_post_meta( $ID , $i, $Row);
								}
							}
						}break;
						case "_stock":
						{
							if(function_exists('wc_update_product_stock'))
							{
								wc_update_product_stock( $ID, $Row);
							}else
							{
								update_post_meta( $ID , $i, $Row);
							}
//							if(intval($Row) > 0)
							{
//								$status = get_post_meta($ID,'_stock_status');
//								if($status !== 'outofstock')
//								update_post_meta( $ID , '_stock_status', 'instock');
//								wc_update_product_stock_status( $ID, 'instock' );
							}
						}
						default:
						{
							if($i !== 'ID' && $i !== 'post_parent' && $i !== 'parent')
							{
								if(strpos($i,"attribute_pa_",0) === 0 && strpos($i,"_visiblefp",0) === FALSE)
								{
//									return $i;
									self::HandleAttrs($ID,$parentid,$parentattrs_cache,$attributes,$Row,$i,count($data),$update_parent_attr);
								}elseif(strpos($i,"attribute_pa_",0) === 0 && strpos($i,"_visiblefp",0) !== FALSE)
								{
									$query = "SELECT post_type FROM {$wpdb->posts} WHERE ID={$ID}";
									$ret = $wpdb->get_var($query);
//									$arrret['i'] = $i;
									if($ret === 'product')//check by post_type if($parentid == 0)
									{
										$patt = get_post_meta($ID,'_product_attributes',true);
										$taxonomy_slug = "";
										$pos = strpos($i,"attribute_");
										if ($pos !== false) {
										    $taxonomy_slug = substr_replace($i,"",$pos,strlen("attribute_"));
										}
										$taxonomy_slug = str_replace('_visiblefp','',$taxonomy_slug);
										$hasitems = false;
										foreach($arrrow as $i1 => $Row1)
										{
											if(strpos($i1,$taxonomy_slug,0) !== FALSE)
											{
												$hasitems = true;
												break;
											}
										}
										if(!is_array($patt))
											$patt = array();
										$new_taxonomy_slug = sanitize_title( $taxonomy_slug );
										if($hasitems)
										{
											if(!isset($patt[$new_taxonomy_slug]))
											 {
											 	$patt[$new_taxonomy_slug] = array();
												$patt[$new_taxonomy_slug]["name"] = $taxonomy_slug;
												$patt[$new_taxonomy_slug]["is_visible"]   = 0;
												$patt[$new_taxonomy_slug]["is_taxonomy"]  = 1;
												$patt[$new_taxonomy_slug]["is_variation"] = 0;
												$patt[$new_taxonomy_slug]["value"]  = "";
												$patt[$new_taxonomy_slug]["position"] = count($patt);
											 }
										}
										if(is_array($patt))
										{
											if(isset($patt[$new_taxonomy_slug]))
										 	{
										 		$val = (int)$Row;
											 	if($val & 1)
													$patt[$new_taxonomy_slug]["is_visible"]   = 1;
												else
													$patt[$new_taxonomy_slug]["is_visible"]   = 0;
												if($val & 2)
													$patt[$new_taxonomy_slug]["is_variation"]   = 1;
												else
													$patt[$new_taxonomy_slug]["is_variation"]   = 0;													
												update_post_meta($ID,'_product_attributes',$patt);
										 	}
										}

									}
									
								}else
								{
									if( strpos($Row,":",0) !== FALSE && strpos($Row,";",0) !== FALSE &&strpos($Row,"{",0) !== FALSE &&strpos($Row,"}",0) !== FALSE)
									{
										$query = "SELECT meta_id FROM {$meta} WHERE post_id={$ID} AND meta_key='{$i}'";
										$ret = $wpdb->get_var($query);
										if($ret === NULL)
										{
											$query = "INSERT INTO {$meta} (post_id,meta_key,meta_value)
							 					 VALUES ({$ID},'{$i}','{$Row}');";
											$ret = $wpdb->query($query);
										}else
										{
											$query = "UPDATE {$meta} SET meta_value='".$Row."' WHERE meta_id={$ret}";
											$wpdb->query($query);
										}
									}else
									{
										update_post_meta( $ID , $i, $Row); //sanitize_text_field
									}
								}
							}
						}
							break;
					}
				}
//				if($parentid > 0)
				{
					if(array_key_exists('_stock_status',$arrrow) || array_key_exists('_manage_stock',$arrrow) || array_key_exists('_stock',$arrrow))
					{
						
						if(function_exists("wc_delete_product_transients"))
						{
							wc_delete_product_transients($ID);
							if($parentid > 0)
								wc_delete_product_transients($parentid);
						}
						if(!self::$isversion3)
						{
							$newvar = new stdClass();
						$newvar->ID = (string)$ID;
						$newvar->_stock_status = get_post_meta($ID,'_stock_status',true);
						$retarray[] = $newvar;	
						}
						
					}
					if(array_key_exists('post_status',$arrrow))
					{
						
						if(function_exists("wc_delete_product_transients"))
						{
							wc_delete_product_transients($ID);
							if($parentid > 0)
								wc_delete_product_transients($parentid);
						}
					}
				}
				if(array_key_exists('_featured',$arrrow))
				{
					delete_transient( 'wc_featured_products' );
				}
				
				if(array_key_exists('_sale_price',$arrrow) || array_key_exists('_regular_price',$arrrow) || array_key_exists('_sale_price_dates_from',$arrrow) || array_key_exists('_sale_price_dates_to',$arrrow))
				{
					self::HandlePriceUpdate($ID,$parentid,$arrrow);	
					if($parentid > 0)
					{
//						if(function_exists("wc_delete_product_transients"))
//							wc_delete_product_transients($parentid);
						if(!in_array($parentid,$update_vars_price))
							$update_vars_price[] = $parentid;
					}else
					{
						if(function_exists("wc_delete_product_transients"))
						   wc_delete_product_transients($ID);
					}
				
				}
				clean_post_cache($ID);
				self::CallWooAction($ID,$oldpost,$arrrow);
				
			}
		}
		
		foreach($update_vars_price as $item_id)
		{
			self::HandleSaleRemove($item_id);
		}
				
		$attrarrays = array();
		if(is_array($attributes) && !empty($attributes))
		{
			foreach($attributes as $attr)
			{
				if(!property_exists($attr,'name'))
					continue;
				$attrarrays[] = 'pa_'.$attr->name;
		    }
		}
		$bdontcheckusedfor = false;
		$curr_settings = get_option('w3exabe_settings');
		if(is_array($curr_settings))
		{
			if(isset($curr_settings['dontcheckusedfor']))
			{
				if($curr_settings['dontcheckusedfor'] == 1)
					$bdontcheckusedfor = true;
			}
		}
		self::WriteDebugInfo("loop number ","","before attr refresh");
		foreach($data as $arrrow)
		{
			if(!is_array($arrrow)) continue;
			$ID = 0;
			if(array_key_exists('ID',$arrrow))
			{
				$ID = (int)$arrrow['ID'];
			
				$parentid = 0;
				if(array_key_exists('post_parent',$arrrow))
					$parentid = (int)$arrrow['post_parent'];
				if($parentid != 0) continue;
				$updatemeta = false;
				
				foreach($arrrow as $i => $Row)
				{
					if(strpos($i,"attribute_pa_",0) === 0)
					{
						$updatemeta = true;
						break;
					}
				}
				if($updatemeta)
				{
					$bvariable = false;
					if(is_object_in_term( $ID, 'product_type', 'variable' ))
						$bvariable = true;
						
					{
						$patt = get_post_meta($ID,'_product_attributes',true);
						if(!is_array($patt))
							$patt = array();
						$attrs = wp_get_object_terms($ID,$attrarrays);
						if(is_array($attrs))
						{
//							foreach($patt as $key => $value)
//							{
//								$haskey = false;
//								foreach($attrs as $attr_obj)
//								{
//									if(!is_object($attr_obj)) continue;
//									if(!property_exists($attr_obj,'taxonomy')) continue;
//									if($key == $attr_obj->taxonomy)
//									{
//										$haskey = true;
//										break;
//									}
//								}
//								if(!$haskey)
//								{
//									unset($patt[$key]);
//								}
//							}
							$existing = array();
							foreach($attrs as $attr_obj)
							{
								if(!is_object($attr_obj)) continue;
								if(!property_exists($attr_obj,'term_id')) continue;
								if(!property_exists($attr_obj,'taxonomy')) continue;
								$new_taxonomy_slug = sanitize_title( $attr_obj->taxonomy );
								if(!in_array($new_taxonomy_slug,$existing))
									$existing[] = $new_taxonomy_slug;
								if(!isset($patt[$new_taxonomy_slug]))
								{
									$patt[$new_taxonomy_slug] = array();
									$patt[$new_taxonomy_slug]["name"] = $attr_obj->taxonomy;
									$patt[$new_taxonomy_slug]["is_visible"]   = 0;
									$patt[$new_taxonomy_slug]["is_taxonomy"]  = 1;
									if($bvariable && !$bdontcheckusedfor)
										$patt[$new_taxonomy_slug]["is_variation"] = 1;
									else
										$patt[$new_taxonomy_slug]["is_variation"] = 0;
									$patt[$new_taxonomy_slug]["value"]  = "";
									$patt[$new_taxonomy_slug]["position"] = count($patt);
								}
							}
//							if(count($attrs) === 0)
							{//check for deleted terms
								foreach($patt as $patt_name => $patt_item)
								{
									if(is_array($patt_item))
									{
										if(isset($patt_item["is_taxonomy"]) && $patt_item["is_taxonomy"] == 1)
										{
											if(!in_array($patt_name,$existing))
											 unset($patt[$patt_name]);
										}
											
									}
								}
							}
							update_post_meta($ID,'_product_attributes',$patt);
							self::CallWooAction($ID);
						}
					}
				}
				
			}
		}
		foreach($update_parent_attr as $parid => $attrarrays)
		{
			$newpar = new stdClass();
			$newpar->ID = $parid;
			$newpar->post_parent = "0";
			$attrs = wp_get_object_terms($parid,$attrarrays);
			
			if(is_array($attrs))
			{
				foreach($attrs as $attr_obj)
				{
					if(!is_object($attr_obj)) continue;
					if(!property_exists($attr_obj,'term_id')) continue;
					if(!property_exists($attr_obj,'name')) continue;
					if(!property_exists($attr_obj,'taxonomy')) continue;
					$attr_prop = 'attribute_'.$attr_obj->taxonomy;
					if(!property_exists($newpar,$attr_prop))
					{
						$newpar->{$attr_prop} = $attr_obj->name;
						self::UpdateParentMeta($parid,$attr_obj->taxonomy);
						$newpar->{$attr_prop . '_visiblefp'} = 2;
					}else
					{
						$newpar->{$attr_prop} = $newpar->{$attr_prop}.', '. $attr_obj->name;
					}
					$attr_ids = 'attribute_'.$attr_obj->taxonomy.'_ids';
					if(!property_exists($newpar,$attr_ids))
					{
						$newpar->{$attr_ids} = (string)$attr_obj->term_id;
					}else
					{
						$newpar->{$attr_ids} = $newpar->{$attr_ids}.','.(string)$attr_obj->term_id;
					}
				}
				
			}
			$retarray[] = $newpar;
		}
		self::WriteDebugInfo("loop number ","","after attr refresh");
		return $retarray;
	}
	
	public static function RefreshCustMetaKeys($ID,&$attrsaved,&$attributes,$bproduct,$parentid = 0)
	{
		global $wpdb;
		$posts = $wpdb->posts;
		$meta = $wpdb->postmeta;
		if($bproduct)
		{
			$query = "SELECT ID from {$posts} WHERE post_parent={$ID} AND (post_type='product_variation')";
			$childids =  $wpdb->get_results($query);
			if(!is_wp_error($childids) && is_array($childids))
			{
				foreach($childids as $childobj)
				{
					$post_meta = $wpdb->get_results("SELECT meta_key, meta_value FROM {$meta} WHERE post_id={$childobj->ID} AND meta_key LIKE 'attribute_%';");

					if ( count( $post_meta ) != 0 ) 
					{

						foreach ( $post_meta as $meta_info ) 
						{
							$meta_key = $meta_info->meta_key;
							$has = false;
							foreach($attributes as $attrin => $attrval)
							{
								 $attrslug = "";
								 if(isset($attrval["is_taxonomy"]) && $attrval["is_taxonomy"] === 0)
								 {
								 	$attrslug = 'attribute_'.$attrin;
								 	if($attrslug === $meta_key)
								 	{
										$has = true;
										break;
									}
								 }elseif(isset($attrval["is_taxonomy"]) && $attrval["is_taxonomy"] === 1)
								 {
								 	$attrslug = 'attribute_'.$attrin;
								 	if($attrslug === $meta_key)
								 	{
										$has = true;
										break;
									}
								 }
							}
							if(!$has)
							{
								delete_post_meta($childobj->ID,$meta_key);
							}
						}
						
					}
				}
			}
		}else
		{
			
			$post_meta = $wpdb->get_results("SELECT meta_key, meta_value FROM {$meta} WHERE post_id={$ID} AND meta_key LIKE 'attribute_%';");
			$patt = get_post_meta($parentid,'_product_attributes',true);
			if ( count( $post_meta ) != 0 ) 
			{

				foreach ( $post_meta as $meta_info ) 
				{
					$meta_key = $meta_info->meta_key;
					$has = false;
					foreach($patt as $attrin => $attrval)
					{
						 $attrslug = "";
						 if(isset($attrval["is_taxonomy"]) && $attrval["is_taxonomy"] === 0)
						 {
						 	$attrslug = 'attribute_'.$attrin;
						 	if($attrslug === $meta_key)
						 	{
								$has = true;
								break;
							}
						 }elseif(isset($attrval["is_taxonomy"]) && $attrval["is_taxonomy"] === 1)
						 {
						 	$attrslug = 'attribute_'.$attrin;
						 	if($attrslug === $meta_key)
						 	{
								$has = true;
								break;
							}
						 }
					}
					if(!$has)
					{
						delete_post_meta($ID,$meta_key);
					}
				}
				
			}
					
		}
	}
	
	
	public static function HandlePriceUpdate($ID,$parentid,&$arrrow)
	{
		$saleprice = 0;
		$regprice = 0;
		$regpricestring = "";
		$salepricestring = "";
		$salefrom = "";
		$saleto = "";
		if(array_key_exists('_sale_price',$arrrow))
		{
			$saleprice = (float)$arrrow['_sale_price'];
			$salepricestring = $arrrow['_sale_price'];
		}else
		{
			$saleprice = (float)get_post_meta($ID,'_sale_price',true);
			$salepricestring = get_post_meta($ID,'_sale_price',true);
		}
		if(array_key_exists('_regular_price',$arrrow))
		{
			$regprice = (float)$arrrow['_regular_price'];
			$regpricestring = get_post_meta($ID,'_regular_price',true);
		}else
		{
			$regpricestring = get_post_meta($ID,'_regular_price',true);
			$regprice = (float)get_post_meta($ID,'_regular_price',true);
		}
		
		if($saleprice > 0)
		{
			if(array_key_exists('_sale_price_dates_from',$arrrow))
			{
				$salefrom = $arrrow['_sale_price_dates_from'];
			}else
			{
				$salefrom = get_post_meta($ID,'_sale_price_dates_from',true);
				if($salefrom != "")
				{
					$salefrom = maybe_unserialize($salefrom);
					$salefrom = date('Y-m-d',$salefrom);
				}
			}
			if(array_key_exists('_sale_price_dates_to',$arrrow))
			{
				$saleto = $arrrow['_sale_price_dates_to'];
			}else
			{
				$saleto = get_post_meta($ID,'_sale_price_dates_to',true);
				if($saleto != "")
				{
					$saleto = date('Y-m-d',(float)$saleto);
				}
			}
			if($salefrom !== "")
			{
				$dt = time();
				$salefromd = strtotime($salefrom);//date('Y-m-d', $salefrom);
				if($saleto !== "")
				{
					$saletod = strtotime($saleto);
					if($salefromd <= $dt && $saletod >= $dt)
					{
						update_post_meta($ID,'_price',$salepricestring);
						return;
					}else
					{
						update_post_meta($ID,'_price',$regpricestring);
						return;
					}
				}
			}
			if($saleto !==  "")
			{
				$dt = time();
				$saletod = strtotime($saleto);
				if($saletod >= $dt)
				{
					update_post_meta($ID,'_price',$salepricestring);
					return;
				}else
				{
					update_post_meta($ID,'_price',$regpricestring);
					return;
				}
			}
			update_post_meta($ID,'_price',$salepricestring);
			return;
		}
		update_post_meta($ID,'_price',$regpricestring);
	}
	
	
	public static function addProducts($prodcount)
	{
		global $wpdb;
		$posts = $wpdb->posts;
		$meta = $wpdb->postmeta;
		$temptable = $wpdb->prefix."wpmelon_advbedit_temp";
		$term = $wpdb->term_relationships;
		$retarray = array();
		
		$insfields = array(
			"_sku"  => "",
   			"_virtual"   => "no",
			"_downloadable"  => "no",
			"_manage_stock"   => "no",
			"_stock_status"   => "instock",
			"_visibility" => "visible",
			"total_sales" => "0",
			"_purchase_note" => "",
			"_featured" => "no",
			"_backorders" => "no",
			"_sold_individually" => "",
			"_product_image_gallery" => "",
			"_regular_price"   => "",
			"_sale_price"   => "",
			"_price"   => ""
		);
		
		$product_data = array();
		$product_data['post_status'] = 'draft';
		$product_data['post_title'] = 'New Product';
		$product_data['post_type'] = 'product';			
		$product_data['post_parent'] = 0;
		$product_data['post_author']  = get_current_user_id();
		$prod_term = get_term_by('slug','simple','product_type');
		for($i = 0; $i < $prodcount; $i++)
		{
			$post_id = wp_insert_post($product_data,true);
			if(is_wp_error($post_id))
			{
				return $post_id;
			}
			
			wp_set_object_terms($post_id,'simple','product_type',true);
			
			update_post_meta($post_id,'_product_attributes',array());
			
			$newvar = new stdClass();
			$newvar->ID = (string)$post_id;
			$newvar->post_parent = '0';
			if(property_exists($prod_term,'term_id'))
			{
				$newvar->product_type = 'simple';
				$newvar->product_type_ids =(string)$prod_term->term_id;
			}
			$newvar->post_type = 'product';
			
			foreach($insfields as $column => $value)
			{
				$query = "INSERT INTO {$meta} (post_id,meta_key,meta_value)
					  VALUES ({$post_id},'{$column}','{$value}');";
			
				$ret = $wpdb->query($query);
				if ( is_wp_error($ret) )
				{
					return $ret;
				} 
			}

			foreach($insfields as $column => $value)
			{
				$newvar->{$column} = $value;
			}
			do_action( 'woocommerce_api_create_product', $post_id, $insfields ); 
			$newvar->post_title = 'New Pos';
			$newvar->post_status = 'draft';
			$newvar->menu_order = '0';
			$retarray[] = $newvar;
		}
		
		
		return $retarray;
	}
	
	public static function getVariations($ID,&$arrvars)
	{
		global $wpdb;
		$posts = $wpdb->posts;
		$meta = $wpdb->postmeta;
		$query = "SELECT ID from {$posts} WHERE post_parent={$ID} AND (post_type='product_variation')";
		$childids =  $wpdb->get_results($query);
		if(!is_wp_error($childids) && is_array($childids))
		{
			foreach($childids as $childobj)
			{
				$post_meta = $wpdb->get_results("SELECT meta_key, meta_value FROM {$meta} WHERE post_id={$childobj->ID} AND meta_key LIKE 'attribute_%';");

				if ( count( $post_meta ) != 0 ) 
				{
					$arrvalues = array();
					foreach ( $post_meta as $meta_info ) 
					{
//						$meta_key = $meta_info->meta_key;
						$arrvalues[$meta_info->meta_key] = $meta_info->meta_value;
					}
					ksort($arrvalues);
					$arrvars[$childobj->ID] = implode("", $arrvalues);
				}
			}
		}	
	}
	
	public static function addVariations(&$data,&$children,&$currentpos,&$batchnumber,$skipdups = true)
	{
		global $wpdb;
		$posts = $wpdb->posts;
		$meta = $wpdb->postmeta;
		$temptable = $wpdb->prefix."wpmelon_advbedit_temp";
		$term = $wpdb->term_relationships;
		$retarray = array();
		$attributes = array();
		$attrmapslugtoname = array();
		$foundattributes = array();
		$parentattrs_cache = array();
		$update_parent_attr = array();
		
		$attributekeys = array();
		
		$parentid = 0;
		
		$menu_order = 0;
		
		$arr_handled_attr = array();
		$arrvars = array();
		
		$insfields = array(
			"_sku"  => "",
   			"_thumbnail_id" => "0",
   			"_virtual"   => "no",
			"_downloadable"  => "no",
			"_manage_stock"   => "no",
			"_stock_status"   => "instock",
			"_regular_price"   => "",
			"_sale_price"   => "",
			"_price"   => ""
		);
		
		$madevarparents = array();
		$currentparent = -1;
		$rowstoskip = -1;
		$counter = 0;
		$processcounter = 0;
//		if($currentpos !== -1)
//		{
//			$rowstoskip = $currentpos * $batchnumber;
//			if($rowstoskip >= count($data))
//			{
//				$rowstoskip = -1;
//			}
//			$currentpos++;
//		}
		
	
			
		foreach($data as $varrow)
		{
			//create variation
			if(array_key_exists('post_parent',$varrow[0]))
				$parentid = (int)$varrow[0]['post_parent'];
			if($parentid == 0)
			{
				return new WPError('Invalid Parent');
			}
			$counter++;
			
//			if($rowstoskip !== -1)
//			{
//				if($counter <= $rowstoskip)
//					continue;
//				if($processcounter < $batchnumber)
//				{
//					$processcounter++;
//				}else
//				{
//					continue;
//				}
//			}
			
			$hasdup = false;
			$arrvalues = array();
			
			if($skipdups)
			{
				if($currentparent != $parentid)
				{
					$arrvars = array();
					self::getVariations($parentid,$arrvars);
					$currentparent = $parentid;
				}
				
				foreach($varrow as $arrrow1)
				{
					if(!is_array($arrrow1)) continue;
					$attrname = sanitize_title($arrrow1['attribute']);
					$attvalue = $arrrow1['value'];
					$attvalue = str_replace('\"','"',$attvalue);
					$attvalue = str_replace("\'","'",$attvalue);
					$arrvalues[$attrname] = $attvalue;
				}
				ksort($arrvalues);
				$varstring = implode("", $arrvalues);
				foreach($arrvars as $key => $value)
				{
					if($varstring === $value)
					{
						$hasdup = true;
						break;
					}
				}
				
				if($hasdup)
					continue;
			}
		
				
			//make sure it is variable
			if(!array_key_exists($parentid,$madevarparents))
			{
				if(is_object_in_term( $parentid, 'product_type', 'simple' ) || is_object_in_term( $parentid, 'product_type', '' ))
				{//convert only simple
					wp_set_object_terms($parentid,'variable','product_type',false);
				}
				$query = "SELECT COUNT({$posts}.ID) FROM {$posts} WHERE post_parent={$parentid} AND post_type='product_variation';";
				$ret = $wpdb->get_var($query);
				$menu_order = 0;
				if ( !is_wp_error($ret) )
				{
					$menu_order = (int)$ret;
				} 
				if(function_exists("wc_delete_product_transients"))
					wc_delete_product_transients($parentid);
				$madevarparents[$parentid] = $menu_order;
				
			}
			
		
			
			
			$product_data = array();
			$menu_order = $madevarparents[$parentid];
			$product_data['menu_order'] = $madevarparents[$parentid];
			$menu_order++;
			$madevarparents[$parentid] = $menu_order;
			$product_data['post_status'] = 'publish';
			$product_data['post_title'] = 'Variation #'.$parentid.' of ';
			$product_data['post_type'] = 'product_variation';			
			$product_data['post_parent'] = $parentid;
			$product_data['post_author'] = get_current_user_id();
			$post_id = wp_insert_post($product_data,true);
			if(is_wp_error($post_id))
			{
				return $post_id;
			}
			do_action( 'woocommerce_create_product_variation', $post_id );
			$newvar = new stdClass();
			$newvar->ID = (string)$post_id;
			$newvar->post_parent = (string)$parentid;
			$newvar->post_type = 'product_variation';
			$attributename = '';
			
			
				
			foreach($varrow as $arrrow)
			{
				if(!is_array($arrrow)) continue;
				$attrname = sanitize_title($arrrow['attribute']);
				$attvalue = $arrrow['value'];
				$attvalue = str_replace('\"','"',$attvalue);
				$attvalue = str_replace('\'',"'",$attvalue);
				$query = "INSERT INTO {$meta} (post_id,meta_key,meta_value)
							  VALUES ({$post_id},'{$attrname}','{$attvalue}');";
					
				$ret = $wpdb->query($query);
				if ( is_wp_error($ret) )
				{
					return $ret;
				} 
			}
			foreach($insfields as $column => $value)
			{
				
				$query = "INSERT INTO {$meta} (post_id,meta_key,meta_value)
					  VALUES ({$post_id},'{$column}','{$value}');";
			
				$ret = $wpdb->query($query);
				if ( is_wp_error($ret) )
				{
					return $ret;
				} 
			}
//			if($attributename == '')
//				$attributename = $product_data['post_title'];
			foreach($insfields as $column => $value)
			{
				$newvar->{$column} = $value;
			}
			$oldattr = '';
			foreach($varrow as $arrrow)
			{
				if(!is_array($arrrow)) continue;
				$attrname = $arrrow['attribute'];
//				if($attrname !== $oldattr)
				if(!isset($foundattributes[$attrname]))
				{
					$foundattributes[$attrname] = "1";
//					$attrmapslugtoname = array();
//					$attributes = array();
					self::GetAttributes($attributes,$attrmapslugtoname,true,$arrrow['attribute']);
				}
				$attvalue = $arrrow['value'];
				$attvalue = str_replace('\"','"',$attvalue);
				$attvalue = str_replace("\'","'",$attvalue);
				
				if($attvalue != '')
				{
					if(isset($attrmapslugtoname[$attrname.$attvalue]))
					{
						if($attributename !='')
							$attributename.= "(". $attrmapslugtoname[$attrname.$attvalue] . ")";
						else
							$attributename.= " (". $attrmapslugtoname[$attrname.$attvalue] . ")";
					}else
					{
						if($attributename !='')
							$attributename.= "(". $attvalue . ")";
						else
							$attributename.= " (". $attvalue . ")";
					}
					$outbreak = false;
					foreach($attributes as $attr)
					{
						if('attribute_pa_'.$attr->name !== $attrname)
							continue;
						foreach($attr->values as $value)
						{
							if($value->slug === $attvalue )
							{
								$newvar->{$attrname} = $value->name;
								$newvar->{$attrname.'_ids'} = $value->term_id;
								if(!array_key_exists($attrname,$arr_handled_attr))
								{
									$arr_handled_attr[] = $attrname;
									self::HandleAttrs($post_id,$parentid,$parentattrs_cache,$attributes,$value->term_id,$attrname,count($varrow),$update_parent_attr,true);
								}
								$outbreak = true;
								break;
							}
						}
						if($outbreak)
							break;
					}
					if(!$outbreak)
					{//custom attribute
						self::HandleVarsCustomAttributes($ID,$parentid,$attrname,$arrrow['value'],$update_parent_attr);
						$newvar->_custom_attributes = array();
						$custarr = array();
						$attrname1 = $attrname;
						if (0 === strpos($attrname1, 'attribute_')) 
						{
							 $attrname1 = substr( $attrname1, 10);
						}
						$custarr['name'] = $attrname1;
						$custarr["attslug"] = sanitize_title($attrname1);
						$custarr["slug"]  = sanitize_title(str_replace('\"','"',$attvalue));
						$custarr["value"] = $arrrow['value'];
						$newvar->_custom_attributes[] = $custarr;
						{
							if(!isset(self::$mapcustom[$attrname1]))
							{
								self::$mapcustom[$attrname1] = sanitize_title($attrname1);
							}
							$values = array_map( 'trim', explode( WC_DELIMITER, $arrrow['value'] ) );

							foreach ( $values as $value ) 
							{
								if(!isset(self::$mapcustom[$value]))
								{
									self::$mapcustom[$value] = sanitize_title($value);
								}
							} 
						}
						$newvar->{sanitize_title($attrname)} = sanitize_title(str_replace('\"','"',$attvalue));
						
//						self::HandleAttrs($post_id,$parentid,$parentattrs_cache,$attributes,$attvalue,$attrname,count($varrow),$update_parent_attr,true,true);
					}
				}
				
				
				
			}
			$newvar->post_title = $attributename;
			$newvar->post_status = 'publish';
			$newvar->_tax_class = "Standard";
			$newvar->menu_order = (string)$madevarparents[$parentid];
			$retarray[] = $newvar;
			do_action( 'woocommerce_save_product_variation', $post_id, 0 );
		}
		foreach($update_parent_attr as $parid => $attrarrays)
		{
			$newpar = new stdClass();
			$newpar->ID = $parid;
			$newpar->post_parent = "0";
			if(in_array('customattribute',$attrarrays))
			{
				$newpar->_product_attributes = get_post_meta($parid,'_product_attributes',true);
				if(is_array($newpar->_product_attributes))
				{
					foreach($newpar->_product_attributes as $patt_name => $patt_item)
					{
						if(is_array($patt_item))
						{
							if(isset($patt_item["is_taxonomy"]) && $patt_item["is_taxonomy"] == 0)
							{
								if(!property_exists($newpar,'_custom_attributes'))
									$newpar->_custom_attributes = array();
								$newcustom = array();
								$newcustom['name'] = $patt_item['name'];
								$newcustom["is_visible"]   = $patt_item["is_visible"];
								$newcustom["is_taxonomy"]  = 0;
								$newcustom["is_variation"] = 1;
								$newcustom['value'] = $patt_item["value"];
								$newcustom['position'] = $patt_item["position"];
								$newpar->_custom_attributes[] = $newcustom;
							}
								
						}
					}
				}
			}
			$attrs = wp_get_object_terms($parid,$attrarrays);
			
			if(is_array($attrs))
			{
				foreach($attrs as $attr_obj)
				{
					if(!is_object($attr_obj)) continue;
					if(!property_exists($attr_obj,'term_id')) continue;
					if(!property_exists($attr_obj,'name')) continue;
					if(!property_exists($attr_obj,'taxonomy')) continue;
					$attr_prop = 'attribute_'.$attr_obj->taxonomy;
					if(!property_exists($newpar,$attr_prop))
					{
						$newpar->{$attr_prop} = $attr_obj->name;
						self::UpdateParentMeta($parid,$attr_obj->taxonomy);
						$newpar->{$attr_prop.'_visiblefp'} = 2;
					}else
					{
						$newpar->{$attr_prop} = $newpar->{$attr_prop}.', '. $attr_obj->name;
					}
					$attr_ids = 'attribute_'.$attr_obj->taxonomy.'_ids';
					if(!property_exists($newpar,$attr_ids))
					{
						$newpar->{$attr_ids} = (string)$attr_obj->term_id;
					}else
					{
						$newpar->{$attr_ids} = $newpar->{$attr_ids}.','.(string)$attr_obj->term_id;
					}
				}
				
			}
			$retarray[] = $newpar;
		}
		
		return $retarray;
	}
	
	public static function HandleVarsCustomAttributes($ID,$parentid,$attrname,$value,&$update_parent_attr)
	{
		global $wpdb;
		$patt = get_post_meta($parentid,'_product_attributes',true);
		if (0 === strpos($attrname, 'attribute_')) 
		{
			 $attrname = substr( $attrname, 10);
		}
		$new_taxonomy_slug = sanitize_title( $attrname );
		if(isset($update_parent_attr[$parentid]))
		{
			$arr_attrs_update = $update_parent_attr[$parentid];
			if(!in_array('customattribute',$arr_attrs_update))
			{
				$arr_attrs_update[] = 'customattribute';
				$update_parent_attr[$parentid] = $arr_attrs_update;
			}
			
		}else
		{
			$arr_attrs_update = array();
			$arr_attrs_update[] = 'customattribute';
			$update_parent_attr[$parentid] = $arr_attrs_update;
		}
		if(is_array($patt))
		{
			 if(isset($patt[$new_taxonomy_slug]))
			 {
				 $patt[$new_taxonomy_slug]["is_variation"] = 1;
				 $oldvalue = $patt[$new_taxonomy_slug]["value"];
				 if(!isset($oldvalue)) $oldvalue = "";
				 $oldvalue = trim($oldvalue);
				  $hasit = false;
				 $values = array_map( 'trim', explode( WC_DELIMITER, $oldvalue ) );
				foreach ( $values as $valueinner ) 
				{
					if($valueinner === $value)
					{
						$hasit = true;
						break;
					}
				} 
				if(!$hasit)
				{
					if($oldvalue === "")
					{
						$patt[$new_taxonomy_slug]["value"]  = $value;
					}else
					{
						$patt[$new_taxonomy_slug]["value"]  = $oldvalue." ".WC_DELIMITER." ".$value;
					}
					update_post_meta($parentid,'_product_attributes',$patt);
				}
				
			 }else
			 {
			 	$patt[$new_taxonomy_slug] = array();
				$patt[$new_taxonomy_slug]["name"] = $attrname;
				$patt[$new_taxonomy_slug]["is_visible"]   = 0;
				$patt[$new_taxonomy_slug]["is_taxonomy"]  = 0;
				$patt[$new_taxonomy_slug]["is_variation"] = 1;
				$patt[$new_taxonomy_slug]["value"]  = $value;
				$patt[$new_taxonomy_slug]["position"] = count($patt);
				 update_post_meta($parentid,'_product_attributes',$patt);
			 }
			
		}else
		{
			$patt = array();
			$patt[$new_taxonomy_slug] = array();
			$patt[$new_taxonomy_slug]["name"] = $attrname;
			$patt[$new_taxonomy_slug]["is_visible"]   = 0;
			$patt[$new_taxonomy_slug]["is_taxonomy"]  = 0;
			$patt[$new_taxonomy_slug]["is_variation"] = 1;
			$patt[$new_taxonomy_slug]["value"]  = $value;
			$patt[$new_taxonomy_slug]["position"] = 0;
			update_post_meta($parentid,'_product_attributes',$patt);
		}
		self::CallWooAction($parentid);
	}
   	
   	
	public static function deleteProducts(&$data,$type,&$currentpos,&$batchnumber,$deleteinternal = false)
	{
		global $wpdb;
		$posts = $wpdb->posts;
		$meta = $wpdb->postmeta;
		$term = $wpdb->term_relationships;
		$updatevarsmeta = array();
		$deleteattach = false;
		$curr_settings = get_option('w3exabe_settings');
		if(!is_array($curr_settings))
			$curr_settings = array();
		if(isset($curr_settings['deleteimages']) && $curr_settings['deleteimages'] == 1)
		{
			$deleteattach = true;
		}
		foreach($data as $arrrow)
		{
			if(!is_array($arrrow)) continue;
			$ID = 0;
			
			if(array_key_exists('ID',$arrrow))
			{
				$ID = (int)$arrrow['ID'];
			
				$parentid = 0;
				$post_status = "draft";
				if(array_key_exists('post_parent',$arrrow))
					$parentid = (int)$arrrow['post_parent'];
				if(array_key_exists('post_status',$arrrow))
					$post_status = (string)$arrrow['post_status'];
				if($ID < 0) continue;
				if($type === "0")
				{
					//skip variations
					if ( $wpdb->get_var( $wpdb->prepare( "SELECT post_type FROM {$posts} WHERE ID = %d", $ID ) ) !== "product" ) 
					 	continue;
					$query = "SELECT post_status FROM {$posts} WHERE ID={$ID}";
					$post_status = $wpdb->get_var($query);
					if($deleteinternal)
					{
						$query = "UPDATE {$posts}
								  SET {$posts}.post_status='trash'
								  WHERE  {$posts}.ID={$ID}";
						$ret = $wpdb->query($query);
						if ( is_wp_error($ret) ) {
							return new WP_Error( 'db_query_error', 
								__( 'Could not execute query' ), $wpdb->last_error );
						} 
						update_post_meta($ID,'_wp_trash_meta_status',$post_status);
						update_post_meta($ID,'_wp_trash_meta_time',time());
						do_action( 'wp_trash_post',$ID);
					}else
					{
						wp_trash_post( $ID); 
					}
					if($parentid != 0)
					{
						if(function_exists("wc_delete_product_transients"))
							wc_delete_product_transients($parentid);
					}else
					{
						if(function_exists("wc_delete_product_transients"))
							wc_delete_product_transients($ID);
					}
					
				}elseif($type === "1")
				{
					$bgroupedchild = false;
					if($parentid == 0)
					{//check if variable
						if($deleteinternal)
						{
							if(is_object_in_term( $ID, 'product_type', 'variable' ))
							{
								$query = "SELECT ID from {$posts} WHERE post_parent={$ID} AND (post_type='product_variation')";
								$childids =  $wpdb->get_results($query);
								if(!is_wp_error($childids) && is_array($childids))
								{
									foreach($childids as $childobj)
									{
										$childid = $childobj->ID;
										do_action( 'before_delete_post',$childid);
										if($deleteattach)
										{
											$thumbid = get_post_meta($childid, '_thumbnail_id',true);
											wp_delete_attachment($thumbid,true);
										}
										$query = "DELETE FROM {$posts}
												  WHERE  {$posts}.ID={$childid}";
										$ret = $wpdb->query($query);
										if ( is_wp_error($ret) ) {
											return new WP_Error( 'db_query_error', 
												__( 'Could not execute query' ), $wpdb->last_error );
										} 
										$query = "DELETE FROM {$meta}
												  WHERE  {$meta}.post_id={$childid}";
										$ret = $wpdb->query($query);
										if ( is_wp_error($ret) ) {
											return new WP_Error( 'db_query_error', 
												__( 'Could not execute query' ), $wpdb->last_error );
										} 
										$query = "DELETE FROM {$term}
												  WHERE  {$term}.object_id={$childid}";
										$ret = $wpdb->query($query);
										if ( is_wp_error($ret) ) {
											return new WP_Error( 'db_query_error', 
												__( 'Could not execute query' ), $wpdb->last_error );
										} 
										do_action( 'delete_post',$childid);
									}
									if(function_exists("wc_delete_product_transients"))
										wc_delete_product_transients($ID);
								}
							}
						}
					}else
					{
						if ( $wpdb->get_var( $wpdb->prepare( "SELECT post_type FROM {$posts} WHERE ID = %d", $ID ) ) === "product_variation" ) 
					 	{	
							if(!array_key_exists($parentid,$updatevarsmeta))
								$updatevarsmeta[] = $parentid;
//							continue;
						}else
						{
							$bgroupedchild = true;
						}
					}
					if($deleteattach)
					{
						$thumbids = get_post_meta($ID, '_product_image_gallery',true);
						$idstodelete = array();
						$idstodelete = explode(',',$thumbids);
						foreach($idstodelete as $idtodelete)
						{
							wp_delete_attachment($idtodelete,true);
						}
						$thumbid = get_post_meta($ID, '_thumbnail_id',true);
						wp_delete_attachment($thumbid,true);	
					}
					if($deleteinternal)
					{
						do_action( 'before_delete_post',$ID);
						$query = "DELETE FROM {$posts}
								  WHERE  {$posts}.ID={$ID}";
						$ret = $wpdb->query($query);
						if ( is_wp_error($ret) ) {
							return new WP_Error( 'db_query_error', 
								__( 'Could not execute query' ), $wpdb->last_error );
						} 
						$query = "DELETE FROM {$meta}
								  WHERE  {$meta}.post_id={$ID}";
						$ret = $wpdb->query($query);
						if ( is_wp_error($ret) ) {
							return new WP_Error( 'db_query_error', 
								__( 'Could not execute query' ), $wpdb->last_error );
						} 
						$query = "DELETE FROM {$term}
								  WHERE  {$term}.object_id={$ID}";
						$ret = $wpdb->query($query);
						if ( is_wp_error($ret) ) {
							return new WP_Error( 'db_query_error', 
								__( 'Could not execute query' ), $wpdb->last_error );
						} 
						do_action( 'delete_post',$ID);
					}
					if($parentid != 0)
					{
						if(function_exists("wc_delete_product_transients"))
							wc_delete_product_transients($parentid);
					}
					if($bgroupedchild)
					{
						$transient_name = 'wc_product_children_' . $parentid;
		        		$args = apply_filters( 'woocommerce_grouped_children_args', array(
		        			'post_parent' 	=> $parentid,
		        			'post_type'		=> 'product',
		        			'orderby'		=> 'menu_order',
		        			'order'			=> 'ASC',
		        			'fields'		=> 'ids',
		        			'post_status'	=> 'publish',
		        			'numberposts'	=> -1,
		        		) );

				        $children = get_posts( $args );

						set_transient( $transient_name, $children, DAY_IN_SECONDS * 30 );
					}
					if(!$deleteinternal)
					{
						if($deleteattach)
						{
							if(is_object_in_term( $ID, 'product_type', 'variable' ))
							{
								$query = "SELECT ID from {$posts} WHERE post_parent={$ID} AND (post_type='product_variation')";
								$childids =  $wpdb->get_results($query);
								if(!is_wp_error($childids) && is_array($childids))
								{
									foreach($childids as $childobj)
									{
										$thumbid = get_post_meta($childobj->ID, '_thumbnail_id',true);
										wp_delete_attachment($thumbid,true);
									}
								}
								
							}
						}
						wp_delete_post( $ID,true); 
					}
					
				}				
			}	
		}
		foreach($updatevarsmeta as $item_id)
		{
			self::HandleSaleRemove($item_id);
		}
	}

	public static function DuplicateProduct(&$arrrow,&$retarray)
	{
		global $wpdb;
		$posts = $wpdb->posts;
		$meta = $wpdb->postmeta;
		$term = $wpdb->term_relationships;
		
		$ID = (int)$arrrow['ID'];
			
		$parentid = 0;

		if($ID < 0) return;
		$post = get_post($ID);
		if($post === null || !is_object($post)) return;
		if($post->post_type != 'product' ) return;
		
		$new_post_author    = wp_get_current_user();
		$new_post_date      = current_time( 'mysql' );
		$new_post_date_gmt  = get_gmt_from_date( $new_post_date );
		
		$post_parent = 0;
		$post_status = 'draft';
		$suffix = ' ' . __( '(Copy)', 'woocommerce' );
		if ( $parentid > 0 ) 
		{
			$post_parent        = $parentid;
			$post_status        = 'publish';
			$suffix             = '';
		}
	    
		$arrpostdata = array(
				'post_author'               => $new_post_author->ID,
				'post_date'                 => $new_post_date,
				'post_date_gmt'             => $new_post_date_gmt,
				'post_content'              => $post->post_content,
				'post_content_filtered'     => $post->post_content_filtered,
				'post_title'                => $post->post_title . $suffix,
				'post_excerpt'              => $post->post_excerpt,
				'post_status'               => $post_status,
				'post_type'                 => $post->post_type,
				'comment_status'            => $post->comment_status,
				'ping_status'               => $post->ping_status,
				'post_password'             => $post->post_password,
				'to_ping'                   => $post->to_ping,
				'pinged'                    => $post->pinged,
				'post_modified'             => $new_post_date,
				'post_modified_gmt'         => $new_post_date_gmt,
				'post_parent'               => $post->post_parent,
				'menu_order'                => $post->menu_order,
				'post_mime_type'            => $post->post_mime_type
			);
			
		$new_post_id = wp_insert_post(
			$arrpostdata,
			true
		);
		if(is_wp_error($new_post_id))
		{
			return $new_post_id;
		}
		
		$newvar = new stdClass();
		$newvar->ID = (string)$new_post_id;
		
		$newvar->post_type = 'product';
		

		foreach($arrpostdata as $column => $value)
		{
			$newvar->{$column} = $value;
		}
		$newvar->post_parent = (string)$post->post_parent;
		
	
		self::duplicate_post_taxonomies( $post->ID, $new_post_id, $post->post_type,$post->post_parent);

		self::duplicate_post_meta( $post->ID, $new_post_id, $newvar);
		
		$retarray[] = $newvar;
		// Copy the children (variations)
		if ( $children_products = get_children( 'post_parent='.$post->ID.'&post_type=product_variation' ) ) 
		{

			if ( $children_products ) 
			{
				$post_parent = $new_post_id;
				foreach ( $children_products as $child ) 
				{
					$varid = absint($child->ID);

					if ( ! $varid ) 
					{
						continue;
					}

					$variations = $wpdb->get_results( "SELECT * FROM $wpdb->posts WHERE ID=$varid" );
					
					if(!is_array($variations)) continue;
					if(count($variations) === 0) continue;
					if(!is_object($variations[0])) continue;
					
					$variation = $variations[0];
					
					$arrpostdata = array(
							'post_author'               => $new_post_author->ID,
							'post_date'                 => $new_post_date,
							'post_date_gmt'             => $new_post_date_gmt,
							'post_content'              => $variation->post_content,
							'post_content_filtered'     => $variation->post_content_filtered,
							'post_title'                => $variation->post_title,
							'post_excerpt'              => $variation->post_excerpt,
							'post_status'               => $variation->post_status,
							'post_type'                 => $variation->post_type,
							'comment_status'            => $variation->comment_status,
							'ping_status'               => $variation->ping_status,
							'post_password'             => $variation->post_password,
							'to_ping'                   => $variation->to_ping,
							'pinged'                    => $variation->pinged,
							'post_modified'             => $new_post_date,
							'post_modified_gmt'         => $new_post_date_gmt,
							'post_parent'               => (string)$post_parent,
							'menu_order'                => $variation->menu_order,
							'post_mime_type'            => $variation->post_mime_type
						);
						
					$wpdb->insert(
						$wpdb->posts,
						$arrpostdata
					);
	
					$new_var_id = $wpdb->insert_id;
					$newvar = new stdClass();
					$newvar->ID = (string)$new_var_id;
					$newvar->post_parent = (string)$post_parent;
					$newvar->post_type = 'product_variation';
					

					foreach($arrpostdata as $column => $value)
					{
						$newvar->{$column} = $value;
					}
					
					self::duplicate_post_taxonomies( $variation->ID, $new_var_id, $variation->post_type );

					self::duplicate_post_meta( $variation->ID, $new_var_id, $newvar);
					
					$retarray[] = $newvar;
				}
			}
		}
		do_action( 'woocommerce_duplicate_product', $new_post_id, $post );
	}
	
	public static function duplicateProducts(&$data,$count=1)
	{
		$retarray = array();
		
		$counter = 0;
		foreach($data as $arrrow)
		{
			if(!is_array($arrrow)) continue;
			$ID = 0;
			if(!array_key_exists('ID',$arrrow)) continue;
			{
				$counter = 0;
				while($counter < $count && $counter <= 100)
				{
					self::DuplicateProduct($arrrow,$retarray);
					$counter++;
				}
			}	
		}
		$total = 0;
		$hasnext = false;
		$isbegin = false;
		
		if(count($retarray) === 0) return $retarray;
		
		self::loadProducts(null,null,null,null,null,null,$total,false,false,$hasnext,$isbegin,false,null,null,null,null,null,$retarray);
		return $retarray;
	}
	
	public static function duplicate_post_taxonomies( $id, $new_id, $post_type, $post_parent = 0 ) 
	{

	
		$taxonomies = get_object_taxonomies( $post_type );
		
		foreach ( $taxonomies as $taxonomy ) 
		{

			$post_terms = wp_get_object_terms( $id, $taxonomy );
			$post_terms_count = sizeof( $post_terms );

			for ( $i=0; $i<$post_terms_count; $i++ ) 
			{
				wp_set_object_terms( $new_id, $post_terms[$i]->slug, $taxonomy, true );
			}
		}
	}

	
	public static function duplicate_post_meta( $id, $new_id, &$postobject) 
	{
		global $wpdb;

		$post_meta_infos = $wpdb->get_results( $wpdb->prepare( "SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=%d AND meta_key NOT IN ( 'total_sales', '_sku' );", absint( $id ) ) );

		if ( count( $post_meta_infos ) != 0 ) 
		{

			$sql_query_sel = array();
			$sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";

			foreach ( $post_meta_infos as $meta_info ) 
			{
				$meta_key = $meta_info->meta_key;
				$meta_value = addslashes( $meta_info->meta_value );
				$postobject->{$meta_key} = $meta_value;
				$sql_query_sel[]= "SELECT $new_id, '$meta_key', '$meta_value'";
			}

			$sql_query.= implode( " UNION ALL ", $sql_query_sel );
			$wpdb->query($sql_query);
		}
	}
	
	public static function HandleCatParams(&$catparams)
	{
		$newarr = array();
		
		self::WriteDebugInfo("incchildren",null,array('entering handlecatparams'));
		
		$args = array(
		    'number'     => 99999,
		    'orderby'    => 'slug',
		    'order'      => 'ASC',
		    'hide_empty' => false,
		    'include'    => '',
			'fields'     => 'all'
		);

		$woo_categoriesouter = get_terms( 'product_cat', $args );
		if(is_wp_error($woo_categoriesouter))
				return;
		foreach($catparams as $cat)
		{
			
			 $args = array(
		     'number'     => 99999,
		     'orderby'    => 'slug',
		     'order'      => 'ASC',
		     'hide_empty' => false,
		     'include'    => '',
			 'fields'     => 'all',
			 'child_of'    => (int)$cat
			);
			foreach($woo_categoriesouter as $categoryouter)
			{
			   if(!is_object($categoryouter)) continue;
			   if(!property_exists($categoryouter,'term_taxonomy_id')) continue;
			    if(!property_exists($categoryouter,'term_id')) continue;
			   if($categoryouter->term_taxonomy_id == $cat)
			   {
			       $args['child_of'] = $categoryouter->term_id;
				   break;
			   }
			};
			self::WriteDebugInfo("incchildren",null,array('child_of'.$cat));
			$woo_categories = get_terms( 'product_cat', $args );
			if(is_wp_error($woo_categories))
				continue;
			foreach($woo_categories as $category)
			{
			    if(!is_object($category)) continue;
			    if(!property_exists($category,'term_taxonomy_id')) continue;
			    if(!property_exists($category,'term_id')) continue;
				if(!in_array($category->term_taxonomy_id,$catparams))
					$newarr[] = $category->term_taxonomy_id;
			};
		}
		$catparams = array_merge($catparams,$newarr);
	}
	
	public static function LoadAttributeTerms(&$attr,$name,$iter,$ball,&$attrmapslugtoname,$converttoutf8,$frontpage)
	{
		global $wpdb;
		$offset = $iter * 1000;
		$iter++;
		$limit = "LIMIT 1000 OFFSET {$offset}";
		$getquery = "SELECT term_id,term_taxonomy_id FROM {$wpdb->prefix}term_taxonomy WHERE taxonomy IN('pa_". $name ."') {$limit}";
		if($ball)
		{
			$getquery = "SELECT t.term_id,t.name,t.slug,tt.term_taxonomy_id,tt.parent FROM {$wpdb->prefix}terms as t INNER JOIN {$wpdb->prefix}term_taxonomy AS tt ON t.term_id= tt.term_id WHERE tt.taxonomy IN('pa_". $name ."') {$limit}";
		}
		$values = $wpdb->get_results($getquery);
		if(is_wp_error($values))
			return;
		foreach($values as $val)
		{
			if(!is_object($val)) continue;
			if(!property_exists($val,'term_taxonomy_id')) continue;
			$value          = new stdClass();
			$value->id      = $val->term_taxonomy_id;
			if(isset($_POST['_iswpmlenabled']))
			{
				$idret = self::lang_object_id($value->id,'pa_'.$name);
				if($idret === null || $idret != ($value->id))
					continue;
			}
			$value->term_id      = $val->term_id;
			if($ball)
			{
				$value->slug    = $val->slug;
				$value->name    = $val->name;
				$value->parent  = $val->parent;
				if(!$frontpage)
				{
					$val_name = substr($value->name,0,100);
					$val_name = preg_replace('/\s+/', ' ', trim($val_name));
					$value->name = $val_name;
					if($converttoutf8)
					{
						$value->name = mb_convert_encoding($value->name, "UTF-8");
					}
					$attrmapslugtoname[$value->term_id] = $val->taxonomy;
				}
			}
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
			self::LoadAttributeTerms($attr,$name,$iter,$ball,$attrmapslugtoname,$converttoutf8,$frontpage);
		}
		
//		if($ball)
//			{
//				$values     = get_terms( 'pa_' . $att->name, array('hide_empty' => false));
//				if(is_wp_error($values))
//					continue;
//				foreach($values as $val)
//				{
//					if(!is_object($val)) continue;
//					if(!property_exists($val,'term_taxonomy_id')) continue;
//					$value          = new stdClass();
//					$value->id      = $val->term_taxonomy_id;
//					$value->term_id      = $val->term_id;
//					if($ball)
//					{
//						$value->slug    = $val->slug;
//						$value->name    = $val->name;
//					//	$val_label = substr($value->slug,0,100);
//					//	$val_label = preg_replace('/\s+/', ' ', trim($val_label));
//					//	$value->slug = $val_label;
//						$val_name = substr($value->name,0,100);
//						$val_name = preg_replace('/\s+/', ' ', trim($val_name));
//						$value->name = $val_name;
//						if($converttoutf8)
//						{
//							$value->name = mb_convert_encoding($value->name, "UTF-8");
//						}
//	//					if($attrname !== '')
//	//					{
//	//						$attrmapslugtoname[$attrname.$value->slug] = $value->name;
//	//					}else
//	//					{
//	//						$attrmapslugtoname[$value->slug] = $value->name;
//	//					}
//						$attrmapslugtoname[$value->term_id] = $val->taxonomy;
//					}				
//					$value->parent  = $val->parent;
//					$att->values[]  = $value;
//				}
//			}else
//			{
//				$values   = $wpdb->get_results("select term_id,term_taxonomy_id from " . $wpdb->prefix . "term_taxonomy WHERE taxonomy='pa_" .$att->name. "'");
//				if(is_wp_error($values))
//					continue;
//				foreach($values as $val)
//				{
//					if(!is_object($val)) continue;
//					if(!property_exists($val,'term_taxonomy_id')) continue;
//					$value          = new stdClass();
//					$value->id      = $val->term_taxonomy_id;
//					$value->term_id      = $val->term_id;
//		
//					$att->values[]  = $value;
//				}
//			}
			
		
	}
	
	public static function GetAttributes(&$attributes,&$attrmapslugtoname,$ball = false,$attrname = '',$frontpage = false,$namesonly = false)
	{
		$curr_settings = get_option('w3exabe_settings');
		if(is_array($curr_settings))
		{
			if(isset($curr_settings['disattributes']))
			{
				if($curr_settings['disattributes'] == 1)
					return;
			}
		}
		
		$converttoutf8 = true;
		if(is_array($curr_settings))
		{
			if(isset($curr_settings['converttoutf8']))
			{
				if($curr_settings['converttoutf8'] == 0)
					$converttoutf8 = false;
			}
		}
		if(!function_exists('mb_convert_encoding'))
		{
			$converttoutf8 = false;
		}
		
		global $wpdb;
		$where = '';
		if($attrname !== '')
		{
			$attrnamewhere = $attrname;
			if (0 === strpos($attrnamewhere, 'attribute_pa_')) {
			   // It starts with 'http'
			   $attrnamewhere = substr( $attrnamewhere, 13);
			}
			$where = " where attribute_name='".$attrnamewhere."'";
		}
		$woo_attrs = $wpdb->get_results("select * from " . $wpdb->prefix . "woocommerce_attribute_taxonomies{$where}",ARRAY_A);
		
		foreach($woo_attrs as $attr)
		{
			if(!$frontpage && !$ball && !$namesonly)
	  		{
				if(!in_array('attribute_pa_'.$attr['attribute_name'],self::$columns) && !empty(self::$columns))
				{
					continue;	
				}
			}
			
			$att         = new stdClass();
			$att->id     = $attr['attribute_id'];
			$att->name   = $attr['attribute_name'];  
			if($ball)
			{
				$att->label  = $attr['attribute_label']; 
				$attr_label = substr($att->label,0,100);
				$attr_label = preg_replace('/\s+/', ' ', trim($attr_label));
				$att->label = $attr_label;
				$attr_name = $att->name;
				$attr_name = preg_replace('/\s+/', ' ', trim($attr_name));
				$att->name = $attr_name;
				if(!$att->label)
					$att->label = ucfirst($att->name);
				$att->type   = $attr['attribute_type'];
				if($converttoutf8)
				{
					$att->name  = mb_convert_encoding($att->name , "UTF-8");
					$att->label  = mb_convert_encoding($att->label , "UTF-8");
				}
		  	}
		  	
			$att->values = array();
//			$attrcount = wp_count_terms( 'pa_' . $att->name, array('hide_empty' => false));
//			if ( is_wp_error($attrcount) ) 
//				continue;
//			if($attrcount > 1500)
//				continue;
			if(!$namesonly)
				self::LoadAttributeTerms($att,$att->name,0,$ball,$attrmapslugtoname,$converttoutf8,$frontpage);
			
			$curr_settings = get_option('w3exabe_settings');
			if(!is_array($curr_settings))
				$curr_settings = array();
		
			$largetemp = array();
			if(isset($curr_settings['largeattributes']) && is_array($curr_settings['largeattributes']))
			{
				$largetemp = $curr_settings['largeattributes'];
			}
			if(count($att->values) >= 100)
			{
				if(isset($largetemp[$att->name]) && $largetemp[$att->name] === "0")
				{
					$att->values = array();
					continue;
				}
			}
//		 	if(count($att->values) > 0)
			{
				$attributes[]                = $att;
			}
		}
	}
	
	public static function HandleFiles($ID,&$downloadable_files)
	{
		global $wpdb;
		$product_id = $ID;
		$existing_download_ids = array_keys( (array) get_post_meta($ID, '_downloadable_files', true) );
		$updated_download_ids  = array_keys( (array) $downloadable_files );
		$new_download_ids      = array_filter( array_diff( $updated_download_ids, $existing_download_ids ) );
		$removed_download_ids  = array_filter( array_diff( $existing_download_ids, $updated_download_ids ) );

		if ( $new_download_ids || $removed_download_ids ) {
			// determine whether downloadable file access has been granted via the typical order completion, or via the admin ajax method
			$existing_permissions = $wpdb->get_results( $wpdb->prepare( "SELECT * from {$wpdb->prefix}woocommerce_downloadable_product_permissions WHERE product_id = %d GROUP BY order_id", $product_id) );

			foreach ( $existing_permissions as $existing_permission ) {
//				$order = new WC_Order( $existing_permission->order_id );

				if ( $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM {$wpdb->prefix}posts WHERE ID = %d", $existing_permission->order_id ) ) ) 
				{ 
					// Remove permissions
					if ( $removed_download_ids ) {
						foreach ( $removed_download_ids as $download_id ) {
							if ( apply_filters( 'woocommerce_process_product_file_download_paths_remove_access_to_old_file', true, $download_id, $product_id, $order ) ) {
								$wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->prefix}woocommerce_downloadable_product_permissions WHERE order_id = %d AND product_id = %d AND download_id = %s", $existing_permission->order_id, $product_id, $download_id ) );
							}
						}
					}
					// Add permissions
					if ( $new_download_ids ) {
						foreach ( $new_download_ids as $download_id ) {
							if ( apply_filters( 'woocommerce_process_product_file_download_paths_grant_access_to_new_file', true, $download_id, $product_id, $order ) ) {
								// grant permission if it doesn't already exist
								if ( ! $wpdb->get_var( $wpdb->prepare( "SELECT 1 FROM {$wpdb->prefix}woocommerce_downloadable_product_permissions WHERE order_id = %d AND product_id = %d AND download_id = %s", $existing_permission->order_id, $product_id, $download_id ) ) ) {
									if(function_exists('wc_downloadable_file_permission'))
									{
										wc_downloadable_file_permission( $download_id, $product_id, $existing_permission->order_id  );
									}else
										self::copied_wc_downloadable_file_permission( $download_id, $product_id, $existing_permission->order_id  );
								}
							}
						}
					}
				}
			}
		}
	}
	
	public static function copied_wc_downloadable_file_permission( $download_id, $product_id, $order_id ) 
	{
		global $wpdb;
	
		$user_email = sanitize_email( get_post_meta($order_id,'_billing_email',true));//$order->billing_email );
		$limit      = trim( get_post_meta( $product_id, '_download_limit', true ) );
		$expiry     = trim( get_post_meta( $product_id, '_download_expiry', true ) );

		$limit      = empty( $limit ) ? '' : absint( $limit );
		$user_id = get_post_meta( $order_id, '_customer_user', true );
		$order_key = get_post_meta( $order_id, '_order_key', true );
		// Default value is NULL in the table schema
		$expiry     = empty( $expiry ) ? null : absint( $expiry );

		if ( $expiry ) {
			$order_completed_date = date_i18n( "Y-m-d", strtotime( get_post_meta($order_id,'_completed_date',true) ) );
			$expiry = date_i18n( "Y-m-d", strtotime( $order_completed_date . ' + ' . $expiry . ' DAY' ) );
		}

		$data = apply_filters( 'woocommerce_downloadable_file_permission_data', array(
			'download_id'			=> $download_id,
			'product_id' 			=> $product_id,
			'user_id' 				=> absint( $user_id ),
			'user_email' 			=> $user_email,
			'order_id' 				=> $order_id,
			'order_key' 			=> $order_key,
			'downloads_remaining' 	=> $limit,
			'access_granted'		=> current_time( 'mysql' ),
			'download_count'		=> 0
		));

		$format = apply_filters( 'woocommerce_downloadable_file_permission_format', array(
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%d'
		), $data);

		if ( ! is_null( $expiry ) ) {
				$data['access_expires'] = $expiry;
				$format[] = '%s';
		}

		// Downloadable product - give access to the customer
		$result = $wpdb->insert( $wpdb->prefix . 'woocommerce_downloadable_product_permissions',
			$data,
			$format
		);

		do_action( 'woocommerce_grant_product_download_access', $data );

		return $result ? $wpdb->insert_id : false;
	}
	
	public static function HandleSaleRemove($parentid)
	{
		$children = array();
		$childids = array();
		global $wpdb;
		$posts = $wpdb->posts;
		$meta = $wpdb->postmeta;
		$temptable = $wpdb->prefix."wpmelon_advbedit_temp";
		$query = "SELECT ID from {$posts} WHERE post_parent={$parentid} AND (post_type='product_variation')";

		{
			$childids =  $wpdb->get_results($query);
			
			if(!is_array($childids)) return;
			if(count($childids) == 0) return;
			self::WriteDebugInfo("loop number ","","parent id ".$parentid);
			self::WriteDebugInfo("loop number ","","ids number ".count($childids));
			$idin = "";
			foreach($childids as $id)
			{
				/*$hasid = false;
				foreach($children as $key)
				{
					if($key['ID'] == $id->ID)
					{
						$hasid = true;
						break;
					}
				}
				if($hasid) continue;*/
				if($idin == "")
				{
					$idin = "(".$id->ID;
				}else
				{
					$idin = $idin.",".$id->ID;
				}			
			}
			if($idin != "")
			{
				$idin = $idin.")";
				$query = "SELECT post_id,meta_value,meta_key FROM {$meta} WHERE (meta_key='_sale_price' OR meta_key='_regular_price')  AND post_id IN ".$idin;
				self::WriteDebugInfo("loop number ","",$query);
				$items =  $wpdb->get_results($query);
				foreach($items as $obj)
				{
					$newitem = array();
					$added = false;
					foreach($children as &$child)
					{
						if($child['ID'] == $obj->post_id)
						{
							$newitem = $child;
							if($obj->meta_key == '_sale_price')
								$child['_sale_price'] = $obj->meta_value;
							else
								$child['_regular_price'] = $obj->meta_value;
							$added = true;
							break;
						}
					}
					if($added) continue;
					$newitem['parentid'] = $parentid;
					$newitem['ID'] = $obj->post_id;
					if($obj->meta_key == '_sale_price')
						$newitem['_sale_price'] = $obj->meta_value;
					else
						$newitem['_regular_price'] = $obj->meta_value;
					$children[] = $newitem;
				}
			}
		}
		
		$biggestval = $lowestval = $biggestid = $lowestid = $biggestsaleval = $biggestsaleid = -1;
		$hasitemwithnosale = false;
		$hasitemwithsale = false;
		$arrnosale = array();
			
		foreach($children as $key)
		{
			if($key['parentid'] != $parentid) continue;
			if(!isset($key['_sale_price']))
				$key['_sale_price'] = "";
			if(!isset($key['_regular_price']))
				$key['_regular_price'] = "";
			$sale_price = $key['_sale_price'];
			$sale_price = trim($sale_price);
			if($sale_price == "")
			{
				$hasitemwithnosale = true;
				$arritem = array();
				$arritem['_regular_price'] = $key['_regular_price'];
				$arritem['ID'] = $key['ID'];
				$arrnosale[] = $arritem;
				continue;
			}
			////check if sale does not apply
			
			
			$salefrom = "";
			$saleto = "";
			$salefrom = get_post_meta($key['ID'],'_sale_price_dates_from',true);
			if($salefrom != "")
			{
				$salefrom = date('Y-m-d',$salefrom);
			}
			$saleto = get_post_meta($key['ID'],'_sale_price_dates_to',true);
			if($saleto != "")
			{
				$saleto = date('Y-m-d',$saleto);
			}
			if($saleto !== "" || $salefrom !== "")
			{
				if($salefrom !== "")
				{
					$dt = time();
					$salefromd = strtotime($salefrom);//date('Y-m-d', $salefrom);
					if($saleto !== "")
					{
						$saletod = strtotime($saleto);
						if($salefromd > $dt || $saletod < $dt)
						{//sale is off
							$sale_price = "";
							$hasitemwithnosale = true;
							$arritem = array();
							$arritem['_regular_price'] = $key['_regular_price'];
							$arritem['ID'] = $key['ID'];
							$arrnosale[] = $arritem;
							continue;
						}
					}
				}
				if($saleto !==  "")
				{
					$dt = time();
					$saletod = strtotime($saleto);
					if($saletod < $dt)
					{
						$sale_price = "";
						$hasitemwithnosale = true;
						$arritem = array();
						$arritem['_regular_price'] = $key['_regular_price'];
						$arritem['ID'] = $key['ID'];
						$arrnosale[] = $arritem;
						continue;
					}
				}
			}
			/////
			$hasitemwithsale = true;
			
			if($biggestval == -1 && $lowestval == -1)
			{
				$biggestval = (float)$sale_price;
				$lowestval = (float)$sale_price;
				$biggestid = $key['ID'];
				$lowestid = $key['ID'];
				continue;
			}
			$sale_price = (float)$sale_price;
			if($sale_price > $biggestval)
			{
				$biggestval = $sale_price;
				$biggestid = $key['ID'];
			}elseif($sale_price < $lowestval)
			{
				$lowestval =$sale_price;
				$lowestid = $key['ID'];
			}
		}
		$biggestvalreg = $lowestvalreg = $biggestidreg = $lowestidreg = -1;
		$biggestsaleval = $biggestval;
		$biggestsaleid = $biggestid;
		foreach($children as $key)
		{
			if($key['parentid'] != $parentid) continue;
			$reg_price = $key['_regular_price'];
			$reg_price = trim($reg_price);
			if($reg_price == "") continue;
			if($biggestvalreg == -1 && $lowestvalreg == -1)
			{
				$biggestvalreg = (float)$reg_price;
				$lowestvalreg = (float)$reg_price;
				$biggestidreg = $key['ID'];
				$lowestidreg = $key['ID'];
				continue;
			}
			$reg_price = (float)$reg_price;
			if($reg_price > $biggestvalreg)
			{
				$biggestvalreg = $reg_price;
				$biggestidreg = $key['ID'];
			}elseif($reg_price < $lowestvalreg)
			{
				$lowestvalreg =$reg_price;
				$lowestidreg = $key['ID'];
			}
			if($hasitemwithnosale)
			{//take reg as biggest
				foreach( $arrnosale as $arrnosaleitem)
				{
					$regprice1 = $arrnosaleitem['_regular_price'];
					$regprice1 = trim($regprice1);
					if($regprice1 == "") continue;
					$regprice1 = (float)$regprice1;
					if($regprice1 > $biggestval)
					{
						$biggestval = (float)$regprice1;
						$biggestid = $arrnosaleitem['ID'];
					}
				}
				
			}
		}
		
		if($biggestval == -1)
		{// all sale prices deleted
//			$query = "UPDATE {$meta} SET meta_value = CASE meta_key WHEN '_min_variation_sale_price' THEN '' 
//			WHEN '_max_variation_sale_price' THEN ''
//			WHEN '_min_sale_price_variation_id' THEN '' 
//			WHEN '_max_sale_price_variation_id' THEN ''
//			ELSE meta_value END WHERE meta_key IN ('_min_variation_sale_price','_max_variation_sale_price','_min_sale_price_variation_id','_max_sale_price_variation_id') AND post_id={$parentid}";
//			$wpdb->query($query);
			update_post_meta($parentid,'_max_variation_sale_price','');
			update_post_meta($parentid,'_min_sale_price_variation_id','');
			update_post_meta($parentid,'_max_sale_price_variation_id','');
//			$query = "UPDATE {$meta} SET meta_value = CASE meta_key 
//			WHEN '_min_variation_price' THEN '{$lowestvalreg}' 
//			WHEN '_max_variation_price' THEN '{$biggestvalreg}'
//			WHEN '_min_variation_regular_price' THEN '{$lowestvalreg}' 
//			WHEN '_max_variation_regular_price' THEN '{$biggestvalreg}'
//			WHEN '_min_regular_price_variation_id' THEN '{$lowestidreg}' 
//			WHEN '_max_regular_price_variation_id' THEN '{$biggestidreg}'
//			WHEN '_min_price_variation_id' THEN '{$lowestidreg}' 
//			WHEN '_max_price_variation_id' THEN '{$biggestidreg}'
//			WHEN '_price' THEN '{$lowestvalreg}'
//			ELSE meta_value END WHERE meta_key IN ('_min_variation_regular_price','_max_variation_regular_price','_min_regular_price_variation_id','_max_regular_price_variation_id','_min_variation_price','_max_variation_price','_max_price_variation_id','_min_price_variation_id','_price') AND post_id={$parentid}";
//			$wpdb->query($query);
			if($lowestvalreg === -1)
				$lowestvalreg = '';
			if($biggestvalreg === -1)
				$biggestvalreg = '';
			if($lowestidreg === -1)
				$lowestidreg = '';
			if($biggestidreg === -1)
				$biggestidreg = '';
			update_post_meta($parentid,'_min_variation_price',$lowestvalreg);
			update_post_meta($parentid,'_max_variation_price',$biggestvalreg);
			update_post_meta($parentid,'_min_variation_regular_price',$lowestvalreg);
			update_post_meta($parentid,'_max_variation_regular_price',$biggestvalreg);
			update_post_meta($parentid,'_min_regular_price_variation_id',$lowestidreg);
			update_post_meta($parentid,'_max_regular_price_variation_id',$biggestidreg);
			update_post_meta($parentid,'_min_price_variation_id',$lowestidreg);
			update_post_meta($parentid,'_max_price_variation_id',$biggestidreg);
			update_post_meta($parentid,'_price',$lowestvalreg);
		}else
		{
//			$query = "UPDATE {$meta} SET meta_value = CASE meta_key 
//			WHEN '_min_variation_sale_price' THEN '{$lowestval}' 
//			WHEN '_max_variation_sale_price' THEN '{$biggestval}'
//			WHEN '_min_sale_price_variation_id' THEN '{$lowestid}'
//			WHEN '_max_sale_price_variation_id' THEN '{$biggestid}'
//			ELSE meta_value END WHERE meta_key IN ('_min_variation_sale_price','_max_variation_sale_price','_min_sale_price_variation_id','_max_sale_price_variation_id') AND post_id={$parentid}";
//			$wpdb->query($query);
			if($lowestval !== -1)
				update_post_meta($parentid,'_min_variation_sale_price',$lowestval);
			else
				update_post_meta($parentid,'_min_variation_sale_price','');
			if($biggestsaleval !== -1)
			{
				if($hasitemwithsale)
					update_post_meta($parentid,'_max_variation_sale_price',$biggestsaleval);
				else
					update_post_meta($parentid,'_max_variation_sale_price','');
			}
			else
				update_post_meta($parentid,'_max_variation_sale_price','');
			if($lowestid !== -1) 
				update_post_meta($parentid,'_min_sale_price_variation_id',$lowestid);
			else
				update_post_meta($parentid,'_min_sale_price_variation_id','');
			if($biggestsaleid !== -1)
			{
				if($hasitemwithsale)
					update_post_meta($parentid,'_max_sale_price_variation_id',$biggestsaleid);
				else
					update_post_meta($parentid,'_max_sale_price_variation_id','');
			}
			else
				update_post_meta($parentid,'_max_sale_price_variation_id','');
//			$query = "UPDATE {$meta} SET meta_value = CASE meta_key 
//			WHEN '_min_variation_price' THEN '{$lowestval}' 
//			WHEN '_max_variation_price' THEN '{$biggestval}'
//			WHEN '_min_variation_regular_price' THEN '{$lowestvalreg}' 
//			WHEN '_max_variation_regular_price' THEN '{$biggestvalreg}'
//			WHEN '_min_regular_price_variation_id' THEN '{$lowestidreg}' 
//			WHEN '_max_regular_price_variation_id' THEN '{$biggestidreg}'
//			WHEN '_min_price_variation_id' THEN '{$lowestid}' 
//			WHEN '_max_price_variation_id' THEN '{$biggestid}'
//			WHEN '_price' THEN '{$lowestval}'
//			ELSE meta_value END WHERE meta_key IN ('_min_variation_regular_price','_max_variation_regular_price','_min_regular_price_variation_id','_max_regular_price_variation_id','_min_variation_price','_max_variation_price','_max_price_variation_id','_min_price_variation_id','_price') AND post_id={$parentid}";
//			$wpdb->query($query);
			if($lowestvalreg === -1)
				$lowestvalreg = '';
			if($biggestvalreg === -1)
				$biggestvalreg = '';
			if($lowestidreg === -1)
				$lowestidreg = '';
			if($biggestidreg === -1)
				$biggestidreg = '';
			if($biggestid === -1)
				$biggestid = '';
			if($biggestval === -1)
				$biggestval = '';
			if($lowestval !== -1)
				update_post_meta($parentid,'_min_variation_price',$lowestval);
			else
				update_post_meta($parentid,'_min_variation_price',$lowestvalreg);
			update_post_meta($parentid,'_max_variation_price',$biggestval);
			update_post_meta($parentid,'_min_variation_regular_price',$lowestvalreg);
			update_post_meta($parentid,'_max_variation_regular_price',$biggestvalreg);
			update_post_meta($parentid,'_min_regular_price_variation_id',$lowestidreg);
			update_post_meta($parentid,'_max_regular_price_variation_id',$biggestidreg);
			if($lowestid !== -1)
				update_post_meta($parentid,'_min_price_variation_id',$lowestid);
			else
				update_post_meta($parentid,'_min_price_variation_id',$lowestidreg);
			update_post_meta($parentid,'_max_price_variation_id',$biggestid);
			if($lowestval !== -1)
				update_post_meta($parentid,'_price',$lowestval);
			else
				update_post_meta($parentid,'_price',$lowestvalreg);
		}
		if(function_exists("wc_delete_product_transients"))
			wc_delete_product_transients($parentid);
		self::CallWooAction($parentid);
//		if(function_exists('wc_get_product'))
//		{
//			$product = wc_get_product($parentid);
//			if(!empty($product) && is_object($product))
//			{
//				do_action( 'woocommerce_product_quick_edit_save',$product);
//			}
//				
//		}
//		$handledchildren[] = $parentid;
	}
	
	public static function exportProducts(&$data,&$children)
	{
		$dir = dirname(__FILE__);
		$dh  = opendir($dir);
		while (false !== ($filename = readdir($dh))) {
			$ibegin = strpos($filename,"temp.csv",0);
	 		if( $ibegin !== FALSE)
			{
				@unlink($dir."/".$filename);
			}
		}
		$randomint = rand();
		$purl = $dir. "/" .$randomint. "temp.csv";
		$df = fopen($purl, 'w');
		if($df)
		{
//			fputcsv($df, array_keys(reset($data)));
//			foreach ($data as $row) {
//			  fputcsv($df, $row);
//			}
			$data = stripslashes($data);
			if(function_exists('mb_convert_encoding'))
				$data = mb_convert_encoding($data, "UTF-8");
			//				$data = mb_convert_encoding($data, "UTF-16LE");
			fwrite($df, pack("CCC",0xef,0xbb,0xbf)); 
//			fwrite($df,chr(255));
//			fwrite($df,chr(254));
			fwrite($df,$data); 
			fclose($df);
		}
		return ($randomint ."temp.csv");
	}
	
	public static function convertSaveArrays(&$data,&$ids,&$children,&$cids,$vars = false)
	{
//		$newarr = array();
//		$ids = array();
		if($vars)
		{
			$counter = 0;
			foreach($data as $field => $items)
			{
				$itemsr = explode('#^#',$items);
				foreach($itemsr as $item)
				{
					$values = explode('$###',$item);
					if(count($values) !== 3) continue;
					$newarritem = array();
					$newarritem['post_parent'] = $values[0];
					$newarritem['attribute'] = $values[1];
					$newarritem['value'] = $values[2];
					if(array_key_exists($counter,$ids))
					{
						$ids[$counter][] = $newarritem;
					}else
					{
						$ids[$counter] = array();
						$ids[$counter][] = $newarritem;
					}
				}
				$counter++;
			}
			unset($data);
			return;
		}
		foreach($data as $field => $items)
		{
			$itemsr = explode('#^#',$items);
			foreach($itemsr as $item)
			{
				$values = explode('$###',$item);
				if(count($values) !== 3) continue;
				if(array_key_exists($values[0],$ids))
				{
					$arritem = &$ids[$values[0]];
					$arritem[$field] = $values[2];
				}else
				{
					$newarritem = array();
//					$newarr[] = $newarritem;
					$newarritem['ID'] = $values[0];
					$newarritem['post_parent'] = $values[1];
					$newarritem[$field] = $values[2];
					$ids[$values[0]] = $newarritem;
				}
//				$values[0]; //ID
//				$values[1]; //value
			}
		}
		unset($data);
		if(count($children) == 0) return;
		$itemsr = explode('#$',$children['children']);
		foreach($itemsr as $item)
		{
			$values = explode('#',$item);
			if(count($values) !== 4) continue;
			if(array_key_exists($values[0],$cids))
			{
				$arritem = &$cids[$values[0]];
				$newarritem['_regular_price'] = $values[2];
				$newarritem['_sale_price'] = $values[3];
			}else
			{
				$newarritem = array();
				$newarritem['ID'] = $values[0];
				$newarritem['parentid'] = $values[1];
				$newarritem['_regular_price'] = $values[2];
				$newarritem['_sale_price'] = $values[3];
				$cids[$values[0]] = $newarritem;
			}
		}
		unset($children);
	}
	
	public static function UpdateParentMeta($parentid,$taxonomy_slug,$bcreatevars = false)
	{
		$bdontcheckusedfor = true;
		$curr_settings = get_option('w3exabe_settings');
		if(is_array($curr_settings))
		{
			if(isset($curr_settings['dontcheckusedfor']))
			{
				if($curr_settings['dontcheckusedfor'] == 0)
					$bdontcheckusedfor = false;
			}
		}
		if($bcreatevars)
			$bdontcheckusedfor = false;
		$patt = get_post_meta($parentid,'_product_attributes',true);
		$new_taxonomy_slug = sanitize_title( $taxonomy_slug );
		if(is_array($patt))
		{
			 if(isset($patt[$new_taxonomy_slug]))
			 {
			 	if(!$bdontcheckusedfor)
					$patt[$new_taxonomy_slug]["is_variation"] = 1;
			 }else
			 {
			 	$patt[$new_taxonomy_slug] = array();
				$patt[$new_taxonomy_slug]["name"] = $taxonomy_slug;
				$patt[$new_taxonomy_slug]["is_visible"]   = 0;
				$patt[$new_taxonomy_slug]["is_taxonomy"]  = 1;
				if($bdontcheckusedfor)
					$patt[$new_taxonomy_slug]["is_variation"] = 0;
				else
					$patt[$new_taxonomy_slug]["is_variation"] = 1;
				$patt[$new_taxonomy_slug]["value"]  = "";
				$patt[$new_taxonomy_slug]["position"] = count($patt);
			 }
			 update_post_meta($parentid,'_product_attributes',$patt);
		}else
		{
			$patt = array();
			$patt[$new_taxonomy_slug] = array();
			$patt[$new_taxonomy_slug]["name"] = $taxonomy_slug;
			$patt[$new_taxonomy_slug]["is_visible"]   = 0;
			$patt[$new_taxonomy_slug]["is_taxonomy"]  = 1;
			if($bdontcheckusedfor)
				$patt[$new_taxonomy_slug]["is_variation"] = 0;
			else
				$patt[$new_taxonomy_slug]["is_variation"] = 1;
			$patt[$new_taxonomy_slug]["value"]  = "";
			$patt[$new_taxonomy_slug]["position"] = 0;
			update_post_meta($parentid,'_product_attributes',$patt);
		}
		self::CallWooAction($parentid);
	}
	
	public static function HandleAttrs($ID,$parentid,&$parentattrs_cache,&$attributes,$values,$attribute,$countdata,&$update_parent_attr,$bcreatevars = false,$bcustomattr = false)
	{
		global $wpdb;
		$bdontcheckusedfor = true;
		$curr_settings = get_option('w3exabe_settings');
		if(is_array($curr_settings))
		{
			if(isset($curr_settings['dontcheckusedfor']))
			{
				if($curr_settings['dontcheckusedfor'] == 0)
					$bdontcheckusedfor = false;
			}
		}

		if($bcreatevars)
			$bdontcheckusedfor = false;
		$taxonomy_slug = "";
		$pos = strpos($attribute,"attribute_");
		if ($pos !== false) {
		    $taxonomy_slug = substr_replace($attribute,"",$pos,strlen("attribute_"));
		}
		$new_taxonomy_slug = sanitize_title( $taxonomy_slug );
		if($parentid == 0)
			$key_for_cache = ((string)$ID).$new_taxonomy_slug;
		else
			$key_for_cache = ((string)$parentid).$new_taxonomy_slug;

		$cat_ids = explode(',',$values);
		
		$query = "SELECT post_type FROM {$wpdb->posts} WHERE ID={$ID}";
		$ret = $wpdb->get_var($query);
		$ids_for_insert = array();
		
		if($ret === 'product')//check by post_type
		{
			if($bcreatevars)
				return;
//			$parentid == 0;
//			$key_for_cache = ((string)$ID).$taxonomy_slug;
			
			//check for cache from a child, add only, their attribute has been added and cached
			if(array_key_exists($key_for_cache,$parentattrs_cache))
			{
				$cached_ids = $parentattrs_cache[$key_for_cache];
				if(is_array($cached_ids))
				{
					foreach($cat_ids as $val_id)
					{//as ids
						if(!array_key_exists($val_id,$cached_ids))	
						{
							$ids_for_insert[] = $val_id;
						}
					}
					if(count($ids_for_insert) > 0)
					{
						$ids_for_insert = array_map( 'intval', $ids_for_insert );
						$ids_for_insert = array_unique( $ids_for_insert );
						wp_set_object_terms($ID,$ids_for_insert,$taxonomy_slug,true);
						$cached_ids = array_merge($cached_ids,$ids_for_insert);
						$parentattrs_cache[$key_for_cache] = $cached_ids;
					}
				}
			}else
			{//set and DON'T insert in cache
				if(count($cat_ids) === 1 && $cat_ids[0] === "")
				{
					unset($cat_ids);
					$cat_ids = array();
				}
				if(count($cat_ids) === 0)
				{
					wp_set_object_terms($ID,NULL,$taxonomy_slug);
				}else
				{
					$cat_ids = array_map( 'intval', $cat_ids );
					$cat_ids = array_unique( $cat_ids );
					wp_set_object_terms($ID,$cat_ids,$taxonomy_slug);
				}
				
				$bvariable = false;
				if(is_object_in_term( $ID, 'product_type', 'variable' ))
					$bvariable = true;
						
				if($countdata === 1 || !$bvariable)
				{//single parent, check if variable and update meta
					
					$patt = get_post_meta($ID,'_product_attributes',true);
					if(count($cat_ids) === 0)
					{
						if(is_array($patt) && isset($patt[$new_taxonomy_slug]))
						{
							unset($patt[$new_taxonomy_slug]);
							update_post_meta($ID,'_product_attributes',$patt);
							if($bvariable)
								self::RefreshCustMetaKeys($ID,$patt,$patt,true);
						}
							
					}else
					{
						if(is_array($patt))
						{
							 if(!isset($patt[$new_taxonomy_slug]))
							 {
							 	$patt[$new_taxonomy_slug] = array();
								$patt[$new_taxonomy_slug]["name"] = $taxonomy_slug;
								$patt[$new_taxonomy_slug]["is_visible"]   = 0;
								$patt[$new_taxonomy_slug]["is_taxonomy"]  = 1;
								if($bvariable && !$bdontcheckusedfor)
									$patt[$new_taxonomy_slug]["is_variation"] = 1;
								else
									$patt[$new_taxonomy_slug]["is_variation"] = 0;
								$patt[$new_taxonomy_slug]["value"]  = "";
								$patt[$new_taxonomy_slug]["position"] = count($patt);
								
							 }else
							 {
							 	$patt[$new_taxonomy_slug]["name"] = $taxonomy_slug;
//								$patt[$taxonomy_slug]["is_visible"]   = 0;
								$patt[$new_taxonomy_slug]["is_taxonomy"]  = 1;
//								if($bvariable && !$bdontcheckusedfor)
//									$patt[$taxonomy_slug]["is_variation"] = 1;
//								else
//									$patt[$taxonomy_slug]["is_variation"] = 0;
//								$patt[$taxonomy_slug]["value"]  = "";
							 }
							 update_post_meta($ID,'_product_attributes',$patt);
						}else
						{
							$patt = array();
							$patt[$new_taxonomy_slug] = array();
							$patt[$new_taxonomy_slug]["name"] = $taxonomy_slug;
							$patt[$new_taxonomy_slug]["is_visible"]   = 0;
							$patt[$new_taxonomy_slug]["is_taxonomy"]  = 1;
							if($bvariable && !$bdontcheckusedfor)
								$patt[$new_taxonomy_slug]["is_variation"] = 1;
							else
								$patt[$new_taxonomy_slug]["is_variation"] = 0;
							$patt[$new_taxonomy_slug]["value"]  = "";
							$patt[$new_taxonomy_slug]["position"] = 0;
							update_post_meta($ID,'_product_attributes',$patt);
						}
					}
					self::CallWooAction($ID);
				}
			}
			
		}else
		{
			if($parentid === 0)
				return;
			$attribute = sanitize_title( $attribute );
			if($bcreatevars && $bcustomattr)
			{
				update_post_meta( $ID , $attribute, $slug);
			}
			if(count($cat_ids) > 1)
			{
				$cat_ids = array_splice($cat_ids, 1);
			}
			if(count($cat_ids) === 1 && $cat_ids[0] === "")
			{
				unset($cat_ids);
				$cat_ids = array();
			}
			$cat_ids = array_map( 'intval', $cat_ids );
			$cat_ids = array_unique( $cat_ids );
			if(array_key_exists($key_for_cache,$parentattrs_cache))
			{
				$cached_ids = $parentattrs_cache[$key_for_cache];
				if(is_array($cached_ids))
				{
					foreach($cat_ids as $val_id)
					{//as ids
						if(!array_key_exists($val_id,$cached_ids))	
						{
							$ids_for_insert[] = $val_id;
						}
					}
					if(count($ids_for_insert) > 0)
					{
						$ids_for_insert = array_map( 'intval', $ids_for_insert );
						$ids_for_insert = array_unique( $ids_for_insert );
						wp_set_object_terms($parentid,$ids_for_insert,$taxonomy_slug,true);
						$cached_ids = array_merge($cached_ids,$ids_for_insert);
						$parentattrs_cache[$key_for_cache] = $cached_ids;
//						self::UpdateParentMeta($parentid,$taxonomy_slug);
					}
				}
			}else
			{//set and insert in cache
				if(count($cat_ids) > 0)
				{
					$ids_for_insert = array();
					$product_terms = wp_get_object_terms( $parentid, $taxonomy_slug);
					foreach($product_terms as $term_value)
					{//as ids
						if(!is_object($term_value)) continue;
			   			if(!property_exists($term_value,'term_taxonomy_id')) continue;
						$ids_for_insert[] = $term_value->term_taxonomy_id;
					}
					if(is_array($ids_for_insert))
					{
						if(!array_key_exists($cat_ids[0],$ids_for_insert))	
						{//add taxonomy term
							wp_set_object_terms($parentid,(int)$cat_ids[0],$taxonomy_slug,true);
							$ids_for_insert[] = (int)$cat_ids[0];
//							if(isset($update_parent_attr[$parentid]))
//							{
//								$arr_attrs_update = $update_parent_attr[$parentid];
//								if(!isset($arr_attrs_update[$taxonomy_slug]))
//								{
//									$arr_attrs_update[$taxonomy_slug] = 1;
//								}
//							}else
//							{
//								$arr_attrs_update = array();
//								$arr_attrs_update[$taxonomy_slug] = 1;
//								$update_parent_attr[$parentid] = $arr_attrs_update;
//							}
							if(isset($update_parent_attr[$parentid]))
							{
								$arr_attrs_update = $update_parent_attr[$parentid];
								if(!array_key_exists($taxonomy_slug,$arr_attrs_update))
								{
									$arr_attrs_update[] = $taxonomy_slug;
								}
								$update_parent_attr[$parentid] = $arr_attrs_update;
							}else
							{
								$arr_attrs_update = array();
								$arr_attrs_update[] = $taxonomy_slug;
								$update_parent_attr[$parentid] = $arr_attrs_update;
							}
						}
						$parentattrs_cache[$key_for_cache] = $ids_for_insert;
						self::UpdateParentMeta($parentid,$taxonomy_slug,$bcreatevars);
					}
				}
			}
			if(count($cat_ids) > 0)
			{//get term slug
				$term = get_term( $cat_ids[0], $taxonomy_slug );
				if($term && is_object($term) && property_exists($term,'slug'))
				{
					$slug = $term->slug; 
					update_post_meta( $ID , $attribute, $slug);
				}
			}else
			{
				update_post_meta( $ID , $attribute, '');
			}
			self::CallWooAction($ID);
		}
		
	}
   	
	public static function FindCustomFields($data,$auto = false)
	{
		global $wpdb;
		$meta = $wpdb->postmeta;
		$posts = $wpdb->posts;
		$query = "SELECT post_parent 
					FROM {$posts}
					WHERE ID={$data} AND (post_type='product' OR post_type='product_variation')";
		 if(self::$debugmode)
		 {
	 		$query = "SELECT post_parent 
				FROM {$posts}
				WHERE ID={$data}";
		 }
//		$metas =  $wpdb->get_var($query);
//		if(is_wp_error($metas) || $metas === NULL)
//		{
//			return -1;
//		}		
		
		$query = "SELECT meta_key,meta_value from {$meta} WHERE post_id={$data} AND meta_key NOT IN ('_regular_price','_sale_price','_sku','_weight','_length','_width','_height','_stock','_stock_status','_visibility','_virtual','_download_type','_download_limit','_download_expiry','_downloadable_files','_downloadable','_sale_price_dates_from','_sale_price_dates_to','_tax_class','_tax_status','_backorders','_manage_stock','_featured','_purchase_note','_sold_individually','_product_url','_button_text','_thumbnail_id','_product_image_gallery','_upsell_ids','_crosssell_ids','_product_attributes','_default_attributes','_price','_edit_lock','_edit_last','_min_variation_price','_max_variation_price','_min_price_variation_id','_max_price_variation_id','_min_variation_regular_price','_max_variation_regular_price','_min_regular_price_variation_id','_max_regular_price_variation_id','_min_variation_sale_price','_max_variation_sale_price','_min_sale_price_variation_id','_max_sale_price_variation_id','_file_paths','_variation_description') AND meta_key NOT LIKE 'attribute_%'";
		if($auto)
		{
			$query = "SELECT 
				ID
				FROM {$posts}
				WHERE {$posts}.post_type='product' ORDER BY ID ASC LIMIT 200";
			$metas =  $wpdb->get_results($query);
			$prodids = "";
			foreach($metas as $meta1)
			{
				if($prodids === "")
					$prodids = $meta1->ID;
				else
					$prodids = $prodids.','.$meta1->ID;
			}
			$query = "SELECT DISTINCT meta_key,meta_value from {$meta} WHERE post_id IN ({$prodids}) AND meta_key NOT IN ('_wp_attachment_image_alt','_regular_price','_sale_price','_sku','_weight','_length','_width','_height','_stock','_stock_status','_visibility','_virtual','_download_type','_download_limit','_download_expiry','_downloadable_files','_downloadable','_sale_price_dates_from','_sale_price_dates_to','_tax_class','_tax_status','_backorders','_manage_stock','_featured','_purchase_note','_sold_individually','_product_url','_button_text','_thumbnail_id','_product_image_gallery','_upsell_ids','_crosssell_ids','_product_attributes','_default_attributes','_price','_edit_lock','_edit_last','_min_variation_price','_max_variation_price','_min_price_variation_id','_max_price_variation_id','_min_variation_regular_price','_max_variation_regular_price','_min_regular_price_variation_id','_max_regular_price_variation_id','_min_variation_sale_price','_max_variation_sale_price','_min_sale_price_variation_id','_max_sale_price_variation_id','_file_paths','_variation_description') AND meta_key NOT LIKE 'attribute_%'";
			$metas =  $wpdb->get_results($query);
			$query = "SELECT 
				ID
				FROM {$posts}
				WHERE {$posts}.post_type='product' ORDER BY ID DESC LIMIT 200";
			$metas1 =  $wpdb->get_results($query);
			$prodids = "";
			foreach($metas1 as $meta1)
			{
				if($prodids === "")
					$prodids = $meta1->ID;
				else
					$prodids = $prodids.','.$meta1->ID;
			}
			$query = "SELECT DISTINCT meta_key,meta_value from {$meta} WHERE post_id IN ({$prodids}) AND meta_key NOT IN ('_wp_attachment_image_alt','_regular_price','_sale_price','_sku','_weight','_length','_width','_height','_stock','_stock_status','_visibility','_virtual','_download_type','_download_limit','_download_expiry','_downloadable_files','_downloadable','_sale_price_dates_from','_sale_price_dates_to','_tax_class','_tax_status','_backorders','_manage_stock','_featured','_purchase_note','_sold_individually','_product_url','_button_text','_thumbnail_id','_product_image_gallery','_upsell_ids','_crosssell_ids','_product_attributes','_default_attributes','_price','_edit_lock','_edit_last','_min_variation_price','_max_variation_price','_min_price_variation_id','_max_price_variation_id','_min_variation_regular_price','_max_variation_regular_price','_min_regular_price_variation_id','_max_regular_price_variation_id','_min_variation_sale_price','_max_variation_sale_price','_min_sale_price_variation_id','_max_sale_price_variation_id','_file_paths','_variation_description') AND meta_key NOT LIKE 'attribute_%'";
			$metas1 =  $wpdb->get_results($query);
			$metas = array_merge($metas,$metas1);
			$query = "SELECT 
				ID
				FROM {$posts}
				WHERE {$posts}.post_type='product_variation' ORDER BY ID ASC LIMIT 200";
			$metas1 =  $wpdb->get_results($query);
			$prodids = "";
			foreach($metas1 as $meta1)
			{
				if($prodids === "")
					$prodids = $meta1->ID;
				else
					$prodids = $prodids.','.$meta1->ID;
			}
			$query = "SELECT DISTINCT meta_key,meta_value from {$meta} WHERE post_id IN ({$prodids}) AND meta_key NOT IN ('_wp_attachment_image_alt','_regular_price','_sale_price','_sku','_weight','_length','_width','_height','_stock','_stock_status','_visibility','_virtual','_download_type','_download_limit','_download_expiry','_downloadable_files','_downloadable','_sale_price_dates_from','_sale_price_dates_to','_tax_class','_tax_status','_backorders','_manage_stock','_featured','_purchase_note','_sold_individually','_product_url','_button_text','_thumbnail_id','_product_image_gallery','_upsell_ids','_crosssell_ids','_product_attributes','_default_attributes','_price','_edit_lock','_edit_last','_min_variation_price','_max_variation_price','_min_price_variation_id','_max_price_variation_id','_min_variation_regular_price','_max_variation_regular_price','_min_regular_price_variation_id','_max_regular_price_variation_id','_min_variation_sale_price','_max_variation_sale_price','_min_sale_price_variation_id','_max_sale_price_variation_id','_file_paths','_variation_description') AND meta_key NOT LIKE 'attribute_%'";
			$metas1 =  $wpdb->get_results($query);
			$metas = array_merge($metas,$metas1);
			return $metas;
			
		 }
		 if(self::$debugmode)
		 {
	 		$query = "SELECT meta_key,meta_value from {$meta} WHERE post_id={$data}";
		 }
		$metas =  $wpdb->get_results($query);
		return $metas;
	}
	
	public static function FindCustomTaxonomies()
	{
		$taxonomies = get_taxonomies(array('object_type' => array('product'),'_builtin' => false)); 
		$metas = array();
		$attributes = array();
		$attrmapslugtoname = array();
		self::GetAttributes($attributes,$attrmapslugtoname,false,'',false,true);
		
		foreach ( $taxonomies as $taxonomy ) 
		{
			if($taxonomy !== "product_tag" && $taxonomy !== "product_cat" && $taxonomy !== "product_shipping_class" && $taxonomy !== "product_type" && $taxonomy !== "product_visibility")
			{
				$hasit = false;
				if(is_array($attributes) && !empty($attributes))
				{
					foreach($attributes as $attr)
					{
						if($taxonomy === 'pa_'.$attr->name)
						{
							$hasit = true;
							break;
						}
				    }
				}
				if(!$hasit)
				{
					$taxobj = new stdClass();
					$taxobj->tax = $taxonomy;
					$taxobj->terms = "";
					$args = array(
					    'number'     => 99999,
					    'orderby'    => 'slug',
					    'order'      => 'ASC',
					    'hide_empty' => false,
					    'include'    => '',
						'fields'     => 'all'
					);

					$woo_categories = get_terms($taxonomy, $args );
					$termname = "";
					$counter  = 0;
					foreach($woo_categories as $category)
					{
					    if(!is_object($category)) continue;
					    if(!property_exists($category,'name')) continue;
					    if(!property_exists($category,'term_id')) continue;
						$catname = str_replace('"','\"',$category->name);
						$catname = trim(preg_replace('/\s+/', ' ', $catname));
					   	if($termname === "")
						{
							$termname = $catname;
						}else
						{
							$termname.= ', '. $catname;
						}
						
						if($counter >= 2) break;
						
						$counter++;
					}
					$taxobj->terms = $termname;
					$metas[] = $taxobj;
				}
			}
		}
		$taxonomies = get_taxonomies(array('object_type' => array('product','product_variation'),'_builtin' => false)); 
		foreach ( $taxonomies as $taxonomy ) 
		{
			if($taxonomy !== "product_tag" && $taxonomy !== "product_cat" && $taxonomy !== "product_shipping_class" && $taxonomy !== "product_type"  && $taxonomy !== "product_visibility")
			{
				$hasit = false;
				if(is_array($attributes) && !empty($attributes))
				{
					foreach($attributes as $attr)
					{
						if($taxonomy === 'pa_'.$attr->name)
						{
							$hasit = true;
							break;
						}
				    }
				}
				if(!$hasit)
				{
					$taxobj = new stdClass();
					$taxobj->tax = $taxonomy;
					$taxobj->terms = "";
					$args = array(
					    'number'     => 99999,
					    'orderby'    => 'slug',
					    'order'      => 'ASC',
					    'hide_empty' => false,
					    'include'    => '',
						'fields'     => 'all'
					);

					$woo_categories = get_terms($taxonomy, $args );
					$termname = "";
					$counter  = 0;
					foreach($woo_categories as $category)
					{
					    if(!is_object($category)) continue;
					    if(!property_exists($category,'name')) continue;
					    if(!property_exists($category,'term_id')) continue;
						$catname = str_replace('"','\"',$category->name);
						$catname = trim(preg_replace('/\s+/', ' ', $catname));
					   	if($termname === "")
						{
							$termname = $catname;
						}else
						{
							$termname.= ', '. $catname;
						}
						
						if($counter >= 2) break;
						
						$counter++;
					}
					$taxobj->terms = $termname;
					$metas[] = $taxobj;
				}
			}
		}
		return $metas;
	}
	
	public static function GetFrontPageInfo(&$attributes,&$attributes_mapped,&$attributes_slugs_mapped,&$attr_bulk)
	{
		$attributes1 = array();
		$attrmapslugtoname = array(); 
		self::GetAttributes($attributes1,$attrmapslugtoname,true,'',true);
		foreach($attributes1 as $attr)
		{
			$attr_label = substr($attr->label,0,100);
			$attr_label = preg_replace('/\s+/', ' ', trim($attr_label));
			$key = "attribute_pa_".$attr->name;
			$bulktext = '<tr data-id="'.$key.'"><td>'
			.'<input id="set'.$key.'" type="checkbox" class="bulkset" data-id="'.$key.'" data-type="customtaxh"><label for="set'.$key.'">Set (attr) '.$attr_label.'</label></td><td>'.
			'<select id="bulkadd'.$key.'" class="bulkselect">'.
				'<option value="new">'.__('set new','woocommerce-advbulkedit').'</option>'.
				'<option value="add">'.__('add','woocommerce-advbulkedit').'</option>'.
				'<option value="remove">'.__('remove','woocommerce-advbulkedit').'</option></select> 
				<button class="butnewattribute button" type="button"><span class="icon-plus-outline"></span>new</button><div class="divnewattribute"> 
		   <input class="inputnewattributename" type="text" placeholder="name" data-slug="'.$key.'"></input><br/> 
		   <input class="inputnewattributeslug" type="text" placeholder="slug (optional)"></input><br/> 
		   <button class="butnewattributesave butbulkdialog" style="position:relative;">Ok</button><button class="butnewattributecancel">Cancel</button></div> 
		   <div class="divnewattributeerror"></div> 
				</td><td class="nontextnumbertd"> 
			 <select id="bulk'.$key.'" class="makechosen catselset" style="width:250px;" data-placeholder="choose\\search" multiple ><option value=""></option>';
						  
	 		foreach($attr->values as $value)
			{
				$attrname = str_replace("\n", '\n', str_replace('"', '\"', addcslashes(str_replace("\r", '', (string)$attr->name), "\0..\37'\\")));
				$attrname = trim(preg_replace('/\s+/', ' ', $attrname));
				$attrslug = $value->slug;
				$attrslug = trim(preg_replace('/\s+/', ' ', $attrslug));
				$attrvalname = str_replace("\n", '\n', str_replace('"', '\"', addcslashes(str_replace("\r", '', (string)$value->name), "\0..\37'\\")));
				$attrvalname = trim(preg_replace('/\s+/', ' ', $attrvalname));
				$attributes->{$value->id} = new stdClass();
				$attributes->{$value->id}->id = $value->id;
				$attributes->{$value->id}->term_id = $value->term_id;
				$attributes->{$value->id}->name = $attrvalname;
				$attributes->{$value->id}->attr = $attrname;
				$attributes->{$value->id}->value = $attrslug;
//				echo 'W3Ex.attributes['.$value->id.'] = {id:'.$value->id.',term_id:'.$value->term_id.',name:"'.$attrvalname.'",attr:"'.$attrname.'",value:"'.$attrslug.'"};';
				$attributes_mapped->{$value->term_id} = $value->id;
//				echo 'W3Ex.attributes_mapped['.$value->term_id.'] = '.$value->id.';';
				$attributes_slugs_mapped->{$value->slug.$attr->name} = $value->id;
//				echo 'W3Ex.attributes_slugs_mapped["'.$value->slug.$attr->name.'"] = '.$value->id.';';
				$val_name = substr($value->name,0,100);
				$val_name = preg_replace('/\s+/', ' ', trim($val_name));
				$bulktext.= '<option value="'.$value->term_id.'">'.$val_name.'</option>';
			}
			
//			WriteDebugInfo();
			$bulktext.= '</select></td><td>(<select class="selectvisiblefp" disabled data-id="'.$key.'">'.
						'<option value="skip">skip</option><option value="andset">and set</option><option value="onlyset">only set</option>'.
						'</select>&nbsp;<input type="checkbox" disabled class="visiblefp" data-id="'.$key.'">Visible on p. p.)&nbsp;&nbsp;'.
						'(<select disabled class="selectusedforvars" data-id="'.$key.'"><option value="skip">skip</option><option value="andset">and set</option><option value="onlyset">only set</option>'.
						'</select>&nbsp;<input type="checkbox" disabled class="usedforvars" data-id="'.$key.'">Used for var.)</td></tr>';
			 $attr_bulk->{str_replace("'","\'",$key)."bulk"}= str_replace("'","\'",$bulktext);
//			  $start_memory = memory_get_usage();
//  		 		 $tmp = unserialize(serialize($bulktext));
//    			$mem = memory_get_usage() - $start_memory;
//    			$usage1 = $mem /(1024 * 1024);
//    			$length = strlen($bulktext);
//    			$asd = 10;
		}
		
//		$usage = memory_get_peak_usage();
//		$usage = $usage /(1024 * 1024);
//		$usage1 = memory_get_usage();
//		$usage1 = $usage1 /(1024 * 1024);
//		$asd = 10;
	}
	
	public static function LoadProductsFields(&$dataids,&$retarray,$customparam = NULL)
	{
		if(isset($_POST['colstoload']))
		{
			self::$columns = $_POST['colstoload'];
		}
			
		if(isset($_POST['colstoloadids']))
		{
			$dataids = explode(",",$_POST['colstoloadids']);
		}
		if(empty($dataids))
			return false;
		
		$counter = 0;
		foreach($dataids as $arrrow)
		{
			$var = new stdClass();
			$var->ID = $arrrow;
			$retarray[] = $var;
		}
		$total = 0;
		$hasnext = false;
		$isbegin = false;
		global $wpdb;
		$which = "";
		$which = self::PrepareQuery("wp_posts");
		if($which !== "")
			$which = ",".$which;
		$query = "SELECT CASE WHEN p1.post_parent = 0 THEN p1.ID ELSE p1.post_parent END AS Sort,
		p1.ID,p1.post_parent,p1.post_type{$which}
		FROM {$wpdb->posts} p1
		ORDER BY Sort DESC";
		$info = $wpdb->get_results($query);
		if(is_wp_error($info))
			return false;
		foreach($info as $id)
		{
			foreach($retarray as $item)
			{
				if($item->ID === $id->ID)
				{
					foreach ($id as $key => $value) 
					{
					    $item->{$key} = $id->{$key};
					}
					break;
				}
			}
			
		}
		self::loadProducts(null,null,null,null,null,$customparam,$total,false,false,$hasnext,$isbegin,false,null,null,null,null,null,$retarray);
		return true;
	}
	
	public static function fopen_utf8($filename)
	{
		if (!file_exists($filename) || !is_readable($filename)) 
			return 0;
	    $encoding='';
	    $handle = fopen($filename, 'r');
	    $bom = fread($handle, 2);
	    rewind($handle);

	    if($bom === chr(0xff).chr(0xfe)  || $bom === chr(0xfe).chr(0xff)){
	            // UTF16 Byte Order Mark present
	        $encoding = 'UTF-16';
	    }
	//        $encoding = mb_detect_encoding($file_sample , 'UTF-8, UTF-7, ASCII, EUC-JP,SJIS, eucJP-win, SJIS-win, JIS, ISO-2022-JP');
		 $bytes = fread($handle, 3);
		 if ($bytes != pack('CCC', 0xef, 0xbb, 0xbf)) 
		 {
		 	rewind($handle);
		 }
		 if($encoding != ''){
		 	stream_filter_append($handle, 'convert.iconv.'.$encoding.'/UTF-8');
		 }
	    return  ($handle);
	} 

    public static function ajax()
    {
		$nonce = $_POST['nonce'];
		if(!wp_verify_nonce( $nonce, 'w3ex-advbedit-nonce' ) )
		{
			$arr = array(
			  'success'=>'no-nonce',
			  'products' => array()
			);
			echo json_encode($arr);
			die();
		}

		$type = $_POST['type'];
		
		$data = array();
		if(isset($_POST['data']))
			$data = $_POST['data'];
		$children = array();
		if(isset($_POST['children']))
			$children = $_POST['children'];
		$columns = array();
		if(isset($_POST['columns']))
			$columns = $_POST['columns'];
		$extrafield = '';
		if(isset($_POST['extrafield']))
			$extrafield = $_POST['extrafield'];
		$response = '';
		$arr = array(
		  'success'=>'yes',
		  'products' => array()
		);
		$total = 0;
		$ispagination = false;
		$isnext = true;
		if(isset($_POST['ispagination']))
		{
			if($_POST['ispagination'] == "true")
				$ispagination = true;
		}
		if(isset($_POST['isnext']))
		{
			if($_POST['isnext'] == "false")
				$isnext = false;
		}
		self::$bwoosave = false;
		self::$bsavepost = false;
		$curr_settings = get_option('w3exabe_settings');
		if(is_array($curr_settings))
		{
			if(isset($curr_settings['calldoaction']))
			{
				if($curr_settings['calldoaction'] == 1)
				{
					self::$bwoosave = true;
				}
			}
			if(isset($curr_settings['calldosavepost']))
			{
				if($curr_settings['calldosavepost'] == 1)
				{
					self::$bsavepost = true;
				}
			}
			if(isset($curr_settings['debugmode']))
			{
				if($curr_settings['debugmode'] == 1)
				{
					self::$debugmode = true;
				}
			}
			if(isset($curr_settings['iswoocostog']) && $curr_settings['iswoocostog'] == 1)
			{
				self::$bhandlewoocog = true;
			}
		}

		global $wpdb;
		
		switch($type){
			case 'fileupload':
			{
				$files = array();
			    $filename = dirname(__FILE__).'/uploadedfile.csv';
			    foreach($_FILES as $file)
			    {
			        if(move_uploaded_file($file['tmp_name'], $filename))
			        {
			            $files[] = $filename;
			        }
			        else
			        {
			            $error = true;
			        }
			    }
			    if($error)
			    {
					 $arr['error'] = array('error' => 'There was an error uploading your files');
				}else
				{
//					 $arr['products'] = array('files' => $files);
					    $c = 0;
					    $d = ',';
					    $l = 999999;
						$headers;
						ini_set("auto_detect_line_endings", true);
						$res = self::fopen_utf8($filename);
						
						if($res !== 0)
						{
						    while ($keys = fgetcsv($res, $l, $d)) {
								
									$str = implode("",$keys);
									trim($str);
								if(function_exists('mb_strlen'))
								{
									if(mb_strlen($str) === 0)
									{
										continue;
									}
								}else
								{
									if(strlen($str) === 0)
									{
										continue;
									}
								}
								if($c==0)
								{
									$headers = $keys;
								}else
								 {
						        	$number_of_fields = count($headers);
									$prod = new stdClass();
									for ($i=0; $i < $number_of_fields; $i++)
							        {
										if(isset($keys[$i]))
										{
											if(function_exists('mb_convert_encoding'))
											{
//												$text = iconv(mb_detect_encoding($text, mb_detect_order(), true), "UTF-8", $keys[$i]);
//												if($text !== false)
//												$prod->{$headers[$i]}  =  $text;
												$prod->{$headers[$i]}  =  mb_convert_encoding($keys[$i], "UTF-8");
											}else
												$prod->{$headers[$i]} = $keys[$i];
										}
							        }
									
									$data[] = $prod;									
									
						         }
						        $c++;
							   }

							    fclose($res);
							
								if (file_exists($filename))
								{
									@unlink($filename);
								} 
								$arr['products'] = $data;
						 }
				}
			   
			}break;
			case 'newattribute':
			{
				if(isset($_POST['name']) && isset($_POST['attrslug']))
				{
					$ret = array();
					$args = array();
					$iscat = false;
					if(isset($_POST['iscat']))
						$iscat = true;
					$attrslug = $_POST['attrslug'];
					if(strpos($attrslug,'attribute_') === 0 && strlen($attrslug) > 10)
					{
						$attrslug =  substr($attrslug,10);
					}else
					{
						$iscat = true;
					}
					if(isset($_POST['slug']))
					{
						$args['slug'] = $_POST['slug'];
					}
					if(isset($_POST['parent']))
					{
						$args['parent'] = $_POST['parent'];
						$parent = (int)$_POST['parent'];
						$level = 1;
						if($parent > 0)
						{
							while(true)
							{
								$term = get_term( $parent, $attrslug);
								if(is_wp_error($term))
									break;
								if($term->parent === 0)
									break;
								$parent = $term->parent;
								$level++;
							}
						}
						$arr['level'] = $level;
					}
					
					$ret = wp_insert_term($_POST['name'],$attrslug,$args);
					if(is_wp_error($ret))
					{
						$arr['success'] = 'no';
						$arr['products'] = $ret;
						echo json_encode($arr);
						return;
					}
					$arr['products'] = $ret;
					$attributes = new stdClass();
					$attributes_mapped = new stdClass();
					$attributes_slugs_mapped = new stdClass();
					$term = get_term( $ret['term_id'], $attrslug );
					if(!is_wp_error($term) && !$iscat)
					{
						$attrname = $attrslug;
						if(strpos($attrname,'pa_') === 0 && strlen($attrname) > 3)
						{
							$attrname =  substr($attrname,3);
						}
						$attrname = str_replace("\n", '\n', str_replace('"', '\"', addcslashes(str_replace("\r", '', $attrname), "\0..\37'\\")));
						$attrname = trim(preg_replace('/\s+/', ' ', $attrname));
						$attrslug = $term->slug;
						$attrslug = trim(preg_replace('/\s+/', ' ', $attrslug));
						$attrvalname = str_replace("\n", '\n', str_replace('"', '\"', addcslashes(str_replace("\r", '', (string)$term->name), "\0..\37'\\")));
						$attrvalname = trim(preg_replace('/\s+/', ' ', $attrvalname));
						$attributes->{$term->term_taxonomy_id} = new stdClass();
						$attributes->{$term->term_taxonomy_id}->id = $term->term_taxonomy_id;
						$attributes->{$term->term_taxonomy_id}->term_id = $term->term_id;
						$attributes->{$term->term_taxonomy_id}->name = $attrvalname;
						$attributes->{$term->term_taxonomy_id}->attr = $attrname;
						$attributes->{$term->term_taxonomy_id}->value = $attrslug;
						$attributes_mapped->{$term->term_id} = $term->term_taxonomy_id;
						$attributes_slugs_mapped->{$term->slug.$attrname} = $term->term_taxonomy_id;
						$arr['attributes'] = $attributes;
						$arr['attributes_mapped'] = $attributes_mapped;
						$arr['attributes_slugs_mapped'] = $attributes_slugs_mapped;
					}
					
				}
			}break;
			case 'loadfrontpageinfo':
			{
				$attributes = new stdClass();
				$attributes_mapped = new stdClass();
				$attributes_slugs_mapped = new stdClass();
				$attr_cols = new stdClass();
				$attr_bulk = new stdClass();
				self::GetFrontPageInfo($attributes,$attributes_mapped,$attributes_slugs_mapped,$attr_bulk);
				$arr['attributes'] = $attributes;
				$arr['attributes_mapped'] = $attributes_mapped;
				$arr['attributes_slugs_mapped'] = $attributes_slugs_mapped;
				$arr['attr_bulk'] = $attr_bulk;
			}break;
			case 'loadproducts':
			{
				$titleparam = NULL;
				if(isset($_POST['titleparam']))
				   $titleparam = $_POST['titleparam'];
				$catparams = NULL;
				if(isset($_POST['catparams']))
					$catparams = $_POST['catparams'];
				$categoryor = false;
				if(isset($_POST['categoryor']))
					$categoryor = true;	
				$attrparams = NULL;
				if(isset($_POST['attrparams']))
					$attrparams = $_POST['attrparams'];
				$priceparam = NULL;
				if(isset($_POST['priceparam']))
					$priceparam = $_POST['priceparam'];
				$saleparam = NULL;
				if(isset($_POST['saleparam']))
					$saleparam = $_POST['saleparam'];
				$customparam = NULL;
				if(isset($_POST['customparam']))
					$customparam = $_POST['customparam'];
				$skuparam = NULL;
				if(isset($_POST['skuparam']))
				   $skuparam = $_POST['skuparam'];
				$tagsparams = NULL;
				if(isset($_POST['tagsparams']))
					$tagsparams = $_POST['tagsparams'];
				$descparam = NULL;
				if(isset($_POST['descparam']))
					$descparam = $_POST['descparam'];
				$shortdescparam = NULL;
				if(isset($_POST['shortdescparam']))
					$shortdescparam = $_POST['shortdescparam'];
				$reserved = NULL;
				if(isset($_POST['reserved']))
					$reserved  = $_POST['reserved'];
				$hasnext = false;
				$isbegin = false;
//				break;
				if(isset($_POST['isvariations']))
				{
					$curr_settings = get_option('w3exabe_settings');
					if(!is_array($curr_settings))
						$curr_settings = array();
					if($_POST['isvariations'] === "true")
						$curr_settings['isvariations'] = 1;
					else
						$curr_settings['isvariations'] = 0;
					update_option('w3exabe_settings',$curr_settings);
				}
				$custsearchparam = array();
				if(isset($_POST['custsearchparam']))
					$custsearchparam = $_POST['custsearchparam'];
//				$extrainfo = 
				self::$columns = $columns;
				$ret = self::loadProducts($titleparam,$catparams,$attrparams,$priceparam,$saleparam,$customparam,$total,$ispagination,$isnext,$hasnext,$isbegin,$categoryor,$skuparam,$tagsparams,$descparam,$shortdescparam,$custsearchparam,NULL,$reserved);
				if(is_wp_error($ret) || -1 === $ret)
				{
					$arr['success'] = 'no';
					if(is_wp_error($ret))
					{
						$arr['error'] = $ret;
						echo json_encode($arr);
						return;
					}
				}
				$arr['products'] = $ret;
				$arr['mapattrs'] = self::$mapcustom;
				self::$mapcustom = array();
				$arr['total'] = $total;
				$arr['hasnext'] = $hasnext;
				$arr['isbegin'] = $isbegin;
			}break;
			case 'getdebuginfo':
			{
//				$curr_settings = get_option('w3exabe_settings');
//				if(!is_array($curr_settings))
//					$curr_settings = array();
//				$retstr = $curr_settings['debuginfo'];
				$retstr = "";
				$retarr = get_option('w3exabe_debuginfo');
				if(!is_array($retarr))
					$retarr = array();
				foreach($retarr as  $value)
				{
					if($value !== "")
						$retstr.= '<br/>'.$value;
				}
				
				$arr['debuginfo'] = $retstr;
			}break;
			case 'saveproducts':
			{
				
				$newarr = array();
				$newcarr = array();
				$currentpos = -1; //-1 for no batches
				$batchnumber = 50;
				$settings = get_option('w3exabe_settings');
				if(!is_array($settings)) $settings = array();
				
				
				if(!isset($settings['savebatch']))
				{
					$settings['savebatch'] = 50;
				}
				
				if(isset($settings['savebatch']) && is_numeric($settings['savebatch']))
				{	
					$currentpos = 0;
					$batchnumber = (int)$settings['savebatch'];
					if(isset($settings['currentbatch']) && is_numeric($settings['currentbatch']))
					{
						$currentpos = (int)$settings['currentbatch'];
						if($currentpos === -1)
							$currentpos = 0;
					}
					if(isset($_POST['isfirst']))
					{
						$currentpos = 0;
						$settings['currentbatch'] = 0;
					}
				}
				
				self::convertSaveArrays($data,$newarr,$children,$newcarr);
				$ret = self::saveProducts($newarr,$newcarr,$currentpos,$batchnumber);
				if(!is_wp_error($ret) && is_array($ret))
					$arr['products'] = $ret;
				if(!is_array($settings)) $settings = array();
				if($currentpos !== -1)
				{
					$currentprodnumber = $currentpos * $batchnumber;
					if($currentprodnumber < count($newarr))
					{
						$settings['currentbatch'] = $currentpos;
						$arr['savingbatch'] = $currentpos;
						$arr['hasmore'] = 1;
					}else
					{
						$settings['currentbatch'] = -1;
						$arr['hasmore'] = 0;
					}
					$arr['totalcount'] = count($newarr);
					$arr['totalbatches'] = $settings['savebatch'];
				}else
				{
					$settings['currentbatch'] = -1;
					$arr['hasmore'] = 0;
				}
				if(isset($_POST['filters']))
				   $settings['filterstate'] = $_POST['filters'];
				update_option('w3exabe_settings',$settings);
				update_option('w3exabe_columns',$columns);
			}break;
			case 'getcustomslugs':
			{
				$ret = array();
				foreach($data as $valuearr)
				{
					$ret[$valuearr['name']] = sanitize_title($valuearr['name']);
					$ret[$ret[$valuearr['name']]] = $valuearr['name'];
					$values = array_map( 'trim', explode( WC_DELIMITER, $valuearr['value'] ) );
					foreach ( $values as $value ) 
					{
						if(!isset($ret[$value]))
						{
							$ret[$value] = sanitize_title($value);
						}
//						$ret[$ret[$value]] = $value;
					} 
				}
				$arr['products'] = $ret;
			}break;
			case 'newview':
			{
				if(isset($_POST['viewname']) && isset($_POST['columns']))
				{
					$curr_settings = get_option('w3exabe_views');
					if(!is_array($curr_settings))
					{
						$curr_settings = array();
					}
					$curr_settings[$_POST['viewname']] = $_POST['columns'];
					update_option('w3exabe_views',$curr_settings);
					update_option('w3exabe_columns',$data);
				}
			}break;
			case 'editviews':
			{
				update_option('w3exabe_views',$data);
			}break;
			case 'createvariations':
			{
				$newarr = array();
				$newcarr = array();
				self::convertSaveArrays($data,$newarr,$children,$newcarr,true);
				$skipdups = true;
				if(!isset($_POST['skipdups']))
				{
					$skipdups = false;
				}else
				{
					$skipdups = true;
				}
				$currentpos = 0;
				$batchnumber = 3;
				$settings = get_option('w3exabe_settings');
				if(!is_array($settings))
					$settings = array();
				{
//					if(isset($settings['savebatch']) && is_numeric($settings['savebatch']))
					{	
						$currentpos = 0;
						if(isset($_POST['firstbatch']))
							$settings['currentbatchvars'] = 0;
						if(isset($settings['currentbatchvars']) && is_numeric($settings['currentbatchvars']))
						{
							$currentpos = (int)$settings['currentbatchvars'];
							if($currentpos === -1)
								$currentpos = 0;
						}
					}
				}
				$ret = self::addVariations($newarr,$newcarr,$currentpos,$batchnumber,$skipdups);
				if(is_wp_error($ret) || -1 === $ret)
				{
					$arr['success'] = 'no';
					if(is_wp_error($ret))
					{
						$arr['error'] = $ret;
						echo json_encode($arr);
						return;
					}
				}
				$currentpos++;
				if($currentpos !== -1)
				{
					$currentprodnumber = $currentpos * $batchnumber;
//					if($currentprodnumber < count($newarr))
					{
						$settings['currentbatchvars'] = $currentpos;
						$arr['savingbatch'] = $currentpos;
						$arr['hasmore'] = 1;
					}
//					else
//					{
//						$settings['currentbatchvars'] = -1;
//						$arr['hasmore'] = 0;
//					}
				}else
				{
					$settings['currentbatchvars'] = -1;
					$arr['hasmore'] = 0;
				}
				update_option('w3exabe_settings',$settings);
				$arr['products'] = $ret;
				if(!empty(self::$mapcustom))
				{
					$arr['mapattrs'] = self::$mapcustom;
					self::$mapcustom = array();
				}
			}break;
			case 'createproducts':
			{
				$prodcount = 1;
				if(isset($_POST['prodcount']))
				{
					$prodcount = (int)$_POST['prodcount'];
					if($prodcount < 1)
						$prodcount = 1;
					if($prodcount > 100)
						$prodcount = 100;	
				}
				$ret = self::addProducts($prodcount);
				if(is_wp_error($ret) || -1 === $ret)
				{
					$arr['success'] = 'no';
					if(is_wp_error($ret))
					{
						$arr['error'] = $ret;
						echo json_encode($arr);
						return;
					}
				}
				$arr['products'] = $ret;
			}break;
			case 'loadgroupedproducts':
			{
				$total = 0;
				$hasnext = false;
				$isbegin = false;
				$arrgrouped = array();
				foreach($data as $prodid)
				{
//					$prod = new stdClass();
//					$prod->ID = $prodid;
					$prod = get_post($prodid);
					if($prod === null) continue;
					$prod->ID = (string)$prod->ID;
					$prod->post_parent = (string)$prod->post_parent;
					$prod->menu_order = (string)$prod->menu_order;
					$arrgrouped[] = $prod;
				}
								
				self::loadProducts(null,null,null,null,null,null,$total,false,false,$hasnext,$isbegin,false,null,null,null,null,null,$arrgrouped);
				$arr['products'] = $arrgrouped;
			}break;
			case 'duplicateproducts':
			{
				$newarr = array();
				$newcarr = array();
				$count = 1;
				if(isset($_POST['dupcount']))
				{
					$count = $_POST['dupcount'];
					$count = (int)$count;
					if($count <= 0) $count = 1;
					if($count > 100) $count = 100;
				}
				self::convertSaveArrays($data,$newarr,$children,$newcarr);
				$ret = self::duplicateProducts($newarr,$count);
				if(is_wp_error($ret) || -1 === $ret)
				{
					$arr['success'] = 'no';
					if(is_wp_error($ret))
					{
						$arr['error'] = $ret;
						echo json_encode($arr);
						return;
					}
				}
				$arr['products'] = $ret;
			}break;
			case 'loadparents':
			{
				$retarray = array();
		
				$counter = 0;
				foreach($data as $arrrow)
				{
					$var = new stdClass();
					$var->ID = $arrrow;
					$retarray[] = $var;
				}
				$total = 0;
				$hasnext = false;
				$isbegin = false;
				global $wpdb;
				$query = "SELECT CASE WHEN p1.post_parent = 0 THEN p1.ID ELSE p1.post_parent END AS Sort,
				p1.ID,p1.post_title,p1.post_parent,p1.post_status,p1.post_content,p1.post_excerpt,p1.post_name,p1.post_date,p1.comment_status,p1.menu_order,p1.post_type
				FROM {$wpdb->posts} p1
				ORDER BY Sort DESC";
				$info = $wpdb->get_results($query);
				foreach($info as $id)
				{
					foreach($retarray as $item)
					{
						if($item->ID === $id->ID)
						{
							$item->post_title = $id->post_title;
							$item->post_parent = $id->post_parent;
							$item->post_status = $id->post_status;
							$item->post_content = $id->post_content;
							$item->post_excerpt = $id->post_excerpt;
							
							$item->post_name = $id->post_name;
							$item->post_date = $id->post_date;
							$item->comment_status = $id->comment_status;
							$item->menu_order = $id->menu_order;
							$item->post_type = $id->post_type;
							break;
						}
					}
					
				}
				self::loadProducts(null,null,null,null,null,null,$total,false,false,$hasnext,$isbegin,false,null,null,null,null,null,$retarray);
				
				if(is_wp_error($retarray) || -1 === $retarray)
				{
					$arr['success'] = 'no';
					if(is_wp_error($retarray))
					{
						$arr['error'] = $retarray;
						echo json_encode($retarray);
						return;
					}
				}
				$arr['products'] = $retarray;
			}break;
			case 'deleteproducts':
			{
				$newarr = array();
				$newcarr = array();
				$currentpos = 0;
				$batchnumber = 3;
				self::convertSaveArrays($data,$newarr,$children,$newcarr);
				$deltype = "0";
				if(isset($_POST['deletetype']))
				{
					$deltype = $_POST['deletetype'];
				}
				$deleteinternal = false;
				$settings = get_option('w3exabe_settings');
				if(is_array($settings))
				{
					if(isset($settings['deleteinternal']))
					{
						if($settings['deleteinternal'] === "1")
						{
							$deleteinternal = true;
						}
					}
					{	
						$currentpos = 0;
						if(isset($_POST['firstbatch']))
							$settings['currentbatchvars'] = 0;
						if(isset($settings['currentbatchvars']) && is_numeric($settings['currentbatchvars']))
						{
							$currentpos = (int)$settings['currentbatchvars'];
							if($currentpos === -1)
								$currentpos = 0;
						}
					}
				}
				self::deleteProducts($newarr,$deltype,$currentpos,$batchnumber,$deleteinternal);
				$currentpos++;
				if($currentpos !== -1)
				{
					$currentprodnumber = $currentpos * $batchnumber;
					{
						$settings['currentbatchvars'] = $currentpos;
						$arr['savingbatch'] = $currentpos;
						$arr['hasmore'] = 1;
					}
				}else
				{
					$settings['currentbatchvars'] = -1;
					$arr['hasmore'] = 0;
				}
				update_option('w3exabe_settings',$settings);
			}break;
			case 'savecolumns':
			{
				if(!empty($data))
					update_option('w3exabe_columns',$data);
				$retarray = array();
				$dataids = array();
				
				if(self::LoadProductsFields($dataids,$retarray))
				{
					if(is_wp_error($retarray) || -1 === $retarray)
					{
						$arr['success'] = 'no';
						if(is_wp_error($retarray))
						{
							$arr['error'] = $retarray;
							echo json_encode($retarray);
							return;
						}
					}
					$arr['products'] = $retarray;
				}
				
				
			}break;
			case 'savecustom':
			{
				if(isset($_POST['foreditor']))
				{
					if(strpos($data,'attribute_') === 0 && strlen($data) > 10)
					{
						$taxname =  substr($data,10);
						$bulktext =  '<div class="'.$data.'">';
						$bulktext.= '<ul class="categorychecklist form-no-clear">';
						$args = array(
							'descendants_and_self'  => 0,
							'selected_cats'         => false,
							'popular_cats'          => false,
							'walker'                => null,
							'taxonomy'              => $taxname,
							'checked_ontop'         => true
						);
						ob_start();
						wp_terms_checklist( 0, $args );
						$bulktext.= ob_get_clean();
						$bulktext.= '</ul></div>';
						$arr['editortext'] = $bulktext;
						break;
					}
					
				}
				if(is_array($data) && !empty($data))
				{
					foreach($data as $key => $innerarray)
					{
						if(isset($innerarray['type']))
						{
							if($innerarray['type'] === 'customh' || $innerarray['type'] === 'custom')
							{
								if(taxonomy_exists($key))
								{
//									'<td>'
//										.'<input id="set'.$key.'" type="checkbox" class="bulkset" data-id="'.$key.'" data-type="customtaxh"><label for="set'.$key.'">Set '.$key.'</label></td><td></td><td>'
									$bulktext = ' class="makechosen catselset" style="width:250px;" data-placeholder="select" multiple ><option value=""></option>';
										   $args = array(
										    'number'     => 99999,
										    'orderby'    => 'slug',
										    'order'      => 'ASC',
										    'hide_empty' => false,
										    'include'    => '',
											'fields'     => 'all'
										);

										$woo_categories = get_terms($key, $args );
										if(is_wp_error($woo_categories))
											continue;
										foreach($woo_categories as $category)
										{
										    if(!is_object($category)) continue;
										    if(!property_exists($category,'name')) continue;
										    if(!property_exists($category,'term_id')) continue;
											$catname = str_replace('"','\"',$category->name);
											$catname = trim(preg_replace('/\s+/', ' ', $catname));
										   	$bulktext.= '<option value="'.$category->term_id.'" >'.$catname.'</option>';
										}
										$bulktext.= '</select>';
										//</td><td></td>
										$arr[$key] = $bulktext;
										if($innerarray['type'] === 'customh')
										{
											$bulktext =  '<div class="'.$key.'">';
											$bulktext.= '<ul class="categorychecklist form-no-clear">';
											$args = array(
												'descendants_and_self'  => 0,
												'selected_cats'         => false,
												'popular_cats'          => false,
												'walker'                => null,
												'taxonomy'              => $key,
												'checked_ontop'         => true
											);
											ob_start();
											wp_terms_checklist( 0, $args );
											$bulktext.= ob_get_clean();
											$bulktext.= '</ul></div>';
											$arr[$key.'edit'] = $bulktext;
										}
								}
								continue;
							}
						}
					}
				}
				update_option('w3exabe_custom',$data);
				$arr['customfieldsdata'] = $data;
				update_option('w3exabe_columns',$columns);
				$retarray = array();
				$dataids = array();
				$customparam = array();
				if(isset($_POST['colstoload']))
				{
					$customparam = $_POST['colstoload'];
				}
				if(self::LoadProductsFields($dataids,$retarray,$customparam))
				{
					if(is_wp_error($retarray) || -1 === $retarray)
					{
						$arr['success'] = 'no';
						if(is_wp_error($retarray))
						{
							$arr['error'] = $retarray;
							echo json_encode($retarray);
							return;
						}
					}
					$arr['products'] = $retarray;
				}
			}break;
			case 'exportproducts':
			{
				$filename = self::exportProducts($data,$children);
				$arr['products'] = plugin_dir_url(__FILE__).$filename;
			}break;
			case 'setthumb':
			{
				$itemids = explode(',',$data[0]);
				foreach($itemids as $id)
				{
					update_post_meta( $id , '_thumbnail_id', $data[1]);
					$query = "UPDATE {$wpdb->posts} SET post_parent='".$id."' WHERE ID={$data[1]}";
					$wpdb->query($query);
					clean_post_cache( $id );
					self::CallWooAction($id);
				}
			}break;
			case 'setgallery':
			{
				$itemids = explode(',',$data[0]);
				$deleteattach = false;
				if(is_array($curr_settings))
				{
					if(isset($curr_settings['deleteimages']) && $curr_settings['deleteimages'] == 1)
					{
						$deleteattach = true;
					}
				}
				foreach($itemids as $id)
				{
					if($deleteattach)
					{
						$thumbids = get_post_meta($id, '_product_image_gallery',true);
						$oldids = explode(',',$thumbids);
						$newids =  explode(',',$data[1]);
						$idstodelete = array();
						$hasit = false;
						foreach($oldids as $oldid)
						{
							$hasit = false;
							foreach($newids as $newid)
							{
								if($oldid === $newid)
								{
									$hasit = true;
									break;
								}
							}
							if(!$hasit)
								$idstodelete[] = $oldid;
						}
						foreach($idstodelete as $idtodelete)
						{
							wp_delete_attachment($idtodelete,true);
						}
							
					}
//					if ( $wpdb->get_var( $wpdb->prepare( "SELECT post_type FROM {$posts} WHERE ID = %d", $id ) ) === "product_variation" ) 
//					{
//						update_post_meta( $id , 'variation_image_gallery', $data[1]);
//					}
					update_post_meta( $id , '_product_image_gallery', $data[1]);
					$query = "UPDATE {$wpdb->posts} SET post_parent='".$id."' WHERE ID={$data[1]}";
					$wpdb->query($query);
					clean_post_cache( $id );
					self::CallWooAction($id);
				}
			}break;
			case 'removethumb':
			{
				$itemids = explode(',',$data[0]);
				$curr_settings = get_option('w3exabe_settings');
				$deleteattach = false;
				if(is_array($curr_settings))
				{
					if(isset($curr_settings['deleteimages']) && $curr_settings['deleteimages'] == 1)
					{
						$deleteattach = true;
					}
				}
				foreach($itemids as $id)
				{
					
					if($deleteattach)
					{
						$thumbid = get_post_meta($id, '_thumbnail_id',true);
						wp_delete_attachment($thumbid,true);
					}
					delete_post_meta( $id , '_thumbnail_id');
					clean_post_cache( $id );
					self::CallWooAction($id);
				}
				
			}break;
			case 'checkcustom':
			{
				if(!taxonomy_exists($extrafield))
				{
					$arr['error'] = 'does not exist';
				}
				
			}break;
			case 'findcustomfields':
			{
				$arr['customfields'] = self::FindCustomFields($data);
				
			}break;
			case 'findcustomtaxonomies':
			{
				$arr['customfields'] = self::FindCustomTaxonomies();
				
			}break;
			case 'findcustomfieldsauto':
			{
				$arr['customfields'] = self::FindCustomFields($data,true);
				
			}break;
			case 'savesettings':
			{
				$curr_settings = get_option('w3exabe_settings');
				if(is_array($curr_settings))
				{
					$curr_settings['settgetall'] = $data['settgetall'];
					$curr_settings['settgetvars'] = $data['settgetvars'];
					if(isset($data['settlimit']))
						$curr_settings['settlimit'] = $data['settlimit'];
					if(isset($data['incchildren']))
						$curr_settings['incchildren'] = $data['incchildren'];
					if(isset($data['disattributes']))
						$curr_settings['disattributes'] = $data['disattributes'];
					if(isset($data['converttoutf8']))
						$curr_settings['converttoutf8'] = $data['converttoutf8'];
					if(isset($data['dontcheckusedfor']))
						$curr_settings['dontcheckusedfor'] = $data['dontcheckusedfor'];
					if(isset($data['showattributes']))
						$curr_settings['showattributes'] = $data['showattributes'];
					if(isset($data['bgetallvarstaxonomies']))
						$curr_settings['bgetallvarstaxonomies'] = $data['bgetallvarstaxonomies'];
					if(isset($data['disablesafety']))
						$curr_settings['disablesafety'] = $data['disablesafety'];
					if(isset($data['showprices']))
						$curr_settings['showprices'] = $data['showprices'];
					if(isset($data['showskutags']))
						$curr_settings['showskutags'] = $data['showskutags'];
					if(isset($data['showdescriptions']))
						$curr_settings['showdescriptions'] = $data['showdescriptions'];
					if(isset($data['showidsearch']))
						$curr_settings['showidsearch'] = $data['showidsearch'];
					if(isset($data['showstocksearch']))
						$curr_settings['showstocksearch'] = $data['showstocksearch'];
					if(isset($data['calldoaction']))
						$curr_settings['calldoaction'] = $data['calldoaction'];
					if(isset($data['calldosavepost']))
						$curr_settings['calldosavepost'] = $data['calldosavepost'];
					if(isset($data['confirmsave']))
						$curr_settings['confirmsave'] = $data['confirmsave'];
					if(isset($data['tableheight']))
						$curr_settings['tableheight'] = $data['tableheight'];
					if(isset($data['searchfiltersheight']))
						$curr_settings['searchfiltersheight'] = $data['searchfiltersheight'];
					if(isset($data['rowheight']))
						$curr_settings['rowheight'] = $data['rowheight'];
					if(isset($data['savebatch']))
						$curr_settings['savebatch'] = $data['savebatch'];
					if(isset($data['debugmode']))
						$curr_settings['debugmode'] = $data['debugmode'];
					if(isset($data['deleteimages']))
						$curr_settings['deleteimages'] = $data['deleteimages'];
					if(isset($data['deleteinternal']))
						$curr_settings['deleteinternal'] = $data['deleteinternal'];
					if(isset($data['largeattributes']))
						$curr_settings['largeattributes'] = $data['largeattributes'];
					else
						$curr_settings['largeattributes'] = array();
					update_option('w3exabe_settings',$curr_settings);
				}else
				{
					update_option('w3exabe_settings',$data);
				}
				if(isset($data['selcustomfields']))
					update_option('w3exabe_customsel',$data['selcustomfields']);
				else
					update_option('w3exabe_customsel',array());
			}break;
			case 'savecheckshowthumbnails':
			{
				$curr_settings = get_option('w3exabe_settings');
				if(!is_array($curr_settings))
					$curr_settings = array();
				$curr_settings['showthumbnails'] = $_POST['showthumbnails'];
				update_option('w3exabe_settings',$curr_settings);
			}break;
			case 'saveopenimageforedit':
			{
				$curr_settings = get_option('w3exabe_settings');
				if(!is_array($curr_settings))
					$curr_settings = array();
				$curr_settings['openimage'] = $_POST['openimage'];
				update_option('w3exabe_settings',$curr_settings);
			}break;
			case 'saveusebuiltin':
			{
				$curr_settings = get_option('w3exabe_settings');
				if(!is_array($curr_settings))
					$curr_settings = array();
				$curr_settings['usebuiltineditor'] = $_POST['usebuiltineditor'];
				update_option('w3exabe_settings',$curr_settings);
			}break;
			case 'savesettingname':
			{
				$curr_settings = get_option('w3exabe_settings');
				if(!is_array($curr_settings))
					$curr_settings = array();
				$curr_settings[$_POST['settingname']] = 1;
				update_option('w3exabe_settings',$curr_settings);
			}break;
			default:
				break;
		}
//		echo self::json_encode1($arr);
//		$arr['products'] = 'ima razni';
//		$jason = json_encode($arr);
		if(function_exists('memory_get_usage'))
		{
			$usage = memory_get_usage();
			$text = 'Memory usage: '.round($usage /(1024 * 1024),2);
			$arr['memoryusage'] = $text;
		}
		echo json_encode($arr );
//		if(function_exists('mb_convert_encoding'))
//			echo 'ima q';
		return;
 		$errj = json_last_error();
		 switch ($errj) {
        case JSON_ERROR_NONE:
            echo ' - No errors';
        break;
        case JSON_ERROR_DEPTH:
            echo ' - Maximum stack depth exceeded';
        break;
        case JSON_ERROR_STATE_MISMATCH:
            echo ' - Underflow or the modes mismatch';
        break;
        case JSON_ERROR_CTRL_CHAR:
            echo ' - Unexpected control character found';
        break;
        case JSON_ERROR_SYNTAX:
            echo ' - Syntax error, malformed JSON';
        break;
        case JSON_ERROR_UTF8:
            echo ' - Malformed UTF-8 characters, possibly incorrectly encoded';
        break;
        default:
            echo ' - Unknown error';
        break;
    }
//		echo json_last_error();
    }
}

W3ExABulkEditAjaxHandler::ajax();
