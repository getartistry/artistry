<?php

namespace CASE27\Classes;

use \CASE27\Classes\Conditions\Conditions;
use \CASE27\Integrations\ListingTypes\Designer;
use \CASE27\Integrations\ListingTypes\ListingType;
use \MyListing\User as User;
use \MyListing\Schema as Schema;

class Listing {

	public static $instances = [];

	private $data,
			$categories,
			$special_keys = [];

	public $schedule,
		   $schema,
		   $type   = null,
		   $author = null;

	public static $aliases = [
		'title'       => 'job_title',
		'tagline'     => 'job_tagline',
		'location'    => 'job_location',
		'category'    => 'job_category',
		'tags'        => 'job_tags',
		'description' => 'job_description',
		'email'       => 'job_email',
		'logo'        => 'job_logo',
		'cover'       => 'job_cover',
		'gallery'     => 'job_gallery',
		'website'     => 'job_website',
		'phone'       => 'job_phone',
		'video_url'   => 'job_video_url',
		'date'        => 'job_date',
	];

	/**
	 * Get a new listing instance (Multiton pattern).
	 * When called the first time, listing will be fetched from database.
	 * Otherwise, it will return the previous instance.
	 *
	 * @since 1.6.0
	 * @param $listing int or \WP_Post
	 */
	public static function get( $listing ) {
		if ( is_numeric( $listing ) ) {
			$listing = get_post( $listing );
		}

		if ( ! $listing instanceof \WP_Post ) {
			return false;
		}

		if ( $listing->post_type !== 'job_listing' ) {
			return false;
		}

		if ( ! array_key_exists( $listing->ID, self::$instances ) ) {
			self::$instances[ $listing->ID ] = new self( $listing );
		}

		return self::$instances[ $listing->ID ];
	}

