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
    $posts_images_urls = get_transient( "wpmc_posts_images_urls" );
    if ( empty( $posts_images_urls ) )
      $posts_images_urls = array();
    $posts_images_ids = get_transient( "wpmc_posts_images_ids" );
    if ( empty( $posts_images_ids ) )
      $posts_images_ids = array();
    $galleries_images = get_transient( "wpmc_galleries_images_urls" );
    if ( empty( $galleries_images ) )
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

    set_transient( "wpmc_posts_images_urls", $posts_images_urls, Meow_WPMC_Core::$transient_life );
    set_transient( "wpmc_posts_images_ids", $posts_images_ids, Meow_WPMC_Core::$transient_life );
    set_transient( "wpmc_galleries_images_urls", $galleries_images, Meow_WPMC_Core::$transient_life );
  }

  public function scan_postmeta( $id ) {
    global $wpdb;
    $query = $wpdb->prepare( "SELECT meta_value FROM $wpdb->postmeta
      WHERE post_id = %d
      AND meta_key = '_thumbnail_id' ", $id ) . $this->likes;
    $metas = $wpdb->get_col( $query );
    if ( count( $metas ) > 0 ) {
      $postmeta_images_ids = get_transient( "wpmc_postmeta_images_ids" );
      if ( empty( $postmeta_images_ids ) )
        $postmeta_images_ids = array();
      $postmeta_images_urls = get_transient( "wpmc_postmeta_images_urls" );
      if ( empty( $postmeta_images_urls ) )
        $postmeta_images_urls = array();

      foreach ( $metas as $meta ) {
        // Just a number, let's assume it's a Media ID
        if ( is_numeric( $meta ) ) {
          //error_log( "META NUMERIC: " . $meta );
          if ( $meta > 0 )
            array_push( $postmeta_images_ids, $meta );
          continue;
        }
        $decoded = @unserialize( $meta );
        if ( is_array( $decoded ) ) {
          // error_log( "META DECODED" );
          // error_log( print_r( $decoded, 1 ) );
          $this->core->array_to_ids_or_urls( $decoded, $postmeta_images_ids, $postmeta_images_urls );
          continue;
        }
        $exploded = explode( ',', $meta );
        if ( is_array( $exploded ) ) {
          // error_log( "META EXPLODED" );
          // error_log( print_r( $exploded, 1 ) );
          $this->core->array_to_ids_or_urls( $exploded, $postmeta_images_ids, $postmeta_images_urls );
          continue;
        }
      }
      set_transient( "wpmc_postmeta_images_ids", $postmeta_images_ids, Meow_WPMC_Core::$transient_life );
      set_transient( "wpmc_postmeta_images_urls", $postmeta_images_urls, Meow_WPMC_Core::$transient_life );
    }
  }

  function scan_postmeta_woocommerce( $id ) {
    global $wpdb;
    $galleries_images_wc = get_transient( "wpmc_galleries_images_woocommerce" );
    if ( empty( $galleries_images_wc ) )
      $galleries_images_wc = array();
    $res = $wpdb->get_col( "SELECT meta_value FROM $wpdb->postmeta WHERE post_id = $id
      AND meta_key = '_product_image_gallery'" );
    foreach ( $res as $values ) {
      $ids = explode( ',', $values );
      $galleries_images_wc = array_merge( $galleries_images_wc, $ids );
    }
    set_transient( "wpmc_galleries_images_woocommerce", $galleries_images_wc, Meow_WPMC_Core::$transient_life );
  }

  public function scan_postmeta_acf( $id ) {
    $postmeta_images_acf_ids = get_transient( "wpmc_postmeta_images_acf_ids" );
    if ( empty( $postmeta_images_acf_ids ) )
      $postmeta_images_acf_ids = array();
    $postmeta_images_acf_urls = get_transient( "wpmc_postmeta_images_acf_urls" );
    if ( empty( $postmeta_images_acf_urls ) )
      $postmeta_images_acf_urls = array();
    $fields = get_field_objects( $id );
    if ( is_array( $fields ) ) {
      foreach ( $fields as $field ) {
        $format = "";
        if ( isset( $field['return_format'] ) )
          $format = $field['return_format'];
        else if ( isset( $field['save_format'] ) )
          $format = $field['save_format'];
        if ( $field['type'] == 'image' && ( $format == 'array' || $format == 'object' ) ) {
          if ( !empty( $field['value']['id'] ) )
            array_push( $postmeta_images_acf_ids, $field['value']['id'] );
          if ( !empty( $field['value']['url'] ) )
            array_push( $postmeta_images_acf_urls, $this->core->wpmc_clean_url( $field['value']['url'] ) );
        }
        else if ( $field['type'] == 'image' && $format == 'id' && !empty( $field['value'] ) ) {
          array_push( $postmeta_images_acf_ids, $field['value'] );
        }
        else if ( $field['type'] == 'image' && $format == 'url' && !empty( $field['value'] ) ) {
          array_push( $postmeta_images_acf_urls, $this->core->wpmc_clean_url( $field['value'] ) );
        }
        else if ( $field['type'] == 'gallery' && !empty( $field['value'] ) ) {
          foreach ( $field['value'] as $media ) {
            if ( !empty( $media['id'] ) )
              array_push( $postmeta_images_acf_ids, $media['id'] );
          }
        }
      }
      set_transient( "wpmc_postmeta_images_acf_ids", $postmeta_images_acf_ids, Meow_WPMC_Core::$transient_life );
      set_transient( "wpmc_postmeta_images_acf_urls", $postmeta_images_acf_urls, Meow_WPMC_Core::$transient_life );
    }
  }

}
