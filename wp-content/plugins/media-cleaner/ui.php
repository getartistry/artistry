<?php

class Meow_WPMC_UI {
	private $core = null;
	private $admin = null;

	function __construct( $core, $admin ) {
		$this->core = $core;
		$this->admin = $admin;
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
		add_action( 'admin_print_scripts', array( $this, 'admin_inline_js' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_metabox' ) );
		add_action( 'wp_ajax_wpmc_prepare_do', array( $this, 'wp_ajax_wpmc_prepare_do' ) );
		add_action( 'wp_ajax_wpmc_scan', array( $this, 'wp_ajax_wpmc_scan' ) );
		add_action( 'wp_ajax_wpmc_scan_do', array( $this, 'wp_ajax_wpmc_scan_do' ) );
		add_action( 'wp_ajax_wpmc_get_all_issues', array( $this, 'wp_ajax_wpmc_get_all_issues' ) );
		add_action( 'wp_ajax_wpmc_get_all_deleted', array( $this, 'wp_ajax_wpmc_get_all_deleted' ) );
		add_action( 'wp_ajax_wpmc_delete_do', array( $this, 'wp_ajax_wpmc_delete_do' ) );
		add_action( 'wp_ajax_wpmc_ignore_do', array( $this, 'wp_ajax_wpmc_ignore_do' ) );
		add_action( 'wp_ajax_wpmc_recover_do', array( $this, 'wp_ajax_wpmc_recover_do' ) );
		add_filter( 'media_row_actions', array( $this, 'media_row_actions' ), 10, 2 );
	}

	/**
	 * Renders a view within the views directory.
	 * @param string $view The name of the view to render
	 * @param array $data
	 * An associative array of variables to bind to the view.
	 * Each key turns into a variable name.
	 * @return string Rendered view
	 */
	function render_view( $view, $data = null ) {
		ob_start();
		if ( is_array( $data ) ) extract( $data );
		include( __DIR__ . "/views/$view.php" );
		return ob_get_clean();
	}

	function admin_menu() {
		//load_plugin_textdomain( 'media-cleaner', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		add_media_page( 'Media Cleaner', 'Cleaner', 'manage_options', 'media-cleaner', array( $this, 'wpmc_screen' ) );
	}

	function wpmc_screen() {
		global $wpdb, $wplr;
		echo $this->render_view( 'menu-screen', array(
			'wpdb'  => $wpdb,
			'wplr'  => $wplr,
			'ui'    => $this,
			'core'  => $this->core,
			'admin' => $this->admin
		) );
	}

	function wp_enqueue_scripts() {
		wp_enqueue_style( 'wp-jquery-ui-dialog' );
		wp_enqueue_script( 'jquery-ui-dialog' );
		wp_enqueue_style( 'media-cleaner-css', plugins_url( '/media-cleaner.css', __FILE__ ) );
		wp_enqueue_script( 'media-cleaner', plugins_url( '/media-cleaner.js', __FILE__ ), array( 'jquery', 'jquery-ui-dialog' ), "3.7.0", true );
	}

	/**
	 *
	 * DASHBOARD
	 *
	 */

	function admin_inline_js() {
		echo "<script type='text/javascript'>\n";
		echo 'var wpmc_cfg = {
			timeout: ' . ( (int) $this->core->get_max_execution_time() ) * 1000 . ',
			delay: ' . get_option( 'wpmc_delay', 100 ) . ',
			postsBuffer:' . get_option( 'wpmc_posts_buffer', 5 ) . ',
			mediasBuffer:' . get_option( 'wpmc_medias_buffer', 100 ) . ',
			analysisBuffer: ' . get_option( 'wpmc_analysis_buffer', 50 ) . ',
			isPro: ' . ( $this->admin->is_registered()  ? '1' : '0') . ',
			scanFiles: ' . ( ( get_option( 'wpmc_method', 'media' ) == 'files' && $this->admin->is_registered() ) ? '1' : '0' ) . ',
			scanMedia: ' . ( get_option( 'wpmc_method', 'media' ) == 'media' ? '1' : '0' ) . ' };';
		echo "\n</script>";
	}

	/*******************************************************************************
	 * METABOX FOR USAGE
	 ******************************************************************************/

	function add_metabox() {
		add_meta_box( 'mfrh_media_usage_box', 'Media Cleaner', array( $this, 'display_metabox' ), 'attachment', 'side', 'default' );
	}

	function display_metabox( $post ) {
		$this->core->log( "Media Edit > Checking Media #{$post->ID}" );
		$success = $this->core->wpmc_check_media( $post->ID, true );
		$this->core->log( "Success $success\n" );
		if ( $success ) {
			if ( $this->core->last_analysis == "CONTENT" ) {
				echo "Found in content.";
			}
			else if ( $this->core->last_analysis == "CONTENT (ID)" ) {
				echo "Found in content (as an ID).";
			}
			else if ( $this->core->last_analysis == "CONTENT (URL)" ) {
				echo "Found in content (as an URL).";
			}
			else if ( $this->core->last_analysis == "THEME" ) {
				echo "Found in theme.";
			}
			else if ( $this->core->last_analysis == "PAGE BUILDER" ) {
				echo "Found in Page Builder.";
			}
			else if ( $this->core->last_analysis == "GALLERY" ) {
				echo "Found in gallery.";
			}
			else if ( $this->core->last_analysis == "META" ) {
				echo "Found in meta.";
			}
			else if ( $this->core->last_analysis == "META (ID)" ) {
				echo "Found in meta (as an ID).";
			}
			else if ( $this->core->last_analysis == "META (URL)" ) {
				echo "Found in meta (as an URL).";
			}
			else if ( $this->core->last_analysis == "META ACF (ID)" ) {
				echo "Found in ACF meta (as an ID).";
			}
			else if ( $this->core->last_analysis == "META ACF (URL)" ) {
				echo "Found in ACF meta (as an URL).";
			}
			else if ( $this->core->last_analysis == "WIDGET" ) {
				echo "Found in widget.";
			}
			else {
				echo "It seems to be used as: " . $this->core->last_analysis;
			}
		}
		else {
			echo "Doesn't seem to be used.";
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

	/*******************************************************************************
	 * ASYNCHRONOUS AJAX FUNCTIONS
	 ******************************************************************************/

	function wp_ajax_wpmc_prepare_do() {
		$limit = isset( $_POST['limit'] ) ? $_POST['limit'] : 0;
		$limitsize = get_option( 'wpmc_posts_buffer', 5 );
		if ( empty( $limit ) )
			$this->core->wpmc_reset_issues();

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

			$this->core->log( "Analyzing for references:" );

			$theme_ids = array();
			$theme_urls = array();
			$this->core->get_images_from_themes( $theme_ids, $theme_urls );
			$this->core->add_reference_id( $theme_ids, 'THEME' );
			$this->core->add_reference_url( $theme_urls, 'THEME' );

			// Only on Start: Analyze Widgets
			if ( get_option( 'wpmc_widgets', false ) ) {
				$widgets_ids = array();
				$widgets_urls = array();
				$this->core->get_images_from_widgets( $widgets_ids, $widgets_urls );
				$this->core->add_reference_id( $widgets_ids, 'WIDGET' );
				$this->core->add_reference_url( $widgets_urls, 'WIDGET' );
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
					$this->core->add_reference_id( $postmeta_images_ids, 'META (ID)' );
				}
			}
		}

		$this->core->timeout_check_start( count( $posts ) );

		foreach ( $posts as $post ) {
			$this->core->timeout_check();
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
			$this->core->timeout_check_additem();
		}
		// Write the references cached by the scanners
		$this->core->write_references();

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
			//$this->core->log( print_r( $found, true ) );
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

	function wp_ajax_wpmc_scan_do() {
		// For debug, to pretend there is a timeout
		//$this->core->deepsleep(10);
		//header("HTTP/1.0 408 Request Timeout");
		//exit;

		ob_start();
		$type = $_POST['type'];
		$data = $_POST['data'];
		$this->core->timeout_check_start( count( $data ) );
		$success = 0;
		foreach ( $data as $piece ) {
			$this->core->timeout_check();
			if ( $type == 'file' ) {
				$this->core->log( "Check File: {$piece}" );
				$result = ( apply_filters( 'wpmc_check_file', true, $piece ) ? 1 : 0 );
				$this->core->log( "Success " . $result . "\n" );
				$success += $result;
			}
			else if ( $type == 'media' ) {
				$this->core->log( "Checking Media #{$piece}" );
				$result = ( $this->core->wpmc_check_media( $piece ) ? 1 : 0 );
				$this->core->log( "Success " . $result . "\n" );
				$success += $result;
			}
			$this->core->timeout_check_additem();
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

	function wp_ajax_wpmc_get_all_issues() {
		global $wpdb;
		$isTrash = ( isset( $_POST['isTrash'] ) && $_POST['isTrash'] == 1 ) ? true : false;
		$table_name = $wpdb->prefix . "mclean_scan";
		$q = "SELECT id FROM $table_name WHERE ignored = 0 AND deleted = " . ( $isTrash ? 1 : 0 );
		if ( $search = ( isset( $_POST['s'] ) && $_POST['s'] ) ? sanitize_text_field( $_POST['s'] ) : '' )
			$q = $wpdb->prepare( $q . ' AND path LIKE %s', '%' . $wpdb->esc_like( $search ) . '%' );
		$ids = $wpdb->get_col( $q );

		echo json_encode(
			array(
				'results' => array( 'ids' => $ids ),
				'success' => true,
				'message' => __( "List generated.", 'media-cleaner' )
			)
		);
		die;
	}

	function wp_ajax_wpmc_get_all_deleted() {
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

	function wp_ajax_wpmc_delete_do() {
		ob_start();
		$data = $_POST['data'];
		$success = 0;
		foreach ( $data as $piece ) {
			$success += ( $this->core->wpmc_delete( $piece ) ? 1 : 0 );
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

	function wp_ajax_wpmc_ignore_do() {
		ob_start();
		$data = $_POST['data'];
		$success = 0;
		foreach ( $data as $piece ) {
			$success += ( $this->core->wpmc_ignore( $piece ) ? 1 : 0 );
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

	function wp_ajax_wpmc_recover_do() {
		ob_start();
		$data = $_POST['data'];
		$success = 0;
		foreach ( $data as $piece ) {
			$success +=  ( $this->core->wpmc_recover( $piece ) ? 1 : 0 );
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
}
