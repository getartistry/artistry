<?php

/**
 * Class XmlImportWooCommerceService
 */
final class XmlImportWooCommerceService {

    /**
     * Singletone instance
     * @var XmlImportWooCommerceService
     */
    protected static $instance;

    /**
     *  Product custom field name to keep information about is product
     *  was created or updated by import.
     *
     */
    const FLAG_IS_NEW_PRODUCT = '__is_newly_created_product';

    /**
     *  Store all originally parsed data in product meta.
     */
    const PARSED_DATA_KEY = '__originally_parsed_data';

    /**
     * @var XmlImportWooTaxonomyService
     */
    public $taxonomiesService;

    /**
     * @var XmlImportWooPriceService
     */
    public $priceService;

    /**
     * @var \PMXI_Image_Record
     */
    public $import;

    /**
     * @var array
     */
    public $product_taxonomies;

    /**
     * Return singletone instance
     * @return XmlImportWooCommerceService
     */
    static public function getInstance() {
        if (self::$instance == NULL) {
            self::$instance = new self();
        }
        self::$instance->setImport();
        return self::$instance;
    }

    /**
     * XmlImportWooCommerceService constructor.
     */
    protected function __construct() {
        try {
            // Init current import instance.
            $this->setImport();
            $this->taxonomiesService = new XmlImportWooTaxonomyService($this->import);
            $this->priceService = new XmlImportWooPriceService($this->import);
            $productTaxonomies = array('post_format', 'product_type', 'product_shipping_class');
            $this->product_taxonomies = array_diff_key(get_taxonomies_by_object_type(array('product'), 'object'), array_flip($productTaxonomies));
        }
        catch(\Exception $e) {
            self::getLogger() && call_user_func(self::getLogger(), '<b>ERROR:</b> ' . $e->getMessage());
        }
    }

    /**
     * Init import object form request data.
     */
    public function setImport() {
        // Init current import instance.
        $this->import = new PMXI_Import_Record();
        $input = new PMXI_Input();
        $importID = $input->get('id');
        if (empty($importID)) {
            $importID = $input->get('import_id');
        }
        if (empty($importID)) {
            $importID = \PMXI_Plugin::$session->import_id;
        }
        if ($importID && ($this->import->isEmpty() || $this->import->id != $importID)) {
            $this->import->getById($importID);
        }
    }

    /**
     * @return \XmlImportWooTaxonomyService
     */
    public function getTaxonomiesService() {
        return $this->taxonomiesService;
    }

    /**
     * @return \XmlImportWooPriceService
     */
    public function getPriceService() {
        return $this->priceService;
    }

    /**
     * @return \PMXI_Image_Record
     */
    public function getImport() {
        return $this->import;
    }

    /**
     * @return array
     */
    public function getProductTaxonomies() {
        return $this->product_taxonomies;
    }

    /**
     * @param $productID
     *
     * @return mixed
     */
    public function getAllOriginallyParsedData($productID) {
        $data = get_post_meta($productID, self::PARSED_DATA_KEY, true);
        return $data;
    }

    /**
     * @param $productID
     * @param $key
     *
     * @return mixed
     */
    public function getOriginallyParsedData($productID, $key) {
        $data = $this->getAllOriginallyParsedData($productID);
        return isset($data[$key]) ? $data[$key] : NULL;
    }

