<?php
require_once(DUPLICATOR_PRO_PLUGIN_PATH.'/classes/class.constants.php');
require_once(DUPLICATOR_PRO_PLUGIN_PATH.'/classes/class.crypt.custom.php');

/**
 * @copyright 2016 Snap Creek LLC
 */
abstract class DUP_PRO_License_Activation_Response
{
    const OK               = 0;
    const POST_ERROR       = -1;
    const INVALID_RESPONSE = -2;

}

abstract class DUP_PRO_License_Type
{
    const Unlicensed   = 0;
    const Personal     = 1;
    const Freelancer   = 2;
    const BusinessGold = 3;

}

class DUP_PRO_License_U
{
    // Pseudo constants
    public static $licenseCacheTime;

    public static function init()
    {
        $hours                  = 336; // 14 days
        self::$licenseCacheTime = $hours * 3600;
    }

    public static function changeLicenseActivation($activate)
    {
        $license = get_option(DUP_PRO_Constants::LICENSE_KEY_OPTION_NAME, '');

        if ($activate) {
            $api_params = array(
                'edd_action' => 'activate_license',
                'license' => $license,
                'item_name' => urlencode(EDD_DUPPRO_ITEM_NAME), // the name of our product in EDD,
                'url' => home_url()
            );
        } else {
            $api_params = array(
                'edd_action' => 'deactivate_license',
                'license' => $license,
                'item_name' => urlencode(EDD_DUPPRO_ITEM_NAME), // the name of our product in EDD,
                'url' => home_url()
            );
        }

        // Call the custom API.
        global $wp_version;

        $agent_string = "WordPress/".$wp_version;

        DUP_PRO_LOG::trace("Wordpress agent string $agent_string");

        $response = wp_remote_post(EDD_DUPPRO_STORE_URL,
            array('timeout' => 15, 'sslverify' => false, 'user-agent' => $agent_string,
            'body' => $api_params));

        // make sure the response came back okay
        if (is_wp_error($response)) {
            if ($activate) {
                $action = 'activating';
            } else {
                $action = 'deactivating';
            }

            DUP_PRO_LOG::traceObject("Error $action $license", $response);

            return DUP_PRO_License_Activation_Response::POST_ERROR;
        }

        $license_data = json_decode(wp_remote_retrieve_body($response));

        if ($activate) {
            // decode the license data
            if ($license_data->license == 'valid') {
                DUP_PRO_LOG::trace("Activated license $license");

                return DUP_PRO_License_Activation_Response::OK;
            } else {
                DUP_PRO_LOG::traceObject("Problem activating license $license", $license_data);
                return DUP_PRO_License_Activation_Response::INVALID_RESPONSE;
            }
        } else {
            // check that license:deactivated and item:Duplicator Pro json
            if ($license_data->license == 'deactivated') {
                DUP_PRO_LOG::trace("Deactivated license $license");
                return DUP_PRO_License_Activation_Response::OK;
            } else {
                // problems activating
                //update_option('edd_sample_license_status', $license_data->license);
                DUP_PRO_LOG::traceObject("Problems deactivating license $license", $license_data);
                return DUP_PRO_License_Activation_Response::INVALID_RESPONSE;
            }
        }
    }

    public static function isValidOvrKey($scrambledKey)
    {
        $isValid        = false;
        $unscrambledKey = DUP_PRO_Crypt::unscramble($scrambledKey);

        if (DUP_PRO_STR::startsWith($unscrambledKey, 'SCOVRK')) {
            $index = strpos($unscrambledKey, '_');

            if ($index !== false) {
                $index++;
                $count = substr($unscrambledKey, $index);

                if (is_numeric($count) && ($count > 0)) {
                    $isValid = true;
                }
            }
        }

        return $isValid;
    }

    public static function setOvrKey($scrambledKey)
    {
        if (self::isValidOvrKey($scrambledKey)) {
            $unscrambledKey = DUP_PRO_Crypt::unscramble($scrambledKey);

            $index = strpos($unscrambledKey, '_');

            if ($index !== false) {
                $index++;
                $count = substr($unscrambledKey, $index);

                /* @var $global DUP_PRO_Global_Entity */
                $global = DUP_PRO_Global_Entity::get_instance();

                $global->license_limit               = $count;
                $global->license_no_activations_left = false;
                $global->license_status              = DUP_PRO_License_Status::Valid;

                $global->save();

                DUP_PRO_LOG::trace("$unscrambledKey is an ovr key with license limit $count");

                update_option(DUP_PRO_Constants::LICENSE_KEY_OPTION_NAME, $scrambledKey);
            }
        } else {
            throw new Exception("Ovr key in wrong format: $unscrambledKey");
        }
    }

    public static function getStandardKeyFromOvrKey($scrambledKey)
    {
        $standardKey = '';
        
        if (self::isValidOvrKey($scrambledKey)) {
            $unscrambledKey = DUP_PRO_Crypt::unscramble($scrambledKey);

            $standardKey = substr($unscrambledKey, 6, 32);

        } else {
            throw new Exception("Ovr key in wrong format: $unscrambledKey");
        }

        return $standardKey;
    }

