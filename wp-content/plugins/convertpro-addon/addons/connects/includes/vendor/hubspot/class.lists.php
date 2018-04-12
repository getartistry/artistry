<?php
/**
* Copyright 2013 HubSpot, Inc.
*
*   Licensed under the Apache License, Version 2.0 (the
* "License"); you may not use this file except in compliance
* with the License.
*   You may obtain a copy of the License at
*
*       http://www.apache.org/licenses/LICENSE-2.0
*
*   Unless required by applicable law or agreed to in writing,
* software distributed under the License is distributed on an
* "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND,
* either express or implied.  See the License for the specific
* language governing permissions and limitations under the
* License.
*/
require_once('class.baseclient.php');

class CPRO_HubSpot_Lists extends CPRO_HubSpot_Baseclient{
	protected $API_PATH = 'contacts';
	protected $API_VERSION = 'v1';



	
	/**
	* Get static Lists
	*
	*@param params: Array of parameters for request URL
	*				count: number of lists to return
	*				offset: offset number at which to start the list query
	*				The list results will have a 'has-more' field which will indicate 
	*				if there are more lists to be returned, as well as an 'offset' field
	*				to indicate where to start the next query if there are more results
	*		
	*
	* @return JSON objects for the requested Lists
	*
	* @throws CPRO_HubSpot_Exception
	**/
	public function get_static_lists($params){
		$endpoint = 'lists/static';
		try{
			return json_decode($this->execute_get_request($this->get_request_url($endpoint,$params)));
		}
		catch(CPRO_HubSpot_Exception $e){
			print_r('Unable to get lists: '.$e);
		}
	}

	/**
	* Add Contacts to static List
	*
	*@param vids: Unassociated array of vids for contacts to add to list
	*		id: ID of list to add contacts to
	*		
	*
	* @return Resonse body from HTTP POST request
	*
	* @throws CPRO_HubSpot_Exception
	**/
	public function add_contacts_to_list($vids,$id){
		$endpoint = 'lists/'.$id.'/add';
		$request_body = array('vids'=>$vids);
		try{
			return $this->execute_JSON_post_request($this->get_request_url($endpoint,null),json_encode($request_body));
		}
		catch(CPRO_HubSpot_Exception $e){
			print_r("Unable to add contacts: ".$e);
		}
	}


}

?>