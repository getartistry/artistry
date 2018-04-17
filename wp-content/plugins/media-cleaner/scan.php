<?php

class MeowApps_WPMC_Scan {

	private $core = null;
	private $likes = "";
	private $metakeys = array( '%gallery%', '%ids%' );

	public function __construct( $core  ) {
		$this->core = $core;

		// Prepare likes for SQL
		foreach ( $this->metakeys as $metakey )
			$this->likes .= "OR meta_key LIKE '$metakey' ";

		// Detect values in the general (known, based on %like%) Meta Keys
		add_action( 'wpmc_scan_postmeta', array( $this, 'scan_postmeta' ) );

		if ( class_exists( 'WooCommerce' ) )
			add_action( 'wpmc_scan_postmeta', array( $this, 'scan_postmeta_woocommerce' ) );

		// Check URLs, IDs, WP Gallery, WooCommerce
		add_action( 'wpmc_scan_post', array( $this, 'scan_post' ), 10, 2 );

		// Advanced Custom Fields
		if ( class_exists( 'acf' ) )
			add_action( 'wpmc_scan_postmeta', array( $this, 'scan_postmeta_acf' ) );

	}

	public function scan_post( $html, $id ) {
		$posts_images_urls = array();
		$posts_images_ids = array();
		$galleries_images = array();

		// Check URLs in HTML
		$new_urls = $this->core->get_urls_from_html( $html );
		$posts_images_urls = array_merge( $posts_images_urls, $new_urls );

		// Check Excerpt for WooCommerce (= Product Short Description)
		if ( class_exists( 'WooCommerce' ) ) {
			$excerpt = get_post_field( 'post_excerpt', $id );
			if ( !empty( $excerpt ) ) {
				$new_urls = $this->core->get_urls_from_html( $excerpt );
				$posts_images_urls = array_merge( $posts_images_urls, $new_urls );
			}
		}

		// Check for images IDs through classes in in posts
		preg_match_all( "/wp-image-([0-9]+)/", $html, $res );
		if ( !empty( $res ) && isset( $res[1] ) && count( $res[1] ) > 0 )
			$posts_images_ids = array_merge( $posts_images_ids, $res[1] );


		// Standard WP Gallery
		$galleries = get_post_galleries_images( $id );
		foreach ( $galleries as $gallery ) {
			foreach ( $gallery as $image ) {
				array_push( $galleries_images, $this->core->wpmc_clean_url( $image ) );
			}
		}

		$this->core->add_reference_id( $posts_images_ids, 'CONTENT (ID)' );
		$this->core->add_reference_url( $posts_images_urls, 'CONTENT (URL)' );
		$this->core->add_reference_url( $galleries_images, 'GALLERY (URL)' );
	}

	public function scan_postmeta( $id ) {
		global $wpdb;
		$query = $wpdb->prepare( "SELECT meta_value FROM $wpdb->postmeta
			WHERE post_id = %d
			AND meta_key = '_thumbnail_id' ", $id ) . $this->likes;
		$metas = $wpdb->get_col( $query );
		if ( count( $metas ) > 0 ) {
			$postmeta_images_ids = array();
			$postmeta_images_urls = array();
			foreach ( $metas as $meta ) {
				// Just a number, let's assume it's a Media ID
				if ( is_numeric( $meta ) ) {
					if ( $meta > 0 )
						array_push( $postmeta_images_ids, $meta );
					continue;
				}
				$decoded = @unserialize( $meta );
				if ( is_array( $decoded ) ) {
					$this->core->array_to_ids_or_urls( $decoded, $postmeta_images_ids, $postmeta_images_urls );
					continue;
				}
				$exploded = explode( ',', $meta );
				if ( is_array( $exploded ) ) {
					$this->core->array_to_ids_or_urls( $exploded, $postmeta_images_ids, $postmeta_images_urls );
					continue;
				}
			}
			$this->core->add_reference_id( $postmeta_images_ids, 'META (ID)' );
			$this->core->add_reference_id( $postmeta_images_urls, 'META (URL)' );
		}
	}

	function scan_postmeta_woocommerce( $id ) {
		global $wpdb;
		$galleries_images_wc = array();
		$res = $wpdb->get_col( "SELECT meta_value FROM $wpdb->postmeta WHERE post_id = $id
			AND meta_key = '_product_image_gallery'" );
		foreach ( $res as $values ) {
			$ids = explode( ',', $values );
			$galleries_images_wc = array_merge( $galleries_images_wc, $ids );
		}
		$this->core->add_reference_id( $galleries_images_wc, 'WOOCOOMMERCE (ID)' );
	}

	public function scan_postmeta_acf( $id ) {
		$postmeta_images_acf_ids = array();
		$postmeta_images_acf_urls = array();
		$fields = get_field_objects( $id );
		if ( is_array( $fields ) ) {
			foreach ( $fields as $field ) {
				$format = "";
				if ( isset( $field['return_format'] ) )
					$format = $field['return_format'];
				else if ( isset( $field['save_format'] ) )
					$format = $field['save_format'];

				// ACF Repeater
				if ( $field['type'] == 'repeater' ) {
					if ( !empty( $field['value'] ) ) {
						foreach ( $field['value'] as $subfields ) {
							foreach ( $subfields as $subfield ) {
								if ( $subfield['type'] == 'image' ) {
									if ( !empty( $subfield['id'] ) )
										array_push( $postmeta_images_acf_ids, $subfield['id'] );
									if ( !empty( $subfield['url'] ) )
										array_push( $postmeta_images_acf_urls, $this->core->wpmc_clean_url( $subfield['url'] ) );
								}
							}
						}
					}
				}
				// ACF Image ID and URL
				else if ( $field['type'] == 'image' && ( $format == 'array' || $format == 'object' ) ) {
					if ( !empty( $field['value']['id'] ) )
						array_push( $postmeta_images_acf_ids, $field['value']['id'] );
					if ( !empty( $field['value']['url'] ) )
						array_push( $postmeta_images_acf_urls, $this->core->wpmc_clean_url( $field['value']['url'] ) );
				}
				 // ACF Image ID
				else if ( $field['type'] == 'image' && $format == 'id' && !empty( $field['value'] ) ) {
					array_push( $postmeta_images_acf_ids, $field['value'] );
				}
				// ACF Image URL
				else if ( $field['type'] == 'image' && $format == 'url' && !empty( $field['value'] ) ) {
					array_push( $postmeta_images_acf_urls, $this->core->wpmc_clean_url( $field['value'] ) );
				}
				// ACF Gallery
				else if ( $field['type'] == 'gallery' && !empty( $field['value'] ) ) {
					foreach ( $field['value'] as $media ) {
						if ( !empty( $media['id'] ) )
							array_push( $postmeta_images_acf_ids, $media['id'] );
					}
				}
			}
			$this->core->add_reference_id( $postmeta_images_acf_ids, 'ACF (ID)' );
			$this->core->add_reference_url( $postmeta_images_acf_urls, 'ACF (URL)' );
		}
	}

}
