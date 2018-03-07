<?php

namespace AutomateWoo;

/**
 * @class Workflow
 */
class Workflow {

	/** @var int */
	public $id;

	/** @var \WP_Post */
	public $post;

	/** @var string */
	public $title;

	/** @var Trigger */
	private $trigger;

	/** @var Actions[] */
	private $actions;

	/** @var Data_Layer */
	private $data_layer;

	/** @var array */
	private $trigger_options;

	/** @var array */
	private $rule_options;

	/** @var Variables_Processor */
	private $variable_processor;

	/** @var Workflow_Location */
	private $location;

	/** @var Workflow_Location  */
	private $tax_location;

	/** @var Log */
	public $log;

	/** @var bool */
	public $exists = false;

	/** @var bool */
	public $preview_mode = false;

	/** @var bool */
	public $test_mode = false;


	/**
	 * @param $post mixed (object or post ID)
	 */
	function __construct( $post ) {

		if ( ! $post instanceof \WP_Post ) {
			// Get from id
			$post = get_post($post);
		}

		// workflow doesn't exists
		if ( ! $post )
			return;

		$this->exists = true;
		$this->post = $post;
		$this->id = $post->ID;
		$this->title = $post->post_title;
	}


	/**
	 * @return int
	 */
	function get_id() {
		return $this->id ? Clean::id( $this->id ) : 0;
	}


	/**
	 * @return string
	 */
	function get_title() {
		return $this->title;
	}


	/**
	 * @return string
	 */
	function get_date_created() {
		return $this->post->post_date_gmt;
	}


	/**
	 * @return Variables_Processor
	 */
	function variable_processor() {

		if ( ! isset( $this->variable_processor ) ) {
			$this->variable_processor = new Variables_Processor( $this );
		}

		return $this->variable_processor;
	}


	/**
	 * @return Data_Layer
	 */
	function data_layer() {
		if ( ! isset( $this->data_layer ) ) {
			$this->data_layer = new Data_Layer();
		}

		return $this->data_layer;
	}


	/**
	 * @return Trigger|false
	 */
	function get_trigger() {
		if ( ! isset( $this->trigger ) ) {

			$this->trigger = false;
			$trigger_name = $this->get_meta( 'trigger_name' );

			if ( $trigger_name && Triggers::get( $trigger_name ) ) {
				// @todo clone triggers just to retrieve options now seems a little confusing and inefficient
				$this->trigger = clone Triggers::get( $trigger_name );
				$this->trigger->set_options( $this->get_trigger_options() );
			}
		}

		return $this->trigger;
	}


	/**
	 * @return Action[]
	 */
	function get_actions() {
		if ( ! isset( $this->actions ) ) {

			$this->actions = [];
			$actions_data = $this->get_meta( 'actions' );

			if ( ! is_array( $actions_data ) ) {
				return $this->actions;
			}

			$n = 1;
			foreach ( $actions_data as $action ) {
				if ( isset( $action['action_name'] ) && Actions::get( $action['action_name'] ) ) {
					$action_obj = clone Actions::get( $action['action_name'] );
					$action_obj->set_options( $action );
					$this->actions[$n] = $action_obj;
					$n++;
				}
			}
		}

		return $this->actions;
	}


	/**
	 * Returns the saved actions with their data
	 *
	 * @param $number
	 * @return Action|false
	 */
	function get_action( $number ) {

		$actions = $this->get_actions();

		if ( ! isset( $actions[$number] ) ) {
			return false;
		}

		return $actions[$number];
	}


	/**
	 * @param Data_Layer|array $data
	 * @param bool $skip_validation
	 * @param bool $force_immediate
	 */
	function maybe_run( $data, $skip_validation = false, $force_immediate = false ) {

		// setup language and data before validation occurs
		$this->setup( $data );

		if ( $skip_validation || $this->validate_workflow() ) {

			if ( $this->get_timing_type() == 'immediately' || $force_immediate ) {
				$this->run();
			}
			else {
				$this->queue();
			}
		}

		$this->cleanup();
	}


