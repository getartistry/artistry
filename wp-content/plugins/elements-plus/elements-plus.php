<?php
/**
 * Plugin Name: Elements Plus!
 * Description: Custom elements for the Elementor page builder by CSSIgniter.com
 * Plugin URI: https://cssigniter.com/plugins/elements-plus/
 * Author: The CSSIgniter Team
 * Version: 1.7.6
 * Author URI: https://cssigniter.com/
 * Text Domain: elements-plus
 * Domain Path: /languages
 *
 * Elements Plus! is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * Elements Plus! is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Elements Plus!. If not, see <http://www.gnu.org/licenses/>.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly.

function elements_plus_dependency() {
	$message      = esc_html__( 'Elements Plus! requires the Elementor page builder to be active. Please activate Elementor to continue.', 'elements-plus' );
	$html_message = sprintf( '<div class="error">%s</div>', wpautop( $message ) );
	echo wp_kses_post( $html_message );
}

function elements_plus_fail_php_version() {
	$message      = esc_html__( 'Elements Plus! requires PHP version 5.4+, the plugin is currently NOT ACTIVE.', 'elements-plus' );
	$html_message = sprintf( '<div class="error">%s</div>', wpautop( $message ) );
	echo wp_kses_post( $html_message );
}

function elements_plus_load_plugin_textdomain() {
	load_plugin_textdomain( 'elements-plus', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

function elements_plus_category() {
	\Elementor\Plugin::instance()->elements_manager->add_category( 'elements-plus', [
		'title' => __( 'Elements Plus!', 'elements-plus' ),
		'icon'  => 'font',
	], 1 );
}

function is_audioigniter_active() {
	_deprecated_function( __FUNCTION__, '1.4.0', 'elements_plus_is_audioigniter_active()' );
	return elements_plus_is_audioigniter_active();
}


function elements_plus_is_audioigniter_active() {
	return class_exists( 'AudioIgniter' );
}

function elements_plus_is_wp_instagram_active() {
	return class_exists( 'null_instagram_widget' );
}

function elements_plus_get_video_url_info( $url ) {
	$is_vimeo   = preg_match( '#(?:https?://)?(?:www\.)?vimeo\.com/([A-Za-z0-9\-_]+)#', $url, $vimeo_id );
	$is_youtube = preg_match( '~
		# Match non-linked youtube URL in the wild. (Rev:20111012)
		https?://         # Required scheme. Either http or https.
		(?:[0-9A-Z-]+\.)? # Optional subdomain.
		(?:               # Group host alternatives.
		  youtu\.be/      # Either youtu.be,
		| youtube\.com    # or youtube.com followed by
		  \S*             # Allow anything up to VIDEO_ID,
		  [^\w\-\s]       # but char before ID is non-ID char.
		)                 # End host alternatives.
		([\w\-]{11})      # $1: VIDEO_ID is exactly 11 chars.
		(?=[^\w\-]|$)     # Assert next char is non-ID or EOS.
		(?!               # Assert URL is not pre-linked.
		  [?=&+%\w]*      # Allow URL (query) remainder.
		  (?:             # Group pre-linked alternatives.
			[\'"][^<>]*>  # Either inside a start tag,
		  | </a>          # or inside <a> element text contents.
		  )               # End recognized pre-linked alts.
		)                 # End negative lookahead assertion.
		[?=&+%\w-]*        # Consume any URL (query) remainder.
		~ix',
	$url, $youtube_id );

	$info = array(
		'supported' => false,
		'provider'  => '',
		'video_id'  => '',
	);

	if ( $is_youtube ) {
		$info['supported'] = true;
		$info['provider']  = 'youtube';
		$info['video_id']  = $youtube_id[1];
	} elseif ( $is_vimeo ) {
		$info['supported'] = true;
		$info['provider']  = 'vimeo';
		$info['video_id']  = $vimeo_id[1];
	}

	return $info;
}

function elements_plus_sanitize_settings( $options ) {
	$defaults = array(
		'checkbox_label'        => '',
		'checkbox_dual_input'   => '',
		'checkbox_justified'    => '',
		'checkbox_cta'          => '',
		'checkbox_maps'         => '',
		'checkbox_audioigniter' => '',
		'checkbox_video_slider' => '',
		'checkbox_preloader'    => '',
		'checkbox_instagram'    => '',
		'checkbox_tooltip'      => '',
		'checkbox_scheduled'    => '',
		'checkbox_icon'         => '',
		'checkbox_flipclock'    => '',
		'api_maps'              => '',
	);

	$options = wp_parse_args( $options, $defaults );

	foreach ( $options as $option => $value ) {
		if ( 'api_maps' === $option ) {
			$options[ $option ] = sanitize_text_field( $value );
		} else {
			$options[ $option ] = intval( $value );
		}
	}

	return $options;
}

function elements_plus_add_fonts() {
	wp_enqueue_style( 'ep-icon', ELEMENTS_PLUS_URL . 'assets/css/ep-icon.css' );
	wp_enqueue_style( 'ep-icon-module', ELEMENTS_PLUS_URL . 'assets/css/ep-icon-module.css' );
	wp_enqueue_style( 'ep-elementor-styles', ELEMENTS_PLUS_URL . 'assets/css/ep-elementor-styles.css' );
}

function elements_plus_add_elements() {

	$options = elements_plus_sanitize_settings( get_option( 'elements_plus_settings' ) );

	if ( $options['checkbox_label'] ) {
		require_once ELEMENTS_PLUS_PATH . 'elements/ep-label.php';
	}

	if ( $options['checkbox_dual_input'] ) {
		require_once ELEMENTS_PLUS_PATH . 'elements/ep-button-plus.php';
	}

	if ( $options['checkbox_justified'] ) {
		require_once ELEMENTS_PLUS_PATH . 'elements/ep-justified-gallery.php';
	}

	if ( $options['checkbox_maps'] ) {
		require_once ELEMENTS_PLUS_PATH . 'elements/ep-styled-maps.php';
	}

	if ( $options['checkbox_cta'] ) {
		require_once ELEMENTS_PLUS_PATH . 'elements/ep-cta.php';
	}

	if ( $options['checkbox_audioigniter'] && elements_plus_is_audioigniter_active() ) {
		require_once ELEMENTS_PLUS_PATH . 'elements/ep-audioigniter.php';
	}

	if ( $options['checkbox_video_slider'] ) {
		require_once ELEMENTS_PLUS_PATH . 'elements/ep-video-slider.php';
	}

	if ( $options['checkbox_preloader'] ) {
		require_once ELEMENTS_PLUS_PATH . 'elements/ep-preloader.php';
	}

	if ( $options['checkbox_instagram'] && elements_plus_is_wp_instagram_active() ) {
		require_once ELEMENTS_PLUS_PATH . 'elements/ep-instagram.php';
	}

	if ( $options['checkbox_tooltip'] ) {
		require_once ELEMENTS_PLUS_PATH . 'elements/ep-tooltip.php';
	}

	if ( $options['checkbox_icon'] ) {
		require_once ELEMENTS_PLUS_PATH . 'elements/ep-icon-plus.php';
	}

	if ( $options['checkbox_scheduled'] ) {
		require_once ELEMENTS_PLUS_PATH . 'elements/ep-scheduled.php';
	}

	if ( $options['checkbox_flipclock'] ) {
		require_once ELEMENTS_PLUS_PATH . 'elements/ep-flipclock.php';
	}
}

function elements_plus_scripts() {
	$options      = elements_plus_sanitize_settings( get_option( 'elements_plus_settings' ) );
	$label        = $options['checkbox_label'];
	$button_plus  = $options['checkbox_dual_input'];
	$justified    = $options['checkbox_justified'];
	$maps         = $options['checkbox_maps'];
	$api_key      = $options['api_maps'];
	$cta          = $options['checkbox_cta'];
	$audioigniter = $options['checkbox_audioigniter'];
	$video_slider = $options['checkbox_video_slider'];
	$preloader    = $options['checkbox_preloader'];
	$instagram    = $options['checkbox_instagram'];
	$tooltip      = $options['checkbox_tooltip'];
	$icon         = $options['checkbox_icon'];
	$flipclock    = $options['checkbox_flipclock'];

	if ( 1 === $icon ) {
		wp_enqueue_style( 'ep-icon-module', ELEMENTS_PLUS_URL . 'assets/css/ep-icon-module.css' );
	}

	if ( 1 === $justified ) {
		wp_enqueue_style( 'justified-gallery', ELEMENTS_PLUS_URL . 'assets/css/justifiedGallery.min.css' );
		wp_enqueue_script( 'justified-gallery', ELEMENTS_PLUS_URL . 'assets/js/jquery.justifiedGallery.min.js', array( 'jquery' ), '3.6.3', true );
	}

	if ( 1 === $justified || 1 === $maps || 1 === $audioigniter || 1 === $video_slider || 1 === $preloader || 1 === $flipclock ) {
		wp_enqueue_script( 'ep-scripts', ELEMENTS_PLUS_URL . 'assets/js/ep-scripts.js', array( 'jquery' ), '1.0.0', true );
	}

	if ( 1 === $video_slider ) {
		wp_enqueue_script( 'ep-fitvids', ELEMENTS_PLUS_URL . 'assets/js/jquery.fitvids.js', array( 'jquery' ), '1.1', true );
		wp_enqueue_script( 'ep-matchHeight', ELEMENTS_PLUS_URL . 'assets/js/jquery.matchHeight.js', array( 'jquery' ), '1.0', true );
	}

	if ( $api_key ) {
		wp_enqueue_script( 'ep-google-maps', 'https://maps.googleapis.com/maps/api/js?key=' . $api_key );
	}

	if ( 1 === $audioigniter && elements_plus_is_audioigniter_active() ) {
		wp_enqueue_script( 'audioigniter' );
	}

	if ( 1 === $flipclock ) {
		wp_enqueue_style( 'flipclock', ELEMENTS_PLUS_URL . 'assets/css/flipclock.css' );
		wp_enqueue_script( 'flipclock', ELEMENTS_PLUS_URL . 'assets/js/flipclock.min.js', array( 'jquery' ), '1.1.a', true );
	}

	if ( 1 === $label || 1 === $button_plus || 1 === $justified || 1 === $cta || 1 === $video_slider || 1 === $preloader || 1 === $instagram || 1 === $tooltip || 1 === $icon || 1 === $flipclock ) {
		wp_enqueue_style( 'ep-elements', ELEMENTS_PLUS_URL . 'assets/css/ep-elements.css' );
	}
}

function elements_plus_admin_styles( $hook ) {
	if ( 'elementor_page_elements_plus' !== $hook ) {
		return;
	}
	wp_enqueue_style( 'custom_wp_admin_css', ELEMENTS_PLUS_URL . 'assets/css/admin-styles.css' );
}

add_action( 'plugins_loaded', 'elements_plus_init' );

function elements_plus_init() {
	if ( ! defined( 'ELEMENTOR_VERSION' ) ) {
		add_action( 'admin_notices', 'elements_plus_dependency' );

		return;
	}

	if ( ! version_compare( PHP_VERSION, '5.4', '>=' ) ) {
		add_action( 'admin_notices', 'elements_plus_fail_php_version' );

		return;
	}

	define( 'ELEMENTS_PLUS_URL', plugins_url( '/', __FILE__ ) );
	define( 'ELEMENTS_PLUS_PATH', plugin_dir_path( __FILE__ ) );

	add_action( 'init', 'elements_plus_load_plugin_textdomain' );

	add_action( 'elementor/init', 'elements_plus_category' );

	require_once ELEMENTS_PLUS_PATH . 'inc/elements-plus-options.php';

	add_action( 'elementor/editor/before_enqueue_scripts', 'elements_plus_add_fonts' );

	add_action( 'elementor/init', 'elements_plus_add_elements' );

	add_action( 'wp_enqueue_scripts', 'elements_plus_scripts' );

	add_action( 'admin_enqueue_scripts', 'elements_plus_admin_styles' );
}
