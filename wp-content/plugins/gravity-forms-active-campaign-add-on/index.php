<?php
/*
Plugin Name: Gravity Forms Active Campaign Add-On
Version: 1.0
Plugin URI: http://softxplorers.com
Description: Integrates Gravity Forms with Active Campaign allowing form submissions to be automatically sent to your Active Campaign account.
Author: Muhammad Arif Amir
Author URI: https://www.linkedin.com/in/marifamir/

------------------------------------------------------------------------
Copyright 2017 SoftXplorers.com

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
*/

require_once plugin_dir_path( __FILE__ ) . 'includes/ActiveCampaign.class.php';

class ActiveCampaignAddon {
	
	public function __construct() {

		add_action('init', array(&$this,'_init'));
		add_filter("gform_addon_navigation", array(&$this, 'add_menu_item'));	
		add_action("gform_after_submission", array(&$this, 'ar_post_content'), 10, 2);
	}

	public function _init() {
		global $pagenow;
		if($pagenow === 'plugins.php') {
			add_action("admin_notices",array(&$this, 'is_gravity_forms_installed'), 10);
		}
	}
	public function is_gravity_forms_installed($asd = '', $echo = true) {
		global $pagenow, $page; $message = '';
		$installed = 0;
		if(!class_exists('RGForms')) {
			if(file_exists(WP_PLUGIN_DIR.'/gravityforms/gravityforms.php')) {
				$installed = 1;
				$message .= __(sprintf('%sGravity Forms is installed but not active. %sActivate Gravity Forms%s to use the %s plugin.%s', '<p>', '<strong><a href="'.wp_nonce_url(admin_url('plugins.php?action=activate&plugin=gravityforms/gravityforms.php'), 'activate-plugin_gravityforms/gravityforms.php').'">', '</a></strong>', $name,'</p>'), 'gravity-forms-salesforce');
			} else {
				$message .= <<<EOD
<p><a href="http://www.gravityforms.com" title="Gravity Forms Contact Form Plugin for WordPress"><img src="http://gravityforms.s3.amazonaws.com/banners/728x90.gif" alt="Gravity Forms Plugin for WordPress" width="728" height="90" style="border:none;" /></a></p>
		<h3><a href="http://www.gravityforms.com" target="_blank">Gravity Forms</a> is required for the Active Campaign Gform Add On</h3>
		<p>You do not have the Gravity Forms plugin installed. <a href="http://www.gravityforms.com">Get Gravity Forms</a> today.</p>
EOD;
			}

			if(!empty($message) && $echo) {
				echo '<div id="message" class="updated">'.$message.'</div>';
			}
		} else {
			return true;
		}
		return $installed;
	}

	public function add_menu_item( $menu_items ) {
	    $menu_items[] = array("name" => "active_campaign", "label" => "Active Campaign Settings", "callback" => array(&$this, 'ar_active_campaign'), "permission" => "edit_posts");
	    return $menu_items;
	}
	
