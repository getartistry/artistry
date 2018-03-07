<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Admin_Workflow_Edit
 * @since 2.6.1
 */
class Admin_Workflow_Edit {

	/** @var Workflow */
	public $workflow;

	static $screen = 'aw_workflow';


	/**
	 * Constructor
	 */
	function __construct() {
		add_action( 'admin_head', [ $this, 'setup_workflow' ] );
		add_action( 'admin_head', [ $this, 'register_meta_boxes' ] );
		add_action( 'admin_head', [ $this, 'enqueue_scripts' ], 15 );
		add_action( 'admin_footer', [ $this, 'workflow_js_templates' ], 15 );
		add_action( 'save_post', [ $this, 'save' ] );

		add_filter( 'wp_insert_post_data', [ $this, 'insert_post_data' ] );
	}


	/**
	 * Setup workflow object
	 */
	function setup_workflow() {
		global $post;

		if ( $post && $post->post_status !== 'auto-draft' ) {
			$this->workflow = AW()->get_workflow( $post );
		}
	}


	/**
	 * Enqueue scripts
	 * Do this on the admin_head action so we have access to the post object
	 */
	function enqueue_scripts() {

		wp_dequeue_script( 'autosave' );

		wp_localize_script( 'automatewoo-workflows', 'automatewooWorkflowLocalizeScript', $this->get_js_data() );

		wp_enqueue_script( 'automatewoo-workflows' );
		wp_enqueue_script( 'automatewoo-variables' );
		wp_enqueue_script( 'automatewoo-rules' );

		wp_enqueue_media();

		// dummy editor for ajax cloning
		?><div style="display: none"><?php wp_editor( '', 'automatewoo_editor' ); ?></div><?php
	}


	/**
	 * @return array
	 */
	function get_js_data() {

		global $post;

		Rules::get_all(); // load all the rules into memory so the order is preserved

		// get rule options
		if ( $this->workflow ) {

			$rule_options = $this->workflow->get_rule_options();

			foreach ( $rule_options as &$rule_group ) {
				foreach ( $rule_group as &$rule ) {

				    if ( ! isset( $rule['name'] ) ) {
				        continue;
                    }

					$rule_object = Rules::get( $rule['name'] );

					if ( ! $rule_object ) {
						continue;
					}

					if ( $rule_object->type === 'object' ) {
						$rule['selected'] = $rule_object->get_object_display_value( $rule['value'] );
					}

					if ( $rule_object->type === 'select' ) {
						$rule_object->get_select_choices(); // load options in to object cache
					}
				}
			}
		} else {
			$rule_options = [];
		}


		// Pass action data map
		$actions_data = [];

		foreach ( Actions::get_all() as $action ) {
			$actions_data[$action->get_name()] = [
				'can_be_previewed' => $action->can_be_previewed,
				'required_data_items' => $action->required_data_items,
				'group' => sanitize_key( $action->get_group() )
			];
		}


		// variables data
		$variables_data = [];

		foreach ( Variables::get_list() as $data_type => $data_variables ) {
			$variables_data[$data_type] = array_keys( $data_variables );
		}

		// convert user variables to customer
		if ( isset( $variables_data['user'] ) ) {
			foreach ( $variables_data['user'] as $variable ) {
				$variables_data['customer'][] = $variable;
			}
		}

		$utm_source = 'workflow-edit';

		$meta_box_help_tips = [
			'rules_box' => Admin::help_link( Admin::get_docs_link( 'rules', $utm_source ) ),
			'trigger_box' => Admin::help_link( Admin::get_docs_link( 'triggers', $utm_source ) ),
			'actions_box' => Admin::help_link( Admin::get_docs_link( 'actions', $utm_source ) )
		];

		return [
			'id' => $post->ID,
			'isNew' => $post->post_status == 'auto-draft',
			'trigger' => $this->workflow ? self::get_trigger_data( $this->workflow->get_trigger() ) : false,
			'ruleOptions' => $rule_options,
			'allRules' => self::get_rules_data(),
			'actions' => $actions_data,
			'variables' => $variables_data,
			'metaBoxHelpTips' => $meta_box_help_tips
		];
	}


