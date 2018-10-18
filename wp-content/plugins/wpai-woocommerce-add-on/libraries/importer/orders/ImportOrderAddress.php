<?php

namespace wpai_woocommerce_add_on\libraries\importer;

require_once dirname(__FILE__) . '/ImportOrderBase.php';

/**
 *
 * Import Order billing & shipping details
 *
 * Class ImportOrderAddress
 * @package wpai_woocommerce_add_on\libraries\importer
 */
class ImportOrderAddress extends ImportOrderBase {

    /**
     * @var array
     */
    public $billing_fields = array(
        'billing_first_name',
        'billing_last_name',
        'billing_company',
        'billing_address_1',
        'billing_address_2',
        'billing_city',
        'billing_postcode',
        'billing_country',
        'billing_state',
        'billing_phone',
        'billing_email'
    );

    /**
     * @var array
     */
    public $billing_data = array();

    /**
     * @return int|\WP_Error
     */
    public function import() {
        $this->importBilling();
        $this->importShipping();
    }

    /**
     *
     * Importing billing information
     *
     */
    public function importBilling() {

        if ($this->isNewOrder() || $this->getImport()->options['update_all_data'] == 'yes' || $this->getImport()->options['is_update_billing_details']) {
            $this->getLogger() and call_user_func($this->getLogger(), sprintf(__('- Importing billing & shipping information for Order ID `%s`.', \PMWI_Plugin::TEXT_DOMAIN), $this->getOrderID()));

            switch ($this->getImport()->options['pmwi_order']['billing_source']) {
                // Load details from existing customer
                case 'existing':

                    $customer = $this->getParser()->get_existing_customer('billing_source', $this->getIndex());

                    if ($customer) {
                        $this->getLogger() and call_user_func($this->getLogger(), sprintf(__('- %s Existing customer with ID `%s` founded for Order `%s`.', \PMWI_Plugin::TEXT_DOMAIN), $customer->ID, $this->getOrderID()));

                        foreach ($this->billing_fields as $billing_field) {
                            $this->billing_data[$billing_field] = get_user_meta($customer->ID, $billing_field, TRUE);
                            update_post_meta($this->getOrderID(), '_' . $billing_field, $this->billing_data[$billing_field]);
                            $this->getLogger() and call_user_func($this->getLogger(), sprintf(__('- Billing field `%s` has been updated with value `%s` for order `%s` ...', \PMWI_Plugin::TEXT_DOMAIN), $billing_field, $this->billing_data[$billing_field], $this->getOrderID()));
                        }
                        update_post_meta($this->getOrderID(), '_customer_user', $customer->ID);
                    }
                    else {
                        if ($this->getImport()->options['pmwi_order']['is_guest_matching']) {
                            foreach ($this->billing_fields as $billing_field) {
                                $this->billing_data[$billing_field] = $this->getValue('guest_' . $billing_field);
                                update_post_meta($this->getOrderID(), '_' . $billing_field, $this->billing_data[$billing_field]);
                                $this->getLogger() and call_user_func($this->getLogger(), sprintf(__('- Billing field `%s` has been updated with value `%s` for order `%s` ...', \PMWI_Plugin::TEXT_DOMAIN), $billing_field, $this->getValue('guest_' . $billing_field), $this->getOrderID()));
                            }

                            update_post_meta($this->getOrderID(), '_customer_user', '0');
                        }
                        else {
                            $this->getLogger() and call_user_func($this->getLogger(), sprintf(__('<b>WARNING</b>: Existing customer not found for Order `%s`.', \PMWI_Plugin::TEXT_DOMAIN), $this->order_data['post_title']));
                        }
                    }

                    break;

                // Use guest customer
                default:

                    foreach ($this->billing_fields as $billing_field) {
                        $this->billing_data[$billing_field] = $this->getValue($billing_field);
                        update_post_meta($this->getOrderID(), '_' . $billing_field, $this->billing_data[$billing_field]);
                        $this->getLogger() and call_user_func($this->getLogger(), sprintf(__('- Billing field `%s` has been updated with value `%s` for order `%s` ...', \PMWI_Plugin::TEXT_DOMAIN), $billing_field, $this->getValue($billing_field), $this->getOrderID()));
                    }

                    update_post_meta($this->getOrderID(), '_customer_user', '0');

                    break;
            }
        }
    }

    /**
     *
     * Importing shipping information
     *
     */
    public function importShipping() {

        if ($this->isNewOrder() || $this->getImport()->options['update_all_data'] == 'yes' || $this->getImport()->options['is_update_shipping_details']) {
            switch ($this->getImport()->options['pmwi_order']['shipping_source']) {
                // Copy from billing
                case 'copy':

                    $this->getLogger() and call_user_func($this->getLogger(), sprintf(__('- Copying shipping from billing information...', \PMWI_Plugin::TEXT_DOMAIN)));

                    if (!empty($this->billing_data)) {
                        foreach ($this->billing_data as $key => $value) {
                            $shipping_field = str_replace('billing', 'shipping', $key);
                            update_post_meta($this->getOrderID(), '_' . $shipping_field, $value);
                            $this->getLogger() and call_user_func($this->getLogger(), sprintf(__('- Shipping field `%s` has been updated with value `%s` for order `%s` ...', \PMWI_Plugin::TEXT_DOMAIN), $shipping_field, $value, $this->getOrderID()));
                        }
                    }

                    break;

                // Import shipping address
                default:
                    foreach ($this->billing_fields as $billing_field) {
                        $shipping_field = str_replace('billing', 'shipping', $billing_field);
                        $shipping_value = '';
                        if ($this->getValue($shipping_field) != '') {
                            $shipping_value = $this->getValue($shipping_field);
                        }
                        elseif ($this->getImport()->options['pmwi_order']['copy_from_billing']) {
                            $shipping_value = empty($this->billing_data[$billing_field]) ? '' : $this->billing_data[$billing_field];
                        }
                        update_post_meta($this->getOrderID(), '_' . $shipping_field, $shipping_value);
                        $this->getLogger() and call_user_func($this->getLogger(), sprintf(__('- Shipping field `%s` has been updated with value `%s` for order `%s` ...', \PMWI_Plugin::TEXT_DOMAIN), $shipping_field, $shipping_value, $this->getOrderID()));
                    }

                    break;
            }
        }
    }
}