	/**
	 * @return bool
	 */
	function validate_workflow() {

		if ( ! $this->is_active() )
			return false;

		if ( ! $trigger = $this->get_trigger() )
			return false;

		if ( ! $trigger->validate_workflow_language( $this ) )
			return false;

		if ( ! $trigger->validate_workflow( $this ) )
			return false;

		if ( ! $this->validate_rules() )
			return false;

		if ( ! apply_filters( 'automatewoo_custom_validate_workflow', true, $this ) )
			return false;

		return true;
	}


	/**
	 * @return bool
	 */
	function validate_rules() {

		$rule_options = $this->get_rule_options();

		// no rules exists
		if ( empty( $rule_options ) )
			return true;

		foreach ( $rule_options as $rule_group ) {

			$is_group_valid = true;

			foreach ( $rule_group as $rule ) {

				// rules have AND relationship so all must return true
				if ( ! $this->validate_rule( $rule ) ) {
					$is_group_valid = false;
					break;
				}
			}

			// groups have an OR relationship so if one is valid we can break the loop and return true
			if ( $is_group_valid )
				return true;
		}

		// no groups were valid
		return false;
	}


	/**
	 * Returns true if rule is missing data so that the rule is skipped
	 *
	 * @param array $rule
	 * @return bool
	 */
	function validate_rule( $rule ) {

		if ( ! is_array( $rule ) )
			return true;

		$rule_name = isset( $rule['name'] ) ? $rule['name'] : false;
		$rule_compare = isset( $rule['compare'] ) ? $rule['compare'] : false;
		$rule_value = isset( $rule['value'] ) ? $rule['value'] : false;

		// its ok for compare to be false for boolean type rules
		if ( ! $rule_name ) {
			return true;
		}

		$rule_object = Rules::get( $rule_name );

		// rule doesn't exists
		if ( ! $rule_object )
			return false;

		// get the data required to validate the rule
		$data_item = $this->data_layer()->get_item( $rule_object->data_item );

		if ( ! $data_item )
			return false;

		// some rules need the full workflow object
		$rule_object->set_workflow( $this );

		return $rule_object->validate( $data_item, $rule_compare, $rule_value );
	}


	/**
	 * @return bool
	 */
	function run() {

		if ( AW_PREVENT_WORKFLOWS ) {
			$log = new \WC_Logger();
			$log->add( 'automatewoo-prevented-workflows', $this->title );
			return false;
		}

		do_action( 'automatewoo/workflow/before_run', $this );

		if ( ! $this->is_test_mode() ) {
			$this->create_run_log();
		}

		if ( $actions = $this->get_actions() ) {

			foreach ( $actions as $action ) {

				$action->workflow = $this;

				do_action('automatewoo_before_action_run', $action, $this );

				$action->run();

				do_action('automatewoo_after_action_run', $action, $this );
			}
		}

		do_action( 'automatewoo_after_workflow_run', $this );

		return true;
	}


	/**
	 * Reset the workflow object
	 * Clears any data that is related to the last run
	 * The trigger and actions don't need to be reset because their data flows from the workflow options not the workflow data layer
	 */
	function reset_data() {
		$this->data_layer()->clear();
		$this->log = null;
		$this->location = null;
		$this->tax_location = null;
	}


	/**
	 * Create queued event from workflow
	 * @return Queued_Event|false
	 */
	function queue() {

		$date = false;
		$queue = new Queued_Event();
		$queue->set_workflow_id( $this->get_id() );

		switch( $this->get_timing_type() ) {

			case 'delayed':
				$date = new \DateTime();
				$date->setTimestamp( time() + $this->get_timing_delay() );
				break;

			case 'scheduled':
				$date = $this->calculate_scheduled_datetime();
				break;

			case 'fixed':
				$date = $this->get_fixed_time();
				break;

			case 'datetime':
				$date = $this->get_variable_time();
				break;
		}

		$date = apply_filters( 'automatewoo/workflow/queue_date', $date, $this );

		if ( ! $date ) {
			return false;
		}

		$queue->set_date_due( $date );
		$queue->save();

		$queue->store_data_layer( $this->data_layer() ); // add meta data after saved

		return $queue;
	}


