<?php

class Eh_Stripe_Hooks {

    protected $eh_stripe_option;
    protected $alipay_payment;
            function __construct() {
        $this->eh_stripe_option = get_option("woocommerce_eh_stripe_pay_settings");
        if(isset($this->eh_stripe_option['eh_stripe_alipay']))
        {
            if ($this->eh_stripe_option['eh_stripe_alipay'] === 'yes') {
                add_action('wp', array($this, 'set_alipay'));
                add_action('woocommerce_available_payment_gateways', array($this, 'gateways_hide_on_review'));
                add_action('woocommerce_review_order_after_submit', array($this, 'add_cancel_order_elements'));
            }
        }
    }
    
    public function add_cancel_order_elements() {
        if ($this->alipay_payment) {
            printf('<a href="' . wc_get_checkout_url() . '" style="text-align: center;" class="button alt eh_cancel_order" >Cancel Order</a>');
        }
    }
    
    function set_alipay()
    {
        if (isset($_REQUEST['source']) && isset($_REQUEST['client_secret']) && isset($_REQUEST['livemode'])) {
            $this->alipay_payment = true;
            setcookie("eh_alipay_payment", true);
        }
        else
        {
            if (isset($_COOKIE["eh_alipay_payment"])) {
                unset($_COOKIE["eh_alipay_payment"]);
                setcookie("eh_alipay_payment", '', time() - 3600);
            }
        }
    }
    
    public function gateways_hide_on_review($gateways) {
        if ($this->alipay_payment) {
            foreach ($gateways as $id => $name) {
                if ($id !== 'eh_stripe_pay') {
                    unset($gateways[$id]);
                }
            }
            return $gateways;
        }
        return $gateways;
    }

}
