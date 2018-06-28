<?php
/**
* This class is called to: retrieve product attributes 
* for populating the rules dropdowns and draggable boxes
*/

class WooSEA_Attributes {

public $attributes;
public $dropdown;
public $standard_attributes;

public function get_channel_countries(){
	$channel_countries = array();
	$channel_configs = get_option ('channel_statics');
	
	foreach ($channel_configs as $key=>$val){
		if (($key != "All countries") && ($key != "Custom Feed")){
			array_push ($channel_countries, $key);
		}
	}
	return $channel_countries;
}

public function get_channels($country){
	$channels = array();
	$channel_configs = get_option ('channel_statics');

	// Lets get the generic channels
	foreach ($channel_configs as $key=>$val){
		if($key == "Custom Feed" || $key == "All countries"){
			$channels = array_merge ($channels, $val);
		}
	}

	// Than get the relevant country channels
	foreach ($channel_configs as $key=>$val){
		if(preg_match("/-$country/i", $key)){
			$channels = array_merge($channels, $val);
		 } elseif ($country == "$key"){
			$channels = array_merge($channels, $val);
		}
	}
	return $channels;
}

private function get_dynamic_attributes(){
	global $wpdb;
	$list = array();

        $no_taxonomies = array("element_category","template_category","portfolio_category","portfolio_skills","portfolio_tags","faq_category","slide-page","yst_prominent_words","category","post_tag","nav_menu","link_category","post_format","product_type","product_visibility","product_cat","product_shipping_class","product_tag");
     	$taxonomies = get_taxonomies();
      	$diff_taxonomies = array_diff($taxonomies, $no_taxonomies);

    	# get custom taxonomy values for a product
    	foreach($diff_taxonomies as $tax_diff){
		$taxonomy_details = get_taxonomy( $tax_diff );

		foreach($taxonomy_details as $kk => $vv){
			if($kk == "name"){
				$pa_short = $vv;
			}
			if($kk == "labels"){
				foreach($vv as $kw => $kv){
					if($kw == "singular_name"){
						$attr_name = $pa_short;
						$attr_name_clean = ucfirst($kv);
					}
				}
			}
		}
               	$list["$attr_name"] = $attr_name_clean;
	}
	return $list;
}

private function get_custom_attributes() {
	global $wpdb;
     	$list = array();

    	$sql = "SELECT meta_key as name, meta_value as type FROM " . $wpdb->prefix . "postmeta" . "  group by meta_key";
     	$data = $wpdb->get_results($sql);


      	if (count($data)) {
     		foreach ($data as $key => $value) {
			if (!preg_match("/pyre|sbg|fusion/i",$value->name)){
				$value_display = str_replace("_", " ",$value->name);
                    		$list["custom_attributes_" . $value->name] = ucfirst($value_display);
            		}
             	}
              	return $list;
     	}
     	return false;
}

public function get_mapping_attributes_dropdown() {
	$sitename = get_option('blogname');
	
	$mapping_attributes = array(
      		"categories" => "Category",
      		"title" => "Product name",
	);

	/**
	 * Create dropdown with main attributes
	 */
	$dropdown = "<option></option>";
	$dropdown .= "<optgroup label='Main attributes'><strong>Main attributes</strong>";
		
	foreach ($mapping_attributes as $key=>$value) {
		if ($key == "categories"){
			$dropdown .= "<option value='$key' selected>" . $value . "</option>";
		} else {
			$dropdown .= "<option value='$key'>" . $value . "</option>";
		}
	}
		
	$dropdown .="</optgroup>";

	$other_attributes = array(
		"all_products" => "Map all products",
	);

	$dropdown .= "<optgroup label='Other options'><strong>Other options</strong>";
	
	foreach ($other_attributes as $key=>$value) {
		$dropdown .= "<option value='$key'>" . $value . "</option>";
	}
	


	return $dropdown;
}

	public function get_special_attributes_dropdown(){
		/**
     		 * Create dropdown with product attributes
     		 */
		$dropdown = "<option></option>";

                $custom_attributes = $this->get_custom_attributes();
                if($custom_attributes){
                        $dropdown .= "<optgroup label='Custom attributes'><strong>Custom attributes</strong>";

                        foreach ($custom_attributes as $key => $value) {
                                if (strpos($value, 0, 1) !== "_") {
					// Exclude total sales
					if($key != "custom_attributes_total_sales"){
						$value = str_replace("attribute","",$value);
                         	               	$dropdown .= "<option value='$key'>" . ucfirst($value) . "</option>";
                               		}
				 }
                        }

                        $dropdown .="</optgroup>";
                }
		return $dropdown;
	}

