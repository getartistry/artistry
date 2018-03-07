<?php

// This file will contains all the CORE checkers of the Media Cleaner system.
// Each 'checker' checks the usage of the file by a certain feature of WordPress.

class Meow_WPMC_Checkers {

	private $core;

	public function __construct( $core ) {
		$this->core = $core;
	}

	function has_background_or_header( $file, $mediaId = null ) {

		$theme_ids = get_transient( "wpmc_theme_ids" );
		if ( is_array( $theme_ids ) && in_array( $mediaId, $theme_ids ) ) {
			$this->core->log( "Media {$mediaId} found in theme" );
			$this->core->last_analysis = "THEME";
			return true;
		}
		$theme_urls = get_transient( "wpmc_theme_urls" );
		if ( is_array( $theme_urls ) && in_array( $file, $theme_urls ) ) {
			$this->core->log( "Media {$mediaId} found in theme" );
			$this->core->last_analysis = "THEME";
			return true;
		}

		return false;
	}

	function check_in_gallery( $file, $mediaId = 0 ) {

		$file = $this->core->wpmc_clean_uploaded_filename( $file );
		$pinfo = pathinfo( $file );
		$url = $pinfo['dirname'] . '/' . $pinfo['filename'] .
			( isset( $pinfo['extension'] ) ? ( '.' . $pinfo['extension'] ) : '' );

		/***************************************************************************
			SEARCH BASED ON MEDIA ID
		***************************************************************************/

		if ( $mediaId > 0 ) {

			// Galleries in Visual Composer (WPBakery)
			if ( class_exists( 'Vc_Manager' ) ) {
				$galleries_images_vc = get_transient( "wpmc_galleries_images_visualcomposer" );
				if ( is_array( $galleries_images_vc ) && in_array( $mediaId, $galleries_images_vc ) ) {
					$this->core->log( "Media {$mediaId} found in a Visual Composer gallery" );
					$this->core->last_analysis = "GALLERY";
					return true;
				}
			}

			// Galleries in Fusion Builder (Avada Theme)
			if ( function_exists( 'fusion_builder_map' ) ) {
				$galleries_images_fb = get_transient( "wpmc_galleries_images_fusionbuilder" );
				if ( is_array( $galleries_images_fb ) && in_array( $mediaId, $galleries_images_fb ) ) {
					$this->core->log( "Media {$mediaId} found in post_content (Fusion Builder)" );
					$this->core->last_analysis = "GALLERY";
					return true;
				}
			}

			// Check in WooCommerce Galleries
			if ( class_exists( 'WooCommerce' ) ) {
				$galleries_images_wc = get_transient( "wpmc_galleries_images_woocommerce" );
				if ( is_array( $galleries_images_wc ) && in_array( $mediaId, $galleries_images_wc ) ) {
					$this->core->log( "Media {$mediaId} found in a WooCommerce gallery" );
					$this->core->last_analysis = "GALLERY";
					return true;
				}
			}

			// Check in Divi
			if ( function_exists( '_et_core_find_latest' ) ) {
				$galleries_images_et = get_transient( "wpmc_galleries_images_divi" );
				if ( is_array( $galleries_images_et ) && in_array( $mediaId, $galleries_images_et ) ) {
					$this->core->log( "Media {$mediaId} found in a Divi gallery" );
					$this->core->last_analysis = "GALLERY";
					return true;
				}
			}

		}

		/***************************************************************************
			SEARCH BASED ON FILE
		***************************************************************************/

		// Check in standard WP Galleries (URLS)
		$galleries_images_urls = get_transient( "wpmc_galleries_images_urls" );
		if ( is_array( $galleries_images_urls ) && in_array( $file, $galleries_images_urls ) ) {
			$this->core->log( "URL {$file} found in a standard WP Gallery" );
			$this->core->last_analysis = "GALLERY";
			return true;
		}

		return false;
	}

