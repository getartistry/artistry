<?php
/**
 * Plugin Name: Essential Addons for Elementor - Pro
 * Description: The ultimate elements library for Elementor page builder plugin for WordPress.
 * Plugin URI: https://essential-addons.com/elementor/
 * Author: Codetic
 * Version: 2.7.0
 * Author URI: http://www.codetic.net
 *
 * Text Domain: essential-addons-elementor
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define( 'ESSENTIAL_ADDONS_EL_URL', plugins_url( '/', __FILE__ ) );
define( 'ESSENTIAL_ADDONS_EL_PATH', plugin_dir_path( __FILE__ ) );
define( 'ESSENTIAL_ADDONS_EL_ROOT', __FILE__ );

// Licensing
define( 'EAEL_STORE_URL', 'https://wpdeveloper.net/' );
define( 'EAEL_SL_ITEM_ID', 4372 );
define( 'EAEL_SL_ITEM_SLUG', 'essential-addons-elementor' );
define( 'EAEL_SL_ITEM_NAME', 'Essential Addons for Elementor' );

require_once ESSENTIAL_ADDONS_EL_PATH.'includes/elementor-helper.php';
require_once ESSENTIAL_ADDONS_EL_PATH.'includes/queries.php';
require_once ESSENTIAL_ADDONS_EL_PATH.'admin/settings.php';

/**
 * This function will return true for all activated modules
 *
 * @since   v2.6.0
 */
function eael_activated_modules() {

   $eael_default_keys = array( 'contact-form-7', 'count-down', 'creative-btn', 'fancy-text', 'img-comparison', 'instagram-gallery', 'interactive-promo',  'lightbox', 'post-block', 'post-grid', 'post-timeline', 'product-grid', 'team-members', 'testimonial-slider', 'testimonials', 'testimonials', 'weforms', 'static-product', 'call-to-action', 'flip-box', 'info-box', 'dual-header', 'price-table', 'flip-carousel', 'interactive-cards', 'ninja-form', 'content-timeline', 'gravity-form', 'data-table', 'caldera-form','twitter-feed', 'twitter-feed-carousel', 'facebook-feed', 'facebook-feed-carousel', 'filter-gallery', 'dynamic-filter-gallery', 'content-ticker', 'image-accordion', 'post-list' );

   $eael_default_settings  = array_fill_keys( $eael_default_keys, true );
   $eael_get_settings      = get_option( 'eael_save_settings', $eael_default_settings );
   $eael_new_settings      = array_diff_key( $eael_default_settings, $eael_get_settings );

   if( ! empty( $eael_new_settings ) ) {
      $eael_updated_settings = array_merge( $eael_get_settings, $eael_new_settings );
      update_option( 'eael_save_settings', $eael_updated_settings );
   }

   return $eael_get_settings = get_option( 'eael_save_settings', $eael_default_settings );

}

/**
 * Acivate or Deactivate Modules
 *
 * @since v1.0.0
 */