	/**
	 * Setup the state of the workflow before it is validated or checked
	 * @param array|Data_Layer|bool $data
	 */
	function setup( $data = false ) {

		// the only time data is false is in preview mode
		if ( $data ) {
			$this->set_data_layer( $data, true );
		}

		if ( Language::is_multilingual() ) {
			$lang = $this->get_language();
			Language::set_current( $lang );

			global $woocommerce_wpml;

			if ( $woocommerce_wpml ) {
				/** @var $woocommerce_wpml \woocommerce_wpml */
				$woocommerce_wpml->emails->change_email_language( $lang );
			}
		}

		$this->data_layer()->ensure_customer_data_matches_order();

		// Ensure mailer and gateways are loaded in case they need to insert data into the emails
		WC()->mailer();
		WC()->payment_gateways();
		WC()->shipping();

		add_filter( 'woocommerce_get_tax_location', [ $this, 'filter_tax_location' ], 50, 2 );

	}


	/**
	 * Clean up after workflow run
	 */
	function cleanup() {

		// reset language
		if ( Language::is_multilingual() ) {
			Language::set_original();
		}

		remove_filter( 'woocommerce_get_tax_location', [ $this, 'filter_tax_location' ]  );
	}


	/**
	 * Record that the workflow has been run
	 */
	function create_run_log() {

		$this->log = new Log();
		$this->log->set_workflow_id( $this->get_id() );
		$this->log->set_date( new \DateTime() );

		if ( $this->is_tracking_enabled() ) {
			$this->log->set_tracking_enabled( true );

			if ( $this->is_conversion_tracking_enabled() ) {
				$this->log->set_conversion_tracking_enabled( true );
			}
		}

		$this->log->save();
		$this->log->store_data_layer( $this->data_layer() );

		do_action( 'automatewoo_create_run_log', $this->log, $this );
	}



	/**
	 * @return int
	 */
	function get_times_run() {

		$cache_key = 'times_run/workflow=' . $this->get_id();
		$cache = Cache::get_transient( $cache_key );

		if ( $cache !== false ) {
			return (int) $cache;
		}

		$query = new Log_Query();
		$query->where( 'workflow_id', $this->get_id() );
		$count = $query->get_count();

		Cache::set_transient( $cache_key, $count, 720 );

		return $count;
	}


	/**
	 * @param bool $try_cache
	 * @return int|string
	 */
	function get_current_queue_count( $try_cache = true ) {

		$cache_key = 'current_queue_count/workflow=' . $this->get_id();
		$cache = Cache::get_transient( $cache_key );

		if ( $try_cache && $cache !== false ) {
			return $cache;
		}
		else {

			$query = new Queue_Query();
			$query->where( 'workflow_id', $this->get_id() );
			$count = $query->get_count();

			Cache::set_transient( $cache_key, $count, 720 );

			return $count;
		}
	}


	/**
	 * @param string $name
	 * @param bool $replace_vars
	 * @return mixed
	 */
	function get_option( $name, $replace_vars = false ) {

		$options = $this->get_meta( 'workflow_options' );

		if ( ! is_array( $options ) || ! isset( $options[$name] ) )
			return false;

		if ( $replace_vars ) {
			return $this->variable_processor()->process_field( $options[$name] );
		}

		return apply_filters( 'automatewoo/workflow/option', $options[$name], $name, $this );
	}


	/**
	 * Returns options are immediately, delayed, scheduled, datetime
	 * @since 2.9
	 * @return string
	 */
	function get_timing_type() {
		$when = Clean::string( $this->get_option( 'when_to_run' ) );
		if ( ! $when ) $when = 'immediately';
		return $when;
	}


