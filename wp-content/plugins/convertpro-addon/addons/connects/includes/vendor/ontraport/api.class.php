<?php
/*
 *
 *
 */

if( !class_exists( 'CPRO_Ontraport_API_Class' ) ) {
	class CPRO_Ontraport_API_Class {
		//class variables
		private $api_url = "https://api.ontraport.com/cdata.php";
		private $api_key;
		private $app_id;
		public $http_error_code = '';

		function __construct( $api_key, $app_id ){
			$this->api_key = $api_key;
			$this->app_id = $app_id;
		}

		function listAddContacts( $contact, $tag, $seq ) {
			// Construct contact data in XML format
			$str = '';
			$tag = ( $tag != '-1' ) ? $tag : '';
			$seq = ( $seq != '-1' ) ? $seq : '';
			foreach( $contact as $key => $val ) {
				$arr = preg_split('/(?<=[a-z])(?=[A-Z])/x',$key);
				$str .= '<field name="' . implode( " ", $arr ) . '">' . $val . '</field>';
			}
			$data = '
			<contact>
			    <Group_Tag name="Contact Information">
			        ' . $str . '
			    </Group_Tag>
			    <Group_Tag name="Sequences and Tags">
			        <field name="Contact Tags">'.$tag.'</field>
			        <field name="Sequences">*/*'.$seq.'*/*</field>
			    </Group_Tag>
			</contact>
			';
			$data = urlencode(urlencode($data));
			// Replace the strings with your API credentials located in Admin > OfficeAutoPilot API Instructions and Key Manager
			$appid = $this->app_id;
			$key = $this->api_key;
			//Set your request type and construct the POST request
			$reqType= "add";
			$postargs = "appid=".$appid."&key=".$key."&return_id=1&reqType=".$reqType."&data=".$data;
			$request = "http://api.ontraport.com/cdata.php";
			//Start the curl session and send the data
			$session = curl_init($request);
			curl_setopt ($session, CURLOPT_POST, true);
			curl_setopt ($session, CURLOPT_POSTFIELDS, $postargs);
			curl_setopt($session, CURLOPT_HEADER, false);
			curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
			//Store the response from the API for confirmation or to process return data
			$response = curl_exec($session);
			//Close the session
			curl_close($session);
			$p = xml_parser_create();
			xml_parse_into_struct($p, $response, $vals, $index);
			xml_parser_free($p);

			if( $vals[$index['STATUS'][0]]['value'] == 'Success' ) {
				return true;
			} else {
				$obj->http_error_code = 'Failed';
				return false;
			}
		}

		function getTags() {
			$result['result'] = $this->execute_curl_call( 'pull_tag' );
			$result['status'] = true;
			if( isset( $result['result']->error ) ){
				$this->http_error_code = $result['result']->error;
				$result['status'] = false;
			}
			return $result;
		}

		function getSequences() {
			$result['result'] = $this->execute_curl_call( 'fetch_sequences', 'SEQUENCE' );
			$result['status'] = true;
			if( isset( $result['result']->error ) ){
				$this->http_error_code = $result['result']->error;
				$result['status'] = false;
			}
			return $result;
		}

		function execute_curl_call( $reqType = '', $tag = 'TAG', $postdata = array() ){

			if( $this->api_key == '' && $this->app_id == '' ) {
				$this->http_error_code = 'You must specify token parameter for the method';
			} else {
				$postargs = "appid=". $this->app_id ."&key=". $this->api_key ."&reqType=".$reqType;
				$request = "http://api.ontraport.com/cdata.php";
				$session = curl_init( $this->api_url );
				curl_setopt ($session, CURLOPT_POST, true);
				curl_setopt ($session, CURLOPT_POSTFIELDS, $postargs);
				curl_setopt($session, CURLOPT_HEADER, false);
				curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($session, CURLOPT_SSL_VERIFYPEER, false);
				$response = curl_exec($session);
				curl_close($session);
				$p = xml_parser_create();
				xml_parse_into_struct($p, $response, $vals, $index);
				xml_parser_free($p);
				$response = array();
				if( isset( $index['ERROR'] ) ) {
					$this->http_error_code = 'Authentication failed.';
				}
				if( isset( $index[$tag] ) ) {
					foreach($index[$tag] as $v) {
						if( $tag == 'TAG' ) {
							$response[] = $vals[$v]['value'];
						} else {
							$response[$vals[$v]['attributes']['ID']] = $vals[$v]['value'];
						}
					}
				} else {
					return array();
				}
				return $response;
			}
			return array();
		}
	}
}