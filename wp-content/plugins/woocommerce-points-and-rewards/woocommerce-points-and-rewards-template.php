<?php
/**
 * WooCommerce Points and Rewards
 *
 * @package     WC-Points-Rewards/Templates
 * @author      WooThemes
 * @copyright   Copyright (c) 2013, WooThemes
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

/**
 * Template Function Overrides
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'woocommerce_points_rewards_my_points' ) ) {

	/**
	 * Template function to render the template
	 *
	 * @since 1.0
	 */
	function woocommerce_points_rewards_my_points() {
		global $wc_points_rewards;

		$points_balance = WC_Points_Rewards_Manager::get_users_points( get_current_user_id() );
		$points_label   = $wc_points_rewards->get_points_label( $points_balance );

		$count = apply_filters( 'wc_points_rewards_my_account_points_events', 5, get_current_user_id() );

		// get a set of points events, ordered newest to oldest
		$args = array(
			'orderby' => array(
				'field' => 'date',
				'order' => 'DESC',
			),
			'per_page' => $count,
			'paged'    => 0,
			'user'     => get_current_user_id(),
		);

		$events = WC_Points_Rewards_Points_Log::get_points_log_entries( $args );

		// load the template
		wc_get_template(
			'myaccount/my-points.php',
			array(
				'points_balance' => $points_balance,
				'points_label'   => $points_label,
				'events'         => $events,
			),
			'',
			$wc_points_rewards->get_plugin_path() . '/templates/'
		);
	}
}
