<?php

class CPRO_Mailchimp {

    public $apikey;
    public $root  = '';
    public $debug = false;

    public function __construct( $apikey = null, $opts=array() ) {
        if ( ! $apikey ) {
            throw new \Exception("You must provide a MailChimp API key", 1);
        }

        $dash_position = strpos( $apikey, '-' );

        $this->root = 'https://' . substr( $apikey, $dash_position + 1 ) . '.api.mailchimp.com/3.0/';

        $this->apikey = $apikey;
        $dc           = "us1";

    }

    public function connect() {

        $response = array( 'error' => false );

        $opts = array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => 'apikey ' . $this->apikey
            ),
            'body' => array()
        );

        $resp = wp_remote_get( $this->root . 'lists/', $opts );

        if( ! is_wp_error( $resp ) ) {
            $body = wp_remote_retrieve_body( $resp );
            
            $request = json_decode( $body );

            if( isset( $resp['response']['code'] ) ) {
                if( $resp['response']['code'] != 200  ) {
                    // Not Connected
                    $response['error'] = $resp['response']['message'];
                }
            } else {
                // Not Connected
                $response['error'] = $request->detail;
            }

        } else {
            // Not Connected
            $response['error'] = $resp->get_error_message();
        }

        return $response;
    }

    public function getList( $listid = '' ) {
        $response = array( 'error' => true );

        $url = ( $listid == '' ) ? $this->root . 'lists/' : $this->root . 'lists/' . $listid . '/interest-categories';

        $opts = array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => 'apikey ' . $this->apikey
            ),
            'body' => array(
                'count' => '100'
            )
        );

        $resp = wp_remote_get( $url, $opts );

        if( ! is_wp_error( $resp ) ) {
            $body = wp_remote_retrieve_body( $resp );
            
            $request = json_decode( $body );

            if( isset( $resp['response']['code'] ) ) {
                if( $resp['response']['code'] == 200  ) {
                    // Connected
                    $response['error'] = false;
                    $response['lists'] = ( $listid != '' ) ? $request->categories : $request->lists;
                } else {
                    // Not Connected
                    $response['error'] = $resp['response']['message'];
                }
            } else {
                // Not Connected
                $response['error'] = $request->detail;
            }

        } else {
            // Not Connected
            $response['error'] = $resp->get_error_message();
        }

        return $response;
    }

    public function getGroups( $listid = '' ) {

        $response = array( 'error' => false );
        $url = ( $listid == '' ) ? $this->root . 'lists/' : $this->root . 'lists/' . $listid . '/interest-categories';
        $opts = array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => 'apikey ' . $this->apikey
            ),
            'body' => array()
        );

        $resp = wp_remote_get( $url, $opts );

        $body = json_decode( wp_remote_retrieve_body( $resp ) );
        $output = array();

        if ( wp_remote_retrieve_response_code( $resp ) == 200 && $body->total_items > 0 ) {
            foreach ( $body->categories as $group ) :
         
                // we can skip hidden interests
                if( $group->type == 'hidden')
                    continue;
         
                // connect to API to get interests from each category
                $result = wp_remote_get( $this->root . 'lists/' . $listid . '/interest-categories/' . $group->id . '/interests', $opts );
                $res_body = json_decode( wp_remote_retrieve_body( $result ) );
         
                if ( wp_remote_retrieve_response_code( $result ) == 200 && $res_body->total_items > 0 ) {
                    foreach( $res_body->interests as $interest ) {
                        $output[$interest->id] = $group->title . ' - ' . $interest->name;
                    }
         
                } else {
                    $response['error'] = wp_remote_retrieve_response_code( $result ) . wp_remote_retrieve_response_message( $result );
                }
         
            endforeach;
            $response['groups'] = $output;
        } else {
            $response['error'] = wp_remote_retrieve_response_code( $resp ) . wp_remote_retrieve_response_message( $resp );
        }
        return $response;
    }

    public function getSegments( $listid = '' ) {
        $response = array( 'error' => true );

        $url = ( $listid == '' ) ? $this->root . 'lists/' : $this->root . 'lists/' . $listid . '/segments';

        $opts = array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => 'apikey ' . $this->apikey
            ),
            'body' => array()
        );

        $resp = wp_remote_get( $url, $opts );

        if( ! is_wp_error( $resp ) ) {
            $body = wp_remote_retrieve_body( $resp );
            
            $request = json_decode( $body );
            if( isset( $resp['response']['code'] ) ) {
                if( $resp['response']['code'] == 200  ) {
                    // Connected
                    $response['error'] = false;
                    $response['segments'] = $request->segments;
                } else {
                    // Not Connected
                    $response['error'] = $resp['response']['message'];
                }
            } else {
                // Not Connected
                $response['error'] = $request->detail;
            }

        } else {
            // Not Connected
            $response['error'] = $resp->get_error_message();
        }

        return $response;
    }

    public function subscribe( $list, $email, $data ) {

        if( empty( $data['merge_fields'] ) ) {
            unset( $data['merge_fields'] );
        }

        $opts = array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => 'apikey ' . $this->apikey
            ),
            'body' => json_encode( $data ),
            'method' => 'PUT'
        );

        $returnArray = array( 'error' => false );

        $req_url = $this->root . 'lists/' . $list . '/members/' . md5( $email );
        $result = wp_remote_post( $req_url, $opts );
        $response_arr = json_decode($result['body']);

        if( isset( $result['response'] ) ) {
            $code = isset( $result['response']['code'] ) ? $result['response']['code'] : false;
            if ( isset($code) && $code !== 200 && $code !== 400 ) {
                // skip user already subscribed case    
                $returnArray['error'] = isset( $response_arr->detail ) ? $response_arr->detail : '';

            }
        }
        
        return $returnArray;
    }
}