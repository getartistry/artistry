<?php
function pmwi_pmxi_before_xml_import( $import_id )
{
	delete_option('wp_all_import_' . $import_id . '_parent_product');
	delete_option('wp_all_import_not_linked_products_' . $import_id);
	delete_option('wp_all_import_previously_updated_order_' . $import_id);
    delete_option('wp_all_import_products_maybe_to_delete_' . $import_id);
    delete_option('wp_all_import_products_maybe_make_them_simple_' . $import_id);	
	
	// Make sure all product type terms are in the database.
	$terms = array( 'simple', 'variable', 'grouped', 'external' );
	foreach ( $terms as $term ) {
		if ( ! get_term_by( 'name', $term, 'product_type' ) ) {
			wp_insert_term( $term, 'product_type' );
		}
	}
}