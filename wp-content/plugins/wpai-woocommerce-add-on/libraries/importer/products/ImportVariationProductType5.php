<?php

namespace wpai_woocommerce_add_on\libraries\importer;

use WC_Product_Attribute;
use wpai_woocommerce_add_on\libraries\helpers\ImporterOptions;

require_once dirname(__FILE__) . '/ImportProduct.php';

/**
 * Import Variation Product.
 *
 * Class ImportVariationProductType5
 * @package wpai_woocommerce_add_on\libraries\importer
 */
class ImportVariationProductType5 extends ImportProduct {

    /**
     * @var \WC_Product_Variation
     */
    public $product;

    /**
     * @var \WC_Product
     */
    public $parentProduct;

    /**
     * @var string
     */
    protected $productType = 'product_variation';

    /**
     * @var bool
     */
    public $isNewVariation;

    /**
     * ImportVariationProductType5 constructor.
     *
     * @param ImporterIndex $index
     * @param ImporterOptions $options
     * @param array $data
     * @param $parentProduct \WC_Product
     */
    public function __construct(ImporterIndex $index, ImporterOptions $options, array $data, $parentProduct) {
        parent::__construct($index, $options, $data);
        $this->parentProduct = $parentProduct;
    }

    /**
     * @return \WC_Product
     */
    public function getParentProduct() {
        return $this->parentProduct;
    }

    /**
     * @return boolean
     */
    public function isNewVariation() {
        return $this->isNewVariation;
    }

    /**
     * @return bool
     */
    public function import() {
        try {
            $this->initProductVariation();
            if ($this->getProduct()) {
                $this->importCustomFields();
                $this->importImages();
                // Re-init variation object after custom fields changes.
                $this->setProduct(new \WC_Product_Variation($this->getProduct()->get_id()));
                parent::import();
                do_action('pmxi_update_product_variation', $this->getProduct()->get_id());
            }
        }
        catch (\Exception $e) {
            $this->log(__('<b>ERROR:</b> ' . $e->getMessage(), \PMWI_Plugin::TEXT_DOMAIN));
        }
    }

    /**
     *
     * Define all product properties.
     *
     * @return mixed
     */
    public function prepareProperties(){
        // Set variation parent product.
        $this->getProduct()->set_parent_id($this->getParentProduct()->get_id());
        $this->getProduct()->set_description($this->getValue('product_description'));

        $this->prepareGeneralProperties();
        $this->prepareInventoryProperties();
        $this->prepareShippingProperties();
        $this->prepareAttributesProperties();
    }

    /**
     *  Define general properties.
     */
    public function prepareGeneralProperties() {
        // Prices.
        $this->setProperty('regular_price', wc_clean( $this->getValue('product_regular_price') ));
        $this->setProperty('sale_price', wc_clean( $this->getValue('product_sale_price') ));
        $this->setProperty('date_on_sale_from', wc_clean( $this->getValue('product_sale_price_dates_from') ));
        $this->setProperty('date_on_sale_to', wc_clean( $this->getValue('product_sale_price_dates_to') ));
        // Product properties.
        $this->setProperty('downloadable', $this->isDownloadable());
        $this->setProperty('virtual', $this->isVirtual());
//        $this->setProperty('featured', $this->isFeatured());
//        $this->setProperty('catalog_visibility', wc_clean( $this->getValue('product_visibility') ));
        $this->prepareDownloadableProperties();
        $this->prepareTaxProperties();
    }

    /**
     * @return float|int
     */
    public function getStockQuantity() {
        return wc_stock_amount($this->getValue('product_stock'));
    }

    /**
     *  Generate variation SKU.
     */
    protected function generateSKU() {
        // Unique SKU.
        $newSKU = esc_html(trim(stripslashes( $this->getValue('product_sku'))));
        if ($newSKU == '' && !$this->getImport()->options['disable_auto_sku_generation']) {
            if ($this->isNewVariation() || $this->getImportService()->isUpdateCustomField('_sku')) {
                $variationTitle = sprintf( __( 'Variation #%s of %s', \PMWI_Plugin::TEXT_DOMAIN ), absint( $this->getProduct()->get_id() ), $this->getProduct()->get_title());
                $newSKU = substr(md5($variationTitle), 0, 12);
            }
        }
        return $newSKU;
    }

