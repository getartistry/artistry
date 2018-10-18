<?php

namespace wpai_woocommerce_add_on\libraries\importer;

/**
 * Class ImportOrderFeeItems
 * @package wpai_woocommerce_add_on\libraries\importer
 */
class ImportOrderFeeItems extends ImportOrderItemsBase {

    /**
     *  Importing fee items
     */
    public function import() {
        if ($this->isNewOrder() || $this->getImport()->options['update_all_data'] == 'yes' || $this->getImport()->options['is_update_fees']) {
            $this->_import_fee_items();
        }
    }

    /**
     *  Import order fee items
     */
    protected function _import_fee_items() {
        $fees = $this->getValue('fees');
        if (!empty($fees)) {
            foreach ($fees as $feeIndex => $fee) {
                if (empty($fee['name'])) {
                    continue;
                }

                $fee_item = new \PMXI_Post_Record();
                $fee_item->getBy(array(
                    'import_id' => $this->getImport()->id,
                    'post_id' => $this->getOrderID(),
                    'unique_key' => 'fee-item-' . $feeIndex
                ));

                if ($fee_item->isEmpty()) {

                    $item_id = FALSE;

                    if (!$this->isNewOrder()) {
                        $order_items = $this->getOrder()->get_items('fee');

                        foreach ($order_items as $order_item_id => $order_item) {
                            if ($order_item['name'] == $fee['name']) {
                                $item_id = $order_item_id;
                                break(2);
                            }
                        }
                    }

                    if (!$item_id) {
                        $fee_line = array(
                            'name' => $fee['name'],
                            'tax_class' => '',
                            'amount' => $fee['amount'],
                            'tax' => '',
                            'tax_data' => array(),
                            'taxable' => 0
                        );
                        if (version_compare(WOOCOMMERCE_VERSION, '3.0') < 0) {
                            $item_id = $this->getOrder()
                                ->add_fee((object) $fee_line);
                        }
                        else {

                            $item = new \WC_Order_Item_Fee();
                            $item->set_order_id($this->getOrderID());
                            $item->set_name(wc_clean($fee_line['name']));
                            $item->set_total(isset($fee_line['amount']) ? floatval($fee_line['amount']) : 0);

                            // if taxable, tax class and total are required
                            if (!empty($fee_line['taxable'])) {
                                if (!isset($fee_line['tax_class'])) {
                                    $this->getLogger() and call_user_func($this->getLogger(), __('- <b>WARNING</b> Fee tax class is required when fee is taxable.', \PMWI_Plugin::TEXT_DOMAIN));
                                }
                                else {
                                    $item->set_tax_status('taxable');
                                    $item->set_tax_class($fee_line['tax_class']);

                                    if (isset($fee_line['total_tax'])) {
                                        $item->set_total_tax(isset($fee_line['total_tax']) ? wc_format_refund_total($fee_line['total_tax']) : 0);
                                    }

                                    if (isset($fee_line['tax_data'])) {
                                        $item->set_total_tax(wc_format_refund_total(array_sum($fee_line['tax_data'])));
                                        $item->set_taxes(array_map('wc_format_refund_total', $fee_line['tax_data']));
                                    }
                                }
                            }
                            $item_id = $item->save();
                        }
                    }

                    if (!$item_id) {
                        $this->getLogger() and call_user_func($this->getLogger(), __('- <b>WARNING</b> order line fee is not added.', \PMWI_Plugin::TEXT_DOMAIN));
                    }
                    else {
                        $fee_item->set(array(
                            'import_id' => $this->getImport()->id,
                            'post_id' => $this->getOrderID(),
                            'unique_key' => 'fee-item-' . $feeIndex,
                            'product_key' => 'fee-item-' . $item_id,
                            'iteration' => $this->getImport()->iteration
                        ))->save();
                    }
                }
                else {
                    $item_id = str_replace('fee-item-', '', $fee_item->product_key);

                    if (version_compare(WOOCOMMERCE_VERSION, '3.0') < 0) {
                        $is_updated = $this->getOrder()
                            ->update_fee($item_id, array(
                                'name' => $fee['name'],
                                'tax_class' => '',
                                'line_total' => $fee['amount'],
                                'line_tax' => 0
                            ));
                    }
                    else {
                        $item = new \WC_Order_Item_Fee($item_id);

                        if (isset($fee['title'])) {
                            $item->set_name(wc_clean($fee['name']));
                        }
                        if (isset($fee['tax_class'])) {
                            $item->set_tax_class($fee['tax_class']);
                        }
                        if (isset($fee['amount'])) {
                            $item->set_total(floatval($fee['amount']));
                        }
                        if (isset($fee['total_tax'])) {
                            $item->set_total_tax(floatval($fee['total_tax']));
                        }
                        $is_updated = $item->save();
                    }

                    if ($is_updated) {
                        $fee_item->set(array(
                            'iteration' => $this->getImport()->iteration
                        ))->save();
                    }
                }
            }
            $this->_calculate_fee_taxes();
        }
    }

}