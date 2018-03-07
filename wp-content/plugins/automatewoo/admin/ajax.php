<?php

namespace AutomateWoo;

/**
 * @class Admin_Ajax
 */
class Admin_Ajax {

	/**
	 * Hook in methods
	 */
	static function init() {
		$ajax_events = [
			'fill_trigger_fields',
			'fill_action_fields',
			'json_search_workflows',
			'json_search_attribute_terms',
			'json_search_taxonomy_terms',
			'json_search_customers',
			'activate',
			'deactivate',
			'email_preview_ui',
			'email_preview_iframe',
			'test_sms',
			'database_update',
			'save_preview_data',
			'send_test_email',
			'dismiss_expiry_notice',
			'dismiss_system_error_notice',
			'get_rule_select_choices',
			'toggle_workflow_status',
			'modal_log_info',
			'modal_queue_info',
			'modal_variable_info',
			'modal_cart_info',
			'update_dynamic_action_select'
		];

		foreach ( $ajax_events as $ajax_event ) {
			add_action( 'wp_ajax_aw_' . $ajax_event, [ __CLASS__, $ajax_event ] );
		}
	}


	/**
	 *
	 */
	static function fill_trigger_fields() {

		if ( ! current_user_can( 'manage_woocommerce' ) )
			die;

		$trigger_name = Clean::string( aw_request('trigger_name') );
		$workflow_id = absint( aw_request('workflow_id') );
		$is_new_workflow = aw_request('is_new_workflow');

		$workflow = false;
		$trigger = Triggers::get( $trigger_name );

		if ( ! $trigger )
			die;

		if ( ! $is_new_workflow ) {
			$workflow = new Workflow( $workflow_id );
		}

		ob_start();

		Admin::get_view('trigger-fields', [
			'trigger' => $trigger,
			'workflow' => $workflow,
		]);

		$fields = ob_get_clean();

		wp_send_json_success([
			'fields' => $fields,
			'trigger' => Admin_Workflow_Edit::get_trigger_data( $trigger ),
		]);
	}


	/**
	 *
	 */
	static function fill_action_fields() {

		if ( ! current_user_can( 'manage_woocommerce' ) )
			die;

		$action_name = Clean::string( aw_request('action_name') );
		$action_number = Clean::string( aw_request('action_number') );

		$action = Actions::get( $action_name );

		ob_start();

		Admin::get_view( 'action-fields', [
			'action' => $action,
			'action_number' => $action_number,
		]);

		$fields = ob_get_clean();

		wp_send_json_success([
			'fields' => $fields,
			'title' => $action->get_title( true ),
			'description' => $action->get_description_html()
		]);
	}


	/**
	 * Search for products and echo json
	 */
	public static function json_search_workflows() {

		if ( ! current_user_can( 'manage_woocommerce' ) )
			die;

		ob_start();

		$term = Clean::string( stripslashes( $_GET['term'] ) );

		if ( empty( $term ) )
			die;

		$args = [
			'post_type' => 'aw_workflow',
			'post_status' => 'any',
			'posts_per_page' => -1,
			's' => $term,
			'fields' => 'ids',
			'suppress_filters' => true,
			'no_found_rows' => true
		];

		$query = new \WP_Query( $args );

		$found = [];

		if ( $query->posts ) {
			foreach ( $query->posts as $workflow_id ) {
				$workflow = new Workflow($workflow_id);
				$found[ $workflow_id ] = rawurldecode( $workflow->title );
			}
		}

		wp_send_json( $found );
	}


