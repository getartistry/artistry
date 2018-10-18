<?php

namespace wpai_woocommerce_add_on\libraries\importer;

/**
 * Class ImportOrderProductItems
 * @package wpai_woocommerce_add_on\libraries\importer
 */
class ImportOrderProductItems extends ImportOrderItemsBase {

    /**
     * @var int
     */
    public $prices_include_tax = 0;

    /**
     *  Importing fee items
     */
    public function import() {

        $this->prices_include_tax = ('yes' === get_option('woocommerce_prices_include_tax', 'no'));

        if ($this->isNewOrder() || $this->getImport()->options['update_all_data'] == 'yes' || $this->getImport()->options['is_update_products']) {
            if (!$this->isNewOrder() && ($this->getImport()->options['update_all_data'] == 'yes' || $this->getImport()->options['is_update_products'] && $this->getImport()->options['update_products_logic'] == 'full_update')) {
                $previously_updated_order = get_option('wp_all_import_previously_updated_order_' . $this->getImport()->id, FALSE);
                if (empty($previously_updated_order) || $previously_updated_order != $this->getArticleData('ID')) {
                    $this->getOrder()->remove_order_items('line_item');
                    $this->wpdb->query($this->wpdb->prepare("DELETE FROM {$this->wpdb->prefix}pmxi_posts WHERE import_id = %d AND post_id = %d AND unique_key LIKE %s;", $this->getImport()->id, $this->getOrderID(), '%' . $this->wpdb->esc_like('line-item') . '%'));
                }
            }
            $this->_import_line_items();
        }
    }

