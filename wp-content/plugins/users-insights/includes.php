<?php

class USIN_Includes{
	
	public static function call(){
		
		include_once('core/schema.php');
		include_once('core/user-data.php');

		//include the library files
		include_once('core/lib/browser.php');
		include_once('core/geolocation-status.php');
		include_once('core/user-detect.php');
		include_once('core/ajax.php');

		include_once('core/functions.php');

		include_once('core/modules/license.php');
		include_once('core/modules/module.php');
		include_once('core/modules/remote-license.php');
		include_once('core/modules/module-default-options.php');
		include_once('core/modules/module-options.php');

		include_once('core/helper.php');
		include_once('core/templates.php');
		include_once('core/actions.php');
		
		if(is_admin()){
			
			include_once('core/field.php');
			include_once('core/capabilities.php');
			include_once('core/notice.php');

			//include the user list page files
			include_once('core/user-list/list-export.php');
			include_once('core/user-list/list-assets.php');
			include_once('core/user-list/list-ajax.php');
			include_once('core/user-list/list-page.php');

			//include the module options page files
			include_once('core/modules/module-page.php');
			include_once('core/modules/module-assets.php');
			include_once('core/modules/module-ajax.php');

			//include the query files
			include_once('core/query/query.php');
			include_once('core/query/user-query.php');
			include_once('core/query/coordinates-query.php');
			include_once('core/query/meta-query.php');
			
			//include the custom fields page files
			include_once('core/crm/custom-fields/custom-fields-page.php');
			include_once('core/crm/custom-fields/custom-fields-assets.php');
			include_once('core/crm/custom-fields/custom-fields-options.php');
			include_once('core/crm/custom-fields/custom-fields-ajax.php');
			include_once('core/crm/custom-fields/custom-fields.php');


			include_once('core/filters.php');
			include_once('core/field-defaults.php');
			include_once('core/options.php');
			include_once('core/user.php');
			include_once('core/user-exported.php');
			include_once('core/segments.php');
			
			include_once('core/crm/groups.php');
			include_once('core/crm/notes/notes.php');
			include_once('core/crm/notes/note.php');
			
			include_once('core/updates/plugin-updater.php');
			
			include_once('core/utils/debug.php');
			
			//include the plugin modules files
			include_once('plugin-modules/plugin-module.php');
			include_once('plugin-modules/woocommerce/woocommerce.php');
			include_once('plugin-modules/wc-subscriptions/wc-subscriptions.php');
			include_once('plugin-modules/wc-memberships/wc-memberships.php');
			include_once('plugin-modules/bbpress/bbpress.php');
			include_once('plugin-modules/buddypress/buddypress.php');
			include_once('plugin-modules/edd/edd.php');
			include_once('plugin-modules/ultimate-member/ultimate-member.php');
			include_once('plugin-modules/gravity-forms/gravity-forms.php');
			include_once('plugin-modules/learndash/learndash.php');
		}
		
		
		do_action('usin_files_loaded');
		
	}
	
}