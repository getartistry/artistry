<?php

namespace CR\tools;

class rest
{

    public $data = false;
    public $url = "https://rest.cleverreach.com/v2";

    public $postFormat = "json";
    public $returnFormat = "json";

    public $authMode = false;
    public $authModeSettings = false;

    public $debugValues = false;

    public $checkHeader = true;
    public $throwExceptions = true;
    public $header = false;
    public $error = false;

    public function __construct($url = "https://rest.cleverreach.com/v2")
    {
        $this->url = rtrim($url, '/');
        $this->authModeSettings = new \stdClass;
        $this->debugValues = new \stdClass;

    }

    /**
     * sets AuthMode (jwt, webauth, etc)
     * @param string    jwt, webauth,none
     * @param mixed
     */
    public function setAuthMode($mode = "none", $value = false)
    {
        switch ($mode) {
            case 'jwt':
                $this->authMode = "jwt";
                $this->authModeSettings->token = $value;
                break;

            case 'bearer':
                $this->authMode = "bearer";
                $this->authModeSettings->token = $value;
                break;

            case 'webauth':
                $this->authMode = "webauth";
                $this->authModeSettings->login = $value->login;
                $this->authModeSettings->password = $value->password;

                break;

            default:
                # code...
                break;
        }
        return $this->authModeSettings;
    }

    ################################################################################################

    /**
     * makes a GET call
     * @param  array
     * @param  string   get/put/delete
     * @return mixed
     */
    public function get($path, $data = false, $mode = "get")
    {
        $this->resetDebug();
        if (is_string($data)) {
            if (!$data = json_decode($data)) {
                throw new \Exception("data is string but no JSON");
            }
        }

        // $url = sprintf($this->url . $path, ($data ? http_build_query($data) : ""));
        // $this->debug("url", $url);

        $curl = curl_init();
        $this->setupCurl($curl);

        curl_setopt( $curl, CURLOPT_URL, $this->url . $path );

        switch ($mode) {
            case 'delete':
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, strtoupper($mode));
                $this->debug("mode", strtoupper($mode));
                break;

            default:
                $this->debug("mode", "GET");
                break;
        }

        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt( $curl, CURLOPT_SSL_VERIFYHOST, FALSE );
        curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, FALSE );
        curl_setopt( $curl, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)');

        $curl_response = curl_exec($curl);
        $headers = curl_getinfo($curl);
        $err = curl_errno($curl);

        curl_close($curl);

        $this->debugEndTimer();

        return json_decode( $curl_response );

    }

    /**
     * makes a DELETE call
     * @param  array
     * @return mixed
     */
    public function delete($path, $data = false)
    {
        return $this->get($path, $data, "delete");
    }

    /**
     * makes a put call
     * @param  array
     * @return mixed
     */
    public function put($path, $data = false)
    {
        return $this->post($path, $data, "put");
    }

    /**
     * does POST
     * @param  [type]
     * @return [type]
     */
    public function post($path, $data, $mode = "post")
    {
        $this->resetDebug();
        $this->debug("url", $this->url . $path);
        if (is_string($data)) {
            if (!$data = json_decode($data)) {
                throw new \Exception("data is string but no JSON");
            }
        }
        $curl_post_data = $data;

        $curl = curl_init();


        $this->setupCurl($curl);
        curl_setopt( $curl, CURLOPT_URL, $this->url . $path );
        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, TRUE );
        curl_setopt( $curl, CURLOPT_SSL_VERIFYHOST, FALSE );
        curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, FALSE );

        switch ($mode) {
            case 'put':
                curl_setopt( $curl, CURLOPT_CUSTOMREQUEST, "PUT" );
                break;

            default:
                curl_setopt( $curl, CURLOPT_POST, TRUE );
                break;
        }
        curl_setopt( $curl, CURLOPT_HEADER, FALSE );
        curl_setopt( $curl, CURLOPT_FOLLOWLOCATION, TRUE );

        $this->debug("mode", strtoupper($mode));

        if ( $this->postFormat == "json" ) {
            $curl_post_data = json_encode($curl_post_data);
        }

        curl_setopt( $curl, CURLOPT_POSTFIELDS, $curl_post_data );
        curl_setopt( $curl, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)');

        // Execute curl
        $curl_response = curl_exec($curl);
        $headers = curl_getinfo($curl);
        $err = curl_errno($curl);
        curl_close($curl);

        $this->debugEndTimer();
        return json_decode( $curl_response );

    }

    ##########################################################################

    /**
     * [resetDebug description]
     * @return [type]
     */
    private function resetDebug()
    {
        $this->debugValues = new \stdClass;
        $this->error = false;
        $this->debugStartTimer();
    }

    /**
     * set debug keys
     * @param  string
     * @param  mixed
     * @return [type]
     */
    private function debug($key, $value)
    {
        $this->debugValues->$key = $value;
    }

    private function debugStartTimer()
    {
        $this->debugValues->time = $this->microtime_float();
    }

    private function debugEndTimer()
    {
        $this->debugValues->time = $this->microtime_float() - $this->debugValues->time;
    }

    /**
     * prepapres curl with settings amd ein object
     * @param  pointer_curl
     */
    private function setupCurl(&$curl)
    {

        $header = array();

        switch ($this->postFormat) {
            case 'json':
                $header['content'] = 'Content-Type: application/json';
                break;

            default:
                $header['content'] = 'Content-Type: application/x-www-form-urlencoded; charset=UTF-8';
                break;
        }

        switch ($this->authMode) {
            case 'webauth':
                curl_setopt($curl, CURLOPT_USERPWD, $this->authModeSettings->login . ":" . $this->authModeSettings->password);
                break;

            case 'jwt':
                $header['token'] = 'X-ACCESS-TOKEN: ' . $this->authModeSettings->token;
                // $header['token'] = 'Authorization: Bearer ' . $this->authModeSettings->token;
                break;

            case 'bearer':
                $header['token'] = 'Authorization: Bearer ' . $this->authModeSettings->token;
                break;

            default:
                # code...
                break;
        }

        $this->debugValues->header = $header;
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
    }

    public function microtime_float()
    {
        list($usec, $sec) = explode(" ", microtime());
        return ((float) $usec + (float) $sec);
    }

}