function add_eael_elements() {

   $is_component_active = eael_activated_modules();

   // load elements
   if( $is_component_active['post-grid'] ) {
      require_once ESSENTIAL_ADDONS_EL_PATH.'elements/post-grid/post-grid.php';
   }

   if( $is_component_active['post-block'] ) {
      require_once ESSENTIAL_ADDONS_EL_PATH.'elements/post-block/post-block.php';
   }

   if( $is_component_active['post-timeline'] ) {
      require_once ESSENTIAL_ADDONS_EL_PATH.'elements/post-timeline/post-timeline.php';
   }

   if( $is_component_active['fancy-text'] ) {
      require_once ESSENTIAL_ADDONS_EL_PATH.'elements/fancy-text/fancy-text.php';
   }

   if( $is_component_active['img-comparison'] ) {
      require_once ESSENTIAL_ADDONS_EL_PATH.'elements/image-comparison/image-comparison.php';
   }

   if( $is_component_active['lightbox'] ) {
      require_once ESSENTIAL_ADDONS_EL_PATH.'elements/lightbox/lightbox.php';
   }

   if( $is_component_active['interactive-promo'] ) {
      require_once ESSENTIAL_ADDONS_EL_PATH.'elements/interactive-promo/interactive-promo.php';
   }

   if( $is_component_active['creative-btn'] ) {
      require_once ESSENTIAL_ADDONS_EL_PATH.'elements/creative-button/creative-button.php';
   }

   if( $is_component_active['count-down'] ) {
      require_once ESSENTIAL_ADDONS_EL_PATH.'elements/countdown/countdown.php';
   }

   if( $is_component_active['instagram-gallery'] ) {
      require_once ESSENTIAL_ADDONS_EL_PATH.'elements/instagram-gallery/instagram-gallery.php';
   }

   if( $is_component_active['team-members'] ) {
      require_once ESSENTIAL_ADDONS_EL_PATH.'elements/team-members/team-members.php';
   }

   if( $is_component_active['testimonials'] ) {
      require_once ESSENTIAL_ADDONS_EL_PATH.'elements/testimonials/testimonials.php';
   }

   if( $is_component_active['testimonial-slider'] ) {
      require_once ESSENTIAL_ADDONS_EL_PATH.'elements/testimonial-slider/testimonial-slider.php';
   }

   if ( function_exists( 'WC' ) && $is_component_active['product-grid'] ) {
      require_once ESSENTIAL_ADDONS_EL_PATH.'elements/product-grid/product-grid.php';
   }

   if ( function_exists( 'wpcf7' ) && $is_component_active['contact-form-7'] ) {
      require_once ESSENTIAL_ADDONS_EL_PATH.'elements/contact-form-7/contact-form-7.php';
   }

   if ( function_exists( 'WeForms' ) && $is_component_active['weforms'] ) {
      require_once ESSENTIAL_ADDONS_EL_PATH.'elements/weforms/weforms.php';
   }

   if( $is_component_active['static-product'] ) {
      require_once ESSENTIAL_ADDONS_EL_PATH.'elements/static-product/static-product.php';
   }

   if( $is_component_active['info-box'] ) {
      require_once ESSENTIAL_ADDONS_EL_PATH.'elements/infobox/infobox.php';
   }

   if( $is_component_active['flip-box'] ) {
      require_once ESSENTIAL_ADDONS_EL_PATH.'elements/flipbox/flipbox.php';
   }

   if( $is_component_active['call-to-action'] ) {
      require_once ESSENTIAL_ADDONS_EL_PATH.'elements/call-to-action/call-to-action.php';
   }

   if( $is_component_active['dual-header'] ) {
      require_once ESSENTIAL_ADDONS_EL_PATH.'elements/dual-color-header/dual-color-header.php';
   }

   if( $is_component_active['price-table'] ) {
      require_once ESSENTIAL_ADDONS_EL_PATH.'elements/pricing-table/pricing-table.php';
   }

   if( $is_component_active['flip-carousel'] ) {
      require_once ESSENTIAL_ADDONS_EL_PATH.'elements/flip-carousel/flip-carousel.php';
   }

   if( $is_component_active['interactive-cards'] ) {
      require_once ESSENTIAL_ADDONS_EL_PATH.'elements/interactive-card/interactive-card.php';
   }

   if( function_exists( 'Ninja_Forms' ) && $is_component_active['ninja-form'] ) {
      require_once ESSENTIAL_ADDONS_EL_PATH.'elements/ninja-form/ninja-form.php';
   }

   if( class_exists( 'GFForms' ) && $is_component_active['gravity-form'] ) {
      require_once ESSENTIAL_ADDONS_EL_PATH.'elements/gravity-form/gravity-form.php';
   }

   if( $is_component_active['content-timeline'] ) {
      require_once ESSENTIAL_ADDONS_EL_PATH.'elements/content-timeline/content-timeline.php';
   }

   if( $is_component_active['data-table'] ) {
      require_once ESSENTIAL_ADDONS_EL_PATH.'elements/data-table/data-table.php';
   }

   if( class_exists( 'Caldera_Forms' ) && $is_component_active['caldera-form'] ) {
      require_once ESSENTIAL_ADDONS_EL_PATH.'elements/caldera-forms/caldera-forms.php';
   }

   if( $is_component_active['twitter-feed'] ) {
      require_once ESSENTIAL_ADDONS_EL_PATH.'elements/twitter-feed/twitter-feed.php';
   }

   if( $is_component_active['facebook-feed'] ) {
      require_once ESSENTIAL_ADDONS_EL_PATH.'elements/facebook-feed/facebook-feed.php';
   }

   if( $is_component_active['facebook-feed-carousel'] ) {
      require_once ESSENTIAL_ADDONS_EL_PATH.'elements/facebook-feed-carousel/facebook-feed-carousel.php';
   }

   if( $is_component_active['twitter-feed-carousel'] ) {
      require_once ESSENTIAL_ADDONS_EL_PATH.'elements/twitter-feed-carousel/twitter-feed-carousel.php';
   }

   if( $is_component_active['filter-gallery'] ) {
      require_once ESSENTIAL_ADDONS_EL_PATH.'elements/filterable-gallery/filterable-gallery.php';
   }

   if( class_exists( 'ElementorPro\Modules\QueryControl\Controls\Group_Control_Posts' ) && $is_component_active['dynamic-filter-gallery'] ) {
      require_once ESSENTIAL_ADDONS_EL_PATH.'elements/dynamic-filter-gallery/dynamic-filter-gallery.php';
   }

   if( $is_component_active['content-ticker'] ) {
      require_once ESSENTIAL_ADDONS_EL_PATH.'elements/content-ticker/content-ticker.php';
   }

   if( $is_component_active['image-accordion'] ) {
      require_once ESSENTIAL_ADDONS_EL_PATH.'elements/image-accordion/image-accordion.php';
   }

   if( $is_component_active['post-list'] ) {
      require_once ESSENTIAL_ADDONS_EL_PATH.'elements/post-list/post-list.php';
   }
}
add_action('elementor/widgets/widgets_registered','add_eael_elements');

