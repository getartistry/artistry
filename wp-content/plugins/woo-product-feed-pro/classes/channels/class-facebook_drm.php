<?php
/**
 * Settings for Facebook DRM product feeds
 */
class WooSEA_facebook_drm {
	public $facebook_drm;

        public static function get_channel_attributes() {

                $sitename = get_option('blogname');

        	$facebook_drm = array(
			"Remarketing fields" => array(
				"id" => array(
					"name" => "id",
					"feed_name" => "g:id",
					"format" => "required",
					"woo_suggest" => "id",
				),
				"availability" => array(
					"name" => "availability",
					"feed_name" => "g:availability",
					"format" => "required",
					"woo_suggest" => "availability",
				),
				"condition" => array(
					"name" => "condition",
					"feed_name" => "g:condition",
					"format" => "required",
					"woo_suggest" => "condition",
				),
				"description" => array(
					"name" => "description",
					"feed_name" => "g:description",
					"format" => "required",
					"woo_suggest" => "description",
				),
				"image_link" => array(
					"name" => "image_link",
					"feed_name" => "g:image_link",
					"format" => "required",
					"woo_suggest" => "image",
				),
				"link" => array(
					"name" => "link",
					"feed_name" => "g:link",
					"format" => "required",
					"woo_suggest" => "link",
				),
				"title" => array(
					"name" => "title",
					"feed_name" => "g:title",
					"format" => "required",
					"woo_suggest" => "title",
				),
				"price" => array(
					"name" => "price",
					"feed_name" => "g:price",
					"format" => "required",
					"woo_suggest" => "price",
				),
				"gtin" => array(
					"name" => "gtin",
					"feed_name" => "g:gtin",
					"format" => "optional",
				),
				"mpn" => array(
					"name" => "mpn",
					"feed_name" => "g:mpn",
					"format" => "optional",
				),
				"brand" => array(
					"name" => "brand",
					"feed_name" => "g:brand",
					"format" => "required",
				),
				"additional_image_link" => array(
					"name" => "additional_image_link",
					"feed_name" => "g:additional_image_link",
					"format" => "optional",
				),
				"age_group" => array(
					"name" => "age_group",
					"feed_name" => "g:age_group",
					"format" => "optional",
				),
				"color" => array(
					"name" => "color",
					"feed_name" => "g:color",
					"format" => "optional",
				),
				"expiration_date" => array(
					"name" => "expiration_date",
					"feed_name" => "g:expiration_date",
					"format" => "optional",
				),
				"gender" => array(
					"name" => "gender",
					"feed_name" => "g:gender",
					"format" => "optional",
				),
				"item_group_id" => array(
					"name" => "item_group_id",
					"feed_name" => "g:item_group_id",
					"format" => "optional",
					"woo_suggest" => "item_group_id",
				),
				"google_product_category" => array(
					"name" => "google_product_category",
					"feed_name" => "g:google_product_category",
					"format" => "optional",
				),
				"material" => array(
					"name" => "material",
					"feed_name" => "g:material",
					"format" => "optional",
				),
				"pattern" => array(
					"name" => "pattern",
					"feed_name" => "g:pattern",
					"format" => "optional",
				),
				"product_type" => array(
					"name" => "product_type",
					"feed_name" => "g:product_type",
					"format" => "optional",
					"woo_suggest" => "categories",
				),
				"sale_price" => array(
					"name" => "sale_price",
					"feed_name" => "g:sale_price",
					"format" => "optional",
					"woo_suggest" => "sale_price",
				),
				"sale_price_effective_date" => array(
					"name" => "sale_price_effective_date",
					"feed_name" => "g:sale_price_effective_date",
					"format" => "optional",
				),
				"shipping" => array(
					"name" => "shipping",
					"feed_name" => "g:shipping",
					"format" => "optional",
				),
				"country" => array(
					"name" => "country",
					"feed_name" => "g:country",
					"format" => "optional",
				),
				"shipping_weight" => array(
					"name" => "shipping_weight",
					"feed_name" => "g:shipping_weight",
					"format" => "optional",
				),
				"shipping_size" => array(
					"name" => "shipping_size",
					"feed_name" => "g:shipping_size",
					"format" => "optional",
				),
				"custom_label_0" => array(
					"name" => "custom_label_0",
					"feed_name" => "g:custom_label_0",
					"format" => "optional",
				),
				"custom_label_1" => array(
					"name" => "custom_label_1",
					"feed_name" => "g:custom_label_1",
					"format" => "optional",
				),
				"custom_label_2" => array(
					"name" => "custom_label_2",
					"feed_name" => "g:custom_label_2",
					"format" => "optional",
				),
	
				"custom_label_3" => array(
					"name" => "custom_label_3",
					"feed_name" => "g:custom_label_3",
					"format" => "optional",
				),
				"custom_label_4" => array(
					"name" => "custom_label_4",
					"feed_name" => "g:custom_label_4",
					"format" => "optional",
				),
			),
		);
		return $facebook_drm;
	}
}
?>
