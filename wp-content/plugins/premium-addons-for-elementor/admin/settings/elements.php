<?php

namespace PremiumAddons;

if( ! defined( 'ABSPATH' ) ) exit(); // Exit if accessed directly

class PA_admin_settings {
    
    protected $page_slug = 'premium-addons';

    public static $pa_elements_keys = ['premium-banner', 'premium-blog','premium-carousel', 'premium-countdown','premium-counter','premium-dual-header','premium-fancytext','premium-image-separator','premium-maps','premium-modalbox','premium-person','premium-progressbar','premium-testimonials','premium-title','premium-videobox','premium-pricing-table','premium-button','premium-contactform', 'premium-image-button', 'premium-grid'];
    
    private $pa_default_settings;
    
    private $pa_settings;
    
    private $pa_get_settings;
   
    public function __construct() {
        add_action( 'admin_menu', array( $this,'pa_admin_menu') );
        add_action('admin_enqueue_scripts', array( $this, 'pa_admin_page_scripts' ) );
        add_action( 'wp_ajax_pa_save_admin_addons_settings', array( $this, 'pa_save_settings_with_ajax' ) );
        add_action('admin_enqueue_scripts',array( $this, 'localize_js_script' ) );
    }
    
    public function localize_js_script(){
        wp_localize_script(
            'pa-admin-js',
            'premiumRollBackConfirm',
            [
                'home_url'  => home_url(),
                'i18n' => [
					'rollback_confirm' => __( 'Are you sure you want to reinstall version ' . PREMIUM_ADDONS_STABLE_VERSION . ' ?', 'premium-addons-for-elementor' ),
					'rollback_to_previous_version' => __( 'Rollback to Previous Version', 'premium-addons-for-elementor' ),
					'yes' => __( 'Yes', 'premium-addons-for-elementor' ),
					'cancel' => __( 'Cancel', 'premium-addons-for-elementor' ),
				],
            ]
            );
    }

    public function pa_admin_page_scripts () {
        wp_enqueue_style( 'pa_admin_icon', PREMIUM_ADDONS_URL .'admin/assets/pa-elements-font/css/pafont.css' );
        $current_screen = get_current_screen();
        if( strpos($current_screen->id , $this->page_slug) !== false ){
            wp_enqueue_style( 'pa-admin-css', PREMIUM_ADDONS_URL.'admin/assets/admin.css' );
            
            wp_enqueue_style( 'premium-addons-sweetalert-style', PREMIUM_ADDONS_URL.'admin/assets/js/sweetalert2/sweetalert2.min.css' );
            
            wp_enqueue_script('pa-admin-js', PREMIUM_ADDONS_URL .'admin/assets/admin.js' , array('jquery'), PREMIUM_ADDONS_VERSION , true );
            
            wp_enqueue_script('pa-admin-dialog', PREMIUM_ADDONS_URL . 'admin/assets/js/dialog/dialog.js',array('jquery-ui-position'),PREMIUM_ADDONS_VERSION,true);
            
            wp_enqueue_script('pa-sweetalert-core', PREMIUM_ADDONS_URL . 'admin/assets/js/sweetalert2/core.js', array( 'jquery' ), PREMIUM_ADDONS_VERSION, true );
            
			wp_enqueue_script( 'pa-sweetalert', PREMIUM_ADDONS_URL . 'admin/assets/js/sweetalert2/sweetalert2.min.js', array( 'jquery', 'pa-sweetalert-core' ), PREMIUM_ADDONS_VERSION, true );
            
        }
    }

    public function pa_admin_menu() {
        
        $plugin_name = get_option('pa_wht_lbl_save_settings')['premium-wht-lbl-plugin-name'];
        
        if( ! defined('PREMIUM_PRO_ADDONS_VERSION') || ! isset( $plugin_name ) || '' == $plugin_name ){
            $plugin_name = 'Premium Addons for Elementor';
        }
        
        add_menu_page(
            $plugin_name,
            $plugin_name,
            'manage_options',
            'premium-addons',
            array( $this , 'pa_admin_page' ),
            '' ,
            100
        );
    }