/**
 * Load module's scripts and styles if any module is active.
 *
 * @since v1.0.0
 */
function essential_addons_el_enqueue(){
   $is_component_active = eael_activated_modules();

   wp_enqueue_style('essential_addons_elementor-slick-css',ESSENTIAL_ADDONS_EL_URL.'assets/slick/slick.css');
   wp_enqueue_style('essential_addons_elementor-css',ESSENTIAL_ADDONS_EL_URL.'assets/css/essential-addons-elementor.css');
   wp_enqueue_style('essential_addons_lightbox-css',ESSENTIAL_ADDONS_EL_URL.'assets/css/lity.min.css');

   if( $is_component_active['flip-carousel']  ) {
      wp_enqueue_style('essential_addons_flipster-css',ESSENTIAL_ADDONS_EL_URL.'assets/flip-carousel/jquery.flipster.min.css');
   }
   if( $is_component_active['fancy-text'] ) {
      wp_enqueue_script('essential_addons_elementor-fancy-text-js',ESSENTIAL_ADDONS_EL_URL.'assets/js/fancy-text.js', array('jquery'),'1.0', true);
   }
   if( $is_component_active['lightbox'] ) {
      wp_enqueue_script('essential_addons_elementor-lightbox-js',ESSENTIAL_ADDONS_EL_URL.'assets/js/lity.min.js', array('jquery'),'1.0', true);
   }
   if( $is_component_active['count-down'] ) {
      wp_enqueue_script('essential_addons_elementor-countdown-js',ESSENTIAL_ADDONS_EL_URL.'assets/js/countdown.min.js', array('jquery'),'1.0', true);
   }
   if( $is_component_active['instagram-gallery'] ) {
      wp_enqueue_script('essential_addons_elementor-instafeed-js',ESSENTIAL_ADDONS_EL_URL.'assets/js/eael-instafeed.min.js', array('jquery'),'1.0', true);
   }
   if( $is_component_active['img-comparison'] ) {
      wp_enqueue_script('essential_addons_elementor-image-comp-js',ESSENTIAL_ADDONS_EL_URL.'assets/js/cocoen.min.js', array('jquery'),'1.0', true);
   }
   if( $is_component_active['testimonial-slider'] || $is_component_active['content-ticker'] ) {
      wp_enqueue_script('essential_addons_elementor-slick-js',ESSENTIAL_ADDONS_EL_URL.'assets/slick/slick.min.js', array('jquery'),'1.0', true);
   }
   if( $is_component_active['facebook-feed-carousel'] || $is_component_active['twitter-feed-carousel'] ) {
      wp_enqueue_script('essential_addons_elementor-flickity-js',ESSENTIAL_ADDONS_EL_URL.'assets/js/flickity.pkgd.min.js', array('jquery'),'1.0', false);
      wp_enqueue_style('essential_addons_flickity-css',ESSENTIAL_ADDONS_EL_URL.'assets/css/flickity.css');
   }
   if( $is_component_active['flip-carousel'] ) {
      wp_enqueue_script('essential_addons_elementor-flipster-js',ESSENTIAL_ADDONS_EL_URL.'assets/flip-carousel/jquery.flipster.min.js', array('jquery'),'1.0', true);
   }
   if( $is_component_active['interactive-cards'] ) {
      wp_enqueue_style('essential_addons_interactive-card-css',ESSENTIAL_ADDONS_EL_URL.'assets/interactive-card/interactive-card.css');
      wp_enqueue_script('essential_addons_elementor-interactive-card-js',ESSENTIAL_ADDONS_EL_URL.'assets/interactive-card/interactive-card.min.js', array('jquery'),'1.0', true);
      wp_enqueue_script('essential_addons_elementor-nicescroll-js',ESSENTIAL_ADDONS_EL_URL.'assets/interactive-card/jquery.nicescroll.min.js', array('jquery'),'1.0', true);
   }
   if( $is_component_active['post-grid'] || $is_component_active['instagram-gallery'] || $is_component_active['facebook-feed'] || $is_component_active['twitter-feed'] ) {
      wp_enqueue_script('essential_addons_elementor-masonry-js',ESSENTIAL_ADDONS_EL_URL.'assets/js/masonry.min.js', array('jquery'),'1.0', true);
   }
   if( $is_component_active['post-block'] || $is_component_active['post-grid'] || $is_component_active['post-timeline'] ) {
      wp_enqueue_script('essential_addons_elementor-eael-load-more-js',ESSENTIAL_ADDONS_EL_URL.'assets/js/eael-load-more.js', array('jquery'),'1.0', true);
   }
   if( $is_component_active['content-timeline'] ) {
      wp_enqueue_script('essential_addons_elementor-vertical-timeline-js',ESSENTIAL_ADDONS_EL_URL.'assets/js/vertical-timeline.js', array('jquery'),'1.0', true);
   }
   if( $is_component_active['data-table'] ) {
      wp_enqueue_script('essential_addons_elementor-data-table-js',ESSENTIAL_ADDONS_EL_URL.'assets/js/jquery.tablesorter.min.js', array('jquery'),'1.0', true);
   }
   if( $is_component_active['twitter-feed'] || $is_component_active['facebook-feed'] ) {
      wp_enqueue_script('essential_addons_elementor-doT-js',ESSENTIAL_ADDONS_EL_URL.'assets/social-feeds/doT.min.js', array('jquery'),'1.0', true);
      wp_enqueue_script('essential_addons_elementor-moment-js',ESSENTIAL_ADDONS_EL_URL.'assets/social-feeds/moment.js', array('jquery'),'1.0', true);
      wp_enqueue_script('essential_addons_elementor-moment-it-js',ESSENTIAL_ADDONS_EL_URL.'assets/social-feeds/it.js', array('jquery'),'1.0', true);
      wp_enqueue_script('essential_addons_elementor-socialfeed-js',ESSENTIAL_ADDONS_EL_URL.'assets/social-feeds/jquery.socialfeed.js', array('jquery'),'1.0', true);
   }
   if( $is_component_active['twitter-feed'] || $is_component_active['twitter-feed-carousel'] ) {
      wp_enqueue_script('essential_addons_elementor-codebird-js',ESSENTIAL_ADDONS_EL_URL.'assets/social-feeds/codebird.js', array('jquery'),'1.0', true);
   }
   if( $is_component_active['filter-gallery'] || $is_component_active['dynamic-filter-gallery'] ) {
      wp_enqueue_script('essential_addons_mixitup-js',ESSENTIAL_ADDONS_EL_URL.'assets/js/mixitup.min.js', array('jquery'),'1.0', true);
      wp_enqueue_script('essential_addons_magnific-popup-js',ESSENTIAL_ADDONS_EL_URL.'assets/js/jquery.magnific-popup.min.js', array('jquery'),'1.0', true);
   }
   if( $is_component_active['content-ticker'] ) {
      wp_enqueue_script('essential_addons_elementor-typed-js',ESSENTIAL_ADDONS_EL_URL.'assets/js/typed.min.js', array('jquery'),'1.0', true);
   }
   if( $is_component_active['post-list'] ) {
      wp_enqueue_script('essential_addons_elementor-post-list',ESSENTIAL_ADDONS_EL_URL.'assets/js/eael-post-list.js', array('jquery'),'1.0', true);

      $eael_post_list_settings = array(
         'ajax_url' => admin_url('admin-ajax.php'),
      );
      wp_localize_script( 'essential_addons_elementor-post-list', 'eaelPostList', $eael_post_list_settings );
   }
}
add_action( 'wp_enqueue_scripts', 'essential_addons_el_enqueue' );


/**
 * Creates an Action Menu
 */
function eael_add_settings_link( $links ) {
   $settings_link = sprintf( '<a href="admin.php?page=eael-settings">' . __( 'Settings' ) . '</a>' );
   array_push( $links, $settings_link );
   return $links;
}
$plugin = plugin_basename( __FILE__ );
add_filter( "plugin_action_links_$plugin", 'eael_add_settings_link' );

/**
 * Activation redirects
 *
 * @since v1.0.0
 */
function eael_activate() {
    add_option('eael_do_activation_redirect', true);
}
register_activation_hook(__FILE__, 'eael_activate');

/**
 * Redirect to options page
 *
 * @since v1.0.0
 */
function eael_redirect() {
    if (get_option('eael_do_activation_redirect', false)) {
        delete_option('eael_do_activation_redirect');
        if(!isset($_GET['activate-multi']))
        {
            wp_redirect("admin.php?page=eael-settings");
        }
    }
}
add_action('admin_init', 'eael_redirect');