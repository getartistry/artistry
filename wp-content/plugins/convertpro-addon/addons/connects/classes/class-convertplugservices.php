<?php
/**
 * Convert Pro Services class
 *
 * @package Convert Pro Addon
 * @author Brainstorm Force
 */

/**
 * Helper class for connecting to third party services.
 *
 * @since 1.0.0
 */
final class ConvertPlugServices {

	/**
	 * Data for working with each supported third party service.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var array $services_data
	 */
	static private $services_data = array(
		'activecampaign'   => array(
			'type'  => 'autoresponder',
			'name'  => 'ActiveCampaign',
			'class' => 'CPRO_Service_ActiveCampaign',
			'url'   => 'http://www.activecampaign.com/help/using-the-api/',
		),
		'aweber'           => array(
			'type'  => 'autoresponder',
			'name'  => 'AWeber',
			'class' => 'CPRO_Service_AWeber',
			'url'   => 'https://help.aweber.com/hc/en-us/articles/204031226-How-Do-I-Authorize-an-App',
		),
		'benchmark-email'  => array(

			'type'  => 'autoresponder',
			'name'  => 'Benchmark Email',
			'class' => 'CPRO_Service_Benchmark_Email',
			'url'   => 'https://ui.benchmarkemail.com/in/help-FAQ/answer/How-do-I-access-the-Benchmark-Email-APIs',
		),
		'campaign-monitor' => array(
			'type'  => 'autoresponder',
			'name'  => 'Campaign Monitor',
			'class' => 'CPRO_Service_Campaign_Monitor',
			'url'   => 'https://www.campaignmonitor.com/api/getting-started/?&_ga=1.18810747.338212664.1439118258#clientid',
		),
		'campayn'          => array(
			'type'  => 'autoresponder',
			'name'  => 'Campayn',
			'class' => 'CPRO_Service_Campayn',
			'url'   => 'https://cloudup.com/csBXrG541nZ',
		),
		'clever-reach'     => array(
			'type'  => 'autoresponder',
			'name'  => 'CleverReach',
			'class' => 'CPRO_Service_Clever_Reach',
			'url'   => 'http://support.cleverreach.de/hc/en-us/articles/202373121-Locating-API-keys-list-IDs-and-form-IDs',
		),
		'constant-contact' => array(
			'type'  => 'autoresponder',
			'name'  => 'Constant Contact',
			'class' => 'CPRO_Service_Constant_Contact',
			'url'   => 'https://developer.constantcontact.com/api-keys.html',
		),
		'convertkit'       => array(
			'type'  => 'autoresponder',
			'name'  => 'ConvertKit',
			'class' => 'CPRO_Service_ConvertKit',
			'url'   => 'https://cloudup.com/cAZGvEtMLlR',
		),
		'customerio'       => array(
			'type'  => 'autoresponder',
			'name'  => 'Customer.io',
			'class' => 'CPRO_Service_Customerio',
			'url'   => 'https://fly.customer.io/account/customerio_integration',
		),
		'drip'             => array(
			'type'  => 'autoresponder',
			'name'  => 'Drip',
			'class' => 'CPRO_Service_Drip',
			'url'   => 'https://www.getdrip.com/user/edit',
		),
		'getresponse'      => array(
			'type'  => 'autoresponder',
			'name'  => 'GetResponse',
			'class' => 'CPRO_Service_GetResponse',
			'url'   => 'https://apidocs.getresponse.com/en/article/api-key',
		),
		'hubspot'          => array(
			'type'  => 'autoresponder',
			'name'  => 'HubSpot',
			'class' => 'CPRO_Service_Hubspot',
			'url'   => 'http://help.hubspot.com/articles/KCS_Article/Integrations/How-do-I-get-my-HubSpot-API-key',
		),
		'icontact'         => array(
			'type'  => 'autoresponder',
			'name'  => 'iContact',
			'class' => 'CPRO_Service_IContact',
			'url'   => 'http://www.icontact.com/developerportal/documentation/register-your-app/',
		),
		'infusionsoft'     => array(
			'type'  => 'autoresponder',
			'name'  => 'Infusionsoft',
			'class' => 'CPRO_Service_Infusionsoft',
			'url'   => 'http://help.infusionsoft.com/userguides/get-started/tips-and-tricks/api-key',
		),
		'klaviyo'          => array(
			'type'  => 'autoresponder',
			'name'  => 'Klaviyo',
			'class' => 'CPRO_Service_Klaviyo',
			'url'   => 'https://help.klaviyo.com/hc/en-us/articles/115005062267-Manage-Your-Account-s-API-Keys',
		),
		'madmimi'          => array(
			'type'  => 'autoresponder',
			'name'  => 'Mad Mimi',
			'class' => 'CPRO_Service_MadMimi',
			'url'   => 'http://help.madmimi.com/where-can-i-find-my-api-key/',
		),
		'mailchimp'        => array(
			'type'  => 'autoresponder',
			'name'  => 'MailChimp',
			'class' => 'CPRO_Service_MailChimp',
			'url'   => 'http://kb.mailchimp.com/accounts/management/about-api-keys',
		),
		'mailerlite'       => array(
			'type'  => 'autoresponder',
			'name'  => 'MailerLite',
			'class' => 'CPRO_Service_MailerLite',
			'url'   => 'https://createform.com/support/mailerlite-api',
		),
		'mailgun'          => array(
			'type'  => 'autoresponder',
			'name'  => 'Mailgun',
			'class' => 'CPRO_Service_Mailgun',
			'url'   => 'https://help.mailgun.com/hc/en-us/articles/203380100-Where-can-I-find-my-API-key-and-SMTP-credentials-',
		),
		'mailjet'          => array(
			'type'  => 'autoresponder',
			'name'  => 'MailJet',
			'class' => 'CPRO_Service_MailJet',
			'url'   => 'https://app.mailjet.com/account/api_keys',
		),
		'mailpoet'         => array(
			'type'  => 'autoresponder',
			'name'  => 'MailPoet',
			'class' => 'CPRO_Service_MailPoet',
			'url'   => '',
		),
		'mailwizz'         => array(
			'type'  => 'autoresponder',
			'name'  => 'MailWizz',
			'class' => 'CPRO_Service_MailWizz',
			'url'   => 'http://bsf.io/tgz89',
		),
		'mautic'           => array(
			'type'  => 'autoresponder',
			'name'  => 'Mautic',
			'class' => 'CPRO_Service_Mautic',
			'url'   => 'https://www.convertplug.com/pro/docs/get-mautic-api-credentials/',
		),
		'mymail'           => array(
			'type'  => 'autoresponder',
			'name'  => 'Mailster',
			'class' => 'CPRO_Service_MyMail',
			'url'   => '',
		),
		'ontraport'        => array(
			'type'  => 'autoresponder',
			'name'  => 'ONTRAPORT',
			'class' => 'CPRO_Service_Ontraport',
			'url'   => 'https://www.convertplug.com/pro/docs/ontraport-api-key/',
		),
		'sendinblue'       => array(
			'type'  => 'autoresponder',
			'name'  => 'SendinBlue',
			'class' => 'CPRO_Service_SendinBlue',
			'url'   => 'https://apidocs.sendinblue.com/faq/',
		),
		'sendlane'         => array(
			'type'  => 'autoresponder',
			'name'  => 'Sendlane',
			'class' => 'CPRO_Service_Sendlane',
			'url'   => 'http://help.sendlane.com/knowledgebase/api-key/',
		),
		'sendreach'        => array(
			'type'  => 'autoresponder',
			'name'  => 'SendReach',
			'class' => 'CPRO_Service_SendReach',
			'url'   => 'http://setup.sendreach.com/v3-migration/api-key-secret/',
		),
		'sendy'            => array(
			'type'  => 'autoresponder',
			'name'  => 'Sendy',
			'class' => 'CPRO_Service_Sendy',
			'url'   => 'https://sendy.co/demo/settings',
		),
		'simplycast'       => array(
			'type'  => 'autoresponder',
			'name'  => 'SimplyCast',
			'class' => 'CPRO_Service_SimplyCast',
			'url'   => 'https://www.convertplug.com/pro/docs/simplycast-api-key/',
		),
		'totalsend'        => array(
			'type'  => 'autoresponder',
			'name'  => 'TotalSend',
			'class' => 'CPRO_Service_TotalSend',
			'url'   => 'https://app.totalsend.com/app/user/integration/wordpress/',
		),
		'verticalresponse' => array(
			'type'  => 'autoresponder',
			'name'  => 'VerticalResponse',
			'class' => 'CPRO_Service_VerticalResponse',
			'url'   => 'http://developers.verticalresponse.com/apps/mykeys',
		),
		'convertfox'       => array(
			'type'  => 'autoresponder',
			'name'  => 'ConvertFox',
			'class' => 'CPRO_Service_Convertfox',
			'url'   => 'https://docs.convertfox.com/article/43-where-can-i-find-my-app-id-or-project-id',
		),
	);

