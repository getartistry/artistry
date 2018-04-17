<?php

class Meow_WPMC_Core {

	public $checkers = null;
	public $admin = null;
	public $last_analysis = null;
	public $last_analysis_ids = null;
	private $regex_file = '/[A-Za-z0-9-_,\s]+[.]{1}(MIMETYPES)/';
	public $current_method = 'media';
	private $refcache = array();

	public function __construct( $admin ) {
		$this->admin = $admin;
		add_action( 'admin_init', array( $this, 'admin_init' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
		add_action( 'admin_print_scripts', array( $this, 'admin_inline_js' ) );
		add_action( 'wp_ajax_wpmc_scan', array( $this, 'wp_ajax_wpmc_scan' ) );
		add_action( 'wp_ajax_wpmc_get_all_issues', array( $this, 'wp_ajax_wpmc_get_all_issues' ) );
		add_action( 'wp_ajax_wpmc_get_all_deleted', array( $this, 'wp_ajax_wpmc_get_all_deleted' ) );
		add_action( 'wp_ajax_wpmc_scan_do', array( $this, 'wp_ajax_wpmc_scan_do' ) );
		add_action( 'wp_ajax_wpmc_prepare_do', array( $this, 'wp_ajax_wpmc_prepare_do' ) );
		add_action( 'wp_ajax_wpmc_delete_do', array( $this, 'wp_ajax_wpmc_delete_do' ) );
		add_action( 'wp_ajax_wpmc_ignore_do', array( $this, 'wp_ajax_wpmc_ignore_do' ) );
		add_action( 'wp_ajax_wpmc_recover_do', array( $this, 'wp_ajax_wpmc_recover_do' ) );
		add_filter( 'media_row_actions', array( $this, 'media_row_actions' ), 10, 2 );
		add_action( 'add_meta_boxes', array( $this, 'add_metabox' ) );
	}

	function admin_init() {
		$this->current_method = get_option( 'wpmc_method', 'media' );
		$types = "jpg|jpeg|jpe|gif|png|tiff|bmp|csv|pdf|xls|xlsx|doc|docx|tiff|mp3|mp4|wav|lua";
		$this->regex_file  = str_replace( "MIMETYPES", $types, $this->regex_file );
		require( 'scan.php' );
		require( 'checkers.php' );
		new MeowApps_WPMC_Scan( $this );
		$this->checkers = new Meow_WPMC_Checkers( $this );
 	}

	/*******************************************************************************
	 * METABOX FOR USAGE
	 ******************************************************************************/

	function add_metabox() {
 		add_meta_box( 'mfrh_media_usage_box', 'Media Cleaner', array( $this, 'display_metabox' ), 'attachment', 'side', 'default' );
 	}

	function display_metabox( $post ) {
		$this->log( "Media Edit > Checking Media #{$post->ID}" );
		$success = $this->wpmc_check_media( $post->ID, true );
		$this->log( "Success $success\n" );
		if ( $success ) {
			if ( $this->last_analysis == "CONTENT" ) {
				echo "Found in content.";
			}
			else if ( $this->last_analysis == "CONTENT (ID)" ) {
				echo "Found in content (as an ID).";
			}
			else if ( $this->last_analysis == "CONTENT (URL)" ) {
				echo "Found in content (as an URL).";
			}
			else if ( $this->last_analysis == "THEME" ) {
				echo "Found in theme.";
			}
			else if ( $this->last_analysis == "PAGE BUILDER" ) {
				echo "Found in Page Builder.";
			}
			else if ( $this->last_analysis == "GALLERY" ) {
				echo "Found in gallery.";
			}
			else if ( $this->last_analysis == "META" ) {
				echo "Found in meta.";
			}
			else if ( $this->last_analysis == "META (ID)" ) {
				echo "Found in meta (as an ID).";
			}
			else if ( $this->last_analysis == "META (URL)" ) {
				echo "Found in meta (as an URL).";
			}
			else if ( $this->last_analysis == "META ACF (ID)" ) {
				echo "Found in ACF meta (as an ID).";
			}
			else if ( $this->last_analysis == "META ACF (URL)" ) {
				echo "Found in ACF meta (as an URL).";
			}
			else if ( $this->last_analysis == "WIDGET" ) {
				echo "Found in widget.";
			}
			else {
				echo "It seems to be used as: " . $this->last_analysis;
			}
		}
		else {
			echo "Doesn't seem to be used.";
		}
	}

	/*******************************************************************************
	 * ASYNCHRONOUS AJAX FUNCTIONS
	 ******************************************************************************/

	function wp_ajax_wpmc_delete_do () {
		ob_start();
		$data = $_POST['data'];
		$success = 0;
		foreach ( $data as $piece ) {
			$success += ( $this->wpmc_delete( $piece ) ? 1 : 0 );
		}
		ob_end_clean();
		echo json_encode(
			array(
				'success' => true,
				'result' => array( 'data' => $data, 'success' => $success ),
				'message' => __( "Status unknown.", 'media-cleaner' )
			)
		);
		die();
	}

	function wp_ajax_wpmc_ignore_do () {
		ob_start();
		$data = $_POST['data'];
		$success = 0;
		foreach ( $data as $piece ) {
			$success += ( $this->wpmc_ignore( $piece ) ? 1 : 0 );
		}
		ob_end_clean();
		echo json_encode(
			array(
				'success' => true,
				'result' => array( 'data' => $data, 'success' => $success ),
				'message' => __( "Status unknown.", 'media-cleaner' )
			)
		);
		die();
	}

	function wp_ajax_wpmc_recover_do () {
		ob_start();
		$data = $_POST['data'];
		$success = 0;
		foreach ( $data as $piece ) {
			$success +=  ( $this->wpmc_recover( $piece ) ? 1 : 0 );
		}
		ob_end_clean();
		echo json_encode(
			array(
				'success' => true,
				'result' => array( 'data' => $data, 'success' => $success ),
				'message' => __( "Status unknown.", 'media-cleaner' )
			)
		);
		die();
	}

	function deepsleep( $seconds ) {
		$start_time = time();
		while( true ) {
			if ( ( time() - $start_time ) > $seconds ) {
				return false;
			}
			get_post( array( 'posts_per_page' => 50 ) );
		}
	}

	private $start_time;
	private $time_elapsed = 0;
	private $item_scan_avg_time = 0;
	private $wordpress_init_time = 0.5;
	private $max_execution_time;
	private $items_checked = 0;
	private $items_count = 0;

	function timeout_check_start( $count ) {
		$this->start_time = time();
		$this->items_count = $count;
		$this->max_execution_time = ini_get( "max_execution_time" );
		if ( empty( $this->max_execution_time ) )
			$this->max_execution_time = 30;
	}

	function timeout_check() {
		$this->time_elapsed = time() - $this->start_time;
		$this->time_remaining = $this->max_execution_time - $this->wordpress_init_time - $this->time_elapsed;
		if ( $this->time_remaining - $this->item_scan_avg_time < 0 ) {
			error_log("Media Cleaner Timeout! Check the Media Cleaner logs for more info.");
			$this->log( "Timeout! Some info for debug:" );
			$this->log( "Elapsed time: $this->time_elapsed" );
			$this->log( "WP init time: $this->wordpress_init_time" );
			$this->log( "Remaining time: $this->time_remaining" );
			$this->log( "Scan time per item: $this->item_scan_avg_time" );
			$this->log( "PHP max_execution_time: $this->max_execution_time" );
			header("HTTP/1.0 408 Request Timeout");
			exit;
		}
	}

	function timeout_check_additem() {
		$this->items_checked++;
		$this->time_elapsed = time() - $this->start_time;
		$this->item_scan_avg_time = ceil( ( $this->time_elapsed / $this->items_checked ) * 10 ) / 10;
	}

	function wp_ajax_wpmc_prepare_do() {
		$limit = isset( $_POST['limit'] ) ? $_POST['limit'] : 0;
		$limitsize = get_option( 'wpmc_posts_buffer', 5 );
		if ( empty( $limit ) )
			$this->wpmc_reset_issues();

		$method = get_option( 'wpmc_method', 'media' );
		$check_library = get_option(' wpmc_media_library', true );
		$check_postmeta = get_option( 'wpmc_postmeta', false );
		$check_posts = get_option( 'wpmc_posts', false );
		$check_widgets = get_option( 'wpmc_widgets', false );
		if ( $method == 'media' && !$check_posts && !$check_postmeta && !$check_widgets ) {
			echo json_encode( array(
				'results' => array(),
				'success' => true,
				'finished' => true,
				'message' => __( "Posts, Meta and Widgets analysis are all off. Done.", 'media-cleaner' )
			) );
			die();
		}
		if ( $method == 'files' && $check_library && !$check_posts && !$check_postmeta && !$check_widgets ) {
			echo json_encode( array(
				'results' => array(),
				'success' => true,
				'finished' => true,
				'message' => __( "Posts, Meta and Widgets analysis are all off. Done.", 'media-cleaner' )
			) );
			die();
		}

		global $wpdb;
		// Maybe we could avoid to check more post_types.
		// SELECT post_type, COUNT(*) FROM `wp_posts` GROUP BY post_type
		$posts = $wpdb->get_col( $wpdb->prepare( "SELECT p.ID FROM $wpdb->posts p
			WHERE p.post_status != 'inherit'
			AND p.post_status != 'trash'
			AND p.post_type != 'attachment'
			AND p.post_type != 'shop_order'
			AND p.post_type != 'shop_order_refund'
			AND p.post_type != 'nav_menu_item'
			AND p.post_type != 'revision'
			AND p.post_type != 'auto-draft'
			AND p.post_type != 'wphb_minify_group'
			AND p.post_type != 'customize_changeset'
			AND p.post_type != 'oembed_cache'
			AND p.post_type NOT LIKE '%acf-%'
			AND p.post_type NOT LIKE '%edd_%'
			LIMIT %d, %d", $limit, $limitsize
			)
		);

		$found = array();

		// Only at the beginning
		if ( empty( $limit ) ) {

			$this->log( "Analyzing for references:" );

			$theme_ids = array();
			$theme_urls = array();
			$this->get_images_from_themes( $theme_ids, $theme_urls );
			$this->add_reference_id( $theme_ids, 'THEME' );
			$this->add_reference_url( $theme_urls, 'THEME' );

			// Only on Start: Analyze Widgets
			if ( get_option( 'wpmc_widgets', false ) ) {
				$widgets_ids = array();
				$widgets_urls = array();
				$this->get_images_from_widgets( $widgets_ids, $widgets_urls );
				$this->add_reference_id( $widgets_ids, 'WIDGET' );
				$this->add_reference_url( $widgets_urls, 'WIDGET' );
			}

			// Only on Start: Analyze WooCommerce Categories Images
			if ( class_exists( 'WooCommerce' ) ) {
				$metas = $wpdb->get_col( "SELECT meta_value
					FROM $wpdb->termmeta
					WHERE meta_key LIKE '%thumbnail_id%'"
				);
				if ( count( $metas ) > 0 ) {
					$postmeta_images_ids = array();
					foreach ( $metas as $meta )
						if ( is_numeric( $meta ) && $meta > 0 )
							array_push( $postmeta_images_ids, $meta );
					$this->add_reference_id( $postmeta_images_ids, 'META (ID)' );
				}
			}
		}

		$this->timeout_check_start( count( $posts ) );

		foreach ( $posts as $post ) {
			$this->timeout_check();
			// Run the scanners
			if ( $check_postmeta )
				do_action( 'wpmc_scan_postmeta', $post );
			if ( $check_posts ) {
				// Get HTML for this post
				$html = get_post_field( 'post_content', $post );
				// Scan on the raw TML content
				do_action( 'wpmc_scan_post', $html, $post );
				$html = do_shortcode( $html );
				$html = wp_make_content_images_responsive( $html );
				// Scan with shortcodes resolved and src-set
				do_action( 'wpmc_scan_post', $html, $post );
			}
			$this->timeout_check_additem();
		}
		// Write the references cached by the scanners
		$this->write_references();

		$finished = count( $posts ) < $limitsize;
		if ( $finished ) {
			$found = array();
			// Optimize DB (but that takes too long!)
			//$table_name = $wpdb->prefix . "mclean_refs";
			// $wpdb->query ("DELETE a FROM $table_name as a, $table_name as b
			// WHERE (a.mediaId = b.mediaId OR a.mediaId IS NULL AND b.mediaId IS NULL)
			// AND (a.mediaUrl = b.mediaUrl OR a.mediaUrl IS NULL AND b.mediaUrl IS NULL)
			// AND (a.originType = b.originType OR a.originType IS NULL AND b.originType IS NULL)
			// AND (a.origin = b.origin OR a.origin IS NULL AND b.origin IS NULL)
			// AND a.ID < b.ID;" );
			// $wpdb->query ("DELETE a FROM $table_name as a, $table_name as b WHERE a.mediaId = b.mediaId AND a.mediaId > 0 AND a.ID < b.ID;" );
			// $wpdb->query ("DELETE a FROM $table_name as a, $table_name as b WHERE a.mediaUrl = b.mediaUrl AND LENGTH(a.mediaUrl) > 1 AND a.ID < b.ID;" );
		}
		if ( $finished && get_option( 'wpmc_debuglogs', false ) ) {
			//$this->log( print_r( $found, true ) );
		}
		echo json_encode(
			array(
				'success' => true,
				'finished' => $finished,
				'limit' => $limit + $limitsize,
				'message' => __( "Posts checked.", 'media-cleaner' ) )
		);
		die();
	}

	function wp_ajax_wpmc_scan_do () {
		// For debug, to pretend there is a timeout
		//$this->deepsleep(10);
		//header("HTTP/1.0 408 Request Timeout");
		//exit;
		
		ob_start();
		$type = $_POST['type'];
		$data = $_POST['data'];
		$this->timeout_check_start( count( $data ) );
		$success = 0;
		foreach ( $data as $piece ) {
			$this->timeout_check();
			if ( $type == 'file' ) {
				$this->log( "Check File: {$piece}" );
				$result = ( apply_filters( 'wpmc_check_file', true, $piece ) ? 1 : 0 );
				$this->log( "Success " . $result . "\n" );
				$success += $result;
			} 
			else if ( $type == 'media' ) {
				$this->log( "Checking Media #{$piece}" );
				$result = ( $this->wpmc_check_media( $piece ) ? 1 : 0 );
				$this->log( "Success " . $result . "\n" );
				$success += $result;
			}
			$this->timeout_check_additem();
		}
		ob_end_clean();
		echo json_encode(
			array(
				'success' => true,
				'result' => array( 'type' => $type, 'data' => $data, 'success' => $success ),
				'message' => __( "Items checked.", 'media-cleaner' )
			)
		);
		die();
	}

	function wp_ajax_wpmc_get_all_deleted () {
		global $wpdb;
		$table_name = $wpdb->prefix . "mclean_scan";
		$ids = $wpdb->get_col( "SELECT id FROM $table_name WHERE ignored = 0 AND deleted = 1" );
		echo json_encode(
			array(
				'results' => array( 'ids' => $ids ),
				'success' => true,
				'message' => __( "List generated.", 'media-cleaner' )
			)
		);
		die;
	}

	function wp_ajax_wpmc_get_all_issues () {
		global $wpdb;
		$isTrash = ( isset( $_POST['isTrash'] ) && $_POST['isTrash'] == 1 ) ? true : false;
		$table_name = $wpdb->prefix . "mclean_scan";
		if ( $isTrash )
			$ids = $wpdb->get_col( "SELECT id FROM $table_name WHERE ignored = 0 AND deleted = 1" );
		else
			$ids = $wpdb->get_col( "SELECT id FROM $table_name WHERE ignored = 0 AND deleted = 0" );
		echo json_encode(
			array(
				'results' => array( 'ids' => $ids ),
				'success' => true,
				'message' => __( "List generated.", 'media-cleaner' )
			)
		);
		die;
	}

	function array_to_ids_or_urls( &$meta, &$ids, &$urls ) {
		foreach ( $meta as $k => $m ) {
			if ( is_numeric( $m ) ) {
				// Probably a Media ID
				if ( $m > 0 )
					array_push( $ids, $m );
			}
			else if ( is_array( $m ) ) {
				// If it's an array with a width, probably that the index is the Media ID
				if ( isset( $m['width'] ) && is_numeric( $k ) ) {
					if ( $k > 0 )
						array_push( $ids, $k );
				}
			}
			else if ( !empty( $m ) ) {
				// If it's a string, maybe it's a file (with an extension)
				if ( preg_match( $this->regex_file, $m ) )
					array_push( $urls, $m );
			}
		}
	}

	function get_images_from_widgets( &$ids, &$urls ) {
		global $wp_registered_widgets;
		$syswidgets = $wp_registered_widgets;
		$active_widgets = get_option( 'sidebars_widgets' );
		foreach ( $active_widgets as $sidebar_name => $widgets ) {
			if ( $sidebar_name != 'wp_inactive_widgets' && !empty( $widgets ) && is_array( $widgets ) ) {
				$i = 0;
				foreach ( $widgets as $key => $widget ) {
					$widget_class = $syswidgets[$widget]['callback'][0]->option_name;
					$instance_id = $syswidgets[$widget]['params'][0]['number'];
					$widget_data = get_option( $widget_class );
					// error_log( "INSTANCE $key ($instance_id)" );
					// error_log( print_r( $widget_data, 1 ) );
					if ( !empty( $widget_data[$instance_id]['text'] ) ) {
						$html = do_shortcode( $widget_data[$instance_id]['text'] );
						$urls = array_merge( $urls, $this->get_urls_from_html( $html ) );
					}
					if ( !empty( $widget_data[$instance_id]['attachment_id'] ) ) {
						$id = $widget_data[$instance_id]['attachment_id'];
						array_push( $ids, $id );
					}
					if ( !empty( $widget_data[$instance_id]['url'] ) ) {
						$url = $this->wpmc_clean_url( $widget_data[$instance_id]['url'] );
						array_push( $urls, $url );
					}
					if ( !empty( $widget_data[$instance_id]['ids'] ) ) {
						$newIds = $widget_data[$instance_id]['ids'];
						$ids = array_merge( $ids, $newIds );
					}
					$i++;
				}
			}
		}
	}

	function get_urls_from_html( $html ) {
		if ( empty( $html ) )
			return array();
		libxml_use_internal_errors( false );

		$dom = new DOMDocument();
		@$dom->loadHTML( $html );

		// Images, src, srcset
		$imgs = $dom->getElementsByTagName( 'img' );
		$results = array();
		foreach ( $imgs as $img ) {
			$src = $this->wpmc_clean_url( $img->getAttribute('src') );
    	array_push( $results, $src );
			$srcset = $img->getAttribute('srcset');
			if ( !empty( $srcset ) ) {
				$setImgs = explode( ',', trim( $srcset ) );
				foreach ( $setImgs as $setImg ) {
					$finalSetImg = explode( ' ', trim( $setImg ) );
					if ( is_array( $finalSetImg ) ) {
						array_push( $results, $this->wpmc_clean_url( $finalSetImg[0] ) );
					}
				}
			}
		}

		// Links, href
		$urls = $dom->getElementsByTagName( 'a' );
		foreach ( $urls as $url ) {
			$src = $this->wpmc_clean_url( $url->getAttribute('href') );
    	array_push( $results, $src );
		}

		// PDF
		preg_match_all( "/((https?:\/\/)?[^\\&\#\[\] \"\?]+\.pdf)/", $html, $res );
		if ( !empty( $res ) && isset( $res[1] ) && count( $res[1] ) > 0 ) {
			foreach ( $res[1] as $url ) {
				error_log(print_r($url, 1));
				array_push( $results, $this->wpmc_clean_url( $url ) );
			}
		}

		// Background images
		preg_match_all( "/url\(\'?\"?((https?:\/\/)?[^\\&\#\[\] \"\?]+\.(jpe?g|gif|png))\'?\"?/", $html, $res );
		if ( !empty( $res ) && isset( $res[1] ) && count( $res[1] ) > 0 ) {
			foreach ( $res[1] as $url ) {
				//error_log(print_r($res, 1));
				array_push( $results, $this->wpmc_clean_url( $url ) );
			}
		}

		return $results;
	}

	// Parse a meta, visit all the arrays, look for the attributes, fill $ids and $urls arrays
	function get_from_meta( $meta, $lookFor, &$ids, &$urls ) {
		foreach ( $meta as $key => $value ) {
			if ( is_object( $value ) || is_array( $value ) )
				$this->get_from_meta( $value, $lookFor, $ids, $urls );
			else if ( in_array( $key, $lookFor ) ) {
				if ( empty( $value ) )
					continue;
				else if ( is_numeric( $value ) )
					array_push( $ids, $value );
				else
					array_push( $urls, $this->wpmc_clean_url( $value ) );
			}
		}
	}

	function get_images_from_themes( &$ids, &$urls ) {
		global $wpdb;

		// USE CURRENT THEME AND WP API
		$ch = get_custom_header();
		if ( !empty( $ch ) && !empty( $ch->url ) ) {
			array_push( $urls, $this->wpmc_clean_url( $ch->url ) );
		}
		if ( !empty( $ch ) && !empty( $ch->attachment_id ) ) {
			array_push( $ids, $ch->attachment_id );
		}
		$cl = get_custom_logo();
		if ( !empty( $cl ) ) {
			$urls = array_merge( $this->get_urls_from_html( $cl ), $urls );
		}
		$cd = get_background_image();
		if ( !empty( $cd ) ) {
			array_push( $urls, $this->wpmc_clean_url( $cd ) );
		}
		$photography_hero_image = get_theme_mod( 'photography_hero_image' );
		if ( !empty( $photography_hero_image ) ) {
			array_push( $ids, $photography_hero_image );
		}
		$author_profile_picture = get_theme_mod( 'author_profile_picture' );
		if ( !empty( $author_profile_picture ) ) {
			array_push( $ids, $author_profile_picture );
		}
	}

	function log( $data, $force = false ) {
		if ( !get_option( 'wpmc_debuglogs', false ) && !$force )
			return;
		$fh = @fopen( trailingslashit( dirname(__FILE__) ) . '/media-cleaner.log', 'a' );
		if ( !$fh )
			return false;
		$date = date( "Y-m-d H:i:s" );
		fwrite( $fh, "$date: {$data}\n" );
		fclose( $fh );
		return true;
	}

	function wp_ajax_wpmc_scan() {
		global $wpdb;

		$method = get_option( 'wpmc_method', 'media' );
		if ( !$this->admin->is_registered() )
			$method = 'media';
		$path = isset( $_POST['path'] ) ? $_POST['path'] : null;
		$limit = isset( $_POST['limit'] ) ? $_POST['limit'] : 0;
		$limitsize = get_option( 'wpmc_medias_buffer', 100 );

		if ( $method == 'files' ) {
			$output = apply_filters( 'wpmc_list_uploaded_files', array(
				'results' => array(), 'success' => false, 'message' => __( "Unavailable.", 'media-cleaner' )
			), $path );
			echo json_encode( $output );
			die();
		}

		if ( $method == 'media' ) {
			// Prevent double scanning by removing filesystem entries that we have DB entries for
			$results = $wpdb->get_col( $wpdb->prepare( "SELECT p.ID FROM $wpdb->posts p
				WHERE p.post_status = 'inherit'
				AND p.post_type = 'attachment'
				LIMIT %d, %d", $limit, $limitsize
				)
			);
			$finished = count( $results ) < $limitsize;
			echo json_encode(
				array(
					'results' => $results,
					'success' => true,
					'finished' => $finished,
					'limit' => $limit + $limitsize,
					'message' => __( "Medias retrieved.", 'media-cleaner' ) )
			);
			die();
		}

		// No task.
		echo json_encode( array( 'success' => false, 'message' => __( "No task.", 'media-cleaner' ) ) );
		die();
	}

	/**
	 *
	 * HELPERS
	 *
	 */

	function wpmc_trashdir() {
		$upload_folder = wp_upload_dir();
		return trailingslashit( $upload_folder['basedir'] ) . 'wpmc-trash';
	}

	/**
	 *
	 * DELETE / SCANNING / RESET
	 *
	 */

	function wpmc_recover_file( $path ) {
		$basedir = wp_upload_dir();
		$originalPath = trailingslashit( $basedir['basedir'] ) . $path;
		$trashPath = trailingslashit( $this->wpmc_trashdir() ) . $path;
		$path_parts = pathinfo( $originalPath );
		if ( !file_exists( $path_parts['dirname'] ) && !wp_mkdir_p( $path_parts['dirname'] ) ) {
			die( 'Failed to create folder.' );
		}
		if ( !file_exists( $trashPath ) ) {
			$this->log( "The file $originalPath actually does not exist in the trash." );
			return true;
		}
		if ( !rename( $trashPath, $originalPath ) ) {
			die( 'Failed to move the file.' );
		}
		return true;
	}

	function wpmc_recover( $id ) {
		global $wpdb;
		$table_name = $wpdb->prefix . "mclean_scan";
		$issue = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_name WHERE id = %d", $id ), OBJECT );
		$issue->path = stripslashes( $issue->path );

		// Files
		if ( $issue->type == 0 ) {
			$this->wpmc_recover_file( $issue->path );
			$wpdb->query( $wpdb->prepare( "UPDATE $table_name SET deleted = 0 WHERE id = %d", $id ) );
			return true;
		}
		// Media
		else if ( $issue->type == 1 ) {

			// Copy the main file back
			$fullpath = get_attached_file( $issue->postId );
			if ( empty( $fullpath ) ) {
				error_log( "Media {$issue->postId} does not have attached file anymore." );
				return false;
			}
			$mainfile = $this->wpmc_clean_uploaded_filename( $fullpath );
			$baseUp = pathinfo( $mainfile );
			$baseUp = $baseUp['dirname'];
			$file = $this->wpmc_clean_uploaded_filename( $fullpath );
			if ( !$this->wpmc_recover_file( $file ) ) {
				$this->log( "Could not recover $file." );
				error_log( "Media Cleaner: Could not recover $file." );
			}

			// If images, copy the other files as well
			$meta = wp_get_attachment_metadata( $issue->postId );
			$isImage = isset( $meta, $meta['width'], $meta['height'] );
			$sizes = $this->wpmc_get_image_sizes();
			if ( $isImage && isset( $meta['sizes'] ) ) {
				foreach ( $meta['sizes'] as $name => $attr ) {
					if  ( isset( $attr['file'] ) ) {
						$filepath = wp_upload_dir();
						$filepath = $filepath['basedir'];
						$filepath = trailingslashit( $filepath ) . trailingslashit( $baseUp ) . $attr['file'];
						$file = $this->wpmc_clean_uploaded_filename( $filepath );
						if ( !$this->wpmc_recover_file( $file ) ) {
							$this->log( "Could not recover $file." );
							error_log( "Media Cleaner: Could not recover $file." );
						}
					}
				}
			}
			if ( !wp_untrash_post( $issue->postId ) ) {
				error_log( "Cleaner: Failed to Untrash Post {$issue->postId} (but deleted it from Cleaner DB)." );
			}
			$wpdb->query( $wpdb->prepare( "UPDATE $table_name SET deleted = 0 WHERE id = %d", $id ) );
			return true;
		}
	}

	function wpmc_trash_file( $fileIssuePath ) {
		global $wpdb;
		$basedir = wp_upload_dir();
		$originalPath = trailingslashit( $basedir['basedir'] ) . $fileIssuePath;
		$trashPath = trailingslashit( $this->wpmc_trashdir() ) . $fileIssuePath;
		$path_parts = pathinfo( $trashPath );

		try {
			if ( !file_exists( $path_parts['dirname'] ) && !wp_mkdir_p( $path_parts['dirname'] ) ) {
				$this->log( "Could not create the trash directory for Media Cleaner." );
				error_log( "Media Cleaner: Could not create the trash directory." );
				return false;
			}
			// Rename the file (move). 'is_dir' is just there for security (no way we should move a whole directory)
			if ( is_dir( $originalPath ) ) {
				$this->log( "Attempted to delete a directory instead of a file ($originalPath). Can't do that." );
				error_log( "Media Cleaner: Attempted to delete a directory instead of a file ($originalPath). Can't do that." );
				return false;
			}
			if ( !file_exists( $originalPath ) ) {
				$this->log( "The file $originalPath actually does not exist." );
				return true;
			}
			if ( !@rename( $originalPath, $trashPath ) ) {
				return false;
			}
		}
		catch ( Exception $e ) {
			return false;
		}
		$this->wpmc_clean_dir( dirname( $originalPath ) );
		return true;
	}

	function wpmc_ignore( $id ) {
		global $wpdb;
		$table_name = $wpdb->prefix . "mclean_scan";
		$has = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $table_name WHERE id = %d AND ignored = 1", $id ) );
		if ( $has > 0 )
			$wpdb->query( $wpdb->prepare( "UPDATE $table_name SET ignored = 0 WHERE id = %d", $id ) );
		else
			$wpdb->query( $wpdb->prepare( "UPDATE $table_name SET ignored = 1 WHERE id = %d", $id ) );
		return true;
	}

	function wpmc_endsWith( $haystack, $needle )
	{
	  $length = strlen( $needle );
	  if ( $length == 0 )
	    return true;
	  return ( substr( $haystack, -$length ) === $needle );
	}

	function wpmc_clean_dir( $dir ) {
		if ( !file_exists( $dir ) )
			return;
		else if ( $this->wpmc_endsWith( $dir, 'uploads' ) )
			return;
		$found = array_diff( scandir( $dir ), array( '.', '..' ) );
		if ( count( $found ) < 1 ) {
			if ( rmdir( $dir ) ) {
				$this->wpmc_clean_dir( dirname( $dir ) );
			}
		}
	}

	function wpmc_delete( $id ) {
		global $wpdb;
		$table_name = $wpdb->prefix . "mclean_scan";
		$issue = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_name WHERE id = %d", $id ), OBJECT );
		$regex = "^(.*)(\\s\\(\\+.*)$";
		$issue->path = preg_replace( '/' . $regex . '/i', '$1', $issue->path ); // remove " (+ 6 files)" from path

		// Make sure there isn't a media DB entry
		if ( $issue->type == 0 ) {
			$attachmentid = $this->wpmc_find_attachment_id_by_file( $issue->path );
			if ( $attachmentid ) {
				$this->log( "Issue listed as filesystem but Media {$attachmentid} exists." );
			}
		}

		if ( $issue->type == 0 ) {

			if ( $issue->deleted == 0 ) {
				// Move file to the trash
				if ( $this->wpmc_trash_file( $issue->path ) )
					$wpdb->query( $wpdb->prepare( "UPDATE $table_name SET deleted = 1 WHERE id = %d", $id ) );
				return true;
			}
			else {
				// Delete file from the trash
				$trashPath = trailingslashit( $this->wpmc_trashdir() ) . $issue->path;
				if ( !unlink( $trashPath ) ) {
					$this->log( "Failed to delete the file." );
					error_log( "Media Cleaner: Failed to delete the file." );
				}
				$wpdb->query( $wpdb->prepare( "DELETE FROM $table_name WHERE id = %d", $id ) );
				$this->wpmc_clean_dir( dirname( $trashPath ) );
				return true;
			}
		}

		if ( $issue->type == 1 ) {
			if ( $issue->deleted == 0 && MEDIA_TRASH ) {
				// Move Media to trash
				// Let's copy the images to the trash so that it can be recovered.
				$fullpath = get_attached_file( $issue->postId );
				$mainfile = $this->wpmc_clean_uploaded_filename( $fullpath );
				$baseUp = pathinfo( $mainfile );
				$baseUp = $baseUp['dirname'];
				$file = $this->wpmc_clean_uploaded_filename( $fullpath );
				if ( !$this->wpmc_trash_file( $file ) ) {
					$this->log( "Could not trash $file." );
					error_log( "Media Cleaner: Could not trash $file." );
					return false;
				}

				// If images, check the other files as well
				$meta = wp_get_attachment_metadata( $issue->postId );
				$isImage = isset( $meta, $meta['width'], $meta['height'] );
				$sizes = $this->wpmc_get_image_sizes();
				if ( $isImage && isset( $meta['sizes'] ) ) {
					foreach ( $meta['sizes'] as $name => $attr ) {
						if  ( isset( $attr['file'] ) ) {
							$filepath = wp_upload_dir();
							$filepath = $filepath['basedir'];
							$filepath = trailingslashit( $filepath ) . trailingslashit( $baseUp ) . $attr['file'];
							$file = $this->wpmc_clean_uploaded_filename( $filepath );
							if ( !$this->wpmc_trash_file( $file ) ) {
								$this->log( "Could not trash $file." );
								error_log( "Media Cleaner: Could not trash $file." );
							}
						}
					}
				}
				wp_delete_attachment( $issue->postId, false );
				$wpdb->query( $wpdb->prepare( "UPDATE $table_name SET deleted = 1 WHERE id = %d", $id ) );
				return true;
			}
			else {
				// Trash Media definitely by recovering it (to be like a normal Media) and remove it through the
				// standard WordPress workflow
				if ( MEDIA_TRASH )
					$this->wpmc_recover( $id );
				wp_delete_attachment( $issue->postId, true );
				$wpdb->query( $wpdb->prepare( "DELETE FROM $table_name WHERE id = %d", $id ) );
				return true;
			}
		}
		return false;
	}

	/**
	 *
	 * SCANNING / RESET
	 *
	 */

	function add_reference_url( $urlOrUrls, $type, $origin = null, $extra = null ) {
		$urlOrUrls = !is_array( $urlOrUrls ) ? array( $urlOrUrls ) : $urlOrUrls;
		foreach ( $urlOrUrls as $url ) {
			// With files, we need both filename without resolution and filename with resolution, it's important
			// to make sure the original file is not deleted if a size exists for it.
			// With media, all URLs should be without resolution to make sure it matches Media.
			if ( $this->current_method == 'files' )
				$this->add_reference( null, $url, $type, $origin );
			$this->add_reference( 0, $this->clean_url_from_resolution( $url ), $type, $origin );
		}
	}

	function add_reference_id( $idOrIds, $type, $origin = null, $extra = null ) {
		$idOrIds = !is_array( $idOrIds ) ? array( $idOrIds ) : $idOrIds;
		foreach ( $idOrIds as $id )
			$this->add_reference( $id, "", $type, $origin );
	}

	private $cached_ids = array();
	private $cached_urls = array();

	private function add_reference( $id, $url, $type, $origin = null, $extra = null ) {
		// The references are actually not being added directly in the DB, they are being pushed
		// into a cache ($this->refcache).
		if ( !empty( $id ) ) {
			if ( !in_array( $id, $this->cached_ids ) )
				array_push( $this->cached_ids, $id );
			else
				return;
		}
		if ( !empty( $url ) ) {
			// The URL shouldn't contain http, https, javascript at the beginning (and there are probably many more cases)
			// The URL must be cleaned before being passed as a reference.
			if ( substr( $url, 0, 5 ) === "http:" )
				return;
			if ( substr( $url, 0, 6 ) === "https:" )
				return;
			if ( substr( $url, 0, 11 ) === "javascript:" )
				return;
			if ( !in_array( $url, $this->cached_urls ) )
				array_push( $this->cached_urls, $url );
			else
				return;
		}
		//
		array_push( $this->refcache, array( 'id' => $id, 'url' => $url, 'type' => $type, 'origin' => $origin ) );

		// Without cache, the code would be this.
		// $wpdb->insert( $table_name,
		//	array(
		//		'time' => current_time('mysql'), 'mediaId' => $id, 'mediaUrl' => $url, 'origin' => $origin, 'originType' => $type )
		//	);
	}

	// The cache containing the references is wrote to the DB.
	function write_references() {
		global $wpdb;
		$table = $wpdb->prefix . "mclean_refs";
		$values = array();
		$place_holders = array();
		$query = "INSERT INTO $table (mediaId, mediaUrl, originType) VALUES ";
		foreach( $this->refcache as $key => $value ) {
			array_push( $values, $value['id'], $value['url'], $value['type'] );
			if ( get_option( 'wpmc_debuglogs', false ) ) {
				if ( !empty( $value['id'] ) )
					$this->log( "* {$value['type']}: Media #{$value['id']}" );
				if ( !empty( $value['url'] ) )
					$this->log( "* {$value['type']}: {$value['url']}" );
			}
			$place_holders[] = "('%d','%s','%s')";
		}
		if ( !empty( $values ) ) {
			$query .= implode( ', ', $place_holders );
			$prepared = $wpdb->prepare( "$query ", $values );
			$wpdb->query( $prepared );
		}
		$this->refcache = array();
	}

	function wpmc_check_is_ignore( $file ) {
		global $wpdb;
		$table_name = $wpdb->prefix . "mclean_scan";
		$count = $wpdb->get_var( "SELECT COUNT(*)
			FROM $table_name
			WHERE ignored = 1
			AND path LIKE '%".  esc_sql( $wpdb->esc_like( $file ) ) . "%'" );
		if ( $count > 0 ) {
			$this->log( "Could not trash $file." );
		}
		return ($count > 0);
	}

	function wpmc_find_attachment_id_by_file( $file ) {
		global $wpdb;
		$postmeta_table_name = $wpdb->prefix . 'postmeta';
		$file = $this->wpmc_clean_uploaded_filename( $file );
		$sql = $wpdb->prepare( "SELECT post_id
			FROM {$postmeta_table_name}
			WHERE meta_key = '_wp_attached_file'
			AND meta_value = %s", $file
		);
		$ret = $wpdb->get_var( $sql );
		if ( empty( $ret ) )
			$this->log( "File $file not found as _wp_attached_file (Library)." );
		else {
			$this->log( "File $file found as Media $ret." );
		}
		return $ret;
	}

	function wpmc_get_image_sizes() {
		$sizes = array();
		global $_wp_additional_image_sizes;
		foreach ( get_intermediate_image_sizes() as $s ) {
			$crop = false;
			if ( isset( $_wp_additional_image_sizes[$s] ) ) {
				$width = intval( $_wp_additional_image_sizes[$s]['width'] );
				$height = intval( $_wp_additional_image_sizes[$s]['height'] );
				$crop = $_wp_additional_image_sizes[$s]['crop'];
			} else {
				$width = get_option( $s.'_size_w' );
				$height = get_option( $s.'_size_h' );
				$crop = get_option( $s.'_crop' );
			}
			$sizes[$s] = array( 'width' => $width, 'height' => $height, 'crop' => $crop );
		}
		return $sizes;
	}

	function clean_url_from_resolution( $url ) {
		$pattern = '/[_-]\d+x\d+(?=\.[a-z]{3,4}$)/';
		$url = preg_replace( $pattern, '', $url );
		return $url;
	}

	function clean_url_from_resolution_ref( &$url ) {
		$url = $this->clean_url_from_resolution( $url );
	}

	// From a url to the shortened and cleaned url (for example '2013/02/file.png')
	function wpmc_clean_url( $url ) {
		$upload_folder = wp_upload_dir();
		$baseurl = $upload_folder['baseurl'];
		if ( substr( $url, 0, 2 ) === "//" )
			$url = "http:" . $url;
		$baseurl = str_replace( 'https://', 'http://', $baseurl );
		$baseurl = str_replace( 'http://www.', 'http://', $baseurl );
		$newurl = str_replace( 'https://', 'http://', $url );
		$newurl = str_replace( 'http://www.', 'http://', $newurl );
		$newurl = str_replace( $baseurl, '', $newurl );
		$newurl = trim( $newurl,  "/" );
		return $newurl;
	}

	// From a fullpath to the shortened and cleaned path (for example '2013/02/file.png')
	function wpmc_clean_uploaded_filename( $fullpath ) {
		$upload_folder = wp_upload_dir();
		$basedir = $upload_folder['basedir'];
		$file = str_replace( $basedir, '', $fullpath );
		$file = str_replace( "./", "", $file );
		$file = trim( $file,  "/" );
		return $file;
	}

	function wpmc_check_media( $attachmentId, $checkOnly = false ) {

		$this->last_analysis = "N/A";

		// Is it an image?
		$meta = wp_get_attachment_metadata( $attachmentId );
		$isImage = isset( $meta, $meta['width'], $meta['height'] );

		// Get the main file
		global $wpdb;
		$fullpath = get_attached_file( $attachmentId );
		$mainfile = $this->wpmc_clean_uploaded_filename( $fullpath );
		$baseUp = pathinfo( $mainfile );
		$baseUp = $baseUp['dirname'];
		$size = 0;
		$countfiles = 0;
		$issue = 'NO_CONTENT';
		if ( file_exists( $fullpath ) ) {

			// Special scan: Broken only!
			$check_postmeta = get_option( 'wpmc_postmeta', false );
			$check_posts = get_option( 'wpmc_posts', false );
			$check_widgets = get_option( 'wpmc_widgets', false );
			if ( !$check_postmeta && !$check_posts && !$check_widgets )
				return true;

			$size = filesize( $fullpath );

			// Analysis
			$this->last_analysis = "NONE";
			$this->log( "Checking Media #{$attachmentId}: {$mainfile}" );
			if ( $this->wpmc_check_is_ignore( $mainfile, $attachmentId ) ) {
				$this->last_analysis = "IGNORED";
				return true;
			}
			if ( $this->checkers->check( $mainfile, $attachmentId ) )
				return true;

			// If images, check the other files as well
			$countfiles = 0;
			$sizes = $this->wpmc_get_image_sizes();
			if ( $isImage && isset( $meta['sizes'] ) ) {
				foreach ( $meta['sizes'] as $name => $attr ) {
					if  ( isset( $attr['file'] ) ) {
						$filepath = wp_upload_dir();
						$filepath = $filepath['basedir'];
						$filepath = trailingslashit( $filepath ) . trailingslashit( $baseUp ) . $attr['file'];
						if ( file_exists( $filepath ) )
							$size += filesize( $filepath );
						$file = $this->wpmc_clean_uploaded_filename( $filepath );
						$countfiles++;
						// Analysis
						$this->log( "Checking Media #{$attachmentId}: {$file}" );
						if ( $this->checkers->check( $mainfile, $attachmentId ) )
							return true;
					}
				}
			}
		} else {
			$this->log( "File {$fullpath} does not exist." );
			$issue = 'ORPHAN_MEDIA';
		}

		if ( !$checkOnly ) {
			$table_name = $wpdb->prefix . "mclean_scan";
			$wpdb->insert( $table_name,
				array(
					'time' => current_time('mysql'),
					'type' => 1,
					'size' => $size,
					'path' => $mainfile . ( $countfiles > 0 ? ( " (+ " . $countfiles . " files)" ) : "" ),
					'postId' => $attachmentId,
					'issue' => $issue
					)
				);
		}
		return false;
	}

	// Delete all issues
	function wpmc_reset_issues( $includingIgnored = false ) {
		global $wpdb;
		$table_name = $wpdb->prefix . "mclean_scan";
		if ( $includingIgnored ) {
			$wpdb->query( "DELETE FROM $table_name WHERE deleted = 0" );
		}
		else {
			$wpdb->query( "DELETE FROM $table_name WHERE ignored = 0 AND deleted = 0" );
		}
		if ( file_exists( plugin_dir_path( __FILE__ ) . '/media-cleaner.log' ) ) {
			file_put_contents( plugin_dir_path( __FILE__ ) . '/media-cleaner.log', '' );
		}
		$table_name = $wpdb->prefix . "mclean_refs";
		$wpdb->query("TRUNCATE $table_name");

		// Delete the old transient. Let's delete those lines later.
		delete_transient( "wpmc_theme_ids" );
		delete_transient( "wpmc_theme_urls" );
		delete_transient( "wpmc_widgets_ids" );
		delete_transient( "wpmc_widgets_urls" );
		delete_transient( "wpmc_posts_images_urls" );
		delete_transient( "wpmc_posts_images_ids" );
		delete_transient( "wpmc_postmeta_images_urls" );
		delete_transient( "wpmc_postmeta_images_ids" );
		delete_transient( "wpmc_postmeta_images_acf_urls" );
		delete_transient( "wpmc_postmeta_images_acf_ids" );
		delete_transient( "wpmc_posts_images_visualcomposer" );
		delete_transient( "wpmc_galleries_images_visualcomposer" );
		delete_transient( "wpmc_galleries_images_fusionbuilder" );
		delete_transient( "wpmc_galleries_images_woocommerce" );
		delete_transient( "wpmc_galleries_images_divi" );
		delete_transient( "wpmc_galleries_images_urls" );
	}

	/**
	 *
	 * DASHBOARD
	 *
	 */

	function admin_inline_js() {
		echo "<script type='text/javascript'>\n";
		echo 'var wpmc_cfg = {
			delay: ' . get_option( 'wpmc_delay', 100 ) . ',
			analysisBuffer: ' . get_option( 'wpmc_analysis_buffer', 50 ) . ',
			isPro: ' . ( $this->admin->is_registered()  ? '1' : '0') . ',
			scanFiles: ' . ( ( get_option( 'wpmc_method', 'media' ) == 'files' && $this->admin->is_registered() ) ? '1' : '0' ) . ',
			scanMedia: ' . ( get_option( 'wpmc_method', 'media' ) == 'media' ? '1' : '0' ) . ' };';
		echo "\n</script>";
	}

	function echo_issue( $issue ) {
		if ( $issue == 'NO_CONTENT' ) {
			_e( "Seems not in use.", 'media-cleaner' );
		}
		else if ( $issue == 'NO_MEDIA' ) {
			_e( "Not in Media Library.", 'media-cleaner' );
		}
		else if ( $issue == 'ORPHAN_RETINA' ) {
			_e( "Orphan retina.", 'media-cleaner' );
		}
		else if ( $issue == 'ORPHAN_MEDIA' ) {
			_e( "File not found.", 'media-cleaner' );
		}
		else {
			echo $issue;
		}
	}

	function media_row_actions( $actions, $post ) {
		global $current_screen;
		if ( 'upload' != $current_screen->id )
		    return $actions;
		global $wpdb;
		$table_name = $wpdb->prefix . "mclean_scan";
		$res = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_name WHERE postId = %d", $post->ID ) );
		if ( !empty( $res ) && isset( $actions['delete'] ) )
			$actions['delete'] = "<a href='?page=media-cleaner&view=deleted'>" .
				__( 'Delete with Media Cleaner', 'media-cleaner' ) . "</a>";
		if ( !empty( $res ) && isset( $actions['trash'] ) )
			$actions['trash'] = "<a href='?page=media-cleaner'>" .
				__( 'Trash with Media Cleaner', 'media-cleaner' ) . "</a>";
		if ( !empty( $res ) && isset( $actions['untrash'] ) ) {
			$actions['untrash'] = "<a href='?page=media-cleaner&view=deleted'>" .
				__( 'Restore with Media Cleaner', 'media-cleaner' ) . "</a>";
		}
		return $actions;
	}

	function wpmc_screen() {
		global $wplr;
		?>
		<div class='wrap'>

			<?php
				echo $this->admin->display_title( "Media Cleaner" );
				global $wpdb;
				$posts_per_page = get_user_meta( get_current_user_id(), 'upload_per_page', true );
				if ( empty( $posts_per_page ) )
					$posts_per_page = 20;
				$view = isset ( $_GET[ 'view' ] ) ? sanitize_text_field( $_GET[ 'view' ] ) : "issues";
				$paged = isset ( $_GET[ 'paged' ] ) ? sanitize_text_field( $_GET[ 'paged' ] ) : 1;
				$reset = isset ( $_GET[ 'reset' ] ) ? $_GET[ 'reset' ] : 0;
				if ( $reset ) {
					wpmc_reset();
					$this->wpmc_reset_issues();
				}
				$s = isset ( $_GET[ 's' ] ) ? sanitize_text_field( $_GET[ 's' ] ) : null;
				$table_name = $wpdb->prefix . "mclean_scan";
				$issues_count = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name WHERE ignored = 0 AND deleted = 0" );
				$total_size = $wpdb->get_var( "SELECT SUM(size) FROM $table_name WHERE ignored = 0 AND deleted = 0" );
				$trash_total_size = $wpdb->get_var( "SELECT SUM(size) FROM $table_name WHERE ignored = 0 AND deleted = 1" );
				$ignored_count = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name WHERE ignored = 1" );
				$deleted_count = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name WHERE deleted = 1" );

				if ( $view == 'deleted' ) {
					$items_count = $deleted_count;
					$items = $wpdb->get_results( $wpdb->prepare( "SELECT id, type, postId, path, size, ignored, deleted, issue
						FROM $table_name WHERE ignored = 0 AND deleted = 1 AND path LIKE %s
						ORDER BY time
						DESC LIMIT %d, %d", '%' . $s . '%', ( $paged - 1 ) * $posts_per_page, $posts_per_page ), OBJECT );
				}
				else if ( $view == 'ignored' ) {
					$items_count = $ignored_count;
					$items = $wpdb->get_results( $wpdb->prepare( "SELECT id, type, postId, path, size, ignored, deleted, issue
						FROM $table_name
						WHERE ignored = 1 AND deleted = 0 AND path LIKE %s
						ORDER BY time
						DESC LIMIT %d, %d", '%' . $s . '%', ( $paged - 1 ) * $posts_per_page, $posts_per_page ), OBJECT );
				}
				else {
					$items_count = $issues_count;
					$items = $wpdb->get_results( $wpdb->prepare( "SELECT id, type, postId, path, size, ignored, deleted, issue
						FROM $table_name
						WHERE ignored = 0 AND deleted = 0  AND path LIKE %s
						ORDER BY time
						DESC LIMIT %d, %d", '%' . $s . '%', ( $paged - 1 ) * $posts_per_page, $posts_per_page ), OBJECT );
				}
			?>

			<style>
				#wpmc-pages {
					float: right;
					position: relative;
					top: 12px;
				}

				#wpmc-pages a {
					text-decoration: none;
					border: 1px solid black;
					padding: 2px 5px;
					border-radius: 4px;
					background: #E9E9E9;
					color: lightslategrey;
					border-color: #BEBEBE;
				}

				#wpmc-pages .current {
					font-weight: bold;
				}
			</style>

			<div style='margin-top: 0px; background: #FFF; padding: 5px; border-radius: 4px; height: 28px; box-shadow: 0px 0px 6px #C2C2C2;'>

				<!-- SCAN -->
				<?php if ( $view != 'deleted' ) { ?>
					<a id='wpmc_scan' onclick='wpmc_scan()' class='button-primary' style='float: left;'><span style="top: 3px; position: relative; left: -5px;" class="dashicons dashicons-search"></span><?php _e("Start Scan", 'media-cleaner'); ?></a>
				<?php } ?>

				<!-- PAUSE -->
				<?php if ( $view != 'deleted' ) { ?>
					<a id='wpmc_pause' onclick='wpmc_pause()' class='button' style='float: left; margin-left: 5px; display: none;'><span style="top: 3px; position: relative; left: -5px;" class="dashicons dashicons-controls-pause"></span><?php _e("Pause", 'media-cleaner'); ?></a>
				<?php } ?>

				<!-- DELETE SELECTED -->
				<a id='wpmc_delete' onclick='wpmc_delete()' class='button' style='float: left; margin-left: 5px;'><span style="top: 3px; position: relative; left: -5px;" class="dashicons dashicons-no"></span><?php _e("Delete", 'media-cleaner'); ?></a>
				<?php if ( $view == 'deleted' ) { ?>
					<a id='wpmc_recover' onclick='wpmc_recover()' class='button-secondary' style='float: left; margin-left: 5px;'><span style="top: 3px; position: relative; left: -5px;" class="dashicons dashicons-controls-repeat"></span><?php _e( "Recover", 'media-cleaner' ); ?></a>
				<?php } ?>

				<!-- IGNORE SELECTED -->
				<a id='wpmc_ignore' onclick='wpmc_ignore()' class='button' style='float: left; margin-left: 5px;'><span style="top: 3px; position: relative; left: -5px;" class="dashicons dashicons-yes"></span><?php
					if ( $view == 'ignored' )
						_e( "Mark as Issue", 'media-cleaner' );
					else
						_e( "Ignore", 'media-cleaner' );
				?>
				</a>

				<!-- RESET -->
				<?php if ( $view != 'deleted' ) { ?>
					<a id='wpmc_reset' href='?page=media-cleaner&reset=1' class='button-primary' style='float: right; margin-left: 5px;'><span style="top: 3px; position: relative; left: -5px;" class="dashicons dashicons-sos"></span><?php _e("Reset", 'media-cleaner'); ?></a>
				<?php } ?>

				<!-- DELETE ALL -->
				<?php if ( $view == 'deleted' ) { ?>
					<a id='wpmc_recover_all' onclick='wpmc_recover_all()' class='button-primary' style='float: right; margin-left: 5px;'><span style="top: 3px; position: relative; left: -5px;" class="dashicons dashicons-controls-repeat"></span><?php _e("Recover all", 'media-cleaner'); ?></a>
					<a id='wpmc_delete_all' onclick='wpmc_delete_all(true)' class='button button-red' style='float: right; margin-left: 5px;'><span style="top: 3px; position: relative; left: -5px;" class="dashicons dashicons-trash"></span><?php _e("Empty trash", 'media-cleaner'); ?></a>
				<?php } else { ?>
					<a id='wpmc_delete_all' onclick='wpmc_delete_all()' class='button button-red' style='float: right; margin-left: 5px;'><span style="top: 3px; position: relative; left: -5px;" class="dashicons dashicons-trash"></span><?php _e("Delete all", 'media-cleaner'); ?></a>
				<?php } ?>

				<form id="posts-filter" action="upload.php" method="get" style='float: right;'>
					<p class="search-box" style='margin-left: 5px; float: left;'>
						<input type="search" name="s" style="width: 120px;" value="<?php echo $s ? $s : ""; ?>">
						<input type="hidden" name="page" value="media-cleaner">
						<input type="hidden" name="view" value="<?php echo $view; ?>">
						<input type="hidden" name="paged" value="1">
						<input type="submit" class="button" value="<?php _e( 'Search', 'media-cleaner' ) ?>"><span style='border-right: #A2A2A2 solid 1px; margin-left: 5px; margin-right: 3px;'>&nbsp;</span>
					</p>
				</form>

				<!-- PROGRESS -->
				<span style='margin-left: 12px; font-size: 15px; top: 5px; position: relative; color: #747474;' id='wpmc_progression'></span>

			</div>

			<p>
				<?php

					$table_scan = $wpdb->prefix . "mclean_scan";
					$table_refs = $wpdb->prefix . "mclean_refs";
					if ( $wpdb->get_var("SHOW TABLES LIKE '$table_scan'") != $table_scan || 
						$wpdb->get_var("SHOW TABLES LIKE '$table_refs'") != $table_refs ) {
							_e( "<div class='notice notice-error'><p><b>The database is not ready for Media Cleaner. The scan will not work.</b> Click on the <b>Reset</b> button, it re-creates the tables required by Media Cleaner. If this message still appear, contact the support.</p></div>", 'media-cleaner' );
					}
					else {
						$method = get_option( 'wpmc_method', 'media' );
						if ( !$this->admin->is_registered() )
							$method = 'media';

						$hide_warning = get_option( 'wpmc_hide_warning', false );

						if ( !$hide_warning ) {
							_e( "<div class='notice notice-warning'><p><b style='color: red;'>Important.</b> <b>Backup your DB and your /uploads directory before using Media Cleaner. </b> The deleted files will be temporarily moved to the <b>uploads/wpmc-trash</b> directory. After testing your website, you can check the <a href='?page=media-cleaner&s&view=deleted'>trash</a> to either empty it or recover the media and files. The Media Cleaner does its best to be safe to use. However, WordPress being a very dynamic and pluggable system, it is impossible to predict all the situations in which your files are used. <b style='color: red;'>Again, please backup!</b> If you don't know how, give a try to this: <a href='https://updraftplus.com/?afref=460' target='_blank'>UpdraftPlus</a>. <br /><br /><b style='color: red;'>Be thoughtful.</b> Don't blame Media Cleaner if it deleted too many or not enough of your files. It makes cleaning possible and this task is only possible this way; don't post a bad review because it broke your install. <b>If you have a proper backup, there is no risk</b>. Sorry for the lengthy message, but better be safe than sorry. You can disable this big warning in the options if you have a Pro license. Make sure you read this warning twice. Media Cleaner is awesome and always getting better so I hope you will enjoy it. Thank you :)</p></div>", 'media-cleaner' );
						}

						if ( !MEDIA_TRASH ) {
							_e( "<div class='notice notice-warning'><p>The trash for the Media Library is disabled. Any media removed by the plugin will be <b>permanently deleted</b>. To enable it, modify your wp-config.php file and add this line (preferably at the top):<br /><b>define( 'MEDIA_TRASH', true );</b></p></div>", 'media-cleaner' );
						}
					}

					if ( !$this->admin->is_registered() ) {
						echo "<div class='notice notice-info'><p>";
						_e( "<b>This version is not Pro.</b> This plugin is a lot of work so please consider <a target='_blank' href='//meowapps.com/media-cleaner'>Media Cleaner Pro</a> in order to receive support and to contribute in the evolution of it. Also, <a target='_blank' href='//meowapps.com/media-cleaner'>Media Cleaner Pro</a> version will also give you the option <b>to scan the physical files in your /uploads folder</b> and extra checks for the common Page Builders.", 'media-cleaner' );
						echo "</p></div>";

						if ( function_exists( '_et_core_find_latest' ) ) {
							echo "<div class='notice notice-warning'><p>";
							_e( "<b>Divi has been detected</b>. The free version might detect the files used by Divi correctly, but its full support is only available in <a target='_blank' href='//meowapps.com/media-cleaner'>Media Cleaner Pro</a>.", 'media-cleaner' );
							echo "</p></div>";
						}

						if ( class_exists( 'Vc_Manager' ) ) {
							echo "<div class='notice notice-warning'><p>";
							_e( "<b>Visual Composer has been detected</b>. The free version might detect the files used by Visual Composer correctly, but its full support is only available in <a target='_blank' href='//meowapps.com/media-cleaner'>Media Cleaner Pro</a>.", 'media-cleaner' );
							echo "</p></div>";
						}

						if ( function_exists( 'fusion_builder_map' ) ) {
							echo "<div class='notice notice-warning'><p>";
							_e( "<b>Fusion Builder has been detected</b>. The free version might detect the files used by Fusion Builder correctly, but its full support is only available in <a target='_blank' href='//meowapps.com/media-cleaner'>Media Cleaner Pro</a>.", 'media-cleaner' );
							echo "</p></div>";
						}

						if ( class_exists( 'FLBuilderModel' ) ) {
							echo "<div class='notice notice-warning'><p>";
							_e( "<b>Beaver Builder has been detected</b>. The free version might detect the files used by Beaver Builder correctly, but its full support is only available in <a target='_blank' href='//meowapps.com/media-cleaner'>Media Cleaner Pro</a>.", 'media-cleaner' );
							echo "</p></div>";
						}

						if ( function_exists( 'elementor_load_plugin_textdomain' ) ) {
							echo "<div class='notice notice-warning'><p>";
							_e( "<b>Elementor has been detected</b>. The free version might detect the files used by Elementor correctly, but its full support is only available in <a target='_blank' href='//meowapps.com/media-cleaner'>Media Cleaner Pro</a>.", 'media-cleaner' );
							echo "</p></div>";
						}

						if ( class_exists( 'SiteOrigin_Panels' ) ) {
							echo "<div class='notice notice-warning'><p>";
							_e( "<b>SiteOrigin Page Builder has been detected</b>. The free version might detect the files used by SiteOrigin Page Builder correctly, but its full support is only available in <a target='_blank' href='//meowapps.com/media-cleaner'>Media Cleaner Pro</a>.", 'media-cleaner' );
							echo "</p></div>";
						}

					}

					$anychecks = get_option( 'wpmc_posts', false ) || get_option( 'wpmc_postmeta', false ) || get_option( 'wpmc_widgets', false );
					$check_library = get_option(' wpmc_media_library', true );

					if ( $method == 'media' ) {
						if ( !$anychecks )
							_e( "<div class='error'><p>Media Cleaner will analyze your Media Library. However, There is <b>NOTHING MARKED TO BE CHECKED</b> in the Settings. Media Cleaner will therefore run a special scan: <b>only the broken medias will be detected as issues.</b></p></div>", 'media-cleaner' );
						else
							_e( "<div class='notice notice-success'><p>Media Cleaner will analyze your Media Library.</p></div>", 'media-cleaner' );
					}
					else if ( $method == 'files' ) {
						if ( !$anychecks && !$check_library )
							_e( "<div class='error'><p>Media Cleaner will analyze your Filesystem. However, There is <b>NOTHING MARKED TO BE CHECKED</b> in the Settings. If you scan now, all the files will be detected as <b>NOT USED</b>.</p></div>", 'media-cleaner' );
						else
							_e( "<div class='notice notice-success'><p>Media Cleaner will analyze your Filesystem.</p></div>", 'media-cleaner' );
					}

					echo sprintf( __( 'There are <b>%s issue(s)</b> with your files, accounting for <b>%s MB</b>. Your trash contains <b>%s MB.</b>', 'media-cleaner' ), number_format( $issues_count, 0 ), number_format( $total_size / 1000000, 2 ), number_format( $trash_total_size / 1000000, 2 ) );
				?>
			</p>

			<div id='wpmc-pages'>
			<?php
			echo paginate_links(array(
				'base' => '?page=media-cleaner&s=' . urlencode($s) . '&view=' . $view . '%_%',
				'current' => $paged,
				'format' => '&paged=%#%',
				'total' => ceil( $items_count / $posts_per_page ),
				'prev_next' => false
			));
			?>
			</div>

			<ul class="subsubsub">
				<li class="all"><a <?php if ( $view == 'issues' ) echo "class='current'"; ?> href='?page=media-cleaner&s=<?php echo $s; ?>&view=issues'><?php _e( "Issues", 'media-cleaner' ); ?></a><span class="count">(<?php echo $issues_count; ?>)</span></li> |
				<li class="all"><a <?php if ( $view == 'ignored' ) echo "class='current'"; ?> href='?page=media-cleaner&s=<?php echo $s; ?>&view=ignored'><?php _e( "Ignored", 'media-cleaner' ); ?></a><span class="count">(<?php echo $ignored_count; ?>)</span></li> |
				<li class="all"><a <?php if ( $view == 'deleted' ) echo "class='current'"; ?> href='?page=media-cleaner&s=<?php echo $s; ?>&view=deleted'><?php _e( "Trash", 'media-cleaner' ); ?></a><span class="count">(<?php echo $deleted_count; ?>)</span></li>
			</ul>

			<table id='wpmc-table' class='wp-list-table widefat fixed media'>

				<thead>
					<tr>
						<th scope="col" id="cb" class="manage-column column-cb check-column"><input id="wpmc-cb-select-all" type="checkbox"></th>
						<?php if ( !get_option( 'wpmc_hide_thumbnails', false ) ): ?>
						<th style='width: 64px;'><?php _e( 'Thumb', 'media-cleaner' ) ?></th>
						<?php endif; ?>
						<th style='width: 50px;'><?php _e( 'Type', 'media-cleaner' ) ?></th>
						<th style='width: 80px;'><?php _e( 'Origin', 'media-cleaner' ) ?></th>

						<?php if ( !empty( $wplr ) ):  ?>
							<th style='width: 70px;'><?php _e( 'LR ID', 'media-cleaner' ) ?></th>
						<?php endif; ?>

						<th><?php _e( 'Path', 'media-cleaner' ) ?></th>
						<th style='width: 220px;'><?php _e( 'Issue', 'media-cleaner' ) ?></th>
						<th style='width: 80px; text-align: right;'><?php _e( 'Size', 'media-cleaner' ) ?></th>
					</tr>
				</thead>

				<tbody>
					<?php
						foreach ( $items as $issue ) {
							$regex = "^(.*)(\\s\\(\\+.*)$";
							$issue->path = preg_replace( '/' .$regex . '/i', '$1', $issue->path );
					?>
					<tr>
						<td><input type="checkbox" name="id" value="<?php echo $issue->id ?>"></td>
						<?php if ( !get_option( 'wpmc_hide_thumbnails', false ) ): ?>
						<td>
							<?php
								if ( $issue->deleted == 0 ) {
									if ( $issue	->type == 0 ) {
										// FILE
										$upload_dir = wp_upload_dir();
										$url = htmlspecialchars( $upload_dir['baseurl'] . '/' . $issue->path, ENT_QUOTES );
										echo "<a target='_blank' href='" . $url .
											"'><img style='max-width: 48px; max-height: 48px;' src='" . $url . "' /></a>";
									}
									else {
										// MEDIA
										$file = get_attached_file( $issue->postId );
										if ( file_exists( $file ) ) {
											$attachmentsrc = wp_get_attachment_image_src( $issue->postId, 'thumbnail' );
											if ( empty( $attachmentsrc ) )
												echo '<span class="dashicons dashicons-no-alt"></span>';
											else {
												$attachmentsrc_clean = htmlspecialchars( $attachmentsrc[0], ENT_QUOTES );
												echo "<a target='_blank' href='" . $attachmentsrc_clean .
													"'><img style='max-width: 48px; max-height: 48px;' src='" .
													$attachmentsrc_clean . "' />";
											}
										}
										else {
											echo '<span class="dashicons dashicons-no-alt"></span>';
										}
									}
								}
								if ( $issue->deleted == 1 ) {
									$upload_dir = wp_upload_dir();
									$url = htmlspecialchars( $upload_dir['baseurl'] . '/wpmc-trash/' . $issue->path, ENT_QUOTES );
									echo "<a target='_blank' href='" . $url .
										"'><img style='max-width: 48px; max-height: 48px;' src='" . $url . "' /></a>";
								}
							?>
						</td>
						<?php endif; ?>
						<td><?php echo $issue->type == 0 ? 'FILE' : 'MEDIA'; ?></td>
						<td><?php echo $issue->type == 0 ? 'Filesystem' : ("<a href='post.php?post=" .
							$issue->postId . "&action=edit'>ID " . $issue->postId . "</a>"); ?></td>
						<?php if ( !empty( $wplr ) ) { $info = $wplr->get_sync_info( $issue->postId ); ?>
							<td style='width: 70px;'><?php echo ( !empty( $info ) && $info->lr_id ? $info->lr_id : "" ); ?></td>
						<?php } ?>
						<td><?php echo stripslashes( $issue->path ); ?></td>
						<td><?php $this->echo_issue( $issue->issue ); ?></td>
						<td style='text-align: right;'><?php echo number_format( $issue->size / 1000, 2 ); ?> KB</td>
					</tr>
					<?php } ?>
				</tbody>

				<tfoot>
					<tr><th></th>
					<?php if ( !get_option( 'hide_thumbnails', false ) ): ?>
					<th></th>
					<?php endif; ?>
					<th><?php _e( 'Type', 'media-cleaner' ) ?></th><th><?php _e( 'Origin', 'media-cleaner' ) ?></th>
					<?php if ( !empty( $wplr ) ):  ?>
						<th style='width: 70px;'><?php _e( 'LR ID', 'media-cleaner' ) ?></th>
					<?php endif; ?>
					<th><?php _e( 'Path', 'media-cleaner' ) ?></th><th><?php _e( 'Issue', 'media-cleaner' ) ?></th><th style='width: 80px; text-align: right;'><?php _e( 'Size', 'media-cleaner' ) ?></th></tr>
				</tfoot>

			</table>
		</wrap>

		<?php
	}

	function admin_menu() {
		//load_plugin_textdomain( 'media-cleaner', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		add_media_page( 'Media Cleaner', 'Cleaner', 'manage_options', 'media-cleaner', array( $this, 'wpmc_screen' ) );
	}

	function wp_enqueue_scripts () {
		wp_enqueue_style( 'media-cleaner-css', plugins_url( '/media-cleaner.css', __FILE__ ) );
		wp_enqueue_script( 'media-cleaner', plugins_url( '/media-cleaner.js', __FILE__ ), array( 'jquery' ), "3.7.0", true );
	}
}


/*
	INSTALL / UNINSTALL
*/

register_activation_hook( __FILE__, 'wpmc_reset' );
register_deactivation_hook( __FILE__, 'wpmc_uninstall' );
register_uninstall_hook( __FILE__, 'wpmc_uninstall' );

function wpmc_reset () {
	wpmc_uninstall();
	wpmc_install();
	$upload_folder = wp_upload_dir();
	$basedir = $upload_folder['basedir'];
	if ( !is_writable( $basedir ) ) {
		echo '<div class="error"><p>' . __( 'The directory for uploads is not writable. Media Cleaner will only be able to scan.', 'media-cleaner' ) . '</p></div>';
	}
	
}

function wpmc_install() {
	global $wpdb;
	$table_name = $wpdb->prefix . "mclean_scan";
	$charset_collate = $wpdb->get_charset_collate();
	$sql = "CREATE TABLE $table_name (
		id BIGINT(20) NOT NULL AUTO_INCREMENT,
		time DATETIME DEFAULT '0000-00-00 00:00:00' NOT NULL,
		type TINYINT(1) NOT NULL,
		postId BIGINT(20) NULL,
		path TINYTEXT NULL,
		size INT(9) NULL,
		ignored TINYINT(1) NOT NULL DEFAULT 0,
		deleted TINYINT(1) NOT NULL DEFAULT 0,
		issue TINYTEXT NOT NULL,
		PRIMARY KEY  (id)
	) " . $charset_collate . ";";
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta( $sql );
	$table_name = $wpdb->prefix . "mclean_refs";
	$charset_collate = $wpdb->get_charset_collate();
	// This key doesn't work on too many installs because of the 'Specified key was too long' issue
	// KEY mediaLookUp (mediaId, mediaUrl)
	$sql = "CREATE TABLE $table_name (
		id BIGINT(20) NOT NULL AUTO_INCREMENT,
		mediaId BIGINT(20) NULL,
		mediaUrl VARCHAR(256) NULL,
		originType VARCHAR(32) NOT NULL,
		PRIMARY KEY  (id)
	) " . $charset_collate . ";";
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta( $sql );
}

function wpmc_uninstall () {
	global $wpdb;
	$table_name = $wpdb->prefix . "wpmcleaner";
	$wpdb->query("DROP TABLE IF EXISTS $table_name");
	$table_name = $wpdb->prefix . "mclean_scan";
	$wpdb->query("DROP TABLE IF EXISTS $table_name");
	$table_name = $wpdb->prefix . "mclean_refs";
	$wpdb->query("DROP TABLE IF EXISTS $table_name");
}
