<?php
namespace ElementPack;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! function_exists('is_plugin_active')){ include_once( ABSPATH . 'wp-admin/includes/plugin.php' ); }

final class Manager {
	private $_modules = null;

	private function is_module_active( $module_id ) {

		$module_data = $this->get_module_data( $module_id );
		$options     = get_option( 'element_pack_active_modules', [] );
		
		if ( ! isset( $options[ $module_id ] ) ) {
			return $module_data['default_activation'];
		} else {
			if($options[ $module_id ] == "on"){
				return true;
			} else {
				return false;
			}
		}

		return 'true' === $options[ $module_id ];
	}

	private function get_module_data( $module_id ) {
		return isset( $this->_modules[ $module_id ] ) ? $this->_modules[ $module_id ] : false;
	}

	public function __construct() {
		$modules = [
			'accordion',
			'advanced-button',
			'animated-heading',
			'advanced-heading',
			'advanced-gmap',
			'advanced-image-gallery',
			'call-out',
			'carousel',
			'countdown',
			'comment',
			'custom-gallery',
			'custom-carousel',
			'device-slider',
			'dropbar',
			'flip-box',
			'image-compare',
			'iconnav',
			'marker',
			'member',
			'modal',
			'navbar',
			'news-ticker',
			'offcanvas',
			'parallax-section',
			'panel-slider',
			'post-card',
			'post-block',
			'post-grid',
			'post-block-modern',
			'post-gallery',
			'post-slider',
			'price-list',
			'price-table',
			'progress-pie',
			'qrcode',
			'query-control',
			'scrollnav',
			'search',
			'slider',
			'slideshow',
			'social-share',
			'scroll-button',
			'switcher',
			'tabs',
			'timeline',
			'toggle',
			'trailer-box',
			'thumb-gallery',
			'user-login',
			'elementor',
			
		];

		$contact_form_seven = element_pack_option('ontact-form-seven', 'element_pack_third_party_widget', 'on' );
		$rev_slider         = element_pack_option('revolution-slider', 'element_pack_third_party_widget', 'on' );
		$instagram_feed     = element_pack_option('instagram-feed', 'element_pack_third_party_widget', 'on' );
		$wp_forms           = element_pack_option('wp-forms', 'element_pack_third_party_widget', 'on' );
		$mailchimp          = element_pack_option('mailchimp', 'element_pack_third_party_widget', 'on' );
		$tcarousel          = element_pack_option('testimonial-carousel', 'element_pack_third_party_widget', 'on' );
		$tslider            = element_pack_option('testimonial-slider', 'element_pack_third_party_widget', 'on' );
		$woocommerce        = element_pack_option('woocommerce', 'element_pack_third_party_widget', 'on' );
		$booked_calendar    = element_pack_option('booked-calendar', 'element_pack_third_party_widget', 'on' );
		$bbpress            = element_pack_option('bbpress', 'element_pack_third_party_widget', 'on' );
		$layerslider        = element_pack_option('layerslider', 'element_pack_third_party_widget', 'on' );
		$downloadmonitor    = element_pack_option('download-monitor', 'element_pack_third_party_widget', 'on' );
		$wpdatatable        = element_pack_option('wpdatatable', 'element_pack_third_party_widget', 'on' );
		$quform             = element_pack_option('quform', 'element_pack_third_party_widget', 'on' );
		$ninja_forms        = element_pack_option('ninja-forms', 'element_pack_third_party_widget', 'on' );
		$caldera_forms      = element_pack_option('caldera-forms', 'element_pack_third_party_widget', 'on' );
		$gravity_forms      = element_pack_option('gravity-forms', 'element_pack_third_party_widget', 'on' );

		if( is_plugin_active('contact-form-7/wp-contact-form-7.php') and 'on' === $contact_form_seven ) {
			$modules[] = 'contact-form-seven';
		}
		if( (is_plugin_active( 'bdthemes-testimonials/bdthemes-testimonials.php' ) || is_plugin_active( 'jetpack/jetpack.php' ) and 'on' === $tcarousel )) {
			$modules[] = 'testimonial-carousel';
		}
		if( (is_plugin_active( 'bdthemes-testimonials/bdthemes-testimonials.php' ) || is_plugin_active( 'jetpack/jetpack.php' ) and 'on' === $tslider )) {
			$modules[] = 'testimonial-slider';
		}
		if( is_plugin_active('revslider/revslider.php') and 'on' === $rev_slider ) {
			$modules[] = 'revolution-slider';
		}
		if( is_plugin_active('mailchimp-for-wp/mailchimp-for-wp.php') and 'on' === $mailchimp ) {
			$modules[] = 'mailchimp';
		}
		if( is_plugin_active('instagram-feed/instagram-feed.php') and 'on' === $instagram_feed) {
			$modules[] = 'instagram-feed';
		}
		if( is_plugin_active('wpforms-lite/wpforms.php') and 'on' === $wp_forms ) {
			$modules[] = 'wp-forms';
		}
		if( is_plugin_active('woocommerce/woocommerce.php') and 'on' === $woocommerce ) {
			$modules[] = 'woocommerce';
		}
		if( is_plugin_active('booked/booked.php') and 'on' === $booked_calendar ) {
			$modules[] = 'booked-calendar';
		}
		if( is_plugin_active('bbpress/bbpress.php') and 'on' === $bbpress ) {
			$modules[] = 'bbpress';
		}
		if( is_plugin_active('LayerSlider/layerslider.php') and 'on' === $layerslider ) {
			$modules[] = 'layer-slider';
		}
		if( is_plugin_active('download-monitor/download-monitor.php') and 'on' === $downloadmonitor ) {
			$modules[] = 'download-monitor';
		}

		// if( is_plugin_active('wpdatatables/wpdatatables.php') and 'on' === $wpdatatable ) {
		// 	$modules[] = 'wpdatatable';
		// }

		if( is_plugin_active('quform/quform.php') and 'on' === $quform ) {
			$modules[] = 'quform';
		}

		if( is_plugin_active('ninja-forms/ninja-forms.php') and 'on' === $ninja_forms ) {
			$modules[] = 'ninja-forms';
		}

		if( is_plugin_active('caldera-forms/caldera-core.php') and 'on' === $caldera_forms ) {
			$modules[] = 'caldera-forms';
		}
		if( is_plugin_active('gravityforms/gravityforms.php') and 'on' === $gravity_forms ) {
			$modules[] = 'gravity-forms';
		}

		// Fetch all modules data
		foreach ( $modules as $module ) {
			$this->_modules[ $module ] = require BDTEP_MODULES_PATH . $module . '/module.info.php';
		}

		foreach ( $this->_modules as $module_id => $module_data ) {
			if ( ! $this->is_module_active( $module_id ) ) {
				continue;
			}

			$class_name = str_replace( '-', ' ', $module_id );
			$class_name = str_replace( ' ', '', ucwords( $class_name ) );
			$class_name = __NAMESPACE__ . '\\Modules\\' . $class_name . '\Module';

			$class_name::instance();
		}
	}
}
