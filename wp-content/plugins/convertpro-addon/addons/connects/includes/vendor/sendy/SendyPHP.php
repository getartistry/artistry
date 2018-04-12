<?php
/**
 * Sendy Class
 */

if(!class_exists('CPRO_SendyPHP')){
	class CPRO_SendyPHP {
	
		//Class variables
		private $installation_url;
		private $api_key;
		private $list_id;
	
		
		/*
		 * Function Name: __construct
		 * Function Description: Constructor
		 */
		
		public function __construct(array $config) {
			//error checking
			$list_id = @$config['list_id'];
			$installation_url = @$config['installation_url'];
			$api_key = @$config['api_key'];
			
			if (empty($installation_url)) {
				throw new \Exception("Required config parameter [installation_url] is not set or empty", 1);
			}
			
			if (empty($api_key)) {
				throw new \Exception("Required config parameter [api_key] is not set or empty", 1);
			}
	
			$this->list_id = $list_id;
			$this->installation_url = $installation_url;
			$this->api_key = $api_key;
		} // __construct ends
		
		
		/*
		 * Function Name: connect_sendy
		 * Function Description: Connect to Sendy Account
		 */
		
		public function connect_sendy(){
	
			$url = $this->installation_url;
			$api_key = $this->api_key;
			$list_id = $this->list_id;
			$postdata = http_build_query( array( 'api_key' => $api_key, 'list_id' => $list_id ) );
			$ch = curl_init ($url . '/api/subscribers/active-subscriber-count.php');
			curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/x-www-form-urlencoded"));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
			$result = curl_exec($ch);
			curl_close($ch);
			$result = json_decode( $result );
			return $result;
		} // connect_sendy ends
		
		
		/*
		 * Function Name: subscribe_sendy
		 * Function Description: Subscribe a user
		 */
		
		public function subscribe_sendy( $name, $customfields = array() , $email = '' ) {
			if( $email != '' ){
				$postarr = array_merge( $customfields, array(
						'api_key' => $this->api_key,
						'email'   => $email,
						'name'    => $name,
						'list'    => $this->list_id,
						'boolean' => 'true'
						) );
				$postdata = http_build_query( $postarr );
				
				$ch = curl_init ( $this->installation_url . '/subscribe' );
				curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/x-www-form-urlencoded"));
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
				$result = curl_exec($ch);
				
				if( $result == 1 ) {
					$status = 1;
				} else {
					$status = 0;
				}
				return array(
							'status' => $status,
							'message' => $result
						);
				
			} else {
				return array(
							'status' => 0,
							'message' => 'Email field is empty.'
						);
			}
		}//subscribe_sendy ends
		
	} //class ends
}//if ends