<?php

namespace wpai_woocommerce_add_on\libraries\importer;

require_once dirname(__FILE__) . '/ImportProduct.php';

/**
 *
 * Import External Product
 *
 * Class ImportExternalProduct
 * @package wpai_woocommerce_add_on\libraries\importer
 */
class ImportExternalProduct extends ImportProduct {

    /**
     * @var string
     */
    protected $productType = 'external';

    /**
     * @return int|\WP_Error
     */
    public function import() {
        parent::import();
    }

    /**
     *  Define general properties for external product
     */
    public function prepareGeneralProperties() {
        parent::prepareGeneralProperties();
        $url = esc_url_raw($this->getValue('product_url'));
        $this->autoCloakLinks($url);
        $this->setProperty('product_url', $url);
        $this->setProperty('button_text', wc_clean($this->getValue('product_button_text')));
    }
}
