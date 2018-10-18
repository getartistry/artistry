<?php

namespace wpai_woocommerce_add_on\libraries\importer;

/**
 * Class ImportOrderCouponItems
 * @package wpai_woocommerce_add_on\libraries\importer
 */
class ImportOrderCouponItems extends ImportOrderItemsBase {

    /**
     *  Importing coupons items
     */
    public function import() {
        if ($this->isNewOrder() || $this->getImport()->options['update_all_data'] == 'yes' || $this->getImport()->options['is_update_coupons']) {
            $this->_import_coupons_items();
        }
    }

    /**
     *  Import order coupon items
     */
    protected function _import_coupons_items() {
        $total_discount_amount = 0;
        $total_discount_amount_tax = 0;

        $coupons = $this->getValue('coupons');
        if (!empty($coupons)) {
            foreach ($coupons as $couponIndex => $coupon) {
                if (empty($coupon['code'])) {
                    continue;
                }

                $coupon += array(
                    'code' => '',
                    'amount' => '',
                    'amount_tax' => ''
                );

                $order_item = new \PMXI_Post_Record();
                $order_item->getBy(array(
                    'import_id' => $this->getImport()->id,
                    'post_id' => $this->getOrderID(),
                    'unique_key' => 'coupon-item-' . $couponIndex
                ));

                $absAmount = abs($coupon['amount']);

                if (!empty($absAmount)) {
                    $total_discount_amount += $absAmount;
                }
                if (!empty($coupon['amount_tax'])) {
                    $total_discount_amount_tax += $coupon['amount_tax'];
                }

                if ($order_item->isEmpty()) {
                    $item_id = FALSE;

                    if (!$this->isNewOrder()) {
                        $order_items = $this->getOrder()->get_items('coupon');

                        foreach ($order_items as $order_item_id => $order_item) {
                            if ($order_item['name'] == $coupon['code']) {
                                $item_id = $order_item_id;
                                break(2);
                            }
                        }
                    }

                    if (!$item_id) {
                        if (version_compare(WOOCOMMERCE_VERSION, '3.0') < 0) {
                            $item_id = $this->getOrder()
                                ->add_coupon($coupon['code'], $absAmount, $coupon['amount_tax']);
                        }
                        else {
                            $item = new \WC_Order_Item_Coupon();
                            $item->set_props(array(
                                'code' => $coupon['code'],
                                'discount' => isset($coupon['amount']) ? floatval($coupon['amount']) : 0,
                                'discount_tax' => 0,
                                'order_id' => $this->getOrderID(),
                            ));
                            $item_id = $item->save();
                        }
                    }

                    if (!$item_id) {
                        $this->getLogger() and call_user_func($this->getLogger(), __('- <b>WARNING</b> Unable to create order coupon line.', \PMWI_Plugin::TEXT_DOMAIN));
                    }
                    else {
                        $order_item->set(array(
                            'import_id' => $this->getImport()->id,
                            'post_id' => $this->getOrderID(),
                            'unique_key' => 'coupon-item-' . $couponIndex,
                            'product_key' => 'coupon-item-' . $item_id,
                            'iteration' => $this->getImport()->iteration
                        ))->save();
                    }
                }
                else {
                    $item_id = str_replace('coupon-item-', '', $order_item->product_key);

                    if (version_compare(WOOCOMMERCE_VERSION, '3.0') < 0) {

                        $is_updated = $this->getOrder()
                            ->update_coupon($item_id, array(
                                'code' => $coupon['code'],
                                'discount_amount' => $absAmount,
                                // 'discount_amount_tax' => empty($coupon['amount_tax']) ? NULL : $coupon['amount_tax']
                            ));

                    }
                    else {

                        $item = new \WC_Order_Item_Coupon($item_id);

                        if (isset($coupon['code'])) {
                            $item->set_code($coupon['code']);
                        }

                        if (isset($coupon['amount'])) {
                            $item->set_discount(floatval($coupon['amount']));
                        }

                        $is_updated = $item->save();
                    }

                    if ($is_updated) {
                        $order_item->set(array(
                            'iteration' => $this->getImport()->iteration
                        ))->save();
                    }
                }
            }
        }
        update_post_meta($this->getOrderID(), '_cart_discount', $total_discount_amount);
        update_post_meta($this->getOrderID(), '_cart_discount_tax', $total_discount_amount_tax);
    }
}
