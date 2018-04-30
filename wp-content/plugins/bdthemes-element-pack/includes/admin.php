<?php
namespace ElementPack;

use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Admin {

	public function __construct() {
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_styles' ] );
		add_filter( 'plugin_row_meta', [ $this, 'plugin_row_meta' ], 10, 2 );
	}

	public function enqueue_styles() {

		$suffix = is_rtl() ? '.rtl' : '';

		wp_register_style( 'bdthemes-element-pack-admin', BDTEP_ASSETS_URL . 'css/admin' . $suffix . '.css', BDTEP_VER );

		wp_enqueue_style( 'bdthemes-element-pack-admin' );
	}


	public function plugin_row_meta( $plugin_meta, $plugin_file ) {
		if ( BDTEP_PBNAME === $plugin_file ) {
			$row_meta = [
				'docs' => '<a href="https://bdthemes.com/support/forum/wordpress-plugins/element-pack-addon-elementor-page-builder-wordpress-plugin/" aria-label="' . esc_attr( __( 'Go for Get Support', 'bdthemes-element-pack' ) ) . '" target="_blank">' . __( 'Get Support', 'bdthemes-element-pack' ) . '</a>',
				'ideo' => '<a href="https://www.youtube.com/playlist?list=PLP0S85GEw7DOJf_cbgUIL20qqwqb5x8KA" aria-label="' . esc_attr( __( 'View Element Pack Video Tutorials', 'bdthemes-element-pack' ) ) . '" target="_blank">' . __( 'Video Tutorials', 'bdthemes-element-pack' ) . '</a>',
			];

			$plugin_meta = array_merge( $plugin_meta, $row_meta );
		}

		return $plugin_meta;
	}

}
