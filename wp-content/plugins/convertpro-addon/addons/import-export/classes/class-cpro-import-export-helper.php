<?php
/**
 * Convert Pro Addon Import/export helper class
 *
 * @package ConvertPro Addon
 * @author Brainstorm Force
 */

// Prohibit direct script loading.
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

/**
 * Responsible for all helper functions
 *
 * @since 0.0.1
 */
final class CPRO_Import_Export_Helper {

	/**
	 * The unique instance of the plugin.
	 *
	 * @var array $instance
	 */
	private static $instance;

	/**
	 * The unique instance of the plugin.
	 *
	 * @var string $uploads_url_export_zip
	 */
	private static $uploads_url_export_zip;

	/**
	 * Gets an instance of our plugin.
	 */
	public static function get_instance() {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 */
	private function __construct() {

		add_filter( 'cp_export_option', array( $this, 'cp_export_option' ) );
		add_filter( 'cp_import_option', array( $this, 'cp_import_option' ) );
		add_action( 'admin_post_cp_export_design', array( $this, 'export_design' ) );
		add_action( 'wp_ajax_cp_import_design', array( $this, 'import_design' ) );
		add_filter( 'upload_mimes', array( $this, 'custom_upload_mimes' ) );
		add_action( 'admin_footer', array( $this, 'add_loader' ) );
	}

	/**
	 * Adds loader while export/import is in progress
	 *
	 * @since 1.0.0
	 */
	public function add_loader() {

		$screen = get_current_screen();

		if ( isset( $screen->base ) && strpos( $screen->base, 'convert-pro' ) !== false ) {
			echo '<div class="cp-import-overlay" style="display:none;overflow: hidden;background: #FCFCFC;width: 100%;height: 100%;top: 0;left: 0;z-index: 9999999; position: fixed;">
	                <div class="cp-absolute-loader" style="visibility: visible;overflow: hidden;">
	                    <div class="cp-loader">
	                        <h2 class="cp-loader-text">Importing...</h2>
	                        <div class="cp-loader-wrap">
	                            <div class="cp-loader-bar">
	                                <div class="cp-loader-shadow"></div>
	                            </div>
	                        </div>
	                    </div>
	                </div>
	            </div>';
		}
	}

	/**
	 * Set mimes for export
	 *
	 * @param array $existing_mimes Mimes array.
	 * @since 1.0.0
	 */
	public function custom_upload_mimes( $existing_mimes ) {
		// add your extension to the mimes array as below.
		$existing_mimes['zip'] = 'application/zip';
		$existing_mimes['gz']  = 'application/x-gzip';
		return $existing_mimes;
	}

	/**
	 * Import design HTML
	 *
	 * @param array $options Import export options.
	 * @since 1.0.0
	 */
	public function cp_import_option( $options ) {
		ob_start();
		?>
		<div class="cp-design-btn cp-import-btn" style="margin-right: 10px;">
			<a href="javascript:void(0);" class="cp-md-btn cp-button-style cp-btn-primary">
				<i class="dashicons-upload dashicons"></i>
			</a>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Exports design HTML
	 *
	 * @since 1.0.0
	 */
	public function cp_export_option() {
		ob_start();
		$form_action = admin_url( 'admin-post.php' );
		?>
		<form class="cp_export_form" action="<?php echo $form_action; ?>" method="post">
			<a class="cp-export-action" href="javascript:void(0);" >
				<span class="cp-question-icon"><i class="dashicons dashicons-download"></i></span>
				<span class="cp-question-title"><?php _e( 'Export', 'convertpro-addon' ); ?></span>
			</a>
			<input type="hidden" name="action" value="cp_export_design">
			<input type="hidden" name="popup_id" value="-1">
		</form>
		<?php
		return ob_get_clean();
	}

	/**
	 * Exports design
	 *
	 * @param array $options export import options.
	 * @since 1.0.0
	 */
	public function export_design( $options ) {

		if ( ! current_user_can( 'access_cp_pro' ) ) {
			die( -1 );
		}

		$post_id       = esc_attr( $_POST['popup_id'] );
		$path          = plugin_dir_path( __FILE__ );
		$wp_upload_dir = wp_upload_dir();

		$export = array();

		if ( '' !== $post_id && '-1' !== $post_id ) {

			$dir      = __( 'convertpro', 'convertpro-addon' );
			$name     = 'design_' . $post_id;
			$filename = 'convertpro/' . $name . '.zip';

			$cp_upload_dir = trailingslashit( $wp_upload_dir['basedir'] );

			// create convertpro folder inside uploads directory.
			if ( false === is_dir( $cp_upload_dir . $dir ) ) {
				mkdir( $cp_upload_dir . $dir, 0700 );
			}

			CPRO_Import_Export_Helper::$uploads_url_export_zip = $cp_upload_dir . $filename;

			$dir = $path . $name;

			if ( ! is_dir( $dir ) ) {
				mkdir( $dir, 0777 );
			}

			$export['meta'] = get_post_meta( $post_id );
			$export['data'] = get_post( $post_id );
			$style_settings = get_post_meta( $post_id, 'cp_modal_data', true );
			$style_settings = json_decode( $style_settings );
			$current_domain = $_SERVER['SERVER_NAME'];

			$img_array  = array();
			$i          = 0;
			$arr        = '';
			$post_terms = array();

			foreach ( $style_settings as $key => $settings ) {
				foreach ( $settings as $k => $setting ) {

					if ( isset( $setting->type ) ) {
						switch ( $setting->type ) {
							case 'panel':
								$bg_img_array = array();

								foreach ( $setting->panel_bg_image as $image ) {

									$arr = explode( '|', $image );

									// size of image will be always full after exporting.
									if ( isset( $arr[2] ) ) {
										$arr[2] = 'full';
									}

									if ( 0 != $arr[0] ) {

										if ( 'string' == gettype( $image ) && false !== strpos( $image, $current_domain ) ) {
											$img_array[ $i ] = $image;
										}

										if ( isset( $setting->panel_bg_image_sizes ) ) {
											$image_sizes = $setting->panel_bg_image_sizes;
											if ( isset( $image_sizes->full->url ) ) {
												$full_img_src = $image_sizes->full->url;

												if ( false !== strpos( $full_img_src, $current_domain ) ) {

													$img_data    = explode( '|', $image );
													$img_data[1] = $full_img_src;

													$img_array[ $i ] = implode( '|', $img_data );
												}
											}
										}

										$arr[1] = '{{CP_MEDIA_' . $i . '}}';
										$arr[0] = '{{CP_MEDIA_ID_' . $i . '}}';

										if ( 'string' == gettype( $image ) && false !== strpos( $image, $current_domain ) ) {

											if ( isset( $setting->panel_bg_image_sizes ) ) {
												$media_sizes                   = '{{CP_MEDIA_SIZES_' . $i . '}}';
												$setting->panel_bg_image_sizes = $media_sizes;
											}

											$bg_img_array[] = implode( '|', $arr );
											$i++;
										}
									}
								}

								$setting->panel_bg_image = $bg_img_array;

								break;

							case 'cp_close_image':
							case 'cp_image':
								$arr = explode( '|', $setting->module_image );

								// size of image will be always full after exporting.
								if ( isset( $arr[2] ) ) {
									$arr[2] = 'full';
								}

								if ( 0 != $arr[0] ) {

									if ( 'string' == gettype( $setting->module_image ) && false !== strpos( $setting->module_image, $current_domain ) ) {
										$img_array[ $i ] = $setting->module_image;
									}

									if ( isset( $setting->module_image_sizes ) ) {
										$image_sizes = $setting->module_image_sizes;
										if ( isset( $image_sizes->full->url ) ) {
											$full_img_src = $image_sizes->full->url;

											if ( false !== strpos( $full_img_src, $current_domain ) ) {

												$img_data    = explode( '|', $setting->module_image );
												$img_data[1] = $full_img_src;

												$img_array[ $i ] = implode( '|', $img_data );
											}
										}
									}

									$arr[1] = '{{CP_MEDIA_' . $i . '}}';
									$arr[3] = '{{CP_MEDIA_ID_' . $i . '}}';

									if ( 'string' == gettype( $setting->module_image ) && false !== strpos( $setting->module_image, $current_domain ) ) {

										if ( isset( $setting->module_image_sizes ) ) {
											$media_sizes                 = '{{CP_MEDIA_SIZES_' . $i . '}}';
											$setting->module_image_sizes = $media_sizes;
										}

										$setting->module_image = implode( '|', $arr );
										$i++;
									}
								}
								break;
						}
					}
				}
			}

			$export['cp_modal_data'] = json_encode( $style_settings );
			$export['cp_modal_data'] = wp_slash( $export['cp_modal_data'] );
			$export['image_mapping'] = $img_array;

			$taxonomies = get_object_taxonomies( $export['data']->post_type );
			// Returns array of taxonomy names for post type, ex array("category", "post_tag").
			foreach ( $taxonomies as $taxonomy ) {
				if ( CP_AB_TEST_TAXONOMY != $taxonomy ) {
					$post_terms[ $taxonomy ] = wp_get_object_terms(
						$post_id, $taxonomy, array(
							'fields' => 'slugs',
						)
					);
				}
			}

			$export['taxonomy'] = $post_terms;
			$arr                = '';

			$img_dir = $path . $name . '/';

			if ( ! is_dir( $img_dir ) ) {
				mkdir( $img_dir, 0777 );
			}

			foreach ( $img_array  as $key => $value ) {
				$arr = explode( '|', $value );
				$ext = pathinfo( $arr[1], PATHINFO_EXTENSION );

				// if image is not hosted on current domain?
				if ( false === strpos( $arr[1], $current_domain ) ) {
					continue;
				}

				$end_url = str_replace( array( 'http://', 'https://', '//' ), '', $arr[1] );

				$protocol = strtolower( substr( $_SERVER['SERVER_PROTOCOL'], 0, strpos( $_SERVER['SERVER_PROTOCOL'], '/' ) ) ) . '://';

				$end_url = $protocol . $end_url;

				copy( $end_url, $img_dir . '/' . $key . '.' . $ext );
			}

			$content   = json_encode( $export );
			$file_name = $dir . '/' . $name . '.txt';
			$file_url  = plugins_url( $file_name, __FILE__ );

			WP_Filesystem();
			global $wp_filesystem;
			$wp_filesystem->put_contents(
				$file_name,
				$content,
				FS_CHMOD_FILE // predefined mode settings for WP files.
			);

			$zip = new ZipArchive();

			if ( $zip->open( CPRO_Import_Export_Helper::$uploads_url_export_zip, ZIPARCHIVE::CREATE | ZipArchive::OVERWRITE ) !== true ) {
				exit( "cannot open <CPRO_Import_Export_Helper::$uploads_url_export_zip>\n" );
			}

			$root_path = realpath( $dir );

			// Create recursive directory iterator.
			$files = new RecursiveIteratorIterator(
				new RecursiveDirectoryIterator( $root_path ),
				RecursiveIteratorIterator::LEAVES_ONLY
			);

			foreach ( $files as $name => $file ) {
				// Skip directories (they would be added automatically).
				if ( ! $file->isDir() ) {
					// Get real and relative path for current file.
					$file_path     = $file->getRealPath();
					$relative_path = substr( $file_path, strlen( $root_path ) + 1 );

					// Add current file to archive.
					$zip->addFile( $file_path, $relative_path );
				}
			}

			// Zip archive will be created only after closing object.
			$zip->close();

			$export_file = CPRO_Import_Export_Helper::$uploads_url_export_zip;

			header( 'Pragma: public' );   // required.
			header( 'Expires: 0' );       // no cache.
			header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
			header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s', filemtime( $export_file ) ) . ' GMT' );
			header( 'Content-Description: File Transfer' );
			header( 'Cache-Control: public' );
			header( 'Content-type: application/octet-stream' );
			header( 'Content-Disposition: attachment; filename="' . basename( $export_file ) . '"' );
			header( 'Content-Transfer-Encoding: binary' );
			header( 'Connection: close' );

			flush();
			ob_end_clean();
			readfile( $export_file );

			// Remove exported directory and its content.
			$wp_filesystem->delete( $export_file );
			$wp_filesystem->rmdir( $dir, true );

			exit();
		}
	}

	/**
	 * Exports design
	 *
	 * @since 1.0.0
	 */
	public function import_design() {

		$response = array(
			'error'    => false,
			'msg'      => '',
			'edit_url' => '',
		);

		$posted_file = $_POST['file'];
		$paths       = wp_upload_dir();

		if ( isset( $posted_file['id'] ) && '' != $posted_file['id'] ) {

			$file_title = sanitize_file_name( $posted_file['title'] );

			$file = realpath( get_attached_file( $posted_file['id'] ) );

			// Get the name of the directory inside the exported zip.
			$zip = zip_open( $file );

			// valid zip file.
			if ( ! is_resource( $zip ) ) {
				$response['error'] = true;
				/* translators: %s: zip object */
				$response['msg'] = sprintf( __( 'Failed to Open. Error Code: %s', 'convertpro-addon' ), $zip );
				return wp_send_json_success( $response );
			}

			// Set the path variable for extracting the zip.
			$paths['export']   = $file_title;
			$cpro_folder_path  = trailingslashit( $paths['basedir'] ) . 'convertpro/';
			$paths['tempdir']  = $cpro_folder_path . $file_title;
			$paths['temp']     = $cpro_folder_path . trailingslashit( $file_title );
			$paths['tempurl']  = trailingslashit( $paths['baseurl'] ) . 'convertpro/' . trailingslashit( $file_title );
			$paths['basepath'] = $cpro_folder_path . trailingslashit( $file_title );
			$folder_path       = $cpro_folder_path . trailingslashit( $file_title );

			// Create the respective directory inside wp-uploads directory.
			if ( ! is_dir( $paths['temp'] ) ) {
				$tempdir = $this->create_folder( $paths['temp'], false );
			}
			WP_Filesystem();
			global $wp_filesystem;
			$destination_path = trailingslashit( $paths['basedir'] ) . 'convertpro/' . $file_title;

			// Extract the zip to our newly created directory.
			$unzipfile = unzip_file( $file, $destination_path );

			if ( ! $unzipfile ) {
				$response['error'] = true;
				$response['msg']   = __( 'Unable to extract the file.', 'convertpro-addon' );
				return wp_send_json_success( $response );
			}

			$folder_path = trailingslashit( $folder_path );

			// grant permission.
			$wp_filesystem->chmod( $folder_path, 0755, true );

			$new_folder_path = $paths['basepath'];

			// rename folder.
			$wp_filesystem->move( $folder_path, $new_folder_path );

			// When the zip file has custom name.
			$zip = new ZipArchive;
			if ( $zip->open( $file ) ) {
				// Ignore the PHPCS warning about constant declaration.
				// @codingStandardsIgnoreStart
				for ( $i = 0; $i < $zip->numFiles; $i++ ) {
					$filename = $zip->getNameIndex( $i );
					if ( false !== strpos( $filename, '.txt' ) ) {
						$file_title = str_replace( '.txt', '', $filename );
					}
				}
				// @codingStandardsIgnoreEnd
			}

			// Set the json file file url to get the settings for the style.
			$json_file = $paths['tempurl'] . $file_title . '.txt';
			// Read the text file containing the json formatted settings of style and decode it.
			$content = wp_remote_get( $json_file );
			if ( ! is_wp_error( $content ) ) {
				$json                 = $content['body'];
				$obj                  = json_decode( $json, true );
				$response             = $this->create_design( $obj, $new_folder_path );
				$response['edit_url'] = get_edit_post_link( $response['post_id'], '' );

			} else {
				$response['error'] = true;
				$response['msg']   = $content->get_error_message();
			}
		} else {
			$response['error'] = true;
			$response['msg']   = __( 'Invalid file ID', 'convertpro-addon' );
		}

		return wp_send_json_success( $response );
	}

	/**
	 * Create design
	 *
	 * @param int    $design design ID.
	 * @param string $path file directory path.
	 * @since 1.0.0
	 */
	public function create_design( $design, $path ) {
		$response = array(
			'error'   => false,
			'msg'     => '',
			'post_id' => -1,
		);

		if ( isset( $design['data'] ) ) {

			$data        = $design['data'];
			$meta        = $design['meta'];
			$img_mapping = $design['image_mapping'];
			$modal_data  = $design['cp_modal_data'];

			$new_design = array(
				'post_content'   => $data['post_content'],
				'post_title'     => $data['post_title'],
				'post_status'    => $data['post_status'],
				'comment_status' => $data['comment_status'],
				'ping_status'    => $data['ping_status'],
				'post_type'      => $data['post_type'],
			);

			$id = wp_insert_post( $new_design );

			if ( ! is_wp_error( $id ) ) {
				$response['post_id'] = $id;

				$taxonomies = get_object_taxonomies( $data['post_type'] );

				foreach ( $taxonomies as $taxonomy ) {
					if ( CP_AB_TEST_TAXONOMY != $taxonomy ) {
						wp_set_object_terms( $id, $design['taxonomy'][ $taxonomy ], $taxonomy, false );
					}
				}

				if ( ! empty( $img_mapping ) ) {

					// Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
					require_once( ABSPATH . 'wp-admin/includes/image.php' );

					foreach ( $img_mapping as $key => $img ) {

						$img_arr = explode( '|', $img );
						$ext     = pathinfo( $img_arr[1], PATHINFO_EXTENSION );

						// $filename should be the path to a file in the upload directory.
						$filename = trailingslashit( $path ) . $key . '.' . $ext;

						// Check the type of file. We'll use this as the 'post_mime_type'.
						$filetype = wp_check_filetype( basename( $filename ), null );

						// Get the path to the upload directory.
						$wp_upload_dir = wp_upload_dir();

						// Prepare an array of post data for the attachment.
						$attachment = array(
							'post_mime_type' => $filetype['type'],
							'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
							'post_content'   => '',
							'post_status'    => 'inherit',
						);

						// Insert the attachment.
						$attach_id = wp_insert_attachment( $attachment, $filename );

						if ( '' != $attach_id ) {

							$image_size = isset( $img_arr[2] ) ? $img_arr[2] : 'full';

							$replace_to = wp_get_attachment_image_src( $attach_id, $image_size );

							$replace_to = $replace_to[0];

							$replace_to   = str_replace( array( 'http://', 'https://' ), '//', $replace_to );
							$replace_from = '{{CP_MEDIA_' . $key . '}}';

							// replace image url.
							$modal_data = str_replace( $replace_from, $replace_to, $modal_data );

							$replace_from = '{{CP_MEDIA_ID_' . $key . '}}';

							// replace image id.
							$modal_data = str_replace( $replace_from, $attach_id, $modal_data );

							$sizes_data = array(
								'full' => $replace_to,
							);

							$replace_to = htmlspecialchars( json_encode( $sizes_data ) );

							$replace_from = '{{CP_MEDIA_SIZES_' . $key . '}}';

							// replace media sizes.
							$modal_data = str_replace( $replace_from, $replace_to, $modal_data );

						} else {
							$response['error'] = true;
							$response['msg']   = __( 'Error in uploading images', 'convertpro-addon' );
						}
					}
				}

				foreach ( $meta as $key => $m ) {
					if ( 'cp_modal_data' == $key ) {
						$meta['cp_modal_data'][0] = $modal_data;
						$value                    = $meta[ $key ][0];
					} elseif ( 'form' == $key || 'panel' == $key || 'launch' == $key || 'embed' == $key || 'cookies' == $key || 'pages' == $key || 'visitors' == $key || 'schedule' == $key || 'connect' == $key || 'email' == $key ) {
						$value = unserialize( $meta[ $key ][0] );
					} elseif ( 'live' == $key ) {
						$value = 0;
					} else {
						$value = $meta[ $key ][0];
					}
					update_post_meta( $id, $key, $value );
				}
			} else {
				$response['error'] = true;
				$response['msg']   = __( 'Error in creating design', 'convertpro-addon' );
			}
		} else {
			$response['error'] = true;
			$response['msg']   = __( 'Invalid file!', 'convertpro-addon' );
		}
		return $response;
	}

	/**
	 * Create folder
	 *
	 * @param string $folder folder path.
	 * @param bool   $addindex file directory path.
	 * @since 1.0.0
	 */
	public function create_folder( &$folder, $addindex = true ) {
		if ( is_dir( $folder ) && false == $addindex ) {
			return true;
		}
		$folder  = trailingslashit( $folder );
		$created = wp_mkdir_p( $folder );

		if ( false == $addindex ) {
			return $created;
		}
		$index_file = trailingslashit( $folder ) . 'index.php';
		if ( file_exists( $index_file ) ) {
			return $created;
		}

		WP_Filesystem();
		global $wp_filesystem;

		$wp_filesystem->put_contents(
			$index_file,
			"<?php\r\necho 'Sorry, browsing the directory is not allowed!';\r\n?>",
			FS_CHMOD_FILE // predefined mode settings for WP files.
		);
		return $created;
	}
}

$cp_import_export_helper = CPRO_Import_Export_Helper::get_instance();