    /**
     * Init Product Variation.
     *
     * @return bool
     * @throws \Exception
     */
    public function initProductVariation() {

        $sku = $this->getValue('product_sku');

        $variationSkuForTitle = ('' == $sku) ? $this->getIndex() : $sku;
        if ($this->getImport()->options['variable_sku_add_parent']) {
            $sku = $this->getParentProduct()->get_sku() . '-' . $sku;
            $variationSkuForTitle = $this->getParentProduct()
                    ->get_sku() . '-' . $sku;
        }
        $isVariationHaveAttributes = FALSE;
        foreach ($this->getParsedDataOption('serialized_attributes') as $attr => $attrOptions) {
            if ($attrOptions['in_variation'][$this->getIndex()] && $attrOptions['value'][$this->getIndex()] != '') {
                $isVariationHaveAttributes = TRUE;
                break;
            }
        }
        // Do not create variation if it doesn't have attributes.
        if (!$isVariationHaveAttributes && $this->getImport()->options['make_simple_product']) {
            return FALSE;
        }

        // Enabled or disabled.
        $postStatus = $this->getValue('product_enabled') == 'yes' ? 'publish' : 'private';
        $variationToUpdateID = FALSE;
        $postRecord = new \PMXI_Post_Record();
        $postRecord->clear();

        // Generate a useful post title.
        $variationPostTitle = sprintf(__('Variation #%s of %s', \PMWI_Plugin::TEXT_DOMAIN), $variationSkuForTitle, $this->getParentProduct()->get_title());

        $variation = array(
            'post_title' => $variationPostTitle,
            'post_content' => '',
            'post_status' => $postStatus,
            'post_parent' => $this->getParentProduct()->get_id(),
            'post_type' => 'product_variation'
        );
        $this->isNewVariation = FALSE;
        $postRecord->getBy(array(
            'unique_key' => 'Variation ' . $variationSkuForTitle . ' of ' . $this->getParentProduct()->get_id(),
            'import_id' => $this->getImport()->id
        ));
        if (!$postRecord->isEmpty()) {
            $variationToUpdateID = $postRecord->post_id;
            $postRecord->set(array('iteration' => $this->getImport()->iteration))
                ->update();
        }

        if (empty($variationToUpdateID)) {
            // Create new variation.
            $variationToUpdateID = wp_insert_post($variation);
            // Associate variation with import.
            $postRecord->isEmpty() and $postRecord->set(array(
                'post_id' => $variationToUpdateID,
                'import_id' => $this->getImport()->id,
                'unique_key' => 'Variation ' . $variationSkuForTitle . ' of ' . $this->getParentProduct()->get_id(),
                'product_key' => ''
            ))->insert();
            $postRecord->set(array('iteration' => $this->getImport()->iteration))
                ->update();
            $this->isNewVariation = TRUE;
            $this->getLogger() && call_user_func($this->getLogger(), sprintf(__('- `%s`: variation created successfully', \PMWI_Plugin::TEXT_DOMAIN), sprintf(__('Variation #%s of %s', \PMWI_Plugin::TEXT_DOMAIN), absint($variationToUpdateID), esc_html($this->getParentProduct()->get_title()))));
        }
        else {
            // Update existing variation.
            $this->wpdb->update($this->wpdb->posts, $variation, array('ID' => $variationToUpdateID));
            $this->getLogger() && call_user_func($this->getLogger(), sprintf(__('- `%s`: variation updated successfully', \PMWI_Plugin::TEXT_DOMAIN), $variationPostTitle));
            // Handle obsolete files (i.e. delete or keep) according to import settings.
            if ($this->getImport()->options['update_all_data'] == 'yes' || (
                    $this->getImport()->options['update_all_data'] == 'no'
                    && $this->getImport()->options['is_update_attachments'])
            ) {
                $this->getLogger() && call_user_func($this->getLogger(), sprintf(__('Deleting attachments for `%s`', \PMWI_Plugin::TEXT_DOMAIN), $variationPostTitle));
                wp_delete_attachments($variationToUpdateID, TRUE, 'files');
            }
            // Handle obsolete images (i.e. delete or keep) according to import settings.
            if ($this->getImport()->options['update_all_data'] == 'yes' || (
                    $this->getImport()->options['update_all_data'] == 'no'
                    && $this->getImport()->options['is_update_images']
                    && $this->getImport()->options['update_images_logic'] == "full_update")
            ) {
                $this->getLogger() && call_user_func($this->getLogger(), sprintf(__('Deleting images for `%s`', \PMWI_Plugin::TEXT_DOMAIN), $variationPostTitle));
                wp_delete_attachments($variationToUpdateID, !$this->getImport()->options['do_not_remove_images'], 'images');
            }
        }
        // Init variation object.
        $this->setProduct(new \WC_Product_Variation($variationToUpdateID));
    }