	/**
	 * Get an array of default custom fields for any mailer.
	 *
	 * @since 1.0.0
	 * @param string $service Slug of any mailer service.
	 * @return array An array of default custom fields.
	 */
	static public function get_mapping_fields( $service ) {

		$instance = self::get_service_instance( $service );
		return $instance->render_mapping();
	}

	/**
	 * Get an array of services data of a certain type such as "autoresponder".
	 * If no type is specified, all services will be returned.
	 *
	 * @since 1.0.0
	 * @param string $type The type of service data to return.
	 * @return array An array of services and related data.
	 */
	static public function get_services_data( $type = null ) {
		$services = array();

		// Return all services.
		if ( ! $type ) {
			$services = self::$services_data;
		} // Return services of a specific type.
		else {

			foreach ( self::$services_data as $key => $service ) {
				if ( $service['type'] == $type ) {
					$services[ $key ] = $service;
				}
			}
		}

		return $services;
	}

	/**
	 * Get an instance of a service helper class.
	 *
	 * @since 1.0.0
	 * @param string $service Service slug.
	 * @return object
	 */
	static public function get_service_instance( $service ) {
		$services = self::get_services_data();

		// Get static service name.
		$data = $services[ $service ];

		// Make sure the base class is loaded.
		if ( ! class_exists( 'CPRO_Service' ) ) {
			require_once 'class-cpro-service.php';
		}

		// Make sure the service class is loaded.
		if ( ! class_exists( $data['class'] ) ) {
			require_once CP_SERVICES_BASE_DIR . 'services/' . $service . '/class-cpro-service-' . $service . '.php';
		}

		return new $data['class']();
	}