    public function pa_admin_page(){
        $theme_name = Premium_Admin_Notices::get_installed_theme();
        
        $js_info = array(
			'ajaxurl'   => admin_url( 'admin-ajax.php' ),
            'theme'     => $theme_name
		);

		wp_localize_script( 'pa-admin-js', 'settings', $js_info );
        
        $this->pa_default_settings = $this->get_default_keys();
       
        $this->pa_get_settings = $this->get_enabled_keys();
       
        $pa_new_settings = array_diff_key( $this->pa_default_settings, $this->pa_get_settings );
       
        if( ! empty( $pa_new_settings ) ) {
            $pa_updated_settings = array_merge( $this->pa_get_settings, $pa_new_settings );
            update_option( 'pa_save_settings', $pa_updated_settings );
        }
        $this->pa_get_settings = get_option( 'pa_save_settings', $this->pa_default_settings );
        
        $prefix = Helper_Functions::get_prefix();
        
	?>
	<div class="wrap">
        <div class="response-wrap"></div>
        <form action="" method="POST" id="pa-settings" name="pa-settings">
            <div class="pa-header-wrapper">
                <div class="pa-title-left">
                    <h1 class="pa-title-main"><?php echo Helper_Functions::name(); ?></h1>
                    <h3 class="pa-title-sub"><?php echo sprintf(__('Thank you for using %s. This plugin has been developed by %s and we hope you enjoy using it.','premium-addons-for-elementor'), Helper_Functions::name(),Helper_Functions::author()); ?></h3>
                </div>
                <?php if( ! Helper_Functions::is_show_logo()) : ?>
                <div class="pa-title-right">
                    <img class="pa-logo" src="<?php echo PREMIUM_ADDONS_URL . 'admin/images/premium-addons-logo.png';?>">
                </div>
                <?php endif; ?>
            </div>
            <div class="pa-settings-tabs">
                <div id="pa-modules" class="pa-settings-tab">
                    <div>
                        <br>
                        <input type="checkbox" class="pa-checkbox" checked="checked">
                        <label>Enable/Disable All</label>
                    </div>
                    <table class="pa-elements-table">
                        <tbody>
                            <tr>
                                <th><?php echo sprintf("%s Banner",$prefix); ?></th>
                                <td>
                                    <label class="switch">
                                        <input type="checkbox" id="premium-banner" name="premium-banner" <?php checked(1, $this->pa_get_settings['premium-banner'], true) ?>>
                                        <span class="slider round"></span>
                                </label>
                                </td>
                                <th><?php echo sprintf("%s Blog",$prefix); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-blog" name="premium-blog" <?php checked(1, $this->pa_get_settings['premium-blog'], true) ?>>
                                            <span class="slider round"></span>
                                    </label>
                                </td>
                            </tr>
                            
                            
                            <tr>
                                <th><?php echo sprintf("%s Carousel",$prefix); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-carousel" name="premium-carousel" <?php checked(1, $this->pa_get_settings['premium-carousel'], true) ?>>
                                            <span class="slider round"></span>
                                    </label>
                                </td>
                                <th><?php echo sprintf("%s Countdown",$prefix); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-countdown" name="premium-countdown" <?php checked(1, $this->pa_get_settings['premium-countdown'], true) ?>>
                                            <span class="slider round"></span>
                                    </label>
                                </td>
                            </tr>
                            
                            <tr>
                                <th><?php echo sprintf("%s Counter",$prefix); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-counter" name="premium-counter" <?php checked(1, $this->pa_get_settings['premium-counter'], true) ?>>
                                            <span class="slider round"></span>
                                    </label>
                                </td>
                                <th><?php echo sprintf("%s Dual Heading",$prefix); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-dual-header" name="premium-dual-header" <?php checked(1, $this->pa_get_settings['premium-dual-header'], true) ?>>
                                            <span class="slider round"></span>
                                    </label>
                                </td>
                            </tr>
                            
                            <tr>
                                <th><?php echo sprintf("%s Fancy Text",$prefix); ?></th>
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
                                
                                <th><?php echo sprintf("%s Maps",$prefix); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-maps" name="premium-maps" <?php checked(1, $this->pa_get_settings['premium-maps'], true) ?>>
                                            <span class="slider round"></span>
                                    </label>
                                </td>
                                <th><?php echo sprintf("%s Modal Box",$prefix); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-modalbox" name="premium-modalbox" <?php checked(1, $this->pa_get_settings['premium-modalbox'], true) ?>>
                                            <span class="slider round"></span>
                                    </label>
                                </td>
                            </tr>
                            
                            <tr>    
                                <th><?php echo sprintf("%s Person",$prefix); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-person" name="premium-person" <?php checked(1, $this->pa_get_settings['premium-person'], true) ?>>
                                            <span class="slider round"></span>
                                    </label>
                                </td>
                                <th><?php echo sprintf("%s Progress Bar",$prefix); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-progressbar" name="premium-progressbar" <?php checked(1, $this->pa_get_settings['premium-progressbar'], true) ?>>
                                            <span class="slider round"></span>
                                    </label>
                                </td>
                            </tr>
                            </tr>
                            
                            <tr>
                                <th><?php echo sprintf("%s Testimonials",$prefix); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-testimonials" name="premium-testimonials" <?php checked(1, $this->pa_get_settings['premium-testimonials'], true) ?>>
                                            <span class="slider round"></span>
                                    </label>
                                </td>
                                <th><?php echo sprintf("%s Title",$prefix); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-title" name="premium-title" <?php checked(1, $this->pa_get_settings['premium-title'], true) ?>>
                                            <span class="slider round"></span>
                                    </label>
                                </td>
                            </tr>
                            
                            <tr>
                                <th><?php echo sprintf("%s Video Box",$prefix); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-videobox" name="premium-videobox" <?php checked(1, $this->pa_get_settings['premium-videobox'], true) ?>>
                                            <span class="slider round"></span>
                                        </label>
                                </td>
                                <th><?php echo sprintf("%s Pricing Table",$prefix); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-pricing-table" name="premium-pricing-table" <?php checked(1, $this->pa_get_settings['premium-pricing-table'], true) ?>>
                                            <span class="slider round"></span>
                                    </label>
                                </td>
                            </tr>
                            
                            <tr>
                                <th><?php echo sprintf("%s Button",$prefix); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-button" name="premium-button" <?php checked(1, $this->pa_get_settings['premium-button'], true) ?>>
                                            <span class="slider round"></span>
                                    </label>
                                </td>
                                <th><?php echo sprintf("%s Contact Form7",$prefix); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-contactform" name="premium-contactform" <?php checked(1, $this->pa_get_settings['premium-contactform'], true) ?>>
                                            <span class="slider round"></span>
                                        </label>
                                </td>
                            </tr>
                            
                            <tr>
                                <th><?php echo sprintf("%s Image Button",$prefix); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-image-button" name="premium-image-button" <?php checked(1, $this->pa_get_settings['premium-image-button'], true) ?>>
                                            <span class="slider round"></span>
                                    </label>
                                </td>
                                
                                <th><?php echo sprintf("%s Grid",$prefix); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-grid" name="premium-grid" <?php checked(1, $this->pa_get_settings['premium-grid'], true) ?>>
                                            <span class="slider round"></span>
                                    </label>
                                </td>
                            </tr>

                            <?php if( !defined('PREMIUM_PRO_ADDONS_VERSION') ) : ?> 
                            <tr class="pa-sec-elems-tr"><th><h1>PRO Elements</h1></th></tr>

                            <tr>
                                
                                <th><?php echo esc_html__('Premium Alert Box', 'premium-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-notbar" name="premium-notbar">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>
                                
                                <th><?php echo esc_html__('Premium Icon Box', 'premium-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-iconbox" name="premium-iconbox">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>
                                
                            </tr>
                            
                            <tr>
                                
                                
                                <th><?php echo esc_html__('Premium Twitter Feed', 'premium-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-twitter-feed" name="premium-twitter-feed">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>
                                
                                <th><?php echo esc_html__('Premium Instagram Feed', 'premium-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-instagram-feed" name="premium-instagram-feed">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>
                                
                            </tr>
                            
                            <tr>
                                <th><?php echo esc_html__('Premium Flip Box', 'premium-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-flipbox" name="premium-flipbox">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>
                                
                                <th><?php echo esc_html__('Premium Unfold', 'premium-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-unfold" name="premium-unfold">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>
                            </tr>
                            
                            <tr>
                                
                                <th><?php echo esc_html__('Premium Messenger Chat', 'premium-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-fb-chat" name="premium-fb-chat">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>
                                
                                <th><?php echo esc_html__('Premium Tabs', 'premium-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-tabs" name="premium-tabs">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>
                            </tr>
                            
                            <tr>
                                <th><?php echo esc_html__('Premium Chart', 'premium-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-charts" name="premium-charts">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>
                                
                                <th><?php echo esc_html__('Premium Preview Window', 'premium-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-prev-img" name="premium-prev-img">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>
                            </tr>
                            
                            <tr>
                                <th><?php echo esc_html__('Premium Image Hotspots', 'premium-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-image-hotspots" name="premium-image-hotspots">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>

                                <th><?php echo esc_html__('Premium Facebook Reviews', 'premium-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-facebook-reviews" name="premium-facebook-reviews">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>
                            </tr>
                            
                            <tr>
                                <th><?php echo esc_html__('Premium Image Comparison', 'premium-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-image-comparison" name="premium-image-comparison">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>
                                
                                <th><?php echo esc_html__('Premium Divider', 'premium-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-divider" name="premium-divider">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>
                                
                            </tr>
                            
                            <tr>
                                <th><?php echo esc_html__('Premium Magic Section', 'premium-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-magic-section" name="premium-magic-section">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>
                                
                                <th><?php echo esc_html__('Premium Google Reviews', 'premium-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-google-reviews" name="premium-google-reviews">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>
                            </tr>
                            
                            <tr>
                                <th><?php echo esc_html__('Premium Behance Feed', 'premium-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-behance" name="premium-behance">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>
                                
                                <th><?php echo esc_html__('Premium Tables', 'premium-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-tables" name="premium-tables">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>
                                
                            </tr>
                            
                            <tr>
                                <th><?php echo esc_html__('Premium Image Layers', 'premium-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-img-layers" name="premium-img-layers">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>
                                
                                <th><?php echo esc_html__('Premium iHover', 'premium-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-ihover" name="premium-ihover">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>
                                
                            </tr>
                            
                            <tr>
                                <th><?php echo esc_html__('Premium Content Switcher', 'premium-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-content-toggle" name="premium-content-toggle">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>
                                
                                <th><?php echo esc_html__('Premium Facebook Feed', 'premium-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-facebook-feed" name="premium-facebook-feed">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>
                                
                            </tr>
                            
                            <tr>
                                <th><?php echo esc_html__('Premium Whatsapp Chat', 'premium-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-whatsapp-chat" name="premium-whatsapp-chat">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>
                                
                            </tr>
                            
                            <tr>
                                <th><?php echo esc_html__('Premium Section Parallax', 'premium-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-section-parallax" name="premium-section-parallax">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>
                                <th><?php echo esc_html__('Premium Section Particles', 'premium-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-section-particles" name="premium-section-particles">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>
                            </tr>
                            
                            <tr>
                                <th><?php echo esc_html__('Premium Section Animated Gradient', 'premium-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-section-gradient" name="premium-section-gradient">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>
                                <th><?php echo esc_html__('Premium Section Ken Burns', 'premium-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="premium-section-kenburns" name="premium-section-kenburns">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>
                            </tr>
                            
                            <?php endif; ?> 
                        </tbody>
                    </table>
                    <input type="submit" value="Save Settings" class="button pa-btn pa-save-button">
                    
                </div>
                <?php if( ! Helper_Functions::is_show_rate()) : ?>
                <div>
                    <p>Did you like Premium Addons for Elementor Plugin? Please <a href="https://wordpress.org/support/plugin/premium-addons-for-elementor/reviews/#new-post" target="_blank">Click Here to Rate it ★★★★★</a></p>
                </div>
                <?php endif; ?>
            </div>
            </form>
        </div>
	<?php
}

    public static function get_default_keys() {
        
        $default_keys = array_fill_keys( self::$pa_elements_keys, true );
        
        return $default_keys;
    }
    
    public static function get_enabled_keys() {
        
        $enabled_keys = get_option( 'pa_save_settings', self::get_default_keys() );
        
        return $enabled_keys;
    }

    public function pa_save_settings_with_ajax() {
        
        if( isset( $_POST['fields'] ) ) {
            parse_str( $_POST['fields'], $settings );
        } else {
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
            'premium-grid'              => intval( $settings['premium-grid'] ? 1 : 0),
        );

        update_option( 'pa_save_settings', $this->pa_settings );

        return true;
        die();
    }
}