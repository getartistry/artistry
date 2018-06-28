<?php

if (!defined('ABSPATH')) {
    exit;
}
if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class Eh_Stripe_Order_Datatables extends WP_List_Table {

    public $order_data;

    function __construct() {
        parent::__construct(array(
            'singular' => 'Order',
            'plural' => 'Orders',
            'ajax' => true
        ));
    }

    public function input() {
        $order_id = eh_stripe_overview_get_order_ids();
        $order_temp = array();
        for ($i = 0; $i < count($order_id); $i++) {
            $order = wc_get_order($order_id[$i]);
            $data = get_post_meta($order_id[$i], '_eh_stripe_payment_charge', true);
            $order_temp[$i]['order_id'] = $order_id[$i];
            $order_temp[$i]['order_status'] = $order->get_status();
            $order_temp[$i]['user_id'] = ($order->get_user_id()) ? $order->get_user_id() : 'guest';
            if ($order_temp[$i]['user_id'] === 'guest') {
                $order_temp[$i]['user_name'] = __('Guest', 'eh-stripe-gateway');
            } else {
                $order_temp[$i]['user_name'] = get_user_meta($order->get_user_id(), 'first_name', true) . ' ' . get_user_meta($order->get_user_id(), 'last_name', true);
            }
            $order_temp[$i]['user_email'] = (WC()->version < '2.7.0') ? $order->billing_email : $order->get_billing_email();
            $order_temp[$i]['ship'] = $order->get_address('shipping');
            $order_temp[$i]['order_total'] = $order->get_total();
            $order_temp[$i]['order_mode'] = (isset($data['mode']) ? $data['mode'] : '');
            $order_temp[$i]['refund_rem'] = $order->get_remaining_refund_amount();
            $order_temp[$i]['price'] = $order->get_formatted_order_total();
            $order_temp[$i]['date'] = date('Y-m-d ', (WC()->version < '2.7.0') ? strtotime($order->order_date) : strtotime($order->get_date_created()));
        }
        $this->order_data = $order_temp;
    }

    function column_cb($item) {
        return sprintf(
                '<input type="checkbox" name="%1$s[]" value="%2$s" />', $this->_args['plural'], $item['order_id']
        );
    }

    function column_order_status($item) {
        return sprintf('<mark class="' . $item['order_status'] . ' tips" title=' . ucfirst($item['order_status']) . ' >' . ucfirst($item['order_status']) . '</mark>');
    }

    function column_order($item) {
        return sprintf('<span><a href="' . get_admin_url() . 'post.php?post=' . $item['order_id'] . '&action=edit"><strong>#' . $item['order_id'] . '</strong></a> by <a href="' . get_admin_url() . 'user-edit.php?user_id=' . $item['user_id'] . '">' . $item['user_name'] . '</a><br>' . $item['user_email'] . '</span>');
    }

    function column_ship($item) {
        
       
        $shiptoaddr = (!empty($item['ship']['first_name']) || !empty($item['ship']['last_name']))? $item['ship']['first_name'] . ' ' . $item['ship']['last_name'].',' : '';
        $shiptoaddr.= (!empty($item['ship']['company']))? $item['ship']['company'].',' : '';
        $shiptoaddr.= (!empty($item['ship']['address_1']))? $item['ship']['address_1'].',' : '';
        $shiptoaddr.= (!empty($item['ship']['address_2']))? $item['ship']['address_2'].',' : '';
        $shiptoaddr.= (!empty($item['ship']['city']))? $item['ship']['city'].',' : '';
        $shiptoaddr.= (!empty($item['ship']['state']))? $item['ship']['state'].'-' : '';
        $shiptoaddr.= (!empty($item['ship']['postcode']))? $item['ship']['postcode'].',' : '';
        $shiptoaddr.= (!empty($item['ship']['country']))? $item['ship']['country'].',' : '';
        return sprintf('<span>' .$shiptoaddr. '</span>');
    }

    function column_price($item) {
        return sprintf('<span>' . $item['price'] . '</span>');
    }

    function column_p_actions($item) {
        $actions = '';
        if (in_array($item['order_status'], array('pending', 'on-hold', 'processing', 'completed', 'cancelled'))) {
            $id = $item['order_id'];
            $data = get_post_meta($id, '_eh_stripe_payment_charge', true);
            if ($data !== '') {
                if ('succeeded' === $data['status']) {
                    switch ($data['captured']) {
                        case 'Captured':
                            $actions = '<span style="width:69%%;text-align: center;" class="button payment_refund_button payment_act" id=' . $id . '>' . __('Refund', 'eh-stripe-gateway') . get_woocommerce_currency_symbol() . '<span class="amount_refund_main_' . $id . '" > ' . $item['refund_rem'] . '</span><span class="amount_refund_place_' . $id . '" hidden> ' . $item['refund_rem'] . '</span><span id="' . $id . '_loader"></span></span><input type="number" id=' . $id . ' class="payment_refund_text_' . $id . '" style="float:left; width:89%%; margin-top:3px; margin-left:3px" placeholder="Amount" hidden>'
                                    . '<input type="checkbox" style="margin-left:3px;" checked class="' . $id . '" id="payment_refund_check" value="refund">' . __('Full', 'eh-stripe-gateway');
                            break;
                        case 'Uncaptured':
                            $actions = '<center<span style="width:100%%;text-align: center;" class="button payment_capture_button payment_act" id=' . $id . '>' . __('Capture', 'eh-stripe-gateway') . get_woocommerce_currency_symbol() . '<span class="amount_capture_main_' . $id . '">' . number_format((float) $item['order_total'], 2, '.', '') . ' </span>';
                            break;
                    }
                }
            }
        }
        return sprintf($actions);
    }

    function column_order_actions($item) {
        $actions = '';
        switch ($item['order_status']) {
            case 'pending':
            case 'on-hold':
                $actions = '<p><span style="width:45%%" class="button processing order_act processing_button" id="' . $item['order_id'] . '" title="Processing">' . __('Processing', 'eh-stripe-gateway') . '</span><span style="width:45%%" class="button complete order_act complete_button" id="' . $item['order_id'] . '" title="completed">' . __('Completed', 'eh-stripe-gateway') . '</span></p>';
                break;
            case 'processing':
                $actions = '<span style="width:98%%" class="button complete_button complete order_act" id="' . $item['order_id'] . '" title="completed">' . __('Completed', 'eh-stripe-gateway') . '</span>';
                break;
            default :
                $actions = '<span></span>';
        }
        return sprintf($actions);
    }

    function column_date($item) {
        $actions = '';
        if ($item['order_mode'] === 'Test') {
            $actions = '<br><strong style="color:orangered">' . __('TEST MODE', 'eh-stripe-gateway') . '</strong>';
        }
        return sprintf('<span>' . $item['date'] . '</span>' . $actions);
    }

    function get_columns() {

        return $columns = array(
            'cb' => '<input type="checkbox" />',
            'order_status' => '<span class="status_head tips">' . __('Status', 'eh-stripe-gateway') . '</span>',
            'order' => __('Order', 'eh-stripe-gateway'),
            'ship' => __('Ship to', 'eh-stripe-gateway'),
            'price' => __('Price', 'eh-stripe-gateway'),
            'p_actions' => __('Payment Action', 'eh-stripe-gateway'),
            'order_actions' => __('Actions', 'eh-stripe-gateway'),
            'date' => __('Date', 'eh-stripe-gateway')
        );
    }

    function get_sortable_columns() {
        $sortable_columns = array();
        return $sortable_columns;
    }

    function get_bulk_actions() {
        $actions = array(
            'processing' => __('Mark Processing', 'eh-stripe-gateway'),
            'on-hold' => __('Mark On-Hold', 'eh-stripe-gateway'),
            'completed' => __('Mark Completed', 'eh-stripe-gateway')
        );
        return $actions;
    }

    function prepare_items($page_num = '', $prepare = '') {
        $per_page = (get_option('eh_order_table_row')) ? get_option('eh_order_table_row') : 20;
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array(
            $columns,
            $hidden,
            $sortable
        );
        $data = $this->order_data;
        $current_page = ($page_num == '') ? $this->get_pagenum() : $page_num;
        $total_items = count($data);
        $data = array_slice($data, (($current_page - 1) * $per_page), $per_page);
        $this->items = $data;
        $this->set_pagination_args(array(
            'total_items' => $total_items,
            'per_page' => $per_page,
            'total_pages' => ceil($total_items / $per_page)
        ));
    }

    function display() {
        parent::display();
    }

    function ajax_response($page_num = '') {

        $this->prepare_items($page_num);

        extract($this->_args);
        extract($this->_pagination_args, EXTR_SKIP);

        ob_start();
        if (!empty($_REQUEST['no_placeholder'])) {
            $this->display_rows();
        } else {
            $this->display_rows_or_placeholder();
        }
        $rows = ob_get_clean();

        ob_start();
        $this->print_column_headers();
        $headers = ob_get_clean();

        ob_start();
        $this->pagination('top');
        $pagination_top = ob_get_clean();

        ob_start();
        $this->pagination('bottom');
        $pagination_bottom = ob_get_clean();

        $response = array(
            'rows' => $rows
        );
        $response['pagination']['top'] = $pagination_top;
        $response['pagination']['bottom'] = $pagination_bottom;
        $response['column_headers'] = $headers;

        if (isset($total_items)) {
            $response['total_items_i18n'] = sprintf(_n('1 item', '%s items', $total_items), number_format_i18n($total_items));
        }

        if (isset($total_pages)) {
            $response['total_pages'] = $total_pages;
            $response['total_pages_i18n'] = number_format_i18n($total_pages);
        }

        die(json_encode($response));
    }

}