	/**
	 * Get scripts
	 *
	 * @since 1.0.0
	 * @return object
	 */
	static public function get_assets_data() {
		$assets         = '';
		$post_data      = ConvertPlugHelper::get_post_data();
		$error_response = array(
			'error'  => true,
			'assets' => $assets,
		);

		if ( ! isset( $post_data['service'] ) ) {
			return $error_response;
		}

		$service     = $post_data['service'];
		$service_dir = CP_SERVICES_BASE_DIR . 'services/' . $service . '/';
		$service_url = CP_SERVICES_BASE_URL . 'services/' . $service . '/';

		if ( file_exists( $service_dir . $service . '.js' ) ) {
			$assets .= '<script class="cp-mailer-' . $service . '-js" src="' . $service_url . $service . '.js"></script>';
		}

		if ( file_exists( $service_dir . $service . '.css' ) ) {
			$assets .= '<link class="cp-mailer-' . $service . '-css" rel="stylesheet" href="' . $service_url . $service . '.css"></link>';
		}

		if ( '' != $assets ) {

			// Return assets.
			return array(
				'error'  => false,
				'assets' => $assets,
			);
		}

		return $error_response;
	}

	/**
	 * Save the API connection of a service and retrieve account settings markup.
	 *
	 * Called via the cppro_connect_service frontend AJAX action.
	 *
	 * @since 1.0.0
	 * @return array The response array.
	 */
	static public function connect_service() {
		$saved_services = ConvertPlugHelper::get_saved_services();
		$post_data      = ConvertPlugHelper::get_post_data();
		$response       = array(
			'error' => false,
			'html'  => '',
		);

		// Validate the service data.
		if ( ! isset( $post_data['service'] ) || empty( $post_data['service'] ) ) {
			$response['error'] = _x( 'Error: Missing service type.', 'Third party service such as MailChimp.', 'convertpro-addon' );
		} elseif ( ! isset( $post_data['fields'] ) || 0 === count( $post_data['fields'] ) ) {
			$response['error'] = _x( 'Error: Missing service data.', 'Connection data such as an API key.', 'convertpro-addon' );
		} elseif ( ! isset( $post_data['fields']['service_account'] ) || empty( $post_data['fields']['service_account'] ) ) {
			$response['error'] = _x( 'Error: Please enter a valid integration name.', 'Integration name for a third party service such as MailChimp.', 'convertpro-addon' );
		}

		// Get the service data.
		$service         = $post_data['service'];
		$service_account = $post_data['fields']['service_account'];

		// Does this account already exist?
		if ( in_array( $service_account, $saved_services ) ) {
			$response['error'] = _x( 'Hey, looks like you already have an account with the same name. Please use another Integration Name.', 'Integration name for a third party service such as MailChimp.', 'convertpro-addon' );
		}

		// Try to connect to the service.
		if ( ! $response['error'] ) {

			$instance = self::get_service_instance( $service );

			$connection       = $instance->connect( $post_data['fields'] );
			$response['data'] = $connection['data'];
			if ( $connection['error'] ) {
				$response['error'] = $connection['error'];
			}
		}

		// Return the response.
		return $response;
	}

	/**
	 * Save the connection settings or account settings for a service.
	 *
	 * Called via the cppro_save_service_settings frontend AJAX action.
	 *
	 * @since 1.0.0
	 * @return array The response array.
	 */
	static public function save_settings() {
		$post_data    = ConvertPlugHelper::get_post_data();
		$service_data = $post_data['serviceData'];
		$account      = $service_data['service_account'];
		$service      = $post_data['service'];

		$response = array(
			'error' => false,
			'html'  => '',
		);

		if ( '' != $account && '' != $service ) {

			$term = wp_insert_term( $account, CP_CONNECTION_TAXONOMY );

			if ( ! is_wp_error( $term ) ) {

				$newterm = update_term_meta( $term['term_id'], CP_API_CONNECTION_SERVICE, $service );

				$instance = self::get_service_instance( $service );

				$auth_meta = $instance->render_auth_meta( $service_data );

				update_term_meta( $term['term_id'], CP_API_CONNECTION_SERVICE_AUTH, $auth_meta );
				$t                   = get_term( $term['term_id'], CP_CONNECTION_TAXONOMY );
				$response['term_id'] = $t->slug;

			} else {
				$response = array(
					'error'   => $term->get_error_message(),
					'html'    => '',
					'term_id' => -1,
				);
			}
		} else {
			$response = array(
				'error'   => __( 'Integration Name should not be blank.', 'convertpro-addon' ),
				'html'    => '',
				'term_id' => -1,
			);
		}

		// Return the response.
		return $response;
	}

