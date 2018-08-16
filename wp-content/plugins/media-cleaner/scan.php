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
		$fields = get_field_objects( $id );
		if ( is_array( $fields ) ) {
			foreach ( $fields as $field )
				$this->scan_postmeta_acf_field( $field, $id, 8 );
		}
	}

	/**
	 * Scans a single ACF field object.
	 * If the specified field is a repeater or a flexible content,
	 * scans each subfield recursively.
	 *
	 * @param array $field
	 * An associative array replesenting a single ACF field.
	 * The actual array must be structured like this:
	 * array (
	 *   'name'  => The name of the field
	 *   'type'  => The field type i.e. 'text', 'object', 'repeater'
	 *   'value' => The value
	 *   ...
	 * )
	 * @param int $id The post ID
	 * @param int $recursion_limit The max recursion depth. Negative number means unlimited
	 *
	 * @since ACF 5.6.10
	 */
	public function scan_postmeta_acf_field( $field, $id, $recursion_limit = -1 ) {
		if ( !isset( $field['type'] ) ) return;

		/** Multiple Fields (Repeater or Flexible Content) **/
		static $recursives = array ( // Possibly Recursive Types
			'repeater',
			'flexible_content'
		);
		if ( in_array( $field['type'], $recursives ) && have_rows( $field['name'], $id ) ) {
			if ( $recursion_limit == 0 ) return; // Too much recursion
			do { // Iterate over rows
				$row = the_row( true );
				foreach ( $row as $col => $value ) { // Iterate over columns (subfields)
					$subfield = get_sub_field_object( $col, true, true );
					if ( !is_array( $subfield ) ) continue;
					if ( WP_DEBUG ) { // XXX Debug
						if ( !isset( $subfield['value'] ) ) trigger_error( 'Unexpected Situation: $subfield[value] is not set', E_USER_ERROR );
						if ( $subfield['value'] != $value ) trigger_error( 'Unexpected Situation: $subfield[value] has unexpected value', E_USER_ERROR );
					}
					$this->scan_postmeta_acf_field( $subfield, $id, $recursion_limit - 1 ); // Recursion
				}
			} while ( have_rows( $field['name'], $id ) );
			return;
		}
		/** Singular Field **/
		$postmeta_images_acf_ids = array();
		$postmeta_images_acf_urls = array();

		$format = "";
		if ( isset( $field['return_format'] ) )
			$format = $field['return_format'];
		else if ( isset( $field['save_format'] ) )
			$format = $field['save_format'];

		// ACF Image ID and URL
		if ( $field['type'] == 'image' && ( $format == 'array' || $format == 'object' ) ) {
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
		$this->core->add_reference_id( $postmeta_images_acf_ids, 'ACF (ID)' );
		$this->core->add_reference_url( $postmeta_images_acf_urls, 'ACF (URL)' );
	}
}
