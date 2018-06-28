<?php

if (!defined('ABSPATH')) {
    exit;
}

class EH_Stripe_Payment extends WC_Payment_Gateway {

    public function __construct() {
        $alipay_cur = array('AUD', 'CAD', 'EUR', 'GBP', 'HKD', 'JPY', 'NZD', 'SGD', 'USD');
        $this->id = 'eh_stripe_pay';
        $this->method_title = __('Stripe Payment', 'eh-stripe-gateway');
        $this->has_fields = true;
        $this->supports = array(
            'products',
            'refunds',
        );
        $this->init_form_fields();
        $this->init_settings();
        $this->enabled = $this->get_option('enabled');
        $this->title = $this->get_option('title');
        $this->description = $this->get_option('description');
        $this->eh_stripe_order_button = $this->get_option('eh_stripe_order_button');
        $this->eh_stripe_checkout_locale = $this->get_option('eh_stripe_checkout_locale');
        $this->eh_stripe_mode = $this->get_option('eh_stripe_mode');
        $this->eh_stripe_test_secret_key = $this->get_option('eh_stripe_test_secret_key');
        $this->eh_stripe_test_publishable_key = $this->get_option('eh_stripe_test_publishable_key');
        $this->eh_stripe_live_secret_key = $this->get_option('eh_stripe_live_secret_key');
        $this->eh_stripe_live_publishable_key = $this->get_option('eh_stripe_live_publishable_key');
        $this->eh_stripe_capture = 'yes' === $this->get_option('eh_stripe_capture', 'yes');
        $this->eh_stripe_currency_converter = 'yes' === $this->get_option('eh_stripe_currency_converter', 'yes');
        $this->eh_stripe_currency_converter_description = $this->get_option('eh_stripe_currency_converter_description');
        $this->eh_stripe_checkout_cards = $this->get_option('eh_stripe_checkout_cards') ? $this->get_option('eh_stripe_checkout_cards') : array();
        $this->eh_stripe_enforce_cards = 'yes' === $this->get_option('eh_stripe_enforce_cards', 'yes');
        $this->eh_stripe_email_receipt = 'yes' === $this->get_option('eh_stripe_email_receipt', 'yes');
        //$this->eh_stripe_bitcoin = 'USD' === strtoupper(get_woocommerce_currency()) && 'yes' === $this->get_option('eh_stripe_bitcoin'); // temperary disabled 2018-05-29 Stripe withdrew support for Bitcoin  search 'bitcoin' and comment all bitcoin related code
        $this->eh_stripe_alipay = (in_array(get_woocommerce_currency(), $alipay_cur)) && 'yes' === $this->get_option('eh_stripe_alipay');
        $this->eh_stripe_form_description = $this->get_option('eh_stripe_form_description');
        $this->eh_stripe_billing_address_check = 'yes' === $this->get_option('eh_stripe_billing_address_check');
        $this->eh_stripe_checkout_image_check = 'yes' === $this->get_option('eh_stripe_checkout_image_check', 'yes');
        $this->eh_stripe_checkout_image = ('' == $this->get_option('eh_stripe_checkout_image')) ? EH_STRIPE_MAIN_URL_PATH . "assets/img/stripe.png" : $this->get_option('eh_stripe_checkout_image');
        $this->eh_stripe_enable_save_cards = 'yes' === $this->get_option('eh_stripe_enable_save_cards');
        $this->eh_stripe_logging = 'yes' === $this->get_option('eh_stripe_logging');
        $this->eh_stripe_zerocurrency = array("BIF", "CLP", "DJF", "GNF", "JPY", "KMF", "KRW", "MGA", "PYG", "RWF", "VND", "VUV", "XAF", "XOF", "XPF");

        $this->order_button_text = __($this->eh_stripe_order_button, 'eh-stripe-gateway');

        if ('yes' === $this->enabled && ('' != $this->eh_stripe_test_secret_key || '' != $this->eh_stripe_live_secret_key ) && ('' != $this->eh_stripe_test_publishable_key || '' != $this->eh_stripe_live_publishable_key )) {
            $this->method_description = sprintf(__("Accept credit card payments directly on your website via Stripe payment gateway.", 'eh-stripe-gateway'));
        } else {
            $this->method_description = sprintf('<div class="updated inline notice is-dismissible"><table><tr><td><span style="font-size: 1.2em;">' . __('Stripe provides payment services over 25 countries', 'eh-stripe-gateway') . '</span></td><td> - </td><td><button class="button-primary" style="width:100%%"><a href="https://stripe.com/global" target="_blank" style="color: antiquewhite; text-decoration: none;">' . __('Available Countries', 'eh-stripe-gateway') . '</a></button></td></tr><tr><td><span style="font-size: 1.2em;">' . __('If you don\'t have Stripe Account, get it for free easily ', 'eh-stripe-gateway') . '</span></td><td> - </td><td><button class="button-primary" style="width:100%%"><a href="https://dashboard.stripe.com/register" target="_blank" style="color: antiquewhite; text-decoration: none;">' . __('Register Now', 'eh-stripe-gateway') . '</a></button></td></tr><tr><td><span style="font-size: 1.2em;">' . __('Get your Access Keys and put it here ', 'eh-stripe-gateway') . '</span></td><td> - </td><td><button class="button-primary" style="width:100%%"><a href="https://dashboard.stripe.com/account/apikeys" target="_blank" style="color: antiquewhite; text-decoration: none;">' . __('Get API Keys', 'eh-stripe-gateway') . '</a></button></td></tr></table></div>');
        }
        if ('test' === $this->eh_stripe_mode) {
            $this->description = $this->description . sprintf('<br>' . '<strong>' . __('Stripe TEST MODE Enabled: ', 'eh-stripe-gateway') . '</strong>' . __(' Use these ', 'eh-stripe-gateway') . '<a href="https://stripe.com/docs/testing" target="_blank">' . __(' Test Card Details ', 'eh-stripe-gateway') . '</a>' . __(' for Testing.', 'eh-stripe-gateway'));
            $this->description = trim($this->description);
        }
        if ('test' == $this->eh_stripe_mode) {
            \Stripe\Stripe::setApiKey($this->eh_stripe_test_secret_key);
        } else {
            \Stripe\Stripe::setApiKey($this->eh_stripe_live_secret_key);
        }

        if (is_admin()) {
            add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
        }
        
        if($this->eh_stripe_alipay && is_checkout()){
            add_filter('woocommerce_ajax_get_endpoint', array($this, 'eh_ajax_get_endpoint'), 10, 2);
        }
        // Hooks
        add_action('wp_enqueue_scripts', array($this, 'payment_scripts'));
        if (isset($_COOKIE['eh_alipay_payment']) && $this->eh_stripe_alipay) {
            $this->title = $this->get_option('eh_stripe_alipay_title');
            $this->description = $this->get_option('eh_stripe_alipay_description');
            $this->order_button_text = __('Place Order', 'eh-stripe-gateway');
        }
                        
        //fix for stripe overview menu not showing without refresh after disable and save
        if ('yes' == $this->get_option('overview')) {
            if (!class_exists('EH_Stripe_Overview')) {
            include(EH_STRIPE_MAIN_PATH . "includes/class-stripe-overview.php");
            }
            $stripe_overview = new EH_Stripe_Overview();            
            global $submenu;
            $main_menu = 'woocommerce';
            if (isset($submenu[$main_menu]) && in_array('eh-stripe-overview', wp_list_pluck($submenu[$main_menu], 2))) {
                // Submenu exists - do nothing.
            } else {
                // Submenu do not exists - add .
                add_submenu_page('woocommerce', __('Stripe Overview', 'eh-stripe-gateway'), __('Stripe Overview', 'eh-stripe-gateway'), 'manage_woocommerce', 'eh-stripe-overview', array(
                    $stripe_overview,
                    'eh_stripe_template_display'
                ));
            }
        } else {
            remove_submenu_page('woocommerce', 'eh-stripe-overview');
        }
    }

    function eh_ajax_get_endpoint($end, $request) {
        return add_query_arg('wc-ajax', $request, remove_query_arg(array('remove_item', 'add-to-cart', 'added-to-cart'), home_url(wp_unslash($_SERVER['REQUEST_URI']))));
    }
    //function to add activation window
    public function admin_options() {
        include('market.php');
        $plugin_name = 'stripepaymentgateway';
        parent::admin_options();
    }