    /**
     *  Import custom fields.
     */
    public function importCustomFields() {
        $existing_variation_meta_keys = array();
        $parentMeta = get_post_meta($this->getProduct()->get_id(), '');
        foreach ($parentMeta as $cur_meta_key => $cur_meta_val) {
            $existing_variation_meta_keys[] = $cur_meta_key;
        }
        // Delete keys which are no longer correspond to import settings.
        if (!empty($existing_variation_meta_keys)) {
            foreach ($existing_variation_meta_keys as $cur_meta_key) {
                // Do not delete post meta for features image.
                if (in_array($cur_meta_key, array(
                    '_thumbnail_id',
                    '_product_image_gallery'
                ))) {
                    continue;
                }
                // Update all data.
                if ($this->isNewVariation() || $this->getImport()->options['update_all_data'] == 'yes') {
                    delete_post_meta($this->getProduct()->get_id(), $cur_meta_key);
                    continue;
                }
                // Do not update attributes.
                if (($this->isNewVariation() || !$this->getImport()->options['is_update_attributes']) && (in_array($cur_meta_key, array(
                            '_default_attributes',
                            '_product_attributes'
                        )) || strpos($cur_meta_key, "attribute_") === 0)
                ) {
                    continue;
                }
                // Update only these Attributes, leave the rest alone.
                if (($this->isNewVariation() || $this->getImport()->options['is_update_attributes']) && $this->getImport()->options['update_attributes_logic'] == 'only') {
                    if ($cur_meta_key == '_product_attributes') {
                        $current_product_attributes = get_post_meta($this->getProduct()->get_id(), '_product_attributes', TRUE);
                        if (!empty($current_product_attributes)
                            && !empty($this->getImport()->options['attributes_list'])
                            && is_array($this->getImport()->options['attributes_list'])
                        ) {
                            foreach ($current_product_attributes as $attr_name => $attr_value) {
                                if (in_array($attr_name, array_filter($this->getImport()->options['attributes_list'], 'trim'))) {
                                    unset($current_product_attributes[$attr_name]);
                                }
                            }
                        }
                        update_post_meta($this->getProduct()->get_id(), '_product_attributes', $current_product_attributes);
                        continue;
                    }
                    if (strpos($cur_meta_key, "attribute_") === 0
                        && !empty($this->getImport()->options['attributes_list'])
                        && is_array($this->getImport()->options['attributes_list'])
                        && !in_array(str_replace("attribute_", "", $cur_meta_key), array_filter($this->getImport()->options['attributes_list'], 'trim'))
                    ) {
                        continue;
                    }
                    if (in_array($cur_meta_key, array('_default_attributes'))) {
                        continue;
                    }
                }
                // Leave these attributes alone, update all other Attributes.
                if (($this->isNewVariation() || $this->getImport()->options['is_update_attributes']) && $this->getImport()->options['update_attributes_logic'] == 'all_except') {
                    if ($cur_meta_key == '_product_attributes') {
                        if (empty($this->getImport()->options['attributes_list'])) {
                            delete_post_meta($this->getProduct()->get_id(), $cur_meta_key);
                            continue;
                        }
                        $current_product_attributes = get_post_meta($this->getProduct()->get_id(), '_product_attributes', TRUE);
                        if (!empty($current_product_attributes)
                            && !empty($this->getImport()->options['attributes_list'])
                            && is_array($this->getImport()->options['attributes_list'])
                        ) {
                            foreach ($current_product_attributes as $attr_name => $attr_value) {
                                if (!in_array($attr_name, array_filter($this->getImport()->options['attributes_list'], 'trim'))) {
                                    unset($current_product_attributes[$attr_name]);
                                }
                            }
                        }
                        update_post_meta($this->getProduct()->get_id(), '_product_attributes', $current_product_attributes);
                        continue;
                    }
                    if (strpos($cur_meta_key, "attribute_") === 0
                        && !empty($this->getImport()->options['attributes_list'])
                        && is_array($this->getImport()->options['attributes_list'])
                        && in_array(str_replace("attribute_", "", $cur_meta_key), array_filter($this->getImport()->options['attributes_list'], 'trim'))
                    ) {
                        continue;
                    }
                    if (in_array($cur_meta_key, array('_default_attributes'))) {
                        continue;
                    }
                }
                // Update all Custom Fields is defined.
                if ($this->getImport()->options['update_custom_fields_logic'] == "full_update") {
                    delete_post_meta($this->getProduct()->get_id(), $cur_meta_key);
                }
                // Update only these Custom Fields, leave the rest alone.
                elseif ($this->getImport()->options['update_custom_fields_logic'] == "only") {
                    if (!empty($this->getImport()->options['custom_fields_list'])
                        && is_array($this->getImport()->options['custom_fields_list'])
                        && in_array($cur_meta_key, $this->getImport()->options['custom_fields_list'])
                    ) {
                        delete_post_meta($this->getProduct()->get_id(), $cur_meta_key);
                    }
                }
                // Leave these fields alone, update all other Custom Fields.
                elseif ($this->getImport()->options['update_custom_fields_logic'] == "all_except") {
                    if (empty($this->getImport()->options['custom_fields_list']) || !in_array($cur_meta_key, $this->getImport()->options['custom_fields_list'])) {
                        delete_post_meta($this->getProduct()->get_id(), $cur_meta_key);
                    }
                }
            }
        }
        // Add any default post meta.
        $totalSales = get_post_meta($this->getProduct()->get_id(), 'total_sales', TRUE);
        if (empty($totalSales)) {
            update_post_meta($this->getProduct()->get_id(), 'total_sales', '0');
        }
    }

