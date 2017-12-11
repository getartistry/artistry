<?php
namespace UserMeta;

/**
 * Handle all reset password processes
 *
 * @since 1.2.1
 *       
 * @author Khaled Hossain
 */
class ResetPassword
{

    /**
     * Handle resetPassword request, key validation, password reset
     */
    function lostPasswordForm($config = [])
    {
        global $userMeta;
        $methodName = "Lostpassword";
        
        $html = null;
        $html .= getHookHtml('login_form_lostpassword');
        if (empty($config))
            $config = $userMeta->getExecutionPageConfig('lostpassword');
        
        $login = $userMeta->getSettings('login');
        if (! empty($login['disable_lostpassword']))
            return $userMeta->showError(__('Password reset is currently not allowed.', $userMeta->name));
        
        $html .= $userMeta->renderPro('lostPasswordForm', array(
            'config' => $config,
            'disableAjax' => ! empty($login['disable_ajax']) ? true : false,
            'methodName' => $methodName
        ), 'login');
        
        return $html;
    }

    function resetPassword($config = [])
    {
        global $userMeta;
        if (empty($config))
            $config = $userMeta->getExecutionPageConfig('resetpass');
        
        $html = null;
        $html .= getHookHtml('login_form_resetpass');
        $html .= getHookHtml('login_form_rp');
        // $user = $userMeta->check_password_reset_key( @$_GET['key'], rawurldecode( @$_GET['login'] ) );
        $user = check_password_reset_key(@$_GET['key'], rawurldecode(@$_GET['login']));
        
        $errors = new \WP_Error();
        if (! is_wp_error($user)) {
            if (isset($_POST['pass1']) && $_POST['pass1'] != $_POST['pass2'])
                $errors->add('password_reset_mismatch', $userMeta->getMsg('password_reset_mismatch'));
            if ($userMeta->isHookEnable('validate_password_reset'))
                do_action('validate_password_reset', $errors, $user);
            if ((! $errors->get_error_code()) && isset($_POST['pass1']) && ! empty($_POST['pass1'])) {
                $userMeta->reset_password($user, $_POST['pass1']);
                do_action('user_meta_after_reset_password', $user);
                $html .= $userMeta->showMessage($userMeta->getMsg('password_reseted'));
                
                $redirect = ! empty($config['redirect']) ? $config['redirect'] : null;
                $redirect = apply_filters('user_meta_reset_password_redirect', $redirect, $user);
                if (! empty($redirect))
                    $html .= $userMeta->jsRedirect($redirect, 5);
                
                return $html;
            }
        } else {
            if ($user->get_error_code() == 'invalid_key')
                return $userMeta->showError($userMeta->getMsg('invalid_key'), false);
            elseif ($user->get_error_code() == 'expired_key')
                return $userMeta->showError($userMeta->getMsg('expired_key'), false);
            else
                return $userMeta->showError($user->get_error_message(), false);
        }
        
        return $userMeta->renderPro('resetPasswordForm', array(
            'config' => $config,
            'user' => $user,
            'errors' => $errors
        ), 'login');
    }

    /**
     * This method will call by POST method
     * Called by umAjaxProModel::postLostpassword()
     *
     * @todo Check if $userMeta->verifyNonce() required
     */
    function postLostPassword()
    {
        global $userMeta;
        
        $settings = $userMeta->getSettings('login');
        if (! empty($settings['resetpass_page'])) {
            $pageID = (int) $settings['resetpass_page'];
            $permalink = get_permalink($pageID);
        }
        
        $output = null;
        
        if ($userMeta->isHookEnable('login_form_retrievepassword')) {
            ob_start();
            do_action('login_form_retrievepassword');
            $output .= ob_get_contents();
            ob_end_clean();
        }
        
        $resetPassLink = ! empty($permalink) ? $permalink : null;
        $response = $userMeta->retrieve_password($resetPassLink);
        
        if ($response === true) {
            $output .= $userMeta->showMessage($userMeta->getMsg('check_email_for_link'), 'success', false);
            $redirect_to = ! empty($_POST['redirect_to']) ? $_POST['redirect_to'] : '';
            
            if ($userMeta->isHookEnable('lostpassword_redirect'))
                $redirect_to = apply_filters('lostpassword_redirect', $redirect_to);
            
            if (! empty($redirect_to))
                $output .= $userMeta->jsRedirect($redirect_to, 5);
        } elseif (is_wp_error($response))
            $output .= $userMeta->showError($response->get_error_message(), false);
        
        return $userMeta->printAjaxOutput($output);
    }
}