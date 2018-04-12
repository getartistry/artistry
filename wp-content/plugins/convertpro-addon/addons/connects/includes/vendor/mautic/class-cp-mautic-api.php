<?php
/**
 * ConverPlug Service Mautic Helper.
 *
 * @package Convert Pro Addon
 * @author Brainstorm Force
 */

/**
 * Helper class for the Mautic.
 *
 * @package Convert Pro Addon
 * @since 1.0.0
 */
class CPRO_Mautic_API {

    /**
     * Default credentials.
     *
     * @since 1.0.0
     * @var string $credentials
     */
    public $credentials;

    /**
     * Constructor.
     */
    public function __construct( $credentials = array() ) {

        if ( empty ( $credentials ) ) {
            throw new \Exception( __( 'You must provide credentails for Mautic integration', 'convertpro-addon' ), 1 );
        }

        $this->credentials = $credentials;
    }

    /**
     * Get User's IP
     *
     * @return array
     * @since 1.0.0
     */
    public function getSegments() {
        $response = array( 'error' => false );

        // If token expired, get new access token.
        if ( $this->credentials['expires_in'] < time() ) {
            $new_token = $this->get_new_token();
        }
        $url = $this->credentials['baseUrl'] . 'api/segments/' . '?access_token=' . $this->credentials['access_token'] . '&limit=10000';

        $result = wp_remote_get( $url );
        if ( ! is_wp_error( $result ) ) {
            $response_body = wp_remote_retrieve_body( $result );
            $body_data = json_decode( $response_body );
            $response_code = wp_remote_retrieve_response_code( $result );

            if ( 201 !== $response_code && 200 !== $response_code ) {
                $response['error'] = $result['response']['message'];
                throw new \Exception( $result['response']['message'], 1 );
            }
            $body_data = ( array ) $body_data;

            if( isset( $body_data['errors'] ) ) {
                $response['error'] = $body_data['errors'][0]['message'];
                throw new \Exception( $body_data['errors'][0]['message'], 1 );
            } else if( isset( $body_data['error'] ) ) {
                $response['error'] = $body_data['error'];
                throw new \Exception( $body_data['error'], 1 );
            }
            return $body_data;
        }
        return $response;
    }

    /**
     * Get User's IP
     *
     * @param array $segments Segments.
     * @param string $email Email ID.
     * @param array $data Posted data.
     * @param string $ip Current IP of user.
     * @return string
     * @since 1.0.0
     */
    public function subscribe( $tags, $segments, $email, $data, $ip ) {

        $access_token = $this->credentials['access_token'];

        $url = $this->credentials['baseUrl'] . "api/contacts/new";

        $body = array(
            "access_token" => $access_token,
            "email" => $email,
            "tags" => $tags,
        );

        $contact_details = $data;            

        $body = array_merge( $data, $body );

        $result = wp_remote_post( $url, array(
            'method' => 'POST',
            'timeout' => 45,
            'redirection' => 5,
            'httpversion' => '1.0',
            'blocking' => true,
            'headers' => array(
                'X-Forwarded-For' => $ip,
            ),
            'body' => $body,
            'cookies' => array()
            )
        );

        $contact_created = json_decode( $result['body'] );
        if( isset( $contact_created->contact ) ) {
            $contact = $contact_created->contact;
            if( isset($contact->id) ) {
                $contact_id =  (int) $contact->id;
                if( ! empty( $segments ) ) {
                    foreach ( $segments as $key => $seg ) {
                        $res = $this->add_to_segment( (int) $seg, $contact_id, $ip );
                    }
                }
            }
        }
        return $result;
    }

    /**
     * Add a contact to given segment
     *
     * @param int $segment_id Segment ID.
     * @param int $contact_id Contact ID.
     * @param string $ip Current IP of user.
     * @return string
     * @since 1.0.0
     */
    public function add_to_segment( $segment_id, $contact_id, $ip ) {

        $response = array();

        if( is_int( $segment_id ) && is_int ( $contact_id ) ) {

            $url = $this->credentials['baseUrl'] . "api/segments/" . $segment_id . "/contact/add/" . $contact_id;
            $access_token = $this->credentials['access_token'];

            $body = array(  
                "access_token" => $access_token,
                "ipAddress" => $_SERVER['REMOTE_ADDR']
            );

            $response = wp_remote_post( $url, array(
                'method' => 'POST',
                'timeout' => 45,
                'redirection' => 5,
                'httpversion' => '1.0',
                'blocking' => true,
                'headers' => array(
                    'X-Forwarded-For' => $ip,
                ),
                'body' => $body,
                'cookies' => array()
                )
            );
        }

        return $response;
    }

