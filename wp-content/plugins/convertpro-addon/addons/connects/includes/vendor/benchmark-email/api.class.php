<?php
/*
 *
 *
 */

if( !class_exists( 'CPPro_Benchmark_API_Class' ) ) {
	class CPPro_Benchmark_API_Class {
		//class variables
		private $api_url = "www1.benchmarkemail.com/api/1.0/?output=json";
		private $api_key;
		public $http_error_code = '';

		function __construct( $api_key ){
			$this->api_key = $api_key;
		}

		function listAddContacts( $contact, $listID , $optin) {
			$result['result'] = $this->execute_curl_call( 'listAddContacts', array( 'listID' => $listID, 'contacts' => $contact , 'optin' => $optin ) );
			$result['status'] = true;
			if( isset( $result['result']->error ) ){
				$this->http_error_code = $result['result']->error;
				$result['status'] = false;
			}
			return $result;
		}

		function getLists() {
			$result['result'] = $this->execute_curl_call( 'listGet' );
			$result['status'] = true;
			if( isset( $result['result']->error ) ){
				$this->http_error_code = $result['result']->error;
				$result['status'] = false;
			}
			return $result;
		}

		function execute_curl_call( $method = '', $postdata = array() ){
			if( $this->api_key == '' ) {
				$this->http_error_code = 'You must specify token parameter for the method';
			} else {
				if( $method != '' ) {
					$session = curl_init();
					$url = $this->api_url . "&method=" . $method;
					$data = array( "token" => $this->api_key );
					if( !empty( $postdata ) ) {
						$data = array_merge( $postdata, $data );
					}
					curl_setopt( $session, CURLOPT_URL, $url );
					curl_setopt( $session, CURLOPT_HTTPAUTH, CURLAUTH_BASIC );
					curl_setopt( $session, CURLOPT_HTTPGET, 1 );
					curl_setopt( $session, CURLOPT_HEADER, false );
					curl_setopt( $session, CURLOPT_RETURNTRANSFER, true );
					curl_setopt( $session, CURLOPT_VERBOSE, 1 );
					curl_setopt( $session, CURLOPT_POSTFIELDS, http_build_query( $data ) );
					curl_setopt( $session, CURLOPT_SSL_VERIFYPEER,false);
					$response = curl_exec( $session );
					curl_close( $session );
					return json_decode( $response );
				} else {
					return array();
				}
			}
			return array();
		}
	}
}