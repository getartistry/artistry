<?php

namespace AutomateWoo;

/**
 * @class Trigger
 */
abstract class Trigger {

	/** @var string */
	public $title;

	/** @var string */
	public $name;

	/** @var string */
	public $description;

	/** @var string */
	public $group;

	/** @var array */
	public $supplied_data_items = [];

	/** @var bool */
	public $allow_queueing = true;

	/** @var array */
	public $fields = [];

	/** @var array */
	public $options;

	/** @var array */
	protected $rules;

	/** @var bool */
	protected $has_loaded_fields = false;

	/** @var bool */
	public $has_loaded_admin_details = false;


	abstract function register_hooks();


	/**
	 * Construct
	 */
	function __construct() {
		$this->supplied_data_items[] = 'shop';
		$this->init();

		// compatibility for user and customer objects
		if ( in_array( 'user', $this->supplied_data_items ) ) {
			$this->supplied_data_items[] = 'customer';
		}

		// backwards compat for custom triggers using the user data item, IMPORTANT to exclude guest triggers
		if ( in_array( 'customer', $this->supplied_data_items ) && ! in_array( 'guest', $this->supplied_data_items ) ) {
			$this->supplied_data_items[] = 'user';
		}

		$this->supplied_data_items = array_unique( $this->supplied_data_items );

		add_action( 'automatewoo_init_triggers', [ $this, 'register_hooks' ] );
	}


	/**
	 * Init
	 */
	function init() {}


	/**
	 * Method to set title, group, description and other admin props
	 */
	function load_admin_details() {}


	/**
	 * Registers any fields used on for a trigger
	 */
	function load_fields() {}


	/**
	 * Admin info loader
	 */
	function maybe_load_admin_details() {
		if ( ! $this->has_loaded_admin_details ) {
			$this->load_admin_details();
			$this->has_loaded_admin_details = true;
		}
	}


	/**
	 * Field loader
	 */
	function maybe_load_fields() {
		if ( ! $this->has_loaded_fields ) {
			$this->load_fields();
			$this->has_loaded_fields = true;
		}
	}


	/**
	 * @param Workflow $workflow
	 * @return bool
	 */
	function validate_workflow( $workflow ) {
		return true;
	}


	/**
	 * @param $option object
	 */
	function add_field( $option ) {
		$option->set_name_base( 'aw_workflow_data[trigger_options]' );
		$this->fields[ $option->get_name() ] = $option;
	}


	/**
	 * @param $option_name
	 */
	function remove_field( $option_name ) {
		if ( isset( $this->fields[ $option_name ] ) ) {
			unset( $this->fields[ $option_name ] );
		}
	}


	/**
	 * @return array
	 */
	function get_supplied_data_items() {
		return $this->supplied_data_items;
	}


	/**
	 * @param $name
	 *
	 * @return mixed
	 */
	function get_field( $name ) {
		$this->maybe_load_fields();

		if ( ! isset( $this->fields[$name] ) ) {
			return false;
		}

		return $this->fields[$name];
	}


	/**
	 * @return Fields\Field[]
	 */
	function get_fields() {
		$this->maybe_load_fields();
		return $this->fields;
	}


	/**
	 * @return bool
	 */
	function has_workflows() {
		$query = new \WP_Query([
			'post_type' => 'aw_workflow',
			'post_status' => 'publish',
			'fields' => 'ids',
			'posts_per_page' => 1,
			'meta_query' => [
				[
					'key' => 'trigger_name',
					'value' => $this->get_name()
				]
			],
			'suppress_filters' => true,
			'no_found_rows' => true
		]);

		return $query->post_count != 0;
	}


	/**
	 * @return array
	 */
	function get_workflow_ids() {

		$query = new Workflow_Query();
		$query->set_return( 'ids' );
		$query->set_trigger( $this );
		$workflows = $query->get_results();

		return $workflows;
	}


	/**
	 * @return Workflow[]
	 */
	function get_workflows() {

		$workflows = [];

		foreach ( $this->get_workflow_ids() as $workflow_id ) {
			if ( $workflow = AW()->get_workflow( $workflow_id ) ) {
				$workflows[] = $workflow;
			}
		}

		return apply_filters( 'automatewoo/trigger/workflows', $workflows, $this );
	}


