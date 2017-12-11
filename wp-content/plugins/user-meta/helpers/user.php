<?php
namespace UserMeta;

/**
 * Determing user_id based on user_login or user_email from provided array.
 *
 * @since 1.2
 * @author Khaled Hossain
 *        
 * @param array $userData            
 */
function getUserID(array $userData)
{
    $userID = null;
    if (! empty($userData['user_login']) && empty($userData['user_email'])) {
        $userID = username_exists(trim($userData['user_login']));
        if (! $userID) {
            $userID = email_exists(trim($userData['user_login']));
        }
    } elseif (! empty($userData['user_login'])) {
        $userID = username_exists(trim($userData['user_login']));
    } elseif (! empty($userData['user_email'])) {
        $userID = email_exists(trim($userData['user_email']));
    }
    
    return $userID;
}