    /**
     * Get User's IP
     *
     * @return string
     * @since 1.0.0
     */
    public function connect( $data, $redirect_url ) {

        $settings = array(
            'baseUrl'       => trailingslashit( $data['base_url'] ),
            'version'       => 'OAuth2',
            'clientKey'     => $data['public_key'],
            'clientSecret'  => $data['secret_key'],
            'callback'      => admin_url( '/?action=convertpro-mautic&redirect_url=' . urlencode( $redirect_url ) ),
            'response_type' => 'code',
            'service_account' => $data['service_account'],
        );

        update_option( '_cp_service_mautic_credentials', $settings );

        $authurl = $settings['baseUrl'] . 'oauth/v2/authorize';
        // OAuth 2.0.
        $authurl .= '?client_id=' . $settings['clientKey'] . '&redirect_uri=' . urlencode( $settings['callback'] );
        $state    = md5( time() . mt_rand() );
        $authurl .= '&state=' . $state;
        $authurl .= '&response_type=' . $settings['response_type'];

        $authurl .= '&response_type=' . $settings['response_type'];

        $response = array(
            'base_url'      => trailingslashit( $data['base_url'] ),
            'public_key'    => $data['public_key'],
            'secret_key'    => $data['secret_key'],
            'redirect_url'  => $authurl
        );

        return $response;
    }

    /**
     * Get Access token
     *
     * @return string
     * @since 1.0.0
     */
    public function get_new_token() {

        $response = array( 'error' => false, 'data' => '' );

        $result = $this->get_access_token( 'refresh_token' );

        if ( is_wp_error( $result ) ) {
            $response['error'] = sprintf( __( 'There appears to be an error with the configuration. - %s', 'convertpro-addon' ), $result->get_error_message() );
        } else {
            $response_body = wp_remote_retrieve_body( $result );
            $access_details = json_decode( $response_body );
            if( ! isset( $access_details->error ) ) {
                $expiration                         = time() + $access_details->expires_in;
                $this->credentials['access_token']  = $access_details->access_token;
                $this->credentials['expires_in']    = $expiration;
                $this->credentials['refresh_token'] = $access_details->refresh_token;
                $response['data'] = $this->credentials;
            } else {
                $response['error'] = $access_details->error;
            }
        }

        $this->update_credentials();

        return $response;
    }

    /**
     * Update credentials to database
     *
     * @return void
     * @since 1.0.0
     */
    public function update_credentials() {
        if ( $this->credentials['service_account'] != '' ) {
            $term = get_term_by( 'slug', $this->credentials['service_account'], CP_CONNECTION_TAXONOMY );
            if ( isset( $term->term_id ) ) {
                $meta = get_term_meta( $term->term_id, CP_API_CONNECTION_SERVICE_AUTH );
                update_term_meta( $term->term_id, CP_API_CONNECTION_SERVICE_AUTH, $this->credentials );
            }
        }
    }

    /**
     * Get Access token as per grant type
     *
     * @param string $grant_type Type of grant.
     * @return array $response New updated token values.
     * @since 1.0.0
     */
    public function get_access_token( $grant_type ) {

        if ( ! isset( $this->credentials['baseUrl'] ) ) {
            return false;
        }
        
        $url = $this->credentials['baseUrl'] . 'oauth/v2/token';

        $body = array(  
                "client_id" => $this->credentials['clientKey'],
                "client_secret" => $this->credentials['clientSecret'],
                "grant_type" => $grant_type,
                "redirect_uri" => $this->credentials['callback'],
                'sslverify' => false
            );
        if( $grant_type == 'authorization_code' ) {
            $body["code"] = $this->credentials['access_code'];
        } else {
            $body["refresh_token"] = $this->credentials['refresh_token'];
        }

        // Request to get access token.
        $response = wp_remote_post( $url, array(
            'method' => 'POST',
            'timeout' => 45,
            'redirection' => 5,
            'httpversion' => '1.0',
            'blocking' => true,
            'headers' => array(),
            'body' => $body,
            'cookies' => array()
            )   
        );

        return $response;
    }
}