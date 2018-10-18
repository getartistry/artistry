<?php

namespace wpai_woocommerce_add_on\libraries\importer;

require_once dirname(__FILE__) . '/ImportOrderBase.php';

/**
 *
 * Import Order items
 *
 * Class ImportOrderItemsBase
 * @package wpai_woocommerce_add_on\libraries\importer
 */
abstract class ImportOrderItemsBase extends ImportOrderBase {

    /**
     * @return bool
     */
    protected function _calculate_fee_taxes() {

        $tax_total = 0;
        $shipping_tax_total = 0;
        $taxes = array();
        $shipping_taxes = array();
        $tax_based_on = get_option('woocommerce_tax_based_on');

        // If is_vat_exempt is 'yes', or wc_tax_enabled is false, return and do nothing.
        if (!wc_tax_enabled()) {
            return FALSE;
        }

        $order = &$this->getOrder();

        if ('billing' === $tax_based_on) {
            $country = $order->billing_country;
            $state = $order->billing_state;
            $postcode = $order->billing_postcode;
            $city = $order->billing_city;
        }
        elseif ('shipping' === $tax_based_on) {
            $country = $order->shipping_country;
            $state = $order->shipping_state;
            $postcode = $order->shipping_postcode;
            $city = $order->shipping_city;
        }

        // Default to base
        if ('base' === $tax_based_on || empty($country)) {
            $default = wc_get_base_location();
            $country = $default['country'];
            $state = $default['state'];
            $postcode = '';
            $city = '';
        }

        // Get items
        foreach ($order->get_items(array('fee')) as $item_id => $item) {

            $product = $order->get_product_from_item($item);
            $line_total = isset($item['line_total']) ? $item['line_total'] : 0;
            $line_subtotal = isset($item['line_subtotal']) ? $item['line_subtotal'] : 0;
            $tax_class = $item['tax_class'];
            $item_tax_status = $product ? $product->get_tax_status() : 'taxable';

            if ('0' !== $tax_class && 'taxable' === $item_tax_status) {

                $tax_rates = \WC_Tax::find_rates(array(
                    'country' => $country,
                    'state' => $state,
                    'postcode' => $postcode,
                    'city' => $city,
                    'tax_class' => $tax_class
                ));

                $line_subtotal_taxes = \WC_Tax::calc_tax($line_subtotal, $tax_rates, FALSE);
                $line_taxes = \WC_Tax::calc_tax($line_total, $tax_rates, FALSE);
                $line_subtotal_tax = max(0, array_sum($line_subtotal_taxes));
                $line_tax = max(0, array_sum($line_taxes));
                $tax_total += $line_tax;

                wc_update_order_item_meta($item_id, '_line_subtotal_tax', wc_format_decimal($line_subtotal_tax));
                wc_update_order_item_meta($item_id, '_line_tax', wc_format_decimal($line_tax));
                wc_update_order_item_meta($item_id, '_line_tax_data', array(
                    'total' => $line_taxes,
                    'subtotal' => $line_subtotal_taxes
                ));

                // Sum the item taxes
                foreach (array_keys($taxes + $line_taxes) as $key) {
                    $taxes[$key] = (isset($line_taxes[$key]) ? $line_taxes[$key] : 0) + (isset($taxes[$key]) ? $taxes[$key] : 0);
                }
            }
        }
    }

    protected function _calculate_shipping_taxes() {

        $tax_total = 0;
        $shipping_tax_total = 0;
        $taxes = array();
        $shipping_taxes = array();
        $tax_based_on = get_option('woocommerce_tax_based_on');

        // If is_vat_exempt is 'yes', or wc_tax_enabled is false, return and do nothing.
        if (!wc_tax_enabled()) {
            return FALSE;
        }

        $order = &$this->getOrder();

        if ('billing' === $tax_based_on) {
            $country = $order->billing_country;
            $state = $order->billing_state;
            $postcode = $order->billing_postcode;
            $city = $order->billing_city;
        }
        elseif ('shipping' === $tax_based_on) {
            $country = $order->shipping_country;
            $state = $order->shipping_state;
            $postcode = $order->shipping_postcode;
            $city = $order->shipping_city;
        }

        // Calc taxes for shipping
        foreach ($order->get_shipping_methods() as $item_id => $item) {

            $shipping_tax_class = get_option('woocommerce_shipping_tax_class');

            // Inherit tax class from items
            if ('' === $shipping_tax_class) {
                $tax_classes = \WC_Tax::get_tax_classes();
                $found_tax_classes = $order->get_items_tax_classes();

                foreach ($tax_classes as $tax_class) {
                    $tax_class = sanitize_title($tax_class);
                    if (in_array($tax_class, $found_tax_classes)) {
                        $tax_rates = \WC_Tax::find_shipping_rates(array(
                            'country' => $country,
                            'state' => $state,
                            'postcode' => $postcode,
                            'city' => $city,
                            'tax_class' => $tax_class,
                        ));
                        break;
                    }
                }
            }
            else {
                $tax_rates = \WC_Tax::find_shipping_rates(array(
                    'country' => $country,
                    'state' => $state,
                    'postcode' => $postcode,
                    'city' => $city,
                    'tax_class' => 'standard' === $shipping_tax_class ? '' : $shipping_tax_class,
                ));
            }

            $line_taxes = \WC_Tax::calc_tax($item['cost'], $tax_rates, FALSE);
            $line_tax = max(0, array_sum($line_taxes));
            $shipping_tax_total += $line_tax;

            wc_update_order_item_meta($item_id, '_line_tax', wc_format_decimal($line_tax));
            wc_update_order_item_meta($item_id, '_line_tax_data', array('total' => $line_taxes));

            // Sum the item taxes
            foreach (array_keys($shipping_taxes + $line_taxes) as $key) {
                $shipping_taxes[$key] = (isset($line_taxes[$key]) ? $line_taxes[$key] : 0) + (isset($shipping_taxes[$key]) ? $shipping_taxes[$key] : 0);
            }
            wc_update_order_item_meta($item_id, 'taxes', $shipping_taxes);
        }
        // Save tax totals
        $order->set_total($shipping_tax_total, 'shipping_tax');
    }
}