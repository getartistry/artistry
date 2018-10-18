<?php

namespace wpai_woocommerce_add_on\libraries\importer;

use WC_Product_Attribute;
use wpai_woocommerce_add_on\libraries\helpers\ImporterOptions;
use wpai_woocommerce_add_on\libraries\parser\VariationsParser;
use XmlImportWooCommerceService;

require_once dirname(__FILE__) . '/ImportProduct.php';

/**
 * Import Variable Product.
 *
 * Class ImportVariableProduct
 * @package wpai_woocommerce_add_on\libraries\importer
 */
class ImportVariableProduct extends ImportProduct {

    /**
     * @var string
     */
    protected $productType = 'variable';

    /**
     * Patent variable product.
     *
     * @var \WC_Product_Variable
     */
    protected $parent;

    /**
     * Variations stack.
     *
     * @var array \WC_Product_Variation
     */
    protected $variations = array();

    /**
     * Additional import engine for variations.
     *
     * @var ImportVariationProduct
     */
    public $variationImporter;

    /**
     * Check is variation needs to be created from parent product row.
     *
     * @return bool
     */
    public function isFirstRowVariation() {
        return ("manual" == $this->getImport()->options['duplicate_matching'] && !$this->isNewProduct() || in_array($this->getImport()->options['matching_parent'], array('auto', 'first_is_parent_title'))) ? FALSE : TRUE;
    }

    /**
     * Check is currently imported product is not variation.
     *
     * @return bool
     */
    public function isImportingParentProduct() {
        return empty($this->parent);
    }

    /**
     * Import Variable Product.
     *
     * @return mixed
     */
    public function import() {
        // Import variable product.
        try {
            // Link all variations option.
            if ($this->getImport()->options['link_all_variations'] && $this->getImportService()->isUpdateDataAllowed('is_update_attributes', $this->isNewProduct())) {
                parent::import();
                $added_variations = $this->linkAllVariations();
                $this->getImportService()->syncVariableProductData($this->getProduct()->get_id());
                $this->log(sprintf(__('<b>CREATED</b>: %s variations for parent product %s.', \PMWI_Plugin::TEXT_DOMAIN), $added_variations, $this->product->get_title()));
            }
            else {
                // Importing variable product using options #1 #2 #3 #4.
                if ('xml' != $this->getImport()->options['matching_parent']) {
                    // Import basic product data.
                    $this->importParentProduct();
                    // Init parent product object.
                    $parentID = $this->isImportingParentProduct() ? $this->getPid() : $this->parent->get_id();
                    // Set is new product flag.
                    update_post_meta($parentID, XmlImportWooCommerceService::FLAG_IS_NEW_PRODUCT, $this->isNewProduct());
                    // Define Product Variation Importer.
                    $this->variationImporter = new ImportVariationProduct($this->index, $this->getOptions(), $this->getParsedData());
                    // Import variations.
                    $this->importVariations($parentID);
                    // Sync parent product with variations.
                    $productStack = get_option('wp_all_import_product_stack_' . $this->getImport()->id, array());
                    if (!in_array($parentID, $productStack)) {
                        $previousParentID = array_shift($productStack);
                        // Sync parent product prices & attributes with variations.
                        if ($previousParentID) {
                            $this->getImportService()->syncVariableProductData($previousParentID);
                        }
                        $productStack[] = $parentID;
                        update_option('wp_all_import_product_stack_' . $this->getImport()->id, $productStack);
                    }
                }

                // Importing variable product using option #5.
                if ('xml' == $this->getImport()->options['matching_parent']) {
                    // Validation.
                    if (empty($this->getImport()->options['variations_xpath'])) {
                        throw new \Exception(__('Variations XPath can\'t be empty.', \PMWI_Plugin::TEXT_DOMAIN));
                    }
                    if (empty($this->getImport()->options['variable_sku']) && $this->getImport()->options['disable_auto_sku_generation']) {
                        throw new \Exception(__('Variations SKU can\'t be empty when auto SKU generation disabled.', \PMWI_Plugin::TEXT_DOMAIN));
                    }
                    // Import parent product data.
                    parent::import();
                    // Init product variations parser.
                    $parser = new VariationsParser($this->getParser()->getOptions(), $this->getIndex());
                    // Parse product variations and get count of parsed variations.
                    $parser->parse();

                    $this->log(__('- Importing Variations', \PMWI_Plugin::TEXT_DOMAIN));
                    // Import variations.
                    for ($i = 0; $i < $parser->getCountVariations(); $i++) {
                        // Init index for variations import.
                        $index = new ImporterIndex($this->getPid(), $i, $this->getArticle());
                        // Init variations imported.
                        $this->variationImporter = new ImportVariationProductType5($index, new ImporterOptions($parser), $parser->getData(), $this->getProduct());
                        $this->variationImporter->import();
                    }
                    $this->getImportService()->syncVariableProductData($this->getProduct()->get_id());
                }
            }
        }
        catch(\Exception $e){
            $this->log('<b>ERROR:</b> ' . $e->getMessage());
        }
    }