	/**
	 * Render the connection settings or account settings for a service.
	 *
	 * Called via the render_service_settings frontend AJAX action.
	 *
	 * @since 1.0.0
	 * @return array The response array.
	 */
	static public function render_settings() {
		$post_data = ConvertPlugHelper::get_post_data();

		$service  = $post_data['service'];
		$response = array(
			'error' => false,
			'html'  => '',
		);

		// Render the settings to connect a new account.
		$response['html']  = '<div class="cp-api-fields cp-new_account-wrap">';
		$response['html'] .= '<input type="text" name="service_account" id="cp_new_account_name" />';
		$response['html'] .= '<label for="cp_new_account_name">' . __( 'Provide a name for this integration', 'convertpro-addon' ) . '</label>';
		$response['html'] .= '</div>';
		$response['html'] .= self::render_connect_settings( $service );
		// Return the response.
		return $response;
	}

	/**
	 * Render the connection settings or account settings for a service.
	 *
	 * Called via the render_service_accounts frontend AJAX action.
	 *
	 * @since 1.0.0
	 * @return array The response array.
	 */
	static public function render_service_accounts() {

		$terms = get_terms(
			CP_CONNECTION_TAXONOMY, array(
				'hide_empty' => false,
			)
		);

		$return_array = array();
		$post_data    = ConvertPlugHelper::get_post_data();

		$url = ( isset( $post_data['service'] ) ) ? ConvertPlugServices::$services_data[ $post_data['service'] ]['url'] : '';

		$response = array(
			'error'         => false,
			'html'          => '',
			'account_count' => 0,
			'url'           => $url,
		);
		if ( ! empty( $terms ) ) {
			foreach ( $terms as $key => $term ) {
				if ( isset( $term->term_id ) ) {
					if ( get_term_meta( $term->term_id, CP_API_CONNECTION_SERVICE, true ) == $post_data['service'] ) {
						$return_array[ $term->slug ] = $term->name;

						$args                             = array(
							'tax_query' => array(
								array(
									'taxonomy' => CP_CONNECTION_TAXONOMY,
									'field'    => 'slug',
									'terms'    => $term->slug,
								),
							),
							'post_type' => CP_CUSTOM_POST_TYPE,
						);
						$query                            = new WP_Query( $args );
						$associative_array[ $term->slug ] = ( isset( $query->post_count ) ) ? $query->post_count : 0;
					}
				} else {
					$response['error'] = __( 'You have not added an account yet. Please add a new account.', 'convertpro-addon' );
				}
			}

			if ( ! empty( $return_array ) ) {
				ob_start();

				ConvertPlugHelper::render_input_html(
					'service_accounts', array(
						'class'       => '',
						'type'        => 'radio',
						'label'       => __( 'Select Integration', 'convertpro-addon' ),
						'help'        => '',
						'default'     => ( isset( $post_data['selected'] ) ) ? $post_data['selected'] : '',
						'options'     => $return_array,
						'association' => $associative_array,
					)
				);

				$response['html']          = ob_get_clean();
				$response['account_count'] = count( $associative_array );
			} else {
				$response['error'] = __( 'You have not added a account yet. Please add a new account.', 'convertpro-addon' );
			}
		} else {
			$response['error'] = true;
			$response['html']  = __( 'You have not added a account yet. Please add a new account.', 'convertpro-addon' );
		}

		return $response;
	}

	/**
	 * Render the settings to connect to a new account.
	 *
	 * @since 1.0.0
	 * @param string $service service slug.
	 * @return string The settings markup.
	 */
	static public function render_connect_settings( $service ) {
		ob_start();

		$instance = self::get_service_instance( $service );

		echo $instance->render_connect_settings();

		return ob_get_clean();
	}