	/**
	 * @param Trigger $trigger
	 * @return array|false
	 */
	static function get_trigger_data( $trigger ) {
		$data = [];

		if ( ! $trigger ) {
			return false;
		}

		$data['title'] = $trigger->get_title();
		$data['name'] = $trigger->get_name();
		$data['description'] = $trigger->get_description();
		$data['supplied_data_items'] = array_values( $trigger->get_supplied_data_items() );
		$data['allow_queueing'] = $trigger->allow_queueing;

		return $data;
	}


	/**
	 * @return array
	 */
	static function get_rules_data() {
		$data = [];

		foreach ( Rules::get_all() as $rule ) {
			$rule_data = (array) $rule;
			$data[$rule->name] = $rule_data;
		}

		return $data;
	}


	/**
	 * Workflow meta boxes
	 */
	function register_meta_boxes() {

		remove_meta_box( 'submitdiv', self::$screen, 'side' );

		Admin::add_meta_box( 'save_box',
			__( 'Save', 'automatewoo' ), [ $this, 'meta_box_save' ],
			self::$screen, 'side'
		);

		Admin::add_meta_box( 'trigger_box',
			__( 'Trigger', 'automatewoo' ), [ $this, 'meta_box_triggers' ],
			self::$screen, 'normal', 'high'
		);

		Admin::add_meta_box( 'rules_box',
			__( 'Rules <small>(optional)</small>', 'automatewoo' ), [ $this, 'meta_box_rules' ],
			self::$screen, 'normal', 'high'
		);

		Admin::add_meta_box( 'actions_box',
			__( 'Actions', 'automatewoo' ), [ $this, 'meta_box_actions' ],
			self::$screen, 'normal', 'high'
		);

		Admin::add_meta_box( 'timing_box',
			__( 'Timing', 'automatewoo' ), [ $this, 'meta_box_timing' ],
			self::$screen, 'side'
		);

		Admin::add_meta_box( 'options_box',
			__( 'Options', 'automatewoo' ), [ $this, 'meta_box_options' ],
			self::$screen, 'side'
		);

		Admin::add_meta_box( 'variables_box',
			__( 'Variables', 'automatewoo' ), [ $this, 'meta_box_variables' ],
			self::$screen, 'side'
		);
	}


	/**
	 * Triggers meta box
	 */
	function meta_box_triggers() {
		Admin::get_view( 'meta-box-trigger', [
			'workflow' => $this->workflow,
			'current_trigger' => $this->workflow ? $this->workflow->get_trigger() : false
		] );
	}


	/**
	 * Rules meta box
	 */
	function meta_box_rules() {
		Admin::get_view( 'meta-box-rules', [
			'workflow' => $this->workflow,
			'selected_trigger' => $this->workflow ? $this->workflow->get_trigger() : false
		] );
	}


	/**
	 * Actions meta box
	 */
	function meta_box_actions() {

		$action_select_box_values = [];

		foreach ( Actions::get_all() as $action ) {
			$action_select_box_values[$action->get_group()][$action->get_name()] = $action->get_title();
		}

		Admin::get_view( 'meta-box-actions', [
			'workflow' => $this->workflow,
			'actions' => $this->workflow ? $this->workflow->get_actions() : false,
			'action_select_box_values' => $action_select_box_values
		] );
	}


	/**
	 * Variables meta box
	 */
	function meta_box_variables() {
		Admin::get_view( 'meta-box-variables' );
	}


	/**
	 * Timing meta box
	 */
	function meta_box_timing() {
		Admin::get_view( 'meta-box-timing', [
			'workflow' => $this->workflow
		] );
	}


	/**
	 * Options meta box
	 */
	function meta_box_options() {
		Admin::get_view( 'meta-box-options', [
			'workflow' => $this->workflow
		] );
	}


	/**
	 * Replace standard post submit box
	 */
	function meta_box_save() {
		Admin::get_view( 'meta-box-save', [
			'workflow' => $this->workflow
		] );
	}


