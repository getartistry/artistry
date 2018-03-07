<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Methods related to WooShip shipping method pricing calculations
 *
 * @class WooShip_Pricing
 * @package WooShip
 * @author RightPress
 */
if (!class_exists('WooShip_Pricing')) {

class WooShip_Pricing
{
    private static $methods;
    private static $charge_subjects;

    /**
     * Constructor class
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        // Make sure some actions run after all classes are initiated
        add_action('init', array($this, 'on_init'));
    }

    /**
     * On init action
     *
     * @access public
     * @return void
     */
    public function on_init()
    {
        // Define price calculation methods
        self::$methods = array(
            'fixed_amount'          => self::get_currency_symbol(),
            'subtotal_percentage'   => '%',
        );

        // Define charge subjects
        self::$charge_subjects = array(
            'product'           => __('Product', 'wooship'),
            'product_category'  => __('Product category', 'wooship'),
            'cart_line_item'    => __('Cart line item', 'wooship'),
            'quantity_unit'     => __('Quantity unit', 'wooship'),
            'shipping_class'    => __('Shipping class', 'wooship'),
            'cart'              => __('Cart', 'wooship'),
        );
    }

    /**
     * Get price methods
     *
     * @access public
     * @param bool $include_percentage
     * @return array
     */
    public static function get_methods($include_percentage = true)
    {
        $methods = self::$methods;

        // Possibly exclude percentage
        if (!$include_percentage) {
            unset($methods['subtotal_percentage']);
        }

        return $methods;
    }

    /**
     * Get currency symbol
     *
     * @access public
     * @return string
     */
    public static function get_currency_symbol()
    {
        return get_woocommerce_currency_symbol();
    }

    /**
     * Get weight unit
     *
     * @access public
     * @return string
     */
    public static function get_weight_unit()
    {
        return get_option('woocommerce_weight_unit');
    }

    /**
     * Maybe adjust weight - round up to quantity unit
     *
     * @access public
     * @param float $weight
     * @return float
     */
    public static function maybe_adjust_weight($weight)
    {
        // Round weight up to nearest multiple of multiplier
        if (WooShip::opt('weight_rounding')) {
            $multiplier = WooShip::opt('weight_multiplier');
            return ceil($weight / $multiplier) * $multiplier;
        }

        return $weight;
    }

    /**
     * Get charge subject list
     *
     * @access public
     * @return array
     */
    public static function get_charge_subjects()
    {
        return self::$charge_subjects;
    }

    /**
     * Get shipping method cost
     *
     * @access public
     * @param array $method
     * @param array $package (currently ignored, cart is used directly)
     * @param float $additional_cost
     * @param string $based_on
     * @return float
     */
    public static function get_shipping_method_cost($method, $package, $additional_cost = 0, $based_on = 'incl')
    {
        global $woocommerce;

        // Get properties
        $subtotal = $based_on === 'incl' ? $woocommerce->cart->subtotal : $woocommerce->cart->subtotal_ex_tax;
        $weight = self::maybe_adjust_weight($woocommerce->cart->cart_contents_weight);
        $sum_of_quantities = $woocommerce->cart->cart_contents_count;

        // Get and return cost
        return self::get_cost_or_charge($method, $subtotal, $weight, $sum_of_quantities, $additional_cost);
    }

    /**
     * Get single additional charge amount
     *
     * @access public
     * @param array $charge
     * @param array $subset
     * @param bool $subset_is_cart
     * @param string $based_on
     * @return float
     */
    public static function get_additional_charge_amount($charge, $subset, $subset_is_cart, $based_on)
    {
        global $woocommerce;

        // Get subtotal
        $subtotal = WooShip_Conditions::get_subset_property($subset, 'subtotal', $based_on);

        // Get weight
        $weight = WooShip_Conditions::get_subset_property($subset, 'weight', $based_on);
        $weight = self::maybe_adjust_weight($weight);

        // Get sum of quantities
        $sum_of_quantities = WooShip_Conditions::get_subset_property($subset, 'sum_of_quantities', $based_on);

        // Get and return cost
        return self::get_cost_or_charge($charge, $subtotal, $weight, $sum_of_quantities);
    }

    /**
     * Get shipping method cost or additional charge
     *
     * @access public
     * @param array $method
     * @param float $subtotal
     * @param float $subtotal_ex_tax
     * @param float $weight
     * @param int $sum_of_quantities
     * @param string $based_on
     * @param float $additional_cost
     * @return float
     */
    public static function get_cost_or_charge($method, $subtotal, $weight, $sum_of_quantities, $additional_cost = 0)
    {
        $cost = 0;

        // Shipping cost and handling fee
        foreach (array('shipping_cost', 'handling_fee') as $context) {
            if (!empty($method[$context])) {
                if ($method[$context . '_method'] === 'fixed_amount') {
                    $cost += wc_format_decimal($method[$context]);
                }
                else {
                    $cost += wc_format_decimal($subtotal * $method[$context] / 100);
                }
            }
        }

        // Weight cost
        if (!empty($method['weight_cost'])) {
            $cost += wc_format_decimal($weight / WooShip::opt('weight_multiplier') * $method['weight_cost']);
        }

        // Item cost
        if (!empty($method['item_cost'])) {
            $cost += wc_format_decimal($sum_of_quantities * $method['item_cost']);
        }

        return wc_format_decimal($cost + $additional_cost);
    }

    /**
     * Get additional charges total amount
     *
     * @access public
     * @param array $package (currencly ignored, cart is used directly)
     * @param string $based_on
     * @return float
     */
    public static function get_additional_charges_total_amount($package, $based_on)
    {
        $amount = 0;

        // Iterate over charges and add amount to total amount
        foreach (self::get_charges($package, $based_on) as $charge) {
            $amount += $charge['amount'];
        }

        return $amount;
    }

    /**
     * Get additional charges
     *
     * @access public
     * @param array $package
     * @param string $based_on
     * @return array
     */
    public static function get_charges($package, $based_on)
    {
        $charges = array();

        // Retrieve options
        $wooship = WooShip::get_instance();

        // Iterate over all additional charges
        if (!empty($wooship->config['additional_charges']) && is_array($wooship->config['additional_charges'])) {
            foreach ($wooship->config['additional_charges'] as $charge) {

                // Store subsets
                $subsets = array();

                // Divide cart into subsets when charging per product
                if ($charge['charge_subject'] === 'product') {

                    // Iterate over package contents
                    foreach ($package['contents'] as $cart_item_key => $cart_item) {

                        // Push to subsets
                        $subsets[$cart_item['product_id']][$cart_item_key] = $cart_item;
                    }
                }

                // Divide cart into subsets when charging per product category
                if ($charge['charge_subject'] === 'product_category') {

                    // Iterate over package contents
                    foreach ($package['contents'] as $cart_item_key => $cart_item) {

                        // Get categories that this item is assigned to
                        // WC31: Product categories may no longer be post terms
                        $cart_item_categories = wp_get_post_terms($cart_item['product_id'], 'product_cat');

                        // Iterate over cart item categories
                        if (!is_wp_error($cart_item_categories)) {
                            foreach ($cart_item_categories as $category) {

                                // Push first category to subsets and continue to next cart item
                                $subsets[$category->term_id][$cart_item_key] = $cart_item;
                                break;
                            }
                        }
                    }
                }

                // Divide cart into subsets when charging per cart line item
                if ($charge['charge_subject'] === 'cart_line_item') {

                    // Iterate over package contents
                    foreach ($package['contents'] as $cart_item_key => $cart_item) {

                        // Push to subsets
                        $subsets[$cart_item_key][$cart_item_key] = $cart_item;
                    }
                }

                // Divide cart into subsets when charging per quantity unit
                if ($charge['charge_subject'] === 'quantity_unit') {

                    // Iterate over package contents
                    foreach ($package['contents'] as $cart_item_key => $cart_item) {

                        // Loop over quantity units
                        for ($i = 0; $i < ((int) $cart_item['quantity']); $i++) {

                            // Push to subsets
                            $subsets[$cart_item_key . '_' . $i][$cart_item_key] = $cart_item;
                        }
                    }
                }

                // Divide cart into subsets when charging per shipping class
                if ($charge['charge_subject'] === 'shipping_class') {

                    // Iterate over package contents
                    foreach ($package['contents'] as $cart_item_key => $cart_item) {

                        $product_id = $cart_item['product_id'];
                        $variation_id = !empty($cart_item['variation_id']) ? $cart_item['variation_id'] : null;

                        // Get shipping class id
                        $shipping_class_id = RightPress_Helper::get_wc_product_shipping_class_id($product_id, $variation_id);
                        $shipping_class_id = $shipping_class_id ? $shipping_class_id : 'no_class';

                        // Push to subsets
                        $subsets[$shipping_class_id][$cart_item_key] = $cart_item;
                    }
                }

                // Divide cart into subsets when charging per cart
                if ($charge['charge_subject'] === 'cart') {
                    $subsets['cart'] = $package['contents'];
                }

                // Check if single subset matches cart
                $subset_is_cart = (count($subsets) < 2);

                // Iterate over subsets
                foreach ($subsets as $subset) {

                    // Check if charge matches conditions
                    if (WooShip_Conditions::charge_matches_conditions($charge, $package, $based_on, $subset, $subset_is_cart)) {

                        // Push charge
                        $charges[] = array(
                            'amount' => self::get_additional_charge_amount($charge, $subset, $subset_is_cart, $based_on),
                        );
                    }
                }
            }
        }

        return $charges;
    }

}

new WooShip_Pricing();

}
