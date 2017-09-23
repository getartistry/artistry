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

if( ! class_exists( 'LB_Save_And_Then_Post_Edit' ) ) {

/**
 * Management of the "edit post" and "new post" admin pages.
 */

class LB_Save_And_Then_Post_Edit {

	/**
	 * URL parameter defining the action to do after saving.
	 */
	const HTTP_PARAM_ACTION = 'lb-sat-action';

	/**
	 * Main entry point. Setups all the Wordpress hooks.
	 */
	static function setup() {
		add_action( 'admin_enqueue_scripts', array( get_called_class(), 'add_admin_scripts' ) );
		add_action( 'post_submitbox_start', array( get_called_class(), 'post_submitbox_start' ) );
	}

	/**
	 * Adds JavaScript and CSS files on the "edit post" or "new post"
	 * page.
	 * 
	 * @param string  $page_id  Page id where we are.
	 */
	static function add_admin_scripts( $page_id ) {

		if( $page_id != 'post.php' && $page_id != 'post-new.php' ) {
			return;
		}

		$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
	
		// Adds post-edit.js	
		wp_enqueue_script(
			'lb-save-and-then-post-edit',
			LB_Save_And_Then_Utils::plugins_url( "js/post-edit{$min}.js" ),
			array('jquery', 'utils'),
			'1.0.1',
			true
		);

		// If Wordpress version < 4.2, we include the backward-compatibility
		// script.
		$wp_version = get_bloginfo('version');

		if( version_compare( $wp_version, '4.2', '<' ) ) {
			wp_enqueue_script(
				'lb-save-and-then-post-edit-pre-4.2',
				LB_Save_And_Then_Utils::plugins_url( "js/backward-compatibility/post-edit.pre-4.2{$min}.js" ),
				array('lb-save-and-then-post-edit'),
				'1.0',
				true
			);
		}

		// Adds post-edit.css
		wp_enqueue_style(
			'lb-save-and-then-post-edit',
			LB_Save_And_Then_Utils::plugins_url( 'css/post-edit.css' ),
			array(),
			'1.0'
		);

		// Adds rtl for post-edit.css
		if( function_exists('wp_style_add_data') ) {
			wp_style_add_data( 'lb-save-and-then-post-edit', 'rtl', 'replace' );
		}
	}


	/**
	 * Adds JavaScript and some HTML to the 'post submit box' in the
	 * edit page.
	 *
	 * Mainly outputs the JavaScript object containing all the enabled
	 * actions and some settings set in Wordpress. Also create
	 * a hidden input containing the referer (used when doing the
	 * redirection).
	 */
	static function post_submitbox_start() {

		$options = LB_Save_And_Then_Settings::get_options();
		$enabled_actions = LB_Save_And_Then_Settings::get_enabled_actions();
		$current_post = get_post();

		// If the user didn't enable any action, we quit here
		if( ! count( $enabled_actions ) )
			return;

		/**
		 * The JavaScript object that will be serialized in
		 * window.LabelBlanc.SaveAndThen.
		 * 
		 * @var array
		 */
		$js_object = array(
			'setAsDefault' => $options['set-as-default'],
			'actions' => array(),
			'defaultActionId' => $options['default-action'],
		);

		// We add to $js_object all the actions and some data
		// about them.
		foreach ( $enabled_actions as $action ) {
			$new_js_action = array(
				'id' => $action->get_id(),
				'buttonLabelPattern' => $action->get_button_label_pattern( $current_post ),
				'enabled' => $action->is_enabled( $current_post ),
			);

			// If the action has a title attribute to add
			if( $button_title = $action->get_button_title( $current_post ) ) {
				$new_js_action['title'] = $button_title;
			}

			$js_object['actions'][] = $new_js_action;
		}

		// Output of the JavaScript object
		echo '<script type="text/javascript">';
		echo 'window.LabelBlanc = window.LabelBlanc || {};';
		echo 'window.LabelBlanc.SaveAndThen = window.LabelBlanc.SaveAndThen || {};';
		echo 'window.LabelBlanc.SaveAndThen.ACTION_LAST_ID = "' . LB_Save_And_Then_Actions::ACTION_LAST . '";';
		echo 'window.LabelBlanc.SaveAndThen.HTTP_PARAM_ACTION = "' . self::HTTP_PARAM_ACTION . '";';
		echo 'window.LabelBlanc.SaveAndThen.config = ' . json_encode( $js_object );
		echo '</script>';
	}
} // end class

} // end if( class_exists() )