	public function get_special_attributes_clean(){
                $custom_attributes = $this->get_custom_attributes();
		return $custom_attributes;
	}

        public function get_product_attributes_dropdown() {
		$sitename = get_option('blogname');

        	$attributes = array(
   			"id" => "Product Id",
            		"sku" => "SKU", 
			"sku_id" => "SKU_ID",
			"title" => "Product name",
			"mother_title" => "Product name mother product",
			"description" => "Product description",
            		"short_description" => "Product short description",
            		"price" => "Price",
            		"regular_price" => "Regular price",
			"sale_price" => "Sale price",
            		"net_price" => "Price excl. VAT",
            		"net_regular_price" => "Regular price excl. VAT",
			"net_sale_price" => "Sale price excl. VAT",
			"price_forced" => "Price incl. VAT front end",
			"regular_price_forced" => "Regular price incl. VAT front end",
			"sale_price_forced" => "Sale price incl. VAT front end",
	 		"sale_price_start_date" => "Sale start date",
            		"sale_price_end_date" => "Sale end date",
            		"sale_price_effective_date" => "Sale price effective date",
			"link" => "Link",
            		"currency" => "Currency",
			"categories" => "Category",
			"category_link" => "Category link",
			"category_path" => "Category path",
			"condition" => "Condition",
            		"availability" => "Availability",
            		"quantity" => "Quantity [Stock]",
			"product_type" => "Product Type",
			"publication_date" => "Publication date",
			"item_group_id" => "Item group ID",
			"weight" => "Weight",
            		"width" => "Width",
            		"height" => "Height",
            		"length" => "Length",
			"shipping" => "Shipping price",
            		"visibility" => "Visibility",
            		"rating_total" => "Total rating",
            		"rating_average" => "Average rating",
        	);

        	$images = array(
            		"image" => "Main image",
            		"feature_image" => "Featured image",
            		"image_1" => "Additional image 1",
            		"image_2" => "Additional image 2",
            		"image_3" => "Additional image 3",
            		"image_4" => "Additional image 4",
            		"image_5" => "Additional image 5",
            		"image_6" => "Additional image 6",
            		"image_7" => "Additional image 7",
            		"image_8" => "Additional image 8",
            		"image_9" => "Additional image 9",
            		"image_10" => "Additional image 10",
        	);

		/**
		 * Create dropdown with main attributes
		 */
		$dropdown = "<option></option>";
		$dropdown .= "<optgroup label='Main attributes'><strong>Main attributes</strong>";
		
		foreach ($attributes as $key=>$value) {
			$dropdown .= "<option value='$key'>" . $value . "</option>";
		}
		
		$dropdown .="</optgroup>";

		/**
		 * Create dropdown with image attributes
		 */
		$dropdown .= "<optgroup label='Image attributes'><strong>Image attributes</strong>";
		
		foreach ($images as $key=>$value) {
			$dropdown .= "<option value='$key'>" . $value . "</option>";
		}
		$dropdown .="</optgroup>";

		/**
     		 * Create dropdown with dynamic attributes
     		 */
        	$dynamic_attributes = $this->get_dynamic_attributes();

		if($dynamic_attributes){
			$dropdown .= "<optgroup label='Dynamic attributes'><strong>Dynamic attributes</strong>";

            		foreach ($dynamic_attributes as $key => $value) {
                		if (strpos($value, 0, 1) !== "_") {
                			$dropdown .= "<option value='$key'>" . ucfirst($value) . "</option>";  
                		}
            		}

			$dropdown .="</optgroup>";
    		}

		$dropdown .= "<optgroup label='Google category taxonomy'><strong>Google category taxonomy</strong>";
		$dropdown .= "<option value='google_category'>Google category</option>";              
		$dropdown .="</optgroup>";

                /**
                 * Create dropdown with custom attributes
                 */
                $custom_attributes = $this->get_custom_attributes();

                if($custom_attributes){
                        $dropdown .= "<optgroup label='Custom field attributes'><strong>Custom field attributes</strong>";

                        foreach ($custom_attributes as $key => $value) {
                             	if (!preg_match("/pyre|sbg|fusion/i",$value)){
                                	if (strpos($value, 0, 1) !== "_") {
                                        	$dropdown .= "<option value='$key'>" . ucfirst($value) . "</option>";
                                	}
				}
                        }

                        $dropdown .="</optgroup>";
                }
		return $dropdown;
	}