class Eh_Stripe_Datatables extends WP_List_Table {

    public $stripe_data;

    function __construct() {
        parent::__construct(array(
            'singular' => 'Stripe',
            'plural' => 'Stripe',
            'ajax' => true
        ));
    }

    public function input() {
        $order_id = eh_stripe_overview_get_order_ids();
        $stripe_temp = array();
        for ($i = 0, $j = 0; $i < count($order_id); $i++) {
            $charge_count = count(get_post_meta($order_id[$i], '_eh_stripe_payment_charge'));
            $refund_count = count(get_post_meta($order_id[$i], '_eh_stripe_payment_refund'));
            $balance_count = count(get_post_meta($order_id[$i], '_eh_stripe_payment_balance'));
            for ($k = 0; $k < $charge_count; $k++) {
                $data = get_post_meta($order_id[$i], '_eh_stripe_payment_charge');
                $order = wc_get_order($order_id[$i]);
                $stripe_temp[$j]['order_id'] = $order_id[$i];
                $stripe_temp[$j]['stripe_way'] = __('Charge', 'eh-stripe-gateway');
                $stripe_temp[$j]['order_status'] = $order->get_status();
                $stripe_temp[$j]['user_id'] = ($order->get_user_id()) ? $order->get_user_id() : 'guest';
                if ($stripe_temp[$j]['user_id'] === 'guest') {
                    $stripe_temp[$j]['user_name'] = __('Guest', 'eh-stripe-gateway');
                } else {
                    $stripe_temp[$j]['user_name'] = get_user_meta($order->get_user_id(), 'first_name', true) . ' ' . get_user_meta($order->get_user_id(), 'last_name', true);
                }
                $stripe_temp[$j]['user_email'] = (WC()->version < '2.7.0') ? $order->billing_email : $order->get_billing_email();
                $stripe_temp[$j]['type'] = $data[$k]['captured'];
                $stripe_temp[$j]['source'] = $data[$k]['source_type'];
                $stripe_temp[$j]['transaction_id'] = $data[$k]['transaction_id'];
                $stripe_temp[$j]['status'] = $data[$k]['status'];
                $stripe_temp[$j]['amount'] = $data[$k]['amount'];
                $stripe_temp[$j]['amount_refunded'] = $data[$k]['amount_refunded'];
                $stripe_temp[$j]['currency'] = $data[$k]['currency'];
                $stripe_temp[$j]['created'] = $data[$k]['origin_time'];
                $j++;
            }
            for ($k = 0; $k < $refund_count; $k++) {
                $data = get_post_meta($order_id[$i], '_eh_stripe_payment_refund');
                $order = wc_get_order($order_id[$i]);
                $stripe_temp[$j]['order_id'] = $order_id[$i];
                $stripe_temp[$j]['stripe_way'] = __('Refund', 'eh-stripe-gateway');
                $stripe_temp[$j]['order_status'] = $order->get_status();
                $stripe_temp[$j]['order_total'] = $order->get_total();
                $stripe_temp[$j]['user_id'] = ($order->get_user_id()) ? $order->get_user_id() : 'guest';
                if ($stripe_temp[$j]['user_id'] === 'guest') {
                    $stripe_temp[$j]['user_name'] = __('Guest', 'eh-stripe-gateway');
                } else {
                    $stripe_temp[$j]['user_name'] = get_user_meta($order->get_user_id(), 'first_name', true) . ' ' . get_user_meta($order->get_user_id(), 'last_name', true);
                }
                $stripe_temp[$j]['user_email'] = (WC()->version < '2.7.0') ? $order->billing_email : $order->get_billing_email();
                $stripe_temp[$j]['transaction_id'] = $data[$k]['transaction_id'];
                $stripe_temp[$j]['status'] = $data[$k]['status'];
                $stripe_temp[$j]['amount'] = $data[$k]['amount'];
                $stripe_temp[$j]['currency'] = $data[$k]['currency'];
                $stripe_temp[$j]['created'] = $data[$k]['origin_time'];
                $j++;
            }
            for ($k = 0; $k < $balance_count; $k++) {
                $data = get_post_meta($order_id[$i], '_eh_stripe_payment_balance');
                $order = wc_get_order($order_id[$i]);
                $stripe_temp[$j]['order_id'] = $order_id[$i];
                $stripe_temp[$j]['stripe_way'] = __('Balance', 'eh-stripe-gateway');
                $stripe_temp[$j]['order_status'] = $order->get_status();
                $stripe_temp[$j]['order_total'] = $order->get_total();
                $stripe_temp[$j]['user_id'] = ($order->get_user_id()) ? $order->get_user_id() : 'guest';
                if ($stripe_temp[$j]['user_id'] === 'guest') {
                    $stripe_temp[$j]['user_name'] = __('Guest', 'eh-stripe-gateway');
                } else {
                    $stripe_temp[$j]['user_name'] = get_user_meta($order->get_user_id(), 'first_name', true) . ' ' . get_user_meta($order->get_user_id(), 'last_name', true);
                }
                $stripe_temp[$j]['user_email'] = (WC()->version < '2.7.0') ? $order->billing_email : $order->get_billing_email();
                $stripe_temp[$j]['transaction_id'] = __('Balance Transaction', 'eh-stripe-gateway');
                $stripe_temp[$j]['status'] = 'succeeded';
                $stripe_temp[$j]['amount'] = $data[$k]['balance_amount'];
                $stripe_temp[$j]['currency'] = $data[$k]['currency'];
                $stripe_temp[$j]['created'] = $data[$k]['origin_time'];
                $j++;
            }
        }
        $this->stripe_data = $stripe_temp;
    }

