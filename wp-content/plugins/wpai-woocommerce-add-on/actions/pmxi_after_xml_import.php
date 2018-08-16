<?php
/**
 *
 * Delete missing variable products if they doesn't have any variations
 *
 * @param $import_id
 */
function pmwi_pmxi_after_xml_import($import_id)
{
	$import = new PMXI_Import_Record();

	$import->getById($import_id);

    // Re-count WooCommerce Terms
	if ( ! $import->isEmpty() and in_array($import->options['custom_type'], array('product', 'product_variation')))
	{
	    $recount_terms_after_import = true;
        $recount_terms_after_import = apply_filters('wp_all_import_recount_terms_after_import', $recount_terms_after_import, $import_id);
	    if ( $recount_terms_after_import && ( ($import->options['create_new_records'] and $import->options['is_keep_former_posts'] == 'yes') or ($import->options['is_keep_former_posts'] == 'no' and ( $import->options['update_all_data'] == 'yes' or $import->options['is_update_categories'] or $import->options['is_update_status'])))) {
            $product_cats = get_terms( 'product_cat', array( 'hide_empty' => false, 'fields' => 'id=>parent' ) );
            _wc_term_recount( $product_cats, get_taxonomy( 'product_cat' ), true, false );
            $product_tags = get_terms( 'product_tag', array( 'hide_empty' => false, 'fields' => 'id=>parent' ) );
            _wc_term_recount( $product_tags, get_taxonomy( 'product_tag' ), true, false );
        }
        $maybe_to_delete = get_option('wp_all_import_products_maybe_to_delete_' . $import_id);
        if ( ! empty($maybe_to_delete)){
            foreach ($maybe_to_delete as $pid){
                $children = get_posts( array(
                    'post_parent' 	=> $pid,
                    'posts_per_page'=> -1,
                    'post_type' 	=> 'product_variation',
                    'fields' 		=> 'ids',
                    'orderby'		=> 'ID',
                    'order'			=> 'ASC',
                    'post_status'	=> array('draft', 'publish', 'trash', 'pending', 'future', 'private')
                ) );

                if ( empty($children) ){
                    wp_delete_post($pid, true);
                }
            }
            delete_option('wp_all_import_products_maybe_to_delete_' . $import_id);
        }
        $maybe_make_simple = get_option('wp_all_import_products_maybe_make_them_simple_' . $import_id);
        if (!empty($maybe_make_simple)){
            foreach ($maybe_make_simple as $pid){
                $children = get_posts( array(
                    'post_parent' 	=> $pid,
                    'posts_per_page'=> -1,
                    'post_type' 	=> 'product_variation',
                    'fields' 		=> 'ids',
                    'orderby'		=> 'ID',
                    'order'			=> 'ASC',
                    'post_status'	=> array('draft', 'publish', 'trash', 'pending', 'future', 'private')
                ) );
                if ( count($children) <= 1){

                    $product_type_term = is_exists_term('simple', 'product_type', 0);
                    if ( ! empty($product_type_term) and ! is_wp_error($product_type_term) ){
                        wp_set_object_terms($pid, array( (int) $product_type_term['term_taxonomy_id'] ), 'product_type');
                    }

                    $table = _get_meta_table('post');

                    global $wpdb;

                    $post_meta_infos = $wpdb->get_results($wpdb->prepare("SELECT meta_key, meta_value FROM ". $table ." WHERE post_id = %d", $pid));

                    foreach ($post_meta_infos as $meta_info) {
                        if (in_array($meta_info->meta_key, array('_regular_price_tmp', '_sale_price_tmp', '_sale_price_dates_from_tmp', '_sale_price_dates_from_tmp', '_sale_price_dates_to_tmp', '_price_tmp', '_stock_tmp', '_stock_status_tmp'))){
                            update_post_meta( $pid, str_replace('_tmp', '', $meta_info->meta_key), $meta_info->meta_value);
                            delete_post_meta( $pid, $meta_info->meta_key );
                        }
                    }
                    do_action('wp_all_import_make_product_simple', $pid, $import_id);
                }
            }
            delete_option('wp_all_import_products_maybe_make_them_simple_' . $import_id);
        }
        delete_option('wp_all_import_not_linked_products_' . $import_id);
	}
}