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

if( ! class_exists( 'LB_Save_And_Then_Post_Save' ) ) {

/**
 * Manages the redirection after a post save
 */

class LB_Save_And_Then_Post_Save {

	/**
	 * Main entry point. Setups all the Wordpress hooks.
	 */
	static function setup() {
		add_filter( 'redirect_post_location', array( get_called_class(), 'redirect_post_location' ), 10, 2 );
	}
	
	/**
	 * Changes the redirect URL after a post save/creation if applicable.
	 *
	 * If the redirect parameter set by this plugin is set, we determine
	 * the URL where to redirect (ex: to a new post, the next post, the
	 * posts list, ...). Called by the filter 'redirect_post_location'.
	 * 
	 * @param  string $location Current new location defined by Wordpress
	 * @param  string $post_id  Id of the saved/created post
	 * @return string           The new (or unchanged) URL where to redirect
	 */
	static function redirect_post_location( $location, $post_id ) {
		/**
		 * Set in Wordpress' wp-admin/post.php
		 * @var string
		 */
		global $action;

		// Only enabled on save or publish actions
		if( ! isset( $_POST['save'] ) && ! isset( $_POST['publish'] ) ) {
			return $location;
		}

		/**
		 * @see  Wordpress' wp-admin/post.php
		 */
		if( ! isset( $action ) || $action != 'editpost' ) {
			return $location;
		}

		// The action parameter must be set
		if( ! isset( $_POST[ LB_Save_And_Then_Post_Edit::HTTP_PARAM_ACTION ] ) ) {
			return $location;
		}

		// The LB_Save_And_Then_Action id
		$sat_action_id = trim( $_POST[ LB_Save_And_Then_Post_Edit::HTTP_PARAM_ACTION ] );
		$current_post = get_post( $post_id );

		// We get the LB_Save_And_Then_Action
		$sat_action = LB_Save_And_Then_Actions::get_action( $sat_action_id );

		if( is_null( $sat_action ) ) {
			return $location;
		}

		// We ask the Action where to redirect the user
		$new_location = $sat_action->get_redirect_url( $location, $current_post );

		// If an error was returned
		if( is_wp_error( $new_location ) ) {
			$error = $new_location;
			wp_die($error);
		}

		if( $new_location ) {
			return esc_url_raw( $new_location );
		}

		return $location;
	}
} // end class

} // end if( class_exists() )