	/**
	 * Return the delay period in seconds
	 * @since 2.9
	 * @return integer
	 */
	function get_timing_delay() {

		$timing_type = $this->get_timing_type();

		if ( ! in_array( $timing_type, [ 'delayed', 'scheduled' ] ) ) {
			return 0;
		}

		$number = $this->get_timing_delay_number();
		$unit = $this->get_timing_delay_unit();

		$units = [
			'm' => MINUTE_IN_SECONDS,
			'h' => HOUR_IN_SECONDS,
			'd' => DAY_IN_SECONDS,
			'w' => WEEK_IN_SECONDS
		];

		if ( ! $number || ! isset( $units[$unit] ) ) {
			return 0;
		}

		return $number * $units[$unit];
	}


	/**
	 * @return int
	 */
	function get_timing_delay_number() {
		return (float) $this->get_option('run_delay_value');
	}


	/**
	 * @return string
	 */
	function get_timing_delay_unit() {
		return Clean::string( $this->get_option('run_delay_unit') );
	}


	/**
	 * Calculate the next point in time that matches the workflow scheduling options
	 * @param bool|integer $current_timestamp - optional, not GMT
	 * @return bool|\DateTime
	 */
	function calculate_scheduled_datetime( $current_timestamp = false ) {

		if ( $this->get_timing_type() !== 'scheduled' ) {
			return false;
		}

		if ( ! $current_timestamp ) {
			$current_timestamp = current_time( 'timestamp' ); // calculate based on the local timezone
		}

		// scheduled day and time are in the sites specified timezone
		$scheduled_time = $this->get_scheduled_time();
		$scheduled_days = $this->get_scheduled_days();
		$scheduled_time_seconds_from_day_start = Time_Helper::calculate_seconds_from_day_start( $scheduled_time );

		// get minimum datetime before scheduling can happen, if no delay is set then this will be now
		$min_wait_datetime = new \DateTime;
		$min_wait_datetime->setTimestamp( $current_timestamp + $this->get_timing_delay() );
		$min_wait_time_seconds_from_day_start = Time_Helper::calculate_seconds_from_day_start( $min_wait_datetime );

		// check to see if the scheduled time of day is later than the min wait time
		$is_scheduled_time_later_than_min_wait_time = $min_wait_time_seconds_from_day_start < $scheduled_time_seconds_from_day_start;

		// if the scheduled time comes before the current min wait time we can not schedule on the same day as the min wait
		// therefore update the min wait datetime so that is its midnight of the next day
		if ( ! $is_scheduled_time_later_than_min_wait_time ) {
			$min_wait_datetime->modify('+1 day');
		}

		$min_wait_datetime->setTime( 0, 0, 0 ); // set time to midnight, time will be added on later

		// check if scheduled day matches the min wait day
		if ( $scheduled_days && ! in_array( $min_wait_datetime->format( 'N' ), $scheduled_days ) ) {

			// advance time until a matching day is found
			while ( ! in_array( $min_wait_datetime->format( 'N' ), $scheduled_days ) ) {
				$min_wait_datetime->modify('+1 day');
			}
		}

		$scheduled_time = new \DateTime;
		$scheduled_time->setTimestamp( $min_wait_datetime->getTimestamp() );
		$scheduled_time->modify( "+$scheduled_time_seconds_from_day_start seconds" );

		Time_Helper::convert_to_gmt( $scheduled_time );

		return $scheduled_time;
	}


	/**
	 * @return string
	 */
	function get_scheduled_time() {
		return Clean::string( $this->get_option( 'scheduled_time' ) );
	}


	/**
	 * Returns empty if set to any day, 1 (for Monday) through 7 (for Sunday)
	 * @return array
	 */
	function get_scheduled_days() {
		return Clean::ids( $this->get_option( 'scheduled_day' ) );
	}


	/**
	 * @return \DateTime|bool
	 */
	function get_fixed_time() {

		$date = Clean::string( $this->get_option('fixed_date') );
		$time = array_map( 'absint', (array) $this->get_option('fixed_time') );

		if ( ! $date ) {
			return false;
		}

		$datetime = new \DateTime( $date );
		$datetime->setTime( isset($time[0]) ? $time[0] : 0, isset($time[1]) ? $time[1] : 0, 0 );

		Time_Helper::convert_to_gmt( $datetime );

		return $datetime;
	}