    /**
     * Sync parent product prices & attributes with variations.
     *
     * @param $parentID
     */
    public function syncVariableProductData($parentID) {
        $product = new \WC_Product_Variable($parentID);
        $variations = array();
        $variationIDs = $product->get_children();
        // Sync parent attributes with variations.
        $attributes = array();
        foreach ($variationIDs as $key => $variationID) {
            $variation = new \WC_Product_Variation($variationID);
            $variations[] = $variation;
            $attributes = array_merge_recursive($attributes, $variation->get_attributes());
        }
        $parentAttributes = get_post_meta($product->get_id(), '_product_attributes', TRUE);
        foreach ($attributes as $tx_name => $terms) {
            if (isset($parentAttributes[$tx_name])) {
                if (!is_array($terms)) {
                    $terms = array($terms);
                }
                // Associate parent product with attribute terms.
                if ($parentAttributes[$tx_name]['is_taxonomy']) {
                    $term_ids = array();
                    foreach ($terms as $slug) {
                        $term = get_term_by('slug', $slug, $tx_name);
                        if (empty($term) || is_wp_error($term)) {
                            $term = get_term_by('slug', urldecode($slug), urldecode($tx_name));
                        }
                        if ($term && !is_wp_error($term)) {
                            $term_ids[] = $term->term_taxonomy_id;
                        }
                    }
                    $this->getTaxonomiesService()->associateTerms($parentID, $term_ids, $tx_name);
                }
                else {
                    // Add variation values to product attributes.
                    $parentAttributes[$tx_name]['value'] = implode("|", $terms);
                }
            }
        }
        // Sync attribute terms with parent product in case they are marked to
        // import as not in variation.
        if (!empty($parentAttributes)) {
            foreach ($parentAttributes as $name => $parentAttribute) {
                // In case attribute is not in variation.
                if (empty($parentAttribute['is_variation']) && $parentAttribute['is_taxonomy']) {
                    $terms = explode("|", $parentAttribute['value']);
                    $terms = array_filter($terms);
                    if (!empty($terms)) {
                        $this->getTaxonomiesService()->associateTerms($parentID, $terms, $name);
                    }
                    $parentAttributes[$name]['value'] = '';
                }
            }
        }
        // Sync parent product with variation if at least one variation exist.
        if (!empty($variations)) {
            /** @var WC_Product_Variable_Data_Store_CPT $data_store */
            $data_store = WC_Data_Store::load( 'product-' . $product->get_type() );
            $data_store->sync_price( $product );
            $data_store->sync_stock_status( $product );
            // Set product default attributes.
            if ($this->isUpdateDataAllowed('is_update_attributes') && $this->getImport()->options['is_default_attributes']) {
                $defaultVariation = FALSE;
                // Set first variation as the default selection.
                if ($this->getImport()->options['default_attributes_type'] == 'first') {
                    $defaultVariation = array_shift($variations);
                }
                // Set first in stock variation as the default selection.
                if ($this->getImport()->options['default_attributes_type'] == 'instock') {
                    /** @var \WC_Product_Variation $variation */
                    foreach ($variations as $variation) {
                        if ($variation->is_in_stock()) {
                            $defaultVariation = $variation;
                            break;
                        }
                    }
                }
                $defaultAttributes = $defaultVariation ? $defaultVariation->get_attributes() : array();
                $product->set_default_attributes($defaultAttributes);
            }
            $product->save();
        }

        update_post_meta($product->get_id(), '_product_attributes', $parentAttributes);
        // Make product simple it has less than two variations.
        if (count($variationIDs) < 2) {
            $this->maybeMakeProductSimple($product, $variationIDs);
        }
        do_action('wp_all_import_variable_product_imported', $product->get_id());
        // Delete originally parsed data, which was temporary stored in
        // product meta.
        delete_post_meta($product->get_id(), self::PARSED_DATA_KEY);
    }

