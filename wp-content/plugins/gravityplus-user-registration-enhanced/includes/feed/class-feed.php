<?php

/**
 * @package   GFP_User_Registration_Feed
 * @copyright 2014-2017 gravity+
 * @license   GPL-2.0+
 * @since     1.0.0
 */

/**
 * Class GFP_User_Registration_Feed
 *
 * Modify URAO Feed
 *
 * @since 1.0.0
 *        
 * @author Naomi C. Bush for gravity+ <support@gravityplus.pro>
 */
class GFP_User_Registration_Feed {

	/**
	 * GFP_User_Registration_Feed constructor.
	 */
	public function __construct () {
		
		add_filter( 'gform_userregistration_feed_settings_fields', array( $this, 'gform_userregistration_feed_settings_fields' ), 20, 2 );
	
	}

	/**
	 * Allow all feed type choices
	 * 
	 * @since 1.1.0
	 *        
	 * @author Naomi C. Bush for gravity+ <support@gravityplus.pro>
	 *
	 * @param $fields
	 * @param $form
	 *
	 * @return mixed
	 */
	public function gform_userregistration_feed_settings_fields( $fields, $form ) {

		foreach( $fields['feed_settings']['fields'] as $key => $field ) {

			if ( 'feedType' == $field['name'] ) {

				$fields['feed_settings']['fields'][$key]['choices'] = gf_user_registration()->get_feed_actions();

			}
			
		}

		return $fields;
	}

}