    function column_order_status($item) {
        return sprintf('<mark class="' . $item['order_status'] . ' tips" title=' . ucfirst($item['order_status']) . ' >' . ucfirst($item['order_status']) . '</mark>');
    }

    function column_order($item) {
        return sprintf('<span><a href="' . get_admin_url() . 'post.php?post=' . $item['order_id'] . '&action=edit"><strong>#' . $item['order_id'] . '</strong></a> by <a href="' . get_admin_url() . 'user-edit.php?user_id=' . $item['user_id'] . '">' . $item['user_name'] . '</a><br>' . $item['user_email'] . '</span>');
    }

    function column_id($item) {
        $action = '';
        if ($item['stripe_way'] === 'Balance' && floatval($item['amount']) != 0) {
            $action = '<br><span style="width:69%%;text-align: center;" class="button stripe_refund_button" id=' . $item['order_id'] . '>' . __('Refund', 'eh-stripe-gateway') . get_woocommerce_currency_symbol(strtoupper($item['currency'])) . ' ' . $item['amount'] . ' ' . strtoupper($item['currency']) . '</span>';
        }
        return sprintf('<span>' . (is_null($item['transaction_id']) ? '-' : $item['transaction_id']) . '</span>' . $action);
    }

    function column_status($item) {
        switch ($item['stripe_way']) {
            case 'Charge':
                if ($item['type'] === 'Captured') {
                    $actions = '<span class="table-type-text" style="color:#7ad03a !important">' . __('Payment Complete', 'eh-stripe-gateway') . '</span>';
                } else {
                    $actions = '<span class="table-type-text" style="color:#39beef !important">' . __('Capture Pending', 'eh-stripe-gateway') . '</span>';
                }
                break;
            case 'Refund':
                
                $order_id = $item['order_id'];
                $ord = new WC_Order($order_id);
                $total_refund = $ord->get_total_refunded();
                
                
                if ($item['amount'] === floatval($item['order_total']) || $total_refund == floatval($item['order_total'])) {
                    $actions = '<span class="table-type-text" style="color:#39beef !important">' . __('Fully Refunded', 'eh-stripe-gateway') . '</span>';
                } else {
                    $actions = '<span class="table-type-text">' . __('Partially Refunded', 'eh-stripe-gateway') . '</span>';
                }
                break;
            case 'Balance':
                $actions = '<span class="table-type-text" style="color:#7ad03a !important">' . __('Transaction Successful', 'eh-stripe-gateway') . '</span>';
                break;
        }
        return sprintf($actions);
    }

