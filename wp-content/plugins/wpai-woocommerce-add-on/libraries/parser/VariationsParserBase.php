<?php

namespace wpai_woocommerce_add_on\libraries\parser;

use XmlImportParser;

require_once dirname(__FILE__) . '/Parser.php';

/**
 * Class VariationsParserBase
 * @package wpai_woocommerce_add_on\libraries\parser
 */
abstract class VariationsParserBase extends Parser {

    /**
     * @var int
     */
    protected $index;

    /**
     * @var int
     */
    public $countVariations = 0;

    /**
     * @param $option
     * @return mixed
     */
    private function getOptionName($option) {
        return str_replace('variable_', 'product_', $option);
    }

    /**
     * Get complete XPath expression for parser factory.
     *
     * @return string
     */
    public function getCompleteParentXPath() {
        return $this->getXpath() . $this->getImport()->xpath;
    }

    /**
     * Get complete variations XPath expression for parser factory.
     *
     * @return string
     */
    public function getCompleteVariationsXPath() {
        return $this->getXpath() . $this->getImport()->xpath . '[' . ( $this->index + 1 ) . ']/'.  ltrim(trim(str_replace("[*]", "", $this->getImport()->options['variations_xpath']),'{}'), '/');
    }

    /**
     * Parse variation options type #1.
     *
     * @param $option
     */
    public function parseOptionType_1($option) {

        try {
            if ($this->getImport()->options[$option] != ""){
                $this->data[$this->getOptionName($option)] = XmlImportParser::factory($this->getXml(), $this->getCompleteVariationsXPath(), $this->getImport()->options[$option], $file)->parse(); $this->tmp_files[] = $file;
            }
            else{
                $this->countVariations && $this->data[$this->getOptionName($option)] = array_fill(0, $this->countVariations, '');
            }
        }
        catch (\Exception $e) {
            $this->log('<b>ERROR:</b> ' . $e->getMessage());
        }
    }

    /**
     * Parse variation options type #2.
     *
     * @param $option
     */
    public function parseOptionType_2($option) {
        try {
            if ($this->getImport()->options['is_variable_' . $option] != 'yes' && "" != $this->getImport()->options['single_variable_' . $option]){
                if ($this->getImport()->options['single_variable_' . $option . '_use_parent']) {
                    $parsedData = XmlImportParser::factory($this->getXml(), $this->getCompleteParentXPath(), $this->getImport()->options['single_variable_' . $option], $file)->parse(); $this->tmp_files[] = $file;
                    $this->countVariations && $this->data[$option] = array_fill(0, $this->countVariations, $parsedData[$this->index]);
                }
                else {
                    $this->data[$option] = XmlImportParser::factory($this->getXml(), $this->getCompleteVariationsXPath(), $this->getImport()->options['single_variable_' . $option], $file)->parse(); $this->tmp_files[] = $file;
                }
            }
            else{
                $this->countVariations && $this->data[$option] = array_fill(0, $this->countVariations, $this->getImport()->options['is_variable_' . $option]);
            }
        }
        catch (\Exception $e) {
            $this->log('<b>ERROR:</b> ' . $e->getMessage());
        }
    }

    /**
     * Parse variation options type #3.
     *
     * @param $option
     */
    public function parseOptionType_3($option) {
        try {
            if ($this->getImport()->options['is_multiple_variable_' . $option] != 'yes' && "" != $this->getImport()->options['single_variable_' . $option]) {
                if ($this->getImport()->options['single_variable_' . $option . '_use_parent']) {
                    $parsedData = XmlImportParser::factory($this->getXml(), $this->getCompleteParentXPath(), $this->getImport()->options['single_variable_' . $option], $file)->parse(); $this->tmp_files[] = $file;
                    $this->countVariations && $this->data[$option] = array_fill(0, $this->countVariations, $parsedData[$this->index]);
                }
                else {
                    $this->data[$option] = XmlImportParser::factory($this->getXml(), $this->getCompleteVariationsXPath(), $this->getImport()->options['single_variable_' . $option], $file)->parse(); $this->tmp_files[] = $file;
                }
            }
            else{
                $this->countVariations && $this->data[$option] = array_fill(0, $this->countVariations, $this->getImport()->options['multiple_variable_' . $option]);
            }
        }
        catch (\Exception $e) {
            $this->log('<b>ERROR:</b> ' . $e->getMessage());
        }
    }

