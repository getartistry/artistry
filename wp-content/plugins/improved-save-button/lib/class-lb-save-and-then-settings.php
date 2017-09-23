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

if( ! class_exists( 'LB_Save_And_Then_Settings' ) ) {

/**
 * Manages the settings page and settings utilities.
 */

class LB_Save_And_Then_Settings {

	/**
	 * Constants used in defining settings names, settings page and
	 * settings menu.
	 */
	const OPTION_GROUP = 'lb-save-and-then';
	const MAIN_SETTING_NAME = 'lb-save-and-then-options';
	const MENU_SLUG = 'save-and-then';
	/**
	 * Version of settings this plugin's version uses. Note that
	 * it is independent of the plugin's version number, since
	 * multiple versions of the plugin may use the same settings
	 * format.
	 */
	const SETTINGS_VERSION = '1.1';

	static protected $cached_options;
	static protected $cached_default_options;

	/**
	 * Main entry point. Setups all the Wordpress hooks.
	 */
	static function setup() {
		add_action( 'plugins_loaded', array( get_called_class(), 'check_settings_version' ) );
		add_action( 'admin_init', array( get_called_class(), 'setup_settings' ) );
		add_action( 'admin_init', array( get_called_class(), 'setup_settings_fields' ) );
		add_action( 'admin_enqueue_scripts', array( get_called_class(), 'add_admin_scripts' ) );
		add_action( 'admin_menu', array( get_called_class(), 'create_administration_menu' ) );
		$plugin = LB_Save_And_Then_Utils::plugin_main_file_basename();
		add_filter("plugin_action_links_$plugin", array( get_called_class(), 'plugin_settings_link' ) );
	}

	/**
	 * Adds JavaScript files required on the settings page. Only add them
	 * on the plugin's settings page.
	 * 
	 * @param string  $page_id  Id of the page currently shown
	 */
	static function add_admin_scripts( $page_id ) {
		if( $page_id != 'settings_page_' . self::MENU_SLUG ) {
			return;
		}

		$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		
		wp_enqueue_script(
			'lb-save-and-then-settings-page',
			LB_Save_And_Then_Utils::plugins_url( "js/settings-page{$min}.js" ),
			array('jquery'),
			'1.0',
			true
		);
	}

	/**
	 * Register in Wordpress the settings where we will save the options.
	 */
	static function setup_settings() {
		register_setting(
			self::OPTION_GROUP,
			self::MAIN_SETTING_NAME,
			array( get_called_class(), 'validate_settings' )
		);
	}

	/**
	 * Defines the settings sections and fields of the settings page.
	 */
	static function setup_settings_fields() {
		$setting_section_name = 'lb-save-and-then-settings-section';

		add_settings_section(
			$setting_section_name,
			null, // No section title
			null, // We don't want to show any particular content
			self::MENU_SLUG
		);

		add_settings_field(
			'lb-save-and-then-set-as-default',
			_x('Display button as default', 'Used in settings page', 'improved-save-button'),
			array( get_called_class(), 'create_setting_field' ),
			self::MENU_SLUG,
			$setting_section_name,
			array( 'option_name' => 'set-as-default' )
		);

		add_settings_field(
			'lb-save-and-then-actions',
			_x('Actions to show', 'Used in settings page', 'improved-save-button'),
			array( get_called_class(), 'create_setting_field' ),
			self::MENU_SLUG,
			$setting_section_name,
			array( 'option_name' => 'actions' )
		);

		add_settings_field(
			'lb-save-and-then-default-action',
			_x('Default action', 'Used in settings page', 'improved-save-button'),
			array( get_called_class(), 'create_setting_field' ),
			self::MENU_SLUG,
			$setting_section_name,
			array( 'option_name' => 'default-action' )
		);
	}

	/**
	 * Adds a menu item in the settings menu to the plugin's
	 * settings page.
	 */
	static function create_administration_menu() {
		add_options_page(
			sprintf( _x('%s Settings', 'Settings page <title>. %s = plugin name', 'improved-save-button'), LB_Save_And_Then::get_localized_name() ),
			__('Improved Save Button', 'improved-save-button'),
			'manage_options',
			self::MENU_SLUG,
			array( get_called_class(), 'create_options_page' )
		);
	}

	/**
	 * Outputs HTML of the settings page
	 */
	static function create_options_page() {
		if ( ! current_user_can( 'manage_options' ) )  {
			wp_die( _x( 'You do not have sufficient permissions to access this page.', 'Shown when trying to access the settings page without proper permissions.', 'improved-save-button' ) );
		}
		?>
		<div class="wrap">
		<h1><?php printf( _x('<em>%s</em> Settings', 'Settings page main title. %s = plugin name', 'improved-save-button'), LB_Save_And_Then::get_localized_name() ); ?></h1>
		<form method="post" action="options.php" data-lb-sat-settings="form">
			<?php settings_fields( self::OPTION_GROUP ); ?>
			<?php do_settings_sections( self::MENU_SLUG ); ?>
			<input type="submit" value="<?php echo esc_attr( _x('Save Changes', 'Settings page\'s save button', 'improved-save-button')); ?>"class="button button-primary" />
		</form>
		</div>
		<?php
	}

	/**
	 * Based on the field, outputs its HTML. This method can generate the HTML
	 * of each field used in the settings page.
	 * 
	 * @param  array  $args  Arguments passed as last parameter in add_settings_field
	 */
	static function create_setting_field( $args ) {
		// The values of all the settings
		$options = self::get_options();
		$actions = LB_Save_And_Then_Actions::get_actions();
		$option_field_name = self::MAIN_SETTING_NAME . '[' . $args['option_name'] . ']';
		// The setting value for this field
		$option_value = $options[ $args['option_name'] ];

		$html = '';

		switch ( $args['option_name'] ) {

			case 'set-as-default':
				$html .= '<fieldset><label><input type="checkbox" name="' . $option_field_name. '" value="1"' . checked( 1, $option_value, false ) . '/>';
				$html .= '<span>' . _x('Display the new save button as the default one.', 'Used in settings page', 'improved-save-button') . '</span></label></fieldset>';
				break;

			case 'actions':
				$html .= '<fieldset>';

				foreach ( $actions as $action_index => $action ) {
					$action_id = $action->get_id();

					$html .= '<label><input type="checkbox" name="' . $option_field_name . '['. $action_id .']" value="1" data-lb-sat-settings="action" data-lb-sat-settings-value="'. $action_id .'" ' . checked( 1, $option_value[ $action_id ], false ) . '/>';
					$html .= '<span>' . $action->get_name() . '</span>';

					if( $action->get_description() ) {
						$html .= ' <span class="description"> — ' . $action->get_description() . '</span>';
					}

					$html .= '</label>';

					if( $action_index != count( $actions ) - 1 ) {
						$html .= '<br />';
					}
				}

				$html .= '</fieldset>';
				break;

			case 'default-action':
				$html .= '<fieldset>';

				$action_index = -1;

				do {

					// Special case : we show the "use last" action as first element
					if ( -1 == $action_index ) {

						$action_id = LB_Save_And_Then_Actions::ACTION_LAST;
						$action_name = '<em>' . _x('Last used', '"Last used" action name (used in settings page)', 'improved-save-button') . '</em>';
						$action_description = _x('The last action that was used.', '"Last used" action description (used in settings page)', 'improved-save-button');

					} else {

						$html .= '<br />';

						$action = $actions[ $action_index ];
						$action_id = $action->get_id();
						$action_name = $action->get_name();
						$action_description = '';
					}

					$html .= '<label><input type="radio" name="' . $option_field_name . '" value="'. $action_id .'" data-lb-sat-settings="default"' . checked( $action_id, $option_value, false ) . '/>';
					
					$html .= '<span>' . $action_name . '</span>';

					if( $action_description ) {
						$html .= ' <span class="description"> — ' . $action_description . '</span>';
					}

					$html .= '</label>';

					$action_index++;

				} while( $action_index < count( $actions ) );

				$html .= '</fieldset>';
				break;
		}

		echo $html;
	}

	/**
	 * Creates the "Settings" link in the plugins page.
	 */
	static function plugin_settings_link( $links ) {
		$settings_link = '<a href="options-general.php?page=' . self::MENU_SLUG . '">' . _x('Settings', 'Settings link for this plugin, in the plugins listing page.', 'improved-save-button') . '</a>'; 
		array_unshift( $links, $settings_link ); 
		return $links; 
	}

	/**
	 * Analyses the arguments received from the request, builds
	 * a new 'clean' settings array and returns it.
	 * If a setting is missing, it will be set with a logical value
	 * (which may be different than the ones provided by
	 * self::get_default_options()).
	 * 
	 * @param  array  $input  Parameters received in the request
	 * @return array          Cleaned settings array
	 */
	static function validate_settings( $input ) {
		$actions = LB_Save_And_Then_Actions::get_actions();

		if( ! $input )
			$input = array();

		$sanitized_input = self::sanitize_options( $input );

		// set-as-default
		if ( ! isset( $sanitized_input['set-as-default'] ) ) {
			$sanitized_input['set-as-default'] = false;
		}

		/*
		 * If an action is missing, we set it as disabled
		 */
		if ( ! isset( $sanitized_input['actions'] ) ) {
			$sanitized_input['actions'] = array();
		}

		foreach ( $actions as $action ) {
			if( ! array_key_exists( $action->get_id(), $sanitized_input['actions'] ) ) {
				$sanitized_input['actions'][ $action->get_id() ] = false;
			}
		}

		/*
		 * Determine the default action.
		 * - If none is set, we use the 'use last' action
		 * - If one is set and it is disabled, we change it to the 'use last' action
		 */
		if ( ! isset( $sanitized_input['default-action'] ) ) {
			$sanitized_input['default-action'] = LB_Save_And_Then_Actions::ACTION_LAST;
		}
	
		if ( $sanitized_input['default-action'] != LB_Save_And_Then_Actions::ACTION_LAST ) {
			// If the default-action is a disabled action, we change it to the default value
			if ( true != $sanitized_input['actions'][ $sanitized_input['default-action'] ] ) {
				$sanitized_input['default-action'] = LB_Save_And_Then_Actions::ACTION_LAST;
			}
		}

		return $sanitized_input;
	}

	/**
	 * Returns the default options values. Used when getting the
	 * settings. Not used when saving the settings.
	 * 
	 * @return array Associative array of options
	 */
	static function get_default_options() {
		if ( ! isset( self::$cached_default_options ) ) {
			$defaults = array(
				'set-as-default' => true,
				'actions' => array(),
				'default-action' => '' // Set below
			);

			// By default, all the available actions are enabled by default
			$actions = LB_Save_And_Then_Actions::get_actions();

			foreach ( $actions as $action ) {
				$defaults['actions'][ $action->get_id() ] = true;
			}

			// The default action is the '_last' one.
			$defaults['default-action'] = LB_Save_And_Then_Actions::ACTION_LAST;

			self::$cached_default_options = $defaults;
		}

		return self::$cached_default_options;
	}

	/**
	 * Returns an array of all the option values saved in the database,
	 * where non-defined options are set with the defaults provided
	 * by self::get_default_options()
	 * 
	 * @return array Associative array of options
	 */
	static function get_options() {
		if ( ! isset( self::$cached_options ) ) {
			$options = get_option( self::MAIN_SETTING_NAME );

			if( ! $options )
				$options = array();

			// Sanitizing any invalid value in the database
			$options = self::sanitize_options( $options );

			self::$cached_options = self::merge_options_with_default( $options );
		}

		return self::$cached_options;
	}

	/**
	 * Returns an options array with the defaults values overwritten
	 * by the ones in the supplied array.
	 * 
	 * @param  array  $options Overwrites to the defaults
	 * @return array
	 */
	static function merge_options_with_default( $options = array() ) {
		return array_replace_recursive( self::get_default_options(), $options );
	}

	/**
	 * Receives an options array and sanitize its value to ensure
	 * it has correct types and existing actions. Removes any invalid
	 * action.
	 */
	static function sanitize_options( $options = array() ) {
		// 'set-as-default' action must be boolean
		if ( isset( $options['set-as-default'] ) ) {
			$options['set-as-default'] = (bool) $options['set-as-default']; // Ensures boolean
		}

		// 'default-action' must be an existing action
		if ( isset( $options['default-action'] ) ) {
			if ( ! LB_Save_And_Then_Actions::action_exists( $options['default-action'] ) ) {
				unset( $options['default-action'] );
			}
		}

		// Each action must exist
		if ( isset( $options['actions'] ) ) {
			if ( ! is_array( $options['actions'] ) ) {
				unset( $options['actions'] );
			} else {
				foreach ( $options['actions'] as $action_id => $action_enabled ) {
					if ( ! LB_Save_And_Then_Actions::action_exists( $action_id ) ) {
						unset( $options['actions'][$action_id] );
						continue;
					}

					$options['actions'][$action_id] = (bool) $options['actions'][$action_id];
				}
			}
		}
		
		return $options;
	}

	/**
	 * Returns an associative array of all the actions enabled in the
	 * settings page. The keys are the action id and the values are the
	 * action data array as returned by LB_Save_And_Then::get_actions().
	 *
	 * @return array The enabled types
	 */
	static function get_enabled_actions() {
		$options = self::get_options();
		$active_actions = array();

		if( isset( $options['actions'] ) ) {
			foreach ( $options['actions'] as $action_id => $action_enabled ) {
				$action = LB_Save_And_Then_Actions::get_action( $action_id );
				if( ! is_null( $action ) && $action_enabled ) {
					$active_actions[ $action_id ] = $action;
				}
			}
		}

		return $active_actions;
	}

	/**
	 * Checks if the settings need an update. If so, update them.
	 * Called by the plugins_loaded hook
	 */
	static function check_settings_version() {
		if( self::do_settings_need_update() ) {
			self::update_settings();
		}
	}

	/**
	 * Returns true if the settings in the database need
	 * an update (because of plugin update) by checking the
	 * settings version number.
	 * 
	 * @return boolean
	 */
	static function do_settings_need_update() {
		$options = get_option( self::MAIN_SETTING_NAME );

		if( ! $options ) {
			return false;
		}

		$old_options_version = self::get_settings_version();

		return version_compare( $old_options_version, self::SETTINGS_VERSION ) === -1;
	}

	/**
	 * Updates the settings from an older version of the plugin
	 * to the ones used in this version.
	 */
	static function update_settings() {
		$options = get_option( self::MAIN_SETTING_NAME );

		if( ! $options ) {
			return;
		}

		$options['version'] = self::SETTINGS_VERSION;
		$v1_0_actions_id_translations = array(
			'new' => 'labelblanc.new',
			'list' => 'labelblanc.list',
			'next' => 'labelblanc.next',
			'previous' => 'labelblanc.previous',
		);

		// In v1.0, we used other action names, we update those
		if( isset( $options['actions'] ) && is_array( $options['actions'] ) ) {
			foreach ( $options['actions'] as $action_id => $action_enabled ) {
				if( array_key_exists( $action_id, $v1_0_actions_id_translations ) ) {
					$new_action_id = $v1_0_actions_id_translations[ $action_id ];
					$options['actions'][$new_action_id] = $action_enabled;
					unset( $options['actions'][$action_id] );
				}
			}
		}

		// Update of the default-action name
		if( isset( $options['default-action'] ) ) {
			if( array_key_exists( $options['default-action'], $v1_0_actions_id_translations ) ) {
				$options['default-action'] = $v1_0_actions_id_translations[ $options['default-action'] ];
			}
		}

		update_option( self::MAIN_SETTING_NAME, $options );
	}

	/**
	 * Returns the settings' format version number. Note that this number
	 * is independent of the plugin's number since multiple plugin's
	 * versions may use the same settings format. Returns false if the
	 * settings do not exist yet.
	 * 
	 * @return string|false
	 */
	static function get_settings_version() {
		$options = get_option( self::MAIN_SETTING_NAME );

		if( ! $options ) {
			return false;
		}

		return isset( $options['version'] ) ? $options['version'] : '1.0';
	}

} // end class

} // end if( class_exists() )