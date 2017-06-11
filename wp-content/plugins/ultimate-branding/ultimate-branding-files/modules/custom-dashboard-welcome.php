<?php
/*
Plugin Name: Hide Dashboard Welcome
Plugin URI:
Description: Hides the dashboard welcome message
Author: Barry (Incsub), Sam Najian (Incsub)
Version: 1.2
Author URI:
Network: true

Copyright 2012 Incsub (email: admin@incsub.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

/**
 * Main class of Custom welcome message ( formerly hide dashboard welcome )
 * Class UB_Custom_Dashboard_Welcome
 */
class UB_Custom_Dashboard_Welcome{

	/**
	 * Custom welcome message
	 *
	 * @since 1.2
	 * @var mixed|void
	 */
	private $_message;

	/**
	 * Kick start the module
	 *
	 * @since 1.2
	 */
	function __construct() {
		add_filter( 'get_user_metadata', array( $this, 'ub_remove_dashboard_welcome' ) , 10, 4 );
		add_action( 'ultimatebranding_settings_menu_widgets', array( $this, 'render_settings' ) );
		add_filter( 'ultimatebranding_settings_menu_widgets_process', array( $this, 'process' ) );
		$this->_message = $this->_get_message();
		if ( ! empty( $this->_message ) ) {
			add_action( 'welcome_panel', array( $this, 'render_custom_message' ) );
		}
	}

	/**
	 * Retrieves custom message from db
	 *
	 * @since 1.2
	 * @return mixed|void
	 */
	private function _get_message() {
		return ub_get_option( 'ub_custom_welcome_message' );
	}

	/**
	 * Saves message to the db
	 *
	 * @since 1.2
	 * @param $data
	 */
	private function _save_message( $data ) {
		ub_update_option( 'ub_custom_welcome_message', $data );
	}


	/**
	 * Removes default welcome message from dashboard
	 *
	 * @param $value
	 * @param $object_id
	 * @param $meta_key
	 * @param $single
	 *
	 * @since 1.0
	 *
	 * @return bool
	 */
	function ub_remove_dashboard_welcome( $value, $object_id, $meta_key, $single ) {
		global $wp_version;
		if ( version_compare( $wp_version, '3.5', '>=' ) ) {
			remove_action( 'welcome_panel', 'wp_welcome_panel' );
			return $value;
		} else {
			if ( $meta_key == 'show_welcome_panel' ) {
				return false;
			}
		}
		return $value;
	}

	/**
	 * Renders settings page
	 *
	 * @since 1.0
	 */
	function render_settings() {
?>
        <div class="postbox">
            <h3 class="hndle" style='cursor:auto;'><span><?php _e( 'Custom Dashboard Welcome','ub' ); ?></span></h3>
            <div class="inside">
                <h2><?php _e( 'Custom message: ', 'ub' ); ?></h2>

                <?php echo wp_nonce_field( 'ub_save_custom_welcome_message', 'custom_welcome_message' ) ?>
<?php
		$args = array( 'textarea_name' => 'custom_admin_welcome_message', 'textarea_rows' => 9, 'teeny' => true );
		wp_editor( stripslashes( ub_get_option( 'ub_custom_welcome_message' ) ) , 'custom_admin_welcome_content', $args );
?>
                <p class='description'><?php _e( 'Leave empty to remove custom welcome widget', 'ub' ); ?></p>
            </div>
        </div>
<?php
	}

	/**
	 * Saves settings to db
	 *
	 * @since 1.2
	 * @return bool
	 */
	function process( $status ) {
		$this->_save_message( $_POST['custom_admin_welcome_message'] );
		return $status && true;
	}

	/**
	 * Renders custom content
	 *
	 * @since 1.2
	 */
	function render_custom_message() {
		echo  nl2br( stripslashes( $this->_message ) );
	}
}

new UB_Custom_Dashboard_Welcome();