	/**
	 * Get scheduled date as set by variable timing option
	 * @return \DateTime|bool
	 */
	function get_variable_time() {
		$datetime = $this->get_option( 'queue_datetime', true );

		if ( ! $datetime ) {
			return false;
		}

		$timestamp = strtotime( $datetime, current_time( 'timestamp' ) );

		$date = new \DateTime();
		$date->setTimestamp( $timestamp );
		Time_Helper::convert_to_gmt( $date ); // convert to UTC

		return $date;
	}


	/**
	 * @return array
	 */
	function get_trigger_options() {

		if ( ! isset( $this->trigger_options ) ) {

			$this->trigger_options = $this->get_meta( 'trigger_options' );

			if ( ! $this->trigger_options ) {
				$this->trigger_options = [];
			}
		}

		return $this->trigger_options;
	}


	/**
	 * @param $name
	 * @return mixed
	 */
	function get_trigger_option( $name ) {

		$this->get_trigger_options(); // ensure options are loaded

		if ( ! isset( $this->trigger_options[$name] ) )
			return false;

		return apply_filters( 'automatewoo_trigger_option', $this->trigger_options[$name], $name, $this );
	}


	/**
	 * @param array $rule_options
	 */
	function set_rule_options( $rule_options ) {

		if ( ! is_array( $rule_options ) )
			return;

		$this->rule_options = $rule_options;
		$this->update_meta( 'rule_options', $rule_options );
	}


	/**
	 * @return array
	 */
	function get_rule_options() {

		if ( ! isset( $this->rule_options ) ) {
			$this->rule_options = $this->get_meta( 'rule_options' );

			if ( ! $this->rule_options ) {
				$this->rule_options = [];
			}
		}

		return $this->rule_options;
	}


	/**
	 * Returns the log currently created by this workflow run.
	 * If false is returned the log record may not have been created.
	 *
	 * @return Log|bool
	 */
	function get_current_log() {
		if ( isset( $this->log ) ) {
			return $this->log;
		}
		return false;
	}


	/**
	 * Check if workflow is exempt from unsubscribing and allow filtering of unsubscribed prop.
	 *
	 * @param Customer $customer
	 * @return bool
	 */
	function is_customer_unsubscribed( $customer ) {
		if ( $this->is_exempt_from_unsubscribing() ) {
			return false;
		}

		if ( ! $customer ) {
			return false;
		}

		return apply_filters( 'automatewoo/workflow/is_customer_unsubscribed', $customer->is_unsubscribed(), $this, $customer );
	}


	/**
	 * @param $user \WP_User or guest user
	 *
	 * @return bool
	 */
	function is_first_run_for_user( $user ) {
		return $this->get_times_run_for_user( $user ) === 0;
	}


	/**
	 * Counts items in log and in queue for this user and workflow
	 *
	 * @param $user \WP_User|Order_Guest
	 * @return int
	 */
	function get_times_run_for_user( $user ) {

		$query = ( new Log_Query() )
			->where( 'workflow_id', $this->get_id() );

		if ( $user->ID === 0 ) { // guest user
			$query->where( 'guest_email', $user->user_email );
		}
		else {
			$query->where( 'user_id', $user->ID );
		}

		return count( $query->get_results() );
	}


	/**
	 * Counts items in log and in queue for this user and workflow
	 *
	 * @param Customer $customer
	 * @return int
	 */
	function get_run_count_for_customer( $customer ) {

		$query = new Log_Query();
		$query->where( 'workflow_id', $this->get_id() );

		if ( $customer->is_registered() ) {
			// match by user OR customer, important as workflow will be converted from user to customer
			$query->where_meta[] = [
				[
					'key' => 'user_id',
					'value' => $customer->get_user_id()
				],
				[
					'key' => '_data_layer_customer',
					'value' => $customer->get_id()
				]
			];
		}
		else {
			$query->where_meta[] = [
				[
					'key' => 'guest_email',
					'value' => $customer->get_email()
				],
				[
					'key' => '_data_layer_customer',
					'value' => $customer->get_id()
				]
			];
		}

		return $query->get_count();
	}