    /**
     * @return bool
     */
    protected function _import_line_items() {

        $is_product_founded = FALSE;

        switch ($this->getImport()->options['pmwi_order']['products_source']) {
            // Get data from existing products
            case 'existing':

                foreach ($this->getValue('products') as $productIndex => $productItem) {
                    if (empty($productItem['sku'])) {
                        continue;
                    }

                    $args = array(
                        'post_type' => 'product',
                        'meta_key' => '_sku',
                        'meta_value' => $productItem['sku'],
                        'meta_compare' => '=',
                    );

                    $product = FALSE;

                    $query = new \WP_Query($args);
                    while ($query->have_posts()) {
                        $query->the_post();
                        $product = WC()->product_factory->get_product($query->post->ID);
                        break;
                    }
                    wp_reset_postdata();

                    if (empty($product)) {
                        $args['post_type'] = 'product_variation';
                        $query = new \WP_Query($args);
                        while ($query->have_posts()) {
                            $query->the_post();
                            $product = WC()->product_factory->get_product($query->post->ID);
                            break;
                        }
                        wp_reset_postdata();
                    }

                    if ($product) {

                        $is_product_founded = TRUE;
                        $item_price = empty($productItem['price_per_unit']) ? $product->get_price() : $productItem['price_per_unit'];
                        $item_qty = empty($productItem['qty']) ? 1 : $productItem['qty'];
                        $item_subtotal = $item_price * $item_qty;
                        $item_subtotal_tax = 0;
                        $line_taxes = array();

                        foreach ($productItem['tax_rates'] as $key => $tax_rate) {
                            if (empty($tax_rate['code'])) {
                                continue;
                            }

                            $tax_rate_codes = explode("|", $tax_rate['code']);
                            $percentage_value = explode("|", $tax_rate['percentage_value']);
                            $amount_per_unit = explode("|", $tax_rate['amount_per_unit']);

                            foreach ($tax_rate_codes as $rate_key => $tax_rate_code) {

                                if ($tax_rate_code == 'standard') {
                                    $tax_rate_code = '';
                                }

                                $line_tax = 0;

                                switch ($tax_rate['calculate_logic']) {
                                    case 'percentage':

                                        if (!empty($percentage_value[$rate_key]) and is_numeric($percentage_value[$rate_key])) {
                                            $line_tax = \WC_Tax::round(($item_subtotal / 100) * $percentage_value[$rate_key]);
                                            $item_subtotal_tax += $line_tax;
                                        }

                                        if (!empty($this->tax_rates)) {
                                            foreach ($this->tax_rates as $rate_id => $rate) {
                                                if ($rate->tax_rate_name == $tax_rate_code) {
                                                    $line_taxes[$rate->tax_rate_id] = $line_tax;
                                                    break;
                                                }
                                            }
                                        }

                                        break;

                                    case 'per_unit';

                                        if (!empty($amount_per_unit[$rate_key]) and is_numeric($amount_per_unit[$rate_key])) {
                                            $line_tax = \WC_Tax::round($amount_per_unit[$rate_key] * $item_qty);
                                            $item_subtotal_tax += $line_tax;
                                        }

                                        if (!empty($this->tax_rates)) {
                                            foreach ($this->tax_rates as $rate_id => $rate) {
                                                if ($rate->tax_rate_name == $tax_rate_code) {
                                                    $line_taxes[$rate->tax_rate_id] = $line_tax;
                                                    break;
                                                }
                                            }
                                        }
                                        break;

                                    // Look up tax rate code
                                    default:

                                        $found_rates = \WC_Tax::get_rates_for_tax_class($tax_rate_code);

                                        if (!empty($found_rates)) {
                                            $found_priority = array();

                                            foreach ($found_rates as $found_rate) {
                                                $matched_tax_rates = array();

                                                if (in_array($found_rate->tax_rate_priority, $found_priority)) {
                                                    continue;
                                                }

                                                $matched_tax_rates[$found_rate->tax_rate_id] = array(
                                                    'rate' => $found_rate->tax_rate,
                                                    'label' => $found_rate->tax_rate_name,
                                                    'shipping' => $found_rate->tax_rate_shipping ? 'yes' : 'no',
                                                    'compound' => $found_rate->tax_rate_compound ? 'yes' : 'no'
                                                );

                                                $line_tax = array_sum(\WC_Tax::calc_tax($item_subtotal, $matched_tax_rates, $this->prices_include_tax));

                                                $item_subtotal_tax += $line_tax;
                                                $line_taxes[$found_rate->tax_rate_id] = $line_tax;

                                                $found_priority[] = $found_rate->tax_rate_priority;
                                            }
                                        }

                                        break;
                                }
                            }
                        }

                        $variation = array();

                        $variation_str = '';

                        if ($product instanceOf \WC_Product_Variation) {
                            $variation = $product->get_variation_attributes();

                            if (!empty($variation)) {
                                foreach ($variation as $key => $value) {
                                    $variation_str .= $key . '-' . $value;
                                }
                            }
                        }

                        $product_item = new \PMXI_Post_Record();
                        $product_item->getBy(array(
                            'import_id' => $this->getImport()->id,
                            'post_id' => $this->getOrderID(),
                            'unique_key' => 'line-item-' . $product->get_id() . '-' . $variation_str
                        ));

                        if ($product_item->isEmpty()) {
                            $item_id = FALSE;

                            // in case when this is new order just add new line items
                            if (!$item_id) {
                                $item_id = $this->getOrder()->add_product(
                                    $product,
                                    $item_qty,
                                    array(
                                        'variation' => $variation,
                                        'totals' => array(
                                            'subtotal' => $item_subtotal,
                                            'subtotal_tax' => $item_subtotal_tax,
                                            'total' => $item_subtotal,
                                            'tax' => $item_subtotal_tax,
                                            'tax_data' => array(
                                                'total' => $line_taxes,
                                                'subtotal' => array()
                                            ) // Since 2.2
                                        )
                                    )
                                );
                            }

                            if (!$item_id) {
                                $this->getLogger() and call_user_func($this->getLogger(), __('- <b>WARNING</b> Unable to create order line product.', \PMWI_Plugin::TEXT_DOMAIN));
                            }
                            else {
                                $product_item->set(array(
                                    'import_id' => $this->getImport()->id,
                                    'post_id' => $this->getOrderID(),
                                    'unique_key' => 'line-item-' . $product->get_id() . '-' . $variation_str,
                                    'product_key' => 'line-item-' . $item_id,
                                    'iteration' => $this->getImport()->iteration
                                ))->save();
                            }
                        }
                        else {
                            $item_id = str_replace('line-item-', '', $product_item->product_key);
                            $is_updated = $this->getOrder()->update_product(
                                $item_id,
                                $product,
                                array(
                                    'qty' => $item_qty,
                                    'tax_class' => $product->get_tax_class(),
                                    'totals' => array(
                                        'subtotal' => $item_subtotal,
                                        'subtotal_tax' => $item_subtotal_tax,
                                        'total' => $item_subtotal,
                                        'tax' => $item_subtotal_tax,
                                        'tax_data' => array(
                                            'total' => $line_taxes,
                                            'subtotal' => array()
                                        ) // Since 2.2
                                    ),
                                    'variation' => $variation
                                )
                            );
                            if ($is_updated) {
                                $product_item->set(array(
                                    'iteration' => $this->getImport()->iteration
                                ))->save();
                            }
                        }
                    }
                }

                break;

            // Manually import product order data and do not try to match to existing products
            default:

                $is_product_founded = TRUE;

                foreach ($this->getValue('manual_products') as $productIndex => $productItem) {

                    if (empty($productItem['sku'])) {
                        continue;
                    }

                    $item_price = $productItem['price_per_unit'];
                    $item_qty = empty($productItem['qty']) ? 1 : $productItem['qty'];
                    $item_subtotal = $item_price * $item_qty;
                    $item_subtotal_tax = 0;
                    $line_taxes = array();

                    foreach ($productItem['tax_rates'] as $key => $tax_rate) {
                        if (empty($tax_rate['code'])) {
                            continue;
                        }

                        $line_tax = 0;

                        switch ($tax_rate['calculate_logic']) {
                            case 'percentage':

                                if (!empty($tax_rate['percentage_value']) and is_numeric($tax_rate['percentage_value'])) {
                                    $line_tax = \WC_Tax::round(($item_subtotal / 100) * $tax_rate['percentage_value']);
                                    $item_subtotal_tax += $line_tax;
                                }
                                break;

                            case 'per_unit';

                                if (!empty($tax_rate['amount_per_unit']) and is_numeric($tax_rate['amount_per_unit'])) {
                                    $line_tax = \WC_Tax::round($tax_rate['amount_per_unit'] * $item_qty);
                                    $item_subtotal_tax += $line_tax;
                                }
                                break;

                            // Look up tax rate code
                            default:

                                $found_rates = \WC_Tax::get_rates_for_tax_class($tax_rate['code']);

                                if (!empty($found_rates)) {
                                    $matched_tax_rates = array();
                                    $found_priority = array();

                                    foreach ($found_rates as $found_rate) {
                                        if (in_array($found_rate->tax_rate_priority, $found_priority)) {
                                            continue;
                                        }

                                        $matched_tax_rates[$found_rate->tax_rate_id] = array(
                                            'rate' => $found_rate->tax_rate,
                                            'label' => $found_rate->tax_rate_name,
                                            'shipping' => $found_rate->tax_rate_shipping ? 'yes' : 'no',
                                            'compound' => $found_rate->tax_rate_compound ? 'yes' : 'no'
                                        );

                                        $found_priority[] = $found_rate->tax_rate_priority;
                                    }
                                    $line_tax = array_sum(\WC_Tax::calc_tax($item_subtotal, $matched_tax_rates, TRUE));
                                    $item_subtotal_tax += $line_tax;
                                }

                                break;
                        }

                        if (!empty($this->tax_rates)) {
                            foreach ($this->tax_rates as $rate_id => $rate) {
                                $line_taxes[$rate->tax_rate_id] = $line_tax;
                                break;
                            }
                        }
                    }

                    $variation = array();

                    $product_item = new \PMXI_Post_Record();
                    $product_item->getBy(array(
                        'import_id' => $this->getImport()->id,
                        'post_id' => $this->getOrderID(),
                        'unique_key' => 'manual-line-item-' . $productIndex . '-' . $productItem['sku']
                    ));

                    if ($product_item->isEmpty()) {
                        $item_id = wc_add_order_item($this->getOrderID(), array(
                            'order_item_name' => $productItem['sku'],
                            'order_item_type' => 'line_item'
                        ));

                        if (!$item_id) {
                            $this->getLogger() and call_user_func($this->getLogger(), __('- <b>WARNING</b> Unable to create order line product.', \PMWI_Plugin::TEXT_DOMAIN));
                        }
                        else {
                            wc_add_order_item_meta($item_id, '_qty', wc_stock_amount($item_qty));
                            wc_add_order_item_meta($item_id, '_tax_class', '');

                            wc_add_order_item_meta($item_id, '_line_subtotal', wc_format_decimal($item_subtotal));
                            wc_add_order_item_meta($item_id, '_line_total', wc_format_decimal($item_subtotal));
                            wc_add_order_item_meta($item_id, '_line_subtotal_tax', wc_format_decimal($item_subtotal_tax));
                            wc_add_order_item_meta($item_id, '_line_tax', wc_format_decimal($item_subtotal_tax));
                            wc_add_order_item_meta($item_id, '_line_tax_data', array(
                                'total' => $line_taxes,
                                'subtotal' => array()
                            ));

                            if (!empty($productItem['meta_name'])) {
                                foreach ($productItem['meta_name'] as $key => $meta_name) {
                                    wc_add_order_item_meta($item_id, $meta_name, isset($productItem['meta_value'][$key]) ? $productItem['meta_value'][$key] : '');
                                }
                            }

                            $product_item->set(array(
                                'import_id' => $this->getImport()->id,
                                'post_id' => $this->getOrderID(),
                                'unique_key' => 'manual-line-item-' . $productIndex . '-' . $productItem['sku'],
                                'product_key' => 'manual-line-item-' . $item_id,
                                'iteration' => $this->getImport()->iteration
                            ))->save();
                        }
                    }
                    else {
                        $item_id = str_replace('manual-line-item-', '', $product_item->product_key);

                        if (is_numeric($item_id)) {
                            wc_update_order_item($item_id, array(
                                'order_item_name' => $productItem['sku'],
                                'order_item_type' => 'line_item'
                            ));

                            wc_update_order_item_meta($item_id, '_qty', wc_stock_amount($item_qty));
                            wc_update_order_item_meta($item_id, '_tax_class', '');

                            wc_update_order_item_meta($item_id, '_line_subtotal', wc_format_decimal($item_subtotal));
                            wc_update_order_item_meta($item_id, '_line_total', wc_format_decimal($item_subtotal));
                            wc_update_order_item_meta($item_id, '_line_subtotal_tax', wc_format_decimal($item_subtotal_tax));
                            wc_update_order_item_meta($item_id, '_line_tax', wc_format_decimal($item_subtotal_tax));
                            wc_update_order_item_meta($item_id, '_line_tax_data', array(
                                'total' => $line_taxes,
                                'subtotal' => array()
                            ));

                            if (!empty($productItem['meta_name'])) {
                                foreach ($productItem['meta_name'] as $key => $meta_name) {
                                    wc_update_order_item_meta($item_id, $meta_name, isset($productItem['meta_value'][$key]) ? $productItem['meta_value'][$key] : '');
                                }
                            }

                            $product_item->set(array(
                                'iteration' => $this->getImport()->iteration
                            ))->save();
                        }
                    }
                }
                break;
        }
        return $is_product_founded;
    }
}