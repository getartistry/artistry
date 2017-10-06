<?php
/**
 * @wordpress-plugin
 * Plugin Name: Gravity Forms User Registration Enhanced
 * Plugin URI: https://gravityplus.pro/gravity-forms-user-registration-enhanced
 * Description: Supercharge the User Registration Add-On
 * Version: 1.1.0
 * Author: gravity+
 * Author URI: https://gravityplus.pro
 * Text Domain: gravityplus-user-registration-enhanced
 * Domain Path: /languages
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package   GFP_User_Registration
 * @version   1.1.0
 * @author    gravity+ <support@gravityplus.pro>
 * @license   GPL-2.0+
 * @link      https://gravityplus.pro
 * @copyright 2014-2017 gravity+
 *
 * last updated: December 7, 2017
 */

define( 'GFP_USER_REGISTRATION_FILE', __FILE__ );

define( 'GFP_USER_REGISTRATION_PATH', plugin_dir_path( __FILE__ ) );

define( 'GFP_USER_REGISTRATION_URL', plugin_dir_url( __FILE__ ) );

require_once( trailingslashit( GFP_USER_REGISTRATION_PATH ) . 'includes/class-user-registration.php' );

$gfp_user_registration = new GFP_User_Registration();