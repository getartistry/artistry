<?php
/**
 * ConverPlug Service VerticalResponse Helper.
 *
 * @package Convert Pro Addon
 * @author Brainstorm Force
 */

/**
 * Helper class for the VerticalResponse.
 *
 * @package Convert Pro Addon
 * @since 1.0.0
 */
class CPRO_VerticalResponseAPI {

    /**
     * Default credentials.
     *
     * @since 1.0.0
     * @var string $credentials
     */
    public $credentials;

    public $root_url = 'https://vrapi.verticalresponse.com/api/';

    /**
     * Constructor.
     */
    public function __construct( $credentials = array() ) {

        if ( empty ( $credentials ) ) {
            throw new \Exception( __( 'You must provide credentails for VerticalResponse integration', 'convertpro-addon' ), 1 );
        }

        $this->credentials = $credentials;
    }

    /**
     * Connect
     *
     * @return string
     * @since 1.0.0
     */
    public function connect( $data, $redirect_url ) {

        $settings = array(
            'api_key'       => $data['api_key'],
            'secret_key'       => $data['secret_key'],
            'callback'      => admin_url( '/?redirect_url=' . urlencode( $redirect_url ) ),
            'service_account' => $data['service_account'],
            'url'  => $this->root_url . 'v1/oauth/authorize?client_id=' . $data['api_key'] . '&redirect_uri=' . admin_url(),
            'redirect_url' => $redirect_url,
            'root_url' => $this->root_url,
        );

        update_option( '_cp_service_verticalresponse_credentials', $settings );

        return $settings;
    }

    /**
     * Get Lists
     *
     * @return array
     * @since 1.0.0
     */
    public function getLists() {

        $response = array( 'error' => false );

        $url = $this->root_url . 'v1/lists?type=all';
        $ip = $this->_get_ip();
        $args = array(
            'headers' => 'Authorization: Bearer ' . $this->credentials['access_token'],
            'X-Forwarded-For' => $ip,
        );

        $result = wp_remote_get( $url, $args );
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
     * @param array $list List.
     * @param array $data Posted data.
     * @return string
     * @since 1.0.0
     */
    public function subscribe( $list, $data ) {

        $response = array( 'error' => false );

        if( '-1' != $list ) {

            $url = $this->root_url . 'v1/lists/' . $list . '/contacts';
            $ip = $this->_get_ip();

            $result = wp_remote_post( $url,
                array(
                    'method' => 'POST',
                    'headers' => 'Authorization: Bearer ' . $this->credentials['access_token'],
                    'body' => $data,
                    'cookies' => array()
                )
            );
            if ( ! is_wp_error( $result ) ) {

                $response_body = wp_remote_retrieve_body( $result );
                $body_data = json_decode( $response_body );
                $response_code = wp_remote_retrieve_response_code( $result );

                if ( 201 !== $response_code && 409 !== $response_code ) {
                    $response['error'] = $result['response']['message'];
                    throw new \Exception( $result['response']['message'], 1 );
                }
            } else {
                
                $response['error'] = $result->get_error_message();
                throw new \Exception( $result->get_error_message(), 1 );
            }
        }

        return $response;
    }

    /**
     * Get User's IP
     *
     * @return string
     * @since 1.0.0
     */
    private function _get_ip() {
        $ip = '';
        $ip_list = array(
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
        );
        foreach ( $ip_list as $key ) {
            if ( ! isset( $_SERVER[ $key ] ) ) {
                continue;
            }
            $ip = esc_attr( $_SERVER[ $key ] );
            if ( ! strpos( $ip, ',' ) ) {
                $ips = explode( ',', $ip );
                foreach ( $ips as &$val ) {
                    $val = trim( $val );
                }
                $ip = end( $ips );
            }
            $ip = trim( $ip );
            break;
        }
        return $ip;
    }
}