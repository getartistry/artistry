<?php

class CPRO_Sendlane {

    public $credentials;
    public $root  = '';
    public $debug = false;

    public function __construct( $credentials = null, $opts=array() ) {
        if ( ! $credentials ) {
            throw new \Exception( "You must provide a Sendlane API key", 1 );
        }
        $this->root = 'https://' . $credentials['domain_url'] . '.sendlane.com/api/v1/';
        $this->credentials = $credentials;
    }

    public function connect() {

        $response = array( 'error' => false );

        $resp = wp_remote_post( $this->root . 'lists?api=' . $this->credentials['api_key'] . '&hash=' . $this->credentials['hash_key'] );

        if( ! is_wp_error( $resp ) ) {
            $body = wp_remote_retrieve_body( $resp );
            
            $request = json_decode( $body );

            if( isset( $resp['response']['code'] ) ) {
                if( $resp['response']['code'] != 200  ) {
                    // Not Connected
                    throw new \Exception( $resp['response']['message'] , 1 );
                }
            } else {
                // Not Connected
                throw new \Exception( $request , 1 );
            }

        } else {
            // Not Connected
            throw new \Exception( $resp->get_error_message() , 1 );
        }

        return $response;
    }

    public function getList() {
        $response = array( 'error' => true );

        $resp = wp_remote_post( $this->root . 'lists?api=' . $this->credentials['api_key'] . '&hash=' . $this->credentials['hash_key'] );

        if( ! is_wp_error( $resp ) ) {
            $body = wp_remote_retrieve_body( $resp );
            
            $request = json_decode( $body );

            if( isset( $resp['response']['code'] ) ) {
                if( $resp['response']['code'] == 200  ) {
                    // Connected
                    if( ! isset( $request->info ) ) {
                        $response['error'] = false;
                        $response['lists'] = $request;
                    } else {
                        // No List found
                        throw new \Exception( $request->info->messages , 1 );
                    }
                } else {
                    // Not Connected
                    throw new \Exception( $resp['response']['message'] , 1 );
                }
            } else {
                // Not Connected
                throw new \Exception( $request->error->messages , 1 );
            }

        } else {
            // Not Connected
            throw new \Exception( $resp->get_error_message() , 1 );
        }

        return $response;
    }

    public function getTags() {
        $response = array( 'error' => true );

        $resp = wp_remote_post( $this->root . 'tags?api=' . $this->credentials['api_key'] . '&hash=' . $this->credentials['hash_key'] );

        if( ! is_wp_error( $resp ) ) {
            $body = wp_remote_retrieve_body( $resp );
            
            $request = json_decode( $body );

            if( isset( $resp['response']['code'] ) ) {
                if( $resp['response']['code'] == 200  ) {
                    if( ! isset( $request->info ) ) {
                        $response['error'] = false;
                        $response['tags'] = $request;
                    } else {
                        // No List found
                        throw new \Exception( $request->info->messages , 1 );
                    }
                } else {
                    // Not Connected
                    throw new \Exception( $resp['response']['message'] , 1 );
                }
            } else {
                // Not Connected
                throw new \Exception( $request->error->messages , 1 );
            }

        } else {
            // Not Connected
            throw new \Exception( $resp->get_error_message() , 1 );
        }

        return $response;
    }

    public function subscribe( $data ) {

        $opts = http_build_query( $data );
        $returnArray = array( 'error' => false );

        $result = wp_remote_post( $this->root . 'list-subscriber-add?api=' . $this->credentials['api_key'] . '&hash=' . $this->credentials['hash_key'] . '&' . $opts );

        $response_arr = json_decode($result['body']);

        if( isset( $result['response'] ) ) {
            $code = isset( $result['response']['code'] ) ? $result['response']['code'] : false;
            if ( isset($code) && $code !== 200 ) {   
                throw new \Exception( $response_arr->error->messages, 1);
            }
        }
        return $returnArray;
    }
}