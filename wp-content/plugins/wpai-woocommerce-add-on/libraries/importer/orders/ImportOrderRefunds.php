<?php

namespace wpai_woocommerce_add_on\libraries\importer;

require_once dirname(__FILE__) . '/ImportOrderBase.php';

/**
 *
 * Import Order Refunds
 *
 * Class ImportOrderRefunds
 * @package wpai_woocommerce_add_on\libraries\importer
 */
class ImportOrderRefunds extends ImportOrderBase {

    /**
     * @throws \Exception
     */
    public function import() {

        if ($this->isNewOrder() || $this->getImport()->options['update_all_data'] == 'yes' || $this->getImport()->options['is_update_refunds']) {

            $order_refund_amount = $this->getValue('order_refund_amount');
            if (!empty($order_refund_amount)) {

                $refund_item = new \PMXI_Post_Record();
                $refund_item->getBy(array(
                    'import_id' => $this->getImport()->id,
                    'post_id' => $this->getOrderID(),
                    'unique_key' => 'refund-item-' . $this->getOrderID()
                ));

                $args = array(
                    'amount' => $this->getValue('order_refund_amount'),
                    'reason' => $this->getValue('order_refund_reason'),
                    'order_id' => $this->getOrderID(),
                    'refund_id' => 0,
                    'line_items' => array(),
                    'date_created' => $this->getValue('order_refund_date')
                );

                if (!$refund_item->isEmpty()) {
                    $args['refund_id'] = str_replace('refund-item-', '', $refund_item->product_key);
                }

                if (!empty($this->getImport()->options['do_not_send_order_notifications'])) {
                    remove_all_actions('woocommerce_order_partially_refunded');
                    remove_all_actions('woocommerce_order_fully_refunded');
                    remove_all_actions('woocommerce_order_status_refunded_notification');
                    remove_all_actions('woocommerce_order_partially_refunded_notification');
                    remove_action('woocommerce_order_status_refunded', array(
                        'WC_Emails',
                        'send_transactional_email'
                    ));
                    remove_action('woocommerce_order_partially_refunded', array(
                        'WC_Emails',
                        'send_transactional_email'
                    ));
                }

                $refund = wc_create_refund($args);

                if ($refund instanceOf \WC_Order_Refund) {

                    $refund_item->set(array(
                        'import_id' => $this->getImport()->id,
                        'post_id' => $this->getOrderID(),
                        'unique_key' => 'refund-item-' . $this->getOrderID(),
                        'product_key' => 'refund-item-' . $refund->get_id(),
                        'iteration' => $this->getImport()->iteration
                    ))->save();

                    $customer = FALSE;
                    if ($this->getImport()->options['pmwi_order']['order_refund_issued_source'] == 'existing') {
                        $customer = $this->getParser()->get_existing_customer('order_refund_issued', $this->getIndex());
                    }

                    if ($customer) {
                        wp_update_post(array(
                            'ID' => $refund->get_id(),
                            'post_author' => $customer->ID
                        ));
                        update_post_meta($refund->get_id(), '_refunded_by', $customer->ID);
                    }
                    else {
                        wp_update_post(array(
                            'ID' => $refund->get_id(),
                            'post_author' => 0
                        ));
                        delete_post_meta($refund->get_id(), '_refunded_by');
                    }
                }
            }
        }
    }
}