<?php

namespace AutomateWoo;

/**
 * @class Tools
 * @since 2.4.5
 */
class Tools {

	/** @var $tools array */
	public static $tools = [];


	/**
	 * @return Tool[]
	 */
	static function get_tools() {

		if ( empty( self::$tools ) ) {

			$path = AW()->path( '/includes/tools/' );

			$tool_includes = [
				$path . 'reset-workflow-records.php',
				$path . 'manual-orders-trigger.php',
				$path . 'unsubscribe-importer.php'
			];

			if ( Integrations::subscriptions_enabled() ) {
				$tool_includes[] = $path . 'manual-subscriptions-trigger.php';
			}

			$tool_includes = apply_filters( 'automatewoo/tools', $tool_includes );

			foreach ( $tool_includes as $tool_include ) {
				$class = include_once $tool_include;
				self::$tools[$class->id] = $class;
			}
		}

		return self::$tools;
	}


	/**
	 * @param $id
	 * @return Tool|false
	 */
	static function get_tool( $id ) {
		$tools = self::get_tools();

		if ( isset( $tools[$id] ) ) {
			return $tools[$id];
		}

		return false;
	}


	/**
	 * @param $tool_id
	 * @param $args
	 */
	static function new_background_process( $tool_id, $args ) {
		wp_schedule_single_event( time(), 'automatewoo/tools/background_process', [ $tool_id, $args ] );
	}


	/**
	 * @param $tool_id
	 * @param $args
	 */
	static function handle_background_process( $tool_id, $args ) {

		$tool = self::get_tool( $tool_id );

		$args = $tool->background_process_batch( $args, self::get_batch_size() );

		if ( $args ) {
			wp_schedule_single_event( time() + self::get_batch_delay(), 'automatewoo/tools/background_process', [ $tool_id, $args ]);
		}
	}


	/**
	 * @return int
	 */
	static function get_batch_size() {
		return apply_filters( 'automatewoo/tools/batch_size', 25 );
	}


	/**
	 * @return int
	 */
	static function get_batch_delay() {
		return apply_filters( 'automatewoo/tools/batch_delay', 5 ) * 60; // 5 minute delay
	}

}