	/**
	 * @param \WC_Order $order
	 * @return int
	 */
	function get_run_count_for_order( $order ) {

		$query = ( new Log_Query() )
			->where( 'workflow_id', $this->get_id() )
			->where( 'order_id', Compat\Order::get_id( $order ) );

		return $query->get_count();
	}


	/**
	 * Counts items in log and in queue for this guest and workflow
	 *
	 * @param $guest Guest
	 * @return int
	 */
	function get_times_run_for_guest( $guest ) {

		$query = ( new Log_Query() )
			->where( 'workflow_id', $this->get_id() )
			->where( 'guest_email', $guest->get_email() );

		return $query->get_count();
	}


	/**
	 * @param $name
	 * @param $item
	 */
	function set_data_item( $name, $item ) {
		$this->data_layer()->set_item( $name, $item );
	}


	/**
	 * @param array|Data_Layer $data_layer
	 * @param bool $reset_workflow_data
	 */
	function set_data_layer( $data_layer, $reset_workflow_data ) {

		if ( ! is_a( $data_layer, 'AutomateWoo\Data_Layer' ) ) {
			$data_layer = new Data_Layer( $data_layer );
		}

		if ( $reset_workflow_data ) {
			$this->reset_data();
		}

		$this->data_layer = $data_layer;
	}


	/**
	 * Retrieve and validate a data item
	 *
	 * @param $name string
	 * @return mixed
	 */
	function get_data_item( $name ) {
		return $this->data_layer()->get_item( $name );
	}


	/**
	 * @return bool
	 */
	function is_active() {
		if ( ! $this->exists ) return false;
		return $this->post->post_status == 'publish';
	}


	/**
	 * @param string $status active|disabled or publish|aw-disabled
	 */
	function update_status( $status ) {

		if ( $status === 'active' ) {
			$post_status = 'publish';
		}
		elseif ( $status === 'disabled' ) {
			$post_status = 'aw-disabled';
		}
		else {
			$post_status = $status;
		}

		wp_update_post([
			'ID' => $this->get_id(),
			'post_status' => $post_status
		]);
	}


	/**
	 * @return bool
	 */
	function is_tracking_enabled() {
		return (bool) $this->get_option( 'click_tracking' );
	}


	/**
	 * @return bool
	 */
	function is_conversion_tracking_enabled() {
		return (bool) $this->get_option( 'conversion_tracking' );
	}


	/**
	 * @return bool
	 */
	function is_ga_tracking_enabled() {
		return ( $this->is_tracking_enabled() && $this->get_ga_tracking_params() ) ;
	}


	/**
	 * @return string
	 */
	function get_ga_tracking_params() {
		return trim( $this->get_option( 'ga_link_tracking', true ) );
	}


	/**
	 * @param string $url
	 * @return string
	 */
	function append_ga_tracking_to_url( $url ) {

		if ( empty( $url ) || ! $this->is_ga_tracking_enabled() ) {
			return $url;
		}

		$params = [];
		parse_str( $this->get_ga_tracking_params(), $params );

		return add_query_arg( $params, $url );
	}


	/**
	 * @return false|string
	 */
	function get_language() {
		if ( Integrations::is_wpml() ) {
			$info = wpml_get_language_information( null, $this->get_id() );
			if ( is_array( $info ) )
				return $info['language_code'];
		}
		return false;
	}


	/**
	 * Return array with all versions of this workflow including the original
	 * @return array
	 */
	function get_translation_ids() {

		if ( ! Integrations::is_wpml() ) {
			return [ $this->get_id() ];
		}

		global $sitepress;

		$ids = [];

		$translations = $sitepress->get_element_translations( $this->get_id(), 'post_post', false, true );

		if ( is_array( $translations ) ) {
			foreach ( $translations as $translation ) {
				$ids[] = $translation->element_id;
			}
		}

		$ids[] = $this->get_id(); // sometimes wpml doesn't return default language id?

		return Clean::ids( $ids );
	}


	/**
	 * @param $key
	 * @param bool $single
	 * @return mixed
	 */
	function get_meta( $key, $single = true ) {
		return get_post_meta( $this->get_id(), $key, $single );
	}