    /**
     *  Define parent product SKU.
     */
    public function prepareSKU() {
        parent::prepareSKU();
        // Update parent SKU with valid value.
        if ($this->isImportingParentProduct()) {
            $this->setProperty('sku', $this->getParentSKU());
        }
    }

    /**
     * Get Parent Product SKU.
     *
     * @return mixed
     */
    protected function getParentSKU() {
        $parentSKU = get_post_meta($this->getPid(), '_parent_sku', TRUE);
        if (empty($parentSKU)) {
            $parentSKU = isset($this->productProperties['sku']) ? $this->productProperties['sku'] : '';
        }
        return $parentSKU;
    }

    /**
     * Define variable product parent.
     *
     * @return mixed
     * @throws \Exception
     */
    protected function initParentProduct() {
        $parent_id = FALSE;
        if ( "manual" != $this->getImport()->options['duplicate_matching'] || $this->isNewProduct() ) {
            // Find corresponding article among previously imported.
            $identity = $this->getValue('single_product_parent_ID');
            if (!empty($identity)){
                $postRecord = $this->wpdb->get_row($this->wpdb->prepare("SELECT * FROM " . $this->wpdb->prefix . "pmxi_posts WHERE `import_id` = %d AND `product_key` = %s ORDER BY post_id ASC", $this->getImport()->id, $identity));
                if (!empty($postRecord) && $postRecord->post_id != $this->getPid()) {
                    $parent_id = apply_filters( 'pmwi_product_parent_post_id', $postRecord->post_id );
                }
            }
        }
        else {
            // If current post doesn't have post_parent defined then use it as parent.
            $postParent = $this->getArticleData('post_parent');
            if (!empty($postParent)) {
                $parent_id = $postParent;
            }
        }
        if ($parent_id && $parent_id != $this->getPid()) {
            $this->variations[] = new \WC_Product_Variation($this->getPid());
        }
        // Init parent product object.
        $this->parent = $parent_id ? new \WC_Product_Variable($parent_id) : NULL;

    }

    /**
     * Init & import parent product data.
     *
     * @throws \Exception
     */
    protected function importParentProduct() {
        // Search for parent product reference.
        $this->initParentProduct();
        if ($this->isImportingParentProduct()) {
            // Set parent product properties.
            $this->setProperties();
            // Save data into database.
            $this->save();
            // Save originally parsed data, but without attributes.
            $properties = $this->getProperties();
            if (isset($properties['attributes'])) {
                unset($properties['attributes']);
            }
            update_post_meta($this->product->get_id(), XmlImportWooCommerceService::PARSED_DATA_KEY, $properties);
        }
    }

    /**
     * Import Product variations.
     *
     * @param $parentID
     *
     * @throws \Exception
     */
    protected function importVariations($parentID) {
        // Create & Update additional variation from parent product row.
        $firstVariationID = $this->createVariationFromParentRow();
        if ($firstVariationID) {
            // Trigger action for first variation.
            do_action( 'pmxi_saved_post', $firstVariationID, FALSE, $this->isNewProduct() );
        }
        // Import variations.
        /** @var \WC_Product_Variation $variation */
        foreach ($this->variations as $variation) {
            // Set variation parent product.
            $variation->set_parent_id($parentID);
            $this->variationImporter->setProduct($variation);
            $this->variationImporter->setProperties();
            // Import Stock data.
            $this->setVariationStockProperties($variation);
            $this->variationImporter->save();

            // Do not save variations as draft.
            if ($this->getImport()->options['create_draft'] == "yes") {
                $this->wpdb->update( $this->wpdb->posts, array('post_status' => 'publish' ), array('ID' => $variation->get_id()));
            }
            // Trigger `pmxi_saved_post` action for additional variation.
            if ($this->isImportingParentProduct() && $firstVariationID == $variation->get_id()) {
                do_action( 'pmxi_saved_post', $variation->get_id(), NULL, $this->isNewProduct());
                // Set _stock value for parent product to the _stock value for the first variation.
                if ($this->getImport()->options['set_parent_stock']) {
                    $this->getImportService()->pushMeta($parentID, '_stock', $variation->get_stock_quantity(), $this->isNewProduct());
                }
            }
            do_action( 'pmxi_update_product_variation', $variation->get_id() );
        }
    }