	/**
	 * Render the account settings for a saved connection.
	 *
	 * @since 1.0.0
	 * @param string $service The service id such as "mailchimp".
	 * @param string $active The name of the active account, if any.
	 * @return string The account settings markup.
	 */
	static public function render_account_settings( $service, $active = '' ) {
		ob_start();

		$saved_services            = ConvertPlugHelper::get_services();
		$settings                  = new stdClass();
		$settings->service_account = $active;
		$options                   = array(
			'-1' => __( 'Choose...', 'convertpro-addon' ),
		);

		// Build the account select options.
		foreach ( $saved_services[ $service ] as $account => $data ) {
			$options[ $account ] = $account;
		}

		$options['add_new_account'] = __( 'Add Integration...', 'convertpro-addon' );

		// Render the account select.
		ConvertPlugHelper::render_settings_field(
			'service_account', array(
				'row_class' => 'convertpro-connects-service-account-row',
				'class'     => 'convertpro-connects-service-account-select',
				'type'      => 'select',
				'label'     => __( 'Existing Integration', 'convertpro-addon' ),
				'options'   => $options,
				'preview'   => array(
					'type' => 'none',
				),
			), $settings
		);

		// Render additional service fields if we have a saved account.
		if ( ! empty( $active ) && isset( $saved_services[ $service ][ $active ] ) ) {

			$post_data = ConvertPlugHelper::get_post_data();
			$module    = ConvertPlugHelper::get_module( $post_data['node_id'] );
			$instance  = self::get_service_instance( $service );
			$response  = $instance->render_fields( $active, $module->settings );

			if ( ! $response['error'] ) {
				echo $response['html'];
			}
		}

		return ob_get_clean();
	}

	/**
	 * Render the markup for service specific fields.
	 *
	 * Called via the render_service_fields frontend AJAX action.
	 *
	 * @since 1.0.0
	 * @return array The response array.
	 */
	static public function render_fields() {

		$post_data = ConvertPlugHelper::get_post_data();

		if ( isset( $post_data['isEdit'] ) && '' != $post_data['isEdit'] ) {

			if ( 'true' == $post_data['noMapping'] ) {
				$src                 = $post_data['src'];
				$opt                 = get_option( '_cp_v2_' . $src . '_form' );
				$cp_connect_settings = ( isset( $opt['cp_connection_values'] ) && '' != $opt['cp_connection_values'] ) ? ConvertPlugHelper::get_decoded_array( stripslashes( $opt['cp_connection_values'] ) ) : array();
			} else {
				$post_id = ( isset( $post_data['style_id'] ) ) ? $post_data['style_id'] : 0;

				$meta = get_post_meta( $post_id, 'connect' );

				$meta = ( ! empty( $meta ) ) ? call_user_func_array( 'array_merge', call_user_func_array( 'array_merge', $meta ) ) : array();

				if ( ! empty( $meta ) ) {

					$cp_connect_settings = ( isset( $meta['cp_connect_settings'] ) && -1 != $meta['cp_connect_settings'] ) ? ConvertPlugHelper::get_decoded_array( $meta['cp_connect_settings'] ) : array();
				}
			}
			if ( ! empty( $cp_connect_settings ) ) {
				$service = $cp_connect_settings['cp-integration-service'];

				$account_name = $cp_connect_settings['cp-integration-account-slug'];

				if ( $account_name == $post_data['account'] ) {

					unset( $cp_connect_settings['cp-integration-service'] );
					unset( $cp_connect_settings['cp-integration-account-slug'] );

					$post_data['default'] = $cp_connect_settings;
				}
			}
		}

		$account         = $post_data['account'];
		$response        = '';
		$connection_data = ConvertPlugHelper::get_connection_data( $account );

		if ( isset( $connection_data[ CP_API_CONNECTION_SERVICE ][0] ) ) {

			$instance = self::get_service_instance( $connection_data[ CP_API_CONNECTION_SERVICE ][0] );
			$response = $instance->render_fields( $connection_data, $post_data );
		} else {
			$account  = apply_filters( 'cp_static_account_service', $account );
			$instance = self::get_service_instance( $account );
			$response = $instance->render_fields( $account, $post_data );
		}
		return $response;
	}

	/**
	 * Delete a saved account from the database.
	 *
	 * Called via the delete_service_account frontend AJAX action.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	static public function delete_account() {
		$post_data = ConvertPlugHelper::get_post_data();

		if ( ! isset( $post_data['account'] ) ) {
			return;
		}
		$response = array(
			'error' => true,
		);
		$result   = ConvertPlugHelper::delete_service_account( $post_data['account'] );

		if ( ! is_wp_error( $result ) ) {
			$response['error'] = false;
		} else {
			$response['error'] = $result->get_error_message();
		}
		return $response;
	}

	/**
	 * Renders the authentication details for the service.
	 *
	 * @since 1.0.0
	 * @param array $account Integration details.
	 * @return array The connection settings markup.
	 */
	public static function get_account_data( $account ) {

		if ( isset( $account[ CP_API_CONNECTION_SERVICE_AUTH ][0] ) ) {
			return unserialize( $account[ CP_API_CONNECTION_SERVICE_AUTH ][0] );
		}
		return true;
	}