	/**
	 * Every data item registered with the trigger should be supplied to this method in its object form.
	 * E.g. a 'user' should be passed as a WP_User object, and an 'order' should be passed as a WC_Order object
	 *
	 * @param array $data_items
	 */
	function maybe_run( $data_items = [] ) {

		// Get all workflows that are registered to use this trigger
		if ( ! $workflows = $this->get_workflows() ) {
			return;
		}

		// Check if each workflow should be run based on its options
		foreach ( $workflows as $workflow ) {
			$workflow->maybe_run( $data_items );
		}
	}


	/**
	 * @return string
	 */
	function get_name() {
		return $this->name;
	}


	/**
	 * @param string $name
	 */
	function set_name( $name ) {
		$this->name = $name;
	}


	/**
	 * @return string
	 */
	function get_title() {
		$this->maybe_load_admin_details();
		return $this->title;
	}


	/**
	 * @return string
	 */
	function get_group() {
		$this->maybe_load_admin_details();
		return $this->group ? $this->group : __( 'Other', 'automatewoo' );
	}


	/**
	 * @return string|null
	 */
	function get_description() {
		$this->maybe_load_admin_details();
		return $this->description;
	}


	/**
	 * @return string
	 */
	function get_description_html() {

		if ( ! $this->get_description() )
			return '';

		return '<p class="aw-field-description">' . $this->get_description() .'</p>';
	}


	/**
	 * @param $options array
	 * @deprecated
	 */
	function set_options( $options ) {
		$this->options = $options;
	}


	/**
	 * Will return all data if $field is false
	 *
	 * @param string $field
	 * @return mixed
	 *
	 * @deprecated use $workflow->get_trigger_option()
	 */
	function get_option( $field ) {

		if ( ! $field ) return false;

		$value = false;

		if ( isset( $this->options[$field] ) ) {
			$value = $this->options[$field];
		}

		return apply_filters( 'automatewoo_trigger_option', $value, $field, $this );
	}



	/**
	 * This method is called just before a queued workflow runs
	 *
	 * @param Workflow $workflow
	 * @return bool
	 */
	function validate_before_queued_event( $workflow ) {
		return true;
	}


	/**
	 * Checks if this trigger's language matches that of the user or guest
	 *
	 * @param Workflow $workflow
	 * @return bool
	 */
	function validate_workflow_language( $workflow ) {

		if ( ! Integrations::is_wpml() ) {
			return true;
		}

		if ( ! $workflow_lang = $workflow->get_language() ) {
			return true; // workflow has no set language
		}

		if ( ! $data_lang = $workflow->data_layer()->get_language() ) {
			return true;
		}

		return $data_lang == $workflow_lang;
	}


	protected function add_field_validate_queued_order_status() {

		$field = new Fields\Checkbox();
		$field->set_name('validate_order_status_before_queued_run');
		$field->set_title( __('Recheck status before run', 'automatewoo' ) );
		$field->default_to_checked = true;
		$field->set_description(
			__( "This is useful for Workflows that are not run immediately as it ensures the status of the order hasn't changed since initial trigger." ,
				'automatewoo'  ) );

		$this->add_field( $field );
	}


	/**
	 *
	 */
	protected function add_field_user_pause_period() {

		$field = ( new Fields\Number() )
			->set_name( 'user_pause_period' )
			->set_title( __( 'Customer pause period (days)', 'automatewoo' ) )
			->set_description( __( 'Can be used to ensure that this trigger will only send once in a set period to a user or guest.', 'automatewoo' ) );
		$this->add_field( $field );
	}


	/**
	 * @param $object_name
	 */
	protected function add_field_recheck_status( $object_name ) {

		$field = ( new Fields\Checkbox() )
			->set_name( 'recheck_status_before_queued_run' )
			->set_title( __( 'Recheck status before run', 'automatewoo') )
			->set_default_to_checked()
			->set_description( sprintf( __(
				"This is useful for workflows that are not run immediately as it ensures the status of the %s hasn't "
				. "changed since initial trigger." , 'automatewoo'  ), $object_name ) );

		$this->add_field( $field );
	}