    function column_amount($item) {
        switch ($item['stripe_way']) {
            case 'Charge':
                $actions = '<span class="table-type-text">' . __('Amount', 'eh-stripe-gateway') . '</span><br> ' . get_woocommerce_currency_symbol(strtoupper($item['currency'])) . ' ' . $item['amount'] . ' ' . strtoupper($item['currency']) . (($item['amount_refunded'] != 0) ? '<br><span class="table-type-text">' . __('Refunded : ', 'eh-stripe-gateway') . '</span> ' . get_woocommerce_currency_symbol(strtoupper($item['currency'])) . ' ' . $item['amount_refunded'] . ' ' . strtoupper($item['currency']) : '');
                break;
            case 'Refund':
                $actions = '<span class="table-type-text">' . __('Refund', 'eh-stripe-gateway') . '</span><br>' . get_woocommerce_currency_symbol(strtoupper($item['currency'])) . ' ' . $item['amount'] . ' ' . strtoupper($item['currency']);
                break;
            case 'Balance':
                $actions = '<span class="table-type-text">' . __('Balance', 'eh-stripe-gateway') . '</span><br>' . get_woocommerce_currency_symbol(strtoupper($item['currency'])) . ' ' . $item['amount'] . ' ' . strtoupper($item['currency']);
                break;
        }
        return sprintf($actions);
    }