	/**
	 * Subscribe to specific lists and group
	 *
	 * Called via the cp_add_subscriber frontend AJAX action.
	 *
	 * @since 1.0.0
	 * @return array The response array.
	 */
	static public function add_subscriber() {

		check_ajax_referer( 'cp_add_subscriber_nonce', '_nonce' );

		$user                = wp_get_current_user();
		$can_user_see_errors = true;

		if ( in_array( 'author', (array) $user->roles ) || in_array( 'editor', (array) $user->roles ) || in_array( 'administrator', (array) $user->roles ) ) {
			$can_user_see_errors = true;
		} else {
			$can_user_see_errors = false;
		}

		$post_data = ConvertPlugHelper::get_post_data();

		$response = array(
			'error'      => false,
			'style_slug' => '',
		);

		$params_cnt    = count( $post_data['param'] );
		$cnt           = 0;
		$keys_with_arr = array();

		foreach ( $post_data['param'] as $key => $value ) {
			if ( false !== strpos( $key, 'checkboxfield_' ) ) {
				$tmp                                    = explode( '-', $key );
				$overrided_key                          = $tmp[0];
				$post_data['param'][ $overrided_key ][] = $value;
				unset( $post_data['param'][ $key ] );

				// Collect multiple values parameters in array.
				if ( ! in_array( $overrided_key, $keys_with_arr ) ) {
					$keys_with_arr[] = $overrided_key;
				}
			}

			// last iteration.
			if ( $cnt == $params_cnt - 1 ) {
				foreach ( $keys_with_arr as $key => $value ) {
					$existing_value = $post_data['param'][ $value ];

					// Comma separated values for checkbox field.
					$post_data['param'][ $value ] = implode( ',', $existing_value );
				}
			}

			$cnt++;
		}

		$settings     = $post_data;
		$meta_mapping = array();
		$email_status = true;

		$style_id = $post_data['style_id'];

		$meta = call_user_func_array( 'array_merge', call_user_func_array( 'array_merge', get_post_meta( $style_id, 'connect' ) ) );

		$post = get_post( $style_id );

		foreach ( $meta as $key => $m ) {
			$meta[ $key ] = json_decode( $m );
		}

		$mailer      = '';
		$mailer_name = '';

		if ( is_array( $meta['cp_connect_settings'] ) ) {
			foreach ( $meta['cp_connect_settings'] as $key => $t ) {
				if ( 'cp-integration-account-slug' == $t->name ) {
					$mailer_name = $t->value;
					$mailer      = ConvertPlugHelper::get_connection_data( $t->value );
				} else {

					if ( isset( $mailer_name ) && '' != $mailer_name && 'mailpoet' != $mailer_name && 'mymail' != $mailer_name ) {
						if ( 'infusionsoft' == $mailer[ CP_API_CONNECTION_SERVICE ][0] && 'infusionsoft_tags' == $t->name ) {
							$settings['infusionsoft_tags'][] = $t->value;
						} elseif ( 'ontraport' == $mailer[ CP_API_CONNECTION_SERVICE ][0] && 'ontraport_tags' == $t->name ) {
							$settings['ontraport_tags'][] = $t->value;
						} elseif ( 'mailchimp' == $mailer[ CP_API_CONNECTION_SERVICE ][0] && 'mailchimp_groups' == $t->name ) {
							$settings['mailchimp_groups'][] = $t->value;
						} elseif ( 'mautic' == $mailer[ CP_API_CONNECTION_SERVICE ][0] && 'mautic_segment' == $t->name ) {
							$settings['mautic_segment'][] = $t->value;
						} elseif ( 'sendlane' == $mailer[ CP_API_CONNECTION_SERVICE ][0] && 'sendlane_tags' == $t->name ) {
							$settings['sendlane_tags'][] = $t->value;
						} else {
							$settings[ $t->name ] = $t->value;
						}
					} else {
						$settings[ $t->name ] = $t->value;
					}
					if ( isset( $meta['cp_mapping'][ $key ] ) ) {
						$meta_mapping[ $meta['cp_mapping'][ $key ]->name ] = $t->value;
					}
				}
			}
		}

		$map = ( isset( $meta['map_placeholder'] ) ) ? ConvertPlugHelper::get_decoded_array( json_encode( $meta['map_placeholder'] ) ) : array();

		$style_name = get_the_title( $settings['style_id'] );
		if ( ! $mailer && ! ( 'mailpoet' == $mailer_name || 'mymail' == $mailer_name ) ) {
			if ( $can_user_see_errors ) {
				$response['error'] = __( 'You are not connected to any service.', 'convertpro-addon' );
			}
			wp_send_json_success( $response );
			return $response;
		}

		if ( is_array( $meta['cp_mapping'] ) ) {

			foreach ( $meta['cp_mapping'] as $key => $t ) {

				$meta['cp_mapping'][ $key ]->name = str_replace( '{', '', $t->name );
				$meta['cp_mapping'][ $key ]->name = str_replace( '}', '', $t->name );
				$meta['cp_mapping'][ $key ]->name = str_replace( 'input', '-input', $t->name );
				$meta['cp_mapping'][ $key ]->name = str_replace( 'cp_mapping', '', $t->name );

				$meta_mapping[ $meta['cp_mapping'][ $key ]->name ] = $t->value;
			}
		}

		$settings['meta']           = $meta_mapping;
		$settings['api_connection'] = $mailer;

		if ( isset( $mailer[ CP_API_CONNECTION_SERVICE ][0] ) ) {
			$instance = self::get_service_instance( $mailer[ CP_API_CONNECTION_SERVICE ][0] );
		} else {
			$instance = self::get_service_instance( $mailer_name );
		}

		$email = isset( $post_data['param']['email'] ) ? $post_data['param']['email'] : '';

		do_action( 'cp_before_subscribe', $email, $style_id );

		$response = $instance->subscribe( $settings, $email );

		if ( ! $can_user_see_errors ) {
			$response['error'] = false;
		}

		$response['style_slug'] = $post->post_name;

		self::send_notification( $response, $post, $style_id, $map, $can_user_see_errors, $settings );

		do_action( 'cp_after_subscribe', $email, $style_id );

		wp_send_json_success( $response );
		return $response;
	}

