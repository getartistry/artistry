<?php

function pmwi_pmxi_visible_confirm_sections( $sections, $post_type )
{
	// render order's template only for bundle and import with WP All Import featured
	if ( 'shop_order' == $post_type && class_exists('WooCommerce') ) return array();
	
	return $sections;
}