        public function get_product_attributes() {
                $sitename = get_option('blogname');

                $attributes = array(
                        "id" => "Product Id",
                        "sku" => "SKU", 
			"sku_id" => "SKU_ID",
			"title" => "Product name",
			"mother_title" => "Product name mother product",
			"description" => "Product description",
                        "short_description" => "Product short description",
                        "link" => "Link",
			"image" => "Main image",
			"feature_image" => "Feature image",
                        "product_type" => "Product Type",
                        "publication_date" => "Publication date",
			"currency" => "Currency",
    			"categories" => "Category",
			"raw_categories" => "Category (not used for mapping)",
			"google_category" => "Google category (for rules and filters only)",
			"category_link" => "Category link",
			"category_path" => "Category path",
			"condition" => "Condition",
                        "availability" => "Availability",
                        "quantity" => "Quantity [Stock]",
                        "price" => "Price",
                        "regular_price" => "Regular price",
                        "sale_price" => "Sale price",
                        "net_price" => "Price excl. VAT",
                        "net_regular_price" => "Regular price excl. VAT",
                        "net_sale_price" => "Sale price excl. VAT",
                        "price_forced" => "Price incl. VAT front end",
                        "regular_price_forced" => "Regular price incl. VAT front end",
                        "sale_price_forced" => "Sale price incl. VAT front end",
                        "sale_price_start_date" => "Sale start date",
                        "sale_price_end_date" => "Sale end date",
			"sale_price_effective_date" => "Sale price effective date",
                        "item_group_id" => "Item group ID",
                        "weight" => "Weight",
                        "width" => "Width",
                        "height" => "Height",
                        "length" => "Length",
                        "shipping" => "Shipping price",
			"visibility" => "Visibility",
                        "rating_total" => "Total rating",
                        "rating_average" => "Average rating",
			"amount_sales" => "Amount of sales",
                );

        	$images = array(
            		"image" => "Main image",
            		"feature_image" => "Featured image",
            		"image_1" => "Additional image 1",
            		"image_2" => "Additional image 2",
            		"image_3" => "Additional image 3",
            		"image_4" => "Additional image 4",
            		"image_5" => "Additional image 5",
            		"image_6" => "Additional image 6",
            		"image_7" => "Additional image 7",
            		"image_8" => "Additional image 8",
            		"image_9" => "Additional image 9",
            		"image_10" => "Additional image 10",
        	);

		$attributes = array_merge($attributes, $images);

		if(is_array($this->get_dynamic_attributes())){
        		$dynamic_attributes = $this->get_dynamic_attributes();
			array_walk($dynamic_attributes, function(&$value, $key) { $value .= ' (Dynamic attribute)';});
			$attributes = array_merge($attributes, $dynamic_attributes);
		}

                if(is_array($this->get_custom_attributes())){
			$custom_attributes = $this->get_custom_attributes();
			array_walk($custom_attributes, function(&$value, $key) { $value .= ' (Custom attribute)';});
			$attributes = array_merge($attributes, $custom_attributes);
                }

        	$static = array(
			"installment" => "Installment",
            		"static_value" => "Static value",
			"calculated" => "Plugin calculation",
        	);

		$attributes = array_merge($attributes, $static);
		return $attributes;
	}

        public static function get_standard_attributes($project) {
		$sitename = get_option('blogname');

        	$standard_attributes = array(
   			"id" => "Product Id",
      		      	"title" => "Product name",
      		      	"categories" => "Category",
        	);

		if ($project['taxonomy'] == 'google_shopping'){
			$standard_attributes["google_product_category"] = "Google product category";
		} elseif ($project['taxonomy'] != 'none'){
			$standard_attributes["$project[name]_product_category"] = "$project[name] category";
		}

		return $standard_attributes;
	}
}
