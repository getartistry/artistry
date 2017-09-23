<?php
/**
 * Plugin Name: Express Login For WordPress
 * Plugin URI: http://www.storeapps.org/product/express-login-for-wordpress/
 * Description: Allow automatic login for customers by passing special parameters in the URL
 * Version: 1.3.3
 * Author: StoreApps
 * Author URI: http://www.storeapps.org/
 * 
 * Text Domain: express-login-for-wordpress
 * License: GPLv2 or later
 * Copyright (c) 2013, 2014, 2015, 2016 StoreApps All rights reserved.
 */

if ( !defined( 'ABSPATH' ) ) exit;

if ( !class_exists( 'Express_Login_For_Wordpress' ) ) {

	class Express_Login_For_Wordpress {

		static $text_domain;

		public function __construct() {
			
			$php_version = phpversion();

			if ( version_compare( $php_version, '5.4.0', '<' ) ) {
				if ( session_id() == '' ) {
				    session_start();
				}
			} else {
				if ( session_status() == PHP_SESSION_NONE ) {
				    session_start();
				}
			}

			$this->express_login_errors = array(
												'pk'		=> __( 'Public Key Empty', self::$text_domain ),
												'email'		=> __( 'Email empty', self::$text_domain ),
												'token'		=> __( 'Token empty', self::$text_domain ),
												'expiry'	=> __( 'Link expired', self::$text_domain ),
												'unauth'	=> __( 'Authentication failed', self::$text_domain )
											);
			
			add_action( 'init', array( $this, 'localize' ) );
			add_action( 'init', array( $this, 'express_login_hook' ) );

			add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'plugin_action_links' ) );
			
			add_action( 'admin_menu', array( $this, 'add_express_login_setting_page' ) );

			add_filter( 'sa_express_login_link', array( $this, 'generate_link_for_express_login' ), 10, 4 );
			add_filter( 'sa_bulk_express_login_link', array( $this, 'bulk_generate_link_for_express_login' ), 10, 5 );
			add_filter( 'wp_login_errors', array( $this, 'wp_express_login_errors' ), 10, 2 );

			add_action( 'admin_footer', array( $this, 'express_login_script' ) );
			add_action( 'wp_ajax_express_login_setting_action', array( $this, 'express_login_setting_action' ) );

            add_action( 'in_admin_footer', array( $this, 'add_social_links' ) );

            add_filter( 'sa_active_plugins_for_quick_help', array( $this, 'is_page_for_notifications' ), 10, 2 );

		}

		/**
         * Language loader
         */
        function localize() {

            $text_domains = array( 'express-login-for-wordpress', 'sa_express_login' );        // For Backward Compatibility

            $plugin_dirname = dirname( plugin_basename(__FILE__) );

            foreach ( $text_domains as $text_domain ) {

                self::$text_domain = $text_domain;

                $locale = apply_filters( 'plugin_locale', get_locale(), self::$text_domain );

                $loaded = load_textdomain( self::$text_domain, WP_LANG_DIR . '/' . $plugin_dirname . '/' . self::$text_domain . '-' . $locale . '.mo' );

                if ( ! $loaded ) {
                    $loaded = load_plugin_textdomain( self::$text_domain, false, $plugin_dirname . '/languages' );
                }

                if ( $loaded ) {
                    break;
                }

            }

        }

		function express_login_hook() {
			global $wpdb;
			
			if ( ! empty( $_REQUEST['expresslogin'] ) && $_REQUEST['expresslogin'] == 1 ) {

				if ( is_user_logged_in() ) {
					wp_logout();
				}

				if ( ! is_array( $_REQUEST ) ) {
					auth_redirect();
				}

		    	$show_login_form = false;
		    	$error_messages = array();

		    	if ( is_array( $_REQUEST ) && array_key_exists( 'pk', $_REQUEST ) && empty( $_REQUEST['pk'] ) ) {
		    		$show_login_form = true;
		    		$error_messages[] = $this->express_login_errors['pk'];
		    	} elseif ( is_array( $_REQUEST ) && array_key_exists( 'email', $_REQUEST ) && empty( $_REQUEST['email'] ) ) {
		    		$show_login_form = true;
		    		$error_messages[] = $this->express_login_errors['email'];
		    	}

		    	if ( !isset( $_REQUEST['token'] ) ) {
		    		$show_login_form = true;
		    		$error_messages[] = $this->express_login_errors['token'];
		    	}

		    	if ( isset( $_REQUEST['expiry'] ) ) {
		    		if ( strtotime( 'NOW' ) > $_REQUEST['expiry'] ) {
		    			$show_login_form = true;
			    		$error_messages[] = $this->express_login_errors['expiry'];
		    		}
		    	}

		    	if ( $show_login_form ) {
		    		if ( !empty( $error_messages ) ) {
		    			$express_login_error_codes = implode( '. ', $error_messages );
		    			$_SESSION['express_login_errors'] = $express_login_error_codes;
		    		}
		    		auth_redirect();
		    	}

	    		$secret_key = get_option( 'sa_express_login_secret_key' );

	    		if ( array_key_exists( 'pk', $_REQUEST ) && ! empty( $_REQUEST['pk'] ) ) {
	        		$pk = urldecode( $_REQUEST['pk'] );
	        		$column_name = "MD5( user_email )";
	    		} elseif ( array_key_exists( 'email', $_REQUEST ) && ! empty( $_REQUEST['email'] ) ) {
	        		$pk = urldecode( $_REQUEST['email'] );
	        		$column_name = "user_email";
	    		} else {
	    			$pk = '';
	    			$column_name = "user_email";
	    		}

	        	$expiry = isset( $_REQUEST['expiry'] ) ? urldecode( $_REQUEST['expiry'] ) : '';
	        	$token = urldecode( $_REQUEST['token'] );

	        	if ( ! wp_check_password( trim( $pk . $secret_key . $expiry ), $token ) ) {
		    		$error_messages = array( $this->express_login_errors['unauth'] );
		    		$_SESSION['express_login_errors'] = implode( '. ', $error_messages );
		    		auth_redirect();
				}

				$user_id = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM {$wpdb->users} WHERE {$column_name} = %s", trim( $pk ) ) );
				$user = new WP_User( $user_id );

				$user_id = $user->ID;
                $user_login = $user->login;

                //login
                wp_set_current_user($user_id, $user_login);
                wp_set_auth_cookie($user_id);
                do_action('wp_login', $user_login, $user);
                
                $redirect_to = remove_query_arg( array( 'expresslogin', 'pk', 'email', 'token', 'expiry' ) );
                
                if ( empty( $redirect_to ) ) {
                    $redirect_to = home_url( '/', ( is_ssl() ? 'https' : 'http' ) );
                }  
                
                wp_safe_redirect( $redirect_to );
                exit;

		    } 
		} // Over hook


		function wp_express_login_errors( $errors, $redirect_to ) {
			
			if ( !empty( $_SESSION['express_login_errors'] ) ) {
				if ( empty( $errors ) ) {
					$errors = new WP_Error();
				}
				$errors->add( 'error', $_SESSION['express_login_errors'] );
			}

			return $errors;
		}

		function express_login_script() {
			?>
				<script type="text/javascript">
					jQuery(function(){
						
						jQuery('input#export_button').on('click', function( e ){
							var redirect_url = jQuery('input#sa_express_login_redirect_to').val();
							if ( redirect_url != undefined && redirect_url != '' ) {
								var index = redirect_url.indexOf( location.host );
								if ( index == -1 ) {
									jQuery('input#sa_express_login_redirect_to').css('border-color', 'red');
									jQuery('input#sa_express_login_redirect_to').attr('title', '<?php _e( "This URL should be from this site only", "sa_express_login" ) ?>');
								} else {
									jQuery('input#sa_express_login_redirect_to').removeAttr('title');
								}
							}
						});

						jQuery('form#express_login_settings').on('click', 'input#save_changes', function(){
							var status_element = jQuery('span#secret_key_action_status');
							status_element.removeAttr('color');
							status_element.addClass('spinner').addClass('is-active');
							jQuery.ajax({
								url: '<?php echo admin_url( "admin-ajax.php" ); ?>',
								type: 'POST',
								dataType: 'json',
								data: {
									action 		: 'express_login_setting_action',
									button 		: jQuery(this).attr( 'id' ),
									secret_key  : jQuery( 'input#sa_express_login_secret_key' ).val(),
									security 	: '<?php echo wp_create_nonce( "express-login-ajax-action" ); ?>'
								},
								success: function( response ) {
									status_element.removeClass('is-active').removeClass('spinner');
									if ( response.result === 'success' ) {
										status_element.addClass('dashicons').removeClass('dashicons-no-alt').addClass('dashicons-yes').css('color', 'green');
									} else {
										status_element.addClass('dashicons').removeClass('dashicons-yes').addClass('dashicons-no-alt').css('color', 'red');
									}
								}
							});
						});

						jQuery('form#express_login_settings').on('click', 'input#export_button', function(){
							var button				= jQuery(this).attr( 'id' );
							var is_expire			= ( jQuery('input#sa_express_login_enable_timestamp').is(':checked') ) ? 'yes' : 'no';
							var duration			= jQuery('input#sa_express_login_timestamp').val();
							var duration_suffix		= jQuery('select[name="sa_express_login_timestamp_suffix"]').val();
							var redirect_to			= jQuery('input#sa_express_login_redirect_to').val();
							var security			= '<?php echo wp_create_nonce( "express-login-ajax-action" ); ?>';
							jQuery(this).append('<iframe src="<?php echo admin_url( "admin-ajax.php?action=express_login_setting_action&button='+button+'&is_expire='+is_expire+'&duration='+duration+'&duration_suffix='+duration_suffix+'&redirect_to='+redirect_to+'&security='+security+'" ); ?>" style="display: none;"></iframe>');
						});

					});
				</script>
			<?php
		}

		function express_login_setting_action() {

			check_ajax_referer( 'express-login-ajax-action', 'security' );

			$return = array();

			if ( ! empty( $_REQUEST['button'] ) && $_REQUEST['button'] == 'save_changes' && ! empty( $_REQUEST['secret_key'] ) ) {
				try {
					update_option( 'sa_express_login_secret_key', $_REQUEST['secret_key'] );
					$return['result'] = 'success';
					$return['message'] = __( 'Updated successfully!', self::$text_domain );
				} catch( Exception $e ) {
					$return['result'] = 'fail';
					$return['message'] = __( $e->getMessage(), self::$text_domain );
				}
				echo json_encode( $return );
				die();
			}

			if ( ! empty( $_REQUEST['button'] ) && $_REQUEST['button'] == 'export_button' ) {
				$timestamp = '';
				if ( isset( $_REQUEST['is_expire'] ) && $_REQUEST['is_expire'] == 'yes' ) {
					$timestamp = strtotime( 'NOW +' . $_REQUEST['duration'] . ' ' . $_REQUEST['duration_suffix'] );
				}

				$secret_key = get_option( 'sa_express_login_secret_key' );
				$redirect_to = ( ! empty( $_REQUEST['redirect_to'] ) ) ? $_REQUEST['redirect_to'] : '';

				if ( empty( $redirect_to ) ) {
					$redirect_to = home_url( '/', ( is_ssl() ? 'https' : 'http' ) );
				}

				// WP_User_Query arguments
				$args = array (
					'fields' => array( 'id', 'user_login', 'user_nicename', 'user_email', 'display_name' ),
				);

				// The User Query
				$user_query = new WP_User_Query( $args );

				$users_header = array(
						'user_id' => __( 'User ID', self::$text_domain ),
						'user_email' => __( 'User Email', self::$text_domain ),
						'user_first_name' => __( 'First Name', self::$text_domain ),
						'user_last_name' => __( 'Last Name', self::$text_domain ),
						'express_link' => __( 'Express Link', self::$text_domain )
					);

				$users_data = array();

				foreach ( $user_query->results as $index => $user ) {
					
					$users_data[ $user->id ] = array();

					$users_data[ $user->id ][ 'user_id' ] = $user->id;

					$first_name = get_user_meta( $user->id, 'first_name', true );
					$last_name = get_user_meta( $user->id, 'last_name', true );

					if ( empty( $first_name ) && empty( $last_name ) ) {
						$first_name = $user->display_name;
					}

					if ( empty( $first_name ) && empty( $last_name ) ) {
						$first_name = $user->user_login;
					}

					$users_data[ $user->id ][ 'user_email' ] = $user->user_email;
					$users_data[ $user->id ][ 'user_first_name' ] = $first_name;
					$users_data[ $user->id ][ 'user_last_name' ] = $last_name;

					$unhashed_token = md5( $user->user_email ) . $secret_key;

					if ( !empty( $timestamp ) ) {
						$unhashed_token .= $timestamp;
					}

					$token = wp_hash_password( trim( $unhashed_token ) );

					$users_data[ $user->id ][ 'express_link' ] = add_query_arg( array( 'expresslogin' => 1, 'pk' => urlencode( md5( $user->user_email ) ), 'token' => urlencode( $token ) ), trailingslashit( $redirect_to ) );

					if ( !empty( $timestamp ) ) {
						$users_data[ $user->id ][ 'express_link' ] = add_query_arg( array( 'expiry' => urlencode( $timestamp ) ), $users_data[ $user->id ][ 'express_link' ] );
					}
				}

				if ( !empty( $users_data ) ) {
					$filename = 'users_'.date( 'd-M-Y_H-i-s', time() ).'.csv';
					if ( ob_get_level() ) {
						$levels = ob_get_level();
						for ( $i = 0; $i < $levels; $i++ ) {
							@ob_end_clean();
						}
					} else {
						@ob_end_clean();
					}
					ob_start();
					$fp = fopen( 'php://output', 'w' ) or wp_die( __( 'Cannot create file', self::$text_domain ) );
					fputcsv( $fp, array_values( $users_header ) );
					foreach ( $users_data as $user_id => $user_data ) {
						fputcsv( $fp, array_values( $user_data ) );
					}
					$file_content = ob_get_clean();
					nocache_headers();
					header( "X-Robots-Tag: noindex, nofollow", true );
					header( "Content-Type: text/x-csv; charset=UTF-8" );
					header( "Content-Description: File Transfer" );
					header( "Content-Transfer-Encoding: binary" );
					header( "Content-Disposition: attachment; filename=\"" . sanitize_file_name( $filename ) . "\";" );
					echo $file_content;
					exit();
				}

			}

		}

		function add_social_links() {

            if ( ! is_callable( 'StoreApps_Upgrade_1_4', 'add_social_links' ) ) return;

            if ( ( ! empty( $_REQUEST['page'] ) && $_REQUEST['page'] == 'sa_express_login' ) ) {
                echo '<div class="sa_express_login_social_links" style="padding-bottom: 1em;">' . StoreApps_Upgrade_1_4::add_social_links( 'sa_express_login' ) . '</div>';
            }

        }

        function is_page_for_notifications( $active_plugins = array(), $upgrader = null ) {
            if ( ! empty( $_GET['page'] ) && $_GET['page'] == 'sa_express_login' ) {
                $active_plugins['elwp'] = 'express-login-for-wordpress';
            } elseif ( array_key_exists( 'elwp', $active_plugins ) ) {
            	unset( $active_plugins['elwp'] );
            }
            return $active_plugins;
        }

        function generate_link_for_express_login( $link = '', $email = '', $valid_for = '', $link_text = '' ) {
			
			if ( empty( $email ) ) return $link;

			if ( empty( $link ) ) $link = home_url( '/', ( is_ssl() ? 'https' : 'http' ) );

			$secret_key = get_option( 'sa_express_login_secret_key' );

			$timestamp = '';
			if ( !empty( $valid_for ) ) {
				$timestamp = strtotime( 'NOW +' . $valid_for );
			}

			$token = wp_hash_password( trim( md5( $email ) . $secret_key . $timestamp ) );

			$link = add_query_arg( array( 'expresslogin' => 1, 'pk' => urlencode( md5( $email ) ), 'token' => urlencode( $token ) ), $link );

			if ( !empty( $timestamp ) ) {
				$link = add_query_arg( array( 'expiry' => urlencode( $timestamp ) ), $link );
			}

			if ( !empty($link_text) ) {
				$link = '<a href="'.$link.'">'.$link_text.'</a>';
			}

			return $link;

		}

		function bulk_generate_link_for_express_login( $generated_links = array(), $link = '', $emails = array(), $valid_for = '', $link_text = '' ) {

			if ( empty( $emails ) ) {
				return $generated_links;
			}

			foreach ( $emails as $email ) {
				$generated_links[ $email ] = apply_filters( 'sa_express_login_link', $link, $email, $valid_for, $link_text );
			}

			return $generated_links;

		}

		function plugin_action_links( $links ) {

			$start_link = add_query_arg( 'page', 'sa_express_login', admin_url( 'options-general.php' ) );

	        $action_links = array(
				        		'start' => '<a href="'.$start_link.'" title="' . __( 'Get started', self::$text_domain ) . '">' . __( 'Get started', self::$text_domain ) . '</a>'
				        	);

	        return ( ! empty( $action_links ) ) ? array_merge( $action_links, $links ) : $links;

		}

		function add_express_login_setting_page() {
			add_options_page( __( 'Express Login Settings', self::$text_domain ), __( 'Express Login', self::$text_domain ), 'manage_options', 'sa_express_login', array( $this, 'express_login_setting_page_content' ) );
		}

		function express_login_setting_page_content() {
			?>
			<div class="wrap">
				<style type="text/css">
					#icon-express-login {
						background-image: url(../wp-admin/images/welcome-icons-2x.png);
						background-position-y: -552px;
					}
					#sa_express_login_enable_timestamp {
						width: 1.1em;
					}
					#sa_express_login_timestamp {
						width: 5em;
					}
					#sa_express_login_secret_key {
						width: 20em;
					}
					#export_button,
					#save_changes {
						width: auto;
					}
				</style>
				<div id="icon-express-login" class="icon32"><br></div>
				<h2><?php _e( 'Express Login For WordPress', self::$text_domain ) ?></h2>

				<form id="express_login_settings" action="" method="post">
					<br class="clear">
					<h3><?php _e( 'General Settings' ); ?></h3>
					<table class="form-table">
						<tr class="form-field form-required" valign="top">
							<th scope="row"><label for="sa_express_login_secret_key"><?php _e( 'Secret Key', self::$text_domain ) ?></label></th>
							<td>
								<input name="sa_express_login_secret_key" type="text" id="sa_express_login_secret_key" value="<?php echo get_option( 'sa_express_login_secret_key' ); ?>" required/> <input type="button" class="button button-primary" name="save_changes" id="save_changes" value="<?php _e( 'Save', self::$text_domain ); ?>" />
								<span id="secret_key_action_status" style="position: absolute; font-size: 2em;"></span>
								<p class="description"><?php _e( 'This key will be used to generate express login links. If you change this, all previous links will stop working.', self::$text_domain ) ?></p>
							</td>
						</tr>
					</table>
					<br class="clear">
					<h3><?php _e( 'Export Users' ); ?></h3>
					<table class="form-table">
						<tr class="form-field" valign="top">
							<th scope="row"><label for="sa_express_login_additional_security"><?php _e( 'Link Expiry (optional)', self::$text_domain ) ?></label></th>
							<td>
								<label for="sa_express_login_enable_timestamp"><input name="sa_express_login_enable_timestamp" type="checkbox" id="sa_express_login_enable_timestamp" value="yes" />
								<?php _e( 'Expires after ', self::$text_domain ); ?></label> <input type="number" step="any" name="sa_express_login_timestamp" id="sa_express_login_timestamp" value="2" min="0.01"/>
								<select name="sa_express_login_timestamp_suffix">
									<option value="HOURS"><?php _e( 'Hours', self::$text_domain ); ?></option>
									<option value="DAYS"><?php _e( 'Days', self::$text_domain ); ?></option>
									<option value="WEEKS" selected="selected"><?php _e( 'Weeks', self::$text_domain ); ?></option>
									<option value="MONTHS"><?php _e( 'Months', self::$text_domain ); ?></option>
								</select>
								<p class="description"><?php _e( 'Select a time period after which generated express login links will expire.', self::$text_domain ) ?></p>
							</td>
						</tr>
						<tr class="form-field" valign="top">
							<th scope="row"><label for="sa_express_login_redirect_to"><?php _e( 'Redirect To', self::$text_domain ) ?></label></th>
							<td>
								<input name="sa_express_login_redirect_to" type="text" id="sa_express_login_redirect_to" value="" placeholder="<?php echo home_url( '/', ( is_ssl() ? 'https' : 'http' ) ); ?>" />
								<p class="description"><?php _e( 'Users will be redirected to this URL after express login.', self::$text_domain ) ?></p>
							</td>
						</tr>
						<tr class="form-field" valign="top">
							<th scope="row"><?php _e( 'Export Users With Express Login Links', self::$text_domain ) ?></th>
							<td>
								<input type="button" class="button" name="export" id="export_button" value="<?php _e( 'Download .csv', 'sa_express_login' ); ?>" />
								<span id="express_login_setting_status" style="position: absolute; font-size: 2em;"></span>
							</td>
						</tr>
					</table>
					<br class="clear">
				</form>
				<br class="clear">
			</div>
			<?php
		}

	}

    require_once 'sa-includes/class-wc-compatibility.php';

	$GLOBAL['sa_express_login'] = new Express_Login_For_Wordpress();

    if ( !class_exists( 'StoreApps_Upgrade_1_4' ) ) {
        require_once 'sa-includes/class-storeapps-upgrade-v-1-4.php';
    }

    $sku = 'elwp';
    $prefix = 'sa_express_login';
    $plugin_name = 'Express Login For WordPress';
    $text_domain = Express_Login_For_Wordpress::$text_domain;
    $documentation_link = 'http://www.storeapps.org/knowledgebase_category/express-login-for-wordpress/';
    $GLOBALS['sa_express_login_upgrade'] = new StoreApps_Upgrade_1_4( __FILE__, $sku, $prefix, $plugin_name, $text_domain, $documentation_link );

}