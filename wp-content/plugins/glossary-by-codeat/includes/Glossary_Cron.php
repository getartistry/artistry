<?php

/**
 * Glossary
 *
 * @package   Glossary
 * @author    Codeat <support@codeat.co>
 * @copyright 2016 GPL 2.0+
 * @license   GPL-2.0+
 * @link      http://codeat.co
 */

/**
 * The Cron system
 */
class Glossary_Cron {

	/**
	 * Initialize the class
	 *
	 * @since 1.0.0
	 * 
	 * @return void
	 */
	public function __construct() {
		add_action( 'admin_init', array( $this, 'count_terms' ) );
		include_once('CronPlus/cronplus.php');

		$args = array(
			'recurrence' => 'daily',
			'name' => 'glossary_terms_counter',
			'cb' => 'gl_update_counter',
		);
		$cronplus = new CronPlus( $args );
		$cronplus->schedule_event();
	}

	/**
	 * Force a manual update of count terms for the caching
	 * 
	 * @return void
	 */
	public function count_terms() {
		if ( !isset( $_GET[ 'gl_count_terms' ] ) ) {
			return;
		}

		if ( empty( $_GET[ 'gl_count_terms' ] ) ) {
			return;
		}

		if ( !current_user_can( 'manage_options' ) ) {
			return;
		}
		gl_update_counter();
	}

}

new Glossary_Cron();
