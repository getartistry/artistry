<?php

namespace wpai_woocommerce_add_on\libraries\importer;

require_once dirname(__FILE__) . '/ImportProduct.php';

/**
 *
 * Import Grouped Product.
 *
 * Class ImportGroupedProduct
 * @package wpai_woocommerce_add_on\libraries\importer
 */
class ImportGroupedProduct extends ImportProduct {

    /**
     * @var string
     */
    protected $productType = 'grouped';

    /**
     * @return mixed
     */
    public function import() {
        parent::import();
    }

    /**
     *  Define general properties for grouped product.
     */
    public function prepareGeneralProperties() {
        parent::prepareGeneralProperties();
        $children = array();
        $this->setProperty('children', $children);
    }
}