	public function ar_active_campaign() {
		global $wpdb;
		$message 	= $validimage = false;
	    $settings 	= get_option("gf_activecampaign_settings");
		if(!empty($_POST["gf_activecampaign_submit"])){
			check_admin_referer("update", "gf_activecampaign_update");
			$settings = array("url" => stripslashes($_POST["gf_activecampaign_url"]), "token" => stripslashes($_POST["gf_activecampaign_api_key"]));
			update_option("gf_activecampaign_settings", $settings);
		}
		$ac = new ActiveCampaign($settings["url"], $settings["token"]);
		/* TEST API CREDENTIALS */
		if($ac){
			$message = (int)$ac->credentials_test();
			if ( $message == true) {
				$class = "updated";
				$validimage = '<img src="' . plugins_url( 'images/tick.png', __FILE__ ) . '" > ';
				$valid = true;
			} else {
				$class = "error";
				$valid = false;
				$validimage = '<img src="' . plugins_url( 'images/cross.png', __FILE__ ) . '" > ';
			}
		}
		else if(!empty($settings["url"]) || !empty($settings["token"])){
			$message = "<p>Invalid Active Campaign URL and/or API Key. Please try another combination.</p>";
			$class = "error";
			$valid = false;
			$validimage = '<img src="'.GFCommon::get_base_url().'/images/cross.png"/>';
		}
	
		
		?>		
		<style>
			.ul-square li { list-style: square!important; }
			.ol-decimal li { list-style: decimal!important; }
		</style>
		<div class="wrap">
			<h2><?php _e('Gravity Forms Active Campaign Add-on Settings'); ?></h2>
		<form method="post" action="">
			<?php wp_nonce_field("update", "gf_activecampaign_update") ?>
			<h3><?php _e("Active Campaign API Settings", "gravityformsactivecampaign") ?></h3>
			<p style="text-align: left;">
				<?php _e(sprintf("If you don't have an Active Campaign account, you can %ssign up for one here%s", "<a href='http://www.activecampaign.com/' target='_blank'>" , "</a>"), "gravityformsactivecampaign") ?>
			</p>
			<table class="form-table">
				<tr>
					<th scope="row"><label for="gf_activecampaign_url"><?php _e("Active Campaign URL", "gravityformsactivecampaign"); ?></label> </th>
					<td><input type="text" size="75" id="gf_activecampaign_url" name="gf_activecampaign_url" value="<?php echo esc_attr($settings["url"]) ?>"/> <?php echo $validimage; ?> <br/> Your Active Campaign URL, e.g. https://yourname.api-us1.com</td>
				</tr>
				<tr>
					<th scope="row"><label for="gf_activecampaign_api_key"><?php _e("API KEY", "gravityformsactivecampaign"); ?></label> </th>
					<td><input type="text" size="75" id="gf_activecampaign_api_key" name="gf_activecampaign_api_key" value="<?php echo esc_attr($settings["token"]) ?>"/> <?php echo $validimage; ?> </td>
				</tr>
				<tr>
					<td colspan="2" ><input type="submit" name="gf_activecampaign_submit" class="button-primary" value="<?php _e("Save Settings", "gravityformsactivecampaign") ?>" /></td>
				</tr>
			</table>
			
		</form>
	</div>
	<?php
	}
	
	public function ar_post_content( $entry, $form ) {

		$settings 	= get_option("gf_activecampaign_settings");	
		$ac = new ActiveCampaign($settings["url"], $settings["token"]);
		$ar_first_name = '';
		$ar_last_name = '';
		
		foreach ( $form['fields'] as $key => $value ) {
			if ( $value['inputName']=='ar_list_id' ) {
				$id = $value['id'];
				$list_id  = $entry[$id];
			}
			if ( $value['inputName']=='ar_first_name' ) {
				$id = $value['id'];
				$ar_first_name  = $entry[$id];
			}
			if ( $value['inputName']=='ar_last_name' ) {
				$id = $value['id'];
				$ar_last_name  = $entry[$id];
			}
			if ( $value['inputName']=='ar_email' ) {
				$id = $value['id'];
				$ar_email  = $entry[$id];
			}
			if ( empty($ar_first_name) || empty ($ar_last_name) ) {
				if ( !empty ( $value['inputs']) ) {
					foreach ( $value['inputs'] as $key=>$field) {
						if ( $field['name']=='ar_first_name') {
							$id = $field['id'];
							$ar_first_name  = $entry["{$id}"];
						}
						if ( $field['name']=='ar_last_name') {
							$id = $field['id'];
							$ar_last_name  = $entry["{$id}"];
						}
					}
				}
			}
		}
		
		$contact = array(
			"email" => $ar_email,
			"first_name" => $ar_first_name,
			"last_name" => $ar_last_name,
			"p[{$list_id}]" => $list_id,
			"status[{$list_id}]" => 1, // "Active" status
		);
		if($ac){
			$message = (int)$ac->credentials_test();
			if ( $message == true) {
				$contact_sync = $ac->api("contact/sync", $contact);
			}
		}			
	}
}

$ActiveCampaignAddOn = new ActiveCampaignAddon();