    function column_date($item) {
        return sprintf('<span>' . $item['created'] . '</span>');
    }

    function column_thumb($item) {
        $ext = version_compare(WC()->version, '2.6', '>=') ? '.svg' : '.png';
        $style = version_compare(WC()->version, '2.6', '>=') ? 'style="margin-left: 0.3em"' : '';
        $icon = '';
        if ($item['stripe_way'] === 'Charge') {
            if (strpos($item['source'], 'Visa') !== false) {
                $icon = '<img src="' . WC_HTTPS::force_https_url(WC()->plugin_url() . '/assets/images/icons/credit-cards/visa' . $ext) . '" alt="Visa" width="32" title="VISA" ' . $style . ' />';
            }
            if (strpos($item['source'], 'MasterCard') !== false) {
                $icon = '<img src="' . WC_HTTPS::force_https_url(WC()->plugin_url() . '/assets/images/icons/credit-cards/mastercard' . $ext) . '" alt="Mastercard" width="32" title="Master Card" ' . $style . ' />';
            }
            if (strpos($item['source'], 'American Express') !== false) {
                $icon = '<img src="' . WC_HTTPS::force_https_url(WC()->plugin_url() . '/assets/images/icons/credit-cards/amex' . $ext) . '" alt="Amex" width="32" title="American Express" ' . $style . ' />';
            }
            if (strpos($item['source'], 'Discover') !== false) {
                $icon = '<img src="' . WC_HTTPS::force_https_url(WC()->plugin_url() . '/assets/images/icons/credit-cards/discover' . $ext) . '" alt="Discover" width="32" title="Discover" ' . $style . ' />';
            }
            if (strpos($item['source'], 'JCB') !== false) {
                $icon = '<img src="' . WC_HTTPS::force_https_url(WC()->plugin_url() . '/assets/images/icons/credit-cards/jcb' . $ext) . '" alt="JCB" width="32" title="JCB" ' . $style . ' />';
            }
            if (strpos($item['source'], 'Diners Club') !== false) {
                $icon = '<img src="' . WC_HTTPS::force_https_url(WC()->plugin_url() . '/assets/images/icons/credit-cards/diners' . $ext) . '" alt="Diners" width="32" title="Diners Club" ' . $style . ' />';
            }

//            if (strpos($item['source'], 'Bitcoin') !== false) { // temperary disabled 2018-05-29 Stripe withdrew support for Bitcoin  search 'bitcoin' and comment all bitcoin related code
//                $icon = '<img src="' . WC_HTTPS::force_https_url(EH_STRIPE_MAIN_URL_PATH . 'assets/img/bitcoin.png') . '" alt="Bitcoin" width="52" title="Bitcoin" ' . $style . ' />';
//            }
            if (strpos($item['source'], 'Alipay') !== false) {
                $icon = '<img src="' . WC_HTTPS::force_https_url(EH_STRIPE_MAIN_URL_PATH . 'assets/img/alipay.png') . '" alt="Alipay" width="52" title="Alipay" ' . $style . ' />';
            }
        }
        return sprintf($icon);
    }

