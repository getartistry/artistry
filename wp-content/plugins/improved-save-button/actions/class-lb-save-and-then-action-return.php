<?php

/**
 * Copyright 2017 Label Blanc (http://www.labelblanc.ca/)
 *
 * This file is part of the "Improved Save Button"
 * Wordpress plugin.
 *
 * The "Improved Save Button" Wordpress plugin
 * is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * 'Save and Return' action: after saving the post,
 * returns to the referer page, no mater which page it was.
 */
class LB_Save_And_Then_Action_Return extends LB_Save_And_Then_Action {

	/**
	 * Name of the cookie that will contain the URL of the 
	 * referer page.
	 */
	const COOKIE_REFERER_URL = 'lbsat_return_referer';

	/**
	 * Constructor, adds a Wordpress hook to 'current_screen' action.
	 */
	function __construct() {
		parent::__construct();
		add_action('current_screen', array( $this, 'save_referer' ) );
	}

	/**
	 * If we are in a post edit page (so this action could be
	 * called), we save in a cookie the referer. If this
	 * action is used, we will redirect to this saved URL.
	 * 
	 * @param  WP_Screen $wp_screen WP_Screen returned by the current_screen action
	 */
	function save_referer( $wp_screen ) {
		if( $wp_screen->base == 'post' ) {
			// Only execute this function in GET
			if( $_SERVER['REQUEST_METHOD'] != 'GET' ) {
				return;
			}

			$referer_url = "";

			if( isset( $_SERVER['HTTP_REFERER'] ) ) {
				$referer_url = $_SERVER['HTTP_REFERER'];
			}

			// If the referer is also the same as this post 
			// edit screen, we don't save its URL. This will
			// allow to use the regular "Update" button at
			// least once without losing where we were before
			// editing.
			$is_same_url = false;
			if( isset( $_GET['post'] ) ) {
				$is_same_url = LB_Save_And_Then_Utils::url_is_post_edit( $referer_url, $_GET['post'] );
			}

			if( ! $is_same_url ) {
				setcookie( self::COOKIE_REFERER_URL, $referer_url );
			}
		}
	}
	
	/**
	 * @see LB_Save_And_Then_Action
	 */
	function get_name() {
		return _x('Save and Return', 'Action name (used in settings page)', 'improved-save-button');
	}
	
	/**
	 * @see LB_Save_And_Then_Action
	 */
	function get_id() {
		return 'labelblanc.return';
	}
	
	/**
	 * @see LB_Save_And_Then_Action
	 */
	function get_description() {
		return _x('Returns to the <strong>previous page</strong> (no matter which page) after save.', 'Action description (used in settings page)', 'improved-save-button');
	}
	
	/**
	 * @see LB_Save_And_Then_Action
	 */
	function get_button_label_pattern( $post ) {
		return _x('%s and Return', 'Button label (used in post edit page). %s = "Publish" or "Update"', 'improved-save-button');
	}

	/**
	 * Returns the URL of the page we were before editing
	 * the post. If, for any reason, we cannot determine
	 * the referer page, returns the $current_url.
	 *
	 * @see LB_Save_And_Then_Action
	 * @param  string $current_url
	 * @param  WP_Post $post
	 * @return string
	 */
	function get_redirect_url( $current_url, $post ) {
		$url = $current_url;
		$url_in_cookie = isset( $_COOKIE[ self::COOKIE_REFERER_URL ] );

		if( ! $url_in_cookie ) {
			return $url;
		}

		$cookie_url = trim( $_COOKIE[ self::COOKIE_REFERER_URL ] );

		if( empty( $cookie_url ) ) {
			return $url;
		}

		$url_parts = LB_Save_And_Then_Utils::parse_url( $current_url );
		$url = $cookie_url;

		// If the URL is a post edit page, we add the
		// parameters to show the "post updated" message
		if( LB_Save_And_Then_Utils::url_is_post_edit( $url ) ) {
			$url = add_query_arg( array(
				LB_Save_And_Then_Messages::HTTP_PARAM_UPDATED_POST_ID => $post->ID,
				'message' => isset( $url_parts['query']['message'] ) ? $url_parts['query']['message'] : ''
			), $url );
		}

		return $url;
	}
}