    /**
     * Convert variable product into simple.
     *
     * @param $product \WC_Product_Variable
     * @param $variationIDs
     */
    public function maybeMakeProductSimple($product, $variationIDs) {
        $isNewProduct = get_post_meta($product->get_id(), self::FLAG_IS_NEW_PRODUCT, true);
        if ($this->isUpdateDataAllowed('is_update_product_type', $isNewProduct) && $this->getImport()->options['make_simple_product']) {
            $product_type_term = is_exists_term('simple', 'product_type', 0);
            if (!empty($product_type_term) && !is_wp_error($product_type_term)) {
                $this->getTaxonomiesService()->associateTerms($product->get_id(), array( (int) $product_type_term['term_taxonomy_id'] ), 'product_type');
            }
            // Sync prices after conversion to simple product.
            $parsedData = $this->getAllOriginallyParsedData($product->get_id());
            if (empty($variationIDs)) {
                // Sync product data in case variations weren't created for this product.
                $simpleProduct = new WC_Product_Simple($product->get_id());
                $simpleProduct->set_stock_status($parsedData['stock_status']);
                $simpleProduct->set_sale_price($parsedData['sale_price']);
                $simpleProduct->set_regular_price($parsedData['regular_price']);
                $simpleProduct->save();
            }
            else {
                XmlImportWooCommerceService::getInstance()->pushMeta($product->get_id(), '_regular_price', $parsedData['regular_price'], $isNewProduct);
                XmlImportWooCommerceService::getInstance()->pushMeta($product->get_id(), '_sale_price', $parsedData['sale_price'], $isNewProduct);
                $price = empty($parsedData['sale_price']) ? $parsedData['regular_price'] : $parsedData['sale_price'];
                XmlImportWooCommerceService::getInstance()->pushMeta($product->get_id(), '_price', $price, $isNewProduct);
            }
            do_action('wp_all_import_make_product_simple', $product->get_id(), $this->getImport()->id);
        }
    }

    /**
     * @param string $option
     * @param bool $isNewProduct
     * @return bool
     */
    public function isUpdateDataAllowed($option = '', $isNewProduct = TRUE) {
        // Allow update data for newly created products.
        if ($isNewProduct) {
            return TRUE;
        }
        // `Update existing posts with changed data in your file` option disabled.
        if ($this->getImport()->options['is_keep_former_posts'] == 'yes') {
            return FALSE;
        }
        // `Update all data` option enabled
        if ($this->getImport()->options['update_all_data'] == 'yes') {
            return TRUE;
        }
        return empty($this->getImport()->options[$option]) ? FALSE : TRUE;
    }

    /**
     * @param $tx_name
     * @param bool $isNewProduct
     * @return bool
     */
    public function isUpdateTaxonomy($tx_name, $isNewProduct = TRUE) {

        if (!$isNewProduct) {
            if ($this->getImport()->options['update_all_data'] == 'yes'){
                return TRUE;
            }
            if ( ! $this->getImport()->options['is_update_categories'] ) {
                return FALSE;
            }
            if ($this->getImport()->options['update_all_data'] == "no" && $this->getImport()->options['update_categories_logic'] == "all_except" && !empty($this->getImport()->options['taxonomies_list'])
                && is_array($this->getImport()->options['taxonomies_list']) && in_array($tx_name, $this->getImport()->options['taxonomies_list'])) {
                return FALSE;
            }
            if ($this->getImport()->options['update_all_data'] == "no" && $this->getImport()->options['update_categories_logic'] == "only" && ((!empty($this->getImport()->options['taxonomies_list'])
                        && is_array($this->getImport()->options['taxonomies_list']) && ! in_array($tx_name, $this->getImport()->options['taxonomies_list'])) || empty($this->getImport()->options['taxonomies_list']))) {
                return FALSE;
            }
        }
        return TRUE;
    }

    /**
     * @param $attributeName
     * @param bool $isNewProduct
     *
     * @return bool
     */
    public function isUpdateAttribute($attributeName, $isNewProduct = TRUE) {
        $is_update_attributes = TRUE;
        // Update only these Attributes, leave the rest alone.
        if ( ! $isNewProduct && $this->getImport()->options['update_all_data'] == "no" && $this->getImport()->options['is_update_attributes'] && $this->getImport()->options['update_attributes_logic'] == 'only') {
            if ( ! empty($this->getImport()->options['attributes_list']) && is_array($this->getImport()->options['attributes_list'])) {
                if ( ! in_array( $attributeName , array_filter($this->getImport()->options['attributes_list'], 'trim'))) {
                    $is_update_attributes = false;
                }
            }
        }
        // Leave these attributes alone, update all other Attributes.
        if ( ! $isNewProduct && $this->getImport()->options['update_all_data'] == "no" && $this->getImport()->options['is_update_attributes'] && $this->getImport()->options['update_attributes_logic'] == 'all_except') {
            if ( ! empty($this->getImport()->options['attributes_list']) && is_array($this->getImport()->options['attributes_list'])) {
                if ( in_array( $attributeName , array_filter($this->getImport()->options['attributes_list'], 'trim'))) {
                    $is_update_attributes = false;
                }
            }
        }
        return $is_update_attributes;
    }

