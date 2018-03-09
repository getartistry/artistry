<?php

/*
  Plugin Name: Actionable Google Analytics
  Plugin URI: http://www.tatvic.com/actionable-google-analytics-woocommerce/
  Description: Actionable Google Analytics is a Plugin for Woocommerce stores which allows you to use some of the most important features of Universal Analytics including Enhanced Ecommerce & User ID Tracking. Additionally, the plugin supports I.P Anonymization, Product Refund, Content Grouping, Form Field Tracking & 15+ Custom Dimensions & Metrics.
  Version: CC-V3-2.1

 @class       WC_Actionable_Google_Analytics
 @extends     WC_Integration
 @author     Jigar Navadiya <jigar@tatvic.com>
*/
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class WC_Actionable_Google_Analytics extends WC_Integration {

    /**
     * Init and hook in the integration.
     *
     * @access public
     * @return void
     */
    //set plugin version
    public $tvc_eeVer = 'CC-V3-2.1';    

    public function __construct() {

        //Set Global Variables
        global $homepage_json_fp, $homepage_json_ATC_link, $homepage_json_rp, $prodpage_json_relProd, $catpage_json,
        $prodpage_json_ATC_link, $catpage_json_ATC_link;
        //define plugin ID       
        $this->id = "actionable_google_analytics";
        $this->method_title = __("Actionable Google Analytics", "woocommerce");
        $this->method_description = __("<b>Important Note:</b> <i>To use User ID Tracking, Content Grouping, Set up Custom Dimensions/ Metrics & Product Refund, you need to configure your Google Analytics. Take this <a href='http://plugins.tatvic.com/enhanced-ecommerce-installation-wizard/?store_type=woocommerce' target='_blank'>quick tour</a> to learn the configuration changes. If you won't configure some features of our plugin will not work.</i>", "woocommerce");

        //start session for product position count
        //session_start removed bcoz it gives warning
        $_SESSION['t_npcnt'] = 0;
        $_SESSION['t_fpcnt'] = 0;
        // Load the integration form
        $this->init_form_fields();
        //load all the settings
        $this->init_settings();

        // Define user set variables -- Always use short names    
        $this->ga_email = $this->get_option("ga_email"); // Email ID
        $this->ga_id = $this->get_option("ga_id"); // UA ID
        $this->ga_Dname = 'auto';
        $this->get_option("ga_Dname"); //Domain Name
        $this->ga_eGTM = $this->get_option("ga_eGTM") == "yes" ? true : false; //client GTM enable
        $this->ga_LC = get_woocommerce_currency(); //Local Currency from Back end 
        //set local currency variable on all page
        $this->wc_version_compare("tvc_lc=" . json_encode($this->ga_LC) . ";");
        $this->ga_eeT = $this->get_option("ga_eeT");  // EE Tracking - never put true : false here
        $this->ga_DF = $this->get_option("ga_DF") == "yes" ? true : false; //Display Feature
        $this->ga_imTh = 6; //Impression Threshold
        $this->ga_RTkn = $this->get_option("ga_RTkn"); //get refresh token
        //advance user defined values 
        $this->ga_IPA = $this->get_option("ga_IPA") == "yes" ? true : false; //IP Anony.
        $this->ga_404ET = $this->get_option("ga_404ET") == "yes" ? true : false; //404 Error Tracking
        $this->ga_optimize = $this->get_option("ga_optimize") == "yes" ? true : false;//Google Optimize featuer
         $this->ga_optimize_data = $this->get_option("ga_optimize_data");//Google Optimize ID
        $this->ga_UID = $this->get_option("ga_UID") == "yes" ? true : false; // User ID
        $this->ga_CG = $this->get_option("ga_CG") == "yes" ? true : false; // Content Grouping
        $this->ga_CGInd = 5; // We have fixed CG index : 5  //CG Index
        $this->ga_FF = $this->get_option("ga_FF") == "yes" ? true : false;  //Form Field Tracking
        $this->ga_InPromo = $this->get_option("ga_InPromo") == "yes" ? true : false; // Internal Promotion
        $this->ga_InPromoData = $this->get_option("ga_InPromoData"); // IP Data
        //Save Changes action for admin settings
        add_action("woocommerce_update_options_integration_" . $this->id, array($this, "process_admin_options"));

        // API Call to LS with e-mail
        // Add All Analytics code into WP environment front end
        add_action("wp_head", array($this, "add_Analytics_code"));
                //Ga optimize Enabled in Admin
        add_action("admin_footer", array($this, "admin_ga_optimize_enabled"));

        // check if current user is Admin or not
        if (is_admin() || current_user_can("manage_options")) {
            return;
        }

        // Enhanced Ecommerce product impression hook on Home, Cat, Shop, Search ,Product Pages
        add_action("wp_footer", array($this, "t_products_impre_clicks"));
        add_action("woocommerce_after_shop_loop_item", array($this, "bind_product_metadata"));
        add_action("woocommerce_after_single_product", array($this, "product_detail_view"));
        add_action("woocommerce_after_cart", array($this, "remove_cart_tracking"));
        //check out step 1,2,3
        add_action("woocommerce_after_checkout_billing_form", array($this, "checkout_step_1_2_tracking"));
        add_action("woocommerce_after_checkout_billing_form", array($this, "checkout_step_3_tracking"));
        add_action("woocommerce_after_add_to_cart_button", array($this, "add_to_cart"));
                
        //Error 404 Tracking
        add_action("wp_footer", array($this, "error_404_tracking"));


        
        //USER ID Tracking
        add_action("wp_footer", array($this, "encode_email_id"));
        add_action("wp_footer", array($this, "user_id_tracking"));

        //form field analysis
        add_action("woocommerce_after_checkout_form", array($this, "form_field_tracking"));

        //Internal Promotions
        add_action("wp_footer", array($this, "internal_promotion"));

        //Advanced Store data Tracking
        add_action("wp_footer", array($this, "tvc_store_meta_data"));
    }
    
    /**
     * Get store meta data for trouble shoot
     * @access public
     * @return void
     */
  
    function tvc_store_meta_data() {
        //only on home page
        global $woocommerce;
        $tvc_sMetaData = array();
        $tvc_sMetaData = array(
            'tvc_wcv' => $woocommerce->version,
            'tvc_wpv' => get_bloginfo('version'),
            'tvc_eev' => $this->tvc_eeVer,
            'tvc_cnf' => array(
                't_ee' => $this->ga_eeT,
                't_df' => $this->ga_DF,
                't_thr' => $this->ga_imTh,
                't_uid' => $this->ga_UID,
                't_ip' => $this->ga_InPromo,
                't_ipa' => $this->ga_IPA,
                't_ff' => $this->ga_FF,
                't_cg' => $this->ga_CG,
                't_404' => $this->ga_404ET,
            )
        );
        $this->wc_version_compare("tvc_smd=" . json_encode($tvc_sMetaData) . ";");
    }

    /**
     * add GTM code for EE js
     * contains  GTM code , Dev id code and GA code
     * @access public
     * @return void
     */

    function admin_ga_optimize_enabled() {
       if(isset($_GET['tab']) && $_GET['tab'] =='integration' ){

        echo '<script>
                t_ga_chk=jQuery("#woocommerce_actionable_google_analytics_ga_optimize").is(":checked");
                if(t_ga_chk){
                   jQuery("#woocommerce_actionable_google_analytics_ga_optimize_data").removeAttr("disabled");
                }
               jQuery("#woocommerce_actionable_google_analytics_ga_optimize").live("change",function(){
                t_ga_chk=jQuery(this).is(":checked");
                if(t_ga_chk){
                   jQuery("#woocommerce_actionable_google_analytics_ga_optimize_data").removeAttr("disabled");
                }else{
                    jQuery("#woocommerce_actionable_google_analytics_ga_optimize_data").attr("disabled",true);
                    t_display_chk=jQuery("#woocommerce_actionable_google_analytics_ga_optimize_data").is(":checked");
                    if(t_display_chk){
                      jQuery("#woocommerce_actionable_google_analytics_ga_optimize_data").removeAttr("checked");
                    }                 }
                   })</script>';
        }
    }

    function add_Analytics_code() {
        // Check if is order received page and stop when the products and not tracked
        if ( is_order_received_page()) {
            $order_id = empty( $_GET[ "order" ] ) ? ( $GLOBALS[ "wp" ]->query_vars[ "order-received" ] ? $GLOBALS[ "wp" ]->query_vars[ "order-received" ] : 0 ) : absint( $_GET[ "order" ] );
            echo $this->thankyou_page_code( $order_id );
        }

        $tracking_id = $this->ga_id;
        if (!$tracking_id) {
            return;
        }

        //domain name validation
        if (!empty($this->ga_set_domain_name)) {
            $set_domain_name = esc_js($this->ga_set_domain_name);
        } else {
            $set_domain_name = "auto";
        }

        //check if Admin is logged in or not and admin bar is showing or not
        if (!is_admin() || !is_admin_bar_showing()) {
            
            $t_page_type = $this->add_page_type();

             //Google Optimize ID
            if ($this->ga_optimize_data && $this->ga_optimize) {

                $ga_optimize_page_hide_snippet = '<!-- Google Optimize Page-hiding snippet By AGA Tatvic--><style>.async-hide { opacity: 0 !important} </style>
                <script>(function(a,s,y,n,c,h,i,d,e){s.className+=" "+y;h.start=1*new Date;
                h.end=i=function(){s.className=s.className.replace(RegExp(" ?"+y)," ")};
                (a[n]=a[n]||[]).hide=h;setTimeout(function(){i();h.end=null},c);h.timeout=c;
                })(window,document.documentElement,"async-hide","dataLayer",4000,
                {"'.esc_js($this->ga_optimize_data).'":true});</script>';

                $ga_optimize_code = 'ga("require","'.esc_js($this->ga_optimize_data).'");';
            } else {
                $ga_optimize_page_hide_snippet = ""; 
                $ga_optimize_code = "";
             }

            // Code for Content Grouping
            if ($this->ga_CG) {
                //get content grouping ID
                $ga_cg_index = $this->ga_CGInd; //CG index fixed : 5
                $ga_content_grouping = 'ga("set", "contentGroup' . $ga_cg_index . '","' . $t_page_type . '");';
                
                //return $ga_content_grouping;
                $ga_content_grouping_code =  $ga_content_grouping;

            }else{
                $ga_content_grouping_code ="";    
            }

            $ga_pagetype ='ga("set", "dimension2","'.$t_page_type.'");';
            //get ga content grouping code if it is enabled --- not on Admin side
            

            //add Pageview on order page if admin is logged in
            $ga_pageview = 'ga("send", "pageview");';

            // IP Anonymization
            if ($this->ga_IPA) {
                $ga_ip_anonymization = 'ga("set", "anonymizeIp", true);';
            } else {
                $ga_ip_anonymization = '';
            }
        } else {
            $ga_content_grouping_code = '';
            $ga_pageview = '';
        }
        // GTM container Code,Plugin Details, Dev Id code and GA code Snippet
        echo '
         <!--Enhanced Ecommerce Google Analytics Plugin for Woocommerce by Tatvic. Plugin Version: ' . $this->tvc_eeVer . '-version-->
        <script>(window.gaDevIds=window.gaDevIds||[]).push("5CDcaG");</script>
        '.$ga_optimize_page_hide_snippet.'
        <script>        
        (function(i,s,o,g,r,a,m){i["GoogleAnalyticsObject"]=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,"script","//www.google-analytics.com/analytics.js","ga");
            ga("create", "' . esc_js($tracking_id) . '", "' . $set_domain_name . '");
            '.$ga_optimize_code.'
            ga(function(tracker) {
                tvc_clientID = tracker.get("clientId");
                ga("set", "dimension17", tvc_clientID);
            });
                        ga("require", "displayfeatures");
                        ga("require", "ec", "ec.js");
                        ' . $ga_content_grouping_code . '
                        ' . $ga_ip_anonymization . '
                        ' . $ga_pagetype . '
                        ' . $ga_pageview . '
        </script>';

        //check if user has enable own GTM
        if (!$this->ga_eGTM) {
            echo '
                <!-- Google Tag Manager -->
                <noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-TSHSWL"
                height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
                <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({"gtm.start":
                new Date().getTime(),event:"gtm.js"});var f=d.getElementsByTagName(s)[0],
                j=d.createElement(s),dl=l!="dataLayer"?"&l="+l:"";j.async=true;j.src=
                "//www.googletagmanager.com/gtm.js?id="+i+dl;f.parentNode.insertBefore(j,f);
                })(window,document,"script","dataLayer","GTM-TSHSWL");</script>
                <!-- End Google Tag Manager -->
            <!--Enhanced Ecommerce Google Analytics Plugin for Woocommerce by Tatvic. Plugin Version: ' . $this->tvc_eeVer . '-version-->
            ';
        }
    }

    /**
     * 404 Error Tracking
     * 
     */
    function error_404_tracking() {
        if (is_404() && $this->ga_404ET) {
            $error_call = '
            if(typeof(error_404_tracking)!=="undefined" && typeof(error_404_tracking) === "function"){
                                    error_404_tracking();
                                }else{
                                t_404_error_call=true;
                                }
            ';
            $this->wc_version_compare($error_call);
        }
    }

    /**
     * obfuscated email address for USER ID
     * 
     * @access public
     * @return void
     */
    function encode_email_id() {
        if (is_user_logged_in() && !is_admin()) {
            $email_id = wp_get_current_user();
            $domain = get_site_url();
            $split_chr = strpos($domain, '//');
            $domain = substr($domain, $split_chr + 2);
            $t_uid = base64_encode($email_id->user_email);
            @setcookie('t_uid', $t_uid, time() + 3600 * 24, '/');
        }
    }

    /**
     * USER ID tracking
     * 
     * @access public
     * @return void
     */
    function user_id_tracking() {
        //User ID Implementation
        $user_id = "if(typeof(user_id_tracking)!=='undefined' && typeof(user_id_tracking) === 'function'){
                    user_id_tracking();
                }else{
                    t_userid_call=true;
                }
        ";
        //check user id is enabled or not
        if ($this->ga_UID)
            $this->wc_version_compare($user_id);
    }
    /**
     * Initialise Settings Form Fields
     *
     * @access public
     * @return void
     */
    function init_form_fields() {
        $this->form_fields = array(
            "ga_email" => array(
                "title" => __("Email Address", "woocommerce"),
                "description" => __("Provide your work email address to receive plugin enhancement updates", "woocommerce"),
                "type" => "email",
                "placeholder" => "example@test.com",
                'custom_attributes' => array(
                    'required' => "required",
                ),
                "desc_tip" => true,
                "default" => get_option("ga_email") // Backwards compat
            ),
            "ga_id" => array(
                "title" => __("Google Analytics ID", "woocommerce"),
                "description" => __("Enter your Google Analytics ID here. You can login into your Google Analytics account to find your ID. e.g.<code>UA-XXXXX-X</code>", "woocommerce"),
                "type" => "text",
                "placeholder" => "UA-XXXXX-X",
                "desc_tip" => true,
                "default" => get_option("ga_id") // Backwards compat
            ),
            "ga_eGTM" => array(
                "title" => __("Use Our Extension with Your GTM", "woocommerce"),
                "label" => __(" ", "woocommerce"),
                "type" => "checkbox",
                "checkboxgroup" => "",
                "description" => sprintf(__("If Yes, follow the instructions <a href='http://plugins.tatvic.com/downloads/woo-plugin-GTM-steps.pdf' target='_blank'>mention</a> in the document to learn how to setup your GTM with our Extension.", "woocommerce")),
                "default" => get_option("ga_eGTM") ? get_option("ga_eGTM") : "no"  // Backwards compat
            ),
            "ga_RTkn" => array(
                "title" => __("GA Authentication Token", "woocommerce"), //http://plugins.tatvic.com/tat_ga/ga_rdr.php
                "description" => sprintf(__("<a href='http://plugins.tatvic.com/tat_ga/ga_rdr_new.php' target='_blank'>Click Here</a> to Authenticate your Google Analytics Account to See Product Refund Data in Your GA. At the end of the authentication, you will be given the token. Kindly copy paste the token in the field above.")),
                "type" => "text",
                "placeholder" => "",
                "custom_attributes" => array(
                    "required" => "required",
                ),
                "default" => get_option("ga_RTkn")
            ),
            "ga_eeT" => array(
                "title" => __("Enhanced Ecommerce Tracking Code", "woocommerce"),
                "label" => __("Add Enhanced Ecommerce Tracking Code", "woocommerce"),
                "type" => "checkbox",
                "checkboxgroup" => "",
                "desc_tip" => true,
                "description" => sprintf(__("This feature adds Enhanced Ecommerce Tracking Code to your Store", "woocommerce")),
                "default" => get_option("ga_eeT") ? get_option("ga_eeT") : "no"  // Backwards compat
            ),            
            //Advance Features section
            "ga_ee_AdFeature" => array(
                "title" => __("<h3>Advanced Features</h3>", "woocommerce"),
                "description" => sprintf(__("", "woocommerce")),
                "type" => "hidden"
            ),
            "ga_optimize" => array(
                "title" => __("Google Optimize", "woocommerce"),
                "label" => __("Enable Google Optimize Feature (Optional)", "woocommerce"),
                "type" => "checkbox",
                "checkboxgroup" => "",
                "description" => __("<a href='https://support.google.com/360suite/optimize/answer/6197440?hl=en&ref_topic=6314903'>Google Optimize</a> allows you to test variants of web pages and see how they perform against an objective that you specify. Optimize monitors the performance of your experiment and tells you which variant is the leader.", "woocommerce"),
                "default" => get_option("ga_optimize") ? get_option("ga_optimize") : "no"  // Backwards compat
            ),
            "ga_optimize_data" => array(
                "title" => __("", "woocommerce"),
                "type" => "text",
                "description" => __("Enter a valid Google Optimize ID", "woocommerce"),
                "custom_attributes" => array(
                    "disabled" => "disabled",
                ),
                "placeholder" => "Enter Google Optimize ID",
                "desc_tip" => true,
                "default" => get_option("ga_optimize_data") // Backwards compat
            ),
            "ga_UID" => array(
                "title" => __("User ID Tracking", "woocommerce"),
                "label" => __("Enable User ID Tracking (Optional)", "woocommerce"),
                "type" => "checkbox",
                "checkboxgroup" => "",
                "description" => sprintf(__("Enable this feature to get more accurate user count & better analyze the signed-in user experience.  To use User ID Tracking kindly create new <b>View in GA</b> as instructed in <b>step 1 of this</b> <a href='http://plugins.tatvic.com/enhanced-ecommerce-installation-wizard/?store_type=woocommerce#1' target='_blank'> wizard</a>.", "woocommerce")),
                "default" => get_option("ga_UID") ? get_option("ga_UID") : "no"  // Backwards compat
            ),
            "ga_CG" => array(
                "title" => __("Content Grouping", "woocommerce"),
                "label" => __("Add Code to enable content grouping (Optional)", "woocommerce"),
                "type" => "checkbox",
                "checkboxgroup" => "",
                "description" => sprintf(__("Content grouping helps you group your web pages (content). To use this feature create Content Grouping in your GA as instructed in <b>step 2 of this </b><a href='http://plugins.tatvic.com/enhanced-ecommerce-installation-wizard/?store_type=woocommerce#2' target='_blank'>wizard</a>.", "woocommerce")),
                "default" => get_option("ga_CG") ? get_option("ga_CG") : "no"  // Backwards compat
            ),
            "ga_FF" => array(
                "title" => __("Form Field Tracking", "woocommerce"),
                "label" => __("Add Code to enable Form Field Analysis (Optional)", "woocommerce"),
                "type" => "checkbox",
                "checkboxgroup" => "",
                "desc_tip" => true,
                "description" => sprintf(__("Enable this feature to carry out form field analysis of your E-commerce store", "woocommerce")),
                "default" => get_option("ga_FF") ? get_option("ga_FF") : "no"  // Backwards compat
            ),
            "ga_IPA" => array(
                "title" => __("IP Anonymization", "woocommerce"),
                "label" => __("Enable IP Anonymization (Optional)", "woocommerce"),
                "type" => "checkbox",
                "checkboxgroup" => "",
                "description" => sprintf(__("Use this feature to anonymize (or stop collecting) the I.P Address of your users in Google Analytics. Be in legal compliance by using I.P Anonymization which is important for EU countries", "woocommerce")),
                "default" => get_option("ga_IPA") ? get_option("ga_IPA") : "no"  // Backwards compat
            ),
            "ga_404ET" => array(
                "title" => __("404 Error Tracking", "woocommerce"),
                "label" => __("Enable 404 Error Tracking (Optional)", "woocommerce"),
                "type" => "checkbox",
                "checkboxgroup" => "",
                "desc_tip" => true,
                "description" => sprintf(__("Enable this feature to fire an event whenever a user lands on your 404 Error Page. You can view this report in Behavior > Events section. (Category Name - 404_error)", "woocommerce")),
                "default" => get_option("woocommerce_ga_404ET") ? get_option("woocommerce_ga_404ET") : "no"  // Backwards compat
            ),
            "ga_InPromo" => array(
                "title" => __("Internal Promotion", "woocommerce"),
                "label" => __("Add Internal Promotion Tracking Code (Optional)", "woocommerce"),
                "type" => "checkbox",
                "checkboxgroup" => "",
                "description" => sprintf(__("This feature enables internal promotion report in Enhanced Ecommerce.<br/>To use Internal Promotion feature, Please provide us the data in the requested format:<br/>
                    Image Path, Promo ID, Name, Creative, Position of the Banner", "woocommerce")),
                "default" => get_option("ga_InPromo") ? get_option("ga_InPromo") : "no"  // Backwards compat
            ),
            "ga_InPromoData" => array(
                "title" => __("", "woocommerce"), //Internal Promotion Data
                "description" => sprintf(__("Example:
                    http://estore.tatvic.com/wp-content/uploads/2014/10/promo1.png,self_promo1,promotion,new_year_sale,top_banner<br/><br/>Where,<br/>
                    Image Path: http://estore.tatvic.com/wp-content/uploads/2014/10/promo1.png<br/>
                    Promo ID: self_promo1<br/> 
                    Name: promotion<br/>
                    Creative: new_year_sale<br/>
                    Position of the Banner: top_banner<br/><br/>
                    Note: Seperate more than one internal promotion data by new line. Also, do not use white space in your name. 
                    ", "woocommerce")),
                "type" => "textarea",
                "placeholder" => 'Image Path,ID,Name,Creative,Position',
                "default" => get_option("ga_InPromoData")
            ),
        );
        /* When user updates the email, post it to the remote server */
        if (isset($_GET["tab"]) && isset($_REQUEST["section"]) && isset($_REQUEST["woocommerce_" . $this->id . "_ga_email"])) {
            $current_tab = ( empty($_GET["tab"]) ) ? false : sanitize_text_field(urldecode($_GET["tab"]));
            $current_section = ( empty($_REQUEST["section"]) ) ? false : sanitize_text_field(urldecode($_REQUEST["section"]));
            $save_for_the_plugin = ($current_tab == "integration" ) && ($current_section == $this->id);

            $update_made_for_email = $_REQUEST["woocommerce_" . $this->id . "_ga_email"] != $this->get_option("ga_email");
            if ($save_for_the_plugin && $update_made_for_email) {
                if ($_REQUEST["woocommerce_" . $this->id . "_ga_email"] != "") {
                    $email = $_REQUEST["woocommerce_" . $this->id . "_ga_email"];
                    $token = $_REQUEST["woocommerce_" . $this->id . "_ga_RTkn"];
                    $this->send_email_to_tatvic($email, 'active',$token);
                }
            }
        }
    }

    /**
     * Google Analytics content grouping
     * Pages: Home, Category, Product, Cart, Checkout, Search ,Shop, Thankyou and Others
     *
     * @access public
     * @return void
     */
    function add_page_type() {
        //identify pages
        if (is_home() || is_front_page()) {
            $t_page_name = "Home Page";
        } else if (is_product_category()) {
            $t_page_name = "Category Pages";
        } else if (is_product()) {
            $t_page_name = "Product Pages";
        } else if (is_cart()) {
            $t_page_name = "Cart Page";
        }else if (is_order_received_page()) {
            $t_page_name = "Thankyou Page";
        } else if (is_checkout()) {
            $t_page_name = "Checkout Page";
        } else if (is_search()) {
            $t_page_name = "Search Page";
        } else if (is_shop()) {
            $t_page_name = "Shop Page";
        } else if (is_404()) {
            $t_page_name = "404 Error Pages";
        } else {
            $t_page_name = "Others";
        }
        //set js parameter - page name
        //$this->wc_version_compare("tvc_pt=" . json_encode($t_page_name) . ";");
        return $t_page_name;

        //add content grouping code
        
    }

    /**
     * Google Analytics eCommerce tracking
     *
     * @access public
     * @param mixed $order_id
     * @return void
     */
    function thankyou_page_code($order_id) {

        global $woocommerce;

        if ($this->disable_tracking($this->ga_eeT) || current_user_can("manage_options") || get_post_meta($order_id, "tvc_tracked", true) == 1)
            return;

        // Get the order and output tracking code
        $order = new WC_Order($order_id);
        //Get Applied Coupon Codes
        $coupons_list = '';
        if ($order->get_used_coupons()) {
            $coupons_count = count($order->get_used_coupons());
            $i = 1;
            foreach ($order->get_used_coupons() as $coupon) {
                $coupons_list .= $coupon;
                if ($i < $coupons_count)
                    $coupons_list .= ', ';
                $i++;
            }
        }

        if ($order->get_items()) {
            $i = 0;
            foreach ($order->get_items() as $item) {
                $_product = $order->get_product_from_item($item);
                $tvc_prnm = get_the_title($item['product_id']);
                
                //get product categories
                $tmp_cat = array();
                if (version_compare($woocommerce->version, "2.7", "<")){
                    $categories = get_the_terms($item['product_id'], "product_cat");

                }else{
                    $categories = get_the_terms($item['product_id'], "product_cat");
                }
                if ($categories) {
                    foreach ($categories as $category) {
                        $tmp_cat[] = $category->name;
                    }
                }
                $categories = esc_js(join(",", $tmp_cat));
                
                //check if product variation data is exists
                if (version_compare($woocommerce->version, "2.7", "<")){
                    if ($_product->product_type === "variation") {
                    //variant data
                    $prod_var_array = $_product->get_variation_attributes();
                    //get var product weight
                    $t_wt = '';
                    if ($_product->variation_has_weight) {
                        $t_wt = $_product->get_weight() . ' ' . esc_attr(get_option('woocommerce_weight_unit'));
                    }
                } else if ($_product->product_type === 'simple') {
                    //for simple product it's should be blank array
                    $prod_var_array = array();
                    //get product weight
                    $t_wt = '';
                    if ($_product->has_weight()) {
                        $t_wt = $_product->get_weight() . ' ' . esc_attr(get_option('woocommerce_weight_unit'));
                    }
                }
                //orderpage Variable Prod Array
                $orderpage_prod_Array[$i] = array(
                    "tvc_id" => esc_html($_product->id),
                    "tvc_i" => esc_js($_product->sku ? $_product->sku : $_product->id),
                    "tvc_n" => $tvc_prnm,
                    "tvc_p" => esc_js($order->get_item_total($item)),
                    "tvc_rp" => $_product->regular_price,
                    "tvc_sp" => $_product->sale_price,
                    "tvc_pd" => $this->cal_prod_discount($_product->regular_price, $_product->sale_price),
                    "tvc_c" => $categories,
                    "tvc_q" => esc_js($item["qty"]),
                    "tvc_vat" => $prod_var_array,
                    "tvc_wt" => $t_wt,
                    "tvc_di" => $_product->get_dimensions(), //dimensions
                    "tvc_ss" => $_product->is_in_stock(),
                    "tvc_st" => $_product->get_stock_quantity(),
                    "tvc_tst" => $_product->get_total_stock(),
                    "tvc_rc" => $_product->get_rating_count(),
                    "tvc_rs" => $_product->get_average_rating()
                );
                }else{
                    if ($_product->get_type() === "variation") {
                    //variant data
                    $prod_var_array = $_product->get_variation_attributes();
                    //get var product weight
                    $t_wt = '';
                    if ($_product->get_weight() == "") {
                        $t_wt = $_product->get_weight() . ' ' . esc_attr(get_option('woocommerce_weight_unit'));
                    }
                } else if ($_product->get_type() === 'simple') {
                    //for simple product it's should be blank array
                    $prod_var_array = array();
                    //get product weight
                    $t_wt = '';
                    if ($_product->has_weight()) {
                        $t_wt = $_product->get_weight() . ' ' . esc_attr(get_option('woocommerce_weight_unit'));
                    }
                }
                //orderpage Variable Prod Array
                $orderpage_prod_Array[$i] = array(
                    "tvc_id" => esc_html($_product->get_id()),
                    "tvc_i" => esc_js($_product->get_sku() ? $_product->get_sku() : $_product->get_id()),
                    "tvc_n" => $tvc_prnm,
                    "tvc_p" => esc_js($order->get_item_total($item)),
                    "tvc_rp" => $_product->get_regular_price(),
                    "tvc_sp" => $_product->get_sale_price(),
                    "tvc_pd" => $this->cal_prod_discount($_product->get_regular_price(), $_product->get_sale_price()),
                    "tvc_c" => $categories,
                    "tvc_q" => esc_js($item["qty"]),
                    "tvc_vat" => $prod_var_array,
                    "tvc_wt" => $t_wt,
                    //"tvc_di" => $_product->get_dimensions(), //dimensions
                    "tvc_ss" => $_product->is_in_stock(),
                    "tvc_st" => $_product->get_stock_quantity(),
                    "tvc_tst" => $_product->get_stock_quantity(),
                    "tvc_rc" => $_product->get_rating_count(),
                    "tvc_rs" => $_product->get_average_rating()
                );
                }
                
                $i++;
            }
            //make json for prod meta data on order page
            $this->wc_version_compare("tvc_oc=" . json_encode($orderpage_prod_Array) . ";");
            //get user type
            $t_user_id = wp_get_current_user();
            $user_bill_addr="";
            $user_ship_addr="";
            if (0 == $t_user_id->ID) {
                $t_ut = 'guest_user';
            } else {
                $t_ut = 'register_user';
                //get city of registed user
                $user_bill_addr = get_user_meta($t_user_id->ID, 'shipping_city', true);
                $user_ship_addr = get_user_meta($t_user_id->ID, 'billing_city', true);
            }

            //get shipping cost based on version >2.1 get_total_shipping() < get_shipping
            if (version_compare($woocommerce->version, "2.1", ">=")) {
                $tvc_sc = $order->get_total_shipping();
            } else {
                $tvc_sc = $order->get_shipping();
            }

            //orderpage transcation data json
            if (version_compare($woocommerce->version, "2.7", "<")){
                $orderpage_trans_Array = array(
                "tvc_tid" => esc_js($order->get_order_number()), // Transaction ID. Required
                "tvc_af" => esc_js(get_bloginfo('name')), // Affiliation or store name
                "tvc_rev" => esc_js($order->get_total()), // Grand Total
                "tvc_tt" => esc_js($order->get_total_tax()), // Tax
                "tvc_sc" => $tvc_sc, // Shipping cost
                "tvc_dc" => $coupons_list, //coupon code
                "tvc_cd" => esc_js($order->get_total_discount()), //cart discount
                "tvc_ut" => $t_ut, //user type
                "tvc_bad" => $user_bill_addr, //billing addr
                "tvc_sad" => $user_ship_addr, //shipping addr
                "tvc_pm" => $order->payment_method_title //payment method
            );
            }else{
                $orderpage_trans_Array = array(
                "tvc_tid" => esc_js($order->get_order_number()), // Transaction ID. Required
                "tvc_af" => esc_js(get_bloginfo('name')), // Affiliation or store name
                "tvc_rev" => esc_js($order->get_total()), // Grand Total
                "tvc_tt" => esc_js($order->get_total_tax()), // Tax
                "tvc_sc" => $tvc_sc, // Shipping cost
                "tvc_dc" => $coupons_list, //coupon code
                "tvc_cd" => esc_js($order->get_total_discount()), //cart discount
                "tvc_ut" => $t_ut, //user type
                "tvc_bad" => $user_bill_addr, //billing addr
                "tvc_sad" => $user_ship_addr, //shipping addr
                "tvc_pm" => $order->get_payment_method() //payment method
            );
            }
            
            //make json for trans data on order page
            $this->wc_version_compare("tvc_td=" . json_encode($orderpage_trans_Array) . ";");
            $thankyou_page_js = '
                var _0x9f1f=["\x67\x65\x74\x44\x61\x74\x65","\x73\x65\x74\x44\x61\x74\x65","","\x3B\x20\x65\x78\x70\x69\x72\x65\x73\x3D","\x74\x6F\x55\x54\x43\x53\x74\x72\x69\x6E\x67","\x63\x6F\x6F\x6B\x69\x65","\x3D","\x3B\x20\x50\x61\x74\x68\x3D\x20\x2F\x3B","\x75\x6E\x64\x65\x66\x69\x6E\x65\x64","\x6C\x65\x6E\x67\x74\x68","\x68\x61\x73\x4F\x77\x6E\x50\x72\x6F\x70\x65\x72\x74\x79","\x3B","\x73\x70\x6C\x69\x74","\x69\x6E\x64\x65\x78\x4F\x66","\x73\x75\x62\x73\x74\x72","\x72\x65\x70\x6C\x61\x63\x65","\x70\x70\x76\x69\x65\x77\x74\x69\x6D\x65\x72","\x72\x6F\x75\x6E\x64","\x73\x65\x74","\x64\x69\x6D\x65\x6E\x73\x69\x6F\x6E\x31\x34","\x64\x69\x6D\x65\x6E\x73\x69\x6F\x6E\x32","\x54\x68\x61\x6E\x6B\x79\x6F\x75\x20\x50\x61\x67\x65","\x64\x69\x6D\x65\x6E\x73\x69\x6F\x6E\x33","\x74\x76\x63\x5F\x75\x74","\x64\x69\x6D\x65\x6E\x73\x69\x6F\x6E\x35","\x74\x76\x63\x5F\x70\x6D","\x74\x76\x63\x5F\x62\x61\x64","\x74\x76\x63\x5F\x73\x61\x64","\x7C","\x74\x5F\x67\x43\x69\x74\x79","\x64\x69\x6D\x65\x6E\x73\x69\x6F\x6E\x36","\x26\x63\x75","\x74\x76\x63\x5F\x73\x73","\x69\x6E\x5F\x73\x74\x6F\x63\x6B","\x6F\x75\x74\x5F\x6F\x66\x5F\x73\x74\x6F\x63\x6B","\x74\x76\x63\x5F\x76\x61\x74","\x6B\x65\x79\x73","\x63\x6F\x6C\x6F\x72","\x73\x69\x7A\x65","\x65\x63\x3A\x61\x64\x64\x50\x72\x6F\x64\x75\x63\x74","\x74\x76\x63\x5F\x69","\x74\x76\x63\x5F\x6E","\x74\x76\x63\x5F\x63","\x74\x76\x63\x5F\x70","\x74\x76\x63\x5F\x71","\x74\x76\x63\x5F\x70\x64","\x25","\x74\x5F\x41\x54\x43\x5F\x70\x6F\x73","\x74\x76\x63\x5F\x73\x74","\x74\x76\x63\x5F\x72\x63","\x74\x76\x63\x5F\x72\x73","\x65\x63\x3A\x73\x65\x74\x41\x63\x74\x69\x6F\x6E","\x70\x75\x72\x63\x68\x61\x73\x65","\x74\x76\x63\x5F\x74\x69\x64","\x74\x76\x63\x5F\x61\x66","\x74\x76\x63\x5F\x72\x65\x76","\x74\x76\x63\x5F\x74\x74","\x74\x76\x63\x5F\x73\x63","\x74\x76\x63\x5F\x64\x63","\x73\x65\x6E\x64","\x65\x76\x65\x6E\x74","\x45\x6E\x68\x61\x6E\x63\x65\x64\x2D\x45\x63\x6F\x6D\x6D\x65\x72\x63\x65","\x6C\x6F\x61\x64","\x6F\x72\x64\x65\x72\x5F\x63\x6F\x6E\x66\x69\x72\x6D\x61\x74\x69\x6F\x6E","\x74\x5F\x70\x72\x6F\x64\x5F\x73\x65\x71","\x66\x69\x72\x73\x74\x5F\x41\x54\x43","\x72\x65\x61\x64\x79"];function t_setCookie(_0x70a7x2,_0x70a7x3){exdays=1;var _0x70a7x4= new Date();_0x70a7x4[_0x9f1f[1]](_0x70a7x4[_0x9f1f[0]]()+exdays);var _0x70a7x5=escape(_0x70a7x3)+((exdays==null)?_0x9f1f[2]:_0x9f1f[3]+_0x70a7x4[_0x9f1f[4]]());document[_0x9f1f[5]]=_0x70a7x2+_0x9f1f[6]+_0x70a7x5+_0x9f1f[7];} ;function t_empty(_0x70a7x7){if( typeof (_0x70a7x7)===_0x9f1f[8]||_0x70a7x7===null){return true;} ;if( typeof (_0x70a7x7[_0x9f1f[9]])!=_0x9f1f[8]){return _0x70a7x7[_0x9f1f[9]]==0;} ;var _0x70a7x8=0;for(var _0x70a7x9 in _0x70a7x7){if(_0x70a7x7[_0x9f1f[10]](_0x70a7x9)){_0x70a7x8++;} ;} ;return _0x70a7x8==0;} ;function t_getCookie(_0x70a7x2){var _0x70a7x9,_0x70a7xb,_0x70a7xc,_0x70a7xd=document[_0x9f1f[5]][_0x9f1f[12]](_0x9f1f[11]);for(_0x70a7x9=0;_0x70a7x9<_0x70a7xd[_0x9f1f[9]];_0x70a7x9++){_0x70a7xb=_0x70a7xd[_0x70a7x9][_0x9f1f[14]](0,_0x70a7xd[_0x70a7x9][_0x9f1f[13]](_0x9f1f[6]));_0x70a7xc=_0x70a7xd[_0x70a7x9][_0x9f1f[14]](_0x70a7xd[_0x70a7x9][_0x9f1f[13]](_0x9f1f[6])+1);_0x70a7xb=_0x70a7xb[_0x9f1f[15]](/^\s+|\s+$/g,_0x9f1f[2]);if(_0x70a7xb==_0x70a7x2){return unescape(_0x70a7xc);} ;} ;return null;} ;function t_delCookie(_0x70a7xf){if(t_getCookie(_0x70a7xf)){t_setCookie(_0x70a7xf,_0x9f1f[2]);} ;} ;jQuery(document)[_0x9f1f[66]](function (){if( typeof (tvc_td)!==_0x9f1f[8]){start_timestmp_purchase=t_getCookie(_0x9f1f[16]);if(start_timestmp_purchase!==undefined&&start_timestmp_purchase!==_0x9f1f[2]){elapsed= new Date()- new Date(start_timestmp_purchase);pp_purchase=(Math[_0x9f1f[17]](elapsed/1000)).toString();} ;ga(_0x9f1f[18],_0x9f1f[19],pp_purchase);ga(_0x9f1f[18],_0x9f1f[20],_0x9f1f[21]);ga(_0x9f1f[18],_0x9f1f[22],tvc_td[_0x9f1f[23]]);ga(_0x9f1f[18],_0x9f1f[24],tvc_td[_0x9f1f[25]]);if(!t_empty(tvc_td[_0x9f1f[26]])&&!t_empty(tvc_td[_0x9f1f[27]])){tvc_dim6=tvc_td[_0x9f1f[26]]+_0x9f1f[28]+tvc_td[_0x9f1f[27]];} else {tvc_dim6=t_getCookie(_0x9f1f[29]);} ;ga(_0x9f1f[18],_0x9f1f[30],tvc_dim6);ga(_0x9f1f[18],_0x9f1f[31],tvc_lc);t_vco=_0x9f1f[2];t_vsi=_0x9f1f[2];for(var _0x70a7x10 in tvc_oc){if(tvc_oc[_0x70a7x10][_0x9f1f[32]]){tvc_ss=_0x9f1f[33];} else {tvc_ss=_0x9f1f[34];} ;if((tvc_oc[_0x70a7x10])[_0x9f1f[10]](_0x9f1f[35])){t_identify_attr=Object[_0x9f1f[36]](tvc_oc[_0x70a7x10][_0x9f1f[35]]);for(i=0;i<t_identify_attr[_0x9f1f[9]];i++){if(t_identify_attr[i][_0x9f1f[13]](_0x9f1f[37])>-1){t_vco=tvc_oc[_0x70a7x10][_0x9f1f[35]][t_identify_attr[i]];} else {if(t_identify_attr[i][_0x9f1f[13]](_0x9f1f[38])>-1){t_vsi=tvc_oc[_0x70a7x10][_0x9f1f[35]][t_identify_attr[i]];} ;} ;} ;} ;ga(_0x9f1f[39],{"\x69\x64":tvc_oc[_0x70a7x10][_0x9f1f[40]],"\x6E\x61\x6D\x65":tvc_oc[_0x70a7x10][_0x9f1f[41]],"\x63\x61\x74\x65\x67\x6F\x72\x79":tvc_oc[_0x70a7x10][_0x9f1f[42]],"\x70\x72\x69\x63\x65":tvc_oc[_0x70a7x10][_0x9f1f[43]],"\x71\x75\x61\x6E\x74\x69\x74\x79":tvc_oc[_0x70a7x10][_0x9f1f[44]],"\x76\x61\x72\x69\x61\x6E\x74":t_vco,"\x64\x69\x6D\x65\x6E\x73\x69\x6F\x6E\x31":tvc_ss,"\x64\x69\x6D\x65\x6E\x73\x69\x6F\x6E\x34":tvc_oc[_0x70a7x10][_0x9f1f[45]]+_0x9f1f[46],"\x64\x69\x6D\x65\x6E\x73\x69\x6F\x6E\x31\x30":t_getCookie(_0x9f1f[47]),"\x64\x69\x6D\x65\x6E\x73\x69\x6F\x6E\x31\x31":tvc_oc[_0x70a7x10][_0x9f1f[48]],"\x64\x69\x6D\x65\x6E\x73\x69\x6F\x6E\x31\x32":tvc_oc[_0x70a7x10][_0x9f1f[49]],"\x64\x69\x6D\x65\x6E\x73\x69\x6F\x6E\x31\x33":tvc_oc[_0x70a7x10][_0x9f1f[50]],"\x64\x69\x6D\x65\x6E\x73\x69\x6F\x6E\x31\x36":t_vsi});} ;ga(_0x9f1f[51],_0x9f1f[52],{"\x69\x64":tvc_td[_0x9f1f[53]],"\x61\x66\x66\x69\x6C\x69\x61\x74\x69\x6F\x6E":tvc_td[_0x9f1f[54]],"\x72\x65\x76\x65\x6E\x75\x65":tvc_td[_0x9f1f[55]],"\x74\x61\x78":tvc_td[_0x9f1f[56]],"\x73\x68\x69\x70\x70\x69\x6E\x67":tvc_td[_0x9f1f[57]],"\x63\x6F\x75\x70\x6F\x6E":tvc_td[_0x9f1f[58]]});ga(_0x9f1f[59],_0x9f1f[60],_0x9f1f[61],_0x9f1f[62],_0x9f1f[63],{"\x6E\x6F\x6E\x49\x6E\x74\x65\x72\x61\x63\x74\x69\x6F\x6E":1});t_cook_arry= new Array(_0x9f1f[64],_0x9f1f[16],_0x9f1f[65]);for(var _0x70a7x10 in t_cook_arry){t_delCookie(t_cook_arry[_0x70a7x10]);} ;} ;} );
            ';
            //check woocommerce version
            $this->wc_version_compare($thankyou_page_js);
            update_post_meta($order_id, "tvc_tracked", 1);
        }
    }

    /**
     * Enhanced E-commerce tracking for single product add to cart (product page)
     *
     * @access public
     * @return void
     */
    function add_to_cart() {
        if ($this->disable_tracking($this->ga_eeT))
            return;
        //return if not product page       
        if (!is_single())
            return;

        $add_to_cart_js = '                     
                        if(typeof(single_ATC)!=="undefined" && typeof(single_ATC) === "function"){
                            single_ATC();
                        }else{
                            t_sATC_call=true;   
                        }
                    
        ';
        //check woocommerce version
        $this->wc_version_compare($add_to_cart_js);
    }

    /**
     * Enhanced E-commerce tracking for product detail view
     *
     * @access public
     * @return void
     */
    public function product_detail_view() {

        if ($this->disable_tracking($this->ga_eeT)) {
            return;
        }

        global $product,$woocommerce;
        if (version_compare($woocommerce->version, "2.7", "<")){
                $category = get_the_terms($product->ID, "product_cat");
            }else{
                $category = get_the_terms($product->get_id(), "product_cat");
            }
        $categories = "";
        if ($category) {
            foreach ($category as $term) {
                $categories.=$term->name . ",";
            }
        }
        //check if product is variable product and product has child products
        if ($product->is_type('variable') && $product->has_child()) {
            //Get All variations IDs
            $t_var_ids = $product->get_children();

            //looping to get all variation data
            for ($i = 0; $i < sizeof($t_var_ids); $i++) {
                $t_var_metadata = wc_get_product($t_var_ids[$i]);
                $t_var_sku = $t_var_metadata->get_sku(); //get sku
                $t_var_prc = $t_var_metadata->get_regular_price(); //get price
                $t_var_sprc = $t_var_metadata->get_sale_price(); //get sale price
                $t_var_rprc = $t_var_metadata->get_regular_price(); //get regular price
                //get var product weight
                $t_vwt = '';
                if ($t_var_metadata->get_weight() == "") {
                    $t_vwt = $t_var_metadata->get_weight() . ' ' . esc_attr(get_option('woocommerce_weight_unit'));
                }

                $prod_var_array[$t_var_ids[$i]] = array(
                    "tvc_vi" => $t_var_sku, //variation sku
                    "tvc_vp" => $t_var_prc, // get price
                    "tvc_vsp" => $t_var_sprc, //get sale price
                    "tvc_vrp" => $t_var_rprc, //get regular price
                    "tvc_pd" => $this->cal_prod_discount($t_var_rprc, $t_var_sprc),
                    "tvc_vat" => $t_var_metadata->get_variation_attributes(), //get avialable attr
                    "tvc_vwt" => $t_vwt, //get weight
                    //"tvc_vdi" => $t_var_metadata->get_dimensions(), //dimensions
                    "tvc_vss" => $t_var_metadata->is_in_stock(), //check stock status
                    "tvc_vst" => $t_var_metadata->get_stock_quantity(), //stock quantity
                    "tvc_vtst" => $t_var_metadata->get_stock_quantity() //total stock with variation count
                );
            }
        } else {
            $prod_var_array = array();
        }

        //remove last comma(,) if multiple categories are there
        $categories = rtrim($categories, ",");
        //get product weight
        $t_wt = '';
        if ($product->has_weight()) {
            $t_wt = $product->get_weight() . ' ' . esc_attr(get_option('woocommerce_weight_unit')); //cond. here bcoz of weight unit
        }
        //product detail view json
        if (version_compare($woocommerce->version, "2.7", "<")){
                $prodpage_detail_json = array(
            "tvc_i" => $product->get_sku() ? $product->get_sku() : $product->id,
            "tvc_n" => $product->get_title(),
            "tvc_c" => $categories,
            "tvc_p" => $product->get_price(),
            "tvc_ss" => $product->is_in_stock(),
            "tvc_st" => $product->get_stock_quantity(),
            "tvc_tst" => $product->get_total_stock(),
            "tvc_var" => $prod_var_array,
            "tvc_rp" => $product->regular_price,
            "tvc_sp" => $product->sale_price,
            "tvc_pd" => $this->cal_prod_discount($product->regular_price, $product->sale_price),
            "tvc_wt" => $t_wt,
            "tvc_di" => $product->get_dimensions(), //dimensions
            "tvc_rc" => $product->get_rating_count(),
            "tvc_rs" => $product->get_average_rating() != '' ? $product->get_average_rating() : '0'
        );
            }else{
                $prodpage_detail_json = array(
            "tvc_i" => $product->get_sku() ? $product->get_sku() : $product->get_id(),
            "tvc_n" => $product->get_title(),
            "tvc_c" => $categories,
            "tvc_p" => $product->get_regular_price(),
            "tvc_ss" => $product->is_in_stock(),
            "tvc_st" => $product->get_stock_quantity(),
            "tvc_tst" => $product->get_stock_quantity(),
            "tvc_var" => $prod_var_array,
            "tvc_rp" => $product->get_regular_price(),
            "tvc_sp" => $product->get_sale_price(),
            "tvc_pd" => $this->cal_prod_discount($product->get_regular_price(), $product->get_sale_price()),
            "tvc_wt" => $t_wt,
            //"tvc_di" => $product->get_dimensions(), //dimensions
            "tvc_rc" => $product->get_rating_count(),
            "tvc_rs" => $product->get_average_rating() != '' ? $product->get_average_rating() : '0'
        );
            }
        
        if (empty($prodpage_detail_json)) { //prod page array
            $prodpage_detail_json = array();
        }
        //prod page detail view json
        $this->wc_version_compare("tvc_po=" . json_encode($prodpage_detail_json) . ";");

        //call function to fire detail view of product    
        $prod_detail_view_js = '
            if(typeof(prod_detail_view)!=="undefined" && typeof(prod_detail_view) === "function"){
                prod_detail_view();
        }else{
        t_pDetail_call=true;
    }';
        //check woocommerce version
        $this->wc_version_compare($prod_detail_view_js);
    }

    /**
     * Enhanced E-commerce tracking for product impressions on category pages
     *
     * @access public
     * @return void
     */
    public function bind_product_metadata() {

        if ($this->disable_tracking($this->ga_eeT)) {
            return;
        }

        global $product,$woocommerce;
        if (version_compare($woocommerce->version, "2.7", "<")){
                $category = get_the_terms($product->ID, "product_cat");
            }else{
                $category = get_the_terms($product->get_id(), "product_cat");
            }
        $categories = "";
        if ($category) {
            foreach ($category as $term) {
                $categories.=$term->name . ",";
            }
        }
        //remove last comma(,) if multiple categories are there
        $categories = rtrim($categories, ",");
        //declare all variable as a global which will used for make json
        global $homepage_json_fp, $homepage_json_ATC_link, $homepage_json_rp, $prodpage_json_relProd, $catpage_json, $prodpage_json_ATC_link, $catpage_json_ATC_link;
        //is home page then make all necessory json
        if (is_home() || is_front_page()) {
            if (!is_array($homepage_json_fp) && !is_array($homepage_json_rp) && !is_array($homepage_json_ATC_link)) {
                $homepage_json_fp = array();
                $homepage_json_rp = array();
                $homepage_json_ATC_link = array();
            }
            // ATC link Array
            if (version_compare($woocommerce->version, "2.7", "<")){
               $homepage_json_ATC_link[$product->add_to_cart_url()] = array("tvc_u" => get_permalink($product->id));
            }else{
               $homepage_json_ATC_link[$product->add_to_cart_url()] = array("tvc_u" => get_permalink($product->get_id()));
            }
            
            //check if product is featured product or not  
            if ($product->is_featured()) {
                //check if product is already exists in homepage featured json 
                if (version_compare($woocommerce->version, "2.7", "<")){
                    if (!array_key_exists(get_permalink($product->id), $homepage_json_fp)) {

                    $homepage_json_fp[get_permalink($product->id)] = array(
                        "tvc_id" => esc_html($product->id),
                        "tvc_i" => esc_html($product->get_sku() ? $product->get_sku() : $product->id),
                        "tvc_n" => esc_html($product->get_title()),
                        "tvc_p" => esc_html($product->get_price()),
                        "tvc_c" => esc_html($categories),
                        "tvc_ss" => $product->is_in_stock(),
                        "tvc_st" => $product->get_stock_quantity(),
                        "tvc_tst" => $product->get_total_stock(),
                        "tvc_pd" => $this->cal_prod_discount($product->regular_price, $product->sale_price),
                        "tvc_rc" => $product->get_rating_count(),
                        "tvc_rs" => $product->get_average_rating(),
                        "tvc_po" => ++$_SESSION['t_fpcnt']
                    );
                    //else add product in homepage recent product json
                } else {
                    $homepage_json_rp[get_permalink($product->id)] = array(
                        "tvc_id" => esc_html($product->id),
                        "tvc_i" => esc_html($product->get_sku() ? $product->get_sku() : $product->id),
                        "tvc_n" => esc_html($product->get_title()),
                        "tvc_p" => esc_html($product->get_price()),
                        "tvc_c" => esc_html($categories),
                        "tvc_ss" => $product->is_in_stock(),
                        "tvc_st" => $product->get_stock_quantity(),
                        "tvc_tst" => $product->get_total_stock(),
                        "tvc_pd" => $this->cal_prod_discount($product->regular_price, $product->sale_price),
                        "tvc_rc" => $product->get_rating_count(),
                        "tvc_rs" => $product->get_average_rating(),
                        "tvc_po" => ++$_SESSION['t_npcnt']
                    );
                }
                }else{
                    if (!array_key_exists(get_permalink($product->get_id()), $homepage_json_fp)) {

                    $homepage_json_fp[get_permalink($product->get_id())] = array(
                        "tvc_id" => esc_html($product->get_id()),
                        "tvc_i" => esc_html($product->get_sku() ? $product->get_sku() : $product->get_id()),
                        "tvc_n" => esc_html($product->get_title()),
                        "tvc_p" => esc_html($product->get_regular_price()),
                        "tvc_c" => esc_html($categories),
                        "tvc_ss" => $product->is_in_stock(),
                        "tvc_st" => $product->get_stock_quantity(),
                        "tvc_tst" => $product->get_stock_quantity(),
                        "tvc_pd" => $this->cal_prod_discount($product->get_regular_price(), $product->get_sale_price()),
                        "tvc_rc" => $product->get_rating_count(),
                        "tvc_rs" => $product->get_average_rating(),
                        "tvc_po" => ++$_SESSION['t_fpcnt']
                    );
                    //else add product in homepage recent product json
                } else {
                    $homepage_json_rp[get_permalink($product->get_id())] = array(
                        "tvc_id" => esc_html($product->get_id()),
                        "tvc_i" => esc_html($product->get_sku() ? $product->get_sku() : $product->get_id()),
                        "tvc_n" => esc_html($product->get_title()),
                        "tvc_p" => esc_html($product->get_regular_price()),
                        "tvc_c" => esc_html($categories),
                        "tvc_ss" => $product->is_in_stock(),
                        "tvc_st" => $product->get_stock_quantity(),
                        "tvc_tst" => $product->get_stock_quantity(),
                        "tvc_pd" => $this->cal_prod_discount($product->get_regular_price(), $product->get_sale_price()),
                        "tvc_rc" => $product->get_rating_count(),
                        "tvc_rs" => $product->get_average_rating(),
                        "tvc_po" => ++$_SESSION['t_npcnt']
                    );
                }
                }
            } else {
                //else prod add in homepage recent json 
                if (version_compare($woocommerce->version, "2.7", "<")){
                     $homepage_json_rp[get_permalink($product->id)] = array(
                    "tvc_id" => esc_html($product->id),
                    "tvc_i" => esc_html($product->get_sku() ? $product->get_sku() : $product->id),
                    "tvc_n" => esc_html($product->get_title()),
                    "tvc_p" => esc_html($product->get_price()),
                    "tvc_c" => esc_html($categories),
                    "tvc_ss" => $product->is_in_stock(),
                    "tvc_st" => $product->get_stock_quantity(),
                    "tvc_pd" => $this->cal_prod_discount($product->regular_price, $product->sale_price),
                    "tvc_tst" => $product->get_total_stock(),
                    "tvc_rc" => $product->get_rating_count(),
                    "tvc_rs" => $product->get_average_rating(),
                    "tvc_po" => ++$_SESSION['t_npcnt']
                );
                 }else{
                   $homepage_json_rp[get_permalink($product->get_id())] = array(
                    "tvc_id" => esc_html($product->get_id()),
                    "tvc_i" => esc_html($product->get_sku() ? $product->get_sku() : $product->get_id()),
                    "tvc_n" => esc_html($product->get_title()),
                    "tvc_p" => esc_html($product->get_regular_price()),
                    "tvc_c" => esc_html($categories),
                    "tvc_ss" => $product->is_in_stock(),
                    "tvc_st" => $product->get_stock_quantity(),
                    "tvc_pd" => $this->cal_prod_discount($product->get_regular_price(), $product->get_sale_price()),
                    "tvc_tst" => $product->get_stock_quantity(),
                    "tvc_rc" => $product->get_rating_count(),
                    "tvc_rs" => $product->get_average_rating(),
                    "tvc_po" => ++$_SESSION['t_npcnt']
                );
                }    
            }
        }
        //if product page then related product page array
        else if (is_product()) {
            if (!is_array($prodpage_json_relProd) && !is_array($prodpage_json_ATC_link)) {
                $prodpage_json_relProd = array();
                $prodpage_json_ATC_link = array();
            }
            // ATC link Array
            if (version_compare($woocommerce->version, "2.7", "<")){
                $prodpage_json_ATC_link[$product->add_to_cart_url()] = array("tvc_u" => get_permalink($product->id));

            $prodpage_json_relProd[get_permalink($product->id)] = array(
                "tvc_id" => esc_html($product->id),
                "tvc_i" => esc_html($product->get_sku() ? $product->get_sku() : $product->id),
                "tvc_n" => esc_html($product->get_title()),
                "tvc_p" => esc_html($product->get_price()),
                "tvc_c" => esc_html($categories),
                "tvc_ss" => $product->is_in_stock(),
                "tvc_st" => $product->get_stock_quantity(),
                "tvc_pd" => $this->cal_prod_discount($product->regular_price, $product->sale_price),
                "tvc_tst" => $product->get_total_stock(),
                "tvc_rc" => $product->get_rating_count(),
                "tvc_rs" => $product->get_average_rating(),
                "tvc_po" => ++$_SESSION['t_npcnt']
            );
            }else{
                $prodpage_json_ATC_link[$product->add_to_cart_url()] = array("tvc_u" => get_permalink($product->get_id()));

            $prodpage_json_relProd[get_permalink($product->get_id())] = array(
                "tvc_id" => esc_html($product->get_id()),
                "tvc_i" => esc_html($product->get_sku() ? $product->get_sku() : $product->get_id()),
                "tvc_n" => esc_html($product->get_title()),
                "tvc_p" => esc_html($product->get_regular_price()),
                "tvc_c" => esc_html($categories),
                "tvc_ss" => $product->is_in_stock(),
                "tvc_st" => $product->get_stock_quantity(),
                "tvc_pd" => $this->cal_prod_discount($product->get_regular_price(), $product->get_sale_price()),
                "tvc_tst" => $product->get_stock_quantity(),
                "tvc_rc" => $product->get_rating_count(),
                "tvc_rs" => $product->get_average_rating(),
                "tvc_po" => ++$_SESSION['t_npcnt']
            );
            }   
        }
        //category page, search page and shop page json
        else if (is_product_category() || is_search() || is_shop()) {
            if (!is_array($catpage_json) && !is_array($catpage_json_ATC_link)) {
                $catpage_json = array();
                $catpage_json_ATC_link = array();
            }
            //cat page ATC array
            if (version_compare($woocommerce->version, "2.7", "<")){
                $catpage_json_ATC_link[$product->add_to_cart_url()] = array("tvc_u" => get_permalink($product->id));

            $catpage_json[get_permalink($product->id)] = array(
                "tvc_id" => esc_html($product->id),
                "tvc_i" => esc_html($product->get_sku() ? $product->get_sku() : $product->id),
                "tvc_n" => esc_html($product->get_title()),
                "tvc_p" => esc_html($product->get_price()),
                "tvc_c" => esc_html($categories),
                "tvc_ss" => $product->is_in_stock(),
                "tvc_st" => $product->get_stock_quantity(),
                "tvc_pd" => $this->cal_prod_discount($product->regular_price, $product->sale_price),
                "tvc_tst" => $product->get_total_stock(),
                "tvc_rc" => $product->get_rating_count(),
                "tvc_rs" => $product->get_average_rating(),
                "tvc_po" => ++$_SESSION['t_npcnt']
            );
            }else{
                $catpage_json_ATC_link[$product->add_to_cart_url()] = array("tvc_u" => get_permalink($product->get_id()));

            $catpage_json[get_permalink($product->get_id())] = array(
                "tvc_id" => esc_html($product->get_id()),
                "tvc_i" => esc_html($product->get_sku() ? $product->get_sku() : $product->get_id()),
                "tvc_n" => esc_html($product->get_title()),
                "tvc_p" => esc_html($product->get_regular_price()),
                "tvc_c" => esc_html($categories),
                "tvc_ss" => $product->is_in_stock(),
                "tvc_st" => $product->get_stock_quantity(),
                "tvc_pd" => $this->cal_prod_discount($product->get_regular_price(), $product->get_sale_price()),
                "tvc_tst" => $product->get_stock_quantity(),
                "tvc_rc" => $product->get_rating_count(),
                "tvc_rs" => $product->get_average_rating(),
                "tvc_po" => ++$_SESSION['t_npcnt']
            );
            }
            
        }
    }

    /**
     * Enhanced E-commerce tracking for product impressions,clicks on Home pages
     *
     * @access public
     * @return void
     */
    function t_products_impre_clicks() {
        if ($this->disable_tracking($this->ga_eeT)) {
            return;
        }
        //get impression threshold
        $impression_threshold = $this->ga_imTh;

        //Product impression on Home Page
        global $homepage_json_fp, $homepage_json_ATC_link, $homepage_json_rp, $prodpage_json_relProd, $catpage_json, $prodpage_json_ATC_link, $catpage_json_ATC_link;
        //home page json for featured products and recent product sections
        //check if php array is empty
        if (empty($homepage_json_ATC_link)) {
            $homepage_json_ATC_link = array(); //define empty array so if empty then in json will be []
        }
        if (empty($homepage_json_fp)) {
            $homepage_json_fp = array(); //define empty array so if empty then in json will be []
        }
        if (empty($homepage_json_rp)) { //home page recent product array
            $homepage_json_rp = array();
        }
        if (empty($prodpage_json_relProd)) { //prod page related section array
            $prodpage_json_relProd = array();
        }
        if (empty($prodpage_json_ATC_link)) {
            $prodpage_json_ATC_link = array(); //prod page ATC link json
        }
        if (empty($catpage_json)) { //category page array
            $catpage_json = array();
        }
        if (empty($catpage_json_ATC_link)) { //category page array
            $catpage_json_ATC_link = array();
        }
        //home page json
        $this->wc_version_compare("tvc_h_a=" . json_encode($homepage_json_ATC_link) . ";");
        $this->wc_version_compare("tvc_fp=" . json_encode($homepage_json_fp) . ";");
        $this->wc_version_compare("tvc_rcp=" . json_encode($homepage_json_rp) . ";");
        //product page json
        $this->wc_version_compare("tvc_rdp=" . json_encode($prodpage_json_relProd) . ";");
        $this->wc_version_compare("tvc_p_a=" . json_encode($prodpage_json_ATC_link) . ";");
        //category page, search page and shop page json
        $this->wc_version_compare("tvc_pgc=" . json_encode($catpage_json) . ";");
        $this->wc_version_compare("tvc_c_a=" . json_encode($catpage_json_ATC_link) . ";");

        $t_products_actions_js = '
                //Set Impression Threshold
                tvc_thr =' . esc_js($impression_threshold) . ';';

        if (is_home() || is_front_page()) {
            $t_products_actions_js .='
               //call featured product impression
                if(typeof(hmpg_impressions_FP)!=="undefined" && typeof(hmpg_impressions_FP) === "function"){
                    hmpg_impressions_FP();
        }else{
                    t_hmpgImprFP_call=true; 
                }
                //call recent product impression
                if(typeof(hmpg_impressions_RP)!=="undefined" && typeof(hmpg_impressions_RP) === "function"){
                    hmpg_impressions_RP();
        }else{
                    t_hmpgImprRP_call=true; 
                }
                //to measure product click on home page
                if(typeof(t_products_clicks)!=="undefined" && typeof(t_products_clicks) === "function"){
                    t_products_clicks(tvc_fp,"fp","Featured Products"); //json name , action name , list name
        }else{
                    t_hmpgClick_call=true;
        }              
              
                //to measure product ATC on home page
                if(typeof(t_products_ATC)!=="undefined" && typeof(t_products_ATC) === "function"){
                    t_products_ATC(tvc_h_a,tvc_fp);
        }else{
                    t_hmpgATC_call=true;
        } 
                
                ';
        }else if (is_search()) {
            $t_products_actions_js .='
                //to measure product impression on Search page
                 if(typeof(t_products_impressions)!=="undefined" && typeof(t_products_impressions) === "function"){
                    t_products_impressions(tvc_pgc,"srch","Search Results");
        }else{
                    t_spImpr_call=true; 
                }                
                 //to measure product click on Search page
                if(typeof(t_products_clicks)!=="undefined" && typeof(t_products_clicks) === "function"){
                    t_products_clicks(tvc_pgc,"srch","Search Results"); //json name , action name , list name
        }else{
                    t_srchpClick_call=true;
        }
                     
        ';
        }else if (is_product()) {
            //product page releted products
            $t_products_actions_js .='
                 //to measure related product impression on product page
                 if(typeof(t_products_impressions)!=="undefined" && typeof(t_products_impressions) === "function"){
                    t_products_impressions(tvc_rdp,"rdp","Related Products");
        }else{
                    t_ppImprRDP_call=true; 
                }                
                 //to measure product click on product page
                if(typeof(t_products_clicks)!=="undefined" && typeof(t_products_clicks) === "function"){
                    t_products_clicks(tvc_rdp,"rdp","Related Products"); //json name , action name , list name
        }else{
                    t_ppClickRDP_call=true;
        }
                 //to measure product ATC on product page (RDP)
                if(typeof(t_products_ATC)!=="undefined" && typeof(t_products_ATC) === "function"){
                    t_products_ATC(tvc_p_a,tvc_rdp);
        }else{
                    t_ppATCrdp_call=true;
        } 
                ';
        } else if (is_product_category()) {
            $t_products_actions_js .='
                //to measure product impression on Category page
                 if(typeof(t_products_impressions)!=="undefined" && typeof(t_products_impressions) === "function"){
                    t_products_impressions(tvc_pgc,"cp","Category Page");
        }else{
                    t_cpImpr_call=true; 
                }                
                 //to measure product click on Category page
                if(typeof(t_products_clicks)!=="undefined" && typeof(t_products_clicks) === "function"){
                    t_products_clicks(tvc_pgc,"cp","Category Page"); //json name , action name , list name
        }else{
                    t_cpClick_call=true;
        }
               
               ';
        } 
        else if (is_shop()) {
            $t_products_actions_js .='
               //to measure product impression on shop page
                 if(typeof(t_products_impressions)!=="undefined" && typeof(t_products_impressions) === "function"){
                    t_products_impressions(tvc_pgc,"sp","Shop Page");
        }else{
                    t_spImpr_call=true; 
                }                
                 //to measure product click on shop page
                if(typeof(t_products_clicks)!=="undefined" && typeof(t_products_clicks) === "function"){
                    t_products_clicks(tvc_pgc,"sp","Shop Page"); //json name , action name , list name
        }else{
                    t_spClick_call=true;
        }
                     
        ';
        } 
        //common ATC link for Category page , Shop Page and Search Page
        if (is_product_category() || is_shop() || is_search()) {
            $t_products_actions_js .='
                  //to measure product ATC on CP,SP,SrchPage
                if(typeof(t_products_ATC)!=="undefined" && typeof(t_products_ATC) === "function"){
                    t_products_ATC(tvc_c_a,tvc_pgc);
        }else{
                    t_commonATC_call=true;
        } 
                    ';
        }

        //on home page, product page , category page
        if (is_home() || is_front_page() || is_product() || is_product_category() || is_search() || is_shop()) {
            $this->wc_version_compare($t_products_actions_js);
        }
    }

    /**
     * Enhanced E-commerce tracking for remove from cart
     *
     * @access public
     * @return void
     */
    public function remove_cart_tracking() {
        if ($this->disable_tracking($this->ga_eeT)) {
            return;
        }
        global $woocommerce;
        $cartpage_prod_array_main = array();
        //check if product is variable product and product has child products
        foreach ($woocommerce->cart->cart_contents as $key => $item) {
            $prod_meta = wc_get_product($item["product_id"]);
            //get remove from cart link           
           if (version_compare($woocommerce->version, "3.3", "<")) {
                    $cart_remove_link=html_entity_decode($woocommerce->cart->get_remove_url($key)); 
            } else {
                    $cart_remove_link=html_entity_decode(wc_get_cart_remove_url($key)); 
            }   
            $category = get_the_terms($item["product_id"], "product_cat");
            $categories = "";
            if ($category) {
                foreach ($category as $term) {
                    $categories.=$term->name . ",";
                }
            }
            //remove last comma(,) if multiple categories are there
            $categories = rtrim($categories, ",");
            //for variable product
            if ($prod_meta->is_type('variable') && $prod_meta->has_child()) {
                //echo $item["variation_id"];
                $t_var_metadata = wc_get_product($item["variation_id"]);
                //echo "<pre>" . print_r($t_var_metadata, TRUE) . "</pre>";
                $t_var_sku = $t_var_metadata->get_sku();
                $t_var_prc = $t_var_metadata->get_regular_price(); //get price
                $t_var_sprc = $t_var_metadata->get_sale_price(); //get sale price
                $t_var_rprc = $t_var_metadata->get_regular_price(); //get regular price
                //get var product weight
                $t_var_wt = '';
                if ($t_var_metadata->get_weight() == "") {
                    $t_var_wt = $t_var_metadata->get_weight() . ' ' . esc_attr(get_option('woocommerce_weight_unit')); //cond. here bcoz of weight unit
                }
                if (version_compare($woocommerce->version, "2.7", "<")){
                    $cartpage_prod_array_main[$cart_remove_link] = array(
                    "tvc_id" => esc_html($prod_meta->id),
                    "tvc_i" => esc_html($t_var_sku),
                    "tvc_n" => esc_html($prod_meta->get_title()),
                    "tvc_p" => esc_html($t_var_prc),
                    "tvc_c" => esc_html($categories),
                    "tvc_q" => $woocommerce->cart->cart_contents[$key]["quantity"],
                    "tvc_sp" => $t_var_sprc, //get sale price
                    "tvc_rp" => $t_var_rprc, //get regular price
                    "tvc_vat" => $t_var_metadata->get_variation_attributes(), //get avialable attr
                    "tvc_wt" => $t_var_wt,
                    "tvc_pd" => $this->cal_prod_discount($t_var_rprc, $t_var_sprc),
                    "tvc_di" => $t_var_metadata->get_dimensions(), //dimensions
                    "tvc_ss" => $t_var_metadata->is_in_stock(),
                    "tvc_st" => $t_var_metadata->get_stock_quantity(),
                    "tvc_tst" => $t_var_metadata->get_total_stock(),
                );
                }else{
                    $cartpage_prod_array_main[$cart_remove_link] = array(
                    "tvc_id" => esc_html($prod_meta->get_id()),
                    "tvc_i" => esc_html($t_var_sku),
                    "tvc_n" => esc_html($prod_meta->get_title()),
                    "tvc_p" => esc_html($t_var_prc),
                    "tvc_c" => esc_html($categories),
                    "tvc_q" => $woocommerce->cart->cart_contents[$key]["quantity"],
                    "tvc_sp" => $t_var_sprc, //get sale price
                    "tvc_rp" => $t_var_rprc, //get regular price
                    "tvc_vat" => $t_var_metadata->get_variation_attributes(), //get avialable attr
                    "tvc_wt" => $t_var_wt,
                    "tvc_pd" => $this->cal_prod_discount($t_var_rprc, $t_var_sprc),
                    //"tvc_di" => $t_var_metadata->get_dimensions(), //dimensions
                    "tvc_ss" => $t_var_metadata->is_in_stock(),
                    "tvc_st" => $t_var_metadata->get_stock_quantity(),
                    "tvc_tst" => $t_var_metadata->get_stock_quantity(),
                );
                }
                
            } else if ($prod_meta->is_type('simple')) {
                //get product weight
                $t_wt = '';
                if ($prod_meta->has_weight()) {
                    $t_wt = $prod_meta->get_weight() . ' ' . esc_attr(get_option('woocommerce_weight_unit')); //cond. here bcoz of weight unit
                }
                if (version_compare($woocommerce->version, "2.7", "<")){
                    $cartpage_prod_array_main[$cart_remove_link] = array(
                    "tvc_id" => esc_html($prod_meta->id),
                    "tvc_i" => esc_html($prod_meta->get_sku() ? $prod_meta->get_sku() : $prod_meta->id),
                    "tvc_n" => esc_html($prod_meta->get_title()),
                    "tvc_p" => esc_html($prod_meta->get_price()),
                    "tvc_pd" => $this->cal_prod_discount($prod_meta->regular_price, $prod_meta->sale_price),
                    "tvc_c" => esc_html($categories),
                    "tvc_q" => $woocommerce->cart->cart_contents[$key]["quantity"],
                    "tvc_wt" => $t_wt,
                    "tvc_di" => $prod_meta->get_dimensions(), //dimensions                     
                    "tvc_ss" => $prod_meta->is_in_stock(),
                    "tvc_st" => $prod_meta->get_stock_quantity(),
                    "tvc_tst" => $prod_meta->get_total_stock(),
                    "tvc_rc" => $prod_meta->get_rating_count(),
                    "tvc_rs" => $prod_meta->get_average_rating()
                );
                }else{
                    $cartpage_prod_array_main[$cart_remove_link] = array(
                    "tvc_id" => esc_html($prod_meta->get_id()),
                    "tvc_i" => esc_html($prod_meta->get_sku() ? $prod_meta->get_sku() : $prod_meta->get_id()),
                    "tvc_n" => esc_html($prod_meta->get_title()),
                    "tvc_p" => esc_html($prod_meta->get_regular_price()),
                    "tvc_pd" => $this->cal_prod_discount($prod_meta->get_regular_price(), $prod_meta->get_sale_price()),
                    "tvc_c" => esc_html($categories),
                    "tvc_q" => $woocommerce->cart->cart_contents[$key]["quantity"],
                    "tvc_wt" => $t_wt,
                    //"tvc_di" => $prod_meta->get_dimensions(), //dimensions                     
                    "tvc_ss" => $prod_meta->is_in_stock(),
                    "tvc_st" => $prod_meta->get_stock_quantity(),
                    "tvc_tst" => $prod_meta->get_stock_quantity(),
                    "tvc_rc" => $prod_meta->get_rating_count(),
                    "tvc_rs" => $prod_meta->get_average_rating()
                );
                }
                
            }
        }

        //Cart Page item Array to Json
        $this->wc_version_compare("tvc_cc=" . json_encode($cartpage_prod_array_main) . ";");

        $remove_from_cart_js = '
            if(typeof(remove_from_cart)!=="undefined" && typeof(remove_from_cart) === "function"){
        remove_from_cart();
            }else{
                t_remove_call=true;
            }';
        //check woocommerce version
        $this->wc_version_compare($remove_from_cart_js);
    }

    /**
     * Enhanced E-commerce tracking checkout step 1 and step 2
     *
     * @access public
     * @return void
     */
    public function checkout_step_1_2_tracking() {
        if ($this->disable_tracking($this->ga_eeT)) {
            return;
        }
        //call fn to make checkout page json
        $this->get_ordered_items();
        //if logged in and first name is filled - Guest Check out
        if (is_user_logged_in()) {
            $step2_onFocus = ' 
            if(typeof(checkout_step1)!=="undefined" && typeof(checkout_step1) === "function"){
                checkout_step1();
            }else{
                t_chkout_S1_call=true; 
            }
            if(typeof(checkout_step2)!=="undefined" && typeof(checkout_step2) === "function"){
                checkout_step2();
            }else{
                t_chkout_S2_call=true;
            }
            ';
        } else {
            $step2_onFocus = '
                if(typeof(checkout_events)!=="undefined" && typeof(checkout_events) === "function"){
                                    checkout_events();
                                }else{
                                    t_chkout_steps_event=true;
                                }

            
            ';
        }
        //check woocommerce version and add code
        $this->wc_version_compare($step2_onFocus);
    }

    /**
     * Enhanced E-commerce tracking checkout step 3
     *
     * @access public
     * @return void
     */
    public function checkout_step_3_tracking() {
        if ($this->disable_tracking($this->ga_eeT)) {
            return;
        }
        $code_step_3 = '
        if(typeof(checkout_step3)!=="undefined" && typeof(checkout_step3) === "function"){
                                    checkout_step3();
                                }else{
                                    t_chkout_S3_call=true;
                                }
        ';
        $inline_js = $code_step_3;
        //check woocommerce version and add code
        $this->wc_version_compare($inline_js);
    }

    /**
     * Get oredered Items for check out page.
     *
     * @access public
     * @return void
     */
    public function get_ordered_items() {
        global $woocommerce;
        //get all items added into the cart
        $i = 0;
        foreach ($woocommerce->cart->cart_contents as $item) {
            $p = wc_get_product($item["product_id"]);

            $category = get_the_terms($item["product_id"], "product_cat");
            $categories = "";
            if ($category) {
                foreach ($category as $term) {
                    $categories.=$term->name . ",";
                }
            }
            //remove last comma(,) if multiple categories are there
            $categories = rtrim($categories, ",");

            //for variable product
            if ($p->is_type('variable') && $p->has_child()) {
                $t_var_metadata = wc_get_product($item["variation_id"]);
                $t_var_sku = $t_var_metadata->get_sku();
                $t_var_prc = $t_var_metadata->get_regular_price(); //get price
                $t_var_sprc = $t_var_metadata->get_sale_price(); //get sale price
                $t_var_rprc = $t_var_metadata->get_regular_price(); //get regular price
                //get var product weight
                $t_var_wt = '';
                if ($t_var_metadata->get_weight() == "") {
                    $t_var_wt = $t_var_metadata->get_weight() . ' ' . esc_attr(get_option('woocommerce_weight_unit')); //cond. here bcoz of weight unit
                }
                if (version_compare($woocommerce->version, "2.7", "<")){
                    $chkout_json[$i] = array(
                    "tvc_id" => esc_html($p->id),
                    "tvc_i" => esc_html($p->id),
                    "tvc_n" => esc_html($p->get_title()),
                    "tvc_p" => esc_html($t_var_prc),
                    "tvc_c" => esc_html($categories),
                    "tvc_q" => esc_js($item["quantity"]),
                    "tvc_sp" => $t_var_sprc, //get sale price
                    "tvc_rp" => $t_var_rprc, //get regular price
                    "tvc_pd" => $this->cal_prod_discount($t_var_rprc, $t_var_sprc),
                    "tvc_vat" => $t_var_metadata->get_variation_attributes(), //get avialable attr
                    "tvc_wt" => $t_var_wt,
                    "tvc_di" => $t_var_metadata->get_dimensions(), //dimensions
                    "tvc_ss" => $t_var_metadata->is_in_stock(),
                    "tvc_st" => $t_var_metadata->get_stock_quantity(),
                    "tvc_tst" => $t_var_metadata->get_total_stock(),
                );
                }else{
                    $chkout_json[$i] = array(
                    "tvc_id" => esc_html($p->get_id()),
                    "tvc_i" => esc_html($p->get_id()),
                    "tvc_n" => esc_html($p->get_title()),
                    "tvc_p" => esc_html($t_var_prc),
                    "tvc_c" => esc_html($categories),
                    "tvc_q" => esc_js($item["quantity"]),
                    "tvc_sp" => $t_var_sprc, //get sale price
                    "tvc_rp" => $t_var_rprc, //get regular price
                    "tvc_pd" => $this->cal_prod_discount($t_var_rprc, $t_var_sprc),
                    "tvc_vat" => $t_var_metadata->get_variation_attributes(), //get avialable attr
                    "tvc_wt" => $t_var_wt,
                    //"tvc_di" => $t_var_metadata->get_dimensions(), //dimensions
                    "tvc_ss" => $t_var_metadata->is_in_stock(),
                    "tvc_st" => $t_var_metadata->get_stock_quantity(),
                    "tvc_tst" => $t_var_metadata->get_stock_quantity(),
                );
                }
                
            } else if ($p->is_type('simple')) {
                //get product weight
                $t_wt = '';
                if ($p->has_weight()) {
                    $t_wt = $p->get_weight() . ' ' . esc_attr(get_option('woocommerce_weight_unit')); //cond. here bcoz of weight unit
                }
                if (version_compare($woocommerce->version, "2.7", "<")){
                   $chkout_json[$i] = array(
                    "tvc_i" => esc_js($p->get_sku() ? $p->get_sku() : $p->id),
                    "tvc_n" => esc_js($p->get_title()),
                    "tvc_p" => esc_js($p->get_price()),
                    "tvc_c" => $categories,
                    "tvc_q" => esc_js($item["quantity"]),
                    "tvc_isf" => $p->is_featured(),
                    "tvc_wt" => $t_wt, //weight
                    "tvc_di" => $p->get_dimensions(), //dimensions
                    "tvc_ss" => $p->is_in_stock(),
                    "tvc_pd" => $this->cal_prod_discount($p->regular_price, $p->sale_price),
                    "tvc_st" => $p->get_stock_quantity(),
                    "tvc_tst" => $p->get_total_stock(),
                    "tvc_rc" => $p->get_rating_count(),
                    "tvc_rs" => $p->get_average_rating()
                );
                }else{
                    $chkout_json[$i] = array(
                    "tvc_i" => esc_js($p->get_sku() ? $p->get_sku() : $p->get_id()),
                    "tvc_n" => esc_js($p->get_title()),
                    "tvc_p" => esc_js($p->get_regular_price()),
                    "tvc_c" => $categories,
                    "tvc_q" => esc_js($item["quantity"]),
                    "tvc_isf" => $p->is_featured(),
                    "tvc_wt" => $t_wt, //weight
                    //"tvc_di" => $p->get_dimensions(), //dimensions
                    "tvc_ss" => $p->is_in_stock(),
                    "tvc_pd" => $this->cal_prod_discount($p->get_regular_price(), $p->get_sale_price()),
                    "tvc_st" => $p->get_stock_quantity(),
                    "tvc_tst" => $p->get_stock_quantity(),
                    "tvc_rc" => $p->get_rating_count(),
                    "tvc_rs" => $p->get_average_rating()
                );
                }
                
            }
            $i++;
        }
        //make product data json on check out page
        $this->wc_version_compare("tvc_ch=" . json_encode($chkout_json) . ";");
    }

    /**
     * Calculate Product discount
     *
     * @access private
     * @param mixed $type
     * @return bool
     */
    function cal_prod_discount($t_rprc, $t_sprc) {  //older $product Object
        $t_dis = '0';
        //calculate discount
        if (!empty($t_rprc) && !empty($t_sprc)) {
            $t_dis = sprintf("%.2f", (( $t_rprc - $t_sprc ) / $t_rprc ) * 100);
        }
        return $t_dis;
    }

    /**
     * Check if tracking is disabled
     *
     * @access private
     * @param mixed $type
     * @return bool
     */
    private function disable_tracking($type) {
        if (is_admin() || current_user_can("manage_options") || (!$this->ga_id ) || "no" == $type) {
            return true;
        }
    }

    /**
     * woocommerce version compare
     *
     * @access public
     * @return void
     */
    function wc_version_compare($codeSnippet) {
        global $woocommerce;
        if (version_compare($woocommerce->version, "2.1", ">=")) {
            wc_enqueue_js($codeSnippet);
        } else {
            $woocommerce->add_inline_js($codeSnippet);
        }
    }

    /**
     * Form Field Analysis in footer
     *
     * @access public
     * @return void
     */
    function form_field_tracking() {
        $form_field_js = '
            if(typeof(form_field_tracking)!=="undefined" && typeof(form_field_tracking) === "function"){
                                    form_field_tracking();
                                }else{
                                    t_form_call=true;
                                }
        ';
        //check if option is enabled by user or not
        if ($this->ga_FF)
            $this->wc_version_compare($form_field_js);
    }

    /**
     * Adding internal promotion code
     *
     * @access public
     * @return void
     */
    function internal_promotion() {
        //check if option is enabled by user or not
        if (!$this->ga_InPromo)
            return;
        //get user defined internal promotion data
        $t_internal_promo_data = $this->ga_InPromoData;
        if (!empty($t_internal_promo_data)) {            
        $t_internal_promo_data_pipe = explode("\r\n", $t_internal_promo_data);
        $t_internal_promo_data = array();

        for ($i = 0; $i < sizeof($t_internal_promo_data_pipe); $i++) {
            $temp_Arr = explode(',', $t_internal_promo_data_pipe[$i]);
                $t_internal_promo_data_temp = array($temp_Arr[0] => array(
                    "tvc_i" => $temp_Arr[1],
                    "tvc_n" => $temp_Arr[2],
                    "tvc_c" => $temp_Arr[3],
                    "tvc_po" => $temp_Arr[4]));
            array_push($t_internal_promo_data, $t_internal_promo_data_temp);
        }
        if (empty($t_internal_promo_data)) {
            $t_internal_promo_data = array();
        }
        //convert array into json and add into footer
        $this->wc_version_compare("tvc_ip=" . json_encode($t_internal_promo_data) . ";");
        $internal_promo_js = '
            if(typeof(t_internal_promotion)!=="undefined" && typeof(t_internal_promotion) === "function"){
                                    t_internal_promotion();
                                }else{
                                    t_inter_call=true;
                                }
        ';

        $this->wc_version_compare($internal_promo_js);
    }
    }

    /**
     * Sending email to remote server
     *
     * @access public
     * @return void
     */
    public function send_email_to_tatvic($email, $status,$token) {
        $url = "http://dev.tatvic.com/leadgen/woocommerce-plugin/store_email/actionable_ga/";
        //set POST variables
        $fields = array(
            "email" => urlencode($email),
            "domain_name" => urlencode(get_site_url()),
            "status" => urlencode($status),
            "tvc_tkn" =>$token
        );
        wp_remote_post($url, array(
            "method" => "POST",
            "timeout" => 1,
            "httpversion" => "1.0",
            "blocking" => false,
            "headers" => array(),
            "body" => $fields
                )
        );
    }

}?>