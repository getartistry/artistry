<?php

if( ! defined( 'ABSPATH' ) ) exit(); // Exit if accessed directly

class PA_admin_settings {
    
    public $pa_elements_keys = ['premium-banner', 'premium-blog','premium-carousel', 'premium-countdown','premium-counter','premium-dual-header','premium-fancytext','premium-image-separator','premium-maps','premium-modalbox','premium-person','premium-progressbar','premium-testimonials','premium-title','premium-videobox','premium-pricing-table','premium-button','premium-contactform', 'premium-image-button', 'premium-map-api', 'premium-map-disable-api'];
    
    private $pa_default_settings;
    
    private $pa_settings;
    
    private $pa_get_settings;

    public function __construct() {
        add_action( 'admin_menu', array( $this,'pa_admin_menu') );
        add_action('init', array( $this, 'pa_admin_page_scripts' ) );
        add_action( 'wp_ajax_pa_save_admin_addons_settings', array( $this, 'pa_save_settings_with_ajax' ) );
    }

    public function pa_admin_page_scripts () {
        wp_enqueue_style( 'pa_admin_icon', plugins_url( '/', __FILE__ ).'assets/pa-elements-font/css/pafont.css' );
        if( isset( $_GET['page'] ) && $_GET['page'] == 'pa-settings-page' ) {
        wp_enqueue_style( 'premium_addons_elementor-css', plugins_url( '/', __FILE__ ).'assets/admin.css' );
        wp_enqueue_style( 'premium_addons-sweetalert2-css', plugins_url( '/', __FILE__ ).'assets/js/sweetalert2/css/sweetalert2.min.css' );
        wp_enqueue_script('pa-addons-elementor-admin-js', plugins_url( '/' , __FILE__ ).'assets/admin.js' , array('jquery','jquery-ui-tabs'), '1.0' , true );
        wp_enqueue_script( 'premium_addons_sweet-js', plugins_url( '/', __FILE__ ).'assets/js/sweetalert2/js/core.js', array( 'jquery' ), '1.0', true );
			wp_enqueue_script( 'premium_addons_sweetalert2-js', plugins_url( '/', __FILE__ ).'assets/js/sweetalert2/js/sweetalert2.min.js', array( 'jquery', 'premium_addons_sweet-js' ), '1.0', true );
    }
    }

    public function pa_admin_menu() {
        add_menu_page( 'Premium Addons for Elementor', 'Premium Addons for Elementor', 'manage_options', 'pa-settings-page', array( $this , 'pa_admin_page' ), '' , 100  );
    }

