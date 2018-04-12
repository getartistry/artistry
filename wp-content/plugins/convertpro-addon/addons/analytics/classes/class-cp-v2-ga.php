<?php
/**
 * CP_V2_GA.
 *
 * @package Convert Pro
 */

// Prohibit direct script loading.
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

if ( ! class_exists( 'CP_V2_GA' ) ) {

	/**
	 * Class CP_V2_GA.
	 */
	class CP_V2_GA {

		/**
		 * View actions
		 *
		 * @var view_actions
		 */
		private $ga_instance;

		/**
		 * AccessToken
		 *
		 * @var access_token
		 */
		private $access_token = '';

		/**
		 * Client id
		 *
		 * @var client_id
		 */
		private $client_id = '853470414267-rqdo6lto825h04j6kmlvbqhcih4qpqvb.apps.googleusercontent.com';

		/**
		 * Client secret
		 *
		 * @var client_secret
		 */
		private $client_secret = 'MKWqvrC7jMo3-nsiC7qVbht5';

		/**
		 * Redirect URI
		 *
		 * @var redirect_uri
		 */
		private $redirect_uri = 'urn:ietf:wg:oauth:2.0:oob';

		/**
		 * Constructor
		 */
		function __construct() {

			$analytics_lib = CP_ADDON_DIR . 'addons/analytics/lib/class-googleanalyticsapi.php';

			if ( file_exists( $analytics_lib ) ) {
				require_once $analytics_lib;
			}

			if ( class_exists( 'GoogleAnalyticsAPI' ) ) {

				add_action( 'wp_ajax_cp_get_ga_token_details', array( $this, 'cp_get_ga_token_details' ) );
				add_action( 'wp_ajax_cp_resync_ga_data', array( $this, 'resync_ga_data' ) );
				add_action( 'wp_ajax_cp_update_ga_access_code', array( $this, 'cp_update_ga_access_code' ) );
				add_action( 'wp_ajax_cp_get_ga_data', array( $this, 'cp_get_ga_data' ) );
				add_action( 'wp_ajax_cp_delete_ga_integration', array( $this, 'cp_delete_ga_integration' ) );

				add_action( 'wp_ajax_cp_save_ga_details', array( $this, 'cp_save_ga_details' ) );

				$this->ga_instance = new GoogleAnalyticsAPI();
				$this->refresh_access_token();
			}
		}

		/**
		 * Function Name: cp_save_ga_details.
		 * Function Description: Save google analytics credentials
		 */
		function cp_save_ga_details() {

			$profile  = isset( $_POST['profile'] ) ? esc_attr( $_POST['profile'] ) : '';
			$timezone = isset( $_POST['timezone'] ) ? esc_attr( $_POST['timezone'] ) : '';

			if ( '' == $profile ) {
				wp_send_json_error();
			}

			$credentials = get_option( '_cp_ga_credentials' );

			$credentials['profile']  = $profile;
			$credentials['timezone'] = $timezone;

			update_option( 'cp_ga_credentials', $credentials );

			update_option( 'cp_ga_analytics_updated_on', date( 'Y-m-d H:i:s' ) );

			$this->refresh_access_token();

			$analytics_data = $this->get_analytics_data();

			$this->cp_map_details( $analytics_data );

			wp_send_json_success();

		}

		/**
		 * Function Name: cp_get_ga_data.
		 * Function Description: cp get ga data.
		 */
		function cp_get_ga_data() {

			if ( ! current_user_can( 'access_cp_pro' ) ) {
				die( '-1' );
			}

			$style_slug = isset( $_POST['style_id'] ) ? esc_attr( $_POST['style_id'] ) : '';
			$filter     = isset( $_POST['filter'] ) ? esc_attr( $_POST['filter'] ) : '';

			if ( '' == $style_slug ) {
				wp_send_json_error();
			}

			$data = get_option( 'cp_ga_analytics_data' );

			$credentials = get_option( 'cp_ga_credentials' );
			$timezone    = isset( $credentials['timezone'] ) ? $credentials['timezone'] : '';

			if ( '' != $timezone ) {
				date_default_timezone_set( $timezone );
			}

			$analytics_data = array();

			$start_date_val = '';
			$today          = strtotime( date( 'Y-m-d' ) );

			switch ( $filter ) {

				case 'month':
					$start_date_val = date( 'Y-m-d', strtotime( date( 'Y-m-d' ) . ' -1 month' ) );
					break;

				case 'week':
					$start_date_val = date( 'Y-m-d', strtotime( date( 'Y-m-d' ) . ' -1 week' ) );
					break;

				case 'yesterday':
					$start_date_val = date( 'Y-m-d H:i:s', strtotime( date( 'Y-m-d H:i:s' ) . ' -1 day' ) );
					break;

				case 'today':
					$start_date_val = date( 'Y-m-d H:i:s' );
					break;

				default:
					$query_args = array(
						'post_type'      => CP_CUSTOM_POST_TYPE,
						'posts_per_page' => -1,
						'post_status'    => 'publish',
					);

					$popups = new WP_Query( $query_args );

					wp_reset_postdata();

					$start_date_val = min(
						array_map(
							function( $item ) {
									return $item->post_date;
							}, $popups->posts
						)
					);
					break;
			}

			$start_date   = strtotime( date( 'Y-m-d', strtotime( $start_date_val ) ) );
			$end_date     = $today;
			$current_date = $start_date;

			if ( 'today' != $filter && 'yesterday' != $filter ) {

				if ( isset( $data[ $style_slug ] ) ) {

					while ( $current_date <= $end_date ) {

						$defaults    = array();
						$defaults[0] = date( 'Y-m-d', $current_date );
						$defaults[1] = 0;
						$defaults[2] = 0;

						$analytics_data[] = $defaults;

						foreach ( $data[ $style_slug ] as $key => $value ) {

							if ( strtotime( $key ) == $current_date ) {

								$defaults[0] = $key;
								$defaults[1] = $value['impressions'];
								$defaults[2] = $value['conversions'];

								$analytics_data[] = $defaults;
							}
						}

						$current_date = ( $current_date + ( 86400 ) );
					}
				} else {

					$analytics_data = array(
						array(
							date( 'Y-m-d', $start_date ),
							0,
							0,
						),
						array(
							date( 'Y-m-d', $end_date ),
							0,
							0,
						),
					);
				}
			} else {

				$curr_date = strtotime( date( 'Y-m-d', strtotime( $start_date_val ) ) );
				if ( isset( $data[ $style_slug ] ) ) {

					$defaults    = array();
					$defaults[0] = $start_date_val;
					$defaults[1] = 0;
					$defaults[2] = 0;

					foreach ( $data[ $style_slug ] as $key => $value ) {

						if ( strtotime( $key ) == $curr_date ) {

							$defaults[0] = $start_date_val;
							$defaults[1] = $value['impressions'];
							$defaults[2] = $value['conversions'];

							$analytics_data[] = $defaults;
						}
					}
				} else {
					$analytics_data = array(
						array( $curr_date, 0, 0 ),
					);
				}
				$edate = date( 'Y-m-d H:i:s', strtotime( date( 'Y-m-d', $curr_date ) ) );

				array_push( $analytics_data, array( $edate, 0, 0 ) );
			}

			$json_table = json_encode( $analytics_data );
			echo $json_table;
			die();

		}

		/**
		 * Function Name: cp_delete_ga_integration.
		 * Function Description: cp delete ga integration.
		 */
		function cp_delete_ga_integration() {

			if ( ! current_user_can( 'access_cp_pro' ) ) {
				die( '-1' );
			}

			delete_option( 'cp_ga_credentials' );
			delete_option( 'cp_ga_analytics_data' );
			delete_option( 'cp_ga_analytics_updated_on' );

			wp_send_json_success();
		}

		/**
		 * Function Name: cp_update_ga_access_code.
		 * Function Description: cp update ga access code.
		 */
		function cp_update_ga_access_code() {

			if ( ! current_user_can( 'access_cp_pro' ) ) {
				die( '-1' );
			}

			check_admin_referer( 'cp-auth-ga-access-action', 'cp_security_nonce' );

			$access_code = isset( $_POST['access_code'] ) ? sanitize_text_field( $_POST['access_code'] ) : '';

			if ( '' !== $access_code ) {
				update_option( 'cp_ga_access_code', $access_code );
				wp_send_json_success();
			} else {
				wp_send_json_error();
			}
		}

		/**
		 * Function Name: refresh_access_token.
		 * Function Description: refresh access token.
		 */
		public function refresh_access_token() {

			$credentials = get_option( 'cp_ga_credentials' );

			if ( is_array( $credentials ) && ! empty( $credentials ) && null !== $credentials['access_token'] ) {

				// Check if the accessToken is expired.
				if ( ( time() - $credentials['token_created'] ) >= $credentials['token_expires'] ) {
					$this->update_access_token( $credentials );
				}
			} else {

				if ( is_array( $credentials ) && isset( $credentials['refresh_token'] ) ) {
					$this->update_access_token( $credentials );
				}
			}

			$new_credentials    = get_option( 'cp_ga_credentials' );
			$this->access_token = $new_credentials['access_token'];

		}

		/**
		 * Update access token in option
		 *
		 * @param array $credentials All related credentail details.
		 * @since 1.0.0
		 */
		public function update_access_token( $credentials ) {

			$this->ga_instance->auth->set_client_id( $this->client_id ); // From the APIs console.
			$this->ga_instance->auth->setClientSecret( $this->client_secret ); // From the APIs console.
			$this->ga_instance->auth->setRedirectUri( $this->redirect_uri );

			$auth = $this->ga_instance->auth->refreshAccessToken( $credentials['refresh_token'] );

			$new_credentials = array(
				'access_token'  => $auth['access_token'],
				'refresh_token' => $credentials['refresh_token'],
				'token_expires' => $auth['expires_in'],
				'token_created' => time(),
			);

			if ( isset( $credentials['profile'] ) ) {
				$new_credentials['profile'] = $credentials['profile'];
			}

			update_option( 'cp_ga_credentials', $new_credentials );

		}

		/**
		 * Get google analytics accounts
		 *
		 * @since 1.0.0
		 */
		public function get_ga_accounts() {

			$accounts = array();
			if ( '' != $this->access_token ) {

				$this->ga_instance->set_access_token( $this->access_token );

				// Load profiles.
				$profiles = $this->ga_instance->get_profiles();

				if ( isset( $profiles['username'] ) ) {
					update_option( '_cpro_ga_profile', $profiles['username'] );
				}
				$curr_domain = isset( $_SERVER['HTTP_HOST'] ) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'];

				if ( is_array( $profiles ) && ! empty( $profiles ) && isset( $profiles['items'] ) ) {
					foreach ( $profiles['items'] as $item ) {

						if ( stripos( untrailingslashit( $item['websiteUrl'] ), $curr_domain ) !== false ) {

							$id          = "ga:{$item['id']}";
							$name        = $item['name'];
							$web_prop_id = $item['webPropertyId'];
							$timezone    = $item['timezone'];

							$account_info = array(
								'name'        => $name,
								'web_prop_id' => $web_prop_id,
								'timezone'    => $timezone,
							);

							$accounts[ $id ] = $account_info;
						}
					}
				}
			}

			return $accounts;
		}

		/**
		 * Function Name: get_analytics_data.
		 * Function Description: Get google analytics data.
		 *
		 * @param string $style_id string parameter.
		 */
		public function get_analytics_data( $style_id = '' ) {

			$visits      = array();
			$credentials = get_option( 'cp_ga_credentials' );

			$account_id = isset( $credentials['profile'] ) ? $credentials['profile'] : '';

			if ( '' == $account_id ) {
				$accounts   = $this->get_ga_accounts();
				$account_id = array_keys( $accounts );
				$account_id = $account_id[0];
			}

			$this->ga_instance->auth->set_client_id( $this->client_id ); // From the APIs console.
			$this->ga_instance->auth->setClientSecret( $this->client_secret ); // From the APIs console.
			$this->ga_instance->auth->setRedirectUri( $this->redirect_uri );
			$this->ga_instance->set_account_id( $account_id );
			$this->ga_instance->set_access_token( $credentials['access_token'] );

			$query_args = array(
				'post_type'      => CP_CUSTOM_POST_TYPE,
				'posts_per_page' => -1,
				'post_status'    => 'publish',
			);

			$popups = new WP_Query( $query_args );
			wp_reset_postdata();

			if ( $popups->post_count > 0 ) {

				if ( '' == $style_id ) {

					$popup_slugs = array();

					foreach ( $popups->posts as $popup ) {

						$style_slug    = $popup->post_name;
						$popup_slugs[] = 'ga:eventLabel==' . $style_slug;
					}

					$filter = 'ga:eventCategory==' . CPRO_BRANDING_NAME . ';' . implode( ',', $popup_slugs );

					$params = array(
						'metrics'    => 'ga:uniqueEvents',
						'dimensions' => 'ga:date,ga:eventAction,ga:eventLabel',
					);

				} else {

					$style_data = get_post( $style_id );
					$style_slug = $style_data->post_name;
					$filter     = 'ga:eventLabel==' . $style_slug . ';ga:eventCategory==' . CPRO_BRANDING_NAME;

					$params = array(
						'metrics'    => 'ga:totalEvents',
						'dimensions' => 'ga:date',
					);
				}

				$min_date = min(
					array_map(
						function( $item ) {
								return $item->post_date;
						}, $popups->posts
					)
				);

				// Set the default params. For example the start/end dates and max-results.
				$defaults = array(
					'start-date'  => date( 'Y-m-d', strtotime( $min_date ) ),
					'end-date'    => date( 'Y-m-d' ),
					'filters'     => $filter,
					'max-results' => 10000,
				);

				$this->ga_instance->set_default_query_params( $defaults );

				$result = $this->ga_instance->query( $params );

				if ( isset( $result['rows'] ) ) {
					$visits = $result['rows'];
				}
			}

			return $visits;

		}

		/**
		 * Generate Outh2 URL
		 *
		 * @since 1.0.0
		 */
		public function generate_auth_url() {

			$this->ga_instance->auth->set_client_id( $this->client_id ); // From the APIs console.
			$this->ga_instance->auth->setClientSecret( $this->client_secret ); // From the APIs console.
			$this->ga_instance->auth->setRedirectUri( $this->redirect_uri ); // Url to your app, must match one in the APIs console.

			// Get the Auth-Url.
			$url = $this->ga_instance->auth->buildAuthUrl();

			return $url;

		}

		/**
		 * Function Name: cp_map_details.
		 * Function Description: cp map details.
		 *
		 * @param string $analytics_data string parameter.
		 */
		public function cp_map_details( $analytics_data ) {
			$ga_data = array();

			if ( is_array( $analytics_data ) && ! empty( $analytics_data ) ) {
				foreach ( $analytics_data as $key => $value ) {

					$date       = date( 'Y-m-d', strtotime( $value[0] ) );
					$action     = $value[1];
					$style_slug = $value[2];
					$impression = 0;
					$conversion = 0;

					if ( 'impression' == $action ) {
						$impression = (int) $value[3];
					} else {
						$conversion = (int) $value[3];
					}

					if ( isset( $ga_data[ $style_slug ] ) ) {

						if ( isset( $ga_data[ $style_slug ][ $date ] ) ) {

							$exist_impressions = isset( $ga_data[ $style_slug ][ $date ]['impressions'] ) ? $ga_data[ $style_slug ][ $date ]['impressions'] : 0;
							$exist_conversions = isset( $ga_data[ $style_slug ][ $date ]['conversions'] ) ? $ga_data[ $style_slug ][ $date ]['conversions'] : 0;

												$impressions = $impression + $exist_impressions;
							$conversions                     = $conversion + $exist_conversions;

												$data = array(
													'impressions' => $impressions,
													'conversions' => $conversions,
												);

							$ga_data[ $style_slug ][ $date ] = $data;

						} else {

							$data = array(
								'impressions' => $impression,
								'conversions' => $conversion,
							);

							$ga_data[ $style_slug ][ $date ] = $data;
						}
					} else {

						$ga_data[ $style_slug ] = array();
						$data                   = array(
							'impressions' => $impression,
							'conversions' => $conversion,
						);

						$ga_data[ $style_slug ][ $date ] = $data;
					}
				}
			}

			update_option( 'cp_ga_analytics_data', $ga_data );
		}

		/**
		 * Function Name: cp_get_ga_token_details.
		 * Function Description: cp get ga token details.
		 */
		function cp_get_ga_token_details() {

			if ( ! current_user_can( 'access_cp_pro' ) ) {
				die( '-1' );
			}

			check_admin_referer( 'cp-auth-ga-access-action', 'cp_security_nonce' );

			$this->ga_instance->auth->set_client_id( $this->client_id ); // From the APIs console.
			$this->ga_instance->auth->setClientSecret( $this->client_secret ); // From the APIs console.
			$this->ga_instance->auth->setRedirectUri( $this->redirect_uri );

			$code = isset( $_POST['access_code'] ) ? sanitize_text_field( $_POST['access_code'] ) : '';

			$auth = $this->ga_instance->auth->get_access_token( $code );

			$auth_url = $this->generate_auth_url();

			// Try to get the AccessToken.
			if ( 200 == $auth['http_code'] ) {

				$credentials = array(
					'access_token'  => $auth['access_token'],
					'refresh_token' => $auth['refresh_token'],
					'token_expires' => $auth['expires_in'],
					'token_created' => time(),
				);

				$this->access_token = $credentials['access_token'];

				// save credentials in temporary option.
				update_option( '_cp_ga_credentials', $credentials );

				// Retrieve accounts for this particular site.
				$accounts = $this->get_ga_accounts();

				if ( is_array( $accounts ) && ! empty( $accounts ) ) {

					echo json_encode(
						array(
							'success'  => true,
							'accounts' => $accounts,
						)
					);
					die();

				} else {
					delete_option( 'cp_ga_analytics_updated_on' );
					delete_option( 'cp_ga_credentials' );
					delete_option( 'cp_ga_analytics_data' );
					delete_option( '_cpro_ga_profile' );
					$this->refresh_access_token();
					echo json_encode(
						array(
							'success' => false,
							'msg'     => __( 'Please create a Google Analytics Property for this Domain. <a class="google-analytic-page-link" href="https://support.google.com/analytics/answer/1042508" target="_blank" rel="noopener">Know more.</a>', 'convertpro-addon' ),
						)
					);
					die();
				}
			} elseif ( 400 == $auth['http_code'] && isset( $auth['error_description'] ) ) {
				// error.
				if ( strpos( $auth['error_description'], 'redeemed' ) !== false ) {
					echo json_encode(
						array(
							'success' => false,
							/* translators: %s auth URL */
							'msg'     => sprintf( __( 'This access code was already redeemed. Please try generating a new code from <a href="%s" target="_blank" rel="noopener">here.</a>', 'convertpro-addon' ), esc_url( $auth_url ) ),
						)
					);
				} else {
					echo json_encode(
						array(
							'success' => false,
							'msg'     => $auth['error_description'],
						)
					);
				}
							die();
			} else {
				// error.
				echo json_encode(
					array(
						'success' => false,
						/* translators: %s auth URL */
						'msg'     => sprintf( __( 'The access code you entered is incorrect! Please <a class="google-analytic-page-link" target="_blank" rel="noopener" href="%s">click here</a> to get an access code.', 'convertpro-addon' ), esc_url( $auth_url ) ),
					)
				);
				die();
			}
		}

		/**
		 * Resync Google analytics data
		 *
		 * @since 1.0.0
		 */
		public function resync_ga_data() {

			if ( ! current_user_can( 'access_cp_pro' ) ) {
				wp_send_json_error();
			}

			$analytics_data = $this->get_analytics_data();
			$ga_data        = array( $analytics_data );
			$this->cp_map_details( $analytics_data );
			update_option( 'cp_ga_analytics_updated_on', date( 'Y-m-d H:i:s' ) );
			wp_send_json_success();

		}

		/**
		 * Resync Google analytics data
		 *
		 * @since 1.0.0
		 */
		public function resync_ga_data_cron() {

			if ( ! current_user_can( 'access_cp_pro' ) ) {
				return false;
			}

			$analytics_data = $this->get_analytics_data();
			$ga_data        = array( $analytics_data );
			$this->cp_map_details( $analytics_data );
			update_option( 'cp_ga_analytics_updated_on', date( 'Y-m-d H:i:s' ) );
			return true;

		}
	}

	new CP_V2_GA();
}
