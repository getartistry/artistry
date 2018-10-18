<?php

namespace wpai_woocommerce_add_on\libraries\importer;

/**
 * Class ImportOrderTaxItems
 * @package wpai_woocommerce_add_on\libraries\importer
 */
class ImportOrderTaxItems extends ImportOrderItemsBase {

    public $tax_rates = array();

    /**
     *  Importing taxes items
     */
    public function import() {

        $tax_classes = array_filter(array_map('trim', explode("\n", get_option('woocommerce_tax_classes'))));

        if ($tax_classes) {
            // Add Standard tax class
            if (!in_array('', $tax_classes)) {
                $tax_classes[] = '';
            }

            foreach ($tax_classes as $class) {
                foreach (\WC_Tax::get_rates_for_tax_class(sanitize_title($class)) as $rate_key => $rate) {
                    $this->tax_rates[$rate->tax_rate_id] = $rate;
                }
            }
        }

        if ($this->isNewOrder() || $this->getImport()->options['update_all_data'] == 'yes' || $this->getImport()->options['is_update_taxes']) {
            $this->_import_taxes_items();
        }
    }

    protected function _import_taxes_items() {
        $taxes = $this->getValue('taxes');
        if (!empty($taxes)) {
            foreach ($taxes as $taxIndex => $tax) {
                if (empty($tax['tax_code'])) {
                    continue;
                }
                $founded = TRUE;
                $tax_rate = NULL;
                if ($this->getImport()->options['pmwi_order']['taxes'][0]['tax_code'] == 'xpath') {
                    if (empty($this->tax_rates[$tax['tax_code']])) {
                        $founded_by_name = FALSE;
                        foreach ($this->tax_rates as $rate_id => $rate) {
                            if ($rate->tax_rate_name == $tax['tax_code']) {
                                $founded_by_name = TRUE;
                                $tax_rate = $rate;
                                break;
                            }
                        }
                        if (!$founded_by_name) {
                            $founded = FALSE;
                        }
                    }
                    else {
                        $tax_rate = $this->tax_rates[$tax['tax_code']];
                        $tax['tax_amount'] = 0;
                        $tax['shipping_tax_amount'] = 0;
                    }
                }
                else {
                    if (!empty($this->tax_rates[$tax['tax_code']])) {
                        $tax_rate = $this->tax_rates[$tax['tax_code']];
                        $tax['tax_amount'] = 0;
                        $tax['shipping_tax_amount'] = 0;
                    }
                    else {
                        $founded = FALSE;
                    }
                }

                if ($founded) {
                    $tax_item = new \PMXI_Post_Record();
                    $tax_item->getBy(array(
                        'import_id' => $this->getImport()->id,
                        'post_id' => $this->getOrderID(),
                        'unique_key' => 'tax-item-' . $taxIndex
                    ));

                    if ($tax_item->isEmpty()) {
                        $item_id = FALSE;

                        if (!$this->isNewOrder()) {
                            $order_items = $this->getOrder()->get_items('tax');

                            foreach ($order_items as $order_item_id => $order_item) {
                                if ($order_item['name'] == $tax['tax_code']) {
                                    $item_id = $order_item_id;
                                    break(2);
                                }
                            }
                        }

                        if (!$item_id) {

                            if (version_compare(WOOCOMMERCE_VERSION, '3.0') < 0) {
                                $item_id = $this->getOrder()
                                    ->add_tax($tax_rate->tax_rate_id, $tax['tax_amount'], $tax['shipping_tax_amount']);
                            }
                            else {

                                $item = new \WC_Order_Item_Tax();
                                $item->set_props(array(
                                    'name' => $tax_rate->tax_rate_name,
                                    'tax_class' => empty($tax_rate->tax_rate_class) ? 0 : $tax_rate->tax_rate_class,
                                    'total' => $tax['tax_amount'],
                                    'total_tax' => $tax['tax_amount'],
                                    'order_id' => $this->getOrderID(),
                                ));
                                $item_id = $item->save();
                            }
                        }

                        if (!$item_id) {
                            $this->getLogger() and call_user_func($this->getLogger(), __('- <b>WARNING</b> Unable to create order tax line.', \PMWI_Plugin::TEXT_DOMAIN));
                        }
                        else {
                            $tax_item->set(array(
                                'import_id' => $this->getImport()->id,
                                'post_id' => $this->getOrderID(),
                                'unique_key' => 'tax-item-' . $taxIndex,
                                'product_key' => 'tax-item-' . $item_id,
                                'iteration' => $this->getImport()->iteration
                            ))->save();
                        }
                    }
                    else {

                        $item_id = str_replace('tax-item-', '', $tax_item->product_key);

                        if (version_compare(WOOCOMMERCE_VERSION, '3.0') >= 0) {

                            $item = new \WC_Order_Item_Tax($item_id);

                            if (isset($tax_rate->tax_rate_name)) {
                                $item->set_name(wc_clean($tax_rate->tax_rate_name));
                            }
                            if (isset($tax_rate->tax_rate_id)) {
                                $item->set_rate($tax_rate->tax_rate_id);
                            }
                            if (isset($tax['tax_amount'])) {
                                $item->set_tax_total(floatval($tax['tax_amount']));
                            }

                            $is_updated = $item->save();

                            if ($is_updated) {
                                $tax_item->set(array(
                                    'iteration' => $this->getImport()->iteration
                                ))->save();
                            }
                        }
                    }
                }
            }
        }
    }
}