	/**
	 * Search customers, includes guests customers
	 */
	static function json_search_customers() {

		if ( ! current_user_can( 'manage_woocommerce' ) )
			die;

		ob_start();

		$term = Clean::string( stripslashes( $_GET['term'] ) );
		$customers = [];
		$limit = 100;

		if ( 3 > strlen( $term ) ) {
			$limit = 20;
		}

		if ( empty( $term ) ) {
			die;
		}

		$guest_query = new Guest_Query();
		$guest_query->where( 'email', "%$term%", 'LIKE' );
		$guest_query->where_meta( 'billing_first_name', "%$term%", 'LIKE' );
		$guest_query->where_meta( 'billing_last_name', "%$term%", 'LIKE' );
		$guest_query->combine_wheres_with_or = true;
		$guest_query->set_limit( $limit );

		foreach ( $guest_query->get_results() as $guest ) {
			if ( $customer = Customer_Factory::get_by_guest_id( $guest->get_id() ) ) {
				$customers[] = $customer;
			}
		}

		$query = new \WP_User_Query([
			'search'         => '*' . esc_attr( $term ) . '*',
			'search_columns' => [ 'user_login', 'user_email', 'user_nicename', 'display_name' ],
			'fields'         => 'ID',
			'number'         => $limit,
		]);

		$query2 = new \WP_User_Query([
			'fields'         => 'ID',
			'number'         => $limit,
			'meta_query'     => [
				'relation' => 'OR',
				[
					'key'     => 'first_name',
					'value'   => $term,
					'compare' => 'LIKE',
				],
				[
					'key'     => 'last_name',
					'value'   => $term,
					'compare' => 'LIKE',
				],
			],
		]);

		$user_ids = wp_parse_id_list( array_merge( $query->get_results(), $query2->get_results() ) );

		foreach ( $user_ids as $user_id ) {
			if ( $customer = Customer_Factory::get_by_user_id( $user_id ) ) {
				$customers[] = $customer;
			}
		}

		$formatted = [];

		foreach ( $customers as $customer ) {
			/** @var $customer Customer */
			$formatted[ $customer->get_id() ] = sprintf(
				esc_html__( '%s &ndash; %s', 'automatewoo' ),
				$customer->is_registered() ? $customer->get_full_name() : $customer->get_full_name() . ' ' . __( '[Guest]', 'automatewoo' ),
				$customer->get_email()
			);
		}

		wp_send_json( $formatted );
	}


	/**
	 * Search for products and echo json
	 */
	public static function json_search_attribute_terms() {

		if ( ! current_user_can( 'manage_woocommerce' ) )
			die;

		if ( empty( $_GET['term'] ) || empty( $_GET['sibling'] ) ) {
			die;
		}

		$search = Clean::string( stripslashes( $_GET['term'] ) );
		$sibling = Clean::string( stripslashes( $_GET['sibling'] ) );

		$terms = get_terms( 'pa_' . $sibling, [
			'hide_empty' => false,
			'search' => $search
		]);

		$found = [];

		if ( ! $terms || is_wp_error($terms)  )
			die();

		foreach ( $terms as $term ) {
			$found[ $term->term_id . '|' . $term->taxonomy  ] = rawurldecode( $term->name );
		}

		wp_send_json( $found );
	}



	/**
	 * Search for products and echo json
	 */
	public static function json_search_taxonomy_terms() {

		if ( ! current_user_can( 'manage_woocommerce' ) )
			die;

		ob_start();

		$search = Clean::string( stripslashes( $_GET['term'] ) );
		$sibling = Clean::string( stripslashes( $_GET['sibling'] ) );

		if ( empty( $search ) || empty($sibling) ) {
			die;
		}

		$terms = get_terms( $sibling, [
			'hide_empty' => false,
			'search' => $search
		]);


		$found = [];

		if ( ! $terms || is_wp_error($terms)  )
			die;

		foreach ( $terms as $term ) {
			$found[ $term->term_id . '|' . $term->taxonomy  ] = rawurldecode( $term->name );
		}

		wp_send_json( $found );
	}



	/**
	 * @param $workflow_id
	 * @param $action_number
	 * @param string $mode test|preview
	 *
	 * @return Action_Send_Email|Action_Send_Email_Raw|false
	 */
	static function _get_preview_action( $workflow_id, $action_number, $mode = 'preview' ) {

		$preview_data = get_option( 'aw_wf_preview_data_' . $workflow_id );

		if ( ! $workflow_id || ! $action_number || ! is_array( $preview_data )  )
			return false;

		// sanitize input
		foreach ( $preview_data as $i => $item ) {
			switch ( $i ) {
				case 'email_html':
				case 'email_content':
					$preview_data[$i] = stripslashes( $item ); // content is be sanitized later
					break;

				default:
					$preview_data[$i] = Clean::string( stripslashes( $item ) );
					break;
			}
		}

		// check action exists
		if ( ! Actions::get( $preview_data['action_name'] ) )
			return false;

		// create a fake action
		$action = clone Actions::get( $preview_data['action_name'] );

		// add the workflow in preview mode
		$workflow = AW()->get_workflow( $workflow_id );

		if ( $mode === 'test' ) {
			$workflow->enable_test_mode();
		}
		else {
			$workflow->enable_preview_mode();
		}

		$action->workflow = $workflow;

		// replace saved options with live preview data
		$action->set_options($preview_data);

		return $action;
	}


