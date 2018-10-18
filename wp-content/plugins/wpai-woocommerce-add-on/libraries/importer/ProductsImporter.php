<?php

namespace wpai_woocommerce_add_on\libraries\importer;

require_once dirname(__FILE__) . '/Importer.php';

/**
 * Class ProductsImporter
 * @package wpai_woocommerce_add_on\libraries\importer
 */
class ProductsImporter extends Importer {

    /**
     * @var ImportProductBase
     */
    public $importEngine;

    /**
     * @var \WC_Product
     */
    public $product;

    /**
     * @var bool
     */
    public $isNewProduct;

    /**
     *
     * Import WooCommerce Products
     *
     */
    public function import() {

        $this->log(__('<strong>WooCommerce ADD-ON:</strong>', \PMWI_Plugin::TEXT_DOMAIN));

        $productID = $this->getArticleData('ID');

        $this->isNewProduct = empty($productID) ? TRUE : FALSE;

        $data = $this->getParsedData();

        $productType = empty($data['product_types'][$this->getIndex()]) ? 'simple' : sanitize_title(stripslashes($data['product_types'][$this->getIndex()]));

        if ($this->getImport()->options['update_all_data'] == 'no' && ! $this->getImport()->options['is_update_product_type'] && ! $this->isNewProduct ){
            $productType = \WC_Product_Factory::get_product_type( $this->getPid() );
        }

        $this->getImportService()->pushMeta($this->getPid(), '_wc_review_count', 0, $this->isNewProduct);
        $this->getImportService()->pushMeta($this->getPid(), '_wc_rating_count', 0, $this->isNewProduct);
        $this->getImportService()->pushMeta($this->getPid(), '_wc_average_rating', 0, $this->isNewProduct);

        $className = \WC_Product_Factory::get_product_classname( $this->getPid(), $productType ? $productType : 'simple' );

        $this->product = new $className( $this->getPid() );

        switch ($productType){
            case 'external':
                $this->importEngine = new ImportExternalProduct($this->getIndexObject(), $this->getOptions(), $data);
                break;
            case 'grouped':
                $this->importEngine = new ImportGroupedProduct($this->getIndexObject(), $this->getOptions(), $data);
                break;
            case 'variation':
            case 'variable':
                $this->importEngine = new ImportVariableProduct($this->getIndexObject(), $this->getOptions(), $data);
                break;
            default:
                $this->importEngine = new ImportSimpleProduct($this->getIndexObject(), $this->getOptions(), $data);
                break;
        }
        $this->importEngine->setProduct($this->product);
        $this->importEngine->import();
    }

