<?php
/*!
* HybridAuth
* http://hybridauth.sourceforge.net | https://github.com/hybridauth/hybridauth
*  (c) 2009-2015 HybridAuth authors | hybridauth.sourceforge.net/licenses.html
*/

/**
 * Hybrid_Providers_Vkontakte provider adapter based on OAuth2 protocol
 *
 * added by guiltar | https://github.com/guiltar
 */

class Hybrid_Providers_Amazon extends Hybrid_Provider_Model_OAuth2
{
    // default permissions
    public $scope = "profile";


    function initialize()
    {

        parent::initialize();


        $this->api->authorize_url  = "https://www.amazon.com/ap/oa";
        $this->api->token_url      = "https://api.amazon.com/auth/o2/token";

    }


}