	/**
	 *
	 */
	static function email_preview_ui() {

		if ( ! current_user_can( 'manage_woocommerce' ) )
			die;

		$type = Clean::string( aw_request('type') );
		$args = Clean::recursive( aw_request('args') );

		$iframe_url = add_query_arg([
			'action' => 'aw_email_preview_iframe',
			'type' => $type,
			'args' => $args
		], admin_url( 'admin-ajax.php' ) );


		switch ( $type ) {
			case 'workflow_action':

				if ( ! $action = self::_get_preview_action( $args['workflow_id'], $args['action_number'] ) ) {
					wp_die( __( 'Error: Email preview data could not be found.', 'automatewoo' ) );
				}

				$email_subject = $action->get_option('subject', true );
				$template = $action->get_option( 'template' );
				break;

			default:
				$email_subject = '';
				$template = '';
		}

		$email_subject = apply_filters( 'automatewoo/email_preview/subject', $email_subject, $type, $args );
		$template = apply_filters( 'automatewoo/email_preview/template', $template, $type, $args );


		Admin::get_view('email-preview-ui', [
			'iframe_url' => $iframe_url,
			'type' => $type,
			'args' => $args,
			'email_subject' => $email_subject,
			'template' => $template
		]);

		die;
	}



	static function email_preview_iframe() {

		if ( ! current_user_can( 'manage_woocommerce' ) )
			die;

		$type = Clean::string( aw_request('type') );
		$args = Clean::recursive( aw_request('args') );

		switch ( $type ) {

			case 'workflow_action':
				if ( ! $action = self::_get_preview_action( $args['workflow_id'], $args['action_number'] ) )
					die();

				if ( ! $action || ! $action->can_be_previewed ) {
					wp_die( __( 'Sorry, this action can not be previewed.', 'automatewoo' ) );
				}

				$action->workflow->setup();

				echo $action->preview();

				$action->workflow->cleanup();

				break;

			default:
				do_action( 'automatewoo/email_preview/html', $type, $args );
		}

		exit();
	}


	/**
	 * Sends a test to supplied emails
	 */
	static function send_test_email() {

		if ( ! current_user_can( 'manage_woocommerce' ) )
			die;

		$type = Clean::string( aw_request('type') );
		$args = Clean::recursive( aw_request('args') );
		$to = Clean::string( aw_request('to_emails') );

		// save the to field
		update_user_meta( get_current_user_id(), 'automatewoo_email_preview_test_emails', $to );

		$to = Emails::parse_multi_email_field( $to );

		switch ( $type ) {

			case 'workflow_action':

				if ( ! $action = self::_get_preview_action( $args['workflow_id'], $args['action_number'], 'test' ) )
					die();

				if ( ! $action || ! $action->can_be_previewed ) {
					wp_die( __( 'Sorry, this action can not be previewed.', 'automatewoo' ) );
				}

				$action->workflow->setup();

				$result = $action->send_test( $to );

				$action->workflow->cleanup();

				break;

			default:
				do_action( 'automatewoo/email_preview/send_test', $type, $to, $args );
				$result = false;
		}

		if ( $result instanceof \WP_Error ) {
			wp_send_json_error([
				'message' => __( 'Error: ', 'automatewoo' ) . $result->get_error_message(),
			]);
		}

		wp_send_json_success([
			'message' => sprintf(
				__( 'Success! %s email%s sent.', 'automatewoo' ),
				count($to),
				count($to) == 1 ? '' : 's'
			)
		]);
	}



	static function test_sms() {

		if ( ! current_user_can( 'manage_woocommerce' ) )
			die;

		$from = Clean::string( aw_request('from') );
		$auth_id = Clean::string( aw_request('auth_id') );
		$auth_token = Clean::string( aw_request('auth_token') );
		$test_message = Clean::string( aw_request('test_message') );
		$test_recipient = Clean::string( aw_request('test_recipient') );

		Integrations::load_twilio();

		$client = new \Services_Twilio($auth_id, $auth_token);

		try {
			$client->account->messages->sendMessage( $from, $test_recipient, $test_message );
			wp_send_json_success( array(
				'message' => __('Message sent.','automatewoo')
			));
		}
		catch(\Exception $e) {
			wp_send_json_error( array(
				'message' => $e->getMessage()
			));

		}
	}



	/**
	 *
	 */
	static function database_update() {

		$verify = wp_verify_nonce( $_REQUEST['nonce'], 'automatewoo_database_upgrade' );
		$plugin_slug = Clean::string( aw_request('plugin_slug') );

		if ( ! $verify ) {
			wp_send_json_error( __( 'Permission error.', 'automatewoo' ) );
		}

		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			die;
		}

