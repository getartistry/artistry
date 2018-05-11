<?php
/**
 *
 * Do not delete missing variable product if it still has variations
 *
 * @param $to_delete
 * @param $pid
 * @param $import
 * @return bool
 */
function pmwi_wp_all_import_is_post_to_delete($to_delete, $pid, $import )
{	
	if ( $import->options['custom_type'] == 'product' && $to_delete && class_exists('WooCommerce'))
	{
        $post_to_delete = get_post($pid);
        switch ($post_to_delete->post_type){
            case 'product':
                $children = get_posts( array(
                    'post_parent' 	=> $pid,
                    'posts_per_page'=> -1,
                    'post_type' 	=> 'product_variation',
                    'fields' 		=> 'ids',
                    'orderby'		=> 'ID',
                    'order'			=> 'ASC',
                    'post_status'	=> array('draft', 'publish', 'trash', 'pending', 'future', 'private')
                ) );

                if ( count($children) ){
                    $to_delete = false;
                    $maybe_to_delete = get_option('wp_all_import_products_maybe_to_delete_' . $import->id, array());
                    if ( ! in_array($pid, $maybe_to_delete) ){
                        $maybe_to_delete[] = $pid;
                        update_option('wp_all_import_products_maybe_to_delete_' . $import->id, $maybe_to_delete);
                    }
                }
                break;
            case 'product_variation':
                if ($import->options['make_simple_product'] && ($import->options['update_all_data'] == 'yes' || $import->options['is_update_product_type'])){
                    $maybe_make_simple = get_option('wp_all_import_products_maybe_make_them_simple_' . $import->id, array());
                    if ( ! in_array($post_to_delete->post_parent, $maybe_make_simple) ){
                        $maybe_make_simple[] = $post_to_delete->post_parent;
                        update_option('wp_all_import_products_maybe_make_them_simple_' . $import->id, $maybe_make_simple);
                    }
                }
                break;
        }
	}
	return $to_delete;
}