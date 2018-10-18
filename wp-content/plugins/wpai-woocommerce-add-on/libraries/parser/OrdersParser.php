<?php

namespace wpai_woocommerce_add_on\libraries\parser;

require_once dirname(__FILE__) . '/Parser.php';

/**
 * Class OrdersParser
 * @package wpai_woocommerce_add_on\libraries\parser
 */
class OrdersParser extends Parser {

    /**
     * Get complete XPath expression for parser factory.
     *
     * @return string
     */
    public function getCompleteXPath() {
        return $this->getXpath() . $this->getImport()->xpath;
    }

    /**
     * @param $option
     * @param $index
     * @return mixed
     */
    public function getValue($option, $index) {
        return $this->data['pmwi_order'][$option][$index];
    }

    /**
     *
     * Parse WooCommerce Order Import Template
     *
     * @return array
     * @throws \XmlImportException
     */
    public function parse() {

        $this->data = array();

        $this->getChunk() == 1 and $this->log(__('Composing shop order data...', \PMWI_Plugin::TEXT_DOMAIN));

        $default = \PMWI_Plugin::get_default_import_options();

        foreach ($default['pmwi_order'] as $option => $default_value) {
            if (in_array($option, array(
                    'status_xpath',
                    'payment_method_xpath',
                    'order_note_visibility_xpath',
                    'billing_source',
                    'billing_source_match_by',
                    'shipping_source',
                    'products_source',
                    'order_taxes_logic',
                    'order_refund_issued_source',
                    'order_refund_issued_match_by',
                    'order_total_logic',
                    'order_note_separate_logic',
                    'order_note_separator',
                    'is_guest_matching',
                    'copy_from_billing'
                )) or strpos($option, 'is_update_') !== FALSE or strpos($option, '_repeater_mode') !== FALSE
            ) {
                continue;
            }

            switch ($option) {
                case 'date':
                case 'order_refund_date':

                    if (!empty($this->getImport()->options['pmwi_order'][$option])) {
                        $dates = \XmlImportParser::factory($this->getXml(), $this->getCompleteXPath(), $this->getImport()->options['pmwi_order'][$option], $file)
                            ->parse();
                        $this->tmp_files[] = $file;

                        $warned = array(); // used to prevent the same notice displaying several times
                        foreach ($dates as $i => $d) {
                            if ($d == 'now') {
                                $d = current_time('mysql');
                            } // Replace 'now' with the WordPress local time to account for timezone offsets (WordPress references its local time during publishing rather than the server’s time so it should use that)
                            $time = strtotime($d);
                            if (FALSE === $time) {
                                $time = time();
                            }
                            $this->data['pmwi_order'][$option][$i] = date('Y-m-d H:i:s', $time);
                        }
                    }
                    else {
                        $this->getCount() and $this->data['pmwi_order'][$option] = array_fill(0, $this->getCount(), date('Y-m-d H:i:s'));
                    }

                    break;

                case 'status':
                case 'payment_method':
                case 'order_note_visibility':

                    if ($this->getImport()->options['pmwi_order'][$option] == 'xpath' && $this->getImport()->options['pmwi_order'][$option . '_xpath'] != "") {
                        $this->data['pmwi_order'][$option] = \XmlImportParser::factory($this->getXml(), $this->getCompleteXPath(), $this->getImport()->options['pmwi_order'][$option . '_xpath'], $file)
                            ->parse();
                        $this->tmp_files[] = $file;
                    }
                    else {
                        $this->getCount() and $this->data['pmwi_order'][$option] = array_fill(0, $this->getCount(), $this->getImport()->options['pmwi_order'][$option]);
                    }

                    break;

                case 'products':
                case 'manual_products':

                    $this->data['pmwi_order'][$option] = array();

                    switch ($this->getImport()->options['pmwi_order']['products_repeater_mode']) {
                        case 'xml':

                            foreach ($this->getImport()->options['pmwi_order'][$option] as $key => $row) {
                                for ($k = 0; $k < $this->getCount(); $k++) {
                                    $base_xpath = '[' . ($k + 1) . ']/' . ltrim(trim($this->getImport()->options['pmwi_order']['products_repeater_mode_foreach'], '{}!'), '/');

                                    $rows = \XmlImportParser::factory($this->getXml(), $this->getCompleteXPath() . $base_xpath, "{.}", $file)
                                        ->parse();
                                    $this->tmp_files[] = $file;

                                    $row_data = $this->parse_item_row($row, $this->getCompleteXPath() . $base_xpath, count($rows));

                                    $products = array();

                                    if (!empty($row_data)) {
                                        for ($j = 0; $j < count($rows); $j++) {
                                            $products[] = array(
                                                'sku' => $row_data['sku'][$j],
                                                'qty' => $row_data['qty'][$j],
                                                'price_per_unit' => isset($row_data['price_per_unit'][$j]) ? $row_data['price_per_unit'][$j] : 0,
                                                'tax_rates' => array()
                                            );

                                            if (!empty($row_data['tax_rates'])) {
                                                foreach ($row_data['tax_rates'] as $tax_rate) {
                                                    $products[$j]['tax_rates'][] = array(
                                                        'code' => $tax_rate['code'][$j],
                                                        'calculate_logic' => $tax_rate['calculate_logic'][$j],
                                                        'percentage_value' => $tax_rate['percentage_value'][$j],
                                                        'amount_per_unit' => $tax_rate['amount_per_unit'][$j]
                                                    );
                                                }
                                            }

                                            if (!empty($row_data['meta_name'])) {
                                                foreach ($row_data['meta_name'] as $meta_name) {
                                                    if (!empty($meta_name[$k])){
                                                        $products[$j]['meta_name'][] = $meta_name[$k];
                                                        $products[$j]['meta_value'][] = isset($row_data['meta_value'][$k]) ? $row_data['meta_value'][$k] : '';
                                                    }
                                                }
                                            }
                                        }
                                    }

                                    $this->data['pmwi_order'][$option][] = $products;
                                }

                                break;
                            }

                            break;

                        case 'csv':

                            foreach ($this->getImport()->options['pmwi_order'][$option] as $key => $row) {
                                if (empty($this->getImport()->options['pmwi_order']['products_repeater_mode_separator'])) {
                                    break;
                                }

                                $row_data = $this->parse_item_row($row, $this->getCompleteXPath(), $this->getCount());
                                for ($k = 0; $k < $this->getCount(); $k++) {
                                    $products = array();

                                    $skus = explode($this->getImport()->options['pmwi_order']['products_repeater_mode_separator'], $row_data['sku'][$k]);
                                    $qtys = explode($this->getImport()->options['pmwi_order']['products_repeater_mode_separator'], $row_data['qty'][$k]);
                                    $prices = isset($row_data['price_per_unit'][$k]) ? explode($this->getImport()->options['pmwi_order']['products_repeater_mode_separator'], $row_data['price_per_unit'][$k]) : array();

                                    if (!empty($skus)) {
                                        for ($j = 0; $j < count($skus); $j++) {
                                            $products[] = array(
                                                'sku' => $skus[$j],
                                                'qty' => $qtys[$j],
                                                'price_per_unit' => isset($prices[$j]) ? $prices[$j] : 0,
                                                'tax_rates' => array()
                                            );

                                            if (!empty($row_data['tax_rates'])) {
                                                foreach ($row_data['tax_rates'] as $tax_rate) {
                                                    $products[$j]['tax_rates'][] = array(
                                                        'code' => $tax_rate['code'][$k],
                                                        'calculate_logic' => $tax_rate['calculate_logic'][$k],
                                                        'percentage_value' => $tax_rate['percentage_value'][$k],
                                                        'amount_per_unit' => $tax_rate['amount_per_unit'][$k]
                                                    );
                                                }
                                            }

                                            if (!empty($row_data['meta_name'])) {
                                                foreach ($row_data['meta_name'] as $meta_name) {
                                                    $products[$j]['meta_name'][] = $meta_name[$k];
                                                }
                                            }

                                            if (!empty($row_data['meta_value'])) {
                                                foreach ($row_data['meta_value'] as $meta_value) {
                                                    $products[$j]['meta_value'][] = $meta_value[$k];
                                                }
                                            }
                                        }
                                    }
                                    $this->data['pmwi_order'][$option][] = $products;
                                }

                                break;
                            }

                            break;

                        default:

                            $row_data = array();

                            foreach ($this->getImport()->options['pmwi_order'][$option] as $key => $row) {
                                $row_data[] = $this->parse_item_row($row, $this->getCompleteXPath(), $this->getCount());
                            }

                            for ($j = 0; $j < $this->getCount(); $j++) {
                                $products = array();

                                foreach ($row_data as $k => $product) {
                                    $products[] = array(
                                        'sku' => $product['sku'][$j],
                                        'qty' => $product['qty'][$j],
                                        'price_per_unit' => isset($product['price_per_unit'][$j]) ? $product['price_per_unit'][$j] : 0,
                                        'tax_rates' => array()
                                    );

                                    if (!empty($product['tax_rates'])) {
                                        foreach ($product['tax_rates'] as $tax_rate) {
                                            $products[$k]['tax_rates'][] = array(
                                                'code' => $tax_rate['code'][$j],
                                                'calculate_logic' => $tax_rate['calculate_logic'][$j],
                                                'percentage_value' => $tax_rate['percentage_value'][$j],
                                                'amount_per_unit' => $tax_rate['amount_per_unit'][$j]
                                            );
                                        }
                                    }

                                    if (!empty($product['meta_name'])) {
                                        foreach ($product['meta_name'] as $meta_name) {
                                            $products[$k]['meta_name'][] = $meta_name[$k];
                                        }
                                    }

                                    if (!empty($product['meta_value'])) {
                                        foreach ($product['meta_value'] as $meta_value) {
                                            $products[$k]['meta_value'][] = $meta_value[$k];
                                        }
                                    }
                                }
                                $this->data['pmwi_order'][$option][] = $products;
                            }

                            break;
                    }

                    break;

                case 'fees':
                case 'coupons':
                case 'shipping':
                case 'taxes':
                case 'notes':

                    $this->data['pmwi_order'][$option] = array();

                    switch ($this->getImport()->options['pmwi_order'][$option . '_repeater_mode']) {
                        case 'xml':

                            foreach ($this->getImport()->options['pmwi_order'][$option] as $key => $row) {
                                for ($k = 0; $k < $this->getCount(); $k++) {
                                    $base_xpath = '[' . ($k + 1) . ']/' . ltrim(trim($this->getImport()->options['pmwi_order'][$option . '_repeater_mode_foreach'], '{}!'), '/');

                                    $rows = \XmlImportParser::factory($this->getXml(), $this->getCompleteXPath() . $base_xpath, "{.}", $file)
                                        ->parse();
                                    $this->tmp_files[] = $file;

                                    $row_data = $this->parse_item_row($row, $this->getCompleteXPath() . $base_xpath, count($rows));

                                    $items = array();

                                    if (!empty($row_data)) {
                                        for ($j = 0; $j < count($rows); $j++) {
                                            foreach ($row_data as $itemkey => $values) {
                                                $items[$j][$itemkey] = isset($values[$j]) ? $values[$j] : '';
                                            }
                                        }
                                    }

                                    $this->data['pmwi_order'][$option][] = $items;
                                }

                                break;
                            }

                            break;

                        case 'csv':

                            $separator = $this->getImport()->options['pmwi_order'][$option . '_repeater_mode_separator'];

                            foreach ($this->getImport()->options['pmwi_order'][$option] as $key => $row) {
                                if (empty($separator)) {
                                    break;
                                }

                                $row_data = $this->parse_item_row($row, $this->getCompleteXPath(), $this->getCount(), $separator);

                                for ($k = 0; $k < $this->getCount(); $k++) {
                                    $items = array();

                                    $maxCountRows = 0;

                                    foreach ($row_data as $itemkey => $values) {
                                        $itemIndex = 0;

                                        $rows = explode($separator, $values[$k]);

                                        if (!empty($rows)) {
                                            if (count($rows) > $maxCountRows) {
                                                $maxCountRows = count($rows);
                                            }

                                            if (count($rows) == 1) {
                                                for ($j = 0; $j < $maxCountRows; $j++) {
                                                    $items[$itemIndex][$itemkey] = trim($rows[0]);
                                                    $itemIndex++;
                                                }
                                            }
                                            else {
                                                foreach ($rows as $val) {
                                                    $items[$itemIndex][$itemkey] = trim($val);
                                                    $itemIndex++;
                                                }
                                            }
                                        }
                                    }

                                    $this->data['pmwi_order'][$option][] = $items;
                                }

                                break;
                            }

                            break;

                        default:

                            $row_data = array();

                            foreach ($this->getCount()->options['pmwi_order'][$option] as $key => $row) {
                                $row_data[] = $this->parse_item_row($row, $this->getCompleteXPath(), $this->getCount());
                            }

                            for ($j = 0; $j < $this->getCount(); $j++) {
                                $items = array();

                                $itemIndex = 0;

                                foreach ($row_data as $k => $item) {
                                    foreach ($item as $itemkey => $values) {
                                        $items[$itemIndex][$itemkey] = $values[$j];
                                    }
                                    $itemIndex++;
                                }

                                $this->data['pmwi_order'][$option][] = $items;
                            }

                            break;
                    }

                    break;

                default:

                    if (!empty($this->getImport()->options['pmwi_order'][$option])) {
                        $this->data['pmwi_order'][$option] = \XmlImportParser::factory($this->getXml(), $this->getCompleteXPath(), $this->getImport()->options['pmwi_order'][$option], $file)
                            ->parse();
                        $this->tmp_files[] = $file;
                    }
                    else {
                        $this->getCount() and $this->data['pmwi_order'][$option] = array_fill(0, $this->getCount(), $default_value);
                    }

                    break;
            }
        }

        // Remove all temporary files created.
        $this->unlinkTempFiles();

        return $this->data;

    }