    public function pa_admin_page(){
        $js_info = array(
			'ajaxurl' => admin_url( 'admin-ajax.php' )
		);
		wp_localize_script( 'pa-addons-elementor-admin-js', 'settings', $js_info );
       
	   $this->pa_default_settings = array_fill_keys( $this->pa_elements_keys, true );
       
	   $this->pa_get_settings = get_option( 'pa_save_settings', $this->pa_default_settings );
       
	   $pa_new_settings = array_diff_key( $this->pa_default_settings, $this->pa_get_settings );
       
	   if( ! empty( $pa_new_settings ) ) {
	   	$pa_updated_settings = array_merge( $this->pa_get_settings, $pa_new_settings );
	   	update_option( 'pa_save_settings', $pa_updated_settings );
	   }
	   $this->pa_get_settings = get_option( 'pa_save_settings', $this->pa_default_settings );
       
       
	?>
	<div class="wrap">
        <div class="response-wrap"></div>
        <form action="" method="POST" id="pa-settings" name="pa-settings">
            <div class="pa-header-wrapper">
                <div class="pa-title-left">
                    <h1 class="pa-title-main"><?php echo esc_html__('Premium Addons for Elementor', 'premium-addons-for-elementor'); ?></h1>
                    <h3 class="pa-title-sub"><?php echo esc_html__('Thank you for using Premium Addons for Elementor. This plugin has been developed by Leap13 and we hope you enjoy using it.', 'premium-addons-for-elementor'); ?></h3>
                </div>
                <div class="pa-title-right">
                    
                    <img class="pa-logo" src="<?php echo plugins_url('/',__FILE__) . 'images/premium-addons-logo.png';?>">
                </div>
            </div>
            <div class="pa-settings-tabs">
                <ul class="pa-settings-tabs-list">
                    <li><a class="pa-tab-list-item" href="#pa-about">About</a></li>
                    <li><a class="pa-tab-list-item" href="#pa-modules">Elements</a></li>
                    <li><a class="pa-tab-list-item" href="#pa-maps-api">Google Maps API</a></li>
                    <li><a class="pa-tab-list-item" href="#pa-system">System Info</a></li>
                </ul>
                <div id="pa-about" class="pa-settings-tab">
                        <div class="pa-row">
                            <div class="pa-col-half">
                                <div class="pa-about-panel">
                                    <div class="pa-icon-container">
                                        <i class="dashicons dashicons-info abt-icon-style"></i>
                                    </div>
                                    <div class="pa-text-container">
                                        <h4>What is Premium Addons?</h4>
                                        <p>Premium Addons for Elementor that extends Elementor Page Builder capabilities with 15 fully customizable elements that helps you build impressive websites with no coding required.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="pa-col-half">
                                <div class="pa-about-panel">
                                    <div class="pa-icon-container">
                                        <i class="dashicons dashicons-universal-access-alt abt-icon-style"></i>
                                    </div>
                                    <div class="pa-text-container">
                                        <h4>Documentation & FAQ</h4>
                                        <p>It’s highly recommended to check out documentation and FAQ before using this plugin. <a target="_blanl" href="http://premiumaddons.com/premium-addons-for-elementor-plugin-documentation/">Click Here </a> for more details.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <div class="pa-row">
                            <div class="pa-col-half">
                                <div class="pa-about-panel">
                                    <div class="pa-icon-container">
                                        <i class="dashicons dashicons-share abt-icon-style"></i>
                                    </div>
                                    <div class="pa-text-container">
                                        <h4>Need More Help?</h4>
                                        <p>Feel free to join us in our <a target="_blank" href="https://www.facebook.com/groups/2042193629353325/">Facebook Group</a> and our <a target="_blank" href="http://www.leap13.com/forums/forum/premium-addons-for-elementor-plugin-community-support/">Community Forums</a> if you need more help using the plugin.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="pa-col-half">
                                <div class="pa-about-panel">
                                    <div class="pa-icon-container">
                                        <i class="dashicons dashicons-download abt-icon-style"></i>
                                    </div>
                                    <div class="pa-text-container">
                                        <h4>Keep Updated</h4>
                                        <p>Join our Newsletter to get more info about our products updates. <a target="_blank" href="http://premiumaddons.com/premium-addons-elementor-newsletter/">Click Here</a> to Join Now.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <div id="pa-modules" class="pa-settings-tab">
                    <div>
                        <br>
                        <input type="checkbox" class="pa-checkbox" checked="checked">
                        <label>Enable/Disable All</label>
                    </div>
                    <table class="pa-elements-table">
                        <tbody>
                            <tr>
                                <th><?php echo esc_html__('Premium Banner', 'premium-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                        <input type="checkbox" id="premium-banner" name="premium-banner" <?php checked(1, $this->pa_get_settings['premium-banner'], true) ?>>
                                        <span class="slider round"></span>
                                </label>
                                </td>
                                <th><?php echo esc_html__('Premium Blog', 'premium-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-blog" name="premium-blog" <?php checked(1, $this->pa_get_settings['premium-blog'], true) ?>>
                                            <span class="slider round"></span>
                                    </label>
                                </td>
                            </tr>
                            
                            
                            <tr>
                                <th><?php echo esc_html__('Premium Carousel', 'premium-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-carousel" name="premium-carousel" <?php checked(1, $this->pa_get_settings['premium-carousel'], true) ?>>
                                            <span class="slider round"></span>
                                    </label>
                                </td>
                                <th><?php echo esc_html__('Premium Countdown', 'premium-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-countdown" name="premium-countdown" <?php checked(1, $this->pa_get_settings['premium-countdown'], true) ?>>
                                            <span class="slider round"></span>
                                    </label>
                                </td>
                            </tr>
                            
                            <tr>
                                <th><?php echo esc_html__('Premium Counter', 'premium-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-counter" name="premium-counter" <?php checked(1, $this->pa_get_settings['premium-counter'], true) ?>>
                                            <span class="slider round"></span>
                                    </label>
                                </td>
                                <th><?php echo esc_html__('Premium Dual Header', 'premium-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-dual-header" name="premium-dual-header" <?php checked(1, $this->pa_get_settings['premium-dual-header'], true) ?>>
                                            <span class="slider round"></span>
                                    </label>
                                </td>
                            </tr>
                            
                            
                            <tr>
                                <th><?php echo esc_html__('Premium Fancy Text', 'premium-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-fancytext" name="premium-fancytext" <?php checked(1, $this->pa_get_settings['premium-fancytext'], true) ?>>
                                            <span class="slider round"></span>
                                    </label>
                                </td>
                                <th><?php echo esc_html__('Premium Image Separator', 'premium-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-image-separator" name="premium-image-separator" <?php checked(1, $this->pa_get_settings['premium-image-separator'], true) ?>>
                                            <span class="slider round"></span>
                                    </label>
                                </td>
                            </tr>
                            
                            
                            <tr>
                                
                                <th><?php echo esc_html__('Premium Maps', 'premium-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-maps" name="premium-maps" <?php checked(1, $this->pa_get_settings['premium-maps'], true) ?>>
                                            <span class="slider round"></span>
                                    </label>
                                </td>
                                <th><?php echo esc_html__('Premium Modal Box', 'premium-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-modalbox" name="premium-modalbox" <?php checked(1, $this->pa_get_settings['premium-modalbox'], true) ?>>
                                            <span class="slider round"></span>
                                    </label>
                                </td>
                            </tr>
                            
                            <tr>    
                                <th><?php echo esc_html__('Premium Person', 'premium-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-person" name="premium-person" <?php checked(1, $this->pa_get_settings['premium-person'], true) ?>>
                                            <span class="slider round"></span>
                                    </label>
                                </td>
                                <th><?php echo esc_html__('Premium Progress Bar', 'premium-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-progressbar" name="premium-progressbar" <?php checked(1, $this->pa_get_settings['premium-progressbar'], true) ?>>
                                            <span class="slider round"></span>
                                    </label>
                                </td>
                            </tr>
                            </tr>
                            
                            <tr>
                                <th><?php echo esc_html__('Premium Testimonials', 'premium-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-testimonials" name="premium-testimonials" <?php checked(1, $this->pa_get_settings['premium-testimonials'], true) ?>>
                                            <span class="slider round"></span>
                                    </label>
                                </td>
                                <th><?php echo esc_html__('Premium Title', 'premium-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-title" name="premium-title" <?php checked(1, $this->pa_get_settings['premium-title'], true) ?>>
                                            <span class="slider round"></span>
                                    </label>
                                </td>
                            </tr>
                            
                            <tr>
                                <th><?php echo esc_html__('Premium Video Box', 'premium-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-videobox" name="premium-videobox" <?php checked(1, $this->pa_get_settings['premium-videobox'], true) ?>>
                                            <span class="slider round"></span>
                                        </label>
                                </td>
                                <th><?php echo esc_html__('Premium Pricing Table', 'premium-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-pricing-table" name="premium-pricing-table" <?php checked(1, $this->pa_get_settings['premium-pricing-table'], true) ?>>
                                            <span class="slider round"></span>
                                    </label>
                                </td>
                            </tr>
                            
                            <tr>
                                <th><?php echo esc_html__('Premium Button', 'premium-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-button" name="premium-button" <?php checked(1, $this->pa_get_settings['premium-button'], true) ?>>
                                            <span class="slider round"></span>
                                    </label>
                                </td>
                                <th><?php echo esc_html__('Premium Contact Form7', 'premium-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-contactform" name="premium-contactform" <?php checked(1, $this->pa_get_settings['premium-contactform'], true) ?>>
                                            <span class="slider round"></span>
                                        </label>
                                </td>
                            </tr>
                            
                            <tr>
                                <th><?php echo esc_html__('Premium Image Button', 'premium-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-image-button" name="premium-image-button" <?php checked(1, $this->pa_get_settings['premium-image-button'], true) ?>>
                                            <span class="slider round"></span>
                                    </label>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <input type="submit" value="Save Settings" class="button pa-btn pa-save-button">
                    
                </div>
                <div id="pa-maps-api" class="pa-maps-tab">
                    <div class="pa-row">
                        <table class="pa-maps-table">
                            <tr>
                                <p class="pa-maps-api-notice">
                                    Premium Maps Element requires Google API key to be entered below. If you don’t have one, Click <a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank"> Here</a> to get your  key.
                                </p>
                            </tr>
                            <tr>
                                <th><h4 class="pa-api-title"><label>Google Maps API Key:</label><input name="premium-map-api" id="premium-map-api" type="text" placeholder="API Key" value="<?php echo $this->pa_get_settings['premium-map-api']; ?>"></h4></th>
                            </tr>
                            <tr>
                                <th><h4 class="pa-api-disable-title"><label><?php echo esc_html__('Enable Maps API JS File:','premium-addons-for-elementor'); ?></label><input name="premium-map-disable-api" id="premium-map-disable-api" type="checkbox" <?php checked(1, $this->pa_get_settings['premium-map-disable-api'], true) ?>><span>This will Enable the API JS file if it's not included by another theme or plugin</span></h4></th>
                            </tr>
                        </table>
                        <input type="submit" value="Save Settings" class="button pa-btn pa-save-button">
                    </div>
                </div>
                <div id="pa-system" class="pa-settings-tab">
                    <div class="pa-row">
                       <h3><?php echo esc_html__('System setup information useful for debugging purposes.','premium-addons-for-elementor');?></h3>
                       <div class="pa-system-info-container">
                           <?php 
                            echo nl2br(pa_get_sysinfo()); 
                           ?>
                       </div>
                    </div>
                </div>
                <div>
                    <p>Did you like Premium Addons for Elementor Plugin? Please<a href="https://wordpress.org/support/plugin/premium-addons-for-elementor/reviews/#new-post" target="_blank"> Click Here to Rate it ★★★★★</a></p>
                </div>

            </div>
            </form>
        </div>
	<?php
}

    public function pa_save_settings_with_ajax() {

            if( isset( $_POST['fields'] ) ) {
                parse_str( $_POST['fields'], $settings );
            }else {
                return;
            }

            $this->pa_settings = array(
                'premium-banner'            => intval( $settings['premium-banner'] ? 1 : 0 ),
                'premium-blog'              => intval( $settings['premium-blog'] ? 1 : 0 ),
                'premium-carousel'          => intval( $settings['premium-carousel'] ? 1 : 0 ),
                'premium-countdown'         => intval( $settings['premium-countdown'] ? 1 : 0 ),
                'premium-counter'           => intval( $settings['premium-counter'] ? 1 : 0 ),
                'premium-dual-header'       => intval( $settings['premium-dual-header'] ? 1 : 0 ),
                'premium-fancytext'         => intval( $settings['premium-fancytext'] ? 1 : 0 ),
                'premium-image-separator'   => intval( $settings['premium-image-separator'] ? 1 : 0 ),
                'premium-maps'              => intval( $settings['premium-maps'] ? 1 : 0 ),
                'premium-modalbox' 			=> intval( $settings['premium-modalbox'] ? 1 : 0 ),
                'premium-person' 			=> intval( $settings['premium-person'] ? 1 : 0 ),
                'premium-progressbar' 		=> intval( $settings['premium-progressbar'] ? 1 : 0 ),
                'premium-testimonials' 		=> intval( $settings['premium-testimonials'] ? 1 : 0 ),
                'premium-title'             => intval( $settings['premium-title'] ? 1 : 0 ),
                'premium-videobox'          => intval( $settings['premium-videobox'] ? 1 : 0 ),
                'premium-pricing-table'     => intval( $settings['premium-pricing-table'] ? 1 : 0),
                'premium-button'            => intval( $settings['premium-button'] ? 1 : 0),
                'premium-contactform'       => intval( $settings['premium-contactform'] ? 1 : 0),
                'premium-image-button'      => intval( $settings['premium-image-button'] ? 1 : 0),
                'premium-map-api'           => $settings['premium-map-api'],
                'premium-map-disable-api'   => intval( $settings['premium-map-disable-api'] ? 1 : 0),
            );
            update_option( 'pa_save_settings', $this->pa_settings );
            
            return true;
            die();


        }
}


new PA_admin_settings();