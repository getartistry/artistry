<?php

/**
 * @package   GFP_User_Registration_Form_Display
 * @copyright 2014-2017 gravity+
 * @license   GPL-2.0+
 * @since     1.0.0
 */

/**
 * Class GFP_User_Registration_Form_Display
 *
 * Actions related to displaying the form
 *
 * @since  1.0.0
 *
 * @author Naomi C. Bush for gravity+ <support@gravityplus.pro>
 */
class GFP_User_Registration_Form_Display {

	/**
	 * GFP_User_Registration_Form_Display constructor.
	 */
	public function __construct() {

		add_action( 'wp_loaded', array( $this, 'wp_loaded' ) );

	}

	/**
	 * Replace User Registration function for prepopulating update feeds
	 *
	 * @since  1.0.0
	 *
	 * @author Naomi C. Bush for gravity+ <support@gravityplus.pro>
	 *
	 */
	function wp_loaded() {

		remove_action( 'gform_pre_render', array( 'GF_User_Registration', 'maybe_prepopulate_form' ) );

		add_filter( 'gform_pre_render', array( $this, 'gform_pre_render' ) );

	}

	/**
	 * Show forms that have both create & update feeds, instead of hiding
	 *
	 * Also pre-populate forms that have both feeds if the user is logged-in, because they are probably updating since
	 * there's no need for a logged-in user to be creating a new user
	 *
	 * @see GF_User_Registration::maybe_prepopulate_form
	 * 
	 * @since  1.0.0
	 *
	 * @author Naomi C. Bush for gravity+ <support@gravityplus.pro>
	 *
	 * @param $form
	 *
	 * @return mixed
	 */
	public function gform_pre_render( $form ) {

		/**
		 * @var GF_User_Registration $gf_user_registration
		 */

		$gf_user_registration = gf_user_registration();

		$update_feed = $gf_user_registration->get_update_feed( $form['id'] );

		$create_feed = false;

		if ( $update_feed ) {

			$feeds = $gf_user_registration->get_feeds( $form['id'] );

			foreach ( $feeds as $feed ) {

				if ( 'create' == rgars( $feed, 'meta/feedType' ) ) {

					$create_feed = true;

					break;

				}

			}

		}

		if ( $update_feed && $create_feed ) {

			if ( $user_id = get_current_user_id() ){

				$form = $gf_user_registration->prepopulate_form( $form, $update_feed, $user_id );

			}

			return $form;

		} else {

			$gf_user_registration->maybe_prepopulate_form( $form );

		}


		return $form;

	}

}