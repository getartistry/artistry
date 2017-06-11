<?php

/**
 * Class that handle all Buddypress hooks
 *
 * @since      2.5.0
 * @package    Wsi
 * @subpackage Wsi/includes
 * @author     Damian Logghe <info@timersys.com>
 */

class Wsi_BP {

	/**
	 * Bp Setup globals slugs
	 * @Since v2.1
	 * @returns void
	 */
	function setup_globals() {
		global $bp, $wpdb;

		if ( !isset( $bp->wsi ) ) {
			$bp->wsi = new stdClass;
		}

		$bp->wsi->id = 'wsi';

		$bp->wsi->table_name = $wpdb->prefix . 'wsi';
		$bp->wsi->slug = apply_filters( 'wsi_bp_base_slug', 'wp-social-invitations');

		/* Register this in the active components array */
		$bp->active_components[$bp->wsi->slug] = $bp->wsi->id;
	}


	/**
	 * Bp Navs
	 * @Since v2.1
	 * @returns void
	 */
	function setup_nav() {
		global $bp;

		/* Add 'Send Social Invites' to the main user profile navigation */
		bp_core_new_nav_item( array(
			'name' => __( 'Send Social Invites', 'wsi' ),
			'slug' => $bp->wsi->slug,
			'position' => 80,
			'screen_function' => array($this,'wsi_screen_one'),
			'default_subnav_slug' => 'invite-new-members',
			'show_for_displayed_user' => $this->access_test()
		) );

	}
	/**
	 * Bp access test check wheter to show or not bp screen
	 * @Since v2.1
	 * @returns bool
	 */
	function access_test() {

		if ( !is_user_logged_in() )
			return false;

		// The site admin can see all
		if ( current_user_can( 'bp_moderate' ) ) {
			return true;
		}

		if ( bp_displayed_user_id() && !bp_is_my_profile() )
			return false;

		return true;

	}

	/**
	 * Bp Screen one function to load screen content
	 * @Since v2.1
	 * @returns void
	 */
	function wsi_screen_one() {

		/* Add a do action here, so your component can be extended by others. */
		do_action( 'wsi/bp/screen_one' );

		add_action( 'bp_template_content', array( $this, 'screen_one_content' ) );

		bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
	}

	/**
	 * Bp screen content
	 * @Since v2.1
	 * @returns void
	 */
	function screen_one_content(){

		$title = apply_filters('wsi/bp/title', sprintf(__('Invite your friends to join %s','wsi'), get_bloginfo('name')));

		echo Wsi_Public::widget( $title );

	}

	/**
	 * Bp Menu wsi. Modify bp admin bar to show links to Social Send Invites Screen
	 * @Since v2.1
	 * @returns void
	 */
	function add_menu(){
		global $bp, $wp_admin_bar;

		if( !empty($bp->loggedin_user->domain) && !empty($bp->wsi->slug)){

			$wp_admin_bar->add_menu( array(
				'parent' => 'my-account-friends',
				'id'     => 'my-account-friends-social-invites',
				'title'  => __( 'Send Social Invites', 'buddypress' ),
				'href'   => $bp->loggedin_user->domain . $bp->wsi->slug .'/'
			) );
			$wp_admin_bar->add_menu( array(
				'parent' => 'my-account',
				'id'     => 'my-account-social-invites',
				'title'  => __( 'Send Social Invites', 'buddypress' ),
				'href'   => $bp->loggedin_user->domain . $bp->wsi->slug .'/'
			) );
		}
	}



}