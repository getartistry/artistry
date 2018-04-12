<?php

namespace CASE27\Integrations\ListingTypes;

class ListingType {
	private $data, $fields, $single, $preview, $search, $settings;

	public function __construct( \WP_Post $post ) {
		$this->data     = $post;
		$this->fields   = unserialize( $this->data->case27_listing_type_fields );
		$this->single   = unserialize( $this->data->case27_listing_type_single_page_options );
		$this->preview  = unserialize( $this->data->case27_listing_type_result_template );
		$this->search   = array_replace_recursive(
			mylisting()->schemes()->get('search'),
			(array) unserialize( $this->data->case27_listing_type_search_page )
		);
		$this->settings = array_replace_recursive(
			mylisting()->schemes()->get('settings'),
			(array) unserialize( $this->data->case27_listing_type_settings_page )
		);
	}

	public function get_id() {
		return $this->data->ID;
	}

	public function get_name() {
		return $this->data->post_title;
	}

	public function get_slug() {
		return $this->data->post_name;
	}

	public function get_singular_name() {
		return $this->settings['singular_name'] ? : $this->data->post_title;
	}

	public function get_plural_name() {
		return $this->settings['plural_name'] ? : $this->data->post_title;
	}

	public function get_data( $key = null ) {
		if ( $key ) {
			if ( isset( $this->data->$key ) ) {
				return $this->data->$key;
			}

			return null;
		}

		return $this->data;
	}

	public function get_fields() {
		return $this->fields;
	}

	public function get_layout() {
		return $this->single;
	}

	public function get_field( $key = null ) {
		if ( $key && ! empty( $this->fields[ $key ] ) ) {
			return $this->fields[ $key ];
		}

		return false;
	}

	public function get_default_logo( $size = 'thumbnail' ) {
		if ( $image = wp_get_attachment_image_src( $this->get_data( 'default_logo' ), $size ) ) {
			return $image[0];
		}

		return false;
	}

	public function get_default_cover( $size = 'large' ) {
		if ( $image = wp_get_attachment_image_src( $this->get_data( 'default_cover_image' ), $size ) ) {
			return $image[0];
		}

		return false;
	}

	public function get_settings() {
		return $this->settings;
	}

	public function get_preview_options() {
		return (array) $this->preview;
	}

	public function get_packages() {
		return $this->settings['packages']['used'];
	}

	public function is_rating_enabled() {
		return (bool) $this->settings['reviews']['ratings']['enabled'];
	}

	/**
	 * Check if this is a global listing type.
	 * Global types can be used in the Explore page to query
	 * results within all other listing types.
	 *
	 * @since 1.6.0
	 */
	public function is_global() {
		return (bool) $this->settings['global'];
	}

	public function get_review_mode() {
		return $this->settings['reviews']['ratings']['mode'];
	}

	public function get_review_categories() {
		$defaults = [
			'rating' => [
				'id'    => 'rating',
				'label' => esc_html__( 'Your Rating', 'my-listing' ),
			],
		];

		$_categories = $this->settings['reviews']['ratings']['categories'];

		if ( $_categories && is_array( $_categories ) ) {
			$categories = [];

			// Sanitize: make sure all required keys available.
			foreach ( $_categories as $category ) {
				$category = wp_parse_args( $category, [
					'id'    => '',
					'label' => '',
				]);

				if ( $category['id'] ) {
					$categories[ $category['id'] ] = $category;
				}
			}

			return $categories;
		}

		return $defaults;
	}

	/**
	 * Get Explore page ordering options.
	 * Values are parsed as following:
	 * Context: option; value: ':option' (prepend option name with colon)
	 * Context: meta_key; value: 'field_key'
	 * Context: raw_meta_key; value: '_raw_field_key' (prepend field key with underscore)
	 *
	 * @since 1.6.0
	 * @return array
	 */
	public function get_ordering_options() {
		$defaults = [
			[
				'label' => _x( 'Latest', 'Explore listings: Order by listing date', 'my-listing' ),
				'key' => 'latest',
				'clauses' => [[
					'orderby' => 'date',
					'order' => 'DESC',
					'context' => 'option',
					'type' => 'CHAR',
					'custom_type' => false,
				]],
			],
			[
				'label' => _x( 'Top rated', 'Explore listings: Order by rating value', 'my-listing' ),
				'key' => 'top-rated',
				'clauses' => [[
					'orderby' => '_case27_average_rating',
					'order' => 'DESC',
					'context' => 'raw_meta_key',
					'type' => 'DECIMAL(10,2)',
					'custom_type' => true,
				]],
			],
			[
				'label' => _x( 'Random', 'Explore listings: Order randomly', 'my-listing' ),
				'key' => 'random',
				'clauses' => [[
					'orderby' => 'rand',
					'order' => 'DESC',
					'context' => 'option',
					'type' => 'CHAR',
					'custom_type' => false,
				]],
			],
		];

		$_options = $this->search['order']['options'];

		if ( $_options && is_array( $_options ) ) {
			$options = [];

			foreach ( (array) $_options as $option ) {
				if ( empty( $option['key'] ) || empty( $option['label'] ) || empty( $option['clauses'] ) ) {
					continue;
				}

				foreach ( (array) $option['clauses'] as $clause ) {
					if ( empty( $clause['orderby'] ) || empty( $clause['order'] ) || empty( $clause['context'] ) || empty( $clause['type'] ) ) {
						continue(2);
					}
				}

				$options[] = $option;
			}

			if ( ! empty( $options ) ) {
				return $options;
			}
		}

		return $defaults;
	}

	public function is_gallery_enabled() {
		return (bool) $this->settings['reviews']['gallery']['enabled'];
	}

	public function get_package( $package_id ) {
		foreach ($this->settings['packages']['used'] as $package) {
			if ( $package['package'] == $package_id ) {
				return $package;
			}
		}

		return false;
	}

	public function get_search_filters( $form = 'advanced' ) {
		return $this->search[ $form ][ 'facets' ];
	}

	public function get_setting( $key = null ) {
		if ( $key && ! empty( $this->settings[ $key ] ) ) {
			return $this->settings[ $key ];
		}

		return false;
	}

	public function get_schema_markup() {
		if ( empty( $this->settings['seo']['markup'] ) || ! is_array( $this->settings['seo']['markup'] ) ) {
			return mylisting()->schemes()->get('schema/LocalBusiness');
		}

		return $this->settings['seo']['markup'];
	}

	public function get_image( $size = 'large' ) {
		$image = wp_get_attachment_image_src( get_post_thumbnail_id( $this->data->ID ), $size );

		return $image ? array_shift( $image ) : false;
	}

	public function get_count() {
		// @todo: Find a way to get the post count without querying on every page load.
		// Using transients or something.

		return 0;
	}
}
