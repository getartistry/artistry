<?php

/**
 *
 * Cleanup temporary price fields.
 *
 * @param $pid - Post ID
 */
function pmwi_update_prices($pid){
    $fields = array('_regular_price', '_sale_price', 'pmxi_wholesale_price', '_sale_price_dates_from', '_sale_price_dates_to', '_price');
    foreach ($fields as $field) {
        $value = get_post_meta( $pid, $field . '_tmp', true);
        update_post_meta($pid, $field, $value);
        delete_post_meta($pid, $field . '_tmp');
    }
}