    function get_columns() {

        return $columns = array(
            'thumb' => '<span class="wc-image">Image</span>',
            'order_status' => '<span class="status_head tips">' . __('Status', 'eh-stripe-gateway') . '</span>',
            'order' => __('Order', 'eh-stripe-gateway'),
            'id' => __('Transaction ID', 'eh-stripe-gateway'),
            'status' => __('Status', 'eh-stripe-gateway'),
            'amount' => __('Amount', 'eh-stripe-gateway'),
            'date' => __('Date', 'eh-stripe-gateway')
        );
    }

    function get_sortable_columns() {
        $sortable_columns = array(
        );
        return $sortable_columns;
    }

    function date_compare($a, $b) {
        $t1 = strtotime($a['created']);
        $t2 = strtotime($b['created']);
        return $t1 < $t2 ? 1 : -1;
    }

    function prepare_items($page_num = '', $prepare = '', $page_count = '') {
        $per_page = (get_option('eh_stripe_table_row')) ? get_option('eh_stripe_table_row') : 20;
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array(
            $columns,
            $hidden,
            $sortable
        );
        $data = $this->stripe_data;
        usort($data, array($this, 'date_compare'));
        $current_page = ($page_num == '') ? $this->get_pagenum() : $page_num;
        $total_items = count($data);
        $data = array_slice($data, (($current_page - 1) * $per_page), $per_page);
        $this->items = $data;
        $this->set_pagination_args(array(
            'total_items' => $total_items,
            'per_page' => $per_page,
            'total_pages' => ceil($total_items / $per_page)
        ));
    }

    function display() {
        parent::display();
    }

    function ajax_response() {

        $this->prepare_items();

        extract($this->_args);
        extract($this->_pagination_args, EXTR_SKIP);

        ob_start();
        if (!empty($_REQUEST['no_placeholder'])) {
            $this->display_rows();
        } else {
            $this->display_rows_or_placeholder();
        }
        $rows = ob_get_clean();

        ob_start();
        $this->print_column_headers();
        $headers = ob_get_clean();

        ob_start();
        $this->pagination('top');
        $pagination_top = ob_get_clean();

        ob_start();
        $this->pagination('bottom');
        $pagination_bottom = ob_get_clean();

        $response = array(
            'rows' => $rows
        );
        $response['pagination']['top'] = $pagination_top;
        $response['pagination']['bottom'] = $pagination_bottom;
        $response['column_headers'] = $headers;

        if (isset($total_items)) {
            $response['total_items_i18n'] = sprintf(_n('1 item', '%s items', $total_items), number_format_i18n($total_items));
        }

        if (isset($total_pages)) {
            $response['total_pages'] = $total_pages;
            $response['total_pages_i18n'] = number_format_i18n($total_pages);
        }

        die(json_encode($response));
    }

}

function eh_spg_order_ajax_data_callback() {
    check_ajax_referer('ajax-eh-spg-nonce', '_ajax_eh_spg_nonce');
    $obj = new Eh_Stripe_Order_Datatables();
    $obj->input();
    $obj->ajax_response();
}

