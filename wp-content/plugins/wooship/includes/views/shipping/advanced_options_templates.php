<?php

/**
 * View for advanced options templates
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

?>

<!-- SHIPPING METHODS -->
<div id="wooship_shipping_methods_templates" style="display: none">

    <!-- NO METHODS CONFIGURED -->
    <div id="wooship_shipping_methods_no_rows_template">
        <div id="wooship_shipping_methods_no_rows"><?php _e('No shipping methods configured.', 'wooship'); ?></div>
    </div>

    <!-- ADD METHOD BUTTON -->
    <div id="wooship_shipping_methods_add_row_template">
        <div id="wooship_shipping_methods_add_row">
            <button type="button" class="button" value="<?php _e('Add Method', 'wooship'); ?>">
                <i class="fa fa-plus">&nbsp;&nbsp;<?php _e('Add Method', 'wooship'); ?></i>
            </button>
        </div>
    </div>

    <!-- WRAPPER -->
    <div id="wooship_shipping_methods_wrapper_template">
        <div id="wooship_shipping_methods_wrapper"></div>
    </div>

    <!-- ROW -->
    <div id="wooship_shipping_methods_row_template">

        <div class="wooship_row">

            <div class="wooship_accordion_handle">
                <div class="wooship_row_sort_handle"><i class="fa fa-bars"></i></div>
                <span class="wooship_row_title">
                    <span class="wooship_row_title_title"></span>
                    <span class="wooship_row_title_note"></span>
                </span>
                <div class="wooship_row_remove_handle"><i class="fa fa-times"></i></div>
                <div class="wooship_row_duplicate_handle"><i class="fa fa-clone"></i></div>
            </div>

            <div class="wooship_row_content">

                <div class="wooship_row_content_first_row">
                    <div class="wooship_field wooship_field_double">
                        <?php WooShip_Form_Builder::text(array(
                            'id'        => 'wooship_shipping_methods_title_{i}',
                            'name'      => 'wooship[shipping_methods][{i}][title]',
                            'class'     => 'wooship_shipping_methods_field_title',
                            'value'     => '{value}',
                            'label'     => __('Method Title', 'wooship') . ' <abbr class="required" title="required">*</abbr>',
                        )); ?>
                    </div>
                    <div class="wooship_field wooship_field_double">
                        <?php WooShip_Form_Builder::text(array(
                            'id'        => 'wooship_shipping_methods_note_{i}',
                            'name'      => 'wooship[shipping_methods][{i}][note]',
                            'class'     => 'wooship_shipping_methods_field_note',
                            'value'     => '{value}',
                            'label'     => __('Private Note', 'wooship'),
                        )); ?>
                    </div>
                    <div style="clear: both;"></div>
                </div>

                <div class="wooship_row_content_child_row wooship_row_content_conditions_row">
                    <div class="wooship_field wooship_field_full">
                        <label><?php _e('Conditions', 'wooship'); ?></label>
                        <div class="wooship_inner_wrapper">
                            <div class="wooship_add_condition">
                                <button type="button" class="button" value="<?php _e('Add Condition', 'wooship'); ?>">
                                    <i class="fa fa-plus">&nbsp;&nbsp;<?php _e('Add Condition', 'wooship'); ?></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div style="clear: both;"></div>
                </div>

                <div class="wooship_row_content_pricing_row">
                    <div class="wooship_field wooship_field_full">
                        <label><?php _e('Charges', 'wooship'); ?></label>
                        <div class="wooship_inner_wrapper">

                            <div class="wooship_field wooship_field_single">
                                <label for="wooship_shipping_methods_shipping_cost_{i}"><?php _e('Shipping cost', 'wooship'); ?></label>
                                <div>
                                    <?php WooShip_Form_Builder::number(array(
                                        'id'        => 'wooship_shipping_methods_shipping_cost_{i}',
                                        'name'      => 'wooship[shipping_methods][{i}][shipping_cost]',
                                        'class'     => 'wooship_shipping_methods_field_shipping_cost wooship_half_left',
                                        'placeholder'   => '0.00',
                                        'value'     => '{value}',
                                    )); ?>
                                    <?php WooShip_Form_Builder::select(array(
                                        'id'        => 'wooship_shipping_methods_shipping_cost_method_{i}',
                                        'name'      => 'wooship[shipping_methods][{i}][shipping_cost_method]',
                                        'class'     => 'wooship_shipping_methods_field_shipping_cost_method wooship_half_right',
                                        'options'   => WooShip_Pricing::get_methods(true),
                                    )); ?>
                                </div>
                            </div>

                            <div class="wooship_field wooship_field_single">
                                <label for="wooship_shipping_methods_handling_fee_{i}"><?php _e('Handling fee', 'wooship'); ?></label>
                                <div>
                                    <?php WooShip_Form_Builder::number(array(
                                        'id'        => 'wooship_shipping_methods_handling_fee_{i}',
                                        'name'      => 'wooship[shipping_methods][{i}][handling_fee]',
                                        'class'     => 'wooship_shipping_methods_field_handling_fee wooship_half_left',
                                        'placeholder'   => '0.00',
                                        'value'     => '{value}',
                                    )); ?>
                                    <?php WooShip_Form_Builder::select(array(
                                        'id'        => 'wooship_shipping_methods_handling_fee_method_{i}',
                                        'name'      => 'wooship[shipping_methods][{i}][handling_fee_method]',
                                        'class'     => 'wooship_shipping_methods_field_handling_fee_method wooship_half_right',
                                        'options'   => WooShip_Pricing::get_methods(true),
                                    )); ?>
                                </div>
                            </div>

                            <div class="wooship_field wooship_field_single">
                                <label for="wooship_shipping_methods_weight_cost_{i}"><?php _e('Cost per weight unit', 'wooship'); ?></label>
                                <div>
                                    <?php WooShip_Form_Builder::number(array(
                                        'id'        => 'wooship_shipping_methods_weight_cost_{i}',
                                        'name'      => 'wooship[shipping_methods][{i}][weight_cost]',
                                        'class'     => 'wooship_shipping_methods_field_weight_cost wooship_half_left',
                                        'placeholder'   => '0.00',
                                        'value'     => '{value}',
                                    )); ?>
                                    <?php WooShip_Form_Builder::select(array(
                                        'id'        => 'wooship_shipping_methods_weight_cost_method_{i}',
                                        'name'      => 'wooship[shipping_methods][{i}][weight_cost_method]',
                                        'class'     => 'wooship_shipping_methods_field_weight_cost_method wooship_half_right',
                                        'options'   => WooShip_Pricing::get_methods(false),
                                    )); ?>
                                </div>
                            </div>

                            <div class="wooship_field wooship_field_single">
                                <label for="wooship_shipping_methods_item_cost_{i}"><?php _e('Cost per quantity unit', 'wooship'); ?></label>
                                <div>
                                    <?php WooShip_Form_Builder::number(array(
                                        'id'        => 'wooship_shipping_methods_item_cost_{i}',
                                        'name'      => 'wooship[shipping_methods][{i}][item_cost]',
                                        'class'     => 'wooship_shipping_methods_item_cost wooship_half_left',
                                        'placeholder'   => '0.00',
                                        'value'     => '{value}',
                                    )); ?>
                                    <?php WooShip_Form_Builder::select(array(
                                        'id'        => 'wooship_shipping_methods_item_cost_method_{i}',
                                        'name'      => 'wooship[shipping_methods][{i}][item_cost_method]',
                                        'class'     => 'wooship_shipping_methods_field_item_cost_method wooship_half_right',
                                        'options'   => WooShip_Pricing::get_methods(false),
                                    )); ?>
                                </div>
                            </div>

                            <div style="clear: both;"></div>

                        </div>
                    </div>
                    <div style="clear: both;"></div>
                </div>

            </div>
        </div>
    </div>

    <!-- NO CONDITIONS -->
    <div id="wooship_shipping_methods_no_conditions_template">
        <div class="wooship_no_conditions"><?php _e('No conditions configured.', 'wooship'); ?></div>
    </div>

    <!-- CONDITIONS WRAPPER -->
    <div id="wooship_shipping_methods_condition_wrapper_template">
        <div class="wooship_condition_wrapper"></div>
    </div>

    <!-- CONDITION -->
    <div id="wooship_shipping_methods_condition_template">
        <div class="wooship_condition">
            <div class="wooship_condition_sort">
                <div class="wooship_condition_sort_handle">
                    <i class="fa fa-bars"></i>
                </div>
            </div>

            <div class="wooship_condition_content">
                <div class="wooship_condition_setting wooship_condition_setting_single wooship_condition_setting_type">
                    <?php WooShip_Form_Builder::grouped_select(array(
                        'id'        => 'wooship_shipping_methods_conditions_{i}_type_{j}',
                        'name'      => 'wooship[shipping_methods][{i}][conditions][{j}][type]',
                        'class'     => 'wooship_shipping_methods_condition_type',
                        'options'   => WooShip_Conditions::conditions('shipping_methods'),
                    )); ?>
                </div>

                <?php foreach(WooShip_Conditions::conditions('shipping_methods') as $group_key => $group): ?>
                    <?php foreach($group['options'] as $option_key => $option): ?>
                        <div class="wooship_condition_setting_fields wooship_condition_setting_fields_<?php echo $group_key . '_' . $option_key ?>" style="display: none;">

                            <?php if (WooShip_Conditions::uses_field($group_key, $option_key, 'meta_key')): ?>
                                <div class="wooship_condition_setting_fields_<?php echo WooShip_Conditions::field_size($group_key, $option_key); ?>">
                                    <?php WooShip_Form_Builder::text(array(
                                        'id'        => 'wooship_shipping_methods_conditions_{i}_meta_key_{j}',
                                        'name'      => 'wooship[shipping_methods][{i}][conditions][{j}][meta_key]',
                                        'class'     => 'wooship_shipping_methods_condition_meta_key',
                                        'placeholder'   => __('meta field key', 'wooship'),
                                        'value'         => '{value}',
                                    )); ?>
                                </div>
                            <?php endif; ?>

                            <?php if (WooShip_Conditions::uses_field($group_key, $option_key, 'timeframe_all_time')): ?>
                                <div class="wooship_condition_setting_fields_<?php echo WooShip_Conditions::field_size($group_key, $option_key); ?>">
                                    <?php WooShip_Form_Builder::select(array(
                                        'id'        => 'wooship_shipping_methods_conditions_{i}_timeframe_{j}',
                                        'name'      => 'wooship[shipping_methods][{i}][conditions][{j}][timeframe]',
                                        'class'     => 'wooship_shipping_methods_condition_timeframe',
                                        'options'   => WooShip_Conditions::timeframes(true),
                                    )); ?>
                                </div>
                            <?php endif; ?>

                            <div class="wooship_condition_setting_fields_<?php echo WooShip_Conditions::field_size($group_key, $option_key); ?>">
                                <?php WooShip_Form_Builder::select(array(
                                    'id'        => 'wooship_shipping_methods_conditions_{i}_' . $group_key . '_' . $option_key . '_method_{j}',
                                    'name'      => 'wooship[shipping_methods][{i}][conditions][{j}][' . $group_key . '_' . $option_key . '_method]',
                                    'class'     => 'wooship_shipping_methods_condition_method',
                                    'options'   => WooShip_Conditions::methods($group_key, $option_key),
                                )); ?>
                            </div>

                            <?php if (WooShip_Conditions::uses_field($group_key, $option_key, 'timeframe')): ?>
                                <div class="wooship_condition_setting_fields_<?php echo WooShip_Conditions::field_size($group_key, $option_key); ?>">
                                    <?php WooShip_Form_Builder::select(array(
                                        'id'        => 'wooship_shipping_methods_conditions_{i}_timeframe_{j}',
                                        'name'      => 'wooship[shipping_methods][{i}][conditions][{j}][timeframe]',
                                        'class'     => 'wooship_shipping_methods_condition_timeframe',
                                        'options'   => WooShip_Conditions::timeframes(),
                                    )); ?>
                                </div>
                            <?php endif; ?>

                            <?php if (WooShip_Conditions::uses_field($group_key, $option_key, 'states')): ?>
                                <div class="wooship_condition_setting_fields_<?php echo WooShip_Conditions::field_size($group_key, $option_key); ?>">
                                    <?php WooShip_Form_Builder::multiselect(array(
                                        'id'        => 'wooship_shipping_methods_conditions_{i}_states_{j}',
                                        'name'      => 'wooship[shipping_methods][{i}][conditions][{j}][states][]',
                                        'class'     => 'wooship_shipping_methods_condition_states wooship_select2',
                                        'options'   => array(),
                                    )); ?>
                                </div>
                            <?php endif; ?>

                            <?php if (WooShip_Conditions::uses_field($group_key, $option_key, 'countries')): ?>
                                <div class="wooship_condition_setting_fields_<?php echo WooShip_Conditions::field_size($group_key, $option_key); ?>">
                                    <?php WooShip_Form_Builder::multiselect(array(
                                        'id'        => 'wooship_shipping_methods_conditions_{i}_countries_{j}',
                                        'name'      => 'wooship[shipping_methods][{i}][conditions][{j}][countries][]',
                                        'class'     => 'wooship_shipping_methods_condition_countries wooship_select2',
                                        'options'   => array(),
                                    )); ?>
                                </div>
                            <?php endif; ?>

                            <?php if (WooShip_Conditions::uses_field($group_key, $option_key, 'shipping_zones')): ?>
                                <div class="wooship_condition_setting_fields_<?php echo WooShip_Conditions::field_size($group_key, $option_key); ?>">
                                    <?php WooShip_Form_Builder::multiselect(array(
                                        'id'        => 'wooship_shipping_methods_conditions_{i}_shipping_zones_{j}',
                                        'name'      => 'wooship[shipping_methods][{i}][conditions][{j}][shipping_zones][]',
                                        'class'     => 'wooship_shipping_methods_condition_shipping_zones wooship_select2',
                                        'options'   => array(),
                                    )); ?>
                                </div>
                            <?php endif; ?>

                            <?php if (WooShip_Conditions::uses_field($group_key, $option_key, 'coupons')): ?>
                                <div class="wooship_condition_setting_fields_<?php echo WooShip_Conditions::field_size($group_key, $option_key); ?>">
                                    <?php WooShip_Form_Builder::multiselect(array(
                                        'id'        => 'wooship_shipping_methods_conditions_{i}_coupons_{j}',
                                        'name'      => 'wooship[shipping_methods][{i}][conditions][{j}][coupons][]',
                                        'class'     => 'wooship_shipping_methods_condition_coupons wooship_select2',
                                        'options'   => array(),
                                    )); ?>
                                </div>
                            <?php endif; ?>

                            <?php if (WooShip_Conditions::uses_field($group_key, $option_key, 'shipping_classes')): ?>
                                <div class="wooship_condition_setting_fields_<?php echo WooShip_Conditions::field_size($group_key, $option_key); ?>">
                                    <?php WooShip_Form_Builder::multiselect(array(
                                        'id'        => 'wooship_shipping_methods_conditions_{i}_shipping_classes_{j}',
                                        'name'      => 'wooship[shipping_methods][{i}][conditions][{j}][shipping_classes][]',
                                        'class'     => 'wooship_shipping_methods_condition_shipping_classes wooship_select2',
                                        'options'   => array(),
                                    )); ?>
                                </div>
                            <?php endif; ?>

                            <?php if (WooShip_Conditions::uses_field($group_key, $option_key, 'roles')): ?>
                                <div class="wooship_condition_setting_fields_<?php echo WooShip_Conditions::field_size($group_key, $option_key); ?>">
                                    <?php WooShip_Form_Builder::multiselect(array(
                                        'id'        => 'wooship_shipping_methods_conditions_{i}_roles_{j}',
                                        'name'      => 'wooship[shipping_methods][{i}][conditions][{j}][roles][]',
                                        'class'     => 'wooship_shipping_methods_condition_roles wooship_select2',
                                        'options'   => array(),
                                    )); ?>
                                </div>
                            <?php endif; ?>

                            <?php if (WooShip_Conditions::uses_field($group_key, $option_key, 'capabilities')): ?>
                                <div class="wooship_condition_setting_fields_<?php echo WooShip_Conditions::field_size($group_key, $option_key); ?>">
                                    <?php WooShip_Form_Builder::multiselect(array(
                                        'id'        => 'wooship_shipping_methods_conditions_{i}_capabilities_{j}',
                                        'name'      => 'wooship[shipping_methods][{i}][conditions][{j}][capabilities][]',
                                        'class'     => 'wooship_shipping_methods_condition_capabilities wooship_select2',
                                        'options'   => array(),
                                    )); ?>
                                </div>
                            <?php endif; ?>

                            <?php if (WooShip_Conditions::uses_field($group_key, $option_key, 'users')): ?>
                                <div class="wooship_condition_setting_fields_<?php echo WooShip_Conditions::field_size($group_key, $option_key); ?>">
                                    <?php WooShip_Form_Builder::multiselect(array(
                                        'id'        => 'wooship_shipping_methods_conditions_{i}_users_{j}',
                                        'name'      => 'wooship[shipping_methods][{i}][conditions][{j}][users][]',
                                        'class'     => 'wooship_shipping_methods_condition_users wooship_select2',
                                        'options'   => array(),
                                    )); ?>
                                </div>
                            <?php endif; ?>

                            <?php if (WooShip_Conditions::uses_field($group_key, $option_key, 'attributes')): ?>
                                <div class="wooship_condition_setting_fields_<?php echo WooShip_Conditions::field_size($group_key, $option_key); ?>">
                                    <?php WooShip_Form_Builder::multiselect(array(
                                        'id'        => 'wooship_shipping_methods_conditions_{i}_attributes_{j}',
                                        'name'      => 'wooship[shipping_methods][{i}][conditions][{j}][attributes][]',
                                        'class'     => 'wooship_shipping_methods_condition_attributes wooship_select2',
                                        'options'   => array(),
                                    )); ?>
                                </div>
                            <?php endif; ?>

                            <?php if (WooShip_Conditions::uses_field($group_key, $option_key, 'tags')): ?>
                                <div class="wooship_condition_setting_fields_<?php echo WooShip_Conditions::field_size($group_key, $option_key); ?>">
                                    <?php WooShip_Form_Builder::multiselect(array(
                                        'id'        => 'wooship_shipping_methods_conditions_{i}_tags_{j}',
                                        'name'      => 'wooship[shipping_methods][{i}][conditions][{j}][tags][]',
                                        'class'     => 'wooship_shipping_methods_condition_tags wooship_select2',
                                        'options'   => array(),
                                    )); ?>
                                </div>
                            <?php endif; ?>

                            <?php if (WooShip_Conditions::uses_field($group_key, $option_key, 'products')): ?>
                                <div class="wooship_condition_setting_fields_<?php echo WooShip_Conditions::field_size($group_key, $option_key); ?>">
                                    <?php WooShip_Form_Builder::multiselect(array(
                                        'id'        => 'wooship_shipping_methods_conditions_{i}_products_{j}',
                                        'name'      => 'wooship[shipping_methods][{i}][conditions][{j}][products][]',
                                        'class'     => 'wooship_shipping_methods_condition_products wooship_select2',
                                        'options'   => array(),
                                    )); ?>
                                </div>
                            <?php endif; ?>

                            <?php if (WooShip_Conditions::uses_field($group_key, $option_key, 'product_categories')): ?>
                                <div class="wooship_condition_setting_fields_<?php echo WooShip_Conditions::field_size($group_key, $option_key); ?>">
                                    <?php WooShip_Form_Builder::multiselect(array(
                                        'id'        => 'wooship_shipping_methods_conditions_{i}_product_categories_{j}',
                                        'name'      => 'wooship[shipping_methods][{i}][conditions][{j}][product_categories][]',
                                        'class'     => 'wooship_shipping_methods_condition_product_categories wooship_select2',
                                        'options'   => array(),
                                    )); ?>
                                </div>
                            <?php endif; ?>

                            <?php if (WooShip_Conditions::uses_field($group_key, $option_key, 'number')): ?>
                                <div class="wooship_condition_setting_fields_<?php echo WooShip_Conditions::field_size($group_key, $option_key); ?>">
                                    <?php WooShip_Form_Builder::text(array(
                                        'id'            => 'wooship_shipping_methods_conditions_{i}_number_{j}',
                                        'name'          => 'wooship[shipping_methods][{i}][conditions][{j}][number]',
                                        'class'         => 'wooship_shipping_methods_condition_number',
                                        'placeholder'   => '0 ',
                                        'value'         => '{value}',
                                    )); ?>
                                </div>
                            <?php endif; ?>

                            <?php if (WooShip_Conditions::uses_field($group_key, $option_key, 'decimal')): ?>
                                <div class="wooship_condition_setting_fields_<?php echo WooShip_Conditions::field_size($group_key, $option_key); ?>">
                                    <?php WooShip_Form_Builder::text(array(
                                        'id'            => 'wooship_shipping_methods_conditions_{i}_decimal_{j}',
                                        'name'          => 'wooship[shipping_methods][{i}][conditions][{j}][decimal]',
                                        'class'         => 'wooship_shipping_methods_condition_decimal',
                                        'placeholder'   => '0.00',
                                        'value'         => '{value}',
                                    )); ?>
                                </div>
                            <?php endif; ?>

                            <?php if (WooShip_Conditions::uses_field($group_key, $option_key, 'text')): ?>
                                <div class="wooship_condition_setting_fields_<?php echo WooShip_Conditions::field_size($group_key, $option_key); ?>" <?php echo ((in_array($option_key, array('customer_meta_field'))) ? 'style="display: none;"' : ''); ?>>
                                    <?php WooShip_Form_Builder::text(array(
                                        'id'            => 'wooship_shipping_methods_conditions_{i}_text_{j}',
                                        'name'          => 'wooship[shipping_methods][{i}][conditions][{j}][text]',
                                        'class'         => 'wooship_shipping_methods_condition_text',
                                        'placeholder'   => ($option_key === 'postcode' ? '90210, 902**, 90200-90299, SW1A 1AA, NSW 2001' : ''),
                                        'value'         => '{value}',
                                    )); ?>
                                </div>
                            <?php endif; ?>

                            <div style="clear: both;"></div>
                        </div>
                    <?php endforeach; ?>
                <?php endforeach; ?>

                <div style="clear: both;"></div>
            </div>

            <div class="wooship_condition_remove">
                <div class="wooship_condition_remove_handle">
                    <i class="fa fa-times"></i>
                </div>
            </div>
            <div style="clear: both;"></div>
        </div>
    </div>
</div>

<!-- ADDITIONAL CHARGES -->
<div id="wooship_additional_charges_templates" style="display: none">

    <!-- NO CHARGES CONFIGURED -->
    <div id="wooship_additional_charges_no_rows_template">
        <div id="wooship_additional_charges_no_rows"><?php _e('No additional charges configured.', 'wooship'); ?></div>
    </div>

    <!-- ADD CHARGE BUTTON -->
    <div id="wooship_additional_charges_add_row_template">
        <div id="wooship_additional_charges_add_row">
            <button type="button" class="button" value="<?php _e('Add Charge', 'wooship'); ?>">
                <i class="fa fa-plus">&nbsp;&nbsp;<?php _e('Add Charge', 'wooship'); ?></i>
            </button>
        </div>
    </div>

    <!-- WRAPPER -->
    <div id="wooship_additional_charges_wrapper_template">
        <div id="wooship_additional_charges_wrapper"></div>
    </div>

    <!-- ROW -->
    <div id="wooship_additional_charges_row_template">

        <div class="wooship_row">

            <div class="wooship_accordion_handle">
                <div class="wooship_row_sort_handle"><i class="fa fa-bars"></i></div>
                <span class="wooship_row_title">
                    <span class="wooship_row_title_title"></span>
                    <span class="wooship_row_title_note"></span>
                </span>
                <div class="wooship_row_remove_handle"><i class="fa fa-times"></i></div>
                <div class="wooship_row_duplicate_handle"><i class="fa fa-clone"></i></div>
            </div>

            <div class="wooship_row_content">

                <div class="wooship_row_content_first_row">
                    <div class="wooship_field wooship_field_double">
                        <?php WooShip_Form_Builder::select(array(
                            'id'        => 'wooship_additional_charges_charge_subject_{i}',
                            'name'      => 'wooship[additional_charges][{i}][charge_subject]',
                            'class'     => 'wooship_additional_charges_field_charge_subject',
                            'label'     => __('Charge Per', 'wooship'),
                            'options'   => WooShip_Pricing::get_charge_subjects(),
                        )); ?>
                    </div>
                    <div class="wooship_field wooship_field_double">
                        <?php WooShip_Form_Builder::text(array(
                            'id'        => 'wooship_additional_charges_title_{i}',
                            'name'      => 'wooship[additional_charges][{i}][title]',
                            'class'     => 'wooship_additional_charges_field_title',
                            'value'     => '{value}',
                            'label'     => __('Private Note', 'wooship'),
                        )); ?>
                    </div>
                    <div style="clear: both;"></div>
                </div>

                <div class="wooship_row_content_child_row wooship_row_content_conditions_row">
                    <div class="wooship_field wooship_field_full">
                        <label><?php _e('Conditions', 'wooship'); ?></label>
                        <div class="wooship_inner_wrapper">
                            <div class="wooship_add_condition">
                                <button type="button" class="button" value="<?php _e('Add Condition', 'wooship'); ?>">
                                    <i class="fa fa-plus">&nbsp;&nbsp;<?php _e('Add Condition', 'wooship'); ?></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div style="clear: both;"></div>
                </div>

                <div class="wooship_row_content_pricing_row">
                    <div class="wooship_field wooship_field_full">
                        <label><?php _e('Charges', 'wooship'); ?></label>
                        <div class="wooship_inner_wrapper">

                            <div class="wooship_field wooship_field_single">
                                <label for="wooship_additional_charges_shipping_cost_{i}"><?php _e('Shipping cost', 'wooship'); ?></label>
                                <div>
                                    <?php WooShip_Form_Builder::number(array(
                                        'id'        => 'wooship_additional_charges_shipping_cost_{i}',
                                        'name'      => 'wooship[additional_charges][{i}][shipping_cost]',
                                        'class'     => 'wooship_additional_charges_field_shipping_cost wooship_half_left',
                                        'placeholder'   => '0.00',
                                        'value'     => '{value}',
                                    )); ?>
                                    <?php WooShip_Form_Builder::select(array(
                                        'id'        => 'wooship_additional_charges_shipping_cost_method_{i}',
                                        'name'      => 'wooship[additional_charges][{i}][shipping_cost_method]',
                                        'class'     => 'wooship_additional_charges_field_shipping_cost_method wooship_half_right',
                                        'options'   => WooShip_Pricing::get_methods(),
                                    )); ?>
                                </div>
                            </div>

                            <div class="wooship_field wooship_field_single">
                                <label for="wooship_additional_charges_handling_fee_{i}"><?php _e('Handling fee', 'wooship'); ?></label>
                                <div>
                                    <?php WooShip_Form_Builder::number(array(
                                        'id'        => 'wooship_additional_charges_handling_fee_{i}',
                                        'name'      => 'wooship[additional_charges][{i}][handling_fee]',
                                        'class'     => 'wooship_additional_charges_field_handling_fee wooship_half_left',
                                        'placeholder'   => '0.00',
                                        'value'     => '{value}',
                                    )); ?>
                                    <?php WooShip_Form_Builder::select(array(
                                        'id'        => 'wooship_additional_charges_handling_fee_method_{i}',
                                        'name'      => 'wooship[additional_charges][{i}][handling_fee_method]',
                                        'class'     => 'wooship_additional_charges_field_handling_fee_method wooship_half_right',
                                        'options'   => WooShip_Pricing::get_methods(),
                                    )); ?>
                                </div>
                            </div>

                            <div class="wooship_field wooship_field_single">
                                <label for="wooship_additional_charges_weight_cost_{i}"><?php _e('Cost per weight unit', 'wooship'); ?></label>
                                <div>
                                    <?php WooShip_Form_Builder::number(array(
                                        'id'        => 'wooship_additional_charges_weight_cost_{i}',
                                        'name'      => 'wooship[additional_charges][{i}][weight_cost]',
                                        'class'     => 'wooship_additional_charges_field_weight_cost wooship_half_left',
                                        'placeholder'   => '0.00',
                                        'value'     => '{value}',
                                    )); ?>
                                    <?php WooShip_Form_Builder::select(array(
                                        'id'        => 'wooship_additional_charges_weight_cost_method_{i}',
                                        'name'      => 'wooship[additional_charges][{i}][weight_cost_method]',
                                        'class'     => 'wooship_additional_charges_field_weight_cost_method wooship_half_right',
                                        'options'   => WooShip_Pricing::get_methods(false),
                                    )); ?>
                                </div>
                            </div>

                            <div class="wooship_field wooship_field_single">
                                <label for="wooship_additional_charges_item_cost_{i}"><?php _e('Cost per quantity unit', 'wooship'); ?></label>
                                <div>
                                    <?php WooShip_Form_Builder::number(array(
                                        'id'        => 'wooship_additional_charges_item_cost_{i}',
                                        'name'      => 'wooship[additional_charges][{i}][item_cost]',
                                        'class'     => 'wooship_additional_charges_item_cost wooship_half_left',
                                        'placeholder'   => '0.00',
                                        'value'     => '{value}',
                                    )); ?>
                                    <?php WooShip_Form_Builder::select(array(
                                        'id'        => 'wooship_additional_charges_item_cost_method_{i}',
                                        'name'      => 'wooship[additional_charges][{i}][item_cost_method]',
                                        'class'     => 'wooship_additional_charges_field_item_cost_method wooship_half_right',
                                        'options'   => WooShip_Pricing::get_methods(false),
                                    )); ?>
                                </div>
                            </div>

                            <div style="clear: both;"></div>

                        </div>
                    </div>
                    <div style="clear: both;"></div>
                </div>


            </div>
        </div>
    </div>

    <!-- NO CONDITIONS -->
    <div id="wooship_additional_charges_no_conditions_template">
        <div class="wooship_no_conditions"><?php _e('No conditions configured.', 'wooship'); ?></div>
    </div>

    <!-- CONDITIONS WRAPPER -->
    <div id="wooship_additional_charges_condition_wrapper_template">
        <div class="wooship_condition_wrapper"></div>
    </div>

    <!-- CONDITION -->
    <div id="wooship_additional_charges_condition_template">
        <div class="wooship_condition">
            <div class="wooship_condition_sort">
                <div class="wooship_condition_sort_handle">
                    <i class="fa fa-bars"></i>
                </div>
            </div>

            <div class="wooship_condition_content">
                <div class="wooship_condition_setting wooship_condition_setting_single wooship_condition_setting_type">
                    <?php WooShip_Form_Builder::grouped_select(array(
                        'id'        => 'wooship_additional_charges_conditions_{i}_type_{j}',
                        'name'      => 'wooship[additional_charges][{i}][conditions][{j}][type]',
                        'class'     => 'wooship_additional_charges_condition_type',
                        'options'   => WooShip_Conditions::conditions('additional_charges'),
                    )); ?>
                </div>

                <?php foreach(WooShip_Conditions::conditions('additional_charges') as $group_key => $group): ?>
                    <?php foreach($group['options'] as $option_key => $option): ?>
                        <div class="wooship_condition_setting_fields wooship_condition_setting_fields_<?php echo $group_key . '_' . $option_key ?>" style="display: none;">

                            <?php if (WooShip_Conditions::uses_field($group_key, $option_key, 'meta_key')): ?>
                                <div class="wooship_condition_setting_fields_<?php echo WooShip_Conditions::field_size($group_key, $option_key); ?>">
                                    <?php WooShip_Form_Builder::text(array(
                                        'id'        => 'wooship_additional_charges_conditions_{i}_meta_key_{j}',
                                        'name'      => 'wooship[additional_charges][{i}][conditions][{j}][meta_key]',
                                        'class'     => 'wooship_additional_charges_condition_meta_key',
                                        'placeholder'   => __('meta field key', 'wooship'),
                                        'value'         => '{value}',
                                    )); ?>
                                </div>
                            <?php endif; ?>

                            <?php if (WooShip_Conditions::uses_field($group_key, $option_key, 'timeframe_all_time')): ?>
                                <div class="wooship_condition_setting_fields_<?php echo WooShip_Conditions::field_size($group_key, $option_key); ?>">
                                    <?php WooShip_Form_Builder::select(array(
                                        'id'        => 'wooship_additional_charges_conditions_{i}_timeframe_{j}',
                                        'name'      => 'wooship[additional_charges][{i}][conditions][{j}][timeframe]',
                                        'class'     => 'wooship_additional_charges_condition_timeframe',
                                        'options'   => WooShip_Conditions::timeframes(true),
                                    )); ?>
                                </div>
                            <?php endif; ?>

                            <div class="wooship_condition_setting_fields_<?php echo WooShip_Conditions::field_size($group_key, $option_key); ?>">
                                <?php WooShip_Form_Builder::select(array(
                                    'id'        => 'wooship_additional_charges_conditions_{i}_' . $group_key . '_' . $option_key . '_method_{j}',
                                    'name'      => 'wooship[additional_charges][{i}][conditions][{j}][' . $group_key . '_' . $option_key . '_method]',
                                    'class'     => 'wooship_additional_charges_condition_method',
                                    'options'   => WooShip_Conditions::methods($group_key, $option_key),
                                )); ?>
                            </div>

                            <?php if (WooShip_Conditions::uses_field($group_key, $option_key, 'timeframe')): ?>
                                <div class="wooship_condition_setting_fields_<?php echo WooShip_Conditions::field_size($group_key, $option_key); ?>">
                                    <?php WooShip_Form_Builder::select(array(
                                        'id'        => 'wooship_additional_charges_conditions_{i}_timeframe_{j}',
                                        'name'      => 'wooship[additional_charges][{i}][conditions][{j}][timeframe]',
                                        'class'     => 'wooship_additional_charges_condition_timeframe',
                                        'options'   => WooShip_Conditions::timeframes(),
                                    )); ?>
                                </div>
                            <?php endif; ?>

                            <?php if (WooShip_Conditions::uses_field($group_key, $option_key, 'states')): ?>
                                <div class="wooship_condition_setting_fields_<?php echo WooShip_Conditions::field_size($group_key, $option_key); ?>">
                                    <?php WooShip_Form_Builder::multiselect(array(
                                        'id'        => 'wooship_additional_charges_conditions_{i}_states_{j}',
                                        'name'      => 'wooship[additional_charges][{i}][conditions][{j}][states][]',
                                        'class'     => 'wooship_additional_charges_condition_states wooship_select2',
                                        'options'   => array(),
                                    )); ?>
                                </div>
                            <?php endif; ?>

                            <?php if (WooShip_Conditions::uses_field($group_key, $option_key, 'countries')): ?>
                                <div class="wooship_condition_setting_fields_<?php echo WooShip_Conditions::field_size($group_key, $option_key); ?>">
                                    <?php WooShip_Form_Builder::multiselect(array(
                                        'id'        => 'wooship_additional_charges_conditions_{i}_countries_{j}',
                                        'name'      => 'wooship[additional_charges][{i}][conditions][{j}][countries][]',
                                        'class'     => 'wooship_additional_charges_condition_countries wooship_select2',
                                        'options'   => array(),
                                    )); ?>
                                </div>
                            <?php endif; ?>

                            <?php if (WooShip_Conditions::uses_field($group_key, $option_key, 'shipping_zones')): ?>
                                <div class="wooship_condition_setting_fields_<?php echo WooShip_Conditions::field_size($group_key, $option_key); ?>">
                                    <?php WooShip_Form_Builder::multiselect(array(
                                        'id'        => 'wooship_additional_charges_conditions_{i}_shipping_zones_{j}',
                                        'name'      => 'wooship[additional_charges][{i}][conditions][{j}][shipping_zones][]',
                                        'class'     => 'wooship_additional_charges_condition_shipping_zones wooship_select2',
                                        'options'   => array(),
                                    )); ?>
                                </div>
                            <?php endif; ?>

                            <?php if (WooShip_Conditions::uses_field($group_key, $option_key, 'coupons')): ?>
                                <div class="wooship_condition_setting_fields_<?php echo WooShip_Conditions::field_size($group_key, $option_key); ?>">
                                    <?php WooShip_Form_Builder::multiselect(array(
                                        'id'        => 'wooship_additional_charges_conditions_{i}_coupons_{j}',
                                        'name'      => 'wooship[additional_charges][{i}][conditions][{j}][coupons][]',
                                        'class'     => 'wooship_additional_charges_condition_coupons wooship_select2',
                                        'options'   => array(),
                                    )); ?>
                                </div>
                            <?php endif; ?>

                            <?php if (WooShip_Conditions::uses_field($group_key, $option_key, 'shipping_classes')): ?>
                                <div class="wooship_condition_setting_fields_<?php echo WooShip_Conditions::field_size($group_key, $option_key); ?>">
                                    <?php WooShip_Form_Builder::multiselect(array(
                                        'id'        => 'wooship_additional_charges_conditions_{i}_shipping_classes_{j}',
                                        'name'      => 'wooship[additional_charges][{i}][conditions][{j}][shipping_classes][]',
                                        'class'     => 'wooship_additional_charges_condition_shipping_classes wooship_select2',
                                        'options'   => array(),
                                    )); ?>
                                </div>
                            <?php endif; ?>

                            <?php if (WooShip_Conditions::uses_field($group_key, $option_key, 'roles')): ?>
                                <div class="wooship_condition_setting_fields_<?php echo WooShip_Conditions::field_size($group_key, $option_key); ?>">
                                    <?php WooShip_Form_Builder::multiselect(array(
                                        'id'        => 'wooship_additional_charges_conditions_{i}_roles_{j}',
                                        'name'      => 'wooship[additional_charges][{i}][conditions][{j}][roles][]',
                                        'class'     => 'wooship_additional_charges_condition_roles wooship_select2',
                                        'options'   => array(),
                                    )); ?>
                                </div>
                            <?php endif; ?>

                            <?php if (WooShip_Conditions::uses_field($group_key, $option_key, 'capabilities')): ?>
                                <div class="wooship_condition_setting_fields_<?php echo WooShip_Conditions::field_size($group_key, $option_key); ?>">
                                    <?php WooShip_Form_Builder::multiselect(array(
                                        'id'        => 'wooship_additional_charges_conditions_{i}_capabilities_{j}',
                                        'name'      => 'wooship[additional_charges][{i}][conditions][{j}][capabilities][]',
                                        'class'     => 'wooship_additional_charges_condition_capabilities wooship_select2',
                                        'options'   => array(),
                                    )); ?>
                                </div>
                            <?php endif; ?>

                            <?php if (WooShip_Conditions::uses_field($group_key, $option_key, 'users')): ?>
                                <div class="wooship_condition_setting_fields_<?php echo WooShip_Conditions::field_size($group_key, $option_key); ?>">
                                    <?php WooShip_Form_Builder::multiselect(array(
                                        'id'        => 'wooship_additional_charges_conditions_{i}_users_{j}',
                                        'name'      => 'wooship[additional_charges][{i}][conditions][{j}][users][]',
                                        'class'     => 'wooship_additional_charges_condition_users wooship_select2',
                                        'options'   => array(),
                                    )); ?>
                                </div>
                            <?php endif; ?>

                            <?php if (WooShip_Conditions::uses_field($group_key, $option_key, 'attributes')): ?>
                                <div class="wooship_condition_setting_fields_<?php echo WooShip_Conditions::field_size($group_key, $option_key); ?>">
                                    <?php WooShip_Form_Builder::multiselect(array(
                                        'id'        => 'wooship_additional_charges_conditions_{i}_attributes_{j}',
                                        'name'      => 'wooship[additional_charges][{i}][conditions][{j}][attributes][]',
                                        'class'     => 'wooship_additional_charges_condition_attributes wooship_select2',
                                        'options'   => array(),
                                    )); ?>
                                </div>
                            <?php endif; ?>

                            <?php if (WooShip_Conditions::uses_field($group_key, $option_key, 'tags')): ?>
                                <div class="wooship_condition_setting_fields_<?php echo WooShip_Conditions::field_size($group_key, $option_key); ?>">
                                    <?php WooShip_Form_Builder::multiselect(array(
                                        'id'        => 'wooship_additional_charges_conditions_{i}_tags_{j}',
                                        'name'      => 'wooship[additional_charges][{i}][conditions][{j}][tags][]',
                                        'class'     => 'wooship_additional_charges_condition_tags wooship_select2',
                                        'options'   => array(),
                                    )); ?>
                                </div>
                            <?php endif; ?>

                            <?php if (WooShip_Conditions::uses_field($group_key, $option_key, 'products')): ?>
                                <div class="wooship_condition_setting_fields_<?php echo WooShip_Conditions::field_size($group_key, $option_key); ?>">
                                    <?php WooShip_Form_Builder::multiselect(array(
                                        'id'        => 'wooship_additional_charges_conditions_{i}_products_{j}',
                                        'name'      => 'wooship[additional_charges][{i}][conditions][{j}][products][]',
                                        'class'     => 'wooship_additional_charges_condition_products wooship_select2',
                                        'options'   => array(),
                                    )); ?>
                                </div>
                            <?php endif; ?>

                            <?php if (WooShip_Conditions::uses_field($group_key, $option_key, 'product_categories')): ?>
                                <div class="wooship_condition_setting_fields_<?php echo WooShip_Conditions::field_size($group_key, $option_key); ?>">
                                    <?php WooShip_Form_Builder::multiselect(array(
                                        'id'        => 'wooship_additional_charges_conditions_{i}_product_categories_{j}',
                                        'name'      => 'wooship[additional_charges][{i}][conditions][{j}][product_categories][]',
                                        'class'     => 'wooship_additional_charges_condition_product_categories wooship_select2',
                                        'options'   => array(),
                                    )); ?>
                                </div>
                            <?php endif; ?>

                            <?php if (WooShip_Conditions::uses_field($group_key, $option_key, 'number')): ?>
                                <div class="wooship_condition_setting_fields_<?php echo WooShip_Conditions::field_size($group_key, $option_key); ?>">
                                    <?php WooShip_Form_Builder::text(array(
                                        'id'            => 'wooship_additional_charges_conditions_{i}_number_{j}',
                                        'name'          => 'wooship[additional_charges][{i}][conditions][{j}][number]',
                                        'class'         => 'wooship_additional_charges_condition_number',
                                        'placeholder'   => '0 ',
                                        'value'         => '{value}',
                                    )); ?>
                                </div>
                            <?php endif; ?>

                            <?php if (WooShip_Conditions::uses_field($group_key, $option_key, 'decimal')): ?>
                                <div class="wooship_condition_setting_fields_<?php echo WooShip_Conditions::field_size($group_key, $option_key); ?>">
                                    <?php WooShip_Form_Builder::text(array(
                                        'id'            => 'wooship_additional_charges_conditions_{i}_decimal_{j}',
                                        'name'          => 'wooship[additional_charges][{i}][conditions][{j}][decimal]',
                                        'class'         => 'wooship_additional_charges_condition_decimal',
                                        'placeholder'   => '0.00',
                                        'value'         => '{value}',
                                    )); ?>
                                </div>
                            <?php endif; ?>

                            <?php if (WooShip_Conditions::uses_field($group_key, $option_key, 'text')): ?>
                                <div class="wooship_condition_setting_fields_<?php echo WooShip_Conditions::field_size($group_key, $option_key); ?>" <?php echo ((in_array($option_key, array('customer_meta_field'))) ? 'style="display: none;"' : ''); ?>>
                                    <?php WooShip_Form_Builder::text(array(
                                        'id'            => 'wooship_additional_charges_conditions_{i}_text_{j}',
                                        'name'          => 'wooship[additional_charges][{i}][conditions][{j}][text]',
                                        'class'         => 'wooship_additional_charges_condition_text',
                                        'placeholder'   => ($option_key === 'postcode' ? '90210, 902**, 90200-90299, SW1A 1AA, NSW 2001' : ''),
                                        'value'         => '{value}',
                                    )); ?>
                                </div>
                            <?php endif; ?>

                            <div style="clear: both;"></div>
                        </div>
                    <?php endforeach; ?>
                <?php endforeach; ?>

                <div style="clear: both;"></div>
            </div>

            <div class="wooship_condition_remove">
                <div class="wooship_condition_remove_handle">
                    <i class="fa fa-times"></i>
                </div>
            </div>
            <div style="clear: both;"></div>
        </div>
    </div>
</div>

<?php if (WooShip::use_proprietary_shipping_zones()): ?>

    <!-- SHIPPING ZONES -->
    <div id="wooship_shipping_zones_templates" style="display: none">

        <!-- NO ZONES CONFIGURED -->
        <div id="wooship_shipping_zones_no_rows_template">
            <div id="wooship_shipping_zones_no_rows"><?php _e('No shipping zones configured.', 'wooship'); ?></div>
        </div>

        <!-- ADD ZONE BUTTON -->
        <div id="wooship_shipping_zones_add_row_template">
            <div id="wooship_shipping_zones_add_row">
                <button type="button" class="button" value="<?php _e('Add Zone', 'wooship'); ?>">
                    <i class="fa fa-plus">&nbsp;&nbsp;<?php _e('Add Zone', 'wooship'); ?></i>
                </button>
            </div>
        </div>

        <!-- WRAPPER -->
        <div id="wooship_shipping_zones_wrapper_template">
            <div id="wooship_shipping_zones_wrapper"></div>
        </div>

        <!-- ROW -->
        <div id="wooship_shipping_zones_row_template">

            <div class="wooship_row">

                <div class="wooship_accordion_handle">
                    <div class="wooship_row_sort_handle"><i class="fa fa-bars"></i></div>
                    <span class="wooship_row_title">
                        <span class="wooship_row_title_title"></span>
                        <span class="wooship_row_title_note"></span>
                    </span>
                    <div class="wooship_row_remove_handle"><i class="fa fa-times"></i></div>
                    <div class="wooship_row_duplicate_handle"><i class="fa fa-clone"></i></div>
                </div>

                <div class="wooship_row_content">

                    <div class="wooship_row_content_first_row">
                        <div class="wooship_field wooship_field_full">
                            <?php WooShip_Form_Builder::text(array(
                                'id'        => 'wooship_shipping_zones_title_{i}',
                                'name'      => 'wooship[shipping_zones][{i}][title]',
                                'class'     => 'wooship_shipping_zones_field_title',
                                'value'     => '{value}',
                                'label'     => __('Zone Title', 'wooship') . ' <abbr class="required" title="required">*</abbr>',
                            )); ?>
                        </div>
                        <div style="clear: both;"></div>
                    </div>

                    <div class="wooship_row_content_child_row wooship_row_content_conditions_row">
                        <div class="wooship_field wooship_field_full">
                            <label><?php _e('Conditions', 'wooship'); ?></label>
                            <div class="wooship_inner_wrapper">
                                <div class="wooship_add_condition">
                                    <button type="button" class="button" value="<?php _e('Add Condition', 'wooship'); ?>">
                                        <i class="fa fa-plus">&nbsp;&nbsp;<?php _e('Add Condition', 'wooship'); ?></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div style="clear: both;"></div>
                    </div>

                    <?php WooShip_Form_Builder::hidden(array(
                        'id'        => 'wooship_shipping_zones_id_{i}',
                        'name'      => 'wooship[shipping_zones][{i}][id]',
                        'value'     => '{value}',
                    )); ?>
                </div>
            </div>
        </div>

        <!-- NO CONDITIONS -->
        <div id="wooship_shipping_zones_no_conditions_template">
            <div class="wooship_no_conditions"><?php _e('No conditions configured.', 'wooship'); ?></div>
        </div>

        <!-- CONDITIONS WRAPPER -->
        <div id="wooship_shipping_zones_condition_wrapper_template">
            <div class="wooship_condition_wrapper"></div>
        </div>

        <!-- CONDITION -->
        <div id="wooship_shipping_zones_condition_template">
            <div class="wooship_condition">
                <div class="wooship_condition_sort">
                    <div class="wooship_condition_sort_handle">
                        <i class="fa fa-bars"></i>
                    </div>
                </div>

                <div class="wooship_condition_content">
                    <div class="wooship_condition_setting wooship_condition_setting_single wooship_condition_setting_type">
                        <?php WooShip_Form_Builder::grouped_select(array(
                            'id'        => 'wooship_shipping_zones_conditions_{i}_type_{j}',
                            'name'      => 'wooship[shipping_zones][{i}][conditions][{j}][type]',
                            'class'     => 'wooship_shipping_zones_condition_type',
                            'options'   => WooShip_Conditions::conditions('shipping_zones'),
                        )); ?>
                    </div>

                    <?php foreach(WooShip_Conditions::conditions('shipping_zones') as $group_key => $group): ?>
                        <?php foreach($group['options'] as $option_key => $option): ?>
                            <div class="wooship_condition_setting_fields wooship_condition_setting_fields_<?php echo $group_key . '_' . $option_key ?>" style="display: none;">

                                <div class="wooship_condition_setting_fields_<?php echo WooShip_Conditions::field_size($group_key, $option_key); ?>">
                                    <?php WooShip_Form_Builder::select(array(
                                        'id'        => 'wooship_shipping_zones_conditions_{i}_' . $group_key . '_' . $option_key . '_method_{j}',
                                        'name'      => 'wooship[shipping_zones][{i}][conditions][{j}][' . $group_key . '_' . $option_key . '_method]',
                                        'class'     => 'wooship_shipping_zones_condition_method',
                                        'options'   => WooShip_Conditions::methods($group_key, $option_key),
                                    )); ?>
                                </div>

                                <?php if (WooShip_Conditions::uses_field($group_key, $option_key, 'states')): ?>
                                    <div class="wooship_condition_setting_fields_<?php echo WooShip_Conditions::field_size($group_key, $option_key); ?>">
                                        <?php WooShip_Form_Builder::multiselect(array(
                                            'id'        => 'wooship_shipping_zones_conditions_{i}_states_{j}',
                                            'name'      => 'wooship[shipping_zones][{i}][conditions][{j}][states][]',
                                            'class'     => 'wooship_shipping_zones_condition_states wooship_select2',
                                            'options'   => array(),
                                        )); ?>
                                    </div>
                                <?php endif; ?>

                                <?php if (WooShip_Conditions::uses_field($group_key, $option_key, 'countries')): ?>
                                    <div class="wooship_condition_setting_fields_<?php echo WooShip_Conditions::field_size($group_key, $option_key); ?>">
                                        <?php WooShip_Form_Builder::multiselect(array(
                                            'id'        => 'wooship_shipping_zones_conditions_{i}_countries_{j}',
                                            'name'      => 'wooship[shipping_zones][{i}][conditions][{j}][countries][]',
                                            'class'     => 'wooship_shipping_zones_condition_countries wooship_select2',
                                            'options'   => array(),
                                        )); ?>
                                    </div>
                                <?php endif; ?>

                                <?php if (WooShip_Conditions::uses_field($group_key, $option_key, 'text')): ?>
                                    <div class="wooship_condition_setting_fields_<?php echo WooShip_Conditions::field_size($group_key, $option_key); ?>" <?php echo ((in_array($option_key, array('customer_meta_field'))) ? 'style="display: none;"' : ''); ?>>
                                        <?php WooShip_Form_Builder::text(array(
                                            'id'            => 'wooship_shipping_zones_conditions_{i}_text_{j}',
                                            'name'          => 'wooship[shipping_zones][{i}][conditions][{j}][text]',
                                            'class'         => 'wooship_shipping_zones_condition_text',
                                            'placeholder'   => ($option_key === 'postcode' ? '90210, 902**, 90200-90299, SW1A 1AA, NSW 2001' : ''),
                                            'value'         => '{value}',
                                        )); ?>
                                    </div>
                                <?php endif; ?>

                                <div style="clear: both;"></div>
                            </div>
                        <?php endforeach; ?>
                    <?php endforeach; ?>

                    <div style="clear: both;"></div>
                </div>

                <div class="wooship_condition_remove">
                    <div class="wooship_condition_remove_handle">
                        <i class="fa fa-times"></i>
                    </div>
                </div>
                <div style="clear: both;"></div>
            </div>
        </div>

    </div>
<?php endif; ?>
