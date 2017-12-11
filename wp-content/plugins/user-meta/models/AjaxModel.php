<?php
namespace UserMeta;

class AjaxModel
{

    function postInsertUser()
    {
        global $userMeta;
        $userMeta->verifyNonce();
        
        $umUserInsert = new UserInsert();
        
        return $umUserInsert->postInsertUserProcess();
    }

    /**
     * This method will call with um_login action
     */
    function postLogin()
    {
        return (new Login())->postLogin();
    }

    function postLostpassword()
    {
        return (new ResetPassword())->postLostPassword();
    }

    function ajaxValidateUniqueField()
    {
        global $userMeta;
        $userMeta->verifyNonce(false);
        
        $status = false;
        if (! isset($_REQUEST['fieldId']) or ! $_REQUEST['fieldValue'])
            return;
        
        $id = ltrim($_REQUEST['fieldId'], 'um_field_');
        $fields = $userMeta->getData('fields');
        
        if (isset($fields[$id])) {
            $fieldData = $userMeta->getFieldData($id, $fields[$id]);
            $status = $userMeta->isUserFieldAvailable($fieldData['field_name'], $_REQUEST['fieldValue']);
            
            if (! $status) {
                $msg = sprintf(__('%s already taken', $userMeta->name), $_REQUEST['fieldValue']);
                if (isset($_REQUEST['customCheck'])) {
                    echo "error";
                    die();
                }
            }
            
            $response[] = $_REQUEST['fieldId'];
            $response[] = isset($status) ? $status : true;
            $response[] = isset($msg) ? $msg : null;
            
            echo json_encode($response);
        }
        
        die();
    }

    function ajaxFileUploader()
    {
        global $userMeta;
        $userMeta->verifyNonce();
        
        // list of valid extensions, ex. array("jpeg", "xml", "bmp")
        $allowedExtensions = array(
            'jpg',
            'jpeg',
            'png',
            'gif'
        );
        // max file size in bytes
        $sizeLimit = 1 * 1024 * 1024;
        $replaceOldFile = FALSE;
        
        $allowedExtensions = apply_filters('pf_file_upload_allowed_extensions', $allowedExtensions);
        $sizeLimit = apply_filters('pf_file_upload_size_limit', $sizeLimit);
        $replaceOldFile = apply_filters('pf_file_upload_is_overwrite', $replaceOldFile);
        
        $uploader = new FileUploader($allowedExtensions, $sizeLimit);
        $result = $uploader->handleUpload($replaceOldFile);
        // to pass data through iframe you will need to encode all html tags
        echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
        die();
    }

    function ajaxShowUploadedFile()
    {
        global $userMeta;
        $userMeta->verifyNonce();
        
        if (isset($_REQUEST['showimage'])) {
            if (isset($_REQUEST['imageurl']))
                echo "<img src='{$_REQUEST['imageurl']}' />";
            die();
        }
        
        $file = new File();
        $file->ajaxUpload();
        
        die();
    }

    function ajaxWithdrawLicense()
    {
        global $userMeta;
        $userMeta->verifyNonce();
        
        $status = $userMeta->withdrawLicense();
        if (is_wp_error($status))
            echo $userMeta->showError($status);
        elseif ($status === true) {
            echo $userMeta->showMessage(__('License has been withdrawn', $userMeta->name));
            echo $userMeta->jsRedirect($userMeta->adminPageUrl('settings', false));
        } else
            echo $userMeta->showError(__('Something went wrong!', $userMeta->name));
        
        die();
    }

    function ajaxGeneratePage()
    {
        global $userMeta;
        check_admin_referer('generate_page');
        
        $pages = array(
            'login' => 'Login',
            'resetpass' => 'Reset password',
            'verify-email' => 'Email verification'
        );
        
        if (! empty($_REQUEST['page'])) {
            $page = $_REQUEST['page'];
            if (isset($pages[$page])) {
                $content = ('login' == $page) ? '[user-meta-login]' : '';
                $pageID = wp_insert_post(array(
                    'post_title' => $pages[$page],
                    'post_content' => $content,
                    'post_status' => 'publish',
                    'post_name' => $page,
                    'post_type' => 'page'
                ));
            }
        }
        
        if (! empty($pageID)) {
            $settings = $userMeta->getData('settings');
            switch ($page) {
                case 'login':
                    $settings['login']['login_page'] = $pageID;
                    $userMeta->updateData('settings', $settings);
                    wp_redirect($userMeta->adminPageUrl('settings', false) . '#um_settings_login');
                    exit();
                    break;
                
                case 'resetpass':
                    $settings['login']['resetpass_page'] = $pageID;
                    $userMeta->updateData('settings', $settings);
                    wp_redirect($userMeta->adminPageUrl('settings', false) . '#um_settings_login');
                    exit();
                    break;
                
                case 'verify-email':
                    $settings['registration']['email_verification_page'] = $pageID;
                    $userMeta->updateData('settings', $settings);
                    wp_redirect($userMeta->adminPageUrl('settings', false) . '#um_settings_registration');
                    exit();
                    break;
            }
        }
        wp_redirect($userMeta->adminPageUrl('settings', false));
        exit();
    }

    function ajaxSaveAdvancedSettings()
    {
        global $userMeta;
        $userMeta->checkAdminReferer(__FUNCTION__);
        
        if (! isset($_REQUEST))
            $userMeta->showError(__('Error occurred while updating', $userMeta->name));
        
        $data = $userMeta->arrayRemoveEmptyValue($_REQUEST);
        $data = $userMeta->removeNonArray($data);
        
        $userMeta->updateData('advanced', stripslashes_deep($data));
        echo $userMeta->showMessage(__('Successfully saved.', $userMeta->name));
        
        die();
    }

    function ajaxTestMethod()
    {
        global $userMeta;
        echo 'Working...';
        $userMeta->dump($_REQUEST);
        die();
    }
}