		if ( $plugin_slug == AW()->plugin_slug ) {
			// updating the primary plugin
			$complete = Installer::run_database_updates();

			wp_send_json_success([
				'complete' => $complete,
				'items_processed' => Installer::$db_update_items_processed
			]);
		}
		else {
			// updating an addon
			$addon = Addons::get( $plugin_slug );

			if ( ! $addon ) {
				wp_send_json_error(__( 'Add-on could not be updated', 'automatewoo' ) );
			}

			$addon->do_database_update();

			wp_send_json_success([
				'complete' => true
			]);
		}
	}


	/**
	 * To preview a workflow, save the data to a transient first
	 */
	static function save_preview_data() {

		if ( ! current_user_can( 'manage_woocommerce' ) )
			die;

		$workflow_id = absint( aw_request('workflow_id') );
		$preview_data = aw_request('preview_data');

		if ( ! is_array( $preview_data ) || ! $workflow_id )
			wp_send_json_error();

		update_option( 'aw_wf_preview_data_' . $workflow_id, $preview_data, HOUR_IN_SECONDS );

		wp_send_json_success();
	}


	/**
	 *
	 */
	static function dismiss_expiry_notice() {

		if ( ! current_user_can( 'manage_woocommerce' ) )
			die;

		set_transient( 'aw_dismiss_licence_expiry_notice', '1', 10 * MONTH_IN_SECONDS );
	}


	/**
	 *
	 */
	static function dismiss_system_error_notice() {

		if ( ! current_user_can( 'manage_woocommerce' ) )
			die;

		delete_transient('automatewoo_background_system_check_errors');
	}



	static function get_rule_select_choices() {

		if ( ! current_user_can( 'manage_woocommerce' ) )
			die;

		if ( ! $rule_name = Clean::string( aw_request('rule_name') ) )
			die;

		$rule_object = Rules::get( $rule_name );

		if ( $rule_object->type == 'select' ) {
			wp_send_json_success([
				'select_choices' => $rule_object->get_select_choices()
			]);
		}

		die;
	}


	/**
	 * Display content for log details modal
	 */
	static function modal_log_info() {

		if ( ! current_user_can( 'manage_woocommerce' ) )
			die;

		if ( $log = AW()->get_log( absint( aw_request('log_id') ) ) ) {
			Admin::get_view( 'modal-log-info', [ 'log' => $log ] );
			die;
		}

		die( __( 'No log found.', 'automatewoo' ) );
	}


	static function modal_queue_info() {

		if ( ! current_user_can( 'manage_woocommerce' ) )
			die;

		if ( $event = AW()->get_queued_event( absint( aw_request('queued_event_id') ) ) ) {
			Admin::get_view( 'modal-queued-event-info', [ 'event' => $event ] );
			die;
		}

		die( __( 'No queued event found.', 'automatewoo' ) );
	}


	static function modal_variable_info() {

		if ( ! current_user_can( 'manage_woocommerce' ) )
			die;

		$variable = Clean::string( aw_request( 'variable' ) );

		Admin::get_view( 'modal-variable-info', [
			'variable' => $variable,
			'variable_obj' => Variables::get_variable( $variable )
		]);

		die;
	}


	static function modal_cart_info() {

		if ( ! current_user_can( 'manage_woocommerce' ) )
			die;

		if ( $cart = AW()->get_cart( absint( aw_request('cart_id') ) ) ) {
			Admin::get_view( 'modal-cart-info', [ 'cart' => $cart ] );
			die;
		}

		die( __( 'No cart found.', 'automatewoo' ) );
	}



	static function toggle_workflow_status() {

		if ( ! current_user_can( 'manage_woocommerce' ) )
			die;

		$workflow = AW()->get_workflow( absint( aw_request( 'workflow_id' ) ) );
		$new_state = Clean::string( aw_request( 'new_state' ) );

		if ( ! $workflow || ! $new_state )
			die;

		$workflow->update_status( $new_state === 'on' ? 'active' : 'disabled' );

		wp_send_json_success();
	}



	static function update_dynamic_action_select() {

		if ( ! current_user_can( 'manage_woocommerce' ) )
			die;

		$action_name = Clean::string( aw_request( 'action_name' ) );
		$target_field_name = Clean::string( aw_request( 'target_field_name' ) );
		$reference_field_value = Clean::string( aw_request( 'reference_field_value' ) );

		$options = [];

		if ( $reference_field_value ) {
			$action = Actions::get( $action_name );
			$options = $action->get_dynamic_field_options( $target_field_name, $reference_field_value );
		}

		wp_send_json_success( $options );
	}

}