    public static function getLicenseStatus($forceRefresh)
    {
        /* @var $global DUP_PRO_Global_Entity */
        $global = DUP_PRO_Global_Entity::get_instance();

        $license_key = get_option(DUP_PRO_Constants::LICENSE_KEY_OPTION_NAME, '');

        if (self::isValidOvrKey($license_key)) {
            if ($global->license_status != DUP_PRO_License_Status::Valid) {
                $global->license_status = DUP_PRO_License_Status::Valid;
                $global->save();
            }
        } else {
            $initial_status = $global->license_status;

            if ($forceRefresh === false) {
                if (time() > $global->license_expiration_time) {
                    DUP_PRO_LOG::trace("Uncaching license because current time = ".time()." and expiration time = {$global->license_expiration_time}");
                    $global->license_status = DUP_PRO_License_Status::Uncached;
                }
            } else {
                DUP_PRO_LOG::trace("forcing live license update");
                $global->license_status = DUP_PRO_License_Status::Uncached;
            }

            if ($global->license_limit == -1) {
                $global->license_status = DUP_PRO_License_Status::Uncached;
            }

            if ($global->license_status == DUP_PRO_License_Status::Uncached) {
                DUP_PRO_LOG::trace("retrieving live license status");
                $store_url = 'https://snapcreek.com';
                $item_name = 'Duplicator Pro';


                if ($license_key != '') {
                    $api_params = array(
                        'edd_action' => 'check_license',
                        'license' => $license_key,
                        'item_name' => urlencode($item_name),
                        'url' => home_url()
                    );

                    global $wp_version;
                    $agent_string = "WordPress/".$wp_version;

                    $response = wp_remote_post($store_url,
                        array('timeout' => 15, 'sslverify' => false, 'user-agent' => $agent_string,
                        'body' => $api_params));

                    if (is_wp_error($response)) {
                        $global->license_status = $initial_status;
                        DUP_PRO_LOG::trace("Error getting license check response for $license_key so leaving status alone");
                    } else {
                        $license_data = json_decode(wp_remote_retrieve_body($response));

                        DUP_PRO_LOG::traceObject("license data in response returned", $response);
                        DUP_PRO_LOG::traceObject("license data returned", $license_data);

                        $global->license_status = self::getLicenseStatusFromString($license_data->license);

                        $global->license_no_activations_left = false;
                        $global->license_limit               = $license_data->license_limit;

                        if (($global->license_status == DUP_PRO_License_Status::Site_Inactive) && ($license_data->activations_left === 0)) {
                            $global->license_no_activations_left = true;
                        }

                        if ($global->license_status == DUP_PRO_License_Status::Unknown) {
                            DUP_PRO_LOG::trace("Problem retrieving license status for $license_key");
                        }
                    }
                } else {
                    $global->license_limit               = -1;
                    $global->license_status              = DUP_PRO_License_Status::Invalid;
                    $global->license_no_activations_left = false;
                }

                $global->license_expiration_time = time() + self::$licenseCacheTime;

                $global->save();

                DUP_PRO_LOG::trace("Set cached value from with expiration ".self::$licenseCacheTime." seconds from now ({$global->license_expiration_time})");
            }
        }

        return $global->license_status;
    }

    public static function getLicenseStatusString($licenseStatusString)
    {
        switch ($licenseStatusString) {
            case DUP_PRO_License_Status::Valid:
                return DUP_PRO_U::__('Valid');

            case DUP_PRO_License_Status::Invalid:
                return DUP_PRO_U::__('Invalid');

            case DUP_PRO_License_Status::Expired:
                return DUP_PRO_U::__('Expired');

            case DUP_PRO_License_Status::Disabled:
                return DUP_PRO_U::__('Disabled');

            case DUP_PRO_License_Status::Site_Inactive:
                return DUP_PRO_U::__('Site Inactive');

            case DUP_PRO_License_Status::Expired:
                return DUP_PRO_U::__('Expired');

            default:
                return DUP_PRO_U::__('Unknown');
        }
    }

    public static function getLicenseType()
    {
        /* @var $global DUP_PRO_Global_Entity */
        $global = DUP_PRO_Global_Entity::get_instance();

        $license_type = DUP_PRO_License_Type::Personal;

        if ($global->license_limit < 0) {
            $license_type = DUP_PRO_License_Type::Unlicensed;
        } else if ($global->license_limit < 15) {
            $license_type = DUP_PRO_License_Type::Personal;
        } else if ($global->license_limit < 500) {
            $license_type = DUP_PRO_License_Type::Freelancer;
        } else if ($global->license_limit >= 500) {
            $license_type = DUP_PRO_License_Type::BusinessGold;
        }

        return $license_type;
    }

    private static function getLicenseStatusFromString($licenseStatusString)
    {
        switch ($licenseStatusString) {
            case 'valid':
                return DUP_PRO_License_Status::Valid;
                break;

            case 'invalid':
                return DUP_PRO_License_Status::Invalid;

            case 'expired':
                return DUP_PRO_License_Status::Expired;

            case 'disabled':
                return DUP_PRO_License_Status::Disabled;

            case 'site_inactive':
                return DUP_PRO_License_Status::Site_Inactive;

            case 'expired':
                return DUP_PRO_License_Status::Expired;

            default:
                return DUP_PRO_License_Status::Unknown;
        }
    }
}
DUP_PRO_License_U::init();