    /**
     * @param $meta_key
     * @return bool
     */
    public function isUpdateCustomField($meta_key) {        

        $options = $this->getImport()->options;         

        if ($options['update_all_data'] == 'yes') {
            return TRUE;
        }

        if (!$options['is_update_custom_fields']) {
            return FALSE;
        }

        if ($options['update_custom_fields_logic'] == "full_update") {
            return TRUE;
        }
        if ($options['update_custom_fields_logic'] == "only"
            && !empty($options['custom_fields_list'])
            && is_array($options['custom_fields_list'])
            && in_array($meta_key, $options['custom_fields_list'])
        ) {
            return TRUE;
        }
        if ($options['update_custom_fields_logic'] == "all_except"
            && (empty($options['custom_fields_list']) || !in_array($meta_key, $options['custom_fields_list']))
        ) {
            return TRUE;
        }

        return FALSE;
    }

    /**
     * @param $pid
     * @param $meta_key
     * @param $meta_value
     * @param bool $isNewPost
     * @return mixed
     */
    public function pushMeta($pid, $meta_key, $meta_value, $isNewPost = TRUE) {
        if (!empty($meta_key) && ($isNewPost || $this->isUpdateCustomField($meta_key))) {
            update_post_meta($pid, $meta_key, $meta_value);
        }
    }

    /**
     * @param $input
     * @return array
     */
    public static function arrayCartesian($input) {

        $result = array();

        while ( list( $key, $values ) = each( $input ) ) {
            // If a sub-array is empty, it doesn't affect the cartesian product
            if ( empty( $values ) ) {
                continue;
            }

            // Special case: seeding the product array with the values from the first sub-array
            if ( empty( $result ) ) {
                foreach ( $values as $value ) {
                    $result[] = array( $key => $value );
                }
            }
            else {
                // Second and subsequent input sub-arrays work like this:
                //   1. In each existing array inside $product, add an item with
                //      key == $key and value == first item in input sub-array
                //   2. Then, for each remaining item in current input sub-array,
                //      add a copy of each existing array inside $product with
                //      key == $key and value == first item in current input sub-array

                // Store all items to be added to $product here; adding them on the spot
                // inside the foreach will result in an infinite loop
                $append = array();
                foreach( $result as &$product ) {
                    // Do step 1 above. array_shift is not the most efficient, but it
                    // allows us to iterate over the rest of the items with a simple
                    // foreach, making the code short and familiar.
                    $product[ $key ] = array_shift( $values );

                    // $product is by reference (that's why the key we added above
                    // will appear in the end result), so make a copy of it here
                    $copy = $product;

                    // Do step 2 above.
                    foreach( $values as $item ) {
                        $copy[ $key ] = $item;
                        $append[] = $copy;
                    }

                    // Undo the side effecst of array_shift
                    array_unshift( $values, $product[ $key ] );
                }

                // Out of the foreach, we can add to $results now
                $result = array_merge( $result, $append );
            }
        }

        return $result;
    }

    /**
     * @return bool|\Closure
     */
    public static function getLogger() {
        $logger = FALSE;
        if (PMXI_Plugin::is_ajax()) {
            $logger = function($m) {echo "<div class='progress-msg'>[". date("H:i:s") ."] $m</div>\n";flush();};
        }
        return $logger;
    }
}