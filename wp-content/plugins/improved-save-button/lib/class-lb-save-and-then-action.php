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
 * Abstract class that represents an action. All actions must
 * extend this class.
 */
abstract class LB_Save_And_Then_Action {

	/**
	 * Constructor, does nothing by default
	 */
	function __construct() {
	}

	/**
	 * Name of this action. Used in the settings page. This is
	 * not the button label (see get_button_label_pattern()).
	 * 
	 * @return string
	 */
	abstract function get_name();

	/**
	 * Unique id of this action. Id should start a namespace,
	 * followed by a dot, followed by the action name. Action
	 * id that start with an undescore are reserved.
	 * Ex: labelblanc.new
	 * 
	 * @return string
	 */
	abstract function get_id();

	/**
	 * Description of this action. Used in the the settings page.
	 * 
	 * @return string
	 */
	abstract function get_description();

	/**
	 * Returns true if this action can be executed when
	 * in the Edit page of the specified $post. Note that
	 * this is NOT weither the action was enabled in the
	 * settings page (if the action is not enabled in the
	 * settings page, it will just not create this instance).
	 *
	 * @param WP_Post $post Post object currently being edited
	 * @return boolean
	 */
	function is_enabled( $post ) {
		return true;
	}

	/**
	 * String (label) to show on the button or in the dropdown for
	 * this action. Since the label will probably be of type 'X and [action]'
	 * where X is the default Wordpress action (ex: 'Save and new'),
	 * '%s' in the label will be replaced with the default action name.
	 * Ex: 'Save and %s'. Note that you can use HTML.
	 * 
	 * @param  WP_Post $post Post currently being edited
	 * @return string
	 */
	abstract function get_button_label_pattern( $post );

	/**
	 * If wanted, an HTML title attribute can be added to the
	 * dropdown element. Return non null value to use.
	 * 
	 * @param  WP_Post $post The post currently
	 * @return string|null
	 */
	function get_button_title( $post ) {
		return null;
	}

	/**
	 * Returns the URL where to send the user when this action
	 * was used to save the post. This is the main function of an
	 * action. If this action does not redirect, return falsy value.
	 * 
	 * @param  string $current_url Current redirect URL, the Wordpress default
	 * @param  [type] $post The post that was saved
	 * @return string|null The URL where to send the user; falsy if no redirection
	 */
	function get_redirect_url( $current_url, $post ) {
		return null;
	}
}