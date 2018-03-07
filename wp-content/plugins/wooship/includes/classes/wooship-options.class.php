<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Methods related to WooShip options
 *
 * @class WooShip_Options
 * @package WooShip
 * @author RightPress
 */
if (!class_exists('WooShip_Options')) {

class WooShip_Options
{
    private static $consistency_issue = false;
    private static $option_types = array(
        'shipping_methods',
        'additional_charges',
        'shipping_zones',
    );
    private static $multiselect_field_keys = array(
        'coupons', 'shipping_classes', 'products', 'product_categories',
        'attributes', 'tags', 'shipping_zones', 'countries', 'states', 'roles',
        'capabilities', 'users'
    );

    /**
     * Constructor class
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        add_action('init', array($this, 'consistency_check'));
    }

    /**
     * Validate and save custom shipping method options
     *
     * @access public
     * @return void
     */
    public static function process()
    {
        // Store options
        $options = array();

        // Handle shipping zones differently
        $shipping_zones = array();
        $i = 1;

        // Iterate over shipping zones
        if (isset($_POST['wooship']) && is_array($_POST['wooship']) && !empty($_POST['wooship'])) {
            if (!empty($_POST['wooship']['shipping_zones']) && is_array($_POST['wooship']['shipping_zones'])) {
                foreach ($_POST['wooship']['shipping_zones'] as $posted) {

                    $current = array();

                    // ID
                    if (!empty($posted['id']) && is_numeric($posted['id'])) {
                        $current['id'] = $posted['id'];
                    }
                    else {
                        $current['id'] = null;
                    }

                    // Zone Title
                    if (!empty($posted['title'])) {
                        $current['title'] = $posted['title'];
                    }
                    else {
                        $current['title'] = __('Shipping Zone', 'wooship') . ' #' . $i;
                    }

                    // Process conditions
                    $conditions = !empty($posted['conditions']) ? $posted['conditions'] : array();
                    $current['conditions'] = self::process_conditions($conditions);

                    // Sort order
                    $current['sort_order'] = $i;

                    // Store current shipping zone
                    $shipping_zones[] = $current;

                    // Increment counter
                    $i++;
                }
            }
        }

        // Save shipping zones
        WooShip_Shipping_Zone::update_shipping_zones($shipping_zones);

        // Process shipping methods and additional charges
        if (isset($_POST['wooship']) && is_array($_POST['wooship']) && !empty($_POST['wooship'])) {

            // Iterate over available option types
            foreach (self::$option_types as $option_type) {

                // Shipping zones have already been processed
                if ($option_type === 'shipping_zones') {
                    continue;
                }

                // Check if any options of this type have been configured
                if (!isset($_POST['wooship'][$option_type])) {
                    continue;
                }

                // Reset counter
                $i = 1;

                // Iterate over options of this type
                foreach ($_POST['wooship'][$option_type] as $posted) {

                    $current = array();

                    // Method Title
                    if (!empty($posted['title'])) {
                        $current['title'] = $posted['title'];
                    }
                    else if ($option_type === 'shipping_methods') {
                        $current['title'] = __('Shipping Method', 'wooship') . ' #' . $i;
                    }
                    else {
                        $current['title'] = '';
                    }

                    // Private Note
                    // Warning: Additional Charge Private Note is actually stored as 'title'!
                    if ($option_type === 'shipping_methods') {
                        $current['note'] = !empty($posted['note']) ? $posted['note'] : '';
                    }

                    // Charge Per
                    if ($option_type === 'additional_charges') {
                        $charge_subjects = array_keys(WooShip_Pricing::get_charge_subjects());
                        $current['charge_subject'] = (!empty($posted['charge_subject']) && in_array($posted['charge_subject'], $charge_subjects)) ? $posted['charge_subject'] : 'shipping_class';
                    }

                    // Charges
                    $charge_methods = array_keys(WooShip_Pricing::get_methods());

                    foreach(array('shipping_cost', 'handling_fee', 'weight_cost', 'item_cost') as $charge_key) {

                        // Charge amount
                        $current[$charge_key] = !empty($posted[$charge_key]) ? preg_replace('/[^0-9.]+/', '', $posted[$charge_key]) : ($posted[$charge_key] === '0' ? '0' : '');

                        // Charge method
                        $current[$charge_key . '_method'] = (!empty($posted[$charge_key . '_method']) && in_array($posted[$charge_key . '_method'], $charge_methods)) ? $posted[$charge_key . '_method'] : 'fixed_amount';
                    }

                    // Process conditions
                    $conditions = !empty($posted['conditions']) ? $posted['conditions'] : array();
                    $current['conditions'] = self::process_conditions($conditions);

                    // Add to main array
                    $options[$option_type][] = $current;

                    // Increase counter
                    $i++;
                }
            }
        }

        // Prepare to process other settings
        $posted = isset($_POST['wooship']) ? $_POST['wooship'] : array();

        // Weight rounding
        if (isset($posted['weight_rounding']) && $posted['weight_rounding']) {
            $options['weight_rounding'] = 1;
        }
        else {
            $options['weight_rounding'] = 0;
        }

        // Weight multiplier
        if (isset($posted['weight_multiplier']) && is_numeric($posted['weight_multiplier'])) {
            $options['weight_multiplier'] = (float) $posted['weight_multiplier'];
        }
        else {
            $options['weight_multiplier'] = 1;
        }

        // Save options
        WooShip::save_config($options, !self::$consistency_issue);
    }

    /**
     * Process conditions
     *
     * @access public
     * @param array $conditions
     * @return mixed
     */
    public static function process_conditions($conditions = array())
    {
        // Get condition types and timeframes
        $condition_types = WooShip_Conditions::conditions();
        $timeframes = WooShip_Conditions::timeframes(true);

        // Store processed conditions
        $current = array();

        if (!empty($conditions) && is_array($conditions)) {

            // Iterate over conditions
            foreach ($conditions as $condition) {

                // Validate and sanitize condition
                if ($processed_condition = self::process_condition($condition, $condition_types, $timeframes)) {
                    $current[] = $processed_condition;
                }
            }
        }

        return $current;
    }

    /**
     * Process single condition
     *
     * @access public
     * @param array $condition
     * @param array $condition_types
     * @param array $timeframes
     * @return mixed
     */
    public static function process_condition($condition, $condition_types, $timeframes)
    {
        $current = array();

        // Type
        if (isset($condition['type']) && ($group_option = WooShip_Conditions::extract_group_and_option($condition['type']))) {

            // Extract group key and option key
            list($group_key, $option_key) = $group_option;

            // Check if such keys exist
            if (isset($condition_types[$group_key]) && isset($condition_types[$group_key]['options'][$option_key])) {
                $current['type'] = $condition['type'];
            }
            else {
                return false;
            }
        }

        // Method
        $method_key = $current['type'] . '_method';

        if (isset($condition[$method_key])) {

            // Get all condition methods for current condition
            $condition_methods = WooShip_Conditions::methods($group_key, $option_key);

            // Check if selected condition method exists
            if (isset($condition_methods[$condition[$method_key]])) {
                $current[$method_key] = $condition[$method_key];
            }
            else {
                return false;
            }
        }
        else {
            return false;
        }

        // Text
        if (WooShip_Conditions::uses_field($group_key, $option_key, 'text')) {
            if (!empty($condition['text']) || $condition['text'] === '0') {
                $current['text'] = $condition['text'];
            }
            else {
                $current['text'] = '';
            }
        }

        // Decimal
        if (WooShip_Conditions::uses_field($group_key, $option_key, 'decimal')) {
            if (isset($condition['decimal']) && is_string($condition['decimal'])) {
                $current['decimal'] = preg_replace('/[^0-9.]+/', '', $condition['decimal']);
            }
            else {
                $current['decimal'] = '';
            }
        }

        // Number
        if (WooShip_Conditions::uses_field($group_key, $option_key, 'number')) {
            if (isset($condition['number']) && is_string($condition['number'])) {
                $current['number'] = preg_replace('/[^0-9]+/', '', $condition['number']);
            }
            else {
                $current['number'] = '';
            }
        }

        // Meta key
        if (WooShip_Conditions::uses_field($group_key, $option_key, 'meta_key')) {
            if (isset($condition['meta_key']) && is_string($condition['meta_key'])) {
                $current['meta_key'] = $condition['meta_key'];
            }
            else {
                $current['meta_key'] = '';
            }
        }

        // Timeframe
        if (WooShip_Conditions::uses_field($group_key, $option_key, 'timeframe') || WooShip_Conditions::uses_field($group_key, $option_key, 'timeframe_all_time')) {

            // Check if selected timeframe exists
            if (isset($condition['timeframe']) && isset($timeframes[$condition['timeframe']])) {
                $current['timeframe'] = $condition['timeframe'];
            }
            else {
                return false;
            }
        }

        // Multiselect fields
        foreach (self::$multiselect_field_keys as $multiselect_field) {
            if (WooShip_Conditions::uses_field($group_key, $option_key, $multiselect_field)) {
                if (isset($condition[$multiselect_field])) {
                    $current[$multiselect_field] = (array) $condition[$multiselect_field];
                }
                else {
                    $current[$multiselect_field] = array();
                }
            }
        }

        return !empty($current) ? $current : false;
    }

    /**
     * Consistency check to detect
     *
     * @access public
     * @return void
     */
    public function consistency_check()
    {
        // Not saving options?
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['wooship']) || !is_array($_POST['wooship']) || empty($_POST['wooship'])) {
            return;
        }

        // Iterate over option types
        foreach (self::$option_types as $option_type) {
            if (!empty($_POST['wooship'][$option_type]) && is_array($_POST['wooship'][$option_type])) {
                foreach ($_POST['wooship'][$option_type] as $item) {
                    if (($option_type !== 'shipping_zones' && !isset($item['item_cost_method'])) || ($option_type === 'shipping_zones' && !isset($item['id']))) {
                        add_action('admin_notices', array($this, 'display_consistency_notice'));
                        self::$consistency_issue = true;
                        return;
                    }
                }
            }
        }
    }

    /**
     * Display consistency notice
     *
     * @access public
     * @return void
     */
    public function display_consistency_notice()
    {
        echo '<div class="error"><p>' . __('<strong>WARNING!</strong> WooShip is unable to save more data due to max_input_vars limit in your PHP configuration. Contact your server administrator to increase this limit so you can add new methods, charges, zones or conditions. Please reload this page in any way as it does not contain your latest configuration.', 'wooship') . '</p></div>';
    }

    /**
     * Get option types
     *
     * @access public
     * @return array
     */
    public static function get_option_types()
    {
        return self::$option_types;
    }

    /**
     * Get multiselect fields
     *
     * @access public
     * @return array
     */
    public static function get_multiselect_field_keys()
    {
        return self::$multiselect_field_keys;
    }

}

new WooShip_Options();

}