    /**
     *
     * Helper method to parse repeated options
     *
     * @param $row
     * @param $cxpath
     * @param $count
     *
     * @param bool $separator
     *
     * @return array
     * @throws \XmlImportException
     */
    protected function parse_item_row($row, $cxpath, $count, $separator = FALSE) {

        $row_data = array();

        foreach ($row as $opt => $value) {
            switch ($opt) {
                case 'class_xpath':
                case 'tax_code_xpath':
                case 'visibility_xpath':
                    // skipp this field(s)
                    break;

                case 'tax_rates':

                    foreach ($value as $i => $tax_rate_row) {
                        $tax_rate_data = array();

                        foreach ($tax_rate_row as $tax_rate_row_opt => $tax_rate_row_value) {
                            if (!empty($tax_rate_row_value)) {
                                $tax_rate_data[$tax_rate_row_opt] = \XmlImportParser::factory($this->getXml(), $cxpath, $tax_rate_row_value, $file)
                                    ->parse();
                                $this->tmp_files[] = $file;
                            }
                            else {
                                $count and $tax_rate_data[$tax_rate_row_opt] = array_fill(0, $count, $tax_rate_row_value);
                            }
                        }
                        $row_data[$opt][] = $tax_rate_data;
                    }

                    break;

                case 'meta_name':
                case 'meta_value':

                    foreach ($value as $meta) {
                        if (!empty($meta)) {
                            $row_data[$opt][] = \XmlImportParser::factory($this->getXml(), $cxpath, $meta, $file)
                                ->parse();
                            $this->tmp_files[] = $file;
                        }
                        else {
                            $row_data[$opt][] = array_fill(0, $count, $meta);
                        }
                    }

                    break;

                case 'class':
                case 'tax_code':
                case 'visibility':

                    if ($value == 'xpath' and $row[$opt . '_xpath'] != '') {
                        $row_data[$opt] = \XmlImportParser::factory($this->getXml(), $cxpath, $row[$opt . '_xpath'], $file)
                            ->parse();
                        $this->tmp_files[] = $file;
                    }
                    else {
                        $count and $row_data[$opt] = array_fill(0, $count, $value);
                    }

                    break;

                case 'date':

                    if (!empty($value)) {
                        $dates = \XmlImportParser::factory($this->getXml(), $cxpath, $value, $file)
                            ->parse();
                        $this->tmp_files[] = $file;

                        foreach ($dates as $i => $d) {
                            $dates[$i] = $separator ? explode($separator, $d) : array($d);
                        }

                        $warned = array(); // used to prevent the same notice displaying several times
                        foreach ($dates as $i => $date) {
                            $times = array();
                            foreach ($date as $d) {
                                if ($d == 'now') {
                                    $d = current_time('mysql');
                                } // Replace 'now' with the WordPress local time to account for timezone offsets (WordPress references its local time during publishing rather than the server’s time so it should use that)
                                $time = strtotime($d);
                                if (FALSE === $time) {
                                    $time = time();
                                }
                                $times[] = date('Y-m-d H:i:s', $time);
                            }
                            $row_data[$opt][$i] = $separator ? implode($separator, $times) : array_shift($times);
                        }
                    }
                    else {
                        $count and $row_data[$opt] = array_fill(0, $count, date('Y-m-d H:i:s'));
                    }

                    break;

                default:

                    if (!empty($value)) {
                        $row_data[$opt] = \XmlImportParser::factory($this->getXml(), $cxpath, $value, $file)
                            ->parse();
                        $this->tmp_files[] = $file;
                    }
                    else {
                        $count and $row_data[$opt] = array_fill(0, $count, $value);
                    }

                    break;
            }
        }

        // remove all temporary files created
        $this->unlinkTempFiles();

        return $row_data;
    }

