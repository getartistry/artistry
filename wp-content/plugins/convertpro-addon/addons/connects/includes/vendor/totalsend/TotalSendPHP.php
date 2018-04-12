<?php
/**
 * ToatalSend Class
 */
define('CPTS_TOTALSEND_ACCOUNT_URL', 'https://app.totalsend.com');
if(!class_exists('CPPro_TotalSendPHP')){
	class CPPro_TotalSendPHP {
	
		//Class variables
		private $api_user_name;
		private $api_password;
		private $cpts_session;
	
		
		/*
		 * Function Name: __construct
		 * Function Description: Constructor
		 */
		
		public function __construct(array $config) {

			//error checking
			$api_user_name = @$config['api_user_name'];
			$api_password = @$config['api_password'];
			
			if (empty($api_user_name)) {
				throw new \Exception("Required config parameter [api_user_name] is not set or empty", 1);
			}
			
			if (empty($api_password)) {
				throw new \Exception("Required config parameter [api_password] is not set or empty", 1);
			}
			
			$this->api_user_name = $api_user_name;
			$this->api_password = $api_password;
		} // __construct ends
		
		/*
		 * Function Name: Get Connection response
		 * Function Description: Connect to Sendy Account
		 */

		public function getConResponse()
		{
			$connect_url = rtrim(CPTS_TOTALSEND_ACCOUNT_URL, '/') . '/api.php?';

			$url = $connect_url . sprintf(
					'Command=User.Login&Username=%s&Password=%s&ResponseFormat=JSON',
					$this->api_user_name,
					$this->api_password
				);
			
			$results = null;
			$parts = parse_url($url);
			parse_str($parts['query'], $fields);

			if(isset($fields['Command']) && $fields['Command'] == 'User.Login')
			{
				$url = $parts['scheme'].'://'.$parts['host'].$parts['path'];

				// Get cURL resource
				$curl = curl_init();

				// Set some options
				curl_setopt_array($curl, array(
					CURLOPT_RETURNTRANSFER => 1,
					CURLOPT_URL => $url,
					CURLOPT_POST => count($fields),
					CURLOPT_POSTFIELDS => http_build_query($fields),
					CURLOPT_SSL_VERIFYPEER => false,
				));

				$results =curl_exec($curl);
				// Close request to clear up some resources
				curl_close($curl);
			}
			return $results;
		}

		/*
		 * Function Name: set session ID
		 * Function Description: Connect to Sendy Account
		 */

		public function setSessionID()
		{
			$session_status = null;
			// Send the request & save response to $resp
			$results = $this->getConResponse();

			if(!$results){
				die('Error: "' . curl_error($curl) . '" - Code: ' . curl_errno($curl));
			}


			$json_results = json_decode($results, true);
			if($json_results['Success'] ===  true ) {
				$this->cpts_session = $json_results['SessionID'];
				$session_status = true;

			} else{
				$session_status = false;

			}

			return $session_status;
		}

		/*
		 * Function Name: connect_totalsend
		 * Function Description: Connect to ToatalSend Account
		 */
		
		public function cptsGetConnect()
		{
			
			$session_status = $this->setSessionID();
			$json_results = null ;
			$name_customField = null ;

			if ( $session_status == true ) {
				/*$results = $this->getConResponse();
				$json_results = json_decode($results, true);*/
				/*$totalsend_name_field = get_option('totalsend_namefield_id');
				if ( $totalsend_name_field == '' ){
					$name_customField = $this->createCustomField();
					$name_customField = json_decode($name_customField, true);
					update_option( 'totalsend_namefield_id', $name_customField['CustomFieldID'] );
				}*/
				$json_results = $this->getSubscriberLists();
				$json_results = json_decode($json_results, true);
			} else{
				$json_results = false;
			}

			return $json_results;
		}

		/*
		 * Function Name: getlist_totalsend
		 * Function Description: Connect to ToatalSend Account
		 */
		

		public function getSubscriberLists()
		{
			$connect_url = rtrim(CPTS_TOTALSEND_ACCOUNT_URL, '/') . '/api.php?';
			$url = $connect_url. sprintf('Command=Lists.Get&SessionID=%s&ResponseFormat=JSON',
											$this->cpts_session
										);
			return file_get_contents($url);

		}

		/*
		 * Function Name: getsubscribe
		 * Function Description: Connect to ToatalSend Account
		 */
		

		public function cptsSubscribe($params = array())
		{
			$ip_address = $this->get_ip_address();
			$connect_url = rtrim(CPTS_TOTALSEND_ACCOUNT_URL, '/') . '/api.php?';
			$url = $connect_url. sprintf('Command=Subscriber.Subscribe&ResponseFormat=JSON&IPAddress=%s',
											$ip_address
										);

			if (count($params)) {
				foreach ($params as $paramKey => $val) {
					if (!empty($val))
						if (!is_array($val)) {
							$url .= sprintf('&%s=%s', $paramKey, htmlentities(urlencode($val)));
						} /*else {
							foreach ($val as $valEl) {
								$url .= sprintf('&%s=%s', $paramKey . '[]', htmlentities(urlencode($valEl)));
							}
						}*/
				}
			}

			return file_get_contents($url);
		}
		function get_ip_address() {
		    $ipaddress = '';
		    $ipaddress = getenv('REMOTE_ADDR');
		    return $ipaddress;
		}

		
		public function createCustomField($ref_id)
		{

			$connect_url = rtrim(CPTS_TOTALSEND_ACCOUNT_URL, '/') . '/api.php?';
			$url = $connect_url. sprintf('SessionID=%s&Command=CustomField.Create&ResponseFormat=JSON&SubscriberListID=%s&FieldName=Name&FieldType=Single line&Visibility=Public&IsGlobal=Yes',
											$this->cpts_session,
											$ref_id
											);
			return file_get_contents($url);
		}
		public function getCustomField($ref_id)
		{

			$session_status = $this->setSessionID();
			
			if ( $session_status == true ) {
				$connect_url = rtrim(CPTS_TOTALSEND_ACCOUNT_URL, '/') . '/api.php?';
				$url = $connect_url. sprintf('SessionID=%s&Command=CustomFields.Get&OrderField=CustomFieldID&OrderType=ASC&SubscriberListID=%s&ResponseFormat=JSON',
											$this->cpts_session,
											$ref_id
											);
				return file_get_contents($url);
			} else{
				$result['CustomFields'] = false;
				json_encode( $result );
				return $result;
			}
		}


	} //class ends
}//if ends