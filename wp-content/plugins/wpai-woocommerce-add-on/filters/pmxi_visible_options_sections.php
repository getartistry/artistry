<?php
function pmwi_pmxi_visible_options_sections( $sections, $post_type )
{
	// render order's options view only for bundle and import with WP All Import featured
	if ( 'shop_order' == $post_type && class_exists('WooCommerce') ) return array('settings');

	return $sections;
}