<?php

/**
 * WooShip shipping method form field configuration
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * WooCommerce Settings field types: text, price, decimal, password, color, textarea, checkbox, select, multiselect, title
 * WooCommerce Settings field options: title, disabled, class, css, placeholder, type, desc_tip, description, custom_attributes
 */

return array(
    'enabled' => array(
        'title'     => __('Enable/Disable', 'wooship'),
        'type'      => 'checkbox',
        'label'     => __('Enable Conditional Shipping', 'wooship'),
        'default'   => 'no',
    ),
    'selection_method' => array(
        'title'     => __('If multiple methods match', 'wooship'),
        'type'      => 'select',
        'default'   => 'cheapest',
        'options'   => array(
            'cheapest'          => __('Select the cheapest', 'wooship'),
            'most_expensive'    => __('Select the most expensive', 'wooship'),
            'first'             => __('Select first in a row', 'wooship'),
            'select'            => __('Allow customers to choose', 'wooship'),
        ),
    ),
    'based_on' => array(
        'title'     => __('Calculations based on', 'wooship'),
        'type'      => 'select',
        'default'   => 'incl',
        'options'   => array(
            'incl'  => __('Prices inclusive of tax', 'wooship'),
            'excl'  => __('Prices exclusive of tax', 'wooship'),
        ),
    ),
    'tax_status' => array(
        'title'     => __('Tax status', 'wooship'),
        'type'      => 'select',
        'default'   => 'taxable',
        'options'   => array(
            'taxable'   => __('Taxable', 'wooship'),
            'none'      => __('None', 'wooship'),
        ),
    ),
    'charges_include_tax' => array(
        'title'     => __('Charges set include tax', 'wooship'),
        'type'      => 'select',
        'default'   => 'no',
        'options'   => array(
            'no'    => __('No', 'wooship'),
            'yes'   => __('Yes', 'wooship'),
        ),
    ),
);
