<?php

function AgileWC_script()
{
    global $AGILEWC_DOMAIN, $AGILEWC_KEY, $current_user, $AGILEWC_SYNC_OPTIONS;
    //get_currentuserinfo();
    wp_get_current_user();
    $email = $current_user->user_email;

    $script = "";

    if ((isset($AGILEWC_DOMAIN) && $AGILEWC_DOMAIN) && (isset($AGILEWC_KEY) && $AGILEWC_KEY)) {
        $script .= '<script src="https://' . $AGILEWC_DOMAIN . '.agilecrm.com/stats/min/agile-min.js"></script>';
        $script .= '<script>';
        $script .= '_agile.set_account("' . $AGILEWC_KEY . '","' . $AGILEWC_DOMAIN . '");';

        if (isset($AGILEWC_SYNC_OPTIONS['track_visitors'])) {
            $script .= '_agile.track_page_view();';
        }

        if (isset($AGILEWC_SYNC_OPTIONS['web_rules'])) {
            $script .= '_agile_execute_web_rules();';
        }

        if (isset($email) && $email != NULL && $email != '') {
            $script .= '_agile.set_email("' . $email . '");';
        } elseif (isset($_SESSION['agileWCTrackEmail'])) {
            $script .= '_agile.set_email("' . $_SESSION['agileWCTrackEmail'] . '");';
        }

        $script .= '</script>';
    }

    echo $script;
}

function AgileWC_created_customer()
{
    $cusIdArr = func_get_args();
    $userId = $cusIdArr[0];
    $cusId = get_post_meta($userId, '_customer_user', true);

    $cusEmail = get_post_meta($userId, '_billing_email', true);

    $customer = new AgileCRM_Customer();
    $customer->first_name = get_post_meta($userId, '_billing_first_name', true);
    $customer->last_name = get_post_meta($userId, '_billing_last_name', true);
    $customer->company = get_post_meta($userId, '_billing_company', true);
    $customer->email = $cusEmail;
    $customer->phone = get_post_meta($userId, '_billing_phone', true);
    $customer->address = AgileWC_getUserAddress($userId);

    $agilecrm = new AgileCRM();
    $agilecrm->customerEmail = $cusEmail;
    $agilecrm->hook = AgileCRM::$hooks['customer.created'];
    $agilecrm->payLoad = $customer->getAgileFormat();
    $res = $agilecrm->post();

    if ($res && $cusId == 0) {
        $_SESSION['agileWCTrackEmail'] = $cusEmail;
    }
}

function AgileWC_new_order()
{
    $_SESSION['agileWCOrderHook'] = AgileCRM::$hooks['order.created'];
}

function AgileWC_order_status_changed()
{
    global $AGILEWC_SYNC_OPTIONS;
    $ordersArr = func_get_args();
    $wcorder = new WC_Order($ordersArr[0]);
    $order = AgileWC_getOrder($wcorder);

    $orderHook = AgileCRM::$hooks['order.updated'];
    if (isset($_SESSION['agileWCOrderHook'])) {
        $orderHook = $_SESSION['agileWCOrderHook'];
        unset($_SESSION['agileWCOrderHook']);
    }

    $agilecrm = new AgileCRM();
    $agilecrm->customerEmail = $wcorder->billing_email;
    $agilecrm->hook = $orderHook;
    $agilecrm->payLoad = array("order" => $order);
    $agilecrm->syncAsTags = "";
    
    if (isset($AGILEWC_SYNC_OPTIONS['sync_product_tags'])) {
        $agilecrm->syncAsTags .= "_products";
    }
    if (isset($AGILEWC_SYNC_OPTIONS['sync_category_tags'])) {
        $agilecrm->syncAsTags .= "_categories";
    }
    
    $agilecrm->post();
}

function AgileWC_new_customer_note()
{
    $parmArr = func_get_args();
    $wcorder = new WC_Order($parmArr[0]['order_id']);

    $agileData = array(
        "subject" => "Customer note on order #" . $parmArr[0]['order_id'],
        "description" => $parmArr[0]['customer_note']
    );

    $agilecrm = new AgileCRM();
    $agilecrm->customerEmail = $wcorder->billing_email;
    $agilecrm->hook = AgileCRM::$hooks['note.created'];
    $agilecrm->payLoad = array("order" => AgileWC_getOrder($wcorder), "note" => $agileData);
    $agilecrm->post();
}

function AgileWC_getOrder($wcorder)
{
    $order = new AgileCRM_Order();
    $order->id = $wcorder->id;
    $order->status = AgileWC_getOrderStatusName($wcorder->post_status);
    $order->billingAddress = str_replace('<br/>', ", ", $wcorder->get_formatted_billing_address());
    $order->shippingAddress = str_replace('<br/>', ", ", $wcorder->get_formatted_shipping_address());
    $order->grandTotal = number_format($wcorder->get_total(), 2, '.', '');
    $order->note = $wcorder->customer_note;

    $items = $wcorder->get_items();
    foreach ($items as $item) {
        $product = new AgileCRM_Product();
        $product->id = $item['product_id'];
        $product->name = $item['name'];
        $product->quantity = $item['qty'];

        $terms = wp_get_post_terms($item['product_id'], 'product_cat', array('fields' => 'names'));
        if ($terms && !is_wp_error($terms)) {
            $product->categories = $terms;
        }
        
        $order->products[] = $product;
    }

    return $order;
}

function AgileWC_getUserAddress($user_id)
{
    global $states;
    $countryCode = get_post_meta($user_id, '_billing_country', true);
    $stateCode = get_post_meta($user_id, '_billing_state', true);
    $agileAddress = new AgileCRM_Address();
    $agileAddress->address = get_post_meta($user_id, '_billing_address_1', true) . ' ' . get_post_meta($user_id, '_billing_address_2', true);
    $agileAddress->city = get_post_meta($user_id, '_billing_city', true);
    $agileAddress->state = isset($states[$countryCode][$stateCode]) ? $states[$countryCode][$stateCode] : "";
    $agileAddress->zip = get_post_meta($user_id, '_billing_postcode', true);
    $agileAddress->country = WC()->countries->countries[$countryCode];
    return $agileAddress;
}

function AgileWC_getOrderStatusName($statusCode)
{
    $orderStatuses = array(
        'wc-pending' => 'Pending Payment',
        'wc-processing' => 'Processing',
        'wc-on-hold' => 'On Hold',
        'wc-completed' => 'Completed',
        'wc-cancelled' => 'Cancelled',
        'wc-refunded' => 'Refunded',
        'wc-failed' => 'Failed',
    );

    return isset($orderStatuses[$statusCode]) ? $orderStatuses[$statusCode] : $statusCode;
}