add_action('wp_ajax_eh_spg_order_ajax_table_data', 'eh_spg_order_ajax_data_callback');

function eh_spg_stripe_ajax_data_callback() {
    check_ajax_referer('ajax-eh-spg-nonce', '_ajax_eh_spg_nonce');
    $obj = new Eh_Stripe_Datatables();
    $obj->input();
    $obj->ajax_response();
}

add_action('wp_ajax_eh_spg_stripe_ajax_table_data', 'eh_spg_stripe_ajax_data_callback');

/**
 * This function adds the jQuery script to the plugin's page footer
 */
function eh_spg_admin_header() {
    $page = (isset($_GET['page'])) ? esc_attr($_GET['page']) : false;
    if ('eh-stripe-overview' != $page)
        return;
    $tab = (!empty($_GET['tab'])) ? esc_attr($_GET['tab']) : 'orders';
    if ($tab === 'orders') {
        echo '<style type="text/css">';
        echo '.wp-list-table { text-align:center ;}';
        echo 'table th{ text-align:center !important;}';
        echo '.wp-list-table .column-date { width: 10%;}';
        echo '.wp-list-table .column-order_actions { width: 10%; vertical-align:middle;}';
        echo '.wp-list-table .column-price { width: 10%;}';
        echo '.wp-list-table .column-p_actions { vertical-align:middle;}';
        echo '</style>';
    } else {
        echo '<style type="text/css">';
        echo '.wp-list-table { text-align:center ;}';
        echo 'table th{ text-align:center !important;}';
        echo '.wp-list-table .column-date { width: 10%;}';
        echo '.wp-list-table .column-status { width: 15%;}';
        echo '.wp-list-table .column-amount { width: 15%;}';
        echo '</style>';
    }
}

