<?php

namespace wpai_woocommerce_add_on\libraries\importer;

use WC_Payment_Gateways;

require_once dirname(__FILE__) . '/ImportOrderBase.php';

/**
 *
 * Import Order payment details
 *
 * Class ImportOrderDetails
 * @package wpai_woocommerce_add_on\libraries\importer
 */
class ImportOrderPayment extends ImportOrderBase {

    public $payment_gateways;

    /**
     * @return void
     */
    public function import() {

        $this->payment_gateways = WC_Payment_Gateways::instance()
            ->payment_gateways();

        if ($this->isNewOrder() || $this->getImport()->options['update_all_data'] == 'yes' || $this->getImport()->options['is_update_payment']) {
            $payment_method = $this->getValue('payment_method');

            if (!empty($payment_method)) {
                if (!empty($this->payment_gateways[$payment_method])) {
                    update_post_meta($this->getOrderID(), '_payment_method', $payment_method);
                    update_post_meta($this->getOrderID(), '_payment_method_title', $this->payment_gateways[$payment_method]->title);
                }
                else {
                    $method = FALSE;
                    if (!empty($this->payment_gateways)) {
                        foreach ($this->payment_gateways as $payment_gateway_slug => $payment_gateway) {
                            if (strtolower($payment_gateway->method_title) == strtolower(trim($payment_method))) {
                                $method = $payment_method;
                                break;
                            }
                        }
                    }
                    if ($method) {
                        update_post_meta($this->getOrderID(), '_payment_method', $payment_method);
                        update_post_meta($this->getOrderID(), '_payment_method_title', $method->method_title);
                    }
                }
            }
            else {
                update_post_meta($this->getOrderID(), '_payment_method', 'N/A');
            }
            update_post_meta($this->getOrderID(), '_transaction_id', $this->getValue('transaction_id'));
        }
    }
}