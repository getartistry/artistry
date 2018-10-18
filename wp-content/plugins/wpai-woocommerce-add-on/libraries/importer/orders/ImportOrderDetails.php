<?php

namespace wpai_woocommerce_add_on\libraries\importer;

require_once dirname(__FILE__) . '/ImportOrderBase.php';

/**
 *
 * Import Order details - status, date
 *
 * Class ImportOrderDetails
 * @package wpai_woocommerce_add_on\libraries\importer
 */
class ImportOrderDetails extends ImportOrderBase {

    /**
     * @return int|\WP_Error
     */
    public function import() {

        $order_status = trim($this->getValue('status'));

        // detect order status by slug or title
        $all_order_statuses = wc_get_order_statuses();
        if (empty($all_order_statuses[$order_status])) {
            $status_founded = FALSE;
            foreach ($all_order_statuses as $key => $value) {
                if (strtolower($value) == strtolower($order_status)) {
                    $order_status = $key;
                    $status_founded = TRUE;
                    break;
                }
            }
            if (!$status_founded) {
                $order_status = 'wc-pending';
            }
        }

        $this->order_data = array(
            'ID' => $this->getOrderID(),
            'post_title' => 'Order &ndash; ' . date_i18n('F j, Y @ h:i A', strtotime($this->getValue('date'))),
            'post_content' => '',
            'post_date' => $this->getValue('date'),
            'post_date_gmt' => get_gmt_from_date($this->getValue('date')),
            'post_status' => $order_status,
            'ping_status' => 'closed',
            'post_password' => uniqid('order_'),
            'post_excerpt' => $this->getValue('customer_provided_note'),
        );

        if (!$this->isNewOrder()) {
            if ($this->getImport()->options['update_all_data'] == 'no') {
                if (!$this->getImport()->options['is_update_dates']) { // preserve date of already existing article when duplicate is found
                    $this->order_data['post_title'] = 'Order &ndash; ' . date_i18n('F j, Y @ h:i A', strtotime($this->getArticleData('post_date')));
                    $this->order_data['post_date'] = $this->getArticleData('post_date');
                    $this->order_data['post_date_gmt'] = $this->getArticleData('post_date_gmt');
                }
                if (!$this->getImport()->options['is_update_status']) { // preserve status and trashed flag
                    $this->order_data['post_status'] = $this->getArticleData('post_status');
                }
                if (!$this->getImport()->options['is_update_excerpt']) { // preserve customer's note
                    $this->order_data['post_excerpt'] = $this->getArticleData('post_excerpt');
                }
            }
        }

        $order_id = wp_update_post($this->order_data);

        if (is_wp_error($order_id)) {
            return $order_id;
        }
    }

    /**
     * @return mixed
     */
    public function getOrderData() {
        return $this->order_data;
    }

    /**
     * @param mixed $order_data
     */
    public function setOrderData($order_data) {
        $this->order_data = $order_data;
    }
}