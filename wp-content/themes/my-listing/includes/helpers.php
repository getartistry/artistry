<?php

class MyListingHelpers {

	protected static $_instance = null;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self;
		}

		return self::$_instance;
	}

    // Get theme template path, with the given $path appended to it.
	public function template_path( $path ) {
		return get_template_directory() . "/$path";
	}

    // Get theme template uri, with the given $uri appended to it.
	public function template_uri( $uri = '' ) {
		return get_template_directory_uri() . "/$uri";
	}

    // URI to asset folder.
	public function asset( $asset ) {
		return $this->template_uri( "assets/$asset" );
	}

    // URI to images folder.
	public function image( $image ) {
		return $this->asset( "images/$image" );
	}

    // Retrieve the featured_image url for the given post, on the given size.
	public function featured_image( $postID, $size = 'large' ) {
		$image = wp_get_attachment_image_src( get_post_thumbnail_id( $postID ), $size );
		return $image ? array_shift( $image ) : false;
	}

    // Get post terms from the given taxonomy.
	public function get_terms( $postID, $taxonomy = 'category' ) {
		$raw_terms = (array) wp_get_post_terms( $postID, $taxonomy );

		$terms = [];
		if ( ! empty( $raw_terms['errors'] ) ) {
			return $terms;
		}

		foreach ( $raw_terms as $raw_term ) {
			$terms[] = [
				'name' => $raw_term->name,
				'link' => get_term_link( $raw_term )
			];
		}

		return $terms;
	}

    // Debugging helper for displaying data in a formatted way.
	public function dump() {
		echo "<pre>";
		array_map( function( $x ) {
			var_dump( $x );
			echo "<br>";
		}, func_get_args() );
		echo "</pre>";
	}

    // Print the post excerpt, limiting it to a given number of characters.
	public function the_excerpt( $charlength, $after = "&hellip;" ) {
		$excerpt = get_the_excerpt();
		$charlength++;

		if ( mb_strlen( $excerpt ) > $charlength ) {
			$subex = mb_substr( $excerpt, 0, $charlength - 5 );
			$exwords = explode( ' ', $subex );
			$excut = - ( mb_strlen( $exwords[ count( $exwords ) - 1 ] ) ) - 1;
			if ( $excut < 0 ) {
				echo mb_substr( $subex, 0, $excut );
			} else {
				echo $subex;
			}
			echo $after;
		} else {
			echo $excerpt;
		}
	}

	public function the_text_excerpt( $text, $charlength, $after = "&hellip;" ) {
		$charlength++;

		if ( mb_strlen( $text ) > $charlength ) {
			$subex = mb_substr( $text, 0, $charlength - 5 );
			$exwords = explode( ' ', $subex );
			$excut = - ( mb_strlen( $exwords[ count( $exwords ) - 1 ] ) ) - 1;
			if ( $excut < 0 ) {
				echo mb_substr( $subex, 0, $excut );
			} else {
				echo $subex;
			}
			echo $after;
		} else {
			echo $text;
		}
	}

	public function merge_options( $defaults, $options ) {
		return array_replace_recursive( $defaults, $options );
	}

	public function get_partial( $template, $data = [] ) {
		if (!locate_template("partials/{$template}.php")) return;

		require locate_template("partials/{$template}.php");
	}

	public function get_section( $template, $data = [] ) {
		if (!locate_template("sections/{$template}.php")) return;

		require locate_template("sections/{$template}.php");
	}

	public function get_listing_type_options($listing_type, $options = ['fields', 'single', 'result', 'search', 'settings']) {
		$return_data = [];

		$listing_type = get_posts([
			'name' => $listing_type,
			'post_type' => 'case27_listing_type',
			'post_status' => 'publish',
			]);

		if (!$listing_type) {
			return false;
		}

		foreach ($options as $option) {
			if ($option == 'fields') {
				$return_data['fields'] = unserialize(get_post_meta($listing_type[0]->ID, 'case27_listing_type_fields', true));
			}

			if ($option == 'single') {
				$return_data['single'] = unserialize(get_post_meta($listing_type[0]->ID, 'case27_listing_type_single_page_options', true));
			}

			if ($option == 'result') {
				$return_data['result'] = unserialize(get_post_meta($listing_type[0]->ID, 'case27_listing_type_result_template', true));
			}

			if ($option == 'search') {
				$return_data['search'] = unserialize(get_post_meta($listing_type[0]->ID, 'case27_listing_type_search_page', true));
			}

			if ($option == 'settings') {
				$return_data['settings'] = unserialize(get_post_meta($listing_type[0]->ID, 'case27_listing_type_settings_page', true));
			}
		}

		return $return_data;
	}


	public function get_terms_dropdown_array($args = [], $key = 'term_id', $value = 'name') {
		$options = [];
		$terms = get_terms($args);

		if (is_wp_error($terms)) {
			return [];
		}

		foreach ((array) $terms as $term) {
			$options[$term->{$key}] = $term->{$value};
		}

		return $options;
	}


	public function get_posts_dropdown_array($args = [], $key = 'ID', $value = 'post_title') {
		$options = [];
		$posts = get_posts($args);

		foreach ((array) $posts as $term) {
			$options[$term->{$key}] = $term->{$value};
		}

		return $options;
	}

	public function get_icon_markup($icon_string)
	{
		// For icon fonts that require the icon name to be the contents of the <i> tag,
		// provide a string that can be exploded into two parts by '://', and use the
		// first part as the tag's class name, and the second part as the contents
		// of the tag. Example: material-icons://view_headline
		if (strpos($icon_string, '://') !== false) {
			$icon_arr = explode('://', $icon_string);

			return "<i class=\"{$icon_arr[0]}\">{$icon_arr[1]}</i>";
		}

		return "<i class=\"{$icon_string}\"></i>";
	}


	public function get_setting( $setting, $default = '' ) {
		return function_exists('get_field') && get_field($setting, 'option') !== null ? get_field($setting, 'option') : $default;
	}


	public function get_site_logo()
	{
		if ($logo_obj = c27()->get_setting('general_site_logo')) {
			return $logo_obj['sizes']['medium'];
		}

		return '';
	}


	public function upload_file($file, $allowed_mime_types = [])
	{
		include_once( ABSPATH . 'wp-admin/includes/file.php' );
		include_once( ABSPATH . 'wp-admin/includes/media.php' );
		include_once( ABSPATH . 'wp-admin/includes/image.php' );

		$uploaded_file = new \stdClass();

		if ( ! in_array( $file['type'], $allowed_mime_types ) ) {
			return new \WP_Error( 'upload', sprintf( __( 'Uploaded files need to be one of the following file types: %s', 'my-listing' ), implode( ', ', array_keys( $allowed_mime_types ) ) ) );
		}

		$upload = wp_handle_upload($file, ['test_form' => false]);

		if ( ! empty( $upload['error'] ) ) {
			return new \WP_Error( 'upload', $upload['error'] );
		}

		$wp_filetype = wp_check_filetype($upload['file']);
		$attach_id = wp_insert_attachment([
			'post_mime_type' => $wp_filetype['type'],
			'post_title' => sanitize_file_name($upload['file']),
			'post_content' => '',
			'post_status' => 'inherit'
			], $upload['file']);

		$attach_data = wp_generate_attachment_metadata($attach_id, $upload['file']);
		wp_update_attachment_metadata( $attach_id, $attach_data );

		return $attach_id;
	}

	public function get_gradients() {
		return [
	    		'gradient1' => ['from' => '#7dd2c7', 'to' => '#f04786'],
				'gradient2' => ['from' => '#71d68b', 'to' => '#00af9c'],
				'gradient3' => ['from' => '#FF5F6D', 'to' => '#FFC371'],
				'gradient4' => ['from' => '#EECDA3', 'to' => '#EF629F'],
				'gradient5' => ['from' => '#114357', 'to' => '#F29492'],
				'gradient6' => ['from' => '#52EDC7', 'to' => '#F29492'],
				'gradient7' => ['from' => '#C644FC', 'to' => '#5856D6'],
	    	];
	}

	public function get_map_skins() {
		return [
			'skin12' => _x( 'Default', 'Google Maps Skin #', 'my-listing' ),
			'skin1' => _x( 'Skin 1', 'Google Maps Skin #', 'my-listing' ),
			'skin2' => _x( 'Skin 2', 'Google Maps Skin #', 'my-listing' ),
			'skin3' => _x( 'Skin 3', 'Google Maps Skin #', 'my-listing' ),
			'skin4' => _x( 'Skin 4', 'Google Maps Skin #', 'my-listing' ),
			'skin5' => _x( 'Skin 5', 'Google Maps Skin #', 'my-listing' ),
			'skin6' => _x( 'Skin 6', 'Google Maps Skin #', 'my-listing' ),
			'skin7' => _x( 'Skin 7', 'Google Maps Skin #', 'my-listing' ),
			'skin8' => _x( 'Skin 8', 'Google Maps Skin #', 'my-listing' ),
			'skin9' => _x( 'Skin 9', 'Google Maps Skin #', 'my-listing' ),
			'skin10' => _x( 'Skin 10', 'Google Maps Skin #', 'my-listing' ),
			'skin11' => _x( 'Skin 11', 'Google Maps Skin #', 'my-listing' ),
		];
	}

	public function new_admin_page( $type = 'menu', $args = [] ) {
		if ( ! in_array( $type, [ 'menu', 'submenu', 'theme' ] ) ) return;

		call_user_func_array('add_' . $type . '_page', $args);
	}

	public function hexToRgb( $hex, $alpha = 1 ) {
		$rgb = [];

		if ( strpos( $hex, 'rgb' ) !== false ) {
			$hex = str_replace( ['rgba', 'rgb', '(', ')', ' '], '', $hex );
			$hexArr = explode( ',', $hex );

			$rgb['r'] = isset( $hexArr[0] ) ? absint( $hexArr[0] ) : 0;
			$rgb['g'] = isset( $hexArr[1] ) ? absint( $hexArr[1] ) : 0;
			$rgb['b'] = isset( $hexArr[2] ) ? absint( $hexArr[2] ) : 0;
			$rgb['a'] = isset( $hexArr[3] ) ? (float) $hexArr[3] : 1;

			return $rgb;
		}

		$hex      = str_replace( '#', '', $hex );
		$length   = strlen( $hex );
		$rgb['r'] = hexdec( $length == 6 ? substr( $hex, 0, 2 ) : ( $length == 3 ? str_repeat( substr( $hex, 0, 1 ), 2 ) : 0 ) );
		$rgb['g'] = hexdec( $length == 6 ? substr( $hex, 2, 2 ) : ( $length == 3 ? str_repeat( substr( $hex, 1, 1 ), 2 ) : 0 ) );
		$rgb['b'] = hexdec( $length == 6 ? substr( $hex, 4, 2 ) : ( $length == 3 ? str_repeat( substr( $hex, 2, 1 ), 2 ) : 0 ) );
		$rgb['a'] = $alpha;

		return $rgb;
	}

	public function getVideoEmbedUrl( $url )
	{
		// Check if youtube
		$rx = '~^(?:https?://)?(?:www\.)?(?:youtube\.com|youtu\.be)/watch\?v=(?<id>[^&]+)~x';
		preg_match($rx, $url, $matches);
		if (isset($matches['id']) && trim($matches['id']) != "") {
			return ['url' => "https://www.youtube.com/embed/{$matches['id']}?origin=*", 'type' => 'external', 'service' => 'youtube', 'video_id' => $matches['id']];
		}

		// Check if vimeo
		$rx = "/(https?:\/\/)?(www\.)?(player\.)?vimeo\.com\/([a-z]*\/)*(?<id>[0-9]{6,11})[?]?.*/";
		preg_match($rx, $url, $matches);
		if (isset($matches['id']) && trim($matches['id']) != "") {
			return ['url' => "https://player.vimeo.com/video/{$matches['id']}?api=1&player_id=".$matches['id'], 'type' => 'external', 'service' => 'vimeo', 'video_id' => $matches['id']];
		}

		// Check if dailymotion
		$rx = "/^.+dailymotion.com\/(video|hub)\/(?<id>[^_]+)[^#]*(#video=(?<id2>[^_&]+))?/";
		preg_match($rx, $url, $matches);
		if (isset($matches['id']) && trim($matches['id']) != "") {
			return ['url' => "https://www.dailymotion.com/embed/video/{$matches['id']}", 'type' => 'external', 'service' => 'dailymotion', 'video_id'=>$matches['id']];
		}

		return false;
	}

	/**
	 * Escape WordPress shortcode brackets.
	 * Used mainly to sanitize user input.
	 *
	 * @since 1.5.1
	 * @param string $value String to escape.
	 * @return string
	 */
	public function esc_shortcodes( $value ) {
		return str_replace( [ "[" , "]" ] , [ "&#91;" , "&#93;" ] , $value );
	}

	public function get_timezone() {
		$timezone_string = get_option( 'timezone_string' );
		if ( ! empty( $timezone_string ) ) {
			return new \DateTimeZone( $timezone_string );
		}

		$offset  = get_option( 'gmt_offset' );
		$hours   = (int) $offset;
		$minutes = abs( ( $offset - (int) $offset ) * 60 );
		$offset  = sprintf( '%+03d:%02d', $hours, $minutes );

		return new \DateTimeZone( $offset );
	}
}

/*
 * Create c27() shorthand method to call MyListingHelpers class methods.
 */
function c27( $className = null ) {
	if ( $className && class_exists( "CASE27_{$className}" ) ) {
		if ( ( $cls = "CASE27_{$className}" ) && method_exists( $cls, 'instance' ) ) {
			return $cls::instance();
		}
	}

	return MyListingHelpers::instance();
}

/*
 * Create dump() shorthand to call c27()->dump($value).
 */
if (!function_exists('dump')) {
	function dump() {
		foreach (func_get_args() as $arg) {
			c27()->dump($arg);
		}
	}
}

if (!function_exists('dd')) {
	function dd() {
		foreach (func_get_args() as $arg) {
			c27()->dump($arg);
		}

		die;
	}
}
