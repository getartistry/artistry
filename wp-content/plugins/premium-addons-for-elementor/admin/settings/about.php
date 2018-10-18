<?php
namespace PremiumAddons;

if ( ! defined( 'ABSPATH' ) ) exit;

class PA_About {

    public function create_about_menu(){
        if ( ! Helper_Functions::is_show_about() ) {
                add_submenu_page(
                'premium-addons',
                '',
                esc_html__('About','premium-addons-for-elementor'),
                'manage_options',
                'premium-addons-about',
                [ $this, 'pa_about_page' ]
            );
        }
    }

	public function pa_about_page(){
        
        $theme_name = Premium_Admin_Notices::get_installed_theme();
        
        $url = sprintf('https://premiumaddons.com/pro/?utm_source=about-page&utm_medium=wp-dash&utm_campaign=get-pro&utm_term=%s', $theme_name );
        
        $support_url = sprintf('https://premiumaddons.com/support/?utm_source=about-page&utm_medium=wp-dash&utm_campaign=get-support&utm_term=%s', $theme_name );
        
        ?>
<div class="wrap">
   <div class="response-wrap"></div>
   <div class="pa-header-wrapper">
      <div class="pa-title-left">
         <h1 class="pa-title-main"><?php echo Helper_Functions::name(); ?></h1>
         <h3 class="pa-title-sub"><?php echo sprintf(__('Thank you for using %s. This plugin has been developed by %s and we hope you enjoy using it.','premium-addons-for-elementor'), Helper_Functions::name(),Helper_Functions::author()); ?></h3>
      </div>
      <?php if( ! Helper_Functions::is_show_logo() ) : ?>
        <div class="pa-title-right">
            <img class="pa-logo" src="<?php echo PREMIUM_ADDONS_URL . 'admin/images/premium-addons-logo.png';?>">
        </div>
        <?php endif; ?>
   </div>
   <div class="pa-settings-tabs">
      <div id="pa-about" class="pa-settings-tab">
         <div class="pa-row">
            <div class="pa-col-half">
               <div class="pa-about-panel">
                  <div class="pa-icon-container">
                     <i class="dashicons dashicons-info abt-icon-style"></i>
                  </div>
                  <div class="pa-text-container">
                     <h4>What is Premium Addons?</h4>
                     <p>Premium Addons for Elementor extends Elementor Page Builder capabilities with many fully customizable widgets and addons that help you to build impressive websites with no coding required.</p>
                     <?php if( !defined('PREMIUM_PRO_ADDONS_VERSION') ) : ?>
                        <p>Get more widgets and addons with <strong>Premium Addons Pro</strong> <a href="<?php echo esc_url( $url ); ?>" target="_blank" >Click Here</a> to know more.</p>
                     <?php endif; ?>
                  </div>
               </div>
            </div>
            <div class="pa-col-half">
               <div class="pa-about-panel">
                  <div class="pa-icon-container">
                     <i class="dashicons dashicons-universal-access-alt abt-icon-style"></i>
                  </div>
                  <div class="pa-text-container">
                     <h4>Docs and Support</h4>
                     <p>It’s highly recommended to check out documentation and FAQ before using this plugin. <a target="_blank" href="<?php echo esc_url( $support_url ); ?>">Click Here </a> for more details. You can also join our <a href="https://www.facebook.com/groups/PremiumAddons" target="_blank">Facebook Group</a> and Our <a href="https://my.leap13.com/forums/" target="_blank">Community Forums</a></p>
                  </div>
               </div>
            </div>
         </div>
        <?php if( ! Helper_Functions::is_show_rate()) : ?>
            <div>
                <p>Did you like Premium Addons for Elementor Plugin? Please <a href="https://wordpress.org/support/plugin/premium-addons-for-elementor/reviews/#new-post" target="_blank">Click Here to Rate it ★★★★★</a></p>
            </div>
        <?php endif; ?>
      </div>
   </div>
</div>
    <?php }
    
	public function __construct() {
        add_action( 'admin_menu', array ($this,'create_about_menu' ), 100 );
	}    
}