    public function get_icon() {
        $ext = version_compare(WC()->version, '2.6', '>=') ? '.svg' : '.png';
        $style = version_compare(WC()->version, '2.6', '>=') ? 'style="margin-left: 0.3em"' : '';
        $icon = '';
        if (is_array($this->eh_stripe_checkout_cards) && !isset($_COOKIE['eh_alipay_payment'])) {
            if (in_array('Visa', $this->eh_stripe_checkout_cards)) {
                $icon .= '<img src="' . WC_HTTPS::force_https_url(WC()->plugin_url() . '/assets/images/icons/credit-cards/visa' . $ext) . '" alt="Visa" width="32" title="VISA" ' . $style . ' />';
            }
            if (in_array('MasterCard', $this->eh_stripe_checkout_cards)) {
                $icon .= '<img src="' . WC_HTTPS::force_https_url(WC()->plugin_url() . '/assets/images/icons/credit-cards/mastercard' . $ext) . '" alt="Mastercard" width="32" title="Master Card" ' . $style . ' />';
            }
            if (in_array('American Express', $this->eh_stripe_checkout_cards)) {
                $icon .= '<img src="' . WC_HTTPS::force_https_url(WC()->plugin_url() . '/assets/images/icons/credit-cards/amex' . $ext) . '" alt="Amex" width="32" title="American Express" ' . $style . ' />';
            }
            if ('USD' === get_woocommerce_currency()) {
                if (in_array('Discover', $this->eh_stripe_checkout_cards)) {
                    $icon .= '<img src="' . WC_HTTPS::force_https_url(WC()->plugin_url() . '/assets/images/icons/credit-cards/discover' . $ext) . '" alt="Discover" width="32" title="Discover" ' . $style . ' />';
                }
                if (in_array('JCB', $this->eh_stripe_checkout_cards)) {
                    $icon .= '<img src="' . WC_HTTPS::force_https_url(WC()->plugin_url() . '/assets/images/icons/credit-cards/jcb' . $ext) . '" alt="JCB" width="32" title="JCB" ' . $style . ' />';
                }
                if (in_array('Diners Club', $this->eh_stripe_checkout_cards)) {
                    $icon .= '<img src="' . WC_HTTPS::force_https_url(WC()->plugin_url() . '/assets/images/icons/credit-cards/diners' . $ext) . '" alt="Diners" width="32" title="Diners Club" ' . $style . ' />';
                }
            }
        }
//        if ($this->eh_stripe_bitcoin && !isset($_COOKIE['eh_alipay_payment'])) {
//            $icon .= '<img src="' . WC_HTTPS::force_https_url(EH_STRIPE_MAIN_URL_PATH . 'assets/img/bitcoin.png') . '" alt="Bitcoin" width="52" title="Bitcoin" ' . $style . ' />';
//        }
        if ( isset($_COOKIE['eh_alipay_payment']) || $this->eh_stripe_alipay) {
            $icon .= '<img src="' . WC_HTTPS::force_https_url(EH_STRIPE_MAIN_URL_PATH . 'assets/img/alipay.png') . '" alt="Alipay" width="52" title="Alipay" ' . $style . ' />';
        }
        return apply_filters('woocommerce_gateway_icon', $icon, $this->id);
    }

    public function is_available() {
        if ('yes' === $this->enabled) {
            if (!$this->eh_stripe_mode && is_checkout()) {
                return false;
            }
            if ('test' === $this->eh_stripe_mode) {
                if (!$this->eh_stripe_test_secret_key || !$this->eh_stripe_test_publishable_key) {
                    return false;
                }
            } else {
                if (!$this->eh_stripe_live_secret_key || !$this->eh_stripe_live_publishable_key) {
                    return false;
                }
            }
            return true;
        }
        return false;
    }

