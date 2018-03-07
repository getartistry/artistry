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

global $rightpress_wc_meta_version;

if (!$rightpress_wc_meta_version || $rightpress_wc_meta_version < $version) {
    $rightpress_wc_meta_version = $version;
}

/**
 * Proxy Class
 */
if (!class_exists('RightPress_WC_Meta')) {

final class RightPress_WC_Meta
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
        global $rightpress_wc_meta_version;

        // Get main class name
        $class_name = 'RightPress_WC_Meta_' . $rightpress_wc_meta_version;

        // Call main class
        return call_user_func_array(array($class_name, $method_name), $arguments);
    }
}
}

/**
 * Main Class
 */
if (!class_exists('RightPress_WC_Meta_16')) {

final class RightPress_WC_Meta_16
{

    /**
     * Check if WooCommerce customer meta exists
     *
     * @access public
     * @param mixed $customer
     * @param string $key
     * @return bool
     */
    public static function customer_meta_exists($customer, $key)
    {
        if (RightPress_Helper::wc_version_gte('3.0')) {
            $customer = is_object($customer) ? $customer : RightPress_Helper::wc_get_customer($customer);
            return $customer ? $customer->meta_exists($key) : false;
        }
        else {
            return RightPress_Helper::user_meta_key_exists($customer, $key);
        }
    }

    /**
     * Get WooCommerce customer meta
     *
     * @access public
     * @param mixed $customer
     * @param string $key
     * @param bool $single
     * @param string $context
     * @return mixed
     */
    public static function customer_get_meta($customer, $key, $single = true, $context = 'view')
    {
        if (RightPress_Helper::wc_version_gte('3.0')) {
            $customer = is_object($customer) ? $customer : RightPress_Helper::wc_get_customer($customer);
            return $customer ? $customer->get_meta($key, $single, $context) : false;
        }
        else {
            return get_user_meta($customer, $key, $single);
        }
    }

    /**
     * Add WooCommerce customer meta
     * Note: If object is passed in, we assume that the calling method will handle save()
     *
     * @access public
     * @param mixed $customer
     * @param string $key
     * @param mixed $value
     * @param bool $unique
     * @return void
     */
    public static function customer_add_meta_data($customer, $key, $value, $unique = false)
    {
        if (RightPress_Helper::wc_version_gte('3.0')) {

            if (is_object($customer)) {
                $customer->add_meta_data($key, $value, $unique);
            }
            else if ($customer = RightPress_Helper::wc_get_customer($customer)) {
                $customer->add_meta_data($key, $value, $unique);
                $customer->save();
            }
        }
        else {
            add_user_meta($customer, $key, $value, $unique);
        }
    }

    /**
     * Update WooCommerce customer meta
     * Note: If object is passed in, we assume that the calling method will handle save()
     *
     * @access public
     * @param mixed $customer
     * @param string $key
     * @param mixed $value
     * @param int $meta_id
     * @return void
     */
    public static function customer_update_meta_data($customer, $key, $value, $meta_id = '')
    {
        if (RightPress_Helper::wc_version_gte('3.0')) {

            if (is_object($customer)) {
                $customer->update_meta_data($key, $value, $meta_id);
            }
            else if ($customer = RightPress_Helper::wc_get_customer($customer)) {
                $customer->update_meta_data($key, $value, $meta_id);
                $customer->save();
            }
        }
        else {
            update_user_meta($customer, $key, $value);
        }
    }

    /**
     * Delete WooCommerce customer meta
     * Note: If object is passed in, we assume that the calling method will handle save()
     *
     * @access public
     * @param mixed $customer
     * @param string $key
     * @return void
     */
    public static function customer_delete_meta_data($customer, $key)
    {
        if (RightPress_Helper::wc_version_gte('3.0')) {

            if (is_object($customer)) {
                $customer->delete_meta_data($key);
            }
            else if ($customer = RightPress_Helper::wc_get_customer($customer)) {
                $customer->delete_meta_data($key);
                $customer->save();
            }
        }
        else {
            delete_user_meta($customer, $key);
        }
    }

    /**
     * Check if WooCommerce order meta exists
     *
     * @access public
     * @param mixed $order
     * @param string $key
     * @return bool
     */
    public static function order_meta_exists($order, $key)
    {
        if (RightPress_Helper::wc_version_gte('3.0')) {
            $order = is_object($order) ? $order : RightPress_Helper::wc_get_order($order);
            return $order ? $order->meta_exists($key) : false;
        }
        else {
            return RightPress_Helper::post_meta_key_exists($order, $key);
        }
    }

    /**
     * Get WooCommerce order meta
     *
     * @access public
     * @param mixed $order
     * @param string $key
     * @param bool $single
     * @param string $context
     * @return mixed
     */
    public static function order_get_meta($order, $key, $single = true, $context = 'view')
    {
        if (RightPress_Helper::wc_version_gte('3.0')) {
            $order = is_object($order) ? $order : RightPress_Helper::wc_get_order($order);
            return $order ? $order->get_meta($key, $single, $context) : false;
        }
        else {
            return get_post_meta($order, $key, $single);
        }
    }

    /**
     * Add WooCommerce order meta
     * Note: If object is passed in, we assume that the calling method will handle save()
     *
     * @access public
     * @param mixed $order
     * @param string $key
     * @param mixed $value
     * @param bool $unique
     * @return void
     */
    public static function order_add_meta_data($order, $key, $value, $unique = false)
    {
        if (RightPress_Helper::wc_version_gte('3.0')) {

            if (is_object($order)) {
                $order->add_meta_data($key, $value, $unique);
            }
            else if ($order = RightPress_Helper::wc_get_order($order)) {
                $order->add_meta_data($key, $value, $unique);
                $order->save();
            }
        }
        else {
            add_post_meta($order, $key, $value, $unique);
        }
    }

    /**
     * Update WooCommerce order meta
     * Note: If object is passed in, we assume that the calling method will handle save()
     *
     * @access public
     * @param mixed $order
     * @param string $key
     * @param mixed $value
     * @param int $meta_id
     * @return void
     */
    public static function order_update_meta_data($order, $key, $value, $meta_id = '')
    {
        if (RightPress_Helper::wc_version_gte('3.0')) {

            if (is_object($order)) {
                $order->update_meta_data($key, $value, $meta_id);
            }
            else if ($order = RightPress_Helper::wc_get_order($order)) {
                $order->update_meta_data($key, $value, $meta_id);
                $order->save();
            }
        }
        else {
            update_post_meta($order, $key, $value);
        }
    }

    /**
     * Delete WooCommerce order meta
     * Note: If object is passed in, we assume that the calling method will handle save()
     *
     * @access public
     * @param mixed $order
     * @param string $key
     * @return void
     */
    public static function order_delete_meta_data($order, $key)
    {
        if (RightPress_Helper::wc_version_gte('3.0')) {

            if (is_object($order)) {
                $order->delete_meta_data($key);
            }
            else if ($order = RightPress_Helper::wc_get_order($order)) {
                $order->delete_meta_data($key);
                $order->save();
            }
        }
        else {
            delete_post_meta($order, $key);
        }
    }

    /**
     * Check if WooCommerce order item meta exists
     *
     * @access public
     * @param mixed $order_item
     * @param string $key
     * @return bool
     */
    public static function order_item_meta_exists($order_item, $key)
    {
        if (RightPress_Helper::wc_version_gte('3.0')) {
            $order_item = is_object($order_item) ? $order_item : RightPress_Helper::wc_get_order_item($order_item);
            return $order_item ? $order_item->meta_exists($key) : false;
        }
        else {
            return RightPress_Helper::order_item_meta_key_exists($order_item, $key);
        }
    }

    /**
     * Get WooCommerce order item meta
     *
     * @access public
     * @param mixed $order_item
     * @param string $key
     * @param bool $single
     * @param string $context
     * @return mixed
     */
    public static function order_item_get_meta($order_item, $key, $single = true, $context = 'view')
    {
        if (RightPress_Helper::wc_version_gte('3.0')) {
            $order_item = is_object($order_item) ? $order_item : RightPress_Helper::wc_get_order_item($order_item);
            return $order_item ? $order_item->get_meta($key, $single, $context) : false;
        }
        else {
            return wc_get_order_item_meta($order_item, $key, $single);
        }
    }

    /**
     * Add WooCommerce order item meta
     * Note: If object is passed in, we assume that the calling method will handle save()
     *
     * @access public
     * @param mixed $order_item
     * @param string $key
     * @param mixed $value
     * @param bool $unique
     * @return void
     */
    public static function order_item_add_meta_data($order_item, $key, $value, $unique = false)
    {
        if (RightPress_Helper::wc_version_gte('3.0')) {

            if (is_object($order_item)) {
                $order_item->add_meta_data($key, $value, $unique);
            }
            else if ($order_item = RightPress_Helper::wc_get_order_item($order_item)) {
                $order_item->add_meta_data($key, $value, $unique);
                $order_item->save();
            }
        }
        else {
            wc_add_order_item_meta($order_item, $key, $value, $unique);
        }
    }

    /**
     * Update WooCommerce order item meta
     * Note: If object is passed in, we assume that the calling method will handle save()
     *
     * @access public
     * @param mixed $order_item
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public static function order_item_update_meta_data($order_item, $key, $value)
    {
        if (RightPress_Helper::wc_version_gte('3.0')) {

            if (is_object($order_item)) {
                $order_item->update_meta_data($key, $value);
            }
            else if ($order_item = RightPress_Helper::wc_get_order_item($order_item)) {
                $order_item->update_meta_data($key, $value);
                $order_item->save();
            }
        }
        else {
            wc_update_order_item_meta($order_item, $key, $value);
        }
    }

    /**
     * Delete WooCommerce order item meta
     * Note: If object is passed in, we assume that the calling method will handle save()
     *
     * @access public
     * @param mixed $order_item
     * @param string $key
     * @return void
     */
    public static function order_item_delete_meta_data($order_item, $key)
    {
        if (RightPress_Helper::wc_version_gte('3.0')) {

            if (is_object($order_item)) {
                $order_item->delete_meta_data($key);
            }
            else if ($order_item = RightPress_Helper::wc_get_order_item($order_item)) {
                $order_item->delete_meta_data($key);
                $order_item->save();
            }
        }
        else {
            wc_delete_order_item_meta($order_item, $key);
        }
    }

    /**
     * Check if WooCommerce product meta exists
     *
     * @access public
     * @param mixed $product
     * @param string $key
     * @return bool
     */
    public static function product_meta_exists($product, $key)
    {
        if (RightPress_Helper::wc_version_gte('3.0')) {
            $product = is_object($product) ? $product : RightPress_Helper::wc_get_product($product);
            return $product ? $product->meta_exists($key) : false;
        }
        else {
            return RightPress_Helper::post_meta_key_exists($product, $key);
        }
    }

    /**
     * Get WooCommerce product meta
     *
     * @access public
     * @param mixed $product
     * @param string $key
     * @param bool $single
     * @param string $context
     * @return mixed
     */
    public static function product_get_meta($product, $key, $single = true, $context = 'view')
    {
        if (RightPress_Helper::wc_version_gte('3.0')) {
            $product = is_object($product) ? $product : RightPress_Helper::wc_get_product($product);
            return $product ? $product->get_meta($key, $single, $context) : false;
        }
        else {
            return get_post_meta($product, $key, $single);
        }
    }

    /**
     * Add WooCommerce product meta
     * Note: If object is passed in, we assume that the calling method will handle save()
     *
     * @access public
     * @param mixed $product
     * @param string $key
     * @param mixed $value
     * @param bool $unique
     * @return void
     */
    public static function product_add_meta_data($product, $key, $value, $unique = false)
    {
        if (RightPress_Helper::wc_version_gte('3.0')) {

            if (is_object($product)) {
                $product->add_meta_data($key, $value, $unique);
            }
            else if ($product = RightPress_Helper::wc_get_product($product)) {
                $product->add_meta_data($key, $value, $unique);
                $product->save();
            }
        }
        else {
            add_post_meta($product, $key, $value, $unique);
        }
    }

    /**
     * Update WooCommerce product meta
     * Note: If object is passed in, we assume that the calling method will handle save()
     *
     * @access public
     * @param mixed $product
     * @param string $key
     * @param mixed $value
     * @param int $meta_id
     * @return void
     */
    public static function product_update_meta_data($product, $key, $value, $meta_id = '')
    {
        if (RightPress_Helper::wc_version_gte('3.0')) {

            if (is_object($product)) {
                $product->update_meta_data($key, $value, $meta_id);
            }
            else if ($product = RightPress_Helper::wc_get_product($product)) {
                $product->update_meta_data($key, $value, $meta_id);
                $product->save();
            }
        }
        else {
            update_post_meta($product, $key, $value);
        }
    }

    /**
     * Delete WooCommerce product meta
     * Note: If object is passed in, we assume that the calling method will handle save()
     *
     * @access public
     * @param mixed $product
     * @param string $key
     * @return void
     */
    public static function product_delete_meta_data($product, $key)
    {
        if (RightPress_Helper::wc_version_gte('3.0')) {

            if (is_object($product)) {
                $product->delete_meta_data($key);
            }
            else if ($product = RightPress_Helper::wc_get_product($product)) {
                $product->delete_meta_data($key);
                $product->save();
            }
        }
        else {
            delete_post_meta($product, $key);
        }
    }

    /**
     * Normalize meta array
     *
     * Turns WC 3.0 style meta data (containing objects) to regular WP post meta format
     * Unwraps meta in all WC versions
     *
     * @access public
     * @param array $meta_data
     * @return array
     */
    public static function normalize_meta_data($meta_data)
    {
        if (RightPress_Helper::wc_version_gte('3.0')) {

            $normalized = array();

            foreach ($meta_data as $meta) {
                $normalized[$meta->key][] = $meta->value;
            }

            $meta_data = $normalized;
        }

        return RightPress_Helper::unwrap_post_meta($meta_data);
    }







}
}
