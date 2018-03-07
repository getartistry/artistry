<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Version Control
 *
 * WARNING: Make sure to update version number here as well as in the main class name
 */
$version = '16';

global $rightpress_wc_legacy_version;

if (!$rightpress_wc_legacy_version || $rightpress_wc_legacy_version < $version) {
    $rightpress_wc_legacy_version = $version;
}

/**
 * Proxy Class
 */
if (!class_exists('RightPress_WC_Legacy')) {

final class RightPress_WC_Legacy
{

    /**
     * Method overload
     *
     * @access public
     * @param string $method_name
     * @param array $arguments
     * @return mixed
     */
    public static function __callStatic($method_name, $arguments)
    {
        // Get latest version of the main class
        global $rightpress_wc_legacy_version;

        // Get main class name
        $class_name = 'RightPress_WC_Legacy_' . $rightpress_wc_legacy_version;

        // Call main class
        return call_user_func_array(array($class_name, $method_name), $arguments);
    }
}
}

/**
 * Handling incompatible changes in WooCommerce core across different versions
 * RightPress_Helper must be loaded for these methods to work properly
 *
 * @class RightPress_WC_Legacy
 * @author RightPress
 */
if (!class_exists('RightPress_WC_Legacy_16')) {

class RightPress_WC_Legacy_16
{

    /**
     * Get order id
     * WC 3.0
     *
     * @access public
     * @param object $order
     * @return int
     */
    public static function order_get_id($order)
    {
        return RightPress_Helper::wc_version_gte('3.0') ? $order->get_id() : $order->id;
    }

    /**
     * Get order billing first name
     * WC 3.0
     *
     * @access public
     * @param object $order
     * @return string
     */
    public static function order_get_billing_first_name($order)
    {
        return RightPress_Helper::wc_version_gte('3.0') ? $order->get_billing_first_name() : $order->billing_first_name;
    }

    /**
     * Get order billing last name
     * WC 3.0
     *
     * @access public
     * @param object $order
     * @return string
     */
    public static function order_get_billing_last_name($order)
    {
        return RightPress_Helper::wc_version_gte('3.0') ? $order->get_billing_last_name() : $order->billing_last_name;
    }

    /**
     * Get order billing company
     * WC 3.0
     *
     * @access public
     * @param object $order
     * @return string
     */
    public static function order_get_billing_company($order)
    {
        return RightPress_Helper::wc_version_gte('3.0') ? $order->get_billing_company() : $order->billing_company;
    }

    /**
     * Get order billing address 1
     * WC 3.0
     *
     * @access public
     * @param object $order
     * @return string
     */
    public static function order_get_billing_address_1($order)
    {
        return RightPress_Helper::wc_version_gte('3.0') ? $order->get_billing_address_1() : $order->billing_address_1;
    }

    /**
     * Get order billing address 2
     * WC 3.0
     *
     * @access public
     * @param object $order
     * @return string
     */
    public static function order_get_billing_address_2($order)
    {
        return RightPress_Helper::wc_version_gte('3.0') ? $order->get_billing_address_2() : $order->billing_address_2;
    }

    /**
     * Get order billing city
     * WC 3.0
     *
     * @access public
     * @param object $order
     * @return string
     */
    public static function order_get_billing_city($order)
    {
        return RightPress_Helper::wc_version_gte('3.0') ? $order->get_billing_city() : $order->billing_city;
    }

    /**
     * Get order billing state
     * WC 3.0
     *
     * @access public
     * @param object $order
     * @return string
     */
    public static function order_get_billing_state($order)
    {
        return RightPress_Helper::wc_version_gte('3.0') ? $order->get_billing_state() : $order->billing_state;
    }

    /**
     * Get order billing postcode
     * WC 3.0
     *
     * @access public
     * @param object $order
     * @return string
     */
    public static function order_get_billing_postcode($order)
    {
        return RightPress_Helper::wc_version_gte('3.0') ? $order->get_billing_postcode() : $order->billing_postcode;
    }

    /**
     * Get order billing country
     * WC 3.0
     *
     * @access public
     * @param object $order
     * @return string
     */
    public static function order_get_billing_country($order)
    {
        return RightPress_Helper::wc_version_gte('3.0') ? $order->get_billing_country() : $order->billing_country;
    }

    /**
     * Get order shipping first name
     * WC 3.0
     *
     * @access public
     * @param object $order
     * @return string
     */
    public static function order_get_shipping_first_name($order)
    {
        return RightPress_Helper::wc_version_gte('3.0') ? $order->get_shipping_first_name() : $order->shipping_first_name;
    }

    /**
     * Get order shipping last name
     * WC 3.0
     *
     * @access public
     * @param object $order
     * @return string
     */
    public static function order_get_shipping_last_name($order)
    {
        return RightPress_Helper::wc_version_gte('3.0') ? $order->get_shipping_last_name() : $order->shipping_last_name;
    }

    /**
     * Get order shipping company
     * WC 3.0
     *
     * @access public
     * @param object $order
     * @return string
     */
    public static function order_get_shipping_company($order)
    {
        return RightPress_Helper::wc_version_gte('3.0') ? $order->get_shipping_company() : $order->shipping_company;
    }

    /**
     * Get order shipping address 1
     * WC 3.0
     *
     * @access public
     * @param object $order
     * @return string
     */
    public static function order_get_shipping_address_1($order)
    {
        return RightPress_Helper::wc_version_gte('3.0') ? $order->get_shipping_address_1() : $order->shipping_address_1;
    }

    /**
     * Get order shipping address 2
     * WC 3.0
     *
     * @access public
     * @param object $order
     * @return string
     */
    public static function order_get_shipping_address_2($order)
    {
        return RightPress_Helper::wc_version_gte('3.0') ? $order->get_shipping_address_2() : $order->shipping_address_2;
    }

    /**
     * Get order shipping city
     * WC 3.0
     *
     * @access public
     * @param object $order
     * @return string
     */
    public static function order_get_shipping_city($order)
    {
        return RightPress_Helper::wc_version_gte('3.0') ? $order->get_shipping_city() : $order->shipping_city;
    }

    /**
     * Get order shipping state
     * WC 3.0
     *
     * @access public
     * @param object $order
     * @return string
     */
    public static function order_get_shipping_state($order)
    {
        return RightPress_Helper::wc_version_gte('3.0') ? $order->get_shipping_state() : $order->shipping_state;
    }

    /**
     * Get order shipping postcode
     * WC 3.0
     *
     * @access public
     * @param object $order
     * @return string
     */
    public static function order_get_shipping_postcode($order)
    {
        return RightPress_Helper::wc_version_gte('3.0') ? $order->get_shipping_postcode() : $order->shipping_postcode;
    }

    /**
     * Get order shipping country
     * WC 3.0
     *
     * @access public
     * @param object $order
     * @return string
     */
    public static function order_get_shipping_country($order)
    {
        return RightPress_Helper::wc_version_gte('3.0') ? $order->get_shipping_country() : $order->shipping_country;
    }

    /**
     * Get order billing phone
     * WC 3.0
     *
     * @access public
     * @param object $order
     * @return string
     */
    public static function order_get_billing_phone($order)
    {
        return RightPress_Helper::wc_version_gte('3.0') ? $order->get_billing_phone() : $order->billing_phone;
    }

    /**
     * Get order billing email
     * WC 3.0
     *
     * @access public
     * @param object $order
     * @return string
     */
    public static function order_get_billing_email($order)
    {
        return RightPress_Helper::wc_version_gte('3.0') ? $order->get_billing_email() : $order->billing_email;
    }

    /**
     * Get order payment method
     * WC 3.0
     *
     * @access public
     * @param object $order
     * @return string
     */
    public static function order_get_payment_method($order)
    {
        return RightPress_Helper::wc_version_gte('3.0') ? $order->get_payment_method() : $order->payment_method;
    }

    /**
     * Get order payment method title
     * WC 3.0
     *
     * @access public
     * @param object $order
     * @return string
     */
    public static function order_get_payment_method_title($order)
    {
        return RightPress_Helper::wc_version_gte('3.0') ? $order->get_payment_method_title() : $order->payment_method_title;
    }

    /**
     * Get order customer note
     * WC 3.0
     *
     * @access public
     * @param object $order
     * @return string
     */
    public static function order_get_customer_note($order)
    {
        return RightPress_Helper::wc_version_gte('3.0') ? $order->get_customer_note() : $order->customer_note;
    }

    /**
     * Get order customer id
     * WC 3.0
     *
     * @access public
     * @param object $order
     * @return int
     */
    public static function order_get_customer_id($order)
    {
        return RightPress_Helper::wc_version_gte('3.0') ? $order->get_customer_id() : $order->get_user_id();
    }

    /**
     * Get order customer ip address
     * WC 3.0
     *
     * @access public
     * @param object $order
     * @return int
     */
    public static function order_get_customer_ip_address($order)
    {
        return RightPress_Helper::wc_version_gte('3.0') ? $order->get_customer_ip_address() : $order->customer_ip_address;
    }

    /**
     * Get order customer user agent
     * WC 3.0
     *
     * @access public
     * @param object $order
     * @return int
     */
    public static function order_get_customer_user_agent($order)
    {
        return RightPress_Helper::wc_version_gte('3.0') ? $order->get_customer_user_agent() : $order->customer_user_agent;
    }

    /**
     * Get order prices include tax
     * WC 3.0
     *
     * @access public
     * @param object $order
     * @return float
     */
    public static function order_get_prices_include_tax($order)
    {
        return RightPress_Helper::wc_version_gte('3.0') ? $order->get_prices_include_tax() : $order->prices_include_tax;
    }

    /**
     * Get order total
     * WC 3.0
     *
     * @access public
     * @param object $order
     * @return float
     */
    public static function order_get_total($order)
    {
        return RightPress_Helper::wc_version_gte('3.0') ? $order->get_total() : $order->order_total;
    }

    /**
     * Get order total discount
     * WC 3.0
     *
     * @access public
     * @param object $order
     * @return float
     */
    public static function order_get_total_discount($order)
    {
        return RightPress_Helper::wc_version_gte('3.0') ? $order->get_total_discount() : $order->cart_discount;
    }

    /**
     * Get order discount tax
     * WC 3.0
     *
     * @access public
     * @param object $order
     * @return float
     */
    public static function order_get_discount_tax($order)
    {
        return RightPress_Helper::wc_version_gte('3.0') ? $order->get_discount_tax() : $order->cart_discount_tax;
    }

    /**
     * Get order cart tax
     * WC 3.0
     *
     * @access public
     * @param object $order
     * @return float
     */
    public static function order_get_cart_tax($order)
    {
        return RightPress_Helper::wc_version_gte('3.0') ? $order->get_cart_tax() : $order->order_tax;
    }

    /**
     * Get formatted order date created
     * WC 3.0
     *
     * @access public
     * @param object $order
     * @param string $format
     * @return string
     */
    public static function order_get_formatted_date_created($order, $format = null)
    {
        $format = ($format !== null ? $format : wc_date_format());
        return RightPress_Helper::wc_version_gte('3.0') ? wc_format_datetime($order->get_date_created(), $format) : date_i18n($format, strtotime($order->order_date));
    }

    /**
     * Get order date created
     * WC 3.0
     *
     * Note: this method returns order date in "Y-m-d H:i:s" format
     *
     * @access public
     * @param object $order
     * @return string
     */
    public static function order_get_date_created($order)
    {
        return RightPress_Helper::wc_version_gte('3.0') ? gmdate('Y-m-d H:i:s', $order->get_date_created()->getOffsetTimestamp()) : $order->order_date;
    }

    /**
     * Get order status
     * WC 3.0
     *
     * @access public
     * @param object $order
     * @return string
     */
    public static function order_get_status($order)
    {
        return RightPress_Helper::wc_version_gte('3.0') ? $order->get_status() : $order->status;
    }

    /**
     * Get order currency
     * WC 3.0
     *
     * @access public
     * @param object $order
     * @return string
     */
    public static function order_get_currency($order)
    {
        return RightPress_Helper::wc_version_gte('3.0') ? $order->get_currency() : $order->get_order_currency();
    }

    /**
     * Reduce stock levels
     * WC 3.0
     *
     * @access public
     * @param object $order
     * @return void
     */
    public static function order_reduce_stock_levels($order)
    {
        if (RightPress_Helper::wc_version_gte('3.0')) {
            wc_reduce_stock_levels(RightPress_WC_Legacy::order_get_id($order));
        }
        else {
            $order->reduce_order_stock();
        }
    }

    /**
     * Get order shipping total
     * WC 3.0
     *
     * @access public
     * @param object $order
     * @return float
     */
    public static function order_get_shipping_total($order)
    {
        return RightPress_Helper::wc_version_gte('3.0') ? $order->get_shipping_total() : $order->get_total_shipping();
    }

    /**
     * Get order item product id
     * WC 3.0
     *
     * @access public
     * @param mixed $order_item
     * @return int
     */
    public static function order_item_get_product_id($order_item)
    {
        return RightPress_Helper::wc_version_gte('3.0') ? $order_item->get_product_id() : $order_item['product_id'];
    }

    /**
     * Get order item variation id
     * WC 3.0
     *
     * @access public
     * @param mixed $order_item
     * @return int
     */
    public static function order_item_get_variation_id($order_item)
    {
        return RightPress_Helper::wc_version_gte('3.0') ? $order_item->get_variation_id() : $order_item['variation_id'];
    }

    /**
     * Get order item quantity
     * WC 3.0
     *
     * @access public
     * @param mixed $order_item
     * @return int
     */
    public static function order_item_get_quantity($order_item)
    {
        return RightPress_Helper::wc_version_gte('3.0') ? $order_item->get_quantity() : $order_item['qty'];
    }

    /**
     * Get order item name
     * WC 3.0
     *
     * @access public
     * @param mixed $order_item
     * @return int
     */
    public static function order_item_get_name($order_item)
    {
        return RightPress_Helper::wc_version_gte('3.0') ? $order_item->get_name() : $order_item['name'];
    }

    /**
     * Get order item tax name
     * WC 3.0
     *
     * @access public
     * @param mixed $order_item
     * @return int
     */
    public static function order_item_tax_get_name($order_item)
    {
        return RightPress_Helper::wc_version_gte('3.0') ? $order_item->get_name() : $order_item['label'];
    }

    /**
     * Get order item tax label
     * WC 3.0
     *
     * @access public
     * @param mixed $order_item
     * @return int
     */
    public static function order_item_tax_get_label($order_item)
    {
        return RightPress_Helper::wc_version_gte('3.0') ? $order_item->get_label() : $order_item['label'];
    }

    /**
     * Get order item tax rate id
     * WC 3.0
     *
     * @access public
     * @param mixed $order_item
     * @return int
     */
    public static function order_item_tax_get_rate_id($order_item)
    {
        return RightPress_Helper::wc_version_gte('3.0') ? $order_item->get_rate_id() : $order_item['rate_id'];
    }

    /**
     * Get order item tax get tax total
     * WC 3.0
     *
     * @access public
     * @param mixed $order_item
     * @return int
     */
    public static function order_item_tax_get_tax_total($order_item)
    {
        return RightPress_Helper::wc_version_gte('3.0') ? $order_item->get_tax_total() : $order_item['tax_amount'];
    }

    /**
     * Get order item tax get shipping tax total
     * WC 3.0
     *
     * @access public
     * @param mixed $order_item
     * @return int
     */
    public static function order_item_tax_get_shipping_tax_total($order_item)
    {
        return RightPress_Helper::wc_version_gte('3.0') ? $order_item->get_shipping_tax_total() : $order_item['shipping_tax_amount'];
    }

    /**
     * Get order item subtotal
     * WC 3.0
     *
     * @access public
     * @param mixed $order_item
     * @return int
     */
    public static function order_item_get_subtotal($order_item)
    {
        return RightPress_Helper::wc_version_gte('3.0') ? $order_item->get_subtotal() : $order_item['line_subtotal'];
    }

    /**
     * Get order item subtotal tax
     * WC 3.0
     *
     * @access public
     * @param mixed $order_item
     * @return int
     */
    public static function order_item_get_subtotal_tax($order_item)
    {
        return RightPress_Helper::wc_version_gte('3.0') ? $order_item->get_subtotal_tax() : $order_item['line_subtotal_tax'];
    }

    /**
     * Get order item total
     * WC 3.0
     *
     * @access public
     * @param mixed $order_item
     * @return int
     */
    public static function order_item_get_total($order_item)
    {
        return RightPress_Helper::wc_version_gte('3.0') ? $order_item->get_total() : $order_item['line_total'];
    }

    /**
     * Get order item total tax
     * WC 3.0
     *
     * @access public
     * @param mixed $order_item
     * @return int
     */
    public static function order_item_get_total_tax($order_item)
    {
        return RightPress_Helper::wc_version_gte('3.0') ? $order_item->get_total_tax() : $order_item['line_tax'];
    }

    /**
     * Get order item tax class
     * WC 3.0
     *
     * @access public
     * @param mixed $order_item
     * @return int
     */
    public static function order_item_get_tax_class($order_item)
    {
        return RightPress_Helper::wc_version_gte('3.0') ? $order_item->get_tax_class() : $order_item['tax_class'];
    }

    /**
     * Get product from order item
     * WC 3.0
     *
     * @access public
     * @param object $order_item
     * @param object $order
     * @return object
     */
    public static function order_item_get_product($order_item, $order = null)
    {
        return RightPress_Helper::wc_version_gte('3.0') ? $order_item->get_product() : $order->get_product_from_item($order_item);
    }

    /**
     * Get order item meta by key
     * WC 3.0
     *
     * @access public
     * @param object $order_item
     * @param string $key
     * @param bool $single
     * @param string $context
     * @return mixed
     */
    public static function order_item_get_meta($order_item, $key, $single = true, $context = 'view')
    {
        if (RightPress_Helper::wc_version_gte('3.0')) {
            return RightPress_WC_Legacy::fix_meta($order_item->get_meta($key, $single, $context), $single);
        }
        else {
            if (isset($order_item['item_meta'][$key])) {
                return $order_item['item_meta'][$key];
            }
            else {
                return $single ? '' : array();
            }
        }
    }

    /**
     * Get order meta by key
     * WC 3.0
     *
     * @access public
     * @param object $order
     * @param string $key
     * @param bool $single
     * @param string $context
     * @return mixed
     */
    public static function order_get_meta($order, $key, $single = true, $context = 'view')
    {
        if (RightPress_Helper::wc_version_gte('3.0')) {
            return RightPress_WC_Legacy::fix_meta($order->get_meta($key, $single, $context), $single);
        }
        else {
            return get_post_meta(RightPress_WC_Legacy::order_get_id($order), $key, $single);
        }
    }

    /**
     * Check if order meta exists
     * WC 3.0
     *
     * @access public
     * @param object $order
     * @param string $key
     * @return bool
     */
    public static function order_meta_exists($order, $key)
    {
        if (RightPress_Helper::wc_version_gte('3.0')) {
            return $order->meta_exists($key);
        }
        else {
            return metadata_exists('post', RightPress_WC_Legacy::order_get_id($order), $key);
        }
    }

    /**
     * Add order meta data
     * WC 3.0
     *
     * @access public
     * @param object $order
     * @param string $key
     * @param mixed $value
     * @param bool $unique
     * @return void
     */
    public static function order_add_meta_data($order, $key, $value, $unique = false)
    {
        if (RightPress_Helper::wc_version_gte('3.0')) {
            $order->add_meta_data($key, $value, $unique);
            $order->save();
        }
        else {
            add_post_meta(RightPress_WC_Legacy::order_get_id($order), $key, $value, $unique);
        }
    }

    /**
     * Update order meta data
     * WC 3.0
     *
     * @access public
     * @param object $order
     * @param string $key
     * @param mixed $value
     * @param int $meta_id
     * @return void
     */
    public static function order_update_meta_data($order, $key, $value, $meta_id = '')
    {
        if (RightPress_Helper::wc_version_gte('3.0')) {
            $order->update_meta_data($key, $value, $meta_id);
            $order->save();
        }
        else {
            update_post_meta(RightPress_WC_Legacy::order_get_id($order), $key, $value);
        }
    }

    /**
     * Delete order meta data
     * WC 3.0
     *
     * Note: this does not support deleting meta data by value from a set of meta values with the same key
     *
     * @access public
     * @param object $order
     * @param string $key
     * @return void
     */
    public static function order_delete_meta_data($order, $key)
    {
        if (RightPress_Helper::wc_version_gte('3.0')) {
            $order->delete_meta_data($key);
            $order->save();
        }
        else {
            delete_post_meta(RightPress_WC_Legacy::order_get_id($order), $key);
        }
    }

    /**
     * Get product id
     * WC 3.0
     *
     * @access public
     * @param object $product
     * @return int
     */
    public static function product_get_id($product)
    {
        if (RightPress_Helper::wc_version_gte('3.0')) {
            return $product->get_id();
        }
        else {
            return $product->is_type('variation') ? $product->variation_id : $product->id;
        }
    }

    /**
     * Get product variation parent id
     * WC 3.0
     *
     * @access public
     * @param object $product_variation
     * @return int
     */
    public static function product_variation_get_parent_id($product_variation)
    {
        return RightPress_Helper::wc_version_gte('3.0') ? $product_variation->get_parent_id() : $product_variation->id;
    }

    /**
     * Get product variation parent
     * WC 3.0
     *
     * @access public
     * @param object $product_variation
     * @return object
     */
    public static function product_variation_get_parent($product_variation)
    {
        return RightPress_Helper::wc_version_gte('3.0') ? wc_get_product($product_variation->get_parent_id()) : $product_variation->parent;
    }

    /**
     * Get product type
     * WC 3.0
     *
     * @access public
     * @param object $product
     * @return string
     */
    public static function product_get_type($product)
    {
        return RightPress_Helper::wc_version_gte('3.0') ? $product->get_type() : $product->product_type;
    }

    /**
     * Get product price
     * WC 3.0
     *
     * @access public
     * @param object $product
     * @param string $context
     * @return float
     */
    public static function product_get_price($product, $context = 'view')
    {
        return RightPress_Helper::wc_version_gte('3.0') ? $product->get_price($context) : $product->price;
    }

    /**
     * Get product regular price
     * WC 3.0
     *
     * @access public
     * @param object $product
     * @param string $context
     * @return float
     */
    public static function product_get_regular_price($product, $context = 'view')
    {
        return RightPress_Helper::wc_version_gte('3.0') ? $product->get_regular_price($context) : $product->regular_price;
    }

    /**
     * Get product sale price
     * WC 3.0
     *
     * @access public
     * @param object $product
     * @param string $context
     * @return float
     */
    public static function product_get_sale_price($product, $context = 'view')
    {
        return RightPress_Helper::wc_version_gte('3.0') ? $product->get_sale_price($context) : $product->sale_price;
    }

    /**
     * Get product price including tax
     * WC 3.0
     *
     * @access public
     * @param object $product
     * @param int $quantity
     * @param float $price
     * @return float
     */
    public static function product_get_price_including_tax($product, $quantity = 1, $price = '')
    {
        return RightPress_Helper::wc_version_gte('3.0') ? wc_get_price_including_tax($product, array('qty' => $quantity, 'price' => $price)) : $product->get_price_including_tax($quantity, $price);
    }

    /**
     * Get product price excluding tax
     * WC 3.0
     *
     * @access public
     * @param object $product
     * @param int $quantity
     * @param float $price
     * @return float
     */
    public static function product_get_price_excluding_tax($product, $quantity = 1, $price = '')
    {
        return RightPress_Helper::wc_version_gte('3.0') ? wc_get_price_excluding_tax($product, array('qty' => $quantity, 'price' => $price)) : $product->get_price_excluding_tax($quantity, $price);
    }

    /**
     * Get product display price
     *
     * @access public
     * @param object $product
     * @param float $price
     * @param int $quantity
     * @return string
     */
    public static function product_get_display_price($product, $price = '', $quantity = 1)
    {
        return RightPress_Helper::wc_version_gte('3.0') ? wc_get_price_to_display($product, array('qty' => $quantity, 'price' => $price)) : $product->get_display_price($price, $quantity);
    }

    /**
     * Get product short description
     * WC 3.0
     *
     * @access public
     * @param object $product
     * @return float
     */
    public static function product_get_short_description($product)
    {
        return RightPress_Helper::wc_version_gte('3.0') ? $product->get_short_description() : $product->post->post_excerpt;
    }

    /**
     * Set product price
     * WC 3.0
     *
     * @access public
     * @param object $product
     * @param float $price
     * @return void
     */
    public static function product_set_price($product, $price)
    {
        if (RightPress_Helper::wc_version_gte('3.0')) {
            $product->set_price($price);
        }
        else {
            $product->price = $price;
        }
    }

    /**
     * Get product meta by key
     * WC 3.0
     *
     * @access public
     * @param object $product
     * @param string $key
     * @param bool $single
     * @param string $context
     * @return mixed
     */
    public static function product_get_meta($product, $key, $single = true, $context = 'view')
    {
        if (RightPress_Helper::wc_version_gte('3.0')) {
            return RightPress_WC_Legacy::fix_meta($product->get_meta($key, $single, $context), $single);
        }
        else {
            return get_post_meta(RightPress_WC_Legacy::product_get_id($product), $key, $single);
        }
    }

    /**
     * Check if product meta exists
     * WC 3.0
     *
     * @access public
     * @param object $product
     * @param string $key
     * @return bool
     */
    public static function product_meta_exists($product, $key)
    {
        if (RightPress_Helper::wc_version_gte('3.0')) {
            return $product->meta_exists($key);
        }
        else {
            return metadata_exists('post', RightPress_WC_Legacy::product_get_id($product), $key);
        }
    }

    /**
     * Add product meta data
     * WC 3.0
     *
     * @access public
     * @param object $product
     * @param string $key
     * @param mixed $value
     * @param bool $unique
     * @return void
     */
    public static function product_add_meta_data($product, $key, $value, $unique = false)
    {
        if (RightPress_Helper::wc_version_gte('3.0')) {
            $product->add_meta_data($key, $value, $unique);
            $product->save();
        }
        else {
            add_post_meta(RightPress_WC_Legacy::product_get_id($product), $key, $value, $unique);
        }
    }

    /**
     * Update product meta data
     * WC 3.0
     *
     * @access public
     * @param object $product
     * @param string $key
     * @param mixed $value
     * @param int $meta_id
     * @return void
     */
    public static function product_update_meta_data($product, $key, $value, $meta_id = '')
    {
        if (RightPress_Helper::wc_version_gte('3.0')) {
            $product->update_meta_data($key, $value, $meta_id);
            $product->save();
        }
        else {
            update_post_meta(RightPress_WC_Legacy::product_get_id($product), $key, $value);
        }
    }

    /**
     * Delete product meta data
     * WC 3.0
     *
     * Note: this does not support deleting meta data by value from a set of meta values with the same key
     *
     * @access public
     * @param object $product
     * @param string $key
     * @return void
     */
    public static function product_delete_meta_data($product, $key)
    {
        if (RightPress_Helper::wc_version_gte('3.0')) {
            $product->delete_meta_data($key);
            $product->save();
        }
        else {
            delete_post_meta(RightPress_WC_Legacy::product_get_id($product), $key);
        }
    }

    /**
     * Get customer billing email
     * WC 3.0
     *
     * @access public
     * @param int $customer_id
     * @return string
     */
    public static function customer_get_billing_email($customer_id)
    {
        if (RightPress_Helper::wc_version_gte('3.0')) {
            $customer = new WC_Customer($customer_id);
            return $customer->get_billing_email();
        }
        else {
            return get_user_meta($customer_id, 'billing_email', true);
        }
    }

    /**
     * Get customer billing phone
     * WC 3.0
     *
     * @access public
     * @param int $customer_id
     * @return string
     */
    public static function customer_get_billing_phone($customer_id)
    {
        if (RightPress_Helper::wc_version_gte('3.0')) {
            $customer = new WC_Customer($customer_id);
            return $customer->get_billing_phone();
        }
        else {
            return get_user_meta($customer_id, 'billing_phone', true);
        }
    }

    /**
     * Get customer meta by key
     * WC 3.0
     *
     * @access public
     * @param int $customer_id
     * @param string $key
     * @param bool $single
     * @param string $context
     * @return mixed
     */
    public static function customer_get_meta($customer_id, $key, $single = true, $context = 'view')
    {
        if (RightPress_Helper::wc_version_gte('3.0')) {
            $customer = new WC_Customer($customer_id);
            return RightPress_WC_Legacy::fix_meta($customer->get_meta($key, $single, $context), $single);
        }
        else {
            return get_user_meta($customer_id, $key, $single);
        }
    }

    /**
     * Check if customer meta exists
     * WC 3.0
     *
     * @access public
     * @param int $customer_id
     * @param string $key
     * @return bool
     */
    public static function customer_meta_exists($customer_id, $key)
    {
        if (RightPress_Helper::wc_version_gte('3.0')) {
            $customer = new WC_Customer($customer_id);
            return $customer->meta_exists($key);
        }
        else {
            return metadata_exists('user', $customer_id, $key);
        }
    }

    /**
     * Add customer meta data
     * WC 3.0
     *
     * @access public
     * @param int $customer_id
     * @param string $key
     * @param mixed $value
     * @param bool $unique
     * @return void
     */
    public static function customer_add_meta_data($customer_id, $key, $value, $unique = false)
    {
        if (RightPress_Helper::wc_version_gte('3.0')) {
            $customer = new WC_Customer($customer_id);
            $customer->add_meta_data($key, $value, $unique);
            $customer->save();
        }
        else {
            add_user_meta($customer_id, $key, $value, $unique);
        }
    }

    /**
     * Update customer meta data
     * WC 3.0
     *
     * @access public
     * @param int $customer_id
     * @param string $key
     * @param mixed $value
     * @param int $meta_id
     * @return void
     */
    public static function customer_update_meta_data($customer_id, $key, $value, $meta_id = '')
    {
        if (RightPress_Helper::wc_version_gte('3.0')) {
            $customer = new WC_Customer($customer_id);
            $customer->update_meta_data($key, $value, $meta_id);
            $customer->save();
        }
        else {
            update_user_meta($customer_id, $key, $value);
        }
    }

    /**
     * Delete customer meta data
     * WC 3.0
     *
     * Note: this does not support deleting meta data by value from a set of meta values with the same key
     *
     * @access public
     * @param int $customer_id
     * @param string $key
     * @return void
     */
    public static function customer_delete_meta_data($customer_id, $key)
    {
        if (RightPress_Helper::wc_version_gte('3.0')) {
            $customer = new WC_Customer($customer_id);
            $customer->delete_meta_data($key);
            $customer->save();
        }
        else {
            delete_user_meta($customer_id, $key);
        }
    }

    /**
     * Get email order items table for display in templates
     * WC 2.5, WC 3.0
     *
     * @access public
     * @param object $order
     * @param array $args
     * @return string
     */
    public static function get_email_order_items($order, $args = array())
    {
        if (RightPress_Helper::wc_version_gte('3.0')) {
            return wc_get_email_order_items($order, $args);
        }
        else if (RightPress_Helper::wc_version_gte('2.5')) {
            return $order->email_order_items_table($args);
        }
        else {
            $default_args = array('show_download_links' => false, 'show_sku' => false, 'show_purchase_note' => false, 'show_image' => false, 'image_size' => array(32, 32), 'plain_text' => false);
            $args = array_merge($default_args, $args);
            return call_user_func_array(array($order, 'email_order_items_table'), $args);
        }
    }

    /**
     * Display item meta
     * WC 3.0
     *
     * @access public
     * @param mixed $item
     * @param object $product
     * @param bool $flat
     * @param bool $return
     * @param array $args
     * @return mixed
     */
    public static function wc_display_item_meta($item, $product = null, $flat = false, $return = false, $args = array())
    {
        if (RightPress_Helper::wc_version_gte('3.0')) {

            // Flat config
            if ($flat) {
                $args = array_merge($args, array(
                    'before'    => '',
                    'separator' => ', ',
                    'after'     => '',
                    'autop'     => false,
                ));
            }

            // Return vs print flag
            $args['echo'] = !$return;

            // Get display meta and strip tags
            return strip_tags(wc_display_item_meta($item, $args));
        }
        else {
            $item_meta = new WC_Order_Item_Meta($item, $product);
            $delimiter = isset($args['separator']) ? $args['separator'] : ", \n";
            return $item_meta->display($flat, $return, '_', $delimiter);
        }
    }

    /**
     * Fix meta in case multiple values are present in WC 3.0+
     *
     * @access public
     * @param mixed $meta
     * @param bool $single
     * @return mixed
     */
    public static function fix_meta($meta, $single)
    {
        // Nothing to fix
        if ($single || empty($meta) || !is_array($meta)) {
            return $meta;
        }

        $fixed = array();

        foreach ($meta as $meta_id => $meta_value) {

            // Something is wrong, bail
            if (!is_a($meta_value, 'WC_Meta_Data')) {
                return $meta;
            }

            $fixed[] = $meta_value->value;
        }

        return $fixed;
    }

    /**
     * Get shipping method title
     *
     * @access public
     * @param object $shipping_method
     * @return string
     */
    public static function shipping_method_get_method_title($shipping_method)
    {
        return RightPress_Helper::wc_version_gte('2.6') ? $shipping_method->get_method_title() : $shipping_method->get_title();
    }

    /**
     * Get WooCommerce shipping zone id
     *
     * @access public
     * @param object $shipping_zone
     * @return int
     */
    public static function shipping_zone_get_id($shipping_zone)
    {
        return RightPress_Helper::wc_version_gte('3.0') ? $shipping_zone->get_id() : $shipping_zone->get_zone_id();
    }

    /**
     * Display order item downloads
     *
     * @access public
     * @param mixed $item
     * @param object $order
     * @return void
     */
    public static function display_item_downloads($item, $order = null)
    {
        if (RightPress_Helper::wc_version_gte('3.0')) {
            wc_display_item_downloads($item);
        }
        else {
            $order->display_item_downloads($item);
        }
    }

    /**
     * Get coupon code
     *
     * @access public
     * @param object $coupon
     * @return string
     */
    public static function coupon_get_code($coupon)
    {
        return RightPress_Helper::wc_version_gte('3.0') ? $coupon->get_code() : $coupon->code;
    }




}
}
