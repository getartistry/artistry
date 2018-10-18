<?php

namespace wpai_woocommerce_add_on\libraries\importer;

require_once dirname(__FILE__) . '/ImportOrderBase.php';

/**
 *
 * Import Order Total Information
 *
 * Class ImportOrderTotal
 * @package wpai_woocommerce_add_on\libraries\importer
 */
class ImportOrderTotal extends ImportOrderBase {

    /**
     * @throws \WC_Data_Exception
     */
    public function import() {

        if ($this->isNewOrder() || $this->getImport()->options['update_all_data'] == 'yes' || $this->getImport()->options['is_update_total']) {
            if ($this->getImport()->options['pmwi_order']['order_total_logic'] !== 'auto') {
                if (version_compare(WC()->version, '3.0') < 0) {
                    $this->getOrder()
                        ->set_total($this->getValue('order_total_xpath'), 'total');
                }
                else {
                    update_post_meta($this->getOrderID(), '_order_total', wc_format_decimal($this->getValue('order_total_xpath'), wc_get_price_decimals()));
                }
            }
            else {
                $this->getOrder()->calculate_totals();
            }
        }
    }
}