	/**
	 * Subscribe to specific lists and group
	 *
	 * Called via the in sync frontend addons
	 *
	 * @param array   $response AJAX response.
	 * @param array   $post Design object.
	 * @param int     $style_id Design ID.
	 * @param array   $map mapping settings.
	 * @param boolean $can_user_see_errors Flag to show errors.
	 * @param array   $settings Entire settings.
	 * @since 1.0.0
	 * @return void.
	 */
	static public function send_notification( $response, $post, $style_id, $map, $can_user_see_errors, $settings ) {

		if ( false !== $response['error'] ) {

			if ( ! $can_user_see_errors ) {
				$response['error'] = false;
			}
			$style_name = get_the_title( $settings['style_id'] );

			$admin_email = get_bloginfo( 'admin_email' );
			$style_link  = get_edit_post_link( $style_id );
			$style_link .= '#connect';

			$email_template     = get_option( 'cp_failure_email_template' );
			$email_template_sbj = get_option( 'cp_failure_email_subject' );

			if ( isset( $email_template_sbj ) && '' != $email_template_sbj ) {
				$subject = $email_template_sbj;
			} else {
				/* translators: %s Error Message */
				$subject = sprintf( __( 'Important Notification! - [SITE_NAME] - %s [MAILER_SERVICE_NAME] configuration error', 'convertpro-addon' ), CPRO_BRANDING_NAME );
			}

			if ( isset( $email_template ) && '' != $email_template ) {
				$template = $email_template;
			} else {
				$template = "The design <strong>[DESIGN_NAME]</strong> integrated with <strong>[MAILER_SERVICE_NAME]</strong> is not working! The following error occured when a user tried to subscribe - \n\n [ERROR_MESSAGE] \n\n Please check <a href='[DESIGN_LINK]' target='_blank' rel='noopener'>configuration</a> settings</a> ASAP.\n\n ----- \n\n The details of the subscriber are given below.\n\n [FORM_SUBMISSION_DATA] \n\n ----- \n\n [ [SITE_NAME] - [SITE_URL] ]";
			}

			$err_str = '<strong><pre style="font-size:14px">' . $response['error'] . '</pre></strong>';

			$template = str_replace( '[DESIGN_NAME]', $style_name, $template );
			$template = str_replace( '-----', '<p>-----</p>', $template );
			$template = str_replace( '[DESIGN_LINK]', $style_link, $template );
			$template = str_replace( '[SITE_URL]', site_url(), $template );
			$template = str_replace( '[SITE_NAME]', get_bloginfo( 'name' ), $template );
			$template = str_replace( '[ERROR_MESSAGE]', $err_str, $template );
			$template = str_replace( '[MAILER_SERVICE_NAME]', ucfirst( $settings['cp-integration-service'] ), $template );
			$subject  = str_replace( '[MAILER_SERVICE_NAME]', ucfirst( $settings['cp-integration-service'] ), $subject );
			$subject  = str_replace( '[SITE_NAME]', get_bloginfo( 'name' ), $subject );
			$template = stripslashes( $template );
			ConvertPlugServices::send_email( $admin_email, $subject, $template, $settings, $map );
		} else {
			$email_meta = get_post_meta( $style_id, 'connect', true );

			$email_meta = ( ! empty( $email_meta ) ) ? call_user_func_array( 'array_merge', $email_meta ) : array();

			if ( ! empty( $email_meta ) && '1' == $email_meta['enable_notification'] ) {
				cpro_notify_via_email( $settings, $email_meta );
			}
		}
	}