    /**
     * Create & Update additional variation from parent product row.
     *
     * @throws \Exception
     */
    protected function createVariationFromParentRow() {
        $variationID = FALSE;
        // Create additional variation from parent product row.
        if ($this->isFirstRowVariation() && $this->isImportingParentProduct()) {
            // Get Parent Product SKU.
            $parentSKU = $this->getParentSKU();
            $variation = FALSE;
            // Matching existing variation.
            $postRecord = new \PMXI_Post_Record();
            $postRecord->clear();
            $variationID = $this->getExistingVariation($parentSKU, $postRecord);

            // Enabled or disabled.
            $post_status = $this->getValue('product_enabled') == 'yes' ? 'publish' : 'private';

            // Generate a useful post title.
            $variation_post_title = sprintf( __( 'Variation #%s of %s', \PMWI_Plugin::TEXT_DOMAIN ), absint( $variationID ), $this->product->get_title());

            // Update or Add Variation.
            $variationData = array(
                'post_title' 	=> $variation_post_title,
                'post_content' 	=> '',
                'post_status'   => $post_status,
                'post_parent' 	=> $this->getPid(),
                'post_type' 	=> 'product_variation',
            );

            if (!$variationID) {
                if ($this->getImport()->options['create_new_records']) {
                    $variationID = wp_insert_post($variationData);
                    if (!is_wp_error($variationID)) {
                        // Create product variation object.
                        $variation = new \WC_Product_Variation($variationID);
                        // Associate variation with import.
                        if ("manual" != $this->getImport()->options['duplicate_matching']) {
                            $postRecord->isEmpty() && $postRecord->set(array(
                                'post_id' => $variation->get_id(),
                                'import_id' => $this->getImport()->id,
                                'unique_key' => 'Variation ' . $parentSKU,
                                'product_key' => '',
                                'iteration' => $this->getImport()->iteration,
                            ))->insert();
                        }
                    }
                    else {
                        $this->log(__('<b>ERROR</b>', \PMWI_Plugin::TEXT_DOMAIN) . ': ' . $variationID->get_error_message());
                    }
                }
            }
            else {
                // Create product variation object.
                $variation = new \WC_Product_Variation($variationID);
                if (!$postRecord->isEmpty()) {
                    $postRecord->set(array('iteration' => $this->getImport()->iteration))->update();
                }
                if (!$this->getImportService()->isUpdateDataAllowed('is_update_status', $this->isNewProduct())) {
                    $variationData['post_status'] = $variation->get_status();
                }
                // Update existing first variation.
                wp_update_post( array_merge($variationData, array('ID' => $variation->get_id())) );
            }
            // Add variation to variations stack.
            if ($variation) {
                $this->variations[] = $variation;
            }
        }
        return $variationID;
    }

    /**
     * Search for existing variation which was created from parent product row.
     *
     * @param $parentSKU
     * @param $postRecord \PMXI_Post_Record
     *
     * @return bool|int
     * @throws \Exception
     */
    protected function getExistingVariation($parentSKU, &$postRecord) {
        $variationID = FALSE;
        if ("manual" != $this->getImport()->options['duplicate_matching'] || $this->isNewProduct()) {
            // Find corresponding article among previously imported.
            $postRecord->getBy(array(
                'unique_key' => 'Variation ' . $parentSKU,
                'import_id'  => $this->getImport()->id,
            ));
            $variationID = ( ! $postRecord->isEmpty() ) ? $postRecord->post_id : FALSE;
        }
        else{
            // Trying to find additional variation by parent product SKU.
            $args = array(
                'post_type' => 'product_variation',
                'meta_query' => array(
                    array(
                        'key' => '_sku',
                        'value' => get_post_meta($this->getPid(), '_sku', TRUE),
                    ),
                ),
            );
            $query = new \WP_Query($args);
            if ($query->have_posts()) {
                $variationID = $query->post->ID;
            }
        }
        return $variationID;
    }

    /**
     * Set variation stock properties.
     *
     * @param $variation \WC_Product_Variation
     */
    protected function setVariationStockProperties($variation) {
        // Update stock data only for first variation.
        if ($this->isFirstRowVariation()) {
            $variation->set_props([
                'manage_stock' => $this->getValue('v_product_manage_stock') == 'yes',
                'stock_quantity' => $this->getValue('v_stock'),
                'stock_status' => $this->getValue('v_stock_status')
            ]);
            if ($this->getImportService()->isUpdateTaxonomy('product_shipping_class', $this->isNewProduct())) {
                $variation->set_shipping_class_id($this->getProduct()->get_shipping_class_id());
            }
        }
    }

