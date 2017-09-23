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
 * 'Save and view' action: after saving the post, redirects to the post
 * page, on the frontend.
 */
class LB_Save_And_Then_Action_View extends LB_Save_And_Then_Action {

	/**
	 * @see LB_Save_And_Then_Action
	 */	
	function get_name() {
		return _x('Save and View', 'Action name (used in settings page)', 'improved-save-button');
	}

	/**
	 * @see LB_Save_And_Then_Action
	 */
	function get_id() {
		return 'labelblanc.view';
	}

	/**
	 * @see LB_Save_And_Then_Action
	 */
	function get_description() {
		return _x('Shows the <strong>post itself</strong> after save. The same window is used.', 'Action description (used in settings page)', 'improved-save-button');
	}

	/**
	 * @see LB_Save_And_Then_Action
	 */
	function get_button_label_pattern( $post ) {
		return _x('%s and View', 'Button label (used in post edit page). %s = "Publish" or "Update"', 'improved-save-button');
	}

	/**
	 * Returns a title attribute that simply informs the
	 * user the post will open in the same window.
	 * 
	 * @see LB_Save_And_Then_Action
	 * @param WP_Post $post
	 */	
	function get_button_title( $post ) {
		return _x('The post will be shown in this window.', 'Button title attribute (used in post edit page)', 'improved-save-button');
	}

	/**
	 * Returns the URL of the post's page on the frontend.
	 *
	 * @see LB_Save_And_Then_Action
	 * @param  string $current_url
	 * @param  WP_Post $post
	 * @return string
	 */
	function get_redirect_url( $current_url, $post ) {
		return get_permalink( $post->id );
	}
}