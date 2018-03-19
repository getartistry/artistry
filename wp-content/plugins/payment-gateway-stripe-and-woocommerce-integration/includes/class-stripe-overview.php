<?php
if (!defined('ABSPATH')) {
    exit;
}
class EH_Stripe_Overview
{
    function __construct() {
        
            add_action('admin_menu', array($this,'eh_stripe_overview_menu_add'));        

    }
    public function eh_stripe_overview_menu_add()
    {

        add_submenu_page('woocommerce',__( 'Stripe Overview','eh-stripe-gateway' ),__( 'Stripe Overview','eh-stripe-gateway' ), 'manage_woocommerce', 'eh-stripe-overview', array(
            $this,
            'eh_stripe_template_display'
        ));
        add_action( 'admin_head', array( $this, 'menu_order_count' ) );
        add_action('admin_init', array(
            $this,
            'eh_stripe_register_plugin_styles_scripts'
        ));
        add_action('wp_default_scripts', function($scripts)
        {
            if (!empty($scripts->registered['jquery'])) {
                $jquery_dependencies                 = $scripts->registered['jquery']->deps;
                $scripts->registered['jquery']->deps = array_diff($jquery_dependencies, array(
                    'jquery-migrate'
                ));
            }
        });
    }
    public function menu_order_count() 
    {
        global $submenu;

        if ( isset( $submenu['woocommerce'] ) ) {
          // Remove 'WooCommerce' sub menu item
          unset( $submenu['woocommerce'][0] );

          // Add count if user has access
          if ( apply_filters( 'woocommerce_include_processing_order_count_in_menu', true ) && current_user_can( 'manage_woocommerce' ) && ( $order_count = $this->eh_spg_uncaptured_count() ) ) {
            foreach ( $submenu['woocommerce'] as $key => $menu_item ) {
              if ( 0 === strpos( $menu_item[0], _x( 'Stripe Overview', 'Admin menu name', 'woocommerce' ) ) ) {
                $submenu['woocommerce'][ $key ][0] .= ' <span class="awaiting-mod update-plugins count-' . $order_count . '"><span class="processing-count">' . number_format_i18n( $order_count ) . '</span></span>';
                break;
              }
            }
          }
        }
    }
    public function eh_spg_uncaptured_count()
    {
        $count=0;
        $id=  eh_stripe_overview_get_order_ids();
        for($i=0;$i<count($id);$i++)
        {
            $data=get_post_meta( $id[$i] ,'_eh_stripe_payment_charge',true);
            if(isset($data['captured']) && $data['captured']=='Uncaptured')
            {
                $count++;
            }
        }
        return $count;
    }
    public function eh_stripe_register_plugin_styles_scripts()
    {
        $page = (isset($_GET['page'])) ? esc_attr($_GET['page']) : false;
        if ('eh-stripe-overview' != $page)
            return;
        wp_nonce_field('ajax-eh-spg-nonce', '_ajax_eh_spg_nonce');
        global $woocommerce;
        $woocommerce_version = function_exists('WC') ? WC()->version : $woocommerce->version;
        wp_enqueue_style('woocommerce_admin_styles', $woocommerce->plugin_url() . '/assets/css/admin.css', array(), $woocommerce_version);
        wp_register_style('eh-boot-style', EH_STRIPE_MAIN_URL_PATH.'assets/css/boot.css');
        wp_enqueue_style('eh-boot-style');
        wp_register_script('eh-datepicker-jquery',EH_STRIPE_MAIN_URL_PATH .'assets/js/datepicker.js');
        wp_enqueue_script('eh-datepicker-jquery');
        wp_register_style('eh-datepicker-style', EH_STRIPE_MAIN_URL_PATH.'assets/css/datepicker.css');
        wp_enqueue_style('eh-datepicker-style');
       
     
        wp_register_style('eh-daterangepicker-style', EH_STRIPE_MAIN_URL_PATH.'assets/css/daterangepicker.css');
        wp_enqueue_style('eh-daterangepicker-style');
        wp_register_style('eh-xcharts.min-style', EH_STRIPE_MAIN_URL_PATH.'assets/css/xcharts.min.css');
        wp_enqueue_style('eh-xcharts.min-style');
        wp_register_style('eh-style-style', EH_STRIPE_MAIN_URL_PATH.'assets/css/style.css');
        wp_enqueue_style('eh-style-style');
        
        //xchart includes
        wp_register_script('eh-xhart-lib-script', '//cdnjs.cloudflare.com/ajax/libs/d3/2.10.0/d3.v2.js');
        wp_enqueue_script('eh-xhart-lib-script');
        wp_register_script('eh-xcharts.min', EH_STRIPE_MAIN_URL_PATH .'assets/js/xcharts.min.js');
        wp_enqueue_script('eh-xcharts.min');
        //date picker
        wp_register_script('eh-xcharts.min', EH_STRIPE_MAIN_URL_PATH .'assets/js/xcharts.min.js');
        wp_enqueue_script('eh-xcharts.min');
        wp_register_script('eh-sugar.min', EH_STRIPE_MAIN_URL_PATH .'assets/js/sugar.min.js');
        wp_enqueue_script('eh-sugar.min');
        wp_register_script('eh-daterangepicker', EH_STRIPE_MAIN_URL_PATH .'assets/js/daterangepicker.js');
        wp_enqueue_script('eh-daterangepicker');
        
        // our chart init file
        
        wp_register_script('eh-custom-chart', EH_STRIPE_MAIN_URL_PATH .'assets/js/script.js');
        wp_enqueue_script('eh-custom-chart');
        wp_register_script('eh-custom', EH_STRIPE_MAIN_URL_PATH .'assets/js/eh-stripe-custom.js');
        wp_enqueue_script('eh-custom');
        
        wp_register_style('eh-alert-style', EH_STRIPE_MAIN_URL_PATH.'/assets/css/sweetalert2.css');
        wp_enqueue_style('eh-alert-style');
        wp_register_script('eh-alert-jquery', EH_STRIPE_MAIN_URL_PATH.'/assets/js/sweetalert2.min.js');
        wp_enqueue_script('eh-alert-jquery');
    } 
    public function eh_stripe_template_display()
    {
        include (EH_STRIPE_MAIN_PATH."templates/template-frontend-main.php");
    }
}
new EH_Stripe_Overview();