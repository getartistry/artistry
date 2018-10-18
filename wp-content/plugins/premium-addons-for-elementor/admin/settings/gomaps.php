<?php

namespace PremiumAddons;

if(!defined('ABSPATH')) exit;

class PA_Gomaps {
    
    public static $pa_maps_keys = [ 'premium-map-api', 'premium-map-disable-api' ];
    
    private $pa_maps_default_settings;
    
    private $pa_maps_settings;
    
    private $pa_maps_get_settings;
    
    public function __construct()
    {
        add_action( 'admin_menu', array ($this,'create_gomaps_menu' ), 100 );
        add_action( 'wp_ajax_pa_maps_save_settings', array( $this, 'pa_save_maps_settings_with_ajax' ) );
    }
    
    public function create_gomaps_menu(){
        add_submenu_page(
            'premium-addons',
            '',
            'Google Maps API',
            'manage_options',
            'premium-addons-maps',
            [$this, 'pa_maps_page']
        );
    }
    
    public function pa_maps_page(){
        $js_info = array(
			'ajaxurl' => admin_url( 'admin-ajax.php' )
		);
        
        wp_localize_script( 'pa-admin-js', 'settings', $js_info );
        
        $this->pa_maps_default_settings = $this->get_default_keys();
       
        $this->pa_maps_get_settings = $this->get_enabled_keys();
       
        $pa_maps_new_settings = array_diff_key( $this->pa_maps_default_settings, $this->pa_maps_get_settings );
        
        if( ! empty( $pa_maps_new_settings ) ) {
            $pa_maps_updated_settings = array_merge( $this->pa_maps_get_settings, $pa_maps_new_settings );
            update_option( 'pa_maps_save_settings', $pa_maps_updated_settings );
        }
        $this->pa_maps_get_settings = get_option( 'pa_maps_save_settings', $this->pa_maps_default_settings );
        
        
        ?>
<div class="wrap">
   <div class="response-wrap"></div>
   <form action="" method="POST" id="pa-maps" name="pa-maps">
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
      <div id="pa-maps-api" class="pa-maps-tab">
         <div class="pa-row">
            <table class="pa-maps-table">
               <tr>
                  <p class="pa-maps-api-notice">
                     <?php echo esc_html( Helper_Functions::get_prefix() ); ?> Maps Element requires Google API key to be entered below. If you don’t have one, Click <a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank"> Here</a> to get your  key.
                  </p>
               </tr>
               <tr>
                  <th>
                     <h4 class="pa-api-title"><label>Google Maps API Key:</label><input name="premium-map-api" id="premium-map-api" type="text" placeholder="API Key" value="<?php echo $this->pa_maps_get_settings['premium-map-api']; ?>"></h4>
                  </th>
               </tr>
               <tr>
                  <th>
                     <h4 class="pa-api-disable-title"><label><?php echo esc_html__('Enable Maps API JS File:','premium-addons-for-elementor'); ?></label><input name="premium-map-disable-api" id="premium-map-disable-api" type="checkbox" <?php checked(1, $this->pa_maps_get_settings['premium-map-disable-api'], true) ?>><span>This will Enable the API JS file if it's not included by another theme or plugin</span></h4>
                  </th>
               </tr>
            </table>
            <input type="submit" value="Save Settings" class="button pa-btn pa-save-button">
            <?php if( ! Helper_Functions::is_show_rate()) : ?>
                <div>
                    <p>Did you like Premium Addons for Elementor Plugin? Please <a href="https://wordpress.org/support/plugin/premium-addons-for-elementor/reviews/#new-post" target="_blank">Click Here to Rate it ★★★★★</a></p>
                </div>
                <?php endif; ?>
         </div>
      </div>
   </div>
   </form>
</div>
    <?php }
    
    public static function get_default_keys() {
        
        $default_keys = array_fill_keys( self::$pa_maps_keys, true );
        
        return $default_keys;
    }
    
    public static function get_enabled_keys() {
        $enabled_keys = get_option( 'pa_maps_save_settings', self::get_default_keys() );
        
        return $enabled_keys;
    }
    
    public function pa_save_maps_settings_with_ajax() {
        
            if( isset( $_POST['fields'] ) ) {
                parse_str( $_POST['fields'], $settings );
            }else {
                return;
            }
            
            $this->pa_maps_settings = array(
                'premium-map-api'           => $settings['premium-map-api'],
                'premium-map-disable-api'   => intval( $settings['premium-map-disable-api'] ? 1 : 0),
            );
            
            update_option( 'pa_maps_save_settings', $this->pa_maps_settings );
            
            return true;
            die();
        }
}