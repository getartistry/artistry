<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * WooShip shipping method class
 *
 * @class WC_Shipping_WooShip
 * @package WooShip
 * @author RightPress
 */
if (!class_exists('WC_Shipping_WooShip') && class_exists('WC_Shipping_Method')) {

class WC_Shipping_WooShip extends WC_Shipping_Method
{

    /**
     * Class constructor
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        // Basic shipping method properties
        $this->id                   = 'wooship';
        $this->method_title         = __('Conditional Shipping', 'wooship');
        $this->method_description   = __('Powerful yet easy to use WooCommerce shipping plugin.', 'wooship');

        // Display custom options fields
        add_action('woocommerce_settings_shipping', 'WC_Shipping_WooShip::display_advanced_options', 11);

        // Send view templates to browser
        add_action('admin_footer', 'WC_Shipping_WooShip::output_templates');

        // Options saving callback
        add_action('woocommerce_update_options_shipping_' . $this->id, array($this, 'process_admin_options'));
        add_action('woocommerce_update_options_shipping_' . $this->id, array($this, 'process_wooship_options'));

        // Load settings
        $this->load_form_fields();
        $this->init_settings();

        // Load option values
        $this->title                = $this->method_title;
        $this->selection_method     = $this->get_option('selection_method');
        $this->based_on             = $this->get_option('based_on');
        $this->tax_status           = $this->get_option('tax_status');
        $this->charges_include_tax  = $this->get_option('charges_include_tax') === 'yes';
    }

    /**
     * Load shipping method settings html
     *
     * @access public
     * @return void
     */
    public function load_form_fields()
    {
        $this->form_fields = include WOOSHIP_PLUGIN_PATH . 'includes/form_fields.inc.php';
    }

    /**
     * Display advanced options
     *
     * @access public
     * @return void
     */
    public static function display_advanced_options()
    {
        if (WooShip::is_settings_page()) {
            include_once WOOSHIP_PLUGIN_PATH . 'includes/views/shipping/advanced_options.php';
        }
    }

    /**
     * Output templates for view
     *
     * @access public
     * @return void
     */
    public static function output_templates()
    {
        if (WooShip::is_settings_page()) {
            include_once WOOSHIP_PLUGIN_PATH . 'includes/views/shipping/advanced_options_templates.php';
        }
    }

    /**
     * Process and save custom options
     *
     * @access public
     * @return void
     */
    public function process_wooship_options()
    {
        WooShip_Options::process();
    }

    /**
     * Calculate shipping for a single package
     *
     * @access public
     * @param array $package
     * @return void
     */
    public function calculate_shipping($package = array())
    {
        $this->rates = array();

        // Add matching shipping methods with additional charges
        foreach ($this->get_rates($package) as $rate) {
            $this->add_rate($rate);
        }
    }

    /**
     * Get rates, i.e. matching shipping methods with additional charges for display in frontend
     *
     * @access public
     * @param array $package
     * @return array
     */
    public function get_rates($package)
    {
        $rates = array();

        // Get total amount of all additional charges
        $additional_charges_amount = WooShip_Pricing::get_additional_charges_total_amount($package, $this->based_on);

        // Retrieve options
        $wooship = WooShip::get_instance();

        // Iterate over shipping methods
        if (!empty($wooship->config['shipping_methods']) && is_array($wooship->config['shipping_methods'])) {
            foreach ($wooship->config['shipping_methods'] as $method_key => $method) {
                if (WooShip_Conditions::method_matches_conditions($method, $package, $this->based_on)) {

                    // Get total shipping cost
                    $total_shipping_cost = (float) WooShip_Pricing::get_shipping_method_cost($method, $package, $additional_charges_amount, $this->based_on);

                    // Get shipping taxes
                    $shipping_taxes = $this->get_shipping_taxes($total_shipping_cost);

                    // Get shipping cost
                    if (is_array($shipping_taxes)) {
                        $shipping_cost = $total_shipping_cost - array_sum($shipping_taxes);
                    }
                    else {
                        $shipping_cost = $total_shipping_cost;
                    }

                    // Add rate
                    $rates[] = array(
                        'id'                    => $this->id . '_' . $method_key,
                        'label'                 => $method['title'],
                        'cost'                  => $shipping_cost,
                        'taxes'                 => $shipping_taxes,
                        'wooship_total_cost'    => $total_shipping_cost,
                    );

                    // First in a row selected automatically?
                    if ($this->selection_method === 'first') {
                        return $rates;
                    }
                }
            }
        }

        // Cheapest or most expensive rate only?
        if (in_array($this->selection_method, array('cheapest', 'most_expensive'))) {
            $sorted = self::sort_shipping_rates($rates);
            $selected = $this->selection_method === 'cheapest' ? array_shift($sorted) : array_pop($sorted);
            $rates = array($selected);
        }

        return $rates;
    }

    /**
     * Sort shipping rates
     *
     * @access public
     * @param array $shipping_rates
     * @return array
     */
    public static function sort_shipping_rates($shipping_rates)
    {
        // Do not sort less than two items
        if (count($shipping_rates) < 2) {
            return empty($shipping_rates) ? array() : $shipping_rates;
        }

        // Sort and return value
        usort($shipping_rates, array('WC_Shipping_WooShip', 'sort_shipping_rates_comparison'));
        return $shipping_rates;
    }

    /**
     * Comparison function for sort shipping rates
     *
     * @access public
     * @param object $a
     * @param object $b
     * @return array
     */
    public static function sort_shipping_rates_comparison($a, $b)
    {
        if ($a['wooship_total_cost'] < $b['wooship_total_cost']) {
            return -1;
        }
        else if ($a['wooship_total_cost'] > $b['wooship_total_cost']) {
            return 1;
        }
        else {
            return 0;
        }
    }

    /**
     * Get shipping taxes
     *
     * @access public
     * @param float $shipping_cost
     * @return mixed
     */
    public function get_shipping_taxes($shipping_cost)
    {
        // Shipping is not taxable
        if ($this->tax_status === 'none') {
            return false;
        }

        // Calculate tax if charges are entered inclusive of tax
        if ($this->charges_include_tax && $this->is_taxable() && $shipping_cost > 0) {
            return WC_Tax::calc_inclusive_tax($shipping_cost, WC_Tax::get_shipping_tax_rates());
        }

        // Let WooCommerce handle shipping tax calculation
        return '';
    }

}

}