	/**
	 * Subscribe to specific lists and group
	 *
	 * Called via the in sync frontend addons
	 *
	 * @param array $connection Connection settings.
	 * @since 1.0.0
	 * @return array The response array.
	 */
	static public function subscribe( $connection ) {

		check_ajax_referer( 'cp_add_subscriber_nonce', '_nonce' );

		$user                = wp_get_current_user();
		$can_user_see_errors = true;

		if ( in_array( 'author', (array) $user->roles ) || in_array( 'editor', (array) $user->roles ) || in_array( 'administrator', (array) $user->roles ) ) {
			$can_user_see_errors = true;
		} else {
			$can_user_see_errors = false;
		}

		$post_data = ConvertPlugHelper::get_post_data();
		$response  = array(
			'error' => false,
		);
		$settings  = $post_data;
		$service   = '';

		$connection_data = isset( $connection['connection'] ) ? ConvertPlugHelper::get_decoded_array( stripslashes( $connection['connection'] ) ) : array();

		$service = $connection_data['cp-integration-service'];
		unset( $connection_data['cp-integration-source'] );

		if ( ! $service ) {
			if ( $can_user_see_errors ) {
				$response['error'] = __( 'You are not connected to any service.', 'convertpro-addon' );
			}
			return $response;
		}

		if ( is_array( $connection_data ) ) {
			foreach ( $connection_data as $key => $t ) {
				if ( 'cp-integration-account-slug' == $key ) {
					$account = ConvertPlugHelper::get_connection_data( $t );
				} else {

					if ( 'infusionsoft' == $service && 'infusionsoft_tags' == $key ) {
						$settings['infusionsoft_tags'][] = $t;
					} elseif ( 'ontraport' == $service && 'ontraport_tags' == $key ) {
						$settings['ontraport_tags'][] = $t;
					} elseif ( 'mautic' == $service && 'mautic_segment' == $key ) {
						$settings['mautic_segment'][] = $t;
					} elseif ( 'sendlane' == $service && 'sendlane_tags' == $key ) {
						$settings['sendlane_tags'][] = $t;
					} else {
						$settings[ $key ] = $t;
					}
				}
			}
		}

		$settings['meta']           = array();
		$settings['api_connection'] = $account;

		$email = isset( $connection['data']['email'] ) ? $connection['data']['email'] : '';

		$settings['param']['email'] = $email;

		do_action( 'cp_before_subscribe', $email, $service );

		$instance = self::get_service_instance( $service );
		$response = $instance->subscribe( $settings, $email );

		if ( ! $can_user_see_errors ) {
			$response['error'] = false;
		}

		do_action( 'cp_after_subscribe' );

		return $response;
	}


	/**
	 * Asynchronously saves meta related to the style id
	 *
	 * Called via the save_meta_setting AJAX action.
	 *
	 * @since 1.0.0
	 * @return array The response array.
	 */
	static public function save_meta() {

		$post_data = ConvertPlugHelper::get_post_data();
		$post_id   = ( isset( $post_data['style_id'] ) ) ? (int) $post_data['style_id'] : 0;

		$response = array(
			'error' => true,
		);

		if ( 0 != $post_id ) {

			$meta_value[0]['cp_connect_settings'] = $post_data['cp_taxonomy'];
			$meta_value[1]['cp_mapping']          = $post_data['cp_mapping'];

			$result = update_post_meta( $post_id, 'connect', $meta_value );

			if ( '-1' == $post_data['cp_taxonomy'] ) {
				wp_delete_object_term_relationships( $post_id, CP_CONNECTION_TAXONOMY );
			}

			if ( ! is_wp_error( $result ) ) {
				$response['error'] = false;
			} else {
				// Error.
				$response['error'] = $result->get_error_message();
			}
		} else {
			// Error.
			$response['error'] = __( 'Wrong Style ID. Please check with admin.', 'convertpro-addon' );
		}

		return $response;
	}


	/**
	 * Sends E-Mail to admin when something goes wrong in subscription
	 *
	 * Called via the add_subscriber function.
	 *
	 * @param string $email User email ID.
	 * @param string $subject Email Subject string.
	 * @param string $template Template string.
	 * @param array  $settings Settings array.
	 * @param array  $map Mapping array.
	 * @since 1.0.0
	 * @return void.
	 */
	static public function send_email( $email, $subject, $template, $settings, $map ) {

		$headers = array(
			'Reply-To: ' . get_bloginfo( 'name' ) . ' <' . $email . '>',
			'Content-Type: text/html; charset=UTF-8',
		);

		$param = '';
		if ( is_array( $settings['param'] ) && count( $settings['param'] ) ) {
			foreach ( $settings['param'] as $key => $value ) {
				$k      = isset( $map[ $key ] ) ? $map[ $key ] : $key;
				$param .= '<p>' . ucfirst( $k ) . ': ' . $value . '</p>';
			}
		}

		$template = str_replace( '[FORM_SUBMISSION_DATA]', $param, $template );

		wp_mail( $email, stripslashes( $subject ), stripslashes( $template ), $headers );
	}
}
