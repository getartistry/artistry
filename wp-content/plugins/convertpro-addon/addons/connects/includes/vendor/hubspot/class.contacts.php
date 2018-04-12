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

class CPRO_HubSpot_Contacts extends CPRO_HubSpot_BaseClient{
	//Client for HubSpot Contacts API

	    //Define required client variables
	protected $API_PATH = 'contacts';
	protected $API_VERSION = 'v1';


    /**
    * Create a Contact
    *
    *@param params: array of properties and property values for new contact, email is required
    *
    * @return Response body with JSON object 
    * for created Contact from HTTP POST request
    *
    * @throws CPRO_HubSpot_Exception
    **/
    public function create_contact($params){
    	$endpoint = 'contact';
    	$properties = array();
    	foreach ($params as $key => $value) {
    		array_push($properties, array("property"=>$key,"value"=>$value));
    	}
    	$properties = json_encode(array("properties"=>$properties));
    	try{
    		return json_decode($this->execute_JSON_post_request($this->get_request_url($endpoint,null),$properties));
    	} catch (CPRO_HubSpot_Exception $e) {
    		throw new CPRO_HubSpot_Exception('Unable to create contact: ' . $e);
    	}
    }

    /**
    * Update a Contact
    *
    *@param params: array of properties and property values for contact
    *
    * @return Response body from HTTP POST request
    *
    * @throws CPRO_HubSpot_Exception
    **/
    public function update_contact($vid, $params){
    	$endpoint = 'contact/vid/'.$vid.'/profile';
    	$properties = array();
    	foreach ($params as $key => $value) {
    		array_push($properties, array("property"=>$key,"value"=>$value));
    	}
    	$properties = json_encode(array("properties"=>$properties));
    	try{
			return json_decode($this->execute_JSON_post_request($this->get_request_url($endpoint,null),$properties));
    	} catch (CPRO_HubSpot_Exception $e) {
    		throw new CPRO_HubSpot_Exception('Unable to update contact: ' . $e);
    	}
    }

}

?>