    /**
     * Parse variation options type #4.
     *
     * @param $option
     */
    public function parseOptionType_4($option) {
        try {
            if ($this->getImport()->options[$option] != "") {
                if ($this->getImport()->options[$option . '_use_parent']) {
                    $parsedData = XmlImportParser::factory($this->getXml(), $this->getCompleteParentXPath(), $this->getImport()->options[$option], $file)->parse(); $this->tmp_files[] = $file;
                    $this->countVariations && $this->data[$this->getOptionName($option)] = array_fill(0, $this->countVariations, $parsedData[$this->index]);
                }
                else {
                    $this->data[$this->getOptionName($option)] = XmlImportParser::factory($this->getXml(), $this->getCompleteVariationsXPath(), $this->getImport()->options[$option], $file)->parse(); $this->tmp_files[] = $file;
                }
            }
            else{
                $this->countVariations && $this->data[$this->getOptionName($option)] = array_fill(0, $this->countVariations, '');
            }
        }
        catch (\Exception $e) {
            $this->log('<b>ERROR:</b> ' . $e->getMessage());
        }
    }

    /**
     * Parse variation options type #5.
     *
     * @param $option
     */
    public function parseOptionType_5($option) {
        try {
            if (!empty($this->getImport()->options[$option])) {
                if ($this->getImport()->options[$option . '_use_parent']) {
                    $parsedData = array_map(array(
                        $this,
                        'adjustPrice'
                    ), array_map(array(
                        $this,
                        'preparePrice'
                    ), XmlImportParser::factory($this->getXml(), $this->getCompleteParentXPath(), $this->getImport()->options[$option], $file)
                        ->parse()), array_fill(0, $this->getCount(), $option));
                    $this->tmp_files[] = $file;
                    $this->countVariations && $this->data[$this->getOptionName($option)] = array_fill(0, $this->countVariations, $parsedData[$this->index]);
                }
                else {
                    $this->data[$this->getOptionName($option)] = array_map(array(
                        $this,
                        'preparePrice'
                    ), XmlImportParser::factory($this->getXml(), $this->getCompleteVariationsXPath(), $this->getImport()->options[$option], $file)
                        ->parse());
                    $this->tmp_files[] = $file;
                }
            }
            else {
                $this->countVariations && $this->data[$this->getOptionName($option)] = array_fill(0, $this->countVariations, '');
            }
        }
        catch (\Exception $e) {
            $this->log('<b>ERROR:</b> ' . $e->getMessage());
        }
    }

    /**
     * Parse product's variation stock status.
     */
    public function parseVariationStockStatus() {
        try {
            // Composing product Stock status.
            if ($this->getImport()->options['variable_stock_status'] == 'xpath' && "" != $this->getImport()->options['single_variable_stock_status']) {
                $this->data['product_stock_status'] = XmlImportParser::factory($this->getXml(), $this->getCompleteVariationsXPath(), $this->getImport()->options['single_variable_stock_status'], $file)
                    ->parse();
                $this->tmp_files[] = $file;
            }
            elseif ($this->getImport()->options['variable_stock_status'] == 'auto') {
                $this->countVariations && $this->data['product_stock_status'] = array_fill(0, $this->countVariations, $this->getImport()->options['variable_stock_status']);
                $noStock = absint(max(get_option('woocommerce_notify_no_stock_amount'), 0));
                foreach ($this->data['product_stock'] as $key => $value) {
                    if ($this->data['product_manage_stock'][$key] == 'yes') {
                        $this->data['product_stock_status'][$key] = (((int) $value === 0 || (int) $value <= $noStock) && $value != "") ? 'outofstock' : 'instock';
                    }
                    else {
                        $this->data['product_stock_status'][$key] = 'instock';
                    }
                }
            }
            else {
                $this->getCount() && $this->data['product_stock_status'] = array_fill(0, $this->countVariations, $this->getImport()->options['variable_stock_status']);
            }
        }
        catch (\Exception $e) {
            $this->log('<b>ERROR:</b> ' . $e->getMessage());
        }
    }

    /**
     * Parse product's variation backorders.
     */
    public function parseVariationBackorders() {
        try {
            if ($this->getImport()->options['variable_allow_backorders'] == 'xpath' && "" != $this->getImport()->options['single_variable_allow_backorders']){
                $this->data['product_allow_backorders'] =  XmlImportParser::factory($this->getXml(), $this->getCompleteVariationsXPath(), $this->getImport()->options['single_variable_allow_backorders'], $file)->parse(); $this->tmp_files[] = $file;
            }
            else{
                $this->countVariations && $this->data['product_allow_backorders'] = array_fill(0, $this->countVariations, $this->getImport()->options['variable_allow_backorders']);
            }
        }
        catch (\Exception $e) {
            $this->log('<b>ERROR:</b> ' . $e->getMessage());
        }
    }