    /**
     *  Import variation images.
     */
    public function importImages() {
        $images = $this->getValue('product_image');
        $uploads = wp_upload_dir();
        if (!empty($uploads) && false === $uploads['error'] && !empty($images) && $this->getImportService()->isUpdateDataAllowed('is_update_images', $this->isNewVariation())) {
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            $gallery = array();
            $images = array_filter(explode(',', $images));
            if (!empty($images)) {
                foreach ($images as $imgURL) {
                    $attachmentID = \PMXI_API::upload_image($this->getProduct()->get_id(), $imgURL, $this->getImport()->options['download_images'], $this->getLogger(), TRUE);
                    if ($attachmentID) {
                        $variationThumbnailID = get_post_thumbnail_id( $this->getProduct()->get_id() );
                        if (empty($post_thumbnail_id) && $this->getImport()->options['is_featured']) {
                            set_post_thumbnail($this->getProduct()->get_id(), $attachmentID);
                        } elseif(!in_array($attachmentID, $gallery) && $variationThumbnailID != $attachmentID) {
                            $gallery[] = (int) $attachmentID;
                        }
                        do_action( 'pmxi_gallery_image', $this->getProduct()->get_id(), $attachmentID, get_attached_file( $attachmentID ));
                    }
                }
            }
            if (!empty($this->getImport()->options['import_additional_variation_images'])){
                update_post_meta($this->getProduct()->get_id(), '_wc_additional_variation_images', implode(",", $gallery) );
            }
        }
    }

