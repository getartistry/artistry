<?php

namespace wpai_woocommerce_add_on\libraries\importer;

use WC_Order;

require_once dirname(__FILE__) . '/Importer.php';

/**
 * Class OrdersImporter
 * @package wpai_woocommerce_add_on\libraries\importer
 */
class OrdersImporter extends Importer {

    /**
     * @var array
     */
    public $importers = array();

    /**
     *
     * Import WooCommerce Order
     *
     * @return array
     */
    public function import() {

        $data = $this->getParsedData()['pmwi_order'];

        $this->importers['orderDetails'] = new ImportOrderDetails($this->getIndexObject(), $this->getOptions(), $data);
        $this->importers['orderAddress'] = new ImportOrderAddress($this->getIndexObject(), $this->getOptions(), $data);
        $this->importers['orderPayment'] = new ImportOrderPayment($this->getIndexObject(), $this->getOptions(), $data);
        $this->importers['orderProductItems'] = new ImportOrderProductItems($this->getIndexObject(), $this->getOptions(), $data);
        $this->importers['orderFeeItems'] = new ImportOrderFeeItems($this->getIndexObject(), $this->getOptions(), $data);
        $this->importers['orderCouponItems'] = new ImportOrderCouponItems($this->getIndexObject(), $this->getOptions(), $data);
        $this->importers['orderShippingItems'] = new ImportOrderShippingItems($this->getIndexObject(), $this->getOptions(), $data);
        $this->importers['orderTaxItems'] = new ImportOrderTaxItems($this->getIndexObject(), $this->getOptions(), $data);
        $this->importers['orderTotal'] = new ImportOrderTotal($this->getIndexObject(), $this->getOptions(), $data);
        $this->importers['orderRefunds'] = new ImportOrderRefunds($this->getIndexObject(), $this->getOptions(), $data);
        $this->importers['orderNotes'] = new ImportOrderNotes($this->getIndexObject(), $this->getOptions(), $data);

        /** @var ImportOrderBase $importer */
        foreach ($this->importers as $importer) {
            $importer->import();
        }
    }

    /**
     *
     * After Import WooCommerce Order
     *
     * @return array
     */
    public function afterPostImport() {

        $old_status = str_replace("wc-", "", $this->getArticleData('post_status'));
        $new_status = str_replace("wc-", "", $this->importers['orderDetails']->getOrderData()['post_status']);

        $orderID = $this->getArticleData('ID');
        // send notifications on order status changed
        if (!empty($orderID) && $new_status !== $old_status && empty($this->getImport()->options['do_not_send_order_notifications'])) {
            do_action('woocommerce_order_status_' . $old_status . '_to_' . $new_status, $this->getPid());
            do_action('woocommerce_order_status_changed', $this->getPid(), $old_status, $new_status);

            if ($new_status == 'completed') {
                do_action('woocommerce_order_status_completed', $this->getPid());
            }
        }

        // send new order notification
        if (empty($orderID) && empty($this->getImport()->options['do_not_send_order_notifications'])) {
            /** @var WC_Order $order */
            $order = wc_get_order($this->getPid());

            do_action('woocommerce_order_status_' . $new_status, $this->getPid());
            do_action('woocommerce_order_status_pending_to_' . $new_status, $this->getPid());
            do_action('woocommerce_before_resend_order_emails', $order);

            // Load mailer
            $mailer = WC()->mailer();
            $email_to_send = 'new_order';
            $mails = $mailer->get_emails();
            if (!empty($mails)) {
                foreach ($mails as $mail) {
                    if ($mail->id == $email_to_send) {
                        $mail->trigger($this->getPid());
                        $this->getLogger() and call_user_func($this->getLogger(), sprintf(__('- %s email notification has beed sent. ...', \PMWI_Plugin::TEXT_DOMAIN), $mail->title));
                    }
                }
            }
            do_action('woocommerce_after_resend_order_email', $order, $email_to_send);
        }
        update_option('wp_all_import_previously_updated_order_' . $this->getImport()->id, $this->getPid());
    }
}
