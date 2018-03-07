<?php

/**
 * View for advanced options
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

?>

<div id="wooship">

    <!-- SHIPPING METHODS -->
    <h3 class="wc-settings-sub-title">
        <?php _e('Shipping Methods', 'wooship'); ?>
    </h3>
    <p>
        <?php _e('Conditions are checked against the cart as a whole. At least one shipping method must be configured (can be zero rate).', 'wooship'); ?>
    </p>
    <div id="wooship_shipping_methods"></div>

    <!-- ADDITIONAL CHARGES -->
    <h3 class="wc-settings-sub-title">
        <?php _e('Additional Charges', 'wooship'); ?>
    </h3>
    <p>
        <?php _e('Conditions are checked and charges are applied based on a single product or a subset of the cart. All matching charges are applied.', 'wooship'); ?>
    </p>
    <div id="wooship_additional_charges"></div>

    <?php if (WooShip::use_proprietary_shipping_zones()): ?>

        <!-- SHIPPING ZONES -->
        <h3 class="wc-settings-sub-title">
            <?php _e('Shipping Zones', 'wooship'); ?>
        </h3>
        <p>
            <?php _e('Group repeating sets of locations, e.g. all European Union countries. First matching zone in a row is selected.', 'wooship'); ?>
        </p>
        <div id="wooship_shipping_zones"></div>
    <?php endif; ?>

    <!-- ADVANCED SETTINGS -->
    <h3 class="wc-settings-sub-title">
        <?php _e('Advanced Settings', 'wooship'); ?>
    </h3>
    <div id="advanced_settings">
        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th scope="row" class="titledesc">
                        <label for="wooship_weight_multiplier"><?php _e('Weight unit multiplier', 'wooship'); ?></label>
                    </th>
                    <td class="forminp">
                        <fieldset>
                            <legend class="screen-reader-text"><span><?php _e('Weight unit multiplier', 'wooship'); ?></span></legend>
                            <?php WooShip_Form_Builder::number(array(
                                'id'            => 'wooship_weight_multiplier',
                                'name'          => 'wooship[weight_multiplier]',
                                'class'         => 'wooship_field_weight_multiplier',
                                'placeholder'   => '1.0',
                                'value'         => WooShip::opt('weight_multiplier'),
                            )); ?>
                            <p class="description"><?php _e('For example, setting this to 0.5 will let you set cost per half kg/lbs.', 'wooship'); ?></p>
                        </fieldset>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row" class="titledesc">
                        <label for="weight_rounding"><?php _e('Weight rounding', 'wooship'); ?></label>
                    </th>
                    <td class="forminp">
                        <fieldset>
                            <legend class="screen-reader-text"><span><?php _e('Weight rounding', 'wooship'); ?></span></legend>
                            <label for="wooship_weight_rounding">
                            <?php WooShip_Form_Builder::checkbox(array(
                                'id'            => 'wooship_weight_rounding',
                                'name'          => 'wooship[weight_rounding]',
                                'class'         => 'wooship_field_weight_rounding',
                                'checked'       => WooShip::opt('weight_rounding'),
                            )); ?>
                            <?php _e('Round weight up to full weight unit', 'wooship'); ?></label><br>
                            <p class="description"><?php _e('For example, if cart weight is 0.35 kg/lbs and weight unit multiplier is set to 0.5, cart weight will be rounded to 0.5 kg/lbs.', 'wooship'); ?></p>
                        </fieldset>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

</div>