    /**
     *  Define attributes properties.
     */
    public function prepareAttributesProperties() {
        if (!$this->getImportService()->isUpdateDataAllowed('is_update_attributes', $this->isNewProduct())) {
            return TRUE;
        }
        $currentAttributes = get_post_meta($this->getProduct()->get_parent_id(), '_product_attributes', TRUE);
        if (empty($currentAttributes)) {
            $currentAttributes = array();
        }
        $parentAttributes = array();
        $parsedAttributes = array();
        $attributes = $this->getAttributesProperties();
        /** @var WC_Product_Attribute $attribute */
        foreach ($attributes as $i => $attribute) {
            // Prepare attribute name.
            $parentAttributeName = $attribute->is_taxonomy() ? sanitize_title($attribute->get_taxonomy()) : sanitize_title($attribute->get_name());
            // Add attribute to array, but don't set values.
            $parentAttributes[$parentAttributeName] = array(
                'name' 			=> $attribute->get_name(),
                'value' 		=> '',
                'position' 		=> $i,
                'is_visible' 	=> $attribute->get_visible(),
                'is_variation' 	=> $attribute->get_variation(),
                'is_taxonomy' 	=> $attribute->is_taxonomy()
            );
            // Check is current attribute used in variations.
            if ($attribute->get_variation()) {
                // Check is current attribute saved as taxonomy term.
                if ($attribute->is_taxonomy()) {
                    $term = get_term($attribute->get_terms()[0], $attribute->get_taxonomy());
                    if ($term && !is_wp_error($term)) {
                        $parsedAttributes[sanitize_title($attribute->get_name())] = $term->slug;
                    }
                }
                else{
                    $parsedAttributes[sanitize_title($attribute->get_name())] = $attribute->get_data()['value'];
                }
            }
            else {
                $value = FALSE;
                // Check is current attribute saved as taxonomy term.
                if ($attribute->is_taxonomy()) {
                    $term = get_term($attribute->get_terms()[0], $attribute->get_taxonomy());
                    if ($term && !is_wp_error($term)) {
                        $value = $term->term_taxonomy_id;
                    }
                }
                else {
                    $value = $attribute->get_data()['value'];
                }
                // Collect variation attribute values in _product_attributes.
                if ($value) {
                    if (isset($currentAttributes[sanitize_title($attribute->get_name())])) {
                        $values = explode("|", $currentAttributes[sanitize_title($attribute->get_name())]['value']);
                        if (!in_array($value, $values)) {
                            $values[] = $value;
                        }
                        $parentAttributes[sanitize_title($attribute->get_name())]['value'] = implode("|", $values);
                    }
                    else {
                        $parentAttributes[sanitize_title($attribute->get_name())]['value'] = $value;
                    }
                }
            }
        }
        $variationAttributes = array();
        $attributes = $this->getProduct()->get_attributes();
        foreach ($attributes as $attribute_name => $attribute_value) {
            if (!$this->getImportService()->isUpdateAttribute($attribute_name, $this->isNewProduct())) {
                $variationAttributes[$attribute_name] = $attribute_value;
            }
        }
        $variationAttributes = array_merge($parsedAttributes, $variationAttributes);
        $this->setProperty('attributes', $variationAttributes);
        // Merge parent attributes with attributes from variation.
        update_post_meta($this->getProduct()->get_parent_id(), '_product_attributes', array_merge($currentAttributes, $parentAttributes));
    }
}