	function has_meta( $file, $mediaId = 0 ) {

		if ( !get_option( 'wpmc_postmeta', true ) )
			return false;

		$postmeta_images_ids = get_transient( "wpmc_postmeta_images_ids" );
		if ( is_array( $postmeta_images_ids ) && in_array( $mediaId, $postmeta_images_ids ) ) {
			$this->core->log( "Media {$mediaId} found in content (Post Meta IDs)" );
			$this->core->last_analysis = "META (ID)";
			return true;
		}

		if ( class_exists( 'acf' ) ) {
			$postmeta_images_acf_ids = get_transient( "wpmc_postmeta_images_acf_ids" );
			if ( is_array( $postmeta_images_acf_ids ) && in_array( $mediaId, $postmeta_images_acf_ids ) ) {
				$this->core->log( "Media {$mediaId} found in content (Post Meta ACF IDs)" );
				$this->core->last_analysis = "META ACF (ID)";
				return true;
			}
		}

		$file = $this->core->wpmc_clean_uploaded_filename( $file );
		$pinfo = pathinfo( $file );
		$url = $pinfo['dirname'] . '/' . $pinfo['filename'] .
			( isset( $pinfo['extension'] ) ? ( '.' . $pinfo['extension'] ) : '' );

		$postmeta_images_urls = get_transient( "wpmc_postmeta_images_urls" );
		if ( is_array( $postmeta_images_urls ) && in_array( $url, $postmeta_images_urls ) ) {
			$this->core->log( "URL {$url} found in content (Post Meta URLs)" );
			$this->core->last_analysis = "META (URL)";
			return true;
		}

		if ( class_exists( 'acf' ) ) {
			$postmeta_images_acf_urls = get_transient( "wpmc_postmeta_images_acf_urls" );
			if ( is_array( $postmeta_images_acf_urls ) && in_array( $url, $postmeta_images_acf_urls ) ) {
				$this->core->log( "URL {$url} found in content (Post Meta ACF URLs)" );
				$this->core->last_analysis = "META ACF (URL)";
				return true;
			}
		}

		return false;
	}


	function has_content( $file, $mediaId = null ) {

		global $wpdb;
		$this->core->last_analysis_ids = null;
		$shortcode_support = get_option( 'wpmc_shortcode', false );
		$file = $this->core->wpmc_clean_uploaded_filename( $file );
		$url = $file;

		// Check in Posts Content
		if ( get_option( 'wpmc_posts', true ) ) {

			if ( !empty( $mediaId ) ) {
				// Search through the CSS class
				$posts_images_ids = get_transient( "wpmc_posts_images_ids" );
				if ( is_array( $posts_images_ids ) && in_array( $mediaId, $posts_images_ids ) ) {
					$this->core->log( "Media {$mediaId} found in content (Posts Images IDs)" );
					$this->core->last_analysis = "CONTENT (ID)";
					return true;
				}

				// Posts in Visual Composer (WPBakery)
				if ( class_exists( 'Vc_Manager' ) ) {
					$posts_images_vc = get_transient( "wpmc_posts_images_visualcomposer" );
					if ( is_array( $posts_images_vc ) && in_array( $mediaId, $posts_images_vc ) ) {
						$this->core->log( "Media {$mediaId} found in content (Visual Composer)" );
						$this->core->last_analysis = "PAGE BUILDER";
						return true;
					}
				}
			}

			// Search through the filename
			$posts_images_urls = get_transient( "wpmc_posts_images_urls" );
			if ( is_array( $posts_images_urls ) && in_array( $url, $posts_images_urls ) ) {
				$this->core->log( "URL {$url} found in content (Posts Images URLs)" );
				$this->core->last_analysis = "CONTENT (URL)";
				return true;
			}
		}

		// Search in widgets
		if ( get_option( 'wpmc_widgets', false ) ) {
			if ( !empty( $mediaId )  ) {
				$widgets_ids = get_transient( "wpmc_widgets_ids" );
				if ( is_array( $widgets_ids ) && in_array( $mediaId, $widgets_ids ) ) {
					$this->core->log( "Media {$mediaId} found in widgets (Widgets IDs)" );
					$this->core->last_analysis = "WIDGET";
					return true;
				}
			}
			$widgets_urls = get_transient( "wpmc_widgets_urls" );
			if ( is_array( $widgets_urls ) && in_array( $url, $widgets_urls ) ) {
				$this->core->log( "URL {$url} found in widgets (Widgets URLs)" );
				$this->core->last_analysis = "WIDGET";
				return true;
			}
		}

		return false;
	}

}

?>
