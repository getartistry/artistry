<ul style="padding-left: 35px;">
    <?php if ( $post['is_update_status']): ?>
        <li> <?php _e('Order status', 'wp_all_import_plugin'); ?></li>
    <?php endif; ?>
    <?php if ( $post['is_update_excerpt']): ?>
        <li> <?php _e('Customer Note', 'wp_all_import_plugin'); ?></li>
    <?php endif; ?>
    <?php if ( $post['is_update_dates']): ?>
        <li> <?php _e('Dates', 'wp_all_import_plugin'); ?></li>
    <?php endif; ?>
    <?php if ( $post['is_update_billing_details']): ?>
        <li> <?php _e('Billing Details', 'wp_all_import_plugin'); ?></li>
    <?php endif; ?>
    <?php if ( $post['is_update_shipping_details']): ?>
        <li> <?php _e('Shipping Details', 'wp_all_import_plugin'); ?></li>
    <?php endif; ?>
    <?php if ( $post['is_update_payment']): ?>
        <li> <?php _e('Payment Details', 'wp_all_import_plugin'); ?></li>
    <?php endif; ?>
    <?php if ( $post['is_update_notes']): ?>
        <li> <?php _e('Order Notes', 'wp_all_import_plugin'); ?></li>
    <?php endif; ?>
    <?php if ( $post['is_update_products']): ?>
        <li>
            <?php
            switch($post['update_products_logic']){
                case 'full_update':
                    _e('Update all products', 'wp_all_import_plugin');
                    break;
                case 'add_new':
                    _e('Don\'t touch existing products, append new products', 'wp_all_import_plugin');
                    break;
            } ?>
        </li>
    <?php endif; ?>
    <?php if ( $post['is_update_fees']): ?>
        <li> <?php _e('Fees Items', 'wp_all_import_plugin'); ?></li>
    <?php endif; ?>
    <?php if ( $post['is_update_coupons']): ?>
        <li> <?php _e('Coupon Items', 'wp_all_import_plugin'); ?></li>
    <?php endif; ?>
    <?php if ( $post['is_update_shipping']): ?>
        <li> <?php _e('Shipping Items', 'wp_all_import_plugin'); ?></li>
    <?php endif; ?>
    <?php if ( $post['is_update_taxes']): ?>
        <li> <?php _e('Tax Items', 'wp_all_import_plugin'); ?></li>
    <?php endif; ?>
    <?php if ( $post['is_update_refunds']): ?>
        <li> <?php _e('Refunds', 'wp_all_import_plugin'); ?></li>
    <?php endif; ?>
    <?php if ( $post['is_update_total']): ?>
        <li> <?php _e('Order Total', 'wp_all_import_plugin'); ?></li>
    <?php endif; ?>
    <?php if ( ! empty($post['is_update_acf'])): ?>
        <li>
            <?php
            switch($post['update_acf_logic']){
                case 'full_update':
                    _e('All advanced custom fields', 'wp_all_import_plugin');
                    break;
                case 'mapped':
                    _e('Only ACF presented in import options', 'wp_all_import_plugin');
                    break;
                case 'only':
                    printf(__('Only these ACF : %s', 'wp_all_import_plugin'), $post['acf_only_list']);
                    break;
                case 'all_except':
                    printf(__('All ACF except these: %s', 'wp_all_import_plugin'), $post['acf_except_list']);
                    break;
            } ?>
        </li>
    <?php endif; ?>
    <?php if ( ! empty($post['is_update_custom_fields'])): ?>
        <li>
            <?php
            switch($post['update_custom_fields_logic']){
                case 'full_update':
                    _e('All custom fields', 'wp_all_import_plugin');
                    break;
                case 'only':
                    printf(__('Only these custom fields : %s', 'wp_all_import_plugin'), $post['custom_fields_only_list']);
                    break;
                case 'all_except':
                    printf(__('All custom fields except these: %s', 'wp_all_import_plugin'), $post['custom_fields_except_list']);
                    break;
            } ?>
        </li>
    <?php endif; ?>
</ul>