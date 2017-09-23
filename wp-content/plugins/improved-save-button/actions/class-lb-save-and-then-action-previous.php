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
 * 'Save and previous' action: after saving the post, redirects to the
 * edit screen of the previous post (same post type). If no previous post
 * exists (note that the action will be disabled, but just in case), does
 * nothing (the default Wordpress redirect).
 */
class LB_Save_And_Then_Action_Previous extends LB_Save_And_Then_Action {

	/**
	 * @see LB_Save_And_Then_Action
	 */
	function get_name() {
		return _x('Save and Previous', 'Action name (used in settings page)', 'improved-save-button');
	}

	/**
	 * @see LB_Save_And_Then_Action
	 */
	function get_id() {
		return 'labelblanc.previous';
	}

	/**
	 * @see LB_Save_And_Then_Action
	 */
	function get_description() {
		return _x('Shows the <strong>previous post</strong> edit form after save.', 'Action description (used in settings page)', 'improved-save-button');
	}

	/**
	 * @see LB_Save_And_Then_Action
	 */
	function get_button_label_pattern( $post ) {
		return _x('%s and Previous', 'Button label (used in post edit page). %s = "Publish" or "Update"', 'improved-save-button');
	}

	/**
	 * Returns true only if there is a previous post. Else
	 * returns false.
	 * 
	 * @see LB_Save_And_Then_Action
	 * @param  WP_Post $post
	 * @return boolean
	 */
	function is_enabled( $post ) {
		return !! LB_Save_And_Then_Utils::get_adjacent_post( $post, 'previous' );
	}

	/**
	 * Returns the HTML title attribute for this action that says
	 * the name of the previous post (if there is one), else a message
	 * indicating why the action is disabled.
	 *
	 * @see LB_Save_And_Then_Action
	 * @param  WP_Post $post
	 * @return string
	 */
	function get_button_title( $post ) {
		if( ! $this->is_enabled( $post ) ) {
			return _x('You are at the first post.', 'Button title attribute (used in post edit page)', 'improved-save-button');
		} else {
			$previous_post = LB_Save_And_Then_Utils::get_adjacent_post( $post, 'previous' );
			return sprintf( _x('The previous post is "%s".', 'Button title attribute (used in post edit page). %s = other post name', 'improved-save-button'), $previous_post->post_title );
		}
	}

	/**
	 * Returns the URL of the previous post's Edit screen. If there
	 * is not a previous post, returns null.
	 *
	 * @see LB_Save_And_Then_Action
	 * @param  string $current_url
	 * @param  WP_Post $post
	 * @return string|null
	 */
	function get_redirect_url( $current_url, $post ) {
		$previous_post = LB_Save_And_Then_Utils::get_adjacent_post( $post, 'previous' );

		// Should not happen, but just to be sure
		if( ! $previous_post ) {
			return $current_url;
		}

		$url_parts = LB_Save_And_Then_Utils::parse_url( $current_url );
		$params = $url_parts['query'];

		// Query params to add
		$params['post'] = $previous_post->ID;
		$params['action'] = 'edit';
		$params[ LB_Save_And_Then_Messages::HTTP_PARAM_UPDATED_POST_ID ] = $post->ID;

		// Standard query params that are kept:
		// - message

		return LB_Save_And_Then_Utils::admin_url( 'post.php', $params );
	}
}