	/**
	 * @param $key
	 * @param $value
	 * @return bool|int
	 */
	function update_meta( $key, $value ) {
		return update_post_meta( $this->get_id(), $key, $value );
	}


	/**
	 * Enabling preview mode also enables test mode
	 */
	function enable_preview_mode() {
		$this->preview_mode = true;
		$this->enable_test_mode();
	}


	/**
	 * Enable test mode
	 */
	function enable_test_mode() {
		$this->test_mode = true;
		$this->set_data_layer( Preview_Data::get_preview_data_layer(), false );
	}


	/**
	 * @return bool
	 */
	function is_test_mode() {
		return $this->test_mode;
	}


	/**
	 * @return bool
	 */
	function is_preview_mode() {
		return $this->preview_mode;
	}


	/**
	 * @param Action $action
	 * @param $note
	 */
	function log_action_note( $action, $note ) {
		if ( ! $log = $this->get_current_log() ) {
			return;
		}

		$log->add_note( $action->get_title() . ': ' . $note );
	}


	/**
	 * @param Action $action
	 * @param $error
	 */
	function log_action_error( $action, $error ) {
		if ( ! $log = $this->get_current_log() ) {
			return;
		}

		$log->add_note( $action->get_title() . ': ' . $error );

		$log->set_has_errors( true );
		$log->save();
	}


	/**
	 * Logs the error response from the Mailer class.
	 * Separates true mail errors from unsubscribes and blacklist errors and logs them accordingly.
	 *
	 * @param \WP_Error $error
	 * @param Action $action
	 */
	function log_action_email_error( $error, $action ) {
		if ( ! $log = $this->get_current_log() ) {
			return;
		}

		if ( $error->get_error_code() === 'email_unsubscribed' || $error->get_error_code() === 'email_blacklisted' ) {
			$this->log_action_note( $action, $error->get_error_message() );
			$log->set_has_blocked_emails( true );
			$log->save();
		}
		else {
			$this->log_action_error( $action, $error->get_error_message() );
		}
	}


	/**
	 * @return Workflow_Location
	 */
	function get_location() {

		if ( ! isset( $this->location ) ) {
			$this->location = new Workflow_Location( $this );
			$this->location = apply_filters( 'automatewoo/workflow/location', $this->location, $this );
		}

		return $this->location;
	}


	/**
	 * @return Workflow_Location
	 */
	function get_tax_location() {

		if ( ! isset( $this->tax_location ) ) {
			$this->tax_location = new Workflow_Location( $this, get_option( 'woocommerce_tax_based_on' )  );
			$this->tax_location = apply_filters( 'automatewoo/workflow/tax_location', $this->tax_location, $this );
		}

		return $this->tax_location;
	}


	/**
	 * Set tax location for the current workflow user
	 *
	 * @param $location
	 * @param $tax_class
	 * @return array
	 */
	function filter_tax_location( $location, $tax_class ) {
		if ( 'base' === get_option( 'woocommerce_tax_based_on' ) ) {
			return $location;
		}

		return $this->get_tax_location()->get_location_array();
	}


	/**
	 * @return bool
	 */
	function is_exempt_from_unsubscribing() {
		$is_exempt = false;

		// this avoids breaking changes for any customers using this gist: https://gist.github.com/danielbitzer/c25209057ba063ed4adcb6764049f1b6
		if ( apply_filters( 'automatewoo_unsubscribe_url', home_url(), $this->get_id(), false ) === false ) {
			$is_exempt = true;
		}

		return apply_filters( 'automatewoo/workflow/is_exempt_from_unsubscribing', $is_exempt, $this->get_id(), $this );
	}





	/**
	 * @param $name
	 * @param $item
	 * @deprecated
	 */
	function add_data_item( $name, $item ) {
		$this->set_data_item( $name, $item );
	}

	/**
	 * @deprecated use log_action_note
	 * @param Action $action
	 * @param $note
	 */
	function add_action_log_note( $action, $note ) {
		$this->log_action_note( $action, $note );
	}


}