	public function __construct( \WP_Post $post ) {
		self::$instances[ $post->ID ] = $this;
		$this->data = $post;
		$this->schedule = new WorkHours( (array) get_post_meta( $this->data->ID, '_work_hours', true ) );
		$this->author = new User( $this->data->post_author );
		$this->setup_special_keys();

		if ( $listing_type = ( get_page_by_path( $post->_case27_listing_type, OBJECT, 'case27_listing_type' ) ) ) {
			$this->type = new ListingType( $listing_type );
		}

		$this->schema = new Schema( $this );
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

	public function get_logo( $size = 'thumbnail' ) {
		if ( $logo = $this->get_field( 'logo' ) ) {
			return job_manager_get_resized_image( $logo, $size );
		}

		if ( $this->type && ( $default_logo = $this->type->get_default_logo( $size ) ) ) {
			return apply_filters( 'mylisting\listing\get_logo\default', $default_logo, $this );
		}

		return apply_filters( 'mylisting\listing\get_logo\default', '', $this );
	}

	public function get_cover_image( $size = 'large' ) {
		if ( $cover = $this->get_field( 'cover' ) ) {
			return job_manager_get_resized_image( $cover, $size );
		}

		if ( $this->type && ( $default_cover = $this->type->get_default_cover( $size ) ) ) {
			return apply_filters( 'mylisting\listing\get_cover_image\default', $default_cover, $this );
		}

		return apply_filters( 'mylisting\listing\get_cover_image\default', '', $this );
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

	public function get_link() {
		return get_permalink( $this->data );
	}

	public function get_schedule() {
		return $this->schedule;
	}

	public function get_type() {
		return $this->type;
	}

	public function get_author() {
		return $this->author;
	}

	public function get_rating() {
		return \MyListing\Reviews::get_listing_rating_optimized( $this->get_id() );
	}

	public function get_field( $key ) {
		if ( ! $this->type ) {
			return false;
		}

		if ( array_key_exists( $key, $this->special_keys ) ) {
			return $this->special_keys[ $key ];
		}

		if ( array_key_exists( $key, self::$aliases ) ) {
			return $this->get_field( self::$aliases[ $key ] );
		}

		if ( ! ( $field = $this->type->get_field( $key ) ) ) {
			return false;
		}

		$conditions = new Conditions( $field, $this->data );

		if ( ! $conditions->passes() ) {
			return false;
		}

		return $this->get_field_value( $field );
	}

	public function get_field_value( $field ) {
		if ( in_array( $field['type'], [ 'term-checklist', 'term-select', 'term-multiselect' ] ) ) {
    		$value = array_filter( (array) wp_get_object_terms(
    			$this->get_id(), $field['taxonomy'],
    			[ 'orderby' => 'term_order', 'order' => 'ASC' ])
    		);

    		if ( is_wp_error( $value ) ) {
    			$value = [];
    		}
		} elseif ( isset( $this->data->{$field['slug']} ) ) {
			$value = $this->data->{$field['slug']};
		} elseif ( isset( $this->data->{'_' . $field['slug']} ) ) {
			$value = $this->data->{'_' . $field['slug']};
		} else {
			$value = '';
		}

		if ( is_serialized( $value ) ) {
			$value = unserialize( $value );
		}

		return $value;
	}


	public function get_social_networks() {
		if ( ! $links = $this->get_field('links') ) {
			return [];
		}

		$networks = [];
		$allowed_networks = (array) mylisting()->schemes()->get('social-networks');

		foreach ( (array) $links as $link ) {
            if ( ! is_array( $link ) || empty( $link['network'] ) ) {
            	continue;
        	}

        	if ( empty( $link['url'] ) || ! isset( $allowed_networks[ $link['network'] ] ) ) {
        		continue;
        	}

        	$network = $allowed_networks[ $link['network'] ];
        	$network['link'] = $link['url'];

        	$networks[] = $network;
		}

		return array_filter( $networks );
	}


	/**
	 * Replace field tags with the actual field value.
	 * Example items to be replaced: [[tagline]] [[description]] [[twitter-id]]
	 *
	 * @since 1.5.0
	 * @param string $string String to replace values into.
	 * @return string
	 */
	public function compile_string( $string ) {
		preg_match_all('/\[\[+(?P<fields>.*?)\]\]/', $string, $matches);

		if ( empty( $matches['fields'] ) ) {
			return $string;
		}

		// Get all field values.
		$fields = [];
		foreach ( array_unique( $matches['fields'] ) as $slug ) {
			$fields[ $slug ] = '';

			if ( ( $value = $this->get_field( $slug ) ) ) {
				$value = apply_filters( 'mylisting\listing\compile_string\field', $value, $slug, $this );

				if ( is_array( $value ) ) {
					$value = join( ', ', $value );
				}

				// Escape square brackets so any shortcode added by the listing owner won't be run.
				$fields[ $slug ] = str_replace( [ "[" , "]" ] , [ "&#91;" , "&#93;" ] , $value );
			}
		}

		// Replace tags with field values.
		foreach ( $fields as $slug => $value ) {

			// If any of the used fields are empty, return false.
			if ( ! $value ) {
				return false;
			}

			$string = str_replace( "[[$slug]]", esc_attr( $value ), $string );
		}

		// Preserve line breaks.
		return $string;
	}

	/**
	 * Replace [[field]] with the field value in a string.
	 *
	 * @since 1.5.1
	 * @param string $string to replace [[field]] from.
	 * @param string $value that will replace [[field]].
	 * @return string
	 */
	public function compile_field_string( $string, $value ) {
		$string = str_replace( '[[field]]', c27()->esc_shortcodes( esc_attr( $value ) ), $string );

		return do_shortcode( $string );
	}

	public function get_preview_options() {
		// Get the preview template options for the listing type of the current listing.
		$options = $this->type ? $this->type->get_preview_options() : [];

   		// Merge with the default options, in case the listing type options meta returns null.
		return c27()->merge_options( mylisting()->schemes()->get('result'), $options );
	}

	public function setup_special_keys() {
		// @todo: Author data, e.g. :authorid, :authorname, and logged in user data, e.g. :currentuserid, :currentusername
		$this->special_keys = [
			':id'              => $this->get_id(),
			':url'             => $this->get_link(),
			':reviews-average' => $this->get_rating(),
			':reviews-mode'    => $this->type ? $this->type->get_review_mode() : 10,
			':reviews-count'   => get_comments_number(), // @todo: get top level comment count in an efficient way.
			':lat'             => $this->get_data('geolocation_lat'),
			':lng'             => $this->get_data('geolocation_long'),
		];
	}
}