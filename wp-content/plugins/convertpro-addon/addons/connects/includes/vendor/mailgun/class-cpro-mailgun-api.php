<?php
/**
 * Collects leads and subscribe to Mailgun
 *
 * @package Convert Pro Addon
 * @author Brainstorm Force
 */

/**
 * API class for the Mailgun API.
 *
 * @since 1.0.0
 */

class CPRO_Mailgun_API {

    /**
     * The Credentials array.
     *
     * @since 1.0.0
     * @var string $credentials
     */
    public $credentials;

    /**
     * The Root API URL.
     *
     * @since 1.0.0
     * @var string $api_endpoint
     */
    public $api_endpoint = 'https://api.mailgun.net/v3/';

    /**
     * Constructor.
     *
     * @since 1.0.0
     * @param array $credentials The Credentials array.
     */
    public function __construct( $credentials = null ) {

        if ( ! $credentials ) {
            throw new Exception("You must provide a Mailgun credentials", 1);
        }
        $this->credentials = $credentials;
    }

    /**
     * Connect to Mailgun API.
     *
     * @since 1.0.0
     */
    public function connect() {

        $response = array( 'error' => false );
        $data = array(
            'body'    => array(),
            'headers' => array(
                'Authorization' => 'Basic '.base64_encode("api:{$this->credentials['api_key']}"),
                'Content-Type'  => 'multipart/form-data;',
            ),
        );

        $url = $this->api_endpoint . "lists?sess=";

        $result = wp_remote_get( $url, $data );
        
        if ( ! is_wp_error( $result ) ) {
            if( isset( $result['response']['code'] ) ) {
                if( $result['response']['code'] != 200  ) {
                    // Not Connected
                    throw new Exception( $result['response']['message'] );
                }
            } else {
                // Not Connected
                throw new Exception( __( 'Something went wrong.', 'convertpro-addon'  ) );
            }
        } else {
            throw new Exception( $result->get_error_message() );
        }

        return $response;
    }

    /**
     * Get all lists via API.
     *
     * @since 1.0.0
     */
    public function getLists() {

        $response = array( 'error' => false );

        $headers = array(
            'Authorization' => 'Basic '.base64_encode("api:{$this->credentials['api_key']}"),
        );

        // Make the request.
        $args = array(
            'method'    => 'GET',
            'body'      => '',
            'headers'   => $headers,
            'sslverify' => true,
        );
        $url = $this->api_endpoint . "lists?sess=";
        $result = wp_remote_request( $url, $args );

        if ( ! is_wp_error( $result ) ) {
            if( isset( $result['response']['code'] ) ) {
                if( $result['response']['code'] != 200  ) {
                    // Not Connected
                    throw new Exception( $result['response']['message'] );
                } else {
                    $response['lists'] = json_decode( $result['body'], true );
                }
            } else {
                // Not Connected
                throw new Exception( __( 'Something went wrong.', 'convertpro-addon' ) );
            }
        } else {
            throw new Exception( $result->get_error_message() );
        }
        return $response;
    }

    /**
     * Add subscriber to list.
     *
     * @since 1.0.0
     * @param string $list List ID.
     * @param string $email Subscriber Email ID.
     * @param array $data Subscriber Data.
     */
    public function subscribe( $list, $email, $data ) {
        
        $response = array( 'error' => false );

        $headers = array(
            'Authorization' => 'Basic '.base64_encode("api:{$this->credentials['api_key']}"),
        );

        if( isset( $data['name'] ) && '' != $data['name'] ) {
            $body['name'] = $data['name'];
            unset( $data['name'] );
        }

        $body['vars'] = json_encode( $data );
        $body['sess'] = '';
        $body['time'] = time();
        $body['hash'] = sha1(date('U'));
        $body['address'] = $email;

        // Make the request.
        $args = array(
            'method'    => 'POST',
            'body'      => $body,
            'headers'   => $headers,
            'sslverify' => true,
        );
        
        $url = $this->api_endpoint . "lists/{$list}/members";
        $result = wp_remote_request( $url, $args );
        $result_body = json_decode( $result['body'], true );

        if ( ! is_wp_error( $result ) ) {
            if( isset( $result['response']['code'] ) ) {
                if( 200 != $result['response']['code'] ) {
                    // Not Connected
                    if( false !== strpos( $result_body['message'] , 'Address already exists' ) ) {
                        // Update already existing user
                        $args = array(
                            'method'    => 'PUT',
                            'body'      => $body,
                            'headers'   => $headers,
                            'sslverify' => true,
                        );
                        
                        $url = $this->api_endpoint . "lists/{$list}/members/{$email}";
                        $update = wp_remote_request( $url, $args );

                        if ( ! is_wp_error( $update ) ) {
                            if( 200 != $update['response']['code'] ) {
                                // Not Connected
                                throw new Exception( __( 'Something went wrong.', 'convertpro-addon' ) );
                            }
                        } else {
                            // Not Connected
                            throw new Exception( $update->get_error_message() );
                        }
                    } else {
                        // Not Connected
                        throw new Exception( $result['response']['message'] );
                    }
                }
            } else {
                // Not Connected
                throw new Exception( __( 'Something went wrong.', 'convertpro-addon' ) );
            }
        } else {
            throw new Exception( $result->get_error_message() );
        }
        return $response;
    }
}