    /**
     *
     * After Import WooCommerce Products
     *
     * @return void
     * @throws \Exception
     */
    public function afterPostImport() {

        $table = $this->wpdb->posts;

        $p = $this->wpdb->get_row($this->wpdb->prepare("SELECT * FROM $table WHERE ID = %d;", $this->getPid()));

        if ($p) {

            if ($p->post_type == 'product_variation') {
                // Do not import variation as draft.
                if ($this->getImport()->options['create_draft'] == 'yes' && $p->post_status == 'draft') {
                    $this->wpdb->update( $this->wpdb->posts, array('post_status' => 'publish' ), array('ID' => $this->getPid()));
                }
                $this->wpdb->update( $this->wpdb->posts, array( 'post_excerpt' => '', 'post_name' => sanitize_title($p->post_title), 'guid' => '' ), array('ID' => $this->getPid()));
                // Unset missing attributes.
                $attributes = $this->getImportService()->getProductTaxonomies();
                if (!empty($attributes)) {
                    foreach ($attributes as $ctx) {
                        if ( strpos($ctx->name, "pa_") === 0 ) {
                            continue;
                        }
                        $this->getImportService()->getTaxonomiesService()->associateTerms($this->getPid(), NULL, $ctx->name);
                    }
                }
                $post_to_update_id = $p->post_parent;
            }
            else {
                // Unset missing attributes.
                $product_attributes = get_post_meta( $this->getPid(), '_product_attributes', TRUE );
                $attributes = $this->getImportService()->getProductTaxonomies();
                if (!empty($attributes)) {
                    foreach ($attributes as $ctx) {
                        if ( strpos($ctx->name, "pa_") === 0 && ! isset($product_attributes[strtolower(urlencode($ctx->name))]) ){
                            $this->getImportService()->getTaxonomiesService()->associateTerms($this->getPid(), NULL, $ctx->name);
                        }
                    }
                }

                update_post_meta( $this->getPid(), '_product_version', WC_VERSION );

                $post_to_update_id = $this->getPid();

                // Associate linked products.
                $wp_all_import_not_linked_products = get_option('wp_all_import_not_linked_products_' . $this->getImport()->id );
                if (!empty($wp_all_import_not_linked_products)) {
                    $post_to_update_sku = get_post_meta($post_to_update_id, '_sku', TRUE);
                    foreach ($wp_all_import_not_linked_products as $product) {
                        if ($product['pid'] != $post_to_update_id && ! empty($product['not_linked_products'])) {
                            if ( in_array($post_to_update_sku, $product['not_linked_products'])
                                || in_array( (string) $post_to_update_id, $product['not_linked_products'])
                                || in_array($p->post_title, $product['not_linked_products'])
                                || in_array($p->post_name, $product['not_linked_products'])
                            )
                            {
                                $linked_products = get_post_meta($product['pid'], $product['type'], TRUE);
                                if (empty($linked_products)) {
                                    $linked_products = array();
                                }
                                if ( ! in_array($post_to_update_id, $linked_products)) {
                                    $linked_products[] = $post_to_update_id;
                                    $this->getLogger() && call_user_func($this->getLogger(), sprintf(__('Added to %s list of product ID %d.', \PMWI_Plugin::TEXT_DOMAIN), $product['type'] == '_upsell_ids' ? 'Up-Sells' : 'Cross-Sells', $product['pid']) );
                                    update_post_meta($product['pid'], $product['type'], $linked_products);
                                }
                            }
                        }
                    }
                }
            }

            if ($post_to_update_id){
                $postRecord = new \PMXI_Post_Record();
                $postRecord->clear();
                // Find corresponding article among previously imported.
                $postRecord->getBy(array(
                    'unique_key' => 'Variation ' . get_post_meta($post_to_update_id, '_sku', TRUE),
                    'import_id'  => $this->getImport()->id,
                ));
                $pid = ( ! $postRecord->isEmpty() ) ? $postRecord->post_id : FALSE;
                // Update first variation.
                if ( $pid ) {
                    // Check is variation already processed.
                    $is_variation_updated = get_post_meta($pid, '_variation_updated', TRUE);
                    if ( empty($is_variation_updated) ){
                        $table = _get_meta_table('post');
                        $post_meta_infos = $this->wpdb->get_results("SELECT meta_key, meta_value FROM $table WHERE post_id = " . $post_to_update_id );
                        if (!empty($post_meta_infos) && !empty($this->getImport()->options['custom_name'])) {
                            foreach ($post_meta_infos as $meta_info) {
                                if (in_array($meta_info->meta_key, $this->getImport()->options['custom_name'])) {
                                    $this->getImportService()->pushMeta($pid, $meta_info->meta_key, maybe_unserialize($meta_info->meta_value), $this->isNewProduct);
                                }
                            }
                        }
                        // save thumbnail
                        if ($this->isNewProduct || $this->getImport()->options['is_update_images'] && $this->getImport()->options['update_images_logic'] == 'full_update') {
                            $post_thumbnail_id = get_post_thumbnail_id( $post_to_update_id );
                            if ($post_thumbnail_id) {
                                set_post_thumbnail($pid, $post_thumbnail_id);
                            }
                            if ($this->getImport()->options['put_variation_image_to_gallery'] && $post_thumbnail_id) {
                                do_action('pmxi_gallery_image', $pid, $post_thumbnail_id, FALSE);
                            }
                        }
                        if ($this->getImport()->options['create_draft'] == 'yes') {
                            $this->wpdb->update( $this->wpdb->posts, array('post_status' => 'publish' ), array('ID' => $pid));
                        }
                        update_post_meta($pid, '_variation_updated', 1);
                        //wc_delete_product_transients($pid);
                    }
                }
            }
            // Update product gallery.
            $tmp_gallery = explode(",", get_post_meta( $post_to_update_id, '_product_image_gallery_tmp', TRUE));
            $gallery = explode(",", get_post_meta( $post_to_update_id, '_product_image_gallery', TRUE));
            if (is_array($gallery)) {
                $gallery = array_filter($gallery);
                if (!empty($tmp_gallery)) {
                    $gallery = array_unique(array_merge($gallery, $tmp_gallery));
                }
            }
            elseif (!empty($tmp_gallery)) {
                $gallery = array_unique($tmp_gallery);
            }
            //  Do not add featured image to the gallery.
            if (!empty($gallery)){
                $post_thumbnail_id = get_post_thumbnail_id( $post_to_update_id );
                foreach ($gallery as $key => $value) {
                    if ($value == $post_thumbnail_id){
                        unset($gallery[$key]);
                    }
                }
            }

            $this->getImportService()->pushMeta( $post_to_update_id, '_product_image_gallery', implode(",", $gallery), $this->isNewProduct );
        }
    }
}
