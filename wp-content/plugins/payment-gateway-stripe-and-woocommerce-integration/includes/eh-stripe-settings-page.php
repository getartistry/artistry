<?php
if (!defined('ABSPATH')) {
    exit;
}
return array(
    'enabled' => array(
        'title' => __('Stripe Payment', 'eh-stripe-gateway'),
        'label' => __('Enable', 'eh-stripe-gateway'),
        'type' => 'checkbox',
        'default' => 'no'
    ),
    'overview' => array(
        'title' => __('Stripe Overview Page', 'eh-stripe-gateway'),
        'label' => __('Enable', 'eh-stripe-gateway'),
        'type' => 'checkbox',
        'description' => sprintf('<a href="' . admin_url('admin.php?page=eh-stripe-overview') . '">'.__( 'Stripe Overview ','eh-stripe-gateway' ).'</a>'),
        'default' => 'yes'
    ),
    'title' => array(
        'title' => __('Title', 'eh-stripe-gateway'),
        'type' => 'text',
        'description' => __('Enter the title of the checkout which the user can see.', 'eh-stripe-gateway'),
        'default' => __('Stripe', 'eh-stripe-gateway'),
        'desc_tip' => true
    ),
    'description' => array(
        'title' => __('Description', 'eh-stripe-gateway'),
        'type' => 'textarea',
        'css' => 'width:25em',
        'description' => __('Description which the user sees during checkout.', 'eh-stripe-gateway'),
        'default' => __('Secure payment via Stripe.', 'eh-stripe-gateway'),
        'desc_tip' => true
    ),
    'eh_stripe_order_button' => array(
        'title' => __('Order Button Text', 'eh-stripe-gateway'),
        'type' => 'text',
        'description' => __('Enter the Order Button Text of the payment page.', 'eh-stripe-gateway'),
        'default' => __('Pay via Stripe', 'eh-stripe-gateway'),
        'desc_tip' => true
    ),
    'eh_stripe_checkout_cards' => array(
        'title' => __('Preferred Cards', 'eh-stripe-gateway'),
        'type' => 'multiselect',
        'class' => 'chosen_select',
        'css' => 'width: 350px;',
        'desc_tip' => __('Select the card types to display the card logo in the checkout page as preferred card.', 'woocommerce'),
        'options' => array(
            'MasterCard' => 'MasterCard',
            'Visa' => 'Visa',
            'American Express' => 'American Express',
            'Discover' => 'Discover',
            'JCB' => 'JCB',
            'Diners Club' => 'Diners Club'
        ),
        'default' => array(
            'MasterCard',
            'Visa',
            'Diners Club',
            'Discover',
            'American Express',
            'JCB'
        )
    ),
    'eh_stripe_checkout_locale' => array(
        'title' => __('Preferred Locale', 'eh-stripe-gateway'),
        'type' => 'select',
        'class' => 'wc-enhanced-select',
        'desc_tip' => __('Select the Stripe Checkout language.', 'eh-stripe-gateway'),
        'options' => array(
            'auto' => 'Auto Locale',
            'zh' => 'Simplified Chinese',
            'da' => 'Danish',
            'nl' => 'Dutch',
            'en' => 'English',
            'fi' => 'Finnish',
            'fr' => 'French',
            'de' => 'German',
            'it' => 'Italian',
            'ja' => 'Japanese',
            'no' => 'Norwegian',
            'es' => 'Spanish',
            'sv' => 'Swedish'
        ),
        'default' => 'auto'
    ),
    'eh_stripe_credit_title' => array(
        'title' => sprintf('<span style="text-decoration: underline;color:brown;">'.__( 'Stripe Credentials','eh-stripe-gateway' ).'<span>'),
        'type' => 'title'
    ),
    'eh_stripe_mode' => array(
        'title' => __('Transaction Mode', 'eh-stripe-gateway'),
        'type' => 'select',
        'options' => array(
            'test' => __('Test Mode', 'eh-stripe-gateway'),
            'live' => __('Live Mode', 'eh-stripe-gateway')
        ),
        'class' => 'wc-enhanced-select',
        'description' => sprintf(__( 'Check appropriate Stripe mode is checked in Stripe ','eh-stripe-gateway' ).'<a href="https://dashboard.stripe.com/dashboard" target="_blank">'.__( 'Dashboard','eh-stripe-gateway' ).'</a>'),
        'default' => 'test'
    ),
    'eh_stripe_test_secret_key' => array(
        'title' => __('Test Secret Key', 'eh-stripe-gateway'),
        'type' => 'text',
        'description' => __('Enter Stripe Test mode Secret Key.', 'eh-stripe-gateway'),
        'placeholder' => 'Test Secret Key',
        'desc_tip' => true
    ),
    'eh_stripe_test_publishable_key' => array(
        'title' => __('Test Publishable Key', 'eh-stripe-gateway'),
        'type' => 'text',
        'description' => __('Enter Stripe Test mode Publishable Key.', 'eh-stripe-gateway'),
        'placeholder' => 'Test Publishable Key',
        'desc_tip' => true
    ),
    'eh_stripe_live_secret_key' => array(
        'title' => __('Live Secret Key', 'eh-stripe-gateway'),
        'type' => 'text',
        'description' => __('Enter Stripe Live mode Publishable Key.', 'eh-stripe-gateway'),
        'placeholder' => 'Live Secret Key',
        'desc_tip' => true
    ),
    'eh_stripe_live_publishable_key' => array(
        'title' => __('Live Publishable Key', 'eh-stripe-gateway'),
        'type' => 'text',
        'description' => __('Enter Stripe Live mode Publishable Key.', 'eh-stripe-gateway'),
        'placeholder' => 'Live Publishable Key',
        'desc_tip' => true
    ),
    'eh_stripe_pay_actions_title' => array(
        'title' => sprintf('<span style="text-decoration: underline;color:brown;">'.__( 'Stripe Actions','eh-stripe-gateway' ).'<span>'),
        'type' => 'title'
    ),
    'eh_stripe_capture' => array(
        'title' => __('Capture Payment', 'eh-stripe-gateway'),
        'label' => __('Capture Payment Immediately', 'eh-stripe-gateway'),
        'type' => 'checkbox',
        'description' => __('Whether or not to immediately capture the charge.Uncaptured charges will expire in 7 days.', 'eh-stripe-gateway'),
        'default' => 'yes',
        'desc_tip' => true
    ),
    'eh_stripe_currency_converter' => array(
        'title' => __('Currency Conversion', 'eh-stripe-gateway'),
        'label' => __('Enable', 'eh-stripe-gateway'),
        'type' => 'checkbox',
        'description' => sprintf(__( 'Currency Conversion is made by Google Finance. Please read ','eh-stripe-gateway' ).'<a href="https://www.google.co.in/intl/en/googlefinance/disclaimer/" >'.__( 'Disclaimer','eh-stripe-gateway' ).'</a>. <br>'.__( 'Google Conversion differs from Stripe so you may get difference in price.','eh-stripe-gateway' )),
        'default' => 'no'
    ),
    'eh_stripe_currency_converter_description' => array(
        'title' => __('Conversion description', 'eh-stripe-gateway'),
        'type' => 'textarea',
        'css' => 'width:25em',
        'description' => __('Please give proper currency conversion alert message to users. Eg:-Like Conversion charges apply!', 'eh-stripe-gateway'),
        'default' => __('Conversion of currency while payment may apply conversion charges.', 'eh-stripe-gateway'),
        'desc_tip' => true
    ),
    'eh_stripe_enforce_cards' => array(
        'title' => __('Restrict Cards', 'eh-stripe-gateway'),
        'label' => __('Restrict Cards except Preferred Cards', 'eh-stripe-gateway'),
        'type' => 'checkbox',
        'description' => __('If enabling Restrict Cards, the card which all are mentioned in preferred cards only can allow for transaction.', 'eh-stripe-gateway'),
        'default' => 'no',
        'desc_tip' => true
    ),
    'eh_stripe_email_receipt' => array(
        'title' => __('Email Transaction Receipt', 'eh-stripe-gateway'),
        'label' => __('Enable ', 'eh-stripe-gateway'),
        'type' => 'checkbox',
        'description' => __('If enabling Email Transaction Receipt, the transaction receipt will send as email to the customers by Stripe.', 'eh-stripe-gateway'),
        'default' => 'no',
        'desc_tip' => true
    ),
    'eh_stripe_alipay_form_title' => array(
        'title' => sprintf('<span style="text-decoration: underline;color:brown;">'.__( 'Alipay','eh-stripe-gateway' ).'<span>'),
        'type' => 'title'
    ),
    'eh_stripe_alipay' => array(
        'title' => __('Alipay', 'eh-stripe-gateway'),
        'label' => __('Enable', 'eh-stripe-gateway'),
        'type' => 'checkbox',
        'description' => __('If enabled, an option to accept Alipay will show on the checkout modal. Note: Store currency must be set to USD.', 'eh-stripe-gateway'),
        'default' => 'no',
        'desc_tip' => true
    ),
    'eh_stripe_alipay_title' => array(
        'title' => __('Title', 'eh-stripe-gateway'),
        'type' => 'text',
        'description' => __('Enter the title of the checkout which the user can see.', 'eh-stripe-gateway'),
        'default' => __('Alipay', 'eh-stripe-gateway'),
        'desc_tip' => true
    ),
    'eh_stripe_alipay_description' => array(
        'title' => __('Description', 'eh-stripe-gateway'),
        'type' => 'textarea',
        'css' => 'width:25em',
        'description' => __('Description which the user sees during checkout.', 'eh-stripe-gateway'),
        'default' => __('Secure payment via Alipay.', 'eh-stripe-gateway'),
        'desc_tip' => true
    ),
    'eh_stripe_alipay_order_button' => array(
        'title' => __('Order Button Text', 'eh-stripe-gateway'),
        'type' => 'text',
        'description' => __('Enter the Order Button Text of the payment page.', 'eh-stripe-gateway'),
        'default' => __('Pay via Alipay', 'eh-stripe-gateway'),
        'desc_tip' => true
    ),
    'eh_stripe_stripe_form_title' => array(
        'title' => sprintf('<span style="text-decoration: underline;color:brown;">'.__( 'Stripe Abilities','eh-stripe-gateway' ).'<span>'),
        'type' => 'title'
    ),
    'eh_stripe_bitcoin' => array(
        'title' => __('Bitcoin Currency', 'eh-stripe-gateway'),
        'label' => __('Enable', 'eh-stripe-gateway'),
        'type' => 'checkbox',
        'description' => __('If enabled, an option to accept bitcoin will show on the checkout modal. Note: Store currency must be set to USD.', 'eh-stripe-gateway'),
        'default' => 'no',
        'desc_tip' => true
    ),
    'eh_stripe_form_description' => array(
        'title' => __('Checkout Form description', 'eh-stripe-gateway'),
        'type' => 'textarea',
        'css' => 'width:25em',
        'description' => __('Please give a description which will be displayed in the checkout form.', 'eh-stripe-gateway'),
        'default' => __('Verify Your Email Address', 'eh-stripe-gateway'),
        'desc_tip' => true
    ),
    'eh_stripe_billing_address_check' => array(
        'title' => __('Ask Billing Address in Stripe', 'eh-stripe-gateway'),
        'label' => __('Enable', 'eh-stripe-gateway'),
        'type' => 'checkbox',
        'description' => __('If enabled, the billing address will be asked to fill in Stripe Payment form.', 'eh-stripe-gateway'),
        'default' => 'no',
        'desc_tip' => true
    ),
    'eh_stripe_checkout_image_check' => array(
        'title' => __('Display Checkout Logo', 'eh-stripe-gateway'),
        'label' => __('Enable', 'eh-stripe-gateway'),
        'type' => 'checkbox',
        'description' => __('If enabled, the logo image provided in the Stripe Checkout Logo will be displayed in the Stripe Checkout', 'eh-stripe-gateway'),
        'default' => 'yes',
        'desc_tip' => true
    ),
    'eh_stripe_checkout_image' => array(
        'title' => __('Stripe Checkout Logo', 'eh-stripe-gateway'),
        'description' => sprintf('<img src="%s" width="128px" height="128px" style="cursor:pointer" title="'.__( 'Click the image to Choose a Stripe Checkout Logo','eh-stripe-gateway' ).'" id="eh_stripe_preview">', ('' == $this->get_option('eh_stripe_checkout_image')) ? EH_STRIPE_MAIN_URL_PATH . "assets/img/stripe.png" : $this->get_option('eh_stripe_checkout_image')),
        'type' => 'text',
        'placeholder' => 'Click the Image to set Logo (Default : Stripe Logo)'
    ),
    'eh_stripe_enable_save_cards' => array(
        'title' => __('Stripe Save Cards', 'eh-stripe-gateway'),
        'label' => __('Enable', 'eh-stripe-gateway'),
        'type' => 'checkbox',
        'description' => __('If enabled, users will be able to check Remember Me option in Stripe checkout for future transactions. Card details are saved on Stripe servers, not on your store.', 'eh-stripe-gateway'),
        'default' => 'no',
        'desc_tip' => true
    ),
    'eh_stripe_log_title' => array(
        'title' => sprintf('<span style="text-decoration: underline;color:brown;">'.__( 'Debugging','eh-stripe-gateway' ).'<span>'),
        'type' => 'title',
        'description' => __('Enable Logging to save Stripe payment logs into log file.', 'eh-stripe-gateway')
    ),
    'eh_stripe_logging' => array(
        'title' => __('Logging', 'eh-stripe-gateway'),
        'label' => __('Enable', 'eh-stripe-gateway'),
        'type' => 'checkbox',
        'description' => sprintf('<span style="color:green">'.__( 'Success Log File','eh-stripe-gateway' ).'</span>: ' . strstr(wc_get_log_file_path('eh_stripe_pay_live'), 'wp-content') . ' ( ' . $this->file_size(filesize(wc_get_log_file_path('eh_stripe_pay_live'))) . ' ) <br><span style="color:red">'.__( 'Failure Log File','eh-stripe-gateway' ).'</span >: ' . strstr(wc_get_log_file_path('eh_stripe_pay_dead'), 'wp-content') . ' ( ' . $this->file_size(filesize(wc_get_log_file_path('eh_stripe_pay_dead'))) . ' ) '),
        'default' => 'yes'
    )
);
