<?php

namespace wpai_woocommerce_add_on\libraries\parser;

use wpai_woocommerce_add_on\libraries\helpers\ParserOptions;

require_once dirname(__FILE__) . '/VariationsParserBase.php';

/**
 * Class VariationsParser
 * @package wpai_woocommerce_add_on\libraries\parser
 */
class VariationsParser extends VariationsParserBase  {

    /**
     * VariationsParser constructor.
     *
     * @param ParserOptions $options
     * @param $index
     */
    public function __construct(ParserOptions $options, $index) {
        parent::__construct($options);
        $this->index = $index;
    }

    /**
     *
     * Parse WooCommerce Products Import Template for Variation.
     *
     * @return array
     */
    public function parse() {
        try {
            $this->getChunk() == 1 && $this->log(__('Composing variations data...', \PMWI_Plugin::TEXT_DOMAIN));
            $variations = \XmlImportParser::factory($this->getXml(), $this->getCompleteVariationsXPath(), '/', $file)->parse(); $tmp_files[] = $file;
            $this->countVariations = count($variations);
            foreach ($this->getParsingWorkflow() as $callback => $options) {
                if (!empty($options)) {
                    array_map(array($this, $callback), $options);
                    continue;
                }
                call_user_func(array($this, $callback));
            }
        }
        catch (\Exception $e) {}
        // Remove all temporary files created.
        $this->unlinkTempFiles();
    }

    /**
     * @return int
     */
    public function getCountVariations() {
        return $this->countVariations;
    }

    /**
     * Get parsing workflow, where keys are callbacks for values.
     *
     * @return array
     */
    public function getParsingWorkflow() {
        return array(
            'parseOptionType_1' => array(
                'variable_sku',
                'variable_file_paths',
                'variable_file_names'
            ),
            'parseOptionType_2' => array(
                'product_manage_stock',
                'product_downloadable',
                'product_virtual'
            ),
            'parseOptionType_3' => array(
                'product_shipping_class',
                'product_tax_class'
            ),
            'parseOptionType_4' => array(
                'variable_description',
                'variable_stock',
                'variable_image',
                'variable_weight',
                'variable_length',
                'variable_width',
                'variable_height',
                'variable_download_limit',
                'variable_download_expiry'
            ),
            'parseOptionType_5' => array(
                'variable_regular_price',
                'variable_sale_price',
                'variable_whosale_price'
            ),
            'parseVariationStockStatus' => array(),
            'parseVariationBackorders' => array(),
            'parseVariationStatus' => array(),
            'parseVariationSalePriceSchedule' => array(),
            'parseVariationAttributes' => array()
        );
    }
}