    public function init_form_fields() {
        $this->form_fields = include( 'eh-stripe-settings-page.php' );
        wp_enqueue_media();
        $page = (isset($_GET['page'])) ? esc_attr($_GET['page']) : false;
        $tab = (isset($_GET['tab'])) ? esc_attr($_GET['tab']) : false;
        $section = (isset($_GET['section'])) ? esc_attr($_GET['section']) : false;
        if ('wc-settings' != $page && 'checkout' != $tab && 'eh_stripe_pay' != $section)
            return;
        wc_enqueue_js("
                    $('.description').css({'font-style':'normal'});
                    jQuery( '#woocommerce_eh_stripe_pay_eh_stripe_mode' ).on( 'change', function() {
                                    var test    = jQuery( '#woocommerce_eh_stripe_pay_eh_stripe_test_publishable_key, #woocommerce_eh_stripe_pay_eh_stripe_test_secret_key' ).closest( 'tr' ),
                                    live = jQuery( '#woocommerce_eh_stripe_pay_eh_stripe_live_publishable_key, #woocommerce_eh_stripe_pay_eh_stripe_live_secret_key' ).closest( 'tr' );

                                    if ('test' === jQuery( this ).val()) {
                                            test.show();
                                            live.hide();
                                    } else {
                                            test.hide();
                                            live.show();
                                    }
                            }).change();
                    jQuery( document ).ready( function( $ ) {
                            var file_frame;
                            jQuery('#eh_stripe_preview').on('click', function( event ){
                                file_frame = wp.media.frames.file_frame = wp.media({
                                        title: 'Select a image to set Stripe Checkout image',
                                        button: {
                                                text: 'Use this image',
                                        },
                                        multiple: false
                                });
                                file_frame.on( 'select', function() {
                                        attachment = file_frame.state().get('selection').first().toJSON();
                                        console.log(attachment);
                                        $( '#eh_stripe_preview' ).attr( 'src', attachment.url );
                                        $( '#woocommerce_eh_stripe_pay_eh_stripe_checkout_image' ).val( attachment.url );
                                });
                                file_frame.open();
                            });
                    });
                    jQuery( '#woocommerce_eh_stripe_pay_eh_stripe_checkout_image_check' ).on( 'change', function() {
                        var checkout_image    = jQuery( '#woocommerce_eh_stripe_pay_eh_stripe_checkout_image').closest( 'tr' );
                        if ( jQuery( this ).is( ':checked' ) ) {
                                checkout_image.show();
                        } else {
                                checkout_image.hide();
                        }
                    }).change();
                    jQuery( '#woocommerce_eh_stripe_pay_eh_stripe_currency_converter' ).on( 'change', function() {
                        var conversion_alert    = jQuery( '#woocommerce_eh_stripe_pay_eh_stripe_currency_converter_description').closest( 'tr' );
                        if ( jQuery( this ).is( ':checked' ) ) {
                                conversion_alert.show();
                        } else {
                                conversion_alert.hide();
                        }
                    }).change();
                    jQuery( '#woocommerce_eh_stripe_pay_eh_stripe_alipay' ).on( 'change', function() {
                        var alipay_fields   = jQuery( '#woocommerce_eh_stripe_pay_eh_stripe_alipay_title,#woocommerce_eh_stripe_pay_eh_stripe_alipay_description,#woocommerce_eh_stripe_pay_eh_stripe_alipay_order_button').closest( 'tr' );
                        if ( jQuery( this ).is( ':checked' ) ) {
                                alipay_fields.show();
                        } else {
                                alipay_fields.hide();
                        }
                    }).change();
            ");
    }

    public function get_payment_values() {
        $list = '<span style="padding: 5px;">
            <select class="select" name="stripe_currency" id="stripe_currency">				
                <option  value="-1">Select Currency</option>
                <option  value="AED">United Arab Emirates Dirham (AED)</option>
                <option  value="AFN">Afghan Afghani (AFN)</option>
                <option  value="ALL">Albanian Lek (ALL)</option>
                <option  value="AMD">Armenian Dram (AMD)</option>
                <option  value="ANG">Netherlands Antillean Guilder (ANG)</option>
                <option  value="AOA">Angolan Kwanza (AOA)</option>
                <option  value="ARS">Argentine Peso (ARS)</option>
                <option  value="AUD">Australian Dollar (A$)</option>
                <option  value="AWG">Aruban Florin (AWG)</option>
                <option  value="AZN">Azerbaijani Manat (AZN)</option>
                <option  value="BAM">Bosnia-Herzegovina Convertible Mark (BAM)</option>
                <option  value="BBD">Barbadian Dollar (BBD)</option>
                <option  value="BDT">Bangladeshi Taka (BDT)</option>
                <option  value="BGN">Bulgarian Lev (BGN)</option>
                <option  value="BHD">Bahraini Dinar (BHD)</option>
                <option  value="BIF">Burundian Franc (BIF)</option>
                <option  value="BMD">Bermudan Dollar (BMD)</option>
                <option  value="BND">Brunei Dollar (BND)</option>
                <option  value="BOB">Bolivian Boliviano (BOB)</option>
                <option  value="BRL">Brazilian Real (R$)</option>
                <option  value="BSD">Bahamian Dollar (BSD)</option>
                <option  value="BTN">Bhutanese Ngultrum (BTN)</option>
                <option  value="BWP">Botswanan Pula (BWP)</option>
                <option  value="BYN">BYN (BYN)</option>
                <option  value="BYR">Belarusian Ruble (BYR)</option>
                <option  value="BZD">Belize Dollar (BZD)</option>
                <option  value="CAD">Canadian Dollar (CA$)</option>
                <option  value="CDF">Congolese Franc (CDF)</option>
                <option  value="CHF">Swiss Franc (CHF)</option>
                <option  value="CLF">Chilean Unit of Account (UF) (CLF)</option>
                <option  value="CLP">Chilean Peso (CLP)</option>
                <option  value="CNH">CNH (CNH)</option>
                <option  value="CNY">Chinese Yuan (CN¥)</option>
                <option  value="COP">Colombian Peso (COP)</option>
                <option  value="CRC">Costa Rican Colón (CRC)</option>
                <option  value="CUP">Cuban Peso (CUP)</option>
                <option  value="CVE">Cape Verdean Escudo (CVE)</option>
                <option  value="CZK">Czech Republic Koruna (CZK)</option>
                <option  value="DEM">German Mark (DEM)</option>
                <option  value="DJF">Djiboutian Franc (DJF)</option>
                <option  value="DKK">Danish Krone (DKK)</option>
                <option  value="DOP">Dominican Peso (DOP)</option>
                <option  value="DZD">Algerian Dinar (DZD)</option>
                <option  value="EGP">Egyptian Pound (EGP)</option>
                <option  value="ERN">Eritrean Nakfa (ERN)</option>
                <option  value="ETB">Ethiopian Birr (ETB)</option>
                <option  value="EUR">Euro (€)</option>
                <option  value="FIM">Finnish Markka (FIM)</option>
                <option  value="FJD">Fijian Dollar (FJD)</option>
                <option  value="FKP">Falkland Islands Pound (FKP)</option>
                <option  value="FRF">French Franc (FRF)</option>
                <option  value="GBP">British Pound (£)</option>
                <option  value="GEL">Georgian Lari (GEL)</option>
                <option  value="GHS">Ghanaian Cedi (GHS)</option>
                <option  value="GIP">Gibraltar Pound (GIP)</option>
                <option  value="GMD">Gambian Dalasi (GMD)</option>
                <option  value="GNF">Guinean Franc (GNF)</option>
                <option  value="GTQ">Guatemalan Quetzal (GTQ)</option>
                <option  value="GYD">Guyanaese Dollar (GYD)</option>
                <option  value="HKD">Hong Kong Dollar (HK$)</option>
                <option  value="HNL">Honduran Lempira (HNL)</option>
                <option  value="HRK">Croatian Kuna (HRK)</option>
                <option  value="HTG">Haitian Gourde (HTG)</option>
                <option  value="HUF">Hungarian Forint (HUF)</option>
                <option  value="IDR">Indonesian Rupiah (IDR)</option>
                <option  value="IEP">Irish Pound (IEP)</option>
                <option  value="ILS">Israeli New Sheqel (₪)</option>
                <option  value="INR">Indian Rupee (₹)</option>
                <option  value="IQD">Iraqi Dinar (IQD)</option>
                <option  value="IRR">Iranian Rial (IRR)</option>
                <option  value="ISK">Icelandic Króna (ISK)</option>
                <option  value="ITL">Italian Lira (ITL)</option>
                <option  value="JMD">Jamaican Dollar (JMD)</option>
                <option  value="JOD">Jordanian Dinar (JOD)</option>
                <option  value="JPY">Japanese Yen (¥)</option>
                <option  value="KES">Kenyan Shilling (KES)</option>
                <option  value="KGS">Kyrgystani Som (KGS)</option>
                <option  value="KHR">Cambodian Riel (KHR)</option>
                <option  value="KMF">Comorian Franc (KMF)</option>
                <option  value="KPW">North Korean Won (KPW)</option>
                <option  value="KRW">South Korean Won (₩)</option>
                <option  value="KWD">Kuwaiti Dinar (KWD)</option>
                <option  value="KYD">Cayman Islands Dollar (KYD)</option>
                <option  value="KZT">Kazakhstani Tenge (KZT)</option>
                <option  value="LAK">Laotian Kip (LAK)</option>
                <option  value="LBP">Lebanese Pound (LBP)</option>
                <option  value="LKR">Sri Lankan Rupee (LKR)</option>
                <option  value="LRD">Liberian Dollar (LRD)</option>
                <option  value="LSL">Lesotho Loti (LSL)</option>
                <option  value="LTL">Lithuanian Litas (LTL)</option>
                <option  value="LVL">Latvian Lats (LVL)</option>
                <option  value="LYD">Libyan Dinar (LYD)</option>
                <option  value="MAD">Moroccan Dirham (MAD)</option>
                <option  value="MDL">Moldovan Leu (MDL)</option>
                <option  value="MGA">Malagasy Ariary (MGA)</option>
                <option  value="MKD">Macedonian Denar (MKD)</option>
                <option  value="MMK">Myanmar Kyat (MMK)</option>
                <option  value="MNT">Mongolian Tugrik (MNT)</option>
                <option  value="MOP">Macanese Pataca (MOP)</option>
                <option  value="MRO">Mauritanian Ouguiya (MRO)</option>
                <option  value="MUR">Mauritian Rupee (MUR)</option>
                <option  value="MVR">Maldivian Rufiyaa (MVR)</option>
                <option  value="MWK">Malawian Kwacha (MWK)</option>
                <option  value="MXN">Mexican Peso (MX$)</option>
                <option  value="MYR">Malaysian Ringgit (MYR)</option>
                <option  value="MZN">Mozambican Metical (MZN)</option>
                <option  value="NAD">Namibian Dollar (NAD)</option>
                <option  value="NGN">Nigerian Naira (NGN)</option>
                <option  value="NIO">Nicaraguan Córdoba (NIO)</option>
                <option  value="NOK">Norwegian Krone (NOK)</option>
                <option  value="NPR">Nepalese Rupee (NPR)</option>
                <option  value="NZD">New Zealand Dollar (NZ$)</option>
                <option  value="OMR">Omani Rial (OMR)</option>
                <option  value="PAB">Panamanian Balboa (PAB)</option>
                <option  value="PEN">Peruvian Nuevo Sol (PEN)</option>
                <option  value="PGK">Papua New Guinean Kina (PGK)</option>
                <option  value="PHP">Philippine Peso (PHP)</option>
                <option  value="PKG">PKG (PKG)</option>
                <option  value="PKR">Pakistani Rupee (PKR)</option>
                <option  value="PLN">Polish Zloty (PLN)</option>
                <option  value="PYG">Paraguayan Guarani (PYG)</option>
                <option  value="QAR">Qatari Rial (QAR)</option>
                <option  value="RON">Romanian Leu (RON)</option>
                <option  value="RSD">Serbian Dinar (RSD)</option>
                <option  value="RUB">Russian Ruble (RUB)</option>
                <option  value="RWF">Rwandan Franc (RWF)</option>
                <option  value="SAR">Saudi Riyal (SAR)</option>
                <option  value="SBD">Solomon Islands Dollar (SBD)</option>
                <option  value="SCR">Seychellois Rupee (SCR)</option>
                <option  value="SDG">Sudanese Pound (SDG)</option>
                <option  value="SEK">Swedish Krona (SEK)</option>
                <option  value="SGD">Singapore Dollar (SGD)</option>
                <option  value="SHP">St. Helena Pound (SHP)</option>
                <option  value="SKK">Slovak Koruna (SKK)</option>
                <option  value="SLL">Sierra Leonean Leone (SLL)</option>
                <option  value="SOS">Somali Shilling (SOS)</option>
                <option  value="SRD">Surinamese Dollar (SRD)</option>
                <option  value="STD">São Tomé &amp; Príncipe Dobra (STD)</option>
                <option  value="SVC">Salvadoran Colón (SVC)</option>
                <option  value="SYP">Syrian Pound (SYP)</option>
                <option  value="SZL">Swazi Lilangeni (SZL)</option>
                <option  value="THB">Thai Baht (THB)</option>
                <option  value="TJS">Tajikistani Somoni (TJS)</option>
                <option  value="TMT">Turkmenistani Manat (TMT)</option>
                <option  value="TND">Tunisian Dinar (TND)</option>
                <option  value="TOP">Tongan Paʻanga (TOP)</option>
                <option  value="TRY">Turkish Lira (TRY)</option>
                <option  value="TTD">Trinidad &amp; Tobago Dollar (TTD)</option>
                <option  value="TWD">New Taiwan Dollar (NT$)</option>
                <option  value="TZS">Tanzanian Shilling (TZS)</option>
                <option  value="UAH">Ukrainian Hryvnia (UAH)</option>
                <option  value="UGX">Ugandan Shilling (UGX)</option>
                <option  value="USD">US Dollar ($)</option>
                <option  value="UYU">Uruguayan Peso (UYU)</option>
                <option  value="UZS">Uzbekistani Som (UZS)</option>
                <option  value="VEF">Venezuelan Bolívar (VEF)</option>
                <option  value="VND">Vietnamese Dong (₫)</option>
                <option  value="VUV">Vanuatu Vatu (VUV)</option>
                <option  value="WST">Samoan Tala (WST)</option>
                <option  value="XAF">Central African CFA Franc (FCFA)</option>
                <option  value="XCD">East Caribbean Dollar (EC$)</option>
                <option  value="XDR">Special Drawing Rights (XDR)</option>
                <option  value="XOF">West African CFA Franc (CFA)</option>
                <option  value="XPF">CFP Franc (CFPF)</option>
                <option  value="YER">Yemeni Rial (YER)</option>
                <option  value="ZAR">South African Rand (ZAR)</option>
                <option  value="ZMK">Zambian Kwacha (1968–2012) (ZMK)</option>
                <option  value="ZMW">Zambian Kwacha (ZMW)</option>
                <option  value="ZWL">Zimbabwean Dollar (2009) (ZWL)</option>
                </select></span><span style="padding: 5px;"><center><strong> ' . WC()->cart->total . ' ' . get_woocommerce_currency() . ' </strong><strong id="eh-stripe-pay_converted_amount"> </strong><br><u>' . $this->eh_stripe_currency_converter_description . '</u></center></span>
                ';
        return $list;
    }

    public function payment_scripts() {
        if (!isset($_COOKIE['eh_alipay_payment'])) {
            wp_enqueue_script('stripe', 'https://checkout.stripe.com/checkout.js');
            wp_enqueue_script('eh_stripe_checkout', plugins_url('assets/js/eh_stripe_checkout.js', EH_STRIPE_MAIN_FILE), array('stripe'), true);
            if ('test' == $this->eh_stripe_mode) {
                $public_key = $this->eh_stripe_test_publishable_key;
            } else {
                $public_key = $this->eh_stripe_live_publishable_key;
            }
            if ($this->eh_stripe_alipay && !is_order_received_page()) {
//                wp_enqueue_script('stripe_js', 'https://js.stripe.com/v3/');
//                wp_enqueue_script('eh_alipay_gen', EH_STRIPE_MAIN_URL_PATH . 'assets/js/eh-alipay.js', array("stripe_js"));
                wp_register_script('stripe_v3_js', 'https://js.stripe.com/v3/');
                wp_enqueue_script('eh_alipay_gen', EH_STRIPE_MAIN_URL_PATH . 'assets/js/eh-alipay.js', array("stripe_v3_js"));
                $eh_alipay_params = array(
                    'button' => $this->get_option('eh_stripe_alipay_order_button'),
                    'key' => $public_key,
                    'amount' => $this->get_stripe_amount(WC()->cart->total),
                    'currency' => strtolower(get_woocommerce_currency()),
                    'redirect' => esc_url(wc_get_checkout_url())
                );
                wp_localize_script('eh_alipay_gen', 'eh_alipay_params', $eh_alipay_params);
            }
            $show_zip_code = apply_filters('eh_stripe_ccshow_zipcode',true);
            $stripe_params = array(
                'key' => $public_key,
                'show_zip_code' => $show_zip_code,
                'i18n_terms' => __('Please accept the terms and conditions first', 'eh-stripe-gateway'),
                'i18n_required_fields' => __('Please fill in required checkout fields first', 'eh-stripe-gateway'),
            );
            wp_localize_script('eh_stripe_checkout', 'eh_stripe_val', apply_filters('eh_stripe_val', $stripe_params));
        }
    }

    public function payment_fields() {
        $user = wp_get_current_user();
        if ($user->ID) {
            $user_email = get_user_meta($user->ID, 'billing_email', true);
            $user_email = $user_email ? $user_email : $user->user_email;
        } else {
            $user_email = '';
        }
        echo '<div class="status-box">';
        if ($this->eh_stripe_currency_converter && !isset($_COOKIE['eh_alipay_payment'])) {
            echo $this->get_currency_script();
            echo apply_filters('eh_stripe_currency', wpautop(( $this->get_payment_values())));
        }
        if ($this->description) {
            echo apply_filters('eh_stripe_desc', wpautop(wp_kses_post("<span>" . $this->description . "</span>")));
        }
        echo "</div>";
        if (!isset($_COOKIE['eh_alipay_payment'])) {
            $pay_button_text = __('Pay', 'eh-stripe-gateway');
            if (is_checkout_pay_page()) {
                $order_id = get_query_var('order-pay');
                $order = wc_get_order($order_id);
                $email = (WC()->version < '2.7.0') ? $order->billing_email : $order->get_billing_email();
                echo '<div
                    id="eh-stripe-pay-data"
                    data-panel-label="' . esc_attr($pay_button_text) . '"
                    data-description="' . esc_attr($this->eh_stripe_form_description) . '"
                    data-email="' . esc_attr(($email !== '') ? $email : get_bloginfo('name', 'display')) . '"
                    data-amount="' . esc_attr($this->get_stripe_amount(((WC()->version < '2.7.0') ? $order->order_total : $order->get_total()))) . '"
                    data-name="' . esc_attr(sprintf(get_bloginfo('name', 'display'))) . '"
                    data-currency="' . esc_attr(((WC()->version < '2.7.0') ? $order->order_currency : $order->get_currency())) . '"
                    data-image="' . esc_attr($this->eh_stripe_checkout_image_check ? $this->eh_stripe_checkout_image : '') . '"'
                    //data-bitcoin="' . esc_attr($this->eh_stripe_bitcoin ? 'true' : 'false' ) . '"
                    .'data-allow-remember-me="' . esc_attr($this->eh_stripe_enable_save_cards ? 'true' : 'false' ) . '"
                    data-billing-address="' . esc_attr($this->eh_stripe_billing_address_check ? 'true' : 'false') . '"
                    data-locale="' . esc_attr($this->eh_stripe_checkout_locale) . '"></div>';
            } else {
                echo '<div
                    id="eh-stripe-pay-data"
                    data-panel-label="' . esc_attr($pay_button_text) . '"
                    data-description="' . esc_attr($this->eh_stripe_form_description) . '"
                    data-email="' . esc_attr($user_email) . '"
                    data-amount="' . esc_attr($this->get_stripe_amount(WC()->cart->total)) . '"
                    data-name="' . esc_attr(sprintf(get_bloginfo('name', 'display'))) . '"
                    data-currency="' . esc_attr(strtolower(get_woocommerce_currency())) . '"
                    data-image="' . esc_attr($this->eh_stripe_checkout_image_check ? $this->eh_stripe_checkout_image : '') . '"'
                    //data-bitcoin="' . esc_attr($this->eh_stripe_bitcoin ? 'true' : 'false' ) . '"
                    .'data-allow-remember-me="' . esc_attr($this->eh_stripe_enable_save_cards ? 'true' : 'false' ) . '"
                    data-billing-address="' . esc_attr($this->eh_stripe_billing_address_check ? 'true' : 'false') . '"
                    data-locale="' . esc_attr($this->eh_stripe_checkout_locale) . '"></div>';
            }
        }
    }

    public function get_currency_script() {
        return
                '   <style>
                    .loader_pay {
                            display: block;
                            position: absolute;
                            left: 0px;
                            top: 0px;
                            width: 100%;
                            height: 100%;
                            z-index: 9999;
                            background: url("' . EH_STRIPE_MAIN_URL_PATH . 'assets/img/load.gif") 50% 50% no-repeat rgb(242, 242, 242) !important;
                            background-size: 25% !important;
                            opacity: 0.7;
                        }
                        .status-box {
                            background: #fff;
                            text-align:center;
                            vertical-align:middle;
                            padding-bottom:15px;
                            display:table;
                            width: 100%;
                          }
                </style>
                
                <script>
                jQuery(".payment_method_eh_stripe_pay #stripe_currency option[value=\'' . get_woocommerce_currency() . '\']").remove();
                jQuery(".payment_method_eh_stripe_pay #stripe_currency").on("change",function()
                {
                    var load="<div class=\"loader_pay\"></div>";
                    jQuery( "#payment" ).prepend( load );
                    var url="' . admin_url("admin-ajax.php") . '";
                    var user_currency=this.value;
                    jQuery.ajax({
                            url: url,
                            type: "post",
                            data: {
                                user_currency : user_currency,
                                action: "eh_spg_currency_convert",
                            },
                            success: function(response) {
                                data=JSON.parse(response)
                                jQuery("#eh-stripe-pay-data").attr("data-amount",data.dec);
                                jQuery("#eh-stripe-pay-data").attr("data-currency",user_currency);
                                if(user_currency != -1)
                                    jQuery("#eh-stripe-pay_converted_amount").html(" = "+data.amo+" "+user_currency);
                                else
                                    jQuery("#eh-stripe-pay_converted_amount").html("");
                                    
                                jQuery( ".loader_pay" ).remove();
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                console.log(textStatus, errorThrown);
                            }
                        });
                });
            </script>';
    }

    public static function eh_spg_currency_convert_callback() {
        $obj = new EH_Stripe_Payment();
        $user_currency = sanitize_text_field($_POST['user_currency']);
        if ($user_currency == -1) {
            die(json_encode(array('dec' => $obj->get_stripe_amount(WC()->cart->total), 'amo' => round(WC()->cart->total, 2))));
        }
        $url = "https://finance.google.com/bctzjpnsun/converter?a=" . WC()->cart->total . "&from=" . get_woocommerce_currency() . "&to=" . $user_currency;
        $data = wp_remote_fopen($url);
        $var1 = '<span class=bld>';
        $var2 = " " . $user_currency;
        $pool = $data;
        $temp1 = strpos($pool, $var1) + strlen($var1);
        $result = substr($pool, $temp1, strlen($pool));
        $dd = strpos($result, $var2);
        if ($dd == 0) {
            $dd = strlen($result);
        }
        $amount = substr($result, 0, $dd);
        die(json_encode(array('dec' => $obj->get_stripe_amount($amount, $user_currency), 'amo' => round($amount, 2))));
    }

    public function get_stripe_amount($total, $currency = '') {
        if (!$currency) {
            $currency = get_woocommerce_currency();
        }
        if (in_array(strtoupper($currency), $this->eh_stripe_zerocurrency)) {
            // Zero decimal currencies
            $total = absint($total);
        } else {
            $total = round($total, 2) * 100; // In cents
        }
        return $total;
    }

    public function reset_stripe_amount($total, $currency = '') {
        if (!$currency) {
            $currency = get_woocommerce_currency();
        }
        if (in_array(strtoupper($currency), $this->eh_stripe_zerocurrency)) {
            // Zero decimal currencies
            $total = absint($total);
        } else {
            $total = round($total, 2) / 100; // In cents
        }
        return $total;
    }

    public function get_clients_details() {
        return array(
            'IP' => $_SERVER['REMOTE_ADDR'],
            'Agent' => $_SERVER['HTTP_USER_AGENT'],
            'Referer' => $_SERVER['HTTP_REFERER']
        );
    }

    private function get_charge_details($wc_order, $token, $order_type, $client, $card_brand, $currency, $amount) {
        $product_name = array();
        foreach ($wc_order->get_items() as $item) {
            array_push($product_name, $item['name']);
        }

        $charge = array(
            'amount' => $amount,
            'currency' => $currency,
            'metadata' => array(
                'order_id' => $wc_order->get_order_number(),
                'Total Tax' => $wc_order->get_total_tax(),
                'Total Shipping' => $wc_order->get_total_shipping(),
                'Customer IP' => $client['IP'],
                'Agent' => $client['Agent'],
                'Referer' => $client['Referer'],
                'WP customer #' => (WC()->version < '2.7.0') ? $wc_order->user_id : $wc_order->get_user_id(),
                'Billing Email' => (WC()->version < '2.7.0') ? $wc_order->billing_email : $wc_order->get_billing_email()
            ),
            'description' => get_bloginfo('blogname') . ' Order #' . $wc_order->get_order_number(),
        );

        $product_list = '';
        foreach ($product_name as $value) {
            $product_list.= $value.' | ';
        }
        $charge['metadata']['Products'] = substr($product_list, 0, 499);
        
        if ('other' != $card_brand) {
            $charge['capture'] = $this->eh_stripe_capture ? 'true' : 'false';
        }
        if ($this->eh_stripe_email_receipt) {
            $charge['receipt_email'] = (WC()->version < '2.7.0') ? $wc_order->billing_email : $wc_order->get_billing_email();
        }
        if (!is_checkout_pay_page()) {
            $charge['shipping'] = array(
                'address' => array(
                    'line1' => (WC()->version < '2.7.0') ? $wc_order->shipping_address_1 : $wc_order->get_shipping_address_1(),
                    'line2' => (WC()->version < '2.7.0') ? $wc_order->shipping_address_2 : $wc_order->get_shipping_address_2(),
                    'city' => (WC()->version < '2.7.0') ? $wc_order->shipping_city : $wc_order->get_shipping_city(),
                    'state' => (WC()->version < '2.7.0') ? $wc_order->shipping_state : $wc_order->get_shipping_state(),
                    'country' => (WC()->version < '2.7.0') ? $wc_order->shipping_country : $wc_order->get_shipping_country(),
                    'postal_code' => (WC()->version < '2.7.0') ? $wc_order->shipping_postcode : $wc_order->get_shipping_postcode()
                ),
                'name' => ((WC()->version < '2.7.0') ? $wc_order->shipping_first_name : $wc_order->get_shipping_first_name()) . ' ' . ((WC()->version < '2.7.0') ? $wc_order->shipping_last_name : $wc_order->get_shipping_last_name()),
                'phone' => (WC()->version < '2.7.0') ? $wc_order->billing_phone : $wc_order->get_billing_phone()
            );
        }
        $charge[$order_type] = $token;
        
        return apply_filters('xa_alter_stripe_charge_params',$charge);
    }

    public function make_charge_params($charge_value, $order_id) {
        $wc_order = wc_get_order($order_id);
        $charge_data = json_decode(json_encode($charge_value));
        $origin_time = date('Y-m-d H:i:s', time() + get_option('gmt_offset') * 3600);
        $charge_parsed = array(
            "id" => $charge_data->id,
            "source_id" => $charge_data->source->id,
            "amount" => $this->reset_stripe_amount($charge_data->amount, $charge_data->currency),
            "amount_refunded" => $this->reset_stripe_amount($charge_data->amount_refunded, $charge_data->currency),
            "currency" => strtoupper($charge_data->currency),
            "order_amount" => (WC()->version < '2.7.0') ? $wc_order->order_total : $wc_order->get_total(),
            "order_currency" => (WC()->version < '2.7.0') ? $wc_order->order_currency : $wc_order->get_currency(),
            "captured" => $charge_data->captured ? "Captured" : "Uncaptured",
            "transaction_id" => $charge_data->balance_transaction,
            "mode" => (false == $charge_data->livemode) ? 'Test' : 'Live',
            "metadata" => $charge_data->metadata,
            "created" => date('Y-m-d H:i:s', $charge_data->created),
            "paid" => $charge_data->paid ? 'Paid' : 'Not Paid',
            "receiptemail" => (null == $charge_data->receipt_email) ? 'Receipt not send' : $charge_data->receipt_email,
            "receiptnumber" => (null == $charge_data->receipt_number) ? 'No Receipt Number' : $charge_data->receipt_number,
            "source_type" => ('card' == $charge_data->source->object ) ? ($charge_data->source->brand . "( " . $charge_data->source->funding . " )") : (( 'bitcoin' == $charge_data->source->type ) ? 'Bitcoin' : (( 'alipay' == $charge_data->source->type ) ? 'Alipay' : 'Undefined')),
            "status" => $charge_data->status,
            "origin_time" => $origin_time
        );
        $trans_time = date('Y-m-d H:i:s', time() + ((get_option('gmt_offset') * 3600) + 10));
        $tranaction_data = array(
            "id" => $charge_data->id,
            "total_amount" => $charge_parsed['amount'],
            "currency" => $charge_parsed['currency'],
            "balance_amount" => 0,
            "origin_time" => $trans_time
        );
        if (0 === count(get_post_meta($order_id, '_eh_stripe_payment_balance'))) {
            if ($charge_parsed['captured'] === 'Captured') {
                $tranaction_data['balance_amount'] = $charge_parsed['amount'];
            }
            add_post_meta($order_id, '_eh_stripe_payment_balance', $tranaction_data);
        } else {
            $tranaction_data['balance_amount'] = $charge_parsed['amount'];
            update_post_meta($order_id, '_eh_stripe_payment_balance', $tranaction_data);
        }
        return $charge_parsed;
    }

    public function make_refund_params($refund_value, $amount, $currency, $order_id) {
        $refund_data = json_decode(json_encode($refund_value));
        $origin_time = date('Y-m-d H:i:s', time() + get_option('gmt_offset') * 3600);
        $refund_parsed = array(
            "id" => $refund_data->id,
            "object" => $refund_data->object,
            "amount" => $this->reset_stripe_amount($refund_data->amount, $refund_data->currency),
            "transaction_id" => $refund_data->balance_transaction,
            "currency" => strtoupper($refund_data->currency),
            "order_amount" => $amount,
            "order_currency" => $currency,
            "metadata" => $refund_data->metadata,
            "created" => date('Y-m-d H:i:s', $refund_data->created + get_option('gmt_offset') * 3600),
            "charge_id" => $refund_data->charge,
            "receiptnumber" => (null == $refund_data->receipt_number) ? 'No Receipt Number' : $refund_data->receipt_number,
            "reason" => $refund_data->reason,
            "status" => $refund_data->status,
            "origin_time" => $origin_time
        );
        $trans_time = date('Y-m-d H:i:s', time() + ((get_option('gmt_offset') * 3600) + 10));
        $transaction_data = get_post_meta($order_id, '_eh_stripe_payment_balance', true);
        $balance = floatval($transaction_data['balance_amount']) - floatval($refund_parsed['amount']);
        $transaction_data['balance_amount'] = $balance;
        $transaction_data['origin_time'] = $trans_time;
        update_post_meta($order_id, '_eh_stripe_payment_balance', $transaction_data);
        return $refund_parsed;
    }

    public function create_stripe_customer($token, $order_id, $user_email = false) {
        if (!$user_email) {
            return false;
        }

        $response = \Stripe\Customer::create(array(
                    "description" => "Customer for Order #" . $order_id,
                    "email" => $user_email,
                    "source" => $token
        ));

        if (empty($response->id)) {
            return false;
        }

        return $response;
    }

    public function process_payment($order_id) {
        $wc_order = wc_get_order($order_id);
        try {
            if (isset($_COOKIE['eh_alipay_payment']) && isset($_REQUEST['source']) && isset($_REQUEST['client_secret']) && isset($_REQUEST['livemode'])) {
                $token = sanitize_text_field($_GET['source']);
                $card_brand = 'other';
                $currency = get_woocommerce_currency();
                $amount = $this->get_stripe_amount(((WC()->version < '2.7.0') ? $wc_order->order_total : $wc_order->get_total()));
            } else {
                $token = sanitize_text_field($_POST['eh_stripe_pay_token']);
                $card_brand = sanitize_text_field($_POST['eh_stripe_card_type']);
                $currency = sanitize_text_field($_POST['eh_stripe_pay_currency']);
                $amount = sanitize_text_field($_POST['eh_stripe_pay_amount']);
            }
            $client = $this->get_clients_details();
            $process_auth = true;
            if ($this->eh_stripe_enforce_cards) {
                if (!in_array($card_brand, $this->eh_stripe_checkout_cards)) {
                    $process_auth = false;
                }
            }
            if ($process_auth) {
                if (isset($_COOKIE['eh_alipay_payment']) && isset($_REQUEST['source']) && isset($_REQUEST['client_secret']) && isset($_REQUEST['livemode'])) {
                    $order_type = 'source';
                } else {
                    $order_type = 'card';
                }
                $charge_response = \Stripe\Charge::create($this->get_charge_details($wc_order, $token, $order_type, $client, $card_brand, $currency, $amount));
                $data = $this->make_charge_params($charge_response, $order_id);
                $order_time = date('Y-m-d H:i:s', time() + get_option('gmt_offset') * 3600);
                if ($charge_response->paid == true) {
                    $wc_order->payment_complete($data['id']);
                    if (!$charge_response->captured) {
                        $wc_order->update_status('on-hold');
                    }
                    $wc_order->add_order_note(__('Payment Status : ', 'eh-stripe-gateway') . ucfirst($data['status']) . ' [ ' . $order_time . ' ] . ' . __('Source : ', 'eh-stripe-gateway') . $data['source_type'] . '. ' . __('Charge Status :', 'eh-stripe-gateway') . $data['captured'] . (is_null($data['transaction_id']) ? '' : '. Transaction ID : ' . $data['transaction_id']), 'woocommerce');
                    WC()->cart->empty_cart();
                    add_post_meta($order_id, '_eh_stripe_payment_charge', $data);
                    EH_Stripe_Log::log_update('live', $data, get_bloginfo('blogname') . ' - Charge - Order #' . $wc_order->get_order_number());
                    return array(
                        'result' => 'success',
                        'redirect' => $this->get_return_url($wc_order),
                    );
                } else {
                    wc_add_notice($data['status'], $notice_type = 'error');
                    EH_Stripe_Log::log_update('dead', $charge_response, get_bloginfo('blogname') . ' - Charge - Order #' . $wc_order->get_order_number());
                }
            } else {
                $user = wp_get_current_user();
                $enforce_detail = array(
                    'name' => get_user_meta($user->ID, 'first_name', true),
                    'email' => $user->user_email,
                    'phone' => get_user_meta($user->ID, 'billing_phone', true),
                    'type' => "card_error",
                    'card' => $card_brand,
                    'token' => $token,
                    'message' => __("Admin declined the payment due to Card Restriction.", 'eh-stripe-gateway')
                );
                wc_add_notice(__('Seller declined ', 'eh-stripe-gateway') . $card_brand . " " . __('payment due to Card Restriction. Reload and try with some other Cards', 'eh-stripe-gateway'), $notice_type = 'error');
                EH_Stripe_Log::log_update('dead', $enforce_detail, get_bloginfo('blogname') . ' - Charge - Order #' . $wc_order->get_order_number());
                return array(
                    'result' => 'failure'
                );
            }
        } catch (Exception $error) {
            $user = wp_get_current_user();
            $user_detail = array(
                'name' => get_user_meta($user->ID, 'first_name', true),
                'email' => $user->user_email,
                'phone' => get_user_meta($user->ID, 'billing_phone', true),
            );

            if (method_exists($error, 'getJsonBody')) {
                $oops = $error->getJsonBody();
                $error_message = $oops['error']['message'];
            } else {
                $oops = array('message' => $error->getMessage());
                $error_message = $error->getMessage();
            }

            wc_add_notice(__('Payment Failed ', 'eh-stripe-gateway') . "( " . $error_message . " )." . __('Refresh and try again', 'eh-stripe-gateway'), $notice_type = 'error');
            EH_Stripe_Log::log_update('dead', array_merge($user_detail, $oops), get_bloginfo('blogname') . ' - Charge - Order #' . $wc_order->get_order_number());
            return array(
                'result' => 'failure'
            );
        }
    }

    private function process_error($order, $title, $message, $status = 'failed') {
        $order->add_order_note($message);
        $order->update_status($status);

        $error_arr = array('message' => $message);
        EH_Stripe_Log::log_update('dead', $error_arr, get_bloginfo('blogname') . " - $title - Order #" . $order->get_order_number());
    }

    public function process_refund($order_id, $amount = NULL, $reason = '') {
        $client = $this->get_clients_details();
        if ($amount > 0) {
            $data = get_post_meta($order_id, '_eh_stripe_payment_charge', true);
            $status = $data['captured'];
            if ('Captured' === $status) {
                $charge_id = $data['id'];
                $currency = $data['currency'];
                $total_amount = $data['amount'];
                $wc_order = new WC_Order($order_id);
                $div = $amount * ($total_amount / ((WC()->version < '2.7.0') ? $wc_order->order_total : $wc_order->get_total()));
                $refund_params = array(
                    'amount' => $this->get_stripe_amount($div, $currency),
                    'reason' => 'requested_by_customer',
                    'metadata' => array(
                        'order_id' => $wc_order->get_order_number(),
                        'Total Tax' => $wc_order->get_total_tax(),
                        'Total Shipping' => (WC()->version < '2.7.0') ? $wc_order->get_total_shipping() : $wc_order->get_shipping_total(),
                        'Customer IP' => $client['IP'],
                        'Agent' => $client['Agent'],
                        'Referer' => $client['Referer'],
                        'Reaon for Refund' => $reason
                    )
                );
                if ('Bitcoin' === $data['source_type'] || 'Alipay' === $data['source_type']) {
                    //$refund_params['refund_address'] = $data['source_id'];
                }
                try {
                    $charge_response = \Stripe\Charge::retrieve($charge_id);
                    $refund_response = $charge_response->refunds->create($refund_params);
                    if ($refund_response) {
                        $refund_time = date('Y-m-d H:i:s', time() + get_option('gmt_offset') * 3600);
                        $data = $this->make_refund_params($refund_response, $amount, ((WC()->version < '2.7.0') ? $wc_order->order_currency : $wc_order->get_currency()), $order_id);
                        add_post_meta($order_id, '_eh_stripe_payment_refund', $data);
                        $wc_order->add_order_note(__('Reason : ', 'eh-stripe-gateway') . $reason . '.<br>' . __('Amount : ', 'eh-stripe-gateway') . get_woocommerce_currency_symbol() . $amount . '.<br>' . __('Status : ', 'eh-stripe-gateway') . (($data['status'] === 'succeeded') ? 'Success' : 'Failed') . ' [ ' . $refund_time . ' ] ' . (is_null($data['transaction_id']) ? '' : '<br>' . __('Transaction ID : ', 'eh-stripe-gateway') . $data['transaction_id']));
                        EH_Stripe_Log::log_update('live', $data, get_bloginfo('blogname') . ' - Refund - Order #' . $wc_order->get_order_number());
                        return true;
                    } else {
                        EH_Stripe_Log::log_update('dead', $refund_response, get_bloginfo('blogname') . ' - Refund Error - Order #' . $wc_order->get_order_number());
                        $wc_order->add_order_note(__('Reason : ', 'eh-stripe-gateway') . $reason . '.<br>' . __('Amount : ', 'eh-stripe-gateway') . get_woocommerce_currency_symbol() . $amount . '.<br>' . __(' Status : Failed ', 'eh-stripe-gateway'));
                        return new WP_Error('error', $refund_response->message);
                    }
                } catch (Exception $error) {
                    $oops = $error->getJsonBody();
                    EH_Stripe_Log::log_update('dead', $oops['error'], get_bloginfo('blogname') . ' - Refund Error - Order #' . $wc_order->get_order_number());
                    $wc_order->add_order_note(__('Reason : ', 'eh-stripe-gateway') . $reason . '.<br>' . __('Amount : ', 'eh-stripe-gateway') . get_woocommerce_currency_symbol() . $amount . '.<br>' . __('Status : ', 'eh-stripe-gateway') . $oops['error']['message']);
                    return new WP_Error('error', $oops['error']['message']);
                }
            } else {
                return new WP_Error('error', __('Uncaptured Amount cannot be refunded', 'eh-stripe-gateway'));
            }
        } else {
            return false;
        }
    }

    public function file_size($bytes) {
        $result = 0;
        $bytes = floatval($bytes);
        $arBytes = array(
            0 => array(
                "UNIT" => "TB",
                "VALUE" => pow(1024, 4)
            ),
            1 => array(
                "UNIT" => "GB",
                "VALUE" => pow(1024, 3)
            ),
            2 => array(
                "UNIT" => "MB",
                "VALUE" => pow(1024, 2)
            ),
            3 => array(
                "UNIT" => "KB",
                "VALUE" => 1024
            ),
            4 => array(
                "UNIT" => "B",
                "VALUE" => 1
            ),
        );

        foreach ($arBytes as $arItem) {
            if ($bytes >= $arItem["VALUE"]) {
                $result = $bytes / $arItem["VALUE"];
                $result = str_replace(".", ".", strval(round($result, 2))) . " " . $arItem["UNIT"];
                break;
            }
        }
        return $result;
    }

    public static function eh_refund_payment() {
        $amount = sanitize_text_field($_POST['refund_amount']);
        $mode = sanitize_text_field($_POST['refund_mode']);
        $order_id = sanitize_text_field($_POST['order_id']);
        $obj = new EH_Stripe_Payment();
        $client = $obj->get_clients_details();
        $data = get_post_meta($order_id, '_eh_stripe_payment_charge', true);
        $status = $data['captured'];
        $reason = "Manual Refund Status:";
        if ('Captured' === $status) {
            $charge_id = $data['id'];
            $currency = $data['currency'];
            $total_amount = $data['amount'];
            $wc_order = new WC_Order($order_id);
            if ($mode === 'full') {
                $refund_amount = $wc_order->get_remaining_refund_amount();
                $div = $wc_order->get_remaining_refund_amount() * ($total_amount / ((WC()->version < '2.7.0') ? $wc_order->order_total : $wc_order->get_total()));
            } else {
                $refund_amount = $amount;
                $div = $amount * ($total_amount / ((WC()->version < '2.7.0') ? $wc_order->order_total : $wc_order->get_total()));
            }
            $refund_params = array(
                'amount' => $obj->get_stripe_amount($div, $currency),
                'reason' => 'requested_by_customer',
                'metadata' => array(
                    'order_id' => $wc_order->get_order_number(),
                    'Total Tax' => $wc_order->get_total_tax(),
                    'Total Shipping' => (WC()->version < '2.7.0') ? $wc_order->get_total_shipping() : $wc_order->get_shipping_total(),
                    'Customer IP' => $client['IP'],
                    'Agent' => $client['Agent'],
                    'Referer' => $client['Referer'],
                    'Reaon for Refund' => 'Refund through Stripe Overview Page'
                )
            );
            if ('Bitcoin' === $data['source_type'] || 'Alipay' === $data['source_type']) {
                //$refund_params['refund_address'] = $data['source_id'];
            }
            try {
                $charge_response = \Stripe\Charge::retrieve($charge_id);
                $refund_response = $charge_response->refunds->create($refund_params);
                if ($refund_response) {
                    $refund = wc_create_refund(array(
                        'amount' => $refund_amount,
                        'reason' => 'Refunded using Stripe',
                        'order_id' => $order_id,
                        'line_items' => array(),
                    ));
                    do_action('woocommerce_refund_processed', $refund, true);
                    $refund_id = (WC()->version < '2.7.0') ? $refund->id : $refund->get_id();
                    if ($wc_order->get_remaining_refund_amount() > 0 || ( $wc_order->has_free_item() && $wc_order->get_remaining_refund_items() > 0 )) {
                        /**
                         * woocommerce_order_partially_refunded.
                         *
                         * @since 2.4.0
                         * Note: 3rd arg was added in err. Kept for bw compat. 2.4.3.
                         */
                        do_action('woocommerce_order_partially_refunded', $order_id, $refund_id, $refund_id);
                    } else {
                        do_action('woocommerce_order_fully_refunded', $order_id, $refund_id);

                        $wc_order->update_status(apply_filters('woocommerce_order_fully_refunded_status', 'refunded', $order_id, $refund_id));
                        $response_data['status'] = 'fully_refunded';
                    }

                    do_action('woocommerce_order_refunded', $order_id, $refund_id);

                    // Clear transients
                    wc_delete_shop_order_transients($order_id);
                    $refund_time = date('Y-m-d H:i:s', time() + get_option('gmt_offset') * 3600);
                    $data = $obj->make_refund_params($refund_response, $refund_amount, ((WC()->version < '2.7.0') ? $wc_order->order_currency : $wc_order->get_currency()), $order_id);
                    add_post_meta($order_id, '_eh_stripe_payment_refund', $data);
                    $wc_order->add_order_note(__('Reason : ', 'eh-stripe-gateway') . $reason . '.<br>' . __('Amount : ', 'eh-stripe-gateway') . get_woocommerce_currency_symbol() . $amount . '.<br>' . __('Status : ', 'eh-stripe-gateway') . (($data['status'] === 'succeeded') ? 'Success' : 'Failed') . ' [ ' . $refund_time . ' ] ' . (is_null($data['transaction_id']) ? '' : '<br>' . __('Transaction ID : ', 'eh-stripe-gateway') . $data['transaction_id']));
                    EH_Stripe_Log::log_update('live', $data, get_bloginfo('blogname') . ' - Refund - Order #' . $wc_order->get_order_number());
                    $message = $refund_amount . ' refund ' . $data['status'] . ' at ' . $refund_time . (is_null($data['transaction_id']) ? '' : '. Transaction Id - ' . $data['transaction_id']);
                    wp_send_json($message);
                } else {
                    EH_Stripe_Log::log_update('dead', $refund_response, get_bloginfo('blogname') . ' - Refund Error - Order #' . $wc_order->get_order_number());
                    $wc_order->add_order_note(__('Reason : ', 'eh-stripe-gateway') . $reason . '.<br>' . __('Amount : ', 'eh-stripe-gateway') . get_woocommerce_currency_symbol() . $amount . '.<br>' . __(' Status : Failed ', 'eh-stripe-gateway'));
                    die($refund_response->message);
                }
            } catch (Exception $error) {
                $oops = $error->getJsonBody();
                EH_Stripe_Log::log_update('dead', $oops['error'], get_bloginfo('blogname') . ' - Refund Error - Order #' . $wc_order->get_order_number());
                $wc_order->add_order_note(__('Reason : ', 'eh-stripe-gateway') . $reason . '.<br>' . __('Amount : ', 'eh-stripe-gateway') . get_woocommerce_currency_symbol() . $amount . '.<br>' . __('Status : ', 'eh-stripe-gateway') . $oops['error']['message']);
                die($oops['error']['message']);
            }
        } else {
            die('Uncaptured Amount cannot be refunded');
        }
    }

    public static function eh_capture_payment() {
        $order_id = sanitize_text_field($_POST['order_id']);
        $order_data = get_post_meta($order_id, '_eh_stripe_payment_charge', true);
        $charge_id = $order_data['id'];
        if (class_exists('EH_Stripe_Payment')) {
            $eh_stripe_this = new EH_Stripe_Payment();
            if ('test' == $eh_stripe_this->eh_stripe_mode) {
                \Stripe\Stripe::setApiKey($eh_stripe_this->eh_stripe_test_secret_key);
            } else {
                \Stripe\Stripe::setApiKey($eh_stripe_this->eh_stripe_live_secret_key);
            }
        }

        try {
            $eh_stripe_this = new EH_Stripe_Payment();
            $wc_order = new WC_Order($order_id);
            $charge_response = \Stripe\Charge::retrieve($charge_id);
            $capture_response = $charge_response->capture();
            $data = $eh_stripe_this->make_charge_params($capture_response, $order_id);
            if ('Captured' == $data['captured'] && 'Paid' == $data['paid']) {

                $capture_time = date('Y-m-d H:i:s', time() + get_option('gmt_offset') * 3600);
                $wc_order->update_status('processing');
                update_post_meta($order_id, '_eh_stripe_payment_charge', $data);
                EH_Stripe_Log::log_update('live', $data, get_bloginfo('blogname') . ' - Capture - Order #' . $wc_order->get_order_number());
                $wc_order->add_order_note(__('Capture Status : ', 'eh-stripe-gateway') . ucfirst($data['status']) . ' [ ' . $capture_time . ' ] . ' . __('Source : ', 'eh-stripe-gateway') . $data['source_type'] . '. ' . __('Charge Status : ', 'eh-stripe-gateway') . $data['captured'] . (is_null($data['transaction_id']) ? '' : '. ' . __('Transaction ID : ', 'eh-stripe-gateway') . $data['transaction_id']));
                die('Capture ' . $data['status'] . ' at ' . $capture_time . ', via ' . $data['source_type']);
            }
        } catch (Exception $error) {
            $user = wp_get_current_user();
            $user_detail = array(
                'name' => get_user_meta($user->ID, 'first_name', true),
                'email' => $user->user_email,
                'phone' => get_user_meta($user->ID, 'billing_phone', true),
            );
            $oops = $error->getJsonBody();
            $wc_order->add_order_note($capture_response->status . ' ' . $error->getMessage());
            EH_Stripe_Log::log_update('dead', array_merge($user_detail, $oops), get_bloginfo('blogname') . ' - Charge - Order #' . $wc_order->get_order_number());
            die($error->getMessage());
        }
    }

    public static function eh_stripe_refund_payment() {
        $order_id = sanitize_text_field($_POST['order_id']);
        $obj = new EH_Stripe_Payment();
        $client = $obj->get_clients_details();
        $reason = "Manual Refund Status:";
        $data = get_post_meta($order_id, '_eh_stripe_payment_charge', true);
        $status = $data['captured'];
        $charge_id = $data['id'];
        if ('Captured' === $status) {
            $balance_data = get_post_meta($order_id, '_eh_stripe_payment_balance', true);
            $amount = $balance_data['balance_amount'];
            $currency = $balance_data['currency'];
            $wc_order = new WC_Order($order_id);
            $remaining_amount = $wc_order->get_remaining_refund_amount();
            $refund_params = array(
                'amount' => $obj->get_stripe_amount($amount, $currency),
                'reason' => 'requested_by_customer',
                'metadata' => array(
                    'order_id' => $wc_order->get_order_number(),
                    'Total Tax' => $wc_order->get_total_tax(),
                    'Total Shipping' => (WC()->version < '2.7.0') ? $wc_order->get_total_shipping() : $wc_order->get_shipping_total(),
                    'Customer IP' => $client['IP'],
                    'Agent' => $client['Agent'],
                    'Referer' => $client['Referer'],
                    'Reaon for Refund' => 'Stripe Overview refund'
                )
            );
            if ('Bitcoin' === $data['source_type'] || 'Alipay' === $data['source_type']) {
                //$refund_params['refund_address'] = $data['source_id'];
            }
            try {
                $charge_response = \Stripe\Charge::retrieve($charge_id);
                $refund_response = $charge_response->refunds->create($refund_params);
                if ($refund_response) {
                    $refund = wc_create_refund(array(
                        'amount' => $remaining_amount,
                        'reason' => 'Refunded using Stripe',
                        'order_id' => $order_id,
                        'line_items' => array(),
                    ));
                    do_action('woocommerce_refund_processed', $refund, true);
                    $refund_id = (WC()->version < '2.7.0') ? $refund->id : $refund->get_id();
                    if ($wc_order->get_remaining_refund_amount() > 0 || ( $wc_order->has_free_item() && $wc_order->get_remaining_refund_items() > 0 )) {
                        /**
                         * woocommerce_order_partially_refunded.
                         *
                         * @since 2.4.0
                         * Note: 3rd arg was added in err. Kept for bw compat. 2.4.3.
                         */
                        do_action('woocommerce_order_partially_refunded', $order_id, $refund_id, $refund_id);
                    } else {
                        do_action('woocommerce_order_fully_refunded', $order_id, $refund_id);

                        $wc_order->update_status(apply_filters('woocommerce_order_fully_refunded_status', 'refunded', $order_id, $refund_id));
                        $response_data['status'] = 'fully_refunded';
                    }

                    do_action('woocommerce_order_refunded', $order_id, $refund_id);

                    // Clear transients
                    wc_delete_shop_order_transients($order_id);
                    $refund_time = date('Y-m-d H:i:s', time() + get_option('gmt_offset') * 3600);
                    $data = $obj->make_refund_params($refund_response, $remaining_amount, ((WC()->version < '2.7.0') ? $wc_order->order_currency : $wc_order->get_currency()), $order_id);
                    add_post_meta($order_id, '_eh_stripe_payment_refund', $data);
                    $wc_order->add_order_note(__('Reason : ', 'eh-stripe-gateway') . $reason . '.<br>' . __('Amount : ', 'eh-stripe-gateway') . get_woocommerce_currency_symbol() . $amount . '.<br>' . __('Status : ', 'eh-stripe-gateway') . (($data['status'] === 'succeeded') ? 'Success' : 'Failed') . ' [ ' . $refund_time . ' ] ' . (is_null($data['transaction_id']) ? '' : '<br>' . __('Transaction ID : ', 'eh-stripe-gateway') . $data['transaction_id']));
                    EH_Stripe_Log::log_update('live', $data, get_bloginfo('blogname') . ' - Refund - Order #' . $wc_order->get_order_number());
                    $message = $remaining_amount . ' refund ' . $data['status'] . ' at ' . $refund_time . (is_null($data['transaction_id']) ? '' : '. Transaction Id - ' . $data['transaction_id']);
                    wp_send_json($message);
                } else {
                    EH_Stripe_Log::log_update('dead', $refund_response, get_bloginfo('blogname') . ' - Refund Error - Order #' . $wc_order->get_order_number());
                    $wc_order->add_order_note(__('Reason : ', 'eh-stripe-gateway') . $reason . '.<br>' . __('Amount : ', 'eh-stripe-gateway') . get_woocommerce_currency_symbol() . $amount . '.<br>' . __(' Status : Failed ', 'eh-stripe-gateway'));
                    die($refund_response->message);
                }
            } catch (Exception $error) {
                $oops = $error->getJsonBody();
                EH_Stripe_Log::log_update('dead', $oops['error'], get_bloginfo('blogname') . ' - Refund Error - Order #' . $wc_order->get_order_number());
                $wc_order->add_order_note(__('Reason : ', 'eh-stripe-gateway') . $reason . '.<br>' . __('Amount : ', 'eh-stripe-gateway') . get_woocommerce_currency_symbol() . $amount . '.<br>' . __('Status : ', 'eh-stripe-gateway') . $oops['error']['message']);
                die($oops['error']['message']);
            }
        } else {
            die('Uncaptured Amount cannot be refunded');
        }
    }

}

add_action('wp_ajax_eh_spg_stripe_refund_payment', array('EH_Stripe_Payment', 'eh_stripe_refund_payment'));
add_action('wp_ajax_eh_spg_capture_payment', array('EH_Stripe_Payment', 'eh_capture_payment'));
add_action('wp_ajax_eh_spg_refund_payment', array('EH_Stripe_Payment', 'eh_refund_payment'));
add_action('wp_ajax_eh_spg_currency_convert', array('EH_Stripe_Payment', 'eh_spg_currency_convert_callback'));
add_action('wp_ajax_nopriv_eh_spg_currency_convert', array('EH_Stripe_Payment', 'eh_spg_currency_convert_callback'));
add_action('woocommerce_order_actions', 'add_order_meta_box_actions', 2, 1);

function add_order_meta_box_actions($actions) {
    global $post;
    $data = get_post_meta($post->ID, '_eh_stripe_payment_charge', true);
    $charge_capture = isset($data['captured']) ? $data['captured'] : '';
    if ($charge_capture == 'Uncaptured') {
        $actions['eh_stripe_capture'] = __('Capture Stripe Payment', 'eh-stripe-gateway');
        return $actions;
    }
    return $actions;
}

add_action('woocommerce_order_action_eh_stripe_capture', 'process_order_meta_box_actions');

function process_order_meta_box_actions() {
    global $post;
    $post_data = get_post_meta($post->ID, '_eh_stripe_payment_charge', true);
    $charge_id = $post_data['id'];
    if (class_exists('EH_Stripe_Payment')) {
        $eh_stripe_this = new EH_Stripe_Payment();
        if ('test' == $eh_stripe_this->eh_stripe_mode) {
            \Stripe\Stripe::setApiKey($eh_stripe_this->eh_stripe_test_secret_key);
        } else {
            \Stripe\Stripe::setApiKey($eh_stripe_this->eh_stripe_live_secret_key);
        }
    }

    try {
        $eh_stripe_this = new EH_Stripe_Payment();
        $wc_order = new WC_Order($post->ID);
        $charge_response = \Stripe\Charge::retrieve($charge_id);
        $capture_response = $charge_response->capture();
        $data = $eh_stripe_this->make_charge_params($capture_response, $post->ID);
        if ('Captured' == $data['captured'] && 'Paid' == $data['paid']) {

            $capture_time = date('Y-m-d H:i:s', time() + get_option('gmt_offset') * 3600);
            $wc_order->update_status('processing');
            update_post_meta($post_data['metadata']->order_id, '_eh_stripe_payment_charge', $data);
            EH_Stripe_Log::log_update('live', $data, get_bloginfo('blogname') . ' - Capture - Order #' . $wc_order->get_order_number());
            $wc_order->add_order_note(__('Capture Status : ', 'eh-stripe-gateway') . ucfirst($data['status']) . ' [ ' . $capture_time . ' ] . ' . __('Source : ', 'eh-stripe-gateway') . $data['source_type'] . '. ' . __('Charge Status : ', 'eh-stripe-gateway') . $data['captured'] . (is_null($data['transaction_id']) ? '' : '. ' . __('Transaction ID : ', 'eh-stripe-gateway') . $data['transaction_id']));
        }
    } catch (Exception $error) {
        $user = wp_get_current_user();
        $user_detail = array(
            'name' => get_user_meta($user->ID, 'first_name', true),
            'email' => $user->user_email,
            'phone' => get_user_meta($user->ID, 'billing_phone', true),
        );
        $oops = $error->getJsonBody();
        $wc_order->add_order_note($capture_response->status . ' ' . $error->getMessage());
        EH_Stripe_Log::log_update('dead', array_merge($user_detail, $oops), get_bloginfo('blogname') . ' - Charge - Order #' . $wc_order->get_order_number());
    }
}
