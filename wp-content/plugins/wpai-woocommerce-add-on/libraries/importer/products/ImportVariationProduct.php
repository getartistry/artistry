<?php

namespace wpai_woocommerce_add_on\libraries\importer;

use WC_Product_Attribute;

require_once dirname(__FILE__) . '/ImportProduct.php';

/**
 * Import Variation Product.
 *
 * Class ImportVariationProduct
 * @package wpai_woocommerce_add_on\libraries\importer
 */
class ImportVariationProduct extends ImportProduct {

    /**
     * @var \WC_Product_Variation
     */
    public $product;

    /**
     * @var string
     */
    protected $productType = 'product_variation';

    /**
     * @return void
     */
    public function import() {
        parent::import();
    }

    /**
     * @return mixed
     */
    public function setProperties() {
        // Set variation description.
        $this->product->set_description($this->getValue('product_variation_description'));
        // Is variation enabled.
        if ($this->getImportService()->isUpdateDataAllowed('is_update_status', $this->isNewProduct())) {
            $post_status = $this->getValue('product_enabled') == 'yes' ? 'publish' : 'private';
            $this->product->set_status($post_status);
        }
        // Set variation basic properties.
        parent::setProperties();
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
                    $terms = $attribute->get_terms();
                    if (empty($terms)) {
                        $terms = $attribute->get_options();
                    }
                    if (!empty($terms)) {
                        $term = get_term($terms[0], $attribute->get_taxonomy());
                        if (empty($term) || is_wp_error($term)) {
                            $term = get_term_by("term_taxonomy_id", $terms[0], $attribute->get_taxonomy());
                        }
                        if ($term && !is_wp_error($term)) {
                            $parsedAttributes[sanitize_title($attribute->get_name())] = $term->slug;
                        }
                    }
                }
                else{
                    $parsedAttributes[sanitize_title($attribute->get_name())] = $attribute->get_data()['value'];
                }
            }
            else {
                $values = [];
                // Check is current attribute saved as taxonomy term.
                if ($attribute->is_taxonomy()) {
                    foreach ($attribute->get_terms() as $term) {
                        $values[] = $term->term_taxonomy_id;
                    }
                }
                else {
                    $values = $attribute->get_options();
                }
                // Collect variation attribute values in _product_attributes.
                if ($values) {
                    if (isset($currentAttributes[sanitize_title($attribute->get_name())])) {
                        $options = explode("|", $currentAttributes[sanitize_title($attribute->get_name())]['value']);
                        $options = array_map('trim', $options);
                        foreach ($values as $value) {
                            if (!in_array($value, $options)) {
                                $options[] = $value;
                            }
                        }
                        $parentAttributes[sanitize_title($attribute->get_name())]['value'] = implode("|", array_filter($options));
                    }
                    else {
                        $parentAttributes[sanitize_title($attribute->get_name())]['value'] = implode("|", $values);
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