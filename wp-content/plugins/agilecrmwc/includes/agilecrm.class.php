<?php
/*
 * Agile CRM WooCommerce Plugin
 * Version: 1.0
 * Author: Purushotham Reddy J
 * Release date: Feb 26, 2015
 * Last updated: April 17, 2015
 * WooCommerce compatible versions >= 2.2
 */

class AgileCRM_Customer
{

    public $first_name, $last_name, $company, $email, $phone, $address;

    public function getAgileFormat()
    {
        return array(
            "properties" => array(
                array(
                    "name" => "first_name",
                    "value" => $this->first_name,
                    "type" => "SYSTEM"
                ),
                array(
                    "name" => "last_name",
                    "value" => $this->last_name,
                    "type" => "SYSTEM"
                ),
                array(
                    "name" => "company",
                    "value" => $this->company,
                    "type" => "SYSTEM"
                ),
                array(
                    "name" => "email",
                    "value" => $this->email,
                    "type" => "SYSTEM"
                ),
                array(
                    "name" => "phone",
                    "value" => $this->phone,
                    "type" => "SYSTEM"
                ),
                array(
                    "name" => "address",
                    "value" => json_encode($this->address),
                    "type" => "SYSTEM"
                )
            )
        );
    }
}

class AgileCRM_Address
{

    public $address, $city, $state, $zip, $country;

}

class AgileCRM_Product
{

    public $id, $name, $cost, $quantity, $sku, $categories = array();

}

class AgileCRM_Order
{

    public $id, $status, $billingAddress, $shippingAddress, $grandTotal, $products = array(), $note, $paymentMethod;

}

class AgileCRM
{

    public static $VERSION = '1.0';
    private $endPoint = 'https://%s.agilecrm.com/ecommerce?api-key=%s';
    private $pluginType = 'WooCommerce';
    public static $hooks = array(
        "customer.created" => "CUSTOMER_CREATED",
        "customer.updated" => "CUSTOMER_UPDATED",
        "order.created" => "ORDER_CREATED",
        "order.updated" => "ORDER_UPDATED",
        "note.created" => "NOTE_CREATED",
    );
    public $hook, $payLoad, $customerEmail, $syncAsTags;

    public function post()
    {
        global $AGILEWC_DOMAIN, $AGILEWC_KEY;

        $postData = array(
            'email' => $this->customerEmail,
            'hook' => $this->hook,
            'payLoad' => json_encode($this->payLoad),
            'pluginType' => $this->pluginType,
            'syncAsTags' => $this->syncAsTags
        );

        $curl = new Curl();
        $curl->post(sprintf($this->endPoint, $AGILEWC_DOMAIN, $AGILEWC_KEY), $postData);
        $resp = (array) $curl->response;
        return isset($resp['success']);
    }
}
