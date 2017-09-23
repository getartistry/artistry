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
 * The scripts for the plugin's settings page.
 */

(function($) {
	
	/**
	 * On DOM ready, main entry point
	 */
	$(function() {
		/**
		 * The configuration form
		 * @type {jQuery}
		 */
		var $form = $('form[data-lb-sat-settings=form]'),
		/**
		 * The checkboxes of "Actions to show"
		 * @type {jQuery}
		 */
			$actionsOptions = $form.find('[data-lb-sat-settings=action]'),
		/**
		 * The radio buttons of "Default action"
		 * @type {jQuery}
		 */
			$defaultOptions = $form.find('[data-lb-sat-settings=default]');

		// When the user checks or unchecks a checkbox of "Actions to show"
		// we want to enable or disable its corresponding radio button in
		// "Default action"
		// Note that we then trigger the call a first time
		$actionsOptions.change(function() {
			updateDefaultOptions( $defaultOptions, $actionsOptions );
		}).change();
	});

	/**
	 * Enables or disables the radio buttons of $defaultOptions
	 * based on the check value of their corresponding checkbox
	 * in $actionsOptions. So, in other words, when the user
	 * unchecks an action in "Actions to show" (the action is not
	 * available), the user cannot select its corresponding radio
	 * button in "Default action"
	 * 
	 * @param  {jQuery} $defaultOptions The radio buttons to enable/disable
	 * @param  {jQuery} $actionsOptions The checkboxes
	 */
	function updateDefaultOptions( $defaultOptions, $actionsOptions ) {
		$actionsOptions.each(function( i, elem ) {
			var $action = $(elem),
				action = $action.data('lbSatSettingsValue'),
				$default = $defaultOptions.filter('[value="' + action + '"]');

			if( ! $action.prop('checked') && $default.prop('checked') ) {
				$defaultOptions.filter('[value="_last"]').prop('checked', true);
			}

			$default.prop('disabled', ! $action.prop('checked') );
		});
	}

})( jQuery );