function eh_spg_ajax_table_script() {
    $screen = get_current_screen();
    if ('woocommerce_page_eh-stripe-overview' != $screen->id)
        return false;
    $tab = (!empty($_GET['tab'])) ? esc_attr($_GET['tab']) : 'orders';
    if ($tab === 'orders') {
        ?>
        <script type="text/javascript">
            (function (jQuery) {

                list = {
                    init: function () {

                        // This will have its utility when dealing with the page number input
                        var timer;
                        var delay = 500;

                        // Pagination links, sortable link
                        jQuery('.tablenav-pages a, .manage-column.sortable a, .manage-column.sorted a').on('click', function (e) {
                            // We don't want to actually follow these links
                            e.preventDefault();
                            // Simple way: use the URL to extract our needed variables
                            var query = this.search.substring(1);

                            var data = {
                                paged: list.__query(query, 'paged') || '1',
                            };
                            list.update(data);
                        });

                        // Page number input
                        jQuery('input[name=paged]').on('keyup', function (e) {
                            if (13 == e.which)
                                e.preventDefault();

                            // This time we fetch the variables in inputs
                            var data = {
                                paged: parseInt(jQuery('input[name=paged]').val()) || '1',
                            };
                            window.clearTimeout(timer);
                            timer = window.setTimeout(function () {
                                list.update(data);
                            }, delay);
                        });
                    },
                    update: function (data) {
                        jQuery("#order_section  .loader").css("display", "block");
                        jQuery.ajax({

                            // /wp-admin/admin-ajax.php
                            url: ajaxurl,
                            // Add action and nonce to our collected data
                            data: jQuery.extend({
                                _ajax_eh_spg_nonce: jQuery('#_ajax_eh_spg_nonce').val(),
                                action: 'eh_spg_order_ajax_table_data',
                            },
                                    data
                                    ),
                            // Handle the successful result
                            success: function (response) {
                                jQuery("#order_section .loader").css("display", "none");
                                // WP_List_Table::ajax_response() returns json
                                var response = jQuery.parseJSON(response);
                                // Add the requested rows
                                if (response.rows.length)
                                    jQuery('#the-list').html(response.rows);
                                // Update column headers for sorting
                                if (response.column_headers.length)
                                    jQuery('thead tr, tfoot tr').html(response.column_headers);
                                // Update pagination for navigation
                                if (response.pagination.bottom.length)
                                    jQuery('.tablenav.top .tablenav-pages').html(jQuery(response.pagination.top).html());
                                if (response.pagination.top.length)
                                    jQuery('.tablenav.bottom .tablenav-pages').html(jQuery(response.pagination.bottom).html());

                                // Init back our event handlers
                                list.init();
                            }
                        });
                    },
                    __query: function (query, variable) {

                        var vars = query.split("&");
                        for (var i = 0; i < vars.length; i++) {
                            var pair = vars[i].split("=");
                            if (pair[0] == variable)
                                return pair[1];
                        }
                        return false;
                    },
                }

                // Show time!
                list.init();

            })(jQuery);
        </script>
        <?php

    } else {
        ?>
        <script type="text/javascript">
            (function (jQuery) {

                list = {
                    init: function () {

                        // This will have its utility when dealing with the page number input
                        var timer;
                        var delay = 500;

                        // Pagination links, sortable link
                        jQuery('.tablenav-pages a, .manage-column.sortable a, .manage-column.sorted a').on('click', function (e) {
                            // We don't want to actually follow these links
                            e.preventDefault();
                            // Simple way: use the URL to extract our needed variables
                            var query = this.search.substring(1);

                            var data = {
                                paged: list.__query(query, 'paged') || '1',
                            };
                            list.update(data);
                        });

                        // Page number input
                        jQuery('input[name=paged]').on('keyup', function (e) {
                            if (13 == e.which)
                                e.preventDefault();

                            // This time we fetch the variables in inputs
                            var data = {
                                paged: parseInt(jQuery('input[name=paged]').val()) || '1',
                            };
                            window.clearTimeout(timer);
                            timer = window.setTimeout(function () {
                                list.update(data);
                            }, delay);
                        });
                    },
                    update: function (data) {
                        jQuery("#stripe_section  .loader").css("display", "block");
                        jQuery.ajax({

                            // /wp-admin/admin-ajax.php
                            url: ajaxurl,
                            // Add action and nonce to our collected data
                            data: jQuery.extend({
                                _ajax_eh_spg_nonce: jQuery('#_ajax_eh_spg_nonce').val(),
                                action: 'eh_spg_stripe_ajax_table_data',
                            },
                                    data
                                    ),
                            // Handle the successful result
                            success: function (response) {
                                jQuery("#stripe_section .loader").css("display", "none");
                                // WP_List_Table::ajax_response() returns json
                                var response = jQuery.parseJSON(response);
                                // Add the requested rows
                                if (response.rows.length)
                                    jQuery('#the-list').html(response.rows);
                                // Update column headers for sorting
                                if (response.column_headers.length)
                                    jQuery('thead tr, tfoot tr').html(response.column_headers);
                                // Update pagination for navigation
                                if (response.pagination.bottom.length)
                                    jQuery('.tablenav.top .tablenav-pages').html(jQuery(response.pagination.top).html());
                                if (response.pagination.top.length)
                                    jQuery('.tablenav.bottom .tablenav-pages').html(jQuery(response.pagination.bottom).html());

                                // Init back our event handlers
                                list.init();
                            }
                        });
                    },
                    __query: function (query, variable) {

                        var vars = query.split("&");
                        for (var i = 0; i < vars.length; i++) {
                            var pair = vars[i].split("=");
                            if (pair[0] == variable)
                                return pair[1];
                        }
                        return false;
                    },
                }

                // Show time!
                list.init();

            })(jQuery);
        </script>
        <?php

    }
}

function eh_stripe_overview_get_order_ids() {
    $args = array(
        'post_type' => 'shop_order',
        'fields' => 'ids',
        'numberposts' => -1,
        'post_status' => array('wc-processing', 'wc-on-hold', 'wc-completed', 'wc-refunded')
    );
    $id = get_posts($args);
    $order_all_id = array();
    for ($i = 0, $count = 0; $i < count($id); $i++) {
        if ('eh_stripe_pay' === get_post_meta($id[$i], '_payment_method', true)) {
            $order_all_id[$count] = $id[$i];
            $count++;
        }
    }
    return $order_all_id;
}
?>
<?php

add_action('admin_head', 'eh_spg_admin_header');
add_action('admin_footer', 'eh_spg_ajax_table_script');
