<?php

namespace wpai_woocommerce_add_on\libraries\importer;

/**
 * Class ImportOrderShippingItems
 * @package wpai_woocommerce_add_on\libraries\importer
 */
class ImportOrderShippingItems extends ImportOrderItemsBase {

    /**
     * @var
     */
    public $shipping_methods;

    /**
     * @var array
     */
    public $shipping_zone_methods = array();

    /**
     *  Importing shipping items
     */
    public function import() {

        $this->shipping_methods = WC()->shipping->get_shipping_methods();

        if (class_exists('WC_Shipping_Zones')) {
            $zones = \WC_Shipping_Zones::get_zones();
            if (!empty($zones)) {
                foreach ($zones as $zone_id => $zone) {
                    if (!empty($zone['shipping_methods'])) {
                        foreach ($zone['shipping_methods'] as $method) {
                            $this->shipping_zone_methods[] = $method;
                        }
                    }
                }
            }
            else {
                $zone = new \WC_Shipping_Zone(0);
                $this->shipping_zone_methods = $zone->get_shipping_methods();
            }
        }

        if ($this->isNewOrder() || $this->getImport()->options['update_all_data'] == 'yes' || $this->getImport()->options['is_update_shipping']) {
            $this->_import_shipping_items();
        }
    }

    /**
     *  Import order shipping items
     */
    protected function _import_shipping_items() {
        $shipping_items = $this->getValue('shipping');
        if (!empty($shipping_items)) {
            $total_shipping = 0;
            foreach ($shipping_items as $shippingIndex => $shipping) {
                if (empty($shipping['name'])) {
                    continue;
                }

                $method = FALSE;

                if ($this->getImport()->options['pmwi_order']['shipping'][0]['class'] == 'xpath') {
                    if (empty($this->shipping_methods[$shipping['class']])) {
                        foreach ($this->shipping_methods as $shipping_method_slug => $shipping_method) {

                            if ($shipping_method_slug == str_replace(" ", "_", strtolower(trim($shipping['class']))) || $shipping_method->method_title == $shipping['class']) {
                                $method = $shipping_method;
                                break;
                            }
                        }
                    }
                    else {
                        $method = $this->shipping_methods[$shipping['class']];
                    }

                    if (empty($method) && !empty($this->shipping_zone_methods)) {
                        foreach ($this->shipping_zone_methods as $shipping_zone_method) {
                            if ($shipping_zone_method->title == $shipping['class']) {
                                $method = $shipping_zone_method;
                                break;
                            }
                        }
                    }
                }
                else {
                    foreach ($this->shipping_methods as $shipping_method_slug => $shipping_method) {
                        if ($shipping_method_slug == str_replace(" ", "_", strtolower(trim($shipping['class']))) || $shipping_method->method_title == $shipping['class']) {
                            $method = $shipping_method;
                            break;
                        }
                    }
                }

                if ($method) {
                    $shipping_method = new \WC_Shipping_Rate($method->id, $shipping['name'], $shipping['amount']);

                    $shipping_item = new \PMXI_Post_Record();
                    $shipping_item->getBy(array(
                        'import_id' => $this->getImport()->id,
                        'post_id' => $this->getOrderID(),
                        'unique_key' => 'shipping-item-' . $shippingIndex
                    ));

                    $total_shipping += $shipping['amount'];

                    if ($shipping_item->isEmpty()) {
                        $item_id = FALSE;

                        if (!$this->isNewOrder()) {
                            $order_items = $this->getOrder()
                                ->get_items('shipping');

                            foreach ($order_items as $order_item_id => $order_item) {
                                if ($order_item['name'] == $shipping['name']) {
                                    $item_id = $order_item_id;
                                    break(2);
                                }
                            }
                        }

                        if (!$item_id) {

                            $item = new \WC_Order_Item_Shipping();
                            $item->set_props(array(
                                'method_title' => $shipping_method->label,
                                'method_id' => $shipping_method->id,
                                'total' => wc_format_decimal($shipping_method->cost),
                                'taxes' => $shipping_method->taxes,
                                'order_id' => $this->getOrderID(),
                            ));
                            foreach ($shipping_method->get_meta_data() as $key => $value) {
                                $item->add_meta_data($key, $value, TRUE);
                            }
                            $item->save();
                            $this->getOrder()->add_item($item);
                            $item_id = $item->get_id();

                        }

                        if (!$item_id) {
                            $this->getLogger() and call_user_func($this->getLogger(), __('- <b>WARNING</b> Unable to create order shipping line.', \PMWI_Plugin::TEXT_DOMAIN));
                        }
                        else {
                            $shipping_item->set(array(
                                'import_id' => $this->getImport()->id,
                                'post_id' => $this->getOrderID(),
                                'unique_key' => 'shipping-item-' . $shippingIndex,
                                'product_key' => 'shipping-item-' . $item_id,
                                'iteration' => $this->getImport()->iteration
                            ))->save();
                        }
                    }
                    else {
                        $item_id = str_replace('shipping-item-', '', $shipping_item->product_key);
                        $item = $this->getOrder()->get_item($item_id);
                        if (is_object($item) && $item->is_type('shipping')) {
                            $args = array(
                                'method_title' => $shipping_method->label,
                                'method_id' => $shipping_method->id,
                                'cost' => $shipping_method->cost
                            );
                            $item->set_order_id($this->getOrderID());
                            $item->set_props($args);
                            $item->save();
                            $this->getOrder()->calculate_shipping();

                            $is_updated = $item->get_id();
                        }

                        if ($is_updated) {
                            $shipping_item->set(array(
                                'iteration' => $this->getImport()->iteration
                            ))->save();
                        }
                    }
                }
            }
            update_post_meta($this->getOrderID(), '_order_shipping', $total_shipping);

            $this->_calculate_shipping_taxes();
        }
    }
}