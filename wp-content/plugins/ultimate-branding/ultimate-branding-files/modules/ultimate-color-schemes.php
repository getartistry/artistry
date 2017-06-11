<?php
/*
  Plugin Name: Ultimate Color Schemes
  Plugin URI: http://premium.wpmudev.org/project/ultimate-color-schemes/
  Description: Customize admin color schemes.
  Author: Incsub
  Author URI: http://premium.wpmudev.org/
  Version: 1.0.1
  TextDomain: ub
  Domain Path: /languages/
  License: GNU General Public License (Version 2 - GPLv2)
  Lead Developer: Marko Miljus

  Copyright 2007-2014 Incsub (http://incsub.com)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License (Version 2 - GPLv2) as published by
  the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */


//http://www.wpbeginner.com/wp-tutorials/how-to-set-default-admin-color-scheme-for-new-users-in-wordpress/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
if ( ! class_exists( 'Ultimate_Color_Schemes' ) ) {

	class Ultimate_Color_Schemes {

		var $version = '1.0';
		var $name = 'Ultimate Color Schemes';
		var $dir_name = 'ultimate-color-schemes';

		function WP_Constructor() {
			$this->__construct();
		}

		function __construct() {

			$this->name = __( 'Ultimate Color Schemes', 'ub' );

			//Custom header actions
			add_action( 'admin_enqueue_scripts', array( &$this, 'admin_header_actions' ) );

			add_action( 'admin_init', array( &$this, 'admin_custom_color_scheme_option' ), 0 );

			// Admin interface
			add_action( 'ultimatebranding_settings_menu_ultimate_color_schemes', array( &$this, 'manage_output' ) );

			add_filter( 'ultimatebranding_settings_menu_ultimate_color_schemes_process', array( &$this, 'process' ) );

			add_action( 'admin_head', array( &$this, 'wp_color_scheme_settings' ), 0 );

			add_filter( 'get_user_option_admin_color', array( &$this, 'force_admin_scheme_color' ), 5, 3 );

			add_action( 'user_register', array( &$this, 'set_default_admin_color' ) );

			add_action( 'wpmu_new_user', array( &$this, 'set_default_admin_color' ) );

		}

		function set_default_admin_color( $user_id ) {
			$default_color = ub_get_option( 'ucs_default_color_scheme', false );

			if ( $default_color && $default_color !== 'false' ) {
				$args = array(
					'ID' => $user_id,
					'admin_color' => $default_color,
				);
				wp_update_user( $args );
			}

		}

		function force_admin_scheme_color( $result, $option, $user ) {
			global $_wp_admin_css_colors;

			$force_color = ub_get_option( 'ucs_force_color_scheme', false );

			if ( $force_color && $force_color !== 'false' ) {
				return $force_color;
			} else {
				return $result;
			}
		}

		function wp_color_scheme_settings() {
			global $_wp_admin_css_colors;

			$screen = get_current_screen();

			if ( $screen->base == 'profile' || $screen->base == 'profile-network' ) {//remove color schemes only on Profile page
				$visible_colors = ub_get_option( 'ucs_visible_color_schemes', false );

				foreach ( $_wp_admin_css_colors as $color => $color_info ) {
					if ( $visible_colors == false ) {
						//do not remove colors
					} else {
						if ( is_array( $visible_colors ) ) {
							if ( ! in_array( $color, $visible_colors ) ) {
								unset( $_wp_admin_css_colors[ $color ] );
							}
						}
					}
				}
			}
		}

		function admin_custom_color_scheme_option() {
			if ( isset( $_GET['custom-color-scheme'] ) ) {
				$this->set_custom_color_scheme();
				exit;
			}

			/* Custom scheme */
			wp_admin_css_color( 'wpi_custom_scheme', ub_get_option( 'ucs_color_scheme_name', 'Ultimate' ), admin_url( '?custom-color-scheme', __FILE__ ), array( ub_get_option( 'ucs_admin_menu_background_color', '#45B29D' ), ub_get_option( 'ucs_admin_menu_submenu_background_color', '#334D5C' ), ub_get_option( 'ucs_admin_menu_current_background_color', '#EFC94C' ), ub_get_option( 'ucs_table_view_switch_icon_color', '#45B29D' ), ub_get_option( 'ucs_table_view_switch_icon_hover_color', '#d46f15' ), ub_get_option( 'ucs_table_alternate_row_color', '#E5ECF0' ) ) );
		}

		function set_custom_color_scheme() {
			header( 'Content-type: text/css' );
			require_once( plugin_dir_path( __FILE__ ) . '/' . $this->dir_name . '-files/custom-color-scheme.php' );
		}

		function admin_header_actions() {
			global $wp_version;

			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'wp-color-picker' );
			wp_enqueue_script( 'ucs-admin', plugins_url( '/' . $this->dir_name . '-files/js/admin.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
		}

		function process() {
			global $plugin_page;
			if ( isset( $_GET['reset'] ) && isset( $_GET['page'] ) && $_GET['page'] == 'branding' ) {
				$colors = $this->colors();
				foreach ( $colors as $color_section => $color_array ) {
					foreach ( $color_array as $property => $value ) {
						ub_update_option( $property, $color_array[ $property ]['default'] );
					}
				}
				wp_redirect( 'admin.php?page=branding&tab=ultimate-color-schemes' );
			} elseif ( isset( $_POST['ucs_color_scheme_name'] ) ) {
				foreach ( $_POST as $key => $value ) {
					if ( preg_match( '/^ucs_/', $key ) ) {
						ub_update_option( $key, $value );
					}
				}
			}

			return true;
		}

		function colors() {
			$colors = array(
				'General' => array(
					'ucs_background_color' => array(
						'title' => 'Background',
						'default' => '#f1f1f1',
						'value' => ub_get_option( 'ucs_background_color', '#f1f1f1' ),
					),
				),
				'Links' => array(
					'ucs_default_link_color' => array(
						'title' => 'Default Link',
						'default' => '#45B29D',
						'value' => ub_get_option( 'ucs_default_link_color', '#45B29D' ),
					),
					'ucs_default_link_hover_color' => array(
						'title' => 'Default Link Hover',
						'default' => '#E27A3F',
						'value' => ub_get_option( 'ucs_default_link_hover_color', '#E27A3F' ),
					),
					'ucs_delete_trash_spam_link_color' => array(
						'title' => 'Delete / Trash / Spam Link',
						'default' => '#DF5A49',
						'value' => ub_get_option( 'ucs_delete_trash_spam_link_color', '#DF5A49' ),
					),
					'ucs_delete_trash_spam_link_hover_color' => array(
						'title' => 'Delete / Trash / Spam Link Hover',
						'default' => '#DF5A49',
						'value' => ub_get_option( 'ucs_delete_trash_spam_link_hover_color', '#E27A3F' ),
					),
					'ucs_inactive_plugins_color' => array(
						'title' => 'Inactive Plugin Link',
						'default' => '#888',
						'value' => ub_get_option( 'ucs_inactive_plugins_color', '#888' ),
					),
				),
				'Forms' => array(
					'ucs_checkbox_radio_color' => array(
						'title' => 'Checkbox / Radio Button',
						'default' => '#45B29D',
						'value' => ub_get_option( 'ucs_checkbox_radio_color', '#45B29D' ),
					),
				),
				'Core UI' => array(
					'ucs_primary_button_background_color' => array(
						'title' => 'Primary Button Background',
						'default' => '#334D5C',
						'value' => ub_get_option( 'ucs_primary_button_background_color', '#334D5C' ),
					),
					'ucs_primary_button_text_color' => array(
						'title' => 'Primary Button Text',
						'default' => '#ffffff',
						'value' => ub_get_option( 'ucs_primary_button_text_color', '#ffffff' ),
					),
					'ucs_primary_button_hover_background_color' => array(
						'title' => 'Primary Button Hover Background',
						'default' => '#EFC94C',
						'value' => ub_get_option( 'ucs_primary_button_hover_background_color', '#EFC94C' ),
					),
					'ucs_primary_button_hover_text_color' => array(
						'title' => 'Primary Button Hover Text',
						'default' => '#ffffff',
						'value' => ub_get_option( 'ucs_primary_button_hover_text_color', '#ffffff' ),
					),
					'ucs_disabled_button_background_color' => array(
						'title' => 'Disabled Button Background',
						'default' => '#cccccc',
						'value' => ub_get_option( 'ucs_disabled_button_background_color', '#cccccc' ),
					),
					'ucs_disabled_button_text_color' => array(
						'title' => 'Disabled Button Text',
						'default' => '#000',
						'value' => ub_get_option( 'ucs_disabled_button_text_color', '#000' ),
					),
				),
				'List Tables' => array(
					'ucs_table_list_hover_color' => array(
						'title' => 'Pagination / Button / Icon Hover',
						'default' => '#45B29D',
						'value' => ub_get_option( 'ucs_table_list_hover_color', '#45B29D' ),
					),
					'ucs_table_view_switch_icon_color' => array(
						'title' => 'View Switch Icon',
						'default' => '#45B29D',
						'value' => ub_get_option( 'ucs_table_view_switch_icon_color', '#45B29D' ),
					),
					'ucs_table_view_switch_icon_hover_color' => array(
						'title' => 'View Switch Icon Hover',
						'default' => '#d46f15',
						'value' => ub_get_option( 'ucs_table_view_switch_icon_hover_color', '#d46f15' ),
					),
					'ucs_table_post_comment_icon_color' => array(
						'title' => 'Post Comment Count Hover',
						'default' => '#45B29D',
						'value' => ub_get_option( 'ucs_table_post_comment_icon_color', '#45B29D' ),
					),
					'ucs_table_post_comment_strong_icon_color' => array(
						'title' => 'Post Comment Count',
						'default' => '#d46f15',
						'value' => ub_get_option( 'ucs_table_post_comment_strong_icon_color', '#d46f15' ),
					),
					'ucs_table_alternate_row_color' => array(
						'title' => 'Alternate row',
						'default' => '#E5ECF0',
						'value' => ub_get_option( 'ucs_table_alternate_row_color', '#E5ECF0' ),
					),
				),
				'Admin Menu' => array(
					'ucs_admin_menu_background_color' => array(
						'title' => 'Admin Menu Background',
						'default' => '#45B29D',
						'value' => ub_get_option( 'ucs_admin_menu_background_color', '#45B29D' ),
					),
					'ucs_admin_menu_link_color' => array(
						'title' => 'Admin Menu Links',
						'default' => '#FFFFFF',
						'value' => ub_get_option( 'ucs_admin_menu_link_color', '#FFFFFF' ),
					),
					'ucs_admin_menu_link_hover_color' => array(
						'title' => 'Admin Menu Links Hover',
						'default' => '#FFFFFF',
						'value' => ub_get_option( 'ucs_admin_menu_link_hover_color', '#FFFFFF' ),
					),
					'ucs_admin_menu_link_hover_background_color' => array(
						'title' => 'Admin Menu Links Hover Background',
						'default' => '#334D5C',
						'value' => ub_get_option( 'ucs_admin_menu_link_hover_background_color', '#334D5C' ),
					),
					'ucs_admin_menu_current_link_color' => array(
						'title' => 'Admin Menu Link (Currently Selected)',
						'default' => '#FFFFFF',
						'value' => ub_get_option( 'ucs_admin_menu_current_link_color', '#FFFFFF' ),
					),
					'ucs_admin_menu_current_link_hover_color' => array(
						'title' => 'Admin Menu Link Hover (Currently Selected)',
						'default' => '#FFFFFF',
						'value' => ub_get_option( 'ucs_admin_menu_current_link_hover_color', '#FFFFFF' ),
					),
					'ucs_admin_menu_current_background_color' => array(
						'title' => 'Admin Menu Background (Currently Selected)',
						'default' => '#EFC94C',
						'value' => ub_get_option( 'ucs_admin_menu_current_background_color', '#EFC94C' ),
					),
					'ucs_admin_menu_current_icons_color' => array(
						'title' => 'Admin Menu Icons (Currently Selected)',
						'default' => '#FFF',
						'value' => ub_get_option( 'ucs_admin_menu_current_icons_color', '#FFF' ),
					),
					'ucs_admin_menu_icons_color' => array(
						'title' => 'Admin Menu Icons',
						'default' => '#FFF',
						'value' => ub_get_option( 'ucs_admin_menu_icons_color', '#FFF' ),
					),
					'ucs_admin_menu_submenu_background_color' => array(
						'title' => 'Admin Submenu',
						'default' => '#334D5C',
						'value' => ub_get_option( 'ucs_admin_menu_submenu_background_color', '#334D5C' ),
					),
					'ucs_admin_menu_submenu_link_color' => array(
						'title' => 'Admin Submenu Links',
						'default' => '#cbc5d3',
						'value' => ub_get_option( 'ucs_admin_menu_submenu_link_color', '#cbc5d3' ),
					),
					'ucs_admin_menu_submenu_link_hover_color' => array(
						'title' => 'Admin Submenu Links Hover',
						'default' => '#fff',
						'value' => ub_get_option( 'ucs_admin_menu_submenu_link_hover_color', '#fff' ),
					),
					'ucs_admin_menu_bubble_text_color' => array(
						'title' => 'Admin Bubble Text',
						'default' => '#fff',
						'value' => ub_get_option( 'ucs_admin_menu_bubble_text_color', '#fff' ),
					),
					'ucs_admin_menu_bubble_background_color' => array(
						'title' => 'Admin Bubble Background',
						'default' => '#EFC94C',
						'value' => ub_get_option( 'ucs_admin_menu_bubble_background_color', '#EFC94C' ),
					),
				),
				'Admin Bar' => array(
					'ucs_admin_bar_background_color' => array(
						'title' => 'Admin Bar Background',
						'default' => '#45B29D',
						'value' => ub_get_option( 'ucs_admin_bar_background_color', '#45B29D' ),
					),
					'ucs_admin_bar_text_color' => array(
						'title' => 'Admin Bar Text',
						'default' => '#FFF',
						'value' => ub_get_option( 'ucs_admin_bar_text_color', '#FFF' ),
					),
					'ucs_admin_bar_icon_color' => array(
						'title' => 'Admin Bar Icon',
						'default' => '#FFF',
						'value' => ub_get_option( 'ucs_admin_bar_icon_color', '#FFF' ),
					),
					'ucs_admin_bar_item_hover_background_color' => array(
						'title' => 'Admin Bar Item Hover Background',
						'default' => '#334D5C',
						'value' => ub_get_option( 'ucs_admin_bar_item_hover_background_color', '#334D5C' ),
					),
					'ucs_admin_bar_item_hover_focus_color' => array(
						'title' => 'Admin Bar Item Hover Focus Color',
						'default' => '#334D5C',
						'value' => ub_get_option( 'ucs_admin_bar_item_hover_focus_color', '#334D5C' ),
					),
					'ucs_admin_bar_item_hover_focus_background' => array(
						'title' => 'Admin Bar Item Hover Focus Background',
						'default' => '#334D5C',
						'value' => ub_get_option( 'ucs_admin_bar_item_hover_focus_background', '#334D5C' ),
					),
					'ucs_admin_bar_submenu_icon_color' => array(
						'title' => 'Admin Bar Submenu Icon and Links',
						'default' => '#ece6f6',
						'value' => ub_get_option( 'ucs_admin_bar_submenu_icon_color', '#ece6f6' ),
					),
					'ucs_admin_bar_item_hover_text_color' => array(
						'title' => 'Admin Bar Item Hover Text and Icon',
						'default' => '#FFF',
						'value' => ub_get_option( 'ucs_admin_bar_item_hover_text_color', '#FFF' ),
					),
				),
				'Media Uploader' => array(
					'ucs_admin_media_progress_bar_color' => array(
						'title' => 'Progress Bar',
						'default' => '#334D5C',
						'value' => ub_get_option( 'ucs_admin_media_progress_bar_color', '#334D5C' ),
					),
					'ucs_admin_media_selected_attachment_color' => array(
						'title' => 'Selected Attachment',
						'default' => '#334D5C',
						'value' => ub_get_option( 'ucs_admin_media_selected_attachment_color', '#334D5C' ),
					),
				),
				'Themes' => array(
					'ucs_admin_active_theme_background_color' => array(
						'title' => 'Active Theme Background',
						'default' => '#334D5C',
						'value' => ub_get_option( 'ucs_admin_active_theme_background_color', '#334D5C' ),
					),
					'ucs_admin_active_theme_actions_background_color' => array(
						'title' => 'Active Theme Actions Background',
						'default' => '#45B29D',
						'value' => ub_get_option( 'ucs_admin_active_theme_actions_background_color', '#45B29D' ),
					),
					'ucs_admin_active_theme_details_background_color' => array(
						'title' => 'Active Theme Details Background',
						'default' => '#45B29D',
						'value' => ub_get_option( 'ucs_admin_active_theme_details_background_color', '#45B29D' ),
					),
				),
				'Plugins' => array(
					'ucs_admin_active_plugin_border_color' => array(
						'title' => 'Active Plugin Border',
						'default' => '#EFC94C',
						'value' => ub_get_option( 'ucs_admin_active_plugin_border_color', '#EFC94C' ),
					),
				),
			);

			return $colors;
		}

		function manage_output() {
			global $wpdb, $current_site, $page;

			$colors = $this->colors();

			$page = $_GET['page'];

			if ( isset( $_GET['error'] ) ) {
				echo '<div id="message" class="error fade"><p>' . __( 'There was an error during the saving operation, please try again.', 'ub' ) . '</p></div>'; } elseif ( isset( $_GET['updated'] ) ) {
				echo '<div id="message" class="updated fade"><p>' . __( 'Changes saved.', 'ub' ) . '</p></div>'; }
?>
            <div class='wrap nosubsub'>
                <div class="icon32" id="icon-themes"><br /></div>

                <?php include_once( plugin_dir_path( __FILE__ ) . '/' . $this->dir_name . '-files/global-options.php' ); ?>

                <p class='description'><?php printf( __( 'Here you can customize "%s" color scheme which use can set within your <a href="%s">user profile page</a>', 'ub' ), ub_get_option( 'ucs_color_scheme_name', 'Ultimate' ), get_edit_user_link( get_current_user_id() ) ); ?></p>

                <h2><?php _e( 'Color Scheme Name', 'ub' ); ?></h2>
                <div class="postbox">
                    <div class="inside">
                        <table class="form-table">
                            <tbody>
                                <tr valign="top">
                                    <th scope="row"><label for="ucs_color_scheme_name"><?php _e( 'Name', 'ub' ); ?></label></th>
                                    <td><input type="text" value="<?php esc_attr_e( ub_get_option( 'ucs_color_scheme_name', 'Ultimate' ) ); ?>" name="ucs_color_scheme_name" /></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

<?php
foreach ( $colors as $color_section => $color_array ) {
?>
	<h2><?php echo $color_section; ?></h2>
	<div class="postbox">
		<div class="inside">
			<table class="form-table">
				<tbody>
<?php
foreach ( $color_array as $property => $value ) {
?>
					<tr valign="top">
						<th scope="row"><label for="<?php esc_attr_e( $property ); ?>"><?php esc_attr_e( $color_array[ $property ]['title'] ); ?></label></th>
						<td><input type="text" value="<?php esc_attr_e( $color_array[ $property ]['value'] ); ?>" class="ultimate-color-field" name="<?php echo esc_attr_e( $property ); ?>" /></td>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
<?php
}
				wp_nonce_field( 'ultimatebranding_settings_ultimate_color_schemes' );
?>
                <p class='description'><a href='<?php echo wp_nonce_url( 'admin.php?page=' . $page . '&amp;tab=ultimate-color-schemes&amp;reset=yes&amp;action=process', 'ultimatebranding_settings_ultimate_color_schemes' ) ?>'><?php _e( 'Reset Scheme Colors', 'ub' ) ?></a></p>
            </div>

<?php
		}
	}

}

$ultimate_color_schemes = new Ultimate_Color_Schemes();