    /**
     * Parse product's variation status.
     */
    public function parseVariationStatus() {
        try {
            if ($this->getImport()->options['is_variable_product_enabled'] == 'xpath' && "" != $this->getImport()->options['single_variable_product_enabled']){
                $this->data['product_enabled'] = XmlImportParser::factory($this->getXml(), $this->getCompleteVariationsXPath(), $this->getImport()->options['single_variable_product_enabled'], $file)->parse(); $this->tmp_files[] = $file;
            }
            else{
                $this->countVariations && $this->data['product_enabled'] = array_fill(0, $this->countVariations, $this->getImport()->options['is_variable_product_enabled']);
            }
        }
        catch (\Exception $e) {
            $this->log('<b>ERROR:</b> ' . $e->getMessage());
        }
    }

    /**
     * Parse product's variation sale price schedule.
     * - product_sale_price_dates_from
     * - product_sale_price_dates_to
     */
    public function parseVariationSalePriceSchedule() {
        try {
            if ($this->getImport()->options['is_variable_sale_price_shedule']) {
                // Sale price dates from.
                if (!empty($this->getImport()->options['variable_sale_price_dates_from'])) {
                    if ($this->getImport()->options['variable_sale_dates_use_parent']) {
                        $parsedData = XmlImportParser::factory($this->getXml(), $this->getCompleteParentXPath(), $this->getImport()->options['variable_sale_price_dates_from'], $file)
                            ->parse();
                        $this->tmp_files[] = $file;
                        $this->countVariations && $this->data['product_sale_price_dates_from'] = array_fill(0, $this->countVariations, $parsedData[$this->index]);
                    }
                    else {
                        $this->data['product_sale_price_dates_from'] = XmlImportParser::factory($this->getXml(), $this->getCompleteVariationsXPath(), $this->getImport()->options['variable_sale_price_dates_from'], $file)
                            ->parse();
                        $this->tmp_files[] = $file;
                    }
                }
                else {
                    $this->countVariations && $this->data['product_sale_price_dates_from'] = array_fill(0, $this->countVariations, '');
                }
                // Sale price dates to.
                if (!empty($this->getImport()->options['variable_sale_price_dates_to'])) {
                    if ($this->getImport()->options['variable_sale_dates_use_parent']) {
                        $parsedData = XmlImportParser::factory($this->getXml(), $this->getCompleteParentXPath(), $this->getImport()->options['variable_sale_price_dates_to'], $file)
                            ->parse();
                        $this->tmp_files[] = $file;
                        $this->countVariations && $this->data['product_sale_price_dates_to'] = array_fill(0, $this->countVariations, $parsedData[$this->index]);
                    }
                    else {
                        $this->data['product_sale_price_dates_to'] = XmlImportParser::factory($this->getXml(), $this->getCompleteVariationsXPath(), $this->getImport()->options['variable_sale_price_dates_to'], $file)
                            ->parse();
                        $this->tmp_files[] = $file;
                    }
                }
                else {
                    $this->countVariations && $this->data['product_sale_price_dates_to'] = array_fill(0, $this->countVariations, '');
                }
            }
        }
        catch (\Exception $e) {
            $this->log('<b>ERROR:</b> ' . $e->getMessage());
        }
    }

    /**
     * Parse product's variation attributes.
     */
    public function parseVariationAttributes() {
        try {
            $variation_attributes = array();
            $options = array(
                'variable_attribute_name',
                'variable_attribute_value',
                'variable_in_variations',
                'variable_is_visible',
                'variable_is_taxonomy',
                'variable_create_taxonomy_in_not_exists'
            );
            $attributeNames = array_filter($this->getImport()->options['variable_attribute_name']);
            for ($j = 0; $j < count($attributeNames); $j++) {
                foreach ($options as $option) {
                    $variation_attributes[$option][$j] = XmlImportParser::factory($this->getXml(), $this->getCompleteVariationsXPath(), $this->getImport()->options[$option][$j], $file)
                        ->parse();
                    $this->tmp_files[] = $file;
                }
            }
            // Serialized attributes for product variations.
            $this->data['serialized_attributes'] = array();
            if (!empty($variation_attributes)) {
                foreach ($attributeNames as $j => $attribute_name) {
                    if (!in_array($attribute_name, array_keys($this->data['serialized_attributes']))) {
                        $this->data['serialized_attributes'][$attribute_name] = array(
                            'names' => $variation_attributes['variable_attribute_name'][$j],
                            'value' => $variation_attributes['variable_attribute_value'][$j],
                            'is_visible' => $variation_attributes['variable_is_visible'][$j],
                            'in_variation' => $variation_attributes['variable_in_variations'][$j],
                            'in_taxonomy' => $variation_attributes['variable_is_taxonomy'][$j],
                            'is_create_taxonomy_terms' => $variation_attributes['variable_create_taxonomy_in_not_exists'][$j]
                        );
                    }
                }
            }
        }
        catch (\Exception $e) {
            $this->log('<b>ERROR:</b> ' . $e->getMessage());
        }
    }
}