    /**
     * @param $option_slug
     * @param $index
     * @return bool|false|mixed|\WP_User
     */
    public function get_existing_customer($option_slug, $index) {

        $customer = FALSE;

        switch ($this->getImport()->options['pmwi_order'][$option_slug . '_match_by']) {
            case 'username':
                $search_by = $this->getValue($option_slug . '_username', $index);
                $customer = get_user_by('login', $search_by) or $customer = get_user_by('slug', $search_by);
                break;

            case 'email':
                $search_by = $this->getValue($option_slug . '_email', $index);
                $customer = get_user_by('email', $search_by);
                break;

            case 'cf':
                $cf_name = $this->getValue($option_slug . '_cf_name', $index);
                $cf_value = $this->getValue($option_slug . '_cf_value', $index);

                $user_query = new \WP_User_Query(array(
                    'meta_key' => $cf_name,
                    'meta_value' => $cf_value
                ));

                if (!empty($user_query->results)) {
                    $customer = array_shift($user_query->results);
                }

                break;

            case 'id':
                $search_by = $this->getValue($option_slug . '_id', $index);
                $customer = get_user_by('id', $search_by);
                break;
        }
        return $customer;
    }

    /**
     * @param $option_slug
     * @param $index
     * @return string
     */
    public function get_existing_customer_for_logger($option_slug, $index ) {
        $log = __("Search customer by ", \PMWI_Plugin::TEXT_DOMAIN);

        switch ($this->getImport()->options['pmwi_order'][$option_slug . '_match_by']){
            case 'username':
                $log .= __("username", \PMWI_Plugin::TEXT_DOMAIN) . " `" . $this->getValue($option_slug . '_username', $index) . "`";
                break;
            case 'email':
                $log .= __("email", \PMWI_Plugin::TEXT_DOMAIN) . " `" . $this->getValue($option_slug . '_email', $index) . "`";
                break;
            case 'cf':
                $log .= __("custom field", \PMWI_Plugin::TEXT_DOMAIN) . ": `" . $this->getValue($option_slug . '_cf_name', $index) . "` equals to `" . $this->getValue($option_slug . '_cf_value', $index) . "`";
                break;
            case 'id':
                $log .= __("ID", \PMWI_Plugin::TEXT_DOMAIN) . " `" . $this->getValue($option_slug . '_id', $index) . "`";
                break;
        }
        return $log . ".";
    }
}