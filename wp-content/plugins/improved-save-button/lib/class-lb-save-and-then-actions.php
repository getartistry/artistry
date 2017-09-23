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
 * Static class to manage the actions.
 */
class LB_Save_And_Then_Actions {

	/**
	 * Special id of the 'use last' action
	 */
	const ACTION_LAST = '_last';

	/**
	 * Array of loaded actions
	 * @var array
	 */
	static protected $actions = array();

	/**
	 * Called on plugin setup. Loads all the actions.
	 */
	static function setup() {
		// Priority set to 9 to be sure it executes before the setting page
		// creates the actions list (which has priority 10)
		add_action( 'admin_init', array( get_called_class(), 'load_actions' ), 9 );
	}

	/**
	 * Applies a filter to load all the actions.
	 * New actions register by hooking on the lbsat_load_actions filter
	 * and adding an instance of themselves in the supplied array.
	 */
	static function load_actions() {
		self::$actions = apply_filters( 'lbsat_load_actions', self::$actions );
	}

	/**
	 * Returns an array of all loaded actions. Note that this method
	 * returns all actions, it is NOT to be used to get
	 * only enabled actions (in the settings page).
	 * 
	 * @return array
	 */
	static function get_actions() {
		return self::$actions;
	}

	/**
	 * Returns true if an action with the specified id is loaded.
	 * Else returns false.
	 * 
	 * @param  string $action_id
	 * @return boolean
	 */
	static function action_exists( $action_id ) {
		return ! is_null( self::get_action( $action_id ) );
	}

	/**
	 * Returns the action with the specified id. If the action
	 * does not exist (invalid id), null is returned.
	 * 
	 * @param  string $action_id
	 * @return LB_Save_And_Then_Action|null
	 */
	static function get_action( $action_id ) {
		foreach ( self::$actions as $action ) {
			if ( $action->get_id() == $action_id ) {
				return $action;
			}
		}

		return null;
	}
}