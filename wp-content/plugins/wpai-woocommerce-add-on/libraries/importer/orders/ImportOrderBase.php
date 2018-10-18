<?php

namespace wpai_woocommerce_add_on\libraries\importer;

use wpai_woocommerce_add_on\libraries\parser\ParserInterface;

/**
 * Created by PhpStorm.
 * User: cmd
 * Date: 11/15/17
 * Time: 2:10 PM
 */
abstract class ImportOrderBase extends ImportBase {

    /**
     * @var
     */
    public $order_data;

    /**
     * @return mixed
     */
    public function getOrderID() {
        return $this->index->getPid();
    }

    /**
     * @return bool|\WC_Order
     */
    public function getOrder() {
        return wc_get_order($this->getOrderID());
    }

    /**
     * @return ParserInterface
     */
    public function getParser(){
        return $this->getOptions()->getParser();
    }

    /**
     * @return boolean
     */
    public function isNewOrder() {
        $orderID = $this->getArticleData('ID');
        return empty($orderID) ? TRUE : FALSE;
    }
}