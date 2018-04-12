<?php
if( ! class_exists( 'CPRO_Klaviyo' ) ) {
    class CPRO_Klaviyo {

        /**
         * The Base URL for the API.
         *
         * @since 1.0.0
         * @var string $root
         */
        public $root = 'https://a.klaviyo.com/api/v1/';

        /**
         * The API Key.
         *
         * @since 1.0.0
         * @var string $apikey
         */
        public $apikey = null;

        /**
         * Constructor.
         *
         * @since 1.0.0
         * @var string $apikey
         */
        public function __construct( $apikey = null ) {
            
            if ( ! $apikey ) {
                throw new \Exception( "You must provide a Klaviyo API Key" , 1 );
            }

            $this->apikey = $apikey;

        }

        /**
         * Make a connection call to Klaviyo.
         *
         * @since 1.0.0
         * @return array {
         *      @type bool|string $error The error message or false if no error.
         * }
         */
        public function connect() {

            $response = array( 'error' => false );

            $opts = array(
                'body' => array(
                    'api_key' => $this->apikey
                )
            );

            $resp = wp_remote_get( $this->root . 'lists', $opts );

            if( ! is_wp_error( $resp ) ) {

                $body = wp_remote_retrieve_body( $resp );
                $request = json_decode( $body );

                if( isset( $resp['response']['code'] ) ) {
                    if( $resp['response']['code'] != 200  ) {
                        // Not Connected
                        throw new \Exception( $request->message , 1 );
                    }
                } else {
                    // Not Connected
                    throw new \Exception( __( 'Something went wrong.', 'convertpro-addon' ) , 1 );
                }

            } else {
                // Not Connected
                throw new \Exception( $resp->get_error_message() , 1 );
            }

            return $response;
        }

        /**
         * Get lists.
         *
         * @since 1.0.0
         * @return array {
         *      @type bool|string $error The error message or false if no error.
         * }
         */
        public function getList() {

            $response = array( 'error' => false );

            $opts = array(
                'body' => array(
                    'api_key' => $this->apikey
                )
            );

            $resp = wp_remote_get( $this->root . 'lists', $opts );

            if( ! is_wp_error( $resp ) ) {
                $body = wp_remote_retrieve_body( $resp );
                
                $request = json_decode( $body );
                
                if( isset( $resp['response']['code'] ) ) {
                    if( $resp['response']['code'] != 200  ) {
                        // Not Connected
                        throw new \Exception( $request->message , 1 );
                    } else {
                        $response['lists'] = $request->data;
                    }
                } else {
                    // Not Connected
                    throw new \Exception( __( 'Something went wrong.', 'convertpro-addon' ) , 1 );
                }

            } else {
                // Not Connected
                throw new \Exception( $resp->get_error_message() , 1 );
            }

            return $response;
        }

        /**
         * Subscribe an email address to Klaviyo.
         *
         * @since 1.0.0
         * @param int $list The list ID.
         * @param string $email The email to subscribe.
         * @param string $data The other data.
         * @return array {
         *      @type bool|string $error The error message or false if no error.
         * }
         */
        public function subscribe( $list, $email, $data ) {

            if( empty( $data['properties'] ) ) {
                unset( $data['properties'] );
            } else {
                $data['properties'] = json_encode( $data['properties'] );
            }

            $data['api_key'] = $this->apikey;

            $opts = array(
                'body' => $data,
            );

            $returnArray = array( 'error' => false );

            $req_url = $this->root . 'list/' . $list . '/members';
            $result = wp_remote_post( $req_url, $opts );

            $response_arr = json_decode( $result['body'] );

            if( isset( $result['response'] ) ) {
                $code = isset( $result['response']['code'] ) ? $result['response']['code'] : false;
                if ( isset($code) && $code !== 200 && $code !== 400 ) {
                    // skip user already subscribed case
                    $returnArray['error'] = isset( $response_arr->detail ) ? $response_arr->detail : '';
                    throw new \Exception( $returnArray['error'] , 1 );
                }
            }
            
            return $returnArray;
        }

    }
}