	/**
	 *
	 */
	function workflow_js_templates() {
		Admin::get_view( 'js-workflow-templates' );
	}


	/**
	 * @param $post_id
	 */
	function save( $post_id ) {

	    global $wpdb;

		$save = [];
		$posted = aw_request( 'aw_workflow_data' );

		if ( ! is_array( $posted ) ) {
			return;
		}

		$save['trigger_name'] = isset( $posted['trigger_name'] ) ? Clean::string( $posted['trigger_name'] ) : false;
		$save['trigger_options'] = isset( $posted['trigger_options'] ) ? $posted['trigger_options'] : [];
		$save['actions'] = isset( $posted['actions'] ) ? $posted['actions'] : [];
		$save['rule_options'] = isset( $posted['rule_options'] ) ? Clean::recursive( $posted['rule_options'] ) : [];

		$options = [];
		$options['when_to_run'] = $this->extract_string_option_value( 'when_to_run', $posted, 'immediately' );
		$options['click_tracking'] = $this->extract_string_option_value( 'click_tracking', $posted );

		if ( $options['click_tracking'] ) {
		    $options['conversion_tracking'] = $this->extract_string_option_value( 'conversion_tracking', $posted );
		    $options['ga_link_tracking'] = $this->extract_string_option_value( 'ga_link_tracking', $posted );
        }

		if ( $save['trigger_name'] ) {
			if ( $trigger = Triggers::get( $save['trigger_name'] ) ) {

				// If queueing is disabled for the trigger force when to run option
				if ( ! $trigger->allow_queueing ) {
					$options['when_to_run'] = 'immediately';
				}
			}
		}

		switch ( $options['when_to_run'] ) {

			case 'delayed':
				$options['run_delay_value'] = $this->extract_string_option_value( 'run_delay_value', $posted );
				$options['run_delay_unit'] = $this->extract_string_option_value( 'run_delay_unit', $posted );
				break;

			case 'scheduled':
				$options['run_delay_value'] = $this->extract_string_option_value( 'run_delay_value', $posted );
				$options['run_delay_unit'] = $this->extract_string_option_value( 'run_delay_unit', $posted );
				$options['scheduled_time'] = $this->extract_string_option_value( 'scheduled_time', $posted );
				$options['scheduled_day'] = $this->extract_array_option_value( 'scheduled_day', $posted );
				break;

			case 'fixed':
				$options['fixed_date'] = $this->extract_string_option_value( 'fixed_date', $posted );
				$options['fixed_time'] = $this->extract_array_option_value( 'fixed_time', $posted );
				break;

			case 'datetime':
				$options['queue_datetime'] = $this->extract_string_option_value( 'queue_datetime', $posted );
				break;
		}


		foreach ( $save['actions'] as &$action_fields ) {
			foreach ( $action_fields as &$action_field ) {
				// encode emojis to avoid emoji serialization issues
				if ( is_string( $action_field ) ) {
					$action_field = wp_encode_emoji( $action_field );
				}
			}
		}

		$save['workflow_options'] = $options;

		// Save the data into meta
		foreach ( $save as $key => $value ) {
			update_post_meta( $post_id, $key, $value );
		}

	}


	/**
	 * @param string $option
	 * @param array $posted
	 * @param string $default
	 * @return string
	 */
	function extract_string_option_value( $option, $posted, $default = '' ) {
		return isset( $posted['workflow_options'][$option] ) ? Clean::string( $posted['workflow_options'][$option] ) : $default;
	}

	/**
	 * @param string $option
	 * @param array $posted
	 * @param array $default
	 * @return string
	 */
	function extract_array_option_value( $option, $posted, $default = [] ) {
		return isset( $posted['workflow_options'][$option] ) ? Clean::recursive( $posted['workflow_options'][$option] ) : $default;
	}


	/**
	 * @param array $data
	 * @return array
	 */
	function insert_post_data( $data ) {

		if ( $status = Clean::string( aw_request( 'workflow_status' ) ) ) {
			$data['post_status'] = $status === 'active' ? 'publish' : 'aw-disabled';
		}

		return $data;
	}


}

new Admin_Workflow_Edit();