	/**
	 * Order status field must be named 'order_status'
	 *
	 * @param $trigger Trigger
	 * @param $order \WC_Order
	 * @deprecated
	 * @return bool
	 * @since 2.0
	 */
	protected function validate_order_status_field( $trigger, $order ) {

		$status = Clean::string( $trigger->get_option('order_status') );

		if ( ! $status ) return true;

		$status = 'wc-' === substr( $status, 0, 3 ) ? substr( $status, 3 ) : $status;

		// wrong status
		if ( $order->get_status() !== $status )
			return false;

		return true;
	}



	/**
	 * @param $workflow Workflow
	 * @return bool
	 */
	protected function validate_field_user_pause_period( $workflow ) {

		$period = floatval( $workflow->get_trigger_option( 'user_pause_period' ) );

		$customer = $workflow->data_layer()->get_customer();
		$user = $workflow->data_layer()->get_user();
		$guest = $workflow->data_layer()->get_guest();

		if ( empty( $period ) ) return true; // no pause period set

		if ( ! $user && ! $guest && ! $customer ) return true; // must have a customer, user or guest

		$hours = $period * 24;

		$period_date = new \DateTime();
		$period_date->modify("-$hours hours");

		// Check to see if this workflow has run since the period date
		$log_query = ( new Log_Query() )
			->where( 'workflow_id', $workflow->get_translation_ids() )
			->where('date', $period_date, '>');

		if ( $customer ) {
			$log_query->where( '_data_layer_customer', $customer->get_id() );
		}
		elseif ( $user ) {
			if ( $user->ID === 0 ) { // guest user
				$log_query->where( 'guest_email', $user->user_email );
			}
			else {
				$log_query->where( 'user_id', $user->ID );
			}
		}
		elseif( $guest ) {
			$log_query->where( 'guest_email', $guest->get_email() );
		}

		if ( $log_query->has_results() ) {
			return false;
		}

		return true;
	}


	/**
	 * @param $allowed_statuses array|string
	 * @param $current_status string
	 *
	 * @return bool
	 */
	protected function validate_status_field( $allowed_statuses, $current_status ) {
		// allow all if left blank
		if ( empty( $allowed_statuses ) ) return true;

		if ( is_array( $allowed_statuses ) ) {
			// multi status match
			$with_prefix_match = in_array( 'wc-' . $current_status, $allowed_statuses );
			$no_prefix_match = in_array( $current_status, $allowed_statuses );

			// at least one has to match
			if ( ! $with_prefix_match && ! $no_prefix_match )
				return false;
		}
		else {
			// single status match, remove prefix
			$allowed_statuses = 'wc-' === substr( $allowed_statuses, 0, 3 ) ? substr( $allowed_statuses, 3 ) : $allowed_statuses;

			if ( $allowed_statuses != $current_status )
				return false;
		}

		return true;
	}


	/**
	 * Get the order status change hook, async or instant
	 * @return string
	 */
	protected function get_hook_order_status_changed() {
		return AUTOMATEWOO_DISABLE_ASYNC_ORDER_STATUS_CHANGED ? 'automatewoo/order/status_changed' : 'automatewoo/order/status_changed_async';
	}


	/**
	 * Get the order paid, async or instant
	 * @return string
	 */
	protected function get_hook_order_paid() {
		return AUTOMATEWOO_DISABLE_ASYNC_ORDER_STATUS_CHANGED ? 'automatewoo/order/paid' : 'automatewoo/order/paid_async';
	}


	/**
	 * Get the subscription status change hook, async or instant
	 * @return string
	 */
	protected function get_hook_subscription_status_changed() {
		return AUTOMATEWOO_DISABLE_ASYNC_SUBSCRIPTION_STATUS_CHANGED ? 'automatewoo/subscription/status_changed' : 'automatewoo/subscription/status_changed_async';
	}


	/**
	 * @return string
	 */
	protected function get_deprecation_warning() {
		return __( 'THIS TRIGGER IS DEPRECATED AND SHOULD NOT BE USED.', 'automatewoo' );
	}

}