    /**
     * Automatically create variations from product attributes.
     *
     * @return int
     * @throws \Exception
     */
    protected function linkAllVariations() {
        @set_time_limit(0);
        $variations = array();
        /** @var WC_Product_Attribute $attribute */
        foreach ( $this->product->get_attributes() as $attribute ) {
            if ( $attribute instanceof WC_Product_Attribute){
                $attribute = array(
                    'name' => $attribute->get_name(),
                    'is_taxonomy' => $attribute->is_taxonomy(),
                    'is_variation' => $attribute->get_variation(),
                    'value' => wc_implode_text_attributes( $attribute->get_options() ),
                );
            }
            if ( ! $attribute['is_variation'] ) {
                continue;
            }
            $attribute_field_name = 'attribute_' . sanitize_title( $attribute['name'] );
            if ( $attribute['is_taxonomy'] ) {
                $options = array();
                $options_arr = wc_get_product_terms( $this->product->get_id(), $attribute['name'], array( 'orderby' => 'parent' ) );
                if (!empty($options_arr)){
                    foreach ($options_arr as $key => $value) {
                        if (!in_array($value->slug, $options)) $options[] = $value->slug;
                    }
                }
            } else {
                $options = explode( '|', $attribute['value'] );
            }
            $options = array_map( 'trim', $options );
            $variations[ $attribute_field_name ] = $options;
        }

        // Quit out if none were found
        if ( sizeof( $variations ) == 0 ) return 0;

        // Get existing variations so we don't create duplicates
        $available_variations = array();
        foreach($this->product->get_children() as $child_id) {
            $child = wc_get_product($child_id);
            if (!empty( $child->variation_id)) {
                $postRecord = new \PMXI_Post_Record();
                $postRecord->getBy(array(
                    'post_id' => $child->variation_id,
                    'import_id' => $this->getImport()->id,
                    'unique_key' => 'Variation ' . $child->variation_id . ' of ' . $this->product->get_id(),
                ));
                if ( ! $postRecord->isEmpty() ){
                    $postRecord->set(array('iteration' => $this->getImport()->iteration))->update();
                }
                $variation_attributes = $child->get_variation_attributes();
                foreach ($variation_attributes as $key => $value) {
                    $variation_attributes[$key] = sanitize_title($value);
                }
                $available_variations[] = $variation_attributes;
                $this->syncVariationWithParent($child->variation_id);
                do_action( 'pmxi_product_variation_saved', $child->variation_id );
            }
        }

        // Created posts will all have the following data.
        $variation_post_data = array(
            'post_title' => 'Product #' . $this->product->get_id() . ' Variation',
            'post_content' => '',
            'post_status' => 'publish',
            'post_author' => get_current_user_id(),
            'post_parent' => $this->product->get_id(),
            'post_type' => 'product_variation',
        );

        $variation_ids = array();
        $added = 0;
        $possible_variations = XmlImportWooCommerceService::arrayCartesian($variations);

        foreach ($possible_variations as $variation) {
            // Check if variation already exists
            if ( in_array( $variation, $available_variations ) )
                continue;

            $variation_id = wp_insert_post( $variation_post_data );

            $postRecord = new \PMXI_Post_Record();
            $postRecord->isEmpty() and $postRecord->set(array(
                'post_id' => $variation_id,
                'import_id' => $this->getImport()->id,
                'unique_key' => 'Variation ' . $variation_id . ' of ' . $this->product->get_id(),
                'product_key' => '',
                'iteration' => $this->getImport()->iteration,
            ))->insert();

            $this->syncVariationWithParent($variation_id);
            $variation_ids[] = $variation_id;
            foreach ( $variation as $key => $value ) {
                update_post_meta( $variation_id, $key, $value );
            }
            $added++;
            do_action( 'pmxi_product_variation_saved', $variation_id );
        }

        return $added;
    }

    /**
     * Sync parent product data with variation.
     *
     * @param $variationID
     */
    protected function syncVariationWithParent($variationID) {
        $fields = array('_regular_price', '_sale_price', '_sale_price_dates_from', '_sale_price_dates_to', '_price', '_stock', '_backorders');
        foreach ($fields as $field) {
            $value = get_post_meta( $this->product->get_id(), $field, TRUE);
            update_post_meta( $variationID, $field, $value);
        }
        if ( class_exists('woocommerce_wholesale_pricing') ) {
            update_post_meta( $variationID, 'pmxi_wholesale_price', get_post_meta( $this->product->get_id(), 'pmxi_wholesale_price', true ) );
        }
        $stockStatus = get_post_meta( $this->product->get_id(), '_stock_status', true );
        $manageStock = get_post_meta($this->product->get_id(), '_manage_stock', true);
        if ($manageStock == 'no') {
            $stockStatus = 'instock';
        }
        update_post_meta( $variationID, '_stock_status', $stockStatus);
        update_post_meta( $variationID, '_manage_stock', $manageStock);
    }
}